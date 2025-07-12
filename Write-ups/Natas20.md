# This is a write-up for Natas - level 20

This level was a tough nut to crack, so don't feel bad for checking out my writeup.

There is a lot of php code and I will not paste it here but merely refer to it as needed.

The first thing to notice is that print_credentials() will give us the password if we manage to set admin key of the SESSION dictionary to 1.

A quick ctrl+f on _SESSION allows us to see where the session data is edited.
During myread(), we see that SESSION is set to an empty array using using 'array()' and in the for loop below that SESSION is filled with data from $line and $line gets is data from $data.
$data comes from $filename which is defined in the function mywrite as  session_save_path() . "/" . "mysess_" . $sid; (. is concatenation)

Accessing the website with ?debug=true shows us that this path looks like /var/lib/php/sessions/mysess_<sid>

So in turn, we want to put something in that file. This can be done with "mywrite()".
Immediately we see a comment saying "our encoding is better". This should make your alarm bells ring. Whenever someone commits an act of hubris, there is usually a repercussion in CTF's.

Unlike the code for myread, $filename is now filled with $data by the php function file_put_contents. What is in $data? Well, it is filled in the for-loop just above with data from the session variable $_SESSION.

$_SESSION is only modified in the code 

```php
<?
if(array_key_exists("name", $_REQUEST)) {
    $_SESSION["name"] = $_REQUEST["name"];
    debug("Name set to " . $_REQUEST["name"]);
}
?>
```

Here is a crucial insight. We can only write to session via the 'name' key from our requests.

But before we write to session, let's ask "How does data go from session to a file?"
Well, in mywrite we see that it happens like this:

```php
<?
foreach($_SESSION as $key => $value) {
        debug("$key => $value");
        $data .= "$key $value\n";
    }
    file_put_contents($filename, $data);
?>
```

Looks harmless, right?

Yes, in principle. However, there are no checks that disallow special characters. And this, we can use!

For you see, in the myread function, data is read like such:

```php
<?
$_SESSION = array();
    foreach(explode("\n", $data) as $line) {
        debug("Read [$line]");
    $parts = explode(" ", $line, 2);
    if($parts[0] != "") $_SESSION[$parts[0]] = $parts[1];
    }
?>
```

What special character appears here?

<details>
    <summary>
        Click to reveal:
    </summary>
    That's right, it's the newline character, '\n'! 
    In the for loop, 'explode' splits the data at every \n and saves it as line.
    Then, explode splits that line at every space (returning at most 2 elements, however. This means that a name of 'admin 1' will return the key value pair name: 'admin 1' in the session.)
    However, a name that contains a newline character will be interpreted as a value for the key 'name' (before \n) and as a key:value pair (after \n). As an example, writing John\nsurname Jackson will make the session data be 'name: John surname: Jackson'

Ok then, set a name like 'asdf\nadmin 1' and be done with it, right?
    Not so fast! Some characters are reserved for special uses in URI's.Take a look at:
    https://en.wikipedia.org/wiki/Percent-encoding#Reserved_characters
    To send a new line we need its ascii code, which is 10 or 0A in hexadecimal. Therefore, a name like 'asdf%0Aadmin%201' would be interpreted as 'asdf\nadmin 1' (%20 is the encoding for ' ', and btw percent-encodings are not case sensitive)
</details>
With this insight, we are actually done, as long as you know what curl request to send.

(Keep in mind you might have to send the request multiple times. First, the data will be written, later it will be read.

I'll save you some trouble and point you in the right direction for curl's manual (man curl)

You will need 
-u (for username+password, i. e. getting access to the site)
-d (for data, means that curl will send a POST request with whatever is in data)
-b (for bcookie, used to send a cookie)

<details>
    <summary>
        My script, in case you need help with curl.
    </summary>
        curl -u natas20:<censored> -d "name=asdf%0Aadmin%201" --cookie "PHPSESSID=adminsesh" http://natas20.natas.labs.overthewire.org/?debug
</details>

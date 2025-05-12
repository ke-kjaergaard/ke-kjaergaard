# This is a write-up for Natas - level 9

```php
<?
$key = "";

if(array_key_exists("needle", $_REQUEST)) {
    $key = $_REQUEST["needle"];
}

if($key != "") {
    passthru("grep -i $key dictionary.txt");
}
?>
```

The php code above has a major security flaw. Can you spot it?


Hint: it has to do with passthru, which you can learn about here.
https://www.php.net/manual/en/function.passthru


To understand the flaw, we need to understand the code above.
As the curious internet monkey will observe, the url changes according to our search input.
It contains ?needle=<...> and inside your browser's inspector (or in the 'View sourcecode') you can see that the text field has <input name="needle">.
In the php code, no matter the request (post, get, etc.) if 'needle' is in that request, 'key' takes the value of needle.
That key is then inserted into passthru, and judging by the word 'grep' we are somehow executing something in a terminal.
https://man7.org/linux/man-pages/man1/grep.1.html

Why is this a security flaw? Well, there are no limitations to what 'key' can be, and we can abuse this.

Below is a list of special characters in BASH, a very common terminal. I'm not sure if BASH is exactly the terminal used on Natas, but for this use-case the special characters are the same.

https://www.oreilly.com/library/view/learning-the-bash/1565923472/ch01s09.html

<details>
    <summary>
        First hint
    </summary>
    We might like to use ; and # to manipulate and insert our own command (grep can get us far, but there are other tools)
</details
<details>
    <summary>
        Second hint:
    </summary>
    pwd and ls are very good command to find your way around. Use 'ls /' to find the root of the system and go from there.
</details
Disclaimer: you could just use grep to find the password if you already know where the password is (take inspiration from previous levels where we accessed /etc/natas_webpass/<filename>)

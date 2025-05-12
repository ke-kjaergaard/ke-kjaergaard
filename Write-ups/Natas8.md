# This is a write-up for Natas - level 8

Viewing the sourcecode, we see the following:

```php 
<?

$encodedSecret = "3d3d516343746d4d6d6c315669563362";

function encodeSecret($secret) {
    return bin2hex(strrev(base64_encode($secret)));
}

if(array_key_exists("submit", $_POST)) {
    if(encodeSecret($_POST['secret']) == $encodedSecret) {
    print "Access granted. The password for natas9 is <censored>";
    } else {
    print "Wrong secret";
    }
}
?>
```

In the big if statement, the 'secret' part of the POST request is being passed through 'encodeSecret', the function, and then compared to encodedSecret.
We can see what encodedSecret is and also what encodeSecret does.
Now we just need to figure out what string 'str' to put in in order to get the equality 'encodeSecret(str)==encodedSecret' (eq. 1)

Luckily, encodeSecret is a composition of functions that are all reversible. You can look them up in the docs:
https://www.php.net/docs.php

Furthermore, we need to recall some algebra in order to solve equation 1:

$encodeSecret(str)==3d3d516343746d4d6d6c315669563362$
$bin2hex(strrev(base64_encode(str)))==3d3d516343746d4d6d6c315669563362$
$strrev(base64_encode(str))==hex2bin(3d3d516343746d4d6d6c315669563362)$
$base64_encode(str)==strrev(hex2bin(3d3d516343746d4d6d6c315669563362))$
$str==base64_decode(strrev(hex2bin(3d3d516343746d4d6d6c315669563362)))$

If you write a script in php, you can actually use this syntax. Otherwise you need to use the appropriate functions from the language you use.

I wrote a script in Python: Try to solve it on your own before checking it out.

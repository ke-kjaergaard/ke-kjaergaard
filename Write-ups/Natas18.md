# This is a write-up for Natas - level 18

After reading the page, I figure it has to do with cookies. Once I inspect them with Firefox "inspect", I find PHPSESSID, which seems promising. It is set to 195, and I try changing it a few times and reloading. 
Nothing happens. However, PHPSESSID is a part of the sourcecode in the function my_session_start. 
After reading through the function, I realize I should try setting debug to true with ?debug=true in the url. When doing that and changing PHPSESSID, I find that every time I change PHPSESSID, I get "session was old: admin flag set". 
This also happens when I switch it back to something it was earlier (e. g. 4->5 reload, "admin flag set", 5->5 reload, no message, 5->4 reload, "admin flag set". This means that the array key 'admin' does not exist yet when I change the PHPSESSID, even when setting it back to an old value.

Changing the ID to a string shows me a form, which I find in the code as the variable $showform - this variable is sometimes true, depending on whether you just logged in or not. If you didn't just log in, the form shows. 
When you log in (thus passing a request with the array keys "username" and "password"), the following code is executed: 'session_id(createID($_REQUEST["username"]));'
I look at createID and realize it is a random function that returns a number between 1 and 640 with a comment saying that 640 'should be enough for all users'. I realize that this can be exploited by using a script that runs through all possible ID's. 

Why is that so? Well, the PHPSESSID is set to some number from the last login by the admin, and when the server reads that ID, it fetches the session data where $_SESSION["admin"] is TRUE (or 1), and when the function print_credentials() runs it will print a different message from when $_SESSION["admin"] is FALSE. 

Knowing the success message, the script can search for that message while looping through all possible session ID's and then stop when it finds the one that corresponds to the admin session.

The below script kind of explains itself. It uses the requests library, which you can find documentation for here: https://requests.readthedocs.io/en/latest/

-- Start of python code --

import requests
from requests.auth import HTTPBasicAuth

basicAuth=HTTPBasicAuth('natas18', '<secret-password>')
headers = {'Content-Type': 'application/x-www-form-urlencoded'}

u="http://natas18.natas.labs.overthewire.org/index.php"

for i in range(1,641):
    payload = {"PHPSESSID": str(i)}
    # This line stores the response from the server
    response = requests.get(u, cookies=payload, headers=headers, auth=basicAuth, verify=False)
    print(i)
    if "You are an admin" in response.text:
        print(response.text)
        print("the admins session id was:", i)
        break

-- End of python code -- 

(when I ran it, the code stopped at 119. Just for fun I opened firefox, set the cookie PHPSESSID to 119 and reloaded, and found the credentials for the next level)




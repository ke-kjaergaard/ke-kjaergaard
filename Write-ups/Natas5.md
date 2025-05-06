# This is a write-up for Natas - level 5

The last CTF introduced the concept of using curl to send web requests.
This CTF introduces cookies!

You may have heard of cookies before - either way, you can read about them here:
https://en.wikipedia.org/wiki/HTTP\_cookie

Anyway.
On the Natas5 site, we are told that we are not logged in.
Information about the user that accesses the website (you, in this example) are often stored in cookies.
How do we read cookies, and can we change them?

To find the cookies, press the inspect button.
Here you will find menus like Inspector, Network, and Storage.
On firefox, cookies are located in Storage. Depending on your browser you might have to dig around a little, but you'll get there eventually!

You should see a cookie called 'loggedin' which is set to 0. You can change its value by double-clicking the field, typing something and then clicking somewhere else.

As with the last write-up, I'm going to let you solve the rest on your own. 

PS: If you're really stuck, look up boolean values ;)

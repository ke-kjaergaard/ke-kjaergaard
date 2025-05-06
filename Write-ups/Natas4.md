# This is a write-up for Natas - level 4

This is the first real "hacker" CTF where we won't just do stuff in the browser.
It is time to introduce cURL!
cURL is a tool that let's you send web requests via your command line and let you specify a lot of details in that request.
It does a ton of different things, but don't get overwhelmed - I'll tell you what you need to know for this CTF:

When you enter the page, it tells you where you came from and that you can only visit if you came from Natas 5. Of course, you can't get there yet, so you have to make a work-around.
If you think it is magical that a browser knows where you came from, then you're just like me! Go and read this Quora post and you will understand the basics.
https://www.quora.com/Can-a-website-I-visit-see-what-webpage-I-came-from

That page won't tell you how to manipulate a website into thinking you came from somewhere specific.
But cURL can!

In the man page for curl, it tells you that -e (or --referer) will let you send "referrer page" information to the http server.
So the first part of our request should be

curl --referer http://natas5.natas.labs.overthewire.org \<more stuff...\>

This tells whatever website we plug into "more stuff" that we came from the natas5 site - brilliant!

Now, you might think it would be enough to plug in the website for natas4 in the more stuff like such:

curl --referer http://natas5.natas.labs.overthewire.org/ http://natas4.natas.labs.overthewire.org/

However, we won't get access to it since there is a username+password lock on the site.

I will let you solve the rest of this on your own with the hint that you should use the command option -u in your curl command.
To read more about -u, go to man curl and search with "/-u" and navigate the results with (n)ext or (Shift+n) previous.

Good luck!

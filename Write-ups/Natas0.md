# This is a write-up for Natas - level 0
## I am using firefox as my browser and Linux Mint XFCE as my OS - your setup may affect your solution

I am greeted with a page containing only the text "You can find the password for the next level on this page."

Intuitively, I click in different places to try to find a hidden button or something, but with no luck.

The second-most obvious idea is to right click and see my options. 
I find the "view page source" button, which shows me the html file that the browser fetches.
Why would that be important? Shouldn't all the content of a website be displayed by the browser?

Yes, but developers may wish to write comments to each other that are not intended for the end user.
These comments are not displayed by the browser, but they are readable in the html file.
In this case, the "developers" were careless enough to leave sensitive information in the file as a comment:

<div id="content">
  You can find the password for the next level on this page.

  <!--The password for natas1 is 0nzCigAq7t2iALyvU9xcHlYN4MlkIwlq -->
</div>

Viewing the page source (or pressing inspect, a more diverse tool) will be important for the next levels.

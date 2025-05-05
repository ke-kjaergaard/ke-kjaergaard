# This is a write-up for Natas - level 3

I am greeted with "There is nothing on this page" just like last time.
Again, I try inspecting the page, but I only find the comment:

"No more information leaks!! Not even Google will find it this time..."

This comment is a reference to something called a web crawler, which Google - among others - uses for their search engine.
If you don't know about web crawlers, read about them here:

https://en.wikipedia.org/wiki/Web_crawler
https://en.wikipedia.org/wiki/Robots.txt

Inside robots.txt, the web developer can put rules for search engines to obey when they fetch information from a site.
When the comment says that even Google won't find it this time, it is probably something to do with those rules inside robots.txt.
(Link to follow:) http://natas3.natas.labs.overthewire.org/robots.txt
In the above link, we are told that all user agents are not allowed to look in s3cr3t - so that's where we'll look!

Inside s3cr3t we find a .txt file with the password for level 4; hurray!

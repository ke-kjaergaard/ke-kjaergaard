# This is a write-up for Natas - level 6

The site tells us to input a secret. 
This could be anything, so let's try some of the usual ones (admin, password, secret, 12345...)

None of them work, but there is a 'View sourcecode' button, which is a recurring theme in Natas.
In here we find a lot of html and some php code.
The html is located within <html> tags, and the php is to be found in <? ?> tags.

Don't worry if you don't know these languages. If you know english and pseudocode, you should be able to grasp the php code (and you can always google keywords).

The important bit in the code is the $secret variable. 
In the form tags we see that <input name=secret>, so whatever we put in the text field will be known to the program as 'secret'.
I like to compare it to python dictionaries. In this case, $_POST is a dictionary with data about the post request, and the 'secret' key contains the value that we plug into the text field.

We want to make a post request where $_POST['secret'] (our input) equals the variable $secret (which is hidden to us)

We can see, however, that the php code includes 'includes/secret.inc'

This notation suggests that the website has a folder structure, and this line indicates that 'includes' is a folder wherein 'secret.inc' is file.

We try appending '/includes/' to the url, but we don't have access - this is a good sign that our hypothesis on the folder structure is correct.

Let's try appending '/includes/secret.inc' - uh oh, it's empty :(

However, it _is_ a page! How can I say that? Well, appending something else like '/includes/<somethingweird>' gives 'Not Found'. Since '/includes/secret.inc' doesn't give that error, it must exist.

I'll let you solve the rest. 
Hint: the page '/includes/secret.inc' looks empty but is not actually empty.

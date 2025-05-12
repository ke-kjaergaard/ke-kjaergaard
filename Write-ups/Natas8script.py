import base64

secretstr = '3d3d516343746d4d6d6c315669563362'

binarystr = bytes.fromhex(secretstr)
print('String after decoding the hex', binarystr)
binarystr = binarystr[::-1]
print('String after reversing', binarystr)
finalstr = base64.b64decode(binarystr)
print('Final string, now base64 decoded', finalstr)

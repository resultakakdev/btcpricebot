# btcpricebot
price bot that works with slack api

configuration: 
1) upload the php files to a directory on your web server
2) create an outgoing webhook in slack
	- configure webhook to look for "troll,price" as the trigger words
	- name the bot whatever, most of the time the name is overridden
3) modify bot.php and add in your token given in step 2

usage:
troll - a familiar but unwelcome face
'price <exchange>' to get latest quote, <exchange> can equal: bfx, bs, okc, btce
'price !' to get help
'price all' to print out all exchange quotes
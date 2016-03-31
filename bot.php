<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("functions.php");
require("exchange.php");
	$excDataArray = ""; // contains high, low, bid, ask, volume, currency, exchange (no last price - thanks Bitstamp!
	setSlackToken("");
	setSlackMessage($_POST);
	setExchangeConfig();
	date_default_timezone_set('UTC');
// commands: !troll, !price, convert
// parameters: bfx, bs, btce

	$findspace   = ' ';
	$cmd = substr(getSlackMessageText(), 0, 6); // the command is 5 characters long and always at the beginning of the line.
	$everythingElse = substr(getSlackMessageText(), 8);
	$cmd = strtolower($cmd);
if($cmd == '!price'){
	$botname = "BTC Price Bot";
	$pos = strpos(getSlackMessageText(), $findspace);
	$exc = trim(substr(getSlackMessageText(), $pos));
	$exc = strtolower($exc);
	if($exc == 'gox' || $exc == 'mtgox'){
		$botname = "MtGox Price Bot";
		$text = "Seriously? You're seriously going to try and check the price on an exchange that went bankrupt? How do I even quote that... ? Try 'troll' instead";
		// technically, last price at gox was 135.69
		$arr = array ('text'=>$text,'username' => $botname);
		// clearly the user is trolling us, so we end the script
    	echo json_encode($arr);
		exit(0);
	} else if($exc == '!'){
		$text= "Commands are case sensitive. Use 'price <exchange>' format where <exchange> is bfx, bs, btce, okc. Use 'all' to get data from all exchanges in one response";
	} else if($exc == 'all'){
		$excDataArray = getLatest('btce');
		$text = formatOutput($excDataArray, "");
		
		$excDataArray = getLatest('bfx');
		$text .= formatOutput($excDataArray, "");
		
		$excDataArray = getLatest('bs');
		$text .= formatOutput($excDataArray, "");
		
		$excDataArray = getLatest('okc');
		$text .= formatOutput($excDataArray, "");
		
	} else {
		$excDataArray = getLatest('btce');
		$text = formatOutput($excDataArray, "");
		
		$excDataArray = getLatest('bfx');
		$text .= formatOutput($excDataArray, "");
		
		$excDataArray = getLatest('bs');
		$text .= formatOutput($excDataArray, "");
		
		$excDataArray = getLatest('okc');
		$text .= formatOutput($excDataArray, "");
	}
	
} else if($cmd == '!troll'){
	$botname = "Mark K. - Professional Goxxer";
	$randValue = mt_rand(0, 23);
	$text = "";
$username = getSlackMessageUserName();
	switch($randValue){
		case 1: $text = "Hey ".$username."... prepare yourself for a proper GOXXING! Frappuccinos for me!"; break;
		case 2: $text = "Dude, where's my keys ".$username."?"; break;
		case 2: $text = "Fuck off! I'm tryna get some slackbot ass ".$username."!"; break;
		case 4: $text = $username."... how's that MOON treating ya?"; break;
		case 5: $text = "I don't like that ".$username." guy... he's always trolling when he speaks"; break;
		case 6: $text = "Oh, what's this? 200K BTC in my sock?!"; break;
		case 7: $text = "Rubbing that salt in your wound since I could drink frappuccinos."; break;
		case 8: $text = "BTC landed on the moon and my fatass somehow shat out 200K BTC."; break;
		case 9: $text = "Y U NO drink Frappuccino, ".$username."?"; break;
		case 10: $text = "I was goxxed, ".$username.". I now drink frappuccinos out of dirty assholes. I call them assuccinos."; break;
		case 11: $text = "My exchange tanks while shady ass BTC-e still thrives, and they're the criminals?"; break;
		case 12: $text = $username." put your only copy of private keys on a USB and format it bro"; break;
		case 13: $text = $username." one does not simply avoid frappuccino before coding session"; break;
		case 14: $text = $username.", you should check to see if your cold storage is leaking."; break;
		case 15: $text = "Waves moon at ".$username." Never gonna get this!"; break;
		case 16: $text = $username.", are you on windows? Try ALT+F4 to collect your lost coins!"; break;
		case 17: $text = $username.", buy high; sell low. Strategy so bulletproof it can't be goxxed."; break;
		case 18: $text = ":fu:".$username.", :fu: hard."; break;
		case 19: $text = $username." make it digitally hail on those analog hoes."; break;
		case 20: $text = "Hey ".$username.", ever been goxxed?"; break;
		case 21: $text = "Choo choo, ".$username.". Can't go to the moon without my willy"; break;
		case 22: $text = "_Sets root password to 'mtgox'_ ".$username.", my exchange cannot be haxxed now"; break;
		case 23: $text = "Frappuccino (noun): A drink known by the world for being consumed by the system administrator of the largest bitcoin exchange on the planet (that's me, bitches). I spilled it on the keyboard of the laptop containing private keys, effectively reducing the bitcoin supply by 7%. This drink may be the sole reason people were goxxed."; break;
		default: $text = "_Nelson_ ha ha (points at ".$username.")"; break;
	}
} else if($cmd == 'conver'){
	$botname = "Price Conversion Bot";
	$text = "";
	$from = strtoupper(substr($everythingElse, 0, 3));
	$to = "";
	$number = substr($everythingElse, 4);
	if($from != "CNY" && $from != "USD"){
		$text = "Use it like this: convert <CNY-or-USD> <value>. Only CNY and USD supported.";
	} else {
		//$text = $everythingElse;
		// get price information
		$url = "http://api.fixer.io/latest?base=".$from;
		$data = file_get_contents($url);
		$json = json_decode($data,true);
		if($from == "CNY"){ $to = "USD"; } else { $to = "CNY"; }
		$rate = $json['rates'][$to];
		$conversionPrice = $rate*$number;
		$text = "At conversion rate of ".$rate." ".$to."/".$from.", the price is ".$conversionPrice." ".$to;
	}
} else if($cmd == '!bfxlo'){
	$botname = "BFX Spot Price Calculator";
	$text = "";
	$daysUntil = 0;
	$nextQtrly = "";
	$time = time(); 
	$currentDate = getdate();
	$year = $currentDate['year'];

	if($time > get_date(12, $year, 1, 5, -1)){ // today is after last quarterly close
		$year = $year+1;
	}

	if($time > get_date(12, $year-1, 1, 5, -1) && $time < get_date(3, $year, 1, 5, -1)) {
		$daysUntil = days_until(date(DATE_RFC850,get_date(3, $year, 1, 5, -1)));
		$nextQtrly = date(DATE_RFC850,get_date(3, $year, 1, 5, -1));
	}

	if($time > get_date(3, $year, 1, 5, -1) && $time < get_date(6, $year, 1, 5, -1)) {
		$daysUntil = days_until(date(DATE_RFC850,get_date(6, $year, 1, 5, -1)));
		$nextQtrly = date(DATE_RFC850,get_date(6, $year, 1, 5, -1));
	}

	if($time > get_date(6, $year, 1, 5, -1) && $time < get_date(9, $year, 1, 5, -1)) {
		$daysUntil = days_until(date(DATE_RFC850,get_date(9, $year, 1, 5, -1)));
		$nextQtrly = date(DATE_RFC850,get_date(9, $year, 1, 5, -1));
	}

	if($time > get_date(9, $year, 1, 5, -1) && $time < get_date(12, $year, 1, 5, -1)) {
		$daysUntil = days_until(date(DATE_RFC850,get_date(12, $year, 1, 5, -1)));
		$nextQtrly = date(DATE_RFC850,get_date(12, $year, 1, 5, -1));
	}
	
	$excDataArray = getLatest('bfx');
	$newPrice = (1 + (0.0006 * $daysUntil)) * floatval($excDataArray["last"]);
	$text = "BFX Last: ".$excDataArray["last"].", Next Qtrly: ".$nextQtrly.", Days Til that date: ".$daysUntil.", Calculated: ".$newPrice;
} else {
	$text = "Some type of error occured. DEBUG: ".$cmd." length cmd = ".strlen($cmd);
	$botname = "ERROR";
}
    // and now we package the return response we've selected above in the slack API format and encode it to json.
    $arr = array ('text'=>$text,'username' => $botname);

    echo json_encode($arr);
	exit(0)
?>

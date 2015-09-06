<?php
$btce_config = "";
$btce_latest = "";

$bfx_config = "";
$bfx_latest = "";

$stamp_config = "";
$stamp_latest = "";

$okc_config = "";
$okc_latest = "";

$huobi_config = "";
$huobi_latest = "";

/* exchange API setup */
function setExchangeConfig(){
	global $btce_config;
	global $bfx_config;
	global $stamp_config;
	global $okc_config;
	global $huobi_config;
	
	$btce_config = array('botname'=> "BTC-e Price Bot",
		'apiUrl'=>'https://btc-e.com/api/3/ticker/btc_usd');
	$bfx_config = array('botname'=> "BFX Price Bot",
		'apiUrl'=>'https://api.bitfinex.com/v1/pubticker/btcusd');
	$stamp_config = array('botname'=> "Bitstamp Price Bot",
		'apiUrl'=>'https://www.bitstamp.net/api/ticker/');
	$okc_config = array('botname'=> "OKCoin Price Bot",
		'apiUrl'=>'https://www.okcoin.com/api/v1/ticker.do?symbol=btc_usd');
	$huobi_config = array('botname'=> "BFX Price Bot",
		'apiUrl'=>'https://api.bitfinex.com/v1/pubticker/btcusd');
}

function getExchangeConfig($exchange){
	global $btce_config;
	global $bfx_config;
	global $stamp_config;
	global $okc_config;
	global $huobi_config;
	
	$config = null;
	if($exchange == 'bfx') {
		$config = $bfx_config;
	} else if($exchange == 'btce'){
		$config = $btce_config;
	} else if($exchange == 'bs'){
		$config = $stamp_config;
	} else if($exchange == 'okc'){
		$config = $okc_config;
	} else {
		// gox lol
	}
	
	return $config;
}

function getExchangeBotname($exchange){
	global $btce_config;
	global $bfx_config;
	global $stamp_config;
	global $okc_config;
	global $huobi_config;
	
	$name = null;
	if($exchange == 'bfx') {
		$name = $bfx_config["botname"];
	} else if($exchange == 'btce'){
		$name = $btce_config["botname"];
	} else if($exchange == 'bs'){
		$name = $stamp_config["botname"];
	} else if($exchange == 'okc'){
		$name = $okc_config["botname"];
	} else {
		// gox lol
	}
	
	return $name;
}

function setLatestBTCE(){ 
	global $btce_latest;
	$config = getExchangeConfig("btce");
	$data = file_get_contents($config["apiUrl"]);
	$json = json_decode($data,true);
	$btce_latest = array('last'=>$json['btc_usd']['last'],
		'high'=>$json['btc_usd']['high'],
		'low'=>$json['btc_usd']['low'],
		'bid'=>$json['btc_usd']['buy'],
		'ask'=>$json['btc_usd']['sell'],
		'volume'=>$json['btc_usd']['vol_cur'],
		'currency'=>'USD',
		'exchange'=>'BTC-e');
	//lost data: vol (usd)
	// USD V: '.$json['btc_usd']['vol'];
}

function setLatestBFX(){//high, low, bid, ask, volume
	global $bfx_latest;
	$config = getExchangeConfig("bfx");
	$data = file_get_contents($config["apiUrl"]);
	$json = json_decode($data,true);
	$bfx_latest = array('last'=>$json['last_price'],
		'high'=>$json['high'],
		'low'=>$json['low'],
		'bid'=>$json['bid'],
		'ask'=>$json['ask'],
		'volume'=>$json['volume'],
		'currency'=>'USD',
		'exchange'=>'BitFinex');
	//lost data: mid
	//'Mid: '.$json['mid']
}

function setLatestSTAMP(){
	global $stamp_latest;
	$config = getExchangeConfig("bs");
	$data = file_get_contents($config["apiUrl"]);
	$json = json_decode($data,true);
	$stamp_latest = array('last'=>$json['last'],
		'high'=>$json['high'],
		'low'=>$json['low'],
		'bid'=>$json['bid'],
		'ask'=>$json['ask'],
		'volume'=>$json['volume'],
		'currency'=>'USD',
		'exchange'=>'Bitstamp');
	//lost data: vwap
	//' VWAP: '.$json['vwap'];
}

function setLatestOKCOIN(){
	global $okc_latest;
	$config = getExchangeConfig("okc");
	$data = file_get_contents($config["apiUrl"]);
	$json = json_decode($data,true);
	$okc_latest = array('last'=>$json['ticker']['last'],
		'high'=>$json['ticker']['high'],
		'low'=>$json['ticker']['low'],
		'bid'=>$json['ticker']['buy'],
		'ask'=>$json['ticker']['sell'],
		'volume'=>$json['ticker']['vol'],
		'currency'=>'USD',
		'exchange'=>'OKCoin');
	// lost data: date
	// $json['date']
}

function setLatest($exchange){
	if($exchange == 'bfx') {
		setLatestBFX();
	} else if($exchange == 'btce'){
		setLatestBTCE();
	} else if($exchange == 'bs'){
		setLatestSTAMP();
	} else if($exchange == 'okc'){
		setLatestOKCOIN();
	}else {
		// gox lol
	}
}

function getLatest($exchange){
	global $btce_latest;
	global $bfx_latest;
	global $stamp_latest;
	global $okc_latest;
	global $huobi_latest;
	setLatest($exchange);
	$latest = null;
	if($exchange == 'bfx') {
		$latest = $bfx_latest;
	} else if($exchange == 'btce'){
		$latest = $btce_latest;
	} else if($exchange == 'bs'){
		$latest = $stamp_latest;
	} else if($exchange == 'okc'){
		$latest = $okc_latest;
	} else {
		// gox lol
	}
	
	return $latest;
}

?>
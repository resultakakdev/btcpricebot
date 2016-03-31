<?php
// global vars, can be accessed directly but preferably not
$slack_message;
$token = "";
$slackToken = "";

/* TOKEN VALIDATION */
function setSlackToken($tokenValue){
	global $slackToken;
	$slackToken = $tokenValue;
}

function getSlackToken(){
	global $slackToken;
	return $slackToken;
}

function setToken($tokenValue){
	global $token;
	$token = $tokenValue;
}

function getToken(){
	global $token;
	return $token;
}

function checkToken(){
	if(getSlackToken() != getToken()){
		exit(0);
	}
}

/* slack message manipulation */

function setSlackMessage(){
	global $slack_message;
	$slack_message = array('token'=>htmlspecialchars($_POST["token"]),
	'team_id' => htmlspecialchars($_POST["team_id"]),
	'team_domain'=>htmlspecialchars($_POST["team_domain"]),
	'channel_id'=>htmlspecialchars($_POST["channel_id"]),
	'channel_name'=>htmlspecialchars($_POST["channel_name"]),
	'timestamp'=>htmlspecialchars($_POST["timestamp"]),
	'user_id'=>htmlspecialchars($_POST["user_id"]),
	'user_name'=>htmlspecialchars($_POST["user_name"]),
	'text'=>htmlspecialchars($_POST["text"]),
	'trigger_word'=>htmlspecialchars($_POST["trigger_word"]));
	setToken($slack_message["token"]);
	//checkToken();
}

function setSlackMessageF(){
	global $slack_message;
	$slack_message = array('token'=>htmlspecialchars($_GET["token"]),
	'team_id' => htmlspecialchars($_GET["team_id"]),
	'team_domain'=>htmlspecialchars($_GET["team_domain"]),
	'channel_id'=>htmlspecialchars($_GET["channel_id"]),
	'channel_name'=>htmlspecialchars($_GET["channel_name"]),
	'timestamp'=>htmlspecialchars($_GET["timestamp"]),
	'user_id'=>htmlspecialchars($_GET["user_id"]),
	'user_name'=>htmlspecialchars($_GET["user_name"]),
	'text'=>htmlspecialchars($_GET["text"]),
	'trigger_word'=>htmlspecialchars($_GET["trigger_word"]));
	setToken($slack_message["token"]);
	//checkToken();
}

function getSlackMessageToken(){
	global $slack_message;
	return $slack_message['token'];
}

function getSlackMessageArray(){
	global $slack_message;
	return $slack_message;
}

function getSlackMessageUserName(){
	global $slack_message;
	return $slack_message['user_name'];
}

function getSlackMessageText(){
	global $slack_message;
	return $slack_message['text'];
}

/* general functions */

function formatOutput($array, $exchange){
	$text = "";
	if($exchange == ""){
		$text = $array["exchange"] . ":" . 
			" *Last:* " . $array["last"] .
			" *High:* " . $array["high"] .
			" *Low:* " . $array["low"] .
			" *Bid:* " . $array["bid"] .
			" *Ask:* " . $array["ask"] .
			" *Volume:* " . $array["volume"] . " BTC." .
			" All Prices in " . $array["currency"] .
			"\n";
	} else {
		$text = " *Last:* " . $array["last"] .
			" *High:* " . $array["high"] .
			" *Low:* " . $array["low"] .
			" *Bid:* " . $array["bid"] .
			" *Ask:* " . $array["ask"] .
			" *Volume:* " . $array["volume"] . " BTC." .
			" All Prices in " . $array["currency"] .
			"\n";
	}
	
	return $text;
}

function get_date($month, $year, $week, $day, $direction) {
  if($direction > 0)
    $startday = 1;
  else
    $startday = date('t', mktime(0, 0, 0, $month, 1, $year));

  $start = mktime(0, 0, 0, $month, $startday, $year);
  $weekday = date('N', $start);

  if($direction * $day >= $direction * $weekday)
    $offset = -$direction * 7;
  else
    $offset = 0;

  $offset += $direction * ($week * 7) + ($day - $weekday);
  return mktime(0, 0, 0, $month, $startday + $offset, $year);
}

function days_until($date){
    return (isset($date)) ? floor((strtotime($date) - time())/60/60/24) : FALSE;
}
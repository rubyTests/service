<?php
// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
require __DIR__ . '/Twilio/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;
 function smsSend($phone,$msg){
	// Your Account SID and Auth Token from twilio.com/console
	$sid = 'AC2f798e216bfce03c03fe4c7822bad992';
	$token = '8658df34b0e8b0cea534942443d9be0e';
	$client = new Client($sid, $token);

	// Use the client to do fun stuff like send text messages!
	$client->messages->create(
		// the number you'd like to send the message to
		'+91'.$phone,
		array(
			// A Twilio phone number you purchased at twilio.com/console
			'from' => '+14248887814',
			// the body of the text message you'd like to send
			'body' => $msg
		)
	);
 }
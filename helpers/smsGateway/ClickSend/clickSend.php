<?php

require 'vendor/autoload.php';

function clickSend($phone,$msg){
	
	try {
		// Prepare ClickSend client.
		$client = new \ClickSendLib\ClickSendClient('Ruby', '5C043826-09BB-0D0B-AE12-CFC0B5B7AEB8');

		// Get SMS instance.
		$sms = $client->getSMS();

		// The payload.
		$messages =  [
			[
				"source" => "php",
				"from" => "sendmobile",
				"body" => $msg,
				"to" => "+91".$phone,
			]
		];

		// Send SMS.
		$response = $sms->sendSms(['messages' => $messages]);

		//print_r($response);

	} catch(\ClickSendLib\APIException $e) {

	   // print_r($e->getResponseBody());

	}
}

#END OF PHP FILE
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

function bulkSend($phone,$msg){
	
	try {
		// Prepare ClickSend client.
		// $client = new \ClickSendLib\ClickSendClient('Ruby', '5C043826-09BB-0D0B-AE12-CFC0B5B7AEB8');
		$client = new \ClickSendLib\ClickSendClient('abdul', 'BAD59387-00AE-F814-1FC3-E58281653394');

		// Get SMS instance.
		$sms = $client->getSMS();

		// The payload.
		$bulk=[];
		foreach($phone as $value){
			$messages = array(
				"source" => "php",
				"from" => "sendmobile",
				"body" => $msg,
				"to" => "+91".$value['PHONE_NO_1'],
			);
			array_push($bulk,$messages);
		}
		
		// $messages =  [
			// [
				// "source" => "php",
				// "from" => "sendmobile",
				// "body" => "Test Send",
				// "to" => "+91 9952322349",
			// ]
		// ];
		
		// print_r($bulk);exit;
		// Send SMS.
		return $response = $sms->sendSms(['messages' => $bulk]);
		// return $response = $sms->sendSms(['messages' => $messages]);

		//print_r($response);

	} catch(\ClickSendLib\APIException $e) {

	   // print_r($e->getResponseBody());

	}
}

#END OF PHP FILE
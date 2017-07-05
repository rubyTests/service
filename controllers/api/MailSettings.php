<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/helpers/mailGun/test.php';
class MailSettings extends CI_Controller {    
    function MailSettings()
    {
		parent::__construct();
		$this->load->model('GeneralMod');
		header("Access-Control-Allow-Origin: *");
		$this->load->library('Curl');
    }
   
	// Send Email via MailGun
	
	function sendMail(){
		//$to=$this->get('mailId');
		$to='karthik@appnlogic.com';
		$result=mailGun($to);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Email sending failed'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	
	// Send SMS via Way2Sms
	
	function smsGateway(){
		// $uid=$this->get('user');
		// $pwd=$this->get('pwd');
		$phone=$this->get('phone');
		$msg=$this->get('msg');
		$users=$this->GeneralMod->sendWay2SMS($phone);
		if ($users==true){
			$this->set_response(['status' =>TRUE,'message'=>'sms send successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'sms could not be send'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	
}
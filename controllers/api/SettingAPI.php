<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
require APPPATH . '/helpers/smsGateway/ClickSend/clickSend.php';
class SettingAPI extends REST_Controller {    
    function SettingAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('setting_model');
		$this->load->library('email');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }

    // Marital List
    function maritalstatus_get(){
    	$result=$this->setting_model->getmaritalDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Status Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }

    // Nationality List
    function Nationality_get(){
    	$result=$this->setting_model->fetchNationalityList();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Nationality Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }

    // Employee List
    function employeeList_get(){
    	$result=$this->setting_model->fetchEmployeeList();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }

    function mailSend_post()
	{
		// print_r($this->post('email'));exit;
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'manisrikan@gmail.com', // change it to yours
			'smtp_pass' => 'mani16121993', // change it to yours
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		);

		$result=$this->setting_model->emailSendList();

		// $result=['vijayaraj@appnlogic.com','manivannan@appnlogic.com'];
		
		for ($i=0; $i < count($result); $i++) { 
			$this->email->initialize($config);
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from('rvijayaraj24@gmail.com'); // change it to yours
			$this->email->to($this->post('email'));
			$this->email->subject($this->post('user'));
			$this->email->message($this->post('subject'));
			if (!$this->email->send())
			{
				show_error($this->email->print_debugger());
			}
			else{
				echo 'Mail Sended Successfully';
			}
			$this->email->clear(TRUE);
		}
		
	}
	
	// Bulk sms sending
	function bulkSms_post(){
		$data['courseId']=$this->post('courseId');
		$data['batchId']=$this->post('batchId');
		// print_r($data['batchId']);exit;
		$data['msg']=$this->post('msg');
		$result=$this->setting_model->bulkSmsSent($data);
		if (!empty($result)){
			print_r($result);exit;
			$bulk=bulkSend($result,$data['msg']);
			$valu=json_decode(json_encode($bulk),true);	
			if($valu['response_code']=='SUCCESS'){
				$result=$this->setting_model->bulkSmsSentDetials($valu,$data);
				if($result=true){
					$this->set_response(['status' =>TRUE,'message'=>'SMS send successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				}else{
					$this->set_response(['status' => FALSE,'message' => 'SMS could not be sent'], REST_Controller::HTTP_OK);
				}
			}
		}
		else{
			$this->set_response(['status' => FALSE,'message' => 'SMS could not be sent'], REST_Controller::HTTP_OK);
		}
	}
	
	function bulkSms_get(){
		$result=$this->setting_model->getBulkSmsDetails();
		if($result){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}else{
			$this->set_response(['status' => FALSE,'message' => 'Data could not be found'], REST_Controller::HTTP_OK);
		}
	}
}
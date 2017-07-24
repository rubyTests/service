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
	
	// Bulk sms sending
	function bulkSms_post(){
		$data['courseId']=$this->post('courseId');
		$data['batchId']=$this->post('batchId');
		$data['msg']=$this->post('msg');
		$result=$this->setting_model->bulkSmsSent($data);
		if (!empty($result)){
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
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
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
}
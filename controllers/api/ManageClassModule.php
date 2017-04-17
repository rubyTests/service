<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class ManageClassModule extends REST_Controller {    
    function ManageClassModule()
    {
		parent::__construct();
		$this->load->model('classmodel');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
		// if (config_item('rest_enable_keys') AND $use_key)
		// {
		// 	// Check to see if they can access the controller
		// 	if (!$this->_check_access())
		// 	{
		// 		$this->response(array('status' => false, 'error' => 'This API key does not have access to the requested controller.'), 401);
		// 	}

		// 	if (config_item('rest_enable_logging') AND $log_method)
		// 	{
		// 		$this->_log_request();
		// 	}
		// 	if($this->_allow === FALSE){
		// 		$this->response(array('status' => false, 'error' => 'Invalid API Key.'), 403);
		// 	}

		// }
		
    }

    // Acodemics Class Details 
	
    function ClassAndBatchDetail_post()
    {
    	$id = $this->post('ACA_COU_ID');
		$data['ACA_COU_NAME']=$this->post('ACA_COU_NAME');
		$data['ACA_COU_SEC_NAME']=$this->post('ACA_COU_SEC_NAME');
		$data['ACA_COU_CODE']=$this->post('ACA_COU_CODE');
		$data['ACA_COU_GRADE_TYPE']=$this->post('ACA_COU_GRADE_TYPE');
		$data['ACA_COU_ELECTIVE_SEL_YN']=$this->post('ACA_COU_ELECTIVE_SEL_YN');
		$data['ACA_BAT_NAME']=$this->post('ACA_BAT_NAME');
		$data['ACA_COU_CRT_USER_ID']=$this->post('ACA_COU_CRT_USER_ID');
		$data['ACA_BAT_START_DT']=$this->post('ACA_BAT_START_DT');
		$data['ACA_BAT_END_DT']=$this->post('ACA_BAT_END_DT');
		$data['ACA_BAT_CRT_USER_ID']=$this->post('ACA_COU_CRT_USER_ID');
		$data['ACA_COU_UPD_USER_ID']=$this->post('ACA_COU_UPD_USER_ID');
		if($id==NULL){
			$result=$this->classmodel->addClassAndBatchDetails($data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'ACA_COU_ID'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->classmodel->EditClassDetails($id,$data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>"Class Updated successfully"], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
	
	function ClassAndBatchDetail_get(){
		$id = $this->get('ACA_COU_ID');
		if($id==NULL){
			$users=$this->classmodel->getClassAndBatchDetailsAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Class And Batch Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$users=$this->classmodel->getClassAndBatchDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Class And Batch Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
		
	function ClassDetail_get(){
		$id = $this->get('ACA_COU_ID');
		if($id==NULL){
			$users=$this->classmodel->getClassDetailsAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Class Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}else{
			$users=$this->classmodel->getClassDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Class Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function ClassDetail_delete(){
		$id = $this->delete('ACA_COU_ID');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Class Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->classmodel->deleteClassDetail($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Class Detail Deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Class Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	

	//Get course
	
	function getCourseDetail_get(){
		$users=$this->classmodel->getClassDetailsAll();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Class Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	function courseViewDetails_get(){
		$result=$this->classmodel->getCourseViewDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Class Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	function batchview_get(){
		$result=$this->classmodel->getBatchViewDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Class Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
}
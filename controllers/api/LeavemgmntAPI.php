<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class LeavemgmntAPI extends REST_Controller {
    function LeavemgmntAPI()
    {
		parent::__construct();
		$this->load->model('leave_mgmnt_model');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }

    // Leave category
	
	function leaveCategory_post(){
		$id = $this->post('id');
		$data['cat_name']=$this->post('cat_name');
		$data['cat_code']=$this->post('cat_code');
		if($id==NULL){
			$result=$this->leave_mgmnt_model->addleaveCategoryDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->leave_mgmnt_model->editLeaveCategoryDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function leaveCategory_get(){
		$id = $this->get('id');
		if($id==NULL){
			$users=$this->leave_mgmnt_model->fetchEmployeeCategoryDetails();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'data'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
	
	function leaveCategory_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->leave_mgmnt_model->deleteLeaveCategoryDetails($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Category Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
}
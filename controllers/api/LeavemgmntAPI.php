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

    // Leave Type
	
	function leaveType_post(){
		$id = $this->post('id');
		$data['cat_name']=$this->post('cat_name');
		$data['cat_code']=$this->post('cat_code');
		if($id==NULL){
			$result=$this->leave_mgmnt_model->addleaveTypeDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->leave_mgmnt_model->editLeaveTypeDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function leaveType_get(){
		$id = $this->get('id');
		if($id==NULL){
			$users=$this->leave_mgmnt_model->fetchLeaveTypeDetails();
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
	
	function leaveType_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->leave_mgmnt_model->deleteLeaveTypeDetails($id);
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

    // leave Entitlement
    function leaveEntitlement_post(){
    	// print_r($this->post());exit;
		$id = $this->post('id');
		$data['dept_id']=$this->post('dept_id');
		$data['emp_id']=$this->post('emp_id');
		$data['leave_list']=$this->post('leave_list');
		if($id==NULL){
			$result=$this->leave_mgmnt_model->addleaveEntitlementDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->leave_mgmnt_model->editLeaveEntitlementDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function leaveEntitlement_get(){
		$id = $this->get('id');
		if($id==NULL){
			$users=$this->leave_mgmnt_model->fetchEntitlementViewDetails();
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
			$users=$this->leave_mgmnt_model->getParticularDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    function leaveEntitlement_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->leave_mgmnt_model->deleteLeaveEntitlement($id);
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

    function leaveEntitlementDelete_delete(){
    	$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->leave_mgmnt_model->deleteParticularCategory($id);
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
    function fetchEmployeeLeaveType_get(){
    	$id = $this->get('id');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->leave_mgmnt_model->getemplyeeleavetypelist($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function leaveTypelistandcount_get(){
    	$id = $this->get('id');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->leave_mgmnt_model->fetchLeaveTypeList_Count($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

     function applyLeave_post(){
    		// print_r($this->post());exit;
		$id = $this->post('id');
		$data['leave_typeID']=$this->post('leave_typeID');
		$data['from_date']=$this->post('from_date');
		$data['upto_date']=$this->post('upto_date');
		$data['description']=$this->post('description');
		$data['total_leave']=$this->post('total_leave');
		$data['employee_id']=$this->post('employee_id');
		if($id==NULL){
			$result=$this->leave_mgmnt_model->applyLeaveDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			// $result=$this->leave_mgmnt_model->editLeaveEntitlementDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function applyLeave_get(){
    	$id = $this->get('id');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->leave_mgmnt_model->getViewdataforApplyLeave($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    function leaveApplicationDetails_get(){
    	$result=$this->leave_mgmnt_model->getViewdataforLeaveApplication();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fetchLeaveDetailList_get(){
    	$empId = $this->get('id');
    	$lineid = $this->get('lineid');
    	if(!empty($empId) && !empty($lineid)){
    		$result=$this->leave_mgmnt_model->getleavetypelistforEmployee($empId,$lineid);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
    	}else {
    		$this->set_response([
			'status' => FALSE,
			'message' => 'Record Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
    	}
    }
    function updateLeaveStatus_post(){
		$id=$this->post('id');
		$data['check_status']=$this->post('check_status');
		if($id){
			$result=$this->leave_mgmnt_model->updateStutus($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			// $result=$this->leave_mgmnt_model->editLeaveEntitlementDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
    function leaveApplicationDetails_delete(){
		$id = $this->delete('id');
		$bal_id = $this->delete('bal_id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->leave_mgmnt_model->deleteLeaveApplicationDetails($id,$bal_id);
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
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class EmployeemgmntAPI extends REST_Controller {
    function EmployeemgmntAPI()
    {
		parent::__construct();
		$this->load->model('employee_mgmnt_model');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }

    // Employee category
	
	function categoryDetail_post(){
		// print_r($this->post());exit;
		$id = $this->post('id');
		$data['NAME']=$this->post('name');
		$data['DESCRIPTION']=$this->post('description');
		if($id==NULL){
			$result=$this->employee_mgmnt_model->addCategoryDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->employee_mgmnt_model->editCategoryDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function categoryDetail_get(){
		$id = $this->get('dept_ID');
		if($id==NULL){
			$users=$this->employee_mgmnt_model->fetchEmployeeCategoryDetails();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'data'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Category Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Category Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
	
	function categoryDetail_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Category Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->employee_mgmnt_model->deleteCategoryDetails($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Category Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

    // Employee Position
	
	function positionDetail_post(){
		// print_r($this->post());exit;
		$id = $this->post('id');
		$data['NAME']=$this->post('name');
		$data['CATEGORY_ID']=$this->post('category_id');
		if($id==NULL){
			$result=$this->employee_mgmnt_model->addPositionDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->employee_mgmnt_model->editPositionDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function positionDetail_get(){
		$id = $this->get('dept_ID');
		if($id==NULL){
			$users=$this->employee_mgmnt_model->fetchEmployeePositionDetails();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'data'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Position Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Position Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
    }
	
	function positionDetail_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Position Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->employee_mgmnt_model->deletePositionDetails($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Position Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Position Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    function positionViewDetail_get(){
    	$result=$this->employee_mgmnt_model->getEmployeePositionView();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Position Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }

    
    // Employee ADMISSOIN	
	
	function employeeAdmission_post(){
		// print_r($this->post());exit;
		$id = $this->post('employee_id');
		$data['admission_no']=$this->post('admission_no');
		$data['join_date']=$this->post('join_date');
		$data['profile_image']=$this->post('profile_image');
		$data['first_name']=$this->post('first_name');
		$data['last_name']=$this->post('last_name');
		$data['gender']=$this->post('gender');
		$data['dob']=$this->post('dob');
		$data['marital_status']=$this->post('marital_status');
		$data['nationality']=$this->post('nationality');
		$data['qualification']=$this->post('qualification');
		$data['department']=$this->post('department');
		$data['category']=$this->post('category');
		$data['position']=$this->post('position');
		$data['address']=$this->post('address');
		$data['city']=$this->post('city');
		$data['state']=$this->post('state');
		$data['pincode']=$this->post('pincode');
		$data['country']=$this->post('country');
		$data['phone_no']=$this->post('phone_no');
		$data['mobile_no']=$this->post('mobile_no');
		$data['email']=$this->post('email');
		$data['ProfileID']=$this->post('ProfileID');
		if($id==NULL){
			$result=$this->employee_mgmnt_model->addEmployeeAdmissionDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->employee_mgmnt_model->editEmployeeAdmissionDetails($id,$data);
			// print_r($result);exit;
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function employeeContactDetail_post(){
		// print_r($this->post());exit;
		$id = $this->post('mailing_id');
		$data['permanant_id']=$this->post('permanant_id');
		$data['employee_id']=$this->post('employee_id');
		$data['ProfileID']=$this->post('ProfileID');
		$data['m_address']=$this->post('m_address');
		$data['m_city']=$this->post('m_city');
		$data['m_state']=$this->post('m_state');
		$data['m_pincode']=$this->post('m_pincode');
		$data['m_country']=$this->post('m_country');
		$data['p_address']=$this->post('p_address');
		$data['p_city']=$this->post('p_city');
		$data['P_state']=$this->post('P_state');
		$data['P_pincode']=$this->post('P_pincode');
		$data['P_country']=$this->post('P_country');
		$data['facebook']=$this->post('facebook');
		$data['google']=$this->post('google');
		$data['linked_in']=$this->post('linked_in');
		$data['phone_no']=$this->post('phone_no');
		$data['mobile_no']=$this->post('mobile_no');
		$data['email']=$this->post('email');
		if($id==NULL){
			$result=$this->employee_mgmnt_model->addEmployeeContactDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->employee_mgmnt_model->editEmployeeContactDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function employeePreviousInstitute_post(){
		// print_r($this->post());exit;
		$data['emp_profile_id']=$this->post('emp_profileID');
		$data['instituteID']=$this->post('instituteID');
		// $data['prevInst_ID']=$this->post('prevInstID');
		$data['prev_inst_data']=$this->post('prev_inst_data');
		$result=$this->employee_mgmnt_model->addEmployeePrevInstitute($data);
		if($result['status']==true){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	function employeeAdditionalDetails_post(){
		$data['profile']=$this->post('profile');
		$data['emp_profile_id']=$this->post('emp_profile_id');
		$data['profile_extra_id']=$this->post('profile_extra_id');
		$data['acc_name']=$this->post('acc_name');
		$data['acc_number']=$this->post('acc_number');
		$data['bank_name']=$this->post('bank_name');
		$data['branch_code']=$this->post('branch');
		$data['passport']=$this->post('passport');
		$data['work_permit']=$this->post('work_permit');
		$result=$this->employee_mgmnt_model->addEmployeeAdditionalDetails($data);
		if($result['status']==true){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}

	function employeeProfileView_get(){
		$result=$this->employee_mgmnt_model->fetchEmployeeViewDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	function fetchEmployeeData_get(){
		$id = $this->get('id');
		if (!empty($id)){
			$users=$this->employee_mgmnt_model->fetchParticularEmployeeDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'data'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}else {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function mailingAddress_get(){
    	$id = $this->get('id');
		if (!empty($id)){
			$users=$this->employee_mgmnt_model->fetchMailingAddressDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'data'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}else {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }

    function previousInstitute_get(){
    	$id = $this->get('id');
		if (!empty($id)){
			$users=$this->employee_mgmnt_model->fetchPreviousInstituteDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'data'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Institute Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}else {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Institute Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }

    function updateEmployeeAdmission_post(){
    	// print_r($this->post());
    	// exit;
    	$data['profile']=$this->post('Profile_id');
		$data['emp_profile_id']=$this->post('EMP_PROF_ID');
		$data['profile_extra_id']=$this->post('Profile_extra_id');
		$data['dob']=$this->post('dob');
		$data['profile_image']=$this->post('image_file');
		$data['natoinality']=$this->post('natoinality');
		$data['marital']=$this->post('marital');
		$data['facebook']=$this->post('facebook');
		$data['google']=$this->post('google');
		$data['linkedin']=$this->post('linkedin');
		$data['qualification']=$this->post('qualification');
		$data['category']=$this->post('category');
		$data['phone']=$this->post('phone');
		$data['mobile']=$this->post('mobile');
		$data['email']=$this->post('email');
		$data['account_name']=$this->post('account_name');
		$data['account_num']=$this->post('account_num');
		$data['bank_name']=$this->post('bank_name');
		$data['branch_name']=$this->post('branch_name');
		$data['passport_num']=$this->post('passport_num');
		$data['work_permit']=$this->post('work_permit');
		$data['perm_add_id']=$this->post('perm_add_id');
		$data['mail_add_id']=$this->post('mail_add_id');
		$data['m_address']=$this->post('m_address');
		$data['m_city']=$this->post('m_city');
		$data['m_state']=$this->post('m_state');
		$data['m_pincode']=$this->post('m_pincode');
		$data['m_country']=$this->post('m_country');
		$data['p_address']=$this->post('p_address');
		$data['p_city']=$this->post('p_city');
		$data['p_state']=$this->post('p_state');
		$data['p_pincode']=$this->post('p_pincode');
		$data['p_country']=$this->post('p_country');
		$data['prvious_work']=$this->post('prvious_work');
		$data['bank_id']=$this->post('bank_id');
		$result=$this->employee_mgmnt_model->updateEmployeeProfileDetails($data);
		if($result['status']==true){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function employeeProfile_delete(){
		$id = $this->delete('id');
		$prof_id = $this->delete('prof_id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->employee_mgmnt_model->deleteEmployeeDetails($id,$prof_id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Employee Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
}
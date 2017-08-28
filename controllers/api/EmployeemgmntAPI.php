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
		$config = Array(
			'protocol' => 'smtp',
			// 'smtp_host' => 'ssl://smtp.googlemail.com',
			// 'smtp_port' => 465,
			'smtp_host' => 'ssl://secure180.servconfig.com',
			'smtp_port' => 465,
			// 'smtp_user' => 'manisrikan@gmail.com', // change it to yours
			// 'smtp_pass' => 'mani16121993', // change it to yours
			'smtp_user' => 'info@rubycampus.com', // change it to yours
			'smtp_pass' => 'rubycampus@1234', // change it to yours
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		);
		// $this->load->library('email');
		$this->load->library('email', $config);
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
		$data['join_date']=date("Y-m-d", strtotime($this->post('join_date')));
		$data['profile_image']=$this->post('profile_image');
		$data['first_name']=$this->post('first_name');
		$data['last_name']=$this->post('last_name');
		$data['gender']=$this->post('gender');
		$data['dob']=date("Y-m-d", strtotime($this->post('dob')));
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
		$data['report_to']=$this->post('report_to');
		$data['inst_id']=$this->post('inst_id');
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
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
	}
	function getEmployeeList_get(){
		$id = $this->get('id');
		if ($id == null)
        {
        	$result=$this->employee_mgmnt_model->getEmployeeList();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
       	}	
		// if (!empty($id)){
		// 	$users=$this->employee_mgmnt_model->getEmployeeList($id);
		// 	if (!empty($users)){
		// 		$this->set_response(['status' =>TRUE,'data'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		// 	}
		// 	else
		// 	{
		// 		$this->set_response([
		// 		'status' => FALSE,
		// 		'message' => 'Employee Details could not be found'
		// 		], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		// 	}
		// }else {
		// 	$this->set_response([
		// 	'status' => FALSE,
		// 	'message' => 'Employee Detail could not be found'
		// 	], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		// }
    }
	function fetchEmployeeData_get(){
		$id = $this->get('id');
		$empProtype = $this->get('empProtype');
		if (!empty($id)){
			$users=$this->employee_mgmnt_model->fetchParticularEmployeeDetails($id,$empProtype);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'data'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}else {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee Detail could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
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
		$empProtype = $this->get('empProtype');
		if (!empty($id)){
			$users=$this->employee_mgmnt_model->fetchPreviousInstituteDetails($id,$empProtype);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'data'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Institute Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}else {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Institute Detail could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }

    function previousInstitute_delete(){
		$id = $this->delete('id');
		$LocId = $this->delete('Loc_id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->employee_mgmnt_model->deletePrevInstDetails($id,$LocId);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
    function updateEmployeeAdmission_post(){
    	// print_r($this->post());
    	// exit;
    	$data['profile']=$this->post('Profile_id');
		$data['emp_profile_id']=$this->post('EMP_PROF_ID');
		$data['profile_extra_id']=$this->post('Profile_extra_id');
		$data['dob']=date("Y-m-d", strtotime($this->post('dob')));
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
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function employeeBasicDetails_get(){
    	$id = $this->get('DEPT_ID');
		if (!empty($id)){
			$users=$this->employee_mgmnt_model->getEmployeeListBasedonDept($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'data'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}else {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }

   
    // Send Email
    function sendEmail_post(){
    	$empid=$this->post('mail_id');
    	// $result=$this->employee_mgmnt_model->checkEmail($empid);
    	
    	if($empid!=''){
    		$data=$this->employee_mgmnt_model->addMailDetails($empid);
    		// print_r($data['EMAIL_LOG_ID']);exit;
    		if($data){
    			// $message='Hi '.$result[0]['EMPLOYEE_NAME'];
				$this->email->set_newline("\r\n");
				$this->email->from('manisrikan@gmail.com','Rubycampus'); // change it to yours
				$this->email->to($empid);
				$this->email->subject('Rubycampus');
				$this->email->message('Rubycampus test');
				if (!$this->email->send())
				{
					show_error($this->email->print_debugger());
				}
				else{
					// echo 'Mail Sended Successfully';
					$this->employee_mgmnt_model->updateMailDetails($data['EMAIL_LOG_ID']);
					echo 'Mail Sended Successfully';
				}
				$this->email->clear(TRUE);
    		}
    	}else {
    		echo 'Mail Not available';
    	}
    }


    function updateEmployeebasicDetails_post(){
    	$data['PROFILE_ID']=$this->post('PROFILE_ID');
    	$EMP_PROFILE_ID=$this->post('EMP_PROFILE_ID');
    	$data['FirstName']=$this->post('FirstName');
		$data['LastName']=$this->post('LastName');
		$data['DateofBirth']=date("Y-m-d", strtotime($this->post('DateofBirth')));
		$data['MotherTongue']=$this->post('MotherTongue');
		$data['Gender']=$this->post('Gender');
		$data['Nationality']=$this->post('Nationality');
		$data['Qualification']=$this->post('Qualification');
		$data['MaritalStatus']=$this->post('MaritalStatus');
		if($EMP_PROFILE_ID==NULL){
			$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
		}else{
			$result=$this->employee_mgmnt_model->updateEmployeePersonalDetails($EMP_PROFILE_ID,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
    function updateEmployeeContactDetails_post(){
    	$PROFILE_ID=$this->post('PROFILE_ID');
    	$data['MAILING_ADDRESS_ID']=$this->post('MAILING_ADDRESS_ID');
    	$data['Email']=$this->post('Email');
		$data['Phone']=$this->post('Phone');
		$data['Address']=$this->post('Address');
		$data['City']=$this->post('City');
		$data['Zipcode']=$this->post('Zipcode');
		$data['State']=$this->post('State');
		$data['Country']=$this->post('Country');
		$data['Facebook']=$this->post('Facebook');
		$data['Google']=$this->post('Google');
		$data['Linkedin']=$this->post('Linkedin');
		if($PROFILE_ID==NULL){
			$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
		}else{
			$result=$this->employee_mgmnt_model->updateEmployeeContactDetails($PROFILE_ID,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }

    function updateEmployeePrevInstitution_post(){
    	$EMP_PROFILE_ID=$this->post('EMP_PROFILE_ID');
    	$data['MAILING_ADDRESS_ID']=$this->post('MAILING_ADDRESS_ID');
    	$data['Institution_Id']=$this->post('Institution_Id');
    	$data['InstitueName']=$this->post('InstitueName');
		$data['EmpRoll']=$this->post('EmpRoll');
		$data['PeriodFrom']=date("Y-m-d", strtotime($this->post('PeriodFrom')));
		$data['PeriodTo']=date("Y-m-d", strtotime($this->post('PeriodTo')));
		$data['Address']=$this->post('Address');
		$data['State']=$this->post('State');
		$data['Country']=$this->post('Country');
		$data['City']=$this->post('City');
		$data['Zipcode']=$this->post('Zipcode');
		$data['LOCATION_ID']=$this->post('LOCATION_ID');
		if($EMP_PROFILE_ID==NULL){
			$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
		}else{
			$result=$this->employee_mgmnt_model->updateEmployeePrevInstitution($EMP_PROFILE_ID,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }

    function updateEmployeeAddtionalDetail_post(){
    	$profile_extra_id=$this->post('profile_extra_id');
    	$data['bankdetail_id']=$this->post('bankdetail_id');
    	$data['AcName']=$this->post('AcName');
		$data['AcNo']=$this->post('AcNo');
		$data['BankName']=$this->post('BankName');
		$data['BranchName']=$this->post('BranchName');
		$data['PassportNo']=$this->post('PassportNo');
		$data['WorkPermit']=$this->post('WorkPermit');
		if($profile_extra_id==NULL){
			$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
		}else{
			$result=$this->employee_mgmnt_model->updateEmployeeAddtionalDetail($profile_extra_id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'data'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
}
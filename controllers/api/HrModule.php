<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class hrModule extends REST_Controller  {
    function hrModule()
    {
        parent::__construct();
        $this->load->model('api/HrModuleMod');
		header("Access-Control-Allow-Origin: *");
		//header("Access-Control-Allow-Headers: Authorization");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
    }
	// Login
	function login_post(){
		$email = $this->post('USER_EMAIL');
		$password = $this->post('USER_PASSWORD');
		$users=$this->HrModuleMod->login($email,$password);
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'aaData'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	
	// Employee category 
	
    function employeeCategory_get(){
		
		$users=$this->HrModuleMod->employeeCategoryView();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'aaData'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
	
	function employeeCategory_post(){
		$users=$this->HrModuleMod->empAdd();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'message'=>'Employee Details are added'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee Detail could not be add'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	
	function employeeCategory_delete(){
    	$id=$this->delete('EMP_C_ID');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->HrModuleMod->empDelete($id);
			if($result!=0){
				$message = [
				'id' => $id,
				'message' => 'Record Deleted Successfully'
				];
				$this->set_response(['status'=>TRUE,'message'=>$message], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_NOT_FOUND);
			}
		}  
    }
	
	function employeeCategoryEdit_get(){
		$id = $this->get('EMP_C_ID');
		if($id==NULL){
			$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->HrModuleMod->empEdit($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'aaData'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function employeeCategoryEdit_post(){
		$id = $this->post('EMP_C_ID');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->HrModuleMod->empUpdate($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>'Employee Details are updated'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	
	// Employee department
	
    function employeeDepartment_get(){
		
		$users=$this->HrModuleMod->employeeDepartmentView();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'aaData'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
	
	function employeeDepartment_post(){
		$users=$this->HrModuleMod->empDepartmentAdd();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'message'=>'Employee Details are added'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee Detail could not be add'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	
	function employeeDepartment_delete(){
    	$id=$this->delete('EMP_D_ID');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->HrModuleMod->empDepartmentDelete($id);
			if($result!=0){
				$message = [
				'id' => $id,
				'message' => 'Record Deleted Successfully'
				];
				$this->set_response(['status'=>TRUE,'message'=>$message], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_NOT_FOUND);
			}
		}  
    }
	
	function employeeDepartmentEdit_get(){
		$id = $this->get('EMP_D_ID');
		if($id==NULL){
			$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->HrModuleMod->empDepartmentEdit($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'aaData'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function employeeDepartmentEdit_post(){
		$id = $this->post('EMP_D_ID');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->HrModuleMod->empDepartmentUpdate($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>'Employee Details are updated'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	
	// Employee Position
	
    function employeePosition_get(){
		
		$users=$this->HrModuleMod->employeePositionView();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'aaData'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
	
	function employeePosition_post(){
		$users=$this->HrModuleMod->empPositionAdd();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'message'=>'Employee Details are added'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee Detail could not be add'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	
	function employeePosition_delete(){
    	$id=$this->delete('EMP_P_ID');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->HrModuleMod->empPositionDelete($id);
			if($result!=0){
				$message = [
				'id' => $id,
				'message' => 'Record Deleted Successfully'
				];
				$this->set_response(['status'=>TRUE,'message'=>$message], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_NOT_FOUND);
			}
		}  
    }
	
	function employeePositionEdit_get(){
		$id = $this->get('EMP_P_ID');
		if($id==NULL){
			$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->HrModuleMod->empPositionEdit($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'aaData'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function employeePositionEdit_post(){
		$id = $this->post('EMP_P_ID');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->HrModuleMod->empPositionUpdate($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>'Employee Details are updated'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class HrConfigModule extends REST_Controller {    
    function HrConfigModule()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('hrConfigModel');
		//loadServer('getResource'); 
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    //Added by rafeeq for Excel
    function insertDataFromXcelToDB_get(){
    	$ext=$this->get('ext');
    	//$ext='csv';
    	//$ext='xls';
        //$this->load->model('excel');
        $this->load->helper('excel');
        if($ext=='csv'){
        	$data=getDataFromCsv($ext,'many');
        }else{
        	$data=getDataFromExcel($ext,'many');	
        }
        $status=$this->hrConfigModel->insertDataFromXcelToDB($data);
        if($status['errorMsg']=='success'){
		$this->set_response(['status' =>TRUE,'message'=>'Data Inserted Successfully','insertedRowCount'=>$status['rowInserted']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Columns not matched',
			'fields' => $status['columnHavingIssue']
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function getDataFromXcel_get(){
    	$ext=$this->get('ext');
    	//$ext='csv';
    	//$ext='xls';
        //$this->load->model('excel');
        $this->load->helper('excel');
        if($ext=='csv'){
        	$data=getDataFromCsv($ext,'one');
        }else{
        	$data=getDataFromExcel($ext,'one');	
        }
		if (!empty($data)){
			$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		 }
		 else
		 {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data is Empty'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function insertKeyWord_post()
    {
    	$data['firstname']=$this->post('firstname');
    	$data['lastname']=$this->post('lastname');
    	$data['email']=$this->post('email');
    	$data['location']=$this->post('location');
    	$data['phone']=$this->post('phone');
    	//$data['csrf_test_name']=$this->post('csrf_test_name');
    	$result=$this->hrConfigModel->insertKeyWord($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
    function insertKeyWord_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->hrConfigModel->getInsertKeyWord();
        	// print_r($result);
        	// exit;
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result[0]], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'No Data found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->hrConfigModel->getInsertKeyWord();
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result[0]], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'No Data found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}
	//Added by rafeeq for Excel
	//-------------------------------------  Employee category ---------------------------------------------------
    function employeeCategory_post()
    {
    	$data['EMP_C_ID']=$this->post('EMP_C_ID');
    	$data['EMP_C_NAME']=$this->post('EMP_C_NAME');
    	$data['EMP_C_PREFIX']=$this->post('EMP_C_PREFIX');
    	$data['EMP_C_ACTIVE_YN']=$this->post('EMP_C_ACTIVE_YN');
    	$data['csrf_test_name']=$this->post('csrf_test_name');
    	$result=$this->hrConfigModel->addEmployeeCategory($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message'],'EMP_C_ID'=>$result['EMP_C_ID']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
    function employeeCategory_get(){
    	
    	
//    	checkTokenAccess();// validate  token  in helper file

    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->hrConfigModel->getAllCategory_details();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->hrConfigModel->getCategory_details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}

    function employeeCategory_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hrConfigModel->deleteCategory($id);
			if($result!=0){
				$message = [
				'id' => $id,
				'message' => 'Record Deleted Successfully'
				];
				$this->set_response(['status'=>TRUE,'message'=>$message], REST_Controller::HTTP_OK);
			}else{
				$message = [
				'id' => $id,
				'message' => 'There is no Record found'
				];
				$this->set_response(['status'=>FALSE,'message'=>$message], REST_Controller::HTTP_NOT_FOUND);
			}
		}  
    }

    

    // ------------------------------------------------------- Employee Department ---------------------------------------------

    function employeeDepartment_post()
    {
    	$data['EMP_D_ID']=$this->post('EMP_D_ID');
    	$data['EMP_D_NAME']=$this->post('EMP_D_NAME');
    	$data['EMP_D_CODE']=$this->post('EMP_D_CODE');
    	$data['EMP_D_STATUS']=$this->post('EMP_D_STATUS');    	
    	$result=$this->hrConfigModel->addDepartment_Details($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
    function employeeDepartment_get(){
		$id=$this->get('id');
		if ($id == null)
        {
        	$result=$this->hrConfigModel->fetchAllDaprtment_Details();
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else {
        	$result=$this->hrConfigModel->fetchDaprtment_Details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
    function employeeDepartment_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hrConfigModel->deleteDepartment($id);
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


    // ---------------------------------------------------Employee Position ----------------------------------------

    function employeePosition_post()
    {
    	$data['EMP_P_ID']=$this->post('EMP_P_ID');
    	$data['EMP_P_NAME']=$this->post('EMP_P_NAME');
    	$data['EMP_P_CATEGORY_ID']=$this->post('EMP_P_CATEGORY_ID');
    	$data['EMP_P_ACTIVE_YN']=$this->post('EMP_P_ACTIVE_YN');
    	$result=$this->hrConfigModel->addPosition_Details($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
    function employeePosition_get(){
		$id=$this->get('id');
		if ($id == null)
        {
        	$result=$this->hrConfigModel->fetchAllPosition_Details();
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else {
        	$result=$this->hrConfigModel->fetchPosition_Details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
    function employeePosition_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hrConfigModel->deletePosition($id);
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

    // -------------------------------------------- Employee Grade --------------------------------------------

    function employeeGrade_post()
    {
    	$data['EMP_G_ID']=$this->post('EMP_G_ID');
    	$data['EMP_G_NAME']=$this->post('EMP_G_NAME');
    	$data['EMP_G_PRIORITY']=$this->post('EMP_G_PRIORITY');
    	$data['EMP_G_MAX_DAY']=$this->post('EMP_G_MAX_DAY');
    	$data['EMP_G_MAX_WEEK']=$this->post('EMP_G_MAX_WEEK');
    	$data['EMP_G_ACTIVE_YN']=$this->post('EMP_G_ACTIVE_YN');
    	$result=$this->hrConfigModel->addGrade_Details($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
    function employeeGrade_get(){
		$id=$this->get('id');
		if ($id == null)
        {
        	$result=$this->hrConfigModel->fetchAllGrade_Details();
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else {
        	$result=$this->hrConfigModel->fetchGrade_Details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
    function employeeGrade_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hrConfigModel->deleteGrade($id);
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

    // ------------------------------------------ Employee Leave Type ----------------------------------------------------


    function employeeLeaveType_post(){

    	$data['EMP_L_ID']=$this->post('EMP_L_ID');
    	$data['EMP_L_NAME']=$this->post('EMP_L_NAME');
    	$data['EMP_L_CODE']=$this->post('EMP_L_CODE');
    	$data['EMP_L_COUNT']=$this->post('EMP_L_COUNT');
    	$data['EMP_L_VALID_FROM']=$this->post('EMP_L_VALID_FROM');
    	$data['EMP_L_ALLOW_LEAVE_BAL']=$this->post('EMP_L_ALLOW_LEAVE_BAL');
    	$data['EMP_L_ALLOW_BAL_COUNT']=$this->post('EMP_L_ALLOW_BAL_COUNT');
    	$data['EMP_L_ADDI_LEAVE_DED_YN']=$this->post('EMP_L_ADDI_LEAVE_DED_YN');
    	$data['EMP_L_STATUS']=$this->post('EMP_L_STATUS');
    	$result=$this->hrConfigModel->addleaveType($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
    function employeeLeaveType_get(){
		$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->hrConfigModel->fetchAllLeaveType_Details();
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else {
        	$result=$this->hrConfigModel->fetchLeaveType_Details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
    function employeeLeaveType_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hrConfigModel->deleteLeaveType($id);
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

    // ------------------------------------------ Bank Details --------------------------------------------------------------

    function employeeBankdetails_post(){
    	$data['EMP_BNK_ID']=$this->post('EMP_BNK_ID');
    	$data['EMP_BNK_NAME']=$this->post('EMP_BNK_NAME');
    	$data['EMP_BNK_ACTIVE_YN']=$this->post('EMP_BNK_ACTIVE_YN');

    	$result=$this->hrConfigModel->addBankDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
    function employeeBankdetails_get(){
		$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->hrConfigModel->fetchAllBank_Details();
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else {
        	$result=$this->hrConfigModel->fetchBank_Details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
    function employeeBankdetails_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hrConfigModel->deleteBankDetails($id);
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

    // ------------------------------------------ Wroking Days --------------------------------------------------------------

    function employeeWorkingDays_post(){
    	$data['EMP_W_ID']=$this->post('EMP_W_ID');
    	$data['EMP_W_MONTH']=$this->post('EMP_W_MONTH');
    	$data['EMP_W_WEEK']=$this->post('EMP_W_WEEK');
    	$result=$this->hrConfigModel->addWorkingDays_Details($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
    function employeeWorkingDays_get(){
		$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->hrConfigModel->fetchAllWorkingDays_Details();
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else {
        	$result=$this->hrConfigModel->fetchWorkingDays_Details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
    function employeeWorkingDays_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hrConfigModel->deleteWorkingDays($id);
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

    // ------------------------------------------ Employee Additional Details --------------------------------------------

    function employeeAdditionalDetails_post(){
    	$data['EMP_ADD_ID']=$this->post('EMP_ADD_ID');
    	$data['EMP_ADD_NAME']=$this->post('EMP_ADD_NAME');
    	$data['EMP_ADD_METHOD']=$this->post('EMP_ADD_METHOD');
    	$data['EMP_ADD_MAND']=$this->post('EMP_ADD_MAND');
    	$data['EMP_ADD_STATUS']=$this->post('EMP_ADD_STATUS');
    	$result=$this->hrConfigModel->addAdditional_Details($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
    function employeeAdditionalDetails_get(){
		$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->hrConfigModel->fetchAllAdditional_Details();
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else {
        	$result=$this->hrConfigModel->fetchAdditional_Details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
    function employeeAdditionalDetails_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hrConfigModel->deleteAdditionalDetails($id);
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
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class ManageBatchModule extends REST_Controller {    
    function ManageBatchModule()
    {
		parent::__construct();
		$this->load->model('batchmodel');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }

	// Acodemics Class And Batch 
	
	function BatchDetail_post()
    {
		$id = $this->post('ACA_BAT_ID');
		$data['ACA_BAT_COU_ID'] = $this->post('ACA_BAT_COU_ID');
		$data['ACA_BAT_NAME'] = $this->post('ACA_BAT_NAME');
		$data['ACA_BAT_START_DT'] = $this->post('ACA_BAT_START_DT');
		$data['ACA_BAT_END_DT'] = $this->post('ACA_BAT_END_DT');
		$data['ACA_BAT_IMP_PRE_BAT_SUB_YN'] = $this->post('ACA_BAT_IMP_PRE_BAT_SUB_YN');
		$data['ACA_BAT_IMP_PRE_BAT_MASTER_FEE_YN'] = $this->post('ACA_BAT_IMP_PRE_BAT_MASTER_FEE_YN');
		$data['ACA_BAT_CRT_USER_ID'] = $this->post('ACA_BAT_CRT_USER_ID');
		$data['ACA_BAT_UPD_USER_ID'] = $this->post('ACA_BAT_UPD_USER_ID');
		if($id==NULL){
			$result=$this->batchmodel->addBatchDetail($data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'ACA_BAT_ID'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->batchmodel->editBatchDetail($id,$data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>"Batch Updated successfully"], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
	
	function BatchDetail_get(){
		$id = $this->get('ACA_COU_ID');
		if($id==NULL){
			$users=$this->batchmodel->getBatchDetailAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Batch Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}else{
			$users=$this->batchmodel->getBatchDetail($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Batch Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function BatchDetail_delete(){
		$id = $this->delete('ACA_BAT_ID');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Batch Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->batchmodel->deleteBatchDetail($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Batch Detail Deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Batch Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	
	function Batch_get(){
		$id = $this->get('ACA_BAT_ID');
		if($id==NULL){
			$users=$this->batchmodel->getBatchDetailAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Batch Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}else{
			$users=$this->batchmodel->getBatch($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Batch Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	// Acodemics Subject Details 
	function SubjectDetail_post()
    {
		$id = $this->post('ACA_SUB_ID');
		$data['ACA_SUB_COU_ID'] = $this->post('ACA_SUB_COU_ID');
		$data['ACA_SUB_BAT_ID'] = $this->post('ACA_SUB_BAT_ID');
		$data['ACA_SUB_NAME'] = $this->post('ACA_SUB_NAME');
		$data['ACA_SUB_CODE'] = $this->post('ACA_SUB_CODE');
		$data['ACA_SUB_MAXCLASS_WEEK'] = $this->post('ACA_SUB_MAXCLASS_WEEK');
		$data['ACA_SUB_NOEXAM_YN'] = $this->post('ACA_SUB_NOEXAM_YN');
		$data['ACA_SUB_SIXTH_SUB_YN'] = $this->post('ACA_SUB_SIXTH_SUB_YN');
		$data['ACA_SUB_ASL_SUB_YN'] = $this->post('ACA_SUB_ASL_SUB_YN');
		$data['ACA_SUB_ASL_MARK'] = $this->post('ACA_SUB_ASL_MARK');
		$data['ACA_SUB_ELECT_YN'] = $this->post('ACA_SUB_ELECT_YN');
		$data['ACA_SUB_ELECT_SUB_NAME'] = $this->post('ACA_SUB_ELECT_SUB_NAME');
		$data['ACA_SUB_CRT_USER_ID'] = $this->post('ACA_SUB_CRT_USER_ID');
		$data['ACA_SUB_UPD_USER_ID'] = $this->post('ACA_SUB_UPD_USER_ID');
		if($id==NULL){
			$result=$this->batchmodel->addSubjectDetail($data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'ACA_SUB_ID'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->batchmodel->editSubjectDetail($id,$data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>"Subject Updated successfully"], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
	
	function SubjectDetail_get(){
		$id = $this->get('ACA_SUB_ID');
		if($id==NULL){
			$users=$this->batchmodel->getSubjectDetailAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Subject Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}else{
			$users=$this->batchmodel->getSubjectDetail($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Subject Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function SubjectDetail_delete(){
		$id = $this->delete('ACA_SUB_ID');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Subject Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->batchmodel->deleteSubjectDetail($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Subject Detail Deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Subject Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	
	function ManageSubjectDetail_get(){
		$id = $this->get('ACA_COU_ID');
		$id1 = $this->get('ACA_BAT_ID');
		// if($id==NULL){
			// $users=$this->batchmodel->getSubjectDetailAll();
			// if (!empty($users)){
				// $this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			// }
			// else
			// {
				// $this->set_response([
				// 'status' => FALSE,
				// 'message' => 'Subject Details could not be found'
				// ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			// }
		// }else{
			$users=$this->batchmodel->ManageSubjectDetail($id,$id1);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Subject Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		//}
    }
	
	function ClassAndBatch_get(){
		$users=$this->batchmodel->getStudent();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Student data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
	
	// Acodemics Grading System
	
	function GradingSystem_get(){
		$id = $this->get('GRA_SYS_BAT_ID');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Grading System Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			$users=$this->batchmodel->getGradingSystem($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Grading System could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function GradingSystem_post()
    {
		$id = $this->post('GRA_SYS_ID');
		$data['GRA_SYS_BAT_ID'] = $this->post('GRA_SYS_BAT_ID');
		$data['GRA_SYS_NAME'] = $this->post('GRA_SYS_NAME');
		$data['GRA_SYS_SCORE_PER'] = $this->post('GRA_SYS_SCORE_PER');
		$data['GRA_SYS_SCORE_DESC'] = $this->post('GRA_SYS_SCORE_DESC');
		$data['GRA_SYS_CRT_USER_ID'] = $this->post('GRA_SYS_CRT_USER_ID');
		$data['GRA_SYS_UPD_USER_ID'] = $this->post('GRA_SYS_UPD_USER_ID');
		if($id==NULL){
			$result=$this->batchmodel->addGradingSystem($data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'GRA_SYS_ID'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->batchmodel->editGradingSystem($id,$data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>"Grading System Updated successfully"], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
	
	function GradingSystem_delete(){
		$id = $this->delete('GRA_SYS_ID');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Grading System could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->batchmodel->deleteGradingSystem($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Grading System Deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Grading System could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	
	function ViewBatchDetail_get(){
		$users=$this->batchmodel->viewBatchDetailAll();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Batch Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
	function manageSubjectView_get(){
		$users=$this->batchmodel->getSubjectDetailsFromView();
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
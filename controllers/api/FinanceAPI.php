<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class FinanceAPI extends REST_Controller {
    function FinanceAPI()
    {
		parent::__construct();
		$this->load->model('financemodel');
		$this->load->helper('dompdf_helper');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }

    // Payitem Details 	
	function feeItem_post(){
		// print_r($this->post());exit;
		$id = $this->post('itemid');
		$data['NAME']=$this->post('itemname');
		$data['DESC']=$this->post('description');
		if($id==NULL){
			$result=$this->financemodel->addFeeItem($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->financemodel->editFeeItem($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function feeItem_get(){
		$id = $this->get('id');
		if($id==NULL){
			$result=$this->financemodel->getFeeItemDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$result=$this->financemodel->getParticularFeeItem($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	function checkFeeItem_get(){
		$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->checkFeeItemAvailableorNot($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function checkFeeFine_get(){
		$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->checkFineAvailableorNot($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function checkFeeStructure_get(){
		$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->checkfeeStructureAvailableorNot($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function feeItem_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->deleteFeeItem($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function fetchStudent_get(){
    	$result=$this->financemodel->getStudentList();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }

    function feeFine_post(){
    	// print_r($this->post());exit();
    	$id = $this->post('id');
		$data['name']=$this->post('name');
		$data['item']=$this->post('fineItem');
		if($id==NULL){
			$result=$this->financemodel->addFine($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->financemodel->editFine($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
    function feeFine_get(){
		$id = $this->get('id');
    	if($id==NULL){
			$result=$this->financemodel->getFeeFineList();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$result=$this->financemodel->getParticularFineDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function feeFineItems_get(){
    	$id = $this->get('id');
    	if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->getfineItemDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    function feeFineItem_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->deleteFeefineItem($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function feeFine_delete(){
    	$id = $this->delete('id');
    	// print_r($id);exit();
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->deletefineItem($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function feeStructure_post(){
    	// print_r($this->post());exit;
    	$id = $this->post('stru_id');
    	$data['stru_name'] = $this->post('structure_name');
		// $data['coursedata']=$this->post('coursedata');
		// $data['studentData']=$this->post('studentData');
		// $data['batch_id']=$this->post('batch_id');
		$data['fee_data']=$this->post('feedata');
		if($id==NULL){
			$result=$this->financemodel->addFeeStructure($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->financemodel->editFeeStructure($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
    function feeStructure_get(){
    	$id = $this->get('id');
    	$profile_id = $this->get('profileID');
		if($profile_id){
			$result=$this->financemodel->getFeeStructurebasedonprofileDetails($profile_id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else if($id){
			$result=$this->financemodel->getParticularFeeStructure($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}else {
			$result=$this->financemodel->getFeeStructureDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function fetchCourseDetails_get(){
    	$result=$this->financemodel->fetchcourseDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function getStudentList_get(){
    	$id = $this->get('course_id');
    	$batch_id = $this->get('batch_id');
		if($id){
			$result=$this->financemodel->getStudentListbasedon_course($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}else if($batch_id){
			$result=$this->financemodel->getStudentListbasedon_batch($batch_id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}else {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fetchBatchList_get(){
    	$id = $this->get('course_id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->getBatchListbasedoncourse($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function fetchBatchDetails_get(){
    	$id = $this->get('course_id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->getBatchNamebasedoncourse($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function feeStructureView_get(){
    	$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->getFeeStructureViewDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function editFeeStructure_get(){
    	$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->getFeeStructureDataforEdit($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function feeStructure_delete(){
    	$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->deleteFeeStructure($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function particularFeeStructure_delete(){
    	$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->deleteParticularFeeStructure($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Fee Structure Deleted Successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function getStudentFeeList_get(){
    	$id = $this->get('batch_id');
    	$stu_id = $this->get('student_id');
    	$course_id = $this->get('course_id');
    	if($id){
    		$result=$this->financemodel->getStudentFeeStructure($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
    	}else if($stu_id){
    		$result=$this->financemodel->getSingleStudentFeeStructure($stu_id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
    	}
    	else if($course_id){
    		$result=$this->financemodel->getStudentFeeStructurebasedoncourse($course_id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
		// if($id==NULL){
		// 		$this->set_response([
		// 		'status' => FALSE,
		// 		'message' => 'Record could not be found'
		// 		], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		// }else{
		// 	$result=$this->financemodel->getStudentFeeStructure($id);
		// 	if (!empty($result)){
		// 		$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		// 	}
		// 	else
		// 	{
		// 		$this->set_response([
		// 		'status' => FALSE,
		// 		'message' => 'Record could not be found'
		// 		], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		// 	}
		// }
    }

    function getStudentFeeReport_get(){
    	$id = $this->get('batch_id');
    	$stu_id = $this->get('student_id');
    	$course_id = $this->get('course_id');
    	if($id){
    		$result=$this->financemodel->getStudentFeeReportbasedonbatch($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
    	}else if($stu_id){
    		$result=$this->financemodel->getSingleStudentFeeReport($stu_id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
    	}
    	else if($course_id){
    		$result=$this->financemodel->getStudentFeeReportbasedoncourse($course_id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

    function fetchStudentList_get(){
    	$id = $this->get('batch_id');
    	$course_id = $this->get('course_id');
    	$structureid = $this->get('structureid');
    	$bacthcourseid = $this->get('cou_id');
		if($course_id){
			$result=$this->financemodel->fetchStudentList($course_id,'course',$structureid,$bacthcourseid);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}else if($id){
			$result=$this->financemodel->fetchStudentList($id,'batch',$structureid,$bacthcourseid);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
    function fetchAssignedStudentList_get(){
    	$checkmode = $this->get('checkmode');
    	$checkId = $this->get('checkId');
		if($checkmode=='course'){
			$result=$this->financemodel->fetchAssignedStudentList($checkId,'course');
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}else if($checkmode=='batch'){
			$result=$this->financemodel->fetchAssignedStudentList($checkId,'batch');
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
    function assignFeeStructure_post(){
    	// print_r($this->post());exit;    	
    	$id = $this->post('struc_id');
		$data['course_id']=$this->post('course_id');
		$data['batch_id']=$this->post('batch_id');
		$data['student']=$this->post('student');
		$result=$this->financemodel->addAssignFeeStructure($id,$data);
		if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
    function assignFeeStructure_get(){
    	$id = $this->get('strc_id');
		if($id==NULL){
			$result=$this->financemodel->fetchAssingedFeeStructure();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}else{
			$result=$this->financemodel->getAssignedFeeStructureforStudent($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function removeAssignStudent_delete(){
    	$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->deleteAssignedStudent($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function assignFeeStructure_delete(){
    	$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->deleteAssignedFeeItem($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function fetchStudentBasicDetails_get(){
    	$id = $this->get('stud_fee_id');
    	$profileID = $this->get('profileID');
    	if($id){
    		$result=$this->financemodel->getStudentBasicDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
    	}else if($profileID){
    		$result=$this->financemodel->getStudentDetailsbasedon_ID($profileID);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
    	}else {
    		$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
    	}
    }
    function feePayment_get(){
    	$id = $this->get('structure_id');
    	$profileId = $this->get('profileId');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->fetchFeeItemandStrutureDetail($id,$profileId);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function fetchFeefineItem_get(){
    	$id = $this->get('structure_id');
    	$profileId = $this->get('profileId');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->fetchFeeFineItem($id,$profileId);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}

    }
    function saveFeePayment_post(){
    	// $id = $this->post('itemid');
    	// print_r($this->post());exit;
		$data['fee_id']=$this->post('student_feeID');
		$data['payment_date']=$this->post('payment_date');
		$data['payment_mode']=$this->post('payment_mode');
		$data['reference_no']=$this->post('refence_no');
		$data['cheque_no']=$this->post('cheque_no');
		$data['dd_no']=$this->post('dd_no');
		$data['bankname']=$this->post('bankname');
		$data['totalpay']=$this->post('totalpay');
		$data['item_data']=$this->post('item_data');
		// $data['fineItem']=$this->post('fineItem');
		$data['profileId']=$this->post('profileid');
		// if($id==NULL){
			$result=$this->financemodel->addFeepayment($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		// }else{
		// 	$result=$this->financemodel->editFeePayment($id,$data);
		// 	if($result['status']==true){
		// 		$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
		// 	}else{
		// 		$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		// 	}
		// }
    }

    function paymentDetails_get(){
		$id = $this->get('student_feeid');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->financemodel->getStudentFeeDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function institutionDetails_get(){
    	$result=$this->financemodel->fetchInstitutionDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
			
  //   	$id = $this->get('inst_id');
  //   	print_r($id);exit();
  //   	if($id){
		// 		$this->set_response([
		// 		'status' => FALSE,
		// 		'message' => 'Record could not be found'
		// 		], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

		// }else{
		// 	$result=$this->financemodel->fetchInstitutionDetails();
		// 	if (!empty($result)){
		// 		$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		// 	}
		// 	else
		// 	{
		// 		$this->set_response([
		// 		'status' => FALSE,
		// 		'message' => 'Record could not be found'
		// 		], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		// 	}
		// }
    }
    function fetchCourse_get(){
    	$result=$this->financemodel->fetchAssignedCourse();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fetchStudentFeesDetails_get(){
    	$id = $this->get('profileID');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->financemodel->fetchStudentFeesDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function fetchStudentDetailfeelist_get(){
    	$id = $this->get('feepaymentid');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->financemodel->fetchStudentDetailfeelist($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    // pdf generation
    // function studentfeelistpdf_post(){
    function index_post(){
		// echo 'sasas';exit;
		$json = file_get_contents('php://input');
      	$data = json_decode($json, TRUE);
      	$html=$this->load->view('studentfeedetails',$data,true);
      	pdf_create($html,"data",$stream=TRUE,'portrait','1.0');
	}

	function categoryDetail_post(){
		// print_r($this->post());exit;
		$id = $this->post('CATEGORY_ID');
		$data['NAME']=$this->post('CATEGORY_NAME');
		$data['DESC']=$this->post('DESCRIPTION');
		if($id==NULL){
			$result=$this->financemodel->addCategory($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->financemodel->editCategory($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function categoryDetail_get(){
		$id = $this->get('id');
		if($id==NULL){
			$result=$this->financemodel->getfinancecategoryDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$result=$this->financemodel->getParticularFeeItem($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function categoryDetailCheck_get(){
		$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->financemodel->checkCategory($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function categoryDetail_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->deleteCategory($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

 //    function income_post(){
	// 	// print_r($this->post());exit;
	// 	$id = $this->post('INCOME_ID');
	// 	$data['NAME']=$this->post('INCOME_NAME');
	// 	$data['DESC']=$this->post('DESCRIPTION');
	// 	$data['AMOUNT']=$this->post('AMOUNT');
	// 	$data['INCOME_DATE']=$this->post('INCOME_DATE');
	// 	$data['CATEGORY_ID']=$this->post('CATEGORY_ID');
	// 	if($id==NULL){
	// 		$result=$this->financemodel->addIncome($data);
	// 		if($result['status']==true){
	// 			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
	// 		}else{
	// 			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
	// 		}
	// 	}else{
	// 		$result=$this->financemodel->editIncome($id,$data);
	// 		if($result['status']==true){
	// 			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
	// 		}else{
	// 			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
	// 		}
	// 	}
	// }

	// function income_get(){
	// 	$id = $this->get('id');
	// 	if($id==NULL){
	// 		$result=$this->financemodel->getfeeIncomeDetails();
	// 		if (!empty($result)){
	// 			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	// 		}
	// 		else
	// 		{
	// 			$this->set_response([
	// 			'status' => FALSE,
	// 			'message' => 'Record could not be found'
	// 			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
	// 		}

	// 	}else{
	// 		// $result=$this->financemodel->checkCategory($id);
	// 		// if (!empty($result)){
	// 		// 	$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	// 		// }
	// 		// else
	// 		// {
	// 			$this->set_response([
	// 			'status' => FALSE,
	// 			'message' => 'Record could not be found'
	// 			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
	// 		// }
	// 	}
	// }

	// function income_delete(){
	// 	$id = $this->delete('id');
	// 	if($id==NULL){
	// 			$this->set_response([
	// 			'status' => FALSE,
	// 			'message' => 'Record could not be found'
	// 			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
	// 	}else{
	// 		$result=$this->financemodel->deleteIncomeDetails($id);
	// 		if ($result!=0){
	// 			$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	// 		}
	// 		else
	// 		{
	// 			$this->set_response([
	// 			'status' => FALSE,
	// 			'message' => 'Record could not be found'
	// 			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
	// 		}
	// 	}
 //    }

 //    function expense_post(){
	// 	// print_r($this->post());exit;
	// 	$id = $this->post('INCOME_ID');
	// 	$data['NAME']=$this->post('INCOME_NAME');
	// 	$data['DESC']=$this->post('DESCRIPTION');
	// 	$data['AMOUNT']=$this->post('AMOUNT');
	// 	$data['EXPENSE_DATE']=$this->post('EXPENSE_DATE');
	// 	$data['CATEGORY_ID']=$this->post('CATEGORY_ID');
	// 	if($id==NULL){
	// 		$result=$this->financemodel->addExpense($data);
	// 		if($result['status']==true){
	// 			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
	// 		}else{
	// 			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
	// 		}
	// 	}else{
	// 		$result=$this->financemodel->editExpense($id,$data);
	// 		if($result['status']==true){
	// 			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
	// 		}else{
	// 			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
	// 		}
	// 	}
	// }

	// function expense_get(){
	// 	$id = $this->get('id');
	// 	if($id==NULL){
	// 		$result=$this->financemodel->getfeeExpenseDetails();
	// 		if (!empty($result)){
	// 			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	// 		}
	// 		else
	// 		{
	// 			$this->set_response([
	// 			'status' => FALSE,
	// 			'message' => 'Record could not be found'
	// 			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
	// 		}

	// 	}else{
	// 		// $result=$this->financemodel->checkCategory($id);
	// 		// if (!empty($result)){
	// 		// 	$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	// 		// }
	// 		// else
	// 		// {
	// 			$this->set_response([
	// 			'status' => FALSE,
	// 			'message' => 'Record could not be found'
	// 			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
	// 		// }
	// 	}
	// }

	// function expense_delete(){
	// 	$id = $this->delete('id');
	// 	if($id==NULL){
	// 			$this->set_response([
	// 			'status' => FALSE,
	// 			'message' => 'Record could not be found'
	// 			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
	// 	}else{
	// 		$result=$this->financemodel->deleteExpenseDetails($id);
	// 		if ($result!=0){
	// 			$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	// 		}
	// 		else
	// 		{
	// 			$this->set_response([
	// 			'status' => FALSE,
	// 			'message' => 'Record could not be found'
	// 			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
	// 		}
	// 	}
 //    }


    function transactionDetails_post(){
		// print_r($this->post());exit;
		$id = $this->post('TRANSACTION_ID');
		$data['NAME']=$this->post('TRANSACTION_NAME');
		$data['DESC']=$this->post('DESCRIPTION');
		$data['AMOUNT']=$this->post('AMOUNT');
		$data['TRANSACTION_DATE']=$this->post('TRANSACTION_DATE');
		$data['CATEGORY_ID']=$this->post('CATEGORY_ID');
		$data['TRANSACTION_TYPE']=$this->post('TRANSACTION_TYPE');
		if($id==NULL){
			$result=$this->financemodel->addTransaction($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->financemodel->editTransaction($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}

	function transactionDetails_get(){
		$id = $this->get('id');
		$type = $this->get('type');
		if($id==NULL){
			$result=$this->financemodel->getTransactionDetails($type);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			// $result=$this->financemodel->checkCategory($id);
			// if (!empty($result)){
			// 	$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			// }
			// else
			// {
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			// }
		}
	}

	function transactionDetails_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->financemodel->deleteTransactionDetails($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    function transactionReport_get(){
    	$fromdate = $this->get('fromdate');
    	$uptodate = $this->get('uptodate');
		$result=$this->financemodel->fetchTransactionReportDetails($fromdate,$uptodate);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fetchFeeDefaulterDetails_get(){
    	$course_id = $this->get('course_id');
    	$batch_id = $this->get('batch_id');
		$student_id = $this->get('student_id');
    	if($course_id){
			$result=$this->financemodel->fetchFeeDefaulterBasedonCourse($course_id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}else if($batch_id){
			$result=$this->financemodel->fetchFeeDefaulterBasedonBatch($batch_id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
		else if($student_id){
			$result=$this->financemodel->fetchFeeDefaulterBasedonProfile($student_id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
}
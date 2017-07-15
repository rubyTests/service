<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class AcademicsAPI extends REST_Controller {
    function AcademicsAPI()
    {
		parent::__construct();
		$this->load->model('academics');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }

    // Department Details 
	
	function departmentDetail_post(){
		// print_r($this->post('DEPT_ID'));exit;
		$id = $this->post('DEPT_ID');
		$data['NAME']=$this->post('DEPT_NAME');
		$data['CODE']=$this->post('DEPT_CODE');
		$data['ROOM_ID']=$this->post('DEPT_ROOM_ID');
		$data['HOD']=$this->post('DEPT_HOD');
		$data['PHONE']=$this->post('DEPT_PHONE');
		$data['IMAGE']=$this->post('DEPT_IMAGE');
		$data['CRT_USER_ID']=$this->post('DEPT_CRT_USER_ID');
		$data['UPD_USER_ID']=$this->post('DEPT_UPD_USER_ID');
		if($id==NULL){
			$result=$this->academics->addDepartmentDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->academics->editDepartmentDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function departmentDetail_get(){
		$id = $this->get('dept_ID');
		if($id==NULL){
			$users=$this->academics->getDepartmentDetailsAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Department Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$users=$this->academics->getDepartmentDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Department Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function departmentDetail_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Department Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->academics->deleteDepartmentDetails($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Department Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Department Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	// Course Details 
	
	function courseDetail_post(){
		$id = $this->post('COURSE_ID');
		$data['NAME']=$this->post('COURSE_NAME');
		$data['DEPT_ID']=$this->post('COURSE_DEPT_ID');
		$data['ATTENDANCE_TYPE']=$this->post('COURSE_ATTENDANCE_TYPE');
		$data['PERCENTAGE']=$this->post('COURSE_PERCENTAGE');
		// $data['GARDE_TYPE']=$this->post('COURSE_GARDE_TYPE');
		$data['CRT_USER_ID']=$this->post('COURSE_CRT_USER_ID');
		$data['UPD_USER_ID']=$this->post('COURSE_UPD_USER_ID');
		if($id==NULL){
			$result=$this->academics->addCourseDetails($data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->academics->editCourseDetails($id,$data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function courseDetail_get(){
		$id = $this->get('course_ID');
		if($id==NULL){
			$users=$this->academics->getCourseDetailsAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Course Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$users=$this->academics->getCourseDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Course Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function courseDetail_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Course Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->academics->deleteCourseDetails($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Course Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Course Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	// Batch Details 
	
	function batchDetail_post(){
		$id = $this->post('batch_id');
		$data['COURSE_ID']=$this->post('course_id');
		$data['NAME']=$this->post('batch_name');		
		$data['INCHARGE']=$this->post('incharge');
		$data['PERIOD_FROM']=$this->post('period_from');
		$data['PERIOD_TO']=$this->post('period_to');
		if($id==NULL){
			$result=$this->academics->addBatchDetails($data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->academics->editBatchDetails($id,$data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function batchDetail_get(){
		$id = $this->get('batch_id');
		if($id==NULL){
			$users=$this->academics->getBatchDetailsAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Batch Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$users=$this->academics->getBatchDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Batch Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function batchDetail_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Batch Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->academics->deleteBatchDetails($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Batch Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Batch Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	// Subject Details 
	
	function subjectDetail_post(){
		// echo "<pre>";
		// print_r($this->post());exit;
		$id = $this->post('cou_sub_id');
		$data['SUBID'] = $this->post('sub_id');
		$data['NAME']=$this->post('subject_name');
		$data['CODE']=$this->post('sub_code');
		// $data['TYPE']=$this->post('sub_type');
		$data['TOTAL_HOURS']=$this->post('total_hours');
		// $data['CREDIT_HOURS']=$this->post('credit_hours');
		$data['COURSE_ID']=$this->post('course_id');
		$data['CRT_USER_ID']=$this->post('SUB_CRT_USER_ID');
		$data['UPD_USER_ID']=$this->post('SUB_UPD_USER_ID');
		if($id==NULL){
			$result=$this->academics->addSubjectDetails($data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->academics->editSubjectDetails($id,$data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function subjectDetail_get(){
		$id = $this->get('subject_ID');
		if($id==NULL){
			$users=$this->academics->getSubjectDetailsAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Subject Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$users=$this->academics->getSubjectDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Subject Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function getSubjectData_get(){
		$id = $this->get('courseId');
		$users=$this->academics->getSubjectFilterData($id);
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Subject Detail could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
	
	function subjectDetail_delete(){
		$id = $this->delete('CS_ID');
		$subID = $this->delete('subID');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Subject Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->academics->deleteSubjectDetails($id,$subID);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Subject Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Subject Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	// Syllabus Details 
	
	function syllabusDetail_post(){
		// print_r($this->post('syllabus_data'));exit;
		$id = $this->post('sub_syllabus_id');
		// $data['NAME']=$this->post('SYLLABUS_NAME');
		$data['COURSE_ID']=$this->post('course_id');
		$data['SUBJECT_ID']=$this->post('subject_id');
		$data['SYLLABUS_DATA']=$this->post('syllabus_data');
		// print_r($data);exit;
		if($id==NULL){
			$result=$this->academics->addSyllabusDetails($data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>"Syllabus Details Added Successfully"], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->academics->editSyllabusDetails($id,$data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>"Syllabus Details Updated successfully"], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function syllabusDetail_get(){
		$id = $this->get('syllabus_ID');
		if($id==NULL){
			$users=$this->academics->getSyllabusDetailsAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Syllabus Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$users=$this->academics->getSyllabusDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Syllabus Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function syllabusDetail_delete(){
		$id = $this->delete('id');
		$syl_id = $this->delete('syllabus_id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Syllabus Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->academics->deleteSyllabusDetails($id,$syl_id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Syllabus Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Syllabus Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

// Employee Profile

    function profile_get(){
		$users=$this->academics->getEmployeeDetails();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Department Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }

    function departmentlist_get(){
    	$users=$this->academics->getDepartmentList();
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Department Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fetchCourseData_get(){
    	$data=$this->academics->getCourseList();
		if (!empty($data)){
			$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Course Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fetchSubjectData_get(){
    	$data=$this->academics->getSubjectList();
		if (!empty($data)){
			$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Subject Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fetchSubjectSyllabusData_get(){
    	$id = $this->get('id');
    	$data=$this->academics->getSubjectSyllabusList($id);
		if (!empty($data)){
			$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Subject Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fetchSyllabusListDetails_get(){
    	$id = $this->get('id');
    	$data=$this->academics->getSyllabusListDetail($id);
		if (!empty($data)){
			$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Subject Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fetchAllSubjectSyllabusData_get(){
		$roleId=$this->get('roleId');
		$profileId=$this->get('profileId');
    	$data=$this->academics->getAllSubjectsyllabusID($roleId,$profileId);
		if (!empty($data)){
			$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Subject Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fetchAllSyllabusData_get(){
    	$data=$this->academics->getAllSyllabusDetail();
		if (!empty($data)){
			$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Subject Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }

    function fetchcourseDetailList_get(){
    	$id = $this->get('id');
    	if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Course Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

		}else{
			$data=$this->academics->getParticularCousreList($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Course Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function fetchbatchDetailList_get(){
    	$id = $this->get('id');
    	if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Batch Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

		}else{
			$data=$this->academics->getParticularBatchList($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Course Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
    function fetchSubjectDetailList_get(){
    	$id = $this->get('id');
    	if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Subject Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

		}else{
			$data=$this->academics->getParticularSubjectList($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Subject Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function fetchTeacherDetailList_get(){
    	$id = $this->get('id');
    	if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Employee Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

		}else{
			$data=$this->academics->getParticularEmployeeList($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    function assignTeacherDetail_post(){
    	$id = $this->post('hidden_id');
		$data['course_id']=$this->post('course_id');
		$data['subject']=$this->post('subject');
		$data['employee_id']=$this->post('employee_id');
		if($id==NULL){
			$result=$this->academics->saveTeacherDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->academics->updateTeacherDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
    function assignTeacherDetail_get(){
		$id = $this->get('dept_ID');
		if($id==NULL){
			$users=$this->academics->getAllteacherDetails();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Department Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$users=$this->academics->getParticularTeacherDetails($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Department Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function assignTeacherDetail_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->academics->deleteteacherDetails($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record Detail could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    // Written by Gnanamani on 27-06-17    -  fetch subject and syllabus details
    function fetchSyllabusListbasedonCourse_get(){
		$courseid=$this->get('courseid');
    	$data=$this->academics->getSubjectandSyllabusDetails($courseid);
    	// print_r($data);exit();
		if (!empty($data)){
			$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Subject Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
    }
    function fecthDepartmentData_get(){
    	$employeeId=$this->get('empID');
    	$data=$this->academics->getDepartmentBasedonEmployee($employeeId);
		if (!empty($data)){
			$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Subject Details could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
    }
    // Written By Manivannan
    function assignRoleDetail_get(){
		$id = $this->get('id');
		if($id==NULL){
			$users=$this->academics->assignRoleDetails();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Role Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}
		// else{
		// 	$users=$this->academics->getParticularAssignRoleDetails($id);
		// 	if (!empty($users)){
		// 		$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		// 	}
		// 	else
		// 	{
		// 		$this->set_response([
		// 		'status' => FALSE,
		// 		'message' => 'Role Detail could not be found'
		// 		], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		// 	}
		// }
    }
    function assignRoleDetail_post(){ 
		// print_r($_POST);exit;
		$id=$this->post('id');
		$data['USER_ID']=$this->post('employee');
		$data['ROLL_NAME']=$this->post('roleName');
		$data['DEPT_ID']=$this->post('dept_name');
		if($id==NULL){
			$result=$this->academics->assignRoleAdd($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->academics->assignRoleUpdate($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function assignRoleDetail_delete(){
    	$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Department Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->academics->assignRoleDelete($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Department Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Batch Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
}
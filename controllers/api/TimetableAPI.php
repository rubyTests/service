<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class TimetableAPI extends REST_Controller {    
    function TimetableAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('timetable');
		$this->load->library('email');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();

		
    }

    // --------------------------------- Timetable Setting  -------------------------------------------------
	function timetableSetting_post(){
		// print_r($this->post());exit();
		$id=$this->post('setting_id');
		$data['start_day']=$this->post('start_day');
		$data['end_day']=$this->post('end_day');
		$data['startTime']=$this->post('startTime');
		$data['endTime']=$this->post('endTime');
		if($id==NULL){
			$result=$this->timetable->saveTimetableSettings($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->timetable->updateTimetableSettings($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	
	 function timetableSetting_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->timetable->fetchSettingDetails($id);
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

  //   function timetableSetting_delete(){
  //   	$id=$this->delete('id');
  //   	if ($id == null)
  //       {
  //           $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
  //       }else{
		// 	$result=$this->timetable->deleteProfileDetails($id);
		// 	if($result!=0){
		// 		$message = [
		// 		'id' => $id,
		// 		'message' => 'Record Deleted Successfully'
		// 		];
		// 		$this->set_response(['status'=>TRUE,'message'=>$message], REST_Controller::HTTP_OK);
		// 	}else{
		// 		$message = [
		// 		'id' => $id,
		// 		'message' => 'There is no Record found'
		// 		];
		// 		$this->set_response(['status'=>FALSE,'message'=>$message], REST_Controller::HTTP_NOT_FOUND);
		// 	}
		// }  
  //   } 

	function getcourseList_get(){
		$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->timetable->fetchCourseList($id);
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
	function getBatchList_get(){
		$courseid=$this->get('courseid');
    	if ($courseid == null)
        {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
       	}else {
       		$result=$this->timetable->fetchBatchList($courseid);
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
	function getSubjectList_get(){
		$courseid=$this->get('courseid');
    	if ($courseid == null)
        {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
       	}else {
       		$result=$this->timetable->fetchSubjectList($courseid);
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
	function getEmployeelistList_get(){
		$subjectid=$this->get('subjectid');
    	if ($subjectid == null)
        {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
       	}else {
       		$result=$this->timetable->fetchEmployeeList($subjectid);
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
	function timetableDetails_post(){
		$id=$this->post('tableid');
		$data['employee_id']=$this->post('employee_id');
		$data['course_id']=$this->post('course_id');
		$data['batch_id']=$this->post('batch_id');
		$data['subject_id']=$this->post('subject_id');
		$data['start_day']=$this->post('day');
		$data['startTime']=$this->post('starttime');
		$data['endTime']=$this->post('endtime');
		if($id==null){
			$result=$this->timetable->saveCalendarDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else {
			$result=$this->timetable->updateCalendarDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function checkTime_get(){
		$starttime=$this->get('starttime');
    	if ($starttime == null)
        {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
       	}else {
       		$result=$this->timetable->checkTimefromSettings($starttime);
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
	function timetableDetails_get(){
		$employee_id=$this->get('employee_id');
		$setday=$this->get('setday');
		$starttime=$this->get('starttime');
		$endtime=$this->get('endtime');  
    	if ($employee_id == null)
        {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
       	}else {
       		$result=$this->timetable->checkEmployeeTimetable($employee_id,$setday,$starttime,$endtime);
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
	function fetchTimetableList_get(){
		$batch=$this->get('batch');
		$emp_id=$this->get('emp_id');
       	if($batch){
       		$result=$this->timetable->getTimetableAllocationDetails($batch);
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
       	}else if($emp_id){
       		$result=$this->timetable->getTimetableDetailbasedonEmployee($emp_id);
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
       	}else {
       		$result=$this->timetable->getAllTimetableDetail();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
	function timetableDetails_delete(){
		$course_id=$this->delete('course_id');
		$batch_id=$this->delete('batch_id');
    	if ($course_id == null && $batch_id==null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->timetable->deleteTimetableDetails($course_id,$batch_id);
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

	function saveEmailData_post(){
		$id=$this->post('id');
		$data['user']=$this->post('user');
		$data['email']=$this->post('email');
		$data['subject']=$this->post('subject');
		if($id==null){
			$result=$this->timetable->saveDetails($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else {
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	function mailSend_post()
	{
		// print_r($this->post('email'));exit;
		// $config = Array(
		// 	'protocol' => 'smtp',
		// 	'smtp_host' => 'ssl://smtp.googlemail.com',
		// 	'smtp_port' => 465,
		// 	'smtp_user' => 'manisrikan@gmail.com', // change it to yours
		// 	'smtp_pass' => 'mani16121993', // change it to yours
		// 	'mailtype' => 'html',
		// 	'charset' => 'iso-8859-1',
		// 	'wordwrap' => TRUE
		// );

		
		// $test=10;
		// for ($i=0; $i < 3; $i++) { 
		// 	$this->email->initialize($config);
		// 	$this->load->library('email', $config);
		// 	$this->email->set_newline("\r\n");
		// 	$this->email->from('rvijayaraj24@gmail.com'); // change it to yours
		// 	$this->email->to($this->post('email'));
		// 	$this->email->subject('hi vijay');
		// 	if (!$this->email->send())
		// 	{
		// 		show_error($this->email->print_debugger());
		// 	}
		// 	else{
		// 		echo 'Mail Sended Successfully';
		// 	}
		// 	$this->email->clear(TRUE);
		// }
		
	}
}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class StuAttendanceAPI extends REST_Controller {    
    function StuAttendanceAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('stuattendance_model');
		$this->load->library('Curl');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    
	//-------------------------------------  Student Attendance marking ---------------------------------------------------
    function stuAttendanceMark_post()
    {
    	// print_r($this->post());exit;
    	// $data['presentStatus']=$this->post('presentStatus');
    	// $data['studentList']=$this->post('studentList');
    	// $data['courseId']=$this->post('courseId');
    	// $data['batchId']=$this->post('batchId');
    	// $data['subjectId']=$this->post('subjectId');
    	// $data['date']=date("Y-m-d", strtotime($this->post('date')));
    	// $data['type']=$this->post('type');
    	$data['userId']=$this->post('userId');
    	$data['details']=$this->post('details');
    	$data['courseId']=$this->post('courseId');
    	$data['batchId']=$this->post('batchId');
    	$data['subjectId']=$this->post('subjectId');
    	$data['date']=date("Y-m-d", strtotime($this->post('date')));
		$result=$this->stuattendance_model->addStuAttendanceDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function assignmentDetail_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->stuattendance_model->getAllAssignment_details();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->stuattendance_model->getAssignment_details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Assignment data could not be found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}

	function assignmentDetail_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->stuattendance_model->deleteAssignmentData($id);
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

	
	// Student admission number check 
	
	function stuAttendanceData_get(){
		$courseId=$this->get('course');
		$batch_id=$this->get('batchId');
		$type=$this->get('type');
		$date=date("Y-m-d", strtotime($this->get('date')));
		$result=$this->stuattendance_model->getStuAttendanceData($courseId,$batch_id,$type,$date);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data Not found'], REST_Controller::HTTP_OK);
		} 			
	}
	
	function stuAttendanceReport_get(){
		// print_r($this->get());exit;
		$pId=$this->get('profileId');
		$courseId=$this->get('course');
		$batch_id=$this->get('batchId');
		$type=$this->get('attendance_type');
		$subjectId=$this->get('subjectId');
		$method=$this->get('type');
		if($pId==null){
			$result=$this->stuattendance_model->getAllStuAttendanceReport($courseId,$batch_id,$type);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data Not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->stuattendance_model->getStuAttendanceReport($type,$pId,$subjectId);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data Not found'], REST_Controller::HTTP_OK);
			}
		} 			
	}
	
	function studentBasicandPercentageDetails_get(){
		// print_r($this->get());exit;
		$profileid=$this->get('profileid');
		$subjectId=$this->get('subjectId');
		$result=$this->stuattendance_model->getStudentBasicandPercentage($profileid,$subjectId);
		// print_r($result);exit;
		if ($result){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data Not found'], REST_Controller::HTTP_OK);
		}
	}


	function stuProfileAttendanceReport_get(){
		$pId=$this->get('profileId');
		$result=$this->stuattendance_model->getStuProfileAttendanceReport($pId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data Not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function stuApplyLeave_post(){
		$data['profileId']=$this->post('profileId');
    	$data['reason']=$this->post('reason');
    	$data['duration']=$this->post('duration');
    	$data['startDate']=date("Y-m-d", strtotime($this->post('startDate')));
    	$data['endDate']=date("Y-m-d", strtotime($this->post('endDate')));
    	$data['userId']=$this->post('userId');
		$result=$this->stuattendance_model->addStuApplyLeave($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	function stuApplyLeave_get(){
		$profileId=$this->get('profileId');
		$result=$this->stuattendance_model->getStuApplyLeave($profileId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data Not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function stuApproveLeave_get(){
		$courseId=$this->get('courseId');
		$batchId=$this->get('batchId');
		$result=$this->stuattendance_model->getAllStuApproveLeave($courseId,$batchId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data Not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function stuApproveLeave_post(){
		$data['ID']=$this->post('ID');
		$data['approval']=$this->post('approval');
		$result=$this->stuattendance_model->updateStuLeaveApprove($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	// Mobile App Student Attendance Report in Month wise
	
	function mStuProfileAttendanceReport_get(){
		$pId=$this->get('profileId');
		$result=$this->stuattendance_model->mGetStuProfileAttendanceReport($pId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data Not found'], REST_Controller::HTTP_OK);
		}
	}
 
}
?>
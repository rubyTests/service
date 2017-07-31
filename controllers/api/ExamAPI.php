<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class ExamAPI extends REST_Controller {    
    function ExamAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('exammodel');
		$this->load->library('Curl');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
	
	function setGrade_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->exammodel->getAllSetGradeDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->getSetGradeDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function setGrade_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['MINI_PERCENTAGE']=$this->post('percentage');
		$data['CREDIT_POINTS']=$this->post('creditPoints');
		$data['DESCRIPTION']=$this->post('description');
		if($id==null){
			$result=$this->exammodel->addSetGradeDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Grade Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->editSetGradeDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Grade Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function setGrade_delete(){
		$id=$this->delete('id');
    	if ($id == null){
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->exammodel->deleteSetGradeDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	// Set Exam
	
	function setExam_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->exammodel->getAllSetExamDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->getSetExamDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function setExam_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['DESCRIPTION']=$this->post('description');
		if($id==null){
			$result=$this->exammodel->addSetExamDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Exam Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->editSetExamDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Exam Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function setExam_delete(){
		$id=$this->delete('id');
    	if ($id == null){
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->exammodel->deleteSetExamDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	// Set Assessment
	
	function setAssessment_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->exammodel->getAllSetAssessmentDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->getSetAssessmentDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function setAssessment_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['WEIGHTAGE']=$this->post('weightage');
		$data['EXAM_ID']=$this->post('examId');
		if($id==null){
			$result=$this->exammodel->addSetAssessmentDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Assessment Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->editSetAssessmentDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Assessment Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function setAssessment_delete(){
		$id=$this->delete('id');
    	if ($id == null){
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->exammodel->deleteSetAssessmentDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	// Set Weightage
	
	function setWeightage_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->exammodel->getAllSetWeightageDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->getSetWeightageDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function setWeightage_post(){
		$id=$this->post('id');
		$data['ASSESSMENT_ID']=$this->post('assessmentId');
		$data['WEIGHTAGE']=$this->post('weightage');
		if($id==null){
			$result=$this->exammodel->addSetWeightageDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Weightage Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->editSetWeightageDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Weightage Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function setWeightage_delete(){
		$id=$this->delete('id');
    	if ($id == null){
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->exammodel->deleteSetWeightageDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	// Set Assign Exam
	
	function setAssignExam_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->exammodel->getAllSetAssignExamDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->getSetAssignExamDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function setAssignExam_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['ASSESSMENT_ID']=$this->post('assessmentId');
		$data['COURSE_ID']=$this->post('courseId');
		$data['START_DATE']=$this->post('startDate');
		$data['END_DATE']=$this->post('endDate');
		if($id==null){
			$result=$this->exammodel->addSetAssignExamDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'AssignExam Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->editSetAssignExamDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'AssignExam Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function setAssignExam_delete(){
		$id=$this->delete('id');
    	if ($id == null){
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->exammodel->deleteSetAssignExamDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	
	// Created @ 03.06.2017 6:04PM
	
	// Set Term
	
	function setTerm_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->exammodel->getAllSetTermDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->getSetTermDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function setTerm_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['DESCRIPTION']=$this->post('description');
		if($id==null){
			$result=$this->exammodel->addSetTermDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Term Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->editSetTermDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Term Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function setTerm_delete(){
		$id=$this->delete('id');
    	if ($id == null){
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->exammodel->deleteSetTermDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	// Set Create Exam
	
	function setCreateExam_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->exammodel->getAllSetCreateExamDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->getSetCreateExamDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function setCreateExam_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['SETTERM_ID']=$this->post('termId');
		$data['STARTDATE']=date("Y-m-d", strtotime($this->post('startDate')));
		$data['ENDDATE']=date("Y-m-d", strtotime($this->post('endDate')));
		if($id==null){
			$result=$this->exammodel->addSetCreateExamDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Create Exam Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->editSetCreateExamDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Create Exam Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function setCreateExam_delete(){
		$id=$this->delete('id');
    	if ($id == null){
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->exammodel->deleteSetCreateExamDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	// Set Assessment1
	
	function setAssessment1_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->exammodel->getAllAssessment1Details();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->getSetAssessment1Details($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function setAssessment1_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['CREATEEXAM_ID']=$this->post('examId');
		$data['MAX_MARK']=$this->post('maxMark');
		if($id==null){
			$result=$this->exammodel->addSetAssessment1Details($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Assessment Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->editSetAssessment1Details($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Assessment Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function setAssessment1_delete(){
		$id=$this->delete('id');
    	if ($id == null){
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->exammodel->deleteSetAssessment1Details($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	// Set Examination
	
	function setExamination_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->exammodel->getAllExaminationDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->getSetExaminationDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function setExamination_post(){
		$id=$this->post('id');
		$data['BATCH_ID']=$this->post('batch_id');
		$data['EXAM_ID']=$this->post('exam_id');
		$data['SUBJECT_ID']=$this->post('subject_id');
		$data['DATE']=$this->post('exam_date');
		$data['START_TIME']=$this->post('starttime');
		$data['END_TIME']=$this->post('endtime');
		$data['PASS_MARK']=$this->post('passMark');
		$data['MAX_MARK']=$this->post('maxMark');
		if($id==null){
			$result=$this->exammodel->addSetExaminationDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Examination Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->exammodel->editSetExaminationDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Examination Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function setExamination_delete(){
		$id=$this->delete('id');
    	if ($id == null){
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->exammodel->deleteSetExaminationDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	// Exam Calendar view
	
	function setExamCalendar_get(){
		// $id=$this->get('id');
		// if($id==null){
			// $this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		// }else{
			$result=$this->exammodel->getCalendarExamDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data1 not found'], REST_Controller::HTTP_OK);
			}
		//}		
	}
	
	// Student Details
	
	function studentDetails_get(){
		$data['termId']=$this->get('termId');
		$data['createExam']=$this->get('createExam');
		$data['assessmentId']=$this->get('assessmentId');
		$data['course']=$this->get('course');
		$data['batch']=$this->get('batch');
		$data['subject']=$this->get('subject');
		$data['studentDetails']=$this->get('getStuDetails');
		$result=$this->exammodel->getStudentDetails($data);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data1 not found'], REST_Controller::HTTP_OK);
		}
	}
	
	// Assessment based student fetch
	
	function studentDetails_post(){
		$data['markList']=$this->post('markList');
		// $data['createExam']=$this->post('createExam');
		$data['assessmentId']=$this->post('assessmentId');
		// $data['course']=$this->post('course');
		$data['batch']=$this->post('batch');
		$data['subject']=$this->post('subject');
		$result=$this->exammodel->addStudentDetails($data);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data1 not found'], REST_Controller::HTTP_OK);
		}
	}
	
	// Exam based student fetch
	
	function examStuDetails_post(){
		$data['markList']=$this->post('markList');
		$data['createExam']=$this->post('createExam');
		$data['batch']=$this->post('batch');
		$data['subject']=$this->post('subject');
		$result=$this->exammodel->addExamStuDetails($data);
		if ($result[0]['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result[0]['message']], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>$result[0]['message']], REST_Controller::HTTP_OK);
		}
	}
	
	function examStuDetails_get(){
		$data['termId']=$this->get('termId');
		$data['createExam']=$this->get('createExam');
		$data['course']=$this->get('course');
		$data['batch']=$this->get('batch');
		$data['subject']=$this->get('subject');
		$result=$this->exammodel->getExamStuDetails($data);
		if ($result[0]['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result[0]['message']], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>$result[0]['message']], REST_Controller::HTTP_OK);
		}
	}
	
	// Term based exam, assessment Details
	
	function termExam_get(){
		$termId=$this->get('termId');
		$result=$this->exammodel->getTermExam($termId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data1 not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function examAssessment_get(){
		$assessmentId=$this->get('assessmentId');
		$result=$this->exammodel->getExamAssessment($assessmentId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data1 not found'], REST_Controller::HTTP_OK);
		}
	}
	
	// Student Exam Marklist Report

	function stuExamReport_get(){
		$proId=$this->get('proId');
		// $result=$this->exammodel->getStuExamReport($proId);
		$result=$this->exammodel->getStuExamReport1($proId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data1 not found'], REST_Controller::HTTP_OK);
		}
	}
	

	function examListBasedonTerms_get(){
		$id=$this->get('termid');
		if($id==null){
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}else{
			$result=$this->exammodel->fetchExamlistbasedonTerms($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}	
	}
	
	function stuExamReportChart_get(){
		$id=$this->get('profileId');
		if($id==null){
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}else{
			$result=$this->exammodel->stuMarkReportChart($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
}
?>
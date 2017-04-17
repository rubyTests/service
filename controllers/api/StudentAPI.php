<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class StudentAPI extends REST_Controller {    
    function StudentAPI()
    {
		parent::__construct();
		$this->load->model('studentmodel');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		// $userIDByToken="";

		//loadServer('getResource'); 
		// checkTokenAccess();
		// checkAccess();
    }

    // Student Admission 
	
    function studentAdmission_post()
    {
    	$data['STU_ADM_NO']=$this->post('STU_ADM_NO');
    	$data['STU_ADM_DT']=$this->post('STU_ADM_DT');
    	$data['STU_ADM_FIRST_NAME']=$this->post('STU_ADM_FIRST_NAME');
    	$data['STU_ADM_MIDDLE_NAME']=$this->post('STU_ADM_MIDDLE_NAME');
    	$data['STU_ADM_LAST_NAME']=$this->post('STU_ADM_LAST_NAME');
    	$data['STU_ADM_DOB']=$this->post('STU_ADM_DOB');
    	$data['STU_ADM_GENDER']=$this->post('STU_ADM_GENDER');
    	$data['STU_ADM_NATIONALITY']=$this->post('STU_ADM_NATIONALITY'); 
    	$data['STU_ADM_MOTHER_TONGUE']=$this->post('STU_ADM_MOTHER_TONGUE');
    	$data['STU_ADM_RELIGION']=$this->post('STU_ADM_RELIGION');
    	$data['STU_ADM_ADD1']=$this->post('STU_ADM_ADD1');
    	$data['STU_ADM_ADD2']=$this->post('STU_ADM_ADD2'); 
    	$data['STU_ADM_CITY']=$this->post('STU_ADM_CITY');
    	$data['STU_ADM_STATE']=$this->post('STU_ADM_STATE');
    	$data['STU_ADM_COUNTRY']=$this->post('STU_ADM_COUNTRY');
    	$data['STU_ADM_PINCODE']=$this->post('STU_ADM_PINCODE'); 
    	$data['STU_ADM_PHONE'] = $this->post('STU_ADM_PHONE');
	   	$data['STU_ADM_MOBILE'] = $this->post('STU_ADM_MOBILE');
	   	$data['STU_ADM_EMAIL'] = $this->post('STU_ADM_EMAIL');
	   	$data['STU_ADM_CB_COURSE'] = $this->post('STU_ADM_CB_COURSE');
	   	$data['STU_ADM_CB_BATCH'] = $this->post('STU_ADM_CB_BATCH');
	   	$data['STU_ADM_CB_ROLL_NO'] = $this->post('STU_ADM_CB_ROLL_NO');
	   	$data['STU_ADM_USER_ID'] = $this->post('STU_ADM_USER_ID');
		$id=$this->post('STU_ADM_ID');
		//$this->set_response(['status' =>TRUE,'id'=>$id,'admission_no'=>$data], REST_Controller::HTTP_CREATED);
		if($id==NULL){
			$result=$this->studentmodel->addStudentAdmissionDetails($data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'admission_no'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->studentmodel->editStudentAdmissionDetails($id,$data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'admission_no'=>$result,'message'=>'Student Admission Details Updated successfully'], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
	
	// Student Parent Details
	
	function studentParentDetails_post()
    {
    	$id=$this->post('STU_PA_ADM_NO');
    	$data['STU_PA_FIRST_NAME']=$this->post('STU_PA_FIRST_NAME');
    	$data['STU_PA_LAST_NAME']=$this->post('STU_PA_LAST_NAME');
    	$data['STU_PA_RELATION']=$this->post('STU_PA_RELATION');
    	$data['STU_PA_DOB']=$this->post('STU_PA_DOB');
    	$data['STU_PA_EDUCATION']=$this->post('STU_PA_EDUCATION');
    	$data['STU_PA_OCCUPATION']=$this->post('STU_PA_OCCUPATION');
    	$data['STU_PA_INCOME']=$this->post('STU_PA_INCOME'); 
    	$data['STU_PA_EMAIL']=$this->post('STU_PA_EMAIL');
    	$data['STU_ADM_RELIGION']=$this->post('STU_ADM_RELIGION');
    	$data['STU_PA_ADD1']=$this->post('STU_PA_ADD1');
    	$data['STU_PA_ADD2']=$this->post('STU_PA_ADD2'); 
    	$data['STU_PA_CITY']=$this->post('STU_PA_CITY');
    	$data['STU_PA_STATE']=$this->post('STU_PA_STATE');
    	$data['STU_PA_COUNTRY']=$this->post('STU_PA_COUNTRY');
    	$data['STU_PA_PHONE1']=$this->post('STU_PA_PHONE1'); 
    	$data['STU_PA_PHONE2'] = $this->post('STU_PA_PHONE2');
	   	$data['STU_PA_MOBILE'] = $this->post('STU_PA_MOBILE');
	   	$data['STU_PA_GA_NAME'] = $this->post('STU_PA_GA_NAME');
	   	$data['STU_PA_GA_RELATION'] = $this->post('STU_PA_GA_RELATION');
	   	$data['STU_PA_GA_ADD1'] = $this->post('STU_PA_GA_ADD1');
	   	$data['STU_PA_GA_ADD2'] = $this->post('STU_PA_GA_ADD2');
	   	$data['STU_PA_GA_CITY'] = $this->post('STU_PA_GA_CITY');
	   	$data['STU_PA_GA_STATE'] = $this->post('STU_PA_GA_STATE');
	   	$data['STU_PA_GA_COUNTRY'] = $this->post('STU_PA_GA_COUNTRY');
	   	$data['STU_PA_GA_PHONE1'] = $this->post('STU_PA_GA_PHONE1');
	   	$data['STU_PA_UPD_USER_ID'] = $this->post('STU_PA_UPD_USER_ID');
    	$result=$this->studentmodel->editStudentParentDetails($id,$data);
    	if($result==true){
			$this->set_response(['status' =>TRUE,'message'=>"success"], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
	
	// Student Previous Education
	
	function studentPreviousEducation_post()
    {
		$folderPath = $config['upload_path'] = 'upload/';
        $config['allowed_types'] = '*';   
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if(!$this->upload->do_upload('0')){
            $this->set_response(['status' =>FALSE,'message'=>'Upload Error'], REST_Controller::HTTP_NOT_FOUND);
        }else{
            $data = $this->upload->data();
            $filePath=$folderPath.$data['file_name'];
        }
		
    	$id=$this->post('STU_PRE_D_ADM_NO');
    	$data['STU_PRE_D_INSTITUTE_NAME']=$this->post('STU_PRE_D_INSTITUTE_NAME');
    	$data['STU_PRE_D_COURSE']=$this->post('STU_PRE_D_COURSE');
    	$data['STU_PRE_D_YEAR']=$this->post('STU_PRE_D_YEAR');
    	$data['STU_PRE_ADD_BLOOD_GROUP']=$this->post('STU_PRE_ADD_BLOOD_GROUP');
    	$data['STU_PRE_ADD_BIRTH_PLACE']=$this->post('STU_PRE_ADD_BIRTH_PLACE');
    	$data['STU_PRE_ADD_STUD_CATE']=$this->post('STU_PRE_ADD_STUD_CATE'); 
    	$data['STU_PRE_D_UPD_USER_ID']=$this->post('STU_PRE_D_UPD_USER_ID');
		$data['STU_PRE_ADD_IMAGE_PATH'] = $filePath;
    	$result=$this->studentmodel->editStudentPreviousEducation($id,$data);
    	if($result==true){
			$this->set_response(['status' =>TRUE,'message'=>"success"], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }
	
	
	
	function studentDetails_get(){
		
		$id=$this->get('STU_ADM_NO');
		if($id==null){
			$users=$this->studentmodel->getStudentDetailAll();
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

		}else{
			$users=$this->studentmodel->getStudentDetail($id);
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
    }

    // student view
    function studentAdmissionView_get(){
		$result=$this->studentmodel->getStudentAdmissionDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Student Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
}
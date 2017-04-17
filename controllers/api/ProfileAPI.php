<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class ProfileAPI extends REST_Controller {    
    function ProfileAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('profilemodel');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    
	//-------------------------------------  Profile Add/Update ---------------------------------------------------
    function admissionDetails_post()
    {
		// print_r($this->post());
		// exit;
    	$id=$this->post('profileId');
    	$data['ADMISSION_NO']=$this->post('admission_no');
    	$data['ADMISSION_DATE']=$this->post('admission_date');
    	$data['FIRSTNAME']=$this->post('first_name');
    	$data['LASTNAME']=$this->post('last_name');
		$data['IMAGE1']=$this->post('filename');
    	$data['GENDER']=$this->post('wizard_gender');
    	$data['DOB']=$this->post('wizard_birth');
    	$data['NATIONALITY']=$this->post('selectize_n');
    	$data['MOTHER_TONGUE']=$this->post('mother_tongue');
    	$data['RELIGION']=$this->post('religion');
    	$data['COURSEBATCH_ID']=$this->post('selectize_a');
    	$data['ROLL_NO']=$this->post('roll_no');
		
		if($id==null){
			$result=$this->profilemodel->addAdmission($data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'profile'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->profilemodel->editAdmission($id,$data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'profile'=>$result,'message'=>'Student Admission Details Updated successfully'], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
	
	// Previous Academics Details
	
	function academicsDetails_post(){
		$id=$this->post('profileId');
		$data['previous']=$this->post('previous');
		$data['sibling']=$this->post('sibling');
		$data['bloodGroup']=$this->post('bloodGroup');
		$data['birthplace']=$this->post('birthplace');
		$data['stu_category']=$this->post('stu_category');
		$data['stu_type']=$this->post('stu_type');
		$result=$this->profilemodel->academicsDetails($id,$data);
		if(!empty($result)){
			$this->set_response(['status' =>TRUE,'profileId'=>$id,'preEduId'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	// Contact Details
	
	function contactDetails_post(){
		//print_r($this->post());exit;
		$id=$this->post('profileId');
		
		$data['sameAddress']=$this->post('sameAddress');
		
		$data['ADDRESS']=$this->post('address');
		$data['CITY']=$this->post('city');
		$data['STATE']=$this->post('state');
		$data['ZIP_CODE']=$this->post('pincode');
		$data['COUNTRY']=$this->post('country');
		
		$data['ADDRESS1']=$this->post('address1');
		$data['CITY1']=$this->post('city1');
		$data['STATE1']=$this->post('state1');
		$data['ZIP_CODE1']=$this->post('pincode1');
		$data['COUNTRY1']=$this->post('country1');
		
		$data['PHONE_NO_1']=$this->post('phone');
		$data['PHONE_NO_2']=$this->post('mobile_no');
		$data['EMAIL']=$this->post('email');
		$data['FACEBOOK_LINK']=$this->post('facebook');
    	$data['GOOGLE_LINK']=$this->post('google');
    	$data['LINKEDIN_LINK']=$this->post('linkedin');
		$result=$this->profilemodel->contactDetails($id,$data);
		if(!empty($result)){
			$this->set_response(['status' =>TRUE,'profileId'=>$id,'locationId'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	function parentsProfile_post(){
		// print_r($this->post());
		// exit;
		$id=$this->post('profileId');
		$data['stuProfileId']=$this->post('stuProfileId');
		$data['father']=$this->post('father');
		$data['mother']=$this->post('mother');
		$data['guardian']=$this->post('guardian');
		$result=$this->profilemodel->parentsProfile($id,$data);
		if(!empty($result)){
			$this->set_response(['status' =>TRUE,'profileId'=>$id,'profileIds'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	function profileDetails_post()
    {
		// print_r($this->post());
		// exit;
    	$id=$this->post('profileId');
    	$data['ADMISSION_NO']=$this->post('admission_no');
    	$data['ADMISSION_DATE']=$this->post('admission_date');
    	$data['FIRSTNAME']=$this->post('first_name');
    	$data['LASTNAME']=$this->post('last_name');
		$data['IMAGE1']=$this->post('filename');
    	$data['GENDER']=$this->post('wizard_gender');
    	$data['DOB']=$this->post('wizard_birth');
    	$data['NATIONALITY']=$this->post('selectize_n');
    	$data['MOTHER_TONGUE']=$this->post('mother_tongue');
    	$data['RELIGION']=$this->post('religion');
    	$data['COURSEBATCH_ID']=$this->post('selectize_a');
    	$data['ROLL_NO']=$this->post('roll_no');
    	$data['ADDRESS']=$this->post('address');
		$data['CITY'] = $this->post('stu_city');
	   	$data['STATE'] = $this->post('stu_state');
	   	$data['COUNTRY'] = $this->post('selectize_country');
	   	$data['ZIP_CODE'] = $this->post('pincode');
    	$data['EMAIL']=$this->post('email'); 
    	$data['PHONE_NO_1']=$this->post('wizard_phone');
    	$data['PHONE_NO_2']=$this->post('mobile_no');
		
    	$data['BLOOD_GROUP']=$this->post('selectize_blood');
    	$data['BIRTHPLACE']=$this->post('birthplace');
    	$data['STUDENTCATEGORY_ID']=$this->post('selectize_cat');
    	$data['STUDENT_TYPE']=$this->post('selectize_styType');
		
		$data['INSTITUTE'] = $this->post('institute');
		$data['LEVEL'] = $this->post('course_name');
		$data['YEAR_COMPLETION'] = $this->post('completion');
		$data['TOTAL_GRADE'] = $this->post('total_mark');
		
		$data['SIBLING'] = $this->post('Sibling');
		
    	$data['FACEBOOK_LINK']=$this->post('facebook');
    	$data['GOOGLE_LINK']=$this->post('google');
    	$data['LINKEDIN_LINK']=$this->post('linkedin');
    	
		$data['CRT_USER_ID'] = $this->post('CRT_USER_ID');
		$data['UPD_USER_ID'] = $this->post('UPD_USER_ID');
    	
		if($id==null){
			$result=$this->profilemodel->addProfileDetails($data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'admission_no'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->profilemodel->editProfileDetails($id,$data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'admission_no'=>$result,'message'=>'Student Admission Details Updated successfully'], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
	
    function profileDetails_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->profilemodel->getProfileDetailsAll();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->profilemodel->getProfileDetails($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}

    function profileDetails_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->profilemodel->deleteProfileDetails($id);
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
	
	// Parents Details
	function parentsDetails_post(){
		//print_r($this->post());exit;
		$data['fname']=$this->post('p_first_name');
		$data['lname']=$this->post('p_last_name');
		$data['relation']=$this->post('p_relation');
		$data['dob']=$this->post('p_dob');
		$data['education']=$this->post('p_education');
		$data['occupation']=$this->post('occupation');
		$data['p_income']=$this->post('p_income');
		$data['pr_address']=$this->post('pr_address');
		$data['pr_city']=$this->post('pr_city');
		$data['pr_state']=$this->post('pr_state');
		$data['pr_pincode']=$this->post('pr_pincode');
		$data['pr_country']=$this->post('pr_country');
		$data['p_phone']=$this->post('p_phone');
		$data['p_mobile_no']=$this->post('p_mobile_no');
		$data['p_email']=$this->post('p_email');
		$data['CRT_USER_ID']=$this->post('userId');
		$data['parentProfileId']=$this->post('parentProfileId');
		$Id=$this->post('profileId');
		//$result=$this->profilemodel->parentsDetails($Id,$data);
		$result=$this->profilemodel->addParentsDetails($Id,$data);
		if(!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	// Edit Parents Details
	
	function editParentsDetails_post(){
		// print_r($this->post('0'));exit;
		//print_r($this->post());exit;
		// $data['fname']=$this->post('p_first_name');
		// $data['lname']=$this->post('p_last_name');
		// $data['relation']=$this->post('p_relation');
		// $data['dob']=$this->post('p_dob');
		// $data['education']=$this->post('p_education');
		// $data['occupation']=$this->post('occupation');
		// $data['p_income']=$this->post('p_income');
		// $data['pr_address']=$this->post('pr_address');
		// $data['pr_city']=$this->post('pr_city');
		// $data['pr_state']=$this->post('pr_state');
		// $data['pr_pincode']=$this->post('pr_pincode');
		// $data['pr_country']=$this->post('pr_country');
		// $data['p_phone']=$this->post('p_phone');
		// $data['p_mobile_no']=$this->post('p_mobile_no');
		// $data['p_email']=$this->post('p_email');
		// $data['CRT_USER_ID']=$this->post('userId');
		// $data['profileId']=$this->post('profileId');
		//$Id=$this->post('profileId');
		//$Id=1;
		//print_r($data);exit;
		
		$data=$this->post('0');
		$result=$this->profilemodel->editParentsDetails($data);
		if(!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	function parentsDetails_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
			$this->set_response(['status' => FALSE,'message' => 'Profile data could not be found'], REST_Controller::HTTP_NOT_FOUND);
		}else{
        	$result=$this->profilemodel->getParentsDetails($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}
	
	function profileEdit_post(){
		$data['profile']=$this->post('profile');
		$data['pre_edu']=$this->post('pre_edu');
		$data['parents']=$this->post('parents');
		$result=$this->profilemodel->profileEdit($data);
		if($result==true){
			$this->set_response(['status' =>TRUE,'message'=>'Student Profile Updated Successfully'], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
}
?>
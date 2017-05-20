<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
require APPPATH . '/helpers/smsGateway/sendSms.php';
require APPPATH . '/helpers/smsGateway/ClickSend/clickSend.php';
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
		$this->load->model('GeneralMod');
		$this->load->library('Curl');
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
    	$data['GENDER']=$this->post('gender');
    	$data['DOB']=$this->post('wizard_birth');
    	$data['NATIONALITY']=$this->post('nationality');
    	$data['MOTHER_TONGUE']=$this->post('mother_tongue');
    	$data['RELIGION']=$this->post('religion');
    	$data['COURSEBATCH_ID']=$this->post('batchId');
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
		$data['student_lives']=$this->post('student_lives');
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
		
		$data['locId']=$this->post('addressId');
		$data['locId1']=$this->post('addressId1');
		
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
			$type='Student';
			//$mailStatus=$this->profilemodel->checkMailVerification($id,$type);
			$mailStatus=$this->profilemodel->checkVerification($id,$type);
			//print_r($mailStatus);exit;
			if($mailStatus){
				//print_r($mailStatus);exit;
				$to=$mailStatus[0]['email'];
				$token=$mailStatus[0]['token'];
				$phone=$mailStatus[0]['phone'];
				$verifyLink='http://192.168.1.139/Projects/campus/#/verification/';
				$msg="Dear Sir/Madam,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to Rubycampus application. <a href='http://192.168.1.139/Projects/campus/#/verification/$token'> Click Here </a> <br><br><br>Thanks &amp; Regards,<br>Admin<br>";
				$sms="Welcome to Rubycampus application. Please click the link: http://192.168.1.137/Projects/campus/#/verification/$token";
				
				//$result=mailVerification($to,$msg);
				if($to){
					$res=mailVerify($to,$msg);
				}
				//print_r($result);exit;
				if($phone){
					$data=$this->GeneralMod->mobileCheck($id,$phone);
					$to=$data[0]['phone'];
					$msg=$data[0]['msg'];
					//$send=smsSend($to,$msg);
					$sms=clickSend($to,$msg);
				}
			}
			
			// $phoneStatus=$this->profilemodel->checkPhoneVerification($id);
			// if(!empty($phoneStatus)){
				// //$status=$this->GeneralMod->phoneVerify($id,$phoneStatus);
			// }
			$this->set_response(['status' =>TRUE,'profileId'=>$id,'locationId'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	// function uniqueMailId_get(){
		// $email=$this->get('email');
		// $phone=$this->get('phone');
		// $result=$this->profilemodel->uniqueMailId($id,$email,$phone);
		// if($result==true){
			// $this->set_response(['status' =>TRUE,'message'=>'Not Matched'], REST_Controller::HTTP_OK);
		// }else{
			// $this->set_response(['status' =>FALSE,'message'=>'Allready exist'], REST_Controller::HTTP_OK);
		// }
	// }
	
	function parentsProfile_post(){
		// print_r($this->post());
		// exit;
		$id=$this->post('profileId');
		$data['stuPro_id']=$this->post('stuPro_id');
		$data['father']=$this->post('father');
		$data['mother']=$this->post('mother');
		$data['guardian']=$this->post('guardian');
		//print_r($data);exit;
		$result=$this->profilemodel->parentsProfile($id,$data);
		$type='Parents';
		if(!empty($result)){
			if($result[0]['frelation_id']){
				$availabeStatus=$data['father']['availabe'];
				if($availabeStatus=='Y'){
					//print_r($result[0]['frelation_id']);exit;
					// $mailStatus=$this->profilemodel->checkMailVerification($result[0]['frelation_id'],$type);
					$mailStatus=$this->profilemodel->checkVerification($result[0]['frelation_id'],$type);
					//print_r($mailStatus);exit;
					if($mailStatus){
						$to=$mailStatus[0]['email'];
						$token=$mailStatus[0]['token'];
						$phone=$mailStatus[0]['phone'];
						$verifyLink='http://192.168.1.139/Projects/campus/#/verification/';
						$msg="Dear Sir/Madam,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to Rubycampus application. <a href='http://192.168.1.139/Projects/campus/#/verification/$token'> Click Here </a> <br><br><br>Thanks &amp; Regards,<br>Admin<br>";
						if($to){
							$res=mailVerify($to,$msg);
						}
						if($phone){
							$data=$this->GeneralMod->mobileCheck($result[0]['frelation_id'],$phone);
							$to=$data[0]['phone'];
							$msg=$data[0]['msg'];
							// $send=smsSend($to,$msg);
							$sms=clickSend($to,$msg);
						}
						//print_r($result);exit;
					}
				}
			}else if($result[0]['mrelation_id']){
				$availabeStatus=$data['mother']['availabe'];
				if($availabeStatus=='Y'){
					// $mailStatus=$this->profilemodel->checkMailVerification($result[0]['mrelation_id'],$type);
					$mailStatus=$this->profilemodel->checkVerification($result[0]['mrelation_id'],$type);
					if($mailStatus){
						$to=$mailStatus[0]['email'];
						$token=$mailStatus[0]['token'];
						$phone=$mailStatus[0]['phone'];
						$verifyLink='http://192.168.1.139/Projects/campus/#/verification/';
						$msg="Dear Sir/Madam,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to Rubycampus application. <a href='http://192.168.1.139/Projects/campus/#/verification/$token'> Click Here </a> <br><br><br>Thanks &amp; Regards,<br>Admin<br>";
						//$res=mailVerify($to,$msg);
						if($to){
							$res=mailVerify($to,$msg);
						}
						if($phone){
							$data=$this->GeneralMod->mobileCheck($result[0]['mrelation_id'],$phone);
							$to=$data[0]['phone'];
							$msg=$data[0]['msg'];
							// $send=smsSend($to,$msg);
							$sms=clickSend($to,$msg);
						}
						//print_r($result);exit;
					}
				}
			}else if($result[0]['grelation_id']){
				// $mailStatus=$this->profilemodel->checkMailVerification($result[0]['grelation_id'],$type);
				$mailStatus=$this->profilemodel->checkVerification($result[0]['grelation_id'],$type);
				if($mailStatus){
					$to=$mailStatus[0]['email'];
					$token=$mailStatus[0]['token'];
					$phone=$mailStatus[0]['phone'];
					$verifyLink='http://192.168.1.139/Projects/campus/#/verification/';
					$msg="Dear Sir/Madam,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to Rubycampus application. <a href='http://192.168.1.139/Projects/campus/#/verification/$token'> Click Here </a> <br><br><br>Thanks &amp; Regards,<br>Admin<br>";
					//$res=mailVerify($to,$msg);
					if($to){
						$res=mailVerify($to,$msg);
					}
					if($phone){
						$data=$this->GeneralMod->mobileCheck($result[0]['grelation_id'],$phone);
						$to=$data[0]['phone'];
						$msg=$data[0]['msg'];
						// $send=smsSend($to,$msg);
						$sms=clickSend($to,$msg);
					}
					//print_r($result);exit;
				}
			}
			
			$this->set_response(['status' =>TRUE,'profileId'=>$id,'profileIds'=>$result,'message'=>'Student Profile Detail Inserted Successfully'], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
    function profileDetails_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	// $result=$this->profilemodel->getProfileDetailsAll();
        	// if (!empty($result)){
				// $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			// }
			// else
			// {
				$this->set_response([
				'status' => FALSE,
				'message' => 'Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			//}
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
	
	// Student Profile Edit
	
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
	
	function studentProfileDetails_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->profilemodel->getStudentProfileDetailsAll();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Student Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->profilemodel->getStudentProfileDetails($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Student Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}
	
	function studentSiblingDetails_get(){
    	$id=$this->get('batchId');   	
    	$profileId=$this->get('profileId');   	
		$result=$this->profilemodel->getStudentSiblingDetails($id,$profileId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Student Sibling data could not be found'
			], REST_Controller::HTTP_NOT_FOUND);
		} 			
	}
	
	// Previous Education Details Delete 
	function preEducation_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->profilemodel->deletePreEducation($id);
			if($result!=0){
				$message = [
				'id' => $id,
				'message' => 'Previous Academics Detail Deleted Successfully'
				];
				$this->set_response(['status'=>TRUE,'message'=>'Previous Academics Detail Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$message = [
				'id' => $id,
				'message' => 'There is no Record found'
				];
				$this->set_response(['status'=>FALSE,'message'=>$message], REST_Controller::HTTP_NOT_FOUND);
			}
		}  
    }
	// myProfile 
	function myProfile_get(){
    	$id=$this->get('user_id');
		$result=$this->profilemodel->myProfile($id);
		if ($result==true){
			$this->set_response(['status' =>FALSE,'message'=>'Admission number already exist'], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>TRUE,'message'=>'Success'], REST_Controller::HTTP_OK);
		} 			
	}
	
	// Student admission number check 
	
	function studentAdmissionNo_get(){
    	$no=$this->get('admission_no');
    	$id=$this->get('profileId');
		$result=$this->profilemodel->checkStudentAdmissionNo($no,$id);
		if ($result==true){
			$this->set_response(['status' =>FALSE,'message'=>'Admission number already exist'], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>TRUE,'message'=>'Success'], REST_Controller::HTTP_OK);
		} 			
	}
 
}
?>
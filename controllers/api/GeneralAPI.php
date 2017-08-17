<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
class GeneralAPI extends REST_Controller {    
    function GeneralAPI()
    {
		parent::__construct();
		$this->load->model('GeneralMod');
		$this->load->model('profilemodel');
		header("Access-Control-Allow-Origin: *");
		$this->load->library('Curl');
    }
    //function tokenGen($type,$client_id,$client_secret){
    function tokenGen($type,$username,$password,$client_id){
    	if($type=='client'){
	    	$data = array(
	            'grant_type'      => 'client_credentials',
	            'client_id' => $client_id,
	            'client_secret'    => $client_secret

	        );	
	        $path=base_url('api/Auth/getToken');
    	}else if($type=='password'){
    		$data = array(
            'grant_type'      => 'password',
            'client_id' => $client_id,
	        //'client_secret'    => $client_secret,
            'username' => $username,
            'password'    => $password

        );
    		$path=base_url('api/Auth/checkUser');
    	}else if($type=='phone'){
    		$data = array(
            'grant_type'      => 'password',
            'client_id' => $client_id,
            'username' => $username,
            'password'    => $password

        );
    		$path=base_url('api/Auth/checkUserPhone');
    	}
        
        //grant_type=client_credentials&client_id=TestClient&client_secret=TestSecret
        //grant_type=password&client_id=TestClient&username=bshaffer&password=brent123

    $data_string = json_encode($data);
    //echo "<br>";
//     $data_string = 'my_parm={
//           "Key1":"Value1",
//           "Key2":"Value2",
//           "Key3":"Value3"
// }';
    $curl = curl_init($path);
    //curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
    );

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // Make it so the data coming back is put into a string
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);  // Insert the data

    // Send the request
    $result = curl_exec($curl);
    // print_r($result);
    // exit;
    $valu=json_decode($result,true);
  // print_r($valu);exit;
   if(isset($valu['access_token'])){
		$user_token=$valu['access_token'];
		$msg='Success';
		$this->session->set_userdata('user_token',$user_token);
	}else if(isset($valu['error'])){
		$user_token=$valu['error'];
		$msg=$valu['error_description'];
	}
	
	// print_r($user_token);exit;
	
    
    // $path=APPPATH;
    // echo $path;
    // Free up the resources $curl is using
    curl_close($curl);
    return array(['token' => $user_token,'message' => $msg]);
    }

    // Login Details 
    function setSessionData($users){
    	$USER_ID = $users[0]['USER_ID'];
    	$USER_FIRST_NAME = $users[0]['USER_FIRST_NAME'];
    	$USER_LAST_NAME = $users[0]['USER_LAST_NAME'];
    	$USER_EMAIL = $users[0]['USER_EMAIL'];
    	
    	$this->session->set_userdata('USER_ID',$USER_ID);
    	$this->session->set_userdata('USER_FIRST_NAME',$USER_FIRST_NAME);
    	$this->session->set_userdata('USER_LAST_NAME',$USER_LAST_NAME);
    	$this->session->set_userdata('USER_EMAIL',$USER_EMAIL);

    }
    function user_get(){
    	$sessionToken=$this->session->userdata('user_token');
    	$this->response(['status' =>TRUE,'access_token'=> $sessionToken], REST_Controller::HTTP_OK); // OK 
			exit;
    }
	function login_get(){
		$email = $this->get('USER_EMAIL');
		$pwd = $this->get('USER_PASSWORD');
		//print_r(is_numeric($email));exit;
		
		$sessionToken=$this->session->userdata('user_token');
		//$this->session->sess_expiration = 20;
		// echo $this->session->userdata('USER_FIRST_NAME');
		// echo 'sessionToken';
		//  exit;
		$sessionEmail=$this->session->userdata('USER_EMAIL');
		if($email==$sessionEmail){
			if($sessionToken!=''){
				$this->response(['status' =>TRUE,'access_token'=> $sessionToken], REST_Controller::HTTP_OK); // OK 
				exit;
			}
		}
		
		// $this->session->set_userdata('user_token','test');
		// echo "came";
		//exit;
		if($email==NULL){
			$this->set_response(['status' => FALSE,'message' => 'Login Detail could not be found'], REST_Controller::HTTP_OK);
		}else{
			//$user=$this->GeneralMod->getLoginDetail($email,$pwd);
			//$type='client';
			if(is_numeric($email)){
				$type='phone';
			}else{
				$type='password';
			}
			$client_id='123';
			$users=$this->tokenGen($type,$email,$pwd,$client_id);
			if($users[0]['message']=='Success'){
				if($type=='phone'){
					$result=$this->GeneralMod->getPhoneLoginDetail($email,$pwd);					
				}else{
					$result=$this->GeneralMod->getEmailLoginDetail($email,$pwd);
				}
				// written on 30-06-17
					// if(isset($result[0]['USER_ID'])){
						// $privilege=$this->GeneralMod->getPrevilegeDetails($result[0]['USER_ID']);
						// $result[0]['user_privileges']=$privilege;
					// }
				$this->setSessionData($result);
				$this->set_response(['status' =>TRUE,'access_token'=> $users[0]['token'],'message'=>$result], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>$users[0]['message']], REST_Controller::HTTP_OK);
			}
			
			// if (!empty($users)){
				
				// //$client_secret='123456';
				// //$users=$this->tokenGen($type,$email,$pwd,$client_id);
				// $result=$this->GeneralMod->getLoginDetail($email,$pwd);
				// $this->setSessionData($result);
				// //$users=$this->tokenGen($type,$client_id,$client_secret);
				// //$users=json_decode($users);
				// $this->set_response(['status' =>TRUE,'access_token'=> $users,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			// }
			// else
			// {
				// $this->set_response([
				// 'status' => FALSE,
				// 'message' => 'Login Detail could not be found'
				// ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			// }
		}
    }
	
	// Check User Phone number

	function ranGen_get(){
		$userData=$this->get('USER_EMAIL');
		$result=$this->GeneralMod->ranGen($userData);
		// $result = Math.floor((Math.random() * 1000000000) + 1);
		if ($result==true){
			$this->set_response(['status' =>true,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else{
			$this->set_response(['status' =>TRUE,'message'=>'Not verify'], REST_Controller::HTTP_OK);
		}
	}
	
	function checkUserValid_get(){
		$userData=$this->get('USER_EMAIL');
		$result=$this->GeneralMod->getLoginDetail($userData);
		if ($result==true){
			$this->set_response(['status' =>true,'message'=>'Already verified'], REST_Controller::HTTP_OK); 
		}
		else{
			$this->set_response(['status' =>TRUE,'message'=>'Not verify'], REST_Controller::HTTP_OK);
		}
	}
	
	function checkAccessToken_get(){
		$token=$this->get('access_token');
		$result=$this->GeneralMod->checkAccessToken($token);
		if ($result==true){
			$this->set_response(['status' =>TRUE,'message'=>'success'], REST_Controller::HTTP_OK); 
		}
		else{
			$this->set_response(['status' =>FALSE,'message'=>'Invalid User Access Token'], REST_Controller::HTTP_OK);
		}
	}
	
	// File Upload API
	
	function fileUpload_post(){
		$folderPath = $config['upload_path'] = 'uploads/';
		$config['allowed_types'] = '*';   
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('file')){
			$this->set_response(['status' =>FALSE,'message'=>'Upload Error'], REST_Controller::HTTP_NOT_FOUND);
		}else{
			$data1 = $this->upload->data();
			$filePath1=$folderPath.$data1['file_name'];
			$this->set_response(['status' =>TRUE,'message'=>$filePath1], REST_Controller::HTTP_OK);
		}
	}
	
	function mobileFileUpload_post(){
		// $folderPath = $config['upload_path'] = 'uploads/';
		// $config['allowed_types'] = '*';   
		// $this->load->library('upload', $config);
		// $this->upload->initialize($config);
		// if(!$this->upload->do_upload('file')){
			// // print_r($_FILES);exit;
			// $this->set_response(['status' =>FALSE,'message'=>'Upload Error'], REST_Controller::HTTP_NOT_FOUND);
		// }else{
			// $data1 = $this->upload->data();
			// // print_r($_FILES);
			// // print_r($data1);
			// // exit;
			// $filePath1=$folderPath.$data1['file_name'];
			// $this->set_response($filePath1, REST_Controller::HTTP_OK);
		// }
		$getFile=$_FILES['file'];
		//print_r($getFile);exit;
		$result=$this->GeneralMod->mfileUpload($getFile);
		//print_r($result);exit;
		if ($result){
			$this->set_response($result, REST_Controller::HTTP_OK); 
		}
		else{
			$this->set_response(['status' =>FALSE,'message'=>'Invalid User Access Token'], REST_Controller::HTTP_OK);
		}
		
	}
	
	function mFileUpload_post(){
		// $folderPath = $config['upload_path'] = 'application/uploads/';
		// $config['allowed_types'] = '*';   
		// $this->load->library('upload', $config);
		// $this->upload->initialize($config);
		// if(!$this->upload->do_upload('file')){
			// $this->set_response(['status' =>FALSE,'message'=>'Upload Error'], REST_Controller::HTTP_NOT_FOUND);
		// }else{
			// $data1 = $this->upload->data();
			// $filePath1=$folderPath.$data1['file_name'];
			// $this->set_response(['status' =>TRUE,'message'=>$filePath1], REST_Controller::HTTP_OK);
		// }
		$file=$this->response($_FILES);
		$cpt = count($_FILES['file']['name']);
		$this->set_response(['status' =>TRUE,'message'=>$cpt], REST_Controller::HTTP_OK);
		for($i=0; $i<$cpt-1; $i++){
			if(!empty($_FILES['file']['name'][$i])){
				$_FILES['file']['name']= $_FILES['file']['name'][$i];
				$_FILES['file']['type']= $_FILES['file']['type'][$i];
				$_FILES['file']['tmp_name']= $_FILES['file']['tmp_name'][$i];
				$_FILES['file']['error']= $_FILES['file']['error'][$i];
				$_FILES['file']['size']= $_FILES['file']['size'][$i];
				$config['upload_path'] = 'application/uploads/';
				$config['allowed_types'] = '*';
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if(!$this->upload->do_upload('file')){
					$this->set_response(['status' =>FALSE,'message'=>'Upload Error'], REST_Controller::HTTP_NOT_FOUND);
				}else{
					$data1 = $this->upload->data();
					$filePath1=$folderPath.$data1['file_name'];
					$this->set_response(['status' =>TRUE,'message'=>$filePath1], REST_Controller::HTTP_OK);
				}
			}
		}
		
      // $this->load->library('upload', $config);
      // $this->upload->initialize($config);
      // $this->upload->do_upload('file');
      // $file_data=$this->upload->data();
      // $file_name=$file_data['file_name'];
      // $file_size=$file_data['file_size'];
      // $file_size=$file_size/1024;//for KB
      // $target_file =base_url().$config['upload_path'] . $file_name;
// above $files = $_FILES;
		
	}
	
	function pushReg_post(){
		$regId = $this->post('regId');
		$users=$this->GeneralMod->addPushReg($regId);
		if (!empty($users)){
			$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'regId could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	function logout_get(){
		$token=$this->get('access_token');
		$access_token=$this->GeneralMod->logoutDetail($token);
		if($access_token){
			$this->session->unset_userdata('user_token');
			$this->session->unset_userdata('USER_ID');
			$this->session->unset_userdata('USER_FIRST_NAME');
			$this->session->unset_userdata('USER_LAST_NAME');
			$this->session->unset_userdata('USER_EMAIL');
			$this->session->unset_userdata('USER_ROLE_ID');
			$this->session->unset_userdata('USER_READ');
			$this->session->unset_userdata('USER_WRITE');
			$this->session->unset_userdata('USER_EDIT');
			$this->session->unset_userdata('USER_DELETE');
			$this->set_response(['status' =>TRUE,'message'=>'Log Out Successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
	}
	
	// Send Email via MailGun
	
	function sendMail_get(){
		$to=$this->get('mailId');
		//$to='karthik@appnlogic.com';
		$result=mailGun($to);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Email sending failed'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	
	function sendMail_post(){
		$to=$this->post('mailId');
		//$to='karthik@appnlogic.com';
		$result=mailGun($to);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Email sending failed'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	
	// Send SMS via Way2Sms
	
	function smsGateway_get(){
		// $uid=$this->get('user');
		// $pwd=$this->get('pwd');
		$phone=$this->get('phone');
		$msg=$this->get('msg');
		$users=$this->GeneralMod->sendWay2SMS($phone);
		print_r($users);exit;
		if ($users==true){
			$this->set_response(['status' =>TRUE,'message'=>'sms send successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'sms could not be send'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
	
	// Set Password Details
	
	function setPasswordDetail_get(){
		$token=$this->get('token');
		$result=$this->profilemodel->getPasswordDetail($token);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
		}
		else{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Invalid token verification'
			], REST_Controller::HTTP_OK);
		}
	}
	
	function checkOtp_get(){
		$id=$this->get('profileId');
		$otp=$this->get('otp');
		$result=$this->GeneralMod->checkOtp($id,$otp);
		if ($result==true){
			$this->set_response(['status' =>TRUE,'message'=>'Success'], REST_Controller::HTTP_OK); 
		}
		else{
			$this->set_response(['status' => FALSE,'message' => 'Invalid Your OTP'], REST_Controller::HTTP_OK);
		}
	}
	
	function setPasswordDetail_post(){
		$data=$this->post('userData');
		$result=$this->profilemodel->setPassword($data);
		if ($result==true){
			$this->set_response(['status' =>TRUE,'message'=>'Your password created succesfully'], REST_Controller::HTTP_OK); 
		}
		else{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Invalid token verification'
			], REST_Controller::HTTP_OK);
		}
	}
	
	// Reset Password 
	
	function passwordReset_get(){
    	$data=$this->get('userData');
		$result=$this->profilemodel->passwordReset($data);
		if (!empty($result)){
			$msg="Dear Sir/Madam,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Your Rubycampus application password has been reset successfully.Please Click the below button and set your password <a href='http://192.168.1.139/Projects/campus/#/verification/$result'> Click Here </a> <br><br><br>Thanks &amp; Regards,<br>Admin<br>";
			$res=mailVerify($data,$msg);
			$this->set_response(['status' =>TRUE,'message'=>'Please check your mail'], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Invalid user email or phone'], REST_Controller::HTTP_OK);
		} 			
	}
	
	// Mobile API's
	function instituteDetail_get(){
		$result=$this->GeneralMod->departmentDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'There is no department details'], REST_Controller::HTTP_OK);
		} 
	}
	
	
	function department_get(){
		$result=$this->GeneralMod->departmentDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'There is no department details'], REST_Controller::HTTP_OK);
		} 
	}
	
	function course_get(){
		$result=$this->GeneralMod->courseDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'There is no course details'], REST_Controller::HTTP_OK);
		} 
	}
	
	function courseBatch_get(){
		$result=$this->GeneralMod->course_batchDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'There is no course_batch details'], REST_Controller::HTTP_OK);
		} 
	}
	
}
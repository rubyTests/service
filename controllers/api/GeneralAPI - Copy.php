<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class GeneralAPI extends REST_Controller {    
    function GeneralAPI()
    {
		parent::__construct();
		$this->load->model('GeneralMod');
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
    
    $user_token=$valu['access_token'];
    $this->session->set_userdata('user_token',$user_token);
    // $path=APPPATH;
    // echo $path;
    // Free up the resources $curl is using
    curl_close($curl);
    return $user_token;
    }

    // Login Details 
    function setSessionData($users){
    	$USER_ID = $users[0]['USER_ID'];
    	$USER_FIRST_NAME = $users[0]['USER_FIRST_NAME'];
    	$USER_LAST_NAME = $users[0]['USER_LAST_NAME'];
    	$USER_EMAIL = $users[0]['USER_EMAIL'];
    	$USER_ROLE_ID = $users[0]['USER_ROLE_ID'];
    	$USER_READ = $users[0]['USER_READ'];
    	$USER_WRITE = $users[0]['USER_WRITE'];
    	$USER_EDIT = $users[0]['USER_EDIT'];
    	$USER_DELETE = $users[0]['USER_DELETE'];
    	$this->session->set_userdata('USER_ID',$USER_ID);
    	$this->session->set_userdata('USER_FIRST_NAME',$USER_FIRST_NAME);
    	$this->session->set_userdata('USER_LAST_NAME',$USER_LAST_NAME);
    	$this->session->set_userdata('USER_EMAIL',$USER_EMAIL);
    	$this->session->set_userdata('USER_ROLE_ID',$USER_ROLE_ID);
    	$this->session->set_userdata('USER_READ',$USER_READ);
    	$this->session->set_userdata('USER_WRITE',$USER_WRITE);
    	$this->session->set_userdata('USER_EDIT',$USER_EDIT);
    	$this->session->set_userdata('USER_DELETE',$USER_DELETE);

    }
    function user_get(){
    	$sessionToken=$this->session->userdata('user_token');
    	$this->response(['status' =>TRUE,'access_token'=> $sessionToken], REST_Controller::HTTP_OK); // OK 
			exit;
    }
	function login_get(){
		$email = $this->get('USER_EMAIL');
		$pwd = $this->get('USER_PASSWORD');
		$sessionToken=$this->session->userdata('user_token');
		// echo $this->session->userdata('USER_FIRST_NAME');
		// echo 'sessionToken';
		//  exit;
		if($sessionToken!=''){
			$this->response(['status' =>TRUE,'access_token'=> $sessionToken], REST_Controller::HTTP_OK); // OK 
			exit;
		}
		// $this->session->set_userdata('user_token','test');
		// echo "came";
		//exit;
		if($email==NULL){
			$this->set_response([
				'status' => FALSE,
				'message' => 'Login Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$user=$this->GeneralMod->getLoginDetail($email,$pwd);
			
			if (!empty($user)){
				$this->setSessionData($user);
				$type='password';
				//$type='client';
				$client_id='123';
				//$client_secret='123456';
				$users=$this->tokenGen($type,$email,$pwd,$client_id);
				//$users=$this->tokenGen($type,$client_id,$client_secret);
				//$users=json_decode($users);
				$this->set_response(['status' =>TRUE,'access_token'=> $users,'message'=>$user], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Login Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	// File Upload API
	
	function fileUpload_post(){
		$folderPath = $config['upload_path'] = 'application/uploads/';
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
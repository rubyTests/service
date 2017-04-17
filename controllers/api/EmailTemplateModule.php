<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
require APPPATH . '/helpers/mailGun/test.php';
class EmailTemplateModule extends REST_Controller {  
    function EmailTemplateModule()
    {
		parent::__construct();
		$this->load->model('emailtemplatemodel');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		//$userIDByToken="";
		checkTokenAccess();
		checkAccess();
		
    }

    // Add and Edit Email Template
	
    function EmailTemplate_post()
    {
    	$id = $this->post('tempId');
		$data['E_TEMP_NAME']=$this->post('name');
		$data['E_TEMP_SUBJECT']=$this->post('subject');
		$data['E_TEMP_BODY']=$this->post('body');
		$data['E_TEMP_USER_ID']=$this->post('userId');
		if($id==NULL){
			$result=$this->emailtemplatemodel->addEmailTemplate($data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Email Template inserted Successfully'], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->emailtemplatemodel->editEmailTemplate($id,$data);
			if($result==true){
				$this->set_response(['status' =>TRUE,'message'=>"Email Template Updated successfully"], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
	
	function EmailTemplate_get(){
		$id = $this->get('tempId');
		if($id==NULL){
			$users=$this->emailtemplatemodel->getEmailTemplateAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Email Template Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$users=$this->emailtemplatemodel->getEmailTemplate($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Email Template Detail could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function EmailTemplate_delete(){
		$id = $this->delete('tempId');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Email Template could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->emailtemplatemodel->deleteEmailTemplate($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Email Template Detail Deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Email Template could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	
	// Send Email via MailGun
	
	function sendMail_get(){
		$to=$this->get('mailId');
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
	
}
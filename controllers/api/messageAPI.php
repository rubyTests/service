<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class messageAPI extends REST_Controller {    
    function messageAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('messagemodel');
		$this->load->library('Curl');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    
	function profileData_get(){
		$result=$this->messagemodel->getProfileDataDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function composeMsg_post(){
		$data['recipient']=$this->post('recipient');
		$data['subject']=$this->post('subject');
		$data['message']=$this->post('message');
		$data['profile_id']=$this->post('profile_id');
		$data['from_id']=$this->post('from_id');
		$result=$this->messagemodel->addComposeMsgDetails($data);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>'Message Send Successfully'], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}

	function messageHeaderList_get(){
		$profileId = $this->get('id');
		$result=$this->messagemodel->getMessageHeaderList($profileId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}

	function messageDetailById_get(){
		$Id = $this->get('id');
		$result=$this->messagemodel->getMessageDetailById($Id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function lCategory_delete(){
		$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->librarymodel->deleteCategoryDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Category Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
}
?>
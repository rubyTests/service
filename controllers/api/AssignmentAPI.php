<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class AssignmentAPI extends REST_Controller {    
    function AssignmentAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('assignment_model');
		$this->load->library('Curl');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    
	//-------------------------------------  Assignment Detail ---------------------------------------------------
    function assignmentDetail_post()
    {
    	$data['id']=$this->post('id');
    	$data['name']=$this->post('name');
    	$data['content']=$this->post('content');
    	$data['course_id']=$this->post('course_id');
    	$data['batch_id']=$this->post('batch_id');
    	$data['subject_id']=$this->post('subject_id');
    	$data['due_date']=date("Y-m-d", strtotime($this->post('due_date')));
    	$data['userId']=$this->post('userId');
		$result=$this->assignment_model->assignmentDetails($data);
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
        	$result=$this->assignment_model->getAllAssignment_details();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Data could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->assignment_model->getAssignment_details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Assignment data could not be found'
				], REST_Controller::HTTP_OK);
			}
        }    			
	}

	function assignmentDetail_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->assignment_model->deleteAssignmentData($id);
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
				$this->set_response(['status'=>FALSE,'message'=>$message], REST_Controller::HTTP_OK);
			}
		}  
    }


    // Studebt assignment details

    function stuAssignmentDetail_get(){
    	$id=$this->get('profileId');
    	$roleId=$this->get('roleId');
    	$result=$this->assignment_model->getAllstuAssignmentDetail($id,$roleId);
    	if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}    			
	}

    // Assignment status marking

    function assignmentStatus_post()
    {
    	$data['profileId']=$this->post('profileId');
    	$data['batch_id']=$this->post('batch_id');
    	$data['assignment_id']=$this->post('assignment_id');
		$result=$this->assignment_model->assignmentStatus($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function getStuDetailBatchID_get(){
    	$id=$this->get('id');
    	$assess=$this->get('assess');
    	if ($id == null)
        {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }else{
        	$result=$this->assignment_model->getStuDetailBatchID($id,$assess);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Assignment data could not be found'
				], REST_Controller::HTTP_OK);
			}
        }
    }
}
?>
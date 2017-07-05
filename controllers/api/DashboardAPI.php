<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class DashboardAPI extends REST_Controller {    
    function DashboardAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('dashboardmodel');
		$this->load->library('Curl');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
	
	// Admin Dashboard 
    
	function adminDashboard_get(){
		$result=$this->dashboardmodel->adminDashboard();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		} 			
	}
	
	function todoAdmin_post(){
		$data['title']=$this->post('title');
		$data['description']=$this->post('description');
		$data['date']=$this->post('date');
		$data['important']=$this->post('important');
		$result=$this->dashboardmodel->addTodoAdmin($data);
		if ($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function todoAdmin_get(){
		$result=$this->dashboardmodel->getTodoAdmin();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		} 			
	}
	
	function todoAdmin_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->dashboardmodel->deleteTodoAdmin($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		} 
    }
	
	// Student Dashboard 
	
	function studentDashboard_get(){
		$pId=$this->get('profileId');
		$result=$this->dashboardmodel->studentDashboard($pId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		} 			
	}
	
	function todoStudent_post(){
		$data['title']=$this->post('title');
		$data['description']=$this->post('description');
		$data['date']=$this->post('date');
		$data['important']=$this->post('important');
		$result=$this->dashboardmodel->addTodoStudent($data);
		if ($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function todoStudent_get(){
		$result=$this->dashboardmodel->getTodoStudent();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		} 			
	}
	
	function todoStudent_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->dashboardmodel->deleteTodoStudent($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		} 
    }
	
	function stuAssignmentShow_get(){
		$proId=$this->get('profileId');
		$result=$this->dashboardmodel->getStuAssignmentShow($proId);
		if($result){
			$this->set_response(['status'=>TRUE,'message'=>$result], REST_Controller::HTTP_OK);
		}else{
			$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
		}
	}
	
}
?>
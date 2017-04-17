<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class ReviewAPI extends REST_Controller {    
    function ReviewAPI()
    {
		parent::__construct();
		
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('reviewmodel');
    }

    // feedback 
	
    function feedback_post()
    {
    	$data['range']=$this->post('range');
    	$data['service']=$this->post('service');
    	$data['ambience']=$this->post('amlience');
    	$data['suggestion']=$this->post('suggestion');
    	$data['requirement']=$this->post('requirement');
    	$data['overall_experiance']=$this->post('rating');
    	$data['name']=$this->post('name');
    	$data['mobile_number']=$this->post('mobile'); 
    	$data['email']=$this->post('email');
    	$data['place']=$this->post('place');
		$result=$this->response($_POST);
		//$result=$this->reviewmodel->addComments($data);
		if(!empty($result)){
			$this->set_response(['status' =>TRUE,'admission_no'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    // get feedback
    function feedback_get(){
		$result=$this->reviewmodel->getfeedbackDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'comments Detail could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}
}
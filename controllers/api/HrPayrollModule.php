<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class HrPayrollModule extends REST_Controller {    
    function HrPayrollModule()
    {
		parent::__construct();
		$this->load->model('payrollmod_api');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
    }

    // ----------------------------------------- Payroll Category ----------------------------------------------------------

    function payrollCategory_post()
    {
    	$result=$this->payrollmod_api->PayrollCategory();
    	if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    } 
    function payrollGroup_post()
    {
        $result=$this->payrollmod_api->payrollGroup();
        if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    }
    function payslipGeneration_post() {
        $result=$this->payrollmod_api->AddPayslipGeneration();
        if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    }
}
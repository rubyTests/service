<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class PayrollPayslipAPI extends REST_Controller {
    function PayrollPayslipAPI()
    {
		parent::__construct();
		$this->load->model('payroll_payslip');
		$this->load->model('employee_mgmnt_model');
		$this->load->helper('dompdf_helper');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();

		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'manisrikan@gmail.com', // change it to yours
			'smtp_pass' => 'mani16121993', // change it to yours
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		);
		$this->load->library('email', $config);
    }

    // Payitem Details 	
	function PayItem_post(){
		// print_r($this->post());exit;
		$id = $this->post('id');
		$data['NAME']=$this->post('name');
		$data['TYPE']=$this->post('type');
		if($id==NULL){
			$result=$this->payroll_payslip->addPayItem($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->payroll_payslip->editPayItem($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	
	function PayItem_get(){
		$id = $this->get('id');
		if($id==NULL){
			$result=$this->payroll_payslip->getPayItemDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$result=$this->payroll_payslip->getParticularPayItem($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	
	function checkPayItem_get(){
		$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->payroll_payslip->checkItemAssignedorNot($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

	function PayItem_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->payroll_payslip->deletepayItem($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    // PayStructure
    function payStructureDetail_post(){
    	//print_r($this->post());exit;
    	$id = $this->post('id');
		$data['PAY_STRU_NAME']=$this->post('structure_name');
		$data['FREQUENCY']=$this->post('frequency');
		$data['PAYITEM_LIST']=$this->post('paydata');
		if($id==NULL){
			$result=$this->payroll_payslip->addPayStructure($data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->payroll_payslip->editPayStructure($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }

    function payStructureDetail_get(){
		$id = $this->get('id');
		if($id==NULL){
			$result=$this->payroll_payslip->getPayStructureDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$result=$this->payroll_payslip->getParticularPayStructure($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function payStructureDetail_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->payroll_payslip->deleteParticularPayStructure($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function paystructureDelete_delete(){
    	$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->payroll_payslip->deleteSinglePayStructureDetails($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function fetchEmployeeList_get(){
    	$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->payroll_payslip->getEmployeeList($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function fetchEmployeeDetails_get(){
    	$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->payroll_payslip->getEmployeeDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function fetchPayStructureDetails_get(){
    	$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->payroll_payslip->getPayStrcutureDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    function assignPayroll_post(){
		// print_r($this->post());exit;
		$id = $this->post('emp_id');
		$data['STRUCTURE_ID']=$this->post('stru_id');
		$data['BASIC_PAY']=$this->post('basic_pay');
		if($id==NULL){
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}else{
			$result=$this->payroll_payslip->assignPayroll($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function getAssignEmployee_get(){
		$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->payroll_payslip->getAssignedEmployeeDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function assignEmployee_post(){
		$id = $this->post('id');
		if($id==NULL){
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}else{
			$result=$this->payroll_payslip->changeAssignEmployee($id);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function payStructure_get(){
		$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->payroll_payslip->fetchPayStructureName($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function getEmployeeforpayslip_get(){
		$result=$this->payroll_payslip->fetchEmpoyeeForPayslip();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_fetchEmployeePayDetailsFOUND (404) being the HTTP response code
		}
	}
	function fetchEmployeePayDetails_get(){
		$id = $this->get('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->payroll_payslip->fetchparticularemployeePaydetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function payslipGeneration_post(){
		$id = $this->post('empid');
		$data['STRUCTURE_ID']=$this->post('struc_id');
		$data['PAYSLIP_ID']=$this->post('payslipId');
		$data['PAYSLIP_Status']=$this->post('payStatus');
		$data['GENERATION_DATE']=$this->post('generatoin_date');
		$data['DEFAULT']=$this->post('default_data');
		$data['ADDON']=$this->post('addon_data');
		$data['Net_pay']=$this->post('Net_pay');
		$data['from_date']=$this->post('fromdate');
		$data['end_date']=$this->post('enddate');
		if($id==NULL){
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}else{
			$result=$this->payroll_payslip->gererateEmployeePayslip($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function fetchAddonPaySlipDetails_get(){
		$payslip = $this->get('payslipId');
		if($payslip==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->payroll_payslip->fetchAddonPayslipDetails($payslip);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function fetchPaySlipAddonDetails_get(){
		$Empid = $this->get('empId');
		$Struc_id = $this->get('pay_struc_ID');
		$gen_date = $this->get('genDate');
		if($Empid==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->payroll_payslip->fetchPayslipAddonDetails($Empid,$Struc_id,$gen_date);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function employeePayslipDetails_get(){
		$empid = $this->get('id');
		if($empid==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			$result=$this->payroll_payslip->fetchEmployeePayslipDetails($empid);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}

	// Payslip generation
	function index_post(){
		// echo 'sasas';exit;
		$json = file_get_contents('php://input');
      	$data = json_decode($json, TRUE);
      	$html=$this->load->view('payslip_view',$data,true);
      	pdf_create($html,"data",$stream=TRUE,'portrait','1.0');
	}

	function payslipReport_get(){
		$deptid = $this->get('dept_id');
		$fromdate = $this->get('fromdate');
		$uptodate = $this->get('uptodate');
		if($deptid==NULL){
			$result=$this->payroll_payslip->fetchPayslipDetailforAllEmployee();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$result=$this->payroll_payslip->fetchPaylipDetailbasedonDept($deptid,$fromdate,$uptodate);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function fetchApproveStatus_get(){
		$status = $this->get('status');
		if($status==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->payroll_payslip->fetchApproveStatusDetails($status);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function updatePayslipStatus_post(){
		$payid = $this->post('payslipID');
		$data['payStatus']=$this->post('status');
		if($payid==NULL){
			$this->set_response(['status' =>FALSE,'message'=>"No data Found"], REST_Controller::HTTP_CREATED);
		}else{
			$result=$this->payroll_payslip->updatepayStatus($payid,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function checkpayitemAvailableorNot_get(){
		$id = $this->get('id');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->payroll_payslip->getPayItemusingPaystructure($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function payslipDelete_delete(){
		$id = $this->delete('id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->payroll_payslip->deleteEmpoyeePayslip($id);
			if ($result!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Record deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}
	function checkPayItemId_get(){
		$id = $this->get('id');
		$paystru_Id = $this->get('payStrID');
		if($id==NULL){
			$this->set_response([
			'status' => FALSE,
			'message' => 'Record could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$result=$this->payroll_payslip->checkANyPayItemAssigned_to_employee($id,$paystru_Id );
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Record could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}

	function updatePayslipGeneration_post(){
		$id = $this->post('payslipid');
		$data['Net_pay']=$this->post('netpay');
		if($id==NULL){
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}else{
			$result=$this->payroll_payslip->regererateEmployeePayslip($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
	}
	function mailSendAfterGeneration_post(){
		$this->email->set_newline("\r\n");
		$this->email->from('rvijayaraj24@gmail.com'); // change it to yours
		$this->email->to('vijayaraj@appnlogic.com');
		$this->email->subject('Rubycampus');
		$this->email->message('Payslip Generation');
		if (!$this->email->send())
		{
			show_error($this->email->print_debugger());
		}
		else{
			echo 'Mail Sended Successfully';
		}
		$this->email->clear(TRUE);
	}
	function sendStatusEmail_post(){
		$payslipID = $this->post('payslipID');
		$status = $this->post('status');
		// $result=$this->payroll_payslip->fetchEmployeeDetailforEmailSending($payslipID);
		// $stack = array($result[0]['MAILID']);
		// array_push($stack, 'karthik@appnlogic.com');
		// print_r($stack);exit();

		if($status=='Approved'){
			$result=$this->payroll_payslip->fetchEmployeeDetailforEmailSending($payslipID);
			if(isset($result[0]['MAILID'])){
				$stack = array($result[0]['MAILID']);
				array_push($stack, 'karthik@appnlogic.com');
				for ($i=0; $i < count($stack); $i++) { 
					if(isset($stack[$i])){
						$data=$this->employee_mgmnt_model->addMailDetails($stack[$i]);
						if($data){
							$this->email->set_newline("\r\n");
							$this->email->from('rvijayaraj24@gmail.com'); // change it to yours
							$this->email->to($stack[$i]);
							$this->email->subject('Rubycampus');
							$this->email->message('Approved Payslip');
							if (!$this->email->send())
							{
								show_error($this->email->print_debugger());
							}else{
								$this->employee_mgmnt_model->updateMailDetails($data['EMAIL_LOG_ID']);
							}
							$this->email->clear(TRUE);	
						}
					}
				}
			}
		}else {
			$adminEmail='vijayaraj@appnlogic.com';
			$data=$this->employee_mgmnt_model->addMailDetails($adminEmail);
			if($data){
				$this->email->set_newline("\r\n");
				$this->email->from('rvijayaraj24@gmail.com'); // change it to yours
				$this->email->to($adminEmail);
				$this->email->subject('Rubycampus');
				$this->email->message('Reject Payslip');
				if (!$this->email->send())
				{
					show_error($this->email->print_debugger());
				}else{
					$this->employee_mgmnt_model->updateMailDetails($data['EMAIL_LOG_ID']);
					echo 'Mail send successfully';
				}
				$this->email->clear(TRUE);	
			}
		}
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class FinanceTxnModule extends REST_Controller {    
    function FinanceTxnModule()
    {
		parent::__construct();
        header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('financetxnmodel');
        $userIDByToken="";
        checkTokenAccess();
        checkAccess();
    }

    // ------------------------------------ Finance Expense -----------------------------------------------

    function expense_post()
    {
    	$data['FINC_TXN_EX_ID']=$this->post('FINC_TXN_EX_ID');
        $data['FINC_TXN_EX_CA_ID']=$this->post('FINC_TXN_EX_CA_ID');
        $data['FINC_TXN_EX_TITLE']=$this->post('FINC_TXN_EX_TITLE');
        $data['FINC_TXN_EX_DESC']=$this->post('FINC_TXN_EX_DESC');
        $data['FINC_TXN_EX_AMT']=$this->post('FINC_TXN_EX_AMT');
        $data['FINC_TXN_EX_DT']=$this->post('FINC_TXN_EX_DT');
        $data['FINC_TXN_EX_STATUS']=$this->post('FINC_TXN_EX_STATUS');
        $result=$this->financetxnmodel->addExpenseData($data);
        if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result['message'],'FINC_TXN_EX_ID'=>$result['FINC_TXN_EX_ID']], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    }
    function expense_get(){
    	$id=$this->get('id');
        if ($id == null)
        {
            $result=$this->financetxnmodel->getAllExpense_details();
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response([ 'status' => FALSE, 'message' => 'Employee data could not be found' ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $result=$this->financetxnmodel->getExpense_details($id);
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response(['status' => FALSE, 'message' => 'Employee data could not be found'], REST_Controller::HTTP_NOT_FOUND);
            }
        }	
	}

    function expense_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->financetxnmodel->deleteExpense($id);
			if($result!=0){
				$message = [
				'id' => $id,
				'message' => 'Record Deleted Successfully'
				];
				$this->set_response(['status'=>TRUE,'message'=>$message], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_NOT_FOUND);
			}
		}  
    }

    // ------------------------------------ Finance Income ----------------------------------------------------------------------

    function income_post()
    {
        $data['FINC_TXN_IN_ID']=$this->post('FINC_TXN_IN_ID');
        $data['FINC_TXN_IN_CA_ID']=$this->post('FINC_TXN_IN_CA_ID');
        $data['FINC_TXN_IN_TITLE']=$this->post('FINC_TXN_IN_TITLE');
        $data['FINC_TXN_IN_DESC']=$this->post('FINC_TXN_IN_DESC');
        $data['FINC_TXN_IN_AMT']=$this->post('FINC_TXN_IN_AMT');
        $data['FINC_TXN_IN_DT']=$this->post('FINC_TXN_IN_DT');
        $data['FINC_TXN_IN_STATUS']=$this->post('FINC_TXN_IN_STATUS');
        $result=$this->financetxnmodel->addIncomeData($data);
        if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result['message'],'FINC_TXN_IN_ID'=>$result['FINC_TXN_IN_ID']], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    }
    function income_get(){
        $id=$this->get('id');
        if ($id == null)
        {
            $result=$this->financetxnmodel->getAllIncome_details();
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response([ 'status' => FALSE, 'message' => 'Employee data could not be found' ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $result=$this->financetxnmodel->getIncome_details($id);
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response(['status' => FALSE, 'message' => 'Employee data could not be found'], REST_Controller::HTTP_NOT_FOUND);
            }
        }      
    }

    function income_delete(){
        $id=$this->delete('id');
        if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
            $result=$this->financetxnmodel->deleteIncome($id);
            if($result!=0){
                $message = [
                'id' => $id,
                'message' => 'Record Deleted Successfully'
                ];
                $this->set_response(['status'=>TRUE,'message'=>$message], REST_Controller::HTTP_OK);
            }else{
                $this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_NOT_FOUND);
            }
        }  
    }
    function categoryList_get(){
        $result=$this->financetxnmodel->fetchCategory();
        if (!empty($result)){
            $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->set_response([ 'status' => FALSE, 'message' => 'Employee data could not be found' ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
?>
   
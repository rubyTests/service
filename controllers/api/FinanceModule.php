<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class FinanceModule extends REST_Controller {    
    function FinanceModule()
    {
		parent::__construct();
        header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('financemodel');
        $userIDByToken="";
        checkTokenAccess();
        checkAccess();
    }

    // ------------------------------------ Finance Asset ----------------------------------------------------------------------

    function Asset_post()
    {
        $data['FINC_AS_ID']=$this->post('FINC_AS_ID');
        $data['FINC_AS_TITLE']=$this->post('FINC_AS_TITLE');
        $data['FINC_AS_DESC']=$this->post('FINC_AS_DESC');
        $data['FINC_AS_AMT']=$this->post('FINC_AS_AMT');
        $result=$this->financemodel->addAssetData($data);
        if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result['message'],'FINC_AS_ID'=>$result['FINC_AS_ID']], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    }
    function Asset_get(){
    	$id=$this->get('id');
        if ($id == null)
        {
            $result=$this->financemodel->getAllAsset_details();
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                'status' => FALSE,
                'message' => 'Asset data could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }else{
            $result=$this->financemodel->getAsset_details($id);
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response([
                'status' => FALSE,
                'message' => 'Asset data could not be found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
	}

    function Asset_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->financemodel->deleteAsset($id);
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
                $this->set_response(['status'=>FALSE,'message'=>$message], REST_Controller::HTTP_NOT_FOUND);
            }
		}  
    }

    // ---------------------------------------------- Finace Liability ------------------------------------------------------

    function liability_post()
    {
        $data['FINC_LI_ID']=$this->post('FINC_LI_ID');
        $data['FINC_LI_TITLE']=$this->post('FINC_LI_TITLE');
        $data['FINC_LI_DESC']=$this->post('FINC_LI_DESC');
        $data['FINC_LI_AMT']=$this->post('FINC_LI_AMT');
        $result=$this->financemodel->addLiabilityData($data);
        if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result['message'],'FINC_LI_ID'=>$result['FINC_LI_ID']], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    }
    function liability_get(){
        $id=$this->get('id');
        if ($id == null)
        {
            $result=$this->financemodel->getAllLiability_details();
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response([ 'status' => FALSE, 'message' => 'Employee data could not be found' ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $result=$this->financemodel->getLiability_details($id);
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response(['status' => FALSE, 'message' => 'Employee data could not be found'], REST_Controller::HTTP_NOT_FOUND);
            }
        }   
    }

    function liability_delete(){
        $id=$this->delete('id');
        if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
            $result=$this->financemodel->deleteLiabilityData($id);
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

    // --------------------------------------- Category -------------------------------------------------------------

    function category_post()
    {
        $data['FINC_CA_ID']=$this->post('FINC_CA_ID');
        $data['FINC_CA_NAME']=$this->post('FINC_CA_NAME');
        $data['FINC_CA_DESC']=$this->post('FINC_CA_DESC');
        $data['FINC_CA_INCOME_YN']=$this->post('FINC_CA_INCOME_YN');
        $result=$this->financemodel->addFinanceCategory($data);
        if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result['message'],'FINC_CA_ID'=>$result['FINC_CA_ID']], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    }
    function category_get(){
        $id=$this->get('id');
        if ($id == null)
        {
            $result=$this->financemodel->getAllFinanceCategory();
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response([ 'status' => FALSE, 'message' => 'Employee data could not be found' ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $result=$this->financemodel->getFinanceCategory($id);
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response(['status' => FALSE, 'message' => 'Employee data could not be found'], REST_Controller::HTTP_NOT_FOUND);
            }
        }  
    }

    function category_delete(){
        $id=$this->delete('id');
        if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
            $result=$this->financemodel->deleteFinanceCategory($id);
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

    // ---------------------------------------------------- Donation ------------------------------------------------------------

    function donation_post()
    {
        $result=$this->financemodel->addDonation();
        if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    }
}
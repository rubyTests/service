<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class HostelAPI extends REST_Controller {    
    function HostelAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('hostelmodel');
		$this->load->library('Curl');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    
	function hostel_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['BUILDING_ID']=$this->post('buildingId');
		if($id==null){
			$result=$this->hostelmodel->addHostel($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->hostelmodel->editHostel($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function hostel_get(){
		$result=$this->hostelmodel->hostelDetails();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function hostelView_get(){
		$result=$this->hostelmodel->hostelView();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function hostel_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hostelmodel->deleteHostelDetails($id);
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
	
	function allocation_get(){
		$id=$this->get('id');
		$result=$this->hostelmodel->allocationDetails($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function allocation_post(){
		$id=$this->post('id');
		$data['RESIDENT_TYPE']=$this->post('type');
		$data['PROFILE_ID']=$this->post('profileId');
		$data['HOSTEL_ID']=$this->post('buildingId');
		$data['BLOCK_ID']=$this->post('blockId');
		$data['ROOM_ID']=$this->post('roomId');
		$data['DATE']=$this->post('date');
		$data['REASON']=$this->post('reason');
		if($id==null){
			$result=$this->hostelmodel->addAllocation($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Room Allocated Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->hostelmodel->editAllocation($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Room Transferred Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function allocationView_get(){
		$result=$this->hostelmodel->allocationView();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function TranferData_get(){
		$id=$this->get('profileId');
		$result=$this->hostelmodel->TranferData($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function allocation_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hostelmodel->deleteAllocationDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		} 
    }
	
	function TranferView_get(){
		$id=$this->get('profileId');
		$result=$this->hostelmodel->TranferView();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function Tranfer_get(){
		$id=$this->get('profileId');
		$result=$this->hostelmodel->hTranfer($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function TranferView_delete(){
		$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hostelmodel->deleteTransferDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function hostelStudentDetail_get(){
		$id=$this->get('batchId');
		$result=$this->hostelmodel->hostelStudentDetail($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function hostelEmployeeDetail_get(){
		$id=$this->get('deptId');
		$result=$this->hostelmodel->hostelEmployeeDetail($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function allocateStudentDetail_get(){
		$id=$this->get('batchId');
		if($id==null){
			$result=$this->hostelmodel->allocateAllStudentDetail();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->hostelmodel->allocateStudentDetail($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function allocateEmployeeDetail_get(){
		$result=$this->hostelmodel->allocateEmployeeDetail();
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function vacate_post(){
		$id=$this->post('id');
		$data['RESIDENT_TYPE']=$this->post('type');
		$data['PROFILE_ID']=$this->post('profileId');
		$data['DATE']=$this->post('date');
		$data['REASON']=$this->post('reason');
		if($id==null){
			$result=$this->hostelmodel->addVacateDetails($data);
			//print_r($result);exit;
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->hostelmodel->editVacateDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function visitors_post(){
		$id=$this->post('id');
		$data['RESIDENT_TYPE']=$this->post('type');
		$data['PROFILE_ID']=$this->post('profileId');
		$data['NAME']=$this->post('name');
		$data['RELATION']=$this->post('relation');
		$data['DATE']=$this->post('date');
		$data['INTIME']=$this->post('inTime');
		$data['OUTTIME']=$this->post('outTime');
		if($id==null){
			$result=$this->hostelmodel->addVisitorsDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->hostelmodel->editVisitorsDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function vacate_get(){
		$id=$this->get('id');
		$result=$this->hostelmodel->vacateDetails($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		} 			
	}
	
	function vacate_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hostelmodel->deleteVacateDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		} 
    }
	
	function visitors_get(){
		$id=$this->get('id');
		$result=$this->hostelmodel->visitorsDetails($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	function visitors_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->hostelmodel->deleteVisitorsDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		} 
    }
	
	// Get Hostel building blocks 
	function hostelBlocks_get(){
		$id=$this->get('id');
		$result=$this->hostelmodel->getAllHostelBlocks($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
 
}
?>
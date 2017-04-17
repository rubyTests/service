<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class HrLeaveMgmntModule extends REST_Controller {    
    function HrLeaveMgmntModule()
    {
		parent::__construct();
		$this->load->model('leavemgmntmodel');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
    }

    // ------------------------------------ Leave Reset -----------------------------------------------------------------

    function leaveReset_post()
    {
    	$result=$this->leavemgmntmodel->addLeaveResetData();
    	if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    }
    function leaveReset_get(){
    	$id=$this->get('EMP_RES_ID');
        if ($id == null)
        {
            $result=$this->leavemgmntmodel->getAllLeaveReset_details();
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response([ 'status' => FALSE, 'message' => 'Employee data could not be found' ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $result=$this->leavemgmntmodel->getLeaveReset_details($id);
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'aaData'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response(['status' => FALSE, 'message' => 'Employee data could not be found'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
	}

    function leaveReset_delete(){
    	$id=$this->delete('EMP_RES_ID');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->leavemgmntmodel->deleteLeaveReset($id);
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
}
?>
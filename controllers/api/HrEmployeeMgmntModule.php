<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class HrEmployeeMgmntModule extends REST_Controller {    
    function HrEmployeeMgmntModule()
    {
		parent::__construct();
		$this->load->model('employeemgmntmodel');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
    }

    // ------------------------------------ Leave Reset -----------------------------------------------------------------

    function employeeAdmission_post()
    {
        $Images = $this->post('data');
        $ImageSplit = explode(',', $Images);        
        $ImageResult = base64_decode($ImageSplit[1]);
        $im = imagecreatefromstring($ImageResult); 
        if ($im !== false) 
        {
            $fileName = date('Ymdhis') .".png";
            $resp = imagepng($im, $_SERVER['DOCUMENT_ROOT'].'smartedu/upload/'.$fileName);
            imagedestroy($im);
        }
        $data['EMP_ID']=$this->post('EMP_ID');
        $data['EMP_NO']=$this->post('EMP_NO');
        $data['EMP_JOIN_DT']=$this->post('EMP_JOIN_DT');
        $data['EMP_FIRST_NAME']=$this->post('EMP_FIRST_NAME');
        $data['EMP_LAST_NAME']=$this->post('EMP_LAST_NAME');
        $data['EMP_GENDER']=$this->post('EMP_GENDER');
        $data['EMP_DOB']=$this->post('EMP_DOB');
        $data['EMP_MARITAL_STATUS']=$this->post('EMP_MARITAL_STATUS');
        $data['EMP_RELIGION']=$this->post('EMP_RELIGION'); 
        $data['EMP_BLOOD_GROUP']=$this->post('EMP_BLOOD_GROUP');
        $data['EMP_NATIONALITY']=$this->post('EMP_NATIONALITY');
        $data['EMP_PROFILE']=$fileName;
        $data['EMP_DEPT']=$this->post('EMP_DEPT'); 
        $data['EMP_CATEGORY']=$this->post('EMP_CATEGORY');
        $data['EMP_POSITION']=$this->post('EMP_POSITION');
        $data['EMP_GRADE']=$this->post('EMP_GRADE');
        $data['EMP_JOB_TITLE']=$this->post('EMP_JOB_TITLE'); 
        $data['EMP_QUALI'] = $this->post('EMP_QUALI');
        $data['EMP_EXPE_INFO'] = $this->post('EMP_EXPE_INFO');
        $data['EMP_TOT_EXPE'] = $this->post('EMP_TOT_EXPE');
        $data['EMP_ADD_1'] = $this->post('EMP_ADD_1');
        $data['EMP_ADD_2'] = $this->post('EMP_ADD_2');
        $data['EMP_CITY'] = $this->post('EMP_CITY');
        $data['EMP_STATE'] = $this->post('EMP_STATE');
        $data['EMP_PINCODE'] = $this->post('EMP_PINCODE');
        $data['EMP_COUNTRY']=$this->post('EMP_COUNTRY');
        $data['EMP_PHONE']=$this->post('EMP_PHONE');
        $data['EMP_OFF_PHONE']=$this->post('EMP_OFF_PHONE'); 
        $data['EMP_MOBILE'] = $this->post('EMP_MOBILE');
        $data['EMP_EMAIL'] = $this->post('EMP_EMAIL');
        $data['EMP_ACC_NAME'] = $this->post('EMP_ACC_NAME');
        $data['EMP_ACC_NO'] = $this->post('EMP_ACC_NO');
        $data['EMP_BANK_NAME'] = $this->post('EMP_BANK_NAME');
        $data['EMP_BANK_BRANCH_NAME'] = $this->post('EMP_BANK_BRANCH_NAME');
        $data['EMP_PASSPORT_NO'] = $this->post('EMP_PASSPORT_NO');
        $data['EMP_PAN_NO'] = $this->post('EMP_PAN_NO');
        $data['EMP_ADHAR_NO'] = $this->post('EMP_ADHAR_NO');
        $data['EMP_WORK_PERMIT'] = $this->post('EMP_WORK_PERMIT');
        // echo "<pre>";print_r($data);exit;
    	$result=$this->employeemgmntmodel->saveEmployeeAdmission($data);
    	if($result['status']==true){
            $this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
        }else{
            $this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
        }
    }
    function employeeAdmission_get(){
        $id=$this->get('id');
        if ($id == null)
        {
            $result=$this->employeemgmntmodel->fetchEmployeeDetails();
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->set_response([
                'status' => FALSE,
                'message' => 'Employee data could not be found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else {
            $result=$this->employeemgmntmodel->fetchPerticularEmployeeDetails($id);
            if (!empty($result)){
                $this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
            }
            else
            {
                $this->set_response([
                'status' => FALSE,
                'message' => 'Employee data could not be found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
    function employeeAdmission_delete(){
        $id=$this->delete('id');
        if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
            $result=$this->employeemgmntmodel->deleteEmployeeDetails($id);
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
}
?>
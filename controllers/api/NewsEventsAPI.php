<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
require APPPATH . '/helpers/checktoken_helper.php';
class NewsEventsAPI extends REST_Controller {    
    function NewsEventsAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('newseventsmodel');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    
	function NewsEvents_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->newseventsmodel->getAllNewsEvents();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->newseventsmodel->getNewsEvents($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function NewsEvents_post(){
		$id=$this->post('id');
		$data['TITLE']=$this->post('title');
		$data['DESCRIPTION']=$this->post('desc');
		$data['STARTDATE']=date("Y-m-d", strtotime($this->post('startDate')));
		$data['ENDDATE']=date("Y-m-d", strtotime($this->post('endDate')));
		$data['STARTTIME']=$this->post('startTime');
		$data['ENDTIME']=$this->post('endTime');
		$data['SHOW_STATUS']=$this->post('showStatus');
		$data['COURSE_ID']=$this->post('courseId');
		$data['userProfileId']=$this->post('userProfileId');
		if($id==null){
			$result=$this->newseventsmodel->addNewsEvents($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'News&Events Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->newseventsmodel->editNewsEvents($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'News&Events Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function NewsEvents_delete(){
		$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->newseventsmodel->deleteNewsEvents($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'News&Events Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function Rep_Post_get(){ 
		$id=$this->get('id');
		if($id==null){
			$result=$this->newseventsmodel->getAllRepPostDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->newseventsmodel->getRepPostDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function Rep_Post_post(){
		$id=$this->post('rep_id');
		$data['TITLE']=$this->post('rep_title');
		$data['UPLOAD_FILE']=$_FILES;
		$data['CONTENT']=$this->post('rep_content');
		$data['COURSE_ID']=$this->post('courseId');
		$data['REP_CATEGORY_ID']=$this->post('categoryId');
		$data['userProfileId']=$this->post('userProfileId');
		
		if($id==null){
			$result=$this->newseventsmodel->addRepPostDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Repository Post Detail Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->newseventsmodel->editRepPostDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Repository Post Detail Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function Rep_Post_delete(){
		$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->newseventsmodel->deleteRepPostDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
 
	function fetchBookIdViewData_get(){
		$id=$this->get('id');
		if($id==null){
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}else{
			$result=$this->newseventsmodel->fetchBookIdDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	// Mobile Rep Details
	
	public function mRepDetails_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->newseventsmodel->mGetAllRepPostDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->newseventsmodel->mGetRepPostDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	public function mGetCourseBased_get(){
		$id=$this->get('profileId');
		$roleId=$this->get('roleId');
		$result=$this->newseventsmodel->mGetRepCoursebased($id,$roleId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}else{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	
	public function mRepPost_post(){
		$id=$this->post('rep_id');
		$data['TITLE']=$this->post('rep_title');
		$data['UPLOAD_FILE']=$this->post('rep_image');
		$data['CONTENT']=$this->post('rep_content');
		$data['COURSE_ID']=$this->post('courseId');
		$data['REP_CATEGORY_ID']=$this->post('categoryId');
		$data['userProfileId']=$this->post('userProfileId');
		
		if($id==null){
			$result=$this->newseventsmodel->addmRepPostDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Repository Post Detail Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->newseventsmodel->editmRepPostDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Repository Post Detail Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
}
?>
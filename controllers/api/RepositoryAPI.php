<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class RepositoryAPI extends REST_Controller {    
    function RepositoryAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('repositorymodel');
		$this->load->library('Curl');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    
	function Rep_Category_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->repositorymodel->getAllCategoryDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->repositorymodel->getCategoryDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function Rep_Category_post(){
		$id=$this->post('rep_cat_id');
		$data['NAME']=$this->post('rep_cat_name');
		$data['DESC']=$this->post('rep_cat_desc');
		if($id==null){
			$result=$this->repositorymodel->addCategoryDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Category Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->repositorymodel->editCategoryDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Category Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function Rep_Category_delete(){
		$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->repositorymodel->deleteCategoryDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Category Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function Rep_Post_get(){ 
		$id=$this->get('id');
		if($id==null){
			$result=$this->repositorymodel->getAllRepPostDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->repositorymodel->getRepPostDetails($id);
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
		
		if($id==null){
			$result=$this->repositorymodel->addRepPostDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Repository Post Detail Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->repositorymodel->editRepPostDetails($id,$data);
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
			$result=$this->repositorymodel->deleteRepPostDetails($id);
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
			$result=$this->repositorymodel->fetchBookIdDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
}
?>
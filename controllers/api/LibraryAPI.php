<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class LibraryAPI extends REST_Controller {    
    function LibraryAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('librarymodel');
		$this->load->library('Curl');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    
	function lCategory_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->librarymodel->getAllCategoryDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->librarymodel->getCategoryDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function lCategory_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['CODE']=$this->post('code');
		if($id==null){
			$result=$this->librarymodel->addCategoryDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Category Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->librarymodel->editCategoryDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Category Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function lCategory_delete(){
		$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->librarymodel->deleteCategoryDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function lBook_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->librarymodel->getAllBookDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->librarymodel->getBookDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function lBook_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['CATEGORY_ID']=$this->post('categoryId');
		$data['DEPT_ID']=$this->post('deptId');
		$data['SUBJECT_ID']=$this->post('subjectId');
		$data['AUTHOR']=$this->post('author');
		$data['REGULATION']=$this->post('regulation');
		$data['YEAROFPUBLISHED']=$this->post('yearOfPublished');
		$data['ISBN']=$this->post('ISBN');
		$data['PUBLISHER']=$this->post('publisher');
		$data['EDITION']=$this->post('edition');
		$data['PRICE']=$this->post('price');
		$data['RACKNO']=$this->post('rackNO');
		$data['C_QUANTITY']=$this->post('currentQuantity');
		$data['T_QUANTITY']=$this->post('totalQuantity');
		$data['IMAGE']=$this->post('image');
		if($id==null){
			$result=$this->librarymodel->addBookDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Book Detail Inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->librarymodel->editBookDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Book Detail Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function lBook_delete(){
		$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->librarymodel->deleteBookDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function lBookIssue_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->librarymodel->getAllBookIssueDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->librarymodel->getBookIssueDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
			}else{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}		
	}
	
	function lBookIssue_post(){
		$id=$this->post('id');
		$data['TYPE']=$this->post('type');
		$data['PROFILE_ID']=$this->post('profileId');
		$data['BOOK_ID']=$this->post('bookId');
		$data['ISSUED_DATETIME']=$this->post('issued_date');
		$data['DUE_DATETIME']=$this->post('due_date');
		if($id==null){
			$result=$this->librarymodel->addBookIssueDetails($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Book Issued Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->librarymodel->editBookIssueDetails($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Book Issued Detail Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function lBookIssue_delete(){
		$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->librarymodel->deleteBookIssueDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
}
?>
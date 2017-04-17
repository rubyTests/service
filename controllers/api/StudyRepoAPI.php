<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class StudyRepoAPI extends REST_Controller {    
    function StudyRepoAPI()
    {
		parent::__construct();
		$this->load->model('studyrepomodel');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
    }

    // study Repository
	
    function studyRepository_post()
    {
		$folderPath = $config['upload_path'] = 'upload/';
        $config['allowed_types'] = '*';   
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if(!$this->upload->do_upload('0')){
            $this->set_response(['status' =>FALSE,'message'=>'Upload Error'], REST_Controller::HTTP_NOT_FOUND);
        }else{
            $data = $this->upload->data();
            $filePath=$folderPath.$data['file_name'];
        }
		
    	$data['STY_REP_COU_ID']=$this->post('STY_REP_COU_ID');
    	$data['STY_REP_BAT_ID']=$this->post('STY_REP_BAT_ID');
    	$data['STY_REP_TITLE']=$this->post('STY_REP_TITLE');
    	$data['STY_REP_CONTENT']=$this->post('STY_REP_CONTENT');
    	$data['STY_REP_DESC']=$this->post('STY_REP_DESC');
    	$data['STY_REP_FILE_PATH']=$filePath;
    	$data['STY_REP_USER_ID']=$this->post('STY_REP_USER_ID');
    	$data['STY_REP_UPD_USER_ID']=$this->post('STY_REP_UPD_USER_ID');
		$id=$this->post('STY_REP_ID');
		if($id==NULL){
			$result=$this->studyrepomodel->addStudyRepository($data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Study Repository Details inserted successfully'], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->studyrepomodel->editStudyRepository($id,$data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result,'message'=>'Study Repository Details Updated successfully'], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
	
	function studyRepository_get(){
		$id=$this->get('STY_REP_ID');
		if($id==null){
			$users=$this->studyrepomodel->getStudyRepositoryAll();
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Study Repository data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$users=$this->studyrepomodel->getStudyRepository($id);
			if (!empty($users)){
				$this->set_response(['status' =>TRUE,'message'=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Study Repository data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
}
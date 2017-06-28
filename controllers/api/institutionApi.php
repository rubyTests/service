<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/helpers/checktoken_helper.php';
class institutionApi extends REST_Controller {    
    function institutionApi()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('institution_model');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
   

   //-------------------------------------  Institution ---------------------------------------------------


    function institutionDetails_post()
    {   
    	// print_r($this->post());exit;
    	$data['institution_id']=$this->post('institution_id');
    	$data['institute_name']=$this->post('institute_name');
    	$data['inst_code']=$this->post('code');
    	$data['image_file']=$this->post('file_image');
    	$data['type']=$this->post('type');
    	$data['time_zone']=$this->post('time_zone');
    	$data['currency']=$this->post('currency');
    	$data['profile_id']=$this->post('profile_id');
    	$result=$this->institution_model->addInstitutionBasicDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function institutionContactDetails_post(){

    	$data['address']=$this->post('address');
    	$data['contact_name']=$this->post('contact_name');
    	$data['city']=$this->post('city');
    	$data['state']=$this->post('state');
    	$data['pincode']=$this->post('pincode');
    	$data['country']=$this->post('country');
    	$data['phone']=$this->post('phone');
    	$data['mobile_no']=$this->post('mobile_no');
    	$data['email']=$this->post('email');
    	$data['facebook']=$this->post('facebook');
    	$data['google']=$this->post('google');
    	$data['profile_id']=$this->post('profile_id');
    	$data['location_id']=$this->post('location_id');
    	$data['lnstut_id']=$this->post('lnstut_id');
    	$result=$this->institution_model->addInstitutionContactDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

  //   function institution_post()
  //   {
  //   	$Datas = $this->post('file_image');
  //   	$Images=$Datas;
  //       $ImageSplit = explode(',', $Images);        
  //       $ImageResult = base64_decode($ImageSplit[1]);
  //       $im = imagecreatefromstring($ImageResult); 
  //       if ($im !== false) 
  //       {
  //           $fileName = date('Ymdhis') .".png";
  //           $resp = imagepng($im, $_SERVER['DOCUMENT_ROOT'].'smartedu/upload/'.$fileName);
  //           imagedestroy($im);
  //       }       
  //   	$data['institute_id']=$this->post('institute_id');
  //   	$data['institute_name']=$this->post('institute_name');
  //   	$data['inst_code']=$this->post('code');
  //   	$data['image_file']=$this->post('file_image');
  //   	$data['type']=$this->post('type');
  //   	$data['time_zone']=$this->post('time_zone');
  //   	$data['currency']=$this->post('currency');
  //   	// $data['fax']=$this->post('fax');
  //   	$data['address']=$this->post('address');
  //   	$data['contact_name']=$this->post('contact_name');
  //   	$data['city']=$this->post('city');
  //   	$data['state']=$this->post('state');
  //   	$data['pincode']=$this->post('pincode');
  //   	$data['country']=$this->post('country');
  //   	$data['phone']=$this->post('phone');
  //   	$data['mobile_no']=$this->post('mobile_no');
  //   	$data['email']=$this->post('email');
  //   	$data['facebook']=$this->post('facebook');
  //   	$data['google']=$this->post('google');
  //   	$data['profile_id']=$this->post('profile_id');
  //   	$data['instID']=$this->post('instID');
  //   	$data['profileID']=$this->post('profileID');
  //   	$data['locationID']=$this->post('locationID');
  //   	// echo "<pre>";print_r($data);exit;
  //   	$result=$this->institution_model->addInstitutionDetails($data);
  //   	if($result['status']==true){
		// 	$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_CREATED);
		// }else{
		// 	$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		// }
  //   }
    function institution_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->institution_model->getAllInstitution_details();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->institution_model->getInstitution_details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
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

    function institution_delete(){
    	$id=$this->delete('id');
    	$profile_id=$this->delete('profile_id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->institution_model->deleteInstitutionData($id,$profile_id);
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


	//-------------------------------------  Building ---------------------------------------------------
    function building_post()
    {
    	$id=$this->post('build_id');
    	$data['name']=$this->post('build_name');
    	$data['number']=$this->post('bulid_no');
    	$data['landmark']=$this->post('landmark');
    	
    	if($id==NULL){
    		$result=$this->institution_model->addbuildingDetails($data);
    		if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Building Name Alredy Exists"], REST_Controller::HTTP_CREATED);
			}
    	}else{
    		$result=$this->institution_model->editbuildingDetails($id,$data);
			if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Building Name Alredy Exists"], REST_Controller::HTTP_CREATED);
			}

    	}


  //   	if($result['status']==true){
		// 	$this->set_response(['status' =>TRUE,'message'=>$result['message'],'BUILDING_ID'=>$result['BUILDING_ID']], REST_Controller::HTTP_CREATED);
		// }else{
		// 	$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		// }
    }
    function building_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->institution_model->getAllBuilding_details();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
				// $this->set_response([
				// 'status' => FALSE,
				// 'message' => 'Data could not be found'
				// ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->institution_model->getBuilding_details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
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
	function checkBuildingDetails_get(){
		$id=$this->get('id');
		if($id==NULL){
			$result=$this->institution_model->getAllBuilding_details();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Building Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$result=$this->institution_model->checkBuildingDetails($id);
			//print_r($users);exit();
			if ($result['status']!=0){
				$this->set_response(['status' =>TRUE],REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => $result['message']
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function building_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->institution_model->deleteBuildingData($id);
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

    //-------------------------------------  Block ---------------------------------------------------
    function block_post()
    {
    	// print_r($this->post());exit;
    	$id=$this->post('block_id');
    	$data['name']=$this->post('block_name');
    	$data['number']=$this->post('block_no');
    	$data['building_id']=$this->post('building_id');
    	if($id == NULL){
    		$result=$this->institution_model->addBlockDetails($data);
    		if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Block Name Alredy Exists"], REST_Controller::HTTP_CREATED);
			}
    	}else{
    		$result=$this->institution_model->editBlockDetails($id,$data);
    		if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Building Name Alredy Exists"], REST_Controller::HTTP_CREATED);
			}
    	}
    }
    function block_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->institution_model->getAllBlock_details();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->institution_model->getBlock_details($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Block data could not be found'
				], REST_Controller::HTTP_OK);
			}
        }    			
	}
	function checkBlockDetails_get(){
		$id=$this->get('id');
		if($id==NULL){
			$result=$this->institution_model->getAllBlock_details();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Building Details could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$result=$this->institution_model->checkBlockDetails($id);
			//print_r($users);exit();
			if ($result['status']!=0){
				$this->set_response(['status' =>TRUE],REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => $result['message']
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
    function block_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->institution_model->deleteBlockData($id);
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

    //-------------------------------------  Room ---------------------------------------------------
    function room_post()
    {
    	$id=$this->post('room_id');
    	$data['name']=$this->post('room_name');
    	// $data['number']=$this->post('room_no');
    	$data['floor']=$this->post('floor');
    	$data['block_id']=$this->post('block_id');
    	$data['building_id']=$this->post('building_id');
    	$data['info']=$this->post('info');
    	if($id == NULL){
    		$result=$this->institution_model->addRoomDetails($data);
    		if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Room Name Alredy Exists"], REST_Controller::HTTP_CREATED);
			}
    	}else{
    		$result=$this->institution_model->editRoomDetailss($id,$data);
    		if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Room Name Alredy Exists"], REST_Controller::HTTP_CREATED);
			}
    	}
  //   	$result=$this->institution_model->addRoomDetails($data);
  //   	if($result['status']==true){
		// 	$this->set_response(['status' =>TRUE,'message'=>$result['message'],'ROOM_ID'=>$result['ROOM_ID']], REST_Controller::HTTP_CREATED);
		// }else{
		// 	$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		// }
    }
    function room_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->institution_model->getAllRoom_details();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->institution_model->getRoom_details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
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
	 function checkRoom_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->institution_model->getAllRoom_details();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Data could not be found'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->institution_model->checkRoom_details($id);
    		if ($result['status']!=0){
				$this->set_response(['status' =>TRUE],REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => $result['message']
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
        }    			
	}
	function room_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->institution_model->deleteRoomData($id);
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
    

    // Comman setting

    function institutetype_get(){
    	$result=$this->institution_model->fetchInstituteType_details();
    	if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}  			
	}
	function timezone_get(){
    	$result=$this->institution_model->fetchTimeZone_details();
    	if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}  			
	}
	function currency_get(){
    	$result=$this->institution_model->fetchCurrency_details();
    	if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}  			
	}
	function country_get(){
    	$result=$this->institution_model->fetchTotalCountry();
    	if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}  			
	}

	// function country_get(){
 //    	$result=$this->institution_model->fetchTotalCountry();
 //    	if (!empty($result)){
	// 		$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	// 	}
	// 	else
	// 	{
	// 		$this->set_response([
	// 		'status' => FALSE,
	// 		'message' => 'Data could not be found'
	// 		], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
	// 	}  			
	// }
	function profile_get(){
		$result=$this->institution_model->getEmployeeData();
    	if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}  	
	}
	function location_get(){
		$result=$this->institution_model->fetchLocationDetails();
    	if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}  	
	}

	function institutionDetails_get(){
		$result=$this->institution_model->fetchInstitutionDetails();
    	if (!empty($result)){
			$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}  	
	}
	function institutionId_get(){
    	$no=$this->get('institutionName');
    	$id=$this->get('institutionID');
    	// print_r($id);exit();
		$result=$this->institution_model->checkInstitution($no,$id);
		if ($result==true){
			$this->set_response(['status' =>FALSE,'message'=>'Admission number already exist'], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>TRUE,'message'=>'Success'], REST_Controller::HTTP_OK);
		} 			
	}
	
	function blockDetails_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->institution_model->fetchAllBlockDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response(['status' => FALSE,'message' => 'Data could not be found'], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}else{
			$result=$this->institution_model->fetchBlockDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response(['status' => FALSE,'message' => 'Data could not be found'], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}  	
	}
	
	function roomDetails_get(){
		$id=$this->get('id');
		if($id==null){
			$result=$this->institution_model->fetchAllRoomDetails();
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response(['status' => FALSE,'message' => 'Data could not be found'], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}else{
			$result=$this->institution_model->fetchRoomDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response(['status' => FALSE,'message' => 'Data could not be found'], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}  	
	}
}
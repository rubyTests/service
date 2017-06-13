<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class TransportAPI extends REST_Controller {    
    function TransportAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('transportmodel');
		$this->load->library('Curl');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    
	function vehicle_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['TYPE']=$this->post('type');
		$data['CAPACITY']=$this->post('capacity');
		$data['REG_NO']=$this->post('regiNumber');
		$data['RES_PERSON']=$this->post('resPerson');
		$data['IMAGE1']=$this->post('image1');
		if($id==null){
			$result=$this->transportmodel->addVehicle($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->transportmodel->editVehicle($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function vehicle_get(){
		$id=$this->get('id');
		$result=$this->transportmodel->vehicleDetails($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	function checkVehicledetails_get(){
		$id=$this->get('id');
		if($id==NULL){
			$result=$this->transportmodel->vehicleDetails($id);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Vehicle Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

		}else{
			$result=$this->transportmodel->checkVehicledetails($id);
			//print_r($users);exit();
			if ($result['status']!=0){
				$this->set_response(['status' =>TRUE],REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => $result['message']
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }
	function vehicle_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->transportmodel->deleteVehicleDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		} 
    }
    //route
    function route_post(){
		$id=$this->post('id');
		$data['NAME']=$this->post('name');
		$data['DESTINATION']=$this->post('destination');
		$data['VIA']=$this->post('routeVia');
		$data['VEHICLE_ID']=$this->post('vehicleName');
		if($id==null){
			$result=$this->transportmodel->addRoute($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->transportmodel->editRoute($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function route_get(){
		$id=$this->get('id');
		$result=$this->transportmodel->routeDetails($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	function route_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->transportmodel->deleteRouteDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		} 
    }
    //routeTime
    function routeTiming_post(){
		$id=$this->post('id');
		$data['ROUTE_ID']=$this->post('routeName');
		$data['M_STARTTIME']=$this->post('m_startTime');
		$data['M_ENDTIME']=$this->post('m_endTime');
		$data['E_STARTTIME']=$this->post('e_startTime');
		$data['E_ENDTIME']=$this->post('e_endTime');
		$data['VEHICLE_ID']=$this->post('vehicleName');
		if($id==null){
			$result=$this->transportmodel->addRouteTiming($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->transportmodel->editRouteTiming($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function routeTiming_get(){
		$id=$this->get('id');
		$result=$this->transportmodel->routeTimingDetails($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	function routeTiming_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->transportmodel->deleteRouteTimingDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		} 
    }
    //routeStops
    function routeStops_post(){
		$id=$this->post('id');
		$data['ROUTE_ID']=$this->post('routeName');
		$data['NAME']=$this->post('stopName');
		$data['VEHICLE_ID']=$this->post('vehicleName');
		$data['PICKUPTIME']=$this->post('picTime');
		$data['DROPTIME']=$this->post('droptime');
		$data['FARE']=$this->post('fare');
		$data['FARETYPE']=$this->post('fareType');
		if($id==null){
			$result=$this->transportmodel->addRouteStops($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->transportmodel->editRouteStops($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
	
	function routeStops_get(){
		$id=$this->get('id');
		$result=$this->transportmodel->routeStopsDetails($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	function routeStops_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->transportmodel->deleteRoutrStopsDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		} 
    }
    //routeAllocation
	function routeAllocation_post(){
		$id=$this->post('id');
		//print_r($id);exit();
		$data['TYPE']=$this->post('type');
		$data['PROFILE_ID']=$this->post('profileId');
		$data['ROUTESTOP_ID']=$this->post('stopname');
		$data['VEHICLE_ID']=$this->post('vehicleName');
		$data['JOINING_DATE']=$this->post('startDate');
		if($id==null){
			$result=$this->transportmodel->addRouteAllocation($data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data inserted Successfully'], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}else{
			$result=$this->transportmodel->editRouteAllocation($id,$data);
			if (!empty($result)){
				$this->set_response(['status' =>TRUE,'message'=>'Data Updated Successfully'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
			}
		}
	}
		
	
	function routeAllocation_get(){
		$id=$this->get('id');
		$result=$this->transportmodel->routeAllocationDetails($id);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>FALSE,'message'=>'Data not found'], REST_Controller::HTTP_OK);
		}
	}
	function routeAllocation_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->transportmodel->deleteRouteAllocationDetails($id);
			if($result!=0){
				$this->set_response(['status'=>TRUE,'message'=>'Record Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$this->set_response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_OK);
			}
		} 
    }	
 
}
?>
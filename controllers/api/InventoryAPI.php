<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/helpers/mailGun/test.php';
require APPPATH . '/helpers/phpMailer/phpmail.php';
//require APPPATH . '/libraries/server.php';
require APPPATH . '/helpers/checktoken_helper.php';
class InventoryAPI extends REST_Controller {    
    function InventoryAPI()
    {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		$this->load->model('inventory_model');
		$this->load->library('Curl');
		$userIDByToken="";
		checkTokenAccess();
		checkAccess();
    }
    
	//-------------------------------------  store category ---------------------------------------------------
    function storeCategory_post()
    {
    	$data['id']=$this->post('store_cat_id');
    	$data['name']=$this->post('store_cat_name');
    	$data['code']=$this->post('store_cat_code');
		$result=$this->inventory_model->storeCategoryDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function storeCategory_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllStoreCategory_details();
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
        	$result=$this->inventory_model->getStoreCategory_details($id);
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

	function storeCategory_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->inventory_model->deleteStoreCategoryData($id);
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

    //-------------------------------------  Item category ---------------------------------------------------
    function itemCategory_post()
    {
    	$data['id']=$this->post('item_cat_id');
    	$data['name']=$this->post('item_cat_name');
    	$data['code']=$this->post('item_cat_code');
		$result=$this->inventory_model->itemCategoryDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function itemCategory_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllItemCategory_details();
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
        	$result=$this->inventory_model->getItemCategory_details($id);
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

	function itemCategory_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->inventory_model->deleteItemCategoryData($id);
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

    //-------------------------------------  Supplier Type ---------------------------------------------------

    function supplierType_post()
    {
    	$data['id']=$this->post('supp_type_id');
    	$data['name']=$this->post('supp_type_name');
    	$data['code']=$this->post('supp_type_code');
		$result=$this->inventory_model->supplierTypeDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function supplierType_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllSupplierType_details();
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
        	$result=$this->inventory_model->getSupplierType_details($id);
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

	function supplierType_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->inventory_model->deleteSupplierTypeData($id);
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

    function supplierIdData_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
			$this->set_response([
			'status' => FALSE,
			'message' => 'Data could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }else{
        	$result=$this->inventory_model->getSupplierData_details($id);
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

    //-------------------------------------  Store ---------------------------------------------------

    function store_post()
    {
    	$data['id']=$this->post('store_id');
    	$data['name']=$this->post('store_name');
    	$data['code']=$this->post('store_code');
    	$data['store_category_id']=$this->post('store_category_id');
		$result=$this->inventory_model->storeDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function store_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllStore_details();
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
        	$result=$this->inventory_model->getStore_details($id);
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

	function store_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->inventory_model->deleteStoreData($id);
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

    //-------------------------------------  Item ---------------------------------------------------

    function item_post()
    {
    	$data['id']=$this->post('item_id');
    	$data['name']=$this->post('item_name');
    	$data['code']=$this->post('item_code');
    	$data['unit']=$this->post('item_unit');
    	$data['part_no']=$this->post('item_part_no');
    	$data['image']=$this->post('item_image');
    	$data['item_category_id']=$this->post('item_category_id');
    	// print_r($data);
    	// exit;
		$result=$this->inventory_model->itemDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function item_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllItem_details();
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
        	$result=$this->inventory_model->getItem_details($id);
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

	function item_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->inventory_model->deleteItemData($id);
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

    //-------------------------------------  Store Item---------------------------------------------------

    function storeItem_post()
    {
    	$data['id']=$this->post('store_item_id');
    	$data['item_id']=$this->post('store_item_name');
    	$data['quantity']=$this->post('store_item_quantity');
    	$data['price']=$this->post('store_item_price');
    	$data['store_id']=$this->post('store_id');
    	$data['item_category_id']=$this->post('item_category_id');
		$result=$this->inventory_model->storeItemDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function storeItem_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllStoreItem_details();
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
        	$result=$this->inventory_model->getStoreItem_details($id);
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

	function storeIdData_get(){
		$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllStoreItem_details();
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
        	$result=$this->inventory_model->getStoreItemData_details($id);
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

	function storeItem_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->inventory_model->deleteStoreItemData($id);
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

    function itemCategoryItems_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
				$this->set_response([
				'status' => FALSE,
				'message' => 'Data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }else{
        	$result=$this->inventory_model->getItemCategoryItems($id);
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

    //-------------------------------------  Supplier ---------------------------------------------------

    function supplier_post()
    {
    	$data['id']=$this->post('supplier_id');
    	$data['name']=$this->post('supplier_name');
    	$data['phone']=$this->post('supplier_contact_number');
    	$data['address']=$this->post('supplier_address');
    	$data['region']=$this->post('supplier_region');
    	$data['supplier_type_id']=$this->post('supplier_type_id');
		$result=$this->inventory_model->supplierDetails($data);
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function supplier_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllSupplier_details();
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
        	$result=$this->inventory_model->getSupplier_details($id);
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

	function supplier_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->inventory_model->deleteSupplierData($id);
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

    //-------------------------------------  Material Request ---------------------------------------------------

    function materialRequest_post()
    {
    	$data['id']=$this->post('materialReq_id');
    	$data['req_num']=$this->post('request_no');
    	$data['req_date']=date("Y-m-d", strtotime($this->post('request_date')));
    	$data['store_id']=$this->post('store_id');
    	$data['notesData']=$this->post('notesData');
    	$data['itemData']=$this->post('itemData');
    	$data['inst_id']=$this->post('inst_id');
    	
		$result=$this->inventory_model->materialRequestDetails($data);
		
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function materialRequest_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllMaterialRequest_details();
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
        	$result=$this->inventory_model->getMaterialRequest_details($id);
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

	function fetchAllMaterialRequest_get(){
		$id  = $this->get('id');
		if($id){
			$data=$this->inventory_model->getAllMaterialRequestData($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Material Request Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Material Request Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	function getItemCode_get(){
		$id  = $this->get('id');
		if($id){
			$data=$this->inventory_model->getItemCode($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Material Request Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Material Request Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	//-------------------------------------  Purchase Order ---------------------------------------------------

    function purchaseOrder_post()
    {
    	$data['id']=$this->post('purchaseOrder_id');
    	$data['po_num']=$this->post('po_number');
    	$data['po_date']=date("Y-m-d", strtotime($this->post('po_date')));
    	$data['store_id']=$this->post('store_id');
    	$data['supp_type_id']=$this->post('supplier_type_id');
    	$data['supp_id']=$this->post('supplier_id');
    	$data['po_ref']=$this->post('po_reference');
    	$data['total_amount']=$this->post('total_amount');
    	$data['itemData']=$this->post('itemData');
    	$data['institute_id']=$this->post('institute_id');
    	
		$result=$this->inventory_model->purchaseOrderPostData($data);
		
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function purchaseOrder_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllPurchaseOrder_details();
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
        	$result=$this->inventory_model->getPurchaseOrder_details($id);
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

	function fetchAllPurchaseOrder_get(){
		$id  = $this->get('id');
		if($id){
			$data=$this->inventory_model->getAllPurchaseOrderData($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Material Request Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Material Request Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	function purchaseOrder_delete(){
		$id = $this->delete('id');
		$syl_id = $this->delete('syllabus_id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Purchase Order Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->inventory_model->deletePurchaseOrderData($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Purchase Order Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Purchase Order could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    //-------------------------------------  Billing ---------------------------------------------------

    function billing_post()
    {
    	$data['id']=$this->post('billing_id');
    	$data['invoice_no']=$this->post('invoice_no');
    	$data['invoice_date']=date("Y-m-d", strtotime($this->post('invoice_date')));
    	$data['store_id']=$this->post('store_id');
    	$data['notes']=$this->post('notes');
    	$data['total_amount']=$this->post('total_amount');
    	$data['itemData']=$this->post('itemData');
    	$data['institute_id']=$this->post('institute_id');
    	
		$result=$this->inventory_model->billingPostData($data);
		
    	if($result['status']==true){
			$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
    }

    function billing_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllBilling_details();
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
        	$result=$this->inventory_model->getBilling_details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Billing data could not be found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}

	function fetchAllBilling_get(){
		$id  = $this->get('id');
		if($id){
			$data=$this->inventory_model->getAllBillingData($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Billing Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Billing Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	function billing_delete(){
		$id = $this->delete('id');
		$syl_id = $this->delete('syllabus_id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'Billing Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->inventory_model->deleteBillingData($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'Billing Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Billing could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    //-------------------------------------  GRN ---------------------------------------------------

    function GRN_post()
    {
    	$id = $this->post('grn_id');

    	$data['id']=$this->post('grn_id');
    	$data['grn_number']=$this->post('grn_number');
    	$data['grn_date']=date("Y-m-d", strtotime($this->post('grn_date')));
    	$data['po_id']=$this->post('po_id');
    	$data['invoice_no']=$this->post('invoice_number');
    	$data['invoice_date']=date("Y-m-d", strtotime($this->post('invoice_date')));
    	$data['total_amount']=$this->post('total_amount');
    	$data['itemData']=$this->post('itemData');
    	$data['institute_id']=$this->post('institute_id');
    	if($id==NULL){
			$result=$this->inventory_model->grnPostData($data);
	    	if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->inventory_model->grnUpdatePostData($id,$data);
	    	if($result['status']==true){
				$this->set_response(['status' =>TRUE,'message'=>$result['message']], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    	
		
    }

    function GRN_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->inventory_model->getAllGRN_details();
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
        	$result=$this->inventory_model->getGRN_details($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'GRN data could not be found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}

	function fetchAllGRN_get(){
		$id  = $this->get('id');
		if($id){
			$data=$this->inventory_model->getAllGRNData($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'GRN Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'GRN Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	function purchaseOrderIdItems_get(){
		$id  = $this->get('id');
		if($id){
			$data=$this->inventory_model->getPurchaseOrderIdItems($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'GRN Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'GRN Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	function purchaseOrderIdUpdateItems_get(){
		$id  = $this->get('id');
		if($id){
			$data=$this->inventory_model->getPurchaseOrderIdUpdateItems($id);
			if (!empty($data)){
				$this->set_response(['status' =>TRUE,'data'=>$data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'GRN Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'GRN Details could not be found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	function GRN_delete(){
		$id = $this->delete('id');
		// $syl_id = $this->delete('syllabus_id');
		if($id==NULL){
				$this->set_response([
				'status' => FALSE,
				'message' => 'GRN Details could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}else{
			$users=$this->inventory_model->deleteGRNData($id);
			if ($users!=0){
				$this->set_response(['status' =>TRUE,'message'=>'GRN Detail deleted successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'GRN could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

	// Inventory Report

    function materialRequestReport_get(){
    	$id=$this->get('id');
    	$fromDate=date("Y-m-d", strtotime($this->get('fromDate')));
    	$toDate=date("Y-m-d", strtotime($this->get('toDate')));
    	if ($id != null)
        {
        	
        	if( $fromDate != NULL && $toDate !=NULL ){
        		$result=$this->inventory_model->getMaterialReport_details($id,$fromDate,$toDate);
        	}else{
        		$result=$this->inventory_model->getMaterialReportAll_details($id,$fromDate,$toDate);
        	}

    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_OK);
			}
        }    			
	}

	function purchaseOrderReport_get(){
    	$id=$this->get('id');
    	$fromDate=date("Y-m-d", strtotime($this->get('fromDate')));
    	$toDate=date("Y-m-d", strtotime($this->get('toDate')));
    	if ($id != null)
        {
        	
        	if( $fromDate != NULL && $toDate !=NULL ){
        		$result=$this->inventory_model->getPurchaseOrderReport_details($id,$fromDate,$toDate);
        	}else{
        		$result=$this->inventory_model->getPurchaseOrderReportAll_details($id,$fromDate,$toDate);
        	}

    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_OK);
			}
        }    			
	}

	function GRNReport_get(){
		$id=$this->get('id');
    	$fromDate=date("Y-m-d", strtotime($this->get('fromDate')));
    	$toDate=date("Y-m-d", strtotime($this->get('toDate')));
    	if ($id != null)
        {
        	if( $fromDate != NULL && $toDate !=NULL ){
        		$result=$this->inventory_model->getGRNReport_details($id,$fromDate,$toDate);
        	}else{
        		$result=$this->inventory_model->getGRNReportAll_details($id,$fromDate,$toDate);
        	}
        	
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'data'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Employee data could not be found'
				], REST_Controller::HTTP_OK);
			}
        }    
	}

	// Previous Academics Details
	
	function academicsDetails_post(){
		$id=$this->post('profileId');
		$data['previous']=$this->post('previous');
		$data['sibling']=$this->post('sibling');
		$data['bloodGroup']=$this->post('bloodGroup');
		$data['birthplace']=$this->post('birthplace');
		$data['stu_category']=$this->post('stu_category');
		$data['stu_type']=$this->post('stu_type');
		$data['student_lives']=$this->post('student_lives');
		$result=$this->profilemodel->academicsDetails($id,$data);
		if(!empty($result)){
			$this->set_response(['status' =>TRUE,'profileId'=>$id,'preEduId'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	// Contact Details
	
	function contactDetails_post(){
		//print_r($this->post());exit;
		$id=$this->post('profileId');
		
		$data['sameAddress']=$this->post('sameAddress');
		
		$data['locId']=$this->post('addressId');
		$data['locId1']=$this->post('addressId1');
		
		$data['ADDRESS']=$this->post('address');
		$data['CITY']=$this->post('city');
		$data['STATE']=$this->post('state');
		$data['ZIP_CODE']=$this->post('pincode');
		$data['COUNTRY']=$this->post('country');
		
		$data['ADDRESS1']=$this->post('address1');
		$data['CITY1']=$this->post('city1');
		$data['STATE1']=$this->post('state1');
		$data['ZIP_CODE1']=$this->post('pincode1');
		$data['COUNTRY1']=$this->post('country1');
		
		$data['PHONE_NO_1']=$this->post('phone');
		$data['PHONE_NO_2']=$this->post('mobile_no');
		$data['EMAIL']=$this->post('email');
		$data['FACEBOOK_LINK']=$this->post('facebook');
    	$data['GOOGLE_LINK']=$this->post('google');
    	$data['LINKEDIN_LINK']=$this->post('linkedin');
		$result=$this->profilemodel->contactDetails($id,$data);
		if(!empty($result)){
			$mailStatus=$this->profilemodel->checkMailVerification($id);
			if($mailStatus){
				//print_r($mailStatus);exit;
				$to=$mailStatus[0]['email'];
				$token=$mailStatus[0]['token'];
				$phone=$mailStatus[0]['phone'];
				$verifyLink='http://192.168.1.137/Projects/campus/#/verification/';
				$msg="Dear Sir/Madam,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to Rubycampus application. <a href='http://192.168.1.138/Projects/campus/#/verification/$token'> Click Here </a> <br><br><br>Thanks &amp; Regards,<br>Admin<br>";
				$sms="Welcome to Rubycampus application. Please click the link: http://192.168.1.137/Projects/campus/#/verification/$token";
				
				//$result=mailVerification($to,$msg);
				if($to){
					$res=mailVerify($to,$msg);
				}
				//print_r($result);exit;
				if($phone){
					$this->GeneralMod->phoneVerify($phone,$sms);
				}
			}
			
			// $phoneStatus=$this->profilemodel->checkPhoneVerification($id);
			// if(!empty($phoneStatus)){
				// //$status=$this->GeneralMod->phoneVerify($id,$phoneStatus);
			// }
			$this->set_response(['status' =>TRUE,'profileId'=>$id,'locationId'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	function parentsProfile_post(){
		// print_r($this->post());
		// exit;
		$id=$this->post('profileId');
		$data['stuPro_id']=$this->post('stuPro_id');
		$data['father']=$this->post('father');
		$data['mother']=$this->post('mother');
		$data['guardian']=$this->post('guardian');
		$result=$this->profilemodel->parentsProfile($id,$data);
		if(!empty($result)){
			if($result[0]['frelation_id']){
				$mailStatus=$this->profilemodel->checkMailVerification($result[0]['frelation_id']);
				if($mailStatus){
					$to=$mailStatus[0]['email'];
					$token=$mailStatus[0]['token'];
					$verifyLink='http://192.168.1.138/Projects/campus/#/verification/';
					$msg="Dear Sir/Madam,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to Rubycampus application. <a href='http://192.168.1.138/Projects/campus/#/verification/$token'> Click Here </a> <br><br><br>Thanks &amp; Regards,<br>Admin<br>";
					$res=mailVerify($to,$msg);
					//print_r($result);exit;
				}
			}else if($result[0]['mrelation_id']){
				$mailStatus=$this->profilemodel->checkMailVerification($result[0]['mrelation_id']);
				if($mailStatus){
					$to=$mailStatus[0]['email'];
					$token=$mailStatus[0]['token'];
					$verifyLink='http://192.168.1.138/Projects/campus/#/verification/';
					$msg="Dear Sir/Madam,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to Rubycampus application. <a href='http://192.168.1.138/Projects/campus/#/verification/$token'> Click Here </a> <br><br><br>Thanks &amp; Regards,<br>Admin<br>";
					$res=mailVerify($to,$msg);
					//print_r($result);exit;
				}
			}else if($result[0]['grelation_id']){
				$mailStatus=$this->profilemodel->checkMailVerification($result[0]['grelation_id']);
				if($mailStatus){
					$to=$mailStatus[0]['email'];
					$token=$mailStatus[0]['token'];
					$verifyLink='http://192.168.1.138/Projects/campus/#/verification/';
					$msg="Dear Sir/Madam,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to Rubycampus application. <a href='http://192.168.1.138/Projects/campus/#/verification/$token'> Click Here </a> <br><br><br>Thanks &amp; Regards,<br>Admin<br>";
					$res=mailVerify($to,$msg);
					//print_r($result);exit;
				}
			}
			
			$this->set_response(['status' =>TRUE,'profileId'=>$id,'profileIds'=>$result,'message'=>'Student Profile Detail Inserted Successfully'], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	function profileDetails_post()
    {
		// print_r($this->post());
		// exit;
    	$id=$this->post('profileId');
    	$data['ADMISSION_NO']=$this->post('admission_no');
    	$data['ADMISSION_DATE']=$this->post('admission_date');
    	$data['FIRSTNAME']=$this->post('first_name');
    	$data['LASTNAME']=$this->post('last_name');
		$data['IMAGE1']=$this->post('filename');
    	$data['GENDER']=$this->post('gender');
    	$data['DOB']=$this->post('wizard_birth');
    	$data['NATIONALITY']=$this->post('nationality');
    	$data['MOTHER_TONGUE']=$this->post('mother_tongue');
    	$data['RELIGION']=$this->post('religion');
    	$data['COURSEBATCH_ID']=$this->post('batchId');
    	$data['ROLL_NO']=$this->post('roll_no');
    	$data['ADDRESS']=$this->post('address');
		$data['CITY'] = $this->post('stu_city');
	   	$data['STATE'] = $this->post('stu_state');
	   	$data['COUNTRY'] = $this->post('selectize_country');
	   	$data['ZIP_CODE'] = $this->post('pincode');
    	$data['EMAIL']=$this->post('email'); 
    	$data['PHONE_NO_1']=$this->post('wizard_phone');
    	$data['PHONE_NO_2']=$this->post('mobile_no');
		
    	$data['BLOOD_GROUP']=$this->post('selectize_blood');
    	$data['BIRTHPLACE']=$this->post('birthplace');
    	$data['STUDENTCATEGORY_ID']=$this->post('selectize_cat');
    	$data['STUDENT_TYPE']=$this->post('selectize_styType');
		
		$data['INSTITUTE'] = $this->post('institute');
		$data['LEVEL'] = $this->post('course_name');
		$data['YEAR_COMPLETION'] = $this->post('completion');
		$data['TOTAL_GRADE'] = $this->post('total_mark');
		
		$data['SIBLING'] = $this->post('Sibling');
		
    	$data['FACEBOOK_LINK']=$this->post('facebook');
    	$data['GOOGLE_LINK']=$this->post('google');
    	$data['LINKEDIN_LINK']=$this->post('linkedin');
    	
		$data['CRT_USER_ID'] = $this->post('CRT_USER_ID');
		$data['UPD_USER_ID'] = $this->post('UPD_USER_ID');
    	
		if($id==null){
			$result=$this->profilemodel->addProfileDetails($data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'admission_no'=>$result], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}else{
			$result=$this->profilemodel->editProfileDetails($id,$data);
			if(!empty($result)){
				$this->set_response(['status' =>TRUE,'admission_no'=>$result,'message'=>'Student Admission Details Updated successfully'], REST_Controller::HTTP_CREATED);
			}else{
				$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
			}
		}
    }
	
    function profileDetails_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->profilemodel->getProfileDetailsAll();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->profilemodel->getProfileDetails($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}

    function profileDetails_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'No data Here'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->profilemodel->deleteProfileDetails($id);
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
	
	// Parents Details
	function parentsDetails_post(){
		//print_r($this->post());exit;
		$data['fname']=$this->post('p_first_name');
		$data['lname']=$this->post('p_last_name');
		$data['relation']=$this->post('p_relation');
		$data['dob']=$this->post('p_dob');
		$data['education']=$this->post('p_education');
		$data['occupation']=$this->post('occupation');
		$data['p_income']=$this->post('p_income');
		$data['pr_address']=$this->post('pr_address');
		$data['pr_city']=$this->post('pr_city');
		$data['pr_state']=$this->post('pr_state');
		$data['pr_pincode']=$this->post('pr_pincode');
		$data['pr_country']=$this->post('pr_country');
		$data['p_phone']=$this->post('p_phone');
		$data['p_mobile_no']=$this->post('p_mobile_no');
		$data['p_email']=$this->post('p_email');
		$data['CRT_USER_ID']=$this->post('userId');
		$data['parentProfileId']=$this->post('parentProfileId');
		$Id=$this->post('profileId');
		//$result=$this->profilemodel->parentsDetails($Id,$data);
		$result=$this->profilemodel->addParentsDetails($Id,$data);
		if(!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	// Edit Parents Details
	
	function editParentsDetails_post(){
		// print_r($this->post('0'));exit;
		//print_r($this->post());exit;
		// $data['fname']=$this->post('p_first_name');
		// $data['lname']=$this->post('p_last_name');
		// $data['relation']=$this->post('p_relation');
		// $data['dob']=$this->post('p_dob');
		// $data['education']=$this->post('p_education');
		// $data['occupation']=$this->post('occupation');
		// $data['p_income']=$this->post('p_income');
		// $data['pr_address']=$this->post('pr_address');
		// $data['pr_city']=$this->post('pr_city');
		// $data['pr_state']=$this->post('pr_state');
		// $data['pr_pincode']=$this->post('pr_pincode');
		// $data['pr_country']=$this->post('pr_country');
		// $data['p_phone']=$this->post('p_phone');
		// $data['p_mobile_no']=$this->post('p_mobile_no');
		// $data['p_email']=$this->post('p_email');
		// $data['CRT_USER_ID']=$this->post('userId');
		// $data['profileId']=$this->post('profileId');
		//$Id=$this->post('profileId');
		//$Id=1;
		//print_r($data);exit;
		
		$data=$this->post('0');
		$result=$this->profilemodel->editParentsDetails($data);
		if(!empty($result)){
			$this->set_response(['status' =>TRUE,'message'=>$result], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	function parentsDetails_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
			$this->set_response(['status' => FALSE,'message' => 'Profile data could not be found'], REST_Controller::HTTP_NOT_FOUND);
		}else{
        	$result=$this->profilemodel->getParentsDetails($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}
	
	// Student Profile Edit
	
	function profileEdit_post(){
		$data['profile']=$this->post('profile');
		$data['pre_edu']=$this->post('pre_edu');
		$data['parents']=$this->post('parents');
		$result=$this->profilemodel->profileEdit($data);
		if($result==true){
			$this->set_response(['status' =>TRUE,'message'=>'Student Profile Updated Successfully'], REST_Controller::HTTP_CREATED);
		}else{
			$this->set_response(['status' =>FALSE,'message'=>"Failure"], REST_Controller::HTTP_CREATED);
		}
	}
	
	function studentProfileDetails_get(){
    	$id=$this->get('id');
    	if ($id == null)
        {
        	$result=$this->profilemodel->getStudentProfileDetailsAll();
        	if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Student Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
        }else{
        	$result=$this->profilemodel->getStudentProfileDetails($id);
    		if (!empty($result)){
				$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
			}
			else
			{
				$this->set_response([
				'status' => FALSE,
				'message' => 'Student Profile data could not be found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
        }    			
	}
	
	function studentSiblingDetails_get(){
    	$id=$this->get('batchId');   	
    	$profileId=$this->get('profileId');   	
		$result=$this->profilemodel->getStudentSiblingDetails($id,$profileId);
		if (!empty($result)){
			$this->set_response(['status' =>TRUE,'result'=>$result], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response([
			'status' => FALSE,
			'message' => 'Student Sibling data could not be found'
			], REST_Controller::HTTP_NOT_FOUND);
		} 			
	}
	
	// Previous Education Details Delete 
	function preEducation_delete(){
    	$id=$this->delete('id');
    	if ($id == null)
        {
            $this->response(['status'=>FALSE,'message'=>'There is no Record found'], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			$result=$this->profilemodel->deletePreEducation($id);
			if($result!=0){
				$message = [
				'id' => $id,
				'message' => 'Previous Academics Detail Deleted Successfully'
				];
				$this->set_response(['status'=>TRUE,'message'=>'Previous Academics Detail Deleted Successfully'], REST_Controller::HTTP_OK);
			}else{
				$message = [
				'id' => $id,
				'message' => 'There is no Record found'
				];
				$this->set_response(['status'=>FALSE,'message'=>$message], REST_Controller::HTTP_NOT_FOUND);
			}
		}  
    }
	
	// Student admission number check 
	
	function studentAdmissionNo_get(){
    	$no=$this->get('admission_no');
    	$id=$this->get('profileId');
		$result=$this->profilemodel->checkStudentAdmissionNo($no,$id);
		if ($result==true){
			$this->set_response(['status' =>FALSE,'message'=>'Admission number already exist'], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->set_response(['status' =>TRUE,'message'=>'Success'], REST_Controller::HTTP_OK);
		} 			
	}
 
}
?>
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class financemodel extends CI_Model {

		// ------------------------------ Asset -----------------------------------------------------------------
		public function addAssetData($value){
	    	$id=$value['FINC_AS_ID'];
	    	$sql="SELECT count(FINC_AS_TITLE) FROM finance_asset WHERE FINC_AS_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(FINC_AS_TITLE)']!=0){
				$data = array(
				   'FINC_AS_TITLE' => $value['FINC_AS_TITLE'],
				   'FINC_AS_DESC' => $value['FINC_AS_DESC'],
				   'FINC_AS_AMT' => $value['FINC_AS_AMT']				
				);
				$this->db->where('FINC_AS_ID', $id);
				$this->db->update('finance_asset', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'FINC_AS_ID'=>$id);
			}else {
				$data = array(
				   'FINC_AS_TITLE' => $value['FINC_AS_TITLE'],
				   'FINC_AS_DESC' => $value['FINC_AS_DESC'],
				   'FINC_AS_AMT' => $value['FINC_AS_AMT']				
				);
				$this->db->insert('finance_asset', $data);
				$emp_id=$this->db->insert_id();
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'FINC_AS_ID'=>$emp_id);
			}
	    }
	    function getAsset_details($id){
	    	$sql="SELECT * FROM finance_asset where FINC_AS_ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function getAllAsset_details(){
	    	$sql="SELECT * FROM finance_asset";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function deleteAsset($id){
	    	$sql="DELETE FROM finance_asset WHERE FINC_AS_ID='$id'";
	    	$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
	    }

	    // ---------------------------------------- Liability ---------------------------------------------------------
	    public function addLiabilityData($value){
			$id=$value['FINC_LI_ID'];
	    	$sql="SELECT count(FINC_LI_TITLE) FROM finance_liability WHERE FINC_LI_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(FINC_LI_TITLE)']!=0){
				$data = array(
				   'FINC_LI_TITLE' => $value['FINC_LI_TITLE'],
				   'FINC_LI_DESC' => $value['FINC_LI_DESC'],
				   'FINC_LI_AMT' => $value['FINC_LI_AMT']				
				);
				$this->db->where('FINC_LI_ID', $id);
				$this->db->update('finance_liability', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'FINC_LI_ID'=>$id);
			}else {
				$data = array(
				   'FINC_LI_TITLE' => $value['FINC_LI_TITLE'],
				   'FINC_LI_DESC' => $value['FINC_LI_DESC'],
				   'FINC_LI_AMT' => $value['FINC_LI_AMT']				
				);
				$this->db->insert('finance_liability', $data);
				$emp_id=$this->db->insert_id();
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'FINC_LI_ID'=>$emp_id);
			}
	    	
	    }
	    function getLiability_details($id){
	    	$sql="SELECT * FROM finance_liability where FINC_LI_ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function getAllLiability_details(){
	    	$sql="SELECT * FROM finance_liability";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function deleteLiabilityData($id){
	    	$sql="DELETE FROM finance_liability WHERE FINC_LI_ID='$id'";
	    	$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
	    }

	    // -------------------------------------------------- Category --------------------------------------------------------

	    function addFinanceCategory($value){
	    	$id=$value['FINC_CA_ID'];
	    	$sql="SELECT count(FINC_CA_NAME) FROM finance_category WHERE FINC_CA_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(FINC_CA_NAME)']!=0){
				$data = array(
				   'FINC_CA_NAME' => $value['FINC_CA_NAME'],
				   'FINC_CA_DESC' => $value['FINC_CA_DESC'],
				   'FINC_CA_INCOME_YN' => $value['FINC_CA_INCOME_YN']				
				);
				$this->db->where('FINC_CA_ID', $id);
				$this->db->update('finance_category', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'FINC_CA_ID'=>$id);
			}else {
				$data = array(
				   'FINC_CA_NAME' => $value['FINC_CA_NAME'],
				   'FINC_CA_DESC' => $value['FINC_CA_DESC'],
				   'FINC_CA_INCOME_YN' => $value['FINC_CA_INCOME_YN']				
				);
				$this->db->insert('finance_category', $data);
				$emp_id=$this->db->insert_id();
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'FINC_CA_ID'=>$emp_id);
			}
	    }
	    function getFinanceCategory($id){
	    	$sql="SELECT * FROM finance_category where FINC_CA_ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function getAllFinanceCategory(){
	    	$sql="SELECT * FROM finance_category";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function deleteFinanceCategory($id){
	    	$sql="DELETE FROM finance_category WHERE FINC_CA_ID='$id'";
	    	$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
	    }

	    // --------------------------------------------------- Donation ---------------------------------------------------

	    function addDonation(){
	    	$data = json_decode(file_get_contents("php://input"));
	    	$id=$data->FINC_DO_ID;
	    	$name=$data->FINC_DO_NAME;
	    	$desc=$data->FINC_DO_DESC;
	    	$txn_date=$data->FINC_DO_TXN_DT;
	    	$amount=$data->FINC_DO_AMT;

	    	$sql="SELECT count(FINC_DO_NAME) FROM finance_donation WHERE FINC_DO_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(FINC_DO_NAME)']!=0){
				$sql="UPDATE finance_donation SET FINC_DO_NAME='$name',FINC_DO_DESC='$desc',FINC_DO_TXN_DT='$txn_date',FINC_DO_AMT='$amount' WHERE FINC_DO_ID='$id'";
				$this->db->query($sql);
				return array('status'=>true, 'message'=>"Record Updated Successfully");
			}else {
				$sql="INSERT INTO finance_donation (FINC_DO_NAME, FINC_DO_DESC, FINC_DO_TXN_DT,FINC_DO_AMT) VALUES ('$name','$desc','$txn_date','$amount')";
				$this->db->query($sql);
				return array('status'=>true, 'message'=>"Record Inserted Successfully");
			}
	    }
	}
?>
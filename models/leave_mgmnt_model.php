<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class leave_mgmnt_model extends CI_Model {

		// Category Details 		
		public function addleaveCategoryDetails($value){
			$name=$value['cat_name'];
			$sql="SELECT * FROM leave_type where NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['cat_name'],
				   'CODE' => $value['cat_code']
				);
				$this->db->insert('leave_type', $data); 
				$cat_id= $this->db->insert_id();
				if(!empty($cat_id)){
					return array('status'=>true, 'message'=>"Record Inserted Successfully");
				}
			}			
	    }
		
		public function editLeaveCategoryDetails($id,$value){
			$sql="SELECT * FROM leave_type where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['cat_name']){
				$data = array(
				   'NAME' => $value['cat_name'],
				   'CODE' => $value['cat_code']
				);
				$this->db->where('ID', $id);
				$this->db->update('leave_type', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully");
			}else {
				$name=$value['cat_name'];
				$sql="SELECT * FROM leave_type where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
					   'NAME' => $value['cat_name'],
					   'CODE' => $value['cat_code']
					);
					$this->db->where('ID', $id);
					$this->db->update('leave_type', $data); 
					return array('status'=>true, 'message'=>"Record Updated Successfully");
				}
			}
		}
		
		public function fetchEmployeeCategoryDetails(){
			$sql="SELECT * FROM leave_type";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function deleteLeaveCategoryDetails($id){
			$sql="DELETE FROM leave_type where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
	}
?>
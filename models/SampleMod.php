<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SampleMod extends CI_Model {
    
    public function Saminsert(){
		$name=$_POST['name'];
		$email=$_POST['email'];
		$address=$_POST['address'];
		$sql="SELECT count(email) FROM sam WHERE email='$email'";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		if($result[0]['count(email)']!=0){
			$sql="UPDATE sam SET name='$name',address='$address' WHERE email='$email'";
			$this->db->query($sql);
			return true;
		}else{
			$sql="INSERT INTO sam (name, email, address) VALUES ('$name','$email','$address')";
			$this->db->query($sql);
			return true;
		}
    }
	
	public function SamselectAll(){
		$sql="SELECT * FROM sam";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		return $result;
	}
	public function Samselect($id){
		$sql="SELECT * FROM sam where id ='$id'";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		return $result;
	}
	public function SamDelete($id){
		$sql="DELETE FROM sam where id ='$id'";
		$result = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	// Employee Category :

	public function addEmployeeCategory(){
    	$name=$_POST['name'];
    	$prefix=$_POST['prefix'];
    	$active_yn=$_POST['status'];
    	// $cr_user=$_POST['EMP_C_CRT_USER_ID'];
    	// $up_user=$_POST['EMP_C_UPD_USER_ID'];
    	// $sql="INSERT INTO employee_category (EMP_C_NAME, EMP_C_PREFIX, EMP_C_ACTIVE_YN,EMP_C_CRT_USER_ID,EMP_C_UPD_USER_ID) VALUES ('$name','$prefix','$active_yn','$cr_user','$up_user')";
    	$sql="INSERT INTO employee_category (EMP_C_NAME, EMP_C_PREFIX, EMP_C_ACTIVE_YN) VALUES ('$name','$prefix','$active_yn')";
		$this->db->query($sql);
		return true;
    }
    function fetchCategoryDetails(){
		$sql="SELECT * FROM employee_category order by EMP_C_ID";
    	return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
    }
    function deleteCategory($id){
    	$sql="DELETE FROM employee_category WHERE EMP_C_ID='$id'";
    	return $result = $this->db->query($sql);
    }
    function getCategory_details($id){
    	$sql="SELECT * FROM employee_category where EMP_C_ID ='$id'";
		return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
    }
    function updateCategory($id,$name,$prefix,$status){
    	$data = array(  
			'EMP_C_NAME' => $name,  
			'EMP_C_PREFIX' => $prefix,
			'EMP_C_ACTIVE_YN' => $status
		);  
		$this->db->where('EMP_C_ID', $id);  
		$this->db->update('employee_category', $data);
		return true;
    }
}
?>
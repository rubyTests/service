<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HrModuleMod extends CI_Model {
	//Login
	public function login($email,$password){
		$sql="SELECT count(USER_EMAIL) FROM user WHERE USER_EMAIL='$email' AND USER_PASSWORD='$password'";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		if($result[0]['count(USER_EMAIL)']!=0){
			return $result[0]['USER_ID'];
		}else {
			$sql="INSERT INTO employee_position (EMP_P_CATEGORY_ID, EMP_P_NAME, EMP_P_ACTIVE_YN) VALUES ('$cat_id','$name','$status')";
			$this->db->query($sql);
			return true;
		}
	}
	
	// Employee category 
    public function employeeCategoryView(){
		$sql="SELECT * FROM `employee_category`";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		return $result;
	}
	public function empAdd(){
		$name=$this->input->post('EMP_C_NAME');
		$prefix=$this->input->post('EMP_C_PREFIX');
		$active_yn=$this->input->post('EMP_C_ACTIVE_YN');
		$sql="INSERT INTO employee_category (EMP_C_NAME, EMP_C_PREFIX, EMP_C_ACTIVE_YN) VALUES ('$name','$prefix','$active_yn')";
		$this->db->query($sql);
		return true;
	}
	public function empDelete($id){
		$sql="DELETE FROM employee_category WHERE EMP_C_ID='$id'";
		$result = $this->db->query($sql);
		return $this->db->affected_rows();
	}
	public function empEdit($id){
		$sql="SELECT * FROM `employee_category` where EMP_C_ID='$id'";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		return $result;
	}
	public function empUpdate($id){
		$name=$this->input->post('EMP_C_NAME');
		$prefix=$this->input->post('EMP_C_PREFIX');
		$active_yn=$this->input->post('EMP_C_ACTIVE_YN');
		$sql="UPDATE employee_category SET EMP_C_NAME='$name',EMP_C_PREFIX='$prefix',EMP_C_ACTIVE_YN='$active_yn' WHERE EMP_C_ID='$id'";
		$this->db->query($sql);
		return true;
	}
	
	// Employee Department 
    public function employeeDepartmentView(){
		$sql="SELECT * FROM `employee_department`";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		return $result;
	}
	public function empDepartmentAdd(){
		$name=$this->input->post('EMP_D_NAME');
	    $code=$this->input->post('EMP_D_CODE');
	    $status=$this->input->post('EMP_D_STATUS');
		$sql="INSERT INTO employee_department (EMP_D_NAME, EMP_D_CODE, EMP_D_STATUS) VALUES ('$name','$code','$status')";
		$this->db->query($sql);
		return true;
	}
	public function empDepartmentDelete($id){
		$sql="DELETE FROM employee_department WHERE EMP_D_ID='$id'";
		$result = $this->db->query($sql);
		return $this->db->affected_rows();
	}
	public function empDepartmentEdit($id){
		$sql="SELECT * FROM `employee_department` where EMP_D_ID='$id'";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		return $result;
	}
	public function empDepartmentUpdate($id){
		$name=$this->input->post('EMP_D_NAME');
	    $code=$this->input->post('EMP_D_CODE');
	    $status=$this->input->post('EMP_D_STATUS');
		$sql="UPDATE employee_department SET EMP_D_NAME='$name',EMP_D_CODE='$code',EMP_D_STATUS='$status' WHERE EMP_D_ID='$id'";
		$this->db->query($sql);
		return true;
	}
	// Employee Position 
    public function employeePositionView(){
		$sql="SELECT * FROM `employee_position`";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		return $result;
	}
	public function empPositionAdd(){
		$cat_id=$this->input->post('EMP_P_CATEGORY_ID');
		$name=$this->input->post('EMP_P_NAME');
		$status=$this->input->post('EMP_P_ACTIVE_YN');
		$sql="INSERT INTO employee_position (EMP_P_CATEGORY_ID, EMP_P_NAME, EMP_P_ACTIVE_YN) VALUES ('$cat_id','$name','$status')";
		$this->db->query($sql);
		return true;
	}
	public function empPositionDelete($id){
		$sql="DELETE FROM employee_position WHERE EMP_P_ID='$id'";
		$result = $this->db->query($sql);
		return $this->db->affected_rows();
	}
	public function empPositionEdit($id){
		$sql="SELECT * FROM `employee_position` where EMP_P_ID='$id'";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		return $result;
	}
	public function empPositionUpdate($id){
		$cat_id=$this->input->post('EMP_P_CATEGORY_ID');
		$name=$this->input->post('EMP_P_NAME');
		$status=$this->input->post('EMP_P_ACTIVE_YN');
		$sql="UPDATE employee_position SET EMP_P_CATEGORY_ID='$cat_id',EMP_P_NAME='$name',EMP_P_ACTIVE_YN='$status' WHERE EMP_P_ID='$id'";
		$this->db->query($sql);
		return true;
	}
}

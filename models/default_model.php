<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class default_model extends CI_Model {
		function fetchEmployeeCategory(){
			$sql="SELECT EMP_C_ID,EMP_C_NAME FROM employee_category";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchFinanceCategory(){
			$sql="SELECT FINC_CA_ID,FINC_CA_NAME FROM finance_category";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getAutoIncermentEmp_Code(){
			$sql="SELECT * FROM emp_auto_gen";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function search($val){
			$sql="SELECT * FROM user WHERE USER_TYPE like '$val%'";
			// LIKE  '%''".$val."''%'
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchAllUserType(){
			$sql="SELECT DISTINCT USER_TYPE FROM user";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchAdmissionDetails($id){
			$sql="SELECT * FROM employee_admission where EMP_NO='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchUsersDetails($id){
			$sql="SELECT * FROM user where USER_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
	}
?>
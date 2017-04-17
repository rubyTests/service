<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class setting_model extends CI_Model {
		// Marital
		public function getmaritalDetails(){
			$sql="SELECT * FROM marital";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		// Nationality
		public function fetchNationalityList(){
			$sql="SELECT * FROM nationality";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		// Employee List
		public function fetchEmployeeList(){
			$sql="SELECT DISTINCT PROFILE.ID,CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS FULLNAME
					FROM employee_profile JOIN PROFILE ON employee_profile.PROFILE_ID=PROFILE.ID";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
	}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommonMod extends CI_Model {
	//Login
	public function login(){
		$email = $_POST['USER_EMAIL'];
		$password = $_POST['USER_PASSWORD'];
		$key = random_string('alnum', 10);
		$sql="SELECT * FROM user WHERE USER_EMAIL='$email' AND USER_PASSWORD='$password'";
		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
		if($result[0]['USER_EMAIL']!=""){
			$id=$result[0]['USER_ID'];
			$sql="SELECT * FROM `keys` WHERE user_id=1";
			$res = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($res[0]['user_id']!=""){				
				$user_id=$res[0]['user_id'];
				$sql="UPDATE keys SET key='$key',WHERE user_id='$user_id'";
				$this->db->query($sql);
				$status = "key Updated";
				return $status;
			}else {
				$sql="INSERT INTO keys (user_id, key) VALUES ('$res[0]['user_id']','$key')";
				$this->db->query($sql);
				$status = "key Inserted";
				return $status;
			}
			
			
			
			return $result[0]['USER_ID'];
		}else {
			$sql="INSERT INTO employee_position (EMP_P_CATEGORY_ID, EMP_P_NAME, EMP_P_ACTIVE_YN) VALUES ('$cat_id','$name','$status')";
			$this->db->query($sql);
			return true;
		}
	}
}

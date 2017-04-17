<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class leavemgmntmodel extends CI_Model {

		// ------------------------------ Asset -----------------------------------------------------------------
		public function addLeaveResetData(){
	    	$data = json_decode(file_get_contents("php://input"));
	    	$id=$data->EMP_RES_ID;
	    	$emp_no=$data->EMP_RES_EMP_NO;
	    	$reset_date=$data->EMP_RES_DT;
	    	$resetby=$data->EMP_RES_BY;

	    	$sql="SELECT count(EMP_RES_EMP_NO) FROM employee_reset WHERE EMP_RES_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(EMP_RES_EMP_NO)']!=0){
				$sql="UPDATE employee_reset SET EMP_RES_EMP_NO='$emp_no',EMP_RES_DT='$reset_date',EMP_RES_BY='$resetby' WHERE EMP_RES_ID='$id'";
				$this->db->query($sql);
				return array('status'=>true, 'message'=>"Record Updated Successfully");
			}else {
				$sql="INSERT INTO employee_reset (EMP_RES_EMP_NO, EMP_RES_DT, EMP_RES_BY) VALUES ('$emp_no','$reset_date','$resetby')";
				$this->db->query($sql);
				return array('status'=>true, 'message'=>"Record Inserted Successfully");
			}
	    	
	    }
	    function getLeaveReset_details($id){
	    	$sql="SELECT * FROM employee_reset where EMP_RES_ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function getAllLeaveReset_details(){
	    	$sql="SELECT * FROM employee_reset";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function deleteLeaveReset($id){
	    	$sql="DELETE FROM employee_reset WHERE EMP_RES_ID='$id'";
	    	$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
	    }
	}
?>
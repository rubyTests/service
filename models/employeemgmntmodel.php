<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class employeemgmntmodel extends CI_Model {

		// ------------------------------ Asset -----------------------------------------------------------------
		public function saveEmployeeAdmission($data){
			$id=$data['EMP_ID'];
			if($id == null){
				$data = array(
				   'EMP_NO' => $data['EMP_NO'],
				   'EMP_JOIN_DT' => $data['EMP_JOIN_DT'],
				   'EMP_FIRST_NAME' => $data['EMP_FIRST_NAME'],
				   'EMP_LAST_NAME' => $data['EMP_LAST_NAME'],
				   'EMP_GENDER' => $data['EMP_GENDER'],
				   'EMP_DOB' => $data['EMP_DOB'],
				   'EMP_MARITAL_STATUS' => $data['EMP_MARITAL_STATUS'],
				   'EMP_RELIGION' => $data['EMP_RELIGION'],
				   'EMP_BLOOD_GROUP' => $data['EMP_BLOOD_GROUP'],
				   'EMP_NATIONALITY' => $data['EMP_NATIONALITY'],
				   'EMP_PROFILE' => $data['EMP_PROFILE'],
				   'EMP_DEPT' => $data['EMP_DEPT'],
				   'EMP_CATEGORY' => $data['EMP_CATEGORY'],
				   'EMP_POSITION' => $data['EMP_POSITION'],
				   'EMP_GRADE' => $data['EMP_GRADE'],
				   'EMP_JOB_TITLE' => $data['EMP_JOB_TITLE'],
				   'EMP_QUALI' => $data['EMP_QUALI'],
				   'EMP_EXPE_INFO' => $data['EMP_EXPE_INFO'],
				   'EMP_TOT_EXPE' => $data['EMP_TOT_EXPE'],
				   'EMP_ADD_1' => $data['EMP_ADD_1'],
				   'EMP_ADD_2' => $data['EMP_ADD_2'],
				   'EMP_CITY' => $data['EMP_CITY'],
				   'EMP_STATE' => $data['EMP_STATE'],
				   'EMP_PINCODE' => $data['EMP_PINCODE'],
				   'EMP_COUNTRY' => $data['EMP_COUNTRY'],
				   'EMP_PHONE' => $data['EMP_PHONE'],
				   'EMP_OFF_PHONE' => $data['EMP_OFF_PHONE'],
				   'EMP_MOBILE' => $data['EMP_MOBILE'],
				   'EMP_EMAIL' => $data['EMP_EMAIL'],
				   'EMP_ACC_NAME' => $data['EMP_ACC_NAME'],
				   'EMP_ACC_NO' => $data['EMP_ACC_NO'],
				   'EMP_BANK_NAME' => $data['EMP_BANK_NAME'],
				   'EMP_BANK_BRANCH_NAME' => $data['EMP_BANK_BRANCH_NAME'],
				   'EMP_PASSPORT_NO' => $data['EMP_PASSPORT_NO'],
				   'EMP_PAN_NO' => $data['EMP_PAN_NO'],
				   'EMP_ADHAR_NO' => $data['EMP_ADHAR_NO'],
				   'EMP_WORK_PERMIT' => $data['EMP_WORK_PERMIT']
				);
				$this->db->insert('employee_admission', $data);
				$emp_id= $this->db->insert_id();
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'INS_EMP_ID'=>$emp_id);
			}else {
				$data = array(
			   'EMP_NO' => $data['EMP_NO'],
			   'EMP_JOIN_DT' => $data['EMP_JOIN_DT'],
			   'EMP_FIRST_NAME' => $data['EMP_FIRST_NAME'],
			   'EMP_LAST_NAME' => $data['EMP_LAST_NAME'],
			   'EMP_GENDER' => $data['EMP_GENDER'],
			   'EMP_DOB' => $data['EMP_DOB'],
			   'EMP_MARITAL_STATUS' => $data['EMP_MARITAL_STATUS'],
			   'EMP_RELIGION' => $data['EMP_RELIGION'],
			   'EMP_BLOOD_GROUP' => $data['EMP_BLOOD_GROUP'],
			   'EMP_NATIONALITY' => $data['EMP_NATIONALITY'],
			   'EMP_PROFILE' => $data['EMP_PROFILE'],
			   'EMP_DEPT' => $data['EMP_DEPT'],
			   'EMP_CATEGORY' => $data['EMP_CATEGORY'],
			   'EMP_POSITION' => $data['EMP_POSITION'],
			   'EMP_GRADE' => $data['EMP_GRADE'],
			   'EMP_JOB_TITLE' => $data['EMP_JOB_TITLE'],
			   'EMP_QUALI' => $data['EMP_QUALI'],
			   'EMP_EXPE_INFO' => $data['EMP_EXPE_INFO'],
			   'EMP_TOT_EXPE' => $data['EMP_TOT_EXPE'],
			   'EMP_ADD_1' => $data['EMP_ADD_1'],
			   'EMP_ADD_2' => $data['EMP_ADD_2'],
			   'EMP_CITY' => $data['EMP_CITY'],
			   'EMP_STATE' => $data['EMP_STATE'],
			   'EMP_PINCODE' => $data['EMP_PINCODE'],
			   'EMP_COUNTRY' => $data['EMP_COUNTRY'],
			   'EMP_PHONE' => $data['EMP_PHONE'],
			   'EMP_OFF_PHONE' => $data['EMP_OFF_PHONE'],
			   'EMP_MOBILE' => $data['EMP_MOBILE'],
			   'EMP_EMAIL' => $data['EMP_EMAIL'],
			   'EMP_ACC_NAME' => $data['EMP_ACC_NAME'],
			   'EMP_ACC_NO' => $data['EMP_ACC_NO'],
			   'EMP_BANK_NAME' => $data['EMP_BANK_NAME'],
			   'EMP_BANK_BRANCH_NAME' => $data['EMP_BANK_BRANCH_NAME'],
			   'EMP_PASSPORT_NO' => $data['EMP_PASSPORT_NO'],
			   'EMP_PAN_NO' => $data['EMP_PAN_NO'],
			   'EMP_ADHAR_NO' => $data['EMP_ADHAR_NO'],
			   'EMP_WORK_PERMIT' => $data['EMP_WORK_PERMIT']
			);
			$this->db->where('EMP_ID', $id);
			$this->db->update('employee_admission', $data);
			return array('status'=>true, 'message'=>"Record Updated Successfully",'INS_EMP_ID'=>$id);
			}
			
	    }

	    function fetchEmployeeDetails(){
	    	$sql="SELECT * FROM employee_admission";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function fetchPerticularEmployeeDetails($id){
	    	$sql="SELECT * FROM employee_admission WHERE EMP_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function deleteEmployeeDetails($id){
	    	$sql="DELETE FROM employee_admission WHERE EMP_ID='$id'";
	    	$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
	    }
	    function updategenerationCode($gener_id,$gener_code){
	    	$genr_name = substr($gener_code,0,3);
	    	$genr_code = substr($gener_code,3);
	    	$new_gen_code = $genr_code+1;
	    	$final_val=$genr_name.$new_gen_code;
	    	$this->db->where('id', $gener_id);
	    	$data= array(
                'EMP_AUTO_GEN_CODE'=>$final_val,
	    	);
	    	$this->db->update('emp_auto_gen',$data);
	    }
	    function addtousertable($gener_code,$f_name,$l_name){
	    	$data = array(
			   'USER_ID' => $this->input->post('USER_ID'),
			   'USER_FIRST_NAME' => $f_name,
			   'USER_LAST_NAME' => $l_name,
			   'USER_PASSWORD' => '123',
			   'USER_TYPE' => 'student',
			   'USER_UNIQ_VALUE' => $gener_code
			);
			$this->db->insert('user', $data); 
	    }
	    function addPayrollDetails(){
	    	$data = array(
			   'EMP_PAY_EMP_NO' => $this->input->post('EMP_PAY_EMP_NO'),
			   'EMP_PAY_GRP_NAME' => $this->input->post('EMP_PAY_GRP_NAME'),
			   'EMP_PAY_GROSS_PAY' => $this->input->post('EMP_PAY_GROSS_PAY'),
			   'EMP_PAY_CATE_TYPE' => $this->input->post('EMP_PAY_CATE_TYPE'),
			   'EMP_PAY_CATE_NAME' => $this->input->post('EMP_PAY_CATE_NAME'),
			   'EMP_PAY_CATE_AMT' => $this->input->post('EMP_PAY_CATE_AMT'),
			   'EMP_PAY_TOT_ERN_AMT' => $this->input->post('EMP_PAY_TOT_ERN_AMT'),
			   'EMP_PAY_TOT_DED_AMT' => $this->input->post('EMP_PAY_TOT_DED_AMT'),
			   'EMP_PAY_NET_AMT' => $this->input->post('EMP_PAY_NET_AMT')
			);
			$this->db->insert('employee_payment', $data); 
			return true;
	    }

		// -------------------------------------------- Subject Association ---------------------------------------------------
	    function addSubjectAssociation(){
	    	$id=$this->input->post('EMP_AS_ID');
	    	$batch=$this->input->post('EMP_AS_BATCH');
	    	$subject=$this->input->post('EMP_AS_SUBJECT');
	    	$emp_no=$this->input->post('EMP_AS_EMP_NO');
	    	$emp_name=$this->input->post('EMP_AS_EMP_NAME');
	    	$status=$this->input->post('EMP_AS_ASSIGNED_YN');
	    	$sql="SELECT count(EMP_AS_BATCH) FROM employee_sub_assoc WHERE EMP_AS_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(EMP_AS_BATCH)']!=0){
				$sql="UPDATE employee_sub_assoc SET EMP_AS_BATCH='$batch',EMP_AS_SUBJECT='$subject',EMP_AS_EMP_NO='$emp_no',EMP_AS_EMP_NAME='$emp_name',EMP_AS_ASSIGNED_YN='$status' WHERE EMP_AS_ID='$id'";
				$this->db->query($sql);
				return true;
			}else {
				$sql="INSERT INTO employee_sub_assoc (EMP_AS_BATCH,EMP_AS_SUBJECT,EMP_AS_EMP_NO,EMP_AS_EMP_NAME,EMP_AS_ASSIGNED_YN) VALUES ('$batch','$subject','$emp_no','$emp_name','$status')";
				$this->db->query($sql);
				return true;
			}
	    }
	    function getSubAssociation($id){
	    	$sql="SELECT * FROM employee_sub_assoc where EMP_AS_ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function deleteAssociationData($id){
	    	$sql="DELETE FROM employee_sub_assoc WHERE EMP_AS_ID='$id'";
	    	$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
	    }
	}
?>
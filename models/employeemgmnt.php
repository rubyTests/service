<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class employeemgmnt extends CI_Model {

		// ------------------------------ Asset -----------------------------------------------------------------
		public function saveEmployeeAdmission(){
			$f_name=$this->input->post('EMP_FIRST_NAME');
			$l_name=$this->input->post('EMP_LAST_NAME');
			$sql="SELECT * FROM emp_auto_gen";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$gener_id=$result[0]['ID'];
			$gener_code=$result[0]['EMP_AUTO_GEN_CODE'];
			$data = array(
			   'EMP_NO' => $gener_code,
			   'EMP_JOIN_DT' => $this->input->post('EMP_JOIN_DT'),
			   'EMP_FIRST_NAME' => $this->input->post('EMP_FIRST_NAME'),
			   'EMP_LAST_NAME' => $this->input->post('EMP_LAST_NAME'),
			   'EMP_GENDER' => $this->input->post('EMP_GENDER'),
			   'EMP_DOB' => $this->input->post('EMP_DOB'),
			   'EMP_MARITAL_STATUS' => $this->input->post('EMP_MARITAL_STATUS'),
			   'EMP_RELIGION' => $this->input->post('EMP_RELIGION'),
			   'EMP_BLOOD_GROUP' => $this->input->post('EMP_BLOOD_GROUP'),
			   'EMP_NATIONALITY' => $this->input->post('EMP_NATIONALITY'),
			   'EMP_PROFILE' => $this->input->post('EMP_PROFILE'),
			   'EMP_DEPT' => $this->input->post('EMP_DEPT'),
			   'EMP_CATEGORY' => $this->input->post('EMP_CATEGORY'),
			   'EMP_POSITION' => $this->input->post('EMP_POSITION'),
			   'EMP_GRADE' => $this->input->post('EMP_GRADE'),
			   'EMP_JOB_TITLE' => $this->input->post('EMP_JOB_TITLE'),
			   'EMP_QUALI' => $this->input->post('EMP_QUALI'),
			   'EMP_EXPE_INFO' => $this->input->post('EMP_EXPE_INFO'),
			   'EMP_TOT_EXPE' => $this->input->post('EMP_TOT_EXPE'),
			   'EMP_ADD_1' => $this->input->post('EMP_ADD_1'),
			   'EMP_ADD_2' => $this->input->post('EMP_ADD_2'),
			   'EMP_CITY' => $this->input->post('EMP_CITY'),
			   'EMP_STATE' => $this->input->post('EMP_STATE'),
			   'EMP_PINCODE' => $this->input->post('EMP_PINCODE'),
			   'EMP_COUNTRY' => $this->input->post('EMP_COUNTRY'),
			   'EMP_PHONE' => $this->input->post('EMP_PHONE'),
			   'EMP_OFF_PHONE' => $this->input->post('EMP_OFF_PHONE'),
			   'EMP_MOBILE' => $this->input->post('EMP_MOBILE'),
			   'EMP_EMAIL' => $this->input->post('EMP_EMAIL'),
			   'EMP_ACC_NAME' => $this->input->post('EMP_ACC_NAME'),
			   'EMP_ACC_NO' => $this->input->post('EMP_ACC_NO'),
			   'EMP_BANK_NAME' => $this->input->post('EMP_BANK_NAME'),
			   'EMP_BANK_BRANCH_NAME' => $this->input->post('EMP_BANK_BRANCH_NAME'),
			   'EMP_PASSPORT_NO' => $this->input->post('EMP_PASSPORT_NO'),
			   'EMP_PAN_NO' => $this->input->post('EMP_PAN_NO'),
			   'EMP_ADHAR_NO' => $this->input->post('EMP_ADHAR_NO'),
			   'EMP_WORK_PERMIT' => $this->input->post('EMP_WORK_PERMIT')
			);
			$this->db->insert('employee_admission', $data); 
			$this->addtousertable($gener_code,$f_name,$l_name);
			$this->updategenerationCode($gener_id,$gener_code);
			return true;
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
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class studentmodel extends CI_Model {

		// Student Admission
		
		public function addStudentAdmissionDetails($values){
			$data = array(
			   'STU_ADM_NO' => $values['STU_ADM_NO'],
			   'STU_ADM_DT' => $values['STU_ADM_DT'],
			   'STU_ADM_FIRST_NAME' => $values['STU_ADM_FIRST_NAME'],
			   'STU_ADM_MIDDLE_NAME' => $values['STU_ADM_MIDDLE_NAME'],
			   'STU_ADM_LAST_NAME' => $values['STU_ADM_LAST_NAME'],
			   'STU_ADM_DOB' => $values['STU_ADM_DOB'],
			   'STU_ADM_GENDER' => $values['STU_ADM_GENDER'],
			   'STU_ADM_NATIONALITY' => $values['STU_ADM_NATIONALITY'],
			   'STU_ADM_MOTHER_TONGUE' => $values['STU_ADM_MOTHER_TONGUE'],
			   'STU_ADM_RELIGION' => $values['STU_ADM_RELIGION'],
			   'STU_ADM_ADD1' => $values['STU_ADM_ADD1'],
			   'STU_ADM_ADD2' => $values['STU_ADM_ADD2'],
			   'STU_ADM_CITY' => $values['STU_ADM_CITY'],
			   'STU_ADM_STATE' => $values['STU_ADM_STATE'],
			   'STU_ADM_COUNTRY' => $values['STU_ADM_COUNTRY'],
			   'STU_ADM_PINCODE' => $values['STU_ADM_PINCODE'],
			   'STU_ADM_PHONE' => $values['STU_ADM_PHONE'],
			   'STU_ADM_MOBILE' => $values['STU_ADM_MOBILE'],
			   'STU_ADM_EMAIL' => $values['STU_ADM_EMAIL'],
			   'STU_ADM_CB_COURSE' => $values['STU_ADM_CB_COURSE'],
			   'STU_ADM_CB_BATCH' => $values['STU_ADM_CB_BATCH'],
			   'STU_ADM_CB_ROLL_NO' => $values['STU_ADM_CB_ROLL_NO'],
			   'STU_ADM_USER_ID' => $values['STU_ADM_USER_ID']
			);
			$this->db->insert('student_admission_details', $data); 
			$adm_id= $this->db->insert_id();
			//return $adm_id;
			if(!empty($adm_id)){
				//Add student parent details
				$data = array(
				   'STU_PA_ADM_NO' => $values['STU_ADM_NO'],
				   'STU_PA_USER_ID' => $values['STU_ADM_USER_ID']
				);
				$this->db->insert('student_parent_details', $data);
				//Add Student Previous Education
				$data = array(
				   'STU_PRE_D_ADM_NO' => $values['STU_ADM_NO'],
				   'STU_PRE_D_USER_ID' => $values['STU_ADM_USER_ID']
				);
				$this->db->insert('student_previous_education', $data);
				return $values['STU_ADM_NO'];
			}
	    }
		public function editStudentAdmissionDetails($id,$values){
			$data = array(
			   'STU_ADM_NO' => $values['STU_ADM_NO'],
			   'STU_ADM_DT' => $values['STU_ADM_DT'],
			   'STU_ADM_FIRST_NAME' => $values['STU_ADM_FIRST_NAME'],
			   'STU_ADM_MIDDLE_NAME' => $values['STU_ADM_MIDDLE_NAME'],
			   'STU_ADM_LAST_NAME' => $values['STU_ADM_LAST_NAME'],
			   'STU_ADM_DOB' => $values['STU_ADM_DOB'],
			   'STU_ADM_GENDER' => $values['STU_ADM_GENDER'],
			   'STU_ADM_NATIONALITY' => $values['STU_ADM_NATIONALITY'],
			   'STU_ADM_MOTHER_TONGUE' => $values['STU_ADM_MOTHER_TONGUE'],
			   'STU_ADM_RELIGION' => $values['STU_ADM_RELIGION'],
			   'STU_ADM_ADD1' => $values['STU_ADM_ADD1'],
			   'STU_ADM_ADD2' => $values['STU_ADM_ADD2'],
			   'STU_ADM_CITY' => $values['STU_ADM_CITY'],
			   'STU_ADM_STATE' => $values['STU_ADM_STATE'],
			   'STU_ADM_COUNTRY' => $values['STU_ADM_COUNTRY'],
			   'STU_ADM_PINCODE' => $values['STU_ADM_PINCODE'],
			   'STU_ADM_PHONE' => $values['STU_ADM_PHONE'],
			   'STU_ADM_MOBILE' => $values['STU_ADM_MOBILE'],
			   'STU_ADM_EMAIL' => $values['STU_ADM_EMAIL'],
			   'STU_ADM_CB_COURSE' => $values['STU_ADM_CB_COURSE'],
			   'STU_ADM_CB_BATCH' => $values['STU_ADM_CB_BATCH'],
			   'STU_ADM_CB_ROLL_NO' => $values['STU_ADM_CB_ROLL_NO'],
			   'STU_ADM_USER_ID' => $values['STU_ADM_USER_ID']
			);
			$this->db->where('STU_ADM_ID', $id);
			$this->db->update('student_admission_details', $data);
			return $values['STU_ADM_NO'];
	    }

	    public function editStudentParentDetails($id,$values){
			$data = array(
			   'STU_PA_FIRST_NAME' => $values['STU_PA_FIRST_NAME'],
			   'STU_PA_LAST_NAME' => $values['STU_PA_LAST_NAME'],
			   'STU_PA_RELATION' => $values['STU_PA_RELATION'],
			   'STU_PA_DOB' => $values['STU_PA_DOB'],
			   'STU_PA_EDUCATION' => $values['STU_PA_EDUCATION'],
			   'STU_PA_OCCUPATION' => $values['STU_PA_OCCUPATION'],
			   'STU_PA_INCOME' => $values['STU_PA_INCOME'],
			   'STU_PA_EMAIL' => $values['STU_PA_EMAIL'],
			   'STU_PA_ADD1' => $values['STU_PA_ADD1'],
			   'STU_PA_ADD2' => $values['STU_PA_ADD2'],
			   'STU_PA_CITY' => $values['STU_PA_CITY'],
			   'STU_PA_STATE' => $values['STU_PA_STATE'],
			   'STU_PA_COUNTRY' => $values['STU_PA_COUNTRY'],
			   'STU_PA_PHONE1' => $values['STU_PA_PHONE1'],
			   'STU_PA_PHONE2' => $values['STU_PA_PHONE2'],
			   'STU_PA_MOBILE' => $values['STU_PA_MOBILE'],
			   'STU_PA_GA_NAME' => $values['STU_PA_GA_NAME'],
			   'STU_PA_GA_RELATION' => $values['STU_PA_GA_RELATION'],
			   'STU_PA_GA_ADD1' => $values['STU_PA_GA_ADD1'],
			   'STU_PA_GA_ADD2' => $values['STU_PA_GA_ADD2'],
			   'STU_PA_GA_CITY' => $values['STU_PA_GA_CITY'],
			   'STU_PA_GA_STATE' => $values['STU_PA_GA_STATE'],
			   'STU_PA_GA_COUNTRY' => $values['STU_PA_GA_COUNTRY'],
			   'STU_PA_GA_PHONE1' => $values['STU_PA_GA_PHONE1'],
			   'STU_PA_UPD_USER_ID' => $values['STU_PA_UPD_USER_ID']
			);
			$this->db->where('STU_PA_ADM_NO', $id);
			$this->db->update('student_parent_details', $data);
			return true;
	    }

	    public function editStudentPreviousEducation($id,$values){
			$data = array(
			   'STU_PRE_D_INSTITUTE_NAME' => $values['STU_PRE_D_INSTITUTE_NAME'],
			   'STU_PRE_D_COURSE' => $values['STU_PRE_D_COURSE'],
			   'STU_PRE_D_YEAR' => $values['STU_PRE_D_YEAR'],
			   'STU_PRE_ADD_BLOOD_GROUP' => $values['STU_PRE_ADD_BLOOD_GROUP'],
			   'STU_PRE_ADD_BIRTH_PLACE' => $values['STU_PRE_ADD_BIRTH_PLACE'],
			   'STU_PRE_ADD_STUD_CATE' => $values['STU_PRE_ADD_STUD_CATE'],
			   'STU_PRE_ADD_IMAGE_PATH' => $values['STU_PRE_ADD_IMAGE_PATH'],
			   'STU_PRE_D_UPD_USER_ID' => $values['STU_PRE_D_UPD_USER_ID']
			);
			$this->db->where('STU_PRE_D_ADM_NO', $id);
			$this->db->update('student_previous_education', $data);
			return true;
	    }

	    public function addStudentCategory(){
			$data = array(
			   'STU_CATE_TYPE_NAME' => $this->input->post('STU_CATE_TYPE_NAME')
			);
			$this->db->insert('student_category', $data);  	
	    }
		
		public function getStudentDetailAll(){
			$sql="SELECT STU_ADM_NO,STU_ADM_FIRST_NAME,STU_ADM_CB_COURSE,STU_ADM_GENDER FROM student_admission_details";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		public function getStudentDetail($id){
			$sql="SELECT * FROM student_admission_details,student_parent_details,student_previous_education where (STU_ADM_NO='$id' AND STU_PA_ADM_NO='$id' AND STU_PRE_D_ADM_NO='$id')";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		function getStudentAdmissionDetails(){
			$sql="SELECT * FROM student_admission_view";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
	}
?>
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class batchmodel extends CI_Model {

		// Acodemics Class And Batch 
		
		public function addBatchDetail($values){
			$data = array(
			   'ACA_BAT_COU_ID' => $values['ACA_BAT_COU_ID'],
			   'ACA_BAT_NAME' => $values['ACA_BAT_NAME'],
			   'ACA_BAT_START_DT' => $values['ACA_BAT_START_DT'],
			   'ACA_BAT_END_DT' => $values['ACA_BAT_END_DT'],
			   'ACA_BAT_IMP_PRE_BAT_SUB_YN' => $values['ACA_BAT_IMP_PRE_BAT_SUB_YN'],
			   'ACA_BAT_IMP_PRE_BAT_MASTER_FEE_YN' => $values['ACA_BAT_IMP_PRE_BAT_MASTER_FEE_YN'],
			   'ACA_BAT_CRT_USER_ID' => $values['ACA_BAT_CRT_USER_ID']
			);
			$this->db->insert('Acodemics_batch_details', $data); 
			$adm_id= $this->db->insert_id();
			return $adm_id;
	    }
		
		public function editBatchDetail($id,$values){
			$data = array(
			   'ACA_BAT_COU_ID' => $values['ACA_BAT_COU_ID'],
			   'ACA_BAT_NAME' => $values['ACA_BAT_NAME'],
			   'ACA_BAT_START_DT' => $values['ACA_BAT_START_DT'],
			   'ACA_BAT_END_DT' => $values['ACA_BAT_END_DT'],
			   'ACA_BAT_IMP_PRE_BAT_SUB_YN' => $values['ACA_BAT_IMP_PRE_BAT_SUB_YN'],
			   'ACA_BAT_IMP_PRE_BAT_MASTER_FEE_YN' => $values['ACA_BAT_IMP_PRE_BAT_MASTER_FEE_YN'],
			   'ACA_BAT_UPD_USER_ID' => $values['ACA_BAT_UPD_USER_ID']
			);
			$this->db->where('ACA_BAT_ID', $id);
			$this->db->update('Acodemics_batch_details', $data); 
			return true;
	    }
		
		public function getBatchDetailAll(){
			// $sql="SELECT * FROM `Acodemics_batch_details`";
			$sql="SELECT * FROM `batch_view`";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function getBatchDetail($id){
			// $sql="SELECT * FROM `Acodemics_batch_details` where ACA_BAT_COU_ID='$id'";
			$sql="SELECT * FROM `batch_view` where ACA_BAT_COU_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deleteBatchDetail($id){
			$sql="DELETE FROM `Acodemics_batch_details` WHERE `ACA_BAT_ID`='$id'";
			$result = $this->db->query($sql);
			return $this->db->affected_rows();
		}
		
		public function getBatch($id){
			$sql="SELECT * FROM `Acodemics_batch_details` where ACA_BAT_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		// Acodemics Subject Details
		
		public function addSubjectDetail($values){
			$data = array(
			   'ACA_SUB_COU_ID' => $values['ACA_SUB_COU_ID'],
			   'ACA_SUB_BAT_ID' => $values['ACA_SUB_BAT_ID'],
			   'ACA_SUB_NAME' => $values['ACA_SUB_NAME'],
			   'ACA_SUB_CODE' => $values['ACA_SUB_CODE'],
			   'ACA_SUB_MAXCLASS_WEEK' => $values['ACA_SUB_MAXCLASS_WEEK'],
			   'ACA_SUB_NOEXAM_YN' => $values['ACA_SUB_NOEXAM_YN'],
			   'ACA_SUB_SIXTH_SUB_YN' => $values['ACA_SUB_SIXTH_SUB_YN'],
			   'ACA_SUB_ASL_SUB_YN' => $values['ACA_SUB_ASL_SUB_YN'],
			   'ACA_SUB_ASL_MARK' => $values['ACA_SUB_ASL_MARK'],
			   'ACA_SUB_ELECT_YN' => $values['ACA_SUB_ELECT_YN'],
			   'ACA_SUB_ELECT_SUB_NAME' => $values['ACA_SUB_ELECT_SUB_NAME'],
			   'ACA_SUB_CRT_USER_ID' => $values['ACA_SUB_CRT_USER_ID']
			);
			$this->db->insert('Acodemics_manage_subject', $data); 
			$adm_id= $this->db->insert_id();
			return $adm_id;
	    }
		
		public function editSubjectDetail($id,$values){
			$data = array(
			   'ACA_SUB_COU_ID' => $values['ACA_SUB_COU_ID'],
			   'ACA_SUB_BAT_ID' => $values['ACA_SUB_BAT_ID'],
			   'ACA_SUB_NAME' => $values['ACA_SUB_NAME'],
			   'ACA_SUB_CODE' => $values['ACA_SUB_CODE'],
			   'ACA_SUB_MAXCLASS_WEEK' => $values['ACA_SUB_MAXCLASS_WEEK'],
			   'ACA_SUB_NOEXAM_YN' => $values['ACA_SUB_NOEXAM_YN'],
			   'ACA_SUB_SIXTH_SUB_YN' => $values['ACA_SUB_SIXTH_SUB_YN'],
			   'ACA_SUB_ASL_SUB_YN' => $values['ACA_SUB_ASL_SUB_YN'],
			   'ACA_SUB_ASL_MARK' => $values['ACA_SUB_ASL_MARK'],
			   'ACA_SUB_ELECT_YN' => $values['ACA_SUB_ELECT_YN'],
			   'ACA_SUB_ELECT_SUB_NAME' => $values['ACA_SUB_ELECT_SUB_NAME'],
			   'ACA_SUB_UPD_USER_ID' => $values['ACA_SUB_UPD_USER_ID']
			);
			$this->db->where('ACA_SUB_ID', $id);
			$this->db->update('Acodemics_manage_subject', $data); 
			return true;
	    }

		public function getSubjectDetailAll(){
			$sql="SELECT * FROM `Acodemics_manage_subject`";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function getSubjectDetail($id){
			$sql="SELECT * FROM `Acodemics_manage_subject` where ACA_SUB_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deleteSubjectDetail($id){
			$sql="DELETE FROM `Acodemics_manage_subject` WHERE `ACA_SUB_ID`='$id'";
			$result = $this->db->query($sql);
			return $this->db->affected_rows();
		}
		
		public function ManageSubjectDetail($id,$id1){
			$sql="SELECT * FROM acodemics_manage_subject WHERE ACA_SUB_COU_ID='$id' AND ACA_SUB_BAT_ID='$id1'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		// Acodemics Grading System
		
		public function addGradingSystem($values){
			$data = array(
			   'GRA_SYS_BAT_ID' => $values['GRA_SYS_BAT_ID'],
			   'GRA_SYS_NAME' => $values['GRA_SYS_NAME'],
			   'GRA_SYS_SCORE_PER' => $values['GRA_SYS_SCORE_PER'],
			   'GRA_SYS_SCORE_DESC' => $values['GRA_SYS_SCORE_DESC'],
			   'GRA_SYS_CRT_USER_ID' => $values['GRA_SYS_CRT_USER_ID']
			);
			$this->db->insert('grading_system', $data); 
			$adm_id= $this->db->insert_id();
			return $adm_id;
	    }
		
		public function editGradingSystem($id,$values){
			$data = array(
			   'GRA_SYS_BAT_ID' => $values['GRA_SYS_BAT_ID'],
			   'GRA_SYS_NAME' => $values['GRA_SYS_NAME'],
			   'GRA_SYS_SCORE_PER' => $values['GRA_SYS_SCORE_PER'],
			   'GRA_SYS_SCORE_DESC' => $values['GRA_SYS_SCORE_DESC'],
			   'GRA_SYS_UPD_USER_ID' => $values['GRA_SYS_UPD_USER_ID']
			);
			$this->db->where('GRA_SYS_ID', $id);
			$this->db->update('grading_system', $data); 
			return true;
	    }
		
		public function getGradingSystemAll(){
			$sql="SELECT * FROM `grading_system`";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function getGradingSystem($id){
			$sql="SELECT * FROM `grading_system` where GRA_SYS_BAT_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deleteGradingSystem($id){
			$sql="DELETE FROM `grading_system` WHERE `GRA_SYS_ID`='$id'";
			$result = $this->db->query($sql);
			return $this->db->affected_rows();
		}
		
		public function getStudent(){
			$sql="SELECT * FROM `Acodemics_batch_details`";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		// View batch detail and student count
		public function viewBatchDetailAll(){
			$sql="SELECT * FROM `Acodemics_batch_details`";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		function getSubjectDetailsFromView(){
			$sql="SELECT * FROM manage_sub_view";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
	}
?>
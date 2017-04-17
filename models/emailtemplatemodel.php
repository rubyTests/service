<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class emailtemplatemodel extends CI_Model {

		// Email Template Details 
		
		public function addEmailTemplate($value){
			$data = array(
			   'E_TEMP_NAME' => $value['E_TEMP_NAME'],
			   'E_TEMP_SUBJECT' => $value['E_TEMP_SUBJECT'],
			   'E_TEMP_BODY' => $value['E_TEMP_BODY'],
			   'E_TEMP_USER_ID' => $value['E_TEMP_USER_ID']
			);
			$this->db->insert('email_template', $data); 
			$cls_id= $this->db->insert_id();
			if(!empty($cls_id)){
				return $cls_id;
			}
	    }
		
		public function editEmailTemplate($id,$value){
			$data = array(
			   'E_TEMP_NAME' => $value['E_TEMP_NAME'],
			   'E_TEMP_SUBJECT' => $value['E_TEMP_SUBJECT'],
			   'E_TEMP_BODY' => $value['E_TEMP_BODY'],
			   'E_TEMP_UPD_USER_ID' => $value['E_TEMP_USER_ID']
			);
			$this->db->where('E_TEMP_ID', $id);
			$this->db->update('email_template', $data);
			return true;
		}
		
		public function getEmailTemplate($id){
			$sql="SELECT * FROM email_template WHERE E_TEMP_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		public function getEmailTemplateAll(){
			$sql="SELECT * FROM email_template";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		public function deleteEmailTemplate($id){
			$sql="DELETE FROM `email_template` WHERE `E_TEMP_ID`='$id'";
			$result = $this->db->query($sql);
			return $val=$this->db->affected_rows();
		}
		
	}
?>
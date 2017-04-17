<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class reviewmodel extends CI_Model {

		// Student Admission
		
		public function addComments($values){
			$data = array(
			   'range' => $values['range'],
			   'service' => $values['service'],
			   'ambience' => $values['ambience'],
			   'suggestion' => $values['suggestion'],
			   'requirement' => $values['requirement'],
			   'overall_experiance' => $values['overall_experiance'],
			   'name' => $values['name'],
			   'mobile_number' => $values['mobile_number'],
			   'email' => $values['email'],
			   'place' => $values['place']
			);
			$this->db->insert('comments', $data); 
			$adm_id= $this->db->insert_id();
			if(!empty($adm_id)){
				return true;
			}
	    }
		
		public function getfeedbackDetails(){
			$sql="SELECT * FROM comments";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
	
	}
?>
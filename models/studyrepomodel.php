<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class studyrepomodel extends CI_Model {

		// Student Admission
		
		public function addStudyRepository($values){
			$data = array(
			   'STY_REP_COU_ID' => $values['STY_REP_COU_ID'],
			   'STY_REP_BAT_ID' => $values['STY_REP_BAT_ID'],
			   'STY_REP_TITLE' => $values['STY_REP_TITLE'],
			   'STY_REP_CONTENT' => $values['STY_REP_CONTENT'],
			   'STY_REP_DESC' => $values['STY_REP_DESC'],
			   'STY_REP_FILE_PATH' => $values['STY_REP_FILE_PATH'],
			   'STY_REP_USER_ID' => $values['STY_REP_USER_ID']
			);
			$this->db->insert('study_material_repository', $data); 
			$adm_id= $this->db->insert_id();
			if(!empty($adm_id)){
				return $adm_id;
			}
	    }
		
		public function editStudyRepository($id,$values){
			$data = array(
			   'STY_REP_COU_ID' => $values['STY_REP_COU_ID'],
			   'STY_REP_BAT_ID' => $values['STY_REP_BAT_ID'],
			   'STY_REP_TITLE' => $values['STY_REP_TITLE'],
			   'STY_REP_CONTENT' => $values['STY_REP_CONTENT'],
			   'STY_REP_DESC' => $values['STY_REP_DESC'],
			   'STY_REP_FILE_PATH' => $values['STY_REP_FILE_PATH'],
			   'STY_REP_UPD_USER_ID' => $values['STY_REP_UPD_USER_ID']
			);
			$this->db->where('STY_REP_ID', $id);
			$this->db->update('study_material_repository', $data);
			return true;
	    }

		public function getStudyRepositoryAll(){
			$sql="SELECT * FROM study_material_repository";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		public function getStudyRepository($id){
			$sql="SELECT * FROM study_material_repository where STY_REP_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
	}
?>
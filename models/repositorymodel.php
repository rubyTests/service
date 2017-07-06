<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class repositorymodel extends CI_Model {
		
		public function getAllCategoryDetails(){
			$sql="SELECT * FROM repository_category";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getCategoryDetails($id){
			$sql="SELECT * FROM repository_category WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addCategoryDetails($values){
			$data = array(
				'NAME' => $values['NAME'],
				'DESC' => $values['DESC']
			);
			$this->db->insert('repository_category', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editCategoryDetails($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'DESC' => $values['DESC']
			);
			$this->db->where('id', $Id);
			$this->db->update('repository_category', $data);
			return array('status'=>true, 'message'=>"Category Updated Successfully",'REP_CAT_ID'=>$Id);
		}
		
		public function deleteCategoryDetails($id){
			$sql="DELETE FROM repository_category where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function getAllRepPostDetails(){
			$sql="SELECT ID,TITLE,REP_CATEGORY_ID,CONTENT,UPLOAD_FILE,CRT_DT, (SELECT NAME FROM repository_category WHERE ID = REP_CATEGORY_ID) AS CATEGORY_NAME FROM repository_post";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getRepPostDetails($id){
			// $sql="SELECT ID,NAME,CATEGORY_ID,DEPT_ID,SUBJECT_ID,AUTHOR,	REGULATION,YEAROFPUBLISHED,ISBN,PUBLISHER,EDITION,PRICE,RACKNO,	C_QUANTITY,IMAGE, FROM l_book WHERE ID='$id'";
			$sql="SELECT ID,TITLE,REP_CATEGORY_ID,COURSE_ID,CONTENT,CRT_DT,UPLOAD_FILE, (SELECT NAME FROM repository_category WHERE ID = REP_CATEGORY_ID) AS CATEGORY_NAME FROM repository_post WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function fetchBookIdDetails($id){
			$sql="SELECT ID,NAME,CODE,CATEGORY_ID,DEPT_ID,SUBJECT_ID,AUTHOR,REGULATION,YEAROFPUBLISHED,ISBN,PUBLISHER,EDITION,PRICE,RACKNO,C_QUANTITY,IMAGE, 
			(SELECT NAME FROM l_category WHERE ID = CATEGORY_ID) AS CATEGORY_NAME,
			(SELECT NAME FROM department WHERE ID = DEPT_ID) AS DEPARTMENT_NAME,
			(SELECT NAME FROM subject WHERE ID = SUBJECT_ID) AS SUBJECT_NAME
			 FROM l_book WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addRepPostDetails($values){
			$filename = $this->fileupload($values['UPLOAD_FILE']['file']);
				$data = array(
					'TITLE' => $values['TITLE'],
					'UPLOAD_FILE' => $filename,
					'CONTENT' => $values['CONTENT'],
					'COURSE_ID' => $values['COURSE_ID'],
					'REP_CATEGORY_ID' => $values['REP_CATEGORY_ID']
				);
				$this->db->insert('repository_post', $data);
				$getId= $this->db->insert_id();
				if($getId){
					return $getId;
				}
		}

		public function fileupload($file){
				$uploaddir = '../RubyCampus/assets/uploads/';
				$uploadfile = $uploaddir . basename($file['name']);
				if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
				    return $file['name'];
				}
		}
		
		public function editRepPostDetails($Id,$values){
			$filename = $this->fileupload($values['UPLOAD_FILE']['file']);
			$data = array(
				'TITLE' => $values['TITLE'],
				'UPLOAD_FILE' => $filename,
				'CONTENT' => $values['CONTENT'],
				'COURSE_ID' => $values['COURSE_ID'],
				'REP_CATEGORY_ID' => $values['REP_CATEGORY_ID']
			);
			$this->db->where('id', $Id);
			$this->db->update('repository_post', $data);
			return array('status'=>true, 'message'=>"Book Details Updated Successfully",'BOOK_ID'=>$Id);
		}
		
		public function deleteRepPostDetails($id){
			$sql="DELETE FROM repository_post where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
	}
?>
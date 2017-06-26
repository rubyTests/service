<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class librarymodel extends CI_Model {
		
		public function getAllCategoryDetails(){
			$sql="SELECT * FROM l_category";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getCategoryDetails($id){
			$sql="SELECT * FROM l_category WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addCategoryDetails($values){
			$data = array(
				'NAME' => $values['NAME'],
				'CODE' => $values['CODE']
			);
			$this->db->insert('l_category', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editCategoryDetails($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'CODE' => $values['CODE']
			);
			$this->db->where('id', $Id);
			$this->db->update('l_category', $data);
		}
		
		public function deleteCategoryDetails($id){
			$sql="DELETE FROM l_category where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function getAllBookDetails(){
			$sql="SELECT * FROM l_book";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getBookDetails($id){
			$sql="SELECT * FROM l_book WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addBookDetails($values){
			$data = array(
				'NAME' => $values['NAME'],
				'CATEGORY_ID' => $values['CATEGORY_ID'],
				'DEPT_ID' => $values['DEPT_ID'],
				'SUBJECT_ID' => $values['SUBJECT_ID'],
				'AUTHOR' => $values['AUTHOR'],
				'REGULATION' => $values['REGULATION'],
				'YEAROFPUBLISHED' => $values['YEAROFPUBLISHED'],
				'ISBN' => $values['ISBN'],
				'PUBLISHER' => $values['PUBLISHER'],
				'EDITION' => $values['EDITION'],
				'PRICE' => $values['PRICE'],
				'RACKNO' => $values['RACKNO'],
				'C_QUANTITY' => $values['C_QUANTITY'],
				'T_QUANTITY' => $values['T_QUANTITY'],
				'IMAGE' => $values['IMAGE']
			);
			$this->db->insert('l_book', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editBookDetails($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'CATEGORY_ID' => $values['CATEGORY_ID'],
				'DEPT_ID' => $values['DEPT_ID'],
				'SUBJECT_ID' => $values['SUBJECT_ID'],
				'AUTHOR' => $values['AUTHOR'],
				'REGULATION' => $values['REGULATION'],
				'YEAROFPUBLISHED' => $values['YEAROFPUBLISHED'],
				'ISBN' => $values['ISBN'],
				'PUBLISHER' => $values['PUBLISHER'],
				'EDITION' => $values['EDITION'],
				'PRICE' => $values['PRICE'],
				'RACKNO' => $values['RACKNO'],
				'C_QUANTITY' => $values['C_QUANTITY'],
				'T_QUANTITY' => $values['T_QUANTITY'],
				'IMAGE' => $values['IMAGE']
			);
			$this->db->where('id', $Id);
			$this->db->update('l_book', $data);
		}
		
		public function deleteBookDetails($id){
			$sql="DELETE FROM l_book where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function getAllBookIssueDetails(){
			$sql="SELECT ID,PROFILE_ID,BOOK_ID,ISSUED_DATETIME,DUE_DATETIME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as profileName,(SELECT NAME FROM l_book where ID=BOOK_ID)as bookName,(SELECT ISBN FROM l_book where ID=BOOK_ID)as ISBN FROM l_book_issue";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getBookIssueDetails($id){
			$sql="SELECT * FROM l_book_issue WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addBookIssueDetails($values){
			$data = array(
				'TYPE' => $values['TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'BOOK_ID' => $values['BOOK_ID'],
				'ISSUED_DATETIME' => $values['ISSUED_DATETIME'],
				'DUE_DATETIME' => $values['DUE_DATETIME']
			);
			$this->db->insert('l_book_issue', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editBookIssueDetails($Id,$values){
			$data = array(
				'TYPE' => $values['TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'BOOK_ID' => $values['BOOK_ID'],
				'ISSUED_DATETIME' => $values['ISSUED_DATETIME'],
				'DUE_DATETIME' => $values['DUE_DATETIME']
			);
			$this->db->where('id', $Id);
			$this->db->update('l_book_issue', $data);
		}
		
		public function deleteBookIssueDetails($id){
			$sql="DELETE FROM l_book_issue where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
	}
?>
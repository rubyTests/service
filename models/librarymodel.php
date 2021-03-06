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
			return array('status'=>true, 'message'=>"Category Updated Successfully",'BOOK_CAT_ID'=>$Id);
		}
		
		public function deleteCategoryDetails($id){
			$sql="DELETE FROM l_category where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function getAllBookDetails(){
			$sql="SELECT ID,NAME,CODE,CATEGORY_ID,AUTHOR,ISBN,T_QUANTITY,C_QUANTITY,IMAGE,STATUS, (SELECT NAME FROM l_category WHERE CATEGORY_ID = ID) AS CATEGORY_NAME FROM l_book";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getBookDetails($id){
			// $sql="SELECT ID,NAME,CATEGORY_ID,DEPT_ID,SUBJECT_ID,AUTHOR,	REGULATION,YEAROFPUBLISHED,ISBN,PUBLISHER,EDITION,PRICE,RACKNO,	C_QUANTITY,IMAGE, FROM l_book WHERE ID='$id'";
			$sql="SELECT * FROM l_book WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		// BookTaken
		
		public function getBookTakenData($profileId){
			$sql="SELECT BOOK_ID,(SELECT NAME FROM l_book WHERE CODE=BOOK_ID)as BOOK_NAME,DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME,DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME,DATEDIFF(CURDATE(),DUE_DATETIME)as daysLeft FROM l_book_issue WHERE PROFILE_ID='$profileId' AND BOOK_RETURN='NO'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function fetchBookIdDetails($id){
			$sql="SELECT ID,NAME,CODE,CATEGORY_ID,DEPT_ID,SUBJECT_ID,AUTHOR,REGULATION,DATE_FORMAT(YEAROFPUBLISHED, '%d-%b-%Y') as YEAROFPUBLISHED,ISBN,PUBLISHER,EDITION,PRICE,RACKNO,C_QUANTITY,IMAGE, 
			(SELECT NAME FROM l_category WHERE ID = CATEGORY_ID) AS CATEGORY_NAME,
			(SELECT NAME FROM department WHERE ID = DEPT_ID) AS DEPARTMENT_NAME,
			(SELECT NAME FROM subject WHERE ID = SUBJECT_ID) AS SUBJECT_NAME
			 FROM l_book WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addBookDetails($values){
			for($i=0;$i<$values['T_QUANTITY'];$i++){ 	 	
				$data = array(
					'NAME' => $values['NAME'],
					'CODE' => $values['CODE'] + $i,
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
					'T_QUANTITY' => $values['T_QUANTITY'],
					'C_QUANTITY' => $values['T_QUANTITY'],
					'IMAGE' => $values['IMAGE'],
					'STATUS' => 'Available'
				);
				$this->db->insert('l_book', $data);
			}
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editBookDetails($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'CODE' => $values['CODE'],
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
				'C_QUANTITY' => $values['T_QUANTITY'],
				'T_QUANTITY' => $values['T_QUANTITY'],
				'IMAGE' => $values['IMAGE']
			);
			$this->db->where('id', $Id);
			$this->db->update('l_book', $data);
			return array('status'=>true, 'message'=>"Book Details Updated Successfully",'BOOK_ID'=>$Id);
		}
		
		public function deleteBookDetails($id){
			$sql="DELETE FROM l_book where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function getAllBookIssueDetails(){
			$sql="SELECT ID,PROFILE_ID,BOOK_ID,DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME,DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as profileName,(SELECT NAME FROM l_book where CODE=BOOK_ID)as BOOK_NAME FROM l_book_issue WHERE BOOK_RETURN = 'NO'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getBookIssueDetails($id){
			$sqlData = "SELECT TYPE,PROFILE_ID FROM l_book_issue WHERE ID = '$id'";
			$result = $this->db->query($sqlData, $return_object = TRUE)->result_array();

			if($result){

				if($result[0]['TYPE'] == "Student"){
					$sql="SELECT ID,TYPE,PROFILE_ID,BOOK_ID,DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME,DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME,
					(SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID=l_book_issue.PROFILE_ID) as batchId,
					(SELECT COURSE_ID FROM course_batch where ID=batchId) as courseId 
					FROM l_book_issue WHERE ID='$id'";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();

				}else if($result[0]['TYPE'] == "Employee"){

					$sql="SELECT TYPE,PROFILE_ID,BOOK_ID,DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME,DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME,
					(SELECT DEPT_ID FROM employee_profile where PROFILE_ID = l_book_issue.PROFILE_ID) as deptId 
					FROM l_book_issue WHERE ID='$id'";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
				}
			}
		}

		public function getBookIssueIdData($id){
			$sqlData = "SELECT TYPE,PROFILE_ID FROM l_book_issue WHERE BOOK_ID = '$id'";
			$result = $this->db->query($sqlData, $return_object = TRUE)->result_array();

			if($result){

				if($result[0]['TYPE'] == "Student"){
					$sql="SELECT ID,TYPE,PROFILE_ID,BOOK_ID,DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME,DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME,
					(SELECT NAME FROM l_book where CODE = BOOK_ID) as BOOK_NAME, 
					(SELECT AUTHOR FROM l_book where CODE = BOOK_ID) as AUTHOR, 
					(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as profileName,
					(SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID=l_book_issue.PROFILE_ID) as batchId,
					(SELECT NAME FROM course_batch where ID = batchId) as BATCH_NAME,
					(SELECT COURSE_ID FROM course_batch where ID = batchId) as courseId,
					(SELECT NAME FROM course where ID = courseId) as COURSE_NAME 
					FROM l_book_issue WHERE BOOK_ID='$id'";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();

				}else if($result[0]['TYPE'] == "Employee"){

					$sql="SELECT ID,TYPE,PROFILE_ID,BOOK_ID,DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME,DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME,
					(SELECT NAME FROM l_book where CODE = BOOK_ID) as BOOK_NAME, 
					(SELECT AUTHOR FROM l_book where CODE = BOOK_ID) as AUTHOR, 
					(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as profileName,
					(SELECT DEPT_ID FROM employee_profile where PROFILE_ID=l_book_issue.PROFILE_ID) as deptId,
					(SELECT NAME FROM department where ID = deptId) as DEPT_NAME 
					FROM l_book_issue WHERE BOOK_ID='$id'";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();

				}
			}
		}

		public function getBatchStudentDetails($id){
			$sql="SELECT PROFILE_ID, (SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as profileName FROM student_profile WHERE COURSEBATCH_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addBookIssueDetails($values){
			$bookId = $values['BOOK_ID'];

			$data = array(
				'BOOK_ID' => $bookId,
				'TYPE' => $values['TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'ISSUED_DATETIME' => $values['ISSUED_DATETIME'],
				'DUE_DATETIME' => $values['DUE_DATETIME'],
				'BOOK_RETURN' => 'NO'
			);
			$this->db->insert('l_book_issue', $data);
			$getId= $this->db->insert_id();
			if($getId){
				
				$sql = "SELECT C_QUANTITY FROM l_book WHERE CODE = '$bookId'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					$currentQty = $result[0]['C_QUANTITY'];
					$data = array(
						'C_QUANTITY' => $currentQty - 1,
						'STATUS' => 'Issued',
					);
					$this->db->where('CODE', $bookId);
					$this->db->update('l_book', $data);
				}

				return $getId;

			}
		}
		
		public function editBookIssueDetails($Id,$values){
			$data = array(
				'BOOK_ID' => $values['BOOK_ID'],
				'TYPE' => $values['TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'ISSUED_DATETIME' => $values['ISSUED_DATETIME'],
				'DUE_DATETIME' => $values['DUE_DATETIME']
			);
			$this->db->where('id', $Id);
			$this->db->update('l_book_issue', $data);
			return array('status'=>true, 'message'=>"Book Details Updated Successfully",'BOOK_ISSUE_ID'=>$Id);
		}
		
		public function deleteBookIssueDetails($id){
			$sql="DELETE FROM l_book_issue where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}

		public function addBookReturnDetails($values){
			$bookId = $values['BOOK_ID'];
			$data = array(
				'BOOK_ISSUE_ID' => $values['BOOK_ISSUE_ID'],
				'RETURN_DATE' => $values['RETURN_DATE'],
				'REMARK' => $values['REMARK']
			);
			$this->db->insert('l_book_return', $data);
			$getId= $this->db->insert_id();
			if($getId){
				$data1 = array(
					'BOOK_RETURN' => 'YES'
				);
				$this->db->where('id', $values['BOOK_ISSUE_ID']);
				$this->db->update('l_book_issue', $data1);

				$sql = "SELECT C_QUANTITY FROM l_book WHERE CODE = '$bookId'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					$currentQty = $result[0]['C_QUANTITY'];
					$data = array(
						'C_QUANTITY' => $currentQty + 1,
					);
					$this->db->where('CODE', $bookId);
					$this->db->update('l_book', $data);
				}

				return $getId;
			}
		}

		public function getAllBookReturnDetails(){
			$sql="SELECT ID,BOOK_ISSUE_ID,DATE_FORMAT(RETURN_DATE, '%d-%b-%Y') as RETURN_DATE,REMARK,
			(SELECT PROFILE_ID FROM l_book_issue where ID=BOOK_ISSUE_ID) as profile_id,
			(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=profile_id) as profileName,
			(SELECT DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME FROM l_book_issue where ID=BOOK_ISSUE_ID) as issue_date,
			(SELECT DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME FROM l_book_issue where ID=BOOK_ISSUE_ID) as due_date,
			(SELECT BOOK_ID FROM l_book_issue where ID=BOOK_ISSUE_ID) as BOOK_ID,
			(SELECT NAME FROM l_book where CODE=BOOK_ID)as BOOK_NAME FROM l_book_return";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function getBookReturnIdViewData($id){

			$sqlData = "SELECT ID,BOOK_ISSUE_ID,DATE_FORMAT(RETURN_DATE, '%d-%b-%Y') as RETURN_DATE,REMARK,
						(SELECT TYPE FROM l_book_issue where ID=BOOK_ISSUE_ID) as user_type
						FROM l_book_return WHERE ID = '$id'";
			$result = $this->db->query($sqlData, $return_object = TRUE)->result_array();

			if($result){

				if($result[0]['user_type'] == "Student"){

					$sql="SELECT ID,BOOK_ISSUE_ID,DATE_FORMAT(RETURN_DATE, '%d-%b-%Y') as RETURN_DATE,REMARK,
					(SELECT TYPE FROM l_book_issue where ID=BOOK_ISSUE_ID) as user_type,
					(SELECT PROFILE_ID FROM l_book_issue where ID=BOOK_ISSUE_ID) as profileData_id,
					(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=profileData_id) as profileName,
					(SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID=profileData_id) as batchId,
					(SELECT NAME FROM course_batch where ID = batchId) as BATCH_NAME,
					(SELECT COURSE_ID FROM course_batch where ID = batchId) as courseId,
					(SELECT NAME FROM course where ID = courseId) as COURSE_NAME,
					(SELECT DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME FROM l_book_issue where ID=BOOK_ISSUE_ID) as issue_date,
					(SELECT DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME FROM l_book_issue where ID=BOOK_ISSUE_ID) as due_date,
					(SELECT BOOK_ID FROM l_book_issue where ID=BOOK_ISSUE_ID) as BOOK_ID,
					(SELECT NAME FROM l_book where CODE=BOOK_ID)as BOOK_NAME,
					(SELECT IMAGE FROM l_book where CODE = BOOK_ID)as BOOK_IMAGE,
					(SELECT AUTHOR FROM l_book where CODE=BOOK_ID)as AUTHOR
					FROM l_book_return WHERE ID='$id'";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();

				}else if($result[0]['user_type'] == "Employee"){
					$sql="SELECT ID,BOOK_ISSUE_ID,DATE_FORMAT(RETURN_DATE, '%d-%b-%Y') as RETURN_DATE,REMARK,
					(SELECT TYPE FROM l_book_issue where ID=BOOK_ISSUE_ID) as user_type,
					(SELECT PROFILE_ID FROM l_book_issue where ID = BOOK_ISSUE_ID) as profileData_id,
					(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID = profileData_id) as profileName,
					(SELECT DEPT_ID FROM employee_profile where PROFILE_ID = profileData_id) as deptId,
					(SELECT NAME FROM department where ID = deptId) as DEPT_NAME
					(SELECT DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME FROM l_book_issue where ID = BOOK_ISSUE_ID) as issue_date,
					(SELECT DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME FROM l_book_issue where ID = BOOK_ISSUE_ID) as due_date,
					(SELECT BOOK_ID FROM l_book_issue where ID = BOOK_ISSUE_ID) as BOOK_ID,
					(SELECT NAME FROM l_book where CODE = BOOK_ID)as BOOK_NAME,
					(SELECT IMAGE FROM l_book where CODE = BOOK_ID)as BOOK_IMAGE,
					(SELECT AUTHOR FROM l_book where CODE = BOOK_ID)as AUTHOR
					FROM l_book_return WHERE ID='$id'";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
				}
			}

			$sql="SELECT ID,BOOK_ISSUE_ID,DATE_FORMAT(RETURN_DATE, '%d-%b-%Y') as RETURN_DATE,REMARK,

			(SELECT TYPE FROM l_book_issue where ID=BOOK_ISSUE_ID) as user_type,
			(SELECT PROFILE_ID FROM l_book_issue where ID=BOOK_ISSUE_ID) as profile_id,
			
			-- (SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=profile_id) as profileName,
			-- (SELECT DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME FROM l_book_issue where ID=BOOK_ISSUE_ID) as issue_date,
			-- (SELECT DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME FROM l_book_issue where ID=BOOK_ISSUE_ID) as due_date,
			-- (SELECT BOOK_ID FROM l_book_issue where ID=BOOK_ISSUE_ID) as BOOK_ID,
			-- (SELECT NAME FROM l_book where CODE=BOOK_ID)as BOOK_NAME


			-- (SELECT NAME FROM l_book where CODE = BOOK_ID) as BOOK_NAME, 
			-- (SELECT AUTHOR FROM l_book where CODE = BOOK_ID) as AUTHOR, 
			-- (SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as profileName,
			-- (SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID=l_book_issue.PROFILE_ID) as batchId,
			-- (SELECT NAME FROM course_batch where ID = batchId) as BATCH_NAME,
			-- (SELECT COURSE_ID FROM course_batch where ID = batchId) as courseId,
			-- (SELECT NAME FROM course where ID = courseId) as COURSE_NAME 
			FROM l_book_return WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function getIssuedBookReportAll_details(){
			$sql="SELECT ID,PROFILE_ID,BOOK_ID,DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME,DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as profileName,(SELECT NAME FROM l_book where CODE=BOOK_ID)as BOOK_NAME FROM l_book_issue WHERE BOOK_RETURN = 'NO'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function getIssuedBookReport_details($fromDate,$toDate){
			$sql="SELECT ID,PROFILE_ID,BOOK_ID,DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME,DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as profileName,(SELECT NAME FROM l_book where CODE=BOOK_ID)as BOOK_NAME FROM l_book_issue WHERE BOOK_RETURN = 'NO' AND ISSUED_DATETIME BETWEEN '$fromDate' AND '$toDate'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function getStuReportAutocompleteData(){
			$sql="SELECT PROFILE_ID FROM l_book_issue WHERE TYPE = 'Student'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
			foreach($result as $key => $value){
				$pId=$value['PROFILE_ID'];
				$sql="SELECT ID,ADMISSION_NO,CONCAT(FIRSTNAME,' ',LASTNAME) as NAME FROM profile where ID='$pId'";
				$proDetail = $this->db->query($sql, $return_object = TRUE)->result_array();

				if($proDetail){
					$data[$key]['profile_id']=$proDetail[0]['ID'];
					$data[$key]['adm_no']=$proDetail[0]['ADMISSION_NO'];
					$data[$key]['name']=$proDetail[0]['NAME'];
				}
			}
			return $data;
		}

		public function getStudentIssuedBookReport_details($id){
			$sql="SELECT ID,PROFILE_ID,BOOK_ID,DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME,DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as profileName,(SELECT NAME FROM l_book where CODE=BOOK_ID)as BOOK_NAME FROM l_book_issue WHERE PROFILE_ID = '$id' AND BOOK_RETURN = 'NO'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function getStudentReturnedBookReport_details($id){
			$sql="SELECT ID,PROFILE_ID,BOOK_ID,DATE_FORMAT(ISSUED_DATETIME, '%d-%b-%Y') as ISSUED_DATETIME,DATE_FORMAT(DUE_DATETIME, '%d-%b-%Y') as DUE_DATETIME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as profileName,(SELECT NAME FROM l_book where CODE=BOOK_ID)as BOOK_NAME FROM l_book_issue WHERE PROFILE_ID = '$id' AND BOOK_RETURN = 'YES'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function getAutoGenBookCode(){
			$sql = "SELECT MAX(ID) as AUTO_CODE FROM l_book";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function getEmpReportAutocompleteData(){
			$sql="SELECT PROFILE_ID FROM l_book_issue WHERE TYPE = 'Employee'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
			foreach($result as $key => $value){
				$pId=$value['PROFILE_ID'];
				$sql="SELECT ID,PROFILE_ID,ADMISSION_NO,EMPLOYEE_NAME FROM employee_profile_view where PROFILE_ID='$pId'";
				$proDetail = $this->db->query($sql, $return_object = TRUE)->result_array();

				if($proDetail){
					$data[$key]['profile_id']=$proDetail[0]['PROFILE_ID'];
					$data[$key]['adm_no']=$proDetail[0]['ADMISSION_NO'];
					$data[$key]['name']=$proDetail[0]['EMPLOYEE_NAME'];
				}
			}
			return $data;
		}
	}
?>
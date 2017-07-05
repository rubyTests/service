<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class dashboardmodel extends CI_Model {
		
		public function adminDashboard(){
			// Student
			$sql="SELECT count(*) as stuCount FROM student_profile";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$data['studentCount']= $result;
			}
			// Employee
			$sql="SELECT count(*) as empCount FROM employee_profile";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$data['employeeCount']= $result;
			}
			// Course
			$sql="SELECT count(*) as courseCount FROM course";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$data['courseCount']= $result;
			}
			// Admission 
			$sql="SELECT count(*) as admission FROM student_profile";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$data['admissionCount']= $result;
			}
			
			return $data;
		}
		
		public function addTodoAdmin($values){
			$data = array(
				'TITLE' => $values['title'],
				'DESCRIPTION' => $values['description'],
				'DATE' => $values['date'],
				'CLOSED' => 'false',
				'IMPORTANT' => $values['important'],
			);
			$this->db->insert('todolist_admin', $data);
			return array('status'=>true, 'message'=>"Record Inserted Successfully");
		}
		
		public function getTodoAdmin(){
			$sql="SELECT ID,TITLE,DESCRIPTION,DATE,CLOSED,IMPORTANT FROM todolist_admin";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function deleteTodoAdmin($id){
			$sql="DELETE FROM todolist_admin where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function studentDashboard($pId){
			// Student
			$sql="SELECT COURSEBATCH_ID,(SELECT COURSE_ID FROM course_batch WHERE ID=COURSEBATCH_ID)as COURSE_ID FROM student_profile WHERE PROFILE_ID='$pId'";
			$res = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($res){
				$courseId=$res[0]['COURSE_ID'];
				$batchId=$res[0]['COURSEBATCH_ID'];
				$sql="SELECT ID,PROFILE_ID,(SELECT NAME FROM course WHERE ID=student_leave.COURSE_ID) as COURSE_NAME,(SELECT NAME FROM course_batch WHERE ID=student_leave.COURSEBATCH_ID) as BATCH_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=student_leave.PROFILE_ID) as ADMISSION_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ROUND((((SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ) - (SELECT count(*) FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId'))/(SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ))*100,2) as total FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' GROUP BY PROFILE_ID)as Percentage FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' Group by PROFILE_ID";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					$data['attendance']= $result;
				}
			}
			
			// $sql="SELECT (SELECT ROUND((((SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ) - (SELECT count(*) FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId'))/(SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ))*100,2) as total FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' GROUP BY PROFILE_ID)as Percentage FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' Group by PROFILE_ID";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// if($result){
				// $data['attendance']= $result;
			// }
			
			// // Employee
			// $sql="SELECT count(*) as empCount FROM employee_profile";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// if($result){
				// $data['employeeCount']= $result;
			// }
			// // Course
			// $sql="SELECT count(*) as courseCount FROM course";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// if($result){
				// $data['courseCount']= $result;
			// }
			// // Admission 
			// $sql="SELECT count(*) as admission FROM student_profile";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// if($result){
				// $data['admissionCount']= $result;
			// }
			
			return $data;
		}
		
		public function addTodoStudent($values){
			$data = array(
				'TITLE' => $values['title'],
				'DESCRIPTION' => $values['description'],
				'DATE' => $values['date'],
				'CLOSED' => 'false',
				'IMPORTANT' => $values['important'],
			);
			$this->db->insert('todolist_student', $data);
			return array('status'=>true, 'message'=>"Record Inserted Successfully");
		}
		
		public function getTodoStudent(){
			$sql="SELECT ID,TITLE,DESCRIPTION,DATE,CLOSED,IMPORTANT FROM todolist_student";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function deleteTodoStudent($id){
			$sql="DELETE FROM todolist_student where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function getStuAssignmentShow($proId){
			$sql="SELECT COURSEBATCH_ID FROM student_profile WHERE PROFILE_ID='$proId'";
			$res = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($res){
				$batchId=$res[0]['COURSEBATCH_ID'];
				$sql="SELECT ID as assignID,NAME,SUBJECT_ID,DUE_DATE,(SELECT NAME FROM subject WHERE ID=SUBJECT_ID)as SUBJECT_NAME,CASE WHEN (SELECT STATUS FROM assignment_status WHERE ASSIGNMENT_ID=assignID AND PROFILE_ID='$proId')='Completed' THEN 'Completed' ELSE 'Pending' END as STATUS FROM assignment WHERE BATCH_ID='$batchId'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				return $result;
			}
		}
		
	}
?>
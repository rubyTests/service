<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class timetable extends CI_Model {

		public function saveTimetableSettings($value){
			$data = array(
			   'START_DAY' => $value['start_day'],
			   'END_DAY' => $value['end_day'],
			   'START_TIME' => $value['startTime'],
			   'END_TIME' => $value['endTime']
			);
			$this->db->insert('TIMETABLE_SETTING', $data); 
			$setting_id= $this->db->insert_id();
			if(!empty($setting_id)){
				// return true;
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'SETTING_ID'=>$setting_id);
			}
	    }
		
		public function updateTimetableSettings($id,$value){
			$data = array(
			   	'START_DAY' => $value['start_day'],
			   	'END_DAY' => $value['end_day'],
			   	'START_TIME' => $value['startTime'],
			   	'END_TIME' => $value['endTime']
			);
			$this->db->where('ID', $id);
			$this->db->update('TIMETABLE_SETTING', $data);
			return array('status'=>true, 'message'=>"Record Updated Successfully",'SETTING_ID'=>$id);
		}
		
		public function fetchSettingDetails(){
			$sql="SELECT * FROM TIMETABLE_SETTING";  
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		function fetchCourseList(){
			$sql="SELECT * FROM COURSE";
			return $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchBatchList($courseid){
			$sql="SELECT * FROM COURSE_BATCH WHERE COURSE_ID='$courseid'";
			return $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchSubjectList($courseid){
			$sql="SELECT SUBJECT_ID,(SELECT NAME FROM SUBJECT WHERE ID=SUBJECT_ID) AS SUBJECT_NAME FROM COURSE_SUBJECT WHERE COURSE_ID='$courseid'";
			return $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchEmployeeList($subjectid){
			$sql="SELECT EMP_PROFILE_ID,(SELECT PROFILE_ID FROM EMPLOYEE_PROFILE WHERE ID=EMP_PROFILE_ID) AS PROF_ID,
			(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROF_ID) AS EMPLOYEE_NAME FROM EMPLOYEE_SUBJECT WHERE SUBJECT_ID='$subjectid'";
			return $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function saveCalendarDetails($value){
			// print_r($value);exit();
			$data = array(
			   'COURSE_ID' => $value['course_id'],
			   'BATCH_ID' => $value['batch_id'],
			   'SUBJECT_ID' => $value['subject_id'],
			   'PROFILE_ID' => $value['employee_id'],
			   'DAY' => $value['start_day'],
			   'START_TIME' => $value['startTime'],
			   'END_TIME' => $value['endTime']
			);
			// print_r($data);exit;
			$this->db->insert('TIMETABLE', $data); 
			$table_id= $this->db->insert_id();
			if(!empty($table_id)){
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'TIMETABLE_ID'=>$table_id);
			}
		}

		function checkTimefromSettings($starttime){
			$sql="SELECT SUBJECT_ID,(SELECT NAME FROM SUBJECT WHERE ID=SUBJECT_ID) AS SUBJECT_NAME FROM COURSE_SUBJECT WHERE COURSE_ID='$courseid'";
			return $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function checkEmployeeTimetable($employee_id,$setday,$starttime,$endtime){
			$sql="SELECT * FROM TIMETABLE WHERE PROFILE_ID='$employee_id' AND DAY='$setday' AND START_TIME >='$starttime' AND END_TIME <='$endtime'";
			// SELECT * FROM TIMETABLE WHERE PROFILE_ID='1' AND DAY='Monday' AND START_TIME >= '09:30:00' AND END_TIME <= '13:30:00' 
			return $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getTimetableAllocationDetails($batch){
			$sql="SELECT ID,SUBJECT_ID,PROFILE_ID,START_TIME,END_TIME,DAY,COURSE_ID,BATCH_ID,(SELECT NAME FROM SUBJECT WHERE ID=SUBJECT_ID) AS SUBJECT_NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS STAFF_NAME FROM TIMETABLE WHERE BATCH_ID='$batch'";
			// $sql="SELECT DAY as day,START_TIME as start,END_TIME as end,(SELECT NAME FROM SUBJECT WHERE ID=TIMETABLE.SUBJECT_ID) AS title FROM TIMETABLE WHERE BATCH_ID='$batch'";
			return $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getTimetableDetailbasedonEmployee($emp_id){
			$sql="SELECT ID,SUBJECT_ID,PROFILE_ID,START_TIME,END_TIME,DAY,(SELECT NAME FROM SUBJECT WHERE ID=SUBJECT_ID) AS SUBJECT_NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS STAFF_NAME FROM TIMETABLE WHERE PROFILE_ID='$emp_id'";
			return $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getAllTimetableDetail(){
			$sql="SELECT ID,COURSE_ID,BATCH_ID,(SELECT NAME FROM COURSE WHERE ID=COURSE_ID) AS COURSE_NAME,(SELECT NAME FROM COURSE_BATCH WHERE ID=BATCH_ID) AS BATCH_NAME FROM TIMETABLE GROUP BY COURSE_ID,BATCH_ID";
			return $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function deleteTimetableDetails($course_id,$batch_id){
			$sql="SELECT * FROM TIMETABLE where COURSE_ID='$course_id' AND BATCH_ID='$batch_id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit();
			foreach ($result as $value) {
				$tableID=$value['ID'];
				$sql="DELETE FROM TIMETABLE WHERE ID='$tableID'";
				$result = $this->db->query($sql);
			}
			return $this->db->affected_rows();
		}
		function updateCalendarDetails($id,$value){
			print_r($value);exit();
			$data = array(
			   'SUBJECT_ID' => $value['subject_id'],
			   'PROFILE_ID' => $value['employee_id'],
			   'DAY' => $value['start_day']
			);
			// print_r($data);exit;
			$this->db->where('ID', $id);
			$this->db->update('TIMETABLE', $data);
			return array('status'=>true, 'message'=>"Record Inserted Successfully",'TIMETABLE_ID'=>$table_id);
		}

		function saveDetails($value){
			// print_r($value);exit;
			$data = array(
			   'NAME' => $value['user'],
			   'MAIL_ID' => $value['email'],
			   'SUBJECT' => $value['subject'],
			   'STATUS' => 'N'
			);
			$this->db->insert('email_log', $data);
			return array('status'=>true, 'message'=>"Record Inserted Successfully");
		}
	}
?>
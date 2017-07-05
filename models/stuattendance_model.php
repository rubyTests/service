<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class stuattendance_model extends CI_Model {

		// Store Category Details

		public function getStuAttendanceData($courseId,$batchId,$type,$date){
			if($type=='studentList'){
				$sql="SELECT ID,PROFILE_ID,PROFILE_ID as proId,ROLL_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID)as ADMISSION_NO FROM student_profile WHERE COURSEBATCH_ID='$batchId'";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}else{
				$sql="SELECT ID,PROFILE_ID,PROFILE_ID as proId,ROLL_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID)as ADMISSION_NO, CASE 
				WHEN (SELECT PROFILE_ID FROM student_leave WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND FROMDATE='$date' AND student_leave.PROFILE_ID=proId) THEN 'true' ELSE 'false' END as row_select
				FROM student_profile WHERE COURSEBATCH_ID='$batchId'";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}
	    }
		
		public function addStuAttendanceDetails($val){
			$courseId=$val['courseId'];
			$batchId=$val['batchId'];
			$subjectId=$val['subjectId'];
			$date=$val['date'];
			if($val['presentStatus']=='AllPresent'){
				$sql="SELECT PROFILE_ID FROM student_profile WHERE COURSEBATCH_ID='$batchId' AND PROFILE_ID NOT IN (SELECT PROFILE_ID FROM student_leave WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND FROMDATE='$date')";
				$result= $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					foreach($result as $value){
						$data = array(
							'COURSE_ID' =>$val['courseId'],
							'COURSEBATCH_ID' =>$val['batchId'],
							'SUBJECT_ID' =>$val['subjectId'],
							'DATE' =>$val['date'],
							'ATTENDANCE_TYPE' =>$val['type'],
							'CRT_USER_ID' =>$val['userId'],
							'PROFILE_ID' =>$value['PROFILE_ID'],
						);
						$this->db->insert('attendance', $data);
					}
				}
			}else{
				for ($i=0; $i <count($val['studentList']) ; $i++) {
					$data = array(
						'COURSE_ID' =>$val['courseId'],
						'COURSEBATCH_ID' =>$val['batchId'],
						'SUBJECT_ID' =>$val['subjectId'],
						'FROMDATE' =>$val['date'],
						'ATTENDANCE_TYPE' =>$val['type'],
						'CRT_USER_ID' =>$val['userId'],
						'PROFILE_ID' =>$val['studentList'][$i]['PROFILE_ID'],
						'REASON' =>$val['studentList'][$i]['remark'],
						'LEAVEDURATION' =>$val['studentList'][$i]['duration'],
					);
					$this->db->insert('student_leave', $data);
				}
				
				$sql="SELECT PROFILE_ID FROM student_profile WHERE COURSEBATCH_ID='$batchId' AND PROFILE_ID NOT IN (SELECT PROFILE_ID FROM student_leave WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND FROMDATE='$date')";
				$result= $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					foreach($result as $value){
						$data = array(
							'COURSE_ID' =>$val['courseId'],
							'COURSEBATCH_ID' =>$val['batchId'],
							'SUBJECT_ID' =>$val['subjectId'],
							'DATE' =>$val['date'],
							'ATTENDANCE_TYPE' =>$val['type'],
							'CRT_USER_ID' =>$val['userId'],
							'PROFILE_ID' =>$value['PROFILE_ID'],
						);
						$this->db->insert('attendance', $data);
					}
				}
			}
	    	return array('status'=>true, 'message'=>"Record Inserted Successfully");
		}

	    public function getAllStuAttendanceReport($courseId,$batchId,$type,$subjectId){
			if($type=='Daily'){
				$sql="SELECT ID,PROFILE_ID,PROFILE_ID as ProId,(SELECT NAME FROM course WHERE ID=student_leave.COURSE_ID) as COURSE_NAME,(SELECT NAME FROM course_batch WHERE ID=student_leave.COURSEBATCH_ID) as BATCH_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=student_leave.PROFILE_ID) as ADMISSION_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,CASE WHEN (SELECT count(*) FROM student_leave WHERE PROFILE_ID=ProId AND ATTENDANCE_TYPE='Daily') THEN (SELECT count(*) FROM student_leave WHERE PROFILE_ID=ProId AND ATTENDANCE_TYPE='Daily') ELSE 0 END as TOTAL_LEAVE FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Daily' Group by PROFILE_ID";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}else{
				$sql="SELECT ID,PROFILE_ID,PROFILE_ID as ProId,(SELECT NAME FROM course WHERE ID=student_leave.COURSE_ID) as COURSE_NAME,(SELECT NAME FROM course_batch WHERE ID=student_leave.COURSEBATCH_ID) as BATCH_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=student_leave.PROFILE_ID) as ADMISSION_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,CASE WHEN (SELECT count(*) FROM student_leave WHERE PROFILE_ID=ProId AND ATTENDANCE_TYPE='Subject-Wise' AND SUBJECT_ID='$subjectId') THEN (SELECT count(*) FROM student_leave WHERE PROFILE_ID=ProId AND ATTENDANCE_TYPE='Subject-Wise' AND SUBJECT_ID='$subjectId' ) ELSE 0 END as TOTAL_LEAVE FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Subject-Wise' AND SUBJECT_ID='$subjectId' Group by PROFILE_ID";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}
		}
		
		public function getStuAttendanceReport($courseId,$batchId,$type,$subjectId,$pId,$method){
			if($method=='approveLeave'){
				$sql="SELECT ID,PROFILE_ID,(SELECT NAME FROM course WHERE ID=student_leave.COURSE_ID) as COURSE_NAME,(SELECT NAME FROM course_batch WHERE ID=student_leave.COURSEBATCH_ID) as BATCH_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=student_leave.PROFILE_ID) as ADMISSION_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ROUND((((SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ) - (SELECT count(*) FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId'))/(SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ))*100,2) as total FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' GROUP BY PROFILE_ID)as Percentage FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' Group by PROFILE_ID";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					foreach($result as $key => $value){
						$sql1="SELECT ID,REASON,FROMDATE,TODATE,COALESCE(DATEDIFF(TODATE,FROMDATE),1)as TOTAL_LEAVE,STATUS as approval FROM student_leave WHERE PROFILE_ID='$pId' AND STATUS IS NOT NULL AND FROMDATE < NOW() ";
						$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
						$result[$key]['leaveDetails']=$result1;
					}
					return $result;
				}
			}else{
				if($type=='Daily'){
					$sql="SELECT ID,PROFILE_ID,(SELECT NAME FROM course WHERE ID=student_leave.COURSE_ID) as COURSE_NAME,(SELECT NAME FROM course_batch WHERE ID=student_leave.COURSEBATCH_ID) as BATCH_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=student_leave.PROFILE_ID) as ADMISSION_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ROUND((((SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ) - (SELECT count(*) FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId'))/(SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ))*100,2) as total FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' GROUP BY PROFILE_ID)as Percentage FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' Group by PROFILE_ID";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					if($result){
						foreach($result as $key => $value){
							$sql1="SELECT REASON,FROMDATE,TODATE,COALESCE(DATEDIFF(TODATE,FROMDATE),1)as TOTAL_LEAVE FROM student_leave WHERE PROFILE_ID='$pId'";
							$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
							$result[$key]['leaveDetails']=$result1;
						}
						return $result;
					}
				}else{
					$sql="SELECT ID,PROFILE_ID,(SELECT NAME FROM course WHERE ID=student_leave.COURSE_ID) as COURSE_NAME,(SELECT NAME FROM course_batch WHERE ID=student_leave.COURSEBATCH_ID) as BATCH_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=student_leave.PROFILE_ID) as ADMISSION_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT NAME FROM subject WHERE ID=student_leave.SUBJECT_ID) as SUBJECT_NAME,(SELECT ROUND((((SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ) - (SELECT count(*) FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Subject-Wise' AND PROFILE_ID='$pId'))/(SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ))*100,2) as total FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Subject-Wise' AND PROFILE_ID='$pId' GROUP BY PROFILE_ID)as Percentage FROM student_leave WHERE COURSE_ID=$courseId AND COURSEBATCH_ID=$batchId AND ATTENDANCE_TYPE='Subject-Wise' AND PROFILE_ID='$pId' AND SUBJECT_ID='$subjectId' Group by PROFILE_ID";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					if($result){
						foreach($result as $key => $value){
							$sql1="SELECT REASON,FROMDATE,TODATE,COALESCE(DATEDIFF(TODATE,FROMDATE),1)as TOTAL_LEAVE FROM student_leave WHERE PROFILE_ID='$pId' AND SUBJECT_ID='$subjectId'";
							$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
							$result[$key]['leaveDetails']=$result1;
						}
						return $result;
					}
				}
			}
		}
		
		public function getStuProfileAttendanceReport($pId){
			//$sql="SELECT (SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID='$pId') as PROFILE_NAME,(SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID='$pId') as BATCH_ID,(SELECT NAME FROM course_batch WHERE ID=BATCH_ID) as BATCH_NAME,(SELECT COURSE_ID FROM course_batch WHERE ID=BATCH_ID) as COURSE_ID,(SELECT NAME FROM course WHERE ID=COURSE_ID)as COURSE_NAME, FROM student_profile WHERE PROFILE_ID='$pId'";
			$sql="SELECT ID,PROFILE_ID,(SELECT NAME FROM course WHERE ID=student_leave.COURSE_ID) as COURSE_NAME,(SELECT NAME FROM course_batch WHERE ID=student_leave.COURSEBATCH_ID) as BATCH_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=student_leave.PROFILE_ID) as ADMISSION_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ROUND((((SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ) - (SELECT count(*) FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId'))/(SELECT DATEDIFF((NOW()),DATE_FORMAT(min(CRT_DT), '%Y-%m-%d')) FROM attendance ))*100,2) as total FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' GROUP BY PROFILE_ID)as Percentage FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' Group by PROFILE_ID";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key => $value){
					$sql1="SELECT REASON,FROMDATE,TODATE,COALESCE(DATEDIFF(TODATE,FROMDATE),1)as TOTAL_LEAVE FROM student_leave WHERE PROFILE_ID='$pId'";
					$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
					$result[$key]['leaveDetails']=$result1;
				}
				return $result;
			}
		}
		
		public function getStuApplyLeave($pId){
			$sql1="SELECT REASON,FROMDATE,TODATE,STATUS,COALESCE(DATEDIFF(TODATE,FROMDATE),1)as TOTAL_LEAVE FROM student_leave WHERE PROFILE_ID='$pId' AND STATUS IS NOT NULL";
			return $result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
		}
		
		public function addStuApplyLeave($values){
			$proId=$values['profileId'];
			$duration=$values['duration'];
			$sql="SELECT COURSEBATCH_ID,(SELECT COURSE_ID FROM course_batch WHERE ID=COURSEBATCH_ID)as COURSE_ID FROM student_profile WHERE PROFILE_ID='$proId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$courseId=$result[0]['COURSE_ID'];
				$batchId=$result[0]['COURSEBATCH_ID'];
				if($duration=='Multiple Days'){
					$data = array(
						'COURSE_ID' =>$courseId,
						'COURSEBATCH_ID' =>$batchId,
						'FROMDATE' =>$values['startDate'],
						'TODATE' =>$values['endDate'],
						'ATTENDANCE_TYPE' =>'Daily',
						'CRT_USER_ID' =>$values['userId'],
						'PROFILE_ID' =>$proId,
						'REASON' =>$values['reason'],
						'LEAVEDURATION' =>$values['duration'],
						'STATUS' => 'Pending'
					);
				}else{
					$data = array(
						'COURSE_ID' =>$courseId,
						'COURSEBATCH_ID' =>$batchId,
						'FROMDATE' =>$values['startDate'],
						'ATTENDANCE_TYPE' =>'Daily',
						'CRT_USER_ID' =>$values['userId'],
						'PROFILE_ID' =>$proId,
						'REASON' =>$values['reason'],
						'LEAVEDURATION' =>$values['duration'],
						'STATUS' => 'Pending'
					);
				}
				$this->db->insert('student_leave', $data);
				return array('status'=>true, 'message'=>"Record Inserted Successfully");
			}
		}
		
		public function getAllStuApproveLeave($courseId,$batchId){
			$sql="SELECT PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,REASON,FROMDATE,TODATE,STATUS,COALESCE(DATEDIFF(TODATE,FROMDATE),1)as TOTAL_LEAVE FROM student_leave WHERE COURSEBATCH_ID='$batchId' AND COURSE_ID='$courseId' AND STATUS ='Pending' GROUP BY PROFILE_ID";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function updateStuLeaveApprove($value){
			if($value['approval']==true){
				$data = array(
					'STATUS' => 'Approved'
				);
				$this->db->where('ID', $value['ID']);
				$this->db->update('student_leave', $data);
			}else{
				$data = array(
					'STATUS' => 'Rejected'
				);
				$this->db->where('ID', $value['ID']);
				$this->db->update('student_leave', $data);
			}
			return array('status'=>true, 'message'=>"Status Updated Successfully");
		}
		
	}
?>
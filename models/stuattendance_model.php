<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class stuattendance_model extends CI_Model {

		// Store Category Details

		public function getStuAttendanceData($courseId,$batchId,$type,$date){
			if($type=='studentList'){
				$sql="SELECT ID,PROFILE_ID,PROFILE_ID as proId,ROLL_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID)as ADMISSION_NO FROM student_profile WHERE COURSEBATCH_ID='$batchId'";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}else{
				// $sql="SELECT ID,PROFILE_ID,PROFILE_ID as proId,ROLL_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID)as ADMISSION_NO, CASE 
				// WHEN (SELECT PROFILE_ID FROM student_leave WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND FROMDATE='$date' AND student_leave.PROFILE_ID=proId) THEN 'true' ELSE 'false' END as row_select
				// FROM student_profile WHERE COURSEBATCH_ID='$batchId'";


				// $sql="SELECT ID,PROFILE_ID,PROFILE_ID as proId,ROLL_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID)as ADMISSION_NO, CASE 
				// WHEN (SELECT PROFILE_ID FROM student_leave WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND FROMDATE='$date' AND student_leave.PROFILE_ID=proId) THEN 'Y' ELSE 'N' END as row_select,
				// CASE WHEN (SELECT STATUS FROM attendance WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND DATE
				// ='$date' AND attendance.PROFILE_ID=proId)='Y' THEN (SELECT REASON FROM student_leave WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND FROMDATE='$date' AND student_leave.PROFILE_ID=proId) END as remark,
				// CASE WHEN (SELECT STATUS FROM attendance WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND DATE
				// ='$date' AND attendance.PROFILE_ID=proId)='Y' THEN (SELECT LEAVEDURATION FROM student_leave WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND FROMDATE='$date' AND student_leave.PROFILE_ID=proId) END as duration
				// FROM student_profile WHERE COURSEBATCH_ID='$batchId'";

				$sql="SELECT ID,PROFILE_ID,PROFILE_ID as proId,ROLL_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID)as ADMISSION_NO, CASE 
				WHEN (SELECT STATUS FROM student_attendance WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND DATE='$date' AND student_attendance.PROFILE_ID=proId)='Y' THEN 'Y' ELSE 'N' END as row_select,
				(SELECT ID FROM student_attendance WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND DATE='$date' AND student_attendance.PROFILE_ID=proId) as attendance_id,
				CASE WHEN (SELECT STATUS FROM student_attendance WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND DATE='$date' AND student_attendance.PROFILE_ID=proId)='Y' THEN (SELECT REASON FROM student_attendance WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND DATE='$date' AND student_attendance.PROFILE_ID=proId) END as remark,CASE WHEN (SELECT STATUS FROM student_attendance WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND DATE='$date' AND student_attendance.PROFILE_ID=proId)='Y' THEN (SELECT 	DURATION FROM student_attendance WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' AND DATE='$date' AND student_attendance.PROFILE_ID=proId) END as duration
				FROM student_profile WHERE COURSEBATCH_ID='$batchId'";

				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}
	    }
		
		public function addStuAttendanceDetails($val){
			//print_r($val);exit;
			for ($i=0; $i < count($val['details']); $i++) {
				$courseid = $val['courseId'];
				$batchId = $val['batchId'];
				$subjectId = $val['subjectId'];
				$date = $val['date'];
				$profileid = $val['details'][$i]['PROFILE_ID'];

				if($val['details'][$i]['row_select']=='N'){
					$sql="SELECT * FROM student_leave WHERE COURSE_ID='$courseid' AND COURSEBATCH_ID='$batchId' AND DATE='$date' AND PROFILE_ID='$profileid'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					if($result){
						$sql="DELETE FROM student_leave WHERE COURSE_ID='$courseid' AND COURSEBATCH_ID='$batchId' AND DATE='$date' AND PROFILE_ID='$profileid'";
						$result = $this->db->query($sql);
					}
					$remark='';
					$duration='';
				}else{
					$remark=$val['details'][$i]['remark'];
					//$duration=$val['details'][$i]['duration'];
					if(isset($val['details'][$i]['duration'])){
						$duration=$val['details'][$i]['duration'];
					}else {
						$duration='';
					}
					$sql2="SELECT * FROM student_leave WHERE COURSE_ID='$courseid' AND COURSEBATCH_ID='$batchId' AND DATE='$date' AND PROFILE_ID='$profileid'";
					$result2 = $this->db->query($sql2, $return_object = TRUE)->result_array();
					if(isset($result2[0]['ID'])){
						$leavedata = array(
							'COURSE_ID' =>$val['courseId'],
							'COURSEBATCH_ID' =>$val['batchId'],
							'SUBJECT_ID' =>$val['subjectId'],
							'DATE' =>$val['date'],
							'CRT_USER_ID' =>$val['userId'],
							'PROFILE_ID' =>$val['details'][$i]['PROFILE_ID'],
							'REASON' =>$remark,
							'DURATION' =>$duration
						);
						$this->db->where('ID', $result2[0]['ID']);
						$this->db->update('student_leave', $leavedata);
					}else {
						$leavedata = array(
							'COURSE_ID' =>$val['courseId'],
							'COURSEBATCH_ID' =>$val['batchId'],
							'SUBJECT_ID' =>$val['subjectId'],
							'DATE' =>$val['date'],
							'CRT_USER_ID' =>$val['userId'],
							'PROFILE_ID' =>$val['details'][$i]['PROFILE_ID'],
							'REASON' =>$remark,
							'DURATION' =>$duration
						);
						$this->db->insert('student_leave', $leavedata); 
					}
				}

				$data = array(
					'COURSE_ID' =>$val['courseId'],
					'COURSEBATCH_ID' =>$val['batchId'],
					'SUBJECT_ID' =>$val['subjectId'],
					'DATE' =>$val['date'],
					'CRT_USER_ID' =>$val['userId'],
					'PROFILE_ID' =>$val['details'][$i]['PROFILE_ID'],
					'STATUS' =>$val['details'][$i]['row_select'],
					'REASON' =>$remark,
					'DURATION' =>$duration
				);
				if($val['details'][$i]['attendance_id']){
					$this->db->where('ID', $val['details'][$i]['attendance_id']);
					$this->db->update('student_attendance', $data);
				}else{
					$this->db->insert('student_attendance', $data);

					$sql1="SELECT * FROM attendance_report WHERE  COURSEBATCH_ID='$batchId' AND PROFILE_ID='$profileid'";
					$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
					// print_r($result1);
					if(isset($result1[0]['TOTAL_DAYS'])){
						$Days=$result1[0]['TOTAL_DAYS']+1;
						$dayDetails = array(
							'TOTAL_DAYS' =>$Days,
							'COURSEBATCH_ID' =>$val['batchId'],
							'PROFILE_ID' =>$val['details'][$i]['PROFILE_ID']
						);
						$this->db->where('ID', $result1[0]['ID']);
						$this->db->update('attendance_report', $dayDetails);
					}else{
						$Days=1;
						$dayDetails = array(
							'TOTAL_DAYS' =>$Days,
							'COURSEBATCH_ID' =>$val['batchId'],
							'PROFILE_ID' =>$val['details'][$i]['PROFILE_ID']
						);
						$this->db->insert('attendance_report', $dayDetails);
					}
				}
			}
			// exit;
			return array('status'=>true, 'message'=>"Record Inserted Successfully");
		}

	    public function getAllStuAttendanceReport($courseId,$batchId,$type){
	    	$sql="SELECT ID,DATE,PROFILE_ID,STATUS,COURSE_ID,(SELECT ATTENDANCE_TYPE FROM COURSE WHERE ID=COURSE_ID) as COURSE_TYPE,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS ADMISSION_NO, (SELECT IMAGE1 FROM PROFILE WHERE ID=PROFILE_ID) AS IMAGE,
	    		(SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID=student_attendance.COURSEBATCH_ID) AS TOTAL_DAYS,
	    		CASE WHEN (SELECT COUNT(ID) FROM student_leave WHERE PROFILE_ID=student_attendance.PROFILE_ID) > 0 THEN 
				ROUND(((((SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID
				=student_attendance.COURSEBATCH_ID)-(SELECT COUNT(ID) FROM student_leave WHERE PROFILE_ID=student_attendance.PROFILE_ID))*100)/(SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID
				=student_attendance.COURSEBATCH_ID)),-1)
				  ELSE '100' END AS PERCENTAGE
	    	FROM student_attendance WHERE COURSE_ID='$courseId' AND COURSEBATCH_ID='$batchId' GROUP BY PROFILE_ID";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();

		}
		
		function getStudentBasicandPercentage($profileid,$subjectId){
			// $sql="SELECT STUDENT_ATTENDANCE.PROFILE_ID,CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.FIRSTNAME) AS STUDENT_NAME,COURSE_BATCH.NAME AS BATCH_NAME,COURSE.NAME AS COURSE_NAME,PROFILE.ADMISSION_NO FROM STUDENT_ATTENDANCE 
			// 	INNER JOIN COURSE ON STUDENT_ATTENDANCE.COURSE_ID=COURSE.ID
			// 	INNER JOIN COURSE_BATCH ON STUDENT_ATTENDANCE.COURSEBATCH_ID=COURSE_BATCH.ID
			// 	INNER JOIN PROFILE ON STUDENT_ATTENDANCE.PROFILE_ID=PROFILE.ID
			// 	WHERE STUDENT_ATTENDANCE.PROFILE_ID='$profileid'";
			if($subjectId){
				$sql="SELECT ID,DATE,PROFILE_ID,STATUS,COURSE_ID,(SELECT NAME FROM COURSE_BATCH WHERE ID=COURSEBATCH_ID) as BATCH_NAME,(SELECT NAME FROM COURSE WHERE ID=COURSE_ID) as COURSE_NAME,(SELECT ATTENDANCE_TYPE FROM COURSE WHERE ID=COURSE_ID) as COURSE_TYPE,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS ADMISSION_NO, (SELECT IMAGE1 FROM PROFILE WHERE ID=PROFILE_ID) AS IMAGE,
	    		(SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID=student_attendance.COURSEBATCH_ID) AS TOTAL_DAYS,
	    		CASE WHEN (SELECT COUNT(ID) FROM student_leave WHERE PROFILE_ID=student_attendance.PROFILE_ID) > 0 THEN 
				ROUND(((((SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID
				=student_attendance.COURSEBATCH_ID)-(SELECT COUNT(ID) FROM student_leave WHERE PROFILE_ID=student_attendance.PROFILE_ID))*100)/(SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID
				=student_attendance.COURSEBATCH_ID)),-1)
				  ELSE '0' END AS PERCENTAGE
		    	FROM student_attendance WHERE PROFILE_ID='$profileid' AND SUBJECT_ID='$subjectId' GROUP BY PROFILE_ID";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}else {
				$sql="SELECT ID,DATE,PROFILE_ID,STATUS,COURSE_ID,(SELECT NAME FROM COURSE_BATCH WHERE ID=COURSEBATCH_ID) as BATCH_NAME,(SELECT NAME FROM COURSE WHERE ID=COURSE_ID) as COURSE_NAME,(SELECT ATTENDANCE_TYPE FROM COURSE WHERE ID=COURSE_ID) as COURSE_TYPE,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS ADMISSION_NO, (SELECT IMAGE1 FROM PROFILE WHERE ID=PROFILE_ID) AS IMAGE,
	    		(SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID=student_attendance.COURSEBATCH_ID) AS TOTAL_DAYS,
	    		CASE WHEN (SELECT COUNT(ID) FROM student_leave WHERE PROFILE_ID=student_attendance.PROFILE_ID) > 0 THEN 
				ROUND(((((SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID
				=student_attendance.COURSEBATCH_ID)-(SELECT COUNT(ID) FROM student_leave WHERE PROFILE_ID=student_attendance.PROFILE_ID))*100)/(SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID
				=student_attendance.COURSEBATCH_ID)),-1)
				  ELSE '0' END AS PERCENTAGE
		    	FROM student_attendance WHERE PROFILE_ID='$profileid' GROUP BY PROFILE_ID";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}
		}
		public function getStuAttendanceReport($type,$pId,$subjectId){
			// echo $type;
			// echo $pId;
			// exit;
			if($subjectId){
				$sql="SELECT * FROM STUDENT_ATTENDANCE WHERE PROFILE_ID='$pId' and STATUS='Y' AND SUBJECT_ID='$subjectId' GROUP BY SUBJECT_ID";
				// $sql="SELECT ID,COURSE_ID,COURSEBATCH_ID,SUBJECT_ID,DATE,PROFILE_ID,STATUS,
				// 	(SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID=student_attendance.COURSEBATCH_ID) AS TOTAL_DAYS,CASE WHEN (SELECT COUNT(ID) FROM student_leave WHERE PROFILE_ID=student_attendance.PROFILE_ID AND SUBJECT_ID=student_attendance.SUBJECT_ID) > 0 THEN 
				// 	ROUND(((((SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID
				// 	=student_attendance.COURSEBATCH_ID)-(SELECT COUNT(ID) FROM student_leave WHERE PROFILE_ID=student_attendance.PROFILE_ID AND SUBJECT_ID=student_attendance.SUBJECT_ID))*100)/(SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID
				// 	=student_attendance.COURSEBATCH_ID)),-1)
				//   ELSE '0' END AS PERCENTAGE FROM STUDENT_ATTENDANCE WHERE PROFILE_ID='$pId' and STATUS='Y' AND SUBJECT_ID='$subjectId' GROUP BY SUBJECT_ID";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}else {
				if($type=='Daily'){
					$sql="SELECT * FROM STUDENT_ATTENDANCE WHERE PROFILE_ID='$pId' and STATUS='Y'";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
				}else{
					// $sql="SELECT ID,COURSE_ID,COURSEBATCH_ID,SUBJECT_ID,DATE,PROFILE_ID,STATUS,(SELECT NAME FROM SUBJECT WHERE ID=SUBJECT_ID) AS SUBJECT_NAME FROM STUDENT_ATTENDANCE WHERE PROFILE_ID='$pId' and STATUS='Y'";
					$sql="SELECT ID,COURSE_ID,COURSEBATCH_ID,SUBJECT_ID,DATE,PROFILE_ID,STATUS,(SELECT NAME FROM SUBJECT WHERE ID=SUBJECT_ID) AS SUBJECT_NAME,(SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID=student_attendance.COURSEBATCH_ID) AS TOTAL_DAYS,CASE WHEN (SELECT COUNT(ID) FROM student_leave WHERE PROFILE_ID=student_attendance.PROFILE_ID AND SUBJECT_ID=student_attendance.SUBJECT_ID) > 0 THEN 
					ROUND(((((SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID
					=student_attendance.COURSEBATCH_ID)-(SELECT COUNT(ID) FROM student_leave WHERE PROFILE_ID=student_attendance.PROFILE_ID AND SUBJECT_ID=student_attendance.SUBJECT_ID))*100)/(SELECT TOTAL_DAYS FROM attendance_report WHERE PROFILE_ID=student_attendance.PROFILE_ID AND COURSEBATCH_ID
					=student_attendance.COURSEBATCH_ID)),-1)
				  ELSE '0' END AS PERCENTAGE FROM STUDENT_ATTENDANCE WHERE PROFILE_ID='$pId' and STATUS='Y' GROUP BY SUBJECT_ID";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
				}
			}
		}
		
		public function getStuProfileAttendanceReport($pId){
			//$sql="SELECT (SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID='$pId') as PROFILE_NAME,(SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID='$pId') as BATCH_ID,(SELECT NAME FROM course_batch WHERE ID=BATCH_ID) as BATCH_NAME,(SELECT COURSE_ID FROM course_batch WHERE ID=BATCH_ID) as COURSE_ID,(SELECT NAME FROM course WHERE ID=COURSE_ID)as COURSE_NAME, FROM student_profile WHERE PROFILE_ID='$pId'";
			if($pId){
				$sql="SELECT ID,PROFILE_ID,(SELECT NAME FROM course WHERE ID=student_leave.COURSE_ID) as COURSE_NAME,(SELECT NAME FROM course_batch WHERE ID=student_leave.COURSEBATCH_ID) as BATCH_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=student_leave.PROFILE_ID) as ADMISSION_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ROUND((((SELECT DATEDIFF((NOW()),DATE_FORMAT(min(DATE), '%Y-%m-%d')) FROM attendance ) - (SELECT count(*) FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId'))/(SELECT DATEDIFF((NOW()),DATE_FORMAT(min(DATE), '%Y-%m-%d')) FROM attendance ))*100,2) as total FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' GROUP BY PROFILE_ID)as Percentage FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' Group by PROFILE_ID";
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
				$headers = apache_request_headers();
				$access_token=$headers['access_token'];
				$sql="SELECT user_id,(SELECT USER_ROLE_ID FROM user WHERE USER_EMAIL=oauth_access_tokens.user_id) as Role,(SELECT USER_PROFILE_ID FROM user WHERE USER_EMAIL=oauth_access_tokens.user_id) as ProfileId FROM oauth_access_tokens WHERE access_token='$access_token'";
				$res = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($res){
					$pId=$res[0]['ProfileId'];
					$sql="SELECT ID,PROFILE_ID,(SELECT NAME FROM course WHERE ID=student_leave.COURSE_ID) as COURSE_NAME,(SELECT NAME FROM course_batch WHERE ID=student_leave.COURSEBATCH_ID) as BATCH_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=student_leave.PROFILE_ID) as ADMISSION_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,(SELECT ROUND((((SELECT DATEDIFF((NOW()),DATE_FORMAT(min(DATE), '%Y-%m-%d')) FROM attendance ) - (SELECT count(*) FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId'))/(SELECT DATEDIFF((NOW()),DATE_FORMAT(min(DATE), '%Y-%m-%d')) FROM attendance ))*100,2) as total FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' GROUP BY PROFILE_ID)as Percentage FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' Group by PROFILE_ID";
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
		
		// Mobile App Student Attendance Report in Month wise
		
		public function mGetStuProfileAttendanceReport($pId){
			$sql="SELECT ID,PROFILE_ID,MONTH(FROMDATE) as month,MONTHNAME(FROMDATE) as monthName,(SELECT NAME FROM course WHERE ID=student_leave.COURSE_ID) as COURSE_NAME,(SELECT NAME FROM course_batch WHERE ID=student_leave.COURSEBATCH_ID) as BATCH_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=student_leave.PROFILE_ID) as ADMISSION_NO,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) as PROFILE_NAME,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID)as IMAGE,ROUND((((CASE WHEN MONTH(FROMDATE) THEN (SELECT COUNT(*) COUNT FROM attendance WHERE MONTH(DATE)=month AND PROFILE_ID='$pId' AND ATTENDANCE_TYPE='Daily') ELSE 0 END)-(CASE WHEN MONTH(FROMDATE) THEN (SELECT COUNT(*) COUNT FROM student_leave WHERE MONTH(FROMDATE)=month AND PROFILE_ID='$pId' AND ATTENDANCE_TYPE='Daily') ELSE 0 END))/(CASE WHEN MONTH(FROMDATE) THEN (SELECT COUNT(*) COUNT FROM attendance WHERE MONTH(DATE)=month AND PROFILE_ID='$pId' AND ATTENDANCE_TYPE='Daily') ELSE 0 END))*100,2)as Percentage FROM student_leave WHERE ATTENDANCE_TYPE='Daily' AND PROFILE_ID='$pId' GROUP BY MONTH(FROMDATE)";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key => $value){
					$mnth=$value['month'];
					$sql1="SELECT REASON,FROMDATE,TODATE,COALESCE(DATEDIFF(TODATE,FROMDATE),1)as TOTAL_LEAVE FROM student_leave WHERE PROFILE_ID='$pId' AND MONTH(FROMDATE)='$mnth'";
					$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
					$result[$key]['leaveDetails']=$result1;
				}
				return $result;
			}
		}
		
	}
?>
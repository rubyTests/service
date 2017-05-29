<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class academics extends CI_Model {

		// Department Details 
		
		public function addDepartmentDetails($value){
			$name=$value['NAME'];
			$sql="SELECT * FROM department where NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['NAME'],
				   'CODE' => $value['CODE'],
				   'HOD' => $value['HOD'],
				   'PHONE' => $value['PHONE'],
				   'ROOM_ID' =>$value['ROOM_ID'],
				   'IMAGE' => $value['IMAGE'],
				   'CRT_USER_ID' => $value['CRT_USER_ID']
				);
				$this->db->insert('department', $data); 
				$dept_id= $this->db->insert_id();
				if(!empty($dept_id)){
					// return true;
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'DEPT_ID'=>$dept_id);
				}
			}
	    }
		
		public function editDepartmentDetails($id,$value){

			$sql="SELECT * FROM department where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['NAME']){
				$data = array(
				   'NAME' => $value['NAME'],
				   'CODE' => $value['CODE'],
				   'HOD' => $value['HOD'],
				   'PHONE' => $value['PHONE'],
				   'ROOM_ID' =>$value['ROOM_ID'],
				   'IMAGE' => $value['IMAGE'],
				   'UPD_USER_ID' => $value['UPD_USER_ID']
				);
				$this->db->where('ID', $id);
				$this->db->update('department', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'DEPT_ID'=>$id);
			}else {
				$name=$value['NAME'];
				$sql="SELECT * FROM department where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
					   'NAME' => $value['NAME'],
					   'CODE' => $value['CODE'],
					   'HOD' => $value['HOD'],
					   'PHONE' => $value['PHONE'],
					   'ROOM_ID' =>$value['ROOM_ID'],
					   'IMAGE' => $value['IMAGE'],
					   'UPD_USER_ID' => $value['UPD_USER_ID']
					);
					$this->db->where('ID', $id);
					$this->db->update('department', $data); 
					return array('status'=>true, 'message'=>"Record Updated Successfully",'DEPT_ID'=>$id);
				}
			}
		}
		
		public function getDepartmentDetailsAll(){
			$sql="SELECT * FROM department";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$room_id=$value['ROOM_ID'];
				$empProfile_id=$value['HOD'];
				$sql1="SELECT * FROM room where ID='$room_id'";
				$result[$key]['roomData'] = $this->db->query($sql1, $return_object = TRUE)->result_array();

				// Employee Profile
				$sql2="SELECT ID,concat(FIRSTNAME,' ',LASTNAME) AS EMP_NAME FROM profile where ID='$empProfile_id'";
				$result[$key]['empProfile'] = $this->db->query($sql2, $return_object = TRUE)->result_array();
			}
			// exit;
			return $result;
		}
		
		public function getDepartmentDetails($id){
			$sql="SELECT * FROM department where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deleteDepartmentDetails($id){
			$sql="DELETE FROM department where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Course Details 
		
		public function addCourseDetails($value){
			$name=$value['NAME'];
			$sql="SELECT * FROM course where NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['NAME'],
				   'DEPT_ID' => $value['DEPT_ID'],
				   'ATTENDANCE_TYPE' => $value['ATTENDANCE_TYPE'],
				   'PERCENTAGE' => $value['PERCENTAGE'],
				   'GARDE_TYPE' => $value['GARDE_TYPE'],
				   'CRT_USER_ID' => $value['CRT_USER_ID']
				);
				$this->db->insert('course', $data); 
				$course_id= $this->db->insert_id();
				if(!empty($course_id)){
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'COURSE_ID'=>$course_id);
				}
			}
	    }
		
		public function editCourseDetails($id,$value){
			$name=$value['NAME'];
			$sql="SELECT * FROM course where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['NAME']){
				$data = array(
				   'NAME' => $value['NAME'],
				   'DEPT_ID' => $value['DEPT_ID'],
				   'ATTENDANCE_TYPE' => $value['ATTENDANCE_TYPE'],
				   'PERCENTAGE' => $value['PERCENTAGE'],
				   'GARDE_TYPE' => $value['GARDE_TYPE'],
				   'UPD_USER_ID' => $value['UPD_USER_ID']
				);
				$this->db->where('ID', $id);
				$this->db->update('course', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'COURSE_ID'=>$id);
			}else {
				$name=$value['NAME'];
				$sql="SELECT * FROM course where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
					   'NAME' => $value['NAME'],
					   'DEPT_ID' => $value['DEPT_ID'],
					   'ATTENDANCE_TYPE' => $value['ATTENDANCE_TYPE'],
					   'PERCENTAGE' => $value['PERCENTAGE'],
					   'GARDE_TYPE' => $value['GARDE_TYPE'],
					   'UPD_USER_ID' => $value['UPD_USER_ID']
					);
					$this->db->where('ID', $id);
					$this->db->update('course', $data); 
					return array('status'=>true, 'message'=>"Record Updated Successfully",'COURSE_ID'=>$id);
				}
			}
		}
		
		public function getCourseDetailsAll(){
			$sql="SELECT * FROM course";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$dept_id=$value['DEPT_ID'];
				$sql1="SELECT * FROM department where ID='$dept_id'";
				$result[$key]['deptData'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;
		}
		
		public function getCourseDetails($id){
			$sql="SELECT * FROM course where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deleteCourseDetails($id){
			$sql="DELETE FROM course where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Batch Details 
		
		public function addBatchDetails($value){
			$name=$value['NAME'];
			$course_id=$value['COURSE_ID'];
			$sql="SELECT * FROM course_batch where NAME='$name' and COURSE_ID='$course_id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['NAME'],
				   'COURSE_ID' => $value['COURSE_ID'],
				   'PERIOD_FROM' => $value['PERIOD_FROM'],
				   'PERIOD_TO' => $value['PERIOD_TO'],
				   'INCHARGE' => $value['INCHARGE']
				);
				$this->db->insert('course_batch', $data); 
				$bacth_id= $this->db->insert_id();
				if(!empty($bacth_id)){
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'BATCH_ID'=>$bacth_id);
				}
			}
	    }
		
		public function editBatchDetails($id,$value){
			$sql="SELECT * FROM course_batch where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['NAME'] && $result[0]['COURSE_ID']==$value['COURSE_ID']){
				$data = array(
				   'NAME' => $value['NAME'],
				   'COURSE_ID' => $value['COURSE_ID'],
				   'PERIOD_FROM' => $value['PERIOD_FROM'],
				   'PERIOD_TO' => $value['PERIOD_TO'],
				   'INCHARGE' => $value['INCHARGE']
				);
				$this->db->where('ID', $id);
				$this->db->update('course_batch', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'BATCH_ID'=>$id);return true;
			}else {
				$name=$value['NAME'];
				$course_id=$value['COURSE_ID'];
				$sql="SELECT * FROM course_batch where NAME='$name' and COURSE_ID='$course_id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
					   'NAME' => $value['NAME'],
					   'COURSE_ID' => $value['COURSE_ID'],
					   'PERIOD_FROM' => $value['PERIOD_FROM'],
					   'PERIOD_TO' => $value['PERIOD_TO'],
					   'INCHARGE' => $value['INCHARGE']
					);
					$this->db->where('ID', $id);
					$this->db->update('course_batch', $data); 
					return array('status'=>true, 'message'=>"Record Updated Successfully",'BATCH_ID'=>$id);return true;
				}
			}			
		}
		
		public function getBatchDetailsAll(){
			$sql="SELECT * FROM course_batch";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$course_id=$value['COURSE_ID'];
				$incharge_id=$value['INCHARGE'];
				$sql1="SELECT * FROM course where ID='$course_id'";
				$result[$key]['courseData'] = $this->db->query($sql1, $return_object = TRUE)->result_array();

				$sql2="SELECT ID,concat(FIRSTNAME,' ',LASTNAME) AS EMP_NAME FROM profile where ID='$incharge_id'";
				$result[$key]['empData'] = $this->db->query($sql2, $return_object = TRUE)->result_array();
			}
			return $result;
		}
		
		public function getBatchDetails($id){
			$sql="SELECT * FROM course_batch where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deleteBatchDetails($id){
			$sql="DELETE FROM course_batch where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Subject Details 
		
		public function addSubjectDetails($value){
			$name=$value['NAME'];
			$course_id=$value['COURSE_ID'];
			$sql="SELECT * FROM course_subject_view where NAME='$name' and COURSE_ID='$course_id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['NAME'],
				   'CODE' => $value['CODE'],
				   'TYPE' => $value['TYPE'],
				   'TOTAL_HOURS' => $value['TOTAL_HOURS'],
				   'CREDIT_HOURS' => $value['CREDIT_HOURS'],
				   'CRT_USER_ID' => $value['CRT_USER_ID']
				);
				$this->db->insert('subject', $data); 
				$subjectID= $this->db->insert_id();
				if(!empty($subjectID)){
					$data = array(
					   'COURSE_ID' => $value['COURSE_ID'],
					   'SUBJECT_ID' => $subjectID,
					   'CRT_USER_ID' => $value['CRT_USER_ID']
					);
					$this->db->insert('course_subject', $data);
					$C_SUB_ID= $this->db->insert_id();
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'COUSRE_SUB_ID'=>$C_SUB_ID,'SUBJECTID'=>$subjectID);
				}
			}			
	    }
		
		public function editSubjectDetails($id,$value){

			$sql="SELECT * FROM course_subject_view where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['NAME'] && $result[0]['COURSE_ID']==$value['COURSE_ID']){
				$data = array(
				   'NAME' => $value['NAME'],
				   'CODE' => $value['CODE'],
				   'TYPE' => $value['TYPE'],
				   'TOTAL_HOURS' => $value['TOTAL_HOURS'],
				   'CREDIT_HOURS' => $value['CREDIT_HOURS'],
				   'UPD_USER_ID' => $value['UPD_USER_ID']
				);
				$this->db->where('ID', $value['SUBID']);
				$this->db->update('subject', $data); 
				if($id){
					$data1 = array(
					   'COURSE_ID' => $value['COURSE_ID'],
					   'SUBJECT_ID' => $value['SUBID'],
					   'CRT_USER_ID' => $value['CRT_USER_ID']
					);
					$this->db->where('ID', $id);
					$this->db->update('course_subject', $data1); 
					return array('status'=>true, 'message'=>"Record Updated Successfully",'COUSRE_SUB_ID'=>$id,'SUBJECTID'=>$value['SUBID']);
				}
			}else {
				$name=$value['NAME'];
				$course_id=$value['COURSE_ID'];
				$sql="SELECT * FROM course_subject_view where NAME='$name' and COURSE_ID='$course_id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
					   'NAME' => $value['NAME'],
					   'CODE' => $value['CODE'],
					   'TYPE' => $value['TYPE'],
					   'TOTAL_HOURS' => $value['TOTAL_HOURS'],
					   'CREDIT_HOURS' => $value['CREDIT_HOURS'],
					   'UPD_USER_ID' => $value['UPD_USER_ID']
					);
					$this->db->where('ID', $value['SUBID']);
					$this->db->update('subject', $data); 
					if($id){
						$data1 = array(
						   'COURSE_ID' => $value['COURSE_ID'],
						   'SUBJECT_ID' => $value['SUBID'],
						   'CRT_USER_ID' => $value['CRT_USER_ID']
						);
						$this->db->where('ID', $id);
						$this->db->update('course_subject', $data1); 
						return array('status'=>true, 'message'=>"Record Updated Successfully",'COUSRE_SUB_ID'=>$id,'SUBJECTID'=>$value['SUBID']);
					}
				}
			}
			
		}
		
		public function getSubjectDetailsAll(){
			$sql="SELECT * FROM course_subject_view";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function getSubjectDetails($id){
			$sql="SELECT * FROM subject where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deleteSubjectDetails($id,$subID){
			$sql="DELETE FROM subject where ID='$subID'";
			$result = $this->db->query($sql);
			// echo "<pre>";
			// print_r($result);exit;
			if($result==1){
				$sql="DELETE FROM course_subject where ID='$id'";
				$result = $this->db->query($sql);
				return $this->db->affected_rows();
			}
	    	// return $this->db->affected_rows();
		}
		
		// Syllabus Details 
		
		public function addSyllabusDetails($value){
			// print_r($value);exit;
			$sub_id=$value['SUBJECT_ID'];
			$course_id=$value['COURSE_ID'];
			$sql="SELECT * FROM subject_syllabus_view where SUBJECT_ID='$sub_id' and COURSE_ID='$course_id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return false;
			}else {
				$data = array(
				   'COURSE_ID' => $value['COURSE_ID'],
				   'SUBJECT_ID' => $value['SUBJECT_ID']
				);
				$this->db->insert('subject_syllabus', $data);
				$sub_syllabus_id= $this->db->insert_id();
				$total=count($value['SYLLABUS_DATA']);
				for($i=0;$i<$total;$i++){
					$data = array(
					   'NAME' => $value['SYLLABUS_DATA'][$i]['syllabus_title'],
					   'DESC' => $value['SYLLABUS_DATA'][$i]['syllabus_content'],
					   'SUB_SYLLABUS_ID' => $sub_syllabus_id
					);
					$this->db->insert('syllabus', $data);
				}
				return true;
			}
	    }
		
		public function editSyllabusDetails($id,$value){

			$sql="SELECT * FROM subject_syllabus_view where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['SUBJECT_ID']==$value['SUBJECT_ID'] && $result[0]['COURSE_ID']==$value['COURSE_ID']){
				$data = array(
				   'COURSE_ID' => $value['COURSE_ID'],
				   'SUBJECT_ID' => $value['SUBJECT_ID']
				);
				$this->db->where('ID', $id);
				$this->db->update('subject_syllabus', $data);

				$total=count($value['SYLLABUS_DATA']);
				for($i=0;$i<$total;$i++){
					$SYL_ID=$value['SYLLABUS_DATA'][$i]['syllabus_ID'];
					if($SYL_ID){
						$data1 = array(
						   'NAME' => $value['SYLLABUS_DATA'][$i]['syllabus_title'],
						   'DESC' => $value['SYLLABUS_DATA'][$i]['syllabus_content'],
						   'SUB_SYLLABUS_ID' => $id
						);
						// echo "<pre>";print_r($data1);	
						$this->db->where('ID', $SYL_ID);
						$this->db->update('syllabus', $data1);
					}else {
						// echo "Insert";
						$data2 = array(
						   'NAME' => $value['SYLLABUS_DATA'][$i]['syllabus_title'],
						   'DESC' => $value['SYLLABUS_DATA'][$i]['syllabus_content'],
						   'SUB_SYLLABUS_ID' => $id
						);
						// echo "<pre>";print_r($data2);
						$this->db->insert('syllabus', $data2);
					}
				}
				return true;
			}else {
				$sub_id=$value['SUBJECT_ID'];
				$course_id=$value['COURSE_ID'];
				$sql="SELECT * FROM subject_syllabus_view where SUBJECT_ID='$sub_id' and COURSE_ID='$course_id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return false;
				}else {
					$data = array(
					   'COURSE_ID' => $value['COURSE_ID'],
					   'SUBJECT_ID' => $value['SUBJECT_ID']
					);
					$this->db->where('ID', $id);
					$this->db->update('subject_syllabus', $data);

					$total=count($value['SYLLABUS_DATA']);
					for($i=0;$i<$total;$i++){
						$SYL_ID=$value['SYLLABUS_DATA'][$i]['syllabus_ID'];
						if($SYL_ID){
							$data1 = array(
							   'NAME' => $value['SYLLABUS_DATA'][$i]['syllabus_title'],
							   'DESC' => $value['SYLLABUS_DATA'][$i]['syllabus_content'],
							   'SUB_SYLLABUS_ID' => $id
							);
							// echo "<pre>";print_r($data1);	
							$this->db->where('ID', $SYL_ID);
							$this->db->update('syllabus', $data1);
						}else {
							// echo "Insert";
							$data2 = array(
							   'NAME' => $value['SYLLABUS_DATA'][$i]['syllabus_title'],
							   'DESC' => $value['SYLLABUS_DATA'][$i]['syllabus_content'],
							   'SUB_SYLLABUS_ID' => $id
							);
							// echo "<pre>";print_r($data2);
							$this->db->insert('syllabus', $data2);
						}
					}
					return true;
				}
			}			
		}
		
		public function getSyllabusDetailsAll(){
			$sql="SELECT * FROM subject_syllabus_view";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function getSyllabusDetails($id){
			$sql="SELECT * FROM syllabus where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deleteSyllabusDetails($id,$syl_id){
			// $sql1="SELECT * FROM syllabus WHERE ID='$id'";
			// $result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// // print_r($result1);
			// foreach ($result1 as $row) {
			//   	echo $syl_id=$row['SUB_SYLLABUS_ID'];
			//   	echo $sql2="DELETE FROM syllabus where SUB_SYLLABUS_ID='$syl_id'";
			// 	$result2 = $this->db->query($sql2);
			// }exit;
			$sql2="DELETE FROM syllabus where SUB_SYLLABUS_ID='$syl_id'";
			$result2 = $this->db->query($sql2);

			$sql="DELETE FROM subject_syllabus where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}



		// Extra Code
		public function getEmployeeDetails(){
			$sql="SELECT ID, concat(FIRSTNAME,' ',LASTNAME) as EMP_NAME FROM profile";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		public function getDepartmentList(){
			$sql="SELECT * FROM department";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		public function getCourseList(){
			$sql="SELECT * FROM course";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		public function getSubjectList(){
			$sql="SELECT * FROM subject";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		public function getSubjectSyllabusList($id){
			$sql="SELECT * FROM subject_syllabus WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		public function getSyllabusListDetail($id){
			$sql="SELECT * FROM syllabus WHERE SUB_SYLLABUS_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		public function getAllSubjectsyllabusID($roleId,$profileId){
			if($roleId==3){
				$sql="SELECT B.COURSE_ID FROM student_profile as SP INNER JOIN  course_batch as B ON SP.COURSEBATCH_ID=B.ID WHERE SP.PROFILE_ID='$profileId'";
				$res = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($res){
					$courseId=$res[0]['COURSE_ID'];
					$sql="SELECT subject.ID,subject.NAME,(SELECT ID FROM subject_syllabus where SUBJECT_ID=subject.ID) AS SUBJECT_ID FROM subject WHERE subject.ID='$courseId'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					// $data['resulat']=$result;
					foreach ($result as $key => $value) {
						$syl_id=$value['SUBJECT_ID'];
						$sql1="SELECT * FROM syllabus where SUB_SYLLABUS_ID='$syl_id'";
						$result[$key]['syllabus'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
					}
					return $result;
				}
			}else{
				$sql="SELECT subject.ID,subject.NAME,(SELECT ID FROM subject_syllabus where SUBJECT_ID=subject.ID) AS SUBJECT_ID FROM subject";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				// $data['resulat']=$result;
				foreach ($result as $key => $value) {
					$syl_id=$value['SUBJECT_ID'];
					$sql1="SELECT * FROM syllabus where SUB_SYLLABUS_ID='$syl_id'";
					$result[$key]['syllabus'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
				}
				return $result;
			}
			



			// for($i=0;$i<count($result);$i++) {
			// 	$syl_id=$result[$i]['SUBJECT_ID'];
			// 	$sql1="SELECT * FROM syllabus where SUB_SYLLABUS_ID='$syl_id'";
			// 	$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// 	$data['resulat1']=$result1;
			// }


			//print_r($result);exit;
			// return $data;
		}
		public function getAllSyllabusDetail(){	
			$sql="SELECT * FROM syllabus";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		// For assign teacher
		public function getParticularCousreList($id){
			$sql="SELECT * FROM course where DEPT_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getParticularBatchList($id){
			$sql="SELECT * FROM course_batch where COURSE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getParticularSubjectList($id){
			$sql="SELECT (SELECT ID FROM subject WHERE ID=course_subject.SUBJECT_ID) AS COU_ID,(SELECT NAME FROM subject WHERE ID=course_subject.SUBJECT_ID) AS COURSE_NAME
				FROM course_subject WHERE COURSE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		public function getParticularEmployeeList($id){
			$sql="SELECT (SELECT ID FROM profile WHERE ID=employee_profile.PROFILE_ID) AS EMP_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile WHERE ID=employee_profile.PROFILE_ID) AS EMP_ANME
				FROM employee_profile WHERE DEPT_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function saveTeacherDetails($value){
				$data = array(
				   'COURSE_ID' => $value['course_id'],
				   'SUBJECT_ID' => $value['subject'],
				   'EMP_PROFILE_ID' => $value['employee_id']
				);
				$this->db->insert('EMPLOYEE_SUBJECT', $data); 
				return array('status'=>true, 'message'=>"Record Inserted Successfully");
	    }
	    public function updateTeacherDetails($id,$value){
	    	if($id){
	    		$data = array(
				   'COURSE_ID' => $value['course_id'],
				   'SUBJECT_ID' => $value['subject'],
				   'EMP_PROFILE_ID' => $value['employee_id']
				);
				$this->db->where('ID', $id);
				$this->db->update('EMPLOYEE_SUBJECT', $data);
				return array('status'=>true, 'message'=>"Record Updated Successfully");
	    	}
	    }

	    public function getAllteacherDetails(){
			$sql="SELECT * FROM EMPLOYEE_ASSIGN_VIEW";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		public function getParticularTeacherDetails($id){
			$sql="SELECT * FROM course where ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function deleteteacherDetails($id){
			$sql="DELETE FROM employee_subject where ID='$id'";
			$result = $this->db->query($sql);
			return $this->db->affected_rows();
		}
	}
?>
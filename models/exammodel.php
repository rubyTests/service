<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class exammodel extends CI_Model {
		
		// Set Grade
		public function getAllSetGradeDetails(){
			$sql="SELECT * FROM e_grade";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getSetGradeDetails($id){
			$sql="SELECT * FROM e_grade WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addSetGradeDetails($values){
			$data = array(
				'NAME' => $values['NAME'],
				'MINI_PERCENTAGE' => $values['MINI_PERCENTAGE'],
				'CREDIT_POINTS' => $values['CREDIT_POINTS'],
				'DESCRIPTION' => $values['DESCRIPTION']
			);
			$this->db->insert('e_grade', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editSetGradeDetails($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'MINI_PERCENTAGE' => $values['MINI_PERCENTAGE'],
				'CREDIT_POINTS' => $values['CREDIT_POINTS'],
				'DESCRIPTION' => $values['DESCRIPTION']
			);
			$this->db->where('id', $Id);
			$this->db->update('e_grade', $data);
			return true;
		}
		
		public function deleteSetGradeDetails($id){
			$sql="DELETE FROM e_grade where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Set Exam
		public function getAllSetExamDetails(){
			$sql="SELECT * FROM e_exam";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getSetExamDetails($id){
			$sql="SELECT * FROM e_exam WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addSetExamDetails($values){
			$data = array(
				'NAME' => $values['NAME'],
				'DESCRIPTION' => $values['DESCRIPTION']
			);
			$this->db->insert('e_exam', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editSetExamDetails($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'DESCRIPTION' => $values['DESCRIPTION']
			);
			$this->db->where('id', $Id);
			$this->db->update('e_exam', $data);
			return true;
		}
		
		public function deleteSetExamDetails($id){
			$sql="DELETE FROM e_exam where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Set Assessment
		public function getAllSetAssessmentDetails(){
			$sql="SELECT ID,NAME,WEIGHTAGE,EXAM_ID,(SELECT NAME FROM e_exam where ID=EXAM_ID)as EXAM FROM e_assessment";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getSetAssessmentDetails($id){
			$sql="SELECT ID,NAME,WEIGHTAGE,EXAM_ID,(SELECT NAME FROM e_exam where ID=EXAM_ID)as EXAM FROM e_assessment WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addSetAssessmentDetails($values){
			$data = array(
				'NAME' => $values['NAME'],
				'WEIGHTAGE' => $values['WEIGHTAGE'],
				'EXAM_ID' => $values['EXAM_ID']
			);
			$this->db->insert('e_assessment', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editSetAssessmentDetails($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'WEIGHTAGE' => $values['WEIGHTAGE'],
				'EXAM_ID' => $values['EXAM_ID']
			);
			$this->db->where('id', $Id);
			$this->db->update('e_assessment', $data);
			return true;
		}
		
		public function deleteSetAssessmentDetails($id){
			$sql="DELETE FROM e_assessment where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Set Weightage
		public function getAllSetWeightageDetails(){
			$sql="SELECT ID,ASSESSMENT_ID,WEIGHTAGE,(SELECT NAME FROM e_assessment where ID=ASSESSMENT_ID)as ASSESSMENT_NAME FROM e_weightage";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getSetWeightageDetails($id){
			$sql="SELECT ID,ASSESSMENT_ID,WEIGHTAGE,(SELECT NAME FROM e_assessment where ID=ASSESSMENT_ID)as ASSESSMENT_NAME FROM e_weightage WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addSetWeightageDetails($values){
			$data = array(
				'ASSESSMENT_ID' => $values['ASSESSMENT_ID'],
				'WEIGHTAGE' => $values['WEIGHTAGE']
			);
			$this->db->insert('e_weightage', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editSetWeightageDetails($Id,$values){
			$data = array(
				'ASSESSMENT_ID' => $values['ASSESSMENT_ID'],
				'WEIGHTAGE' => $values['WEIGHTAGE']
			);
			$this->db->where('id', $Id);
			$this->db->update('e_weightage', $data);
			return true;
		}
		
		public function deleteSetWeightageDetails($id){
			$sql="DELETE FROM e_weightage where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Set Assign Exam
		public function getAllSetAssignExamDetails(){
			$sql="SELECT ID,NAME,ASSESSMENT_ID,COURSE_ID,(SELECT NAME FROM e_assessment WHERE ID=ASSESSMENT_ID)as ASSESSMENT_NAME,(SELECT count(*) FROM e_assignexam_course WHERE ASSIGNEXAM_ID=ID)as COURSE FROM e_assignexam";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getSetAssignExamDetails($id){
			$sql="SELECT ID,NAME,ASSESSMENT_ID,COURSE_ID,(SELECT NAME FROM e_assessment WHERE ID=ASSESSMENT_ID)as ASSESSMENT_NAME,(SELECT count(*) FROM e_assignexam_course WHERE ASSIGNEXAM_ID=ID)as COURSE FROM e_assignexam WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addSetAssignExamDetails($values){
			$data = array(
				'NAME' => $values['NAME'],
				'ASSESSMENT_ID' => $values['ASSESSMENT_ID'],
				'START_DATE' => $values['START_DATE'],
				'END_DATE' => $values['END_DATE']
				// 'COURSE_ID' => $values['COURSE_ID']
			);
			$this->db->insert('e_assignexam', $data);
			$assignExam_id= $this->db->insert_id();
			if($assignExam_id){
				foreach($values['COURSE_ID'] as $cId){
					$data = array(
						'ASSIGNEXAM_ID' => $assignExam_id,
						'COURSE_ID' => $cId
					);
					$this->db->insert('e_assignexam_course', $data);
				}
				return $assignExam_id;
			}
		}
		
		public function editSetAssignExamDetails($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'ASSESSMENT_ID' => $values['ASSESSMENT_ID'],
				'START_DATE' => $values['START_DATE'],
				'END_DATE' => $values['END_DATE']
				//'COURSE_ID' => $values['COURSE_ID']
			);
			$this->db->where('id', $Id);
			$this->db->update('e_assignexam', $data);
			
			$sql="SELECT * FROM e_assignexam_course WHERE ASSIGNEXAM_ID='$Id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $value){
					print_r($value);
					$data = array(
						'ASSIGNEXAM_ID' => $assignExam_id,
						'COURSE_ID' => $cId
					);
					$this->db->insert('e_assignexam_course', $data);
				}
				exit;
			}
			// return true;
		}
		
		public function deleteSetAssignExamDetails($id){
			$sql="DELETE FROM e_assignexam where ID='$id'";
			$sql1="DELETE FROM e_assignexam_course where ASSIGNEXAM_ID='$id'";
			$result = $this->db->query($sql);
			$result = $this->db->query($sql1);
	    	return $this->db->affected_rows();
		}
		
		// Created @ 03.06.2017 6:04PM
	
		// Set Term
		
		public function getAllSetTermDetails(){
			$sql="SELECT * FROM e_setterm";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getSetTermDetails($id){
			$sql="SELECT * FROM e_setterm WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addSetTermDetails($values){
			$data = array(
				'NAME' => $values['NAME'],
				'DESCRIPTION' => $values['DESCRIPTION']
			);
			$this->db->insert('e_setterm', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editSetTermDetails($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'DESCRIPTION' => $values['DESCRIPTION']
			);
			$this->db->where('id', $Id);
			$this->db->update('e_setterm', $data);
			return true;
		}
		
		public function deleteSetTermDetails($id){
			$sql="DELETE FROM e_setterm where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Create Exam
		
		public function getAllSetCreateExamDetails(){
			$sql="SELECT ID,NAME,SETTERM_ID,STARTDATE,ENDDATE,(SELECT NAME FROM e_setterm WHERE ID=SETTERM_ID)as TermName FROM e_createexam";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getSetCreateExamDetails($id){
			$sql="SELECT ID,NAME,SETTERM_ID,STARTDATE,ENDDATE,(SELECT NAME FROM e_setterm WHERE ID=SETTERM_ID)as TermName FROM e_createexam WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addSetCreateExamDetails($values){
			$data = array(
				'NAME' => $values['NAME'],
				'SETTERM_ID' => $values['SETTERM_ID'],
				'STARTDATE' => $values['STARTDATE'],
				'ENDDATE' => $values['ENDDATE']
			);
			$this->db->insert('e_createexam', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editSetCreateExamDetails($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'SETTERM_ID' => $values['SETTERM_ID'],
				'STARTDATE' => $values['STARTDATE'],
				'ENDDATE' => $values['ENDDATE']
			);
			$this->db->where('id', $Id);
			$this->db->update('e_createexam', $data);
			return true;
		}
		
		public function deleteSetCreateExamDetails($id){
			$sql="DELETE FROM e_createexam where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Set Assessment
		
		public function getAllAssessment1Details(){
			$sql="SELECT ID,NAME,CREATEEXAM_ID,MAX_MARK,(SELECT NAME FROM e_createexam WHERE ID=CREATEEXAM_ID)as EXAM_NAME FROM e_setassessment";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getSetAssessment1Details($id){
			$sql="SELECT ID,NAME,CREATEEXAM_ID,MAX_MARK,(SELECT NAME FROM e_createexam WHERE ID=CREATEEXAM_ID)as EXAM_NAME FROM e_setassessment WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addSetAssessment1Details($values){
			$data = array(
				'NAME' => $values['NAME'],
				'CREATEEXAM_ID' => $values['CREATEEXAM_ID'],
				'MAX_MARK' => $values['MAX_MARK']
			);
			$this->db->insert('e_setassessment', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editSetAssessment1Details($Id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'SETTERM_ID' => $values['SETTERM_ID'],
				'STARTDATE' => $values['STARTDATE'],
				'ENDDATE' => $values['ENDDATE']
			);
			$this->db->where('id', $Id);
			$this->db->update('e_setassessment', $data);
			return true;
		}
		
		public function deleteSetAssessment1Details($id){
			$sql="DELETE FROM e_setassessment where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Set Examination
		
		public function getAllExaminationDetails(){
			$sql="SELECT ID,NAME,CREATEEXAM_ID,MAX_MARK,(SELECT NAME FROM e_createexam WHERE ID=CREATEEXAM_ID)as EXAM_NAME FROM e_setexamination";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getSetExaminationDetails($id){
			$sql="SELECT ID,NAME,CREATEEXAM_ID,MAX_MARK,(SELECT NAME FROM e_createexam WHERE ID=CREATEEXAM_ID)as EXAM_NAME FROM e_setexamination WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addSetExaminationDetails($values){
			$data = array(
				'COURSEBATCH_ID' => $values['COURSEBATCH_ID'],
				'CREATEEXAM_ID' => $values['CREATEEXAM_ID'],
				'SUBJECT_ID' => $values['SUBJECT_ID'],
				'DATE' => $values['DATE'],
				'START_TIME' => $values['START_TIME'],
				'END_TIME' => $values['END_TIME'],
				'PASS_MARK' => $values['PASS_MARK'],
				'MAX_MARK' => $values['MAX_MARK']
			);
			$this->db->insert('e_setexamination', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editSetExaminationDetails($Id,$values){
			$data = array(
				'COURSEBATCH_ID' => $values['COURSEBATCH_ID'],
				'CREATEEXAM_ID' => $values['CREATEEXAM_ID'],
				'SUBJECT_ID' => $values['SUBJECT_ID'],
				'DATE' => $values['DATE'],
				'START_TIME' => $values['START_TIME'],
				'END_TIME' => $values['END_TIME'],
				'PASS_MARK' => $values['PASS_MARK'],
				'MAX_MARK' => $values['MAX_MARK']
			);
			$this->db->where('id', $Id);
			$this->db->update('e_setexamination', $data);
			return true;
		}
		
		public function deleteSetExaminationDetails($id){
			$sql="DELETE FROM e_setexamination where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function getCalendarExamDetails(){
			$sql="SELECT (SELECT COURSE_ID FROM course_batch WHERE ID=COURSEBATCH_ID)AS COURSE_ID,(SELECT NAME FROM course WHERE ID=COURSE_ID)AS COURSE_NAME,COURSEBATCH_ID,(SELECT NAME FROM course_batch WHERE ID=COURSEBATCH_ID)AS BATCH_NAME FROM e_setexamination GROUP BY COURSEBATCH_ID";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key => $value){
					$batchId=$value['COURSEBATCH_ID'];
					$sql="SELECT ID,SUBJECT_ID,(SELECT NAME FROM subject WHERE ID=SUBJECT_ID)AS SUBJECT_NAME,CREATEEXAM_ID,(SELECT NAME FROM e_createexam WHERE ID=CREATEEXAM_ID)AS CREATEEXAM_NAME,(SELECT SETTERM_ID FROM e_createexam WHERE ID=CREATEEXAM_ID)AS TERM_ID,(SELECT NAME FROM e_setterm WHERE ID=TERM_ID)AS TERM_NAME,DATE,CONCAT(DATE,' ',START_TIME)AS START_TIME,CONCAT(DATE,' ',END_TIME)AS END_TIME,PASS_MARK,MAX_MARK FROM e_setexamination WHERE COURSEBATCH_ID='$batchId'";
					$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					// foreach($result1 as $key => $value){
						// $data[$key]['exam']=$value['TERM_NAME'];
						// $data[$key]['assessment']=$value['CREATEEXAM_NAME'];
						// $data[$key]['max_mark']=$value['MAX_MARK'];
						// // $data[$key]['subject']=$value['SUBJECT_NAME'];
						// $data[$key]['title']=$value['SUBJECT_NAME'];
						// $data[$key]['start']=$value['START_TIME'];
						// $data[$key]['end']=$value['END_TIME'];
						// $data[$key]['_id']=$value['ID'];
					// }
					$result[$key]['events']=$result1;
					//$result[$key]['events']=$result1;
				}
				return $result;
			}
		}
		
		public function getStudentDetails($data){
			$batchId=$data['batch'];
			$assessmentId=$data['assessmentId'];
			$subject=$data['subject'];
			$sql="SELECT ID FROM e_setassessment_mark WHERE SETASSESSMENT_ID='$assessmentId' AND SUBJECT_ID='$subject'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$sql="SELECT ID,SETASSESSMENT_ID,MARK,COURSEBATCH_ID,SUBJECT_ID,PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as Name,(SELECT MAX_MARK FROM e_setassessment WHERE ID=SETASSESSMENT_ID)as MAX_MARK FROM e_setassessment_mark WHERE SETASSESSMENT_ID='$assessmentId' AND SUBJECT_ID='$subject'";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}else{
				$sql="SELECT ID,PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=student_profile.PROFILE_ID)as Name,(SELECT MAX_MARK FROM e_setassessment WHERE ID='$assessmentId')as MAX_MARK FROM student_profile WHERE COURSEBATCH_ID='$batchId'";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}
		}
		
		// Assessment markList
		public function addStudentDetails($val){
			//print_r($val);exit;
			$assessmentId=$val['assessmentId'];
			$subject=$val['subject'];
			$sql="SELECT ID FROM e_setassessment_mark WHERE SETASSESSMENT_ID='$assessmentId' AND SUBJECT_ID='$subject'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($val['markList'] as $values){
					$data = array(
						'SETASSESSMENT_ID' => $values['SETASSESSMENT_ID'],
						'MARK' => $values['MARK'],
						'COURSEBATCH_ID' => $values['COURSEBATCH_ID'],
						'SUBJECT_ID' => $values['SUBJECT_ID'],
						'PROFILE_ID' => $values['PROFILE_ID']
					);
					$this->db->where('id', $values['ID']);
					$this->db->update('e_setassessment_mark', $data);
				}
				return true;
			}else{
				foreach($val['markList'] as $values){
					$data = array(
						'SETASSESSMENT_ID' => $val['assessmentId'],
						'MARK' => $values['MARK'],
						'COURSEBATCH_ID' => $val['batch'],
						'SUBJECT_ID' => $val['subject'],
						'PROFILE_ID' => $values['PROFILE_ID']
					);
					$this->db->insert('e_setassessment_mark', $data);
				}
				return true;
			}
		}
		
		// Exam markList
		public function addExamStuDetails($val){
			$createExam=$val['createExam'];
			$batch=$val['batch'];
			$subject=$val['subject'];
			$sql="SELECT ID FROM e_setexamination WHERE CREATEEXAM_ID='$createExam' AND SUBJECT_ID='$subject' AND COURSEBATCH_ID='$batch' ";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$setExamId=$result[0]['ID'];
				foreach($val['markList'] as $values){
					$sql="SELECT ID FROM e_marklist WHERE SETEXAM_ID='$setExamId'";
					$res = $this->db->query($sql, $return_object = TRUE)->result_array();
					if($res){
						$data = array(
							'SETEXAM_ID' => $values['SETEXAM_ID'],
							'MARK' => $values['MARK'],
							'PROFILE_ID' => $values['PROFILE_ID']
						);
						$this->db->where('id', $values['ID']);
						$this->db->update('e_marklist', $data);
						$msg="Student Mark List Details Updated Successfully";
					}else{
						$data = array(
							'SETEXAM_ID' => $setExamId,
							'MARK' => $values['MARK'],
							'PROFILE_ID' => $values['PROFILE_ID']
						);
						$this->db->insert('e_marklist', $data);
						$msg="Student Mark List Details Inserted Successfully";
					}
				}
				return array(['status'=>'true','message'=>$msg]);
			}else{
				return array(['status'=>'false','message'=>'Not found']);
			}
		}
		
		public function getExamStuDetails($data){
			$batch=$data['batch'];
			$createExam=$data['createExam'];
			$subject=$data['subject'];
			$sql="SELECT ID FROM e_setexamination WHERE CREATEEXAM_ID='$createExam' AND SUBJECT_ID='$subject' AND COURSEBATCH_ID='$batch' AND DATE < CURDATE()";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$setExamId=$result[0]['ID'];
				$sql="SELECT ID FROM e_marklist WHERE SETEXAM_ID='$setExamId'";
				$res = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($res){
					$sql="SELECT ID,SETEXAM_ID,MARK,PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as Name,(SELECT MAX_MARK FROM e_setexamination WHERE CREATEEXAM_ID='$createExam' AND SUBJECT_ID='$subject' AND COURSEBATCH_ID='$batch')as MAX_MARK FROM e_marklist WHERE SETEXAM_ID='$setExamId'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					return array(['status'=>'true','message'=>$result]);
				}else{
					$sql="SELECT ID,PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=student_profile.PROFILE_ID)as Name,(SELECT MAX_MARK FROM e_setexamination WHERE CREATEEXAM_ID='$createExam' AND SUBJECT_ID='$subject' AND COURSEBATCH_ID='$batch')as MAX_MARK FROM student_profile WHERE COURSEBATCH_ID='$batch'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					return array(['status'=>'true','message'=>$result]);
				}
			}else{
				return array(['status'=>'false','message'=>'Exam Not found']);
			}
		}
		
		public function getTermExam($val){
			$sql="SELECT ID,NAME,SETTERM_ID,STARTDATE,ENDDATE FROM e_createexam WHERE SETTERM_ID='$val'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getExamAssessment($val){
			$sql="SELECT ID,NAME,CREATEEXAM_ID,MAX_MARK FROM e_setassessment WHERE CREATEEXAM_ID='$val'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		// public function getStuExamReport($val){
			// //$sql="SELECT ID,PROFILE_ID,SETEXAM_ID,MARK FROM e_marklist WHERE PROFILE_ID='$val'";
			// $sql="SELECT mark.ID,mark.PROFILE_ID,mark.SETEXAM_ID,term.NAME as TermName FROM e_marklist as mark INNER JOIN e_setexamination as exam ON mark.SETEXAM_ID=exam.ID INNER JOIN e_createexam as createE ON createE.ID=exam.CREATEEXAM_ID INNER JOIN e_setterm as term ON term.ID=createE.SETTERM_ID WHERE mark.PROFILE_ID='$val'";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// if($result){
				// foreach($result as $key => $value){
					// $setExamId=$value['SETEXAM_ID'];
					// $profileId=$value['PROFILE_ID'];
					// $sql="SELECT ID as EXAM_ID,NAME,STARTDATE,ENDDATE FROM e_createexam WHERE SETTERM_ID='$setExamId'";
					// $result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					// $result[$key]['createExams'] = $result1;
					// foreach($result1 as $key1 => $value){
						// $setExamId=$value['EXAM_ID'];
						// $sql="SELECT exam.ID,exam.SUBJECT_ID,(SELECT NAME FROM subject WHERE ID=exam.SUBJECT_ID)as SUBJECT_NAME,mark.MARK,grade.NAME as Grade FROM e_setexamination as exam INNER JOIN e_marklist as mark ON exam.ID=mark.SETEXAM_ID INNER JOIN (SELECT NAME,min(MINI_PERCENTAGE),MINI_PERCENTAGE FROM e_grade)as grade ON grade.MINI_PERCENTAGE >=mark.MARK WHERE mark.PROFILE_ID='$profileId' AND CREATEEXAM_ID='$setExamId'";
						// $result2 = $this->db->query($sql, $return_object = TRUE)->result_array();
						// $result[$key]['createExams'][$key1]['setExams'] = $result2;
					// }
				// }
				// return $result;
			// }
			
		// }
		
		public function getStuExamReport($val){
			// $sql="SELECT mark.ID,mark.PROFILE_ID,mark.SETEXAM_ID,term.NAME as TermName FROM e_marklist as mark INNER JOIN e_setexamination as exam ON mark.SETEXAM_ID=exam.ID INNER JOIN e_createexam as createE ON createE.ID=exam.CREATEEXAM_ID INNER JOIN e_setterm as term ON term.ID=createE.SETTERM_ID WHERE mark.PROFILE_ID='$val'";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// if($result){
				// foreach($result as $key => $value){
					// $setExamId=$value['SETEXAM_ID'];
					// //$sql="SELECT mark.PROFILE_ID,mark.SETEXAM_ID,mark.MARK,exam.SUBJECT_ID,(SELECT NAME FROM subject WHERE ID=exam.SUBJECT_ID)as SUBJECT_NAME,createE.NAME as ExamName,term.NAME as TermName FROM e_marklist as mark INNER JOIN e_setexamination as exam ON mark.SETEXAM_ID=exam.ID INNER JOIN e_createexam as createE ON createE.ID=exam.CREATEEXAM_ID INNER JOIN e_setterm as term ON term.ID=createE.SETTERM_ID WHERE mark.PROFILE_ID='$val' AND mark.SETEXAM_ID='$setExamId'";
					// $sql="SELECT mark.PROFILE_ID,mark.MARK,sExam.SUBJECT_ID,(SELECT NAME FROM subject WHERE ID=sExam.SUBJECT_ID)as SUBJECT_NAME,sExam.MAX_MARK FROM e_createexam as cExam INNER JOIN e_setexamination as sExam ON cExam.ID=sExam.CREATEEXAM_ID INNER JOIN e_marklist as mark ON sExam.ID=mark.SETEXAM_ID WHERE mark.PROFILE_ID='$val'";
					// $result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					// $result[$key]['createExams'] = $result1;
				// }
				// return $result;
			// }
			$sql="select e_marklist.SETEXAM_ID,e_setexamination.COURSEBATCH_ID,(SELECT NAME FROM course_batch WHERE ID=e_setexamination.COURSEBATCH_ID)as BATCH_NAME,(SELECT COURSE_ID FROM course_batch WHERE ID=e_setexamination.COURSEBATCH_ID)as COURSE_ID,(SELECT NAME FROM course WHERE ID=COURSE_ID)as COURSE_NAME,(SELECT DEPT_ID FROM course WHERE ID=COURSE_ID)as DEPT_ID,(SELECT NAME FROM department WHERE ID=DEPT_ID)as DEPT_NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=e_marklist.PROFILE_ID)as PROFILE_NAME,e_setexamination.CREATEEXAM_ID,e_createexam.SETTERM_ID,e_setterm.NAME AS TERM_NAME
				from e_marklist
				left join e_setexamination on e_marklist.SETEXAM_ID=e_setexamination.ID
				left join e_createexam on e_setexamination.CREATEEXAM_ID=e_createexam.ID
				left join e_setterm on e_createexam.SETTERM_ID=e_setterm.ID
				left join subject on e_setexamination.SUBJECT_ID=subject.ID
				where e_marklist.PROFILE_ID='$val' GROUP BY e_createexam.SETTERM_ID";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
			foreach($result as $key => $value){
				$TERMID=$value['SETTERM_ID'];
				$sql1="select * from e_createexam where SETTERM_ID='$TERMID'";
				$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
				$result[$key]['createExams']=$result1;
				foreach($result1 as $key1 => $value1){
					$examID=$value1['ID'];
					$sql2="select e_setexamination.ID,e_setexamination.COURSEBATCH_ID,e_setexamination.CREATEEXAM_ID,e_setexamination.SUBJECT_ID,e_setexamination.DATE,e_setexamination.MAX_MARK,subject.NAME AS SUBJECT_NAME,
						(select MARK from e_marklist where SETEXAM_ID=e_setexamination.ID AND PROFILE_ID='$val') AS MARKS,as_mark.MARK,as_mark.SUBJECT_ID as ASub,as_mark.PROFILE_ID
						from e_setexamination 
						left join subject on e_setexamination.SUBJECT_ID=subject.ID
						left join e_setassessment_mark as as_mark ON as_mark.SUBJECT_ID=e_setexamination.SUBJECT_ID AND as_mark.PROFILE_ID='$val'
						where e_setexamination.CREATEEXAM_ID='$examID'";
				    $result2 = $this->db->query($sql2, $return_object = TRUE)->result_array();
					
					$sql3="select assess.MAX_MARK,as_mark.MARK,as_mark.SUBJECT_ID,as_mark.PROFILE_ID from e_setassessment as assess right join e_setassessment_mark as as_mark ON as_mark.SETASSESSMENT_ID=assess.ID where as_mark.PROFILE_ID='$val' AND assess.CREATEEXAM_ID='$examID'";
					$result3 = $this->db->query($sql3, $return_object = TRUE)->result_array();
					$result[$key]['createExams'][$key1]['subject']=$result2;
					$result[$key]['createExams'][$key1]['assessment']=$result3;
					//$result[$key]['list'][$key1] = $this->db->query($sql2, $return_object = TRUE)->result_array();
					// $sql1="select * from e_createexam where SETTERM_ID='$TERMID'";
					// $result[$key]['list'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
				}
			}
			return $result;
			// print_r($result);exit;
		}
		
		public function getStuExamReport1($val){
			$sql="SELECT ID,NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile WHERE ID='$val')as PROFILE_NAME,(SELECT COURSEBATCH_ID FROM student_profile WHERE PROFILE_ID='$val')as batchId,(SELECT NAME FROM course_batch WHERE ID=batchId)as batch_name,(SELECT COURSE_ID FROM course_batch WHERE ID=batchId)as courseId,(SELECT NAME FROM course WHERE ID=courseId)as course_name,(SELECT DEPT_ID FROM course WHERE ID=courseId)as deptId,(SELECT NAME FROM department WHERE ID=deptId)as deptName FROM e_setterm";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key =>$value){
					$termId=$value['ID'];
					$sql1="SELECT ID,NAME FROM e_createexam WHERE SETTERM_ID='$termId'";
					$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
					$result[$key]['Exam']=$result1;
					foreach($result1 as $key1 =>$value){
						$createExamId=$value['ID'];
						//$sql2="SELECT ID,SUBJECT_ID,MAX_MARK,PASS_MARK,COURSEBATCH_ID FROM e_setexamination WHERE CREATEEXAM_ID='$createExamId'";
						//$sql2="SELECT sExam.ID,sExam.SUBJECT_ID,sExam.MAX_MARK,mark.MARK,mark.PROFILE_ID FROM e_setexamination as sExam right join e_marklist as mark ON mark.SETEXAM_ID=sExam.ID WHERE mark.PROFILE_ID='$val' AND sExam.CREATEEXAM_ID='$createExamId'";
						//$sql2="SELECT sExam.ID,sExam.SUBJECT_ID,sExam.MAX_MARK,mark.MARK,mark.PROFILE_ID,assessment.MAX_MARK as asMAX,assessment.MARK as asMark FROM e_setexamination as sExam right join e_marklist as mark ON mark.SETEXAM_ID=sExam.ID inner join (select assess.MAX_MARK,as_mark.MARK,as_mark.PROFILE_ID from e_setassessment as assess right join e_setassessment_mark as as_mark ON as_mark.SETASSESSMENT_ID=assess.ID where as_mark.PROFILE_ID='$val' AND assess.CREATEEXAM_ID='$createExamId' GROUP BY assess.CREATEEXAM_ID) as assessment WHERE mark.PROFILE_ID='$val' AND sExam.CREATEEXAM_ID='$createExamId' ";
						$sql2="SELECT sExam.ID,sExam.SUBJECT_ID,(SELECT NAME FROM subject WHERE ID=sExam.SUBJECT_ID)as SUBJECT_NAME,sExam.MAX_MARK,mark.MARK,mark.PROFILE_ID,CASE WHEN (select as_mark.MARK from e_setassessment as assess right join e_setassessment_mark as as_mark ON as_mark.SETASSESSMENT_ID=assess.ID WHERE assess.CREATEEXAM_ID=sExam.CREATEEXAM_ID AND as_mark.PROFILE_ID=mark.PROFILE_ID AND as_mark.SUBJECT_ID=sExam.SUBJECT_ID) THEN (select as_mark.MARK from e_setassessment as assess right join e_setassessment_mark as as_mark ON as_mark.SETASSESSMENT_ID=assess.ID WHERE assess.CREATEEXAM_ID=sExam.CREATEEXAM_ID AND as_mark.PROFILE_ID=mark.PROFILE_ID AND as_mark.SUBJECT_ID=sExam.SUBJECT_ID) ELSE 0 END as AssMark,CASE WHEN (select assess.MAX_MARK from e_setassessment as assess right join e_setassessment_mark as as_mark ON as_mark.SETASSESSMENT_ID=assess.ID WHERE assess.CREATEEXAM_ID=sExam.CREATEEXAM_ID AND as_mark.PROFILE_ID=mark.PROFILE_ID AND as_mark.SUBJECT_ID=sExam.SUBJECT_ID) THEN (select assess.MAX_MARK from e_setassessment as assess right join e_setassessment_mark as as_mark ON as_mark.SETASSESSMENT_ID=assess.ID WHERE assess.CREATEEXAM_ID=sExam.CREATEEXAM_ID AND as_mark.PROFILE_ID=mark.PROFILE_ID AND as_mark.SUBJECT_ID=sExam.SUBJECT_ID) ELSE 0 END as AssMax FROM e_setexamination as sExam right join e_marklist as mark ON mark.SETEXAM_ID=sExam.ID WHERE mark.PROFILE_ID=$val AND sExam.CREATEEXAM_ID='$createExamId'";
						$result2 = $this->db->query($sql2, $return_object = TRUE)->result_array();
						if($result2){
							$result[$key]['Exam'][$key1]['Subject']=$result2;
						}else{
							$result[$key]['Exam'][$key1]['Subject']=null;
						}
					}
				}
				return $result;
			}
		}
		
	}
?>
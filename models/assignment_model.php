<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class assignment_model extends CI_Model {

		// Assignment Detail

		public function assignmentDetails($value){
			$id=$value['id'];
	    	$sql="SELECT count(NAME) FROM assignment WHERE ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(NAME)']!=0){
				$data = array(
				   'NAME' => $value['name'],
				   'CONTENT' => $value['content'],
				   'DUE_DATE' => $value['due_date'],
				   'COURSE_ID' => $value['course_id'],
				   'BATCH_ID' => $value['batch_id'],
				   'SUBJECT_ID' => $value['subject_id'],
				   'UPD_USER_ID' => $value['userId']
				);
				$this->db->where('ID', $id);
				$this->db->update('assignment', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'ASSIGNMT_ID'=>$id);
			}else {
				$data = array(
				   'NAME' => $value['name'],
				   'CONTENT' => $value['content'],
				   'DUE_DATE' => $value['due_date'],
				   'COURSE_ID' => $value['course_id'],
				   'BATCH_ID' => $value['batch_id'],
				   'SUBJECT_ID' => $value['subject_id'],
				   'CRT_USER_ID' => $value['userId']
				);
				$this->db->insert('assignment', $data);
				$get_id=$this->db->insert_id();
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'ASSIGNMT_ID'=>$get_id);
			}
	    }

	    function getAllAssignment_details(){
	    	$headers = apache_request_headers();
			$access_token=$headers['access_token'];
			$sql="SELECT user_id,(SELECT USER_ROLE_ID FROM user WHERE USER_EMAIL=oauth_access_tokens.user_id) as Role,(SELECT USER_PROFILE_ID FROM user WHERE USER_EMAIL=oauth_access_tokens.user_id) as ProfileId FROM oauth_access_tokens WHERE access_token='$access_token'";
			$res = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($res){
				$roleId=$res[0]['Role'];
				$ProfileId=$res[0]['ProfileId'];
				if($roleId==2){
					$sql="SELECT ID,NAME,CONTENT,DATE_FORMAT(DUE_DATE, '%d-%b-%Y') as DUE_DATE,COURSE_ID,BATCH_ID,SUBJECT_ID,
					(SELECT NAME FROM course WHERE ID = COURSE_ID) as COURSE_NAME,
					(SELECT NAME FROM course_batch WHERE ID = BATCH_ID) as BATCH_NAME,
					(SELECT NAME FROM subject WHERE ID = SUBJECT_ID) as SUBJECT_NAME,COALESCE(UPD_USER_ID,CRT_USER_ID)as post_userId,
					(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=post_userId)as profileName,DATE_FORMAT(CRT_DT, '%d-%m-%Y') as POST_DATE,
					CASE WHEN (SELECT count(PROFILE_ID) FROM student_profile WHERE COURSEBATCH_ID=BATCH_ID) = (SELECT count(ID) FROM assignment_status WHERE ASSIGNMENT_ID=assignment.ID AND COURSEBATCH_ID=assignment.BATCH_ID) THEN 'Completed' ELSE 'Pending' END as STATUS
					 FROM assignment WHERE CRT_USER_ID='$ProfileId' ORDER BY `CRT_DT` DESC";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
				}else{
					$sql="SELECT ID,NAME,CONTENT,DATE_FORMAT(DUE_DATE, '%d-%b-%Y') as DUE_DATE,COURSE_ID,BATCH_ID,SUBJECT_ID,
					(SELECT NAME FROM course WHERE ID = COURSE_ID) as COURSE_NAME,
					(SELECT NAME FROM course_batch WHERE ID = BATCH_ID) as BATCH_NAME,
					(SELECT NAME FROM subject WHERE ID = SUBJECT_ID) as SUBJECT_NAME,COALESCE(UPD_USER_ID,CRT_USER_ID)as post_userId,
					(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=post_userId)as profileName,DATE_FORMAT(CRT_DT, '%d-%m-%Y') as POST_DATE,
					CASE WHEN (SELECT count(PROFILE_ID) FROM student_profile WHERE COURSEBATCH_ID=BATCH_ID) = (SELECT count(ID) FROM assignment_status WHERE ASSIGNMENT_ID=assignment.ID AND COURSEBATCH_ID=assignment.BATCH_ID) THEN 'Completed' ELSE 'Pending' END as STATUS
					 FROM assignment ORDER BY `CRT_DT` DESC";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
				}
			}
	    }

	    function getAssignment_details($id){
	    	$sql="SELECT * FROM assignment where ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }

	    function getAllstuAssignmentDetail($id,$roleId){
	    	if($roleId==3){
				$sql="SELECT COURSEBATCH_ID FROM student_profile WHERE PROFILE_ID='$id'";
				$res = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($res){
					$batchId=$res[0]['COURSEBATCH_ID'];
					$sql="SELECT ID,NAME,CONTENT,DATE_FORMAT(DUE_DATE, '%d-%b-%Y') as DUE_DATE,SUBJECT_ID,CRT_USER_ID,COALESCE(UPD_USER_ID,CRT_USER_ID)as post_userId,
					(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=post_userId)as profileName,
					(SELECT NAME FROM subject WHERE ID = SUBJECT_ID) as SUBJECT_NAME,DATE_FORMAT(CRT_DT, '%d-%m-%Y') as POST_DATE FROM assignment WHERE BATCH_ID='$batchId' ORDER BY `assignment`.`DUE_DATE` DESC";
					return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
				}
			}else if($roleId==2){
				$sql="SELECT ID,NAME,CONTENT,DATE_FORMAT(DUE_DATE, '%d-%b-%Y') as DUE_DATE,SUBJECT_ID,CRT_USER_ID,COALESCE(UPD_USER_ID,CRT_USER_ID)as post_userId,
				(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=post_userId)as profileName,
				(SELECT NAME FROM subject WHERE ID = SUBJECT_ID) as SUBJECT_NAME,DATE_FORMAT(CRT_DT, '%d-%m-%Y') as POST_DATE FROM assignment WHERE CRT_USER_ID='$id' ORDER BY `assignment`.`DUE_DATE` DESC";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}
	    }

	    function deleteAssignmentData($id){
	    	$sql="DELETE FROM assignment WHERE ID='$id'";
	    	$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
	    }

	    function getStuDetailBatchID($id,$assess){
	    	$sql="SELECT PROFILE_ID,COURSEBATCH_ID,ROLL_NO,
		    		(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile WHERE ID = PROFILE_ID) as STUDENT_NAME,
		    		(SELECT IMAGE1 FROM profile WHERE ID = PROFILE_ID) as IMAGE,
		    		(SELECT ADMISSION_NO FROM profile WHERE ID = PROFILE_ID) as ADMISSION_NUM FROM student_profile WHERE COURSEBATCH_ID = '$id' AND PROFILE_ID NOT IN(SELECT PROFILE_ID FROM assignment_status WHERE COURSEBATCH_ID = '$id' AND ASSIGNMENT_ID='$assess')";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }

	    // Assignment status marking

	    public function assignmentStatus($val){
	    	for ($i=0; $i <count($val['profileId']) ; $i++) { 
	    		$data = array(
	    			'ASSIGNMENT_ID' =>$val['assignment_id'],
	    			'PROFILE_ID' =>$val['profileId'][$i],
	    			'COURSEBATCH_ID' =>$val['batch_id'][$i],
	    			'STATUS' => 'Completed'
	    		);
	    		$this->db->insert('assignment_status', $data);
	    		//print_r($data);
	    	}
	    	//exit;
	    	return array('status'=>true, 'message'=>"Record Inserted Successfully");;
	    }
		
	}
?>
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class newseventsmodel extends CI_Model {
		
		public function getAllNewsEvents(){
			$sql="SELECT ID,TITLE,DESCRIPTION,STARTDATE,ENDDATE,STARTTIME,ENDTIME,SHOW_STATUS,CRT_DT FROM news_events";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key => $value){
					$newsId=$value['ID'];
					$sql="SELECT COURSE_ID FROM newsevents_courses WHERE NEWS_EVENTS_ID='$newsId'";
					$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					$result[$key]['course']=$result1;
				}
				return $result;
			}
		}
		
		public function getNewsEvents($id){
			$sql="SELECT ID,TITLE,DESCRIPTION,STARTDATE,ENDDATE,STARTTIME,ENDTIME,SHOW_STATUS,CRT_DT FROM news_events WHERE ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key => $value){
					$newsId=$value['ID'];
					$sql="SELECT COURSE_ID FROM newsevents_courses WHERE NEWS_EVENTS_ID='$newsId'";
					$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					$result[$key]['course']=$result1;
				}
				return $result;
			}
		}
		
		public function addNewsEvents($values){
			if($values['SHOW_STATUS']=='Y'){
				$data = array(
					'TITLE' => $values['TITLE'],
					'DESCRIPTION' => $values['DESCRIPTION'],
					'STARTDATE' => $values['STARTDATE'],
					'ENDDATE' => $values['ENDDATE'],
					'STARTTIME' => $values['STARTTIME'],
					'ENDTIME' => $values['ENDTIME'],
					'SHOW_STATUS' => $values['SHOW_STATUS'],
					'CRT_USER_ID' => $values['userProfileId']
				);
				$this->db->insert('news_events', $data);
				$getId= $this->db->insert_id();
				return $getId; 
			}else{
				$data = array(
					'TITLE' => $values['TITLE'],
					'DESCRIPTION' => $values['DESCRIPTION'],
					'STARTDATE' => $values['STARTDATE'],
					'ENDDATE' => $values['ENDDATE'],
					'STARTTIME' => $values['STARTTIME'],
					'ENDTIME' => $values['ENDTIME'],
					'SHOW_STATUS' => $values['SHOW_STATUS'],
					'CRT_USER_ID' => $values['userProfileId']
				);
				$this->db->insert('news_events', $data);
				$getId= $this->db->insert_id();
				if($getId){
					for($i=0;$i<count($values['COURSE_ID']);$i++){
						$data1 = array(
							'NEWS_EVENTS_ID' => $getId,
							'COURSE_ID' => $values['COURSE_ID'][$i]
						);
						$this->db->insert('newsevents_courses', $data1);
					}
					return $getId;
				}
			}
		}
		
		public function editNewsEvents($Id,$values){
			if($values['SHOW_STATUS']=='Y'){
				$data = array(
					'TITLE' => $values['TITLE'],
					'DESCRIPTION' => $values['DESCRIPTION'],
					'STARTDATE' => $values['STARTDATE'],
					'ENDDATE' => $values['ENDDATE'],
					'STARTTIME' => $values['STARTTIME'],
					'ENDTIME' => $values['ENDTIME'],
					'SHOW_STATUS' => $values['SHOW_STATUS'],
					'CRT_USER_ID' => $values['userProfileId']
				);
				$this->db->where('id', $Id);
				$this->db->update('news_events', $data);
				return true;
			}else{
				$data = array(
					'TITLE' => $values['TITLE'],
					'DESCRIPTION' => $values['DESCRIPTION'],
					'STARTDATE' => $values['STARTDATE'],
					'ENDDATE' => $values['ENDDATE'],
					'STARTTIME' => $values['STARTTIME'],
					'ENDTIME' => $values['ENDTIME'],
					'SHOW_STATUS' => $values['SHOW_STATUS'],
					'CRT_USER_ID' => $values['userProfileId']
				);
				$this->db->where('id', $Id);
				$this->db->update('news_events', $data);
				foreach($values['COURSE_ID'] as $value){
					$data1 = array(
						'COURSE_ID' => $value
					);
					$this->db->where('NEWS_EVENTS_ID', $Id);
					$this->db->update('newsevents_courses', $data);
				}
				return true;
			}
		}
		
		public function deleteNewsEvents($id){
			$sql="DELETE FROM news_events where ID='$id'";
			$sql1="SELECT * FROM newsevents_courses WHERE NEWS_EVENTS_ID='$id'";
			$res=$this->db->query($sql1, $return_object = TRUE)->result_array();
			if($res){
				$sql1="DELETE FROM newsevents_courses where NEWS_EVENTS_ID='$id'";
				$result1 = $this->db->query($sql1);
			}
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function getAllRepPostDetails(){
			$sql="SELECT ID,TITLE,REP_CATEGORY_ID,CONTENT,UPLOAD_FILE,CRT_DT,COALESCE(UPD_DT,CRT_DT)as postDate,COALESCE(UPD_USER_ID,CRT_USER_ID)as postUser,(SELECT NAME FROM repository_category WHERE ID = REP_CATEGORY_ID) AS CATEGORY_NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=postUser)as ProfileName,(SELECT NAME FROM course WHERE ID=COURSE_ID)as COURSE_NAME FROM repository_post";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getRepPostDetails($id){
			// $sql="SELECT ID,NAME,CATEGORY_ID,DEPT_ID,SUBJECT_ID,AUTHOR,	REGULATION,YEAROFPUBLISHED,ISBN,PUBLISHER,EDITION,PRICE,RACKNO,	C_QUANTITY,IMAGE, FROM l_book WHERE ID='$id'";
			$sql="SELECT ID,TITLE,REP_CATEGORY_ID,COURSE_ID,CONTENT,CRT_DT,COALESCE(UPD_DT,CRT_DT)as postDate,COALESCE(UPD_USER_ID,CRT_USER_ID)as postUser,UPLOAD_FILE, (SELECT NAME FROM repository_category WHERE ID = REP_CATEGORY_ID) AS CATEGORY_NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=postUser)as ProfileName,(SELECT NAME FROM course WHERE ID=COURSE_ID)as COURSE_NAME FROM repository_post WHERE ID='$id'";
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
					'REP_CATEGORY_ID' => $values['REP_CATEGORY_ID'],
					'CRT_USER_ID' => $values['userProfileId']
				);
				$this->db->insert('repository_post', $data);
				$getId= $this->db->insert_id();
				if($getId){
					return $getId;
				}
		}

		public function fileupload($file){
			// $uploaddir = 'application/uploads/';
			$uploaddir='uploads/';
			$uploadfile = $uploaddir . basename($file['name']);
			if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
				return $uploaddir.$file['name'];
			}
		}
		
		public function editRepPostDetails($Id,$values){
			$filename = $this->fileupload($values['UPLOAD_FILE']['file']);
			$data = array(
				'TITLE' => $values['TITLE'],
				'UPLOAD_FILE' => $filename,
				'CONTENT' => $values['CONTENT'],
				'COURSE_ID' => $values['COURSE_ID'],
				'REP_CATEGORY_ID' => $values['REP_CATEGORY_ID'],
				'UPD_USER_ID' => $values['userProfileId']
			);
			$this->db->where('id', $Id);
			$this->db->update('repository_post', $data);
			return array('status'=>true, 'message'=>"Book Details Updated Successfully",'BOOK_ID'=>$Id);
		}
		
		
		// Mobile app repository create/update
		
		public function addmRepPostDetails($values){
			$data = array(
				'TITLE' => $values['TITLE'],
				'UPLOAD_FILE' => $values['UPLOAD_FILE'],
				'CONTENT' => $values['CONTENT'],
				'COURSE_ID' => $values['COURSE_ID'],
				'REP_CATEGORY_ID' => $values['REP_CATEGORY_ID'],
				'CRT_USER_ID' => $values['userProfileId']
			);
			$this->db->insert('repository_post', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editmRepPostDetails($Id,$values){
			$data = array(
				'TITLE' => $values['TITLE'],
				'UPLOAD_FILE' => $values['UPLOAD_FILE'],
				'CONTENT' => $values['CONTENT'],
				'COURSE_ID' => $values['COURSE_ID'],
				'REP_CATEGORY_ID' => $values['REP_CATEGORY_ID'],
				'UPD_USER_ID' => $values['userProfileId']
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
		
		// Mobile Details 
		
		public function mGetAllRepPostDetails(){
			$sql="SELECT ID,TITLE,REP_CATEGORY_ID,CONTENT,UPLOAD_FILE,CRT_DT,COALESCE(UPD_DT,CRT_DT)as postDate,COALESCE(UPD_USER_ID,CRT_USER_ID)as postUser, (SELECT NAME FROM repository_category WHERE ID = REP_CATEGORY_ID) AS CATEGORY_NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=postUser)as ProfileName,COURSE_ID,(SELECT NAME FROM course WHERE ID=COURSE_ID)as COURSE_NAME FROM repository_post ORDER BY `repository_post`.`CRT_DT` DESC";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key => $value){
					 $path=$value['UPLOAD_FILE'];
					//$path='https://www.w3schools.com/css/trolltunga.jpg';
					$imgData = base64_encode(file_get_contents($path));
					$mimeType=mime_content_type($path);
					// Format the image SRC:  data:{mime};base64,{data};
					$src = 'data: '.$mimeType.';base64,'.$imgData;
					$result[$key]['image']=$src;
					//print_r($src);
					//exit;
				}
				return $result;
			}
		}
		
		public function mGetRepPostDetails($id){
			$sql="SELECT ID,TITLE,REP_CATEGORY_ID,CONTENT,UPLOAD_FILE,CRT_DT,COALESCE(UPD_DT,CRT_DT)as postDate,COALESCE(UPD_USER_ID,CRT_USER_ID)as postUser, (SELECT NAME FROM repository_category WHERE ID = REP_CATEGORY_ID) AS CATEGORY_NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=postUser)as ProfileName,(SELECT NAME FROM course WHERE ID=COURSE_ID)as COURSE_NAME FROM repository_post WHERE ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key => $value){
					$path=$value['UPLOAD_FILE'];
					$imgData = base64_encode(file_get_contents($path));
					$mimeType=mime_content_type($path);
					// Format the image SRC:  data:{mime};base64,{data};
					$src = 'data: '.$mimeType.';base64,'.$imgData;
					$result[$key]['image']=$src;
				}
				return $result;
			}
		}
		
		
		// Course based repository posts
		public function mGetRepCoursebased($id,$roleId){
			if($roleId==3){
				$sql="SELECT COURSEBATCH_ID,(SELECT COURSE_ID FROM course_batch WHERE ID=COURSEBATCH_ID) as COURSE_ID FROM student_profile WHERE PROFILE_ID='$id'";
				$res = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($res){
					$ress=$res[0]['COURSE_ID'];
					$sql="SELECT ID,TITLE,REP_CATEGORY_ID,CONTENT,UPLOAD_FILE,CRT_DT,COALESCE(UPD_DT,CRT_DT)as postDate,COALESCE(UPD_USER_ID,CRT_USER_ID)as postUser, (SELECT NAME FROM repository_category WHERE ID = REP_CATEGORY_ID) AS CATEGORY_NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=postUser)as ProfileName,(SELECT NAME FROM course WHERE ID=COURSE_ID)as COURSE_NAME FROM repository_post WHERE COURSE_ID='$ress'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					if($result){
						foreach($result as $key => $value){
							//$path='uploads/'.$value['UPLOAD_FILE']; 
							$path=$value['UPLOAD_FILE']; 
							// print_r($path);exit;
							$data = file_get_contents($path);
							//$imgData = base64_encode(file_get_contents($path));
							$mimeType=mime_content_type($value['UPLOAD_FILE']);
							// Format the image SRC:  data:{mime};base64,{data};
							$src = 'data: '.$mimeType.';base64,'.base64_encode($data);
							$result[$key]['image']=$src;
						}
						return $result;
					}
				}
			}else if($roleId==2){
				$sql="SELECT ID,TITLE,REP_CATEGORY_ID,CONTENT,UPLOAD_FILE,CRT_DT,COALESCE(UPD_DT,CRT_DT)as postDate,COALESCE(UPD_USER_ID,CRT_USER_ID)as postUser, (SELECT NAME FROM repository_category WHERE ID = REP_CATEGORY_ID) AS CATEGORY_NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=postUser)as ProfileName,(SELECT NAME FROM course WHERE ID=COURSE_ID)as COURSE_NAME,COURSE_ID FROM repository_post WHERE CRT_USER_ID IN ($id)";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					foreach($result as $key => $value){
						//$path='uploads/'.$value['UPLOAD_FILE']; 
						$path=$value['UPLOAD_FILE']; 
						// print_r($path);exit;
						$data = file_get_contents($path);
						//$imgData = base64_encode(file_get_contents($path));
						$mimeType=mime_content_type($value['UPLOAD_FILE']);
						// Format the image SRC:  data:{mime};base64,{data};
						$src = 'data: '.$mimeType.';base64,'.base64_encode($data);
						$result[$key]['image']=$src;
					}
					return $result;
				}
			}else{
				$sql="SELECT ID,TITLE,REP_CATEGORY_ID,CONTENT,UPLOAD_FILE,CRT_DT,COALESCE(UPD_DT,CRT_DT)as postDate,COALESCE(UPD_USER_ID,CRT_USER_ID)as postUser, (SELECT NAME FROM repository_category WHERE ID = REP_CATEGORY_ID) AS CATEGORY_NAME,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=postUser)as ProfileName,(SELECT NAME FROM course WHERE ID=COURSE_ID)as COURSE_NAME FROM repository_post";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					foreach($result as $key => $value){
						$path=$value['UPLOAD_FILE'];
						$imgData = base64_encode(file_get_contents($path));
						$mimeType=mime_content_type($path);
						// Format the image SRC:  data:{mime};base64,{data};
						$src = 'data: '.$mimeType.';base64,'.$imgData;
						$result[$key]['image']=$src;
						//print_r($src);
						//exit;
					}
					return $result;
				}
			}
			
		}
		
	}
?>
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class profilemodel extends CI_Model {

		// Add Admission Details
		public function addAdmission($values){
			$data = array(
			   'ADMISSION_NO' => $values['ADMISSION_NO'],
			   'ADMISSION_DATE' => $values['ADMISSION_DATE'],
			   'FIRSTNAME' => $values['FIRSTNAME'],
			   'LASTNAME' => $values['LASTNAME'],
			   'GENDER' => $values['GENDER'],
			   'IMAGE1' => $values['IMAGE1'],
			   'DOB' => $values['DOB'],
			   'NATIONALITY' => $values['NATIONALITY'],
			   'MOTHER_TONGUE' => $values['MOTHER_TONGUE'],
			   'RELIGION' => $values['RELIGION']
			);
			$this->db->insert('profile', $data); 
			$profile_id= $this->db->insert_id();
			if(!empty($profile_id)){
				$data1 = array(
					'PROFILE_ID' => $profile_id,
					'COURSEBATCH_ID' => $values['COURSEBATCH_ID'],
					'ROLL_NO' => $values['ROLL_NO']
				);
				$this->db->insert('student_profile', $data1);
				$student_profile_id= $this->db->insert_id();
				// if(!empty($student_profile_id)){
					// $data2 = array(
						// 'STU_PROF_ID' => $student_profile_id
					// );
					// $this->db->insert('student_relation', $data2);
				// }
				//return $profile_id;
				return array(['profile_id' => $profile_id,'stu_profileId'=> $student_profile_id]);
			}
	    }
		
		public function editAdmission($id,$values){
			$data = array(
			   'ADMISSION_NO' => $values['ADMISSION_NO'],
			   'ADMISSION_DATE' => $values['ADMISSION_DATE'],
			   'FIRSTNAME' => $values['FIRSTNAME'],
			   'LASTNAME' => $values['LASTNAME'],
			   'GENDER' => $values['GENDER'],
			   'IMAGE1' => $values['IMAGE1'],
			   'DOB' => $values['DOB'],
			   'NATIONALITY' => $values['NATIONALITY'],
			   'MOTHER_TONGUE' => $values['MOTHER_TONGUE'],
			   'RELIGION' => $values['RELIGION']
			);
			$this->db->where('ID', $id);
			$this->db->update('profile', $data);
			
			$data1 = array(
				'COURSEBATCH_ID' => $values['COURSEBATCH_ID'],
				'ROLL_NO' => $values['ROLL_NO']
			);
			$this->db->where('PROFILE_ID', $id);
			$this->db->update('student_profile', $data1);
			
			$sql="SELECT * FROM student_profile WHERE PROFILE_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$student_profile_id=$result[0]['ID'];
			}
			//return $id;
			return array(['profile_id' => $id,'stu_profileId'=> $student_profile_id]);
		}
		
		
		// Previous Academics Details
	
		public function academicsDetails($id,$values){
			//print_r($id);
			//print_r($values);exit;
			$preEdu_id=[];
			foreach($values['previous'] as $value){
				if($value['preEdu_id']){
					$data = array(
						'INSTITUTE' => $value['institute'],
						'LEVEL' => $value['course_name'],
						'YEAR_COMPLETION' => $value['completion'],
						'TOTAL_GRADE' => $value['total_mark'],
						'PROFILE_ID' => $id,
					);
					$this->db->where('ID', $value['preEdu_id']);
					$this->db->update('previous_education', $data);
					$pre_id= $value['preEdu_id'];
					array_push($preEdu_id,$pre_id);
				}else{
					$data = array(
						'INSTITUTE' => $value['institute'],
						'LEVEL' => $value['course_name'],
						'YEAR_COMPLETION' => $value['completion'],
						'TOTAL_GRADE' => $value['total_mark'],
						'PROFILE_ID' => $id,
					);
					$this->db->insert('previous_education', $data);
					$pre_id= $this->db->insert_id();
					array_push($preEdu_id,$pre_id);
				}
			}
			
			$data1 = array(
				'STUDENTCATEGORY_ID' => $values['stu_category'],
				'STUDENT_TYPE' => $values['stu_type'],
				'STUDENT_LIVES' => $values['student_lives']
			);
			$this->db->where('PROFILE_ID', $id);
			$this->db->update('student_profile', $data1);
			
			$sql="SELECT ID FROM student_profile where PROFILE_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$stuProId=$result[0]['ID'];
			
			$sql="select * from student_relation where RELATION_TYPE='Sibling' AND STU_PROF_ID='$stuProId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if(!empty($result)){
				$stuRelId=$result[0]['ID'];
				$data3 = array(
					'PROF_ID' => $values['sibling'],
				);
				$this->db->where('ID', $stuRelId);
				$this->db->update('student_relation', $data3);
			}else{
				$data4 = array(
					'PROF_ID' => $values['sibling'],
					'RELATION_TYPE' => 'Sibling',
					'STU_PROF_ID' => $stuProId
				);
				$this->db->insert('student_relation', $data4);
			}
			
			
			$data2 = array(
				'BLOOD_GROUP' => $values['bloodGroup'],
				'BIRTHPLACE' => $values['birthplace']
			);
			$this->db->where('ID', $id);
			$this->db->update('profile', $data2);
			
			return $preEdu_id;
		}
		
		
		// Contact Details
	
		public function contactDetails($id,$values){
			// print_r($id);
			// print_r($values);exit;
			$locationId=[];
			
			$sql="SELECT EMAIL,EMAIL_VERIFIED,EMAIL_STATUS FROM profile where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$email=$result[0]['EMAIL'];
				if($email==$values['EMAIL']){
					$emailVerify=$result[0]['EMAIL_VERIFIED'];
					$emailStatus=$result[0]['EMAIL_STATUS'];
				}else{
					$emailVerify='N';
					$emailStatus='N';
				}
			}
			
			if($values['sameAddress']=="Yes"){
				if($values['locId']){
					$data = array(
					   'ADDRESS' => $values['ADDRESS'],
					   'CITY' => $values['CITY'],
					   'STATE' => $values['STATE'],
					   'COUNTRY' => $values['COUNTRY'],
					   'ZIP_CODE' => $values['ZIP_CODE']
					);
					$this->db->where('ID', $values['locId']);
					$this->db->update('location', $data);
					array_push($locationId,$values['locId']);
					$data1 = array(
						'EMAIL' => $values['EMAIL'],
						'EMAIL_VERIFIED' => $emailVerify,
						'EMAIL_STATUS' => $emailStatus,
						'PHONE_NO_1' => $values['PHONE_NO_1'],
						'PHONE_NO_2' => $values['PHONE_NO_2'],
						'LOCATION_ID' => $values['locId'],
						'FACEBOOK_LINK' => $values['FACEBOOK_LINK'],
						'GOOGLE_LINK' => $values['GOOGLE_LINK'],
						'LINKEDIN_LINK' => $values['LINKEDIN_LINK']
					);
					$this->db->where('ID', $id);
					$this->db->update('profile', $data1);
					
					$data2 = array(
						'MAILLING_ADDRESS_ID' => $values['locId']
					);
					$this->db->where('PROFILE_ID', $id);
					$this->db->update('student_profile', $data2);
					
					return $locationId;
				}else{
					$data = array(
					   'ADDRESS' => $values['ADDRESS'],
					   'CITY' => $values['CITY'],
					   'STATE' => $values['STATE'],
					   'COUNTRY' => $values['COUNTRY'],
					   'ZIP_CODE' => $values['ZIP_CODE']
					);
					$this->db->insert('location', $data); 
					$location_id= $this->db->insert_id();
					array_push($locationId,$location_id);
					if(!empty($location_id)){
						$data1 = array(
							'EMAIL' => $values['EMAIL'],
							'EMAIL_VERIFIED' => $emailVerify,
							'EMAIL_STATUS' => $emailStatus,
							'PHONE_NO_1' => $values['PHONE_NO_1'],
							'PHONE_NO_2' => $values['PHONE_NO_2'],
							'LOCATION_ID' => $location_id,
							'FACEBOOK_LINK' => $values['FACEBOOK_LINK'],
							'GOOGLE_LINK' => $values['GOOGLE_LINK'],
							'LINKEDIN_LINK' => $values['LINKEDIN_LINK']
						);
						$this->db->where('ID', $id);
						$this->db->update('profile', $data1);
						
						$data2 = array(
							'MAILLING_ADDRESS_ID' => $location_id
						);
						$this->db->where('PROFILE_ID', $id);
						$this->db->update('student_profile', $data2);
					}
					return $locationId;
				}
			}else{
				if($values['locId']){
					$data = array(
					   'ADDRESS' => $values['ADDRESS'],
					   'CITY' => $values['CITY'],
					   'STATE' => $values['STATE'],
					   'COUNTRY' => $values['COUNTRY'],
					   'ZIP_CODE' => $values['ZIP_CODE']
					);
					$this->db->where('ID', $values['locId']);
					$this->db->update('location', $data);
					array_push($locationId,$values['locId']);
					
					$data1 = array(
						'EMAIL' => $values['EMAIL'],
						'EMAIL_VERIFIED' => $emailVerify,
						'EMAIL_STATUS' => $emailStatus,
						'PHONE_NO_1' => $values['PHONE_NO_1'],
						'PHONE_NO_2' => $values['PHONE_NO_2'],
						'FACEBOOK_LINK' => $values['FACEBOOK_LINK'],
						'GOOGLE_LINK' => $values['GOOGLE_LINK'],
						'LINKEDIN_LINK' => $values['LINKEDIN_LINK']
					);
					$this->db->where('ID', $id);
					$this->db->update('profile', $data1);
					
					$data2 = array(
						'MAILLING_ADDRESS_ID' => $values['locId']
					);
					$this->db->where('PROFILE_ID', $id);
					$this->db->update('student_profile', $data2);
				}else{
					$data = array(
					   'ADDRESS' => $values['ADDRESS'],
					   'CITY' => $values['CITY'],
					   'STATE' => $values['STATE'],
					   'COUNTRY' => $values['COUNTRY'],
					   'ZIP_CODE' => $values['ZIP_CODE']
					);
					$this->db->insert('location', $data); 
					$location_id1= $this->db->insert_id();
					array_push($locationId,$location_id1);
					if(!empty($location_id1)){
						$data1 = array(
							'EMAIL' => $values['EMAIL'],
							'EMAIL_VERIFIED' => $emailVerify,
							'EMAIL_STATUS' => $emailStatus,
							'PHONE_NO_1' => $values['PHONE_NO_1'],
							'PHONE_NO_2' => $values['PHONE_NO_2'],
							'FACEBOOK_LINK' => $values['FACEBOOK_LINK'],
							'GOOGLE_LINK' => $values['GOOGLE_LINK'],
							'LINKEDIN_LINK' => $values['LINKEDIN_LINK']
						);
						$this->db->where('ID', $id);
						$this->db->update('profile', $data1);
						
						$data2 = array(
							'MAILLING_ADDRESS_ID' => $location_id1
						);
						$this->db->where('PROFILE_ID', $id);
						$this->db->update('student_profile', $data2);
					}
				}
				
				if($values['locId1']){
					$data1 = array(
					   'ADDRESS' => $values['ADDRESS1'],
					   'CITY' => $values['CITY1'],
					   'STATE' => $values['STATE1'],
					   'COUNTRY' => $values['COUNTRY1'],
					   'ZIP_CODE' => $values['ZIP_CODE1']
					);
					$this->db->where('ID', $values['locId1']);
					$this->db->update('location', $data1);
					array_push($locationId,$values['locId1']);
					
					$data2 = array(
						'EMAIL' => $values['EMAIL'],
						'EMAIL_VERIFIED' => $emailVerify,
						'EMAIL_STATUS' => $emailStatus,
						'PHONE_NO_1' => $values['PHONE_NO_1'],
						'PHONE_NO_2' => $values['PHONE_NO_2'],
						'LOCATION_ID' => $values['locId1'],
						'FACEBOOK_LINK' => $values['FACEBOOK_LINK'],
						'GOOGLE_LINK' => $values['GOOGLE_LINK'],
						'LINKEDIN_LINK' => $values['LINKEDIN_LINK']
					);
					$this->db->where('ID', $id);
					$this->db->update('profile', $data2);
				}else{
					$data1 = array(
					   'ADDRESS' => $values['ADDRESS1'],
					   'CITY' => $values['CITY1'],
					   'STATE' => $values['STATE1'],
					   'COUNTRY' => $values['COUNTRY1'],
					   'ZIP_CODE' => $values['ZIP_CODE1']
					);
					$this->db->insert('location', $data1); 
					$location_id2= $this->db->insert_id();
					array_push($locationId,$location_id2);
					if(!empty($location_id2)){
						$data2 = array(
							'EMAIL' => $values['EMAIL'],
							'EMAIL_VERIFIED' => $emailVerify,
							'EMAIL_STATUS' => $emailStatus,
							'PHONE_NO_1' => $values['PHONE_NO_1'],
							'PHONE_NO_2' => $values['PHONE_NO_2'],
							'LOCATION_ID' => $location_id2,
							'FACEBOOK_LINK' => $values['FACEBOOK_LINK'],
							'GOOGLE_LINK' => $values['GOOGLE_LINK'],
							'LINKEDIN_LINK' => $values['LINKEDIN_LINK']
						);
						$this->db->where('ID', $id);
						$this->db->update('profile', $data2);
					}
				}
				
				return $locationId;
			}
			
		}
		
		// Parents Profile
		public function parentsProfile($id,$values){
			//print_r($id);
			//print_r($values);exit;
			// $sql="SELECT ID FROM student_profile where PROFILE_ID='$id'";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// $sProId=$result[0]['ID'];
			// $sql="SELECT PROF_ID FROM student_relation where STU_PROF_ID='$sProId'";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			
			if($values['father']['relationId']!=0){
				$frel_id=$values['father']['relationId'];
				$sql="SELECT LOCATION_ID FROM profile where ID='$frel_id'";
				$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
				//print_r($result1);exit;
				if(isset($values['father']['country'])){
					$country=$values['father']['country'];
				}else{
					$country='';
				}
				$lId=$result1[0]['LOCATION_ID'];
				$data = array(
				   'ADDRESS' => $values['father']['pr_address'],
				   'CITY' => $values['father']['pr_city'],
				   'STATE' => $values['father']['pr_state'],
				   'COUNTRY' => $country,
				   'ZIP_CODE' => $values['father']['pr_pincode']
				);
				$this->db->where('ID', $lId);
				$this->db->update('location', $data);
				
				$data1 = array(
					'FIRSTNAME' => $values['father']['p_first_name'],
					'LASTNAME' => $values['father']['p_last_name'],
					'DOB' => $values['father']['p_dob'],
					'PHONE_NO_1' => $values['father']['p_phone'],
					'PHONE_NO_2' => $values['father']['p_mobile_no'],
					'EMAIL' => $values['father']['p_email'],
					'FACEBOOK_LINK' => $values['father']['facebook'],
					'GOOGLE_LINK' => $values['father']['google'],
					'LINKEDIN_LINK' => $values['father']['linkedin']
				);
				$this->db->where('ID', $frel_id);
				$this->db->update('profile', $data1);
				
				$data2 = array (
					'OCCUPATION' => $values['father']['occupation'],
					'INCOME' => $values['father']['p_income'],
				);
				$this->db->where('PROFILE_ID', $frel_id);
				$this->db->update('profile_extra', $data2);
				
				$data3 = array (
					'STU_PROF_ID' => $values['stuPro_id'],
					'EDUCATION' => $values['father']['p_education'],
					'RELATION_TYPE' => $values['father']['first_relation'],
					'AVAILABLE' => $values['father']['availabe']
				);
				$this->db->where('PROF_ID', $frel_id);
				$this->db->update('student_relation', $data3);
			}else{
				if(isset($values['father']['country'])){
					$country=$values['father']['country'];
				}else{
					$country='';
				}
				$data = array(
				   'ADDRESS' => $values['father']['pr_address'],
				   'CITY' => $values['father']['pr_city'],
				   'STATE' => $values['father']['pr_state'],
				   'COUNTRY' => $country,
				   'ZIP_CODE' => $values['father']['pr_pincode']
				);
				$this->db->insert('location', $data);
				$location_id= $this->db->insert_id();
				if(!empty($location_id)){
					$data1 = array(
						'FIRSTNAME' => $values['father']['p_first_name'],
						'LASTNAME' => $values['father']['p_last_name'],
						'DOB' => $values['father']['p_dob'],
						'PHONE_NO_1' => $values['father']['p_phone'],
						'PHONE_NO_2' => $values['father']['p_mobile_no'],
						'EMAIL' => $values['father']['p_email'],
						'LOCATION_ID' => $location_id,
						'FACEBOOK_LINK' => $values['father']['facebook'],
						'GOOGLE_LINK' => $values['father']['google'],
						'LINKEDIN_LINK' => $values['father']['linkedin']
					);
					$this->db->insert('profile', $data1);
					$fprofile_id= $this->db->insert_id();
					if(!empty($fprofile_id)){
						$data2 = array (
							'PROFILE_ID' => $fprofile_id,
							'OCCUPATION' => $values['father']['occupation'],
							'INCOME' => $values['father']['p_income'],
						);
						$this->db->insert('profile_extra', $data2);
						
						$data3 = array (
							'PROF_ID' => $fprofile_id,
							'STU_PROF_ID' => $values['stuPro_id'],
							'EDUCATION' => $values['father']['p_education'],
							'RELATION_TYPE' => $values['father']['first_relation'],
							'AVAILABLE' => $values['father']['availabe']
						);
						//$this->db->where('STU_PROF_ID', $values['stuPro_id']);
						//$this->db->update('student_relation', $data3);
						$this->db->insert('student_relation', $data3);
						$frel_id= $this->db->insert_id();
					}
				}
			}
			
			// Mother Details
			
			if($values['mother']['relationId']!=0){
				$mrel_id=$values['mother']['relationId'];
				$sql="SELECT LOCATION_ID FROM profile where ID='$mrel_id'";
				$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
				//print_r($result1);exit;
				if(isset($values['mother']['country'])){
					$country=$values['mother']['country'];
				}else{
					$country='';
				}
				$lId=$result1[0]['LOCATION_ID'];
				$data = array(
				   'ADDRESS' => $values['mother']['pr_address'],
				   'CITY' => $values['mother']['pr_city'],
				   'STATE' => $values['mother']['pr_state'],
				   'COUNTRY' => $country,
				   'ZIP_CODE' => $values['mother']['pr_pincode']
				);
				$this->db->where('ID', $lId);
				$this->db->update('location', $data);
				
				$data1 = array(
					'FIRSTNAME' => $values['mother']['p_first_name'],
					'LASTNAME' => $values['mother']['p_last_name'],
					'DOB' => $values['mother']['p_dob'],
					'PHONE_NO_1' => $values['mother']['p_phone'],
					'PHONE_NO_2' => $values['mother']['p_mobile_no'],
					'EMAIL' => $values['mother']['p_email'],
					'FACEBOOK_LINK' => $values['mother']['facebook'],
					'GOOGLE_LINK' => $values['mother']['google'],
					'LINKEDIN_LINK' => $values['mother']['linkedin']
				);
				$this->db->where('ID', $mrel_id);
				$this->db->update('profile', $data1);
				
				$data2 = array (
					'OCCUPATION' => $values['mother']['occupation'],
					'INCOME' => $values['mother']['p_income'],
				);
				$this->db->where('PROFILE_ID', $mrel_id);
				$this->db->update('profile_extra', $data2);
				
				$data3 = array (
					'STU_PROF_ID' => $values['stuPro_id'],
					'EDUCATION' => $values['mother']['p_education'],
					'RELATION_TYPE' => $values['mother']['second_relation'],
					'AVAILABLE' => $values['mother']['availabe']
				);
				$this->db->where('PROF_ID', $mrel_id);
				$this->db->update('student_relation', $data3);
			}else{
				if(isset($values['mother']['country'])){
					$country=$values['mother']['country'];
				}else{
					$country='';
				}
				$data4 = array(
				   'ADDRESS' => $values['mother']['pr_address'],
				   'CITY' => $values['mother']['pr_city'],
				   'STATE' => $values['mother']['pr_state'],
				   'COUNTRY' => $country,
				   'ZIP_CODE' => $values['mother']['pr_pincode']
				);
				$this->db->insert('location', $data4);
				$location_id1= $this->db->insert_id();
				if(!empty($location_id1)){
					$data5 = array(
						'FIRSTNAME' => $values['mother']['p_first_name'],
						'LASTNAME' => $values['mother']['p_last_name'],
						'DOB' => $values['mother']['p_dob'],
						'PHONE_NO_1' => $values['mother']['p_phone'],
						'PHONE_NO_2' => $values['mother']['p_mobile_no'],
						'EMAIL' => $values['mother']['p_email'],
						'LOCATION_ID' => $location_id1,
						'FACEBOOK_LINK' => $values['mother']['facebook'],
						'GOOGLE_LINK' => $values['mother']['google'],
						'LINKEDIN_LINK' => $values['mother']['linkedin']
					);
					$this->db->insert('profile', $data5);
					$mprofile_id= $this->db->insert_id();
					if(!empty($mprofile_id)){
						$data6 = array (
							'PROFILE_ID' => $mprofile_id,
							'OCCUPATION' => $values['mother']['occupation'],
							'INCOME' => $values['mother']['p_income'],
						);
						$this->db->insert('profile_extra', $data6);
						
						$data7 = array (
							'PROF_ID' => $mprofile_id,
							'STU_PROF_ID' => $values['stuPro_id'],
							'EDUCATION' => $values['mother']['p_education'],
							'RELATION_TYPE' => $values['mother']['second_relation'],
							'AVAILABLE' => $values['mother']['availabe']
						);
						// $this->db->where('STU_PROF_ID', $values['stuPro_id']);
						// $this->db->update('student_relation', $data7);
						$this->db->insert('student_relation', $data7);
						$mrel_id= $this->db->insert_id();
					}
				}
			}
			
			if($values['guardian']['relationId']!=0){
				$grel_id=$values['guardian']['relationId'];
				$sql="SELECT LOCATION_ID FROM profile where ID='$grel_id'";
				$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
				$lId=$result1[0]['LOCATION_ID'];
				$data = array(
				   'ADDRESS' => $values['guardian']['pr_address'],
				   'CITY' => $values['guardian']['pr_city'],
				   'STATE' => $values['guardian']['pr_state'],
				   'COUNTRY' => $values['guardian']['country'],
				   'ZIP_CODE' => $values['guardian']['pr_pincode']
				);
				$this->db->where('ID', $lId);
				$this->db->update('location', $data);
				
				$data1 = array(
					'FIRSTNAME' => $values['guardian']['p_first_name'],
					'LASTNAME' => $values['guardian']['p_last_name'],
					'DOB' => $values['guardian']['p_dob'],
					'PHONE_NO_1' => $values['guardian']['p_phone'],
					'PHONE_NO_2' => $values['guardian']['p_mobile_no'],
					'EMAIL' => $values['guardian']['p_email'],
					'FACEBOOK_LINK' => $values['guardian']['facebook'],
					'GOOGLE_LINK' => $values['guardian']['google'],
					'LINKEDIN_LINK' => $values['guardian']['linkedin']
				);
				$this->db->where('ID', $grel_id);
				$this->db->update('profile', $data1);
				
				$data2 = array (
					'OCCUPATION' => $values['guardian']['occupation'],
					'INCOME' => $values['guardian']['p_income'],
				);
				$this->db->where('PROFILE_ID', $grel_id);
				$this->db->update('profile_extra', $data2);
				
				$data3 = array (
					'STU_PROF_ID' => $values['stuPro_id'],
					'EDUCATION' => $values['guardian']['p_education'],
					'RELATION_TYPE' => $values['guardian']['third_relation'],
					'AVAILABLE' => $values['guardian']['availabe']
				);
				$this->db->where('PROF_ID', $grel_id);
				$this->db->update('student_relation', $data3);
			}else{
				if($values['guardian']['p_first_name']){
					$data8 = array(
					   'ADDRESS' => $values['guardian']['pr_address'],
					   'CITY' => $values['guardian']['pr_city'],
					   'STATE' => $values['guardian']['pr_state'],
					   'COUNTRY' => $values['guardian']['country'],
					   'ZIP_CODE' => $values['guardian']['pr_pincode']
					);
					$this->db->insert('location', $data8);
					$location_id2= $this->db->insert_id();
					if(!empty($location_id2)){
						$data9 = array(
							'FIRSTNAME' => $values['guardian']['p_first_name'],
							'LASTNAME' => $values['guardian']['p_last_name'],
							'DOB' => $values['guardian']['p_dob'],
							'PHONE_NO_1' => $values['guardian']['p_phone'],
							'PHONE_NO_2' => $values['guardian']['p_mobile_no'],
							'EMAIL' => $values['guardian']['p_email'],
							'LOCATION_ID' => $location_id2,
							'FACEBOOK_LINK' => $values['guardian']['facebook'],
							'GOOGLE_LINK' => $values['guardian']['google'],
							'LINKEDIN_LINK' => $values['guardian']['linkedin']
						);
						$this->db->insert('profile', $data9);
						$gprofile_id= $this->db->insert_id();
						if(!empty($gprofile_id)){
							$gdata = array (
								'PROFILE_ID' => $gprofile_id,
								'OCCUPATION' => $values['guardian']['occupation'],
								'INCOME' => $values['guardian']['p_income'],
							);
							$this->db->insert('profile_extra', $gdata);
							
							$gdata1 = array (
								'PROF_ID' => $gprofile_id,
								'STU_PROF_ID' => $values['stuPro_id'],
								'EDUCATION' => $values['guardian']['p_education'],
								'RELATION_TYPE' => $values['guardian']['third_relation']
							);
							// $this->db->where('STU_PROF_ID', $values['stuPro_id']);
							// $this->db->update('student_relation', $gdata1);
							$this->db->insert('student_relation', $gdata1);
							$grel_id= $this->db->insert_id();
						}
					}
				}else{
						$grel_id='';
				}
			}
			

			return array(['frelation_id' => $frel_id,'mrelation_id'=> $mrel_id,'grelation_id' => $grel_id]);
		}
	
		// Profile details
		public function addProfileDetails($values){
			$data = array(
			   'ADMISSION_NO' => $values['ADMISSION_NO'],
			   'ADMISSION_DATE' => $values['ADMISSION_DATE'],
			   'FIRSTNAME' => $values['FIRSTNAME'],
			   'LASTNAME' => $values['LASTNAME'],
			   'GENDER' => $values['GENDER'],
			   'IMAGE1' => $values['IMAGE1'],
			   'DOB' => $values['DOB'],
			   'NATIONALITY' => $values['NATIONALITY'],
			   'MOTHER_TONGUE' => $values['MOTHER_TONGUE'],
			   'RELIGION' => $values['RELIGION']
			);
			$this->db->insert('profile', $data); 
			$profile_id= $this->db->insert_id();
			if(!empty($profile_id)){
				$data1 = array(
					'PROFILE_ID' => $profile_id,
					'COURSEBATCH_ID' => $values['COURSEBATCH_ID'],
					'ROLL_NO' => $values['ROLL_NO']
				);
				$this->db->insert('student_profile', $data1);
				$student_profile_id= $this->db->insert_id();
				if(!empty($student_profile_id)){
					$data2 = array(
						'STU_PROF_ID' => $student_profile_id
					);
					$this->db->insert('student_relation', $data2);
				}
				return $profile_id;
			}
	    }
		
		public function editProfileDetails($id,$values){
			$sql="SELECT LOCATION_ID FROM profile where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['LOCATION_ID']){
				// location updates
				$data = array(
				   'ADDRESS' => $values['ADDRESS'],
				   'CITY' => $values['CITY'],
				   'STATE' => $values['STATE'],
				   'COUNTRY' => $values['COUNTRY'],
				   'ZIP_CODE' => $values['ZIP_CODE']
				);
				$this->db->where('ID', $result[0]['LOCATION_ID']);
				$this->db->update('location', $data);
				
				// Profile updates
				$data1 = array(
					'ADMISSION_NO' => $values['ADMISSION_NO'],
					'ADMISSION_DATE' => $values['ADMISSION_DATE'],
					'FIRSTNAME' => $values['FIRSTNAME'],
					'LASTNAME' => $values['LASTNAME'],
					'GENDER' => $values['GENDER'],
					'IMAGE1' => $values['IMAGE1'],
					'DOB' => $values['DOB'],
					'NATIONALITY' => $values['NATIONALITY'],
					'MOTHER_TONGUE' => $values['MOTHER_TONGUE'],
					'RELIGION' => $values['RELIGION'],
					'EMAIL' => $values['EMAIL'],
					'PHONE_NO_1' => $values['PHONE_NO_1'],
					'PHONE_NO_2' => $values['PHONE_NO_2'],
					'LOCATION_ID' => $result[0]['LOCATION_ID'],
					'BLOOD_GROUP' => $values['BLOOD_GROUP'],
					'BIRTHPLACE' => $values['BIRTHPLACE'],
					'FACEBOOK_LINK' => $values['FACEBOOK_LINK'],
					'GOOGLE_LINK' => $values['GOOGLE_LINK'],
					'LINKEDIN_LINK' => $values['LINKEDIN_LINK']
					
				);
				$this->db->where('ID', $id);
				$this->db->update('profile', $data1);
				
				// previous education add and updates
				
				$sql="SELECT PREVIOUSEDUCATION_ID FROM student_profile where PROFILE_ID='$id'";
				$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result1[0]['PREVIOUSEDUCATION_ID']){
					$data = array(
						'INSTITUTE' => $values['INSTITUTE'],
						'LEVEL' => $values['LEVEL'],
						'YEAR_COMPLETION' => $values['YEAR_COMPLETION'],
						'TOTAL_GRADE' => $values['TOTAL_GRADE']
					);
					
					$this->db->where('ID', $result1[0]['PREVIOUSEDUCATION_ID']);
					$this->db->update('previous_education', $data);
				}else{
					$data = array(
						'INSTITUTE' => $values['INSTITUTE'],
						'LEVEL' => $values['LEVEL'],
						'YEAR_COMPLETION' => $values['YEAR_COMPLETION'],
						'TOTAL_GRADE' => $values['TOTAL_GRADE']
					);
					$this->db->insert('previous_education', $data);
					
					$preEducation_id= $this->db->insert_id();
					if(!empty($preEducation_id)){
						$data = array(
						   'PREVIOUSEDUCATION_ID' => $preEducation_id
						);
						$this->db->where('PROFILE_ID', $id);
						$this->db->update('student_profile', $data);
					}
				}	
				return $id;
			}else{
				$data = array(
				   'ADDRESS' => $values['ADDRESS'],
				   'CITY' => $values['CITY'],
				   'STATE' => $values['STATE'],
				   'COUNTRY' => $values['COUNTRY'],
				   'ZIP_CODE' => $values['ZIP_CODE']
				);
				$this->db->insert('location', $data); 
				$location_id= $this->db->insert_id();
				if(!empty($location_id)){
					$data1 = array(
						'ADMISSION_NO' => $values['ADMISSION_NO'],
						'ADMISSION_DATE' => $values['ADMISSION_DATE'],
						'FIRSTNAME' => $values['FIRSTNAME'],
						'LASTNAME' => $values['LASTNAME'],
						'GENDER' => $values['GENDER'],
						'IMAGE1' => $values['IMAGE1'],
						'DOB' => $values['DOB'],
						'NATIONALITY' => $values['NATIONALITY'],
						'MOTHER_TONGUE' => $values['MOTHER_TONGUE'],
						'RELIGION' => $values['RELIGION'],
						'EMAIL' => $values['EMAIL'],
						'PHONE_NO_1' => $values['PHONE_NO_1'],
						'PHONE_NO_2' => $values['PHONE_NO_2'],
						'LOCATION_ID' => $location_id,
						'BLOOD_GROUP' => $values['BLOOD_GROUP'],
						'BIRTHPLACE' => $values['BIRTHPLACE'],
						'FACEBOOK_LINK' => $values['FACEBOOK_LINK'],
						'GOOGLE_LINK' => $values['GOOGLE_LINK'],
						'LINKEDIN_LINK' => $values['LINKEDIN_LINK']
					);
					$this->db->where('ID', $id);
					$this->db->update('profile', $data1);
				}
				return $id;
			}
	    }
		
		public function getProfileDetailsAll(){
			$sql="SELECT * FROM profile";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function getProfileDetails($id){
			$sql="SELECT * FROM profile where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		// Parents Details
		public function parentsDetails($Id,$values){
			$sql="SELECT ID FROM student_profile where PROFILE_ID='$Id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$pro_id=$result[0]['ID'];
			$sql="SELECT PROF_ID FROM student_relation where STU_PROF_ID='$pro_id'";
			$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
			$totalCount=count($result1);
			if($totalCount>0 || $totalCount!=null){
				for($i=0;$i<$totalCount;$i++){
					$sId=$result1[$i]['PROF_ID'];
					$sql="SELECT ID,LOCATION_ID FROM profile where ID='$sId'";
					$result2 = $this->db->query($sql, $return_object = TRUE)->result_array();
					print_r($result2);exit;
					$data = array(
						'ADDRESS' => $values['pr_address'][$i]['company'],
						'CITY' => $values['pr_city'][$i]['company'],
						'STATE' => $values['pr_state'][$i]['company'],
						'COUNTRY' => 'India',
						'ZIP_CODE' => $values['pr_pincode'][$i]['company']
					);
					$this->db->where('ID', $result2[$i]['LOCATION_ID']);
					$this->db->update('location', $data);
					
					$data1 = array(
						'FIRSTNAME' => $values['fname'][$i]['company'],
						'LASTNAME' => $values['lname'][$i]['company'],
						'DOB' => $values['dob'][$i]['company'],
						'EMAIL' => $values['p_email'][$i]['company'],
						'PHONE_NO_1' => $values['p_phone'][$i]['company'],
						'PHONE_NO_2' => $values['p_mobile_no'][$i]['company']
					);
					$this->db->where('ID', $result2[$i]['ID']);
					$this->db->update('profile', $data1);
					
					$data2 = array(
						'OCCUPATION' => $values['occupation'][$i]['company'],
						'INCOME' => $values['p_income'][$i]['company']
					);
					$this->db->where('PROFILE_ID', $result2[$i]['ID']);
					$this->db->update('profile_extra', $data2);
					
					$data3 = array(
						'EDUCATION' => $values['education'][$i]['company'],
						'RELATION_TYPE' => $values['relation'][$i]['company']
					);
					$this->db->where('PROF_ID', $result2[$i]['ID']);
					$this->db->update('student_relation', $data3);
					
				}
				exit;
				return $Id;
			}else{
				for($i=0;$i<count($values['fname']);$i++){
					$data = array(
						'ADDRESS' => $values['pr_address'][$i]['company'],
						'CITY' => $values['pr_city'][$i]['company'],
						'STATE' => $values['pr_state'][$i]['company'],
						'COUNTRY' => 'India',
						'ZIP_CODE' => $values['pr_pincode'][$i]['company']
					);
					$this->db->insert('location', $data); 
					$location_id= $this->db->insert_id();
					if(!empty($location_id)){
						$data1 = array(
							'FIRSTNAME' => $values['fname'][$i]['company'],
							'LASTNAME' => $values['lname'][$i]['company'],
							'DOB' => $values['dob'][$i]['company'],
							'EMAIL' => $values['p_email'][$i]['company'],
							'PHONE_NO_1' => $values['p_phone'][$i]['company'],
							'PHONE_NO_2' => $values['p_mobile_no'][$i]['company'],
							'LOCATION_ID' => $location_id
						);
						$this->db->insert('profile', $data1); 
						$profile_id= $this->db->insert_id();
						if(!empty($profile_id)){
							$data2 = array(
								'PROFILE_ID' => $profile_id,
								'OCCUPATION' => $values['occupation'][$i]['company'],
								'INCOME' => $values['p_income'][$i]['company']
							);
							$this->db->insert('profile_extra', $data2);
							$data3 = array(
								'PROF_ID' => $profile_id,
								'EDUCATION' => $values['education'][$i]['company'],
								'RELATION_TYPE' => $values['relation'][$i]['company']
							);
							$this->db->where('STU_PROF_ID', $pro_id);
							$this->db->update('student_relation', $data3);
						}
					}
				}
				return $Id;
			}
		}
		
		public function addParentsDetails($Id,$values){
			//print_r($values);exit;
			if($values['parentProfileId']){
				for($i=0;$i<count($values['parentProfileId']);$i++){
					$pId=$values['parentProfileId'][$i];
					$sql="SELECT LOCATION_ID FROM profile where ID='$pId'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					//print_r($result);
					$data = array(
						'ADDRESS' => $values['pr_address'][$i]['company'],
						'CITY' => $values['pr_city'][$i]['company'],
						'STATE' => $values['pr_state'][$i]['company'],
						'COUNTRY' => 'India',
						'ZIP_CODE' => $values['pr_pincode'][$i]['company']
					);
					$this->db->where('ID', $result[0]['LOCATION_ID']);
					$this->db->update('location', $data);
					
					$data1 = array(
						'FIRSTNAME' => $values['fname'][$i]['company'],
						'LASTNAME' => $values['lname'][$i]['company'],
						'DOB' => $values['dob'][$i]['company'],
						'EMAIL' => $values['p_email'][$i]['company'],
						'PHONE_NO_1' => $values['p_phone'][$i]['company'],
						'PHONE_NO_2' => $values['p_mobile_no'][$i]['company']
					);
					$this->db->where('ID', $values['parentProfileId'][$i]);
					$this->db->update('profile', $data1);
					
					$data2 = array(
						'OCCUPATION' => $values['occupation'][$i]['company'],
						'INCOME' => $values['p_income'][$i]['company']
					);
					$this->db->where('PROFILE_ID', $values['parentProfileId'][$i]);
					$this->db->update('profile_extra', $data2);
					
					$data3 = array(
						'EDUCATION' => $values['education'][$i]['company'],
						'RELATION_TYPE' => $values['relation'][$i]['company']
					);
					$this->db->where('PROF_ID', $values['parentProfileId'][$i]);
					$this->db->update('student_relation', $data3);
				}
				//exit;
				return $values['parentProfileId'];
			}else{
				$proId=[];
				for($i=0;$i<count($values['fname']);$i++){
					$data = array(
						'ADDRESS' => $values['pr_address'][$i]['company'],
						'CITY' => $values['pr_city'][$i]['company'],
						'STATE' => $values['pr_state'][$i]['company'],
						'COUNTRY' => 'India',
						'ZIP_CODE' => $values['pr_pincode'][$i]['company']
					);
					$this->db->insert('location', $data); 
					$location_id= $this->db->insert_id();
					if(!empty($location_id)){
						$data1 = array(
							'FIRSTNAME' => $values['fname'][$i]['company'],
							'LASTNAME' => $values['lname'][$i]['company'],
							'DOB' => $values['dob'][$i]['company'],
							'EMAIL' => $values['p_email'][$i]['company'],
							'PHONE_NO_1' => $values['p_phone'][$i]['company'],
							'PHONE_NO_2' => $values['p_mobile_no'][$i]['company'],
							'LOCATION_ID' => $location_id
						);
						$this->db->insert('profile', $data1); 
						$profile_id= $this->db->insert_id();
						array_push($proId,$profile_id);
						if(!empty($profile_id)){
							$data2 = array(
								'PROFILE_ID' => $profile_id,
								'OCCUPATION' => $values['occupation'][$i]['company'],
								'INCOME' => $values['p_income'][$i]['company']
							);
							$this->db->insert('profile_extra', $data2);
							$data3 = array(
								'PROF_ID' => $profile_id,
								'EDUCATION' => $values['education'][$i]['company'],
								'RELATION_TYPE' => $values['relation'][$i]['company']
							);
							$this->db->where('STU_PROF_ID', $Id);
							$this->db->update('student_relation', $data3);
						}
					}
				}
				return $proId;
			}
			//exit;
		}
		
		// Edit Parents Details
		
		public function editParentsDetails($values){
			//print_r($values[0]['profileId']);exit;
			$Id=$values[0]['profileId'];
			$sql="SELECT ID FROM student_profile where PROFILE_ID='$Id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$pro_id=$result[0]['ID'];
			//print_r($result);exit;
			$sql="SELECT PROF_ID FROM student_relation where STU_PROF_ID='$pro_id'";
			$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
			$totalCount=count($result1);
			if($totalCount>0 || $totalCount!=null){
				for($i=0;$i<$totalCount;$i++){
					$sId=$result1[$i]['PROF_ID'];
					$sql="SELECT ID,LOCATION_ID FROM profile where ID='$sId'";
					$result2 = $this->db->query($sql, $return_object = TRUE)->result_array();
					$data = array(
						'ADDRESS' => $values[$i]['pr_address'],
						'CITY' => $values[$i]['pr_city'],
						'STATE' => $values[$i]['pr_state'],
						'COUNTRY' => 'India',
						'ZIP_CODE' => $values[$i]['pr_pincode']	
					);
					$this->db->where('ID', $result2[$i]['LOCATION_ID']);
					$this->db->update('location', $data);
					
					$data1 = array(
						'FIRSTNAME' => $values[$i]['p_first_name'],
						'LASTNAME' => $values[$i]['p_last_name'],
						'DOB' => $values[$i]['p_dob'],
						'EMAIL' => $values[$i]['p_email'],
						'PHONE_NO_1' => $values[$i]['p_phone'],
						'PHONE_NO_2' => $values[$i]['p_mobile_no'],
					);
					$this->db->where('ID', $result2[$i]['ID']);
					$this->db->update('profile', $data1);
					
					$data2 = array(
						'OCCUPATION' => $values[$i]['occupation'],
						'INCOME' => $values[$i]['p_income']
					);
					$this->db->where('PROFILE_ID', $result2[$i]['ID']);
					$this->db->update('profile_extra', $data2);
					
					$data3 = array(
						'EDUCATION' => $values[$i]['p_education'],
						'RELATION_TYPE' => $values[$i]['p_relation']
					);
					$this->db->where('PROF_ID', $result2[$i]['ID']);
					$this->db->update('student_relation', $data3);
					
				}
				return $Id;
			}
		}
		
		public function getParentsDetails($id){
			$sql="SELECT * FROM profile where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$data['profileId']=$result[0]['ID'];
			$data['admission_no']=$result[0]['ADMISSION_NO'];
			$data['admission_date']=$result[0]['ADMISSION_DATE'];
			$data['first_name']=$result[0]['FIRSTNAME'];
			$data['last_name']=$result[0]['LASTNAME'];
			$data['gender']=$result[0]['GENDER'];
			$data['wizard_birth']=$result[0]['DOB'];
			$data['filename']=$result[0]['IMAGE1'];
			$data['email']=$result[0]['EMAIL'];
			$data['wizard_phone']=$result[0]['PHONE_NO_1'];
			$data['mobile_no']=$result[0]['PHONE_NO_2'];
			$data['facebook']=$result[0]['FACEBOOK_LINK'];
			$data['google']=$result[0]['GOOGLE_LINK'];
			$data['linkedin']=$result[0]['LINKEDIN_LINK'];
			$data['selectize_blood']=$result[0]['BLOOD_GROUP'];
			$data['religion']=$result[0]['RELIGION'];
			$data['mother_tongue']=$result[0]['MOTHER_TONGUE'];
			$data['nationality']=$result[0]['NATIONALITY'];
			$nationId=$result[0]['NATIONALITY'];
			$sql="SELECT NAME FROM nationality where ID='$nationId'";
			$nation = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($nation){
				$data['nationality_name']=$nation[0]['NAME'];
			}
			$data['birthplace']=$result[0]['BIRTHPLACE'];
			$location_Id=$result[0]['LOCATION_ID'];
			$data['LOCATION_ID']=$result[0]['LOCATION_ID'];
			$sql="SELECT * FROM location where ID='$location_Id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$data['location_Id']=$result[0]['ID'];
				$data['address']=$result[0]['ADDRESS'];
				$data['stu_city']=$result[0]['CITY'];
				$data['stu_state']=$result[0]['STATE'];
				$data['country']=$result[0]['COUNTRY'];
				$data['pincode']=$result[0]['ZIP_CODE'];
				$countryId=$result[0]['COUNTRY'];
				$sql="SELECT NAME FROM country where ID='$countryId'";
				$country = $this->db->query($sql, $return_object = TRUE)->result_array();
				$data['country_name']=$country[0]['NAME'];
			}
			
			$sql="SELECT * FROM student_profile where PROFILE_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$data['batchId']=$result[0]['COURSEBATCH_ID'];
			$data['selectize_cat']=$result[0]['STUDENTCATEGORY_ID'];
			$data['selectize_styType']=$result[0]['STUDENT_TYPE'];
			$data['student_lives']=$result[0]['STUDENT_LIVES'];
			$data['roll_no']=$result[0]['ROLL_NO'];
			$data['stuPro_id']=$result[0]['ID'];
			$data['maillingAddressId']=$result[0]['MAILLING_ADDRESS_ID'];
			$maillingAddressId=$result[0]['MAILLING_ADDRESS_ID'];
			$pre_eduId=$result[0]['PREVIOUSEDUCATION_ID'];
			$stu_proId=$result[0]['ID'];
			$batchId=$result[0]['COURSEBATCH_ID'];
			//print_r($stu_proId);exit;
			
			// Course Batch Details
			
			$sql="SELECT NAME,COURSE_ID,PERIOD_FROM,PERIOD_TO,(SELECT NAME FROM course where ID=course_batch.COURSE_ID)as courseName,(SELECT DEPT_ID FROM course where ID=course_batch.COURSE_ID)as deptId,(SELECT NAME FROM department where ID=deptId)as deptName FROM course_batch where ID='$batchId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$data['batch_name']=$result[0]['NAME'];
			$data['course_id']=$result[0]['COURSE_ID'];
			$data['course_name']=$result[0]['courseName'];
			$data['dept_id']=$result[0]['deptId'];
			$data['dept_name']=$result[0]['deptName'];
			$data['batch_from']=$result[0]['PERIOD_FROM'];
			$data['batch_to']=$result[0]['PERIOD_TO'];
			
			//Sibling Details
			
			$sql="SELECT * FROM student_relation WHERE RELATION_TYPE='Sibling' AND STU_PROF_ID='$stu_proId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$data['sibling_id']=$result[0]['PROF_ID'];
				$sibling_id=$result[0]['PROF_ID'];
				$sql="SELECT FIRSTNAME,LASTNAME FROM profile WHERE ID='$sibling_id'";
				$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result1){
					$data['sibling_name']=$result1[0]['FIRSTNAME'];
				}
				$sql="SELECT COURSEBATCH_ID FROM student_profile WHERE PROFILE_ID='$sibling_id'";
				$result2 = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result2){
					$sibling_batchId=$result2[0]['COURSEBATCH_ID'];
					$sql="SELECT ID,NAME,COURSE_ID,(SELECT NAME FROM course where ID=course_batch.COURSE_ID)as courseName FROM course_batch where ID='$sibling_batchId'";
					$result3 = $this->db->query($sql, $return_object = TRUE)->result_array();
					$data['sibling_batchId']=$result3[0]['ID'];
					$data['sibling_batchName']=$result3[0]['NAME'];
					$data['sibling_courseId']=$result3[0]['COURSE_ID'];
					$data['sibling_courseName']=$result3[0]['courseName'];
				}
			}
			
			
			$sql="SELECT * FROM location where ID='$maillingAddressId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$data['mail_address']=$result[0]['ADDRESS'];
				$data['mail_city']=$result[0]['CITY'];
				$data['mail_state']=$result[0]['STATE'];
				$data['mail_country']=$result[0]['COUNTRY'];
				$data['mail_pincode']=$result[0]['ZIP_CODE'];
				$countryId=$result[0]['COUNTRY'];
				$sql="SELECT NAME FROM country where ID='$countryId'";
				$country = $this->db->query($sql, $return_object = TRUE)->result_array();
				$data['mail_country_name']=$country[0]['NAME'];
			}
			
			$sql="SELECT ID,INSTITUTE,LEVEL,YEAR_COMPLETION,TOTAL_GRADE FROM previous_education where PROFILE_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key => $value){
					$data2[$key]['preEdu_id']=$value['ID'];
					$data2[$key]['institute']=$value['INSTITUTE'];
					$data2[$key]['course_name']=$value['LEVEL'];
					$data2[$key]['completion']=$value['YEAR_COMPLETION'];
					$data2[$key]['total_mark']=$value['TOTAL_GRADE'];
				}
			}else{
				$data2="";
			}
			
			//$sql="SELECT * FROM profile p,student_relation re,location l,profile_extra px where re.PROF_ID=p.ID AND p.LOCATION_ID=l.ID AND px.PROFILE_ID=p.ID AND STU_PROF_ID='$stu_proId'";
			$sql="SELECT *,p.ID as parent_ProId,px.ID as proEx_ID FROM profile p,student_relation re,location l,profile_extra px where re.PROF_ID=p.ID AND p.LOCATION_ID=l.ID AND px.PROFILE_ID=p.ID AND STU_PROF_ID='$stu_proId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				foreach($result as $key => $value){
					$data1[$key]['pPro_id']=$value['parent_ProId'];
					$data1[$key]['pProEx_id']=$value['proEx_ID'];
					$data1[$key]['p_first_name']=$value['FIRSTNAME'];
					$data1[$key]['p_last_name']=$value['LASTNAME'];
					$data1[$key]['p_relation']=$value['RELATION_TYPE'];
					$data1[$key]['availabe']=$value['AVAILABLE'];
					$data1[$key]['p_dob']=$value['DOB'];
					$data1[$key]['p_education']=$value['EDUCATION'];
					$data1[$key]['occupation']=$value['OCCUPATION'];
					$data1[$key]['p_income']=$value['INCOME'];
					$data1[$key]['pr_address']=$value['ADDRESS'];
					$data1[$key]['pr_city']=$value['CITY'];
					$data1[$key]['pr_state']=$value['STATE'];
					$data1[$key]['pr_pincode']=$value['ZIP_CODE'];
					$data1[$key]['pr_country']=$value['COUNTRY'];
					$data1[$key]['p_phone']=$value['PHONE_NO_1'];
					$data1[$key]['p_mobile_no']=$value['PHONE_NO_2'];
					$data1[$key]['p_email']=$value['EMAIL'];
					$data1[$key]['locationId']=$value['LOCATION_ID'];
					$data1[$key]['student_profile_id']=$value['STU_PROF_ID'];
					$data1[$key]['p_facebook']=$value['FACEBOOK_LINK'];
					$data1[$key]['p_google']=$value['GOOGLE_LINK'];
					$data1[$key]['p_linkedin']=$value['LINKEDIN_LINK'];
					$data1[$key]['profileId']=$id;
					$countryId=$value['COUNTRY'];
					$sql="SELECT NAME FROM country where ID='$countryId'";
					$country = $this->db->query($sql, $return_object = TRUE)->result_array();
					if($country){
						$data1[$key]['country_name']=$country[0]['NAME'];
					}
				}
			}else{
				$data1="";
			}
			
			
			
			// $sql="SELECT * FROM location";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// foreach($result as $key => $value){
				// $data[$key]['address']=$value['ADDRESS'];
				// $data[$key]['stu_city']=$value['CITY'];
				// $data[$key]['stu_state']=$value['STATE'];
				// $data[$key]['country']=$value['COUNTRY'];
				// $data[$key]['pincode']=$value['ZIP_CODE'];
				// //print_r($data);
			// }
			
			//exit;
			 return array(['user_detail' => $data,'user_parents'=> $data1,'pre_edu'=> $data2]);
		}
		
		public function profileEdit($values){
			//print_r($values);exit;
			$data = array (
				'DOB' => $values['profile']['wizard_birth'],
				'IMAGE1' => $values['profile']['filename'],
				'NATIONALITY' => $values['profile']['nationality'],
				'RELIGION' => $values['profile']['religion'],
				'MOTHER_TONGUE' => $values['profile']['mother_tongue'],
				'FACEBOOK_LINK' => $values['profile']['facebook'],
				'GOOGLE_LINK' => $values['profile']['google'],
				'LINKEDIN_LINK' => $values['profile']['linkedin'],
				'BIRTHPLACE' => $values['profile']['birthplace'],
				'BLOOD_GROUP' => $values['profile']['selectize_blood'],
				'EMAIL' => $values['profile']['email'],
				'PHONE_NO_1' => $values['profile']['wizard_phone'],
				'PHONE_NO_2' => $values['profile']['mobile_no'],
			);
			$this->db->where('ID', $values['profile']['profileId']);
			$this->db->update('profile', $data);
			
			$data1 = array (
				'STUDENTCATEGORY_ID' => $values['profile']['selectize_cat'],
				'STUDENT_TYPE' => $values['profile']['selectize_styType'],
			);
			$this->db->where('ID', $values['profile']['stuPro_id']);
			$this->db->update('student_profile', $data1);
			
			$data2 = array (
				'ADDRESS' => $values['profile']['address'],
				'CITY' => $values['profile']['stu_city'],
				'STATE' => $values['profile']['stu_state'],
				'COUNTRY' => $values['profile']['country'],
				'ZIP_CODE' => $values['profile']['pincode']
			);
			$this->db->where('ID', $values['profile']['location_Id']);
			$this->db->update('location', $data2);
			
			$data3 = array (
				'ADDRESS' => $values['profile']['mail_address'],
				'CITY' => $values['profile']['mail_city'],
				'STATE' => $values['profile']['mail_state'],
				'COUNTRY' => $values['profile']['mail_country'],
				'ZIP_CODE' => $values['profile']['mail_pincode']
			);
			$this->db->where('ID', $values['profile']['maillingAddressId']);
			$this->db->update('location', $data3);
			
			// Sibling Details
			$stu_proId=$values['profile']['stuPro_id'];
			//print_r($stu_proId);exit;
			$sql="SELECT * FROM student_relation WHERE RELATION_TYPE='Sibling' AND STU_PROF_ID='$stu_proId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$stuRelId=$result[0]['ID'];
				$data1 = array (
					'PROF_ID' => $values['profile']['sibling_id']
				);
				$this->db->where('ID', $stuRelId);
				$this->db->update('student_relation', $data1);
			}
			
			// Previous Education
			
			foreach($values['pre_edu'] as $values){
				$data = array (
					'INSTITUTE' => $values['institute'],
					'LEVEL' => $values['course_name'],
					'YEAR_COMPLETION' => $values['completion'],
					'TOTAL_GRADE' => $values['total_mark']
				);
				$this->db->where('ID', $values['preEdu_id']);
				$this->db->update('previous_education', $data);
			}
			
			// parents Details
			if(isset($values['parents'])){
				foreach($values['parents'] as $values){
					$data4 = array (
						'FIRSTNAME' => $values['p_first_name'],
						'LASTNAME' => $values['p_last_name'],
						'DOB' => $values['p_dob'],
						'FACEBOOK_LINK' => $values['p_facebook'],
						'GOOGLE_LINK' => $values['p_google'],
						'LINKEDIN_LINK' => $values['p_linkedin'],
						'EMAIL' => $values['p_email'],
						'PHONE_NO_1' => $values['p_phone'],
						'PHONE_NO_2' => $values['p_mobile_no'],
					);
					$this->db->where('ID', $values['pPro_id']);
					$this->db->update('profile', $data4);
					
					$data5 = array (
						'ADDRESS' => $values['pr_address'],
						'CITY' => $values['pr_city'],
						'STATE' => $values['pr_state'],
						'COUNTRY' => $values['pr_country'],
						'ZIP_CODE' => $values['pr_pincode']
					);
					$this->db->where('ID', $values['locationId']);
					$this->db->update('location', $data5);
					
					$data6 = array (
						'OCCUPATION' => $values['occupation'],
						'INCOME' => $values['p_income']
					);
					$this->db->where('ID', $values['pProEx_id']);
					$this->db->update('profile_extra', $data6);
					
					$data7 = array (
						'EDUCATION' => $values['p_education'],
						'AVAILABLE' => $values['availabe']
					);
					$this->db->where('PROF_ID', $values['pPro_id']);
					$this->db->update('student_relation', $data7);
				}
			}
			return true;
		}
		
		// Get Student Profile
		
		public function getStudentProfileDetailsAll(){
			$sql="SELECT * FROM student_profile";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach($result as $key => $value){
				$data[$key]['profileId']=$value['PROFILE_ID'];
				$pId=$value['PROFILE_ID'];
				$cbId=$value['COURSEBATCH_ID'];
				$sql="SELECT ADMISSION_NO,FIRSTNAME,LASTNAME,IMAGE1,EMAIL,PHONE_NO_1 FROM profile where ID='$pId'";
				$proDetail = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($proDetail){
					$data[$key]['adm_no']=$proDetail[0]['ADMISSION_NO'];
				$data[$key]['fname']=$proDetail[0]['FIRSTNAME'];
				$data[$key]['lname']=$proDetail[0]['LASTNAME'];
				$data[$key]['image']=$proDetail[0]['IMAGE1'];
				$data[$key]['email']=$proDetail[0]['EMAIL'];
				$data[$key]['phone']=$proDetail[0]['PHONE_NO_1'];
				}
				
				$sql="SELECT NAME,COURSE_ID,(SELECT NAME FROM course where ID=course_batch.COURSE_ID)as courseName,(SELECT DEPT_ID FROM course where ID=course_batch.COURSE_ID)as deptId,(SELECT NAME FROM department where ID=deptId)as deptName FROM course_batch where ID='$cbId'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					$data[$key]['batch_name']=$result[0]['NAME'];
				$data[$key]['course_id']=$result[0]['COURSE_ID'];
				$data[$key]['course_name']=$result[0]['courseName'];
				$data[$key]['dept_id']=$result[0]['deptId'];
				$data[$key]['dept_name']=$result[0]['deptName'];
				}
			}
			return $data;
		}
		
		public function getStudentProfileDetails($id){
			$sql="SELECT * FROM profile where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		// Student Sibling Data
		
		public function getStudentSiblingDetails($id,$profileId){
			$sql="SELECT * FROM student_profile where NOT PROFILE_ID='$profileId' AND COURSEBATCH_ID='$id'";
			$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
			$pro=[];
			foreach($result1 as $result){
				$proId=$result['PROFILE_ID'];
				$sql="SELECT * FROM profile where ID='$proId'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				array_push($pro,$result);
			}
			return $pro;
		}
		
		// Previous Education Details Delete 
		public function deletePreEducation($id){
			$sql="DELETE FROM previous_education where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Student admission number check 
		
		public function checkStudentAdmissionNo($no,$id){
			if($id){
				$sql="SELECT ADMISSION_NO FROM profile where ADMISSION_NO='$no'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					$sql="SELECT ADMISSION_NO FROM profile where ID='$id'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					$admNo=$result[0]['ADMISSION_NO'];
					if($admNo==$no){
						return false;
					}else{
						return true;
					}
				}else{
					return false;
				}
			}else{
				$sql="SELECT ADMISSION_NO FROM profile where ADMISSION_NO='$no'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return true;
				}else{
					return false;
				}
			}
		}
		
		// set password details
		
		public function getPasswordDetail($token){
			$sql="SELECT USER_PROFILE_ID,USER_EMAIL,USER_PHONE FROM user where USER_VERIFYKEY='$token'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return $result;
			}
		}
		
		public function setPassword($values){
			$data = array (
				'USER_PASSWORD' => $values['password'],
				'USER_VERIFYKEY' => '',
				'USER_STATUS' => 'Y'
			);
			$this->db->where('USER_PROFILE_ID', $values['id']);
			$this->db->update('user', $data);
			
			$data1 = array (
				'EMAIL_VERIFIED' => 'Y'
			);
			$this->db->where('ID', $values['id']);
			$this->db->update('profile', $data1);
			
			return true;
		}
		
		
		public function checkMailVerification($id){
			//$sql="SELECT EMAIL,EMAIL_VERIFIED,PHONE_NO_1,PHONE_NO_1_VERIFIED FROM profile where ID='$id'";
			$sql="SELECT * FROM profile where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['EMAIL']){
				$check=$result[0]['EMAIL_VERIFIED'];
				$mailStatus=$result[0]['EMAIL_STATUS'];
				if($mailStatus=='N'){
					if($check=='N'){
						$to=$result[0]['EMAIL'];
						$phone=$result[0]['PHONE_NO_1'];
						
						// Random Key Generation
						$this->load->helper('string');
						$token=random_string('alnum',25);
						
						$sql="SELECT * FROM user where USER_PROFILE_ID='$id'";
						$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
						if($result1){
							$data = array(
								'USER_FIRST_NAME' => $result[0]['FIRSTNAME'],
								'USER_LAST_NAME' => $result[0]['LASTNAME'],
								'USER_PHONE' => $result[0]['PHONE_NO_1'],
								'USER_EMAIL' => $result[0]['EMAIL'],
								'USER_PASSWORD' => '',
								'USER_ROLE_ID' => 1,
								'USER_VERIFYKEY' => $token,
								'USER_STATUS' => 'N',
							);
							$this->db->where('USER_PROFILE_ID', $id);
							$this->db->update('user', $data);
							
							$data1 = array(
								'EMAIL_STATUS' => 'Y'
							);
							$this->db->where('ID', $id);
							$this->db->update('profile', $data1);
							
							return array(['email' => $to,'token'=> $token,'phone' =>$phone]);
						}else{
							$data = array(
								'USER_FIRST_NAME' => $result[0]['FIRSTNAME'],
								'USER_LAST_NAME' => $result[0]['LASTNAME'],
								'USER_PROFILE_ID' => $result[0]['ID'],
								'USER_PHONE' => $result[0]['PHONE_NO_1'],
								'USER_EMAIL' => $result[0]['EMAIL'],
								'USER_PASSWORD' => '',
								'USER_ROLE_ID' => 1,
								'USER_VERIFYKEY' => $token,
								'USER_STATUS' => 'N',
							);
							$this->db->insert('user', $data);
							
							$data1 = array(
								'EMAIL_STATUS' => 'Y'
							);
							$this->db->where('ID', $id);
							$this->db->update('profile', $data1);
							
							return array(['email' => $to,'token'=> $token,'phone' =>$phone]);
						}
					}
				}
			}
		}
		
		public function checkPhoneVerification($id){
			$sql="SELECT * FROM profile where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['PHONE_NO_1']){
				$check=$result[0]['PHONE_NO_1_VERIFIED'];
				$phoneStatus=$result[0]['PHONE_STATUS'];
				if($phoneStatus=='N'){
					if($check=='N'){
						$to=$result[0]['PHONE_NO_1'];
						$sql="SELECT * FROM user where USER_PROFILE_ID='$id'";
						$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
						if($result1){
							$mailVerify=$result1[0]['USER_VERIFYKEY'];
							$mailStatus=$result1[0]['USER_STATUS'];
							if($mailVerify){
								
							}else{
								if($mailStatus!='N'){
									$data = array(
										'USER_FIRST_NAME' => $result[0]['FIRSTNAME'],
										'USER_LAST_NAME' => $result[0]['LASTNAME'],
										'USER_PHONE' => $result[0]['PHONE_NO_1'],
										'USER_EMAIL' => $result[0]['EMAIL'],
										'USER_PASSWORD' => '',
										'USER_ROLE_ID' => 1,
										'USER_STATUS' => 'N',
									);
									$this->db->where('USER_PROFILE_ID', $id);
									$this->db->update('user', $data);
								}
								
								$data1 = array(
									'PHONE_STATUS' => 'Y'
								);
								$this->db->where('ID', $id);
								$this->db->update('profile', $data1);
							}
						}
						return $to;
						
						// $sql="SELECT * FROM user where USER_PROFILE_ID='$id'";
						// $result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
						// if($result1){
							
							
							
							
							// return array(['phone' => $to,'token'=> $token]);
						// }else{
							// $data = array(
								// 'USER_FIRST_NAME' => $result[0]['FIRSTNAME'],
								// 'USER_LAST_NAME' => $result[0]['LASTNAME'],
								// 'USER_PROFILE_ID' => $result[0]['ID'],
								// 'USER_PHONE' => $result[0]['PHONE_NO_1'],
								// 'USER_EMAIL' => $result[0]['EMAIL'],
								// 'USER_PASSWORD' => '',
								// 'USER_ROLE_ID' => 1,
								// 'USER_STATUS' => 'N',
							// );
							// $this->db->insert('user', $data);
							
							// $data1 = array(
								// 'PHONE_STATUS' => 'Y'
							// );
							// $this->db->where('ID', $id);
							// $this->db->update('profile', $data1);
							
							// return array(['phone' => $to,'token'=> $token]);
						// }
					}
				}
			}
		}
		
		public function passwordReset($data){
			$sql="SELECT * FROM user where USER_EMAIL='$data' AND USER_STATUS='Y'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$user_id=$result[0]['USER_ID'];
				$this->load->helper('string');
				$token=random_string('alnum',25);
				//$token='sgfnskldgnsdjlnsf';
				$data1 = array(
					'USER_PASSWORD' => '',
					'USER_VERIFYKEY' => $token,
					'USER_STATUS' => 'N'
				);
				$this->db->where('USER_ID', $user_id);
				$this->db->update('user', $data1);
				return $token;
			}
		}
		
	}
?>
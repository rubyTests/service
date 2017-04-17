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
			
			return $id;
		}
		
		
		// Previous Academics Details
	
		public function academicsDetails($id,$values){
			//print_r($id);
			//print_r($values);exit;
			$preEdu_id=[];
			foreach($values['previous'] as $value){
				if($value['preEduId']){
					$data = array(
						'INSTITUTE' => $value['institute'],
						'LEVEL' => $value['course_name'],
						'YEAR_COMPLETION' => $value['year'],
						'TOTAL_GRADE' => $value['total_mark'],
						'PROFILE_ID' => $id,
					);
					$this->db->where('ID', $value['preEduId']);
					$this->db->update('previous_education', $data);
					$pre_id= $value['preEduId'];
					array_push($preEdu_id,$pre_id);
				}else{
					$data = array(
						'INSTITUTE' => $value['institute'],
						'LEVEL' => $value['course_name'],
						'YEAR_COMPLETION' => $value['year'],
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
				'STUDENT_TYPE' => $values['stu_type']
			);
			$this->db->where('PROFILE_ID', $id);
			$this->db->update('student_profile', $data1);
			
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
			if($values['sameAddress']=="Yes"){
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
						'EMAIL' => $values['EMAIL'],
						'PHONE_NO_1' => $values['PHONE_NO_1'],
						'PHONE_NO_2' => $values['PHONE_NO_2'],
						'LOCATION_ID' => $location_id,
						'FACEBOOK_LINK' => $values['FACEBOOK_LINK'],
						'GOOGLE_LINK' => $values['GOOGLE_LINK'],
						'LINKEDIN_LINK' => $values['LINKEDIN_LINK']
					);
					$this->db->where('ID', $id);
					$this->db->update('profile', $data1);
				}
				return $location_id;
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
						'PHONE_NO_1' => $values['PHONE_NO_1'],
						'PHONE_NO_2' => $values['PHONE_NO_2'],
						'LOCATION_ID' => $location_id1,
						'FACEBOOK_LINK' => $values['FACEBOOK_LINK'],
						'GOOGLE_LINK' => $values['GOOGLE_LINK'],
						'LINKEDIN_LINK' => $values['LINKEDIN_LINK']
					);
					$this->db->where('ID', $id);
					$this->db->update('profile', $data1);
				}
				
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
				$sql="SELECT PROF_ID FROM student_relation where ID='$frel_id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				//print_r($result);exit;
				$pId=$result[0]['PROF_ID'];
				$sql="SELECT LOCATION_ID FROM profile where ID='$pId'";
				$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
				//print_r($result1);exit;
				$lId=$result1[0]['LOCATION_ID'];
				$data = array(
				   'ADDRESS' => $values['father']['pr_address'],
				   'CITY' => $values['father']['pr_city'],
				   'STATE' => $values['father']['pr_state'],
				   'COUNTRY' => $values['father']['country'],
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
				$this->db->where('ID', $pId);
				$this->db->update('profile', $data1);
				
				$data2 = array (
					'OCCUPATION' => $values['father']['occupation'],
					'INCOME' => $values['father']['p_income'],
				);
				$this->db->where('PROFILE_ID', $pId);
				$this->db->update('profile_extra', $data2);
				
				$data3 = array (
					'STU_PROF_ID' => $values['stuProfileId'],
					'EDUCATION' => $values['father']['p_education'],
					'RELATION_TYPE' => $values['father']['first_relation'],
				);
				$this->db->where('PROF_ID', $pId);
				$this->db->update('student_relation', $data3);
			}else{
				$data = array(
				   'ADDRESS' => $values['father']['pr_address'],
				   'CITY' => $values['father']['pr_city'],
				   'STATE' => $values['father']['pr_state'],
				   'COUNTRY' => $values['father']['country'],
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
							'STU_PROF_ID' => $values['stuProfileId'],
							'EDUCATION' => $values['father']['p_education'],
							'RELATION_TYPE' => $values['father']['first_relation'],
						);
						//$this->db->where('STU_PROF_ID', $values['stuProfileId']);
						//$this->db->update('student_relation', $data3);
						$this->db->insert('student_relation', $data3);
						$frel_id= $this->db->insert_id();
					}
				}
			}
			
			// Mother Details
			
			if($values['mother']['relationId']!=0){
				$mrel_id=$values['mother']['relationId'];
				$sql="SELECT PROF_ID FROM student_relation where ID='$mrel_id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				$pId=$result[0]['PROF_ID'];
				$sql="SELECT LOCATION_ID FROM profile where ID='$pId'";
				$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
				//print_r($result1);exit;
				$lId=$result1[0]['LOCATION_ID'];
				$data = array(
				   'ADDRESS' => $values['mother']['pr_address'],
				   'CITY' => $values['mother']['pr_city'],
				   'STATE' => $values['mother']['pr_state'],
				   'COUNTRY' => $values['mother']['selectize_country'],
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
				$this->db->where('ID', $pId);
				$this->db->update('profile', $data1);
				
				$data2 = array (
					'OCCUPATION' => $values['mother']['occupation'],
					'INCOME' => $values['mother']['p_income'],
				);
				$this->db->where('PROFILE_ID', $pId);
				$this->db->update('profile_extra', $data2);
				
				$data3 = array (
					'STU_PROF_ID' => $values['stuProfileId'],
					'EDUCATION' => $values['mother']['p_education'],
					'RELATION_TYPE' => $values['mother']['second_relation'],
				);
				$this->db->where('PROF_ID', $pId);
				$this->db->update('student_relation', $data3);
			}else{
				$data4 = array(
				   'ADDRESS' => $values['mother']['pr_address'],
				   'CITY' => $values['mother']['pr_city'],
				   'STATE' => $values['mother']['pr_state'],
				   'COUNTRY' => $values['mother']['selectize_country'],
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
							'STU_PROF_ID' => $values['stuProfileId'],
							'EDUCATION' => $values['mother']['p_education'],
							'RELATION_TYPE' => $values['mother']['second_relation'],
						);
						// $this->db->where('STU_PROF_ID', $values['stuProfileId']);
						// $this->db->update('student_relation', $data7);
						$this->db->insert('student_relation', $data7);
						$mrel_id= $this->db->insert_id();
					}
				}
			}
			
			if($values['guardian']['relationId']!=0){
				$grel_id=$values['guardian']['relationId'];
				$sql="SELECT PROF_ID FROM student_relation where ID='$grel_id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				$pId=$result[0]['PROF_ID'];
				$sql="SELECT LOCATION_ID FROM profile where ID='$pId'";
				$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
				$lId=$result1[0]['LOCATION_ID'];
				$data = array(
				   'ADDRESS' => $values['guardian']['pr_address'],
				   'CITY' => $values['guardian']['pr_city'],
				   'STATE' => $values['guardian']['pr_state'],
				   'COUNTRY' => $values['guardian']['selectize_country'],
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
				$this->db->where('ID', $pId);
				$this->db->update('profile', $data1);
				
				$data2 = array (
					'OCCUPATION' => $values['guardian']['occupation'],
					'INCOME' => $values['guardian']['p_income'],
				);
				$this->db->where('PROFILE_ID', $pId);
				$this->db->update('profile_extra', $data2);
				
				$data3 = array (
					'STU_PROF_ID' => $values['stuProfileId'],
					'EDUCATION' => $values['guardian']['p_education'],
					'RELATION_TYPE' => $values['guardian']['third_relation'],
				);
				$this->db->where('PROF_ID', $pId);
				$this->db->update('student_relation', $data3);
			}else{
				if(count($values['guardian'])!=0){
					$data8 = array(
					   'ADDRESS' => $values['guardian']['pr_address'],
					   'CITY' => $values['guardian']['pr_city'],
					   'STATE' => $values['guardian']['pr_state'],
					   'COUNTRY' => $values['guardian']['selectize_country'],
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
								'STU_PROF_ID' => $values['stuProfileId'],
								'EDUCATION' => $values['guardian']['p_education'],
								'RELATION_TYPE' => $values['guardian']['third_relation'],
							);
							// $this->db->where('STU_PROF_ID', $values['stuProfileId']);
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
			$data['wizard_gender']=$result[0]['GENDER'];
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
			$data['selectize_n']=$result[0]['NATIONALITY'];
			$data['birthplace']=$result[0]['BIRTHPLACE'];
			$location_Id=$result[0]['LOCATION_ID'];
			$sql="SELECT * FROM location where ID='$location_Id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$data['location_Id']=$result[0]['ID'];
				$data['address']=$result[0]['ADDRESS'];
				$data['stu_city']=$result[0]['CITY'];
				$data['stu_state']=$result[0]['STATE'];
				$data['country']=$result[0]['COUNTRY'];
				$data['pincode']=$result[0]['ZIP_CODE'];
			}
			
			$sql="SELECT * FROM student_profile where PROFILE_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$data['batchId']=$result[0]['COURSEBATCH_ID'];
			$data['selectize_cat']=$result[0]['STUDENTCATEGORY_ID'];
			$data['selectize_styType']=$result[0]['STUDENT_TYPE'];
			$data['roll_no']=$result[0]['ROLL_NO'];
			$data['stuPro_id']=$result[0]['ID'];
			$data['maillingAddressId']=$result[0]['MAILLING_ADDRESS_ID'];
			$pre_eduId=$result[0]['PREVIOUSEDUCATION_ID'];
			$stu_proId=$result[0]['ID'];
			//print_r($stu_proId);exit;
			
			$maillingAddressId=$result[0]['MAILLING_ADDRESS_ID'];
			$sql="SELECT * FROM location where ID='$maillingAddressId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$data['mail_address']=$result[0]['ADDRESS'];
				$data['mail_city']=$result[0]['CITY'];
				$data['mail_state']=$result[0]['STATE'];
				$data['mail_country']=$result[0]['COUNTRY'];
				$data['mail_pincode']=$result[0]['ZIP_CODE'];
			}
			
			$sql="SELECT * FROM previous_education where PROFILE_ID='$id'";
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
				// $data[$key]['selectize_country']=$value['COUNTRY'];
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
				'NATIONALITY' => $values['profile']['selectize_n'],
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
			
			// parents Details
			
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
					'EDUCATION' => $values['p_education']
				);
				$this->db->where('PROF_ID', $values['pPro_id']);
				$this->db->update('student_relation', $data7);
			}
			return true;
		}
	}
?>
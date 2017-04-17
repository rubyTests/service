<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class profileapi_model extends CI_Model {

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
				   'ZIPCODE' => $values['ZIPCODE']
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
				   'ZIPCODE' => $values['ZIPCODE']
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
						'ZIPCODE' => $values['pr_pincode'][$i]['company']
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
						'ZIPCODE' => $values['pr_pincode'][$i]['company']
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
						'ZIPCODE' => $values['pr_pincode'][$i]['company']
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
						'ZIPCODE' => $values['pr_pincode'][$i]['company']
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
						'ZIPCODE' => $values[$i]['pr_pincode']
						
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
			$data['address']=$result[0]['ADDRESS'];
			$data['stu_city']=$result[0]['CITY'];
			$data['stu_state']=$result[0]['STATE'];
			$data['country']=$result[0]['COUNTRY'];
			$data['pincode']=$result[0]['ZIPCODE'];
			
			$sql="SELECT * FROM student_profile where PROFILE_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$data['selectize_a']=$result[0]['COURSEBATCH_ID'];
			$data['selectize_cat']=$result[0]['STUDENTCATEGORY_ID'];
			$data['selectize_styType']=$result[0]['STUDENT_TYPE'];
			$data['roll_no']=$result[0]['ROLL_NO'];
			$pre_eduId=$result[0]['PREVIOUSEDUCATION_ID'];
			$stu_proId=$result[0]['ID'];
			
			$sql="SELECT * FROM previous_education where ID='$pre_eduId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$data['institute']=$result[0]['INSTITUTE'];
			$data['course_name']=$result[0]['LEVEL'];
			$data['completion']=$result[0]['YEAR_COMPLETION'];
			$data['total_mark']=$result[0]['TOTAL_GRADE'];
			
			$sql="SELECT * FROM profile p,student_relation re,location l,profile_extra px where re.PROF_ID=p.ID AND p.LOCATION_ID=l.ID AND px.PROFILE_ID=p.ID AND STU_PROF_ID='$stu_proId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach($result as $key => $value){
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
				$data1[$key]['pr_pincode']=$value['ZIPCODE'];
				$data1[$key]['pr_country']=$value['COUNTRY'];
				$data1[$key]['p_phone']=$value['PHONE_NO_1'];
				$data1[$key]['p_mobile_no']=$value['PHONE_NO_2'];
				$data1[$key]['p_email']=$value['EMAIL'];
				$data1[$key]['locationId']=$value['LOCATION_ID'];
				$data1[$key]['student_profile_id']=$value['STU_PROF_ID'];
				$data1[$key]['profileId']=$id;
			}
			
			
			// $sql="SELECT * FROM location";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// foreach($result as $key => $value){
				// $data[$key]['address']=$value['ADDRESS'];
				// $data[$key]['stu_city']=$value['CITY'];
				// $data[$key]['stu_state']=$value['STATE'];
				// $data[$key]['selectize_country']=$value['COUNTRY'];
				// $data[$key]['pincode']=$value['ZIPCODE'];
				// //print_r($data);
			// }
			
			//exit;
			 return array(['user_detail' => $data,'user_parents'=> $data1]);
		}
	}
?>
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class employee_mgmnt_model extends CI_Model {

		// Department Details 
		
		public function addCategoryDetails($value){
			$data = array(
			   'NAME' => $value['NAME'],
			   'DESCRIPTION' => $value['DESCRIPTION']
			);
			$this->db->insert('employee_category', $data); 
			$cat_id= $this->db->insert_id();
			if(!empty($cat_id)){
				// return true;
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'CAT_ID'=>$cat_id);
			}
	    }
		
		public function editCategoryDetails($id,$value){
			$data = array(
			   'NAME' => $value['NAME'],
			   'DESCRIPTION' => $value['DESCRIPTION']
			);
			$this->db->where('ID', $id);
			$this->db->update('employee_category', $data); 
			return array('status'=>true, 'message'=>"Record Updated Successfully",'CAT_ID'=>$id);
		}
		
		public function fetchEmployeeCategoryDetails(){
			$sql="SELECT * FROM employee_category";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deleteCategoryDetails($id){
			$sql="DELETE FROM employee_category where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		// Position Details 
		
		public function addPositionDetails($value){
			$data = array(
			   'NAME' => $value['NAME'],
			   'EMP_CATEGORY_ID' => $value['CATEGORY_ID']
			);
			$this->db->insert('employee_position', $data); 
			$position_id= $this->db->insert_id();
			if(!empty($position_id)){
				// return true;
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'POSITION_ID'=>$position_id);
			}
	    }
		
		public function editPositionDetails($id,$value){
			$data = array(
			   'NAME' => $value['NAME'],
			   'EMP_CATEGORY_ID' => $value['CATEGORY_ID']
			);
			$this->db->where('ID', $id);
			$this->db->update('employee_position', $data); 
			return array('status'=>true, 'message'=>"Record Updated Successfully",'POSITION_ID'=>$id);
		}
		
		public function fetchEmployeePositionDetails(){
			$sql="SELECT * FROM employee_position";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deletePositionDetails($id){
			$sql="DELETE FROM employee_position where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}

		public function getEmployeePositionView(){
			$sql="SELECT * FROM employee_position_view";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		

		// employee admission

		public function addEmployeeAdmissionDetails($value){
			// echo "add";exit;
			// if($value['profile_image']){
			// 	$Images=$value['profile_image'];
			//    	$ImageSplit = explode(',', $Images);        
		 //        $ImageResult = base64_decode($ImageSplit[1]);
		 //        $im = imagecreatefromstring($ImageResult); 
		 //        if ($im !== false) 
		 //        {
		 //            $fileName = date('Ymdhis') .".png";
		 //            $resp = imagepng($im, $_SERVER['DOCUMENT_ROOT'].'smartedu/upload/'.$fileName);
		 //            imagedestroy($im);
		 //        }
			// }else {
			// 	$fileName='';
			// }
			$adm_no=$value['admission_no'];
			$sql="SELECT * FROM profile where ADMISSION_NO='$adm_no'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$profile = array(
				   'ADMISSION_NO' => $value['admission_no'],
				   'ADMISSION_DATE' => $value['join_date'],
				   'FIRSTNAME' => $value['first_name'],
				   'LASTNAME' => $value['last_name'],
				   'GENDER' => $value['gender'],
				   'DOB' => $value['dob'],
				   'IMAGE1' => $value['profile_image'],
				   'NATIONALITY' => $value['nationality'],
				   'MARITAL_STATUS' => $value['marital_status']
				);
				// print_r($profile);exit;
				$this->db->insert('profile', $profile); 
				$profile_id= $this->db->insert_id();
				if(!empty($profile_id)){
					$emp_profile = array(
					   'PROFILE_ID' => $profile_id,
					   'QUALIFICATION' =>$value['qualification'],
					   'DEPT_ID' => $value['department'],
					   'EMP_CATEGORY_ID' => $value['category'],
					   'EMP_POSTION_ID' => $value['position'],
					   'MANAGER_PROFILE_ID' => $value['report_to'],
					);
					$this->db->insert('employee_profile', $emp_profile); 
					$emp_profile_id= $this->db->insert_id();
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'PROFILE_ID'=>$profile_id,'EMP_PROFILE_ID'=>$emp_profile_id);
				}
			}
		}

		// Edit admission Profile
		public function editEmployeeAdmissionDetails($id,$value){
			// if($value['profile_image']){
			// 	$Images=$value['profile_image'];
			// 	$ImageSplit = explode(',', $Images);  
			// 	$ImageSplit1 = explode(':', $ImageSplit[0]);
			// 	if($ImageSplit1[0]=='http') {
			// 		$IMG = $value['profile_image'];
			// 		$splitIMage = explode('/', $IMG);
			// 		$fileName=$splitIMage[5];
			// 	}else {
			// 		$ImageResult = base64_decode($ImageSplit[1]);
			//         $im = imagecreatefromstring($ImageResult); 
			//         if ($im !== false) 
			//         {
			//             $fileName = date('Ymdhis') .".png";
			//             $resp = imagepng($im, $_SERVER['DOCUMENT_ROOT'].'smartedu/upload/'.$fileName);
			//             imagedestroy($im);
			//         }
			// 	}
			// }else {
			// 	$fileName='';
			// }	
			$ProId=$value['ProfileID'];
			$sql="SELECT * FROM profile where ID='$ProId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['ADMISSION_NO']==$value['admission_no']){
				$profile = array(
				   'ADMISSION_NO' => $value['admission_no'],
				   'ADMISSION_DATE' => $value['join_date'],
				   'FIRSTNAME' => $value['first_name'],
				   'LASTNAME' => $value['last_name'],
				   'GENDER' => $value['gender'],
				   'DOB' => $value['dob'],
				   'IMAGE1' => $value['profile_image'],
				   'NATIONALITY' => $value['nationality'],
				   'MARITAL_STATUS' => $value['marital_status']
				);
				$this->db->where('ID', $value['ProfileID']);
				$this->db->update('profile', $profile);

				$emp_profile = array(
				   'PROFILE_ID' => $value['ProfileID'],
				   'QUALIFICATION' =>$value['qualification'],
				   'DEPT_ID' => $value['department'],
				   'EMP_CATEGORY_ID' => $value['category'],
				   'EMP_POSTION_ID' => $value['position'],
				   'MANAGER_PROFILE_ID' => $value['report_to'],
				);
				$this->db->where('ID', $id);
				$this->db->update('employee_profile', $emp_profile);
				return array('status'=>true, 'message'=>"Record Updated Successfully",'PROFILE_ID'=>$value['ProfileID'],'EMP_PROFILE_ID'=>$id);
			}else {
				$name=$value['admission_no'];
				$sql="SELECT * FROM profile where ADMISSION_NO='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$profile = array(
					   'ADMISSION_NO' => $value['admission_no'],
					   'ADMISSION_DATE' => $value['join_date'],
					   'FIRSTNAME' => $value['first_name'],
					   'LASTNAME' => $value['last_name'],
					   'GENDER' => $value['gender'],
					   'DOB' => $value['dob'],
					   'IMAGE1' => $value['profile_image'],
					   'NATIONALITY' => $value['nationality'],
					   'MARITAL_STATUS' => $value['marital_status']
					);
					$this->db->where('ID', $value['ProfileID']);
					$this->db->update('profile', $profile);

					$emp_profile = array(
					   'PROFILE_ID' => $value['ProfileID'],
					   'QUALIFICATION' =>$value['qualification'],
					   'DEPT_ID' => $value['department'],
					   'EMP_CATEGORY_ID' => $value['category'],
					   'EMP_POSTION_ID' => $value['position'],
					   'MANAGER_PROFILE_ID' => $value['report_to'],
					);
					$this->db->where('ID', $id);
					$this->db->update('employee_profile', $emp_profile);
					return array('status'=>true, 'message'=>"Record Updated Successfully",'PROFILE_ID'=>$value['ProfileID'],'EMP_PROFILE_ID'=>$id);
				}
			}
			
		}

		function addEmployeeContactDetails($value){
			$EmailID=$value["email"];
			$sql="SELECT * FROM profile where EMAIL='$EmailID'";
			$result=$this->db->query($sql, $return_object = TRUE)->result_array();
			// return array('status'=>false);
			// print_r($result);exit();
			if($result){
				return array('status'=>false);
			}else {
				if($value['m_address']){
					$data = array(
					   'ADDRESS' => $value['m_address'],
					   'CITY' => $value['m_city'],
					   'STATE' => $value['m_state'],
					   'COUNTRY' => $value['m_country'],
					   'ZIP_CODE' => $value['m_pincode']
					);
					$this->db->insert('location', $data);
					$mailing_id= $this->db->insert_id();
				}
				if($value['m_address']){
					$data1 = array(
					   'ADDRESS' => $value['p_address'],
					   'CITY' => $value['p_city'],
					   'STATE' => $value['P_state'],
					   'COUNTRY' => $value['P_country'],
					   'ZIP_CODE' => $value['P_pincode']
					);
					$this->db->insert('location', $data1);
					$permanant_id= $this->db->insert_id();
				}
				if($value['ProfileID']){
					$profile = array(
					   'EMAIL' => $value['email'],
					   'PHONE_NO_1' => $value['phone_no'],
					   'PHONE_NO_2' => $value['mobile_no'],
					   'GOOGLE_LINK' => $value['google'],
					   'FACEBOOK_LINK' => $value['facebook'],
					   'LINKEDIN_LINK' => $value['linked_in'],
					   'MAILING_ADDRESS' => $mailing_id,
					   'PERMANANT_ADDRESS' => $permanant_id
					);
					$this->db->where('ID', $value['ProfileID']);
					$this->db->update('profile', $profile); 
				}
				return array('status'=>true, 'message'=>"Record inserted Successfully",'PROFILE_ID'=>$value['ProfileID'],'EMP_PROFILE_ID'=>$value['employee_id'],'PERM_ADDRESS_ID'=>$permanant_id,'MAILING_ADDRESS_ID'=>$mailing_id);
			}
			
		}
		function editEmployeeContactDetails($id,$value){
			if($id){
				$data = array(
				   'ADDRESS' => $value['m_address'],
				   'CITY' => $value['m_city'],
				   'STATE' => $value['m_state'],
				   'COUNTRY' => $value['m_country'],
				   'ZIP_CODE' => $value['m_pincode']
				);
				$this->db->where('ID', $id);
				$this->db->update('location', $data);
			}
			if($value['permanant_id']){
				$data1 = array(
				   'ADDRESS' => $value['p_address'],
				   'CITY' => $value['p_city'],
				   'STATE' => $value['P_state'],
				   'COUNTRY' => $value['P_country'],
				   'ZIP_CODE' => $value['P_pincode']
				);
				$this->db->where('ID', $value['permanant_id']);
				$this->db->update('location', $data1);
			}
			if($value['ProfileID']){
				$profile = array(
				   'EMAIL' => $value['email'],
				   'PHONE_NO_1' => $value['phone_no'],
				   'PHONE_NO_2' => $value['mobile_no'],
				   'GOOGLE_LINK' => $value['google'],
				   'FACEBOOK_LINK' => $value['facebook'],
				   'LINKEDIN_LINK' => $value['linked_in'],
				   'MAILING_ADDRESS' => $id,
				   'PERMANANT_ADDRESS' => $value['permanant_id']
				);
				$this->db->where('ID', $value['ProfileID']);
				$this->db->update('profile', $profile); 
			}
			return array('status'=>true, 'message'=>"Record Updated Successfully",'PROFILE_ID'=>$value['ProfileID'],'EMP_PROFILE_ID'=>$value['employee_id'],'PERM_ADDRESS_ID'=>$value['permanant_id'],'MAILING_ADDRESS_ID'=>$id);
		}
		function addEmployeePrevInstitute($value){
			 // print_r($value);exit;
			if($value['instituteID']!='')
			{
				$instID=[];
				$LocID=[];
				// print_r($value);exit;
				for($i=0;$i<count($value['prev_inst_data']);$i++){
					$inst_ID=$value['prev_inst_data'][$i]['prevInst_ID'];
					if($inst_ID){
						$lction_id=$value['prev_inst_data'][$i]['locationID'];
						$sql="SELECT * FROM LOCATION WHERE ID='$lction_id'";
						$result = $this->db->query($sql, $return_object = TRUE)->result_array();
						$lc_id=$result[0]['ID'];
						if($result){
							$data1 = array(
							   'ADDRESS' => $value['prev_inst_data'][$i]['prev_address'],
							   'CITY' => $value['prev_inst_data'][$i]['prev_city'],
							   'STATE' => $value['prev_inst_data'][$i]['prev_state'],
							   'COUNTRY' => $value['prev_inst_data'][$i]['prev_country'],
							   'ZIP_CODE' => $value['prev_inst_data'][$i]['prev_pincode']
							);
							$this->db->where('ID', $lc_id);
							$fff=$this->db->update('location', $data1);

							$sql1="SELECT * FROM previous_institute WHERE ID='$inst_ID'";
							$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
							$in_id=$result1[0]['ID'];
							if($result1){
								$emp_data = array(
								   'DESIGNATION' =>  $value['prev_inst_data'][$i]['employee_role'],
								   'INST_NAME' =>  $value['prev_inst_data'][$i]['p_institute_name'],
								   'LOCATION_ID' =>  $value['prev_inst_data'][$i]['locationID'],
								   'PERIOD_FROM' =>  $value['prev_inst_data'][$i]['prev_period_from'],
								   'PERIOD_TO' =>  $value['prev_inst_data'][$i]['prev_period_to'],
								   'EMP_PROF_ID' => $value['emp_profile_id']
								);
								$this->db->where('ID', $in_id);
								$this->db->update('previous_institute', $emp_data);
								array_push($LocID, $lc_id);
								array_push($instID, $in_id);
							}
						}
					}
					else
					{
						if($value['prev_inst_data'][$i]['employee_role']){
							$data1 = array(
							   'ADDRESS' => $value['prev_inst_data'][$i]['prev_address'],
							   'CITY' => $value['prev_inst_data'][$i]['prev_city'],
							   'STATE' => $value['prev_inst_data'][$i]['prev_state'],
							   'COUNTRY' => $value['prev_inst_data'][$i]['prev_country'],
							   'ZIP_CODE' => $value['prev_inst_data'][$i]['prev_pincode']
							);
							$this->db->insert('location', $data1);
							$loc_id= $this->db->insert_id();
							if(!empty($loc_id)){
								$emp_data = array(
								   'DESIGNATION' =>  $value['prev_inst_data'][$i]['employee_role'],
								   'INST_NAME' =>  $value['prev_inst_data'][$i]['p_institute_name'],
								   'LOCATION_ID' =>  $value['prev_inst_data'][$i]['locationID'],
								   'PERIOD_FROM' =>  $value['prev_inst_data'][$i]['prev_period_from'],
								   'PERIOD_TO' =>  $value['prev_inst_data'][$i]['prev_period_to'],
								   'EMP_PROF_ID' => $value['emp_profile_id']
								);
								$this->db->insert('previous_institute', $emp_data);
								$institute_id= $this->db->insert_id();
								array_push($instID, $institute_id);
								array_push($LocID, $loc_id);
							}
						}
					}
					
				}
				return array('status'=>true, 'message'=>"Record Updated Successfully",'INSTITUTE_ID'=>$instID,'LOCATION_ID'=>$LocID);
			}
			else 
			{
				$instID=[];
				$LocID=[];
				$empProfileID=$value['emp_profile_id'];
				$total=$value['prev_inst_data'];
				for($i=0;$i<count($total);$i++){
					if($total[$i]['employee_role']){
						$data1 = array(
						   'ADDRESS' => $total[$i]['prev_address'],
						   'CITY' => $total[$i]['prev_city'],
						   'STATE' => $total[$i]['prev_state'],
						   'COUNTRY' => $total[$i]['prev_country'],
						   'ZIP_CODE' => $total[$i]['prev_pincode']
						);
						$this->db->insert('location', $data1);
						$loc_id= $this->db->insert_id();
						if(!empty($loc_id)){
							$emp_data = array(
							   'DESIGNATION' => $total[$i]['employee_role'],
							   'INST_NAME' => $total[$i]['p_institute_name'],
							   'LOCATION_ID' => $loc_id,
							   'PERIOD_FROM' => $total[$i]['prev_period_from'],
							   'PERIOD_TO' => $total[$i]['prev_period_to'],
							   'EMP_PROF_ID' => $empProfileID
							);
							$this->db->insert('previous_institute', $emp_data);
							$institute_id= $this->db->insert_id();
							array_push($instID, $institute_id);
							array_push($LocID, $loc_id);
						}
					}
				}
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'INSTITUTE_ID'=>$instID,'LOCATION_ID'=>$LocID);
			}
		}
		function addEmployeeAdditionalDetails($value){
			if($value['profile_extra_id']){
				
			}else {
				if($value['acc_name'] || $value['acc_number'] || $value['bank_name']){
					$data = array(
					   'ACCOUNT_NAME' => $value['acc_name'],
					   'ACCOUNT_NO' => $value['acc_number'],
					   'BANK_NAME' => $value['bank_name'],
					   'BRANCH_NO' => $value['branch_code']
					);
					$this->db->insert('bank_details', $data);
					$bank_id= $this->db->insert_id();
					if(!empty($bank_id)){
						$data1 = array(
						   'PROFILE_ID' => $value['profile'],
						   'BANK_DETAIL_ID' => $bank_id,
						   'PASSPORT_NO' => $value['passport'],
						   'WORK_PERMIT' => $value['work_permit'],
						);
						$this->db->insert('profile_extra', $data1);
						$extra_id= $this->db->insert_id();
					}

					if(!empty($extra_id)){
						$emp_profile = array(
						   'PROFILE_EXTRA_ID' => $extra_id
						);
						$this->db->where('ID', $value['emp_profile_id']);
						$this->db->update('employee_profile', $emp_profile);
					}

					return array('status'=>true, 'message'=>"Record Inserted Successfully",'BANK_ID'=>$bank_id,'PROF_EXTRA_ID'=>$extra_id);
				}else {
					return array('status'=>true, 'message'=>"Record Inserted Successfully");
				}
			}
		}
		function fetchEmployeeViewDetails(){
			$sql="SELECT * FROM employee_profile_view";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchParticularEmployeeDetails($id){
			// $sql="select 
			// employee_profile.ID,profile.ID AS PROF_ID,profile.ADMISSION_NO,profile.FIRSTNAME,profile.LASTNAME,profile.DOB,
			// profile.GENDER,profile.IMAGE1,profile.EMAIL,profile.PHONE_NO_1,profile.PHONE_NO_2,profile.FACEBOOK_LINK,
			// profile.GOOGLE_LINK,profile.LINKEDIN_LINK,profile.MAILING_ADDRESS,profile.PERMANANT_ADDRESS,
			// NATIONALITY.ID as NATION_ID,NATIONALITY.NAME AS NATION_NAME,
			// marital.ID as MARITAL_ID,marital.NAME AS MARITAL_NAME,employee_profile.QUALIFICATION,
			// department.ID as DEPT_ID,department.NAME AS DEPT_NAME,
			// employee_position.ID as POSITION_ID,employee_position.NAME AS POSITION_NAME,
			// employee_category.ID as CAT_ID,employee_category.NAME AS CATEGORY_NAME,
			// profile_extra.PASSPORT_NO,profile_extra.WORK_PERMIT,profile_extra.ID AS P_EXTRA_ID,
			// bank_details.ID AS BANK_ID,bank_details.ACCOUNT_NAME,bank_details.ACCOUNT_NO,bank_details.BANK_NAME,bank_details.BRANCH_NO
			// from employee_profile 
			// JOIN profile on employee_profile.PROFILE_ID=profile.ID 
			// JOIN nationality on profile.NATIONALITY=nationality.ID
			// JOIN marital on profile.MARITAL_STATUS=marital.ID
			// JOIN employee_category on employee_profile.EMP_CATEGORY_ID=employee_category.ID
			// JOIN department on employee_profile.DEPT_ID=department.ID
			// JOIN employee_position on employee_profile.EMP_POSTION_ID=employee_position.ID
			// JOIN profile_extra on employee_profile.PROFILE_EXTRA_ID=profile_extra.ID
			// JOIN bank_details on profile_extra.BANK_DETAIL_ID=bank_details.ID
			// where employee_profile.ID='$id'";
			$sql="select ID,PROFILE_ID,QUALIFICATION,DEPT_ID,EMP_CATEGORY_ID,EMP_POSTION_ID,BANK_DETAIL_ID,PROFILE_EXTRA_ID,MANAGER_PROFILE_ID,
			(select FIRSTNAME from profile where ID=PROFILE_ID) AS FIRSTNAME,
			(select LASTNAME from profile where ID=PROFILE_ID) AS LASTNAME,
			(select ADMISSION_NO from profile where ID=PROFILE_ID) AS ADMISSION_NO,
			(select GENDER from profile where ID=PROFILE_ID) AS GENDER,
			(select DOB from profile where ID=PROFILE_ID) AS DOB,
			(select IMAGE1 from profile where ID=PROFILE_ID) AS IMAGE1,
			(select EMAIL from profile where ID=PROFILE_ID) AS EMAIL,
			(select PHONE_NO_1 from profile where ID=PROFILE_ID) AS PHONE_NO_1,
			(select PHONE_NO_2 from profile where ID=PROFILE_ID) AS PHONE_NO_2,
			(select FACEBOOK_LINK from profile where ID=PROFILE_ID) AS FACEBOOK_LINK,
			(select GOOGLE_LINK from profile where ID=PROFILE_ID) AS GOOGLE_LINK,
			(select LINKEDIN_LINK from profile where ID=PROFILE_ID) AS LINKEDIN_LINK,
			(select RELIGION from profile where ID=PROFILE_ID) AS RELIGION,
			(select MOTHER_TONGUE from profile where ID=PROFILE_ID) AS MOTHER_TONGUE,
			(select NATIONALITY from profile where ID=PROFILE_ID) AS NATIONALITY,
			(select MARITAL_STATUS from profile where ID=PROFILE_ID) AS MARITAL_STATUS,
			(select MAILING_ADDRESS from profile where ID=PROFILE_ID) AS MAILING_ADDRESS,
			(select PERMANANT_ADDRESS from profile where ID=PROFILE_ID) AS PERMANANT_ADDRESS,
			(select LOCATION_ID from profile where ID=PROFILE_ID) AS LOCATION_ID,
			(select NAME from department where ID=DEPT_ID) AS DEPT_NAME,
			(select NAME from employee_position where ID=EMP_POSTION_ID) AS POSITION_NAME,
			(select NAME from employee_category where ID=EMP_CATEGORY_ID) AS CATEGORY_NAME,
			(select NAME from nationality where ID=NATIONALITY) AS NATION_NAME,
			(select NAME from marital where ID=MARITAL_STATUS) AS MARITAL_NAME,
			(select PASSPORT_NO from profile_extra where ID=PROFILE_EXTRA_ID) AS PASSPORT_NO,
			(select WORK_PERMIT from profile_extra where ID=PROFILE_EXTRA_ID) AS WORK_PERMIT,
			(select BANK_DETAIL_ID from profile_extra where ID=PROFILE_EXTRA_ID) AS BANK_ID,
			(select ACCOUNT_NAME from bank_details where ID=BANK_ID) AS ACCOUNT_NAME,
			(select ACCOUNT_NO from bank_details where ID=BANK_ID) AS ACCOUNT_NO,
			(select BRANCH_NO from bank_details where ID=BANK_ID) AS BRANCH_NO,
			(select BANK_NAME from bank_details where ID=BANK_ID) AS BANK_NAME
			from employee_profile where employee_profile.ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getEmployeeList(){
			$sql="SELECT PROFILE_ID as ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=employee_profile.PROFILE_ID)as EMP_NAME FROM employee_profile";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		function fetchMailingAddressDetails($id){
			$sql="SELECT ID,ADDRESS,CITY,STATE,COUNTRY,ZIP_CODE,(select NAME from country where ID=location.COUNTRY)AS COUNTRY_NAME FROM location where ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchPreviousInstituteDetails($id){
			// $sql="SELECT ID,DESIGNATION,INST_NAME,LOCATION_ID,PERIOD_FROM,PERIOD_TO FROM previous_institute where EMP_PROF_ID='$id'";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();

			// foreach ($result as $key => $value) {
			// 	$loc_id=$value['LOCATION_ID'];
			// 	$sql1="SELECT ADDRESS,CITY,STATE,COUNTRY,ZIP_CODE,(SELECT NAME FROM country WHERE ID=location.COUNTRY)AS COUNRTY_NAME FROM location where ID='$loc_id'";
			// 	$result[$key]['location'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// }
			// return $result;


			$sql="SELECT ID,DESIGNATION,INST_NAME,LOCATION_ID,PERIOD_FROM,PERIOD_TO,
				(SELECT ADDRESS FROM LOCATION WHERE ID=LOCATION_ID) AS ADDRESS,
				(SELECT CITY FROM LOCATION WHERE ID=LOCATION_ID) AS CITY,
				(SELECT STATE FROM LOCATION WHERE ID=LOCATION_ID) AS STATE,
				(SELECT ZIP_CODE FROM LOCATION WHERE ID=LOCATION_ID) AS ZIP_CODE,
				(SELECT COUNTRY FROM LOCATION WHERE ID=LOCATION_ID) AS COUNTRY,
				(SELECT NAME FROM country WHERE ID=COUNTRY) AS COUNTRY_NAME
				FROM previous_institute where EMP_PROF_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function updateEmployeeProfileDetails($value){
			$PROFILEid=$value['profile'];
			$passEmail=$value['email'];
			$sql="SELECT * FROM PROFILE WHERE ID='$PROFILEid'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['EMAIL']==$value['email']){
				// echo 'same';
				if($value['emp_profile_id']){
					$profile = array(
					   'DOB' => $value['dob'],
					   'IMAGE1' => $value['profile_image'],
					   'NATIONALITY' => $value['natoinality'],
					   'MARITAL_STATUS' => $value['marital'],
					   'EMAIL' => $value['email'],
					   'PHONE_NO_1' => $value['phone'],
					   'PHONE_NO_2' => $value['mobile'],
					   'GOOGLE_LINK' => $value['google'],
					   'FACEBOOK_LINK' => $value['facebook'],
					   'LINKEDIN_LINK' => $value['linkedin']
					);
					$this->db->where('ID', $value['profile']);
					$this->db->update('profile', $profile);

					$emp_profile = array(
					   'QUALIFICATION' =>$value['qualification'],
					   'EMP_CATEGORY_ID' => $value['category']
					);
					$this->db->where('ID', $value['emp_profile_id']);
					$this->db->update('employee_profile', $emp_profile);


					// Contact Details
					if($value['mail_add_id']){
						$mail = array(
						   'ADDRESS' => $value['m_address'],
						   'CITY' => $value['m_city'],
						   'STATE' => $value['m_state'],
						   'COUNTRY' => $value['m_country'],
						   'ZIP_CODE' => $value['m_pincode']
						);
						$this->db->where('ID', $value['mail_add_id']);
						$this->db->update('location', $mail);
					}else {
						$mail = array(
						   'ADDRESS' => $value['m_address'],
						   'CITY' => $value['m_city'],
						   'STATE' => $value['m_state'],
						   'COUNTRY' => $value['m_country'],
						   'ZIP_CODE' => $value['m_pincode']
						);
						$this->db->insert('location', $mail);
						$mailing_id= $this->db->insert_id();

						$perm = array(
						   'ADDRESS' => $value['p_address'],
						   'CITY' => $value['p_city'],
						   'STATE' => $value['p_state'],
						   'COUNTRY' => $value['p_country'],
						   'ZIP_CODE' => $value['p_pincode']
						);
						$this->db->insert('location', $perm);
						$permanant_id= $this->db->insert_id();

						$profile = array(
						   'MAILING_ADDRESS' => $mailing_id,
						   'PERMANANT_ADDRESS' => $permanant_id
						);
						$this->db->where('ID', $value['profile']);
						$this->db->update('profile', $profile);
					}
					if($value['perm_add_id']){
						$perm = array(
						   'ADDRESS' => $value['p_address'],
						   'CITY' => $value['p_city'],
						   'STATE' => $value['p_state'],
						   'COUNTRY' => $value['p_country'],
						   'ZIP_CODE' => $value['p_pincode']
						);
						$this->db->where('ID', $value['perm_add_id']);
						$this->db->update('location', $perm);
					}


					// Addtional Details
					if($value['bank_id']) {
						$bank= array(
						   'ACCOUNT_NAME' => $value['account_name'],
						   'ACCOUNT_NO' => $value['account_num'],
						   'BANK_NAME' => $value['bank_name'],
						   'BRANCH_NO' => $value['branch_name']
						);
						$this->db->where('ID', $value['bank_id']);
						$this->db->update('bank_details', $bank);
					}else {
						$bank = array(
						   'ACCOUNT_NAME' => $value['account_name'],
						   'ACCOUNT_NO' => $value['account_num'],
						   'BANK_NAME' => $value['bank_name'],
						   'BRANCH_NO' => $value['branch_name']
						);
						$this->db->insert('bank_details', $bank);
						$bank_id= $this->db->insert_id();
						if(!empty($bank_id)){
							$data1 = array(
							   'PROFILE_ID' => $value['profile'],
							   'BANK_DETAIL_ID' => $bank_id,
							   'PASSPORT_NO' => $value['passport_num'],
							   'WORK_PERMIT' => $value['work_permit'],
							);
							$this->db->insert('profile_extra', $data1);
							$extra_id= $this->db->insert_id();
							if(!empty($extra_id)){
								$emp_profile = array(
								   'PROFILE_EXTRA_ID' =>$extra_id
								);
								$this->db->where('ID', $value['emp_profile_id']);
								$this->db->update('employee_profile', $emp_profile);
							}
						}
					}

					if($value['profile_extra_id'])
					{
						$extra = array(
						   'PASSPORT_NO' => $value['passport_num'],
						   'WORK_PERMIT' => $value['work_permit'],
						);
						$this->db->where('ID', $value['profile_extra_id']);
						$this->db->update('profile_extra', $extra);
					}

					
					// Modified by vijay (25-04-17)

					// previous Institute
					for($i=0;$i<count($value['prvious_work']);$i++){
						if($value['prvious_work'][$i]['ID']){
							// echo "edit";
							$address = array(
							   'ADDRESS' => $value['prvious_work'][$i]['ADDRESS'],
							   'CITY' => $value['prvious_work'][$i]['CITY'],
							   'STATE' => $value['prvious_work'][$i]['STATE'],
							   'COUNTRY' => $value['prvious_work'][$i]['COUNTRY'],
							   'ZIP_CODE' => $value['prvious_work'][$i]['ZIP_CODE']
							);
							$this->db->where('ID', $value['prvious_work'][$i]['LOCATION_ID']);
							$fff=$this->db->update('location', $address);

							$inst = array(
							   'DESIGNATION' =>  $value['prvious_work'][$i]['DESIGNATION'],
							   'INST_NAME' =>  $value['prvious_work'][$i]['INST_NAME'],
							   'LOCATION_ID' =>  $value['prvious_work'][$i]['LOCATION_ID'],
							   'PERIOD_FROM' =>  $value['prvious_work'][$i]['PERIOD_FROM'],
							   'PERIOD_TO' =>  $value['prvious_work'][$i]['PERIOD_TO']
							);
							$this->db->where('ID', $value['prvious_work'][$i]['ID']);
							$this->db->update('previous_institute', $inst);
						}else {
							// echo "add";
							$newaddress = array(
							   'ADDRESS' => $value['prvious_work'][$i]['ADDRESS'],
							   'CITY' => $value['prvious_work'][$i]['CITY'],
							   'STATE' => $value['prvious_work'][$i]['STATE'],
							   'COUNTRY' => $value['prvious_work'][$i]['COUNTRY'],
							   'ZIP_CODE' => $value['prvious_work'][$i]['ZIP_CODE']
							);
							$this->db->insert('location', $newaddress);
							$locID_id= $this->db->insert_id();
							if(!empty($locID_id)){
								$newinst = array(
								   'DESIGNATION' =>  $value['prvious_work'][$i]['DESIGNATION'],
								   'INST_NAME' =>  $value['prvious_work'][$i]['INST_NAME'],
								   'EMP_PROF_ID' =>  $value['emp_profile_id'],
								   'LOCATION_ID' =>  $locID_id,
								   'PERIOD_FROM' =>  $value['prvious_work'][$i]['PERIOD_FROM'],
								   'PERIOD_TO' =>  $value['prvious_work'][$i]['PERIOD_TO']
								);
								$this->db->insert('previous_institute', $newinst);
							}
						}
					}
					// exit;
				}
				return array('status'=>true, 'message'=>"Record Updated Successfully",'check'=>'Old');
			}else {
				$sql="SELECT EMAIL FROM PROFILE WHERE EMAIL='$passEmail'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					if($value['emp_profile_id']){
						$profile = array(
						   'DOB' => $value['dob'],
						   'IMAGE1' => $value['profile_image'],
						   'NATIONALITY' => $value['natoinality'],
						   'MARITAL_STATUS' => $value['marital'],
						   'EMAIL' => $value['email'],
						   'PHONE_NO_1' => $value['phone'],
						   'PHONE_NO_2' => $value['mobile'],
						   'GOOGLE_LINK' => $value['google'],
						   'FACEBOOK_LINK' => $value['facebook'],
						   'LINKEDIN_LINK' => $value['linkedin']
						);
						$this->db->where('ID', $value['profile']);
						$this->db->update('profile', $profile);

						$emp_profile = array(
						   'QUALIFICATION' =>$value['qualification'],
						   'EMP_CATEGORY_ID' => $value['category']
						);
						$this->db->where('ID', $value['emp_profile_id']);
						$this->db->update('employee_profile', $emp_profile);


						// Contact Details
						if($value['mail_add_id']){
							$mail = array(
							   'ADDRESS' => $value['m_address'],
							   'CITY' => $value['m_city'],
							   'STATE' => $value['m_state'],
							   'COUNTRY' => $value['m_country'],
							   'ZIP_CODE' => $value['m_pincode']
							);
							$this->db->where('ID', $value['mail_add_id']);
							$this->db->update('location', $mail);
						}else {
							$mail = array(
							   'ADDRESS' => $value['m_address'],
							   'CITY' => $value['m_city'],
							   'STATE' => $value['m_state'],
							   'COUNTRY' => $value['m_country'],
							   'ZIP_CODE' => $value['m_pincode']
							);
							$this->db->insert('location', $mail);
							$mailing_id= $this->db->insert_id();

							$perm = array(
							   'ADDRESS' => $value['p_address'],
							   'CITY' => $value['p_city'],
							   'STATE' => $value['p_state'],
							   'COUNTRY' => $value['p_country'],
							   'ZIP_CODE' => $value['p_pincode']
							);
							$this->db->insert('location', $perm);
							$permanant_id= $this->db->insert_id();

							$profile = array(
							   'MAILING_ADDRESS' => $mailing_id,
							   'PERMANANT_ADDRESS' => $permanant_id
							);
							$this->db->where('ID', $value['profile']);
							$this->db->update('profile', $profile);
						}
						if($value['perm_add_id']){
							$perm = array(
							   'ADDRESS' => $value['p_address'],
							   'CITY' => $value['p_city'],
							   'STATE' => $value['p_state'],
							   'COUNTRY' => $value['p_country'],
							   'ZIP_CODE' => $value['p_pincode']
							);
							$this->db->where('ID', $value['perm_add_id']);
							$this->db->update('location', $perm);
						}


						// Addtional Details
						if($value['bank_id']) {
							$bank= array(
							   'ACCOUNT_NAME' => $value['account_name'],
							   'ACCOUNT_NO' => $value['account_num'],
							   'BANK_NAME' => $value['bank_name'],
							   'BRANCH_NO' => $value['branch_name']
							);
							$this->db->where('ID', $value['bank_id']);
							$this->db->update('bank_details', $bank);
						}else {
							$bank = array(
							   'ACCOUNT_NAME' => $value['account_name'],
							   'ACCOUNT_NO' => $value['account_num'],
							   'BANK_NAME' => $value['bank_name'],
							   'BRANCH_NO' => $value['branch_name']
							);
							$this->db->insert('bank_details', $bank);
							$bank_id= $this->db->insert_id();
							if(!empty($bank_id)){
								$data1 = array(
								   'PROFILE_ID' => $value['profile'],
								   'BANK_DETAIL_ID' => $bank_id,
								   'PASSPORT_NO' => $value['passport_num'],
								   'WORK_PERMIT' => $value['work_permit'],
								);
								$this->db->insert('profile_extra', $data1);
								$extra_id= $this->db->insert_id();
								if(!empty($extra_id)){
									$emp_profile = array(
									   'PROFILE_EXTRA_ID' =>$extra_id
									);
									$this->db->where('ID', $value['emp_profile_id']);
									$this->db->update('employee_profile', $emp_profile);
								}
							}
						}

						if($value['profile_extra_id'])
						{
							$extra = array(
							   'PASSPORT_NO' => $value['passport_num'],
							   'WORK_PERMIT' => $value['work_permit'],
							);
							$this->db->where('ID', $value['profile_extra_id']);
							$this->db->update('profile_extra', $extra);
						}

						
						// Modified by vijay (25-04-17)

						// previous Institute
						for($i=0;$i<count($value['prvious_work']);$i++){
							if($value['prvious_work'][$i]['ID']){
								// echo "edit";
								$address = array(
								   'ADDRESS' => $value['prvious_work'][$i]['ADDRESS'],
								   'CITY' => $value['prvious_work'][$i]['CITY'],
								   'STATE' => $value['prvious_work'][$i]['STATE'],
								   'COUNTRY' => $value['prvious_work'][$i]['COUNTRY'],
								   'ZIP_CODE' => $value['prvious_work'][$i]['ZIP_CODE']
								);
								$this->db->where('ID', $value['prvious_work'][$i]['LOCATION_ID']);
								$fff=$this->db->update('location', $address);

								$inst = array(
								   'DESIGNATION' =>  $value['prvious_work'][$i]['DESIGNATION'],
								   'INST_NAME' =>  $value['prvious_work'][$i]['INST_NAME'],
								   'LOCATION_ID' =>  $value['prvious_work'][$i]['LOCATION_ID'],
								   'PERIOD_FROM' =>  $value['prvious_work'][$i]['PERIOD_FROM'],
								   'PERIOD_TO' =>  $value['prvious_work'][$i]['PERIOD_TO']
								);
								$this->db->where('ID', $value['prvious_work'][$i]['ID']);
								$this->db->update('previous_institute', $inst);
							}else {
								// echo "add";
								$newaddress = array(
								   'ADDRESS' => $value['prvious_work'][$i]['ADDRESS'],
								   'CITY' => $value['prvious_work'][$i]['CITY'],
								   'STATE' => $value['prvious_work'][$i]['STATE'],
								   'COUNTRY' => $value['prvious_work'][$i]['COUNTRY'],
								   'ZIP_CODE' => $value['prvious_work'][$i]['ZIP_CODE']
								);
								$this->db->insert('location', $newaddress);
								$locID_id= $this->db->insert_id();
								if(!empty($locID_id)){
									$newinst = array(
									   'DESIGNATION' =>  $value['prvious_work'][$i]['DESIGNATION'],
									   'INST_NAME' =>  $value['prvious_work'][$i]['INST_NAME'],
									   'EMP_PROF_ID' =>  $value['emp_profile_id'],
									   'LOCATION_ID' =>  $locID_id,
									   'PERIOD_FROM' =>  $value['prvious_work'][$i]['PERIOD_FROM'],
									   'PERIOD_TO' =>  $value['prvious_work'][$i]['PERIOD_TO']
									);
									$this->db->insert('previous_institute', $newinst);
								}
							}
						}
						// exit;
					}
					return array('status'=>true, 'message'=>"Record Updated Successfully", 'check'=>'New');
				}
			}
			exit;













			// echo "<pre>";print_r($value);exit;
			// if($value['emp_profile_id']){
			// 	$profile = array(
			// 	   'DOB' => $value['dob'],
			// 	   'IMAGE1' => $value['profile_image'],
			// 	   'NATIONALITY' => $value['natoinality'],
			// 	   'MARITAL_STATUS' => $value['marital'],
			// 	   'EMAIL' => $value['email'],
			// 	   'PHONE_NO_1' => $value['phone'],
			// 	   'PHONE_NO_2' => $value['mobile'],
			// 	   'GOOGLE_LINK' => $value['google'],
			// 	   'FACEBOOK_LINK' => $value['facebook'],
			// 	   'LINKEDIN_LINK' => $value['linkedin']
			// 	);
			// 	$this->db->where('ID', $value['profile']);
			// 	$this->db->update('profile', $profile);

			// 	$emp_profile = array(
			// 	   'QUALIFICATION' =>$value['qualification'],
			// 	   'EMP_CATEGORY_ID' => $value['category']
			// 	);
			// 	$this->db->where('ID', $value['emp_profile_id']);
			// 	$this->db->update('employee_profile', $emp_profile);


			// 	// Contact Details
			// 	if($value['mail_add_id']){
			// 		$mail = array(
			// 		   'ADDRESS' => $value['m_address'],
			// 		   'CITY' => $value['m_city'],
			// 		   'STATE' => $value['m_state'],
			// 		   'COUNTRY' => $value['m_country'],
			// 		   'ZIP_CODE' => $value['m_pincode']
			// 		);
			// 		$this->db->where('ID', $value['mail_add_id']);
			// 		$this->db->update('location', $mail);
			// 	}else {
			// 		$mail = array(
			// 		   'ADDRESS' => $value['m_address'],
			// 		   'CITY' => $value['m_city'],
			// 		   'STATE' => $value['m_state'],
			// 		   'COUNTRY' => $value['m_country'],
			// 		   'ZIP_CODE' => $value['m_pincode']
			// 		);
			// 		$this->db->insert('location', $mail);
			// 		$mailing_id= $this->db->insert_id();

			// 		$perm = array(
			// 		   'ADDRESS' => $value['p_address'],
			// 		   'CITY' => $value['p_city'],
			// 		   'STATE' => $value['p_state'],
			// 		   'COUNTRY' => $value['p_country'],
			// 		   'ZIP_CODE' => $value['p_pincode']
			// 		);
			// 		$this->db->insert('location', $perm);
			// 		$permanant_id= $this->db->insert_id();

			// 		$profile = array(
			// 		   'MAILING_ADDRESS' => $mailing_id,
			// 		   'PERMANANT_ADDRESS' => $permanant_id
			// 		);
			// 		$this->db->where('ID', $value['profile']);
			// 		$this->db->update('profile', $profile);
			// 	}
			// 	if($value['perm_add_id']){
			// 		$perm = array(
			// 		   'ADDRESS' => $value['p_address'],
			// 		   'CITY' => $value['p_city'],
			// 		   'STATE' => $value['p_state'],
			// 		   'COUNTRY' => $value['p_country'],
			// 		   'ZIP_CODE' => $value['p_pincode']
			// 		);
			// 		$this->db->where('ID', $value['perm_add_id']);
			// 		$this->db->update('location', $perm);
			// 	}


			// 	// Addtional Details
			// 	if($value['bank_id']) {
			// 		$bank= array(
			// 		   'ACCOUNT_NAME' => $value['account_name'],
			// 		   'ACCOUNT_NO' => $value['account_num'],
			// 		   'BANK_NAME' => $value['bank_name'],
			// 		   'BRANCH_NO' => $value['branch_name']
			// 		);
			// 		$this->db->where('ID', $value['bank_id']);
			// 		$this->db->update('bank_details', $bank);
			// 	}else {
			// 		$bank = array(
			// 		   'ACCOUNT_NAME' => $value['account_name'],
			// 		   'ACCOUNT_NO' => $value['account_num'],
			// 		   'BANK_NAME' => $value['bank_name'],
			// 		   'BRANCH_NO' => $value['branch_name']
			// 		);
			// 		$this->db->insert('bank_details', $bank);
			// 		$bank_id= $this->db->insert_id();
			// 		if(!empty($bank_id)){
			// 			$data1 = array(
			// 			   'PROFILE_ID' => $value['profile'],
			// 			   'BANK_DETAIL_ID' => $bank_id,
			// 			   'PASSPORT_NO' => $value['passport_num'],
			// 			   'WORK_PERMIT' => $value['work_permit'],
			// 			);
			// 			$this->db->insert('profile_extra', $data1);
			// 			$extra_id= $this->db->insert_id();
			// 			if(!empty($extra_id)){
			// 				$emp_profile = array(
			// 				   'PROFILE_EXTRA_ID' =>$extra_id
			// 				);
			// 				$this->db->where('ID', $value['emp_profile_id']);
			// 				$this->db->update('employee_profile', $emp_profile);
			// 			}
			// 		}
			// 	}

			// 	if($value['profile_extra_id'])
			// 	{
			// 		$extra = array(
			// 		   'PASSPORT_NO' => $value['passport_num'],
			// 		   'WORK_PERMIT' => $value['work_permit'],
			// 		);
			// 		$this->db->where('ID', $value['profile_extra_id']);
			// 		$this->db->update('profile_extra', $extra);
			// 	}

				
			// 	// Modified by vijay (25-04-17)

			// 	// previous Institute
			// 	for($i=0;$i<count($value['prvious_work']);$i++){
			// 		if($value['prvious_work'][$i]['ID']){
			// 			// echo "edit";
			// 			$address = array(
			// 			   'ADDRESS' => $value['prvious_work'][$i]['ADDRESS'],
			// 			   'CITY' => $value['prvious_work'][$i]['CITY'],
			// 			   'STATE' => $value['prvious_work'][$i]['STATE'],
			// 			   'COUNTRY' => $value['prvious_work'][$i]['COUNTRY'],
			// 			   'ZIP_CODE' => $value['prvious_work'][$i]['ZIP_CODE']
			// 			);
			// 			$this->db->where('ID', $value['prvious_work'][$i]['LOCATION_ID']);
			// 			$fff=$this->db->update('location', $address);

			// 			$inst = array(
			// 			   'DESIGNATION' =>  $value['prvious_work'][$i]['DESIGNATION'],
			// 			   'INST_NAME' =>  $value['prvious_work'][$i]['INST_NAME'],
			// 			   'LOCATION_ID' =>  $value['prvious_work'][$i]['LOCATION_ID'],
			// 			   'PERIOD_FROM' =>  $value['prvious_work'][$i]['PERIOD_FROM'],
			// 			   'PERIOD_TO' =>  $value['prvious_work'][$i]['PERIOD_TO']
			// 			);
			// 			$this->db->where('ID', $value['prvious_work'][$i]['ID']);
			// 			$this->db->update('previous_institute', $inst);
			// 		}else {
			// 			// echo "add";
			// 			$newaddress = array(
			// 			   'ADDRESS' => $value['prvious_work'][$i]['ADDRESS'],
			// 			   'CITY' => $value['prvious_work'][$i]['CITY'],
			// 			   'STATE' => $value['prvious_work'][$i]['STATE'],
			// 			   'COUNTRY' => $value['prvious_work'][$i]['COUNTRY'],
			// 			   'ZIP_CODE' => $value['prvious_work'][$i]['ZIP_CODE']
			// 			);
			// 			$this->db->insert('location', $newaddress);
			// 			$locID_id= $this->db->insert_id();
			// 			if(!empty($locID_id)){
			// 				$newinst = array(
			// 				   'DESIGNATION' =>  $value['prvious_work'][$i]['DESIGNATION'],
			// 				   'INST_NAME' =>  $value['prvious_work'][$i]['INST_NAME'],
			// 				   'EMP_PROF_ID' =>  $value['emp_profile_id'],
			// 				   'LOCATION_ID' =>  $locID_id,
			// 				   'PERIOD_FROM' =>  $value['prvious_work'][$i]['PERIOD_FROM'],
			// 				   'PERIOD_TO' =>  $value['prvious_work'][$i]['PERIOD_TO']
			// 				);
			// 				$this->db->insert('previous_institute', $newinst);
			// 			}
			// 		}
			// 	}
			// 	// exit;
			// }
			// return array('status'=>true, 'message'=>"Record Updated Successfully");
		}

		public function deleteEmployeeDetails($id,$prof_id){
			$sql="select ID,PROFILE_ID,PROFILE_EXTRA_ID,
			(select MAILING_ADDRESS from profile where ID=PROFILE_ID) AS MAILING_ADDRESS,
			(select PERMANANT_ADDRESS from profile where ID=PROFILE_ID) AS PERMANANT_ADDRESS,
			(select BANK_DETAIL_ID from profile_extra where ID=PROFILE_EXTRA_ID) AS BANK_ID
			from employee_profile where employee_profile.ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();

			if(isset($result[0]['PROFILE_ID'])){
				$emp="DELETE FROM employee_profile where ID='$id'";
				$res = $this->db->query($emp);
				$profile_id=$result[0]['PROFILE_ID'];
				$sql1="DELETE FROM profile where ID='$profile_id'";
				$result1 = $this->db->query($sql1);
				if(isset($result[0]['MAILING_ADDRESS'])){
					$mail_id=$result[0]['MAILING_ADDRESS'];
					$sql2="DELETE FROM location where ID='$mail_id'";
					$result2 = $this->db->query($sql2);
					if(isset($result[0]['PERMANANT_ADDRESS'])){
						$perm_id=$result[0]['PERMANANT_ADDRESS'];
						$sql3="DELETE FROM location where ID='$perm_id'";
						$result3 = $this->db->query($sql3);
						if(isset($result[0]['BANK_ID'])){
							$bank_id=$result[0]['BANK_ID'];
							$sql4="DELETE FROM bank_details where ID='$bank_id'";
							$result4 = $this->db->query($sql4);
							if(isset($result[0]['PROFILE_EXTRA_ID'])){
								$extra_id=$result[0]['PROFILE_EXTRA_ID'];
								$sql5="DELETE FROM profile_extra where ID='$extra_id'";
								$result5 = $this->db->query($sql5);
							}
						}
					}
				}
			}

			// print_r($result);exit;
			// if(isset($result[0]['ID'])){
			// 	$emp_id=$result[0]['ID'];
			// 	$sql="DELETE FROM employee_profile where ID='$emp_id'";
			// 	$result = $this->db->query($sql);
			// }
			// if(isset($result[0]['PROFILE_ID'])){
			// 	$profile_id=$result[0]['PROFILE_ID'];
			// 	$sql="DELETE FROM profile where ID='$profile_id'";
			// 	$result = $this->db->query($sql);
			// }
			// if(isset($result[0]['MAILING_ADDRESS'])){
			// 	$mail_id=$result[0]['MAILING_ADDRESS'];
			// 	$sql="DELETE FROM location where ID='$mail_id'";
			// 	$result = $this->db->query($sql);
			// }
			// if(isset($result[0]['PERMANANT_ADDRESS'])){
			// 	$perm_id=$result[0]['PERMANANT_ADDRESS'];
			// 	$sql="DELETE FROM location where ID='$perm_id'";
			// 	$result = $this->db->query($sql);
			// }
			// if(isset($result[0]['BANK_ID'])){
			// 	$bank_id=$result[0]['BANK_ID'];
			// 	$sql="DELETE FROM bank_details where ID='$bank_id'";
			// 	$result = $this->db->query($sql);
			// }
			// if(isset($result[0]['PROFILE_EXTRA_ID'])){
			// 	$extra_id=$result[0]['PROFILE_EXTRA_ID'];
			// 	$sql="DELETE FROM profile_extra where ID='$extra_id'";
			// 	$result = $this->db->query($sql);
			// }
			return $this->db->affected_rows();
			// $sql="DELETE FROM employee_profile where ID='$id'";
			// $result = $this->db->query($sql);
			// if($result==1){
			// 	$sql="DELETE FROM profile where ID='$prof_id'";
			// 	$result = $this->db->query($sql);
			// 	return $this->db->affected_rows();
			// }
		}
		public function deletePrevInstDetails($id,$LocId){
			$sql="DELETE FROM previous_institute where ID='$id'";
			$result = $this->db->query($sql);
			$sql1="DELETE FROM location where ID='$LocId'";
			$result1 = $this->db->query($sql1);
	    	return $this->db->affected_rows();
		}

		function getEmployeeListBasedonDept($deptid){
			// $sql="SELECT PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NAME FROM EMPLOYEE_PROFILE WHERE DEPT_ID='$deptid'";
			$sql="SELECT PROFILE.* FROM EMPLOYEE_PROFILE INNER JOIN PROFILE ON EMPLOYEE_PROFILE.PROFILE_ID=PROFILE.ID WHERE DEPT_ID='$deptid'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		function getEmployeeSearchDetails($search){
			$sql="SELECT EMPLOYEE_PROFILE.ID,EMPLOYEE_PROFILE.PROFILE_ID,CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS EMPLOYEE_NAME,PROFILE.ADMISSION_NO FROM EMPLOYEE_PROFILE LEFT JOIN PROFILE ON EMPLOYEE_PROFILE.PROFILE_ID=PROFILE.ID WHERE PROFILE.FIRSTNAME LIKE '%$search%' OR PROFILE.LASTNAME LIKE '%$search%'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function checkEmail($empid){
			$sql="SELECT MANAGER_PROFILE_ID AS MNGRP_ID,(SELECT PROFILE_ID FROM EMPLOYEE_PROFILE WHERE ID=MNGRP_ID) AS E_ID,(SELECT EMAIL FROM PROFILE WHERE ID=E_ID) AS MAIL_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=E_ID) AS EMPLOYEE_NAME FROM EMPLOYEE_PROFILE WHERE ID='$empid'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function addMailDetails($email){
			$data = array(
			   'MAIL_TO' => $email,
			   'STATUS' => 'N'
			);
			$this->db->insert('EMAIL_LOG', $data); 
			$Elog_id= $this->db->insert_id();
			if(!empty($Elog_id)){
				// return true;
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'EMAIL_LOG_ID'=>$Elog_id);
			}
		}
		function updateMailDetails($emaillog_Id){
			$data = array(
			   'STATUS' => 'Y'
			);
			$this->db->where('ID', $emaillog_Id);
			$this->db->update('EMAIL_LOG', $data); 
			return array('status'=>true, 'message'=>"Record Updated Successfully",'EMAIL_LOG_ID'=>$emaillog_Id);
		}
	}
?>
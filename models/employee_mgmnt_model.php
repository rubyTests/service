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
			if($value['profile_image']){
				$Images=$value['profile_image'];
			   	$ImageSplit = explode(',', $Images);        
		        $ImageResult = base64_decode($ImageSplit[1]);
		        $im = imagecreatefromstring($ImageResult); 
		        if ($im !== false) 
		        {
		            $fileName = date('Ymdhis') .".png";
		            $resp = imagepng($im, $_SERVER['DOCUMENT_ROOT'].'smartedu/upload/'.$fileName);
		            imagedestroy($im);
		        }
			}else {
				$fileName='';
			}
			
			$profile = array(
			   'ADMISSION_NO' => $value['admission_no'],
			   'ADMISSION_DATE' => $value['join_date'],
			   'FIRSTNAME' => $value['first_name'],
			   'LASTNAME' => $value['last_name'],
			   'GENDER' => $value['gender'],
			   'DOB' => $value['dob'],
			   'IMAGE1' => $fileName,
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
				   'EMP_POSTION_ID' => $value['position']
				);
				$this->db->insert('employee_profile', $emp_profile); 
				$emp_profile_id= $this->db->insert_id();
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'PROFILE_ID'=>$profile_id,'EMP_PROFILE_ID'=>$emp_profile_id);
			}
		}

		// Edit admission Profile
		public function editEmployeeAdmissionDetails($id,$value){
			if($value['profile_image']){
				$Images=$value['profile_image'];
				$ImageSplit = explode(',', $Images);  
				$ImageSplit1 = explode(':', $ImageSplit[0]);
				if($ImageSplit1[0]=='http') {
					$IMG = $value['profile_image'];
					$splitIMage = explode('/', $IMG);
					$fileName=$splitIMage[5];
				}else {
					$ImageResult = base64_decode($ImageSplit[1]);
			        $im = imagecreatefromstring($ImageResult); 
			        if ($im !== false) 
			        {
			            $fileName = date('Ymdhis') .".png";
			            $resp = imagepng($im, $_SERVER['DOCUMENT_ROOT'].'smartedu/upload/'.$fileName);
			            imagedestroy($im);
			        }
				}
			}else {
				$fileName='';
			}			
			$profile = array(
			   'ADMISSION_NO' => $value['admission_no'],
			   'ADMISSION_DATE' => $value['join_date'],
			   'FIRSTNAME' => $value['first_name'],
			   'LASTNAME' => $value['last_name'],
			   'GENDER' => $value['gender'],
			   'DOB' => $value['dob'],
			   'IMAGE1' => $fileName,
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
			   'EMP_POSTION_ID' => $value['position']
			);
			$this->db->where('ID', $id);
			$this->db->update('employee_profile', $emp_profile);
			return array('status'=>true, 'message'=>"Record Updated Successfully",'PROFILE_ID'=>$value['ProfileID'],'EMP_PROFILE_ID'=>$id);
		}

		function addEmployeeContactDetails($value){
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
				return array('status'=>true, 'message'=>"Record Updated Successfully",'INSTITUTE_ID'=>$instID,'LOCATION_ID'=>$LocID);
			}
			else 
			{
				$instID=[];
				$LocID=[];
				$empProfileID=$value['emp_profile_id'];
				$total=$value['prev_inst_data'];
				for($i=0;$i<count($total);$i++){
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
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'INSTITUTE_ID'=>$instID,'LOCATION_ID'=>$LocID);
			}
		}
		function addEmployeeAdditionalDetails($value){
			if($value['profile_extra_id']){
				
			}else {
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
			}
		}
		function fetchEmployeeViewDetails(){
			$sql="SELECT * FROM employee_profile_view";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchParticularEmployeeDetails($id){
			$sql="select 
			employee_profile.ID,profile.ID AS PROF_ID,profile.ADMISSION_NO,profile.FIRSTNAME,profile.LASTNAME,profile.DOB,
			profile.GENDER,profile.IMAGE1,profile.EMAIL,profile.PHONE_NO_1,profile.PHONE_NO_2,profile.FACEBOOK_LINK,
			profile.GOOGLE_LINK,profile.LINKEDIN_LINK,profile.MAILING_ADDRESS,profile.PERMANANT_ADDRESS,
			NATIONALITY.ID as NATION_ID,NATIONALITY.NAME AS NATION_NAME,
			marital.ID as MARITAL_ID,marital.NAME AS MARITAL_NAME,employee_profile.QUALIFICATION,
			department.ID as DEPT_ID,department.NAME AS DEPT_NAME,
			employee_position.ID as POSITION_ID,employee_position.NAME AS POSITION_NAME,
			employee_category.ID as CAT_ID,employee_category.NAME AS CATEGORY_NAME,
			profile_extra.PASSPORT_NO,profile_extra.WORK_PERMIT,profile_extra.ID AS P_EXTRA_ID,
			bank_details.ID AS BANK_ID,bank_details.ACCOUNT_NAME,bank_details.ACCOUNT_NO,bank_details.BANK_NAME,bank_details.BRANCH_NO
			from employee_profile 
			JOIN profile on employee_profile.PROFILE_ID=profile.ID 
			JOIN nationality on profile.NATIONALITY=nationality.ID
			JOIN marital on profile.MARITAL_STATUS=marital.ID
			JOIN employee_category on employee_profile.EMP_CATEGORY_ID=employee_category.ID
			JOIN department on employee_profile.DEPT_ID=department.ID
			JOIN employee_position on employee_profile.EMP_POSTION_ID=employee_position.ID
			JOIN profile_extra on employee_profile.PROFILE_EXTRA_ID=profile_extra.ID
			JOIN bank_details on profile_extra.BANK_DETAIL_ID=bank_details.ID
			where employee_profile.ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchMailingAddressDetails($id){
			$sql="SELECT ID,ADDRESS,CITY,STATE,COUNTRY,ZIP_CODE,(select NAME from country where ID=location.COUNTRY)AS COUNTRY_NAME FROM location where ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchPreviousInstituteDetails($id){
			$sql="SELECT * FROM previous_institute where EMP_PROF_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();

			foreach ($result as $key => $value) {
				$loc_id=$value['LOCATION_ID'];
				$sql1="SELECT ADDRESS,CITY,STATE,COUNTRY,ZIP_CODE,(SELECT NAME FROM country WHERE ID=location.COUNTRY)AS COUNRTY_NAME FROM location where ID='$loc_id'";
				$result[$key]['location'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;
		}
		function updateEmployeeProfileDetails($value){
			// echo "<pre>";print_r($value);exit;
			if($value['emp_profile_id']){
				$profile = array(
				   'DOB' => $value['dob'],
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

				// previous Institute
				for($i=0;$i<count($value['prvious_work']);$i++){
					$address = array(
					   'ADDRESS' => $value['prvious_work'][$i]['location'][0]['ADDRESS'],
					   'CITY' => $value['prvious_work'][$i]['location'][0]['CITY'],
					   'STATE' => $value['prvious_work'][$i]['location'][0]['STATE'],
					   'COUNTRY' => $value['prvious_work'][$i]['location'][0]['COUNTRY'],
					   'ZIP_CODE' => $value['prvious_work'][$i]['location'][0]['ZIP_CODE']
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
				}
			}
			return array('status'=>true, 'message'=>"Record Updated Successfully");
		}

		public function deleteEmployeeDetails($id,$prof_id){
			$sql="DELETE FROM employee_profile where ID='$id'";
			$result = $this->db->query($sql);
			if($result==1){
				$sql="DELETE FROM profile where ID='$prof_id'";
				$result = $this->db->query($sql);
				return $this->db->affected_rows();
			}
		}
	}
?>
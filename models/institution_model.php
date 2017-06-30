<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class institution_model extends CI_Model {

		// Institution

		function addInstitutionBasicDetails($value){
			// $Images=$value['image_file'];
			// 	print_r($Images);exit;
			if($value['institution_id']!=''){

				// $Images=$value['image_file'];
				// // printer_abort($Images);exit;
			 //   	$ImageSplit = explode(',', $Images);        
		  //       $ImageResult = base64_decode($ImageSplit[1]);
		  //       $im = imagecreatefromstring($ImageResult); 
		  //       if ($im !== false) 
		  //       {
		  //           $fileName = date('Ymdhis') .".png";
		  //           $resp = imagepng($im, $_SERVER['DOCUMENT_ROOT'].'smartedu/upload/'.$fileName);
		  //           imagedestroy($im);
		  //       } 


				// $Images=$value['image_file'];
				// $ImageSplit = explode(',', $Images);  
				// $ImageSplit1 = explode(':', $ImageSplit[0]);
				// if($ImageSplit1[0]=='http') {
				// 	$IMG = $value['image_file'];
				// 	$splitIMage = explode('/', $IMG);
				// 	$fileName=$splitIMage[5];
				// }else {
				// 	$ImageResult = base64_decode($ImageSplit[1]);
			 //        $im = imagecreatefromstring($ImageResult); 
			 //        if ($im !== false) 
			 //        {
			 //            $fileName = date('Ymdhis') .".png";
			 //            $resp = imagepng($im, $_SERVER['DOCUMENT_ROOT'].'rubyServices/upload/'.$fileName);
			 //            imagedestroy($im);
			 //        }
				// }
				
		        $profile = array(
				   'FIRSTNAME' => $value['institute_name']
				);
				$this->db->where('ID', $value['profile_id']);
				$this->db->update('profile', $profile);

				if($value['profile_id']){
					$institution = array(
					   'CODE' => $value['inst_code'],
					   'PROF_ID' => $value['profile_id'],
					   'TYPE' => $value['type'],
					   'TIME_ZONE' => $value['time_zone'],
					   'CURRENCY' => $value['currency'],
					   'LOGO' => $value['image_file']
					);
					$this->db->where('ID', $value['institution_id']);
					$this->db->update('institution', $institution);
				}
				return array('status'=>true, 'message'=>"Record Updated Successfully",'PROFILE_ID'=>$value['profile_id'],'INSTITUTION_ID'=>$value['institution_id']);

			}else {
				// $Images=$value['image_file'];
			 //   	$ImageSplit = explode(',', $Images);        
		  //       $ImageResult = base64_decode($ImageSplit[1]);
		  //       $im = imagecreatefromstring($ImageResult); 
		  //       if ($im !== false) 
		  //       {
		  //           $fileName = date('Ymdhis') .".png";
		  //           $resp = imagepng($im, $_SERVER['DOCUMENT_ROOT'].'smartedu/upload/'.$fileName);
		  //           imagedestroy($im);
		  //       }  

				$data = array(
				   'FIRSTNAME' => $value['institute_name'],
				);
				$this->db->insert('profile', $data);
				$profile_id=$this->db->insert_id();
				if($profile_id){
					$data1 = array(
					   'CODE' => $value['inst_code'],
					   'PROF_ID' => $profile_id,
					   'TYPE' => $value['type'],
					   'TIME_ZONE' => $value['time_zone'],
					   'CURRENCY' => $value['currency'],
					   'LOGO' => $value['image_file']
					);
					$this->db->insert('institution', $data1);
					$inst_id=$this->db->insert_id();
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'PROFILE_ID'=>$profile_id,'INSTITUTION_ID'=>$inst_id);
				}
			}
		}

		function addInstitutionContactDetails($value){
			if($value['lnstut_id']){
				if($value['location_id']!=''){
					$location= array(
					   'ADDRESS' => $value['address'],
					   'CITY' => $value['city'],
					   'STATE' => $value['state'],
					   'COUNTRY' => $value['country'],
					   'ZIP_CODE' => $value['pincode'],
					);
					$this->db->where('ID', $value['location_id']);
					$this->db->update('location', $location);


					$profile1 = array(
					   'LOCATION_ID' => $value['location_id'],
					   'EMAIL' => $value['email'],
					   'PHONE_NO_1' => $value['phone'],
					   'PHONE_NO_2' => $value['mobile_no'],
					   'FACEBOOK_LINK' => $value['facebook'],
					   'GOOGLE_LINK' => $value['google'],

					);
					$this->db->where('ID', $value['profile_id']);
					$this->db->update('profile', $profile1);

					$ins = array(
					   'CONTACT_PERSON' => $value['contact_name']
					);
					$this->db->where('ID', $value['lnstut_id']);
					$this->db->update('institution', $ins);

					return array('status'=>true, 'message'=>"Record Updated Successfully",'PROFILE_ID'=>$value['profile_id'],'LOCATION_ID'=>$value['location_id']);
				}else {
					$location= array(
					   'ADDRESS' => $value['address'],
					   'CITY' => $value['city'],
					   'STATE' => $value['state'],
					   'COUNTRY' => $value['country'],
					   'ZIP_CODE' => $value['pincode'],
					);
					//print_r($location);exit();
					$this->db->insert('location', $location);
					$locat_id=$this->db->insert_id();
					if($locat_id){
						$profile = array(
						   'LOCATION_ID' => $locat_id,
						   'EMAIL' => $value['email'],
						   'PHONE_NO_1' => $value['phone'],
						   'PHONE_NO_2' => $value['mobile_no'],
						   'FACEBOOK_LINK' => $value['facebook'],
						   'GOOGLE_LINK' => $value['google']
						);
						$this->db->where('ID', $value['profile_id']);
						$this->db->update('profile', $profile);
					}

					$ins = array(
					   'CONTACT_PERSON' => $value['contact_name']
					);
					$this->db->where('ID', $value['lnstut_id']);
					$this->db->update('institution', $ins);

					return array('status'=>true, 'message'=>"Record Inserted Successfully",'PROFILE_ID'=>$value['profile_id'],'LOCATION_ID'=>$locat_id);
				}
			}
		}

		public function addInstitutionDetails($value){
			if($value['instID']!=''){
				$Images=$value['image_file'];
				$ImageSplit = explode(',', $Images);  
				$ImageSplit1 = explode(':', $ImageSplit[0]);
				if($ImageSplit1[0]=='http') {
					$IMG = $value['image_file'];
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
				$data3 = array(
				   'FIRSTNAME' => $value['institute_name'],
				   'LOCATION_ID' => $value['locationID'],
				   'EMAIL' => $value['email'],
				   'PHONE_NO_1' => $value['phone'],
				   'PHONE_NO_2' => $value['mobile_no'],
				   'FACEBOOK_LINK' => $value['facebook'],
				   'GOOGLE_LINK' => $value['google']
				);
				$this->db->where('ID', $value['profileID']);
				$this->db->update('profile', $data3);

				$data1 = array(
				   'CODE' => $value['inst_code'],
				   'PROF_ID' => $value['profileID'],
				   'TYPE' => $value['type'],
				   'TIME_ZONE' => $value['time_zone'],
				   'CURRENCY' => $value['currency'],
				   'LOGO' => $fileName,
				   'FAX' => $value['fax']
				);
				$this->db->where('ID', $value['instID']);
				$this->db->update('institution', $data1);



				$data2 = array(
				   'ADDRESS' => $value['address'],
				   'CITY' => $value['city'],
				   'STATE' => $value['state'],
				   'COUNTRY' => $value['country'],
				   'ZIP_CODE' => $value['pincode'],
				);
				$this->db->where('ID', $value['locationID']);
				$this->db->update('location', $data2);
				return array('status'=>true, 'message'=>"Record Updated Successfully",'PROFILE_ID'=>$value['profileID']);
			}else {
				// echo "insert";exit;
				if($value['profile_id']){
					$data2 = array(
					   'ADDRESS' => $value['address'],
					   'CITY' => $value['city'],
					   'STATE' => $value['state'],
					   'COUNTRY' => $value['country'],
					   'ZIP_CODE' => $value['pincode'],
					);
					$this->db->insert('location', $data2);
					$loc_id=$this->db->insert_id();
					if($loc_id){
						$data3 = array(
						   'LOCATION_ID' => $loc_id,
						   'EMAIL' => $value['email'],
						   'PHONE_NO_1' => $value['phone'],
						   'PHONE_NO_2' => $value['mobile_no'],
						   'FACEBOOK_LINK' => $value['facebook'],
						   'GOOGLE_LINK' => $value['google']
						);
						$this->db->where('ID', $value['profile_id']);
						$this->db->update('profile', $data3);
						return array('status'=>true, 'message'=>"Record Inserted Successfully",'PROFILE_ID'=>$value['profile_id'],'checkStatus'=>true);
					}
				}else {
					$Images=$value['image_file'];
				   	$ImageSplit = explode(',', $Images);        
			        $ImageResult = base64_decode($ImageSplit[1]);
			        $im = imagecreatefromstring($ImageResult); 
			        if ($im !== false) 
			        {
			            $fileName = date('Ymdhis') .".png";
			            $resp = imagepng($im, $_SERVER['DOCUMENT_ROOT'].'smartedu/upload/'.$fileName);
			            imagedestroy($im);
			        }  

					$data = array(
					   'FIRSTNAME' => $value['institute_name'],
					);
					$this->db->insert('profile', $data);
					$profile_id=$this->db->insert_id();
					if($profile_id){
						$data1 = array(
						   'CODE' => $value['inst_code'],
						   'PROF_ID' => $profile_id,
						   'TYPE' => $value['type'],
						   'TIME_ZONE' => $value['time_zone'],
						   'CURRENCY' => $value['currency'],
						   'LOGO' => $fileName,
						   'FAX' => $value['fax']
						);
						$this->db->insert('institution', $data1);
						return array('status'=>true, 'message'=>"Record Inserted Successfully",'PROFILE_ID'=>$profile_id,'checkStatus'=>false);
					}
				}
			}
	    }
	    function getInstitution_details($id){
	    	$sql="SELECT * FROM institution where ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }

	    function getAllInstitution_details(){
	    	$sql="SELECT * FROM institution_view";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function deleteInstitutionData($id,$profile_id){
	    	$sql="DELETE FROM institution WHERE ID='$id'";
	    	$result = $this->db->query($sql);
	    	if($result==1){
	    		$sql="SELECT * FROM profile where ID='$profile_id'";
				$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
				$location_Id=$result1[0]['ID'];
				if($location_Id!=''){
					$sql1="DELETE FROM location where ID='$location_Id'";
					$result1 = $this->db->query($sql1);
					if($result1==1){
						$sql2="DELETE FROM profile where ID='$profile_id'";
						$result2 = $this->db->query($sql2);
						return $this->db->affected_rows();
					}
				}				
			}
	    }

		// Building
		public function addbuildingDetails($value){
			$name=$value['name'];
	    	$sql="SELECT * FROM building WHERE NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			//print_r($result);exit;
			if($result){
				return array('status' => false);
			}else{
				$data = array(
				   'NAME' => $value['name'],
				   'NUMBER' => $value['number'],
				   'LANDMARK' => $value['landmark']
				);
				$this->db->insert('building', $data);
				$build_id=$this->db->insert_id();
				$sql1="SELECT NAME,ID FROM building WHERE ID='$build_id'";
				$result=$this->db->query($sql1, $return_object = TRUE)->result_array();
				if(!empty($result)){
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'BUILDING_NAME'=>$result[0]['NAME'],'BUILDING_ID'=>$result[0]['ID']);
				}
				// return array('status'=>true, 'message'=>"Record Inserted Successfully",'BUILDING_ID'=>$build_id);
			}
			// if($result[0]['count(NAME)']!=0){
			// 	$data = array(
			// 	   'NAME' => $value['name'],
			// 	   'NUMBER' => $value['number'],
			// 	   'LANDMARK' => $value['landmark']
			// 	);
			// 	$this->db->where('ID', $id);
			// 	$this->db->update('building', $data); 
			// 	return array('status'=>true, 'message'=>"Record Updated Successfully",'BUILDING_ID'=>$id);
			// }else {
			// 	$data = array(
			// 	   'NAME' => $value['name'],
			// 	   'NUMBER' => $value['number'],
			// 	   'LANDMARK' => $value['landmark']				
			// 	);
			// 	$this->db->insert('building', $data);
			// 	$build_id=$this->db->insert_id();
			// 	return array('status'=>true, 'message'=>"Record Inserted Successfully",'BUILDING_ID'=>$build_id);
			// }
	    }
	    public function editbuildingDetails($id,$value){
			$sql="SELECT * FROM building where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['name']){
				$data = array(
				   'NAME' => $value['name'],
				   'NUMBER' => $value['number'],
				   'LANDMARK' => $value['landmark']
				);
				$this->db->where('ID', $id);
				$this->db->update('building', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'DEPT_ID'=>$id);
			}else {
				$name=$value['name'];
				$sql="SELECT * FROM building where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
				   'NAME' => $value['name'],
				   'NUMBER' => $value['number'],
				   'LANDMARK' => $value['landmark']
				);
					$this->db->where('ID', $id);
					$this->db->update('building', $data); 
					return array('status'=>true, 'message'=>"Record Updated Successfully",'DEPT_ID'=>$id);
				}
			}
		}
	    function getBuilding_details($id){
	    	$sql="SELECT * FROM building where ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }

	    function getAllBuilding_details(){
	    	$sql="SELECT * FROM building";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    public function checkBuildingDetails($id){
			$sql="SELECT ID,(SELECT BUILDING_ID FROM block WHERE BUILDING_ID=building.ID GROUP BY BUILDING_ID)AS block_data,(SELECT BUILDING_ID FROM room WHERE BUILDING_ID=building.ID GROUP BY BUILDING_ID)AS room_data,(SELECT BUILDING_ID FROM hostel WHERE BUILDING_ID=building.ID GROUP BY BUILDING_ID)AS hostel_data FROM building where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();

			if(isset($result[0]['block_data'])){
				$status = array('status' => 0, 'message' =>'Building details are assigned to block');
				return $status;
			}else if(isset($result[0]['room_data'])){
				$status= array('status' => 0,'message' =>'Building details are assigned to route room');
				return $status;
			}else if(isset($result[0]['hostel_data'])){
				$status= array('status' => 0,'message' =>'Building details are assigned to route hostel');
				return $status;
			}
			else{
				$status = array('status' => 1);
				return $status;
			}
		}
	    function deleteBuildingData($id){
			$sql="SELECT * FROM hostel where BUILDING_ID ='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return 0;
			}else{
				$sql="DELETE FROM building WHERE ID='$id'";
				$result = $this->db->query($sql);
				return $this->db->affected_rows();
			}
	    }

		// Block
	    public function addBlockDetails($value){
			$name=$value['name'];
	    	$sql="SELECT * FROM block WHERE NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status' =>false );
			}else{
				$data = array(
				   'NAME' => $value['name'],
				   'NUMBER' => $value['number'],
				   'BUILDING_ID' => $value['building_id']				
				);
				$this->db->insert('block',$data);
				$block_id=$this->db->insert_id();
				$sql1="SELECT NAME,ID FROM block WHERE ID='$block_id'";
				$result=$this->db->query($sql1, $return_object = TRUE)->result_array();
				if(!empty($result)){
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'BLOCK_NAME'=>$result[0]['NAME'],'BLOCK_ID'=>$result[0]['ID']);
				}
				//return array('status' => true,'message' =>"Record Inserted Successfully",'BLOCK_ID'=>$block_id);
			}
			// if($result[0]['count(NAME)']!=0){
			// 	$data = array(
			// 	   'NAME' => $value['name'],
			// 	   'NUMBER' => $value['number'],
			// 	   'BUILDING_ID' => $value['building_id']				
			// 	);
			// 	$this->db->where('ID', $id);
			// 	$this->db->update('block', $data); 
			// 	return array('status'=>true, 'message'=>"Record Updated Successfully",'BLOCK_ID'=>$id);
			// }else {
			// 	$data = array(
			// 	   'NAME' => $value['name'],
			// 	   'NUMBER' => $value['number'],
			// 	   'BUILDING_ID' => $value['building_id']				
			// 	);
			// 	$this->db->insert('block', $data);
			// 	$block_id=$this->db->insert_id();
			// 	return array('status'=>true, 'message'=>"Record Inserted Successfully",'BLOCK_ID'=>$block_id);
			// }
	    }
	    function editBlockDetails($id,$value){
			$sql="SELECT * FROM block where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['name']){
				$data = array(
				   'NAME' => $value['name'],
				   'NUMBER' => $value['number'],
				   'BUILDING_ID' => $value['building_id']				
				);
				$this->db->where('ID', $id);
				$this->db->update('block', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'BLOCK_ID'=>$id);
			}else {
				$name=$value['name'];
				$sql="SELECT * FROM block where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
				   'NAME' => $value['name'],
				   'NUMBER' => $value['number'],
				   'BUILDING_ID' => $value['building_id']				
				);
					$this->db->where('ID', $id);
					$this->db->update('block', $data); 
					return array('status'=>true, 'message'=>"Record Updated Successfully",'BLOCK_ID'=>$id);
				}
			}
		}
	    function getBlock_details($id){
	    	//print_r($id);exit();
	    	$sql="SELECT * FROM block where BUILDING_ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			
	    }
	    function checkBlockDetails($id){
	    	
			$sql="SELECT BLOCK_ID FROM room WHERE BLOCK_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			//print_r($result);exit();
			if(isset($result[0])){
				$status= array('status' => 0,'message' =>'Block details are assigned to room');
				return $status;
			}else{
				$status = array('status' => 1);
				return $status;
			}
	    }

	    function getAllBlock_details(){
	    	$sql="SELECT * FROM block_view";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function deleteBlockData($id){
			$sql="SELECT * FROM h_allocation where BLOCK_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return 0;
			}else{
				$sql="DELETE FROM block WHERE ID='$id'";
				$result = $this->db->query($sql);
				return $this->db->affected_rows();
			}
	    }


	    // Room
	    public function addRoomDetails($value){
			$name=$value['name'];
	    	$sql="SELECT * FROM room WHERE NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status' =>false );
			}else{
				$data = array(
				   'NAME' => $value['name'],
				   // 'NUMBER' => $value['number'],
				   'FLOOR' => $value['floor'],
				   'BLOCK_ID' => $value['block_id'],
				   'BUILDING_ID' => $value['building_id'],			
				   'INFO' => $value['info'],
				);
				$this->db->insert('room', $data);
				$room_id=$this->db->insert_id();
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'ROOM_ID'=>$room_id);
			}
			// if($result[0]['count(NAME)']!=0){
			// 	$data = array(
			// 	   'NAME' => $value['name'],
			// 	   'NUMBER' => $value['number'],
			// 	   'FLOOR' => $value['floor'],
			// 	   'BLOCK_ID' => $value['block_id'],
			// 	   'BUILDING_ID' => $value['building_id'],			
			// 	   'INFO' => $value['info'],
			// 	);
			// 	$this->db->where('ID', $id);
			// 	$this->db->update('room', $data); 
			// 	return array('status'=>true, 'message'=>"Record Updated Successfully",'ROOM_ID'=>$id);
			// }else {
			// 	$data = array(
			// 	   'NAME' => $value['name'],
			// 	   'NUMBER' => $value['number'],
			// 	   'FLOOR' => $value['floor'],
			// 	   'BLOCK_ID' => $value['block_id'],
			// 	   'BUILDING_ID' => $value['building_id'],			
			// 	   'INFO' => $value['info'],
			// 	);
			// 	$this->db->insert('room', $data);
			// 	$room_id=$this->db->insert_id();
			// 	return array('status'=>true, 'message'=>"Record Inserted Successfully",'ROOM_ID'=>$room_id);
			// }
	    }
	    function editRoomDetailss($id,$value){
			$sql="SELECT * FROM room where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['NAME']==$value['name']){
				$data = array(
				   'NAME' => $value['name'],
				   // 'NUMBER' => $value['number'],
				   'FLOOR' => $value['floor'],
				   'BLOCK_ID' => $value['block_id'],
				   'BUILDING_ID' => $value['building_id'],			
				   'INFO' => $value['info'],
				);
				$this->db->where('ID', $id);
				$this->db->update('room', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'ROOM_ID'=>$id);
			}else {
				$name=$value['name'];
				$sql="SELECT * FROM room where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
				   'NAME' => $value['name'],
				   // 'NUMBER' => $value['number'],
				   'FLOOR' => $value['floor'],
				   'BLOCK_ID' => $value['block_id'],
				   'BUILDING_ID' => $value['building_id'],			
				   'INFO' => $value['info'],
				);
				$this->db->where('ID', $id);
				$this->db->update('room', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'ROOM_ID'=>$id);
				}
			}
		}
	    function getRoom_details($id){
	    	$sql="SELECT * FROM room where ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }

	    function getAllRoom_details(){
	    	$sql="SELECT * FROM room_view";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function checkRoom_details($id){
	    	$sql="SELECT ID,(SELECT ROOM_ID FROM department where ROOM_ID=room.ID GROUP BY ROOM_ID)as dept_data,(SELECT ROOM_ID FROM h_allocation where ROOM_ID=room.ID GROUP BY ROOM_ID)as h_alloc_data FROM room where ID='$id'";
			// $sql="SELECT * FROM h_allocation where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			//print_r($result);exit();
			if(isset($result[0]['dept_data'])){
				$status = array('status' => 0, 'message'=>"Room details are assigned to department");
				return $status;
			}
			else if(isset($result[0]['h_alloc_data'])){
				$status = array('status' => 0, 'message'=>"Room details are assigned to hostel allocation");
				return $status;
			}
			else{
				$status = array('status' => 1);
				return $status;
	    		
			}
			
	    }
	    function deleteRoomData($id){
	    	$sql="DELETE FROM room WHERE ID='$id'";
			$result = $this->db->query($sql);
			return $this->db->affected_rows();
		}

	    // Common Data
	    function fetchInstituteType_details(){
	    	$sql="SELECT * FROM institute_type";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function fetchTimeZone_details(){
	    	$sql="SELECT * FROM time_zone";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function fetchCurrency_details(){
	    	$sql="SELECT * FROM currency";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function fetchTotalCountry(){
	    	$sql="SELECT * FROM country";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function getEmployeeData(){
	    	$sql="SELECT * FROM profile";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function fetchLocationDetails(){
	    	$sql="SELECT * FROM location";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }

	    function fetchInstitutionDetails(){
	   //  	$sql="SELECT 
				// institution.ID,institution.CODE,institution.TYPE,institution.PROF_ID,institution.TIME_ZONE,institution.CURRENCY,
				// institution.LOGO,institution.CONTACT_PERSON
				// ,institution.TYPE,institution.PROF_ID,institution.TIME_ZONE,institution.CURRENCY,
				// PROFILE.FIRSTNAME AS INST_NAME,PROFILE.EMAIL,PROFILE.PHONE_NO_1,PROFILE.PHONE_NO_2,
				// PROFILE.GOOGLE_LINK,PROFILE.FACEBOOK_LINK,PROFILE.LOCATION_ID,
				// LOCATION.ADDRESS,LOCATION.CITY,LOCATION.STATE,LOCATION.ZIP_CODE,LOCATION.COUNTRY,
				// country.ID AS COUNTRY_ID,country.NAME AS COUNTRY_NAME
				// FROM institution 
				// JOIN PROFILE ON institution.PROF_ID=PROFILE.ID
				// JOIN LOCATION ON PROFILE.LOCATION_ID=LOCATION.ID
				// JOIN country ON LOCATION.COUNTRY=country.ID";
	    	$sql="SELECT institution.ID,institution.CODE,institution.TYPE,institution.PROF_ID,institution.TIME_ZONE,institution.CURRENCY,institution.LOGO,institution.CONTACT_PERSON,institution.TYPE,institution.PROF_ID,institution.TIME_ZONE,institution.CURRENCY,(SELECT FIRSTNAME FROM PROFILE WHERE ID=institution.PROF_ID) AS INST_NAME,
				(SELECT EMAIL FROM PROFILE WHERE ID=institution.PROF_ID) AS EMAIL,
				(SELECT PHONE_NO_1 FROM PROFILE WHERE ID=institution.PROF_ID) AS PHONE_NO_1,
				(SELECT PHONE_NO_2 FROM PROFILE WHERE ID=institution.PROF_ID) AS PHONE_NO_2,
				(SELECT GOOGLE_LINK FROM PROFILE WHERE ID=institution.PROF_ID) AS GOOGLE_LINK,
				(SELECT FACEBOOK_LINK FROM PROFILE WHERE ID=institution.PROF_ID) AS FACEBOOK_LINK,
				(SELECT LINKEDIN_LINK FROM PROFILE WHERE ID=institution.PROF_ID) AS LINKEDIN_LINK,
				(SELECT LOCATION_ID FROM PROFILE WHERE ID=institution.PROF_ID) AS LOCATION_ID,
				(SELECT ADDRESS FROM LOCATION WHERE ID=LOCATION_ID) AS ADDRESS,
				(SELECT CITY FROM LOCATION WHERE ID=LOCATION_ID) AS CITY,
				(SELECT STATE FROM LOCATION WHERE ID=LOCATION_ID) AS STATE,
				(SELECT ZIP_CODE FROM LOCATION WHERE ID=LOCATION_ID) AS ZIP_CODE,
				(SELECT COUNTRY FROM LOCATION WHERE ID=LOCATION_ID) AS COUNTRY,
				(SELECT ID FROM country WHERE ID=COUNTRY) AS COUNTRY_ID,
				(SELECT NAME FROM country WHERE ID=COUNTRY) AS COUNTRY_NAME,
				(SELECT NAME FROM currency WHERE ID=CURRENCY) AS CURRENCY_NAME,
				(SELECT NAME FROM time_zone WHERE ID=TIME_ZONE) AS TIME_ZONE_NAME,
				(SELECT NAME FROM institute_type WHERE ID=TYPE) AS TYPE_NAME
				FROM institution";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    public function checkInstitutionName($no){
			// if($id){
			// 	$sql="SELECT FIRSTNAME FROM institution where FIRSTNAME='$no'";
			// 	$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// 	if($result){
			// 		$sql="SELECT FIRSTNAME FROM institution where ID='$id'";
			// 		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// 		$admNo=$result[0]['FIRSTNAME'];
			// 		if($admNo==$no){
			// 			return false;
			// 		}else{
			// 			return true;
			// 		}
			// 	}else{
			// 		return false;
			// 	}
			// }else{
			// 	$sql="SELECT FIRSTNAME FROM institution where FIRSTNAME='$no'";
			// 	$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// 	if($result){
			// 		return true;
			// 	}else{
			// 		return false;
			// 	}
			// }
		}
		
		public function fetchBlockDetails($id){
			$sql="SELECT * FROM block where BUILDING_ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function fetchAllBlockDetails(){
			$sql="SELECT * FROM block";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function fetchRoomDetails($id){
			$sql="SELECT * FROM room where BLOCK_ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function fetchAllRoomDetails(){
			$sql="SELECT * FROM room";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
	}
?>
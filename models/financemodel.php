<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class financemodel extends CI_Model {

		// Department Details 
		
		public function addFeeItem($value){
			// print_r($value);exit;
			$name=$value['NAME'];
			$sql="SELECT * FROM FEEITEM where NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['NAME'],
				   'DESCRIPTION' => $value['DESC']
				);
				$this->db->insert('FEEITEM', $data); 
				$item_id= $this->db->insert_id();
				if(!empty($item_id)){
					// return true;
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'ITEM_ID'=>$item_id);
				}
			}
	    }	
		
		public function editFeeItem($id,$value){

			$sql="SELECT * FROM FEEITEM where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['NAME']){
				$data = array(
				   'NAME' => $value['NAME'],
				   'DESCRIPTION' => $value['DESC']
				);
				$this->db->where('ID', $id);
				$this->db->update('FEEITEM', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'ITEM_ID'=>$id);
			}else {
				$name=$value['NAME'];
				$sql="SELECT * FROM FEEITEM where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
					   'NAME' => $value['NAME'],
					   'DESCRIPTION' => $value['DESC']
					);
					$this->db->where('ID', $id);	
					$this->db->update('FEEITEM', $data); 
					return array('status'=>true, 'message'=>"Record Updated Successfully",'PAYITEM_ID'=>$id);
				}
			}
		}
		
		public function getFeeItemDetails(){
			$sql="SELECT * FROM FEEITEM";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getDepartmentDetails($id){
			$sql="SELECT * FROM FEEITEM where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		function checkFeeItemAvailableorNot($id){
			$sql="SELECT * FROM fee_item_structure where FEE_ITEM_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function checkFineAvailableorNot($id){
			$sql="SELECT * FROM fee_item_structure where FEE_FINE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function checkfeeStructureAvailableorNot($id){
			$sql="SELECT * FROM student_fee where FEE_STRUCTURE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		public function deleteFeeItem($id){
			$sql="DELETE FROM FEEITEM where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}

		function getStudentList(){
			// $sql="SELECT PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS ADMISSION_NO FROM student_profile WHERE FEE_STRUCTURE_ID=''";
			$sql="SELECT PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS ADMISSION_NO FROM student_profile";
			return  $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getFeeFineList(){
			$sql="SELECT * FROM feefine";
			$result=$this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$fineId=$value['ID'];
				$sql1="SELECT ID,FEE_FINE_ID,DUE_DATE,VALUE,MODE,count(FEE_FINE_ID) as total_count FROM feefine_slabs where FEE_FINE_ID='$fineId'";
				$result[$key]['fine_slobs']=$this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;
		}
		function getParticularFineDetails($id){
			$sql="SELECT * FROM feefine where ID='$id'";
			$result=$this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$fineId=$value['ID'];
				$sql1="SELECT * FROM feefine_slabs where FEE_FINE_ID='$fineId'";
				$result[$key]['fine_slobs']=$this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;
		}
		function addFine($value){
			//print_r($value);exit;
			$name=$value['name'];
			$sql="SELECT * FROM feefine where NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['name']
				);
				$this->db->insert('feefine', $data); 
				$fine_id= $this->db->insert_id();
				if(!empty($fine_id)){
					for($i=0;$i<count($value['item']);$i++){
						$fine = array(
						   'FEE_FINE_ID' => $fine_id,
						   'DUE_DATE' => $value['item'][$i]['Days_After'],
						   'VALUE' => $value['item'][$i]['Fine_Value'],
						   'MODE' => $value['item'][$i]['mode']
						);  
						$this->db->insert('feefine_slabs', $fine);
					}
					return array('status'=>true, 'message'=>"Record Inserted Successfully");
				}
			}
		}
		public function editFine($id,$value){
			//print_r($value);exit;
			$sql="SELECT * FROM feefine where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['NAME']==$value['name']){
				$data = array(
				   'NAME' => $value['name']
				);
				$this->db->where('ID', $id);
				$this->db->update('feefine', $data);

				for($i=0;$i<count($value['item']);$i++){
					if(isset($value['item'][$i]['ID'])){
						$fine = array(
						   'FEE_FINE_ID' => $value['item'][$i]['FEE_FINE_ID'],
						   'DUE_DATE' => $value['item'][$i]['DUE_DATE'],
						   'VALUE' => $value['item'][$i]['VALUE'],
						   'MODE' => $value['item'][$i]['MODE']
						);
						$this->db->where('ID', $value['item'][$i]['ID']);
						$this->db->update('feefine_slabs', $fine);
					}else {
						$fine = array(
						   'FEE_FINE_ID' => $id,
						   'DUE_DATE' => $value['item'][$i]['DUE_DATE'],
						   'VALUE' => $value['item'][$i]['VALUE'],
						   'MODE' => $value['item'][$i]['MODE']
						);
						$this->db->insert('feefine_slabs', $fine);
					}
				}
				return array('status'=>true, 'message'=>"Record Updated Successfully");
			}else {
				$name=$value['name'];
				$sql="SELECT * FROM feefine where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
					   'NAME' => $value['name']
					);
					$this->db->where('ID', $id);	
					$this->db->update('feefine', $data);
					for($i=0;$i<count($value['item']);$i++){
						if(isset($value['item'][$i]['ID'])){
							$fine = array(
							   'FEE_FINE_ID' => $value['item'][$i]['FEE_FINE_ID'],
							   'DUE_DATE' => $value['item'][$i]['DUE_DATE'],
							   'VALUE' => $value['item'][$i]['VALUE'],
							   'MODE' => $value['item'][$i]['MODE']
							);
							$this->db->where('ID', $value['item'][$i]['ID']);
							$this->db->update('feefine_slabs', $fine);
						}else {
							$fine = array(
							   'FEE_FINE_ID' => $id,
							   'DUE_DATE' => $value['item'][$i]['DUE_DATE'],
							   'VALUE' => $value['item'][$i]['VALUE'],
							   'MODE' => $value['item'][$i]['MODE']
							);
							$this->db->insert('feefine_slabs', $fine);
						}
					}
					return array('status'=>true, 'message'=>"Record Updated Successfully");
				}
			}
		}
		function deleteFeefineItem($id){
			$sql="DELETE FROM feefine_slabs where ID='$id'";
			$result = $this->db->query($sql);			
	    	return $this->db->affected_rows();
		}
		function deletefineItem($id){
			$sql="SELECT * FROM feefine_slabs where FEE_FINE_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $value) {
				$fineId=$value['ID'];
				$sql1="DELETE FROM feefine_slabs where ID='$fineId'";
				$result1 = $this->db->query($sql1);
			}
			$sql2="DELETE FROM feefine where ID='$id'";
			$result1 = $this->db->query($sql2);
	    	return $this->db->affected_rows();
		}

		function addFeeStructure($value){
			//print_r($value);exit;
			$name=$value['stru_name'];
			$sql="SELECT * FROM fee_structure where NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['stru_name']
				);
				$this->db->insert('fee_structure', $data); 
				$stru_id= $this->db->insert_id();
				if(!empty($stru_id)){
					// for($i=0;$i<count($value['studentData']);$i++){
					// 	if(isset($value['studentData'][$i])){
					// 		$STD_DATA = array(
					// 		   'COURSE_ID' => $value['coursedata'],
					// 		   'BATCH_ID' => $value['batch_id'],
					// 		   'STUDENT_PROFILE_ID' => $value['studentData'][$i],
					// 		   'FEE_STRUCTURE_ID' => $stru_id
					// 		);
					// 		$this->db->insert('student_fee', $STD_DATA);
					// 	}

					// }					
					for($i=0;$i<count($value['fee_data']);$i++){
						$data = array(
						   'FEE_ITEM_ID' => $value['fee_data'][$i]['feeitem'],
						   'FEE_FINE_ID' => $value['fee_data'][$i]['feefine'],
						   'FEE_STRUCTURE_ID' => $stru_id,
						   'DUE_DATE' => $value['fee_data'][$i]['due_date'],
						   'AMOUNT' => $value['fee_data'][$i]['amount'],
						   'FREQUENCY' => $value['fee_data'][$i]['frequency']
						);
						$this->db->insert('fee_item_structure', $data);
					}
					return array('status'=>true, 'message'=>"Record Inserted Successfully");
				}
			}
		}

		public function editFeeStructure($id,$value){
			// print_r($value);exit;
			$data = array(
			   'NAME' => $value['stru_name']
			);
			$this->db->where('ID', $id);
			$this->db->update('fee_structure', $data);

			// if($value['assign_to']=='Batch'){
			// 	for($i=0;$i<count($value['studentData']);$i++){
			// 		$stu_prof = array(
			// 		   'FEE_STRUCTURE_ID' => $id
			// 		);
			// 		$this->db->where('ID', $value['studentData'][$i]);
			// 		$this->db->update('student_profile', $stu_prof);
			// 	}
			// }else {
			// 	$stu_prof = array(
			// 	   'FEE_STRUCTURE_ID' => $id
			// 	);
			// 	$this->db->where('ID', $value['student_id']);
			// 	$this->db->update('student_profile', $stu_prof);
			// }

			for($i=0;$i<count($value['fee_data']);$i++){
				if(isset($value['fee_data'][$i]['ID'])){
					$data1 = array(
					   'FEE_ITEM_ID' => $value['fee_data'][$i]['FEE_ITEM_ID'],
					   'FEE_FINE_ID' => $value['fee_data'][$i]['FEE_FINE_ID'],
					   'FEE_STRUCTURE_ID' => $id,
					   'DUE_DATE' => $value['fee_data'][$i]['DUE_DATE'],
					   'AMOUNT' => $value['fee_data'][$i]['AMOUNT'],
					   'FREQUENCY' => $value['fee_data'][$i]['FREQUENCY']
					);
					$this->db->where('ID', $value['fee_data'][$i]['ID']);
					$this->db->update('fee_item_structure', $data1);
				}else {
					$data1 = array(
					   'FEE_ITEM_ID' => $value['fee_data'][$i]['FEE_ITEM_ID'],
					   'FEE_FINE_ID' => $value['fee_data'][$i]['FEE_FINE_ID'],
					   'FEE_STRUCTURE_ID' => $id,
					   'DUE_DATE' => $value['fee_data'][$i]['DUE_DATE'],
					   'AMOUNT' => $value['fee_data'][$i]['AMOUNT'],
					   'FREQUENCY' => $value['fee_data'][$i]['FREQUENCY']
					);
					$this->db->insert('fee_item_structure', $data1);
				}					
			}
			return array('status'=>true, 'message'=>"Record Updated Successfully");			
		}

		function getFeeStructureDetails(){
			$sql="SELECT ID,NAME,(SELECT COUNT(FEE_STRUCTURE_ID) FROM fee_item_structure WHERE FEE_STRUCTURE_ID=fee_structure.ID) AS ITEM_COUNT FROM fee_structure";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getParticularFeeStructure($id){
			$sql="SELECT ID,NAME,(SELECT COUNT(FEE_STRUCTURE_ID) FROM fee_item_structure WHERE FEE_STRUCTURE_ID=fee_structure.ID) AS ITEM_COUNT FROM fee_structure where ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getFeeStructurebasedonprofileDetails($profid){
			$sql="SELECT fee_structure.ID,fee_structure.NAME FROM
				student_fee inner join fee_structure on student_fee.FEE_STRUCTURE_ID=fee_structure.ID
				where student_fee.STUDENT_PROFILE_ID='$profid'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchcourseDetails(){
			$sql="SELECT COURSE_ID,(SELECT NAME FROM course WHERE ID=COURSE_ID) as COUSE_NAME FROM `course_batch` group by COURSE_ID";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getStudentListbasedon_course($courseID){
			$sql="SELECT ID,COURSE_ID FROM course_batch where COURSE_ID='$courseID'";
			$result=$this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
			foreach ($result as $key => $value) {
				$id=$value['ID'];
				$sql1="SELECT PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) AS STUDENT_NAME FROM student_profile where COURSEBATCH_ID='$id'";
				$result[$key]['studentlist']=$this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;
		}
		function getBatchListbasedoncourse($id){
			$sql="SELECT * FROM course_batch where COURSE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getBatchNamebasedoncourse($id){
			$sql="SELECT course_batch.ID,course_batch.NAME FROM student_fee INNER JOIN course_batch on student_fee.BATCH_ID=course_batch.ID where student_fee.COURSE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getStudentListbasedon_batch($batchid){
			// $sql="SELECT PROFILE_ID,FEE_STRUCTURE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) AS STUDENT_NAME FROM student_profile where COURSEBATCH_ID='$batchid' AND FEE_STRUCTURE_ID=''";
			$sql="SELECT PROFILE_ID,FEE_STRUCTURE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) AS STUDENT_NAME FROM student_profile where COURSEBATCH_ID='$batchid'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getFeeStructureViewDetails($id){

			$sql="SELECT ID,FEE_STRUCTURE_ID,STUDENT_PROFILE_ID,(SELECT NAME FROM fee_structure WHERE ID=FEE_STRUCTURE_ID) AS STRUCTURE_NAME,(SELECT NAME FROM course_batch WHERE ID=BATCH_ID) AS BATCH_NAME,(SELECT NAME FROM course WHERE ID=COURSE_ID) AS COURSE_NAME FROM student_fee where FEE_STRUCTURE_ID='$id' group by FEE_STRUCTURE_ID";
			$result=$this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$Str_id=$value['FEE_STRUCTURE_ID'];
				$sql1="SELECT ID,AMOUNT,DUE_DATE,FREQUENCY,(SELECT NAME FROM feeitem WHERE ID=fee_item_structure.FEE_ITEM_ID) ITEM_NAME,(SELECT NAME FROM feefine WHERE ID=fee_item_structure.FEE_FINE_ID) FINE_NAME FROM fee_item_structure where FEE_STRUCTURE_ID='$Str_id'";
				$result[$key]['fee_item'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;

			// $sql11="SELECT * FROM fee_structure where ID='$id'";
			// $checkAssign = $this->db->query($sql11, $return_object = TRUE)->result_array();
			// // print_r($checkAssign);exit;
			// if($checkAssign){
			// 	$stID=$checkAssign[0]['ASSIGN_TO'];
			// 	if($stID=='Student'){
			// 		// $sql="SELECT ID,NAME,ASSIGN_TO,(SELECT COURSEBATCH_ID FROM student_profile where FEE_STRUCTURE_ID=fee_structure.ID GROUP BY FEE_STRUCTURE_ID) AS BATCHID,(SELECT COURSE_ID FROM course_batch where ID=BATCHID) AS COUSREID,(SELECT NAME FROM course_batch where ID=BATCHID) AS BATCH_NAME,(SELECT NAME FROM course where ID=COUSREID) AS COUSRE_NAME,(SELECT ID FROM student_profile where FEE_STRUCTURE_ID=fee_structure.ID) AS STD_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=STD_ID) AS STUDENT_NAME FROM fee_structure where ID='$id'";
			// 		$sql="SELECT ID,FEE_STRUCTURE_ID,STUDENT_PROFILE_ID,
			// 			(SELECT NAME FROM fee_structure WHERE ID=FEE_STRUCTURE_ID) AS STRUCTURE_NAME,
			// 			(SELECT ASSIGN_TO FROM fee_structure WHERE ID=FEE_STRUCTURE_ID) AS ASSIGN_TO,
			// 			(SELECT COURSEBATCH_ID FROM student_profile WHERE PROFILE_ID=STUDENT_PROFILE_ID) AS BATCHID,
			// 			(SELECT COURSE_ID FROM course_batch WHERE ID=BATCHID) AS COURSEID,
			// 			(SELECT NAME FROM course_batch WHERE ID=BATCHID) AS BATCH_NAME,
			// 			(SELECT NAME FROM course WHERE ID=COURSEID) AS COURSE_NAME,
			// 			(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=STUDENT_PROFILE_ID) AS STUDENT_NAME
			// 			FROM student_fee where FEE_STRUCTURE_ID='$id' group by FEE_STRUCTURE_ID";
			// 		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// 	}else {
			// 		// $sql="SELECT ID,NAME,ASSIGN_TO,(SELECT COURSEBATCH_ID FROM student_profile where FEE_STRUCTURE_ID=fee_structure.ID GROUP BY FEE_STRUCTURE_ID) AS BATCHID,(SELECT COURSE_ID FROM course_batch where ID=BATCHID) AS COUSREID,(SELECT NAME FROM course_batch where ID=BATCHID) AS BATCH_NAME,(SELECT NAME FROM course where ID=COUSREID) AS COUSRE_NAME FROM fee_structure where ID='$id'";

			// 		$sql="SELECT ID,FEE_STRUCTURE_ID,STUDENT_PROFILE_ID,
			// 			(SELECT NAME FROM fee_structure WHERE ID=FEE_STRUCTURE_ID) AS STRUCTURE_NAME,
			// 			(SELECT ASSIGN_TO FROM fee_structure WHERE ID=FEE_STRUCTURE_ID) AS ASSIGN_TO,
			// 			(SELECT COURSEBATCH_ID FROM student_profile WHERE PROFILE_ID=STUDENT_PROFILE_ID) AS BATCHID,
			// 			(SELECT COURSE_ID FROM course_batch WHERE ID=BATCHID) AS COURSEID,
			// 			(SELECT NAME FROM course_batch WHERE ID=BATCHID) AS BATCH_NAME,
			// 			(SELECT NAME FROM course WHERE ID=COURSEID) AS COURSE_NAME
			// 			FROM student_fee where FEE_STRUCTURE_ID='$id' group by FEE_STRUCTURE_ID";
			// 		$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// 	}
			// 	foreach ($result as $key => $value) {
			// 		$Str_id=$value['ID'];
			// 		$sql1="SELECT ID,AMOUNT,DUE_DATE,FREQUENCY,(SELECT NAME FROM feeitem WHERE ID=fee_item_structure.FEE_ITEM_ID) ITEM_NAME,(SELECT NAME FROM feefine WHERE ID=fee_item_structure.FEE_FINE_ID) FINE_NAME FROM fee_item_structure where FEE_STRUCTURE_ID='$Str_id'";
			// 		$result[$key]['fee_item'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// 	}
			// 	return $result;
			//}
			// exit;
			// $sql="SELECT ID,NAME,ASSIGN_TO,(SELECT COURSEBATCH_ID FROM student_profile where FEE_STRUCTURE_ID=fee_structure.ID GROUP BY FEE_STRUCTURE_ID) AS BATCHID,(SELECT COURSE_ID FROM course_batch where ID=BATCHID) AS COUSREID,(SELECT NAME FROM course_batch where ID=BATCHID) AS BATCH_NAME,(SELECT NAME FROM course where ID=COUSREID) AS COUSRE_NAME FROM fee_structure where ID='$id'";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// foreach ($result as $key => $value) {
			// 	$Str_id=$value['ID'];
			// 	$sql1="SELECT ID,AMOUNT,DUE_DATE,FREQUENCY,(SELECT NAME FROM feeitem WHERE ID=fee_item_structure.FEE_ITEM_ID) ITEM_NAME,(SELECT NAME FROM feefine WHERE ID=fee_item_structure.FEE_FINE_ID) FINE_NAME FROM fee_item_structure where FEE_STRUCTURE_ID='$Str_id'";
			// 	$result[$key]['fee_item'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// }
			// return $result;
		}
		function getFeeStructureDataforEdit($id){

			// $sql="SELECT ID,NAME,ASSIGN_TO,(SELECT COURSEBATCH_ID FROM student_profile where FEE_STRUCTURE_ID=fee_structure.ID GROUP BY FEE_STRUCTURE_ID) AS BATCHID,(SELECT COURSE_ID FROM course_batch where ID=BATCHID) AS COUSREID,(SELECT NAME FROM course_batch where ID=BATCHID) AS BATCH_NAME,(SELECT NAME FROM course where ID=COUSREID) AS COUSRE_NAME FROM fee_structure where ID='$id'";

			// MODIFIED ON 22-05-17
			// $sql="SELECT ID,FEE_STRUCTURE_ID,STUDENT_PROFILE_ID,
			// 	(SELECT NAME FROM fee_structure WHERE ID=FEE_STRUCTURE_ID) AS STRUCTURE_NAME,
			// 	(SELECT ASSIGN_TO FROM fee_structure WHERE ID=FEE_STRUCTURE_ID) AS ASSIGN_TO,
			// 	(SELECT COURSEBATCH_ID FROM student_profile WHERE PROFILE_ID=STUDENT_PROFILE_ID) AS BATCHID,
			// 	(SELECT COURSE_ID FROM course_batch WHERE ID=BATCHID) AS COURSEID,
			// 	(SELECT NAME FROM course_batch WHERE ID=BATCHID) AS BATCH_NAME,
			// 	(SELECT NAME FROM course WHERE ID=COURSEID) AS BATCH_NAME
			// 	FROM student_fee where FEE_STRUCTURE_ID='$id' group by FEE_STRUCTURE_ID";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();

			$sql="SELECT * FROM fee_structure where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$Str_id=$value['ID'];
				$sql1="SELECT * FROM fee_item_structure where FEE_STRUCTURE_ID='$Str_id'";
				$result[$key]['fee_item'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;
		}
		function deleteFeeStructure($id){
			$sql="DELETE FROM fee_structure where ID='$id'";
			$result = $this->db->query($sql);
			$sql1="SELECT * FROM fee_item_structure where FEE_STRUCTURE_ID='$id'";
			$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
			foreach ($result1 as $key => $value) {
				$itemID=$value['ID'];
				$sql2="DELETE FROM fee_item_structure where ID='$itemID'";
				$result2 = $this->db->query($sql2);
			}
	    	return $this->db->affected_rows();
		}
		function deleteParticularFeeStructure($id){
			$sql="DELETE FROM fee_item_structure where ID='$id'";
			$result = $this->db->query($sql);
			return $this->db->affected_rows();
		}
		function getStudentFeeStructure($id){
			// $sql="SELECT PROFILE_ID,FEE_STRUCTURE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID) AS ADM_NO,(SELECT NAME FROM course_batch where ID=17) AS BATCH_NAME,(SELECT COURSE_ID FROM course_batch where ID='$id') AS COURSEID,(SELECT NAME FROM COURSE where ID=COURSEID) AS COURSE_NAME FROM student_profile where COURSEBATCH_ID='$id' and FEE_STRUCTURE_ID !=''";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// foreach ($result as $key => $value) {
			// 	$Str_id=$value['FEE_STRUCTURE_ID'];
			// 	$sql1="SELECT SUM(AMOUNT) as total_fee FROM fee_item_structure where FEE_STRUCTURE_ID='$Str_id'";
			// 	$result[$key]['total_amount'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// }
			// return $result;

			// MODIFIED ON 25-05-17

			// $sql="SELECT P.ID AS PROFILE_ID,concat(P.FIRSTNAME,' ',P.LASTNAME) AS STUDENT_NAME,P.ADMISSION_NO,P.IMAGE1 AS IMAGE,C.NAME AS COURSE_NAME,B.NAME AS BATCH_NAME from course_batch as B INNER JOIN course as C ON B.COURSE_ID = C.ID INNER JOIN student_profile as SP ON SP.COURSEBATCH_ID = B.ID INNER JOIN profile as P ON SP.PROFILE_ID=P.ID WHERE B.ID='$id'";.

			// $sql="SELECT student_fee.ID,student_fee.STUDENT_PROFILE_ID,course_batch.NAME AS BATCH_NAME,COURSE.NAME AS COURSE_NAME,
			// 	CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS STUDENT_NAME,PROFILE.ADMISSION_NO
			// 	FROM student_fee
			// 	INNER JOIN PROFILE ON student_fee.STUDENT_PROFILE_ID=PROFILE.ID
			// 	INNER JOIN COURSE ON student_fee.COURSE_ID=COURSE.ID
			// 	INNER JOIN course_batch ON student_fee.BATCH_ID=course_batch.ID
			// 	WHERE student_fee.BATCH_ID='$id'";
			// return $result = $this->db->query($sql, $return_object = TRUE)->result_array();

			// Modified on 31-05-17

			// $sql="SELECT student_fee.ID,student_fee.STUDENT_PROFILE_ID,course_batch.NAME AS BATCH_NAME,COURSE.NAME AS COURSE_NAME,
			// 	CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS STUDENT_NAME,PROFILE.ADMISSION_NO,student_fee.FEE_STRUCTURE_ID,
			// 	fee_payment.STUDENT_FEE_ID,
   //              case when fee_payment.STUDENT_FEE_ID = student_fee.ID then 
			// 		(select SUM(AMOUNT) from fee_item_structure where fee_item_structure.FEE_STRUCTURE_ID=student_fee.FEE_STRUCTURE_ID)
			// 		else
			// 		(select SUM(AMOUNT) from fee_item_structure where fee_item_structure.FEE_STRUCTURE_ID=student_fee.FEE_STRUCTURE_ID)
			// 		end as TOTAL_AMOUNT,
			// 		case when fee_payment.ID = (select FEE_PAYMENT_ID from student_fee_status where FEE_PAYMENT_ID IN(fee_payment.ID) GROUP BY FEE_PAYMENT_ID) then 
			// 		(select SUM(PAID_AMOUNT) from student_fee_status where fee_payment.ID = student_fee_status.FEE_PAYMENT_ID)
			// 		else
			// 		0
			// 		end as TOTAL_PAID_AMOUNT
   //              FROM student_fee
			// 		INNER JOIN COURSE ON student_fee.COURSE_ID=COURSE.ID
			// 		INNER JOIN course_batch ON student_fee.BATCH_ID=course_batch.ID
			// 		INNER JOIN PROFILE ON student_fee.STUDENT_PROFILE_ID=PROFILE.ID			
			// 		LEFT JOIN fee_payment ON fee_payment.STUDENT_FEE_ID=student_fee.ID
			// 		WHERE student_fee.BATCH_ID='$id'";
			// return $result=$this->db->query($sql, $return_object = TRUE)->result_array();


			// **************** NEW QUERY MODIFIED ON 14-06-17 ***************************

			$sql="SELECT student_fee.ID,student_fee.COURSE_ID,student_fee.STUDENT_PROFILE_ID,student_fee.FEE_STRUCTURE_ID,
				concat(profile.FIRSTNAME,' ',profile.LASTNAME) AS STUDENT_NAME,profile.ADMISSION_NO,
				COURSE.NAME AS COURSE_NAME,
				(SELECT NAME FROM course_batch WHERE ID=student_profile.COURSEBATCH_ID) AS BATCH_NAME
				FROM 
				student_fee 
				INNER JOIN profile ON student_fee.STUDENT_PROFILE_ID=profile.ID
				INNER JOIN course ON student_fee.COURSE_ID=course.ID
				INNER JOIN student_profile ON student_fee.STUDENT_PROFILE_ID=student_profile.PROFILE_ID
				WHERE student_fee.BATCH_ID='$id' GROUP BY student_fee.STUDENT_PROFILE_ID";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
			foreach ($result as $key => $value) {
				$PROFILEid=$value['STUDENT_PROFILE_ID'];
				$sql1="SELECT student_fee.FEE_STRUCTURE_ID,fee_item_structure.AMOUNT,
						CASE WHEN (SELECT count(FEE_ITEM_ID) FROM STUDENT_FEE_STATUS WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID) > 0 THEN 
							(SELECT SUM(PAID_AMOUNT) FROM STUDENT_FEE_STATUS WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID)
						ELSE 
						0
						END AS TOTAL_PAID_AMOUNT,
						CASE WHEN DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) > 0 THEN
							(SELECT VALUE FROM feefine_slabs WHERE FEE_FINE_ID=fee_item_structure.FEE_FINE_ID and DUE_DATE <= DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) ORDER BY ID DESC LIMIT 1)
							ELSE
							0
							END AS FINE_AMOUNT,
						CASE WHEN (SELECT count(FEE_ITEM_ID) FROM student_fee_fine WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_FINE_ID=fee_item_structure.FEE_FINE_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID) > 0 THEN 
							(SELECT SUM(AMOUNT) FROM student_fee_fine WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_FINE_ID=fee_item_structure.FEE_FINE_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID)
						ELSE 
						0
						END AS TOTAL_PAID_FINE_AMOUNT
						from student_fee 
						LEFT JOIN fee_item_structure ON student_fee.FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID
						WHERE student_fee.STUDENT_PROFILE_ID='$PROFILEid'";
				$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
				$totalItemAmount=0;
				$totalPaidAmount=0;
				$totalFineAmount=0;
				$totalPaidFineAmount=0;
				foreach($result1 as $key1 => $value1){
					$totalItemAmount=$totalItemAmount+$value1['AMOUNT'];
					$totalPaidAmount=$totalPaidAmount+$value1['TOTAL_PAID_AMOUNT'];
					$totalFineAmount=$totalFineAmount+$value1['FINE_AMOUNT'];
					$totalPaidFineAmount=$totalPaidFineAmount+$value1['TOTAL_PAID_FINE_AMOUNT'];
				}
				$result[$key]['Total_Amount']=$totalItemAmount;
				$result[$key]['Total_Paid']=$totalPaidAmount;
				$result[$key]['Total_Fine']=$totalFineAmount;
				$result[$key]['Total_Paid_Fine']=$totalPaidFineAmount;

			}
			// print_r($result);exit;
			return $result;	
		}
		function fetchStudentList($id,$check,$structureid,$bacthcourseid){
			if($check=='course'){
				// MODIFIED ON 15-05-17

				// $sql="SELECT P.ID AS PROFILE_ID,concat(P.FIRSTNAME,' ',P.LASTNAME) AS STUDENT_NAME,P.ADMISSION_NO,P.IMAGE1 AS IMAGE from course_batch as B INNER JOIN course as C ON B.COURSE_ID = C.ID INNER JOIN student_profile as SP ON SP.COURSEBATCH_ID = B.ID INNER JOIN profile as P ON SP.PROFILE_ID=P.ID WHERE C.ID='$id'";
				// return $result=$this->db->query($sql, $return_object = TRUE)->result_array();

				// 02-06-17
				// $sql="select student_profile.PROFILE_ID,concat(profile.FIRSTNAME,' ',profile.LASTNAME) AS STUDENT_NAME,profile.ADMISSION_NO,profile.IMAGE1 AS IMAGE 
				// 	from course_batch
				// 	inner join student_profile on course_batch.ID=student_profile.COURSEBATCH_ID
				// 	inner join profile on student_profile.PROFILE_ID=profile.ID
				// 	where course_batch.COURSE_ID='$id'";
				// return $result=$this->db->query($sql, $return_object = TRUE)->result_array();

				// 09-06-17
				$sql="SELECT student_profile.ID AS STUD_PROFILE_ID,student_profile.PROFILE_ID,concat(profile.FIRSTNAME,' ',profile.LASTNAME) AS STUDENT_NAME,profile.ADMISSION_NO,profile.IMAGE1 AS IMAGE,
					CASE WHEN student_profile.ID NOT IN(SELECT STUDENT_PROFILE_ID FROM student_fee WHERE COURSE_ID='$id' AND FEE_STRUCTURE_ID='$structureid') THEN 'Yes'
                    ELSE 'No' END AS STATUS
					FROM course_batch 
					INNER JOIN student_profile ON COURSEBATCH_ID=course_batch.ID
					INNER JOIN profile on student_profile.PROFILE_ID=profile.ID
					WHERE course_batch.COURSE_ID='$id'";
				return $result=$this->db->query($sql, $return_object = TRUE)->result_array();

			}else if($check=='batch'){

				// MODIFIED on 15-5-17
				// $sql="SELECT PROFILE.ID,CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS STUDENT_NAME,PROFILE.ADMISSION_NO,PROFILE.IMAGE1 AS IMAGE FROM student_fee INNER JOIN PROFILE ON student_fee.STUDENT_PROFILE_ID=PROFILE.ID
				// 	WHERE student_fee.BATCH_ID='$id'";

				// // MODIFIED on 2-6-17
				// $sql="SELECT PROFILE.ID,CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS STUDENT_NAME,PROFILE.ADMISSION_NO,PROFILE.IMAGE1 AS IMAGE FROM student_fee INNER JOIN PROFILE ON student_fee.STUDENT_PROFILE_ID=PROFILE.ID
				// 	WHERE student_fee.BATCH_ID='$id'";
				// return $result=$this->db->query($sql, $return_object = TRUE)->result_array();

				// $sql="SELECT PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID) AS ADMISSION_NO,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID) AS IMAGE FROM student_profile where COURSEBATCH_ID='$id'";
				// return $result=$this->db->query($sql, $return_object = TRUE)->result_array();

				// 09-06-17
				// $sql="SELECT PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID) AS ADMISSION_NO,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID) AS IMAGE,CASE WHEN student_profile.ID NOT IN(SELECT STUDENT_PROFILE_ID FROM student_fee WHERE BATCH_ID='$id' AND FEE_STRUCTURE_ID='$structureid' AND COURSE_ID='$bacthcourseid') THEN 'Yes'
    //                 ELSE 'No' END AS STATUS FROM student_profile where COURSEBATCH_ID='$id'";


				$sql="SELECT PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID) AS ADMISSION_NO,(SELECT IMAGE1 FROM profile where ID=PROFILE_ID) AS IMAGE,CASE WHEN student_profile.ID NOT IN(SELECT STUDENT_PROFILE_ID FROM student_fee WHERE FEE_STRUCTURE_ID='$structureid' AND COURSE_ID='$bacthcourseid') THEN 'Yes'
                    ELSE 'No' END AS STATUS FROM student_profile where COURSEBATCH_ID='$id'";
				return $result=$this->db->query($sql, $return_object = TRUE)->result_array();
			}
		}
		function fetchAssignedStudentList($id,$check){
			if($check=='course'){

				$sql=" SELECT STUDENT_PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=STUDENT_PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM profile where ID=STUDENT_PROFILE_ID) AS ADMISSION_NO FROM student_fee WHERE COURSE_ID='$id' GROUP BY STUDENT_PROFILE_ID";
				return $result=$this->db->query($sql, $return_object = TRUE)->result_array();

			}else if($check=='batch'){

				$sql="SELECT STUDENT_PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=STUDENT_PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=STUDENT_PROFILE_ID) AS ADMISSION_NO FROM student_fee WHERE BATCH_ID='$id' GROUP BY STUDENT_PROFILE_ID";
				return $result=$this->db->query($sql, $return_object = TRUE)->result_array();
			}
		}
		function getSingleStudentFeeStructure($stu_id){
			// MODIFIED on 25-05-17

				// $sql="SELECT PROFILE_ID,COURSEBATCH_ID,FEE_STRUCTURE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM profile where ID=PROFILE_ID) AS ADMISSION_NO,(SELECT NAME FROM course_batch where ID=COURSEBATCH_ID) AS BATCH_NAME,(SELECT COURSE_ID FROM course_batch where ID=COURSEBATCH_ID) AS COURSEID,(SELECT NAME FROM COURSE where ID=COURSEID) AS COURSE_NAME FROM student_profile where PROFILE_ID='$stu_id'";
				// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
				// foreach ($result as $key => $value) {
				// 	$Str_id=$value['FEE_STRUCTURE_ID'];
				// 	$sql1="SELECT SUM(AMOUNT) as total_fee FROM fee_item_structure where FEE_STRUCTURE_ID='$Str_id'";
				// 	$result[$key]['total_amount'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
				// }
				// return $result;

			// Modified on 25-05-17
			// $sql="SELECT student_fee.ID,student_fee.STUDENT_PROFILE_ID,course_batch.NAME AS BATCH_NAME,COURSE.NAME AS COURSE_NAME,
			// 	CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS STUDENT_NAME,PROFILE.ADMISSION_NO
			// 	FROM student_fee INNER JOIN PROFILE ON student_fee.STUDENT_PROFILE_ID=PROFILE.ID
			// 	INNER JOIN COURSE ON student_fee.COURSE_ID=COURSE.ID
			// 	INNER JOIN course_batch ON student_fee.BATCH_ID=course_batch.ID
			// 	WHERE student_fee.STUDENT_PROFILE_ID='$stu_id'";
			// return $result=$this->db->query($sql, $return_object = TRUE)->result_array();


// -------------------------------------------------------------------------------------------------------
			// MODIFIED ON 31-05-17

			// $sql="SELECT student_fee.ID,student_fee.STUDENT_PROFILE_ID,course_batch.NAME AS BATCH_NAME,COURSE.NAME AS COURSE_NAME,
			// 	CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS STUDENT_NAME,PROFILE.ADMISSION_NO,student_fee.FEE_STRUCTURE_ID,
			// 	fee_payment.STUDENT_FEE_ID,
   //              case when fee_payment.STUDENT_FEE_ID = student_fee.ID then 
			// 		(select SUM(AMOUNT) from fee_item_structure where fee_item_structure.FEE_STRUCTURE_ID=student_fee.FEE_STRUCTURE_ID)
			// 		else
			// 		(select SUM(AMOUNT) from fee_item_structure where fee_item_structure.FEE_STRUCTURE_ID=student_fee.FEE_STRUCTURE_ID)
			// 		end as TOTAL_AMOUNT,
			// 		case when fee_payment.ID = (select FEE_PAYMENT_ID from student_fee_status where FEE_PAYMENT_ID IN(fee_payment.ID) GROUP BY FEE_PAYMENT_ID) then 
			// 		(select SUM(PAID_AMOUNT) from student_fee_status where fee_payment.ID = student_fee_status.FEE_PAYMENT_ID)
			// 		else
			// 		0
			// 		end as TOTAL_PAID_AMOUNT
   //              FROM student_fee
			// 		INNER JOIN COURSE ON student_fee.COURSE_ID=COURSE.ID
			// 		INNER JOIN course_batch ON student_fee.BATCH_ID=course_batch.ID
			// 		INNER JOIN PROFILE ON student_fee.STUDENT_PROFILE_ID=PROFILE.ID			
			// 		LEFT JOIN fee_payment ON fee_payment.STUDENT_FEE_ID=student_fee.ID
			// 		WHERE student_fee.STUDENT_PROFILE_ID='$stu_id'";
			// return $result=$this->db->query($sql, $return_object = TRUE)->result_array();

			// *********************************************** MODIFIED on 14-06-17


			$sql="SELECT student_fee.ID,student_fee.COURSE_ID,student_fee.STUDENT_PROFILE_ID,student_fee.FEE_STRUCTURE_ID,
				concat(profile.FIRSTNAME,' ',profile.LASTNAME) AS STUDENT_NAME,profile.ADMISSION_NO,
				COURSE.NAME AS COURSE_NAME,
				(SELECT NAME FROM course_batch WHERE ID=student_profile.COURSEBATCH_ID) AS BATCH_NAME
				FROM 
				student_fee 
				INNER JOIN profile ON student_fee.STUDENT_PROFILE_ID=profile.ID
				INNER JOIN course ON student_fee.COURSE_ID=course.ID
				INNER JOIN student_profile ON student_fee.STUDENT_PROFILE_ID=student_profile.PROFILE_ID
				WHERE student_fee.STUDENT_PROFILE_ID='$stu_id' GROUP BY student_fee.STUDENT_PROFILE_ID";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
			foreach ($result as $key => $value) {
				$PROFILEid=$value['STUDENT_PROFILE_ID'];
				$sql1="SELECT student_fee.FEE_STRUCTURE_ID,fee_item_structure.AMOUNT,
						CASE WHEN (SELECT count(FEE_ITEM_ID) FROM STUDENT_FEE_STATUS WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID) > 0 THEN 
							(SELECT SUM(PAID_AMOUNT) FROM STUDENT_FEE_STATUS WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID)
						ELSE 
						0
						END AS TOTAL_PAID_AMOUNT,
						CASE WHEN DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) > 0 THEN
							(SELECT VALUE FROM feefine_slabs WHERE FEE_FINE_ID=fee_item_structure.FEE_FINE_ID and DUE_DATE <= DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) ORDER BY ID DESC LIMIT 1)
							ELSE
							0
							END AS FINE_AMOUNT,
						CASE WHEN (SELECT count(FEE_ITEM_ID) FROM student_fee_fine WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_FINE_ID=fee_item_structure.FEE_FINE_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID) > 0 THEN 
							(SELECT SUM(AMOUNT) FROM student_fee_fine WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_FINE_ID=fee_item_structure.FEE_FINE_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID)
						ELSE 
						0
						END AS TOTAL_PAID_FINE_AMOUNT
						from student_fee 
						LEFT JOIN fee_item_structure ON student_fee.FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID
						WHERE student_fee.STUDENT_PROFILE_ID='$PROFILEid'";
				$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
				$totalItemAmount=0;
				$totalPaidAmount=0;
				$totalFineAmount=0;
				$totalPaidFineAmount=0;
				foreach($result1 as $key1 => $value1){
					$totalItemAmount=$totalItemAmount+$value1['AMOUNT'];
					$totalPaidAmount=$totalPaidAmount+$value1['TOTAL_PAID_AMOUNT'];
					$totalFineAmount=$totalFineAmount+$value1['FINE_AMOUNT'];
					$totalPaidFineAmount=$totalPaidFineAmount+$value1['TOTAL_PAID_FINE_AMOUNT'];
				}
				$result[$key]['Total_Amount']=$totalItemAmount;
				$result[$key]['Total_Paid']=$totalPaidAmount;
				$result[$key]['Total_Fine']=$totalFineAmount;
				$result[$key]['Total_Paid_Fine']=$totalPaidFineAmount;

			}
			// print_r($result);exit;
			return $result;	

		}
		function addAssignFeeStructure($id,$value){
			// print_r($value);exit;
			for($i=0;$i<count($value['student']);$i++){
				$data = array(
				   'COURSE_ID' => $value['course_id'],
				   'BATCH_ID' => $value['batch_id'],
				   'STUDENT_PROFILE_ID' => $value['student'][$i],
				   'FEE_STRUCTURE_ID' => $id
				);
				$this->db->insert('student_fee', $data); 
			}
			return array('status'=>true, 'message'=>"Record Inserted Successfully");
		}
		function fetchAssingedFeeStructure(){
			$sql="SELECT fee_structure.NAME,student_fee.FEE_STRUCTURE_ID,COUNT(*) AS TOTAL_STUDENT FROM student_fee INNER JOIN fee_structure ON fee_structure.ID = student_fee.FEE_STRUCTURE_ID GROUP BY student_fee.FEE_STRUCTURE_ID";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function deleteAssignedFeeItem($id){
			$sql="DELETE FROM student_fee where FEE_STRUCTURE_ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		function getAssignedFeeStructureforStudent($id){
			$sql="SELECT P.ID,CONCAT(P.FIRSTNAME,' ',P.LASTNAME) AS STUDENT_NAME,P.ADMISSION_NO,P.IMAGE1 FROM student_fee
				INNER JOIN PROFILE AS P ON student_fee.STUDENT_PROFILE_ID=P.ID WHERE student_fee.FEE_STRUCTURE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function deleteAssignedStudent($id){
			$sql="DELETE FROM student_fee where STUDENT_PROFILE_ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		function getStudentFeeStructurebasedoncourse($courseId){
			// $sql="SELECT P.ID AS PROFILE_ID,concat(P.FIRSTNAME,' ',P.LASTNAME) AS STUDENT_NAME,P.ADMISSION_NO,P.IMAGE1 AS IMAGE,C.NAME AS COURSE_NAME,B.NAME AS BATCH_NAME from course_batch as B INNER JOIN course as C ON B.COURSE_ID = C.ID INNER JOIN student_profile as SP ON SP.COURSEBATCH_ID = B.ID INNER JOIN profile as P ON SP.PROFILE_ID=P.ID WHERE C.ID='$courseId'";

			// $sql="SELECT student_fee.ID,student_fee.STUDENT_PROFILE_ID,course_batch.NAME AS BATCH_NAME,COURSE.NAME AS COURSE_NAME,
			// 	CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS STUDENT_NAME,PROFILE.ADMISSION_NO,student_fee.FEE_STRUCTURE_ID FROM student_fee
			// 		INNER JOIN COURSE ON student_fee.COURSE_ID=COURSE.ID
			// 		INNER JOIN course_batch ON student_fee.BATCH_ID=course_batch.ID
			// 		INNER JOIN PROFILE ON student_fee.STUDENT_PROFILE_ID=PROFILE.ID
			// 		WHERE student_fee.COURSE_ID='$courseId'";
			// return $result = $this->db->query($sql, $return_object = TRUE)->result_array();

			// $sql="SELECT student_fee.ID,student_fee.STUDENT_PROFILE_ID,course_batch.NAME AS BATCH_NAME,COURSE.NAME AS COURSE_NAME,
			// 	CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS STUDENT_NAME,PROFILE.ADMISSION_NO,student_fee.FEE_STRUCTURE_ID,
			// 	fee_payment.STUDENT_FEE_ID,
   //              case when fee_payment.STUDENT_FEE_ID = student_fee.ID then 
			// 		(select SUM(AMOUNT) from fee_item_structure where fee_item_structure.FEE_STRUCTURE_ID=student_fee.FEE_STRUCTURE_ID)
			// 		else
			// 		(select SUM(AMOUNT) from fee_item_structure where fee_item_structure.FEE_STRUCTURE_ID=student_fee.FEE_STRUCTURE_ID)
			// 		end as TOTAL_AMOUNT,
			// 		case when fee_payment.ID = (select FEE_PAYMENT_ID from student_fee_status where FEE_PAYMENT_ID IN(fee_payment.ID) GROUP BY FEE_PAYMENT_ID) then 
			// 		(select SUM(PAID_AMOUNT) from student_fee_status where fee_payment.ID = student_fee_status.FEE_PAYMENT_ID)
			// 		else
			// 		0
			// 		end as TOTAL_PAID_AMOUNT
   //              FROM student_fee
			// 		INNER JOIN COURSE ON student_fee.COURSE_ID=COURSE.ID
			// 		INNER JOIN course_batch ON student_fee.BATCH_ID=course_batch.ID
			// 		INNER JOIN PROFILE ON student_fee.STUDENT_PROFILE_ID=PROFILE.ID			
			// 		LEFT JOIN fee_payment ON fee_payment.STUDENT_FEE_ID=student_fee.ID
			// 		WHERE student_fee.COURSE_ID='$courseId'";
			// return $result = $this->db->query($sql, $return_object = TRUE)->result_array();

// *******************************************************************
			//MODIFIED ON 02-06-17
			// $sql="SELECT student_fee.ID,student_fee.STUDENT_PROFILE_ID,course_batch.NAME AS BATCH_NAME,COURSE.NAME AS COURSE_NAME,
			// 	CONCAT(PROFILE.FIRSTNAME,' ',PROFILE.LASTNAME) AS STUDENT_NAME,PROFILE.ADMISSION_NO,student_fee.FEE_STRUCTURE_ID,
			// 	(select STUDENT_FEE_ID from fee_payment where STUDENT_FEE_ID=student_fee.ID GROUP BY STUDENT_FEE_ID) AS DUP_STUDENT_FEE_ID,
			// 	(select ID from fee_payment where STUDENT_FEE_ID=student_fee.ID GROUP BY STUDENT_FEE_ID) AS DUP_PAYMENT_ID,
			// 	case when (select STUDENT_FEE_ID from fee_payment where STUDENT_FEE_ID=student_fee.ID GROUP BY STUDENT_FEE_ID) = student_fee.ID then 
			// 		(select SUM(AMOUNT) from fee_item_structure where fee_item_structure.FEE_STRUCTURE_ID=student_fee.FEE_STRUCTURE_ID)
			// 		else
			// 		(select SUM(AMOUNT) from fee_item_structure where fee_item_structure.FEE_STRUCTURE_ID=student_fee.FEE_STRUCTURE_ID)
			// 		end as TOTAL_AMOUNT,
			// 	case when (select ID from fee_payment where STUDENT_FEE_ID=student_fee.ID GROUP BY STUDENT_FEE_ID) = (select FEE_PAYMENT_ID from student_fee_status where FEE_PAYMENT_ID IN(select STUDENT_FEE_ID from fee_payment where STUDENT_FEE_ID=student_fee.ID GROUP BY STUDENT_FEE_ID) GROUP BY FEE_PAYMENT_ID) then 
			// 		(select SUM(PAID_AMOUNT) from student_fee_status where (select ID from fee_payment where STUDENT_FEE_ID=student_fee.ID GROUP BY STUDENT_FEE_ID) = student_fee_status.FEE_PAYMENT_ID)
			// 		else
			// 		0
			// 		end as TOTAL_PAID_AMOUNT
   //              FROM student_fee
			// 		INNER JOIN COURSE ON student_fee.COURSE_ID=COURSE.ID
			// 		INNER JOIN course_batch ON student_fee.BATCH_ID=course_batch.ID
			// 		INNER JOIN PROFILE ON student_fee.STUDENT_PROFILE_ID=PROFILE.ID			
			// 		WHERE student_fee.COURSE_ID='$courseId'";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// foreach ($result as $key => $value) {
			// 	$payFeeID=$value['DUP_STUDENT_FEE_ID'];

			// 	$sql1 = "select (select SUM(PAID_AMOUNT) from student_fee_status where FEE_PAYMENT_ID=fee_payment.ID) AS TOTAL_PAID from fee_payment where fee_payment.STUDENT_FEE_ID='$payFeeID'";
			// 	$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// 	$totalpaindAmount=0;
			// 	foreach ($result1 as $key1 => $value1) {
			// 		$totalpaindAmount += $result1[$key1]['TOTAL_PAID'];
			// 	}
			// 	$result[$key]['TOTAL_PAID_AMOUNT'] = $totalpaindAmount;
				
			// }
			// return $result;


// *******************************************************************


			// ------------------------------------------------------------------------------


			// MODIFIED ON 09-06-17

			// $sql="SELECT student_fee.COURSE_ID,student_fee.STUDENT_PROFILE_ID,student_fee.FEE_STRUCTURE_ID,
			// 	concat(profile.FIRSTNAME,' ',profile.LASTNAME) AS STUDENT_NAME,profile.ADMISSION_NO,
			// 	COURSE.NAME AS COURSE_NAME,
			// 	(SELECT NAME FROM course_batch WHERE ID=student_profile.COURSEBATCH_ID) AS BATCH_NAME
			// 	FROM 
			// 	student_fee 
			// 	INNER JOIN profile ON student_fee.STUDENT_PROFILE_ID=profile.ID
			// 	INNER JOIN course ON student_fee.COURSE_ID=course.ID
			// 	INNER JOIN student_profile ON student_fee.STUDENT_PROFILE_ID=student_profile.PROFILE_ID
			// 	WHERE student_fee.COURSE_ID='$courseId' GROUP BY student_fee.STUDENT_PROFILE_ID";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// // print_r($result);exit;
			// foreach ($result as $key => $value) {
			// 	$PROFILEid=$value['STUDENT_PROFILE_ID'];
			// 	$sql1="select FEE_STRUCTURE_ID from student_fee where STUDENT_PROFILE_ID='$PROFILEid'";
			// 	$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// 	$result[$key]['AMOUNT'] = 0;
			// 	foreach ($result1 as $key1 => $value1) {
					
			// 		$stru_id=$value1['FEE_STRUCTURE_ID'];
			// 		$sql2="select SUM(fee_item_structure.AMOUNT),(select ID from fee_payment where PROFILE_ID = '$PROFILEid') AS FEE_PAY_ID from fee_item_structure where FEE_STRUCTURE_ID='$stru_id'";
			// 		$result2 = $this->db->query($sql2, $return_object = TRUE)->result_array();
			// 		// $result[$key]['total_amount'][$key1]['payment_id']=$testhello;
			// 		foreach ($result2 as $key2 => $value2) {
			// 			$result[$key]['AMOUNT'] = $result[$key]['AMOUNT'] + $value2['SUM(fee_item_structure.AMOUNT)'];
			// 		}
			// 		// $result[$key]['AMOUNT']=$SUM;
			// 	}
			// }
			// print_r($result);exit;
			// // return $result;

			// ----------------------------------------------------------------

			// ---------------------------NEW QUERY MODIFIED ON 14-06-17------------------------------

			$sql="SELECT student_fee.ID,student_fee.COURSE_ID,student_fee.STUDENT_PROFILE_ID,student_fee.FEE_STRUCTURE_ID,
				concat(profile.FIRSTNAME,' ',profile.LASTNAME) AS STUDENT_NAME,profile.ADMISSION_NO,
				COURSE.NAME AS COURSE_NAME,
				(SELECT NAME FROM course_batch WHERE ID=student_profile.COURSEBATCH_ID) AS BATCH_NAME
				FROM 
				student_fee 
				INNER JOIN profile ON student_fee.STUDENT_PROFILE_ID=profile.ID
				INNER JOIN course ON student_fee.COURSE_ID=course.ID
				INNER JOIN student_profile ON student_fee.STUDENT_PROFILE_ID=student_profile.PROFILE_ID
				WHERE student_fee.COURSE_ID='$courseId' GROUP BY student_fee.STUDENT_PROFILE_ID";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
			foreach ($result as $key => $value) {
				$PROFILEid=$value['STUDENT_PROFILE_ID'];
				$sql1="SELECT student_fee.FEE_STRUCTURE_ID,fee_item_structure.AMOUNT,
						CASE WHEN (SELECT count(FEE_ITEM_ID) FROM STUDENT_FEE_STATUS WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID) > 0 THEN 
							(SELECT SUM(PAID_AMOUNT) FROM STUDENT_FEE_STATUS WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID)
						ELSE 
						0
						END AS TOTAL_PAID_AMOUNT,
						CASE WHEN DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) > 0 THEN
							(SELECT VALUE FROM feefine_slabs WHERE FEE_FINE_ID=fee_item_structure.FEE_FINE_ID and DUE_DATE <= DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) ORDER BY ID DESC LIMIT 1)
							ELSE
							0
							END AS FINE_AMOUNT,
						CASE WHEN (SELECT count(FEE_ITEM_ID) FROM student_fee_fine WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_FINE_ID=fee_item_structure.FEE_FINE_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID) > 0 THEN 
							(SELECT SUM(AMOUNT) FROM student_fee_fine WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_FINE_ID=fee_item_structure.FEE_FINE_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID)
						ELSE 
						0
						END AS TOTAL_PAID_FINE_AMOUNT
						from student_fee 
						LEFT JOIN fee_item_structure ON student_fee.FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID
						WHERE student_fee.STUDENT_PROFILE_ID='$PROFILEid'";
				$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
				$totalItemAmount=0;
				$totalPaidAmount=0;
				$totalFineAmount=0;
				$totalPaidFineAmount=0;
				foreach($result1 as $key1 => $value1){
					$totalItemAmount=$totalItemAmount+$value1['AMOUNT'];
					$totalPaidAmount=$totalPaidAmount+$value1['TOTAL_PAID_AMOUNT'];
					$totalFineAmount=$totalFineAmount+$value1['FINE_AMOUNT'];
					$totalPaidFineAmount=$totalPaidFineAmount+$value1['TOTAL_PAID_FINE_AMOUNT'];
				}
				$result[$key]['Total_Amount']=$totalItemAmount;
				$result[$key]['Total_Paid']=$totalPaidAmount;
				$result[$key]['Total_Fine']=$totalFineAmount;
				$result[$key]['Total_Paid_Fine']=$totalPaidFineAmount;

			}
			// print_r($result);exit;
			return $result;			
		}
		function getStudentBasicDetails($id){
			// Modified on 25-05-17

			// $sql="SELECT ID,COURSE_ID,BATCH_ID,STUDENT_PROFILE_ID,
			// 	(SELECT NAME FROM COURSE WHERE ID=COURSE_ID) AS COURSE_NAME,
			// 	(SELECT NAME FROM course_batch WHERE ID=BATCH_ID) AS BATCH_NAME,
			// 	(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile WHERE ID=STUDENT_PROFILE_ID) AS STUDENT_NAME,
			// 	(SELECT ADMISSION_NO FROM profile WHERE ID=STUDENT_PROFILE_ID) AS ADMISSION_NO
			// 	FROM student_fee where student_fee.STUDENT_PROFILE_ID='$id'";
			$sql="SELECT ID,COURSE_ID,BATCH_ID,STUDENT_PROFILE_ID,
				(SELECT NAME FROM COURSE WHERE ID=COURSE_ID) AS COURSE_NAME,
				(SELECT NAME FROM course_batch WHERE ID=BATCH_ID) AS BATCH_NAME,
				(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile WHERE ID=STUDENT_PROFILE_ID) AS STUDENT_NAME,
				(SELECT ADMISSION_NO FROM profile WHERE ID=STUDENT_PROFILE_ID) AS ADMISSION_NO
				FROM student_fee where student_fee.ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchFeeItemandStrutureDetail($id,$profileId){

			// PAID VALUE

			// 03-06-17 added gorup by in student_fee_status table
			


			// $sql="select student_fee.ID,student_fee.FEE_STRUCTURE_ID,fee_payment.ID as payment_id, 
			// 		case when student_fee.ID=fee_payment.STUDENT_FEE_ID then
			// 			(select PAID_AMOUNT from student_fee_status where FEE_PAYMENT_ID=fee_payment.ID 	)
			// 		else 
			// 		0
			// 		end as TOTAL_PAID_AMOUNT,
			// 		(select AMOUNT from fee_item_structure where FEE_STRUCTURE_ID=student_fee.FEE_STRUCTURE_ID GROUP BY FEE_STRUCTURE_ID) AS TOTAL_AMOUNT,
			// 		(select FEE_ITEM_ID from fee_item_structure where FEE_STRUCTURE_ID=student_fee.FEE_STRUCTURE_ID GROUP BY FEE_STRUCTURE_ID) AS FEE_ITEM_ID,
			// 		(select NAME from fee_structure where ID=student_fee.FEE_STRUCTURE_ID) AS STRUCTURE_NAME
			// 		from student_fee
   //                  LEFT JOIN fee_payment ON fee_payment.STUDENT_FEE_ID=student_fee.ID
			// 		where student_fee.FEE_STRUCTURE_ID IN($id)";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			
			// foreach ($result as $key => $value) {
			// 	$feeStsuc_ID=$value['FEE_STRUCTURE_ID'];
			// 	$sql1="SELECT AMOUNT,FEE_ITEM_ID,(SELECT NAME FROM feeitem WHERE ID=FEE_ITEM_ID) as ITEM_NAME FROM fee_item_structure WHERE FEE_STRUCTURE_ID='$feeStsuc_ID'";
			// 	$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// 	$result[$key]['ITEMS']=$result1;
			// 	// $result[$key]['TOTAL_PAID_AMOUNT']=0;
			// 	foreach ($result1 as $key1 => $value1) {
			// 		$feeitem_id = $value1['FEE_ITEM_ID'];
			// 		$payment_id = $result[$key]['payment_id'];
			// 		$sql2 = "select * from student_fee_status where FEE_ITEM_ID='$feeitem_id' and FEE_PAYMENT_ID='$payment_id'";
			// 		$result2 = $this->db->query($sql2, $return_object = TRUE)->result_array();
			// 		$result[$key]['ITEMS'][$key1]['PAID_AMOUNT']=0;
			// 		$result[$key]['ITEMS'][$key1]['payment_id']=$payment_id;
			// 		$result[$key]['ITEMS'][$key1]['feeitem_id']=$feeitem_id;
			// 		foreach ($result2 as $key2 => $value2) {
			// 			$result[$key]['ITEMS'][$key1]['PAID_AMOUNT']= $result2[$key2]['PAID_AMOUNT'];
			// 		}
			// 		// print_r($value1);
			// 	// $result[$key]['TOTAL_PAID_AMOUNT']=$result[$key]['TOTAL_PAID_AMOUNT']+$value1['AMOUNT'];
			// 	}
			// }
			// // return $result;
			// print_r($result);
			// exit;


			// // END 07-06-17


			// START 07-06-17


			$sql="SELECT STUDENT_PROFILE_ID,FEE_STRUCTURE_ID,(SELECT NAME FROM FEE_STRUCTURE WHERE ID=FEE_STRUCTURE_ID) AS STRUCTURE_NAME FROM STUDENT_FEE WHERE FEE_STRUCTURE_ID IN($id) AND STUDENT_PROFILE_ID='$profileId' order by FEE_STRUCTURE_ID";
			$result=$this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
			foreach ($result as $key => $value) {
				$studprofId=$value['STUDENT_PROFILE_ID'];
				$feestruc_id=$value['FEE_STRUCTURE_ID'];
				// $sql1="SELECT fee_item_structure.AMOUNT,fee_item_structure.FEE_ITEM_ID,fee_item_structure.FEE_FINE_ID,fee_item_structure.DUE_DATE,fee_item_structure.FEE_STRUCTURE_ID,
				// 	(SELECT NAME FROM fee_structure WHERE ID=fee_item_structure.FEE_STRUCTURE_ID) AS STRUCTURE_NAME,
				// 	(SELECT NAME FROM feeitem WHERE ID=fee_item_structure.FEE_ITEM_ID) AS ITEM_NAME,
				// 	CASE WHEN (SELECT count(FEE_ITEM_ID) FROM STUDENT_FEE_STATUS WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID) > 0 THEN 
				// 		(SELECT SUM(PAID_AMOUNT) FROM STUDENT_FEE_STATUS WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID)
				// 	ELSE 
				// 	0
				// 	END AS TOTAL_PAID_AMOUNT
				// 	FROM fee_item_structure WHERE fee_item_structure.FEE_STRUCTURE_ID IN($feestruc_id)";
				// $result[$key]['list']=$this->db->query($sql1, $return_object = TRUE)->result_array();

				// MODIFIED ON 12-06-17
				$sql1="SELECT fee_item_structure.AMOUNT,fee_item_structure.FEE_ITEM_ID,fee_item_structure.FEE_FINE_ID,fee_item_structure.DUE_DATE,fee_item_structure.FEE_STRUCTURE_ID,
					(SELECT NAME FROM fee_structure WHERE ID=fee_item_structure.FEE_STRUCTURE_ID) AS STRUCTURE_NAME,
					(SELECT NAME FROM feeitem WHERE ID=fee_item_structure.FEE_ITEM_ID) AS ITEM_NAME,
					CASE WHEN (SELECT count(FEE_ITEM_ID) FROM STUDENT_FEE_STATUS WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID) > 0 THEN 
						(SELECT SUM(PAID_AMOUNT) FROM STUDENT_FEE_STATUS WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID)
					ELSE 
					0
					END AS TOTAL_PAID_AMOUNT,
						CASE WHEN DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) > 0 THEN
					(SELECT VALUE FROM feefine_slabs WHERE FEE_FINE_ID=fee_item_structure.FEE_FINE_ID and DUE_DATE <= DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) ORDER BY ID DESC LIMIT 1)
					ELSE
					0
					END AS FINE_AMOUNT,
					CASE WHEN (SELECT count(FEE_ITEM_ID) FROM student_fee_fine WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_FINE_ID=fee_item_structure.FEE_FINE_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID) > 0 THEN 
						(SELECT SUM(AMOUNT) FROM student_fee_fine WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_FINE_ID=fee_item_structure.FEE_FINE_ID AND FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID)
					ELSE 
					0
					END AS TOTAL_PAID_FINE_AMOUNT
					FROM fee_item_structure WHERE fee_item_structure.FEE_STRUCTURE_ID IN($feestruc_id)";
				$result[$key]['list']=$this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;
			// print_r($result);exit;




			// $sql="SELECT fee_item_structure.FEE_ITEM_ID,fee_item_structure.FEE_FINE_ID,fee_item_structure.FEE_STRUCTURE_ID,fee_item_structure.AMOUNT,fee_item_structure.FINE_AMOUNT,fee_item_structure.DUE_DATE,
			// 	fee_structure.NAME,feeitem.NAME AS ITEM_NAME FROM fee_item_structure
			// 	JOIN fee_structure on fee_item_structure.FEE_STRUCTURE_ID=fee_structure.ID
			// 	JOIN feeitem on fee_item_structure.FEE_ITEM_ID=feeitem.ID
			// 	where FEE_STRUCTURE_ID IN($id)";
			// return $result = $this->db->query($sql, $return_object = TRUE)->result_array();


			// $strutureID=explode(",",$id);
			// $arrayName = array();
			// foreach ($strutureID as $key => $value) {
			// 	$sql="SELECT ID,NAME FROM fee_structure WHERE ID='$value'";
			// 	$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// 	$arrayName[$key]=array('fee_structure_Name' => $result[0]['NAME']);
			// 	$str_id=$value;
			// 	$sql="SELECT * FROM fee_item_structure WHERE FEE_STRUCTURE_ID='$str_id'";
			// 	$arrayName[$key]['items'] = $this->db->query($sql, $return_object = TRUE)->result_array();
			// }
			// // return $a;
			// print_r($arrayName);
			// exit;
		}
		function fetchFeeFineItem($id,$profileId){
			// $sql="select student_fee.ID,student_fee.FEE_STRUCTURE_ID,
			// 		case when fee_payment.ID=(select FEE_PAYMENT_ID from student_fee_fine) then
			// 			(select AMOUNT from student_fee_fine where student_fee_fine.FEE_PAYMENT_ID=fee_payment.ID)
			// 		else 
			// 		0
			// 		end as TOTAL_PAID_FINE_AMOUNT,(select NAME from fee_structure where ID=student_fee.FEE_STRUCTURE_ID) AS STRUCTURE_NAME
			// 		from student_fee
   //                  LEFT JOIN fee_payment ON fee_payment.STUDENT_FEE_ID=student_fee.ID
			// 		where student_fee.FEE_STRUCTURE_ID IN($id)";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// foreach ($result as $key => $value) {
			// 	$feeStsuc_ID=$value['FEE_STRUCTURE_ID'];
			// 	$sql1="SELECT DATEDIFF(CURDATE(),DUE_DATE) AS DAYS,FEE_ITEM_ID,FEE_FINE_ID FROM fee_item_structure WHERE FEE_STRUCTURE_ID='$feeStsuc_ID'";
			// 	$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// 	foreach ($result1 as $key1 => $value1) {
			// 		$days=$value1['DAYS'];
			// 		$fineId=$value1['FEE_FINE_ID'];
			// 		$sql2="SELECT MAX(VALUE) AS VALUE FROM feefine_slabs WHERE DUE_DATE <= '$days' AND FEE_FINE_ID='$fineId'";
			// 		$result3 = $this->db->query($sql2, $return_object = TRUE)->result_array();
			// 		$result[$key1]['result3'][$key] = $result3[0]['VALUE'];
			// 		// $result[$key]['VALUE'][$key1] = $result3[0];
			// 		// $result[$key]['FINE_NAME'][$key1] = $result3[0]['FINE_NAME'];
			// 		// $result[$key]['FINE_ID'][$key1] = $result3[0]['FINE_ID'];
			// 		// $result[$key]['FEE_ITEM_ID'][$key1] = $result1[0]['FEE_ITEM_ID'];
			// 	}
			// }
			// return $result;
			// print_r($result);exit();


			// MODIFIED on 08-06-17
			$sql="SELECT STUDENT_PROFILE_ID,FEE_STRUCTURE_ID,(SELECT NAME FROM FEE_STRUCTURE WHERE ID=FEE_STRUCTURE_ID) AS STRUCTURE_NAME FROM STUDENT_FEE WHERE FEE_STRUCTURE_ID IN($id) AND STUDENT_PROFILE_ID='$profileId' order by FEE_STRUCTURE_ID";
			$result=$this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
			foreach ($result as $key => $value) {
				$studprofId=$value['STUDENT_PROFILE_ID'];
				$feestruc_id=$value['FEE_STRUCTURE_ID'];
				$sql1="SELECT fee_item_structure.AMOUNT,fee_item_structure.FEE_ITEM_ID,fee_item_structure.FEE_FINE_ID,fee_item_structure.DUE_DATE,fee_item_structure.FEE_STRUCTURE_ID,DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) AS DAYS,	
					CASE WHEN DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) > 0 THEN
					(SELECT VALUE FROM feefine_slabs WHERE FEE_FINE_ID=fee_item_structure.FEE_FINE_ID and DUE_DATE <= DATEDIFF(CURDATE(),fee_item_structure.DUE_DATE) ORDER BY ID DESC LIMIT 1)
					ELSE
					0
					END AS FINE_AMOUNT,
					CASE WHEN (SELECT count(FEE_ITEM_ID) FROM student_fee_fine WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_FINE_ID=fee_item_structure.FEE_FINE_ID) > 0 THEN 
						(SELECT SUM(AMOUNT) FROM student_fee_fine WHERE FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND FEE_FINE_ID=fee_item_structure.FEE_FINE_ID)
					ELSE 
					0
					END AS TOTAL_PAID_FINE_AMOUNT,
					(SELECT NAME FROM fee_structure WHERE ID=fee_item_structure.FEE_STRUCTURE_ID) AS STRUCTURE_NAME,
					(SELECT NAME FROM feeitem WHERE ID=fee_item_structure.FEE_ITEM_ID) AS ITEM_NAME
					FROM fee_item_structure WHERE fee_item_structure.FEE_STRUCTURE_ID IN($feestruc_id)";
				$result[$key]['finelist']=$this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			// print_r($result);exit;
			return $result;
		}
		function addFeepayment($value){
			// print_r($value);
			// exit;
			if($value['payment_mode']=='Cash'){
				$data = array(
				   'PAYMENT_MODE' => $value['payment_mode'],
				   'REFERENCE_NO' => $value['reference_no']
				);
			}else if($value['payment_mode']=='Cheque'){
				$data = array(
				   'PAYMENT_MODE' => $value['payment_mode'],
				   'BANK_NAME' => $value['bankname'],
				   'CHEQUE_NO' => $value['cheque_no']
				);
			}else if($value['payment_mode']=='DD'){
				$data = array(
				   'PAYMENT_MODE' => $value['payment_mode'],
				   'REFERENCE_NO' => $value['dd_no']
				);
			}else if($value['payment_mode']=='Others'){
				$data = array(
				   'PAYMENT_MODE' => $value['payment_mode'],
				   'REFERENCE_NO' => $value['reference_no']
				);
			}
			$this->db->insert('modeofpayment', $data); 
			$modePay_id= $this->db->insert_id();
			if(!empty($modePay_id)){
				$receiptnum='REC'.mt_rand(10000, 99999);
				$feepayment = array(
				   'STUDENT_FEE_ID' => $value['fee_id'],
				   'PAYMENT_DATE' => $value['payment_date'],
				   'MODEOF_PAYMENT_ID' => $modePay_id,
				   'TOTAL_AMOUNT' => $value['totalpay'],
				   'RECIEPT_NO' => $receiptnum,
				   'PROFILE_ID' =>$value['profileId']
				);
				$this->db->insert('fee_payment', $feepayment); 
				$feePay_id= $this->db->insert_id();
				if(!empty($feePay_id)){
					for($i=0;$i<count($value['item_data']);$i++){
						for ($j=0; $j < count($value['item_data'][$i]['list']); $j++) {
							if(isset($value['item_data'][$i]['list'][$j]['AMOUNT1']) && $value['item_data'][$i]['list'][$j]['AMOUNT1']!=''){
								$paid=$value['item_data'][$i]['list'][$j]['TOTAL_PAID_AMOUNT'];
								$paidFine=$value['item_data'][$i]['list'][$j]['TOTAL_PAID_FINE_AMOUNT'];
								$payAmount=$value['item_data'][$i]['list'][$j]['AMOUNT1'];
								$totalPaid=$paid+$payAmount;

								$totalAmount=$value['item_data'][$i]['list'][$j]['AMOUNT'];
								$fineAmount = $value['item_data'][$i]['list'][$j]['FINE_AMOUNT'];
								//$fineAmount = ($fineAmount ? $fineAmount:0);


								$X =  $totalAmount-$paid;
								if($X < 0){
									// echo $payAmount;
									// $totalAmount-$payAmount
									$strutureID=explode(",",$id);

									$feeFine = array(
									   'FEE_ITEM_ID' => $value['item_data'][$i]['list'][$j]['FEE_ITEM_ID'],
									   'FEE_FINE_ID' => $value['item_data'][$i]['list'][$j]['FEE_FINE_ID'],
									   'AMOUNT' => $payAmount,
									   'FEE_STRUCTURE_ID' => $value['item_data'][$i]['list'][$j]['FEE_STRUCTURE_ID'],
									   'FEE_PAYMENT_ID' => $feePay_id
									);
									$this->db->insert('student_fee_fine', $feeFine);
								}else{
									$y=$X-$payAmount;
									if($y < 0){
										$fine=explode("-",$y);
										$feeFine = array(
										   'FEE_ITEM_ID' => $value['item_data'][$i]['list'][$j]['FEE_ITEM_ID'],
										   'FEE_FINE_ID' => $value['item_data'][$i]['list'][$j]['FEE_FINE_ID'],
										   'AMOUNT' => $fine[1],
										   'FEE_STRUCTURE_ID' => $value['item_data'][$i]['list'][$j]['FEE_STRUCTURE_ID'],
										   'FEE_PAYMENT_ID' => $feePay_id
										);
										$this->db->insert('student_fee_fine', $feeFine);


										$feestatus = array(
										   'FEE_PAYMENT_ID' => $feePay_id,
										   'FEE_ITEM_ID' => $value['item_data'][$i]['list'][$j]['FEE_ITEM_ID'],
										   'PAID_AMOUNT' => $X,
										   'FEE_STRUCTURE_ID' => $value['item_data'][$i]['list'][$j]['FEE_STRUCTURE_ID'],
										   'PAY_STATUS' => ''
										);
										$this->db->insert('student_fee_status', $feestatus);
									}else{
										$feestatus = array(
										   'FEE_PAYMENT_ID' => $feePay_id,
										   'FEE_ITEM_ID' => $value['item_data'][$i]['list'][$j]['FEE_ITEM_ID'],
										   'PAID_AMOUNT' => $payAmount,
										   'FEE_STRUCTURE_ID' => $value['item_data'][$i]['list'][$j]['FEE_STRUCTURE_ID'],
										   'PAY_STATUS' => ''
										);
										$this->db->insert('student_fee_status', $feestatus);
									}
								}

								

								


								// echo "fineAmount";
								// print_r($fineAmount);
								// echo "totalAmount";
								// print_r($totalAmount);
								// echo "paid";
								// print_r($paid);

								// $payableAmount = ($totalAmount+$fineAmount) - $paid;
								// echo "payableAmount";
								// print_r($payableAmount);
								// echo "payAmount";
								// print_r($payAmount);
								// if($payableAmount <= $payAmount){
								// 	if ($fineAmount!=0) {
								// 		$remainingFine = $payAmount - $payableAmount;
								// 		// echo "fine";
								// 		// print_r($remainingFine);
								// 		$feeFine = array(
								// 		   'FEE_ITEM_ID' => $value['item_data'][$i]['list'][$j]['FEE_ITEM_ID'],
								// 		   'FEE_FINE_ID' => $value['item_data'][$i]['list'][$j]['FEE_FINE_ID'],
								// 		   'AMOUNT' => $remainingFine,
								// 		   'FEE_STRUCTURE_ID' => $value['item_data'][$i]['list'][$j]['FEE_STRUCTURE_ID'],
								// 		   'FEE_PAYMENT_ID' => $feePay_id
								// 		);
								// 		$this->db->insert('student_fee_fine', $feeFine);
								// 	}else{
								// 		echo "payableAmount if";
								// 		print_r($payAmount);
								// 		$feestatus = array(
								// 		   'FEE_PAYMENT_ID' => $feePay_id,
								// 		   'FEE_ITEM_ID' => $value['item_data'][$i]['list'][$j]['FEE_ITEM_ID'],
								// 		   'PAID_AMOUNT' => $payAmount,
								// 		   'FEE_STRUCTURE_ID' => $value['item_data'][$i]['list'][$j]['FEE_STRUCTURE_ID'],
								// 		   'PAY_STATUS' => ''
								// 		);
								// 		$this->db->insert('student_fee_status', $feestatus);
								// 	}
								// }else {
								// 	echo "payableAmount else";
								// 	print_r($payAmount);
								// 	$feestatus = array(
								// 	   'FEE_PAYMENT_ID' => $feePay_id,
								// 	   'FEE_ITEM_ID' => $value['item_data'][$i]['list'][$j]['FEE_ITEM_ID'],
								// 	   'PAID_AMOUNT' => $payAmount,
								// 	   'FEE_STRUCTURE_ID' => $value['item_data'][$i]['list'][$j]['FEE_STRUCTURE_ID'],
								// 	   'PAY_STATUS' => ''
								// 	);
								// 	$this->db->insert('student_fee_status', $feestatus);
								// }



								// $feestatus = array(
								//    'FEE_PAYMENT_ID' => $feePay_id,
								//    'FEE_ITEM_ID' => $value['item_data'][$i]['list'][$j]['FEE_ITEM_ID'],
								//    'PAID_AMOUNT' => $value['item_data'][$i]['list'][$j]['AMOUNT1'],
								//    'FEE_STRUCTURE_ID' => $value['item_data'][$i]['list'][$j]['FEE_STRUCTURE_ID'],
								//    'PAY_STATUS' => ''
								// );
								// $this->db->insert('student_fee_status', $feestatus);


								// $feestatus = array(
								//    'FEE_ITEM_ID' => $value['fineItem'][$i]['finelist'][$j]['FEE_ITEM_ID'],
								//    'FEE_FINE_ID' => $value['fineItem'][$i]['finelist'][$j]['FEE_FINE_ID'],
								//    'AMOUNT' => $value['fineItem'][$i]['finelist'][$j]['AMOUNT1'],
								//    'FEE_STRUCTURE_ID' => $value['fineItem'][$i]['finelist'][$j]['FEE_STRUCTURE_ID'],
								//    'FEE_PAYMENT_ID' => $feePay_id
								// );
								// $this->db->insert('student_fee_fine', $feestatus);
							}
						}
					}
				}

				// for($i=0;$i<count($value['fineItem']);$i++){

				// 	for ($j=0; $j < count($value['fineItem'][$i]['finelist']); $j++) {
				// 		if(isset($value['fineItem'][$i]['finelist'][$j]['AMOUNT1'])){
				// 			$feestatus = array(
				// 			   'FEE_ITEM_ID' => $value['fineItem'][$i]['finelist'][$j]['FEE_ITEM_ID'],
				// 			   'FEE_FINE_ID' => $value['fineItem'][$i]['finelist'][$j]['FEE_FINE_ID'],
				// 			   'AMOUNT' => $value['fineItem'][$i]['finelist'][$j]['AMOUNT1'],
				// 			   'FEE_STRUCTURE_ID' => $value['fineItem'][$i]['finelist'][$j]['FEE_STRUCTURE_ID'],
				// 			   'FEE_PAYMENT_ID' => $feePay_id
				// 			);
				// 			$this->db->insert('student_fee_fine', $feestatus);
				// 			// $feefine_id= $this->db->insert_id();
				// 			// if(!empty($feefine_id)){
				// 			// 	$feestatus1 = array(
				// 			// 	   'STUDENT_FEEFINE_ID' => $feefine_id
				// 			// 	);
				// 			// 	$this->db->where('FEE_PAYMENT_ID', $feePay_id);	
				// 			// 	$this->db->update('student_fee_status', $feestatus1);
				// 			// }
				// 		}
				// 	}

				// 	// if(isset($value['fineItem'][$i]['AMOUNT1'])){
				// 	// 	$feestatus = array(
				// 	// 	   'FEE_ITEM_ID' => $value['fineItem'][$i]['FEE_ITEM_ID'],
				// 	// 	   'FEE_FINE_ID' => $value['fineItem'][$i]['FINE_ID'],
				// 	// 	   'AMOUNT' => $value['fineItem'][$i]['AMOUNT1'],
				// 	// 	);
				// 	// 	$this->db->insert('student_fee_fine', $feestatus);
				// 	// }
				// }
			}
			 // exit;
			return array('status'=>true, 'message'=>"Record Inserted Successfully",'FEEPAYMENT_ID'=>$feePay_id);
		}
		function getStudentFeeDetails($id){
			$sql="select ID,STUDENT_FEE_ID,MODEOF_PAYMENT_ID,PAYMENT_DATE,TOTAL_AMOUNT,
				(select STUDENT_PROFILE_ID from student_fee where ID=STUDENT_FEE_ID) AS PROFILEID,
				(select BATCH_ID from student_fee where ID=STUDENT_FEE_ID) AS BATCHID,
				(select COURSE_ID from student_fee where ID=STUDENT_FEE_ID) AS COURSEID,
				(select concat(FIRSTNAME,' ',LASTNAME) from profile where ID=PROFILEID) AS STUDENT_NAME,
				(select ADMISSION_NO from profile where ID=PROFILEID) AS ADMISSION_NO,
				(select NAME from course where ID=COURSEID) AS COURSE_NAME,
				(select NAME from course_batch where ID=BATCHID) AS BATCH_NAME,
				(select PAYMENT_MODE from modeofpayment where ID=MODEOF_PAYMENT_ID) AS MODEOF_PAYMENT
				from fee_payment where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// return $result;
			foreach ($result as $key => $value) {
				$paymentID=$value['ID'];
				$sql1="select FEE_ITEM_ID,PAID_AMOUNT,STUDENT_FEEFINE_ID,(SELECT NAME FROM feeitem WHERE ID=FEE_ITEM_ID) AS FEEITEM_NAME,(SELECT AMOUNT FROM student_fee_fine WHERE ID=STUDENT_FEEFINE_ID) AS FEEFINE_AMOUNT,(SELECT FEE_ITEM_ID FROM student_fee_fine WHERE ID=STUDENT_FEEFINE_ID) AS FINE_FEEITEMID,(SELECT NAME FROM feeitem WHERE ID=FINE_FEEITEMID) AS FINEFEEITEM_NAME from student_fee_status where FEE_PAYMENT_ID='$paymentID'";
				$result[$key]['itemlist'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
				$sql2="select FEE_ITEM_ID,FEE_FINE_ID,AMOUNT,(SELECT NAME FROM feeitem WHERE ID=FEE_ITEM_ID) AS FEEITEM_NAME from student_fee_fine where FEE_PAYMENT_ID='$paymentID'";
				$result[$key]['finelist'] = $this->db->query($sql2, $return_object = TRUE)->result_array();
			}
			// print_r($result);exit();
			return $result;
		}
		function fetchInstitutionDetails(){
			$sql="select ID,PROF_ID,
				(select FIRSTNAME from profile where ID=PROF_ID) AS INSTITUTION_NAME,
				(select LOCATION_ID from profile where ID=PROF_ID) AS LOCATIONID,
				(select PHONE_NO_1 from profile where ID=PROF_ID) AS PHONE_NO,
				(select ADDRESS from location where ID=LOCATIONID) AS ADDRESS,
				(select CITY from location where ID=LOCATIONID) AS CITY,
				(select STATE from location where ID=LOCATIONID) AS STATE
				from institution";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchAssignedCourse(){
			$sql="SELECT COURSE_ID,(SELECT NAME FROM course WHERE ID=COURSE_ID) AS COURSE_NAME FROM student_fee GROUP BY COURSE_ID";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getStudentDetailsbasedon_ID($profileID){
			$sql="SELECT ID,STUDENT_PROFILE_ID,COURSE_ID,(SELECT NAME FROM course_batch WHERE COURSE_ID=student_fee.COURSE_ID GROUP BY COURSE_ID) AS BATCH_NAME,(SELECT NAME FROM course WHERE ID=COURSE_ID) AS COURSE_NAME,(SELECT concat(FIRSTNAME,' ',LASTNAME) FROM profile WHERE ID=STUDENT_PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=STUDENT_PROFILE_ID) AS ADMISSION_NO FROM student_fee WHERE STUDENT_PROFILE_ID='$profileID' GROUP BY STUDENT_PROFILE_ID";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchStudentFeesDetails($profileid){
			$sql="select fee_payment.ID AS PAYMENT_ID,fee_payment.RECIEPT_NO,fee_payment.PAYMENT_DATE,student_fee_status.FEE_STRUCTURE_ID,student_fee_status.PAID_AMOUNT,fee_item_structure.AMOUNT
                    from student_fee 
                    right join fee_payment on student_fee.ID=fee_payment.STUDENT_FEE_ID
                    right join student_fee_status on fee_payment.ID=student_fee_status.FEE_PAYMENT_ID
                    right join fee_item_structure on student_fee_status.FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND student_fee_status.FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID
                    where STUDENT_PROFILE_ID='$profileid'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function fetchStudentDetailfeelist($feepaymentid){
			$sql="SELECT ID,PROFILE_ID,PAYMENT_DATE,RECIEPT_NO,(SELECT concat(FIRSTNAME,' ',LASTNAME) FROM profile WHERE ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM profile WHERE ID=PROFILE_ID) AS ADMISSION_NO,
				(SELECT COURSE_ID FROM student_fee WHERE STUDENT_PROFILE_ID=PROFILE_ID GROUP BY STUDENT_PROFILE_ID) AS COURSEID,
				(SELECT BATCH_ID FROM student_fee WHERE STUDENT_PROFILE_ID=PROFILE_ID GROUP BY STUDENT_PROFILE_ID) AS BATCH_ID,
				(SELECT NAME FROM course WHERE ID=COURSEID) AS COURSE_NAME,
				(SELECT NAME FROM course_batch WHERE ID=BATCH_ID) AS BATCH_NAME
				FROM fee_payment WHERE ID='$feepaymentid'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$peymentID=$value['ID'];
				$sql1="select student_fee_status.FEE_ITEM_ID,student_fee_status.FEE_STRUCTURE_ID,student_fee_status.PAID_AMOUNT,(select NAME from feeitem where ID=student_fee_status.FEE_ITEM_ID) AS ITEM_NAME,fee_item_structure.AMOUNT from student_fee_status
				join fee_item_structure on student_fee_status.FEE_ITEM_ID=fee_item_structure.FEE_ITEM_ID AND student_fee_status.FEE_STRUCTURE_ID=fee_item_structure.FEE_STRUCTURE_ID
				where student_fee_status.FEE_PAYMENT_ID='$peymentID'";
				$result[$key]['feelist'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			// print_r($result);exit;
			return $result;
		}
	}
?>
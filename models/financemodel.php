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
		
		public function deleteFeeItem($id){
			$sql="DELETE FROM FEEITEM where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		function getStudentList(){
			$sql="SELECT PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS ADMISSION_NO FROM student_profile WHERE FEE_STRUCTURE_ID=''";
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
			// print_r($value);exit;
			$name=$value['stru_name'];
			$sql="SELECT * FROM fee_structure where NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['stru_name'],
				   'ASSIGN_TO'=>$value['assign_to']
				);
				$this->db->insert('fee_structure', $data); 
				$stru_id= $this->db->insert_id();
				if(!empty($stru_id)){
					if($value['assign_to']=='Batch'){
						for($i=0;$i<count($value['studentData']);$i++){
							$stu_prof = array(
							   'FEE_STRUCTURE_ID' => $stru_id
							);
							$this->db->where('ID', $value['studentData'][$i]);
							$this->db->update('student_profile', $stu_prof);
						}
					}else {
						$stu_prof = array(
						   'FEE_STRUCTURE_ID' => $stru_id
						);
						$this->db->where('ID', $value['student_id']);
						$this->db->update('student_profile', $stu_prof);
					}

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
			   'NAME' => $value['stru_name'],
			   'ASSIGN_TO'=>$value['assign_to']
			);
			$this->db->where('ID', $id);
			$this->db->update('fee_structure', $data);

			if($value['assign_to']=='Batch'){
				for($i=0;$i<count($value['studentData']);$i++){
					$stu_prof = array(
					   'FEE_STRUCTURE_ID' => $id
					);
					$this->db->where('ID', $value['studentData'][$i]);
					$this->db->update('student_profile', $stu_prof);
				}
			}else {
				$stu_prof = array(
				   'FEE_STRUCTURE_ID' => $id
				);
				$this->db->where('ID', $value['student_id']);
				$this->db->update('student_profile', $stu_prof);
			}

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
			$sql="SELECT ID,NAME,ASSIGN_TO,(SELECT COUNT(FEE_STRUCTURE_ID) FROM fee_item_structure WHERE FEE_STRUCTURE_ID=fee_structure.ID) AS ITEM_COUNT FROM fee_structure";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getParticularFeeStructure($id){
			$sql="SELECT ID,NAME,(SELECT COUNT(FEE_STRUCTURE_ID) FROM fee_item_structure WHERE FEE_STRUCTURE_ID=fee_structure.ID) AS ITEM_COUNT,(SELECT TYPE FROM fee_structure_assign WHERE FEE_STRUCTURE_ID=fee_structure.ID GROUP BY TYPE) AS ASSIGN FROM fee_structure where ID='$id'";
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
		function getStudentListbasedon_batch($batchid){
			$sql="SELECT PROFILE_ID,FEE_STRUCTURE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID) AS STUDENT_NAME FROM student_profile where COURSEBATCH_ID='$batchid' AND FEE_STRUCTURE_ID=''";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getFeeStructureViewDetails($id){
			$sql11="SELECT * FROM fee_structure where ID='$id'";
			$checkAssign = $this->db->query($sql11, $return_object = TRUE)->result_array();
			// print_r($checkAssign);exit;
			if($checkAssign){
				$stID=$checkAssign[0]['ASSIGN_TO'];
				if($stID=='Student'){
					$sql="SELECT ID,NAME,ASSIGN_TO,(SELECT COURSEBATCH_ID FROM student_profile where FEE_STRUCTURE_ID=fee_structure.ID GROUP BY FEE_STRUCTURE_ID) AS BATCHID,(SELECT COURSE_ID FROM course_batch where ID=BATCHID) AS COUSREID,(SELECT NAME FROM course_batch where ID=BATCHID) AS BATCH_NAME,(SELECT NAME FROM course where ID=COUSREID) AS COUSRE_NAME,(SELECT ID FROM student_profile where FEE_STRUCTURE_ID=fee_structure.ID) AS STD_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=STD_ID) AS STUDENT_NAME FROM fee_structure where ID='$id'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				}else {
					$sql="SELECT ID,NAME,ASSIGN_TO,(SELECT COURSEBATCH_ID FROM student_profile where FEE_STRUCTURE_ID=fee_structure.ID GROUP BY FEE_STRUCTURE_ID) AS BATCHID,(SELECT COURSE_ID FROM course_batch where ID=BATCHID) AS COUSREID,(SELECT NAME FROM course_batch where ID=BATCHID) AS BATCH_NAME,(SELECT NAME FROM course where ID=COUSREID) AS COUSRE_NAME FROM fee_structure where ID='$id'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				}
				foreach ($result as $key => $value) {
					$Str_id=$value['ID'];
					$sql1="SELECT ID,AMOUNT,DUE_DATE,FREQUENCY,(SELECT NAME FROM feeitem WHERE ID=fee_item_structure.FEE_ITEM_ID) ITEM_NAME,(SELECT NAME FROM feefine WHERE ID=fee_item_structure.FEE_FINE_ID) FINE_NAME FROM fee_item_structure where FEE_STRUCTURE_ID='$Str_id'";
					$result[$key]['fee_item'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
				}
				return $result;
			}
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
			$sql="SELECT ID,NAME,ASSIGN_TO,(SELECT COURSEBATCH_ID FROM student_profile where FEE_STRUCTURE_ID=fee_structure.ID GROUP BY FEE_STRUCTURE_ID) AS BATCHID,(SELECT COURSE_ID FROM course_batch where ID=BATCHID) AS COUSREID,(SELECT NAME FROM course_batch where ID=BATCHID) AS BATCH_NAME,(SELECT NAME FROM course where ID=COUSREID) AS COUSRE_NAME FROM fee_structure where ID='$id'";
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

			$sql3="SELECT * FROM student_profile where FEE_STRUCTURE_ID='$id'";
			$result3 = $this->db->query($sql3, $return_object = TRUE)->result_array();
			foreach ($result3 as $key => $value1) {
				$studID=$value1['ID'];
				$data1 = array(
				   'FEE_STRUCTURE_ID' => ''
				);
				$this->db->where('ID', $studID);
				$this->db->update('student_profile', $data1);
			}
	    	return $this->db->affected_rows();
		}
		function deleteParticularFeeStructure($id){
			$sql="DELETE FROM fee_item_structure where ID='$id'";
			$result = $this->db->query($sql);
			return $this->db->affected_rows();
		}
	}
?>
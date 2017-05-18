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
			$sql="SELECT PROF_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROF_ID) AS STUDENT_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROF_ID) AS ADMISSION_NO FROM student_profile";
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
					if($value['assign_to']=='course'){
						for($i=0;$i<count($value['course_details']);$i++){
							$data = array(
							   'TYPE' => $value['assign_to'],
							   'COURSE_ID' => $value['course_details'][$i],
							   'FEE_STRUCTURE_ID' => $stru_id
							);
							$this->db->insert('fee_structure_assign', $data);
						}
					}else {
						$data = array(
						   'TYPE' => $value['assign_to'],
						   'COURSE_ID' => $value['student_details'],
						   'FEE_STRUCTURE_ID' => $stru_id
						);
						$this->db->insert('fee_structure_assign', $data);
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
		function getFeeStructureDetails(){
			$sql="SELECT ID,NAME,(SELECT COUNT(FEE_STRUCTURE_ID) FROM fee_item_structure WHERE FEE_STRUCTURE_ID=fee_structure.ID) AS ITEM_COUNT,(SELECT TYPE FROM fee_structure_assign WHERE FEE_STRUCTURE_ID=fee_structure.ID GROUP BY TYPE) AS ASSIGN FROM fee_structure";
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
				$sql1="SELECT PROF_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROF_ID) AS STUDENT_NAME FROM student_profile where COU_BATCH_ID='$id'";
				$result[$key]['studentlist']=$this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;
		}
		function getBatchListbasedoncourse($id){
			$sql="SELECT * FROM course_batch where COURSE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getStudentListbasedon_batch($batchid){
			$sql="SELECT PROF_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROF_ID) AS STUDENT_NAME FROM student_profile where COU_BATCH_ID='$batchid'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
	}
?>
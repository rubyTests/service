<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class hostelmodel extends CI_Model {
		
		public function addHostel($values){
			$data = array(
				'NAME' => $values['NAME'],
				'BUILDING_ID' => $values['BUILDING_ID']
			);
			$this->db->insert('hostel', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editHostel($id,$values){
			$data = array(
				'NAME' => $values['NAME'],
				'BUILDING_ID' => $values['BUILDING_ID']
			);
			$this->db->where('id', $id);
			$this->db->update('hostel', $data);
			return true;
		}
		
		public function hostelDetails(){
			$sql="SELECT * FROM hostel";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return $result;
			}
		}
		
		public function deleteHostelDetails($id){
			$sql="DELETE FROM hostel where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function hostelView(){
			$sql="SELECT h.ID,h.NAME,b.NAME as building,b.ID as building_id from hostel h,building b WHERE h.BUILDING_ID=b.ID";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return $result;
			}
		}
		
		public function addAllocation($values){
			$data = array(
				'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'HOSTEL_ID' => $values['HOSTEL_ID'],
				'BLOCK_ID' => $values['BLOCK_ID'],
				'ROOM_ID' => $values['ROOM_ID'],
				'DATE' => $values['DATE']
			);
			$this->db->insert('h_allocation', $data);
			$getId= $this->db->insert_id();
			if($getId){
				$data = array(
					'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
					'PROFILE_ID' => $values['PROFILE_ID'],
					'HOSTEL_ID' => $values['HOSTEL_ID'],
					'BLOCK_ID' => $values['BLOCK_ID'],
					'ROOM_ID' => $values['ROOM_ID'],
					'DATE' => $values['DATE']
				);
				$this->db->insert('h_transfer_history', $data);
				return $getId;
			}
		}
		
		public function editAllocation($id,$values){
			$data = array(
				'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'HOSTEL_ID' => $values['HOSTEL_ID'],
				'BLOCK_ID' => $values['BLOCK_ID'],
				'ROOM_ID' => $values['ROOM_ID'],
				'DATE' => $values['DATE']
			);
			$this->db->where('id', $id);
			$this->db->update('h_allocation', $data);
			$sql="SELECT HOSTEL_ID,BLOCK_ID,ROOM_ID FROM h_allocation where id='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$hostelId=$result[0]['HOSTEL_ID'];
				$blockId=$result[0]['BLOCK_ID'];
				$roomId=$result[0]['ROOM_ID'];
				if($hostelId!=$values['HOSTEL_ID'] || $blockId!=$values['BLOCK_ID'] || $roomId!=$values['ROOM_ID']){
					$data = array(
						'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
						'PROFILE_ID' => $values['PROFILE_ID'],
						'HOSTEL_ID' => $values['HOSTEL_ID'],
						'BLOCK_ID' => $values['BLOCK_ID'],
						'ROOM_ID' => $values['ROOM_ID'],
						'DATE' => $values['DATE']
					);
					$this->db->insert('h_transfer_history', $data);
				}
			}
			return true;
		}
		
		public function allocationDetails($id){
			if($id==null){
				$sql="SELECT * FROM h_allocation";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}else{
				$sql="SELECT * FROM h_allocation WHERE id='$id'";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}
		}
		
		public function TranferData($id){
			$sql="SELECT * FROM h_allocation WHERE PROFILE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function deleteAllocationDetails($id){
			$sql="DELETE FROM h_allocation where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function allocationView(){
			$sql="SELECT ID,RESIDENT_TYPE,PROFILE_ID,HOSTEL_ID,BLOCK_ID,ROOM_ID,DATE,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as Name,(SELECT NAME FROM hostel where ID=HOSTEL_ID)as HostelName,(SELECT NAME FROM room where ID=ROOM_ID)as RoomName FROM h_allocation";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function addVacateDetails($values){
			$data = array(
				'RESIDENT_TYPE' => $values['type'],
				'PROFILE_ID' => $values['profileId'],
				'DATE' => $values['date']
			);
			$this->db->insert('h_vacate', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editVacateDetails($Id,$values){
			$data = array(
				'RESIDENT_TYPE' => $values['type'],
				'PROFILE_ID' => $values['profileId'],
				'DATE' => $values['date']
			);
			$this->db->where('id', $Id);
			$this->db->update('h_vacate', $data);
		}
		
		public function vacateDetails($id){
			if($id==null){
				$sql="SELECT * FROM h_vacate";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}else{
				$sql="SELECT * FROM h_vacate where id='$id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}
		}
		
		public function addVisitorsDetails($values){
			$data = array(
				'RESIDENT_TYPE' => $values['type'],
				'PROFILE_ID' => $values['profileId'],
				'NAME' => $values['name'],
				'RELATION' => $values['relation'],
				'DATE' => $values['date'],
				'INTIME' => $values['inTime'],
				'OUTTIME' => $values['outTime']
			);
			$this->db->insert('h_visitors', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editVisitorsDetails($Id,$values){
			$data = array(
				'RESIDENT_TYPE' => $values['type'],
				'PROFILE_ID' => $values['profileId'],
				'NAME' => $values['name'],
				'RELATION' => $values['relation'],
				'DATE' => $values['date'],
				'INTIME' => $values['inTime'],
				'OUTTIME' => $values['outTime']
			);
			$this->db->where('id', $Id);
			$this->db->update('h_visitors', $data);
		}
		
		public function visitorsDetails($id){
			if($id==null){
				$sql="SELECT * FROM h_visitors";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}else{
				$sql="SELECT * FROM h_visitors where id='$id'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return $result;
				}
			}
		}
		
		public function getAllHostelBlocks($id){
			$sql="SELECT * FROM hostel where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			//print_r($result);exit;
			if($result){
				$building_id=$result[0]['BUILDING_ID'];
				$sql="SELECT * FROM block where BUILDING_ID='$building_id'";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}
		}
	}
?>
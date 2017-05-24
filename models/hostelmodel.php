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
			$proId=$values['PROFILE_ID'];
			$sql="SELECT PROFILE_ID,ROOM_ID FROM h_transfer where PROFILE_ID='$proId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$rId=$result[0]['ROOM_ID'];
				if($rId!=$values['ROOM_ID']){
										$data = array(
						'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
						'PROFILE_ID' => $values['PROFILE_ID'],
						'HOSTEL_ID' => $values['HOSTEL_ID'],
						'BLOCK_ID' => $values['BLOCK_ID'],
						'ROOM_ID' => $values['ROOM_ID'],
						'DATE' => $values['DATE'],
						'REASON' => 'demo'
					);
					$this->db->where('PROFILE_ID', $proId);
					$this->db->update('h_transfer', $data);
					$sql="SELECT HOSTEL_ID,BLOCK_ID,ROOM_ID FROM h_transfer where id='$id'";
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
								'DATE' => $values['DATE'],
								'REASON' => 'demo'
							);
							$this->db->insert('h_transfer_history', $data);
						}
					}
					return true;
				}else{
					return false;
				}
			}else{
				$sql="SELECT PROFILE_ID,ROOM_ID FROM h_allocation where PROFILE_ID='$proId'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					$rId=$result[0]['ROOM_ID'];
					if($rId!=$values['ROOM_ID']){
						$data = array(
							'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
							'PROFILE_ID' => $values['PROFILE_ID'],
							'HOSTEL_ID' => $values['HOSTEL_ID'],
							'BLOCK_ID' => $values['BLOCK_ID'],
							'ROOM_ID' => $values['ROOM_ID'],
							'DATE' => $values['DATE'],
							'REASON' => 'demo'
						);
						$this->db->insert('h_transfer', $data);
						$data = array(
							'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
							'PROFILE_ID' => $values['PROFILE_ID'],
							'HOSTEL_ID' => $values['HOSTEL_ID'],
							'BLOCK_ID' => $values['BLOCK_ID'],
							'ROOM_ID' => $values['ROOM_ID'],
							'DATE' => $values['DATE'],
							'REASON' => 'demo'
						);
						$this->db->insert('h_transfer_history', $data);				
						return true;
					}
				}else{
					return false;
				}
			}
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
		
		public function TranferView(){
			$sql="select PROFILE_ID from h_vacate";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$result1=[];
			if($result){
				// foreach($result as $value){
					// $proId=$value['PROFILE_ID'];
					// $sql="SELECT ID,RESIDENT_TYPE,PROFILE_ID,DATE,HOSTEL_ID,ROOM_ID,REASON,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as Name,(SELECT MAX(ID) FROM h_transfer_history where PROFILE_ID=h_transfer.PROFILE_ID)as maxId,(SELECT MAX(ID) FROM h_transfer_history where NOT ID=maxId AND PROFILE_ID=h_transfer.PROFILE_ID)as preMaxId,(SELECT HOSTEL_ID FROM h_transfer_history where ID=preMaxId)as a_hostelId,(SELECT ROOM_ID FROM h_transfer_history where ID=preMaxId)as a_roomId,(SELECT NAME FROM hostel where ID=a_hostelId) as preHostelName,(SELECT NAME FROM room where ID=a_roomId) as preRoomName,(SELECT NAME FROM hostel where ID=HOSTEL_ID) as HostelName,(SELECT NAME FROM room where ID=ROOM_ID) as roomName,(SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID=h_transfer.PROFILE_ID) as batchId,(SELECT COURSE_ID FROM course_batch where ID=batchId) as courseId,(SELECT DEPT_ID FROM course where ID=courseId)as deptId from h_transfer where NOT PROFILE_ID='$proId'";
					// $result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					// array_push($result1,$result1);
				// }
				// return $result1;
				
				$sql="SELECT ID,RESIDENT_TYPE,PROFILE_ID,DATE,HOSTEL_ID,ROOM_ID,BLOCK_ID,REASON,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as Name,(SELECT MAX(ID) FROM h_transfer_history where PROFILE_ID=h_transfer.PROFILE_ID)as maxId,(SELECT MAX(ID) FROM h_transfer_history where NOT ID=maxId AND PROFILE_ID=h_transfer.PROFILE_ID)as preMaxId,(SELECT HOSTEL_ID FROM h_transfer_history where ID=preMaxId)as a_hostelId,(SELECT BLOCK_ID FROM h_transfer_history where ID=preMaxId)as a_blockId,(SELECT ROOM_ID FROM h_transfer_history where ID=preMaxId)as a_roomId,(SELECT NAME FROM hostel where ID=a_hostelId) as preHostelName,(SELECT NAME FROM block where ID=a_blockId) as preBlockName,(SELECT NAME FROM room where ID=a_roomId) as preRoomName,(SELECT NAME FROM hostel where ID=HOSTEL_ID) as HostelName,(SELECT NAME FROM room where ID=ROOM_ID) as roomName,(SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID=h_transfer.PROFILE_ID) as batchId,(SELECT COURSE_ID FROM course_batch where ID=batchId) as courseId,(SELECT DEPT_ID FROM course where ID=courseId)as deptId from h_transfer where NOT PROFILE_ID IN (SELECT PROFILE_ID FROM h_vacate)";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
				
			}else{
				$sql="SELECT ID,RESIDENT_TYPE,PROFILE_ID,DATE,HOSTEL_ID,ROOM_ID,BLOCK_ID,REASON,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as Name,(SELECT MAX(ID) FROM h_transfer_history where PROFILE_ID=h_transfer.PROFILE_ID)as maxId,(SELECT MAX(ID) FROM h_transfer_history where NOT ID=maxId AND PROFILE_ID=h_transfer.PROFILE_ID)as preMaxId,(SELECT HOSTEL_ID FROM h_transfer_history where ID=preMaxId)as a_hostelId,(SELECT BLOCK_ID FROM h_transfer_history where ID=preMaxId)as a_blockId,(SELECT ROOM_ID FROM h_transfer_history where ID=preMaxId)as a_roomId,(SELECT NAME FROM hostel where ID=a_hostelId) as preHostelName,(SELECT NAME FROM block where ID=a_blockId) as preBlockName,(SELECT NAME FROM room where ID=a_roomId) as preRoomName,(SELECT NAME FROM hostel where ID=HOSTEL_ID) as HostelName,(SELECT NAME FROM room where ID=ROOM_ID) as roomName,(SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID=h_transfer.PROFILE_ID) as batchId,(SELECT COURSE_ID FROM course_batch where ID=batchId) as courseId,(SELECT DEPT_ID FROM course where ID=courseId)as deptId from h_transfer";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			}
			
			// $tranfer=[];
			// $sql="SELECT MAX(ID),PROFILE_ID FROM h_transfer_history GROUP BY `PROFILE_ID` order by ID";
			// $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// if($result){
				// foreach($result as $values){
					// $id=$values['MAX(ID)'];
					// $pro_id=$values['PROFILE_ID'];
					// $sql="SELECT MAX(ID) FROM h_transfer_history WHERE PROFILE_ID='$pro_id' AND NOT ID='$id'";
					// $res = $this->db->query($sql, $return_object = TRUE)->result_array();
					// $id1=$res[0]['MAX(ID)'];
					// $sql="SELECT ID,DATE,PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=h_transfer_history.PROFILE_ID)as Name,(SELECT NAME FROM hostel where ID=h_transfer_history.HOSTEL_ID)as HostelName,(SELECT NAME FROM room where ID=h_transfer_history.ROOM_ID)as RoomName FROM h_transfer_history WHERE ID IN('$id1','$id');";
					// $result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					// array_push($tranfer,$result1);
				// }
				// return $tranfer;
			// }
		}
		
		public function deleteTransferDetails($id){
			$sql="DELETE FROM h_transfer where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		
		public function hostelStudentDetail($id){
			$sql="SELECT PROFILE_ID FROM student_profile where COURSEBATCH_ID='$id' AND STUDENT_TYPE='Hostel' AND NOT PROFILE_ID IN (SELECT PROFILE_ID FROM h_allocation)";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$stuDetails=[];
			if($result){
				foreach($result as $values){
					$proId=$values['PROFILE_ID'];
					$sql="SELECT ID,CONCAT(FIRSTNAME,' ',LASTNAME)as NAME FROM profile where ID='$proId'";
					$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					array_push($stuDetails,$result1);
				}
				return $stuDetails;
			}
		}
		
		public function hostelEmployeeDetail($id){
			$sql="SELECT PROFILE_ID FROM employee_profile where DEPT_ID='$id' AND NOT PROFILE_ID IN (SELECT PROFILE_ID FROM h_allocation)";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$stuDetails=[];
			if($result){
				foreach($result as $values){
					$proId=$values['PROFILE_ID'];
					$sql="SELECT ID,CONCAT(FIRSTNAME,' ',LASTNAME)as NAME FROM profile where ID='$proId'";
					$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					array_push($stuDetails,$result1);
				}
				return $stuDetails;
			}
		}
		
		public function allocateAllStudentDetail(){
			//$sql="SELECT PROFILE_ID FROM h_allocation where RESIDENT_TYPE='Student'";
			$sql="SELECT PROFILE_ID FROM h_allocation WHERE RESIDENT_TYPE='Student' AND NOT PROFILE_ID IN (SELECT PROFILE_ID from h_vacate)";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$stutype=[];
			if($result){
				foreach($result as $values){
					$stuProId=$values['PROFILE_ID'];
					$sql="SELECT ID,CONCAT(FIRSTNAME,' ',LASTNAME)as NAME FROM profile where ID='$stuProId'";
					$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					array_push($stutype,$result1);
				}
				return $stutype;
			}
		}
		public function allocateStudentDetail($id){
			//$sql="SELECT * FROM h_allocation,student_profile where h_allocation.RESIDENT_TYPE='Student' AND h_allocation.PROFILE_ID=student_profile.PROFILE_ID AND student_profile.COURSEBATCH_ID='$id'";
			$sql="SELECT * FROM h_allocation,student_profile where h_allocation.RESIDENT_TYPE='Student' AND h_allocation.PROFILE_ID=student_profile.PROFILE_ID AND student_profile.COURSEBATCH_ID='$id' AND NOT h_allocation.PROFILE_ID IN(SELECT PROFILE_ID FROM h_vacate)";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$stutype=[];
			if($result){
				foreach($result as $values){
					$stuProId=$values['PROFILE_ID'];
					$sql="SELECT ID,CONCAT(FIRSTNAME,' ',LASTNAME)as NAME FROM profile where ID='$stuProId'";
					$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					array_push($stutype,$result1);
				}
				return $stutype;
			}
		}
		
		public function allocateEmployeeDetail(){
			$sql="SELECT PROFILE_ID FROM h_allocation where RESIDENT_TYPE='Employee'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$stutype=[];
			if($result){
				foreach($result as $values){
					$stuProId=$values['PROFILE_ID'];
					$sql="SELECT ID,CONCAT(FIRSTNAME,' ',LASTNAME)as NAME FROM profile where ID='$stuProId'";
					$result1 = $this->db->query($sql, $return_object = TRUE)->result_array();
					array_push($stutype,$result1);
				}
				return $stutype;
			}
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
				'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'DATE' => $values['DATE'],
				'REASON' => $values['REASON']
			);
			$this->db->insert('h_vacate', $data);
			$getId= $this->db->insert_id();
			if($getId){
				$proId=$values['PROFILE_ID'];
				$sql="DELETE FROM h_allocation where PROFILE_ID='$proId'";
				$sql1="DELETE FROM h_transfer where PROFILE_ID='$proId'";
				$result = $this->db->query($sql);
				$result = $this->db->query($sql1);
				return $this->db->affected_rows();
			}
		}
		
		public function editVacateDetails($Id,$values){
			$data = array(
				'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'DATE' => $values['DATE'],
				'REASON' => $values['REASON']
			);
			$this->db->where('id', $Id);
			$this->db->update('h_vacate', $data);
			return true;
		}
		
		public function vacateDetails($id){
			if($id==null){
				//$sql="SELECT ID,DATE,RESIDENT_TYPE,PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=h_vacate.PROFILE_ID)as Name FROM h_vacate";
				$sql="SELECT ID,REASON,PROFILE_ID,DATE as VacateDate,RESIDENT_TYPE,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=PROFILE_ID)as Name,(SELECT DATE from h_allocation where PROFILE_ID=h_vacate.PROFILE_ID)as AllocateDate,(SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID=h_vacate.PROFILE_ID) as batchId,(SELECT COURSE_ID FROM course_batch where ID=batchId) as courseId,(SELECT DEPT_ID FROM course where ID=courseId) as deptId FROM h_vacate";
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
		
		public function deleteVacateDetails($id){
			$sql="SELECT PROFILE_ID FROM h_vacate where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				$proId=$result[0]['PROFILE_ID'];
				$sql="DELETE FROM h_vacate where ID='$id'";
				$result = $this->db->query($sql);
				$sql="DELETE FROM h_transfer where PROFILE_ID='$proId'";
				$result = $this->db->query($sql);
				return $this->db->affected_rows();
			}
		}
		
		public function addVisitorsDetails($values){
			$data = array(
				'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'NAME' => $values['NAME'],
				'RELATION' => $values['RELATION'],
				'DATE' => $values['DATE'],
				'INTIME' => $values['INTIME']
			);
			$this->db->insert('h_visitors', $data);
			$getId= $this->db->insert_id();
			if($getId){
				return $getId;
			}
		}
		
		public function editVisitorsDetails($Id,$values){
			$data = array(
				'RESIDENT_TYPE' => $values['RESIDENT_TYPE'],
				'PROFILE_ID' => $values['PROFILE_ID'],
				'NAME' => $values['NAME'],
				'RELATION' => $values['RELATION'],
				'DATE' => $values['DATE'],
				'INTIME' => $values['INTIME'],
				'OUTTIME' => $values['OUTTIME']
			);
			$this->db->where('id', $Id);
			$this->db->update('h_visitors', $data);
			return true;
		}
		
		public function visitorsDetails($id){
			if($id==null){
				$sql="SELECT ID,NAME as visitorName,RELATION,DATE,INTIME,OUTTIME,RESIDENT_TYPE,PROFILE_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM profile where ID=h_visitors.PROFILE_ID)as Name,(SELECT HOSTEL_ID FROM h_allocation where PROFILE_ID=h_visitors.PROFILE_ID) as hostelID,(SELECT ROOM_ID FROM h_allocation where PROFILE_ID=h_visitors.PROFILE_ID) as roomID,(SELECT NAME FROM hostel where ID=hostelID) as HostelName,(SELECT NAME FROM room where ID=roomID) as roomName,(SELECT COURSEBATCH_ID FROM student_profile where PROFILE_ID=h_visitors.PROFILE_ID) as batchId,(SELECT COURSE_ID FROM course_batch where ID=batchId) as courseId,(SELECT DEPT_ID FROM course where ID=courseId) as deptId FROM h_visitors";
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
		
		public function deleteVisitorsDetails($id){
			$sql="DELETE FROM h_visitors where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
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
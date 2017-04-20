<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class leave_mgmnt_model extends CI_Model {

		// Leave type Details 		
		public function addleaveTypeDetails($value){
			$name=$value['cat_name'];
			$sql="SELECT * FROM leave_type where NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['cat_name'],
				   'CODE' => $value['cat_code']
				);
				$this->db->insert('leave_type', $data); 
				$cat_id= $this->db->insert_id();
				if(!empty($cat_id)){
					return array('status'=>true, 'message'=>"Record Inserted Successfully");
				}
			}			
	    }
		
		public function editLeaveTypeDetails($id,$value){
			$sql="SELECT * FROM leave_type where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['cat_name']){
				$data = array(
				   'NAME' => $value['cat_name'],
				   'CODE' => $value['cat_code']
				);
				$this->db->where('ID', $id);
				$this->db->update('leave_type', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully");
			}else {
				$name=$value['cat_name'];
				$sql="SELECT * FROM leave_type where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
					   'NAME' => $value['cat_name'],
					   'CODE' => $value['cat_code']
					);
					$this->db->where('ID', $id);
					$this->db->update('leave_type', $data); 
					return array('status'=>true, 'message'=>"Record Updated Successfully");
				}
			}
		}
		
		public function fetchLeaveTypeDetails(){
			$sql="SELECT * FROM leave_type";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function deleteLeaveTypeDetails($id){
			$sql="DELETE FROM leave_type where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}

		// leave Entilement

		public function addleaveEntitlementDetails($value){
			// print_r($value['leave_list']);exit;
			// $list=$value['leave_list'];
			for($i=0;$i<count($value['leave_list']);$i++){
				$data = array(
				   'LEAVE_TYPE_ID' => $value['leave_list'][$i]['leave_category'],
				   'LEAVETYPE_COUNT' => $value['leave_list'][$i]['leave_count'],
				   'VALID_FROM' => $value['leave_list'][$i]['valid_from'],
				   'EMP_PROFILE_ID' => $value['emp_id']
				);
				$this->db->insert('leave_entitlement', $data); 
			}
			return array('status'=>true, 'message'=>"Record Inserted Successfully");
	    }

	    public function editLeaveEntitlementDetails($id,$value){
			if($id){
				for($i=0;$i<count($value['leave_list']);$i++){
					if (isset($value['leave_list'][$i]['EMP_PROFILE_ID'])){  
						$data = array(
						   'LEAVE_TYPE_ID' => $value['leave_list'][$i]['LEAVE_TYPE_ID'],
						   'LEAVETYPE_COUNT' => $value['leave_list'][$i]['LEAVETYPE_COUNT'],
						   'VALID_FROM' => $value['leave_list'][$i]['VALID_FROM'],
						   'EMP_PROFILE_ID' => $value['emp_id']
						);
						$this->db->where('ID', $value['leave_list'][$i]['ID']);
						$this->db->update('leave_entitlement', $data);
					}else {
						$data = array(
						   'LEAVE_TYPE_ID' => $value['leave_list'][$i]['LEAVE_TYPE_ID'],
						   'LEAVETYPE_COUNT' => $value['leave_list'][$i]['LEAVETYPE_COUNT'],
						   'VALID_FROM' => $value['leave_list'][$i]['VALID_FROM'],
						   'EMP_PROFILE_ID' => $value['emp_id']
						);
						$this->db->insert('leave_entitlement', $data);
					}
				}
				// exit;
				return array('status'=>true, 'message'=>"Record Updated Successfully");
			}
			
	    }

	    function fetchEntitlementViewDetails(){
	    	$sql="SELECT * FROM leave_entitlement_view";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function getParticularDetails($id){
	    	$sql="SELECT * FROM leave_entitlement_view where EMP_PROFILE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    public function deleteLeaveEntitlement($id){
	    	$sql="SELECT * FROM leave_entitlement where EMP_PROFILE_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$ent_id=$value['ID'];
				$delete="DELETE FROM leave_entitlement where ID='$ent_id'";
				$result1 = $this->db->query($delete);
			}
			return $this->db->affected_rows();
		}
		function deleteParticularCategory($id){
			$sql="DELETE FROM leave_entitlement where ID='$id'";
			$result = $this->db->query($sql);
			return $this->db->affected_rows();	    	
		}
		function getemplyeeleavetypelist($id){
			$sql="SELECT leave_entitlement.ID,leave_entitlement.LEAVE_TYPE_ID,leave_entitlement.EMP_PROFILE_ID,leave_entitlement.LEAVETYPE_COUNT,leave_entitlement.VALID_FROM,
				(select NAME from leave_type where ID=leave_entitlement.LEAVE_TYPE_ID) AS LEAVE_NAME,
				(select CODE from leave_type where ID=leave_entitlement.LEAVE_TYPE_ID) AS LEAVE_CODE
				FROM leave_entitlement where EMP_PROFILE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		function fetchLeaveTypeList_Count($id){
			$sql="select leave_entitlement.EMP_PROFILE_ID,leave_entitlement.LEAVETYPE_COUNT,
					leave_type.ID AS LEAVE_TYPE_ID, leave_type.NAME AS LEAVE_CATEGORY
					from leave_entitlement
					join leave_type on leave_entitlement.LEAVE_TYPE_ID=leave_type.ID
					where EMP_PROFILE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		public function applyLeaveDetails($value){
			$typeID=$value['leave_typeID'];
			$empID=$value['employee_id'];
			$val = $value['total_leave'];
			$res=explode("-",$val);
			$totaleLeave=$res[1];
			$data = array(
			   'LEAVE_TYPE_ID' => $value['leave_typeID'],
			   'EMP_PROFILE_ID' => $value['employee_id'],
			   'DESCRIPTION' => $value['description'],
			   'FROM_DATE' => $value['from_date'],
			   'TOTAL_LEAVE' => $totaleLeave,
			   'TO_DATE' => $value['upto_date'],
			   'STATUS' => 'Pending',
			);
			$this->db->insert('employee_leave', $data);
			return array('status'=>true, 'message'=>"Record Inserted Successfully"); 		
	    }

	    function getViewdataforApplyLeave($id){
	    	$sql="select SUM(employee_leave.TOTAL_LEAVE) AS TOTAL_LEAVE,
				(select LEAVETYPE_COUNT from leave_entitlement where EMP_PROFILE_ID=employee_leave.EMP_PROFILE_ID and LEAVE_TYPE_ID=employee_leave.LEAVE_TYPE_ID) AS TOTAL_ALLOC_LEAVE,
				(select NAME from leave_type where ID=employee_leave.LEAVE_TYPE_ID) AS LEAVE_NAME
				from employee_leave where EMP_PROFILE_ID='$id' GROUP BY LEAVE_TYPE_ID HAVING count(LEAVE_TYPE_ID)";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function getViewdataforLeaveApplication(){
	    	$sql="select employee_leave.ID,employee_leave.EMP_PROFILE_ID,employee_leave.FROM_DATE,employee_leave.TO_DATE,employee_leave.STATUS,employee_leave.TOTAL_LEAVE,(select concat(FIRSTNAME,' ',LASTNAME) from profile where ID=employee_leave.EMP_PROFILE_ID) AS EMPLOYEE_NAME,(select ID from leave_type where ID=employee_leave.LEAVE_TYPE_ID) AS LEAVE_ID,(select NAME from leave_type where ID=employee_leave.LEAVE_TYPE_ID) AS LEAVE_NAME,(select ID from employee_leave_balance where LEAVE_TYPE_ID=employee_leave.LEAVE_TYPE_ID) AS EMP_L_BAL_ID from employee_leave";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	     function getleavetypelistforEmployee($empID,$lineid){
	    	$sql="select employee_leave.ID,employee_leave.EMP_PROFILE_ID,employee_leave.LEAVE_TYPE_ID,
				employee_leave.DESCRIPTION,employee_leave.FROM_DATE,employee_leave.TO_DATE,employee_leave.STATUS,
				(select NAME from leave_type where ID=employee_leave.LEAVE_TYPE_ID) AS LEAVE_NAME
				from employee_leave where employee_leave.ID='$lineid' and employee_leave.EMP_PROFILE_ID='$empID'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function updateStutus($id,$value){
	    	$data = array(
			   'STATUS' => $value['check_status']
			);
			$this->db->where('ID', $id);
			$this->db->update('employee_leave', $data); 
			return array('status'=>true, 'message'=>"Record Updated Successfully");
	    }
	    function deleteLeaveApplicationDetails($id,$bal_id){
	    	$sql="DELETE FROM employee_leave where ID='$id'";
			$result = $this->db->query($sql);
			if($result==1){
				$sql="DELETE FROM employee_leave_balance where ID='$bal_id'";
				$result = $this->db->query($sql);
				return $this->db->affected_rows();
			}
	    }
	}
?>
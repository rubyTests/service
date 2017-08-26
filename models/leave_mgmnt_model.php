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
			// print_r($res);exit;
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
			$apply_id= $this->db->insert_id();
			return array('status'=>true, 'message'=>"Record Inserted Successfully",'APPLY_ID'=>$apply_id); 		
	    }

	    function getViewdataforApplyLeave($id){
	    	$sql="select SUM(employee_leave.TOTAL_LEAVE) AS TOTAL_LEAVE,
				(select LEAVETYPE_COUNT from leave_entitlement where EMP_PROFILE_ID=employee_leave.EMP_PROFILE_ID and LEAVE_TYPE_ID=employee_leave.LEAVE_TYPE_ID) AS TOTAL_ALLOC_LEAVE,
				(select NAME from leave_type where ID=employee_leave.LEAVE_TYPE_ID) AS LEAVE_NAME
				from employee_leave where EMP_PROFILE_ID='$id' GROUP BY LEAVE_TYPE_ID HAVING count(LEAVE_TYPE_ID)";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function getViewdataforLeaveApplication(){
	    	$sql="select employee_leave.ID,employee_leave.EMP_PROFILE_ID,employee_leave.FROM_DATE,employee_leave.TO_DATE,employee_leave.STATUS,employee_leave.TOTAL_LEAVE,(select concat(FIRSTNAME,' ',LASTNAME) from profile where ID=employee_leave.EMP_PROFILE_ID) AS EMPLOYEE_NAME,(select ID from leave_type where ID=employee_leave.LEAVE_TYPE_ID) AS LEAVE_ID,(select NAME from leave_type where ID=employee_leave.LEAVE_TYPE_ID) AS LEAVE_NAME from employee_leave";
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
	    function deleteLeaveApplicationDetails($id){
	    	$sql="DELETE FROM employee_leave where ID='$id'";
			$result = $this->db->query($sql);
			return $this->db->affected_rows();
	    }
	    function checkandgetEmployeeEmail($empid){
	    	$sql="SELECT PROFILE_ID,(SELECT EMAIL FROM PROFILE WHERE ID=PROFILE_ID) AS EMAIL_ID FROM employee_profile WHERE ID='$empid'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function fetchEmployeeDetailsBasedonDepartment($id,$date){
	    	// $sql="SELECT ID,PROFILE_ID,EMP_CATEGORY_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS ADM_NO,(SELECT IMAGE1 FROM PROFILE WHERE ID=PROFILE_ID) AS PROFILE_IMAGE,(SELECT NAME FROM EMPLOYEE_CATEGORY WHERE ID=EMP_CATEGORY_ID) AS CATEGORY_NAME FROM EMPLOYEE_PROFILE WHERE DEPT_ID='$id'";
	    	$sql="SELECT ID,PROFILE_ID,PROFILE_ID as empProfile_id,EMP_CATEGORY_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS ADM_NO,(SELECT IMAGE1 FROM PROFILE WHERE ID=PROFILE_ID) AS PROFILE_IMAGE,(SELECT NAME FROM EMPLOYEE_CATEGORY WHERE ID=EMP_CATEGORY_ID) AS CATEGORY_NAME,CASE WHEN (SELECT STATUS FROM employee_attendance WHERE DEPT_ID='$id' AND DATE='$date' AND employee_attendance.PROFILE_ID=empProfile_id)='Y' THEN 'Y' ELSE 'N' END as row_select,(SELECT ID FROM employee_attendance WHERE DEPT_ID='$id' AND DATE='$date' AND employee_attendance.PROFILE_ID=empProfile_id) as attendance_id,CASE WHEN (SELECT STATUS FROM employee_attendance WHERE DEPT_ID='$id' AND DATE='$date' AND employee_attendance.PROFILE_ID=empProfile_id)='Y' THEN (SELECT REASON FROM employee_attendance WHERE DEPT_ID='$id' AND DATE='$date' AND employee_attendance.PROFILE_ID=empProfile_id) END as remark,CASE WHEN (SELECT STATUS FROM employee_attendance WHERE DEPT_ID='$id' AND DATE='$date' AND employee_attendance.PROFILE_ID=empProfile_id)='Y' THEN (SELECT DURATION FROM employee_attendance WHERE DEPT_ID='$id' AND DATE='$date' AND employee_attendance.PROFILE_ID=empProfile_id) END as duration,CASE WHEN (SELECT STATUS FROM employee_attendance WHERE DEPT_ID='$id' AND DATE='$date' AND employee_attendance.PROFILE_ID=empProfile_id)='Y' THEN (SELECT LEAVE_TYPE FROM employee_attendance WHERE DEPT_ID='$id' AND DATE='$date' AND employee_attendance.PROFILE_ID=empProfile_id) END as leaveType FROM EMPLOYEE_PROFILE WHERE DEPT_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function addEmployeeAttendanceDetails($val){
	    	// print_r($value);exit;
	    	for ($i=0; $i < count($val['details']); $i++) {
				$dept_id = $val['dept_id'];
				$date = $val['attendance_date'];
				$profileid = $val['details'][$i]['PROFILE_ID'];
				if($val['details'][$i]['leaveType']){
					$leavetype=$val['details'][$i]['leaveType'];
				}else{
					$leavetype='';
				}

				if($val['details'][$i]['row_select']=='N'){
					$sql="SELECT * FROM employee_attendance_leave WHERE DEPT_ID='$dept_id' AND DATE='$date' AND PROFILE_ID='$profileid'";
					$result = $this->db->query($sql, $return_object = TRUE)->result_array();
					if($result){
						$sql="DELETE FROM employee_attendance_leave WHERE DEPT_ID='$dept_id' AND DATE='$date' AND PROFILE_ID='$profileid'";
						$result = $this->db->query($sql);
					}
					$remark='';
					$duration='';
				}else{
					$remark=$val['details'][$i]['remark'];
					if(isset($val['details'][$i]['duration'])){
						$duration=$val['details'][$i]['duration'];
					}else {
						$duration='';
					}
					$sql2="SELECT * FROM employee_attendance_leave WHERE DEPT_ID='$dept_id' AND DATE='$date' AND PROFILE_ID='$profileid'";
					$result2 = $this->db->query($sql2, $return_object = TRUE)->result_array();
					if(isset($result2[0]['ID'])){
						$leavedata = array(
							'DEPT_ID' =>$val['dept_id'],
							'PROFILE_ID' =>$val['details'][$i]['PROFILE_ID'],
							'DATE' =>$val['attendance_date'],
							'REASON' =>$remark,
							'DURATION' =>$duration,
							'LEAVE_TYPE' =>$leavetype,
							'STATUS' =>$val['details'][$i]['row_select'],
							'CRT_USER_ID' =>$val['userId']
						);
						$this->db->where('ID', $result2[0]['ID']);
						$this->db->update('employee_attendance_leave', $leavedata);
					}else {
						$leavedata = array(
							'DEPT_ID' =>$val['dept_id'],
							'PROFILE_ID' =>$val['details'][$i]['PROFILE_ID'],
							'DATE' =>$val['attendance_date'],
							'REASON' =>$remark,
							'DURATION' =>$duration,
							'LEAVE_TYPE' =>$leavetype,
							'STATUS' =>$val['details'][$i]['row_select'],
							'CRT_USER_ID' =>$val['userId']
						);
						$this->db->insert('employee_attendance_leave', $leavedata); 
					}
				}

				$data = array(
					'DEPT_ID' =>$val['dept_id'],
					'PROFILE_ID' =>$val['details'][$i]['PROFILE_ID'],
					'DATE' =>$val['attendance_date'],
					'REASON' =>$remark,
					'DURATION' =>$duration,
					'LEAVE_TYPE' =>$leavetype,
					'STATUS' =>$val['details'][$i]['row_select'],
					'CRT_USER_ID' =>$val['userId']
				);
				if($val['details'][$i]['attendance_id']){
					$this->db->where('ID', $val['details'][$i]['attendance_id']);
					$this->db->update('employee_attendance', $data);
				}else{
					$this->db->insert('employee_attendance', $data);

					$sql1="SELECT * FROM employee_attendance_report WHERE  DEPT_ID='$dept_id' AND PROFILE_ID='$profileid'";
					$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
					// print_r($result1);
					if(isset($result1[0]['NO_OF_DAYS'])){
						$Days=$result1[0]['NO_OF_DAYS']+1;
						$dayDetails = array(
							'NO_OF_DAYS' =>$Days,
							'DEPT_ID' =>$dept_id,
							'PROFILE_ID' =>$val['details'][$i]['PROFILE_ID']
						);
						$this->db->where('ID', $result1[0]['ID']);
						$this->db->update('employee_attendance_report', $dayDetails);
					}else{
						$Days=1;
						$dayDetails = array(
							'NO_OF_DAYS' =>$Days,
							'DEPT_ID' =>$dept_id,
							'PROFILE_ID' =>$val['details'][$i]['PROFILE_ID']
						);
						$this->db->insert('employee_attendance_report', $dayDetails);
					}
				}
			}
			// exit;
			return array('status'=>true, 'message'=>"Record Inserted Successfully");
	    }
	    function fetchEmployeeDetailsandPercentage($dept_id){
	    	$sql="SELECT ID,PROFILE_ID,PROFILE_ID as empProfile_id,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS ADM_NO,(SELECT IMAGE1 FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_IMAGE,
	    		CASE WHEN (SELECT COUNT(ID) FROM employee_attendance_leave WHERE PROFILE_ID=employee_attendance.PROFILE_ID) > 0 THEN ROUND(((((SELECT NO_OF_DAYS FROM employee_attendance_report WHERE PROFILE_ID=employee_attendance.PROFILE_ID AND DEPT_ID=employee_attendance.DEPT_ID)-(SELECT COUNT(ID) FROM employee_attendance_leave WHERE PROFILE_ID=employee_attendance.PROFILE_ID))*100)/(SELECT NO_OF_DAYS FROM employee_attendance_report WHERE PROFILE_ID=employee_attendance.PROFILE_ID AND DEPT_ID=employee_attendance.DEPT_ID)),-1) ELSE '100' END AS PERCENTAGE FROM employee_attendance WHERE DEPT_ID='$dept_id' GROUP BY PROFILE_ID";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function fetchParticularEmployeeDetailsandPercentage($profileid){
	    	$sql="SELECT ID,PROFILE_ID,PROFILE_ID as empProfile_id,(SELECT EMP_CATEGORY_ID FROM EMPLOYEE_PROFILE WHERE EMPLOYEE_PROFILE.PROFILE_ID=employee_attendance.PROFILE_ID) AS EMP_CATEGORY_ID,(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NAME,(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS ADM_NO,(SELECT IMAGE1 FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_IMAGE,(SELECT NAME FROM EMPLOYEE_CATEGORY WHERE ID=EMP_CATEGORY_ID) AS CATEGORY_NAME,
	    		CASE WHEN (SELECT COUNT(ID) FROM employee_attendance_leave WHERE PROFILE_ID=employee_attendance.PROFILE_ID) > 0 THEN ROUND(((((SELECT NO_OF_DAYS FROM employee_attendance_report WHERE PROFILE_ID=employee_attendance.PROFILE_ID AND DEPT_ID=employee_attendance.DEPT_ID)-(SELECT COUNT(ID) FROM employee_attendance_leave WHERE PROFILE_ID=employee_attendance.PROFILE_ID))*100)/(SELECT NO_OF_DAYS FROM employee_attendance_report WHERE PROFILE_ID=employee_attendance.PROFILE_ID AND DEPT_ID=employee_attendance.DEPT_ID)),-1) ELSE '100' END AS PERCENTAGE FROM employee_attendance WHERE PROFILE_ID='$profileid' GROUP BY PROFILE_ID";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$profileID=$value['PROFILE_ID'];
				$sql1="SELECT * from employee_attendance_leave where PROFILE_ID='$profileID'";
				$result[$key]['leave_list']=$this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			return $result;
	    }
	}
?>
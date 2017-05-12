<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class payroll_payslip extends CI_Model {

		// Department Details 
		
		public function addPayItem($value){
			// print_r($value);exit;
			$name=$value['NAME'];
			$sql="SELECT * FROM PAYITEM where NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['NAME'],
				   'TYPE' => $value['TYPE']
				);
				$this->db->insert('PAYITEM', $data); 
				$item_id= $this->db->insert_id();
				if(!empty($item_id)){
					// return true;
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'PAYITEM_ID'=>$item_id);
				}
			}
	    }	
		
		public function editPayItem($id,$value){

			$sql="SELECT * FROM PAYITEM where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['NAME']){
				$data = array(
				   'NAME' => $value['NAME'],
				   'TYPE' => $value['TYPE']
				);
				$this->db->where('ID', $id);
				$this->db->update('PAYITEM', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'PAYITEM_ID'=>$id);
			}else {
				$name=$value['NAME'];
				$sql="SELECT * FROM PAYITEM where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
					   'NAME' => $value['NAME'],
					   'TYPE' => $value['TYPE']
					);
					$this->db->where('ID', $id);	
					$this->db->update('PAYITEM', $data); 
					return array('status'=>true, 'message'=>"Record Updated Successfully",'PAYITEM_ID'=>$id);
				}
			}
		}
		
		public function getPayItemDetails(){
			$sql="SELECT * FROM PAYITEM";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		
		public function getDepartmentDetails($id){
			$sql="SELECT * FROM PAYITEM where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			return $result;
		}
		
		public function deletepayItem($id){
			$sql="DELETE FROM PAYITEM where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}

		function checkItemAssignedorNot($id){
			$sql="SELECT * FROM payitemstructure where PAYITEM_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}

		// Paystructure
		public function addPayStructure($value){
			// print_r($value);exit;
			$name=$value['PAY_STRU_NAME'];
			$sql="SELECT * FROM paystructure where NAME='$name'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false);
			}else {
				$data = array(
				   'NAME' => $value['PAY_STRU_NAME'],
				   'FREQUENCY' => $value['FREQUENCY']
				);
				$this->db->insert('paystructure', $data); 
				$stru_id= $this->db->insert_id();
				if(!empty($stru_id)){
					for($i=0;$i<count($value['PAYITEM_LIST']);$i++){
						$data1 = array(
						   'PAYITEM_ID' => $value['PAYITEM_LIST'][$i]['itemType'],
						   'AMOUNT' => $value['PAYITEM_LIST'][$i]['percentage'],
						   'PAYSTRUCTURE_ID' => $stru_id
						);
						$this->db->insert('payitemstructure', $data1); 
					}
					return array('status'=>true, 'message'=>"Record Inserted Successfully",'PAYSTRUC_ID'=>$stru_id);
				}
			}
	    }
	    public function editPayStructure($id,$value){
	    	// print_r($value);exit;
			$sql="SELECT * FROM paystructure where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();			
			if($result[0]['NAME']==$value['PAY_STRU_NAME']){
				$data = array(
				   'NAME' => $value['PAY_STRU_NAME'],
				   'FREQUENCY' => $value['FREQUENCY']
				);
				$this->db->where('ID', $id);
				$this->db->update('paystructure', $data); 

				if($id){
					for($i=0;$i<count($value['PAYITEM_LIST']);$i++){
						if(isset($value['PAYITEM_LIST'][$i]['ID'])){
							$item = array(
							   'PAYITEM_ID' => $value['PAYITEM_LIST'][$i]['PAYITEM_ID'],
							   'AMOUNT' => $value['PAYITEM_LIST'][$i]['AMOUNT'],
							   'PAYSTRUCTURE_ID' => $id
							);
							$this->db->where('ID', $value['PAYITEM_LIST'][$i]['ID']);
							$this->db->update('payitemstructure', $item); 
						}
						else {
							$data1 = array(
							   'PAYITEM_ID' => $value['PAYITEM_LIST'][$i]['PAYITEM_ID'],
							   'AMOUNT' => $value['PAYITEM_LIST'][$i]['AMOUNT'],
							   'PAYSTRUCTURE_ID' => $id
							);
							$this->db->insert('payitemstructure', $data1); 
						}
					}
				}
				return array('status'=>true, 'message'=>"Record Updated Successfully");
			}else {
				$name=$value['PAY_STRU_NAME'];
				$sql="SELECT * FROM paystructure where NAME='$name'";
				$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				if($result){
					return array('status'=>false);
				}else {
					$data = array(
					   'NAME' => $value['PAY_STRU_NAME'],
					   'FREQUENCY' => $value['FREQUENCY']
					);
					$this->db->where('ID', $id);
					$this->db->update('paystructure', $data); 

					if($id){
						for($i=0;$i<count($value['PAYITEM_LIST']);$i++){
							if(isset($value['PAYITEM_LIST'][$i]['ID'])){
								$item = array(
								   'PAYITEM_ID' => $value['PAYITEM_LIST'][$i]['PAYITEM_ID'],
								   'AMOUNT' => $value['PAYITEM_LIST'][$i]['AMOUNT'],
								   'PAYSTRUCTURE_ID' => $id
								);
								$this->db->where('ID', $value['PAYITEM_LIST'][$i]['ID']);
								$this->db->update('payitemstructure', $item); 
							}
							else {
								$data1 = array(
								   'PAYITEM_ID' => $value['PAYITEM_LIST'][$i]['PAYITEM_ID'],
								   'AMOUNT' => $value['PAYITEM_LIST'][$i]['AMOUNT'],
								   'PAYSTRUCTURE_ID' => $id
								);
								$this->db->insert('payitemstructure', $data1); 
							}
						}
					}
					return array('status'=>true, 'message'=>"Record Updated Successfully");
				}
			}
	    }
		public function getPayStructureDetails(){
			$sql="SELECT ID,NAME,FREQUENCY,(SELECT count(PAYSTRUCTURE_ID) FROM payitemstructure WHERE PAYSTRUCTURE_ID=paystructure.ID) AS TOTAL_ITEM,
				(SELECT count(PROFILE_ID) FROM employee_profile WHERE PAY_STRUCTURE_ID=paystructure.ID) AS TOTAL_EMPLOYEE
				FROM paystructure GROUP BY NAME HAVING count(NAME) ORDER BY ID ASC";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getParticularPayStructure($id){
			$sql="SELECT * FROM paystructure where ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
			foreach ($result as $key => $value) {
				$struc_id=$value['ID'];
				// $sql1="SELECT * FROM payitemstructure where PAYSTRUCTURE_ID='$struc_id'";
				$sql1="SELECT ID,PAYITEM_ID,AMOUNT,PAYSTRUCTURE_ID,(SELECT NAME FROM payitem where ID=PAYITEM_ID) AS ITEM_NAME,(SELECT TYPE FROM payitem where ID=PAYITEM_ID) AS ITEM_TYPE FROM payitemstructure where PAYSTRUCTURE_ID='$struc_id'";
				$result[$key]['item'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			// print_r($result);exit;
			return $result[0];
		}
		function deleteParticularPayStructure($id){
			$sql="DELETE FROM payitemstructure where ID='$id'";
			$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
		}
		function deleteSinglePayStructureDetails($id){
			// echo $id;exit;
			if($id){
				$data = array(
				   'PAY_STRUCTURE_ID' => ''
				);
				$this->db->where('PAY_STRUCTURE_ID', $id);
				$this->db->update('employee_profile', $data);
			}
			$sql="DELETE FROM paystructure where ID='$id'";
			$result = $this->db->query($sql);			
	    	return $this->db->affected_rows();
		}
		function getPayItemusingPaystructure($id){
			$sql="SELECT * FROM payitemstructure where PAYSTRUCTURE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getEmployeeList($id){
			$sql="select ID,PROFILE_ID,EMP_CATEGORY_ID,EMP_POSTION_ID,DEPT_ID,PAY_STRUCTURE_ID,BASIC_PAY,
				(select NAME from department where ID=DEPT_ID) AS DEPARTMENT,
				(select NAME from employee_category where ID=EMP_CATEGORY_ID) AS CATEGORY,
				(select NAME from employee_position where ID=EMP_POSTION_ID) AS POSITION,
				(select concat(FIRSTNAME,' ',LASTNAME) from profile where ID=PROFILE_ID) AS EMPLOYEE_NAME,
				(select ADMISSION_NO from profile where ID=PROFILE_ID) AS EMPLOYEE_NO
				from employee_profile where DEPT_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getEmployeeDetails($id){
			$sql="select ID,PROFILE_ID,EMP_CATEGORY_ID,EMP_POSTION_ID,DEPT_ID,PAY_STRUCTURE_ID,BASIC_PAY,
				(select NAME from department where ID=DEPT_ID) AS DEPARTMENT,
				(select NAME from employee_category where ID=EMP_CATEGORY_ID) AS CATEGORY,
				(select NAME from employee_position where ID=EMP_POSTION_ID) AS POSITION,
				(select concat(FIRSTNAME,' ',LASTNAME) from profile where ID=PROFILE_ID) AS EMPLOYEE_NAME,
				(select ADMISSION_NO from profile where ID=PROFILE_ID) AS EMPLOYEE_NO
				from employee_profile where ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		function getPayStrcutureDetails($id){
			$sql="SELECT AMOUNT,PAYITEM_ID,(SELECT NAME FROM payitem WHERE ID=PAYITEM_ID) AS ITEM_NAME,
				(SELECT TYPE FROM payitem WHERE ID=PAYITEM_ID) AS ITEM_TYPE
				FROM payitemstructure WHERE PAYSTRUCTURE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
		}
		public function assignPayroll($id,$value){
			// echo $id;
			// print_r($value);exit;
			if($id){
				$data = array(
				   'BASIC_PAY' => $value['BASIC_PAY'],
				   'PAY_STRUCTURE_ID' => $value['STRUCTURE_ID']
				);
				// 'BASIC_PAY' => number_format($value['BASIC_PAY'], 2),
				// print_r($data);exit;
				$this->db->where('ID', $id);
				$this->db->update('employee_profile', $data);
				return array('status'=>true, 'message'=>"Record Added Successfully");
			}
	    }
	    function getAssignedEmployeeDetails($id){
	    	$sql="select ID,PROFILE_ID,EMP_CATEGORY_ID,EMP_POSTION_ID,DEPT_ID,
				(select NAME from department where ID=DEPT_ID) AS DEPARTMENT,
				(select NAME from employee_category where ID=EMP_CATEGORY_ID) AS CATEGORY,
				(select NAME from employee_position where ID=EMP_POSTION_ID) AS POSITION,
				(select concat(FIRSTNAME,' ',LASTNAME) from profile where ID=PROFILE_ID) AS EMPLOYEE_NAME,
				(select ADMISSION_NO from profile where ID=PROFILE_ID) AS EMPLOYEE_NO
				FROM employee_profile WHERE PAY_STRUCTURE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function changeAssignEmployee($id){
	    	if($id){
				$data = array(
				   'BASIC_PAY' => '',
				   'PAY_STRUCTURE_ID' => ''
				);
				$this->db->where('ID', $id);
				$this->db->update('employee_profile', $data);
				return array('status'=>true, 'message'=>"Record Deleted Successfully");
			}
	    }
	    function fetchPayStructureName($id){
	    	$sql="select * FROM paystructure WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function fetchEmpoyeeForPayslip(){
	    	$sql="SELECT ID,PROFILE_ID,DEPT_ID,PAY_STRUCTURE_ID,
				(SELECT NAME FROM paystructure WHERE ID=PAY_STRUCTURE_ID) AS STRUCURE_NAME,
				(SELECT FREQUENCY FROM paystructure WHERE ID=PAY_STRUCTURE_ID) AS FREQUENCY,
				(SELECT NAME FROM department WHERE ID=DEPT_ID) AS DEPT_NAME,
				(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NAME
				FROM employee_profile WHERE PAY_STRUCTURE_ID != ''";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function fetchparticularemployeePaydetails($id){
	    	$sql="SELECT ID,PROFILE_ID,DEPT_ID,PAY_STRUCTURE_ID,EMP_POSTION_ID,BASIC_PAY,
				(SELECT NAME FROM paystructure WHERE ID=PAY_STRUCTURE_ID) AS STRUCURE_NAME,
				(SELECT FREQUENCY FROM paystructure WHERE ID=PAY_STRUCTURE_ID) AS FREQUENCY,
				(SELECT NAME FROM department WHERE ID=DEPT_ID) AS DEPT_NAME,
				(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NAME,
				(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NO,
				(SELECT NAME FROM employee_position WHERE ID=EMP_POSTION_ID) AS POSITION
				FROM employee_profile WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function gererateEmployeePayslip($id,$value){
	    	// print_r($value);exit;
	    	$fromDate=$value["from_date"];
	    	$endDate=$value["end_date"];
	    	$sql="SELECT * FROM employee_payslip WHERE EMP_PROFILE_ID='$id' and GENERATION_DATE BETWEEN '$fromDate' AND '$endDate'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result){
				return array('status'=>false, 'message'=>"Payslip already generated");
			}else {
				if($value['PAYSLIP_ID']){
		    		$data = array(
					   'GENERATION_DATE' => $value['GENERATION_DATE'],
					   'EMP_PROFILE_ID' => $id,
					   'PAYSTRUCTURE_ID' => $value['STRUCTURE_ID'],
					   'STATUS' => $value['PAYSLIP_Status'],
					   'NETPAY' => $value['Net_pay'],
					);
					$this->db->where('ID', $value['PAYSLIP_ID']);
					$this->db->update('employee_payslip', $data);

					if(isset($value['DEFAULT'])){
						// for($i=0;$i<count($value['DEFAULT']);$i++){
						// 	$data = array(
						// 	   'EMP_PAYSLIP_ID' => $payslip_id,
						// 	   'NAME' => $value['DEFAULT'][$i]['ITEM_NAME'],
						// 	   'AMOUNT' => $value['DEFAULT'][$i]['AMOUNT'],
						// 	   'TYPE' => $value['DEFAULT'][$i]['ITEM_TYPE'],
						// 	);
						// 	$this->db->where('ID', $value['PAYSLIP_ID']);
						// 	$this->db->update('employee_payslip_addon', $data);
						// 	// $this->db->insert('employee_payslip_addon', $data);
						// }
					}
					if(isset($value['ADDON'])){
						for($i=0;$i<count($value['ADDON']);$i++){

							if(isset($value['ADDON'][$i]['ID'])){
								$data = array(
								   'EMP_PAYSLIP_ID' => $value['PAYSLIP_ID'],
								   'NAME' => $value['ADDON'][$i]['NAME'],
								   'AMOUNT' => $value['ADDON'][$i]['AMOUNT'],
								   'TYPE' => $value['ADDON'][$i]['TYPE']
								);
								$this->db->where('ID', $value['ADDON'][$i]['ID']);
								$this->db->update('employee_payslip_addon', $data);
							}else {
								$data = array(
								   'EMP_PAYSLIP_ID' => $value['PAYSLIP_ID'],
								   'NAME' => $value['ADDON'][$i]['NAME'],
								   'AMOUNT' => $value['ADDON'][$i]['AMOUNT'],
								   'TYPE' => $value['ADDON'][$i]['TYPE']
								);
								$this->db->insert('employee_payslip_addon', $data);
							}
						}
					}
					return array('status'=>true, 'message'=>"Payslip Generated Successfully");
		    	}else {
		    		$data = array(
					   'GENERATION_DATE' => $value['GENERATION_DATE'],
					   'EMP_PROFILE_ID' => $id,
					   'PAYSTRUCTURE_ID' => $value['STRUCTURE_ID'],
					   'STATUS' => 'Pending',
					   'NETPAY' => $value['Net_pay'],
					);
					$this->db->insert('employee_payslip', $data); 
					$payslip_id= $this->db->insert_id();
					if(!empty($payslip_id)){
						if(isset($value['DEFAULT'])){
							for($i=0;$i<count($value['DEFAULT']);$i++){
								$data = array(
								   'EMP_PAYSLIP_ID' => $payslip_id,
								   'NAME' => $value['DEFAULT'][$i]['ITEM_NAME'],
								   'AMOUNT' => $value['DEFAULT'][$i]['changedAmount'],
								   'TYPE' => $value['DEFAULT'][$i]['ITEM_TYPE'],
								);
								$this->db->insert('employee_payslip_addon', $data);
							}
						}
						if(isset($value['ADDON'])){
							for($i=0;$i<count($value['ADDON']);$i++){

								if(isset($value['ADDON'][$i]['ID'])){

								}else {
									$data = array(
									   'EMP_PAYSLIP_ID' => $payslip_id,
									   'NAME' => $value['ADDON'][$i]['NAME'],
									   'AMOUNT' => $value['ADDON'][$i]['AMOUNT'],
									   'TYPE' => $value['ADDON'][$i]['TYPE']
									);
									$this->db->insert('employee_payslip_addon', $data);
								}
							}
						}
						return array('status'=>true, 'message'=>"Payslip Generated Successfully");
					}
		    	}
			}
	    }
	    function fetchPayslipAddonDetails($empid,$stucId,$gen_date){
	    	// $sql="SELECT ID,PROFILE_ID,PAY_STRUCTURE_ID,(SELECT ID FROM employee_payslip WHERE ID=employee_profile.ID) AS PAYSLIP_ID,(SELECT GENERATION_DATE FROM employee_payslip WHERE ID=employee_profile.ID) AS GEN_DATE,(SELECT STATUS FROM employee_payslip WHERE ID=employee_profile.ID) AS STATUS,(SELECT NETPAY FROM employee_payslip WHERE ID=employee_profile.ID) AS NETPAY FROM employee_profile WHERE employee_profile.ID='$empid' AND employee_profile.PAY_STRUCTURE_ID='$stucId'";
	    	$sql="SELECT ID,PROFILE_ID,PAY_STRUCTURE_ID,(SELECT ID FROM employee_payslip WHERE EMP_PROFILE_ID=employee_profile.ID AND GENERATION_DATE='$gen_date') AS PAYSLIP_ID,(SELECT GENERATION_DATE FROM employee_payslip WHERE EMP_PROFILE_ID=employee_profile.ID AND GENERATION_DATE='$gen_date') AS GEN_DATE,(SELECT STATUS FROM employee_payslip WHERE EMP_PROFILE_ID=employee_profile.ID AND GENERATION_DATE='$gen_date') AS STATUS,(SELECT NETPAY FROM employee_payslip WHERE EMP_PROFILE_ID=employee_profile.ID AND GENERATION_DATE='$gen_date') AS NETPAY FROM employee_profile WHERE employee_profile.ID='$empid' AND employee_profile.PAY_STRUCTURE_ID='$stucId'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			foreach ($result as $key => $value) {
				$payslipId=$value['PAYSLIP_ID'];
				$sql1="SELECT * FROM employee_payslip_addon WHERE EMP_PAYSLIP_ID='$payslipId'";
				$result[$key]['addon'] = $this->db->query($sql1, $return_object = TRUE)->result_array();
			}
			// print_r($result);exit;
			return $result[0];
			// if($result){
			// 	$payslipId=$result[0]['ID'];
			// 	$sql1="SELECT * FROM employee_payslip_addon WHERE EMP_PAYSLIP_ID='$payslipId'";
			// 	return $result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
			// }
			// print_r($result1);exit;
	    }
	    function fetchEmployeePayslipDetails($id){
	    	// $sql="SELECT ID,PROFILE_ID,PAY_STRUCTURE_ID,(SELECT ID FROM employee_payslip WHERE ID=employee_profile.ID) AS PAYSLIP_ID,(SELECT GENERATION_DATE FROM employee_payslip WHERE ID=employee_profile.ID) AS GEN_DATE,(SELECT STATUS FROM employee_payslip WHERE ID=employee_profile.ID) AS STATUS,(SELECT NETPAY FROM employee_payslip WHERE ID=employee_profile.ID) AS NETPAY FROM employee_profile WHERE employee_profile.ID='$id'";

	    	// modified on 2-5-17

	    	$sql="SELECT * FROM employee_payslip WHERE EMP_PROFILE_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	  	
	  	// payslip generation
	  	function getEmployeeBasic_details($id,$genDate){
	  		$sql="SELECT ID,PROFILE_ID,DEPT_ID,PAY_STRUCTURE_ID,EMP_POSTION_ID,BASIC_PAY,
				(SELECT NAME FROM paystructure WHERE ID=PAY_STRUCTURE_ID) AS STRUCURE_NAME,
				(SELECT FREQUENCY FROM paystructure WHERE ID=PAY_STRUCTURE_ID) AS FREQUENCY,
				(SELECT NAME FROM department WHERE ID=DEPT_ID) AS DEPT_NAME,
				(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NAME,
				(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NO,
				(SELECT NAME FROM employee_position WHERE ID=EMP_POSTION_ID) AS POSITION,
				(SELECT GENERATION_DATE FROM employee_payslip WHERE EMP_PROFILE_ID=PROFILE_ID AND PAYSTRUCTURE_ID=PAY_STRUCTURE_ID AND GENERATION_DATE='$genDate') AS GENERATION_DATE,(SELECT ID FROM employee_payslip WHERE EMP_PROFILE_ID=PROFILE_ID AND PAYSTRUCTURE_ID=PAY_STRUCTURE_ID AND GENERATION_DATE='$genDate') AS PAYSLIP_ID
				FROM employee_profile WHERE ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	  	}
	  	function getPayslipList($id){
	  		$sql="SELECT * FROM employee_payslip_addon WHERE EMP_PAYSLIP_ID='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
	  	}
	  	function fetchPayslipDetailforAllEmployee(){
	  	// 	$sql="select ID,PROFILE_ID,DEPT_ID,PAY_STRUCTURE_ID,
				// (SELECT NAME FROM paystructure WHERE ID=PAY_STRUCTURE_ID) AS STRUCURE_NAME,
				// (SELECT FREQUENCY FROM paystructure WHERE ID=PAY_STRUCTURE_ID) AS FREQUENCY,
				// (SELECT NAME FROM department WHERE ID=DEPT_ID) AS DEPT_NAME,
				// (SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NAME,
				// (SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NO,
				// (SELECT STATUS FROM employee_payslip WHERE EMP_PROFILE_ID=PROFILE_ID AND PAYSTRUCTURE_ID=PAY_STRUCTURE_ID) AS STATUS,
				// (SELECT NETPAY FROM employee_payslip WHERE EMP_PROFILE_ID=PROFILE_ID AND PAYSTRUCTURE_ID=PAY_STRUCTURE_ID) AS TOTAL_AMOUNT,
				// (SELECT GENERATION_DATE FROM employee_payslip WHERE EMP_PROFILE_ID=PROFILE_ID AND PAYSTRUCTURE_ID=PAY_STRUCTURE_ID) AS GEN_DATE
				// from employee_profile where PAY_STRUCTURE_ID !=''";
	  		$arrayList=[];
	  		$sql="SELECT ID,PROFILE_ID,DEPT_ID,PAY_STRUCTURE_ID,
				(SELECT NAME FROM paystructure WHERE ID=PAY_STRUCTURE_ID) AS STRUCURE_NAME,
				(SELECT FREQUENCY FROM paystructure WHERE ID=PAY_STRUCTURE_ID) AS FREQUENCY,
				(SELECT NAME FROM department WHERE ID=DEPT_ID) AS DEPT_NAME,
				(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NAME,
				(SELECT ADMISSION_NO FROM PROFILE WHERE ID=PROFILE_ID) AS EMPLOYEE_NO
				from employee_profile where PAY_STRUCTURE_ID !=''";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$PayslipList=array();
			foreach ($result as $key => $value) {
				$empID=$value['ID'];
				$profile_id=$value['PROFILE_ID'];
				$sql1="SELECT ID,GENERATION_DATE,EMP_PROFILE_ID,PAYSTRUCTURE_ID,STATUS,NETPAY,
						(SELECT NAME FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS STRUCURE_NAME,
						(SELECT FREQUENCY FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS FREQUENCY,
						(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID='$profile_id') AS EMPLOYEE_NAME,
						(SELECT ADMISSION_NO FROM PROFILE WHERE ID='$profile_id') AS EMPLOYEE_NO
						from employee_payslip where EMP_PROFILE_ID='$empID'";
  				$tempArray = $this->db->query($sql1, $return_object = TRUE)->result_array();
				foreach ($tempArray as  $value) {
					$PayslipList[]=$value;
				}
  				// array_push($arrayList, $result1);
			}
			
			// $new = array_map(function($arr) {
			// 			// print_r($arr);
			// 		    return $arr;
			// 		}, $PayslipList);
			// print_r($PayslipList);
			// exit();
			return $PayslipList;
	  	}
	  	function fetchPaylipDetailbasedonDept($deptid,$fromdate,$uptodate){
	  		if($deptid!=null && $fromdate==null && $uptodate==null){
	  			$sql="select * from employee_profile where DEPT_ID='$deptid'";
	  			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				$result1=array();
	  			foreach ($result as  $value) {
	  				$emp_id=$value['ID'];
					$profile_id=$value['PROFILE_ID'];
	  				$sql1="SELECT ID,GENERATION_DATE,EMP_PROFILE_ID,PAYSTRUCTURE_ID,STATUS,NETPAY,
							(SELECT NAME FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS STRUCURE_NAME,
							(SELECT FREQUENCY FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS FREQUENCY,
							(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID='$profile_id') AS EMPLOYEE_NAME,
							(SELECT ADMISSION_NO FROM PROFILE WHERE ID='$profile_id') AS EMPLOYEE_NO
							from employee_payslip where EMP_PROFILE_ID='$emp_id'";
	  				$result1[] = $this->db->query($sql1, $return_object = TRUE)->result_array();
	  			}
	  			return $result1[0];
	  		}
	  		else if($deptid!=null && $fromdate!=null && $uptodate==null){
				$sql="select * from employee_profile where DEPT_ID='$deptid'";
	  			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				$result1=array();
	  			foreach ($result as  $value) {
	  				$emp_id=$value['ID'];
					$profile_id=$value['PROFILE_ID'];
	  				$sql1="SELECT ID,GENERATION_DATE,EMP_PROFILE_ID,PAYSTRUCTURE_ID,STATUS,NETPAY,
							(SELECT NAME FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS STRUCURE_NAME,
							(SELECT FREQUENCY FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS FREQUENCY,
							(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID='$profile_id') AS EMPLOYEE_NAME,
							(SELECT ADMISSION_NO FROM PROFILE WHERE ID='$profile_id') AS EMPLOYEE_NO
							from employee_payslip where EMP_PROFILE_ID='$emp_id' and GENERATION_DATE >= '$fromdate'";
	  				$result1[] = $this->db->query($sql1, $return_object = TRUE)->result_array();
	  			}
				return $result1[0];
	  		}
	  		else if($deptid!=null && $fromdate==null && $uptodate!=null){
				$sql="select * from employee_profile where DEPT_ID='$deptid'";
	  			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				$result1=array();
	  			foreach ($result as  $value) {
	  				$emp_id=$value['ID'];
					$profile_id=$value['PROFILE_ID'];
	  				$sql1="SELECT ID,GENERATION_DATE,EMP_PROFILE_ID,PAYSTRUCTURE_ID,STATUS,NETPAY,
							(SELECT NAME FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS STRUCURE_NAME,
							(SELECT FREQUENCY FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS FREQUENCY,
							(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID='$profile_id') AS EMPLOYEE_NAME,
							(SELECT ADMISSION_NO FROM PROFILE WHERE ID='$profile_id') AS EMPLOYEE_NO
							from employee_payslip where EMP_PROFILE_ID='$emp_id' and GENERATION_DATE <= '$uptodate'";
	  				$result1[] = $this->db->query($sql1, $return_object = TRUE)->result_array();
	  			}
				return $result1[0];
	  		}
	  		else if($deptid!=null && $fromdate!=null && $uptodate!=null){
				$sql="select * from employee_profile where DEPT_ID='$deptid'";
	  			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
				$result1=array();
	  			foreach ($result as  $value) {
	  				$emp_id=$value['ID'];
					$profile_id=$value['PROFILE_ID'];
	  				$sql1="SELECT ID,GENERATION_DATE,EMP_PROFILE_ID,PAYSTRUCTURE_ID,STATUS,NETPAY,
							(SELECT NAME FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS STRUCURE_NAME,
							(SELECT FREQUENCY FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS FREQUENCY,
							(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID='$profile_id') AS EMPLOYEE_NAME,
							(SELECT ADMISSION_NO FROM PROFILE WHERE ID='$profile_id') AS EMPLOYEE_NO
							from employee_payslip where EMP_PROFILE_ID='$emp_id' and GENERATION_DATE BETWEEN '$fromdate' AND '$uptodate'";
	  				$result1[] = $this->db->query($sql1, $return_object = TRUE)->result_array();
	  			}
				return $result1[0];
	  		}
	  	}
	  	function fetchApproveStatusDetails($status){
	  		if($status=='Pending'){
	  			// $sql="SELECT * FROM employee_payslip WHERE STATUS='Pending'";
	  			$sql="select ID,GENERATION_DATE,EMP_PROFILE_ID,PAYSTRUCTURE_ID,STATUS,
					(SELECT NAME FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS STRUCURE_NAME,
					(SELECT PROFILE_ID FROM employee_profile WHERE ID=EMP_PROFILE_ID) AS PROF_ID,
					(SELECT DEPT_ID FROM employee_profile WHERE ID=EMP_PROFILE_ID) AS DEP_ID,
					(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROF_ID) AS EMPLOYEE_NAME,
					(SELECT NAME FROM department WHERE ID=DEP_ID) AS DEPT_NAME
					from employee_payslip where STATUS='Pending'";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	  		}else if($status=='Rejected'){
	  			$sql="select ID,GENERATION_DATE,EMP_PROFILE_ID,PAYSTRUCTURE_ID,STATUS,
					(SELECT NAME FROM paystructure WHERE ID=PAYSTRUCTURE_ID) AS STRUCURE_NAME,
					(SELECT PROFILE_ID FROM employee_profile WHERE ID=EMP_PROFILE_ID) AS PROF_ID,
					(SELECT DEPT_ID FROM employee_profile WHERE ID=EMP_PROFILE_ID) AS DEP_ID,
					(SELECT CONCAT(FIRSTNAME,' ',LASTNAME) FROM PROFILE WHERE ID=PROF_ID) AS EMPLOYEE_NAME,
					(SELECT NAME FROM department WHERE ID=DEP_ID) AS DEPT_NAME
					from employee_payslip where STATUS='Rejected'";
				return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	  		}
	  		
	  	}
	  	function updatepayStatus($id,$status){
	  		// print_r($status);exit;
	  		$data = array(
			   'STATUS' => $status['payStatus']
			);
			$this->db->where('ID', $id);
			$this->db->update('employee_payslip', $data); 
			return array('status'=>true, 'message'=>"Record Updated Successfully");
	  	}
	  	function deleteEmpoyeePayslip($id){
	  		$sql="DELETE FROM employee_payslip where ID='$id'";
			$result = $this->db->query($sql);
			$sql1="select * from employee_payslip_addon where EMP_PAYSLIP_ID='$id'";
			$result1 = $this->db->query($sql1, $return_object = TRUE)->result_array();
			foreach ($result1 as $key => $value) {
				$empPayslip_ID=$value['ID'];
				$sql="DELETE FROM employee_payslip_addon where ID='$empPayslip_ID'";
				$result = $this->db->query($sql);
			}
			// exit;
	    	return $this->db->affected_rows();
	  	}
	  	function checkANyPayItemAssigned_to_employee($id,$paystru_Id ){
	  		$sql="select PAYSTRUCTURE_ID,(select PAY_STRUCTURE_ID from employee_profile where PAY_STRUCTURE_ID=PAYSTRUCTURE_ID) AS STRUCTURE_ID from payitemstructure where PAYITEM_ID='$id' and PAYSTRUCTURE_ID='$paystru_Id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
			// print_r($result);exit;
	  	}
	  	function regererateEmployeePayslip($payslipid,$value){
	  		// print_r($value);
	  		// exit;
	  		if($payslipid){
	  			$data = array(
				   'STATUS' => 'Pending',
				   'NETPAY' => $value['Net_pay'],
				);
				$this->db->where('ID', $payslipid);
				$this->db->update('employee_payslip', $data);
				return array('status'=>true, 'message'=>"Payslip Generated Successfully");
	  		}
	  	}
	}
?>
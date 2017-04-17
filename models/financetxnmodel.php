<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class financetxnmodel extends CI_Model {

		// ------------------------------ Finance Expense -----------------------------------------------------------------
		public function addExpenseData($value){
			$id=$value['FINC_TXN_EX_ID'];
	    	$sql="SELECT count(FINC_TXN_EX_TITLE) FROM finance_txn_expense WHERE FINC_TXN_EX_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(FINC_TXN_EX_TITLE)']!=0){
				$data = array(
				   'FINC_TXN_EX_CA_ID' => $value['FINC_TXN_EX_CA_ID'],
				   'FINC_TXN_EX_TITLE' => $value['FINC_TXN_EX_TITLE'],
				   'FINC_TXN_EX_DESC' => $value['FINC_TXN_EX_DESC'],			
				   'FINC_TXN_EX_AMT' => $value['FINC_TXN_EX_AMT'],
				   'FINC_TXN_EX_DT' => $value['FINC_TXN_EX_DT'],
				   'FINC_TXN_EX_STATUS' => $value['FINC_TXN_EX_STATUS']
				);
				$this->db->where('FINC_TXN_EX_ID', $id);
				$this->db->update('finance_txn_expense', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'FINC_TXN_EX_ID'=>$id);
			}else {
				$data = array(
				   'FINC_TXN_EX_CA_ID' => $value['FINC_TXN_EX_CA_ID'],
				   'FINC_TXN_EX_TITLE' => $value['FINC_TXN_EX_TITLE'],
				   'FINC_TXN_EX_DESC' => $value['FINC_TXN_EX_DESC'],			
				   'FINC_TXN_EX_AMT' => $value['FINC_TXN_EX_AMT'],
				   'FINC_TXN_EX_DT' => $value['FINC_TXN_EX_DT'],
				   'FINC_TXN_EX_STATUS' => $value['FINC_TXN_EX_STATUS']
				);
				$this->db->insert('finance_txn_expense', $data);
				$emp_id=$this->db->insert_id();
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'FINC_TXN_EX_ID'=>$emp_id);
			}	    	
	    }
	    function getExpense_details($id){
	    	$sql="SELECT * FROM finance_txn_expense where FINC_TXN_EX_ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function getAllExpense_details(){
	    	$sql="SELECT * FROM finance_txn_expense";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function deleteExpense($id){
	    	$sql="DELETE FROM finance_txn_expense WHERE FINC_TXN_EX_ID='$id'";
	    	$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
	    }

	    // -------------------------------------  Finance Income  ---------------------------------------------------------------

	    public function addIncomeData($value){
	    	$id=$value['FINC_TXN_IN_ID'];
	    	$sql="SELECT count(FINC_TXN_IN_TITLE) FROM finance_txn_income WHERE FINC_TXN_IN_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(FINC_TXN_IN_TITLE)']!=0){
				$data = array(
				   'FINC_TXN_IN_CA_ID' => $value['FINC_TXN_IN_CA_ID'],
				   'FINC_TXN_IN_TITLE' => $value['FINC_TXN_IN_TITLE'],
				   'FINC_TXN_IN_DESC' => $value['FINC_TXN_IN_DESC'],			
				   'FINC_TXN_IN_AMT' => $value['FINC_TXN_IN_AMT'],
				   'FINC_TXN_IN_DT' => $value['FINC_TXN_IN_DT'],
				   'FINC_TXN_IN_STATUS' => $value['FINC_TXN_IN_STATUS']
				);
				$this->db->where('FINC_TXN_IN_ID', $id);
				$this->db->update('finance_txn_income', $data); 
				return array('status'=>true, 'message'=>"Record Updated Successfully",'FINC_TXN_IN_ID'=>$id);
			}else {
				$data = array(
				   'FINC_TXN_IN_CA_ID' => $value['FINC_TXN_IN_CA_ID'],
				   'FINC_TXN_IN_TITLE' => $value['FINC_TXN_IN_TITLE'],
				   'FINC_TXN_IN_DESC' => $value['FINC_TXN_IN_DESC'],			
				   'FINC_TXN_IN_AMT' => $value['FINC_TXN_IN_AMT'],
				   'FINC_TXN_IN_DT' => $value['FINC_TXN_IN_DT'],
				   'FINC_TXN_IN_STATUS' => $value['FINC_TXN_IN_STATUS']
				);
				$this->db->insert('finance_txn_income', $data);
				$emp_id=$this->db->insert_id();
				return array('status'=>true, 'message'=>"Record Inserted Successfully",'FINC_TXN_IN_ID'=>$emp_id);
			}
	    }
	    function getIncome_details($id){
	    	$sql="SELECT * FROM finance_txn_income where FINC_TXN_IN_ID ='$id'";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function getAllIncome_details(){
	    	$sql="SELECT * FROM finance_txn_income";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	    function deleteIncome($id){
	    	$sql="DELETE FROM finance_txn_income WHERE FINC_TXN_IN_ID='$id'";
	    	$result = $this->db->query($sql);
	    	return $this->db->affected_rows();
	    }
	    function fetchCategory(){
	    	$sql="SELECT * FROM finance_category";
			return $result = $this->db->query($sql, $return_object = TRUE)->result_array();
	    }
	}
?>
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class payrollmod_api extends CI_Model {

		// ------------------------------------- Payroll category ------------------------------------------------------

		public function PayrollCategory(){
			
			$id=$this->input->post('PR_C_H_ID');
			$parent_id=$this->input->post('PR_C_L_ID');
			$sql="SELECT count(PR_C_H_NAME) FROM payroll_category_head WHERE PR_C_H_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(PR_C_H_NAME)']!=0){
		    	$cat_name=$this->input->post('PR_C_H_NAME');
		    	$cat_code=$this->input->post('PR_C_H_CODE');
		    	$cat_type=$this->input->post('PR_C_H_TYPE');
		    	$val_type=$this->input->post('PR_C_H_VAL_TYPE');
		    	$data=array(
	    			'PR_C_H_NAME'=>$cat_name,
	    			'PR_C_H_CODE'=>$cat_code,
	    			'PR_C_H_TYPE'=>$cat_type,
	    			'PR_C_H_VAL_TYPE'=>$val_type
	    		);
	    		$this->db->where('PR_C_H_ID', $id);
	    		$this->db->update('payroll_category_head', $data);

	    		if($val_type=='ConditionalFormula'){
		    		$val1_count=count($this->input->post('PR_C_L_VALI_1'));
		    		for($i=0;$i<$val1_count;$i++){
		    			$line_data=array(
			    			'PR_C_L_H_ID'=>$id,
			    			'PR_C_L_CALC_TYPE'=>$val_type,
			    			'PR_C_L_VALI_1'=>$_POST['PR_C_L_VALI_1'][$i],
			    			'PR_C_L_VALI_2'=>$_POST['PR_C_L_VALI_2'][$i],
			    			'PR_C_L_VALI_3'=>$_POST['PR_C_L_VALI_3'][$i],
			    			'PR_C_L_VALI_4'=>$_POST['PR_C_L_VALI_4'][$i],
			    			'PR_C_L_DEFAULT_VAL'=>$_POST['PR_C_L_DEFAULT_VAL']
			    		);
			    		$this->db->where('PR_C_L_ID', $parent_id);
			    		$this->db->update('payroll_category_line', $line_data); 
		    		}
		    	}else {
		    		$line_data=array(
		    			'PR_C_L_H_ID'=>$id,
		    			'PR_C_L_CALC_TYPE'=>$val_type,
		    			'PR_C_L_VALI_1'=>$this->input->post('PR_C_L_VALI_1'),
		    			'PR_C_L_VALI_2'=>$this->input->post('PR_C_L_VALI_2'),
		    			'PR_C_L_VALI_3'=>$this->input->post('PR_C_L_VALI_3'),
		    			'PR_C_L_VALI_4'=>$this->input->post('PR_C_L_VALI_4'),
		    			'PR_C_L_DEFAULT_VAL'=>$this->input->post('PR_C_L_DEFAULT_VAL')
		    		);
		    		$this->db->where('PR_C_L_ID', $parent_id);
		    		$this->db->update('payroll_category_line', $line_data); 
		    	}
		    	return array('status'=>true, 'message'=>"Record Updated Successfully");
		    }else {
			
		    	$cat_name=$this->input->post('PR_C_H_NAME');
		    	$cat_code=$this->input->post('PR_C_H_CODE');
		    	$cat_type=$this->input->post('PR_C_H_TYPE');
		    	$val_type=$this->input->post('PR_C_H_VAL_TYPE');
		    	$data=array(
	    			'PR_C_H_NAME'=>$cat_name,
	    			'PR_C_H_CODE'=>$cat_code,
	    			'PR_C_H_TYPE'=>$cat_type,
	    			'PR_C_H_VAL_TYPE'=>$val_type
	    		);
	    		$this->db->insert('payroll_category_head', $data);
	    		$category_id=$this->db->insert_id();
		    	if($val_type=='CF'){
				
		    		$val1_count=count($this->input->post('PR_C_L_VALI_1'));
		    		for($i=0;$i<$val1_count;$i++){
		    			$line_data=array(
			    			'PR_C_L_CALC_TYPE'=>$category_id,
			    			'PR_C_L_CALC_TYPE'=>$val_type,
			    			'PR_C_L_VALI_1'=>$_POST['PR_C_L_VALI_1'][$i],
			    			'PR_C_L_VALI_2'=>$_POST['PR_C_L_VALI_2'][$i],
			    			'PR_C_L_VALI_3'=>$_POST['PR_C_L_VALI_3'][$i],
			    			'PR_C_L_VALI_4'=>$_POST['PR_C_L_VALI_4'][$i],
			    			'PR_C_L_DEFAULT_VAL'=>$_POST['PR_C_L_DEFAULT_VAL']
			    		);
			    		$this->db->insert('payroll_category_line', $line_data); 
		    		}
		    	}else {
				
		    		$line_data1=array(
		    			'PR_C_L_CALC_TYPE'=>$category_id,
		    			'PR_C_L_CALC_TYPE'=>$val_type,
		    			'PR_C_L_VALI_1'=>$_POST['PR_C_L_VALI_1'][0],
		    			'PR_C_L_VALI_2'=>$_POST['PR_C_L_VALI_2'][0],
		    			'PR_C_L_VALI_3'=>$_POST['PR_C_L_VALI_3'][0],
		    			'PR_C_L_VALI_4'=>$_POST['PR_C_L_VALI_4'][0],
		    			'PR_C_L_DEFAULT_VAL'=>$_POST['PR_C_L_DEFAULT_VAL']
		    		);
		    		$this->db->insert('payroll_category_line', $line_data1); 
		    	}	
		    	return array('status'=>true, 'message'=>"Record Inserted Successfully");
		    }  	
	    }



		// ------------------------------------------- Payroll Group -------------------------------------------------

	    function payrollGroup(){
	    	$id=$this->input->post('PR_G_H_ID');
			$group_id=$this->input->post('PR_G_L_ID');
			$sql="SELECT count(PR_G_H_NAME) FROM payroll_group_head WHERE PR_G_H_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			$val_type=$this->input->post('PR_G_H_VAL_TYPE');
			if($result[0]['count(PR_G_H_NAME)']!=0){
		    	$data=array(
	    			'PR_G_H_NAME'=>$this->input->post('PR_G_H_NAME'),
	    			'PR_G_H_PAY_H_PERIOD_TYPE'=>$this->input->post('PR_G_H_PAY_H_PERIOD_TYPE'),
	    			'PR_G_H_PAYSLIP_GEN_DT'=>$this->input->post('PR_G_H_PAYSLIP_GEN_DT'),
	    			'PR_G_H_LOP_CRITERIA_YN'=>$this->input->post('PR_G_H_LOP_CRITERIA_YN'),
	    			'PR_G_H_VAL_TYPE'=>$this->input->post('PR_G_H_VAL_TYPE'),
	    		);
	    		$this->db->where('PR_G_H_ID', $id);
	    		$this->db->update('payroll_group_head', $data);

	    		if($val_type=='ConditionalFormula'){
		    		$val1_count=count($this->input->post('PR_C_L_VALI_1'));
		    		for($i=0;$i<$val1_count;$i++){
		    			$line_data=array(
			    			'PR_G_L_H_ID'=>$id,
			    			'PR_G_L_CALC_TYPE'=>$val_type,
			    			'PR_G_L_VALI_1'=>$_POST['PR_G_L_VALI_1'][$i],
			    			'PR_G_L_VALI_2'=>$_POST['PR_G_L_VALI_2'][$i],
			    			'PR_G_L_VALI_3'=>$_POST['PR_G_L_VALI_3'][$i],
			    			'PR_G_L_VALI_4'=>$_POST['PR_G_L_VALI_4'][$i],
			    			'PR_G_L_DEFAULT_VAL'=>$_POST['PR_G_L_DEFAULT_VAL']
			    		);
			    		$this->db->where('PR_G_L_ID', $group_id);
			    		$this->db->update('payroll_group_line', $line_data); 
		    		}
		    	}else {
		    		$line_data=array(
		    			'PR_G_L_H_ID'=>$id,
		    			'PR_G_L_CALC_TYPE'=>$val_type,
		    			'PR_G_L_VALI_1'=>$this->input->post('PR_G_L_VALI_1'),
		    			'PR_G_L_VALI_2'=>$this->input->post('PR_G_L_VALI_2'),
		    			'PR_G_L_VALI_3'=>$this->input->post('PR_G_L_VALI_3'),
		    			'PR_G_L_VALI_4'=>$this->input->post('PR_G_L_VALI_4'),
		    			'PR_G_L_DEFAULT_VAL'=>$this->input->post('PR_G_L_DEFAULT_VAL')
		    		);
		    		$this->db->where('PR_G_L_ID', $group_id);
		    		$this->db->update('payroll_group_line', $line_data); 
		    	}
		    	return array('status'=>true, 'message'=>"Record Updated Successfully");
		    }else {
		    	$data=array(
	    			'PR_G_H_NAME'=>$this->input->post('PR_G_H_NAME'),
	    			'PR_G_H_PAY_H_PERIOD_TYPE'=>$this->input->post('PR_G_H_PAY_H_PERIOD_TYPE'),
	    			'PR_G_H_PAYSLIP_GEN_DT'=>$this->input->post('PR_G_H_PAYSLIP_GEN_DT'),
	    			'PR_G_H_LOP_CRITERIA_YN'=>$this->input->post('PR_G_H_LOP_CRITERIA_YN'),
	    			'PR_G_H_VAL_TYPE'=>$this->input->post('PR_G_H_VAL_TYPE'),
	    		);
	    		$this->db->insert('payroll_group_head', $data);
	    		$grp_head_id=$this->db->insert_id();
		    	if($val_type=='CF'){
		    		$val1_count=count($this->input->post('PR_G_L_VALI_1'));
		    		for($i=0;$i<$val1_count;$i++){
		    			$line_data=array(
			    			'PR_G_L_H_ID'=>$grp_head_id,
			    			'PR_G_L_CALC_TYPE'=>$val_type,
			    			'PR_G_L_VALI_1'=>$_POST['PR_G_L_VALI_1'][$i],
			    			'PR_G_L_VALI_2'=>$_POST['PR_G_L_VALI_2'][$i],
			    			'PR_G_L_VALI_3'=>$_POST['PR_G_L_VALI_3'][$i],
			    			'PR_G_L_VALI_4'=>$_POST['PR_G_L_VALI_4'][$i],
			    			'PR_G_L_DEFAULT_VAL'=>$_POST['PR_G_L_DEFAULT_VAL']
			    		);
			    		$this->db->insert('payroll_group_line', $line_data); 
		    		}
		    	}else {
		    		$line_data=array(
		    			'PR_G_L_H_ID'=>$grp_head_id,
		    			'PR_G_L_CALC_TYPE'=>$val_type,
		    			'PR_G_L_VALI_1'=>$_POST['PR_G_L_VALI_1'][0],
		    			'PR_G_L_VALI_2'=>$_POST['PR_G_L_VALI_2'][0],
		    			'PR_G_L_VALI_3'=>$_POST['PR_G_L_VALI_3'][0],
		    			'PR_G_L_VALI_4'=>$_POST['PR_G_L_VALI_4'][0],
		    			'PR_G_L_DEFAULT_VAL'=>$this->input->post('PR_G_L_DEFAULT_VAL')
		    		);
		    		$this->db->insert('payroll_group_line', $line_data); 
		    	}	
		    	return array('status'=>true, 'message'=>"Record Inserted Successfully");
		    } 
	    }

	    // ------------------------------- Employee Payslip Generation ----------------------------------------

	    function AddPayslipGeneration(){
	    	$id=$this->input->post('PS_H_ID');
	    	$gen_line_id=$this->input->post('PS_L_ID');
	    	$sql="SELECT count(PS_H_EMP_NO) FROM payslip_gen_head WHERE PS_H_ID='$id'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if($result[0]['count(PS_H_EMP_NO)']!=0){
				$data=array(
	    			'PS_H_PR_GRP_ID'=>$this->input->post('PS_H_PR_GRP_ID'),
	    			'PS_H_EMP_NO'=>$this->input->post('PS_H_EMP_NO'),
	    			'PS_H_TOT_E_AMT'=>$this->input->post('PS_H_TOT_E_AMT'),
	    			'PS_H_TOT_D_AMT'=>$this->input->post('PS_H_TOT_D_AMT'),
	    			'PS_H_NET_PAY'=>$this->input->post('PS_H_NET_PAY'),
	    			'PS_H_STATUS'=>$this->input->post('PS_H_STATUS')
	    		);
	    		$this->db->where('PS_H_ID', $id);
	    		$this->db->update('payslip_gen_head', $data);
	    		$val_count=count($this->input->post('PS_L_VAL_TYPE'));
	    		for($i=0;$i<$val_count;$i++){
	    			$line=array(
		    			'PS_L_H_ID'=>$id,
		    			'PS_L_VAL_TYPE'=>$_POST['PS_L_VAL_TYPE'][$i],
		    			'PS_L_CATE_NAME'=>$_POST['PR_G_L_VALI_1'][$i],
		    			'PS_L_CATE_AMT'=>$_POST['PR_G_L_VALI_2'][$i],
		    			'PS_L_NEW_OR_OLD'=>$_POST['PR_G_L_VALI_3'][$i]
		    		);
		    		$this->db->where('PS_L_ID', $gen_line_id);
		    		$this->db->update('payslip_gen_line', $line); 
	    		}
	    		return array('status'=>true, 'message'=>"Record Updated Successfully");
			}else {
				$data=array(
	    			'PS_H_PR_GRP_ID'=>$this->input->post('PS_H_PR_GRP_ID'),
	    			'PS_H_EMP_NO'=>$this->input->post('PS_H_EMP_NO'),
	    			'PS_H_TOT_E_AMT'=>$this->input->post('PS_H_TOT_E_AMT'),
	    			'PS_H_TOT_D_AMT'=>$this->input->post('PS_H_TOT_D_AMT'),
	    			'PS_H_NET_PAY'=>$this->input->post('PS_H_NET_PAY'),
	    			'PS_H_STATUS'=>$this->input->post('PS_H_STATUS')
	    		);
	    		$this->db->insert('payslip_gen_head', $data);
	    		$gen_head_id=$this->db->insert_id();
	    		$val_count=count($this->input->post('PS_L_VAL_TYPE'));
	    		for($i=0;$i<$val_count;$i++){
	    			$line=array(
		    			'PS_L_H_ID'=>$gen_head_id,
		    			'PS_L_VAL_TYPE'=>$_POST['PS_L_VAL_TYPE'][$i],
		    			'PS_L_CATE_NAME'=>$_POST['PR_G_L_VALI_1'][$i],
		    			'PS_L_CATE_AMT'=>$_POST['PR_G_L_VALI_2'][$i],
		    			'PS_L_NEW_OR_OLD'=>$_POST['PR_G_L_VALI_3'][$i]
		    		);
		    		$this->db->insert('payslip_gen_line', $line);
	    		}
	    		return array('status'=>true, 'message'=>"Record Inserted Successfully");
			}
	    }
	}
?>
<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class HrSettingModel extends CI_Model {

    function leaveTypeEdit($id){
	$this->db->select('*');
	$this->db->from('employee_leave_type');
	$this->db->where('EMP_L_ID',$id);
	$data=$this->db->get();
	return $data->result_array();
    }
    }
?>
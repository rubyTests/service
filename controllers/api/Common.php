<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Common extends CI_Controller  {
    function Common()
    {
        parent::__construct();
        $this->load->model('api/CommonMod');
		$this->load->helper('string');
		header("Access-Control-Allow-Origin: *");
    }
	// Login
	function login(){
		$users=$this->CommonMod->login();
		if (!empty($users)){
			//echo json_encode($users);
			echo random_string('alnum', 50);
		}
		else
		{
			echo "Error";
		}
	}
}

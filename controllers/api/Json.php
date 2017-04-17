<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Json extends CI_Controller  {
    function Json()
    {
        parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
    }
	// payroll group payslip 
	function payroll(){
		echo $value='{"status":true,"message":[{"par_roll_grp":"Test","rec_payslip":"February 2017","employees":"23" },{"par_roll_grp":"Grp","rec_payslip":"January 2017","employees":"13" },{"par_roll_grp":"Emp","rec_payslip":"December 2016","employees":"8" },{"par_roll_grp":"Helo","rec_payslip":"November 2016","employees":"3" },{"par_roll_grp":"salary","rec_payslip":"October 2016","employees":"4" },{"par_roll_grp":"asgf","rec_payslip":"September 2016","employees":"12" }]}';
	}
}

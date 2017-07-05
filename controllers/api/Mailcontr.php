<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailcontr extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('email');
	    }
	public function index()
	{
		echo "Hello";exit;
		//$this->load->view('login');
	}
	function sendmail($email)
	{
		echo "Hello";exit;
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'manisrikan@gmail.com', // change it to yours
			'smtp_pass' => '16121993', // change it to yours
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		);
		$this->email->initialize($config);
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('manisrikan@gmail.com'); // change it to yours
		$this->email->to($email);
		$this->email->subject('mail send');
		$this->email->message('hi ');
		if (!$this->email->send())
		{
			echo 'Generate error';
		}
		else{
			echo 'Mail Sended Successfully';
		}
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/server.php';
//require APPPATH . '/libraries/Token.php';
class Auth extends CI_Controller {    
    function Auth()
    {
		parent::__construct();
		//$this->load->model('hrConfigModel');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type,access_token");
		header("Access-Control-Allow-Methods: GET,POST,DELETE");
		ini_set('display_errors',1);error_reporting(E_ALL);
        $userIDByToken="";
    }
    function test(){
          echo "test";
       }
    function getToken(){
      // Handle a request for an OAuth2.0 Access Token and send the response to the client
      loadServer('getToken');
    }
    function getAuthCode(){
        loadServer('getAuthCode');
    }
    function getResource(){
        loadServer('getResource');   
    }
    function checkUser(){
        // print_r($_POST["my_parm"]);
        // exit;
        loadServer('checkUser');  
    }
    function checkClient(){
        loadServer('checkClient');  
    }
   
}

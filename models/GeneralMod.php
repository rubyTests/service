<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class GeneralMod extends CI_Model {

		// Login Details 
		
		public function getLoginDetail($id,$id1){
			$sql="SELECT * FROM `user` where USER_EMAIL='$id' AND USER_PASSWORD='$id1'";
			$result = $this->db->query($sql, $return_object = TRUE)->result_array();
			if(!empty($result)){
				return $result;
			}
		}
		
		public function addPushReg($Regid){
			$id=1;
			$data = array(
			   'regID' => $Regid
			);
			$this->db->where('id', $id);
			$this->db->update('pushNotify', $data);
			return true;
		}
		
		function sendWay2SMS($phone)
 {
	 
	 
	 $random_no = mt_rand(100000,999999);
	 $data = array(
		'phone' => $phone,
		'random_no' => $random_no
	);
	$this->db->insert('mobile_otp', $data);
	
	$msg = "OTP for Rubycampus E-application is ".$random_no.". This password is valid for 15 mins whichever is earlier. Do not share it with anyone";
	 
   $curl = curl_init();
   $timeout = 30;
   $result = array();
   $user='7418225915';
   $pwd='lonlyboy';
   $uid = urlencode($user);
   $pwd = urlencode($pwd);
   //$autobalancer = rand(1, 8);
   $autobalancer = 23;
   // Setup for login
   curl_setopt($curl, CURLOPT_URL, "http://site".$autobalancer.".way2sms.com/Login1.action");
   curl_setopt($curl, CURLOPT_POST, 1);
   curl_setopt($curl, CURLOPT_POSTFIELDS, "username=".$uid."&password=".$pwd."&button=Login");
   curl_setopt($curl, CURLOPT_COOKIESESSION, 1);
   curl_setopt($curl, CURLOPT_COOKIEFILE, "cookie_way2sms");
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($curl, CURLOPT_MAXREDIRS, 20);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.5) Gecko/2008120122 Firefox/3.0.5");
   curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
   curl_setopt($curl, CURLOPT_REFERER, "http://site".$autobalancer.".way2sms.com/");
   $text = curl_exec($curl);
   if (curl_errno($curl))
     return "access error : ". curl_error($curl);
   // Check for proper login
   $pos = stripos(curl_getinfo($curl, CURLINFO_EFFECTIVE_URL), "ebrdg.action");
   if ($pos === "FALSE" || $pos == 0 || $pos == "")
     return "invalid login";
   // Check the message
   if (trim($msg) == "" || strlen($msg) == 0)
     return "invalid message";
   // Take only the first 140 characters of the message
   $msg = urlencode(substr($msg, 0, 140));
   // Store the numbers from the string to an array
   $pharr = explode(",", $phone);
   // Set the home page from where we can send message
   $refurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
   $newurl = str_replace("ebrdg.action?id=", "main.action?section=s&Token=", $refurl);
   curl_setopt($curl, CURLOPT_URL, $newurl);
   // Extract the token from the URL
   $jstoken = substr($newurl, 50, -41);
   //Go to the homepage
   $text = curl_exec($curl);
   // Send SMS to each number
   foreach ($pharr as $p)
   {
  // Check the mobile number
     if (strlen($p) != 10 || !is_numeric($p) || strpos($p, ".") != false)
     {
       $result[] = array('phone' => $p, 'msg' => urldecode($msg), 'result' => "invalid number");
       continue;
     }
     $p = urlencode($p);
     // Setup to send SMS
     curl_setopt($curl, CURLOPT_URL, 'http://site'.$autobalancer.'.way2sms.com/smstoss.action');
     curl_setopt($curl, CURLOPT_REFERER, curl_getinfo($curl, CURLINFO_EFFECTIVE_URL));
     curl_setopt($curl, CURLOPT_POST, 1);
     curl_setopt($curl, CURLOPT_POSTFIELDS, "ssaction=ss&Token=".$jstoken."&mobile=".$p."&message=".$msg."&button=Login");
     $contents = curl_exec($curl);
     //Check Message Status
     $pos = strpos($contents, 'Message has been submitted successfully');
     $res = ($pos !== false) ? true : false;
     $result[] = array('phone' => $p, 'msg' => urldecode($msg), 'result' => $res);
   }
   // Logout
   curl_setopt($curl, CURLOPT_URL, "http://site".$autobalancer.".way2sms.com/LogOut");
   curl_setopt($curl, CURLOPT_REFERER, $refurl);
   $text = curl_exec($curl);
   curl_close($curl);
   //print_r($text);exit;
   return true;
 }
		
	}
?>
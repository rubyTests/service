<?php
include 'PHPMailer/class.phpmailer.php';
include 'PHPMailer/PHPMailerAutoload.php';
	function mailVerify($email,$msg){
		$mail = new PHPMailer();     
		$mail->IsSMTP();
		$mail->Mailer = 'smtp';
		$mail->SMTPAuth = true;
		$mail->Host = 'ssl://smtp.googlemail.com'; // "ssl://smtp.gmail.com" didn't worked
		$mail->Port = 465;
		$mail->SMTPSecure = 'ssl';
		$mail->Username = "manisrikan@gmail.com";
		$mail->Password = "mani16121993";
		$mail->From = "admin@rubycampus.com";
		$mail->FromName = "Administrator";
		$mail->Subject = "Rubycampus mail Verification";
		$body = '<html><head><title>Mail from Cloudlogic</title></head> <body>'.$msg.'</body> </html>';
		$mail->MsgHTML($body);
		$mail->addAddress($email);
		//$mail->AddAttachment($path);
		if(!$mail->Send()) {
			//echo "Mailer Error: " . $mail->ErrorInfo;
		}else {
			//echo 'mail successfully sended';
		}
	}
?>
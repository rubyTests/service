<?php 
# Include the Autoloader (see "Libraries" for install instructions)
require 'vendor/autoload.php';
use Mailgun\Mailgun;
function mailGun($to){
	
	//use Mailgun\Mailer as MailgunMailer;

	# Instantiate the client.
	$mgClient = new Mailgun('key-5da1d873a6eb40ce92f7891c520e32a0');
	$domain = 'sandboxbdd1fa162f844008be761ad16568148e.mailgun.org';

	# Make the call to the client.
	$result = $mgClient->sendMessage($domain, array(
		'from'    => 'info@sandboxbdd1fa162f844008be761ad16568148e.mailgun.org',
		'to'      => $to,
		'subject' => 'Rubycampus',
		'text'    => 'Testing some Mailgun awesomness!',
		'html'    => '<html>HTML version of the body</html>'
	));
	return $result;
}

function mailVerification($to,$msg){
	
	//use Mailgun\Mailer as MailgunMailer;

	# Instantiate the client.
	$mgClient = new Mailgun('key-5da1d873a6eb40ce92f7891c520e32a0');
	$domain = 'sandboxbdd1fa162f844008be761ad16568148e.mailgun.org';

	# Make the call to the client.
	$result = $mgClient->sendMessage($domain, array(
		'from'    => 'info@sandboxbdd1fa162f844008be761ad16568148e.mailgun.org',
		'to'      => $to,
		'subject' => 'Rubycampus mail Verification and Set password',
		'text'    => 'Click the Below link',
		'html'    => $msg
	));
	return $result;
}

?>
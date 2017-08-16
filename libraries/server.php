<?php



// error reporting (this is a demo, after all!)
ini_set('display_errors',1);error_reporting(E_ALL);

// Autoloading (composer is preferred, but for this example let's just do this)

function loadServer($loadData){
// Localhost
$dsn      = 'mysql:dbname=rubycampus_new;host=localhost';
$username = 'root';
$password = '';

// $dsn      = 'mysql:dbname=campusen_rubycampus;host=localhost';
// $username = 'campusen_DBadmin';
// $password = 'Rubycampus@123';

require_once('oauth2-server-php/src/OAuth2/Autoloader.php');
OAuth2\Autoloader::register();

// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
$config = array(
    'access_lifetime' => 3600// expiry time
);
// Pass a storage object or array of storage objects to the OAuth2 server class
$server = new OAuth2\Server($storage,$config);



// add the grant type to your OAuth server
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));


// Add the "Authorization Code" grant type (this is where the oauth magic happens)
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

if($loadData=='getToken'){

	$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();	

}else if($loadData=='getAuthCode'){
	$request = OAuth2\Request::createFromGlobals();
	//$server->handleTokenRequest($request);
	$response = new OAuth2\Response();
	// print_r($response);
	// exit;

	// validate the authorize request
	if (!$server->validateAuthorizeRequest($request, $response)) {
		//echo "send";
	    $response->send();
	    die;
	}
	// display an authorization form
	if (empty($_POST)) {
	  exit('
	<form method="post">
	  <label>Do You Authorize TestClient?</label><br />
	  <input type="submit" name="authorized" value="yes">
	  <input type="submit" name="authorized" value="no">
	</form>');
	}

	// print the authorization code if the user has authorized your client
	$is_authorized = ($_POST['authorized'] === 'yes');
	$server->handleAuthorizeRequest($request, $response, $is_authorized);
	if ($is_authorized) {
	  // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
	  $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
	  exit("SUCCESS! Authorization Code: $code");
	}
	$response->send();
}else if($loadData=='getResource'){
	if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
	    $server->getResponse()->send();
	    die;
	}
	//echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));

}else if($loadData=='checkClient'){
	// create test clients in memory
	$clients = array('123' => array('client_secret' => '123456'));

	// create a storage object
	$memory = new OAuth2\Storage\Memory(array('client_credentials' => $clients));

	// create the grant type
	$grantType = new OAuth2\GrantType\ClientCredentials($memory);

	// this request will only allow authorization via the Authorize HTTP Header (Http Basic)
	// $grantType = new OAuth2\GrantType\ClientCredentials($storage, array(
	//     'allow_credentials_in_request_body' => false
	// ));

	// add the grant type to your OAuth server
	$server->addGrantType($grantType);

}else if($loadData=='checkUser'){
	$user_status='Y';
	$UsersList = $storage->getAuthUserDetails($user_status);
	$AllUsers=[];

	for($i=0;$i<count($UsersList);$i++){
		$AllUsers[$UsersList[$i]['USER_EMAIL']] = array('password' => $UsersList[$i]['USER_PASSWORD']);
	}
	// print_r(UsersList);exit;
	// create some users in memory
		
	//$users = array('admin@gmail.com' => array('password' => '123'));

	// create a storage object
	$memory = new OAuth2\Storage\Memory(array('user_credentials' => $AllUsers));

	//print_r($memory);exit;
	
	// create the grant type
	$grantType = new OAuth2\GrantType\UserCredentials($memory);

	// add the grant type to your OAuth server
	$server->addGrantType($grantType);

	$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();	

}else if($loadData=='checkUserPhone'){
	$user_status='Y';
	$UsersList = $storage->getAuthUserPhoneDetails($user_status);
	$AllUsers=[];

	for($i=0;$i<count($UsersList);$i++){
		$AllUsers[$UsersList[$i]['USER_PHONE']] = array('password' => $UsersList[$i]['USER_PASSWORD']);
	}
	
	// create some users in memory
		
	//$users = array('admin@gmail.com' => array('password' => '123'));

	// create a storage object
	$memory = new OAuth2\Storage\Memory(array('user_credentials' => $AllUsers));

	//print_r($memory);exit;
	
	// create the grant type
	$grantType = new OAuth2\GrantType\UserCredentials($memory);

	// add the grant type to your OAuth server
	$server->addGrantType($grantType);

	$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();	

}


}
// print_r($server);
// exit;	
<?php 
	require_once("src/FoursquareAPI.class.php"); //foursquare doc


	//foursquare creds
	$client_key = "xxxxx";  //add foursquare client key
	$client_secret = "xxxxx"; //add foursquare client secret 
	$redirect_uri = "xxxx";  //add your redirect uri

	// Load the Foursquare API library
	$foursquare = new FoursquareAPI($client_key,$client_secret);

	// If the link has been clicked, and we have a supplied code, use it to request a token
	if(array_key_exists("code",$_GET)){
		$token = $foursquare->GetToken($_GET['code'],$redirect_uri);
	}

//conncect to db
$link = mysql_connect("xxDB HOSTxxx", "xxDB USER NAMExxx", "xxxDB Passxxx");
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	$db_selected = mysql_select_db("xxxDB Namexxx");
	if (!$db_selected) {
		die ('Can\'t use foo : ' . mysql_error());
	}

?>
<!doctype html>
<html>
<head>
	<title>Add your account</title>
</head>
<body>
<h1>Add Your Foursquare Account</h1>
<p>
	<?php 
	// If we have not received a token, have them connect 
	if(!isset($token)){ 
		echo "<a href='".$foursquare->AuthenticationLink($redirect_uri)."'>Connect to this app via Foursquare</a>";
	//
	}else{
		$decoded_auth = json_decode($token,true); 
		$access_token = $decoded_auth['access_token'];
		$userinfo = file_get_contents("https://api.foursquare.com/v2/users/self?oauth_token=".$token);
		$decoded_userinfo = json_decode($userinfo, true);
		$name = $decoded_userinfo['response']['user']['firstName']; //first name
		$lname=$decoded_userinfo['response']['user']['lastName'];  //last name 
		echo "Thanks $name $lname for registering! "; 
		echo "<br /> <br />";
		echo "Your auth token is $token";
		//enter auth into db
	$sql = "INSERT INTO users (`fname`,`lname`,`token`) VALUES ('".$name."','".$lname."','".$token."')"; //insert info into db
	mysql_query($sql);
	}
	mysql_close();
	?>
	
</p>
<hr />
<?php 

?>

</body>
</html>
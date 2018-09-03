<!DOCTYPE html>
<html>
<head>
	<title>Simple OAuth page</title>
	<link rel="icon" href="favicon.ico">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<h2>Choose wisely</h2>
	<li><a href="index.php?oauth=true">Oauth</a></li>
	<li><a href="#" id="resource2">or not Oauth</a></li>
<?php

if (isset($_GET['oauth'])) {
			//first step of the flow
		    $risposta = request_to_resource_owner();
		    echo  "<h2><br> Request to resource Owner's answer is: <br></h2>";
		    echo "<h5>Response:</h5>".$risposta->response;
		    echo "<h5>User_id:</h5>".$risposta->testclient;
		    echo "<h5>User_pass:</h5>".$risposta->testpass;
		    echo "<h5>Url:</h5>".$risposta->url;
		    echo "<h5>Grant type:</h5>".$risposta->grant_type;
		    //second step of the flow
		    $risposta = request_to_authorization_server($risposta->testclient, $risposta->testpass, $risposta->url, $risposta->grant_type);
		    echo  "<h2><br> Request to Authorization Server's answer is: <br></h2>";
		    echo "<h5>Access token:</h5>".$risposta->access_token;
		    echo "<h5>Expires in:</h5>".$risposta->expires_in;
		    echo "<h5>Token type:</h5>".$risposta->token_type;
		    //getting the resource
		  	$risposta = obtain_resource($risposta->access_token,"http://localhost/SOASEC_PRJ/Resource_server/index.php");
		    echo  "<h2><br> Request to Resource Server's answer is: <br></h2>";
		    echo "<h5>Response:</h5>".$risposta->response;
		    echo "<h5>Protected Resource:</h5>".$risposta->message;
}

//Logging for debug
function write_log($data)
{
	$log = fopen("log.txt", "a");
	if(!$log) die ("Error while operating with the file");
	fwrite($log, date('d/m/Y H:i:s').' - '.print_r($data, true)."\r\n");
	fclose($log);
}

function request_to_resource_owner(){
	include_once __DIR__ . "/config.php";
	// The data to send to the API
	$postData = array(
	    'r' => 'requestToResourceOwner'
	);
	// Setup cURL
	$ch = curl_init('http://127.0.0.1/SOASEC_PRJ/Resource_owner/');
	curl_setopt_array($ch, array(
	    CURLOPT_POST => TRUE,
	    CURLOPT_RETURNTRANSFER => TRUE,
	    CURLOPT_HTTPHEADER => array(
	        'Content-Type: application/json'
	    ),
	    CURLOPT_POSTFIELDS => json_encode($postData)
	));
	// Send the request
	$response = curl_exec($ch);
	// Check for errors
	if($response === FALSE){
	    die(curl_error($ch));
	}
	// Decode the response
	$responseData = json_decode($response);
	return $responseData;
}

function request_to_authorization_server($testclient, $testpass, $url, $grantType ){
	include_once __DIR__ . "/config.php";
	// Setup cURL
	$ch = curl_init($url);
	curl_setopt_array($ch, array(
	    CURLOPT_POST => TRUE,
	    CURLOPT_RETURNTRANSFER => TRUE,
	    CURLOPT_HTTPHEADER => array(
			"Authorization: Basic " . base64_encode($testclient . ":" . $testpass)
	    ),
	    CURLOPT_POSTFIELDS => array('grant_type'=> $grantType)
	));
	// Send the request
	$response = curl_exec($ch);
	// Check for errors
	if($response === FALSE){
	    die(curl_error($ch));
	}
	// Decode the response
	$responseData = json_decode($response);
	return $responseData;
}

function obtain_resource($token, $url){
	include_once __DIR__ . "/config.php";
	// The data to send to the API
		$postData = array(
		    'r' => 'requestToResource_server',
		    't' => $token
		);
		// Setup cURL
		$ch = curl_init($url);
		curl_setopt_array($ch, array(
		    CURLOPT_POST => TRUE,
		    CURLOPT_RETURNTRANSFER => TRUE,
		    CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
		    CURLOPT_POSTFIELDS => json_encode($postData)
		));
		// Send the request
		$response = curl_exec($ch);
		// Check for errors
		if($response === FALSE){
		    die(curl_error($ch));
		}
		// Decode the response
		$responseData = json_decode($response);
		return $responseData;
}
?>
</body>
</html>
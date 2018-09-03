<?php

include_once __DIR__ . "/config.php";

//Logging for debug
function write_log($data)
{
	$log = fopen("log.txt", "a");
	if(!$log) die ("Error while operating with the file");
	fwrite($log, date('d/m/Y H:i:s').' - '.print_r($data, true)."\r\n");
	fclose($log);
}

//Escaping caratteri
function sanitize($str, $quotes = ENT_NOQUOTES)
{
	$str = htmlspecialchars($str, $quotes);
	return $str;
}

function return_something($return)
{
//Encode the stdClass object containing information and return data as a json string
	$json = json_encode($return);
	echo $json;
	//echo $return;
}
function testing()
{
	$risposta = array('response' => 'true');
	return $risposta;
}

function confirm_token($token, $url){
		// Setup cURL
		$ch = curl_init($url);

		$auth = "Authorization: Bearer " .$token;

		curl_setopt_array($ch, array(
		    CURLOPT_POST => TRUE,
		    CURLOPT_RETURNTRANSFER => TRUE,
		     CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded', $auth)
		));
		// Send the request
		$response = curl_exec($ch);
		
		// Check for errors
		if($response === FALSE){
		    die(curl_error($ch));
		}
		// Decode the response
		$responseData = json_decode($response);
		$risposta = array('response' => 'true', 'message' => $responseData->message);
		return $risposta;
}

function return_protected_resource($token){
	$risposta = confirm_token($token,'http://localhost/SOASEC_PRJ/Authorization_server/resource.php' );
	$response = array('response' => 'false');

	if ($risposta['response'] == 'true'){
	$response = array('response' => 'true', 'message' => 'Beccati sta risorsa!!');
	} 
	return $response;
}

function default_return()
{
	$risposta = array('response' => 'error');
	return $risposta;
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

//Create a stdClass instance to hold important information
$return = new stdClass();
$return->success = true;
$return->errorMessage = "";
$return->data = array();
$method = $_POST;
$client_data = file_get_contents("php://input");
$php_object = json_decode($client_data, true);
$allHeaders = getallheaders();

foreach ($php_object as $key => $value) {
	write_log($key.": ".$value);
}
switch ($php_object['r'])
{
	case "Testing":
		$return = testing();
		return_something($return);
		break;
	case "requestToResource_server":
		$return = return_protected_resource($php_object['t']);
		return_something($return);
		break;
	default:
		$return = default_return();
		return_something($return);
		break;
}
?>
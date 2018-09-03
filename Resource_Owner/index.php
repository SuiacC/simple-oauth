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
}

//testing mode for debug
function testing()
{
	$risposta = array('response' => 'true');
	return $risposta;
}

function authorization_grant()
{
	$risposta = array('response' => 'true','testclient' => 'testclient', 'testpass' => 'testpass', 'url' => 'http://localhost/SOASEC_PRJ/Authorization_server/token.php', 'grant_type' => 'client_credentials');
	return $risposta;
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
//Logging function for debugging
foreach ($php_object as $key => $value) {
	write_log($key.": ".$value);
}

switch ($php_object['r'])
{
	//testing case for debug
	case "Testing":
		$return = testing();
		return_something($return);
		break;
	case "requestToResourceOwner":
		$return = authorization_grant();
		return_something($return);
		break;
	default:
		$return = default_return();
		return_something($return);
		break;
}
?>
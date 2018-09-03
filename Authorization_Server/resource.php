<?php
// include our OAuth2 Server object
require_once __DIR__.'/server.php';

// Handle a request to a resource and authenticate the access token
if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    $server->getResponse()->send();
    writeLog($server);
    die;
}

//Logging di operazioni
function writeLog($data)
{
	$fp = fopen("log.txt", "a");
	if(!$fp) die ("Errore operazione con il file");
	fwrite($fp, date('d/m/Y H:i:s').' - '.print_r($data, true)."\r\n");
	fclose($fp);
}

echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));

?>
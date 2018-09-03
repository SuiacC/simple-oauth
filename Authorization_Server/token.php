<?php
// include our OAuth2 Server object
require_once __DIR__.'/server.php';

// Handle a request for an OAuth2.0 Access Token and send the response to the client
$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();

# {"access_token":"21a330c0ed324a86b588676ffb7dbf2d78238fa2","expires_in":3600,"token_type":"Bearer","scope":null}

?>


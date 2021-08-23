<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) == 'cgi')
    header("Status: 404 Not Found");
else
    header("HTTP/1.0 404 Not Found");

echo json_encode([
	"ERROR" => "ERROR 404",
	"Message" => "Page Not Found"
]);
die;

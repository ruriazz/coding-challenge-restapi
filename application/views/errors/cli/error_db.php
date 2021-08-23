<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) == 'cgi')
    header("Status: 500 Internal Server Error");
else
    header("HTTP/1.1 500 Internal Server Error");

echo json_encode([
	"DATABASE ERROR" => $heading,
	"Message" => $message
]);
die;

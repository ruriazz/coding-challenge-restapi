<?php defined('BASEPATH') OR exit('No direct script access allowed');

$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) == 'cgi')
    header("Status: 500 Internal Server Error");
else
    header("HTTP/1.1 500 Internal Server Error");

$backtrace = array();
if(defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE) {
	foreach (debug_backtrace() as $error) {
		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0) {
			array_push($backtrace, array(
				'File' => $error['file'],
				'Line' => $error['line'],
				'Function' => $error['function']
			));
		}
	}
}

$data = array(
	'Severity' => $severity,
	'Message' => $message,
	'Filename' => $filepath,
	'Line' => $line,
	'Backtrace' => $backtrace
);

echo json_encode($data);
die;
?>

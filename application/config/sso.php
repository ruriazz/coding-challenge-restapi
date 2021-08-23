<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['location'] = array();
$hundred = '/home/developer/100apps';
if(file_exists("$hundred/sso_config.php")) require_once("$hundred/sso_config.php");

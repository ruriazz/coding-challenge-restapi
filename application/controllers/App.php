<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . "libraries/rest/rest_controller.php";
use chriskacerguis\RestServer\RestController;

class App extends RestController {

	function __construct() {
		parent::__construct();
	}

    public function index_get() {
        $this->response_ok();
    }
}
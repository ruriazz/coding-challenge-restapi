<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . "libraries/rest/rest_controller.php";
use chriskacerguis\RestServer\RestController;

class User extends RestController {

	function __construct() {
		parent::__construct();

        $this->load->model('User_model', 'user_model');
	}

    public function email_get() {
        $requestData = $this->special_get('val');
        $requestData = $requestData->data;

        if($requestData->val == null)
            $this->bad_response(['message' => 'invalid data']);

        $userModel = array('email' => $requestData->val);
        $userModel = new User_Model($userModel);
        $userModel->get_data('email');

        if($userModel->id !== null)
            $this->bad_response(['message' => 'email used']);

        $this->response_ok();
    }

    public function index_get() {
        $this->is_auth();
        $requestData = $this->special_get('role');
        $requestData = $requestData->data;

        if($requestData->role == null)
            $this->response_ok(['content' => $this->auth_data, 'type' => 'authenticated']);

        $whereClause = array('role' => $requestData->role);
        $users = $this->user_model->get_list_data($whereClause);
        $this->response_ok(['content' => $users, 'type' => 'list_user']);
    }

    public function index_post() {
        $this->is_admin();
        $requestData = $this->special_post('users');
        $requestData = $requestData->data;

        if($requestData->users == null)
            $this->bad_response(['message' => 'invalid data']);

        $resultUser = array();
        foreach ($requestData->users as $user) {
            $userModel = new User_Model($user);
            $userModel->get_data('email');
            
            if($userModel->time_stored > 0)
                return;

            $userModel->save_data();
            array_push($resultUser, $userModel);
        }

        // echo json_encode($resultUser);
        $this->response_ok(['content' => $resultUser, 'type' => 'new_users']);
    }

    public function auth_post() {
        $requestData = $this->special_post('email, password');
        $requestData = $requestData->data;

        if($requestData->email == null || $requestData->email == null)
        $this->bad_response(['message' => 'invalid email and password', 'type' => 'empty']);

        if(!Valid::email($requestData->email))
            $this->bad_response(['message' => 'invalid data', 'type' => 'email']);

        $userModel = new User_model(['email' => $requestData->email]);
        $userModel->get_data('email');

        if($userModel->id == null || $userModel->password == null)
            $this->bad_response(['message' => 'invalid email or password', 'type' => 'email_password']);

        if(!password_verify($requestData->password, $userModel->password))
            $this->bad_response(['message' => 'invalid email or password', 'type' => 'email_password']);

        $userModel->auth_token = JsonWebToken::encode($userModel->id);
        unset($userModel->password);

        $this->response_ok(['content' => $userModel, 'type' => 'authenticated']);
    }
}
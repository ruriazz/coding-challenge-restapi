<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . "libraries/rest/rest_controller.php";
use chriskacerguis\RestServer\RestController;

class Payment extends RestController {

	function __construct() {
		parent::__construct();

        $this->load->model('User_model');
        $this->load->model('Payment_model');
        $this->load->model('Member_model');
	}

    public function index_get() {
        $this->is_auth();

        $requestData = $this->special_get('id, member');
        $requestData = $requestData->data;

        if($requestData->id == null)
            $this->bad_response(['message' => 'data not found']);

        $paymentModel = new Payment_model($requestData);
        $paymentModel->get_data('id');

        if($paymentModel->total_payment == null)
            $this->bad_response(['message' => 'data not found']);

        if($this->auth_data->role == UserRole::User) {
            $exists = false;
            foreach ($paymentModel->member as $member) {
                if($member->user->id == $this->auth_data->id) {
                    $exists = true;
                    break;
                }
            }

            if(!$exists) 
                $this->bad_response(['message' => 'restricted']);

        }

        $this->response_ok(['content' => $paymentModel]);
    }

    public function index_post() {
        $this->is_admin();

        $requestData = $this->special_post('payment');
        $requestData = $requestData->data;

        if($requestData->payment == null)
            $this->bad_response(['message' => 'invalid data']);

        $requestData->payment = (object) $requestData->payment;
        $requestData = (object) $requestData->payment;
        
        if($requestData->submiter['id'] !== $this->auth_data->id)
            $this->bad_response(['message' => 'request error']);

        $requestData->submiter = $requestData->submiter['id'];

        $members = $requestData->member;
        unset($requestData->member);

        $paymentModel = new Payment_Model($requestData);
        $paymentModel->save_data();

        $memberModels = array();

        foreach ($members as $member) {
            $member = (object) $member;
            $member->user = $member->user['id'];
            $member->payment = $paymentModel->id;

            $memberModel = new Member_model($member);
            $memberModel->save_data();
            array_push($memberModels, $memberModel);
        }

        $paymentModel->member = $memberModels;
        $paymentModel->get_submiter();
        $paymentModel->get_member();

        $this->response_ok(['content' => $paymentModel]);
    }
}
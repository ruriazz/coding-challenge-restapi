<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model {

    public ?String $id = null;
    public ?float $total_payment = null;
    public $submiter = null;
    public ?array $member = array();
    public ?int $time_stored;

    const table = 'payment_history';

    function __construct($data = null) {
        if($data !== null)
            $this->set_data($data);

        $this->load->model('User_model');
        $this->load->model('Member_model', 'member_model');
        $this->load->database();
    }

    public function get_data($index) {
        $whereClause = array();
        $index = explode(',', $index);
        foreach ($index as $idx) {
            $whereClause = array_merge($whereClause, ["$idx" => $this->$idx]);
        }

        $result = $this->db->select('*')
                        ->from(Payment_model::table)
                        ->where($whereClause)
                        ->get()->row_array();

        if($result !== null) {
            $this->set_data($result);
            $this->get_submiter();
            $this->get_member();
        }
    }

    public function get_submiter() {
        $userModel = new User_model([
            'id' => $this->submiter
        ]);
        $userModel->get_data('id');
        unset($userModel->password, $userModel->auth_token);

        $this->submiter = $userModel;
    }

    public function get_member() {
        $this->member = $this->member_model->get_as_list(['payment' => $this->id]);
    }

    public function save_data() {
        if(array_key_exists('member', (array) $this))
            unset($this->member);

        if($this->time_stored == 0)
            $this->time_stored = time();

        if($this->id == null || $this->id == '')
            $this->id = Generate::unique_id(IDType::Payment);

        $this->db->insert(Payment_model::table, $this);
    }

    public function update_data($newData) {

    }

    public function set_data($data) {
        $data = (object) $data;

        if(property_exists($data, 'id'))
            $this->id = (String) $data->id;

        if(property_exists($data, 'total_payment'))
            $this->total_payment = (float) $data->total_payment;

        if(property_exists($data, 'submiter'))
            $this->submiter = $data->submiter;

        if(property_exists($data, 'member'))
            $this->member = (array) $data->member;

        if(property_exists($data, 'time_stored'))
            $this->time_stored = (int) $data->time_stored;
    }

}
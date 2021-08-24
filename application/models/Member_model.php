<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model {

    public ?String $payment = null;
    public $user = null;
    public ?float $value = null;

    const table = 'payment_member';

    function __construct($data = null) {
        if($data !== null)
            $this->set_data($data);

        $this->load->model('User_model');
        $this->load->database();
    }

    public function get_data($index) {
        $whereClause = array();
        $index = explode(',', $index);
        foreach ($index as $idx) {
            $whereClause = array_merge($whereClause, ["$idx" => $this->$idx]);
        }


    }

    public function get_user() {
        $userModel = new User_model(['id' => $this->user]);
        $userModel->get_data('id');
        unset($userModel->password, $userModel->auth_token);

        $this->user = $userModel;
    }

    public function get_all_data($whereClause) {
        $data = array();

        $results = $this->db->select('*')
                            ->from(Member_model::table)
                            ->where($whereClause)
                            ->get()->result_array();

        foreach ($results as $result) {
            $memberModel = new Member_model($result);
            array_push($data, $memberModel);
        }

        return $data;
    }

    public function get_as_list($whereClause) {
        $results = $this->db->select('*')
                            ->from(Member_model::table)
                            ->where($whereClause)
                            ->get()->result_array();

        $members = array();
        foreach ($results as $result) {
            $member = new Member_model($result);
            $member->get_user();

            array_push($members, $member);
        }

        return $members;
    }

    public function save_data() {
        $this->db->insert(Member_model::table, $this);
    }

    public function update_data($newData) {

    }

    public function set_data($data) {
        $data = (object) $data;

        if(property_exists($data, 'payment'))
            $this->payment = (String) $data->payment;

        if(property_exists($data, 'user'))
            $this->user = $data->user;

        if(property_exists($data, 'value'))
            $this->value = (float) $data->value;

    }

}
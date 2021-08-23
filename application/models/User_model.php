<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public ?String $id = null;
    public ?String $email = null;
    public ?String $name = null;
    public ?String $password = null;
    public ?String $auth_token;
    public ?String $role = null;
    public ?String $photo = null;
    public ?int $time_stored = 0;

    private const table = "user_data";

    function __construct($data = null) {
        if($data !== null)
            $this->set_data($data);

        $this->load->database();
    }

    public function save_data() {
        if($this->id == null || $this->id == '')
            $this->id = Generate::unique_id(IDType::User);

        if($this->password == null || $this->password == '')
            $this->password = password_hash('default123', PASSWORD_DEFAULT);

        if($this->role == null || $this->role == '')
            $this->role = UserRole::User;

        if($this->time_stored == 0 || $this->time_stored == null)
            $this->time_stored = time();

        $this->db->insert(User_model::table, $this);
    }

    public function get_data($index) {
        $whereClause = array();
        $index = explode(',', $index);
        foreach ($index as $idx) {
            $whereClause = array_merge($whereClause, ["$idx" => $this->$idx]);
        }

        $result = $this->db->select('*')
                        ->from(User_model::table)
                        ->where($whereClause)
                        ->get()->row_array();

        if($result !== null)
            $this->set_data($result);
    }

    public function get_list_data(array $whereClause) {
        $resultData = array();
        $results = $this->db->select('*')
                        ->from(User_model::table)
                        ->where($whereClause)
                        ->order_by('name', 'ASC')
                        ->get()->result_array();

        foreach ($results as $result) {
            $userModel = new User_model($result);
            unset($userModel->password, $userModel->auth_token);

            array_push($resultData, $userModel);
        }

        return $resultData;
    }

    public function set_data($data) {
        $data = (object) $data;

        if(property_exists($data, 'id'))
            $this->id = (String) $data->id;

        if(property_exists($data, 'email'))
            $this->email = (String) $data->email;

        if(property_exists($data, 'name'))
            $this->name = (String) $data->name;

        if(property_exists($data, 'password'))
            $this->password = (String) $data->password;

        if(property_exists($data, 'role'))
            $this->role = (String) $data->role;

        if(property_exists($data, 'photo'))
            $this->photo = (String) $data->photo;

        if(property_exists($data, 'time_stored'))
            $this->time_stored = (int) $data->time_stored;
    }

}

class UserRole {
    const Admin = 'admin';
    const User = 'user';
}
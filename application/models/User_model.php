<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function query_exe($query) {
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data; //format the array into json data
        }
    }

    //check user if exist in system
    function check_user($emailid) {
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data; //format the array into json data
        }
    }

    //end of check user5
    //get user detail by id
    function user_details($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('admin_users');
        if ($query->num_rows() > 0) {
            return $query->result()[0];
        } else {
            return array();
        }
    }

    // end of user detail by id
    //get user detail by id
    function user_reports($user_type) {

        switch ($user_type) {
            case 'Blocked':
                $this->db->where(array('status=' => 'Blocked'));
                break;
            case 'All':
                $this->db->where(array('status!=' => 'Blocked'));
                break;
            default:
                $this->db->where(array('user_type' => $user_type, 'status!=' => 'Blocked'));
                break;
        }
        $query = $this->db->get('admin_users');
        return $query->result();
    }

    // end of user detail by id
}

?>
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class UserManager extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
        $this->user_type = $this->session->logged_in['user_type'];
    }

    public function index() {
        $this->db->order_by("id", "desc");
        $this->db->from('admin_users');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['users'] = $query->result();
        } else {
            $data['users'] = [];
        }
        $this->load->view('userManager/usersReport', $data);
    }

    public function not_granted() {
        
    }

    public function usersReport() {
        $data['users_vendor'] = $this->User_model->user_reports("Vendor");
        $data['users_customer'] = $this->User_model->user_reports("Customer");
        $data['users_all'] = $this->User_model->user_reports("All");
        $data['users_blocked'] = $this->User_model->user_reports("Blocked");

        $this->load->view('userManager/usersReport', $data);
    }

    public function addVendor() {
        $config['upload_path'] = 'assets_main/userimages';
        $config['allowed_types'] = '*';
        if (isset($_POST['submit'])) {
            $picture = '';
            if (!empty($_FILES['picture']['name'])) {
                $temp1 = rand(100, 1000000);
                $ext1 = explode('.', $_FILES['picture']['name']);
                $ext = strtolower(end($ext1));
                $file_newname = $temp1 . "1." . $ext;
                ;
                $config['file_name'] = $file_newname;
                //Load upload library and initialize configuration
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('picture')) {
                    $uploadData = $this->upload->data();
                    $picture = $uploadData['file_name'];
                } else {
                    $picture = '';
                }
            }
            $op_date_time = date('Y-m-d H:i:s');
            $user_type = 'Vendor';
            $password = $this->input->post('password');
            $pwd = md5($password);
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $email = $this->input->post('email');
            $contact_no = $this->input->post('contact_no');
            $address = $this->input->post('address');
            $post_data = array('first_name' => $first_name,
                'last_name' => $last_name, 'email ' => $email,
                'user_type' => $user_type, 'password2' => $password, 'image' => $picture,
                'password' => $pwd, 'address' => $address, 'contact_no' => $contact_no,
                'op_date_time' => $op_date_time);
            $this->db->insert('admin_users', $post_data);
            redirect('UserManager/addVendor');
        }
        $this->load->view('userManager/addVendor');
    }

    public function profile_update_info($user_id = 0) {
        $user_model = $this->User_model;
        $config['upload_path'] = 'assets_main/userimages';
        $config['allowed_types'] = '*';

        if ($user_id) {
            $uid = $user_id;
        } else {
            $uid = $this->user_id;
        }

        $userdetails = $user_model->user_details($uid);

        if (!$userdetails) {
            redirect('ProductManager/productReport');
        }

        $data['user_details'] = $userdetails;
        if (isset($_POST['submit'])) {
            $picture = '';
            if (!empty($_FILES['picture']['name'])) {
                $temp1 = rand(100, 1000000);
                $config['overwrite'] = TRUE;
                $ext1 = explode('.', $_FILES['picture']['name']);
                $ext = strtolower(end($ext1));
                if ($userdetails->image) {
                    $ext22 = explode('.', $userdetails->image);
                    $ext33 = strtolower(end($ext22));
                    $filename = $ext22[0];
                    $file_newname = $filename . "." . $ext;
                } else {
                    $file_newname = $temp1 . "1." . $ext;
                }
                $picture = $file_newname;
                $config['file_name'] = $file_newname;
                //Load upload library and initialize configuration
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('picture')) {
                    $uploadData = $this->upload->data();
                    $picture = $uploadData['file_name'];
                } else {
                    $picture = '';
                }
            }
            $op_date_time = date('Y-m-d H:i:s');
            $user_type = 'Vendor';
            $password = $this->input->post('password');
            $pwd = md5($password);
            $this->db->set('first_name', $this->input->post('first_name'));
            $this->db->set('image', $picture);
            $this->db->set('last_name', $this->input->post('last_name'));
            $this->db->set('contact_no', $this->input->post('contact_no'));
            $this->db->set('address', $this->input->post('address'));
            $this->db->set('state', $this->input->post('state'));
            $this->db->set('city', $this->input->post('city'));
            $this->db->set('pincode', $this->input->post('pincode'));
            $this->db->where('id', $uid); //set column_name and value in which row need to update
            $this->db->update('admin_users');
            redirect('UserManager/usersReport');
        }

        //Delete User
        if (isset($_POST['delete_user'])) {
            $user_delte_id = $this->input->post('delete_user');
            $this->db->where('id', $user_delte_id);
            $this->db->delete('admin_users');
            redirect('UserManager/usersReport');
        }


        //Block User
        if (isset($_POST['block_user'])) {
            $user_delte_id = $this->input->post('block_user');
            $this->db->set('status', 'Blocked');
            $this->db->where('id', $user_delte_id);
            $this->db->update('admin_users');
            redirect('UserManager/usersReport');
        }

        //Unblock User
        if (isset($_POST['unblock_user'])) {
            $user_delte_id = $this->input->post('unblock_user');
            $this->db->set('status', '');
            $this->db->where('id', $user_delte_id);
            $this->db->update('admin_users');
            redirect('UserManager/usersReport');
        }


        $this->load->view('userManager/profile_update_info', $data);
    }

    public function users_api() {
        $query = "select u.*, 
            (select sum(credit) from user_credit as uc where uc.user_id = u.id) as credits
            (select sum(credit) from user_debit as uc where uc.user_id = u.id) as debits,
            from admin_users as u";

        $userslist = $this->User_model->query_exe($query);
        
        $usersdata = array();
        foreach ($userslist as $key => $value) {
            $usersdata[$value['id']] = $value;
        }
        
        echo json_encode(array('list' => $usersdata));
    }

    public function usersCreditDebit() {

        $op_date = date('Y-m-d');
        $op_time = date('H:i:s');
        if (isset($_POST['allot_credit'])) {
            $credit_data = array(
                'c_date' => $op_date,
                'c_time' => $op_time,
                'credit' => $this->input->post('credit'),
                'user_id' => $this->input->post('user_id'),
                'remark' => $this->input->post('remark'),
            );
            $this->db->insert('user_credit', $credit_data);
            redirect('UserManager/usersCreditDebit#' . $this->input->post('user_id'));
        }
        $this->load->view('userManager/usersCreditDebit');
    }

}

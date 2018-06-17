<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->library('session');
        $session_user = $this->session->userdata('logged_in');
        if ($session_user) {
            $this->user_id = $session_user['login_id'];
        } else {
            $this->user_id = 0;
        }
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
        $this->user_type = $this->session->logged_in['user_type'];
    }

    public function index() {
        redirect('/');
    }

    //order details
    public function orderdetails($order_key) {
        if ($this->user_type != 'Admin') {
            redirect('UserManager/not_granted');
        }

        $order_details = $this->Order_model->getOrderDetails($order_key, 'key');
        
        if ($order_details) {
            $order_id = $order_details['order_data']->id;
            $data['ordersdetails'] = $order_details;
            $data['order_key'] = $order_key;

            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $order_id);
            $query = $this->db->get('user_order_status');
            $orderstatuslist = $query->result();
            $data['user_order_status'] = $orderstatuslist;

            if (isset($_POST['submit'])) {
                $productattr = array(
                    'c_date' => date('Y-m-d'),
                    'c_time' => date('H:i:s'),
                    'status' => $this->input->post('status'),
                    'remark' => $this->input->post('remark'),
                    'description' => $this->input->post('description'),
                    'order_id' => $order_id
                );
                $this->db->insert('user_order_status', $productattr);
                redirect("Order/orderdetails/$order_key");
            }


            try {
                $order_id = $order_id;
                // $this->Product_model->order_mail($order_id);
                //redirect("Order/orderdetails/$order_key");
            } catch (customException $e) {
                //display custom message
                // redirect("Order/orderdetails/$order_key");
            }
        } else {
            redirect('/');
        }

        $this->load->view('Order/orderdetails', $data);
    }
    
    public function remove_order_status($status_id, $orderkey) {
        $this->db->delete('user_order_status', array('id' => $status_id));
        redirect("Order/orderdetails/$orderkey");
    }
    

    //order list accroding to user type
    public function orderslist() {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('user_order');
        $orderlist = $query->result();
        $data['orderslist'] = $orderlist;

        $this->load->view('Order/orderslist', $data);
    }

}

?>

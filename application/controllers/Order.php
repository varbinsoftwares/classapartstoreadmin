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
        $vendor_order_details = $this->Order_model->getVendorsOrder($order_key);
        $data['vendor_order'] = $vendor_order_details;
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
        if ($this->user_type == 'Admin') {
            $this->db->order_by('id', 'desc');
            $query = $this->db->get('user_order');
            $orderlist = $query->result();
            $orderslistr = [];
            foreach ($orderlist as $key => $value) {
                $this->db->order_by('id', 'desc');
                $this->db->where('order_id', $value->id);
                $query = $this->db->get('user_order_status');
                $status = $query->row();
                $value->status = $status ? $status->status : $value->status;
                array_push($orderslistr, $value);
            }
            $data['orderslist'] = $orderslistr;
            $this->load->view('Order/orderslist', $data);
        }
        if ($this->user_type == 'Vendor') {
            $this->db->order_by('vo.id', 'desc');
            $this->db->group_by('vo.id');
            $this->db->select('o.order_no, vo.id, o.name, o.email, o.address, o.city,'
                    . 'o.state, vo.vendor_order_no, vo.total_price, vo.total_quantity, vo.c_date, vo.c_time');
            $this->db->join('user_order as o', 'o.id = vo.order_id', 'left');
            $this->db->where('vo.vendor_id', $this->user_id);
            $this->db->from('vendor_order as vo');
            $query = $this->db->get();
            $orderlist = $query->result();
            $orderslistr = [];
            foreach ($orderlist as $key => $value) {

                $this->db->order_by('id', 'desc');
                $this->db->where('vendor_order_id', $value->id);
                $query = $this->db->get('vendor_order_status');
                $status = $query->row();
                $value->status = $status ? $status->status : $value->status;
                array_push($orderslistr, $value);
            }
            $data['orderslist'] = $orderslistr;
            $this->load->view('Order/vendororderslist', $data);
        }
    }

    //vendor order details
    public function vendor_order_details($order_id) {
        $this->db->where('id', $order_id);
        $query = $this->db->get('vendor_order');
        $vendor_order_details = $query->row();
        $data['vendor_order'] = $vendor_order_details;

        $this->db->where('id', $vendor_order_details->order_id);
        $query = $this->db->get('user_order');
        $order_details = $query->row();
        $data['order_details'] = $order_details;

        if ($this->user_id != $vendor_order_details->vendor_id) {
            redirect('UserManager/not_granted');
        }


        $this->db->where('order_id', $order_details->id);
        $this->db->where('vendor_id', $this->user_id);
        $query = $this->db->get('cart');
        $data['cart_items'] = $query->result();

        $data['cstatus'] = '';

        if ($order_details) {
            $order_id = $vendor_order_details->id;
            $data['order_id'] = $order_id;
            $this->db->order_by('id', 'desc');
            $this->db->where('vendor_order_id', $order_id);
            $query = $this->db->get('vendor_order_status');
            $orderstatuslist = $query->result();

            $data['cstatus'] = count($orderstatuslist) > 0 ? $orderstatuslist[0]->status : '';


            $data['vendor_order_status'] = $orderstatuslist;
            if (isset($_POST['submit'])) {
                $productattr = array(
                    'c_date' => date('Y-m-d'),
                    'c_time' => date('H:i:s'),
                    'status' => $this->input->post('status'),
                    'remark' => $this->input->post('remark'),
                    'description' => $this->input->post('description'),
                    'order_id' => $vendor_order_details->order_id,
                    'vendor_order_id' => $vendor_order_details->id,
                    'vendor_id' => $vendor_order_details->vendor_id,
                );
                $this->db->insert('vendor_order_status', $productattr);
                redirect("Order/vendor_order_details/$order_id");
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
        $this->load->view('Order/vendororderdetails', $data);
    }

    //vendor order status
    public function remove_vendor_order_status($status_id, $order_id) {
        $this->db->delete('vendor_order_status', array('id' => $status_id));
        redirect("Order/vendor_order_details/$order_id");
    }

    //order analisys
    public function orderAnalysis() {
        if ($this->user_type != 'Admin') {
            redirect('UserManager/not_granted');
        }

        $this->db->order_by('id', 'desc');
        $query = $this->db->get('user_order');
        $orderlist = $query->result_array();
        $orderslistr = [];

        foreach ($orderlist as $key => $value) {
            $this->db->order_by('id', 'desc');
            $this->db->where('order_id', $value['id']);
            $query = $this->db->get('user_order_status');
            $status = $query->row();
            $value['status'] = $status ? $status->status : $value['status'];
            array_push($orderslistr, $value);
        }

        $this->db->order_by('id', 'desc');
        $query = $this->db->get('admin_users');
        $orderlist = $query->result_array();


        $data['total_order'] = count($orderslistr);

        $data['orderslist'] = $orderslistr;

        $this->load->library('JsonSorting', $orderslistr);

        $orderstatus = $this->jsonsorting->collect_data('status');

        $orderuser = $this->jsonsorting->collect_data('name');

        $orderdate = $this->jsonsorting->collect_data('order_date');

        $data['orderstatus'] = $orderstatus;
        $data['orderuser'] = $orderuser;
        $data['orderdate'] = $orderdate;

        $this->load->view('Order/orderanalysis', $data);
    }

}

?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portal extends CI_Controller {


    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('portal_model');
        $this->load->model('app_model');
        $this->load->library('upload');
        date_default_timezone_set('Asia/Manila');


        //Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    function get_dateTime()
    {
        $timestamp = time();
        $date_time = date('F j, Y g:i:s A  ', $timestamp);
        $result['current_dateTime'] = $date_time;
        echo json_encode($result);
    }

    public function index()
    {
        if ($this->session->userdata('portal_logged_in') && $this->session->userdata('user_type') == 'tenant')
        {
           redirect('portal/home');
        } else {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/portal/login_portal', $data);
        }

        // redirect('portal/tenant_soa');
    }

    public function check_login()
    {
        $username = $this->sanitize($this->input->post('username'));
        $password = $this->sanitize($this->input->post('password'));
        $result   = $this->portal_model->check_login($username, $password);

        if ($result) {
            if ($this->session->userdata('user_type') == 'tenant')
            {
                redirect('portal/home');
            }
            else
            {
                $this->session->set_flashdata('message', 'Invalid Login');
                redirect('portal/');
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Invalid Login');
            redirect('portal/');
        }
    }

    public function home()
    {
        // if ($this->session->userdata('leasing_logged_in'))
        // {
        //     $data['flashdata'] = $this->session->flashdata('message');
        //     // $data['expiry_tenants'] = $this->portal_model->get_expiryTenants();
        //     $this->load->view('leasing/portal/portal_header', $data);
        //     $this->load->view('leasing/portal/portal_home');
        //     $this->load->view('leasing/portal/portal_footer');
        // } else {
        //     redirect('portal/');
        // }

        redirect('portal/tenant_soa');
    }

    public function logout()
    {
        $newdata = array(
            'id'                    => '',
            'tenant_id'             => '',
            'password'              => '',
            'user_type'             => '',
            'leasing_logged_in'     => FALSE
        );

        $session = (object)$this->session->userdata;

        if(isset($session->session_id)){
            $user_session_data = ['date_ended'=> date('Y-m-d H:i:s')];

            $this->db->where('session_id', $session->session_id);
            $this->db->update('user_session', $user_session_data);
        }

        

        $this->session->unset_userdata($newdata);
        $this->session->sess_destroy();
        redirect('portal/');
    }

    public function tenant_soa(){
        // var_dump($this->session->userdata('trade_name'));
        if ($this->session->userdata('portal_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/portal/portal_header', $data);
            $this->load->view('leasing/reprint_soa');
            $this->load->view('leasing/portal/portal_footer');
        } else {
            redirect('portal/');
        }
    }
}
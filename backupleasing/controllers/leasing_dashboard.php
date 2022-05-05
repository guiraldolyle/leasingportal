<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leasing_dashboard extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('app_model');
        $this->load->library('upload');
        date_default_timezone_set("Asia/Manila");

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


    public function curdate()
    {
        return date('Y-m-d');
    }

    public function now()
    {
        return date('Y-m-d H:i:s');
    }

    public function index()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator')
            {

                $data['flashdata'] = $this->session->flashdata('message');
                $data['storeDetails'][0]['store_name'] = '';
                $data['storeDetails'][0]['store_address'] = '';

            } else {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['storeDetails'] = $this->app_model->get_storeDetails();
            }

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/dashboard');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing');
        }
    }

    public function tenants_perYear()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->tenants_perYear();
            echo json_encode($result);
        }
    }


    public function tenants_perAreaClassification()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->tenants_perAreaClassification();
            echo json_encode($result);
        }
    }


    public function tenants_perAreaType()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->tenants_perAreaType();
            echo json_encode($result);
        }
    }

    public function tenants_perLesseeType()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->tenants_perLesseeType();
            echo json_encode($result);
        }
    }


}/* End of file Leasing_dashboard.php */
/* Location: ./application/controllers/Leasing_dashboard.php */

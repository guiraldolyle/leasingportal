<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ctrl_leasing extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('app_model');
        $this->load->library('upload');
        date_default_timezone_set('Asia/Manila');


        //Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        ('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
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
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') != 'General Manager') {
            redirect('ctrl_leasing/home');
        } else {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/login_leasing', $data);
        }
    }



    public function admin()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            redirect('ctrl_leasing/home');
        } else {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/leasing_adminLogin', $data);
        }
    }

    public function check_login()
    {
        $username = $this->sanitize($this->input->post('username'));
        $password = $this->sanitize($this->input->post('password'));
        $result = $this->app_model->check_login($username, $password);
        if ($result) {
            if ($this->session->userdata('user_type') == 'General Manager') {
                redirect('ctrl_leasing/GM_home');
            } elseif ($this->session->userdata('user_type') == 'IAD') {
                // redirect('ctrl_leasing/IAD_home');
                redirect('ctrl_leasing/home');
            } else {
                redirect('ctrl_leasing/home');
            }
        } else {
            $this->session->set_flashdata('message', 'Invalid Login');
            redirect('ctrl_leasing/');
        }
    }



    public function admin_login()
    {
        $username = $this->sanitize($this->input->post('username'));
        $password = $this->sanitize($this->input->post('password'));
        $result = $this->app_model->check_adminLogin($username, $password);
        if ($result) {
            redirect('ctrl_leasing/home');
        } else {
            $this->session->set_flashdata('message', 'Invalid Login');
            redirect('ctrl_leasing/admin');
        }
    }




    public function logout()
    {
        $newdata = array(
            'id'                    => '',
            'username'              => '',
            'password'              => '',
            'user_type'             => '',
            'username'              => '',
            'first_name'            => '',
            'middle_name'           => '',
            'last_name'             => '',
            'company_code'          => '',
            'user_group'            => '',
            'leasing_logged_in'     => FALSE
        );

        $session = (object)$this->session->userdata;

        if (isset($session->session_id)) {
            $user_session_data = ['date_ended' => date('Y-m-d H:i:s')];

            $this->db->where('session_id', $session->session_id);
            $this->db->update('user_session', $user_session_data);
        }



        $this->session->unset_userdata($newdata);
        $this->session->sess_destroy();
        redirect('ctrl_leasing/');
    }

    public function home()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/home');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function user_credentials()
    {
        if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('cfs_logged_in')) {
            $result = $this->app_model->user_credentials();
            echo json_encode($result);
        } else if ($this->session->userdata('portal_logged_in')) {
            $result = $this->app_model->tenant_user_credentials();
            echo json_encode($result);
        }
    }

    public function update_usettings()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $username = $this->sanitize($this->input->post('username'));
            $new_pass = $this->sanitize($this->input->post('new_pass'));
            $id = $this->uri->segment(3);

            $data = array(
                'username' => $username,
                'password' => md5($new_pass)
            );

            $this->app_model->update($data, $id, 'leasing_users');
            redirect('ctrl_leasing/logout');
        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function update_usettings_tenant()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $username = $this->sanitize($this->input->post('username'));
            $new_pass = $this->sanitize($this->input->post('new_pass'));
            $id = $this->uri->segment(3);

            $data = array(
                'tenant_id' => $username,
                'password' => md5($new_pass)
            );

            $this->app_model->update($data, $id, 'tenant_users');
            redirect('portal/logout');
        } else {
            redirect('portal/');
        }
    }


    public function IAD_home()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'IAD') {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('iad/header', $data);
            $this->load->view('iad/home');
            $this->load->view('iad/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function get_activeTenants()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'IAD') {
            $result = $this->app_model->activeTenants();
            echo json_encode($result);
        }
    }
    public function general_ledger()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'IAD') {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('iad/header', $data);
            $this->load->view('iad/general_ledger');
            $this->load->view('iad/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function rr_ar_summary()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'IAD') {
            $data['flashdata'] = $this->session->flashdata('message');
            // $this->load->view('iad/header', $data);
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('iad/rr_ar_summary');
            $this->load->view('iad/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function tenant_ledger()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'IAD') {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('iad/header', $data);
            $this->load->view('iad/tenant_ledger');
            $this->load->view('iad/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function soa()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'IAD') {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('iad/header', $data);
            $this->load->view('iad/soa');
            $this->load->view('iad/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function payment_history()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'IAD') {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('iad/header', $data);
            $this->load->view('iad/payment_history');
            $this->load->view('iad/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function GM_home()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'General Manager') {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['rental_increment'] = $this->app_model->getAll('rental_increment');
            $this->load->view('gm/header', $data);
            $this->load->view('gm/home');
            $this->load->view('gm/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }





    public function lt_forreview()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'General Manager') {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('gm/header', $data);
            $this->load->view('gm/lt_forreview');
            $this->load->view('gm/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function st_forreview()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'General Manager') {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('gm/header', $data);
            $this->load->view('gm/st_forreview');
            $this->load->view('gm/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function about()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('about/about');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }




    public function monthly_payable()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'IAD') {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            // $this->load->view('iad/header', $data);
            $this->load->view('leasing/header', $data);
            $this->load->view('iad/monthly_payable');
            $this->load->view('iad/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function monthly_receivable_summary()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'IAD') {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            // $this->load->view('iad/header', $data);
            $this->load->view('leasing/header', $data);
            $this->load->view('iad/receivable_summary');
            $this->load->view('iad/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function CFS_remittance()
    {
        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        // $this->load->view('iad/header', $data);
        $this->load->view('leasing/header', $data);
        $this->load->view('iad/CFS_remittance');
        $this->load->view('iad/footer');
    }


    public function payment_report()
    {


        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('iad/header', $data);
        $this->load->view('iad/payment_report');
        $this->load->view('iad/footer');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

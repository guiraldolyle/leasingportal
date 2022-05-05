<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Ctrl_recon extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('excel');
        $this->load->model('app_model');
        $this->load->library('upload');
        $this->load->library('fpdf');
        ini_set('memory_limit', '200M');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_maxs_size', '200M');
        ini_set('max_input_time', 3600);
        ini_set('max_execution_time', 3600);
        date_default_timezone_set('Asia/Manila');
        $timestamp = time();
        $this->_currentDate = date('Y-m-d', $timestamp);
        $this->_currentYear = date('Y', $timestamp);

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


   
    public function index()
    {
        if ($this->session->userdata('recon_logged_in'))
        {
           redirect('ctrl_recon/home');
        } else {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('recon/login_recon', $data);
        }
    }

    public function check_login()
    {
        $username = $this->sanitize($this->input->post('username'));
        $password = $this->sanitize($this->input->post('password'));
        $result = $this->app_model->recon_checkLogin($username, $password);
        if ($result) {
            redirect('ctrl_recon/home');
        }
        else
        {
            $this->session->set_flashdata('message', 'Invalid Login');
            redirect('ctrl_recon/');
        }
    }


    public function home()
    {
        if ($this->session->userdata('recon_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['stores'] = $this->app_model->get_stores();
            $this->load->view('recon/header', $data);
            $this->load->view('recon/home');
            $this->load->view('recon/footer');
        } else {
            redirect('ctrl_recon/');
        }
    }

    public function record_internalPayment() {
        if ($this->session->userdata('recon_logged_in'))
        {
            $data['trans_no']       = $this->app_model->generate_InternalTransactionNo();
            $data['flashdata'] = $this->session->flashdata('message');
            $data['stores'] = $this->app_model->get_stores();
            $this->load->view('recon/header', $data);
            $this->load->view('recon/record_internalPayment');
            $this->load->view('recon/footer');

        } else {
            redirect('ctrl_recon/');
        }
    }


    public function reverse_internalPayment() {
        if ($this->session->userdata('recon_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['stores']    = $this->app_model->get_stores();
            $data['banks']     = $this->app_model->getAll('accredited_banks');
            $data['trans_no']  = $this->app_model->generate_reverseInternalTransactionNo();
            $this->load->view('recon/header', $data);
            $this->load->view('recon/reverse_internalPayment');
            $this->load->view('recon/footer');

        } else {
            redirect('ctrl_recon/');
        }
    }

        public function collection_reports()
    {
        if ($this->session->userdata('recon_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('recon/header', $data);
            $this->load->view('recon/collection_reports');
            $this->load->view('recon/footer');
        }
        else

        {
            redirect('ctrl_recon/');
        }
    }

    public function generate_paymentCollection2()
    {
        if ($this->session->userdata('recon_logged_in'))
        {
            $month = $this->sanitize($this->input->post('month'));
            $company_code = "";
            $dept_code = "";
            $store_code = "";

            if ($this->session->userdata('user_group') != '' || $this->session->userdata('user_group') != '0' || $this->session->userdata('user_group') != NULL)
            {
                $company_code = $this->session->userdata('company_code');
                $dept_code    = $this->session->userdata('dept_code');
                $store_code   = $this->session->userdata('store_code') . " Leasing";
            }


            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $file_name = "Unlcosed PDC" . $timeStamp . ".txt";

            $handle = fopen($file_name, "w");
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file_name));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');

            $closedPDC             = $this->app_model->generate_closedPDC($month);

            
            // output each row of the data
            $line_no = 10000;

         

           

            foreach ($closedPDC as $result)
            {

                if ($result['amount'] != '0') {
                    $doc_no = 'PR' .  date('mdy', strtotime($result['posting_date']));
                    $bank = array(
                        'CASH RECEI',
                        'LS COLL',
                        $line_no,
                        'Bank Account',
                        $result['bank_code'],
                        date('m/d/Y', strtotime($result['posting_date'])),
                        'Payment',
                        $result['doc_no'],
                        $doc_no,
                        $result['bank_name'] . ' - ' . $store_code,
                        'PHP',
                        $result['amount'] * -1,
                        $result['amount'] * -1,
                        '',
                        $result['amount'] * -1,
                        $result['amount'] * -1,
                        '1',
                        $company_code,
                        $dept_code,
                        'PAYMENTJNL',
                        $result['amount'] * -1,
                        $result['amount'],
                        date('m/d/Y', strtotime($result['posting_date'])),
                        'Bank Account',
                        $result['bank_code'],
                        $result['amount'] * -1,
                        $result['amount']

                    );

                    file_put_contents($file_name, PHP_EOL . implode(",", $bank), FILE_APPEND | LOCK_EX);

                    $line_no += 10000;

                    $gl_account = array(
                        'CASH RECEI',
                        'LS COLL',
                        $line_no,
                        'G/L Account',
                        $result['gl_code'],
                        date('m/d/Y', strtotime($result['posting_date'])),
                        'Payment',
                        $result['doc_no'],
                        $doc_no,
                        $result['gl_account'],
                        'PHP',
                        $result['amount'],
                        '',
                        $result['amount'] * -1,
                        $result['amount'],
                        $result['amount'],
                        '1',
                        $company_code,
                        $dept_code,
                        'PAYMENTJNL',
                        $result['amount'],
                        $result['amount'] * -1,
                        date('m/d/Y', strtotime($result['posting_date'])),
                        '',
                        '',
                        $result['amount'],
                        $result['amount'] * -1
                    );

                    
                    file_put_contents($file_name, PHP_EOL . implode(",", $gl_account), FILE_APPEND | LOCK_EX);
                    $line_no += 10000;
                }
            }

            fclose($handle);
            readfile($file_name);
            unlink($file_name);
            exit();
        }
        else
        {
            redirect('ctrl_recon/');
        }
    }


    public function unclosedPDC() {
        if ($this->session->userdata('recon_logged_in'))
        {   

            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('recon/header', $data);
            $this->load->view('recon/unclosedPDC');
            $this->load->view('recon/footer');

        } else {
            redirect('ctrl_recon/');
        }
    }

   
    public function get_stores()
    {
        if ($this->session->userdata('recon_logged_in'))
        {
            $result = $this->app_model->get_stores();
            echo json_encode($result);
        } 
    }


    public function recon_data()
    {
        
        if ($this->session->userdata('recon_logged_in'))
        {
            $month = $this->sanitize(str_replace("%20", " ", $this->uri->segment(3)));
            $result = $this->app_model->recon_data($month);
            echo json_encode($result->result_array());
        }
    }


    public function view_attachement()
    {
        if ($this->session->userdata('recon_logged_in'))
        {
            $description = $this->sanitize(str_replace("%20", " ", $this->uri->segment(3)));
            $receipt_no = $this->sanitize(str_replace("%20", " ", $this->uri->segment(4)));
            $filename = "";
            $ext = "";
            $path = base_url() . 'assets/payment_docs/';
            if ($description == 'Check') 
            {
                $filename = $this->db->query("SELECT file_name FROM payment_supportingdocs WHERE receipt_no = '$receipt_no' LIMIT 1")->row()->file_name;
            }
            else
            {
                $filename = $this->db->query("SELECT supp_doc FROM payment_scheme WHERE receipt_no = '$receipt_no' LIMIT 1")->row()->supp_doc;
            }


            echo "<iframe src=\"$path$filename\" width=\"100%\" style=\"height:100%\"></iframe>"; 



        }
    }


    public function recon_exportCSV() {
        if ($this->session->userdata('recon_logged_in'))
        {
            $month = $this->sanitize(str_replace("%20", " ", $this->uri->segment(3)));
            $query = $this->app_model->recon_data($month);
            $date      = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename  = "paymentRepor" . $timeStamp;
            $this->excel->to_excel($query, $filename);
        }
    }


    public function logout()
    {
        if ($this->session->userdata('recon_logged_in'))
        {
           $newdata = array(
                'id'                    =>  '',
                'password'              =>  '',
                'user_type'             =>  '',
                'username'              =>  '',
                'first_name'            =>  '',
                'middle_name'           =>  '',
                'last_name'             =>  '',
                'user_group'            =>  '',
                'company_code'          =>  '',
                'recon_logged_in'       =>  FALSE
            );

            $this->session->unset_userdata($newdata);
            $this->session->sess_destroy();
            redirect('ctrl_recon/');
        } 
    }



}

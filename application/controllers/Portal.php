<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class Portal extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('portal_model');
        $this->load->model('app_model');
        $this->load->library('upload');
        date_default_timezone_set('Asia/Manila');
        $timestamp = time();
        $this->_currentDate = date('Y-m-d', $timestamp);
        $this->_currentYear = date('Y', $timestamp);
        $this->load->library('excel');
        $this->load->library('PHPExcel');


        # Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        ('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    # TABLE OF CONTENTS
    # PORTAL INDEX FUNCTION

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

    # PORTAL SIDE FUNCTION

    public function check_login()
    {
        $username = $this->sanitize($this->input->post('username'));
        $password = $this->sanitize($this->input->post('password'));
        $result   = $this->portal_model->check_login($username, $password);

        if ($result) {
            if ($this->session->userdata('user_type') == 'Tenant') {
                redirect('portal/home');
            } else if ($this->session->userdata('user_type') == 'Admin') {
                redirect('portal/admin_home');
            } else {
                $this->session->set_flashdata('message', 'Invalid Login');
                redirect('portal/');
            }
        } else {
            $this->session->set_flashdata('message', 'Invalid Login');
            redirect('portal/');
        }
    }

    public function logout()
    {
        $newdata = array(
            'id'                    => '',
            'username'              => '',
            'tenant_id'             => '',
            'password'              => '',
            'user_type'             => '',
            'portal_logged_in'     => FALSE
        );

        $session = (object)$this->session->userdata;

        if (isset($session->session_id)) {
            $user_session_data = ['date_ended' => date('Y-m-d H:i:s')];

            $this->db->where('session_id', $session->session_id);
            $this->db->update('user_session', $user_session_data);
        }

        $this->session->unset_userdata($newdata);
        $this->session->sess_destroy();
        redirect('portal/');
    }

    public function index()
    {
        if ($this->session->userdata('portal_logged_in') && $this->session->userdata('user_type') == 'tenant') {
            redirect('portal/home');
        } else {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/PortalPages/login_portal', $data);
        }
    }

    public function home()
    {
        if ($this->session->userdata('portal_logged_in')) {
            redirect('portal/tenant_soa');
        } else {
            redirect('portal/');
        }
    }

    # PAYMENT ADVICE
    public function paymentAdvice()
    {
        if ($this->session->userdata('portal_logged_in')) {

            $tenant_id = $this->session->userdata('tenant_id');
            $store = str_replace(array('-', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'LT', 'ST'), "", $tenant_id);

            $data['current_date']   = getCurrentDate();
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();

            // $data['bank']        = $this->portal_model->getBankAccount($store);
            $data['bankAccount'] = array();
            $data['location']    = '';

            if ($store == 'ACT') {
                $data['location'] = 'Alta Citta';
            } else if ($store == 'AM') {
                $data['location'] = 'Alturas Mall';
            } else if ($store == 'ICM') {
                $data['location'] = 'Island City Mall';
            } else if ($store == 'PM') {
                $data['location'] = 'Plaza Marcela';
            }

            if ($tenant_id == 'ICM-LT000008' || $tenant_id == 'ICM-LT000442' || $tenant_id == 'ICM-LT000492' || $tenant_id == 'ICM-LT000035' || $tenant_id == 'ICM-LT000120') {
                $data['bank'] = [array('bank_name' => 'Banks of the Philippine Islands')];
            } elseif ($store == 'ACT') {
                if ($tenant_id == 'ACT-LT000027') {
                    $data['bank'] = [array('bank_name' => 'Banks of the Philippine Islands')];
                } else {
                    $data['bank'] = [array('bank_name' => 'PNB')];
                }
            } elseif ($tenant_id == 'ICM-LT000218' || $tenant_id == 'ICM-LT000219') {
                $data['bank'] = [array('bank_name' => 'Land Bank of the Philippines')];
            } else {
                if ($store == 'AM') {
                    $data['bank'] = [array('bank_name' => 'PNB')];
                } elseif ($store == "PM") {
                    $data['bank'] = [array('bank_name' => 'Land Bank of the Philippines')];
                } elseif ($store == 'AM' || $tenant_id != 'ICM-LT000008' || $tenant_id != 'ICM-LT000442' || $tenant_id != 'ICM-LT000492' || $tenant_id != 'ICM-LT000035' || $tenant_id != 'ICM-LT000120') {
                    $data['bank'] = [array('bank_name' => 'PNB')];
                } else {
                    $data['bank'] = [array('bank_name' => 'PNB'), array('bank_name' => 'Land Bank of the Philippines'), array('bank_name' => 'Banks of the Philippine Islands')];
                }
            }

            $this->load->view('leasing/PortalTemplates/portal_header', $data);
            $this->load->view('leasing/PortalPages/payment_advice');
            $this->load->view('leasing/PortalTemplates/portal_footer');
        } else {
            redirect('portal/');
        }
    }

    # ADMIN SIDE FUNCTIONS
    public function notices()
    {
        if ($this->session->userdata('portal_logged_in')) {

            $data['current_date']   = getCurrentDate();
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['notices']        = $this->portal_model->getNotices();
            $data['count']          = count($data['notices']);

            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_notices');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    public function tenant_soa()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/PortalTemplates/portal_header', $data);
            $this->load->view('leasing/PortalPages/reprint_soa');
            $this->load->view('leasing/PortalTemplates/portal_footer');
        } else {
            redirect('portal/');
        }
    }

    public function rr_ledger()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['current_date']   = $this->_currentDate;

            $this->load->view('leasing/PortalTemplates/portal_header', $data);
            $this->load->view('leasing/PortalPages/rr_ledger');
            $this->load->view('leasing/PortalTemplates/portal_footer');
        } else {
            redirect('portal/');
        }
    }

    public function utilityReadings()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['current_date']   = $this->_currentDate;
            $this->load->view('leasing/PortalTemplates/portal_header', $data);
            $this->load->view('leasing/PortalPages/portal_utility_reading');
            $this->load->view('leasing/PortalTemplates/portal_footer');
        } else {
            redirect('portal/');
        }
    }

    public function getReading()
    {
        $tenant_id = str_replace("%20", "", $this->uri->segment(2));
        $startDate = str_replace("-", "", $this->uri->segment(3));
        $endDate   = str_replace("-", "", $this->uri->segment(4));

        $result = $this->portal_model->getReading($tenant_id, $startDate, $endDate);
        echo json_encode($result);
    }

    public function user_credentials()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $result = $this->app_model->tenant_user_credentials();
            echo json_encode($result);
        }
    }

    public function get_tenantSoa()
    {
        if ($this->session->userdata('portal_logged_in')) {
            # Format JSON POST
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
            header('Access-Control-Allow-Methods: GET, POST, PUT');
            $jsonstring = file_get_contents('php://input');
            $arr        = json_decode($jsonstring, true);


            $trade_name = $arr["param"];
            $result     = $this->app_model->get_tenantSoa($trade_name);
            echo json_encode($result);
        }
    }

    public function update_usettings_tenant()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $username = $this->sanitize($this->input->post('username'));
            $new_pass = $this->sanitize($this->input->post('new_pass'));
            $id = $this->uri->segment(3);

            echo $id;
            exit();
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

    public function get_forwarded_balance()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $tenant_id = str_replace("%20", "", $this->uri->segment(2));
            $startDate = str_replace("-", "", $this->uri->segment(3));
            $forwardedBalance = $this->app_model->get_forwarded_balance($tenant_id, $startDate);

            $fbalance = array();

            foreach ($forwardedBalance as $value) {
                if ($value['balance'] == '') {
                    $fbalance[] = ['forwardedBalance' => $value['balance']];
                } else {
                    $fbalance[] = ['forwardedBalance' => $value['balance']];
                }
            }

            echo json_encode($fbalance);
        }
    }

    public function get_ledgerTenant()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $tenant_id = str_replace("%20", "", $this->uri->segment(2));
            $startDate = str_replace("-", "", $this->uri->segment(3));
            $endDate = str_replace("-", "", $this->uri->segment(4));

            $result           = $this->app_model->get_tenant_ledger($tenant_id, $startDate, $endDate);
            $forwardedBalance = $this->app_model->get_forwarded_balance($tenant_id, $startDate);

            $result_withbalance = array();
            $prev_refno = '';
            $beggining_balance = 0;
            $running_balance = 0;

            foreach ($result as $value) {
                if ($value['ref_no'] != $prev_refno) {
                    $result_withbalance[] =
                        [
                            'document_type' => $value['document_type'],
                            'flag'          => $value['type'],
                            'due_date'      => $value['due_date'],
                            'doc_no'        => $value['doc_no'],
                            'ref_no'        => $value['ref_no'],
                            'posting_date'  => $value['posting_date'],
                            'debit'         => $value['debit'],
                            'credit'        => $value['credit'],
                            'balance'       => $value['balance'],
                            'adj_amount'    => $value['adj_amount'],
                            'inv_amount'    => $value['invoice_amount'],
                            'runningBalance' => $value['runningBalance']
                        ];

                    $running_balance = $value['balance'];
                } else {
                    if ($value['debit'] == "0.00" || $value['debit'] == null) {
                        $new_running_balance = $running_balance - $value['credit'];

                        $result_withbalance[] =
                            [
                                'document_type' => $value['document_type'],
                                'flag'           => $value['type'],
                                'due_date'      => $value['due_date'],
                                'doc_no'        => $value['doc_no'],
                                'ref_no'        => $value['ref_no'],
                                'posting_date'  => $value['posting_date'],
                                'debit'         => $value['debit'],
                                'credit'        => $value['credit'],
                                'adj_amount'    => $value['adj_amount'],
                                'inv_amount'    => $value['invoice_amount'],
                                'runningBalance' => $value['runningBalance'],
                                'balance'       => $new_running_balance,
                            ];

                        $running_balance = $new_running_balance;
                    } else {

                        $new_running_balance = $running_balance + $value['debit'];

                        $result_withbalance[] =
                            [
                                'document_type' => $value['document_type'],
                                'flag'          => $value['type'],
                                'due_date'      => $value['due_date'],
                                'doc_no'        => $value['doc_no'],
                                'ref_no'        => $value['ref_no'],
                                'posting_date'  => $value['posting_date'],
                                'debit'         => $value['debit'],
                                'credit'        => $value['credit'],
                                'adj_amount'    => $value['adj_amount'],
                                'inv_amount'    => $value['invoice_amount'],
                                'runningBalance' => $value['runningBalance'],
                                'balance'       => $new_running_balance,
                            ];

                        $running_balance = $new_running_balance;
                    }
                }
                $prev_refno = $value['ref_no'];
            }

            echo json_encode($result_withbalance);
        }
    }

    public function get_payment_details()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $ref_no = $this->uri->segment(2);
            $tenant_id = $this->uri->segment(3);
            $payment_details = $this->app_model->get_payment_details($ref_no, $tenant_id);
            echo json_encode($payment_details);
        }
    }

    public function check_oldpass_tenant($data)
    {
        $data = explode("_", $data);
        $oldpass = $data[0];
        $id = $data[1];

        $result = $this->db->query(
            "SELECT
            `id`
            FROM
            `tenant_users`
            WHERE
            `password` = '" . md5($oldpass) . "'
            AND
            `id` = '$id'"
        );

        if ($result->num_rows() > 0) {
            echo false;
        } else {
            echo true;
        }
    }

    public function verify_username_update($data)
    {
        $data = explode("_", $data);
        $username = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
            id
            FROM
            tenant_users
            WHERE
            tenant_id = '$username'
            AND
            id <> '$id'");
        if ($result->num_rows() > 0) {
            echo true;
        } else {

            echo false;
        }
    }

    public function uploadLeasingData()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['current_date']   = $this->_currentDate;
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/adminLeasingData');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    # =============================== ADMIN CONTROLLER =============================== #
    public function admin_home()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['notices']        = $this->portal_model->getNotices();
            $data['count']          = count($data['notices']);
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_portal');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    public function admin_soa()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['notices']        = $this->portal_model->getNotices();
            $data['count']          = count($data['notices']);
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_soa');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    public function admin_tenants()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['tenantUsers'] = $this->portal_model->getUsers();
            $data['notices']        = $this->portal_model->getNotices();
            $data['count']          = count($data['notices']);
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_tenants');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    public function admin_invoices()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['tenantUsers'] = $this->portal_model->getUsers();
            $data['notices']        = $this->portal_model->getNotices();
            $data['count']          = count($data['notices']);
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_invoices');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    public function perStore()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['tenantUsers'] = $this->portal_model->getUsers();
            $data['notices']        = $this->portal_model->getNotices();
            $data['count']          = count($data['notices']);
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_invoicePerStore');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    public function allData()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['tenantUsers'] = $this->portal_model->getUsers();
            $data['notices']        = $this->portal_model->getNotices();
            $data['count']          = count($data['notices']);
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_invoiceAllData');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    public function perTenant()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['tenantUsers'] = $this->portal_model->getUsers();
            $data['notices']        = $this->portal_model->getNotices();
            $data['count']          = count($data['notices']);
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_invoicePerTenant');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    public function admin_payment()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['tenantUsers'] = $this->portal_model->getUsers();
            $data['notices']        = $this->portal_model->getNotices();
            $data['count']          = count($data['notices']);
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_payment');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    public function admin_paymentPerStore()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['tenantUsers'] = $this->portal_model->getUsers();
            $data['notices']        = $this->portal_model->getNotices();
            $data['count']          = count($data['notices']);
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_paymentPerStore');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }

    public function soa_balances()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $tenant_id = $this->session->userdata('tenant_id');
            $soa_docs = $this->portal_model->getSoaWithBalances($tenant_id);

            JSONResponse($soa_docs);
        } else {
            redirect('portal/');
        }
    }

    public function getSoa()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $soa_no = $this->uri->segment(3);
            $soa    = $this->portal_model->getSoa($soa_no);

            echo json_encode($soa);
        } else {
            redirect('portal/');
        }
    }

    public function savePaymentAdvice()
    {
        if ($this->session->userdata('portal_logged_in')) {

            $data           = $this->input->post(NULL, FILTER_SANITIZE_STRING);
            $multilocation  = array();
            $msg            = array();
            $payment_advice = array();
            $pa_soa         = array();

            # CHECK PROOF OF PAYMENT
            $proof_of_transfer = $_FILES['supp_doc']['name'];
            $file_name         = str_replace(['"', '[', ']'], '', json_encode($proof_of_transfer));
            $ext               = pathinfo($_FILES['supp_doc']['name'], PATHINFO_EXTENSION);
            $target            = getcwd() . DIRECTORY_SEPARATOR . 'assets/proof_of_payment/' . $file_name;

            if (!empty($data['multi'])) {
                foreach ($data['multi'] as $value) {
                    $multilocation[] = $value;
                }
            }

            if (isset($data)) {
                if ($file_name != '') {
                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                        $this->db->trans_start();

                        move_uploaded_file($_FILES['supp_doc']['tmp_name'], $target);

                        if ($data['payment_type'] == 'One Location') {
                            $payment_advice =
                                [
                                    'store'             => $data['storeLocation'],
                                    'tenant_id'         => $this->session->userdata('tenant_id'),
                                    'bank_account'      => $data['bank_code'],
                                    'store_account'     => $data['store_account'],
                                    'tenant_bank'       => $data['t_bankaccount'],
                                    'account_number'    => $data['acount_number'],
                                    'account_name'      => $data['account_name'],
                                    'payment_date'      => $data['payment_date'],
                                    'payment_type'      => $data['payment_type'],
                                    'amount_paid'       => str_replace(',', '', $data['amount_paid']),
                                    'proof_of_transfer' => $file_name
                                ];

                            $this->db->insert('payment_advice', $payment_advice);

                            $pa_id = $this->db->insert_id();

                            $pa_soa =
                                [
                                    'payment_advice_id' => $pa_id,
                                    'soa_no'            => $data['soa_data'],
                                    'tenant_id'         => $this->session->userdata('tenant_id'),
                                    'total_payable'     => str_replace(',', '', $data['totalPayable'])
                                ];

                            $this->db->insert('payment_advice_soa', $pa_soa);
                        } else {
                            $payment_advice =
                                [
                                    'store'             => $data['storeLocation'],
                                    'tenant_id'         => $this->session->userdata('tenant_id'),
                                    'bank_account'      => $data['bank_code'],
                                    'store_account'     => $data['store_account'],
                                    'tenant_bank'       => $data['t_bankaccount'],
                                    'account_number'    => $data['acount_number'],
                                    'account_name'      => $data['account_name'],
                                    'payment_date'      => $data['payment_date'],
                                    'payment_type'      => $data['payment_type'],
                                    'amount_paid'       => str_replace(',', '', $data['amount_paid']),
                                    'proof_of_transfer' => $file_name
                                ];

                            $this->db->insert('payment_advice', $payment_advice);

                            $pa_id = $this->db->insert_id();

                            foreach ($multilocation as $value) {

                                $pa_soa =
                                    [
                                        'payment_advice_id' => $pa_id,
                                        'soa_no'            => $data['payment_type'],
                                        'tenant_id'         => $value['locations'],
                                        'total_payable'     => str_replace(',', '', $data['totalPayable'])
                                    ];

                                $this->db->insert('payment_advice_soa', $pa_soa);
                            }
                        }

                        $this->db->trans_complete();

                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();

                            JSONResponse(['info' => 'error', 'message' => 'Something went wrong! Unable to send payment advice']);
                        } else {
                            $msg = ['message' => 'Payment Advice Sent.', 'info' => 'Success'];
                        }
                    } else {
                        $msg = ['message' => 'File Format not supported. Only image files are supported. Example: jpg, jpeg, and png.', 'info' => 'error'];
                    }
                } else {
                    $msg = ['message' => 'Please upload proof of payment to continue.', 'info' => 'error'];
                }
            } else {
                $msg = ['message' => 'Data sent seems to be empty. Please try again.', 'info' => 'error'];
            }

            JSONResponse($msg);
        } else {
            redirect('portal/');
        }
    }

    public function getTenants()
    {
        $tenant_id = $this->input->post('store') . "-" . $this->input->post('type');
        $tenants = $this->portal_model->getTenants2($tenant_id);

        echo json_encode($tenants);
    }

    public function get_soa()
    {
        $tenantID  = $this->input->post('store') . '-' . $this->input->post('tenant_type');
        $startDate = $this->input->post('start_date');
        $endDate   = $this->input->post('last_date');
        $data      = array();

        $soa = $this->portal_model->get_soa($tenantID, $startDate, $endDate);

        foreach ($soa as $value) {

            $checkBox = '<input type="checkbox" id="uploadCheck" name="uploadCheck[]" value="' . $value['id'] . '">';
            $action = '<span class="btn btn-primary btn-xs" style = "margin-right:.5rem" data-toggle="tooltip" title="View" onClick = viewFile("' . trim($value['file_name']) . '")>' . '<i class="fa fa-file-pdf-o" aria-hidden="true"></i> View ' . '</span> &nbsp;
            <span class="btn btn-danger btn-xs" data-toggle="tooltip" title="Upload" onClick = upload("' . $value['id'] . '")><i class="fa fa-upload" aria-hidden="true"></i> Upload</span>';

            $sub_array = array();
            $sub_array[] = $value['id'];
            $sub_array[] = $value['tenant_id'];
            $sub_array[] = $value['file_name'];
            $sub_array[] = $value['soa_no'];
            $sub_array[] = $value['billing_period'];
            $sub_array[] = $value['amount_payable'];
            $sub_array[] = $value['posting_date'];
            $sub_array[] = $value['collection_date'];
            $sub_array[] = $value['transaction_date'];
            $sub_array[] = $value['upload_status'];
            $sub_array[] = $action;
            $sub_array[] = $checkBox;
            $sub_array[] = $value['trade_name'];
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function search_tradeName()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $result = $this->portal_model->search_tradeName();
            echo json_encode($result);
        }
    }

    public function get_payment()
    {
        $tenant    = $this->input->post('tenant');
        $tenant_id = str_replace(array('[', ']'), '', explode(" - ", $tenant));
        $data      = array();

        $payment = $this->portal_model->getPayments($tenant_id[0]);

        foreach ($payment as $value) {

            $checkBox = '<input type="checkbox" id="uploadCheckPayment" name="uploadCheckPayment[]" value="' . $value['id'] . '">';
            $action = '<span class="btn btn-primary btn-xs" style = "margin-right:.5rem" data-toggle="tooltip" title="View" onClick = viewFile("' . trim($value['receipt_doc']) . '")>' . '<i class="fa fa-file-pdf-o" aria-hidden="true"></i> View ' . '</span> &nbsp;
            <span class="btn btn-danger btn-xs" data-toggle="tooltip" title="Upload" onClick = uploadPayment("' . $value['id'] . '")><i class="fa fa-upload" aria-hidden="true"></i> Upload</span>';

            $sub_array = array();
            $sub_array[] = $value['receipt_no'];
            $sub_array[] = $value['tender_typeDesc'];
            $sub_array[] = 'P ' . number_format($value['amount_paid'], 2);

            if ($value['check_no'] == '') {
                $sub_array[] = 'N/A';
            } else {
                $sub_array[] = $value['check_no'];
            }

            if ($value['check_date'] == '0000-00-00' || $value['check_date'] == '') {
                $sub_array[] = 'N/A';
            } else {
                $sub_array[] = $value['check_date'];
            }
            $sub_array[] = $value['payee'];

            $sub_array[] = $action;
            $sub_array[] = $value['id'];
            $sub_array[] = $value['receipt_doc'];
            $sub_array[] = $checkBox;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function get_paymentPerStore()
    {
        $tenantID  = $this->input->post('store') . '-' . $this->input->post('tenant_type');
        $startDate = $this->input->post('start_date');
        $endDate   = $this->input->post('last_date');
        $data      = array();

        $payment = $this->portal_model->getPaymentsPerstore($tenantID, $startDate, $endDate);

        foreach ($payment as $value) {

            $checkBox = '<input type="checkbox" id="uploadCheckPayment" name="uploadCheckPayment[]" value="' . $value['id'] . '">';
            $action = '<span class="btn btn-primary btn-xs" style = "margin-right:.5rem" data-toggle="tooltip" title="View" onClick = viewFile("' . trim($value['receipt_doc']) . '")>' . '<i class="fa fa-file-pdf-o" aria-hidden="true"></i> View ' . '</span> &nbsp;
            <span class="btn btn-danger btn-xs" data-toggle="tooltip" title="Upload" onClick = uploadPayment("' . $value['id'] . '")><i class="fa fa-upload" aria-hidden="true"></i> Upload</span>';

            $sub_array = array();
            $sub_array[] = $value['id'];
            $sub_array[] = $value['trade_name'];
            $sub_array[] = $value['tenant_id'];
            $sub_array[] = $value['receipt_no'];
            $sub_array[] = $value['tender_typeDesc'];
            $sub_array[] = 'P ' . number_format($value['amount_due'], 2);
            $sub_array[] = 'P ' . number_format(-1 * $value['amount_paid'], 2);

            if ($value['check_no'] == '') {
                $sub_array[] = 'N/A';
            } else {
                $sub_array[] = $value['check_no'];
            }

            if ($value['check_date'] == '0000-00-00' || $value['check_date'] == '') {
                $sub_array[] = 'N/A';
            } else {
                $sub_array[] = $value['check_date'];
            }
            $sub_array[] = $value['payee'];
            $sub_array[] = $action;
            $sub_array[] = $value['receipt_doc'];
            $sub_array[] = $checkBox;
            $sub_array[] = $value['soa_no'];
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    # TO create error

    // public function function callthisError()
    // {
    //     $this->portal_mode->get();
    //     $query();
    // }

    # INVOICES FIRST
    public function uploadAllInvoiceData()
    {
        $startDate = $this->input->post("start_date");
        $endDate   = $this->input->post("last_date");
        $url       = 'https://leasingportal.altsportal.com/uploadAllInvoiceData';

        $data = array();

        $subsidiary = $this->portal_model->getInvoices1('subsidiary_ledger', $startDate, $endDate);
        $general    = $this->portal_model->getInvoices1('general_ledger', $startDate, $endDate);
        $ledger     = $this->portal_model->getInvoices3($startDate, $endDate);
        $invoice    = $this->portal_model->getInvoices4($startDate, $endDate);
        $tmpcharges = $this->portal_model->getTmpCharges();

        if ($this->isDomainAvailable('leasingportal.altsportal.com')) {
            if (!empty($subsidiary)) {
                if (!empty($ledger)) {
                    if (!empty($invoice)) {
                        $this->db->trans_start();

                        $data     = ["table" => "general_ledger", "data" => $general];
                        $response = $this->sendData($url, $data);

                        $data = array();

                        $data     = ["table" => "subsidiary_ledger", "data" => $subsidiary];
                        $response = $this->sendData($url, $data);

                        if ($response == 'Success') {

                            $data     = ["table" => "ledger", "data" => $ledger];
                            $response = $this->sendData($url, $data);

                            if ($response == 'Success') {

                                $data     = ["table" => "tmp_preoperationcharges", "data" => $tmpcharges];
                                $response = $this->sendData($url, $data);

                                $data = array();

                                $data     = ["table" => "invoicing", "data" => $invoice];
                                $response = $this->sendData($url, $data);

                                if ($response == 'Success') {

                                    // $update = 
                                    // [
                                    //     'upload_status' => 'Uploaded',
                                    //     'upload_date' => date('Y-m-d')
                                    // ];

                                    foreach ($subsidiary as $value) {
                                        //$this->db->where('id', $value['id'])
                                        // $this->db-update('subsidiary_ledger', $update);

                                        //$this->db->where('id', $value['id'])
                                        // $this->db-update('general_ledger', $update);
                                    }

                                    foreach ($ledger as $value) {
                                        //$this->db->where('id', $value['id'])
                                        // $this->db-update('ledger', $update);
                                    }

                                    foreach ($invoice as $value) {
                                        //$this->db->where('id', $value['id'])
                                        // $this->db-update('invoicing', $update);
                                    }

                                    foreach ($tmpcharges as $value) {
                                        //$this->db->where('id', $value['id'])
                                        // $this->db-update('tmp_preoperationcharges', $update);
                                    }

                                    $this->db->trans_complete();

                                    if ($this->db->trans_status() === FALSE) {
                                        $msg = ['message' => 'Uploading Data Failed. Try Again.', 'info' => 'error'];
                                    } else {
                                        $msg = ['message' => 'Uploaded Succesfully.', 'info' => 'success'];
                                    }
                                } else if ($response == "No Data" || $response == "Uploading Failed.") {
                                    $msg = ['message' => 'Invoicing : ' . $response . ', Cant Proceed.', 'info' => 'error'];
                                }
                            } else if ($response == "No Data" || $response == "Uploading Failed.") {
                                $msg = ['message' => 'Ledger : ' . $response . ', Cant Proceed.', 'info' => 'error'];
                            }
                        } else if ($response == "No Data" || $response == "Uploading Failed.") {
                            $msg = ['message' => 'Subsidiary Ledger: ' . $response . ', Cant Proceed.', 'info' => 'error'];
                        }
                    } else {
                        $msg = ['message' => 'Invoice Data Empty, Cant Proceed.', 'info' => 'empty'];
                    }
                } else {
                    $msg = ['message' => 'Ledger Data Empty, Cant Proceed.', 'info' => 'empty'];
                }
            } else {
                $msg = ['message' => 'Subsidiary Data Empty, Cant Proceed.', 'info' => 'empty'];
            }
        } else {
            $msg = ['message' => 'Connection Error, Please check your internet connection and try again.', 'info' => 'error'];
        }

        echo json_encode($msg);
    }


    # USE THIS FIRST
    public function perTenantUpload()
    {
        $data       = $this->input->post(NULL, FILTER_SANITIZE_STRING);
        $url        = 'https://leasingportal.altsportal.com/perTenantUpload';
        $msg        = array();
        $subsidiary = array(); #SUBSIDIARY LEDGER AND GENERAL LEDGER
        $ledger     = array();
        $invoice    = array();
        $tmpcharges = array();
        $toUpload   = array();
        $update     = array();
        $portal     = '';

        if (isset($data)) {
            $this->db->trans_start();

            $subsidiary = $this->portal_model->getDataV1($data['tenant_id'], $data['start_date'], $data['last_date'], 'subsidiary_ledger');
            $ledger     = $this->portal_model->getDataV1($data['tenant_id'], $data['start_date'], $data['last_date'], 'ledger');
            $invoice    = $this->portal_model->getDataV2($data['tenant_id'], $data['start_date'], $data['last_date'], 'invoicing');
            $tmpcharges = $this->portal_model->getDataV2($data['tenant_id'], $data['start_date'], $data['last_date'], 'tmp_preoperationcharges');

            if (!empty($invoice)) {
                if ($this->isDomainAvailable('leasingportal.altsportal.com')) {
                    $toUpload = ['subsidiary' => $subsidiary, 'ledger' => $ledger, 'invoicing' => $invoice, 'charges' => $tmpcharges];
                    $portal   = $this->sendData($url, $toUpload);

                    if ($portal == 'success') {
                        $update = ['upload_status' => 'Uploaded', 'upload_date' => date('Y-m-d')];

                        foreach ($subsidiary as $value) {
                            $this->db->where('id', $value['id'])
                                ->update('subsidiary_ledger', $update);

                            // $this->db->where('id', $value['id'])
                            //          ->update('general_ledger', $update);
                        }

                        foreach ($ledger as $value) {
                            $this->db->where('id', $value['id'])
                                ->update('ledger', $update);
                        }

                        foreach ($invoice as $value) {
                            $this->db->where('id', $value['id'])
                                ->update('invoicing', $update);
                        }

                        if (isset($tmpcharges)) {
                            foreach ($tmpcharges as $value) {
                                $this->db->where('id', $value['id'])
                                    ->update('tmp_preoperationcharges', $update);
                            }
                        }

                        $this->db->trans_complete();

                        if ($this->db->trans_status() === 'FALSE') {
                            $this->db->trans_rollback();
                            $msg = ['info' => 'error', 'message' => 'Something went wrong, please check data uploaded.'];
                        } else {
                            $msg = ['info' => 'success', 'message' => 'Invoices Uploaded Succesfully.'];
                        }
                    } else if ($portal == 'error') {
                        $msg = ['info' => 'error', 'message' => 'Failed Uploading Invoices, Please try again.'];
                    } else if ($portal == 'empty') {
                        $msg = ['info' => 'error', 'message' => 'Failed Uploading Invoices, no data to upload.'];
                    }
                } else {
                    $msg = ['info' => 'error', 'message' => 'PC has no connection, please try again.'];
                }
            } else {
                $msg = ['info' => 'error', 'message' => 'No New Invoices Found.'];
            }
        } else {
            $msg = ['info' => 'error', 'message' => 'Data seems to be empty. Please try again.'];
        }

        $this->saveLog('Invoice', '', $data['tenant_id'], $msg['info'], $msg['message']);
        echo json_encode($msg);
    }

    # SOA SECOND
    public function uploadSoaData()
    {
        $soaID    = $this->uri->segment(2);
        $url1     = 'https://leasingportal.altsportal.com/uploadSoaDataAPI';
        $url2     = 'https://leasingportal.altsportal.com/uploadSoaFile';
        $url3     = 'https://leasingportal.altsportal.com/notifications';

        $msg   = array();
        $data  = array();
        $b     = array();

        $soafiledata = $this->portal_model->getSOAFile($soaID);
        $soalinedata = $this->portal_model->getSOALine($soafiledata->soa_no);
        $tenant      = $this->portal_model->getTenant($soafiledata->tenant_id);

        $balance        = $this->app_model->get_forwarded_balance($soafiledata->tenant_id, $soafiledata->posting_date);
        $previousAmount = $this->portal_model->getPreviousBalance($soafiledata->posting_date, $soafiledata->tenant_id);

        # $defaultPassword = MD5("Portal" . str_replace(array('ICM', 'AM', 'PM', 'ACT', '-', 'LT', 'ST', '0'), "", $soafiledata->tenant_id));

        foreach ($balance as $value) {
            $b = $value['balance'];
        }

        $filePath = 'http://172.16.161.37/agc-pms/assets/pdf/' . $soafiledata->file_name;
        $b64Doc   = chunk_split(base64_encode(file_get_contents($filePath)));

        if (!empty($soafiledata) && !empty($soalinedata) && !empty($balance) && !empty($previousAmount)) {
            if ($this->isDomainAvailable('leasingportal.altsportal.com')) {
                $this->db->trans_start();

                $data    = array('soa_file' => $soafiledata, 'soa_line' => $soalinedata, 'tenant' => $tenant);
                $soaData = $this->sendData($url1, $data);

                if ($soaData == 'success') {
                    $soaFile = $this->sendData($url2, array('pdfB64' => $b64Doc, 'file_name' => $soafiledata->file_name));

                    if ($soaFile == 'PDF Saved') {
                        $data    = array('soa_file' => $soafiledata, 'balance' => $b, 'previous' => $previousAmount->amount_payable, 'tenant' => $tenant);
                        $soaData = $this->sendData($url3, $data);

                        $update =
                            [
                                'upload_status' => 'Uploaded',
                                'upload_date'   => date('Y-m-d')
                            ];

                        $this->db->where('id', $soafiledata->id);
                        $this->db->update('soa_file', $update);


                        foreach ($soalinedata as $value) {
                            $this->db->where('id', $value['id']);
                            $this->db->update('soa_line', $update);
                        }

                        $this->db->trans_complete();

                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            $msg = ['message' => 'Uploading Data Failed. Try Again.', 'info' => 'error'];
                        } else {
                            $msg = ['message' => 'Uploaded Succesfully.', 'info' => 'success'];
                        }
                    } else if ($soaFile == 'Error') {
                        $msg = ['message' => 'Uploading SOA Failed. Try Again.', 'info' => 'error'];
                    } else if ($soaFile == 'Exist') {
                        $msg = ['message' => 'SOA already uploaded.', 'info' => 'exist'];
                    }
                } else if ($soaData == 'error') {
                    $msg = ['message' => 'Uploading Failed, something went wrong. Please try again.', 'info' => 'error'];
                } else if ($soaData == 'empty') {
                    $msg = ['message' => 'Data uploaded seems to be empty, Please try again.', 'info' => 'empty'];
                }
            } else {
                $msg = ['message' => 'Connection Error, Please check your internet connection and try again.', 'info' => 'error'];
            }
        } else {
            $msg = ['message' => 'Error, It seems there are no data found to be uploaded, Please try again.', 'info' => 'error'];
        }

        $this->saveLog('SOA', $soafiledata->soa_no, $soafiledata->tenant_id, $msg['info'], $msg['message']);
        echo json_encode($msg);
    }

    # PAYMENT THIRD
    public function uploadPaymentData()
    {
        $paymentID = $this->uri->segment(2);

        $this->db->trans_start();
        # CONTAINER FOR payment_scheme
        $paymentScheme = $this->portal_model->getPaymentScheme($paymentID);

        $slData  = array();
        $glData  = array();
        $Ledger  = array();

        # GET DATA FROM subsidiary_ledger
        $sl      = $this->portal_model->getSL($paymentScheme->receipt_no);
        $payment = $this->portal_model->getPaymentTable($paymentScheme->receipt_no);

        # LOOP TO GET THE REFERENCE NO
        foreach ($sl as $value) {
            $slData[] = $this->portal_model->getLedgers($value['ref_no'], 'subsidiary_ledger');
        }

        # SUBSIDIARY AND GENERAL LEDGER CONTAINER
        $subsidiary = array();

        # LOOP TO GET THE ARRAYS CONTAINING THE DATA FROM subsidiary_ledger USING THE ref_no
        for ($i = 0; $i < count($slData); $i++) {
            for ($z = 0; $z < count($slData[$i]); $z++) {
                $subsidiary[] = $slData[$i][$z];
            }
        }

        # CONTAINER
        $doc_no = array();

        foreach ($subsidiary as $value) {

            $doc_no[] = $value['doc_no'];
        }

        # DOCUMENT NUMBER FOR ledger TABLE
        $forLedger = array_unique($doc_no);
        $lData     = array();

        foreach ($forLedger as $value) {
            $lData[] = $this->portal_model->getLedgerTable($value);
        }

        # LEDGER CONTAINER
        $ledger = array();

        for ($i = 0; $i < count($lData); $i++) {
            for ($z = 0; $z < count($lData[$i]); $z++) {
                $ledger[] = $lData[$i][$z];
            }
        }

        # $subsidiary    = subsidiary_ledger and general_ledger
        # ledger         = $ledger
        # payment        = $payment
        # payment_scheme = $paymentScheme

        $soa_file = $this->portal_model->getSoaForPayment($paymentScheme->soa_no);

        $url          = 'https://leasingportal.altsportal.com/uploadPaymentData';
        $notification = 'https://leasingportal.altsportal.com/paymentNotification';

        if ($this->isDomainAvailable('leasingportal.altsportal.com')) {
            if (!empty($subsidiary) && !empty($ledger) && !empty($paymentScheme)) {
                $upload   = array('subsidiary' => $subsidiary, 'ledger' => $ledger, 'paymentScheme' => $paymentScheme, 'payment' => $payment);
                $response = $this->sendData($url, $upload);
                $sms      = $this->sendData($notification, array('payment_scheme' => $paymentScheme, 'soa' => $soa_file));

                if ($response == 'success') {
                    $this->db->trans_complete();

                    $update = ['upload_status' => 'Uploaded', 'upload_date' => date('Y-m-s')];

                    foreach ($subsidiary as $value) {
                        $this->db->where('id', $value['id']);
                        $this->db->update('subsidiary_ledger', $update);

                        // $this->db->where('id', $value['id']);
                        // $this->db->update('general_ledger', $update);
                    }

                    foreach ($ledger as $value) {
                        $this->db->where('id', $value['id']);
                        $this->db->update('ledger', $update);
                    }

                    if (isset($payment)) {
                        $this->db->where('id', $payment->id);
                        $this->db->update('payment', $update);
                    }

                    $this->db->where('id', $paymentScheme->id);
                    $this->db->update('payment_scheme', $update);

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $msg = ['info' => 'error', 'message' => 'Data Seems to be empty, cant proceed.'];
                    } else {
                        $msg = ['info' => 'success', 'message' => 'Payment Data Succesfully Uploaded.'];
                    }
                } else {
                    $msg = ['info' => 'error', 'message' => 'Something went wrong, please try again.'];
                }
            } else {
                $msg = ['info' => 'error', 'message' => 'Data Seems to be empty, cant proceed.'];
            }
        } else {
            $msg = ['info' => 'error', 'message' => 'PC has no connection, cant proceed.'];
        }

        $this->saveLog('Payment', $paymentScheme->receipt_no, $paymentScheme->tenant_id, $msg['info'], $msg['message']);
        echo json_encode($msg);
    }

    public function getInvoices()
    {
        $invoices  = $this->portal_model->getInvoices();
        $data      = array();
        $invoiceNo = '';

        foreach ($invoices as $key => $value) {

            if ($value['doc_no'] != $invoiceNo) {
                if ($value['gl_accountID'] == '4') {
                    $invoice = 'Basic';
                } else if ($value['gl_accountID'] == '22') {
                    $invoice = 'Other';
                }

                $data[] =
                    [
                        'id'        => $value['id'],
                        'tenant_id' => $value['tenant_id'],
                        'doc_type' => $invoice,
                        'doc_no' => $value['doc_no']
                    ];
            }

            $invoiceNo = $value['doc_no'];
        }

        var_dump($data);
    }

    public function password()
    {
        echo MD5('Portal123');
    }

    # API
    # CHECK DOMAIN - returns true, if domain is availible, false if not
    public function isDomainAvailable($domain)
    {
        $file = @fsockopen($domain, 80); #@fsockopen is used to connect to a socket

        # Verify whether the internet is working or not
        if ($file) {
            return true;
        } else {
            return false;
        }
    }

    public function sendData($url, $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $server_output = curl_exec($ch);

        return $server_output;

        curl_close($ch);
    }

    # API FUNCTIONS

    public function sendMail($subject, $message, $email, $source)
    {
        $this->load->library('email');

        $this->email->from($source);
        $this->email->to($email);
        $this->email->set_header('Header1', 'Value1');
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
    }

    public function sendSMS($number, $msg)
    {
        $apicode = "PR-ALTUR166130_RHH2A";
        $pswd    = "9)h!tc%#y$";

        $sendSMS = $this->itexmo($number, $msg, $apicode, $pswd);
        return $sendSMS;
    }

    public function itexmo($number, $message, $apicode, $passwd)
    {
        $ch = curl_init();
        $itexmo = array('1' => $number, '2' => $message, '3' => $apicode, 'passwd' => $passwd);
        curl_setopt($ch, CURLOPT_URL, "https://www.itexmo.com/php_api/api.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($itexmo));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
        curl_close($ch);
    }

    public function postAdvice()
    {
        $data   = $this->input->post(NULL, FILTER_SANITIZE_STRING);
        $multiPay = array();
        $status = ['status' => 'Posted'];
        $msg    = array();

        if (isset($data['type'])) {
            if (!empty($data)) {
                $this->db->trans_start();
                $this->db->where('id', $data['adviceid']);
                $this->db->update('payment_advice', $status);
                $this->db->trans_complete();

                if ($this->db->trans_status() === 'FALSE') {
                    $this->db->trans_rollback();
                    $msg = ['info' => 'Error', 'message' => 'Something went wrong, cant post Payment Advice.'];
                } else {
                    $msg = ['info' => 'Success', 'message' => 'Payment Advice Posted.'];
                }
            } else {
                $msg = ['info' => 'Error', 'message' => 'No Data to be Posted.'];
            }
        } else if (isset($data['m_type'])) {
            if (!empty($data)) {
                if ($data['soaApplied'] == '') {
                    $msg = ['info' => 'Error', 'message' => 'Please Input any SOA applied'];
                    echo json_encode($msg);
                    exit();
                }

                $soaApplied = str_replace(' ', '', explode(",", $data['soaApplied']));

                foreach ($soaApplied as $key => $value) {
                    $multiPay[] = explode("_", $value);
                }

                $this->db->trans_start();
                $this->db->where('id', $data['m_adviceid']);
                $this->db->update('payment_advice', $status);

                foreach ($multiPay as $value) {

                    $advice_soa = $this->db->query("SELECT * FROM payment_advice_soa WHERE tenant_id = '" . $value[0] . "' AND payment_advice_id = '" . $data['m_adviceid'] . "'")->ROW();
                    $this->db->where('id', $advice_soa->id);
                    $this->db->update('payment_advice_soa', ['soa_no' => $value[1]]);
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === 'FALSE') {
                    $this->db->trans_rollback();
                    $msg = ['info' => 'Error', 'message' => 'Something went wrong, cant post Payment Advice.'];
                } else {
                    $msg = ['info' => 'Success', 'message' => 'Payment Advice Posted.'];
                }
            } else {
                $msg = ['info' => 'Error', 'message' => 'No Data to be Posted.'];
            }
        }

        echo json_encode($msg);
    }

    public function getAdvices()
    {
        $paymentAdviceID = $this->uri->segment(2);
        $paymentType     = str_replace("%20", " ", $this->uri->segment(3));
        $advices         = array();

        if ($paymentType == 'One Location') {
            $advices = $this->portal_model->getAdvices($paymentAdviceID);
        } else {
        }

        echo json_encode($advices);
    }

    public function uploadhistory()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();

            $data['history'] = $this->portal_model->getHistory();
            $this->load->view('leasing/PortalAdmin/admin_header', $data);
            $this->load->view('leasing/PortalAdmin/admin_uploadlog');
            $this->load->view('leasing/PortalAdmin/admin_footer');
        } else {
            redirect('portal/');
        }
    }


    public function saveLog($type, $doc_no, $tenant_id, $status, $statusMessage)
    {
        $data =
            [
                'type_uploaded'  => $type,
                'doc_no'         => $doc_no,
                'tenant_id'      => $tenant_id,
                'upload_status'  => $status,
                'status_message' => $statusMessage,
                'date_uploaded'  => date('Y-m-d'),
                'user_id'        => $this->session->userdata('id')
            ];

        $this->db->trans_start();
        $this->db->insert('upload_log', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === 'FALSE') {
            $this->db->trans_rollback();
        }
    }

    public function getTenantsMulti()
    {
        $paymentAdviceID          = $this->input->post('id');
        $data['paymentadvice']    = $this->portal_model->getPaymentAdvice($paymentAdviceID);
        $data['paymentadvicesoa'] = $this->portal_model->getPaymentAdviceSoa($paymentAdviceID);

        echo json_encode($data);
    }

    public function test()
    {
        $msg = ['message' => 'asdasd', 'info' => 'asdasd111'];

        echo $this->session->userdata('id');
    }

    public function exportPaymentAdvice()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $query     = $this->portal_model->getNotices();
            $date      = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename  = "payment_advices" . $timeStamp;
            // $this->excel->to_excel($query, $filename);
            $this->excel->to_exceltenantledger($query, $filename);
        }
    }

    public function uploadCheckedSoa()
    {
        $data        = $this->input->post(NULL);
        $msg         = array();
        $b           = array();
        $b64Doc      = '';
        $soaData     = '';
        $soaFile     = '';
        $count       = count($data['uploadCheck']);
        $uploadCheck = $data['uploadCheck'];

        # PORTAL URLs
        $url1     = 'https://leasingportal.altsportal.com/uploadSoaDataAPI';
        $url2     = 'https://leasingportal.altsportal.com/uploadSoaFile';
        $url3     = 'https://leasingportal.altsportal.com/notifications';

        $testing  = 'https://leasingportal.altsportal.com/testingUpload1';

        $update = ['upload_status' => 'Uploaded', 'upload_date'   => date('Y-m-d')];

        if (!empty($data['uploadCheck'])) {
            for ($i = 0; $i < $count; $i++) {
                $soafiledata = $this->portal_model->getSOAFile($uploadCheck[$i]);
                $soalinedata = $this->portal_model->getSOALine($soafiledata->soa_no);
                $tenant      = $this->portal_model->getTenant($soafiledata->tenant_id);

                $balance        = $this->app_model->get_forwarded_balance($soafiledata->tenant_id, $soafiledata->posting_date);
                $previousAmount = $this->portal_model->getPreviousBalance($soafiledata->posting_date, $soafiledata->tenant_id);

                foreach ($balance as $value) {
                    $b = $value['balance'];
                }

                $filePath = 'http://172.16.161.37/agc-pms/assets/pdf/' . $soafiledata->file_name;

                if (!file_exists($filePath)) {
                    $b64Doc = chunk_split(base64_encode(file_get_contents($filePath)));
                } else {
                    $b64Doc = '';
                }

                if (!empty($soafiledata) && !empty($soalinedata) && !empty($balance) && !empty($previousAmount)) {
                    if ($this->isDomainAvailable('leasingportal.altsportal.com')) {

                        # SOA LINE AND SOA FILE UPLOAD
                        $data    = array('soa_file' => $soafiledata, 'soa_line' => $soalinedata, 'tenant' => $tenant);
                        $soaData = $this->sendData($url1, $data);
                        $soaFile = $this->sendData($url2, array('pdfB64' => $b64Doc, 'file_name' => $soafiledata->file_name));

                        # SOA SMS AND EMAIL
                        $data    = array('soa_file' => $soafiledata, 'balance' => $b, 'previous' => $previousAmount->amount_payable, 'tenant' => $tenant);
                        $soaData = $this->sendData($url3, $data);

                        $this->db->trans_start();
                        $this->db->where('id', $soafiledata->id);
                        $this->db->update('soa_file', $update);

                        foreach ($soalinedata as $value) {
                            $this->db->where('id', $value['id']);
                            $this->db->update('soa_line', $update);
                        }

                        $this->saveLog('SOA', $soafiledata->soa_no, $soafiledata->tenant_id, 'Uploaded', 'Uploaded Successfully');

                        $this->db->trans_complete();

                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                        } else {
                            continue;
                        }
                    } else {
                        $msg = ['message' => 'Connection Error, Please check your internet connection and try again.', 'info' => 'error'];
                    }
                } else {
                    $msg = ['message' => 'Error, It seems there are no data found to be uploaded, Please try again.', 'info' => 'error'];
                }
            }

            $msg = ['message' => 'SOA Data Uploaded Successfully.', 'info' => 'success'];
        } else {

            $msg = ['message' => 'Please Check any SOA to upload.', 'info' => 'error'];
        }

        echo json_encode($msg);
    }

    public function uploadCheckedPayment()
    {
        $data        = $this->input->post(NULL);
        $count       = count($data['uploadCheckPayment']);
        $uploadCheck = $data['uploadCheckPayment'];
        $update      = ['upload_status' => 'Uploaded', 'upload_date'   => date('Y-m-d')];
        $msg         = array();

        if (!empty($data['uploadCheckPayment'])) {
            for ($id = 0; $id < $count; $id++) {

                $this->db->trans_start();
                # CONTAINER FOR payment_scheme
                $paymentScheme = $this->portal_model->getPaymentScheme($uploadCheck[$id]);

                $slData  = array();
                $glData  = array();
                $Ledger  = array();

                # GET DATA FROM subsidiary_ledger
                $sl      = $this->portal_model->getSL($paymentScheme->receipt_no);
                $payment = $this->portal_model->getPaymentTable($paymentScheme->receipt_no);

                # LOOP TO GET THE REFERENCE NO
                foreach ($sl as $value) {
                    $slData[] = $this->portal_model->getLedgers($value['ref_no'], 'subsidiary_ledger');
                }

                # SUBSIDIARY AND GENERAL LEDGER CONTAINER
                $subsidiary = array();

                # LOOP TO GET THE ARRAYS CONTAINING THE DATA FROM subsidiary_ledger USING THE ref_no
                for ($i = 0; $i < count($slData); $i++) {
                    for ($z = 0; $z < count($slData[$i]); $z++) {
                        $subsidiary[] = $slData[$i][$z];
                    }
                }

                # CONTAINER
                $doc_no = array();

                foreach ($subsidiary as $value) {

                    $doc_no[] = $value['doc_no'];
                }

                # DOCUMENT NUMBER FOR ledger TABLE
                $forLedger = array_unique($doc_no);
                $lData     = array();

                foreach ($forLedger as $value) {
                    $lData[] = $this->portal_model->getLedgerTable($value);
                }

                # LEDGER CONTAINER
                $ledger = array();

                for ($i = 0; $i < count($lData); $i++) {
                    for ($z = 0; $z < count($lData[$i]); $z++) {
                        $ledger[] = $lData[$i][$z];
                    }
                }

                # subsidiary_ledger = $subsidiary
                # ledger            = $ledger
                # payment           = $payment
                # payment_scheme    = $paymentScheme

                $soa_file = $this->portal_model->getSoaForPayment($paymentScheme->soa_no);

                $url          = 'https://leasingportal.altsportal.com/uploadPaymentData';
                $notification = 'https://leasingportal.altsportal.com/paymentNotification';

                if ($this->isDomainAvailable('leasingportal.altsportal.com')) {
                    if (!empty($subsidiary) && !empty($ledger) && !empty($paymentScheme)) {
                        $upload   = array('subsidiary' => $subsidiary, 'ledger' => $ledger, 'paymentScheme' => $paymentScheme, 'payment' => $payment);
                        $response = $this->sendData($url, $upload);
                        $sms      = $this->sendData($notification, array('payment_scheme' => $paymentScheme, 'soa' => $soa_file));

                        foreach ($subsidiary as $value) {
                            $this->db->where('id', $value['id']);
                            $this->db->update('subsidiary_ledger', $update);
                            $this->saveLog('Payment', $value['doc_no'], $value['tenant_id'], 'Uploaded', 'Uploaded Successfully');
                        }

                        foreach ($ledger as $value) {
                            $this->db->where('id', $value['id']);
                            $this->db->update('ledger', $update);
                            $this->saveLog('Payment', $value['doc_no'], $value['tenant_id'], 'Uploaded', 'Uploaded Successfully');
                        }

                        if (isset($payment)) {
                            $this->db->where('id', $payment->id);
                            $this->db->update('payment', $update);
                            $this->saveLog('Payment', $payment->doc_no, $payment->tenant_id, 'Uploaded', 'Uploaded Successfully');
                        }

                        $this->db->where('id', $paymentScheme->id);
                        $this->db->update('payment_scheme', $update);
                        $this->saveLog('Payment', $paymentScheme->receipt_no, $paymentScheme->tenant_id, 'Uploaded', 'Uploaded Successfully');


                        $this->db->trans_complete();

                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                        } else {
                            continue;
                        }
                    } else {
                        $msg = [
                            'info' => 'error', 'message' => 'Data Seems to be empty, cant proceed.'
                        ];
                    }
                } else {
                    $msg = ['info' => 'error', 'message' => 'PC has no connection, cant proceed.'];
                }
            }

            $msg = ['info' => 'success', 'message' => 'Payment Data Succesfully Uploaded.'];
        } else {
            $msg = ['message' => 'Please Check any Payment to upload.', 'info' => 'error'];
        }

        echo json_encode($msg);
    }

    public function uploadUpdate()
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['notices']        = $this->portal_model->getNotices();
        $data['count']          = count($data['notices']);
        $this->load->view('leasing/PortalAdmin/admin_header', $data);
        $this->load->view('uploadinfo');
        $this->load->view('leasing/PortalAdmin/admin_footer');
    }

    public function blastSMSEmail()
    {
        $query = $this->db->query("SELECT
                    `t`.`id`,
                    `t`.`tenant_id`,
                    `p`.`trade_name`,
                    `p`.`contact_number`,
                    `p`.`email`,
                    `lc`.`location_code`,
                    `lc`.`location_desc`
                FROM
                    `tenants` `t`,
                    `prospect` `p`,
                    `stores` `s`,
                    `floors` `f`,
                    `leasee_type` `lt`,
                    `location_code` `lc`,
                    `area_classification` `ac`,
                    `area_type` `at`
                WHERE
                    `p`.`id` = `t`.`prospect_id`
                AND
                    `p`.`lesseeType_id` = `lt`.`id`
                AND
                    `t`.`status` = 'Active'
                AND
                    `t`.`flag` = 'Posted'
                AND
                    `lc`.`status` = 'Active'
                AND
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `t`.`tenancy_type` = 'Long Term'
                AND
                    `f`.`store_id` = '2'
                AND `t`.`id` IN ('3652',
'3653',
'3654',
'3655',
'3657',
'3658',
'3659',
'3660',
'3661',
'3662',
'3663',
'3664',
'3665',
'3666',
'3667',
'3668',
'3669',
'3670',
'3671',
'3672',
'3673',
'3674',
'3676',
'3677',
'3678',
'3679',
'3685',
'3697',
'3705',
'3707',
'3708',
'3710',
'3711',
'3760',
'3774',
'3775',
'3777',
'3778',
'3780',
'3781',
'3782',
'3783',
'3784',
'3785',
'3786',
'3787',
'3788',
'3789',
'3790',
'3791',
'3792',
'3846'
)
                GROUP BY
                    `t`.`id`")->RESULT_ARRAY();

        // var_dump($query);
        $blast =  $this->sendData('https://leasingportal.altsportal.com/blastSend', ['info' => $query]);
        echo $blast;
        // var_dump($query);

        // $phone = '0977 316 6658';

        // if (preg_match("/^[0-9]{4}[0-9]{3}[0-9]{4}$/", $phone)) {
        //     echo "Valid";
        // } else {
        //     echo "Not Valid";
        // }
    }

    // public function createUsers()
    // {
    //     $tenant      = $this->portal_model->getTenants();
    //     $newUsers    = array();
    //     $existedUser = array();

    //     foreach ($tenant as $key => $value) {
    //         $dup = $this->portal_model->checkUserDuplicate($value['tenant_id']);

    //         if(empty($dup))
    //         {
    //             $defaultPassword = MD5("Portal" . str_replace(array('ICM', 'AM', 'PM', 'ACT', '-', 'LT', 'ST', '0'), "", $value['tenant_id']));

    //             $newUsers[] = 
    //             [
    //                 'tenant_id' => $value['tenant_id'],
    //                 'username'  => $value['tenant_id'],
    //                 'password'  => $defaultPassword,
    //                 'user_type' => 'Tenant'
    //             ];
    //         }
    //         else
    //         {
    //             $existedUser[] = $value['tenant_id'];
    //         }
    //     }

    //     if(!empty($newUsers) && empty($existedUser))
    //     {
    //         foreach ($newUsers as $key => $value) {
    //             $this->db->insert('tenant_users', $value);
    //         }

    //         echo "Users Saved.";
    //     }
    //     else if(!empty($newUsers) && !empty($existedUser))
    //     {
    //         foreach ($newUsers as $key => $value) {
    //             $this->db->insert('tenant_users', $value);
    //         }

    //         echo "New Users Saved";
    //     }
    //     else if(empty($newUsers) && !empty($existedUser))
    //     {
    //         echo "Users Already Existed";
    //     }
    //     else if(empty($newUsers) && empty($existedUser))
    //     {
    //         echo "No Data";
    //     }
    // }
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Ctrl_tmp extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('excel');
        $this->load->model('app_model');
        $this->load->model('ccm_model');
        $this->load->library('upload');
        $this->load->library('fpdf');
        ini_set('memory_limit', '200M');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_maxs_size', '200M');
        ini_set('max_input_time', 3600);
        ini_set('MAX_EXECUTION_TIME', '-1');
        date_default_timezone_set('Asia/Manila');
        $timestamp = time();
        $this->_currentDate = date('Y-m-d', $timestamp);
        $this->_currentYear = date('Y', $timestamp);
        $this->_user_id = $this->session->userdata('id');

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

    public function closeInternalPayment()
    {   
        if (!$this->session->userdata('cfs_logged_in'))
            JSONResponse(['type'=>'error', 'msg'=>'Unauthorize Access!']);


        $response         = array();
        $date             = new DateTime();
        $timeStamp        = $date->getTimestamp();


        $tenancy_type     = $this->sanitize($this->input->post('tenancy_type'));
        $tenant_id        = $this->sanitize($this->input->post('tenant_id'));
        $trade_name       = $this->sanitize($this->input->post('trade_name'));
        $contract_no      = $this->sanitize($this->input->post('contract_no'));

        $receipt_no       = $this->input->post('receipt_no');
        $bank_code        = $this->sanitize($this->input->post('bank_code'));
        $bank_name        = $this->sanitize($this->input->post('bank_name'));


        $tender_type      = $this->input->post('tender_type');
        $posting_date     = $this->sanitize($this->input->post('posting_date'));
        $remarks          = $this->sanitize($this->input->post('remarks'));


        //START CHECK DETAILS
        $account_no     = $this->sanitize($this->input->post('account_no'));
        $account_name   = $this->sanitize($this->input->post('account_name'));
        $ds_no          = $this->input->post('ds_no');
        $check_date     = $this->sanitize($this->input->post('check_date'));
        $expiry_date    = $this->sanitize($this->input->post('expiry_date'));
        $check_class    = $this->sanitize($this->input->post('check_class'));
        $check_category = $this->sanitize($this->input->post('check_category'));
        $customer_name  = $this->sanitize($this->input->post('customer_name'));
        $check_bank     = $this->sanitize($this->input->post('check_bank'));
        //END CHECK DETAILS

        $amount_paid      = str_replace(",", "", $this->sanitize($this->input->post('amount_paid')));
        $total_payable    = str_replace(",", "", $this->sanitize($this->input->post('total_payable')));

        $ips              = $this->input->post('ips');
        

        $tender_amount    = $amount_paid;


        $receipts = $this->db->query("SELECT `id` FROM `payment_scheme` WHERE `receipt_no` = '$receipt_no' OR `receipt_no` = 'PR".$receipt_no."'")->result_array();

        if(count($receipts)>0){
            JSONResponse(['type'=>'error', 'msg'=>'Receipt No already in use.']);
        }
        
        
        if($amount_paid > $total_payable){
            JSONResponse(['type'=>'error', 'msg'=>'Advance Payment is not applicable in Closing Internal Payment transaction.']);
        }

        if(!$ips){
            JSONResponse(['type'=>'error', 'msg'=>'Please select at least One(1) Internal Payment to close!']);
        }

        $allowed_tender_types = ['Check', 'JV payment - Business Unit', 'JV payment - Subsidiary'];

        if(!in_array($tender_type, $allowed_tender_types)){
            JSONResponse(['type'=>'error', 'msg'=>'Please select a valid Tender Type!']);
        }

        if ($amount_paid == 0 || $bank_name == '' || $bank_name == '? undefined:undefined ?')
        {
            JSONResponse(['type'=>'error', 'msg'=>'Please fill the required fields!']);
        }

        if($tender_type == 'Check' && 
            (
               empty($account_no) || 
               empty($account_name) || 
               empty($ds_no) || 
               empty($check_date) || 
               empty($customer_name)
            )
        ){

            JSONResponse(['type'=>'error', 'msg'=>'Please fill the required fields!']); 
        }


        
        $store_name;
        $supp_doc = "";
        $gl_code = "";
        $pdc_status = "";

        $file_name =  $tenant_id . $timeStamp . '.pdf';
    
        try {

            $this->db->trans_start(); // Transaction function starts here!!!

            $store_code    = $this->app_model->tenant_storeCode($tenant_id);

            $store_details = $this->app_model->store_details(trim($store_code));
            $store         = (object)($store_details ? $store_details[0] : []);
            $store_name    = $store->store_name;

            $details_soa   = $this->app_model->details_soa($tenant_id);
            $detail        = (object)($details_soa ? $details_soa[0] : []);

            // Upload Attchament

            if ($tender_type == 'Check') {
                $targetPath    = getcwd() . '/assets/payment_docs/';
                $supp_doc      = save_uploaded_file($targetPath, 'deposit_slip', $tenant_id);
            }

            $pdf = new FPDF('p','mm','A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');

            $logoPath = getcwd() . '/assets/other_img/'. $store->logo;

            $pdf->cell(20, 20, $pdf->Image($logoPath, 100, $pdf->GetY(), 15), 0, 0, 'C', false);
            $pdf->ln();
            $pdf->setFont ('times','B',14);
            $pdf->cell(75, 6, " ", 0, 0, 'L');
            $pdf->cell(40, 10, strtoupper($store_name), 0, 0, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(35, 35, 35);
            $pdf->cell(35, 6, " ", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','',14);
            $pdf->cell(15, 0, " ", 0, 0, 'L');
            $pdf->cell(0, 10, $store->store_address, 0, 0, 'C');

            $pdf->ln();
            $pdf->ln();



            if($detail){
                $pdf->setFont('times','',10);
                $pdf->cell(30, 6, "Receipt No.", 0, 0, 'L');
                $pdf->cell(60, 6, $receipt_no, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "TIN No.", 0, 0, 'L');
                $pdf->cell(60, 6, $detail->tin, 1, 0, 'L');

                $pdf->ln();
                $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
                $pdf->cell(60, 6, $tenant_id, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Date", 0, 0, 'L');
                $pdf->cell(60, 6, $posting_date, 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
                $pdf->cell(60, 6, $trade_name, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Remarks", 0, 0, 'L');
                $pdf->cell(60, 6, $remarks, 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
                $pdf->cell(60, 6, $detail->corporate_name, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Total Payable", 0, 0, 'L');
                $pdf->cell(60, 6, number_format($total_payable, 2), 1, 0, 'L');
                $pdf->ln();
                $pdf->ln();
            }

            


            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store_name) , 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','B',16);
            $pdf->cell(0, 6, "Payment Receipt", 0, 0, 'C');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(30, 8, "Doc. Type", 0, 0, 'C');
            $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
            $pdf->cell(30, 8, "Description", 0, 0, 'C');
            $pdf->cell(30, 8, "Posting Date", 0, 0, 'C');
            $pdf->cell(30, 8, "Due Date", 0, 0, 'C');
            $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
            $pdf->setFont('times','',10);


            /*==============    NO CASH ON IP CLOSING      ====================
            if ($tender_type == 'Cash' || $tender_type == 'Bank to Bank') {
                $gl_code = '10.10.01.01.02';
            } 
            ================    NO CASH ON IP CLOSING      ===================*/

            if($tender_type == 'Check') {
                $gl_code = '10.10.01.01.02';

                /*==============    NO PDC ON IP CLOSING      ====================
                if ($check_date > $transaction_date) {
                    $gl_code = '10.10.01.03.07.01';
                    $pdc_status = 'PDC';
                } else {
                    $gl_code = '10.10.01.01.02';
                }
                ================    NO PDC ON IP CLOSING      ====================*/

            }

            if($tender_type == 'JV payment - Business Unit') {
                $gl_code = $this->app_model->bu_entry();
            }

            if($tender_type == 'JV payment - Subsidiary'){
                $gl_code = '10.10.01.03.11';
            }

            foreach ($ips as $IP) {
                $IP = (object) $IP;

                $IP->amount = str_replace(",", "", $IP->amount);

                if($tender_amount > 0){

                    $pdf->ln();
                    $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                    $pdf->cell(30, 8, $IP->doc_no, 0, 0, 'C');

                    if ($IP->gl_accountID == 4){
                        $pdf->cell(30, 8, "Basic-" . $trade_name, 0, 0, 'C');
                    }
                    elseif($IP->gl_accountID == 7){
                        $pdf->cell(30, 8, "Advance-" . $trade_name, 0, 0, 'C');
                    }
                    else{
                        $pdf->cell(30, 8, "Other-" . $trade_name, 0, 0, 'C');
                    }



                    $clearing_entry = array(
                        'posting_date'      =>  $posting_date,
                        'transaction_date'  =>  $this->_currentDate,
                        'document_type'     =>  'Payment',
                        'ref_no'            =>  $IP->ref_no,
                        'doc_no'            =>  $receipt_no,
                        'tenant_id'         =>  $tenant_id,
                        'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                        'company_code'      =>  $store->company_code,
                        'department_code'   =>  '01.04',
                        'debit'             =>  ($tender_amount >= $IP->amount ? $IP->amount : $tender_amount),
                        'bank_name'         =>  $bank_name,
                        'bank_code'         =>  $bank_code,
                        'prepared_by'       =>  $this->session->userdata('id')
                    );

                    $this->app_model->insert('subsidiary_ledger', $clearing_entry);
                    $this->app_model->insert('general_ledger', $clearing_entry);

                    $rr_entry = array(
                        'posting_date'      =>  $posting_date,
                        'transaction_date'  =>  $this->_currentDate,
                        'document_type'     =>  'Payment',
                        'ref_no'            =>  $IP->ref_no,
                        'doc_no'            =>  $receipt_no,
                        'tenant_id'         =>  $tenant_id,
                        'gl_accountID'      =>  29,
                        'company_code'      =>  $store->company_code,
                        'department_code'   =>  '01.04',
                        'credit'            =>  -1 * ($tender_amount >= $IP->amount ? $IP->amount : $tender_amount),
                        'bank_name'         =>  $bank_name,
                        'bank_code'         =>  $bank_code,
                        'status'            =>   $pdc_status,
                        'prepared_by'       =>  $this->session->userdata('id')
                    );

                    $this->app_model->insert('subsidiary_ledger', $rr_entry);
                    $this->app_model->insert('general_ledger', $rr_entry);

                    $tender_amount -= $IP->amount;

                    $pdf->cell(30, 8, $IP->posting_date, 0, 0, 'C');
                    $pdf->cell(30, 8, '', 0, 0, 'C');
                    $pdf->cell(30, 8, number_format($IP->amount, 2), 0, 0, 'R');
                }
            }


            /* =================== ADVANCE  PAYMEN NOT APPLICABLE FOR CLOSING INTERNAL PAYMENT =======
            if ($tender_amount >= 1)
            {
                $advance_sl_refNo = $this->app_model->generate_refNo();
                $advance_gl_refNo = $this->app_model->gl_refNo();

                $entry_CIB = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $this->_currentDate,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $advance_gl_refNo,
                    'doc_no'            =>  $receipt_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'debit'             =>  $tender_amount,
                    'bank_name'         =>  $bank_name,
                    'bank_code'         =>  $bank_code,
                    'prepared_by'       =>  $this->session->userdata('id')
                );
                $this->app_model->insert('general_ledger', $entry_CIB);
                $this->app_model->insert('subsidiary_ledger', $entry_CIB);


                $advance_unearned = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $this->_currentDate,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $advance_gl_refNo,
                    'doc_no'            =>  $receipt_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  -1 * $tender_amount,
                    'bank_name'         =>  $bank_name,
                    'bank_code'         =>  $bank_code,
                    'status'            =>   $pdc_status,
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $advance_unearned);
                $this->app_model->insert('subsidiary_ledger', $advance_unearned);


                $advance_ledger = array(
                    'posting_date'      =>    $posting_date,
                    'transaction_date'  =>    $this->_currentDate,
                    'document_type'     =>    'Advance Payment',
                    'ref_no'            =>    $advance_sl_refNo,
                    'doc_no'            =>    $receipt_no,
                    'tenant_id'         =>    $tenant_id,
                    'contract_no'       =>    $contract_no,
                    'description'       =>    'Advance Payment' . "-" . $trade_name,
                    'debit'             =>    $tender_amount,
                    'balance'           =>    $tender_amount
                );

                $this->app_model->insert('ledger', $advance_ledger);

                $reportData = array(
                    'tenant_id'     =>  $tenant_id,
                    'doc_no'        =>  $receipt_no,
                    'posting_date'  =>  $posting_date,
                    'description'   =>  'Advance Payment',
                    'amount'        =>  $tender_amount
                );

                $this->app_model->insert('monthly_receivable_report', $reportData);
            }
            ====================== ADVANCE  PAYMEN NOT APPLICABLE FOR CLOSING INTERNAL PAYMENT  =======*/


            $pdf->ln();
            $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(150, 8, "Payment Scheme:", 0, 0, 'L');
            $pdf->cell(100, 8, "Payment Date:" . $posting_date, 0, 0, 'L');
            $pdf->ln();


            $pdf->setFont('times','',10);
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Tender Type: ", 0, 0, 'L');
            $pdf->cell(60, 4, $tender_type , 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
            $pdf->cell(60, 4, "P " . number_format($total_payable, 2), 0, 0, 'L');
            $pdf->ln();

            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
            $pdf->cell(60, 4, $bank_name, 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
            $pdf->cell(60, 4, "P " . number_format($amount_paid, 2), 0, 0, 'L');
            $pdf->ln();

            $balance = $total_payable > $amount_paid ? $total_payable - $amount_paid : 0;
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
            $pdf->cell(60, 4, $ds_no, 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
            $pdf->cell(60, 4, "P " . number_format($balance, 2), 0, 0, 'L');
            $pdf->ln();

            $advance = $amount_paid > $total_payable ? $amount_paid - $total_payable : 0;
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
            $pdf->cell(60, 4, $check_date, 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
            $pdf->cell(60, 4, "P " . number_format($advance, 2), 0, 0, 'L');

            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
            $pdf->cell(60, 4, $trade_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
            $pdf->cell(60, 4, $store_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "OR #: ", 0, 0, 'L');
            $pdf->cell(60, 4, $receipt_no, 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','',10);
            $pdf->cell(0, 4, "Prepared By: _____________________      Check By:______________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');


            $paymentScheme = array(
                'tenant_id'        =>   $tenant_id,
                'contract_no'      =>   $contract_no,
                'tenancy_type'     =>   $tenancy_type,
                'receipt_no'       =>   $receipt_no,
                'tender_typeCode'  =>   $tender_type,
                'tender_typeDesc'  =>   $tender_type,
                'soa_no'           =>   $this->app_model->get_latestSOANo($tenant_id),
                'amount_due'       =>   $total_payable,
                'amount_paid'      =>   $amount_paid,
                'bank'             =>   $bank_name,
                'check_date'       =>   $check_date,
                'payor'            =>   $trade_name,
                'payee'            =>   $store_name,
                'check_no'         =>   $ds_no,
                'supp_doc'         =>   $supp_doc,
                'receipt_doc'      =>   $file_name
            );


            $paymentSuppDocs = array(
                'tenant_id'  => $tenant_id,
                'receipt_no' => $receipt_no,
                'file_name'  => $supp_doc
            );


            $reverse_internalPaymentData = array(
                'tenant_id'    => $tenant_id,
                'posting_date' => $posting_date,
                'amount'       => $amount_paid,
                'doc_no'       => $receipt_no,
                'bank_code'    => $bank_code
            );

            $this->app_model->insert('reverse_internalPayment', $reverse_internalPaymentData);
            $this->app_model->insert('payment_supportingdocs', $paymentSuppDocs);
            $this->app_model->insert('payment_scheme', $paymentScheme);




            // Save data to Check Clearing & Monitoring System
            /*======================   CCM DATA =================================== */

            if ($tender_type == 'Check') 
            {
                $customer_id = $this->ccm_model->check_customer($customer_name);
                $checksreceivingtransaction_id = $this->ccm_model->checksreceivingtransaction();


                $ccm_data = array(
                    'checksreceivingtransaction_id' => $checksreceivingtransaction_id, 
                    'customer_id'                   => $customer_id,
                    'businessunit_id'               => $this->ccm_model->get_BU(),
                    'department_from'               => '12',
                    'leasing_docno'                 => $receipt_no,
                    'check_no'                      => $ds_no,
                    'check_class'                   => $check_class,
                    'check_category'                => $check_category,
                    'check_expiry'                  => $expiry_date,
                    'check_date'                    => $check_date,
                    'check_received'                => $this->_currentDate,
                    'check_type'                    => "",
                    'account_no'                    => $account_no,
                    'account_name'                  => $account_name,
                    'bank_id'                       => $check_bank,
                    'check_amount'                  => $tender_amount,
                    'currency_id'                   => '1',
                    'check_status'                  => 'PENDING'

                );

                $this->ccm_model->insert('checks', $ccm_data);
            }

            /*========================   CCM DATA ===================================*/


            $this->db->trans_complete(); 
            // End of transaction function

        }
        catch (Exception $e)
        {

            $this->db->trans_rollback(); // If failed rollback all queries
            $error = array('action' => 'IP Closing', 'error_msg' => $e->getMessage()); //Log error message to `error_log` table
            $this->app_model->insert('error_log', $error);
            
            JSONResponse(['type'=>'error', 'msg'=>$e->getMessage()]);
        }

        $file_dir = base_url() . 'assets/pdf/' . $file_name;
        $pdf->Output('assets/pdf/' . $file_name , 'F');

        JSONResponse(['type'=>'success', 'msg'=>'Transaction Complete', 'file_dir'=>$file_dir]);
    }

    public function save_reg_fundTransfer()
    {
        if (!$this->session->userdata('leasing_logged_in'))
            JSONResponse(['type'=>'error', 'msg'=>'Unauthorize Access!']);

    
        $response         = array();
        $date             = new DateTime();
        $timeStamp        = $date->getTimestamp();

        $tenant_id        = $this->sanitize($this->input->post('tenant_id'));
        $contract_no      = $this->sanitize($this->input->post('contract_no'));
        $tenancy_type     = $this->sanitize($this->input->post('tenancy_type'));

        $bank_code        = $this->sanitize($this->input->post('bank_code'));
        $bank_name        = $this->sanitize($this->input->post('bank_name'));
        $receipt_no       = "PR" . $this->sanitize($this->input->post('receipt_no'));

        $ds_no            = $this->input->post('ds_no');

        $trade_name       = $this->sanitize($this->input->post('trade_name'));
        $amount_paid      = str_replace(",", "", $this->sanitize($this->input->post('amount_paid')));
        $tender_amount    = $amount_paid;

        $posting_date = $this->sanitize($this->input->post('posting_date'));

        $ufts             = $this->input->post('ufts');
        
        $total_payable    = str_replace(",", "", $this->sanitize($this->input->post('total_payable')));

        $remarks          = "";


        $daysOfMonth      = date('t', strtotime($this->_currentDate));
        $store_name;


        $file_name =  $tenant_id . $timeStamp . '.pdf';

        if ($amount_paid == 0 || $bank_name == '' || $bank_name == '? undefined:undefined ?')
        { 
            JSONResponse(['type'=>'error', 'msg'=>'Please fill all the required fields!']);
        }


    
        try {

            $this->db->trans_start(); // Transaction function starts here!!!
            $store_code    = $this->app_model->tenant_storeCode($tenant_id);

            $store_details = $this->app_model->store_details(trim($store_code));
            $store         = (object)($store_details ? $store_details[0] : []);
            $store_name    = $store->store_name;

            $details_soa   = $this->app_model->details_soa($tenant_id);
            $detail        = (object)($details_soa ? $details_soa[0] : []);

            // Upload Deposit Slip

            $targetPath    = getcwd() . '/assets/payment_docs/';
            $supp_doc      =  save_uploaded_file($targetPath, 'deposit_slip', $tenant_id);

            $pdf = new FPDF('p','mm','A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');

            $logoPath = getcwd() . '/assets/other_img/'. $store->logo;

            $pdf->cell(20, 20, $pdf->Image($logoPath, 100, $pdf->GetY(), 15), 0, 0, 'C', false);
            $pdf->ln();
            $pdf->setFont ('times','B',14);
            $pdf->cell(75, 6, " ", 0, 0, 'L');
            $pdf->cell(40, 10, strtoupper($store_name), 0, 0, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(35, 35, 35);
            $pdf->cell(35, 6, " ", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','',14);
            $pdf->cell(15, 0, " ", 0, 0, 'L');
            $pdf->cell(0, 10, $store->store_address, 0, 0, 'C');

            $pdf->ln();
            $pdf->ln();

            if($detail){
                $pdf->setFont('times','',10);
                $pdf->cell(30, 6, "Receipt No.", 0, 0, 'L');
                $pdf->cell(60, 6, $receipt_no, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "TIN No.", 0, 0, 'L');
                $pdf->cell(60, 6, $detail->tin, 1, 0, 'L');

                $pdf->ln();
                $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
                $pdf->cell(60, 6, $tenant_id, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Date", 0, 0, 'L');
                $pdf->cell(60, 6, $posting_date, 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
                $pdf->cell(60, 6, $trade_name, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Remarks", 0, 0, 'L');
                $pdf->cell(60, 6, $remarks, 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
                $pdf->cell(60, 6, $detail->corporate_name, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Total Payable", 0, 0, 'L');
                $pdf->cell(60, 6, number_format($total_payable, 2), 1, 0, 'L');
                $pdf->ln();
                $pdf->ln();
            }


            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store_name) , 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','B',16);
            $pdf->cell(0, 6, "Payment Receipt", 0, 0, 'C');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(30, 8, "Doc. Type", 0, 0, 'C');
            $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
            $pdf->cell(30, 8, "Description", 0, 0, 'C');
            $pdf->cell(30, 8, "Posting Date", 0, 0, 'C');
            $pdf->cell(30, 8, "Due Date", 0, 0, 'C');
            $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
            $pdf->setFont('times','',10);


            foreach ($ufts as $key => $uft) {
                $uft = (object) $uft;

                $uft->amount = str_replace(",", "", $uft->amount);

                if($tender_amount > 0){

                    $pdf->ln();
                    $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                    $pdf->cell(30, 8, $uft->doc_no, 0, 0, 'C');

                    if ($uft->gl_accountID == 4){
                        $pdf->cell(30, 8, "Basic-" . $trade_name, 0, 0, 'C');
                    }
                    elseif($uft->gl_accountID == 7){
                        $pdf->cell(30, 8, "Advance-" . $trade_name, 0, 0, 'C');
                    }
                    else{
                        $pdf->cell(30, 8, "Other-" . $trade_name, 0, 0, 'C');
                    }

                    $clearing_entry = array(
                        'posting_date'      =>  $posting_date,
                        'transaction_date'  =>  $this->_currentDate,
                        'document_type'     =>  'Payment',
                        'ref_no'            =>  $uft->ref_no,
                        'doc_no'            =>  $receipt_no,
                        'tenant_id'         =>  $tenant_id,
                        'gl_accountID'      =>  $uft->gl_accountID,
                        'company_code'      =>  $store->company_code,
                        'department_code'   =>  '01.04',
                        'debit'             =>  ($tender_amount >= $uft->amount ? $uft->amount : $tender_amount),
                        'bank_name'         =>  $bank_name,
                        'bank_code'         =>  $bank_code,
                        'prepared_by'       =>  $this->session->userdata('id'),
                        'status'            =>  $uft->status,
                        'ft_ref'            =>  $uft->ft_ref,
                    );

                    $this->app_model->insert('subsidiary_ledger', $clearing_entry);

                    $rr_entry = array(
                        'posting_date'      =>  $posting_date,
                        'transaction_date'  =>  $this->_currentDate,
                        'document_type'     =>  'Payment',
                        'ref_no'            =>  $uft->ref_no,
                        'doc_no'            =>  $receipt_no,
                        'tenant_id'         =>  $tenant_id,
                        'gl_accountID'      =>  $uft->gl_accountID,
                        'company_code'      =>  $store->company_code,
                        'department_code'   =>  '01.04',
                        'credit'            =>  -1 * ($tender_amount >= $uft->amount ? $uft->amount : $tender_amount),
                        'bank_name'         =>  $bank_name,
                        'bank_code'         =>  $bank_code,
                        'prepared_by'       =>  $this->session->userdata('id'),
                        'ft_ref'            =>  $uft->ft_ref
                    );

                    $this->app_model->insert('subsidiary_ledger', $rr_entry);


                    $tender_amount -= $uft->amount;

                    $pdf->cell(30, 8, $uft->posting_date, 0, 0, 'C');
                    $pdf->cell(30, 8, $uft->due_date, 0, 0, 'C');
                    $pdf->cell(30, 8, number_format($uft->amount, 2), 0, 0, 'R');

                }
            }


            if ($tender_amount >= 1)
            {
                $advance_sl_refNo = $this->app_model->generate_refNo();
                $advance_gl_refNo = $this->app_model->gl_refNo();

                $entry_CIB = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $this->_currentDate,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $advance_gl_refNo,
                    'doc_no'            =>  $receipt_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'debit'             =>  $tender_amount,
                    'bank_name'         =>  $bank_name,
                    'bank_code'         =>  $bank_code,
                    'prepared_by'       =>  $this->session->userdata('id')
                );
                $this->app_model->insert('general_ledger', $entry_CIB);
                $this->app_model->insert('subsidiary_ledger', $entry_CIB);


                $advance_unearned = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $this->_currentDate,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $advance_gl_refNo,
                    'doc_no'            =>  $receipt_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  -1 * $tender_amount,
                    'bank_name'         =>  $bank_name,
                    'bank_code'         =>  $bank_code,
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $advance_unearned);
                $this->app_model->insert('subsidiary_ledger', $advance_unearned);


                $advance_ledger = array(
                    'posting_date'      =>    $posting_date,
                    'transaction_date'  =>    $this->_currentDate,
                    'document_type'     =>    'Advance Payment',
                    'ref_no'            =>    $advance_sl_refNo,
                    'doc_no'            =>    $receipt_no,
                    'tenant_id'         =>    $tenant_id,
                    'contract_no'       =>    $contract_no,
                    'description'       =>    'Advance Payment' . "-" . $trade_name,
                    'debit'             =>    $tender_amount,
                    'balance'           =>    $tender_amount
                );

                $this->app_model->insert('ledger', $advance_ledger);

                $reportData = array(
                    'tenant_id'     =>  $tenant_id,
                    'doc_no'        =>  $receipt_no,
                    'posting_date'  =>  $posting_date,
                    'description'   =>  'Advance Payment',
                    'amount'        =>  $tender_amount
                );

                $this->app_model->insert('monthly_receivable_report', $reportData);

            }


            $pdf->ln();
            $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(150, 8, "Payment Scheme:", 0, 0, 'L');
            $pdf->cell(100, 8, "Payment Date:" . $posting_date, 0, 0, 'L');
            $pdf->ln();


            $pdf->setFont('times','',10);
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Description: ", 0, 0, 'L');
            $pdf->cell(60, 4, 'Bank to Bank', 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
            $pdf->cell(60, 4, "P " . number_format($total_payable, 2), 0, 0, 'L');
            $pdf->ln();

            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
            $pdf->cell(60, 4, $bank_name, 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
            $pdf->cell(60, 4, "P " . number_format($amount_paid, 2), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
            $pdf->cell(60, 4, '', 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
            if($amount_paid - $total_payable >= 0)
            {
                $pdf->cell(60, 4, "P " . "0.00", 0, 0, 'L');
            }
            else
            {
                $pdf->cell(60, 4, "P " . number_format(abs($amount_paid - $total_payable), 2), 0, 0, 'L');
            }
            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
            $pdf->cell(60, 4, '', 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
            $pdf->cell(60, 4, "P " . number_format(abs($amount_paid - $total_payable), 2), 0, 0, 'L');

            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
            $pdf->cell(60, 4, $trade_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
            $pdf->cell(60, 4, $store_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "OR #: ", 0, 0, 'L');
            $pdf->cell(60, 4, $receipt_no, 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','',10);
            $pdf->cell(0, 4, "Prepared By: _____________________      Check By:______________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');


            $paymentScheme = array(
                'tenant_id'        =>   $tenant_id,
                'contract_no'      =>   $contract_no,
                'tenancy_type'     =>   $tenancy_type,
                'receipt_no'       =>   $receipt_no,
                'tender_typeCode'  =>   '',
                'tender_typeDesc'  =>   'Bank to Bank',
                'soa_no'           =>   $this->app_model->get_latestSOANo($tenant_id),
                'amount_due'       =>   $total_payable,
                'amount_paid'      =>   $amount_paid,
                'bank'             =>   $bank_name,
                'check_date'       =>   '',
                'payor'            =>   $trade_name,
                'payee'            =>   $store_name,
                'check_no'         =>   $ds_no,
                'supp_doc'         =>   $supp_doc,
                'receipt_doc'      =>   $file_name
            );


            $paymentSuppDocs = array(
                'tenant_id'  => $tenant_id,
                'receipt_no' => $receipt_no,
                'file_name'  => $supp_doc
            );

            $this->app_model->insert('payment_supportingdocs', $paymentSuppDocs);
            $this->app_model->insert('payment_scheme', $paymentScheme);
            $this->db->trans_complete(); // End of transaction function

        }
        catch (Exception $e)
        {

            $this->db->trans_rollback(); // If failed rollback all queries
            $error = array('action' => 'RR Clearing', 'error_msg' => $e->getMessage()); //Log error message to `error_log` table
            $this->app_model->insert('error_log', $error);
             
            JSONResponse(['type'=>'error', 'msg'=>$e->getMessage()]);
        }

        $file_dir = base_url() . 'assets/pdf/' . $file_name;

        $pdf->Output('assets/pdf/' . $file_name , 'F');

        JSONResponse(['type'=>'success', 'msg'=>'Transaction Complete', 'file_dir'=>$file_dir]);
    }
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Leasing extends CI_Controller
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


        if(!$this->session->userdata('leasing_logged_in') && !$this->session->userdata('cfs_logged_in')){
            redirect('ctrl_leasing/');
        }


        //SET TO FALSE IF PANDEMIC "NO PENALTY RULE" ENDS  
        $this->DISABLE_PENALTY = TRUE;

    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    function test(){
        echo "Hello World";
    }

    public function invoicing()
    { 

        $data['doc_no']    = $this->app_model->get_docNo(false);
        $data['current_date']   = getCurrentDate();

        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/accounting/invoicing');
        $this->load->view('leasing/footer');
        
    }

    public function get_tenant_details()
    {
        $trade_name = $this->input->get('trade_name', TRUE);
        $tenancy_type = $this->input->get('tenancy_type', TRUE);

        $result     = $this->app_model->select_tradeName($trade_name, $tenancy_type);

        if(empty($result)){
            JSONResponse(null);
        }

        /* ==========  START MODIFICATIONS ==============*/
        $tenant = (object)$result[0];

        $tenant->discounts = $this->app_model->get_myDiscounts($tenant->primaryKey);


        JSONResponse($tenant);

    }

    public function invoicing_init_data()
    {
        $preop_charges = $this->app_model->get_preopCharges();
        $cons_materials = $this->app_model->get_constMat();

        JSONResponse(compact('preop_charges', 'cons_materials'));
    }

    public function selected_monthly_charges($tenant_id)
    {
        $tenant_id= $this->sanitize($tenant_id);

        $result    = $this->app_model->selected_monthly_charges($tenant_id);
        JSONResponse($result);
    }

    public function get_otherCharges($tenant_id)
    {
        $tenant_id= $this->sanitize($tenant_id);

        $result = $this->app_model->get_otherCharges($tenant_id);
        JSONResponse($result);
    }

    public function save_invoice(){

        $date             = new DateTime();
        $timeStamp        = $date->getTimestamp();


        $tenancy_type       = $this->sanitize($this->input->post('tenancy_type'));
        $trade_name         = $this->sanitize($this->input->post('trade_name'));
        $tenant_id          = $this->sanitize($this->input->post('tenant_id'));
        $contract_no        = $this->sanitize($this->input->post('contract_no'));

        $rental_type        = $this->sanitize($this->input->post('rental_type'));
        $transaction_date   = date('Y-m-d');
        $posting_date       = $this->sanitize($this->input->post('posting_date'));
        $due_date           = $this->sanitize($this->input->post('due_date'));


        $invoice_type       = $this->sanitize($this->input->post('invoice_type'));
        $invoices           = $this->input->post('invoices');

        $basic_override_token = $this->sanitize($this->input->post('basic_override_token'));

        $posting_date   = date('Y-m-d', strtotime($posting_date));
        $due_date       = date('Y-m-d', strtotime($due_date));
       
        if(!validDate($posting_date)) {
            JSONResponse(['type'=>'error', 'msg'=>'Invalid date format on posting date!']);
        }

        if(!validDate($due_date)) {
            JSONResponse(['type'=>'error', 'msg'=>'Invalid date format on due date!']);
        }

        if(!in_array($tenancy_type, ['Short Term Tenant', 'Long Term Tenant'])){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Tenancy Type!']);
        }

        if(!in_array($rental_type, ['Fixed', 'Percentage', 'Fixed Plus Percentage', 'Fixed/Percentage w/c Higher', 'Fixed/Percentage/Minimum w/c Higher']))
        {
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Rental Type!']);
        }

        if(!in_array($invoice_type, ['Basic', 'Basic Manual', 'Retro Rent', 'Pre Operation Charges', 'Contruction Materials', 'Other Charges'])){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Invoice Type!']);
        }

        if(empty($invoices)){
            JSONResponse(['type'=>'error', 'msg'=>'No invoices found!']);
        }

        $total = 0;

        foreach ($invoices as $key => $invoice) { 
            $total += (float)$invoice['actual_amount'];
        }

        if($total <= 0){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Invoice total!']);
        }

        try{
            $store_code = $this->app_model->tenant_storeCode($tenant_id);
            $store = $this->app_model->getStore($store_code);

            if (empty($store_code ) || empty($store)) {
                throw new Exception('Invalid Tenant');
            } 
        }catch(Exception $e){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Tenant! Tenant might be terminated.']);
        }


        //VALIDATE BASIC OVERRIDE TOKEN
        if($invoice_type == 'Basic Manual'){
            $session_key = $this->session->userdata('invoice_basic_override_token');

            if(empty($basic_override_token) || empty($session_key) || empty($session_key->token)){
                JSONResponse(['type'=>'error_token', 'msg'=>'Manager\'s key is required!']);
            }

            if($basic_override_token !== $session_key->token){
                JSONResponse(['type'=>'error_token', 'msg'=>'Manager\'s key mismatch!']);
            }


            $upload = new FileUpload;

            $supp_docs= $upload->validate('supp_docs', 'Supporting Document')
                ->required()
                ->multiple()
                ->get();

            if($upload->has_error()){
                JSONResponse(['type'=>'error', 'msg'=>$upload->get_errors('<br>')]);
            }

        }


        $doc_no = $this->app_model->get_docNo();
        $gl_refNo = $this->app_model->gl_refNo();

        if(!$this->DISABLE_PENALTY){
            $this->generateLatePaymentPenalty($tenant_id, $posting_date, $due_date);
        }

        

        if(in_array($invoice_type, ['Basic', 'Basic Manual', 'Retro Rent'])){

            $receivable = 0;
            $rent_income = 0;

            $subledger_data = [];
            $invoices_data = [];
            $monthly_rec_data = [];
            $vat = null;
            $cwt = null;

            $mr_basic_rent = 0;
            $mr_percentage_rent = 0;
            $mr_discount = 0;
            $mr_vat = 0;
            $mr_cwt = 0;


            foreach ($invoices as $key => $invoice) {

                $invoice = (object) $invoice;

                $receivable += (float)$invoice->actual_amount;

                $invoices_data[] = array(
                    'tenant_id'        =>   $tenant_id,
                    'trade_name'       =>   $trade_name,
                    'doc_no'           =>   $doc_no,
                    'posting_date'     =>   $posting_date,
                    'transaction_date' =>   $transaction_date,
                    'due_date'         =>   $due_date,
                    'store_code'       =>   $store_code,
                    'contract_no'      =>   $contract_no,
                    'charges_type'     =>   $invoice->charge_type,
                    'charges_code'     =>   $invoice->charge_code,
                    'description'      =>   $invoice->description,
                    'uom'              =>   $invoice->uom,
                    'unit_price'       =>   !empty($invoice->unit_price) ? $invoice->unit_price : 0,
                    'total_unit'       =>   $invoice->total_unit,
                    'expected_amt'     =>   $invoice->actual_amount,
                    'balance'          =>   $invoice->actual_amount,
                    'actual_amt'       =>   $invoice->actual_amount,
                    'total_gross'      =>   $invoice->charge_type == 'Percentage Rent' ? $invoice->unit_price : 0,
                    'days_in_month'    =>   $invoice->charge_type == 'Basic/Monthly Rental' ? $invoice->days_in_month : NULL,
                    'days_occupied'    =>   $invoice->charge_type == 'Basic/Monthly Rental' ? $invoice->days_occupied : NULL,
                    'tag'              =>   'Posted'
                );

                
                if($invoice->description != 'Vat Output' && $invoice->description != 'Creditable Witholding Tax'){
                    $rent_income += (float)$invoice->actual_amount;
                }


                //MONTHLY RECEIVABLE PERCENTAGE RENT
                if(in_array($invoice->description, ['Percentage Rent'])){
                    $mr_percentage_rent += (float)$invoice->actual_amount;
                }

                //MONTHLY RECEIVABLE BASIC RENT
                if(in_array($invoice->description, ['Rental Incrementation']) || in_array($invoice->charge_type, ['Basic/Monthly Rental'])){
                    $mr_basic_rent += (float)$invoice->actual_amount;
                }

                //MONTHLY RECEIVABLE BASIC DISCOUNT
                if($invoice->charge_type == 'Discount'){
                    $mr_discount += (float) abs($invoice->actual_amount);
                }


                if($invoice->description == 'Vat Output'){
                    $vat = $invoice;
                    $mr_vat += (float)abs($invoice->actual_amount);
                }

                if($invoice->description == 'Creditable Witholding Tax'){
                    $cwt = $invoice;
                    $mr_cwt += (float)abs($invoice->actual_amount);
                }
            }

            /* ======================== START MONTHLY RECEIVABLE RECORD =========================*/

                //BASIC RENT
                $monthly_rec_data[] = array(
                    'tenant_id'     =>  $tenant_id,
                    'doc_no'        =>  $doc_no,
                    'posting_date'  =>  $posting_date,
                    'description'   =>  'Basic Rent',
                    'amount'        =>  abs($mr_basic_rent)
                );

                //PERCENTAGE RENT
                if($mr_percentage_rent > 0){
                    $monthly_rec_data[] = array(
                        'tenant_id'     =>  $tenant_id,
                        'doc_no'        =>  $doc_no,
                        'posting_date'  =>  $posting_date,
                        'description'   =>  'Percentage Rent',
                        'amount'        =>  abs($mr_basic_rent)
                    );
                }

                //VAT
                if($mr_vat > 0){
                    $monthly_rec_data[] = array(
                        'tenant_id'     =>  $tenant_id,
                        'doc_no'        =>  $doc_no,
                        'posting_date'  =>  $posting_date,
                        'description'   =>  'VAT',
                        'amount'        =>  abs($mr_vat)
                    );
                }

                //CWT
                if($mr_cwt > 0){
                    $monthly_rec_data[] = array(
                        'tenant_id'     =>  $tenant_id,
                        'doc_no'        =>  $doc_no,
                        'posting_date'  =>  $posting_date,
                        'description'   =>  'WHT',
                        'amount'        =>  abs($mr_cwt)
                    );
                }

                $monthly_rec_data[] = array(
                    'tenant_id'     =>  $tenant_id,
                    'doc_no'        =>  $doc_no,
                    'posting_date'  =>  $posting_date,
                    'description'   =>  'Net Rental',
                    'amount'        =>  abs($receivable)
                );

            /*========================= END MONTHLY RECEIVABLE RECORD==========================*/



            //RENT RECEIVABLE SL DATA
            $subledger_data[] = array(
                'posting_date'      =>  $posting_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $gl_refNo,
                'doc_no'            =>  $doc_no,
                'due_date'          =>  $due_date,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                'company_code'      =>  $store->company_code,
                'department_code'   =>  '01.04',
                'debit'             =>  abs($receivable),
                'tag'               =>  $invoice_type == 'Retro Rent'? 'Retro Rent' : 'Basic Rent',
                'prepared_by'       =>  $this->session->userdata('id')
            );


            //RENT INCOME SL DATA
            $subledger_data[] = array(
                'posting_date'      =>  $posting_date,
                'transaction_date'  =>  $transaction_date,
                'due_date'          =>  $due_date,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $gl_refNo,
                'doc_no'            =>  $doc_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID('20.60.01'),
                'company_code'      =>  $store->company_code,
                'department_code'   =>  '01.04',
                'credit'            =>  -1 * abs($rent_income),
                'prepared_by'       =>  $this->session->userdata('id')
            );

            if(!empty($vat)){
                $subledger_data[] = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $transaction_date,
                    'due_date'          =>  $due_date,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refNo,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.01.14'),
                    'company_code'      =>  $store->company_code,
                    'department_code'   =>  '01.04',
                    'credit'            =>  -1 * abs($vat->actual_amount),
                    'prepared_by'       =>  $this->session->userdata('id')
                );
            }

            if(!empty($cwt)){
                $subledger_data[] =  array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $transaction_date,
                    'due_date'          =>  $due_date,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refNo,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.06.05'),
                    'company_code'      =>  $store->company_code,
                    'department_code'   =>  '01.04',
                    'debit'             =>  abs($cwt->actual_amount),
                    'prepared_by'       =>  $this->session->userdata('id')
                );
            }
                

            $ledger_data = array(
                'posting_date'      =>  $posting_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $this->app_model->generate_refNo(),
                'doc_no'            =>  $doc_no,
                'tenant_id'         =>  $tenant_id,
                'contract_no'       =>  $contract_no,
                'description'       =>  ($invoice_type == 'Retro Rent' ? 'Retro-':'Basic-'). $trade_name,
                'credit'            =>  abs($receivable),
                'debit'             =>  0,
                'balance'           =>  -1 * abs($receivable),
                'due_date'          =>  $due_date,
                'charges_type'      =>  ($invoice_type == 'Retro Rent' ? 'Retro':'Basic')
            );
            $this->db->trans_start();

            $this->db->insert_batch('invoicing', $invoices_data);
            $this->db->insert('ledger', $ledger_data);
            $this->db->insert_batch('monthly_receivable_report', $monthly_rec_data);

            foreach ($subledger_data as $key => $data) {
                $this->db->insert('general_ledger', $data);
                $this->db->insert('subsidiary_ledger', $data);
            }

            if($invoice_type == 'Basic Manual'){
                $session_key = $this->session->userdata('invoice_basic_override_token');

                $inv_over_data = [
                    'tenant_id'     => $tenant_id,
                    'doc_no'        => $doc_no,
                    'manager_id'    => $session_key->manager_id,
                    'override_by'   => $this->session->userdata('id'),
                    'invoice_type'  =>'Basic Rent',
                    'amount'        => abs($receivable)
                ];

                $this->db->insert('invoice_override', $inv_over_data);

                $inv_over_id = $this->db->insert_id();


                $targetPath  = getcwd() . '/assets/invoice_override_docs/';
                foreach ($supp_docs as $key => $supp) {

                    //Setup our new file path
                    $filename    = $tenant_id . time() . $supp['name'];
                    move_uploaded_file($supp['tmp_name'], $targetPath.$filename);

                    $supp_doc_data = [
                        'inv_over_id'   => $inv_over_id,
                        'tenant_id'     => $tenant_id, 
                        'doc_no'        => $doc_no,
                        'file_name'     => $filename,
                    ];

                    $this->db->insert('invoice_override_docs', $supp_doc_data);

                }
            }

            $this->db->trans_complete();


            if ($this->db->trans_status() === FALSE)
            {   
                $this->db->trans_rollback();
                JSONResponse(['type'=>'error', 'msg'=>'Something went wrong!']);
            }

            JSONResponse(['type'=>'success', 'msg'=>'Transaction complete!']);  
        }
        elseif($invoice_type == 'Pre Operation Charges'){

            $preop_data=[];
            $invoices_data = [];
            $monthly_rec_data = [];

            foreach ($invoices as $key => $invoice) {
                $invoice = (object) $invoice;

                $preop_data[] = array(
                    'tenant_id'     =>  $tenant_id,
                    'doc_no'        =>  $doc_no,
                    'description'   =>  $invoice->description,
                    'posting_date'  =>  $posting_date,
                    'due_date'      =>  $due_date,
                    'amount'        =>  abs($invoice->actual_amount),
                    'org_amount'    =>  abs($invoice->actual_amount),
                    'tag'           =>  'Posted'
                );

                // For Montly Receivable Report
                $monthly_rec_data[] = array(
                    'tenant_id'     =>  $tenant_id,
                    'doc_no'        =>  $doc_no,
                    'posting_date'  =>  $posting_date,
                    'description'   =>  $invoice->description,
                    'amount'        =>  abs($invoice->actual_amount)
                );

                $invoices_data[] = array(
                    'tenant_id'        =>   $tenant_id,
                    'trade_name'       =>   $trade_name,
                    'doc_no'           =>   $doc_no,
                    'posting_date'     =>   $posting_date,
                    'transaction_date' =>   $transaction_date,
                    'due_date'         =>   $due_date,
                    'store_code'       =>   $store_code,
                    'contract_no'      =>   $contract_no,
                    'charges_type'     =>   $invoice->charge_type,
                    'charges_code'     =>   $invoice->charge_code,
                    'description'      =>   $invoice->description,
                    'uom'              =>   $invoice->uom,
                    'unit_price'       =>   $invoice->unit_price,
                    'total_unit'       =>   $invoice->total_unit,
                    'expected_amt'     =>   abs($invoice->actual_amount),
                    'actual_amt'       =>   abs($invoice->actual_amount),
                    'balance'          =>   abs($invoice->actual_amount),
                    'tag'              =>   'Posted'
                );
            }

            $this->db->trans_start();
            
            $this->db->insert_batch('invoicing', $invoices_data);
            $this->db->insert_batch('tmp_preoperationcharges', $preop_data);
            $this->db->insert_batch('monthly_receivable_report', $monthly_rec_data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {   
                $this->db->trans_rollback();

                JSONResponse(['type'=>'error', 'msg'=>'Something went wrong!']);
            }
       
            JSONResponse(['type'=>'success', 'msg'=>'Transaction complete!']);
        }
        else{

            $invoices_data      = [];
            $sl_data            = [];
            $monthly_rec_data   = [];
            $ledger_data        = [];
            $ar_amount          = 0;

            foreach ($invoices as $key => $invoice) {
                $invoice = (object) $invoice;

                $ar_amount += (float)$invoice->actual_amount;

                // INVOICING DATA
                $invoices_data[] = array(
                    'tenant_id'        =>   $tenant_id,
                    'trade_name'       =>   $trade_name,
                    'doc_no'           =>   $doc_no,
                    'posting_date'     =>   $posting_date,
                    'transaction_date' =>   $transaction_date,
                    'due_date'         =>   $due_date,
                    'store_code'       =>   $store_code,
                    'contract_no'      =>   $contract_no,
                    'charges_type'     =>   $invoice->charge_type,
                    'charges_code'     =>   $invoice->charge_code,
                    'description'      =>   $invoice->description,
                    'uom'              =>   $invoice->uom,
                    'unit_price'       =>   $invoice->unit_price,
                    'prev_reading'     =>   $invoice->prev_reading,
                    'curr_reading'     =>   $invoice->curr_reading,
                    'total_unit'       =>   $invoice->total_unit,
                    'expected_amt'     =>   $invoice->actual_amount,
                    'actual_amt'       =>   $invoice->actual_amount,
                    'balance'          =>   $invoice->actual_amount,
                    'with_penalty'     =>   $invoice->with_penalty,
                    'tag'              =>   'Posted'
                );

                $ledger_data[] = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $transaction_date,
                    'document_type'     =>  'Invoice',
                    'doc_no'            =>  $doc_no,
                    'ref_no'            =>  $this->app_model->generate_refNo(),
                    'tenant_id'         =>  $tenant_id,
                    'contract_no'       =>  $contract_no,
                    'description'       =>  'Other-' . $trade_name . '-' . $invoice->description,
                    'credit'            =>  $invoice->description != 'Expanded Withholding Tax'? abs($invoice->actual_amount) : 0,
                    'debit'             =>  $invoice->description == 'Expanded Withholding Tax'? abs($invoice->actual_amount) : 0,
                    'balance'           =>  $invoice->description == 'Expanded Withholding Tax'? abs($invoice->actual_amount) : (-1 * abs($invoice->actual_amount)),
                    'due_date'          =>  $due_date,
                    'charges_type'      =>  'Other',
                    'flag'              =>  $invoice->description == 'Expanded Withholding Tax' ? 'EWT' : NULL,
                    'with_penalty'      =>  $invoice->with_penalty
                );

                // For Montly Receivable Report
                $monthly_rec_data[] = array(
                    'tenant_id'     =>  $tenant_id,
                    'doc_no'        =>  $doc_no,
                    'posting_date'  =>  $posting_date,
                    'description'   =>  $invoice->description,
                    'amount'        =>  abs($invoice->actual_amount)
                );

    
                if($invoice->description == 'Expanded Withholding Tax')
                {
                    $gl_code = "10.10.01.06.05";
                }
                elseif($invoice->description == 'Common Usage Charges')
                {
                    $gl_code = '20.80.01.08.03';
                }
                elseif($invoice->description == 'Electricity')
                {
                    $gl_code = '20.80.01.08.02';
                }
                elseif($invoice->description == 'Aircon')
                {
                    $gl_code = '20.80.01.08.04';
                }
                elseif($invoice->description == 'Late submission of Deposit Slip' || $invoice->description == 'Late Payment Penalty' || $invoice->description == 'Penalty')
                {
                    $gl_code = '20.80.01.08.01';
                }
                elseif($invoice->description == 'Chilled Water')
                {
                    $gl_code = '20.80.01.08.05';
                }
                elseif($invoice->description == 'Water')
                {
                    $gl_code = '20.80.01.08.08';
                }
                else
                {
                    $gl_code = '20.80.01.08.07';
                }

                $sl_data[] = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $transaction_date,
                    'due_date'          =>  $due_date,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refNo,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                    'company_code'      =>  $store->company_code,
                    'department_code'   =>  '01.04',
                    'debit'             =>  $invoice->description == 'Expanded Withholding Tax' ? abs($invoice->actual_amount) : null,
                    'credit'             => $invoice->description != 'Expanded Withholding Tax' ? (-1 * abs($invoice->actual_amount)) : null,
                    'tag'               =>  $invoice->description == 'Expanded Withholding Tax' ? 'Expanded' : null,
                    'with_penalty'      =>  $invoice->with_penalty,
                    'prepared_by'       =>  $this->session->userdata('id')
                );

            }

            $isAGCSubsidiary = $this->app_model->is_AGCSubsidiary($tenant_id);
            $ar_code = $isAGCSubsidiary ? '10.10.01.03.04' : '10.10.01.03.03';

            //AR ENTRY
            array_unshift($sl_data, [
                'posting_date'      =>  $posting_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $gl_refNo,
                'doc_no'            =>  $doc_no,
                'due_date'          =>  $due_date,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID($ar_code),
                'company_code'      =>  $this->session->userdata('company_code'),
                'department_code'   =>  '01.04',
                'debit'             =>  $ar_amount,
                'tag'               =>  'Other',  
                'prepared_by'       =>  $this->session->userdata('id')
            ]);

            $this->db->trans_start();

            $this->db->insert_batch('invoicing', $invoices_data);
            $this->db->insert_batch('ledger', $ledger_data);
            $this->db->insert_batch('monthly_receivable_report', $monthly_rec_data);

            foreach ($sl_data as $key => $data) {
                $this->db->insert('general_ledger', $data);
                $this->db->insert('subsidiary_ledger', $data);
            }

            $this->db->trans_complete();


            if ($this->db->trans_status() === FALSE)
            {   
                $this->db->trans_rollback();
                JSONResponse(['type'=>'error', 'msg'=>'Something went wrong!']);
            }

            JSONResponse(['type'=>'success', 'msg'=>'Transaction complete!']);  
        }
    }

    function generateLatePaymentPenalty($tenant_id="", $posting_date = '', $due_date=""){

        $tenant_id = $this->sanitize($tenant_id);
        $posting_date = $this->sanitize($posting_date);
        $due_date = $this->sanitize($due_date);

        if(!validDate($posting_date)){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Posting Date!']);
        }

        if(!validDate($due_date)){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Due Date!']);
        }

        $posting_date = date('Y-m-d', strtotime($posting_date));

        $tenant = $this->app_model->getTenantByTenantID($tenant_id);

        try{
            $store_code = $this->app_model->tenant_storeCode($tenant_id);
            $store = $this->app_model->getStore($store_code);

            if (empty($store_code ) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            } 
        }catch(Exception $e){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Tenant! Tenant might be terminated.']);
        }


        $transaction_date = date('Y-m-d');

        $penalty_latepayment = $this->app_model->get_latePaymentPenalty($tenant_id);

        if(!$penalty_latepayment) return;
        
        $invoices_data = [];
        $ledger_data = [];
        $sl_data = [];
        $monthly_rec_data = [];
        foreach ($penalty_latepayment as $penalty)
        {
            $penalty_docNo = $this->app_model->get_docNo();
            $invoices_data[] = array(
                'tenant_id'        =>   $tenant_id,
                'trade_name'       =>   $tenant->trade_name,
                'doc_no'           =>   $penalty_docNo,
                'posting_date'     =>   $posting_date,
                'transaction_date' =>   $transaction_date,
                'due_date'         =>   $due_date,
                'store_code'       =>   $store_code,
                'contract_no'      =>   $tenant->contract_no,
                'charges_type'     =>   'Other',
                'description'      =>   $penalty['description'],
                'expected_amt'     =>   $penalty['amount'],
                'actual_amt'       =>   $penalty['amount'],
                'balance'          =>   $penalty['amount'],
                'flag'             =>   'Penalty',
                'tag'              =>   'Posted',
                'with_penalty'     =>   'Yes'
            );

            $gl_penaltyRefNo = $this->app_model->gl_refNo();

            $ledger_data[] = array(
                'posting_date'      =>  $posting_date,
                'transaction_date'  =>  $penalty['posting_date'],
                'document_type'     =>  'Payment',
                'due_date'          =>  $due_date,
                'doc_no'            =>  $penalty_docNo,
                'charges_type'      =>  'Other',
                'ref_no'            =>  $this->app_model->generate_refNo(),
                'tenant_id'         =>  $tenant_id,
                'contract_no'       =>  $penalty['contract_no'],
                'description'       =>  'Other-' . $tenant->trade_name . '-Penalty',
                'credit'            =>  $penalty['amount'],
                'debit'             =>  0,
                'balance'           =>  -1 * round($penalty['amount'], 2),
                'flag'              =>  'Penalty',
                'with_penalty'     =>   'Yes'
            );

            $isAGCSubsidiary = $this->app_model->is_AGCSubsidiary($tenant_id);
            $ar_code = $isAGCSubsidiary ? '10.10.01.03.04' : '10.10.01.03.03';

            $sl_data[] = array(
                'posting_date'      =>  $posting_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $gl_penaltyRefNo,
                'doc_no'            =>  $penalty_docNo,
                'due_date'          =>  $due_date,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID($ar_code),
                'company_code'      =>  $store->company_code,
                'department_code'   =>  '01.04',
                'debit'             =>  round($penalty['amount'], 2),
                'tag'               =>  'Penalty',
                'prepared_by'       =>  $this->session->userdata('id')
            );


            $sl_data[] = array(
                'posting_date'      =>  $posting_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $gl_penaltyRefNo,
                'due_date'          =>  $due_date,
                'doc_no'            =>  $penalty_docNo,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.01'),
                'company_code'      =>  $this->session->userdata('company_code'),
                'department_code'   =>  '01.04',
                'credit'             =>  -1 * round($penalty['amount'], 2),
                'prepared_by'       =>  $this->session->userdata('id'),
                'with_penalty'     =>   'Yes'
            );

            // For Montly Receivable Report
            $monthly_rec_data[] = array(
                'tenant_id'     =>  $tenant_id,
                'doc_no'        =>  $penalty_docNo,
                'posting_date'  =>  $posting_date,
                'description'   =>  'Penalty',
                'amount'        =>  $penalty['amount']
            );
            //$this->app_model->insert('monthly_receivable_report', $reportData);
        }


        $this->db->trans_start();

        $this->db->insert_batch('invoicing', $invoices_data);
        $this->db->insert_batch('ledger', $ledger_data);
        $this->db->insert_batch('monthly_receivable_report', $monthly_rec_data);

        foreach ($sl_data as $key => $data) {
            $this->db->insert('general_ledger', $data);
            $this->db->insert('subsidiary_ledger', $data);
        }

        $this->db->trans_complete();

        if($result = $this->db->trans_status()){
            foreach ($penalty_latepayment as $penalty){
                //===== Update tmp_latepaymentpenalty that the penalty was already invoiced ===== //
                $this->app_model->update_tmp_latepaymentpenalty($penalty['id'], $penalty_docNo);
                $this->app_model->update_ledgerDueDate($tenant_id, $penalty['doc_no'], $due_date);
            }
        }


        return $result;
    }


    public function soa()
    { 
        $data['soa_no']          = $this->app_model->get_soaNo(false);
        $data['current_date']   = getCurrentDate();

        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/accounting/soa');
        $this->load->view('leasing/footer'); 
    }

    public function get_tenant_balances()
    {
        $tenant_id      = $this->sanitize($this->input->post('tenant_id'));
        $date_created   = $this->sanitize($this->input->post('date_created'));

        $date_created = date('Y-m-d', strtotime($date_created));

        $invoices = $this->app_model->getTenantBalances($tenant_id, $date_created);
        $preop_charges =$this->app_model->getTenantPreopBalances($tenant_id, $date_created);

        $data = array_merge($invoices, $preop_charges);

        $uris =  $this->app_model->getTenantUnearnedRentIncome($tenant_id, $date_created);

        $advance = 0;
        foreach ($uris as $key => $uri) {
            $advance+=$uri->balance;
        }

        JSONResponse(['docs'=>$data, 'advance'=>$advance]);
    }

    public function generate_soa()
    {
        $tenancy_type    = $this->input->post('tenancy_type');
        $trade_name      = $this->input->post('trade_name');
        $contract_no     = $this->input->post('contract_no');
        $tenant_id       = $this->sanitize($this->input->post('tenant_id'));
        $tenant_address  = $this->input->post('tenant_address');
        $billing_period  = $this->input->post('billing_period');
        $date_created    = $this->input->post('date_created');
        $date_created    = date('Y-m-d', strtotime($date_created));

        $collection_date = $this->input->post('collection_date');
        $totalAmount     = $this->input->post('totalAmount');
        $totalAmount     = str_replace(",", "", $totalAmount);
        $soa_docs        = $this->input->post('soa_docs');

        $soa_no          = $this->app_model->get_soaNo();
        $details_soa     = $this->app_model->details_soa($tenant_id);

        $transaction_date = date('Y-m-d');


        $this->db->trans_start();

        $tenant          = $this->app_model->getTenantByTenantID($tenant_id);

        $soa_display     = [
            'tenant'            => $tenant,
            'soa_no'            => $soa_no,
            'total_amount_due'  => 0,
            'billing_period'    => strtoupper($billing_period),
            'collection_date'   => date('F d, Y', strtotime($collection_date)),
            'date_created'      => date('F d, Y', strtotime($date_created)),
            'tenancy_type'      => ucwords($tenancy_type)
        ];

        try{
            $store_code             = $this->app_model->tenant_storeCode($tenant_id);
            $store                  = $this->app_model->getStore($store_code);
            $soa_display['store']   = $store;

            if (empty($store_code ) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            } 
        }
        catch(Exception $e)
        {
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Tenant! Tenant might be terminated.']);
        }



        $grouped_docs = array_group_by($soa_docs, function($doc){
            return $doc['document_type'];
        });

        // ====== FOR SHOWING THE ORIGINAL SUB TOTAL ====== ADDED 2021-09-06 //
        $debit_display = 0;

        foreach($soa_docs as $key => $value) 
        {
            $debit_display += $value['debit'];
        }

        $retro_rent  = !empty($grouped_docs['Retro'])  ? $grouped_docs['Retro'] : [];
        $preop_charges = !empty($grouped_docs['Preop-Charges'])  ? $grouped_docs['Preop-Charges'] : [];
        $invoices = !empty($grouped_docs['Invoice'])  ? $grouped_docs['Invoice'] : [];

        /*$date = date_create($date_created);
        date_sub($date,date_interval_create_from_date_string("15 days"));
        $soa_current_date   =  date_format($date,"Y-m");*/

        $soa_amount_due     = 0;        


        // FOR PRE-OPERATIONAL CHARGES
        if(!empty($preop_charges))
        {
            $soa_display['preop'] = [];

            $preop_total = 0;
            foreach($preop_charges as $key => $preop) 
            {
                $preop = (object)$preop;

                //dd($preop);

                $soa_display['preop'][$key]['description']  = $preop->description;
                $soa_display['preop'][$key]['amount']       = $preop->balance;
                $preop_total += $preop->balance;

                $this->db->insert('soa_line', [
                    'soa_no'    =>$soa_no,
                    'doc_no'    =>$preop->doc_no,
                    'amount'    =>$preop->balance,
                    'tenant_id' =>$tenant_id,
                    'preop_id'  =>$preop->id
                ]);

            }

            $soa_display['preop_total'] = $preop_total;
            $soa_amount_due += $preop_total;
        }

        // FOR RETRO RENT
        if(!empty($retro_rent))
        {
            $retro_total = 0;

            foreach($retro_rent as $key => $retro) {
                $retro = (object)$retro;

                $soa_display['retro'][$key]['debit']  = $retro->debit;
                $soa_display['retro'][$key]['credit'] = $retro->credit;
                $soa_display['retro'][$key]['balance'] = $retro->balance;
                $retro_total += $retro->balance;

                $this->db->insert('soa_line', [
                    'soa_no'    =>$soa_no,
                    'doc_no'    =>$retro->doc_no,
                    'amount'    =>$retro->balance,
                    'tenant_id' =>$tenant_id,
                ]);

            }
            $soa_display['retro_total'] = $retro_total;
            $soa_amount_due += $retro_total;
        }

        //GET THE LAST POSTING AND DUE DATE 
        if(!empty($invoices))
        {
           
            $last_due_date  = $invoices[0]['due_date'];
            $last_post_date = $invoices[0]['posting_date']; 
        
        
            foreach ($invoices as $key => $inv) {
                $inv = (object) $inv;
                $last_due_date  = strtotime($inv->due_date) > strtotime($last_due_date) ?  $inv->due_date : $last_due_date;
                $last_post_date = strtotime($inv->posting_date) > strtotime($last_post_date) ?  $inv->posting_date : $last_post_date;
            }

            
            /*//POSTING DATE BASE CURRENT DATE
            $curr_month = date_create($last_post_date);
            date_sub($curr_month, date_interval_create_from_date_string("15 days"));*/


            //DUE DATE BASE CURRENT DATE
            $curr_month = date_create($last_due_date);
            date_sub($curr_month, date_interval_create_from_date_string("20 days"));


            $curr_month = date_format($curr_month,"Y-m");
        }


        $grouped_invoices = array_group_by($invoices, function($doc)
        {
            $doc = (object) $doc;
            //POSTING DATE BASE GROUP
            //$date = date_create($doc->posting_date);
            //date_sub($date,date_interval_create_from_date_string("15 days"));

            //DUE DATE BASE GROUP
            $date = date_create($doc->due_date);
            date_sub($date,date_interval_create_from_date_string("20 days"));

            return date_format($date,"Y-m");
        });

        ksort($grouped_invoices);

        if(!empty($grouped_invoices))
        {
            $soa_display['previous'] = [];
            $penalties = [];

            foreach($grouped_invoices as $date => $gp_inv) 
            {    
                if($date != $curr_month)
                {
                    $soa_display['previous'][$date] = [];

                    $total_per_month_debit      = 0;
                    $total_per_month_credit     = 0;
                    $total_per_month_balance    = 0;
                    $total_per_month_payable    = 0;
                    $has_basic = false;

                    foreach ($gp_inv as $key => $inv) 
                    {
                        $inv = (object) $inv;
                        $total_per_month_debit      += $inv->debit;
                        $total_per_month_credit     += $inv->credit;
                        $total_per_month_balance    += $inv->balance;

                        if($inv->gl_accountID == 4)
                        {
                            $has_basic = true;
                        }

                        $this->db->insert('soa_line', [
                            'soa_no'    =>$soa_no,
                            'doc_no'    =>$inv->doc_no,
                            'amount'    =>$inv->balance,
                            'tenant_id' =>$tenant_id,
                        ]);
                    }

                    $soa_display['previous'][$date]['has_basic']    = $has_basic;
                    $soa_display['previous'][$date]['debit']        = $total_per_month_debit;
                    $soa_display['previous'][$date]['credit']       = $total_per_month_credit;
                    $soa_display['previous'][$date]['balance']      = $total_per_month_balance;

                    $total_per_month_payable += $total_per_month_balance;

                    //========== START OF CALCULATING PENALTY HERE ================
                    if($tenant->penalty_exempt != 1 && !$this->DISABLE_PENALTY)
                    {

                        $penalty_grouped_invoices = array_group_by($gp_inv, function($inv) use ($last_due_date) {
                            $inv = (object) $inv;

                            $last_due       = date_create($last_due_date);
                            $due_date       = date_create($inv->due_date);
                            $diff           = date_diff($due_date, $last_due);
                            $diff           = (int) $diff->format("%R%a");

                            return floor($diff/20);
                        });

                        $soa_display['previous'][$date]['penalties'] = [];
                        $total_no_penalty       = 0;

                        foreach ($penalty_grouped_invoices as $penalty => $pen_inv) {
                            if($penalty == 0) continue;

                            $penalty_percentage     = $penalty >= 2 ? 3 : 2;
                            $total_penaltyble       = 0;
                            $total_penalty          = 0;
                            $penalty_due_date       = $last_due_date;   
                           

                            foreach ($pen_inv as $key => $inv) {
                                $inv = (object) $inv;

                                $penaltyble = $inv->balance - $inv->nopenalty_amount;
                                $total_penaltyble += ($penaltyble > 0 ? $penaltyble : 0);
                                $total_no_penalty += ($penaltyble > 0 ? $inv->nopenalty_amount : 0);

                                if($penaltyble > 0){
                                    $penalty_due_date   = $inv->due_date;
                                    $penalty_doc_no     = $inv->doc_no;
                                }
                               
                            }

                            //IF ZERO PENALTY SKIP HERE
                            if($total_penaltyble <= 0) continue;

                            $total_penalty = $total_penaltyble * ($penalty_percentage / 100);
                            $total_penalty = round($total_penalty, 2);
                            
                            $total_per_month_payable += $total_penalty;

                            $soa_display['previous'][$date]['penalties'][] = [
                                'penaltyble_amount'     => $total_penaltyble,
                                'penalty_percentage'    => $penalty_percentage,
                                'penalty_amount'        => $total_penalty
                            ];


                            //=================PUT PENALTY ENTRY HERE =====================

                            //INSERT PENALTY TO LEDGER TABLE
                            $this->app_model->insert('ledger', [
                                'posting_date'      =>  $date_created,
                                'document_type'     =>  'Penalty',
                                'ref_no'            =>  $this->app_model->generate_refNo(),
                                'due_date'          =>  $penalty_due_date,
                                'transaction_date'  =>  $transaction_date,
                                'doc_no'            =>  $soa_no,
                                'tenant_id'         =>  $tenant_id,
                                'contract_no'       =>  $contract_no,
                                'description'       =>  'Penalty-' . $trade_name,
                                'credit'            =>  $total_penalty,
                                'balance'           =>  -1 * $total_penalty,
                                'flag'              =>  'Penalty'
                            ]);

                            $sl_data = [];

                            $ref_no             = $this->app_model->gl_refNo();
                            $isAGCSubsidiary    = $this->app_model->is_AGCSubsidiary($tenant_id);
                            $ar_code            = $isAGCSubsidiary ? '10.10.01.03.04' : '10.10.01.03.03';

                            $penalties[] = [
                                'doc_no'            =>  $soa_no,
                                'ref_no'            =>  $ref_no,
                                'gl_accountID'      =>  $this->app_model->gl_accountID($ar_code),
                                'balance'            =>  $total_penalty,
                            ];

                            $sl_data['ar_entry'] = [
                                'posting_date'      =>  $date_created,
                                'transaction_date'  =>  $transaction_date,
                                'due_date'          =>  $penalty_due_date,
                                'document_type'     =>  'Invoice',
                                'ref_no'            =>  $ref_no,
                                'doc_no'            =>  $soa_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID($ar_code),
                                'company_code'      =>  $store->company_code,
                                'department_code'   =>  '01.04',
                                'debit'             =>  $total_penalty,
                                'tag'              =>  'Penalty',
                                'prepared_by'       =>  $this->session->userdata('id')
                            ];

                            $sl_data['penalty_entry'] = [
                                'posting_date'      =>  $date_created,
                                'transaction_date'  =>  $transaction_date,
                                'due_date'          =>  $penalty_due_date,
                                'document_type'     =>  'Invoice',
                                'ref_no'            =>  $ref_no,
                                'doc_no'            =>  $soa_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.01'),
                                'company_code'      =>  $store->company_code,
                                'department_code'   =>  '01.04',
                                'credit'            =>   -1 * $total_penalty,
                                'prepared_by'       =>  $this->session->userdata('id')
                            ];

                            foreach ($sl_data as $key => $data) {
                                $this->db->insert('subsidiary_ledger', $data);
                                $this->db->insert('general_ledger', $data);
                                
                            }

                            // ============ Insert Into monthly_penalty table ============ //
                            $this->app_model->insert('monthly_penalty', [
                                'tenant_id'         =>      $tenant_id,
                                'percent'           =>      $penalty_percentage,
                                'due_date'          =>      $penalty_due_date,
                                'doc_no'            =>      $penalty_doc_no,
                                'collection_date'   =>      $collection_date,
                                'soa_no'            =>      $soa_no,
                                'amount'            =>      $total_penalty,
                                'balance'           =>      $total_penalty
                            ]);


                            $this->app_model->insert('monthly_receivable_report', [
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $soa_no,
                                'posting_date'  =>  $date_created,
                                'description'   =>  'Penalty',
                                'amount'        =>  $total_penalty
                            ]);


                            $this->db->insert('invoicing', [
                                'tenant_id'        =>   $tenant_id,
                                'trade_name'       =>   $tenant->trade_name,
                                'doc_no'           =>   $soa_no,
                                'posting_date'     =>   $date_created,
                                'transaction_date' =>   $transaction_date,
                                'due_date'         =>   $penalty_due_date,
                                'store_code'       =>   $store_code,
                                'contract_no'      =>   $tenant->contract_no,
                                'charges_type'     =>   'Other',
                                'description'      =>   'Penalty',
                                'expected_amt'     =>   $total_penalty,
                                'actual_amt'       =>   $total_penalty,
                                'balance'          =>   $total_penalty,
                                'flag'             =>   'Penalty',
                                'tag'              =>   'Posted',
                                'with_penalty'     =>   'Yes'
                            ]);

                            $this->db->insert('soa_line', [
                                'soa_no'    =>$soa_no,
                                'doc_no'    =>$soa_no,
                                'amount'    =>$total_penalty,
                                'tenant_id' =>$tenant_id,
                            ]);

                        }

                        $soa_display['previous'][$date]['no_penalty']   = $total_no_penalty;
                        //========== END OF CALCULATING PENALTY HERE =====================
                    }

                    $soa_display['previous'][$date]['total']        = $total_per_month_payable;
                    $soa_amount_due += $total_per_month_payable;  
                }
                else
                {
                    $soa_display['current'] = ['date'=>$date];
                    $current_total = 0;

                    $type_grouped_invoices = array_group_by($gp_inv, function($inv)
                    {
                        $inv = (object) $inv;
                        return ($inv->gl_accountID == 4 ? 'basic' : 'others');
                    });

                    //CURRENT BASIC
                    $soa_display['current']['basic'] = [];
                    if(!empty($type_grouped_invoices['basic']))
                    {
                        $basic_sub_total = 0;
                        $basic_adj_amount = 0;
                        $basic_paid_amount = 0;

                        foreach ($type_grouped_invoices['basic'] as $key => $inv) 
                        {
                            $inv = (object) $inv;

                            $invoicing = $this->app_model->getInvoicingData($tenant_id, $inv->doc_no);

                            $soa_display['current']['basic'][$inv->doc_no]['invoices']      = [];
                            $soa_display['current']['basic'][$inv->doc_no]['adj_amount']    = $inv->adj_amount;
                            $soa_display['current']['basic'][$inv->doc_no]['total']         = $inv->balance;
                            $current_total += $inv->balance;
                            $basic_sub_total += $inv->balance;
                            $basic_adj_amount += $inv->adj_amount;
                            $basic_paid_amount += $inv->credit;


                            foreach ($invoicing as $key => $invoice) 
                            {
                                $details = '';
                                if($invoice->charges_type == 'Basic/Monthly Rental')
                                {
                                    $description = 'Basic Rent';
                                    
                                    if($invoice->total_unit != 1 && is_numeric($invoice->days_in_month) && is_numeric($invoice->days_occupied))
                                    {
                                        $details = "(".number_format($invoice->unit_price, 2) . 
                                            " x $invoice->days_occupied"."/$invoice->days_in_month"." days)";
                                    }
                                }
                                elseif($invoice->charges_type == 'Discount')
                                {
                                    $description = 'Discount/'.$invoice->description;
                                }
                                else
                                {
                                    $description = $invoice->description;

                                    if($description == 'Rental Incrementation' || $description == 'Percentage Rent')
                                    {
                                        $details = "(".number_format($invoice->unit_price, 2)." x $invoice->total_unit%)";
                                    } 
                                }

                                if($invoice->charges_type == 'Discount' 
                                    || $invoice->description == 'Creditable Witholding Tax' 
                                    || $invoice->description == 'Creditable Witholding Taxes')
                                {
                                    $amount = abs($invoice->actual_amt) * -1;
                                }
                                elseif($invoice->charges_type == 'Basic/Monthly Rental')
                                {
                                    $amount = abs($invoice->expected_amt);
                                }
                                else
                                {
                                    $amount = abs($invoice->actual_amt);
                                }   

                                $soa_display['current']['basic'][$inv->doc_no]['invoices'][] = [
                                    'description'       => $description,
                                    'amount'            => $amount,
                                    'unit_price'        => $invoice->unit_price,
                                    'total_unit'        => $invoice->total_unit,
                                    'details'           => $details,
                                ];
                            }


                            if(abs($inv->adj_amount) > 0)
                            {
                                $invoice_adjustments = $this->app_model->get_adj_for_soa_display($tenant_id, $inv->doc_no);
                                $soa_display['current']['basic'][$inv->doc_no]['adj_details'] = $invoice_adjustments;
                            }

                            $soa_display['current']['basic_adj_amount']    = $basic_adj_amount;
                            $soa_display['current']['basic_sub_total']     = $basic_sub_total;
                            $soa_display['current']['basic_paid_amount']   = $basic_paid_amount;
                            


                            $this->db->insert('soa_line', [
                                'soa_no'    =>$soa_no,
                                'doc_no'    =>$inv->doc_no,
                                'amount'    =>$inv->balance,
                                'tenant_id' =>$tenant_id,
                            ]); 
                        }
                    }

                    //CURRENT OTHER CHARGES
                    $soa_display['current']['others'] = [];
                    if(!empty($type_grouped_invoices['others'])){
                       
                        $other_sub_total    = 0;
                        $other_adj_amount   = 0;
                        $other_paid_amount  = 0;
                        $total_ewt = 0;

                            

                        foreach ($type_grouped_invoices['others'] as $key => $inv) {
                            $inv = (object) $inv;

                            $invoicing = $this->app_model->getInvoicingData($tenant_id, $inv->doc_no);

                            $soa_display['current']['others'][$inv->doc_no]['invoices']      = [];
                            $soa_display['current']['others'][$inv->doc_no]['adj_amount']    = $inv->adj_amount;
                            $soa_display['current']['others'][$inv->doc_no]['total']         = $inv->balance;
                            $current_total      += $inv->balance;
                            $other_sub_total    += $inv->balance;
                            $other_adj_amount   += $inv->adj_amount;
                            $other_paid_amount  += $inv->credit;

                            foreach ($invoicing as $key => $invoice) {

                                $amount = abs($invoice->actual_amt);
                                $amount = $invoice->description == 'Expanded Withholding Tax' ? ($amount * -1) : $amount;

                                // ICM POST OFFICE EXEMPTION MOTHERFUCKER
                                if ($tenant_id == 'ICM-LT000114' && $invoice->description == 'Expanded Withholding Tax')
                                {
                                    $total_ewt += $amount;
                                }
                                else
                                {   
                                    $total_unit = empty($invoice->total_unit) || $invoice->total_unit == 0 ? 
                                        ($invoice->curr_reading - $invoice->prev_reading) : 
                                        $invoice->total_unit;
                                    $soa_display['current']['others'][$inv->doc_no]['invoices'][] = [
                                        'description'       => $invoice->description,
                                        'amount'            => $amount,
                                        'prev_reading'      => $invoice->prev_reading,
                                        'curr_reading'      => $invoice->curr_reading,
                                        'unit_price'        => $invoice->unit_price,
                                        'total_unit'        => $total_unit,
                                    ];
                                }    
                            }

                            if(abs($inv->adj_amount) > 0){
                                $invoice_adjustments = $this->app_model->get_adj_for_soa_display($tenant_id, $inv->doc_no);
                                $soa_display['current']['others'][$inv->doc_no]['adj_details'] = $invoice_adjustments;
                            }

                            $soa_display['current']['other_adj_amount']    = $other_adj_amount;
                            $soa_display['current']['other_sub_total']     = $other_sub_total;
                            $soa_display['current']['other_paid_amount']   = $other_paid_amount;

                            $soa_display['current']['other_total_without_ewt']   = round($other_sub_total - $total_ewt,2);
                            $soa_display['current']['other_total_ewt']           = round($total_ewt,2);

                            $this->db->insert('soa_line', [
                                'soa_no'    =>$soa_no,
                                'doc_no'    =>$inv->doc_no,
                                'amount'    =>$inv->balance,
                                'tenant_id' =>$tenant_id,
                            ]);
                        }
                    }


                    $soa_display['current']['total'] =  $current_total;
                    $soa_amount_due += $current_total;
                }
                
            }

            /* =============  START APPLY ADVANCES  =========== */
            $uris = $this->app_model->getTenantUnearnedRentIncome($tenant_id, $date_created);

            $total_uri_amount = 0;
            $total_uri_amount_paid = 0;

            foreach ($uris as $key => $uri) {
                $total_uri_amount+=$uri->balance;
            }


            //COMMENT THIS IF APPLIED FR0M OLDEST TO NEWEST
            //$grouped_invoices = array_reverse($grouped_invoices); 

            foreach($grouped_invoices as $date => $gp_inv) {
                foreach ($gp_inv as $key => $inv) {

                    $inv = (object) $inv;

                    foreach ($uris as $key => $uri) {

                        if($inv->balance <= 0) break;
                        if($uri->balance <= 0) continue;

                        if($uri->balance >= $inv->balance ){
                            $uri_amount = $inv->balance;
                            $uri->balance -= $inv->balance;
                            $inv->balance -= $inv->balance;
                        }else{
                            $uri_amount = $uri->balance;
                            $inv->balance -= $uri->balance;
                            $uri->balance -= $uri->balance;
                        }

                        $total_uri_amount_paid += $uri_amount;
                       
                        $sl_data = [];
                        $ft_ref = $this->app_model->generate_ClosingRefNo();

                        $sl_data['uri_entry'] = array(
                            'posting_date'      =>  $date_created,
                            'transaction_date'  =>  $transaction_date,
                            'document_type'     =>  'Payment',
                            'ref_no'            =>  $uri->ref_no,
                            'doc_no'            =>  $soa_no,
                            'tenant_id'         =>  $tenant_id,
                            'gl_accountID'      =>  $uri->gl_accountID,
                            'company_code'      =>  $store->company_code,
                            'department_code'   =>  '01.04',
                            'debit'             =>  $uri_amount,
                            'ft_ref'            =>  $ft_ref,
                            'prepared_by'       =>  $this->session->userdata('id')
                        );
                        

                        $sl_data['rec_entry'] = array(
                            'posting_date'      => $date_created,
                            'transaction_date'  => $transaction_date,
                            'document_type'     => 'Payment',
                            'ref_no'            => $inv->ref_no,
                            'doc_no'            => $soa_no,
                            'tenant_id'         => $tenant_id,
                            'gl_accountID'      => $inv->gl_accountID,
                            'company_code'      => $store->company_code,
                            'department_code'   => '01.04',
                            'credit'            => -1 * $uri_amount,
                            'ft_ref'            =>  $ft_ref,
                            'prepared_by'       => $this->session->userdata('id')
                        );

                        foreach ($sl_data as $key => $data) {
                            $this->db->insert('subsidiary_ledger', $data);
                            $this->db->insert('general_ledger', $data);
                            
                        }

                        //START OF ledger table entry
                        $lgr_inv = $this->app_model->getLedgerFirstResultByDocNo($tenant_id, $inv->doc_no);
                        if(!empty($lgr_inv)){

                            $ledger_data = array(
                                'posting_date'  =>  $date_created,
                                'transaction_date' =>$transaction_date,
                                'document_type' =>  'SOA',
                                'ref_no'        =>  $lgr_inv->ref_no,
                                'doc_no'        =>  $soa_no,
                                'tenant_id'     =>  $tenant_id,
                                'contract_no'   =>  $contract_no,
                                'description'   =>  $lgr_inv->description,
                                'debit'         =>  $uri_amount,
                                'balance'       =>  0
                            );
                            

                            $this->app_model->insert('ledger', $ledger_data);
                        }

                        $lgr_uri = $this->app_model->getLedgerFirstResultByDocNo($tenant_id, $uri->doc_no);
                        if(!empty($lgr_uri)){

                            $ledger_data = array(
                                'posting_date'  =>  $date_created,
                                'transaction_date' =>$transaction_date,
                                'document_type' =>  'SOA',
                                'ref_no'        =>  $lgr_uri->ref_no,
                                'doc_no'        =>  $soa_no,
                                'tenant_id'     =>  $tenant_id,
                                'contract_no'   =>  $contract_no,
                                'description'   =>  $lgr_uri->description,
                                'credit'        =>  $uri_amount,
                                'balance'       =>  0
                            );
                            

                            $this->app_model->insert('ledger', $ledger_data);
                        }
                        //END OF ledger table entry
                        
                    }
                }
            }

            foreach ($penalties as $key => $inv) {

                $inv = (object) $inv;
                foreach ($uris as $key => $uri) {

                    if($inv->balance <= 0) break;
                    if($uri->balance <= 0) continue;

                    if($uri->balance >= $inv->balance ){
                        $uri_amount = $inv->balance;
                        $uri->balance -= $inv->balance;
                        $inv->balance -= $inv->balance;
                    }else{
                        $uri_amount = $uri->balance;
                        $inv->balance -= $uri->balance;
                        $uri->balance -= $uri->balance;
                    }

                    $total_uri_amount_paid += $uri_amount;

                    $sl_data = [];
                    $ft_ref = $this->app_model->generate_ClosingRefNo();

                    $sl_data['uri_entry'] = array(
                        'posting_date'      =>  $date_created,
                        'transaction_date'  =>  $transaction_date,
                        'document_type'     =>  'Payment',
                        'ref_no'            =>  $uri->ref_no,
                        'doc_no'            =>  $soa_no,
                        'tenant_id'         =>  $tenant_id,
                        'gl_accountID'      =>  $uri->gl_accountID,
                        'company_code'      =>  $store->company_code,
                        'department_code'   =>  '01.04',
                        'debit'             =>  $uri_amount,
                        'ft_ref'            =>  $ft_ref,
                        'prepared_by'       =>  $this->session->userdata('id')
                    );

                    $sl_data['rec_entry'] = array(
                        'posting_date'      => $date_created,
                        'transaction_date'  => $transaction_date,
                        'document_type'     => 'Payment',
                        'ref_no'            => $inv->ref_no,
                        'doc_no'            => $soa_no,
                        'tenant_id'         => $tenant_id,
                        'gl_accountID'      => $inv->gl_accountID,
                        'company_code'      => $store->company_code,
                        'department_code'   => '01.04',
                        'credit'            => -1 * $uri_amount,
                        'ft_ref'            =>  $ft_ref,
                        'prepared_by'       => $this->session->userdata('id')
                    );

                    foreach ($sl_data as $key => $data) {
                        $this->db->insert('subsidiary_ledger', $data);
                        $this->db->insert('general_ledger', $data);
                        
                    }


                    //START OF ledger table entry
                    $lgr_inv = $this->app_model->getLedgerFirstResultByDocNo($tenant_id, $inv->doc_no);
                    if(!empty($lgr_inv)){

                        $ledger_data = array(
                            'posting_date'  =>  $date_created,
                            'transaction_date' =>$transaction_date,
                            'document_type' =>  'SOA',
                            'ref_no'        =>  $lgr_inv->ref_no,
                            'doc_no'        =>  $soa_no,
                            'tenant_id'     =>  $tenant_id,
                            'contract_no'   =>  $contract_no,
                            'description'   =>  $lgr_inv->description,
                            'debit'         =>  $uri_amount,
                            'balance'       =>  0
                        );
                        

                        $this->app_model->insert('ledger', $ledger_data);
                    }

                    $lgr_uri = $this->app_model->getLedgerFirstResultByDocNo($tenant_id, $uri->doc_no);
                    if(!empty($lgr_uri)){

                        $ledger_data = array(
                            'posting_date'  =>  $date_created,
                            'transaction_date' =>$transaction_date,
                            'document_type' =>  'SOA',
                            'ref_no'        =>  $lgr_uri->ref_no,
                            'doc_no'        =>  $soa_no,
                            'tenant_id'     =>  $tenant_id,
                            'contract_no'   =>  $contract_no,
                            'description'   =>  $lgr_uri->description,
                            'credit'        =>  $uri_amount,
                            'balance'       =>  0
                        );
                        
                        $this->app_model->insert('ledger', $ledger_data);
                    }
                    //END OF ledger table entry
                }
            } 
        
            /* =============  END APPLY ADVANCES  =========== */


            $uri_balance = 0;
            foreach ($uris as $key => $uri) {
                $uri_balance += $uri->balance;
                $uri_date = $uri->posting_date;
            }
        
            $soa_display['uri'] = [
                'total_uri_amount'      => $total_uri_amount,
                'total_uri_amount_paid' => $total_uri_amount_paid,
                'remaining'             => $uri_balance,
                'date'                  => (!empty($uri_date) ? $uri_date : '')
            ];
            
            $soa_amount_due -= $total_uri_amount_paid;
        }

        $soa_display['net_amount_due'] = $soa_amount_due;

        //dump($soa_display);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) 
        {
            $this->db->trans_rollback(); 

            /*$this->app_model->insert('error_log', [
                'action' => 'Generating SOA', 
                'error_msg' => $this->db->_error_message()
            ]);*/

            JSONResponse(['type'=>'error', 'msg'=>'Something went wrong! Unable to generate SOA file']);
        }
        

        $file = $this->createSoaFile($soa_display, $debit_display);

        //var_dump($grouped_invoices);

        JSONResponse(['type'=>'success', 'msg'=>'SOA Successfully Generated', 'file'=>$file]);
    }

    function createSoaFile($soa, $debit){
        $soa = (object) $soa;

        $store = $soa->store;
        $tenant = $soa->tenant;

        $pdf = new FPDF('p','mm', 'A4');
        $pdf->AddPage();
        $pdf->setDisplayMode ('fullpage');
        $logoPath = getcwd() . '/assets/other_img/';
        $inCharge = getcwd() . '/img/karen_longjas_1.png';


        $pdf->cell(15, 15, $pdf->Image($logoPath . $store->logo, $pdf->GetX(), $pdf->GetY(), 15), 0, 0, 'L', false);

        $pdf->setFont ('times','B',12);
        $pdf->cell(50, 10, strtoupper($store->store_name), 0, 0, 'L');
        $store_name = $store->store_name;
        $pdf->SetTextColor(201, 201, 201);
        $pdf->SetFillColor(35, 35, 35);
        $pdf->cell(35, 6, " ", 0, 0, 'L');


        $pdf->setFont ('times','',9);
        $pdf->cell(30, 6, "Statement For:", 1, 0, 'C', TRUE);
        $pdf->cell(30, 6, "Please Pay By:", 1, 0, 'C', TRUE);
        $pdf->cell(30, 6, "Amount Due:", 1, 0, 'C', TRUE);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->ln();

        $pdf->setFont ('times','',12);
        $pdf->cell(15, 0, " ", 0, 0, 'L');
        $pdf->cell(20, 10, $store->store_address, 0, 0, 'L');

        $pdf->cell(65, 6, " ", 0, 0, 'L');
        $pdf->setFont ('times','',9);
        $pdf->cell(30, 5, $soa->billing_period, 1, 0, 'C');
        $pdf->cell(30, 5, date('F j, Y',strtotime($soa->collection_date)), 1, 0, 'C');
        $pdf->cell(30, 5, "P " . number_format($soa->net_amount_due, 2), 1, 0, 'C');


        $pdf->ln();
        $pdf->ln();
        $pdf->cell(75, 6, " ", 0, 0, 'L');
        $pdf->SetTextColor(201, 201, 201);

        $pdf->cell(25, 6, " ", 0, 0, 'L');
        $pdf->cell(90, 5, "Questions? Contact", 1, 0, 'C', TRUE);
        $pdf->setFont ('times','',10);
        $pdf->ln();

        $pdf->SetTextColor(201, 201, 201);
        $pdf->setFont ('times','B',10);
        $pdf->cell(75, 10, "LESSEE'S INFORMATION", 1, 0, 'C', TRUE);
        $pdf->cell(25, 6, " ", 0, 0, 'L');
        $pdf->setFont ('times','',10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Multicell(90, 4, $store->contact_person . "\n" . "Phone: " . $store->contact_no . "\n" . "E-mail: " .$store->email, 1, 'C');

        $pdf->ln();
        $pdf->SetTextColor(0, 0, 0);

        // ============ LESSEE INFORMATION ============ //
        $rental_type = $tenant->rental_type;
        $pdf->setFont ('times','B',8);
        $pdf->cell(25, 4, "Tenant ID ", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " . $tenant->tenant_id, 0, 0, 'L');
        $pdf->cell(10, 4, "  ", 0, 0, 'L');
        $pdf->cell(25, 4, "SOA No.", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " . $soa->soa_no, 0, 0, 'L');

        $pdf->ln();

        $pdf->cell(25, 4, "Contract No", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " . $tenant->contract_no, 0, 0, 'L');
        $pdf->cell(10, 4, "  ", 0, 0, 'L');
        $pdf->cell(25, 4, "Date", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " . $soa->date_created, 0, 0, 'L');

        $pdf->ln();

        $pdf->cell(25, 4, "Trade Name", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " . $tenant->trade_name, 0, 0, 'L');
        $pdf->cell(10, 4, "  ", 0, 0, 'L');
        $pdf->cell(25, 4, "Location Code", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " . $tenant->location_code, 0, 0, 'L');

        $pdf->ln();

        $pdf->cell(25, 4, "Corp Name", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " .  $tenant->corporate_name, 0, 0, 'L');
        $pdf->cell(10, 4, "  ", 0, 0, 'L');
        $pdf->cell(25, 4, "Floor Area", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " .  $tenant->floor_area . " Square Meters", 0, 0, 'L');

        $pdf->ln();


        $pdf->cell(25, 4, "Address", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " . ellipsis($tenant->address, 30), 0, 0, 'L');
        $pdf->cell(10, 4, "  ", 0, 0, 'L');
        $pdf->cell(25, 4, "Billing Period", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " .  $soa->billing_period, 0, 0, 'L');

        $pdf->ln();

        $pdf->cell(25, 4, "Rental Type", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " .  $tenant->rental_type, 0, 0, 'L');
        $pdf->cell(10, 4, "  ", 0, 0, 'L');
        $pdf->cell(25, 4, "TIN", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ":  " .  $tenant->tin, 0, 0, 'L');

        $pdf->ln();

        $pdf->cell(25, 4, "Percentage Rate", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');


        if ($tenant->rental_type == 'Fixed' )
        {
            $pdf->cell(60, 4, ":  " .  "N/A", 0, 0, 'L');
        } else {
            $pdf->cell(60, 4, ":  " .  $tenant->rent_percentage . "%", 0, 0, 'L');
        }
        $pdf->cell(10, 4, "  ", 0, 0, 'L');
        $pdf->cell(25, 4, "Tenancy Type", 0, 0, 'L');
        $pdf->cell(2, 4, "  ", 0, 0, 'L');
        $pdf->cell(60, 4, ": " . $soa->tenancy_type, 0, 0, 'L');



        $pdf->ln();
        $pdf->setFont ('times','B',10);

        $tenant_id = $tenant->tenant_id;

        if ($tenant_id == 'ICM-LT000008' || $tenant_id == 'ICM-LT000442' || $tenant_id == 'ICM-LT000492' || $tenant_id == 'ICM-LT000035' || $tenant_id == 'ICM-LT000120')
        {
            $pdf->cell(0, 5, "Please make all checks payable to ALTURAS SUPERMARKET CORP. BPI - 1201008995"  , 0, 0, 'R');
        }
        elseif ($store_name == 'ALTA CITTA') 
        {
            if($tenant_id == 'ACT-LT000027')
            {
                $pdf->cell(0, 5, "Please make all checks payable to ALTURAS SUPERMARKET CORP. - ALTA CITTA LEASING BPI  ACCT# 9471-0016-75"  , 0, 0, 'R');
            }else
            {
                $pdf->cell(0, 5, "Please make all checks payable to ALTURAS SUPERMARKET CORP. - ALTA CITTA LEASING PNB ACCT# 3059-7000-6462"  , 0, 0, 'R');
            }  
        }
        elseif ($tenant_id == 'ICM-LT000218' || $tenant_id == 'ICM-LT000219') 
        {
            $pdf->cell(0, 5, "Please make payment credited to ASC-ICM LEASING with acct# 3522 1000 63"  , 0, 0, 'R');
        }
        else
        {
            if ($store_name == 'ALTURAS MALL') 
            {
                $pdf->cell(0, 5, "ALTURAS SUPERMARKET CORP. with Acct# 3059-7000-5922"  , 0, 0, 'R');
            }
            elseif($store_name == "PLAZA MARCELA")
            {
                $pdf->cell(0, 5, " MFI - PLAZA MARCELA, LB ACCT #0612-0011-11"  , 0, 0, 'R');
            }
            elseif($store_name == 'ISLAND CITY MALL' || $tenant_id != 'ICM-LT000008' || $tenant_id != 'ICM-LT000442' || $tenant_id != 'ICM-LT000492' || $tenant_id != 'ICM-LT000035' || $tenant_id != 'ICM-LT000120')
            {
                $pdf->cell(0, 5, "Please make all checks payable to ISLAND CITY MALL,Acct # 9471 -0016-59 "  , 0, 0, 'R');
            }else 
            {
                $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store->store_name). "" , 0, 0, 'R');
            }
        }

        $pdf->ln();
        $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont ('times','B',16);
        $pdf->cell(0, 6, "Statement of Account", 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();

        // $pdf->setFont ('times','B',12);
        // $pdf->cell(190, 6, "                                            DESCRIPTION                                                                    AMOUNT", 1, 0, 'L');
        // $pdf->ln();

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(35, 35, 35);
        $pdf->setFont ('times','B',12);
        $pdf->cell(190, 6, "                                            DESCRIPTION                                                                    AMOUNT", 1, 0, 'L', TRUE);
        $pdf->ln();
        $pdf->SetTextColor(0, 0, 0);


        $date_created    = date('Y-m-d', strtotime($soa->date_created));
        $collection_date = date('Y-m-d', strtotime($soa->collection_date));

        $prev_bill = $this->app_model->get_soaPreviousBilling($tenant->tenant_id, $date_created);

        if(!empty($prev_bill))
        {
            $previous_amount = $prev_bill->amount_payable;
            $amount_paid = $prev_bill->amount_paid;;

            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(100, 8, "Previous Billing Amount", 0, 0, 'L');
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->setFont('times','',10);
            $pdf->cell(20, 4, number_format($previous_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(100, 8, "Payment Received Amount - Thank you!", 0, 0, 'L');
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->setFont('times','',10);
            $pdf->cell(20, 4, number_format($amount_paid, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

        }


        // ============ IF HAS PRE-OPERATIONAL ============ //
        if(!empty($soa->preop))
        {
            $preop_total = 0;
            $pdf->cell(100, 8, "Additional/Preoparation Charges", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->setFont('times','',10);
            $pdf->ln();

            foreach ($soa->preop as $preop)
            {   
                $preop = (object) $preop;

                $preop_desc = "";
                if ($preop->description == 'Security Deposit - Kiosk and Cart' || $preop->description == 'Security Deposit')
                {
                    $preop_desc = "Security Deposit";
                }
                else
                {
                    $preop_desc = $preop->description;
                }

                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(80, 4, $preop_desc, 0, 0, 'L');
                $pdf->cell(40, 4, "P " . number_format($preop->amount, 2), 0, 0, 'R');
                $pdf->ln();
            }

            $pdf->ln();
            $pdf->setFont('times','',10);
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(80, 4, "Total", 0, 0, 'L');
            $pdf->setFont('times','B',10);
            $pdf->cell(40, 4, "P " . number_format($soa->preop_total, 2), 0, 0, 'R');
            $pdf->ln();
        }

        // ============ IF HAS RETRO RENTAL ============ //
        if (!empty($soa->retro)) // Retro Rental
        {
            $pdf->cell(100, 8, "RETRO RENT", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->setFont('times','',10);
            $pdf->ln();

            $pdf->setFont('times','',10);

            foreach ($soa->retro as $retro)
            {
                $retro = (object) $retro;

                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(80, 4, "Previous Balance", 0, 0, 'L');
                $pdf->cell(40, 4, "P " . number_format($retro->debit, 2), 0, 0, 'R');
                $pdf->ln();

                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(80, 4, "Payment Received", 0, 0, 'L');
                $pdf->setFont('times','U',10);
                $pdf->cell(40, 4, "P " . number_format($retro->credit, 2), 0, 0, 'R');
                $pdf->ln();
                $pdf->setFont('times','',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(80, 4, "Balance", 0, 0, 'L');
                $pdf->setFont('times','B',10);
                $pdf->cell(40, 4, "P " . number_format($retro->balance, 2), 0, 0, 'R');
                $pdf->ln();
            }
        }

        // ============ PREVIOUS BALANCES ============ //
        if(!empty($soa->previous))
        {

        // die(var_dump($soa->previous));

            foreach ($soa->previous as $date => $prev) 
            {
                $prev = (object) $prev;


                $pdf->setFont('times','B',10);
                $pdf->cell(100, 8, "PREVIOUS", 0, 0, 'L');
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, date("F Y", strtotime($date)), 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->ln();

                
                if(!$prev->has_basic){
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "Previous Balance", 0, 0, 'L');
                    $pdf->cell(40, 4, "P " . number_format($prev->balance, 2), 0, 0, 'R');
                    $pdf->ln();
                }else{
                    
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "Previous Balance", 0, 0, 'L');
                    $pdf->cell(40, 4, "P " . number_format($prev->debit, 2), 0, 0, 'R');
                    $pdf->ln();

                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');

                    $pdf->cell(80, 4, "Payment Received", 0, 0, 'L');
                    $pdf->setFont('times','U',10);

                    $pdf->cell(40, 4, number_format($prev->credit, 2), 0, 0, 'R');

                    $pdf->ln();
                    $pdf->setFont('times','',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "Balance", 0, 0, 'L');
                    $pdf->cell(40, 4, number_format($prev->balance, 2), 0, 0, 'R');
                }

                if (!empty($prev->no_penalty))
                {
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "No Penalty Charges", 0, 0, 'L');
                    $pdf->cell(40, 4, "P " . number_format($prev->no_penalty, 2), 0, 0, 'R');
                    $pdf->ln();
                }

                if(!empty($prev->penalties)){

                    foreach ($prev->penalties as $key => $penalty) {
                        $penalty = (object)$penalty;

                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(30, 4, "Penalty:", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->setFont('times','',10);

                        $pdf->cell(80, 4, number_format($penalty->penaltyble_amount, 2) . " x $penalty->penalty_percentage% (" . date('F Y', strtotime($date)) . ")", 0, 0, 'L');
                        $pdf->cell(40, 4, number_format($penalty->penalty_amount, 2), 0, 0, 'R');
                    }
                }

                $pdf->ln();
                $pdf->ln();
                $pdf->ln();
                $pdf->setFont('times','', '10');
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->setFont('times','B',10);
                $pdf->cell(100, 4, "Total Amount", 0, 0, 'L');
                $pdf->cell(40, 4, "P " . number_format($prev->total, 2), 0, 0, 'R');

                $pdf->ln();
                $pdf->ln();
            }   
        }

        if(!empty($soa->current)){
            $pdf->setFont('times','B',10);
            $pdf->cell(100, 8, "CURRENT(" . date("F Y", strtotime($soa->current['date'])). ")", 0, 0, 'L');

           
           if(!empty($soa->current['basic'])){

                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "Rental", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->ln();
                   

                foreach ($soa->current['basic'] as $key => $basic) 
                {
                    $basic = (object)$basic;

                    foreach ($basic->invoices as $key => $inv) 
                    {
                        $inv = (object)$inv;

                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->cell(80, 4, $inv->description . " $inv->details", 0, 0, 'L');
                        $pdf->cell(40, 4, ($key==0 ? 'P ' : '') . number_format($inv->amount, 2), 0, 0, 'R');
                        $pdf->ln();
                    } 

                    if(abs($basic->adj_amount) > 0)
                    {
                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->cell(80, 4, "Adjustment/s : " , 0, 0, 'L');
                        //$pdf->cell(40, 4, number_format($basic->adj_amount, 2), 0, 0, 'R');
                        $pdf->setFont('times','',10);
                        $pdf->ln();

                        foreach ($basic->adj_details as $adj) {
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, ($adj->tag == 'Rent Income' ? 'Basic Rent' : $adj->tag), 0, 0, 'L');
                            $pdf->cell(40, 4, ($key==0 ? 'P ' : '') . number_format($adj->amount, 2), 0, 0, 'R');
                            $pdf->ln();
                        }
                    }

                    $pdf->ln();
                }

                if($soa->current['basic_adj_amount'] > 0){

                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "Adjustments : " , 0, 0, 'L');
                    $pdf->cell(40, 4, 'P'. number_format($soa->current['basic_adj_amount'], 2), 0, 0, 'R');
                    $pdf->ln();
                }

                if(abs($soa->current['basic_paid_amount']) > 0)
                {
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "Payment Received : " , 0, 0, 'L');
                    $pdf->cell(40, 4, 'P'. number_format($soa->current['basic_paid_amount'], 2), 0, 0, 'R');

                    // // ========== SUB TOTAL ========== //
                    // $pdf->setFont('times','B',10);
                    // $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    // $pdf->cell(100, 4, "Sub Total", 0, 0, 'L');
                    // $pdf->setFont('times','',10);
                    // $pdf->setFont('times','B',10);
                    // $pdf->cell(40, 4, "P " . number_format($debit, 2), 0, 0, 'R'); 
                    // $pdf->ln();

                    // // $pdf->setFont('times','B',10);
                    // // $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    // // $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    // // $pdf->cell(80, 4, "Payment Received : " , 0, 0, 'L');
                    // // $pdf->cell(40, 4, 'P '. number_format($soa->current['basic_paid_amount'], 2), 0, 0, 'R');

                    $basic_paid_amount = -1 * $soa->current['basic_paid_amount'];
                }
                    // ========== ORIGINAL SUB TOTAL ========== //
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(100, 4, "Sub Total", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->setFont('times','B',10);
                $pdf->cell(40, 4, "P " . number_format($soa->current['basic_sub_total'], 2), 0, 0, 'R'); 
                $pdf->ln();

            }

            if(!empty($soa->current['others'])){

                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "Add:Other Charges", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->ln();
                    

                foreach ($soa->current['others'] as $key => $other){
                    $other = (object)$other;

                    foreach ($other->invoices as $key => $inv){
                        $inv = (object) $inv;

                        switch ($inv->description) {
                            case 'Electricity':
                            case 'Water':
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(80, 4, $inv->description, 0, 0, 'L');
                                $pdf->ln();
                                $pdf->setFont('times','',8);
                                $pdf->cell(60, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "Present", 0, 0, 'L');
                                $pdf->cell(20, 4, "Previous", 0, 0, 'L');
                                $pdf->cell(20, 4, "Consumed", 0, 0, 'L');
                                $pdf->ln();
                                $pdf->cell(60, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, number_format($inv->curr_reading, 2), 0, 0, 'L');
                                $pdf->cell(20, 4, number_format($inv->prev_reading, 2), 0, 0, 'L');
                                $pdf->cell(20, 4, number_format($inv->total_unit, 2), 0, 0, 'L');
                                $pdf->cell(10, 4, " ", 0, 0, 'L');
                                $pdf->setFont('times','',10);
                                $pdf->cell(40, 4, number_format($inv->amount, 2), 0, 0, 'R');
                                $pdf->ln();
                                break;
                            
                            default:
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(80, 4, $inv->description, 0, 0, 'L');
                                $pdf->cell(40, 4, number_format($inv->amount, 2), 0, 0, 'R');
                                $pdf->ln();
                                break;
                        }
                    }



                    if(abs($other->adj_amount) > 0){
                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->cell(80, 4, "Adjustment/s : " , 0, 0, 'L');
                        //$pdf->cell(40, 4, number_format($other->adj_amount, 2), 0, 0, 'R');
                        $pdf->setFont('times','',10);
                        $pdf->ln();

                        foreach ($other->adj_details as $adj) {
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, $adj->tag, 0, 0, 'L');
                            $pdf->cell(40, 4, ($key==0 ? 'P ' : '') . number_format($adj->amount, 2), 0, 0, 'R');
                            $pdf->ln();
                        }
                    }


                    $pdf->ln();
                }

                if ($tenant_id == 'ICM-LT000114'){
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Total W/out Withholding Taxes", 0, 0, 'L');
                    $pdf->setFont('times','',10);
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($soa->current['other_total_without_ewt'], 2), 0, 0, 'R'); 
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Withholding Taxes", 0, 0, 'L');
                    $pdf->setFont('times','',10);
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($soa->current['other_total_ewt'], 2), 0, 0, 'R'); 
                    $pdf->ln();
                    $pdf->ln();
                }

                if(abs($soa->current['other_paid_amount']) > 0){
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "Payment Received : " , 0, 0, 'L');
                    $pdf->cell(40, 4, 'P'. number_format($soa->current['other_paid_amount'], 2), 0, 0, 'R');
                }
                     

                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(100, 4, "Sub Total", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->setFont('times','B',10);
                $pdf->cell(40, 4, "P " . number_format($soa->current['other_sub_total'], 2), 0, 0, 'R');   
            }

            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(100, 4, "Total Amount", 0, 0, 'L');
            $pdf->setFont('times','',10);
            $pdf->cell(40, 4, "P " . number_format($soa->current['total'], 2), 0, 0, 'R');


            $pdf->ln();
            $pdf->ln();
        }


        if (!empty($soa->uri)) 
        {
            $uri = (object)$soa->uri;

            if($uri->total_uri_amount > 0)
            {
                if ($basic_paid_amount > 0) 
                {
                    $advance_total = $basic_paid_amount + $uri->total_uri_amount;

                    $pdf->ln();
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Advance Payment"  . " (" . $uri->date . ")", 0, 0, 'L');
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($advance_total, 2), 0, 0, 'R');
                }
                else
                {
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Advance Payment"  . " (" . $uri->date . ")", 0, 0, 'L');
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($uri->total_uri_amount, 2), 0, 0, 'R');
                }
            }
        }
        else
        {
            $uri = [];
        }

        /*if($uri->total_uri_amount_paid > 0 && $uri->total_uri_amount != $uri->total_uri_amount_paid){
            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(100, 4, "Advance Payment Applied", 0, 0, 'L');
            $pdf->setFont('times','B',10);
            $pdf->cell(40, 4, "-" . number_format($uri->total_uri_amount_paid, 2), 0, 0, 'R');
        }*/

        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times','B',10);
        $pdf->cell(30, 4, "     ", 0, 0, 'L');
        $pdf->cell(100, 4, "Total Amount Due", 0, 0, 'L');
        $pdf->setFont('times','BU',10);
        $pdf->cell(40, 4, "P " . number_format($soa->net_amount_due, 2), 0, 0, 'R');
        

        if ($uri == []) 
        {
        
        }
        else
        {
            if($uri->remaining > 0){
                $pdf->ln();
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(100, 4, "Remaining Advance Payment", 0, 0, 'L');
                $pdf->setFont('times','B',10);
                $pdf->cell(40, 4, "P " . number_format($uri->remaining, 2), 0, 0, 'R');
            }
        }


        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont('times','',10);
        $pdf->cell(75, 5, "Certified: ", 0, 0, 'R');
        $pdf->cell(75, 5, $pdf->Image($inCharge, 80, $pdf->GetY(), 60, 13, 'PNG'), 0, 0, 'C');

        $pdf->ln(10);
        $pdf->setFont('times','',8);
        $pdf->cell(50, 4, "     ", 0, 0, 'L');
        $pdf->cell(0, 5, "                                            Corporate Leasing Manager                               ", 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont('times','B',10);

        $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');
        $pdf->setFont('times','B',12);
        $pdf->ln();
        $pdf->cell(190, 4,"         ", 1, 0, 'L', TRUE);
        $pdf->ln();
        $pdf->setFont('times','B',8);
        $pdf->cell(0, 4, "Note: Presentation of this statement is sufficient notice that the account is due. Interest of 3% will be charged for all past due accounts.", 0, 0, 'L');
   


        $file_name =  $tenant->tenant_id . time().'.pdf';

        $this->app_model->insert('soa_file', [
            'tenant_id'         =>  $tenant->tenant_id,
            'file_name'         =>  $file_name,
            'soa_no'            =>  $soa->soa_no,
            'billing_period'    =>  $soa->billing_period,
            'amount_payable'    =>  $soa->net_amount_due,
            'posting_date'      =>  $date_created,
            'collection_date'   =>  $collection_date,
            'transaction_date'  =>  getCurrentDate()
        ]);

        //$response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
        //header('Content-Type: application/facilityrental_pdf');

        $pdf->Output('assets/pdf/' . $file_name , 'F');

        return $file_name;
    }


    public function payment()
    { 
        //dump($this->session->userdata);

        if($this->session->userdata('user_type') == 'Accounting Staff')
        {
            $data['current_date']   = getCurrentDate();

            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['payee']          = $this->app_model->my_store();
            $data['store_id']       = $this->session->userdata('user_group');

            $data['tender_types']   = json_encode([
                                        ['id' => 4, 'desc' => 'AR-Employee'],
                                        ['id' => 1, 'desc' => 'Cash'],
                                        ['id' => 2, 'desc' => 'Check'],
                                        ['id' => 3, 'desc' => 'Bank to Bank'],
                                        ['id' => 80, 'desc' => 'JV payment - Business Unit'],
                                        ['id' => 81, 'desc' => 'JV payment - Subsidiary'],
                                        ['id' => 11, 'desc' => 'Unidentified Fund Transfer'],
                                        ['id' => 12, 'desc' => 'Internal Payment']
                                    ]);

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/accounting/payment');
            $this->load->view('leasing/footer');
        } 
    }

    public function getSoaWithBalances($tenant_id, $posting_date){

        $tenant_id      = $this->sanitize($tenant_id);
        $posting_date   = $this->sanitize($posting_date);

        $date_now = date("Y-m-d");

        if($date_now < '2021-08-31'){
            $this->prepost_old_soa($tenant_id, $posting_date);
        }

        $soa_docs = $this->app_model->getSoaWithBalances($tenant_id, $posting_date);

        JSONResponse($soa_docs);
    }

    public function getInvoicesBySoaNo($tenant_id, $soa_no){
        $tenant_id      = $this->sanitize($tenant_id);
        $soa_no         = $this->sanitize($soa_no);

        $data = $this->app_model->getInvoicesBySoaNo($tenant_id, $soa_no);

        JSONResponse($data);
    }

    public function get_payment_initial_data(){
        
        if($this->session->userdata('cfs_logged_in')){
            $store_id = $this->session->userdata('user_group');
            $banks = $this->app_model->get_mycashbank($store_id);
        }else{
            $banks = $this->app_model->getAccreditedBanks();
        }
        

        $uft_no = $this->app_model->generate_UTFTransactionNo(FALSE);
        $ip_no = $this->app_model->generate_InternalTransactionNo(FALSE);
        $stores = $this->app_model->get_stores();

        JSONResponse(compact('banks', 'uft_no', 'ip_no', 'stores'));

    }

    public function save_payment(){

        /*=====================  SETTING VALUES STARTS HERE ==========================*/

        $tenancy_type       = $this->sanitize($this->input->post('tenancy_type'));
        $trade_name         = $this->sanitize($this->input->post('trade_name'));
        $tenant_id          = $this->sanitize($this->input->post('tenant_id'));
        $contract_no        = $this->sanitize($this->input->post('contract_no'));
        $tenant_address     = $this->sanitize($this->input->post('tenant_address'));
        $payment_date       = $this->sanitize($this->input->post('payment_date'));
        $soa_no             = $this->sanitize($this->input->post('soa_no'));
        $billing_period     = $this->sanitize($this->input->post('billing_period'));
        $remarks            = $this->sanitize($this->input->post('remarks'));
        $tender_typeCode    = $this->sanitize($this->input->post('tender_typeCode'));
        $receipt_no         = $this->sanitize($this->input->post('receipt_no'));
        $amount_paid        = $this->sanitize($this->input->post('amount_paid'));
        $tender_amount      = $amount_paid;
        $receipt_no         = "PR".$receipt_no;
        $payment_docs       = $this->input->post('payment_docs');

        $transaction_date   = getCurrentDate();
        $payment_date       = date('Y-m-d', strtotime($payment_date));


        //IF NOT INTERNAL PAYMENT
        $bank_code          = $this->sanitize($this->input->post('bank_code'));
        $bank_name          = $this->sanitize($this->input->post('bank_name'));
        $payor              = $this->sanitize($this->input->post('payor'));
        $payee              = $this->sanitize($this->input->post('payee'));

        //IF INTERNAL PAYMENT
        $ip_store_code          = $this->sanitize($this->input->post('store_code'));
        $ip_store_name          = $this->sanitize($this->input->post('store_name'));
        

        //IF CHECK
        $check_type          = $this->sanitize($this->input->post('check_type'));
        $bank_code          = $this->sanitize($this->input->post('bank_code'));
        $account_no         = $this->sanitize($this->input->post('account_no'));
        $account_name       = $this->sanitize($this->input->post('account_name'));
        $check_no           = $this->sanitize($this->input->post('check_no'));
        $check_date         = $this->sanitize($this->input->post('check_date'));
        $check_due_date     = $this->sanitize($this->input->post('check_due_date'));
        $expiry_date        = $this->sanitize($this->input->post('expiry_date'));
        $check_class        = $this->sanitize($this->input->post('check_class'));
        $check_category     = $this->sanitize($this->input->post('check_category'));
        $customer_name      = $this->sanitize($this->input->post('customer_name'));
        $check_bank         = $this->sanitize($this->input->post('check_bank'));
        $check_date         = date('Y-m-d', strtotime($check_date));
        $check_due_date     = date('Y-m-d', strtotime($check_due_date));
        $expiry_date        = date('Y-m-d', strtotime($expiry_date));

        /*=====================  SETTING VALUES ENDS HERE ==========================*/

        /*=====================  VALIDATION STARTS HERE ==========================*/


        $this->form_validation->set_rules('tenancy_type', 'Tenancy Type', 'required|in_list[Short Term Tenant,Long Term Tenant]');
        $this->form_validation->set_rules('trade_name', 'Trade Name', 'required');
        $this->form_validation->set_rules('tenant_id', 'Tenant ID', 'required');
        $this->form_validation->set_rules('contract_no', 'Contract No.', 'required');
        $this->form_validation->set_rules('tenant_address', 'Tenant Address', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
        $this->form_validation->set_rules('soa_no', 'SOA No.', 'required');
        $this->form_validation->set_rules('billing_period', 'Billing Period', 'required');
        $this->form_validation->set_rules('tender_typeCode', '', 'required|in_list[1,2,3,11,12,80,81]');
        $this->form_validation->set_rules('receipt_no', 'Reciept No.', 'required');
        $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required|numeric');
        $this->form_validation->set_rules('payment_docs', 'Payment Documents', 'required');



        if(in_array($tender_typeCode, [1,2,3,11])){
            $this->form_validation->set_rules('bank_code', 'Bank Code', 'required');
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
            $this->form_validation->set_rules('payor', 'Payor', 'required');
            $this->form_validation->set_rules('payee', 'Payee', 'required');
        }else{
            if($tender_typeCode == 12){
                $this->form_validation->set_rules('store_code', 'Store Code', 'required');
                $this->form_validation->set_rules('store_name', 'Store Name', 'required');
            }

            $bank_code = null;
            $bank_name = null;
        }

        if($tender_typeCode == '2'){
            if($this->session->userdata('cfs_logged_in')){
                $this->form_validation->set_rules('account_no', 'Account No.', 'required');
                $this->form_validation->set_rules('account_name', 'Account Name', 'required');
                //$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');
                $this->form_validation->set_rules('check_class', 'Check Class', 'required');
                $this->form_validation->set_rules('check_category', 'Check Category', 'required');
                $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('check_bank', 'Check Bank', 'required');
            }
            $this->form_validation->set_rules('check_type', 'Check Type', 'required|in_list[DATED CHEC, POST DATED CHECK]');
            $this->form_validation->set_rules('check_no', 'Check No.', 'required');
            $this->form_validation->set_rules('check_date', 'Check Date', 'required'); 

            if(!in_array($check_type, ['DATED CHECK', 'POST DATED CHECK'])){
                JSONResponse(['type'=>'error', 'msg'=>'Invalid Check Type!']);
            }

            if($check_type  == 'POST DATED CHECK') {
                $this->form_validation->set_rules('check_due_date', 'Check Due Date', 'required');

                if(!validDate($check_due_date)){
                    JSONResponse(['type'=>'error', 'msg'=>'Check Due Date is not valid!']);
                }  
            }

            if($this->session->userdata('cfs_logged_in') && !validDate($expiry_date)){
                $expiry_date = '';
               //JSONResponse(['type'=>'error', 'msg'=>'Check Expiry Date is not valid!']);
            }

            if(!validDate($check_date)){
                JSONResponse(['type'=>'error', 'msg'=>'Check Date is not valid!']);
            } 
        }


        if ($this->form_validation->run() == FALSE)
        {
            JSONResponse(['type'=>'error', 'msg'=>validation_errors()]);
        }

        $soa  = $this->app_model->get_soaDetails($tenant_id, $soa_no);

        if(empty($soa)){
            JSONResponse(['type'=>'error', 'msg'=>'SOA not found!']);
        }


        $tender_types = [
            '4'     =>'AR-Employee',
            '1'     =>'Cash', 
            '2'     =>'Check', 
            '3'     =>'Bank to Bank', 
            '80'    =>'JV payment - Business Unit',
            '81'    =>'JV payment - Subsidiary',
            '11'    => 'Unidentified Fund Transfer',
            '12'    =>'Internal Payment'
        ];

        $tender_typeDesc = $tender_types[$tender_typeCode];

        if(!in_array($tender_typeCode, [1,2,3,11,12,80,81,4])){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Tender Type!']);
        }

        if(in_array($tender_typeCode, ['2','80','81'])){
            $upload = new FileUpload;

            $supp_doc= $upload->validate('supp_doc', 'Supporting Documents')
                ->required()
                ->multiple()
                ->get();

            if($upload->has_error()){
                JSONResponse(['type'=>'error', 'msg'=>$upload->get_errors('<br>')]);
            }
        }

        if(in_array($tender_typeCode, ['1','2','80','81','4'])){
            
            if($this->app_model->checkPaymentReceiptExistence($receipt_no)){
                JSONResponse(['type'=>'error', 'msg'=>'Payment Receipt already used!']);
            }
        }

        if(!validDate($payment_date)){
            JSONResponse(['type'=>'error', 'msg'=>'Payment Date is not valid!']);
        }

        if(empty($payment_docs) || !is_array($payment_docs)){
            JSONResponse(['type'=>'error', 'msg'=>'Payment documents are required!']);
        }

        $total_payable = 0;
        foreach ($payment_docs as $key => $doc) {
            $payment_docs[$key]['amount_paid'] = 0;
            $doc = (object)$doc;
            $total_payable+= $doc->balance;
        }

        $balance = round($total_payable > $amount_paid ? $total_payable - $amount_paid : 0, 2);

        if($total_payable < 1){
            JSONResponse(['type'=>'error', 'msg'=>'Total Payable amount can\'t be 0.00']);
        }

        if($amount_paid <= 0){
            JSONResponse(['type'=>'error', 'msg'=>'Amount paint can\'t be 0.00']);
        }

        $tenant   = $this->app_model->getTenantByTenantID($tenant_id);
        try{
            $store_code             = $this->app_model->tenant_storeCode($tenant_id);
            $store                  = $this->app_model->getStore($store_code);
            $soa_display['store']   = $store;

            if (empty($store_code ) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            } 
        }catch(Exception $e){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Tenant! Tenant might be terminated.']);
        }


        /*=====================  VALIDATION ENDS HERE ==========================*/


        //SET UFT OR IP PAYMENT DOC. NO.
        switch ($tender_typeCode) {
            case '11':
                $receipt_no = $this->app_model->generate_UTFTransactionNo();
                break;
            case '12':
                $receipt_no = $this->app_model->generate_InternalTransactionNo();
                break;
            default:
                $receipt_no;
                break;
        }

        /*
        $tender_types = [
            '1'     =>'Cash', 
            '2'     =>'Check', 
            '3'     =>'Bank to Bank', 
            '80'    =>'JV payment - Business Unit',
            '81'    =>'JV payment - Subsidiary',
            '11'    => 'Unidentified Fund Transfer',
            '12'    =>'Internal Payment'
        ];*/

        $this->db->trans_start(); 

        //APPLY PAYMENT TO INVOICES
        foreach ($payment_docs as $key => $doc) {
            $doc = (object) $doc;
            
            if($amount_paid <= 0){
                break;
            }

            $doc_payment_amt    = round($amount_paid > $doc->balance ? $doc->balance : $amount_paid, 2);
            $amount_paid        = round($amount_paid - $doc_payment_amt, 2);

            $payment_docs[$key]['amount_paid'] =  $doc_payment_amt;
            
            
            //IF PREOP CHARGES GENERATE GLREFERENCE NO. AND SET CORRESPONDING GL ACCOUNT
            if($doc->document_type == 'Preop-Charges' || empty($doc->gl_accountID)){
                $doc->ref_no        = $this->app_model->gl_refNo();

                switch ($doc->description) {
                    case 'Security Deposit - Kiosk and Cart':
                    case 'Security Deposit':
                        $doc->gl_accountID  = 9;
                        break;
                    case 'Construction Bond':
                        $doc->gl_accountID  = 8;
                        break;
                    default:
                        $doc->gl_accountID  = 7;
                        break;
                }

                $preop_balance = round($doc->balance - $doc_payment_amt,2);

                $this->app_model->update(['amount'=>$preop_balance], $doc->id, 'tmp_preoperationcharges');

            }

            $sl_data = [];
            $gl_code = '';
            $debit_status = NULL;
            $credit_status = NULL;
            $ft_ref = NULL;
            $due_date = NULL;



            //CASH | BANK TO BANK 
            if($tender_typeCode == 1 || $tender_typeCode == 3){
                $gl_code = '10.10.01.01.02';

            //CHECK
            }elseif($tender_typeCode == 2){
                $gl_code = $check_type == 'POST DATED CHECK' ? '10.10.01.03.07.01' : '10.10.01.01.02';

                if($check_type == 'POST DATED CHECK'){
                    //$debit_status = 'PDC';
                    $credit_status = 'PDC';
                    $ft_ref = $this->app_model->generate_ClosingRefNo();
                    $due_date = $check_due_date;
                }

            //JV payment - Business Unit
            }elseif($tender_typeCode == 80){
                $gl_code = $this->app_model->bu_entry();

            //JV payment - Subsidiary
            }elseif($tender_typeCode == 81){
                $gl_code = '10.10.01.03.11';

            //UFT
            }elseif($tender_typeCode == 11){
                $gl_code = '10.10.01.01.02';
                $ft_ref = $this->app_model->generate_ClosingRefNo();

                switch ($doc->gl_accountID) {
                    case '9':
                    case '8':
                    case '7':
                        $credit_status = 'Preop Clearing';
                        break;
                    case '4':
                        $credit_status = 'RR Clearing';
                        break;
                    default:
                        $credit_status = 'AR Clearing';
                        break;
                }

            //INTERNAL PAYMENT
            }elseif($tender_typeCode == 4){
                //AR - EMPLOYEE
                    $gl_code = '10.10.01.03.01.03';
            }else{
                $ft_ref = $this->app_model->generate_ClosingRefNo();
                $gl_code    = '10.10.01.03.04';
                $debit_status      = $ip_store_name;
                $credit_status     = 'ARNTI';
            }


            $sl_data['debit'] = array(
                'posting_date'      =>  $payment_date,
                'due_date'          =>  $due_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Payment',
                'ref_no'            =>  $doc->ref_no,
                'doc_no'            =>  $receipt_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                'company_code'      =>  $store->company_code,
                'department_code'   =>  '01.04',
                'debit'             =>  $doc_payment_amt,
                'bank_name'         =>  $tender_typeCode != 12 ? $bank_name : null,
                'bank_code'         =>  $tender_typeCode != 12 ? $bank_code : null,
                'status'            =>  $debit_status,
                'ft_ref'            =>  $ft_ref,
                'prepared_by'       =>  $this->session->userdata('id')
            );

            $sl_data['credit'] = array(
                'posting_date'      =>  $payment_date,
                'due_date'          =>  $due_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Payment',
                'ref_no'            =>  $doc->ref_no,
                'doc_no'            =>  $receipt_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $doc->gl_accountID,
                'company_code'      =>  $store->company_code,
                'department_code'   =>  '01.04',
                'credit'            =>  -1 * $doc_payment_amt,
                'bank_name'         =>  $tender_typeCode != 12 ? $bank_name : null,
                'bank_code'         =>  $tender_typeCode != 12 ? $bank_code : null,
                'status'            =>  $credit_status,
                'ft_ref'            =>  $ft_ref,
                'prepared_by'       =>  $this->session->userdata('id')
            );

            foreach ($sl_data as $key => $data) {
                $this->db->insert('general_ledger', $data);
                $this->db->insert('subsidiary_ledger', $data);
            }

            if($doc->document_type != 'Preop-Charges'){
                $inv = $this->app_model->getLedgerFirstResultByDocNo($tenant_id, $doc->doc_no);
                if(!empty($inv)){
                    $ledger_data = array(
                        'posting_date'  =>  $payment_date,
                        'document_type' =>  'Payment',
                        'ref_no'        =>  $inv->ref_no,
                        'doc_no'        =>  $receipt_no,
                        'tenant_id'     =>  $tenant_id,
                        'contract_no'   =>  $contract_no,
                        'description'   =>  $inv->description,
                        'debit'         =>  $doc_payment_amt,
                        'balance'       =>  0
                    );

                    $this->app_model->insert('ledger', $ledger_data);
                }
            }

            // For Accountability Report
            if( in_array($tender_typeCode, ['1','2','3','80','81', '4']) && 
                in_array($doc->gl_accountID, ['22', '29', '4', '9', '8', '7', '33']) &&
                $this->session->userdata('cfs_logged_in')){

                switch ($doc->gl_accountID) {
                    case '22':
                    case '29':
                        $gl_account_desc = 'Account Receivable';
                        break;
                    case '4' :
                        $gl_account_desc = 'Rent Receivable';
                        break;
                    case '9' :
                        $gl_account_desc = 'Security Deposit';
                        break;
                    case '8' :
                        $gl_account_desc = 'Construction Bond';
                        break;
                    case '33' :
                        $gl_account_desc = 'AR-Employee';
                        break;
                    default : 
                        $gl_account_desc = 'Advance Deposit';
                }
                
                $this->app_model->insert_accReport($tenant_id, $gl_account_desc, $doc_payment_amt, $payment_date, $tender_typeDesc);
            }

            
        }



        //INSERT URI IF HAS ADVANCE
        $advance_amount = $amount_paid;
        if($advance_amount > 0){


            $sl_data    = [];
            $gl_code        = '';
            $ft_ref         = NULL;
            $debit_status   = NULL;
            $credit_status  = NULL;
            $uri_ref_no     = $this->app_model->gl_refNo();
            $due_date = NULL;


            //CASH | BANK TO BANK 
            if($tender_typeCode == 1 || $tender_typeCode == 3){
                $gl_code = '10.10.01.01.02';

            //CHECK
            }elseif($tender_typeCode == 2){
                $gl_code = $check_type == 'POST DATED CHECK' ? '10.10.01.03.07.01' : '10.10.01.01.02';

                if($check_type == 'POST DATED CHECK'){
                    //$debit_status = 'PDC';
                    $credit_status = 'PDC';
                    $ft_ref = $this->app_model->generate_ClosingRefNo();
                    $due_date = $check_due_date;
                }

            //JV payment - Business Unit
            }elseif($tender_typeCode == 80){
                $gl_code = $this->app_model->bu_entry();

            //JV payment - Subsidiary
            }elseif($tender_typeCode == 81){
                $gl_code = '10.10.01.03.11';

            //UFT
            }elseif($tender_typeCode == 11){
                $gl_code = '10.10.01.01.02';
                $credit_status = 'URI Clearing';
                $ft_ref = $this->app_model->generate_ClosingRefNo();

            //INTERNAL PAYMENT
            }else{
                $gl_code    = '10.10.01.03.04';
                $debit_status      = $ip_store_name;
                $credit_status     = 'ARNTI';
                $ft_ref = $this->app_model->generate_ClosingRefNo();
            }


            $sl_data['debit'] = array(
                'posting_date'      =>  $payment_date,
                'due_date'          =>  $due_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Payment',
                'ref_no'            =>  $uri_ref_no,
                'doc_no'            =>  $receipt_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                'company_code'      =>  $store->company_code,
                'department_code'   =>  '01.04',
                'debit'             =>  $advance_amount,
                'bank_name'         =>  $tender_typeCode != 12 ? $bank_name : null,
                'bank_code'         =>  $tender_typeCode != 12 ? $bank_code : null,
                'status'            =>  $debit_status,
                'ft_ref'            =>  $ft_ref,
                'prepared_by'       =>  $this->session->userdata('id')
            );

            $sl_data['credit'] = array(
                'posting_date'      =>  $payment_date,
                'due_date'          =>  $due_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Payment',
                'ref_no'            =>  $uri_ref_no,
                'doc_no'            =>  $receipt_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                'company_code'      =>  $store->company_code,
                'department_code'   =>  '01.04',
                'credit'            =>  -1 * $advance_amount,
                'bank_name'         =>  $tender_typeCode != 12 ? $bank_name : null,
                'bank_code'         =>  $tender_typeCode != 12 ? $bank_code : null,
                'status'            =>  $credit_status,
                'ft_ref'            =>  $ft_ref,
                'prepared_by'       =>  $this->session->userdata('id')
            );

            foreach ($sl_data as $key => $data) {
                $this->db->insert('general_ledger', $data);
                $this->db->insert('subsidiary_ledger', $data);
            }


            // For Montly Receivable Report
            $mon_rec_report_data = array(
                'tenant_id'     =>  $tenant_id,
                'doc_no'        =>  $receipt_no,
                'posting_date'  =>  $payment_date,
                'description'   =>  'Advance Payment',
                'amount'        =>  $advance_amount
            );

            $this->app_model->insert('monthly_receivable_report', $mon_rec_report_data);

            $ledger_data = array(
                'posting_date'       =>  $payment_date,
                'transaction_date'   =>  $transaction_date,
                'document_type'      =>  'Advance Payment',
                'doc_no'             =>  $receipt_no,
                'ref_no'             =>  $this->app_model->generate_refNo(),
                'tenant_id'          =>  $tenant_id,
                'contract_no'        =>  $contract_no,
                'description'        =>  'Advance Payment-' . $trade_name,
                'debit'              =>  $advance_amount,
                'credit'             =>  0,
                'balance'            =>  $advance_amount
            );

            $this->app_model->insert('ledger', $ledger_data);


            // For Accountability Report
            if( in_array($tender_typeCode, ['1','2','3','80','81']) && $this->session->userdata('cfs_logged_in')){

                $this->app_model->insert_accReport($tenant_id, 'Advance Deposit', $advance_amount, $payment_date, $tender_typeDesc);
            }
        }

        

        //SAVE SUPPORTING DOCUMENT
        if(in_array($tender_typeCode, ['2','80','81'])){

            $targetPath  = getcwd() . '/assets/payment_docs/';

            foreach ($supp_doc as $key => $supp) {

                //Setup our new file path
                $filename    = $tenant_id . time() . $supp['name'];
                move_uploaded_file($supp['tmp_name'], $targetPath.$filename);

                $supp_doc_data = [
                    'tenant_id'     => $tenant_id, 
                    'file_name'     => $filename, 
                    'receipt_no'    => $receipt_no
                ];

                $this->db->insert('payment_supportingdocs', $supp_doc_data);

            }
        }


        //INSERT TO PAYMENT SCHEME
        if (in_array($tender_typeCode, ['1','2', '3','80','81','4']))
        {   

            $check_date = $tender_typeCode == 2 ? $check_date : null;

            $pmt_display = (object) compact(
                'tenant', 
                'store', 
                'receipt_no',
                'soa_no',
                'payment_date',
                'remarks',
                'total_payable',
                'payment_docs',
                'payment_date',
                'tender_typeCode',
                'tender_typeDesc',
                'check_type',
                'bank_code',
                'bank_name',
                'tender_amount',
                'check_no',
                'balance',
                'check_due_date',
                'check_date',
                'advance_amount',
                'payor',
                'payee'
            );

            $payment_report =  $this->createPaymentDocsFile($pmt_display);

            /*$check_date = ($tender_typeCode == 2 ?
                ($check_type == 'POST DATED CHECK'? $check_due_date : $payment_date) 
            : null);*/

            

            $paymentScheme = array(
                'tenant_id'        =>   $tenant_id,
                'contract_no'      =>   $contract_no,
                'tenancy_type'     =>   $tenancy_type,
                'receipt_no'       =>   $receipt_no,
                'tender_typeCode'  =>   $tender_typeCode,
                'tender_typeDesc'  =>   $tender_typeDesc,
                'soa_no'           =>   $soa_no,
                'billing_period'   =>   $billing_period,
                'amount_due'       =>   $total_payable,
                'amount_paid'      =>   $tender_amount,
                'bank'             =>   $bank_name,
                'check_no'         =>   $tender_typeCode == 2 ? $check_no : null,
                'check_date'       =>   $check_date,
                'payor'            =>   $payor,
                'payee'            =>   $payee,
                'receipt_doc'      =>   $payment_report,
                'rec_amount_paid'  =>   $tender_amount,
            );

            $this->db->insert('payment_scheme', $paymentScheme);

            /*======================  CCM DATA =================================== */

            if ($tender_typeCode == '2' && $this->session->userdata('cfs_logged_in')) 
            {
                $this->load->model('ccm_model');

                $customer_id = $this->ccm_model->check_customer($customer_name);
                $checksreceivingtransaction_id = $this->ccm_model->checksreceivingtransaction();


                $ccm_data = array(
                    'checksreceivingtransaction_id' => $checksreceivingtransaction_id, 
                    'customer_id'                   => $customer_id,
                    'businessunit_id'               => $this->ccm_model->get_BU(),
                    'department_from'               => '12',
                    'leasing_docno'                 => $receipt_no,
                    'check_no'                      => $check_no,
                    'check_class'                   => $check_class,
                    'check_category'                => $check_category,
                    'check_expiry'                  => $expiry_date,
                    'check_date'                    => $check_date,
                    'check_received'                => $transaction_date,
                    'check_type'                    => $check_type,
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
        }

        //$last_soa = $this->app_model->getLastestSOA($tenant_id, $payment_date);

        //INSERT TO PAYMENT
        $paymentData = array(
            'posting_date' =>   $payment_date,
            'soa_no'       =>   $soa_no,
            'amount_paid'  =>   $tender_amount,
            'tenant_id'    =>   $tenant_id,
            'doc_no'       =>   $receipt_no,
            'rec_amount_paid'  =>   $tender_amount,
        );

        $this->db->insert('payment', $paymentData);


        //INSERT UFT
        if($tender_typeCode == '11'){
            $data_utf = array(
                'tenant_id'      => $tenant_id,
                'bank_code'      => $bank_code,
                'bank_name'      => $bank_name,
                'posting_date'   => $payment_date,
                'amount_payable' => $total_payable,
                'amount_paid'    => $tender_amount
            );
            $this->db->insert('uft_payment', $data_utf);
        }


        



        //CHECK IF DELAYED PAYMENT
        if (!$this->app_model->is_penaltyExempt($tenant_id) && 
            $soa->billing_period != 'Upon Signing of Notice' && 
            !$this->DISABLE_PENALTY)
        {
            $collection_date  = $soa->collection_date;

            if (date('Y-m-d', strtotime($payment_date)) > date('Y-m-d', strtotime($collection_date . "+ 1 day")))
            {
                $daysOfMonth         = date('t', strtotime($payment_date));
                $daydiff             = floor((abs(strtotime($payment_date . "- 1 days") - strtotime($collection_date))/(60*60*24)));
                $sundays             = $this->app_model->get_sundays($collection_date, $payment_date);
                $daydiff             = $daydiff - $sundays;
                $penalty_latepayment = ($tender_amount * .02 * $daydiff) / $daysOfMonth;

                $penaltyEntry = array(
                    'tenant_id'     =>  $tenant_id,
                    'posting_date'  =>  $payment_date,
                    'contract_no'   =>  $contract_no,
                    'doc_no'        =>  $receipt_no,
                    'description'   =>  'Late Payment-' . $trade_name,
                    'amount'        =>  round($penalty_latepayment, 2)
                );
                $this->db->insert('tmp_latepaymentpenalty', $penaltyEntry);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) 
        {
            $this->db->trans_rollback(); 
            $this->app_model->insert('error_log', [
                'action' => 'Saving Payment', 
                'error_msg' => $this->db->_error_message()
            ]);

            JSONResponse(['type'=>'error', 'msg'=>'Something went wrong while posting payment!']);
        }
        

        if (in_array($tender_typeCode, ['1','2', '3','80','81','4'])){
            JSONResponse(['type'=>'success', 'msg'=>'Payment successfully posted!', 'file'=>$payment_report]);
        }
            

        JSONResponse(['type'=>'success', 'msg'=>'Payment successfully posted!']);
         

    }


    function createPaymentDocsFile($pmt){

        $pdf = new FPDF('p','mm','A4');
        $pdf->AddPage();
        $pdf->setDisplayMode ('fullpage');
        $logoPath = getcwd() . '/assets/other_img/';


        $store = $pmt->store;
        $tenant = $pmt->tenant;

        $pdf->cell(20, 20, $pdf->Image($logoPath . $store->logo, 100, $pdf->GetY(), 15), 0, 0, 'C', false);
        $pdf->ln();
        $pdf->setFont ('times','B',14);
        $pdf->cell(75, 6, " ", 0, 0, 'L');
        $pdf->cell(40, 10, strtoupper($store->store_name), 0, 0, 'L');
        $store_name = $store->store_name;
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(35, 35, 35);
        $pdf->cell(35, 6, " ", 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont ('times','',14);
        $pdf->cell(15, 0, " ", 0, 0, 'L');
        $pdf->cell(0, 10, $store->store_address, 0, 0, 'C');

        $pdf->ln();
        $pdf->ln();


        $pdf->setFont('times','',10);
        $pdf->cell(30, 6, "Receipt No.", 0, 0, 'L');
        $pdf->cell(60, 6, $pmt->receipt_no, 1, 0, 'L');
        $pdf->cell(5, 6, " ", 0, 0, 'L');
        $pdf->cell(30, 6, "Soa No.", 0, 0, 'L');
        $pdf->cell(60, 6, $pmt->soa_no, 1, 0, 'L');

        $pdf->ln();
        $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
        $pdf->cell(60, 6, $tenant->tenant_id, 1, 0, 'L');
        $pdf->cell(5, 6, " ", 0, 0, 'L');
        $pdf->cell(30, 6, "Date", 0, 0, 'L');
        $pdf->cell(60, 6, $pmt->payment_date, 1, 0, 'L');
        $pdf->ln();
        $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
        $pdf->cell(60, 6, $tenant->trade_name, 1, 0, 'L');
        $pdf->cell(5, 6, " ", 0, 0, 'L');
        $pdf->cell(30, 6, "Remarks", 0, 0, 'L');
        $pdf->cell(60, 6, $pmt->remarks, 1, 0, 'L');
        $pdf->ln();
        $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
        $pdf->cell(60, 6, $tenant->corporate_name, 1, 0, 'L');
        $pdf->cell(5, 6, " ", 0, 0, 'L');
        $pdf->cell(30, 6, "Total Payable", 0, 0, 'L');
        $pdf->cell(60, 6, number_format($pmt->total_payable, 2), 1, 0, 'L');
        $pdf->ln();
        $pdf->cell(30, 6, "TIN", 0, 0, 'L');
        $pdf->cell(60, 6, $tenant->tin, 1, 0, 'L');

        $pdf->ln();
        $pdf->ln();

        $pdf->ln();
        $pdf->setFont ('times','B',10);
        $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store->store_name) , 0, 0, 'R');
        $pdf->ln();
        $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont ('times','B',16);
        $pdf->cell(0, 6, "Payment Receipt", 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();


        $pdf->ln();


        // =================== Receipt Charges Table ============= //
        $pdf->setFont('times','B',10);
        /*$pdf->cell(20, 8, "Doc. Type", 0, 0, 'L');*/
        $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
        $pdf->cell(60, 8, "Charges Type", 0, 0, 'L');
        $pdf->cell(20, 8, "Posting Date", 0, 0, 'C');
        $pdf->cell(20, 8, "Due Date", 0, 0, 'C');
        $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
        $pdf->cell(30, 8, "Amount Paid", 0, 0, 'R');
        $pdf->setFont('times','',10);


        foreach ($pmt->payment_docs as $key => $doc) {
            $doc = (object) $doc;

            $pdf->ln();
            /*$pdf->cell(20, 8, $doc->document_type == 'Preop-Charges' ? 'Preop-Charge' : 'Invoice', 0, 0, 'L');*/
            $pdf->cell(30, 8, $doc->doc_no, 0, 0, 'C');
            $pdf->cell(60, 8, ($doc->document_type == 'Preop-Charges' ? 'Preop-' : '').$doc->description, 0, 0, 'L');
            $pdf->cell(20, 8, $doc->posting_date, 0, 0, 'C');
            $pdf->cell(20, 8, $doc->due_date, 0, 0, 'C');
            $pdf->cell(30, 8, number_format($doc->balance, 2), 0, 0, 'R');
            $pdf->cell(30, 8, number_format($doc->amount_paid, 2), 0, 0, 'R');
        }

        $pdf->ln();
        $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
        $pdf->ln();


        $pdf->setFont('times','B',10);
        $pdf->cell(150, 8, "Payment Scheme: ", 0, 0, 'L');
        $pdf->cell(100, 8, "Payment Date: " . $pmt->payment_date, 0, 0, 'L');
        $pdf->ln();

        $pdf->setFont('times','',10);
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Description: ", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt->tender_typeCode != 2 ? $pmt->tender_typeDesc : ucwords($pmt->check_type)), 0, 0, 'L');
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format($pmt->total_payable, 2), 0, 0, 'L');
        $pdf->ln();

        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
        $pdf->cell(60, 4, (in_array($pmt->tender_typeCode, [1,2,3,11]) ?  $pmt->bank_name : 'N/A'), 0, 0, 'L');
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format($pmt->tender_amount, 2), 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt->tender_typeCode == 2  ? $pmt->check_no : 'N/A'), 0, 0, 'L');
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format($pmt->balance, 2), 0, 0, 'L');  

        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');

        $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt->tender_typeCode == 2 ? $pmt->check_date : 'N/A') , 0, 0, 'L');
        
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format($pmt->advance_amount, 2), 0, 0, 'L');

        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Check Due Date:: ", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt->tender_typeCode == 2 && $pmt->check_type == 'POST DATED CHECK' ? $pmt->check_due_date : 'N/A'), 0, 0, 'L');


        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->payor, 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->payee, 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "OR #: ", 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->receipt_no, 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();


        $pdf->ln();
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

        $file_name =   $tenant->tenant_id . time() . '.pdf';

        $pdf->Output('assets/pdf/' . $file_name , 'F');


        return $file_name;


    }

    public  function preop_payment(){
        $data['payment_docNo']  = $this->app_model->payment_docNo(false);
        $data['current_date']   = getCurrentDate();

        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['payee']          = $this->app_model->my_store();

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/accounting/preop_payment');
        $this->load->view('leasing/footer');
    }

    public function get_preop_balance($tenant_id='', $gl_account=''){
        $tenant_id = str_replace("%20", ' ', $tenant_id);
        $gl_account = str_replace("%20", ' ', $gl_account);

        JSONResponse($this->app_model->get_preop_balance($tenant_id, $gl_account));
    }

    public function getPreopPaymentInvoicesBySoaNo($tenant_id, $soa_no){
        $tenant_id      = $this->sanitize($tenant_id);
        $soa_no         = $this->sanitize($soa_no);

        $data = $this->app_model->getInvoicesBySoaNo($tenant_id, $soa_no, 'preop_payment');

        JSONResponse($data);
    }

    public function save_paymentUsingPreop(){

        /*=====================  SETTING VALUES STARTS HERE ==========================*/

        //$receipt_no         = $this->sanitize($this->input->post('receipt_no'));

        $tenancy_type       = $this->sanitize($this->input->post('tenancy_type'));
        $trade_name         = $this->sanitize($this->input->post('trade_name'));
        $tenant_id          = $this->sanitize($this->input->post('tenant_id'));
        $contract_no        = $this->sanitize($this->input->post('contract_no'));
        $tenant_address     = $this->sanitize($this->input->post('tenant_address'));
        $payee              = $this->sanitize($this->input->post('payee'));

        $payment_date       = $this->sanitize($this->input->post('payment_date'));
        $soa_no             = $this->sanitize($this->input->post('soa_no'));
        $billing_period     = $this->sanitize($this->input->post('billing_period'));
        $remarks            = $this->sanitize($this->input->post('remarks'));

        $tender_typeDesc        = $this->sanitize($this->input->post('tender_typeDesc'));
        $amount_paid        = $this->sanitize($this->input->post('amount_paid'));
        $tender_amount      = $amount_paid;

        $payment_docs       = $this->input->post('payment_docs');

        $transaction_date   = getCurrentDate();
        $payment_date       = date('Y-m-d', strtotime($payment_date));

        

        /*=====================  SETTING VALUES ENDS HERE ==========================*/

        /*=====================  VALIDATION STARTS HERE ==========================*/


        $this->form_validation->set_rules('tenancy_type', 'Tenancy Type', 'required|in_list[Short Term Tenant,Long Term Tenant]');
        $this->form_validation->set_rules('trade_name', 'Trade Name', 'required');
        $this->form_validation->set_rules('tenant_id', 'Tenant ID', 'required');
        $this->form_validation->set_rules('contract_no', 'Contract No.', 'required');
        $this->form_validation->set_rules('tenant_address', 'Tenant Address', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
        $this->form_validation->set_rules('soa_no', 'Trade Name', 'required');
        $this->form_validation->set_rules('billing_period', 'Billing Period', 'required');
        $this->form_validation->set_rules('tender_typeDesc', 'Tender Type Description', 'required|in_list[Security Deposit,Construction Bond]');
        $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required|numeric');
        $this->form_validation->set_rules('payment_docs', 'Payment Documents', 'required');
        $this->form_validation->set_rules('payee', 'Payee', 'required');


        if ($this->form_validation->run() == FALSE)
        {
            JSONResponse(['type'=>'error', 'msg'=>validation_errors()]);
        }

        $soa  = $this->app_model->get_soaDetails($tenant_id, $soa_no);

        if(empty($soa)){
            JSONResponse(['type'=>'error', 'msg'=>'SOA not found!']);
        }


        if(!validDate($payment_date)){
            JSONResponse(['type'=>'error', 'msg'=>'Payment Date is not valid!']);
        }

        if(empty($payment_docs) || !is_array($payment_docs)){
            JSONResponse(['type'=>'error', 'msg'=>'Payment documents are required!']);
        }

        $total_payable = 0;
        foreach ($payment_docs as $key => $doc) {
            $payment_docs[$key]['amount_paid'] = 0;
            $doc = (object)$doc;
            $total_payable+= $doc->balance;
        }

        $balance = round($total_payable > $amount_paid ? $total_payable - $amount_paid : 0, 2);

        if($total_payable < 1){
            JSONResponse(['type'=>'error', 'msg'=>'Total Payable amount can\'t be 0.00']);
        }

        if($amount_paid <= 0){
            JSONResponse(['type'=>'error', 'msg'=>'Amount paint can\'t be 0.00']);
        }

        if($amount_paid > $total_payable){
            JSONResponse(['type'=>'error', 'msg'=>"Amount Paid can't be greater than Total Payable Amount!"]);
        }

        $tenant   = $this->app_model->getTenantByTenantID($tenant_id);
        try{
            $store_code             = $this->app_model->tenant_storeCode($tenant_id);
            $store                  = $this->app_model->getStore($store_code);
            $soa_display['store']   = $store;

            if (empty($store_code ) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            } 
        }catch(Exception $e){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Tenant! Tenant might be terminated.']);
        }


        $preop_total    = $this->app_model->get_preop_balance($tenant_id, $tender_typeDesc);

        if(empty($preop_total->balance)){
            JSONResponse(['type'=>'error', 'msg'=>"No $tender_typeDesc available."]);
        }

        if($amount_paid > $preop_total->balance){
            JSONResponse(['type'=>'error', 'msg'=>"Amount Paid can't be greater than $tender_typeDesc amount"]);
        }


        /*=====================  VALIDATION ENDS HERE ==========================*/
        

        $this->db->trans_start(); 

        $receipt_no = $this->app_model->payment_docNo(true);
        $preops         = $this->app_model->get_preops_with_balance($tenant_id, $tender_typeDesc);
        

        //APPLY PAYMENT TO INVOICES
        foreach ($payment_docs as $key => $doc) {
            $doc = (object) $doc;
            
            if($amount_paid <= 0){
                break;
            }

            $inv_balance = $doc->balance > $amount_paid ? $amount_paid : $doc->balance;

            foreach($preops as $prekey => $preop){
                if($inv_balance <= 0) break;
                if($preop->balance <= 0) continue;


                $doc_payment_amt    = $preop->balance >= $inv_balance ? $inv_balance : $preop->balance;
                $preop->balance     -= $doc_payment_amt;
                $inv_balance        -= $doc_payment_amt;               
                

                $amount_paid        = round($amount_paid - $doc_payment_amt, 2);

                $payment_docs[$key]['amount_paid'] +=  $doc_payment_amt;
                
                $sl_data = [];

                $sl_data['debit'] = array(
                    'posting_date'      =>  $payment_date,
                    'transaction_date'  =>  $transaction_date,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $preop->ref_no,
                    'doc_no'            =>  $receipt_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $preop->gl_accountID,
                    'company_code'      =>  $store->company_code,
                    'department_code'   =>  '01.04',
                    'debit'             =>  $doc_payment_amt,
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $sl_data['credit'] = array(
                    'posting_date'      =>  $payment_date,
                    'transaction_date'  =>  $transaction_date,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $doc->ref_no,
                    'doc_no'            =>  $receipt_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $doc->gl_accountID,
                    'company_code'      =>  $store->company_code,
                    'department_code'   =>  '01.04',
                    'credit'            =>  -1 * $doc_payment_amt,
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                foreach ($sl_data as $data) {
                    $this->db->insert('general_ledger', $data);
                    $this->db->insert('subsidiary_ledger', $data);
                }

                
                //SAVE TO LEDGER
                $inv = $this->app_model->getLedgerFirstResultByDocNo($tenant_id, $doc->doc_no);
                if(!empty($inv)){
                    $ledger_data = array(
                        'posting_date'  =>  $payment_date,
                        'document_type' =>  'Payment',
                        'ref_no'        =>  $inv->ref_no,
                        'doc_no'        =>  $receipt_no,
                        'tenant_id'     =>  $tenant_id,
                        'contract_no'   =>  $contract_no,
                        'description'   =>  $inv->description,
                        'debit'         =>  $doc_payment_amt,
                        'balance'       =>  0
                    );

                    $this->app_model->insert('ledger', $ledger_data);
                }
            }
        }

        //SETTING TO DATA TO AVOID ERRORS IN createPaymentDocsFile METHOD
        $tender_typeCode = null;
        $check_type = null;
        $bank_code = null;
        $bank_name = null;
        $check_no = null;
        $check_due_date = null;
        $advance_amount = 0;
        $payor = $trade_name;

    
        /*============= SAVE TO PAYMENT SCHEME ============ */
        $pmt_display = (object) compact(
            'tenant', 
            'store', 
            'receipt_no',
            'soa_no',
            'payment_date',
            'remarks',
            'total_payable',
            'payment_docs',
            'tender_typeCode',
            'tender_typeDesc',
            'check_type',
            'bank_code',
            'bank_name',
            'tender_amount',
            'check_no',
            'balance',
            'check_due_date',
            'advance_amount',
            'payor',
            'payee'
        );

        $payment_report =  $this->createPaymentDocsFile($pmt_display);

        $paymentScheme = array(
            'tenant_id'        =>   $tenant_id,
            'contract_no'      =>   $contract_no,
            'tenancy_type'     =>   $tenancy_type,
            'receipt_no'       =>   $receipt_no,
            'tender_typeCode'  =>   '',
            'tender_typeDesc'  =>   $tender_typeDesc,
            'soa_no'           =>   $soa_no,
            'billing_period'   =>   $billing_period,
            'amount_due'       =>   $total_payable,
            'amount_paid'      =>   $tender_amount,
            'bank'             =>   '',
            'check_no'         =>   '',
            'check_date'       =>   '',
            'payor'            =>   $trade_name,
            'payee'            =>   $payee,
            'receipt_doc'      =>   $payment_report
        );

        $this->db->insert('payment_scheme', $paymentScheme);

        //$last_soa = $this->app_model->getLastSOA();

        //INSERT TO PAYMENT
        $paymentData = array(
            'posting_date' =>   $payment_date,
            'soa_no'       =>   $soa_no,
            'amount_paid'  =>   $tender_amount,
            'tenant_id'    =>   $tenant_id,
            'doc_no'       =>   $receipt_no
        );

        $this->db->insert('payment', $paymentData);
        /*============= END OF SAVE TO PAYMENT SCHEME ============ */



        //CHECK IF DELAYED PAYMENT
        /*if (!$this->app_model->is_penaltyExempt($tenant_id) && $soa->billing_period != 'Upon Signing of Notice')
        {
            $collection_date  = $soa->collection_date;

            if (date('Y-m-d', strtotime($payment_date)) > date('Y-m-d', strtotime($collection_date . "+ 1 day")))
            {
                $daysOfMonth         = date('t', strtotime($payment_date));
                $daydiff             = floor((abs(strtotime($payment_date . "- 1 days") - strtotime($collection_date))/(60*60*24)));
                $sundays             = $this->app_model->get_sundays($collection_date, $payment_date);
                $daydiff             = $daydiff - $sundays;
                $penalty_latepayment = ($tender_amount * .02 * $daydiff) / $daysOfMonth;

                $penaltyEntry = array(
                    'tenant_id'     =>  $tenant_id,
                    'posting_date'  =>  $payment_date,
                    'contract_no'   =>  $contract_no,
                    'doc_no'        =>  $receipt_no,
                    'description'   =>  'Late Payment-' . $trade_name,
                    'amount'        =>  round($penalty_latepayment, 2)
                );
                $this->db->insert('tmp_latepaymentpenalty', $penaltyEntry);
            }
        }*/

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) 
        {
            $this->db->trans_rollback(); 
            $this->app_model->insert('error_log', [
                'action' => 'Saving Payment', 
                'error_msg' => $this->db->_error_message()
            ]);

            JSONResponse(['type'=>'error', 'msg'=>'Something went wrong while posting payment!']);
        }
        
       
        JSONResponse(['type'=>'success', 'msg'=>'Payment successfully posted!', 'file'=>$payment_report]);

    }



    public function advance_payment()
    { 
        //dump($this->session->userdata);

        if($this->session->userdata('user_type') == 'Accounting Staff')
        {
            $data['current_date']   = getCurrentDate();

            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['payee']          = $this->app_model->my_store();
            $data['store_id']       = $this->session->userdata('user_group');

            $data['tender_types']   = json_encode([
                                        ['id' => 1, 'desc' => 'Cash'],
                                        ['id' => 2, 'desc' => 'Check'],
                                        ['id' => 3, 'desc' => 'Bank to Bank'],
                                        ['id' => 80, 'desc' => 'JV payment - Business Unit'],
                                        ['id' => 81, 'desc' => 'JV payment - Subsidiary'],
                                        ['id' => 11, 'desc' => 'Unidentified Fund Transfer'],
                                        ['id' => 12, 'desc' => 'Internal Payment']
                                    ]);

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/accounting/advance_payment');
            $this->load->view('leasing/footer');
        } 
    }



    public function save_advancePayment(){

        /*=====================  SETTING VALUES STARTS HERE ==========================*/

        $tenancy_type       = $this->sanitize($this->input->post('tenancy_type'));
        $trade_name         = $this->sanitize($this->input->post('trade_name'));
        $tenant_id          = $this->sanitize($this->input->post('tenant_id'));
        $contract_no        = $this->sanitize($this->input->post('contract_no'));
        $tenant_address     = $this->sanitize($this->input->post('tenant_address'));
        $payment_date       = $this->sanitize($this->input->post('payment_date'));
        $remarks            = $this->sanitize($this->input->post('remarks'));
        $tender_typeCode    = $this->sanitize($this->input->post('tender_typeCode'));
        $receipt_no         = $this->sanitize($this->input->post('receipt_no'));
        $amount_paid        = $this->sanitize($this->input->post('amount_paid'));
        $tender_amount      = $amount_paid;
        $receipt_no         = "PR".$receipt_no;

        $transaction_date   = getCurrentDate();
        $payment_date       = date('Y-m-d', strtotime($payment_date));


        //IF NOT INTERNAL PAYMENT
        $bank_code          = $this->sanitize($this->input->post('bank_code'));
        $bank_name          = $this->sanitize($this->input->post('bank_name'));
        $payor              = $this->sanitize($this->input->post('payor'));
        $payee              = $this->sanitize($this->input->post('payee'));

        //IF INTERNAL PAYMENT
        $ip_store_code          = $this->sanitize($this->input->post('store_code'));
        $ip_store_name          = $this->sanitize($this->input->post('store_name'));
        

        //IF CHECK
        $check_type          = $this->sanitize($this->input->post('check_type'));
        $bank_code          = $this->sanitize($this->input->post('bank_code'));
        $account_no         = $this->sanitize($this->input->post('account_no'));
        $account_name       = $this->sanitize($this->input->post('account_name'));
        $check_no           = $this->sanitize($this->input->post('check_no'));
        $check_date         = $this->sanitize($this->input->post('check_date'));
        $check_due_date     = $this->sanitize($this->input->post('check_due_date'));
        $expiry_date        = $this->sanitize($this->input->post('expiry_date'));
        $check_class        = $this->sanitize($this->input->post('check_class'));
        $check_category     = $this->sanitize($this->input->post('check_category'));
        $customer_name      = $this->sanitize($this->input->post('customer_name'));
        $check_bank         = $this->sanitize($this->input->post('check_bank'));
        $check_date         = date('Y-m-d', strtotime($check_date));
        $check_due_date     = date('Y-m-d', strtotime($check_due_date));
        $expiry_date        = date('Y-m-d', strtotime($expiry_date));

        /*=====================  SETTING VALUES ENDS HERE ==========================*/

        /*=====================  VALIDATION STARTS HERE ==========================*/


        $this->form_validation->set_rules('tenancy_type', 'Tenancy Type', 'required|in_list[Short Term Tenant,Long Term Tenant]');
        $this->form_validation->set_rules('trade_name', 'Trade Name', 'required');
        $this->form_validation->set_rules('tenant_id', 'Tenant ID', 'required');
        $this->form_validation->set_rules('contract_no', 'Contract No.', 'required');
        $this->form_validation->set_rules('tenant_address', 'Tenant Address', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
        $this->form_validation->set_rules('tender_typeCode', '', 'required|in_list[1,2,3,11,12,80,81]');
        $this->form_validation->set_rules('receipt_no', 'Reciept No.', 'required');
        $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required|numeric');



        if(in_array($tender_typeCode, [1,2,3,11])){
            $this->form_validation->set_rules('bank_code', 'Bank Code', 'required');
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
            $this->form_validation->set_rules('payor', 'Payor', 'required');
            $this->form_validation->set_rules('payee', 'Payee', 'required');
        }else{
            if($tender_typeCode == 12){
                $this->form_validation->set_rules('store_code', 'Store Code', 'required');
                $this->form_validation->set_rules('store_name', 'Store Name', 'required');
            }

            $bank_code = null;
            $bank_name = null;
        }

        if($tender_typeCode == '2'){
            if($this->session->userdata('cfs_logged_in')){
                $this->form_validation->set_rules('account_no', 'Account No.', 'required');
                $this->form_validation->set_rules('account_name', 'Account Name', 'required');
                //$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');
                $this->form_validation->set_rules('check_class', 'Check Class', 'required');
                $this->form_validation->set_rules('check_category', 'Check Category', 'required');
                $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('check_bank', 'Check Bank', 'required');
            }
            $this->form_validation->set_rules('check_type', 'Check Type', 'required|in_list[DATED CHEC, POST DATED CHECK]');
            $this->form_validation->set_rules('check_no', 'Check No.', 'required');
            $this->form_validation->set_rules('check_date', 'Check Date', 'required'); 

            if(!in_array($check_type, ['DATED CHECK', 'POST DATED CHECK'])){
                JSONResponse(['type'=>'error', 'msg'=>'Invalid Check Type!']);
            }

            if($check_type  == 'POST DATED CHECK') {
                $this->form_validation->set_rules('check_due_date', 'Check Due Date', 'required');

                if(!validDate($check_due_date)){
                    JSONResponse(['type'=>'error', 'msg'=>'Check Due Date is not valid!']);
                }  
            }

            if($this->session->userdata('cfs_logged_in') && !validDate($expiry_date)){
                $expiry_date = '';
               //JSONResponse(['type'=>'error', 'msg'=>'Check Expiry Date is not valid!']);
            }

        }


        if ($this->form_validation->run() == FALSE)
        {
            JSONResponse(['type'=>'error', 'msg'=>validation_errors()]);
        }

        /*$soa  = $this->app_model->get_soaDetails($tenant_id, $soa_no);

        if(empty($soa)){
            JSONResponse(['type'=>'error', 'msg'=>'SOA not found!']);
        }*/


        $tender_types = [
            '1'     =>'Cash', 
            '2'     =>'Check', 
            '3'     =>'Bank to Bank', 
            '80'    =>'JV payment - Business Unit',
            '81'    =>'JV payment - Subsidiary',
            '11'    => 'Unidentified Fund Transfer',
            '12'    =>'Internal Payment'
        ];
        $tender_typeDesc = $tender_types[$tender_typeCode];

        if(!in_array($tender_typeCode, [1,2,3,11,12,80,81])){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Tender Type!']);
        }

        if(in_array($tender_typeCode, ['2','80','81'])){
            $upload = new FileUpload;

            $supp_doc= $upload->validate('supp_doc', 'Supporting Documents')
                ->required()
                ->multiple()
                ->get();

            if($upload->has_error()){
                JSONResponse(['type'=>'error', 'msg'=>$upload->get_errors('<br>')]);
            }
        }

        if(in_array($tender_typeCode, ['1','2','80','81'])){
            
            if($this->app_model->checkPaymentReceiptExistence($receipt_no)){
                JSONResponse(['type'=>'error', 'msg'=>'Payment Receipt already used!']);
            }
        }

        if(!validDate($payment_date)){
            JSONResponse(['type'=>'error', 'msg'=>'Payment Date is not valid!']);
        }

       

        if($amount_paid <= 0){
            JSONResponse(['type'=>'error', 'msg'=>'Amount paint can\'t be 0.00']);
        }

        $tenant   = $this->app_model->getTenantByTenantID($tenant_id);
        try{
            $store_code             = $this->app_model->tenant_storeCode($tenant_id);
            $store                  = $this->app_model->getStore($store_code);

            if (empty($store_code ) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            } 
        }catch(Exception $e){
            JSONResponse(['type'=>'error', 'msg'=>'Invalid Tenant! Tenant might be terminated.']);
        }


        /*=====================  VALIDATION ENDS HERE ==========================*/


        //SET UFT OR IP PAYMENT DOC. NO.
        switch ($tender_typeCode) {
            case '11':
                $receipt_no = $this->app_model->generate_UTFTransactionNo();
                break;
            case '12':
                $receipt_no = $this->app_model->generate_InternalTransactionNo();
                break;
            default:
                $receipt_no;
                break;
        }

        $this->db->trans_start();


        //INSERT URI IF HAS ADVANCE
        $advance_amount = $amount_paid;
        if($advance_amount > 0){

            $sl_data    = [];
            $gl_code        = '';
            $ft_ref         = NULL;
            $debit_status   = NULL;
            $credit_status  = NULL;
            $uri_ref_no     = $this->app_model->gl_refNo();
            $due_date = NULL;


            //CASH | BANK TO BANK 
            if($tender_typeCode == 1 || $tender_typeCode == 3){
                $gl_code = '10.10.01.01.02';

            //CHECK
            }elseif($tender_typeCode == 2){
                $gl_code = $check_type == 'POST DATED CHECK' ? '10.10.01.03.07.01' : '10.10.01.01.02';

                if($check_type == 'POST DATED CHECK'){
                    //$debit_status = 'PDC';
                    $credit_status = 'PDC';
                    $ft_ref = $this->app_model->generate_ClosingRefNo();
                    $due_date = $check_due_date;
                }

            //JV payment - Business Unit
            }elseif($tender_typeCode == 80){
                $gl_code = $this->app_model->bu_entry();

            //JV payment - Subsidiary
            }elseif($tender_typeCode == 81){
                $gl_code = '10.10.01.03.11';

            //UFT
            }elseif($tender_typeCode == 11){
                $gl_code = '10.10.01.01.02';
                $credit_status = 'URI Clearing';
                $ft_ref = $this->app_model->generate_ClosingRefNo();

            //INTERNAL PAYMENT
            }else{
                $gl_code    = '10.10.01.03.04';
                $debit_status      = $ip_store_name;
                $credit_status     = 'ARNTI';
                $ft_ref = $this->app_model->generate_ClosingRefNo();
            }


            $sl_data['debit'] = array(
                'posting_date'      =>  $payment_date,
                'due_date'          =>  $due_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Payment',
                'ref_no'            =>  $uri_ref_no,
                'doc_no'            =>  $receipt_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                'company_code'      =>  $store->company_code,
                'department_code'   =>  '01.04',
                'debit'             =>  $advance_amount,
                'bank_name'         =>  $tender_typeCode != 12 ? $bank_name : null,
                'bank_code'         =>  $tender_typeCode != 12 ? $bank_code : null,
                'status'            =>  $debit_status,
                'ft_ref'            =>  $ft_ref,
                'prepared_by'       =>  $this->session->userdata('id')
            );

            $sl_data['credit'] = array(
                'posting_date'      =>  $payment_date,
                'due_date'          =>  $due_date,
                'transaction_date'  =>  $transaction_date,
                'document_type'     =>  'Payment',
                'ref_no'            =>  $uri_ref_no,
                'doc_no'            =>  $receipt_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                'company_code'      =>  $store->company_code,
                'department_code'   =>  '01.04',
                'credit'            =>  -1 * $advance_amount,
                'bank_name'         =>  $tender_typeCode != 12 ? $bank_name : null,
                'bank_code'         =>  $tender_typeCode != 12 ? $bank_code : null,
                'status'            =>  $credit_status,
                'ft_ref'            =>  $ft_ref,
                'prepared_by'       =>  $this->session->userdata('id')
            );

            foreach ($sl_data as $key => $data) {
                $this->db->insert('general_ledger', $data);
                $this->db->insert('subsidiary_ledger', $data);
            }


            // For Montly Receivable Report
            $mon_rec_report_data = array(
                'tenant_id'     =>  $tenant_id,
                'doc_no'        =>  $receipt_no,
                'posting_date'  =>  $payment_date,
                'description'   =>  'Advance Payment',
                'amount'        =>  $advance_amount
            );

            $this->app_model->insert('monthly_receivable_report', $mon_rec_report_data);

            $ledger_data = array(
                'posting_date'       =>  $payment_date,
                'transaction_date'   =>  $transaction_date,
                'document_type'      =>  'Advance Payment',
                'doc_no'             =>  $receipt_no,
                'ref_no'             =>  $this->app_model->generate_refNo(),
                'tenant_id'          =>  $tenant_id,
                'contract_no'        =>  $contract_no,
                'description'        =>  'Advance Payment-' . $trade_name,
                'debit'              =>  $advance_amount,
                'credit'             =>  0,
                'balance'            =>  $advance_amount
            );

            $this->app_model->insert('ledger', $ledger_data);

            // For Accountability Report
            if( in_array($tender_typeCode, ['1','2','3','80','81']) && $this->session->userdata('cfs_logged_in')){

                $this->app_model->insert_accReport($tenant_id, 'Advance Deposit', $advance_amount, $payment_date, $tender_typeDesc);
            }
        }

        $adv_amt_for_preop = $amount_paid;
        

        

        //SAVE SUPPORTING DOCUMENT
        if(in_array($tender_typeCode, ['2','80','81'])){

            $targetPath  = getcwd() . '/assets/payment_docs/';

            foreach ($supp_doc as $key => $supp) {

                //Setup our new file path
                $filename    = $tenant_id . time() . $supp['name'];
                move_uploaded_file($supp['tmp_name'], $targetPath.$filename);

                $supp_doc_data = [
                    'tenant_id'     => $tenant_id, 
                    'file_name'     => $filename, 
                    'receipt_no'    => $receipt_no
                ];

                $this->db->insert('payment_supportingdocs', $supp_doc_data);

            }
        }

        //GET LAST SOA TO REFLECT PAYMENT
        $soa_no             = "";
        $billing_period     = "";

        $last_soa = $this->app_model->getLastestSOA($tenant_id);

        if(!empty($last_soa)){
            $soa_no             = $last_soa->soa_no;
            $billing_period     = $last_soa->billing_period;
        }

        //INSERT TO PAYMENT SCHEME
        if (in_array($tender_typeCode, ['1','2', '3','80','81']))
        {   
            $check_date = $tender_typeCode == 2 ? $check_date : null;
            $pmt_display = (object) compact(
                'tenant', 
                'store', 
                'receipt_no',                
                'payment_date',
                'remarks',
                'payment_date',
                'tender_typeCode',
                'tender_typeDesc',
                'check_type',
                'bank_code',
                'bank_name',
                'tender_amount',
                'check_no',
                'check_due_date',
                'check_date',
                'advance_amount',
                'payor',
                'payee'
            );

            $payment_report =  $this->createAdvancePaymentDocsFile($pmt_display);

            /*$check_date = ($tender_typeCode == 2 ?
                ($check_type == 'POST DATED CHECK'? $check_due_date : $payment_date) 
            : null);*/
 
            

            $paymentScheme = array(
                'tenant_id'        =>   $tenant_id,
                'contract_no'      =>   $contract_no,
                'tenancy_type'     =>   $tenancy_type,
                'receipt_no'       =>   $receipt_no,
                'tender_typeCode'  =>   $tender_typeCode,
                'tender_typeDesc'  =>   $tender_typeDesc,
                'soa_no'           =>   $soa_no,
                'billing_period'   =>   $billing_period,
                'amount_due'       =>   0,
                'amount_paid'      =>   $tender_amount,
                'bank'             =>   $bank_name,
                'check_no'         =>   $tender_typeCode == 2 ? $check_no : null,
                'check_date'       =>   $check_date,
                'payor'            =>   $payor,
                'payee'            =>   $payee,
                'receipt_doc'      =>   $payment_report
            );

            $this->db->insert('payment_scheme', $paymentScheme);



            /*======================  CCM DATA =================================== */

            if ($tender_typeCode == '2' && $this->session->userdata('cfs_logged_in')) 
            {
                $this->load->model('ccm_model');

                $customer_id = $this->ccm_model->check_customer($customer_name);
                $checksreceivingtransaction_id = $this->ccm_model->checksreceivingtransaction();


                $ccm_data = array(
                    'checksreceivingtransaction_id' => $checksreceivingtransaction_id, 
                    'customer_id'                   => $customer_id,
                    'businessunit_id'               => $this->ccm_model->get_BU(),
                    'department_from'               => '12',
                    'leasing_docno'                 => $receipt_no,
                    'check_no'                      => $check_no,
                    'check_class'                   => $check_class,
                    'check_category'                => $check_category,
                    'check_expiry'                  => $expiry_date,
                    'check_date'                    => $check_date,
                    'check_received'                => $transaction_date,
                    'check_type'                    => $check_type,
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
        }

        

        //INSERT TO PAYMENT
        $paymentData = array(
            'posting_date' =>   $payment_date,
            'soa_no'       =>   $soa_no,
            'amount_paid'  =>   $tender_amount,
            'tenant_id'    =>   $tenant_id,
            'doc_no'       =>   $receipt_no
        );

        $this->db->insert('payment', $paymentData);


        //INSERT UFT
        if($tender_typeCode == '11'){
            $data_utf = array(
                'tenant_id'      => $tenant_id,
                'bank_code'      => $bank_code,
                'bank_name'      => $bank_name,
                'posting_date'   => $payment_date,
                'amount_payable' => 0,
                'amount_paid'    => $tender_amount
            );
            $this->db->insert('uft_payment', $data_utf);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) 
        {
            $this->db->trans_rollback(); 
            $this->app_model->insert('error_log', [
                'action' => 'Saving Payment', 
                'error_msg' => $this->db->_error_message()
            ]);

            JSONResponse(['type'=>'error', 'msg'=>'Something went wrong while posting payment!']);
        }
        

        if (in_array($tender_typeCode, ['1','2', '3','80','81'])){
            JSONResponse(['type'=>'success', 'msg'=>'Payment successfully posted!', 'file'=>$payment_report]);
        }
            

        JSONResponse(['type'=>'success', 'msg'=>'Payment successfully posted!']);    
    }


    function createAdvancePaymentDocsFile($pmt){

        $pdf = new FPDF('p','mm','A4');
        $pdf->AddPage();
        $pdf->setDisplayMode ('fullpage');
        $logoPath = getcwd() . '/assets/other_img/';


        $store = $pmt->store;
        $tenant = $pmt->tenant;

        $pdf->cell(20, 20, $pdf->Image($logoPath . $store->logo, 100, $pdf->GetY(), 15), 0, 0, 'C', false);
        $pdf->ln();
        $pdf->setFont ('times','B',14);
        $pdf->cell(75, 6, " ", 0, 0, 'L');
        $pdf->cell(40, 10, strtoupper($store->store_name), 0, 0, 'L');
        $store_name = $store->store_name;
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(35, 35, 35);
        $pdf->cell(35, 6, " ", 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont ('times','',14);
        $pdf->cell(15, 0, " ", 0, 0, 'L');
        $pdf->cell(0, 10, $store->store_address, 0, 0, 'C');

        $pdf->ln();
        $pdf->ln();


        $pdf->setFont('times','',10);
        $pdf->cell(30, 6, "Receipt No.", 0, 0, 'L');
        $pdf->cell(60, 6, $pmt->receipt_no, 1, 0, 'L');
        $pdf->cell(5, 6, " ", 0, 0, 'L');
        $pdf->cell(30, 6, "Date", 0, 0, 'L');
        $pdf->cell(60, 6, $pmt->payment_date, 1, 0, 'L');

        $pdf->ln();
        $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
        $pdf->cell(60, 6, $tenant->tenant_id, 1, 0, 'L');
        $pdf->cell(5, 6, " ", 0, 0, 'L');
        $pdf->cell(30, 6, "Remarks", 0, 0, 'L');
        $pdf->cell(60, 6, $pmt->remarks, 1, 0, 'L');

        $pdf->ln();
        $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
        $pdf->cell(60, 6, $tenant->trade_name, 1, 0, 'L');
        $pdf->cell(5, 6, " ", 0, 0, 'L');
        $pdf->cell(30, 6, "Total Payable", 0, 0, 'L');
        $pdf->cell(60, 6, number_format(0, 2), 1, 0, 'L');
        $pdf->ln();

        $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
        $pdf->cell(60, 6, $tenant->corporate_name, 1, 0, 'L');
        $pdf->cell(5, 6, " ", 0, 0, 'L');
        $pdf->ln();

        $pdf->cell(30, 6, "TIN", 0, 0, 'L');
        $pdf->cell(60, 6, $tenant->tin, 1, 0, 'L');

        $pdf->ln();
        $pdf->ln();

        $pdf->ln();
        $pdf->setFont ('times','B',10);
        $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store->store_name) , 0, 0, 'R');
        $pdf->ln();
        $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont ('times','B',16);
        $pdf->cell(0, 6, "Payment Receipt", 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();


        $pdf->ln();


        // =================== Receipt Charges Table ============= //
        $pdf->setFont('times','B',10);
        /*$pdf->cell(20, 8, "Doc. Type", 0, 0, 'L');*/
        $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
        $pdf->cell(80, 8, "Charges Type", 0, 0, 'L');
        $pdf->cell(30, 8, "Posting Date", 0, 0, 'C');
        $pdf->cell(30, 8, "Amount Paid", 0, 0, 'R');
        $pdf->setFont('times','',10);


        $pdf->ln();
        $pdf->cell(30, 8, $pmt->receipt_no, 0, 0, 'C');
        $pdf->cell(80, 8, 'Advance Payment', 0, 0, 'L');
        $pdf->cell(30, 8, $pmt->payment_date, 0, 0, 'C');
        $pdf->cell(30, 8, number_format($pmt->advance_amount, 2), 0, 0, 'R');


        $pdf->ln();
        $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
        $pdf->ln();


        $pdf->setFont('times','B',10);
        $pdf->cell(150, 8, "Payment Scheme: ", 0, 0, 'L');
        $pdf->cell(100, 8, "Payment Date: " . $pmt->payment_date, 0, 0, 'L');
        $pdf->ln();

        $pdf->setFont('times','',10);
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Description: ", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt->tender_typeCode != 2 ? $pmt->tender_typeDesc : ucwords($pmt->check_type)), 0, 0, 'L');
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format(0, 2), 0, 0, 'L');
        $pdf->ln();

        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
        $pdf->cell(60, 4, (in_array($pmt->tender_typeCode, [1,2,3,11]) ?  $pmt->bank_name : 'N/A'), 0, 0, 'L');
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format($pmt->tender_amount, 2), 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt->tender_typeCode == 2  ? $pmt->check_no : 'N/A'), 0, 0, 'L');
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format(0, 2), 0, 0, 'L');  

        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');

        $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt->tender_typeCode == 2 ? $pmt->check_date : 'N/A') , 0, 0, 'L');
        
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format($pmt->advance_amount, 2), 0, 0, 'L');

        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Check Due Date:: ", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt->tender_typeCode == 2 && $pmt->check_type == 'POST DATED CHECK' ? $pmt->check_due_date : 'N/A'), 0, 0, 'L');


        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->payor, 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->payee, 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(20, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "OR #: ", 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->receipt_no, 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();


        $pdf->ln();
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

        $file_name =   $tenant->tenant_id . time() . '.pdf';

        $pdf->Output('assets/pdf/' . $file_name , 'F');


        return $file_name;
    }  

    public function prepost_old_soa_action($tenant_id, $date = null){

        $res = $this->prepost_old_soa($tenant_id, $date);

        die($res > 0 ? "Success! $res invoice/s preposted." : "No invoice preposted!");
    }


    private function prepost_old_soa($tenant_id, $date = null){

        $success = 0;
        $exist = 0;

        //$year = date('Y-m');

        //if(!$this->app_model->tenant_has_record_in_soa_line($tenant_id, $date)){

        $data = $this->app_model->get_old_soa_invoices_with_balance($tenant_id, $date);
        if($data){
            $soa_no = $data[0]->soa_no;

            //CHECK HERE IF SOA EXIST
            if(!$this->app_model->soa_no_exist_in_soa_line($soa_no, $tenant_id)){

                foreach ($data as $key => $inv) {

                    if(!$this->app_model->prepost_exist_in_soa_line($soa_no, $inv->doc_no)){
                        $soa_line = [
                            'soa_no'    =>$soa_no,
                            'doc_no'    =>$inv->doc_no,
                            'amount'    =>$inv->inv_amount,
                            'tenant_id' =>$inv->tenant_id
                        ];
                        $this->db->insert('soa_line', $soa_line);

                        $success++;
                    }else{
                        $exist++;
                    }
                }

                $preop_data = $this->app_model->get_tmp_preop_invoice_with_balance($tenant_id, $date);

                if($preop_data){

                    foreach ($preop_data as $key => $inv) {
                        $soa_line = [
                            'soa_no'    =>$soa_no,
                            'doc_no'    =>$inv->doc_no,
                            'amount'    =>$inv->amount,
                            'tenant_id' =>$inv->tenant_id,
                            'preop_id'  => $inv->id
                        ];

                        $this->db->insert('soa_line', $soa_line);

                        $success++;
                    }
                }
            }
            
        }
      

        return $success > 0;
    }

    public function get_managers_key(){
        $username   = $this->sanitize($this->input->post('username'));
        $password   = $this->sanitize($this->input->post('password'));
        $for        = $this->sanitize($this->input->post('for'));

        if(empty($username) || empty($password) || empty($for)){
            JSONResponse(['type'=>'error', 'msg'=>'Please input the required fields!']);
        }

        $store_name    = $this->app_model->my_store();

        if($this->app_model->managers_key($username, $password, $store_name)){
            //$token = openssl_random_pseudo_bytes(16);
            //$token = bin2hex($token);

            $token = md5(microtime());


            $manager =   $this->app_model->get_user_by_credentials($username, $password);

            $this->session->set_userdata($for, (object)['manager_id'=>$manager->id, 'token'=>$token]);
            JSONResponse(['type'=>'success', 'msg'=>"Manager's key applied!", 'token'=>$token]);

        }

        JSONResponse(['type'=>'error', 'msg'=>'Invalid Credentials!']);

        
    }

    public function recon_sys_vs_nav(){
        $data['current_date']   = getCurrentDate();

        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['stores']         = $this->app_model->get_stores();
        $data['gl_accounts']    = $this->app_model->get_gl_accounts();


        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/recon_sys_vs_nav');
        $this->load->view('leasing/footer');
    }

    public function generate_recon_sys_vs_nav_report(){
        $store      = $this->sanitize($this->input->post('store'));
        $gl_ids     = $this->input->post('gl_ids');
        $date_from  = $this->sanitize($this->input->post('date_from'));
        $date_to    = $this->sanitize($this->input->post('date_to'));

        $date_from  = date('Y-m-d', strtotime($date_from));
        $date_to    = date('Y-m-d', strtotime($date_to));

        if($this->session->userdata('user_group') != 0){
            $store = $this->app_model->get_storeDetails();

            if(empty($store)){
                $store = '';
            }else{
                $store = $store[0]['store_code'];
            }
           
        }
    
        $result = $this->app_model->get_data_recon_sys_vs_nav_report($store, $gl_ids, $date_from, $date_to);

        $csv_data[] = [
            'ID',
            'Tenant Code',
            'Trade Name',
            'Document Type',
            'GL Account',
            'Doc. No.',
            'Ref. No.',
            'Posting Date',
            'Due Date',
            'Bank Code',
            'Bank Name',
            'Debit',
            'Credit'
        ];

        foreach ($result as $d) {
            $d = (object) $d;

            $csv_data[] =  [
                $d->id,
                $d->tenant_id,
                $d->trade_name,
                $d->document_type,
                $d->description,
                $d->doc_no,
                $d->ref_no,
                $d->posting_date,
                $d->due_date,
                $d->bank_code,
                $d->bank_name,
                $d->debit,
                $d->credit,
                
            ];
        }

        $csv_data =  arrayToString($csv_data);

        //$file_name = "GL Report - $store ".strtoupper(uniqid()).'.csv';
        $file_name = "GL Report - $store ". date('Ymd', strtotime($date_from)).'-'.date('Ymd', strtotime($date_to)).'.csv';

        download_send_headers($file_name, $csv_data);

    }


    public function recon_sys_vs_bank(){
        $data['current_date']   = getCurrentDate();

        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['stores']         = $this->app_model->get_stores();
        $data['banks']          = $this->app_model->get_banks();


        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/recon_sys_vs_bank');
        $this->load->view('leasing/footer');
    }

    public function generate_recon_sys_vs_bank_report(){
        //$store      = $this->sanitize($this->input->post('store'));
        //$gl_ids     = $this->input->post('gl_ids');

        $bank_id     = $this->input->post('bank_id');
        $date_from  = $this->sanitize($this->input->post('date_from'));
        $date_to    = $this->sanitize($this->input->post('date_to'));

        $date_from  = date('Y-m-d', strtotime($date_from));
        $date_to    = date('Y-m-d', strtotime($date_to));

       /* $store = '';*/

        /*if($this->session->userdata('user_group') != 0){
            $store = $this->app_model->get_storeDetails();

            if(empty($store)){
                $store = '';
            }else{
                $store = $store[0]['store_code'];
            }
           
        }*/

        $bank = $this->app_model->get_bank_by_id($bank_id);

        if(!$bank){
            die('INVALID BANK ID!');
        }
    
        $result = $this->app_model->get_data_recon_sys_vs_bank_report($bank, $date_from, $date_to);

        $csv_data[] = [
            'ID',
            'Tenant Code',
            'Trade Name',
            'Document Type',
            'GL Account',
            'Doc. No.',
            'Posting Date',
            'Due Date',
            'Bank Code',
            'Bank Name',
            'Debit',
            'Credit',
            'Amount'
        ];

        foreach ($result as $d) {
            $d = (object) $d;

            $csv_data[] =  [
                $d->id,
                $d->tenant_id,
                $d->trade_name,
                $d->document_type,
                $d->description,
                $d->doc_no,
                $d->posting_date,
                $d->due_date,
                $d->bank_code,
                $d->bank_name,
                $d->debit,
                $d->credit,
                $d->amount,
            ];
        }

        $csv_data =  arrayToString($csv_data);

        //$file_name = "GL Report - $store ".strtoupper(uniqid()).'.csv';
        $file_name = "SYS VS BANK REPORT - $bank->store_code ". date('Ymd', strtotime($date_from)).'-'.date('Ymd', strtotime($date_to)).' '. time().'.csv';

        download_send_headers($file_name, $csv_data);
    }
    

    public function invoice_override_history(){
        $data['current_date']   = getCurrentDate();

        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();


        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/accounting/invoice_override_history');
        $this->load->view('leasing/footer');
    }

    public function get_invoice_override_data($tenant_id = ''){
        $tenant_id = $this->sanitize($tenant_id);

        $data = $this->app_model->get_invoice_override_data($tenant_id);

        JSONResponse($data);
    }












    function test_date_diff($last_due, $due_date){
        $daylen      = 60*60*24;
        dump($daylen);
        $daysDiff    = (strtotime($last_due)-strtotime($due_date))/$daylen;
        dump($daysDiff);
        $daysDiff    = $daysDiff / 20;
        dump($daysDiff);
        $daysDiff    = floor($daysDiff);
        dump($daysDiff);
        $current_due = strtotime($due_date . "-20 days");
        dump($current_due);
    }

    function test_date_diff2($last_due, $due_date){

        $last_due=date_create($last_due);
        $due_date=date_create($due_date);
        $diff=date_diff( $due_date, $last_due);
        dump($last_due);
        dump($due_date);
        dump($diff);
        echo FLOOR((int)$diff->format("%R%a")/20).'<br>';
   

        /*$daylen      = 60*60*24;
        dump($daylen);
        $daysDiff    = (strtotime($last_due)-strtotime($due_date))/$daylen;
        dump($daysDiff);
        $daysDiff    = $daysDiff / 20;
        dump($daysDiff);
        $daysDiff    = floor($daysDiff);
        dump($daysDiff);
        $current_due = strtotime($due_date[$i] . "-20 days");
        dump($current_due);*/
    }

    function testpage(){
        //dump(DecimalToWord::convert(1599.6, 'Pesos', 'Centavos'));
       // dump(DecimalToWord::$formatted);

        $last_soa = $this->app_model->getLastestSOA('ACT-LT000065');

        dd($last_soa);
    }

}
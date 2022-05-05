<?php 
function save_payment()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $response              = array();
            $date                  = new DateTime();
            $timeStamp             = $date->getTimestamp();

            //                     =============== Basic Data ============== //

            $tenant_id             = $this->sanitize($this->input->post('tenant_id'));
            $trade_name            = $this->sanitize($this->input->post('trade_name'));
            $contract_no           = $this->sanitize($this->input->post('contract_no'));
            $tenancy_type          = $this->sanitize($this->input->post('tenancy_type'));
            $receipt_no            = "PR" . strtoupper($this->sanitize($this->input->post('receipt_no')));
            $soa_no                = $this->sanitize($this->input->post('soa_no'));
            $billing_period        = $this->sanitize($this->input->post('billing_period'));
            $date                  = $this->sanitize($this->input->post('curr_date'));
            $remarks               = $this->sanitize($this->input->post('remarks'));
            $total_payable         = $this->sanitize($this->input->post('total'));
            $total_payable         = str_replace(",", "", $total_payable);


            //                     =============== From Table Data ============== //
            $charge_id             = $this->input->post('charge_id');
            $description           = $this->input->post('desc');
            $doc_no                = $this->input->post('doc_no');
            $due_date              = $this->input->post('due_date');
            $posting_date          = $this->input->post('posting_date');
            $amount                = $this->input->post('amount');
            $balance               = $this->input->post('balance');
            $retro_doc_no          = $this->input->post('retro_doc_no');
            $preop_doc_no          = $this->input->post('preop_doc_no');
            $penalty_doc_no        = $this->input->post('penalty_doc_no');

            //                     =============== Payment Scheme =============== //

            $tender_typeCode       = $this->sanitize($this->input->post('tender_typeCode'));
            $tender_typeDesc       = $this->sanitize($this->input->post('tender_typeDesc'));
            $amount_paid           = $this->sanitize($this->input->post('amount_paid'));
            $amount_paid           = str_replace(",", "", $amount_paid);
            $bank                  = $this->sanitize($this->input->post('bank'));
            $bank_code             = $this->sanitize($this->input->post('bank_code'));
            $check_no              = $this->sanitize($this->input->post('check_no'));
            $check_date            = $this->sanitize($this->input->post('check_date'));
            $payor                 = $this->sanitize($this->input->post('payor'));
            $payee                 = $this->sanitize($this->input->post('payee'));

            $advanceDeposit_amount = 0.00;

            if ($tender_typeDesc == 'JV payment - Business Unit' || $tender_typeDesc == 'JV payment - Subsidiary')
            {
                $bank = '';
                $bank_code = '';
            }

            $supp_doc = "";


            if (($tender_typeDesc == 'Cash' || $tender_typeDesc == 'Check') && ($bank == '' || $bank == '? undefined:undefined ?'))
            {
                $response['msg'] = 'Required';
            }
            else
            {
                if ($amount_paid > 0)
                {

                    if ($tender_typeDesc != 'Cash')
                    {
                        for ($i=0; $i < count($_FILES["supp_doc"]['name']); $i++)
                        {
                            $targetPath  = getcwd() . '/assets/payment_docs/';
                            $tmpFilePath = $_FILES['supp_doc']['tmp_name'][$i];
                            //Make sure we have a filepath
                            if ($tmpFilePath != "")
                            {
                                //Setup our new file path
                                $filename    = $tenant_id . $timeStamp . $_FILES['supp_doc']['name'][$i];
                                $newFilePath = $targetPath . $filename;
                                //Upload the file into the temp dir
                                move_uploaded_file($tmpFilePath, $newFilePath);

                                $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'receipt_no' => $receipt_no);
                                $this->app_model->insert('payment_supportingdocs', $data);
                            }
                        }
                    }

                    $advance_payment         = 0;
                    $file_name               =  $tenant_id . $timeStamp . '.pdf';
                    $tender_amount           = $amount_paid;
                    $amount_paid_for_invoice = $amount_paid;
                    $sledger_amountPaid      = $amount_paid;
                    $gledger_amountPaid      = $amount_paid;



                    $this->db->trans_start(); // Transaction function starts here!!!

                    // ============================ PDF =============================== //

                    $store_code      = $this->app_model->tenant_storeCode($tenant_id);
                    $store_details   = $this->app_model->store_details(trim($store_code));
                    $details_soa     = $this->app_model->details_soa($tenant_id);
                    $lessee_info     = $this->app_model->get_lesseeInfo($tenant_id, $contract_no);
                    $collection_date = $this->app_model->get_collectionDate($soa_no);
                    $daysOfMonth     = date('t', strtotime($date));
                    $pdc_status = '';


                    if ($tender_typeDesc == 'Check')
                    {
                        if ($check_date > $date)
                        {
                            $pdc_status = 'PDC';
                        }
                    }


                    $pdf = new FPDF('p','mm','A4');
                    $pdf->AddPage();
                    $pdf->setDisplayMode ('fullpage');
                    $logoPath = getcwd() . '/assets/other_img/';

                    // ==================== Receipt Header ================== //

                    foreach ($store_details as $detail)
                    {
                        $pdf->cell(20, 20, $pdf->Image($logoPath . $detail['logo'], 100, $pdf->GetY(), 15), 0, 0, 'C', false);
                        $pdf->ln();
                        $pdf->setFont ('times','B',14);
                        $pdf->cell(75, 6, " ", 0, 0, 'L');
                        $pdf->cell(40, 10, strtoupper($detail['store_name']), 0, 0, 'L');
                        $store_name = $detail['store_name'];
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFillColor(35, 35, 35);
                        $pdf->cell(35, 6, " ", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->setFont ('times','',14);
                        $pdf->cell(15, 0, " ", 0, 0, 'L');
                        $pdf->cell(0, 10, $detail['store_address'], 0, 0, 'C');

                        $pdf->ln();
                        $pdf->ln();
                    }


                    foreach ($details_soa as $detail)
                    {
                        $pdf->setFont('times','',10);
                        $pdf->cell(30, 6, "Receipt No.", 0, 0, 'L');
                        $pdf->cell(60, 6, $receipt_no, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Soa No.", 0, 0, 'L');
                        $pdf->cell(60, 6, $soa_no, 1, 0, 'L');

                        $pdf->ln();
                        $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
                        $pdf->cell(60, 6, $tenant_id, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Date", 0, 0, 'L');
                        $pdf->cell(60, 6, $date, 1, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
                        $pdf->cell(60, 6, $trade_name, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Remarks", 0, 0, 'L');
                        $pdf->cell(60, 6, $remarks, 1, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
                        $pdf->cell(60, 6, $detail['corporate_name'], 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Total Payable", 0, 0, 'L');
                        $pdf->cell(60, 6, number_format($total_payable, 2), 1, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 6, "TIN", 0, 0, 'L');
                        $pdf->cell(60, 6, $detail['tin'], 1, 0, 'L');

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


                        $pdf->ln();


                    // =================== Receipt Charges Table ============= //
                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 8, "Doc. Type", 0, 0, 'C');
                        $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
                        $pdf->cell(30, 8, "Charges Type", 0, 0, 'C');
                        $pdf->cell(30, 8, "Posting Date", 0, 0, 'C');
                        $pdf->cell(30, 8, "Due Date", 0, 0, 'C');
                        $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
                        $pdf->setFont('times','',10);


                    if ($doc_no)
                    {
                        for ($i=0; $i < count($doc_no); $i++)
                        {
                            $chargesDetails = $this->app_model->gl_chargesDetails($tenant_id, $doc_no[$i]);
                            foreach ($chargesDetails as $detail)
                            {
                                $pdf->ln();
                                $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                                $pdf->cell(30, 8, $doc_no[$i], 0, 0, 'C');

                                if ($detail['tag'] == 'Basic Rent')
                                {
                                    $pdf->cell(30, 8, "Basic-" . $trade_name, 0, 0, 'C');
                                }
                                elseif ($detail['tag'] == 'Other')
                                {
                                    $pdf->cell(30, 8, "Other-" . $trade_name, 0, 0, 'C');
                                }
                                elseif ($detail['tag'] == 'Penalty')
                                {
                                    $pdf->cell(30, 8, "Penalty-" . $trade_name, 0, 0, 'C');
                                }
                                $pdf->cell(30, 8, $detail['posting_date'], 0, 0, 'C');
                                $pdf->cell(30, 8, $detail['due_date'], 0, 0, 'C');
                                $pdf->cell(30, 8, number_format($detail['balance'], 2), 0, 0, 'R');
                            }
                        }


                        // ================= Penalty deduction ============== //



                        if ($penalty_doc_no)
                        {
                            $penaltyDetails = $this->app_model->get_penalty($tenant_id, $contract_no);

                            foreach ($penaltyDetails as $penalty)
                            {
                                if ($amount_paid > 0)
                                {
                                    $amount_paid -= $penalty['balance'];
                                    $sledger_amountPaid -= $penalty['balance'];

                                    if ($amount_paid >= 0)
                                    {
                                        $penaltyEntry = array(
                                            'posting_date'  =>  $date,
                                            'document_type' =>  'Payment',
                                            'ref_no'        =>  $penalty['ref_no'],
                                            'doc_no'        =>  $receipt_no,
                                            'tenant_id'     =>  $tenant_id,
                                            'contract_no'   =>  $contract_no,
                                            'description'   =>  $penalty['description'],
                                            'debit'         =>  $penalty['balance'],
                                            'balance'       =>  0
                                        );

                                        $this->app_model->insert('ledger', $penaltyEntry);


                                    } else {
                                        $penaltyEntry = array(
                                            'posting_date'  =>  $date,
                                            'document_type' =>  'Payment',
                                            'ref_no'        =>  $penalty['ref_no'],
                                            'doc_no'        =>  $receipt_no,
                                            'tenant_id'     =>  $tenant_id,
                                            'contract_no'   =>  $contract_no,
                                            'description'   =>  $penalty['description'],
                                            'debit'         =>  $amount_paid,
                                            'balance'       =>  $penalty['balance'] - $amount_paid
                                        );

                                        $this->app_model->insert('ledger', $penaltyEntry);
                                    }

                                    //delete from tmp_latepaymentpenalty
                                    $this->app_model->delete_tmp_latepaymentpenalty($penalty['doc_no'], $penalty['tenant_id']);
                                }
                            }


                            $gl_penalties = $this->app_model->get_glPenalties($tenant_id);

                            foreach ($gl_penalties as $penalty)
                            {

                                if ($gledger_amountPaid > 0)
                                {
                                    $gledger_amountPaid -= $penalty['balance'];

                                    if ($gledger_amountPaid >= 0)
                                    {
                                        if ($tender_typeDesc == 'Check')
                                        {
                                            if ($check_date > $date)
                                            {
                                                // If PDC

                                                $penalty_PDC = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $penalty['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'debit'             =>  $penalty['balance'],
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );

                                                $this->app_model->insert('general_ledger', $penalty_PDC);
                                                $this->app_model->insert('subsidiary_ledger', $penalty_PDC);

                                                $penalty_AR = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $penalty['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'credit'            =>  -1 * $penalty['balance'],
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'status'            =>  $pdc_status,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );

                                                $this->app_model->insert('general_ledger', $penalty_AR);
                                                $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                            }
                                            else
                                            {
                                                // If Dated Check

                                                $penalty_CIB = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $penalty['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'debit'             =>  $penalty['balance'],
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );

                                                $this->app_model->insert('general_ledger', $penalty_CIB);
                                                $this->app_model->insert('subsidiary_ledger', $penalty_CIB);

                                                $penalty_AR = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $penalty['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'credit'            =>  -1 * $penalty['balance'],
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );

                                                $this->app_model->insert('general_ledger', $penalty_AR);
                                                $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                            }

                                        }
                                        elseif ($tender_typeDesc == 'Cash')
                                        {
                                            $penalty_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_CIB);

                                            $penalty_AR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_AR);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                        }
                                        elseif ($tender_typeDesc == 'JV payment - Business Unit')
                                        {
                                            $penalty_JV = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_JV);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_JV);

                                            $penalty_AR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_AR);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                        }
                                        elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                                        {
                                            $penalty_JV = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_JV);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_JV);

                                            $penalty_AR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_AR);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                        }
                                        elseif ($tender_typeDesc == 'Bank to Bank')
                                        {
                                            $penalty_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_CIB);

                                            $penalty_AR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_AR);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                        }
                                    }
                                    else
                                    {
                                        $penalty_CIB = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $penalty['ref_no'],
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $gledger_amountPaid,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );

                                        $this->app_model->insert('general_ledger', $penalty_CIB);
                                        $this->app_model->insert('subsidiary_ledger', $penalty_CIB);

                                        $penalty_AR = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $penalty['ref_no'],
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>  -1 * $gledger_amountPaid,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );

                                        $this->app_model->insert('general_ledger', $penalty_AR);
                                        $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                    }
                                }
                            }
                        }


                        // =========================================================================== //


                        // ============== Add Entry to Ledger ============== //

                        if ($doc_no)
                        {
                           $doc_no = $this->sort_ascending($doc_no);
                        }

                        for ($i=0; $i < count($doc_no); $i++)
                        {
                            if ($doc_no[$i] != "")
                            {
                                if ($sledger_amountPaid > 0) // Check if Amount Paid has value
                                {
                                    $ledger_item    = $this->app_model->get_ledgerEntry($tenant_id, $doc_no[$i]);
                                    $expanded_taxes = $this->app_model->get_expandedTaxes($tenant_id, $doc_no[$i]);

                                    foreach ($expanded_taxes as $value) // Add the Expanded Withholding Tax amount to sledger_amountPaid(Binogo ni)
                                    {
                                        $sledger_amountPaid += $value['debit'];
                                        $this->app_model->delete('ledger', 'id', $value['id']);
                                    }

                                    foreach ($ledger_item as $item)
                                    {
                                        if ($sledger_amountPaid >= 1)
                                        {
                                            $credit;
                                            $balance;

                                            if ($sledger_amountPaid >= $item['balance'])
                                            {
                                                $credit  = $item['balance'];
                                                $balance = 0;
                                            }
                                            else
                                            {
                                                $credit  = $sledger_amountPaid;
                                                $balance = abs($item['balance'] - $sledger_amountPaid);
                                            }

                                            $sledger_amountPaid -= $item['balance'];

                                            $entryData = array(
                                                'posting_date'  =>  $date,
                                                'document_type' =>  'Payment',
                                                'ref_no'        =>  $item['ref_no'],
                                                'doc_no'        =>  $receipt_no,
                                                'tenant_id'     =>  $tenant_id,
                                                'contract_no'   =>  $contract_no,
                                                'description'   =>  $item['description'],
                                                'debit'         =>  $credit,
                                                'balance'       =>  $balance,
                                                'due_date'      =>  $item['due_date']
                                            );

                                            $this->app_model->insert('ledger', $entryData);
                                        }
                                    }
                                }
                            }
                        }

                        // ================== Closing GL Accounts ================ //

                        if ($doc_no)
                        {
                            $doc_no = $this->sort_ascending($doc_no);
                        }

                        for ($i=0; $i < count($doc_no); $i++)
                        {
                            if ($doc_no != "")
                            {
                                if ($gledger_amountPaid > 0)
                                {
                                    $gl_entries = $this->app_model->gl_chargesDetails($tenant_id, $doc_no[$i]);

                                    foreach ($gl_entries as $entry)
                                    {
                                        $credit;
                                        if ($gledger_amountPaid >= $entry['balance'])
                                        {
                                            $credit = $entry['balance'];
                                        }
                                        else
                                        {
                                            $credit = $gledger_amountPaid;
                                        }

                                        $gledger_amountPaid -= $entry['balance'];

                                        if ($tender_typeDesc == 'Cash')
                                        {
                                            $entry_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                        }
                                        elseif ($tender_typeDesc == 'Check')
                                        {
                                            if ($check_date > $date)
                                            {
                                                // If PDC

                                                $entry_PDC = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $entry['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'debit'             =>  $credit,
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );
                                                $this->app_model->insert('general_ledger', $entry_PDC);
                                                $this->app_model->insert('subsidiary_ledger', $entry_PDC);
                                            }
                                            else
                                            {
                                                $entry_CIB = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $entry['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'debit'             =>  $credit,
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );
                                                $this->app_model->insert('general_ledger', $entry_CIB);
                                                $this->app_model->insert('subsidiary_ledger', $entry_CIB);

                                            }

                                        }
                                        elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                                        {
                                            $entry_JV = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_JV);
                                            $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                        }
                                        elseif ($tender_typeDesc == 'JV payment - Business Unit')
                                        {
                                            $entry_JV = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_JV);
                                            $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                        }
                                        elseif ($tender_typeDesc == 'Bank to Bank')
                                        {
                                            $entry_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                        }


                                        if ($entry['tag'] == 'Basic Rent')
                                        {
                                            $credit_RR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'status'            =>  $pdc_status,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $credit_RR);
                                            $this->app_model->insert('subsidiary_ledger', $credit_RR);
                                        }
                                        elseif ($entry['tag'] == 'Other')
                                        {
                                            $ar_code = '10.10.01.03.03';
                                            if ($this->app_model->is_AGCSubsidiary($tenant_id)) {
                                                $ar_code = '10.10.01.03.04';
                                            }

                                            $credit_AR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID($ar_code),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'status'            =>  $pdc_status,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $credit_AR);
                                            $this->app_model->insert('subsidiary_ledger', $credit_AR);
                                        }
                                    }
                                }
                            }
                        }


                        // ===== Charges deduction in invoicing table ====== //

                        if ($doc_no)
                        {
                            $doc_no = $this->sort_ascending($doc_no);
                        }

                        for ($i=0; $i < count($doc_no); $i++)
                        {
                            if ($doc_no[$i] != "")
                            {
                                if ($amount_paid_for_invoice > 0) // Check if Amount Paid has value
                                {
                                    $docNo_total             = $this->app_model->total_perDocNo($tenant_id, $doc_no[$i]);
                                    $amount_paid_for_invoice -= $docNo_total;
                                    if ($amount_paid_for_invoice >= 0)
                                    {
                                        $balance = 0;
                                        $this->app_model->updateInvoice_afterPayment($tenant_id, $doc_no[$i], $balance, $receipt_no);
                                    }
                                    else
                                    {
                                        $balance = abs($amount_paid_for_invoice);
                                        $this->app_model->updateInvoice_afterPayment($tenant_id, $doc_no[$i], $balance, $receipt_no);
                                    }
                                }
                            }
                        }
                    }// End of If has document Number



                    if ($retro_doc_no)
                    {
                        // If has Retro Rental
                        if ($amount_paid > 0)
                        {

                            $retro_charges = $this->app_model->get_glRetroPayment($tenant_id);
                            foreach ($retro_charges as $retro)
                            {
                                if ($amount_paid > 0)
                                {
                                    $pdf->ln();
                                    $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                                    $pdf->cell(30, 8, $retro['doc_no'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $retro['tag'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $retro['posting_date'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $retro['due_date'], 0, 0, 'C');
                                    $pdf->cell(30, 8, number_format($retro['balance'], 2), 0, 0, 'R');


                                    $credit;
                                    if ($gledger_amountPaid >= $retro['balance'])
                                    {
                                        $credit = $retro['balance'];
                                    }
                                    else
                                    {
                                        $credit = $gledger_amountPaid;
                                    }


                                    if ($tender_typeDesc == 'Cash')
                                    {
                                        $entry_CIB = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $retro['ref_no'],
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $credit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_CIB);
                                        $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                    }
                                    elseif ($tender_typeDesc == 'Check')
                                    {
                                        if ($check_date > $date)
                                        {
                                            // If PDC

                                            $entry_PDC = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $retro['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_PDC);
                                            $this->app_model->insert('subsidiary_ledger', $entry_PDC);

                                        }
                                        else
                                        {
                                            $entry_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $retro['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                        }

                                    }
                                    elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                                    {
                                        $entry_JV = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $retro['ref_no'],
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $credit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_JV);
                                        $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                    }
                                    elseif ($tender_typeDesc == 'JV payment - Business Unit')
                                    {
                                        $entry_JV = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $retro['ref_no'],
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $credit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_JV);
                                        $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                    }

                                    $credit_RR = array(
                                        'posting_date'      =>  $date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $retro['ref_no'],
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'credit'            =>  -1 * $credit,
                                        'bank_name'         =>  $bank,
                                        'bank_code'         =>  $bank_code,
                                        'status'            =>  $pdc_status,
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );
                                    $this->app_model->insert('general_ledger', $credit_RR);
                                    $this->app_model->insert('subsidiary_ledger', $credit_RR);
                                    $amount_paid -= $credit;
                                }
                            }


                            // to SL

                            $retro_ledger = $this->app_model->get_invoiceRetro($tenant_id);

                            foreach ($retro_ledger as $item)
                            {
                                if ($sledger_amountPaid > 0)
                                {
                                    $credit;
                                    $balance;

                                    if ($sledger_amountPaid >= $item['balance'])
                                    {
                                        $credit  = $item['balance'];
                                        $balance = 0;
                                    }
                                    else
                                    {
                                        $credit  = $sledger_amountPaid;
                                        $balance = abs($item['balance'] - $sledger_amountPaid);
                                    }

                                    $sledger_amountPaid -= $item['balance'];

                                    $entryData = array(
                                        'posting_date'  =>  $date,
                                        'document_type' =>  'Payment',
                                        'ref_no'        =>  $item['ref_no'],
                                        'doc_no'        =>  $receipt_no,
                                        'tenant_id'     =>  $tenant_id,
                                        'contract_no'   =>  $contract_no,
                                        'description'   =>  $item['description'],
                                        'debit'         =>  $credit,
                                        'balance'       =>  $balance,
                                        'due_date'      =>  $item['due_date']
                                    );

                                    $this->app_model->insert('ledger', $entryData);
                                }
                            }
                        }
                    }



                    if ($preop_doc_no) // IF has Preoperation Charges
                    {
                        $preop_data = $this->app_model->get_preopdata($tenant_id);

                        if ($gledger_amountPaid > 0)
                        {
                            foreach ($preop_data as $preop)
                            {
                                if ($gledger_amountPaid > 0)
                                {
                                    $pdf->ln();
                                    $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                                    $pdf->cell(30, 8, $preop['doc_no'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $preop['description'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $preop['posting_date'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $preop['due_date'], 0, 0, 'C');
                                    $pdf->cell(30, 8, number_format($preop['amount'], 2), 0, 0, 'R');

                                    $sl_refNo = $this->app_model->generate_refNo();
                                    $gl_refNo = $this->app_model->gl_refNo();
                                    //todo

                                    $debit;
                                    $balance;
                                    if ($gledger_amountPaid >= $preop['amount'])
                                    {
                                       $debit   = $preop['amount'];
                                       $balance = 0;
                                    }
                                    else
                                    {
                                        $balance = abs($preop['amount'] - $gledger_amountPaid);
                                        $debit   = $gledger_amountPaid;
                                    }

                                    $gl_code;
                                    $doc_type;
                                    if ($preop['description'] == 'Advance Rent')
                                    {
                                        $gl_code  = '10.20.01.01.02.01';
                                        $doc_type = 'Advance Payment';
                                    }
                                    elseif ($preop['description'] == 'Security Deposit' || $preop['description'] == 'Security Deposit - Kiosk and Cart')
                                    {
                                        $gl_code  = '10.20.01.01.03.12';
                                        $doc_type = 'Payment';
                                    }
                                    elseif ($preop['description'] == 'Construction Bond')
                                    {
                                        $gl_code  = '10.20.01.01.03.10';
                                        $doc_type = 'Payment';
                                    }

                                    if ($tender_typeDesc == 'Cash')
                                    {
                                        $entry_CIB = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $gl_refNo,
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $debit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_CIB);
                                        $this->app_model->insert('subsidiary_ledger', $entry_CIB);


                                    }
                                    elseif ($tender_typeDesc == 'Check')
                                    {
                                        if ($check_date > $date)
                                        {
                                            // if PDC
                                            $entry_PDC = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_refNo,
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $debit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')

                                            );
                                            $this->app_model->insert('general_ledger', $entry_PDC);
                                            $this->app_model->insert('subsidiary_ledger', $entry_PDC);
                                        }
                                        else
                                        {
                                            $entry_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_refNo,
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $debit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                        }
                                    }
                                    elseif ($tender_typeDesc == 'JV payment - Business Unit')
                                    {
                                       $entry_JV = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $gl_refNo,
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $debit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_JV);
                                        $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                    }
                                    elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                                    {
                                       $entry_JV = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $gl_refNo,
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $debit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_JV);
                                        $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                    }
                                    elseif ($tender_typeDesc == 'Bank to Bank')
                                    {
                                        $entry_CIB = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $gl_refNo,
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $debit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_CIB);
                                        $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                    }

                                    $preop_entry = array(
                                        'posting_date'      =>    $date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>    'Payment',
                                        'ref_no'            =>    $gl_refNo,
                                        'doc_no'            =>    $receipt_no,
                                        'tenant_id'         =>    $tenant_id,
                                        'gl_accountID'      =>    $this->app_model->gl_accountID($gl_code),
                                        'company_code'      =>    $this->session->userdata('company_code'),
                                        'department_code'   =>    '01.04',
                                        'credit'            =>    -1 * $debit,
                                        'bank_name'         =>    $bank,
                                        'bank_code'         =>    $bank_code,
                                        'status'            =>  $pdc_status,
                                        'prepared_by'       =>    $this->session->userdata('id')
                                    );
                                    $this->app_model->insert('general_ledger', $preop_entry);
                                    $this->app_model->insert('subsidiary_ledger', $preop_entry);

                                    $sl_preOp = array(
                                        'posting_date'      =>    $date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>    $doc_type,
                                        'ref_no'            =>    $sl_refNo,
                                        'doc_no'            =>    $receipt_no,
                                        'tenant_id'         =>    $tenant_id,
                                        'contract_no'       =>    $contract_no,
                                        'description'       =>    $preop['description'] . "-" . $trade_name,
                                        'charges_type'      =>    $preop['description'],
                                        'debit'             =>    $debit,
                                        'balance'           =>    $balance
                                    );
                                    $this->app_model->insert('ledger', $sl_preOp);


                                    $gledger_amountPaid -= $preop['amount'];

                                    if ($gledger_amountPaid >= 0)
                                    {
                                        $this->app_model->delete('tmp_preoperationcharges', 'id', $preop['id']);
                                    }
                                    else
                                    {
                                        $data = array('amount' => ABS($gledger_amountPaid));
                                        $this->app_model->update($data, $preop['id'], 'tmp_preoperationcharges');
                                    }
                                }
                            }
                        }
                    }



                    if ($tender_amount > $total_payable)
                    {
                        $advance_payment = $tender_amount - $total_payable;

                        if ($advance_payment >= 1)
                        {
                            $dataLedger = array(
                                'posting_date'       =>  $date,
                                'transaction_date'   =>  $date,
                                'document_type'      =>  'Advance Payment',
                                'doc_no'             =>  $receipt_no,
                                'ref_no'             =>  $this->app_model->generate_refNo(),
                                'tenant_id'          =>  $tenant_id,
                                'contract_no'        =>  $contract_no,
                                'description'        =>  'Advance Payment-' . $trade_name,
                                'debit'              =>  $advance_payment,
                                'credit'             =>  0,
                                'balance'            =>  $advance_payment
                            );

                            $this->app_model->insert('ledger', $dataLedger);


                            //======== Unearned Rent for Advance Payment =========//

                            $advance_refNo = $this->app_model->gl_refNo();


                            if ($tender_typeDesc == 'Cash')
                            {

                                $advance_CIB = array(
                                    'posting_date'      =>  $date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $advance_refNo,
                                    'doc_no'            =>  $receipt_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $advance_payment,
                                    'bank_name'         =>  $bank,
                                    'bank_code'         =>  $bank_code,
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );

                                $this->app_model->insert('general_ledger', $advance_CIB);
                                $this->app_model->insert('subsidiary_ledger', $advance_CIB);
                            }
                            elseif ($tender_typeDesc == 'Check')
                            {
                                if ($check_date > $date)
                                {

                                    $advance_PDC = array(
                                        'posting_date'      =>  $date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $advance_refNo,
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  $advance_payment,
                                        'bank_name'         =>  $bank,
                                        'bank_code'         =>  $bank_code,
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('general_ledger', $advance_PDC);
                                    $this->app_model->insert('subsidiary_ledger', $advance_PDC);

                                }
                                else
                                {
                                    $advance_CIB = array(
                                        'posting_date'      =>  $date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $advance_refNo,
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  $advance_payment,
                                        'bank_name'         =>  $bank,
                                        'bank_code'         =>  $bank_code,
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('general_ledger', $advance_CIB);
                                    $this->app_model->insert('subsidiary_ledger', $advance_CIB);
                                }
                            }
                            elseif ($tender_typeDesc == 'JV payment - Business Unit')
                            {
                                $advance_JV = array(
                                    'posting_date'      =>  $date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $advance_refNo,
                                    'doc_no'            =>  $receipt_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $advance_payment,
                                    'bank_name'         =>  $bank,
                                    'bank_code'         =>  $bank_code,
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );

                                $this->app_model->insert('general_ledger', $advance_JV);
                                $this->app_model->insert('subsidiary_ledger', $advance_JV);
                            }
                            elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                            {
                                $advance_JV = array(
                                    'posting_date'      =>  $date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $advance_refNo,
                                    'doc_no'            =>  $receipt_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $advance_payment,
                                    'bank_name'         =>  $bank,
                                    'bank_code'         =>  $bank_code,
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );

                                $this->app_model->insert('general_ledger', $advance_JV);
                                $this->app_model->insert('subsidiary_ledger', $advance_JV);
                            }


                            $advance_unearned = array(
                                'posting_date'      =>  $date,
                                'transaction_date'  =>  $this->_currentDate,
                                'document_type'     =>  'Payment',
                                'ref_no'            =>  $advance_refNo,
                                'doc_no'            =>  $receipt_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'credit'            =>  -1 * $advance_payment,
                                'bank_name'         =>  $bank,
                                'bank_code'         =>  $bank_code,
                                'status'            =>  $pdc_status,
                                'prepared_by'       =>  $this->session->userdata('id')
                            );

                            $this->app_model->insert('general_ledger', $advance_unearned);
                            $this->app_model->insert('subsidiary_ledger', $advance_unearned);


                             // For Montly Receivable Report
                            $reportData = array(
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $receipt_no,
                                'posting_date'  =>  $date,
                                'description'   =>  'Advance Payment',
                                'amount'        =>  $advance_payment
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);
                        }

                    }


                    $pdf->ln();
                    $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                    $pdf->ln();


                    $pdf->setFont('times','B',10);
                    $pdf->cell(150, 8, "Payment Scheme:", 0, 0, 'L');
                    $pdf->cell(100, 8, "Payment Date:" . $date, 0, 0, 'L');
                    $pdf->ln();

                    $pdf->setFont('times','',10);
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Description: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $tender_typeDesc, 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format($total_payable, 2), 0, 0, 'L');
                    $pdf->ln();

                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $bank, 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format($tender_amount, 2), 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $check_no, 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
                    if($tender_amount - $total_payable >= 0)
                    {
                        $pdf->cell(30, 4, "P " . "0.00", 0, 0, 'L');
                    }
                    else
                    {
                        $pdf->cell(60, 4, "P " . number_format(abs($tender_amount - $total_payable), 2), 0, 0, 'L');
                    }
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $check_date, 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format($advance_payment + $advanceDeposit_amount, 2), 0, 0, 'L');

                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $payor, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $payee, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "OR #: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $receipt_no, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->ln();

                    if ($tender_typeDesc != 'Cash')
                    {
                        $paymentScheme = array(
                            'tenant_id'        =>   $tenant_id,
                            'contract_no'      =>   $contract_no,
                            'tenancy_type'     =>   $tenancy_type,
                            'receipt_no'       =>   $receipt_no,
                            'tender_typeCode'  =>   $tender_typeCode,
                            'tender_typeDesc'  =>   $tender_typeDesc,
                            'soa_no'           =>   $soa_no,
                            'amount_due'       =>   $total_payable,
                            'amount_paid'      =>   $tender_amount,
                            'bank'             =>   $bank,
                            'check_no'         =>   $check_no,
                            'check_date'       =>   $check_date,
                            'payor'            =>   $payor,
                            'payee'            =>   $payee,
                            'receipt_doc'      =>   $file_name
                        );

                        $this->app_model->insert('payment_scheme', $paymentScheme);
                    }
                    else
                    {
                        $paymentScheme = array(
                            'tenant_id'        =>   $tenant_id,
                            'contract_no'      =>   $contract_no,
                            'tenancy_type'     =>   $tenancy_type,
                            'receipt_no'       =>   $receipt_no,
                            'tender_typeCode'  =>   $tender_typeCode,
                            'billing_period'   =>   $billing_period,
                            'tender_typeDesc'  =>   $tender_typeDesc,
                            'soa_no'           =>   $soa_no,
                            'amount_due'       =>   $total_payable,
                            'amount_paid'      =>   $tender_amount,
                            'bank'             =>   $bank,
                            'check_no'         =>   $check_no,
                            'check_date'       =>   $check_date,
                            'payor'            =>   $payor,
                            'payee'            =>   $payee,
                            'receipt_doc'      =>   $file_name
                        );

                        $this->app_model->insert('payment_scheme', $paymentScheme);
                    }

                    $paymentData = array(
                        'posting_date' =>   $date,
                        'soa_no'       =>   $soa_no,
                        'amount_paid'  =>   $tender_amount,
                        'tenant_id'    =>   $tenant_id,
                        'doc_no'       =>   $receipt_no
                    );

                    $this->app_model->insert('payment', $paymentData);


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

                    $this->db->trans_complete(); // End of transaction function

                    if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                    {
                        $this->db->trans_rollback(); // If failed rollback all queries
                        $error = array('action' => 'Saving Payment', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                        $this->app_model->insert('error_log', $error);
                        $response['msg'] = 'DB_error';
                    }
                    else
                    {

                        $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                        $pdf->Output('assets/pdf/' . $file_name , 'F');
                        $response['msg'] = "Success";
                    }



                    // ========= Check if payment is delayed to apply penalty ========= //

                    if (!$this->app_model->is_penaltyExempt($tenant_id) && $billing_period != 'Upon Signing of Notice')
                    {
                        if (date('Y-m-d', strtotime($date)) > date('Y-m-d', strtotime($collection_date . "+ 1 day")))
                        {
                            $daydiff             = floor((abs(strtotime($date . "- 1 days") - strtotime($collection_date))/(60*60*24)));
                            $sundays             = $this->app_model->get_sundays($collection_date, $date);
                            $daydiff             = $daydiff - $sundays;
                            $penalty_latepayment = ($tender_amount * .02 * $daydiff) / $daysOfMonth;

                            $penaltyEntry = array(
                                'tenant_id'     =>  $tenant_id,
                                'posting_date'  =>  $date,
                                'contract_no'   =>  $contract_no,
                                'doc_no'        =>  $receipt_no,
                                'description'   =>  'Late Payment-' . $trade_name,
                                'amount'        =>  round($penalty_latepayment, 2)
                            );
                            $this->app_model->insert('tmp_latepaymentpenalty', $penaltyEntry);
                        }
                    }

                }
                else
                {
                    $response['msg'] = 'Required';
                }
            }
            echo json_encode($response);
        }
    } // End Save Payment
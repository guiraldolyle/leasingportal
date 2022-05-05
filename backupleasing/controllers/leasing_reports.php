<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leasing_reports extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('app_model');
        $this->load->library('excel');
        $this->load->library('upload');
        $this->load->library('fpdf');
        $this->load->library('pdf');
        $this->load->library('excel');
        ini_set('max_execution_time', '-1');
        date_default_timezone_set("Asia/Manila");
        $timestamp = time();
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

    public function invoice_history()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/invoice_history');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing');
        }

    }


    public function get_invoiceHistory()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = $this->uri->segment(3);
            $result = $this->app_model->get_invoiceHistory($tenant_id);
            echo json_encode($result);
        }
    }

    public function get_penaltyHistory()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = $this->uri->segment(3);
            $result = $this->app_model->get_penaltyHistory($tenant_id);
            echo json_encode($result);
        }
    }

    public function print_invoiceHistory()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $response = array();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $tenant_id = $this->sanitize($this->input->post('tenant_id'));
            $tenancy_type = $this->input->post('tenancy_type');
            $trade_name = $this->input->post('trade_name');
            $address = $this->input->post('address');
            $contract_no = $this->input->post('contract_no');
            $tin = $this->input->post('tin');
            $rental_type = $this->input->post('rental_type');
            $curr_date = $this->input->post('curr_date');
            $result = $this->app_model->get_invoiceHistory($tenant_id);
            $penalties = $this->app_model->get_penaltyHistory($tenant_id);

            if ($result)
            {
                $store_code = $this->app_model->tenant_storeCode($tenant_id);
                $store_details = $this->app_model->store_details(trim($store_code));

                $pdf = new FPDF('p','mm','A4');
                $pdf->AddPage();
                $pdf->setDisplayMode ('fullpage');

                // ==================== Receipt Header ================== //
                foreach ($store_details as $detail)
                {
                    $pdf->setFont ('times','',12);
                    $pdf->cell(0, 10, $detail['store_name'], 0, 0, 'C');
                    $pdf->ln();
                    $pdf->cell(0, 0, $detail['store_address'], 0, 0, 'C');

                    $pdf->setFont ('times','B',15);
                    $pdf->ln();
                    $pdf->cell(0, 10, "Invoice History", 0, 0, 'C');

                    $pdf->ln();
                    $pdf->ln();
                }


                // ==================== Tenant Details ================== //

                $pdf->setFont('times','',10);
                $pdf->cell(30, 8, "Tenancy Type", 0, 0, 'L');
                $pdf->cell(60, 8, $tenancy_type, 1, 0, 'L');
                $pdf->cell(5, 8, " ", 0, 0, 'L');
                $pdf->cell(30, 8, "Contract No.", 0, 0, 'L');
                $pdf->cell(60, 8, $contract_no, 1, 0, 'L');

                $pdf->ln();

                $pdf->cell(30, 8, "Tenant ID", 0, 0, 'L');
                $pdf->cell(60, 8, $tenant_id, 1, 0, 'L');
                $pdf->cell(5, 8, " ", 0, 0, 'L');
                $pdf->cell(30, 8, "TIN", 0, 0, 'L');
                $pdf->cell(60, 8, $tin, 1, 0, 'L');

                $pdf->ln();

                $pdf->cell(30, 8, "Trade Name", 0, 0, 'L');
                $pdf->cell(60, 8, $trade_name, 1, 0, 'L');
                $pdf->cell(5, 8, " ", 0, 0, 'L');
                $pdf->cell(30, 8, "Rental Type", 0, 0, 'L');
                $pdf->cell(60, 8, $rental_type, 1, 0, 'L');

                $pdf->ln();

                $pdf->cell(30, 8, "Address", 0, 0, 'L');
                $pdf->cell(60, 8, $address, 1, 0, 'L');
                $pdf->cell(5, 8, " ", 0, 0, 'L');
                $pdf->cell(30, 8, "Date", 0, 0, 'L');
                $pdf->cell(60, 8, $curr_date, 1, 0, 'L');


                $pdf->ln();
                $pdf->ln();
                $pdf->cell(0, 8, "-----------------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0, 'L');
                $pdf->ln();
                $pdf->ln();

                // =================== Payment Table ============= //
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 8, "Document No.", 0, 0, 'L');
                $pdf->cell(30, 8, "Description", 0, 0, 'L');
                $pdf->cell(30, 8, "Posting Date", 0, 0, 'L');
                $pdf->cell(30, 8, "Due Date", 0, 0, 'L');
                $pdf->cell(30, 8, "Prev. Balance Amt.", 0, 0, 'L');
                $pdf->cell(30, 8, "Paid Amount", 0, 0, 'L');
                $pdf->cell(30, 8, "Balance", 0, 0, 'L');

                $pdf->setFont('times','',10);

                foreach ($result as  $value)
                {
                    $pdf->ln();
                    $pdf->cell(30, 8, $value['doc_no'], 0, 0, 'L');
                    if ($value['charges_type'] != 'Other')
                    {
                        $pdf->cell(30, 8, $trade_name . "-Basic", 0, 0, 'L');
                    } else {
                        $pdf->cell(30, 8, $trade_name . "-Other", 0, 0, 'L');
                    }
                    $pdf->cell(30, 8, $value['posting_date'], 0, 0, 'L');
                    $pdf->cell(30, 8, $value['due_date'], 0, 0, 'L');
                    $pdf->cell(30, 8, $value['prev_amount'], 0, 0, 'L');
                    $pdf->cell(30, 8, $value['paid_amount'], 0, 0, 'L');
                    $pdf->cell(30, 8, $value['balance'], 0, 0, 'L');
                }

                foreach ($penalties as  $penalty)
                {
                    $pdf->ln();
                    $pdf->cell(30, 8, $penalty['doc_no'], 0, 0, 'L');
                    $pdf->cell(30, 8, $trade_name . "-Penalty", 0, 0, 'L');
                    $pdf->cell(30, 8, "", 0, 0, 'L');
                    $pdf->cell(30, 8, $penalty['due_date'], 0, 0, 'L');
                    $pdf->cell(30, 8, $penalty['amount'], 0, 0, 'L');
                    $pdf->cell(30, 8, $penalty['amount_paid'], 0, 0, 'L');
                    $pdf->cell(30, 8, $penalty['balance'], 0, 0, 'L');
                }



                // ======================= Prepared By ====================== //
                $preparedby = $this->app_model->get_preparedBy();
                $pdf->setFont('times','B',10);
                $pdf->ln();
                $pdf->ln();
                $pdf->ln();
                $pdf->ln();
                $pdf->cell(150, 4, "Prepared by: " . $preparedby, 0, 0, 'L');



                $file_name =  $tenant_id . $timeStamp . '.pdf';
                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');

                $response['msg'] = 'Success';
            } else {
                $response['msg'] = 'No Payment History';
            }

            echo json_encode($response);
        }
    }

    public function leasableArea()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/leasableArea');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing');
        }
    }


    public function locationCode_perTenant()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/locationCode_perTenant');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing');
        }
    }


    public function get_locationCodePerTenant()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
           $result = $this->app_model->get_locationCodePerTenant();
           echo json_encode($result->result_array());
        }
    }

    public function export_locationCodePerTenant()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $query = $this->app_model->get_locationCodePerTenant();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename = "locationCode_" . $timeStamp;
            $this->excel->to_excel($query, $filename);
        }
    }

    public function shortTerm_leasableArea()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/shortTerm_leasableArea');
            $this->load->view('leasing/footer');

        } else {
            redirect('ctrl_leasing');
        }
    }


    public function print_shortTerm_leasableArea()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $this->app_model->scheck_vacant();

            $result = array();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $header = array('Location Code', 'Category', 'Store/Property', 'Floor Location', 'Price Per Sq.M', 'Floor Area', 'Rate Per Day', 'Rate Per Week', 'Rate Per Month', 'Status');

            $pdf = new FPDF('L','mm','A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');

            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $result = $this->app_model->get_exhibit_rates();

                $pdf->setFont ('times','',12);
                $pdf->cell(0, 10, "AGC Corporate Center", 0, 0, 'C');
                $pdf->ln();
                $pdf->cell(0, 0, "Dao District, Tagbilaran City", 0, 0, 'C');

                $pdf->setFont ('times','B',15);
                $pdf->ln();
                $pdf->cell(0, 10, "Short Term Leasable Area", 0, 0, 'C');

                $pdf->ln();
                $pdf->ln();


            } else {
                $result = $this->app_model->store_exhibit_rates();
                $store_details = $this->app_model->get_store();
                foreach ($store_details as $detail)
                {
                    $pdf->setFont ('times','',12);
                    $pdf->cell(0, 10, $detail['store_name'], 0, 0, 'C');
                    $pdf->ln();
                    $pdf->cell(0, 0, $detail['store_address'], 0, 0, 'C');

                    $pdf->setFont ('times','B',15);
                    $pdf->ln();
                    $pdf->cell(0, 10, "Short Term Leasable Area", 0, 0, 'C');

                    $pdf->ln();
                    $pdf->ln();
                }

            }

            $pdf->setFont ('times','B',10);
            $pdf->SetFillColor(53, 93, 107);
            for ($i=0; $i<count($header); $i++)
            {
                $pdf->cell(28, 8, $header[$i], 1, 0, 'C', TRUE);
            }

            $pdf->setFont ('times','',10);
            foreach ($result as $value)
            {
                $pdf->ln();
                $pdf->cell(28, 8, $value['location_code'], 1, 0, 'L');
                $pdf->cell(28, 8, $value['category'], 1, 0, 'L');
                $pdf->cell(28, 8, $value['store_code'], 1, 0, 'L');
                $pdf->cell(28, 8, $value['floor_name'], 1, 0, 'L');
                $pdf->cell(28, 8, number_format($value['price'], 2), 1, 0, 'L');
                $pdf->cell(28, 8, number_format($value['floor_area'], 2), 1, 0, 'L');
                $pdf->cell(28, 8, number_format($value['rate_per_day'], 2), 1, 0, 'L');
                $pdf->cell(28, 8, number_format($value['rate_per_week'], 2), 1, 0, 'L');
                $pdf->cell(28, 8, number_format($value['rate_per_month'], 2), 1, 0, 'L');

                if ($value['status'] == 'Occupied')
                {
                    $pdf->cell(28, 8, "Occupied", 1, 0, 'L');
                } else {
                    $pdf->cell(28, 8, "Vacant", 1, 0, 'L');
                }
            }


            // ======================= Prepared By ====================== //
            $preparedby = $this->app_model->get_preparedBy();

            $pdf->setFont('times','B',10);
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->cell(150, 4, "Prepared by: " . $preparedby, 0, 0, 'L');


            $file_name =  $timeStamp . '.pdf';
            $response['file_name'] = base_url() . 'assets/reports/' . $file_name;
            $pdf->Output('assets/reports/' . $file_name , 'F');

            $response['msg'] = 'Success';

            echo json_encode($response);
        }
    }



    public function print_longTerm_leasableArea()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $this->app_model->check_vacant();
            $result = array();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $header = array('Location Code', 'Store/Property', 'Floor Location', 'Floor Area', 'Price Per Sq.M', 'Basic Rental/Month', 'Status');

            $pdf = new FPDF('L','mm','A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $result = $this->app_model->get_price_locationCode();
                $pdf->setFont ('times','',12);
                $pdf->cell(0, 10, "AGC Corporate Center", 0, 0, 'C');
                $pdf->ln();
                $pdf->cell(0, 0, "Dao District, Tagbilaran City", 0, 0, 'C');

                $pdf->setFont ('times','B',15);
                $pdf->ln();
                $pdf->cell(0, 10, "Short Term Leasable Area", 0, 0, 'C');

                $pdf->ln();
                $pdf->ln();
            } else {
                $result = $this->app_model->store_price_locationCode();
                $store_details = $this->app_model->get_store();
                foreach ($store_details as $detail)
                {
                    $pdf->setFont ('times','',12);
                    $pdf->cell(0, 10, $detail['store_name'], 0, 0, 'C');
                    $pdf->ln();
                    $pdf->cell(0, 0, $detail['store_address'], 0, 0, 'C');

                    $pdf->setFont ('times','B',15);
                    $pdf->ln();
                    $pdf->cell(0, 10, "Short Term Leasable Area", 0, 0, 'C');

                    $pdf->ln();
                    $pdf->ln();
                }

            }

            $pdf->setFont ('times','B',10);
            $pdf->SetFillColor(53, 93, 107);
            for ($i=0; $i<count($header); $i++)
            {
                $pdf->cell(40, 8, $header[$i], 1, 0, 'C', TRUE);
            }


            $pdf->setFont ('times','',10);
            foreach ($result as $value)
            {
                $pdf->ln();
                $pdf->cell(40, 8, $value['location_code'], 1, 0, 'L');
                $pdf->cell(40, 8, $value['store_name'], 1, 0, 'L');
                $pdf->cell(40, 8, $value['floor_name'], 1, 0, 'L');
                $pdf->cell(40, 8, number_format($value['floor_area'], 2), 1, 0, 'L');
                $pdf->cell(40, 8, number_format($value['price'], 2), 1, 0, 'L');
                $pdf->cell(40, 8, number_format($value['basic_rental'], 2), 1, 0, 'L');

                if ($value['status'] == 'Occupied')
                {
                    $pdf->cell(40, 8, "Occupied", 1, 0, 'L');
                } else {
                    $pdf->cell(40, 8, "Vacant", 1, 0, 'L');
                }
            }



            // ======================= Prepared By ====================== //
            $preparedby = $this->app_model->get_preparedBy();
            $pdf->setFont('times','B',10);
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->cell(150, 4, "Prepared by: " . $preparedby, 0, 0, 'L');

            $file_name =  $timeStamp . '.pdf';
            $response['file_name'] = base_url() . 'assets/reports/' . $file_name;
            $pdf->Output('assets/reports/' . $file_name , 'F');

            $response['msg'] = 'Success';

            echo json_encode($response);

        }
    }


    public function payment_scheme()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/payment_scheme');
            $this->load->view('leasing/footer');

        } else {
            redirect('ctrl_leasing');
        }
    }


    public function payment_report()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/payment_report');
            $this->load->view('leasing/footer');

        } else {
            redirect('ctrl_leasing');
        }
    }


    /*public function generate_paymentReport()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $from_date   = $this->sanitize($this->input->post('from_date'));
            $to_date     = $this->sanitize($this->input->post('to_date'));
            $user_id     = $this->session->userdata('id');
            $description = $this->input->post("description");
            $total       = 0;
            $response    = array();
            $date        = new DateTime();
            $timeStamp   = $date->getTimestamp();
            $file_name   =  $user_id . $timeStamp . '.pdf';
            $pdf         = new FPDF('L','mm','Letter');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            $this->db->trans_start(); // Transaction function starts here!!!


            if (in_array("JV", $description)) {
                array_push($description, "JV payment - Business Unit", "JV payment - Subsidiary");
            }



            $cashier_name = $this->app_model->get_cashierName($user_id);
            $report = $this->app_model->paymentReport($from_date, $to_date, $description);

            $pdf->setFont ('times','B',16);
            $pdf->cell(0, 6, 'ALTURAS GROUP OF COMPANIES', 0, 0, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(35, 35, 35);
            $pdf->ln();
            $pdf->setFont ('times','B',11);
            $pdf->cell(0, 6, "Property Management System", 0, 0, 'C');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','',10);
            $pdf->cell(0, 4, 'Date Printed: ' .  date('F j, Y h:i:s:A'), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 4, 'Transaction Date: ' . date('m/d/y', strtotime($from_date)) . ' To ' . date('m/d/y', strtotime($to_date)), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 4, 'Generated By: ' .  $cashier_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 12, "___________________________________________________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','B',14);
            $pdf->cell(0, 4, 'Payment Report List', 0, 0, 'C');
            $pdf->ln();
            $pdf->setFont ('times','BU',14);
            $pdf->cell(0, 10, "_________________________________________________________________________________________________________", 0, 0, 'L');-
            $pdf->ln();

            // =================== Payment Table ============= //
            $pdf->setFont('times','B',10);
            $pdf->cell(30, 8, "Payment Date", 0, 0, 'L');
            $pdf->cell(80, 8, "Payor", 0, 0, 'L');
            $pdf->cell(40, 8, "TIN", 0, 0, 'L');
            $pdf->cell(30, 8, "Tender Type", 0, 0, 'L');
            $pdf->cell(30, 8, "OR #", 0, 0, 'L');
            $pdf->cell(40, 8, "Amount Paid", 0, 0, 'R');


            $pdf->setFont('times','',10);

            foreach ($report as  $value)
            {
                $pdf->ln();
                $pdf->cell(30, 8, $value['payment_date'], 0, 0, 'L');
                $pdf->cell(80, 8, $value['payor'], 0, 0, 'L');
                $pdf->cell(40, 8, $value['tin'], 0, 0, 'L');
                $pdf->cell(30, 8, $value['tender_typeDesc'], 0, 0, 'L');
                $pdf->cell(30, 8, $value['receipt_no'], 0, 0, 'L');
                $pdf->cell(40, 8, number_format($value['amount_paid'], 2), 0, 0, 'R');

                $total += $value['amount_paid'];
            }


            $pdf->ln();
            $pdf->cell(0, 8, "___________________________________________________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(210, 8, "Total Amount ", 0, 0, 'L');



            $pdf->setFont ('times','BUU',10);
            $pdf->cell(40, 8,number_format($total, 2), 0, 0, 'R');

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

            echo json_encode($response);

        }
    }*/

    public function generate_paymentReport()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $from_date   = $this->sanitize($this->input->post('from_date'));
            $to_date     = $this->sanitize($this->input->post('to_date'));
            $doc_type    = $this->sanitize($this->input->post('doc_type'));


            $user_id     = $this->session->userdata('id');
            $description = $this->input->post("description");
            $total       = 0;
            $response    = array();
            $date        = new DateTime();
            $timeStamp   = $date->getTimestamp();

            $store =  $this->session->userdata('store_code');

            $file_name   =  "$store - Payment Report $user_id $timeStamp". ($doc_type=='excel'? '.xls' : '.pdf');
            $this->db->trans_start(); // Transaction function starts here!!!


            if (in_array("JV", $description)) {
                array_push($description, "JV payment - Business Unit", "JV payment - Subsidiary");
            }


            $cashier_name = $this->app_model->get_cashierName($user_id);
            $report = $this->app_model->paymentReport($from_date, $to_date, $description);

            if($doc_type =="excel") return $this->paymentReportExcel($report, $file_name);

            $pdf         = new FPDF('L','mm','Letter');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            

            $pdf->setFont ('times','B',16);
            $pdf->cell(0, 6, 'ALTURAS GROUP OF COMPANIES', 0, 0, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(35, 35, 35);
            $pdf->ln();
            $pdf->setFont ('times','B',11);
            $pdf->cell(0, 6, "Property Management System", 0, 0, 'C');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','',10);
            $pdf->cell(0, 4, 'Date Printed: ' .  date('F j, Y h:i:s:A'), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 4, 'Transaction Date: ' . date('m/d/y', strtotime($from_date)) . ' To ' . date('m/d/y', strtotime($to_date)), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 4, 'Generated By: ' .  $cashier_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 12, "___________________________________________________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','B',14);
            $pdf->cell(0, 4, 'Payment Report List', 0, 0, 'C');
            $pdf->ln();
            $pdf->setFont ('times','BU',14);
            $pdf->cell(0, 10, "_________________________________________________________________________________________________________", 0, 0, 'L');-
            $pdf->ln();

            // =================== Payment Table ============= //
            $pdf->setFont('times','B',10);
            $pdf->cell(30, 8, "Payment Date", 0, 0, 'L');
            $pdf->cell(80, 8, "Payor", 0, 0, 'L');
            $pdf->cell(40, 8, "TIN", 0, 0, 'L');
            $pdf->cell(30, 8, "Tender Type", 0, 0, 'L');
            $pdf->cell(30, 8, "OR #", 0, 0, 'L');
            $pdf->cell(40, 8, "Amount Paid", 0, 0, 'R');


            $pdf->setFont('times','',10);

            foreach ($report as  $value)
            {
                $pdf->ln();
                $pdf->cell(30, 8, $value['payment_date'], 0, 0, 'L');
                $pdf->cell(80, 8, $value['payor'], 0, 0, 'L');
                $pdf->cell(40, 8, $value['tin'], 0, 0, 'L');
                $pdf->cell(30, 8, $value['tender_typeDesc'], 0, 0, 'L');
                $pdf->cell(30, 8, $value['receipt_no'], 0, 0, 'L');
                $pdf->cell(40, 8, number_format($value['amount_paid'], 2), 0, 0, 'R');

                $total += $value['amount_paid'];
            }


            $pdf->ln();
            $pdf->cell(0, 8, "___________________________________________________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(210, 8, "Total Amount ", 0, 0, 'L');



            $pdf->setFont ('times','BUU',10);
            $pdf->cell(40, 8,number_format($total, 2), 0, 0, 'R');

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

            echo json_encode($response);

        }
    }

    function paymentReportExcel($report, $file_name){

        $report_data = [];

        array_push($report_data, ["Payment Date", "Payor", "TIN", "Tender Type", "OR #", "Amount Paid"]);

        $total = 0;

        foreach ($report as  $value)
        {
            $value = (object) $value;

            array_push($report_data, [
                $value->payment_date,
                $value->payor,
                $value->tin,
                $value->tender_typeDesc,
                $value->receipt_no,
                number_format($value->amount_paid, 2),
            ]);

            $total += $value->amount_paid;
        }

        array_push($report_data, ["Total Amount ", "", "", "", "", number_format($total, 2)]);

        $report_string = arrayToString($report_data, "\t", '"', true, PHP_EOL);

        $filedir = 'assets/excel/' . $file_name;
        $file = fopen($filedir, "w") or die(json_encode(['msg'=>'Cannot create file']));
        fwrite($file, $report_string);
        fclose($file);

        die(json_encode(['msg'=>'Success', "file_name"=>base_url() .$filedir]));
    }


    public function payment_proofList()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/payment_proofList');
            $this->load->view('leasing/footer');

        } else {
            redirect('ctrl_leasing');
        }
    }

    public function get_paymentScheme()
    {
        if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('cfs_logged_in'))
        {
            $tenant_id = $this->uri->segment(3);
            $result = $this->app_model->get_paymentScheme($tenant_id);
            echo json_encode($result);
        }
    }


    public function creditMemo_history()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/creditMemo_history');
            $this->load->view('leasing/footer');

        } else {
            redirect('ctrl_leasing');
        }
    }


    public function get_creditMemo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_creditMemo();
            echo json_encode($result);
        }
    }


    public function print_creditMemo_history()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = array();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $result = $this->app_model->get_creditMemo();

            $pdf = new FPDF('L','mm','A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $pdf->setFont ('times','',12);
                $pdf->cell(0, 10, "AGC Corporate Center", 0, 0, 'C');
                $pdf->ln();
                $pdf->cell(0, 0, "Dao District, Tagbilaran City", 0, 0, 'C');

                $pdf->setFont ('times','B',15);
                $pdf->ln();
                $pdf->cell(0, 10, "Credit Memo History", 0, 0, 'C');

                $pdf->ln();
                $pdf->ln();
            } else {

                $store_details = $this->app_model->get_store();
                foreach ($store_details as $detail)
                {
                    $pdf->setFont ('times','',12);
                    $pdf->cell(0, 10, $detail['store_name'], 0, 0, 'C');
                    $pdf->ln();
                    $pdf->cell(0, 0, $detail['store_address'], 0, 0, 'C');

                    $pdf->setFont ('times','B',15);
                    $pdf->ln();
                    $pdf->cell(0, 10, "Credit Memo History", 0, 0, 'C');

                    $pdf->ln();
                    $pdf->ln();
                }

            }

            $pdf->setFont ('times','B',10);
            $pdf->SetFillColor(53, 93, 107);


            $pdf->cell(30, 8, "Tenant ID", 1, 0, 'C', TRUE);
            $pdf->cell(70, 8, "Reason", 1, 0, 'C', TRUE);
            $pdf->cell(30, 8, "Original Amount", 1, 0, 'C', TRUE);
            $pdf->cell(30, 8, "+Positive Amount", 1, 0, 'C', TRUE);
            $pdf->cell(30, 8, "-Negative Amount", 1, 0, 'C', TRUE);
            $pdf->cell(30, 8, "Total Amount", 1, 0, 'C', TRUE);
            $pdf->cell(30, 8, "Date Modified", 1, 0, 'C', TRUE);
            $pdf->cell(30, 8, "Modified By", 1, 0, 'C', TRUE);


            $pdf->setFont ('times','',10);
            foreach ($result as $value)
            {
                $pdf->ln();
                $pdf->cell(30, 8, $value['tenant_id'], 1, 0, 'L');
                $pdf->cell(70, 8, $value['reason'], 1, 0, 'L');
                $pdf->cell(30, 8, number_format($value['original_amount'], 2), 1, 0, 'L');
                $pdf->cell(30, 8, number_format($value['positive_amount'], 2), 1, 0, 'L');
                $pdf->cell(30, 8, number_format($value['negative_amount'], 2), 1, 0, 'L');
                $pdf->cell(30, 8, number_format($value['total_amount'], 2), 1, 0, 'L');
                $pdf->cell(30, 8, $value['date_modified'], 1, 0, 'L');
                $pdf->cell(30, 8, $value['modified_by'], 1, 0, 'L');

            }


            // ======================= Prepared By ====================== //
            $preparedby = $this->app_model->get_preparedBy();
            $pdf->setFont('times','B',10);
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->cell(150, 4, "Prepared by: " . $preparedby, 0, 0, 'L');

            $file_name =  $timeStamp . '.pdf';
            $response['file_name'] = base_url() . 'assets/reports/' . $file_name;
            $pdf->Output('assets/reports/' . $file_name , 'F');

            $response['msg'] = 'Success';
            echo json_encode($response);

        }
    }


    public function longTerm_amendments()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/longTerm_amendments');
            $this->load->view('leasing/footer');

        } else {
            redirect('ctrl_leasing');
        }
    }


    public function get_amendments()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $flag = str_replace("%20", " ", $this->uri->segment(3));
            $result = $this->app_model->get_amendments($flag);
            echo json_encode($result);
        }
    }

    public function shortTerm_amendments()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/shortTerm_amendments');
            $this->load->view('leasing/footer');

        } else {
            redirect('ctrl_leasing');
        }
    }

    public function print_longTerm_amendments()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = array();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $result = $this->app_model->get_amendments('Long Term');

            $pdf = new FPDF('L','mm','A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $pdf->setFont ('times','',12);
                $pdf->cell(0, 10, "AGC Corporate Center", 0, 0, 'C');
                $pdf->ln();
                $pdf->cell(0, 0, "Dao District, Tagbilaran City", 0, 0, 'C');

                $pdf->setFont ('times','B',15);
                $pdf->ln();
                $pdf->cell(0, 10, "Long Term Contract Amendments", 0, 0, 'C');

                $pdf->ln();
                $pdf->ln();
            } else {

                $store_details = $this->app_model->get_store();
                foreach ($store_details as $detail)
                {
                    $pdf->setFont ('times','',12);
                    $pdf->cell(0, 10, $detail['store_name'], 0, 0, 'C');
                    $pdf->ln();
                    $pdf->cell(0, 0, $detail['store_address'], 0, 0, 'C');

                    $pdf->setFont ('times','B',15);
                    $pdf->ln();
                    $pdf->cell(0, 10, "Long Term Contract Amendments", 0, 0, 'C');

                    $pdf->ln();
                    $pdf->ln();
                }

            }

            $pdf->setFont ('times','B',10);
            $pdf->SetFillColor(53, 93, 107);


            $pdf->cell(30, 8, "Tenant ID", 1, 0, 'C', TRUE);
            $pdf->cell(30, 8, "Contract No.", 1, 0, 'C', TRUE);
            $pdf->cell(35, 8, "Trade Name", 1, 0, 'C', TRUE);
            $pdf->cell(35, 8, "Store Location", 1, 0, 'C', TRUE);
            $pdf->cell(80, 8, "Reason", 1, 0, 'C', TRUE);
            $pdf->cell(30, 8, "Date Modified", 1, 0, 'C', TRUE);
            $pdf->cell(35, 8, "Modified By", 1, 0, 'C', TRUE);

            $pdf->setFont ('times','',10);
            foreach ($result as $value)
            {
                $pdf->ln();
                $pdf->cell(30, 8, $value['tenant_id'], 1, 0, 'L');
                $pdf->cell(30, 8, $value['contract_no'], 1, 0, 'L');
                $pdf->cell(35, 8, $value['trade_name'], 1, 0, 'L');
                $pdf->cell(35, 8, $value['store_name'], 1, 0, 'L');
                $pdf->cell(80, 8, $value['reason'], 1, 0, 'L');
                $pdf->cell(30, 8, $value['date_modified'], 1, 0, 'L');
                $pdf->cell(35, 8, $value['modified_by'], 1, 0, 'L');

            }


            // ======================= Prepared By ====================== //
            $preparedby = $this->app_model->get_preparedBy();
            $pdf->setFont('times','B',10);
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->cell(150, 4, "Prepared by: " . $preparedby, 0, 0, 'L');

            $file_name =  $timeStamp . '.pdf';
            $response['file_name'] = base_url() . 'assets/reports/' . $file_name;
            $pdf->Output('assets/reports/' . $file_name , 'F');

            $response['msg'] = 'Success';


            echo json_encode($response);

        } else {
            redirect('ctrl_leasing');
        }
    }


    public function print_shortTerm_amendments()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $result = array();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $result = $this->app_model->get_amendments('Short Term');

            $pdf = new FPDF('L','mm','A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $pdf->setFont ('times','',12);
                $pdf->cell(0, 10, "AGC Corporate Center", 0, 0, 'C');
                $pdf->ln();
                $pdf->cell(0, 0, "Dao District, Tagbilaran City", 0, 0, 'C');

                $pdf->setFont ('times','B',15);
                $pdf->ln();
                $pdf->cell(0, 10, "Short Term Contract Amendments", 0, 0, 'C');

                $pdf->ln();
                $pdf->ln();
            } else {

                $store_details = $this->app_model->get_store();
                foreach ($store_details as $detail)
                {
                    $pdf->setFont ('times','',12);
                    $pdf->cell(0, 10, $detail['store_name'], 0, 0, 'C');
                    $pdf->ln();
                    $pdf->cell(0, 0, $detail['store_address'], 0, 0, 'C');

                    $pdf->setFont ('times','B',15);
                    $pdf->ln();
                    $pdf->cell(0, 10, "Long Term Contract Amendments", 0, 0, 'C');

                    $pdf->ln();
                    $pdf->ln();
                }

            }

            $pdf->setFont ('times','B',10);
            $pdf->SetFillColor(53, 93, 107);


            $pdf->cell(30, 8, "Tenant ID", 1, 0, 'C', TRUE);
            $pdf->cell(30, 8, "Contract No.", 1, 0, 'C', TRUE);
            $pdf->cell(35, 8, "Trade Name", 1, 0, 'C', TRUE);
            $pdf->cell(35, 8, "Store Location", 1, 0, 'C', TRUE);
            $pdf->cell(80, 8, "Reason", 1, 0, 'C', TRUE);
            $pdf->cell(30, 8, "Date Modified", 1, 0, 'C', TRUE);
            $pdf->cell(35, 8, "Modified By", 1, 0, 'C', TRUE);

            $pdf->setFont ('times','',10);
            foreach ($result as $value)
            {
                $pdf->ln();
                $pdf->cell(30, 8, $value['tenant_id'], 1, 0, 'L');
                $pdf->cell(30, 8, $value['contract_no'], 1, 0, 'L');
                $pdf->cell(35, 8, $value['trade_name'], 1, 0, 'L');
                $pdf->cell(35, 8, $value['store_name'], 1, 0, 'L');
                $pdf->cell(80, 8, $value['reason'], 1, 0, 'L');
                $pdf->cell(30, 8, $value['date_modified'], 1, 0, 'L');
                $pdf->cell(35, 8, $value['modified_by'], 1, 0, 'L');
            }


            // ======================= Prepared By ====================== //
            $preparedby = $this->app_model->get_preparedBy();
            $pdf->setFont('times','B',10);
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->cell(150, 4, "Prepared by: " . $preparedby, 0, 0, 'L');

            $file_name =  $timeStamp . '.pdf';
            $response['file_name'] = base_url() . 'assets/reports/' . $file_name;
            $pdf->Output('assets/reports/' . $file_name , 'F');

            $response['msg'] = 'Success';
            echo json_encode($response);

        } else {
            redirect('ctrl_leasing');
        }
    }


    public function rent_receivable()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/rent_receivable');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function populate_rentReceivable()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_rentReceivable();
            echo json_encode($result->result_array());
        }
    }

    public function export_rentReceivable()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $date_from = $this->uri->segment(3);
            $date_to = $this->uri->segment(4);
            $query = $this->app_model->get_rentReceivable($date_from, $date_to);
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename = "RR_" . $timeStamp;
            $this->excel->to_excel($query, $filename);
        }
    }


    public function account_receivable()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/account_receivable');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function populate_accountReceivable()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_accountReceivable();
            echo json_encode($result->result_array());
        }
    }


    public function export_accountReceivable()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $date_from = $this->uri->segment(3);
            $date_to = $this->uri->segment(4);
            $query = $this->app_model->get_accountReceivable($date_from, $date_to);
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename = "AR_" . $timeStamp;
            $this->excel->to_excel($query, $filename);
        }
    }

    public function category_treeview()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['first_level'] = $this->app_model->getAll('category_one');
            $data['second_level'] = $this->app_model->getAll('category_two');
            $data['third_level'] = $this->app_model->getAll('category_three');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/category_treeview');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function tenant_firstCategory()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $category = str_replace("%20", " ", $this->uri->segment(3));
            $result = $this->app_model->tenant_firstCategory($category);
            echo json_encode($result);
        }
    }


    public function tenant_secondCategory()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $category = str_replace("%20", " ", $this->uri->segment(3));
            $result = $this->app_model->tenant_secondCategory($category);
            echo json_encode($result);
        }
    }


    public function tenant_thirdCategory()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $category = str_replace("%20", " ", $this->uri->segment(3));
            $result = $this->app_model->tenant_thirdCategory($category);
            echo json_encode($result);
        }
    }



    public function bank_ledger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            echo "Soon!";
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }



    public function account_chart()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/account_chart');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_accountChart()
    {
        if ($this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'CFS')
        {
            $gl_code = $this->uri->segment(3);
            $result = $this->app_model->get_accountChart();
            echo json_encode($result);
        }
    }


    public function get_GLaccount()
    {
        if ($this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Administrator')
        {
            $gl_code = $this->uri->segment(3);
            $result = $this->app_model->get_GLaccount($gl_code);
            echo json_encode($result);
        }
    }


    public function navigate()
    {
        if ($this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Administrator')
        {
            $gl_id = $this->uri->segment(3);
            $result = $this->app_model->navigate($gl_id);
            echo json_encode($result);
        }
    }



    public function acountability_report()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'Administrator')
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['users'] = $this->app_model->get_cashier();
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/accountability_report');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function generate_accountabilityReport()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'Administrator')
        {
            $user_id = $this->sanitize($this->input->post('username'));
            $beginning_date = $this->sanitize($this->input->post('beginning_date'));
            $end_date = $this->sanitize($this->input->post('end_date'));
            $response = array();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();

            $cash_rr_amount = 0; $cash_ar_amount = 0; $cash_ri_amount = 0; $cash_vat_amount = 0; $cash_wht_amount = 0; $cash_advance_amount = 0; $cash_secdepo_amount = 0; $cash_consbond_amount = 0;
            $check_rr_amount = 0; $check_ar_amount = 0; $check_ri_amount = 0; $check_vat_amount = 0; $check_wht_amount = 0; $check_advance_amount = 0; $check_secdepo_amount = 0; $check_consbond_amount = 0;
            $cash_total = 0; $check_total = 0; $grand_total = 0;

            $file_name =  $user_id . $timeStamp . '.pdf';
            $pdf = new FPDF('p','mm','Letter');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            $this->db->trans_start(); // Transaction function starts here!!!
            $store_details = $this->app_model->store_details($this->session->userdata('store_code'));
            $cashier_name = $this->app_model->get_cashierName($user_id);
            $report = $this->app_model->gathered($user_id, $beginning_date, $end_date);

            foreach ($report as $value)
            {
                if ($value['description'] == 'Rent Receivable')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_rr_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_rr_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Account Receivable')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_ar_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_ar_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Rent Income')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_ri_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_ri_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'VAT Output')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_vat_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_vat_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }

                }
                elseif ($value['description'] == 'Creditable Withholding Tax')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_wht_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_wht_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Creditable Withholding Tax')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_wht_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_wht_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Advance Deposit')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_advance_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_advance_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Security Deposit')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_secdepo_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_secdepo_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Construction Bond')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_consbond_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_consbond_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
            }

            $grand_total = $cash_total + $check_total;


            $pdf->setFont ('times','B',16);
            $pdf->cell(0, 6, 'ALTURAS GROUP OF COMPANIES', 0, 0, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(35, 35, 35);
            $pdf->ln();
            $pdf->setFont ('times','B',11);
            $pdf->cell(0, 6, "Property Management System", 0, 0, 'C');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','',10);
            $pdf->cell(0, 4, 'Date Printed: ' .  date('F j, Y h:i:s:A'), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 4, 'Transaction Date: ' . date('m/d/y', strtotime($beginning_date)) . ' To ' . date('m/d/y', strtotime($end_date)), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 4, 'Cashier: ' .  $cashier_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 12, "_____________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','B',14);
            $pdf->cell(0, 4, 'Cashier Accountability Report', 0, 0, 'C');
            $pdf->ln();
            $pdf->setFont ('times','BU',14);
            $pdf->cell(0, 10, "______________________________________________________________________________", 0, 0, 'L');-
            $pdf->ln();

            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, 'Cash', 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','',10);
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Rent Receivable', 0, 0, 'L');
            $pdf->cell(0,5, number_format($cash_rr_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'VAT Output', 0, 0, 'L');
            $pdf->cell(40, 5, number_format($cash_vat_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'Rent Income', 0, 0, 'L');
            $pdf->cell(40, 5, number_format(-1 * $cash_ri_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'Creditable withholding Tax', 0, 0, 'L');
            $pdf->cell(40, 5, number_format(-1 * $cash_wht_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Account Receivable', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($cash_ar_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Advance Rent', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($cash_advance_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Security Deposit', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($cash_secdepo_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Construction Bond', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($cash_consbond_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, 'Total', 0, 0, 'L');
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 5, 'P ' . number_format($cash_total, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(0, 12, "______________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 5, 'Check', 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','',10);
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Rent Receivable', 0, 0, 'L');
            $pdf->cell(0,5, number_format($check_rr_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'VAT Output', 0, 0, 'L');
            $pdf->cell(40, 5, number_format($check_vat_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'Rent Income', 0, 0, 'L');
            $pdf->cell(40, 5, number_format(-1 * $check_ri_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'Creditable withholding Tax', 0, 0, 'L');
            $pdf->cell(40, 5, number_format(-1 * $check_wht_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Account Receivable', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($check_ar_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Advance Rent', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($check_advance_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Security Deposit', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($check_secdepo_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Construction Bond', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($check_consbond_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, 'Total', 0, 0, 'L');
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 5, 'P ' . number_format($check_total, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 12, "______________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, 'Grand Total', 0, 0, 'L');
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 5, 'P ' . number_format($grand_total, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(0, 8, "______________________________________________________________________________________________________________", 0, 0, 'L');


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

            echo json_encode($response);

        }
    }


    public function cfs_accountabilityReport()
    {
        if ($this->session->userdata('cfs_logged_in'))
        {
            $user_id = $this->session->userdata('id');
            $beginning_date = $this->sanitize($this->input->post('beginning_date'));
            $end_date = $this->sanitize($this->input->post('end_date'));
            $response = array();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();

            $cash_rr_amount = 0; $cash_ar_amount = 0; $cash_ri_amount = 0; $cash_vat_amount = 0; $cash_wht_amount = 0; $cash_advance_amount = 0; $cash_secdepo_amount = 0; $cash_consbond_amount = 0;
            $check_rr_amount = 0; $check_ar_amount = 0; $check_ri_amount = 0; $check_vat_amount = 0; $check_wht_amount = 0; $check_advance_amount = 0; $check_secdepo_amount = 0; $check_consbond_amount = 0;
            $cash_total = 0; $check_total = 0; $grand_total = 0;

            $file_name =  $user_id . $timeStamp . '.pdf';
            $pdf = new FPDF('p','mm','Letter');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            $this->db->trans_start(); // Transaction function starts here!!!
            $store_details = $this->app_model->store_details($this->session->userdata('store_code'));
            $cashier_name = $this->app_model->get_cashierName($user_id);
            $report = $this->app_model->gathered($user_id, $beginning_date, $end_date);

            foreach ($report as $value)
            {
                if ($value['description'] == 'Rent Receivable')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_rr_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_rr_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Account Receivable')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_ar_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_ar_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Rent Income')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_ri_amount += $value['amount'];

                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_ri_amount += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'VAT Output')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_vat_amount += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_vat_amount += $value['amount'];
                    }

                }
                elseif ($value['description'] == 'Creditable Withholding Tax')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_wht_amount += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_wht_amount += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Creditable Withholding Tax')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_wht_amount += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_wht_amount += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Advance Deposit')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_advance_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_advance_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Security Deposit')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_secdepo_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_secdepo_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
                elseif ($value['description'] == 'Construction Bond')
                {
                    if ($value['tender_desc'] == 'Cash')
                    {
                        $cash_consbond_amount += $value['amount'];
                        $cash_total += $value['amount'];
                    }
                    elseif ($value['tender_desc'] == 'Check') {
                        $check_consbond_amount += $value['amount'];
                        $check_total += $value['amount'];
                    }
                }
            }

            $grand_total = $cash_total + $check_total;


            $pdf->setFont ('times','B',16);
            $pdf->cell(0, 6, 'ALTRUAS GROUP OF COMPANIES', 0, 0, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(35, 35, 35);
            $pdf->ln();
            $pdf->setFont ('times','B',11);
            $pdf->cell(0, 6, "Property Management System", 0, 0, 'C');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','',10);
            $pdf->cell(0, 4, 'Date Printed: ' .  date('F j, Y h:i:s:A'), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 4, 'Transaction Date: ' . date('m/d/y', strtotime($beginning_date)) . ' To ' . date('m/d/y', strtotime($end_date)), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 4, 'Cashier: ' .  $cashier_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 12, "_____________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','B',14);
            $pdf->cell(0, 4, 'Cashier Accountability Report', 0, 0, 'C');
            $pdf->ln();
            $pdf->setFont ('times','BU',14);
            $pdf->cell(0, 10, "______________________________________________________________________________", 0, 0, 'L');-
            $pdf->ln();

            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, 'Cash', 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','',10);
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Rent Receivable', 0, 0, 'L');
            $pdf->cell(0,5, number_format($cash_rr_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'VAT Output', 0, 0, 'L');
            $pdf->cell(40, 5, number_format($cash_vat_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'Rent Income', 0, 0, 'L');
            $pdf->cell(40, 5, number_format(-1 * $cash_ri_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'Creditable withholding Tax', 0, 0, 'L');
            $pdf->cell(40, 5, number_format(-1 * $cash_wht_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Account Receivable', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($cash_ar_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Advance Rent', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($cash_advance_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Security Deposit', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($cash_secdepo_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Construction Bond', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($cash_consbond_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, 'Total', 0, 0, 'L');
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 5, 'P ' . number_format($cash_total, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(0, 12, "______________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 5, 'Check', 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','',10);
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Rent Receivable', 0, 0, 'L');
            $pdf->cell(0,5, number_format($check_rr_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'VAT Output', 0, 0, 'L');
            $pdf->cell(40, 5, number_format($check_vat_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'Rent Income', 0, 0, 'L');
            $pdf->cell(40, 5, number_format(-1 * $check_ri_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(40, 5, '', 0, 0, 'L');
            $pdf->cell(90, 5, 'Creditable withholding Tax', 0, 0, 'L');
            $pdf->cell(40, 5, number_format(-1 * $check_wht_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Account Receivable', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($check_ar_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Advance Rent', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($check_advance_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Security Deposit', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($check_secdepo_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(20, 5, '', 0, 0, 'L');
            $pdf->cell(0, 5, 'Construction Bond', 0, 0, 'L');
            $pdf->cell(0, 5, number_format($check_consbond_amount, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, 'Total', 0, 0, 'L');
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 5, 'P ' . number_format($check_total, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 12, "______________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, 'Grand Total', 0, 0, 'L');
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 5, 'P ' . number_format($grand_total, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(0, 8, "______________________________________________________________________________________________________________", 0, 0, 'L');


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

            echo json_encode($response);
        }
    }


    public function waived_penlaties()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/waived_penlaties');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_waivedPenalties()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_waivedPenalties();
            echo json_encode($result);
        }
    }

    public function generate_GLCSV()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $beginning_date = $this->sanitize($this->input->post('beginning_date'));
            $end_date = $this->sanitize($this->input->post('end_date'));
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $file_name = "GL" . $timeStamp . ".csv";

            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename=' . $file_name);
            // do not cache the file
            header('Pragma: no-cache');
            header('Expires: 0');
            $file = fopen('php://output', 'w');
            // send the column headers
            fputcsv($file, array('Entry No.', 'Tenant ID', 'Trade name', 'Doc. Type', 'Document No.', 'GL Code', 'Description', 'Due Date', 'Posting Date', 'Debit', 'Credit','Running Balance'));

            $result = $this->app_model->generate_GLCSV($beginning_date, $end_date);

            $result_withbalance = array();
            $running_balance = 0;
            $prev_docno = '';

            foreach($result as $value)
            {
              

                if($value['doc_no'] != $prev_docno)
                {
                    $result_withbalance[] = 
                    [
                        'id'            => $value['id'],
                        'trade_name'    => $value['trade_name'],
                        'tenant_id'     => $value['tenant_id'],
                        'document_type' => $value['document_type'],
                        'doc_no'        => $value['doc_no'],
                        'gl_code'       => $value['gl_code'],                     
                        'gl_account'    => $value['gl_account'],
                        'due_date'      => $value['due_date'],
                        'posting_date'  => $value['posting_date'],
                        'debit'         => number_format($value['debit'],2),
                        'credit'        => number_format($value['credit'],2),
                     
                    ];
                }
                else
                {
                    if($value['credit'] == '0.00' || $value['credit'] == null)
                    {
                        $running_balance = $running_balance + $value['debit'];
                        $result_withbalance[] = 
                        [
                            'id'            => $value['id'],
                            'trade_name'    => $value['trade_name'],
                            'tenant_id'     => $value['tenant_id'],
                            'document_type' => $value['document_type'],
                            'doc_no'        => $value['doc_no'],
                            'gl_code'       => $value['gl_code'],
                            'gl_account'    => $value['gl_account'],
                            'due_date'      => $value['due_date'],
                            'posting_date'  => $value['posting_date'],
                            'debit'         => number_format($value['debit'],2),
                            'credit'        => number_format($value['credit'],2),
                          
                        ];
                    }
                    else
                    {
                        $running_balance = $running_balance - $value['credit'];
                        $result_withbalance[] = 
                        [
                            'id'            => $value['id'],
                            'trade_name'    => $value['trade_name'],
                            'tenant_id'     => $value['tenant_id'],
                            'document_type' => $value['document_type'],
                            'doc_no'        => $value['doc_no'],
                            'gl_code'       => $value['gl_code'],
                            'gl_account'    => $value['gl_account'],
                            'due_date'      => $value['due_date'],
                            'posting_date'  => $value['posting_date'],
                            'debit'         => number_format($value['debit'],2),
                            'credit'        => number_format($value['credit'],2),
                         
                        ];
                    }
                }
            }


            foreach ($result_withbalance as $row)
            {
                fputcsv($file, $row);
            }
            exit();
        }
    }


    public function monthly_receivable()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/monthly_receivable');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function monthly_payable()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/monthly_payable');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function monthly_report_glentry()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/monthly_report_glentry');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function monthly_preop_summary()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/monthly_preop_summary');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function generate_preop_summary()
    {
        $month = $this->sanitize($this->input->post('month'));
        $reportData = $this->app_model->generate_preop_summary($month);

        $filename ="Monthly Preop Summary " . $month;
        $this->excel->generate_preop_summary($reportData, $filename, $month);
    }


    public function monthly_receivable_summary()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/monthly_receivable_summary');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    
    public function generate_monthly_receivable_summary()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $month = $this->sanitize($this->input->post('month'));
            $reportData = $this->app_model->generate_monthly_receivable_summary($month);
            $ar_total = $this->app_model->AR_monthly_total($month);
            $rr_total = $this->app_model->RR_monthly_total($month);
            $filename ="Monthly Receivable Summary " . $month;
            $this->excel->generate_monthly_receivable_summary($reportData, $ar_total, $rr_total, $filename, $month);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }





    public function generate_monthly_report_glentry()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $beginning_date = $this->sanitize($this->input->post('beginning_date'));
            $end_date = $this->sanitize($this->input->post('end_date'));
            $reportData = $this->app_model->get_monthly_report_gl_entry($beginning_date, $end_date);
            $gl_accounts = $this->app_model->getAll('gl_accounts');
            $tenants = $this->app_model->get_tenants();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename ="generate_report_receivable_" . $timeStamp;
            $this->excel->generate_monthly_report_glentry($gl_accounts, $tenants, $reportData, $filename);

        }
    }


    public function generate_monthly_payable()
    {
        $month = $this->sanitize($this->input->post('month'));
        $reportData = $this->app_model->generate_monthly_payable($month);
        $tenants = $this->app_model->tenants();
        $date = new DateTime();
        $timeStamp = $date->getTimestamp();
        $filename ="generate_report_" . $month . '_' . $timeStamp;
        $this->excel->generate_monthly_payable($reportData, $month, $tenants, $filename);
    }


    public function generate_monthly_receivable()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $this->db->trans_start(); // Transaction function starts here!!!
            $response       = array();
            $beginning_date = $this->sanitize($this->input->post('beginning_date'));
            $end_date       = $this->sanitize($this->input->post('end_date'));
            $tenancy_type   = $this->sanitize($this->input->post('tenancy_type'));
            $doc_type       = $this->sanitize($this->input->post('doc_type'));

            $doc_type       = $doc_type == 'excel' ? 'excel' : 'pdf';


            $date           = new DateTime();
            $timeStamp      = $date->getTimestamp();
            $file_name      =  'monthly_receivable' . $timeStamp . '.pdf';
            $pdfFilePath    ="./assets/pdf/" . $file_name;

            $data['store_name'] = $this->app_model->store_name($this->session->userdata('store_code'));
            $data['tenancy_type'] = $tenancy_type;
            $data['reports'] = $this->app_model->get_monthly_receivable($beginning_date, $end_date, $tenancy_type);
            $data['inclusive_date'] = $beginning_date . ' to ' . $end_date;

            if($doc_type == 'excel'){
                return $this->generate_monthly_receivable_excel($data);
            }


            $html = $this->load->view('leasing/monthly_receivable_pdf', $data, true); // render the view into HTML
            $pdf = $this->pdf->load();
            $pdf->AddPage('L', // L - landscape, P - portrait
                                   '', '', '', '',
                                   5, // margin_left
                                   5, // margin right
                                   5, // margin top
                                   5, // margin bottom
                                   5, // margin header
                                   5); // margin footer
            $pdf->setFooter("Page {PAGENO} of {nb}"); // Add a footer for good measure ;)
            $stylesheet = file_get_contents(base_url() . 'css/report.css'); // external css
            $pdf->WriteHTML($stylesheet, 1);
            $pdf->WriteHTML($html, 2); // write the HTML into the PDF
            $pdf->Output($pdfFilePath, 'F'); // save to file because we can


            $this->db->trans_complete(); // End of transaction function

            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $response['msg'] = 'DB_error';
            }
            else
            {
                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');
                $response['msg'] = "Success";
            }

            echo json_encode($response);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    function generate_monthly_receivable_excel($data){
        $data = (object) $data;

        $counter = 0; 
        $total_basic = 0; 
        $total_percentage = 0; 
        $total_discount = 0; 
        $total_total = 0; 
        $total_vat = 0; 
        $total_subtotal = 0; 
        $total_wht = 0; 
        $total_net = 0;  
        $last_part = false; 
        $total_cusa = 0; 
        $total_aircon = 0; 
        $total_chilledWater = 0; 
        $total_electricity = 0; 
        $total_water = 0; 
        $total_gas = 0; 
        $total_pestControl = 0;
        $total_bioAug = 0; 
        $total_serviceReq = 0; 
        $total_overtime = 0; 
        $total_IDcharges = 0; 
        $total_faxCharges = 0; 
        $total_fixedAssetRental = 0; 
        $total_penalty = 0; 
        $total_motorcadeCharges = 0;
        $total_securityCharges = 0; 
        $total_plywoodEnclosure = 0; 
        $total_pvcLock = 0; 
        $total_exhaustDuct = 0; 
        $total_trainingRoomCharges = 0; 
        $total_storageRoomCharges = 0;
        $total_neonLights = 0; 
        $total_rollup = 0; 
        $total_addbox = 0; 
        $total_unathorizedClosure = 0; 
        $total_houseViolation = 0;
        $total_notaryFee = 0; 
        $total_lateSubmission = 0; 
        $total_bannerBoard = 0; 
        $total_ledwall = 0; 
        $total_forwardedBalance = 0; 
        $total_others = 0; 
        $total_adjustmentVAT = 0;
        $total_leakDetector = 0;
        $total_glassService = 0; 
        $total_coupon = 0; 
        $total_expandedTax = 0; 
        $grand_total = 0;


           

            $trade_names        = [''];
            $basic_rents        = ['Basic Rent'];
            $percentage_rents   = ['Percentage Rent'];
            $discounts          = ['Discount'];
            $totals             = ['Total'];
            $vats               = ['Add VAT'];
            $subtotals          = ['Subtotal'];
            $whts               = ['Less Tax Withheld'];
            $nets               = ['NET RENTAL DUE AFTER TAX'];
            $other_curr_charges = ['Add: Other Current Charges'];
            $cusas              = ['CUSA'];
            $aircons            = ['Aircon'];
            $chilled_waters     = ['Chilled Water'];
            $electricities      = ['Electricity'];
            $waters             = ['Water'];
            $gasses             = ['Gas'];
            $pest_controls      = ['Pest Control'];
            $bio_augs           = ['Bio Augmentation'];
            $service_requests   = ['Service Request'];
            $overtimes          = ['Overtime Charges'];
            $id_charges         = ['ID Charges'];
            $fixed_assets       = ['Fixed Asset Rental'];
            $penalties          = ['Penalty'];
            $sec_charges        = ['Security Charges'];
            $expanded_taxes     = ['Expanded Withholding Tax'];
            $plywoods           = ['Plywood Enclosure'];
            $pvcs               = ['PVC Door & Lock'];
            $exhausts           = ['Exhaust Duct Cleaning Charges'];
            $storage_rooms      = ['Storage Charges'];   
            $addboxes           = ['AdBox'];
            $unatho_closures    = ['Unathorized Closure'];
            $house_rules        = ['Houserules Violation'];
            $notary_fees        = ['Notary Fee'];
            $late_depositSlips  = ['Late Submission of Deposit Slip'];
            $banner_boards      = ['Banner Board'];
            $led_walls          = ['LED Wall'];
            $forwarded_balances = ['Forwarded Balance'];
            $others             = ['Others'];
            $adjustment_VATs    = ['Adjustment(VAT)'];
            $leak_detectors     = ['Gas Leak Detector'];
            $glass_services     = ['Glass Service'];
            $coupons            = ['Coupon'];
            $totals2            = ['TOTALS'];

            foreach ($data->reports as $key => $report) {
                $report = (object) $report;

                $tenant = 0;

                /*==== START TRADE NAME ==== */
                    array_push($trade_names, $report->trade_name);
                    if($key == count($data->reports) -1)
                        array_push($trade_names, 'Grand Total');
                /*==== END TRADE NAME ==== */

                /*==== START BASIC RENT ==== */

                    $basic_rent = ''; 

                    if ($report->rental_type == 'Fixed'){

                        $basic_rent = number_format($report->basic_rent, 2); 
                        $total_basic += $report->basic_rent ;

                    }elseif ($report->rental_type == 'Fixed Plus Percentage' && $report->basic_rent > 0){

                        $basic_rent = number_format($report->basic_rent, 2); 
                        $total_basic += $report->basic_rent ;

                    } elseif ($report->rental_type == 'Fixed/Percentage w/c Higher'){

                        if ($report->basic_rental >= $report->basic_rent) {
                            $basic_rent = number_format($report->basic_rent, 2); 
                            $total_basic += $report->basic_rent ;
                        }
                    }

                    array_push($basic_rents, $basic_rent);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_basic, 2); 
                        array_push($basic_rents, $gt);
                    }
                /*==== END BASIC RENT ==== */

                /*==== START PECENTAGE RENT ==== */

                    $percentage_rent = '';

                    if ($report->rental_type == 'Fixed/Percentage w/c Higher'){
                        if ($report->basic_rent > 0 && $report->basic_rent > $report->basic_rental){

                            $percentage_rent = number_format($report->basic_rent, 2); 
                            $total_percentage +=  $report->basic_rent;
                        }
                    }
                        
                    if ($report->rental_type == 'Fixed Plus Percentage'){

                        if ($report->basic_rent > 0){
                           $percentage_rent = number_format($report->basic_rent - $report->basic_rental, 2); 
                           $total_percentage +=  $report->basic_rent - $report->basic_rental;
                        }
                    }

                    array_push($percentage_rents, $percentage_rent);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_percentage, 2); 
                        array_push($percentage_rents, $gt);
                    }

                /*==== END PECENTAGE RENT ==== */

                /*==== START DISCOUNT ==== */
                    $discount = number_format($report->discount, 2); 
                    $total_discount += $report->discount; 

                    array_push($discounts, $discount);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_discount, 2); 
                        array_push($discounts, $gt);
                    }
                /*==== END DISCOUNT ==== */

                /*==== START TOTAL ==== */
                    $total = number_format($report->basic_rent, 2); 
                    $total_total += $report->basic_rent; 

                    array_push($totals, $total);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_total, 2); 
                        array_push($totals, $gt);
                    }

                /*==== END TOTAL ==== */

                /*==== START VAT ==== */
                    $vat = number_format($report->vat, 2); 
                    $total_vat += $report->vat; 

                    array_push($vats, $vat);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_vat, 2); 
                        array_push($vats, $gt);
                    }
                /*==== END VAT ==== */

                /*==== START SUBTOTAL ==== */

                    $subtotal =  number_format($report->basic_rent + $report->vat, 2); 
                    $total_subtotal += $report->basic_rent + $report->vat;

                    array_push($subtotals, $subtotal);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_subtotal, 2); 
                        array_push($subtotals, $gt);
                    }

                /*==== END SUBTOTAL ==== */

                /*==== START WHT ==== */

                    $wht =  number_format($report->wht * -1, 2); 
                    $total_wht += ($report->wht * -1);

                    array_push($whts, $wht);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_wht, 2); 
                        array_push($whts, $gt);
                    }
                /*==== END WHT ==== */

                /*==== START NET ==== */

                    $net = number_format($report->net_rental, 2); 
                    $tenant += $report->net_rental;
                    $total_net += $report->net_rental;   

                    array_push($nets, $net);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_net, 2); 
                        array_push($nets, $gt);
                    }

                /*==== END NET ==== */

                /*==== START CUSA ==== */

                    $cusa = number_format($report->cusa, 2); 
                    $tenant += $report->cusa;
                    $total_cusa += $report->cusa;   

                    array_push($cusas, $cusa);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_cusa, 2); 
                        array_push($cusas, $gt);
                    }
                /*==== END CUSA ==== */

                /*==== START AIRCON ==== */

                    $aircon = number_format($report->aircon, 2); 
                    $tenant += $report->aircon;
                    $total_aircon += $report->aircon;   

                    array_push($aircons, $aircon);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_aircon, 2); 
                        array_push($aircons, $gt);
                    }
                /*==== END AIRCON ==== */

                /*==== START CHILLED WATER ==== */

                    $chilled_water = number_format($report->chilled_water, 2); 
                    $tenant += $report->chilled_water;
                    $total_chilledWater += $report->chilled_water;   

                    array_push($chilled_waters, $chilled_water);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_chilledWater, 2); 
                        array_push($chilled_waters, $gt);
                    }
                /*==== END CHILLED WATER ==== */


                /*==== START ELECTRICITY ==== */

                    $electricity = number_format($report->electricity, 2); 
                    $tenant += $report->electricity;
                    $total_electricity += $report->electricity;   

                    array_push($electricities, $electricity);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_electricity, 2); 
                        array_push($electricities, $gt);
                    }
                /*==== END ELECTRICITY ==== */

                /*==== START WATER ==== */

                    $water = number_format($report->water, 2); 
                    $tenant += $report->water;
                    $total_water += $report->water;   

                    array_push($waters, $water);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_water, 2); 
                        array_push($waters, $gt);
                    }
                /*==== END WATER ==== */

                /*==== START GAS ==== */

                    $gas = number_format($report->gas, 2); 
                    $tenant += $report->gas;
                    $total_gas += $report->gas;   

                    array_push($gasses, $gas);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_gas, 2); 
                        array_push($gasses, $gt);
                    }
                /*==== END GAS ==== */

                /*==== START PEST CONTROL ==== */

                    $pest_control = number_format($report->pest_control, 2); 
                    $tenant += $report->pest_control;
                    $total_pestControl += $report->pest_control;   

                    array_push($pest_controls, $pest_control);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_pestControl, 2); 
                        array_push($pest_controls, $gt);
                    }
                /*==== END PEST CONTROL ==== */

                /*==== START BIO AUGMENTATION ==== */

                    $bio_aug = number_format($report->bio_aug, 2); 
                    $tenant += $report->bio_aug;
                    $total_bioAug += $report->bio_aug;   

                    array_push($bio_augs, $bio_aug);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_bioAug, 2); 
                        array_push($bio_augs, $gt);
                    }
                /*==== END BIO AUGMENTATION ==== */

                /*==== START SERVICE REQUEST ==== */

                    $service_request = number_format($report->service_request, 2); 
                    $tenant += $report->service_request;
                    $total_serviceReq += $report->service_request;   

                    array_push($service_requests, $service_request);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_serviceReq, 2); 
                        array_push($service_requests, $gt);
                    }
                /*==== END SERVICE REQUEST ==== */

                /*==== START OVER TIMES ==== */

                    $overtime = number_format($report->overtime, 2); 
                    $tenant += $report->overtime;
                    $total_overtime += $report->overtime;   

                    array_push($overtimes, $overtime);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_overtime, 2); 
                        array_push($overtimes, $gt);
                    }
                /*==== END OVER TIMES ==== */

                /*==== START ID CHARGES ==== */

                    $id_charge = number_format($report->id_charges, 2); 
                    $tenant += $report->id_charges;
                    $total_IDcharges += $report->id_charges;   

                    array_push($id_charges, $id_charge);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_IDcharges, 2); 
                        array_push($id_charges, $gt);
                    }
                /*==== END  ID CHARGES==== */

                /*==== START FIXED ASSETS ==== */

                    $fixed_asset = number_format($report->fixed_asset, 2); 
                    $tenant += $report->fixed_asset;
                    $total_fixedAssetRental += $report->fixed_asset;   

                    array_push($fixed_assets, $fixed_asset);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_fixedAssetRental, 2); 
                        array_push($fixed_assets, $gt);
                    }
                /*==== END FIXED ASSETS ==== */

                /*==== START PENALTIES ==== */

                    $penalty = number_format($report->penalty, 2); 
                    $tenant += $report->penalty;
                    $total_penalty += $report->penalty;   

                    array_push($penalties, $penalty);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_penalty, 2); 
                        array_push($penalties, $gt);
                    }
                /*==== END PENALTIES ==== */

                /*==== START SECURITY CHARGES ==== */

                    $sec_charge = number_format($report->sec_charges, 2); 
                    $tenant += $report->sec_charges;
                    $total_securityCharges += $report->sec_charges;   

                    array_push($sec_charges, $sec_charge);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_securityCharges, 2); 
                        array_push($sec_charges, $gt);
                    }
                /*==== END SECURITY CHARGES ==== */


                /*==== START EXPANDED TAX ==== */

                    $expanded_tax = number_format($report->expanded_tax * -1, 2); 
                    $tenant -= $report->expanded_tax;
                    $total_expandedTax += ($report->expanded_tax * -1);

                    array_push($expanded_taxes, $expanded_tax);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_expandedTax, 2); 
                        array_push($expanded_taxes, $gt);
                    }
                /*==== END EXPANDED TAX ==== */   


                /*==== START PLYWOOD ==== */

                    $plywood = number_format($report->plywood, 2); 
                    $tenant += $report->plywood;
                    $total_plywoodEnclosure += $report->plywood;   

                    array_push($plywoods, $plywood);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_plywoodEnclosure, 2); 
                        array_push($plywoods, $gt);
                    }
                /*==== END PLYWOOD ==== */   

                /*==== START PVC ==== */

                    $pvc = number_format($report->pvc, 2); 
                    $tenant += $report->pvc;
                    $total_pvcLock += $report->pvc;   

                    array_push($pvcs, $pvc);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_pvcLock, 2); 
                        array_push($pvcs, $gt);
                    }
                /*==== END PVC ==== */    

                /*==== START EXHAUST ==== */

                    $exhaust = number_format($report->exhaust, 2); 
                    $tenant += $report->exhaust;
                    $total_exhaustDuct += $report->exhaust;   

                    array_push($exhausts, $exhaust);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_exhaustDuct, 2); 
                        array_push($exhausts, $gt);
                    }
                /*==== END EXHAUST ==== */    

                /*==== START STORAGE ROOMS ==== */

                    $storage_room = number_format($report->storage_room, 2); 
                    $tenant += $report->storage_room;
                    $total_storageRoomCharges += $report->storage_room;   

                    array_push($storage_rooms, $storage_room);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_storageRoomCharges, 2); 
                        array_push($storage_rooms, $gt);
                    }
                /*==== END STORAGE ROOMS ==== */

                /*==== START AD BOX ==== */

                    $addbox = number_format($report->addbox, 2); 
                    $tenant += $report->addbox;
                    $total_addbox += $report->addbox;   

                    array_push($addboxes, $addbox);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_addbox, 2); 
                        array_push($addboxes, $gt);
                    }
                /*==== END AD BOX ==== */

                /*==== START UNAUTHORIZE CLOSURES==== */

                    $unatho_closure = number_format($report->unatho_closure, 2); 
                    $tenant += $report->unatho_closure;
                    $total_unathorizedClosure += $report->unatho_closure;   

                    array_push($unatho_closures, $unatho_closure);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_unathorizedClosure, 2); 
                        array_push($unatho_closures, $gt);
                    }
                /*==== END UNAUTHORIZE CLOSURES ==== */

                /*==== START Houserules Violation==== */

                    $house_rule = number_format($report->house_rules, 2); 
                    $tenant += $report->house_rules;
                    $total_houseViolation += $report->house_rules;   

                    array_push($house_rules, $house_rule);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_houseViolation, 2); 
                        array_push($house_rules, $gt);
                    }
                /*==== END Houserules Violation ==== */

                /*==== START NOTARY FEE==== */

                    $notary_fee = number_format($report->notary_fee, 2); 
                    $tenant += $report->notary_fee;
                    $total_notaryFee += $report->notary_fee;   

                    array_push($notary_fees, $notary_fee);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_notaryFee, 2); 
                        array_push($notary_fees, $gt);
                    }
                /*==== END NOTARY FEE ==== */


                /*==== START LATE SUBMISSION SLIP==== */

                    $late_depositSlip = number_format($report->late_depositSlip, 2); 
                    $tenant += $report->late_depositSlip;
                    $total_lateSubmission += $report->late_depositSlip;   

                    array_push($late_depositSlips, $late_depositSlip);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_lateSubmission, 2); 
                        array_push($late_depositSlips, $gt);
                    }
                /*==== END LATE SUBMISSION SLIP ==== */

                /*==== START Banner Board==== */

                    $banner_board = number_format($report->banner_board, 2); 
                    $tenant += $report->banner_board;
                    $total_bannerBoard += $report->banner_board;   

                    array_push($banner_boards, $banner_board);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_bannerBoard, 2); 
                        array_push($banner_boards, $gt);
                    }
                /*==== END Banner Board ==== */


                /*==== START LED Wall==== */

                    $led_wall = number_format($report->led_wall, 2); 
                    $tenant += $report->led_wall;
                    $total_ledwall += $report->led_wall;   

                    array_push($led_walls, $led_wall);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_ledwall, 2); 
                        array_push($led_walls, $gt);
                    }
                /*==== END LED Wall ==== */


                /*==== START Forwarded Balance==== */

                    $forwarded_balance = number_format($report->forwarded_balance, 2); 
                    $tenant += $report->forwarded_balance;
                    $total_forwardedBalance += $report->forwarded_balance;   

                    array_push($forwarded_balances, $forwarded_balance);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_forwardedBalance, 2); 
                        array_push($forwarded_balances, $gt);
                    }
                /*==== END Forwarded Balance ==== */


                /*==== START Others==== */

                    $other = number_format($report->others, 2); 
                    $tenant += $report->others;
                    $total_others += $report->others;   

                    array_push($others, $other);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_others, 2); 
                        array_push($others, $gt);
                    }
                /*==== END Others ==== */

                /*==== START Adjustment(VAT)==== */

                    $adjustment_VAT = number_format($report->adjustment_VAT, 2); 
                    $tenant += $report->adjustment_VAT;
                    $total_adjustmentVAT += $report->adjustment_VAT;   

                    array_push($adjustment_VATs, $adjustment_VAT);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_adjustmentVAT, 2); 
                        array_push($adjustment_VATs, $gt);
                    }
                /*==== END Adjustment(VAT) ==== */

                /*==== START Gas Leak Detector==== */

                    $leak_detector = number_format($report->leak_detector, 2); 
                    $tenant += $report->leak_detector;
                    $total_leakDetector += $report->leak_detector;   

                    array_push($leak_detectors, $leak_detector);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_leakDetector, 2); 
                        array_push($leak_detectors, $gt);
                    }
                /*==== END Gas Leak Detector ==== */


                /*==== START Glass Service==== */

                    $glass_service = number_format($report->glass_service, 2); 
                    $tenant += $report->glass_service;
                    $total_glassService += $report->glass_service;   

                    array_push($glass_services, $glass_service);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_glassService, 2); 
                        array_push($glass_services, $gt);
                    }
                /*==== END Glass Service ==== */

                /*==== START Coupon==== */

                    $coupon = number_format($report->coupon, 2); 
                    $tenant += $report->coupon;
                    $total_coupon += $report->coupon;   

                    array_push($coupons, $coupon);

                    if($key == count($data->reports) -1){
                        $gt = number_format($total_coupon, 2); 
                        array_push($coupons, $gt);
                    }
                /*==== END Coupon ==== */



                /*==== START TOTALS 2 ==== */
                    $grand_total += $tenant;
                    $tenant = number_format($tenant, 2);
                    array_push($totals2, $tenant);

                    if($key == count($data->reports) -1){
                        $gt = number_format($grand_total, 2); 
                        array_push($totals2, $gt);
                    }

                /*==== END TOTALS 2 ==== */
                

            }

            $report_data = [];

            array_push($report_data, [$data->store_name]);
            array_push($report_data, ["Monthly Receivable -   $data->tenancy_type ($data->inclusive_date)"]);
            array_push($report_data, ["Run Date: ".date('Y-m-d').", Run Time ".date('Y-m-d h:i:s:A')]);

            array_push($report_data, $trade_names, $basic_rents, $percentage_rents, $discounts, $totals, $vats, $subtotals, $whts, $nets, $other_curr_charges, $cusas, $aircons, $chilled_waters, $electricities, $waters, $gasses ,$pest_controls, $bio_augs, $service_requests, $overtimes, $id_charges, $fixed_assets, $penalties, $sec_charges, $expanded_taxes, $plywoods, $pvcs, $exhausts, $storage_rooms, $addboxes, $unatho_closures, $house_rules, $notary_fees, $late_depositSlips, $banner_boards, $led_walls, $others, $adjustment_VATs, $leak_detectors, $glass_services, $coupons, $totals2);



            $report_string = arrayToString($report_data, "\t", '"', true, PHP_EOL);

            $date           = new DateTime();
            $timeStamp      = $date->getTimestamp();
            $file_name      =  'monthly_receivable' . $timeStamp . '.xls';

            $filedir = 'assets/excel/' . $file_name;
            $file = fopen($filedir, "w") or die(json_encode(['msg'=>'Cannot create file']));
            fwrite($file, $report_string);
            fclose($file);

            die(json_encode(['msg'=>'Success', "file_name"=>base_url() .$filedir]));
    }




    public function export_agingRR()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $this->db->trans_start(); // Transaction function starts here!!!
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename = 'RR-Aging'  . $timeStamp;
            $file_name      =  'monthly_receivable' . $timeStamp . '.pdf';
            $pdfFilePath    ="./assets/pdf/" . $file_name;

            $date = $this->uri->segment(3);
            $as_of = date('F d, Y', strtotime($date));
            $store_name = $this->app_model->store_name($this->session->userdata('store_code'));
            $query = $this->app_model->get_agingRR($date);
            $data['title'] = $store_name . ' - Leasing Aging(Rent Receivable) as of ' . $as_of;
            $data['reportData'] = $query->result_array();

            $html = $this->load->view('leasing/aging_pdf', $data, true); // render the view into HTML
            $pdf = $this->pdf->load();
            $pdf->AddPage('L', // L - landscape, P - portrait
                                   '', '', '', '',
                                   5, // margin_left
                                   5, // margin right
                                   10, // margin top
                                   10, // margin bottom
                                   5, // margin header
                                   5); // margin footer

            $pdf->setFooter("Date Generate: " .  date('F d, Y') . " | |Page {PAGENO} of {nb}"); // Add a footer for good measure ;)
            $stylesheet = file_get_contents(base_url() . 'css/report.css'); // external css
            $pdf->WriteHTML($stylesheet, 1);
            $pdf->WriteHTML($html, 2); // write the HTML into the PDF
            $pdf->Output($pdfFilePath, 'F'); // save to file because we can




            $this->db->trans_complete(); // End of transaction function

            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $response['msg'] = 'DB_error';
            }
            else
            {
                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');
                $response['msg'] = "Success";
            }

            echo json_encode($response);
        }
    }


    public function export_agingAR()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $this->db->trans_start(); // Transaction function starts here!!!
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename = 'AR-Aging'  . $timeStamp;
            $file_name      =  'monthly_receivable' . $timeStamp . '.pdf';
            $pdfFilePath    ="./assets/pdf/" . $file_name;

            $date = $this->uri->segment(3);
            $as_of = date('F d, Y', strtotime($date));
            $store_name = $this->app_model->store_name($this->session->userdata('store_code'));
            $query = $this->app_model->get_agingAR($date);
            $data['title'] = $store_name . ' - Leasing Aging(Account Receivable) as of ' . $as_of;
            $data['reportData'] = $query->result_array();

            $html = $this->load->view('leasing/aging_pdf', $data, true); // render the view into HTML
            $pdf = $this->pdf->load();
            $pdf->AddPage('L', // L - landscape, P - portrait
                                   '', '', '', '',
                                   5, // margin_left
                                   5, // margin right
                                   10, // margin top
                                   10, // margin bottom
                                   5, // margin header
                                   5); // margin footer

            $pdf->setFooter("Date Generate: " .  date('F d, Y') . " | |Page {PAGENO} of {nb}"); // Add a footer for good measure ;)
            $stylesheet = file_get_contents(base_url() . 'css/report.css'); // external css
            $pdf->WriteHTML($stylesheet, 1);
            $pdf->WriteHTML($html, 2); // write the HTML into the PDF
            $pdf->Output($pdfFilePath, 'F'); // save to file because we can


            $this->db->trans_complete(); // End of transaction function

            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $response['msg'] = 'DB_error';
            }
            else
            {
                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');
                $response['msg'] = "Success";
            }

            echo json_encode($response);
        }
    }




    public function delinquent_tenants()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/delinquent_tenants');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }



    public function generate_delinquent_tenants()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $this->db->trans_start(); // Transaction function starts here!!!
            $response = array();
            $from_date = $this->sanitize($this->input->post('from_date'));
            $as_of_date = $this->sanitize($this->input->post('as_of_date'));
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $file_name =  'delinquent' . $timeStamp . '.pdf';
            $pdfFilePath ="./assets/pdf/" . $file_name;

            $data['store_name'] = $this->app_model->store_name($this->session->userdata('store_code'));
            // $data['reports'] = $this->app_model->get_receivable_summary($as_of_date);
            $data['delinquent'] = $this->app_model->delinquent($as_of_date);

            $tenants = array();

            foreach ($data['delinquent'] as $value)
            {
                $tenants[]  = array(
                            'tenant_id'         =>  $value['tenant_id'],
                            'trade_name'        =>  $value['trade_name'],
                            'contact_person'    =>  $value['contact_person']
                        );
            }

            $data['tenants'] = array_unique($tenants, SORT_REGULAR);

            // $data['account_receivable'] = $this->app_model->AR_delinquent($as_of_date);
            $data['partial_payment'] = $this->app_model->partial_payment($from_date, $as_of_date);
            $data['as_of_date'] = date("M j, Y", strtotime($as_of_date));
            $html = $this->load->view('leasing/delinquent_tenants_pdf', $data, true); // render the view into HTML
            $pdf = $this->pdf->load();
            $pdf->AddPage('L', // L - landscape, P - portrait
                                   '', '', '', '',
                                   10, // margin_left
                                   10, // margin right
                                   10, // margin top
                                   10, // margin bottom
                                   10, // margin header
                                   10); // margin footer
            $pdf->setFooter("Page {PAGENO} of {nb}"); // Add a footer for good measure ;)
            $stylesheet = file_get_contents(base_url() . 'css/report.css'); // external css
            $pdf->WriteHTML($stylesheet, 1);
            $pdf->WriteHTML($html, 2); // write the HTML into the PDF
            $pdf->Output($pdfFilePath, 'F'); // save to file because we can


            $this->db->trans_complete(); // End of transaction function

            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $response['msg'] = 'DB_error';
            }
            else
            {
                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');
                $response['msg'] = "Success";
            }
            echo json_encode($response);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function receivable_summary()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/receivable_summary');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function generate_receivable_summary()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $this->db->trans_start(); // Transaction function starts here!!!
            $response = array();
            $from_date = $this->sanitize($this->input->post('from_date'));
            $as_of_date = $this->sanitize($this->input->post('as_of_date'));
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $file_name =  'receivable_summary' . $timeStamp . '.pdf';
            $pdfFilePath ="./assets/pdf/" . $file_name;

            $data['store_name'] = $this->app_model->store_name($this->session->userdata('store_code'));
            $data['rr_summary'] = $this->app_model->RR_summary($as_of_date);
            $data['ar_summary'] = $this->app_model->AR_summary($as_of_date);
            $data['reports'] = $this->app_model->get_tenants();

            $data['as_of_date'] = date("M j, Y", strtotime($as_of_date));
            $html = $this->load->view('leasing/receivable_summary_pdf', $data, true); // render the view into HTML
            $pdf = $this->pdf->load();
            $pdf->AddPage('L', // L - landscape, P - portrait
                                   '', '', '', '',
                                   10, // margin_left
                                   10, // margin right
                                   10, // margin top
                                   10, // margin bottom
                                   10, // margin header
                                   10); // margin footer
            $pdf->setFooter("Page {PAGENO} of {nb}"); // Add a footer for good measure ;)
            $stylesheet = file_get_contents(base_url() . 'css/report.css'); // external css
            $pdf->WriteHTML($stylesheet, 1);
            $pdf->WriteHTML($html, 2); // write the HTML into the PDF
            $pdf->Output($pdfFilePath, 'F'); // save to file because we can

            $this->db->trans_complete(); // End of transaction function

            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $response['msg'] = 'DB_error';
            }
            else
            {
                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');
                $response['msg'] = "Success";
            }
            echo json_encode($response);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function longTerm_tenantHistory()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/longTerm_tenantHistory');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_longtermHistory()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_longtermHistory();
            echo json_encode($result);
        }
    }


    public function view_history()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = $this->sanitize($this->uri->segment(3));
            $result = $this->app_model->view_history($tenant_id);
            echo json_encode($result);
        }
    }


    public function shortTerm_tenantHistory()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/shortTerm_tenantHistory');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_shorttermHistory()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_shorttermHistory();
            echo json_encode($result);
        }
    }


    public function aging_RR()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/aging_RR');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_agingRR()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            // Format JSON POST
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
            header('Access-Control-Allow-Methods: GET, POST, PUT');
            $jsonstring = file_get_contents ( 'php://input' );
            $arr        = json_decode($jsonstring,true);
            $date = $arr["param"];
            $result = $this->app_model->get_agingRR($date);
            $result = $result->result_array();
            echo json_encode($result);
        }
    }


    public function aging_AR()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/aging_AR');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_agingAR()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
            header('Access-Control-Allow-Methods: GET, POST, PUT');
            $jsonstring = file_get_contents ( 'php://input' );
            $arr        = json_decode($jsonstring,true);
            $date = $arr["param"];
            $result = $this->app_model->get_agingAR($date);
            $result = $result->result_array();
            echo json_encode($result);
        }
    }



    public function get_availableArea()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_availableArea();
            echo json_encode($result);
        }
    }

    public function migrate_data()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/migrate_data');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function migrate_bigBal()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $response = array();

            try
            {

                $filename = $_FILES['file']['tmp_name'];
                $inputFileType = PHPExcel_IOFactory::identify($filename);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($filename);

            } catch (Exception $e) {
                echo $e;
            }

            //  Get worksheet dimensions
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for ($row = 1; $row <= $highestRow; $row++)
            {


                $this->db->trans_start(); // Transaction function starts here!!!

                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL, TRUE, FALSE);
                $due_date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($rowData[0][0]));
                $doc_type = $rowData[0][1];
                $doc_no = $this->app_model->get_docNo();
                $ref_no = $this->app_model->generate_refNo();
                $gl_refNo = $this->app_model->gl_refNo();
                $trade_name = $rowData[0][3];
                $amount = $rowData[0][4];
                $flag = $rowData[0][5];
                $tag = "";
                $gl_code;
                $charges_type="";


                if ($flag == 'Basic')
                {
                    $tag = 'Basic Rent';
                    $charges_type = 'Basic/Monthly Rental';
                    $gl_code = 4;
                }
                else
                {
                    $gl_code = 22;
                }

                $tenantData = $this->app_model->get_tenantData($trade_name);
                foreach ($tenantData as $value)
                {
                    $invoiceData = array(
                        'tenant_id'         =>  $value['tenant_id'],
                        'contract_no'       =>  $value['contract_no'],
                        'trade_name'        =>  $value['trade_name'],
                        'doc_no'            =>  $doc_no,
                        'posting_date'      =>  $due_date,
                        'transaction_date'  =>  $due_date,
                        'due_date'          =>  $due_date,
                        'description'       =>  $value['rental_type'],
                        'actual_amt'        =>  $amount,
                        'balance'           =>  $amount,
                        'store_code'        =>  $value['store_code'],
                        'tag'               =>  'Posted'
                    );

                    $this->app_model->insert('invoicing', $invoiceData);


                    $ledgerData = array(
                        'posting_date'      =>  $due_date,
                        'transaction_date'  =>  $due_date,
                        'document_type'     =>  $doc_type,
                        'ref_no'            =>  $ref_no,
                        'doc_no'            =>  $doc_no,
                        'posting_date'      =>  $due_date,
                        'tenant_id'         =>  $value['tenant_id'],
                        'contract_no'       =>  $value['contract_no'],
                        'description'       =>  $flag . '-' . $value['trade_name'],
                        'debit'             =>  0,
                        'credit'            =>  $amount,
                        'balance'           =>  $amount * -1,
                        'due_date'          =>  $due_date,
                        'charges_type'      =>  $flag
                    );

                    $this->app_model->insert('ledger', $ledgerData);


                    $GLData = array(
                        'posting_date'      =>  $due_date,
                        'transaction_date'  =>  $due_date,
                        'due_date'          =>  $due_date,
                        'document_type'     =>  $doc_type,
                        'posting_date'      =>  $due_date,
                        'ref_no'            =>  $gl_refNo,
                        'doc_no'            =>  $doc_no,
                        'tenant_id'         =>  $value['tenant_id'],
                        'gl_accountID'      =>  $gl_code,
                        'company_code'      =>  '01.04',
                        'department_code'   => '01.04',
                        'debit'             => $amount,
                        'tag'               => $tag,
                        'prepared_by'       => $this->session->userdata('id')
                    );
                    $this->app_model->insert('general_ledger', $GLData);
                    $this->app_model->insert('subsidiary_ledger', $GLData);

                }


                $this->db->trans_complete(); // End of transaction function

                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $response['msg'] = "Error";
                }
                else
                {
                    $response['msg'] = "Success";
                }

            }

            echo json_encode($response);
        }

    }



    public function generate_paymentProofList()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $trade_name = $this->sanitize($this->input->post('trade_name'));
            $tenant_id = $this->sanitize($this->input->post('tenant_id'));
            $address = $this->sanitize($this->input->post('address'));
            $tin = $this->sanitize($this->input->post('tin'));
            $tenant_type = $this->sanitize($this->input->post('tenant_type'));
            $date_created = $this->sanitize($this->input->post('date'));
            $from = $this->sanitize($this->input->post('from'));
            $to = $this->sanitize($this->input->post('to'));
            $response = array();
            $result = $this->app_model->get_paymentProofList($tenant_id, $from, $to);
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();


            if ($result)
            {
                $store_code = $this->app_model->tenant_storeCode($tenant_id);
                $store_details = $this->app_model->store_details(trim($store_code));

                $pdf = new FPDF('p','mm','A4');
                $pdf->AddPage();
                $pdf->setDisplayMode ('fullpage');

                // ==================== Receipt Header ================== //
                foreach ($store_details as $detail)
                {
                    $pdf->setFont ('times','',12);
                    $pdf->cell(0, 10, $detail['store_name'], 0, 0, 'C');
                    $pdf->ln();
                    $pdf->cell(0, 0, $detail['store_address'], 0, 0, 'C');

                    $pdf->setFont ('times','B',15);
                    $pdf->ln();
                    $pdf->cell(0, 10, "Payment Proof List", 0, 0, 'C');

                    $pdf->ln();
                    $pdf->ln();
                }


                // ==================== Tenant Details ================== //

                $pdf->setFont('times','',10);

                $pdf->cell(30, 8, "Trade Name", 0, 0, 'L');
                $pdf->cell(60, 8, $trade_name, 1, 0, 'L');
                $pdf->cell(5, 8, " ", 0, 0, 'L');
                $pdf->cell(30, 8, "Tenant Type", 0, 0, 'L');
                $pdf->cell(60, 8, $tenant_type, 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 8, "Tenant ID", 0, 0, 'L');
                $pdf->cell(60, 8, $tenant_id, 1, 0, 'L');
                $pdf->cell(5, 8, " ", 0, 0, 'L');
                $pdf->cell(30, 8, "TIN", 0, 0, 'L');
                $pdf->cell(60, 8, $tin, 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 8, "Address", 0, 0, 'L');
                $pdf->cell(60, 8, $address, 1, 0, 'L');
                $pdf->cell(5, 8, " ", 0, 0, 'L');
                $pdf->cell(30, 8, "Date", 0, 0, 'L');
                $pdf->cell(60, 8, $date_created, 1, 0, 'L');


                $pdf->ln();
                $pdf->ln();
                $pdf->cell(0, 8, "-----------------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0, 'L');
                $pdf->ln();
                $pdf->ln();

                // =================== Payment Table ============= //
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 8, "Payment Date", 0, 0, 'L');
                $pdf->cell(30, 8, "SOA No.", 0, 0, 'L');
                $pdf->cell(30, 8, "Tender Type", 0, 0, 'L');
                $pdf->cell(30, 8, "OR #", 0, 0, 'L');
                $pdf->cell(30, 8, "Amount Paid", 0, 0, 'R');


                $pdf->setFont('times','',10);

                foreach ($result as  $value)
                {
                    $pdf->ln();
                    $pdf->cell(30, 8, $value['posting_date'], 0, 0, 'L');
                    $pdf->cell(30, 8, $value['soa_no'], 0, 0, 'L');
                    $pdf->cell(30, 8, $value['tender_typeDesc'], 0, 0, 'L');
                    $pdf->cell(30, 8, $value['receipt_no'], 0, 0, 'L');
                    $pdf->cell(30, 8, number_format($value['amount_paid'], 2), 0, 0, 'R');
                }


                // ======================= Prepared By ====================== //
                $preparedby = $this->app_model->get_preparedBy();
                $pdf->setFont('times','B',10);
                $pdf->ln();
                $pdf->ln();
                $pdf->ln();
                $pdf->ln();
                $pdf->cell(150, 4, "Prepared by: " . $preparedby, 0, 0, 'L');


                $file_name =  $tenant_id . $timeStamp . '.pdf';
                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');

                $response['msg'] = 'Success';
            } else {
                $response['msg'] = 'No Payment History';
            }

            echo json_encode($response);
        }
    }


    public function generate_ar_ar_summary()
    {
        $tenant_type = $this->sanitize($this->input->post('tenant_type'));
        $month       = $this->sanitize($this->input->post('month'));

        $previous_month = date("F Y", strtotime($month . ' -1 months'));
        $total_due = $this->app_model->current_receivable($tenant_type, $month);
        $current = $this->app_model->generate_monthly_receivable_summary($month);
        $previous = $this->app_model->current_receivable($tenant_type, $previous_month);
        $amount_paid = $this->app_model->get_amountPaid($previous_month, $month);
        $date        = new DateTime();
        $timeStamp   = $date->getTimestamp();
        $filename    = $timeStamp . "_RR_AR_SUMMARY_" . $month;
        $this->excel->generate_ar_ar_summary($total_due, $previous, $current, $amount_paid, $month, $filename, $tenant_type);

    }



    public function generate_CFS_remittance()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $from_date = $this->sanitize($this->input->post('from_date'));
            $to_date = $this->sanitize($this->input->post('to_date'));
            $user_id = $this->session->userdata('id');
            $total = 0;
            $response = array();
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();

            $file_name =  $user_id . $timeStamp . '.pdf';
            $pdf = new FPDF('L','mm','Letter');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            $this->db->trans_start(); // Transaction function starts here!!!

            $cashier_name = $this->app_model->get_cashierName($user_id);
            $report = $this->app_model->generate_CFS_remittance($from_date, $to_date);


            $pdf->setFont ('times','B',16);
            $pdf->cell(0, 6, 'ALTURAS GROUP OF COMPANIES', 0, 0, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(35, 35, 35);
            $pdf->ln();
            $pdf->setFont ('times','B',11);
            $pdf->cell(0, 6, "Property Management System", 0, 0, 'C');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','',10);
            $pdf->cell(0, 4, 'Date Printed: ' .  date('F j, Y h:i:s:A'), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 4, 'Transaction Date: ' . date('m/d/y', strtotime($from_date)) . ' To ' . date('m/d/y', strtotime($to_date)), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(0, 4, 'Generated By: ' .  $cashier_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','BU',10);
            $pdf->cell(0, 12, "___________________________________________________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','B',14);
            $pdf->cell(0, 4, 'Payment Report List', 0, 0, 'C');
            $pdf->ln();
            $pdf->setFont ('times','BU',14);
            $pdf->cell(0, 10, "_________________________________________________________________________________________________________", 0, 0, 'L');-
            $pdf->ln();

            // =================== Payment Table ============= //
            $pdf->setFont('times','B',10);
            $pdf->cell(30, 8, "Payment Date", 0, 0, 'L');
            $pdf->cell(80, 8, "Payor", 0, 0, 'L');
            $pdf->cell(40, 8, "TIN", 0, 0, 'L');
            $pdf->cell(30, 8, "Tender Type", 0, 0, 'L');
            $pdf->cell(30, 8, "OR #", 0, 0, 'L');
            $pdf->cell(40, 8, "Amount Paid", 0, 0, 'R');


            $pdf->setFont('times','',10);

            foreach ($report as  $value)
            {
                $pdf->ln();
                $pdf->cell(30, 8, $value['payment_date'], 0, 0, 'L');
                $pdf->cell(80, 8, $value['payor'], 0, 0, 'L');
                $pdf->cell(40, 8, $value['tin'], 0, 0, 'L');
                $pdf->cell(30, 8, $value['tender_typeDesc'], 0, 0, 'L');
                $pdf->cell(30, 8, $value['receipt_no'], 0, 0, 'L');
                $pdf->cell(40, 8, number_format($value['amount_paid'], 2), 0, 0, 'R');

                $total += $value['amount_paid'];
            }


            $pdf->ln();
            $pdf->cell(0, 8, "___________________________________________________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(210, 8, "Total Amount ", 0, 0, 'L');



            $pdf->setFont ('times','BUU',10);
            $pdf->cell(40, 8,number_format($total, 2), 0, 0, 'R');

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

            echo json_encode($response);

        }
    }


    public function RR_reports()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/RR_reports');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    

    public function AR_reports()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/AR_reports');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    /*=================== NAV EXPORTATION ===================*/
        public function generate_ARreports()
        {
            if (!$this->session->userdata('leasing_logged_in'))
                redirect('ctrl_leasing/');

            $month = $this->sanitize($this->input->post('month'));
            $month = date('F Y', strtotime($month));

            $posting_date = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
           
            $company_code =  $this->session->userdata('company_code');
            $dept_code =  $this->session->userdata('dept_code');


            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $file_name = "Other Charges " . date('Y-m', strtotime($month)) . ".txt";
            $doc_no = 'LS' .  date('mdy', strtotime(date('Y-m-t', strtotime($month))));

            
            $report_data = $this->app_model->generate_ARreports($month);


            $data        = $report_data['data'];
            $doc_nos     = $report_data['doc_nos'];

            
            // output each row of the data
            $file_data = '';

            if(!empty($data)){
                $line_no = 10000;

                $rows = [];
                foreach ($data as $result)
                {

                    if ($result['gl_code'] == '10.10.01.03.03' || $result['gl_code'] == '10.10.01.06.05') {
                        $rows[] = array(
                            'SALES',
                            'LEASING',
                            $line_no,
                            'G/L Account',
                            $result['gl_code'],
                            $posting_date,
                            'Invoice',
                            $month,
                            $doc_no,
                            $result['gl_account'],
                            'PHP',
                            $result['amount'],
                            $result['amount'],
                            '',
                            $result['amount'],
                            $result['amount'],
                            '1',
                            $company_code,
                            $dept_code,
                            'SALESJNL',
                            $result['amount'],
                            $result['amount'] * -1,
                            $posting_date,
                            $result['amount'],
                            $result['amount'] * -1
                        );
                    }
                    else
                    {
                        $rows[] = array(
                            'SALES',
                            'LEASING',
                            $line_no,
                            'G/L Account',
                            $result['gl_code'],
                            $posting_date,
                            'Invoice',
                            $month,
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
                            'SALESJNL',
                            $result['amount'],
                            $result['amount'] * -1,
                            $posting_date,
                            $result['amount'],
                            $result['amount'] * -1
                        );
                    }

                    $line_no += 10000;
                }

                $exp_batch_no = $this->app_model->generate_ExportNo(true);

                //Tag Export
                foreach ($doc_nos as $doc_no) {
                    $this->app_model->updateEntryAsExported($doc_no, $exp_batch_no);
                }

                $file_data = arrayToString($rows);

                $filter_date = date('Y-m', strtotime($posting_date));

                $file_name = "Other Charges $filter_date - " .$exp_batch_no. ".txt";

                //$insert to export log
                $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Other Charges', $file_name);

            }

            download_send_headers($file_name, $file_data);
        }

        public function generate_RRreports()
        {
            if (!$this->session->userdata('leasing_logged_in'))
                redirect('ctrl_leasing/');
        
            $month = $this->sanitize($this->input->post('month'));
            $month = date('F Y', strtotime($month));

            //dd($month);

            $posting_date = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
            
            $company_code =  $this->session->userdata('company_code');
            $dept_code    = $this->session->userdata('dept_code');


            $file_name = "Basic Rent " . date('Y-m', strtotime($month)) . ".txt";
            $doc_no = 'LS' .  date('mdy', strtotime(date('Y-m-t', strtotime($month))));


            $report_data = $this->app_model->generate_RRreports($month);

            $data        = $report_data['data'];
            $doc_nos     = $report_data['doc_nos'];


            $file_data = '';

            // output each row of the data

            if(!empty($data)){

                $rows = [];
                $line_no = 10000;
               
                foreach ($data as $result)
                {
                    if ($result['gl_code'] == '10.10.01.03.16' || $result['gl_code'] == '10.10.01.06.05') {
                        $rows[] = array(
                            'SALES',
                            'LEASING',
                            $line_no,
                            'G/L Account',
                            $result['gl_code'],
                            $posting_date,
                            'Invoice',
                            $month,
                            $doc_no,
                            $result['gl_account'],
                            'PHP',
                            $result['amount'],
                            $result['amount'],
                            '',
                            $result['amount'],
                            $result['amount'],
                            '1',
                            $company_code,
                            $dept_code,
                            'SALESJNL',
                            $result['amount'],
                            $result['amount'] * -1,
                            $posting_date,
                            $result['amount'],
                            $result['amount'] * -1
                        );
                    }
                    else
                    {
                        $rows[] = array(
                            'SALES',
                            'LEASING',
                            $line_no,
                            'G/L Account',
                            $result['gl_code'],
                            $posting_date,
                            'Invoice',
                            $month,
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
                            'SALESJNL',
                            $result['amount'],
                            $result['amount'] * -1,
                            $posting_date,
                            $result['amount'],
                            $result['amount'] * -1
                        );
                    }

                    $line_no += 10000;
                }

                $exp_batch_no = $this->app_model->generate_ExportNo(true);

                //Tag Export
                foreach ($doc_nos as $doc_no) {
                    $this->app_model->updateEntryAsExported($doc_no, $exp_batch_no);
                }

                $file_data = arrayToString($rows);

                $filter_date = date('Y-m', strtotime($posting_date));

                $file_name = "Basic Rent $filter_date - " .$exp_batch_no. ".txt";

                //$insert to export log
                $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Basic Rent', $file_name);

            }


            download_send_headers($file_name, $file_data);
        }

        public function nav_export_history()
        {
            if ($this->session->userdata('leasing_logged_in')) {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/nav_export_history');
                $this->load->view('leasing/footer');
            }
            else

            {
                redirect('ctrl_leasing/');
            }
        }

        public function get_nav_export_data(){
            $data =  $this->app_model->get_nav_export_data();
            JSONResponse($data);
        }

        public function generate_paymentCollection(){

            $store      = $this->session->userdata('store_code');
            $post_date  = $this->sanitize($this->input->post('month'));

            $post_date = date('Y-m', strtotime($post_date));
        
            $entries = $this->db->query("
                SELECT 
                    tbl.*,
                    SUM(tbl.db) AS debit,
                    SUM(tbl.cr) AS credit,
                    sl2.partnerID,
                    SUM(tbl.db + tbl.cr) AS amount
                FROM
                    (SELECT 
                        g.id,
                        g.tenant_id,
                        g.posting_date,
                        g.due_date,
                        g.doc_no,
                        g.ref_no,
                        g.document_type,
                        p.trade_name,
                        g.gl_accountID,
                        g.bank_code,
                        g.bank_name,
                        g.company_code,
                        g.department_code,
                        a.gl_account,
                        SUM(IFNULL(g.debit, 0)) db,
                        SUM(IFNULL(g.credit, 0)) cr,
                        SUM(IFNULL(g.debit, 0) + IFNULL(g.credit, 0)) amt
                    FROM
                        subsidiary_ledger AS g
                    LEFT JOIN (
                        SELECT 
                            DISTINCT(tnt.prospect_id) AS prospect_id,
                            tnt.tenant_id,
                            tnt.store_code
                        FROM
                            tenants tnt) t 
                    ON t.tenant_id = g.tenant_id

                    LEFT JOIN 
                        prospect p 
                    ON p.id = t.prospect_id

                    LEFT JOIN 
                        gl_accounts a 
                    ON a.id = g.gl_accountID

                    WHERE
                        DATE(g.posting_date) LIKE '%$post_date%'
                    AND t.store_code = '$store'
                    AND (g.document_type = 'Payment' OR g.document_type = 'Payment Adjustment')
                    AND (g.tenant_id <> 'DELETED'
                    AND g.ref_no <> 'DELETED'
                    AND g.doc_no <> 'DELETED')
                    AND g.tenant_id <> 'ICM-LT000064'
                    AND (g.export_batch_code IS NULL
                    OR g.export_batch_code = '')

                    GROUP BY 
                        g.doc_no, g.ref_no, g.gl_accountID, g.tenant_id, g.posting_date/*, g.document_type*/
                    HAVING 
                        amt <> 0
                    ORDER BY 
                        g.document_type , g.doc_no) 
                AS tbl
                LEFT JOIN
                    (SELECT 
                        s.*, s.gl_accountID AS partnerID
                    FROM
                        subsidiary_ledger s
                    WHERE
                        s.debit IS NULL 
                    AND s.credit IS NOT NULL
                    AND (s.document_type = 'Payment' OR s.document_type = 'Payment Adjustment')
                    GROUP BY 
                        s.doc_no, s.ref_no , s.credit) sl2 
                ON tbl.doc_no = sl2.doc_no
                AND tbl.ref_no = sl2.ref_no
                AND tbl.db <> 0
                AND tbl.db = ABS(sl2.credit)
                AND tbl.tenant_id = sl2.tenant_id
                AND tbl.document_type = sl2.document_type
                GROUP BY 
                    tbl.doc_no, tbl.gl_accountID, tbl.posting_date, tbl.document_type, sl2.partnerID
                ORDER BY 
                    tbl.posting_date, tbl.doc_no, tbl.id ,credit DESC , debit DESC , tbl.trade_name , tbl.ref_no")
            ->result_object();


            $file_name = "Payment Collection $post_date.txt";

            $data = '';

            if(!empty($entries)){
                

                $line_no = 10000;
                $data_csv = [];
                $doc_nos = [];

                foreach ($entries as $key => $entry) {
                   
                    if($entry->amount == 0 ) continue;

                    $doc_no = 'PR' .  date('mdy', strtotime($entry->posting_date));

                    if($entry->gl_accountID == 3){

                        $data_csv[] = array(
                            'CASH RECEI',
                            'LS COLL',
                            $line_no,
                            'Bank Account',
                            $entry->bank_code,
                            date('m/d/Y', strtotime($entry->posting_date)),
                            $entry->document_type == 'Payment Adjustment' ? '' : 'Payment',
                            strval($entry->doc_no),
                            $doc_no,
                            $entry->bank_name . ' - ' . $store,
                            'PHP',
                            $entry->amount,
                            $entry->amount,
                            '',
                            $entry->amount,
                            $entry->amount,
                            '1',
                            $entry->company_code,
                            $entry->department_code,
                            'PAYMENTJNL',
                            $entry->amount,
                            $entry->amount * -1,
                            date('m/d/Y', strtotime($entry->posting_date)),
                            'Bank Account',
                            $entry->bank_code,
                            $entry->amount,
                            $entry->amount * -1
                        );

                    } else{

                        $gl = $this->db->select('*')
                            ->from('gl_accounts')
                            ->where(['id'=>$entry->gl_accountID])
                            ->limit(1)
                            ->get()
                            ->row();


                        $data_csv[] = array(
                            'CASH RECEI',
                            'LS COLL',
                            $line_no,
                            'G/L Account',
                            $gl->gl_code,
                            date('m/d/Y', strtotime($entry->posting_date)),
                            $entry->document_type == 'Payment Adjustment' ? '' : 'Payment',
                            strval($entry->doc_no),
                            $doc_no,
                            $gl->gl_account,
                            'PHP',
                            $entry->amount,
                            $entry->amount > 0  ? abs($entry->amount) : '',
                            $entry->amount < 0  ? abs($entry->amount) : '',
                            $entry->amount,
                            $entry->amount,
                            '1',
                            $entry->company_code,
                            $entry->department_code,
                            'PAYMENTJNL',
                            $entry->amount,
                            $entry->amount * -1,
                            date('m/d/Y', strtotime($entry->posting_date)),
                            '',
                            '',
                            $entry->amount,
                            $entry->amount * -1
                        );

                    }

                    $line_no += 10000;

                    if(!in_array($entry->doc_no, $doc_nos)){
                        $doc_nos[] = $entry->doc_no;
                    }
                }

                $exp_batch_no = $this->app_model->generate_ExportNo(true);
                //Tag Export
                foreach ($doc_nos as $doc_no) {
                    $this->app_model->updateEntryAsExported($doc_no, $exp_batch_no);
                }

                $data = arrayToString($data_csv);

                $file_name = "Payment Collection $post_date - " .$exp_batch_no. ".txt";

                //$insert to export log
                $this->app_model->logExportForNav($exp_batch_no, $post_date, $data, 'Payment', $file_name);

            }  

            download_send_headers($file_name, $data);
        }

        public function redownloadNavTextfile($id = null){
            $id = $this->sanitize($id);
            $file = $this->app_model->getExportForNavFile($id);

            if(!$file) die('File not found!');

            download_send_headers($file->file_name, $file->data);
        }

        public function generate_ExportNo(){
            $seq = $this->app_model->generate_ExportNo(false);

            var_dump($seq);
        }

        public function generate_paymentCollectionByDocno(){
            if (!$this->session->userdata('leasing_logged_in'))
                redirect('ctrl_leasing/');

            $doc_nos = $this->sanitize($this->input->post('doc_nos'));
            $doc_nos = array_map('trim', explode(',', $doc_nos));

            foreach ($doc_nos as $key => $docno) {
                if($docno == '') array_splice($doc_nos, $key, 1);
            }

            $name_ext = implode(" ", $doc_nos);
            $filter =  implode(', ',$doc_nos);
            $doc_nos = "'".implode("', '", $doc_nos)."'";

            
            $company_code = $this->session->userdata('company_code');
            $dept_code    = $this->session->userdata('dept_code');
            $store_code   = $this->session->userdata('store_code') . " Leasing";
    

            $name_ext = strlen($name_ext) > 30 ? substr($name_ext,0,30).'...' : $name_ext;
            $file_name = "Payment Collection $name_ext.txt";


            $reportData            = $this->app_model->generate_paymentCollectionByDocno($doc_nos);

            // output each row of the data
            
           

            if(!empty($reportData)){
                $rows = [];
                $line_no = 10000;

                $docs = [];
                foreach ($reportData as $result)
                {
                    if($result->debit == 0 && $result->credit == 0) continue;

                    if(!in_array($result->doc_no, $docs)){
                        $docs[] = $result->doc_no;
                    }

                    $doc_no = 'PR' .  date('mdy', strtotime($result->posting_date));

                    if($result->gl_accountID == 3){

                        $debit = $result->debit ? (float) $result->debit:  0;
                        $credit = $result->credit ? (float) $result->credit : 0;

                        $amount = $debit + $credit;

                        $rows[] = array(
                            'CASH RECEI',
                            'LS COLL',
                            $line_no,
                            'Bank Account',
                            $result->bank_code,
                            date('m/d/Y', strtotime($result->posting_date)),
                            $result->document_type == 'Payment Adjustment' ? '' : 'Payment',
                            strval($result->doc_no),
                            $doc_no,
                            $result->bank_name . ' - ' . $store_code,
                            'PHP',
                            $amount,
                            $amount,
                            '',
                            $amount,
                            $amount,
                            '1',
                            $company_code,
                            $dept_code,
                            'PAYMENTJNL',
                            $amount,
                            $amount * -1,
                            date('m/d/Y', strtotime($result->posting_date)),
                            'Bank Account',
                            $result->bank_code,
                            $amount,
                            $amount * -1
                        );

                    } else{

                        $debit = $result->debit ? (float) $result->debit:  0;
                        $credit = $result->credit ? (float) $result->credit : 0;

                        $amount = $debit + $credit;

                        //var_dump($debit, $credit,  $amount);

                        $rows[] = array(
                            'CASH RECEI',
                            'LS COLL',
                            $line_no,
                            'G/L Account',
                            $result->gl_code,
                            date('m/d/Y', strtotime($result->posting_date)),
                            $result->document_type == 'Payment Adjustment' ? '' : 'Payment',
                            strval($result->doc_no),
                            $doc_no,
                            $result->gl_account,
                            'PHP',
                            $amount,
                            $amount > 0  ? abs($amount) : '',
                            $amount < 0  ? abs($amount) : '',
                            $amount,
                            $amount,
                            '1',
                            $company_code,
                            $dept_code,
                            'PAYMENTJNL',
                            $amount,
                            $amount * -1,
                            date('m/d/Y', strtotime($result->posting_date)),
                            '',
                            '',
                            $amount,
                            $amount * -1
                        );

                    }
                    $line_no += 10000;
                    
                }

                $exp_batch_no = $this->app_model->generate_ExportNo(true);

                //Tag Export
                foreach ($docs as $doc) {
                    $this->app_model->updateEntryAsExported($doc, $exp_batch_no);
                }

                $file_data = arrayToString($rows);

                $file_name = "Payment Collection $name_ext -" .$exp_batch_no . ".txt";

                //$insert to export log
                $this->app_model->logExportForNav($exp_batch_no, $filter, $file_data, 'Payment', $file_name);

            }

            download_send_headers($file_name, $file_data);   
        }

        function removeExportationTag($batch_code= ''){
            if($this->session->userdata('user_type') != 'Administrator')
                JSONResponse(['type'=>'error', 'msg'=>'Unauthorize action!']);

            $batch_code = $this->sanitize($batch_code);

            $result = $this->app_model->removeExportationTag($batch_code);

            if($result){
                JSONResponse(['type'=>'success', 'msg'=>'Untagging Success!']);
            }

            JSONResponse(['type'=>'error', 'msg'=>'Untagging Failed!']);
        }
    /*=================== END OF NAV EXPORTATION ===================*/

    public function collection_reports()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/collection_reports');
            $this->load->view('leasing/footer');
        }
        else

        {
            redirect('ctrl_leasing/');
        }
    }

    public function collection_reports_manual()
    {


        if ($this->session->userdata('leasing_logged_in')) {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/collection_report_manual');
            $this->load->view('leasing/footer');
        }
        else

        {
            redirect('ctrl_leasing/');
        }
    }

    public function bank_recon()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/bank_recon');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function generate_forBankRecon()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $from_date = $this->sanitize($this->input->post('from_date'));
            $to_date = $this->sanitize($this->input->post('to_date'));

            $reportData = $this->app_model->generate_forBankRecon($from_date, $to_date);

            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename ="for_recon_" . $timeStamp;
            $this->excel->generate_forBankRecon($reportData ,$filename);

        }
    }

    public function receipts_audit()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/receipts_audit');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function generate_receiptsAudit()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $month = $this->sanitize($this->input->post('month'));
            $reportData = $this->app_model->generate_receiptsAudit($month);
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $storename = $this->app_model->my_store();
            $filename ="Receipt Audit " . $month;
            $this->excel->generate_receiptsAudit($reportData, $storename, $month, $filename);

        }
    }


    public function RR_AR_ledger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/RR_AR_ledger');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function generate_RR_ARLedger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $month = $this->sanitize($this->input->post('month'));
            $company_code = "";
            $dept_code = "";
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0' || $this->session->userdata('user_group') == NULL)
            {
                $company_code = "";
            }
            else
            {
                $company_code = $this->session->userdata('company_code');
                $dept_code    = $this->session->userdata('dept_code');
            }

            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $file_name = "Rent and Other Charges_" . $timeStamp . ".csv";
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename=' . $file_name);
            // do not cache the file
            header('Pragma: no-cache');
            header('Expires: 0');
            $file = fopen('php://output', 'w');
            // send the column headers
            $header = array('Journal Template Name', 'Journal Batch Name', 'Line No.', 'Account Type', 'Account No.', 'Posting Date', 'Document Type', 'External Document No.', 'Document No.', 'Description', 'Currency Code', 'Amount', 'Debit Amount', 'Credit Amount', 'Amount (LCY)', 'Balance (LCY)', 'Currency Factor', 'Company code Code', 'Department Code', 'Source Code', 'VAT Base Amount', 'Bal. VAT Base Amount', 'Document Date', 'VAT Base Amount (LCY)', 'Bal. VAT Base Amount (LCY)');
            fputcsv($file,$header,',','"');

            $reportData = $this->app_model->generate_RR_ARLedger($month);

            // output each row of the data
            $line_no = 10000;
            foreach ($reportData as $result)
            {

                if ($result['document_type'] == 'Invoice')
                {

                    if ($result['gl_code'] == '10.10.01.03.16' || $result['gl_code'] == '10.10.01.06.05' || $result['gl_code'] == '10.10.01.03.03' || $result['gl_code'] == '10.10.01.03.04')
                    {
                        $row = array(
                            'SALES',
                            'LEASING',
                            $line_no,
                            'G/L Account',
                            $result['gl_code'],
                            $result['posting_date'],
                            $result['document_type'],
                            '',
                            $result['doc_no'],
                            $result['gl_account'],
                            'PHP',
                            $result['debit'],
                            $result['debit'],
                            '',
                            $result['debit'],
                            $result['debit'],
                            '1',
                            $company_code,
                            $dept_code,
                            'SALESJNL',
                            $result['debit'],
                            $result['debit'] * -1,
                            $result['posting_date'],
                            $result['debit'],
                            $result['debit'] * -1
                        );
                    }
                    else
                    {
                        $row = array(
                            'SALES',
                            'LEASING',
                            $line_no,
                            'G/L Account',
                            $result['gl_code'],
                            $result['posting_date'],
                            $result['document_type'],
                            '',
                            $result['doc_no'],
                            $result['gl_account'],
                            'PHP',
                            $result['credit'] * -1,
                            '',
                            $result['credit'],
                            $result['credit'] * -1,
                            $result['credit'] * -1,
                            '1',
                            $company_code,
                            $dept_code,
                            'SALESJNL',
                            $result['credit'] * -1,
                            $result['credit'],
                            $result['posting_date'],
                            $result['credit'] * -1,
                            $result['credit']
                        );
                    }
                }
                else
                {
                    if ($result['gl_code'] == '10.10.01.03.16' || $result['gl_code'] == '10.10.01.06.05' || $result['gl_code'] == '10.10.01.03.03' || $result['gl_code'] == '10.10.01.03.04')
                    {
                        $row = array(
                            'SALES',
                            'LEASING',
                            $line_no,
                            'G/L Account',
                            $result['gl_code'],
                            $result['posting_date'],
                            $result['document_type'],
                            '',
                            $result['doc_no'],
                            $result['gl_account'],
                            'PHP',
                            $result['credit'] * -1,
                            '',
                            $result['credit'],
                            $result['credit'] * -1,
                            $result['credit'] * -1,
                            '1',
                            $company_code,
                            $dept_code,
                            'SALESJNL',
                            $result['credit'] * -1,
                            $result['credit'],
                            $result['posting_date'],
                            $result['credit'] * -1,
                            $result['credit']
                        );

                    }
                    else
                    {
                        $row = array(
                            'SALES',
                            'LEASING',
                            $line_no,
                            'G/L Account',
                            $result['gl_code'],
                            $result['posting_date'],
                            $result['document_type'],
                            '',
                            $result['doc_no'],
                            $result['gl_account'],
                            'PHP',
                            $result['debit'],
                            $result['debit'],
                            '',
                            $result['debit'],
                            $result['debit'],
                            '1',
                            $company_code,
                            $dept_code,
                            'SALESJNL',
                            $result['debit'],
                            $result['debit'] * -1,
                            $result['posting_date'],
                            $result['debit'],
                            $result['debit'] * -1
                        );
                    }
                }

                fputcsv($file, $row);

                $line_no += 10000;
            }
            exit();
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function payment_ledger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/payment_ledger');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function generate_paymentLedger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $month = $this->sanitize($this->input->post('month'));
            $company_code = "";
            $dept_code = "";
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0' || $this->session->userdata('user_group') == NULL)
            {
                $company_code = "";
            }
            else
            {
                $company_code = $this->session->userdata('company_code');
                $dept_code    = $this->session->userdata('dept_code');
            }

            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $file_name = "Payment Ledger_" . $timeStamp . ".csv";
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename=' . $file_name);
            // do not cache the file
            header('Pragma: no-cache');
            header('Expires: 0');
            $file = fopen('php://output', 'w');
            // send the column headers
            $header = array('Journal Template Name', 'Journal Batch Name', 'Line No.', 'Account Type', 'Account No.', 'Posting Date', 'Document Type', 'External Document No.', 'Document No.', 'Description', 'Currency Code', 'Amount', 'Debit Amount', 'Credit Amount', 'Amount (LCY)', 'Balance (LCY)', 'Currency Factor', 'Company code Code', 'Department Code', 'Source Code', 'VAT Base Amount', 'Bal. VAT Base Amount', 'Document Date', 'VAT Base Amount (LCY)', 'Bal. VAT Base Amount (LCY)');
            fputcsv($file,$header,',','"');

            $reportData = $this->app_model->generate_paymentLedger($month);

            // output each row of the data
            $line_no = 10000;
            foreach ($reportData as $result)
            {

                if ($result['debit'] > 0)  // If debit
                {
                    $row = array(
                        'CASH RECEI',
                        'LS COLL',
                        $line_no,
                        'G/L Account',
                        $result['gl_code'],
                        $result['posting_date'],
                        $result['document_type'],
                        '',
                        $result['doc_no'],
                        $result['gl_account'],
                        'PHP',
                        $result['debit'],
                        $result['debit'],
                        '',
                        $result['debit'],
                        $result['debit'],
                        '1',
                        $company_code,
                        $dept_code,
                        'PAYMENTJNL',
                        $result['debit'],
                        $result['debit'] * -1,
                        $result['posting_date'],
                        '',
                        '',
                        $result['debit'],
                        $result['debit'] * -1
                    );
                }
                else
                {
                    $row = array(
                        'CASH RECEI',
                        'LS COLL',
                        $line_no,
                        'G/L Account',
                        $result['gl_code'],
                        $result['posting_date'],
                        $result['document_type'],
                        '',
                        $result['doc_no'],
                        $result['gl_account'],
                        'PHP',
                        $result['credit'] * -1,
                        '',
                        $result['credit'],
                        $result['credit'] * -1,
                        $result['credit'] * -1,
                        '1',
                        $company_code,
                        $dept_code,
                        'PAYMENTJNL',
                        $result['credit'] * 1,
                        $result['credit'],
                        $result['posting_date'],
                        '',
                        '',
                        $result['credit'] * -1,
                        $result['credit']
                    );
                }

                fputcsv($file, $row);

                $line_no += 10000;
            }
            exit();
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    // ============================= Lyle Additonal ======================================== //

    public function save_adj_print_log()
    {
        $document_name  = $this->input->post('document');
        $tenant_id      = $this->input->post('tenant_id');
        $printed_by     = $this->session->userdata('id');
        $doc_type       = $this->input->post('doc_type');
        $adj_no         = $this->input->post('adj_no');
        $date           = new DateTime();
        $timeStamp      = $date->getTimestamp();
        $printed_on     = date('Y-m-d', $timeStamp);
        $response       = array();

        $data = 
        [
            'document_name' => $document_name,
            'printed_by'    => $printed_by,
            'printed_date'  => $printed_on
        ];

        if (!empty($data)) 
        {
            $this->db->trans_start(); // Transaction function starts here!!!

            $this->app_model->insert('adjustment_print_log', $data);

            $print_count = $this->app_model->count_print_adj($document_name);
            $printData   = $this->app_model->get_adjustmentsData1($document_name);                                             

            $file_name = $tenant_id . $printData->adj_no . '.pdf';

            if ($print_count == null || $print_count == '') 
            {
               $count = 1;
            }
            else
            {
                $count = $print_count;
            }

            // echo $memoType . " - " . $doc_type . " - " . ABS($amount) . " - " . $count . " - " . $file_name;

            $store_code      = $this->app_model->tenant_storeCode($tenant_id);
            $store_details   = $this->app_model->store_details(trim($store_code));
            $prospect_id     = $this->app_model->get_pID($tenant_id);
            $trade_name      = $this->app_model->get_tenantName($prospect_id);

            $pdf = new FPDF('p','mm','A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            $logoPath = getcwd() . '/assets/other_img/';

            if ($doc_type == 'Invoice Adjustment') 
            {
                // ============================ PDF INVOICE ================================================================================ //

                    // ==================== Receipt Header ================== //

                    if ($printData->debit == '' || $printData->debit == null) 
                    {
                        $memoType = 'Credit Memo';
                        $amount = $printData->credit;
                    }
                    else
                    {
                        $memoType = 'Debit Memo';
                        $amount = $printData->debit;
                    }

                    if ($printData->gl_accountID == '22' || $printData->gl_accountID == '29') 
                    {
                        $doc_type = 'Other Charges';
                    }
                    else
                    {
                        $doc_type = 'Basic Rent';
                    }

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

                        $pdf->ln();
                        $pdf->setFont ('times','B',10);
                        $pdf->cell(0, 5, "Print No: " . $print_count, 0, 0, 'L');
                        $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store_name) , 0, 0, 'R');
                        $pdf->ln();
                        $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->ln();

                        $pdf->setFont ('times','B',16);
                        $pdf->cell(0, 6, $memoType, 0, 0, 'C'); // Memo Type 
                        $pdf->ln();
                        $pdf->ln();
                        $pdf->ln();

                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "Document No.", 0, 0, 'L');
                        $pdf->cell(10, 4, " : ", 0, 0, 'C');
                        $pdf->setFont('times','',10);  
                        $pdf->cell(15, 4, $printData->adj_no, 0, 0, 'L');

                        $pdf->cell(75, 4, " ", 0, 0, 'L'); 

                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "Date Adjusted", 0, 0, 'L');
                        $pdf->cell(10, 4, " : ", 0, 0, 'C');
                        $pdf->setFont('times','',10);  
                        $pdf->cell(15, 4, $printData->transaction_date, 0, 0, 'L');

                        $pdf->ln();

                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "Tenant ID", 0, 0, 'L');
                        $pdf->cell(10, 4, " : ", 0, 0, 'C');
                        $pdf->setFont('times','',10);  
                        $pdf->cell(15, 4, $tenant_id, 0, 0, 'L');

                        $pdf->cell(75, 4, " ", 0, 0, 'L'); 

                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "Posting Date", 0, 0, 'L');
                        $pdf->cell(10, 4, " : ", 0, 0, 'C');
                        $pdf->setFont('times','',10);  
                        $pdf->cell(15, 4, $printData->posting_date, 0, 0, 'L');

                        $pdf->ln();
                        $pdf->ln();

                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "Adjusted the account of", 0, 0, 'L');
                        $pdf->cell(10, 4, " : ", 0, 0, 'C');
                        $pdf->setFont('times','',10); 
                        $pdf->cell(15, 4, $trade_name, 0, 0, 'L');
                        $pdf->setFont('times','',10);

                        $pdf->ln();

                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "Amounting to ", 0, 0, 'L');
                        $pdf->cell(10, 4, " : ", 0, 0, 'C');
                        $pdf->setFont('times','B',11);
                        $pdf->cell(25, 4, DecimalToWord::convert(ABS(str_replace(",", "", $amount)), 'Pesos', 'Centavos') . " only " . " (" . DecimalToWord::$formatted . ")", 0, 0, 'L');
                        $pdf->setFont('times','',10);

                        $pdf->ln();
                        $pdf->ln();

                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "Document Type", 0, 0, 'L');
                        $pdf->cell(10, 4, " : ", 0, 0, 'C');
                        $pdf->setFont('times','',10);  
                        $pdf->cell(15, 4, $doc_type, 0, 0, 'L');

                        $pdf->ln();

                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "Invoice No.", 0, 0, 'L');
                        $pdf->cell(10, 4, " : ", 0, 0, 'C');
                        $pdf->setFont('times','',10);  
                        $pdf->cell(15, 4, $printData->doc_no, 0, 0, 'L');

                        $pdf->ln();

                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "Reason For Adjusting", 0, 0, 'L');
                        $pdf->cell(10, 4, " : ", 0, 0, 'C');
                        $pdf->setFont('times','',10); 
                        $pdf->cell(25, 4, $printData->adj_reason, 0, 0, 'L');
                        $pdf->setFont('times','',10);

                        $pdf->ln();

                        $pdf->SetTextColor(201, 201, 201);
                        $pdf->SetFillColor(35, 35, 35);
                        $pdf->cell(20, 6, " ", 0, 0, 'R');

                        $pdf->ln();

                        $pdf->cell(10, 6, " ", 0, 0, 'R');
                        $pdf->setFont ('times','',9);
                        $pdf->cell(35, 6, "Doc No", 1, 0, 'C', TRUE);
                        $pdf->cell(35, 6, "Description", 1, 0, 'C', TRUE);
                        $pdf->cell(35, 6, "Amount", 1, 0, 'C', TRUE);
                        $pdf->cell(35, 6, "Adjustment", 1, 0, 'C', TRUE);
                        $pdf->cell(35, 6, "Net Amount", 1, 0, 'C', TRUE);

                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->ln();

                        $pdf->cell(10, 6, " ", 0, 0, 'R');
                        $pdf->setFont ('times','',9);
                        $pdf->cell(35, 6, $printData->doc_no, 1, 0, 'C');
                        $pdf->cell(35, 6, $doc_type, 1, 0, 'C');
                        $pdf->cell(35, 6, "P " . number_format($printData->prev_amount, 2, '.', ','), 1, 0, 'C');
                        $pdf->cell(35, 6, "P " . number_format($amount, 2, '.', ','), 1, 0, 'C');
                        $pdf->cell(35, 6, "P " . number_format($printData->net_balance, 2, '.', ','), 1, 0, 'C');

                        $pdf->ln();
                        $pdf->ln();

                        $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->ln();
                        $pdf->ln();
                        $pdf->ln();
                        $pdf->ln();
                        $pdf->ln();

                        $prepared_by = $this->app_model->preparedBy($this->session->userdata('id'));
                        $pdf->setFont('times','',10);
                        // $pdf->cell(0, 4, "Prepared By: " . $this->session->userdata('first_name') . " " . $this->session->userdata('last_name') . "      Check By:______________________", 0, 0, 'L');
                        $pdf->cell(0, 4, "Prepared By: " . $printData->modified_by . "      Check By:______________________", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->ln();
                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');

                        $this->db->trans_complete(); // End of transaction function

                        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                        {
                            $this->db->trans_rollback(); // If failed rollback all queries
                            $error = array('action' => 'Saving Print No', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                            $this->app_model->insert('error_log', $error);
                            $response['msg'] = 'DB_error';
                        }
                        else
                        {
                            $response['msg'] = "Success";
                            $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                            $pdf->Output('assets/pdf/' . $file_name , 'F');
                        }
            }
            else
            {

            }
        }
        else
        {
            $response['msg'] = 'Error';
        }

        echo json_encode($response);
    }

    public function fetch_adj_supp_doc()
    {
        $adj_no = $this->input->post('adj_no'); 
        
        $query = $this->db->query("SELECT * FROM `adjsupportingdoc` WHERE adj_no = '$adj_no'");
        $result = $query->result_array();

        foreach ($result as $value) 
        {
            $data[] = 
            [
                'file_name' => $value['file_name']
            ];
        }

        JSONResponse($data);

    }
}

/* End of file Leasing_reports.php */
/* Location: ./application/controllers/Leasing_reports.php */

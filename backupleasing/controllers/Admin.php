<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('admin_model');
        $this->load->model('app_model');
        $this->load->library('upload');
        $timestamp = time();
        $this->_currentDate = date('Y-m-d', $timestamp);
    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function index()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            redirect('admin/dashboard');
        }
        else
        {
            $this->load->view('admin/index');
        }
    }


    public function check_login()
    {
        $username = $this->sanitize($this->input->post('username'));
        $password = $this->sanitize($this->input->post('password'));

        $result = $this->admin_model->check_adminLogin($username, $password);
        if ($result)
        {
            redirect('admin/dashboard');
        }
        else
        {
            $this->session->set_flashdata('message', 'Invalid Login');
            redirect('admin');
        }
    }


    public function logout()
    {
        if ($this->session->userdata('admin_logged_in')) 
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
                'admin_logged_in'       => FALSE
            );


            $session = (object)$this->session->userdata;

            if(isset($session->session_id)){
                $user_session_data = ['date_ended'=> date('Y-m-d H:i:s')];

                $this->db->where('session_id', $session->session_id);
                $this->db->update('user_session', $user_session_data);
            }

            $this->session->unset_userdata($newdata);
            $this->session->sess_destroy();   
        }

        redirect('admin');
    }

    public function dashboard()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['active']              = $this->admin_model->get_activeTenants();
            $data['long_term']           = $this->admin_model->get_longtermTenants();
            $data['short_term']          = $this->admin_model->get_shorttermTenants();
            $data['lessee_type']         = $this->admin_model->get_lesseeTypeCount();
            $data['area_classification'] = $this->admin_model->get_areaClassificationCount();
            $data['area_type']           = $this->admin_model->get_areaTypeCount();
            $data['category']            = $this->admin_model->get_categoryCount();
            $this->load->view('admin/header', $data);
            $this->load->view('admin/dashboard');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function delete_entry_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $this->load->view('admin/header');
            $this->load->view('admin/delete_entry');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function cancel_soa_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $this->load->view('admin/header');
            $this->load->view('admin/cancel_soa');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }

    public function deleteEntry()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = '';
            $tenant_id       = $this->sanitize($this->input->post('tenant_id'));
            $doc_no          = $this->sanitize($this->input->post('doc_no'));

            if ($this->admin_model->deleteEntry($tenant_id, $doc_no)) 
            {
                $response['msg'] = 'Success';
            }

            echo json_encode($response);
        }
        else
        {
            redirect('admin');
        }
    }


    public function cancel_soa()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = '';
            $tenant_id       = $this->sanitize($this->input->post('tenant_id'));
            $soa_no          = $this->sanitize($this->input->post('soa_no'));

            if ($this->admin_model->cancel_soa($tenant_id, $soa_no)) 
            {
                $response['msg'] = 'Success';
            }

            echo json_encode($response);
        }
        else
        {
            redirect('admin');
        }
    }


    public function change_dueDate_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $this->load->view('admin/header');
            $this->load->view('admin/change_dueDate');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function change_dueDate()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = '';
            $tenant_id       = $this->sanitize($this->input->post('tenant_id'));
            $doc_no          = $this->sanitize($this->input->post('doc_no'));
            $due_date        = $this->sanitize($this->input->post('due_date'));
            if ($this->admin_model->change_dueDate($tenant_id, $doc_no, $due_date)) 
            {
                $response['msg'] = 'Success';
            }
            echo json_encode($response);
        }
    }


    public function change_postingDate_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $this->load->view('admin/header');
            $this->load->view('admin/change_postingDate');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }

    public function change_postingDate()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = '';
            $tenant_id       = $this->sanitize($this->input->post('tenant_id'));
            $doc_no          = $this->sanitize($this->input->post('doc_no'));
            $posting_date    = $this->sanitize($this->input->post('posting_date'));
            if ($this->admin_model->change_postingDate($tenant_id, $doc_no, $posting_date)) 
            {
                $response['msg'] = 'Success';
            }
            echo json_encode($response);
        }
    }


    public function change_SOACollectionDate_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $this->load->view('admin/header');
            $this->load->view('admin/change_SOACollectionDate');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function change_SOACollectionDate()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = '';
            $tenant_id       = $this->sanitize($this->input->post('tenant_id'));
            $soa_no          = $this->sanitize($this->input->post('soa_no'));
            $collection_date = $this->sanitize($this->input->post('collection_date'));
            if ($this->admin_model->change_SOACollectionDate($tenant_id, $soa_no, $collection_date)) 
            {
                $response['msg'] = 'Success';
            }
            echo json_encode($response);
        }
        else
        {
            redirect('admin');
        }
    }


    public function change_receiptNo_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $this->load->view('admin/header');
            $this->load->view('admin/change_receiptNo');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }

    public function change_receiptNo()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = '';
            $tenant_id       = $this->sanitize($this->input->post('tenant_id'));
            $old_receiptNo   = $this->sanitize($this->input->post('old_receiptNo'));
            $new_receiptNo   = $this->sanitize($this->input->post('new_receiptNo'));
            
            if ($this->admin_model->change_receiptNo($tenant_id, $old_receiptNo, $new_receiptNo)) 
            {
                $response['msg'] = 'Success';
            }
            echo json_encode($response);
        }
        else
        {
            redirect('admin');
        }
    }



    public function change_bankTagging_page(){

        if ($this->session->userdata('admin_logged_in')) 
        {
            
            $banks = $this->admin_model->get_accredited_banks();

            $this->load->view('admin/header');
            $this->load->view('admin/change_bankTagging_page', compact('banks'));
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function update_bankTagging() {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = '';
            $tenant_id = $this->sanitize($this->input->post('tenant_id'));
            $or_number = $this->sanitize($this->input->post('or_number'));
            $bank_name = $this->sanitize($this->input->post('bank_name'));
            $bank_code = $this->sanitize($this->input->post('bank_code'));

            if ($this->admin_model->change_bankTagging($tenant_id, $or_number, $bank_name, $bank_code)) {
                $response['msg'] = 'Success';
            }

            echo json_encode($response);

        }
    }


    public function add_charges_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $this->load->view('admin/header');
            $this->load->view('admin/add_charges');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function search_tradeName()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $result = $this->admin_model->search_tradeName();
            echo json_encode($result);
        }
    }


    public function search_receiptNo() {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $result = $this->admin_model->search_receiptNo();
            echo json_encode($result);
        }
    }


    public function populate_addChargesDetails()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response = array();
            $response['charges'] = '<option value="" disabled="" selected="" style="display:none">Please Select One</option>';
            $tenant_id           = $this->input->post('id');
            $details             = $this->admin_model->get_tenantDetails($tenant_id);
            $charges             = $this->app_model->get_charges_setup();
            
            foreach ($details as $value) 
            {
                $response['tenant_id']  = $tenant_id;
                $response['trade_name']  = $value['trade_name']; 
            }

            foreach ($charges as $desc) 
            {
                $response['charges'] .= "<option value='" . $desc['id'] . "'>" . $desc['description'] .  "</option>";
            }


            echo json_encode($response);
        }
    }


    public function add_charges()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = 'Success';
            $tenant_id       = $this->sanitize($this->input->post('tenant_id'));
            $charge_id       = $this->sanitize($this->input->post('description'));
            $uom             = $this->sanitize($this->input->post('uom'));
            $unit_price      = $this->sanitize(str_replace(",", "", $this->input->post('unit_price')));

            $data = array(
                'tenant_id'             =>  $tenant_id,
                'monthly_chargers_id'   =>  $charge_id,
                'uom'                   =>  $uom,
                'unit_price'            =>  $unit_price,
                'flag'                  =>  'Active'
            );

            $this->app_model->insert('selected_monthly_charges', $data);
            echo json_encode($response);
        }
    }


    public function save_vdsTagging() {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = 'Error';
            $receipt_no       = $this->sanitize($this->input->post('receipt_no'));
            $vds_no       = $this->sanitize($this->input->post('vds_no'));

            $data = array('vds_no' => $vds_no);

            if ($this->app_model->update_where($data, 'receipt_no', $receipt_no, 'payment_scheme')) {
                $response['msg'] = 'Success';
            }
            echo json_encode($response);
        }
    }


    public function update_charges_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/update_charges');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }

    public function populate_updateChargesDetails()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response            = array();
            $response['charges'] = '';
            $tenant_id           = $this->input->post('id');
            $details             = $this->admin_model->get_tenantDetails($tenant_id);
            $charges             = $this->app_model->selected_monthly_charges($tenant_id);
            foreach ($details as $value) 
            {
                $response['tenant_id']  = $tenant_id;
                $response['trade_name']  = $value['trade_name']; 
            }

            foreach ($charges as $value) 
            {
                $response['charges'] .= '<tr>' .
                                            '<td>' . $value['description'] . '</td>' .
                                            '<td>' . $value['charges_code'] . '</td>' .
                                            '<td>' . $value['uom'] . '</td>' .
                                            '<td align = "right">' . number_format($value['unit_price'], 2) . '</td>' .
                                            '<td align = "center">' .
                                                '<span  style = "margin-right:.5rem" data-toggle="tooltip" title="Edit" class="btn btn-primary btn-xs" onClick = "selectedCharge(' . $value['selected_id'] . ')"><i class="fa fa-pencil"></i></span>' .
                                                '<span class="btn btn-danger btn-xs" data-toggle="tooltip" title="Delete" onClick = "deleteCharge(' . $value['selected_id'] . ')"><i class="fa fa-trash-o"></i></span>' .
                                            '</td>' .
                                        '</tr>';
            }


            echo json_encode($response);
        }
    }


    public function get_charges()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response           = array();
            $response['result'] = '';
            $id                 = $this->input->post('id');
            $result             = $this->admin_model->get_charges($id);

            foreach ($result as $value) 
            {
                $response['result'] .= '<div class="form-group">' .
                                                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1 text-right" for="description"><b>Description</b></label>' .
                                                '<div class="col-lg-6">' .
                                                    '<input type ="text" name = "charge_id"  style = "display:none" value = "' . $value['id'] . '"/><input type="text" autocomplete="off" name = "description" value = "' . $value['description'] . '" readonly class="form-control" id="description">' .
                                                '</div>' .
                                            '</div>' .
                                            '<div class="form-group">' .
                                                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1 text-right" for="charges_code"><b>Charges Code</b></label>' .
                                                '<div class="col-lg-6">' .
                                                    '<input type="text" autocomplete="off" name = "charges_code" value = "' . $value['charges_code'] . '" readonly class="form-control" id="charges_code">' .
                                                '</div>' .
                                            '</div>' .
                                            '<div class="form-group">' .
                                                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1 text-right" for="charges_code"><b>Unit of Measure</b></label>' .
                                                '<div class="col-lg-6">' .
                                                    '<select class = "form-control" value"' . $value['uom'] . '" name = "uom" required>' .
                                                        '<option>Per Kilowatt Hour</option>' .
                                                        '<option>Per Kilogram</option>' .
                                                        '<option>Per Cubic Meter</option>' .
                                                        '<option>Per Square Meter</option>' .
                                                        '<option>Per Grease Trap</option>' .
                                                        '<option>Per Feet</option>' . 
                                                        '<option>Per Ton</option>' . 
                                                        '<option>Per Hour</option>' . 
                                                        '<option>Per Piece</option>' .
                                                        '<option>Per Contract</option>' . 
                                                        '<option>Per Linear</option>' . 
                                                        '<option>Per Page</option>' .
                                                        '<option>Fixed Amount</option>' .
                                                        '<option>Inputted</option>' .
                                                    '</select>' .
                                                '</div>' .
                                            '</div>' .
                                            '<div class="form-group">' .
                                                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="unit_price"><b>Unit Price</b></label>' .
                                                '<div class="col-lg-6">' .
                                                    '<input type="text" autocomplete="off" name = "unit_price" value = "' . $value['unit_price'] . '"  class="form-control currency" id="unit_price">' .
                                                '</div>' .
                                            '</div>' . 
                                            '<div class="modal-footer">' .
                                                '<div class = "col-md-6 pull-right">' .
                                                    '<button type = "submit"  class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>' .
                                                    '<button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>' .
                                                '</div>' .
                                            '</div>';
            }


            echo json_encode($response);
        }
    }


    public function update_charges()
    {

        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = '';
            $charge_id       = $this->sanitize($this->input->post('charge_id'));
            $uom             = $this->sanitize($this->input->post('uom'));
            $unit_price      = $this->sanitize(str_replace(",", "", $this->input->post('unit_price')));

            $data = array(
                'uom'                   =>  $uom,
                'unit_price'            =>  $unit_price
            );

            if ($this->app_model->update($data, $charge_id, 'selected_monthly_charges')) 
            {
                $response['msg'] = 'Success';
            }
            echo json_encode($response);
        }
        
    }


    public function delete_charges()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $id = $this->uri->segment(3);
            if ($this->app_model->delete('selected_monthly_charges', 'id', $id)) 
            {
                $this->session->set_flashdata('message', 'Deleted');
            }

            redirect('admin/update_charges_page');
        }
    }


    public function change_basicRental_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/change_basicRental');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function populate_updateBasicRental()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response            = array();
            $response['charges'] = '<option value="" disabled="" selected="" style="display:none">Please Select One</option>';
            $tenant_id           = $this->input->post('id');
            $details             = $this->admin_model->get_tenantDetails($tenant_id);
            $charges             = $this->app_model->get_charges_setup();
            
            foreach ($details as $value) 
            {
                $response['tenant_id']    = $tenant_id;
                $response['trade_name']   = $value['trade_name']; 
                $response['contract_no']  = $value['contract_no']; 
                $response['basic_rental'] = $value['basic_rental']; 
            }

            echo json_encode($response);
        }
    }


    public function update_basicRental()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = '';
            $tenant_id = $this->sanitize($this->input->post('tenant_id'));
            $contract_no = $this->sanitize($this->input->post('contract_no'));
            $basic_rental = $this->sanitize(str_replace(",", "", $this->input->post('basic_rental')));

            if ($this->admin_model->update_basicRental($tenant_id, $contract_no, $basic_rental)) 
            {
                $response['msg'] = 'Success';
            }

            echo json_encode($response);
        
        }
    }


    public function active_longtermTenants()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['result']    = $this->app_model->get_Ltenants();
            $this->load->view('admin/header', $data);
            $this->load->view('admin/active_longtermTenants');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function get_tenantTerms()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $action = $this->uri->segment(3);
            $id = $this->uri->segment(4);
            $data['result'] = $this->app_model->tenant_details($id);
            if ($action == 'update') 
            {
                $this->load->view('admin/update_tenantTerms_page', $data);
            }
            else
            {
                $this->load->view('admin/view_tenantTerms_page', $data);
            }
        }
    }


    public function update_tenantTerms()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {

            $flag                 = $this->uri->segment(3);
            $vat                  = $this->sanitize($this->input->post('vatable'));
            $wht                  = $this->sanitize($this->input->post('less_wht'));
            $id                   = $this->sanitize($this->input->post('id'));
            $tin                  = $this->sanitize($this->input->post('tin'));
            $tenant_type          = $this->sanitize($this->input->post('tenant_type'));
            $rental_type          = $this->sanitize($this->input->post('rental_type'));
            $increment_percentage = $this->sanitize($this->input->post('increment_percentage'));
            $increment_frequency  = $this->sanitize($this->input->post('increment_frequency'));
            $opening_date         = $this->sanitize($this->input->post('opening_date'));
            $expiry_date          = $this->sanitize($this->input->post('expiry_date'));
            $rent_percentage      = $this->sanitize(str_replace(",", "", $this->input->post('rent_percentage')));
            $vatable              = $this->input->post('vatable');
            $penalty_exempt       = $this->input->post('penalty_exempt');
            $vat_percentage       = $this->input->post('vat_percentage');
            $wht_percentage       = $this->input->post('wht_percentage');
            $vat_agreement        = $this->input->post('vat_agreement');
            $sales                = $this->input->post('sales');

            $data = array(
                'is_vat'               =>  $vat,
                'wht'                  =>  $wht,
                'tin'                  =>  $tin, 
                'tenant_type'          =>  $tenant_type, 
                'rental_type'          =>  $rental_type, 
                'increment_percentage' =>  $increment_percentage, 
                'increment_frequency'  =>  $increment_frequency, 
                'opening_date'         =>  $opening_date, 
                'expiry_date'          =>  $expiry_date, 
                'rent_percentage'      =>  $rent_percentage,
                'penalty_exempt'       =>  $penalty_exempt,
                'vat_percentage'       =>  $vat_percentage,
                'wht_percentage'       =>  $wht_percentage,
                'vat_agreement'        =>  $vat_percentage,
                'sales'                =>  $sales

            );

        
            if ($this->app_model->update($data, $id, 'tenants')) 
            {
                $this->session->set_flashdata('message', 'Updated');
            }

            if ($flag == 'longTerm') 
            {
                redirect('admin/active_longtermTenants');
            }
            else
            {
                redirect('admin/active_shorttermTenants');
            }

        }
    }


    public function active_shorttermTenants()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['result'] = $this->app_model->get_Stenants();
            $this->load->view('admin/header', $data);
            $this->load->view('admin/active_shorttermTenants');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }

    public function prospect_longterm()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['result'] = $this->app_model->get_lprospect();
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/prospect_longterm');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }

    public function prospect_shortterm()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['result'] = $this->app_model->get_sprospect();
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/prospect_shortterm');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }

    public function pending_longterm()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['result'] = $this->app_model->pending_lcontracts();
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/pending_longterm');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function pending_shortterm()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['result'] = $this->app_model->pending_scontracts();
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/pending_shortterm');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }

    public function get_prospectDetails()
    {
        $id = $this->uri->segment(3);
        $data['result'] = $this->app_model->view_prospect($id);
        $this->load->view('admin/view_prospect', $data);
    }


    public function approve_prospect()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $id = $this->uri->segment(3);
            $tenancy_type = $this->app_model->get_tenancyFromProspect($id);
            $data = array(
                'status'        =>  'Approved',
                'approved_date' =>   $this->_currentDate
            );

            $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
            if ($this->app_model->update($data, $id, 'prospect')) 
            {
                $tenantData = array(
                    'prospect_id'   =>  $id,
                    'store_code'    =>  $this->app_model->get_storeCode($id), 
                    'tenancy_type'  =>  $tenancy_type, 
                    'flag'          =>  'Pending',
                    'tenant_id'     =>  $this->app_model->generateID($id, $tenancy_type),
                    'contract_no'   =>  $this->app_model->generate_contractCode($id),
                    'status'        =>  'Active'
                );

                $this->app_model->insert('tenants', $tenantData);
                $this->session->set_flashdata('message', 'Success');
            }

            redirect($prev_url);

        }
        else
        {
            redirect('admin');
        }
    }


    public function deny_prospect()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $id = $this->uri->segment(3);
            
            $data = array(
                'status'        =>  'Denied',
                'approved_date' =>   $this->_currentDate
            );

            $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
            if ($this->app_model->update($data, $id, 'prospect')) 
            {
                $this->session->set_flashdata('message', 'Success');
            }

            redirect($prev_url);
        }
        else
        {
            redirect('admin');
        }
    }


    public function terminated_longtermContracts()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['result'] = $this->app_model->terminated_ltenant();
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/terminated_longtermContracts');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function terminated_shorttermContracts()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['result'] = $this->app_model->terminated_stenant();
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/terminated_shorttermContracts');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function restore_contract()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $id = $this->uri->segment(3);
            $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
            if ($this->admin_model->restore_contract($id)) 
            {
                $this->session->set_flashdata('message', 'Success');
            }
            else
            {
                $this->session->set_flashdata('message', 'Success');
            }

            redirect($prev_url);
        }
        else
        {
            redirect('admin');
        }
    }

    public function cancel_payment_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/cancel_payment');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }

    public function cancel_payment()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response['msg'] = '';
            $tenant_id = $this->sanitize($this->input->post('tenant_id'));
            $receipt_no = $this->sanitize($this->input->post('receipt_no'));

            if ($this->admin_model->cancel_payment($tenant_id, $receipt_no)) 
            {
                $response['msg'] = 'Success';
            }
            
            echo json_encode($response);

        }
    }



    public function add_preop_page()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/add_preop_page');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }


    public function add_preopcharges()
    {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $response     = array();
            $tenant_id    = $this->sanitize($this->input->post('tenant_id'));
            $description  = $this->sanitize($this->input->post('description'));
            $doc_no       = $this->sanitize($this->input->post('doc_no'));
            $posting_date = $this->sanitize($this->input->post('posting_date'));
            $bank_name    = $this->sanitize($this->input->post('bank_name'));
            $bank_code    = $this->sanitize($this->input->post('bank_code'));
            $preop_amount = str_replace(",", "", $this->sanitize($this->input->post('preop_amount')));

            if ($preop_amount > 0) 
            {
                $this->db->trans_start(); // Transaction function starts here!!!

                $company_code = $this->admin_model->get_tenantCompanyCode($tenant_id);
                $trade_name   = $this->admin_model->get_tradeName($tenant_id);
                $sl_refNo     = $this->app_model->generate_refNo();
                $gl_refNo     = $this->app_model->gl_refNo();
                $gl_code;
                $doc_type;
                if ($description == 'Advance Rent')
                {
                    $gl_code  = '10.20.01.01.02.01';
                    $doc_type = 'Advance Payment';
                }
                elseif ($description == 'Security Deposit')
                {
                    $gl_code  = '10.20.01.01.03.12';
                    $doc_type = 'Payment';
                }
                else
                {
                    $gl_code  = '10.20.01.01.03.10';
                    $doc_type = 'Payment';
                }



                $entry_CIB = array(
                    'posting_date'      =>  $posting_date,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $gl_refNo,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                    'company_code'      =>  $company_code,
                    'department_code'   =>  '01.04',
                    'debit'             =>  $preop_amount,
                    'bank_name'         =>  $bank_name,
                    'bank_code'         =>  $bank_code,
                    'prepared_by'       =>  $this->session->userdata('id')
                );
                $this->app_model->insert('general_ledger', $entry_CIB);
                $this->app_model->insert('subsidiary_ledger', $entry_CIB);


                $preop_entry = array(
                    'posting_date'      =>    $posting_date,
                    'transaction_date'  =>    $posting_date,
                    'document_type'     =>    'Payment',
                    'ref_no'            =>    $gl_refNo,
                    'doc_no'            =>    $doc_no,
                    'tenant_id'         =>    $tenant_id,
                    'gl_accountID'      =>    $this->app_model->gl_accountID($gl_code),
                    'company_code'      =>    $company_code,
                    'department_code'   =>    '01.04',
                    'credit'            =>    -1 * $preop_amount,
                    'bank_name'         =>    $bank_name,
                    'bank_code'         =>    $bank_code,
                    'prepared_by'       =>    $this->session->userdata('id')
                );
                $this->app_model->insert('general_ledger', $preop_entry);
                $this->app_model->insert('subsidiary_ledger', $preop_entry);


                $sl_preOp = array(
                    'posting_date'      =>    $posting_date,
                    'transaction_date'  =>    $posting_date,
                    'document_type'     =>    $doc_type,
                    'ref_no'            =>    $sl_refNo,
                    'doc_no'            =>    $doc_no,
                    'tenant_id'         =>    $tenant_id,
                    'description'       =>    $description . "-" . $trade_name,
                    'charges_type'      =>    $description,
                    'debit'             =>    $preop_amount,
                    'balance'           =>    $preop_amount
                );
                $this->app_model->insert('ledger', $sl_preOp);



                $this->db->trans_complete(); // End of transaction function

                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $response['msg'] = 'Error';
                    $this->db->trans_rollback(); // If failed rollback all queries
                }
                else
                {
                    $response['msg'] = 'Success';
                }
            }
            else
            {
                $response['msg'] = 'No Amount';
            }

            echo json_encode($response);

        }
    }


    public function VDS_tagging() {
        if ($this->session->userdata('admin_logged_in')) 
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('admin/header', $data);
            $this->load->view('admin/VDS_tagging');
            $this->load->view('admin/footer');
        }
        else
        {
            redirect('admin');
        }
    }
}




/* End of file welcome.php */
/* Location: ./application/controllers/Admin.php */
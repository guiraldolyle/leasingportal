<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class leasing_facilityrental extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('excel');
        $this->load->model('facilityrental_model');
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

    public function FacilityRental()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['current_date']   = $this->_currentDate;
            if ($this->session->userdata('user_group') != '0' && $this->session->userdata('user_group') != null)
            {
                $data['stores'] = $this->app_model->get_store();
                $data['store_floors'] = $this->app_model->store_floors();
                $data['stores']       = $this->app_model->my_store();
            }
            else
            {
                $data['stores'] = $this->app_model->get_stores();
            }
            $data['frno'] = $this->facilityrental_model->get_frno();
            $data['leasee_types'] = $this->app_model->getAll('leasee_type');
            $data['category_one'] = $this->app_model->getAll('category_one');
            $store_id =  $this->session->userdata('user_group');
            $data['facilities'] = $this->facilityrental_model->selectWhere_StoreID('FacilityRental_facilities',$store_id);
            $data['hours_reserved'] = $this->facilityrental_model->getHoursReserved($store_id);

            $this->load->view('leasing/header', $data);
            $this->load->view('FacilityRental/FacilityRental');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function FacilityRental_invoice()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['current_date']   = $this->_currentDate;
            if ($this->session->userdata('user_group') != '0' && $this->session->userdata('user_group') != null)
            {
                $data['stores'] = $this->app_model->get_store();
                $data['store_floors'] = $this->app_model->store_floors();
                $data['stores']       = $this->app_model->my_store();
            }
            else
            {
                $data['stores'] = $this->app_model->get_stores();
            }
            $data['frinvoiceno'] = $this->facilityrental_model->get_frinvoicedocno();
            $data['leasee_types'] = $this->app_model->getAll('leasee_type');
            $data['category_one'] = $this->app_model->getAll('category_one');
            $store_id =  $this->session->userdata('user_group');
            $data['facilities'] = $this->facilityrental_model->selectWhere_StoreID('FacilityRental_facilities',$store_id);
            $data['hours_reserved'] = $this->facilityrental_model->getHoursReserved($store_id);

            $this->load->view('leasing/header', $data);
            $this->load->view('FacilityRental/FacilityRental_invoice');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function FacilityRental_soa()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['soa_no'] = $this->facilityrental_model->get_soaNo();
            $data['billing_period'] = ['1-30 January', '1-28 February', '1-30 March', '1-30 April', '1-30 May', '1-30 June', '1-30 July', '1-30 August', '1-30 September', '1-30 October', '1-30 November', '1-30 December'];
            $this->load->view('leasing/header', $data);
            $this->load->view('FacilityRental/FacilityRental_soa');
            $this->load->view('leasing/footer');
        } 
        else 
        {
            redirect('ctrl_leasing/');
        }
    }

    public function FacilityRental_reprintsoa()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('FacilityRental/FacilityRental_reprintsoa');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    
    public function FacilityRental_payment()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['store'] = $this->app_model->get_store();
            $data['receipt_no'] = $this->facilityrental_model->get_receiptno();
            $this->load->view('leasing/header', $data);
            $this->load->view('FacilityRental/FacilityRental_Payment');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function FacilityRental_Discount()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('FacilityRental/FacilityRental_discount');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function FacilityRental_PaymentHistory()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('FacilityRental/FacilityRental_PaymentHistory');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function get_reserved_datetimecalendar()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $date = $this->input->post('date');
            $store_id = $this->session->userdata('user_group');

            $reserve_dates = $this->facilityrental_model->get_reserved_time($date, $store_id);
            echo json_encode($reserve_dates);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_tmpreserved_datetimecalendar()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $date = $this->input->post('date');
            $store_id = $this->session->userdata('user_group');

            $reserve_dates = $this->facilityrental_model->get_tmpreserved_time($date, $store_id);
            echo json_encode($reserve_dates);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function add_FacilityRentalCustomer()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $customer_name = $this->sanitize($this->input->post('fr_addcustomername'));
            $contactperson = $this->sanitize($this->input->post('fr_addcustomercontactperson'));
            $contactnumber = $this->sanitize($this->input->post('fr_addcustomercontactnum'));
            $customeraddress = $this->sanitize($this->input->post('fr_addcustomeraddress'));

            $data = array (
                'FacilityRental_Cusname'           =>  $customer_name,
                'FacilityRental_ContactPerson'     =>  $contactperson,
                'FacilityRental_ContactNumber'     =>  $contactnumber,
                'FacilityRental_CustomerAddress'   =>  $customeraddress,
                'store_id'                         =>  $this->session->userdata('user_group'),
            );

            $store_id  = $this->session->userdata('user_group');

            $count = $this->facilityrental_model->checkexistinsert($customer_name,'FacilityRental_Cusname','facilityrental_customers',$store_id);
        
            if($count == 'exist')
            {
                $flag = '2';
                echo json_encode($flag);
            }
            else
            {
                $this->db->trans_start();
                $this->facilityrental_model->insert('facilityrental_customers',$data);
                $this->db->trans_complete();
    
                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $flag = '0';
                    echo json_encode($flag);
                }
                else
                {
                    $flag = '1';
                    echo json_encode($flag);
                }
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function populate_facilityrentalcustomer()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $flag   = str_replace("%20", " ", $this->uri->segment(3));
            $result = $this->facilityrental_model->populate_facilityrentalcustomer($flag);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function generate_frcustomerdetails()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            // Format JSON POST
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
            header('Access-Control-Allow-Methods: GET, POST, PUT');
            $jsonstring = file_get_contents ( 'php://input' );
            $arr        = json_decode($jsonstring,true);


            $frCustomerName = str_replace("%20", " ",$this->uri->segment(3));
            $result         = $this->facilityrental_model->generate_frcustomerdetails($frCustomerName);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }
    
    public function get_customertable()
    {
        usleep(80000);
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_id =  $this->session->userdata('user_group');
            $result = $this->facilityrental_model->selectWhere_StoreID('facilityrental_customers',$store_id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_tmpreserve()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $store_id =  $this->session->userdata('user_group');
            $result = $this->facilityrental_model->selectWhereGroupBy_StoreID('facilityrental_tmpreservedatetime',$store_id,'tmp_date');

            $tmp_reservetime_clean = '';
            $result_complete = array();

            foreach($result as $value)
            {
                $get_tmptime = $this->facilityrental_model->get_tmptime($value['tmp_date'],$store_id);
                foreach($get_tmptime as $value_tmptime)
                {
                    $tmp_reservetime_clean.= $value_tmptime['tmp_time'].', ';
                }

                $result_complete[] =
                [
                    'tmp_date'  => $value['tmp_date'],
                    'tmp_time'  => $tmp_reservetime_clean,
                ];
                $tmp_reservetime_clean='';
            }

            echo json_encode($result_complete);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_reservationtable()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $store_id =  $this->session->userdata('user_group');
            $result = $this->facilityrental_model->get_reservationtable($this->session->userdata('user_group'));
            $result_complete = array();
            foreach($result as $value)
            {
                $facility_reserved = $this->facilityrental_model->get_facilitiesreservedbyfrno($value['facilityrental_no']);
                $facility_reserved_clean = '';
                foreach($facility_reserved as $value_facilityreserved)
                {
                    $facility_reserved_clean .= $value_facilityreserved['facility_name']. ', ';
                }
               
                $result_complete[] = [
                    'facilityrental_no'         => $value['facilityrental_no'],
                    'FacilityRental_Cusname'    => $value['FacilityRental_Cusname'],
                    'request_date'              => $value['request_date'],
                    'facility_reserved_clean'   => $facility_reserved_clean,
                ];
            }
            echo json_encode($result_complete);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function reservation_datedetails()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $frno = $this->input->post('frno');

            $reserve_dates = $this->facilityrental_model->get_reservedatebyfrno($frno);
            $reserve_time_clean = '';
            $result_complete = '';

            foreach($reserve_dates as $value_reservedate)
            {
                $reserve_time = $this->facilityrental_model->get_reservetimebyfrno($frno,$value_reservedate['reserve_date']);

                foreach($reserve_time as $value_reservetime)
                {
                    $reserve_time_clean.= $value_reservetime['time'].', ';
                }

                $result_complete.="<tr>
                <td style='text-align:center;'>" . $value_reservedate['reserve_date'] . "</td>
                <td style='text-align:center;'>" . $reserve_time_clean . "</td>
                </tr>";

                $reserve_time_clean = '';
            }
         
            echo json_encode($result_complete);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_frintentletter()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $frno    = $this->uri->segment(3);
            $result = $this->app_model->get_img('facilityrental_intentletter', 'facilityrental_no', $frno);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }
    
    public function delete_frcustomer()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $this->app_model->delete('facilityrental_customers', 'id', $id);
            redirect('leasing_facilityrental/FacilityRental');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_frcustomer_id()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('facilityrental_customers', $id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }
    
    public function update_FacilityRentalCustomer()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $customer_name = $this->sanitize($this->input->post('fr_updatecustomername'));
            $contactperson = $this->sanitize($this->input->post('fr_updatecustomercontactperson'));
            $contactnumber = $this->sanitize($this->input->post('fr_updatecustomercontactnum'));
            $customeraddress = $this->sanitize($this->input->post('fr_updatecustomeraddress'));
            $id = $this->sanitize($this->input->post('frcus_id'));

            $data = array (
                'FacilityRental_Cusname'           =>  $customer_name,
                'FacilityRental_ContactPerson'     =>  $contactperson,
                'FacilityRental_ContactNumber'     =>  $contactnumber,
                'FacilityRental_CustomerAddress'   =>  $customeraddress,
            );

            $store_id  = $this->session->userdata('user_group');

            $count = $this->facilityrental_model->checkexistupdate($customer_name,'facility_name','FacilityRental_Cusname',$id, $store_id);
        
            if($count == 'exist')
            {
                $flag = '2';
                echo json_encode($flag);
            }
            else
            {
                $this->db->trans_start();
                $this->facilityrental_model->update($data, $id, 'facilityrental_customers');
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $flag = '0';
                    echo json_encode($flag);
                }
                else
                {
                    $flag = '1';
                    echo json_encode($flag);
                }
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    //Facility File Maintenance
    public function add_FacilityRentalFacility()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $facilityname = $this->sanitize($this->input->post('fr_facilityname'));
            $fr_facilitydescription = $this->sanitize($this->input->post('fr_facilitydescription'));
            $fr_facilityrate = $this->sanitize($this->input->post('fr_facilityrate'));

            $data = array (
                'store_id'              =>  $this->session->userdata('user_group'),
                'facility_name'         =>  $facilityname,
                'description'           =>  $fr_facilitydescription,
                'FacilityRental_rate'   =>  $fr_facilityrate,
            );

            $store_id  = $this->session->userdata('user_group');

            $count = $this->facilityrental_model->checkexistinsert($facilityname,'facility_name','facilityrental_facilities',$store_id);
        
            if($count == 'exist')
            {
                $flag = '2';
                echo json_encode($flag);
            }
            else
            {
                $this->db->trans_start();
                $this->facilityrental_model->insert('facilityrental_facilities',$data);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $flag = '0';
                    echo json_encode($flag);
                }
                else
                {
                    $flag = '1';
                    echo json_encode($flag);
                }
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

     
    public function get_facilitytable()
    {
        usleep(80000);
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_id =  $this->session->userdata('user_group');
            $result = $this->facilityrental_model->selectWhere_StoreID('facilityrental_facilities',$store_id);
         
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function delete_frFacility()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $this->facilityrental_model->delete('facilityrental_facilities', 'id', $id);
            redirect('leasing_facilityrental/FacilityRental');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_frfacility_id()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('facilityrental_facilities', $id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_FacilityRentalFacility()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $facilityname = $this->sanitize($this->input->post('fr_updatefacilityname'));
            $description = $this->sanitize($this->input->post('fr_updatefacilitydescription'));
            $facilityrate = $this->sanitize($this->input->post('fr_updatefacilityrate'));
            $id = $this->sanitize($this->input->post('frfacility_id'));

            $data = array (
                'facility_name'         =>  $facilityname,
                'description'           =>  $description,
                'FacilityRental_rate'   =>  $facilityrate,
            );

            $store_id  = $this->session->userdata('user_group');

            $count = $this->facilityrental_model->checkexistupdate($facilityname,'facility_name','facilityrental_facilities',$id, $store_id);
        
            if($count == 'exist')
            {
                $flag = '2';
                echo json_encode($flag);
            }
            else
            {
                $this->db->trans_start();
                $this->facilityrental_model->update($data, $id, 'facilityrental_facilities');
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $flag = '0';
                    echo json_encode($flag);
                }
                else
                {
                    $flag = '1';
                    echo json_encode($flag);
                }
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function insert_frdiscount()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $discounttype        = $this->input->post('discount_type');
            $discountoption      = $this->input->post('discount_option');
            $discountamount      = $this->input->post('discount_amount');
            $discountdescription = $this->input->post('discount_description');

            $data = array(
                'discount_type'         => $discounttype, 
                'discount_option'       => $discountoption, 
                'discount_amount'       => str_replace(",", "",$discountamount), 
                'discount_description'  => $discountdescription
            );

            $count = $this->facilityrental_model->checkexistinsertNoStoreID($discounttype,'discount_type','facilityrental_discountsetup');

            if($count == 'exist')
            {
                $flag = '2';
                echo json_encode($flag);
            }
            else
            {
                $this->db->trans_start();
                $this->facilityrental_model->insert('facilityrental_discountsetup',$data);
                $this->db->trans_complete();
    
                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $flag = '0';
                    echo json_encode($flag);
                }
                else
                {
                    $flag = '1';
                    echo json_encode($flag);
                }
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_discount()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->facilityrental_model->getAll('facilityrental_discountsetup');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function delete_frdiscount()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $this->app_model->delete('facilityrental_discountsetup', 'id', $id);
            redirect('leasing_facilityrental/FacilityRental_discount');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    
    public function get_discount_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('facilityrental_discountsetup', $id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_frdiscount()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $discounttype        = $this->input->post('discount_typeupdate');
            $discountoption      = $this->input->post('discount_optionupdate');
            $discountamount      = $this->input->post('discount_amountupdate');
            $discountdescription = $this->input->post('discount_descriptionupdate');
            $id                  = $this->input->post('discount_idupdate');

            $data = array(
                'discount_type'         => $discounttype, 
                'discount_option'       => $discountoption, 
                'discount_amount'       => str_replace(",", "",$discountamount), 
                'discount_description'  => $discountdescription
            );

            $count = $this->facilityrental_model->checkexistupdateNoStoreID($discounttype,'discount_type','facilityrental_discountsetup',$id);

            if($count == 'exist')
            {
                $flag = '2';
                echo json_encode($flag);
            }
            else
            {
                $this->db->trans_start();
                $this->facilityrental_model->update($data,$id,'facilityrental_discountsetup');
                $this->db->trans_complete();
    
                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $flag = '0';
                    echo json_encode($flag);
                }
                else
                {
                    $flag = '1';
                    echo json_encode($flag);
                }
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function disable_reserve_time()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $reserve_date   = $this->sanitize($this->input->post('reserve_date'));
            $store_id       = $this->session->userdata('user_group');       
            
            $reserve_times  = $this->facilityrental_model->get_reserved_time($reserve_date, $store_id);
            echo json_encode($reserve_times);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function disable_tmpreserve_time()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $reserve_date   = $this->sanitize($this->input->post('reserve_date'));
            $store_id       = $this->session->userdata('user_group');       
            
            $reserve_times  = $this->facilityrental_model->get_tmpreserved_time($reserve_date, $store_id);
            echo json_encode($reserve_times);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function add_frtmpreservedatetime()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tmp_reservetime    =   $this->input->post('tmp_reservetime');
            $frreservedate      =   $this->input->post('frreserve_date');
            $store_id           =   $this->session->userdata('user_group');

            //TRANSACTION STARTS HERE
            $this->db->trans_start();
            $count_reserve_time = count($tmp_reservetime);
            for($i = 0; $i<=$count_reserve_time-1; $i++)
            {
                $data_tmpreservedtime = array(
                    'tmp_date'     =>  $frreservedate,
                    'tmp_time'     =>  $tmp_reservetime[$i],
                    'store_id'     =>  $store_id,
                );
                $this->facilityrental_model->insert('facilityrental_tmpreservedatetime',$data_tmpreservedtime);
            }
            $this->db->trans_complete();
        
            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $flag = '0';
                echo json_encode($flag);
            }
            else
            {
                $flag = '1';
                echo json_encode($flag);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function delete_tmpreservation()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tmp_date = $this->uri->segment(3);
            $store_id = $this->session->userdata('user_group');
            $this->facilityrental_model->deleteWhereStoreID('facilityrental_tmpreservedatetime', 'tmp_date', $tmp_date, $store_id);
            redirect('leasing_facilityrental/FacilityRental');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }
    
    public function add_FacilityRentalTransaction()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
        
            $frno               =   $this->input->post('frno');
            $frcustomername     =   $this->input->post('frcustomername');
            $frhoursusage       =   $this->input->post('frhoursusage');    
            $store_id           =   $this->session->userdata('user_group');
            $facility_requested =   $this->input->post('facility_requested');
            // $frrentalprice   =   $this->input->post('frrentalprice');

            $cus_id  = $this->facilityrental_model->getID_WhereStoreID('facilityrental_customers','FacilityRental_Cusname',$frcustomername,$store_id);

            $data = array(
                'facilityrental_no'     =>  $frno,
                'facilityrental_cusID'  =>  $cus_id,
                'store_id'              =>  $this->session->userdata('user_group'),
                'request_date'          =>  date('Y-m-d'),
            );
      
            //TRANSACTION STARTS HERE
            $this->db->trans_start();

            $this->facilityrental_model->insert('facilityrental_reservation',$data);

            //INSERT TIME RESERVED START
            $tmp_reservedata = $this->facilityrental_model->selectWhere_StoreID('facilityrental_tmpreservedatetime',$store_id);
            $count_tmp_reservedata = count($tmp_reservedata);
            for($i=0; $i<=$count_tmp_reservedata-1; $i++)
            {
                $data_reservedetails = array(
                    'facilityrental_no' =>  $frno,
                    'time'              =>  $tmp_reservedata[$i]['tmp_time'],
                    'reserve_date'      =>  $tmp_reservedata[$i]['tmp_date'],
                );

                $this->facilityrental_model->insert('facilityrental_reservedtime',$data_reservedetails);
                $this->facilityrental_model->delete('facilityrental_tmpreservedatetime','id',$tmp_reservedata[$i]['id']);
            }
            //INSERT TIME RESERVED END

            //INSERT FACILITY REQUESTED START
            $count_facility_requested = count($facility_requested);
            for($i=0; $i<=$count_facility_requested-1; $i++)
            {
                $data_facilityrequested = array(
                    'facilityrental_no' =>  $frno,
                    'facility_id'       =>  $facility_requested[$i],
                );

                $this->facilityrental_model->insert('facilityrental_reservedfacility',$data_facilityrequested);
            }
            //INSERT FACILITY REQUESTED END
            
            $this->db->trans_complete();
        
            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $flag = '0';
                echo json_encode($flag);
            }
            else
            {
                $flag = '1';
                echo json_encode($flag);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function add_FacilityRentalTransactionFile()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $frno       = $this->input->post('frno');
            $date       = new DateTime();
            $timeStamp  = $date->getTimestamp();
            
            $this->db->trans_start();
           //INSERT FACILITY RENTAL INTENT LETTER FILE START
           for ($i=0; $i < count($_FILES["frapprovedintentletter"]['name']); $i++)
           {
               $targetPath = getcwd() . '/assets/facilityrental_intentletter/';
               $tmpFilePath = $_FILES['frapprovedintentletter']['tmp_name'][$i];
               //Make sure we have a filepath
               if ($tmpFilePath != "")
               {
                   //Setup our new file path
                   $filename = $timeStamp . $_FILES['frapprovedintentletter']['name'][$i];
                   $newFilePath = $targetPath . $filename;
                   //Upload the file into the temp dir
                   move_uploaded_file($tmpFilePath, $newFilePath);

                   $data = array('facilityrental_no' => $frno, 'file_name' => $filename);
                   $this->app_model->insert('facilityrental_intentletter', $data);
               }
           }
           //INSERT FACILITY RENTAL INTENT LETTER FILE END
           $this->db->trans_complete();
        
           if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
           {
               $this->db->trans_rollback(); // If failed rollback all queries
               $flag = '0';
               echo json_encode($flag);
           }
           else
           {
               $flag = '1';
               echo json_encode($flag);
           }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function cancel_reservation()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = $this->facilityrental_model->getValue_WhereStoreID('store_name','stores','id',$this->session->userdata('user_group'));
            $username   = $this->input->post('username_key'); 
            $password   = $this->input->post('password_key');
            $frno       = $this->input->post('managerkey_frno');
          
            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                 //TRANSACTION STARTS HERE
                $this->db->trans_start();

                $this->facilityrental_model->delete('facilityrental_reservation','facilityrental_no',$frno);
                $this->facilityrental_model->delete('facilityrental_reservedtime','facilityrental_no',$frno);
                $this->facilityrental_model->delete('facilityrental_reservedfacility','facilityrental_no',$frno);

                $this->db->trans_complete();
        
                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $flag = '0';
                    echo json_encode($flag);
                }
                else
                {
                    $flag = '1';
                    echo json_encode($flag);
                }
            }
            else
            {
                echo json_encode('invalidKey');
            }
        
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE
  //INVOICE

    public function get_reservationtableforInvoice()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $store_id =  $this->session->userdata('user_group');
            $result = $this->facilityrental_model->get_reservationtableforInvoice($this->session->userdata('user_group'));
            $result_complete = array();
            foreach($result as $value)
            {
                $facility_reserved = $this->facilityrental_model->get_facilitiesreservedbyfrno($value['facilityrental_no']);
                $facility_reserved_clean = '';
                foreach($facility_reserved as $value_facilityreserved)
                {
                    $facility_reserved_clean .= $value_facilityreserved['facility_name']. ', ';
                }
                
                $result_complete[] = [
                    'facilityrental_no'         => $value['facilityrental_no'],
                    'FacilityRental_Cusname'    => $value['FacilityRental_Cusname'],
                    'request_date'              => $value['request_date'],
                    'facility_reserved_clean'   => $facility_reserved_clean,
                ];
            }
            echo json_encode($result_complete);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_customerdetailsInvoice()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $frno = $this->input->post('id');

            $customerdetails = $this->facilityrental_model->get_customerdetailsInvoice($frno);
            $result = array();

            foreach($customerdetails as $value)
            {
                $result[] = [
                    'FacilityRental_Cusname'            => $value['FacilityRental_Cusname'],
                    'FacilityRental_ContactPerson'      => $value['FacilityRental_ContactPerson'],
                    'FacilityRental_ContactNumber'      => $value['FacilityRental_ContactNumber'],
                    'FacilityRental_CustomerAddress'    => $value['FacilityRental_CustomerAddress'],
                    'TransactionDate'                   => date('Y-m-d'),
                    'frcustomerid'                      => $value['frcustomerid'],
                ];
            }

            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
      
    }

    public function get_facilitiesreservedInvoice()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $frno   = $this->input->post('id');
            $result = $this->facilityrental_model->get_facilitiesreservedInvoice($frno);
            $facility_data = '';
            $running_balance = 0;
           
            foreach($result as $value)
            {
                $amount = $value['FacilityRental_rate'] * $value['totalHours'];
                $running_balance = $running_balance + $amount;
                $facility_data.="<tr>
                <td style='text-align:center;' name='facility_name[]'>" . $value['facility_name'] . "</td>
                <td style='text-align:center;' name='DateOfUse[]'>" . $value['DateOfUse'] . "</td>
                <td style='text-align:center;' name='FacilityRental_rate[]'>" . "P ".number_format($value['FacilityRental_rate'], 2)."</td>
             
                <td style='text-align:center;' name='totalHours[]'>" . $value['totalHours'] . "</td>
                <td style='text-align:center;' name='totalamount[]'>" . "P ".number_format($amount, 2) . "</td>
                </tr>";
            }

            $result_complete = array(
                'FacilityData'      => $facility_data,
                'RunningBalance'    => number_format($running_balance, 2),
            );
         
            echo json_encode($result_complete);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
        
    }

    public function get_discountforinvoice()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = $this->sanitize($this->uri->segment(3));
            $result = $this->facilityrental_model->get_discountforinvoice();
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_discountdetails()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $discount_type = $this->sanitize($this->uri->segment(3));
            $discount_type = str_replace("%20", " ", $discount_type);
            $result      = $this->facilityrental_model->get_discountdetails($discount_type);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function append_InvoiceDiscount()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $discount_id    = $this->input->post('id');
            $frno           = $this->input->post('frno');

            $data = array(
                'facilityrental_no' => $frno,
                'discount_id'       => $discount_id,
            );
            $count = $this->facilityrental_model->checkexistinsertWherefrno($discount_id,'discount_id','facilityrental_tmpinvoicediscount',$frno);
        
            if($count == 'exist')
            {
                $flag = '2';
                echo json_encode($flag);
            }
            else
            {
                $this->db->trans_start();
                $this->facilityrental_model->insert('facilityrental_tmpinvoicediscount',$data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $flag = '0';
                    echo json_encode($flag);
                }
                else
                {
                    $result = $this->facilityrental_model->get_invoiceAppendedDiscount($frno);
                    $discount_table = '';
                    $discount_total = 0;
                    $actual_amount = 0;
                    $running_balance = str_replace(",", "", $this->input->post('runningbalance'));
                    foreach($result as $value)
                    {
                        if($value['discount_option'] == 'Percentage')
                        {
                            $discount_amount = $value['discount_amount'].'% (P '.number_format($running_balance * ($value['discount_amount']/100), 2).')';
                            $discount_total = $discount_total+($running_balance * ($value['discount_amount']/100));
                        }
                        else
                        {
                            $discount_amount = 'P '. number_format($value['discount_amount'], 2);
                            $discount_total = $discount_total+$value['discount_amount'];
                        }

                        $discount_table.="<tr>
                        <td style='text-align:center;'>" . $value['discount_type'] . "</td>
                        <td style='text-align:center;'>" . $value['discount_option'] . "</td>
                        <td style='text-align:center;'>" . $discount_amount."</td>
                        <td style='text-align:center;'>" . $value['discount_description']."</td>
                        <td style='text-align:center;'><a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick='delete_appendeddiscount(".$value['id'].")'> <i class = 'fa fa-trash'></i> Delete</a></td>
                        </tr>";
                    }
                    $actual_amount = $running_balance - $discount_total;
                    $result_complete = array(
                        'DiscountTable' => $discount_table,
                        'DiscountTotal' => number_format($discount_total, 2),
                        'actual_amount' => number_format($actual_amount, 2),
                    );

                    echo json_encode($result_complete);
                }
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_invoiceAppendedDiscount()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            
            $frno   = $this->input->post('id');
            $result = $this->facilityrental_model->get_invoiceAppendedDiscount($frno);
            $running_balance = str_replace(",", "", $this->input->post('runningbalance'));
            $discount_table = '';
            $discount_total = 0;
            $actual_amount = 0;
            foreach($result as $value)
            {
                if($value['discount_option'] == 'Percentage')
                {
                    $discount_amount = $value['discount_amount'].'% (P '.number_format($running_balance * ($value['discount_amount']/100), 2).')';
                    $multiplier = $value['discount_amount'];
                    $discount_total = $discount_total+($running_balance * ($multiplier/100));
                }
                else
                {
                    $discount_amount = 'P '. number_format($value['discount_amount'], 2);
                    $discount_total = $discount_total+$value['discount_amount'];
                }
                $discount_table.="<tr>
                <td style='text-align:center;'>" . $value['discount_type'] . "</td>
                <td style='text-align:center;'>" . $value['discount_option'] . "</td>
                <td style='text-align:center;'>" . $discount_amount."</td>
                <td style='text-align:center;'>" . $value['discount_description']."</td>
                <td style='text-align:center;'><a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick='delete_appendeddiscount(".$value['id'].")'> <i class = 'fa fa-trash'></i> Delete</a></td>
                </tr>";
            }
            $actual_amount = $running_balance - $discount_total;
            $result_complete = array(
                'DiscountTable' => $discount_table,
                'DiscountTotal' => number_format($discount_total, 2),
                'actual_amount' => number_format($actual_amount, 2),
            );
            
            echo json_encode($result_complete);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function delete_appendeddiscount()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $this->app_model->delete('facilityrental_tmpinvoicediscount', 'id', $id);
            redirect('leasing_facilityrental/FacilityRental_invoice');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function savefrinvoice()
    {
        if($this->session->userdata('leasing_logged_in'))
        {    
            $customername = $this->input->post('frcustomername');

            $frno               = $this->input->post('facilityrental_no');
            $result             = $this->facilityrental_model->get_facilitiesreservedInvoice($frno);
            $frinvoiceno        = $this->input->post('facilityrental_invoiceno');
            $frcustomerid       = $this->input->post('frcustomerid');
            $posting_date       = $this->input->post('posting_date');
            $transaction_date   = $this->input->post('transaction_date');
            $due_date           = $this->input->post('due_date');
            $expected_amount    = str_replace(",", "", $this->input->post('ExpectedAmount'));
            $total_discount     = str_replace(",", "", $this->input->post('TotalDiscount'));
            $actual_amount      = str_replace(",", "", $this->input->post('ActualAmount'));
            $store_id           = $this->session->userdata('user_group');
            $user_id            = $this->session->userdata('id');
            
            //INSERT RESERVED FACILITY TO INVOICING TABLE
            $this->db->trans_start();

            foreach($result as $value)
            {
                $amount = $value['FacilityRental_rate'] * $value['totalHours'];
                
                $invoice_data= array(
                    'facilityrental_docno'      => $frinvoiceno,
                    'facilityrental_no'         => $frno,
                    'facility_id'               => $value['facility_id'],
                    'date_used'                 => $value['DateOfUse'],
                    'facility_rate'             => $value['FacilityRental_rate'],
                    'hours_used'                => $value['totalHours'],
                    'amount'                    => $amount,
                    'store_id'                  => $store_id,
                    'user_id'                   => $user_id,
                );

                $this->facilityrental_model->insert('facilityrental_invoicing', $invoice_data);
            }
             //INSERT RESERVED FACILITY TO INVOICING TABLE END

            //UPDATE FACILITY RENTAL RESERVATION STATUS TO POSTED
            $update_data= array(
                'invoice_status'    => 'Posted',
            );
            
            $this->facilityrental_model->updateWhereColumnName('facilityrental_no',$update_data,$frno,'facilityrental_reservation');
             //UPDATE FACILITY RENTAL RESERVATION STATUS TO POSTED END

            //INSERT TEMPORARY ADDED DISCOUNT TO ADDED INVOICING DISCOUNT TABLE
            $tmp_invoice = $this->facilityrental_model->get_invoiceAppendedDiscount($frno);

            foreach($tmp_invoice as $value)
            {
                $invoicediscount_data = array (
                    'facilityrental_docno'  => $frinvoiceno,
                    'facilityrental_no'     => $frno,
                    'discount_type'         => $value['discount_type'],
                    'discount_option'       => $value['discount_option'],
                    'discount_amount'       => $value['discount_amount'],
                    'discount_description'  => $value['discount_description']
                );

                $this->facilityrental_model->insert('facilityrental_addedinvoicediscount', $invoicediscount_data);
            }
            //INSERT TEMPORARY ADDED DISCOUNT TO ADDED INVOICING DISCOUNT TABLE END

            //DELETE TEMPORARY DISCOUNT DATA WHERE FRNO
            $this->facilityrental_model->delete('facilityrental_tmpinvoicediscount', 'facilityrental_no', $frno);
            //DELETE TEMPORARY DISCOUNT DATA WHERE FRNO END

            //INSERT INVOICE SUMMARY
            $invoicesummary_data = array(
                'facilityrental_docno'  => $frinvoiceno,
                'facilityrental_no'     => $frno,
                'customer_id'           => $frcustomerid,
                'posting_date'          => $posting_date,
                'transaction_date'      => date('Y-m-d'),
                'due_date'              => $due_date,
                'expected_amount'       => $expected_amount,
                'total_discount'        => $total_discount,
                'actual_amount'         => $actual_amount,
                'amount_paid'           => 0.00,
                'balance'               => $actual_amount,
                'store_id'              => $store_id,
                'user_id'               => $user_id,
            );

            $this->facilityrental_model->insert('facilityrental_invoicesummary', $invoicesummary_data);
            //INSERT INVOICE SUMMARY END

            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $flag = '0';
                echo json_encode($flag);
            }
            else
            {
                $flag = '1';
                echo json_encode($flag);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }

    }
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    
    public function get_reservationtableforSoa()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $store_id =  $this->session->userdata('user_group');
            $result = $this->facilityrental_model->get_reservationtableforSoa($this->session->userdata('user_group'));
            $result_complete = array();
            foreach($result as $value)
            {
                $facility_reserved = $this->facilityrental_model->get_facilitiesreservedbyfrno($value['facilityrental_no']);
                $facility_reserved_clean = '';
                foreach($facility_reserved as $value_facilityreserved)
                {
                    $facility_reserved_clean .= $value_facilityreserved['facility_name']. ', ';
                }
                
                $result_complete[] = [
                    'facilityrental_no'         => $value['facilityrental_no'],
                    'FacilityRental_Cusname'    => $value['FacilityRental_Cusname'],
                    'request_date'              => $value['request_date'],
                    'facility_reserved_clean'   => $facility_reserved_clean,
                    'facilityrental_docno'      => $value['facilityrental_docno'],
                ];
            }
            echo json_encode($result_complete);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }
    public function get_customerdetailsSoa()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $frno = $this->input->post('frno');

            $customerdetails = $this->facilityrental_model->get_customerdetailsSoa($frno);
            $result = array();

            foreach($customerdetails as $value)
            {
                $result[] = [
                    'FacilityRental_docno'              => $value['facilityrental_docno'],
                    'FacilityRental_Cusname'            => $value['FacilityRental_Cusname'],
                    'FacilityRental_ContactPerson'      => $value['FacilityRental_ContactPerson'],
                    'FacilityRental_ContactNumber'      => $value['FacilityRental_ContactNumber'],
                    'FacilityRental_CustomerAddress'    => $value['FacilityRental_CustomerAddress'],
                    'TransactionDate'                   => date('Y-m-d'),
                    'frcustomerid'                      => $value['frcustomerid'],
                    'expected_amount'                   => number_format($value['expected_amount'], 2),
                    'total_discount'                    => number_format($value['total_discount'], 2),
                    'actual_amount'                     => number_format($value['actual_amount'], 2),
                    'customer_id'                       => $value['frcustomerid'],
                ];
            }

            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
      
    }

    public function get_soaInvoicing()
    {
        $frno = $this->input->post('frno');
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->facilityrental_model->get_soaInvoicing($frno);
            $soaInvoicing='';
            foreach($result as $value)
            {
                $soaInvoicing.="<tr>
                <td style='text-align:center;' name='facility_name[]'>" . $value['facilityrental_docno'] . "</td>
                <td style='text-align:center;' name='DateOfUse[]'>" . $value['facility_name'] . "</td>
                <td style='text-align:center;' name='FacilityRental_rate[]'>" . $value['date_used']."</td>
                <td style='text-align:center;' name='FacilityRental_rate[]'>" . "P ".number_format($value['facility_rate'], 2)."</td>
                <td style='text-align:center;' name='totalHours[]'>" . $value['hours_used'] . "</td>
                <td style='text-align:center;' name='totalamount[]'>" . "P ".number_format($value['amount'], 2) . "</td>
                </tr>";
            }
            echo json_encode($soaInvoicing);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_soaDiscount()
    {
        $frno = $this->input->post('frno');
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->facilityrental_model->get_soaDiscount($frno);
            $soaDiscount='';
            $expected_amount = $this->facilityrental_model->getValue_WhereStoreID('expected_amount','facilityrental_invoicesummary','facilityrental_no', $frno);

            foreach($result as $value)
            {
                if($value['discount_option'] == 'Percentage')
                {
                    $discount_amount = $value['discount_amount'];
                    $discount_amount = $expected_amount * ($discount_amount/100);
                    $discount_amount = $value['discount_amount'].'% (P '.number_format($discount_amount, 2).')'; 
                    
                }
                else
                {
                    $discount_amount = 'P '. number_format($value['discount_amount'], 2);
                }

                $soaDiscount.="<tr>
                <td style='text-align:center;' name='facility_name[]'>" . $value['facilityrental_docno'] . "</td>
                <td style='text-align:center;' name='facility_name[]'>" . $value['discount_type'] . "</td>
                <td style='text-align:center;' name='DateOfUse[]'>" . $value['discount_option'] . "</td>
                <td style='text-align:center;' name='FacilityRental_rate[]'>".$discount_amount."</td>
                <td style='text-align:center;' name='FacilityRental_rate[]'>".$value['discount_description']."</td>
                </tr>";
            }

            echo json_encode($soaDiscount);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function savefrsoa()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $frno = $this->input->post('facilityrental_no');
            $store_id = $this->session->userdata('user_group');
            $user_id = $this->session->userdata('id');
            $collection_date = $this->input->post('collection_date');
            $facilityrental_soano = $this->input->post('facilityrental_soano');
            $actual_amount = $this->input->post('ActualAmount');
            $billing_period  = $this->sanitize($this->input->post('billing_period'));
            $expected_amount = $this->input->post('ExpectedAmount');
            $total_discount = $this->input->post('TotalDiscount');
            $frcustomerid = $this->input->post('frcustomerid');
            $facilityrental_docno = $this->input->post('facilityrental_docno');
            $response = array();
            $user_id = $this->session->userdata('id');
            $date = new DateTime();
            $timeStamp = $date->getTimestamp(); 

            $invoicing_data = $this->facilityrental_model->get_invoicingdataSoa($frno);
            $this->db->trans_start();
            foreach($invoicing_data as $value)
            {
                $soa_data = array(
                    'facilityrental_invoicingID'    => $value['id'],
                    'facilityrental_soaNo'          => $facilityrental_soano,
                    'collection_date'               => $collection_date,
                    'store_id'                      => $store_id, 
                    'user'                          => $user_id,     
                );

                $this->facilityrental_model->insert('facilityrental_soa', $soa_data);
            }
            $update_soa_to_posted = array(
                'is_soa_posted' => 1,
            );
                $this->facilityrental_model->updateWhereColumnName('facilityrental_no',$update_soa_to_posted,$frno,'facilityrental_invoicesummary');

              //INSERT ADDED INVOICE DISCOUNT TO SOA DISCOUNT TABLE START
              $added_discounts = $this->facilityrental_model->get_soaDiscount($frno);
              foreach($added_discounts as $value)
              {
                  $soa_discounts = array(
                    'facilityrental_soaNo' => $facilityrental_soano,
                    'facilityrental_addedinvoicediscountID' => $value['id'],
                  );
                  $this->facilityrental_model->insert('facilityrental_soadiscount', $soa_discounts);
              }
              //INSERT ADDED INVOICE DISCOUNT TO SOA DISCOUNT TABLE END

            // PDF START
            // PDF START
            // PDF START
            $store_details = $this->facilityrental_model->get_storeDetails($store_id);
            $customer_detailsSoa = $this->facilityrental_model->get_customerdetailsSoa($frno);

            $pdf = new FPDF('p','mm', 'A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            $logoPath = getcwd() . '/assets/other_img/';

            foreach($store_details as $detail)
            {
                $pdf->cell(15, 15, $pdf->Image($logoPath . $detail['logo'], $pdf->GetX(), $pdf->GetY(), 15), 0, 0, 'L', false);

                $pdf->setFont ('times','B',12);
                $pdf->cell(50, 10, strtoupper($detail['store_name']), 0, 0, 'L');
                $store_name = $detail['store_name'];
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
                $pdf->cell(20, 10, $detail['store_address'], 0, 0, 'L');

                $pdf->cell(65, 6, " ", 0, 0, 'L');
                $pdf->setFont ('times','',9);
                $pdf->cell(30, 5, $billing_period, 1, 0, 'C');
                $pdf->cell(30, 5, date('F j, Y',strtotime($collection_date)), 1, 0, 'C');
                $pdf->cell(30, 5, "P ".$actual_amount, 1, 0, 'C');
             

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
                $pdf->cell(75, 10, "CUSTOMER'S INFORMATION", 1, 0, 'C', TRUE);
                $pdf->cell(25, 6, " ", 0, 0, 'L');
                $pdf->setFont ('times','',10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Multicell(90, 4, $detail['contact_person'] . "\n" . "Phone: " . $detail['contact_no'] . "\n" . "E-mail: " . $detail['email'], 1, 'C');

                $pdf->ln();

                $pdf->SetTextColor(0, 0, 0);
            }

            foreach( $customer_detailsSoa as $data)
            {
                $pdf->setFont ('times','B',8);
                $pdf->cell(25, 4, "Facility Rental No. ", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $data['facilityrental_no'], 0, 0, 'L');
                $pdf->cell(10, 4, "  ", 0, 0, 'L');
                $pdf->cell(25, 4, "Contact Number", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $data['FacilityRental_ContactNumber'], 0, 0, 'L');

                $pdf->ln();

                $pdf->cell(25, 4, "Customer Name ", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $data['FacilityRental_Cusname'], 0, 0, 'L');
                $pdf->cell(10, 4, "  ", 0, 0, 'L');
                $pdf->cell(25, 4, "Soa No", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $facilityrental_soano, 0, 0, 'L');

                $pdf->ln();

                $pdf->cell(25, 4, "Address.", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $data['FacilityRental_CustomerAddress'], 0, 0, 'L');
                $pdf->cell(10, 4, "  ", 0, 0, 'L');
                $pdf->cell(25, 4, "Date", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . date('F j, Y',strtotime($this->_currentDate)), 0, 0, 'L');

                $pdf->ln();

                $pdf->cell(25, 4, "Contact Person.", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $data['FacilityRental_ContactPerson'], 0, 0, 'L');
            }

            $pdf->ln();
            $pdf->cell(0, 5, "_____________________________________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->setFont ('times','B',11);
            $pdf->cell(25, 4, "Facilities Rented: ", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','B',9);
            $pdf->cell(25, 8, 'Document no.',0 ,0, 'L');
            $pdf->cell(25, 8, 'Date of Use',0 ,0, 'L');
            $pdf->cell(30, 8, 'Facility Name',0 ,0, 'L');
            $pdf->cell(30, 8, 'Hours Used',0 ,0, 'L');
            $pdf->cell(60, 8, 'Amount',0 ,0, 'R');
            $pdf->ln();

            $invoicing_data_forpdf = $this->facilityrental_model->get_soaInvoicing($frno);
            foreach($invoicing_data_forpdf as $data)
            {
             
                $pdf->setFont ('times','',9);
                $pdf->cell(25, 4, $data['facilityrental_docno'], 0, 0, 'L');
                $pdf->cell(25, 4, $data['date_used'], 0, 0, 'L');
                $pdf->cell(30, 4, $data['facility_name'], 0, 0, 'L');
                $pdf->cell(30, 4, "No. of Hours(".$data['hours_used'].")  X  P ". number_format($data['facility_rate'], 2), 0, 0, 'L');
                $pdf->cell(60, 4, ":  P " . number_format($data['amount'], 2), 0, 0, 'R');
                $pdf->ln();
                $pdf->ln();
            }

     
            $pdf->setFont ('times','B',9);
            $pdf->cell(25, 4, "Sub Total ", 0, 0, 'L');
            $pdf->cell(125, 4, "  ", 0, 0, 'L');
            $pdf->cell(25, 4,  ":  P ". $expected_amount, 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->setFont ('times','B',11);
            $pdf->cell(25, 4, "Discount: ", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->setFont ('times','B',9);
            $pdf->cell(50, 8, 'Document no.',0 ,0, 'L');
            $pdf->cell(40, 8, 'Discount Type',0 ,0, 'L');
            $pdf->cell(30, 8, 'Fixed/Percentage',0 ,0, 'L');
            $pdf->cell(50, 8, 'Amount',0 ,0, 'R');
            $pdf->ln();
            $soa_discountforpdf = $this->facilityrental_model->get_soaDiscount($frno);

            foreach($soa_discountforpdf as $data)
            {
                $pdf->setFont ('times','',9);
                $pdf->cell(50, 4, $data['facilityrental_docno'], 0, 0, 'L');
                $pdf->cell(40, 4, $data['discount_type'], 0, 0, 'L');
                if($data['discount_option'] == "Percentage")
                {
                    $pdf->cell(30, 4, "Percentage (".$data['discount_amount']."%)", 0, 0, 'L');
                    $multiplier = $data['discount_amount'];
                    $true_amount = str_replace(",", "",$expected_amount) * ($multiplier/100);
                    $pdf->cell(50, 4, ":  P " . number_format($true_amount, 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->ln();
                }
                else
                {
                    $pdf->cell(30, 4, "Fixed Amount ", 0, 0, 'L');
                    $pdf->cell(50, 4, ":  P " . number_format($data['discount_amount'], 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->ln();
                }
            }

         
            $pdf->setFont ('times','B',9);
            $pdf->cell(25, 4, "Sub Total ", 0, 0, 'L');
            $pdf->cell(145, 4,  ":  P ". $total_discount, 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(147, 5, " ", 0, 0, 'L');
            $pdf->cell(0, 5, "______________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->setFont ('times','B',11);
            $pdf->cell(25, 4, "Net Amount Due", 0, 0, 'L');
            $pdf->cell(145, 4,  ":  P ". $actual_amount, 0, 0, 'R');
            $pdf->ln();

            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','',10);
            $pdf->cell(20, 4, "Prepared by: ",0 ,0, 'L');
            $pdf->cell(105, 4, $this->session->userdata('last_name').", ".$this->session->userdata('first_name')." ".$this->session->userdata('middle_name'), 0, 0, 'L');
            $pdf->cell(20, 4, "Confirmed by: ",0 ,0, 'L');
            $pdf->cell(20, 4, "______________________",0, 0, 'L');
            $pdf->ln();

            $pdf->setFont('times','B',8);
            $pdf->cell(20, 4, "",0 ,0, 'L');
            $pdf->cell(105, 4, "Leasing Accounting Officer", 0, 0, 'L');
            $pdf->cell(20, 4, "",0 ,0, 'L');
            $pdf->cell(20, 4, "Leasing Manager",0, 0, 'L');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(190, 4,"         ", 1, 0, 'L', TRUE);
            $pdf->ln();
            // PDF END
            // PDF END
            // PDF END
            // PDF END

            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $flag = '0';
                echo json_encode($flag);
            }
            else
            {
                $file_name = "SOA-".$frno.$timeStamp.'.pdf';

                  //INSERT SOA FILE NAME START
                  $soa_files = array(
                    'soa_no' => $facilityrental_soano,
                    'facilityrental_no' => $frno,
                    'facilityrental_docno' => $facilityrental_docno,
                    'customer_id' => $frcustomerid,
                    'store_id' => $this->session->userdata('user_group'),
                    'user_id' => $user_id,
                    'expected_amount' => str_replace(",", "", $expected_amount),
                    'total_discount' => str_replace(",", "", $total_discount),
                    'amount_payable' => str_replace(",", "", $actual_amount),
                    'file_name' => $file_name,
                    'billing_period' => $billing_period,
                );
                $this->facilityrental_model->insert('facilityrental_soafile', $soa_files);
                //INSERT SOA FILE NAME END

                $response['file_name'] = base_url() . 'assets/facilityrental_pdf/' . $file_name;
                $pdf->Output('assets/facilityrental_pdf/' . $file_name , 'F');
                echo json_encode($response);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

     //REPRINT SOA
    //REPRINT SOA
    //REPRINT SOA
    //REPRINT SOA
    //REPRINT SOA
    //REPRINT SOA
    //REPRINT SOA
    public function get_frSoa()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            // Format JSON POST
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
            header('Access-Control-Allow-Methods: GET, POST, PUT');
            $jsonstring = file_get_contents ( 'php://input' );
            $arr        = json_decode($jsonstring,true);
    
    
            $frcustomername = $arr["param"];
            $result     = $this->facilityrental_model->get_frSoa($frcustomername);
        
            echo json_encode($result);
        }
    }

    public function cancel_soa()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $soano = $this->sanitize($this->uri->segment(3));
            $frno = $this->sanitize($this->uri->segment(4));
 
            $this->facilityrental_model->delete('facilityrental_soafile','soa_no', $soano);
            $this->facilityrental_model->delete('facilityrental_soadiscount','facilityrental_soaNo', $soano);
            $this->facilityrental_model->delete('facilityrental_soa','facilityrental_soaNo', $soano);

            //update is_soa_posted tag to 0 in invoice summary table.
            $update_invoice_summary = array(
                'is_soa_posted' => 0,
            );
            $this->facilityrental_model->updateWhereColumnName('facilityrental_no',$update_invoice_summary,$frno,'facilityrental_invoicesummary');
            
            redirect('leasing_facilityrental/Facilityrental_reprintsoa');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }
    //CCM
    //CCM
    //CCM
    //CCM
    //CCM
    //CCM
    
    public function populate_ccm_customer() 
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->ccm_model->populate_ccm_customer();
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function ccm_banks() {
        if($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->ccm_model->ccm_banks();
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }
    //FACILITY RENTAL PAYMENT
    //FACILITY RENTAL PAYMENT
    //FACILITY RENTAL PAYMENT
    //FACILITY RENTAL PAYMENT
    //FACILITY RENTAL PAYMENT
    //FACILITY RENTAL PAYMENT
    //FACILITY RENTAL PAYMENT
    public function get_storeBankCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
           
            $result    = $this->facilityrental_model->get_storeBankCode();
            echo json_encode($result);
        }
    }

    public function get_bankName()
    {
        $bank_code = str_replace("%20", " ", $this->uri->segment(3));
        $result = $this->facilityrental_model->get_bankName($bank_code);
        echo json_encode($result);

    }

    public function populate_soano()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $flag   = str_replace("%20", " ", $this->uri->segment(3));
            $result = $this->facilityrental_model->populate_soano($flag);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_soadetailspayment()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $soano = str_replace("%20", " ",$this->uri->segment(3));
            $result         = $this->facilityrental_model->get_soadetailspayment($soano);
            $result_clean = array();
            foreach($result as $value)
            {
                $result_clean[] = [
                    'frcustomername' => $value['FacilityRental_Cusname'],
                    'billing_period' => $value['billing_period'],
                    'expected_amount' => $value['expected_amount'],
                    'total_discount' => $value['total_discount'],
                    'actual_amount' => $value['amount_payable'],
                    'facilityrental_docno' => $value['facilityrental_docno'],
                    'customer_id' => $value['customer_id'],
                    'amount_paid' => $value['amount_paid'],
                    'balance' => $value['balance'],
                ];
            }
            echo json_encode($result_clean);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_paymentsoatable()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $soano = $this->input->post('soano');

            $result = $this->facilityrental_model->get_paymentsoatable($soano);
            $result_table = '';

            foreach($result as $value)
            {
                $result_table.="<tr>
                <td style='text-align:center;'>".$value['facilityrental_docno']."</td>
                <td style='text-align:center;'>".$value['facility_name']."</td>
                <td style='text-align:center;'>".$value['date_used']."</td>
                <td style='text-align:center;'>".number_format($value['facility_rate'], 2)."</td>
                <td style='text-align:center;'>".$value['hours_used']."</td>
                <td style='text-align:center;'>".number_format($value['amount'], 2)."</td>
                </tr>";
            }

            echo json_encode($result_table);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_paymentdiscounttable()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $soano = $this->input->post('soano');
            
            $result = $this->facilityrental_model->get_paymentdiscounttable($soano);
            $result_table = "";
            $expected_amount = $this->facilityrental_model->get_paymentexpectedamount($soano);
            
            foreach($result as $value)
            {
                if($value['discount_option'] == 'Percentage')
                {
                    $discount_amount = $value['discount_amount'].' %';
                    $actual_discount =  $expected_amount * ($value['discount_amount']/100);

                    $discount_amount = $discount_amount . ' (P '.number_format($actual_discount, 2).')';
                }
                else
                {
                    $discount_amount = 'P '. number_format($value['discount_amount'], 2);
                }

                $result_table.="<tr>
                    <td style='text-align:center;'>".$value['facilityrental_docno']."</td>
                    <td style='text-align:center;'>".$value['discount_type']."</td>
                    <td style='text-align:center;'>".$value['discount_option']."</td>
                    <td style='text-align:center;'>".$discount_amount."</td>
                    <td style='text-align:center;'>".$value['discount_description']."</td>
                </tr>";
            }

            echo json_encode($result_table);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function save_facilityrentalpayment()
    {
        if($this->session->userdata('leasing_logged_in'))
        {
            $amountPaid = str_replace(",", "", $this->input->post('amount_paid'));
            $soaNo = $this->input->post('soa_no');
            $docno = $this->input->post('facilityrental_docno');
            $receipt_no = $this->input->post('receipt_no');
            $customer_id = $this->input->post('customer_id');
            $billing_period = $this->input->post('billing_period');
            $payment_date = $this->input->post('payment_date');
            $tender_typeDesc = $this->input->post('tender_typeDesc');
            $bank = $this->input->post('bank');
            $amount_due = str_replace(",", "", $this->input->post('balance'));
            $frcustomername = $this->input->post('frcustomername');
            $store_id = $this->session->userdata('user_group');
            $payee = $this->input->post('payee');
            $check_no = $this->input->post('check_no');
            $check_date = $this->input->post('check_date');
            $expected_amount = str_replace(",", "", $this->input->post('expected_amount'));
            $total_discount = str_replace(",", "", $this->input->post('total_discount'));
            $user_id = $this->session->userdata('id');
            $date = new DateTime();
            $timeStamp = $date->getTimestamp();
            $response = array();

            if($amountPaid>= $amount_due)
            {
                $remaining_balance = $this->facilityrental_model->get_invoiceSummaryBalance($soaNo);
                
                $new_balance = $remaining_balance - $amountPaid;
                
                $update_invoicesummarydata = array(
                    'amount_paid' => $amountPaid,
                    'balance' => $new_balance,
                );
                
                $this->db->trans_start();

                //update invoice summary balance
                $this->facilityrental_model->updateWhereColumnName('facilityrental_docno',$update_invoicesummarydata, $docno, 'facilityrental_invoicesummary');
                //

                $store_details = $this->facilityrental_model->get_storeDetails($store_id);
                //PDF
                $pdf = new FPDF('p','mm', 'A4');
                $pdf->AddPage();
                $pdf->setDisplayMode ('fullpage');
                $logoPath = getcwd() . '/assets/other_img/';

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
                 
                }
                $pdf->ln();
                $pdf->cell(40, 1, '____________________________________________________________________________',0 ,0, 'L');
                $pdf->ln();
                $pdf->setFont('times', 'B', 14);
                $pdf->cell(190, 20, 'Payment Receipt', 0 , 0 , 'C');
                $pdf->ln();
                $pdf->setFont('times', 'B', 12);
                $pdf->cell(25,12, 'Facilities Rented', 0 ,0, 'L');
                $pdf->ln();
                $pdf->setFont('times', 'B', 9);
                $pdf->cell(35, 8 , 'Doc No.', 0, 0, 'C');
                $pdf->cell(45, 8 , 'Facility Name.', 0, 0, 'C');
                $pdf->cell(35, 8 , 'Date Used', 0, 0, 'C');
                $pdf->cell(25, 8 , 'Facility Rate', 0, 0, 'C');
                $pdf->cell(25, 8 , 'Total Hours', 0, 0, 'C');
                $pdf->cell(35, 8 , 'Amount', 0, 0, 'C');
                    
                $receipt_data = $this->facilityrental_model->get_paymentsoatable($soaNo);

                foreach($receipt_data as $value)
                {
                    $pdf->ln();
                    $pdf->setFont('times', '', 9);
                    $pdf->cell(35, 10, $value['facilityrental_docno'],0 ,0, 'C');
                    $pdf->cell(45, 10, $value['facility_name'],0 ,0, 'C');
                    $pdf->cell(35, 10, $value['date_used'],0 ,0, 'C');
                    $pdf->cell(25, 10, 'P '.number_format($value['facility_rate'], 2),0 ,0, 'C');
                    $pdf->cell(25, 10, $value['hours_used'],0 ,0, 'C');
                    $pdf->cell(35, 10, 'P '.number_format($value['amount'], 2),0 ,0, 'C');
                }

                $pdf->ln();
                $pdf->setFont('times', 'B', 12);
                $pdf->cell(25,12, 'Discount Added', 0 ,0, 'L');
                $pdf->ln();
                $pdf->setFont('times', 'B', 9);
                $pdf->cell(49, 10 , 'Doc No.', 0, 0, 'C');
                $pdf->cell(49, 10 , 'Discount Type.', 0, 0, 'C');
                $pdf->cell(49, 10 , 'Percentage/Fixed', 0, 0, 'C');
                $pdf->cell(49, 10 , 'Discount Amount', 0, 0, 'C');
                

                $receipt_discount = $this->facilityrental_model->get_paymentdiscounttable($soaNo);
                $expected_amount = $this->facilityrental_model->get_paymentexpectedamount($soaNo);
                           
                foreach($receipt_discount as $value)
                {
                    if($value['discount_option'] == 'Percentage')
                    {
                        $discount_amount = $value['discount_amount'].' %';
                        $actual_discount =  $expected_amount * ($value['discount_amount']/100);
               
                        $discount_amount = $discount_amount . ' (P '.number_format($actual_discount, 2).')';
                    }
                    else
                    {
                        $discount_amount = 'P '. number_format($value['discount_amount'], 2);
                    }
                
                    $pdf->ln();
                    $pdf->setFont('times', '', 9);
                    $pdf->cell(49, 10 , $value['facilityrental_docno'], 0, 0, 'C');
                    $pdf->cell(49, 10 , $value['discount_type'], 0, 0, 'C');
                    $pdf->cell(49, 10 , $value['discount_option'], 0, 0, 'C');
                    $pdf->cell(49, 10 , $discount_amount, 0, 0, 'C');
                }
                
                $pdf->ln();
                $pdf->ln();
                $pdf->setFont ('times','',14);
                $pdf->cell(40, 1, '____________________________________________________________________________',0 ,0, 'L');
                $pdf->ln();
                $pdf->ln();
                $pdf->setFont('times', 'B', 9);
                $pdf->cell(20, 10, 'Payment Scheme:', 0, 0, 'L');
                $pdf->ln();

                if($tender_typeDesc == 'Check')
                {
                    $pdf->setFont('times', '', 9);
                    $pdf->cell(20, 6, 'Description: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $tender_typeDesc, 0, 0, 'L');
                    $pdf->cell(60, 6, '   ', 0, 0, 'L');
                    $pdf->cell(35, 6, 'Expected Amount: ', 0, 0, 'L');
                    $pdf->cell(25, 6, 'P '. number_format($expected_amount, 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(20, 6, 'Bank: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $bank, 0, 0, 'L');
                    $pdf->cell(60, 6, '   ', 0, 0, 'L');
                    $pdf->cell(35, 6, 'Total Discount: ', 0, 0, 'L');
                    $pdf->cell(25, 6, 'P '. number_format($total_discount, 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(20, 6, 'Check No: ', 0 , 0, 'L');
                    $pdf->cell(20, 6, $check_no, 0 , 0, 'L');
                    $pdf->cell(60, 6, '   ', 0, 0, 'L');
                    $pdf->cell(35, 6, 'Actual Amount: ', 0, 0, 'L');
                    $pdf->cell(25, 6, 'P '. number_format($amount_due, 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(20, 6, 'Check date: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $check_date, 0, 0, 'L');
                    $pdf->cell(60, 6, '   ', 0, 0, 'L');
                    $pdf->cell(35, 6, 'Amount Paid: ', 0, 0, 'L');
                    $pdf->cell(25, 6, 'P '. number_format($amountPaid, 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(20, 6, 'Payor: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $frcustomername);
                    $pdf->cell(60, 6, '   ', 0, 0, 'L');
                    $pdf->cell(35, 6, 'Balance: ', 0, 0, 'L');
                    $pdf->cell(25, 6, 'P '. number_format($new_balance, 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(20, 6, 'Payee: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $payee, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 6, 'OR #: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $receipt_no, 0, 0, 'L');
                }
                else if($tender_typeDesc == 'Cash')
                {
                    $pdf->setFont('times', '', 9);
                    $pdf->cell(20, 6, 'Description: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $tender_typeDesc, 0, 0, 'L');
                    $pdf->cell(60, 6, '   ', 0, 0, 'L');
                    $pdf->cell(35, 6, 'Expected Amount: ', 0, 0, 'L');
                    $pdf->cell(25, 6, 'P '. number_format($expected_amount, 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(20, 6, 'Bank: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $bank, 0, 0, 'L');
                    $pdf->cell(60, 6, '   ', 0, 0, 'L');
                    $pdf->cell(35, 6, 'Total Discount: ', 0, 0, 'L');
                    $pdf->cell(25, 6, 'P '. number_format($total_discount, 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(20, 6, 'Payor: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $frcustomername);
                    $pdf->cell(60, 6, '   ', 0, 0, 'L');
                    $pdf->cell(35, 6, 'Actual Amount: ', 0, 0, 'L');
                    $pdf->cell(25, 6, 'P '. number_format($amount_due, 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(20, 6, 'Payee: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $payee, 0, 0, 'L');
                    $pdf->cell(60, 6, '   ', 0, 0, 'L');
                    $pdf->cell(35, 6, 'Amount Paid: ', 0, 0, 'L');
                    $pdf->cell(25, 6, 'P '. number_format($amountPaid, 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(20, 6, 'OR #: ', 0, 0, 'L');
                    $pdf->cell(20, 6, $receipt_no, 0, 0, 'L');
                    $pdf->cell(60, 6, '   ', 0, 0, 'L');
                    $pdf->cell(35, 6, 'Balance: ', 0, 0, 'L');
                    $pdf->cell(25, 6, 'P '. number_format($new_balance, 2), 0, 0, 'R');
                }

                $pdf->ln();
                $pdf->ln();
                $pdf->ln();
                $pdf->ln();
                $pdf->setFont('times','',10);
                $pdf->cell(0, 4, "Prepared By: ".$this->session->userdata('last_name').", ".$this->session->userdata('first_name')." ".$this->session->userdata('middle_name')."                                                     Confirm By:______________________", 0, 0, 'L');
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');
                
                $file_name = 'OR'.$soaNo.$timeStamp.'.pdf';
                $response['file_name'] = base_url() . 'assets/facilityrental_pdf/' . $file_name;
                $pdf->Output('assets/facilityrental_pdf/' . $file_name , 'F');

                //PDF END

                //insert payment data start
                if($tender_typeDesc == 'Cash')
                {
                    $insert_data = array(
                        'facilityrental_receiptno' => $receipt_no,
                        'soa_no' => $soaNo,
                        'customer_id' => $customer_id,
                        'billing_period' => $billing_period,
                        'payment_date' => $payment_date,
                        'tender_type' => $tender_typeDesc,
                        'bank' => $bank,
                        'amount_due' => $amount_due,
                        'amount_paid'=> $amountPaid,
                        'receipt_file' => $file_name,
                        'payee' => $payee,
                        'payor' => $frcustomername,
                        'store_id' => $store_id,
                        'user_id' => $user_id,
                    );
                }
                else if($tender_typeDesc == 'Check')
                {
                    $insert_data = array(
                        'facilityrental_receiptno' => $receipt_no,
                        'soa_no' => $soaNo,
                        'customer_id' => $customer_id,
                        'billing_period' => $billing_period,
                        'payment_date' => $payment_date,
                        'tender_type' => $tender_typeDesc,
                        'bank' => $bank,
                        'amount_due' => $amount_due,
                        'amount_paid'=> $amountPaid,
                        'receipt_file' => $file_name,
                        'payee' => $payee,
                        'payor' => $frcustomername,
                        'store_id' => $store_id,
                        'check_no' => $check_no,
                        'check_date' => $check_date,
                        'user_id' => $user_id,
                    );
                                    
                    //INSERT Payment supp docs FILE START
                    for ($i=0; $i < count($_FILES["supp_doc"]['name']); $i++)
                    {
                        $targetPath = getcwd() . '/assets/facilityrental_suppdocs/';
                        $tmpFilePath = $_FILES['supp_doc']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename = $timeStamp . $_FILES['supp_doc']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('facilityrental_receiptno' => $receipt_no, 'file_name' => $filename);
                            $this->facilityrental_model->insert('facilityrental_paymentsuppdocs', $data);
                        }
                    }
                    //INSERT Payment supp docs FILE END
                }
                
                $this->facilityrental_model->insert('facilityrental_payment', $insert_data);
                //insert payment data end
                
                $this->db->trans_complete();            
                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $flag = '0';
                    echo json_encode($flag);
                }
                else
                {
                    echo json_encode($file_name);
                }
            }
            else
            {
                $flag = '2';
                echo json_encode($flag);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }
    //PAYMENT HISTORY
    //PAYMENT HISTORY
    //PAYMENT HISTORY
    //PAYMENT HISTORY
    //PAYMENT HISTORY
    public function get_paymentdata()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $customer_id= $this->uri->segment(3);
            $result = $this->facilityrental_model->get_paymentdata($customer_id);
 
            echo json_encode($result);
        }
    }

    public function get_paymentDocs()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $receipt_no = str_replace("%20", " ", $this->uri->segment(3));
            $result     = $this->facilityrental_model->get_paymentDocs($receipt_no);

            echo json_encode($result);
        }
    }

    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    //TRUNCATE FACILITY RENTAL
    public function truncate_facilityrental()
    {
        $this->db->query('TRUNCATE facilityrental_addedinvoicediscount');
        $this->db->query('TRUNCATE facilityrental_customers');
        $this->db->query('TRUNCATE facilityrental_discountsetup');
        $this->db->query('TRUNCATE facilityrental_facilities');
        $this->db->query('TRUNCATE facilityrental_intentletter');
        $this->db->query('TRUNCATE facilityrental_invoicesummary');
        $this->db->query('TRUNCATE facilityrental_invoicing');
        $this->db->query('TRUNCATE facilityrental_payment');
        $this->db->query('TRUNCATE facilityrental_paymentsuppdocs');
        $this->db->query('TRUNCATE facilityrental_reservation');
        $this->db->query('TRUNCATE facilityrental_reservedfacility');
        $this->db->query('TRUNCATE facilityrental_reservedtime');
        $this->db->query('TRUNCATE facilityrental_soa');
        $this->db->query('TRUNCATE facilityrental_soafile');
        $this->db->query('TRUNCATE facilityrental_soadiscount');
        $this->db->query('TRUNCATE facilityrental_tmpinvoicediscount');
        $this->db->query('TRUNCATE facilityrental_tmpreservedatetime');
    }
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leasing_mstrfile extends CI_Controller {


    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('app_model');
        $this->load->library('upload');
        date_default_timezone_set('Asia/Manila');
        $timestamp = time();
        $this->_currentDate = date('Y-m-d', $timestamp);
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

    function stores()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $data['banks'] = $this->app_model->getAll('accredited_banks');
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/stores');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }

    function get_stores()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data = $this->app_model->get_stores();
            echo json_encode($data);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function floorplan_setup()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/floorplan_setup');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function upload_image($targetPath, $image_name)
    {
        $date = new DateTime();
        $timeStamp = $date->getTimestamp();
        $filename = "";

        $tmpFilePath = $_FILES[$image_name]['tmp_name'];
            //Make sure we have a filepath
        if ($tmpFilePath != "")
        {
            //Setup our new file path
            $filename = $timeStamp . $_FILES[$image_name]['name'];
            $newFilePath = $targetPath . $filename;
            //Upload the file into the temp dir
            move_uploaded_file($tmpFilePath, $newFilePath);
        }

        return $filename;
    }

    function get_floors()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data = $this->app_model->get_floors();
            echo json_encode($data);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_floorplan()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data = $this->app_model->get_floorplan();
            echo json_encode($data);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function setup_3DModel()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $floor_id = $this->uri->segment(3);
            $response = array();
            $targetPath = getcwd() . '/assets/3D/';
            $file_name = $this->upload_image($targetPath, 'x3d');
            $data = array('model' => $file_name);
            if ($this->app_model->update($data, $floor_id, 'floors')) {
                $response['msg'] = 'Success';
            }
            else
            {
                $response['msg'] = 'Error';
            }

            echo json_encode($response);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function view_3D()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            ;
            $data['model'] = $this->uri->segment(3);
            $this->load->view('leasing/3d_viewer', $data);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    function generate_locationCodeID()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result['locationCode_id'] = $this->app_model->generate_locationCodeID();
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    function get_prefix()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = str_replace("%20", " ", $this->uri->segment(3));
            $result['store_code'] = $this->app_model->get_prefix($store_name);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    function add_store()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name     = $this->sanitize($this->input->post('store_name'));
            $company_code   = $this->sanitize($this->input->post('company_code'));
            $store_code     = $this->sanitize($this->input->post('store_code'));
            $dept_code      = $this->sanitize($this->input->post('dept_code'));
            $store_address  = $this->sanitize($this->input->post('address'));
            $contact_person = $this->sanitize($this->input->post('contact_person'));
            $contact_no     = $this->sanitize($this->input->post('contact_number'));
            $email          = $this->sanitize($this->input->post('email'));
            $logo           = $this->input->post('logo');
            $targetPath     = getcwd() . '/assets/other_img/';
            $logo           = $this->upload_image($targetPath, 'store_logo');
            $floors         = $this->input->post('floor_name');
            $accre_bank     = $this->input->post('accre_bank');

            if ($this->app_model->add_store($company_code, $store_name, $store_code, $dept_code, $store_address, $contact_person, $contact_no, $email, $floors, $logo, $accre_bank)) {
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/stores');
            }
            else
            {
                $this->session->set_flashdata('message', 'Not saved');
                redirect('leasing_mstrfile/stores');
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }




    function add_floor()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_id = $this->uri->segment(3);
            $floor_name = $this->sanitize($this->input->post('floor_name'));
            $this->app_model->add_floor($store_id, $floor_name);
            $this->session->set_flashdata('message', 'Added');
            redirect('leasing_mstrfile/stores');

        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function cash_to_bank()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['stores'] = $this->app_model->get_storeForCashBankSetup();
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/cash_to_bank');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function add_cashtobank()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {

               $store_id = $this->sanitize($this->input->post('store_name'));
               $bank_code = $this->sanitize($this->input->post('bank_code'));

                $result = $this->app_model->add_cashtobank($store_id, $bank_code);

                if ($result)
                {
                    $this->session->set_flashdata('message', 'Added');
                }
                redirect('leasing_mstrfile/cash_to_bank');

            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function get_cashtobank()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $result = $this->app_model->get_cashtobank();
            echo json_encode($result);
        }
    }

    function add_selectedBank()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_id = $this->uri->segment(3);
            $bank_name = $this->sanitize($this->input->post('bank_name'));


            $result = $this->app_model->add_selectedBank($store_id, $bank_name);

            if ($result)
            {
                $this->session->set_flashdata('message', 'Added');
            }
            else
            {
                $this->session->set_flashdata('message', 'Duplicated');
            }
            redirect('leasing_mstrfile/stores');

        } else {
            redirect('ctrl_leasing/');
        }
    }


    function delete_cashbank()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $cashbank_id = $this->uri->segment(3);
            $data = array('status' => 'Not Active');
            $result = $this->app_model->update($data, $cashbank_id, 'cash_bank');

            if ($result)
            {
                $this->session->set_flashdata('message', 'Deleted');
            }

            redirect('leasing_mstrfile/cash_to_bank');

        } else {
            redirect('ctrl_leasing/');
        }
    }


    function get_cashbankforupdat()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $cashbank_id = $this->uri->segment(3);
            $result = $this->app_model->get_cashbankforupdat($cashbank_id);
            echo json_encode($result);
        }
    }

    function get_storedata()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_id = $this->uri->segment(3);
            $result = $this->app_model->get_storedata($store_id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    function get_myfloor()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_id = $this->uri->segment(3);
            $result = $this->app_model->get_myfloor($store_id);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_cashtobank()
    {
        $cashbank_id = $this->uri->segment(3);

         if ($this->session->userdata('leasing_logged_in'))
        {

            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {

               $store_name = $this->sanitize($this->input->post('store_name'));
               $bank_code = $this->sanitize($this->input->post('bank_code'));

                $result = $this->app_model->update_cashtobank($store_name, $bank_code, $cashbank_id);

                if ($result)
                {
                    $this->session->set_flashdata('message', 'Updated');
                }
                redirect('leasing_mstrfile/cash_to_bank');

            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }


    function get_selectedBanks()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_id = $this->uri->segment(3);
            $result = $this->app_model->get_myBanks($store_id);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    function get_selectedBankCode()
    {
       if ($this->session->userdata('leasing_logged_in'))
        {
            $bank_name = $this->sanitize(str_replace("%20", " " , $this->uri->segment(3)));
            $result = $this->app_model->get_bankCode($bank_name);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }



    function delete_floor()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'Administrator')
        {
            $floor_id = $this->uri->segment(3);


            if ($this->app_model->delete('floors', 'id', $floor_id))
            {
                $this->session->set_flashdata('message', 'Deleted');
            }

            redirect('leasing_mstrfile/stores');


        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    function delete_selectedBank()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'Administrator')
        {
            $selectedBank_id = $this->uri->segment(3);


            if ($this->app_model->delete('selected_banks', 'id', $selectedBank_id))
            {
                $this->session->set_flashdata('message', 'Deleted');
            }

            redirect('leasing_mstrfile/stores');


        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_store()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_id = $this->uri->segment(3);
            $store_name = $this->sanitize($this->input->post('store_name'));
            $company_code = $this->sanitize($this->input->post('company_code'));
            $store_code = $this->sanitize($this->input->post('store_code'));
            $dept_code = $this->sanitize($this->input->post('dept_code'));
            $store_address = $this->sanitize($this->input->post('store_address'));
            $contact_person = $this->sanitize($this->input->post('contact_person'));
            $contact_no = $this->sanitize($this->input->post('contact_number'));
            $email = $this->sanitize($this->input->post('email'));
            $floor_name = $this->input->post('floor_name');
            $floor_id = $this->input->post('floor_id');


            $data = array(
               'store_name'     => $store_name,
               'company_code'   => $company_code,
               'store_code'     => $store_code,
               'dept_code'      => $dept_code,
               'store_address'  => $store_address,
               'contact_person' => $contact_person,
               'contact_no'     => $contact_no,
               'email'          => $email
            );

            $tbl_name = 'stores';

            $this->app_model->update($data, $store_id, $tbl_name);
            $this->app_model->update_floors($floor_name, $floor_id);

            $this->session->set_flashdata('message', 'Updated');
            redirect('leasing_mstrfile/stores');

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    function delete_store()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $store_id = $this->uri->segment(3);
                if ($this->app_model->delete('stores', 'id', $store_id) == TRUE && $this->app_model->delete('floors', 'store_id', $store_id))
                {
                    $this->session->set_flashdata('message', 'Deleted');
                    redirect('leasing_mstrfile/stores');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Error');
                    redirect('leasing_mstrfile/stores');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }
    function category_one()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/category_one');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    function get_category_one()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('category_one');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    function add_category_one()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $category_name = $this->sanitize($this->input->post('category_name'));
                $description = $this->sanitize($this->input->post('description'));

                $data = array(
                   'category_name'   => $category_name,
                   'description'     => $description
                );

                $this->app_model->insert('category_one', $data);
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/category_one');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    function get_categoryOne_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('category_one', $id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    function update_categoryOne()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0') {
                $id = $this->uri->segment(3);
                $category_name = $this->sanitize($this->input->post('category_name'));
                $description = $this->sanitize($this->input->post('description'));

                $data = array(
                   'category_name'   => $category_name,
                   'description'     => $description
                );

                if ($this->app_model->update($data, $id, 'category_one')) {
                    $this->session->set_flashdata('message', 'Updated');
                    redirect('leasing_mstrfile/category_one');
                }
                else
                {
                    redirect('leasing_mstrfile/category_one');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    function delete_categoryOne()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id =  $this->uri->segment(3);
                if ($this->app_model->delete('category_one', 'id', $id))
                {
                    $this->session->set_flashdata('message', 'Deleted');
                    redirect('leasing_mstrfile/category_one');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Error');
                    redirect('leasing_mstrfile/category_one');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        } else {
             redirect('ctrl_leasing/');
        }
    }


    function category_two()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/category_two');
            $this->load->view('leasing/footer');

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    function get_category_two()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_category_two();
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    function add_category_two()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $first_level = $this->sanitize($this->input->post('first_level'));
                $second_level = $this->sanitize($this->input->post('second_level'));
                $description = $this->sanitize($this->input->post('description'));

                $categoryOne_id = $this->db->query("SELECT `id` FROM `category_one` WHERE `category_name` = '$first_level'")->row()->id;

                $data = array(
                   'category_name'   => $second_level,
                   'description'     => $description,
                   'categoryOne_id'  => $categoryOne_id
                );

                $this->app_model->insert('category_two', $data);
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/category_two');

            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }


    function get_categoryTwo_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->get_categoryTwo_data($id);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    function update_categoryTwo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_update = $this->uri->segment(3);
                $first_level = $this->sanitize($this->input->post('first_level'));
                $second_level = $this->sanitize($this->input->post('second_level'));
                $description = $this->sanitize($this->input->post('description'));

                $categoryOne_id = $this->db->query("SELECT `id` FROM `category_one` WHERE `category_name` = '$first_level'")->row()->id;

                $data = array(
                   'category_name'   => $second_level,
                   'description'     => $description,
                   'categoryOne_id'  => $categoryOne_id
                );

                if ($this->app_model->update($data, $id_to_update, 'category_two'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                    redirect('leasing_mstrfile/category_two');
                }
                else
                {
                    redirect('leasing_mstrfile/category_two');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }


    function delete_categoryTwo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_delete = $this->uri->segment(3);
                if ($this->app_model->delete('category_two', 'id', $id_to_delete)) {
                    $this->session->set_flashdata('message', 'Deleted');
                    redirect('leasing_mstrfile/category_two');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Error');
                    redirect('leasing_mstrfile/category_two');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        } else {
            redirect('ctrl_leasing/');
        }
    }


    function category_three()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/category_three');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_category_three()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_category_three();
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function populate_categoryTwo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $first_level = str_replace('%20', " ", $this->uri->segment(3));
            $result = $this->app_model->populate_categoryTwo($first_level);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function populate_categoryThree()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $second_level = str_replace('%20', " ", $this->uri->segment(3));
            $result = $this->app_model->populate_categoryThree($second_level);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function add_category_three()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $second_level = $this->input->post('second_level');
                $first_level = $this->sanitize($this->input->post('first_level'));
                $third_level = $this->sanitize($this->input->post('third_level'));
                $description = $this->sanitize($this->input->post('description'));
                if ($second_level[0] != "?")
                {

                    $categoryOne_id = $this->db->query("SELECT id FROM category_one WHERE category_name = '$first_level' LIMIT 1")->row()->id;
                    $categoryTwo_id = $this->db->query("SELECT id FROM category_two WHERE category_name = '$second_level' LIMIT 1")->row()->id;

                    $data = array(
                       'category_name'   => $third_level,
                       'description'     => $description,
                       'categorytwo_id'  => $categoryTwo_id
                    );

                    $this->app_model->insert('category_three', $data);
                    $this->session->set_flashdata('message', 'Added');
                    redirect('leasing_mstrfile/category_three');

                }
                else
                {
                    $this->session->set_flashdata('message', 'Required');
                    redirect('leasing_mstrfile/category_three');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_categoryThree_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_update = $this->uri->segment(3);
            $id = $this->uri->segment(3);
            $result = $this->app_model->get_categoryThree_data($id);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function update_categoryThree()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_update = $this->uri->segment(3);
                $second_level = $this->input->post('second_level');
                $first_level = $this->sanitize($this->input->post('first_level'));
                $third_level = $this->sanitize($this->input->post('third_level'));
                $description = $this->sanitize($this->input->post('description'));

                if ($second_level[0] != "?")
                {

                    $categoryOne_id = $this->db->query("SELECT id FROM category_one WHERE category_name = '$first_level' LIMIT 1")->row()->id;
                    $categoryTwo_id = $this->db->query("SELECT id FROM category_two WHERE category_name = '$second_level' LIMIT 1")->row()->id;

                    $data = array(
                       'category_name'   => $third_level,
                       'description'     => $description,
                       'categorytwo_id'  => $categoryTwo_id
                    );

                    if ($this->app_model->update($data, $id_to_update, 'category_three'))
                    {
                        $this->session->set_flashdata('message', 'Updated');
                        redirect('leasing_mstrfile/category_three');
                    }
                    else
                    {
                        redirect('leasing_mstrfile/category_three');
                    }
                }
                else
                {
                    $this->session->set_flashdata('message', 'Required');
                    redirect('leasing_mstrfile/category_three');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function delete_categoryThree()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_delete = $this->uri->segment(3);
                if ($this->app_model->delete('category_three', 'id', $id_to_delete))
                {

                    $this->session->set_flashdata('message', 'Deleted');
                    redirect('leasing_mstrfile/category_three');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Error');
                    redirect('leasing_mstrfile/category_three');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
             redirect('ctrl_leasing/');
        }
    }

    public function tenant_type()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/tenant_type');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function get_tenant_type()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('tenant_type');
            echo json_encode($result);

        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function add_tenant_type()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $tenant_type = $this->sanitize($this->input->post('tenant_type'));
                $discount_type = $this->sanitize($this->input->post('discount_type'));
                $description = $this->sanitize($this->input->post('description'));
                $discount = str_replace(",", "", $this->input->post('discount'));

                $data = array(
                   'tenant_type'   =>  $tenant_type,
                   'discount_type' =>  $discount_type,
                   'discount'      =>  $discount,
                   'description'   =>  $description
                );

                $this->app_model->insert('tenant_type', $data);
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/tenant_type');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function get_tenantType_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('tenant_type', $id);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function update_tenantType()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_update = $this->uri->segment(3);
                $tenant_type = $this->sanitize($this->input->post('tenant_type'));
                $discount_type = $this->sanitize($this->input->post('discount_type'));
                $description = $this->sanitize($this->input->post('description'));
                $discount = str_replace(",", "", $this->input->post('discount'));

                $data = array(
                   'tenant_type'   =>  $tenant_type,
                   'discount_type' =>  $discount_type,
                   'discount'      =>  $discount,
                   'description'   =>  $description
                );
                if ($this->app_model->update($data, $id_to_update, 'tenant_type'))
                {

                    $this->session->set_flashdata('message', 'Updated');
                }

                redirect('leasing_mstrfile/tenant_type');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function delete_tenantType()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_delete = $this->uri->segment(3);

                if ($this->app_model->delete('tenant_type', 'id', $id_to_delete))
                {
                    $this->session->set_flashdata('message', 'Deleted');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Error');
                }

                redirect('leasing_mstrfile/tenant_type');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function leasee_type()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/leasee_type');
            $this->load->view('leasing/footer');

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_leasee_type()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('leasee_type');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function add_leasee_type()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $leasee_type = $this->sanitize($this->input->post('leasee_type'));
                $description = $this->sanitize($this->input->post('description'));

                $data = array(
                   'leasee_type'   =>  $leasee_type,
                   'description'   =>  $description
                );

                $this->app_model->insert('leasee_type', $data);
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/leasee_type');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_leaseeType_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('leasee_type', $id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function update_leaseeType()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_update = $this->uri->segment(3);

                $leasee_type = $this->sanitize($this->input->post('leasee_type'));
                $description = $this->sanitize($this->input->post('description'));

                $data = array(
                   'leasee_type'   =>  $leasee_type,
                   'description'   =>  $description
                );

                if ($this->app_model->update($data, $id_to_update, 'leasee_type'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                }

                redirect('leasing_mstrfile/leasee_type');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function delete_leaseeType()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_delete = $this->uri->segment(3);

                if ($this->app_model->delete('leasee_type', 'id', $id_to_delete))
                {
                    $this->session->set_flashdata('message', 'Deleted');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Error');
                }
                redirect('leasing_mstrfile/leasee_type');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function price_floor()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');

            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $data['stores'] = $this->app_model->get_stores();
            }
            else
            {
                $data['stores'] = $this->app_model->get_store();
                $data['store_floors'] = $this->app_model->floor_no_pricing();
                $data['user_group'] = $this->app_model->my_store();
            }

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/price_floor');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_price_floor()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $result = $this->app_model->get_price_floor();
                echo json_encode($result);
            }
            else
            {
                $result = $this->app_model->store_price_floor();
                echo json_encode($result);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function floor_for_pricing()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = str_replace('%20', " ", $this->uri->segment(3));
            $result = $this->app_model->floor_for_pricing($store_name);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function floor_with_price()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = str_replace('%20', " ", $this->uri->segment(3));
            $result = $this->app_model->floor_with_price($store_name);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function populate_locationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = str_replace('%20', " ", $this->uri->segment(3));
            $floor_name = str_replace('%20', " ", $this->uri->segment(4));
            $result = $this->app_model->populate_locationCode($store_name, $floor_name);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function add_price_floor()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = $this->sanitize($this->input->post('store_name'));
            $floor_name = $this->sanitize($this->input->post('floor_name'));
            $price = str_replace(",", "", $this->sanitize($this->input->post('price')));
            if ($price != '0.00')
            {

                $floor_id = $this->db->query(
                    "SELECT
                        `f`.`id`
                    FROM
                        `stores` `s`,
                        `floors` `f`
                    WHERE
                        `f`.`floor_name` = '$floor_name'
                    AND
                        `s`.`store_name` = '$store_name'
                    AND
                        `f`.`store_id` = `s`.`id`
                    LIMIT 1")->row()->id;

                $data = array(
                   'floor_id'   =>  $floor_id,
                   'price'      =>  $price
                );

            $this->app_model->insert('price_floor', $data);

            $this->session->set_flashdata('message', 'Added');
            redirect('leasing_mstrfile/price_floor');

            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/price_floor');
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_priceFloor_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->get_priceFloor_data($id);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_priceFloor()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_update = $this->uri->segment(3);
            $store_name = $this->sanitize($this->input->post('store_name'));
            $floor_name = $this->sanitize($this->input->post('floor_name'));
            $price = str_replace(",", "", $this->sanitize($this->input->post('price')));
            if ($price != '0.00')
            {

                $floor_id = $this->db->query(
                    "SELECT
                        `f`.`id`
                    FROM
                        `stores` `s`,
                        `floors` `f`
                    WHERE
                        `f`.`floor_name` = '$floor_name'
                    AND
                        `s`.`store_name` = '$store_name'
                    AND
                        `f`.`store_id` = `s`.`id`
                    LIMIT 1")->row()->id;

                $data = array(
                   'floor_id'   =>  $floor_id,
                   'price'      =>  $price
                );

            $this->app_model->update($data, $id_to_update, 'price_floor');

            $this->session->set_flashdata('message', 'Updated');
            redirect('leasing_mstrfile/price_floor');

            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/price_floor');
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }



    public function delete_price_floor()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_delete = $this->uri->segment(3);
            if ($this->app_model->delete('price_floor', 'id', $id_to_delete))
            {
                $this->session->set_flashdata('message', 'Deleted');
                redirect('leasing_mstrfile/price_floor');
            }
            else
            {
                $this->session->set_flashdata('message', 'Error');
                redirect('leasing_mstrfile/price_floor');
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function price_locationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $data['stores'] = $this->app_model->get_stores();
            }
            else
            {
                $data['stores'] = $this->app_model->get_store();
                $data['store_floors'] = $this->app_model->store_floor_price();
                $data['user_group'] = $this->app_model->my_store();
            }
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/price_locationCode');
            $this->load->view('leasing/footer');

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function check_vacant()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->check_vacant();
            echo json_encode($result);
        }
    }


    public function get_price_locationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $this->app_model->check_vacant();
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $result = $this->app_model->get_price_locationCode();
                echo json_encode($result);
            }
            else
            {
                $result = $this->app_model->store_price_locationCode();
                echo json_encode($result);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_floor_price()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = str_replace('%20', " ", $this->uri->segment(3));
            $result = $this->app_model->get_floor_price($store_name);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_floorPrice()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = str_replace('%20', " ", $this->uri->segment(3));
            $floor_name = str_replace('%20', " ", $this->uri->segment(4));

            $result = $this->app_model->get_floorPrice($store_name, $floor_name);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    // public function update_locationCode()
    // {
    //     if ($this->session->userdata('leasing_logged_in'))
    //     {
    //         $id_to_update = $this->uri->segment(3);
    //         $store_name = $this->sanitize($this->input->post('store_name'));
    //         $floor_name = $this->sanitize($this->input->post('floor_name'));
    //         $location_code = $this->sanitize($this->input->post('location_code'));
    //         $floor_area = $this->sanitize($this->input->post('floor_area'));
    //         $floor_price = $this->sanitize($this->input->post('floor_price'));
    //         $floor_area = str_replace(",", "", $floor_area);


    //         $price_floor_id = $this->db->query(
    //                             "SELECT
    //                             `pf`.`id`
    //                         FROM
    //                             `price_floor` `pf`,
    //                             `stores` `s`,
    //                             `floors` `f`
    //                         WHERE
    //                             `s`.`store_name` = '$store_name'
    //                         AND
    //                             `f`.`floor_name` = '$floor_name'
    //                         AND
    //                             `f`.`store_id` = `s`.`id`
    //                         AND
    //                             `f`.`id` = `pf`.`floor_id`
    //                         LIMIT 1")->row()->id;

    //         if ($floor_area != '0.00' && $floor_price != '0.00')
    //         {

    //             $data = array(
    //                'price_floor_id'   =>  $price_floor_id,
    //                'location_code'    =>  $location_code,
    //                'floor_area'       =>  $floor_area
    //             );

    //             if ($this->app_model->update($data, $id_to_update, 'price_locationCode')) {
    //                 $this->session->set_flashdata('message', 'Updated');
    //             }

    //             redirect('leasing_mstrfile/price_locationCode');


    //         } else {
    //             $this->session->set_flashdata('message', 'Required');
    //             redirect('leasing_mstrfile/price_locationCode');
    //         }

    //     } else {
    //         redirect('ctrl_leasing/');
    //     }
    // }

    public function delete_price_locationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_delete = $this->uri->segment(3);
            if ($this->app_model->delete('price_locationCode', 'id', $id_to_delete))
            {
                $this->session->set_flashdata('message', 'Deleted');
                redirect('leasing_mstrfile/price_locationCode');
            }
            else
            {
                $this->session->set_flashdata('message', 'Error');
                redirect('leasing_mstrfile/price_locationCode');
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function exhibit_rates()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $data['flashdata'] = $this->session->flashdata('message');

            if ($this->session->userdata('user_type') != 'Administrator')
            {
                $data['stores'] = $this->app_model->get_store();
                $data['store_floors'] = $this->app_model->store_floors();
                $data['user_group'] = $this->app_model->my_store();
            }
            else
            {
                $data['stores'] = $this->app_model->get_stores();
            }

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/exhibit_rates');
            $this->load->view('leasing/footer');

        }
        else
        {
            redirect('ctrl_leasing/');
        }

    }

    public function get_exhibit_rates()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $this->app_model->scheck_vacant();
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $result = $this->app_model->get_exhibit_rates();
                echo json_encode($result);
            }
            else
            {
                $result = $this->app_model->store_exhibit_rates();
                echo json_encode($result);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function add_exhibit_rate()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $store_name = $this->sanitize($this->input->post('store_name'));
            $floor_name = $this->sanitize($this->input->post('floor_name'));
            $location_code = $this->sanitize($this->input->post('location_code'));
            $floor_area = $this->sanitize($this->input->post('floor_area'));
            $floor_price = $this->sanitize($this->input->post('floor_price'));
            $category = $this->sanitize($this->input->post('category'));
            $floor_area = str_replace(",", "", $floor_area);


            $price_floor_id = $this->db->query(
                                "SELECT
                                `pf`.`id`
                            FROM
                                `price_floor` `pf`,
                                `stores` `s`,
                                `floors` `f`
                            WHERE
                                `s`.`store_name` = '$store_name'
                            AND
                                `f`.`floor_name` = '$floor_name'
                            AND
                                `f`.`store_id` = `s`.`id`
                            AND
                                `f`.`id` = `pf`.`floor_id`
                            LIMIT 1")->row()->id;

            if ($floor_area != '0.00' && $floor_price != '0.00')
            {

                $data = array(
                   'price_floor_id'   =>  $price_floor_id,
                   'location_code'    =>  $location_code,
                   'floor_area'       =>  $floor_area,
                   'category'         =>  $category
                );

                $this->app_model->insert('exhibit_rates', $data);
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/exhibit_rates');
            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/exhibit_rates');
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_exhibitRate_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->get_exhibitRate_data($id);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function update_exhibitRate()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_update = $this->uri->segment(3);

            $store_name = $this->sanitize($this->input->post('store_name'));
            $floor_name = $this->sanitize($this->input->post('floor_name'));
            $location_code = $this->sanitize($this->input->post('location_code'));
            $floor_area = $this->sanitize($this->input->post('floor_area'));
            $floor_price = $this->sanitize($this->input->post('floor_price'));
            $category = $this->sanitize($this->input->post('category'));
            $floor_area = str_replace(",", "", $floor_area);


            $price_floor_id = $this->db->query(
                                "SELECT
                                `pf`.`id`
                            FROM
                                `price_floor` `pf`,
                                `stores` `s`,
                                `floors` `f`
                            WHERE
                                `s`.`store_name` = '$store_name'
                            AND
                                `f`.`floor_name` = '$floor_name'
                            AND
                                `f`.`store_id` = `s`.`id`
                            AND
                                `f`.`id` = `pf`.`floor_id`
                            LIMIT 1")->row()->id;

            if ($floor_area != '0.00' && $floor_price != '0.00')
            {

                $data = array(
                   'price_floor_id'   =>  $price_floor_id,
                   'location_code'    =>  $location_code,
                   'floor_area'       =>  $floor_area,
                   'category'         =>  $category
                );

                if ($this->app_model->update($data, $id_to_update, 'exhibit_rates'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                }

                redirect('leasing_mstrfile/exhibit_rates');
            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/exhibit_rates');
            }


        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function delete_exhibit_rate()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_delete = $this->uri->segment(3);

            if ($this->app_model->delete('exhibit_rates', 'id', $id_to_delete))
            {
                $this->session->set_flashdata('message', 'Deleted');
                redirect('leasing_mstrfile/exhibit_rates');
            }
            else
            {
                $this->session->set_flashdata('message', 'Error');
                redirect('leasing_mstrfile/exhibit_rates');
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function charges_setup()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type'))
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $data['charge_code'] = $this->app_model->generate_chargesCode();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/charges_setup');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function add_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type'))
            {
                $data = array();
                $charges_type = $this->sanitize($this->input->post('charges_type'));
                $charges_code = $this->sanitize($this->input->post('charges_code'));
                $description = $this->sanitize($this->input->post('description'));
                $uom = $this->sanitize($this->input->post('uom'));

                $numberOfmons = $this->sanitize($this->input->post('numberOfmons'));
                $unit_price = $this->sanitize($this->input->post('unit_price'));
                $unit_price = str_replace(",", "", $unit_price);

                $with_penalty = $this->input->post('with_penalty');

                if ($with_penalty != 'Yes')
                {
                    $with_penalty = "No";
                }

                if ($charges_type != 'Pre Operation Charges')
                {
                    $data = array(
                        'charges_type' => $charges_type,
                        'charges_code' => $charges_code,
                        'description'  => $description,
                        'uom'          => $uom,
                        'unit_price'   => $unit_price,
                        'with_penalty' => $with_penalty
                    );

                }
                else
                {
                    $data = array(
                        'charges_type' => $charges_type,
                        'charges_code' => $charges_code,
                        'description'  => $description,
                        'uom'          => $numberOfmons,
                        'unit_price'   => 0,
                        'with_penalty' => $with_penalty
                    );
                }

                $this->app_model->insert('charges_setup', $data);
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/charges_setup');


            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    
    public function get_charges_setup()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('charges_setup');
            echo json_encode($result);
        }
    }

    public function get_chargesSetup_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('charges_setup', $id);
            echo json_encode($result);
        }
    }

    public function update_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
           if ($this->session->userdata('user_type') == 'Administrator')
           {
                $id_to_update = $this->uri->segment(3);

                $data = array();
                $charges_type = $this->sanitize($this->input->post('charges_type'));
                $charges_code = $this->sanitize($this->input->post('charges_code'));
                $description = $this->sanitize($this->input->post('description'));
                $uom = $this->sanitize($this->input->post('uom'));

                $numberOfmons = $this->sanitize($this->input->post('numberOfmons'));
                $unit_price = $this->sanitize($this->input->post('unit_price'));
                $unit_price = str_replace(",", "", $unit_price);

                $with_penalty = $this->input->post('with_penalty');

                if ($with_penalty != 'Yes')
                {
                    $with_penalty = "No";
                }


                if ($charges_type != 'Pre Operation Charges')
                {
                    $data = array(
                        'charges_type' => $charges_type,
                        'charges_code' => $charges_code,
                        'description'  => $description,
                        'uom'          => $uom,
                        'unit_price'   => $unit_price,
                        'with_penalty' => $with_penalty
                    );
                }
                else
                {
                    $data = array(
                        'charges_type' => $charges_type,
                        'charges_code' => $charges_code,
                        'description'  => $description,
                        'uom'          => $numberOfmons,
                        'unit_price'   => 0,
                        'with_penalty' => $with_penalty
                    );
                }


                $this->app_model->update($data, $id_to_update, 'charges_setup');
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/charges_setup');

            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function delete_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor')
            {
                $id_to_delete = $this->uri->segment(3);
                if ($this->app_model->delete('charges_setup', 'id', $id_to_delete))
                {
                    $this->session->set_flashdata('message', 'Deleted');
                }

                redirect('leasing_mstrfile/charges_setup');

            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function other_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['stores'] = $this->app_model->get_stores();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/other_charges');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_other_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('other_charges');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function add_other_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $description = $this->sanitize($this->input->post('description'));
                $uom = $this->sanitize($this->input->post('uom'));
                $amount = $this->sanitize($this->input->post('amount'));
                $amount = str_replace(",", "", $amount);

                if ($amount != '0.00') {

                    $data = array(
                        'description' => $description,
                        'uom'         => $uom,
                        'amount'      => $amount
                    );

                    $this->app_model->insert('other_charges', $data);
                    $this->session->set_flashdata('message', 'Added');
                    redirect('leasing_mstrfile/other_charges');

                }
                else
                {
                    $this->session->set_flashdata('message', 'Required');
                    redirect('leasing_mstrfile/other_charges');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_otherCharges_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('other_charges', $id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_otherCharges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_update = $this->uri->segment(3);
                $description = $this->sanitize($this->input->post('description'));
                $uom = $this->sanitize($this->input->post('uom'));
                $amount = $this->sanitize($this->input->post('amount'));
                $amount = str_replace(",", "", $amount);

                if ($amount != '0.00')
                {

                    $data = array(
                        'description' => $description,
                        'uom'         => $uom,
                        'amount'      => $amount
                    );

                    if ($this->app_model->update($data, $id_to_update, 'other_charges')) {
                        $this->session->set_flashdata('message', 'Updated');
                    }

                    redirect('leasing_mstrfile/other_charges');

                }
                else
                {
                    $this->session->set_flashdata('message', 'Required');
                    redirect('leasing_mstrfile/other_charges');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function delete_otherCharges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '0' || $this->session->userdata('user_group') == '0')
            {
                $id_to_delete =  $this->uri->segment(3);
                if ($this->app_model->delete('other_charges', 'id', $id_to_delete))
                {
                    $this->session->set_flashdata('message', 'Deleted');
                    redirect('leasing_mstrfile/other_charges');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Error');
                    redirect('leasing_mstrfile/other_charges');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function monthly_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/monthly_charges');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function add_monthly_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $description = $this->sanitize($this->input->post('description'));
                $uom = $this->sanitize($this->input->post('uom'));
                $amount = $this->sanitize($this->input->post('amount'));
                $amount = str_replace(",", "", $amount);

                if ($amount != '0.00')
                {

                    $data = array(
                        'description' => $description,
                        'uom'         => $uom,
                        'amount'      => $amount
                    );

                    $this->app_model->insert('monthly_charges', $data);
                    $this->session->set_flashdata('message', 'Added');
                    redirect('leasing_mstrfile/monthly_charges');

                }
                else
                {
                    $this->session->set_flashdata('message', 'Required');
                    redirect('leasing_mstrfile/monthly_charges');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function get_monthly_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_monthly_charges();
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_monthlyCharges_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('monthly_charges', $id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_monthlyCharges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_update = $this->uri->segment(3);
                $description = $this->sanitize($this->input->post('description'));
                $uom = $this->sanitize($this->input->post('uom'));
                $amount = $this->sanitize($this->input->post('amount'));
                $amount = str_replace(",", "", $amount);

                if ($amount != '0')
                {

                    $data = array(
                        'description' => $description,
                        'uom'         => $uom,
                        'amount'      => $amount
                    );

                    if ($this->app_model->update($data, $id_to_update, 'monthly_charges')) {
                        $this->session->set_flashdata('message', 'Updated');
                    }

                    redirect('leasing_mstrfile/monthly_charges');

                }
                else
                {
                    $this->session->set_flashdata('message', 'Required');
                    redirect('leasing_mstrfile/monthly_charges');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function delete_monthlyCharges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_delete =  $this->uri->segment(3);
                if ($this->app_model->delete('monthly_charges', 'id', $id_to_delete)) {
                    $this->session->set_flashdata('message', 'Deleted');
                    redirect('leasing_mstrfile/monthly_charges');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Error');
                    redirect('leasing_mstrfile/monthly_charges');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function overtime_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/overtime_charges');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function get_overtime_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('overtime_charges');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_overtimeCharges_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('overtime_charges', $id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_overtime_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $id_to_update = $this->uri->segment(3);
                $time_begin = $this->input->post('time_begin');
                $time_end = $this->input->post('time_end');
                $per_hour = str_replace(",", "", $this->input->post('per_hour'));
                $first_three = str_replace(",", "", $this->input->post('first_three'));
                $three_to_six = str_replace(",", "", $this->input->post('three_to_six'));
                $six_to_nine = str_replace(",", "", $this->input->post('six_to_nine'));
                $nine_to_eleven = str_replace(",", "", $this->input->post('nine_to_eleven'));

                if ($per_hour == '0.00' || $first_three == '0.00' || $three_to_six == '0.00' || $six_to_nine == '0.00' || $nine_to_eleven == '0.00')
                {
                    $this->session->set_flashdata('message', 'Required');
                    redirect('leasing_mstrfile/overtime_charges');
                }
                else
                {
                    $data = array(
                        'time_begin'        =>  $time_begin,
                        'time_end'          =>  $time_end,
                        'per_hour'          =>  $per_hour,
                        'first_three'       =>  $first_three,
                        'three_to_six'      =>  $three_to_six,
                        'six_to_nine'       =>  $six_to_nine,
                        'nine_to_eleven'    =>  $nine_to_eleven
                    );

                    if ($this->app_model->update($data, $id_to_update, 'overtime_charges'))
                    {
                        $this->session->set_flashdata('message', 'Updated');
                    }

                    redirect('leasing_mstrfile/overtime_charges');
                }
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }


        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_leasing_users()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_leasing_users();
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function add_leasing_user()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data = array();

            $first_name = $this->sanitize($this->input->post('first_name'));
            $middle_name = $this->sanitize($this->input->post('middle_name'));
            $last_name = $this->sanitize($this->input->post('last_name'));
            $username = $this->sanitize($this->input->post('username'));
            $password = $this->sanitize($this->input->post('password'));
            $user_type = $this->sanitize($this->input->post('user_type'));
            $user_group = $this->sanitize($this->input->post('user_group'));
            $user_group_id = $this->app_model->store_id($user_group);

            if ($user_type == "Administrator" || $user_type == "Corporate Leasing Supervisor" || $user_type == "Corporate Documentation Officer")
            {

                $data = array(
                    'first_name'    =>  $first_name,
                    'middle_name'   =>  $middle_name,
                    'last_name'     =>  $last_name,
                    'username'      =>  $username,
                    'password'      =>  md5($password),
                    'user_type'     =>  $user_type,
                    'status'        =>  'Active'
                );

            }
            else
            {

                $data = array(
                    'first_name'    =>  $first_name,
                    'middle_name'   =>  $middle_name,
                    'last_name'     =>  $last_name,
                    'username'      =>  $username,
                    'password'      =>  md5($password),
                    'user_type'     =>  $user_type,
                    'status'        =>  'Active',
                    'user_group'    =>  $user_group_id
                );

            }
            $this->app_model->insert('leasing_users', $data);
            $this->session->set_flashdata('message', 'Added');
            redirect('leasing_mstrfile/leasing_users');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function preOperation_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['security_bond'] = $this->app_model->getAll('security_bond');
                $data['advance_rent'] = $this->app_model->getAll('advance_rent');
                $data['construction_bond'] = $this->app_model->getAll('construction_bond');
                $data['plywood_enc'] = $this->app_model->getAll('plywood_enc');
                $data['door_lock'] = $this->app_model->getAll('door_lock');
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/preOperation_charges');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function security_bond()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('security_bond');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function update_secbond()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $mos = $this->input->post('security_bond');
                $temp = explode(" ", $mos);
                $actual_num = $temp[0];
                $data = array(
                    'months'     =>  $mos,
                    'actual_num' =>  $actual_num
                );
                $this->db->update('security_bond', $data);

                if ($this->db->affected_rows() > 0)
                {
                   $this->session->set_flashdata('message', 'Updated');
                }

                redirect('leasing_mstrfile/preOperation_charges');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function advance_rent()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('advance_rent');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_adrent()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $mos = $this->input->post('advance_rent');
                $temp = explode(" ", $mos);
                $actual_num = $temp[0];
                $data = array(
                    'months'     =>  $mos,
                    'actual_num' =>  $actual_num
                );
                $this->db->update('advance_rent', $data);

                if ($this->db->affected_rows() > 0) {
                   $this->session->set_flashdata('message', 'Updated');
                }

                redirect('leasing_mstrfile/preOperation_charges');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function cons_bond()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('construction_bond');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_cons_bond()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $mos = $this->input->post('cons_bond');
                $temp = explode(" ", $mos);
                $actual_num = $temp[0];
                $data = array(
                    'months'     =>  $mos,
                    'actual_num' =>  $actual_num
                );
                $this->db->update('construction_bond', $data);

                if ($this->db->affected_rows() > 0)
                {
                   $this->session->set_flashdata('message', 'Updated');
                }

                redirect('leasing_mstrfile/preOperation_charges');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function plywood_enc()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('plywood_enc');
            echo json_encode($result);
        } else {
            redirect('ctrl_leasing/');
        }
    }

    public function update_plywood_enc()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $addon = $this->sanitize($this->input->post('addon'));
            $per_lmeter = $this->sanitize($this->input->post('lmeter'));
            $per_lmeter = str_replace(",", "", $per_lmeter);

            if ($addon == '0.00' || $per_lmeter == '0.00')
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/preOperation_charges');
            }
            else
            {
                $data = array(
                    'addon'      => $addon,
                    'per_lmeter' => $per_lmeter
                );

                $this->db->update('plywood_enc', $data);

                if ($this->db->affected_rows() > 0)
                {
                   $this->session->set_flashdata('message', 'Updated');
                }
                redirect('leasing_mstrfile/preOperation_charges');
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function door_lock()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('door_lock');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_door_lock()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $per_set = str_replace(",", "", $this->sanitize($this->input->post('per_set')));
            if ($per_set != '0.00') {
                $data = array('per_set' => $per_set);
                $this->db->update('door_lock', $data);

                if ($this->db->affected_rows() > 0)
                {
                   $this->session->set_flashdata('message', 'Updated');
                }
                redirect('leasing_mstrfile/preOperation_charges');
            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/preOperation_charges');
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function penalty_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $data['penalty_lateopen'] = $this->app_model->getAll('penalty_lateopening');
                $data['penalty_latepayment'] = $this->app_model->getAll('penalty_latepayment');
                $data['flashdata'] = $this->session->flashdata('message');
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/penalty_charges');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function penalty_lateopen()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('penalty_lateopening');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_penalty_lateopen()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $per_hour = $this->sanitize($this->input->post('penalty_lateopen'));
            if ($per_hour != '0') {

            $data = array('per_hour' =>  $per_hour);
            $this->db->update('penalty_lateopening', $data);

            if ($this->db->affected_rows() > 0)
            {
               $this->session->set_flashdata('message', 'Updated');
            }
            redirect('leasing_mstrfile/penalty_charges');

            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/penalty_charges');
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function penalty_latepayment()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('penalty_latepayment');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_penalty_latepayment()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $multiplier = $this->sanitize($this->input->post('penalty_latepayment'));

            if ($multiplier != '0')
            {
                $data = array('multiplier' =>  $multiplier);
                $this->db->update('penalty_latepayment', $data);

                if ($this->db->affected_rows() > 0)
                {
                   $this->session->set_flashdata('message', 'Updated');
                }
                redirect('leasing_mstrfile/penalty_charges');
            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/penalty_charges');
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function vat_setup()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $data['vat'] = $this->app_model->get_vat();
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $data['flashdata'] = $this->session->flashdata('message');
                $data['rental_increment'] = $this->app_model->getAll('rental_increment');
                $data['wtax'] = $this->app_model->getAll('withholding_tax');
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/vat_setup');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_vat()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $vat = $this->sanitize($this->input->post('vat'));
            $this->db->query("UPDATE vat SET vat = '$vat'");
            if ($this->db->affected_rows() > 0)
            {
               $this->session->set_flashdata('message', 'Updated');
            }

            redirect('leasing_mstrfile/vat_setup');

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function rentalInc()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('rental_increment');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function wtax()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('withholding_tax');
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_rentalInc()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $amount = str_replace(",", "", $this->sanitize($this->input->post('rental_inc')));

            if ($amount != '0') {

                $data = array('amount' =>  $amount);
                $this->db->update('rental_increment', $data);

                if ($this->db->affected_rows() > 0)
                {
                   $this->session->set_flashdata('message', 'Updated');
                }
                redirect('leasing_mstrfile/vat_setup');

            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/vat_setup');
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function update_rentalIncrement()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'General Manager')
        {
            $amount = str_replace(",", "", $this->sanitize($this->input->post('rent_increment')));

            if ($amount != '0')
            {

                $data = array('amount' =>  $amount);
                $this->db->update('rental_increment', $data);

                if ($this->db->affected_rows() > 0)
                {
                   $this->session->set_flashdata('message', 'Updated');
                }
                redirect('ctrl_leasing/GM_home');

            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('ctrl_leasing/GM_home');
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function update_wtax()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $wtax = str_replace(",", "", $this->sanitize($this->input->post('wtax')));

            if ($wtax != '0')
            {

                $data = array('withholding' =>  $wtax);
                $this->db->update('withholding_tax', $data);

                if ($this->db->affected_rows() > 0)
                {
                   $this->session->set_flashdata('message', 'Updated');
                }
                redirect('leasing_mstrfile/vat_setup');

            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/vat_setup');
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function leasing_users()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            if ($this->session->userdata('user_group') == '' || $this->session->userdata('user_group') == '0')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $data['stores'] = $this->app_model->get_stores();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/leasing_users');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function delete_user()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_delete = $this->uri->segment(3);

            if ($this->app_model->delete('leasing_users', 'id', $id_to_delete)) {
                $this->session->set_flashdata('message', 'Deleted');
                redirect('leasing_mstrfile/leasing_users');
            }
            else
            {
                $this->session->set_flashdata('message', 'Error');
                redirect('leasing_mstrfile/leasing_users');
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function activate_user()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $data = array('status' => 'Active');

            if ($this->app_model->update($data, $id, 'leasing_users'))
            {
                $this->session->set_flashdata('message', 'Activated');
            }

            redirect('leasing_mstrfile/leasing_users');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function block_user()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $id = $this->uri->segment(3);
            $data = array('status' => 'Blocked');

            if ($this->app_model->update($data, $id, 'leasing_users'))
            {
                $this->session->set_flashdata('message', 'Blocked');
            }

            redirect('leasing_mstrfile/leasing_users');

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function reset_password()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $data = array('password' => md5('Leasing123'));

            if ($this->app_model->update($data, $id, 'leasing_users'))
            {
                $this->session->set_flashdata('message', 'Reset');
            }
            redirect('leasing_mstrfile/leasing_users');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_leasingUser_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->get_leasingUser_data($id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function update_leasingUser()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_update = $this->uri->segment(3);
            $data = array();

            $first_name = $this->sanitize($this->input->post('first_name'));
            $middle_name = $this->sanitize($this->input->post('middle_name'));
            $last_name = $this->sanitize($this->input->post('last_name'));
            $username = $this->sanitize($this->input->post('username'));
            $user_type = $this->sanitize($this->input->post('user_type'));
            $user_group = $this->sanitize($this->input->post('user_group'));
            $user_group_id = $this->app_model->store_id($user_group);

            if ($user_type == "Administrator" && $user_type == "Corporate Documentation Officer" && $user_type == "Corporate Accounting Staff" && $user_type == "Corporate Leasing Supervisor" )
            {
                $data = array(
                    'first_name'    =>  $first_name,
                    'middle_name'   =>  $middle_name,
                    'last_name'     =>  $last_name,
                    'username'      =>  $username,
                    'user_type'     =>  $user_type,
                    'status'        =>  'Active',
                    'user_group'    =>  ''
                );

            }
            else
            {
                $data = array(
                    'first_name'    =>  $first_name,
                    'middle_name'   =>  $middle_name,
                    'last_name'     =>  $last_name,
                    'username'      =>  $username,
                    'user_type'     =>  $user_type,
                    'status'        =>  'Active',
                    'user_group'    =>  $user_group_id
                );
            }

            if ($this->app_model->update($data, $id_to_update, 'leasing_users'))
            {
                $this->session->set_flashdata('message', 'Updated');
            }

            redirect('leasing_mstrfile/leasing_users');

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function area_classification()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Documentation Officer' || $this->session->userdata('user_type') == 'Supervisor' || $this->session->userdata('user_type') == 'Store Manager' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/area_classification');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function get_areaClassification()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('area_classification');
            echo json_encode($result);
        }
        
    }


    public function add_areaClassification()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $classification = $this->sanitize($this->input->post('classification'));
                $description = $this->sanitize($this->input->post('description'));
                $data = array('classification' => $classification, 'description' => $description);

                $this->app_model->insert('area_classification', $data);
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/area_classification');

            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function get_areaClassification_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('area_classification', $id);
            echo json_encode($result);
        }
    }


    public function update_areaClassification()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $id_to_update = $this->uri->segment(3);
                $classification = $this->sanitize($this->input->post('classification'));
                $description = $this->sanitize($this->input->post('description'));
                $data = array('classification' => $classification, 'description' => $description);

                if ($this->app_model->update($data, $id_to_update, 'area_classification'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                }
                redirect('leasing_mstrfile/area_classification');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
    }


    public function delete_areaClassification()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator')
            {
               $id_to_delete = $this->uri->segment(3);
                if ($this->app_model->delete('area_classification', 'id', $id_to_delete))
                {
                    $this->session->set_flashdata('message', 'Deleted');
                }

                redirect('leasing_mstrfile/area_classification');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function area_type()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Documentation Officer' || $this->session->userdata('user_type') == 'Store Manager' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/area_type');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function decodeArray($data)
    {

        $d = array();

        foreach($data as $key=>$value)
        {

            if(is_string($key))

                $key = htmlspecialchars_decode($key,ENT_QUOTES);

            if(is_string($value))

                $value = htmlspecialchars_decode($value,ENT_QUOTES);

            else if(is_array($value))   

                $value = self::decodeArray($value);

            $d[$key] = $value;

        }

        return $d;
    }


    public function get_areaType()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('area_type');
            echo json_encode($result);
        }
    }

    public function add_areaType()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $type = $this->sanitize($this->input->post('type'));
                $description = $this->sanitize($this->input->post('description'));
                $data = array('type' => $type, 'description' => $description);

                $this->app_model->insert('area_type', $data);
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/area_type');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function get_areaType_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->selectWhere('area_type', $id);
            echo json_encode($result);
        }
    }


    public function update_areaType()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $id_to_update = $this->uri->segment(3);
                $type = $this->sanitize($this->input->post('type'));
                $description = $this->sanitize($this->input->post('description'));
                $data = array('type' => $type, 'description' => $description);


                if ($this->app_model->update($data, $id_to_update, 'area_type'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                }

                redirect('leasing_mstrfile/area_type');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function delete_areaType()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $id_to_delete = $this->uri->segment(3);
                if ($this->app_model->delete('area_type', 'id', $id_to_delete))
                {
                    $this->session->set_flashdata('message', 'Deleted');
                }

                redirect('leasing_mstrfile/area_type');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }




    public function rent_period()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Documentation Officer')
            {
                $data['flashdata'] = $this->session->flashdata('message');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/rent_period');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function get_rentPeriod()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll2('rent_period');
            echo json_encode($result);
        }
    }


    public function add_rentPeriod()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor')
            {
                $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
                $number = $this->sanitize($this->input->post('number'));
                $uom = $this->sanitize($this->input->post('uom'));


                $data = array(
                    'tenancy_type' =>   $tenancy_type,
                    'number'       =>   $number,
                    'uom'          =>   $uom,
                    'status'       =>   'Active'
                );

                $this->app_model->insert('rent_period', $data);
                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/rent_period');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function get_rentPeriod_data()
    {
        $id = $this->uri->segment(3);
        $result = $this->app_model->selectWhere('rent_period', $id);
        echo json_encode($result);
    }


    public function update_rentPeriod()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor')
            {
                $id_to_update = $this->uri->segment(3);
                $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
                $number = $this->sanitize($this->input->post('number'));
                $uom = $this->sanitize($this->input->post('uom'));


                $data = array(
                    'tenancy_type' =>   $tenancy_type,
                    'number'       =>   $number,
                    'uom'          =>   $uom
                );

                if ($this->app_model->update($data, $id_to_update, 'rent_period'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                }

                redirect('leasing_mstrfile/rent_period');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }

// ========================================== ORIGINAL/OLD CODE
    // public function delete_rentPeriod()
    // {
    //      if ($this->session->userdata('leasing_logged_in'))
    //     {
    //         if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor')
    //         {
    //             $id_to_delete = $this->uri->segment(3);
    //             if ($this->app_model->delete('rent_period', 'id', $id_to_delete))
    //             {
    //                 $this->session->set_flashdata('message', 'Deleted');
    //             }
    //             redirect('leasing_mstrfile/rent_period');
    //         }
    //         else
    //         {
    //             $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
    //             $this->session->set_flashdata('message', 'Restriction');
    //             redirect($prev_url);
    //         }
    //     }
    //     else
    //     {
    //         redirect('ctrl_leasing');
    //     }
    // }

// ================================================================== KING ARTHURS REVISION ========================================================================== //
    public function delete_rentPeriod()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor')
            {
                $id_to_deactivate = $this->uri->segment(3);
                // $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
                // $number = $this->sanitize($this->input->post('number'));
                // $uom = $this->sanitize($this->input->post('uom'));


                $data = array('status' => 'Deactivated');

                if ($this->app_model->update($data, $id_to_deactivate, 'rent_period'))
                {
                    $this->session->set_flashdata('message', 'Deactivated');
                }

                redirect('leasing_mstrfile/rent_period');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }

        //  if ($this->session->userdata('leasing_logged_in'))
        // {
        //     if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor')
        //     {
        //         $id_to_delete = $this->uri->segment(3);
        //         if ($this->app_model->delete('rent_period', 'id', $id_to_delete))
        //         {
        //             $this->session->set_flashdata('message', 'Deleted');
        //         }
        //         redirect('leasing_mstrfile/rent_period');
        //     }
        //     else
        //     {
        //         $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
        //         $this->session->set_flashdata('message', 'Restriction');
        //         redirect($prev_url);
        //     }
        // }
        // else
        // {
        //     redirect('ctrl_leasing');
        // }
    }
// ================================================================== KING ARTHURS REVISION END ========================================================================== //


    public function locationCode_setup()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor' || $this->session->userdata('user_type') == 'Documentation Officer')
            {

                $data['flashdata'] = $this->session->flashdata('message');
                if ($this->session->userdata('user_type') == 'Administrator')
                {
                    $data['stores'] = $this->app_model->get_stores();
                }
                else
                {
                    $data['stores'] = $this->app_model->my_store();
                    $data['store_floors'] = $this->app_model->store_floors();
                }

                $data['area_classification'] = $this->app_model->getAll('area_classification');
                $data['area_type'] = $this->app_model->getAll('area_type');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/locationCode_setup');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function locationSlot_setup()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor' || $this->session->userdata('user_type') == 'Documentation Officer' || $this->session->userdata('user_type') == 'Store Manager' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
            {

                $data['flashdata'] = $this->session->flashdata('message');
                if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
                {
                    $data['stores'] = $this->app_model->get_stores();
                }
                else
                {
                    $data['stores'] = $this->app_model->my_store();
                    $data['store_floors'] = $this->app_model->store_floors();
                }

                $data['area_classification'] = $this->app_model->getAll('area_classification');
                $data['area_type'] = $this->app_model->getAll('area_type');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/locationSlot_setup');
                $this->load->view('leasing/footer');
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function get_locationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor' || $this->session->userdata('user_type') == 'Documentation Officer' || $this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Store Manager')
            {
                $result = $this->app_model->get_locationCode();
                echo json_encode($result);
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }



    public function get_locationSlot()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor' || $this->session->userdata('user_type') == 'Documentation Officer' || $this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Store Manager' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
            {
                $result = $this->app_model->get_locationSlot();
                echo json_encode($result);
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }



    public function locationCode_occupied()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor' || $this->session->userdata('user_type') == 'Documentation Officer')
            {
                $result = $this->app_model->locationCode_occupied();
                echo json_encode($result);
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function locationCode_vacant()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor' || $this->session->userdata('user_type') == 'Documentation Officer')
            {
                $result = $this->app_model->locationCode_vacant();
                echo json_encode($result);
            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function populate_floors()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = str_replace("%20", " ", $this->uri->segment(3));
            $result = $this->app_model->populate_floors($store_name);
            echo json_encode($result);
        }
    }


    public function populate_rentPeriod()
    {
        $tenancy_type = str_replace("%20", " ", $this->uri->segment(3));
        $result = $this->app_model->populate_rentPeriod($tenancy_type);
        echo json_encode($result);
    }




    public function add_locationSlot()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'Administrator')
        {
            $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
            $store_name = $this->sanitize($this->input->post('store_name'));
            $floor_name = $this->sanitize($this->input->post('floor_name'));
            $slot_no = strtoupper($this->sanitize($this->input->post('slot_no')));
            $floor_area = str_replace(",", "", $this->sanitize($this->input->post('floor_area')));
            $rental_rate = str_replace(",", "", $this->sanitize($this->input->post('rental_rate')));
            $floor_id = $this->app_model->get_floorID($store_name, $floor_name);


            if ($floor_area != '0.00' && $rental_rate != '0.00')
            {

                // =================== Insert to Location Code table =========== //

                $data = array(
                   'tenancy_type'           =>      $tenancy_type,
                   'floor_id'               =>      $floor_id,
                   'slot_no'                =>      $slot_no,
                   'floor_area'             =>      $floor_area,
                   'rental_rate'            =>      $rental_rate,
                   'modified_by'            =>      $this->session->userdata('id'),
                   'modified_date'          =>      $this->_currentDate
                );

                $this->app_model->insert('location_slot', $data);

                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/locationSlot_setup');
            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/locationSlot_setup');
            }
        }
    }


    public function add_locationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
            $store_name = $this->sanitize($this->input->post('store_name'));
            $floor_name = $this->sanitize($this->input->post('floor_name'));
            $location_code = strtoupper($this->sanitize($this->input->post('prefix'))) . '-' .  strtoupper($this->sanitize($this->input->post('location_code')));
            $floor_area = str_replace(",", "", $this->sanitize($this->input->post('floor_area')));
            $rental_rate = str_replace(",", "", $this->sanitize($this->input->post('rental_rate')));
            $area_classification = $this->sanitize($this->input->post('area_classification'));
            $area_type = $this->sanitize($this->input->post('area_type'));
            $rent_period = $this->sanitize($this->input->post('rent_period'));
            $payment_mode = $this->sanitize($this->input->post('payment_mode'));
            $floor_id = $this->app_model->get_floorID($store_name, $floor_name);

            if ($floor_area != '0.00' && $rental_rate != '0.00')
            {

                // =================== Insert to Location Code table =========== //

                $data = array(
                   'tenancy_type'           =>      $tenancy_type,
                   'floor_id'               =>      $floor_id,
                   'location_code'          =>      $location_code,
                   'floor_area'             =>      $floor_area,
                   'rental_rate'            =>      $rental_rate,
                   'area_classification'    =>      $area_classification,
                   'area_type'              =>      $area_type,
                   'rent_period'            =>      $rent_period,
                   'payment_mode'           =>      $payment_mode,
                   'status'                 =>      'Vacant',
                   'modified_by'            =>      $this->session->userdata('id'),
                   'date_modified'          =>      $this->_currentDate,
                   'flag'                   =>      'Active'
                );

                $this->app_model->insert('location_code', $data);


                // ============= Insert to Location Code History table =========== //

                $locationCode_id = $this->app_model->get_locationCode_id($location_code);
                $data_to_history = array(
                   'locationCode_id'        =>      $locationCode_id,
                   'tenancy_type'           =>      $tenancy_type,
                   'floor_id'               =>      $floor_id,
                   'location_code'          =>      $location_code,
                   'floor_area'             =>      $floor_area,
                   'rental_rate'            =>      $rental_rate,
                   'area_classification'    =>      $area_classification,
                   'area_type'              =>      $area_type,
                   'rent_period'            =>      $rent_period,
                   'payment_mode'           =>      $payment_mode,
                   'modified_by'            =>      $this->session->userdata('id'),
                   'date_modified'          =>      $this->_currentDate,
                   'status'                 =>      'Active'
                );
                $this->app_model->insert('history_locationCode', $data_to_history);


                $this->session->set_flashdata('message', 'Added');
                redirect('leasing_mstrfile/locationCode_setup');

            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/locationCode_setup');
            }

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_locationCode_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->get_locationCode_data($id);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }



    public function get_locationSlot_data()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->get_locationSlot_data($id);
            echo json_encode($result);

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function update_locationSlot()
    {
        if ($this->session->userdata('leasing_logged_in') && $this->session->userdata('user_type') == 'Administrator')
        {
            $id_to_update = $this->sanitize($this->input->post('id_to_update'));
            $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
            $store_name = $this->sanitize($this->input->post('store_name'));
            $floor_name = $this->sanitize($this->input->post('floor_name'));
            $slot_no = strtoupper($this->sanitize($this->input->post('slot_no')));
            $floor_area = str_replace(",", "", $this->sanitize($this->input->post('floor_area')));
            $rental_rate = str_replace(",", "", $this->sanitize($this->input->post('rental_rate')));
            $floor_id = $this->app_model->get_floorID($store_name, $floor_name);


            if ($floor_area != '0.00' && $rental_rate != '0.00')
            {

                // =================== Insert to Location Code table =========== //

                $data = array(
                   'tenancy_type'           =>      $tenancy_type,
                   'floor_id'               =>      $floor_id,
                   'slot_no'                =>      $slot_no,
                   'floor_area'             =>      $floor_area,
                   'rental_rate'            =>      $rental_rate,
                   'modified_by'            =>      $this->session->userdata('id'),
                   'modified_date'          =>      $this->_currentDate
                );


                $this->app_model->locationSlot_history($id_to_update);
                
                
                if ($this->app_model->update($data, $id_to_update, 'location_slot'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                }

                redirect('leasing_mstrfile/locationSlot_setup');
            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
                redirect('leasing_mstrfile/locationSlot_setup');
            }
        }
    }


    public function update_locationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $username = $this->sanitize(str_replace("%20", " ", $this->uri->segment(3)));
            $password = $this->sanitize(str_replace("%20", " ", $this->uri->segment(4)));
            $response = array();


            $id_to_update = $this->sanitize($this->input->post('id_to_update'));
            $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
            $store_name = $this->sanitize($this->input->post('store_name'));
            $floor_name = $this->sanitize($this->input->post('floor_name'));
            $location_code = strtoupper($this->sanitize($this->input->post('prefix'))) . '-' . strtoupper($this->sanitize($this->input->post('location_code')));
            $floor_area = str_replace(",", "", $this->sanitize($this->input->post('floor_area')));
            $rental_rate = str_replace(",", "", $this->sanitize($this->input->post('rental_rate')));
            $area_classification = $this->sanitize($this->input->post('area_classification'));
            $area_type = $this->sanitize($this->input->post('area_type'));
            $rent_period = $this->sanitize($this->input->post('rent_period'));
            $payment_mode = $this->sanitize($this->input->post('payment_mode'));
            $floor_id = $this->app_model->get_floorID($store_name, $floor_name);


            $data_to_update = array(
               'tenancy_type'           =>      $tenancy_type,
               'floor_id'               =>      $floor_id,
               'location_code'          =>      $location_code,
               'floor_area'             =>      $floor_area,
               'rental_rate'            =>      $rental_rate,
               'area_classification'    =>      $area_classification,
               'area_type'              =>      $area_type,
               'rent_period'            =>      $rent_period,
               'payment_mode'           =>      $payment_mode,
               'status'                 =>      'Vacant',
               'modified_by'            =>      $this->session->userdata('id'),
               'date_modified'          =>      $this->_currentDate,
               'flag'                   =>      'Active'
            );


            $data_to_history = array(
               'locationCode_id'        =>      $id_to_update,
               'tenancy_type'           =>      $tenancy_type,
               'floor_id'               =>      $floor_id,
               'location_code'          =>      $location_code,
               'floor_area'             =>      $floor_area,
               'rental_rate'            =>      $rental_rate,
               'area_classification'    =>      $area_classification,
               'area_type'              =>      $area_type,
               'rent_period'            =>      $rent_period,
               'payment_mode'           =>      $payment_mode,
               'modified_by'            =>      $this->session->userdata('id'),
               'date_modified'          =>      $this->_currentDate,
               'status'                 =>      'Active'
            );

            if ($floor_area != '0.00' && $rental_rate != '0.00')
            {
                if ($this->app_model->update($data_to_update, $id_to_update, 'location_code'))
                {
                    $this->app_model->disable_locationCodeHistoryStatus($id_to_update);
                    $this->app_model->insert('history_locationCode', $data_to_history);
                    $this->session->set_flashdata('message', 'Updated');
                }
            }
            else
            {
                $this->session->set_flashdata('message', 'Required');
            }

            redirect('leasing_mstrfile/locationCode_setup');
        }
    }


    public function delete_locationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_delete = $this->uri->segment(3);

            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor')
            {
                if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager")
                {

                    $username = $this->sanitize($this->input->post('username'));
                    $password = $this->sanitize($this->input->post('password'));
                    $store_name = $this->app_model->my_store();

                    if ($this->app_model->managers_key($username, $password, $store_name))
                    {
                        if ($this->app_model->delete('location_code', 'id', $id_to_delete))
                        {
                            $this->session->set_flashdata('message', 'Deleted');
                        }
                        redirect('leasing_mstrfile/locationCode_setup');

                    }
                    else
                    {
                        $this->session->set_flashdata('message', 'Invalid Key');
                        redirect('leasing_mstrfile/locationCode_setup');
                    }

                }
                else
                {

                    if ($this->app_model->delete('location_code', 'id', $id_to_delete))
                    {
                        $this->session->set_flashdata('message', 'Deleted');
                    }

                    redirect('leasing_mstrfile/locationCode_setup');
                }

            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
    }
    


    public function delete_locationSlot()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_delete = $this->uri->segment(3);

            if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor')
            {
                if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager")
                {

                    $username = $this->sanitize($this->input->post('username'));
                    $password = $this->sanitize($this->input->post('password'));
                    $store_name = $this->app_model->my_store();

                    if ($this->app_model->managers_key($username, $password, $store_name))
                    {
                        if ($this->app_model->delete('location_slot', 'id', $id_to_delete))
                        {
                            $this->session->set_flashdata('message', 'Deleted');
                        }
                        redirect('leasing_mstrfile/locationSlot_setup');

                    }
                    else
                    {
                        $this->session->set_flashdata('message', 'Invalid Key');
                        redirect('leasing_mstrfile/locationSlot_setup');
                    }

                }
                else
                {

                    if ($this->app_model->delete('location_slot', 'id', $id_to_delete))
                    {
                        $this->session->set_flashdata('message', 'Deleted');
                    }

                    redirect('leasing_mstrfile/locationSlot_setup');
                }

            }
            else
            {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        }
    }


    public function get_locationCodeSlotHistory()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $result = $this->app_model->get_locationCodeSlotHistory($id);
            echo json_encode($result);
        }
    }


    public function gl_accounts()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/gl_accounts');
            $this->load->view('leasing/footer');

        }
        else
        {
            redirect('ctrl_leasing');
        }
    }

    public function get_GLaccounts()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->getAll('gl_accounts');
            echo json_encode($result);
        }
    }


    public function add_GLaccount()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $gl_code = $this->sanitize($this->input->post('gl_code'));
            $gl_account = $this->sanitize($this->input->post('gl_account'));

            $data = array('gl_code' => $gl_code, 'gl_account' => $gl_account);
            $this->app_model->insert('gl_accounts', $data);
            $this->session->set_flashdata('message', 'Added');
            redirect('leasing_mstrfile/gl_accounts');
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }

    public function get_glAccount_data()
    {
        $id_to_update = $this->uri->segment(3);
        $result = $this->app_model->selectWhere('gl_accounts', $id_to_update);
        echo json_encode($result);
    }

    public function update_GLaccount()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id_to_update = $this->uri->segment(3);
            $gl_code = $this->sanitize($this->input->post('gl_code'));
            $gl_account = $this->sanitize($this->input->post('gl_account'));

            $data = array(
               'gl_code'    => $gl_code,
               'gl_account' => $gl_account
            );

            if ($this->app_model->update($data, $id_to_update, 'gl_accounts'))
            {
                $this->session->set_flashdata('message', 'Updated');
                redirect('leasing_mstrfile/gl_accounts');
            }
            else
            {
                redirect('leasing_mstrfile/gl_accounts');
            }

        }
        else
        {
            redirect('ctrl_leasing');
        }
    }



    public function delete_GLaccount()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id =  $this->uri->segment(3);
            if ($this->app_model->delete('gl_accounts', 'id', $id))
            {
                $this->session->set_flashdata('message', 'Deleted');
                redirect('leasing_mstrfile/gl_accounts');
            }
            else
            {
                $this->session->set_flashdata('message', 'Error');
                redirect('leasing_mstrfile/gl_accounts');
            }
        }
    }


    public function bank_setup()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/bank_setup');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }

    public function add_bank()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $bank_code = $this->sanitize($this->input->post('bank_code'));
            $bank_name = $this->sanitize($this->input->post('bank_name'));
            $store_code = $this->sanitize($this->input->post('store_code'));


            $data = array('bank_code' => $bank_code, 'bank_name' => $bank_name, 'store_code' => $store_code);
            $this->app_model->insert('accredited_banks', $data);
            $this->session->set_flashdata('message', 'Added');
            redirect('leasing_mstrfile/bank_setup');
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }

    public function get_accreditedBanks()
    {
        $result = $this->db->query("SELECT b.*, s.store_name FROM accredited_banks b LEFT JOIN stores s on s.store_code = b.store_code")->result_array();
        //$this->app_model->getAll('accredited_banks');
        echo json_encode($result);
    }

    public function get_accreditedBankData()
    {
        $id = $this->uri->segment(3);
        $result = $this->app_model->selectWhere('accredited_banks', $id);
        echo json_encode($result);
    }


    public function update_bankDetails()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $bank_code = $this->sanitize($this->input->post('bank_code'));
            $bank_name = $this->sanitize($this->input->post('bank_name'));
            $store_code = $this->sanitize($this->input->post('store_code'));

            $data = array('bank_code' => $bank_code, 'bank_name' => $bank_name, 'store_code'=>$store_code);

            if ($this->app_model->update($data, $id, 'accredited_banks'))
            {
                $this->session->set_flashdata('message', 'Updated');
            }

            redirect('leasing_mstrfile/bank_setup');

        }
        else
        {
            redirect('ctrl_leasing');
        }

    }


    public function delete_accreditedBank()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);

            if ($this->app_model->delete('accredited_banks', 'id', $id))
            {
                $this->session->set_flashdata('message', 'Deleted');
            }

            redirect('leasing_mstrfile/bank_setup');

        }
        else
        {
            redirect('ctrl_leasing');
        }
    }

}

/* End of file Leasing_mstrfile.php */
/* Location: ./application/controllers/Leasing_mstrfile.php */

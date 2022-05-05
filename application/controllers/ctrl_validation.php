<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ctrl_validation extends CI_Controller {


    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('app_model');
        $this->load->library('upload');

        //Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }


    function sanitize($string)
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }


    function verify_store($data)
    {
        $data = str_replace('%20', " ", $data);
        $result = $this->db->query("SELECT
                                        *
                                    FROM
                                        stores
                                    WHERE
                                        store_name = '$data'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }


    function verify_slot($data)
    {
        $data = str_replace('%20', " ", $data);
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        location_slot
                                    WHERE
                                        slot_no = '$data'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }

    function verify_storeCode($data)
    {
        $data = str_replace('%20', " ", $data);
        $result = $this->db->query("SELECT
                                        *
                                    FROM
                                        stores
                                    WHERE
                                        store_code = '$data'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }


    function verify_storeCode_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $store_code = $data[0];
        $id = $data[1];
        $result = $this->db->query("SELECT
                                        *
                                    FROM
                                        stores
                                    WHERE
                                        store_code = '$store_code'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    public function verify_slotUpdate($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $slot_no = $data[0];
        $id = $data[1];
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        location_slot
                                    WHERE
                                        slot_no = '$slot_no'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    public function verify_added_floor($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $floor_name = $data[0];
        $id = $data[1];
        $result = $this->db->query("SELECT
                                        *
                                    FROM
                                        floors
                                    WHERE
                                        floor_name = '$floor_name'
                                    AND
                                        store_id = '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    public function verify_storeupdate($data)
    {

        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $store_name = $data[0];
        $id = $data[1];
        $result = $this->db->query("SELECT
                                        *
                                    FROM
                                        stores
                                    WHERE
                                        store_name = '$store_name'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    function verify_category_one()
    {
        $category_name = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        category_one
                                    WHERE
                                        category_name = '$category_name'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }

    function verify_categoryOne_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $category_name = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        category_one
                                    WHERE
                                        category_name = '$category_name'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }

    function verify_category_two()
    {
        $category_name = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        category_two
                                    WHERE
                                        category_name = '$category_name'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }


    function verify_categoryTwo_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $category_name = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        category_two
                                    WHERE
                                        category_name = '$category_name'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    function verify_category_three()
    {
        $category_name = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        category_three
                                    WHERE
                                        category_name = '$category_name'");
        if ($result->num_rows() > 0) {
            echo true;
        } else {
            echo false;
        }
    }


    function verify_categoryThree_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $category_name = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        category_three
                                    WHERE
                                        category_name = '$category_name'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }

    function verify_tenant_type()
    {
        $tenant_type = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        tenant_type
                                    WHERE
                                        tenant_type = '$tenant_type'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }

    function verify_tenantType_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $tenant_type = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        tenant_type
                                    WHERE
                                        tenant_type = '$tenant_type'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    function verify_leasee_type()
    {
        $leasee_type = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        leasee_type
                                    WHERE
                                        leasee_type = '$leasee_type'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }


    function verify_areaClassification()
    {
        $classification = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        area_classification
                                    WHERE
                                        classification = '$classification'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }


    function verify_leaseeType_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $leasee_type = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        leasee_type
                                    WHERE
                                        leasee_type = '$leasee_type'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    function verify_areaType()
    {
        $type = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        area_type
                                    WHERE
                                        type = '$type'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }


    function verify_areaType_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $type = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        area_type
                                    WHERE
                                        type = '$type'
                                    AND
                                        id != '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }

    public function verify_locationCode($data)
    {
        $location_code = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        price_locationCode
                                    WHERE
                                        location_code = '$location_code'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }

    public function verify_locationCode_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $location_code = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        price_locationCode
                                    WHERE
                                        location_code = '$location_code'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    public function verify_exhibit_locationCode($data)
    {
        $location_code = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        exhibit_rates
                                    WHERE
                                        location_code = '$location_code'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }


    public function verify_exhibitLocationCode_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $location_code = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        exhibit_rates
                                    WHERE
                                        location_code = '$location_code'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    public function verify_Charges($data)
    {
        $description = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        charges_setup
                                    WHERE
                                        description = '$description'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }


    public function verify_Charges_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $description = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        charges_setup
                                    WHERE
                                        description = '$description'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }




    public function verify_otherCharges($data)
    {
        $description = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        other_charges
                                    WHERE
                                        description = '$description'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }




    public function verify_otherCharges_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $description = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        other_charges
                                    WHERE
                                        description = '$description'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    public function verify_monthlyCharges()
    {
        $description = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        monthly_charges
                                    WHERE
                                        description = '$description'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }



    public function verify_monthlyCharges_update($data)
    {
        $data = str_replace('%20', " ", $data);
        $data = explode("_", $data);
        $description = $data[0];
        $id = $data[1];

        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        monthly_charges
                                    WHERE
                                        description = '$description'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    public function verify_username($username)
    {
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        leasing_users
                                    WHERE
                                        username = '$username'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
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
                                        leasing_users
                                    WHERE
                                        username = '$username'
                                    AND
                                        id <> '$id'");
        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    public function check_oldpass($data)
    {
        $data = explode("_", $data);
        $oldpass = $data[0];
        $id = $data[1];

        $result = $this->db->query(
            "SELECT
                `id`
            FROM
                `leasing_users`
            WHERE
                `password` = '" . md5($oldpass) . "'
            AND
                `id` = '$id'"
            );

        if ($result->num_rows()>0)
        {
            echo false;
        } else {

            echo true;
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

        if ($result->num_rows()> 0)
        {
            echo false;
        } else {
            echo true;
        }
    }



    public function verify_areaLocationCode()
    {
        $location_code = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        id
                                    FROM
                                        location_code
                                    WHERE
                                        location_code = '$location_code'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }


    public function verify_receiptNo()
    {
        $receipt_no = str_replace('%20', " ", $this->uri->segment(3));
        //$result = $this->db->query("SELECT `id` FROM `payment_scheme` WHERE `receipt_no` = '$receipt_no' OR `receipt_no` = 'PR".$receipt_no."'");
        $result = $this->db->query("SELECT `id` FROM `payment_scheme` WHERE ROUND(`amount_paid`) >= 1 AND (`receipt_no` = '$receipt_no' OR `receipt_no` = 'PR".$receipt_no."')");

        //var_dump($result->result_array());
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }

    public function verify_checkNo()
    {
        $check_no = str_replace('%20', " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        `id`
                                    FROM
                                        `payment_scheme`
                                    WHERE
                                        `check_no` = '$check_no'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }

    public function verify_glCode()
    {

        $gl_code = str_replace("%20", " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        `id`
                                    FROM
                                        `gl_accounts`
                                    WHERE
                                        `gl_code` = '$gl_code'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }

    public function verify_glAccount()
    {
        $gl_account = str_replace("%20", " ", $this->uri->segment(3));
        $result = $this->db->query("SELECT
                                        `id`
                                    FROM
                                        `gl_accounts`
                                    WHERE
                                        `gl_account` = '$gl_account'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }
    }


    public function verify_glCode_update($data)
    {
        $data = explode("_", $data);
        $gl_code = $data[0];
        $id = $data[1];

        $result = $this->db->query(
            "SELECT
                `id`
            FROM
                `gl_accounts`
            WHERE
                `gl_code` = '$gl_code'
            AND
                `id` != '$id'"
            );

        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }


    public function verify_bank_code($data)
    {
        $bank_name = str_replace("%20", " ", $data);
        $result = $this->db->query("SELECT
                                        `id`
                                    FROM
                                        `accredited_banks`
                                    WHERE
                                        `bank_code` = '$bank_name'");
        if ($result->num_rows()>0) {
            echo true;
        } else {
            echo false;
        }

    }



    public function verify_bankData_update($data)
    {
        $data = explode("_", $data);
        $bank_code = $data[0];
        $id = $data[1];

        $result = $this->db->query(
            "SELECT
                `id`
            FROM
                `accredited_banks`
            WHERE
                `bank_code` = '$bank_code'
            AND
                `id` != '$id'"
            );

        if ($result->num_rows()>0)
        {
            echo true;
        } else {

            echo false;
        }
    }



}

/* End of file Ctrl_validation.php */
/* Location: ./application/controllers/Ctrl_validation.php */

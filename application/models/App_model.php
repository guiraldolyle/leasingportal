<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->_user_group = $this->session->userdata('user_group');
        $this->_user_id = $this->session->userdata('id');
    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function check_login($username, $password)
    {

        $query = $this->db->query(
            "SELECT
                `u`.`id`,
                `u`.`password`,
                `u`.`user_type`,
                `u`.`username`,
                `u`.`first_name`,
                `u`.`middle_name`,
                `u`.`last_name`,
                `u`.`user_group`,
                `s`.`company_code`,
                `s`.`store_code`,
                `s`.`dept_code`
            FROM
                `leasing_users` `u`
            LEFT Join
                `stores` `s`
            ON
                `u`.`user_group` = `s`.`id`
            WHERE
                `u`.`password` = '" . MD5($password) . "'
            AND
                `u`.`username` = '$username'
        ");

        if($query->num_rows() > 0)
        {
            foreach($query->result() as $rows)
            {
                //add all data to session
                $data = array(
                    'id'                =>  $rows->id,
                    'password'          =>  $rows->password,
                    'user_type'         =>  $rows->user_type,
                    'username'          =>  $rows->username,
                    'first_name'        =>  $rows->first_name,
                    'middle_name'       =>  $rows->middle_name,
                    'last_name'         =>  $rows->last_name,
                    'user_group'        =>  $rows->user_group,
                    'company_code'      =>  $rows->company_code,
                    'store_code'        =>  $rows->store_code,
                    'dept_code'         =>  $rows->dept_code,
                    'leasing_logged_in' =>  TRUE
                );
                $this->session->set_userdata($data);
                
            }

            $session = (object)$this->session->userdata;

            $user_session_data = [
            	'user_id'	 => isset($session->id) 	    ? $session->id         : '',
            	'session_id' => isset($session->session_id) ? $session->session_id : '',
            	'ip_address' => isset($session->ip_address) ? $session->ip_address : '',
            	'user_agent' => isset($session->user_agent) ? $session->user_agent : '',
            	'user_data'	 => json_encode($session),
            	'login_in'   => 'leasing'
            ];

            $this->db->insert('user_session', $user_session_data);

            return true;
        }

        return false;
    }



    public function check_adminLogin($username, $password)
    {
        $this -> db -> select('*');
        $this -> db -> from('leasing_users');
        $this -> db -> where('username = ' . "'" . $username . "'");
        $this -> db -> where('password = ' . "'" . md5($password) . "'");
        $this -> db -> where('status = ' . "'" . 'Active' . "'");
        $this -> db -> where('user_type = ' . "'" . 'Administrator' . "'");
        $this -> db -> limit(1);
        $query = $this -> db -> get();


        if($query->num_rows()>0)
        {
            foreach($query->result() as $rows)
            {
                //add all data to session
                $data = array(
                    'id'                    =>  $rows->id,
                    'password'              =>  $rows->password,
                    'user_type'             =>  $rows->user_type,
                    'username'              =>  $rows->username,
                    'first_name'            =>  $rows->first_name,
                    'middle_name'           =>  $rows->middle_name,
                    'last_name'             =>  $rows->last_name,
                    'user_group'            =>  $rows->user_group,
                    'company_code'          =>  '',
                    'leasing_logged_in'     =>  TRUE
                );
                $this->session->set_userdata($data);
            }

            $session = (object)$this->session->userdata;

            $user_session_data = [
            	'user_id'	 => isset($session->id) 	    ? $session->id         : '',
            	'session_id' => isset($session->session_id) ? $session->session_id : '',
            	'ip_address' => isset($session->ip_address) ? $session->ip_address : '',
            	'user_agent' => isset($session->user_agent) ? $session->user_agent : '',
            	'user_data'	 => json_encode($session),
            	'login_in'   => 'admin'
            ];

            $this->db->insert('user_session', $user_session_data);

            return true;
        }
        return false;
    }



    public function check_cfsLogin($username, $password)
    {
         $query = $this->db->query(
            "SELECT
                `u`.`id`,
                `u`.`password`,
                `u`.`user_type`,
                `u`.`username`,
                `u`.`first_name`,
                `u`.`middle_name`,
                `u`.`last_name`,
                `u`.`user_group`,
                `s`.`company_code`,
                `s`.`store_code`
            FROM
                `leasing_users` `u`
            LEFT Join
                `stores` `s`
            ON
                `u`.`user_group` = `s`.`id`
            WHERE
                `u`.`password` = '" . MD5($password) . "'
            AND
                `u`.`username` = '$username'
        ");


        if($query->num_rows()>0)
        {
            foreach($query->result() as $rows)
            {
                //add all data to session
                $data = array(
                    'id'                    =>  $rows->id,
                    'password'              =>  $rows->password,
                    'user_type'             =>  $rows->user_type,
                    'username'              =>  $rows->username,
                    'first_name'            =>  $rows->first_name,
                    'middle_name'           =>  $rows->middle_name,
                    'last_name'             =>  $rows->last_name,
                    'user_group'            =>  $rows->user_group,
                    'store_code'            =>  $rows->store_code,
                    'company_code'          =>  $rows->company_code,
                    'cfs_logged_in'         =>  TRUE
                );
                $this->session->set_userdata($data);
                
            }

            $session = (object)$this->session->userdata;

            $user_session_data = [
            	'user_id'	 => isset($session->id) 	    ? $session->id         : '',
            	'session_id' => isset($session->session_id) ? $session->session_id : '',
            	'ip_address' => isset($session->ip_address) ? $session->ip_address : '',
            	'user_agent' => isset($session->user_agent) ? $session->user_agent : '',
            	'user_data'	 => json_encode($session),
            	'login_in'   => 'cfs'
            ];

            $this->db->insert('user_session', $user_session_data);

            return true;
        }
        return false;
    }


    public function user_credentials()
    {
        $this -> db -> select('*');
        $this -> db -> from('leasing_users');
        $this -> db -> where('id = ' . "'" . $this->_user_id . "'");
        $query = $this -> db -> get();

        return $query->result_array();
    }

    public function tenant_user_credentials()
    {
        $user = array();

        $this -> db -> select('*');
        $this -> db -> from('tenant_users');
        $this -> db -> where('id = ' . "'" . $this->_user_id . "'");
        $query = $this -> db -> get();

        if ($query->num_rows() > 0) 
        {
            foreach($query->result_array() as $value)
            {
                $user = array(['id'       => $value['id'],
                    'username' => $value['tenant_id'],
                    'password' => $value['password']]);
            }

            return $user;
        }
        // echo $this->db->last_query();
        // return $query->result_array();
    }


    public function managers_key($username, $password, $store_name)
    {
        $Muser_type;
        $Muser_group;
        $Mstore_name;

        $query = $this->db->query(
            "SELECT
                `u`.`user_type`,
                `u`.`user_group`,
                `s`.`store_name`
            FROM
                `leasing_users` `u`
            LEFT JOIN
                `stores` `s`
            ON
                `u`.user_group = `s`.`id`
            WHERE
                `u`.`username` = '$username'
            AND
                `u`.`password` = '" . MD5($password) . "'
            LIMIT 1
        ");

        if ($query->num_rows() > 0)
        {

            foreach($query->result() as $rows)
            {
                $Muser_type = $rows->user_type;
                $Muser_group = $rows->user_group;
                $Mstore_name = $rows->store_name;
            }

            if ($Muser_type == 'Corporate Leasing Supervisor')
            {
                return true;
            }
            else
            {
                $ret = ($Mstore_name == $store_name  && ($Muser_type == 'Store Manager' ||  $Muser_type == 'Supervisor') ? true : false);
                return $ret;
            }
        }
    }



    public function check_tradeName($store_id, $trade_name) {

        $query = $this->db->query("SELECT id FROM prospect WHERE store_id = '$store_id' AND trade_name = '$trade_name'");

        if ($query->num_rows() > 0) {
            return false;
        }
        
        return true;
    }


    public function get_expiryTenants()
    {
        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Documentation Officer' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor')
        {
            $query = $this->db->query(
                "SELECT
                    `p`.`trade_name`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`
                FROM
                    `prospect` `p`,
                    `tenants` `t`
                WHERE
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`status` = 'Active'
                AND
                    `t`.`flag` = 'Posted'
                AND
                    `t`.`expiry_date` <= NOW() + INTERVAL 3 MONTH
                AND
                    `t`.`tenancy_type` = 'Long Term'
            ");
            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `p`.`trade_name`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`
                FROM
                    `prospect` `p`,
                    `tenants` `t`
                WHERE
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`status` = 'Active'
                AND
                    (`t`.`expiry_date` <= NOW() + INTERVAL 3 MONTH AND `t`.`expiry_date` > NOW())
                AND
                    `t`.`tenancy_type` = 'Long Term'
                AND
                    `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
            ");
            return $query->result_array();
        }
    }



    public function add_store($company_code, $store_name, $dept_code, $store_code, $store_address, $contact_person, $contact_no, $email, $floors, $logo, $accre_bank)
    {
        $data = array(
           'store_name'     =>  $store_name ,
           'company_code'   =>  $company_code ,
           'store_code'     =>  $store_code ,
           'dept_code'      =>  $dept_code ,
           'store_address'  =>  $store_address,
           'contact_person' =>  $contact_person ,
           'contact_no'     =>  $contact_no ,
           'email'          =>  $email,
           'logo'           =>  $logo
        );


        $this->db->trans_start();
        $this->db->insert('stores', $data);
        $store_id = $this->db->query("SELECT id FROM stores WHERE store_name = '$store_name' LIMIT 1 ")->row()->id;
        for ($i=0; $i < count($floors); $i++)
        {
            $this->db->query("INSERT INTO
                            `floors`
                            (
                                `store_id`,
                                `floor_name`
                            )
                        VALUES
                            (
                                '$store_id',
                                '" . $this->sanitize($floors[$i]) . "'
                            )");
        }


        for ($i=0; $i < count($accre_bank); $i++)
        {
            $bank_id = $this->db->query("SELECT id FROM accredited_banks WHERE bank_name = '" . $accre_bank[$i] . "' LIMIT 1 ")->row()->id;

            $selected = array(
               'bank_id'     =>  $bank_id ,
               'store_id'    =>  $store_id
            );

            $this->insert('selected_banks', $selected);

        }


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            return TRUE;
        }

    }




    public function get_stores()
    {
        $query = $this->db->get('stores');
        return $query->result_array();
    }


    public function tenant_floorLocation($id)
    {
        $query = $this->db->query(
            "SELECT
                `f`.`floor_name`
            FROM
                `stores` `s`,
                `floors` `f`,
                `tenants` `t`
            WHERE
                `f`.`store_id` = `s`.`id`
            AND
                `s`.`store_code` = `t`.`store_code`
            AND
                `t`.`id` = '$id'
        ");

        return $query->result_array();
    }

    public function get_store()
    {
        $this -> db -> select('id, store_name, store_address');
        $this -> db -> from('stores');
        $this -> db -> where('id = ' . "'" . $this->_user_group . "'");
        $query = $this -> db -> get();

        return $query->result_array();
    }


    public function floors_tenantStoreLocation($id)
    {
        $query = $this->db->query(
            "SELECT
                f.floor_name
            FROM
                floors f,
                stores s,
                tenants t
            WHERE
                t.id = '$id'
            AND
                s.store_code = t.store_code
            AND
                s.id = f.store_id
        ");

        return $query->result_array();
    }


    public function store_floors()
    {
        $query = $this->db->query(
            "SELECT
                `f`.`floor_name`,
                `f`.`id`
            FROM
                `stores` `s`,
                `floors` `f`
            WHERE
                `f`.`store_id` = `s`.`id`
            AND
                `s`.`id` = '$this->_user_group'"
        );

        return $query->result_array();
    }


    public function get_floors()
    {
        $query = $this->db->get('floors');
        return $query->result_array();
    }


    public function get_floorplan()
    {
        $query;
        if ($this->session->userdata('user_type') == 'Administrator') {
            $query = $this->db->query("SELECT `f`.`id`, `s`.`store_name`, `s`.`company_code`, `f`.`floor_name`, `f`.`model` FROM `stores` `s`, `floors` `f` WHERE `s`.`id` = `f`.`store_id`");
        }
        else
        {
            $query = $this->db->query("SELECT `f`.`id`, `s`.`store_name`, `s`.`company_code`, `f`.`floor_name`, `f`.`model` FROM `stores` `s`, `floors` `f` WHERE `s`.`id` = `f`.`store_id` AND `s`.`id` = '" . $this->_user_group . "'");
        }

        return $query->result_array();
    }


    public function add_floor($store_id, $floor_name)
    {
         $data = array(
           'store_id'     => $store_id,
           'floor_name'   => $floor_name
        );

        $this->db->insert('floors', $data);
    }


    public function add_selectedBank($store_id, $bank_name)
    {


        $check = $this->db->query(
            "SELECT
                `accre`.`id`
            FROM
                `accredited_banks` `accre`,
                `stores` `s`,
                `selected_banks` `selected`
            WHERE
                `accre`.`bank_name` = '$bank_name'
            AND
                `s`.`id` = '$store_id'
            AND
                `s`.`id` = `selected`.`store_id`
            AND
                `accre`.`id` = `selected`.`bank_id`
        ");


        if ($check->num_rows() > 0)
        {
            return false;
        }
        else
        {
            $bank_id = $this->db->query("SELECT id FROM accredited_banks WHERE bank_name = '$bank_name' LIMIT 1 ")->row()->id;

            $selected = array(
               'bank_id'     =>  $bank_id ,
               'store_id'    =>  $store_id
            );

            $this->insert('selected_banks', $selected);
        }

        return true;
    }

    public function get_storedata($store_id)
    {
        $this -> db -> select('*');
        $this -> db -> from('stores');
        $this -> db -> where('id = ' . "'" . $store_id . "'");
        $query = $this -> db -> get();

        return $query->result_array();
    }


    public function get_myfloor($store_id)
    {
        $this -> db -> select('*');
        $this -> db -> from('floors');
        $this -> db -> where('store_id = ' . "'" . $store_id . "'");
        $query = $this -> db -> get();

        return $query->result_array();
    }


    public function get_myBanks($store_id)
    {
        $query = $this->db->query(
            "SELECT
                `selected`.`id`,
                `accre`.`bank_name`
            FROM
                `accredited_banks` `accre`,
                `selected_banks` `selected`,
                `stores` `s`
            WHERE
                `s`.`id` = `selected`.`store_id`
            AND
                `selected`.`bank_id` = `accre`.`id`
            AND
                `s`.`id` = '$store_id'
        ");

        return $query->result_array();
    }

    public function update($data, $id, $tbl_name)
    {
        $this->db->where('id', $id);
        $this->db->update($tbl_name, $data);

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function update_where($data, $where, $value, $tbl_name)
    {
        $this->db->where($where, $value);
        $this->db->update($tbl_name, $data);

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function updateTenant($tenant_id, $data)
    {
        $this->db->where('tenant_id', $tenant_id);
        $this->db->where('status', 'Active');
        $this->db->update('tenants', $data);

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function update_tenant($data, $tenant_id)
    {
        $this->db->where('tenant_id', $tenant_id);
        $this->db->update('ltenant', $data);

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }



    public function update_status($data, $id, $tbl_name)
    {
        $this->db->where('id', $id);
        $this->db->update($tbl_name, $data);

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function disable_locationCode($id)
    {
        $this->db->query("UPDATE `location_code` `lc`, `tenants` `t` SET `lc`.`status` = 'Disabled' WHERE `t`.`locationCode_id` = `lc`.`id` AND `t`.`id` = '$id' AND `lc`.`status` = 'Active'");
    }


    public function check_expiredTenants()
    {
        $query = $this->db->query("SELECT `id` FROM `tenants` WHERE date_add(`expiry_date`, INTERVAL 1 DAY) <= NOW() AND `status` = 'Active'");
        return $query->result_array();
    }



    public function get_vat()
    {
        $vat = $this->db->query("SELECT vat FROM vat LIMIT 1")->row()->vat;
        return $vat;
    }

    public function get_wht()
    {
        return $this->db->query("SELECT `withholding` FROM `withholding_tax` LIMIT 1")->row()->withholding;
    }

    public function update_floors($floor_name, $floor_id)
    {
        for ($i=0; $i < count($floor_name) ; $i++)
        {
            $this->db->query("UPDATE floors SET floor_name = '" . $this->sanitize($floor_name[$i]) . "' WHERE id = '" . $floor_id[$i] . "'");
        }
    }




    public function delete($tbl_name, $where, $value)
    {
        $this->db->where($where, $value);
        $this->db->delete($tbl_name);
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function get_tenancyFromProspect($id)
    {
        $tenancy_type = $this->db->query(
            "SELECT
                `flag`
            FROM
                `prospect`
            WHERE
                `id` = '$id'
            LIMIT 1
            ")->row()->flag;

        return $tenancy_type;
    }
    public function get_tenancy_type($tenant_id)
    {
        $tenancy_type = $this->db->query(
            "SELECT
                `tenancy_type`
            FROM
                `tenants`
            WHERE
                `id` = '$tenant_id'
            LIMIT 1
            ")->row()->tenancy_type;

        return $tenancy_type;
    }

    public function getAll($tbl_name)
    {
        $query = $this->db->get($tbl_name);
        return $query->result_array();
    }

    public function insert($tbl_name, $data)
    {
        $this->db->insert($tbl_name, $data);
    }

    public function selectWhere($tbl_name, $id)
    {
        $this -> db -> select('*');
        $this -> db -> from($tbl_name);
        $this -> db -> where('id = ' . "'" . $id . "'");
        $query = $this -> db -> get();

        return $query->result_array();
    }


    public function get_locationSlots($floor_id, $idArray)
    {
        $query = $this->db->query(
            "SELECT
                `id`,
                `slot_no`,
                `floor_area`,
                `rental_rate`
            FROM
                `location_slot`
            WHERE
                `floor_id` = '$floor_id'
            AND
                `id` NOT IN (' " . implode($idArray, "','") . "')
        ");

        return $query->result_array();
    }


    public function get_availableArea()
    {

        $occupiedID = array();
        $occupiedArea = $this->get_occupiedArea();

        foreach ($occupiedArea as $value)
        {
            $id = explode(",", $value['slots_id']);

            for ($i=0; $i < count($id); $i++)
            {
                array_push($occupiedID, $id[$i]);
            }
        }

        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
        {

            $query = $this->db->query(
                "SELECT
                    `ls`.`id`,
                    `ls`.`slot_no`,
                    `ls`.`tenancy_type`,
                    `f`.`floor_name`,
                    `s`.`store_name`,
                    `ls`.`floor_area`,
                    `ls`.`rental_rate`
                FROM
                    `location_slot` `ls`,
                    `floors` `f`,
                    `stores` `s`
                WHERE
                    `ls`.`id` NOT IN (' " . implode($occupiedID, "','") . "')
                AND
                    `ls`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
            ");

            return $query->result_array();

        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `ls`.`id`,
                    `ls`.`slot_no`,
                    `ls`.`floor_area`,
                    `ls`.`tenancy_type`,
                    `f`.`floor_name`,
                    `s`.`store_name`,
                    `ls`.`rental_rate`
                FROM
                    `location_slot` `ls`,
                    `floors` `f`,
                    `stores` `s`
                WHERE
                    `ls`.`id` NOT IN (' " . implode($occupiedID, "','") . "')
                AND
                    `ls`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `s`.`id` = '" . $this->_user_group . "'
            ");

            return $query->result_array();
        }
    }


    public function get_occupiedArea()
    {
        $query = $this->db->query("SELECT `slots_id` FROM `location_code` WHERE `status` = 'Active'");
        return $query->result_array();
    }




    public function get_occupiedSlots($floor_id)
    {
        $query = $this->db->query("SELECT `slots_id` FROM `location_code` WHERE `floor_id` = '$floor_id' AND `status` = 'Active'");
        return $query->result_array();
    }


    public function get_floorLocation($prospect_id)
    {
        $query = $this->db->query(
            "SELECT
                `f`.`id`,
                `f`.`floor_name`
            FROM
                `floors` `f`,
                `stores` `s`,
                `prospect` `p`
            WHERE
                `p`.`store_id` = `f`.`store_id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `p`.`id` = '$prospect_id'
        ");
        return $query->result_array();
    }

    public function get_rentPeriod($tenancy_type)
    {
        $this -> db -> select('*');
        $this -> db -> from('rent_period');
        $this -> db -> where('tenancy_type = ' . "'" . $tenancy_type . "'");
        $this -> db -> where('status', 'Active');
        $this -> db -> order_by('number', 'ASC');
        $query = $this -> db -> get();

        return $query->result_array();
    }

    public function get_img($tbl_name, $condition, $value)
    {
        $this -> db -> select('*');
        $this -> db -> from($tbl_name);
        $this->db->where($condition, $value);
        $query = $this -> db -> get();

        return $query->result_array();
    }


    public function get_contractDocs($id)
    {
        $query = $this->db->query(
            "SELECT
                `file_name`
            FROM
                `contract_docs`
            WHERE
                `tenant_id` = '$id'
            ORDER BY `id` DESC
        ");

        return $query->result_array();
    }

    
    public function get_suppDocs($id)
    {
        $query = $this->db->query(
            "SELECT
                `file_name`
            FROM
                `tenanttype_supportingdocs`
            WHERE
                `tenant_id` = '$id'
        ");

        return $query->result_array();
    }


    public function get_exhibitor_attachements($condition, $value, $flag)
    {
        $this -> db -> select('*');
        $this -> db -> from('exhibitor_attachements');
        $this->db->where($condition, $value);
        $this->db->where('flag', $flag);
        $query = $this -> db -> get();

        return $query->result_array();
    }

    public function get_category_two()
    {
        $query = $this->db->query(
            "SELECT
                `two`.`id`,
                `one`.`category_name` as `first_level`,
                `two`.`category_name` as `second_level`,
                `two`.`description`
            FROM
                `category_one` `one`,
                `category_two` `two`
            WHERE
                `two`.`categoryOne_id` = `one`.`id`
            ORDER BY
                `first_level`
            ASC
        ");
        return $query->result_array();
    }


    public function get_categoryTwo_data($id)
    {
        $query = $this->db->query(
            "SELECT
                `two`.`id`,
                `one`.`category_name` as `first_level`,
                `two`.`category_name` as `second_level`,
                `two`.`description`
            FROM
                `category_one` `one`,
                `category_two` `two`
            WHERE
                `two`.`categoryOne_id` = `one`.`id`
            AND
                `two`.`id` = '$id'
            ORDER BY
                `first_level`
            ASC
        ");
        return $query->result_array();
    }


    public function get_category_three()
    {
        $query = $this->db->query(
            "SELECT
                `three`.`id`,
                `one`.`category_name` as `first_level`,
                `two`.`category_name` as `second_level`,
                `three`.`category_name` as `third_level`,
                `three`.`description`
            FROM
                `category_one` `one`,
                `category_two` `two`,
                `category_three` `three`
            WHERE
                `three`.`categoryTwo_id` = `two`.`id`
            AND
                `two`.`categoryOne_id` = `one`.`id`
            ORDER BY
                `first_level`
            ASC
        ");

        return $query->result_array();
    }


    public function populate_categoryTwo($data)
    {
        $query = $this->db->query(
            "SELECT
                `two`.`category_name` as `second_level`
            FROM
                `category_one` `one`,
                `category_two` `two`
            WHERE
                `one`.`id` = `two`.`categoryOne_id`
            AND
                `one`.`category_name` = '$data'
            ORDER BY
                `second_level`
            ASC
        ");
        return $query->result_array();
    }


    public function populate_categoryThree($data)
    {
        $query = $this->db->query(
            "SELECT
                `three`.`category_name` as `third_level`
            FROM
                `category_three` `three`,
                `category_two` `two`
            WHERE
                `two`.`id` = `three`.`categoryTwo_id`
            AND
                `two`.`category_name` = '$data'
            ORDER BY
                `third_level`
            ASC
        ");
        return $query->result_array();
    }


    public function get_categoryThree_data($id)
    {
        $query = $this->db->query(
            "SELECT
                `three`.`id`,
                `one`.`category_name` as `first_level`,
                `two`.`category_name` as `second_level`,
                `three`.`category_name` as `third_level`,
                `three`.`description`
            FROM
                `category_one` `one`,
                `category_two` `two`,
                `category_three` `three`
            WHERE
                `three`.`categoryTwo_id` = `two`.`id`
            AND
                `two`.`categoryOne_id` = `one`.`id`
            AND
                `three`.`id` = '$id'
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function get_price_floor()
    {
        $query = $this->db->query(
            "SELECT
                `price`.`id`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `price`.`price`
            FROM
                `price_floor` `price`,
                `floors` `f`,
                `stores` `s`
            WHERE
                `f`.`store_id` = `s`.`id`
            AND
                `price`.`floor_id` = `f`.`id`
            ORDER BY
                `s`.`store_name`
            ASC
        ");
        return $query->result_array();
    }


    public function store_price_floor()
    {
        $query = $this->db->query(
            "SELECT
                `price`.`id`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `price`.`price`
            FROM
                `price_floor` `price`,
                `floors` `f`,
                `stores` `s`
            WHERE
                `s`.`id` = '$this->_user_group'
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `price`.`floor_id` = `f`.`id`
            ORDER BY
                `s`.`store_name`
            ASC
        ");
        return $query->result_array();
    }


    public function floor_for_pricing($store_name)
    {
        $query = $this->db->query(
            "SELECT
                `floors`.`floor_name`
            FROM
                `stores`
            INNER JOIN
                `floors`
            ON
                `floors`.`store_id` = `stores`.`id`
            LEFT JOIN
                `price_floor`
            ON
                `floors`.`id` = `price_floor`.`floor_id`
            WHERE
                `stores`.`store_name` = '$store_name'
            AND
                `price_floor`.`price` IS NULL
        ");

        return $query->result_array();
    }

    public function floor_no_pricing()
    {
        $query = $this->db->query(
            "SELECT
                `floors`.`floor_name`
            FROM
                `stores`
            INNER JOIN
                `floors`
            ON
                `floors`.`store_id` = `stores`.`id`
            LEFT JOIN
                `price_floor`
            ON
                `floors`.`id` = `price_floor`.`floor_id`
            WHERE
                `stores`.`id` = '$this->_user_group'
            AND
                `price_floor`.`price` IS NULL
        ");

        return $query->result_array();
    }

    public function store_floor_price()
    {
        $query = $this->db->query(
            "SELECT
                `floors`.`floor_name`
            FROM
                `stores`
            INNER JOIN
                `floors`
            ON
                `floors`.`store_id` = `stores`.`id`
            LEFT JOIN
                `price_floor`
            ON
                `floors`.`id` = `price_floor`.`floor_id`
            WHERE
                `stores`.`id` = '$this->_user_group'
            AND
                `price_floor`.`price` IS NOT NULL
        ");

        return $query->result_array();
    }


    public function floor_with_price($store_name)
    {
        $query = $this->db->query(
            "SELECT
                `floors`.`floor_name`
            FROM
                `stores`
            INNER JOIN
                `floors`
            ON
                `floors`.`store_id` = `stores`.`id`
            LEFT JOIN
                `price_floor`
            ON
                `floors`.`id` = `price_floor`.`floor_id`
            WHERE
                `stores`.`store_name` = '$store_name'
            AND
                `price_floor`.`price` IS NOT NULL
            ORDER BY
                `floor_name`
            ASC
        ");

        return $query->result_array();
    }


    public function populate_locationCode($store_name, $floor_name)
    {
        $query = $this->db->query(
            "SELECT
                `pl`.`location_code`
            FROM
                `stores` `s`,
                `floors` `f`,
                `price_locationCode` `pl`,
                `price_floor` `pf`
            WHERE
                `s`.`id` = `f`.`store_id`
            AND
                `pf`.`floor_id` = `f`.`id`
            AND
                `pf`.`id` = `pl`.`price_floor_id`
            AND
                `s`.`store_name` = '$store_name'
            AND
                `f`.`floor_name` = '$floor_name'
            AND
                `pl`.`id` NOT IN
                    (
                        SELECT
                            `p`.`locationCode_id`
                        FROM
                            `lprospect` `p`,
                            `ltenant` `t`
                        WHERE
                            `p`.`id` = `t`.`prospect_id`
                        AND
                            `t`.`flag` = 'Long Term'
                        AND
                            `t`.`status` = 'Active'
                    )
            ORDER BY
                `location_code`
            ASC
        ");

        return $query->result_array();
    }


    public function populate_price($code)
    {
        $query = $this->db->query(
            "SELECT
                `pf`.`price`,
                `lc`.`floor_area`,
                round((`lc`.`floor_area`) * (`pf`.`price`), 2) AS `basic_rental`
            FROM
                `price_floor` `pf`,
                `price_locationCode` `lc`
            WHERE
                `pf`.`id` = `lc`.`price_floor_id`
            AND
                `lc`.`location_code` = '$code'
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function get_priceFloor_data($id)
    {
        $query = $this->db->query(
            "SELECT
                `price`.`id`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `price`.`price`
            FROM
                `price_floor` `price`,
                `floors` `f`,
                `stores` `s`
            WHERE
                `f`.`store_id` = `s`.`id`
            AND
                `price`.`floor_id` = `f`.`id`
            AND
                `price`.`id` = '$id'
        ");

        return $query->result_array();
    }


    public function get_price_locationCode()
    {
        $query = $this->db->query(
            "SELECT
                `lc`.`id`,
                `s`.`store_name`,
                `lc`.`location_code`,
                `f`.`floor_name`,
                `lc`.`floor_area`,
                `price`.`price`,
                (`lc`.`floor_area`) * (`price`.`price`) AS `basic_rental`,
                `lc`.`status`
            FROM
                `price_locationCode` `lc`,
                `stores` `s`,
                `floors` `f`,
                `price_floor` `price`
            WHERE
                `f`.`store_id` = `s`.`id`
            AND
                `lc`.`price_floor_id` = `price`.`id`
            AND
                `price`.`floor_id` =`f`.`id`
            ORDER BY
                `s`.`store_name`
            ASC
        ");

        return $query->result_array();
    }

    public function check_vacant()
    {
        $this->db->query(
            "UPDATE
                `price_locationcode`  `pl` ,
                `lprospect`  `p` ,
                `ltenant`  `t`
            SET
                `pl`.`status` =  'Occupied'
            WHERE
                `p`.`locationCode_id` =  `pl`.`id`
            AND
                `p`.`id` =  `t`.`prospect_id`
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`flag` = 'Long Term'
        ");

        $this->db->query(
            "UPDATE
                `price_locationcode`  `pl` ,
                `lprospect`  `p` ,
                `ltenant`  `t`
            SET
                `pl`.`status` =  'Vacant'
            WHERE
                `p`.`locationCode_id` =  `pl`.`id`
            AND
                `p`.`id` =  `t`.`prospect_id`
            AND
                `t`.`status` != 'Active'
            AND
                `t`.`flag` = 'Long Term'
        ");
    }

    public function scheck_vacant()
    {
        $this->db->query(
            "UPDATE
                `exhibit_rates`  `ex` ,
                `sprospect`  `p` ,
                `ltenant`  `t`
            SET
                `ex`.`status` =  'Occupied'
            WHERE
                `ex`.`id` =  `p`.`exhibitrate_id`
            AND
                `p`.`id` =  `t`.`prospect_id`
            AND
                `t`.`flag` = 'Short Term'
            AND
                `t`.`status` =  'Active'
            AND
                `t`.`flag` = 'Short Term'
        ");


        $this->db->query(
            "UPDATE
                `exhibit_rates`  `ex` ,
                `sprospect`  `p` ,
                `ltenant`  `t`
            SET
                `ex`.`status` =  'Vacant'
            WHERE
                `ex`.`id` =  `p`.`exhibitrate_id`
            AND
                `p`.`id` =  `t`.`prospect_id`
            AND
                `t`.`flag` = 'Short Term'
            AND
                `t`.`status` !=  'Active'
            AND
                `t`.`flag` = 'Short Term'
        ");
    }

    public function store_price_locationCode()
    {
        $query = $this->db->query(
            "SELECT
                `lc`.`id`,
                `s`.`store_name`,
                `lc`.`location_code`,
                `f`.`floor_name`,
                `lc`.`floor_area`,
                `price`.`price`,
                (`lc`.`floor_area`) * (`price`.`price`) AS `basic_rental`,
                `lc`.`status`
            FROM
                `price_locationCode` `lc`,
                `stores` `s`,
                `floors` `f`,
                `price_floor` `price`
            WHERE
                `f`.`store_id` = `s`.`id`
            AND
                `s`.`id` = '$this->_user_group'
            AND
                `lc`.`price_floor_id` = `price`.`id`
            AND
                `price`.`floor_id` =`f`.`id`
            ORDER BY
                `lc`.`location_code`
            ASC
        ");

        return $query->result_array();
    }


    public function get_floor_price($store_name)
    {
        $query = $this->db->query(
            "SELECT
                `f`.`floor_name`
            FROM
                `floors` `f`,
                `stores` `s`,
                price_floor price
            WHERE
                `s`.`store_name` = '$store_name'
            AND
                `s`.`id` = `f`.`store_id`
            AND
                `price`.`floor_id` = `f`.`id`
            ORDER BY
                `floor_name`
            ASC
        ");
        return $query->result_array();
    }


    public function get_floorID($store_name, $floor_name)
    {
        $query = $this->db->query(
            "SELECT
                `f`.`id`
            FROM
                `floors` `f`,
                `stores` `s`
            WHERE
                `s`.`store_name` = '$store_name'
            AND
                `f`.`floor_name` = '$floor_name'
            AND
                `f`.`store_id` = `s`.`id`
        ")->row()->id;

        return $query;
    }

    public function get_floorPrice($store_name, $floor_name)
    {
        $query = $this->db->query(
            "SELECT
                `price`.`price`
            FROM
                `price_floor` `price`,
                `stores` `s`,
                `floors` `f`
            WHERE
                `s`.`store_name` = '$store_name'
            AND
                `f`.`floor_name` = '$floor_name'
            AND
                `f`.`id` = `price`.`floor_id`
            AND
                `f`.`store_id` = `s`.`id`
            LIMIT 1
        ")->row()->price;

        return $query;
    }





    public function get_exhibit_rates()
    {
        $query = $this->db->query(
            "SELECT
                `ex`.`id`,
                `ex`.`location_code`,
                `ex`.`category`,
                `s`.`store_name`,
                `s`.`store_code`,
                `f`.`floor_name`,
                `pf`.`price`,
                `ex`.`floor_area`,
                round(((`ex`.floor_area) * (`pf`.price)) / 30, 2) as `rate_per_day`,
                round((((`ex`.floor_area) * (`pf`.price)) / 30) * 7, 2) as `rate_per_week`,
                round((`ex`.floor_area) * (`pf`.price), 2) as `rate_per_month`,
                `ex`.`status`
            FROM
                `exhibit_rates` `ex`,
                `stores` `s`,
                `floors` `f`,
                `price_floor` `pf`
            WHERE
                `s`.`id` = `f`.`store_id`
            AND
                `f`.`id` = `pf`.`floor_id`
            AND
                `pf`.`id` = `ex`.`price_floor_id`
            ORDER BY
                `location_code`
            ASC
        ");

        return $query->result_array();
    }


    public function store_exhibit_rates()
    {
        $query = $this->db->query(
            "SELECT
                `ex`.`id`,
                `ex`.`location_code`,
                `ex`.`category`,
                `s`.`store_name`,
                `s`.`store_code`,
                `f`.`floor_name`,
                `pf`.`price`,
                `ex`.`floor_area`,
                round(((`ex`.floor_area) * (`pf`.price)) / 30, 2) as `rate_per_day`,
                round((((`ex`.floor_area) * (`pf`.price)) / 30) * 7, 2) as `rate_per_week`,
                round((`ex`.floor_area) * (`pf`.price), 2) as `rate_per_month`,
                `ex`.`status`
            FROM
                `exhibit_rates` `ex`,
                `stores` `s`,
                `floors` `f`,
                `price_floor` `pf`
            WHERE
                `s`.`id` = `f`.`store_id`
            AND
                `f`.`id` = `pf`.`floor_id`
            AND
                `pf`.`id` = `ex`.`price_floor_id`
            AND
                `s`.`id` = '$this->_user_group'
            ORDER BY
                `location_code`
            ASC
        ");

        return $query->result_array();
    }


    public function get_exhibitRate_data($id)
    {
        $query = $this->db->query(
            "SELECT
                `ex`.`id`,
                `ex`.`location_code`,
                `ex`.`category`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `pf`.`price`,
                `ex`.`floor_area`,
                round(((`ex`.floor_area) * (`pf`.price)) / 30, 2) as `rate_per_day`,
                round((((`ex`.floor_area) * (`pf`.price)) / 30) * 7, 2) as `rate_per_week`,
                round((`ex`.floor_area) * (`pf`.price), 2) as `rate_per_month`
            FROM
                `exhibit_rates` `ex`,
                `stores` `s`,
                `floors` `f`,
                `price_floor` `pf`
            WHERE
                `s`.`id` = `f`.`store_id`
            AND
                `f`.`id` = `pf`.`floor_id`
            AND
                `pf`.`id` = `ex`.`price_floor_id`
            AND
                `ex`.`id` = '$id'
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function get_leasing_users()
    {
        $query = $this->db->query(
            "SELECT
                `leasing_users`.`id`,
                `leasing_users`.`first_name`,
                `leasing_users`.`middle_name`,
                `leasing_users`.`last_name`,
                `leasing_users`.`username`,
                `leasing_users`.`password`,
                `leasing_users`.`user_type`,
                `leasing_users`.`status`,
                `stores`.`store_name`
            FROM
                `leasing_users`
            LEFT JOIN
                `stores`
            ON
                `leasing_users`.`user_group` = `stores`.`id`
            ORDER BY
                `first_name`
        ");

        return $query->result_array();
    }


    public function store_id($store_name)
    {
        $query = $this->db->query("SELECT `id` FROM `stores` WHERE `store_name` = '$store_name' LIMIT 1")->row()->id;
        return $query;
    }

    public function select_id($tbl_name, $where, $equal)
    {
        $id = $this->db->query("SELECT `id` FROM `$tbl_name` WHERE `$where` = '$equal' LIMIT 1")->row()->id;
        return $id;
    }

    public function select_storeId($tbl_name, $where, $equal)
    {
        $id = $this->db->query("SELECT `store_id` FROM `$tbl_name` WHERE `$where` = '$equal' LIMIT 1")->row()->store_id;
        return $id;
    }

    public function my_store() // STORE WHERE NON-ADMINSTRATOR USER BELONGS
    {
        $query = $this->db->query("SELECT store_name FROM stores WHERE id = '$this->_user_group' LIMIT 1")->row()->store_name;
        return $query;
    }


    public function tenant_store($id)
    {
        $query = $this->db->query("SELECT s.store_name FROM stores s, tenants t WHERE t.id = '$id' AND t.store_code = s.store_code LIMIT 1")->row()->store_name;
        return $query;
    }

    public function company_code() // STORE WHERE NON-ADMINSTRATOR USER BELONGS
    {
        $query = $this->db->query("SELECT company_code FROM stores WHERE id = '" . $this->_user_group . "' LIMIT 1")->row()->company_code;
        return $query;

    }



    public function get_leasingUser_data($id)
    {
        $query = $this->db->query(
            "SELECT
                `leasing_users`.`id`,
                `leasing_users`.`first_name`,
                `leasing_users`.`middle_name`,
                `leasing_users`.`last_name`,
                `leasing_users`.`username`,
                `leasing_users`.`password`,
                `leasing_users`.`user_type`,
                `leasing_users`.`status`,
                `stores`.`store_name`
            FROM
                `leasing_users`
            LEFT JOIN
                `stores`
            ON
                `leasing_users`.`user_group` = `stores`.`id`
            WHERE
                `leasing_users`.`id` = '$id'
        ");

        return $query->result_array();
    }

    public function prospect_id($trade_name, $store_id, $request_date)
    {
        $id = $this->db->query(
                "SELECT
                    `id`
                FROM
                    `prospect`
                WHERE
                    `trade_name` = '$trade_name'
                AND
                    `store_id` = '$store_id'
                AND
                    `request_date` = '$request_date'
                LIMIT 1
            ")->row()->id;

        return $id;
    }


    public function sprospect_id($trade_name, $exhibitrate_id, $request_date)
    {
        $id = $this->db->query(
                "SELECT
                    `id`
                FROM
                    `sprospect`
                WHERE
                    `trade_name` = '$trade_name'
                AND
                    `exhibitrate_id` = '$exhibitrate_id'
                AND
                    `request_date` = '$request_date'
                LIMIT 1
            ")->row()->id;

        return $id;
    }


// ======================================== ORIGINAL
    // public function get_lprospect()
    // {
    //     $query = $this->db->query(
    //         "SELECT
    //             `p`.`id`,
    //             `p`.`trade_name`,
    //             `p`.`corporate_name`,
    //             `p`.`address`,
    //             `p`.`first_category`,
    //             `p`.`second_category`,
    //             `p`.`third_category`,
    //             `p`.`contact_person`,
    //             `p`.`contact_number`,
    //             `p`.`request_date`,
    //             `s`.`store_name`,
    //             `p`.`status`,
    //             CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
    //         FROM
    //             `prospect` `p`,
    //             `leasee_type` `lt`,
    //             `stores` `s`,
    //             `leasing_users` `u`
    //         WHERE
    //            `s`.`id` = `p`.`store_id`
    //         AND
    //             `p`.`prepared_by` = `u`.`id`
    //         AND
    //             (`p`.`status` != 'On Contract' AND `p`.`status` != 'Denied' AND `p`.`status` != 'Deleted')
    //         AND
    //             `p`.`flag` = 'Long Term'
    //         GROUP BY
    //             `p`.`id`
    //     ");

    //     return $query->result_array();
    // }

    // public function get_deniedLprospect()
    // {
    //     $query = $this->db->query(
    //         "SELECT
    //             `p`.`id`,
    //             `p`.`trade_name`,
    //             `p`.`corporate_name`,
    //             `p`.`request_date`,
    //             `p`.`address`,
    //             `p`.`first_category`,
    //             `p`.`second_category`,
    //             `p`.`third_category`,
    //             `p`.`contact_person`,
    //             `p`.`contact_number`,
    //             `p`.`approved_date`,
    //             `lt`.`leasee_type`,
    //             `s`.`store_name`,
    //             `p`.`status`,
    //             CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
    //         FROM
    //             `prospect` `p`,
    //             `leasee_type` `lt`,
    //             `stores` `s`,
    //             `leasing_users` `u`
    //         WHERE
    //             `p`.`lesseeType_id` = `lt`.`id`
    //         AND
    //             `p`.`prepared_by` = `u`.`id`
    //         AND
    //             `p`.`status` =  'Denied'
    //         AND
    //             `s`.`id` = `p`.`store_id`
    //         AND
    //             `p`.`flag` = 'Long Term'
    //     ");

    //     return $query->result_array();
    // }



    public function store_deniedLprospect()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`request_date`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`approved_date`,
                `lt`.`leasee_type`,
                `s`.`store_name`,
                `p`.`status`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `leasing_users` `u`
            WHERE
                `p`.`lesseeType_id` = `lt`.`id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                `p`.`status` =  'Denied'
            AND
                `s`.`id` = `p`.`store_id`
            AND
                `p`.`flag` = 'Long Term'
            AND
                `s`.`id` = '" . $this->_user_group . "'
        ");


        return $query->result_array();
    }


    public function store_lprospect()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`request_date`,
                `lt`.`leasee_type`,
                `s`.`store_name`,
                `p`.`status`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `leasing_users` `u`
            WHERE
               `s`.`id` = `p`.`store_id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                (`p`.`status` != 'On Contract' AND `p`.`status` != 'Denied' AND `p`.`status` != 'Deleted')
            AND
                `p`.`flag` = 'Long Term'
            AND
                `s`.`id` = '" . $this->_user_group . "'
            GROUP BY
                `p`.`id`
        ");

        return $query->result_array();
    }

    public function get_ltforreview()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`request_date`,
                `lt`.`leasee_type`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `lc`.`location_code`,
                `p`.`status`,
                `lc`.`floor_area`,
                `lc`.`area_classification`,
                `lc`.`area_type`,
                `lc`.`rent_period`,
                `lc`.`payment_mode`,
                `lc`.`rental_rate`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `floors` `f`,
                `location_code` `lc`,
                `leasing_users` `u`
            WHERE
                `p`.`locationCode_id` = `lc`.`id`
            AND
                `lc`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `p`.`lesseeType_id` = `lt`.`id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                `p`.`status` = 'For Review'
            AND
                `lc`.`tenancy_type` = 'Long Term'
        ");

        return $query->result_array();
    }


    public function get_stforreview()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`request_date`,
                `lt`.`leasee_type`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `lc`.`location_code`,
                `p`.`status`,
                `lc`.`floor_area`,
                `lc`.`area_classification`,
                `lc`.`area_type`,
                `lc`.`rent_period`,
                `lc`.`payment_mode`,
                `lc`.`rental_rate`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `floors` `f`,
                `location_code` `lc`,
                `leasing_users` `u`
            WHERE
                `p`.`locationCode_id` = `lc`.`id`
            AND
                `lc`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `p`.`lesseeType_id` = `lt`.`id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                `p`.`status` = 'For Review'
            AND
                `lc`.`tenancy_type` = 'Short Term'
        ");


        return $query->result_array();
    }





    public function view_prospect($id)
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `lt`.`leasee_type`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`request_date`,
                `p`.`approved_date`,
                `p`.`flag`,
                `p`.`remarks`,
                `s`.`store_name`,
                `p`.`status`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `stores` `s`,
                `leasee_type` `lt`,
                `leasing_users` `u`
            WHERE
                `p`.`store_id` = `s`.`id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                `lt`.`id` = `p`.`lesseetype_id`
            AND
                `p`.`id` = '$id'
            LIMIT 1
        ");



        return $query->result_array();
    }


    public function unlink_file($file_path, $file)
    {
        $file_name = end((explode('/', $file)));
        unlink($file_path . $file_name);
    }



    public function get_anotherStores($id)
    {
        $query = $this->db->query(
            "SELECT
                `s`.`store_name`
            FROM
                `stores` `s`,
                `location_code` `lc`,
                `prospect` `p`,
                `floors` `f`

            WHERE
                `p`.`locationCode_id` = `lc`.`id`
            AND
                `lc`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` != `s`.`id`
            AND
                `p`.`id` = '$id'
        ");

        return $query->result_array();
    }


    public function get_approveLprospect()
    {

        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Documentation Officer' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor')
        {
            $query = $this->db->query(
                "SELECT
                    `p`.`id`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `p`.`third_category`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`approved_date`,
                    `lt`.`leasee_type`,
                    `s`.`store_name`,
                    `p`.`status`,
                    CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
                FROM
                    `prospect` `p`,
                    `leasee_type` `lt`,
                    `stores` `s`,
                    `leasing_users` `u`
                WHERE
                    `p`.`lesseeType_id` = `lt`.`id`
                AND
                    `p`.`prepared_by` = `u`.`id`
                AND
                    `p`.`status` =  'Approved'
                AND
                    `s`.`id` = `p`.`store_id`
                AND
                    `p`.`flag` = 'Long Term'
            ");

        } else {
            $query = $this->db->query(
                "SELECT
                    `p`.`id`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `p`.`third_category`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`approved_date`,
                    `lt`.`leasee_type`,
                    `s`.`store_name`,
                    `p`.`status`,
                    CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
                FROM
                    `prospect` `p`,
                    `leasee_type` `lt`,
                    `stores` `s`,
                    `leasing_users` `u`
                WHERE
                    `p`.`lesseeType_id` = `lt`.`id`
                AND
                    `p`.`prepared_by` = `u`.`id`
                AND
                    `p`.`status` =  'Approved'
                AND
                    `s`.`id` = `p`.`store_id`
                AND
                    `p`.`flag` = 'Long Term'
                AND

                    `s`.`id` = '" . $this->_user_group . "'
            ");
        }


        return $query->result_array();
    }


    public function exhibit_code($store_name, $floor_name)
    {
        $query = $this->db->query(
            "SELECT
                `ex`.`location_code`
            FROM
                `exhibit_rates` `ex`,
                `stores` `s`,
                `floors` `f`,
                `price_floor` `pf`
            WHERE
                `s`.`id` = `f`.`store_id`
            AND
                `f`.`id` = `pf`.`floor_id`
            AND
                `pf`.`id` = `ex`.`price_floor_id`
            AND
                s.store_name = '$store_name'
            AND
                f.floor_name = '$floor_name'
            AND
                `ex`.`id` NOT IN
                (
                    SELECT
                        `p`.`exhibitrate_id`
                    FROM
                        `sprospect` `p`,
                        `ltenant` `t`
                    WHERE
                        `p`.`id` = `t`.`prospect_id`
                    AND
                        `t`.`flag` = 'Short Term'
                    AND
                        `t`.`status` = 'Active'
                )
        ");

        return $query->result_array();

    }


    public function exhibit_credentials($location_code)
    {
        $query = $this->db->query(
            "SELECT
                `ex`.`id`,
                `ex`.`category`,
                `pf`.`price`,
                `ex`.`floor_area`,
                round(((`ex`.floor_area) * (`pf`.price)) / 30, 2) as `rate_per_day`,
                round((((`ex`.floor_area) * (`pf`.price)) / 30) * 7, 2) as `rate_per_week`,
                round((`ex`.floor_area) * (`pf`.price), 2) as `rate_per_month`
            FROM
                `exhibit_rates` `ex`,
                `stores` `s`,
                `floors` `f`,
                `price_floor` `pf`
            WHERE
                `s`.`id` = `f`.`store_id`
            AND
                `f`.`id` = `pf`.`floor_id`
            AND
                `pf`.`id` = `ex`.`price_floor_id`
            AND
                `ex`.`location_code`= '$location_code'
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function floor_exhibit_price($store_name)
    {
        $query = $this->db->query(
            "SELECT
                `floors`.`floor_name`
            FROM
                `stores`
            INNER JOIN
                `floors`
            ON
                `floors`.`store_id` = `stores`.`id`
            INNER JOIN
                `price_floor`
            ON
                `floors`.`id` = `price_floor`.`floor_id`
            LEFT JOIN
                `exhibit_rates`
            ON
                `exhibit_rates`.`price_floor_id` = `price_floor`.`id`
            WHERE
                `stores`.`store_name` = '$store_name'
            AND
                `exhibit_rates`.`location_code` IS NOT NULL
        ");

        return $query->result_array();
    }

    public function exhibit_floors($store_name)
    {
        $query = $this->db->query(
            "SELECT
                `f`.`floor_name`
            FROM
                `floors` `f`,
                `stores` `s`
            WHERE
                `s`.`store_name` = '$store_name'
            AND
                `s`.`id` = `f`.`store_id`
        ");

        return $query->result_array();

    }

    public function get_sprospect()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`request_date`,
                `s`.`store_name`,
                `p`.`status`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `leasing_users` `u`
            WHERE
               `s`.`id` = `p`.`store_id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                (`p`.`status` != 'On Contract' AND `p`.`status` != 'Denied' AND `p`.`status` != 'Revoked' AND `p`.`status` != 'Deleted')
            AND
                `p`.`flag` = 'Short Term'
            GROUP BY
                `p`.`id`
        ");


        return $query->result_array();
    }
    
// =========================================== ORIGINAL
    // public function get_deniedSprospect()
    // {
    //     $query = $this->db->query(
    //         "SELECT
    //             `p`.`id`,
    //             `p`.`trade_name`,
    //             `p`.`corporate_name`,
    //             `p`.`request_date`,
    //             `p`.`address`,
    //             `p`.`first_category`,
    //             `p`.`second_category`,
    //             `p`.`third_category`,
    //             `p`.`contact_person`,
    //             `p`.`contact_number`,
    //             `p`.`approved_date`,
    //             `lt`.`leasee_type`,
    //             `s`.`store_name`,
    //             `p`.`status`,
    //             CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
    //         FROM
    //             `prospect` `p`,
    //             `leasee_type` `lt`,
    //             `stores` `s`,
    //             `leasing_users` `u`
    //         WHERE
    //             `p`.`lesseeType_id` = `lt`.`id`
    //         AND
    //             `p`.`prepared_by` = `u`.`id`
    //         AND
    //             `p`.`status` =  'Denied'
    //         AND
    //             `s`.`id` = `p`.`store_id`
    //         AND
    //             `p`.`flag` = 'Short Term'
    //     ");


    //     return $query->result_array();
    // }


    public function store_deniedSprospect()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`request_date`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`approved_date`,
                `lt`.`leasee_type`,
                `s`.`store_name`,
                `p`.`status`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `leasing_users` `u`
            WHERE
                `p`.`lesseeType_id` = `lt`.`id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                `p`.`status` =  'Denied'
            AND
                `s`.`id` = `p`.`store_id`
            AND
                `p`.`flag` = 'Short Term'
            AND
                `s`.`id` = '" . $this->_user_group . "'
        ");


        return $query->result_array();
    }

    public function store_sprospect()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`request_date`,
                `s`.`store_name`,
                `p`.`status`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `leasing_users` `u`
            WHERE
               `s`.`id` = `p`.`store_id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                (`p`.`status` != 'On Contract' AND `p`.`status` != 'Denied')
            AND
                `p`.`flag` = 'Short Term'
            AND
                `s`.`id` = '" . $this->_user_group . "'
            GROUP BY
                `p`.`id`
        ");


        return $query->result_array();
    }


    public function view_sprospect($id)
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corp_name`,
                `p`.`address`,
                `p`.`contact_person1`,
                `p`.`contact_person2`,
                `p`.`contact_number1`,
                `p`.`contact_number2`,
                `p`.`status`,
                `p`.request_date,
                `p`.`remarks`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `ex`.`location_code`,
                `ex`.`category`,
                `pf`.`price`,
                `ex`.`floor_area`,
                round(((`ex`.floor_area) * (`pf`.price)) / 30, 2) as `rate_per_day`,
                round((((`ex`.floor_area) * (`pf`.price)) / 30) * 7, 2) as `rate_per_week`,
                round((`ex`.floor_area) * (`pf`.price), 2) as `rate_per_month`,
                (SELECT CONCAT(`preparedby`.`first_name`, ' ', `preparedby`.`last_name`) AS `name` FROM `leasing_users` `preparedby`, `sprospect` `s` WHERE `s`.`id` = '$id' AND `s`.`preparedby_id` = `preparedby`.`id`) AS `preparedby`
            FROM
                `sprospect` `p`,
                `stores` `s`,
                `floors` `f`,
                `exhibit_rates` `ex`,
                `price_floor` `pf`
            WHERE
                `p`.`exhibitrate_id` = `ex`.`id`
            AND
                `ex`.`price_floor_id` = `pf`.`id`
            AND
                `pf`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `p`.`id` = '$id'
        ");

        return $query->result_array();
    }



    public function get_approveSprospect()
    {
        $query = "";

        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Documentation Officer' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor')
        {
            $query = $this->db->query(
                "SELECT
                    `p`.`id`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `p`.`third_category`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`approved_date`,
                    `lt`.`leasee_type`,
                    `s`.`store_name`,
                    `p`.`status`,
                    CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
                FROM
                    `prospect` `p`,
                    `leasee_type` `lt`,
                    `stores` `s`,
                    `leasing_users` `u`
                WHERE
                    `p`.`lesseeType_id` = `lt`.`id`
                AND
                    `p`.`prepared_by` = `u`.`id`
                AND
                    `p`.`status` =  'Approved'
                AND
                    `s`.`id` = `p`.`store_id`
                AND
                    `p`.`flag` = 'Short Term'
            ");

        } else {
            $query = $this->db->query(
                "SELECT
                    `p`.`id`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `p`.`third_category`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`approved_date`,
                    `lt`.`leasee_type`,
                    `s`.`store_name`,
                    `p`.`status`,
                    CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
                FROM
                    `prospect` `p`,
                    `leasee_type` `lt`,
                    `stores` `s`,
                    `leasing_users` `u`
                WHERE
                    `p`.`lesseeType_id` = `lt`.`id`
                AND
                    `p`.`prepared_by` = `u`.`id`
                AND
                    `p`.`status` =  'Approved'
                AND
                    `s`.`id` = `p`.`store_id`
                AND
                    `p`.`flag` = 'Short Term'
                AND

                    `s`.`id` = '" . $this->_user_group . "'
            ");
        }


        return $query->result_array();
    }

    public function get_storeCode($id)
    {
        $query = $this->db->query(
            "SELECT
                `s`.`store_code`
            FROM
                `stores` `s`,
                `prospect` `p`
            WHERE
                `p`.`id` = '$id'
            AND
                `p`.`store_id` = `s`.`id`
            LIMIT 1
        ")->row()->store_code;

        return $query;
    }



    public function generate_locationCodeID()
    {

        $ret;
        $count = $this->db->query(
                "SELECT
                    count(`id`) as `counter`
                FROM
                    `location_code`
            ")->row()->counter;



        if ($count == "0") {
            $ret = "000001";
        } else {
            $ret = str_pad($count + 1, 6, "0", STR_PAD_LEFT);
        }

        return $ret;

    }

    public function get_previousLocationCode($id)
    {
        return $this->db->query("SELECT lc.location_code FROM location_code lc, tenants t WHERE t.id = '$id' AND t.locationCode_id = lc.id ORDER BY lc.id DESC LIMIT 1")->row()->location_code;
    }



    public function generate_UTFTransactionNo($useNext = TRUE)
    {
        /*$ret;
        $count = $this->db->query(
                "SELECT
                    count(`id`) as `counter`
                FROM
                    `uft_payment`
            ")->row()->counter;


        if ($count == "0") {
            $ret = 'UFT' . "0000001";
        } else {
            $ret = 'UFT' . str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }*/


        $sequence = getSequenceNo(
            [   
                'table'         => 'sequence_uft',
                'code'          => "UFT",
                'number'        => '2905',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "UFT Number Sequence"
            ],
            [
                'table' =>  'subsidiary_ledger',
                'column' => 'doc_no'
            ],
            $useNext
        );

        return $sequence;
    }



    public function generate_ClosingRefNo($useNext = true)
    {


        $sequence = getSequenceNo(
            [   
                'code'          => "C",
                'number'        => '3',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "UFT, IP Closing Reference No."
            ],
            [
                'table' =>  'subsidiary_ledger',
                'column' => 'ft_ref'
            ],
            $useNext
        );

        return $sequence;
    }


    public function generate_InternalTransactionNo($useNext = true)
    {
        /*$ret;
        $count = $this->db->query(
                "SELECT
                    count(`id`) as `counter`
                FROM
                    `internal_payment`
            ")->row()->counter;


        if ($count == "0") {
            $ret = 'IP' . "0000001";
        } else {
            $ret = 'IP' . str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }

        return $ret;*/


        $sequence = getSequenceNo(
            [   
                'code'          => "IP",
                'number'        => '113',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Internal Payment Sequence No."
            ],
            [
                'table' =>  'internal_payment',
                'column' => 'doc_no'
            ],
            $useNext
        );

        return $sequence;
    }


    public function generate_reverseInternalTransactionNo() {
        $ret;
        $count = $this->db->query(
                "SELECT
                    count(`id`) as `counter`
                FROM
                    `reverse_internalPayment`
            ")->row()->counter;


        if ($count == "0") {
            $ret = 'RI' . "0000001";
        } else {
            $ret = 'RI' . str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }

        return $ret;
    }

    public function get_prefix($store_name)
    {
        $store_code = $this->db->query(
                "SELECT
                    `store_code`
                FROM
                    `stores`
                WHERE
                    `store_name` = '$store_name'
                LIMIT 1
            ")->row()->store_code;


        return $store_code;

    }


    public function generateID($id, $tenancy_type)
    {
        $ret;
        $prefix;
        $count;

        $store_code = $this->get_storeCode($id);
        $count = $this->db->query(
                "SELECT
                    count(id) as `counter`
                FROM
                    `tenants`
                WHERE
                    `store_code` = '$store_code'
                AND
                    `tenancy_type` = '$tenancy_type'
            ")->row()->counter;


        if ($tenancy_type == 'Long Term')
        {
            $prefix = "LT";
        } else {
            $prefix = "ST";
        }



        if ($count == "0") {
            $ret = "000001";
        } else {
            $ret = str_pad($count + 1, 6, "0", STR_PAD_LEFT);
        }

        return $store_code . "-" . $prefix . $ret;

    }


    public function get_payee($id)
    {
        $query = $this->db->query("SELECT `store_name` FROM `stores` WHERE `id` = '$id' LIMIT 1")->row()->store_name;
        return $query;
    }


    public function shortTerm_tenantID($id)
    {
        $ret;
        $store_code = $this->db->query(
            "SELECT
                `s`.`store_code`
             FROM
                `sprospect` `pros`,
                `stores` `s`,
                `floors` `f`,
                `exhibit_rates` `ex`,
                `price_floor` `pf`
            WHERE
                `f`.`store_id` = `s`.`id`
            AND
                `f`.`id` = `pf`.`floor_id`
            AND
                `pf`.`id` = `ex`.`price_floor_id`
            AND
                `ex`.`id` = `pros`.`exhibitrate_id`
            AND
                `pros`.`id` = '$id'
            LIMIT 1
        ")->row()->store_code;


        $count = $this->db->query(
                    "SELECT
                        count(id) as `counter`
                    FROM
                        `ltenant`
                    WHERE
                        `store_code` = '$store_code'
                    AND
                        flag = 'Short Term'")->row()->counter;

        if ($count == "0")
        {
            $ret = "000001";
        } else {
            $ret = str_pad($count + 1, 6, "0", STR_PAD_LEFT);
        }

        return $store_code . "-" . "TS" . $ret;

    }


    public function generate_invoiceNo()
    {

    }


    public function generate_chargesCode()
    {
        $ret;
        $count = $this->db->query("SELECT max(id) as counter FROM `charges_setup`")->row()->counter;

        if ($count == "0") {
            $ret = "000001";
        } else {
            $ret = str_pad($count + 1, 6, "0", STR_PAD_LEFT);
        }

        return  'PC' . $ret;
    }


    public function payment_docNo($useNext = true)
    {
        /*$ret;
        $count = $this->db->query("SELECT max(id) as counter FROM `payment_scheme`")->row()->counter;

        if ($count == "0") {
            $ret = "0000001";
        } else {
            $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }

        return  'P' . $ret;*/

        $sequence = getSequenceNo(
            [  
                'code'          => "P",
                'number'        => '11697',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Payment Doc No. Sequence"
            ],
            [
                'table' =>  'subsidiary_ledger',
                'column' => 'doc_no'
            ],
            $useNext
        );    

        return $sequence;
    }


    public function get_charges_setup()
    {
        $this -> db -> select('*');
        $this -> db -> from('charges_setup');
        $query = $this -> db -> get();

        return $query->result_array();
    }


    public function generate_contractCode($id)
    {
        $ret;
        $store_code = $this->get_storeCode($id);

        $count = $this->db->query("SELECT count(id) as `counter` FROM `tenants` WHERE `store_code` = '$store_code'")->row()->counter;

        if ($count == "0") {
            $ret = "000001";
        } else {
            $ret = str_pad($count + 1, 6, "0", STR_PAD_LEFT);
        }

        return $store_code . "-" . "C" . $ret;
    }


    function generate_receiptNo()
    {
        $ret;
        $count = $this->db->query("SELECT MAX(`receipt_no`) as `counter` FROM `payment`")->row()->counter;


        $count = ltrim($count, 'PR');
        $count = ltrim($count, '0');

        if ($count == "0")
        {
            $ret = "0000001";
        }
        else
        {
            $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }

        return "PR" . $ret;

    }


    public function generate_contractCode_forShorterm($id)
    {
        $ret;
        $store_code = $this->db->query(
            "SELECT
                `s`.`store_code`
            FROM
                `sprospect` `pros`,
                `exhibit_rates` `ex`,
                `price_floor` `pf`,
                `floors` `f`,
                `stores` `s`
            WHERE
                `pros`.`exhibitrate_id` = `ex`.`id`
            AND
                `ex`.`price_floor_id` = `pf`.`id`
            AND
                `pf`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `pros`.`id` = '$id'
            LIMIT 1
        ")->row()->store_code;


        $count = $this->db->query("SELECT count(id) as `counter` FROM `ltenant` WHERE `store_code` = '$store_code'")->row()->counter;

        if ($count == "0") {
            $ret = "000001";
        } else {
            $ret = str_pad($count + 1, 6, "0", STR_PAD_LEFT);
        }

        return $store_code . "-" . "C" . $ret;
    }


    public function get_discount($data)
    {
        $query = $this->db->query(
            "SELECT
                `discount_type`,
                `discount`
            FROM
                `tenant_type`
            WHERE
                `tenant_type` = '$data'
            LIMIT 1");
        return $query->result_array();
    }


    public function getVat()
    {
        $vat = $this->db->query("SELECT vat FROM vat")->row()->vat;
        return $vat;
    }

    public function WHT()
    {
        $wht = $this->db->query("SELECT withholding FROM withholding_tax")->row()->withholding;
        return $wht;
    }



    public function get_storeCode2($id)
    {
        $store_code = $this->db->query(
            "SELECT
                `s`.`store_code`
             FROM
                `sprospect` `pros`,
                `stores` `s`,
                `floors` `f`,
                `exhibit_rates` `ex`,
                `price_floor` `pf`
            WHERE
               `pros`.`id` = '$id'
            AND
                `pros`.`exhibitrate_id` = `ex`.`id`
            AND
                `ex`.`price_floor_id` = `pf`.`id`
            AND
                `pf`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
        ")->row()->store_code;

        return $store_code;
    }



    public function get_Ltenants()
    {

        $query = "";

        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Super Admin' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`tenant_id`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lt`.`leasee_type`,
                    `lc`.`location_code`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`floor_area`,
                    `lc`.`rental_rate` AS `basic_rental`
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
                    `f`.`store_id` = `s`.`id`
                GROUP BY
                    `t`.`id`
            ");
            return $query->result_array();
        } else {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`tenant_id`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lt`.`leasee_type`,
                    `lc`.`location_code`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`floor_area`,
                    `lc`.`rental_rate` AS `basic_rental`
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
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `t`.`flag` = 'Posted'
                AND
                    `t`.`status` = 'Active'
                AND
                    `lc`.`status` = 'Active'
                AND
                    `t`.`tenancy_type` = 'Long Term'
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                GROUP BY
                    `t`.`id`
            ");

            return $query->result_array();

        }
    }


    public function pending_lcontracts()
    {
        $query = "";

        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Super Admin' || $this->session->userdata('user_type') == 'Corporate Documentation Officer' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor')
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`prospect_id`,
                    `t`.`tenant_id`,
                    `t`.`tenancy_type`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lt`.`leasee_type`,
                    `lc`.`location_code`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`floor_area`,
                    `lc`.`rental_rate` AS `basic_rental`
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
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `p`.`lesseetype_id` = `lt`.`id`
                AND
                    `t`.`status` = 'Active'
                AND
                    `lc`.`status` = 'Active'
                AND
                    `t`.`flag` = 'Pending'
                AND
                    `t`.`tenancy_type` = 'Long Term'
                GROUP BY
                    `t`.`id`
            ");
            return $query->result_array();
        } else {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`prospect_id`,
                    `t`.`tenant_id`,
                    `t`.`tenancy_type`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lt`.`leasee_type`,
                    `lc`.`location_code`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`floor_area`,
                    `lc`.`rental_rate` AS `basic_rental`
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
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `p`.`lesseetype_id` = `lt`.`id`
                AND
                    `t`.`status` = 'Active'
                AND
                    `lc`.`status` = 'Active'
                AND
                    `t`.`flag` = 'Pending'
                AND
                    `t`.`tenancy_type` = 'Long Term'
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                GROUP BY
                    `t`.`id`
            ");

            return $query->result_array();
        }
    }



    public function pending_scontracts()
    {
        $query = "";

        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Super Admin' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`prospect_id`,
                    `t`.`tenant_id`,
                    `t`.`tenancy_type`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lt`.`leasee_type`,
                    `lc`.`location_code`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`floor_area`,
                    `lc`.`rental_rate` AS `basic_rental`
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
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `p`.`lesseetype_id` = `lt`.`id`
                AND
                    `t`.`status` = 'Active'
                AND
                    `lc`.`status` = 'Active'
                AND
                    `t`.`flag` = 'Pending'
                AND
                    `t`.`tenancy_type` = 'Short Term'
                GROUP BY
                    `t`.`id`
            ");
            return $query->result_array();
        } else {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`prospect_id`,
                    `t`.`tenant_id`,
                    `t`.`tenancy_type`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lt`.`leasee_type`,
                    `lc`.`location_code`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`floor_area`,
                    `lc`.`rental_rate` AS `basic_rental`
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
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `p`.`lesseetype_id` = `lt`.`id`
                AND
                    `t`.`status` = 'Active'
                AND
                    `lc`.`status` = 'Active'
                AND
                    `t`.`flag` = 'Pending'
                AND
                    `t`.`tenancy_type` = 'Short Term'
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                GROUP BY
                    `t`.`id`
            ");

            return $query->result_array();
        }
    }


    public function get_prospectID($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `prospect_id`
            FROM
                `tenants`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `status` = 'Active'
        ");

        return $query->result_array();
    }

    public function get_Stenants()
    {
        $query = "";

        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Super Admin' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`tenant_id`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lt`.`leasee_type`,
                    `lc`.`location_code`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`floor_area`,
                    `lc`.`rental_rate` AS `basic_rental`
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
                    `f`.`store_id` = `s`.`id`
                AND
                    `t`.`tenancy_type` = 'Short Term'
                GROUP BY
                    `t`.`id`
            ");
            return $query->result_array();
        } else {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`tenant_id`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lt`.`leasee_type`,
                    `lc`.`location_code`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`floor_area`,
                    `lc`.`rental_rate` AS `basic_rental`
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
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `t`.`flag` = 'Posted'
                AND
                    `t`.`status` = 'Active'
                AND
                    `lc`.`status` = 'Active'
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `t`.`tenancy_type` = 'Short Term'
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                GROUP BY
                    `t`.`id`
            ");

            return $query->result_array();

        }
    }


    public function tenant_details($id)
    {
        $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`contract_no`,
                    `t`.`rental_type`,
                    `t`.`rent_percentage`,
                    `t`.`tenant_id`,
                    `t`.`tenant_type`,
                    `t`.`increment_percentage`,
                    `t`.`increment_frequency`,
                    (CASE
                      WHEN t.is_vat = 'Added' THEN TRUE ELSE  FALSE
                     END) as is_vat,
                    (CASE
                      WHEN t.wht = 'Added' THEN TRUE ELSE  FALSE
                     END) as wht,
                    `t`.`opening_date`,
                    `t`.`vat_agreement`,
                    `t`.`vat_percentage`,
                    `t`.`wht_percentage`,
                    `t`.`penalty_exempt`,
                    `t`.`sales`,
                    `t`.`tenancy_type`,
                    `t`.`tin`,
                    `t`.`expiry_date`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`id` AS `prospect_id`,
                    `p`.`address`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `p`.`third_category`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lt`.`leasee_type`,
                    `lc`.`location_code`,
                    `lc`.`rent_period`,
                    `lc`.`location_desc`,
                    `lc`.`slots_id`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`floor_area`,
                    (CASE
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Annual' THEN TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE())
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Biennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 2)
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Triennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 3)
                        ELSE '0'
                    END) AS `is_incrementable`,
                    `lc`.`rental_rate` AS `basic_rental`
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
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `lc`.`status` = 'Active'
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `p`.`lesseetype_id` = `lt`.`id`
                AND
                    `t`.`id` = '$id'
                GROUP BY
                    `t`.`id`
            ");
            return $query->result_array();
    }


    public function terms_amendment($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `t`.`contract_no`,
                `p`.`trade_name`,
                `f`.`floor_name`,
                `pl`.`location_code`,
                `t`.`lease_period`,
                `t`.`price_persq`,
                `t`.`floor_area`,
                `t`.`rental_type`,
                `t`.`rent_percentage`,
                `t`.`opening_date`,
                `t`.`expiry_date`,
                `t`.`is_vat`,
                `t`.`bir_doc`,
                `t`.`monthly_rental` as `basic_rental`
            FROM
                `ltenant` `t`,
                `lprospect` `p`,
                `price_locationcode` `pl`,
                `price_floor` `pf`,
                `floors` `f`
            WHERE
                `t`.`tenant_id` = '$tenant_id'
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `p`.`locationCode_id` = `pl`.`id`
            AND
                `pl`.`price_floor_id` = `pf`.`id`
            AND
                `pf`.`floor_id` = `f`.`id`
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function sterms_amendment($tenant_id)

    {
        $query = $this->db->query(
            "SELECT
                `t`.`contract_no`,
                `t`.`tenant_id`,
                `p`.`trade_name`,
                `f`.`floor_name`,
                `ex`.`location_code`,
                `t`.`price_persq`,
                `t`.`bir_doc`,
                `t`.`floor_area`,
                `t`.`opening_date`,
                `t`.`expiry_date`,
                `t`.`is_vat`,
                round(`t`.`actual_balance`, 2) AS `basic_rental`,
                datediff(`t`.`expiry_date`, `t`.`opening_date`) AS `num_days`,
                round(`actual_balance` / datediff(`t`.`expiry_date`, `t`.`opening_date`), 2) AS `rate_perday`
            FROM
                `ltenant` `t`,
                `sprospect` `p`,
                `floors` `f`,
                `exhibit_rates` `ex`,
                `price_floor` = `pf`
            WHERE
                `t`.`tenant_id` = '$tenant_id'
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `p`.`exhibitrate_id` = `ex`.`id`
            AND
                `ex`.`price_floor_id` = `pf`.`id`
            AND
                `pf`.`floor_id` = `f`.`id`
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function delete_oldContractDocs($tenant_id)
    {
        $query = $this->db->query(
            "DELETE

            FROM
                `contract_docs`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `status` != 'Terminated'
        ");
    }


    public function delete_selectedDiscounts($tenant_id)
    {
        $query = $this->db->query(
            "DELETE

            FROM
                `selected_discount`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `status` != 'Terminated'
        ");
    }


    public function tenant_terms($id)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `t`.`contract_no`,
                `t`.`tin`,
                `t`.`increment_percentage`,
                CONCAT(`t`.`increment_percentage`, '% ', `t`.`increment_frequency`) as `rent_increment`,
                `t`.`rental_type`,
                `t`.`rent_percentage`,
                `lc`.`rent_period`,
                `t`.`tenant_type`,
                `t`.`opening_date`,
                `t`.`expiry_date`,
                `t`.`is_vat`,
                `t`.`wht`,
                `t`.`bir_doc`
            FROM
                `tenants` `t`,
                `location_code` `lc`
            WHERE
                `t`.`id` = '$id'
            AND
                `t`.`status` != 'Terminated'
            AND
                `t`.`locationCode_id` = `lc`.`id`
            AND
                `lc`.`status` = 'Active'
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function get_discounts($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `type`.`id`,
                `type`.`tenant_type`,
                `type`.`discount_type`,
                `type`.`discount`,
                `type`.`description`
            FROM
                `tenant_type` `type`,
                `selected_discount` `dis`
            WHERE
                `type`.`id` = `dis`.`discount_id`
            AND
                `dis`.`tenant_id` = '$tenant_id'
        ");

        return $query->result_array();
    }


    public function stenant_terms($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `tenant_id`,
                `contract_no`,
                `tin`,
                `opening_date`,
                `expiry_date`,
                datediff(`expiry_date`, `opening_date`) AS `num_days`,
                `is_vat`,
                `bir_doc`,
                `actual_balance` as `basic_rental`
            FROM
                `ltenant`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `flag` = 'Short Term'
            AND
                `status` = 'Active'
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function stenant_details($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `p`.`trade_name`,
                `p`.`corp_name`,
                `p`.`address`,
                `t`.`prospect_id`,
                `p`.`contact_person1`,
                `p`.`contact_person2`,
                `p`.`contact_number1`,
                `p`.`contact_number2`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `ex`.`location_code`,
                `ex`.`category`,
                `t`.`price_persq`,
                `t`.`floor_area`,
                round(((`t`.floor_area) * (`t`.price_persq)) / 30, 2) as `rate_perday`,
                round((((`t`.floor_area) * (`t`.price_persq)) / 30) * 7, 2) as `rate_perweek`,
                round((`t`.floor_area) * (`t`.price_persq), 2) as `rate_permonth`
            FROM
                `ltenant` `t`,
                `sprospect` `p`,
                `exhibit_rates` `ex`,
                `stores` `s`,
                `floors` `f`,
                `price_floor` `pf`
            WHERE
                `t`.`tenant_id` = '$tenant_id'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `p`.`exhibitrate_id` = `ex`.`id`
            AND
                `ex`.`price_floor_id` = `pf`.`id`
            AND
                `pf`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `t`.`flag` = 'Short Term'
            AND
                `t`.`status` = 'Active'
            LIMIT 1
        ");
        return $query->result_array();
    }

    public function get_logs()
    {
        $query = $this->db->query(
            "SELECT
                `l`.`id`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) as user,
                `l`.`action`,
                `l`.`date`
            FROM
                `logs` `l`,
                `leasing_users` `u`
            WHERE
                `u`.`id` = `l`.`user_id`
            ORDER BY
                `l`.`date`
            DESC");
        return $query->result_array();
    }

    public function delete_logs($array)
    {
        for ($i=0; $i < count($array); $i++)
        {
            $this->db->where('id', $array[$i]);
            $this->db->delete('logs');
        }

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function get_otherCharges($tenant_id)
    {

        $query = $this->db->query(
            "SELECT
                *,
                IF(uom = 'Fixed Amount', 1, 0) total_unit,
                IF(uom = 'Fixed Amount', unit_price, 0) actual_amount,
                CAST(IFNULL((
                    SELECT 
                        i.curr_reading
                    FROM 
                        `invoicing` i
                    WHERE 
                        i.charges_code = `charges_setup`.`charges_code` 
                    AND 
                        i.tenant_id = '$tenant_id'
                    ORDER BY 
                        i.posting_date DESC, 
                        i.id DESC
                    LIMIT 1
                ), 0)  AS UNSIGNED) as prev_reading,
                0 as curr_reading

            FROM
                charges_setup
            WHERE
                charges_type != 'Pre Operation Charges'
            AND 
                charges_type != 'Construction Materials'
            AND
               id NOT IN (SELECT `monthly_chargers_id` FROM `selected_monthly_charges` WHERE `tenant_id` = '$tenant_id')

            ORDER BY 
                description ASC
        ");
        return $query->result_array();
    }

    public function get_constMat()
    {
        $query = $this->db->query("SELECT * FROM `charges_setup` WHERE `charges_type` = 'Construction Materials'");
        return $query->result_array();
    }


    public function get_preopCharges()
    {
        $query = $this->db->query("SELECT * FROM `charges_setup` WHERE `charges_type` = 'Pre Operation Charges' ORDER BY `description` ASC");
        return $query->result_array();
    }

    public function preopDetails($tenant_id, $doc_no, $description)
    {
        $query = $this->db->query("SELECT * FROM `tmp_preoperationcharges` WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no' AND `description` = '$description'");
        return $query->result_array();
    }




    public function chargeDetails($description)
    {
        $this -> db -> select('*');
        $this -> db -> from('charges_setup');
        $this -> db -> where('description = ' . "'" . $description . "'");
        $query = $this -> db -> get();

        return $query->result_array();
    }


    public function constMat($description)
    {
        $query = $this->db->query("SELECT `description` FROM `charges_setup` WHERE `charges_type` = '$description'");
        return $query->result_array();
    }


    public function get_monthly_charges()
    {
        $this -> db -> select('*');
        $this -> db -> from('charges_setup');
        $this -> db -> where('charges_type = ' . "'" . 'Monthly Charges' . "'");
        $query = $this -> db -> get();
        return $query->result_array();
    }

    public function get_tenantID($id)
    {

        $query = $this->db->query("SELECT `tenant_id` FROM `tenants` WHERE `id` = '$id' LIMIT 1")->row()->tenant_id;
        return $query;
    }

    public function getTenantByTenantID($tenant_id)
    {

        $query = $this->db->query(
            "SELECT
                `t`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`address`,
                `t`.`tin`,
                `t`.`contract_no`,
                `t`.`tenant_id`,
                `t`.`rental_type`,
                `t`.`tenant_type`,
                `t`.`increment_percentage`,
                `t`.`opening_date`,
                `t`.`rent_percentage`,
                `t`.`basic_rental`,
                `t`.`basic_rental` AS org_basic_rental,
                `t`.`wht`,
                `t`.`is_vat`,
                `t`.`vat_agreement`,
                `t`.`tenancy_type`,
                `t`.`increment_frequency`,
                `t`.`vat_percentage`,
                `t`.`wht_percentage`,
                `t`.`sales`,
                `t`.`penalty_exempt`,
                `l`.`location_code`,
                `l`.`floor_area`,
                (CASE
                    WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Annual' THEN TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE())
                    WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Biennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 2)
                    WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Triennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 3)
                    ELSE '0'
                END) AS `is_incrementable`
            FROM
                `prospect` `p`,
                `tenants` `t`
            LEFT JOIN
                `location_code` `l`
            ON
                (`t`.`locationCode_id` = `l`.`id` AND `l`.`status` = 'Active')

            WHERE
                `t`.`tenant_id` = '$tenant_id'            
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `t`.`status` IN ('Active', 'Terminated')
            ORDER BY
                t.status ASC, t.id DESC
            LIMIT 1
            
        ");

        return $query->row();
    }


    public function select_tradeName($data, $tenancy_type)
    {
        $type = "Short Term";
        if ($tenancy_type == 'Long Term Tenant') {
            $type = "Long Term";
        }

        $store_code = $this->session->userdata('store_code');
        $trade_name = $this->db->query(
            "SELECT
                `t`.`id` as `primaryKey`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `t`.`contract_no`,
                `t`.`tenant_id`,
                `t`.`rental_type`,
                `p`.`address`,
                `t`.`tenant_type`,
                `t`.`increment_percentage`,
                `t`.`opening_date`,
                `t`.`rent_percentage`,
                `t`.`basic_rental`,
                `t`.`basic_rental` AS org_basic_rental,
                `t`.`wht`,
                `t`.`is_vat`,
                (CASE
                    WHEN (`t`.`is_vat` <> 'Added' AND `t`.`wht` <> 'Added') THEN ''
                    WHEN (`t`.`is_vat` = 'Added' OR `t`.`wht` = 'Added') AND `t`.`vat_agreement` = 'Inclusive' THEN 'Inclusive'
                    ELSE 'Exclusive'
                END) vat_agreement,
                `t`.`tenancy_type`,
                `t`.`tin`,
                `t`.`increment_frequency`,
                `t`.`vat_percentage`,
                `t`.`wht_percentage`,
                `t`.`sales`,
                (CASE
                    WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Annual' THEN TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE())
                    WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Biennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 2)
                    WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Triennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 3)
                    ELSE '0'
                END) AS `is_incrementable`
            FROM
                `prospect` `p`,
                `tenants` `t`
            WHERE
                `p`.`trade_name` = '$data'
            and
                `p`.`flag` = '$type'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `t`.`status` IN ('Active', 'terminated')
            AND
                `t`.`store_code` = '$store_code'
            ORDER BY
                t.status ASC, t.id DESC
            LIMIT 1
        ");
        //die($this->db->last_query());
        return $trade_name->result_array();
    }


    public function tenantDetails($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `p`.`trade_name`,
                `t`.`contract_no`
            FROM
                `tenants` `t`,
                `prospect` `p`
            WHERE
                `t`.`tenant_id` = '$tenant_id'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `t`.`status` = 'Active'
        ");

        return $query->result_array();
    }

    public function get_docNo($useNext=true)
    {

        /* ========== MODIFIED ==========
        $count = $this->db->query("SELECT max(doc_no) as `counter` FROM `invoicing`")->row()->counter;

        $count = ltrim($count, 'IC');
        $count = ltrim($count, '0');

        if ($count == "0")
        {
            $ret = "0000001";
        } else {
            $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }
        return "IC" . $ret;
         ========== MODIFIED ========== */


        /*$sequence = getSequenceNo(
            [
                'code'          => "IC",
                'number'        => '1',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Invoice Number Sequence"
            ],
            [
                'table' =>  'invoicing',
                'column' => 'doc_no'
            ],
            $useNext
        );*/

        $sequence = getSequenceNo(
            [  
                'code'          => "IC",
                'number'        => '25652',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Invoice Number Sequence"
            ],
            [
                'table' =>  'invoicing',
                'column' => 'doc_no'
            ],
            $useNext
        );    

        return $sequence;

    }

    public function generate_refNo($useNext = true)
    {
        /*======== MODIFIED =====

        $ret;
        $count = $this->db->query("SELECT max(`ref_no`) as `counter` FROM `ledger`")->row()->counter;

        $count = ltrim($count, 'RN');
        $count = ltrim($count, '0');

        if ($count == "0") {
            $ret = "0000001";
        } else {
            $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }

        return "RN" . $ret;

        ======== MODIFIED =====*/


        /*$sequence = getSequenceNo(
            [
                'code'          => "RN",
                'number'        => '1',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Ledger Reference No. Sequence"
            ],
            [
                'table' =>  'ledger',
                'column' => 'ref_no'
            ],
            $useNext
        );*/

         $sequence = getSequenceNo(
            [   
                //'table'         => 'sequence_refno',
                'code'          => "RN",
                'number'        => '57210',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Ledger Reference No. Sequence"
            ],
            [
                'table' =>  'ledger',
                'column' => 'ref_no'
            ],
            $useNext
        );

        return $sequence;
    }

    public function gl_refNo($useNext = true)
    {
       /* ========= MODIFIED =========
        $ret;
        $count = $this->db->query("SELECT max(`ref_no`) as `counter` FROM `general_ledger`")->row()->counter;

        $count = ltrim($count, 'GL');
        $count = ltrim($count, '0');

        if ($count == "0") {
            $ret = "0000001";
        } else {
            $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }

        return "GL" . $ret;
         ========= MODIFIED =========*/

        /*$sequence = getSequenceNo(
            [
                'code'          => "GL",
                'number'        => '1',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "General Ledger Reference No. Sequence"
            ],
            [
                'table' =>  'general_ledger',
                'column' => 'ref_no'
            ],
            $useNext
        );*/

        $sequence = getSequenceNo(
            [   
                'code'          => "GL",
                'number'        => '26749',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "General Ledger Reference No. Sequence"
            ],
            [
                'table' =>  'general_ledger',
                'column' => 'ref_no'
            ],
            $useNext
        );

        return $sequence;
    }

    

    public function get_soaNo($useNext= true)
    {
        $sequence = getSequenceNo(
            [   
                'code'          => "B",
                'number'        => '11661',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "SOA Number Sequence"
            ],
            [
                'table' =>  'soa',
                'column' => 'soa_no'
            ],
            $useNext
        );

        return $sequence;

    }


    public function prev_electricity_reading($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `curr_reading`
            FROM
                `invoicing`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `id` = (SELECT max(`id`) FROM `invoicing` WHERE `description` = 'Electricity' AND `tenant_id` = '$tenant_id')
            LIMIT 1");

        return $query->result_array();
    }


    public function prev_water_reading($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `curr_reading`
            FROM
                `invoicing`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `id` = (SELECT max(`id`) FROM `invoicing` WHERE `description` = 'Water' AND `tenant_id` = '$tenant_id')
            LIMIT 1");

        return $query->result_array();
    }

    



    


    public function updateARforAdvanceDeposit($tenant_id, $doc_no, $amount)
    {
        $this->db->query(
            "UPDATE
                `general_ledger`
            SET
                `debit` = ((SELECT `debit` FROM (SELECT * FROM `general_ledger` WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no') as `x`) - $amount)
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `doc_no` = '$doc_no'
        ");

    }

    




    public function closingPDC_docNo($useNext = true)
    {

        $sequence = getSequenceNo(
            [   
                'code'          => "PDC",
                'number'        => '124',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "PDC Closing Sequence No."
            ],
            [
                'table' =>  'pdc_closing',
                'column' => 'doc_no'
            ],
            $useNext
        );

        return $sequence;

        /*$count = $this->db->query("SELECT max(doc_no) as `counter` FROM `pdc_closing`")->row()->counter;

        $count = ltrim($count, 'PDC');
        $count = ltrim($count, '0');

        if ($count == "0")
        {
            $ret = "0000001";
        } else {
            $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }
        return "PDC" . $ret;*/
    }


    public function get_WOFDocNo()
    {
        $count = $this->db->query("SELECT max(doc_no) as `counter` FROM `wof_transactions`")->row()->counter;

        $count = ltrim($count, 'WOF');
        $count = ltrim($count, '0');

        if ($count == "0")
        {
            $ret = "0000001";
        } else {
            $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }
        return "WOF" . $ret;
    }


    public function get_CDTransactionNo()
    {
        $count = $this->db->query("SELECT count(`id`) as `counter` FROM `ledger` WHERE `document_type` = 'Credit Memo'")->row()->counter;

        if ($count == 0)
        {
            $ret = "0000001";
        }
        else
        {
            $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }

        return "CM" . $ret;
    }

    public function get_DMTransactionNo()
    {
        $count = $this->db->query("SELECT count(`id`) as `counter` FROM `ledger` WHERE `document_type` = 'Debit Memo'")->row()->counter;

        if ($count == 0)
        {
            $ret = "0000001";
        }
        else
        {
            $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        }

        return "DM" . $ret;
    }

    public function selected_monthly_charges($tenant_id)
    {

        $query = $this->db->query(
            "SELECT
                `charges`.`id`,
                `selected`.`id` AS `selected_id`,
                `charges`.`description`,
                `charges`.`charges_code`,
                `charges`.`with_penalty`,
                `selected`.`uom`,
                `selected`.`unit_price`,
                IF(`selected`.`uom` = 'Fixed Amount', 1, 0) total_unit,
                IF(`selected`.`uom` = 'Fixed Amount', `selected`.`unit_price`, 0) actual_amount,
                CAST(IFNULL((
                    SELECT 
                        `curr_reading` 
                    FROM 
                        `invoicing` 
                    WHERE 
                        `charges_code` = `charges`.`charges_code` 
                    AND 
                        `tenant_id` = `selected`.`tenant_id`
                    ORDER BY 
                        `posting_date` DESC, 
                        `id` DESC
                    LIMIT 1
                ), 0)  AS UNSIGNED) as prev_reading,
                0 as curr_reading
            FROM
                `charges_setup` `charges`,
                `selected_monthly_charges` `selected`
            WHERE
                `selected`.`tenant_id` = '$tenant_id'
            AND
                `selected`.`monthly_chargers_id` = `charges`.`id`
            AND
                `selected`.`flag` = 'Active'");
        return $query->result_array();
    }

    public function get_monthlyCharges_details($desc)
    {
        $query = $this->db->query(
            "SELECT
                `uom`,
                `unit_price`,
                `charges_code`
            FROM
                `charges_setup`
            WHERE
                `description` = '$desc'
            LIMIT 1
        ");
        return $query->result_array();
    }




    public function populate_tradeName($flag)
    {
        $query;
        if ($this->session->userdata('user_type') == 'Administrator')
        {
            if ($flag == 'Long Term Tenant')
            {
                $query = $this->db->query(
                    "SELECT
                        `p`.`trade_name`
                    FROM
                        `tenants` `t`,
                        `prospect` `p`
                    WHERE
                        `t`.`tenancy_type` = 'Long Term'
                    AND
                        (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                    AND
                        (`t`.`flag` = 'Posted' || `t`.`flag` = 'Pending')
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    GROUP BY `t`.`tenant_id`
                    ORDER BY
                        `p`.`trade_name`
                    ASC
                ");
            }
            else
            {
                $query = $this->db->query(
                    "SELECT
                        `p`.`trade_name`
                    FROM
                        `tenants` `t`,
                        `prospect` `p`
                    WHERE
                        `t`.`tenancy_type` = 'Short Term'
                    AND
                        (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                    AND
                        (`t`.`flag` = 'Posted' || `t`.`flag` = 'Pending')
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    GROUP BY `t`.`tenant_id`
                    ORDER BY
                        `p`.`trade_name`
                    ASC
                ");
            }
        }
        else
        {
            if ($flag == 'Long Term Tenant')
            {
                $query = $this->db->query(
                    "SELECT
                        `p`.`trade_name`
                    FROM
                        `tenants` `t`,
                        `prospect` `p`,
                        `stores` `s`
                    WHERE
                        `t`.`tenancy_type` = 'Long Term'
                    AND
                        (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                    AND
                        (`t`.`flag` = 'Posted' || `t`.`flag` = 'Pending')
                    AND
                        `s`.`id` = '" . $this->session->userdata('user_group') . "'
                    AND
                        `s`.`store_code` = `t`.`store_code`
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    GROUP BY `t`.`tenant_id`
                    ORDER BY
                        `p`.`trade_name`
                    ASC
                ");
            }
            else
            {
                $query = $this->db->query(
                    "SELECT
                        `p`.`trade_name`
                    FROM
                        `tenants` `t`,
                        `prospect` `p`,
                        `stores` `s`
                    WHERE
                        `t`.`tenancy_type` = 'Short Term'
                    AND
                        (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                    AND
                        (`t`.`flag` = 'Posted' || `t`.`flag` = 'Pending')
                    AND
                        `s`.`id` = '" . $this->session->userdata('user_group') . "'
                    AND
                        `s`.`store_code` = `t`.`store_code`
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    GROUP BY `t`.`tenant_id`
                    ORDER BY
                        `p`.`trade_name`
                    ASC
                ");
            }
        }

        return $query->result_array();
    }


    public function get_myContract($id)
    {
        $contract_no = $this->db->query("SELECT `contract_no` FROM `tenants` WHERE `prospect_id` = '$id' AND `flag` = 'Pending' AND `status` = 'Active' LIMIT 1")->row()->contract_no;
        return $contract_no;
    }


    public function get_myTenantID($id)
    {
        $tenant_id = $this->db->query("SELECT `tenant_id` FROM `tenants` WHERE `prospect_id` = '$id' AND `flag` = 'Pending' AND `status` = 'Active' LIMIT 1")->row()->tenant_id;
        return $tenant_id;
    }


    public function populate_tenantID($flag)
    {
        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query;
            if ($flag == 'Long Term Tenant')
            {
                $query = $this->db->query(
                    "SELECT
                        `tenant_id`
                    FROM
                        `tenants`
                    WHERE
                        `tenancy_type` = 'Long Term'
                    AND
                        `status` = 'Active'
                    ORDER BY
                        `tenant_id`
                    ASC
                ");

            } else {
                $query = $this->db->query(
                    "SELECT
                        `tenant_id`
                    FROM
                        `tenants`
                    WHERE
                        `tenancy_type` = 'Short Term'
                    AND
                        `status` = 'Active'
                    ORDER BY
                        `tenant_id`
                    ASC
                ");
            }
        } else {
            if ($flag == 'Long Term Tenant')
            {
                $query = $this->db->query(
                    "SELECT
                        `t`.`tenant_id`
                    FROM
                        `tenants` `t`,
                        `stores` `s`
                    WHERE
                        `t`.`tenancy_type` = 'Long Term'
                    AND
                        `s`.`id` = '" . $this->session->userdata('user_group') . "'
                    AND
                        `s`.`store_code` = `t`.`store_code`
                    AND
                        `t`.`status` = 'Active'
                    ORDER BY
                        `t`.`tenant_id`
                    ASC
                ");
            } else {
                $query = $this->db->query(
                    "SELECT
                        `t`.`tenant_id`
                    FROM
                        `tenants` `t`,
                        `stores` `s`
                    WHERE
                        `t`.`tenancy_type` = 'Short Term'
                    AND
                        `s`.`id` = '" . $this->session->userdata('user_group') . "'
                    AND
                        `s`.`store_code` = `t`.`store_code`
                    AND
                        `t`.`status` = 'Active'
                    ORDER BY
                        `t`.`tenant_id`
                    ASC
                ");
            }
        }

        return $query->result_array();
    }


    public function get_billingDate($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `inclusive_date`
            FROM
                `billing_date`
            WHERE
                `inclusive_date`
            NOT IN
                (SELECT
                    `b`.`inclusive_date`
                FROM
                    `billing_date` `b`
                LEFT JOIN
                    `soa_file` `s`
                ON
                    `b`.`tenant_id` = `s`.`tenant_id`
                WHERE
                    `b`.`tenant_id` = '$tenant_id'
                AND
                    `b`.`inclusive_date` = `s`.`billing_date`
                )
            AND
                `tenant_id` = '$tenant_id'
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function shortTerm_charges($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `tenant_id`,
                `is_vat`,
                `actual_balance`
            FROM
                `ltenant`
            WHERE
                `tenant_id` = '$tenant_id'
            LIMIT 1
        ");

        return $query->result_array();
    }

    public function get_myDiscounts($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `dis`.`tenant_type`,
                `dis`.`discount_type`,
                `dis`.`discount`
            FROM
                `tenant_type` `dis`,
                `selected_discount` `sdis`,
                `tenants` `t`
            WHERE
                `sdis`.`tenant_id` = `t`.`id`
            AND
                `t`.`id` = '$tenant_id'
            AND
                `t`.`status` = 'Active'
            AND
                `sdis`.`discount_id` = `dis`.`id`
            AND
                (`sdis`.`status` != 'Terminated' || `sdis`.`status` IS NULL)
            ORDER BY
                `dis`.`tenant_type`
            DESC
        ");

        return $query->result_array();
    }


    public function get_monthlyCharges($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `c`.`id`,
                `c`.`charges_type`,
                `c`.`charges_code`,
                `c`.`description`,
                `c`.`unit_price`
            FROM
                `charges_setup` `c`
            WHERE
                `c`.`charges_type` = 'Monthly Charges'
        ");

        return $query->result_array();
    }


    public function tenant_storeCode($tenant_id)
    {
        $store_code = $this->db->query(
            "SELECT
                `store_code`
            FROM
                `tenants`
            WHERE
                `tenant_id` = '$tenant_id'
            LIMIT 1
        ")->row()->store_code;

        return $store_code;
    }

    public function tenant_tenancyType($tenant_id)
    {
        $tenancy_type = $this->db->query(
            "SELECT
                `flag`
            FROM
                `ltenant`
            WHERE
                `tenant_id` = '$tenant_id'
            LIMIT 1
        ")->row()->flag;

        return $tenancy_type;
    }



    public function sterm_storeCode($tenant_id)
    {
        $store_code = $this->db->query(
            "SELECT
                `s`.`store_code`
            FROM
                `ltenant` `t`,
                `sprospect` `p`,
                `exhibit_rates` `ex`,
                `price_floor` `pf`,
                `floors` `f`,
                `stores` `s`
            WHERE
                `t`.`tenant_id` = '$tenant_id'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `p`.`exhibitrate_id` = `ex`.`id`
            AND
                `ex`.`price_floor_id` = `pf`.`id`
            AND
                `pf`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            LIMIT 1
        ")->row()->store_code;

        return $store_code;
    }


    public function store_name($store_code)
    {
        $store_name = $this->db->query(
            "SELECT
                `store_name`
            FROM
                `stores`
            WHERE
                `store_code` = '$store_code'
            LIMIT 1
        ")->row()->store_name;

        return $store_name;
    }

    public function getStore($store_code)
    {
        $query = $this->db->select('*')
            ->from('stores')
            ->where("store_code = '$store_code'")
            ->limit(1)
            ->get();

        return $query->row();
    }


    public function get_draftInvoice()
    {
        $query = $this->db->query(
            "SELECT
                `invoice`.`tenant_id`,
                `invoice`.`trade_name`,
                `invoice`.`charges_type`,
                `invoice`.`doc_no`,
                `invoice`.`contract_no`,
                `invoice`.`posting_date`,
                `invoice`.`due_date`,
                round(sum(`balance`), 2) as `total`
            FROM
                `invoicing` `invoice`,
                `tenants` `t`
            WHERE
                `invoice`.`tenant_id` = `t`.`tenant_id`
            AND
                `invoice`.`tag` = 'Draft'
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
            GROUP BY
                `invoice`.`doc_no`
        ");

        return $query->result_array();
    }


   /* public function get_postedInvoice()
    {
        $query;
        if ($this->session->userdata('user_type') == 'Administrator')
        {

            $query = $this->db->query(
                "SELECT
                    `invoice`.`tenant_id`,
                    `p`.`trade_name`,
                    `invoice`.`charges_type`,
                    `invoice`.`doc_no`,
                    `t`.`contract_no`,
                    `invoice`.`posting_date`,
                    `invoice`.`due_date`,
                    round(sum(`actual_amt`), 2) as `total`
                FROM
                    `invoicing` `invoice`,
                    `tenants` `t`,
                    `prospect` `p`
                WHERE
                    `invoice`.`tenant_id` = `t`.`tenant_id`
                AND
                    `invoice`.`tag` = 'Posted'
                AND
                    `p`.`id` = `t`.`prospect_id`
                AND
                    `t`.`tenant_id` IN (SELECT tenant_id FROM tenants GROUP BY tenant_id)
                AND
                    (`invoice`.`charges_type` = 'Basic/Monthly Rental' || `invoice`.`charges_type` = 'Other')
                GROUP BY
                    `invoice`.`doc_no`
            ");

        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `invoice`.`tenant_id`,
                    `p`.`trade_name`,
                    `invoice`.`charges_type`,
                    `invoice`.`doc_no`,
                    `t`.`contract_no`,
                    `invoice`.`posting_date`,
                    `invoice`.`due_date`,
                    round(sum(`actual_amt`), 2) as `total`
                FROM
                    `invoicing` `invoice`,
                    `tenants` `t`,
                    `prospect` `p`
                WHERE
                    `invoice`.`tenant_id` = `t`.`tenant_id`
                AND
                    `invoice`.`tag` = 'Posted'
                AND
                    `p`.`id` = `t`.`prospect_id`
                AND
                    `t`.`tenant_id` IN (SELECT tenant_id FROM tenants WHERE store_code = '" . $this->session->userdata('store_code') . "' GROUP BY tenant_id)
                AND
                    (`invoice`.`charges_type` = 'Basic/Monthly Rental' || `invoice`.`charges_type` = 'Other')
                GROUP BY
                    `invoice`.`doc_no`
            ");
        }

        return $query->result_array();
    }*/

    public function get_postedInvoice()
    {
        $query;
        if ($this->session->userdata('user_type') == 'Administrator')
        {

            $query = $this->db->query(
                "SELECT
                    i.tenant_id,
                    p.trade_name,
                    i.charges_type,
                    i.doc_no,
                    t.contract_no,
                    i.posting_date,
                    i.due_date,
                    sum(i.actual_amt) total
                FROM
                    invoicing i
                INNER JOIN 
                    (SELECT distinct tenant_id, prospect_id, contract_no, store_code FROM tenants) as t
                ON
                    i.tenant_id = t.tenant_id 
                RIGHT JOIN
                    prospect p
                ON
                    t.prospect_id = p.id
                WHERE
                    i.tag = 'Posted'
                AND
                    p.id = t.prospect_id
                AND
                    (i.charges_type = 'Basic/Monthly Rental' || i.charges_type = 'Other')
                GROUP BY
                i.doc_no
            ");

        }
        else
        {
            $store_code = $this->session->userdata('store_code');
            $query = $this->db->query(

                "SELECT
                    i.tenant_id,
                    p.trade_name,
                    i.charges_type,
                    i.doc_no,
                    t.contract_no,
                    i.posting_date,
                    i.due_date,
                    sum(i.actual_amt) total
                FROM
                    invoicing i
                INNER JOIN 
                    (SELECT distinct tenant_id, prospect_id, contract_no, store_code FROM tenants) as t
                ON
                    i.tenant_id = t.tenant_id 
                RIGHT JOIN
                    prospect p
                ON
                    t.prospect_id = p.id
                WHERE
                    i.tag = 'Posted'
                AND
                    p.id = t.prospect_id
                AND
                    (i.charges_type = 'Basic/Monthly Rental' || i.charges_type = 'Other')
                AND
                    t.store_code = '$store_code'
                GROUP BY
                i.doc_no");
        }

        return $query->result_array();
    }

    public function invoice_primaryDetails($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `invoice`.`tenant_id`,
                `invoice`.`doc_no`,
                `invoice`.`contract_no`,
                `invoice`.`trade_name`,
                `t`.`rental_type`,
                `invoice`.`posting_date`,
                `invoice`.`transaction_date`,
                `invoice`.`due_date`,
                round(sum(`invoice`.`balance`), 2) as `total`,
                `t`.`tenancy_type`
            FROM
                `invoicing` `invoice`,
                `tenants` `t`
            WHERE
                `invoice`.`tenant_id` = '$tenant_id'
            AND
                `invoice`.`doc_no` = '$doc_no'
            AND
                `invoice`.`tenant_id` = `t`.`tenant_id`
            AND
                `t`.`status` = 'Active'
            GROUP BY
                `invoice`.`doc_no`,
                `invoice`.`tenant_id`
        ");

        return $query->result_array();
    }

    public function draft_invoiceCharges($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `id`,
                `charges_type`,
                `charges_code`,
                `description`,
                `uom`,
                `unit_price`,
                `total_unit`,
                round(`expected_amt`, 2) as `amount`
            FROM
                `invoicing`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `doc_no` = '$doc_no'
        ");

        return $query->result_array();
    }


    public function store_address($store_code)
    {
        $store_address = $this->db->query(
            "SELECT
                `store_address`
            FROM
                `stores`
            WHERE
                `store_code` = '$store_code'
            LIMIT 1
        ")->row()->store_address;

        return $store_address;
    }


    public function store_details($store_code)
    {
        $query = $this->db->query("SELECT * FROM stores WHERE store_code = '$store_code' LIMIT 1");
        return $query->result_array();
    }



    public function generate_billing_date($tenant_id, $opening_date, $expiry_date)
    {
        $opening_date = strtotime($opening_date);
        $expiry_date = strtotime($expiry_date);
        $fetchLimit = "1 month";

        if ($expiry_date <= strtotime(gmdate("Y-m-d", $opening_date)." +" . $fetchLimit)) {
            // it fits in one chunk
            $this->fetchChunk($opening_date, $expiry_date, $tenant_id);

        } else {  // chunkify it!
            $lowerBound = $opening_date;
            $upperBound = strtotime(gmdate("Y-m-d", $opening_date)." +".$fetchLimit);
            while ($upperBound < $expiry_date)
            { // fetch full chunks while there're some left
                $this->fetchChunk($lowerBound,$upperBound, $tenant_id);
                $lowerBound = $upperBound;
                $upperBound = strtotime(gmdate("Y-m-d",$lowerBound)." +".$fetchLimit);
            }
            $this->fetchChunk($lowerBound, $expiry_date, $tenant_id); // get last (likely) partial chunk
        }

    }


    function fetchChunk($a, $b, $tenant_id)
    {
        /* insert your function that actually grabs the partial data */
        //
        // for test, just display the chunk range:
        $inclusive_date = date("j M. Y",$a)." to ".date("j M. Y",$b);


        $data = array(
                'tenant_id'      => $tenant_id,
                'inclusive_date' => $inclusive_date
        );
        $this->db->insert('billing_date', $data);
    }



    public function terminated_ltenant()
    {
        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Super Admin' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`tenant_id`,
                    `t`.`prospect_id`,
                    `pros`.`trade_name`,
                    `terminate`.`termination_date`,
                    `terminate`.`reason`
                FROM
                    `tenants` `t`,
                    `prospect` `pros`,
                    `terminated_contract` `terminate`
                WHERE
                    `terminate`.`tenant_id` = `t`.`id`
                AND
                    `t`.`prospect_id` = `pros`.`id`
                AND
                    `t`.`tenancy_type` = 'Long Term'
                AND
                    `terminate`.`status` != 'Renewed'
                GROUP BY
                    `t`.`tenant_id`
                ORDER BY
                    `terminate`.`termination_date`
                DESC
            ");

            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`tenant_id`,
                    `t`.`prospect_id`,
                    `pros`.`trade_name`,
                    `terminate`.`termination_date`,
                    `terminate`.`reason`
                FROM
                    `tenants` `t`,
                    `prospect` `pros`,
                    `terminated_contract` `terminate`
                WHERE
                    `terminate`.`tenant_id` = `t`.`id`
                AND
                    `t`.`prospect_id` = `pros`.`id`
                AND
                    `t`.`tenancy_type` = 'Long Term'
                AND
                    `terminate`.`status` != 'Renewed'
                AND
                    `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
                GROUP BY
                    `t`.`tenant_id`
                ORDER BY
                    `terminate`.`termination_date`
                DESC
            ");

            return $query->result_array();
        }

    }


    public function terminated_stenant()
    {
        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Super Admin' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`tenant_id`,
                    `t`.`prospect_id`,
                    `pros`.`trade_name`,
                    `terminate`.`termination_date`,
                    `terminate`.`reason`
                FROM
                    `tenants` `t`,
                    `prospect` `pros`,
                    `terminated_contract` `terminate`
                WHERE
                    `terminate`.`tenant_id` = `t`.`id`
                AND
                    `t`.`prospect_id` = `pros`.`id`
                AND
                    `t`.`tenancy_type` = 'Short Term'
                AND
                    `terminate`.`status` != 'Renewed'
                GROUP BY
                    `t`.`tenant_id`
                ORDER BY
                    `terminate`.`termination_date`
                DESC
            ");

            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`tenant_id`,
                    `t`.`prospect_id`,
                    `pros`.`trade_name`,
                    `terminate`.`termination_date`,
                    `terminate`.`reason`
                FROM
                    `tenants` `t`,
                    `prospect` `pros`,
                    `terminated_contract` `terminate`
                WHERE
                    `terminate`.`tenant_id` = `t`.`id`
                AND
                    `t`.`prospect_id` = `pros`.`id`
                AND
                    `t`.`tenancy_type` = 'Short Term'
                AND
                    `terminate`.`status` != 'Renewed'
                AND
                    `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
                GROUP BY
                    `t`.`tenant_id`
                ORDER BY
                    `terminate`.`termination_date`
                DESC
            ");
            return $query->result_array();
        }
    }


    public function get_prospectData($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`contact_person1`,
                `p`.`contact_person2`,
                `p`.`contact_number1`,
                `p`.`contact_number2`,
                `p`.`status`,
                `p`.request_date,
                `p`.`remarks`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `ex`.`location_code`,
                `ex`.`category`,
                `pf`.`price`,
                `ex`.`floor_area`,
                round(((`ex`.floor_area) * (`pf`.price)) / 30, 2) as `rate_per_day`,
                round((((`ex`.floor_area) * (`pf`.price)) / 30) * 7, 2) as `rate_per_week`,
                round((`ex`.floor_area) * (`pf`.price), 2) as `rate_per_month`,
                (SELECT CONCAT(`preparedby`.`first_name`, ' ', `preparedby`.`last_name`) AS `name` FROM `leasing_users` `preparedby`, `sprospect` `s` WHERE `s`.`id` = `p`.`id` AND `s`.`preparedby_id` = `preparedby`.`id`) AS `preparedby`,
                `t`.`tin`
            FROM
                `sprospect` `p`,
                `stores` `s`,
                `floors` `f`,
                `exhibit_rates` `ex`,
                `price_floor` `pf`,
                `ltenant` `t`
            WHERE
                `p`.`exhibitrate_id` = `ex`.`id`
            AND
                `ex`.`price_floor_id` = `pf`.`id`
            AND
                `pf`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `p`.`id` = `t`.`prospect_id`
            AND
                `t`.`tenant_id` = '$tenant_id'
            GROUP BY
                `p`.`id`
        ");

        return $query->result_array();
    }


    public function get_lprospectData($tenant_id)
    {
       $query = $this->db->query(
            "SELECT
                `pros`.`id`,
                `pros`.`trade_name`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `lt`.`leasee_type`,
                `pf`.`price`,
                `lc`.`floor_area`,
                round((`lc`.`floor_area`) * (`pf`.`price`), 2) AS `basic_rental`,
                `lc`.`location_code`,
                `pros`.`category_one`,
                `pros`.`category_two`,
                `pros`.`category_three`,
                `pros`.`contact_person1`,
                `pros`.`contact_person2`,
                `pros`.`contact_number1`,
                `pros`.`contact_number2`,
                `pros`.`request_date`,
                `pros`.`approved_date`,
                `pros`.`status`,
                `pros`.`remarks`,
                `t`.`tin`
            FROM
                `lprospect` `pros`,
                `stores` `s`,
                `floors` `f`,
                `price_locationcode` `lc`,
                `price_floor` `pf`,
                `category_one` `c1`,
                `category_two` `c2`,
                `category_three` `c3`,
                `leasee_type` `lt`,
                `ltenant` `t`
            WHERE
                `f`.`store_id` = `s`.`id`
            AND
                `f`.`id` = `pf`.`floor_id`
            AND
                `pf`.`id` = `lc`.`price_floor_id`
            AND
                `lc`.`id` = `pros`.`locationCode_id`
            AND
                `pros`.`leaseetype_id` = `lt`.`id`
            AND
                `pros`.`id` = `t`.`prospect_id`
            AND
                `t`.`tenant_id` = '$tenant_id'
            GROUP BY
                `pros`.`id`
            LIMIT 1
        ");


        return $query->result_array();
    }

    public function get_prospect_id($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `prospect_id`
            FROM
                `ltenant`
            WHERE
                `tenant_id` = '$tenant_id'
        ")->row()->prospect_id;

        return $query;
    }


    public function generate_soaCredentials($data)
    {
        $trade_name = $this->db->query(
            "SELECT
                `p`.`trade_name`,
                `p`.`address`,
                `t`.`contract_no`,
                `t`.`tenant_id`
            FROM
                `prospect` `p`,
                `tenants` `t`
            WHERE
                `p`.`trade_name` = '$data'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                (`t`.`status` = 'Active' OR `t`.`status` = 'Pending' OR `t`.`status` = 'Terminated')
            AND
                `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
            ORDER BY `t`.`id` DESC
            LIMIT 1
        ");

        return $trade_name->result_array();
    }

    public function get_invoiceBasic($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`document_type`,
                `sl`.`description`,
                `sl`.`ref_no`,
                `sl`.`doc_no`,
                `sl`.`posting_date`,
                `sl`.`due_date`,
                SUM(`sl`.`credit`) as `actual_amt`,
                ROUND(IFNULL((SELECT SUM(`sl2`.`debit`)  FROM `ledger` `sl2` WHERE `sl`.`ref_no` = `sl2`.`ref_no`) ,0) ,2) AS `amount_paid`,
                ROUND(SUM(`sl`.`credit` - IFNULL((SELECT SUM(`sl1`.`debit`) FROM  `ledger`  `sl1` WHERE  `sl`.`ref_no` =  `sl1`.`ref_no` ) , 0 ) ) , 2) AS  `balance`
            FROM
                `ledger`  `sl`
            WHERE
                `sl`.`tenant_id` =  '$tenant_id'
            AND
                `sl`.`charges_type` =  'Basic'
            AND
                `sl`.`document_type` != 'Credit Memo'
            AND
                ROUND(`sl`.`credit` - IFNULL((SELECT SUM(ABS(`sl1`.`debit`)) FROM `ledger` `sl1` WHERE `sl1`.`ref_no` = `sl`.`ref_no`), 0) , 2) > 1
            GROUP BY
                `sl`.`doc_no`
        ");

        return $query->result_array();
    }



    public function sl_tenantRR($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`document_type`,
                `sl`.`description`,
                `sl`.`ref_no`,
                `sl`.`doc_no`,
                `sl`.`posting_date`,
                `sl`.`due_date`,
                SUM(`sl`.`credit`) as `actual_amt`,
                ROUND(IFNULL((SELECT SUM(`sl2`.`debit`)  FROM `ledger` `sl2` WHERE `sl`.`ref_no` = `sl2`.`ref_no`) ,0) ,2) AS `amount_paid`,
                ROUND(SUM(`sl`.`credit` - IFNULL((SELECT SUM(`sl1`.`debit`) FROM  `ledger`  `sl1` WHERE  `sl`.`ref_no` =  `sl1`.`ref_no` ) , 0 ) ) , 2) AS  `balance`
            FROM
                `ledger`  `sl`
            WHERE
                `sl`.`tenant_id` =  '$tenant_id'
            AND
                `sl`.`charges_type` =  'Basic'
            AND
                `sl`.`document_type` != 'Credit Memo'
            AND
                `sl`.`doc_no` = '$doc_no'
            AND
                ROUND(`sl`.`credit` - IFNULL((SELECT SUM(ABS(`sl1`.`debit`)) FROM `ledger` `sl1` WHERE `sl1`.`ref_no` = `sl`.`ref_no`), 0) , 2) > 1
            GROUP BY
                `sl`.`doc_no`
        ");

        return $query->result_array();
    }


    public function get_invoiceRetro($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`document_type`,
                `sl`.`description`,
                `sl`.`ref_no`,
                `sl`.`doc_no`,
                `sl`.`posting_date`,
                `sl`.`due_date`,
                `sl`.`credit` as `actual_amt`,
                ROUND(IFNULL((SELECT SUM(`sl2`.`debit`)  FROM `ledger` `sl2` WHERE `sl`.`ref_no` = `sl2`.`ref_no`) ,0) ,2) AS `amount_paid`,
                `sl`.`doc_no` ,
                ROUND(SUM(`sl`.`credit` - IFNULL((SELECT SUM(`sl1`.`debit`) FROM  `ledger`  `sl1` WHERE  `sl`.`ref_no` =  `sl1`.`ref_no` ) , 0 ) ) , 2) AS  `balance`
            FROM
                `ledger`  `sl`
            WHERE
                `sl`.`tenant_id` =  '$tenant_id'
            AND
                `sl`.`charges_type` =  'Retro'
            AND
                ROUND(`sl`.`credit` - IFNULL((SELECT SUM(ABS(`sl1`.`debit`)) FROM `ledger` `sl1` WHERE `sl1`.`ref_no` = `sl`.`ref_no`), 0) , 2) > 1
            GROUP BY
                `sl`.`doc_no`
        ");

        return $query->result_array();
    }



    public function get_previous_dueDateData($tenant_id, $due_date)
    {
        $query = $this->db->query(
            "SELECT
                SUM(tmp.previous_balance) AS previous_balance,
                SUM(tmp.amount_paid) AS amount_paid
            FROM
                (SELECT
                    SUM(gl.debit) AS previous_balance,
                        IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` 
                        AND `gl`.`tenant_id` = `gl1`.`tenant_id` 
                        AND (`gl1`.`gl_accountID` = '22' OR `gl1`.`gl_accountID` = '29' OR `gl1`.`gl_accountID` = '4')) ,0) AS `amount_paid`
                    FROM
                        general_ledger gl
                    WHERE
                        (gl.tag = 'Basic Rent' OR gl.tag = 'Other' OR gl.tag = 'Penalty')
                    AND
                        gl.tenant_id = '$tenant_id'
                    AND
                        gl.due_date = '$due_date'
                    GROUP BY gl.ref_no) AS tmp
        ");

        return $query->result_array();
    }


    public function get_invoiceID($tenant_id, $doc_no, $description)
    {
        return  $id = $this->db->query("SELECT `id` FROM `invoicing` WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no' AND `description` = '$description'")->row()->id;
    }

    public function get_invoiceOther($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `doc_no`,
                `posting_date`,
                `due_date`,
                `description`,
                ROUND(SUM(`actual_amt`) ,2) AS `actual_amt`,
                ROUND(`actual_amt` - `balance` ,2) AS `amount_paid`,
                ROUND(SUM(`balance`) ,2) AS `balance`
            FROM
                `invoicing`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                (
                    `charges_type` != 'Basic/Monthly Rental'
                AND
                    `charges_type` != 'Basic'
                AND
                    `charges_type` != 'Discount'
                AND
                    `charges_type` != 'Rent Incrementation'
                )
            AND
                `tag` = 'Posted'
            AND
                `status` != 'Paid'
            AND
                `balance` > 0
            AND
                `with_penalty` != 'No'
            GROUP BY
                `tenant_id`,
                `doc_no`
        ");

        return $query->result_array();
    }



    public function get_slOtherCharges($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`document_type`,
                `sl`.`description`,
                `sl`.`posting_date`,
                `sl`.`doc_no`,
                `sl`.`due_date`,
                `sl`.`ref_no`,
                ROUND(SUM(`sl`.`credit`),2) as `actual_amt`,
                ROUND(IFNULL((SELECT SUM(`sl2`.`debit`)  FROM `ledger` `sl2` WHERE `sl`.`ref_no` = `sl2`.`ref_no`) ,0) ,2) AS `amount_paid`,
                `sl`.`doc_no` ,
                ROUND(SUM(`sl`.`credit` - IFNULL((SELECT SUM(`sl1`.`debit`) FROM  `ledger`  `sl1` WHERE  `sl`.`ref_no` =  `sl1`.`ref_no` ) , 0 ) ) , 2) AS  `balance`
            FROM
                `ledger`  `sl`
            WHERE
                `sl`.`tenant_id` =  '$tenant_id'
            AND
                `sl`.`charges_type` =  'Other'
            AND
                `sl`.`doc_no` = '$doc_no'
            AND
                (`sl`.`flag` != 'Penalty' || `sl`.`flag` IS NULL)
            AND
                 ROUND(`sl`.`credit` - IFNULL((SELECT SUM(ABS(`sl1`.`debit`)) FROM `ledger` `sl1` WHERE `sl1`.`ref_no` = `sl`.`ref_no`), 0) , 2) > 1
            GROUP BY
                `sl`.`ref_no`
        ");

        return $query->result_array();
    }

    public function get_invoiceOtherCharges($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`document_type`,
                `sl`.`description`,
                `sl`.`posting_date`,
                `sl`.`doc_no`,
                `sl`.`due_date`,
                `sl`.`ref_no`,
                ROUND(SUM(`sl`.`credit`) - (SELECT IFNULL(SUM(`sl1`.`debit`) ,0) FROM `ledger` `sl1` WHERE `sl1`.`doc_no` = `sl`.`doc_no` AND `sl1`.`flag` = 'EWT'),2) as `actual_amt`,
                ROUND(IFNULL((SELECT SUM(`sl2`.`debit`)  FROM `ledger` `sl2` WHERE `sl`.`ref_no` = `sl2`.`ref_no`) ,0) ,2) AS `amount_paid`,
                `sl`.`doc_no` ,
                ROUND(SUM(`sl`.`credit` - IFNULL((SELECT SUM(`sl1`.`debit`) FROM  `ledger`  `sl1` WHERE  `sl`.`ref_no` =  `sl1`.`ref_no` ) , 0 ) ) , 2) AS  `balance`
            FROM
                `ledger`  `sl`
            WHERE
                `sl`.`tenant_id` =  '$tenant_id'
            AND
                `sl`.`charges_type` =  'Other'
            AND
                (`sl`.`flag` != 'Penalty' || `sl`.`flag` IS NULL)
            GROUP BY
                `sl`.`due_date`
            HAVING
                ROUND(SUM(`sl`.`credit` - IFNULL((SELECT SUM(`sl1`.`debit`) FROM  `ledger`  `sl1` WHERE `sl`.`ref_no` =  `sl1`.`ref_no` ) , 0 ) ) , 2) > 1
        ");

        return $query->result_array();


    }


    public function invoicedTenant()
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`tenant_id`,
                `sl`.`contract_no`,
                `sl`.`doc_no`
            FROM
                `ledger`  `sl`
            WHERE
                (`sl`.`charges_type` =  'Basic' OR  `sl`.`charges_type` =  'Other')
            AND
                ROUND(IFNULL(`sl`.`credit` ,0) - IFNULL((SELECT SUM( ABS(`sl1`.`debit`)) FROM `ledger` `sl1` WHERE  `sl1`.`ref_no` = `sl`.`ref_no`), 0), 2) > 0
            GROUP BY
                `sl`.`tenant_id`
        ");

        return $query->result_array();
    }


    public function get_invoicedDocNo($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`doc_no`
            FROM
                `ledger`  `sl`
            WHERE
                (`sl`.`charges_type` =  'Basic' OR  `sl`.`charges_type` =  'Other')
            AND
                `sl`.`tenant_id` = '$tenant_id'
            AND
                ROUND(`sl`.`credit` - IFNULL((SELECT SUM( ABS(`sl1`.`debit`)) FROM `ledger` `sl1` WHERE  `sl1`.`ref_no` = `sl`.`ref_no`), 0), 2) > 0
            GROUP BY
                `sl`.`doc_no`
            ORDER BY
                `sl`.`doc_no` ASC
        ");

        return $query->result_array();
    }

    public function get_dueDate($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`due_date`,
                (SELECT MAX(`tl`.`due_date`) FROM `ledger` `tl` WHERE `tl`.`tenant_id` = '$tenant_id' LIMIT 1) AS `latest_dueDate`
            FROM
                `ledger`  `sl`
            WHERE
                (`sl`.`charges_type` =  'Basic' OR  `sl`.`charges_type` =  'Other')
            AND
                `sl`.`tenant_id` = '$tenant_id'
            AND
                ROUND(`sl`.`credit` - IFNULL((SELECT SUM( ABS(`sl1`.`debit`)) FROM `ledger` `sl1` WHERE  `sl1`.`ref_no` = `sl`.`ref_no`), 0), 2) >0
            GROUP BY
                `sl`.`due_date`
            ORDER BY
                `sl`.`due_date` ASC
        ");

        return $query->result_array();
    }


    function get_latestDueDate($due_date)
    {
        $arr = array();
        foreach ($due_date as $value) {
            array_push($arr, $value['due_date']);
        }

        return max($arr);
    }


    function get_oledstDueDate($due_date)
    {
        $arr = array();
        foreach ($due_date as $value) {
            array_push($arr, $value['due_date']);
        }

        return min($arr);
    }


    public function get_tenantpreopCharges($tenant_id)
    {
        $query = $this->db->query("SELECT *, 'Preop-Charges' as document_type FROM `tmp_preoperationcharges` WHERE `tenant_id` = '$tenant_id' AND (`tag` = '' || `tag` = 'Posted' AND amount >= 1)");
        return $query->result_array();
    }


    public function check_penalty_able()
    {
        $query = $this->db->query("");
    }


    public function get_paymentBasic($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `inv`.`id` ,
                `inv`.`doc_no` ,
                `inv`.`posting_date` ,
                `inv`.`due_date` ,
                `inv`.`actual_amt`,
                ROUND(`inv`.`actual_amt` ,2) AS `amount`,
                ROUND(`inv`.`balance` ,2) AS `balance`,
                ROUND((`inv`.`actual_amt` - `inv`.`balance`), 2) AS `amount_paid`
            FROM
                `invoicing`  `inv` ,
                `soa`  `s`
            WHERE
                `inv`.`status` !=  'Paid'
            AND
                `inv`.`charges_type` =  'Basic/Monthly Rental'
            AND
                `s`.`invoice_id` =  `inv`.`id`
            AND
                `inv`.`tag` =  'Posted'
            AND
                `inv`.`tenant_id` = '$tenant_id'
            AND
                `s`.`tenant_id` = `inv`.`tenant_id`
            GROUP BY
                `invoice_id`
        ");

        return $query->result_array();
    }



    public function get_glRetroPayment($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `gl`.`id`,
                `gl`.`doc_no`,
                `gl`.`ref_no`,
                `gl`.`posting_date`,
                `gl`.`gl_accountID`,
                `gl`.`due_date`,
                `gl`.`tag`,
                `gl`.`debit` AS `amount`,
                IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16') AND `gl1`.`gl_accountID` = `acc1`.`id`) ,0) AS `amount_paid`,
                ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16' OR `acc1`.`gl_code` = '10.10.01.01.01') AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) AS `balance`
            FROM
                `general_ledger` `gl`,
                `soa` `s`,
                `invoicing` `inv`
            WHERE
                `gl`.`tenant_id` = '$tenant_id'
            AND
                `gl`.`tenant_id` = `s`.`tenant_id`
            AND
                `s`.`invoice_id` = `inv`.`id`
            AND
                `gl`.`tag` = 'Retro Rent'
            AND
                ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16' OR `acc1`.`gl_code` = '10.10.01.01.01') AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) > 1
           GROUP BY `gl`.`due_date`
        ");

        return $query->result_array();
    }

    public function get_unCloseRentDue($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `gl`.`id`,
                `gl`.`doc_no`,
                `gl`.`ref_no`,
                `gl`.`posting_date`,
                ABS(`gl`.`debit`) AS `amount`,
                IFNULL((SELECT SUM(ABS(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `acc1`.`gl_code` = '10.10.01.03.04' AND `gl1`.`gl_accountID` = `acc1`.`id`) ,0) AS `amount_paid`,
                ROUND(ABS(`gl`.`debit`) - IFNULL((SELECT SUM(ABS(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `acc1`.`gl_code` = '10.10.01.03.04' AND `gl1`.`gl_accountID` = `acc1`.`id`) ,0), 2) AS `balance`
            FROM
                `general_ledger` `gl`,
                `gl_accounts` `acc`
            WHERE
                `gl`.`tenant_id` = '$tenant_id'
            AND
                `gl`.`gl_accountID` = `acc`.`id`
            AND
                `acc`.`gl_code` = '10.10.01.03.04'
            AND
                ROUND(ABS(`gl`.`debit`) - IFNULL((SELECT SUM(ABS(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `acc1`.`gl_code` = '10.10.01.03.04' AND `gl1`.`gl_accountID` = `acc1`.`id`) ,0), 2) > 1
            GROUP BY `gl`.`doc_no`"
        );

        return $query->result_array();
    }

    public function get_glBasicPayment($tenant_id)
    {

        /*   =========   OLD QUERY=============================
        "SELECT
            `gl`.`id`,
            `gl`.`doc_no`,
            `gl`.`posting_date`,
            `gl`.`due_date`,
            SUM(`gl`.`debit`) AS `amount`,
            IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16') AND `gl1`.`gl_accountID` = `acc1`.`id`) ,0) AS `amount_paid`,
            ROUND(SUM(`gl`.`debit`) - IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16' OR `acc1`.`gl_code` = '10.10.01.01.01') AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) AS `balance`
        FROM
            `general_ledger` `gl`
        WHERE
            `gl`.`tenant_id` = '$tenant_id'
        AND
            `gl`.`tag` = 'Basic Rent'
        AND
            ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16' OR `acc1`.`gl_code` = '10.10.01.01.01') AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) > 1
       GROUP BY `gl`.`ref_no`, `gl`.`due_date`"

       */

        $query = $this->db->query(
            "SELECT
                `gl`.`id`,
                `gl`.`doc_no`,
                `gl`.`posting_date`,
                `gl`.`due_date`,
                (IFNULL(gl.debit,0) + IFNULL(memo.amount,0)) AS `amount`,
                IFNULL(gl2.paid_amount,0) as amount_paid,
                (IFNULL(gl.debit,0) + IFNULL(memo.amount,0))  - IFNULL(gl2.paid_amount,0) as balance
            FROM
                `general_ledger` `gl`
            LEFT JOIN
                (SELECT
                    g.ref_no,
                    ABS(SUM(IFNULL(credit, 0))) as paid_amount
                FROM 
                    `general_ledger` g 
                WHERE
                    g.document_type = 'Payment'
                AND
                    g.gl_accountID = '4'
                AND 
                    g.tenant_id = '$tenant_id'
                GROUP BY
                    g.ref_no) AS gl2
            ON
                gl2.ref_no = gl.ref_no
            LEFT JOIN
                (SELECT 
                    m.ref_no,
                    SUM(IFNULL(m.debit, 0) + IFNULL(m.credit,0)) as amount
                FROM
                    general_ledger m
                WHERE
                    (m.document_type = 'Credit Memo' OR m.document_type = 'Debit Memo')
                AND
                    m.gl_accountID = '4'
                AND 
                    m.tenant_id = '$tenant_id'
                GROUP BY
                    m.ref_no  
                ) AS memo
                ON
                    memo.ref_no = gl.ref_no
            WHERE
                `gl`.`tenant_id` = '$tenant_id'
            AND
                `gl`.`tag` = 'Basic Rent'
            AND 
                `gl`.`document_type` = 'Invoice'
            AND 
                (IFNULL(gl.debit,0) + IFNULL(memo.amount,0))  - IFNULL(gl2.paid_amount,0) >= 1
            ORDER BY 
                gl.posting_date ASC"
        );

        return $query->result_array();
    }

    public function get_glOtherPayment($tenant_id)
    {   
        /*===============  OLD QUERY ==================
        $query = $this->db->query(
            SELECT
            `gl`.`id`,
            `gl`.`doc_no`,
            `gl`.`posting_date`,
            `gl`.`due_date`,
            (`gl`.`debit` + IFNULL((SELECT `amount` FROM `advance_deposit` WHERE `tenant_id` = '$tenant_id') ,0) - (SELECT IFNULL(SUM(abs(`gl2`.`credit`)), 0) FROM `general_ledger` `gl2` WHERE `gl`.`ref_no` = `gl2`.`ref_no` AND `document_type` = 'Credit Memo') )  AS `amount`,
            IFNULL((SELECT SUM(abs(IFNULL(`credit` ,0))) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.03.04') AND `gl1`.`gl_accountID` = `acc1`.`id`) ,0) AS `amount_paid`,
            ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.03.04' OR `acc1`.`gl_code` = '10.10.01.01.03') AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'cleared' OR `gl1`.`status` IS NULL)) ,0) + IFNULL((SELECT `amount` FROM `advance_deposit` WHERE `tenant_id` = '$tenant_id') ,0), 2) AS `balance`
        FROM
            `general_ledger` `gl`,
            `gl_accounts` `ga`
        WHERE
            `gl`.`tenant_id` = '$tenant_id'
        AND
            `gl`.`tag` = 'Other'
        AND
            ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.04' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.01.03') AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'cleared' OR `gl1`.`status` IS NULL)) ,0) + IFNULL((SELECT `amount` FROM `advance_deposit` WHERE `tenant_id` = '$tenant_id') ,0)) > 1
        GROUP BY `gl`.`ref_no`, `due_date`"
        );
        =================================================*/
        $query = $this->db->query(
            "SELECT
                `gl`.`id`,
                `gl`.`doc_no`,
                `gl`.`posting_date`,
                `gl`.`due_date`,
                (IFNULL(gl.debit,0) + IFNULL(memo.amount,0)) AS `amount`,
                IFNULL(gl2.paid_amount,0) as amount_paid,
                (IFNULL(gl.debit,0) + IFNULL(memo.amount,0))  - IFNULL(gl2.paid_amount,0) as balance
            FROM
                `general_ledger` `gl`
            LEFT JOIN
                (SELECT
                    g.ref_no,
                    ABS(SUM(IFNULL(credit, 0))) as paid_amount
                FROM 
                    `general_ledger` g 
                WHERE
                    g.document_type = 'Payment'
                AND
                    (g.gl_accountID = '22' OR g.gl_accountID = '29')
                AND 
                    g.tenant_id = '$tenant_id'
                GROUP BY
                    g.ref_no) AS gl2
            ON
                gl2.ref_no = gl.ref_no
            LEFT JOIN
                (SELECT 
                    m.ref_no,
                    SUM(IFNULL(m.debit, 0) + IFNULL(m.credit,0)) as amount
                FROM
                    general_ledger m
                WHERE
                    (m.document_type = 'Credit Memo' OR m.document_type = 'Debit Memo')
                AND 
                    (m.gl_accountID = 22 OR m.gl_accountID = 29)
                AND 
                    m.tenant_id = '$tenant_id'
                GROUP BY
                    m.ref_no  
                ) AS memo
                ON
                    memo.ref_no = gl.ref_no
            WHERE
                `gl`.`tenant_id` = '$tenant_id'
            AND
                `gl`.`tag` = 'Other'
            AND 
                `gl`.`document_type` = 'Invoice'
            AND 
                (IFNULL(gl.debit,0) + IFNULL(memo.amount,0))  - IFNULL(gl2.paid_amount,0) >= 1 
            ORDER BY 
                gl.posting_date ASC"
        );

        return $query->result_array();
    }



    public function tenant_lookup($tenancy_type)
    {
        $query;
        if ($tenancy_type == 'Long Term Tenant')
        {

            $query = $this->db->query(
                "SELECT
                    `t`.`tenant_id`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `s`.`store_name`
                FROM
                    `tenants` `t`,
                    `prospect` `p`,
                    `stores` `s`
                WHERE
                    `t`.`tenancy_type` = 'Long Term'
                AND
                    (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `s`.`store_code` = `t`.`store_code`
                AND
                    s.id = '" . $this->_user_group . "'
                GROUP BY `t`.`tenant_id`
                ORDER BY
                    `t`.`tenant_id` ASC
            ");

        }
        elseif($tenancy_type == 'Short Term Tenant')
        {

            $query = $this->db->query(
                "SELECT
                    `t`.`tenant_id`,
                    `p`.`trade_name`,
                    `s`.`store_name`
                FROM
                    `tenants` `t`,
                    `prospect` `p`,
                    `stores` `s`
                WHERE
                    `t`.`tenancy_type` = 'Short Term'
                AND
                    (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `s`.`store_code` = `t`.`store_code`
                AND
                    s.id = '" . $this->_user_group . "'
                GROUP BY `t`.`tenant_id`
                ORDER BY
                    `t`.`tenant_id` ASC
            ");

        }else{
            return [];
        }

        return $query->result_array();
    }



    public function get_paymentOther($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `inv`.`id`,
                round(SUM(`inv`.`actual_amt`), 2) as `amount`,
                `inv`.`doc_no`,
                `inv`.`posting_date`,
                `inv`.`due_date`,
                ROUND(SUM(`inv`.`balance`), 2) AS `balance`,
                ROUND(SUM(`inv`.`actual_amt`) - SUM(`inv`.`balance`), 2) AS `amount_paid`
            FROM
                `invoicing` `inv`,
                `soa` `s`
            WHERE
                `inv`.`tenant_id` = '$tenant_id'
            AND
                `inv`.`charges_type` = 'Other'
            AND
                `inv`.`tag` = 'Posted'
            AND
                `inv`.`status` != 'Paid'
            AND
                `s`.`invoice_id` = `inv`.`id`
            AND
                `s`.`soa_no` = (SELECT max(`soa_no`) FROM `soa` WHERE tenant_id = '$tenant_id')
            GROUP BY
                `inv`.`tenant_id`,
                `inv`.`doc_no`
        ");

        return $query->result_array();
    }


    public function details_soa($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `p`.`corporate_name`,
                `p`.`address`,
                `p`.`contact_person`,
                `t`.`tin`,
                `t`.`rental_type`,
                `lc`.`location_code`,
                `lc`.`floor_area`,
                `t`.`rent_percentage`
            FROM
                `prospect` `p`,
                `tenants` `t`
            LEFT JOIN
                `history_locationcode` `lc`
            ON
                `lc`.`location_historyID` = `t`.`locationCode_id` 
            WHERE
                `t`.`tenant_id` = '$tenant_id'
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`prospect_id` = `p`.`id`
                
            LIMIT 1
        ");

        return $query->result_array();
    }

    public function get_tenantType($tenant_id)
    {
        $query = $this->db->query("SELECT `tenant_type` FROM tenants WHERE `tenant_id` = '$tenant_id' AND `status` = 'Active' LIMIT 1")->row()->tenant_type;
        return $query;
    }



    public function get_penaltyforDueDate($tenant_id, $due_date)
    {
        $query = $this->db->query(
                "SELECT
                    ROUND((SELECT IFNULL(`sl`.`credit`,0) - SUM(IFNULL(`tl`.`debit`, 0)) FROM `ledger` `tl` WHERE `sl`.`ref_no` = `tl`.`ref_no` AND `tl`.`tenant_id` = '$tenant_id'), 2) AS `amount`
                FROM
                    `ledger` `sl`,
                    `invoicing` `inv`,
                    `tmp_latepaymentpenalty` `tmp`
                WHERE
                    `sl`.`doc_no` = `tmp`.`doc_no`
                AND
                    `inv`.`doc_no` = `tmp`.`invoice_no`
                AND
                    `inv`.`due_date` = '$due_date'
                AND
                    `inv`.`flag` = 'Penalty'
                AND
                    `sl`.`flag` = 'Penalty'
                AND
                    `sl`.`tenant_id` = '$tenant_id'
                AND
                    `inv`.`tenant_id` = '$tenant_id'
                AND
                    `tmp`.`tenant_id` = '$tenant_id'
                AND
                     ROUND((SELECT IFNULL(`sl`.`credit`,0) - SUM(IFNULL(`tl`.`debit`, 0)) FROM `ledger` `tl` WHERE `sl`.`ref_no` = `tl`.`ref_no` AND `tl`.`tenant_id` = '$tenant_id'), 2) > 1
            ");
        return $query->result_array();
    }

    public function chargesDetails($tenant_id, $due_date, $charges_type)
    {
        if ($charges_type == 'Other')
        {
            $query = $this->db->query(
                "SELECT
                    `inv`.`id`,
                    `inv`.`charges_type`,
                    `inv`.`due_date`,
                    `inv`.`doc_no`,
                    `inv`.`description`,
                    `inv`.`total_gross`,
                    `inv`.`prev_reading`,
                    `inv`.`curr_reading`,
                    `inv`.`unit_price`,
                    `inv`.`total_unit`,
                    ROUND(`inv`.`expected_amt` ,2) AS `expected_amt`,
                    ROUND(`inv`.`actual_amt` ,2) AS `actual_amt`,
                    ROUND(`inv`.`expected_amt`, 2) AS `amount`,
                    ROUND(IFNULL(`inv`.`actual_amt` ,0) - IFNULL(`balance` ,0), 2) AS `amount_paid`,
                    ROUND(`inv`.`balance`, 2) AS `balance`,
                    `inv`.`status`,
                    `t`.`basic_rental`,
                    `t`.`increment_percentage`,
                    (CASE
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Annual' THEN TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE())
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Biennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 2)
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Triennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 3)
                        ELSE '0'
                    END) AS `is_incrementable`
                FROM
                    `invoicing` `inv`,
                    `tenants` `t`
                WHERE
                    `inv`.`tenant_id` = '$tenant_id'
                AND
                    `inv`.`due_date` = '$due_date'
                AND
                    `inv`.`charges_type` = '$charges_type'
                AND
                    `inv`.`tag` = 'Posted'
                AND
                    `inv`.`status` != 'Paid'
                AND
                    `t`.`tenant_id` = `inv`.`tenant_id`
                AND
                    `t`.`contract_no` = `inv`.`contract_no`
                AND
                    `t`.`status` = 'Active'
                GROUP BY
                    `inv`.`description`, `inv`.`due_date`
                ORDER BY
                    `inv`.`due_date`
                ASC
            ");
        }
        elseif ($charges_type == 'Basic' || $charges_type == 'Discount' || $charges_type == 'Rent Incrementation')
        {
            $query = $this->db->query(
                "SELECT
                    `inv`.`id`,
                    `inv`.`charges_type`,
                    `inv`.`due_date`,
                    `inv`.`doc_no`,
                    `inv`.`description`,
                    `inv`.`total_gross`,
                    `inv`.`prev_reading`,
                    `inv`.`curr_reading`,
                    `inv`.`unit_price`,
                    `inv`.`total_unit`,
                    ROUND(`inv`.`expected_amt` ,2) AS `expected_amt`,
                    ROUND(`inv`.`actual_amt` ,2) AS `actual_amt`,
                    ROUND(`inv`.`expected_amt`, 2) AS `amount`,
                    ROUND(IFNULL(`inv`.`actual_amt` ,0) - IFNULL(`balance` ,0), 2) AS `amount_paid`,
                    ROUND(`inv`.`balance`, 2) AS `balance`,
                    `inv`.`status`,
                    `t`.`basic_rental`,
                    `t`.`increment_percentage`,
                    (CASE
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Annual' THEN TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE())
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Biennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 2)
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Triennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 3)
                        ELSE '0'
                    END) AS `is_incrementable`
                FROM
                    `invoicing` `inv`,
                    `tenants` `t`
                WHERE
                    `inv`.`tenant_id` = '$tenant_id'
                AND
                    `inv`.`due_date` = '$due_date'
                AND
                    `inv`.`charges_type` = '$charges_type'
                AND
                    `inv`.`tag` = 'Posted'
                AND
                    `inv`.`status` != 'Paid'
                AND
                    `t`.`tenant_id` = `inv`.`tenant_id`
                AND
                    `t`.`contract_no` = `inv`.`contract_no`
                AND
                    `t`.`status` = 'Active'
                GROUP BY
                    `inv`.`description`, `inv`.`due_date`
                ORDER BY
                    `inv`.`due_date`
                ASC
            ");
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `inv`.`id`,
                    `inv`.`charges_type`,
                    `inv`.`due_date`,
                    `inv`.`doc_no`,
                    `inv`.`description`,
                    `inv`.`total_gross`,
                    `inv`.`prev_reading`,
                    `inv`.`curr_reading`,
                    `inv`.`unit_price`,
                    `inv`.`total_unit`,
                    ROUND(SUM(`inv`.`expected_amt`) ,2) AS `expected_amt`,
                    ROUND(`inv`.`actual_amt` ,2) AS `actual_amt`,
                    ROUND(SUM(`inv`.`expected_amt`), 2) AS `amount`,
                    ROUND(IFNULL(`inv`.`actual_amt` ,0) - IFNULL(`balance` ,0), 2) AS `amount_paid`,
                    ROUND(`inv`.`balance`, 2) AS `balance`,
                    `inv`.`status`,
                    ROUND(SUM(`inv`.`expected_amt`), 2) AS `base_rental`,
                    `t`.`basic_rental`,
                    `t`.`increment_percentage`,
                    (CASE
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Annual' THEN TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE())
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Biennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 2)
                        WHEN `t`.`increment_percentage` != 'None' AND `t`.`increment_frequency` = 'Triennial' THEN FLoor(TIMESTAMPDIFF(YEAR, `t`.`opening_date`, CURDATE()) / 3)
                        ELSE '0'
                    END) AS `is_incrementable`
                FROM
                    `invoicing` `inv`,
                    `tenants` `t`
                WHERE
                    `inv`.`tenant_id` = '$tenant_id'
                AND
                    `inv`.`due_date` = '$due_date'
                AND
                    `inv`.`charges_type` = '$charges_type'
                AND
                    `inv`.`tag` = 'Posted'
                AND
                    `inv`.`status` != 'Paid'
                AND
                    `t`.`tenant_id` = `inv`.`tenant_id`
                AND
                    `t`.`status` = 'Active'
                AND
                    ROUND(`inv`.`balance`, 2) > 0
                GROUP BY
                     `inv`.`due_date`
                ORDER BY
                    `inv`.`id`
                ASC
            ");
        }

        $results = $query->result_array();

        foreach ($results as $key => $result) {

            $result = (object)$result;

            if($result->is_incrementable > 1){
                $exponent = $result->is_incrementable - 1;

                $basic_rental = $result->basic_rental *  pow(1+ ($result->increment_percentage/100), $exponent);

                $result->basic_rental = round($basic_rental, 2);
                $result->is_incrementable = 1;
            }

            $results[$key] = (array) $result;
            
        }

        return $results;
    }

    /*=============== REMOVED DURING MODIFICATION =============

    public function chargesDetails($tenant_id, $due_date, $charges_type)
    {
        if ($charges_type == 'Other')
        {
            $query = $this->db->query(
                "SELECT
                    `inv`.`id`,
                    `inv`.`charges_type`,
                    `inv`.`due_date`,
                    `inv`.`doc_no`,
                    `inv`.`description`,
                    `inv`.`total_gross`,
                    `inv`.`prev_reading`,
                    `inv`.`curr_reading`,
                    `inv`.`unit_price`,
                    `inv`.`total_unit`,
                    ROUND(`inv`.`expected_amt` ,2) AS `expected_amt`,
                    ROUND(`inv`.`actual_amt` ,2) AS `actual_amt`,
                    ROUND(`inv`.`expected_amt`, 2) AS `amount`,
                    ROUND(IFNULL(`inv`.`actual_amt` ,0) - IFNULL(`balance` ,0), 2) AS `amount_paid`,
                    ROUND(`inv`.`balance`, 2) AS `balance`,
                    `inv`.`status`,
                    `t`.`basic_rental`
                FROM
                    `invoicing` `inv`,
                    `tenants` `t`
                WHERE
                    `inv`.`tenant_id` = '$tenant_id'
                AND
                    `inv`.`due_date` = '$due_date'
                AND
                    `inv`.`charges_type` = '$charges_type'
                AND
                    `inv`.`tag` = 'Posted'
                AND
                    `inv`.`status` != 'Paid'
                AND
                    `t`.`tenant_id` = `inv`.`tenant_id`
                AND
                    `t`.`contract_no` = `inv`.`contract_no`
                AND
                    `t`.`status` = 'Active'
                GROUP BY
                    `inv`.`description`, `inv`.`due_date`
                ORDER BY
                    `inv`.`due_date`
                ASC
            ");
        }
        elseif ($charges_type == 'Basic' || $charges_type == 'Discount' || $charges_type == 'Rent Incrementation')
        {
            $query = $this->db->query(
                "SELECT
                    `inv`.`id`,
                    `inv`.`charges_type`,
                    `inv`.`due_date`,
                    `inv`.`doc_no`,
                    `inv`.`description`,
                    `inv`.`total_gross`,
                    `inv`.`prev_reading`,
                    `inv`.`curr_reading`,
                    `inv`.`unit_price`,
                    `inv`.`total_unit`,
                    ROUND(`inv`.`expected_amt` ,2) AS `expected_amt`,
                    ROUND(`inv`.`actual_amt` ,2) AS `actual_amt`,
                    ROUND(`inv`.`expected_amt`, 2) AS `amount`,
                    ROUND(IFNULL(`inv`.`actual_amt` ,0) - IFNULL(`balance` ,0), 2) AS `amount_paid`,
                    ROUND(`inv`.`balance`, 2) AS `balance`,
                    `inv`.`status`,
                    `t`.`basic_rental`
                FROM
                    `invoicing` `inv`,
                    `tenants` `t`
                WHERE
                    `inv`.`tenant_id` = '$tenant_id'
                AND
                    `inv`.`due_date` = '$due_date'
                AND
                    `inv`.`charges_type` = '$charges_type'
                AND
                    `inv`.`tag` = 'Posted'
                AND
                    `inv`.`status` != 'Paid'
                AND
                    `t`.`tenant_id` = `inv`.`tenant_id`
                AND
                    `t`.`contract_no` = `inv`.`contract_no`
                AND
                    `t`.`status` = 'Active'
                GROUP BY
                    `inv`.`description`, `inv`.`due_date`
                ORDER BY
                    `inv`.`due_date`
                ASC
            ");
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `inv`.`id`,
                    `inv`.`charges_type`,
                    `inv`.`due_date`,
                    `inv`.`doc_no`,
                    `inv`.`description`,
                    `inv`.`total_gross`,
                    `inv`.`prev_reading`,
                    `inv`.`curr_reading`,
                    `inv`.`unit_price`,
                    `inv`.`total_unit`,
                    ROUND(SUM(`inv`.`expected_amt`) ,2) AS `expected_amt`,
                    ROUND(`inv`.`actual_amt` ,2) AS `actual_amt`,
                    ROUND(SUM(`inv`.`expected_amt`), 2) AS `amount`,
                    ROUND(IFNULL(`inv`.`actual_amt` ,0) - IFNULL(`balance` ,0), 2) AS `amount_paid`,
                    ROUND(`inv`.`balance`, 2) AS `balance`,
                    `inv`.`status`,
                    ROUND(SUM(`inv`.`expected_amt`), 2) AS `base_rental`,
                    `t`.`basic_rental`
                FROM
                    `invoicing` `inv`,
                    `tenants` `t`
                WHERE
                    `inv`.`tenant_id` = '$tenant_id'
                AND
                    `inv`.`due_date` = '$due_date'
                AND
                    `inv`.`charges_type` = '$charges_type'
                AND
                    `inv`.`tag` = 'Posted'
                AND
                    `inv`.`status` != 'Paid'
                AND
                    `t`.`tenant_id` = `inv`.`tenant_id`
                AND
                    `t`.`status` = 'Active'
                AND
                    ROUND(`inv`.`balance`, 2) > 0
                GROUP BY
                     `inv`.`due_date`
                ORDER BY
                    `inv`.`id`
                ASC
            ");
        }

        return $query->result_array();
    }
    =============== REMOVED DURING MODIFICATION =============*/


    public function other_chargeDetails($tenant_id, $due_date)
    {
        $query = $this->db->query(
                "SELECT
                    `inv`.`id`,
                    `inv`.`charges_type`,
                    `inv`.`due_date`,
                    `inv`.`doc_no`,
                    `inv`.`description`,
                    `inv`.`total_gross`,
                    `inv`.`prev_reading`,
                    `inv`.`curr_reading`,
                    `inv`.`unit_price`,
                    `inv`.`total_unit`,
                    ROUND(`inv`.`expected_amt` ,2) AS `expected_amt`,
                    ROUND(`inv`.`actual_amt` ,2) AS `actual_amt`,
                    ROUND(`inv`.`expected_amt`, 2) AS `amount`,
                    ROUND(IFNULL(`inv`.`actual_amt`, 0) - IFNULL(`balance`, 0), 2) AS `amount_paid`,
                    ROUND(`inv`.`balance`, 2) AS `balance`,
                    `inv`.`status`,
                    `t`.`basic_rental`
                FROM
                    `invoicing` `inv`,
                    `tenants` `t`
                WHERE
                    `inv`.`tenant_id` = '$tenant_id'
                AND
                    `inv`.`due_date` = '$due_date'
                AND
                    (
                        `inv`.`charges_type` = 'Other'
                        OR
                        `inv`.`charges_type` = 'Construction Materials'
                    )
                AND
                    `inv`.`tag` = 'Posted'
                AND
                    (`inv`.`status` != 'Paid' OR `inv`.`status` IS NULL)
                AND
                    `t`.`tenant_id` = `inv`.`tenant_id`
                AND
                    `t`.`status` = 'Active'
                AND 
                	`inv`.`flag` <> 'Penalty' 
                ORDER BY
                    `inv`.`due_date`
                ASC
            ");

        	//AND  `inv`.`flag` <> 'Penalty' LINE FOR TEMPORARY NOT INCLUDE PENALTY DUE TO COVID

        return $query->result_array();
    }


    public function get_preopdata($tenant_id)
    {
        $query = $this->db->query("SELECT * FROM `tmp_preoperationcharges` WHERE `tenant_id` = '$tenant_id' AND `tag` = 'Posted'");
        return $query->result_array();
    }


    public function charges_withNo_penalty($tenant_id)
    {
        $query = $this->db->query(
                "SELECT
                    `inv`.`id`,
                    `inv`.`charges_type`,
                    `inv`.`due_date`,
                    `inv`.`doc_no`,
                    `inv`.`description`,
                    `inv`.`total_gross`,
                    `inv`.`prev_reading`,
                    `inv`.`curr_reading`,
                    `inv`.`unit_price`,
                    `inv`.`total_unit`,
                    ROUND(`inv`.`expected_amt` ,2) AS `expected_amt`,
                    ROUND(`inv`.`actual_amt` ,2) AS `actual_amt`,
                    ROUND(`inv`.`expected_amt`, 2) AS `amount`,
                    ROUND(IFNULL(`inv`.`actual_amt`, 0) - IFNULL(`balance`, 0), 2) AS `amount_paid`,
                    ROUND(`inv`.`balance`, 2) AS `balance`,
                    `inv`.`status`,
                    `t`.`basic_rental`
                FROM
                    `invoicing` `inv`,
                    `tenants` `t`
                WHERE
                    `inv`.`tenant_id` = '$tenant_id'
                AND
                    (
                        `inv`.`charges_type` = 'Other'
                        OR
                        `inv`.`charges_type` = 'Construction Materials'
                        OR
                        `inv`.`charges_type` = 'Pre Operation Charges'
                    )
                AND
                    `inv`.`tag` = 'Posted'
                AND
                    `inv`.`status` != 'Paid'
                AND
                    `t`.`tenant_id` = `inv`.`tenant_id`
                AND
                    `t`.`contract_no` = `inv`.`contract_no`
                AND
                    `t`.`status` = 'Active'
                AND
                    `inv`.`with_penalty` = 'No'
                ORDER BY
                    `inv`.`due_date`
                ASC
            ");

        return $query->result_array();
    }


    public function checK_ifapplicabletopenalty($description)
    {
        $query = $this->db->query("SELECT `with_penalty` FROM `charges_setup` WHERE `description` = '$description' LIMIT 1");
        return $query->result_array();
    }

    public function unclosed_chargesWithNo_penalty($doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`document_type`,
                `sl`.`description`,
                `sl`.`posting_date`,
                `sl`.`due_date`,
                `sl`.`debit`,
                ROUND(IFNULL((SELECT SUM(`sl2`.`debit`)  FROM `ledger` `sl2` WHERE `sl`.`ref_no` = `sl2`.`ref_no`) ,0) ,2) AS `amount_paid`,
                `sl`.`doc_no` ,
                ROUND( SUM(  `sl`.`credit` - IFNULL( (
            SELECT
                SUM(  `sl1`.`debit` )
                FROM  `ledger`  `sl1`
                WHERE  `sl`.`ref_no` =  `sl1`.`ref_no` ) , 0 ) ) , 2
                ) AS  `balance`
            FROM
                `ledger`  `sl`
            WHERE
                `sl`.`doc_no` =  '$doc_no'
            AND
                `sl`.`with_penalty` = 'No'
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function total_perDueDate($tenant_id, $due_date)
    {
        $query = $this->db->query(
            "SELECT
                ROUND( IFNULL(SUM( IFNULL(`sl`.`credit` ,0) -  IFNULL(`sl`.`debit` ,0) ),0) + IFNULL((SELECT SUM(`amount`) FROM `tmp_preoperationcharges` WHERE `tenant_id` = '$tenant_id' AND `due_date` = '$due_date') ,0) , 2 ) AS  `total`
            FROM
                `ledger`  `sl`
            WHERE
                `sl`.`tenant_id` =  '$tenant_id'
            AND
                `sl`.`due_date` =  '$due_date'")->row()->total;

        return $query;
    }


    public function previous_totalPerDueDate($tenant_id, $due_date)
    {
        $query = $this->db->query(
            "SELECT
                ROUND(SUM(`sl`.`credit` - IFNULL((SELECT SUM(`sl1`.`debit`) FROM  `ledger`  `sl1` WHERE  `sl`.`ref_no` =  `sl1`.`ref_no` AND `sl`.`tenant_id` = `sl1`.`tenant_id`) , 0 ) ) , 2) AS  `total`
            FROM
                `ledger`  `sl`
            WHERE
                `sl`.`tenant_id` =  '$tenant_id'
            AND
                `sl`.`due_date` =  '$due_date'
            AND
                `sl`.`document_type` != 'Credit Memo'")->row()->total;

        return $query;
    }


    public function previous_withNoPenalty($tenant_id, $due_date)
    {
        $query = $this->db->query(
            "SELECT
                ROUND(SUM(`sl`.`credit` - IFNULL((SELECT SUM(`sl1`.`debit`) FROM  `ledger`  `sl1` WHERE  `sl`.`ref_no` =  `sl1`.`ref_no` ) , 0 ) ) , 2) AS  `amount`
            FROM
                `ledger`  `sl`
            WHERE
                `sl`.`tenant_id` =  '$tenant_id'
            AND
                `sl`.`due_date` =  '$due_date'
            AND
                `sl`.`document_type` != 'Credit Memo'
            AND
                `sl`.`with_penalty` = 'No'")->row()->amount;

        return $query;
    }

    public function total_payableDuedate($tenant_id, $due_date)
    {
        $query = $this->db->query(
            "SELECT
                round(sum(IFNULL(`actual_amt` ,0)), 2) as `total`
            FROM
                `invoicing`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `due_date` = '$due_date'
            AND
                `status` != 'Paid'
            AND
                (`charges_type` != 'Basic' && `charges_type` != 'Discount' && `charges_type` != 'Rent Incrementation')
            LIMIT 1 ")->row()->total;

        return $query;
    }

    public function get_totalAdvance($tenant_id, $contract_no)
    {
        $query = $this->db->query(
            "SELECT 
                ROUND(  `tl`.`debit` - (
                SELECT IFNULL( SUM(  `sl`.`credit` ) , 0 )
                FROM  `ledger`  `sl`
                WHERE  `sl`.`ref_no` =  `tl`.`ref_no` ) , 2 ) AS  `advance`,  
                `tl`.`transaction_date`, 
                `tl`.`posting_date`
            FROM
                `ledger`  `tl`
            WHERE
                `tl`.`tenant_id` =  '$tenant_id'
            AND
                `tl`.`document_type` =  'Advance Payment'
            AND
                ROUND(  `tl`.`debit` - (
                SELECT IFNULL( SUM(  `sl`.`credit` ) , 0 )
                FROM  `ledger`  `sl`
                WHERE  `sl`.`ref_no` =  `tl`.`ref_no` ) , 2 ) > 0
            ORDER BY tl.posting_date ASC
        ");
        return $query->result_array();
    }

    public function get_tenantSoa($trade_name)
    {
        if ($this->_user_group == '0' || $this->_user_group == NULL)
        {
            /*$query = $this->db->query(
                "SELECT
                    `sf`.`tenant_id`,
                    `sf`.`soa_no`,
                    `sf`.`file_name`,
                    `s`.`collection_date`
                FROM
                    `soa_file` `sf`,
                    `soa` `s`,
                    `tenants` `t`,
                    `prospect` `p`
                WHERE
                    `sf`.`tenant_id` = `t`.`tenant_id`
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `p`.`trade_name` = '$trade_name'
                AND
                    `s`.`tenant_id` = `sf`.`tenant_id`
                AND
                    `sf`.`soa_no` = `s`.`soa_no`
                GROUP BY
                    `soa_no`
                ORDER BY
                    `collection_date` DESC
                ");*/

                $query = $this->db->query(
                    "SELECT
                        sf.tenant_id,
                        sf.soa_no,
                        sf.file_name,
                        IFNULL(sf.collection_date, s.collection_date) as collection_date
                    FROM
                        soa_file sf
                    LEFT JOIN 
                        (SELECT distinct(prospect_id) as prospect_id, tenant_id, store_code  FROM tenants) t 
                        ON t.tenant_id = sf.tenant_id
                    LEFT JOIN 
                        prospect p on p.id = t.prospect_id
                    LEFT JOIN
                        soa s
                        ON sf.soa_no = s.soa_no AND sf.tenant_id = s.tenant_id
                    WHERE 
                         p.trade_name = '$trade_name'
                    GROUP BY
                        sf.soa_no
                    ORDER BY
                        collection_date DESC, sf.id DESC");

            // dump($this->db->last_query());
            return $query->result_array();
        }
        else
        {

            $store_code = $this->session->userdata('store_code');
            $query = $this->db->query(
                "SELECT
                    sf.tenant_id,
                    sf.soa_no,
                    sf.file_name,
                    IFNULL(sf.collection_date, s.collection_date) as collection_date
                FROM
                    soa_file sf
                LEFT JOIN 
                    (SELECT distinct(prospect_id) as prospect_id, tenant_id, store_code  FROM tenants) t 
                    ON t.tenant_id = sf.tenant_id
                LEFT JOIN 
                    prospect p on p.id = t.prospect_id
                LEFT JOIN
                    soa s
                    ON sf.soa_no = s.soa_no AND sf.tenant_id = s.tenant_id
                WHERE 
                     p.trade_name = '$trade_name'
                AND
                    t.store_code = '$store_code'
                GROUP BY
                    sf.soa_no
                ORDER BY
                    collection_date DESC, sf.id DESC");

            // dump($this->db->last_query());
            return $query->result_array();
        }
    }


    public function get_latestSOANo($tenant_id)
    {
        return $this->db->query("SELECT MAX(`soa_no`) AS `soa_no` FROM `soa_file` WHERE `tenant_id` = '$tenant_id' LIMIT 1")->row()->soa_no;
    }


    public function get_previousBilling($tenant_id, $contract_no)
    {
        $query = $this->db->query(
            "SELECT
                `sf`.`tenant_id`,
                IFNULL(`sf`.`amount_payable`, 0) as `amount_payable`,
                IFNULL((SELECT SUM(IFNULL(`p`.`amount_paid`, 0)) FROM payment p where `p`.`tenant_id` = `sf`.`tenant_id` AND `sf`.`soa_no` = `p`.`soa_no`), 0) as `amount_paid`
            FROM
                soa_file sf
            WHERE
                `sf`.`tenant_id` = '$tenant_id'
            AND
                (SELECT max(`soa_no`) FROM `soa_file` where `tenant_id` = '$tenant_id') = `sf`.`soa_no`
        ");

        return $query->result_array();
    }

    public function get_soaDocs($trade_name)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `t`.`contract_no`,
                `p`.`corporate_name`,
                `p`.`trade_name`,
                `t`.`tenant_id`,
                `s`.`soa_no`,
                `sf`.`billing_period`,
                ROUND(sum(IFNULL(`inv`.`balance` ,0)) + IFNULL((SELECT SUM(`penalty`.`balance`) FROM `monthly_penalty` `penalty` WHERE `penalty`.`soa_no` = `s`.`soa_no`) ,0), 2) AS `total_amount`
            FROM
                `tenants`  `t`,
                `prospect`  `p`,
                `soa`  `s`,
                `soa_file` `sf`,
                `invoicing` `inv`
            WHERE
                `p`.`trade_name` =  '$trade_name'
            AND
                `t`.`tenant_id` =  `s`.`tenant_id`
            AND
                `t`.`prospect_id` =  `p`.`id`
            AND
                `sf`.`soa_no` = `s`.`soa_no`
            AND
                `s`.`soa_no` = (SELECT MAX(`soa_no`) FROM  `soa` WHERE  `tenant_id` =  `t`.`tenant_id`)
            AND
                `s`.`invoice_id` = `inv`.`id`
            AND
                `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
            LIMIT 1
        ");

        return $query->result_array();
    }



    public function cfs_soaDocs($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `t`.`contract_no`,
                `p`.`corporate_name`,
                `p`.`trade_name`,
                `t`.`tenant_id`,
                `s`.`soa_no`,
                `sf`.`billing_period`,
                ROUND(sum(IFNULL(`inv`.`balance` ,0)) + IFNULL((SELECT SUM(`penalty`.`balance`) FROM `monthly_penalty` `penalty` WHERE `penalty`.`soa_no` = `s`.`soa_no`) ,0), 2) AS `total_amount`
            FROM
                `tenants`  `t`,
                `prospect`  `p`,
                `soa`  `s`,
                `soa_file` `sf`,
                `invoicing` `inv`
            WHERE
                `t`.`tenant_id` =  '$tenant_id'
            AND
                (`t`.`status` =  'Active' || `t`.`status` = 'Terminated')
            AND
                `t`.`tenant_id` =  `s`.`tenant_id`
            AND
                `t`.`prospect_id` =  `p`.`id`
            AND
                `sf`.`soa_no` = `s`.`soa_no`
            AND
                `s`.`soa_no` = (SELECT MAX(`soa_no`) FROM  `soa` WHERE  `tenant_id` =  `t`.`tenant_id`)
            AND
                `s`.`invoice_id` = `inv`.`id`
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function generate_primaryDetails($trade_name)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `t`.`contract_no`,
                `p`.`corporate_name`,
                `p`.`trade_name`,
                `t`.`tenant_id`
            FROM
                `tenants`  `t`,
                `prospect`  `p`
            WHERE
                `p`.`trade_name` =  '$trade_name'
            AND
                `t`.`prospect_id` =  `p`.`id`
            AND
                `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
        ");
        return $query->result_array();
    }



    public function get_unclearedPayment($tenant_id)
    {
        /* OLD QUERY
        "SELECT
                `sl`.`id`,
                `sl`.`ref_no`,
                `sl`.`document_type`,
                `acc`.`gl_account`,
                `sl`.`doc_no`,
                `sl`.`status`,
                `sl`.`posting_date`,
                ROUND(SUM(ABS(`sl`.`credit`)) - (SELECT SUM(IFNULL(ABS(`sl1`.`debit`) ,0)) FROM `subsidiary_ledger` `sl1` WHERE `sl1`.`ref_no` = `sl`.`ref_no` AND `sl1`.`gl_accountID` = `sl`.`gl_accountID` AND (`sl1`.`status` IS NOT NULL AND `sl1`.`status` != '')), 2) AS `amount`
            FROM
                `subsidiary_ledger` `sl`,
                `gl_accounts` `acc`
            WHERE
                `sl`.`gl_accountID` = `acc`.`id`
            AND
                `sl`.`tenant_id` = '$tenant_id'
            AND
                (`sl`.`status` IS NOT NULL AND `sl`.`status` != '' AND `sl`.`status` != 'PDC')
            GROUP BY
                `sl`.`ref_no`
        "*/

 
 
        $query = $this->db->query("
            SELECT
                `sl`.`id`,
                `sl`.`ref_no`,
                `sl`.`document_type`,
                `acc`.`gl_account`,
                `sl`.`doc_no`,
                `sl`.`status`,
                `sl`.`posting_date`,
                `sl`.`due_date`,
                `sl`.`ft_ref`,
                `sl`.`gl_accountID`,
                SUM(IFNULL(sl.debit, 0)) debit,
                SUM(IFNULL(sl.credit, 0)) credit,
                ABS(SUM(IFNULL(sl.debit, 0)) + SUM(IFNULL(sl.credit, 0))) amount
            FROM
                `subsidiary_ledger` `sl`
            LEFT JOIN
                `gl_accounts` `acc`
            ON
                `sl`.`gl_accountID` = `acc`.`id`
            WHERE
                `sl`.`tenant_id` = '$tenant_id'
            AND
                (`sl`.`status` = 'AR Clearing' OR `sl`.`status` = 'RR Clearing' OR `sl`.`status` = 'URI Clearing' OR `sl`.`status` = 'Preop Clearing')
            AND 
                (`sl`.`document_type` = 'Payment' OR `sl`.`document_type` = 'Payment Adjustment')
            GROUP BY
                sl.ref_no, sl.gl_accountID, sl.ft_ref
            HAVING
                amount <> 0
            ORDER BY 
                sl.doc_no ASC, acc.gl_account DESC
        ");

        //(`sl`.`status` IS NOT NULL AND `sl`.`status` != '' AND `sl`.`status` != 'PDC')


        return $query->result_array();
    }


    public function get_internalUnclearedPayment($tenant_id) {
        /*$query = $this->db->query(
            "SELECT
                `sl`.`id`,
                `sl`.`ref_no`,
                `sl`.`document_type`,
                `acc`.`gl_account`,
                `sl`.`doc_no`,
                `sl`.`status`,
                `sl`.`posting_date`,
                `sl2`.`gl_accountID`,
                `sl2`.`status` as status2,
                `sl`.`ft_ref`,
                SUM(ABS(`sl`.`debit`)) - (SELECT SUM(IFNULL(ABS(`sl1`.`credit`) ,0)) FROM `subsidiary_ledger` `sl1` WHERE `sl1`.`ref_no` = `sl`.`ref_no` AND `sl1`.`gl_accountID` = `sl`.`gl_accountID` AND `sl1`.`ft_ref` = `sl`.`ft_ref`) AS `amount`
            FROM
                `subsidiary_ledger` `sl`
            
            LEFT JOIN
                `subsidiary_ledger` `sl2`
                ON 
                (sl2.status = 'ARNTI' 
                AND sl2.doc_no = sl.doc_no 
                AND sl2.ref_no = sl.ref_no 
                AND sl2.tenant_id = sl.tenant_id 
                AND sl2.document_type = 'Payment' 
                AND ABS(sl2.credit) = ABS(sl.debit)
                AND sl2.id <> sl.id)

            LEFT JOIN
                `gl_accounts` `acc`
                ON
                `acc`.`id` = `sl2`.`gl_accountID`

            WHERE
                `sl`.`gl_accountID` = '29'

            AND
                `sl`.`tenant_id` = '$tenant_id'
            AND
                `sl`.`document_type` = 'Payment'
            AND
                (`sl`.`status` != '' AND `sl`.`status` IS NOT NULL AND `sl`.`status` != 'ARNTI' AND `sl`.`status` != 'RR Clearing' AND `sl`.`status` != 'AR Clearing' AND `sl`.`status` != 'Preop Clearing' AND `sl`.`status` != 'URI Clearing' AND `sl`.`status` != 'PDC')
            GROUP BY
                `sl`.`id`
            HAVING 
                amount > 0
        ");*/

        $query = $this->db->query("
            SELECT 
                ip.*,
                IFNULL(ip.amount, 0) as ip_amount,
                SUM(IFNULL(ri.amount, 0)) as ri_amount,
                IFNULL(ip.amount, 0) + SUM(IFNULL(ri.amount, 0)) as amount
            FROM
                (SELECT
                    `sl`.`id`,
                    `sl`.`ref_no`,
                    `sl`.`document_type`,
                    `acc`.`gl_account`,
                    `sl`.`doc_no`,
                    `sl`.`status`,
                    `sl`.`posting_date`,
                    `sl2`.`gl_accountID`,
                    `sl2`.`status` as status2,
                    `sl`.`ft_ref`,
                    SUM(IFNULL(`sl`.debit , 0) +  IFNULL(`sl`.credit , 0)) AS amount
                    FROM
                    `subsidiary_ledger` `sl`

                LEFT JOIN
                    `subsidiary_ledger` `sl2`
                    ON 
                    (sl2.status = 'ARNTI' 
                    AND sl2.doc_no = sl.doc_no 
                    AND sl2.ref_no = sl.ref_no 
                    AND sl2.tenant_id = sl.tenant_id 
                    AND sl2.document_type IN ('Payment', 'Payment Adjustment')
                    AND ABS(sl2.credit) = ABS(sl.debit)
                    AND sl2.id <> sl.id)
                LEFT JOIN
                    `gl_accounts` `acc`
                    ON
                    `acc`.`id` = `sl2`.`gl_accountID`
                WHERE
                    `sl`.`gl_accountID` = '29'
                AND
                    `sl`.`tenant_id` = '$tenant_id'
                AND
                    `sl`.`document_type` IN ('Payment', 'Payment Adjustment')
                AND
                    (`sl`.`status` != '' AND `sl`.`status` IS NOT NULL AND `sl`.`status` != 'ARNTI' AND `sl`.`status` != 'RR Clearing' AND `sl`.`status` != 'AR Clearing' AND `sl`.`status` != 'Preop Clearing' AND `sl`.`status` != 'URI Clearing' AND `sl`.`status` != 'PDC')
                GROUP BY
                      `sl`.`doc_no`, `sl`.`ref_no`, `sl`.`ft_ref`
                HAVING 
                    amount > 0
                ) AS ip
            LEFT JOIN
                (SELECT 
                    doc_no,
                    ref_no,
                    ft_ref,
                    SUM(IFNULL(debit , 0) +  IFNULL(credit , 0)) AS amount
                FROM 
                    subsidiary_ledger
                WHERE
                    gl_accountID = '29'
                AND 
                    tenant_id = '$tenant_id'
                AND
                    IFNULL(status, '') = ''
                AND
                    document_type IN ('Payment', 'Payment Adjustment')
                GROUP BY
                    doc_no, ref_no, ft_ref
                ) AS ri
                ON  ip.doc_no <> ri.doc_no AND
                    ip.ref_no = ri.ref_no AND
                    ip.ft_ref = ri.ft_ref
                GROUP BY
                    ip.doc_no, ip.ref_no, ip.ft_ref
                HAVING 
                    amount >= 1
                ORDER BY
                    ip.posting_date ASC, ip.doc_no ASC
        ");


        return $query->result_array();
    }


    public function get_unclearedAdvance($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `gl`.`ref_no`,
                `gl`.`document_type`,
                `acc`.`gl_account`,
                `gl`.`doc_no`,
                `gl`.`posting_date`,
                ROUND(SUM(ABS(`gl`.`credit`)) - IFNULL((SELECT SUM(ABS(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND `gl1`.`gl_accountID` = `acc1`.`id` AND  `gl1`.`status` IS NOT NULL AND  `acc1`.`gl_code` = '10.20.01.01.02.01') ,0), 2) as `amount`
            FROM
                `general_ledger` `gl`,
                `gl_accounts` `acc`
            WHERE
                `gl`.`gl_accountID` = `acc`.`id`
            AND
                `gl`.`tenant_id` = '$tenant_id'
            AND
                (`acc`.`gl_code` = '10.10.01.01.03' OR `acc`.`gl_code` = '10.10.01.01.01')
            AND
                `gl`.`tag` = 'Advance'
            GROUP BY
                `gl`.`ref_no`
           HAVING
                ROUND(SUM(ABS(`gl`.`credit`)) - IFNULL((SELECT SUM(ABS(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND `gl1`.`gl_accountID` = `acc1`.`id` AND  `gl1`.`status` IS NOT NULL AND  `acc1`.`gl_code` = '10.20.01.01.02.01') ,0), 2) > 1
        ");

        return $query->result_array();
    }


    public function get_unclearedAdvanceForClearing($tenant_id, $ref_no)
    {
        $query = $this->db->query(
            "SELECT
                `gl`.`ref_no`,
                `gl`.`document_type`,
                `acc`.`gl_account`,
                `gl`.`doc_no`,
                `gl`.`posting_date`,
                ROUND(SUM(ABS(`gl`.`debit`)) - IFNULL((SELECT SUM(ABS(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND `gl1`.`gl_accountID` = `acc1`.`id` AND  `gl1`.`status` IS NOT NULL AND `acc1`.`gl_code` = '10.10.01.01.01') ,0), 2) as `amount`
            FROM
                `general_ledger` `gl`,
                `gl_accounts` `acc`
            WHERE
                `gl`.`gl_accountID` = `acc`.`id`
            AND
                `gl`.`tenant_id` = '$tenant_id'
            AND
                `acc`.`gl_code` = '10.10.01.01.01'
            AND
                `gl`.`ref_no` = '$ref_no'
            GROUP BY
                `gl`.`ref_no`
            HAVING
                ROUND(SUM(ABS(`gl`.`debit`)) - IFNULL((SELECT SUM(ABS(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND `gl1`.`gl_accountID` = `acc1`.`id` AND  `gl1`.`status` IS NOT NULL AND `acc1`.`gl_code` = '10.10.01.01.01') ,0), 2) > 1
        ");

        return $query->result_array();
    }


    public function get_storeDetails()
    {

        $user_group = $this->session->userdata('user_group');
        
        $query = $this->db->query("SELECT * FROM `stores` `s`, `leasing_users` `u` WHERE `u`.`user_group` = '$user_group' AND `u`.`user_group` = `s`.`id` LIMIT 1");

        // var_dump(last_query());
        // return $query;
        return $query->result_array();
    }


    public function delete_oldInvoice($tenant_id, $doc_no)
    {
        $this->db->query("DELETE FROM `invoicing` WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no'");
    }

    public function delete_oldPreop($tenant_id, $doc_no)
    {
        $this->db->query("DELETE FROM `tmp_preoperationcharges` WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no'");
    }

    public function post_invoice($tenant_id, $doc_no)
    {
        $query = $this->db->query("UPDATE `invoicing` SET `tag` = 'Posted' WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no'");
    }


    public function get_penalty($tenant_id, $contract_no)
    {
        $query = $this->db->query(
            "SELECT
                `tl`.`tenant_id` ,
                `tl`.`ref_no` ,
                `tl`.`description` ,
                `tl`.`debit` ,
                `tl`.`credit` ,
                `tl`.`doc_no` ,
                `tl`.`posting_date` ,
                ROUND( ( SELECT SUM( IFNULL( `sl`.`credit`, 0) )  FROM  `ledger`  `sl`  WHERE  `sl`.`ref_no` =  `tl`.`ref_no` ) , 2 ) AS  `begbal` ,
                ROUND(  IFNULL(`tl`.`credit` ,0) - ( SELECT SUM(  IFNULL(`sl`.`debit` ,0) )  FROM  `ledger`  `sl`  WHERE  `sl`.`ref_no` =  `tl`.`ref_no` ) , 2 ) AS  `balance` ,
                ROUND( ( SELECT SUM(  IFNULL(`sl`.`debit`, 0) )  FROM  `ledger`  `sl`  WHERE  `sl`.`ref_no` =  `tl`.`ref_no` ) , 2 ) AS  `amount_paid` ,
                `tl`.`due_date`
            FROM
                `ledger`  `tl`
            WHERE
                `tl`.`tenant_id` =  '$tenant_id'
            AND
                `tl`.`flag` =  'Penalty'
            AND
                ROUND(  IFNULL(`tl`.`credit` ,0) - ( SELECT SUM(  IFNULL(`sl`.`debit`, 0) )  FROM  `ledger`  `sl`  WHERE  `sl`.`ref_no` =  `tl`.`ref_no` ) , 2 ) > 0
        ");

        return $query->result_array();
    }



    public function total_perDocNo($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                ROUND(SUM(`balance`), 2) AS `total`
            FROM
                `invoicing`
            WHERE
                `doc_no` = '$doc_no'
            AND
                `tenant_id` = '$tenant_id'
            AND
                charges_type != 'Basic'
        ")->row()->total;

        return $query;
    }


    public function get_ledgerEntry($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `tl`.`ref_no`,
                `tl`.`description`,
                `tl`.`charges_type`,
                `tl`.`debit`,
                ROUND((SELECT SUM(`sl`.`credit`) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no`) ,2) as `credit`,
                ROUND(`tl`.`credit` - (SELECT SUM(`sl`.`debit`) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no`),2) AS `balance`,
                `tl`.`due_date`
            FROM
                `ledger` `tl`
            WHERE
                `tl`.`tenant_id` = '$tenant_id'
            AND
                `tl`.`doc_no` = '$doc_no'
            AND
                (`charges_type` != 'Advance Rent' OR `charges_type` != 'Construction Bond' OR `charges_type` != 'Security Deposit')
            AND
                (`tl`.`flag` != 'Penalty' OR `tl`.`flag` IS NULL)
        ");

        return $query->result_array();
    }


    public function get_expandedTaxes($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `tl`.`id`,
                `tl`.`ref_no`,
                `tl`.`description`,
                `tl`.`charges_type`,
                `tl`.`debit`
            FROM
                `ledger` `tl`
            WHERE
                `tl`.`tenant_id` = '$tenant_id'
            AND
                `tl`.`doc_no` = '$doc_no'
            AND
                (`tl`.`flag` = 'EWT')
        ");

        return $query->result_array();
    }


    public function get_ledgerData($id)
    {
        $query = $this->db->query(
            "SELECT
                `tl`.`ref_no`,
                `tl`.`due_date`,
                `tl`.`posting_date`,
                `tl`.`doc_no`,
                `tl`.`tenant_id`,
                `tl`.`contract_no`,
                `tl`.`description`,
                ROUND(IFNULL(`tl`.`credit`, 0) -  (SELECT SUM(IFNULL(`sl`.`debit`, 0)) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no`),2) AS `remaining_balance`
            FROM
                `ledger` `tl`
            WHERE
                `tl`.`id` = '$id'
        ");
        return $query->result_array();
    }


    public function get_advancePayment($tenant_id, $contract_no)
    {
        $query = $this->db->query(
            "SELECT
                `tl`.`ref_no`,
                `tl`.`description`,
                `tl`.`debit`,
                `tl`.`credit`,
                ROUND(IFNULL(`tl`.`debit`, 0) - (SELECT SUM(IFNULL(`sl`.`credit` ,0)) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no`),2) AS `balance`,
                `tl`.`due_date`
            FROM
                `ledger` `tl`
            WHERE
                `tl`.`tenant_id` = '$tenant_id'
            AND
                `tl`.`document_type` = 'Advance Payment'
        ");

        return $query->result_array();
    }


    public function updateInvoice_afterPayment($tenant_id, $doc_no, $balance, $receipt_no)
    {
        $charges_type = $this->db->query(
            "SELECT
                `charges_type`
            FROM
                `invoicing`
            WHERE
                `doc_no` = '$doc_no'
            AND
                `tenant_id` = '$tenant_id'
            LIMIT 1
        ")->row()->charges_type;


        if ($charges_type != 'Other')
        {
            $this->db->query(
                    "UPDATE
                        `invoicing`
                    SET
                        `balance` = '$balance',
                        receipt_no = '$receipt_no'
                    WHERE
                        `tenant_id` = '$tenant_id'
                    AND
                        `doc_no` = '$doc_no'
                    AND
                        `charges_type` = 'Basic/Monthly Rental'
                ");

            // if ($balance == 0)
            // {
            //     $this->db->query("UPDATE `invoicing` SET `status` = 'Paid', receipt_no = '$receipt_no' WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no' AND `charges_type` != 'Other'");
            // } else {
            //     $this->db->query("UPDATE `invoicing` SET `status` = 'Partial', receipt_no = '$receipt_no' WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no' AND `charges_type` != 'Other'");
            // }


        } else {

            if ($balance == 0)
            {
                $this->db->query(
                    "UPDATE
                        `invoicing`
                    SET
                        `balance` = '0',
                        `status` = 'Paid',
                        receipt_no = '$receipt_no'
                    WHERE
                        `tenant_id` = '$tenant_id'
                    AND
                        `doc_no` = '$doc_no'
                    AND
                        (`charges_type` = 'Other' OR `charges_type` = 'Pre Operation Charges')
                ");
            } else {
                $counter = $this->db->query("SELECT count(id) AS counter FROM invoicing WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'")->row()->counter;
                $balance = $balance / $counter;
                $this->db->query(
                    "UPDATE
                        `invoicing`
                    SET
                        `status` = 'Partial',
                        `balance` = '$balance',
                        `receipt_no` = '$receipt_no'
                    WHERE
                        `tenant_id` = '$tenant_id'
                    AND
                        `doc_no` = '$doc_no'
                    AND
                        (`charges_type` = 'Other' OR `charges_type` = 'Pre Operation Charges')
                ");
            }
        }
    }


    public function chargeDetails_payment($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `description`,
                `posting_date`,
                `due_date`,
                `charges_type`,
                ROUND(SUM(`actual_amt`), 2) AS `amount`,
                ROUND(SUM(`actual_amt`) - SUM(`balance`), 2) AS `amount_paid`,
                ROUND(SUM(`balance`), 2) AS `balance`
            FROM
                `invoicing`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `doc_no` = '$doc_no'
            GROUP BY
                `doc_no`
        ");

        return $query->result_array();
    }


    public function gl_chargesDetails($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `gl`.`id`,
                `gl`.`doc_no`,
                `gl`.`posting_date`,
                `gl`.`gl_accountID`,
                `gl`.`due_date`,
                `gl`.`ref_no`,
                `tag`,
                `gl`.`debit` AS `amount`,
                 ROUND(IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND ((`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16') OR (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.01.01' OR `acc1`.`gl_code` = '10.10.01.01.03')) AND `gl1`.`gl_accountID` = `acc1`.`id`), 0) ,2) AS `amount_paid`,
                ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND ((`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16') OR (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.01.01' OR `acc1`.`gl_code` = '10.10.01.01.03')) AND `gl1`.`gl_accountID` = `acc1`.`id`) ,0), 2) AS `balance`
            FROM
                `general_ledger` `gl`
            WHERE
                `gl`.`tenant_id` = '$tenant_id'
            AND
                (`gl`.`tag` = 'Basic Rent' OR `gl`.`tag` = 'Other' OR `gl`.`tag` = 'Penalty')
            AND
                `gl`.`doc_no` = '$doc_no'
            AND
                ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND ((`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16') OR (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.01.01' OR `acc1`.`gl_code` = '10.10.01.01.03')) AND `gl1`.`gl_accountID` = `acc1`.`id`) ,0), 2) > 0
            "
        );

        return $query->result_array();
    }


    public function tenants_perYear()
    {
        $query="";

        if ($this->_user_group == '0' || $this->_user_group == '' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                "SELECT
                    EXTRACT( YEAR FROM `opening_date` ) AS `year`,
                    (SELECT COUNT( `tenant_id` ) FROM `tenants` WHERE `tenancy_type` = 'Long Term' AND  EXTRACT( YEAR FROM `opening_date` ) = `year` ) AS lterm_count,
                    (SELECT COUNT( `tenant_id` ) FROM `tenants` WHERE `tenancy_type` = 'Short Term' AND  EXTRACT( YEAR FROM `opening_date` ) = `year`) AS sterm_count
                FROM
                    `tenants`
                GROUP BY
                    `YEAR`
            ");
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    EXTRACT( YEAR FROM `opening_date` ) AS `year`,
                    (SELECT COUNT( `t`.`tenant_id` ) FROM `tenants` `t`, `stores` `s`, `leasing_users` `u` WHERE `t`.`tenancy_type` = 'Long Term' AND  EXTRACT( YEAR FROM `t`.`opening_date` ) = `year` AND `s`.`id` = `u`.`user_group` AND `s`.`store_code` = `t`.`store_code` AND `u`.`user_group` = '$this->_user_group' AND `u`.`id` = '$this->_user_id') AS lterm_count,
                    (SELECT COUNT( `t`.`tenant_id` ) FROM `tenants` `t`, `stores` `s`, `leasing_users` `u` WHERE `t`.`tenancy_type` = 'Short Term' AND  EXTRACT( YEAR FROM `t`.`opening_date` ) = `year` AND `s`.`id` = `u`.`user_group` AND `s`.`store_code` = `t`.`store_code` AND `u`.`user_group` = '$this->_user_group' AND `u`.`id` =  '$this->_user_id') AS sterm_count
                FROM
                    `tenants`
                GROUP BY
                    `YEAR`
            ");
        }

        return $query->result_array();
    }


    public function tenants_perAreaClassification()
    {
        $query = "";

        if ($this->_user_group == '0' || $this->_user_group == '' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                "SELECT
                    `ac`.`classification`,
                    (SELECT
                        COUNT(`t`.`tenant_id`)
                    FROM
                        `tenants` `t`,
                        `prospect` `p`,
                        `location_code` `lc`
                    WHERE
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `t`.`locationCode_id` = `lc`.`id`
                    AND
                        `lc`.`area_classification` = `ac`.`classification`
                    AND
                        `lc`.`status` = 'Active') AS `tenantCount`
                FROM
                     `area_classification` `ac`

            ");
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `ac`.`classification`,
                    (SELECT
                        COUNT(`t`.`tenant_id`)
                    FROM
                        `tenants` `t`,
                        `prospect` `p`,
                        `location_code` `lc`,
                        `floors` `f`,
                        `stores` `s`
                    WHERE
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `t`.`locationCode_id` = `lc`.`id`
                    AND
                        `lc`.`area_classification` = `ac`.`classification`
                    AND
                        `lc`.`status` = 'Active'
                    AND
                        `lc`.`floor_id` = `f`.`id`
                    AND
                        `f`.`store_id` = `s`.`id`
                    AND
                        `s`.`id` = '$this->_user_group') AS `tenantCount`
                FROM
                     `area_classification` `ac`
            ");
        }

        return $query->result_array();

    }



    public function tenants_perAreaType()
    {
        $query = "";

        if ($this->_user_group == '0' || $this->_user_group == '' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                "SELECT
                    `at`.`type` as `area_type`,
                    (SELECT
                        COUNT(`t`.`tenant_id`)
                    FROM
                        `tenants` `t`,
                        `prospect` `p`,
                        `location_code` `lc`
                    WHERE
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `t`.`locationCode_id` = `lc`.`id`
                    AND
                        `lc`.`area_type` = `at`.`type`
                    AND
                        `lc`.`status` = 'Active') AS `tenantCount`
                FROM
                     `area_type` `at`

            ");
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `at`.`type` as `area_type`,
                    (SELECT
                        COUNT(`t`.`tenant_id`)
                    FROM
                        `tenants` `t`,
                        `prospect` `p`,
                        `location_code` `lc`,
                        `floors` `f`,
                        `location_slot` `ls`,
                        `stores` `s`
                    WHERE
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `t`.`locationCode_id` = `lc`.`id`
                    AND
                        `lc`.`area_type` = `at`.`type`
                    AND
                        `lc`.`status` = 'Active'
                    AND
                        `lc`.`slots_id` = `ls`.`id`
                    AND
                        `ls`.`floor_id` = `f`.`id`
                    AND
                        `f`.`store_id` = `s`.`id`
                    AND
                        `s`.`id` = '$this->_user_group') AS `tenantCount`
                FROM
                     `area_type` `at`
            ");
        }

        return $query->result_array();
    }


    public function tenants_perLesseeType()
    {
        $query = "";

        if ($this->_user_group == '0' || $this->_user_group == '' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                "SELECT
                    `lt`.`leasee_type`,
                    (SELECT
                        COUNT(`t`.`tenant_id`)
                    FROM `tenants` `t`,
                        `prospect` `p`,
                        `leasee_type` `lt1`
                    WHERE
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `p`.`lesseeType_id` = `lt1`.`id`
                    AND
                        `lt1`.`id` = `lt`.`id`) AS `tenantCount`
                FROM
                    `leasee_type` `lt`
                ");
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `lt`.`leasee_type`,
                    (SELECT
                        COUNT(`t`.`tenant_id`)
                    FROM `tenants` `t`,
                        `prospect` `p`,
                        `leasee_type` `lt1`,
                        `location_code` `lc`,
                        `floors` `f`,
                        `stores` `s`
                    WHERE
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `p`.`lesseeType_id` = `lt1`.`id`
                    AND
                        `lt1`.`id` = `lt`.`id`
                    AND
                        `t`.`locationCode_id` = `lc`.`id`
                    AND
                        `lc`.`status` = 'Active'
                    AND
                        `lc`.`floor_id` = `f`.`id`
                    AND
                        `f`.`store_id` = `s`.`id`
                    AND
                        `s`.`id` = '$this->_user_group') AS `tenantCount`
                FROM
                    `leasee_type` `lt`
                ");
        }

        return $query->result_array();
    }



    public function admin_tenantDetails($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `p`.`trade_name`,
                `t`.`tin`,
                `t`.`tenant_id`,
                `t`.`contract_no`,
                `t`.`rental_type`,
                `p`.`corporate_name`,
                `p`.`address`,
                `t`.`tenancy_type`
            FROM
                `tenants` `t`,
                `prospect` `p`
            WHERE
                `t`.`tenant_id` = '$tenant_id'
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`prospect_id` = `p`.`id`
            LIMIT 1
        ");

        return $query->result_array();
    }



    public function get_tenantDetails($trade_name, $tenancy_type)
    {

        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query = $this->db->query(
                "SELECT
                    `p`.`trade_name`,
                    `t`.`tin`,
                    `t`.`tenant_id`,
                    `t`.`contract_no`,
                    `t`.`tenant_type`,
                    `t`.`rental_type`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `t`.`tenancy_type`
                FROM
                    `tenants` `t`,
                    `prospect` `p`
                WHERE
                    `p`.`trade_name` = '$trade_name'
                AND
                    (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                AND
                    `t`.`prospect_id` = `p`.`id`
                LIMIT 1
            ");

            return $query->result_array();
        }
        else
        {

            $query = $this->db->query(
                "SELECT
                    `p`.`trade_name`,
                    `t`.`tin`,
                    `t`.`tenant_id`,
                    `t`.`tenant_type`,
                    `t`.`contract_no`,
                    `t`.`rental_type`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `t`.`tenancy_type`
                FROM
                    `tenants` `t`,
                    `prospect` `p`
                WHERE
                    `p`.`trade_name` = '$trade_name'
                AND
                    (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
                LIMIT 1
            ");


            return $query->result_array();
        }


    }

    public function get_dataForCreditMemo($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `tl`.`id`,
                `tl`.`ref_no`,
                `tl`.`posting_date`,
                `tl`.`doc_no`,
                `tl`.`tenant_id`,
                `tl`.`contract_no`,
                `tl`.`description`,
                ROUND(`tl`.`credit` - (SELECT SUM(`sl`.`debit`) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no`),2) AS `balance`,
                `tl`.`due_date`
            FROM
                `ledger` `tl`
            WHERE
                `tl`.`tenant_id` = '$tenant_id'
            AND
                (ROUND(`tl`.`credit` - (SELECT SUM(`sl`.`debit`) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no`),2) > 0)
            AND
                (`charges_type` = 'Basic' OR `charges_type` = 'Other')
            AND
                `tl`.`doc_no` IN
                (
                    SELECT
                        `inv`.`doc_no`
                    FROM
                        `invoicing` `inv`
                    WHERE
                        `inv`.`id` NOT IN (SELECT invoice_id FROM soa)
                    AND
                        `inv`.`tag` = 'Posted' GROUP BY `inv`.`doc_no`
                )
            GROUP BY `tl`.`ref_no`
        ");

        return $query->result_array();
    }


    public function SOA_done($tenant_id)
    {
        // $query = $this->db->query(
        //     "SELECT
        //         `tl`.`id`,
        //         `tl`.`ref_no`,
        //         `tl`.`posting_date`,
        //         `tl`.`doc_no`,
        //         `tl`.`tenant_id`,
        //         `tl`.`contract_no`,
        //         `tl`.`description`,
        //         ROUND((SELECT SUM(IFNULL(`sl`.`debit`, 0)) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no`) ,2) as `debit`,
        //         ROUND((SELECT SUM(ABS(IFNULL(`sl`.`credit` ,0))) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no`) ,2) as `credit`,
        //         ROUND(IFNULL(`tl`.`debit`, 0) - (SELECT SUM(IFNULL(`sl`.`credit`, 0)) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no`),2) AS `balance`,
        //         `tl`.`due_date`
        //     FROM
        //         `ledger` `tl`
        //     WHERE
        //         `tl`.`tenant_id` = '$tenant_id'
        //     AND
        //         (ROUND(IFNULL(`tl`.`debit`, 0) - (SELECT SUM(IFNULL(`sl`.`credit` ,0)) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no`),2) > 0)
        //     AND
        //         (`charges_type` = 'Basic' OR `charges_type` = 'Other' OR `charges_type` = 'Pre Operation Charges')
        //     AND
        //         `tl`.`doc_no` IN
        //         (
        //             SELECT
        //                 `inv`.`doc_no`
        //             FROM
        //                 `invoicing` `inv`
        //             WHERE
        //                 `inv`.`id` IN (SELECT invoice_id FROM soa)
        //             AND
        //                 `inv`.`tag` = 'Posted' GROUP BY `inv`.`doc_no`
        //         )
        //     GROUP BY `tl`.`ref_no`
        // ");



        $query = $this->db->query(
            "SELECT
                `gl`.`id`,
                `gl`.`ref_no`,
                `gl`.`posting_date`,
                `gl`.`doc_no`,
                `gl`.`due_date`,
                (CASE
                        WHEN
                            `gl`.`tag` = 'Basic Rent'
                        THEN
                            'Basic Rent'
                        ELSE
                            'Other Charges'
                    END) AS `description`,
                ROUND((SELECT SUM(IFNULL(`gl1`.`debit`, 0)) FROM `general_ledger` `gl1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `gl1`.`bank_code` IS NULL AND (`gl1`.`tag` = 'Basic Rent' OR `gl1`.`tag` = 'Other')) ,2) as `debit`,
                IFNULL((SELECT SUM(`gl1`.`debit`) FROM `general_ledger` `gl1`, `gl_accounts` `ac` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `gl1`.`gl_accountID` = `ac`.`id` AND (`ac`.`gl_code` = '10.10.01.01.02' OR `ac`.`gl_code` = '10.20.01.01.02.01' OR `ac`.`gl_code` = '10.10.01.03.11' OR `ac`.`gl_code` = '10.10.01.03.11.01' OR `ac`.`gl_code` = '10.10.01.03.11.02' OR `ac`.`gl_code` = '10.10.01.03.07.01')), 0) as `credit`,
                (ROUND((SELECT SUM(IFNULL(`gl1`.`debit`, 0)) FROM `general_ledger` `gl1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `gl1`.`bank_code` IS NULL AND (`gl1`.`tag` = 'Basic Rent' OR `gl1`.`tag` = 'Other')) ,2) - IFNULL((SELECT SUM(`gl1`.`debit`) FROM `general_ledger` `gl1`, `gl_accounts` `ac` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `gl1`.`gl_accountID` = `ac`.`id` AND (`ac`.`gl_code` = '10.10.01.01.02' OR `ac`.`gl_code` = '10.20.01.01.02.01' OR `ac`.`gl_code` = '10.10.01.03.11' OR `ac`.`gl_code` = '10.10.01.03.11.01' OR `ac`.`gl_code` = '10.10.01.03.11.02' OR `ac`.`gl_code` = '10.10.01.03.07.01')), 0)) as `balance`
            FROM
                `general_ledger` `gl`
            WHERE
                `gl`.`tenant_id` = '$tenant_id'
            AND
                `gl`.`doc_no` IN
                (
                    SELECT
                        `inv`.`doc_no`
                    FROM
                        `invoicing` `inv`
                    WHERE
                        `inv`.`id` IN (SELECT invoice_id FROM soa)
                    AND
                        `inv`.`tag` = 'Posted' GROUP BY `inv`.`doc_no`
                )
            AND
                (ROUND((SELECT SUM(IFNULL(`gl1`.`debit`, 0)) FROM `general_ledger` `gl1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `gl1`.`bank_code` IS NULL AND (`gl1`.`tag` = 'Basic Rent' OR `gl1`.`tag` = 'Other')) ,2) - IFNULL((SELECT SUM(`gl1`.`debit`) FROM `general_ledger` `gl1`, `gl_accounts` `ac` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `gl1`.`gl_accountID` = `ac`.`id` AND (`ac`.`gl_code` = '10.10.01.01.02' OR `ac`.`gl_code` = '10.20.01.01.02.01' OR `ac`.`gl_code` = '10.10.01.03.11' OR `ac`.`gl_code` = '10.10.01.03.11.01' OR `ac`.`gl_code` = '10.10.01.03.11.02' OR `ac`.`gl_code` = '10.10.01.03.07.01')), 0)) > 0
            GROUP BY
                `gl`.`ref_no`

            ");

        return $query->result_array();
    }



    public function dueDate_validation($tenant_id, $due_date, $charges_type)
    {
        // if ($charges_type != 'Other')
        // {
        //     $query = $this->db->query(
        //         "SELECT
        //             `id`
        //         FROM
        //             `invoicing`
        //         WHERE

        //             '$due_date' <= `due_date` + INTERVAL 26 DAY
        //         AND
        //             `tenant_id` = '$tenant_id'
        //         AND
        //             `charges_type` = '$charges_type'
        //     ");

        //     return $query->num_rows();
        // }
    }




    public function get_withPenalty($description)
    {
        return $this->db->query("SELECT `with_penalty` FROM `charges_setup` WHERE `description` = '$description' LIMIT 1")->row()->with_penalty;
    }


    public function collectionDate_validation($tenant_id, $collection_date)
    {
        // $query = $this->db->query(
        //     "SELECT
        //         id
        //     FROM
        //         `soa`
        //     WHERE
        //         '$collection_date' <= `collection_date` + INTERVAL 15 DAY
        //     AND
        //         `tenant_id` = '$tenant_id'
        //     AND
        //         `status` != 'Upon Signing of Notice'
        // ");

        // return $query->num_rows();

        return 0;
    }


    public function get_invOtherforCreditMemo($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `id`,
                round(sum(`actual_amt`), 2) as `total_amount`,
                `doc_no`,
                `posting_date`,
                `due_date`
            FROM
                `invoicing`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `charges_type` = 'Other'
            AND
                `tag` = 'Posted'
            AND
                `id` NOT IN (SELECT `invoice_id` FROM `soa`)
            GROUP BY
                `doc_no`
        ");

        return $query->result_array();
    }


    public function get_invoiceHistory($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `doc_no`,
                `charges_type`,
                `posting_date`,
                `due_date`,
                 ROUND(SUM(`actual_amt`) ,2) AS `prev_amount`,
                 ROUND(SUM(`actual_amt`) - SUM(`balance`), 2) AS `paid_amount`,
                 ROUND(SUM(`balance`) ,2) AS `balance`
            FROM
                `invoicing`
            WHERE
                `tenant_id` = '$tenant_id'
            GROUP BY
                `doc_no`
        ");

        return $query->result_array();
    }


    public function get_penaltyHistory($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `id`,
                `doc_no`,
                `due_date`,
                `amount`,
                ROUND(`amount` - `balance`) AS `amount_paid`,
                `balance`
            FROM
                `monthly_penalty`
            WHERE
                `tenant_id` = '$tenant_id'
        ");

        return $query->result_array();
    }

    public function get_paymentScheme($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `id`,
                `receipt_no`,
                `tender_typeCode`,
                `tender_typeDesc`,
                `amount_paid`,
                `billing_period`,
                `check_no`,
                `check_date`,
                `payee`,
                `supp_doc`,
                `receipt_doc`
            FROM
                `payment_scheme`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                ROUND(`amount_paid`) >= 1 
        ");

        return $query->result_array();
    }

    public function get_preparedBy()
    {
        $query = $this->db->query(
                "SELECT
                    CONCAT(`first_name`, ' ', `last_name`) AS `preparedby`
                FROM
                    `leasing_users`
                WHERE
                    `id` = '" . $this->_user_id . "'
                LIMIT 1
            ")->row()->preparedby;

        return $query;
    }


    public function get_creditMemo()
    {
        $query;
        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query = $this->db->query(
                "SELECT
                    `cd`.`tenant_id`,
                    `cd`.`reason`,
                    `cd`.`date_modified`,
                    `cd`.`original_amount`,
                    `cd`.`positive_amount`,
                    `cd`.`negative_amount`,
                    `cd`.`total_amount`,
                    CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `modified_by`
                FROM
                    `credit_memo` `cd`,
                    `leasing_users` `u`
                WHERE
                    `cd`.`modified_by` = `u`.`id`
            ");

        } else {
            $query = $this->db->query(
                "SELECT
                    `cd`.`tenant_id`,
                    `cd`.`reason`,
                    `cd`.`date_modified`,
                    `cd`.`original_amount`,
                    `cd`.`positive_amount`,
                    `cd`.`negative_amount`,
                    `cd`.`total_amount`,
                    CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `modified_by`
                FROM
                    `credit_memo` `cd`,
                    `leasing_users` `u`
                WHERE
                    `cd`.`modified_by` = `u`.`id`
                AND
                    `cd`.`group` = '$this->_user_group'
            ");
        }

        return $query->result_array();

    }

    public function basic_invoiceCreditMemo($tenant_id, $due_date, $doc_no, $balance, $amount)
    {
        $this->db->query(
            "UPDATE
                `invoicing`
            SET
                `balance` = '$balance',
                `expected_amt` = (`expected_amt` - '$amount'),
                `actual_amt` = (`actual_amt` - '$amount'),
                `status` = 'Credited'
            WHERE
                `due_date` = '$due_date'
            AND
                `doc_no` = '$doc_no'
            AND
                `tenant_id` = '$tenant_id'
            AND
                `charges_type` = 'Basic/Monthly Rental'
        ");
    }

    public function basic_invoiceDebitMemo($tenant_id, $due_date, $doc_no)
    {
        $this->db->query(
            "UPDATE
                `invoicing`
            SET
                `status` = 'Debited'
            WHERE
                `due_date` = '$due_date'
            AND
                `doc_no` = '$doc_no'
            AND
                `tenant_id` = '$tenant_id'
            AND
                `charges_type` = 'Basic/Monthly Rental'
        ");
    }

    public function vat_invoiceDebitMemo($tenant_id, $due_date, $doc_no, $amount)
    {
        $this->db->query(
            "UPDATE
                `invoicing`
            SET
                `expected_amt` = (`expected_amt` + '$amount'),
                `status` = 'Debited'
            WHERE
                `due_date` = '$due_date'
            AND
                `doc_no` = '$doc_no'
            AND
                `tenant_id` = '$tenant_id'
            AND
                `description` = 'VAT Output'
        ");
    }


    public function wht_invoiceDebitMemo($tenant_id, $due_date, $doc_no, $amount)
    {
        $this->db->query(
            "UPDATE
                `invoicing`
            SET
                `expected_amt` = (`expected_amt` + '$amount'),
                `status` = 'Debited'
            WHERE
                `due_date` = '$due_date'
            AND
                `doc_no` = '$doc_no'
            AND
                `tenant_id` = '$tenant_id'
            AND
                `description` = 'Creditable Witholding Taxes'
        ");
    }



    public function rr_creditMemo($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `gl`.`tenant_id`,
                `gl`.`ref_no`,
                `gl`.`doc_no`,
                `gl`.`due_date`,
                `gl`.`posting_date`,
                `gl`.`company_code`,
                `gl`.`department_code`,
                `gl`.`debit`,
                `gl`.`credit`,
                `ac`.`gl_account`
            FROM
                `general_ledger` `gl`,
                `gl_accounts` `ac`
            WHERE
                `gl`.`tenant_id` = '$tenant_id'
            AND
                `gl`.`doc_no` = '$doc_no'
            AND
                `gl`.`gl_accountID` = `ac`.`id`
        ");

        return $query->result_array();
    }


    public function other_invoiceCreditMemo($description, $due_date, $doc_no, $amount)
    {
        $this->db->query(
            "UPDATE
                `invoicing`
            SET
                `expected_amt` = (`expected_amt` - '$amount'),
                `actual_amt` = (`actual_amt` - '$amount'),
                `balance` = (`balance` - '$amount')
            WHERE
                `description` = '$description'
            AND
                `due_date` = '$due_date'
            AND
                `doc_no` = '$doc_no'
        ");
    }


    public function other_invoiceDebitMemo($description, $due_date, $doc_no, $amount)
    {
        $this->db->query(
            "UPDATE
                `invoicing`
            SET
                `expected_amt` = (`expected_amt` + '$amount'),
                `actual_amt` = (`actual_amt` + '$amount'),
                `balance` = (`balance` + '$amount')
            WHERE
                `description` = '$description'
            AND
                `due_date` = '$due_date'
            AND
                `doc_no` = '$doc_no'
        ");
    }


    public function get_amendments($flag)
    {
        $query;

        if ($this->session->userdata('user_type') == 'Administrator')
        {
            if ($flag == 'Long Term') {
                $query = $this->db->query(
                    "SELECT
                        `ca`.`tenant_id`,
                        `ca`.`contract_no`,
                        `p`.`trade_name`,
                        `s`.`store_name`,
                        `ca`.`reason`,
                        `ca`.`date_modified`,
                        CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `modified_by`
                    FROM
                        `contract_amendment` `ca`,
                        `ltenant` `t`,
                        `lprospect` `p`,
                        `price_locationcode` `pl`,
                        `price_floor` `pf`,
                        `floors` `f`,
                        `stores` `s`,
                        `leasing_users` `u`
                    WHERE
                        `ca`.`tenant_id` = `t`.`tenant_id`
                    AND
                        `ca`.`flag` = 'Long Term'
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `p`.`locationCode_id` = `pl`.`id`
                    AND
                        `pl`.`price_floor_id` = `pf`.`id`
                    AND
                        `pf`.`floor_id` = `f`.`id`
                    AND
                        `f`.`store_id` = `s`.`id`
                    AND
                        `u`.`id` = `ca`.`modified_by`
                    GROUP BY
                        `ca`.`contract_no`
                ");
            } else {
                $query = $this->db->query(
                    "SELECT
                        `ca`.`tenant_id`,
                        `ca`.`contract_no`,
                        `p`.`trade_name`,
                        `s`.`store_name`,
                        `ca`.`reason`,
                        `ca`.`date_modified`,
                        CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `modified_by`
                    FROM
                        `contract_amendment` `ca`,
                        `ltenant` `t`,
                        `sprospect` `p`,
                        `exhibit_rates` `ex`,
                        `price_floor` `pf`,
                        `floors` `f`,
                        `stores` `s`,
                        `leasing_users` `u`
                    WHERE
                        `ca`.`tenant_id` = `t`.`tenant_id`
                    AND
                        `ca`.`flag` = 'Short Term'
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `p`.`exhibitrate_id` = `ex`.`id`
                    AND
                        `ex`.`price_floor_id` = `pf`.`id`
                    AND
                        `pf`.`floor_id` = `f`.`id`
                    AND
                        `f`.`store_id` = `s`.`id`
                    AND
                        `u`.`id` = `ca`.`modified_by`
                    GROUP BY
                        `ca`.`contract_no`
                ");
            }

        } else {
            if ($flag == 'Long Term') {
                $query = $this->db->query(
                    "SELECT
                        `ca`.`tenant_id`,
                        `ca`.`contract_no`,
                        `p`.`trade_name`,
                        `s`.`store_name`,
                        `ca`.`reason`,
                        `ca`.`date_modified`,
                        CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `modified_by`
                    FROM
                        `contract_amendment` `ca`,
                        `ltenant` `t`,
                        `lprospect` `p`,
                        `price_locationcode` `pl`,
                        `price_floor` `pf`,
                        `floors` `f`,
                        `stores` `s`,
                        `leasing_users` `u`
                    WHERE
                        `ca`.`tenant_id` = `t`.`tenant_id`
                    AND
                        `ca`.`flag` = 'Long Term'
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `p`.`locationCode_id` = `pl`.`id`
                    AND
                        `pl`.`price_floor_id` = `pf`.`id`
                    AND
                        `pf`.`floor_id` = `f`.`id`
                    AND
                        `f`.`store_id` = `s`.`id`
                    AND
                        `s`.`id` = '" . $this->session->userdata('user_group') . "'
                    AND
                        `u`.`id` = `ca`.`modified_by`
                    GROUP BY
                        `ca`.`contract_no`
                ");
            } else {
                $query = $this->db->query(
                    "SELECT
                        `ca`.`tenant_id`,
                        `ca`.`contract_no`,
                        `p`.`trade_name`,
                        `s`.`store_name`,
                        `ca`.`reason`,
                        `ca`.`date_modified`,
                        CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `modified_by`
                    FROM
                        `contract_amendment` `ca`,
                        `ltenant` `t`,
                        `sprospect` `p`,
                        `exhibit_rates` `ex`,
                        `price_floor` `pf`,
                        `floors` `f`,
                        `stores` `s`,
                        `leasing_users` `u`
                    WHERE
                        `ca`.`tenant_id` = `t`.`tenant_id`
                    AND
                        `ca`.`flag` = 'Short Term'
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `p`.`exhibitrate_id` = `ex`.`id`
                    AND
                        `ex`.`price_floor_id` = `pf`.`id`
                    AND
                        `pf`.`floor_id` = `f`.`id`
                    AND
                        `f`.`store_id` = `s`.`id`
                    AND
                        `s`.`id` = '" . $this->session->userdata('user_group') . "'
                    AND
                        `u`.`id` = `ca`.`modified_by`
                    GROUP BY
                        `ca`.`contract_no`
                ");
            }
        }

        return $query->result_array();
    }



    public function get_locationSlot()
    {
        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
        {
            $query = $this->db->query(
                "SELECT
                    `ls`.`id`,
                    `ls`.`slot_no`,
                    `ls`.`tenancy_type`,
                    `ls`.`floor_area`,
                    `ls`.`rental_rate`,
                    `f`.`floor_name`,
                    `s`.`store_name`
                FROM
                    `location_slot` `ls`,
                    `stores` `s`,
                    `floors` `f`
                WHERE
                    `ls`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `S`.`id`
            ");
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `ls`.`id`,
                    `ls`.`slot_no`,
                    `ls`.`tenancy_type`,
                    `ls`.`floor_area`,
                    `ls`.`rental_rate`,
                    `f`.`floor_name`,
                    `s`.`store_name`
                FROM
                    `location_slot` `ls`,
                    `stores` `s`,
                    `floors` `f`
                WHERE
                    `ls`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `S`.`id`
                AND
                    `s`.`id` = '" . $this->_user_group . "'
            ");
        }
        return $query->result_array();

    }



    public function get_locationSlot_data($id)
    {
        $query = $this->db->query(
            "SELECT
                `ls`.`id`,
                `ls`.`slot_no`,
                `ls`.`tenancy_type`,
                `ls`.`floor_area`,
                `ls`.`rental_rate`,
                `f`.`floor_name`,
                `s`.`store_name`
            FROM
                `location_slot` `ls`,
                `stores` `s`,
                `floors` `f`
            WHERE
                `ls`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `S`.`id`
            AND
                `ls`.`id` = '$id'
        ");

        return $query->result_array();
    }



    public function get_locationCode()
    {
        $query;

        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query = $this->db->query(
                "SELECT
                    `lc`.`id`,
                    `lc`.`tenancy_type`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `f`.`model`,
                    `lc`.`location_code`,
                    `lc`.`floor_area`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`payment_mode`,
                    `lc`.`rental_rate`,
                    `lc`.`rent_period`,
                    (CASE
                        WHEN
                            `t`.`tenant_id` IS NOT NULL
                        THEN
                            'Occupied'
                        ELSE
                            'Vacant'
                    END) AS `status`

                FROM
                    `location_code` `lc`
                INNER JOIN
                    `floors` `f`
                ON
                    `f`.`id` = `lc`.`floor_id`
                INNER JOIN
                    `stores` `s`
                ON
                    `s`.`id` = `f`.`store_id`

                LEFT JOIN
                    `prospect` `p`
                ON
                    `lc`.`id` = `p`.`locationCode_id`
                LEFT JOIN
                    `tenants` `t`
                ON
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`status` =  'Active'
                AND
                    `lc`.`flag` != 'Disabled'
                GROUP BY `lc`.`id`
            ");
        } else {
            $query = $this->db->query(
                "SELECT
                    `lc`.`id`,
                    `lc`.`tenancy_type`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `f`.`model`,
                    `lc`.`location_code`,
                    `lc`.`floor_area`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`payment_mode`,
                    `lc`.`rental_rate`,
                    `lc`.`rent_period`,
                    (CASE
                        WHEN
                            `t`.`tenant_id` IS NOT NULL
                        THEN
                            'Occupied'
                        ELSE
                            'Vacant'
                    END) AS `status`

                FROM
                    `location_code` `lc`
                INNER JOIN
                    `floors` `f`
                ON
                    `f`.`id` = `lc`.`floor_id`
                INNER JOIN
                    `stores` `s`
                ON
                    `s`.`id` = `f`.`store_id`
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                LEFT JOIN
                    `prospect` `p`
                ON
                    `lc`.`id` = `p`.`locationCode_id`
                LEFT JOIN
                    `tenants` `t`
                ON
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`status` =  'Active'
                AND
                    `lc`.`flag` != 'Disabled'

                GROUP BY `lc`.`id`
            ");

        }

        return $query->result_array();
    }


    public function locationCode_occupied()
    {
        $query;

        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query = $this->db->query(
                "SELECT
                    `lc`.`id`,
                    `lc`.`tenancy_type`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lc`.`location_code`,
                    `lc`.`floor_area`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`payment_mode`,
                    `lc`.`rental_rate`,
                    `lc`.`rent_period`
                FROM
                    `location_code` `lc`,
                    `floors` `f`,
                    `stores` `s`
                WHERE
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `lc`.`flag` != 'Disabled'
                AND
                    `lc`.`id` IN
                    (SELECT

                       `hlc`.`locationCode_id`
                    FROM
                        `history_locationcode` `hlc`,
                        `prospect` `p`,
                        `tenants` `t`
                    WHERE
                        `hlc`.`locationCode_id` = `lc`.`id`
                    AND
                        `hlc`.`status` = 'Active'
                    AND
                        `p`.`locationCode_id` = `hlc`.`locationCode_id`
                    AND
                        `p`.`id` = `t`.`prospect_id`
                    AND
                        `t`.`status` = 'Active')
            ");
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `lc`.`id`,
                    `lc`.`tenancy_type`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lc`.`location_code`,
                    `lc`.`floor_area`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`payment_mode`,
                    `lc`.`rental_rate`,
                    `lc`.`rent_period`
                FROM
                    `location_code` `lc`,
                    `floors` `f`,
                    `stores` `s`
                WHERE
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `lc`.`flag` != 'Disabled'
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                AND
                    `lc`.`id` IN
                        (SELECT

                           `hlc`.`locationCode_id`
                        FROM
                            `history_locationcode` `hlc`,
                            `prospect` `p`,
                            `tenants` `t`
                        WHERE
                            `hlc`.`locationCode_id` = `lc`.`id`
                        AND
                            `hlc`.`status` = 'Active'
                        AND
                            `p`.`locationCode_id` = `hlc`.`locationCode_id`
                        AND
                            `p`.`id` = `t`.`prospect_id`
                        AND
                            `t`.`status` = 'Active')
            ");

        }

        return $query->result_array();
    }


    public function locationCode_vacant()
    {
        $query;

        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query = $this->db->query(
                "SELECT
                    `lc`.`id`,
                    `lc`.`tenancy_type`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lc`.`location_code`,
                    `lc`.`floor_area`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`payment_mode`,
                    `lc`.`rental_rate`,
                    `lc`.`rent_period`
                FROM
                    `location_code` `lc`,
                    `floors` `f`,
                    `stores` `s`
                WHERE
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `lc`.`flag` != 'Disabled'
                AND
                    `lc`.`id` NOT IN
                    (SELECT

                       `hlc`.`locationCode_id`
                    FROM
                        `history_locationcode` `hlc`,
                        `prospect` `p`,
                        `tenants` `t`
                    WHERE
                        `hlc`.`locationCode_id` = `lc`.`id`
                    AND
                        `hlc`.`status` = 'Active'
                    AND
                        `p`.`locationCode_id` = `hlc`.`locationCode_id`
                    AND
                        `p`.`id` = `t`.`prospect_id`
                    AND
                        `t`.`status` = 'Active')
            ");
        } else {
            $query = $this->db->query(
                "SELECT
                    `lc`.`id`,
                    `lc`.`tenancy_type`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lc`.`location_code`,
                    `lc`.`floor_area`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`payment_mode`,
                    `lc`.`rental_rate`,
                    `lc`.`rent_period`
                FROM
                    `location_code` `lc`,
                    `floors` `f`,
                    `stores` `s`
                WHERE
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `lc`.`flag` != 'Disabled'
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                AND
                    `lc`.`id` NOT IN
                        (SELECT

                           `hlc`.`locationCode_id`
                        FROM
                            `history_locationcode` `hlc`,
                            `prospect` `p`,
                            `tenants` `t`
                        WHERE
                            `hlc`.`locationCode_id` = `lc`.`id`
                        AND
                            `hlc`.`status` = 'Active'
                        AND
                            `p`.`locationCode_id` = `hlc`.`locationCode_id`
                        AND
                            `p`.`id` = `t`.`prospect_id`
                        AND
                            `t`.`status` = 'Active')
            ");

        }

        return $query->result_array();
    }


    public function populate_floors($store_name)
    {
        $query = $this->db->query(
            "SELECT
                `f`.`floor_name`
            FROM
                `floors` `f`,
                `stores` `s`
            WHERE
                `f`.`store_id` = `s`.`id`
            AND
                `s`.`store_name` = '$store_name'
        ");

        return $query->result_array();
    }


    public function get_slentryForPreop($description, $tenant_id)
    {
        if ($description == 'Security Deposit')
        {
            $query = $this->db->query("
                SELECT
                    ROUND(`sl`.`debit` - (SELECT SUM(IFNULL(`tl`.`credit`, 0)) FROM `ledger` `tl` WHERE `sl`.`ref_no` = `tl`.`ref_no`), 2) as `amount`,
                    `sl`.`ref_no`
                FROM
                    `ledger` `sl`
                WHERE
                    (`sl`.`charges_type` = 'Security Deposit' || `sl`.`charges_type` = 'Security Deposit - Kiosk and Cart')
                AND
                    `sl`.`tenant_id` = '$tenant_id'
                AND
                    ROUND(`sl`.`debit` - (SELECT SUM(IFNULL(`tl`.`credit`, 0)) FROM `ledger` `tl` WHERE `sl`.`ref_no` = `tl`.`ref_no`), 2) > 0
            ");

            return $query->result_array();
        }
        else
        {
            $query = $this->db->query("
                SELECT
                    ROUND(`sl`.`debit` - (SELECT SUM(IFNULL(`tl`.`credit`, 0)) FROM `ledger` `tl` WHERE `sl`.`ref_no` = `tl`.`ref_no`), 2) as `amount`,
                    `sl`.`ref_no`
                FROM
                    `ledger` `sl`
                WHERE
                    `sl`.`charges_type` = '$description'
                AND
                    `sl`.`tenant_id` = '$tenant_id'
                AND
                    ROUND(`sl`.`debit` - (SELECT SUM(IFNULL(`tl`.`credit`, 0)) FROM `ledger` `tl` WHERE `sl`.`ref_no` = `tl`.`ref_no`), 2) > 0
            ");

            return $query->result_array();
        }
    }



    public function get_glentryForPreop($description, $tenant_id)
    {
        $query;
        if ($description == 'Security Deposit')
        {
            $query = $this->db->query(
                "SELECT
                    ROUND(ABS(IFNULL(`gl`.`credit`, 0)) - (SELECT SUM(IFNULL(`gl1`.`debit`, 0)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `acc1`.`id` = `gl1`.`gl_accountID` AND `acc1`.`gl_code` = '10.20.01.01.03.12'), 2) as `amount`,
                    `gl`.`ref_no`,
                    `ac`.`gl_code`,
                    `gl`.`bank_code`,
                    `gl`.`bank_name`
                FROM
                    `general_ledger` `gl`,
                    `gl_accounts` `ac`
                WHERE
                    `ac`.`id` = `gl`.`gl_accountID`
                AND
                    `ac`.`gl_code` = '10.20.01.01.03.12'
                AND
                    `gl`.`tenant_id` = '$tenant_id'
                AND
                    ROUND(ABS(IFNULL(`gl`.`credit`, 0)) - (SELECT SUM(IFNULL(`gl1`.`debit`, 0)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `acc1`.`id` = `gl1`.`gl_accountID` AND `acc1`.`gl_code` = '10.20.01.01.03.12'), 2) > 0
            ");
        }
        else
        {

            $query = $this->db->query(
                "SELECT
                    ROUND(ABS(IFNULL(`gl`.`credit`, 0)) - (SELECT SUM(IFNULL(`gl1`.`debit`, 0)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `acc1`.`id` = `gl1`.`gl_accountID` AND `acc1`.`gl_code` = '10.20.01.01.03.10'), 2) as `amount`,
                    `gl`.`ref_no`,
                    `ac`.`gl_code`,
                    `gl`.`bank_code`,
                    `gl`.`bank_name`
                FROM
                    `general_ledger` `gl`,
                    `gl_accounts` `ac`
                WHERE
                    `ac`.`id` = `gl`.`gl_accountID`
                AND
                    `ac`.`gl_code` = '10.20.01.01.03.10'
                AND
                    `gl`.`tenant_id` = '$tenant_id'
                AND
                    ROUND(ABS(IFNULL(`gl`.`credit`, 0)) - (SELECT SUM(IFNULL(`gl1`.`debit`, 0)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `acc1`.`id` = `gl1`.`gl_accountID` AND `acc1`.`gl_code` = '10.20.01.01.03.10'), 2) > 0
            ");
        }
        return $query->result_array();
    }


    public function get_locationCode_data($id)
    {
        $query = $this->db->query(
            "SELECT
                `lc`.`id`,
                `lc`.`tenancy_type`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `lc`.`location_code`,
                `lc`.`floor_area`,
                `lc`.`area_classification`,
                `lc`.`area_type`,
                `lc`.`rent_period`,
                `lc`.`payment_mode`,
                `lc`.`rental_rate`,
                `lc`.`status`
            FROM
                `location_code` `lc`,
                `floors` `f`,
                `stores` `s`
            WHERE
                `lc`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `lc`.`flag` != 'Disabled'
            AND
                `lc`.`id` = '$id'
            LIMIT 1
        ");


        return $query->result_array();
    }


    public function populate_ltlocationCode($store_name, $floor_name)
    {
        $query = $this->db->query(
            "SELECT
                `lc`.`location_code`
            FROM
                `location_code` `lc`,
                `floors` `f`,
                `stores` `s`
            WHERE
                `lc`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `f`.`floor_name` = '$floor_name'
            AND
                `s`.`store_name` = '$store_name'
            AND
                `lc`.`tenancy_type` = 'Long Term'
            AND
                `lc`.`location_code` NOT IN
                (SELECT
                    `hlc`.`location_code`
                FROM
                    `prospect` `p`,
                    `history_locationCode` `hlc`,
                    `tenants` `t`
                 WHERE
                    `hlc`.`locationCode_id` = `lc`.`id`
                 AND
                    `hlc`.`status` = 'Active'
                 AND
                    `p`.`locationCode_id` = `lc`.`id`
                 AND
                    `p`.`id` = `t`.`prospect_id`
                 AND
                    `t`.`status` = 'Active'
                )
        ");

        return $query->result_array();
    }



    public function populate_stlocationCode($store_name, $floor_name)
    {
        $query = $this->db->query(
            "SELECT
                `lc`.`location_code`
            FROM
                `location_code` `lc`,
                `floors` `f`,
                `stores` `s`
            WHERE
                `lc`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `f`.`floor_name` = '$floor_name'
            AND
                `s`.`store_name` = '$store_name'
            AND
                `lc`.`tenancy_type` = 'Short Term'
            AND
                `lc`.`location_code` NOT IN
                (SELECT
                    `hlc`.`location_code`
                FROM
                    `prospect` `p`,
                    `history_locationCode` `hlc`,
                    `tenants` `t`
                 WHERE
                    `hlc`.`locationCode_id` = `lc`.`id`
                 AND
                    `hlc`.`status` = 'Active'
                 AND
                    `p`.`locationCode_id` = `lc`.`id`
                 AND
                    `p`.`id` = `t`.`prospect_id`
                 AND
                    `t`.`status` = 'Active'
                )
        ");

        return $query->result_array();
    }



    public function get_locationCodeInfo($location_code)
    {
        $query = $this->db->query(
            "SELECT
                `lc`.`id`,
                `lc`.`tenancy_type`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `lc`.`location_code`,
                `lc`.`floor_area`,
                `lc`.`area_classification`,
                `lc`.`area_type`,
                `lc`.`rent_period`,
                `lc`.`payment_mode`,
                `lc`.`rental_rate`,
                `lc`.`status`
            FROM
                `location_code` `lc`,
                `floors` `f`,
                `stores` `s`
            WHERE
                `lc`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `lc`.`flag` != 'Disabled'
            AND
                `lc`.`location_code` = '$location_code'
            LIMIT 1
        ");

        return $query->result_array();
    }


    public function get_locationCode_id($location_code)
    {
        $query = $this->db->query(
            "SELECT
                `id`
            FROM
                `location_code`
            WHERE
                `location_code` = '$location_code'
            AND
                `status` = 'Active'
        ")->row()->id;

        return $query;
    }


    public function get_historyLocationCodeID($location_code)
    {
        $query = $this->db->query(
            "SELECT
                `location_historyID`
            FROM
                `history_locationcode`
            WHERE
                `location_code` = '$location_code'
        ")->row()->location_historyID;

        return $query;
    }




    public function get_tenantCounterID($tenant_id)
    {
        $query = $this->db->query("SELECT `id` FROM `tenants` WHERE `tenant_id` = '$tenant_id' AND `status` = 'Active' LIMIT 1")->row()->id;
        return $query;
    }

    public function change_tenantStatus($id)
    {
        $query = $this->db->query("UPDATE `tenants` SET `status` = 'Disabled' WHERE `id` = '$id'");
    }

    public function change_statusAttachment($tbl_name, $tenant_id)
    {
        $query = $this->db->query("UPDATE `$tbl_name` SET `status` = 'Disabled' WHERE `tenant_id` = '$tenant_id'");
    }

    public function change_selectedCharges($tenant_id)
    {
        $this->db->query("UPDATE `selected_monthly_charges` SET `flag` = 'Disabled' WHERE `tenant_id` = '$tenant_id'");
    }


    public function disable_locationCodeHistoryStatus($id)
    {
        $query = $this->db->query("UPDATE `history_locationcode` SET `status` = 'Disabled' WHERE `locationCode_id` = '$id'");
    }


    public function get_tenantOldData($id)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `t`.`tin`,
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`request_date`,
                `p`.`approved_date`,
                `p`.`flag`,
                `lt`.`leasee_type`,
                `s`.`store_name`,
                `f`.`floor_name`,
                `lc`.`location_code`,
                `p`.`status`,
                `lc`.`floor_area`,
                `lc`.`area_classification`,
                `lc`.`area_type`,
                `lc`.`rent_period`,
                `lc`.`payment_mode`,
                `lc`.`rental_rate` AS `basic_rental`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `floors` `f`,
                `location_code` `lc`,
                `leasing_users` `u`,
                `tenants` `t`
            WHERE
                `lc`.`id` = `t`.`locationCode_id`
            AND
                `lc`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `s`.`id`
            AND
                `p`.`lesseeType_id` = `lt`.`id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                `p`.`id` = `t`.`prospect_id`
            AND
                `t`.`id` = '$id'
            LIMIT 1
        ");



        return $query->result_array();
    }



    public function populate_rentPeriod($tenancy_type)
    {
        $query = $this->db->query(
            "SELECT
                CONCAT(`number`, ' ', `uom`) AS `rent_period`
            FROM
                `rent_period`
            WHERE
                `tenancy_type` = '$tenancy_type'
        ");

        return $query->result_array();
    }


    public function get_lesseeInfo($tenant_id, $contract_no)
    {
        $query = $this->db->query(
            "SELECT
                `p`.`corporate_name`,
                `p`.`trade_name`,
                `p`.`address`,
                `t`.`tin`,
                `t`.`tenant_id`,
                `t`.`tenant_type`,
                `t`.`contract_no`,
                `t`.`rental_type`,
                `t`.`tenancy_type`,
                `t`.`rent_percentage`,
                `lc`.`location_code`,
                `lc`.`floor_area`
            FROM
                `prospect` `p`,
                `tenants` `t`,
                `location_code` `lc`
            WHERE
                `t`.`tenant_id` = '$tenant_id'
            AND
                `t`.`contract_no` = '$contract_no'
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `t`.`locationCode_id` = `lc`.`id`
            AND
                `lc`.`status` = 'Active'
            LIMIT 1
        ");


        if($query->num_rows()>0) // If tenant has contract
        {
            return $query->result_array();
        }
        else
        {

            $query2 = $this->db->query(
                "SELECT
                    `p`.`corporate_name`,
                    `p`.`trade_name`,
                    `p`.`address`,
                    `t`.`tin`,
                    `t`.`tenant_id`,
                    `t`.`tenant_type`,
                    `t`.`contract_no`,
                    `t`.`rental_type`,
                    `t`.`tenancy_type`,
                    `t`.`rent_percentage`,
                    `t`.`tenant_type` AS `location_code`,
                    `t`.`tenant_type` AS `floor_area`
                FROM
                    `prospect` `p`,
                    `tenants` `t`,
                    `location_code` `lc`
                WHERE
                    `t`.`tenant_id` = '$tenant_id'
                AND
                    `t`.`contract_no` = '$contract_no'
                AND
                    `t`.`status` = 'Active'
                AND
                    `t`.`prospect_id` = `p`.`id`
                LIMIT 1
            ");
            return $query2->result_array();
        }
    }


    public function get_rentalIncrement()
    {
        return $query = $this->db->query("SELECT `amount` FROM `rental_increment` LIMIT 1")->row()->amount;
    }

    public function get_collectionDate($soa_no)
    {
        return $query = $this->db->query("SELECT `collection_date` FROM `soa` WHERE `soa_no` = '$soa_no' LIMIT 1")->row()->collection_date;
    }
    public function get_latestCollectionDate($tenant_id)
    {
        return $this->db->query("SELECT MAX(`collection_date`) AS `collection_date` FROM `soa` WHERE `tenant_id` = '$tenant_id' LIMIT 1")->row()->collection_date;
    }


    public function get_latePaymentPenalty($tenant_id)
    {
        $query = $this->db->query("SELECT * FROM `tmp_latepaymentpenalty` WHERE `tenant_id` = '$tenant_id' AND `flag` != 'Invoiced' AND `amount` > 0");
        return $query->result_array();
    }


    public function delete_tmp_latepaymentpenalty($doc_no, $tenant_id)
    {
        $this->db->query("DELETE FROM `tmp_latepaymentpenalty` WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no'");
    }


    public function check_SOACancellation($soa_no, $collection_date)
    {
        $query = $this->db->query(
            "SELECT
                `id`
            FROM
                `soa`
            WHERE
                '$collection_date' <= NOW() - INTERVAL 15 DAY
            AND
                `soa_no` = '$soa_no'
        ");


        if ($query->num_rows() == 0)
        {
            return true;
        }
        return false;
    }
    public function cancel_latepaymentSOA($soa_no)
    {
        $this->db->query(
            "UPDATE
                `tmp_latepaymentpenalty`
            SET
                `soa` = '',
                `soa_no` = ''
            WHERE
                `soa_no` = '$soa_no'
        ");
    }


    public function get_cashier()
    {

        $query = $this->db->query(
            "SELECT
                `id`,
                CONCAT(`first_name`, ' ', `last_name`) as `username`
            FROM
                `leasing_users`
            WHERE
                `user_type` = 'CFS'
        ");

        return $query->result_array();
    }


    public function get_invoicedlatepaymentpenalty($tenant_id)
    {
        $query = $this->db->query("SELECT * FROM `tmp_latepaymentpenalty` WHERE `tenant_id` = '$tenant_id' AND `flag` = 'Invoiced' AND `soa` != 'Yes'");
        return $query->result_array();
    }


    public function update_tmp_latepaymentpenalty($id, $doc_no)
    {
        $this->db->query("UPDATE `tmp_latepaymentpenalty` SET `flag` = 'Invoiced', `invoice_no` = '$doc_no' WHERE `id` = '$id'");
    }

    public function update_ledgerDueDate($tenant_id, $doc_no, $new_due_date)
    {
        $this->db->query("UPDATE `ledger` SET `due_date` = '$new_due_date' WHERE `doc_no` = '$doc_no' AND `tenant_id` = '$tenant_id' AND `flag` = 'Penalty'");
    }


    public function retainContractDocs($new_id, $old_id)
    {
        $this->db->query("UPDATE `contract_docs` SET `tenant_id` = '$new_id' WHERE `tenant_id` = '$old_id' AND status IS NULL");
    }

    public function get_postedData($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `tenant_id`,
                `contract_no`,
                `description`,
                `due_date`,
                `charges_type`,
                `balance`
            FROM
                `invoicing`
            WHERE-
                `tenant_id` = '$tenant_id'
            AND
                `doc_no` = '$doc_no'
            AND
                (`charges_type` = 'Basic/Monthly Rental' OR `charges_type` = 'Other')
            AND
                `tag` = 'Draft'
        ");

        return $query->result_array();
    }


    public function draft_invoice($tenant_id, $doc_no)
    {
        $query = $this->db->query("SELECT * FROM `invoicing` WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no' AND `tag` = 'Draft'");
        return $query->result_array();
    }

    public function get_subsidiaryLedger($tenant_id)
    {
        $query = $this->db->query(
                    "SELECT
                        `sl`.`id` as `entry_no`,
                        `p`.`trade_name`,
                        `sl`.`document_type`,
                        `sl`.`due_date`,
                        `sl`.`doc_no`,
                        `sl`.`ref_no`,
                        `ac`.`gl_code`,
                        `sl`.`bank_code`,
                        (CASE
                            WHEN
                                (`sl`.`status` != '' AND `sl`.`status` IS NOT NULL) THEN CONCAT(`ac`.`gl_account`, '-', `sl`.`status`)
                            ELSE `ac`.`gl_account`
                         END) AS `gl_account`,
                        `sl`.`posting_date`,
                        `sl`.`debit`,
                        ABS(`sl`.`credit`) AS `credit`
                    FROM
                        (SELECT *,  @row_number := @row_number + 1 as row_number FROM subsidiary_ledger CROSS JOIN (SELECT @row_number := 0) r WHERE tenant_id = '$tenant_id' ORDER BY posting_date, id ASC) `sl`,
                        `gl_accounts` `ac`,
                        `tenants` `t`,
                        `prospect` `p`
                    WHERE
                        `sl`.`gl_accountID` = `ac`.`id`
                    AND
                        `sl`.`tenant_id` = `t`.`tenant_id`
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    GROUP BY
                        `sl`.`id`
                    ORDER BY
                        `sl`.`posting_date`, `sl`.`id` ASC
            ");
        return $query->result_array();
    }




    public function get_tenantLedger($tenant_id)
    {
        $query = $this->db->query("
                SELECT
                    `tl`.`id`,
                    `tl`.`doc_no`,
                    `tl`.`document_type`,
                    `tl`.`ref_no`,
                    `tl`.`description`,
                    `tl`.`posting_date`,
                    `tl`.`due_date`,
                    `tl`.`debit`,
                    `tl`.`credit`,
                    ROUND((SELECT SUM(IFNULL(`sl`.`debit`,0)) - SUM(IFNULL(`sl`.`credit`, 0)) FROM `ledger` `sl` WHERE `sl`.`id` <= `tl`.`id` AND `sl`.`tenant_id` = '$tenant_id'), 2) AS `balance`
                FROM
                    `ledger` `tl`
                WHERE
                    `tl`.`tenant_id` = '$tenant_id'
                ORDER BY
                    `tl`.`id`
                ASC
            ");
        return $query->result_array();
    }


    public function tenant_penalties($tenant_id)
    {
        $query = $this->db->query("
                SELECT
                    `tl`.`id`,
                    `tl`.`doc_no`,
                    `tl`.`ref_no`,
                    `tl`.`description`,
                    `tl`.`posting_date`,
                    ROUND((SELECT IFNULL(`sl`.`credit`,0) - SUM(IFNULL(`sl`.`debit`, 0)) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no` AND `sl`.`tenant_id` = '$tenant_id'), 2) AS `amount`
                FROM
                    `ledger` `tl`
                WHERE
                    `tl`.`tenant_id` = '$tenant_id'
                AND
                    `tl`.`flag` = 'Penalty'
                AND
                    ROUND((SELECT IFNULL(`sl`.`credit`,0) - SUM(IFNULL(`sl`.`debit`, 0)) FROM `ledger` `sl` WHERE `sl`.`ref_no` = `tl`.`ref_no` AND `sl`.`tenant_id` = '$tenant_id'), 2) > 1
                ORDER BY
                    `tl`.`id`
                ASC
            ");
        return $query->result_array();
    }


    public function export_tenantLedger($tenant_id)
    {
        $query = $this->db->query(
                    "
                SELECT
                        `sl`.`id` as `entry_no`,
                        `p`.`trade_name`,
                        `sl`.`document_type`,
                        `sl`.`due_date`,
                        `sl`.`doc_no`,
                        `sl`.`ref_no`,
                        `ac`.`gl_code`,
                        (CASE
                            WHEN
                                (`sl`.`status` != '' AND `sl`.`status` IS NOT NULL) THEN CONCAT(`ac`.`gl_account`, '-', `sl`.`status`)
                            ELSE `ac`.`gl_account`
                         END) AS `gl_account`,
                        `sl`.`posting_date`,
                        `sl`.`debit`,
                        ABS(`sl`.`credit`) AS `credit`
                    FROM
                        (SELECT *,  @row_number := @row_number + 1 as row_number FROM subsidiary_ledger CROSS JOIN (SELECT @row_number := 0) r WHERE tenant_id = '$tenant_id' ORDER BY posting_date, id ASC) `sl`,
                        `gl_accounts` `ac`,
                        `tenants` `t`,
                        `prospect` `p`
                    WHERE
                        `sl`.`gl_accountID` = `ac`.`id`
                    AND
                        `sl`.`tenant_id` = `t`.`tenant_id`
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    GROUP BY
                        `sl`.`id`
                    ORDER BY
                        `sl`.`posting_date`, `sl`.`id` ASC
            ");


        return $query->result_array();
    }



    public function get_generalLedger()
    {
        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query = $this->db->query(
                "SELECT
                        `sl`.`id`,
                        `p`.`trade_name`,
                        `sl`.`document_type`,
                        `sl`.`due_date`,
                        `sl`.`doc_no`,
                        `sl`.`ref_no`,
                        `ac`.`gl_code`,
                        (CASE
                            WHEN
                                (`sl`.`status` != '' AND `sl`.`status` IS NOT NULL) THEN CONCAT(`ac`.`gl_account`, '-', `sl`.`status`)
                            ELSE `ac`.`gl_account`
                         END) AS `gl_account`,
                        `sl`.`posting_date`,
                        `sl`.`debit`,
                        ABS(`sl`.`credit`) AS `credit`
                    FROM
                        `subsidiary_ledger` `sl`,
                        `gl_accounts` `ac`,
                        `tenants` `t`,
                        `prospect` `p`
                    WHERE
                        `sl`.`gl_accountID` = `ac`.`id`
                    AND
                        `sl`.`tenant_id` = `t`.`tenant_id`
                    AND
                        `t`.`prospect_id` = `p`.`id`

                    GROUP BY
                        `sl`.`id`
                    ORDER BY
                        `sl`.`id` ASC
            ");

            return $query->result_array();
        }
        else
        {
            /* ======== OLD QUERY =============
                $query = $this->db->query(
                    "SELECT
                            `sl`.`id`,
                            `p`.`trade_name`,
                            `sl`.`document_type`,
                            `sl`.`due_date`,
                            `sl`.`doc_no`,
                            `sl`.`ref_no`,
                            `ac`.`gl_code`,
                            (CASE
                                WHEN
                                    (`sl`.`status` != '' AND `sl`.`status` IS NOT NULL) THEN CONCAT(`ac`.`gl_account`, '-', `sl`.`status`)
                                ELSE `ac`.`gl_account`
                             END) AS `gl_account`,
                            `sl`.`posting_date`,
                            `sl`.`debit`,
                            ABS(`sl`.`credit`) AS `credit`
                        FROM
                            `subsidiary_ledger` `sl`,
                            `gl_accounts` `ac`,
                            `tenants` `t`,
                            `prospect` `p`
                        WHERE
                            `sl`.`gl_accountID` = `ac`.`id`
                        AND
                            `sl`.`tenant_id` = `t`.`tenant_id`
                        AND
                            `t`.`prospect_id` = `p`.`id`
                        AND
                            `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
                        GROUP BY
                            `sl`.`id`
                        ORDER BY
                            `sl`.`id` ASC
                ");
            ===================================*/

            $store = $this->session->userdata('store_code');


            $query = $this->db->query("
                SELECT 
                    tbl.*,
                    IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 ) as debit,
                    IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0) as credit,
                    (IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 ) + IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0)) as amount
                FROM 
                (
                    SELECT
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
                        (CASE 
                            WHEN (g.status != '' AND g.status IS NOT NULL) THEN CONCAT(a.gl_account, '-', g.status) 
                            WHEN (a.gl_account = 'Creditable WHT Receivable' AND g.tag != '' AND g.tag IS NOT NULL) THEN CONCAT(a.gl_account, '-', g.tag) 
                            ELSE a.gl_account 
                        END) AS gl_account,
                        a.gl_code,
                        SUM(IFNULL(g.debit, 0)) debit,
                        SUM(IFNULL(g.credit, 0)) credit
                    FROM 
                        subsidiary_ledger as g
                    LEFT JOIN 
                        (SELECT distinct(tnt.prospect_id) as prospect_id, tnt.tenant_id, tnt.store_code  FROM tenants tnt) t on t.tenant_id = g.tenant_id
                    LEFT JOIN 
                        prospect p on p.id = t.prospect_id
                    LEFT JOIN 
                        gl_accounts a ON a.id = g.gl_accountID
                    WHERE
                        t.store_code = '$store'
                    AND 
                        (g.document_type = 'Invoice' OR g.document_type = 'Payment')
                    AND
                        (g.tenant_id <> 'DELETED' AND g.ref_no <> 'DELETED' AND g.doc_no <> 'DELETED')
                    AND 
                        g.tenant_id <> 'ICM-LT000064'

                    GROUP BY
                        CASE 
                        WHEN g.document_type = 'Invoice'
                        THEN  CONCAT(TRIM(g.doc_no),TRIM(g.ref_no),TRIM(g.gl_accountID),TRIM(g.tenant_id))
                        ELSE CONCAT(TRIM(g.gl_accountID),TRIM(g.id),TRIM(g.tenant_id), TRIM(g.ref_no)) 
                        END
                    ORDER BY 
                        g.document_type, g.doc_no
                ) 
                AS 
                    tbl
                LEFT JOIN 
                    (SELECT 
                        memo.ref_no,
                        memo.gl_accountID,
                        memo.tenant_id,
                        SUM(IFNULL(memo.debit, 0) + IFNULL(memo.credit, 0)) as amt
                    FROM 
                        subsidiary_ledger as memo
                    WHERE 
                        (memo.document_type = 'Credit Memo' OR memo.document_type = 'Debit Memo')
                    AND
                        memo.tenant_id != 'DELETED'
                    GROUP BY
                        memo.ref_no, memo.gl_accountID, memo.tenant_id
                    ) AS m
                ON 
                    (tbl.ref_no = m.ref_no AND tbl.gl_accountID = m.gl_accountID AND tbl.document_type = 'Invoice')
                WHERE  
                    (IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 ) + IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0)) <> 0
                /*GROUP BY 
                    tbl.doc_no , tbl.ref_no , tbl.gl_accountID, tbl.tenant_id*/
                
                ORDER BY  
                    tbl.posting_date, tbl.credit DESC, tbl.debit DESC, tbl.trade_name, tbl.ref_no, tbl.doc_no");

            return $query->result_array();

        }
    }



    public function generate_GLCSV($beginning_date, $end_date)
    {
        $query;
        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query = $this->db->query(
                "SELECT
                        `sl`.`id`,
                        `sl`.`tenant_id`,
                        `p`.`trade_name`,
                        `sl`.`document_type`,
                        `sl`.`doc_no`,
                        `ac`.`gl_code`,
                        (CASE
                            WHEN
                                (`sl`.`status` != '' AND `sl`.`status` IS NOT NULL) THEN CONCAT(`ac`.`gl_account`, '-', `sl`.`status`)
                            ELSE `ac`.`gl_account`
                         END) AS `gl_account`,
                        `sl`.`due_date`,
                        `sl`.`posting_date`,
                        `sl`.`debit`,
                        ABS(`sl`.`credit`) as credit
                    FROM
                    `subsidiary_ledger` `sl`,
                    `gl_accounts` `ac`,
                    `tenants` `t`,
                    `prospect` `p`
                WHERE
                    `sl`.`gl_accountID` = `ac`.`id`
                AND
                    `sl`.`tenant_id` = `t`.`tenant_id`
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    (`sl`.`posting_date` BETWEEN '$beginning_date' AND '$end_date')
                GROUP BY
                    `sl`.`id`
                ORDER BY
                    `sl`.`id` ASC
            ");
        }
        else
        {
            /*$query = $this->db->query(
                    "SELECT
                        `sl`.`id`,
                        `sl`.`tenant_id`,
                        `p`.`trade_name`,
                        `sl`.`document_type`,
                        `sl`.`doc_no`,
                        `ac`.`gl_code`,
                        (CASE
                            WHEN
                                (`sl`.`status` != '' AND `sl`.`status` IS NOT NULL) THEN CONCAT(`ac`.`gl_account`, '-', `sl`.`status`)
                            ELSE `ac`.`gl_account`
                         END) AS `gl_account`,
                        `sl`.`due_date`,
                        `sl`.`posting_date`,
                        `sl`.`debit`,
                        ABS(`sl`.`credit`) as credit
                    FROM
                        `subsidiary_ledger` `sl`,
                        `gl_accounts` `ac`,
                        `tenants` `t`,
                        `prospect` `p`
                    WHERE
                        `sl`.`gl_accountID` = `ac`.`id`
                    AND
                        `sl`.`tenant_id` = `t`.`tenant_id`
                    AND
                        (`sl`.`posting_date` BETWEEN '$beginning_date' AND '$end_date')
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    AND
                        `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
                    GROUP BY
                        `sl`.`id`
                    ORDER BY
                        `sl`.`id` ASC
                ");*/

             $store = $this->session->userdata('store_code');


            $query = $this->db->query("
                SELECT 
                    tbl.*,
                    IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 ) as debit,
                    IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0) as credit,
                    (IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 ) + IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0)) as amount
                FROM 
                (
                    SELECT
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
                        (CASE 
                            WHEN (g.status != '' AND g.status IS NOT NULL) THEN CONCAT(a.gl_account, '-', g.status) 
                            WHEN (a.gl_account = 'Creditable WHT Receivable' AND g.tag != '' AND g.tag IS NOT NULL) THEN CONCAT(a.gl_account, '-', g.tag) 
                            ELSE a.gl_account 
                        END) AS gl_account,
                        a.gl_code,
                        SUM(IFNULL(g.debit, 0)) debit,
                        SUM(IFNULL(g.credit, 0)) credit
                    FROM 
                        subsidiary_ledger as g
                    LEFT JOIN 
                        (SELECT distinct(tnt.prospect_id) as prospect_id, tnt.tenant_id, tnt.store_code  FROM tenants tnt) t on t.tenant_id = g.tenant_id
                    LEFT JOIN 
                        prospect p on p.id = t.prospect_id
                    LEFT JOIN 
                        gl_accounts a ON a.id = g.gl_accountID
                    WHERE
                        t.store_code = '$store'
                    AND 
                        (g.document_type = 'Invoice' OR g.document_type = 'Payment')
                    AND
                        (g.tenant_id <> 'DELETED' AND g.ref_no <> 'DELETED' AND g.doc_no <> 'DELETED')
                    AND 
                        g.tenant_id <> 'ICM-LT000064'
                    AND
                        (g.posting_date BETWEEN '$beginning_date' AND '$end_date')
                    GROUP BY
                        CASE 
                        WHEN g.document_type = 'Invoice'
                        THEN  CONCAT(TRIM(g.doc_no),TRIM(g.ref_no),TRIM(g.gl_accountID),TRIM(g.tenant_id))
                        ELSE CONCAT(TRIM(g.gl_accountID),TRIM(g.id),TRIM(g.tenant_id), TRIM(g.ref_no)) 
                        END
                    ORDER BY 
                        g.document_type, g.doc_no
                ) 
                AS 
                    tbl
                LEFT JOIN 
                    (SELECT 
                        memo.ref_no,
                        memo.gl_accountID,
                        memo.tenant_id,
                        SUM(IFNULL(memo.debit, 0) + IFNULL(memo.credit, 0)) as amt
                    FROM 
                        subsidiary_ledger as memo
                    WHERE 
                        (memo.document_type = 'Credit Memo' OR memo.document_type = 'Debit Memo')
                    AND
                        memo.tenant_id != 'DELETED'
                    GROUP BY
                        memo.ref_no, memo.gl_accountID, memo.tenant_id
                    ) AS m
                ON 
                    (tbl.ref_no = m.ref_no AND tbl.gl_accountID = m.gl_accountID AND tbl.document_type = 'Invoice')
                WHERE  
                    (IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 ) + IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0)) <> 0
                /*GROUP BY 
                    tbl.doc_no , tbl.ref_no , tbl.gl_accountID, tbl.tenant_id*/
                
                ORDER BY  
                    tbl.posting_date, tbl.credit DESC, tbl.debit DESC, tbl.trade_name, tbl.ref_no, tbl.doc_no");

            return $query->result_array();
        }

        return $query->result_array();
    }


    public function get_unclosedPDC($as_of)
    {

        if ($this->session->userdata('user_type') == 'Administrator')
        {
            /*$query = $this->db->query(
                "SELECT
                    `gl`.`id`,
                    `p`.`trade_name`,
                    `gl`.`tenant_id`,
                    `gl`.`doc_no`,
                    `gl`.`ref_no`,
                    `ac`.`gl_code`,
                    `ac`.`gl_account`,
                    `ps`.`check_date`,
                    `gl`.`posting_date`,
                    SUM(ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `ac1` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND `ac1`.`gl_code` = '10.10.01.03.07.01' AND `gl1`.`gl_accountID` = `ac1`.`id`) ,0) ,2)) as `amount`
                FROM
                    `general_ledger` `gl`,
                    `gl_accounts` `ac`,
                    `tenants` `t`,
                    `prospect` `p`,
                    `location_code` `lc`,
                    `floors` `f`,
                    `payment_scheme` `ps`,
                    `stores` `s`
                WHERE
                    `gl`.`gl_accountID` = `ac`.`id`
                AND
                    `gl`.`tenant_id` = `t`.`tenant_id`
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`status` = 'Active'
                AND
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `gl`.`doc_no` = `ps`.`receipt_no`
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `ac`.`gl_code` = '10.10.01.03.07.01'
                GROUP BY `ps`.`check_no`
                HAVING
                    SUM(ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `ac1` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND `ac1`.`gl_code` = '10.10.01.03.07.01' AND `gl1`.`gl_accountID` = `ac1`.`id`) ,0) ,2)) > 0
            ");*/

             $query = $this->db->query("
                SELECT
                    tbl.*,
                    SUM(tbl.debit) as debit,
                    SUM(tbl.credit) as credit,
                    SUM(tbl.amount) as amount 
                FROM 
                    (SELECT
                        sl.id,
                        p.trade_name,
                        sl.tenant_id,
                        sl.doc_no,
                        sl.ref_no,
                        a.gl_code,
                        a.gl_account,
                        ps.check_date,
                        sl.posting_date,
                        ps.check_no,
                        SUM(IFNULL(sl.debit, 0)) AS  debit,
                        SUM(IFNULL(sl.credit, 0)) AS credit,
                        SUM(IFNULL(sl.debit, 0) + IFNULL(sl.credit, 0)) AS amount
                    FROM
                        subsidiary_ledger sl
                    LEFT JOIN
                        gl_accounts a
                        ON sl.gl_accountID = a.id
                    LEFT JOIN
                        (SELECT distinct(prospect_id) as prospect_id, tenant_id, store_code  FROM tenants) t 
                        ON sl.tenant_id =  t.tenant_id
                    LEFT JOIN
                        prospect p
                        ON p.id = t.prospect_id
                    LEFT JOIN
                        (SELECT receipt_no, check_date, tenant_id, check_no  FROM payment_scheme GROUP BY receipt_no, tenant_id) ps
                        ON sl.tenant_id  = ps.tenant_id AND sl.doc_no = ps.receipt_no
                    WHERE
                        a.gl_code = '10.10.01.03.07.01'
                    AND 
                        sl.tenant_id <> 'DELETED'
                    AND 
                        sl.document_type IN ('Payment', 'Payment Adjustment')
                    GROUP BY 
                        sl.ref_no, sl.tenant_id, sl.ft_ref
                    HAVING 
                        amount >= 1
                    ORDER BY 
                        sl.posting_date ASC) AS tbl
                WHERE 
                    check_date <= '$as_of'
                GROUP BY
                    tbl.doc_no, tbl.tenant_id
                HAVING 
                    amount >= 1    
                ORDER BY
                    tbl.check_date DESC
            ")->result_array();

            return $query;
        }
        else
        {
            

            /*$query = $this->db->query(
                "SELECT
                    `gl`.`id`,
                    `t`.`trade_name`,
                    `gl`.`tenant_id`,
                    `gl`.`doc_no`,
                    `gl`.`ref_no`,
                    `ac`.`gl_code`,
                    `ac`.`gl_account`,
                    `ps`.`check_date`,
                    `gl`.`posting_date`,
                    SUM(ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `ac1` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND `ac1`.`gl_code` = '10.10.01.03.07.01' AND `gl1`.`gl_accountID` = `ac1`.`id`) ,0) ,2)) as `amount`
                FROM
                    `general_ledger` `gl`,
                    `gl_accounts` `ac`,
                    (SELECT t.tenant_id, p.trade_name, p.contact_person FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS t,
                    `payment_scheme` `ps`
                WHERE
                    `gl`.`gl_accountID` = `ac`.`id`
                AND
                    `gl`.`tenant_id` = `t`.`tenant_id`
                AND
                    `gl`.`doc_no` = `ps`.`receipt_no`
                AND
                    `ac`.`gl_code` = '10.10.01.03.07.01'
                GROUP BY `ps`.`check_no`
                HAVING
                    SUM(ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `ac1` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND `ac1`.`gl_code` = '10.10.01.03.07.01' AND `gl1`.`gl_accountID` = `ac1`.`id`) ,0) ,2)) > 0
                ORDER BY `gl`.`posting_date` ASC
            ");*/
            $store_code = $this->session->userdata('store_code');
            $query = $this->db->query("
                SELECT
                    tbl.*,
                    SUM(tbl.debit) as debit,
                    SUM(tbl.credit) as credit,
                    SUM(tbl.amount) as amount 
                FROM 
                    (SELECT
                        sl.id,
                        p.trade_name,
                        sl.tenant_id,
                        sl.doc_no,
                        sl.ref_no,
                        a.gl_code,
                        a.gl_account,
                        ps.check_date,
                        sl.posting_date,
                        ps.check_no,
                        SUM(IFNULL(sl.debit, 0)) AS  debit,
                        SUM(IFNULL(sl.credit, 0)) AS credit,
                        SUM(IFNULL(sl.debit, 0) + IFNULL(sl.credit, 0)) AS amount
                    FROM
                        subsidiary_ledger sl
                    LEFT JOIN
                        gl_accounts a
                        ON sl.gl_accountID = a.id
                    LEFT JOIN
                        (SELECT distinct(prospect_id) as prospect_id, tenant_id, store_code  FROM tenants) t 
                        ON sl.tenant_id =  t.tenant_id
                    LEFT JOIN
                        prospect p
                        ON p.id = t.prospect_id
                    LEFT JOIN
                        (SELECT receipt_no, check_date, tenant_id, check_no  FROM payment_scheme GROUP BY receipt_no, tenant_id) ps
                        ON sl.tenant_id  = ps.tenant_id AND sl.doc_no = ps.receipt_no
                    WHERE
                        a.gl_code = '10.10.01.03.07.01'
                    AND 
                        sl.tenant_id <> 'DELETED'
                    AND 
                        sl.document_type IN ('Payment', 'Payment Adjustment')
                    AND 
                        t.store_code = '$store_code'
                    GROUP BY 
                        sl.ref_no, sl.tenant_id, sl.ft_ref
                    HAVING 
                        amount >= 1
                    ORDER BY 
                        sl.posting_date ASC) AS tbl
                WHERE 
                    check_date <= '$as_of'
                GROUP BY
                    tbl.doc_no, tbl.tenant_id
                HAVING 
                    amount >= 1    
                ORDER BY
                    tbl.check_date DESC
            ");

            

            return $query;
        }

    }



    public function get_receiptNo($gl_id)
    {
        $query = $this->db->query("SELECT `doc_no` as `receipt_no` FROM `subsidiary_ledger` WHERE id = '$gl_id'")->row()->receipt_no;
        return $query;
    }


    public function get_PDCToClose($receipt_no)
    {
        /*$query = $this->db->query(
            "SELECT
                `gl`.`id` ,
                `gl`.`doc_no` ,
                `gl`.`ref_no` ,
                `gl`.`tenant_id`,
                `gl`.`gl_accountID` ,
                ABS(  `gl`.`debit` ) AS  `amount` ,
                `gl`.`company_code` ,
                `gl`.`department_code` ,
                `gl`.`bank_name` ,
                `gl`.`bank_code` ,
                `ps`.`check_no`
            FROM
                `general_ledger`  `gl` ,
                `gl_accounts`  `ac` ,
                `payment_scheme`  `ps`
            WHERE
                `gl`.`doc_no` =  '$receipt_no'
            AND
                `ac`.`gl_code` =  '10.10.01.03.07.01'
            AND
                `ac`.`id` =  `gl`.`gl_accountID`
            AND
                `ps`.`receipt_no` =  `gl`.`doc_no`
        ");*/

        $query = $this->db->query("
            SELECT
                tbl.*
            FROM 
                (SELECT
                    sl.id,
                    p.trade_name,
                    sl.tenant_id,
                    sl.doc_no,
                    sl.ref_no,
                    ps.check_date,
                    sl.posting_date,
                    sl.ft_ref,
                    sl.gl_accountID,
                    sl.company_code,
                    sl.department_code,
                    sl.bank_name,
                    sl.bank_code,
                    a.gl_code,
                    a.gl_account,
                    ps.check_no,
                    SUM(IFNULL(sl.debit, 0)) AS  debit,
                    SUM(IFNULL(sl.credit, 0)) AS credit,
                    SUM(IFNULL(sl.debit, 0) + IFNULL(sl.credit, 0)) AS amount
                FROM
                    subsidiary_ledger sl
                LEFT JOIN
                    gl_accounts a
                    ON sl.gl_accountID = a.id
                LEFT JOIN
                    (SELECT distinct(prospect_id) as prospect_id, tenant_id  FROM tenants) t 
                    ON sl.tenant_id =  t.tenant_id
                LEFT JOIN
                    prospect p
                    ON p.id = t.prospect_id
                LEFT JOIN
                    (SELECT receipt_no, check_date, tenant_id, check_no  FROM payment_scheme GROUP BY receipt_no, tenant_id) ps
                    ON sl.tenant_id  = ps.tenant_id AND sl.doc_no = ps.receipt_no
                WHERE
                    a.gl_code = '10.10.01.03.07.01'
                AND 
                    sl.tenant_id <> 'DELETED'
                AND sl.document_type IN ('Payment', 'Payment Adjustment')
                GROUP BY sl.ref_no, sl.tenant_id, sl.ft_ref
                HAVING amount >= 1
                ORDER BY sl.posting_date ASC) AS tbl
            WHERE 
                tbl.doc_no='$receipt_no'
            GROUP BY
                tbl.doc_no, tbl.tenant_id 
            ORDER BY
                tbl.posting_date
        ");



        return $query->result_array();

    }



    public function gl_accountID($code)
    {
        $id = $this->db->query("SELECT `id` FROM `gl_accounts` WHERE gl_code = '$code' LIMIT 1")->row()->id;
        return $id;
    }


    public function get_glRefNo($posting_date, $gl_accountID)
    {
        $ref_no = $this->db->query(
            "SELECT
                `ref_no`
            FROM
                `general_ledger`
            WHERE
                `posting_date` = '$posting_date'
            AND
                `gl_accountID` = '$gl_accountID'
            ")->row()->ref_no;
        return $ref_no;
    }



    public function get_storeBankCode($tenant_id)
    {   
        $store = $this->session->userdata('store_code');

        $query = $this->db->query(
            "SELECT
                `bank_code`
            FROM
                `accredited_banks`
            WHERE
                `store_code` LIKE '%$store%'
            ");

        return $query->result_array();
    }


    public function get_glPenalties($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `gl`.`tenant_id`,
                `gl`.`ref_no`,
                `gl`.`gl_accountID`,
                `gl`.`company_code`,
                `gl`.`department_code`,
                ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND ((`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16') OR (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03')) AND `gl1`.`gl_accountID` = `acc1`.`id`) ,0), 2) AS `balance`
            FROM
                `general_ledger` `gl`
            WHERE
                `gl`.`tenant_id` = '$tenant_id'
            AND
                `gl`.`tag` = 'Penalty'
            AND
                ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND ((`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16') OR (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03')) AND `gl1`.`gl_accountID` = `acc1`.`id`) ,0)) > 0
        ");

        return $query->result_array();
    }



    public function get_advanceDeposit($tenant_id)
    {
        $query = $this->db->query("SELECT * FROM `advance_deposit` WHERE `tenant_id` = '$tenant_id' LIMIT 1");
        return $query->result_array();
    }


    public function gl_entries($tenant_id, $doc_no)
    {
        $tag = $this->db->query("SELECT `tag` FROM `general_ledger` WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no' AND `tag` != '' LIMIT 1")->row()->tag;

        if ($tag == 'Basic Rent')
        {
            $query = $this->db->query(
                "SELECT
                    `gl`.`tenant_id`,
                    `gl`.`ref_no`,
                    `gl`.`company_code`,
                    `gl`.`department_code`,
                    `gl`.`tag`,
                    ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND (`acc`.`gl_code` = '10.10.01.01.02' OR `acc`.`gl_code` = '10.10.01.03.16' OR `acc`.`gl_code` = '10.10.01.01.01') AND `gl1`.`gl_accountID` = `acc`.`id`), 0) ,2) AS `balance`
                FROM
                    `general_ledger` `gl`
                WHERE
                    `tenant_id` = '$tenant_id'
                AND
                    `doc_no` = '$doc_no'
                AND
                    `tag` = 'Basic Rent'
                AND
                    ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND (`acc`.`gl_code` = '10.10.01.01.02' OR `acc`.`gl_code` = '10.10.01.03.16' OR `acc`.`gl_code` = '10.10.01.01.01') AND `gl1`.`gl_accountID` = `acc`.`id`), 0) ,2) > 0
                LIMIT 1
            ");

            return $query->result_array();
        }
        elseif ($tag == 'Other')
        {
           $query = $this->db->query(
                "SELECT
                    `gl`.`tenant_id`,
                    `gl`.`ref_no`,
                    `gl`.`company_code`,
                    `gl`.`department_code`,
                    `gl`.`tag`,
                    ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.01.03') AND `gl1`.`gl_accountID` = `acc1`.`id`) , 0) ,2) AS `balance`
                FROM
                    `general_ledger` `gl`
                WHERE
                    `tenant_id` = '$tenant_id'
                AND
                    `doc_no` = '$doc_no'
                AND
                    `tag` = 'Other'
                AND
                    ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl1`.`ref_no` = `gl`.`ref_no` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.01.03') AND `gl1`.`gl_accountID` = `acc1`.`id`) , 0) ,2) > 0
                LIMIT 1
            ");

            return $query->result_array();
        }
    }

    //TODO
    public function gl_advancePayment($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `gl`.`ref_no`,
                `gl`.`bank_code`,
                `gl`.`bank_name`,
                ROUND(ABS(IFNULL(`gl`.`credit`, 0)) - (SELECT IFNULL(ABS(SUM(`gl2`.`debit`)) ,0) FROM `general_ledger` `gl2`, `gl_accounts` `acc2` WHERE `acc2`.`id` = `gl2`.`gl_accountID` AND `acc2`.`gl_code` = '10.20.01.01.02.01' AND `gl`.`ref_no` = `gl2`.`ref_no` AND `gl2`.`tenant_id` = '$tenant_id') ,2) as `amount`
            FROM
                `general_ledger` `gl`,
                `gl_accounts` `acc`
            WHERE
                `acc`.`id` = `gl`.`gl_accountID`
            AND
                `acc`.`gl_code` = '10.20.01.01.02.01'
            AND
                `gl`.`tenant_id` = '$tenant_id'
            AND
                ROUND(ABS(IFNULL(`gl`.`credit`, 0)) - (SELECT IFNULL(ABS(SUM(`gl2`.`debit`)) ,0) FROM `general_ledger` `gl2`, `gl_accounts` `acc2` WHERE `acc2`.`id` = `gl2`.`gl_accountID` AND `acc2`.`gl_code` = '10.20.01.01.02.01' AND `gl`.`ref_no` = `gl2`.`ref_no` AND `gl2`.`tenant_id` = '$tenant_id') ,2) > 0
            ORDER BY `gl`.`posting_date` ASC
        ");

        return $query->result_array();
    }



    public function bu_entry()
    {
        $store_name = $this->my_store();
        $gl_code = "";
        if (strtoupper($store_name) == 'ISLAND CITY MALL')
        {
            $gl_code = "10.10.01.03.11.01";
        }
        elseif (strtoupper($store_name) == 'ALTURAS MALL')
        {
            $gl_code = "10.10.01.03.11.02";
        } 
        elseif (strtoupper($store_name) == 'PLAZA MARCELA') {
            $gl_code = "10.10.01.03.11.03";
        } 
        elseif (strtoupper($store_name) == 'ALTA CITTA') {
            $gl_code = "10.10.01.03.11.08";
        }

        return $gl_code;
    }


    public function get_rentReceivable()
    {
        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query = $this->db->query(
                "SELECT
                    `p`.`trade_name` ,
                    `gl`.`ref_no` ,
                    `gl`.`doc_no` ,
                    `ac`.`gl_account` ,
                    `gl`.`posting_date` ,
                    `gl`.`due_date` , ROUND( SUM( `gl`.`debit` ) + SUM(  `gl`.`credit` ) , 2 ) AS  `amount`
                FROM
                    `general_ledger`  `gl`,
                    `gl_accounts`  `ac`,
                    `tenants`  `t`,
                    `prospect`  `p`
                WHERE
                    `gl`.`gl_accountID` =  `ac`.`id`
                AND
                    `ac`.`gl_code` =  '10.10.01.03.16'
                AND
                    `gl`.`tenant_id` =  `t`.`tenant_id`
                AND
                    `t`.`prospect_id` =  `p`.`id`
                GROUP BY
                    `gl`.`ref_no`
                HAVING
                    ROUND( SUM(  `gl`.`debit` ) + SUM(  `gl`.`credit` ) , 2 ) >0
            ");

            return $query;
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `p`.`trade_name` ,
                    `gl`.`ref_no` ,
                    `gl`.`doc_no` ,
                    `ac`.`gl_account` ,
                    `gl`.`posting_date` ,
                    `gl`.`due_date` , ROUND( SUM( `gl`.`debit` ) + SUM(  `gl`.`credit` ) , 2 ) AS  `amount`
                FROM
                    `general_ledger`  `gl` ,
                    `gl_accounts`  `ac` ,
                    `tenants`  `t` ,
                    `prospect`  `p` ,
                    `location_code` `lc`,
                    `floors` `f`,
                    `stores` `s`
                WHERE
                    `gl`.`gl_accountID` =  `ac`.`id`
                AND
                    `ac`.`gl_code` =  '10.10.01.03.16'
                AND
                    `gl`.`tenant_id` =  `t`.`tenant_id`
                AND
                    `t`.`prospect_id` =  `p`.`id`
                AND
                    `p`.`locationCode_id` = `lc`.`id`
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                GROUP BY
                    `gl`.`ref_no`
                HAVING
                    ROUND( SUM(  `gl`.`debit` ) + SUM(  `gl`.`credit` ) , 2 ) >0
            ");

            return $query;
        }
    }


    public function get_accountReceivable()
    {
        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query = $this->db->query(
                "SELECT
                    `p`.`trade_name` ,
                    `gl`.`ref_no` ,
                    `gl`.`doc_no` ,
                    `ac`.`gl_account` ,
                    `gl`.`posting_date` ,
                    `gl`.`due_date` , ROUND( SUM( IFNULL(`gl`.`debit` ,0) ) + SUM(  IFNULL(`gl`.`credit` ,0) ) , 2 ) AS  `amount`
                FROM
                    `general_ledger`  `gl` ,
                    `gl_accounts`  `ac` ,
                    `tenants`  `t` ,
                    `prospect`  `p`
                WHERE
                    `gl`.`gl_accountID` =  `ac`.`id`
                AND
                    `ac`.`gl_code` =  '10.10.01.03.03'
                AND
                    `gl`.`tenant_id` =  `t`.`tenant_id`
                AND
                    `t`.`prospect_id` =  `p`.`id`
                GROUP BY
                    `gl`.`ref_no`
                HAVING
                    ROUND( SUM(  `gl`.`debit` ) + SUM(  `gl`.`credit` ) , 2 ) > 0
            ");

            return $query;
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `p`.`trade_name` ,
                    `gl`.`ref_no` ,
                    `gl`.`doc_no` ,
                    `ac`.`gl_account` ,
                    `gl`.`posting_date` ,
                    `gl`.`due_date` , ROUND( SUM( IFNULL(`gl`.`debit` ,0) ) + SUM(  IFNULL(`gl`.`credit` ,0) ) , 2 ) AS  `amount`
                FROM
                    `general_ledger`  `gl` ,
                    `gl_accounts`  `ac` ,
                    `tenants`  `t` ,
                    `prospect`  `p` ,
                    `location_code` `lc`,
                    `floors` `f`,
                    `stores` `s`
                WHERE
                    `gl`.`gl_accountID` =  `ac`.`id`
                AND
                    `ac`.`gl_code` =  '10.10.01.03.03'
                AND
                    `gl`.`tenant_id` =  `t`.`tenant_id`
                AND
                    `t`.`prospect_id` =  `p`.`id`
                AND
                    `p`.`locationCode_id` = `lc`.`id`
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                GROUP BY
                    `gl`.`ref_no`
                HAVING
                    ROUND( SUM(  `gl`.`debit` ) + SUM(  `gl`.`credit` ) , 2 ) > 0
            ");

            return $query;
        }
    }


    public function is_vatable($tenant_id)
    {
        return $this->db->query("SELECT `is_vat` FROM `tenants` WHERE `tenant_id` = '$tenant_id' AND `status` = 'Active' AND `flag` = 'Posted' LIMIT 1")->row()->is_vat;
    }


    public function get_bankName($bank_code)
    {   
        $store = $this->session->userdata('store_code');
        $query = $this->db->query("SELECT `bank_name` FROM `accredited_banks` WHERE `bank_code` = '$bank_code' AND  store_code LIKE '%$store%' LIMIT 1");
        return $query->result_array();
    }


    public function get_tenantRR($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                ROUND( (SUM(IFNULL(`gl`.`debit`, 0))) - IFNULL((SELECT SUM(ABS(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.07.01' OR `acc1`.`gl_code` = '10.10.01.03.11' OR `acc1`.`gl_code` = '10.10.01.03.11.01' OR `acc1`.`gl_code` = '10.10.01.03.11.02' OR `acc1`.`gl_code` = '10.10.01.03.16') AND `gl1`.`gl_accountID` = `acc1`.`id` AND `gl`.`ref_no` = `gl1`.`ref_no`) ,0), 2) AS `balance` ,
                `gl`.`ref_no` ,
                `gl`.`doc_no`
            FROM
                `general_ledger`  `gl`,
                `gl_accounts`  `ac`
            WHERE
                `gl`.`gl_accountID` =  `ac`.`id`
            AND
                `ac`.`gl_code` =  '10.10.01.03.16'
            AND
                `gl`.`tenant_id` =  '$tenant_id'
            AND
                `gl`.`doc_no` = '$doc_no'
            GROUP BY
                `gl`.`ref_no`
           HAVING
                ROUND( (SUM(IFNULL(`gl`.`debit`, 0))) - IFNULL((SELECT SUM(ABS(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.07.01' OR `acc1`.`gl_code` = '10.10.01.03.11' OR `acc1`.`gl_code` = '10.10.01.03.11.01' OR `acc1`.`gl_code` = '10.10.01.03.11.02' OR `acc1`.`gl_code` = '10.10.01.03.16') AND `gl1`.`gl_accountID` = `acc1`.`id` AND `gl`.`ref_no` = `gl1`.`ref_no`) ,0), 2) > 1
        ");

        return $query->result_array();
    }


    public function get_tenantAR($tenant_id, $doc_no)
    {
        $query = $this->db->query(
            "SELECT
                `gl`.`doc_no`,
                ROUND( (SUM(IFNULL(`gl`.`debit`, 0)) ) - IFNULL((SELECT SUM(ABS(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.07.01' OR `acc1`.`gl_code` = '10.10.01.03.11' OR `acc1`.`gl_code` = '10.10.01.03.11.01' OR `acc1`.`gl_code` = '10.10.01.03.11.02' OR `acc1`.`gl_code` =  '10.10.01.03.03' OR `acc1`.`gl_code` =  '10.10.01.03.04') AND `gl1`.`gl_accountID` = `acc1`.`id` AND `gl`.`ref_no` = `gl1`.`ref_no`) ,0), 2) AS `balance` ,
                `gl`.`ref_no`
            FROM
                `general_ledger`  `gl`,
                `gl_accounts`  `ac`
            WHERE
                `gl`.`gl_accountID` =  `ac`.`id`
            AND
                (`ac`.`gl_code` =  '10.10.01.03.03' || `ac`.`gl_code` =  '10.10.01.03.04')
            AND
                `gl`.`tenant_id` =  '$tenant_id'
            AND
                `gl`.`doc_no` = '$doc_no'
            AND
                (`gl`.`tag` != 'Penalty' OR `gl`.`tag` IS NULL)
            GROUP BY
                `gl`.`ref_no`
           HAVING
                ROUND( (SUM(IFNULL(`gl`.`debit`, 0)) ) - IFNULL((SELECT SUM(ABS(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.07.01' OR `acc1`.`gl_code` = '10.10.01.03.11' OR `acc1`.`gl_code` = '10.10.01.03.11.01' OR `acc1`.`gl_code` = '10.10.01.03.11.02' OR `acc1`.`gl_code` =  '10.10.01.03.03' OR `acc1`.`gl_code` =  '10.10.01.03.04') AND `gl1`.`gl_accountID` = `acc1`.`id` AND `gl`.`ref_no` = `gl1`.`ref_no`) ,0), 2) > 1
        ");

        return $query->result_array();
    }


    public function ARNTI_forClearing($id)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`ref_no`,
                `sl`.`doc_no`,
                `sl`.`due_date`,
                `sl`.`gl_accountID`,
                `sl`.`bank_name`,
                `acc`.`gl_code`,
                `sl`.`status`,
                `acc`.`gl_account`,
                `sl`.`posting_date`,
                 ABS(`sl`.`debit`) AS `amount`
            FROM
                `subsidiary_ledger` `sl`,
                `gl_accounts` `acc`
            WHERE
                `sl`.`gl_accountID` = `acc`.`id`
            AND
                `sl`.`id` = '$id'
        ");
        return $query->result_array();
    }


    public function rr_forCLearing($tenant_id, $ref_no)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`ref_no`,
                `sl`.`doc_no`,
                `sl`.`due_date`,
                `sl`.`gl_accountID`,
                `sl`.`bank_name`,
                `acc`.`gl_code`,
                `sl`.`status`,
                `acc`.`gl_account`,
                `sl`.`posting_date`,
                `sl`.`ft_ref`,
                ROUND(SUM(ABS(`sl`.`credit`)) - (SELECT SUM(IFNULL(ABS(`sl1`.`debit`) ,0)) FROM `subsidiary_ledger` `sl1` WHERE `sl1`.`ref_no` = `sl`.`ref_no` AND `sl1`.`gl_accountID` = `sl`.`gl_accountID` AND (`sl1`.`status` IS NOT NULL AND `sl1`.`status` != '')), 2) AS `amount`
            FROM
                `subsidiary_ledger` `sl`,
                `gl_accounts` `acc`
            WHERE
                `sl`.`gl_accountID` = `acc`.`id`
            AND
                `sl`.`tenant_id` = '$tenant_id'
            AND
                `sl`.`ref_no` = '$ref_no'
            AND
                (`sl`.`status` = 'RR Clearing' || `sl`.`status` = 'AR Clearing' || `sl`.`status` = 'Preop Clearing' || `sl`.`status` = 'URI Clearing')
            GROUP BY
                `sl`.`ref_no`, `sl`.`ft_ref`
        ");
        return $query->result_array();
    }



    public function clearing_postingDate($tenant_id, $id)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`posting_date`
            FROM
                `subsidiary_ledger`  `sl`
            WHERE
               `sl`.`id` = '$id'
            AND
                `gl`.`tenant_id` = '$tenant_id'
            LIMIT 1
        ");

        return $query->result_array();
    }



    public function get_docNo_refNo($tenant_id, $doc_no)
    {
        return $this->db->query("SELECT `ref_no` FROM `general_ledger` WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no' LIMIT 1")->row()->ref_no;
    }



    public function tenant_firstCategory($category)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`id`,
                `p`.`trade_name`,
                `t`.`tenant_id`,
                `t`.`tenancy_type`,
                `t`.`rental_type`
            FROM
                `tenants` `t`,
                `prospect` `p`
            WHERE
                `p`.`id` = `t`.`prospect_id`
            AND
                `t`.`status` = 'Active'
            AND
                `p`.`first_category` = '$category'
        ");

        return $query->result_array();
    }



    public function tenant_secondCategory($category)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`id`,
                `p`.`trade_name`,
                `t`.`tenant_id`,
                `t`.`tenancy_type`,
                `t`.`rental_type`
            FROM
                `tenants` `t`,
                `prospect` `p`
            WHERE
                `p`.`id` = `t`.`prospect_id`
            AND
                `t`.`status` = 'Active'
            AND
                `p`.`second_category` = '$category'
        ");

        return $query->result_array();
    }




    public function tenant_thirdCategory($category)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`id`,
                `p`.`trade_name`,
                `t`.`tenant_id`,
                `t`.`tenancy_type`,
                `t`.`rental_type`
            FROM
                `tenants` `t`,
                `prospect` `p`
            WHERE
                `p`.`id` = `t`.`prospect_id`
            AND
                `t`.`status` = 'Active'
            AND
                `p`.`third_category` = '$category'
        ");

        return $query->result_array();
    }


    public function get_GLaccount($gl_code)
    {
        $query;
        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'CFS')
        {
            $query = $this->db->query(
                "SELECT
                    `gl`.`id`,
                    `gl`.`posting_date`,
                    `gl`.`ref_no`,
                    `acc`.`gl_code`,
                    `gl`.`doc_no`,
                    (CASE
                        WHEN
                            `gl`.`debit` IS NULL || `gl`.`debit` = '0' || `gl`.`debit` = ''
                        THEN
                            `gl`.`credit`
                        ELSE
                            `gl`.`debit`
                    END) AS `amount`
                FROM
                    `general_ledger` `gl`,
                    `gl_accounts` `acc`
                WHERE
                    `gl`.`gl_accountID` = `acc`.`id`
                AND
                    `acc`.`gl_code` = '$gl_code'
            ");

        }
        else
        {

            $query = $this->db->query(
                "SELECT
                    `gl`.`id`,
                    `gl`.`posting_date`,
                    `gl`.`ref_no`,
                    `acc`.`gl_code`,
                    `gl`.`doc_no`,
                    (CASE
                        WHEN
                            `gl`.`debit` IS NULL || `gl`.`debit` = '0' || `gl`.`debit` = ''
                        THEN
                            `gl`.`credit`
                        ELSE
                            `gl`.`debit`
                    END) AS `amount`
                FROM
                    `general_ledger` `gl`,
                    `gl_accounts` `acc`,
                    `tenants` `t`
                WHERE
                    `gl`.`gl_accountID` = `acc`.`id`
                AND
                    `acc`.`gl_code` = '$gl_code'
                AND
                    `gl`.`tenant_id` = `t`.`tenant_id`
                AND
                    `t`.`store_code` = '" . $this->session->userdata('store_code') . "'

            ");

        }

        return $query->result_array();
    }



    public function get_accountChart()
    {
        $query;
        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'CFS')
        {
            $query = $this->db->query(
                "SELECT
                    `ac`.`id`,
                    `ac`.`gl_code`,
                    `ac`.`gl_account`,
                    ROUND(SUM(IFNULL(`gl`.`debit`, 0)) - SUM(ABS(IFNULL(`gl`.`credit`, 0))) ,2) AS `amount`
                FROM
                    `general_ledger` `gl`,
                    `gl_accounts` `ac`
                WHERE
                    `gl`.`gl_accountID` = `ac`.`id`
                GROUP BY
                    `ac`.`gl_code`
            ");
        }
        else
        {

            $query = $this->db->query(
                "SELECT
                    `ac`.`id`,
                    `ac`.`gl_code`,
                    `ac`.`gl_account`,
                    ROUND(SUM(IFNULL(`gl`.`debit`, 0)) - SUM(ABS(IFNULL(`gl`.`credit`, 0))) ,2) AS `amount`
                FROM
                    `general_ledger` `gl`,
                    `gl_accounts` `ac`,
                    `tenants` `t`
                WHERE
                    `gl`.`gl_accountID` = `ac`.`id`
                AND
                    `gl`.`tenant_id` = `t`.`tenant_id`
                AND
                    `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
                GROUP BY
                    `ac`.`gl_code`
            ");
        }

        return $query->result_array();
    }


    function navigate($gl_id)
    {

        $tenant_id;
        $posting_date;
        $ref_no;
        $doc_no;

        $main_entry = $this->db->query(
            "SELECT
                `tenant_id`,
                `posting_date`,
                `ref_no`,
                `doc_no`
            FROM
                `general_ledger`
            WHERE
                `id` = '$gl_id'
        ");



        foreach($main_entry->result() as $rows)
        {
            $tenant_id = $rows->tenant_id;
            $posting_date = $rows->posting_date;
            $ref_no = $rows->ref_no;
            $doc_no = $rows->doc_no;
        }




        $query = $this->db->query(
            "SELECT
                `gl`.`id`,
                `p`.`trade_name`,
                `gl`.`doc_no`,
                `acc`.`gl_account`,
                `acc`.`gl_code`,
                `gl`.`ref_no`,
                `gl`.`posting_date`,
                `gl`.`debit`,
                `gl`.`credit`
            FROM
                `general_ledger` `gl`,
                `gl_accounts` `acc`,
                `prospect` `p`,
                `tenants` `t`
            WHERE
                `gl`.`gl_accountID` = `acc`.`id`
            AND
                `gl`.`doc_no` = '$doc_no'
            AND
                `gl`.`ref_no` = '$ref_no'
            AND
                `gl`.`posting_date` = '$posting_date'
            AND
                `gl`.`tenant_id` = '$tenant_id'
            AND
                `gl`.`tenant_id` = `t`.`tenant_id`
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`prospect_id` = `p`.`id`
        ");


        return $query->result_array();
    }



    public function get_SLEntry($sl_id)
    {
        $query = $this->db->query(
            "SELECT
                `tenant_id`,
                `posting_date`,
                `ref_no`,
                `doc_no`,
                `contract_no`,
                `description`,
                `credit` as `amount`
            FROM
                `ledger`
            WHERE
                `id` = '$sl_id'
            LIMIT 1
        ");
        return $query->result_array();
    }


    public function GLPenaltyEntry($tenant_id, $posting_date, $doc_no, $amount)
    {

        $query = $this->db->query(
            "SELECT
                `ref_no`,
                `gl_accountID`,
                `company_code`,
                `department_code`,
                `debit`,
                `credit`
            FROM
                `general_ledger`
            WHERE
                `tenant_id` = '$tenant_id'
            AND
                `posting_date` = '$posting_date'
            AND
                `doc_no` = '$doc_no'
            AND
                `tag` = 'Penalty'
            AND
                `debit` LIKE '%" . $amount . "'
            LIMIT 1
        ");

        return $query->result_array();

    }


    public function delete_tmppenalty($tenant_id, $doc_no, $posting_date)
    {
        $invoice_no = $this->db->query("SELECT `invoice_no` FROM  `tmp_latepaymentpenalty` WHERE  `tenant_id` =  '$tenant_id' AND  `doc_no` =  '$doc_no' AND  `posting_date` =  '$posting_date' LIMIT 1");
        if ($invoice_no->num_rows()>0)
        {
            foreach ($invoice_no->result() as $value)
            {
                $this->db->query("UPDATE `invoicing` SET `status` = 'Paid' WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '" . $value->invoice_no . "' AND `flag` = 'Penalty'");
                $this->db->query("DELETE FROM `tmp_latepaymentpenalty` WHERE `tenant_id` = '$tenant_id' AND `doc_no` = '$doc_no' AND `posting_date` = '$posting_date'");
            }
        }

    }



    public function get_waivedPenalties()
    {
        $query;
        if ($this->session->userdata('user_type') == 'Administrator')
        {
            $query = $this->db->query(
                "SELECT
                    `sl`.`tenant_id`,
                    `p`.`trade_name`,
                    (SELECT `tl`.`posting_date` FROM `ledger` tl WHERE `tl`.`document_type` = 'Waive Penalty' AND `tl`.`ref_no` = `sl`.`ref_no` AND `sl`.`id` = `wp`.`sl_id` LIMIT 1) as `posting_date`,
                    `sl`.`description`,
                    `sl`.`credit` as `amount`,
                    CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) as `prepared_by`,
                    `wp`.`attachment`
                FROM
                    `ledger` `sl`,
                    `prospect` `p`,
                    `tenants` `t`,
                    `waived_penalties` `wp`,
                    `leasing_users` `u`
                WHERE
                    `sl`.`tenant_id` = `t`.`tenant_id`
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `sl`.`flag` = 'Penalty'
                AND
                    `wp`.`prepared_by` = `u`.`id`
                AND
                    (SELECT `tl`.`posting_date` FROM `ledger` tl WHERE `tl`.`document_type` = 'Waive Penalty' AND `tl`.`ref_no` = `sl`.`ref_no` AND `sl`.`id` = `wp`.`sl_id` LIMIT 1) IS NOT NULL
            ");
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `sl`.`tenant_id`,
                    `p`.`trade_name`,
                    (SELECT `tl`.`posting_date` FROM `ledger` tl WHERE `tl`.`document_type` = 'Waive Penalty' AND `tl`.`ref_no` = `sl`.`ref_no` AND `sl`.`id` = `wp`.`sl_id` LIMIT 1) as `posting_date`,
                    `sl`.`description`,
                    `sl`.`credit` as `amount`,
                    CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) as `prepared_by`,
                    `wp`.`attachment`
                FROM
                    `ledger` `sl`,
                    `prospect` `p`,
                    `tenants` `t`,
                    `waived_penalties` `wp`,
                    `leasing_users` `u`
                WHERE
                    `sl`.`tenant_id` = `t`.`tenant_id`
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
                AND
                    `sl`.`flag` = 'Penalty'
                AND
                    `wp`.`prepared_by` = `u`.`id`
                AND
                    (SELECT `tl`.`posting_date` FROM `ledger` tl WHERE `tl`.`document_type` = 'Waive Penalty' AND `tl`.`ref_no` = `sl`.`ref_no` AND `sl`.`id` = `wp`.`sl_id` LIMIT 1) IS NOT NULL
            ");
        }

        return $query->result_array();
    }



    public function get_waivedPenalty($tenant_id, $old_dueDate, $latest_dueDate)
    {
        $query = $this->db->query(
            "SELECT
                IFNULL(ROUND(SUM(`gl`.`debit`) ,2) ,0) as `amount`
            FROM
                `general_ledger` `gl`,
                `general_ledger` `gl1`,
                `gl_accounts` `ac`
            WHERE
                `gl`.`document_type` = 'Waive Penalty'
            AND
                `gl`.`tenant_id` = '$tenant_id'
            AND
                `gl`.`ref_no` = `gl1`.`ref_no`
            AND
                `ac`.`gl_code` = '20.80.01.08.01'
            AND
                `ac`.`id` = `gl1`.`gl_accountID`
            AND
                (`gl1`.`posting_date` BETWEEN '$old_dueDate' AND '$latest_dueDate')
        ")->row()->amount;

        return $query;
    }


    public function get_cashierName($user_id)
    {
        $query = $this->db->query(
            "SELECT
                CONCAT(`first_name`, ' ', `last_name`) as `cashier_name`
            FROM
                `leasing_users`
            WHERE
                `id` = '$user_id'
        ")->row()->cashier_name;

        return $query;
    }


    public function insert_accReport($tenant_id, $description, $amount, $posting_date, $tender_desc)
    {
        if ($description == 'Rent Receivable')
        {

            $tenant_type = "";
            $is_vat = "";
            $query = $this->db->query("SELECT `tenant_type`, `is_vat` FROM `tenants` WHERE `tenant_id` = '$tenant_id' AND `status` = 'Active' LIMIT 1");

            foreach($query->result() as $rows)
            {
                $tenant_type = $rows->tenant_type;
                $is_vat = $rows->is_vat;
            }

            if ($is_vat == 'Added')
            {
                $vat_amt = ($amount / 1.07) * .12 ;
                $data_vat = array(
                    'posting_date'  =>  $posting_date,
                    'tenant_id'     =>  $tenant_id,
                    'description'   =>  'VAT Output',
                    'amount'        =>  $vat_amt,
                    'tender_desc'   =>  $tender_desc,
                    'prepared_by'   =>  $this->_user_id
                );

                $this->insert('accountability_report', $data_vat);


                $wht_amt = ($amount / 1.07) * .05;

                $data_wht = array(
                    'posting_date'  =>  $posting_date,
                    'tenant_id'     =>  $tenant_id,
                    'description'   =>  'Creditable Withholding Tax',
                    'amount'        =>  $wht_amt,
                    'tender_desc'   =>  $tender_desc,
                    'prepared_by'   =>  $this->_user_id
                );

                $this->insert('accountability_report', $data_wht);

                $ri_amount = $amount - ($vat_amt + $wht_amt);

                $data_ri = array(
                    'posting_date'  =>  $posting_date,
                    'tenant_id'     =>  $tenant_id,
                    'description'   =>  'Rent Income',
                    'amount'        =>  $ri_amount,
                    'tender_desc'   =>  $tender_desc,
                    'prepared_by'   =>  $this->_user_id
                );
                $this->insert('accountability_report', $data_ri);

            }
            else
            {
                $wht_amt = ($amount / .95) * .05;

                $data_wht = array(
                    'posting_date'  =>  $posting_date,
                    'tenant_id'     =>  $tenant_id,
                    'description'   =>  'Creditable Withholding Tax',
                    'amount'        =>  $wht_amt,
                    'tender_desc'   =>  $tender_desc,
                    'prepared_by'   =>  $this->_user_id
                );

                $this->insert('accountability_report', $data_wht);

                $data_ri = array(
                    'posting_date'  =>  $posting_date,
                    'tenant_id'     =>  $tenant_id,
                    'description'   =>  'Rent Income',
                    'amount'        =>  $amount - $wht_amt,
                    'tender_desc'   =>  $tender_desc,
                    'prepared_by'   =>  $this->_user_id
                );
                $this->insert('accountability_report', $data_ri);
            }


            $data_rr = array(
                'posting_date'  =>  $posting_date,
                'tenant_id'     =>  $tenant_id,
                'description'   =>  'Rent Receivable',
                'amount'        =>  $amount,
                'tender_desc'   =>  $tender_desc,
                'prepared_by'   =>  $this->_user_id
            );
            $this->insert('accountability_report', $data_rr);
        }
        else
        {
            $data = array(
                'posting_date'  =>  $posting_date,
                'tenant_id'     =>  $tenant_id,
                'description'   =>  $description,
                'amount'        =>  $amount,
                'tender_desc'   =>  $tender_desc,
                'prepared_by'   =>  $this->_user_id
            );

            $this->insert('accountability_report', $data);
        }
    }



    public function gathered($user_id, $beginning_date, $end_date)
    {
        $query = $this->db->query(
            "SELECT
                `description`,
                `tender_desc`,
                ROUND(IFNULL(SUM(`amount`), 0), 2) as `amount`
            FROM
                `accountability_report`
            WHERE
                `prepared_by` = '$user_id'
            AND
                (`posting_date` BETWEEN '$beginning_date' AND '$end_date')
            AND
                (
                    `description` = 'Advance Deposit' OR
                    `description` = 'Security Deposit' OR
                    `description` = 'Construction Bond' OR
                    `description` = 'Account Receivable' OR
                    `description` = 'Rent Receivable' OR
                    `description` = 'Vat Output' OR
                    `description` = 'Rent Income' OR
                    `description` = 'Creditable Withholding Tax'
                )
            GROUP BY description, tender_desc
        ");

        return $query->result_array();
    }


    public function paymentReport($from_date, $to_date, $description)
    {


        $description = implode("','",$description);

        $query = $this->db->query(
            "SELECT
                `sl`.`posting_date` AS `payment_date`,
                `p`.`payor`,
                `t`.`tin`,
                `p`.`tender_typeDesc`,
                `p`.`receipt_no`,
                `p`.`amount_paid`
            FROM
                `payment_scheme` `p`,
                `subsidiary_ledger` `sl`,
                `tenants` `t`
            WHERE
                `p`.`tenant_id` = `t`.`tenant_id`
            AND
                (`sl`.`posting_date` BETWEEN '$from_date' AND '$to_date')
            AND
                `p`.`receipt_no` = `sl`.`doc_no`
            AND
                `p`.`tender_typeDesc` IN ('" . $description . "')
            AND
                `p`.`tenant_id` = `sl`.`tenant_id`
            AND
                `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
            GROUP BY `p`.`receipt_no`
        ");

        return $query->result_array();
    }


    public function generate_CFS_remittance($from_date, $to_date)
    {
        $query = $this->db->query(
            "SELECT
                `sl`.`posting_date` AS `payment_date`,
                `p`.`payor`,
                `t`.`tin`,
                `p`.`tender_typeDesc`,
                `p`.`receipt_no`,
                `p`.`amount_paid`
            FROM
                `payment_scheme` `p`,
                `subsidiary_ledger` `sl`,
                `tenants` `t`,
                `leasing_users` `lu`,
                `stores` `s`
            WHERE
                `p`.`tenant_id` = `t`.`tenant_id`
            AND
                (`sl`.`posting_date` BETWEEN '$from_date' AND '$to_date')
            AND
                `p`.`receipt_no` = `sl`.`doc_no`
            AND
                `p`.`tenant_id` = `sl`.`tenant_id`
            AND
                sl.prepared_by = lu.id
            AND
                lu.user_type = 'CFS'
            AND
                lu.user_group = s.id
            AND
                s.store_code = '" . $this->session->userdata('store_code') . "'
            GROUP BY `p`.`receipt_no`
            ORDER BY payment_date ASC
        ");

        return $query->result_array();
    }

    public function paymentList($user_id, $beginning_date, $end_date)
    {
        $query = $this->db->query(
            "SELECT
                `gl`.`posting_date` AS `payment_date`,
                `p`.`payor`,
                `p`.`tender_typeDesc`,
                `p`.`receipt_no`,
                `p`.`amount_paid`
            FROM
                `payment_scheme` `p`,
                `general_ledger` `gl`,
                `tenants` `t`
            WHERE
                (`gl`.`posting_date` BETWEEN '$beginning_date' AND '$end_date')
            AND
                `p`.`receipt_no` = `gl`.`doc_no`
            AND
                `p`.`tenant_id` = `gl`.`tenant_id`
            AND
                `gl`.`prepared_by` = '$user_id'
            GROUP BY `p`.`receipt_no`
        ");

        return $query->result_array();
    }


    public function get_storeForCashBankSetup()
    {
        $query = $this->db->query(
            "SELECT
                `s`.`store_name`,
                `s`.`id`
            FROM
                `stores` `s`
            WHERE
                `s`.`id` NOT IN  (SELECT `store_id` FROM `cash_bank` WHERE `status` != 'Not Active')
        ");
        return $query->result_array();
    }


    public function get_bankCode($bank_name)
    {
         $query = $this->db->query(
            "SELECT
                `bank_code`
            FROM
                `accredited_banks`
            WHERE
                `bank_name` = '$bank_name'
        ");
        return $query->result_array();
    }


    public function add_cashtobank($store_id, $bank_code)
    {


        $this->db->trans_start();
        $bank_id = $this->db->query("SELECT `id` FROM `accredited_banks` WHERE `bank_code` = '$bank_code' LIMIT 1")->row()->id;

        $data = array(
            'store_id'  =>  $store_id,
            'bank_id'   =>  $bank_id,
            'Status'    =>  'Active'
        );

        $this->insert('cash_bank', $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function update_cashtobank($store_name, $bank_code, $cb_id)
    {
        $this->db->trans_start();
        $store_id = $this->db->query("SELECT `id` FROM `stores` WHERE `store_name` = '$store_name' LIMIT 1")->row()->id;
        $bank_id = $this->db->query("SELECT `id` FROM `accredited_banks` WHERE `bank_code` = '$bank_code' LIMIT 1")->row()->id;

        $data = array(
            'store_id'  =>  $store_id,
            'bank_id'   =>  $bank_id
        );

        $this->update($data, $cb_id, 'cash_bank');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function get_cashtobank()
    {
        $query = $this->db->query(
            "SELECT
                `c`.`id`,
                `s`.`id` AS `store_id`,
                `s`.`store_name`,
                `ac`.`bank_name`,
                `ac`.`bank_code`
            FROM
                `cash_bank` `c`,
                `stores` `s`,
                `accredited_banks` `ac`
            WHERE
                `c`.`store_id` = `s`.`id`
            AND
                `c`.`bank_id` = `ac`.`id`
            AND
                `c`.`status` = 'Active'
        ");
        return $query->result_array();
    }


    public function get_cashbankforupdat($id)
    {
        $query = $this->db->query(
            "SELECT
                `c`.`id`,
                `s`.`store_name`,
                `ac`.`bank_name`,
                `ac`.`bank_code`
            FROM
                `cash_bank` `c`,
                `stores` `s`,
                `accredited_banks` `ac`
            WHERE
                `c`.`store_id` = `s`.`id`
            AND
                `c`.`bank_id` = `ac`.`id`
            AND
                `c`.`status` = 'Active'
            AND
                `c`.`id` = '$id'
            LIMIT 1
        ");
        return $query->result_array();
    }


    public function get_mycashbank($id)
    {
        $query = $this->db->query(
            "SELECT
                `ac`.`bank_name`,
                `ac`.`bank_code`
            FROM
                `cash_bank` `c`,
                `stores` `s`,
                `accredited_banks` `ac`
            WHERE
                `c`.`store_id` = `s`.`id`
            AND
                `c`.`bank_id` = `ac`.`id`
            AND
                `c`.`status` = 'Active'
            AND
                `c`.`store_id` = '$id'
            LIMIT 1
        ");
        return $query->result_array();
    }

    public function get_monthly_receivable($beginning_date, $end_date, $tenancy_type){
        $flag = 'Short Term';
        if ($tenancy_type == 'Long Term Tenants') {
            $flag = 'Long Term';
        }


        $query = $this->db->query("
            SELECT 
                p.trade_name,
                t.tenant_id,
                t.rental_type,
                p.trade_name,
                t.status,
                r.description,
                t.basic_rental + SUM(Case When r.description = 'Rental Incrementation' Then ABS(r.amount) Else 0 End) basic_rental,
                SUM(Case When r.description = 'Rental Incrementation' Then ABS(r.amount) Else 0 End) rental_increment,
                SUM(Case When r.description = 'Basic Rent' Then ABS(r.amount) Else 0 End) basic_rent,
                SUM(Case When r.description = 'Discount' Then ABS(r.amount) Else 0 End) discount,
                SUM(Case When r.description = 'VAT' Then ABS(r.amount) Else 0 End) vat,
                SUM(Case When r.description = 'Expanded Withholding Tax' Then ABS(r.amount) Else 0 End) expanded_tax,
                SUM(Case When r.description = 'WHT' Then ABS(r.amount) Else 0 End) wht,
                SUM(Case When r.description = 'Net Rental' Then ABS(r.amount) Else 0 End) net_rental,
                SUM(Case When r.description = 'Common Usage Charges' Then ABS(r.amount) Else 0 End) cusa,
                SUM(Case When r.description = 'Aircon' Then ABS(r.amount) Else 0 End) aircon,
                SUM(Case When r.description = 'Chilled Water' Then ABS(r.amount) Else 0 End) chilled_water,
                SUM(Case When r.description = 'Electricity' 
                    OR r.description = 'Electricity - Adjustment' 
                    Then ABS(r.amount) Else 0 End) electricity,
                SUM(Case When r.description = 'Water' Then ABS(r.amount) Else 0 End) water,
                SUM(Case When r.description = 'Gas' Then ABS(r.amount) Else 0 End) gas,
                SUM(Case When r.description = 'Pest Control' Then ABS(r.amount) Else 0 End) pest_control,
                SUM(Case When r.description = 'Bio Augmentation' Then ABS(r.amount) Else 0 End) bio_aug,
                SUM(Case When r.description = 'Service Request' 
                    OR r.description = 'Service Request from ASC Construction' 
                    Then ABS(r.amount) Else 0 End) service_request,
                SUM(Case When r.description = 'Overtime and Overnight' Then ABS(r.amount) Else 0 End) overtime,
                SUM(Case When r.description = 'Employee ID' 
                    OR r.description='Worker ID' 
                    Then ABS(r.amount) Else 0 End) id_charges,
                SUM(Case When r.description = 'Fax' Then ABS(r.amount) Else 0 End) fax,
                SUM(Case When r.description = 'Fixed Asset Rental' Then ABS(r.amount) Else 0 End) fixed_asset,
                SUM(Case When r.description = 'Penalty' 
                    OR r.description = 'Late Payment Penalty' 
                    OR r.description = 'penalty house rules violation'
                    OR r.description = 'Penalty for late Opening and Early Closing' 
                    OR r.description = 'Penalty late submission of sales report'  
                    Then ABS(r.amount) Else 0 End) penalty,
                SUM(Case When r.description = 'Motorcade Charges' Then ABS(r.amount) Else 0 End) motorcade_charges,
                SUM(Case When r.description = 'Security Charges' Then ABS(r.amount) Else 0 End) sec_charges,
                SUM(Case When r.description = 'Plywood' 
                    OR r.description = 'Plywood Enclosure'  
                    OR r.description = 'Plywood_Enclosure'  
                    Then ABS(r.amount) Else 0 End) plywood,
                SUM(Case When r.description = 'PVC Door and Lock Set' Then ABS(r.amount) Else 0 End) pvc,
                SUM(Case When r.description = 'Exhaust Duct Cleaning Charges' Then ABS(r.amount) Else 0 End) exhaust,
                SUM(Case When r.description = 'Training Room Charges' Then ABS(r.amount) Else 0 End) training_room,
                SUM(Case When r.description = 'Storage Room Charges' Then ABS(r.amount) Else 0 End) storage_room,
                SUM(Case When r.description = 'Neon lights' Then ABS(r.amount) Else 0 End) neon_lights,
                SUM(Case When r.description = 'Roll Up Door' Then ABS(r.amount) Else 0 End) roll_up,
                SUM(Case When r.description = 'Adbox Charges' Then ABS(r.amount) Else 0 End) addbox,
                SUM(Case When r.description = 'Unauthorized Closure' Then ABS(r.amount) Else 0 End) unatho_closure,
                SUM(Case When r.description = 'Houserules Violation' Then ABS(r.amount) Else 0 End) house_rules,
                SUM(Case When r.description = 'Notary Fee' Then ABS(r.amount) Else 0 End) notary_fee,
                SUM(Case When r.description = 'Late submission of Deposit Slip' Then ABS(r.amount) Else 0 End) late_depositSlip,
                SUM(Case When r.description = 'Bannerboard Charges' Then ABS(r.amount) Else 0 End) banner_board,
                SUM(Case When r.description = 'Forwarded Balance' 
                    OR r.description = 'Forwarded Balances'
                    Then ABS(r.amount) Else 0 End) forwarded_balance,
                SUM(Case When r.description = 'LED WALL' Then ABS(r.amount) Else 0 End) led_wall,
                SUM(Case When r.description = 'Others' 
                    OR r.description = 'Standy'
                    OR r.description = 'Sprinkler Water Draining Charging'
                    OR r.description = 'Housekeeping Charges'
                    OR r.description = 'Lamp Post Charges'
                    OR r.description = 'FIRE DETECTION AND ALARM SYSTEM'
                    OR r.description = 'During Construction Charges'
                    Then ABS(r.amount) Else 0 End) others,
                SUM(Case When r.description = 'Adjustment VAT Output' Then ABS(r.amount) Else 0 End) adjustment_VAT,
                SUM(Case When r.description = 'Gas Leak Detector' Then ABS(r.amount) Else 0 End) leak_detector,
                SUM(Case When r.description = 'Glass Service' Then ABS(r.amount) Else 0 End) glass_service,
                SUM(Case When r.description = 'Food Coupon' Then ABS(r.amount) Else 0 End) coupon
            FROM
                (SELECT t1.* 
                FROM (SELECT * FROM tenants WHERE  (status = 'Active' OR status = 'Terminated') AND tenancy_type = '$flag' AND rental_type != ''
                    ORDER BY id DESC) t1 
                GROUP BY t1.tenant_id) t
            LEFT JOIN
                prospect p ON p.id = t.prospect_id
            INNER JOIN
                 (SELECT 
                    * 
                FROM  
                    monthly_receivable_report 
                WHERE 
                    (posting_date BETWEEN '$beginning_date' AND '$end_date')
                AND 
                    (
                           description = 'Rental Incrementation'
                        OR description = 'Basic Rent'
                        OR description = 'Discount'
                        OR description = 'VAT'
                        OR description = 'Expanded Withholding Tax'
                        OR description = 'WHT'
                        OR description = 'Net Rental'
                        OR description = 'Common Usage Charges'
                        OR description = 'Aircon'
                        OR description = 'Chilled Water'
                        OR description = 'Electricity'
                        OR description = 'Electricity - Adjustment'
                        OR description = 'Water'
                        OR description = 'Gas'
                        OR description = 'Pest Control'
                        OR description = 'Bio Augmentation'
                        OR description = 'Service Request'
                        OR description = 'Service Request from ASC Construction'
                        OR description = 'Overtime and Overnight'
                        OR description = 'Employee ID' 
                        OR description = 'Worker ID'
                        OR description = 'Fax'
                        OR description = 'Fixed Asset Rental'
                        OR description = 'Penalty' 
                        OR description = 'Late Payment Penalty' 
                        OR description = 'penalty house rules violation'
                        OR description = 'Penalty for late Opening and Early Closing'
                        OR description = 'Penalty late submission of sales report'
                        OR description = 'Motorcade Charges'
                        OR description = 'Security Charges'
                        OR description = 'Plywood'
                        OR description = 'Plywood Enclosure'  
                        OR description = 'Plywood_Enclosure' 
                        OR description = 'PVC Door and Lock Set'
                        OR description = 'Exhaust Duct Cleaning Charges'
                        OR description = 'Training Room Charges'
                        OR description = 'Storage Room Charges'
                        OR description = 'Neon lights'
                        OR description = 'Roll Up Door'
                        OR description = 'Adbox Charges'
                        OR description = 'Unauthorized Closure'
                        OR description = 'Houserules Violation'
                        OR description = 'Notary Fee'
                        OR description = 'Late submission of Deposit Slip'
                        OR description = 'Bannerboard Charges'
                        OR description = 'Forwarded Balance'
                        OR description = 'Forwarded Balances'
                        OR description = 'LED WALL'
                        OR description = 'Others' 
                        OR description = 'Standy'
                        OR description = 'Sprinkler Water Draining Charging'
                        OR description = 'Housekeeping Charges'
                        OR description = 'Lamp Post Charges'
                        OR description = 'FIRE DETECTION AND ALARM SYSTEM'
                        OR description = 'During Construction Charges'
                        OR description = 'Adjustment VAT Output'
                        OR description = 'Gas Leak Detector'
                        OR description = 'Glass Service'
                        OR description = 'Food Coupon'
                    )
                ) r ON r.tenant_id = t.tenant_id 
            WHERE
                t.store_code LIKE '%" . $this->session->userdata('store_code') . "%'
            GROUP BY 
                t.tenant_id
            ORDER BY
                t.tenant_id DESC
                
            ");

        return $query->result_array();
    }

    /*public function get_monthly_receivable($beginning_date, $end_date, $tenancy_type)
    {
        $flag = 'Short Term';
        if ($tenancy_type == 'Long Term Tenants') {
            $flag = 'Long Term';
        }


        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `t`.`rental_type`,
                `t`.`basic_rental` + (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Rental Incrementation' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `basic_rental`,
                `p`.`trade_name`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Basic Rent' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `basic_rent`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Discount' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `discount`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'VAT' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `vat`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Expanded Withholding Tax' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `expanded_tax`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'WHT' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `wht`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Net Rental' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `net_rental`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Common Usage Charges' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `cusa`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Aircon' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `aircon`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Chilled Water' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `chilled_water`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND (`mrr`.`description` = 'Electricity' OR `mrr`.`description` = 'Electricity - Adjustment') AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `electricity`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Water' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `water`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Gas' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `gas`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Pest Control' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `pest_control`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Bio Augmentation' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `bio_aug`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND (`mrr`.`description` = 'Service Request' || `mrr`.`description` = 'Service Request from ASC Construction') AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `service_request`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Overtime and Overnight' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `overtime`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND (`mrr`.`description` = 'Employee ID' OR `mrr`.`description` = 'Worker ID') AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `id_charges`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Fax' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `fax`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Fixed Asset Rental' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `fixed_asset`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND (`mrr`.`description` = 'Penalty' || `mrr`.`description` = 'Late Payment Penalty' || `mrr`.`description` = 'penalty house rules violation' || `mrr`.`description` = 'Penalty for late Opening and Early Closing' || `mrr`.`description` = 'Penalty late submission of sales report') AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `penalty`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Motorcade Charges' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `motorcade_charges`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Security Charges' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `sec_charges`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND (`mrr`.`description` = 'Plywood' || `mrr`.`description` = 'Plywood Enclosure' || `mrr`.`description` = 'Plywood_Enclosure') AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `plywood`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'PVC Door and Lock Set' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `pvc`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Exhaust Duct Cleaning Charges' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `exhaust`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Training Room Charges' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `training_room`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Storage Room Charges' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `storage_room`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Neon lights' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `neon_lights`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Roll Up Door' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `roll_up`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Adbox Charges' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `addbox`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Unauthorized Closure' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `unatho_closure`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Houserules Violation' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `house_rules`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Notary Fee' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `notary_fee`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Late submission of Deposit Slip' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `late_depositSlip`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Bannerboard Charges' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `banner_board`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND (`mrr`.`description` = 'Forwarded Balance' || `mrr`.`description` = 'Forwarded Balances') AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `forwarded_balance`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'LED WALL' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `led_wall`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND (`mrr`.`description` = 'Others' || `mrr`.`description` = 'Standy' || `mrr`.`description` = 'Sprinkler Water Draining Charging' || `mrr`.`description` = 'Housekeeping Charges' || `mrr`.`description` = 'Lamp Post Charges' || `mrr`.`description` = 'FIRE DETECTION AND ALARM SYSTEM' || `mrr`.`description` = 'During Construction Charges') AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `others`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Adjustment VAT Output' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `adjustment_VAT`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Gas Leak Detector' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `leak_detector`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Glass Service' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `glass_service`,
                (SELECT IFNULL(ROUND( SUM(ABS(`amount`)) ,2), 0) FROM `monthly_receivable_report` `mrr` WHERE `mrr`.`tenant_id` = `t`.`tenant_id` AND `mrr`.`description` = 'Food Coupon' AND (`mrr`.`posting_date` BETWEEN '$beginning_date' AND '$end_date'))  AS `coupon`
            FROM
                `tenants` `t`,
                `prospect` `p`
            WHERE
                `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `t`.`tenancy_type` = '$flag'
            AND
                `t`.`tenant_id` IN (SELECT tenant_id FROM monthly_receivable_report WHERE posting_date BETWEEN '$beginning_date' AND '$end_date'  GROUP BY tenant_id)
            AND
                `t`.`rental_type` != ''
            GROUP BY t.tenant_id
            ORDER BY t.id DESC
        ");


        return $query->result_array();

    }*/


    public function get_receivable_summary($as_of_date)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `p`.`trade_name`,
                `p`.`contact_person`,
                ROUND(SUM(`gl`.`debit`) - (IFNULL((SELECT SUM(`gl1`.`debit`) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `acc1`.`id` = `gl1`.`gl_accountID` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.07.01' OR `acc1`.`gl_code` = '10.10.01.03.11' OR `acc1`.`gl_code` = '10.10.01.03.11.01' OR `acc1`.`gl_code` = '10.10.01.03.11.02') AND `gl`.`tenant_id` = `gl1`.`tenant_id` AND `gl1`.`posting_date` <= '$as_of_date' AND `gl`.`ref_no` = `gl1`.`ref_no`) ,0)) ,2) AS `total`,
                `acc`.`gl_account`
            FROM
                `general_ledger` `gl`,
                `gl_accounts` `acc`,
                `prospect` `p`,
                `tenants` `t`
            WHERE
                `gl`.`gl_accountID` = `acc`.`id`
            AND
                (`acc`.`gl_code` = '10.10.01.03.16' OR `acc`.`gl_code` = '10.10.01.03.03')
            AND
                `gl`.`posting_date` <= '$as_of_date'
            AND
                `gl`.`tenant_id` = `t`.`tenant_id`
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
            GROUP BY
                 `t`.`tenant_id`
            ORDER BY `p`.`trade_name` ASC
        ");
        return $query->result_array();
    }


    public function AR_delinquent($as_of_date)
    {
        $query = $this->db->query(
            "SELECT
                DISTINCT
                `t`.`tenant_id`,
                DATE_FORMAT(`gl`.`posting_date`, '%b') AS `due_date`,
                ROUND(SUM(`gl`.`debit`) - IFNULL((SELECT SUM(ABS(`gl1`.`debit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.07.01' OR `acc1`.`gl_code` = '10.10.01.03.11' OR `acc1`.`gl_code` = '10.10.01.03.11.01' OR `acc1`.`gl_code` = '10.10.01.03.11.02') AND `gl1`.`posting_date` <= '$as_of_date'),0) ,2) AS `total`
            FROM
                `general_ledger` `gl`,
                `gl_accounts` `acc`,
                `prospect` `p`,
                `tenants` `t`
            WHERE
                `gl`.`gl_accountID` = `acc`.`id`
            AND
                (`acc`.`gl_code` = '10.10.01.03.03')
            AND
                `gl`.`posting_date` <= '$as_of_date'
            AND
                `gl`.`tenant_id` = `t`.`tenant_id`
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
           GROUP BY `tenant_id`, `due_date`
        ");
        return $query->result_array();
    }


    public function RR_summary($as_of_date)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `t`.`contact_person`,
                `t`.`trade_name`,
                ROUND(SUM(`gl`.`debit`) - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND  (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16' OR `acc1`.`gl_code` = '10.10.01.01.01') AND `gl1`.`posting_date` <= '$as_of_date' AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) AS `total`
            FROM
                `general_ledger` `gl`,
                (SELECT t.tenant_id, p.trade_name, p.contact_person FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS t
            WHERE
                `gl`.`tenant_id` = t.tenant_id
            AND
                `gl`.`due_date` <= '$as_of_date'
            AND
                `gl`.`tag` = 'Basic Rent'
            AND
                ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no`  AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16' OR `acc1`.`gl_code` = '10.10.01.01.01') AND `gl1`.`posting_date` <= '$as_of_date' AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) > 1
           GROUP BY `t`.`tenant_id`
        ");

        return $query->result_array();
    }


    public function AR_summary($as_of_date)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `t`.`contact_person`,
                `t`.`trade_name`,
                ROUND(SUM(`gl`.`debit`) - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND  (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.03.04') AND `gl1`.`posting_date` <= '$as_of_date' AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) AS `total`
            FROM
                `general_ledger` `gl`,
                (SELECT t.tenant_id, p.trade_name, p.contact_person FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS t
            WHERE
                `gl`.`tenant_id` = t.tenant_id
            AND
                `gl`.`due_date` <= '$as_of_date'
            AND
                (`gl`.`tag` = 'Other' OR `gl`.`tag` = 'Penalty')
            AND
                ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no`  AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.03.04') AND `gl1`.`posting_date` <= '$as_of_date' AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) > 1
           GROUP BY `t`.`tenant_id`
        ");


        return $query->result_array();
    }

    public function delinquent($as_of_date)
    {
        $query = $this->db->query(
            "SELECT *  FROM
                (SELECT
                    DISTINCT
                    `p`.`contact_person`,
                    `p`.`trade_name`,
                    `t`.`tenant_id`,
                    `acc`.`gl_account`,
                    `gl`.`posting_date`,
                    DATE_FORMAT(`gl`.`posting_date`, '%b') AS `due_date`,
                    ROUND(SUM(`gl`.`debit`) - IFNULL((SELECT SUM(ABS(`gl1`.`debit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.07.01' OR `acc1`.`gl_code` = '10.10.01.03.11' OR `acc1`.`gl_code` = '10.10.01.03.11.01' OR `acc1`.`gl_code` = '10.10.01.03.11.02') AND `gl1`.`posting_date` <= '$as_of_date'),0) ,2) AS `total`
                 FROM
                    `general_ledger` `gl`,
                    `gl_accounts` `acc`,
                    `prospect` `p`,
                    `tenants` `t`
                WHERE
                    `gl`.`gl_accountID` = `acc`.`id`
                AND
                    (`acc`.`gl_code` = '10.10.01.03.03' || `acc`.`gl_code` = '10.10.01.03.16')
                AND
                    `gl`.`posting_date` <= '$as_of_date'
                AND
                    `gl`.`tenant_id` = `t`.`tenant_id`
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
                GROUP BY `gl`.`tenant_id`, `gl`.`due_date`, `acc`.`id`
                ORDER BY `p`.`trade_name`, `gl`.`id` ASC) as tmp
            WHERE
                total > 1
            ORDER BY trade_name, posting_date ASC
        ");

        return $query->result_array();
    }

    public function partial_payment($from_date, $as_of_date)
    {
        $query = $this->db->query(
            "SELECT
                DISTINCT
                `p`.`tenant_id`,
                CONCAT(`p`.`receipt_no`, '-', `p`.`check_no`, ROUND(`p`.`amount_paid` ,2)) AS `partial`
            FROM
                `payment_scheme` `p`,
                `general_ledger` `gl`
            WHERE
                `p`.`tenant_id` = `gl`.`tenant_id`
            AND
                `p`.`receipt_no` = `gl`.`doc_no`
            AND
                `p`.`billing_period` != 'Upon Signing of Notice'
            AND
                (`gl`.`posting_date` BETWEEN '$from_date' AND '$as_of_date')
        ");

        return $query->result_array();
    }


    public function get_locationCodePerTenant()
    {
        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
        {
            $query = $this->db->query(
                "SELECT
                    `c1`.`id` AS `category_id`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `t`.`tenancy_type`,
                    `lc`.`location_code`,
                    `lc`.`location_desc` AS `description`,
                    `p`.`trade_name`,
                    `t`.`rental_type`,
                    CONCAT(`t`.`opening_date`, '<=>', `t`.`expiry_date`) AS `contract_date`
                FROM
                    `location_code` `lc`,
                    `prospect` `p`,
                    `tenants` `t`,
                    `floors` `f`,
                    `category_one` `c1`,
                    `stores` `s`
                WHERE
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `t`.`status` = 'Active'
                AND
                    `lc`.`status` = 'Active'
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `p`.`first_category` = `c1`.`category_name`
                ORDER BY
                    `s`.`store_name`
                ASC
            ");
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lc`.`location_code`,
                    `lc`.`location_desc` AS `description`,
                    `p`.`trade_name`,
                    CONCAT(`t`.`opening_date`, '<=>', `t`.`expiry_date`) AS `contract_date`
                FROM
                    `location_code` `lc`,
                    `prospect` `p`,
                    `tenants` `t`,
                    `floors` `f`,
                    `stores` `s`
                WHERE
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `t`.`status` = 'Active'
                AND
                    `lc`.`status` = 'Active'
                AND
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                ORDER BY
                    `s`.`store_name`
                ASC
            ");
        }


        return $query;
    }


    public function get_longtermHistory()
    {
        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`tenant_id`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`contact_person`,
                    `p`.`contact_number`
                FROM
                    `tenants` `t`,
                    `prospect` `p`
                WHERE
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `p`.`flag` = 'Long Term'
                GROUP BY
                    `t`.`tenant_id`
            ");

            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`tenant_id`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`contact_person`,
                    `p`.`contact_number`
                FROM
                    `tenants` `t`,
                    `prospect` `p`
                WHERE
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `p`.`flag` = 'Long Term'
                AND
                    `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
                GROUP BY
                    `t`.`tenant_id`
            ");

            return $query->result_array();
        }
    }

    public function view_history($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`contract_no`,
                `t`.`rental_type`,
                `t`.`rent_percentage`,
                CONCAT(`t`.`opening_date`, ' <==> ', `t`.`expiry_date`) AS `contract_date`,
                `p`.`trade_name`,
                `lc`.`location_code`,
                `lc`.`floor_area`,
                `t`.`basic_rental`,
                `lc`.`slots_id`,
                `lc`.`status`
            FROM
                `tenants` `t`,
                `prospect` `p`,
                `location_code` `lc`
            WHERE
                `t`.`prospect_id` = `p`.`id`
            AND
                `t`.`locationCode_id` = `lc`.`id`
            AND
                `t`.`tenant_id` = '$tenant_id'
            GROUP BY
                `t`.`id`
        ");

        $history = $query->result_array();


        foreach ($history as $key => $column) {
            $slots = explode(',', $column['slots_id']);

            $slots = "'".implode($slots, "','")."'";

            $slots = $this->db->query("SELECT `slot_no` FROM `location_slot` WHERE id IN ($slots)")->result_array();

            foreach ($slots as $k => $slot) {
                $slots[$k] = $slot['slot_no'];
            }

            $history[$key]['slots'] =  implode($slots, ', ');
        }

        return $history;
    } 

    /*public function view_history($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `t`.`contract_no`,
                `t`.`rental_type`,
                `t`.`rent_percentage`,
                CONCAT(`t`.`opening_date`, ' <==> ', `t`.`expiry_date`) AS `contract_date`,
                `p`.`trade_name`,
                `lc`.`location_code`,
                `lc`.`floor_area`,
                `t`.`basic_rental`
            FROM
                `tenants` `t`,
                `prospect` `p`,
                `location_code` `lc`
            WHERE
                `t`.`prospect_id` = `p`.`id`
            AND
                `t`.`locationCode_id` = `lc`.`id`
            AND
                `t`.`tenant_id` = '$tenant_id'
            GROUP BY
                `t`.`id`
        ");

        return $query->result_array();
    }*/



    public function get_shorttermHistory()
    {
        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor' || $this->session->userdata('user_type') == 'Corporate Documentation Officer')
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`tenant_id`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`contact_person`,
                    `p`.`contact_number`
                FROM
                    `tenants` `t`,
                    `prospect` `p`
                WHERE
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `p`.`flag` = 'Short Term'
                GROUP BY
                    `t`.`tenant_id`
            ");

            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    `t`.`tenant_id`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`contact_person`,
                    `p`.`contact_number`
                FROM
                    `tenants` `t`,
                    `prospect` `p`
                WHERE
                    `t`.`prospect_id` = `p`.`id`
                AND
                    `p`.`flag` = 'Short Term'
                AND
                    `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
                GROUP BY
                    `t`.`tenant_id`
            ");

            return $query->result_array();
        }
    }


    public function get_agingRR($date)
    {

        $query = $this->db->query(
                "SELECT
                    rr.tenant_id,
                    rr.trade_name,
                    SUM(rr.current) as current,
                    SUM(rr.days7) AS days7,
                    SUM(rr.days15) as days15,
                    SUM(rr.days30) as days30,
                    SUM(rr.days60) AS days60,
                    SUM(rr.days90) AS days90,
                    SUM(rr.days180) AS days180,
                    SUM(rr.days360) AS days360
                FROM
                    (SELECT
                        report.tenant_id,
                        report.trade_name,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 7 AND DATEDIFF('$date', report.due_date) >= 0) THEN report.balance ELSE 0 END) as current,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 15 AND DATEDIFF('$date', report.due_date) >= 7) THEN report.balance ELSE 0 END) as days7,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 30 AND DATEDIFF('$date', report.due_date) >= 15) THEN report.balance ELSE 0 END) as days15,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 60 AND DATEDIFF('$date', report.due_date) >= 30) THEN report.balance ELSE 0 END) as days30,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 90 AND DATEDIFF('$date', report.due_date) >= 60) THEN report.balance ELSE 0 END) as days60,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 180 AND DATEDIFF('$date', report.due_date) >= 90) THEN report.balance ELSE 0 END) as days90,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 360 AND DATEDIFF('$date', report.due_date) >= 180) THEN report.balance ELSE 0 END) as days180,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) >= 360) THEN report.balance ELSE 0 END) as days360
                    FROM
                        (SELECT
                            `t`.`tenant_id`,
                            `t`.`trade_name`,
                            `gl`.`posting_date`,
                            `gl`.`due_date`,
                            ROUND(SUM(`gl`.`debit`) - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND  (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16' OR `acc1`.`gl_code` = '10.10.01.01.01') AND `gl1`.`posting_date` <= '$date' AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) AS `balance`
                        FROM
                            `general_ledger` `gl`,
                            (SELECT t.tenant_id, p.trade_name FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS t
                        WHERE
                            `gl`.`tenant_id` = t.tenant_id
                        AND
                            `gl`.`due_date` <= '$date'
                        AND
                            (`gl`.`tag` = 'Basic Rent' || gl.tag = 'Retro Rent')
                        AND
                            ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no`  AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.16' OR `acc1`.`gl_code` = '10.10.01.01.01') AND `gl1`.`posting_date` <= '$date' AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) > 1
                        GROUP BY `gl`.`ref_no`, `gl`.`due_date`) AS report) as rr
                    GROUP BY rr.tenant_id
                    ORDER BY rr.trade_name ASC
                ");
            return $query;
    }



    public function get_agingAR($date)
    {
        $query = $this->db->query(
                "SELECT
                    rr.tenant_id,
                    rr.trade_name,
                    SUM(rr.current) as current,
                    SUM(rr.days7) AS days7,
                    SUM(rr.days15) as days15,
                    SUM(rr.days30) as days30,
                    SUM(rr.days60) AS days60,
                    SUM(rr.days90) AS days90,
                    SUM(rr.days180) AS days180,
                    SUM(rr.days360) AS days360
                FROM
                    (SELECT
                        report.tenant_id,
                        report.trade_name,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 7 AND DATEDIFF('$date', report.due_date) >= 0) THEN report.balance ELSE 0 END) as current,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 15 AND DATEDIFF('$date', report.due_date) >= 7) THEN report.balance ELSE 0 END) as days7,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 30 AND DATEDIFF('$date', report.due_date) >= 15) THEN report.balance ELSE 0 END) as days15,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 60 AND DATEDIFF('$date', report.due_date) >= 30) THEN report.balance ELSE 0 END) as days30,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 90 AND DATEDIFF('$date', report.due_date) >= 60) THEN report.balance ELSE 0 END) as days60,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 180 AND DATEDIFF('$date', report.due_date) >= 90) THEN report.balance ELSE 0 END) as days90,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) < 360 AND DATEDIFF('$date', report.due_date) >= 180) THEN report.balance ELSE 0 END) as days180,
                        (CASE WHEN (DATEDIFF('$date', report.due_date) >= 360) THEN report.balance ELSE 0 END) as days360
                    FROM
                        (SELECT
                            `t`.`tenant_id`, 
                            `t`.`trade_name`,
                            `gl`.`posting_date`,
                            `gl`.`due_date`,
                            ROUND(SUM(`gl`.`debit`) - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no` AND  (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.03.04') AND `gl1`.`posting_date` <= '$date' AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) AS `balance`
                        FROM
                            `general_ledger` `gl`,
                            (SELECT t.tenant_id, p.trade_name FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS t
                        WHERE
                            `gl`.`tenant_id` = t.tenant_id
                        AND
                            `gl`.`due_date` <= '$date'
                        AND
                            (`gl`.`tag` = 'Other' OR `gl`.`tag` = 'Penalty')
                        AND
                            ROUND(`gl`.`debit` - IFNULL((SELECT SUM(abs(`gl1`.`credit`)) FROM `general_ledger` `gl1`, `gl_accounts` `acc1` WHERE `gl`.`ref_no` = `gl1`.`ref_no`  AND (`acc1`.`gl_code` = '10.10.01.01.02' OR `acc1`.`gl_code` = '10.10.01.03.03' OR `acc1`.`gl_code` = '10.10.01.03.04') AND `gl1`.`posting_date` <= '$date' AND `gl1`.`gl_accountID` = `acc1`.`id` AND (`gl1`.`status` != 'Cleared' OR `gl1`.`status` IS NULL)) ,0), 2) > 1
                        GROUP BY `gl`.`ref_no`, `gl`.`due_date`) AS report) as rr
                    GROUP BY rr.tenant_id
                    ORDER BY rr.trade_name ASC
            ");
            return $query;
    }

    public function get_tenantData($trade_name)
    {

        $trade_name = trim($trade_name);
        $query = $this->db->query(
            "SELECT
                `t`.`contract_no`,
                `p`.`trade_name`,
                `t`.`tenant_id`,
                `t`.`rental_type`,
                `t`.`store_code`
            FROM
                `tenants` `t`,
                `prospect` `p`
            WHERE
                `t`.`prospect_id` = `p`.`id`
            AND
                `p`.`trade_name` = '$trade_name'
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
        ");

        return $query->result_array();

    }


    public function get_paymentProofList($tenant_id, $from, $to)
    {
        $query = $this->db->query(
            "SELECT
                `ps`.`soa_no`,
                `ps`.`tender_typeDesc`,
                `ps`.`receipt_no`,
                `ps`.`amount_paid`,
                `sl`.`posting_date`
            FROM
                `payment_scheme` `ps`,
                `subsidiary_ledger` `sl`
            WHERE
                `ps`.`tenant_id` = '$tenant_id'
            AND
                `ps`.`tenant_id` = `sl`.`tenant_id`
            AND
                `sl`.`doc_no` = `ps`.`receipt_no`
            AND
                (`sl`.`posting_date` BETWEEN '$from' AND '$to')
            GROUP BY
                `sl`.`posting_date`
        ");

        return $query->result_array();
    }


    public function get_wofDetails()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`trade_name`,
                `t`.`tenant_id`,
                `t`.`contract_no`,
                `t`.`tenant_type`
            FROM
                `tenants` `t`,
                `prospect` `p`
            WHERE
                `t`.`rental_type` = 'WOF'
            AND
                `t`.`status` = 'Active'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `store_code` = '" . $this->session->userdata('store_code') . "'
            LIMIT 1
        ");

        return $query->result_array();
    }

    public function is_AGCSubsidiary($tenant_id)
    {
        $tenant_type = $this->db->query("SELECT `tenant_type` FROM `tenants` WHERE `tenant_id` = '$tenant_id' LIMIT 1")->row()->tenant_type;

        if ($tenant_type == 'AGC-Subsidiary')
        {
            return true;
        }
        return false;

    }

    public function is_penaltyExempt($tenant_id) {
        $tenant_type = $this->db->query("SELECT `penalty_exempt` FROM `tenants` WHERE `tenant_id` = '$tenant_id' ORDER BY id DESC LIMIT 1")->row()->penalty_exempt;

        if ($tenant_type == '1')
        {
            return true;
        }
        return false;
    }
    public function get_sundays($collection_date, $payment_date)
    {
        $start = new DateTime($collection_date);
        $end = new DateTime($payment_date);
        $days = $start->diff($end, true)->days;
        $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

        return $sundays;
    }


    public function activeTenants()
    {
        $query = $this->db->query(
                "SELECT
                    `t`.`id`,
                    `t`.`tenant_id`,
                    `t`.`opening_date`,
                    `t`.`expiry_date`,
                    `p`.`trade_name`,
                    `p`.`corporate_name`,
                    `p`.`address`,
                    `p`.`contact_person`,
                    `p`.`contact_number`,
                    `p`.`first_category`,
                    `p`.`second_category`,
                    `s`.`store_name`,
                    `f`.`floor_name`,
                    `lt`.`leasee_type`,
                    `lc`.`location_code`,
                    `lc`.`area_classification`,
                    `lc`.`area_type`,
                    `lc`.`floor_area`,
                    `lc`.`rental_rate` AS `basic_rental`
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
                    `t`.`locationCode_id` = `lc`.`id`
                AND
                    `t`.`flag` = 'Posted'
                AND
                    `t`.`status` = 'Active'
                AND
                    `lc`.`status` = 'Active'
                AND
                    `lc`.`floor_id` = `f`.`id`
                AND
                    `f`.`store_id` = `s`.`id`
                AND
                    `s`.`id` = '" . $this->_user_group . "'
                GROUP BY
                    `t`.`id`
            ");


        return $query->result_array();
    }

    public function get_forTransfer()
    {
        $query = $this->db->query(
            "SELECT
                `tenant_id`,
                `receipt_no`,
                `supp_doc`
            FROM
                `payment_scheme`
            WHERE
                (`tender_typeDesc` = 'Bank to Bank' || `tender_typeDesc` = 'Check' || `tender_typeDesc` = 'JV payment - Business Unit' || `tender_typeDesc` = 'JV payment - Subsidiary')
        ");

        return $query->result_array();
    }

    public function get_paymentDocs($receipt_no)
    {
        $query = $this->db->query("SELECT `file_name` FROM `payment_supportingdocs` WHERE `receipt_no` = '$receipt_no'");
        return $query->result_array();
    }


    public function get_locationCodeSlotHistory($id)
    {
        $query = $this->db->query(
            "SELECT
                `ls`.`id`,
                `ls`.`slot_no`,
                `ls`.`tenancy_type`,
                `ls`.`floor_area`,
                `ls`.`rental_rate`,
                `f`.`floor_name`,
                `s`.`store_name`,
                `ls`.`modified_date`
            FROM
                `locationSlot_history` `ls`,
                `stores` `s`,
                `floors` `f`
            WHERE
                `ls`.`floor_id` = `f`.`id`
            AND
                `f`.`store_id` = `S`.`id`
            AND
                `ls`.`id` = '$id'
            ORDER BY `modified_date` DESC
        ");

        return $query->result_array();
    }


    public function locationSlot_history($id)
    {
        $query = $this->db->query(
            "INSERT INTO
                locationSlot_history(id, slot_no, tenancy_type, floor_id, floor_area, rental_rate, modified_by)
                (SELECT
                    id,
                    slot_no,
                    tenancy_type,
                    floor_id,
                    floor_area,
                    rental_rate,
                    modified_by
                FROM
                    location_slot
                WHERE id = '$id')
        ");
    }



    public function get_tenants()
    {
        $query = $this->db->query(
            "SELECT
                `t`.`tenant_id`,
                `p`.`trade_name`,
                `p`.`contact_person`
            FROM
                `tenants` `t`,
                `prospect` `p`
            WHERE
                `t`.`store_code` = '" . $this->session->userdata('store_code') . "'
            AND
                `t`.`prospect_id` = `p`.`id`
            AND
                `t`.`rental_type` != ''
            GROUP BY `t`.`tenant_id`
        ");

        return $query->result_array();
    }

    public function get_monthly_report_gl_entry($beginning_date, $end_date)
    {
        $query = $this->db->query(
            "SELECT
                gl.id,
                gl.tenant_id,
                gl.gl_accountID,
                (IFNULL(SUM(gl.debit),0) + IFNULL(SUM(gl.credit),0)) as amount
            FROM
                general_ledger gl
            WHERE
                (gl.posting_date BETWEEN '$beginning_date' AND '$end_date')
            GROUP BY gl.tenant_id, gl.gl_accountID
        ");
        return $query->result_array();
    }


    public function generate_monthly_receivable_summary($month)
    {
        $query = $this->db->query(
            "SELECT
                    report.tenant_id,
                    report.trade_name,
                    SUM(report.amount) AS amount
                FROM
                    (SELECT
                        gl.tenant_id,
                        tenants.trade_name,
                        SUM(gl.debit) - IFNULL((SELECT SUM(ABS(gl1.credit)) FROM subsidiary_ledger gl1 WHERE gl.ref_no = gl1.ref_no AND gl1.document_type = 'Credit Memo' AND gl1.gl_accountID = gl.gl_accountID), 0)  AS amount
                    FROM
                        subsidiary_ledger gl,
                        (SELECT t.tenant_id, p.trade_name FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS tenants
                    WHERE
                        (gl.gl_accountID = '4' OR gl.gl_accountID = '22' OR gl.gl_accountID = '29')
                    AND
                        (gl.document_type = 'Invoice')
                    AND
                        DATE_FORMAT(gl.posting_date, '%M %Y') = '$month'
                    AND
                        tenants.tenant_id = gl.tenant_id
                    GROUP BY
                        gl.ref_no) AS report
                GROUP BY report.tenant_id
                ORDER BY report.trade_name ASC

        ");
        return $query->result_array();
    }


    public function AR_monthly_total($month)
    {

        $query = $this->db->query("SELECT
                    SUM(report.amount) AS amount
                FROM
                    (SELECT
                        gl.tenant_id,
                        tenants.trade_name,
                        SUM(gl.debit) - IFNULL((SELECT SUM(ABS(gl1.credit)) FROM general_ledger gl1 WHERE gl.ref_no = gl1.ref_no AND gl1.document_type = 'Credit Memo' AND gl1.gl_accountID = gl.gl_accountID), 0)  AS amount
                    FROM
                        subsidiary_ledger gl,
                        (SELECT t.tenant_id, p.trade_name FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS tenants
                    WHERE
                        (gl.gl_accountID = '22' || gl.gl_accountID = '29')
                    AND
                        (gl.document_type = 'Invoice')
                    AND
                        DATE_FORMAT(gl.posting_date, '%M %Y') = '$month'
                    AND
                        tenants.tenant_id = gl.tenant_id
                    GROUP BY
                        gl.ref_no) AS report")->row()->amount;

        return $query;
    }



    public function RR_monthly_total($month)
    {

        $query = $this->db->query("SELECT
                    SUM(report.amount) AS amount
                FROM
                    (SELECT
                        gl.tenant_id,
                        tenants.trade_name,
                        SUM(gl.debit) AS amount
                    FROM
                        subsidiary_ledger gl,
                        (SELECT t.tenant_id, p.trade_name FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS tenants
                    WHERE
                        (gl.gl_accountID = '4')
                    AND
                        (gl.document_type = 'Invoice')
                    AND
                        DATE_FORMAT(gl.posting_date, '%M %Y') = '$month'
                    AND
                        tenants.tenant_id = gl.tenant_id
                    GROUP BY
                        gl.ref_no) AS report")->row()->amount;

        return $query;
    }

    
    public function generate_preop_summary($month)
    {
        $query = $this->db->query(
            "SELECT
                mrr.tenant_id,
                tenants.trade_name,
                mrr.description,
                IFNULL(SUM(ABS(amount)), 0) AS amount
            FROM
                (SELECT t.tenant_id, p.trade_name FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS tenants,
                monthly_receivable_report mrr
            WHERE
                mrr.tenant_id = tenants.tenant_id
            AND
                (mrr.description = 'Security Deposit - Kiosk and Cart' OR mrr.description = 'Security Deposit' OR mrr.description = 'Construction Bond')
            AND
                DATE_FORMAT(mrr.posting_date, '%M %Y') = '$month'
            GROUP BY mrr.tenant_id, mrr.description
        ");
        return $query->result_array();
    }


    public function generate_monthly_payable($month)
    {

        $query = $this->db->query(
            "SELECT
                report.tenant_id,
                report.trade_name,
                SUM(report.amount) AS amount,
                SUM(report.amount_paid) AS amount_paid,
                report.posting_date,
                report.transaction_date,
                report.due_date
            FROM
                (SELECT
                    gl.tenant_id,
                    tenants.trade_name,
                    gl.posting_date,
                    gl.transaction_date,
                    gl.due_date,
                    SUM(gl.debit) - IFNULL((SELECT SUM(ABS(gl1.credit)) FROM general_ledger gl1 WHERE gl.ref_no = gl1.ref_no AND gl1.document_type = 'Credit Memo' AND gl1.gl_accountID = gl.gl_accountID), 0)  AS amount,
                    IFNULL((SELECT SUM(ABS(gl1.credit)) FROM general_ledger gl1 WHERE gl.ref_no = gl1.ref_no AND gl1.document_type = 'Payment' AND gl1.gl_accountID = gl.gl_accountID), 0) AS amount_paid
                FROM
                    general_ledger gl,
                    (SELECT t.tenant_id, p.trade_name  FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS tenants
                WHERE
                    (gl.gl_accountID = '4' OR gl.gl_accountID = '22' OR gl.gl_accountID = '29')
                AND
                    (gl.document_type = 'Invoice')
                AND
                    DATE_FORMAT(gl.posting_date, '%M %Y') = '$month'
                AND
                    tenants.tenant_id = gl.tenant_id
                GROUP BY
                    gl.ref_no) AS report
            GROUP BY report.tenant_id
            ORDER BY report.trade_name ASC
        ");

        return $query->result_array();
    }


    public function tenants()
    {
        if ($this->_user_group = '0' || $this->_user_group = NULL)
        {
            $query = $this->db->query(
                "SELECT
                    t.tenant_id,
                    p.trade_name
                FROM
                    tenants t,
                    prospect p
                WHERE
                    t.prospect_id = p.id
                GROUP BY
                    t.tenant_id
                ORDER BY
                    t.tenant_id ASC
            ");

            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    t.tenant_id,
                    p.trade_name
                FROM
                    tenants t,
                    prospect p
                WHERE
                    t.prospect_id = p.id
                AND
                    t.store_code = '" . $this->session->userdata('store_code') . "'
                GROUP BY
                    t.tenant_id
                ORDER BY
                    t.tenant_id ASC
            ");

            return $query->result_array();
        }
    }


    
    public function current_receivable($tenant_type, $current_month)
    {

        $last_date =  date('Y-m-t', strtotime($current_month));
        if ($tenant_type == 'Exhibitor')
        {
            $query = $this->db->query(
                    "SELECT
                        current.tenant_id,
                        current.soa_no,
                        current.trade_name,
                        SUM(total) AS current_amount
                    FROM
                        (SELECT
                            tenants.tenant_id,
                            (SELECT GROUP_CONCAT(soa_no SEPARATOR ', ') FROM soa_file where tenant_id = tenants.tenant_id AND DATE_FORMAT(posting_date, '%M %Y') = '$current_month') soa_no,
                            p.trade_name,
                            ROUND(IFNULL(SUM(gl.debit), 0) - (IFNULL((SELECT SUM(ABS(gl1.credit)) FROM general_ledger gl1 WHERE  (gl1.gl_accountID = '4' OR gl1.gl_accountID = '22')  AND gl.tenant_id = gl1.tenant_id AND gl1.posting_date <= '$last_date' AND gl.ref_no = gl1.ref_no) ,0)) ,2) AS total
                        FROM
                            (SELECT t.tenant_id, t.store_code, t.prospect_id, t.rental_type
                                FROM general_ledger gl, tenants t
                                WHERE DATE_FORMAT(gl.posting_date, '%M %Y') = '$current_month'
                                AND  gl.tenant_id = t.tenant_id
                                AND  t.store_code = '" . $this->session->userdata('store_code') . "'
                                AND gl.tenant_id != 'DELETED'
                                GROUP BY gl.tenant_id) AS tenants,
                            prospect p,
                            general_ledger gl,
                            leasee_type lt
                        WHERE
                            tenants.prospect_id = p.id
                        AND
                            p.lesseeType_id = lt.id
                        AND
                            lt.leasee_type = 'EXHIBITOR'
                        AND
                            (gl.gl_accountID = '4' OR gl.gl_accountID = '22' OR gl.gl_accountID = '29')
                        AND
                            gl.tenant_id = tenants.tenant_id
                        AND
                            gl.posting_date <= '$last_date'
                        GROUP BY tenants.tenant_id, gl.ref_no
                        ) AS current
                    GROUP BY current.tenant_id
                    HAVING SUM(total) > 1
                    ORDER BY current.trade_name ASC
                ");

            return $query->result_array();
        }
        elseif ($tenant_type == 'Activity Center')
        {
            $query = $this->db->query(
                    "SELECT
                        current.tenant_id,
                        current.soa_no,
                        current.trade_name,
                        SUM(total) AS current_amount
                    FROM
                        (SELECT
                            tenants.tenant_id,
                            (SELECT GROUP_CONCAT(soa_no SEPARATOR ', ') FROM soa_file where tenant_id = tenants.tenant_id AND DATE_FORMAT(posting_date, '%M %Y') = '$current_month') soa_no,
                            p.trade_name,
                            ROUND(IFNULL(SUM(gl.debit), 0) - (IFNULL((SELECT SUM(ABS(gl1.credit)) FROM general_ledger gl1 WHERE  (gl1.gl_accountID = '4' OR gl1.gl_accountID = '22')  AND gl.tenant_id = gl1.tenant_id AND gl1.posting_date <= '$last_date' AND gl.ref_no = gl1.ref_no) ,0)) ,2) AS total
                        FROM
                            (SELECT t.tenant_id, t.store_code, t.prospect_id, t.rental_type
                                FROM general_ledger gl, tenants t
                                WHERE DATE_FORMAT(gl.posting_date, '%M %Y') = '$current_month'
                                AND  gl.tenant_id = t.tenant_id
                                AND  t.store_code = '" . $this->session->userdata('store_code') . "'
                                AND gl.tenant_id != 'DELETED'
                                GROUP BY gl.tenant_id) AS tenants,
                            prospect p,
                            general_ledger gl,
                            leasee_type lt
                        WHERE
                            tenants.prospect_id = p.id
                        AND
                            p.lesseeType_id = lt.id
                        AND
                            lt.leasee_type = 'ACTIVITY CENTER'
                        AND
                            (gl.gl_accountID = '4' OR gl.gl_accountID = '22')
                        AND
                            gl.tenant_id = tenants.tenant_id
                        AND
                            gl.posting_date <= '$last_date'
                        GROUP BY tenants.tenant_id, gl.ref_no
                        ) AS current
                    GROUP BY current.tenant_id
                    HAVING SUM(total) > 1
                    ORDER BY current.trade_name ASC
                ");

            return $query->result_array();
        }
        elseif ($tenant_type == 'External Tenants')
        {
            $query = $this->db->query(
                    "SELECT
                        current.tenant_id,
                        current.soa_no,
                        current.trade_name,
                        SUM(total) AS current_amount
                    FROM
                        (SELECT
                            tenants.tenant_id,
                            (SELECT GROUP_CONCAT(soa_no SEPARATOR ', ') FROM soa_file where tenant_id = tenants.tenant_id AND DATE_FORMAT(posting_date, '%M %Y') = '$current_month') soa_no,
                            p.trade_name,
                            ROUND(IFNULL(SUM(gl.debit), 0) - (IFNULL((SELECT SUM(ABS(gl1.credit)) FROM general_ledger gl1 WHERE  (gl1.gl_accountID = '4' OR gl1.gl_accountID = '22')  AND gl.tenant_id = gl1.tenant_id AND gl1.posting_date <= '$last_date' AND gl.ref_no = gl1.ref_no) ,0)) ,2) AS total
                        FROM
                            (SELECT t.tenant_id, t.store_code, t.prospect_id, t.tenant_type
                                FROM general_ledger gl, tenants t
                                WHERE DATE_FORMAT(gl.posting_date, '%M %Y') = '$current_month'
                                AND  gl.tenant_id = t.tenant_id
                                AND  t.store_code = '" . $this->session->userdata('store_code') . "'
                                AND gl.tenant_id != 'DELETED'
                                AND t.tenant_type != 'AGC-Subsidiary'
                                GROUP BY gl.tenant_id) AS tenants,
                            prospect p,
                            general_ledger gl
                        WHERE
                            tenants.prospect_id = p.id
                        AND
                            (gl.gl_accountID = '4' OR gl.gl_accountID = '22')
                        AND
                            gl.tenant_id = tenants.tenant_id
                        AND
                            gl.posting_date <= '$last_date'
                        GROUP BY tenants.tenant_id, gl.ref_no
                        ) AS current
                    GROUP BY current.tenant_id
                    HAVING SUM(total) > 1
                    ORDER BY current.trade_name ASC
                ");

            return $query->result_array();
        }
        elseif ($tenant_type == 'Internal Tenants')
        {
            $query = $this->db->query(
                    "SELECT
                        current.tenant_id,
                        current.soa_no,
                        current.trade_name,
                        SUM(total) AS current_amount
                    FROM
                        (SELECT
                            tenants.tenant_id,
                            (SELECT GROUP_CONCAT(soa_no SEPARATOR ', ') FROM soa_file where tenant_id = tenants.tenant_id AND DATE_FORMAT(posting_date, '%M %Y') = '$current_month') soa_no,
                            p.trade_name,
                            ROUND(IFNULL(SUM(gl.debit), 0) - (IFNULL((SELECT SUM(ABS(gl1.credit)) FROM general_ledger gl1 WHERE  (gl1.gl_accountID = '4' OR gl1.gl_accountID = '22')  AND gl.tenant_id = gl1.tenant_id AND gl1.posting_date <= '$last_date' AND gl.ref_no = gl1.ref_no) ,0)) ,2) AS total
                        FROM
                            (SELECT t.tenant_id, t.store_code, t.prospect_id, t.tenant_type
                                FROM general_ledger gl, tenants t
                                WHERE DATE_FORMAT(gl.posting_date, '%M %Y') = '$current_month'
                                AND  gl.tenant_id = t.tenant_id
                                AND  t.store_code = '" . $this->session->userdata('store_code') . "'
                                AND gl.tenant_id != 'DELETED'
                                AND t.tenant_type = 'AGC-Subsidiary'
                                GROUP BY gl.tenant_id) AS tenants,
                            prospect p,
                            general_ledger gl
                        WHERE
                            tenants.prospect_id = p.id
                        AND
                            (gl.gl_accountID = '4' OR gl.gl_accountID = '22')
                        AND
                            gl.tenant_id = tenants.tenant_id
                        AND
                            gl.posting_date <= '$last_date'
                        GROUP BY tenants.tenant_id, gl.ref_no
                        ) AS current
                    GROUP BY current.tenant_id
                    HAVING SUM(total) > 1
                    ORDER BY current.trade_name ASC
                ");

            return $query->result_array();
        }

    }


    public function previous_receivable($current_month)
    {
 
        $previous_month = date("F Y", strtotime($current_month . ' -1 months'));
        $last_date_current_month =  date('Y-m-t', strtotime($current_month));
        $last_date_previous_month =  date('Y-m-t', strtotime($previous_month));
        $query = $this->db->query(
            "SELECT
                previous.tenant_id,
                SUM(previous_amount) AS previous_amount,
                SUM(paid_amount) AS paid_amount
            FROM
                (SELECT
                    tenants.tenant_id,
                    p.trade_name,
                    ROUND(IFNULL(SUM(gl.debit), 0) - (IFNULL((SELECT SUM(ABS(gl1.credit)) FROM general_ledger gl1 WHERE (gl1.gl_accountID = '4' OR gl1.gl_accountID = '22')  AND gl.tenant_id = gl1.tenant_id AND gl1.posting_date <= '$last_date_previous_month' AND gl.ref_no = gl1.ref_no) ,0)) ,2) AS previous_amount,
                    (SELECT IFNULL(SUM(ABS(gl1.credit)), 0) FROM general_ledger gl1 WHERE (gl1.gl_accountID = '4' OR gl1.gl_accountID = '22')  AND gl.tenant_id = gl1.tenant_id AND (gl1.posting_date BETWEEN '$last_date_previous_month' AND '$last_date_current_month') AND gl.ref_no = gl1.ref_no) AS paid_amount
                FROM
                    (SELECT t.tenant_id, t.store_code, t.prospect_id, t.rental_type
                    FROM general_ledger gl, tenants t
                    WHERE DATE_FORMAT(gl.posting_date, '%M %Y') = '$previous_month'
                    AND  gl.tenant_id = t.tenant_id
                    AND  t.store_code = '" . $this->session->userdata('store_code') . "'
                    AND gl.tenant_id != 'DELETED'
                    GROUP BY gl.tenant_id) AS tenants,
                    prospect p,
                    general_ledger gl
                WHERE
                    tenants.prospect_id = p.id
                AND
                    (gl.gl_accountID = '4' OR gl.gl_accountID = '22')
                AND
                    gl.tenant_id = tenants.tenant_id
                AND
                gl.posting_date <= '$last_date_previous_month'
                GROUP BY tenants.tenant_id, gl.ref_no) AS previous
            GROUP BY previous.tenant_id
            ORDER BY previous.trade_name ASC
        ");

        return $query->result_array();
    }


    public function get_amountPaid($previous, $current)
    {
        $previous_last_date = date('Y-m-t', strtotime($previous));

        $current_last_date = date('Y-m-t', strtotime($current));


        $query = $this->db->query(
            "SELECT
                tenants.tenant_id,
                IFNULL(SUM(ABS(gl.credit)), 0) AS amount_paid
            FROM
                (SELECT t.tenant_id, p.trade_name FROM tenants t, prospect p WHERE t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'DELETED' AND t.prospect_id =  p.id GROUP BY t.tenant_id) AS tenants,
                general_ledger gl
            WHERE
                gl.tenant_id = tenants.tenant_id
            AND
                (gl.gl_accountID = '22' OR gl.gl_accountID = '4' OR gl.gl_accountID = '29')
            AND
                gl.document_type = 'Payment'
            AND
                gl.posting_date BETWEEN '$previous_last_date' AND '$current_last_date'
            GROUP by gl.tenant_id

        ");

        return $query->result_array();

    }




    public function generate_ar_ar_summary($rental_type, $current_month)
    {

        $param = $current_month . ' -1 months';
        $prev_month = date("F Y", strtotime($param));

        if ($rental_type == 'Fixed Tenants')
        {
            $query = $this->db->query(
                "SELECT
                        (SELECT soa_no FROM soa_file where tenant_id = t.tenant_id order by soa_no DESC LIMIT 1) soa_no,
                        p.trade_name,
                        IFNULL((SELECT amount_payable FROM soa_file where tenant_id = t.tenant_id order by soa_no DESC LIMIT 1,1), 0) as previous_amount,
                        IFNULL((SELECT SUM(IFNULL(amount_paid, 0)) FROM payment where tenant_id = t.tenant_id AND soa_no = (SELECT soa_no FROM soa_file where tenant_id = t.tenant_id order by soa_no DESC LIMIT 1,1)),0) as payment,
                        (SELECT amount_payable FROM soa_file where tenant_id = t.tenant_id order by soa_no DESC LIMIT 1) as current
                    FROM
                        tenants t,
                        prospect p
                    where
                        t.prospect_id = p.id
                    AND
                        t.status = 'Active'
                    AND
                        t.rental_type = 'Fixed'
            ");
            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                        (SELECT soa_no FROM soa_file where tenant_id = t.tenant_id order by soa_no DESC LIMIT 1) soa_no,
                        p.trade_name,
                        IFNULL((SELECT amount_payable FROM soa_file where tenant_id = t.tenant_id order by soa_no DESC LIMIT 1,1), 0) as previous_amount,
                        IFNULL((SELECT SUM(IFNULL(amount_paid, 0)) FROM payment where tenant_id = t.tenant_id AND soa_no = (SELECT soa_no FROM soa_file where tenant_id = t.tenant_id order by soa_no DESC LIMIT 1,1)),0) as payment,
                        (SELECT amount_payable FROM soa_file where tenant_id = t.tenant_id order by soa_no DESC LIMIT 1) as current
                    FROM
                        tenants t,
                        prospect p
                    where
                        t.prospect_id = p.id
                    AND
                        t.status = 'Active'
                    AND
                        (t.rental_type != 'Fixed' && t.rental_type != '' && t.rental_type != 'WOF')
            ");
            return $query->result_array();
        }
    }

    /*=================== NAV EXPORTATION ===================*/

        public function generate_ExportNo($useNext = TRUE){
            $sequence = getSequenceNo(
                [   
                    //'table'         => 'sequence_uft',
                    'code'          => "EXP",
                    'number'        => '1',
                    'lpad'          => '7',
                    'pad_string'    => '0',
                    'description'   => "Nav Exportation Sequence"
                ],
                [
                    'table' =>  'exportation_log',
                    'column' => 'batch_code'
                ],
                $useNext
            );

            return $sequence;
        }

        public function updateEntryAsExported($doc_no, $batch_code){
            $this->db->update('subsidiary_ledger', 
                ['export_batch_code'=>$batch_code], 
                "doc_no = '$doc_no'   AND (export_batch_code IS NULL OR export_batch_code = '')"
            );
        }

        public function logExportForNav($batch_code, $filter, $data, $type, $file_name){

            $log_data = [
                'batch_code' => $batch_code,
                'type'       => $type,
                'file_name'  => $file_name,
                'data'       => $data,
                'filter'     => $filter,
                'store'      => $this->session->userdata('store_code'),
                'exported_by'=> $this->session->userdata('id')
            ];

            $this->db->insert('exportation_log', $log_data);
        }

        public function get_nav_export_data(){

            $store = $this->session->userdata('store_code');

            $data = $this->db->select([
                    'l.id',
                    'l.batch_code', 
                    'l.type', 
                    'l.filter', 
                    'l.store', 
                    'l.exported_at', 
                    "CONCAT(u.first_name, ' ', u.last_name) as full_name"])
                ->from('exportation_log l')
                ->join('leasing_users u', 'u.id = l.exported_by')
                ->where("store LIKE '%$store%'")
                ->order_by('id DESC')
                ->get()
                ->result_array();

            return $data;
        }

        public function getExportForNavFile($id){
     
            $res = $this->db->select('*')->from('exportation_log')->where("id = '$id'")->limit(1)->get()->row();

            if(empty($res)){
                return false;
            }

            return $res;
        }
    
        public function generate_RRreports($month)
        {

            $store = $this->session->userdata('store_code');

            $invoices = $this->db->query("
                SELECT 
                    doc_no, 
                    ref_no
                FROM
                    subsidiary_ledger
                WHERE 
                    DATE_FORMAT(posting_date, '%M %Y') = '$month'
                AND 
                    gl_accountID = '4'
                AND
                    (tenant_id <> 'DELETED' AND ref_no <> 'DELETED' AND doc_no <> 'DELETED')
                AND 
                    tenant_id <> 'ICM-LT000064'
                AND 
                    tenant_id LIKE '%$store%'
                AND
                   (document_type = 'Invoice' OR document_type = 'Invoice Adjustment')
                AND 
                    (export_batch_code IS NULL OR export_batch_code = '')
                GROUP BY 
                    doc_no
            ")->result();


            
            $doc_nos = [];

            foreach ($invoices as $key => $invoice) {
                $doc_nos[] = $invoice->doc_no;
            }

            $docs = $doc_nos;

            //dd($doc_nos);

            $docs = implode("','", $doc_nos);

            $data = $this->db->query("
                SELECT 
                    tbl.gl_code,
                    tbl.gl_account,
                    SUM(IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 )) as debit,
                    SUM(IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0)) as credit,
                    SUM(IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 ) + IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0)) as amount
                 FROM 
                    (
                        SELECT
                            g.id,
                            TRIM(g.tenant_id) tenant_id,
                            TRIM(g.posting_date) posting_date,
                            TRIM(g.due_date) due_date,
                            TRIM(g.doc_no) doc_no,
                            TRIM(g.ref_no) ref_no,
                            TRIM(g.document_type) document_type,
                            TRIM(g.gl_accountID) gl_accountID,
                            TRIM(p.trade_name) trade_name,
                            TRIM(a.gl_account) gl_account,
                            TRIM(a.gl_code) gl_code,
                            SUM(IFNULL(g.debit, 0)) debit,
                            SUM(IFNULL(g.credit, 0)) credit
                        FROM 
                            subsidiary_ledger as g
                        LEFT JOIN 
                            (SELECT distinct(tnt.prospect_id) as prospect_id, tnt.tenant_id, tnt.store_code  FROM tenants tnt) t 
                            ON t.tenant_id = g.tenant_id
                        LEFT JOIN 
                            prospect p on p.id = t.prospect_id
                        LEFT JOIN 
                            gl_accounts a ON a.id = g.gl_accountID
                        WHERE
                            g.doc_no IN ('$docs')
                        AND
                            (g.gl_accountID = '6' OR g.gl_accountID = '4' OR g.gl_accountID = '11' OR (g.gl_accountID = '5' AND (g.tag IS NULL OR g.tag = '')))
                        AND 
                            (g.document_type = 'Invoice' OR g.document_type = 'Invoice Adjustment')
                        AND
                            (g.tenant_id <> 'DELETED' AND g.ref_no <> 'DELETED' AND g.doc_no <> 'DELETED')
                        AND 
                            g.tenant_id != 'ICM-LT000064'
                        AND 
                            t.store_code = '$store'
                        GROUP BY
                            g.doc_no, g.ref_no, g.gl_accountID, g.tenant_id
                        ORDER BY 
                            g.document_type, g.doc_no
                    ) 
                AS 
                    tbl
                LEFT JOIN 
                    (SELECT 
                        memo.ref_no,
                        memo.gl_accountID,
                        memo.tenant_id,
                        SUM(IFNULL(memo.debit, 0) + IFNULL(memo.credit, 0)) as amt
                    FROM 
                        subsidiary_ledger as memo
                    WHERE 
                        (memo.document_type = 'Credit Memo' OR memo.document_type = 'Debit Memo')
                    AND
                        memo.tenant_id != 'DELETED'
                    GROUP BY
                        memo.ref_no, memo.gl_accountID, memo.tenant_id
                    ) AS m
                ON 
                    (tbl.ref_no = m.ref_no AND tbl.gl_accountID = m.gl_accountID AND tbl.document_type = 'Invoice')
                GROUP BY
                    tbl.gl_accountID
                ORDER BY  
                    tbl.debit DESC,  tbl.credit DESC, tbl.trade_name, tbl.gl_account, tbl.doc_no,  tbl.id, tbl.document_type
            ")->result_array();

            echo $this->db->last_query();
            // return compact('data', 'doc_nos');
        }

        public function generate_ARreports($month)
        {

            $store = $this->session->userdata('store_code');

            $invoices = $this->db->query("
                SELECT
                    sl.doc_no
                FROM
                    subsidiary_ledger sl
                WHERE
                    (sl.document_type = 'Invoice' OR sl.document_type = 'Invoice Adjustment')
                AND
                    (sl.gl_accountID = '12' OR sl.gl_accountID = '13' OR sl.gl_accountID = '14' OR sl.gl_accountID = '15' OR sl.gl_accountID = '16' OR sl.gl_accountID = '17' OR sl.gl_accountID = '18' OR sl.gl_accountID = '20' OR sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR sl.gl_accountID = '30' OR ( sl.gl_accountID = '5' AND sl.tag = 'Expanded'))
                AND
                    DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                AND 
                    (sl.export_batch_code IS NULL OR sl.export_batch_code = '')
                AND
                    (sl.tenant_id <> 'DELETED' AND sl.ref_no <> 'DELETED' AND sl.doc_no <> 'DELETED')
                AND
                    sl.tenant_id IN (SELECT t.tenant_id FROM tenants t, prospect p where  t.store_code = '$store' AND t.tenant_id != 'ICM-LT000064' AND t.prospect_id = p.id group by tenant_id)
                GROUP BY
                    sl.doc_no, sl.tenant_id
            ")->result();

            $doc_nos = [];

            foreach ($invoices as $key => $invoice) {
                $doc_nos[] = $invoice->doc_no;
            }

            $docs = $doc_nos;


            $docs = implode("','", $doc_nos);


            $data = $this->db->query(
                    "SELECT
                        gl.gl_code,
                        gl.gl_account,
                        SUM(amount) as amount
                    FROM
                        (SELECT
                            sl.posting_date,
                            ga.gl_code,
                            ga.gl_account,
                                (CASE
                                    WHEN sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR (sl.gl_accountID = '5' AND sl.tag = 'Expanded') THEN
                                        SUM(sl.debit) - (SELECT IFNULL(SUM(ABS(sl1.credit)), 0) FROM subsidiary_ledger sl1 WHERE sl.ref_no = sl1.ref_no AND sl1.gl_accountID = sl.gl_accountID AND sl1.document_type = 'Credit Memo' AND sl1.tenant_id != 'DELETED')
                                    ELSE
                                        SUM(sl.credit) + (SELECT IFNULL(SUM(sl1.debit), 0) FROM subsidiary_ledger sl1 WHERE sl.ref_no = sl1.ref_no AND sl1.gl_accountID = sl.gl_accountID AND sl1.document_type = 'Credit Memo' AND sl1.tenant_id != 'DELETED')
                                END) AS amount
                        FROM
                            subsidiary_ledger sl,
                            gl_accounts ga
                        WHERE
                            sl.gl_accountID = ga.id
                        AND
                            (sl.gl_accountID = '12' OR sl.gl_accountID = '13' OR sl.gl_accountID = '14' OR sl.gl_accountID = '15' OR sl.gl_accountID = '16' OR sl.gl_accountID = '17' OR sl.gl_accountID = '18' OR sl.gl_accountID = '20' OR sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR sl.gl_accountID = '30' OR ( sl.gl_accountID = '5' AND sl.tag = 'Expanded'))
                        AND
                            (sl.document_type = 'Invoice' OR sl.document_type = 'Invoice Adjustment')
                        AND
                            DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                        AND
                            (sl.tenant_id <> 'DELETED' AND sl.ref_no <> 'DELETED' AND sl.doc_no <> 'DELETED')
                        AND
                            sl.doc_no IN ('$docs')
                        AND 
                            (sl.export_batch_code IS NULL OR sl.export_batch_code = '')
                        AND
                            sl.tenant_id IN (SELECT t.tenant_id FROM tenants t, prospect p where  t.store_code = '$store' AND t.tenant_id != 'ICM-LT000064' AND t.prospect_id = p.id group by tenant_id)
                        GROUP BY
                            sl.gl_accountID, sl.ref_no
                        ) gl
                    GROUP BY gl.gl_account
                    ORDER BY gl.amount DESC
                ")->result_array();

            return compact('data', 'doc_nos');
        }

        public function generate_paymentCollectionByDocno($doc_nos)
        {
            $store =  $this->session->userdata('store_code');
            $query = $this->db->query(
                    "
                    SELECT 
                        tbl.*,
                        SUM(tbl.debit) debit,
                        SUM(tbl.credit) credit,
                        sl2.partnerID
                    FROM 
                        (SELECT
                            sl.document_type,
                            sl.tenant_id,
                            sl.bank_code,
                            sl.bank_name,
                            sl.posting_date,
                            sl.ref_no,
                            ga.gl_code,
                            ga.gl_account,
                            sl.gl_accountID,
                            sl.doc_no,
                            SUM(IFNULL(sl.debit, 0)) debit,
                            SUM(IFNULL(sl.credit, 0)) credit,
                            SUM(IFNULL(sl.debit, 0) + IFNULL(sl.credit, 0)) amount
                        FROM
                            subsidiary_ledger sl
                        LEFT JOIN 
                            gl_accounts ga
                        ON 
                            ga.id = sl.gl_accountID
                        WHERE
                            (sl.gl_accountID = '4' 
                            OR sl.gl_accountID = '22' 
                            OR sl.gl_accountID = '29' 
                            OR sl.gl_accountID = '7' 
                            OR sl.gl_accountID = '8' 
                            OR sl.gl_accountID = '9' 
                            OR sl.gl_accountID = '3' 
                            OR sl.gl_accountID = '26'  
                            OR sl.gl_accountID = '23'
                            OR sl.gl_accountID = '24'
                            OR sl.gl_accountID = '25'
                            OR sl.gl_accountID = '31'
                            OR sl.gl_accountID = '32')
                        AND
                            (sl.document_type = 'Payment' OR sl.document_type = 'Payment Adjustment')
                        AND 
                            sl.doc_no IN ($doc_nos)
                        AND
                            (sl.export_batch_code IS NULL OR sl.export_batch_code = '')
                        AND
                            sl.tenant_id IN (SELECT t.tenant_id FROM tenants t, prospect p where  t.store_code = '$store' AND t.tenant_id != 'ICM-LT000064' AND t.prospect_id = p.id group by tenant_id)
                        GROUP BY
                            sl.doc_no,
                            sl.ref_no,
                            sl.gl_accountID, 
                            sl.posting_date,
                            sl.tenant_id
                            /*,sl.document_type*/
                        HAVING
                            amount <> 0
                        ORDER BY 
                            sl.posting_date, 
                            sl.doc_no ASC, 
                            sl.ref_no ASC,  
                            sl.id ASC , 
                            sl.bank_code ASC)
                    AS tbl

                    LEFT JOIN
                        (SELECT 
                            s.*, 
                            s.gl_accountID as partnerID 
                        FROM 
                            subsidiary_ledger s 
                        WHERE 
                            (s.debit IS NULL OR s.debit = 0 )
                        AND 
                            (s.credit IS NOT NULL OR s.credit <> 0) 
                        AND 
                            (s.document_type = 'Payment' OR s.document_type = 'Payment Adjustment')
                        GROUP BY 
                            s.doc_no, s.ref_no, s.credit)
                    AS sl2
                    ON
                        tbl.doc_no = sl2.doc_no   
                        AND tbl.ref_no = sl2.ref_no 
                        AND tbl.debit <> 0 
                        AND tbl.debit = ABS(sl2.credit) 
                        AND tbl.tenant_id = sl2.tenant_id
                        AND tbl.document_type = sl2.document_type
                    GROUP BY
                        tbl.doc_no, 
                        tbl.gl_accountID, 
                        tbl.posting_date,
                        tbl.document_type,
                        sl2.partnerID

                    ORDER BY 
                        tbl.posting_date, 
                        tbl.doc_no ASC,
                        tbl.ref_no ASC,
                        tbl.document_type ASC,
                        tbl.gl_accountID ASC
                    "
            );

            return $query->result();
        }

        public function removeExportationTag($batch_code){
            $this->db->trans_start();

            $this->db->where('export_batch_code', $batch_code);
            $this->db->update('subsidiary_ledger', ['export_batch_code'=> NULL]);

            $this->db->delete('exportation_log', ['batch_code' => $batch_code]);
            

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }

            return $this->db->trans_status();
        
        }
    /*================= END OF NAV EXPORTATION=====================*/


    public function generate_usingARNTIPayment($month) {
        if ($this->_user_group == '' || $this->_user_group == '0' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                    "SELECT
                        sl.bank_code,
                        sl.bank_name,
                        sl.posting_date,
                        ga.gl_code,
                        sl.doc_no,
                        ga.gl_account,
                        SUM(IFNULL(credit ,0)) AS amount
                    FROM
                        subsidiary_ledger sl, gl_accounts ga
                    WHERE
                        ga.id = sl.gl_accountID
                    AND
                        (sl.gl_accountID = '4' OR sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR sl.gl_accountID = '7' OR sl.gl_accountID = '8' OR sl.gl_accountID = '9')
                    AND
                        sl.document_type = 'Payment'
                    AND
                        sl.status = 'ARNTI'
                    AND
                        DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                    GROUP BY
                        gl_code, sl.doc_no
                    ORDER BY posting_date ASC
                ");
            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                    "SELECT
                        sl.bank_code,
                        sl.bank_name,
                        ga.gl_code,
                        sl.posting_date,
                        ga.gl_account,
                        sl.doc_no,
                        SUM(IFNULL(credit ,0)) AS amount
                    FROM
                        subsidiary_ledger sl, gl_accounts ga
                    WHERE
                        ga.id = sl.gl_accountID
                    AND
                        (sl.gl_accountID = '4' OR sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR sl.gl_accountID = '7' OR sl.gl_accountID = '8' OR sl.gl_accountID = '9')
                    AND
                        sl.document_type = 'Payment'
                    AND
                        sl.status = 'ARNTI'
                    AND
                        DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                    AND
                        sl.tenant_id IN (SELECT t.tenant_id FROM tenants t, prospect p where  t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'ICM-LT000064' AND t.prospect_id = p.id group by tenant_id)
                    GROUP BY
                        gl_code, sl.doc_no
                    ORDER BY posting_date ASC
                ");
            return $query->result_array();
        }
    }

    
    public function generate_closedPDC($month) {
        $query = $this->db->query(
                    "SELECT
                        sl.bank_code,
                        sl.bank_name,
                        ga.gl_code,
                        sl.posting_date,
                        ga.gl_account,
                        sl.doc_no,
                        SUM(IFNULL(credit ,0)) AS amount
                    FROM
                        subsidiary_ledger sl, gl_accounts ga
                    WHERE
                        ga.id = sl.gl_accountID
                    AND
                        (sl.gl_accountID = '26')
                    AND
                        sl.document_type = 'Payment'
                    AND
                        sl.status IS NULL
                    AND
                        (bank_code != '' AND bank_code IS NOT NULL AND bank_code != '? undefined:undefined ?')
                    AND
                        DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                    AND
                        sl.tenant_id IN (SELECT t.tenant_id FROM tenants t, prospect p where  t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'ICM-LT000064' AND t.prospect_id = p.id group by tenant_id)
                    GROUP BY
                        gl_code, sl.doc_no
                    HAVING SUM(IFNULL(credit ,0)) < 0
                    ORDER BY posting_date, bank_code ASC
                ");
            return $query->result_array();
    }

    public function generate_PDCCollection($month){
        if ($this->_user_group == '' || $this->_user_group == '0' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                    "SELECT
                        sl.bank_code,
                        sl.bank_name,
                        sl.posting_date,
                        ga.gl_code,
                        sl.doc_no,
                        ga.gl_account,
                        SUM(IFNULL(credit ,0)) AS amount
                    FROM
                        subsidiary_ledger sl, gl_accounts ga
                    WHERE
                        ga.id = sl.gl_accountID
                    AND
                        (sl.gl_accountID = '4' OR sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR sl.gl_accountID = '7' OR sl.gl_accountID = '8' OR sl.gl_accountID = '9')
                    AND
                        sl.document_type = 'Payment'
                    AND
                        sl.doc_no NOT IN (' " . implode($exempted, "','") . "')
                    AND
                        sl.status = 'PDC'
                    AND
                        (bank_code != '' AND bank_code IS NOT NULL AND bank_code != '? undefined:undefined ?')
                    AND
                        DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                    GROUP BY
                        gl_code, sl.doc_no
                    ORDER BY posting_date, bank_code ASC
                ");
            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                    "SELECT
                        sl.bank_code,
                        sl.bank_name,
                        ga.gl_code,
                        sl.posting_date,
                        ga.gl_account,
                        sl.doc_no,
                        SUM(IFNULL(credit ,0)) AS amount
                    FROM
                        subsidiary_ledger sl, gl_accounts ga
                    WHERE
                        ga.id = sl.gl_accountID
                    AND
                        (sl.gl_accountID = '4' OR sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR sl.gl_accountID = '7' OR sl.gl_accountID = '8' OR sl.gl_accountID = '9')
                    AND
                        sl.document_type = 'Payment'
                    AND
                         sl.status = 'PDC'
                    AND
                        (bank_code != '' AND bank_code IS NOT NULL AND bank_code != '? undefined:undefined ?')
                    AND
                        DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                    AND
                        sl.tenant_id IN (SELECT t.tenant_id FROM tenants t, prospect p where  t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'ICM-LT000064' AND t.prospect_id = p.id group by tenant_id)
                    GROUP BY
                        gl_code, sl.doc_no
                    ORDER BY posting_date, bank_code ASC
                ");
            return $query->result_array();
        }
    }
    public function generate_paymentCollection($month)
    {
        if ($this->_user_group == '' || $this->_user_group == '0' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                    "SELECT
                        sl.bank_code,
                        sl.bank_name,
                        sl.posting_date,
                        ga.gl_code,
                        ga.gl_account,
                        sl.doc_no,
                        SUM(IFNULL(credit ,0)) AS amount
                    FROM
                        subsidiary_ledger sl, gl_accounts ga
                    WHERE
                        ga.id = sl.gl_accountID
                    AND
                        (sl.gl_accountID = '4' OR sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR sl.gl_accountID = '7' OR sl.gl_accountID = '8' OR sl.gl_accountID = '9')
                    AND
                        sl.document_type = 'Payment'
                    AND
                        (sl.status IS NULL OR sl.status = '')
                    AND
                        (bank_code != '' AND bank_code IS NOT NULL AND bank_code != '? undefined:undefined ?')
                    AND
                        DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                    GROUP BY
                        gl_code, sl.doc_no
                    ORDER BY posting_date, bank_code ASC
                ");
            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                    "SELECT
                        sl.bank_code,
                        sl.bank_name,
                        ga.gl_code,
                        sl.posting_date,
                        ga.gl_account,
                        sl.doc_no,
                        SUM(IFNULL(credit ,0)) AS amount
                    FROM
                        general_ledger sl, gl_accounts ga
                    WHERE
                        ga.id = sl.gl_accountID
                    AND
                        (sl.gl_accountID = '4' OR sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR sl.gl_accountID = '7' OR sl.gl_accountID = '8' OR sl.gl_accountID = '9')
                    AND
                        sl.document_type = 'Payment'
                    AND
                        (sl.status != 'PDC' OR (sl.status IS NULL OR sl.status = '' OR sl.status = 'AR Clearing' OR sl.status = 'RR Clearing' OR sl.status = 'URI Clearing' OR sl.status = 'Preop Clearing'))
                    AND
                        (bank_code != '' AND bank_code IS NOT NULL AND bank_code != '? undefined:undefined ?')
                    AND
                        DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                    AND
                        sl.tenant_id IN (SELECT t.tenant_id FROM tenants t, prospect p where  t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'ICM-LT000064' AND t.prospect_id = p.id group by tenant_id)
                    GROUP BY
                        gl_code, sl.doc_no
                    ORDER BY posting_date, bank_code ASC
                ");
            return $query->result_array();
        }
    }

    


    public function generate_paymentPDCClosing() {
        if ($this->_user_group == '' || $this->_user_group == '0' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                    "SELECT
                        sl.bank_code,
                        sl.bank_name,
                        sl.posting_date,
                        ga.gl_code,
                        sl.doc_no,
                        ga.gl_account,
                        SUM(IFNULL(credit ,0)) AS amount
                    FROM
                        subsidiary_ledger sl, gl_accounts ga
                    WHERE
                        ga.id = sl.gl_accountID
                    AND
                        (sl.gl_accountID = '4' OR sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR sl.gl_accountID = '7' OR sl.gl_accountID = '8' OR sl.gl_accountID = '9')
                    AND
                        sl.document_type = 'Payment'
                    AND
                        (sl.status IS NULL OR sl.status = '')
                    AND
                        (bank_code != '' AND bank_code IS NOT NULL AND bank_code != '? undefined:undefined ?')
                    AND
                        DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                    GROUP BY
                        gl_code, sl.doc_no
                    ORDER BY posting_date, bank_code ASC
                ");
            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                    "SELECT
                        sl.bank_code,
                        sl.bank_name,
                        ga.gl_code,
                        sl.doc_no,
                        sl.posting_date,
                        ga.gl_account,
                        SUM(IFNULL(credit ,0)) AS amount
                    FROM
                        general_ledger sl, gl_accounts ga
                    WHERE
                        ga.id = sl.gl_accountID
                    AND
                        (sl.gl_accountID = '4' OR sl.gl_accountID = '22' OR sl.gl_accountID = '29' OR sl.gl_accountID = '7' OR sl.gl_accountID = '8' OR sl.gl_accountID = '9')
                    AND
                        sl.document_type = 'Payment'
                    AND
                        (sl.status != 'PDC' OR (sl.status IS NULL OR sl.status = '' OR sl.status = 'AR Clearing' OR sl.status = 'RR Clearing' OR sl.status = 'URI Clearing' OR sl.status = 'Preop Clearing'))
                    AND
                        (bank_code != '' AND bank_code IS NOT NULL AND bank_code != '? undefined:undefined ?')
                    AND
                        DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                    AND
                        sl.tenant_id IN (SELECT t.tenant_id FROM tenants t, prospect p where  t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'ICM-LT000064' AND t.prospect_id = p.id group by tenant_id)
                    GROUP BY
                        gl_code, sl.doc_no
                    ORDER BY posting_date, bank_code ASC
                ");
            return $query->result_array();
        }
    }



    public function generate_JVpaymentCollection($month)
    {
        if ($this->_user_group == '' || $this->_user_group == '0' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                    "SELECT
                        gl.gl_code,
                        gl.JV_desc,
                        gl.posting_date,
                        gl.doc_no,
                        SUM(IFNULL(gl.RR_amount,0)) AS RR_amount,
                        SUM(IFNULL(gl.AR_amount,0)) AS AR_amount
                    FROM
                        (SELECT
                            sl.doc_no,
                            ga.gl_code,
                            sl.posting_date,
                            ga.gl_account AS JV_desc,
                            (SELECT SUM(IFNULL(sl1.credit, 0)) FROM subsidiary_ledger sl1 WHERE sl1.doc_no = sl.doc_no AND sl1.gl_accountID = '4'    AND sl1.document_type = 'Payment' GROUP BY sl.gl_accountID) as RR_amount,
                            (SELECT SUM(IFNULL(sl1.credit, 0)) FROM subsidiary_ledger sl1 WHERE sl1.doc_no = sl.doc_no AND (sl1.gl_accountID = '22' OR sl1.gl_accountID = '29') AND sl1.document_type = 'Payment' GROUP BY sl.gl_accountID) as AR_amount
                        FROM
                            subsidiary_ledger sl,
                            gl_accounts ga
                        WHERE
                            (sl.gl_accountID = '23' OR sl.gl_accountID = '24' OR sl.gl_accountID = '25')
                        AND
                            sl.gl_accountID = ga.id
                        AND
                            DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                        GROUP BY  sl.doc_no) AS gl
                    GROUP BY gl.JV_desc, gl.doc_no
                    ORDER BY gl.posting_date ASC
                ");
            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                    "SELECT
                        gl.gl_code,
                        gl.JV_desc,
                        gl.posting_date,
                        gl.doc_no,
                        SUM(IFNULL(gl.RR_amount,0)) AS RR_amount,
                        SUM(IFNULL(gl.AR_amount,0)) AS AR_amount
                    FROM
                        (SELECT
                            sl.doc_no,
                            ga.gl_code,
                            sl.posting_date,
                            ga.gl_account AS JV_desc,
                          (SELECT SUM(IFNULL(sl1.credit, 0)) FROM subsidiary_ledger sl1 WHERE sl1.doc_no = sl.doc_no AND sl1.gl_accountID = '4'   AND sl1.document_type = 'Payment' GROUP BY sl.gl_accountID) as RR_amount,
                            (SELECT SUM(IFNULL(sl1.credit, 0)) FROM subsidiary_ledger sl1 WHERE sl1.doc_no = sl.doc_no AND (sl1.gl_accountID = '22' OR sl1.gl_accountID = '29') AND sl1.document_type = 'Payment' GROUP BY sl.gl_accountID) as AR_amount
                        FROM
                            subsidiary_ledger sl,
                            gl_accounts ga
                        WHERE
                            (sl.gl_accountID = '23' OR sl.gl_accountID = '24' OR sl.gl_accountID = '25')
                        AND
                            sl.gl_accountID = ga.id
                        AND
                            DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                        AND
                            sl.tenant_id IN (select t.tenant_id FROM tenants t, prospect p where t.prospect_id = p.id AND t.store_code = '" . $this->session->userdata('store_code') . "' group by tenant_id)
                        GROUP BY  sl.doc_no) AS gl
                    GROUP BY gl.JV_desc, gl.doc_no
                    ORDER BY gl.posting_date ASC
                ");
            return $query->result_array();
        }
    }



    public function generate_UsingPreopCollection($month)
    {
        if ($this->_user_group == '' || $this->_user_group == '0' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                    "SELECT
                        gl.gl_code,
                        gl.preopDesc,
                        gl.doc_no,
                        gl.posting_date,
                        SUM(IFNULL(gl.RR_amount,0)) AS RR_amount,
                        SUM(IFNULL(gl.AR_amount,0)) AS AR_amount
                    FROM
                        (SELECT
                            sl.doc_no,
                            ga.gl_code,
                            sl.posting_date,
                            ga.gl_account AS preopDesc,
                            (SELECT SUM(IFNULL(sl1.credit, 0)) FROM subsidiary_ledger sl1 WHERE sl1.doc_no = sl.doc_no AND sl1.gl_accountID = '4' AND sl1.document_type = 'Payment' GROUP BY sl.gl_accountID) as RR_amount,
                            (SELECT SUM(IFNULL(sl1.credit, 0)) FROM subsidiary_ledger sl1 WHERE sl1.doc_no = sl.doc_no AND sl1.gl_accountID = '22' AND sl1.document_type = 'Payment' GROUP BY sl.gl_accountID) as AR_amount
                        FROM
                            subsidiary_ledger sl,
                            gl_accounts ga
                        WHERE
                            (sl.gl_accountID = '7' OR sl.gl_accountID = '8' OR sl.gl_accountID = '9')
                        AND
                            (sl.bank_code = '' OR sl.bank_code IS NULL)
                        AND
                            sl.gl_accountID = ga.id
                        AND
                            DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                        GROUP BY  sl.doc_no) AS gl
                    GROUP BY gl.preopDesc, gl.doc_no
                    ORDER BY gl.posting_date ASC
                ");
            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                    "SELECT
                        gl.gl_code,
                        gl.preopDesc,
                        gl.posting_date,
                        gl.doc_no,
                        SUM(IFNULL(gl.RR_amount,0)) AS RR_amount,
                        SUM(IFNULL(gl.AR_amount,0)) AS AR_amount
                    FROM
                        (SELECT
                            sl.doc_no,
                            ga.gl_code,
                            sl.posting_date,
                            ga.gl_account AS preopDesc,
                            (SELECT SUM(IFNULL(sl1.credit, 0)) FROM subsidiary_ledger sl1 WHERE sl1.doc_no = sl.doc_no AND sl1.gl_accountID = '4' AND sl1.document_type = 'Payment' GROUP BY sl.gl_accountID) as RR_amount,
                            (SELECT SUM(IFNULL(sl1.credit, 0)) FROM subsidiary_ledger sl1 WHERE sl1.doc_no = sl.doc_no AND sl1.gl_accountID = '22' AND sl1.document_type = 'Payment' GROUP BY sl.gl_accountID) as AR_amount
                        FROM
                            subsidiary_ledger sl,
                            gl_accounts ga
                        WHERE
                            (sl.gl_accountID = '7' OR sl.gl_accountID = '8' OR sl.gl_accountID = '9')
                        AND
                            (sl.bank_code = '' OR sl.bank_code IS NULL)
                        AND
                            sl.gl_accountID = ga.id
                        AND
                            DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                        AND
                            sl.tenant_id IN (select t.tenant_id FROM tenants t, prospect p where t.prospect_id = p.id AND t.store_code = '" . $this->session->userdata('store_code') . "' group by tenant_id)
                        GROUP BY  sl.doc_no) AS gl
                    GROUP BY gl.preopDesc, gl.doc_no
                    ORDER BY gl.posting_date ASC
                ");
            return $query->result_array();
        }
    }


    public function generate_forBankRecon($from_date, $to_date)
    {
        if ($this->_user_group == '' || $this->_user_group == '0' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                    "SELECT
                        `ps`.`payor`,
                        `ps`.`tender_typeDesc`,
                        `ps`.`amount_paid`,
                        `ps`.`receipt_no`,
                        `ps`.`check_no`,
                        `ps`.`check_date`
                    FROM
                        `payment_scheme` `ps`,
                        `prospect` `p`,
                        `subsidiary_ledger` `sl`,
                        (SELECT tenant_id, prospect_id FROM tenants GROUP BY tenant_id) AS tenants
                    WHERE
                        `ps`.`tenant_id` = `tenants`.`tenant_id`
                    AND
                        tenants.prospect_id = p.id
                    AND
                        (`sl`.`posting_date` BETWEEN '$from_date' AND '$to_date')
                    AND
                        `ps`.`receipt_no` = `sl`.`doc_no`
                    AND
                        (ps.tender_typeDesc = 'Bank to Bank' OR ps.tender_typeDesc = 'Check')
                    AND
                        `ps`.`tenant_id` = `sl`.`tenant_id`
                    GROUP BY `ps`.`receipt_no`
                ");

            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                    "SELECT
                        `ps`.`payor`,
                        `ps`.`tender_typeDesc`,
                        `ps`.`amount_paid`,
                        `ps`.`receipt_no`,
                        `ps`.`check_no`,
                        `ps`.`check_date`
                    FROM
                        `payment_scheme` `ps`,
                        `prospect` `p`,
                        `subsidiary_ledger` `sl`,
                        (SELECT tenant_id, prospect_id FROM tenants where store_code = '" . $this->session->userdata('store_code') . "' GROUP BY tenant_id) AS tenants
                    WHERE
                        `ps`.`tenant_id` = `tenants`.`tenant_id`
                    AND
                        tenants.prospect_id = p.id
                    AND
                        (`sl`.`posting_date` BETWEEN '$from_date' AND '$to_date')
                    AND
                        `ps`.`receipt_no` = `sl`.`doc_no`
                    AND
                        (ps.tender_typeDesc = 'Bank to Bank' OR ps.tender_typeDesc = 'Check')
                    AND
                        `ps`.`tenant_id` = `sl`.`tenant_id`
                    GROUP BY `ps`.`receipt_no`
                ");

            return $query->result_array();
        }
    }



    public function recon_checkLogin($username, $password)
    {
        $query = $this->db->query(
            "SELECT
                `u`.`id`,
                `u`.`password`,
                `u`.`user_type`,
                `u`.`username`,
                `u`.`first_name`,
                `u`.`middle_name`,
                `u`.`last_name`,
                `u`.`user_group`,
                `s`.`company_code`,
                `s`.`store_code`
            FROM
                `leasing_users` `u`
            LEFT Join
                `stores` `s`
            ON
                `u`.`user_group` = `s`.`id`
            WHERE
                `u`.`password` = '" . MD5($password) . "'
            AND
                `u`.`username` = '$username'
        ");

        if($query->num_rows() > 0)
        {
            foreach($query->result() as $rows)
            {
                //add all data to session
                $data = array(
                    'id'                    =>  $rows->id,
                    'password'              =>  $rows->password,
                    'user_type'             =>  $rows->user_type,
                    'username'              =>  $rows->username,
                    'first_name'            =>  $rows->first_name,
                    'middle_name'           =>  $rows->middle_name,
                    'last_name'             =>  $rows->last_name,
                    'user_group'            =>  $rows->user_group,
                    'company_code'          =>  $rows->company_code,
                    'store_code'            =>  $rows->store_code,
                    'recon_logged_in'       =>  TRUE
                );
                $this->session->set_userdata($data);
                return true;
            }
        }
        return false;
    }


    public function recon_data($month)
    {

        $query = $this->db->query(
                "SELECT
                    `sl`.`posting_date`,
                    `ps`.`payor`,
                    `ps`.`tender_typeDesc`,
                    `ps`.`amount_paid`,
                    `ps`.`receipt_no`,
                    `ps`.`check_no`,
                    `ps`.`check_date`,
                    CONCAT(`sl`.`bank_name`, ' ', `sl`.`bank_code`) AS `bank`
                FROM
                    `payment_scheme` `ps`,
                    `prospect` `p`,
                    `subsidiary_ledger` `sl`,
                    (SELECT tenant_id, prospect_id FROM tenants  where store_code = '" . $this->session->userdata('store_code') . "'  GROUP BY tenant_id) AS tenants
                WHERE
                    `ps`.`tenant_id` = `tenants`.`tenant_id`
                AND
                    tenants.prospect_id = p.id
                AND
                    DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                AND
                    `ps`.`receipt_no` = `sl`.`doc_no`
                AND
                    `ps`.`tenant_id` = `sl`.`tenant_id`
                GROUP BY `ps`.`receipt_no`
                ORDER BY `sl`.`posting_date` ASC
            ");

        return $query;

    }

    public function generate_receiptsAudit($month)
    {
        $query = $this->db->query(
            "SELECT
                `ps`.`receipt_no`,
                `sl`.`posting_date`,
                `ps`.`billing_period`,
                `ps`.`payor`,
                `ps`.`vds_no`,
                (CASE
                    WHEN `ps`.`tender_typeDesc` = 'Construction Bond' OR `ps`.`tender_typeDesc` = 'Security Deposit' THEN 'Cash'
                    ELSE `ps`.`tender_typeDesc`
                 END) AS tender_type,
                `ps`.`amount_paid`,
                `ps`.`check_no`,
                `ps`.`check_date`
            FROM
                `payment_scheme` `ps`,
                `prospect` `p`,
                `subsidiary_ledger` `sl`,
                (SELECT tenant_id, prospect_id FROM tenants where store_code = '" . $this->session->userdata('store_code') . "' GROUP BY tenant_id) AS tenants
            WHERE
                `ps`.`tenant_id` = `tenants`.`tenant_id`
            AND
                tenants.prospect_id = p.id
            AND
                DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
            AND
                `ps`.`receipt_no` = `sl`.`doc_no`
            AND
                `ps`.`tenant_id` = `sl`.`tenant_id`
            GROUP BY `ps`.`receipt_no`
        ");

        return $query->result_array();
    }


    public function generate_RR_ARLedger($month)
    {
        if ($this->_user_group == '' || $this->_user_group == '0' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                "SELECT
                    sl.ref_no,
                    sl.posting_date,
                    sl.doc_no,
                    sl.document_type,
                    ga.gl_code,
                    ga.gl_account,
                    ABS(sl.debit) AS debit,
                    ABS(sl.credit) AS credit
                FROM
                    subsidiary_ledger sl,
                    gl_accounts ga
                WHERE
                    sl.gl_accountID = ga.id
                AND
                    (sl.tenant_id != '' OR sl.tenant_id != 'DELETED' OR sl.tenant_id IS NOT NULL)
                AND
                    (sl.document_type = 'Invoice' OR sl.document_type = 'Credit Memo')
                AND
                    DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                ORDER BY posting_date ASC
            ");

            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    sl.ref_no,
                    sl.posting_date,
                    sl.doc_no,
                    sl.document_type,
                    ga.gl_code,
                    ga.gl_account,
                    ABS(sl.debit) AS debit,
                    ABS(sl.credit) AS credit
                FROM
                    subsidiary_ledger sl,
                    gl_accounts ga
                WHERE
                    sl.gl_accountID = ga.id
                AND
                    (sl.tenant_id != '' OR sl.tenant_id != 'DELETED' OR sl.tenant_id IS NOT NULL)
                AND
                    (sl.document_type = 'Invoice' OR sl.document_type = 'Credit Memo')
                AND
                    DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                AND
                    sl.tenant_id IN (SELECT t.tenant_id FROM tenants t, prospect p where  t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'ICM-LT000064' AND t.prospect_id = p.id group by tenant_id)
                ORDER BY posting_date ASC
            ");

            return $query->result_array();
        }
    }


    public function generate_paymentLedger($month)
    {
        if ($this->_user_group == '' || $this->_user_group == '0' || $this->_user_group == NULL)
        {
            $query = $this->db->query(
                "SELECT
                    sl.ref_no,
                    sl.posting_date,
                    sl.doc_no,
                    sl.document_type,
                    ga.gl_code,
                    ga.gl_account,
                    ABS(IFNULL(sl.debit, 0)) AS debit,
                    ABS(IFNULL(sl.credit, 0)) AS credit
                FROM
                    subsidiary_ledger sl,
                    gl_accounts ga
                WHERE
                    sl.gl_accountID = ga.id
                AND
                    (sl.tenant_id != '' OR sl.tenant_id != 'DELETED' OR sl.tenant_id IS NOT NULL)
                AND
                    sl.document_type = 'Payment'
                AND
                    DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                ORDER BY posting_date, tenant_id  ASC");

            return $query->result_array();
        }
        else
        {
            $query = $this->db->query(
                "SELECT
                    sl.ref_no,
                    sl.posting_date,
                    sl.doc_no,
                    sl.document_type,
                    ga.gl_code,
                    ga.gl_account,
                    ABS(IFNULL(sl.debit, 0)) AS debit,
                    ABS(IFNULL(sl.credit, 0)) AS credit
                FROM
                    subsidiary_ledger sl,
                    gl_accounts ga
                WHERE
                    sl.gl_accountID = ga.id
                AND
                    (sl.tenant_id != '' OR sl.tenant_id != 'DELETED' OR sl.tenant_id IS NOT NULL)
                AND
                    sl.document_type = 'Payment'
                AND
                    sl.doc_no NOT LIKE 'UFT%'
                AND
                    DATE_FORMAT(sl.posting_date, '%M %Y') = '$month'
                AND
                    sl.tenant_id IN (SELECT t.tenant_id FROM tenants t, prospect p where  t.store_code = '" . $this->session->userdata('store_code') . "' AND t.tenant_id != 'ICM-LT000064' AND t.prospect_id = p.id group by tenant_id)
                ORDER BY posting_date, tenant_id  ASC");

            return $query->result_array();
        }
    }


    public function getTenantBalances($tenant_id, $date_created){
        $query = $this->db->query("
            SELECT  
                (CASE 
                    WHEN inv.tag = 'Retro Rent' THEN 'Retro'
                    ELSE inv.document_type
                END) AS document_type,
                inv.doc_no,
                inv.posting_date,
                inv.due_date,
                inv.ref_no,
                (CASE
                    WHEN inv.gl_accountID = '4' AND inv.tag = 'Retro Rent'  THEN CONCAT('Basic-', p.trade_name, '-Retro Rent')
                    WHEN inv.gl_accountID = '4' THEN CONCAT('Basic-', p.trade_name)
                    WHEN inv.gl_accountID IN (22,29) AND inv.tag = 'Penalty' THEN CONCAT('Other-', p.trade_name,'-Penalty')
                    ELSE CONCAT('Other-', p.trade_name) 
                END) AS description,
                inv.tag,
                inv.gl_accountID,
                inv.tenant_id,                
                SUM(IFNULL(inv.amount,0)) as invoice_amount,
                SUM(IFNULL(memo.amount, 0)) as adj_amount,
                SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                SUM(IFNULL(pmt.amount, 0)) as credit,
                SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance,
                ABS(SUM(IFNULL(np.amount, 0))) AS nopenalty_amount
            FROM
                (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                    FROM subsidiary_ledger
                    WHERE gl_accountID IN (22,29,4)
                    AND document_type = 'Invoice'
                    AND tenant_id = '$tenant_id'
                    AND posting_date <= '$date_created'
                    GROUP BY ref_no, gl_accountID, tenant_id) as inv
            LEFT JOIN
                (SELECT tenant_id,
                        doc_no,
                        ref_no,
                        gl_accountID,
                        SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                FROM subsidiary_ledger
                WHERE gl_accountID IN (22,29,4)
                AND document_type IN ('Credit Memo', 'Debit Memo', 'Invoice Adjustment')
                AND tenant_id <> 'DELETED'
                GROUP BY ref_no, gl_accountID, tenant_id
                ) AS memo
                ON
                    inv.ref_no = memo.ref_no AND 
                    inv.gl_accountID = memo.gl_accountID AND 
                    inv.tenant_id = memo.tenant_id
            LEFT JOIN
                (SELECT p.tenant_id,
                        p.doc_no,
                        p.ref_no,
                        p.gl_accountID,
                        SUM(IFNULL(p.debit,0) + IFNULL(p.credit,0)) as amount
                    FROM subsidiary_ledger p
                    WHERE p.gl_accountID IN (22,29,4)
                    AND (p.document_type = 'Payment' OR  p.document_type = 'Payment Adjustment')
                    AND p.tenant_id <> 'DELETED'
                    GROUP BY p.ref_no, p.gl_accountID, p.tenant_id) AS pmt
                    ON  inv.ref_no = pmt.ref_no AND 
                        inv.gl_accountID = pmt.gl_accountID  AND 
                        inv.tenant_id = pmt.tenant_id
            LEFT JOIN
                (SELECT tenant_id,
                        doc_no,
                        ref_no,
                        SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                FROM subsidiary_ledger
                WHERE gl_accountID NOT IN (22,29,4)
                AND document_type = 'Invoice'
                AND with_penalty = 'No'
                AND tenant_id <> 'DELETED'
                GROUP BY ref_no, doc_no, tenant_id
                ) AS np
                ON  inv.ref_no = np.ref_no AND 
                    inv.doc_no = np.doc_no  AND 
                    inv.tenant_id = np.tenant_id AND
                    inv.gl_accountID <> 4
            LEFT JOIN
                (SELECT 
                    distinct(tnt.prospect_id) as prospect_id, 
                    tnt.tenant_id, tnt.store_code  
                FROM 
                    tenants tnt) 
                AS t 
                ON t.tenant_id = inv.tenant_id
            LEFT JOIN 
                prospect p 
                ON p.id = t.prospect_id
            GROUP BY 
                inv.ref_no, inv.gl_accountID, inv.tenant_id
            HAVING
                (balance > .10 OR balance >= 1)
            ORDER BY 
                document_type ASC, inv.gl_accountID ASC, inv.posting_date ASC
        ");
        

        $result = $query->result_array();
        
        return $result;
    }

    public function getTenantPreopBalances($tenant_id, $date_created)
    {
        $query = $this->db->query("
            SELECT 
                id,
                'Preop-Charges' as document_type,
                doc_no,
                description,
                posting_date,
                due_date,
                IFNULL(org_amount, amount)  as debit,
                (CASE 
                    WHEN org_amount IS NULL THEN 0
                    ELSE (org_amount - amount) * -1 
                END) AS credit,
                amount as balance
            FROM 
                `tmp_preoperationcharges` 
            WHERE 
                `tenant_id` = '$tenant_id' 
            AND 
                (`tag` = '' || `tag` = 'Posted')
            AND 
                amount >= 1
            AND
                posting_date <= '$date_created'
            ORDER BY
                posting_date ASC, id ASC
        ");

        return $query->result_array();
    }

    public function getInvoicingData($tenant_id, $doc_no){
        $invoice =$this->db->query("
            SELECT 
                * 
            FROM 
                invoicing 
            WHERE 
                tenant_id = '$tenant_id' 
            AND 
                doc_no = '$doc_no'
        ")->result_object();

        return $invoice;
    }

    public function getTenantUnearnedRentIncome($tenant_id, $posting_date){
        $query = $this->db->query("
            SELECT 
                doc_no,
                ref_no,
                posting_date,
                gl_accountID,
                SUM(ABS(IFNULL(debit,0))) as debit,
                SUM(ABS(IFNULL(credit,0))) as credit,
                SUM(ABS(IFNULL(credit,0))) - SUM(ABS(IFNULL(debit,0))) as balance
            FROM
                subsidiary_ledger
            WHERE
                gl_accountID = 7
            AND
                tenant_id = '$tenant_id'
            GROUP BY 
                ref_no, tenant_id, gl_accountID
            having 
                balance >= 1 AND posting_date <= '$posting_date'
        ")->result_object();

        return $query;
    }

    public function get_soaPreviousBilling($tenant_id, $posting_date)
    {
        $query = $this->db->query(
            "SELECT
                s.tenant_id,
                IFNULL(s.amount_payable, 0) as amount_payable,
                IFNULL(p.amount_paid, 0) as amount_paid
            FROM
                soa_file s
            LEFT JOIN
                (SELECT 
                    tenant_id,
                    soa_no,
                    SUM(IFNULL(amount_paid, 0)) as amount_paid
                FROM 
                    payment  
                WHERE 
                    tenant_id = '$tenant_id'
                GROUP BY
                    soa_no, tenant_id
                ) AS p
                ON 
                    s.tenant_id = p.tenant_id AND s.soa_no = p.soa_no
            WHERE
                s.tenant_id = '$tenant_id'
            AND
                s.posting_date < '$posting_date'
            ORDER BY
                s.posting_date DESC, s.id DESC
            LIMIT 1
        ");

        return $query->row();
    }

    public function getSoaWithBalances($tenant_id, $posting_date){
        $query = $this->db->query(
            "SELECT
                s.id,
                s.tenant_id, 
                s.soa_no,
                f.billing_period,
                f.collection_date,
                f.posting_date,
                IFNULL(i.balance, 0) as inv_balance,
                IFNULL(p.balance, 0) as pre_balance,
                SUM(IFNULL(i.balance, 0)+IFNULL(p.balance, 0)) AS balance
            FROM 
                soa_line s
            LEFT JOIN
                soa_file AS f
            ON
                f.soa_no = s.soa_no AND f.tenant_id = s.tenant_id
            LEFT JOIN
                (SELECT  
                    (CASE 
                        WHEN inv.tag = 'Retro Rent' THEN 'Retro'
                        ELSE inv.document_type
                    END) AS document_type,
                    inv.doc_no,
                    inv.tenant_id, 
                    SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance
                FROM
                    (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                        FROM subsidiary_ledger
                        WHERE gl_accountID IN (22,29,4)
                        AND document_type = 'Invoice'
                        AND tenant_id = '$tenant_id'
                        AND posting_date <= '$posting_date'
                        GROUP BY ref_no, gl_accountID, tenant_id) as inv
                LEFT JOIN
                    (SELECT tenant_id,
                        doc_no,
                        ref_no,
                        gl_accountID,
                        SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                    FROM subsidiary_ledger
                    WHERE gl_accountID IN (22,29,4)
                    AND document_type IN ('Credit Memo', 'Debit Memo', 'Invoice Adjustment')
                    AND tenant_id <> 'DELETED'
                    GROUP BY ref_no, gl_accountID, tenant_id
                    ) AS memo
                    ON
                        inv.ref_no = memo.ref_no AND 
                        inv.gl_accountID = memo.gl_accountID AND 
                        inv.tenant_id = memo.tenant_id
                LEFT JOIN
                    (SELECT p.tenant_id,
                        p.doc_no,
                        p.ref_no,
                        p.gl_accountID,
                        SUM(IFNULL(p.debit,0) + IFNULL(p.credit,0)) as amount
                    FROM subsidiary_ledger p
                    WHERE p.gl_accountID IN (22,29,4)
                    AND (p.document_type = 'Payment' OR  p.document_type = 'Payment Adjustment')
                    AND p.tenant_id <> 'DELETED'
                    GROUP BY p.ref_no, p.gl_accountID, p.tenant_id) AS pmt
                    ON  inv.ref_no = pmt.ref_no AND 
                        inv.gl_accountID = pmt.gl_accountID  AND 
                        inv.tenant_id = pmt.tenant_id
                GROUP BY 
                    inv.ref_no, inv.gl_accountID, inv.tenant_id
                HAVING
                    ABS(balance) >= 1
                ORDER BY 
                    document_type ASC, inv.gl_accountID ASC, inv.posting_date ASC) 
            AS i
            ON s.doc_no = i.doc_no AND s.tenant_id = i.tenant_id
            LEFT JOIN
                (SELECT 
                    id,
                    doc_no,
                    tenant_id,
                    amount as balance
                FROM 
                    `tmp_preoperationcharges` 
                WHERE 
                    `tenant_id` = '$tenant_id'
                AND 
                    posting_date <= '$posting_date'
                AND 
                    (`tag` = '' || `tag` = 'Posted')
                AND 
                    amount >= 1
                ORDER BY
                    posting_date ASC, id ASC)
            AS p
            ON s.preop_id = p.id AND s.tenant_id = p.tenant_id

            WHERE 
                s.tenant_id = '$tenant_id' AND f.posting_date <= '$posting_date'
            GROUP BY
                s.soa_no, s.tenant_id
            HAVING 
                balance >= 1
            ORDER BY f.posting_date DESC, s.id DESC"
        )->result_object();


        /*$soa_docs = array_map(function($soa){
            return $soa->soa_no;
        }, $query);*/


        return $query;
    }

    public function getInvoicesBySoaNo($tenant_id, $soa_no, $pmt_type = ''){
        $docs = $this->db->query("
            SELECT 
                * 
            FROM 
                soa_line 
            WHERE 
                soa_no='$soa_no' 
            AND 
                tenant_id = '$tenant_id'
            ")->result_object();

        $invs = [];
        $preops = [];
        foreach ($docs as $key => $doc) {
            if($doc->preop_id){
                //IF FOR PAYMENT USING PREOP, DONT INCLUDE PREOP INVOICES
                if($pmt_type != 'preop_payment'){
                    $preops[] = $doc->preop_id;
                }
                
            }else{
                $invs[] = $doc->doc_no;
            }
        }

        $inv_docnos = "'".implode("','", $invs)."'";
        $preops_ids = "'".implode("','", $preops)."'";

        $invoices = $this->db->query("
            SELECT  
                (CASE 
                    WHEN inv.tag = 'Retro Rent' THEN 'Retro'
                    ELSE inv.document_type
                END) AS document_type,
                inv.doc_no,
                inv.posting_date,
                inv.due_date,
                inv.ref_no,
                (CASE
                    WHEN inv.gl_accountID = '4' AND inv.tag = 'Retro Rent'  THEN CONCAT('Basic-', p.trade_name, '-Retro Rent')
                    WHEN inv.gl_accountID = '4' THEN CONCAT('Basic-', p.trade_name)
                    WHEN inv.gl_accountID IN (22,29) AND inv.tag = 'Penalty' THEN CONCAT('Other-', p.trade_name,'-Penalty')
                    ELSE CONCAT('Other-', p.trade_name) 
                END) AS description,
                inv.tag,
                inv.gl_accountID,
                inv.tenant_id,                
                SUM(IFNULL(inv.amount,0)) as invoice_amount,
                SUM(IFNULL(memo.amount, 0)) as adj_amount,
                SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                SUM(IFNULL(pmt.amount, 0)) as credit,
                SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance,
                ABS(SUM(IFNULL(np.amount, 0))) AS nopenalty_amount
            FROM
                (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                    FROM subsidiary_ledger
                    WHERE gl_accountID IN (22,29,4)
                    AND document_type = 'Invoice'
                    AND tenant_id = '$tenant_id'
                    AND doc_no IN ($inv_docnos)
                    GROUP BY ref_no, gl_accountID, tenant_id) as inv
            LEFT JOIN
                (SELECT tenant_id,
                    doc_no,
                    ref_no,
                    gl_accountID,
                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                FROM subsidiary_ledger
                WHERE gl_accountID IN (22,29,4)
                AND document_type IN ('Credit Memo', 'Debit Memo', 'Invoice Adjustment')
                AND tenant_id <> 'DELETED'
                GROUP BY ref_no, gl_accountID, tenant_id
                ) AS memo
                ON
                    inv.ref_no = memo.ref_no AND 
                    inv.gl_accountID = memo.gl_accountID AND 
                    inv.tenant_id = memo.tenant_id
            LEFT JOIN
                (SELECT p.tenant_id,
                        p.doc_no,
                        p.ref_no,
                        p.gl_accountID,
                        SUM(IFNULL(p.debit,0) + IFNULL(p.credit,0)) as amount
                FROM subsidiary_ledger p
                WHERE p.gl_accountID IN (22,29,4)
                AND (p.document_type = 'Payment' OR  p.document_type = 'Payment Adjustment')
                AND p.tenant_id <> 'DELETED'
                GROUP BY p.ref_no, p.gl_accountID, p.tenant_id) AS pmt
                ON  inv.ref_no = pmt.ref_no AND 
                    inv.gl_accountID = pmt.gl_accountID  AND 
                    inv.tenant_id = pmt.tenant_id
            LEFT JOIN
                (SELECT tenant_id,
                        doc_no,
                        ref_no,
                        SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                FROM subsidiary_ledger
                WHERE gl_accountID NOT IN (22,29,4)
                AND document_type = 'Invoice'
                AND with_penalty = 'No'
                AND tenant_id <> 'DELETED'
                GROUP BY ref_no, doc_no, tenant_id
                ) AS np
                ON  inv.ref_no = np.ref_no AND 
                    inv.doc_no = np.doc_no  AND 
                    inv.tenant_id = np.tenant_id AND
                    inv.gl_accountID <> 4
            LEFT JOIN
                (SELECT 
                    distinct(tnt.prospect_id) as prospect_id, 
                    tnt.tenant_id, tnt.store_code  
                FROM 
                    tenants tnt) 
                AS t 
                ON t.tenant_id = inv.tenant_id
            LEFT JOIN 
                prospect p 
                ON p.id = t.prospect_id
            GROUP BY 
                inv.ref_no, inv.gl_accountID, inv.tenant_id
            HAVING
                balance >= 1
            ORDER BY 
                inv.posting_date ASC, inv.gl_accountID ASC, inv.id ASC
        ")->result_object();

       /* ====================================================================
        SUM(IFNULL(adj.amount,0)) as pmt_adj,
        SUM(IFNULL(p.debit,0) + IFNULL(p.credit,0)) + SUM(IFNULL(adj.amount,0)) as amount
        LEFT JOIN 
            (SELECT tenant_id,
                doc_no,
                ref_no,
                adj_ref,
                gl_accountID,
                SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
            FROM subsidiary_ledger
            WHERE document_type = 'Payment Adjustment'
            AND tenant_id <> 'DELETED'
            GROUP BY ref_no, gl_accountID, tenant_id, ft_ref) AS  adj
            ON p.ref_no = adj.ref_no AND p.doc_no = adj.adj_ref AND p.gl_accountID = adj.gl_accountID AND p.tenant_id = adj.tenant_id
        ==============================================================================================================================*/

        $preop_charges = $this->db->query("
            SELECT 
                id,
                'Preop-Charges' as document_type,
                doc_no,
                description,
                posting_date,
                due_date,
                IFNULL(org_amount, amount)  as debit,
                (CASE 
                    WHEN org_amount IS NULL THEN 0
                    ELSE (org_amount - amount) * -1 
                END) AS credit,
                amount as balance
            FROM 
                `tmp_preoperationcharges` 
            WHERE 
                `tenant_id` = '$tenant_id' 
            AND 
                (`tag` = '' || `tag` = 'Posted')
            AND 
                amount >= 1
            AND
                id IN ($preops_ids)
            ORDER BY
                posting_date ASC, id ASC
        ")->result_object();

        $data = array_merge($preop_charges, $invoices);

        usort($data, function($d1, $d2){
            $date1 = strtotime($d1->posting_date);
            $date2 = strtotime($d2->posting_date);

            if ($date1 == $date2) {
                if(!empty($d2->gl_accountID) && !empty($d1->gl_accountID) && $d2->gl_accountID == 4 && $d1->gl_accountID == 4){
                    return 0;
                }elseif(!empty($d2->gl_accountID) && !empty($d1->gl_accountID) && $d2->gl_accountID == 4 && $d1->gl_accountID != 4){
                    return 1;
                }elseif(!empty($d2->gl_accountID) && !empty($d1->gl_accountID)){
                    return 0;
                }elseif(empty($d2->gl_accountID) && !empty($d1->gl_accountID)){
                    return 1;
                }else{
                    return -1;
                }
            }

            return ($date1 < $date2) ? -1 : 1;           
        });

        foreach ($data as $key => $inv) {
            $inv->org_sequence = $key;
            $inv->sequence = null;
        }

        return $data;
    }

    function getAccreditedBanks(){

        $store_code = $this->session->userdata['store_code'];
        $query = $this->db->query("SELECT * FROM accredited_banks WHERE store_code = '$store_code'");
        return $query->result_object();

    }

    public function get_soaDetails($tenant_id, $soa_no)
    {
        return $this->db->query("
            SELECT 
                * 
            FROM 
                `soa_file` 
            WHERE 
                `tenant_id` = '$tenant_id' 
            AND 
                `soa_no` = '$soa_no' 
            LIMIT 1
        ")->row();
    }

    public function checkPaymentReceiptExistence($receipt_no){
        $result = $this->db->query("
            SELECT 
                * 
            FROM 
                `payment_scheme` 
            WHERE
                ROUND(`amount_paid`) >= 1
            AND
                (`receipt_no` = '$receipt_no' 
            OR 
                `receipt_no` = 'PR".$receipt_no."')
        ")->result_array();

        return !empty($result);
        
    }

    public function getLedgerFirstResultByDocNo($tenant_id, $doc_no){
        $query = $this->db->query("
            SELECT 
                * 
            FROM 
                ledger 
            WHERE 
                tenant_id = '$tenant_id'  
            AND 
                doc_no='$doc_no' 
            LIMIT 1
        ");

        return $query->row();
    }

    public function getLastSoa(){

    }
    public function updatePaymentSoaTagging($soa_no){

        $soa = $this->db->query("SELECT  *  FROM  `soa_file` WHERE `soa_no` = '$soa_no'  LIMIT 1")->row();

        if(!empty($soa)){
            $next_soa = $this->db->query("SELECT 
                    *
                FROM
                    soa_file
                WHERE
                    tenant_id = '$soa->tenant_id'
                        AND posting_date <= '$soa->posting_date'
                        AND soa_no <> '$soa->soa_no'
                ORDER BY posting_date DESC , soa_no DESC
                LIMIT 1
            ")->row(); 


            if(!empty($next_soa)){
                $this->db->update('payment', ['soa_no'=> $next_soa->soa_no], "soa_no = '$soa_no'");
            }    
        }

    }

    // =========================================================================================== KING ARTHURS REVISION =======================================================//
    public function get_lprospect()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`request_date`,
                `s`.`store_name`,
                `p`.`status`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `leasing_users` `u`
            WHERE
               `s`.`id` = `p`.`store_id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                (`p`.`status` != 'On Contract' AND `p`.`status` != 'Denied' AND `p`.`status` != 'Revoked' AND `p`.`status` != 'Deleted')
            AND
                `p`.`flag` = 'Long Term'
            GROUP BY
                `p`.`id`
        ");

        return $query->result_array();
    }

    public function getAllz($tbl_name, $orderBy)
    {
        $this->db->select('*');
        $this->db->from($tbl_name);
        $this->db->order_by($orderBy, 'ASC');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getAll2($tbl_name)
    {
        // $query = $this->db->get($tbl_name);
        // return $query->result_array();

        $this->db->select('*');
        $this->db->from($tbl_name);
        $this->db->where('status', 'Active');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_deniedLprospect()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`request_date`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`approved_date`,
                `lt`.`leasee_type`,
                `s`.`store_name`,
                `p`.`status`,
                `p`.`remarks`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `leasing_users` `u`
            WHERE
                `p`.`lesseeType_id` = `lt`.`id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                (`p`.`status` =  'Denied' OR `p`.`status` =  'Revoked')
            AND
                `s`.`id` = `p`.`store_id`
            AND
                `p`.`flag` = 'Long Term'
        ");


        return $query->result_array();
    }

    public function get_deniedSprospect()
    {
        $query = $this->db->query(
            "SELECT
                `p`.`id`,
                `p`.`trade_name`,
                `p`.`corporate_name`,
                `p`.`request_date`,
                `p`.`address`,
                `p`.`first_category`,
                `p`.`second_category`,
                `p`.`third_category`,
                `p`.`contact_person`,
                `p`.`contact_number`,
                `p`.`approved_date`,
                `lt`.`leasee_type`,
                `s`.`store_name`,
                `p`.`status`,
                `p`.`remarks`,
                CONCAT(`u`.`first_name`, ' ', `u`.`last_name`) AS `prepared_by`
            FROM
                `prospect` `p`,
                `leasee_type` `lt`,
                `stores` `s`,
                `leasing_users` `u`
            WHERE
                `p`.`lesseeType_id` = `lt`.`id`
            AND
                `p`.`prepared_by` = `u`.`id`
            AND
                (`p`.`status` =  'Denied' OR `p`.`status` =  'Revoked')
            AND
                `s`.`id` = `p`.`store_id`
            AND
                `p`.`flag` = 'Short Term'
        ");


        return $query->result_array();
    }

    public function get_tenant_ledger($tenant_id, $startDate, $endDate)
    {
        $query = $this->db->query(" SELECT  inv.id,
                                            inv.document_type,
                                            (CASE 
                                                WHEN inv.gl_accountID = '4' THEN 'Basic Rent' ELSE 'Other Charges' 
                                            END) AS type,
                                            inv.ref_no,
                                            inv.doc_no,
                                            inv.posting_date,
                                            inv.due_date,
                                            inv.amount as invoice_amount,
                                            memo.amount as adj_amount,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                                            SUM(IFNULL(pmt.amount, 0)) as credit,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance

                                    FROM

                                        (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type = 'Invoice'
                                            AND tenant_id = '$tenant_id'
                                            AND posting_date BETWEEN '$startDate' AND '$endDate'
                                            GROUP BY ref_no, gl_accountID, tenant_id) as inv

                                    LEFT JOIN

                                        (SELECT tenant_id,
                                                doc_no,
                                                ref_no,
                                                gl_accountID,
                                                SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type IN ('Credit Memo', 'Debit Memo', 'Invoice Adjustment')
                                            AND tenant_id <> 'DELETED'
                                            GROUP BY ref_no, gl_accountID, tenant_id) AS memo
                                    ON
                                        inv.ref_no = memo.ref_no AND inv.gl_accountID = memo.gl_accountID  AND inv.gl_accountID = memo.gl_accountID

                                    LEFT JOIN
    
                                        (SELECT tenant_id,
                                                doc_no,
                                                ref_no,
                                                gl_accountID,
                                                SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                        FROM subsidiary_ledger
                                        WHERE gl_accountID IN (22,29,4)
                                        AND document_type IN ('Payment', 'Payment Adjustment')
                                        AND tenant_id <> 'DELETED'
                                        GROUP BY ref_no, gl_accountID, tenant_id) AS pmt
                                    ON 
                                        inv.ref_no = pmt.ref_no AND inv.gl_accountID = pmt.gl_accountID  AND inv.gl_accountID = pmt.gl_accountID
                                    GROUP BY inv.ref_no, inv.gl_accountID, inv.tenant_id
                                    ORDER BY inv.posting_date, inv.id, inv.gl_accountID ");

        // GROUP BY ref_no, gl_accountID, tenant_id

        
        $result = $query->result_array();
        $fBalance = $this->get_forwarded_balance($tenant_id, $startDate);
        $runningBalance = empty($fBalance)?0: $fBalance[0]['balance'];

        foreach ($result as $key => $row) 
        {
            $result[$key]['runningBalance'] = $runningBalance += $row['balance'];
        }

        return $result;
    }

    public function get_forwarded_balance($tenant_id, $startDate)
    {
        $query = $this->db->query("SELECT  inv.id,
                                            inv.document_type,
                                            (CASE 
                                                WHEN inv.gl_accountID = '4' THEN 'Basic Rent' ELSE 'Other Charges' 
                                            END) AS type,
                                            inv.ref_no,
                                            inv.doc_no,
                                            inv.posting_date,
                                            inv.due_date,
                                            inv.amount as invoice_amount,
                                            memo.amount as adj_amount,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                                            SUM(IFNULL(pmt.amount, 0)) as credit,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance

                                    FROM

                                        (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type = 'Invoice'
                                            AND tenant_id = '$tenant_id'
                                            AND posting_date < '$startDate'
                                            GROUP BY ref_no, gl_accountID, tenant_id) as inv

                                    LEFT JOIN

                                        (SELECT tenant_id,
                                                doc_no,
                                                ref_no,
                                                gl_accountID,
                                                SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type IN ('Credit Memo', 'Debit Memo', 'Invoice Adjustment')
                                            AND tenant_id <> 'DELETED'
                                            GROUP BY ref_no, gl_accountID, tenant_id) AS memo
                                    ON
                                        inv.ref_no = memo.ref_no AND inv.gl_accountID = memo.gl_accountID  AND inv.gl_accountID = memo.gl_accountID

                                    LEFT JOIN
    
                                        (SELECT tenant_id,
                                                doc_no,
                                                ref_no,
                                                gl_accountID,
                                                SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                        FROM subsidiary_ledger
                                        WHERE gl_accountID IN (22,29,4)
                                        AND document_type = 'Payment'
                                        AND tenant_id <> 'DELETED'
                                        GROUP BY ref_no, gl_accountID, tenant_id) AS pmt
                                    ON 
                                        inv.ref_no = pmt.ref_no AND inv.gl_accountID = pmt.gl_accountID  AND inv.gl_accountID = pmt.gl_accountID");
        return $query->result_array();
    }

    public function get_payment_details($ref_no, $tenant_id)
    {
        $query = $this->db->query("
                SELECT 
                    `doc_no`, 
                    `posting_date`, 
                    `gl_accounts`.`gl_account` as `payment_type`, 
                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                FROM 
                    `subsidiary_ledger`
                LEFT JOIN 
                    `gl_accounts` ON `subsidiary_ledger`.`gl_accountID` = `gl_accounts`.`id`
                WHERE 
                    `ref_no` = '$ref_no'
                AND 
                    `tenant_id` = '$tenant_id' 
                AND 
                    `document_type` IN ('Payment', 'Payment Adjustment') 
                AND 
                    `gl_accountID` NOT IN (4,22,29) GROUP BY ref_no, gl_accountID, tenant_id, doc_no");

        
        if (!empty($query)) 
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    public function get_ledgerAdjustment($tenant_id, $searchType, $searchBy)
    {
        if ($searchBy == 'Show All') 
        {
            $query = $this->get_ledgerAdjustmentAll($tenant_id);
        }
        else if ($searchBy == 'Doc No') 
        {
            $query = $this->get_ledgerAdjustmentDocumentType($tenant_id, $searchType);
        }
        else if ($searchBy == 'Ref No') 
        {
            $query = $this->get_ledgerAdjustmentReferenceNo($tenant_id, $searchType);
        }
        else
        {
            $query = $this->get_ledgerAdjustmentPostingDate($tenant_id, $searchType);
        }

        return $query;
    }

    public function get_ledgerAdjustmentAll($tenant_id)
    {
        $query = $this->db->query(" SELECT  
                                            inv.id,
                                            inv.document_type,
                                            (CASE
                                                WHEN 
                                                inv.gl_accountID = '4' THEN 'Basic Rent' ELSE 'Other Charges'
                                            END) AS type,
                                            inv.ref_no,
                                            inv.doc_no,
                                            inv.posting_date,
                                            inv.due_date,
                                            inv.amount as invoice_amount,
                                            memo.amount as adj_amount,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                                            SUM(IFNULL(pmt.amount, 0)) as credit,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance

                                    FROM
                                            (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                                FROM subsidiary_ledger
                                                WHERE gl_accountID IN (22,29,4)
                                                AND document_type = 'Invoice'
                                                AND tenant_id = '$tenant_id'
                                                GROUP BY ref_no, gl_accountID, tenant_id) as inv

                                    LEFT JOIN

                                            (SELECT tenant_id,
                                                    doc_no,
                                                    ref_no,
                                                    gl_accountID,
                                                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                                FROM subsidiary_ledger
                                                WHERE gl_accountID IN (22,29,4)
                                                AND document_type IN ('Invoice Adjustment', 'Debit Memo', 'Credit Memo')
                                                AND tenant_id <> 'DELETED'
                                                GROUP BY ref_no, gl_accountID, tenant_id) AS memo
                                    ON
                                            inv.ref_no = memo.ref_no AND inv.gl_accountID = memo.gl_accountID  AND inv.gl_accountID = memo.gl_accountID

                                    LEFT JOIN
    
                                            (SELECT tenant_id,
                                                    doc_no,
                                                    ref_no,
                                                    gl_accountID,
                                                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type IN ('Payment', 'Payment Adjustment')
                                            AND tenant_id <> 'DELETED'
                                            GROUP BY ref_no, gl_accountID, tenant_id) AS pmt
                                    ON 
                                            inv.ref_no = pmt.ref_no AND inv.gl_accountID = pmt.gl_accountID 
                                            GROUP BY inv.ref_no, inv.gl_accountID, inv.tenant_id
                                            ORDER BY inv.posting_date, inv.id, inv.gl_accountID");

        return  $query->result_array();
    }

    public function get_ledgerAdjustmentDocumentType($tenant_id, $doc_no)
    {
        $query = $this->db->query(" SELECT  
                                            inv.id,
                                            inv.document_type,
                                            (CASE
                                                WHEN 
                                                inv.gl_accountID = '4' THEN 'Basic Rent' ELSE 'Other Charges'
                                            END) AS type,
                                            inv.ref_no,
                                            inv.doc_no,
                                            inv.posting_date,
                                            inv.due_date,
                                            inv.amount as invoice_amount,
                                            memo.amount as adj_amount,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                                            SUM(IFNULL(pmt.amount, 0)) as credit,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance

                                    FROM
                                            (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                                FROM subsidiary_ledger
                                                WHERE gl_accountID IN (22,29,4)
                                                AND document_type = 'Invoice'
                                                AND tenant_id = '$tenant_id'
                                                AND doc_no = '$doc_no'
                                                GROUP BY ref_no, gl_accountID, tenant_id) as inv

                                    LEFT JOIN

                                            (SELECT tenant_id,
                                                    doc_no,
                                                    ref_no,
                                                    gl_accountID,
                                                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                                FROM subsidiary_ledger
                                                WHERE gl_accountID IN (22,29,4)
                                                AND document_type IN ('Invoice Adjustment', 'Debit Memo', 'Credit Memo')
                                                AND tenant_id <> 'DELETED'
                                                GROUP BY ref_no, gl_accountID, tenant_id) AS memo
                                    ON
                                            inv.ref_no = memo.ref_no AND inv.gl_accountID = memo.gl_accountID  AND inv.gl_accountID = memo.gl_accountID

                                    LEFT JOIN
    
                                            (SELECT tenant_id,
                                                    doc_no,
                                                    ref_no,
                                                    gl_accountID,
                                                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type = 'Payment'
                                            AND tenant_id <> 'DELETED'
                                            GROUP BY ref_no, gl_accountID, tenant_id) AS pmt
                                    ON 
                                            inv.ref_no = pmt.ref_no AND inv.gl_accountID = pmt.gl_accountID  AND inv.gl_accountID = pmt.gl_accountID
                                            GROUP BY inv.ref_no, inv.gl_accountID, inv.tenant_id
                                            ORDER BY inv.posting_date, inv.id, inv.gl_accountID");

        return  $query->result_array();
    }

    public function get_ledgerAdjustmentReferenceNo($tenant_id, $ref_no)
    {
        $query = $this->db->query(" SELECT  
                                            inv.id,
                                            inv.document_type,
                                            (CASE
                                                WHEN 
                                                inv.gl_accountID = '4' THEN 'Basic Rent' ELSE 'Other Charges'
                                            END) AS type,
                                            inv.ref_no,
                                            inv.doc_no,
                                            inv.posting_date,
                                            inv.due_date,
                                            inv.amount as invoice_amount,
                                            memo.amount as adj_amount,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                                            SUM(IFNULL(pmt.amount, 0)) as credit,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance

                                    FROM
                                            (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                                FROM subsidiary_ledger
                                                WHERE gl_accountID IN (22,29,4)
                                                AND document_type = 'Invoice'
                                                AND tenant_id = '$tenant_id'
                                                AND ref_no = '$ref_no'
                                                GROUP BY ref_no, gl_accountID, tenant_id) as inv

                                    LEFT JOIN

                                            (SELECT tenant_id,
                                                    doc_no,
                                                    ref_no,
                                                    gl_accountID,
                                                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                                FROM subsidiary_ledger
                                                WHERE gl_accountID IN (22,29,4)
                                                AND document_type IN ('Invoice Adjustment', 'Debit Memo', 'Credit Memo')
                                                AND tenant_id <> 'DELETED'
                                                GROUP BY ref_no, gl_accountID, tenant_id) AS memo
                                    ON
                                            inv.ref_no = memo.ref_no AND inv.gl_accountID = memo.gl_accountID  AND inv.gl_accountID = memo.gl_accountID

                                    LEFT JOIN
    
                                            (SELECT tenant_id,
                                                    doc_no,
                                                    ref_no,
                                                    gl_accountID,
                                                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type = 'Payment'
                                            AND tenant_id <> 'DELETED'
                                            GROUP BY ref_no, gl_accountID, tenant_id) AS pmt
                                    ON 
                                            inv.ref_no = pmt.ref_no AND inv.gl_accountID = pmt.gl_accountID  AND inv.gl_accountID = pmt.gl_accountID
                                            GROUP BY inv.ref_no, inv.gl_accountID, inv.tenant_id
                                            ORDER BY inv.posting_date, inv.id, inv.gl_accountID");

        return $query->result_array();
    }

    public function get_ledgerAdjustmentPostingDate($tenant_id, $posting_date)
    {
        $query = $this->db->query(" SELECT  
                                            inv.id,
                                            inv.document_type,
                                            (CASE
                                                WHEN 
                                                inv.gl_accountID = '4' THEN 'Basic Rent' ELSE 'Other Charges'
                                            END) AS type,
                                            inv.ref_no,
                                            inv.doc_no,
                                            inv.posting_date,
                                            inv.due_date,
                                            inv.amount as invoice_amount,
                                            memo.amount as adj_amount,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                                            SUM(IFNULL(pmt.amount, 0)) as credit,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance

                                    FROM
                                            (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                                FROM subsidiary_ledger
                                                WHERE gl_accountID IN (22,29,4)
                                                AND document_type = 'Invoice'
                                                AND tenant_id = '$tenant_id'
                                                AND posting_date = '$posting_date'
                                                GROUP BY ref_no, gl_accountID, tenant_id) as inv

                                    LEFT JOIN

                                            (SELECT tenant_id,
                                                    doc_no,
                                                    ref_no,
                                                    gl_accountID,
                                                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                                FROM subsidiary_ledger
                                                WHERE gl_accountID IN (22,29,4)
                                                AND document_type IN ('Invoice Adjustment', 'Debit Memo', 'Credit Memo')
                                                AND tenant_id <> 'DELETED'
                                                GROUP BY ref_no, gl_accountID, tenant_id) AS memo
                                    ON
                                            inv.ref_no = memo.ref_no AND inv.gl_accountID = memo.gl_accountID  AND inv.gl_accountID = memo.gl_accountID

                                    LEFT JOIN
    
                                            (SELECT tenant_id,
                                                    doc_no,
                                                    ref_no,
                                                    gl_accountID,
                                                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type = 'Payment'
                                            AND tenant_id <> 'DELETED'
                                            GROUP BY ref_no, gl_accountID, tenant_id) AS pmt
                                    ON 
                                            inv.ref_no = pmt.ref_no AND inv.gl_accountID = pmt.gl_accountID  AND inv.gl_accountID = pmt.gl_accountID
                                            GROUP BY inv.ref_no, inv.gl_accountID, inv.tenant_id
                                            ORDER BY inv.posting_date, inv.id, inv.gl_accountID");

        return $query->result_array();
    }

    public function get_ledgerAdjustmentPayment($tenant_id, $ORNUMBER)
    {
        /*$query = $this->db->query("
            SELECT  
                (CASE
                    WHEN c.doc_no IS NOT NULL THEN 'Payment'
                    WHEN p.gl_accountID IN (4,22,29) AND i.document_type IS NOT NULL THEN i.document_type
                    WHEN p.gl_accountID = 9 THEN 'Sec. Deposit'
                    WHEN p.gl_accountID = 8 THEN 'Construction Bond'
                    WHEN p.gl_accountID = 7 THEN 'Advance'
                    ELSE ''
                END) as applied_to,
                (CASE
                    WHEN c.doc_no IS NOT NULL THEN c.doc_no
                    WHEN i.doc_no IS NOT NULL THEN i.doc_no
                    ELSE NULL
                END)  as doc_applied,
                a.gl_account,
                p.tenant_id,
                p.doc_no pmt_doc_no,
                p.ref_no,
                p.gl_accountID,
                (CASE
                    WHEN c.doc_no IS NOT NULL THEN SUM(c.amount  + IFNULL(p.debit,0) + IFNULL(p.credit,0) + IFNULL(adj.amount,0))
                    WHEN SUM(i.balance) IS NOT NULL THEN SUM(i.balance)
                    ELSE NULL
                END) as balance,
                SUM(IFNULL(adj.amount,0)) as pmt_adj,
                SUM(IFNULL(p.debit,0) + IFNULL(p.credit,0)) + SUM(IFNULL(adj.amount,0)) as pmt_amount,
                ABS(IFNULL(p.credit, 0)) as org_amount,
                 (CASE 
                        WHEN p.doc_no LIKE 'B%' 
                        THEN (SELECT p2.ref_no 
                            FROM subsidiary_ledger p2 
                            WHERE IFNULL(p2.debit, 0) > 0 
                            AND ABS(p2.debit) = ABS(p.credit)  
                            AND p2.doc_no = p.doc_no 
                            AND IFNULL(p2.ft_ref, '') = IFNULL(p.ft_ref, '') 
                            AND p2.posting_date = p.posting_date
                            AND p2.document_type = 'Payment'
                            AND p2.tenant_id = p.tenant_id
                            LIMIT 1)
                        ELSE p.ref_no
                    END) AS debit_refno  
            FROM subsidiary_ledger p
            LEFT JOIN 
                (SELECT tenant_id,
                    doc_no,
                    ref_no,
                    adj_ref,
                    ft_ref,
                    gl_accountID,
                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                FROM subsidiary_ledger
                WHERE document_type = 'Payment Adjustment'
                AND tenant_id = '$tenant_id'
                GROUP BY ref_no, gl_accountID, tenant_id, ft_ref
                ) 
                AS  adj
                ON p.ref_no = adj.ref_no 
                AND p.doc_no = adj.doc_no 
                AND IFNULL(p.ft_ref, '') = IFNULL(adj.ft_ref, '') 
                AND p.gl_accountID = adj.gl_accountID 
                AND p.tenant_id = adj.tenant_id
            LEFT JOIN
                (SELECT
                    doc_no,
                    ref_no,
                    tenant_id,
                    gl_accountID,
                    ft_ref,
                    status,
                    ABS(SUM(IFNULL(debit, 0) + IFNULL(credit, 0))) amount
                FROM 
                    subsidiary_ledger
                WHERE
                    document_type IN ('Payment', 'Payment Adjustment')
                AND
                    tenant_id = '$tenant_id'
                GROUP BY 
                    ref_no, gl_accountID, tenant_id, IFNULL(ft_ref, ''), doc_no
                ) AS c
                ON p.tenant_id = c.tenant_id 
                AND p.doc_no <> c.doc_no 
                AND p.ref_no = c.ref_no
                AND IFNULL(p.ft_ref, '') <> ''
                AND IFNULL(c.ft_ref, '') <> ''
                AND p.ft_ref = c.ft_ref
                AND p.gl_accountID = c.gl_accountID
                AND IFNULL(p.status, '') = ''
            LEFT JOIN
                (SELECT  
                    (CASE 
                        WHEN inv.gl_accountID = '4' AND inv.tag = 'Retro Rent' THEN 'Retro Rent'
                        WHEN inv.gl_accountID = '4' THEN 'Basic Rent'
                        WHEN inv.gl_accountID IN (22,29) AND inv.tag = 'Penalty' THEN 'Penalty'
                        ELSE 'Others'
                    END) AS document_type,
                    inv.doc_no,
                    inv.posting_date,
                    inv.due_date,
                    inv.ref_no,
                    inv.gl_accountID,
                    inv.tenant_id,                
                    SUM(IFNULL(inv.amount,0)) as invoice_amount,
                    SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                    SUM(IFNULL(pmt.amount, 0)) as credit,
                    SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance
                FROM
                    (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                        FROM subsidiary_ledger
                        WHERE gl_accountID IN (22,29,4)
                        AND document_type = 'Invoice'
                        AND tenant_id = '$tenant_id'
                        GROUP BY ref_no, gl_accountID, tenant_id) as inv
                LEFT JOIN
                    (SELECT tenant_id,
                            doc_no,
                            ref_no,
                            gl_accountID,
                            SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                    FROM subsidiary_ledger
                    WHERE gl_accountID IN (22,29,4)
                    AND document_type IN ('Credit Memo', 'Debit Memo', 'Invoice Adjustment')
                    AND tenant_id =  '$tenant_id'
                    GROUP BY ref_no, gl_accountID, tenant_id
                    ) AS memo
                    ON
                        inv.ref_no = memo.ref_no AND 
                        inv.gl_accountID = memo.gl_accountID AND 
                        inv.tenant_id = memo.tenant_id
                LEFT JOIN
                    (SELECT p.tenant_id,
                            p.doc_no,
                            p.ref_no,
                            p.gl_accountID,
                            SUM(IFNULL(p.debit,0) + IFNULL(p.credit,0)) as amount
                    FROM subsidiary_ledger p
                    WHERE p.gl_accountID IN (22,29,4)
                    AND p.document_type IN ('Payment', 'Payment Adjustment')
                    AND p.tenant_id = '$tenant_id'
                    GROUP BY p.ref_no, p.gl_accountID, p.tenant_id
                    ) AS pmt
                    ON  inv.ref_no = pmt.ref_no AND 
                        inv.gl_accountID = pmt.gl_accountID  AND 
                        inv.tenant_id = pmt.tenant_id
                GROUP BY 
                    inv.ref_no, inv.gl_accountID, inv.tenant_id
                ORDER BY 
                    document_type ASC, inv.gl_accountID ASC, inv.posting_date ASC) 
                AS i
                ON  p.ref_no = i.ref_no 
                    AND p.gl_accountID = i.gl_accountID 
                    AND p.tenant_id = i.tenant_id 
                    AND c.doc_no IS NULL
            LEFT JOIN
                gl_accounts a
                ON p.gl_accountID = a.id
            WHERE IFNULL(p.credit, 0) <> 0
            AND p.document_type = 'Payment'
            AND p.tenant_id = '$tenant_id'
            AND p.doc_no = '$ORNUMBER'
            GROUP BY p.ref_no, p.gl_accountID, p.tenant_id, p.id
            ");*/

        $query = $this->db->query("
            SELECT  
                (CASE
                    WHEN c.doc_no IS NOT NULL THEN 'Payment'
                    WHEN p.gl_accountID IN (4,22,29) AND i.document_type IS NOT NULL THEN i.document_type
                    WHEN p.gl_accountID = 9 THEN 'Sec. Deposit'
                    WHEN p.gl_accountID = 8 THEN 'Construction Bond'
                    WHEN p.gl_accountID = 7 THEN 'Advance'
                    ELSE ''
                END) as applied_to,
                (CASE
                    WHEN c.doc_no IS NOT NULL THEN c.doc_no
                    WHEN i.doc_no IS NOT NULL THEN i.doc_no
                    ELSE NULL
                END)  as doc_applied,
                a.gl_account,
                p.tenant_id,
                p.doc_no pmt_doc_no,
                p.ref_no,
                p.gl_accountID,
                (CASE
                    WHEN c.doc_no IS NOT NULL THEN c.amount  + IFNULL(p.pmt_amount,0)
                    WHEN i.balance IS NOT NULL THEN i.balance
                    ELSE NULL
                END) as balance,
                p.debit_refno,
                p.org_amount,
                p.pmt_adj,
                p.pmt_amount
            FROM (
                SELECT  
                    p1.*,
                    (CASE 
                        WHEN p1.doc_no LIKE 'B0%' OR p1.doc_no LIKE 'P0%'
                        THEN (SELECT p2.ref_no 
                            FROM subsidiary_ledger p2 
                            WHERE IFNULL(p2.debit, 0) > 0 
                            AND ABS(p2.debit) = ABS(p1.credit)  
                            AND p2.doc_no = p1.doc_no 
                            AND IFNULL(p2.ft_ref, '') = IFNULL(p1.ft_ref, '') 
                            AND p2.posting_date = p1.posting_date
                            AND p2.document_type = p1.document_type
                            AND p2.tenant_id = p1.tenant_id
                            LIMIT 1)
                        ELSE p1.ref_no
                    END) AS debit_refno,
                    ABS(IFNULL(p1.credit, 0)) as org_amount,
                    SUM((IFNULL(p1.debit, 0) + IFNULL(p1.credit, 0)) - IFNULL(p1.credit, 0)) as pmt_adj,
                    SUM(IFNULL(p1.debit, 0) + IFNULL(p1.credit, 0)) as  pmt_amount
                FROM subsidiary_ledger p1
                WHERE p1.document_type IN ('Payment', 'Payment Adjustment')
                AND p1.tenant_id = '$tenant_id'
                AND p1.doc_no = '$ORNUMBER'
                GROUP BY p1.doc_no, p1.ref_no, p1.gl_accountID, p1.tenant_id, p1.status, IFNULL(p1.ft_ref, '')
                HAVING pmt_amount <= 0 AND org_amount > 0
            ) p
            LEFT JOIN
                (SELECT
                    doc_no,
                    ref_no,
                    tenant_id,
                    gl_accountID,
                    ft_ref,
                    status,
                    ABS(SUM(IFNULL(debit, 0) + IFNULL(credit, 0))) amount
                FROM 
                    subsidiary_ledger
                WHERE
                    document_type IN ('Payment', 'Payment Adjustment')
                AND
                    tenant_id = '$tenant_id'
                GROUP BY 
                    ref_no, gl_accountID, tenant_id, IFNULL(ft_ref, ''), doc_no
                ) AS c
                ON p.tenant_id = c.tenant_id 
                AND p.doc_no <> c.doc_no 
                AND p.ref_no = c.ref_no
                AND IFNULL(p.ft_ref, '') <> ''
                AND IFNULL(c.ft_ref, '') <> ''
                AND p.ft_ref = c.ft_ref
                AND p.gl_accountID = c.gl_accountID
                AND IFNULL(p.status, '') = ''
            LEFT JOIN
                (SELECT  
                    (CASE 
                        WHEN inv.gl_accountID = '4' AND inv.tag = 'Retro Rent' THEN 'Retro Rent'
                        WHEN inv.gl_accountID = '4' THEN 'Basic Rent'
                        WHEN inv.gl_accountID IN (22,29) AND inv.tag = 'Penalty' THEN 'Penalty'
                        ELSE 'Others'
                    END) AS document_type,
                    inv.doc_no,
                    inv.posting_date,
                    inv.due_date,
                    inv.ref_no,
                    inv.gl_accountID,
                    inv.tenant_id,                
                    SUM(IFNULL(inv.amount,0)) as invoice_amount,
                    SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                    SUM(IFNULL(pmt.amount, 0)) as credit,
                    SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance
                FROM
                    (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                        FROM subsidiary_ledger
                        WHERE gl_accountID IN (22,29,4)
                        AND document_type = 'Invoice'
                        AND tenant_id = '$tenant_id'
                        GROUP BY ref_no, gl_accountID, tenant_id) as inv
                LEFT JOIN
                    (SELECT tenant_id,
                            doc_no,
                            ref_no,
                            gl_accountID,
                            SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                    FROM subsidiary_ledger
                    WHERE gl_accountID IN (22,29,4)
                    AND document_type IN ('Credit Memo', 'Debit Memo', 'Invoice Adjustment')
                    AND tenant_id =  '$tenant_id'
                    GROUP BY ref_no, gl_accountID, tenant_id
                    ) AS memo
                    ON
                        inv.ref_no = memo.ref_no AND 
                        inv.gl_accountID = memo.gl_accountID AND 
                        inv.tenant_id = memo.tenant_id
                LEFT JOIN
                    (SELECT p.tenant_id,
                            p.doc_no,
                            p.ref_no,
                            p.gl_accountID,
                            SUM(IFNULL(p.debit,0) + IFNULL(p.credit,0)) as amount
                    FROM subsidiary_ledger p
                    WHERE p.gl_accountID IN (22,29,4)
                    AND p.document_type IN ('Payment', 'Payment Adjustment')
                    AND p.tenant_id = '$tenant_id'
                    GROUP BY p.ref_no, p.gl_accountID, p.tenant_id
                    ) AS pmt
                    ON  inv.ref_no = pmt.ref_no AND 
                        inv.gl_accountID = pmt.gl_accountID  AND 
                        inv.tenant_id = pmt.tenant_id
                GROUP BY 
                    inv.ref_no, inv.gl_accountID, inv.tenant_id
                ORDER BY 
                    document_type ASC, inv.gl_accountID ASC, inv.posting_date ASC) 
                AS i
                ON  p.ref_no = i.ref_no 
                    AND p.gl_accountID = i.gl_accountID 
                    AND p.tenant_id = i.tenant_id 
                    AND c.doc_no IS NULL
            LEFT JOIN
                gl_accounts a
                ON p.gl_accountID = a.id
            ");

    

        return  $query->result_array();
    }

    public function get_adjNo($useNext = true)
    {
        $sequence = getSequenceNo(
            [   
                'code'          => "ADJ",
                'number'        => '1',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Adjustment"
            ],
            [
                'table'     =>  'adjustments',
                'column'    => 'adj_no'
            ],

            $useNext
        );

        // var_dump($this->db->last_query());
        return $sequence;
    }

    public function get_otherCharges1()
    {
        $query = $this->db->query(
                "SELECT DISTINCT
                    `c`.`id`,
                    `c`.`charges_type`,
                    `c`.`description`
                FROM
                    `charges_setup` `c`
                WHERE
                    `c`.`charges_type` != 'Pre Operation Charges' ORDER BY `c`.`description`
        ");
        
        return $query->result_array();
    }

    public function get_LUStorename($username)
    {
        $query = $this->db->query("
                SELECT `stores`.`store_name`
                FROM
                        `leasing_users`
                LEFT JOIN
                        `stores`
                ON
                        `leasing_users`.`user_group` = `stores`.`id`
                WHERE
                        `leasing_users`.`username` = '$username'
                ORDER BY
                        `first_name`");

        $ret = $query->row();
        return $ret->store_name;
    }

    public function get_SLdata($doc_no, $gl_accountID)
    {
        $query = $this->db->query(" SELECT * FROM subsidiary_ledger WHERE doc_no = '$doc_no' AND gl_accountID = '$gl_accountID';");

        $ret = $query->row();
        return $ret;
    }

    public function get_SLdata1($doc_no)
    {
        $query = $this->db->query(" SELECT * FROM subsidiary_ledger WHERE doc_no = '$doc_no' AND (tag = 'Other' OR tag = 'Penalty');");

        $ret = $query->row();
        return $ret;
    }

    public function managers_keyID($username, $password, $store_name)
    {
        $query = $this->db->query(
            "SELECT
                `u`.`id`,
                `u`.`user_type`,
                `u`.`user_group`,
                `s`.`store_name`
            FROM
                `leasing_users` `u`
            LEFT JOIN
                `stores` `s`
            ON
                `u`.user_group = `s`.`id`
            WHERE
                `u`.`username` = '$username'
            AND
                `u`.`password` = '" . MD5($password) . "'
            LIMIT 1
        ");


        $ret = $query->row();
        return $ret;
    }

    public function get_paymentData($Dref_no, $doc_no)
    {
        $query = $this->db->query("SELECT * 
                                    FROM 
                                        subsidiary_ledger 
                                    WHERE 
                                        doc_no = '$doc_no' 
                                    AND ref_no = '$Dref_no' 
                                    AND IFNULL(debit, 0) <> 0 
                                    AND document_type = 'Payment' LIMIT 1");
        return $query->result_array();
    }

    public function get_paymentData2($ref_no, $doc_no, $org_amount)
    {
        $query = $this->db->query("SELECT * 
                                    FROM 
                                        subsidiary_ledger 
                                    WHERE 
                                        doc_no = '$doc_no' 
                                    AND ref_no = '$ref_no' 
                                    AND IFNULL(credit, 0) <> 0 
                                    AND document_type = 'Payment' LIMIT 1");
        return $query->result_array();
    }

    public function get_adjustmentsData($tenant_id)
    {
        $query = $this->db->query(" SELECT
                                        adj_no,
                                        doc_no,
                                        tenant_id,
                                        doc_type,
                                        transaction_date,
                                        CONCAT(m.first_name, ' ', m.middle_name, ' ', m.last_name) as modified_by,
                                        CONCAT(a.first_name, ' ', a.middle_name, ' ', a.last_name) as approved_by,
                                        adj_document,
                                        SUM(debit) as debit,
                                        SUM(credit) as credit
                                    FROM
                                        adjustments
                                    LEFT JOIN 
                                        leasing_users m
                                    ON 
                                        adjustments.modified_by = m.id
                                    LEFT JOIN 
                                        leasing_users a
                                    ON 
                                        adjustments.managers_key = a.id
                                    WHERE 
                                        tenant_id = '$tenant_id' AND adj_document IS NOT NULL GROUP BY adj_no");

        return $query->result_array();
    }

    public function get_tenantName($prospect_id)
    {
        $query = $this->db->query("SELECT trade_name FROM prospect WHERE id = '$prospect_id'")->row()->trade_name;
        return $query;

    }

    public function get_pID($tenant_id)
    {
        $query = $this->db->query(
            "SELECT
                `prospect_id`
            FROM
                `tenants`
            WHERE
                `tenant_id` = '$tenant_id'
        ")->row()->prospect_id;

        return $query;
    }

    public function preparedBy($prepared_by)
    {   
        $query = $this->db->query(" SELECT CONCAT(leasing_users.first_name, ' ', leasing_users.middle_name, ' ', leasing_users.last_name) AS prepared_by
                                    FROM adjustments
                                    RIGHT JOIN leasing_users 
                                    ON adjustments.modified_by = leasing_users.id 
                                    WHERE adjustments.modified_by = '$prepared_by' GROUP BY prepared_by LIMIT 1");
        // return $query; ->row()->prepared_by

        $ret = $query->row();
        return $ret;
    }

    public function count_print_adj($document_name)
    {
        $query = $this->db->query("SELECT COUNT(document_name) AS print_no FROM adjustment_print_log WHERE document_name = '$document_name'")->row()->print_no;

        return $query;
    }

    public function get_adjustmentsData1($document_name)
    {
        $query = $this->db->query(" SELECT 
                                            adj_no, 
                                            tenant_id,
                                            posting_date, 
                                            transaction_date, 
                                            gl_accountID, 
                                            debit, 
                                            credit, 
                                            doc_no, 
                                            adj_reason, 
                                            CONCAT(m.first_name, ' ', m.middle_name, ' ', m.last_name) as modified_by, 
                                            CONCAT(a.first_name, ' ', a.middle_name, ' ', a.last_name) as approved_by, 
                                            adj_document,
                                            net_balance,
                                            prev_amount 
                                    FROM 
                                            adjustments 
                                    LEFT JOIN 
                                            leasing_users m ON adjustments.modified_by = m.id 
                                    LEFT JOIN 
                                            leasing_users a ON adjustments.managers_key = a.id 
                                    WHERE 
                                            adj_document = '$document_name' AND adj_document IS NOT NULL GROUP BY adj_no");

        $ret = $query->row();
        return $ret;
    }

    public function get_adjustmentsData2($adj_no)
    {
        $query = $this->db->query(" SELECT 
                                            adj_no, 
                                            tenant_id, 
                                            transaction_date, 
                                            gl_accountID, 
                                            debit, 
                                            credit, 
                                            doc_no, 
                                            adj_reason, 
                                            CONCAT(m.first_name, ' ', m.middle_name, ' ', m.last_name) as modified_by, 
                                            CONCAT(a.first_name, ' ', a.middle_name, ' ', a.last_name) as approved_by, 
                                            adj_document 
                                    FROM 
                                            adjustments 
                                    LEFT JOIN 
                                            leasing_users m ON adjustments.modified_by = m.id 
                                    LEFT JOIN 
                                            leasing_users a ON adjustments.managers_key = a.id 
                                    WHERE 
                                            adj_no = '$adj_no' AND adj_document IS NULL GROUP BY adj_no");

        $ret = $query->row();
        return $ret;
    }

// ==================================================================================== KING ARTHURS REVISION END =====================================================//



    public function get_preop_balance($tenant_id, $gl_account){

        switch ($gl_account) {
            case 'Security Deposit':
                $gl_account = 'Security Deposit Payable';
                break;
            default:
                $gl_account;
                break;
        }

        $query = $this->db->query("
            SELECT 
                a.gl_account,
                SUM(ifnull(sl.debit,0) + ifnull(sl.credit,0)) * -1  AS balance
            FROM 
                subsidiary_ledger  sl
            LEFT JOIN
                gl_accounts a
            ON 
                a.id = sl.gl_accountID
            WHERE 
                sl.tenant_id=? AND a.gl_account = ?
            AND 
                sl.document_type IN ('Payment', 'Payment Adjustment')
        ", [$tenant_id, $gl_account])->row();

        return $query;
    }

    public function get_preops_with_balance($tenant_id, $gl_account){

        switch ($gl_account) {
            case 'Security Deposit':
                $gl_account = 'Security Deposit Payable';
                break;
            default:
                $gl_account;
                break;
        }

        $result = $this->db->query("
            SELECT 
                sl.*,
                a.gl_account,
                SUM(ifnull(sl.debit,0) + ifnull(sl.credit,0)) * -1  AS balance
            FROM 
                subsidiary_ledger  sl
            LEFT JOIN
                gl_accounts a
            ON 
                a.id = sl.gl_accountID
            WHERE 
                sl.tenant_id = ? 
            AND 
                a.gl_account = ?
            AND 
                sl.document_type IN ('Payment', 'Payment Adjustment')
            GROUP BY
                sl.ref_no
            HAVING 
                balance > 0
            ORDER BY
                sl.posting_date ASC, id ASC
        ", [$tenant_id, $gl_account])->result_object();

        return $result;
    }

    public function getLastestSOA($tenant_id, $date_filter = null)
    {   
        $date_filter = empty($date_filter) ? '' : "AND posting_date <= '$date_filter'";

        $query = $this->db->query(
            "SELECT
                *
            FROM
                soa_file
            WHERE
                tenant_id =  '$tenant_id'
            $date_filter
            ORDER BY posting_date DESC, id DESC
            LIMIT 1
        ");

        return $query->row();
    }

    public function get_old_soa_invoices_with_balance($tenant_id, $date= null){
        $result = $this->db->query(
            "SELECT
                inv2.*,
                SUM(IFNULL(pmt.debit, 0) + IFNULL(pmt.credit, 0)) as pmt_amount,
                inv2.inv_amount + SUM(IFNULL(pmt.debit, 0) + IFNULL(pmt.credit, 0)) as balance
            FROM 
                (SELECT 
                    i.soa_no,
                    inv.*,
                    SUM(IFNULL(inv.debit, 0) + IFNULL(inv.credit, 0)) as inv_amount
                FROM
                    (SELECT 
                        s.soa_no,
                        i.doc_no,
                        i.tenant_id
                    FROM
                        soa s
                    LEFT JOIN
                        soa_file f
                    ON
                        s.soa_no = f.soa_no
                    LEFT JOIN
                        `invoicing` i
                    ON 
                        s.invoice_id = i.id AND s.tenant_id = i.tenant_id
                    WHERE
                        s.tenant_id = '$tenant_id'
                    AND 
                        f.posting_date <= '$date'
                    GROUP BY
                        i.doc_no) AS i
                LEFT JOIN
                    subsidiary_ledger inv
                ON
                    inv.doc_no = i.doc_no AND
                    inv.tenant_id = i.tenant_id AND
                    inv.document_type IN ('Invoice', 'Invoice Adjustment', 'Credit Memo', 'Debit Memo') AND
                    inv.gl_accountID IN (4, 22, 29)
                GROUP BY
                    inv.tenant_id, inv.ref_no, inv.gl_accountID
                ) AS inv2
            LEFT JOIN 
                subsidiary_ledger pmt
            ON
                inv2.tenant_id = pmt.tenant_id AND
                inv2.gl_accountID = pmt.gl_accountID AND
                pmt.document_type IN ('Payment', 'Payment Adjustment') AND
                inv2.ref_no = pmt.ref_no
            GROUP BY
                inv2.tenant_id, inv2.gl_accountID, inv2.ref_no
            HAVING
                ROUND(balance) >= 1
            ORDER BY
                inv2.posting_date DESC, inv2.id"
                )->result_object();


            /* ===  OPTION 2 QUERY

            "SELECT 
                inv2.*,
                SUM(IFNULL(pmt.debit, 0) + IFNULL(pmt.credit, 0)) AS pmt_amount,
                inv2.inv_amount + SUM(IFNULL(pmt.debit, 0) + IFNULL(pmt.credit, 0)) AS balance
            FROM
                (SELECT 
                    i.soa_no,
                        inv.*,
                        SUM(IFNULL(inv.debit, 0) + IFNULL(inv.credit, 0)) AS inv_amount
                FROM
                    (SELECT 
                        s.soa_no, 
                        i.doc_no, 
                        i.tenant_id
                    FROM
                        soa s
                    LEFT JOIN 
                        soa_file f 
                    ON 
                        s.soa_no = f.soa_no
                    LEFT JOIN 
                        `invoicing` i 
                    ON 
                        s.invoice_id = i.id 
                        AND s.tenant_id = i.tenant_id
                    WHERE
                        s.tenant_id = 'ACT-LT000065'
                    AND 
                        f.posting_date <= '2021-06-07'
                    GROUP BY 
                        i.doc_no , s.soa_no) AS i
                LEFT JOIN 
                    subsidiary_ledger inv 
                ON 
                    inv.doc_no = i.doc_no
                    AND inv.tenant_id = i.tenant_id
                    AND inv.document_type IN ('Invoice' , 'Invoice Adjustment', 'Credit Memo', 'Debit Memo')
                    AND inv.gl_accountID IN (4 , 22, 29)
                GROUP BY 
                    inv.tenant_id , inv.ref_no , inv.gl_accountID , i.soa_no) AS inv2
                LEFT JOIN
                    subsidiary_ledger pmt 
                ON 
                    inv2.tenant_id = pmt.tenant_id
                    AND inv2.gl_accountID = pmt.gl_accountID
                    AND pmt.document_type IN ('Payment' , 'Payment Adjustment')
                    AND inv2.ref_no = pmt.ref_no
            GROUP BY 
                inv2.tenant_id , inv2.gl_accountID , inv2.ref_no , inv2.soa_no
            HAVING 
                ROUND(balance) >= 1
            ORDER BY 
                inv2.posting_date DESC , inv2.id"*/

        return $result;
    }

    public function soa_no_exist_in_soa_line($soa_no, $tenant_id){
        $result = $this->db->query("
            SELECT
                *
            FROM
                soa_line
            WHERE
                soa_no = '$soa_no'
            AND
                tenant_id = '$tenant_id'
        ")->result_array();


        return count($result) > 0;
    }

    public function prepost_exist_in_soa_line($soa_no, $doc_no){
        $result = $this->db->query("
            SELECT
                *
            FROM
                soa_line
            WHERE
                soa_no = '$soa_no'
            AND
                doc_no = '$doc_no'
        ")->result_array();


        return count($result) > 0;
    }

    public function tenant_has_record_in_soa_line($tenant_id, $date = null){

        $result = $this->db->query("
            SELECT
                l.*
            FROM
                soa_line l
            LEFT JOIN
                soa_file f
            ON
                l.soa_no = f.soa_no
            WHERE
                l.tenant_id = '$tenant_id'
            AND 
                f.posting_date <= '$date'
            AND 
                l.soa_no IN (SELECT soa_no FROM soa_file WHERE tenant_id = '$tenant_id')
            ")->result_array();
        return count($result) > 0;
    }

    public function get_user_by_credentials($username, $password)
    {
        $Muser_type;
        $Muser_group;
        $Mstore_name;

        $query = $this->db->query(
            "SELECT
               *
            FROM
                `leasing_users`
            WHERE
                `username` = '$username'
            AND
                `password` = '" . MD5($password) . "'
            LIMIT 1
        ")->row();

        return $query;
    }

    public function get_gl_accounts(){
        $result = $this->db->query("
            SELECT 
                * 
            FROM 
                gl_accounts 
            WHERE 
                id 
            ORDER BY 
                gl_account")
        ->result_object();

        return $result;
    }

    public function get_data_recon_sys_vs_nav_report($store,  array $gl_ids, $date_from, $date_to){
        $gl_account_IDS = implode("','", $gl_ids);

        $result = $this->db->query("
            SELECT 
                tbl.*,
                IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 ) as debit,
                IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0) as credit,
                (IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 ) + IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0)) as amount
            FROM 
            (
                SELECT
                    g.id,
                    g.tenant_id,
                    g.posting_date,
                    g.due_date,
                    g.doc_no,
                    g.ref_no,
                    g.document_type,
                    p.trade_name,
                    g.gl_accountID,
                    a.gl_account,
                    g.bank_code,
                    g.bank_name,
                    (CASE 
                        WHEN (g.status != '' AND g.status IS NOT NULL) THEN CONCAT(a.gl_account, '-', g.status) 
                        WHEN (a.gl_account = 'Creditable WHT Receivable' AND g.tag != '' AND g.tag IS NOT NULL) THEN CONCAT(a.gl_account, '-', g.tag) 
                        ELSE a.gl_account 
                    END) AS description,
                    SUM(IFNULL(g.debit, 0)) debit,
                    SUM(IFNULL(g.credit, 0)) credit
                FROM 
                    subsidiary_ledger as g
                LEFT JOIN 
                    (SELECT distinct(tnt.prospect_id) as prospect_id, tnt.tenant_id, tnt.store_code  FROM tenants tnt) t on t.tenant_id = g.tenant_id
                LEFT JOIN 
                    prospect p on p.id = t.prospect_id
                LEFT JOIN 
                    gl_accounts a ON a.id = g.gl_accountID
                WHERE
                    date(g.posting_date) BETWEEN  date('$date_from') AND date('$date_to')
                AND 
                    g.gl_accountID IN ('$gl_account_IDS')
                AND 
                    t.store_code = '$store'
                AND 
                    g.document_type IN ('Invoice', 'Invoice Adjustment', 'Payment', 'Payment Adjustment') 
                AND
                    (g.tenant_id <> 'DELETED' AND g.ref_no <> 'DELETED' AND g.doc_no <> 'DELETED')
                AND 
                    g.tenant_id <> 'ICM-LT000064'

                GROUP BY
                    CASE 
                    WHEN g.document_type IN ('Invoice', 'Invoice Adjustment')
                    THEN  CONCAT(TRIM(g.doc_no),TRIM(g.ref_no),TRIM(g.gl_accountID),TRIM(g.tenant_id))
                    ELSE CONCAT(TRIM(g.gl_accountID), TRIM(g.posting_date), TRIM(g.doc_no),TRIM(g.tenant_id), TRIM(g.ref_no)) 
                    END
                ORDER BY 
                    g.document_type, g.doc_no
            ) 
            AS 
                tbl
            LEFT JOIN 
                (SELECT 
                    memo.ref_no,
                    memo.gl_accountID,
                    memo.tenant_id,
                    SUM(IFNULL(memo.debit, 0) + IFNULL(memo.credit, 0)) as amt
                FROM 
                    subsidiary_ledger as memo
                WHERE 
                    (memo.document_type = 'Credit Memo' OR memo.document_type = 'Debit Memo')
                AND
                    memo.tenant_id != 'DELETED'
                GROUP BY
                    memo.ref_no, memo.gl_accountID, memo.tenant_id
                ) AS m
            ON 
                (tbl.ref_no = m.ref_no AND tbl.gl_accountID = m.gl_accountID AND tbl.document_type = 'Invoice')
            /*WHERE 

                (IF(tbl.debit <> 0, tbl.debit + IFNULL(m.amt,0), 0 ) + IF(tbl.credit <> 0, tbl.credit + IFNULL(m.amt,0), 0)) <> 0
            GROUP BY 
                tbl.doc_no , tbl.ref_no , tbl.gl_accountID, tbl.tenant_id*/
            HAVING amount <> 0
            
            ORDER BY  
                tbl.posting_date, tbl.credit DESC, tbl.debit DESC, tbl.trade_name, tbl.ref_no, tbl.doc_no")->result_array();
        return $result;
    }

    public function get_banks(){

        $store = '';

        if($this->session->userdata('user_group') != 0){
            $store = $this->get_storeDetails();

            if(empty($store)){
                $store = '';
            }else{
                $store = $store[0]['store_code'];
            }
           
        }

        $result = $this->db->query("
            SELECT
                *
            FROM
                accredited_banks
            WHERE
                store_code LIKE '$store%'
            OR
                store_code IS NULL
            ORDER BY 
                bank_name ASC")
        ->result_object();

        return $result;
    }

    public function get_bank_by_id($bank_id){

        $result = $this->db->query("
            SELECT 
                * 
            FROM 
                accredited_banks 
            WHERE 
                id='$bank_id' 
            LIMIT 1
        ")->row();

        return $result;
    }

    public function get_data_recon_sys_vs_bank_report($bank, $date_from, $date_to){
        $result =   $this->db->query("
            SELECT
                g.id,
                g.tenant_id,
                g.posting_date,
                g.due_date,
                g.doc_no,
                g.ref_no,
                g.document_type,
                p.trade_name,
                g.gl_accountID,
                a.gl_account,
                g.bank_code,
                g.bank_name,
                (CASE
                    WHEN (g.status != '' AND g.status IS NOT NULL) THEN CONCAT(a.gl_account, '-', g.status)
                    WHEN (a.gl_account = 'Creditable WHT Receivable' AND g.tag != '' AND g.tag IS NOT NULL) THEN CONCAT(a.gl_account, '-', g.tag)
                    ELSE a.gl_account
                END) AS description,
                SUM(IFNULL(g.debit, 0)) debit,
                SUM(IFNULL(g.credit, 0)) credit,
                 SUM(IFNULL(g.debit, 0) + IFNULL(g.credit, 0)) amount
            FROM
                subsidiary_ledger as g
            LEFT JOIN
                (SELECT distinct(tnt.prospect_id) as prospect_id, tnt.tenant_id, tnt.store_code  FROM tenants tnt) t on t.tenant_id = g.tenant_id
            LEFT JOIN
                prospect p on p.id = t.prospect_id
            LEFT JOIN
                gl_accounts a ON a.id = g.gl_accountID
            WHERE
                date(g.posting_date) BETWEEN  date('$date_from') AND date('$date_to')
            AND
                g.gl_accountID = '3'
            AND
                t.store_code LIKE '$bank->store_code%'
            AND
                g.bank_code = '$bank->bank_code'
            AND
                g.document_type IN ('Payment', 'Payment Adjustment')
            AND
                (g.tenant_id <> 'DELETED' AND g.ref_no <> 'DELETED' AND g.doc_no <> 'DELETED')
            AND
                g.tenant_id <> 'ICM-LT000064'
            GROUP BY
                CONCAT(TRIM(g.gl_accountID), TRIM(g.posting_date), TRIM(g.doc_no),TRIM(g.tenant_id))
            ORDER BY
                g.document_type, g.posting_date, g.doc_no
        ")->result_object();

        return $result;
    }

    public function get_tmp_preop_invoice_with_balance($tenant_id, $date = null){

        $result = $this->db->query("
            SELECT 
                p.*
            FROM
                tmp_preoperationcharges p
            LEFT JOIN 
                soa_line s
            ON 
                s.tenant_id = p.tenant_id AND
                s.preop_id = p.id
            WHERE
                p.tenant_id = ? AND 
                ROUND(p.amount) >= 1 AND
                s.id IS NULL AND
                p.posting_date <= ?
        ", [$tenant_id, $date])->result_object();

        return $result;

    }

    public function get_adj_for_soa_display($tenant_id, $doc_no){
        $result = $this->db->query("
            SELECT 
                *,
                (SUM(IFNULL(debit, 0) + IFNULL(credit, 0)) * -1) as amount 
            FROM 
                adjustments
            WHERE
                tenant_id = ? AND 
                doc_no = ? AND
                gl_accountID NOT IN (22,29,4)
            GROUP BY gl_accountID
        ", [$tenant_id, $doc_no])->result_object();

        return $result;
    }

    public function get_invoice_override_data($tenant_id){
        $result = $this->db->query("
            SELECT 
                o.*,
                CONCAT(b.first_name, ' ', b.last_name) as override_by,
                CONCAT(m.first_name, ' ', m.last_name) as approved_by
            FROM
                invoice_override o
            LEFT JOIN
                leasing_users b
            ON
                b.id = o.override_by

            LEFT JOIN
                leasing_users m
            ON
                m.id = o.manager_id
            WHERE 
                o.tenant_id = ?
        ", [$tenant_id])->result_object();

        foreach ($result as $key => $res) {
            $result[$key]->supp_docs = $this->get_invoice_override_docs(['inv_over_id'=>$res->id]);
            $result[$key]->invoice_details =  $this->get_invoice_details(['tenant_id'=>$tenant_id, 'doc_no'=>$res->doc_no]);
        }

        return $result;
    }

    public function get_invoice_override_docs(array $cond){
        $result = $this->db
            ->select('*')
            ->from('invoice_override_docs')
            ->where($cond)
            ->get()
            ->result_object();

        return $result;
    }

    public function get_invoice_details(array $cond){
        $result = $this->db
            ->select('*')
            ->from('invoicing')
            ->where($cond)
            ->get()
            ->result_object();

        return $result;
    }

    public function getPaymentDetails($doc_no)
    {
        $result = $this->db->query("SELECT * FROM payment WHERE doc_no = '$doc_no' ORDER BY amount_paid DESC LIMIT 1");
        $ret = $result->row();
        return $ret;
    }

    public function getPaymetSchemeDetails($doc_no)
    {
        $result = $this->db->query("SELECT * FROM payment_scheme WHERE receipt_no = '$doc_no' ORDER BY amount_paid DESC LIMIT 1");
        $ret = $result->row();
        return $ret;
    }

} //end of Model

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leasing_archive extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('app_model');
        $this->load->library('upload');
        date_default_timezone_set("Asia/Manila");

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
        return date('Y-m-');
    }

    public function now()
    {
        return date('Y-m-d H:i:s');
    }


    public function managersKey()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $response[] = array();
            $username = $this->sanitize($this->input->post('username'));
            $password = $this->sanitize($this->input->post('password'));
            $store_name = $this->db->query("SELECT store_name FROM stores WHERE id = '" . $this->session->userdata('user_group') . "' LIMIT 1")->row()->store_name;

            if ($this->app_model->managers_key($username, $password, $store_name)) {
                $response['msg'] = "Correct";
            } else {
                $response['msg'] = "Incorrect";
            }

            echo json_encode($response);
        }
    }


    public function archive_lprospect()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/archive_lprospect');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing');
        }
    }

    public function get_deniedLprospect()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '0' || $this->session->userdata('user_group') == null)
            {
                $result = $this->app_model->get_deniedLprospect();
                echo json_encode($result);
            } else {
                $result = $this->app_model->store_deniedLprospect();
                echo json_encode($result);
            }
        }
    }

    public function restore_lprospect()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $store_name = str_replace("%20", " ", $this->uri->segment(4));
            $username = $this->sanitize($this->input->post('username'));
            $password = $this->sanitize($this->input->post('password'));

            $data = array('status'  =>  'Pending');

            if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager")
            {
                if ($this->app_model->managers_key($username, $password, $store_name)) {

                    if ($this->app_model->update($data, $id, 'prospect')) {

                        $this->session->set_flashdata('message', 'Restored');
                    }
                    redirect('leasing_archive/archive_lprospect');

                } else {
                    $this->session->set_flashdata('message', 'Invalid Key');
                    redirect('leasing_archive/archive_lprospect');
                }

            } else {
                if ($this->app_model->update($data, $id, 'prospect')) {
                    $this->session->set_flashdata('message', 'Restored');
                }
                redirect('leasing_archive/archive_lprospect');
            }
        } else {
            redirect('ctrl_leasing');
        }
    }



    public function delete_lprospect()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $store_name = str_replace("%20", " ", $this->uri->segment(4));
            $username = $this->sanitize($this->input->post('username'));
            $password = $this->sanitize($this->input->post('password'));


            if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager")
            {
                if ($this->app_model->managers_key($username, $password, $store_name)) {

                    if ($this->app_model->delete('lprospect', 'id', $id)) {

                        $this->session->set_flashdata('message', 'Deleted');

                            // save action to logs

                        $logs = array(
                            'action'    => 'Long Term Prospect ID of ' . $id . ' was deleted',
                            'user_id'   => $this->session->userdata('id'),
                            'date'      => $this->now()
                        );

                        $this->app_model->insert('logs', $logs);
                    }
                    redirect('leasing_archive/archive_lprospect');

                } else {
                    $this->session->set_flashdata('message', 'Invalid Key');
                    redirect('leasing_archive/archive_lprospect');
                }

            } else {
                if ($this->app_model->update('lprospect', 'id', $id)) {
                    $this->session->set_flashdata('message', 'Deleted');
                            // save action to logs

                    $logs = array(
                        'action'    =>  'Long Term Prospect ID of ' . $id . ' was deleted',
                        'user_id'   =>  $this->session->userdata('id'),
                        'date'      =>  $this->now()
                    );

                    $this->app_model->insert('logs', $logs);
                }
                redirect('leasing_archive/archive_lprospect');
            }
        } else {
            redirect('ctrl_leasing');
        }
    }


    public function archive_sprospect()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/archive_sprospect');
            $this->load->view('leasing/footer');

        } else {
            redirect('ctrl_leasing');
        }
    }


    public function get_deniedSprospect()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_group') == '0' || $this->session->userdata('user_group') == null)
            {
                $result = $this->app_model->get_deniedSprospect();
                echo json_encode($result);
            } else {
                $result = $this->app_model->store_deniedSprospect();
                echo json_encode($result);
            }
        }
    }


    public function restore_sprospect()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $store_name = str_replace("%20", " ", $this->uri->segment(4));
            $username = $this->sanitize($this->input->post('username'));
            $password = $this->sanitize($this->input->post('password'));

            $data = array('status'  =>  'Pending');

            if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager")
            {
                if ($this->app_model->managers_key($username, $password, $store_name)) {

                    if ($this->app_model->update($data, $id, 'prospect')) {

                        $this->session->set_flashdata('message', 'Restored');
                    }
                    redirect('leasing_archive/archive_sprospect');

                } else {
                    $this->session->set_flashdata('message', 'Invalid Key');
                    redirect('leasing_archive/archive_sprospect');
                }

            } else {
                if ($this->app_model->update($data, $id, 'prospect')) {
                    $this->session->set_flashdata('message', 'Restored');
                }
                redirect('leasing_archive/archive_sprospect');
            }
        } else {
            redirect('ctrl_leasing');
        }
    }


    public function delete_sprospect()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $id = $this->uri->segment(3);
            $store_name = str_replace("%20", " ", $this->uri->segment(4));
            $username = $this->sanitize($this->input->post('username'));
            $password = $this->sanitize($this->input->post('password'));

            if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager")
            {
                if ($this->app_model->managers_key($username, $password, $store_name)) {

                    if ($this->app_model->delete('sprospect', 'id', $id)) {

                        $this->session->set_flashdata('message', 'Deleted');

                            // save action to logs

                        $logs = array(
                            'action'    => 'Short Term Prospect ID of ' . $id . ' was deleted',
                            'user_id'   => $this->session->userdata('id'),
                            'date'      => $this->now()
                        );

                        $this->app_model->insert('logs', $logs);
                    }
                    redirect('leasing_archive/archive_sprospect');

                } else {
                    $this->session->set_flashdata('message', 'Invalid Key');
                    redirect('leasing_archive/archive_sprospect');
                }

            } else {
                if ($this->app_model->update('sprospect', 'id', $id)) {
                    $this->session->set_flashdata('message', 'Deleted');
                            // save action to logs

                    $logs = array(
                        'action'    =>  'Short Term Prospect ID of ' . $id . ' was deleted',
                        'user_id'   =>  $this->session->userdata('id'),
                        'date'      =>  $this->now()
                    );

                    $this->app_model->insert('logs', $logs);
                }
                redirect('leasing_archive/archive_sprospect');
            }
        } else {
            redirect('ctrl_leasing');
        }
    }

    public function logs()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Administrator') {
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $data['flashdata'] = $this->session->flashdata('message');
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/logs');
                $this->load->view('leasing/footer');
            } else {
                echo "User Type Restriction.";
            }
        } else {
            redirect('ctrl_leasing');
        }
    }

    public function get_logs()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_logs();
            echo json_encode($result);
        }
    }


    public function delete_logs()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $box = $this->input->post('checkbox');
            if ($this->app_model->delete_logs($box))
            {
                $this->session->set_flashdata('message', 'Deleted');
            }

            redirect('leasing_archive/logs');
        } else {
            redirect('ctrl_leasing');
        }
    }




}

/* End of file welcome.php */
/* Location: ./application/controllers/Leasing_archive.php */

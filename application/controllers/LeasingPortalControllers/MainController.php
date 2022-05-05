<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MainController extends CI_Controller
{
    public function index()
    {
        $this->load->view('leasingportal/Login');
    }

    public function home()
    {
        $data['title'] = 'My SOA';
        $data['status'] = 'soa';
        $this->load->view('leasingportal/Header', $data);
        $this->load->view('leasingportal/MySoa');
        $this->load->view('leasingportal/Footer');
    }

    public function mysoa()
    {
        $data['title'] = 'My SOA';
        $data['status'] = 'soa';
        $this->load->view('leasingportal/Header', $data);
        $this->load->view('leasingportal/MySoa');
        $this->load->view('leasingportal/Footer');
    }

    public function myledger()
    {
        $data['title'] = 'My Ledger';
        $data['status'] = 'ledger';
        $this->load->view('leasingportal/Header', $data);
        $this->load->view('leasingportal/MyLedger');
        $this->load->view('leasingportal/Footer');
    }

    public function utilityreadings()
    {
        $data['title'] = 'Utility Readings';
        $data['status'] = 'readings';
        $this->load->view('leasingportal/Header', $data);
        $this->load->view('leasingportal/UtilityReadings');
        $this->load->view('leasingportal/Footer');
    }

    public function paymentadvice()
    {
        $data['title'] = 'Payment Advice';
        $data['status'] = 'advice';
        $this->load->view('leasingportal/Header', $data);
        $this->load->view('leasingportal/PaymentAdvice');
        $this->load->view('leasingportal/Footer');
    }
}

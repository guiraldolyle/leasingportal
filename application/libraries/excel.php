<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php";
class Excel extends PHPExcel {
    public function __construct()
    {
        parent::__construct();
    }

    function to_excel($array, $filename)
    {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');

         //Filter all keys, they'll be table headers
        $h = array();
        foreach($array->result_array() as $row)
        {
            foreach($row as $key=>$val)
            {
                if(!in_array($key, $h))
                {
                 	$h[] = $key;
                }
            }
        }
        //echo the entire table headers
        echo '<table><tr>';
        foreach($h as $key)
        {
            $key = ucwords($key);
            echo '<th>'.$key.'</th>';
        }
        echo '</tr>';

        foreach($array->result_array() as $row)
        {
            echo '<tr>';
            foreach($row as $val)
                $this->writeRow($val);
        }
        echo '</tr>';
        echo '</table>';
    }

    function to_exceltenantledger($array, $filename)
    {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');

         //Filter all keys, they'll be table headers
        $h = array();
        foreach($array as $row)
        {
            foreach($row as $key=>$val)
            {
                if(!in_array($key, $h))
                {
                 	$h[] = $key;
                }
            }
        }
        //echo the entire table headers
        echo '<table><tr>';
        foreach($h as $key)
        {
            $key = ucwords($key);
            echo '<th>'.$key.'</th>';
        }
        echo '</tr>';

        foreach($array as $row)
        {
            echo '<tr>';
            foreach($row as $val)
                $this->writeRow($val);
        }
        echo '</tr>';
        echo '</table>';
    }


    function writeRow($val)
    {
        echo '<td>'.utf8_decode($val).'</td>';
    }

    function writeMonetary($val)
    {
        if ($val == '') {
            $val = 0;
        }
        echo '<td align="right">'.number_format(utf8_decode($val), 2).'</td>';
    }



    function generate_monthly_report_glentry($gl_accounts, $tenants, $reportData, $filename)
    {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');

        echo '<table><tr>';

        foreach ($tenants as $tenant)
        {
            echo '<th>'.ucwords($tenant['trade_name']).'</th>';
            echo '</tr>';

            foreach($gl_accounts as $row)
            {
                echo '<tr>';
                    $this->writeRow($row['gl_account']);
                    foreach ($reportData as $data)
                    {
                        if ($tenant['tenant_id'] == $data['tenant_id'] && $data['gl_accountID'] == $row['id'])
                        {
                            $this->writeMonetary($data['amount']);
                        }
                    }
                echo '</tr>';
            }

            echo '<tr></tr>';
        }

        echo '</table>';
    }


    public function generate_preop_summary($reportData, $filename, $month)
    {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');

        $header = ['Tenant ID', 'Trade name', 'Description', 'Amount'];
        echo "<h3>Monthly Preop Charges Summary for the month of " . strtoupper($month) . "<h3><br>";
        echo '<table border = "0"><tr>';
            for ($i=0; $i < count($header); $i++)
            {
                echo '<th>'.ucwords($header[$i]).'</th>';
            }
        echo '</tr>';

        $total = 0;
        foreach ($reportData as $value)
        {
            echo '<tr>';
            $this->writeRow($value['tenant_id']);
            $this->writeRow($value['trade_name']);
            $this->writeRow($value['description']);
            $this->writeMonetary($value['amount']);
            echo '</tr>';

            $total += $value['amount'];
        }
        echo "<tr>";
            echo "<td><b>Total</b></td>";
            echo "<td></td><td></td>";
            echo $this->writeMonetary($total);
        echo "</tr>";
        echo '</table>';
    }


    public function generate_monthly_receivable_summary($reportData, $ar_total, $rr_total, $filename, $month)
    {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');

        $header = ['Tenant ID', 'Trade name', 'Amount'];
        echo "<h3>Monthly Receivable Summary for the month of " . strtoupper($month) . "<h3><br>";
        echo '<table border = "0"><tr>';
            for ($i=0; $i < count($header); $i++)
            {
                echo '<th>'.ucwords($header[$i]).'</th>';
            }
        echo '</tr>';

        $total = 0;
        foreach ($reportData as $value)
        {
            echo '<tr>';
            $this->writeRow($value['tenant_id']);
            $this->writeRow($value['trade_name']);
            $this->writeMonetary($value['amount']);
            echo '</tr>';

            $total += $value['amount'];
        }

        echo "<tr></tr>";
        echo "<tr>";
            echo "<td><b>Rent Receivable</b></td>";
            echo "<td></td>";
            echo $this->writeMonetary($rr_total);
        echo "</tr>";
        echo "<tr>";
            echo "<td><b>Account Receivable</b></td>";
            echo "<td></td>";
            echo $this->writeMonetary($ar_total);
        echo "</tr>";
        echo "<tr>";
            echo "<td><b>Total</b></td>";
            echo "<td></td>";
            echo $this->writeMonetary($total);
        echo "</tr>";
        echo '</table>';
    }


    function generate_ar_ar_summary($total_due, $previous, $current, $amount_paid, $month, $filename, $tenant_type)
    {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');


        $tenant_count = 0;
        $total_ar = 0;


        foreach ($total_due as $data)
        {
            $tenant_count++;
            $total_ar += $data['current_amount'];
        }


        $header = ['SOA No.', 'TENANT NAME', 'PREVIOUS', 'PAYMENT', 'FOR THE MONTH', 'TOTAL AMOUNT DUE FOR ' . strtoupper($month) . ' ' . date('Y')];
        echo '<table border = "1">';
        echo '<tr>';
            echo '<th>'.strtoupper('TOTAL ACTIVE ' . $tenant_type . ' TENANTS AS OF ' . $month).'</th>';
            echo '<th style = "font-size:20px; background-color:cyan; color:red">'.ucwords($tenant_count).'</th>';
            echo '<th></th>';
            echo '<th></th>';
            echo '<th>'.ucwords('TOTAL AR').'</th>';
            echo '<th align="right" style = "font-size:20px; background-color:cyan; color:red">'.number_format($total_ar, 2).'</th>';
        echo '<tr>';

        for ($i=0; $i < count($header); $i++)
        {
            echo '<th>'.ucwords($header[$i]).'</th>';
        }

        echo '</tr>';


        foreach ($total_due as $total)
        {
            echo '<tr>';

            $this->writeRow($total['soa_no']);
            $this->writeRow($total['trade_name']);

            echo '<td align="right">';
            foreach ($previous as $prev)
            {

                if ($total['tenant_id'] == $prev['tenant_id'])
                {
                    echo number_format($prev['current_amount'], 2);
                }

            }
            echo "</td>";

            echo '<td align="right">';
            foreach ($amount_paid as $amount)
            {

                if ($total['tenant_id'] == $amount['tenant_id'])
                {
                    echo number_format($amount['amount_paid'], 2);
                }

            }
            echo "</td>";

            echo '<td align="right">';
            foreach ($current as $curr)
            {

                if ($total['tenant_id'] == $curr['tenant_id'])
                {
                    echo number_format($curr['amount'], 2);
                }

            }
            echo "</td>";


            $this->writeMonetary($total['current_amount']);

            echo '</tr>';
        }

        echo '</table>';

    }

    public function generate_monthly_payable($reportData, $month, $tenants, $filename)
    {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');


        $header = ['TENAN ID.', 'TENANT NAME', 'AMOUNT DUE FOR ' . strtoupper($month), 'AMOUNT PAID', 'POSTING DATE', 'TRANSACTION  DATE', 'DUE DATE'];

        echo '<table border = "1"><tr>';

        for ($i=0; $i < count($header); $i++)
        {
            echo '<th>'.ucwords($header[$i]).'</th>';
        }
        echo "</tr>";

        foreach ($tenants as $tenant)
        {
            $amount_due  = 0;
            $amount_paid = 0;
            $posted = '';
            $transact = '';
            $due      = '';

     
            foreach ($reportData as $value)
            {
                if ($tenant['tenant_id'] == $value['tenant_id'])
                {
                    $amount_due  += $value['amount'];
                    $amount_paid += $value['amount_paid'];
                    $posted = $value['posting_date'];
                    $transact = $value['transaction_date'];
                    $due  =  $value['due_date'];


                }
            }

            if ($amount_due > 0)
            {
                echo "<tr>";
                $this->writeRow($tenant['tenant_id']);
                $this->writeRow($tenant['trade_name']);
                $this->writeMonetary($amount_due);
                $this->writeMonetary($amount_paid);
                $this->writeRow($value['posting_date']);
                $this->writeRow($value['transaction_date']);
                $this->writeRow($value['due_date']);
                echo "</tr>";
            }
        }
        echo "</table>";
    }



    public function generate_forBankRecon($reportData, $filename)
    {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');


        $tenant_count = 0;
        $total_ar = 0;

        $header = ['Payor', 'Description', 'Amount', 'OR #', 'Check No', 'Check Date'];
        echo '<table border = "1"><tr>';

        for ($i=0; $i < count($header); $i++)
        {
            echo '<th>'.ucwords($header[$i]).'</th>';
        }
        echo '</tr>';

        foreach ($reportData as $value)
        {
            echo '<tr>';
            $this->writeRow($value['payor']);
            $this->writeRow($value['tender_typeDesc']);
            $this->writeMonetary($value['amount_paid']);
            $this->writeRow($value['receipt_no']);
            $this->writeRow($value['check_no']);
            $this->writeRow($value['check_date']);
            echo '</tr>';
        }

        echo '</table>';
    }


    public function generate_receiptsAudit($reportData, $storename, $month, $filename)
    {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');

        echo "<b>" . strtoupper($storename)  . "</b><br>";
        echo "<b>LEASING DEPARTMENT</b><br>";
        echo "<b>'" . strtoupper($month)  . "</b><br>";
        echo "<table border = '1'><tr>";
        echo "<th></th><th></th><th></th><th></th><th></th><th></th>";
        echo "<th colspan = '4'>AMOUNT</th>";
        echo "<th></th><th></th><th></th><th></th></tr><tr>";
        echo "<th>OR#</th>";
        echo "<th>OR DATE</th>";
        echo "<th>PAYOR</th>";
        echo "<th>PARTICULARS BILLING PERIOD</th>";
        echo "<th align = 'center'>TYPE OF PAYMENT</th>";
        echo "<th>CHECK DATE</th>";
        echo "<th>CASH</th>";
        echo "<th>CHECK</th>";
        echo "<th>OTC/ONLINE</th>";
        echo "<th>JV</th>";
        echo "<th>AMOUNT</th>";
        echo "<th>VARIANCE</th>";
        echo "<th>VDS #</th>";
        echo "<th>DEPOSIT DATE</th>";

        echo "</tr>";

        foreach ($reportData as $value)
        {
            echo '<tr>';
            $this->writeRow($value['receipt_no']);
            $this->writeRow($value['posting_date']);
            $this->writeRow($value['payor']);
            $this->writeRow($value['billing_period']);

            if ($value['tender_type'] == 'Check')
            {
                $this->writeRow($value['check_no']);
            }
            else
            {
                $this->writeRow($value['tender_type']);
            }

            if ($value['check_date'] == '0000-00-00')
            {
                $this->writeRow("");
            }
            else
            {
                $this->writeRow($value['check_date']);
            }

            if ($value['tender_type'] == 'Cash')
            {
                $this->writeMonetary($value['amount_paid']);
            }
            else
            {
                $this->writeRow("");
            }


            if ($value['tender_type'] == 'Check')
            {
                $this->writeMonetary($value['amount_paid']);
            }
            else
            {
                $this->writeRow("");
            }

            if ($value['tender_type'] == 'Bank to Bank')
            {
                $this->writeMonetary($value['amount_paid']);
            }
            else
            {
                $this->writeRow("");
            }


            if ($value['tender_type'] == 'JV payment - Business Unit' || $value['tender_type'] == 'JV payment - Subsidiary')
            {
                $this->writeMonetary($value['amount_paid']);
            }
            else
            {
                $this->writeRow("");
            }


            $this->writeMonetary($value['amount_paid']);
            $this->writeRow("");
            $this->writeRow($value['vds_no']);
            $this->writeRow("");


            echo "</tr>";
        }



    }
}

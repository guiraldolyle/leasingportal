<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Portal_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->_user_group = $this->session->userdata('user_group');
        $this->_user_id    = $this->session->userdata('id');
    }

    public function check_login($username, $password)
    {
        $userType = '';
        $query1 = $this->db->query("SELECT * FROM tenant_users WHERE password = '" . MD5($password) . "' AND username = '" . $username . "'")->RESULT_ARRAY();

        foreach ($query1 as $value) {
            $userType = $value['user_type'];
        }

        if ($userType == 'Admin') {
            $query = $this->db->query("SELECT * FROM tenant_users WHERE password = '" . MD5($password) . "' AND username = '$username'");

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $rows) {
                    //add all data to session
                    $data = array(
                        'id'                => $rows->id,
                        'username'          => $rows->username,
                        'password'          => $rows->password,
                        'user_type'         => $rows->user_type,
                        'tenant_id'         => $rows->tenant_id,
                        'portal_logged_in' =>  TRUE
                    );
                    $this->session->set_userdata($data);
                }

                $session = (object)$this->session->userdata;

                $user_session_data = [
                    'user_id'    => isset($session->id)         ? $session->id         : '',
                    'session_id' => isset($session->session_id) ? $session->session_id : '',
                    'ip_address' => isset($session->ip_address) ? $session->ip_address : '',
                    'user_agent' => isset($session->user_agent) ? $session->user_agent : '',
                    'user_data'  => json_encode($session),
                    'login_in'   => 'portal_leasing'
                ];

                $this->db->insert('user_session', $user_session_data);

                return true;
            }
        } else {

            $query = $this->db->query("SELECT tu.*, t.tenancy_type, p.trade_name, t.status 
                                               FROM tenant_users tu
                                               LEFT JOIN tenants t ON tu.tenant_id = t.tenant_id
                                               LEFT JOIN prospect p ON t.prospect_id = p.id
                                               WHERE t.status = 'ACTIVE' 
                                               AND tu.password = '" . MD5($password) . "' AND tu.username = '" . $username . "'");

            // echo $this->db->last_query();
            // var_dump($query->RESULT_ARRAY());
            // exit();

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $rows) {
                    //add all data to session
                    $data = array(
                        'id'                => $rows->id,
                        'username'          => $rows->username,
                        'password'          => $rows->password,
                        'user_type'         => $rows->user_type,
                        'tenant_id'         => $rows->tenant_id,
                        'tenancy_type'      => $rows->tenancy_type,
                        'trade_name'        => $rows->trade_name,
                        'status'            => $rows->status,
                        'portal_logged_in' =>  TRUE
                    );
                    $this->session->set_userdata($data);
                }

                $session = (object)$this->session->userdata;

                $user_session_data = [
                    'user_id'    => isset($session->id)         ? $session->id         : '',
                    'session_id' => isset($session->session_id) ? $session->session_id : '',
                    'ip_address' => isset($session->ip_address) ? $session->ip_address : '',
                    'user_agent' => isset($session->user_agent) ? $session->user_agent : '',
                    'user_data'  => json_encode($session),
                    'login_in'   => 'portal_leasing'
                ];

                $this->db->insert('user_session', $user_session_data);

                return true;
            }
        }

        return false;
    }

    public function getReading($tenant_id, $dateStart, $dateEnd)
    {
        $result = $this->db->QUERY("SELECT * 
                                    FROM invoicing 
                                    WHERE tenant_id = '$tenant_id' 
                                    AND charges_type = 'Other'
                                    AND charges_code IN ('PC000002', 'PC000007', 'PC000005')  
                                    AND posting_date
                                    BETWEEN '$dateStart' 
                                    AND '$dateEnd'")->RESULT_ARRAY();

        return $result;
    }

    # ADMIN PORTAL MODEL

    public function get_soa($tenant_type, $start, $end)
    {
        // $result = $this->db->QUERY("SELECT s.* FROM soa_file s 
        //                             WHERE s.upload_status <> 'Uploaded'
        //                             AND s.tenant_id LIKE '%$tenant_type%'
        //                             AND s.posting_date BETWEEN '$start' AND '$end'")->RESULT_ARRAY();

        $result = $this->db->QUERY("SELECT 
                                    p.trade_name, 
                                    s.* 
                                    FROM soa_file s 
                                    LEFT JOIN tenants t ON s.tenant_id = t.tenant_id
                                    LEFT JOIN prospect p ON t.prospect_id = p.id
                                    WHERE s.upload_status <> 'Uploaded' 
                                    AND s.tenant_id LIKE '%$tenant_type%' 
                                    AND s.posting_date BETWEEN '$start' AND '$end'
                                    GROUP BY s.soa_no")->RESULT_ARRAY();

        // echo $this->db->last_query();
        return $result;
    }

    public function getPayments($tenant_id)
    {
        $result = $this->db->QUERY("SELECT * FROM payment_scheme 
                                    WHERE tenant_id = '$tenant_id' 
                                    AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getPaymentsPerstore($tenant_id, $start, $end)
    {
        $result = $this->db->QUERY("SELECT p.posting_date, pp.trade_name, ps.*
                                    FROM payment p
                                    LEFT JOIN payment_scheme ps ON p.doc_no = ps.receipt_no
                                    LEFT JOIN tenants t ON ps.tenant_id = t.tenant_id
                                    LEFT JOIN prospect pp ON t.prospect_id = pp.id
                                    WHERE p.tenant_id LIKE '%$tenant_id%'
                                    AND p.upload_status <> 'Uploaded'
                                    AND p.doc_no NOT LIKE '%UFT%' 
                                    AND p.posting_date BETWEEN '$start' AND '$end'
                                    GROUP BY ps.receipt_no")->RESULT_ARRAY();
        return $result;
    }

    public function search_tradeName()
    {
        $this->db->select("CONCAT(('['), (t.tenant_id), (']'), (' - ') , (p.trade_name)) AS value, t.tenant_id as id");
        $this->db->from('prospect AS p');
        $this->db->join('tenants AS t', 'p.id = t.prospect_id AND t.status = ' . "'Active'" . ' AND t.flag = ' . "'Posted'" . '');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getDataSoa($soaID)
    {
        $result = $this->db->QUERY("SELECT s.* FROM soa_file s 
                                    WHERE s.upload_status <> 'Uploaded'
                                    AND s.id = '$soaID'")->ROW();
        return $result;
    }

    public function getSOAFile($id)
    {
        $result = $this->db->SELECT('*')
            ->FROM('soa_file')
            ->WHERE('id', $id)
            ->GET()
            ->ROW();
        return $result;
    }

    public function getSOALine($soa_no)
    {
        $result = $this->db->SELECT('*')
            ->FROM('soa_line')
            ->WHERE('soa_no', $soa_no)
            ->GET()
            ->RESULT_ARRAY();
        return $result;
    }

    public function getTenant($tenant_id)
    {
        $result = $this->db->SELECT('p.*')
            ->FROM('prospect p')
            ->JOIN('tenants t', 'p.id = t.prospect_id', 'LEFT')
            ->WHERE('t.tenant_id', $tenant_id)
            ->GET()
            ->ROW();
        return $result;
    }

    public function getTenantUsers()
    {
    }

    public function getPreviousBalance($postingdate, $tenant_id)
    {
        $result = $this->db->SELECT('*')
            ->FROM('soa_file')
            ->WHERE('posting_date < ', $postingdate)
            ->WHERE('tenant_id', $tenant_id)
            ->ORDER_BY('posting_date', 'DESC')
            ->LIMIT(1)
            ->GET()
            ->ROW();
        return $result;
    }

    public function getTenants()
    {
        $result = $this->db->query("SELECT * FROM tenants WHERE status = 'Active'")->RESULT_ARRAY();
        return $result;
    }

    public function getTenants2($tenant_id)
    {
        $result = $this->db->query("SELECT t.id, t.tenant_id, p.trade_name FROM tenants t 
                                    LEFT JOIN prospect p ON t.prospect_id = p.id WHERE t.status = 'Active' AND t.tenant_id LIKE '%" . $tenant_id . "%' ORDER BY p.trade_name")->RESULT_ARRAY();
        return $result;
    }

    public function checkUserDuplicate($tenant_id)
    {
        $result = $this->db->query("SELECT * FROM tenant_users WHERE tenant_id = '$tenant_id'")->ROW();
        return $result;
    }

    public function getUsers()
    {
        $result = $this->db->query("SELECT * FROM tenant_users WHERE status = 'Active'")->RESULT_ARRAY();
        return $result;
    }

    public function getInvoices1($table, $startDate, $endDate)
    {
        $result = $this->db->query("SELECT * FROM $table 
                                    WHERE document_type IN ('Invoice', 'Invoice Adjustment')
                                    AND posting_date BETWEEN '$startDate' AND '$endDate' 
                                    AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getInvoices2($startDate, $endDate, $store, $tenantType)
    {
        $result = $this->db->query("SELECT * FROM subsidiary_ledger 
                                    WHERE document_type IN ('Invoice', 'Invoice Adjustment') 
                                    AND tenant_id LIKE '%ICM-LT%' 
                                    AND posting_date BETWEEN '$startDate' AND '$endDate'
                                    AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getInvoices3($startDate, $endDate)
    {
        $result = $this->db->query("SELECT * FROM ledger 
                                    WHERE document_type IN ('Invoice', 'Penalty', 'Debit Memo', 'Waive Penalty', 'Credit Memo') 
                                    AND posting_date BETWEEN '$startDate' AND '$endDate'
                                    AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getInvoices4($startDate, $endDate)
    {
        $result = $this->db->query("SELECT * FROM invoicing WHERE posting_date BETWEEN '$startDate' AND '$endDate' AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getPaymentScheme($id)
    {
        $result = $this->db->query("SELECT * FROM payment_scheme WHERE id = '$id' AND upload_status <> 'Uploaded'")->ROW();
        return $result;
    }

    public function getSL($doc_no)
    {
        $result = $this->db->query("SELECT distinct ref_no FROM subsidiary_ledger WHERE doc_no = '$doc_no' AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getLedgers($ref_no, $table)
    {
        $result = $this->db->query("SELECT * FROM $table WHERE ref_no = '$ref_no' AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getLedgerTable($doc_no)
    {
        $result = $this->db->query("SELECT * FROM ledger WHERE doc_no = '$doc_no' AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getPaymentTable($doc_no)
    {
        $result = $this->db->query("SELECT * FROM payment WHERE doc_no = '$doc_no' AND upload_status <> 'Uploaded'")->ROW();
        return $result;
    }

    public function getBankAccount($store)
    {
        $query = $this->db->query("SELECT distinct bank_name FROM accredited_banks WHERE store_code = '$store'")->RESULT_ARRAY();
        return $query;
    }

    public function getSoaWithBalances($tenant_id)
    {
        $query = $this->db->query("SELECT
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
                                        (`tag` = '' || `tag` = 'Posted')
                                    AND 
                                        amount >= 1
                                    ORDER BY
                                        posting_date ASC, id ASC)
                                AS p
                                ON s.preop_id = p.id AND s.tenant_id = p.tenant_id
                                LEFT JOIN payment_advice_soa pa ON s.soa_no = pa.soa_no 

                                WHERE 
                                    s.tenant_id = '$tenant_id'
                                AND s.soa_no NOT IN (SELECT soa_no FROM payment_advice_soa)
                                GROUP BY
                                    s.soa_no, s.tenant_id
                                HAVING 
                                    balance >= 1
                                ORDER BY f.posting_date DESC, s.id DESC")->RESULT_ARRAY();

        return $query;
    }

    public function getSoa($soa_no)
    {
        $query = $this->db->query("SELECT SUM(amount) as amount FROM soa_line WHERE soa_no = '$soa_no'")->ROW();
        return $query;
    }

    public function getNotices()
    {
        $query = $this->db->query("SELECT * FROM payment_advice WHERE status <> 'Posted'")->RESULT_ARRAY();
        return $query;
    }

    public function getAdvices($id)
    {
        $query = $this->db->query("SELECT p.*, pa.soa_no, pa.tenant_id, pa.total_payable 
                                   FROM payment_advice p
                                   LEFT JOIN payment_advice_soa pa ON p.id = pa.payment_advice_id
                                   WHERE p.id = '$id'
                                   AND p.status <> 'Posted'")->ROW();
        return $query;
    }

    public function getTmpCharges()
    {
        $query = $this->db->query("SELECT * FROM tmp_preoperationcharges WHERE upload_status <> 'Uploaded'")->ROW();
        return $query;
    }

    // - - PAYMENT ADMIN QUERY

    public function getSoaForPayment($soa_no)
    {
        $query = $this->db->query("SELECT * FROM soa_file WHERE soa_no = '$soa_no'")->ROW();
        return $query;
    }

    # FOR PORTAL


    # INVOICE PER TENANT

    public function getDataV1($tenant_id, $startDate, $endDate, $table)
    {
        $result = $this->db->SELECT('*')
            ->FROM($table)
            ->WHERE('tenant_id', $tenant_id)
            ->WHERE("document_type = 'Invoice'")
            ->WHERE("upload_status <> 'Uploaded'")
            ->WHERE("posting_date BETWEEN '" . $startDate . "' AND '" . $endDate . "'")
            ->GET()
            ->RESULT_ARRAY();

        return $result;
    }

    public function getDataV2($tenant_id, $startDate, $endDate, $table)
    {
        $result = $this->db->SELECT('*')
            ->FROM($table)
            ->WHERE('tenant_id', $tenant_id)
            ->WHERE("upload_status <> 'Uploaded'")
            ->WHERE("posting_date BETWEEN '" . $startDate . "' AND '" . $endDate . "'")
            ->GET()
            ->RESULT_ARRAY();

        return $result;
    }

    public function getHistory()
    {
        $result = $this->db->QUERY("SELECT l.*, u.username FROM upload_log l LEFT JOIN tenant_users u ON l.user_id = u.id")->RESULT_ARRAY();
        return $result;
    }

    public function getPaymentAdvice($paymentadviceid)
    {
        $result = $this->db->QUERY("SELECT * FROM payment_advice WHERE id = '$paymentadviceid'")->ROW();
        return $result;
    }

    public function getPaymentAdviceSoa($paymentadviceid)
    {
        $result = $this->db->QUERY("SELECT * FROM payment_advice_soa WHERE payment_advice_id = '$paymentadviceid'")->RESULT_ARRAY();
        return $result;
    }
}

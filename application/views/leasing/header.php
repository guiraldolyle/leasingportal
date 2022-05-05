<!DOCTYPE html>
<html ng-app="myApp">

<head>
    <title>AGC-PMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>img/logo-ico.ico" />
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/3D.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/noty-buttons.css"> -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/noty-animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/angular-chart.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/multilevel-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/angular-datepicker.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/angular-clock.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/fileinput.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/checkboxes.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/massautocomplete.theme.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/treeview.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/ng-table.min.css">
    <link rel="stylesheet" type="text/css" href='<?php echo base_url(); ?>css/calendar/lib/main.css'>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url(); ?>css/animate.css'>
    <script>
        function docReady(fn) {
            if (document.readyState === "complete" || document.readyState === "interactive") {
                setTimeout(fn, 1);
            } else {
                document.addEventListener("DOMContentLoaded", fn);
            }
        }

        docReady(function() {
            $('.modal').modal({
                show: false,
                keyboard: false,
                backdrop: true
            })

            //window.pop.loading();

        });
    </script>
</head>

<body ng-controller="appController">
    <nav class="navbar navbar-default navbar-static-top" id="leasing_navbar">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img class="brand-img" src="<?php echo base_url(); ?>img/leasing-brand.png"></a>
            </div>
            <div class="nav-container">
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/home"><i class="fa fa-home"></i> Home </a></li>

                        <!-- Hidden from accounting users -->
                        <?php if ($this->session->userdata('user_type') != 'Accounting Staff' && $this->session->userdata('user_type') != 'IAD') : ?>
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file"></i> Master Setup <span class="caret"></span></a>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

                                    <?php if ($this->session->userdata('user_type') == 'Administrator') : ?>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/stores"><i class="fa fa-building"></i> Stores/Property</a></li>
                                    <?php endif ?>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator') : ?>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/floorplan_setup"><i class="fa fa-map"></i> Floor Plan Model Setup</a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/cash_to_bank"><i class="fa fa-money"></i> Cash To Bank Setup</a></li>
                                    <?php endif; ?>
                                    <li class="dropdown-submenu"><a tabindex="-1" href="#"><i class="fa fa-sitemap"></i> Lessee Category</a>
                                        <ul class="dropdown-menu">
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_mstrfile/category_one">First Level Category</a></li>
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/category_two">Second Level Category</a></li>
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/category_three">Third Level Category</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/leasee_type"><i class="fa fa-clone"></i> Lessee Type</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/area_classification"><i class="fa fa-delicious"></i> Area Classification</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/area_type"><i class="fa fa-ticket"></i> Area Type</a></li>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator') : ?>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/rent_period"><i class="fa fa-filter"></i> Rent Period Setup</a></li>
                                    <?php endif ?>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/locationSlot_setup"><i class="fa fa-map-marker"></i> Location Slot Setup</a></li>
                                    <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/locationCode_setup"><i class="fa fa-map-marker"></i> Location Code Setup</a></li> -->
                                    <!-- <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"><i class="fa fa-tag"></i> Price Management</a>
                                    <ul class="dropdown-menu">
                                        <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_mstrfile/price_floor">Per Floor</a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/price_locationCode">Per Location Code</a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/exhibit_rates"><i class="fa fa-tags"></i> Exhibit Rate</a></li>         -->
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/charges_setup"><i class="fa fa-money"></i> Charges</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/tenant_type"><i class="fa fa-user-secret"></i> Discount Management</a></li>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator') : ?>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/vat_setup"><i class="fa fa-plus-square"></i> VAT & Rental Increment</a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/gl_accounts"><i class="fa fa-clipboard"></i> G/L Accounts Setup</a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/bank_setup"><i class="fa fa-building"></i> Bank Setup</a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/leasing_users"><i class="fa fa-users"></i> User Setup</a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/leasing_users"><i class="fa fa-users"></i> Tenant User Setup</a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/migrate_data"><i class="fa fa-database"></i> Migrate Data</a></li>
                                    <?php endif ?>
                                </ul>
                            </li>
                        <?php endif ?>

                        <?php if ($this->session->userdata('user_type') != 'Administrator' && $this->session->userdata('user_type') != 'IAD') : ?>
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-exchange" aria-hidden="true"></i> Transaction <span class="caret"></span></a>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

                                    <!-- Hide Master Setup from accounting users -->
                                    <?php if ($this->session->userdata('user_type') != 'Accounting Staff') : ?>
                                        <li class="dropdown-submenu"><a tabindex="-1" href="#"><i class="fa fa-user-secret"></i> Prospect Management</a>
                                            <ul class="dropdown-menu">
                                                <!-- <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/FacilityRental">Facility Rental</a></li> -->
                                                <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/Lprospect_management">Long Term Prospects</a></li>
                                                <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/Sprospect_management">Short Term Prospects</a></li>
                                            </ul>
                                        </li>
                                    <?php endif ?>
                                    <li class="dropdown-submenu"><a tabindex="-1" href="#"><i class="fa fa-edit"></i> Contract Management</a>
                                        <ul class="dropdown-menu">
                                            <?php if ($this->session->userdata('user_type') == 'Accounting Staff') : ?>
                                                <li><a href="<?php echo base_url(); ?>index.php/leasing_facilityrental/FacilityRental">Facility Rental</a></li>
                                            <?php endif ?>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/Lcontract_management">Long Term Tenants</a></li>
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/Scontract_management">Short Term Tenants</a></li>

                                        </ul>
                                    </li>


                                    <?php if ($this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor') : ?>
                                        <?php if ($this->session->userdata('user_type') != 'IAD') : ?>
                                            <li class="dropdown-submenu"> <a tabindex="1" href="#"><i class="fa fa-columns"></i> Invoicing</a>
                                                <ul class="dropdown-menu">
                                                    <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing/invoicing">Create Invoice</a></li>
                                                    <!-- <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/retro_invoice">Retro Invoice</a></li> -->
                                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/draft_invoice">Draft Invoice</a></li>
                                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/posted_invoice">Posted Invoice</a></li>
                                                    <?php if ($this->session->userdata('user_type') == 'Accounting Staff') : ?>
                                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_facilityrental/FacilityRental_invoice">Facility Rental Invoice</a></li>
                                                    <?php endif ?>
                                                </ul>
                                            </li>
                                        <?php endif ?>
                                        <li class="dropdown-submenu"> <a tabindex="1" href="#"><i class="fa fa-columns"></i> Statement of Account</a>
                                            <ul class="dropdown-menu">
                                                <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing/soa">Create SOA(Per Tenant)</a></li>
                                                <!-- <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/soa_batch">Create SOA(Per Batch)</a></li> -->
                                                <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/reprint_soa">Re-print SOA</a></li>


                                                <!-- for facilty rental -->

                                                <?php if ($this->session->userdata('user_type') == 'Accounting Staff') : ?>

                                                    <li class="dropdown-submenu"> <a tabindex="1" href="#"><i class="fa fa-circle"></i> Faciltiy Rental</a>
                                                        <ul class="dropdown-menu">

                                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_facilityrental/FacilityRental_soa">Statement of Account</a></li>

                                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_facilityrental/FacilityRental_reprintsoa">Re-print SOA</a></li>

                                                        </ul>
                                                    </li>

                                                <?php endif ?>
                                            </ul>
                                        </li>
                                        <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-credit-card"></i> Payment</a>
                                            <ul class="dropdown-menu">
                                                <li><a href="<?php echo base_url(); ?>index.php/leasing/payment">Regular Payment</a></li>
                                                <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/payment">Regular Payment (Old)</a></li> -->
                                                <!-- <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/preop_payment">Using Preop Charges</a></li> -->
                                                <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing/preop_payment">Using Preop Charges</a></li>
                                                <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing/advance_payment">Advance Payment</a></li>

                                                <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/reg_fundTransfer">Identified Fund Transfer / UFT Clearing</a></li>

                                                <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/reverse_internalPayment">Reverse Internal Payment / IP Clearing</a></li>

                                                <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/unclosed_PDC">PDC Clearing</a></li>



                                                <?php if ($this->session->userdata('user_type') == 'Accounting Staff') : ?>


                                                    <!-- <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-credit-card"></i>Facilty Rental Payment</a>

                                              <ul class="dropdown-menu">

                                                  <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_facilityrental/FacilityRental_Payment">Payment</a></li>
                                                  <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_facilityrental/FacilityRental_PaymentHistory">Payment History</a></li>

                                              </ul></li> -->

                                                <?php endif ?>

                                            </ul>
                                        </li>
                                        <!-- <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-registered"></i> RR Clearing</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/unreg_fundTransfer">Unidentified Fund Transfer/OTC Payments</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/reg_fundTransfer">Identified Fund Transfer/OTC Payments</a></li>
                                        </ul>
                                    </li> -->
                                        <!-- <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-credit-card"></i> Internal Payment</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/record_internalPayment">Record Payment</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/reverse_internalPayment">Reverse Internal Payment</a></li>
                                        </ul>
                                    </li> -->
                                        <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/credit_memo"><i class="fa fa-cc-amex"></i> Credit Memo</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/debit_memo"><i class="fa fa-cc-amex"></i> Debit Memo</a></li> -->
                                        <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/unclosed_PDC"><i class="fa fa-stop"></i> Unclosed PDC</a></li> -->
                                        <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/penalties"><i class="fa fa-list"></i> Penalties</a></li> -->

                                        <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-pencil" aria-hidden="true"></i> Adjustments</a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-toggle="modal" ng-click="managers_key('<?php echo base_url(); ?>index.php/leasing_transaction/adjustmentsInvoice')" data-target="#manager_modal">Invoice Adjustment
                                                    </a>
                                                </li>

                                                <li><a href="#" data-toggle="modal" ng-click="managers_key('<?php echo base_url(); ?>index.php/leasing_transaction/adjustmentsPayment')" data-target="#manager_modal">Payment Adjustment
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>

                                    <?php endif ?>
                                </ul>
                            </li>
                        <?php endif ?>
                        <?php if ($this->session->userdata('user_type') == 'Accounting Staff') : ?>
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-book"></i> WOF Transaction <span class="caret"></span></a>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/recognize_rentDue"><i class="fa fa-reply-all"></i> Recognize Rentable Due</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/closing_rentDue"><i class="fa fa-exchange"></i> Closing Rentable Due</a></li>
                                </ul>
                            </li>
                        <?php endif ?>
                        <!-- <li class=""><a href="<?php echo base_url(); ?>index.php/leasing_dashboard"><i class="fa fa-bar-chart"></i> Dashboard</a></li> -->
                        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i> Reports <span class="caret"></span></a>
                            <ul class="dropdown-menu multi-level">
                                <?php if ($this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor' || $this->session->userdata('user_type') == 'Store Manager') : ?>
                                    <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-balance-scale"></i> Ledger</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/rr_ledger">Receivables Ledger</a></li>
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/tenant_ledger">Tenant Ledger</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/general_ledger">General Ledger</a></li>
                                        </ul>
                                    </li>

                                    <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-book"></i> Reports for Nav</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/RR_reports">Basic - Rent Receivables</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/AR_reports">Other Charges - AR Non Trade</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/RR_AR_ledger">Rent & Other Charges Ledger</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/collection_reports">Payments - Collection</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/collection_reports_manual">Payments - Collection (Manual Doc. No Entry)</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/payment_ledger">Payment Ledger</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/bank_recon">For Bank Recon</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing/recon_sys_vs_nav">For System vs Navision Recon</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing/recon_sys_vs_bank">For System vs Bank Recon</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/nav_export_history">Exportation History</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/receipts_audit"> <i class="fa fa-book"></i> Receipts Audit</a></li>

                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/monthly_receivable"> <i class="fa fa-list-alt"></i> Monthly Receivable Report</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/monthly_payable"> <i class="fa fa-list-alt"></i> Monthly Payable & Amount Paid</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/monthly_receivable_summary"> <i class="fa fa-list-alt"></i> Monthly Receivable Summary</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/monthly_preop_summary"> <i class="fa fa-list-alt"></i> Monthly Preop Summary</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/receivable_summary"> <i class="fa fa-book"></i> Receivable Summary</a></li>
                                    <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/delinquent_tenants"> <i class="fa fa-list-alt"></i> Delinquent Tenants</a></li> -->
                                    <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-calendar"></i> Receivable Aging</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/aging_RR">Rent Receivable</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/aging_AR">Account Receivable</a></li>
                                            <!-- <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/bank_ledger">Bank Ledger</a></li> -->
                                        </ul>
                                    </li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/account_chart"> <i class="fa fa-pie-chart"></i> Chart of Accounts</a></li>
                                    <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/rent_receivable"> <i class="fa fa-book"></i> Rent Receivable</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/account_receivable"> <i class="fa fa-server"></i> Account Receivable</a></li> -->
                                    <?php if ($this->session->userdata('user_type') == 'Administrator') : ?>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/acountability_report"> <i class="fa fa-money"></i> Accountablility Report</a></li>
                                    <?php endif; ?>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/waived_penlaties"> <i class="fa fa-close"></i> Waived Penalties</a></li>
                                    <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/invoice_history"> <i class="fa fa-server"></i> Invoice History</a></li> -->
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/payment_proofList"> <i class="fa fa-list"></i> Payment Proof List</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/payment_scheme"> <i class="fa fa-list"></i> Payment History</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/payment_report"> <i class="fa fa-list"></i> Payment Report</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/adjustment_ledger"><i class="fa fa-list"></i> Adjustment History</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing/invoice_override_history"> <i class="fa fa-list"></i> Invoice Override History</a></li>

                                <?php endif ?>
                                <?php if ($this->session->userdata('user_type') != 'Accounting Staff' && $this->session->userdata('user_type') != 'IAD') : ?>
                                    <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-balance-scale"></i> Ledger</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/rr_ledger">Receivables Ledger</a></li>
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/tenant_ledger">Tenant Ledger</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/general_ledger">General Ledger</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-history"></i> Tenant History</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/longTerm_tenantHistory">Long Term Tenants</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/shortTerm_tenantHistory">Short Term Tenants</a></li>
                                            <!-- <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/bank_ledger">Bank Ledger</a></li> -->
                                        </ul>
                                    </li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/leasableArea"><i class="fa fa-th-large"></i> Leasable Area</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/locationCode_perTenant"><i class="fa fa-th-large"></i> Location Code per Tenant</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/payment_scheme"> <i class="fa fa-list"></i> Payment History</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/payment_report"> <i class="fa fa-list"></i> Payment Report</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/adjustment_ledger"><i class="fa fa-list"></i> Adjustment History</a></li>
                                <?php endif ?>
                                <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/category_treeview"><i class="fa fa-newspaper-o"></i> Lessee Category</a></li> -->
                                <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/creditMemo_history"> <i class="fa fa-credit-card"></i> Credit Memo History</a></li>
                                 <li class="dropdown-submenu"><a tabindex="-1" href="#"><i class="fa fa-tasks"></i> Contract Amendment History</a>
                                    <ul class="dropdown-menu">
                                        <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/longTerm_amendments">Long Term</a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/shortTerm_amendments">Short Term/Exhibitors</a></li>
                                    </ul>
                                </li> -->
                            </ul>

                            <!-- THIS IS IAD REPORT -->
                            <?php if ($this->session->userdata('user_type') == 'IAD') : ?>
                                <ul class="dropdown-menu multi-level">
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/tenant_ledger">Tenant Ledger</a></li>
                                    <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_transaction/general_ledger">General Ledger</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/reprint_soa">SOA</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/payment_scheme"> <i class="fa fa-list"></i> Payment History</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/rr_ar_summary"><i class="fa fa-newspaper-o"></i> Monthly RR/AR Summary</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/monthly_payable"><i class="fa fa-list-alt"></i> Monthly Payable & Amount Paid</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/monthly_receivable_summary"><i class="fa fa-list-alt"></i> Monthly Receivable Summary</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/CFS_remittance"><i class="fa fa-list-alt"></i> CFS Remittance</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/payment_report"> <i class="fa fa-list"></i> Payment Report</a></li>
                                    <li class="dropdown-submenu"><a tabindex="1" href="#"><i class="fa fa-calendar"></i> Receivable Aging</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_reports/aging_RR">Rent Receivable</a></li>
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/aging_AR">Account Receivable</a></li>
                                            <!-- <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_reports/bank_ledger">Bank Ledger</a></li> -->
                                        </ul>
                                    </li>
                                </ul>
                            <?php endif ?>
                        </li>

                        <?php if ($this->session->userdata('user_type') != 'Accounting Staff' && $this->session->userdata('user_type') != 'IAD') : ?>
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-archive"></i> Archive <span class="caret"></span></a>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                    <li class="dropdown-submenu"><a tabindex="-1" href="#"><i class="fa fa-file-archive-o "></i> Revoked Prospect</a>
                                        <ul class="dropdown-menu">
                                            <li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/leasing_archive/archive_lprospect">Long Term</a></li>
                                            <li><a href="<?php echo base_url(); ?>index.php/leasing_archive/archive_sprospect">Short Term</a></li>
                                        </ul>
                                    </li>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator') : ?>
                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_archive/logs"><i class="fa fa-file-archive-o "></i> Logs</a></li>
                                    <?php endif ?>
                                </ul>
                            </li>
                        <?php endif ?>

                        <?php if ($this->session->userdata('user_type') == 'Accounting Staff') : ?>
                            <li class=""><a href="http://172.16.46.135/dummy/"><i class="fa fa-book" aria-hidden="true"></i> Leasing Tracing</a></li>
                        <?php endif ?>

                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/about"><i class="fa fa-exclamation-circle"></i> About</a></li>
                        <!-- <li class=""><a href="<?php echo base_url(); ?>index.php/leasing_transaction/truncate_transaction"><i class="fa fa-trash"></i> Truncate Transaction</a></li> -->
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown notification-dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class=""></i> Notification<span class="badge" style="background-color:red"><?php echo count($expiry_tenants); ?></span></a>
                            <ul class="dropdown-menu notifications" role="menu" aria-labelledby="dLabel">
                                <div class="notification-heading">
                                    <h4 class="menu-title">Contracts to expire.</h4>
                                    <h4 class="menu-title pull-right">
                                </div>
                                <li class="divider"></li>
                                <div class="notifications-wrapper">

                                    <?php foreach ($expiry_tenants as $tenant) : ?>
                                        <a class="content" href="#">
                                            <div class="notification-item">
                                                <h4 class="item-title"><?php echo $tenant['trade_name']; ?> contract is about to expire.</h4>
                                                <p class="item-info"><?php echo $tenant['opening_date'] . " To " . $tenant['expiry_date']; ?></p>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                                <li class="divider"></li>
                                <div class="notification-footer">
                                    <h4 class="menu-title"></h4>
                                </div>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Welcome: <u><?php echo $this->session->userdata('first_name'); ?></u><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#usettings_modal" ng-click="user_credentials('<?php echo base_url(); ?>index.php/ctrl_leasing/user_credentials/')"><i class="fa fa-cog"></i> Account Settings</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div> <!-- /.container-fluid -->
    </nav>


    <!-- Manager's Key Modal -->
    <div class="modal fade" id="manager_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-key"></i> Manager's Key</h4>
                </div>
                <form action="" method="post" name="frm_manager" id="frm_manager">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-addon squared"><i class="fa fa-user"></i></div>
                                                <input type="text" required class="form-control" ng-model="username" id="username" name="username" autocomplete="off">
                                            </div>
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_manager.username.$dirty && frm_manager.username.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-addon squared"><i class="fa fa-key"></i></div>
                                                <input type="password" required class="form-control" ng-model="password" id="password" name="password" autocomplete="off">
                                            </div>
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_manager.password.$dirty && frm_manager.password.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_manager.$invalid" class="btn btn-primary"> <i class="fa fa-unlock"></i> Submit</button>
                            <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Confirmation Modal -->

    <!-- KING ARTHURS ADDITION -------------------------------------------------------------------------------------------------------------------------------------------------------->
    <!-- Decline Remarks Modal Lprospect_management --------------------------------------------------------------------------------------------------------------------------->
    <div class="modal" id="remarks_modal" style="overflow-y: scroll;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Remarks</h4>
                </div>

                <form action="" method="post" name="frm_remarks" id="frm_remarks">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="">
                                                <div ng-hide="true">{{ID}} {{sName}}</div>
                                                <p>Reason for revoking {{ tname }}: </p>
                                                <textarea style="resize: vertical; height: 200px" type="text" required class="form-control" ng-model="remarks_frm_model" id="remarks_frm" name="remarks_frm" autocomplete="off"></textarea>
                                            </div>
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_remarks.remarks_frm.$dirty && frm_remarks.remarks_frm.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                    <div class="modal-footer">
                        <button ng-disabled="frm_remarks.$invalid" type="button" class="btn btn-alert" data-dismiss="modal" data-toggle="modal" ng-click="managers_key(link + ID + '/' + sName + '/' + remarks_frm_model)" data-target="#manager_modal">
                            <i class="fa fa-thumbs-down"></i> Decline
                        </button>
                        <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </form>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- Decline Remarks Modal End Lprospect_management-->

    <!-- Decline Remarks Modal 2 lst_Lforcontract-->
    <div class="modal" id="remarks_modal2" style="overflow-y: scroll;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Remarks</h4>
                </div>

                <form action="" method="post" name="frm_remarks2" id="frm_remarks2">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="">
                                                <div ng-hide="true">{{ID}} {{sName}}</div>
                                                <p>Reason for revoking {{ tname }}: </p>
                                                <textarea style="resize: vertical; height: 200px" type="text" required class="form-control" ng-model="remarks_frm_model2" id="remarks_frm2" name="remarks_frm2" autocomplete="off"></textarea>
                                            </div>
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_remarks2.remarks_frm2.$dirty && frm_remarks2.remarks_frm2.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                    <div class="modal-footer">
                        <button ng-disabled="frm_remarks2.$invalid" type="button" class="btn btn-alert" data-dismiss="modal" data-toggle="modal" ng-click="managers_key2('<?php echo base_url(); ?>index.php/leasing_transaction/deny_lprospect2_decline/' + ID + '/' + sName + '/' + remarks_frm_model2)" data-target="#manager_modal">
                            <i class="fa fa-thumbs-down"></i> Decline
                        </button>
                        <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </form>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- Decline Remarks Modal End 2 lst_Lforcontract--------------------------------------------------------------------------------------------------------------------->
    <!-- KING ARTHURS ADDITION  --------------------------------------------------------------------------------------------------------------------------------------------------->

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmation_modal" style="z-index: 1080 !important;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-question-circle"></i> Confirmation</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span id="confirm_msg">You wish to delete this data?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" id="anchor_delete" class="btn btn-danger"><i class="fa fa-trash"></i> Proceed</a>
                        <button type="button" class="btn btn-primary" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Confirmation Modal -->


    <!-- Confirmation1 Modal -->
    <div class="modal fade" id="confirmation1_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-question-circle"></i> Confirmation</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span id="confirm_msg">You wish to continue this action?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" id="anchor_confirm" class="btn btn-primary"><i class="fa fa-thumbs-up"></i> Proceed</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Confirmation Modal -->


    <!-- Print Preview Modal -->
    <div class="modal fade" id="print_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-tv"></i> Print Preview</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="print_preview"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" onclick="callPrint('printPreview_frame')" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Print Preview Modal -->





    <!-- User Setting Modal -->
    <div class="modal fade" id="usettings_modal">
        <div class="modal-dialog modal-medium">
            <div class="modal-content" ng-repeat="user in details">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-cog"></i> User Settings</h4>
                </div>
                <form action="{{ '../ctrl_leasing/update_usettings/' + user.id }}" method="post" name="frm_usettings" id="frm_usettings">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="floor_area" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>Username</label>
                                        <div class="col-md-6">
                                            <input type="text" required class="form-control" ng-model="user.username" id="username" name="username" ng-minlength="5" autocomplete="off" ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}$/" is-unique-update is-unique-id="{{user.id}}" is-unique-api="<?php echo base_url(); ?>index.php/ctrl_validation/verify_username_update/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_usettings.username.$dirty && frm_usettings.username.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_usettings.username.$dirty && frm_usettings.username.$error.pattern">
                                                    <p class="error-display">A combination of alphanumeric characters and at least 1 upppercase.</p>
                                                </span>
                                                <span ng-show="frm_usettings.username.$dirty && frm_usettings.username.$error.minlength">
                                                    <p class="error-display">Atleast 5 characters.</p>
                                                </span>
                                                <span ng-show="frm_usettings.username.$dirty && frm_usettings.username.$error.unique">
                                                    <p class="error-display">Username already taken.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="floor_area" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>Old Password</label>
                                        <div class="col-md-6">
                                            <input type="password" required class="form-control" ng-model="old_pass" id="old_pass" name="old_pass" autocomplete="off" is-unique-update is-unique-id="{{user.id}}" is-unique-api="<?php echo base_url(); ?>index.php/ctrl_validation/check_oldpass/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_usettings.old_pass.$dirty && frm_usettings.old_pass.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_usettings.old_pass.$dirty && frm_usettings.old_pass.$error.unique">
                                                    <p class="error-display">Old password not match.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="floor_area" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>New Password</label>
                                        <div class="col-md-6">
                                            <input type="password" required class="form-control" ng-model="new_pass" id="new_pass" name="new_pass" autocomplete="off" ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}$/" ng-minlength="5">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_usettings.new_pass.$dirty && frm_usettings.new_pass.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_usettings.new_pass.$dirty && frm_usettings.new_pass.$error.pattern">
                                                    <p class="error-display">A combination of alphanumeric characters and at least 1 upppercase.</p>
                                                </span>
                                                <span ng-show="frm_usettings.new_pass.$dirty && frm_usettings.new_pass.$error.minlength">
                                                    <p class="error-display">Atleast 5 characters.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="floor_area" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>Confirm Password</label>
                                        <div class="col-md-6">
                                            <input type="password" required class="form-control" ng-model="confirm_pass" id="confirm_pass" name="confirm_pass" autocomplete="off" data-password-verify="new_pass">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_usettings.confirm_pass.$dirty && frm_usettings.confirm_pass.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_usettings.confirm_pass.$dirty && frm_usettings.confirm_pass.$error.passwordVerify">
                                                    <p class="error-display">Password not match.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_usettings.$invalid" class="btn btn-primary"> <i class="fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End User Setting Modal -->

    <!-- Loading Modal -->
    <div class="modal" id="spinner_modal">
        <!-- <h3 class="spin"><i class="fa fa-circle-o-notch fa-spin fa-3x"></i></h3>
        <br>
        <span class = "process">Processing...</span> -->
        <div class="spin">
            <div id="fountainTextG">
                <div id="fountainTextG_1" class="fountainTextG">L</div>
                <div id="fountainTextG_2" class="fountainTextG">o</div>
                <div id="fountainTextG_3" class="fountainTextG">a</div>
                <div id="fountainTextG_4" class="fountainTextG">d</div>
                <div id="fountainTextG_5" class="fountainTextG">i</div>
                <div id="fountainTextG_6" class="fountainTextG">n</div>
                <div id="fountainTextG_7" class="fountainTextG">g</div>
                <div id="fountainTextG_8" class="fountainTextG">.</div>
                <div id="fountainTextG_9" class="fountainTextG">.</div>
                <div id="fountainTextG_10" class="fountainTextG">.</div>
            </div>
        </div>
    </div>
    <!-- End Confirmation Modal -->


    <!-- Contract Termination Modal -->
    <div class="modal fade" id="contract_termination">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-remove"></i> Terminate Contract</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-inline" ng-submit="terminate_tenant('<?php echo base_url(); ?>index.php/leasing_transaction/terminate_tenant/' + reason, '<?php echo $this->session->userdata('user_type'); ?>')" name="frm_termination" id="frm_termination">
                                <div class="form-group col-md-12">
                                    <label for="reason_termination">Reason</label>
                                    <input type="text" style="display:none" id="tenantID" ng-model="tenantID" name="tenantID">
                                    <input type="text" class="form-control" id="reason_termination" name="reason" autocomplete="off" ng-model="reason" placeholder="Reason for termination." required>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <div class="validation-Error">
                                        <span ng-show="frm_termination.reason.$dirty && frm_termination.reason.$error.required">
                                            <p class="error-display">This field is required.</p>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Contract Termination Modal -->
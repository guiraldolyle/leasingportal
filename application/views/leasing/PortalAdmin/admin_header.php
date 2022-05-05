<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>LEASING PORTAL - Admin</title>
    <!-- jquery ui CSS -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>img/logo-ico.ico" />
    <link href="<?php echo base_url(); ?>css/jquery-ui.min.css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet">
    <!-- Data Table CSS -->
    <link href="<?php echo base_url(); ?>css/datatables.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(); ?>css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/jquery.dreamalert.css">
    <link href="<?php echo base_url(); ?>css/bootstrap-toggle.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>css/admin-style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>js/sweetalert2/sweetalert2.css" rel="stylesheet">



</head>

<body>

    <section id="container">
        <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
        <!--header start-->
        <header class="header black-bg">
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
            </div>
            <!--logo start-->
            <a href="<?php echo base_url(); ?>index.php/portal/admin_home" class="logo"><b>LEASING PORTAL ADMIN</b></a>
            <!--logo end-->

            <div class="top-menu">
                <ul class="nav pull-right top-menu">
                    <li><a class="logout" href="<?php echo base_url(); ?>logout"><i class="fa fa-power-off"></i> Logout</a></li>
                </ul>
            </div>
        </header>
        <!--header end-->

        <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
        <!--sidebar start-->
        <aside>
            <div id="sidebar" class="nav-collapse ">
                <!-- sidebar menu start-->
                <ul class="sidebar-menu" id="nav-accordion">
                    <p class="centered"><a href="<?php echo base_url(); ?>index.php/admin/dashboard"><img src="<?php echo base_url(); ?>img/logo.png" width="60"></a></p>
                    <h5 class="centered">ADMINISTRATOR</h5>
                    <li class="mt">
                        <a href="<?php echo base_url(); ?>portal/admin_home">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-money"></i>
                            <span>Upload Invoice Data</span>
                            <span class="badge bg-important pull-right"></span>
                        </a>
                        <ul class="sub">
                            <!-- <li><a href="<?php echo base_url(); ?>portal/allData">All Data</a></li> -->
                            <li><a href="<?php echo base_url(); ?>portal/perTenant">Per Tenant</a></li>
                            <!-- <li><a href="<?php echo base_url(); ?>portal/perStore">Per Store</a></li>
                            <li><a href="<?php echo base_url(); ?>portal/admin_invoices">Per Invoices</a></li> -->
                        </ul>
                    </li>

                    <li class="">
                        <a href="<?php echo base_url(); ?>portal/admin_soa">
                            <i class="fa fa-dashboard"></i>
                            <span>Upload SOA Data</span>
                        </a>
                    </li>

                    <!-- <li class="">
                        <a href="<?php echo base_url(); ?>portal/admin_payment">
                            <i class="fa fa-dashboard"></i>
                            <span>Upload Payment Data</span>
                        </a>
                    </li> -->

                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-money"></i>
                            <span>Upload Payment Data</span>
                            <span class="badge bg-important pull-right"></span>
                        </a>
                        <ul class="sub">
                            <!-- <li><a href="<?php echo base_url(); ?>portal/allData">All Data</a></li> -->
                            <li><a href="<?php echo base_url(); ?>portal/admin_payment">Payment Per Tenant</a></li>
                            <li><a href="<?php echo base_url(); ?>portal/admin_paymentPerStore">Per Store</a></li>
                            <!-- <li><a href="<?php echo base_url(); ?>portal/perStore">Per Store</a></li>
                            <li><a href="<?php echo base_url(); ?>portal/admin_invoices">Per Invoices</a></li> -->
                        </ul>
                    </li>

                    <?php if ($this->session->userdata('user_type') == 'Admin') : ?>

                        <li class="">
                            <a href="<?php echo base_url(); ?>portal/admin_tenants">
                                <i class="fa fa-user"></i>
                                <span>Users</span>
                            </a>
                        </li>

                        <li class="">
                            <a href="<?php echo base_url(); ?>portal/uploadhistory">
                                <i class="fa fa-history" aria-hidden="true"></i>
                                <span>Upload History</span>
                            </a>
                        </li>

                        <li class="">
                            <a href="<?php echo base_url(); ?>portal/notices">
                                <i class="fa fa-bell" aria-hidden="true"></i>
                                <span>Payment Advice Notices</span>
                                <?php if ($count > 0) : ?>
                                    <span class="badge bg-important pull-right"><?php echo $count; ?></span>
                                <?php else : ?>
                                    <span class="badge bg-important pull-right"></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <!-- sidebar menu end-->
            </div>
        </aside>
        <!--sidebar end-->

        <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->


        <div class="modal" id="spinner_modal">
            <div class="spinner">
                <img src="<?php echo base_url(); ?>img/loading.svg">
            </div>
        </div>



        <div class="modal fade" id="modal_confirmation" style="z-index: 1080 !important;">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-question-circle"></i> Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span id="confirm_msg"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" id="anchor_delete" class="btn btn-success"><i class="fa fa-check"></i> Proceed</a>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
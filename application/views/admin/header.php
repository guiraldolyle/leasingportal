<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Dashboard">
        <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

        <title>LEASING - Admin</title>
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
        
    
        
    </head>
    <body>

    <section id="container" >
        <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
        <!--header start-->
        <header class="header black-bg">
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
            </div>
            <!--logo start-->
            <a href="<?php echo base_url(); ?>index.php/admin/dashboard" class="logo"><b>LEASING - ADMIN</b></a>
            <!--logo end-->
        
            <div class="top-menu">
                <ul class="nav pull-right top-menu">
                    <li><a class="logout" href="<?php echo base_url(); ?>index.php/admin/logout"><i class = "fa fa-power-off"></i> Logout</a></li>
                </ul>
            </div>
        </header>
        <!--header end-->
      
        <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
        <!--sidebar start-->
        <aside>
            <div id="sidebar"  class="nav-collapse ">
                <!-- sidebar menu start-->
                <ul class="sidebar-menu" id="nav-accordion">
                    <p class="centered"><a href="<?php echo base_url(); ?>index.php/admin/dashboard"><img src="<?php echo base_url(); ?>img/logo.png"  width="60"></a></p>
                    <h5 class="centered">ADMINISTRATOR</h5>
                    <li class="mt">
                        <a href="<?php echo base_url(); ?>index.php/admin/dashboard">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;" >
                            <i class="fa fa-money"></i>
                            <span>Charges</span>
                            <span class = "badge bg-important pull-right">4</span>
                        </a>
                        <ul class="sub">
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/add_charges_page">Add Charges</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/update_charges_page">Update/Delete Charges</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/change_basicRental_page">Change Basic Rental</a></li>
                            <li><a  href="#">Swap Location Code</a></li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;" >
                            <i class="fa fa-info-circle"></i>
                            <span>Long Term</span>
                            <span class = "badge bg-important pull-right">4</span>
                        </a>
                        <ul class="sub">
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/prospect_longterm">Prospects</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/pending_longterm">Pending Contracts</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/active_longtermTenants">Active</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/terminated_longtermContracts">Terminated</a></li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;" >
                            <i class="fa fa-info-circle"></i>
                            <span>Short Term</span>
                            <span class = "badge bg-important pull-right">4</span>
                        </a>
                        <ul class="sub">
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/prospect_shortterm">Prospects</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/pending_shortterm">Pending Contracts</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/active_shorttermTenants">Active</a></li>
                            <li><a  href="<?php echo base_url() ?>index.php/admin/terminated_shorttermContracts">Terminated</a></li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;" >
                            <i class="fa fa-stack-exchange"></i>
                            <span>GL Operations</span>
                            <span class = "badge bg-important pull-right">7</span>
                        </a>
                        <ul class="sub">
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/delete_entry_page">Delete Entry</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/cancel_soa_page">Cancel SOA Entry</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/cancel_payment_page">Cancel Payment</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/change_dueDate_page">Change Due Date</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/change_postingDate_page">Change Posting Date</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/change_SOACollectionDate_page">Change SOA Collection Date</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/change_receiptNo_page">Change Receipt No.</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/change_bankTagging_page">Change Bank Tagging</a></li>
                            <li><a  href="<?php echo base_url(); ?>index.php/admin/VDS_tagging">VDS Tagging</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/admin/add_preop_page">
                            <i class="fa fa-plus-square"></i>
                            <span>Add Preop</span>
                        </a>
                    </li>
                </ul>
                <!-- sidebar menu end-->
            </div>
        </aside>
        <!--sidebar end-->
      
        <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->


        <div class="modal" id = "spinner_modal">
            <div class="spinner">
                <img src="<?php echo base_url(); ?>img/spinner.svg">
            </div>
        </div>



        <div class="modal fade" id = "modal_confirmation" style="z-index: 1080 !important;">
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
                            <a href="#" id = "anchor_delete" class="btn btn-success"><i class="fa fa-check"></i> Proceed</a>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
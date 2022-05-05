<!DOCTYPE html>
<html lang="en" ng-app="app">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leasing Portal | <?php echo $title; ?></title>
    <link rel="icon" href="http://172.16.46.135/LEASINGPORTAL/img/LeasingPortalLogo.png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/style.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-caret-square-left"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
                <img class="brand-image" src="http://172.16.46.135/LEASINGPORTAL/img/LeasingPortalLogo.png" style="opacity: .8">
                <span class="brand-text font-weight-heavy">LEASING PORTAL</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="dist/img/user.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?php echo $this->session->userdata('tenant_id'); ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <?php if ($status == 'soa') : ?>
                                <a href="<?php echo base_url() ?>mysoa" class="nav-link active">
                                <?php else : ?>
                                    <a href="<?php echo base_url() ?>mysoa" class="nav-link">
                                    <?php endif; ?>
                                    <i class="nav-icon fas fa-receipt"></i>
                                    <p>My SOA</p>
                                    </a>
                        </li>
                        <li class="nav-item">
                            <?php if ($status == 'ledger') : ?>
                                <a href="<?php echo base_url() ?>myledger" class="nav-link active">
                                <?php else : ?>
                                    <a href="<?php echo base_url() ?>myledger" class="nav-link">
                                    <?php endif; ?>
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>My Ledger</p>
                                    </a>
                        </li>
                        <li class="nav-item">
                            <?php if ($status == 'readings') : ?>
                                <a href="<?php echo base_url() ?>utilityreadings" class="nav-link active">
                                <?php else : ?>
                                    <a href="<?php echo base_url() ?>utilityreadings" class="nav-link">
                                    <?php endif; ?>
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Utility Readings</p>
                                    </a>
                        </li>
                        <li class="nav-item">
                            <?php if ($status == 'advice') : ?>
                                <a href="<?php echo base_url() ?>paymentadvice" class="nav-link active">
                                <?php else : ?>
                                    <a href="<?php echo base_url() ?>paymentadvice" class="nav-link">
                                    <?php endif; ?>
                                    <i class="nav-icon far fa-credit-card"></i>
                                    <p>Payment Advice</p>
                                    </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
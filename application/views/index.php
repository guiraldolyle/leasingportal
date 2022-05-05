<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS -->

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.min.css">    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/index_style.css">

    <title>AGC-PMS</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>img/logo-ico.ico" />
</head>
<body>
    <div class="main-body"> 
        <div class="container">
            <div class="row">               
                <div class="main-page">
                    <div class="content-main">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="info float-container">
                                        <div class="col-sm-12 bottom-title" style="margin-bottom: 30px;">
                                            <h1 class="text-uppercase" style="color: #5aff00; text-shadow: 1px 2px black; margin-bottom: 0px; margin-top: 10px;"><strong>ALTURAS GROUP OF COMPANIES</strong></h1>
                                            <h3 class="text-uppercase" style="color: white; text-shadow: 1px 2px black;"><strong>Property Management System</strong></h3>
                                        </div>
                                        <div class="col-md-7" style="padding-left: 10px;">
                                            <div class="row form-group">
                                                <label for="exampleFormControlInput1"></label>
                                                <div class="col-md-8">
                                                    <a class="btn btn-primary btn-block btn-lg" href="<?php echo base_url(); ?>index.php/ctrl_leasing" title="" style="text-align: left; padding: 0px; border: 0px;">
                                                        <img src="<?php echo base_url(); ?>img/lease.jpg" alt="Image" class="menu-img">
                                                        <strong class="text-left ml-3">LEASING MODULE</strong>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label for="exampleFormControlInput1"></label>
                                                <div class="col-md-8">
                                                    <a class="btn btn-primary btn-block btn-lg" href="<?php echo base_url(); ?>index.php/ctrl_cfs/cfs_login" title="" style="text-align: left; padding: 0px; border: 0px;">
                                                         <img src="<?php echo base_url(); ?>img/cfs.jpg" alt="Image" class="menu-img">
                                                        <strong class="text-left ml-3">CFS MODULE</strong>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label for="exampleFormControlInput1"></label>
                                                <div class="col-md-8">
                                                    <a class="btn btn-primary btn-block btn-lg" href="<?= base_url('../AGC-TSMS'); ?>" title="" style="text-align: left; padding: 0px; border: 0px;">
                                                        <img src="<?php echo base_url(); ?>img/accounting.jpg" alt="Image" class="menu-img">
                                                        <strong class="text-left ml-3">TSMS MODULE</strong>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label for="exampleFormControlInput1"></label>
                                                <div class="col-md-8">
                                                    <a class="btn btn-primary btn-block btn-lg" href="<?php echo base_url(); ?>index.php/portal" title="" style="text-align: left; padding: 0px; border: 0px;">
                                                        <img src="<?php echo base_url(); ?>img/portal.jpg" alt="Image" class="menu-img">
                                                        <strong class="text-left ml-3">PORTAL LEASING</strong>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>       
            <footer class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
                    <p class="copyright">Copyright © 2021 AGC | Design: <a rel="nofollow" href="#" >Cyril Andrew</a></p>
                </div>    
            </footer> 
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
</body>
</html>


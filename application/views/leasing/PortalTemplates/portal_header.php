<!DOCTYPE html>
<html ng-app="myApp">

<head>
    <title>LEASING PORTAL</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>img/logo-ico.ico" />
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/3D.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/noty-animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/angular-chart.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/multilevel-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/angular-datepicker.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/angular-clock.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/fileinput.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/checkboxes.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/massautocomplete.theme.css">
    <link rel="stylesheet" type="text/css" href='<?php echo base_url(); ?>css/animate.css'>
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/adminlte.min.css"> -->
</head>

<body ng-controller="appController">

    <nav class="navbar navbar-default navbar-static-top portal-navbar">
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
                    <ul class="nav navbar-nav navbar-left">
                        <li class=""><a href="<?php echo base_url(); ?>tenant_soa"><i class="fa fa-print"></i> MY SOA</a></li>
                        <li class=""><a href="<?php echo base_url(); ?>rr_ledger"><i class="fa fa-book" aria-hidden="true"></i> MY LEDGER</a></li>
                        <li class=""><a href="<?php echo base_url(); ?>utilityReadings"><i class="fa fa-clock-o" aria-hidden="true"></i> UTILITY READINGS</a></li>
                        <li class=""><a href="<?php echo base_url(); ?>paymentAdvice"><i class="fa fa-clock-o" aria-hidden="true"></i> Payment Advice</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class=""><a href="#"><i></i> Welcome: <?php echo $this->session->userdata('trade_name'); ?></a></li>
                        <li class=""><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#usettings_modal" ng-click="user_credentials('<?php echo base_url(); ?>user_credentials')"><i class="fa fa-cog"></i> Settings</a></li>
                        <li class=""><a href="<?php echo base_url(); ?>logout"><i class="fa fa-power-off"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div> <!-- /.container-fluid -->
    </nav>
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

    <!-- User Setting Modal -->
    <div class="modal fade" id="usettings_modal">
        <div class="modal-dialog modal-medium">
            <div class="modal-content" ng-repeat="user in details">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-cog"></i> User Settings</h4>
                </div>
                <form action="{{ '../portal/update_usettings_tenant/' + user.id }}" method="post" name="frm_usettings" id="frm_usettings">
                    <!-- <form action="<?php echo base_url(); ?>updateUserSettings/{{user.id}}" method="post" name="frm_usettings" id="frm_usettings"> -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="floor_area" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>Username</label>
                                        <div class="col-md-6">
                                            <input type="text" required class="form-control" ng-model="user.username" id="username" name="username" ng-minlength="5" autocomplete="off" is-unique-update is-unique-id="{{user.id}}" is-unique-api="<?php echo base_url(); ?>portal/verify_username_update/">
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
                                            <input type="password" required class="form-control" ng-model="old_pass" id="old_pass" name="old_pass" autocomplete="off" is-unique-update is-unique-id="{{user.id}}" is-unique-api="<?php echo base_url(); ?>portal/check_oldpass_tenant/">
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
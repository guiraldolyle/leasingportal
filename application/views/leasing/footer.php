<div id="app-loader" class="modal fade in" tabindex="-1" role="dialog" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog" style="margin-top: 200px; background: rgb(255,255,255); border-radius: 10px; width: 400px;">
        <div class="modal-body">
            <div class="" style="text-align: center;">
                <div>
                    <center>
                        <img width="50px;" src="http://172.16.46.135/AGC-PMS/img/spinner2.svg">
                    </center>
                </div>
                <div class="loader-text" style="margin-top: 5px;">
                    <center>
                        <b id="app-loader-msg">Collecting data. Please wait ...</b>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //eeshiro_revisions
    window.$base_url = `<?= base_url() ?>`
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.noty.packaged.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/notification.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/angular.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/angular-sanitize.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/massautocomplete.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/Chart.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/angular-chart.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/angular.mask.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/fileinput.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/dynamic-element.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/angular-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/angular-clock.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ajax.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/mask.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ng-table.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/app.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/directives.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/editable-grid.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/angular-input-masks-standalone.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/treeview.js"></script>
<script type="text/javascript" src='<?php echo base_url(); ?>js/main.js'></script>
<script src="<?= base_url('js/bootbox.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('js/myplugin.js') ?>" type="text/javascript"></script>

<!-- eeshiro_revisions -->
<script type="text/javascript" src="<?= base_url(); ?>js/controller/invoicing.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/controller/soa.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/controller/payment.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/controller/preop_payment.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/controller/advance_payment.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/controller/invoice_override_history.js"></script>
<!-- eeshiro_revisions -->

<script type="text/javascript">
    <?php switch ($flashdata):
        case "Invalid Login": ?>
            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Invalid Login</div>');
            <?php break; ?>

        <?php
        case "Added": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Successfully Saved.</div>');
            <?php break; ?>

        <?php
        case "Not saved": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Transaction not saved. Error Occured.</div>');
            <?php break; ?>

        <?php
        case "Deleted": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Successfully Deleted.</div>');
            <?php break; ?>

        <?php
        case "Error": ?>
            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Error Occured. Action not saved.</div>');
            <?php break; ?>

        <?php
        case "Required": ?>
            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Pleas Fill out all Required Fields</div>');
            <?php break; ?>

        <?php
        case "Updated": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Changes Successfully Updated.</div>');
            <?php break; ?>

        <?php
        case "Terminated": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Contract Successfully Terminated.</div>');
            <?php break; ?>

        <?php
        case "Blocked": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> User Successfully Blocked.</div>');
            <?php break; ?>

        <?php
        case "Activated": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> User Successfully Activated.</div>');
            <?php break; ?>

        <?php
        case "Reset": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Password Successfully Reset.</div>');
            <?php break; ?>

        <?php
        case "Invalid Key": ?>
            generate('error', '<div class="activity-item"> <i class="fa fa-times text-alert"></i> Invalid Manager&#39;s Key</div>');
            <?php break; ?>

        <?php
        case "IncorrectMangersKey": ?>
            generate('error', "Incorrect Manager's Key.");
            <?php break; ?>

        <?php
        case "Approved": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-thumbs-up text-success"></i> Successfully Approved.</div>');
            <?php break; ?>

        <?php
        case "Denied": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-thumbs-down text-success"></i> Successfully Denied.</div>');
            <?php break; ?>

            // ============================ KING ARTHURS REVISION
        <?php
        case "Revoked": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-thumbs-down text-success"></i> Successfully Revoked.</div>');
            <?php break; ?>
            // ============================ KING ARTHURS REVISION END

        <?php
        case "Restored": ?>
            generate('success', "Successfully Restored.");
            <?php break; ?>
        <?php
        case "Restriction": ?>
            generate('error', "Not allowed. User Restriction");
            <?php break; ?>
        <?php
        case "Posted": ?>
            generate('success', "Successfully posted.");
            <?php break; ?>
        <?php
        case "Saved": ?>
            generate('success', "Successfully Saved.");
            <?php break; ?>
        <?php
        case "DB Error": ?>
            generate('error', "Database Error. Please Contact the System Admin.");
            <?php break; ?>
        <?php
        case "Duplicated": ?>
            generate('error', "Data Already Exist.");
            <?php break; ?>
        <?php
        case "SOA cannot be deleted": ?>
            generate('error', "SOA cannot be deleted.");
            <?php break; ?>

            // =========================== KING ARTHURS REVISION
        <?php
        case "Deactivated": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Successfully Deactivated.</div>');
            <?php break; ?>
            // =========================== KING ARTHURS REVISION END
    <?php endswitch; ?>
</script>

</html>
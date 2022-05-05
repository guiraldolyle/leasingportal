    <!--main content end-->
    <!--footer start-->
    <footer class="site-footer">
        <div class="text-center">
            2022 - PORTAL ADMIN
            <a href="#" class="go-top">
                <i class="fa fa-angle-up"></i>
            </a>
        </div>
    </footer>
    <!--footer end-->
    </section>

<script>
    window.$base_url = `<?= base_url() ?>`
</script>

    <!-- javascript libraries and plugins -->
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.inputmask.bundle.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.dreamalert.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.nicescroll.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo base_url(); ?>js/common-scripts.js"></script>
    <script src="<?php echo base_url(); ?>js/datatables.js"></script>
    <script src="<?php echo base_url(); ?>/js/bootstrap-toggle.min.js"></script>
    <script src="<?php echo base_url(); ?>js/admin-scripts.js"></script>
    <script src="<?php echo base_url(); ?>js/sweetalert2/sweetalert2.js"></script>
    <script type="text/javascript" src = "<?php echo base_url(); ?>js/notification.js"></script>

    <script type="text/javascript">
        $(function() {
            $("#due_date").datepicker({
                dateFormat: 'yy-mm-dd'
            }).val();
            $("#collection_date").datepicker({
                dateFormat: 'yy-mm-dd'
            }).val();
            $('#datatable').DataTable();

        });
    </script>

    <script type="text/javascript">
        <?php switch ($flashdata):
            case "Deleted": ?>
                alertMe('success', 'Successfully Deleted');
                <?php break; ?>
            <?php
            case "Updated": ?>
                alertMe('success', 'Successfully Updated');
                <?php break; ?>
            <?php
            case "Success": ?>
                alertMe('success', 'Operation Completed');
                <?php break; ?>
            <?php
            case "Error": ?>
                alertMe('error', 'Operation Completed');
                <?php break; ?>
        <?php endswitch; ?>
    </script>

    <script type="text/javascript">
    <?php switch($flashdata):
        case "Invalid Login": ?>
            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Invalid Login</div>');
        <?php break;?>

        <?php case "Added": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Successfully Saved.</div>');
        <?php break;?>

        <?php case "Not saved": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Transaction not saved. Error Occured.</div>');
        <?php break;?>

        <?php case "Deleted": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Successfully Deleted.</div>');
        <?php break;?>

        <?php case "Error": ?>
            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Error Occured. Action not saved.</div>');
        <?php break;?>

        <?php case "Required": ?>
            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Pleas Fill out all Required Fields</div>');
        <?php break;?>

        <?php case "Updated": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Changes Successfully Updated.</div>');
        <?php break;?>

        <?php case "Terminated": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Contract Successfully Terminated.</div>');
        <?php break;?>

        <?php case "Blocked": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> User Successfully Blocked.</div>');
        <?php break;?>

        <?php case "Activated": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> User Successfully Activated.</div>');
        <?php break;?>

        <?php case "Reset": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Password Successfully Reset.</div>');
        <?php break;?>

        <?php case "Invalid Key": ?>
            generate('error', '<div class="activity-item"> <i class="fa fa-times text-alert"></i> Invalid Manager&#39;s Key</div>');
        <?php break;?>

        <?php case "IncorrectMangersKey": ?>
            generate('error', "Incorrect Manager's Key.");
        <?php break;?>

        <?php case "Approved": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-thumbs-up text-success"></i> Successfully Approved.</div>');
        <?php break;?>

        <?php case "Denied": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-thumbs-down text-success"></i> Successfully Denied.</div>');
        <?php break;?>

        // ============================ KING ARTHURS REVISION
        <?php case "Revoked": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-thumbs-down text-success"></i> Successfully Revoked.</div>');
        <?php break;?>
        // ============================ KING ARTHURS REVISION END

        <?php case "Restored": ?>
            generate('success', "Successfully Restored.");
        <?php break;?>
        <?php case "Restriction": ?>
            generate('error', "Not allowed. User Restriction");
        <?php break;?>
        <?php case "Posted": ?>
            generate('success', "Successfully posted.");
        <?php break;?>
        <?php case "Saved": ?>
            generate('success', "Successfully Saved.");
        <?php break;?>
        <?php case "DB Error": ?>
            generate('error', "Database Error. Please Contact the System Admin.");
        <?php break;?>
        <?php case "Duplicated": ?>
            generate('error', "Data Already Exist.");
        <?php break;?>
        <?php case "SOA cannot be deleted": ?>
            generate('error', "SOA cannot be deleted.");
        <?php break;?>

         // =========================== KING ARTHURS REVISION
        <?php case "Deactivated": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Successfully Deactivated.</div>');
        <?php break;?>
        // =========================== KING ARTHURS REVISION END
    <?php endswitch;?>
</script> 
    </body>

    </html>
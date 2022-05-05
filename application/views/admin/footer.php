    
        <!--main content end-->
          <!--footer start-->
            <footer class="site-footer">
                <div class="text-center">
                    2018 - AGC-PMS
                    <a href="#" class="go-top">
                        <i class="fa fa-angle-up"></i>
                    </a>
                </div>
            </footer>
            <!--footer end-->
        </section>

            
        
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
    
        <script type="text/javascript">
            $(function() {
                $( "#due_date" ).datepicker({ dateFormat: 'yy-mm-dd' }).val();
                $( "#collection_date" ).datepicker({ dateFormat: 'yy-mm-dd' }).val();
                $('#datatable').DataTable();
                
            });
        </script>

        <script type="text/javascript">
            <?php switch($flashdata):
                case "Deleted": ?>
                    alertMe('success', 'Successfully Deleted');
                <?php break;?>
                <?php case "Updated": ?>
                    alertMe('success', 'Successfully Updated');
                <?php break;?>
                <?php case "Success": ?>
                    alertMe('success', 'Operation Completed');
                <?php break;?>
            <?php endswitch;?>
        </script>
    </body>
</html>


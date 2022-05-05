        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5><i class="fas fa-cogs"></i> Account Settings</h5>
            </div>
            <div class="p-3 ml-2">
                <div class="row mb-2">
                    <a href="#"><i class="fas fa-key"></i> Change Password</a>
                </div>
                <div class="row">
                    <a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                <!-- Anything you want -->
            </div>
            <!-- Default to the left -->
            <strong>Leasing Portal 1.2 - 2022</strong>
        </footer>
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->
        <script>
            //eeshiro_revisions
            window.$base_url = `<?= base_url() ?>`
        </script>
        <!-- jQuery -->
        <script src="<?php echo base_url(); ?>plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="<?php echo base_url(); ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo base_url(); ?>dist/js/adminlte.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <!-- <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script> -->
        <script src="<?php echo base_url(); ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/jszip/jszip.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/pdfmake/pdfmake.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/pdfmake/vfs_fonts.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

        <!-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> -->
        <!-- ANGULAR JS-->
        <script type="text/javascript" src="<?php echo base_url(); ?>js/angular.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>dist/angularjs/root.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>dist/angularjs/soa.js"></script>

        <script>
            $(document).ready(function() {
                $('#mySoaTable').DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                });
                $('#myLedgerTable').DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                });
                $('#utilityReadings').DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                });
            });
            // $(function() {
            //     $("#mySoaTable").DataTable({
            //         "responsive": true,
            //         "lengthChange": false,
            //         "autoWidth": false,
            //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            //     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            //     $("#myLedgerTable").DataTable({
            //         "responsive": true,
            //         "lengthChange": false,
            //         "autoWidth": false,
            //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            //         "columnDefs": [{
            //                 "width": "10%",
            //                 "targets": 0
            //             }, {
            //                 "width": "15%",
            //                 "targets": 2
            //             },
            //             {
            //                 "width": "10%",
            //                 "targets": 3
            //             },
            //             {
            //                 "width": "10%",
            //                 "targets": 4
            //             }, {
            //                 "width": "10%",
            //                 "targets": 5
            //             }, {
            //                 "width": "10%",
            //                 "targets": 6
            //             }, {
            //                 "width": "10%",
            //                 "targets": 7
            //             }, {
            //                 "width": "15%",
            //                 "targets": 8
            //             }
            //         ]
            //     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            //     // $('#myLedgerTable').DataTable({
            //     //     "paging": true,
            //     //     "lengthChange": false,
            //     //     "searching": false,
            //     //     "ordering": true,
            //     //     "info": true,
            //     //     "autoWidth": false,
            //     //     "responsive": true,
            //     // });
            // });
        </script>
        </body>

        </html>
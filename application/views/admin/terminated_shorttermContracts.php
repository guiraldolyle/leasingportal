
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <!-- INLINE FORM ELELEMNTS -->
            <div class="row mt">
                <div class="col-md-12">
                    <div class = "panel panel-theme">
                        <div class="panel-heading"><i class="fa fa-list"></i> Short Term Terminated Contracts</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <section id="unseen">
                                        <table class="table table-striped table-advance table-hover" id = "datatable">
                                            <thead> 
                                                <tr> 
                                                    <th>Tenant ID</th>
                                                    <th>Trade Name</th> 
                                                    <th>Date of Termination</th>
                                                    <th>Reason of Termination</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($result as $tenant): ?>
                                                <tr>
                                                    <td><?php echo $tenant['tenant_id']; ?></td>
                                                    <td><?php echo $tenant['trade_name']; ?></td>
                                                    <td><?php echo $tenant['termination_date']; ?></td>
                                                    <td><?php echo $tenant['reason']; ?></td>
                                                    <td>
                                                        <span class="btn btn-theme btn-xs" data-toggle="tooltip" title="Restore" onClick = "restore_contract('<?php echo $tenant['id'] ?>')"><i class="fa fa-recycle"></i></span>
                                                    </td>
                                                </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>    
                                    </section>
                                </div>  
                            </div>
                        </div><!-- /panel-body -->
                    </div> <!-- /panel-theme -->
                </div><!-- /col-lg-12 -->
            </div><!-- /row -->
        </section><! --/wrapper -->
    </section><!-- /MAIN CONTENT -->


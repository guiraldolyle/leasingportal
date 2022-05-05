
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <!-- INLINE FORM ELELEMNTS -->
            <div class="row mt">
                <div class="col-md-12">
                    <div class = "panel panel-theme">
                        <div class="panel-heading"><i class="fa fa-list"></i> Short Term Prospects</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <section id="unseen">
                                        <table class="table table-striped table-advance table-hover" id = "datatable">
                                            <thead> 
                                                <tr> 
                                                    <th>Trade Name</th>
                                                    <th>Corporate Name</th> 
                                                    <th>Property/Store</th>
                                                    <th>Contact Person</th>
                                                    <th>Contact No.</th>
                                                    <th>Status</th>
                                                    <th>Request Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead
                                            <tbody>
                                                <?php foreach ($result as $prospect): ?>
                                                <tr>
                                                    <td><?php echo $prospect['trade_name']; ?></td>
                                                    <td><?php echo $prospect['corporate_name']; ?></td>
                                                    <td><?php echo $prospect['store_name']; ?></td>
                                                    <td><?php echo $prospect['contact_person']; ?></td>
                                                    <td><?php echo $prospect['contact_number']; ?></td>
                                                    <td>
                                                        <?php if ($prospect['status'] == 'Approved'): ?>
                                                            <span class="badge bg-info"><i class = "fa fa-check"><?php echo $prospect['status']; ?></i></span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning"><?php echo $prospect['status']; ?></span>
                                                        <?php endif ?>
                                                        
                                                    </td>
                                                    <td><?php echo $prospect['request_date']; ?></td>
                                                    <td>
                                                        <?php if ($prospect['status'] == 'Pending'): ?>
                                                            <span  style = "margin-right:.3rem" data-toggle="tooltip" title="Approve" class="btn btn-primary btn-xs" onClick = "approve_prospect('<?php echo $prospect['id'] ?>')"><i class="fa fa-thumbs-up"></i></span>
                                                        <?php else: ?>
                                                            <span  style = "margin-right:.3rem" data-toggle="tooltip" title="Deny" class="btn btn-danger btn-xs" onClick = "deny_prospect('<?php echo $prospect['id'] ?>')"><i class="fa fa-thumbs-down"></i></span>
                                                        <?php endif ?>
                                                        <span class="btn btn-theme btn-xs" data-toggle="tooltip" title="View" onClick = "view_prospect('<?php echo $prospect['id'] ?>')"><i class="fa fa-eye"></i></span>
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


    <div class="modal fade" id = "modal_viewProspect" style="z-index: 1080 !important;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-eye"></i> View Prospect Details</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal tasi-form" action="#" method="post" id = "view_TenantDetails">
                                
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
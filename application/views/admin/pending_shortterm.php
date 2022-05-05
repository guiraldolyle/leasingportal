
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <!-- <h3><i class="fa fa-angle-right"></i> Long Term Active Tenants</h3> -->
            <!-- INLINE FORM ELELEMNTS -->
            <div class="row mt">
                <div class="col-md-12">
                    <div class = "panel panel-theme">
                        <div class="panel-heading"><i class="fa fa-list"></i> Short Term Active Tenants</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <section id="unseen">
                                        <table class="table table-striped table-advance table-hover" id = "datatable">
                                            <thead> 
                                                <tr> 
                                                    <th>Tenant ID</th>
                                                    <th>Trade Name</th> 
                                                    <th>Property/Store</th>
                                                    <th>Location Code</th>
                                                    <th>Lessee Type</th>
                                                    <th>Contact Person</th>
                                                    <th>Contract Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead
                                            <tbody>
                                                <?php foreach ($result as $tenant): ?>
                                                <tr>
                                                    <td><?php echo $tenant['tenant_id']; ?></td>
                                                    <td><?php echo $tenant['trade_name']; ?></td>
                                                    <td><?php echo $tenant['store_name']; ?></td>
                                                    <td><?php echo $tenant['location_code']; ?></td>
                                                    <td><?php echo $tenant['leasee_type']; ?></td>
                                                    <td><?php echo $tenant['contact_person']; ?></td>
                                                    <td><?php echo $tenant['opening_date'] . " Until " .  $tenant['expiry_date']; ?></td>
                                                    <td>
                                                       <span  style = "margin-right:.3rem" data-toggle="tooltip" title="Edit" class="btn btn-primary btn-xs" onClick = "update_terms('<?php echo $tenant['id'] ?>')"><i class="fa fa-pencil"></i></span>
                                                        <span class="btn btn-theme btn-xs" data-toggle="tooltip" title="View" onClick = "view_terms('<?php echo $tenant['id'] ?>')"><i class="fa fa-eye"></i></span>
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


    <div class="modal fade" id = "modal_viewTerms" style="z-index: 1080 !important;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-eye"></i> View Tenant Details</h4>
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

    <div class="modal fade" id = "modal_updateTerms" style="z-index: 1080 !important;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Update Tenant Details</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal tasi-form" action="<?php echo base_url(); ?>index.php/admin/update_tenantTerms/longTerm" method="post" id = "frm_updateTenantDetails">
                                
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>



    
    



<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- INLINE FORM ELELEMNTS -->
        <div class="row mt">
            <div class="col-md-12">
                <div class="panel panel-theme">
                    <div class="panel-heading"><i class="fa fa-list"></i> User Management <button type="" class="btn btn-primary btn-xs float-right" data-toggle="modal" data-target="#addUser">Add New User</button></div>
                    <div class="panel-body">
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <section id="unseen">
                                    <table class="table table-striped table-advance table-hover" id="userTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Tenant ID</th>
                                                <th class="text-center">Username</th>
                                                <th class="text-center">Tenant Type</th>
                                                <th class="text-center" style="width: 200px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tenantUsers as $users) : ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $users['id']; ?></td>
                                                    <td class="text-center"><?php echo $users['tenant_id']; ?></td>
                                                    <td class="text-center"><?php echo $users['username']; ?></td>
                                                    <td class="text-center"><?php echo $users['user_type']; ?></td>
                                                    <td class="text-center">
                                                        <span style="margin-right:.3rem" data-toggle="modal" title="View" class="btn btn-primary btn-xs" data-target="#viewUser">View</span>
                                                        <span style="margin-right:.3rem" data-toggle="modal" title="Edit" class="btn btn-info btn-xs" data-target="#editUser">Edit</span>
                                                        <span style="margin-right:.3rem" title="Deactivate" class="btn btn-danger btn-xs" onClick="approve_prospect('<?php echo $users['id'] ?>')">Deactivate</span>
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
    </section>
    <! --/wrapper -->
</section><!-- /MAIN CONTENT -->


<!-- VIEW USER -->
<div class="modal fade" id="viewUser" style="z-index: 1080 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-eye"></i> View User Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal tasi-form" action="#" method="post" id="view_TenantDetails">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Trade Name</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Tenant ID</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Username</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Password</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Contact Person </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Contact Number </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Remarks </b></label>
                                            <div class="col-lg-8">
                                                <textarea rows="3" readonly class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Request Date </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Status </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Prepared By </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<!-- EDIT USER -->
<div class="modal fade" id="editUser" style="z-index: 1080 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-eye"></i> View User Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal tasi-form" action="#" method="post" id="view_TenantDetails">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Trade Name</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Tenant ID</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Username</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Lessee Type</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>First Category </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Second Category </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Third Category </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Contact Person </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Contact Number </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Remarks </b></label>
                                            <div class="col-lg-8">
                                                <textarea rows="3" readonly class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Request Date </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Status </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Prepared By </b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<!-- ADD USER -->
<div class="modal fade" id="addUser" style="z-index: 1080 !important;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-eye"></i> View User Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal tasi-form" action="#" method="post" id="view_TenantDetails">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>User Type</b></label>
                                            <div class="col-lg-8">
                                                <select name="" class="form-control">
                                                    <option>Tenant</option>
                                                    <option>Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Trade Name</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Tenant ID</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Username</b></label>
                                            <div class="col-lg-8">
                                                <input type="text" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Password</b></label>
                                            <div class="col-lg-8">
                                                <input type="password" value="" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label col-lg-4"><b>Confirm Password</b></label>
                                            <div class="col-lg-8">
                                                <input type="password" value="" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
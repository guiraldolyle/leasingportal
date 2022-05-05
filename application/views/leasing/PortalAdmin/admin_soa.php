<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- INLINE FORM ELELEMNTS -->
        <div class="row mt">
            <div class="col-md-12">
                <div class="panel panel-theme">
                    <div class="panel-heading"><i class="fa fa-list"></i> Upload SOA Data</div>
                    <div class="panel-body">
                        <form class="form-horizontal tasi-form" action="#" method="post" id="frm_getLatestSoa">
                            <div class="row">
                                <div class="col-md-6">
                                    <section id="unseen">
                                        <div class="">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="tenant_id"><b>Tenant Type: </b></label>
                                                <div class="col-lg-6">
                                                    <!-- <input type="text" autocomplete="off" name = "tenant_id" required class="form-control" id="tenant_id"> -->
                                                    <select class="form-control" id="tenant_type" name="tenant_type" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option value="LT">Long Term</option>
                                                        <option value="ST">Short Term</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="doc_no"><b>Store: </b></label>
                                                <div class="col-lg-6">
                                                    <!-- <input type="text" required autocomplete="off" name = "doc_no" class="form-control" id="doc_no"> -->
                                                    <select class="form-control" id="store" name="store" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option value="AM">Alturas Mall</option>
                                                        <option value="ACT">Alta Citta</option>
                                                        <option value="ICM">Island City Mall</option>
                                                        <option value="PM">Plaza Marcela</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div><!-- /form-panel -->
                                    </section>
                                </div>

                                <div class="col-md-6">
                                    <section id="unseen">
                                        <div class="">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="start_date"><b>Start Date: </b></label>
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                        <datepicker>
                                                            <input type="text" required placeholder="Choose a date" class="form-control" id="start_date" name="start_date" autocomplete="off">
                                                        </datepicker>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="last_date"><b>Last Date: </b></label>
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                        <datepicker>
                                                            <input type="text" required placeholder="Choose a date" class="form-control" id="last_date" name="last_date" autocomplete="off">
                                                        </datepicker>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-4">
                                                    <button type="submit" class="btn btn-theme03 btn-sm btn-block">Get SOAs</button>
                                                    <!-- <button type="button" class="btn btn-theme03 btn-sm btn-block" onclick="clickthis()">click</button> -->
                                                </div>
                                            </div>
                                        </div><!-- /form-panel -->
                                    </section>
                                </div>
                            </div>
                        </form>

                        <form method="POST" id="uploadForm">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 mt-5 mb-5 form-group">
                                        <button class="btn btn-default btn-medium pull-center" id="uploadSelected" name="uploadSelected" class="uploadSelected"><i class="fa fa-upload" aria-hidden="true"></i> Upload Selected</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <section id="unseen">
                                        <table class="table table-striped table-advance table-hover" id="soa_table">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="headerCheck" name="headerCheck"></th>
                                                    <th>Trade Name</th>
                                                    <th>Tenant ID</th>
                                                    <th>File Name</th>
                                                    <th>SOA No</th>
                                                    <th>Billing Period</th>
                                                    <th>Amount Payable</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </section>
                                </div>
                            </div>
                        </form>
                    </div><!-- /panel-body -->
                </div> <!-- /panel-theme -->
            </div><!-- /col-lg-12 -->
        </div><!-- /row -->
    </section>
    <! --/wrapper -->
</section><!-- /MAIN CONTENT -->


<div class="modal fade" id="modal_viewProspect" style="z-index: 1080 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-eye"></i> View Prospect Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal tasi-form" action="#" method="post" id="view_TenantDetails">

                        </form>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
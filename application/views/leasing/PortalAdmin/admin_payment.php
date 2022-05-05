<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- INLINE FORM ELELEMNTS -->
        <div class="row mt">
            <div class="col-md-12">
                <div class="panel panel-theme">
                    <div class="panel-heading"><i class="fa fa-list"></i> Upload Payment Data</div>
                    <div class="panel-body">
                        <form class="form-horizontal tasi-form" action="#" method="post" id="frm_getPayments">
                            <div class="row">
                                <div class="col-md-10">
                                    <section id="unseen">
                                        <div class="">
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label col-lg-3 col-md-offset-1" for="search_tradeName"><b>Search by Trade Name:</b></label>
                                                <div class="col-lg-6">
                                                    <input type="text" autocomplete="on" name="search_tradeName" required class="form-control" id="search_tradeName">
                                                    <span id="searchclear" class="fa fa-close"></span>
                                                </div>
                                            </div>
                                        </div><!-- /form-panel -->
                                    </section>
                                </div>

                                <div class="col-md-10">
                                    <section id="unseen">
                                        <div class="">
                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-4">
                                                    <button type="submit" class="btn btn-theme03 btn-sm btn-block">Get Payments</button>
                                                </div>
                                            </div>
                                        </div><!-- /form-panel -->
                                    </section>
                                </div>
                            </div>
                        </form>
                        <form method="POST" id="uploadFormPayment">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 mt-5 mb-5 form-group">
                                        <button class="btn btn-default btn-medium pull-center" id="uploadSelectedPayment" name="uploadSelectedPayment" class="uploadSelectedPayment"><i class="fa fa-upload" aria-hidden="true"></i> Upload Selected</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <section id="unseen">
                                        <table class="table table-striped table-advance table-hover" id="payment_table">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="headerCheckPayment" name="headerCheckPayment"></th>
                                                    <th>Receipt No</th>
                                                    <th>Description</th>
                                                    <th>Amount Paid</th>
                                                    <th>Check No</th>
                                                    <th>Check Date</th>
                                                    <th>Payee</th>
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
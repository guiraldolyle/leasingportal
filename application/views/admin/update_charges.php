
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <!-- INLINE FORM ELELEMNTS -->
            <div class="row mt">
                <div class="col-md-10 col-md-offset-1">
                    <div class = "panel panel-theme">
                        <div class="panel-heading"><i class="fa fa-pencil"></i> Update Charges</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <section id="unseen">
                                        <div class="form-panel">
                                            <form class="form-horizontal tasi-form" action="#" method="post" id = "frm_updateCharges">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="search_tradeName"><b>Search by Trade Name:</b></label>
                                                    <div class="col-lg-6">
                                                        <input type="text" autocomplete="on" name = "search_tradeName" required class="form-control" id="search_tradeName">
                                                        <span id="searchclear" class="fa fa-close"></span>
                                                    </div>
                                                </div>
                                                <div id="details">
                                                    <!-- Code Here! -->
                                                </div>
                                            </form>
                                        </div><!-- /form-panel -->
                                    </section>
                                </div>  
                            </div>
                        </div><!-- /panel-body -->
                    </div> <!-- /panel-theme -->
                </div><!-- /col-lg-12 -->
            </div><!-- /row -->
        </section><! --/wrapper -->
    </section><!-- /MAIN CONTENT -->




    <div class="modal fade" id = "modal_updateCharges" style="z-index: 1080 !important;">
        <div class="modal-dialog modal-medium">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Charges</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" id = "chargeDetails">
                                <form class = "form-horizontal tasi-form" action = "" id = "frm_updateCharge" method = "post">

                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>

    



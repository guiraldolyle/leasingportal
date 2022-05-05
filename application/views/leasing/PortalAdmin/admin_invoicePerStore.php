
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            
            <!-- INLINE FORM ELELEMNTS -->
            <div class="row mt">
                <div class="col-md-10 col-md-offset-1">
                    <div class = "panel panel-theme">
                        <div class="panel-heading"><i class="fa fa-pencil"></i> Upload Invoice Per Store</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <section id="unseen">
                                        <div class="form-panel">
                                            <form class="form-horizontal tasi-form" action="#" method="post" id = "frm_cancelSoa">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="doc_no"><b>Store: </b></label>
                                                    <div class="col-lg-4">
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

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="start_date"><b>Start Date: </b></label>
                                                    <div class="col-lg-4">
                                                        <div class="input-group">
                                                            <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                            <datepicker>
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    placeholder="Choose a date"
                                                                    class="form-control"
                                                                    id="start_date"
                                                                    name = "start_date"
                                                                    autocomplete="off">
                                                             </datepicker>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="last_date"><b>Last Date: </b></label>
                                                    <div class="col-lg-4">
                                                        <div class="input-group">
                                                            <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                            <datepicker>
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    placeholder="Choose a date"
                                                                    class="form-control"
                                                                    id="last_date"
                                                                    name = "last_date"
                                                                    autocomplete="off">
                                                             </datepicker>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group">
                                                    <div class="col-md-4 col-md-offset-4">
                                                        <button type = "submit" class="btn btn-theme03 btn-lg btn-block">Upload</button>
                                                    </div>
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

      
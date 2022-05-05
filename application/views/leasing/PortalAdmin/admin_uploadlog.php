<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- INLINE FORM ELELEMNTS -->
        <div class="row mt">
            <div class="col-md-12">
                <div class="panel panel-theme">
                    <div class="panel-heading"><i class="fa fa-list"></i> Uploading History</div>
                    <div class="panel-body">
                        <form class="form-horizontal tasi-form" action="#" method="post" id="frm_history">
                            <div class="row">
                                <div class="col-md-6">
                                    <section id="unseen">
                                        <div class="">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="start_date"><b>Date: </b></label>
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                        <datepicker>
                                                            <input type="text" required placeholder="Choose a date" class="form-control" id="start_date" name="start_date" autocomplete="off">
                                                        </datepicker>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- /form-panel -->
                                    </section>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <section id="unseen">
                                    <table class="table table-striped table-advance table-hover" id="upload_log">
                                        <thead>
                                            <tr>
                                                <th>Type Uploaded</th>
                                                <th>Upload Status</th>
                                                <th>Info</th>
                                                <th>Date Uploaded</th>
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($history as $value): ?>
                                            <tr>
                                                <td colspan="" rowspan="" headers=""><?php echo $value['type_uploaded']?></td>
                                                <td colspan="" rowspan="" headers=""><?php echo $value['upload_status']?></td>
                                                <td colspan="" rowspan="" headers=""><?php echo $value['status_message']?></td>
                                                <td colspan="" rowspan="" headers=""><?php echo $value['date_uploaded']?></td>
                                                <td colspan="" rowspan="" headers=""><?php echo $value['username']?></td>
                                            </tr>
                                            <?php endforeach;?>
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

    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <!-- INLINE FORM ELELEMNTS -->
            <div class="row mt">
                <div class="col-md-10 col-md-offset-1">

                    <div class = "panel panel-theme">
                        <div class="panel-heading"><i class="fa fa-pencil"></i> Change Receipt No.</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <section id="unseen">
                                        <div class="form-panel">
                                            <form class="form-horizontal tasi-form" action="#" method="post" id = "frm_changeBankTagging">
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="tenant_id"><b>Tenant ID</b></label>
                                                    <div class="col-lg-4">
                                                        <input type="text" autocomplete="off" name = "tenant_id" required class="form-control" id="tenant_id">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="or_number"><b>OR#</b></label>
                                                    <div class="col-lg-4">
                                                        <input type="text" required autocomplete="off" name = "or_number" class="form-control" id="or_number">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="bank_name"><b>Bank Code</b></label>
                                                    <div class="col-lg-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" style="padding: 0;">
                                                            <button type="button" class="btn btn-primary btn-sm" style="margin: 0;" data-toggle="modal" data-target="#bankListModal">Select</button>
                                                            </span>
                                                            <input type="text" required autocomplete="off" name = "bank_code" class="form-control" id="bank_code" readonly="">
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="bank_name"><b>Bank Name</b></label>
                                                    <div class="col-lg-4">
                                                        <input type="text" required autocomplete="off" name = "bank_name" class="form-control" id="bank_name" readonly="">
                                                    </div>
                                                </div>
                                               
                                                <br>
                                                <div class="form-group">
                                                    <div class="col-md-4 col-md-offset-4">
                                                        <button type = "submit" class="btn btn-theme03 btn-lg btn-block">Submit</button>
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

<!-- Modal -->
<div class="modal fade" id="bankListModal" tabindex="-1" role="dialog" aria-labelledby="bankListModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title" >Bank List</h5>
      </div>
      <div class="modal-body">
        <table class="table table-sm table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>Store</th>
                    <th>Bank Code</th>
                    <th>Bank Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banks as $bank) {?>
                    <tr data-dismiss="modal" onclick="$('#bank_code').val('<?=$bank->bank_code?>'); $('#bank_name').val('<?=$bank->bank_name?>');">
                        <td><?=$bank->store_code?></td>
                        <td><?=$bank->bank_code?></td>
                        <td><?= $bank->bank_name?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    <script type="text/javascript"></script>

      
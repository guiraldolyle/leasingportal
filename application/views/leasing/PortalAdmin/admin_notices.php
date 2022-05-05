<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- INLINE FORM ELELEMNTS -->
        <div class="row mt">
            <div class="col-md-12">
                <div class="panel panel-theme">
                    <div class="panel-heading"><i class="fa fa-list"></i> Payment Notices</div>
                    <div class="panel-body">
                        <!-- <form class="form-horizontal tasi-form" action="#" method="post" id="frm_getLatestSoa">
                            <div class="row">
                                <div class="col-md-6">
                                    <section id="unseen">
                                        <div class="">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="tenant_id"><b>Tenant Type: </b></label>
                                                <div class="col-lg-6">
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
                                                    <select class="form-control" id="store" name="store" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option value="AM">Alturas Mall</option>
                                                        <option value="ACT">Alta Citta</option>
                                                        <option value="ICM">Island City Mall</option>
                                                        <option value="PM">Plaza Marcela</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
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
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </form> -->
                        <div class="row">
                            <div class="col-md-12">
                                <section id="unseen">
                                    <table class="table table-striped table-advance table-hover" id="notice_table">
                                        <thead style="font-size: 10px;">
                                            <tr>
                                                <th>Payment Date</th>
                                                <th>Store</th>
                                                <th>Store Bank Account</th>
                                                <th>Store Account Number</th>
                                                <th>Tenant Bank Account</th>
                                                <th>Tenant Account Number</th>
                                                <th>Tenant Account Name</th>
                                                <th>Payment Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($notices as $value) : ?>
                                                <tr>
                                                    <td><?php echo $value['payment_date']; ?></td>
                                                    <td><?php echo $value['store']; ?></td>
                                                    <td><?php echo $value['bank_account']; ?></td>
                                                    <td><?php echo $value['store_account']; ?></td>
                                                    <td><?php echo $value['tenant_bank']; ?></td>
                                                    <td><?php echo $value['account_number']; ?></td>
                                                    <td><?php echo $value['account_name']; ?></td>
                                                    <td><?php echo $value['payment_type']; ?></td>
                                                    <td>
                                                        <span style="margin-right:.3rem" data-toggle="tooltip" title="Proof of Payment" class="btn btn-primary btn-xs" onClick="proof('<?php echo $value['proof_of_transfer']; ?>')">View Proof</span>
                                                        <?php if ($value['payment_type'] == 'One Location') : ?>
                                                            <span class="btn btn-theme btn-xs" data-toggle="modal" title="Post" data-target="#advices" title="View" onClick="viewAdvice('<?php echo $value['id']; ?>', '<?php echo $value['payment_type']; ?>')">View Advice</span>
                                                        <?php else : ?>
                                                            <span class="btn btn-theme btn-xs" data-toggle="modal" title="Post" data-target="#multilocation" onClick="multiAdvice('<?php echo $value['id']; ?>', '<?php echo $value['payment_type']; ?>')">View Advice</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>

                                    </table>
                                    <div class="col-md-12 mt-5">
                                        <a class="btn btn-default btn-medium pull-right" onclick="exportPaymentAdvice()"><i class="fa fa-download"></i> Export Excel</a>
                                    </div>
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


    <!-- ONE LOCATION VIEW ADVICE MODAL-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-hidden="true" id = "advices" style="z-index: 1080 !important;">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-eye"></i> Payment Advice</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal tasi-form"  method="post" id = "advicesForm">
                                <div class="col-md-6">
                                    <input type="hidden" id="adviceid" name="adviceid" readonly class="form-control">
                                    <input type="hidden" id="type" name="type" readonly class="form-control">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Tenant ID</b></label>
                                                <div class="col-lg-8">
                                                    <input type = "text" id="tenantid" name="tenantid" class="form-control" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Payment Date</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="paymentdate" name="paymentdate" readonly class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Store</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="store" name="store" readonly class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Store Bank Account</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="bankaccount" name="bankaccount" readonly class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Store Account Number</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="account" name="account" readonly class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4" ><b>Bank Account</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="t_bankaccount" name="t_bankaccount" readonly  class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4" ><b>Account Number</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="accountnumber" name="accountnumber" readonly  class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Account Name</b></label>
                                                <div class="col-lg-8">
                                                    <input type = "text" id="accountname" name="accountname" class="form-control" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>SOA No.</b></label>
                                                <div class="col-lg-8">
                                                    <input type = "text" id="soanumber" name="soanumber" class="form-control" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Total Payable</b></label>
                                                <div class="col-lg-8">
                                                    <input type = "text" id="payable" name="payable" class="form-control" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Amount Paid</b></label>
                                                <div class="col-lg-8">
                                                    <input type = "text" id="amountpaid" name="amountpaid" class="form-control" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> Post</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>


    <!-- MULTI LOCATION VIEW ADVICE MODAL-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"  id = "multilocation" style="z-index: 1080 !important;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-eye"></i> Payment Advice</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal tasi-form" action="" method="post" id = "m_advicesForm">
                                <div class="col-md-6">
                                    <input type="hidden" id="m_adviceid" name="m_adviceid" readonly class="form-control" >
                                    <input type="hidden" id="m_type" name="m_type" readonly class="form-control">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Tenant ID</b></label>
                                                <div class="col-lg-8">
                                                    <input type = "text" id="m_tenantid" name="m_tenantid" class="form-control" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Payment Date</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="m_paymentdate" name="m_paymentdate" readonly class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Store</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="m_store" name="m_store" readonly class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Store Bank Account</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="m_bankaccount" name="m_bankaccount" readonly class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Store Account Number</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="m_account" name="m_account" readonly class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4" ><b>Bank Account</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="mt_bankaccount" name="mt_bankaccount" readonly  class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4" ><b>Account Number</b></label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="m_accountnumber" name="m_accountnumber" readonly  class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Account Name</b></label>
                                                <div class="col-lg-8">
                                                    <input type = "text" id="m_accountname" name="m_accountname" class="form-control" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label col-lg-4"><b>Amount Paid</b></label>
                                                <div class="col-lg-8">
                                                    <input type = "text" id="m_amountpaid" name="m_amountpaid" class="form-control" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<hr>
                                <!-- <div class="col-md-12"> -->
                                    <div class="row container">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="control-label" ><b>SOA Applied - (FORMAT: Tenant ID_SOA No.)</b> (If multiple separate with comma ",".)</label>
                                                
                                                    <input type="text" id="soaApplied" name="soaApplied"  class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                <!-- </div> -->

                                <div class="row">
                                    <div class="col-md-12">
                                        <section id="unseen">
                                            <table class="table table-striped table-advance table-hover" id="multiTable">
                                                <thead>
                                                    <tr>
                                                        <th>Tenant ID</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="multiTableBody">
                                                </tbody>
                                            </table>
                                        </section>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> Post</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="clearTable()"> <i class="fa fa-close"></i> Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>

<div class="container" ng-controller="paymentCntrl">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-portal"><i class="fa fa-pencil-square"></i> Payment Advice</div>
            <div class="panel-body">
                <div class="col-sm-12 col-md-12 col-lg-12 mt-5">
                    <div class="row">
                        <!-- <ul class="nav nav-tabs bot-margin" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul> -->
                        <div class="tab-content ng-cloak">
                            <form action="" ng-submit="savePaymentAdvice($event)" method="post" name="frm_advice" id="frm_advice">
                                <div role="tabpanel" class="tab-pane active" id="payment">
                                    <div class="row">
                                        <div class="col-sm-10 col-md-10 col-lg-10 col-md-offset-1">
                                            <div class="row">
                                                <div class="col-sm-9 col-md-9 col-lg-9">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="storeLocation" class="col-sm-5 col-md-5 col-lg-5 control-label text-right">Store Location</label>
                                                            <div class="col-md-7">
                                                                <input type="text" readonly class="form-control" id="storeLocation" name="storeLocation" autocomplete="off" value="<?php echo $location; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="bank_code" class="col-md-5 control-label text-right">
                                                                <i class="fa fa-asterisk"></i> Store Bank Account
                                                            </label>
                                                            <div class="col-md-7">
                                                                <select class="form-control" name="bank_code" id="bank_code" required ng-model="bank_code" ng-change="getAccounts()">
                                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                                    <?php foreach ($bank as $value) : ?>
                                                                        <option><?php echo $value['bank_name']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>

                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_advice.bank_code.$dirty && frm_advice.bank_code.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="soa_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Store Account Number</label>
                                                            <div class="col-md-7">
                                                                <select name="store_account" class="form-control" required ng-model="store_account">
                                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                                    <option ng-repeat="a in sAccounts">{{a.account}}</option>
                                                                </select>

                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_advice.store_account.$dirty && frm_advice.store_account.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="t_bankaccount" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Bank Account</label>
                                                            <div class="col-md-7">
                                                                <select name="t_bankaccount" class="form-control" required ng-model="t_bankaccount">
                                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                                    <option>BANKS OF THE PHILIPPINE ISLANDS</option>
                                                                    <option>BDO</option>
                                                                    <option>CITIBANK</option>
                                                                    <option>EASTWEST</option>
                                                                    <option>FIRST CONSOLIDATED BANK</option>
                                                                    <option>LAND BANK OF THE PHILIPPINES</option>
                                                                    <option>METRO BANK</option>
                                                                    <option>PHILIPPINE BANK OF COMMUNICATIONS</option>
                                                                    <option>PNB</option>
                                                                    <option>SECURITY BANK</option>
                                                                    <option>UCPB</option>
                                                                    <option>UNION BANK</option>
                                                                </select>

                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_advice.t_bankaccount.$dirty && frm_advice.t_bankaccount.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php include './application/views/leasing/PortalPages/accountComponent.php'; ?>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="storeLocation" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Name</label>
                                                            <div class="col-md-7">
                                                                <input type="text" class="form-control" name="account_name" autocomplete="off" ng-model="account_name" required>
                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_advice.account_name.$dirty && frm_advice.account_name.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="payment_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Payment Date</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd">
                                                                        <input type="text" required readonly placeholder="Choose a date" class="form-control" ng-model="payment_date" name="payment_date" autocomplete="off">
                                                                    </datepicker>

                                                                </div>

                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_advice.payment_date.$dirty && frm_advice.payment_date.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="soa_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Payment Type</label>
                                                            <div class="col-md-7">
                                                                <select name="payment_type" class="form-control" ng-model="payment_type" ng-change="paymentType()" required>
                                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                                    <option>One Location</option>
                                                                    <option>Multi Location</option>
                                                                </select>

                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_advice.payment_type.$dirty && frm_advice.payment_type.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" ng-show="soanumber">
                                                        <div class="form-group">
                                                            <label for="soa_data" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>SOA No.</label>
                                                            <div class="col-md-7">
                                                                <select class="form-control" style="font-weight: bold;" ng-model="soa_data" name="soa_data" ng-change="getAmountSoa()">
                                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                                    <option ng-repeat="soa in soaDoc">{{soa.soa_no}}</option>
                                                                </select>

                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_advice.soa_data.$dirty && frm_advice.soa_data.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row" ng-show="soanumber">
                                                        <div class="form-group">
                                                            <label for="totalPayable" class="col-md-5 control-label text-right">Total Payable Amount</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input type="text" readonly class="form-control currency" ng-value="totalPayable|currency : ''" name="totalPayable" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" ng-show="multilocation">
                                                        <div class="form-group">
                                                            <label class="col-md-5 control-label text-right"></label>
                                                            <div class="col-md-7">
                                                                <label for="BRAmount"><i class="fa fa-asterisk"></i> Other Location/Tenant ID</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row" ng-show="multilocation">
                                                        <div class="col-sm-12 col-md-12 col-lg-12" ng-init="multi = [{}];">
                                                            <div ng-repeat="data in multi" class="row">
                                                                <div class="form-group">
                                                                    <label for="" class="col-sm-5 col-md-5 col-lg-5 control-label text-right"></label>
                                                                    <div class="col-md-5">
                                                                        <input type="text" class="form-control" ng-model="data.locations" autocomplete="off">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" ng-if="$index == 0" class="btn btn-md" ng-click="multi.push({})">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger" ng-if="$index > 0" ng-click="multi.splice($index, 1)">
                                                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" ng-show="amountpaid">
                                                        <div class="form-group">
                                                            <label for="amount_paid" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Amount Paid</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input type="text" required class="form-control currency" ng-model="pmt.amount_paid" ui-number-mask="2" autocomplete="off" name="amount_paid" id="amount_paid">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" ng-show="proofoftransfer">
                                                        <div class="form-group">
                                                            <label for="tender_typeDesc" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Proof of Transfer</label>
                                                            <div class="col-md-7">
                                                                <input type="file" id="supp_doc" name="supp_doc" multiple accept="image/*" required class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button role="submit" ng-disabled="frm_advice.$invalid" class="btn btn-large btn-primary col-md-1 col-md-offset-10" type="submit">
                                                <i class="fa fa-save"></i> Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->
</div> <!-- End of Container -->
</body>
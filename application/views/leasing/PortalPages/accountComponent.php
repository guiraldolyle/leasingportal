<div class="row" ng-if="t_bankaccount == 'BDO'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 99999 9" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'BANKS OF THE PHILIPPINE ISLANDS'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 9999 99" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'CITIBANK'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9 999999 999" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'EASTWEST'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 9999 9999" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'FIRST CONSOLIDATED BANK'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 9999 9999" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'LAND BANK OF THE PHILIPPINES'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 9999 99" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'METRO BANK'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 9999 9999 9" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'PHILIPPINE BANK OF COMMUNICATIONS'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 9999 9999" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'SECURITY BANK'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 9999 9999 9" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'UCPB'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 9999 999" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'UNION BANK'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 9999 9999" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="t_bankaccount == 'PNB'">
    <div class="form-group">
        <label for="acount_number" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tenant Account Number</label>
        <div class="col-md-7">
            <!-- <input type="number" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" required> -->
            <input type="text" class="form-control" name="acount_number" autocomplete="off" ng-model="acount_number" ui-mask="9999 9999 9999" ui-mask-placeholder ui-mask-placeholder-char="" required>
            <!-- FOR ERRORS -->
            <div class="validation-Error">
                <span ng-show="frm_advice.acount_number.$dirty && frm_advice.acount_number.$error.required">
                    <p class="error-display">This field is required.</p>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container" id = "transactionController" ng-controller="soaCntrl">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Statement of Account</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-11">
                                    <div class="row">
                                        <form action="" ng-submit="generateSoa($event)"  method="post" id="frm_soa" name = "frm_soa">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="soa_no" class="col-md-5 control-label text-right">SOA No.</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type = "text"
                                                                    required
                                                                    readonly
                                                                    class = "form-control"
                                                                    value = "<?=$soa_no?>"
                                                                    id = "soa_no"
                                                                    name = "soa_no"
                                                                    autocomplete = "off"
                                                                    style="font-weight: bold;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenancy_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenancy Type</label>
                                                            <div class="col-md-7">
                                                                <select
                                                                    class = "form-control"
                                                                    name = "tenancy_type"
                                                                    id="tenancy_type"
                                                                    ng-model = "tenancy_type"
                                                                    ng-change="populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type);"
                                                                    required>
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <option>Short Term Tenant</option>
                                                                    <option>Long Term Tenant</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
                                                            <div class="col-md-7">
                                                                <div class="">
                                                                    <div mass-autocomplete>
                                                                        <input
                                                                            onkeyup="this.value = this.value.toUpperCase();"
                                                                            required
                                                                            name = "trade_name"
                                                                            ng-model = "dirty.value"
                                                                            mass-autocomplete-item = "autocomplete_options"
                                                                            class = "form-control"
                                                                            id = "trade_name"
                                                                            ng-change="generate_soaCredentials(dirty.value, tenancy_type)"
                                                                            ng-model-options="{debounce : 400}">
                                                                    </div>
                                                                    <!-- <span class="input-group-btn">
                                                                        <button
                                                                            class = "btn btn-info"
                                                                            type = "button"
                                                                            ng-click = "generate_soaCredentials(dirty.value)"><i class = "fa fa-search"></i>
                                                                        </button>
                                                                    </span> -->
                                                                </div><!-- /input-group -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="contract_no" class="col-md-5 control-label text-right">Contract No.</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type = "text"
                                                                    required
                                                                    readonly
                                                                    class = "form-control"
                                                                    ng-model = "tenant.contract_no"
                                                                    id = "contract_no"
                                                                    name = "contract_no"
                                                                    autocomplete = "off">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenant_id" class="col-md-5 control-label text-right">Tenant ID</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class = "form-control"
                                                                    ng-model = "tenant.tenant_id"
                                                                    id = "tenant_id"
                                                                    name = "tenant_id"
                                                                    autocomplete = "off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenant_address" class="col-md-5 control-label text-right">Tenant Address</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class = "form-control"
                                                                    ng-model = "tenant.address"
                                                                    id = "tenant_address"
                                                                    name = "tenant_address"
                                                                    autocomplete = "off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF COL-MD-6 WRAPPER -->
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->

                                                    

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="billing_period" class="col-md-5 control-label text-right" ng-init = "b_period='Upon Signing of Notice'"></label>
                                                            <input type = "radio" name = "radio" id = "radio1" checked="checked" ng-model = "b_period" value="Upon Signing of Notice" required/><label> Upon Signing of Notice </label>
                                                            <input type = "radio" name = "radio" id = "radio2" ng-model = "b_period" value="Input Billing Period" /> <label>Input Billing Period</label>
                                                        </div>
                                                    </div>

                                                    <div class="row" ng-if = "b_period == 'Input Billing Period'">
                                                        <div class="form-group">
                                                            <label for="billing_period" class="col-md-5 control-label text-right">Billing Period</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    ng-disabled="!tenant || tenant.length == 0"
                                                                    onkeyup="this.value = this.value.toUpperCase();"
                                                                    type = "text"
                                                                    required
                                                                    class = "form-control"
                                                                    ng-model = "billing_period"
                                                                    id = "billing_period"
                                                                    name = "billing_period"
                                                                    autocomplete = "off">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" ng-if = "b_period == 'Upon Signing of Notice'">
                                                        <div class="form-group">
                                                            <label for="billing_period" class="col-md-5 control-label text-right">Billing Period</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    readonly
                                                                    type = "text"
                                                                    class = "form-control"
                                                                    value = "Upon Signing of Notice"
                                                                    id = "billing_period"
                                                                    name = "billing_period"
                                                                    autocomplete = "off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="curr_date" class="col-md-5 control-label text-right">Date Created</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd">
                                                                        <input
                                                                            ng-disabled="!tenant || tenant.length == 0"
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="date_created"
                                                                            name = "date_created"
                                                                            autocomplete="off"
                                                                            ng-change = "calculate_colDate(date_created); generateTenantBalances()"
                                                                            ng-model-options="{debounce : 500}">
                                                                    </datepicker>
                                                                    
                                                                </div>
                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_soa.date_created.$dirty && frm_soa.date_created.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="collection_date" class="col-md-5 control-label text-right">Collection Date</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd" date-min-limit="{{min_collectionDate}}">
                                                                        <input
                                                                            ng-disabled="!tenant || tenant.length == 0"
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="collection_date"
                                                                            id="collection_date"
                                                                            name = "collection_date"
                                                                            autocomplete="off">
                                                                    </datepicker>
                                                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_soa.collection_date.$dirty && frm_soa.collection_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="total" class="col-md-5 control-label text-right">Total Amount Due</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        required
                                                                        class="form-control currency"
                                                                        ng-value="total() | currency : ''"
                                                                        readonly
                                                                        name = "totalAmount">
                                                                </div>
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_charges.total.$dirty && frm_charges.total.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="total" class="col-md-5 control-label text-right">Advance Payment</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        required
                                                                        class="form-control currency"
                                                                        ng-value="soa.advance_payment | currency : ''"
                                                                        readonly>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE SECOND COL-MD-6 WRAPPER -->
                                            </div>
                                            <div class="row">
                                                <table class="table table-bordered" id="soa_table" style="margin-left:50px">
                                                    <thead>
                                                        <tr>
                                                            <th>Doc. Type</th>
                                                            <th>Document No.</th>
                                                            <th>Description</th>
                                                            <th>Posting Date</th>
                                                            <th>Due Date</th>
                                                            <th>Amount</th>
                                                            <th>Amount Paid</th>
                                                            <th>Balance</th>
                                                            <!-- <th >Action</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody id="soa_tbody" ng-show = "!isLoading">
                                                        <tr ng-repeat = "data in soa_docs">
                                                            <td>{{data.document_type}}</td>
                                                            <td>{{data.doc_no}}</td>
                                                            <td>{{data.description}}</td>
                                                            <td>{{data.posting_date}}</td>
                                                            <td>{{data.due_date}}</td>
                                                            <td align="right">{{data.debit | currency : '&#8369;' }}</td>
                                                            <td align="right">{{data.credit | currency : '&#8369;' }}</td>
                                                            <td align="right">{{data.balance | currency : '&#8369;'}}</td>
                                                            <!-- <td align="center">
                                                                <button 
                                                                    ng-click="removeFromSoa(data)"
                                                                    type="button" 
                                                                    class="btn btn-xs btn-danger">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td> -->
                                                        </tr>
                                                    </tbody>
                                                    <tbody ng-show = "isLoading">
                                                        <tr>
                                                            <td colspan="9">
                                                                <div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
                                                                <div class = "loader-text"><center><b>Collecting Data. Please Wait...</b></center></div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- EDITABLE GRID END ROW -->
                                            <div class="row"> <!-- ROW FOR BUTTONS -->
                                                <div class = "col-md-12 text-right">
                                                    <button type = "submit" ng-disabled = "frm_soa.$invalid || total() == 0" class = "btn btn-primary btn-medium"><i class = "fa fa-save"></i> Generate SOA</button>
                                                    <!-- <button data-toggle="modal" data-target="#sampleModal" class = "btn btn-primary btn-medium"><i class = "fa fa-save"></i> toggle</button> -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->

    <!-- Add Retro Rental Modal -->
    <div class="modal fade" id = "sampleModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Retro Rent </h4>
                </div>
                <div class="modal-body">

                    <div class="col-md-12" ng-init="sample_data = [{value1: 'hey', value2 : 'hi', value3: 'lue'}];">
                        <div ng-repeat="data in sample_data" class="row">
                            <div class="col-md-3">
                                <input type="text" ng-model="data.value1" class="form-control">
                            </div>
                            <div class="col-md-3">
                                 <input type="text" ng-model="data.value2" class="form-control">
                            </div>
                            <div class="col-md-3">
                                 <input type="text" ng-model="data.value3" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <button ng-if="$index == 0" ng-click="sample_data.push({value1: 'w', value2: 'f', value3: '3'})" class="btn btn-md">add</button>
                                <button ng-if="$index > 0" ng-click="sample_data.splice($index, 1)"  class="btn btn-md">delete</button>
                            </div>
                        </div>
                        
                    </div>
                    
                    
                </div><!-- /.modal-content -->
                <div class="modal-footer">
                    <button class="btn btn-primary" ng-click="append_basic()">
                        <i class="fa fa-save"></i> Apply
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> 
                        <i class="fa fa-close"></i> Close
                    </button>
                </div>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Basic Rental Modal -->
</div> <!-- End of Container -->
</body>

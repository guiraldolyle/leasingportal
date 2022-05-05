
<div class="container" ng-controller="preopPaymentCntrl">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Payment Using Preoperation Charges</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs bot-margin" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <form action="" ng-submit="savePayment($event)"    method="post"  name = "frm_payment" id = "frm_payment">
                            	<div role="tabpanel" class="tab-pane active" id="payment">
	                                <div class="row">
	                                	<div class="col-md-10 col-md-offset-1">
											<div class="row">
												<div class="col-md-6">
													<div class="row">
				                                        <div class="form-group">
				                                            <label for="receipt_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Document No.</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        readonly
							                                        value = "<?= $payment_docNo ;?>"
							                                        id="receipt_no"
							                                        name = "receipt_no"
							                                        class = "form-control">
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
	                                                                ng-change = "populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type)"
	                                                                onchange = "clear_paymentData()"
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

				                                            	<div mass-autocomplete>
			                                                        <input
			                                                         	onkeyup="this.value = this.value.toUpperCase();"
			                                                            required
			                                                            name = "trade_name"
			                                                            ng-model = "pmt.trade_name"
			                                                            mass-autocomplete-item = "autocomplete_options"
			                                                            class = "form-control"
			                                                            id = "trade_name"
			                                                            ng-change="generate_paymentCredentials(pmt.trade_name, tenancy_type)"
                                                                        ng-model-options="{debounce : 400}">
			                                                    </div>

				                                                <!-- <div class="input-group">
				                                                    <div mass-autocomplete>
				                                                        <input
				                                                            required
				                                                            name = "trade_name"
				                                                            ng-model = "dirty.value"
				                                                            mass-autocomplete-item = "autocomplete_options"
				                                                            class = "form-control"
				                                                            id = "trade_name">
				                                                    </div>
				                                                    <span class="input-group-btn">
				                                                        <button
				                                                            class = "btn btn-info"
				                                                            type = "button"
				                                                            ng-click = "generate_soaForPayment(dirty.value)"><i class = "fa fa-search"></i>
				                                                        </button>
				                                                    </span>
				                                                </div> -->
				                                            </div>
				                                        </div>
				                                    </div>
				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="tenant_id" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenant ID</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        readonly
							                                        ng-model = "tenant.tenant_id"
							                                        id="tenant_id"
							                                        name = "tenant_id"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>
				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="contract_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Contract No.</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        ng-model = "tenant.contract_no"
							                                        readonly
							                                        id = "contract_no"
							                                        name = "contract_no"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>

				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="contract_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenant Address</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        ng-model = "tenant.address"
							                                        readonly
							                                        name = "tenant_address"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>

				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="contract_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Corporate Name</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        ng-model = "tenant.corporate_name"
							                                        readonly
							                                        name = "corporate_name"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="payee" class="col-md-5 control-label text-right">Payee</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type = "text"
                                                                    value="<?php echo $payee; ?>"
                                                                    id="payee"
                                                                    name = "payee"
                                                                    required
                                                                    readonly
                                                                    class = "form-control">

                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_payment.payee.$dirty && frm_payment.payee.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
												</div>
												<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->

													<div class="row">
                                                        <div class="form-group">
                                                            <label for="payment_date" class="col-md-5 control-label text-right">
                                                            	<i class="fa fa-asterisk"></i> Payment Date
                                                            </label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd">
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            ng-disabled="!tenant"
                                                                            readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="pmt.payment_date"
                                                                            id="payment_date"
                                                                            name = "payment_date"
                                                                            autocomplete="off"
                                                                            ng-change="getSoaWithBalances(tenant.tenant_id, pmt.payment_date)">
                                                                    </datepicker>
                                                                    
                                                                </div>

                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_payment.payment_date.$dirty && frm_payment.payment_date.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="soa_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>SOA No.</label>
				                                            <div class="col-md-7">
				                                            	<select
				                                            		class="form-control"
				                                            		style="font-weight: bold;" 
				                                            		ng-model="pmt.soa"
				                                            		ng-options="s as s.soa_no for s in pmt.soa_docs"
				                                            		ng-change="getInvoicesBySoaNo(tenant.tenant_id, pmt.soa.soa_no)">
				                                            	</select>
				                                            	<input type="hidden" 
				                                            		name="soa_no" 
				                                            		ng-value="pmt.soa.soa_no">
				                                            </div>
				                                        </div>
				                                    </div>

				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="billing_period" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Billing Period</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        ng-value = "pmt.soa.billing_period"
							                                        id = "billing_period"
							                                        name = "billing_period"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>
											
				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="remarks" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Remarks</label>
				                                            <div class="col-md-7">
						                                        <input
							                                        type = "text"
							                                        ng-model = "remarks"
							                                        id="remarks"
							                                        name = "remarks"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tender_typeDesc" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tender Type Description</label>
                                                            <div class="col-md-7">
                                                                <select class="form-control" 
                                                                	name="tender_typeDesc" 
                                                                	required
                                                                	ng-model="pmt.preop_account"
                                                                	ng-change="getPreopBalance(tenant.tenant_id, pmt.preop_account)"
                                                                	>
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <option>Security Deposit</option>
                                                                    <option>Construction Bond</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
	                                                    <div class="form-group">
	                                                        <label for="total" class="col-md-5 control-label text-right">Pre-op Available Amount</label>
	                                                        <div class="col-md-7">
	                                                            <div class="input-group">
	                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
	                                                                <input
	                                                                    type="text"
	                                                                    ui-number-mask="2"
	                                                                    class="form-control currency"
	                                                                    ng-model="pmt.preop_amount"
	                                                                    readonly
	                                                                    autocomplete="off">
	                                                            </div>
	                                                        </div>
	                                                    </div>
	                                                </div>

				                                    <div class="row">
	                                                    <div class="form-group">
	                                                        <label for="total" class="col-md-5 control-label text-right">Total Payable Amount</label>
	                                                        <div class="col-md-7">
	                                                            <div class="input-group">
	                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
	                                                                <input
	                                                                    type="text"
	                                                                    class="form-control currency"
	                                                                    readonly
	                                                                   	ng-value="totalPayable()|currency : ''"
	                                                                    name = "total"
	                                                                    autocomplete="off">
	                                                            </div>
	                                                        </div>
	                                                    </div>
	                                                </div>
	                                                <div class="row">
	                                                    <div class="form-group">
	                                                        <label for="total" class="col-md-5 control-label text-right">Amount Paid</label>
	                                                        <div class="col-md-7">
	                                                            <div class="input-group">
	                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
	                                                                <input
	                                                                    type="text"
	                                                                    required
	                                                                    ui-number-mask="2"
	                                                                    class="form-control currency"
	                                                                    ng-model="pmt.tender_amount"
	                                                                    autocomplete="off">

	                                                                <input type="hidden" name="amount_paid" ng-value="pmt.tender_amount">
	                                                            </div>
	                                                            <div class="validation-Error">
                                                                    <span ng-show="!validTenderAmount()">
                                                                    	<div>
                                                                    		<p ng-if="pmt.tender_amount == 0" class="error-display">This field is required.</p>
                                                                        	<p ng-if="pmt.tender_amount > getMaxPaidAmount()" class="error-display">Tender Amount can't be greater than {{getMaxPaidAmount() | currency:''}}</p>
                                                                    		
                                                                    	</div>
                                                                       
                                                                    </span>
                                                                </div>
	                                                        </div>
	                                                    </div>
	                                                </div>

	                                                <div class="row" ng-if="invoices && invoices.length > 0">
				                                        <div class="form-group">
				                                            <label for="billing_period" class="col-md-5 control-label text-right"></label>
				                                            <div class="col-xs-7 animate__animated animate__fadeIn">
				                                            	<div class="container-fluid" style="padding: 0px !important; margin: 0 !important;">
				                                            		<div class="col-xs-6" style="padding: 0px !important; margin: 0 !important;">
					                                            		<button type="button" 
																		  	class="btn btn-block {{pmt.application == 'Apply To' ?  'btn-primary' : 'btn-default'}}" 
																		  	style="width: 133px; !important;"
																		  	data-toggle="modal"
																		  	data-target="#apply-to-modal">
																		  	Apply To
																		  </button>
					                                            	</div>
					                                            	<div class="col-xs-6" style="padding: 0px !important; margin: 0 !important;">
					                                            		<button type="button" 
																	  	class="btn btn-block {{pmt.application == 'Oldest to Newest' ?  'btn-primary' : 'btn-default'}}"
																	  	style="width: 133px; !important;"
																	  	ng-click="applyOldToNewest()">
																	  	Oldest To Newest
																	  </button>
					                                            	</div>
				                                            	</div>
				                                            </div>
				                                        </div>
				                                    </div>
												</div>
											</div>
		                                </div>
	                                </div>
	                                <div class="row">
	                                	<div class="col-md-12">
											<div class="row">
								 				<div class="col-md-12">
													<table class="table table-bordered" id="payment_table">
			                                            <thead>
			                                                <tr>
			                                                    <th>Doc. Type</th>
			                                                    <th>Description</th>
			                                                    <th>Doc. No.</th>
			                                                    <th>Posting Date</th>
			                                                    <th>Due Date</th>
			                                                    <th>Amount</th>
			                                                    <th>Amount Paid</th>
			                                                    <th>Balance</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                            	<tr ng-repeat="inv in payment_docs">
			                                            		<td>{{inv.document_type}}</td>
			                                            		<td>{{inv.description}}</td>
			                                            		<td>{{inv.doc_no}}</td>
			                                            		<td>{{inv.posting_date}}</td>
			                                            		<td>{{inv.due_date}}</td>
			                                            		<td align="right">{{inv.debit | currency :''}}</td>
			                                            		<td align="right">{{inv.credit | currency :''}}</td>
			                                            		<td align="right">{{inv.balance | currency :''}}</td>
			                                            	</tr>

			                                            </tbody>
			                                        </table>
												</div>
											</div>
		                                </div>
	                                </div>
	                                <div class="row">
	                                	<div class="col-md-12">
	                                		<button role = "submit" ng-disabled = "frm_payment.$invalid || !validTenderAmount()" class="btn btn-large btn-primary col-md-1 col-md-offset-10"><i class="fa fa-save"></i> Submit</button>
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
    <!-- Modal -->
	<div id="apply-to-modal" class="modal fade" role="dialog">
	  <div class="modal-dialog" style="width: 80%;">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Apply To</h4>
	      </div>
	      <div class="modal-body">
	        <table class="table table-sm table-border">
	        	<thead>
	        		<tr>
	        			<th>Doc. Type</th>
	        			<th>Description</th>
	        			<th>Document No.</th>
	        			<th>Posting Date</th>
	        			<th>Amount</th>
	        			<th>Amount Paid</th>
	        			<th>Balance</th>
	        			<th align="center">Select</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		<tr ng-repeat="inv in invoices | orderBy:'org_sequence':false | orderBy:'sequence':false" 
	        			class="{{inv.selected ? 'bg-success animate__animated  animate__slideInUp' : 'animate__animated  animate__slideInDown' }}">
	        			<td>{{inv.document_type}}</td>
	        			<td>{{inv.description}}</td>
	        			<td>{{inv.doc_no}}</td>
	        			<td>{{inv.posting_date}}</td>
	        			<td align="right">{{inv.debit | currency:''}}</td>
	        			<td align="right">{{inv.credit | currency:''}}</td>
	        			<td align="right">{{inv.balance | currency:''}}</td>
	        			<td align="center">
	        				<input type="checkbox" style="zoom: 1.5" ng-model="inv.selected" ng-change="selectInvoice(inv)">
	        			</td>
	        		</tr>
	        	</tbody>
	        </table>
	      </div>
	      <div class="modal-footer">
	      	<button type="button"
	      		class="btn btn-primary" 
	      		ng-click="applySelectedInvoices()"
	      		ng-disabled="getSelectedInvoices().length == 0" >Apply</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>

	  </div>
	</div>
</div> <!-- End of Container -->
</body>

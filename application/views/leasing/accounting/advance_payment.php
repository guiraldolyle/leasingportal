
<div class="container" 
	ng-controller="AdvPaymentCntrl" 
	ng-init='getInitData(); clearPaymentData();
		tender_types = <?= $tender_types?>; 
		<?=$this->session->userdata('cfs_logged_in') ? "getCcmCustomers()" : ""?>'>
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading <?=$this->session->userdata('cfs_logged_in') ? 'panel-cfs' : 'panel-leasing'?>"><i class="fa fa-pencil-square"></i> Advance Payment</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs bot-margin" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <form action="" ng-submit="savePayment($event)"   method="post"  name = "frm_payment" id = "frm_payment">
                            	<div role="tabpanel" class="tab-pane active" id="payment">
	                                <div class="row">
	                                	<div class="col-md-10 col-md-offset-1">
											<div class="row">
												<div class="col-md-6">
													

				                                    <div class="row">
	                                                    <div class="form-group">
	                                                        <label for="tenancy_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenancy Type</label>
	                                                        <div class="col-md-7">
	                                                            <select
	                                                                class = "form-control"
	                                                                name = "tenancy_type"
	                                                                id="tenancy_type"
	                                                                ng-model = "tenancy_type"
	                                                                ng-change = "populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type); clearPaymentData()"
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
				                                                <div>
				                                                	<?php if($this->session->userdata('cfs_logged_in')): ?>
				                                                		<div class="input-group">
						                                                    <div mass-autocomplete>
						                                                        <input
						                                                            required
						                                                            name = "trade_name"
						                                                            ng-model = "pmt.trade_name"
						                                                            class = "form-control"
						                                                            readonly=""
						                                                            id = "trade_name">
						                                                    </div>
						                                                    <span class="input-group-btn">
						                                                         <button
						                                                         	ng-disabled="!tenancy_type || tenancy_type.length == 0"
						                                                            class = "btn btn-info"
						                                                            type = "button"
						                                                            data-backdrop="static"
						                                                            data-keyboard="false"
						                                                            data-toggle="modal"
						                                                            data-target="#lookup"
						                                                            ng-click = "loadList2('<?php echo base_url(); ?>index.php/ctrl_cfs/tenant_lookup/' + tenancy_type)"><i class = "fa fa-search"></i>
						                                                        </button>
						                                                    </span>
						                                                </div>
				                                                	<?php else:?>

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

																	<?php endif;?>
				                                                </div>
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

												</div>
												<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
				                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="payment_date" class="col-md-5 control-label text-right">Payment Date</label>
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
                                                                            autocomplete="off">
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
				                                            <label for="remarks" class="col-md-5 control-label text-right">
				                                            	<!-- <i class="fa fa-asterisk"></i> -->Remarks
				                                            </label>
				                                            <div class="col-md-7">
						                                        <input
							                                        type = "text"
							                                        ng-model = "pmt.remarks"
							                                        id="remarks"
							                                        name = "remarks"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>

												</div>
											</div>
		                                </div>
	                                </div>
	                                <div class="row" >
	                                	<div class="col-md-12">
											<div class="row">
												<div class="col-md-12">
													<label class="large-label">Payment Scheme :</label>
												</div>
											</div>
											<div class="row" ng-init="tender_typeCode = 1">
												<div class="col-md-10 col-md-offset-1">
													<div class="col-md-6 {{tender_typeCode != '2' ? '' : ''}}">
														<div class="row">
					                                        <div class="form-group">
					                                            <label for="tender_typeCode" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tender Type</label>
					                                            <div class="col-md-7">
								                                    <select
								                                    	class="form-control"
								                                    	ng-model = "tender_typeCode"
								                                    	required
								                                    	ng-options="t.id as t.desc  for t in tender_types">
								                                    </select>
								                                    <input type="hidden" name= "tender_typeCode" ng-value="tender_typeCode">
					                                            </div>
					                                        </div>
					                                    </div>

														<div class="row" ng-if="tender_typeCode != '11' && tender_typeCode != '12'">
					                                        <div class="form-group">
					                                            <label for="receipt_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Payment Receipt No.</label>
					                                            <div class="col-md-7">
								                                    <div class="input-group">
								                                    	<div class="input-group-addon input-date"><b>PR</b></div>
								                                    	<input
									                                        type = "text"
									                                        ng-model="pmt.receipt_no"
									                                        id="receipt_no"
									                                        required
									                                        name = "receipt_no"
									                                        class = "form-control emphasize currency"
									                                        is-unique
							                                                is-unique-api="../ctrl_validation/verify_receiptNo/"
							                                                autocomplete="off">
									                                    </div>

								                                    <!-- FOR ERRORS -->
						                                            <div class="validation-Error">
						                                                <span ng-show="frm_payment.receipt_no.$dirty && frm_payment.receipt_no.$error.required">
						                                                    <p class="error-display">This field is required.</p>
						                                                </span>
						                                                <span ng-show="frm_payment.receipt_no.$dirty && frm_payment.receipt_no.$error.unique">
						                                                    <p class="error-display">Receipt No already in use.</p>
						                                                </span>
						                                            </div>
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row" ng-if="tender_typeCode == '11'">
					                                        <div class="form-group">
					                                            <label for="receipt_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Transaction No.</label>
					                                            <div class="col-md-7">
							                                    	<input
								                                        type = "text"
								                                        readonly
								                                        name = "receipt_no"
								                                        class = "form-control emphasize currency"
								                                        ng-value="pmt.uft_no"
								                                        >
					                                            </div>
					                                        </div>
					                                    </div>

					                                    <div class="row" ng-if="tender_typeCode == '12'">
					                                        <div class="form-group">
					                                            <label for="receipt_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Transaction No.</label>
					                                            <div class="col-md-7">
							                                    	<input
								                                        type = "text"
								                                        readonly
								                                        name = "receipt_no"
								                                        class = "form-control emphasize currency"
								                                        ng-value="pmt.ip_no"
								                                        >
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row" ng-if="tender_typeCode == '12'">
														    <div class="form-group">
														    <label for="account_num" class="col-md-5 control-label text-right">
														    	<i class="fa fa-asterisk"></i> Store Name
														    </label>
													        <div class="col-md-7">
													            <select ng-model="pmt.store" class="form-control"
													            	ng-options="s as s.store_name for s in stores">
													            </select>
													            <input type="hidden" name="store_code" ng-value="pmt.store.store_code">
													            <input type="hidden" name="store_name" ng-value="pmt.store.store_name">
													        </div>
														    </div>
														</div>
					                                    <div class="row" ng-if = "tender_typeCode != '1' && tender_typeCode != '3' && tender_typeCode != '11' && tender_typeCode != '12'">
					                                        <div class="form-group">
					                                            <label for="tender_typeDesc" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Supporting Document</label>
					                                            <div class="col-md-7">
							                                        <input
								                                        type = "file"
								                                        id="supp_doc"
								                                        name = "supp_doc[]"
								                                        multiple
								                                        accept="image/*"
								                                        required
								                                        class = "form-control">
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row">
		                                                    <div class="form-group">
		                                                        <label for="amount_paid" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Amount Paid</label>
		                                                        <div class="col-md-7">
		                                                            <div class="input-group">
		                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
		                                                                <input
		                                                                    type="text"
		                                                                    required
		                                                                    class="form-control currency"
		                                                                    ng-model="pmt.amount_paid"
		                                                                    ui-number-mask="2"
		                                                                    autocomplete="off">
		                                                                <input type="hidden" name="amount_paid" ng-value="pmt.amount_paid">
		                                                            </div>
		                                                        </div>
		                                                    </div>
		                                                </div>
				                                    	<div class="row" ng-if="in_array(tender_typeCode, [1,2,3,11])">
					                                        <div class="form-group">
					                                            <label for="bank_code" class="col-md-5 control-label text-right">
					                                            	<i class="fa fa-asterisk" ng-if="in_array(tender_typeCode, [1,2,3,11])"></i>Bank Code
					                                            </label>
					                                            <div class="col-md-7">
							                                        <select
							                                        	ng-required = "in_array(tender_typeCode, [1,2,3,11])"
							                                        	ng-disabled="!in_array(tender_typeCode, [1,2,3,11])"
							                                        	ng-model = "pmt.bank"
							                                        	class="form-control"
							                                        	ng-options="bank as bank.bank_code for bank in banks">
							                                        </select>
							                                        <input type="hidden" name="bank_code" ng-value="pmt.bank.bank_code">
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row" ng-if="in_array(tender_typeCode, [1,2,3,11])">
					                                        <div class="form-group">
					                                            <label for="bank" class="col-md-5 control-label text-right">
					                                            	<i class="fa fa-asterisk" ng-if="in_array(tender_typeCode, [1,2,3,11])"></i>Bank Name
					                                            </label>
					                                            <div class="col-md-7">
							                                        <input type="text" 
							                                        	class="form-control" 
							                                        	name="bank_name" 
							                                        	ng-value="pmt.bank.bank_name" 
							                                        	readonly=""
							                                        	ng-required = "in_array(tender_typeCode, [1,2,3,11])"
							                                        	ng-disabled="!in_array(tender_typeCode, [1,2,3,11])">
					                                            </div>
					                                        </div>
					                                    </div>

					                                    <?php if($this->session->userdata('cfs_logged_in')): ?>
						                                    <div class="row" ng-if="tender_typeCode != '12'">
						                                        <div class="form-group">
						                                            <label for="payee" class="col-md-5 control-label text-right">Payor</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        ng-value="tenant.trade_name"
									                                        name = "payor"
									                                        readonly
									                                        class = "form-control">
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row" ng-if="tender_typeCode != '12'">
						                                        <div class="form-group">
						                                            <label for="payee" class="col-md-5 control-label text-right">Payee</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        value="<?= $payee; ?>"
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
						                                <?php endif;?>
													</div> <!-- col-md-6 divider -->


													<?php if($this->session->userdata('cfs_logged_in')): ?>
														<div class="col-md-6">

															<div class="row">
						                                        <div class="form-group">
						                                            <label for="check_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Type</label>
						                                            <div class="col-md-7">
						                                            	<select name="check_type"
						                                            		class="form-control" 
						                                            		ng-model="pmt.check_type"  
						                                            		ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'">
						                                            		<option>DATED CHECK</option>
						                                            		<option>POST DATED CHECK</option>
						                                            	</select>
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.check_type.$dirty && frm_payment.check_type.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
	                                                                        </span>
	                                                                    </div>
						                                            </div>
						                                        </div>
						                                    </div>
															<div class="row">
						                                        <div class="form-group">
						                                            <label for="account_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Account Number</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        ng-model = "account_no"
									                                        id="account_no"
									                                        name = "account_no"
									                                        required
									                                        ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'"
									                                        class = "form-control">
									                                    <!-- FOR ERRORS -->
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.account_no.$dirty && frm_payment.account_no.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
	                                                                        </span>
	                                                                    </div>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="account_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Account Name</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        ng-model = "account_name"
									                                        id="account_name"
									                                        name = "account_name"
									                                        required
									                                        ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'"
									                                        class = "form-control">
									                                    <!-- FOR ERRORS -->
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.account_name.$dirty && frm_payment.account_name.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
	                                                                        </span>
	                                                                    </div>
						                                            </div>
						                                        </div>
						                                    </div>
															<div class="row">
						                                        <div class="form-group">
						                                            <label for="check_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Number</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        ng-model = "pmt.check_no"
									                                        id="check_no"
									                                        name = "check_no"
									                                        required
									                                        ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'"
									                                        class = "form-control">
									                                    <!-- FOR ERRORS -->
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.check_no.$dirty && frm_payment.check_no.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
	                                                                        </span>
	                                                                    </div>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="check_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Date</label>
						                                            <div class="col-md-7">
								                                        <div class="input-group">
		                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
		                                                                    <datepicker  date-format="yyyy-M-dd">
		                                                                        <input
		                                                                            type="text"
		                                                                            required
		                                                                            readonly
		                                                                            ng-disabled = "tender_typeCode != '2'"
								                                        			ng-required = "tender_typeCode == '2'"
		                                                                            placeholder="Choose a date"
		                                                                            class="form-control"
		                                                                            ng-model="pmt.check_date"
		                                                                            id="check_date"
		                                                                            name = "check_date"
		                                                                            autocomplete="off">
		                                                                    </datepicker>
		                                                                </div>
						                                            </div>
						                                        </div>
						                                    </div>

						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="check_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Due Date</label>
						                                            <div class="col-md-7">
								                                        <div class="input-group">
		                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
		                                                                    <datepicker  date-format="yyyy-M-dd"> 
		                                                                        <input
		                                                                            type="text"
		                                                                            readonly
		                                                                            ng-disabled = "tender_typeCode != '2' || pmt.check_type != 'POST DATED CHECK'"
								                                        			ng-required = "tender_typeCode == '2' && pmt.check_type == 'POST DATED CHECK'"
		                                                                            placeholder="Choose a date"
		                                                                            class="form-control"
		                                                                            ng-model="pmt.check_due_date"
		                                                                            name = "check_due_date"
		                                                                            autocomplete="off">
		                                                                    </datepicker>
		                                                                </div>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="expiry_date" class="col-md-5 control-label text-right">
						                                            	<!-- <i class="fa fa-asterisk"></i> -->Expiry Date
						                                            </label>
						                                            <div class="col-md-7">
								                                        <div class="input-group">
		                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
		                                                                    <datepicker  date-format="yyyy-M-dd">
		                                                                        <input
		                                                                            type="text"
		                                                                            readonly
		                                                                            ng-disabled = "tender_typeCode != '2'"
		                                                                            placeholder="Choose a date"
		                                                                            class="form-control"
		                                                                            ng-model="expiry_date"
		                                                                            id="expiry_date"
		                                                                            name = "expiry_date"
		                                                                            autocomplete="off">
		                                                                    </datepicker>
		                                                                </div>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="bank" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Class</label>
						                                            <div class="col-md-7">
								                                        <select
								                                        	id = "check_class"
								                                        	ng-model = "check_class"
								                                        	name = "check_class"
								                                        	required
								                                        	class="form-control"
								                                        	ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'">
								                                        	<option value="" disabled="" selected="" style = "display:none">Please Select One if Check</option>
								                                        	<option>COMPANY</option>
								                                        	<option>GOVERNMENT</option>
								                                        	<option>PERSONAL</option>
								                                        	<option>SUPPLIER</option>
								                                        </select>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="bank" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Category</label>
						                                            <div class="col-md-7">
								                                        <select
								                                        	id = "check_category"
								                                        	ng-model = "check_category"
								                                        	name = "check_category"
								                                        	class="form-control"
								                                        	required
								                                        	ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'">
								                                        	<option value="" disabled="" selected="" style = "display:none">Please Select One if Check</option>
								                                        	<option>LOCAL</option>
								                                        	<option>MANILA</option>
								                                        	<option>NATIONAL</option>
								                                        	<option>REGIONAL</option>
								                                        </select>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="customer_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Customer Name</label>
						                                            <div class="col-md-7">
								                                        <div mass-autocomplete>
								                                        	<input
										                                        type = "text"
										                                        ng-model="pmt.customer_name"
	                                                                            mass-autocomplete-item="ccm_cust_autocomplete"
										                                        name = "customer_name"
										                                        required
										                                        autocomplete="on"
										                                        ng-disabled = "tender_typeCode != '2'"
									                                        	ng-required = "tender_typeCode == '2'"
										                                        class = "form-control">
								                                        </div>
									                                    <!-- FOR ERRORS -->
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.customer_name.$dirty && frm_payment.customer_name.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
	                                                                        </span>
	                                                                    </div>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="bank" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Bank</label>
						                                            <div class="col-md-7">
								                                        <select
								                                        	ng-model = "pmt.check_bank"
								                                        	name = "check_bank"
								                                        	required
								                                        	class="form-control"
								                                        	ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'">
								                                        	<option value="" disabled="" selected="" style = "display:none">Please Select One if Check</option>
								                                        	<?php foreach ($ccm_banks as $key => $bank):?>
								                                        		<option value="<?=$bank['bank_id'] ?>">
								                                        			<?=$bank['bankbranchname']?>	
								                                        		</option>
								                                        	<?php endforeach; ?>
								                                        </select>
						                                            </div>
						                                        </div>
						                                    </div>
														</div>
													<?php else: ?>
														<div class="col-md-6">
															<div class="row">
						                                        <div class="form-group">
						                                            <label for="check_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Type</label>
						                                            <div class="col-md-7">
						                                            	<select name="check_type"
						                                            		class="form-control" 
						                                            		ng-model="pmt.check_type"  
						                                            		ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'">
						                                            		<option>DATED CHECK</option>
						                                            		<option>POST DATED CHECK</option>
						                                            	</select>
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.check_type.$dirty && frm_payment.check_type.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
	                                                                        </span>
	                                                                    </div>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    
															<div class="row">
						                                        <div class="form-group">
						                                            <label for="check_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check No.</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        ng-model = "pmt.check_no"
									                                        id="check_no"
									                                        name = "check_no"
									                                        required
							                                                autocomplete="off"
									                                        ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'"
									                                        class = "form-control"
									                                        ng-pattern = "/^\d+$/">

	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.check_no.$dirty && frm_payment.check_no.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
	                                                                        </span>
	                                                                    </div>
	                                                                    
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.check_no.$dirty && frm_payment.check_no.$error.pattern">
	                                                                            <p class="error-display">Numeric value only</p>
	                                                                        </span>
	                                                                    </div>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="check_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Date</label>
						                                            <div class="col-md-7">
								                                        <div class="input-group">
		                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
		                                                                    <datepicker  date-format="yyyy-M-dd"> 
		                                                                        <input
		                                                                            type="text"
		                                                                            readonly
		                                                                            ng-disabled = "tender_typeCode != '2'"
								                                        			ng-required = "tender_typeCode == '2'"
		                                                                            placeholder="Choose a date"
		                                                                            class="form-control"
		                                                                            ng-model="pmt.check_date"
		                                                                            id="check_date"
		                                                                            name = "check_date"
		                                                                            autocomplete="off">
		                                                                    </datepicker>
		                                                                </div>
						                                            </div>
						                                        </div>
						                                    </div>

						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="check_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Due Date</label>
						                                            <div class="col-md-7">
								                                        <div class="input-group">
		                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
		                                                                    <datepicker  date-format="yyyy-M-dd"> 
		                                                                        <input
		                                                                            type="text"
		                                                                            readonly
		                                                                            ng-disabled = "tender_typeCode != '2' || pmt.check_type != 'POST DATED CHECK'"
								                                        			ng-required = "tender_typeCode == '2' && pmt.check_type == 'POST DATED CHECK'"
		                                                                            placeholder="Choose a date"
		                                                                            class="form-control"
		                                                                            ng-model="pmt.check_due_date"
		                                                                            name = "check_due_date"
		                                                                            autocomplete="off">
		                                                                    </datepicker>
		                                                                </div>
						                                            </div>
						                                        </div>
						                                    </div>

						                                    <div class="row" ng-if="tender_typeCode != '12'">
						                                        <div class="form-group">
						                                            <label for="payee" class="col-md-5 control-label text-right">Payor</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        ng-value="tenant.trade_name"
									                                        name = "payor"
									                                        readonly
									                                        class = "form-control">
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row" ng-if="tender_typeCode != '12'">
						                                        <div class="form-group">
						                                            <label for="payee" class="col-md-5 control-label text-right">Payee</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        value="<?= $payee; ?>"
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
													<?php endif; ?>
												</div>
											</div>
		                                </div>
	                                </div>
	                                <div class="row">
	                                	<div class="col-md-12">
	                                		<button 
	                                			role = "submit" 
	                                			ng-disabled = "frm_payment.$invalid || pmt.amount_paid == 0 || !pmt.amount_paid " 
	                                			class="btn btn-large btn-primary col-md-1 col-md-offset-10"
	                                			type="submit">
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




	<?php if($this->session->userdata('cfs_logged_in')): ?>
		<!-- Tenant Lookup Modal -->
		<div class="modal fade" id = "lookup" >
		    <div class="modal-dialog modal-lg">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h4 class="modal-title"><i class="fa fa-tv"></i> Tenant Look Up</h4>
		            </div>
		            <div class="modal-body">
		                <div class="row">
		                    <div class="col-md-3 pull-right">
		                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="query2" ng-keydown = "currentPage2 = 0" />
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-md-12">
		                        <table class="table table-bordered">
		                            <thead>
		                                <tr>
		                                    <th ><a href="#" data-ng-click="sortBy = 'tenant_id'; reverse = !reverse">Tenant ID</a></th>
		                                    <th ><a href="#" data-ng-click="sortBy = 'trade_name'; reverse = !reverse">Trade Name</a></th>
		                                    <th ><a href="#" data-ng-click="sortBy = 'store_name'; reverse = !reverse">Store Location</a></th>
		                                    <th width="15%">Action</th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                                <tr class="ng-cloak" ng-show="dataList2.length!=0" ng-repeat= "data in dataList2 | filter:query2 | orderBy:sortBy:reverse | offset: currentPage2*itemsPerPage2 | limitTo: itemsPerPage2">
		                                    <td>{{data.tenant_id}}</td>
		                                    <td>{{data.trade_name}}</td>
		                                    <td>{{data.store_name}}</td>
		                                    <td align="right">
		                                        <!-- Split button -->
		                                        <div class="btn-group" style="width:70% !important">
		                                            <button type="button" style="width:70% !important" ng-click = "generate_paymentCredentials(data.trade_name, tenancy_type); pmt.trade_name =  data.trade_name;" class="btn btn-xs btn-primary" data-dismiss = "modal">Select</button>
		                                        </div>
		                                    </td>
		                                </tr>
		                                <tr class="ng-cloak" ng-show="dataList2.length==0 || (dataList2 | filter:query2).length == 0">
		                                    <td colspan="6"><center>No Data Available.</center></td>
		                                </tr>
		                            </tbody>
		                            <tfoot>
		                                <tr class="ng-cloak">
		                                    <td colspan="6" style="padding: 5px;">
		                                        <div>
		                                            <ul class="pagination">
		                                                <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0" ng-class="prevPageDisabled2()">
		                                                    <a href ng-click="prevPage2()" style="border-radius: 0px;"><i class="fa fa-angle-double-left"></i> Prev</a>
		                                                </li>
		                                                <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0" ng-repeat="n in range2()" ng-class="{active: n == currentPage2}" ng-click="setPage2(n)">
		                                                    <a href="#">{{n+1}}</a>
		                                                </li>
		                                                <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0" ng-class="nextPageDisabled2()">
		                                                    <a href ng-click="nextPage2()" style="border-radius: 0px;">Next <i class="fa fa-angle-double-right"></i></a>
		                                                </li>
		                                            </ul>
		                                        </div>
		                                    </td>
		                                </tr>
		                            </tfoot>
		                        </table>
		                    </div>
		                </div>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-danger" data-dismiss = "modal"><i class = "fa fa-close"></i> Close</button>
		            </div>
		        </div><!-- /.modal-dialog -->
		    </div><!-- /.modal -->
		</div>
		<!-- End Tenant Lookup Modal -->
	<?php endif; ?>
</div> <!-- End of Container -->
</body>

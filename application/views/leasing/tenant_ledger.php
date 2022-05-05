<div class="container" ng-controller="transactionController">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Tenant Ledger</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                                            </ul>
                                            <div class="tab-content ng-cloak">
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
                    			                                                    ng-model = "tenancy_type"
                    			                                                    ng-change = "populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type)"
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
                    			                                                <div class="input-group">
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
                    			                                                            ng-click = "subsidiary_ledger(dirty.value, tenancy_type)"><i class = "fa fa-search"></i>
                    			                                                        </button>
                    			                                                    </span>
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
                    						                                        ng-model = "tenant_id"
                    						                                        id="tenant_id"
                    						                                        name = "tenant_id"
                    						                                        class = "form-control">
                    			                                            </div>
                    			                                        </div>
                    			                                    </div>

                    			                                    <div class="row">
                    			                                        <div class="form-group">
                    			                                            <label for="corporate_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Corporate Name</label>
                    			                                            <div class="col-md-7">
                    						                                    <input
                    						                                        type = "text"
                    						                                        readonly
                    						                                        ng-model = "corporate_name"
                    						                                        id="corporate_name"
                    						                                        name = "corporate_name"
                    						                                        class = "form-control">
                    			                                            </div>
                    			                                        </div>
                    			                                    </div>

                    											</div>
                    											<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
                    												<div class="row">
                    			                                        <div class="form-group">
                    			                                            <label for="address" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Address</label>
                    			                                            <div class="col-md-7">
                    						                                    <input
                    						                                        type = "text"
                    						                                        readonly
                    						                                        ng-model = "address"
                    						                                        id="address"
                    						                                        name = "address"
                    						                                        class = "form-control">
                    			                                            </div>
                    			                                        </div>
                    			                                    </div>
                    												<div class="row">
                    			                                        <div class="form-group">
                    			                                            <label for="tin" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>TIN</label>
                    			                                            <div class="col-md-7">
                    						                                    <input
                    						                                        type = "text"
                    						                                        readonly
                    						                                        ng-model = "tin"
                    						                                        id="tin"
                    						                                        name = "tin"
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
                    						                                        readonly
                    						                                        ng-model = "contract_no"
                    						                                        id="contract_no"
                    						                                        name = "contract_no"
                    						                                        class = "form-control">
                    			                                            </div>
                    			                                        </div>
                    			                                    </div>

                    												<div class="row">
                    			                                        <div class="form-group">
                    			                                            <label for="rental_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Rental Type</label>
                    			                                            <div class="col-md-7">
                    						                                    <input
                    						                                        type = "text"
                    						                                        readonly
                    						                                        ng-model = "rental_type"
                    						                                        id = "rental_type"
                    						                                        name = "rental_type"
                    						                                        class = "form-control">
                    			                                            </div>
                    			                                        </div>
                    			                                    </div>
                    											</div>
                    										</div>
                    	                                </div>
                                                    </div>

                                                    <hr>

                                                    <div class="row"> <!-- table wrapper  -->
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3 pull-right">
                                                                    <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    	<table class="table table-bordered" ng-table = "tableParams" id="subsidiaryLedger_table" ng-controller="tableController">
                                                    		
                                                            <tbody id = "subsidiaryLedger_tbody">
                                                            	<tr class="ng-cloak" ng-repeat= "dt in data">
                                                            		<td title = "'Entry No.'" sortable = "'entry_no'">{{ dt.entry_no }}</td>
                                                            		<td title = "'Doc. Type'" sortable = "'document_type'">{{ dt.document_type }}</td>
                                                            		<td title = "'Doc. No.'" sortable = "'doc_no'">{{ dt.doc_no }}</td>
                                                            		<td title = "'Ref. No.'" sortable = "'ref_no'">{{ dt.ref_no }}</td>
                                                            		<td title = "'Description'" sortable = "'gl_account'">{{ dt.gl_account }}</td>
                                                            		<td title = "'Posting Date'" sortable = "'posting_date'">{{ dt.posting_date }}</td>
                                                            		<td title = "'Due Date'" sortable = "'due_date'">{{ dt.due_date }}</td>
                                                            		<td title = "'Debit'" sortable = "'debit'" class = "currency-align">
                                                            			<span ng-if = "dt.debit === '0' || !dt.debit">-</span>
                                                            			<span ng-if = "dt.debit !== '0'">{{ dt.debit | currency : '' }}</span>
                                                            		</td>
                                                            		<td title = "'Credit'" sortable = "'credit'" class = "currency-align">
                                                            			<span ng-if = "dt.credit === '0' || !dt.credit">-</span>
                                                            			<span ng-if = "dt.credit !== '0'">{{ dt.credit | currency : '' }}</span>
                                                            		</td>
                                                                    <td title = "'Bank Code'" sortable = "'bank_code'">{{ dt.bank_code }}</td>
                                                            		<td title = "'Running Balance'" sortable = "'balance'" class = "currency-align">
                                                            			<span ng-if = "dt.balance === '0' || !dt.balance">-</span>
                                                            			<span ng-if = "dt.balance !== '0'">{{ dt.balance | currency : '' }}</span>
                                                            		</td>
                                                            	</tr>
                                                            </tbody>
                                                            <tbody ng-show = "isLoading">
                                                                <tr>
                                                                    <td colspan="10">
                                                                        <div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
                                                                        <div class = "loader-text"><center><b>Collecting Data. Please Wait...</b></center></div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                    	</table>
                                                    	<div class="col-md-12" ng-if = "contract_no">
                                                        	<a class="btn btn-default btn-medium pull-right" href="<?php echo base_url(); ?>index.php/leasing_transaction/export_tenantLedger/{{tenant_id}}"  ><i class="fa fa-download"></i> Export Excel</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- End of tab-content -->
                                        </div>
                                    </div>
                                </div> <!-- End of panel-body -->
                            </div> <!-- End of panel -->
                        </div> <!-- End of Well -->
                    </div>
                </div> <!-- row -->
            </div> <!-- .content-main -->
        </div> <!-- .main-page -->
    </div> <!-- .row -->
    <footer class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
            <p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#" >Cyril Andrew</a></p>
        </div>
    </footer>  <!-- .row -->
</div> <!-- .container -->

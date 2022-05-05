
<div class="container" ng-controller="invoiceOverrideHstryCntrl">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Invoice Override History</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="payment_history">
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
			                                                    ng-change = "populate_tradeName('<?=base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type)"
			                                                    onchange = "clear_paymentHistory()"
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
			                                                            ng-click = "generate_tenantCredentials(dirty.value, tenancy_type)"><i class = "fa fa-search"></i>
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
						                                        ng-model = "tenant.tenant_id"
						                                        id="tenant_id"
						                                        name = "tenant_id"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>
			                                    <div class="row">
			                                        <div class="form-group">
			                                            <label for="address" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Address</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        ng-model = "tenant.address"
						                                        id="address"
						                                        name = "address"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>
											</div>
											<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
												<div class="row">
			                                        <div class="form-group">
			                                            <label for="contract_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Contract No.</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        ng-model = "tenant.contract_no"
						                                        id="contract_no"
						                                        name = "contract_no"
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
						                                        ng-model = "tenant.tin"
						                                        id="tin"
						                                        name = "tin"
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
						                                        ng-model = "tenant.rental_type"
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
                                	<div class="col-md-12" ng-controller="tableController">
                                		<div class="row">
						                    <div class="col-md-3 pull-right">
						                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
						                    </div>
						                </div>
				                        <table class="table table-bordered" ng-table = "tableParams">
				                            <tbody id = "paymentHistory_tbody">
				                                <tr class="ng-cloak" ng-repeat= "dt in data">
				                                    <td title="'Invoice No.'" sortable="'doc_no'">{{ dt.doc_no }}</td>
				                                    <td title="'Invoice Type'" sortable="'invoice_type'">{{ dt.invoice_type }}</td>
				                                    <td title="'Overrided By'" sortable="'override_by'">{{ dt.override_by }}</td>
				                                    <td title="'Approved By'" sortable="'approved_by'">{{ dt.approved_by }}</td>
				                                    <td title="'Amount'" sortable="'amount'" align="right">{{ dt.amount | currency:'' }}</td>

				                                    
				                                    <td title="'Action'" align="center">
				                                    	<button class="btn btn-xs btn-primary" 
				                                    		title="View Invoice"
				                                    		ng-click="displayInvoiceDetails(dt.invoice_details)">
				                                    		<i class="fa fa-search"></i>
				                                    	</button>
				                                    	<button class="btn btn-xs btn-success" 
				                                    		title="Supporting Documents" 
				                                    		ng-click="displaySuppDocs(dt.supp_docs)">
				                                    		<i class="fa fa-file"></i>
				                                    	</button>
				                                    	
				                                    </td>
				                                </tr>
				                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
				                                    <td colspan="8"><center>No Data Available.</center></td>
				                                </tr>
				                            </tbody>
				                        </table>
				                    </div>
                                </div>
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->

    <!-- Carousel Modal -->
    <div class="modal fade" id="supported-docs-modal">
        <div class="modal-dialog modal-md">
        	
            <div class="modal-content" style="border-radius: 10px;">
            	<div class="modal-header">
			        <h5 class="modal-title">Supporting Documents 
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
			    	</h5>
			        
			    </div>
            	<div class="modal-body">
            		<div class="container">

            			<div id="suppDocsCarousel" class="carousel slide" data-ride="carousel">
						  <!-- Indicators -->
						  <ol class="carousel-indicators">
						    <li ng-repeat="doc in ctrl_data.supp_docs" 
						    	data-target="#suppDocsCarousel"
						    	ng-class="{active : ($index === 0)}"
						    	data-slide-to="{{$index}}">
						    </li>
						  </ol>

						  <!-- Wrapper for slides -->
						  <div class="carousel-inner" role="listbox" style="min-height: 400px;">
						  	<div ng-if = "!ctrl_data.supp_docs || ctrl_data.supp_docs.length == 0" 
						  		class = "item active">
	                            <img src="<?= base_url(); ?>img/thumbnail.png" alt="Default" class="img-responsive">
	                        </div>

						    <div ng-repeat="doc in ctrl_data.supp_docs"
						    	 ng-class="{item: true, active: ($index === 0)}">
						        <img src="<?= base_url('assets/invoice_override_docs')?>/{{doc.file_name}}" 
						        	alt="{{doc.file_name}}" style="width:100%;">
						    </div>

						  </div>

						  <!-- Left and right controls -->
						  <a class="left carousel-control" href="#suppDocsCarousel" data-slide="prev" role="button">
						    <span class="glyphicon glyphicon-chevron-left"></span>
						    <span class="sr-only">Previous</span>
						  </a>
						  <a class="right carousel-control" href="#suppDocsCarousel" data-slide="next" role="button">
						    <span class="glyphicon glyphicon-chevron-right"></span>
						    <span class="sr-only">Next</span>
						  </a>
						</div>
            		</div>
            	</div>
	                
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!--End Carousel Modal -->


    <!-- Invoice Modal -->
    <div class="modal fade" id="invoice-details-modal">
        <div class="modal-dialog " style="width: 80%;">
        	
            <div class="modal-content" style="border-radius: 10px; ">
            	<div class="modal-header">
			        <h5 class="modal-title">Invoice Details 
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
			    	</h5>
			        
			    </div>
            	<div class="modal-body">
            		<div class="container">
            			<table class="table table-bordered" id="charges_table">
                            <thead>
                                <tr>
                                    <th >Charges Type</th>
                                    <th >Charges Code</th>
                                    <th >Description</th>
                                    <th >UOM</th>
                                    <th >Total Unit</th>
                                    <th >Unit Price / Base Amount</th>
                                    <th >Actual Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                               <tr ng-repeat="inv in ctrl_data.invoice_details">
                                   <td>{{inv.charges_type}}</td>
                                   <td>{{inv.charges_code}}</td>
                                   <td>{{inv.description}}</td>
                                   <td>{{inv.uom}}</td>
                                   <td align="right">{{inv.total_unit | currency : ''}}</td>
                                   <td align="right">{{inv.unit_price | currency : '&#8369;'}}</td>
                                   <td align="right">{{inv.actual_amt | currency : '&#8369;'}}</td>
                               </tr>
                            </tbody>
                        </table>
            		</div>
            	</div>
	                
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!--End Invoice Modal -->

</div> <!-- End of Container -->





</body>

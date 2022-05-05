<?php 
//var_dump($this->session->userdata('store_code'));
//var_dump($this->session->userdata);
 ?>
<div class="container" ng-controller="invoicingCntrl" 
    ng-init="posting_date= ''; getInitData();">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Invoicing</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General</a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-11">
                                    <div class="row">
                                        <form action="#"  method="post" id="frm_invoice" name = "frm_charges" ng-submit="submitInvoice($event)">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="doc_no" class="col-md-5 control-label text-right">
                                                                Document No.
                                                            </label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    value="<?= $doc_no;?>"
                                                                    id="doc_no"
                                                                    name = "doc_no"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenancy_type" class="col-md-5 control-label text-right">
                                                                <i class="fa fa-asterisk"></i>Tenancy Type
                                                            </label>
                                                            <div class="col-md-7">
                                                                <select
                                                                    class = "form-control"
                                                                    name = "tenancy_type"
                                                                    id="tenancy_type"
                                                                    ng-model = "tenancy_type"
                                                                    ng-change = "populate_tradeName($base_url + 'leasing_transaction/populate_tradeName/' + tenancy_type); clear_invocingData()"
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
                                                            <label for="tenant_id" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
                                                            <div class="col-md-7">
                                                                <div class="">
                                                                    <div mass-autocomplete>
                                                                        <input
                                                                            onkeyup="this.value = this.value.toUpperCase();"
                                                                            required
                                                                            name = "trade_name"
                                                                            ng-model="dirty.value"
                                                                            mass-autocomplete-item="autocomplete_options"
                                                                            class = "form-control"
                                                                            id = "trade_name"
                                                                            ng-change="generate_invoicingCredentials(dirty.value, tenancy_type)"
                                                                            ng-model-options="{debounce : 400}">
                                                                    </div>
                                                                    <!-- <span class="input-group-btn">
                                                                        <button
                                                                            class="btn btn-info"
                                                                            type="button"
                                                                            ng-click = "generate_invoicingCredentials(dirty.value, tenancy_type)"><i class = "fa fa-search"></i>
                                                                        </button>
                                                                    </span> -->
                                                                </div><!-- /input-group -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label  class="col-md-5 control-label text-right">Contract No.</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    ng-model="tenant.contract_no"
                                                                    name = "contract_no"
                                                                    autocomplete="off" >
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_charges.contract_no.$dirty && frm_charges.contract_no.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="col-md-5 control-label text-right">Tenant ID</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    ng-model="tenant.tenant_id"
                                                                    name = "tenant_id"
                                                                    autocomplete="off" >
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_charges.tenant_id.$dirty && frm_charges.tenant_id.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenant_id" class="col-md-5 control-label text-right">Corporate Name</label>
                                                            <div class="col-md-7">
                                                                <div class="">
                                                                    <div >
                                                                        <input
                                                                            name = "corporate_name"
                                                                            ng-model="tenant.corporate_name"
                                                                            class = "form-control"
                                                                            readonly>
                                                                    </div>
                                                                   
                                                                </div><!-- /input-group -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF COL-MD-6 WRAPPER -->
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="col-md-5 control-label text-right">Rental Type</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    class="form-control"
                                                                    ng-model="tenant.rental_type"
                                                                    name = "rental_type"
                                                                    autocomplete="off" >
                                                                    <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_charges.rental_type.$dirty && frm_charges.rental_type.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label  class="col-md-5 control-label text-right">Transaction Date</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class = "fa fa-calendar"></i></strong></div>
                                                                    <input
                                                                        type="text"
                                                                        required
                                                                        readonly
                                                                        class="form-control"
                                                                        value="<?php echo $current_date; ?>"
                                                                        name = "transaction_date"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Posting Date</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd" >
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            ng-change = "calculate_dueDate(posting_date)"
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="posting_date"
                                                                            name = "posting_date"
                                                                            autocomplete="off">
                                                                     </datepicker>

                                                                     <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_charges.posting_date.$dirty && frm_charges.posting_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="due_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Due Date</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd" date-min-limit="{{min_dueDate}}">
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="due_date"
                                                                            name = "due_date"
                                                                            autocomplete="off">
                                                                    </datepicker>

                                                                     <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_charges.due_date.$dirty && frm_charges.due_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="total" class="col-md-5 control-label text-right">Total</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control currency"
                                                                        ng-value="total() | currency : ''"
                                                                        readonly
                                                                        name = "total"
                                                                        autocomplete="off">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE SECOND COL-MD-6 WRAPPER -->
                                            </div>
                                            <div class="row" ng-if="tenant && posting_date.length > 0"> <!-- EDITABLE GRID ROW -->
                                                <div class = "row text-center" style="margin-left:50px" >

                                                    <button type="button"
                                                        class = "btn btn-tiny btn-fresh btn-xs"
                                                        ng-disabled="menuIsNotSelected('Basic') || posting_date.length == 0"
                                                        ng-click="selectMenu('Basic', '#basicRental_modal')"
                                                        style="padding: 8px;">
                                                        <i class  = "fa fa-plus-circle"></i> Basic Rental
                                                    </button>

                                                    <button type="button"
                                                        class = "btn btn-tiny btn-fresh  btn-xs"
                                                        ng-disabled="menuIsNotSelected('Basic Manual') || posting_date.length == 0"
                                                        ng-click="selectMenu('Basic Manual', '#basicRental_modal')"
                                                        style="padding: 8px;">

                                                        <i class  = "fa fa-plus-circle"></i> Basic Rental(Manual)
                                                    </button>

                                                    <button type="button"
                                                        ng-if="tenancy_type == 'Long Term Tenant'"
                                                        class = "btn btn-tiny btn-fresh  btn-xs"
                                                        ng-disabled="menuIsNotSelected('Retro Rent')"
                                                        ng-click="selectMenu('Retro Rent', '#retroRent_modal')"
                                                        style="padding: 8px;">
                                                        <i class  = "fa fa-plus-circle"></i> Retro Rent
                                                    </button>

                                                    <button type="button"
                                                        ng-if="tenancy_type == 'Long Term Tenant'"
                                                        class = "btn btn-tiny btn-fresh  btn-xs"
                                                        ng-disabled="menuIsNotSelected('Pre Operation Charges')"
                                                        ng-click="selectMenu('Pre Operation Charges', '#preop_charges')"
                                                        style="padding: 8px;">
                                                        <i class  = "fa fa-plus-circle"></i> Pre Operation Charges
                                                    </button>

                                                    


                                                    <button type="button"
                                                        ng-if="tenancy_type == 'Long Term Tenant'"
                                                        class = "btn btn-tiny btn-fresh  btn-xs"
                                                        ng-disabled="menuIsNotSelected('Other Charges')"
                                                        ng-click="selectMenu('Other Charges', '#constMat')"
                                                        style="padding: 8px;">
                                                        <i class  = "fa fa-plus-circle"></i> Contruction Materials
                                                    </button>

                                                    <button type="button"
                                                        class = "btn btn-tiny btn-fresh  btn-xs"
                                                        ng-disabled="menuIsNotSelected('Other Charges')"
                                                        ng-click="selectMenu('Other Charges', '#monthly_charges')"
                                                        style="padding: 8px;">

                                                        <i class  = "fa fa-plus-circle"></i> Monthly Charges
                                                    </button>

                                                    <button type="button"
                                                        class = "btn btn-tiny btn-fresh  btn-xs"
                                                        ng-disabled="menuIsNotSelected('Other Charges')"
                                                        ng-click="selectMenu('Other Charges', '#other_charges')"
                                                        style="padding: 8px;">
                                                        <i class  = "fa fa-plus-circle"></i> Other Charges
                                                    </button>

                                                    <button type="button"
                                                        ng-disabled="invoices.length == 0 || !invoices"
                                                        ng-click="clearInvoices()"
                                                        class = "btn btn-tiny btn-hot  btn-xs"
                                                         style="padding: 8px;">
                                                        <i class  = "fa fa-times"></i> Clear
                                                    </button>
                                                </div>

                                                <table class="table table-bordered" id="charges_table" style="margin-left:50px">
                                                    <thead>
                                                        <tr>
                                                            <th >Charges Type</th>
                                                            <th >Charges Code</th>
                                                            <th >Description</th>
                                                            <th >UOM</th>
                                                            <th >Total Unit</th>
                                                            <th >Unit Price / Base Amount</th>
                                                            <th >Actual Amount</th>
                                                            <th width="4px;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <tr ng-repeat="inv in invoices">
                                                           <td>{{inv.charge_type}}</td>
                                                           <td>{{inv.charge_code}}</td>
                                                           <td>{{inv.description}}</td>
                                                           <td>{{inv.uom}}</td>
                                                           <td align="right">{{inv.total_unit | currency : ''}}</td>
                                                           <td align="right">{{inv.unit_price | currency : '&#8369;'}}</td>
                                                           <td align="right">{{inv.actual_amount | currency : '&#8369;'}}</td>
                                                           <td align="center" >
                                                                <button type="button" 
                                                                    ng-if="inv.removable"
                                                                    class="btn btn-danger btn-xs"
                                                                    ng-click="removeInvoice(inv)">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                           </td>
                                                       </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- EDITABLE GRID END ROW -->
                                            <div class="row"> <!-- ROW FOR BUTTONS -->
                                                <div class = "col-md-12 text-right">
                                                    <button type = "submit" ng-disabled = "frm_charges.$invalid || total() <= 0" class = "btn btn-primary btn-medium"><i class = "fa fa-save"></i> Save Invoice</button>
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

    <!-- Add Basic Rental Modal -->
    <div class="modal fade" id = "basicRental_modal">
        <div class="modal-dialog modal-md" 
            style="{{tenant.rental_type == 'Fixed/Percentage w/c Higher' && formData.invoice_type == 'Basic' ? 'width: 80%' : ''}}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Basic Rental </h4>
                </div>
                <div class="modal-body">

                    <!-- FIXED RENTAL TYPE -->
                        <div class = "row" ng-if="tenant.rental_type == 'Fixed' && 
                            (formData.invoice_type == 'Basic' || 
                            (formData.invoice_type == 'Basic Manual' && !formData.fixed_total_manual_basic))">
                            <div class = "col-md-12">

                                
                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">
                                            Basic Rent 
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-label" 
                                            ng-if="formData.invoice_type == 'Basic'">
                                            {{basic_rental() | currency:'&#8369;'}}
                                        </label>
                                        <div class="col-md-5 col-md-offset-3 " 
                                            ng-if="formData.invoice_type == 'Basic Manual'">
                                            <input type="text" 
                                                ui-number-mask="2" 
                                                class="form-control text-right" 
                                                ng-model="formData.manual_basic"
                                                style="font-weight: bold;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="rental_increment() > 0">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label">
               <!-- basicRental_modal  -->                            Rental Increment ({{tenant.increment_percentage}}%) 
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-label">
                                            {{rental_increment() | currency:'&#8369;'}}
                                        </label>
                                        
                                    </div>
                                </div>

                                <div class="row no-padding mb-3" ng-if="getDaysFromOpening() < currentMonthDays()">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">
                                            Number of Days:
                                        </label>
                                        <div class="col-md-5 col-md-offset-3 text-right control-label invoice-label">
                                            <input type="number" 
                                                step="1" 
                                                class="form-control text-right"
                                                style="font-weight: bold;" 
                                                max="{{days.days_in_month}}"
                                                min="1"
                                                ng-model="days.days_occupied">
                                            <span style="font-size: 13px; color: red">
                                                {{basic_plus_increment() | currency : '&#8369;'}} /
                                                <input type="number" 
                                                    min="28" 
                                                    max="31" 
                                                    style="font-weight: bold; width: 40px !important;"
                                                    ng-model="days.days_in_month"
                                                    readonly=""> 
                                                * {{days.days_occupied ? days.days_occupied : 'Invalid'}}
                                            </span> 

                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="getDaysFromOpening() < currentMonthDays()">
                                    <div class="form-group">
                                        <label for="gross_sales" 
                                            class="col-md-4 control-label text-right invoice-label"
                                            style="font-weight: bold;">
                                            Current Rental
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-label" 
                                            style="font-weight: bold;">
                                            {{calculated_basic() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>


                                

                                <div class="row no-padding" ng-if="tenant.discounts && tenant.discounts.length > 0">
                                    <div class="form-group">
                                        <div class="col-md-4 control-label text-right invoice-label">
                                            Discount(s):
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-repeat = "discount in tenant.discounts">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-size: 13px;">
                                            <i>{{ discount.tenant_type }} : </i>
                                        </label>
                                        <label class="col-md-4 text-right  control-label invoice-label "
                                            ng-if="discount.discount_type == 'Percentage'">
                                            ({{calculated_basic()|currency:'&#8369;'}}) {{ discount.discount | currency : '%' }}
                                        </label>
                                        <label class="col-md-4  control-label invoice-label text-right"
                                            ng-if="discount.discount_type == 'Percentage'">
                                            ({{ (calculated_basic() * (parseNumber(discount.discount) / 100)) | currency : '&#8369;'}})
                                        </label>
                                        <label class="col-md-6 col-md-offset-2 control-label invoice-label text-right"
                                            ng-if="discount.discount_type != 'Percentage'">
                                            ({{discount.discount | currency : '&#8369;'}})
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold">
                                            Sub Total:
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"  
                                            style="font-weight: bold; text-decoration: underline;">
                                            {{sub_total() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.is_vat == 'Added'">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" >
                                           VAT(+) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                        </label>
                                         <label class="col-md-2 text-right col-md-offset-2 control-label  invoice-label" >
                                           {{vat_percentage() | currency :'%'}}
                                        </label>
                                        <label class="col-md-4 text-right control-label invoice-label">
                                            {{vat_amount() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.wht == 'Added'">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" >
                                           CWT(-) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                        </label>
                                        <label class="col-md-2  text-right col-md-offset-2 control-label  invoice-label" >
                                           {{wht_percentage() | currency :'%'}}
                                        </label>
                                        <label class="col-md-4 text-right control-label invoice-label">
                                            {{wht_amount() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>
                                <br>
                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold;">
                                           Total Basic Rental
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"
                                            style="font-weight: bold; text-decoration: underline;">
                                            {{total_basic_rental() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <!-- END OF FIXED RENTAL TYPE -->


                    <!-- FIXED PLUS PERCENTAGE RENTAL TYPE -->
                        <div class = "row" ng-if="tenant.rental_type == 'Fixed Plus Percentage' && 
                            (formData.invoice_type == 'Basic' || 
                            (formData.invoice_type == 'Basic Manual' && !formData.fixed_total_manual_basic))">
                            <div class = "col-md-12">

                                <div class="row no-padding mb-3">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-5 control-label invoice-label text-right">{{tenant.sales == 'GROSS' || tenant.sales == 'NET' ? 'SALES' : ''}} 
                                            <i style="font-size: 11px;">(Inclusive of VAT)</i>
                                            <span style="color:red;"> / 1.12 </span>
                                        </label>
                                        
                                        <div class="col-md-5 col-md-offset-2 pull-right" >
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <strong>&#8369;</strong>
                                                </div>
                                                <input type="text" 
                                                    ui-number-mask="2" 
                                                    class="form-control text-right" 
                                                    ng-model="fpp.sales"
                                                    style="font-weight: bold;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label">
                                           Rentable Sales : 
                                        </label>
                                        
                                        <div class="col-md-5 col-md-offset-3 text-right " >
                                            <span class="control-label text-right invoice-label">
                                                {{(fpp_total_sales() | currency : '₱')}}
                                            </span>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="row no-padding" ng-if="tenant.tenant_id == 'ICM-LT000008'">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">SALES
                                            <i style="font-size: 11px;">(Exclusive of VAT)</i>
                                        </label>
                                        <div class="col-md-5 pull-right">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <strong>&#8369;</strong>
                                                </div>
                                                <input 
                                                    type="text" 
                                                    ui-number-mask="2" 
                                                    class="form-control text-right" 
                                                    ng-model="fpp.tax_exempt"
                                                    style="font-weight: bold;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label">
                                            Percentage Rent : 
                                        </label>
                                        <label class="col-md-4 text-right  control-label invoice-label">
                                            ({{fpp_total_sales()|currency:'₱'}}) {{ tenant.rent_percentage | currency : '%' }}
                                        </label>
                                        <label class="col-md-4  control-label invoice-label text-right" style="font-weight: bold;">
                                            {{ fpp_percentage_rent() | currency : '₱'}}
                                        </label>
                                    </div>
                                </div>

                                <!-- ####################################################### -->


                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">
                                            Basic Rent 
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-label" 
                                            ng-if="formData.invoice_type == 'Basic'">
                                            {{basic_rental() | currency:'&#8369;'}}
                                        </label>
                                        <div class="col-md-5 col-md-offset-3 " 
                                            ng-if="formData.invoice_type == 'Basic Manual'">
                                            <input type="text" 
                                                ui-number-mask="2" 
                                                class="form-control text-right" 
                                                ng-model="formData.manual_basic"
                                                style="font-weight: bold;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="rental_increment() > 0">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label">
                                            Rental Increment ({{tenant.increment_percentage}}%) 
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-label">
                                            {{rental_increment() | currency:'&#8369;'}}
                                        </label>
                                        
                                    </div>
                                </div>

                                <div class="row no-padding mb-3" ng-if="getDaysFromOpening() < currentMonthDays()">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">
                                            Number of Days:
                                        </label>
                                        <div class="col-md-5 col-md-offset-3 text-right control-label invoice-label">
                                            <input type="number" 
                                                step="1" 
                                                class="form-control text-right"
                                                style="font-weight: bold;" 
                                                max="{{days.days_in_month}}"
                                                min="1"
                                                ng-model="days.days_occupied">
                                            <span style="font-size: 13px; color: red">
                                                {{basic_plus_increment() | currency : '&#8369;'}} /
                                                <input type="number" 
                                                    min="28" 
                                                    max="31" 
                                                    style="font-weight: bold; width: 40px !important;"
                                                    ng-model="days.days_in_month"
                                                    readonly=""> 
                                                * {{days.days_occupied ? days.days_occupied : 'Invalid'}}
                                            </span> 

                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label for="gross_sales" 
                                            class="col-md-4 control-label text-right invoice-label"
                                            style="font-weight: bold;">
                                            Current Rental
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-label" 
                                            style="font-weight: bold;">
                                            {{calculated_basic() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>


                                

                                <div class="row no-padding" ng-if="tenant.discounts && tenant.discounts.length > 0">
                                    <div class="form-group">
                                        <div class="col-md-4 control-label text-right invoice-label">
                                            Discount(s):
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-repeat = "discount in tenant.discounts">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-size: 13px;">
                                            <i>{{ discount.tenant_type }} : </i>
                                        </label>
                                        <label class="col-md-4 text-right  control-label invoice-label "
                                            ng-if="discount.discount_type == 'Percentage'">
                                            ({{calculated_basic()|currency:'&#8369;'}}) {{ discount.discount | currency : '%' }}
                                        </label>
                                        <label class="col-md-4  control-label invoice-label text-right"
                                            ng-if="discount.discount_type == 'Percentage'">
                                            ({{ (calculated_basic() * (parseNumber(discount.discount) / 100)) | currency : '&#8369;'}})
                                        </label>
                                        <label class="col-md-6 col-md-offset-2 control-label invoice-label text-right"
                                            ng-if="discount.discount_type != 'Percentage'">
                                            ({{discount.discount | currency : '&#8369;'}})
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold">
                                            Sub Total:
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"  
                                            style="font-weight: bold; text-decoration: underline;">
                                            {{sub_total() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.is_vat == 'Added'">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" >
                                           VAT(+) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                        </label>
                                         <label class="col-md-2 text-right col-md-offset-2 control-label  invoice-label" >
                                           {{vat_percentage() | currency :'%'}}
                                        </label>
                                        <label class="col-md-4 text-right control-label invoice-label">
                                            {{vat_amount() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.wht == 'Added'">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" >
                                           CWT(-) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                        </label>
                                        <label class="col-md-2  text-right col-md-offset-2 control-label  invoice-label" >
                                           {{wht_percentage() | currency :'%'}}
                                        </label>
                                        <label class="col-md-4 text-right control-label invoice-label">
                                            {{wht_amount() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>
                                <br>
                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold;">
                                           Total Basic Rental
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"
                                            style="font-weight: bold; text-decoration: underline;">
                                            {{total_basic_rental() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                
                            </div>
                        </div>
                    <!-- END OF FIXED PLUS PERCENTAGE RENTAL TYPE -->


                    <!-- PERCENTAGE RENTAL TYPE -->
                        <div class = "row" ng-if="tenant.rental_type == 'Percentage' && formData.invoice_type == 'Basic'">
                            <div class = "col-md-12">
                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-5 control-label invoice-label text-right">
                                          {{tenant.sales}} {{tenant.sales == 'GROSS' || tenant.sales == 'NET' ? 'SALES' : ''}} 
                                            <i style="font-size: 11px;">(VAT included)</i>
                                        </label>
                                        
                                        <div class="col-md-5 col-md-offset-2 text-right" >
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <strong>₱</strong>
                                                </div>
                                                <input type="text" 
                                                    ui-number-mask="2" 
                                                    class="form-control text-right" 
                                                    ng-model="pct.sales"
                                                    style="font-weight: bold;">
                                            </div>
                                            <span style="color:red;"> / 1.12 </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label">
                                            Percentage Rent : 
                                        </label>
                                        <label class="col-md-4 text-right  control-label invoice-label">
                                            ({{pct_total_sales()|currency:'₱'}}) {{ tenant.rent_percentage | currency : '%' }}
                                        </label>
                                        <label class="col-md-4  control-label invoice-label text-right" style="font-weight: bold;">
                                            {{ pct_percentage_rent() | currency : '₱'}}
                                        </label>
                                    </div>
                                </div>

                                <!-- ####################################################### -->
                                

                                <div class="row no-padding" ng-if="tenant.discounts && tenant.discounts.length > 0">
                                    <div class="form-group">
                                        <div class="col-md-4 control-label text-right invoice-label">
                                            Discount(s):
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-repeat = "discount in tenant.discounts">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-size: 13px;">
                                            <i>{{ discount.tenant_type }} : </i>
                                        </label>
                                        <label class="col-md-4 text-right  control-label invoice-label "
                                            ng-if="discount.discount_type == 'Percentage'">
                                            ({{calculated_basic()|currency:'&#8369;'}}) {{ discount.discount | currency : '%' }}
                                        </label>
                                        <label class="col-md-4  control-label invoice-label text-right"
                                            ng-if="discount.discount_type == 'Percentage'">
                                            ({{ (calculated_basic() * (parseNumber(discount.discount) / 100)) | currency : '&#8369;'}})
                                        </label>
                                        <label class="col-md-6 col-md-offset-2 control-label invoice-label text-right"
                                            ng-if="discount.discount_type != 'Percentage'">
                                            ({{discount.discount | currency : '&#8369;'}})
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold">
                                            Sub Total:
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"  
                                            style="font-weight: bold; text-decoration: underline;">
                                            {{sub_total() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.is_vat == 'Added'">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" >
                                           VAT(+) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                        </label>
                                         <label class="col-md-2 text-right col-md-offset-2 control-label  invoice-label" >
                                           {{vat_percentage() | currency :'%'}}
                                        </label>
                                        <label class="col-md-4 text-right control-label invoice-label">
                                            {{vat_amount() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.wht == 'Added'">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" >
                                           CWT(-) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                        </label>
                                        <label class="col-md-2  text-right col-md-offset-2 control-label  invoice-label" >
                                           {{wht_percentage() | currency :'%'}}
                                        </label>
                                        <label class="col-md-4 text-right control-label invoice-label">
                                            {{wht_amount() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>
                                <br>
                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold;">
                                           Total Basic Rental
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"
                                            style="font-weight: bold; text-decoration: underline;">
                                            {{total_basic_rental() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                
                            </div>
                        </div>
                    <!-- END OF PERCENTAGE RENTAL TYPE -->


                    <!-- PERCENTAGE RENTAL TYPE -->
                        <div class = "row" ng-if="tenant.rental_type == 'Fixed/Percentage w/c Higher' && formData.invoice_type == 'Basic'">
                            <div class = "col-md-6 side-divider" >
                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-5 control-label invoice-label text-right">
                                          {{tenant.sales}} {{tenant.sales == 'GROSS' || tenant.sales == 'NET' ? 'SALES' : ''}} 
                                            <i style="font-size: 11px;">(VAT included)</i>
                                        </label>
                                        
                                        <div class="col-md-5 col-md-offset-2 text-right" >
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <strong>₱</strong>
                                                </div>
                                                <input type="text" 
                                                    ui-number-mask="2" 
                                                    class="form-control text-right" 
                                                    ng-model="fop.sales"
                                                    style="font-weight: bold;">
                                            </div>
                                            <span style="color:red;"> / 1.12 </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-5 control-label text-right invoice-label">
                                            Total Sales : 
                                        </label>
                                        <label class="col-md-5 col-md-offset-2 control-label invoice-label text-right">
                                            {{ fop_total_sales() | currency : '₱'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-5 control-label text-right invoice-label">
                                            Rent Percentage :  
                                        </label>
                                        <label class="col-md-5 col-md-offset-2 control-label invoice-label text-right">
                                             {{ tenant.rent_percentage | currency : '%' }}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-5 control-label text-right invoice-label" style="font-weight: bold;">
                                            Rentable Sales :
                                        </label>
                                        <label class="col-md-5 col-md-offset-2 control-label invoice-label text-right" style="font-weight: bold;">
                                            {{ fop_percentage_rent() | currency : '₱'}}
                                        </label>
                                    </div>
                                </div>

                            </div>
                                <!-- ####################################################### -->
                            
                            <div class = "col-md-6">

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-5 control-label text-right invoice-label">
                                            Basic Rent 
                                        </label>
                                        <label class="col-md-5 col-md-offset-2 text-right control-label invoice-label">
                                            {{basic_rental() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="rental_increment() > 0">
                                    <div class="form-group">
                                        <label class="col-md-5 control-label text-right invoice-label">
                                            Rental Increment ({{tenant.increment_percentage}}%) 
                                        </label>
                                        <label class="col-md-5 col-md-offset-2 text-right control-label invoice-label">
                                            {{rental_increment() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding mb-3" ng-if="getDaysFromOpening() < currentMonthDays()">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-5 control-label text-right invoice-label">
                                            Number of Days:
                                        </label>
                                        <div class="col-md-5 col-md-offset-2 text-right control-label invoice-label">
                                            <input type="number" 
                                                step="1" 
                                                class="form-control text-right"
                                                style="font-weight: bold;" 
                                                max="{{days.days_in_month}}"
                                                min="1"
                                                ng-model="days.days_occupied">
                                            <span style="font-size: 13px; color: red">
                                                {{basic_plus_increment() | currency : '&#8369;'}} /
                                                <input type="number" 
                                                    min="28" 
                                                    max="31" 
                                                    style="font-weight: bold; width: 40px !important;"
                                                    ng-model="days.days_in_month"
                                                    readonly=""> 
                                                * {{days.days_occupied ? days.days_occupied : 'Invalid'}}
                                            </span> 

                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label for="gross_sales" class="col-md-5 control-label text-right invoice-label" style="font-weight: bold;">
                                            Total Basic Rent 
                                        </label>
                                        <label class="col-md-5 col-md-offset-2 text-right control-label invoice-label" style="font-weight: bold;">
                                            {{calc_basic_plus_increment() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-md-offset-3" style="padding: 0;">
                                <div class="panel panel-info">
                                    <div class="panel-heading">Evaluated Basic Rent</div>
                                    <div class="panel-body">

                                        <div class="row no-padding">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold;">
                                                    Basic Rent :
                                                </label>
                                                <label class="col-md-5 col-md-offset-3 text-right control-label invoice-label" style="font-weight: bold;">
                                                    {{calculated_basic() | currency:'&#8369;'}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row no-padding" ng-if="tenant.discounts && tenant.discounts.length > 0">
                                            <div class="form-group">
                                                <div class="col-md-4 control-label text-right invoice-label">
                                                    Discount(s):
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row no-padding" ng-repeat = "discount in tenant.discounts">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label text-right invoice-label" style="font-size: 13px;">
                                                    <i>{{ discount.tenant_type }} : </i>
                                                </label>
                                                <label class="col-md-4 text-right  control-label invoice-label "
                                                    ng-if="discount.discount_type == 'Percentage'">
                                                    ({{calculated_basic()|currency:'&#8369;'}}) {{ discount.discount | currency : '%' }}
                                                </label>
                                                <label class="col-md-4  control-label invoice-label text-right"
                                                    ng-if="discount.discount_type == 'Percentage'">
                                                    ({{ (calculated_basic() * (parseNumber(discount.discount) / 100)) | currency : '&#8369;'}})
                                                </label>
                                                <label class="col-md-6 col-md-offset-2 control-label invoice-label text-right"
                                                    ng-if="discount.discount_type != 'Percentage'">
                                                    ({{discount.discount | currency : '&#8369;'}})
                                                </label>
                                            </div>
                                        </div>

                                        <div class="row no-padding">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold">
                                                    Sub Total:
                                                </label>
                                                <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"  
                                                    style="font-weight: bold; text-decoration: underline;">
                                                    {{sub_total() | currency:'&#8369;'}}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="row no-padding" ng-if="tenant.is_vat == 'Added'">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label text-right invoice-label" >
                                                   VAT(+) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                                </label>
                                                 <label class="col-md-2 text-right col-md-offset-2 control-label  invoice-label" >
                                                   {{vat_percentage() | currency :'%'}}
                                                </label>
                                                <label class="col-md-4 text-right control-label invoice-label">
                                                    {{vat_amount() | currency:'&#8369;'}}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="row no-padding" ng-if="tenant.wht == 'Added'">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label text-right invoice-label" >
                                                   CWT(-) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                                </label>
                                                <label class="col-md-2  text-right col-md-offset-2 control-label  invoice-label" >
                                                   {{wht_percentage() | currency :'%'}}
                                                </label>
                                                <label class="col-md-4 text-right control-label invoice-label">
                                                    {{wht_amount() | currency:'&#8369;'}}
                                                </label>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row no-padding">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold;">
                                                   Total Basic Rental
                                                </label>
                                                <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"
                                                    style="font-weight: bold; text-decoration: underline;">
                                                    {{total_basic_rental() | currency:'&#8369;'}}
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- <div class="panel-footer">Panel Footer</div> -->
                                </div>
                                        
                            </div>


                        </div>
                    <!-- END OF PERCENTAGE RENTAL TYPE -->



                    <!-- FIXED TOTAL BASIC  -->
                        <div class = "row" ng-if="formData.invoice_type == 'Basic Manual' && formData.fixed_total_manual_basic">
                            <div class = "col-md-12">
                                <div class="row no-padding">
                                    <label for="basicRental_manual" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                                    <div class="col-md-4 pull-right">
                                        <input
                                            type = "text"
                                            class = "form-control currency"
                                            ng-model = "formData.manual_basic"
                                            ui-number-mask="2"
                                            autocomplete = "off">
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.discounts && tenant.discounts.length > 0">
                                    <div class="form-group">
                                        <div class="col-md-4 control-label text-right invoice-label">
                                            Discount(s):
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-repeat = "discount in tenant.discounts">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-size: 13px;">
                                            <i>{{ discount.tenant_type }} : </i>
                                        </label>
                                        <label class="col-md-4 text-right  control-label invoice-label "
                                            ng-if="discount.discount_type == 'Percentage'">
                                            ({{calculated_basic()|currency:'&#8369;'}}) {{ discount.discount | currency : '%' }}
                                        </label>
                                        <label class="col-md-4  control-label invoice-label text-right"
                                            ng-if="discount.discount_type == 'Percentage'">
                                            ({{ (calculated_basic() * (parseNumber(discount.discount) / 100)) | currency : '&#8369;'}})
                                        </label>
                                        <label class="col-md-6 col-md-offset-2 control-label invoice-label text-right"
                                            ng-if="discount.discount_type != 'Percentage'">
                                            ({{discount.discount | currency : '&#8369;'}})
                                        </label>
                                    </div>
                                </div>
                                <br>

                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold">
                                            Sub Total:
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"  
                                            style="font-weight: bold; text-decoration: underline;">
                                            {{sub_total() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.is_vat == 'Added'">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" >
                                           VAT(+) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                        </label>
                                         <label class="col-md-2 text-right col-md-offset-2 control-label  invoice-label" >
                                           {{vat_percentage() | currency :'%'}}
                                        </label>
                                        <label class="col-md-4 text-right control-label invoice-label">
                                            {{vat_amount() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.wht == 'Added'">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" >
                                           CWT(-) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                        </label>
                                        <label class="col-md-2  text-right col-md-offset-2 control-label  invoice-label" >
                                           {{wht_percentage() | currency :'%'}}
                                        </label>
                                        <label class="col-md-4 text-right control-label invoice-label">
                                            {{wht_amount() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>
                                <br>
                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold;">
                                           Total Basic Rental
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"
                                            style="font-weight: bold; text-decoration: underline;">
                                            {{total_basic_rental() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- END OF FIXED TOTAL BASIC  -->


                    <!-- MANUAL BASIC SUPPORTING DOCS  -->
                        <div class = "row card" ng-if="formData.invoice_type == 'Basic Manual'">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold;">
                                        <i class="fa fa-asterisk"></i> Supporting Docs.
                                    </label>
                                    <div class="col-md-7 col-md-offset-1">
                                        <input type="file" name="supp_docs[]" multiple="" class="form-control" id="inv-supp-docs">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- END OF MANUAL BASIC SUPPORTING DOCS  -->


                    <div class="modal-footer">
                        <button class="btn btn-primary pull-left" 
                            ng-if="formData.invoice_type == 'Basic Manual' 
                                && tenant.rental_type !='Percentage' 
                                && tenant.rental_type !='Fixed/Percentage w/c Higher'" 
                            ng-click="formData.fixed_total_manual_basic = (formData.fixed_total_manual_basic == true ? false : true)">
                            {{formData.fixed_total_manual_basic ? 'Manual Basic Input' : 'Fixed Total Basic Rent'}}
                        </button>
                        <button class="btn btn-primary" ng-click="append_basic()">
                            <i class="fa fa-save"></i> Apply
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> 
                            <i class="fa fa-close"></i> Close
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Basic Rental Modal -->

    <!-- Add Pre Operation Modal -->
    <div class="modal fade" id = "preop_charges">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Pre Operation Charges</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10" id = "charges_wrapper">
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                    <div class="col-md-8">
                                        <select 
                                            name = "preOp_desc"
                                            ng-model = "formData.preOp" 
                                            class = "form-control"
                                            ng-options="p.description for p in preop_charges">
                                                
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div ng-if="formData.preOp">
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                        <div class="col-md-8">
                                            <input type = "text" ng-model = "formData.preOp.charges_code" class = "form-control" readonly >
                                        </div>
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit of Measure</label>

                                        <div class = "row">
                                            <div class="col-md-2 pull-left">
                                                <input type = "text" readonly ng-model = "formData.preOp.uom"name = "preOp_uom" class = "form-control" />
                                            </div>
                                            <span>mo(s).Basic/fixed basic rent</span>
                                        </div>
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Basic Rental</label>
                                        <div class="col-md-8">
                                            <input
                                                type = "text"
                                                ui-number-mask="2"
                                                ng-model = "formData.preOp.basic_rental"
                                                ng-keyup="formData.preOp.actual_amount = parseNumber(formData.preOp.basic_rental) * parseNumber(formData.preOp.uom)"
                                                class = "form-control currency">
                                        </div>
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                        <div class="col-md-8">
                                            <input type = "text" 
                                                ui-number-mask="2" 
                                                ng-model = "formData.preOp.actual_amount"
                                                ng-keyup="formData.preOp.basic_rental = parseNumber(formData.preOp.actual_amount) / parseNumber(formData.preOp.uom)" 
                                                class = "form-control currency" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn btn-primary" ng-click="append_preop()"><i class="fa fa-save"></i> Append</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Pre Operation Modal -->

    <!-- Add Construction Materials Modal -->
    <div class="modal fade" id = "constMat">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Construction Materials</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                    <div class="col-md-8">
                                        <select ng-model= "formData.cons_mat" 
                                            class="form-control" 
                                            ng-options="c.description for c in cons_materials">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div ng-if="formData.cons_mat">
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                        <div class="col-md-8">
                                            <input type = "text" ng-model = "formData.cons_mat.charges_code" class = "form-control" readonly >
                                        </div>
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit of Measure</label>
                                        <div class="col-md-8">
                                            <input type = "text" readonly ng-model = "formData.cons_mat.uom" class = "form-control" />
                                        </div>
                                    </div>
                                </div>



                                <div class = "row">
                                    <div class="form-group">
                                        <label for="unit_price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit Price</label>
                                        <div class="col-md-8">
                                            <input
                                            type = "text"
                                                ui-number-mask="2"
                                                ng-model = "formData.cons_mat.unit_price"
                                                name = "unit_price"
                                                class = "form-control currency"
                                                readonly>
                                        </div>
                                    </div>
                                </div>


                                <div ng-if="formData.cons_mat.description == 'Water' || formData.cons_mat.description == 'Electricity'">
                                    <div class = "row" >
                                        <div class="form-group">
                                            <label for="prev_reading" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Previous Reading </label>
                                            <div class="col-md-8">
                                                <input
                                                type = "text"
                                                    ui-number-mask="2"
                                                    ng-model = "prev_reading"
                                                    class = "form-control currency"
                                                    ng-keyup = "formData.cons_mat.total_unit = parseNumber(curr_reading) - parseNumber(prev_reading);
                                                        formData.cons_mat.actual_amount = parseNumber(formData.cons_mat.total_unit) * parseNumber(formData.cons_mat.unit_price)">
                                            </div>
                                        </div>
                                    </div>

                                    <div class = "row">
                                        <div class="form-group">
                                            <label for="curr_reading" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Current Reading</label>
                                            <div class="col-md-8">
                                                <input
                                                    type = "text"
                                                    ui-number-mask="2"
                                                    ng-model = "curr_reading"
                                                    min="prev_reading"
                                                    class = "form-control currency"
                                                    ng-keyup = "formData.cons_mat.total_unit = parseNumber(curr_reading) - parseNumber(prev_reading);
                                                        formData.cons_mat.actual_amount = parseNumber(formData.cons_mat.total_unit) * parseNumber(formData.cons_mat.unit_price)">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- If UOM is equal to 'Fixed Amount', hide total_unit and actual amount is equal to unit_price -->

                                <div class = "row" ng-if = "formData.cons_mat.uom != 'Fixed Amount'">
                                    <div class="form-group">
                                        <label for="total_unit" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Total Unit</label>
                                        <div class="col-md-8">
                                            <input
                                                type = "text"
                                                ui-number-mask="2"
                                                ng-model = "formData.cons_mat.total_unit"
                                                class = "form-control currency"
                                                ng-keyup = "formData.cons_mat.actual_amount = parseNumber(formData.cons_mat.total_unit) * parseNumber(formData.cons_mat.unit_price)">
                                        </div>
                                    </div>
                                </div>
                                <div class = "row" ng-if = "formData.cons_mat.uom != 'Fixed Amount'">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                        <div class="col-md-8">
                                            <input
                                                type = "text"
                                                readonly
                                                ui-number-mask="2"
                                                ng-model = "formData.cons_mat.actual_amount"
                                                class = "form-control currency">


                                        </div>
                                    </div>
                                </div>


                                <!-- If UOM is equal to 'Fixed Amount', hide total_unit and actual amount is equal to unit_price -->


                                <div class = "row" ng-if = "formData.cons_mat.uom == 'Fixed Amount'">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                        <div class="col-md-8">
                                            <input
                                                type = "text"
                                                readonly
                                                ui-number-mask="2"
                                                ng-model = "formData.cons_mat.unit_price"
                                                class = "form-control currency">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" ng-click="append_cons_mat()" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Construction Material Charges Modal -->

    <!-- Add Other Charges Modal -->
    <div class="modal fade" id = "monthly_charges">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Selected Monthly Charges</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="23%">Description</th>
                                        <th width="11%">Charges Code</th>
                                        <th width="11%">UOM</th>
                                        <th width="11%">Unit Price</th>
                                        <th width="11%">Previous Reading</th>
                                        <th width="11%">Current Reading</th>
                                        <th width="11%">Total Unit</th>
                                        <th width="11%">Actual Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat = "charge in monthly_charges">
                                        <td>{{ charge.description }}</td>
                                        <td>{{ charge.charges_code }}</td>
                                        <td>{{ charge.uom }} 
                                        </td>
                                        <td align="right">{{ charge.unit_price | currency : '' }}</td>
                                        <td>
                                            <input type="text"
                                                ui-number-mask="2"
                                                ui-hide-group-sep
                                                ng-model="charge.prev_reading" 
                                                class="form-control currency" 
                                                ng-readonly="charge.description != 'Electricity' && charge.description != 'Water'"
                                                ng-keyup="charge.total_unit = parseNumber(charge.curr_reading) - parseNumber(charge.prev_reading);  
                                                    charge.actual_amount = parseNumber(charge.unit_price) * parseNumber(charge.total_unit)"/>
                                        </td>
                                        <td>
                                            <input type="text"
                                                ui-number-mask="2"
                                                ui-hide-group-sep
                                                ng-model="charge.curr_reading"  
                                                class="form-control currency" 
                                                ng-readonly="charge.description != 'Electricity' && charge.description != 'Water'"
                                                ng-keyup="charge.total_unit = parseNumber(charge.curr_reading) - parseNumber(charge.prev_reading);  
                                                    charge.actual_amount = parseNumber(charge.unit_price) * parseNumber(charge.total_unit)"/>
                                        </td>
                                        <td>
                                            <input type = "text" 
                                                ui-number-mask="2" 
                                                ng-readonly = "charge.description == 'Water' || charge.description == 'Electricity' || charge.uom == 'Fixed Amount' || charge.uom == 'Inputted'" 
                                                ng-model = "charge.total_unit" 
                                                ng-keyup="charge.actual_amount = parseNumber(charge.unit_price) * parseNumber(charge.total_unit)"  
                                                class="form-control currency"/>
                                        </td>
                                        <td>
                                            <input type="text"
                                                ui-number-mask="2"  
                                                ng-model="charge.actual_amount" 
                                                class="form-control currency"
                                                ng-keyup="charge.uom == 'Inputted' ? charge.total_unit == null : charge.total_unit = charge.actual_amount / parseNumber(charge.unit_price)"/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" ng-click="append_monthly_charges()"  class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Other Charges Modal -->

    <!-- Add Other Charges Modal -->
    <div class="modal fade" id = "other_charges">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Other Charges</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10" id = "charges_wrapper">
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                    <div class="col-md-8">
                                        <select required ng-model = "formData.charge" class = "form-control" 
                                            ng-options="c.description for c in other_charges">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div ng-if="formData.charge">
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                        <div class="col-md-8">
                                            <input type = "text" ng-model="formData.charge.charges_code" class = "form-control" readonly >
                                        </div>
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit of Measure</label>
                                        <div class="col-md-8">
                                            <input type = "text" readonly ng-model = "formData.charge.uom" class = "form-control" />
                                        </div>
                                    </div>
                                </div>



                                <div class = "row" ng-if="formData.charge.uom != 'Inputted'">
                                    <div class="form-group">
                                        <label for="unit_price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit Price</label>
                                        <div class="col-md-8">
                                            <input
                                                type = "text"
                                                ui-number-mask="2"
                                                ng-model = "formData.charge.unit_price"
                                                class = "form-control currency"
                                                ng-keyup="formData.charge.actual_amount = parseNumber(formData.charge.total_unit) * parseNumber(formData.charge.unit_price);">
                                        </div>
                                    </div>
                                </div>


                                <div ng-if="formData.charge.description == 'Water' || formData.charge.description == 'Electricity'">
                                    <div class = "row" >
                                        <div class="form-group">
                                            <label for="prev_reading" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Previous Reading </label>
                                            <div class="col-md-8" >
                                                <input
                                                    type = "text"
                                                        ui-number-mask="2"
                                                        ng-model = "formData.charge.prev_reading"
                                                        class = "form-control currency"
                                                        ng-keyup="
                                                            formData.charge.total_unit = parseNumber(formData.charge.curr_reading) - parseNumber(formData.charge.prev_reading); 
                                                            formData.charge.actual_amount = parseNumber(formData.charge.total_unit) * parseNumber(formData.charge.unit_price);">
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class = "row">
                                        <div class="form-group">
                                            <label for="curr_reading" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Current Reading</label>
                                            <div class="col-md-8">
                                                <input
                                                    type = "text"
                                                    ui-number-mask="2"
                                                    ng-model = "formData.charge.curr_reading"
                                                    class = "form-control currency"
                                                    ng-keyup="
                                                        formData.charge.total_unit = parseNumber(formData.charge.curr_reading) - parseNumber(formData.charge.prev_reading); 
                                                        formData.charge.actual_amount = parseNumber(formData.charge.total_unit) * parseNumber(formData.charge.unit_price);">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- If UOM is equal to 'Fixed Amount', hide total_unit and actual amount is equal to unit_price -->

                                <div class = "row" ng-if="formData.charge.uom != 'Fixed Amount' && formData.charge.uom != 'Inputted'">
                                    <div class="form-group">
                                        <label for="total_unit" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Total Unit</label>
                                        <div class="col-md-8">
                                            <input
                                                type = "text"
                                                ui-number-mask="2"
                                                ng-model = "formData.charge.total_unit"
                                                class = "form-control currency"
                                                ng-keyup="formData.charge.actual_amount = parseNumber(formData.charge.total_unit) * parseNumber(formData.charge.unit_price);">
                                        </div>
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                        <div class="col-md-8">
                                            <input
                                                type = "text"
                                                ui-number-mask="2"
                                                ng-model = "formData.charge.actual_amount"
                                                class = "form-control currency"
                                                ng-keyup="formData.charge.total_unit = parseNumber(formData.charge.actual_amount) / parseNumber(formData.charge.unit_price);">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" ng-click = "append_other_charges()" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Other Charges Modal -->

    <!-- Add Retro Rental Modal -->
    <div class="modal fade" id = "retroRent_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Retro Rent </h4>
                </div>
                <div class="modal-body">

                    <!-- RETRO RENT  -->
                        <div class = "row">
                            <div class = "col-md-12">
                                <div class="row no-padding">
                                    <label for="basicRental_manual" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                                    <div class="col-md-4 pull-right">
                                        <input
                                            type = "text"
                                            class = "form-control currency"
                                            ng-model = "formData.retro_amount"
                                            ui-number-mask="2"
                                            autocomplete = "off">
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.is_vat == 'Added'">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" >
                                           VAT(+) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                        </label>
                                         <label class="col-md-2 text-right col-md-offset-2 control-label  invoice-label" >
                                           {{vat_percentage() | currency :'%'}}
                                        </label>
                                        <label class="col-md-4 text-right control-label invoice-label">
                                            {{vat_amount() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>

                                <div class="row no-padding" ng-if="tenant.wht == 'Added'">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" >
                                           CWT(-) <i style="font-size: 12px; color: red;">({{tenant.vat_agreement}})</i>
                                        </label>
                                        <label class="col-md-2  text-right col-md-offset-2 control-label  invoice-label" >
                                           {{wht_percentage() | currency :'%'}}
                                        </label>
                                        <label class="col-md-4 text-right control-label invoice-label">
                                            {{wht_amount() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>
                                <br>
                                <div class="row no-padding">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right invoice-label" style="font-weight: bold;">
                                           Total Basic Rental
                                        </label>
                                        <label class="col-md-5 col-md-offset-3 text-right control-label invoice-total"
                                            style="font-weight: bold; text-decoration: underline;">
                                            {{total_basic_rental() | currency:'&#8369;'}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- END OF RETRO RENT  -->
                    <div class="modal-footer">
                        <button class="btn btn-primary" ng-click="append_basic()">
                            <i class="fa fa-save"></i> Apply
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> 
                            <i class="fa fa-close"></i> Close
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Basic Rental Modal -->


    <!-- Add Manager's Key Modal -->
    <div class="modal fade" id = "managers-key-modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-key"></i> Manager's Key </h4>
                </div>
                <div class="modal-body">
                    <form ng-submit="getManagersKey($event)">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row no-padding" style="margin-bottom: 10px !important;">
                                    <label class="col-md-3 control-label">Username: </label>
                                    <div class="col-md-9 pull-right">
                                        <input
                                            type = "text"
                                            class = "form-control "
                                            name="username">
                                    </div>
                                </div>
                                <div class="row no-padding">
                                    <label class="col-md-3 control-label">Password: </label>
                                    <div class="col-md-9 pull-right">
                                        <input
                                            type = "password"
                                            class = "form-control "
                                            name="password">
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-save"></i> Submit
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> 
                                <i class="fa fa-close"></i> Close
                            </button>
                        </div>

                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Manager's Key Modal -->
</div> 
<!-- End of Container -->
</body>




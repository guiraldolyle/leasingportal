<div class="container" ng-controller="transactionController">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">

                            <input type="hidden" name="portalLogIn" value="<?php echo $this->session->userdata('portal_logged_in'); ?>">

                            <div class="panel panel-default">
                                <div class="panel-heading panel-portal"><i class="fa fa-edit"></i> Utility Readings</div>
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
                                                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
                                                                            <div class="col-md-7">
                                                                                <input required name="trade_name" class="form-control" id="trade_name" style="width: 300px;" value="<?php echo $this->session->userdata('trade_name'); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="tenant_id" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenant ID</label>
                                                                            <div class="col-md-7">
                                                                                <input type="text" readonly id="tenant_id" name="tenant_id" class="form-control" style="width: 300px;" value="<?php echo $this->session->userdata('tenant_id'); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <!-- COL-MD-6 DIVIDER -->
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="start_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Starting Date</label>
                                                                            <div class="col-md-7">
                                                                                <div class="input-group">
                                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                                    <datepicker date-format="yyyy-MM-dd">
                                                                                        <input type="text" required readonly placeholder="Choose a date" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $current_date; ?>" onchange="startingDate()">
                                                                                    </datepicker>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="end_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>End Date</label>
                                                                            <div class="col-md-7">
                                                                                <div class="input-group">
                                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                                    <datepicker date-format="yyyy-MM-dd">
                                                                                        <input type="text" required readonly placeholder="Choose a date" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $current_date; ?>">
                                                                                    </datepicker>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="corporate_name" class="col-md-5 control-label text-right"></label>
                                                                            <div class="col-md-7">
                                                                                <button class="btn btn-primary btn-block" type="button" id="trade_name_button" ng-click="getReading(dirty.value, tenancy_type)" disabled><i class="fa fa-search"> Generate Readings</i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="row">
                                                        <!-- table wrapper  -->
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3 pull-right">
                                                                    <input type="text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <table class="table table-bordered" ng-table="tableParams" id="utilityReadingTable" ng-controller="tableController">
                                                            <tbody id="utilityReadingTable_body">
                                                                <tr class="ng-cloak" ng-repeat="u in data">
                                                                    <td title="'Invoice. No'" sortable="'doc_no'">{{ u.doc_no }}</td>
                                                                    <td title="'Posting Date'" sortable="'posting_date'">{{ u.posting_date }}</td>
                                                                    <td title="'Charges Code'" sortable="'charges_code'">{{ u.charges_code }}</td>
                                                                    <td title="'Description'" sortable="'description'">{{ u.description }}</td>
                                                                    <td title="'UOM'" sortable="'uom'">{{ u.uom }}</td>
                                                                    <td title="'Previous Reading'" sortable="'prev_reading'">{{ u.prev_reading }}</td>
                                                                    <td title="'Currrent Reading'" sortable="'curr_reading'">{{ u.curr_reading }}</td>
                                                                    <td title="'Unit Price'" sortable="'unit_price'">{{ u.unit_price }}</td>
                                                                    <td title="'Total Unit'" sortable="'total_unit'">{{ u.total_unit }}</td>
                                                                    <td title="'Actual Amount'" sortable="'actual_amt'">{{ u.actual_amt }}</td>
                                                                    <td title="'Balance'" sortable="'balance'">{{ u.balance }}</td>
                                                                </tr>
                                                            </tbody>
                                                            <tbody ng-show="isLoading">
                                                                <tr>
                                                                    <td colspan="11">
                                                                        <div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
                                                                        <div class="loader-text">
                                                                            <center><b>Collecting Data. Please Wait...</b></center>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="col-md-12 mt-5" ng-if="contract_no">
                                                            <a class="btn btn-default btn-medium pull-right" ng-click="exportExcel()"><i class="fa fa-download"></i> Export Excel</a>
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
            <p class="copyright">Copyright © 2016 AGC<a rel="nofollow" href="#"></a></p>
        </div>
    </footer> <!-- .row -->
</div> <!-- .container -->
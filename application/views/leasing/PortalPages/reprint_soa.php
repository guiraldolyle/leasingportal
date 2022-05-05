<div class="container" ng-controller="tableController">
    <div class="well" ng-controller="transactionController">
        <div class="panel panel-default">
            <div class="panel-heading panel-portal"><i class="fa fa-print"></i> MY SOA</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-11">

                                    <div class="row" style="margin-left:2%">
                                        <div class="form-group">
                                            <label for="tenant_id" class="col-md-2 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <div>
                                                        <input required name="trade_name" id="trade_name" class="form-control" style="width: 300px;" value="<?php echo $this->session->userdata('trade_name'); ?>" readonly>
                                                    </div>

                                                    <span class="input-group-btn">
                                                        <button class="btn btn-info" type="button" ng-click="generateTenantSOA('<?php echo base_url(); ?>getTenantSoa')">
                                                            <i class="fa fa-search"></i>
                                                            GENERATE
                                                        </button>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <table class="table table-bordered" ng-table="ajaxTableParams" id="charges_table" style="margin-left:50px">
                                            <tbody id="soa_tbody">
                                                <tr ng-repeat="dt in ajaxData">
                                                    <td title="'Tenant ID'" sortable="'tenant_id'">{{dt.tenant_id}}</td>
                                                    <td title="'SOA No.'" sortable="'soa_no'">{{dt.soa_no}}</td>
                                                    <td title="'File Name'" sortable="'file_name'">{{dt.file_name}}</td>
                                                    <td title="'Collection Date'" sortable="'collection_date'">{{dt.collection_date}}</td>
                                                    <td title="'Action'" align="center">
                                                        <button class="btn btn-success small-print-button" type="button" ng-click="reprint_soa('<?php echo base_url(); ?>assets/pdf/' + dt.file_name)">
                                                            <i class="fa fa-print"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr class="ng-cloak" ng-show="!ajaxData.length && !isLoading">
                                                    <td colspan="5">
                                                        <center>No Data Available.</center>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> <!-- EDITABLE GRID END ROW -->
                                </div>
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->
</div> <!-- End of Container -->
</body>
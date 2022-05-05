
<?php foreach ($result as $value): ?>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="tenant_id"><b>Tenant ID</b></label>
                    <div class="col-lg-8">
                        <input type="text" value="<?php echo $value['id']; ?>"  readonly name = "id" style = "display:none" id="id">
                        <input type="text" value="<?php echo $value['tenant_id']; ?>"  readonly name = "tenant_id" required class="form-control" id="tenant_id">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="contract_no"><b>Contract No.</b></label>
                    <div class="col-lg-8">
                        <input type="text" value="<?php echo $value['contract_no']; ?>" readonly name = "contract_no" required class="form-control" id="contract_no">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="tin"><b>TIN</b></label>
                    <div class="col-lg-8">
                        <input type="text" value="<?php echo $value['tin']; ?>" autocomplete="off" name = "tin"  class="form-control" id="tin">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="tenant_type"><b>Tenant Type</b></label>
                    <div class="col-lg-8">
                        <select class="form-control"   name = "tenant_type" required>
                            <option><?php echo $value['tenant_type']; ?></option>
                            <option>AGC-Subsidiary</option>
                            <option>Cooperative</option>
                            <option>Government Agencies(w/ Basic)</option>
                            <option>Government Agencies(w/o Basic)</option>
                            <option>Private Entities</option>   
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="rental_type"><b>Rental Type </b></label>
                    <div class="col-lg-8">
                        <select class="form-control" id = "rental_type" onchange="check_rentalType()"  name = "rental_type" required>
                            <option><?php echo $value['rental_type']; ?></option>
                            <option>Fixed</option>
                            <option>Percentage</option>
                            <option>Fixed Plus Percentage</option>
                            <option>Fixed/Percentage w/c Higher</option>
                            <option>WOF</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div id = "percentage_holder">
            <?php if ($value['rental_type'] != 'Fixed' && $value['rental_type'] != 'WOF'): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-4 control-label col-lg-4" for="rent_percentage"><b>Rent Percentage </b></label>
                            <div class="col-lg-8">
                                <input type="text" value="<?php echo $value['rent_percentage']; ?>" name = "rent_percentage" required class="form-control currency" id="rent_percentage">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="vat_agreement"><b>Percentage Base</b></label>
                    <div class="col-lg-8">
                        <select class="form-control" id = "sales"  name = "sales">
                            <option><?php echo $value['sales']; ?></option>
                            <option>SALES</option>
                            <option>GROSS</option>
                            <option>NET</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="penalty_exempt"><b>Penalty Exempt</b></label>
                    <div class="col-lg-1">
                        <?php if ($value['penalty_exempt'] == '1'): ?>
                            <input type="checkbox" id = "penalty_exempt" checked name = "penalty_exempt" value = "1" checked data-toggle="toggle">
                        <?php else: ?>
                            <input type="checkbox" id = "penalty_exempt" name = "penalty_exempt" value = "1"  data-toggle="toggle">
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="vatable"><b>Vatable</b></label>
                    <div class="col-lg-1">
                        <?php if ($value['is_vat'] == TRUE): ?>
                            <input type="checkbox" id = "vatable" checked name = "vatable" value = "Added" checked data-toggle="toggle">
                        <?php else: ?>
                            <input type="checkbox" id = "vatable" name = "vatable" value = "Added"  data-toggle="toggle">
                        <?php endif ?>
                    </div>
                    <label class="col-sm-3 control-label col-lg-3"  for="less_wht"><b>Less WHT</b></label>
                    <div class="col-lg-1">
                        <?php if ($value['wht'] == TRUE): ?>
                            <input type="checkbox" name = "less_wht" value = "Added" checked id = "less_wht" data-toggle="toggle">
                        <?php else: ?>
                            <input type="checkbox" name = "less_wht" value = "Added" id = "less_wht" data-toggle="toggle">
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="vat_percentage"><b>Vat Percentage </b></label>
                    <div class="col-lg-8">
                        <input type="text" value="<?php echo $value['vat_percentage']; ?>" name = "vat_percentage" required class="form-control currency" id="vat_percentage">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="wht_percentage"><b>WHT Percentage </b></label>
                    <div class="col-lg-8">
                        <input type="text" value="<?php echo $value['wht_percentage']; ?>" name = "wht_percentage" required class="form-control currency" id="wht_percentage">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="vat_agreement"><b>Vat Agreement</b></label>
                    <div class="col-lg-8">
                        <select class="form-control" id = "vat_agreement"  name = "vat_agreement">
                            <option><?php echo $value['vat_agreement']; ?></option>
                            <option>Exclusive</option>
                            <option>Inclusive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="increment_percentage"><b>Increment Percentage</b></label>
                    <div class="col-lg-8">
                        <select class="form-control"  required name = "increment_percentage">
                            <option><?php echo $value['increment_percentage']; ?></option>
                            <option value="2">2%</option>
                            <option value="3">3%</option>
                            <option value="5">5%</option>
                            <option value="6">6%</option>
                            <option value="8">8%</option>
                            <option value="10">10%</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="increment_frequency"><b>Increment Frequency</b></label>
                    <div class="col-lg-8">
                        <select class="form-control"  required name = "increment_frequency">
                            <option><?php echo $value['increment_frequency']; ?></option>
                            <option>None</option>
                            <option>Annual</option>
                            <option>Biennial</option>
                            <option>Triennial</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="opening_date"><b>Opening Date</b></label>
                    <div class="col-lg-8">
                        <input type="text" autocomplete="off" value = "<?php echo $value['opening_date']; ?>"  required name = "opening_date" required class="form-control" id="opening_date">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" for="expiry_date"><b>Expiry Date</b></label>
                    <div class="col-lg-8">
                        <input type="text" autocomplete="off" value = "<?php echo $value['expiry_date']; ?>"  required name = "expiry_date" required class="form-control" id="expiry_date">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type = "submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
    </div>
<?php endforeach ?>


<script type="text/javascript" src="<?php echo base_url(); ?>js/admin-scripts.js"></script>
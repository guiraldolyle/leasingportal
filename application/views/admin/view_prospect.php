
<?php foreach ($result as $value): ?>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4"><b>Trade Name</b></label>
                    <div class="col-lg-8">
                        <input type="text" value="<?php echo $value['trade_name']; ?>" readonly class="form-control" >
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4"><b>Corporate Name</b></label>
                    <div class="col-lg-8">
                        <input type="text" value="<?php echo $value['corporate_name']; ?>" readonly class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" ><b>Address</b></label>
                    <div class="col-lg-8">
                        <input type="text" value="<?php echo $value['address']; ?>" readonly  class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4"><b>Lessee Type</b></label>
                    <div class="col-lg-8">
                        <input type = "text" value="<?php echo $value['leasee_type'] ?>" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" ><b>First Category </b></label>
                    <div class="col-lg-8">
                        <input type = "text" value="<?php echo $value['first_category'] ?>" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" ><b>Second Category </b></label>
                    <div class="col-lg-8">
                        <input type = "text" value="<?php echo $value['second_category'] ?>" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4" ><b>Third Category </b></label>
                    <div class="col-lg-8">
                        <input type = "text" value="<?php echo $value['third_category'] ?>" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4"><b>Contact Person </b></label>
                    <div class="col-lg-8">
                        <input type = "text" value="<?php echo $value['contact_person'] ?>" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4"><b>Contact Number </b></label>
                    <div class="col-lg-8">
                        <input type = "text" value="<?php echo $value['contact_number'] ?>" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4"><b>Remarks </b></label>
                    <div class="col-lg-8">
                        <textarea rows="3" readonly class="form-control"><?php echo $value['remarks'] ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4"><b>Request Date </b></label>
                    <div class="col-lg-8">
                        <input type = "text" value="<?php echo $value['request_date'] ?>" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4"><b>Status </b></label>
                    <div class="col-lg-8">
                        <input type = "text" value="<?php echo $value['status'] ?>" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label col-lg-4"><b>Prepared By </b></label>
                    <div class="col-lg-8">
                        <input type = "text" value="<?php echo $value['prepared_by'] ?>" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
    </div>
<?php endforeach ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/admin-scripts.js"></script>
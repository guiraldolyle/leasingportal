        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Payment Advice</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Payment Advice</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Payment Advice Form</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form class="form-horizontal">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Store Location</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="inputEmail3" value="Island City Mall">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label"><span class="important">*</span> Bank Account</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="bank_code" id="bank_code" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label"><span class="important">*</span> Account Deposited</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="bank_code" id="bank_code" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label"><span class="important">*</span> Account Number</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inputPassword3">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label"><span class="important">*</span> Account Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inputPassword3">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label"><span class="important">*</span> Payment Date</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" id="inputPassword3">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label"><span class="important">*</span> Payment Type</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="bank_code" id="bank_code" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>One Location</option>
                                                    <option>Multi Location</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label"><span class="important">*</span> SOA No.</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="bank_code" id="bank_code" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>One Location</option>
                                                    <option>Multi Location</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-group form-group">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label">Total Amount Payable</label>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><strong>₱</strong></span>
                                            </div>
                                            <input type="text" class="form-control text-right col-sm-12 col-md-6 col-lg-6" id="inputPassword3" value="0.00">
                                        </div>

                                        <div class="input-group form-group">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label"><span class="important">*</span> Amount Paid</label>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><strong>₱</strong></span>
                                            </div>
                                            <input type="text" class="form-control text-right col-sm-12 col-md-6 col-lg-6" id="inputPassword4" value="0.00">
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label"><span class="important">*</span> Proof of Transfer</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" name="proof" style="height: 45px;">
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <!-- <button type="submit" class="btn btn-info">Sign in</button> -->
                                            <button type="submit" class="btn btn-info float-right"><i class="fas fa-paper-plane"></i> Submit</button>
                                        </div>
                                        <!-- /.card-footer -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
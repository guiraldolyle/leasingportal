        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Utility Readings</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Utility Readings</li>
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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label">Trade Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="inputEmail3" value="ALVAS FOOD PROCESSING - CANTEEN" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label">Tenant ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="inputEmail3" value="ICM-LT000382" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-3 col-form-label"><span class="important">*</span>Date From</label>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                </div>
                                                <input type="date" class="form-control" placeholder="Username">
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="inputPassword3" class="col-sm-3 col-form-label"><span class="important">*</span>Date To</label>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                </div>
                                                <input type="date" class="form-control" placeholder="Username">
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label"></label>
                                                <div class="col-sm-9">
                                                    <button class="btn btn-block btn-primary"><i class="fas fa-cogs"></i> Generate Ledger</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <table id="utilityReadings" class="table table-striped table-bordered responsive nowrap table-sm display">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th class="text-center">Invoice. No</th>
                                                        <th class="text-center">Posting Date</th>
                                                        <th class="text-center">Description</th>
                                                        <th class="text-center">UOM</th>
                                                        <th class="text-center">Previous Reading</th>
                                                        <th class="text-center">Currrent Reading</th>
                                                        <th class="text-center">Unit Price</th>
                                                        <th class="text-center">Total Unit</th>
                                                        <th class="text-center">Actual Amount</th>
                                                        <th class="text-center">Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>IC0038389</td>
                                                        <td>2022-03-31</td>
                                                        <td>Water</td>
                                                        <td>Per Cubic Meter</td>
                                                        <td>5105.60</td>
                                                        <td class="text-right">5448.57</td>
                                                        <td class="text-right">52.00</td>
                                                        <td class="text-right">342.97</td>
                                                        <td class="text-right">17834.44</td>
                                                        <td>17834.44</td>
                                                    </tr>
                                                    <tr>
                                                        <td>IC0038389</td>
                                                        <td>2022-03-31</td>
                                                        <td>Gas</td>
                                                        <td>Inputted</td>
                                                        <td>0.00</td>
                                                        <td class="text-right">0.00</td>
                                                        <td class="text-right">0.00</td>
                                                        <td class="text-right">0.00</td>
                                                        <td class="text-right">266499.50</td>
                                                        <td>266499.50</td>
                                                    </tr>
                                                    <tr>
                                                        <td>IC0038389</td>
                                                        <td>2022-03-31</td>
                                                        <td>Electricity</td>
                                                        <td>Per Kilowatt Hour</td>
                                                        <td>1794960.00</td>
                                                        <td class="text-right">1805160.00</td>
                                                        <td class="text-right">11.00</td>
                                                        <td class="text-right">10200.00</td>
                                                        <td class="text-right">112200.00</td>
                                                        <td>112200.00</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
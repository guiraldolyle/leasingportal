        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">My Ledger</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">My Ledger</li>
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
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">Forwarded Balance</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control text-right" id="inputEmail3" value="0.00" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <table id="myLedgerTable" class="table table-striped table-bordered responsive nowrap table-sm display">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th class="text-center">Doc Type</th>
                                                        <th class="text-center">Doc No.</th>
                                                        <th class="text-center">Type</th>
                                                        <th class="text-center">Posting Date</th>
                                                        <th class="text-center">Due Date</th>
                                                        <th class="text-center">Total Payable</th>
                                                        <th class="text-center">Payment</th>
                                                        <th class="text-center">Balance</th>
                                                        <th class="text-center">Running Balance</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">Invoice</td>
                                                        <td class="text-center">IC0038383</td>
                                                        <td class="text-center">Basic Rent</td>
                                                        <td class="text-center">2022-03-31</td>
                                                        <td class="text-center">2022-04-15</td>
                                                        <td class="text-right">480,683.60</td>
                                                        <td class="text-right">0.00</td>
                                                        <td class="text-right">480,683.60</td>
                                                        <td class="text-right">480,683.60</td>
                                                        <td class="text-center">
                                                            <button class="btn btn-info btn-xs" title="Payment Details"><i class="fas fa-search"></i></button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Invoice</td>
                                                        <td class="text-center">IC0038389</td>
                                                        <td class="text-center">Other Charges</td>
                                                        <td class="text-center">2022-03-31</td>
                                                        <td class="text-center">2022-04-15</td>
                                                        <td class="text-right">532,496.25</td>
                                                        <td class="text-right">0.00</td>
                                                        <td class="text-right">532,496.25</td>
                                                        <td class="text-right">1,013,179.85</td>
                                                        <td class="text-center">
                                                            <button class="btn btn-info btn-xs" title="Payment Details"><i class="fas fa-search"></i></button>
                                                        </td>
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
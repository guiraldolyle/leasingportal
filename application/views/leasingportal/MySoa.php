        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">My SOA</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">My SOA</li>
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
                                        <div class="col-12">
                                            <div class="input-group input-group">
                                                <label for="inputPassword3" class="col-sm-2 col-form-label">Trade Name: </label>
                                                <input type="text" class="form-control" value="<?php echo $this->session->userdata('trade_name') ?>" readonly>
                                                <span class="input-group-append">
                                                    <button type="button" class="btn btn-info btn-flat">View SOAs</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <table id="mySoaTable" class="table table-striped table-bordered responsive nowrap table-sm display">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th class="text-center">Tenant ID</th>
                                                        <th class="text-center">SOA No.</th>
                                                        <th class="text-center">Filename</th>
                                                        <th class="text-center">Collection Date</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">ICM-LT000015</td>
                                                        <td class="text-center">B0017796</td>
                                                        <td class="text-center">ICM-LT0000151649141965.pdf</td>
                                                        <td class="text-center">2022-04-15</td>
                                                        <td class="text-center" style="width: 10%;">
                                                            <button class="btn btn-info btn-sm"><i class="fas fa-print"></i> View SOA</button>
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
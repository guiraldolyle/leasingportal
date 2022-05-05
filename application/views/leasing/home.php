

<div class="container" ng-controller = "CtrlChart" ng-init = "homeChart();barChart();doughnutChart();pieChart()">
    <div class="well">
        <div class="row">
            <div class="col-md-6">
                <div class="greeting">Home page | AGC Leasing</div>
            </div>
            <div class="col-md-6 pull-right">
                <div class="dateTime pull-right ng-cloak" ng-init = "tick('<?php echo base_url(); ?>index.php/ctrl_leasing/get_dateTime')">
                    <p>{{ clock  | date:'medium'}}</p>
                </div>
            </div>
        </div>
        <hr />
        <div class="row" >
            <div class="col-md-12 ng-cloak"  >
                <div class="col-md-6">
                   <div class="panel panel-default ">
                        <div class="panel-heading panel-leasing">Long Term & Short Term Tenants per Year</div>
                        <div class="panel-body">
                            <div class='canvasContainer'>
                                <canvas 
                                    id="line"
                                    class="chart chart-line" 
                                    chart-data="data"
                                    chart-labels="labels" 
                                    chart-legend="true" 
                                    chart-series="series"
                                    chart-colours = "colours">
                                </canvas>
                            </div      
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading panel-leasing">Tenants per Lessee Type</div>
                    <div class="panel-body">
                        <div class='canvasContainer'>
                            <canvas 
                                id="bar"
                                class="chart chart-bar" 
                                chart-data="barData"
                                chart-labels="barLabels" 
                                chart-legend="true" 
                                chart-series="barSeries"
                                chart-colours = "barColours">
                            </canvas>
                        </div      
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading panel-leasing">Tenants per Area Classification</div>
                    <div class="panel-body">
                        <div class='canvasContainer'>
                            <canvas 
                                id="doughnut"
                                class="chart chart-doughnut" 
                                chart-data="doughnutData"
                                chart-labels="doughnutLabels">
                            </canvas>
                        </div      
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                    <div class="panel-heading panel-leasing">Tenants per Area Type</div>
                    <div class="panel-body">
                        <div class='canvasContainer'>
                            <canvas 
                                id="pie"
                                class="chart chart-pie" 
                                chart-data="pieData"
                                chart-labels="pieLabels">
                            </canvas>
                        </div      
                    </div>
                </div>
            </div>
        </div>
        
    </div>

</div>

</body>
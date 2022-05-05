window.myApp.controller('invoiceOverrideHstryCntrl',  function($scope, $http, $timeout, moment, $sce, $q, filterFilter, $rootScope, $interval){

	$scope.tenant = null;
    $scope.ctrl_data = {
        supp_docs : [],
        invoice_details : []
    }

	$scope.generate_tenantCredentials = function(trade_name, tenancy_type)
    {	
        var data = {trade_name, tenancy_type};
        let url =  $base_url +  'leasing/get_tenant_details';
   
        $.get(url, data, function(res){
        	$scope.tenant = res;

            if(res.tenant_id){
                let url =  $base_url +  'leasing/get_invoice_override_data/' + res.tenant_id;
                $rootScope.$emit("CallTablelistMethod", url);
            }

        	$scope.$apply();
        }, 'json');
    }

    $scope.displaySuppDocs = function(supp_docs){
        $scope.ctrl_data.supp_docs = supp_docs;
        $('#supported-docs-modal').modal('show');
        
    }

    $scope.displayInvoiceDetails = function(invoice_details){
        console.log(invoice_details);
        $scope.ctrl_data.invoice_details = invoice_details;
        $('#invoice-details-modal').modal('show');
        
    }

    /*$scope.getInvoiceOverrideData = (tenant_id)=>{

        let data = {tenant_id};
        

        $.get(url, data, function(res){

            if(res.tenant_id){
                
            }

            $scope.$apply();
        }, 'json');
    }*/

})
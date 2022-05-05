window.myApp.controller('soaCntrl',  function($scope, $http, $timeout, moment, $sce, $q, filterFilter, $rootScope, $interval){

	$scope.soa_docs = [];
	$scope.tenant = null;

    $scope.soa = {};
    $scope.soa.advance_payment = 0;

	$scope.calculate_colDate = function(posting_date)
    {
        $scope.collection_date = moment(posting_date).add(10, 'days').format('YYYY-MM-DD');
        $scope.min_collectionDate = moment(moment(posting_date).add(7, 'days')).format('YYYY-MM-DD');
    }

	$scope.generate_soaCredentials = function(trade_name, tenancy_type)
    {	

    	$scope.soa_docs = [];
    	$scope.date_created = '';
    	$scope.collection_date = '';
       
        var data = {trade_name, tenancy_type};
        let url =  $base_url +  'leasing/get_tenant_details';
   
        $.get(url, data, function(res){
        	$scope.tenant = res;
        	$scope.$apply();
        }, 'json');
    }

    $scope.generateTenantBalances = function(){
	
    	$scope.isLoading = true;
    	$scope.soa_docs = [];

    	let url =  $base_url +  'leasing/get_tenant_balances/';

    	let data = {
    		tenant_id : $scope.tenant.tenant_id,
    		date_created : $scope.date_created
    	}

    	$.post(url, data, function(res){
    		$scope.isLoading = false;
        	$scope.soa_docs = res.docs;
            $scope.soa.advance_payment = res.advance;

        	$scope.$apply();
        }, 'json');
    }

    $scope.removeFromSoa =  function(soa){
    	let index = $scope.soa_docs.indexOf(soa);
    	$scope.soa_docs.splice(index, 1);
    }

    $scope.total = function(){
    	let total = 0;
    	$scope.soa_docs.forEach((doc)=>{
    		total+= parseNumber(doc.balance)
    	})

    	return total;
    }


    $scope.generateSoa = function(e){
        e.preventDefault();

        pop.confirm('Are you sure you want to continue generating SOA?', (res)=>{
            if(!res) return;
            let data = $(e.target).serializeObject();

            data.soa_docs       = JSON.parse(angular.toJson($scope.soa_docs));

            console.log(data);

            let url = $base_url + 'leasing/generate_soa';

            let loader = pop.loading('Generating SOA. Please wait ...');

            $.post(url, data, function(res){
                loader.modal('hide');

                if(res.type == 'success'){
                    window.open($base_url+'assets/pdf/'+res.file);
                    notify('success', res.msg);
                    return;
                }

                generate(res.type, res.msg);
                
            }, 'json');

        })
        
    }
})
window.myApp.controller('AdvPaymentCntrl',  function($scope, $http, $timeout, moment, $sce, $q, filterFilter, $rootScope, $interval){

	$scope.pmt = {application : null};
	$scope.tenant;
    $scope.stores = [];

    /*$scope.tender_types = [
        {id : 1, desc : 'Cash'},
        {id : 2, desc : 'Check'},
        {id : 3, desc : 'Bank to Bank'},
        {id : 80, desc : 'JV payment - Business Unit'},
        {id : 81, desc : 'JV payment - Subsidiary'},
        {id : 11, desc : 'Unidentified Fund Transfer'},
        {id : 12, desc : 'Internal Payment'},

    ];*/

    $scope.clearPaymentData = function(){
        $scope.pmt = {application : null};
        $scope.tenant = {};
        $scope.getInitData();
    }

    $scope.getInitData = function(){
        let url = $base_url + 'leasing/get_payment_initial_data';

        $.get(url,function(res){
            $scope.banks = res.banks;
            $scope.pmt.uft_no = res.uft_no;
            $scope.pmt.ip_no = res.ip_no;
            $scope.stores   = res.stores;

            $scope.pmt.bank = res.banks[0] ? res.banks[0] : {};
            //console.log($scope.pmt.bank);
            $scope.$apply();
        }, 'json');

    }


	$scope.generate_paymentCredentials = function(trade_name, tenancy_type)
    {	
        $scope.pmt.application  = null;
    	$scope.pmt.payment_date = '';
       
        var data = {trade_name, tenancy_type};
        let url =  $base_url +  'leasing/get_tenant_details';
   
        $.get(url, data, function(res){
        	$scope.tenant = res;
        	$scope.$apply();
        }, 'json');
    }



    $scope.savePayment = function(e){
        e.preventDefault();

        pop.confirm('Do you really want to <b>post this payment</b>?', (res)=>{
            if(!res) return;

            //let payment_docs    = JSON.parse(angular.toJson($scope.payment_docs));
            let formData        = new FormData(e.target);

            //formData            = convertModelToFormData(payment_docs, formData, 'payment_docs');

            let loader = pop.loading('Posting Payment. Please wait ...');
            
            let url = $base_url + 'leasing/save_advancePayment';

            $.ajax({
                type:'POST',
                url: url,
                data: formData,
                enctype: 'multipart/form-data',
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(res)
                {
                    loader.modal('hide');

                    if(res.type == 'success'){
                        if(res.file){
                            window.open($base_url+'assets/pdf/'+res.file);
                        }

                        notify('success', res.msg);

                        $scope.clearPaymentData();
                        return;
                    }

                    generate(res.type, res.msg);
                }
            });


        })
    }


    var ccm_customers = [];

    $scope.getCcmCustomers = function(){
        let url = $base_url + 'ctrl_cfs/populate_ccm_customer'
        $http.post(url).success(function(result){
            ccm_customers = result;
        });
    }

    $scope.ccm_cust_autocomplete = {
        suggest: (term)=>{
            var q = term.toLowerCase().trim();
            var results = [];

            // Find first 10 states that start with `term`.
            for (var i = 0; i < ccm_customers.length && results.length < 10; i++) {

                var state = ccm_customers[i].fullname;
                if (state.toLowerCase().indexOf(q) === 0)
                results.push({ label: state, value: state });
            }

            return results;
        }
    };

})
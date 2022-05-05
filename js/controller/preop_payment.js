window.myApp.controller('preopPaymentCntrl',  function($scope, $http, $timeout, moment, $sce, $q, filterFilter, $rootScope, $interval){

    $scope.pmt = {application : null};
    $scope.tenant;
    $scope.invoices = [];
    $scope.payment_docs = [];
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
        $scope.invoices = [];
        $scope.payment_docs = [];
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
            console.log($scope.pmt.bank);
            $scope.$apply();
        }, 'json');

    }


    $scope.generate_paymentCredentials = function(trade_name, tenancy_type)
    {   
        $scope.payment_docs = [];
        $scope.pmt.soa_docs = [];
        $scope.invoices = [];
        $scope.pmt.application  = null;
        $scope.pmt.payment_date = '';
        $scope.pmt.preop_account = '';
        $scope.pmt.preop_amount = '';
       
        var data = {trade_name, tenancy_type};
        let url =  $base_url +  'leasing/get_tenant_details';
   
        $.get(url, data, function(res){
            $scope.tenant = res;
            $scope.$apply();
        }, 'json');
    }

    var loader = null;

    $scope.getSoaWithBalances = function(tenant_id, posting_date){

        $scope.payment_docs = [];
        $scope.pmt.soa_docs = [];
        $scope.invoices = [];
        $scope.pmt.application  = null;

        if(!loader) {
            loader = pop.loading('Collecting data. Please wait ...');
        }else{
            loader.modal('show');
        }

        let url =  $base_url +  `leasing/getSoaWithBalances/${tenant_id}/${posting_date}`;
        
        $.get(url, function(res){
            $scope.pmt.soa_docs = res;
            if(res.length > 0){
                $scope.pmt.soa = res[0];
                $scope.getInvoicesBySoaNo(tenant_id, res[0].soa_no);
            }else{
                pop.alert('<strong>No SOA with balances found!</strong>');
            }

            loader.modal('hide');
            $scope.$apply();
        }, 'json');
    }

    $scope.getInvoicesBySoaNo = async function(tenant_id, soa_no){

        if(!soa_no || soa_no == '' || soa_no.length == 0){
            return; 
        }
        
        $scope.payment_docs = [];
        $scope.invoices = [];
        $scope.pmt.application  = null;

        if(!loader) {
            loader = pop.loading('Collecting data. Please wait ...');
        }else{
            loader.modal('show');
        }

        let url =  $base_url +  `leasing/getPreopPaymentInvoicesBySoaNo/${tenant_id}/${soa_no}`;
 
        await $.get(url, function(res){
            $scope.invoices = res;
            $scope.$apply();
        }, 'json');

        loader.modal('hide');
    }

    $scope.getPreopBalance = function(tenant_id, preop_account){

        let url =  $base_url +  `leasing/get_preop_balance/${tenant_id}/${preop_account}`;
        //loader = pop.loading('Collecting data. Please wait ...');

        $.get(url, function(res){

            //loader.modal('hide');

            if(res.balance == '' || res.balance == 0 || !res.balance){
                pop.alert(`<strong>No ${preop_account} amount available! </strong>`);
                $scope.pmt.preop_amount = 0;
            }else{
                $scope.pmt.preop_amount = res.balance;
            }
            
            $scope.$apply();
        }, 'json');
    }

    $scope.selectInvoice = function(invoice){

        let arr_num = $scope.invoices.map(function(i){
            if(!i.sequence) return 0;
            return i.sequence;
        })

        let nextNum = Math.max(...arr_num) + 1;


        invoice.sequence = invoice.selected ? nextNum : null;

        //console.log($scope.invoices);

    }

    $scope.getSelectedInvoices = function(){
        let selectedInvs = $scope.invoices.filter((inv)=>{
            return inv.selected;
        })

        return selectedInvs;
    }

    $scope.applySelectedInvoices = function(){

        pop.confirm('Are you sure you want to apply the payment to this selected invoices?', (res)=>{
            if(!res) return;

            $scope.payment_docs = [];

            let selectedInvs = $scope.getSelectedInvoices();

            let sortedInvs = selectedInvs.sort(function(a, b){
                if(a.sequence == b.sequence) return 0;

                return b.sequence < a.sequence ? 1 : -1;
            })


            $scope.payment_docs = sortedInvs;

            $scope.pmt.application  = 'Apply To';

            $scope.$apply();

            $('#apply-to-modal').modal('hide');

        })
    }

    $scope.applyOldToNewest = function(){
        pop.confirm('Are you sure you want to apply payment base on invoice\'s posting date? <br/><b>(OLDEST TO NEWEST)</b>', (res)=>{
            if(!res) return;

            $scope.payment_docs = $scope.invoices;
            $scope.pmt.application  = 'Oldest to Newest';
            $scope.$apply();

        })
    }

    $scope.totalPayable = function(){
        let total = 0;

        $scope.payment_docs.forEach((doc)=>{
            total+= parseNumber(doc.balance);
        })

        return total;
    }

    $scope.getMaxPaidAmount = function(){
        let preop_amount = parseNumber($scope.pmt.preop_amount);
        let payable_amount = parseNumber($scope.totalPayable());

        return payable_amount < preop_amount ? payable_amount : preop_amount;
    }

    $scope.validTenderAmount = function(){
        let tender_amount = parseNumber($scope.pmt.tender_amount);
        let max_amount = parseNumber($scope.getMaxPaidAmount());

        if(tender_amount == 0) return false;

        return tender_amount <= max_amount;
    }


    $scope.savePayment = function(e){
        e.preventDefault();

        pop.confirm('Do you really want to <b>post this payment</b>?', (res)=>{
            if(!res) return;

            let payment_docs    = JSON.parse(angular.toJson($scope.payment_docs));
            let formData        = new FormData(e.target);

            formData            = convertModelToFormData(payment_docs, formData, 'payment_docs');

            let loader = pop.loading('Posting Payment. Please wait ...');
            
            let url = $base_url + 'leasing/save_paymentUsingPreop';

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


})
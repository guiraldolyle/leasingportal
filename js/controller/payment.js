window.myApp.controller('paymentCntrl', function($scope, $http, $timeout, moment, $sce, $q, $filter, $rootScope, $interval) {

    $scope.pmt = { application: null };
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

        $scope.clearPaymentData = function() {
            $scope.pmt = { application: null };
            $scope.tenant = {};
            $scope.invoices = [];
            $scope.payment_docs = [];
            $scope.getInitData();
        }

        $scope.getInitData = function() {
            let url = $base_url + 'leasing/get_payment_initial_data';

            $.get(url, function(res) {
                $scope.banks = res.banks;
                $scope.pmt.uft_no = res.uft_no;
                $scope.pmt.ip_no = res.ip_no;
                $scope.stores = res.stores;

                $scope.pmt.bank = res.banks[0] ? res.banks[0] : {};
                console.log($scope.pmt.bank);
                $scope.$apply();
            }, 'json');

        }


        $scope.generate_paymentCredentials = function(trade_name, tenancy_type) {
            $scope.payment_docs = [];
            $scope.pmt.soa_docs = [];
            $scope.invoices = [];
            $scope.pmt.application = null;
            $scope.pmt.payment_date = '';

            var data = { trade_name, tenancy_type };
            let url = $base_url + 'leasing/get_tenant_details';

            $.get(url, data, function(res) {
                $scope.tenant = res;
                $scope.$apply();
            }, 'json');
        }

        var loader = null;

        $scope.getSoaWithBalances = function(tenant_id, posting_date) {

            $scope.payment_docs = [];
            $scope.pmt.soa_docs = [];
            $scope.invoices = [];
            $scope.pmt.application = null;

            if (!loader) {
                loader = pop.loading('Collecting data. Please wait ...');
            } else {
                loader.modal('show');
            }

            let url = $base_url + `leasing/getSoaWithBalances/${tenant_id}/${posting_date}`;

            $.get(url, function(res) {
                $scope.pmt.soa_docs = res;
                if (res.length > 0) {
                    $scope.pmt.soa = res[0];
                    $scope.getInvoicesBySoaNo(tenant_id, res[0].soa_no);
                } else {
                    pop.alert('<strong>No SOA with balances found!</strong>');
                }

                loader.modal('hide');
                $scope.$apply();
            }, 'json');
        }

        $scope.getInvoicesBySoaNo = async function(tenant_id, soa_no) {

            if (!soa_no || soa_no == '' || soa_no.length == 0) {
                return;
            }

            $scope.payment_docs = [];
            $scope.invoices = [];
            $scope.pmt.application = null;

            if (!loader) {
                loader = pop.loading('Collecting data. Please wait ...');
            } else {
                loader.modal('show');
            }

            let url = $base_url + `leasing/getInvoicesBySoaNo/${tenant_id}/${soa_no}`;

            await $.get(url, function(res) {
                $scope.invoices = res;
                $scope.$apply();
            }, 'json');

            loader.modal('hide');
        }

        $scope.selectInvoice = function(invoice) {

            let arr_num = $scope.invoices.map(function(i) {
                if (!i.sequence) return 0;
                return i.sequence;
            })

            let nextNum = Math.max(...arr_num) + 1;


            invoice.sequence = invoice.selected ? nextNum : null;

        //console.log($scope.invoices);

    }

    $scope.getSelectedInvoices = function() {
        let selectedInvs = $scope.invoices.filter((inv) => {
            return inv.selected;
        })

        return selectedInvs;
    }

    $scope.applySelectedInvoices = function() {

        pop.confirm('Are you sure you want to apply the payment to this selected invoices?', (res) => {
            if (!res) return;

            $scope.payment_docs = [];

            let selectedInvs = $scope.getSelectedInvoices();

            let sortedInvs = selectedInvs.sort(function(a, b) {
                if (a.sequence == b.sequence) return 0;

                return b.sequence < a.sequence ? 1 : -1;
            })


            $scope.payment_docs = sortedInvs;

            $scope.pmt.application = 'Apply To';

            $scope.$apply();

            $('#apply-to-modal').modal('hide');

        })
    }

    $scope.applyOldToNewest = function() {
        pop.confirm('Are you sure you want to apply payment base on invoice\'s posting date? <br/><b>(OLDEST TO NEWEST)</b>', (res) => {
            if (!res) return;

            $scope.payment_docs = $scope.invoices;
            $scope.pmt.application = 'Oldest to Newest';
            $scope.$apply();

        })
    }

    $scope.totalPayable = function() {
        let total = 0;

        $scope.payment_docs.forEach((doc) => {
            total += parseNumber(doc.balance);
        })

        return total;
    }




    $scope.savePayment = function(e) {
        e.preventDefault();

        let totalPayable = parseNumber($scope.totalPayable());
        let amount_paid = parseNumber($scope.pmt.amount_paid);

        // if(amount_paid > totalPayable && $scope.pmt.application == 'Apply To'){
        //     let adv_amt = parseNumber(amount_paid - totalPayable);
        //     adv_amt = $filter('currency')(adv_amt, 'Php ');
        //     pop.alert(`<b>Advance Payment</b> amounting <b>${adv_amt}</b> was detected. </br>
        //         <b>Advance Payment</b> is <b>not allowed</b> when using <b>"Apply To"</b>.  </br>
        //         Please <b>select more invoice</b> or choose <b>"OLDEST TO NEWEST"</b> to proceed!`);
        //     return;
        // }

        pop.confirm('Do you really want to <b>post this payment</b>?', (res) => {
            if (!res) return;

            let payment_docs = JSON.parse(angular.toJson($scope.payment_docs));
            let formData = new FormData(e.target);

            formData = convertModelToFormData(payment_docs, formData, 'payment_docs');

            let loader = pop.loading('Posting Payment. Please wait ...');

            let url = $base_url + 'leasing/save_payment';

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                enctype: 'multipart/form-data',
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    loader.modal('hide');

                    if (res.type == 'success') {
                        if (res.file) {
                            window.open($base_url + 'assets/pdf/' + res.file);
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

    $scope.getCcmCustomers = function() {
        let url = $base_url + 'ctrl_cfs/populate_ccm_customer'
        $http.post(url).success(function(result) {
            ccm_customers = result;
        });
    }

    $scope.ccm_cust_autocomplete = {
        suggest: (term) => {
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

    /*$scope.test = function(){
        $http( {
            url: 'Yawa', 
            method: "POST",
            params : {var1 : 'PROR#21222'},
        })
    }*/

    $scope.paymentType = function() {
        if ($scope.payment_type == 'One Location') {
            if (!loader) {
                loader = pop.loading('Collecting data. Please wait ...');
            } else {
                loader.modal('show');
            }

            let url = $base_url + `portal/soa_balances`;


            $http.get(url).success(function(result) {
                if (result.length > 0) {
                    $scope.soaDoc = result;

                    $scope.soanumber = true;
                    $scope.amountpaid = true;
                    $scope.proofoftransfer = true;
                    $scope.multilocation = false;
                } else {
                    pop.alert('<strong>No SOA with balances found!</strong>');
                }

                loader.modal('hide');
            });
        } else {
            if (!loader) {
                loader = pop.loading('Collecting data. Please wait ...');
            } else {
                loader.modal('show');
            }

            let url = $base_url + `portal/soa_balances`;


            $http.get(url).success(function(result) {
                if (result.length > 0) {
                    pop.alert('<strong>SOA with balances found!</strong>');
                    $scope.soanumber = false;
                    $scope.amountpaid = true;
                    $scope.proofoftransfer = true;
                    $scope.multilocation = true;
                } else {
                    pop.alert('<strong>No SOA with balances found!</strong>');
                }

                loader.modal('hide');
            });
        }
    }

    $scope.getAmountSoa = function() {
        let url = $base_url + `portal/getSoa/${$scope.soa_data}`

        $http.get(url).success(function(result) {
            $scope.totalPayable = result.amount;
        });
    }

    $scope.savePaymentAdvice = function(e) {
        e.preventDefault();

        pop.confirm('Do you really want to <b>post this payment</b>?', (res) => {
            if (!res) return;

            var formData = new FormData(e.target);
            var multi   = JSON.parse(angular.toJson($scope.multi));
            formData     = convertModelToFormData(multi, formData, 'multi');
            // formData            = convertModelToFormData(payment_docs, formData, 'payment_docs');

            let loader = pop.loading('Posting Payment. Please wait ...');

            let url = $base_url + 'savePaymentAdvice';

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                enctype: 'multipart/form-data',
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(result) {
                    loader.modal('hide');

                    if (result.info == 'Success') {

                        notify('success', result.message);
                        return;
                    } else {
                        generate('error', result.message);
                    }
                }
            });


        })
    }

    $scope.getAccounts = function()
    {
        var store     = $('#storeLocation').val();
        var bank      = $scope.bank_code;
        var tenant_id = $tenantID;

        if (tenant_id == 'ICM-LT000008' || tenant_id == 'ICM-LT000442' || tenant_id == 'ICM-LT000492' || tenant_id == 'ICM-LT000035' || tenant_id == 'ICM-LT000120')
        {
            $scope.sAccounts = [{account:'1201-0089-95'}];
        }
        else if (store == 'ACT') 
        {
            if(tenant_id == 'ACT-LT000027')
            {
                $scope.sAccounts = [{account:'9471-0016-75'}];
            }
            else
            {
                $scope.sAccounts = [{account:'3059-7000-6462'}];
            }  
        }
        else if (tenant_id == 'ICM-LT000218' || tenant_id == 'ICM-LT000219') 
        {
            $scope.sAccounts = [{account:'3522-1000-63'}];
        }
        else
        {
            if (store == 'Alturas Mall') 
            {
                $scope.sAccounts = [{account:'3059-7000-5922'}];
            }
            else if(store == "Plaza Marcela")
            {
                $scope.sAccounts = [{account:'0612-0011-11'}];
            }
            else if(store == 'Alturas Mall' || tenant_id != 'ICM-LT000008' || tenant_id != 'ICM-LT000442' || tenant_id != 'ICM-LT000492' || tenant_id != 'ICM-LT000035' || tenant_id != 'ICM-LT000120')
            {
                $scope.sAccounts = [{account:'9471-0016-59'}];
            }
            else 
            {
                if(bank == 'PNB')
                {
                    $scope.sAccounts = [{account:'9471-0016-59'}, {account:'3059-7000-5922'}, {account:'3059-7000-6462'}];
                }
                else if(bank == 'Land Bank of the Philippines')
                {
                    $scope.sAccounts = [{account:'3522-1000-63'}, {account:'0612-0011-11'}];
                }
                else if(bank == 'Banks of the Philippine Islands')
                {
                    $scope.sAccounts = [{account:'1201-0089-95'}, {account:'9471-0016-75'}];
                }
            }
        }
    }

    })
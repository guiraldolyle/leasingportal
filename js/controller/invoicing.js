window.myApp.controller('invoicingCntrl',  function($scope, $http, $timeout, moment, $sce, $q, filterFilter, $rootScope, $interval){

	$scope.invoice_type = null;
	$scope.invoices = [];
	$scope.tenant = null;

	$scope.formData = {
		invoice_type : '',
		manual_basic : 0,
        retro_amount : 0,
        fixed_total_manual_basic : false,
        basic_override_token : null,
        showForm : true
	};


	$scope.days = {
		days_in_month : 31,
		days_occupied : 31,
	}

	var timeOut = null;

    $scope.preop_charges = [];

    $scope.getInitData = function(){
        //GET PREOPERATION CHARGES
        $.get($base_url + 'leasing/invoicing_init_data', function(res){
            $scope.preop_charges = res.preop_charges;
            $scope.cons_materials = res.cons_materials;
            console.log(res);
        }, 'json');

    }

	$scope.generate_invoicingCredentials = function(trade_name, tenancy_type)
    {
        // ==== Clear table first ==== //
        $scope.invoices = null;
        $scope.is_longTerm = "";

        var data = {trade_name, tenancy_type};
        let url =  $base_url +  'leasing/get_tenant_details';

    
    	$.get(url, data, function(res){
        	$scope.tenant = res;
        	console.log(res);

        	if(!res) 
        		return;


            $http.post($base_url+'leasing/selected_monthly_charges/' +  res.tenant_id).success(function(data) {
                //console.log(data);
                $scope.monthly_charges = data;
            });

            $http.post($base_url+'leasing/get_otherCharges/' +  res.tenant_id).success(function(data) {
                console.log(data);
                $scope.other_charges = data;
            });

        }, 'json');
    }

    $scope.selectMenu = function(menu, modal){

        if(menu == 'Basic Manual' && ($scope.tenant.rental_type =='Percentage') || $scope.tenant.rental_type =='Fixed/Percentage w/c Higher'){
            $scope.formData.fixed_total_manual_basic = true;
        }


    	$scope.formData.invoice_type = menu;

        if($scope.formData.basic_override_token == null && menu == 'Basic Manual'){
            $scope.formData.showForm = true;
            $('#managers-key-modal').modal('show');
        }else{
            $(modal).modal('show');
        }
 
    	
    }

    $scope.getManagersKey = function(e){
        e.preventDefault();

        let data = $(e.target).serializeObject();
        data.for ='invoice_basic_override_token';
        //console.log(data);

         $.post($base_url + 'leasing/get_managers_key', data, function(res){
            if(res.type == 'success'){
               $scope.formData.basic_override_token = res.token;
               $('#managers-key-modal').modal('hide');

               if($scope.formData.showForm)
                    $('#basicRental_modal').modal('show');
            }

            generate(res.type, res.msg);
            
        }, 'json');

    }

    $scope.menuIsNotSelected = function(menu){
    	return $scope.formData.invoice_type != menu && 
    		$scope.formData.invoice_type && 
    		$scope.formData.invoice_type.length > 0 && 
    		($scope.invoices && $scope.invoices.length > 0)
    }

    $scope.currentMonthDays = function(){
       let date = moment($scope.posting_date).subtract(15, "days");

       return parseNumber(getDaysInMonth(date));
    }

    $scope.$watch('posting_date', function(date) {

    	$scope.days.days_in_month = $scope.currentMonthDays()

    	if($scope.tenant && $scope.getDaysFromOpening() < $scope.currentMonthDays()){
    		$scope.days.days_occupied = parseNumber($scope.getDaysFromOpening());
    	}else{
    		$scope.days.days_occupied = $scope.currentMonthDays();
    	}

        $scope.formData.manual_basic =  $scope.basic_rental();	
    });

    $scope.$watch('tenant', function(tenant) {
    	if(tenant){
    		if($scope.getDaysFromOpening() < $scope.currentMonthDays())
    			$scope.days.days_occupied = parseNumber($scope.getDaysFromOpening());
    		else
    			$scope.days.days_occupied = $scope.currentMonthDays();
    	}

    	$scope.formData.manual_basic =  $scope.basic_rental();
    });


    $scope.calculate_dueDate = function(posting_date)
    {
        var suggested_dueDate = moment(posting_date).add(8, 'days');
        $scope.due_date = moment(suggested_dueDate).format('YYYY-MM-DD');

        $scope.min_dueDate = moment(moment(posting_date).add(7, 'days')).format('YYYY-MM-DD');
    }

    $scope.date_diff = function(from, to, unit = 'year', fraction = false){
    	return moment(from).diff(to, unit, fraction);
    }


    $scope.getDaysFromOpening = function(){

    	let days;
    	let opening_date = moment($scope.tenant.opening_date).format('YYYY-MM-DD');
    	let posting_date = moment($scope.posting_date).format('YYYY-MM-DD');


    	let opening_date_year = moment(opening_date).year();
    	let posting_date_year = moment(posting_date).year();

    	let diff_year = posting_date_year - opening_date_year;

    	opening_date = moment(opening_date).add(diff_year, 'years').format('YYYY-MM-DD');

    	let diff_month = $scope.date_diff(posting_date, opening_date, 'months');

    	if(diff_month == 0){
    		days = Math.abs($scope.date_diff(posting_date, opening_date, 'days')) + 1;
    	}else{
    		days = $scope.date_diff($scope.posting_date, $scope.tenant.opening_date, 'days');
    	}

    	
    	//console.log(days, opening_date, posting_date, diff_month);

    		
    	return parseNumber(days);
    }

    $scope.getMonthsFromOpening = function(){
    	let months = $scope.date_diff($scope.posting_date, $scope.tenant.opening_date, 'months');
    	return parseNumber(months);
    }

    $scope.is_incrementable = function(){
    	let tenant = $scope.tenant;

    	let is_incrementable = 0;

    	if(tenant.increment_percentage != 'None' && tenant.increment_frequency == 'Annual'){

    		is_incrementable = $scope.date_diff($scope.posting_date, tenant.opening_date);

    	}else if(tenant.increment_percentage != 'None' && tenant.increment_frequency == 'Biennial'){

    		is_incrementable = $scope.date_diff($scope.posting_date, tenant.opening_date)/2;

    	}else if(tenant.increment_percentage != 'None' && tenant.increment_frequency == 'Triennial'){

    		is_incrementable = $scope.date_diff($scope.posting_date, tenant.opening_date)/3;

    	}else{
    		is_incrementable = 0;
    	}

    	return Math.floor(is_incrementable);
    }

    
    $scope.basic_rental = function(){
    	let tenant = $scope.tenant;
  
    	if(!tenant) return;

    	let basic_rental = tenant.basic_rental ? tenant.basic_rental : 0;

    	if($scope.formData.invoice_type == 'Basic Manual'){
    		return parseNumber($scope.formData.manual_basic);
    	} 

    	if($scope.is_incrementable() > 0){

            let exponent = $scope.is_incrementable() - 1;

            basic_rental = parseNumber(basic_rental)  *  Math.pow(1+ (parseFloat(tenant.increment_percentage)/100), exponent); 
        }

        return parseNumber(basic_rental);
    }

    $scope.calculated_basic = function(){

        if(!$scope.tenant) return;

        if($scope.formData.invoice_type == 'Basic Manual' && $scope.formData.fixed_total_manual_basic){
            return parseNumber($scope.formData.manual_basic);
        }

        if($scope.tenant.rental_type == 'Percentage'){
            return parseNumber($scope.pct_percentage_rent());
        }


    	//console.log('days:',$scope.days.days_in_month, $scope.days.days_occupied);  

        /*let calc_basic = parseNumber($scope.basic_plus_increment());    

    	if($scope.getDaysFromOpening() < $scope.currentMonthDays()){
    		
    		calc_basic = (calc_basic / parseNumber($scope.days.days_in_month)) * parseNumber($scope.days.days_occupied);
    	}*/

        let calc_basic = parseNumber($scope.calc_basic_plus_increment());


    	if($scope.tenant.rental_type == 'Fixed Plus Percentage'){
    		calc_basic += parseNumber($scope.fpp_percentage_rent());
    	}


        if($scope.tenant.rental_type == 'Fixed/Percentage w/c Higher'){
            let percentage_rent = parseNumber($scope.fop_percentage_rent());

            if( percentage_rent > calc_basic){
                return percentage_rent;
            }else{
                return calc_basic;
            }
        }



    	return parseNumber(calc_basic);
    }

    $scope.calc_basic_plus_increment = function(){
        let calc_basic = parseNumber($scope.basic_plus_increment()); 

        if($scope.getDaysFromOpening() < $scope.currentMonthDays()){
            
            calc_basic = (calc_basic / parseNumber($scope.days.days_in_month)) * parseNumber($scope.days.days_occupied);
        }

        return parseNumber(calc_basic);
    }


    $scope.rental_increment = function(){
    	let rental_increment = 0;
    	let tenant = $scope.tenant;


    	//if($scope.formData.invoice_type == 'Basic Manual') return 0;
    	
        if($scope.is_incrementable() > 0){
            rental_increment = ($scope.basic_rental() * (tenant.increment_percentage /100));
        }

    	return rental_increment;
    }

    $scope.basic_plus_increment = function(){
    	return (parseNumber($scope.basic_rental()) + parseNumber($scope.rental_increment()));
    }


    $scope.vat_percentage =  function(){
        if(!$scope.tenant) return;

    	if($scope.tenant.is_vat != 'Added'){
    		return  0;
    	}

    	let vat_percentage = parseNumber($scope.tenant.vat_percentage);
    	return  vat_percentage = vat_percentage < 1 ?  (vat_percentage * 100) :
            (vat_percentage < 2 && vat_percentage > 1 ? (vat_percentage - 1) * 100 : vat_percentage);
    }

    $scope.vat_amount = function(){
        if(!$scope.tenant) return;

    	let vat_amount = 0;
    	let vat_percentage = parseNumber($scope.vat_percentage());

    	if($scope.tenant.is_vat != 'Added'){

    		vat_amount =  0;

    	}else if($scope.tenant.vat_agreement == 'Exclusive'){

    		vat_amount = $scope.sub_total() * (vat_percentage / 100);

    	}else if($scope.tenant.vat_agreement == 'Inclusive'){

    		vat_amount = ($scope.sub_total() / (1 + (vat_percentage / 100))) * (vat_percentage / 100);
    	}

    	return parseNumber(vat_amount);
    }

    $scope.wht_percentage = function(){
        if(!$scope.tenant) return;
        
    	if($scope.tenant.wht != 'Added'){
    		return 0;
    	}

    	let wht_percentage = parseNumber($scope.tenant.wht_percentage);

        if(wht_percentage < 1){
            return parseNumber(wht_percentage = wht_percentage  * 100);
        }else if(wht_percentage < 2 && wht_percentage > 1){
            return parseNumber(wht_percentage = (wht_percentage - 1) * 100);
        }else{
            return parseNumber(wht_percentage);
        }

    	//return wht_percentage = wht_percentage < 2 ? ((wht_percentage - 1) * 100) : wht_percentage;
    }

    $scope.wht_amount = function(){
        if(!$scope.tenant) return;

    	let wht_amount = 0;
    	let wht_percentage = $scope.wht_percentage();

    	if($scope.tenant.wht != 'Added'){
    		wht_amount =  0;
    	}else if($scope.tenant.vat_agreement == 'Exclusive'){

    		wht_amount = $scope.sub_total() * (wht_percentage / 100);

    	}else if($scope.tenant.vat_agreement == 'Inclusive'){

    		let vat_percentage = parseNumber($scope.vat_percentage()) == 0 ? 12 : parseNumber($scope.vat_percentage());

    		wht_amount = ($scope.sub_total() / (1 + (vat_percentage / 100))) * (wht_percentage / 100);

    	}

    	return parseNumber(wht_amount) * -1;
    }

    $scope.sub_total = function(){

        if(!$scope.tenant) return;

        //let sub_total = 0;
        if($scope.formData.invoice_type == 'Retro Rent'){
            return parseNumber($scope.formData.retro_amount);
        }

        let basic = $scope.calculated_basic();
        let total_discount = 0;

        $scope.tenant.discounts.forEach((disc)=>{
            if(disc.discount_type == 'Percentage'){
                total_discount +=  parseNumber(basic * (parseNumber(disc.discount) / 100));
            }else{
                total_discount +=  parseNumber(disc.discount);
            }
        })

        return parseNumber(basic - total_discount);

        /*if($scope.formData.invoice_type == 'Basic Manual' && $scope.formData.fixed_total_manual_basic){
            sub_total = parseNumber($scope.ftb_sub_total());
        }else{

            switch($scope.tenant.rental_type) {
                case 'Fixed':
                    sub_total = parseNumber($scope.fixed_sub_total());
                    break;
                case 'Fixed Plus Percentage':
                    sub_total = parseNumber($scope.fpp_sub_total());
                    break;

                case 'Percentage':
                    sub_total = parseNumber($scope.pct_sub_total());
                    break;

                case 'Fixed Plus Percentage':
                    sub_total = parseNumber($scope.pct_sub_total());
                    
                default:
                    sub_total = 0;
            }
        }*/
    	
		//return sub_total;
    }

  
    $scope.total_basic_rental = function(){
    	return $scope.sub_total() + $scope.vat_amount() + $scope.wht_amount();
    }




    /*=================== FIXED CODINGS ===========================*/

	    

	    /*$scope.fixed_sub_total = function(){
	    	let basic = $scope.calculated_basic();
	    	let total_discount = 0;

	    	$scope.tenant.discounts.forEach((disc)=>{
	    		if(disc.discount_type == 'Percentage'){
	    			total_discount +=  basic * (parseNumber(disc.discount) / 100);
	    		}else{
	    			total_discount +=  parseNumber(disc.discount);
	    		}
	    	})

	    	return basic - total_discount;
	    }*/

	

	    $scope.fixed_append = function(){
	    	$scope.invoices = [];

	    	let unit_value = $scope.getDaysFromOpening() < $scope.currentMonthDays() ? (parseNumber($scope.days.days_occupied)/parseNumber($scope.days.days_in_month)) : 1;

	    	$scope.invoices.push({
	    		charge_type : 'Basic/Monthly Rental',
	    		charge_code : '',
	    		description : $scope.tenant.rental_type,
	    		uom : 'Month',
	    		total_unit : unit_value,
	    		unit_price : parseNumber($scope.basic_rental()),
	    		actual_amount: parseNumber($scope.basic_rental() * unit_value),
                days_in_month :  $scope.days.days_in_month,
                days_occupied : $scope.days.days_occupied
	    	});

	    	if($scope.rental_increment() > 0){
	    		$scope.invoices.push({
		    		charge_type : 'Basic',
		    		charge_code : '',
		    		description : 'Rental Incrementation',
		    		uom : 'Percentage',
		    		total_unit : $scope.tenant.increment_percentage,
		    		unit_price : parseNumber($scope.basic_rental() * unit_value),
		    		actual_amount: parseNumber($scope.rental_increment() * unit_value) 
		    	});
	    	}

	    	$scope.tenant.discounts.forEach((disc)=>{
	    		let disc_amount = 0;
	    		let unit_price = null;

	    		if(disc.discount_type == 'Percentage'){
	    			disc_amount =  $scope.calculated_basic() * (parseNumber(disc.discount) / 100);
	    			unit_price = $scope.calculated_basic();
                    total_unit = disc.discount;
	    		}else{
	    			disc_amount =  parseNumber(disc.discount);
                    unit_price  = disc.discount;
                    total_unit = 1;

	    		}

	    		$scope.invoices.push({
		    		charge_type : 'Discount',
		    		charge_code : '',
		    		description : disc.tenant_type,
		    		uom : disc.discount_type,
		    		total_unit : total_unit,
		    		unit_price : unit_price,
		    		actual_amount: (parseNumber(disc_amount) * -1)
		    	});
	    	});

	    	if($scope.vat_amount() > 0){
	    		$scope.invoices.push({
		    		charge_type : 	'Basic',
		    		charge_code : 	'',
		    		description : 	'Vat Output',
		    		uom 		: 	'Percentage',
		    		total_unit 	: $scope.vat_percentage(),
		    		actual_amount: parseNumber($scope.vat_amount())
		    	});
	    	}


	    	if($scope.wht_amount() < 0){
	    		$scope.invoices.push({
		    		charge_type : 'Basic',
		    		charge_code : '',
		    		description : 'Creditable Witholding Tax',
		    		uom 		: 'Percentage',
		    		total_unit 	: $scope.wht_percentage(),
		    		actual_amount: parseNumber($scope.wht_amount())
		    	});
	    	}
	    }

    /*=================== END OF FIXED CODINGS ===========================*/


    /*=================== FIXED PLUS PERCENTAGE CODINGS ===========================*/
    	$scope.fpp = {
    		sales      : 0,
            tax_exempt : 0
    	};

    	$scope.fpp_total_sales = function(){
    		return parseNumber($scope.fpp.sales) / 1.12 + parseNumber($scope.fpp.tax_exempt);
    	}

    	$scope.fpp_percentage_rent = function(){
			let total_sales =	parseNumber($scope.fpp_total_sales());
			let percentage_rent = 0;
			let rent_percentage = parseNumber($scope.tenant.rent_percentage);

			percentage_rent = total_sales * (rent_percentage / 100);

			return percentage_rent;
    	}

    	/*$scope.fpp_sub_total = function(){
	    	let basic = $scope.calculated_basic();
	    	let total_discount = 0;

	    	$scope.tenant.discounts.forEach((disc)=>{
	    		if(disc.discount_type == 'Percentage'){
	    			total_discount +=  basic * (parseNumber(disc.discount) / 100);
	    		}else{
	    			total_discount +=  parseNumber(disc.discount);
	    		}
	    	})

	    	return basic - total_discount;
	    }*/


	    $scope.fpp_append = function(){
	    	$scope.invoices = [];

            if(parseNumber($scope.fpp_percentage_rent()) > 0){
                $scope.invoices.push({
                    charge_type : 'Basic',
                    charge_code : '',
                    description :'Percentage Rent',
                    uom :  'Percentage',
                    total_unit : parseNumber($scope.tenant.rent_percentage),
                    unit_price : parseNumber($scope.fpp_total_sales()),
                    actual_amount: parseNumber($scope.fpp_percentage_rent())
                });
            }
	    	

	    	let unit_value = $scope.getDaysFromOpening() < $scope.currentMonthDays() ? (parseNumber($scope.days.days_occupied)/parseNumber($scope.days.days_in_month)) : 1;

	    	$scope.invoices.push({
	    		charge_type : 'Basic/Monthly Rental',
	    		charge_code : '',
	    		description : $scope.tenant.rental_type,
	    		uom : 'Month',
	    		total_unit : unit_value,
	    		unit_price : parseNumber($scope.basic_rental()),
	    		actual_amount: parseNumber($scope.basic_rental() * unit_value),
                days_in_month :  $scope.days.days_in_month,
                days_occupied : $scope.days.days_occupied
	    	});

	    	if($scope.rental_increment() > 0){
	    		$scope.invoices.push({
		    		charge_type : 'Basic',
		    		charge_code : '',
		    		description : 'Rental Incrementation',
		    		uom : 'Percentage',
		    		total_unit : $scope.tenant.increment_percentage,
		    		unit_price : parseNumber($scope.basic_rental() * unit_value),
		    		actual_amount: parseNumber($scope.rental_increment() * unit_value) 
		    	});
	    	}

	    	$scope.tenant.discounts.forEach((disc)=>{
	    		let disc_amount = 0;
	    		let total_unit = null;

                if(disc.discount_type == 'Percentage'){
                    disc_amount =  $scope.calculated_basic() * (parseNumber(disc.discount) / 100);
                    unit_price = $scope.calculated_basic();
                    total_unit = disc.discount;
                }else{
                    disc_amount =  parseNumber(disc.discount);
                    unit_price  = disc.discount;
                    total_unit = 1;
                }

                $scope.invoices.push({
                    charge_type : 'Discount',
                    charge_code : '',
                    description : disc.tenant_type,
                    uom : disc.discount_type,
                    total_unit : total_unit,
                    unit_price : unit_price,
                    actual_amount: (parseNumber(disc_amount) * -1)
                });
	    		
	    	});

	    	if($scope.vat_amount() > 0){
	    		$scope.invoices.push({
		    		charge_type : 	'Basic',
		    		charge_code : 	'',
		    		description : 	'Vat Output',
		    		uom 		: 	'Percentage',
		    		total_unit 	: $scope.vat_percentage(),
		    		actual_amount: parseNumber($scope.vat_amount())
		    	});
	    	}


	    	if($scope.wht_amount() < 0){
	    		$scope.invoices.push({
		    		charge_type : 'Basic',
		    		charge_code : '',
		    		description : 'Creditable Witholding Tax',
		    		uom 		: 'Percentage',
		    		total_unit 	: $scope.wht_percentage(),
		    		actual_amount: parseNumber($scope.wht_amount())
		    	});
	    	}
	    }

    /*=================== END OF FIXED PLUS PERCENTAGE CODINGS ===========================*/

    /*=================== PERCENTAGE CODINGS ===========================*/
        $scope.pct = {
            sales : 0
        };


        $scope.pct_total_sales = function(){
            return parseNumber($scope.pct.sales) / 1.12;
        }

        $scope.pct_percentage_rent = function(){
            let total_sales =   parseNumber($scope.pct_total_sales());
            let percentage_rent = 0;
            let rent_percentage = parseNumber($scope.tenant.rent_percentage);

            percentage_rent = total_sales * (rent_percentage / 100);

            return percentage_rent;
        }

        /*$scope.pct_sub_total = function(){
            let basic = $scope.calculated_basic();
            let total_discount = 0;

            $scope.tenant.discounts.forEach((disc)=>{
                if(disc.discount_type == 'Percentage'){
                    total_discount +=  basic * (parseNumber(disc.discount) / 100);
                }else{
                    total_discount +=  parseNumber(disc.discount);
                }
            })

            return basic - total_discount;
        }*/


        $scope.pct_append = function(){
            $scope.invoices = [];

            if(parseNumber($scope.pct_percentage_rent()) > 0){
                $scope.invoices.push({
                    charge_type : 'Basic',
                    charge_code : '',
                    description :'Percentage Rent',
                    uom :  'Percentage',
                    total_unit : parseNumber($scope.tenant.rent_percentage),
                    unit_price : parseNumber($scope.pct_total_sales()),
                    actual_amount: parseNumber($scope.pct_percentage_rent())
                });
            }

            $scope.tenant.discounts.forEach((disc)=>{
                let disc_amount = 0;
                let total_unit = null;

                if(disc.discount_type == 'Percentage'){
                    disc_amount =  $scope.calculated_basic() * (parseNumber(disc.discount) / 100);
                    unit_price = $scope.calculated_basic();
                    total_unit = disc.discount;
                }else{
                    disc_amount =  parseNumber(disc.discount);
                    unit_price  = disc.discount;
                    total_unit = 1;
                }

                $scope.invoices.push({
                    charge_type : 'Discount',
                    charge_code : '',
                    description : disc.tenant_type,
                    uom : disc.discount_type,
                    total_unit : total_unit,
                    unit_price : unit_price,
                    actual_amount: (parseNumber(disc_amount) * -1)
                });
                
            });

            if($scope.vat_amount() > 0){
                $scope.invoices.push({
                    charge_type :   'Basic',
                    charge_code :   '',
                    description :   'Vat Output',
                    uom         :   'Percentage',
                    total_unit     : $scope.vat_percentage(),
                    actual_amount: parseNumber($scope.vat_amount())
                });
            }


            if($scope.wht_amount() < 0){
                $scope.invoices.push({
                    charge_type : 'Basic',
                    charge_code : '',
                    description : 'Creditable Witholding Tax',
                    uom         : 'Percentage',
                    total_unit     : $scope.wht_percentage(),
                    actual_amount: parseNumber($scope.wht_amount())
                });
            }
        }


    /*=================== END OF PERCENTAGE CODINGS ===========================*/

    /*=================== FIXED OR PERCENTAGE W/C IS HIGHER CODINGS =====================*/
        $scope.fop = {
            sales : 0,
            evaluated_basic : 0,
        };

        $scope.fop_total_sales = function(){
            return parseNumber($scope.fop.sales) / 1.12;
        }

        $scope.fop_percentage_rent = function(){
            let total_sales =   parseNumber($scope.fop_total_sales());
            let percentage_rent = 0;
            let rent_percentage = parseNumber($scope.tenant.rent_percentage);

            percentage_rent = total_sales * (rent_percentage / 100);

            return percentage_rent;
        }

        $scope.fop_append = function(){
            $scope.invoices = [];

            if($scope.fop_percentage_rent() > $scope.calc_basic_plus_increment()){
                $scope.invoices.push({
                    charge_type : 'Basic',
                    charge_code : '',
                    description :'Percentage Rent',
                    uom :  'Percentage',
                    total_unit : parseNumber($scope.tenant.rent_percentage),
                    unit_price : parseNumber($scope.fop_total_sales()),
                    actual_amount: parseNumber($scope.fop_percentage_rent())
                });
            }else{

                let unit_value = $scope.getDaysFromOpening() < $scope.currentMonthDays() ? (parseNumber($scope.days.days_occupied)/parseNumber($scope.days.days_in_month)) : 1;

                $scope.invoices.push({
                    charge_type : 'Basic/Monthly Rental',
                    charge_code : '',
                    description : $scope.tenant.rental_type,
                    uom : 'Month',
                    total_unit : unit_value,
                    unit_price : parseNumber($scope.basic_rental()),
                    actual_amount: parseNumber($scope.basic_rental() * unit_value),
                    days_in_month :  $scope.days.days_in_month,
                    days_occupied : $scope.days.days_occupied
                });

                if($scope.rental_increment() > 0){
                    $scope.invoices.push({
                        charge_type : 'Basic',
                        charge_code : '',
                        description : 'Rental Incrementation',
                        uom : 'Percentage',
                        total_unit : $scope.tenant.increment_percentage,
                        unit_price : parseNumber($scope.basic_rental() * unit_value),
                        actual_amount: parseNumber($scope.rental_increment() * unit_value) 
                    });
                }

            }

            $scope.tenant.discounts.forEach((disc)=>{
                let disc_amount = 0;
                let total_unit = null;

                if(disc.discount_type == 'Percentage'){
                    disc_amount =  $scope.calculated_basic() * (parseNumber(disc.discount) / 100);
                    unit_price = $scope.calculated_basic();
                    total_unit = disc.discount;
                }else{
                    disc_amount =  parseNumber(disc.discount);
                    unit_price  = disc.discount;
                    total_unit = 1;
                }

                $scope.invoices.push({
                    charge_type : 'Discount',
                    charge_code : '',
                    description : disc.tenant_type,
                    uom : disc.discount_type,
                    total_unit : total_unit,
                    unit_price : unit_price,
                    actual_amount: (parseNumber(disc_amount) * -1)
                });
                
            });


            if($scope.vat_amount() > 0){
                $scope.invoices.push({
                    charge_type :   'Basic',
                    charge_code :   '',
                    description :   'Vat Output',
                    uom         :   'Percentage',
                    total_unit  : $scope.vat_percentage(),
                    actual_amount: parseNumber($scope.vat_amount())
                });
            }


            if($scope.wht_amount() < 0){
                $scope.invoices.push({
                    charge_type : 'Basic',
                    charge_code : '',
                    description : 'Creditable Witholding Tax',
                    uom         : 'Percentage',
                    total_unit  : $scope.wht_percentage(),
                    actual_amount: parseNumber($scope.wht_amount())
                });
            }
        }




        /*$scope.fop_sub_total = function(){
            let basic = parseNumber($scope.fop.calculated_basic);
            let total_discount = 0;

            $scope.tenant.discounts.forEach((disc)=>{
                if(disc.discount_type == 'Percentage'){
                    total_discount +=  basic * (parseNumber(disc.discount) / 100);
                }else{
                    total_discount +=  parseNumber(disc.discount);
                }
            })

            return basic - total_discount;
        }*/



    /*=================== END OF FIXED OR PERCENTAGE W/C IS HIGHER CODINGS =====================*/


    /*=================== FIXED TOTAL MANUAL BASIC CODINGS ===========================*/
        /*$scope.ftb_sub_total = function(){
            let basic = $scope.calculated_basic();
            let total_discount = 0;

            $scope.tenant.discounts.forEach((disc)=>{
                if(disc.discount_type == 'Percentage'){
                    total_discount +=  basic * (parseNumber(disc.discount) / 100);
                }else{
                    total_discount +=  parseNumber(disc.discount);
                }
            })

            return parseNumber(basic - total_discount);
        }*/


        $scope.ftb_append = function(){
            $scope.invoices = [];

            
            $scope.invoices.push({
                charge_type : 'Basic/Monthly Rental',
                charge_code : '',
                description : 'Manual Input',
                uom : 'Month',
                total_unit : 1,
                unit_price : parseNumber($scope.basic_rental()),
                actual_amount: parseNumber($scope.basic_rental()),
                days_in_month :  $scope.days.days_in_month,
                days_occupied : $scope.days.days_occupied
            });

            $scope.tenant.discounts.forEach((disc)=>{
                let disc_amount = 0;
                let total_unit = null;

                if(disc.discount_type == 'Percentage'){
                    disc_amount =  $scope.calculated_basic() * (parseNumber(disc.discount) / 100);
                    unit_price = $scope.calculated_basic();
                    total_unit = disc.discount;
                }else{
                    disc_amount =  parseNumber(disc.discount);
                    unit_price  = disc.discount;
                    total_unit = 1;
                }

                $scope.invoices.push({
                    charge_type : 'Discount',
                    charge_code : '',
                    description : disc.tenant_type,
                    uom : disc.discount_type,
                    total_unit : total_unit,
                    unit_price : unit_price,
                    actual_amount: (parseNumber(disc_amount) * -1)
                });
                
            });

            if($scope.vat_amount() > 0){
                $scope.invoices.push({
                    charge_type :   'Basic',
                    charge_code :   '',
                    description :   'Vat Output',
                    uom         :   'Percentage',
                    total_unit     : $scope.vat_percentage(),
                    actual_amount: parseNumber($scope.vat_amount())
                });
            }


            if($scope.wht_amount() < 0){
                $scope.invoices.push({
                    charge_type : 'Basic',
                    charge_code : '',
                    description : 'Creditable Witholding Tax',
                    uom         : 'Percentage',
                    total_unit     : $scope.wht_percentage(),
                    actual_amount: parseNumber($scope.wht_amount())
                });
            }
        }


    /*=================== END OF FIXED TOTAL MANUAL BASIC CODINGS ===========================*/

    /*=================== RETRO RENT CODINGS ===========================*/
    $scope.retro_append = function(){
        $scope.invoices = [];

        
        $scope.invoices.push({
            charge_type : 'Retro Rental',
            charge_code : '',
            description : 'Retro Rental',
            uom : 'Manual Input',
            total_unit : null,
            unit_price : null,
            actual_amount: parseNumber($scope.sub_total())
        });


        if($scope.vat_amount() > 0){
            $scope.invoices.push({
                charge_type :   'Basic',
                charge_code :   '',
                description :   'Vat Output',
                uom         :   'Percentage',
                total_unit     : $scope.vat_percentage(),
                actual_amount: parseNumber($scope.vat_amount())
            });
        }


        if($scope.wht_amount() < 0){
            $scope.invoices.push({
                charge_type : 'Basic',
                charge_code : '',
                description : 'Creditable Witholding Tax',
                uom         : 'Percentage',
                total_unit     : $scope.wht_percentage(),
                actual_amount: parseNumber($scope.wht_amount())
            });
        }
    }
    /*=================== END OF RETRO RENT CODINGS ===========================*/



    $scope.append_basic = function(){

        if ($scope.formData.invoice_type == 'Basic Manual' && $('#inv-supp-docs').get(0).files.length === 0) {
            generate('error', 'Invoice override supporting documents are required!')
            return;
        }

    	pop.confirm('Are you sure you want to apply this transaction?', (res)=>{
    		if(!res) return;


            if($scope.formData.invoice_type == 'Basic Manual' && $scope.formData.fixed_total_manual_basic){
                $scope.ftb_append();
            }else if($scope.formData.invoice_type == 'Retro Rent'){
                $scope.retro_append();
            }else{

        		switch($scope.tenant.rental_type){
        			case 'Fixed':
        				$scope.fixed_append();
        				break;
        			case 'Fixed Plus Percentage':
    				    $scope.fpp_append();
    				    break;
                    case 'Percentage':
                        $scope.pct_append();
                        break;
                    case 'Fixed/Percentage w/c Higher':
                        $scope.fop_append()
                        break;

    				default: break;
        		}
            }

	    	$('#basicRental_modal').modal('hide');
            $('#retroRent_modal').modal('hide');

	    	$scope.$apply();
    	})
    }

    $scope.append_preop = function(){

        pop.confirm('Are you sure you want to apply this transaction?', (res)=>{
            if(!res) return;

            if(!$scope.invoices)
                $scope.invoices = [];


            if(!$scope.formData.preOp){
                pop.alert('Please select Pre Operational Charges!');
                return;
            }

            if(parseNumber($scope.formData.preOp.actual_amount) == 0){
                pop.alert('Actual Amount can\'t be zero!');
                return;
            }


            $scope.invoices.push({
                charge_type : $scope.formData.preOp.charges_type,
                charge_code : $scope.formData.preOp.charges_code,
                description : $scope.formData.preOp.description,
                uom : 'Month',
                total_unit : $scope.formData.preOp.uom,
                unit_price : parseNumber($scope.formData.preOp.basic_rental),
                actual_amount: parseNumber($scope.formData.preOp.actual_amount),
                removable : true
            });

            $('#preop_charges').modal('hide');

            $scope.$apply();
        })
    }


    $scope.append_cons_mat = function(){

        pop.confirm('Are you sure you want to apply this transaction?', (res)=>{
            if(!res) return;

            if(!$scope.invoices)
                $scope.invoices = [];


            if(!$scope.formData.cons_mat){
                pop.alert('Please select Contruction Material!');
                return;
            }

            let actual_amount = 0;
            let total_unit = 0;

            if($scope.formData.cons_mat.uom == 'Fixed Amount'){
                actual_amount =  parseNumber($scope.formData.cons_mat.unit_price);
                total_unit    = 1;
            }else{
                actual_amount = parseNumber($scope.formData.cons_mat.actual_amount);
                total_unit    = $scope.formData.cons_mat.total_unit;
            }

            if(actual_amount == 0){
                pop.alert('Actual Amount can\'t be zero!');
                return;
            }

            $scope.invoices.push({
                charge_type : $scope.formData.cons_mat.charges_type,
                charge_code : $scope.formData.cons_mat.charges_code,
                description : $scope.formData.cons_mat.description,
                uom         : $scope.formData.cons_mat.uom,
                total_unit  : total_unit,
                unit_price  : parseNumber($scope.formData.cons_mat.unit_price),
                actual_amount: actual_amount,
                curr_reading : Math.abs($scope.formData.cons_mat.curr_reading),
                prev_reading : Math.abs($scope.formData.cons_mat.prev_reading),
                with_penalty : $scope.formData.cons_mat.with_penalty,
                removable    : true
            });

            $('#constMat').modal('hide');

            $scope.$apply();
        })
    }

    $scope.append_monthly_charges = function(){

        pop.confirm('Are you sure you want to append this charges?', (res)=>{
            if(!res) return;

            if(!$scope.invoices)
                $scope.invoices = [];


            if(!$scope.monthly_charges){
                pop.alert('Please select Monthly Charges!');
                return;
            }

            $scope.monthly_charges.forEach((charge)=>{

                if(parseNumber(charge.actual_amount) == 0) return;
                $scope.invoices.push({
                    charge_type : 'Other',
                    charge_code : charge.charges_code,
                    description : charge.description,
                    uom         : charge.uom,
                    total_unit  : Math.abs(parseNumber(charge.total_unit)),
                    unit_price  : parseNumber(charge.unit_price),
                    actual_amount: Math.abs(parseNumber(charge.actual_amount)),
                    curr_reading : Math.abs(charge.curr_reading),
                    prev_reading : Math.abs(charge.prev_reading),
                    with_penalty : charge.with_penalty,
                    removable    : true
                });
            })

            $('#monthly_charges').modal('hide');

            $scope.$apply();
        })
    }

    $scope.append_other_charges = function(){

        pop.confirm('Are you sure you want to append this charges?', (res)=>{
            if(!res) return;

            if(!$scope.invoices)
                $scope.invoices = [];

            if(!$scope.formData.charge){
                pop.alert('Please select from other charges!');
                return;
            }

            let unit_price = null;
            let total_unit = null;
            let actual_amount = Math.abs(parseNumber($scope.formData.charge.actual_amount));

            if(actual_amount == 0){
                pop.alert('Actual Amount can\'t be zero!');
                return;
            }

            if($scope.formData.charge.uom != 'Inputted'){
                unit_price = parseNumber($scope.formData.charge.unit_price);
                total_unit = Math.abs(parseNumber($scope.formData.charge.total_unit));
            }

            if($scope.formData.charge.description == 'Expanded Withholding Tax'){
                actual_amount = actual_amount * -1;
            }


            $scope.invoices.push({
                charge_type : 'Other',
                charge_code : $scope.formData.charge.charges_code,
                description : $scope.formData.charge.description,
                uom         : $scope.formData.charge.uom,
                total_unit  : total_unit,
                unit_price  : unit_price,
                actual_amount: actual_amount,
                curr_reading : Math.abs($scope.formData.charge.curr_reading),
                prev_reading : Math.abs($scope.formData.charge.prev_reading),
                with_penalty : $scope.formData.charge.with_penalty,
                removable    : true
            });

            $('#other_charges').modal('hide');

            $scope.$apply();
        })
    }



    $scope.total = function(){
    	var amount = 0;

    	if(!$scope.invoices) return amount;

    	$scope.invoices.forEach((inv)=>{
    		amount += parseNumber(inv.actual_amount);
    	})

    	return parseNumber(amount);
    }

    $scope.clearInvoices = function(){
    	pop.confirm('Are you sure you to clear the invoices?', (res)=>{
    		if(!res) return;

    		$scope.invoices = [];
    		$scope.$apply();
    	})
    }

    $scope.removeInvoice = function(invoice){
    	pop.confirm('Are you sure you to remove this invoice?', (res)=>{
    		if(!res) return;

    		var index = $scope.invoices.indexOf(invoice);
    		$scope.invoices.splice(index, 1);
    		$scope.$apply();
    	})
    }



    $scope.submitInvoice = function(e){
        e.preventDefault();

        pop.confirm('Are you sure you want to continue posting this invoice?', (res)=>{
            if(!res) return;
            
            /*let data = $(e.target).serializeObject();

            data.invoices               = JSON.parse(angular.toJson($scope.invoices));
            data.invoice_type           = $scope.formData.invoice_type; 
            data.basic_override_token   = $scope.formData.basic_override_token;   

            $.post($base_url + 'leasing/save_invoice', data, function(res){
                if(res.type == 'success'){
                    notify('success', res.msg);
                    return;
                }

                if(res.type == 'error_token'){
                    $('#managers-key-modal').modal('show');
                    generate('error', res.msg);
                    $scope.formData.showForm = false;
                    return;
                }

                generate(res.type, res.msg);
                
            }, 'json');*/

            let formData        = new FormData(e.target);
            let invoices        = JSON.parse(angular.toJson($scope.invoices));

            console.log(invoices);
            
         
            formData.append('invoice_type', $scope.formData.invoice_type);
            formData.append('basic_override_token', $scope.formData.basic_override_token);
            //formData.append("supp_docs", $('#inv-supp-docs').get(0).files);

            if($scope.formData.invoice_type == 'Basic Manual'){
                $.each($('#inv-supp-docs')[0].files, function(i, file) {
                    formData.append(`supp_docs[${i}]`, file);
                });
            }

           

            formData  = convertModelToFormData(invoices, formData, 'invoices');
            //formData  = convertModelToFormData(docs, formData, 'supp_docs');

            let loader = pop.loading('Posting Invoice. Please wait ...');
            
            let url = $base_url + 'leasing/save_invoice';

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
                        notify('success', res.msg);
                        return;
                    }

                    if(res.type == 'error_token'){
                        $('#managers-key-modal').modal('show');
                        generate('error', res.msg);
                        $scope.formData.showForm = false;
                        return;
                    }

                    generate(res.type, res.msg);
                }
            });

        })
        
    }

    $scope.parseNumber = parseNumber;
    $scope.getDaysInMonth = getDaysInMonth;

})

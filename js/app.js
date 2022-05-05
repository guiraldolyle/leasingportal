window.myApp = null;
myApp = angular.module('myApp', ['chart.js', '720kb.datepicker', 'ds.clock', 'ngSanitize', 'MassAutoComplete', 'ui.mask', 'ui.utils.masks', 'ngTable']);

myApp.filter('offset', function() {
    return function(input, start) {
        if (!input || !input.length) { return; }
        start = +start; //parse to int
        return input.slice(start);
    }
});

myApp.filter('absolute', function() {
    return function(number) {
        return Math.abs(parseNumber(number));
    }
});

myApp.filter('split', function() {
    return function(input, splitChar, splitIndex) {
        // do some bounds checking here to ensure it has that index
        return input.split(splitChar)[splitIndex];
    }
});

myApp.filter('power', function() {
    return function(base, exponent) {
        return Math.pow(base, exponent);
    }
});

myApp.filter('selected', function() {
    return function(data) {
        return data.filter(function(dt) {
            return dt.selected;
        })
    }
});

myApp.filter('formatDate', function() {
    return function(date, format = 'YYYY-MM-DD') {
        if (!date || date.length == 0) {
            return '';
        }

        return moment(date).format(format);
    }
});


myApp.filter('dateSubtract', function() {
    return function(date, num = 1, unit = 'days', format = 'YYYY-MM-DD') {
        if (!date || date.length == 0) {
            return '';
        }

        return moment(date).subtract(num, unit).format(format);
    }
});

myApp.filter('dateAdd', function() {
    return function(date, num = 1, unit = 'days', format = 'YYYY-MM-DD') {
        if (!date || date.length == 0) {
            return '';
        }

        return moment(date).add(num, unit).format(format);
    }
});


myApp.constant("moment", moment);


//====================== appController ==========================//

myApp.controller('appController', function($scope, $http, $timeout, moment, $sce, $q, filterFilter, $rootScope, $interval) {
    //eeshiro_revisions
    $scope.$base_url = window.$base_url;

    $scope.in_array = function(needle, haystack) {
        return haystack.includes(needle);
    }

    $scope.selectAll = function(data = []) {

        //var _inputValue = false;

        return function(value) {
            //value = arguments.length ? (_inputValue = value) : _inputValue;

            if (arguments.length) {
                data.forEach(function(dt) {
                    dt.selected = value;
                })

                return value;
            } else {

                let selected_data = data.filter(function(dt) {
                    return dt.selected;
                })

                return data.length > 0 && selected_data.length == data.length;
            }
        }
    }





    /*function(e){
        console.log(e);



        data.forEach(function(dt){

        })

    }*/

    $scope.power = function(base, exponent) {
        return Math.pow(base, exponent);
    }

    //======================= TIN Input Mask ======================//
    $scope.TINMask = "999-999-999-999";
    $scope.removeMask = function() {
        if ($scope.rep.phone.length === 13) {
            $scope.TINMask = "999-999-999-999";
        }
    };
    $scope.placeMask = function() {
        $scope.TINMask = "999-999-999-999";
    }

    //======================= Autocomplete ======================//

    $scope.dirty = {};
    var trade_name = [];


    $scope.populate_tradeName = function(url) {
        $http.post(url).success(function(result) {
            trade_name = result;
            // console.log(result);
        });
    }


    function suggest_state(term) {
        var q = term.toLowerCase().trim();
        var results = [];

        // Find first 10 states that start with `term`.
        for (var i = 0; i < trade_name.length && results.length < 10; i++) {
            var state = trade_name[i].trade_name;
            if (state.toLowerCase().indexOf(q) === 0)
                results.push({ label: state, value: state });
        }
        return results;
    }

    $scope.autocomplete_options = {
        suggest: suggest_state
    };


    // ===================== End of Autocomplete =================//


    $scope.startTimeValue = 1430990693334;
    $scope.theme = 'dark';


    $scope.clock = "Loading Date and Time..."; // initialise the time variable
    $scope.tickInterval = 1000 //ms
    $scope.tick = function(url) {

        $interval(function() {
            $http.post(url).success(function(result) {
                $scope.clock = result.current_dateTime; // get the current time
            });
        }.bind(this), 1000);
    }


    $scope.form_action = function(url) {
        $scope.store_id = url.split("/").pop();
        document.getElementById('frm_addfloor').action = url;
    }

    $scope.create_onsubmit = function(url) {
        document.getElementById("frm_3DModel").setAttribute("onSubmit", "setup_3D('" + url + "');return false");
    }

    $scope.form_action_addSelectedBank = function(url) {
        $scope.store_id = url.split("/").pop();
        document.getElementById('frm_addselectedBank').action = url;
    }

    $scope.update_store = function(url, id) {

        $scope.storeData = [];
        $scope.floorData = [];
        $scope.selectedBanks = [];
        $http.post(url + id).success(function(result) {
            $scope.storeData = result;
        });

        $http.post("../leasing_mstrfile/get_myfloor/" + id).success(function(result) {
            $scope.floorData = result;
            // console.log(result);
        });


        $http.post("../leasing_mstrfile/get_selectedBanks/" + id).success(function(result) {
            $scope.selectedBanks = result;
            // console.log(result);
        });

    }

    $scope.update_category_one = function(url) {
        $scope.categoryData = [];
        $http.post(url).success(function(result) {
            $scope.categoryData = result;
        });
    }


    $scope.update_accreditedBank = function(url) {
        $scope.bankData = [];
        $http.post(url).success(function(result) {
            $scope.bankData = result;
        });
    }


    $scope.update = function(url) {
        // alert(url);
        $scope.updateData = [];
        $http.post(url).success(function(result) {
            $scope.updateData = result;
            console.log(result);
        });
    }


    $scope.GLData = function(url) {
        $scope.GLData = [];
        $http.post(url).success(function(result) {
            $scope.GLData = result;
            console.log(result);
        })
    }




    $scope.dataList2 = [];
    $scope.loadList2 = function(url) {

        $scope.dataList2 = [];
        $http.post(url).success(function(result) {
            $scope.dataList2 = result;
            console.log(result);
        })
    }


    $scope.navigate = function(url) {
        $scope.navigateData = [];
        $http.post(url).success(function(result) {
            $scope.navigateData = result;
            console.log(result);
        })
    }




    $scope.itemsPerPage2 = 15;
    $scope.currentPage2 = 0;


    $scope.range2 = function() {
        var rangeSize;

        if ($scope.pageCount2() > 10) {
            rangeSize = 10;
        } else {
            rangeSize = $scope.pageCount2() + 1;
        }

        var ret = [];
        var start;

        start = $scope.currentPage2;
        if (start > $scope.pageCount2() - rangeSize) {
            start = $scope.pageCount2() - rangeSize + 1;
        }

        for (var i = start; i < start + rangeSize; i++) {
            ret.push(i);
        }
        return ret;
    }


    $scope.pageCount2 = function() {
        return Math.ceil($scope.dataList2.length / $scope.itemsPerPage2) - 1;
    }


    $scope.prevPage2 = function() {
        if ($scope.currentPage2 > 0) {
            $scope.currentPage2--;
        }
    }

    $scope.prevPageDisabled2 = function() {
        return $scope.currentPage2 === 0 ? "disabled" : "";
    }


    $scope.nextPage2 = function() {
        if ($scope.currentPage2 < $scope.pageCount2()) {
            $scope.currentPage2++;
        }
    }

    $scope.nextPageDisabled2 = function() {
        return $scope.currentPage2 === $scope.pageCount2() ? "disabled" : "";
    };

    $scope.setPage2 = function(n) {
        $scope.currentPage2 = n;
    };


    $scope.update_status = function(url) {
        $http.post(url).success(function(result) {

        });
    }

    $scope.delete = function(url) {
        document.getElementById('anchor_delete').href = url;
    }


    $scope.confirm = function(url) {
        document.getElementById('anchor_confirm').href = url;
    }


    $scope.closingPDC = function(url, trade_name, check_date, amount) {
        document.getElementById('frm_closingPDC').action = url;


        $scope.doc_no = [];
        $scope.trade_name = trade_name;
        $scope.check_date = check_date;
        $scope.amount = amount;
        var doc_no = $http.post('../leasing_transaction/closingPDC_docNo/').success(function(result) {
            console.log(result);
            $scope.doc_no = result['doc_no'];
        });
    }


    $scope.populate_categoryOne = function(url) {
        $scope.categoryOne = [];
        $http.post(url).success(function(result) {
            $scope.categoryOne = result;
        });
    }

    $scope.populate_categoryTwo = function(url) {

        $scope.categoryTwo = [];
        $http.post(url).success(function(result) {
            $scope.categoryTwo = result;
            console.log(result);
        });
    }


    $scope.populate_categoryThree = function(url) {
        $scope.categoryThree = [];
        $http.post(url).success(function(result) {
            $scope.categoryThree = result;
        });
    }



    $scope.exhibit_credentials = function(url) {
        $scope.yourCategory;
        $scope.yourPrice;
        $scope.yourfloor_area;
        $scope.yourper_day;
        $scope.yourper_week;
        $scope.yourper_month;
        $http.post(url).success(function(result) {
            $scope.yourCategory = result[0].category;
            $scope.yourPrice = result[0].price;
            $scope.yourfloor_area = result[0].floor_area;
            $scope.yourper_day = result[0].rate_per_day;
            $scope.yourper_week = result[0].rate_per_week;
            $scope.yourper_month = result[0].rate_per_month;
        });
    }

    $scope.clear_credentials = function(location_code) {
        document.getElementById(location_code).value = "";
        $scope.yourCategory = "";
        $scope.yourPrice = "0.00";
        $scope.yourfloor_area = "0.00";
        $scope.yourper_day = "0.00";
        $scope.yourper_week = "0.00";
        $scope.yourper_month = "0.00";
    }


    $scope.update_clear_credentials = function(location_code, category, price, floor_area, per_day, per_week, per_month) {
        document.getElementById(location_code).value = "";
        document.getElementById(category).value = "";
        document.getElementById(price).value = "0.00";
        document.getElementById(floor_area).value = "0.00";
        document.getElementById(per_day).value = "0.00";
        document.getElementById(per_week).value = "0.00";
        document.getElementById(per_month).value = "0.00";
    }

    $scope.update_credentials = function(url, category, price, floor_area, per_day, per_week, per_month) {
        var newcategory, newprice, new_floorarea, new_perday, new_perweek, new_permonth;
        $http.post(url).success(function(result) {

            newcategory = result[0].category;
            newprice = result[0].price;
            new_floorarea = result[0].floor_area;
            new_perday = result[0].rate_per_day;
            new_perweek = result[0].rate_per_week;
            new_permonth = result[0].rate_per_month;
            document.getElementById(category).value = newcategory;

            newprice = parseFloat(newprice);
            document.getElementById(price).value = newprice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

            new_floorarea = parseFloat(new_floorarea);
            document.getElementById(floor_area).value = new_floorarea.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

            new_perday = parseFloat(new_perday);
            document.getElementById(per_day).value = new_perday.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

            new_perweek = parseFloat(new_perweek);
            document.getElementById(per_week).value = new_perweek.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

            new_permonth = parseFloat(new_permonth);
            document.getElementById(per_month).value = new_permonth.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        });

    }

    $scope.populate_locationCode = function(url) {
        $scope.codeList = [];
        $http.post(url).success(function(result) {
            $scope.codeList = result;
            // console.log(result);
        });
    }

    $scope.populate_combobox = function(url) {
        $scope.itemList = [];
        $http.post(url).success(function(result) {
            $scope.itemList = result;
            // console.log(result);
        });
    }


    $scope.populate_bankCode = function(param) {
        var url = '../leasing_transaction/get_bankName/' + param;
        $scope.populate_combobox(url);
    }




    $scope.populate_rentPeriod = function(url) {
        $scope.periodList = [];
        $http.post(url).success(function(result) {
            $scope.periodList = result;
            // console.log(result);
        });
    }

    $scope.generate_locationCodeID = function() {
        $scope.locationCodeID;
        $http.post('../leasing_mstrfile/generate_locationCodeID').success(function(result) {

            $scope.locationCodeID = result['locationCode_id'];
        });
    }


    $scope.get_prefix = function(store_name) {
        $scope.prefix_storeCode;
        $http.post('../leasing_mstrfile/get_prefix/' + store_name).success(function(result) {

            $scope.prefix_storeCode = result['store_code'];

        });
    }


    $scope.get_prefix2 = function(store_name) {
        $scope.prefix_storeCode;
        $http.post('../leasing_mstrfile/get_prefix/' + store_name).success(function(result) {

            document.getElementById('txt_prefix').value = result['store_code'];
            document.getElementById('prefix').innerHTML = result['store_code'];
        });
    }


    $scope.get_locationCodeInfo = function(url) {

        $http.post(url).success(function(result) {
            // console.log(result);
            document.getElementById('area_classification').value = result[0].area_classification;
            document.getElementById('area_type').value = result[0].area_type;
            document.getElementById('payment_mode').value = result[0].payment_mode;
            document.getElementById('rent_period').value = result[0].rent_period;
            var floor_area = result[0].floor_area;
            var basic_rental = result[0].rental_rate;

            document.getElementById('add_floor_area').value = Number(toNumber(floor_area)).toFixed(2);
            document.getElementById('add_basic_rental').value = Number(toNumber(basic_rental)).toFixed(2);


        });
    }

    $scope.update_locationCodeInfo = function(url) {
        $http.post(url).success(function(result) {
            // console.log(result);
            document.getElementById('update_areaClassification').value = result[0].area_classification;
            document.getElementById('update_areaType').value = result[0].area_type;
            document.getElementById('update_paymentMode').value = result[0].payment_mode;
            document.getElementById('update_rentPeriod').value = result[0].rent_period;

            var floor_area = result[0].floor_area;
            var basic_rental = result[0].rental_rate;

            document.getElementById('update_floorArea').value = Number(toNumber(floor_area)).toFixed(2);
            document.getElementById('update_basicRental').value = Number(toNumber(basic_rental)).toFixed(2);
        });
    }


    $scope.populate_price = function(url) {
        $scope.yourbasic_rental;
        $scope.yourfloor_area;
        $scope.yourfloor_price;
        $http.post(url).success(function(result) {

            $scope.yourbasic_rental = result[0].basic_rental;
            $scope.yourfloor_area = result[0].floor_area;
            $scope.yourfloor_price = result[0].price;
        });
    }

    $scope.updated_price = function(url, price, floor_area, basic_rental) {
        $http.post(url).success(function(result) {
            var rental = result[0].basic_rental;
            var area = result[0].floor_area;
            var floor_price = result[0].price;

            floor_price = parseFloat(floor_price);
            area = parseFloat(area);
            rental = parseFloat(rental);
            document.getElementById(price).value = floor_price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            document.getElementById(floor_area).value = area.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            document.getElementById(basic_rental).value = rental.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        });
    }


    $scope.get_floorPrice = function(url) {
        $scope.floorPrice = "";
        $http.post(url).success(function(result) {
            var result = result.replace(/\"/g, "");
            result = result.substr(2);
            // console.log(result);
            $scope.floorPrice = result;
            //document.getElementById(updatePriceId).value = result;
        });
    }

    $scope.get_floorPriceForUpdate = function(priceholderID, url) {
        $http.post(url).success(function(result) {
            var result = result.replace(/\"/g, "");
            result = result.substr(2);

            var newPrice = parseFloat(result);
            document.getElementById(priceholderID).value = newPrice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        });
    }


    $scope.clear_price = function() {
        $scope.floorPrice = "0.00";
        $scope.basic_rental = "0.00";
    }

    $scope.compute_basicRental_update = function(floor_area, price_holderID, basic_rentalID) {
        var price = document.getElementById(price_holderID).value;
        price = price.replace(/,/g, "");
        floor_area = floor_area.replace(/,/g, "");
        floor_area = floor_area.replace(",", "");
        price = price.replace(",", "");
        var total = floor_area * price;
        document.getElementById(basic_rentalID).value = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    $scope.compute_basicRental = function(floor_area, floor_price) {
        if (floor_area == undefined) {
            floor_area = '0.00';
        }
        floor_area = floor_area.replace(/,/g, "");
        floor_price = floor_price.replace(/,/g, "");
        floor_area = parseFloat(floor_area);
        floor_price = parseFloat(floor_price);
        $scope.basic_rental = floor_area * floor_price;
    }

    $scope.clear_floorPrice = function(floorPriceID, basic_rentalID) {
        document.getElementById(floorPriceID).value = "0.00";
        document.getElementById(basic_rentalID).value = "0.00";
    }

    $scope.clear_locationCode = function(location_code, floor_price, floor_area, basic_rental) {

        document.getElementById(location_code).remove(0);
        document.getElementById(floor_price).value = "0.00";
        document.getElementById(floor_area).value = "0.00";
        document.getElementById(basic_rental).value = "0.00";
    }

    $scope.compute_rates = function(floorPrice, floor_area, rate_per_day, rate_per_week, rate_per_month) {
        floor_area = floor_area.replace(/,/g, "");
        floorPrice = floorPrice.replace(/,/g, "");
        var per_month = floor_area * floorPrice;
        var per_day = per_month / 30;
        var per_week = per_day * 7;

        document.getElementById(rate_per_day).value = per_day.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        document.getElementById(rate_per_week).value = per_week.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        document.getElementById(rate_per_month).value = per_month.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }


    $scope.compute_rates_update = function(floorPriceID, floor_area, rate_per_day, rate_per_week, rate_per_month) {
        var floor_price = document.getElementById(floorPriceID).value;
        floor_price = toNumber(floor_price);
        floor_area = $scope.toNumber(floor_area);

        var per_month = floor_area * floor_price;
        var per_day = per_month / 30;
        var per_week = per_day * 7;

        document.getElementById(rate_per_day).value = per_day.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        document.getElementById(rate_per_week).value = per_week.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        document.getElementById(rate_per_month).value = per_month.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

    }


    $scope.security_bond = function() {
        $http.post('../leasing_mstrfile/security_bond').success(function(result) {
            $scope.yourSecurity_bond = result[0].months;
        });
    }

    $scope.advance_rent = function() {
        $http.post('../leasing_mstrfile/advance_rent').success(function(result) {
            $scope.yourAdvance_rent = result[0].months;
        });
    }

    $scope.cons_bond = function() {
        $http.post('../leasing_mstrfile/cons_bond').success(function(result) {
            $scope.yourCons_bond = result[0].months;
        });
    }

    $scope.plywood_enc = function() {
        $http.post('../leasing_mstrfile/plywood_enc').success(function(result) {
            $scope.yourAddon = result[0].addon;
            $scope.yourLmeter = result[0].per_lmeter;
        });
    }

    $scope.door_lock = function() {
        $http.post('../leasing_mstrfile/door_lock').success(function(result) {
            $scope.yourPer_set = result[0].per_set;
        });
    }

    $scope.penalty_lateopen = function() {
        $http.post('../leasing_mstrfile/penalty_lateopen').success(function(result) {
            $scope.yourPenalty_lateopen = result[0].per_hour;
        });
    }

    $scope.penalty_latepayment = function() {
        $http.post('../leasing_mstrfile/penalty_latepayment').success(function(result) {
            $scope.yourPenalty_latepayment = result[0].multiplier;
        });
    }

    $scope.rentalInc = function() {
        $http.post('../leasing_mstrfile/rentalInc').success(function(result) {
            $scope.yourRental_inc = result[0].amount;
        });
    }


    $scope.wtax = function() {
        $http.post('../leasing_mstrfile/wtax').success(function(result) {
            $scope.yourWtax = result[0].withholding;
        });
    }


    $scope.viewing = function(url) {

        $scope.viewList = [];
        $http.post(url).success(function(result) {
            $scope.viewList = result;
            // console.log(result);
        });
    }

    $scope.details = function(url) {

        $scope.dataList = [];
        $http.post(url).success(function(result) {
            $scope.dataList = result;

        });
    }

    $scope.draft_invoiceCharges = function(url) {
        $scope.chargeList = [];
        $http.post(url).success(function(result) {
            $scope.chargeList = result;
            // console.log(result);
        });
    }


    // $scope.delete_invoiceCharge = function(url)
    // {
    //     $http.post(url).success(function(result){
    //         console.log(result);
    //         if (result.msg == 'Deleted')
    //         {
    //            location.reload();
    //         }
    //     });
    // }

    $scope.get_discounts = function(url) {
        $scope.discountList = [];
        $http.post(url).success(function(result) {
            $scope.discountList = result;

        });
    }

    $scope.get_img = function(url, imgPath) {

        $scope.imgPath = imgPath;
        $scope.imgList = [];
        $http.post(url).success(function(result) {
            $scope.imgList = result;

            if (result[0].file_name) {
                var filename = result[0].file_name;
                var file = filename.split(".").pop();
                if (file == 'pdf') {
                    window.open(imgPath + filename);
                    // window.open(imgPath + filename);
                }
            }
        });
    }

    $scope.previewImg = function(url) {
        $scope.ImgData = url;
    }

    $scope.changeImg = function(imgpath, header, url) {
        $scope.imgpath = imgpath;
        $scope.header = header;
        $scope.url = url;
    }

    $scope.addImg = function(url, header) {
        $scope.url = url;
        $scope.header = header;
    }

    $scope.managers_key = function(url) {

        document.getElementById('frm_manager').action = url;
    }

    $scope.pass_URLtoManagerModal = function(url) {
        // document.getElementById("frm_penalty").action = url;
        document.getElementById("frm_penalty").setAttribute("onSubmit", "waive_penalty('" + url + "');return false");

    }





    $scope.terminate_reason = function(tenant_id) {
        document.getElementById('tenantID').value = tenant_id;
    }

    $scope.get_discount = function(data) {
        $scope.discountData = [];
        $http.post('../get_discount/' + data).success(function(result) {
            $scope.discountData = result;
        });
    }



    $scope.calculate_balance = function(lease_period) {
        var number = lease_period.split(" ");
        var year = parseFloat(number[0]);
        var monthly = document.getElementById("monthly_rental").value;
        monthly = monthly.replace(/,/g, "");
        monthly = parseFloat(monthly);
        var balance = (year * 12) * monthly

        document.getElementById("actual_balance").value = balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        document.getElementById("opening_date").value = "";
        document.getElementById("expiry_date").value = "";
    }

    $scope.actual_balance = function() {
        var discount = document.getElementById("discount").value;
        var balance = document.getElementById("balance").value;
        balance = balance.replace(/,/g, "");
        balance = parseFloat(balance);
        discount = discount.replace(/,/g, "");
        discount = parseFloat(discount);
        var actual_balance;
        var flag = document.getElementById("flag").value;
        if (flag == 'Percentage') {
            actual_balance = balance - ((discount / 100) * balance);
        } else {
            actual_balance = balance - discount;
        }

        document.getElementById("actual_balance").value = actual_balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    $scope.toNumber = function(data) {
        data = String(data).replace(/,/g, "");
        data = parseFloat(data);
        return data;
    }

    $scope.less_wht = function(data) {
        var wht = document.getElementById("wht").value;
        wht = $scope.toNumber(wht);

        var monthly_rental = document.getElementById("original_MR").value;
        monthly_rental = $scope.toNumber(monthly_rental);

        var less_wht = monthly_rental - ((wht / 100) * monthly_rental);

        if (data == 'Basic Rental plus VAT' || data == 'Basic plus Percentage plus VAT' || data == 'Percentage Rental plus VAT') {
            document.getElementById("monthly_rental").value = document.getElementById("original_MR").value
        } else {
            document.getElementById("monthly_rental").value = less_wht.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    }


    $scope.plus_vat = function() {
        var rental_type = document.getElementById("rental_type").value;
        var flag = document.getElementById("flag").value;
        var discount = document.getElementById("discount").value;
        var actual_balance;

        discount = $scope.toNumber(discount);

        var vat = document.getElementById("vat").value;
        vat = $scope.toNumber(vat);

        var wht = document.getElementById("wht").value;
        wht = $scope.toNumber(wht);

        var lease_period = document.getElementById("lease_period").value;
        lease_period = lease_period.split(" ");
        lease_period = $scope.toNumber(lease_period[0]);


        monthly_rental = document.getElementById("original_MR").value;
        monthly_rental = $scope.toNumber(monthly_rental);

        if (document.getElementById("plus_vat").checked) {
            var plus_vat = ((vat / 100) + 1) * monthly_rental;
            document.getElementById("monthly_rental").value = plus_vat.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

            var balance = (lease_period * 12) * plus_vat;
            document.getElementById("balan ce").value = balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

            if (flag == 'Percentage') {
                actual_balance = balance - ((discount / 100) * balance);
                document.getElementById("actual_balance").value = actual_balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            } else {
                actual_balance = balance - discount;
                document.getElementById("actual_balance").value = actual_balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

        } else {

            if (rental_type == 'Basic Rental plus VAT' || rental_type == 'Basic plus Percentage plus VAT' || rental_type == 'Percentage Rental plus VAT') {
                document.getElementById("monthly_rental").value = document.getElementById("original_MR").value
                var balance = (lease_period * 12) * monthly_rental;
                document.getElementById("balance").value = balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

                $scope.actual_balance()

            } else {
                var less_wht = monthly_rental - ((wht / 100) * monthly_rental);
                document.getElementById("monthly_rental").value = less_wht.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                var balance = (lease_period * 12) * less_wht;
                document.getElementById("balance").value = balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                $scope.actual_balance()
            }
        }
    }



    $scope.compute_contract_expiration = function(lease_period, opening_date) {
        lease_period = lease_period.split(" ");
        lease_period = $scope.toNumber(lease_period[0]);
        var date = new moment(opening_date);

        date.add(lease_period, 'years');
        $scope.expiry_date = moment(date).format('YYYY-M-DD');

    }

    $scope.days_diff = function(opening_date, expiry_date) {
        var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        var firstDate = new Date(opening_date);
        var secondDate = new Date(expiry_date);

        var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime()) / (oneDay)));
        document.getElementById("num_days").value = diffDays + 1;

        var rate_perday = toNumber(document.getElementById("per_day").value);
        var actual_balance = rate_perday * (diffDays + 1);

        document.getElementById("actual_balance").value = actual_balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

    }


    $scope.recalculate_rental = function(price_persq, floor_area, opening_date, expiry_date) {
        price_persq = toNumber(price_persq);
        floor_area = toNumber(floor_area);
        var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        var firstDate = new Date(opening_date);
        var secondDate = new Date(expiry_date);

        var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime()) / (oneDay)));

        var monthly_rent = price_persq * floor_area;
        var newDaily_rate = monthly_rent / 30;
        var newRental = newDaily_rate * diffDays;

        newDaily_rate = Number(newDaily_rate).toFixed(2);
        newRental = Number(newRental).toFixed(2);

        document.getElementById("amendment_numdays").value = diffDays;
        document.getElementById("amendment_rate_perday").value = newDaily_rate;
        document.getElementById("amendment_basic_rental").value = newRental;


    }


    $scope.clear_numbers = function(price_perSq, floor_area, basic_rental) {
        document.getElementById(price_perSq).value = '0.00';
        document.getElementById(floor_area).value = '0.00';
        document.getElementById(basic_rental).value = '0.00';
    }


    $scope.checkall = function(checkall, id) {
        var checkboxes = document.getElementsByTagName('input');
        if (document.getElementById(checkall).checked) {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = true;
                }
            }
        } else {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = false;
                }
            }
        }
    }


    $scope.user_credentials = function(url) {
        $scope.details;
        $http.post(url).success(function(result) {
            $scope.details = result;

            // console.log(result);
        });
    }

    $scope.terminate_tenant = function(url, user_type) {
        var tenant_id = document.getElementById('tenantID').value;
        url = url + '/' + tenant_id;
        if (user_type != 'Store Manager' && user_type != 'Administrator' && user_type != 'Corporate Documentation Officer' && user_type != 'Documentation Officer' && user_type != 'Corporate Leasing Supervisor') {
            trigger_managersKey();
            $scope.managers_key(url);
        } else {
            trigger_confirmation();
            $scope.confirm(url);
        }

    }

    //EZ EDITS
    $scope.getStores = function(url) {
        $http.get(url).success(function(result) {
            $scope.stores = result;
        });
    }



    //=========================== Discount Functions ============================//

    $scope.pickedDiscounts = [];
    $scope.allDiscounts = [];


    $scope.selectedCharges = [];
    $scope.get_selectedCharges = function(url) {
        $http.post(url).success(function(result) {
            $scope.selectedCharges = result;

            console.log($scope.selectedCharges);
        });
    }

    $scope.is_chargesExist = function(charge) {

        if (filterFilter($scope.selectedCharges, {
                charges_code: charge.charges_code
            }).length > 0) {
            return true;
        }
    }

    $scope.get_pickedDiscounts = function(url) {
        $http.post(url).success(function(result) {
            $scope.pickedDiscounts = result;
        });
    }

    $scope.get_allDiscounts = function(url) {

        $http.post(url).success(function(result) {
            $scope.allDiscounts = result;
        });
    }



    $scope.is_discountExist = function(discount) {
        if (filterFilter($scope.pickedDiscounts, {
                id: discount.id
            }).length > 0) {
            return true;
        }
    }



    //======================== End of Discount Functions ========================//

    // ======================= KING ARTHURS REVISION MY REVISION FOR LONGTERM PROSPECT MANAGEMENT ======= //
    $scope.decline_remarks = function($id, $sName, $tname, $url) {
        $scope.link = $url;
        $scope.ID = $id;
        $scope.sName = $sName;
        $scope.tname = $tname;

        console.log($scope.link);
    }

    $scope.managers_key2 = function(url) {
            document.getElementById('frm_manager').action = url;
        }
        // ======================= KING ARTHURS REVISION MY REVISION FOR LONGTERM PROSPECT MANAGEMENT END ==== //

});


myApp.$inject = ['$scope'];


//======================== Chart Controller ========================//


myApp.controller('CtrlChart', function($scope, $http) {
    $scope.homeChart = function() {
        $http.post('../leasing_dashboard/tenants_perYear/').success(function(result) {
            $scope.series = ['Long Term Tenants', 'Short Term Tenants'];
            $scope.colours = ["#0084FF", "#274A3C", "#0000FF", "#00FFFF", "#FF0000"];
            $scope.labels = [];
            $scope.data = [];
            var longtermData = [];
            var shorttermData = [];

            for (var i = 0; i < result.length; i++) {
                $scope.labels.push(result[i].year);
                longtermData.push(result[i].lterm_count);
                shorttermData.push(result[i].sterm_count);
            };

            $scope.data = [
                longtermData,
                shorttermData
            ];
        });
        $scope.onClick = function(points, evt) {
            console.log(points, evt);
        };
    }

    $scope.barChart = function() {


        $scope.barData = [];
        $scope.barLabels = [];
        $scope.barColours = [];
        $scope.barSeries = [];
        var arrData = [];

        $http.post('../leasing_dashboard/tenants_perLesseeType/').success(function(result) {

            for (var i = 0; i < result.length; i++) {
                $scope.barColours.push('#' + (Math.random() * 0xFFFFFF << 0).toString(16));
                $scope.barSeries.push(result[i].leasee_type);
                arrData.push(result[i].tenantCount);
            };

            //console.log(arrData);

            for (var x = 0; x < arrData.length; x++) {
                $scope.barData.push([arrData[x]]);
            }


        });




        $scope.onClick = function(points, evt) {
            console.log(points, evt);
        };
    }

    $scope.lineChart = function() {
        $scope.lineLabel = ["2010", "2011", "2012", "2013", "2014", "2015", "2016"];
        $scope.lineSeries = ['Long Term Tenants', 'Short Term Tenants'];
        $scope.lineColours = ["#0084FF", "#274A3C", "#0000FF", "#00FFFF", "#FF0000"];
        $scope.lineData = [
            [65, 87, 80, 81, 74, 85, 88, 89, 80, 75, 72, 69],
            [87, 69, 78, 70, 86, 83, 90, 82, 78, 94, 85, 100]
        ];
    }

    $scope.doughnutChart = function() {

        $http.post('../leasing_dashboard/tenants_perAreaClassification/').success(function(result) {


            $scope.doughnutLabels = [];
            $scope.doughnutData = [];

            for (var i = 0; i < result.length; i++) {
                $scope.doughnutLabels.push(result[i].classification);
                $scope.doughnutData.push(result[i].tenantCount);
            };
        });
    }

    $scope.pieChart = function() {
        $http.post('../leasing_dashboard/tenants_perAreaType/').success(function(result) {


            $scope.pieLabels = [];
            $scope.pieData = [];
            for (var i = 0; i < result.length; i++) {
                $scope.pieLabels.push(result[i].area_type);
                $scope.pieData.push(result[i].tenantCount);
            };
        });

    }


    $scope.printChart = function(canvas, store_name, store_address) {
        var canvas = document.getElementById(canvas);
        var img = canvas.toDataURL("image/png");
        var header = "<center><h1>" + store_name + "</h1><h4>" + store_address + "<h4></center>";
        var footer = "<chart-legend><ul class='line-legend'><li><span style='background-color:blue'></span>Long Term Tenants</li><li><span style='background-color:green'></span>Short Term Tenants</li></ul></chart-legend>";
        var win = window.open();
        win.document.write(header + "<br><center><img src='" + img + "'/></center><br>" + footer);
        win.print();
        win.location.reload();
    }


    // $scope.check_expiredTenants = function()
    // {

    //     $http.post('../leasing_transaction/check_expiredTenants/').success(function(result){

    //     });
    // }

});


//======================== End of Chart Controller ========================//




//======================== Table Controller ========================//
myApp.controller('tableController', function($scope, $http, $timeout, $rootScope, $q, NgTableParams, $filter, filterFilter) {

    $rootScope.$on("CallTablelistMethod", function(event, data) {
        $scope.loadList(data);
    });

    $scope.dataList = [];

    $scope.loadList = function(url) {

        $scope.isLoading = true;
        var data = $http.post(url).success(function(data) {
            $scope.dataList = data;
            $scope.$watch("searchedKeyword", function() {
                $scope.tableParams.page(1);
                $scope.tableParams.reload();
            });

            var searchData = function() {
                if ($scope.searchedKeyword)
                    return $filter('filter')($scope.dataList, $scope.searchedKeyword);
                return $scope.dataList;
            }

            $scope.tableParams = new NgTableParams({
                page: 1, // show first page
                count: 15 // count per page
            }, {
                total: $scope.dataList.length, // length of data
                getData: function(params) {
                    var searchedData = searchData();
                    params.total(searchedData.length);
                    $scope.data = searchedData.slice((params.page() - 1) * params.count(), params.page() * params.count());
                    $scope.data = params.sorting() ? $filter('orderBy')($scope.data, params.orderBy()) : $scope.data;
                    return $scope.data;
                }
            });
        });

        $q.all([data]).then(function(ret) {
            $scope.isLoading = false;
        });
    }



    $scope.ajaxDataList = [];

    $scope.generateTenantSOA = function(url) {
        this.ajaxLoadList(url, $('#trade_name').val());
    }

    $scope.ajaxLoadList = function(url, param) {
        // console.log(url + param);

        var objData = { "param": param };
        $scope.isLoading = true;
        var data = $http.post(url, objData).success(function(ajaxData) {

            $scope.ajaxDataList = ajaxData;
            $scope.$watch("searchedKeyword", function() {
                $scope.ajaxTableParams.page(1);
                $scope.ajaxTableParams.reload();
            });

            var searchData = function() {
                if ($scope.searchedKeyword)
                    return $filter('filter')($scope.ajaxDataList, $scope.searchedKeyword);
                return $scope.ajaxDataList;
            }

            $scope.ajaxTableParams = new NgTableParams({
                page: 1, // show first page
                count: 15 // count per page
            }, {
                total: $scope.ajaxDataList.length, // length of data
                getData: function(params) {
                    var searchedData = searchData();
                    params.total(searchedData.length);
                    $scope.ajaxData = searchedData.slice((params.page() - 1) * params.count(), params.page() * params.count());
                    $scope.ajaxData = params.sorting() ? $filter('orderBy')($scope.ajaxData, params.orderBy()) : $scope.ajaxData;
                    return $scope.ajaxData;
                }
            });
        });

        $q.all([data]).then(function(ret) {
            $scope.isLoading = false;
        });
    }


    $http.post("../leasing_mstrfile/get_floors/").success(function(result) {
        $scope.floorList = result;
    });


    $scope.selectedCharges = [];
    $scope.get_selectedCharges = function(url) {
        $http.post(url).success(function(result) {
            $scope.selectedCharges = result;

            console.log($scope.selectedCharges);
        });
    }

    $scope.is_chargesExist = function(charge) {

        if (filterFilter($scope.selectedCharges, {
                charges_code: charge.charges_code
            }).length > 0) {
            return true;
        }
    }



});


//======================== End of Table Controller ========================//





//======================== Transaction Controller ========================//

myApp.controller('transactionController', function($scope, $http, $timeout, $interval, $rootScope, $compile, $q) {


    $scope.vat = 0;
    $scope.wht = 0;
    $scope.rental_increment = 0;
    $scope.discount_uom = '';
    $scope.discount = 0;
    $scope.rent_percentage = 0;
    $scope.basic_rental = 0;
    $scope.is_vat = '';
    $scope.is_wht = '';
    $scope.rentable_sales = 0;

    $http.post('../leasing_transaction/vat_wht/').success(function(result) {
        $scope.vat = result['vat'];
        $scope.wht = result['wht'];
    });


    $scope.get_chargeDetails = function(url) {

        $scope.chargeDetails = [];
        $http.post(url).success(function(result) {
            $scope.chargeDetails = result;
            console.log(result);
        });
    }

    $scope.preOp_actualAmt = function(uom, basic_rental) {
        uom = uom.replace(/,/g, "");
        uom = parseFloat(uom);

        basic_rental = parseFloat(basic_rental);

        var actual_amt = uom * basic_rental;
        $scope.preOp_amount = actual_amt;
        // document.getElementById("preOp_actualAmt").value = actual_amt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    $scope.penalty_actualAmt = function(unit_price, total_unit) {
        unit_price = unit_price.replace(/,/g, "");
        unit_price = parseFloat(unit_price);

        total_unit = total_unit.replace(/,/g, "");
        total_unit = parseFloat(total_unit);

        var actual_amt = unit_price * total_unit;
        document.getElementById("penalty_actualAmt").value = actual_amt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    $scope.getTotalSOA = function(amount) {
        var amt = $scope.toNumber(amount);
        var totalAmount = $scope.toNumber(document.getElementById("totalAmount").value);
        var new_total = totalAmount - amt;
        document.getElementById("totalAmount").value = new_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }


    $scope.get_paymentTotal = function(amount) {
        var amt = $scope.toNumber(amount);
        var totalAmount = $scope.toNumber(document.getElementById("total_amount").value);
        var new_total = totalAmount - amt;
        document.getElementById("total_amount").value = new_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    $scope.getSelected = function(data) {
        return data.filter(function(dt) {
            return dt.selected;
        })
    }

    $scope.selectedIPTotal = function(data) {

        data = !data ? [] : data;

        console.log(data);
        let total = 0;

        data.forEach(function(dt) {
            if (dt.selected) {
                total += ($scope.toNumber(dt.amount) && $scope.toNumber(dt.amount) > 0 ? $scope.toNumber(dt.amount) : 0);
            }
        })

        return total;
    }

    $scope.get_totalUncleared = function(amount) {
        var amt = $scope.toNumber(amount);
        $scope.total_amount -= amt;
    }

    $scope.generate_tenantDetailsForCFS = function(tenant_id) {

        $http.post('../ctrl_cfs/tenantDetails/' + tenant_id).success(function(result) {
            $scope.trade_name = result[0].trade_name;
            $scope.tenant_id = result[0].tenant_id;
            $scope.contract_no = result[0].contract_no;
        });

        $http.post('../leasing_transaction/get_storeBankCode/' + tenant_id).success(function(data) {
            console.log(data);
            $scope.storeBankCode = data;
        });
    }

    $scope.generate_invoicingCredentials = function(trade_name, tenancy_type) {
        // ==== Clear table first ==== //
        clear_invoicingTable();

        $scope.is_longTerm = "";
        $scope.trade_name = [];
        var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };


        $http.post('../leasing_transaction/get_tradeName/', objData).success(function(result) {
            $scope.tenant_primaryKey = result[0].primaryKey;
            $scope.trade_name = result[0].trade_name;
            $scope.contract_no = result[0].contract_no;
            $scope.tenant_id = result[0].tenant_id;
            $scope.rental_type = result[0].rental_type;
            $scope.rent_percentage = result[0].rent_percentage;
            $scope.increment_percentage = result[0].increment_percentage;
            $scope.tenant_type = result[0].tenant_type;
            $scope.opening_date = result[0].opening_date;
            $scope.basic_rental = result[0].basic_rental;
            $scope.is_vat = result[0].is_vat;
            $scope.is_wht = result[0].wht;
            $scope.vat_agreement = result[0].vat_agreement;
            $scope.is_incrementable = result[0].is_incrementable;
            $scope.tenantIncrement_id = result[0].id;

            $http.post('../leasing_transaction/prev_electricity_reading/' + $scope.tenant_id).success(function(data) {
                if (data.length > 0) {
                    $scope.electricReading = data[0].curr_reading;
                } else {
                    $scope.electricReading = 0;
                }
            });
            $http.post('../leasing_transaction/prev_water_reading/' + $scope.tenant_id).success(function(data) {
                if (data.length > 0) {
                    $scope.waterReading = data[0].curr_reading;
                } else {
                    $scope.waterReading = 0;
                }
            });


            $http.post('../leasing_transaction/get_storeBankCode/' + $scope.tenant_id).success(function(data) {
                console.log(data);
                $scope.storeBankCode = data;
            });

        });
    }



    $scope.generate_invoicingRetro = function(trade_name) {
        var objData = { "trade_name": trade_name };
        $http.post('../leasing_transaction/get_tradeName/', objData).success(function(result) {
            //console.log(result);
            $scope.tenant_primaryKey = result[0].primaryKey;
            $scope.trade_name = result[0].trade_name;
            $scope.contract_no = result[0].contract_no;
            $scope.tenant_id = result[0].tenant_id;
            $scope.rental_type = result[0].rental_type;
            $scope.rent_percentage = result[0].rent_percentage;
            $scope.increment_percentage = result[0].increment_percentage;
            $scope.tenant_type = result[0].tenant_type;
            $scope.basic_rental = result[0].basic_rental;
            $scope.is_vat = result[0].is_vat;
            $scope.is_incrementable = result[0].is_incrementable;
            $scope.tenantIncrement_id = result[0].id;
        });
        openModal('retro_charges');
    }


    $scope.generate_soaCredentials = function(trade_name) {

        var objData = { "trade_name": trade_name };
        $http.post('../leasing_transaction/generate_soaCredentials/', objData).success(function(result) {

            $scope.isLoading = true;
            $scope.trade_name = result[0].trade_name;
            $scope.description = result[0].trade_name;
            $scope.tenant_address = result[0].address;
            $scope.contract_no = result[0].contract_no;
            $scope.invoiceBasic = [];
            $scope.invoiceOther = [];
            $scope.totalAmount = 0.00;
            var tenant_id = result[0].tenant_id;
            $scope.tenant_id = result[0].tenant_id;
            var basic_amount = 0;
            var other_amount = 0;


            var penalty = $http.post('../leasing_transaction/get_penalty/' + tenant_id + '/' + $scope.contract_no).success(function(res) {
                $scope.invoicePenalty = res;

                for (var i = 0; i < res.length; i++) {
                    if (res.length != 0) {
                        basic_amount += parseFloat(res[i].balance);
                    }
                };
            });


            var basic = $http.post('../leasing_transaction/get_invoiceBasic/' + tenant_id).success(function(data) {
                $scope.invoiceBasic = data;
                //console.log(data);
                for (var i = 0; i < data.length; i++) {
                    basic_amount += parseFloat(data[i].balance);
                }
            });


            var others = $http.post('../leasing_transaction/get_invoiceOtherCharges/' + tenant_id).success(function(data) {

                $scope.invoiceOther = data;
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    other_amount += parseFloat(data[i].balance);
                }
                // $scope.totalAmount = parseFloat(other_amount.toFixed(2)) + parseFloat(basic_amount.toFixed(2));
                $scope.total_amountDue = Number(parseFloat(other_amount) + parseFloat(basic_amount)).toFixed(2);
            });


            var preop = $http.post('../leasing_transaction/get_tenantpreopCharges/' + tenant_id).success(function(data) {
                $scope.preopCharges = data;
                // console.log(data);
                for (var i = 0; i < data.length; i++) {
                    other_amount += parseFloat(data[i].amount);
                }
                // $scope.totalAmount = parseFloat(other_amount.toFixed(2)) + parseFloat(basic_amount.toFixed(2));
                $scope.total_amountDue = Number(parseFloat(other_amount) + parseFloat(basic_amount)).toFixed(2);
            });


            var retro = $http.post('../leasing_transaction/get_invoiceRetro/' + tenant_id).success(function(retro) {
                $scope.retroCharges = retro;
                for (var i = 0; i < retro.length; i++) {
                    other_amount += parseFloat(retro[i].balance);
                }
            });

            $scope.soa_no = [];
            var soa_no = $http.post('../leasing_transaction/get_soaNo/' + tenant_id).success(function(result) {
                $scope.soa_no = result['soa_no'];
            });

            $scope.doc_no = [];
            var doc_no = $http.post('../leasing_transaction/get_docNo/' + tenant_id).success(function(result) {
                $scope.doc_no = result['doc_no'];
            });


            $q.all([penalty, basic, retro, others, preop, soa_no, doc_no]).then(function(ret) {
                $scope.total_amountDue = Number(parseFloat(other_amount) + parseFloat(basic_amount)).toFixed(2);
                $scope.isLoading = false;
            });
        });


    }

    $scope.clear_soaForm = function() {
        $scope.trade_name = "";
        $scope.tenant_address = "";
        $scope.contract_no = "";
        $scope.dirty.value = "";
        $scope.total_amountDue = "";
        clear_soaTable();
    }

    $scope.get_tenantSoa = function(tenant_id) {

        // console.log("tenant_id");
        $http.post('../leasing_transaction/get_tenantSoa/' + tenant_id).success(function(result) {
            $scope.tenantSoa = result;
        });
    }


    $scope.get_unCloseRentDue = function(url) {
        $scope.unCloseRentDue = [];
        $http.post(url).success(function(result) {
            $scope.unCloseRentDue = result;
        });
    }


    $scope.generate_soaForPayment = function(trade_name) {
        $scope.soa_no;
        $scope.total_amount = 0.00;
        var objData = { "trade_name": trade_name };

        $http.post('../leasing_transaction/get_soaDocs/', objData).success(function(result) {
            $scope.isLoading = true;
            $scope.trade_name = result[0].trade_name;
            $scope.doc_no = result[0].doc_no;
            $scope.soa_no = result[0].soa_no;
            $scope.billing_period = result[0].billing_period;
            $scope.corporate_name = result[0].corporate_name;
            $scope.contract_no = result[0].contract_no;
            // $scope.total_amount = result[0].total_amount;
            $scope.trade_name = result[0].trade_name;
            var tenant_id = result[0].tenant_id;
            $scope.tenant_id = tenant_id;

            var penalty = $http.post('../leasing_transaction/get_penalty/' + tenant_id + '/' + $scope.contract_no).success(function(res) {
                $scope.invoicePenalty = res;

                for (var i = 0; i < res.length; i++) {
                    if (res.length != 0) {
                        $scope.total_amount += parseFloat(res[i].balance);
                    }
                };
            });

            var basic = $http.post('../leasing_transaction/get_glBasicPayment/' + tenant_id).success(function(data) {
                $scope.paymentBasic = data;

                for (var i = 0; i < data.length; i++) {
                    if (data.length != 0) {
                        $scope.total_amount += parseFloat(data[i].balance);
                    }
                };
            });


            var retro = $http.post('../leasing_transaction/get_glRetroPayment/' + tenant_id).success(function(data) {
                $scope.paymentRetro = data;
                for (var i = 0; i < data.length; i++) {
                    if (data.length != 0) {
                        $scope.total_amount += parseFloat(data[i].balance);
                    }
                };
            });


            var others = $http.post('../leasing_transaction/get_glOtherPayment/' + tenant_id).success(function(data) {
                $scope.paymentOther = data;
                for (var i = 0; i < data.length; i++) {
                    if (data.length != 0) {
                        $scope.total_amount += parseFloat(data[i].balance);
                    }
                };
            });


            var preop = $http.post('../leasing_transaction/get_tenantpreopCharges/' + tenant_id).success(function(data) {
                $scope.preopCharges = data;
                // console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $scope.total_amount += parseFloat(data[i].amount);
                }
            });

            var bank_code = $http.post('../leasing_transaction/get_storeBankCode/' + tenant_id).success(function(data) {
                $scope.storeBankCode = data;
            });


            $q.all([penalty, basic, retro, others, preop, bank_code]).then(function(ret) {
                $scope.isLoading = false;
            });
        });
    }



    $scope.generate_primaryDetails = function(trade_name) {
        $http.post('../leasing_transaction/get_soaDocs/' + trade_name).success(function(result) {
            // console.log(result);
            $scope.trade_name = result[0].trade_name;
            $scope.corporate_name = result[0].corporate_name;
            $scope.contract_no = result[0].contract_no;
            $scope.tenant_id = result[0].tenant_id;
            $scope.soa_no = result[0].soa_no;
        });
    }


    $scope.SOA_done = function(trade_name) {
        $scope.total_amount = 0;
        $http.post('../leasing_transaction/get_soaDocs/' + trade_name).success(function(result) {
            $scope.trade_name = result[0].trade_name;
            $scope.corporate_name = result[0].corporate_name;
            $scope.contract_no = result[0].contract_no;
            $scope.tenant_id = result[0].tenant_id;
            $scope.soa_no = result[0].soa_no;

            $http.post('../leasing_transaction/SOA_done/' + $scope.tenant_id).success(function(result) {
                // console.log(result);
                $scope.SOA_done_items = result;

                for (var i = 0; i < result.length; i++) {
                    if (result.length != 0) {
                        $scope.total_amount += parseFloat(result[i].balance);
                    }
                };
            });

        });
    }


    $scope.get_unclearedPayment = function(trade_name) {

        $scope.total_amount = 0;
        var objData = { "trade_name": trade_name };
        $http.post('../leasing_transaction/generate_primaryDetails/', objData).success(function(result) {
            $scope.isLoading = true;
            $scope.trade_name = result[0].trade_name;
            $scope.corporate_name = result[0].corporate_name;
            $scope.contract_no = result[0].contract_no;
            $scope.tenant_id = result[0].tenant_id;


            var banks = $http.post('../leasing_transaction/get_storebankCode/' + $scope.tenant_id).success(function(codes) {
                console.log(codes);
                $scope.storeBankCode = codes;
            });

            $scope.unclearedPayments;
            var data = $http.post('../leasing_transaction/get_unclearedPayment/' + $scope.tenant_id).success(function(result) {
                $scope.unclearedPayments = result;

                for (var i = 0; i < result.length; i++) {
                    if (result.length != 0) {
                        if (parseFloat(result[i].amount) > 0) {
                            $scope.total_amount += parseFloat(result[i].amount);
                        }
                    }
                };
            });

            $q.all([banks, data]).then(function(ret) {
                $scope.isLoading = false;
            });

        });
    }


    $scope.get_internalUnclearedPayment = function(trade_name) {

        $scope.total_amount = 0;
        var objData = { "trade_name": trade_name };
        $http.post('../leasing_transaction/generate_primaryDetails/', objData).success(function(result) {
            $scope.isLoading = true;
            $scope.trade_name = result[0].trade_name;
            $scope.corporate_name = result[0].corporate_name;
            $scope.contract_no = result[0].contract_no;
            $scope.tenant_id = result[0].tenant_id;


            var banks = $http.post('../leasing_transaction/get_storebankCode/' + $scope.tenant_id).success(function(codes) {
                console.log(codes);
                $scope.storeBankCode = codes;
            });

            $scope.unclearedPayments;
            var data = $http.post('../leasing_transaction/get_internalUnclearedPayment/' + $scope.tenant_id).success(function(result) {
                $scope.unclearedPayments = result;

                for (var i = 0; i < result.length; i++) {
                    if (result.length != 0) {
                        if (parseFloat(result[i].amount) > 0) {
                            $scope.total_amount += parseFloat(result[i].amount);
                        }
                    }
                };
            });

            $q.all([banks, data]).then(function(ret) {
                $scope.isLoading = false;
            });

        });
    }






    $scope.get_dataForPayment = function(id) {

        $scope.soa_no;
        $scope.total_amount = 0.00;
        $http.post('../ctrl_cfs/get_soaDocs/' + id).success(function(result) {

            $scope.isLoading = true;
            $scope.trade_name = result[0].trade_name;
            $scope.doc_no = result[0].doc_no;
            $scope.soa_no = result[0].soa_no;
            $scope.billing_period = result[0].billing_period;
            $scope.corporate_name = result[0].corporate_name;
            $scope.contract_no = result[0].contract_no;
            // $scope.total_amount = result[0].total_amount;
            $scope.trade_name = result[0].trade_name;
            var tenant_id = result[0].tenant_id;
            $scope.tenant_id = tenant_id;



            var bank_code = $http.post('../leasing_transaction/get_storebankCode/' + tenant_id).success(function(codes) {
                $scope.storeBankCode = codes;
            });


            var penalty = $http.post('../ctrl_cfs/get_penalty/' + tenant_id + '/' + $scope.contract_no).success(function(res) {
                $scope.invoicePenalty = res;

                for (var i = 0; i < res.length; i++) {
                    if (res.length != 0) {
                        $scope.total_amount += parseFloat(res[i].balance);
                    }
                };
            });

            var basic = $http.post('../ctrl_cfs/get_glBasicPayment/' + tenant_id).success(function(data) {
                $scope.paymentBasic = data;
                for (var i = 0; i < data.length; i++) {
                    if (data.length != 0) {
                        $scope.total_amount += parseFloat(data[i].balance);
                    }
                };
            });


            var retro = $http.post('../ctrl_cfs/get_glRetroPayment/' + tenant_id).success(function(data) {
                $scope.paymentRetro = data;
                for (var i = 0; i < data.length; i++) {
                    if (data.length != 0) {
                        $scope.total_amount += parseFloat(data[i].balance);
                    }
                };
            });

            var others = $http.post('../ctrl_cfs/get_glOtherPayment/' + tenant_id).success(function(data) {
                $scope.paymentOther = data;
                for (var i = 0; i < data.length; i++) {
                    if (data.length != 0) {
                        $scope.total_amount += parseFloat(data[i].balance);
                    }
                };
            });

            var preop = $http.post('../leasing_transaction/get_tenantpreopCharges/' + tenant_id).success(function(data) {
                $scope.preopCharges = data;
                // console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $scope.total_amount += parseFloat(data[i].amount);
                }
            });

            $q.all([penalty, basic, retro, others, preop, bank_code]).then(function(ret) {
                $scope.isLoading = false;
            });

        });
    }


    $scope.reprint_soa = function(file) {
        window.open(file);
    }

    $scope.cancel_soa = function(soa_no) {
        $http.post(url).success(function(res) {

        });
    }

    $scope.shortTerm_charges = function(tenant_id, tenancy_type) {

        if (tenancy_type == 'Short Term Tenant') {
            $scope.chargesList = [];
            $scope.mydiscounts = [];

            $http.post('../leasing_transaction/shortTerm_charges/' + tenant_id).success(function(result) {
                $scope.sTerm_rental = result[0].actual_balance;
                $scope.sTerm_isvat = result[0].is_vat;

                $scope.added_vat;
                $scope.less_witholding;
                $scope.less_discount;
                var rental = toNumber($scope.sTerm_rental);
                var vat = toNumber($scope.vat);
                var wht = toNumber($scope.wht);
                var plus_vat;
                var less_wht;
                var less_discount;
                var discount;

                $http.post('../leasing_transaction/get_myDiscounts/' + tenant_id).success(function(result) {
                    $scope.mydiscounts = result;
                    if ($scope.sTerm_isvat == 'added') {
                        for (var i = 0; i < $scope.mydiscounts.length; i++) {
                            if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                                discount = (toNumber($scope.mydiscounts[i].discount) / 100) * rental;
                                if (i > 0) {
                                    less_discount = less_discount - discount;
                                } else {
                                    less_discount = rental - discount;
                                }
                            } else {
                                if (i > 0) {
                                    less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                                } else {
                                    less_discount = rental - toNumber($scope.mydiscounts[i].discount);
                                }
                            }
                        }

                        if ($scope.mydiscounts.length != 0) {
                            plus_vat = ((vat / 100) + 1) * less_discount;
                            less_wht = plus_vat - ((wht / 100) * less_discount);
                            $scope.added_vat = ($scope.vat / 100) * less_discount;
                            $scope.less_witholding = ($scope.wht / 100) * less_discount;
                            $scope.less_discount = less_discount;
                        } else {
                            plus_vat = ((vat / 100) + 1) * rental;
                            less_wht = plus_vat - ((wht / 100) * rental);
                            $scope.added_vat = ($scope.vat / 100) * rental;
                            $scope.less_witholding = ($scope.wht / 100) * rental;
                            $scope.less_discount = rental;
                        }

                        $scope.sTerm_totalRental = less_wht;

                    } else {

                        for (var i = 0; i < $scope.mydiscounts.length; i++) {
                            if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                                discount = (toNumber($scope.mydiscounts[i].discount) / 100) * rental;
                                if (i > 0) {
                                    less_discount = less_discount - discount;
                                } else {
                                    less_discount = rental - discount;
                                }
                            } else {
                                if (i > 0) {
                                    less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                                } else {
                                    less_discount = rental - toNumber($scope.mydiscounts[i].discount);
                                }
                            }
                        }

                        if ($scope.mydiscounts.length != 0) {
                            less_wht = less_discount - ((wht / 100) * less_discount);
                            $scope.added_vat = ($scope.vat / 100) * less_discount;
                            $scope.less_witholding = ($scope.wht / 100) * less_discount;
                            $scope.less_discount = less_discount;
                        } else {
                            less_wht = rental - ((wht / 100) * rental);
                            $scope.added_vat = ($scope.vat / 100) * rental;
                            $scope.less_witholding = ($scope.wht / 100) * rental;
                            $scope.less_discount = rental;
                        }
                        $scope.sTerm_totalRental = less_wht;
                    }
                });
            });
        }
    }

    $scope.clear_invocingData = function() {
        $scope.trade_name = "";
        $scope.contract_no = "";
        $scope.rental_type = "";
        $scope.discount_uom = "";
        $scope.discount = "";
        $scope.rent_percentage = "";
        $scope.monthly_rental = "";
        $scope.is_vat = "";
        $scope.invoice_no = "";
        $scope.doc_no = "";
        $scope.total = "";
        document.getElementById('tenant_id').value = "";
        clear_invoicingTable();
    }


    $scope.get_otherCharges = function(url) {
        $scope.chargeDesc = [];
        $http.post(url).success(function(result) {
            $scope.chargeDesc = result;
            // console.log(result);
        });
    }


    $scope.get_constMat = function(url) {
        $scope.constMat = [];
        $http.post(url).success(function(result) {
            $scope.constMat = result;
            // console.log(result);
        });
    }

    $scope.get_preopCharges = function(url) {
        $scope.preopDesc = [];
        $http.post(url).success(function(result) {
            $scope.preopDesc = result;
            // console.log(result);
        });
    }



    $scope.get_availableSlot = function(url) {

        var floor_id = document.getElementById("floor_location").value;
        document.getElementById("slots_id").value = "";
        $scope.total_floorArea = 0;
        $scope.total_rent = 0;
        $scope.is_SlotSelected = []; // to clear selected Slots
        openModal('slot_modal');
        $scope.location_slots = [];
        $http.post(url + floor_id).success(function(result) {
            $scope.location_slots = result;
            console.log(result);
        });
    }


    $scope.get_availableSlot_for_amendents = function(url) {
        openModal('slot_modal');
        $scope.is_SlotSelected = []; // to clear selected Slots
        document.getElementById("slots_id").value = "";
        document.getElementById("add_floor_area").value = "";
        document.getElementById("add_basic_rental").value = "";

        $scope.location_slots = [];
        $http.post(url).success(function(result) {
            $scope.location_slots = result;
        });
    }

    $scope.is_SlotSelected = [];
    $scope.selectedSlots = [];
    $scope.total_floorArea = 0.00;
    $scope.total_rent = 0.00;
    $scope.chosen_slotNo = function(param) {
        if ($scope.is_SlotSelected[param]) {
            $http.post('../../../leasing_mstrfile/get_locationSlot_data/' + param).success(function(result) {
                $scope.total_floorArea += parseFloat(result[0].floor_area);
                $scope.total_rent += parseFloat(result[0].rental_rate);
                $scope.selectedSlots.push(param);
                var slots_id = $scope.selectedSlots.toString();
                document.getElementById('slots_id').value = slots_id;

            });

        } else {
            $http.post('../../../leasing_mstrfile/get_locationSlot_data/' + param).success(function(result) {
                $scope.total_floorArea -= parseFloat(result[0].floor_area);
                $scope.total_rent -= parseFloat(result[0].rental_rate);
                var index = $scope.selectedSlots.indexOf(param);
                $scope.selectedSlots.splice(index, 1);
                document.getElementById('slots_id').value = slots_id;
            });
        }
    }



    $scope.chosen_slotNo_for_amendment = function(param) {
        if ($scope.is_SlotSelected[param]) {
            $http.post('../../leasing_mstrfile/get_locationSlot_data/' + param).success(function(result) {
                var total_floorArea = document.getElementById('add_floor_area').value;
                var total_basicRental = document.getElementById('add_basic_rental').value;

                if (total_floorArea == "" || total_floorArea == "0" || total_floorArea == "0.00") {
                    total_floorArea = 0;
                    total_basicRental = 0;
                } else {
                    total_floorArea = parseFloat(total_floorArea);
                    total_basicRental = parseFloat(total_basicRental);
                }

                total_floorArea += parseFloat(result[0].floor_area);
                total_basicRental += parseFloat(result[0].rental_rate);
                $scope.selectedSlots.push(param);
                var slots_id = $scope.selectedSlots.toString();
                document.getElementById('slots_id').value = slots_id;
                document.getElementById('add_floor_area').value = Number(total_floorArea).toFixed(2);
                document.getElementById('add_basic_rental').value = Number(total_basicRental).toFixed(2);
                $scope.total_floorArea = Number(total_floorArea).toFixed(2);
                $scope.total_rent = Number(total_basicRental).toFixed(2);
            });


        } else {

            var total_floorArea = parseFloat(document.getElementById('add_floor_area').value);
            var total_basicRental = document.getElementById('add_basic_rental').value;
            $http.post('../../leasing_mstrfile/get_locationSlot_data/' + param).success(function(result) {
                total_floorArea -= parseFloat(result[0].floor_area);
                total_basicRental -= parseFloat(result[0].rental_rate);
                var index = $scope.selectedSlots.indexOf(param);
                $scope.selectedSlots.splice(index, 1);
                document.getElementById('slots_id').value = slots_id;
                document.getElementById('add_floor_area').value = Number(total_floorArea).toFixed(2);
                document.getElementById('add_basic_rental').value = Number(total_basicRental).toFixed(2);
                $scope.total_floorArea = Number(total_floorArea).toFixed(2);
                $scope.total_rent = Number(total_basicRental).toFixed(2);
            });
        }
    }


    $scope.get_monthly_charges = function(tenant_id) {

        $scope.selected_monthly_charges = [];
        $http.post('../leasing_transaction/selected_monthly_charges/' + tenant_id).success(function(result) {
            $scope.selected_monthly_charges = result;
            console.log(result);
        });
    }



    $scope.get_monthlyCharges = function(url) {

        $scope.monthly_charges = [];
        $http.post(url).success(function(result) {
            $scope.monthly_charges = result;
        });
    }





    $scope.get_monthlyCharges_details = function(url) {
        $scope.monthly_details = [];
        $http.post(url).success(function(result) {
            $scope.monthly_details = result;
        });
    }


    $scope.inputted_retro = function(amount) {
        amount = parseFloat(amount);

        if ($scope.is_vat == 'Added') {
            $scope.added_vat = ($scope.vat / 100) * amount;
            $scope.less_witholding = ($scope.wht / 100) * amount;
            $scope.total_retro = (amount + $scope.added_vat) - $scope.less_witholding;
        } else {
            $scope.less_witholding = ($scope.wht / 100) * amount;
            $scope.total_retro = (amount - $scope.less_witholding);
        }

    }


    $scope.baseOnIncome = function(tenant_primaryKey, income, is_incrementable, tenant_type) {


        $scope.total_basicRental = 0;
        var basic_rental = parseFloat(income);
        $scope.basic_rental = basic_rental;
        $scope.mydiscounts = [];
        var total_rental;
        var less_discount;
        var discount;
        $scope.added_vat;
        $scope.less_witholding;
        $scope.less_discount;
        $scope.percent_increment;
        $scope.increment_value;



        var rental_increment = document.getElementById("rental_increment").value;


        if (is_incrementable > 0) {
            $scope.percent_increment = parseFloat(rental_increment) * parseFloat(is_incrementable);
            $scope.increment_value = (basic_rental * ($scope.percent_increment / 100));
            basic_rental = basic_rental + (basic_rental * ($scope.percent_increment / 100));
        }


        if (tenant_type == 'Private Entities' || tenant_type == 'AGC-Subsidiary') {
            if ($scope.rental_type == 'Percentage Base Tenant') {
                $http.post('../leasing_transaction/get_myDiscounts/' + tenant_primaryKey).success(function(result) {
                    $scope.mydiscounts = result;
                    if ($scope.is_vat == 'Added') {
                        for (var i = 0; i < $scope.mydiscounts.length; i++) {
                            if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                                discount = (toNumber($scope.mydiscounts[i].discount) / 100) * basic_rental;
                                if (i > 0) {
                                    less_discount = less_discount - discount;
                                } else {
                                    less_discount = basic_rental - discount;
                                }
                            } else {
                                if (i > 0) {
                                    less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                                } else {
                                    less_discount = basic_rental - toNumber($scope.mydiscounts[i].discount);
                                }
                            }
                        }

                        if ($scope.mydiscounts.length != 0) {
                            var plus_vat = (($scope.vat / 100) + 1) * less_discount;
                            var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * less_discount);
                            $scope.added_vat = ($scope.vat / 100) * less_discount;
                            $scope.less_witholding = ($scope.wht / 100) * less_discount;
                            $scope.less_discount = less_discount;
                        } else {
                            var plus_vat = (($scope.vat / 100) + 1) * basic_rental;
                            var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * basic_rental);
                            $scope.added_vat = ($scope.vat / 100) * basic_rental;
                            $scope.less_witholding = ($scope.wht / 100) * basic_rental;
                            $scope.less_discount = basic_rental;
                        }

                        $scope.total_basicRental = less_wht;


                    } else {

                        for (var i = 0; i < $scope.mydiscounts.length; i++) {
                            if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                                discount = (toNumber($scope.mydiscounts[i].discount) / 100) * basic_rental;

                                if (i > 0) {
                                    less_discount = less_discount - discount;
                                } else {
                                    less_discount = basic_rental - discount;
                                }
                            } else {

                                if (i > 0) {
                                    less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                                } else {
                                    less_discount = basic_rental - toNumber($scope.mydiscounts[i].discount);
                                }
                            }
                        }

                        if ($scope.mydiscounts.length != 0) {
                            var less_wht = less_discount - ((toNumber($scope.wht) / 100) * less_discount);
                            $scope.less_witholding = ($scope.wht / 100) * less_discount;
                            $scope.less_discount = less_discount;
                        } else {
                            var less_wht = basic_rental - ((toNumber($scope.wht) / 100) * basic_rental);
                            $scope.less_witholding = ($scope.wht / 100) * basic_rental;
                            $scope.less_discount = basic_rental;
                        }
                        $scope.added_vat = "0.00";
                        $scope.total_basicRental = less_wht;
                    }
                });

            }

        }

    }


    $scope.calculate_currentRental_fixedTenant = function(basic_rental, month_days, num_days, tenant_primaryKey, vat_agreement) {

        var rent = parseFloat(basic_rental);
        var m_days = parseFloat(month_days);
        var n_days = parseFloat(num_days);

        $scope.current_rental = (rent / m_days) * n_days;

        $scope.basic_fixed_less_30Days = 0.00;
        $scope.mydiscounts = [];
        var total_rental;
        var less_discount;
        var discount;
        $scope.vat_fixed_less_30Days;
        $scope.wht_fixed_less_30Days;
        $scope.less_discount;
        $scope.wht = '5'; //Default WHT
        $http.post('../leasing_transaction/get_myDiscounts/' + tenant_primaryKey).success(function(result) {

            $scope.mydiscounts = result;
            if ($scope.is_vat == 'Added') {
                for (var i = 0; i < $scope.mydiscounts.length; i++) {
                    if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                        discount = (toNumber($scope.mydiscounts[i].discount) / 100) * $scope.current_rental;
                        if (i > 0) {
                            less_discount = less_discount - discount;
                        } else {
                            less_discount = $scope.current_rental - discount;
                        }
                    } else {
                        if (i > 0) {
                            less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                        } else {
                            less_discount = $scope.current_rental - toNumber($scope.mydiscounts[i].discount);
                        }
                    }
                }

                if ($scope.mydiscounts.length != 0) {
                    var plus_vat = (($scope.vat / 100) + 1) * less_discount;
                    var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * less_discount);
                    $scope.vat_fixed_less_30Days = ($scope.vat / 100) * less_discount;
                    $scope.wht_fixed_less_30Days = ($scope.wht / 100) * less_discount;
                    $scope.less_discount = less_discount;
                } else {
                    var plus_vat = (($scope.vat / 100) + 1) * $scope.current_rental;
                    var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * $scope.current_rental);
                    $scope.vat_fixed_less_30Days = ($scope.vat / 100) * $scope.current_rental;
                    $scope.wht_fixed_less_30Days = ($scope.wht / 100) * $scope.current_rental;
                    $scope.less_discount = $scope.current_rental;
                }

                $scope.basic_fixed_less_30Days = less_wht;


            } else {

                for (var i = 0; i < $scope.mydiscounts.length; i++) {
                    if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                        discount = (toNumber($scope.mydiscounts[i].discount) / 100) * $scope.current_rental;

                        if (i > 0) {
                            less_discount = less_discount - discount;
                        } else {
                            less_discount = $scope.current_rental - discount;
                        }
                    } else {

                        if (i > 0) {
                            less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                        } else {
                            less_discount = $scope.current_rental - toNumber($scope.mydiscounts[i].discount);
                        }
                    }
                }

                if ($scope.mydiscounts.length != 0) {

                    if ($scope.is_wht == 'Added') {
                        var less_wht = less_discount - ((toNumber($scope.wht) / 100) * less_discount);
                        $scope.wht_fixed_less_30Days = ($scope.wht / 100) * less_discount;
                        $scope.less_discount = less_discount;
                    } else {
                        var less_wht = less_discount - less_discount;
                        $scope.wht_fixed_less_30Days = 0.00;
                        $scope.less_discount = less_discount;
                    }

                } else {
                    if ($scope.is_wht == 'Added') {
                        if (vat_agreement == 'Inclusive') {
                            var less_wht = $scope.current_rental - ((toNumber($scope.wht) / 100) * ($scope.current_rental / 1.12));
                            $scope.wht_fixed_less_30Days = ($scope.wht / 100) * ($scope.current_rental / 1.12);
                            $scope.less_discount = $scope.current_rental;
                        } else {
                            var less_wht = $scope.current_rental - ((toNumber($scope.wht) / 100) * $scope.current_rental);
                            $scope.wht_fixed_less_30Days = ($scope.wht / 100) * $scope.current_rental;
                            $scope.less_discount = $scope.current_rental;
                        }
                    } else {
                        var less_wht = $scope.current_rental;
                        $scope.wht_fixed_less_30Days = 0.00;
                        $scope.less_discount = $scope.current_rental;
                    }
                }
                $scope.vat_fixed_less_30Days = "0.00";
                $scope.basic_fixed_less_30Days = less_wht;

            }
        });
    }

    $scope.calculate_currentRental_fixedPercentage = function(basic_rental, total_gross, rent_percentage, month_days, num_days, tenant_primaryKey) {
        var percentage = parseFloat(rent_percentage);
        var total_gross = parseFloat(total_gross);
        var rent = parseFloat(basic_rental);
        var m_days = parseFloat(month_days);
        var n_days = parseFloat(num_days);

        $scope.less_30_fixedPercentage_current_rental = (total_gross * (percentage / 100)) + (rent / m_days) * n_days;

        $scope.basic_fixedPercentage_less_30Days = 0.00;
        $scope.mydiscounts = [];
        var total_rental;
        var less_discount;
        var discount;
        $scope.vat_fixedPercentage_less_30Days;
        $scope.wht_fixedPercentage_less_30Days;
        $scope.less_discount;
        $http.post('../leasing_transaction/get_myDiscounts/' + tenant_primaryKey).success(function(result) {

            $scope.mydiscounts = result;
            if ($scope.is_vat == 'Added') {
                for (var i = 0; i < $scope.mydiscounts.length; i++) {
                    if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                        discount = (toNumber($scope.mydiscounts[i].discount) / 100) * $scope.less_30_fixedPercentage_current_rental;
                        if (i > 0) {
                            less_discount = less_discount - discount;
                        } else {
                            less_discount = $scope.less_30_fixedPercentage_current_rental - discount;
                        }
                    } else {
                        if (i > 0) {
                            less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                        } else {
                            less_discount = $scope.less_30_fixedPercentage_current_rental - toNumber($scope.mydiscounts[i].discount);
                        }
                    }
                }

                if ($scope.mydiscounts.length != 0) {
                    var plus_vat = (($scope.vat / 100) + 1) * less_discount;
                    var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * less_discount);
                    $scope.vat_fixedPercentage_less_30Days = ($scope.vat / 100) * less_discount;
                    $scope.wht_fixedPercentage_less_30Days = ($scope.wht / 100) * less_discount;
                    $scope.less_discount = less_discount;
                } else {
                    var plus_vat = (($scope.vat / 100) + 1) * $scope.less_30_fixedPercentage_current_rental;
                    var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * $scope.less_30_fixedPercentage_current_rental);
                    $scope.vat_fixedPercentage_less_30Days = ($scope.vat / 100) * $scope.less_30_fixedPercentage_current_rental;
                    $scope.wht_fixedPercentage_less_30Days = ($scope.wht / 100) * $scope.less_30_fixedPercentage_current_rental;
                    $scope.less_discount = $scope.less_30_fixedPercentage_current_rental;
                }

                $scope.basic_fixedPercentage_less_30Days = less_wht;


            } else {

                for (var i = 0; i < $scope.mydiscounts.length; i++) {
                    if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                        discount = (toNumber($scope.mydiscounts[i].discount) / 100) * $scope.less_30_fixedPercentage_current_rental;

                        if (i > 0) {
                            less_discount = less_discount - discount;
                        } else {
                            less_discount = $scope.less_30_fixedPercentage_current_rental - discount;
                        }
                    } else {

                        if (i > 0) {
                            less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                        } else {
                            less_discount = $scope.less_30_fixedPercentage_current_rental - toNumber($scope.mydiscounts[i].discount);
                        }
                    }
                }

                if ($scope.mydiscounts.length != 0) {
                    var less_wht = less_discount - ((toNumber($scope.wht) / 100) * less_discount);
                    $scope.wht_fixedPercentage_less_30Days = ($scope.wht / 100) * less_discount;
                    $scope.less_discount = less_discount;
                } else {
                    var less_wht = $scope.less_30_fixedPercentage_current_rental - ((toNumber($scope.wht) / 100) * $scope.less_30_fixedPercentage_current_rental);
                    $scope.wht_fixedPercentage_less_30Days = ($scope.wht / 100) * $scope.less_30_fixedPercentage_current_rental;
                    $scope.less_discount = $scope.less_30_fixedPercentage_current_rental;
                }
                $scope.vat_fixedPercentage_less_30Days = "0.00";
                $scope.basic_fixedPercentage_less_30Days = less_wht;

            }
        });
    }



    $scope.inputted_gross_less30_FixedPercentage = function(tenant_primaryKey, gross, is_incrementable) {
        gross = parseFloat(gross);
        var total_gross = gross - ((gross * .12) / 1.12);
        $scope.less30_FixedPercentage_total_gross = total_gross;
        $scope.total_gross = total_gross;
        // var percent = $scope.rent_percentage / 100;
        // var total_rentable = 0;
        // var total_rental;
        // var less_discount;
        // var discount;
    }


    $scope.inputted_gross = function(tenant_primaryKey, gross, is_incrementable) {
        var tenant_id = document.getElementById("tenant_id").value;
        gross = parseFloat(gross);
        var total_gross = gross - ((gross * .12) / 1.12);
        $scope.wht = '5'; //Default WHT
        if (tenant_id == 'ICM-LT000008') {
            var tax_exempt = $scope.toNumber(document.getElementById("tax_exempt").value);
            tax_exempt = parseFloat(tax_exempt);
            total_gross += tax_exempt;
        };

        $scope.total_gross = total_gross;

        var percent = $scope.rent_percentage / 100;
        var total_rentable = 0;
        var total_rental;
        var less_discount;
        var discount;
        $scope.added_vat;
        $scope.less_witholding;
        $scope.less_discount;

        $scope.percent_increment;
        $scope.increment_value;

        var rental_increment = document.getElementById("rental_increment").value;


        var tenant_id = $scope.tenant_id;
        $scope.mydiscount = [];

        $http.post('../leasing_transaction/get_myDiscounts/' + tenant_primaryKey).success(function(result) {
            //console.log(result);
            $scope.mydiscount = result;

            if ($scope.rental_type == 'Fixed Plus Percentage') {
                if (is_incrementable > 0) {
                    $scope.percent_increment = parseFloat(rental_increment) * parseFloat(is_incrementable);
                    $scope.increment_value = ($scope.basic_rental * ($scope.percent_increment / 100));
                    rent_sale = $scope.basic_rental + ($scope.basic_rental * ($scope.percent_increment / 100));
                    rent_sale = (total_gross * percent) + rent_sale;
                    $scope.rent_sale = (total_gross * percent) + $scope.basic_rental;
                } else {
                    var rent_sale = (total_gross * percent) + $scope.basic_rental;
                    $scope.rent_sale = rent_sale;
                }

                if ($scope.is_vat == 'Added') {
                    for (var i = 0; i < $scope.mydiscount.length; i++) {
                        if ($scope.mydiscount[i].discount_type == 'Percentage') {
                            discount = (toNumber($scope.mydiscount[i].discount) / 100) * rent_sale;
                            if (i > 0) {
                                less_discount = less_discount - discount;
                            } else {
                                less_discount = rent_sale - discount;
                            }
                        } else {
                            if (i > 0) {
                                less_discount = less_discount - toNumber($scope.mydiscount[i].discount);
                            } else {
                                less_discount = rent_sale - toNumber($scope.mydiscount[i].discount);
                            }
                        }
                    }

                    if ($scope.mydiscount.length != 0) {
                        var plus_vat = (($scope.vat / 100) + 1) * less_discount;
                        var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * less_discount);
                        $scope.added_vat = ($scope.vat / 100) * less_discount;
                        $scope.less_witholding = ($scope.wht / 100) * less_discount;
                        $scope.less_discount = less_discount;

                    } else {
                        var plus_vat = (($scope.vat / 100) + 1) * rent_sale;
                        var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * rent_sale);
                        $scope.added_vat = ($scope.vat / 100) * rent_sale;
                        $scope.less_witholding = ($scope.wht / 100) * rent_sale;
                        $scope.less_discount = rent_sale;
                    }

                    $scope.total_rentable = less_wht;
                } else {
                    for (var i = 0; i < $scope.mydiscount.length; i++) {
                        if ($scope.mydiscount[i].discount_type == 'Percentage') {
                            discount = (toNumber($scope.mydiscount[i].discount) / 100) * rent_sale;
                            if (i > 0) {
                                less_discount = less_discount - discount;
                            } else {
                                less_discount = rent_sale - discount;
                            }
                        } else {
                            if (i > 0) {
                                less_discount = less_discount - toNumber($scope.mydiscount[i].discount);
                            } else {
                                less_discount = rent_sale - toNumber($scope.mydiscount[i].discount);
                            }
                        }
                    }

                    if ($scope.mydiscount.length != 0) {
                        var less_wht = less_discount - ((toNumber($scope.wht) / 100) * less_discount);
                        $scope.less_witholding = ($scope.wht / 100) * less_discount;
                        $scope.less_discount = less_discount;
                    } else {
                        var less_wht = rent_sale - ((toNumber($scope.wht) / 100) * rent_sale);
                        $scope.less_witholding = ($scope.wht / 100) * rent_sale;
                        $scope.less_discount = less_discount;
                    }

                    $scope.total_rentable = less_wht;

                }

            } else if ($scope.rental_type == 'Fixed/Percentage w/c Higher') {
                // === Fixed or Percentage w/c higer === //

                var rent_sale = (total_gross * percent);
                $scope.rent_sale = rent_sale;



            } else if ($scope.rental_type == 'Percentage') {

                var rent_sale = (total_gross * percent);
                $scope.rent_sale = rent_sale;

                if (is_incrementable > 0) {
                    $scope.percent_increment = parseFloat(rental_increment) * parseFloat(is_incrementable);
                    $scope.increment_value = (rent_sale * ($scope.percent_increment / 100));
                    rent_sale = rent_sale + (rent_sale * ($scope.percent_increment / 100));
                }


                if ($scope.is_vat == 'Added') {

                    for (var i = 0; i < $scope.mydiscount.length; i++) {
                        if ($scope.mydiscount[i].discount_type == 'Percentage') {
                            discount = (toNumber($scope.mydiscount[i].discount) / 100) * rent_sale;
                            if (i > 0) {
                                less_discount = less_discount - discount;
                            } else {
                                less_discount = rent_sale - discount;
                            }
                        } else {
                            if (i > 0) {
                                less_discount = less_discount - toNumber($scope.mydiscount[i].discount);
                            } else {
                                less_discount = rent_sale - toNumber($scope.mydiscount[i].discount);
                            }
                        }
                    }

                    if ($scope.mydiscount.length != 0) {
                        var plus_vat = (($scope.vat / 100) + 1) * less_discount;
                        var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * less_discount);
                        $scope.added_vat = ($scope.vat / 100) * less_discount;
                        $scope.less_witholding = ($scope.wht / 100) * less_discount;
                        $scope.less_discount = less_discount;

                    } else {
                        var plus_vat = (($scope.vat / 100) + 1) * rent_sale;
                        var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * rent_sale);
                        $scope.added_vat = ($scope.vat / 100) * rent_sale;
                        $scope.less_witholding = ($scope.wht / 100) * rent_sale;
                        $scope.less_discount = rent_sale;
                    }

                    $scope.total_rentable = less_wht;
                } else {
                    for (var i = 0; i < $scope.mydiscount.length; i++) {
                        if ($scope.mydiscount[i].discount_type == 'Percentage') {
                            discount = (toNumber($scope.mydiscount[i].discount) / 100) * rent_sale;
                            if (i > 0) {
                                less_discount = less_discount - discount;
                            } else {
                                less_discount = rent_sale - discount;
                            }
                        } else {
                            if (i > 0) {
                                less_discount = less_discount - toNumber($scope.mydiscount[i].discount);
                            } else {
                                less_discount = rent_sale - toNumber($scope.mydiscount[i].discount);
                            }
                        }
                    }

                    if ($scope.mydiscount.length != 0) {
                        var less_wht = less_discount - ((toNumber($scope.wht) / 100) * less_discount);
                        $scope.less_witholding = ($scope.wht / 100) * less_discount;
                        $scope.less_discount = less_discount;
                    } else {
                        var less_wht = rent_sale - ((toNumber($scope.wht) / 100) * rent_sale);
                        $scope.less_witholding = ($scope.wht / 100) * rent_sale;
                        $scope.less_discount = less_discount;
                    }
                    $scope.total_rentable = less_wht;
                }
            }

        });
    }



    $scope.evaluate_rental = function(tenant_primaryKey, rent_sale, basic_rental, is_incrementable) {

        var discount;
        var less_discount;
        var tenant_id = document.getElementById("tenant_id").value;
        $scope.mydiscount = [];
        $scope.evaluated_rental;
        $scope.higher_rental;
        $scope.hide_increment = false;
        $scope.percent_increment;
        $scope.increment_value;
        var rental_increment = document.getElementById("rental_increment").value;
        $scope.percent_increment = parseFloat(rental_increment) * parseFloat(is_incrementable);
        $scope.increment_value = (basic_rental * ($scope.percent_increment / 100));
        // basic_rental = basic_rental + (basic_rental * ($scope.percent_increment / 100));


        $http.post('../leasing_transaction/get_myDiscounts/' + tenant_primaryKey).success(function(result) {
            $scope.mydiscount = result;

            if ((basic_rental + $scope.increment_value) >= rent_sale) {

                $scope.higher_rental = basic_rental;

                if ($scope.is_vat == 'Added') {
                    for (var i = 0; i < $scope.mydiscount.length; i++) {
                        if ($scope.mydiscount[i].discount_type == 'Percentage') {
                            discount = (toNumber($scope.mydiscount[i].discount) / 100) * basic_rental;
                            if (i > 0) {
                                less_discount = less_discount - discount;
                            } else {
                                less_discount = basic_rental - discount;
                            }
                        } else {
                            if (i > 0) {
                                less_discount = less_discount - toNumber($scope.mydiscount[i].discount);
                            } else {
                                less_discount = basic_rental - toNumber($scope.mydiscount[i].discount);
                            }
                        }
                    }


                    if ($scope.mydiscount.length != 0) {
                        var plus_vat = (($scope.vat / 100) + 1) * (less_discount + $scope.increment_value);
                        var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * less_discount);
                        $scope.added_vat = ($scope.vat / 100) * less_discount;
                        $scope.less_witholding = ($scope.wht / 100) * (less_discount + $scope.increment_value);
                        $scope.less_discount = less_discount;

                    } else {
                        var plus_vat = (($scope.vat / 100) + 1) * (basic_rental + $scope.increment_value);
                        var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * (basic_rental + $scope.increment_value));
                        $scope.added_vat = ($scope.vat / 100) * (basic_rental + $scope.increment_value);
                        $scope.less_witholding = ($scope.wht / 100) * (basic_rental + $scope.increment_value);

                        $scope.less_discount = basic_rental;
                    }
                    $scope.evaluated_rental = less_wht;

                } else { // === No Added Tax === //

                    for (var i = 0; i < $scope.mydiscount.length; i++) {
                        if ($scope.mydiscount[i].discount_type == 'Percentage') {
                            discount = (toNumber($scope.mydiscount[i].discount) / 100) * basic_rental;
                            if (i > 0) {
                                less_discount = less_discount - discount;
                            } else {
                                less_discount = basic_rental - discount;
                            }
                        } else {
                            if (i > 0) {
                                less_discount = less_discount - toNumber($scope.mydiscount[i].discount);
                            } else {
                                less_discount = basic_rental - toNumber($scope.mydiscount[i].discount);
                            }
                        }
                    }

                    if ($scope.mydiscount.length != 0) {
                        var less_wht = less_discount - ((toNumber($scope.wht) / 100) * less_discount);
                        $scope.less_witholding = ($scope.wht / 100) * (basic_rental + less_discount);
                        $scope.less_discount = less_discount;
                    } else {
                        var less_wht = basic_rental - ((toNumber($scope.wht) / 100) * (basic_rental + $scope.increment_value));
                        $scope.less_witholding = ($scope.wht / 100) * basic_rental;
                        $scope.less_discount = less_discount;
                    }

                    $scope.evaluated_rental = less_wht;
                }


            } else { // ===== if percentage is higher than fixed rental ===== //

                $scope.higher_rental = rent_sale;
                $scope.hide_increment = true;
                // $scope.percent_increment = parseFloat(rental_increment) * parseFloat(is_incrementable);
                // $scope.increment_value = (basic_rental * ($scope.percent_increment / 100));
                // rent_sale = rent_sale + (basic_rental * ($scope.percent_increment / 100));

                if ($scope.is_vat == 'Added') {
                    for (var i = 0; i < $scope.mydiscount.length; i++) {
                        if ($scope.mydiscount[i].discount_type == 'Percentage') {
                            discount = (toNumber($scope.mydiscount[i].discount) / 100) * rent_sale;
                            if (i > 0) {
                                less_discount = less_discount - discount;
                            } else {
                                less_discount = rent_sale - discount;
                            }
                        } else {
                            if (i > 0) {
                                less_discount = less_discount - toNumber($scope.mydiscount[i].discount);
                            } else {
                                less_discount = rent_sale - toNumber($scope.mydiscount[i].discount);
                            }
                        }
                    }

                    if ($scope.mydiscount.length != 0) {
                        var plus_vat = (($scope.vat / 100) + 1) * less_discount;
                        var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * less_discount);
                        $scope.added_vat = ($scope.vat / 100) * less_discount;
                        $scope.less_witholding = ($scope.wht / 100) * less_discount;
                        $scope.less_discount = less_discount;

                    } else {
                        var plus_vat = (($scope.vat / 100) + 1) * rent_sale;
                        var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * rent_sale);
                        $scope.added_vat = ($scope.vat / 100) * rent_sale;
                        $scope.less_witholding = ($scope.wht / 100) * rent_sale;
                        $scope.less_discount = rent_sale;
                    }
                    $scope.evaluated_rental = less_wht;

                } else { // === No Added Tax === //

                    for (var i = 0; i < $scope.mydiscount.length; i++) {
                        if ($scope.mydiscount[i].discount_type == 'Percentage') {
                            discount = (toNumber($scope.mydiscount[i].discount) / 100) * rent_sale;
                            if (i > 0) {
                                less_discount = less_discount - discount;
                            } else {
                                less_discount = rent_sale - discount;
                            }
                        } else {
                            if (i > 0) {
                                less_discount = less_discount - toNumber($scope.mydiscount[i].discount);
                            } else {
                                less_discount = rent_sale - toNumber($scope.mydiscount[i].discount);
                            }
                        }
                    }

                    if ($scope.mydiscount.length != 0) {
                        var less_wht = less_discount - ((toNumber($scope.wht) / 100) * rent_sale);
                        $scope.less_witholding = ($scope.wht / 100) * less_discount;
                        $scope.less_discount = less_discount;
                    } else {
                        var less_wht = rent_sale - ((toNumber($scope.wht) / 100) * rent_sale);
                        $scope.less_witholding = ($scope.wht / 100) * rent_sale;
                        $scope.less_discount = less_discount;
                    }

                    $scope.evaluated_rental = less_wht;
                }
            }

        });

    }


    $scope.openBasic = function(tenancy_type, tenant_type, opening_date) {

        opening_date = moment(opening_date);
        var rental_type = $('#rental_type').val();
        var current_date = moment();
        var diff = current_date.diff(opening_date, 'days')

        $scope.month_days = moment().daysInMonth()
        if (diff >= 30 || tenancy_type == 'Short Term Tenant') {
            openBasic(tenant_type);
        } else {
            if (rental_type == 'Fixed') {
                openModal('less_than_30Days_Fixed_tenant');
            } else if (rental_type == 'Percentage') {
                openModal('basicRental_modal');
            } else if (rental_type == 'Fixed Plus Percentage') {
                openModal('less_than_30Days_FixedPercentage_tenant');
            } else if (rental_type == 'Fixed/Percentage w/c Higher') {
                openModal('less_than_30Days_FixedORPercentage_tenant');
            }
        }

    }

    $scope.openOther = function(tenancy_type, opening_date) {
        pening_date = moment(opening_date);
        var current_date = moment();
        var diff = current_date.diff(opening_date, 'days')
        $scope.month_days = moment().daysInMonth()
        $scope.num_of_days = diff;
        if (diff < 30 && tenancy_type == 'Long Term Tenant') {
            openModal('less30_monthly_charges');
        } else {
            openModal('monthly_charges');
        }
    }


    $scope.datafor_basicRent = function(tenant_primaryKey, tenant_id, is_incrementable, tenant_type, vat_agreement) {
        $scope.total_basicRental = 0;
        var basic_rental = parseFloat($scope.basic_rental);
        $scope.basic_rental = basic_rental;
        $scope.mydiscounts = [];
        var total_rental;
        var less_discount;
        var discount;
        $scope.added_vat;
        $scope.less_witholding;
        $scope.less_discount;
        $scope.percent_increment;
        $scope.increment_value;
        $scope.wht = '5'; //Default WHT
        if (tenant_id == 'ICM-LT000029' || tenant_id == 'ICM-LT000218' || tenant_id == 'ICM-LT000219' || tenant_id == 'PM-LT000009') {
            $scope.wht = '10';
        }

        var rental_increment = document.getElementById("rental_increment").value;

        if (is_incrementable > 0) {
            $scope.percent_increment = parseFloat(rental_increment) * parseFloat(is_incrementable);
            $scope.increment_value = (basic_rental * ($scope.percent_increment / 100));
            basic_rental = basic_rental + (basic_rental * ($scope.percent_increment / 100));
        }

        if (tenant_type == 'Private Entities' || tenant_type == 'AGC-Subsidiary' || tenant_type == 'Cooperative') {
            if ($scope.rental_type == 'Fixed') {
                $http.post('../leasing_transaction/get_myDiscounts/' + tenant_primaryKey).success(function(result) {
                    $scope.mydiscounts = result;
                    if ($scope.is_vat == 'Added') {
                        for (var i = 0; i < $scope.mydiscounts.length; i++) {
                            if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                                discount = (toNumber($scope.mydiscounts[i].discount) / 100) * basic_rental;
                                if (i > 0) {
                                    less_discount = less_discount - discount;
                                } else {
                                    less_discount = basic_rental - discount;
                                }
                            } else {
                                if (i > 0) {
                                    less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                                } else {
                                    less_discount = basic_rental - toNumber($scope.mydiscounts[i].discount);
                                }
                            }
                        }

                        if ($scope.mydiscounts.length != 0) {
                            if ($scope.is_wht == 'Added') {
                                var plus_vat = (($scope.vat / 100) + 1) * less_discount;
                                var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * less_discount);
                                $scope.added_vat = ($scope.vat / 100) * less_discount;
                                $scope.less_witholding = ($scope.wht / 100) * less_discount;
                                $scope.less_discount = less_discount;
                            } else {
                                var plus_vat = (($scope.vat / 100) + 1) * less_discount;
                                var less_wht = plus_vat;
                                $scope.added_vat = ($scope.vat / 100) * less_discount;
                                $scope.less_witholding = 0;
                                $scope.less_discount = 0;
                            }
                        } else {
                            if ($scope.is_wht == 'Added') {
                                var plus_vat = (($scope.vat / 100) + 1) * basic_rental;
                                var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * basic_rental);
                                $scope.added_vat = ($scope.vat / 100) * basic_rental;
                                $scope.less_witholding = ($scope.wht / 100) * basic_rental;
                                $scope.less_discount = 0;
                            } else {
                                var plus_vat = (($scope.vat / 100) + 1) * basic_rental;
                                var less_wht = plus_vat;
                                $scope.added_vat = ($scope.vat / 100) * basic_rental;
                                $scope.less_witholding = 0;
                                $scope.less_discount = 0;
                            }
                        }

                        $scope.total_basicRental = less_wht;

                    } else {

                        for (var i = 0; i < $scope.mydiscounts.length; i++) {
                            if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                                discount = (toNumber($scope.mydiscounts[i].discount) / 100) * basic_rental;

                                if (i > 0) {
                                    less_discount = less_discount - discount;
                                } else {
                                    less_discount = basic_rental - discount;
                                }
                            } else {

                                if (i > 0) {
                                    less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                                } else {
                                    less_discount = basic_rental - toNumber($scope.mydiscounts[i].discount);
                                }
                            }
                        }

                        if ($scope.mydiscounts.length != 0) {
                            if ($scope.is_wht == 'Added') {
                                if (vat_agreement == 'Inclusive') {
                                    var less_wht = less_discount - ((toNumber($scope.wht) / 100) * (less_discount / 1.12));
                                    $scope.less_witholding = ($scope.wht / 100) * less_discount;
                                    $scope.less_discount = less_discount;
                                } else {
                                    var less_wht = less_discount - ((toNumber($scope.wht) / 100) * less_discount);
                                    $scope.less_witholding = ($scope.wht / 100) * (less_discount / 1.12);
                                    $scope.less_discount = less_discount;
                                }
                            } else {
                                var less_wht = less_discount;
                                $scope.less_witholding = 0.00;
                                $scope.less_discount = less_discount;
                            }
                        } else {
                            if ($scope.is_wht == 'Added') {
                                if (vat_agreement == 'Inclusive') {
                                    var less_wht = basic_rental - ((toNumber($scope.wht) / 100) * (basic_rental / 1.12));
                                    $scope.less_witholding = ($scope.wht / 100) * (basic_rental / 1.12);
                                    $scope.less_discount = basic_rental;
                                } else {
                                    var less_wht = basic_rental - ((toNumber($scope.wht) / 100) * basic_rental);
                                    $scope.less_witholding = ($scope.wht / 100) * basic_rental;
                                    $scope.less_discount = basic_rental;
                                }
                            } else {
                                var less_wht = basic_rental;
                                $scope.less_witholding = 0.00;
                                $scope.less_discount = basic_rental;
                            }
                        }
                        $scope.added_vat = "0.00";
                        $scope.total_basicRental = less_wht;

                    }
                });

            } else if ($scope.rental_type == 'Fixed Plus Percentage') {
                $http.post('../leasing_transaction/get_myDiscounts/' + tenant_primaryKey).success(function(result) {
                    $scope.mydiscounts = result;
                    if ($scope.is_vat == 'Added') {
                        for (var i = 0; i < $scope.mydiscounts.length; i++) {
                            if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                                discount = (toNumber($scope.mydiscounts[i].discount) / 100) * basic_rental;
                                if (i > 0) {
                                    less_discount = less_discount - discount;
                                } else {
                                    less_discount = basic_rental - discount;
                                }
                            } else {
                                if (i > 0) {
                                    less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                                } else {
                                    less_discount = basic_rental - toNumber($scope.mydiscounts[i].discount);
                                }
                            }
                        }

                        if ($scope.mydiscounts.length != 0) {
                            var plus_vat = (($scope.vat / 100) + 1) * less_discount;
                            $scope.added_vat = ($scope.vat / 100) * less_discount;
                            $scope.less_discount = less_discount;
                        } else {
                            var plus_vat = (($scope.vat / 100) + 1) * basic_rental;
                            $scope.added_vat = ($scope.vat / 100) * basic_rental;
                            $scope.less_discount = basic_rental;
                        }
                        $scope.less_witholding = "0.00";
                        $scope.total_basicRental = plus_vat;

                    } else {
                        for (var i = 0; i < $scope.mydiscounts.length; i++) {
                            if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                                discount = (toNumber($scope.mydiscounts[i].discount) / 100) * basic_rental;
                                if (i > 0) {
                                    less_discount = less_discount - discount;
                                } else {
                                    less_discount = basic_rental - discount;
                                }
                            } else {
                                if (i > 0) {
                                    less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                                } else {
                                    less_discount = basic_rental - toNumber($scope.mydiscounts[i].discount);
                                }
                            }
                        }

                        if ($scope.mydiscounts.length != 0) {
                            $scope.total_basicRental = less_discount;
                            $scope.less_discount = less_discount;
                        } else {
                            $scope.total_basicRental = basic_rental;
                            $scope.less_discount = basic_rental;
                        }

                        $scope.less_witholding = "0.00";
                        $scope.added_vat = "0.00";
                    }
                });

            } else {

                $http.post('../leasing_transaction/get_myDiscounts/' + tenant_primaryKey).success(function(result) {
                    $scope.mydiscounts = result;
                });
            }
        } else if (tenant_type == 'Government Agencies(w/o Basic)') {
            generate('error', 'Basic Rental is not applicable for this tenant.');
        } else if (tenant_type == 'Government Agencies(w/ Basic)') {
            $http.post('../leasing_transaction/get_myDiscounts/' + tenant_primaryKey).success(function(result) {
                // console.log(result);
                $scope.mydiscounts = result;

                for (var i = 0; i < $scope.mydiscounts.length; i++) {
                    if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                        discount = (toNumber($scope.mydiscounts[i].discount) / 100) * basic_rental;
                        if (i > 0) {
                            less_discount = less_discount - discount;
                        } else {
                            less_discount = basic_rental - discount;
                        }
                    } else {
                        if (i > 0) {
                            less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                        } else {
                            less_discount = basic_rental - toNumber($scope.mydiscounts[i].discount);
                        }
                    }
                }

                if ($scope.mydiscounts.length != 0) {
                    if ($scope.is_wht == 'Added') {
                        var less_wht = less_discount - ((toNumber($scope.wht) / 100) * less_discount);
                        $scope.less_witholding = ($scope.wht / 100) * less_discount;
                        $scope.less_discount = less_discount;
                    } else {
                        var less_wht = less_discount;
                        $scope.less_witholding = 0.00;
                        $scope.less_discount = less_discount;
                    }

                } else {

                    if ($scope.is_wht == 'Added') {
                        var less_wht = basic_rental - ((toNumber($scope.wht) / 100) * basic_rental);
                        $scope.less_witholding = ($scope.wht / 100) * basic_rental;
                        $scope.less_discount = basic_rental;
                    } else {
                        var less_wht = basic_rental;
                        $scope.less_witholding = 0.00;
                        $scope.less_discount = basic_rental;
                    }
                }

                $scope.total_basicRental = less_wht;
            });
        }
    }


    $scope.basic_manual_input = function(tenant_primaryKey, basic_manual) {


        var basic_rental = parseFloat(basic_manual);
        $scope.mydiscounts = [];
        var total_rental;
        var less_discount;
        var discount;
        $scope.added_vat;
        $scope.less_witholding;
        $scope.less_discount;
        $scope.total_basicRental_manual;


        var tenant_id = document.getElementById("tenant_id").value;

        $scope.wht = '5'; //Default WHT
        if (tenant_id == 'ICM-LT000029' || tenant_id == 'ICM-LT000218' || tenant_id == 'ICM-LT000219' || tenant_id == 'PM-LT000009') {
            $scope.wht = '10';
        }


        $http.post('../leasing_transaction/get_myDiscounts/' + tenant_primaryKey).success(function(result) {
            $scope.mydiscounts = result;
            if ($scope.is_vat == 'Added') {
                for (var i = 0; i < $scope.mydiscounts.length; i++) {
                    if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                        discount = (toNumber($scope.mydiscounts[i].discount) / 100) * basic_rental;
                        if (i > 0) {
                            less_discount = less_discount - discount;
                        } else {
                            less_discount = basic_rental - discount;
                        }
                    } else {
                        if (i > 0) {
                            less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                        } else {
                            less_discount = basic_rental - toNumber($scope.mydiscounts[i].discount);
                        }
                    }
                }

                if ($scope.mydiscounts.length != 0) {
                    var plus_vat = (($scope.vat / 100) + 1) * less_discount;
                    var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * less_discount);
                    $scope.added_vat = ($scope.vat / 100) * less_discount;
                    $scope.less_witholding = ($scope.wht / 100) * less_discount;
                    $scope.less_discount = less_discount;

                } else {
                    if ($scope.is_wht == 'Added') {
                        var plus_vat = (($scope.vat / 100) + 1) * basic_rental;
                        var less_wht = plus_vat - ((toNumber($scope.wht) / 100) * basic_rental);
                        $scope.added_vat = ($scope.vat / 100) * basic_rental;
                        $scope.less_witholding = ($scope.wht / 100) * basic_rental;
                        $scope.less_discount = 0;
                    } else {
                        var plus_vat = (($scope.vat / 100) + 1) * basic_rental;
                        var less_wht = plus_vat;
                        $scope.added_vat = ($scope.vat / 100) * basic_rental;
                        $scope.less_witholding = 0;
                        $scope.less_discount = 0;
                    }
                }

                $scope.total_basicRental_manual = less_wht;

            } else {

                for (var i = 0; i < $scope.mydiscounts.length; i++) {
                    if ($scope.mydiscounts[i].discount_type == 'Percentage') {
                        discount = (toNumber($scope.mydiscounts[i].discount) / 100) * basic_rental;

                        if (i > 0) {
                            less_discount = less_discount - discount;
                        } else {
                            less_discount = basic_rental - discount;
                        }
                    } else {

                        if (i > 0) {
                            less_discount = less_discount - toNumber($scope.mydiscounts[i].discount);
                        } else {
                            less_discount = basic_rental - toNumber($scope.mydiscounts[i].discount);
                        }
                    }
                }

                if ($scope.mydiscounts.length != 0) {

                    if ($scope.is_wht == 'Added') {
                        var less_wht = less_discount - ((toNumber($scope.wht) / 100) * less_discount);
                        $scope.less_witholding = ($scope.wht / 100) * less_discount;
                        $scope.less_discount = less_discount;
                    } else {
                        var less_wht = less_discount;
                        $scope.less_witholding = 0.00;
                        $scope.less_discount = less_discount;
                    }
                } else {

                    if ($scope.is_wht == 'Added') {
                        var less_wht = basic_rental - ((toNumber($scope.wht) / 100) * basic_rental);

                        $scope.less_witholding = ($scope.wht / 100) * basic_rental;
                        $scope.less_discount = basic_rental;
                    } else {
                        var less_wht = basic_rental;
                        $scope.less_witholding = 0.00;
                        $scope.less_discount = basic_rental;
                    }
                }
                $scope.added_vat = "0.00";
                $scope.total_basicRental_manual = less_wht;
            }
        });
    }

    $scope.get_invforCreditMemo = function(trade_name, tenancy_type) {

        var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };
        $scope.isLoading = true;
        $http.post('../leasing_transaction/get_tenantDetails/', objData).success(function(result) {
            $scope.tin = result[0].tin;
            $scope.contract_no = result[0].contract_no;
            $scope.rental_type = result[0].rental_type;
            $scope.tenant_id = result[0].tenant_id;


            $http.post('../leasing_transaction/get_dataForCreditMemo/' + $scope.tenant_id).success(function(result) {
                $scope.basicData = result;
                $scope.isLoading = false;
            });

        });



    }


    $scope.admin_TL = function(tenant_id) {

        $http.post('../leasing_transaction/admin_tenantDetails/' + tenant_id).success(function(result) {
            $scope.trade_name = result[0].trade_name;
            $scope.tenant_id = result[0].tenant_id;
            $scope.tin = result[0].tin;
            $scope.contract_no = result[0].contract_no;
            $scope.rental_type = result[0].rental_type;
            $scope.corporate_name = result[0].corporate_name;
            $scope.address = result[0].address;

            var url = "../leasing_transaction/get_subsidiaryLedger/" + $scope.tenant_id;
            $rootScope.$emit("CallTablelistMethod", url);
        });

    }





    $scope.subsidiary_ledger = function(trade_name, tenancy_type) {
        var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };
        $http.post('../leasing_transaction/get_tenantDetails/', objData).success(function(result) {
            $scope.tenant_id = result[0].tenant_id;
            $scope.tin = result[0].tin;
            $scope.contract_no = result[0].contract_no;
            $scope.rental_type = result[0].rental_type;
            $scope.corporate_name = result[0].corporate_name;
            $scope.address = result[0].address;
            var url = "../leasing_transaction/get_subsidiaryLedger/" + $scope.tenant_id;
            $rootScope.$emit("CallTablelistMethod", url);
        });
    }


    $scope.tenant_ledger = function(trade_name, tenancy_type) {

        $http.post('../leasing_transaction/get_tenantDetails/' + trade_name + '/' + tenancy_type).success(function(result) {
            $scope.tenant_id = result[0].tenant_id;
            $scope.tin = result[0].tin;
            $scope.contract_no = result[0].contract_no;
            $scope.rental_type = result[0].rental_type;
            $scope.corporate_name = result[0].corporate_name;
            $scope.address = result[0].address;

            var url = "../leasing_transaction/get_tenantLedger/" + $scope.tenant_id;
            $rootScope.$emit("CallTablelistMethod", url);
        });
    }



    $scope.tenant_penalties = function(trade_name, tenancy_type) {

        var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };
        $http.post('../leasing_transaction/get_tenantDetails/', objData).success(function(result) {
            $scope.tenant_id = result[0].tenant_id;
            $scope.tin = result[0].tin;
            $scope.contract_no = result[0].contract_no;
            $scope.rental_type = result[0].rental_type;
            $scope.corporate_name = result[0].corporate_name;
            $scope.address = result[0].address;

            var url = "../leasing_transaction/tenant_penalties/" + $scope.tenant_id;
            $rootScope.$emit("CallTablelistMethod", url);
        });
    }


    $scope.apply_CreditMemo = function(id, description, amount) {
        var res = description.split("-");
        apply_CreditMemo_modal(res[0], id, amount);
        $scope.max_credit = amount;
        $scope.ledger_id = id;
    }


    $scope.apply_DebitMemo = function(id, description, amount) {
        var res = description.split("-");
        apply_DebitMemo_modal(res[0], id, amount);
        $scope.ledger_id = id;
    }


    $scope.other_creditMemo = function(url, user_type) {
        var ledger_id = document.getElementById('ledger_id').value;
        url = url + '/' + ledger_id;
        if (user_type == 'Administrator' || user_type == 'Store Manager') {
            window.location = url;
        } else {
            creditMemo_managerModal('modal_creditMemo');
            $scope.managers_key(url);
        }
    }


    $scope.basic_creditMemo = function(url, user_type) {
        var ledger_id = document.getElementById('ledger_id').value;
        url = url + '/' + ledger_id;
        if (user_type == 'Administrator' || user_type == 'Store Manager') {
            window.location = url;
        } else {
            creditMemo_managerModal('basic_creditMemo_modal');
            $scope.managers_key(url);
        }

    }





    $scope.get_invoiceHistory = function(tenant_id, tenancy_type) {
        $http.post('../leasing_transaction/get_tenantDetails/' + tenant_id + '/' + tenancy_type).success(function(result) {
            $scope.trade_name = result[0].trade_name;
            $scope.tin = result[0].tin;
            $scope.contract_no = result[0].contract_no;
            $scope.rental_type = result[0].rental_type;
            $scope.address = result[0].address;
        });

        var url_payment = "../leasing_reports/get_invoiceHistory/" + tenant_id;
        $rootScope.$emit("CallTablelistMethod", url_payment);

    }


    $scope.get_paymentScheme = function(trade_name, tenancy_type) {
        var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };
        $http.post('../leasing_transaction/get_tenantDetails/', objData).success(function(result) {
            console.log(result);
            $scope.trade_name = result[0].trade_name;
            $scope.tenant_id = result[0].tenant_id;
            $scope.tin = result[0].tin;
            $scope.contract_no = result[0].contract_no;
            $scope.rental_type = result[0].rental_type;
            $scope.address = result[0].address;


            var url_payment = "../leasing_reports/get_paymentScheme/" + result[0].tenant_id;
            $rootScope.$emit("CallTablelistMethod", url_payment);
        });
    }

    $scope.get_paymentProofList = function(trade_name, tenancy_type) {
        var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };
        $http.post('../leasing_transaction/get_tenantDetails/', objData).success(function(result) {
            // console.log(result);
            $scope.trade_name = result[0].trade_name;
            $scope.tenant_id = result[0].tenant_id;
            $scope.tin = result[0].tin;
            $scope.contract_no = result[0].contract_no;
            $scope.tenant_type = result[0].tenant_type;
            $scope.address = result[0].address;
        });
    }


    $scope.details = function(url) {


        $scope.dataList = [];
        $http.post(url).success(function(result) {
            $scope.dataList = result;
            // console.log(result);
        });
    }


    $scope.get_invoiceForUpdate = function(url) {

        $http.post(url).success(function(result) {
            $scope.doc_no = result[0].doc_no;
            $scope.tenant_id = result[0].tenant_id;
            $scope.contract_no = result[0].contract_no;
            $scope.trade_name = result[0].trade_name;
            $scope.rental_type = result[0].rental_type;
            $scope.posting_date = result[0].posting_date;
            $scope.transaction_date = result[0].transaction_date;
            $scope.due_date = result[0].due_date;
            $scope.total = result[0].total;
            $scope.tenancy_type = result[0].tenancy_type;
            $scope.charges_type = result[0].charges_type;
            // console.log(result);
        });
    }


    $scope.calculate_otherCharges = function(unit_price, total_unit, uom) {
        if (uom != 'Inputted') {
            var total = parseFloat(unit_price) * parseFloat(total_unit);
            $scope.other_amount = total;
        } else {
            $scope.other_amount = total_unit;
        };


    }


    $scope.calculate_consumed = function(prev_reading, curr_reading, unit_price) {
        var total = parseFloat(curr_reading) - parseFloat(prev_reading);
        $scope.total_unit = total;
        $scope.other_amount = total * parseFloat(unit_price);

    }


    $scope.compute_actual_amount = function(description, unit_price, total_unit, actual_amount, uom) {


        if (uom != 'Inputted') {
            var t_unit = document.getElementById(total_unit).value;
            var u_price = document.getElementById(unit_price).value;
            t_unit = t_unit.replace(",", "");
            u_price = u_price.replace(",", "");
            var total = parseFloat(u_price) * parseFloat(t_unit);
            if (description == 'Bio Augmentation') {
                total = Math.ceil(total / 100) * 100;
            }
            document.getElementById(actual_amount).value = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

        } else {
            var t_unit = document.getElementById(total_unit).value;
            total = parseFloat(t_unit.replace(",", ""));
            document.getElementById(actual_amount).value = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    }

    $scope.less30_compute_actual_amount = function(description, unit_price, total_unit, actual_amount, uom, num_of_days, month_days) {

        var n_days = Math.abs(parseFloat(num_of_days));
        var m_days = parseFloat(month_days);


        if (uom != 'Inputted') {
            var t_unit = document.getElementById(total_unit).value;
            var u_price = document.getElementById(unit_price).value;
            t_unit = t_unit.replace(",", "");
            u_price = u_price.replace(",", "");
            var total = parseFloat(u_price) * parseFloat(t_unit);
            if (uom == 'Per Square Meter' || uom == 'Fixed Amount' || uom == 'Per Feet') {
                total = (total / m_days) * n_days;
            };
            if (description == 'Bio Augmentation') {
                total = Math.ceil(total / 100) * 100;
            }
            document.getElementById(actual_amount).value = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        } else {
            var t_unit = document.getElementById(total_unit).value;
            total = parseFloat(t_unit.replace(",", ""));
            document.getElementById(actual_amount).value = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    }

    $scope.compute_currentReading = function(unit_price, prev_reading, curr_reading, actual_amount) {
        var u_price = document.getElementById(unit_price).value;
        u_price = parseFloat(u_price.replace(",", ""));

        var p_reading = document.getElementById(prev_reading).value;

        if (p_reading == null) {
            p_reading = 0;
        } else {
            p_reading = $scope.toNumber(p_reading);
        }

        var c_reading = document.getElementById(curr_reading).value;

        c_reading = $scope.toNumber(c_reading);

        var total = (c_reading - p_reading) * u_price;
        document.getElementById(actual_amount).value = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }


    $scope.less30_compute_currentReading = function(unit_price, prev_reading, curr_reading, actual_amount, num_of_days) {

        var n_days = parseFloat(num_of_days);
        var m_days = moment().daysInMonth()
        var u_price = document.getElementById(unit_price).value;
        u_price = parseFloat(u_price.replace(",", ""));

        var p_reading = document.getElementById(prev_reading).value;

        if (p_reading == null) {
            p_reading = 0;
        } else {
            p_reading = $scope.toNumber(p_reading);
        }



        var c_reading = document.getElementById(curr_reading).value;
        c_reading = c_reading = $scope.toNumber(c_reading);

        var total = (c_reading - p_reading) * u_price;
        //total = (total / m_days) * n_days;

        document.getElementById(actual_amount).value = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }


    $scope.clear_totalUnit_actualAmount = function() {
        $scope.total_unit = "";
        $scope.other_amount = "";
    }



    // ============== Update Receipt Number every second ================ //

    // $scope.get_receiptNo = function()
    // {
    //     $interval(function(){
    //         $http.post('../leasing_transaction/get_paymentReceipt/').success(function(result){
    //             $scope.receipt_no= result.replace(/"/g, "");
    //             // console.log(result);
    //         });
    //     }.bind(this), 1000);
    // }

    $scope.populat_tenderTypeDesc = function(typeCode) {

        $scope.tender_typeDesc = "";
        switch (typeCode) {
            case "1":
                $scope.tender_typeDesc = 'Cash';
                break;
            case "2":
                $scope.tender_typeDesc = 'Check';
                break;
            case "3":
                $scope.tender_typeDesc = 'Bank to Bank';
                break;
            case "80":
                $scope.tender_typeDesc = 'JV payment - Business Unit';
                break;
            case "81":
                $scope.tender_typeDesc = 'JV payment - Subsidiary';
                break;
        }
    }


    $scope.is_cash = function(param, store_id) {

        if ((param == 1 || param == 2) && store_id != undefined) {
            $http.post('../ctrl_cfs/get_mycashbank/' + store_id).success(function(result) {
                document.getElementById('_cash_bank').innerHTML = "";
                document.getElementById('_cash_bank').innerHTML = "<div class='row'>" +
                    "<div class='form-group'>" +
                    "<label for='bank' class='col-md-5 control-label text-right'><i class='fa fa-asterisk'></i>Bank Code</label>" +
                    "<div class='col-md-7'>" +
                    "<input type = 'text' readonly value = '" + result[0].bank_code + "' name = 'bank_code' class = 'form-control' />" +
                    "</div>" +
                    "</div>" +
                    "</div>" +

                    "<div class='row'>" +
                    "<div class='form-group'>" +
                    "<label for='bank' class='col-md-5 control-label text-right'><i class='fa fa-asterisk'></i>Bank Name</label>" +
                    "<div class='col-md-7'>" +
                    "<input type = 'text' readonly value = '" + result[0].bank_name + "' name = 'bank' class = 'form-control' />" +
                    "</div>" +
                    "</div>" +
                    "</div>";

            });

        } else {
            document.getElementById('_cash_bank').innerHTML = "";
            angular.element(document.getElementById('_cash_bank')).append($compile("<div class='row'" +
                "<div class='form-group'>" +
                "<label for='bank' class='col-md-5 control-label text-right'><i class='fa fa-asterisk'></i>Bank Code</label>" +
                "<div class='col-md-7'>" +
                "<select class='form-control' required id = 'payment_bank_code' ng-model = 'code.bank_code' name = 'bank_code' ng-change = 'populate_bankCode(code.bank_code)'>" +
                "<option ng-repeat = 'code in storeBankCode'>{{code.bank_code}}</option>" +
                "</select>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "<div class='row'>" +
                "<div class='form-group'>" +
                "<label for='bank' class='col-md-5 control-label text-right'><i class='fa fa-asterisk'></i>Bank Name</label>" +
                "<div class='col-md-7'>" +
                "<select id = 'payment_bank_name' ng-model = 'data.bank_name'  required name = 'bank' class='form-control'>" +
                "<option ng-repeat = 'data in itemList'>{{data.bank_name}}</option>" +
                "</select>" +
                "</div>" +
                "</div>" +
                "</div>")($scope));

            // $http.post('../leasing_transaction/get_storebankCode/' + $scope.tenant_id).success(function(codes) {
            //     console.log(codes);
            //     $scope.storeBankCode = codes;
            // });
        }
    }




    $scope.export_subsidiaryLedger = function(tenant_id) {

        $http.post('../leasing_transaction/export_subsidiaryLedger/' + tenant_id).success(function(result) {
            // console.log(result);
        });
    }

    $scope.get_rentReceivable = function() {
        var url = "../leasing_reports/populate_rentReceivable/";
        $rootScope.$emit("CallTablelistMethod", url);
    }


    $scope.get_accountReceivable = function() {

        var url = "../leasing_reports/populate_accountReceivable/";
        $rootScope.$emit("CallTablelistMethod", url);

    }

    $scope.generate_expiryDate = function(rent_period, opening_date) {
        var rent_period = document.getElementById(rent_period).value;

        if (rent_period != "Open Contract") {
            var res = rent_period.split(" ");
            var opening_date = moment(opening_date);
            var expiry_date = opening_date.add(res[0], res[1]);
            $scope.expiry_date = moment(expiry_date).format('YYYY-MM-DD');
        } else {
            $scope.expiry_date = rent_period;
        }
    }


    $scope.calculate_postingDate = function(posting_date) {
        var suggested_dueDate = moment(posting_date).add(8, 'days');
        $scope.due_date = moment(suggested_dueDate).format('YYYY-MM-DD');

        $scope.min_dueDate = moment(moment(posting_date).add(7, 'days')).format('YYYY-MM-DD');
    }

    // ========================================== KING ARTHURS REVISION ADDED ======================================== //

    // DOCUMENT OFFICER FUNCTIONS
    $scope.tenant_type_change = function() {
        if ($('#tenant_types').val() == 'AGC-Subsidiary' || $('#tenant_types').val() == 'Private Entities') {
            $scope.vat_percentage = "12.00";
            $scope.wht_percentage = "5.00";
        } else {
            $scope.vat_percentage = "";
            $scope.wht_percentage = "";
        }
    }

    $scope.generate_expiryDate = function(rent_period, opening_date) {
        var rent_period = document.getElementById(rent_period).value;

        if (rent_period != "Open Contract") {
            var res = rent_period.split(" ");
            var opening_date = moment(opening_date);
            var expiry_date = opening_date.add(res[0], res[1]);
            $scope.expiry_date = moment(expiry_date).format('YYYY-MM-DD');
        } else {
            $scope.expiry_date = rent_period;
        }
    }

    $scope.rent_period_change = function() {
        $('#opening_date').val("");
        $('#expiry_date').val("");
    }

    $scope.rent_period_change2 = function() {
        $('#opening_date').val("");
        $('#expiry_date').val("");
    }

    $scope.generate_expiryDate2 = function(rent_period, opening_date) {
            $scope.data = { expiry_date: null };
            var rent_period = document.getElementById(rent_period).value;

            if (rent_period != "Open Contract") {
                var res = rent_period.split(" ");

                var opening_date = moment(opening_date);
                var expiry_date = opening_date.add(res[0], res[1]);
                // $scope.data.expiry_date = moment(expiry_date).format('YYYY-MM-DD');
                $('#expiry_date').val(moment(expiry_date).format('YYYY-MM-DD'));
            } else {
                // $scope.data.expiry_date = rent_period;
                $('#expiry_date').val(rent_period);
            }
        }
        // DOCUMENT OFFICER FUNCTIONS

    // REPORT FUNCTIONS
    $scope.tenantInfo3 = function(trade_name, tenancy_type) {
        // console.log(trade_name + " " + tenancy_type);
        var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };

        $http.post('../leasing_transaction/get_tenantDetails/', objData).success(function(result) {
            $scope.tenant_id = result[0].tenant_id;
            $scope.tin = result[0].tin;
            $scope.contract_no = result[0].contract_no;
            $scope.rental_type = result[0].rental_type;
            $scope.corporate_name = result[0].corporate_name;
            $scope.address = result[0].address;
        });
    }

    $scope.ledger_tenant = function(trade_name, tenancy_type) {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        $http.post('../leasing_transaction/get_forwarded_balance/' + $scope.tenant_id + '/' + start_date).success(function(result) {
            if (result[0].forwardedBalance == null || result[0].forwardedBalance == '') {
                $scope.forwardedBalance = '0.00';
            } else {
                $scope.forwardedBalance = result[0].forwardedBalance;
            }

            console.log($scope.forwardedBalance);
        });

        var url = "../leasing_transaction/get_ledgerTenant/" + $scope.tenant_id + "/" + start_date + "/" + end_date;
        $rootScope.$emit("CallTablelistMethod", url);
    }

    $scope.ledger_tenant1 = function(trade_name, tenancy_type) {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var tenant_id = $('#tenant_id').val();
        var url = $base_url + 'getForwardedBalance/' + tenant_id + '/' + start_date;

        $http.post(url).success(function(result) {
            if (result[0].forwardedBalance == null || result[0].forwardedBalance == '') {
                $scope.forwardedBalance = '0.00';
            } else {
                $scope.forwardedBalance = result[0].forwardedBalance;
            }

            console.log($scope.forwardedBalance);
        });

        var url = $base_url + "get_ledgerTenant/" + tenant_id + "/" + start_date + "/" + end_date;
        $rootScope.$emit("CallTablelistMethod", url);
    }

    $scope.getReading = function(trade_name, tenancy_type) {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var tenant_id = $('#tenant_id').val();

        var url = `${$base_url}getReading/${tenant_id}/${start_date}/${end_date}`;
        $rootScope.$emit("CallTablelistMethod", url);
    }

    $scope.payment_details = function(ref_no, doc_no, posting_date, credit) {
        var tenant_id = '';

        if ($('#portalLogIn').val() == 1) {
            tenant_id = $('#tenant_id').val();
        } else {
            tenant_id = $scope.tenant_id
        }

        $scope.doc_no = doc_no;
        $scope.posting_date = posting_date;

        $http.post(`${$base_url}get_payment_details/${ref_no}/${tenant_id}`).success(function(result) {
            $scope.paymentDetails = result;

            if (credit === '0.00') {
                $scope.grand_total = '0.00';
            } else {
                $scope.grand_total = credit;
            }
        });
    }

    $scope.Pdetails = function(data) {
        $scope.d_amount = data.inv_amount;
        $scope.net_total = data.debit;

        if (data.adj_amount == null || data.adj_amount == '0.00') {
            $scope.adj_amount = '0.00';
        } else {
            $scope.adj_amount = data.adj_amount
        }

        if (data.flag == 'Other Charges') {
            $scope.other_charges = data.inv_amount;
            $scope.basic_rent = '0.00';
        } else {
            $scope.basic_rent = data.inv_amount;
            $scope.other_charges = '0.00';
        }
    }

    $scope.exportExcel = function() {
        var tenant_id = $scope.tenant_id;
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        window.open('../leasing_transaction/export_tenantLedgerRR/' + tenant_id + '/' + start_date + '/' + end_date);
    }

    $scope.showAll = function() {
        if ($scope.searchBy == 'Show All') {
            console.log($scope.searchBy);
            $('#trade_name_button').prop('disabled', false);
        } else {
            $('#trade_name_button').prop('disabled', true);
        }
    }

    $scope.adjustment_ledger = function(trade_name, tenancy_type) {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };

        $http.post('../leasing_transaction/get_tenantDetails/', objData).success(function(result) {
            $scope.tenant_id = result[0].tenant_id;
            $scope.corporate_name = result[0].corporate_name;

            // var url = "../leasing_transaction/adjustmentLedger/" + $scope.tenant_id + "/" + start_date + "/" + end_date;
            var url = "../leasing_transaction/adjustmentLedger/" + $scope.tenant_id;
            $rootScope.$emit("CallTablelistMethod", url);
        });
    }

    $scope.printlog = function(data) {
        console.log(data);

        $.ajax({
            type: 'POST',
            url: '../leasing_reports/save_adj_print_log',
            data: { document: data.adj_document, tenant_id: data.tenant_id, doc_type: data.doc_type, adj_no: data.adj_no },
            success: function(data) {
                data = JSON.parse(data);

                if (data['msg'] == 'Success') {
                    var filename = data['file_name'];
                    window.open(filename);
                    console.log("Success");
                } else if (data['msg'] == 'Error') {
                    generate('error', 'Problem Showing Document.');
                } else if (data['msg'] == 'DB_error') {
                    generate('error', 'Error on showing document, something went wrong.');
                }
            }
        });

        // window.open('/AGC-PMS/assets/pdf/' + data.adj_document);
    }

    $scope.fetchSuppDoc = function(data) {
        var adj_no = data.adj_no;

        $.ajax({
            type: 'POST',
            url: '../leasing_reports/fetch_adj_supp_doc',
            data: { adj_no: data.adj_no },
            success: function(result) {
                $scope.image = result;
                $scope.$apply();

                console.log(result);
            }
        });
    }

    $scope.imageClose = function() {
            $scope.image = '';
        }
        // REPORT FUNCTIONS

    $scope.radioChange = function() {
        if ($scope.documentTypeRadio == 'invoice') {
            console.log("Invoice");
        } else {
            console.log("Payment");
        }
    }

    // ADJUSTMENT FUNCTIONS

    // BASIC RENT ADJUSTMENT FUNCTIONS
    $scope.data = [];

    $scope.tenantInfo = function(trade_name, tenancy_type) {
        if (trade_name == null && tenancy_type == null) {
            console.log("data empty");
        } else {
            var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };

            $http.post('../leasing_transaction/get_tenantDetails/', objData).success(function(result) {
                $scope.tenant_id = result[0].tenant_id;
                $scope.tin = result[0].tin;
                $scope.contract_no = result[0].contract_no;
                $scope.rental_type = result[0].rental_type;
                $scope.corporate_name = result[0].corporate_name;
                $scope.address = result[0].address;
            });
        }
    }

    $scope.adjustments = function(trade_name, tenancy_type) {
        if ($scope.searchBy == 'Show All') {
            var searchType = $scope.searchBy;
        } else if ($scope.searchBy == 'Doc No') {
            var searchType = $('#doc_no').val();
        } else if ($scope.searchBy == 'Ref No') {
            var searchType = $('#ref_no').val();
        } else {
            var searchType = $('#posting_date').val();
        }

        var url = "../leasing_transaction/get_ledgerAdjustment/" + $scope.tenant_id + "/" + searchType + "/" + $scope.searchBy;

        $http.post(url).success(function(result) {
            if (result == null || result == '' || result == [{}]) {
                if ($scope.searchBy == 'Show All') {
                    pop.alert('<strong>No Invoices Found.</strong>');
                } else if ($scope.searchBy == 'Doc No') {
                    pop.alert('<strong>Document Number not found.</strong>');
                } else if ($scope.searchBy == 'Ref No') {
                    pop.alert('<strong>Reference Number not found.</strong>');
                } else {
                    pop.alert('<strong>No Invoices within the date.</strong>');
                }
            }
        });

        $rootScope.$emit("CallTablelistMethod", url);
    }

    $scope.basicRent_adj = function(data) {
        $http.post('../leasing_transaction/get_adjNo').success(function(result) {
            $('#adj_code1').val(result.adj_no)
        });

        $scope.paidAmount1 = data.credit;
        $scope.amount = data.debit;
        $scope.rr_adj = 'Rent Recievables';

        $('#BRDoctag').val(data.flag);
        $('#adj_doc_no1').val(data.doc_no);

        $scope.DOCposting_date1 = data.posting_date;
    }

    $scope.reasonChange = function() {
        if ($scope.BRReason == 'VAT Output Adjustment') {
            $scope.BRdescription = "VAT Output";
            $scope.data.BRAmount2 = '';
            $scope.BRData3 = true;
            $scope.BRData2 = true;
            $('#BRCharges').prop('required', false);
            $('#BRAmount').prop('required', false);
            $('#BRdescription').prop('required', true);
            $('#BRAmount2').prop('required', true);
            $scope.BRData = [{}];
        } else if ($scope.BRReason == 'Creditable WHT Recievable Adjustment') {
            $scope.BRdescription = "Creditable WHT Receivable";
            $scope.data.BRAmount2 = '';
            $scope.BRData3 = true;
            $scope.BRData2 = true;
            $('#BRCharges').prop('required', false);
            $('#BRAmount').prop('required', false);
            $('#BRdescription').prop('required', true);
            $('#BRAmount2').prop('required', true);
            $scope.BRData = [{}];
        } else if ($scope.BRReason == 'Rent Income Adjustment') {
            $scope.BRdescription = "Rent Income";
            $scope.data.BRAmount2 = '';
            $scope.BRData3 = true;
            $scope.BRData2 = true;
            $('#BRCharges').prop('required', false);
            $('#BRAmount').prop('required', false);
            $('#BRdescription').prop('required', true);
            $('#BRAmount2').prop('required', true);
            $scope.BRData = [{}];
        } else if ($scope.BRReason == undefined) {
            $scope.BRdescription = '';
            $scope.data.BRAmount2 = '';
            $scope.BRData2 = false;
        } else {
            $scope.BRdescription = '';
            $scope.data.BRAmount2 = '';
            $scope.BRData3 = false;
            $scope.BRData2 = true;
            $('#BRCharges').prop('required', true);
            $('#BRAmount').prop('required', true);
            $('#BRdescription').prop('required', false);
            $('#BRAmount2').prop('required', false);
        }
    }

    $scope.BRSubmit = function(e) {
        e.preventDefault();

        pop.confirm('Are you sure you want to continue to submit this adjustment?', (res) => {
            if (!res) return;

            var formData = new FormData(e.target);
            var BRData = JSON.parse(angular.toJson($scope.BRData));
            formData = convertModelToFormData(BRData, formData, 'BRData');

            // var formData    = $(e.target).serializeObject();
            // formData.BRData = $scope.BRData;

            console.log(formData);

            $.ajax({
                type: 'POST',
                url: 'save_basicRentADJ',
                data: formData,
                enctype: 'multipart/form-data',
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#spinner_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                success: function(data) {

                    $('#spinner_modal').modal('toggle');

                    if (data.type == 'error_file') {
                        generate('error', data.msg);
                    } else {
                        data = JSON.parse(data);

                        if (data['msg'] == 'Success1') {
                            notify('success', 'Successfully Adjusted.');
                        } else if (data['msg'] == 'Success') {
                            var filename = data['file_name'];
                            window.open(filename);
                            notify('success', 'Successfully Adjusted.');
                        } else if (data['msg'] == 'Error') {
                            generate('error', 'No Data to save.');
                        } else if (data['msg'] == 'DB_error') {
                            generate('error', 'Error on saving data, Something went wrong.');
                        }
                    }
                }
            });
        });
    }

    $scope.RRAmount = function() {
        let total = 0;

        if ($scope.BRReason == 'Others') {
            $scope.BRData.forEach(function(data) {
                total += parseNumber(data.BRAmount);
            });
        } else {
            total += parseNumber($scope.data.BRAmount2);
        }

        console.log(total);

        return -1 * total;
    }

    $scope.BRDataIsInvalid = function() {
        var balance = parseNumber($scope.amount.replace(/\,/g, '')); // = debit
        var paidAmount = Math.abs(parseNumber($scope.paidAmount1)); // == credit
        var BRTotal = $scope.RRAmount(); // === total
        var newInvoiceAmount = 0;

        newInvoiceAmount = balance + BRTotal;

        return newInvoiceAmount < paidAmount;
    }

    $scope.BRData = [];
    $scope.amount = '';

    $scope.newBRAmount = function() {
        var balance = parseNumber($scope.amount.replace(/\,/g, '')); // = debit
        var paidAmount = Math.abs(parseNumber($scope.paidAmount1)); // == credit
        var BRTotal = $scope.RRAmount(); // === total
        var newInvoiceAmount = 0;

        newInvoiceAmount = balance + BRTotal;

        if (newInvoiceAmount < paidAmount) {
            $('#BRnewAmount').css("border", "1px solid red");
            //$('#reviewButtonBR').prop('disabled', true);
            $scope.errorDisplay1 = "New Invoice Amount can't be less than to " + paidAmount;
            // $('#BRSubmit').prop('disabled', true);
        } else {
            $('#BRnewAmount').css("border", "1px solid #ced4da");
            $scope.errorDisplay1 = "";
            // $('#reviewButtonBR').prop('disabled', false);
            //$('#BRSubmit').prop('disabled', false);
        }

        return parseNumber(newInvoiceAmount);
    }

    $scope.totalDebit = function() {
        var debitTotal = 0;
        var rrtotal = $scope.RRAmount();
        var total = 0;

        if ($scope.BRReason == 'VAT Output Adjustment' || $scope.BRReason == 'Creditable WHT Recievable Adjustment' || $scope.BRReason == 'Rent Income Adjustment') {
            if ($scope.data.BRAmount2 > 0) {
                debitTotal += parseNumber($scope.data.BRAmount2);
            }
        } else {
            $scope.BRData.forEach(function(data) {
                if (data.BRAmount > 0) {
                    debitTotal += parseNumber(data.BRAmount);
                }
            });
        }

        if (rrtotal > 0) {
            total = debitTotal + rrtotal;
        } else {
            total = debitTotal;
        }

        return total;
    }

    $scope.totalCredit = function() {
        var creditTotal = 0;
        var rrtotal = $scope.RRAmount();
        var total = 0;

        if ($scope.BRReason == 'VAT Output Adjustment' || $scope.BRReason == 'Creditable WHT Recievable Adjustment' || $scope.BRReason == 'Rent Income Adjustment') {
            if ($scope.data.BRAmount2 < 0) {
                creditTotal += parseNumber($scope.data.BRAmount2);
            }
        } else {
            $scope.BRData.forEach(function(data) {
                if (data.BRAmount < 0) {
                    creditTotal += parseNumber(data.BRAmount);
                }
            });
        }

        if (rrtotal < 0) {
            total = creditTotal + rrtotal;
        } else {
            total = creditTotal;
        }

        return total;
    }

    $scope.BRClose = function() {
        $scope.BRnewAmount = '';
        $scope.BRData = [{}];
        $scope.BRmemoType = '';
        $scope.BRReason = '';
        $scope.BRdescription = "";
        $scope.data.BRAmount2 = '';
        $scope.BRData2 = false;
    }

    // OTHER CHARGES ADJUSTMENT FUNCTIONS
    $scope.OCSubmit = function(e) {
        e.preventDefault();

        pop.confirm('Are you sure you want to continue to submit this adjustment?', (res) => {
            if (!res) return;

            // var formData = $(e.target).serializeObject();
            // formData.OCData = $scope.OCData;

            var formData = new FormData(e.target);
            var OCData = JSON.parse(angular.toJson($scope.OCData));
            formData = convertModelToFormData(OCData, formData, 'OCData');

            console.log(formData);

            $.ajax({
                type: 'POST',
                url: 'save_otherChargesADJ',
                data: formData,
                enctype: 'multipart/form-data',
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#spinner_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                success: function(data) {
                    $('#spinner_modal').modal('toggle');

                    console.log(data.type + " " + data.msg);

                    if (data.type == 'error_file') {
                        generate('error', data.msg);
                    } else {
                        data = JSON.parse(data);

                        if (data['msg'] == 'Success1') {
                            notify('success', 'Successfully Adjusted.');
                        } else if (data['msg'] == 'Success') {
                            var filename = data['file_name'];
                            window.open(filename);
                            notify('success', 'Successfully Adjusted.');
                        } else if (data['msg'] == 'Error') {
                            generate('error', 'No Data to save.');
                        } else if (data['msg'] == 'DB_error') {
                            generate('error', 'Error on saving data, Something went wrong.');
                        }
                    }
                }
            });
        });
    }

    $scope.OCAmountADJ = function() {
        let total = 0;

        $scope.OCData.forEach(function(data) {
            total += parseNumber(data.OCAmount);
        });

        console.log(total);

        return -1 * total;
    }

    $scope.otherCharges_adj = function(data) {
        $http.post('../leasing_transaction/get_adjNo').success(function(result) {
            $('#adj_code').val(result.adj_no)
        });

        $http.post('../leasing_transaction/getGLName/' + data.doc_no).success(function(result) {
            var gl_accountID = result.gl_accountID;

            if (gl_accountID == '29') {
                $scope.oc_adj = 'A/R Non Trade Internal';
            } else if (gl_accountID == '22') {
                $scope.oc_adj = 'A/R Non Trade External';
            }
        });

        $scope.amountOC = data.debit;
        $scope.OCpaidAmount = data.credit;

        $('#OCdoc_tag').val(data.flag);
        $('#adj_doc_no').val(data.doc_no);
        $('#DOCposting_date').val(data.posting_date);
    }

    $scope.OCDataTotal = function() {
        let total = 0;

        $scope.OCData.forEach(function(data) {
            total += data.OCAmount;
        });

        return total;
    }

    $scope.OCDataIsInvalid = function() {
        var balance = parseNumber($scope.amount.replace(/\,/g, '')); // = debit
        var paidAmount = Math.abs(parseNumber($scope.paidAmount1)); // == credit
        var BRTotal = $scope.RRAmount(); // === total
        var newInvoiceAmount = 0;

        newInvoiceAmount = balance + BRTotal;

        return newInvoiceAmount < paidAmount;
    }

    $scope.OCData = [];
    $scope.amountOC = '';

    $scope.newOCAmount = function() {
        var balance = parseNumber($scope.amountOC.replace(/\,/g, '')); // = debit
        var paidAmount = Math.abs(parseNumber($scope.OCpaidAmount)); // == credit
        var OCTotal = $scope.OCAmountADJ(); // === total
        var newInvoiceAmount = 0;

        newInvoiceAmount = balance + OCTotal;

        console.log(OCTotal);

        $('#OCSubmitBtn').prop('disabled', true);

        if (newInvoiceAmount < paidAmount) {
            $('#newAmount').css("border", "1px solid red");
            $scope.errorDisplay = "New Invoice Amount can't be less than to " + paidAmount;
            // $('#reviewButtonOC').prop('disabled', true);
            // $('#OCSubmitBtn').prop('disabled', true);
        } else {
            $('#newAmount').css("border", "1px solid #ced4da");
            $scope.errorDisplay = "";
            // $('#reviewButtonOC').prop('disabled', false);
            // $('#OCSubmitBtn').prop('disabled', false);
        }

        // if (OCTotal == '0' ) 
        // {
        //     $('#OCSubmitBtn').prop('disabled', true);
        //     $('#reviewButtonOC').prop('disabled', true);
        // }

        return parseNumber(newInvoiceAmount);
    }

    $scope.totalDebitOC = function() {
        var debitTotal = 0;
        var OCDtotal = $scope.OCAmountADJ();
        var total = 0;

        $scope.OCData.forEach(function(data) {
            if (data.OCAmount > 0) {
                debitTotal += parseNumber(data.OCAmount);
            }
        });

        if (OCDtotal > 0) {
            total = debitTotal + OCDtotal;
        } else {
            total = debitTotal;
        }

        return total;
    }

    $scope.totalCreditOC = function() {
        var creditTotal = 0;
        var OCCtotal = $scope.OCAmountADJ();
        var total = 0;

        $scope.OCData.forEach(function(data) {
            if (data.OCAmount < 0) {
                creditTotal += parseNumber(data.OCAmount);
            }
        });

        if (OCCtotal < 0) {
            total = creditTotal + OCCtotal;
        } else {
            total = creditTotal;
        }

        return total;
    }

    $scope.clearData = function() {
        $scope.newAmount = '';
        $scope.doc_type1 = '';
        $scope.OCdoc_tag = '';
        $scope.adj_code = '';
        $scope.adj_doc_no = '';
        $scope.DOCposting_date = '';
        $scope.OCData = [{}];
        $scope.memoType = '';
        $scope.OCReason = '';
    }


    $scope.review = function() {
        var arr = [];
        var doc_no = $('#adj_doc_no').val();
        var amount = $scope.OCAmountADJ();

        $scope.RDoc_tag = $('#OCdoc_tag').val();

        console.log($scope.RDoc_tag);

        Object.keys($scope.OCData).forEach(function(key) {
            arr.push($scope.OCData[key]);
        });

        $scope.reviewData = arr;

        $http.post('../leasing_transaction/getGLName/' + doc_no).success(function(result) {
            var gl_accountID = result.gl_accountID;

            if (gl_accountID == '29') {
                $scope.Rcharges = 'A/R Non Trade Internal';
            } else if (gl_accountID == '22') {
                $scope.Rcharges = 'A/R  Non Trade External';
            }
        });

        if (amount > 0) {
            $scope.OCTotalDebit = amount;
            $scope.OCTotalCredit = '';
        } else {
            $scope.OCTotalDebit = '';
            $scope.OCTotalCredit = amount;
        }

        $('#adjTotal').val($('#newAmount').val());
    }

    $scope.review2 = function() {
        var arr = [];
        var doc_no = $('#adj_doc_no').val();
        var amount = $scope.RRAmount();

        $scope.RDoc_tag = $('#BRDoctag').val();

        if ($scope.BRReason == 'VAT Output Adjustment' || $scope.BRReason == 'Creditable WHT Recievable Adjustment' || $scope.BRReason == 'Rent Income Adjustment') {
            arr = [{ BRCharges: $scope.BRdescription, BRAmount: $scope.data.BRAmount2 }];
        } else {
            Object.keys($scope.BRData).forEach(function(key) {
                arr.push($scope.BRData[key]);
            });
        }

        console.log(arr);

        $scope.reviewData2 = arr;
        $scope.BRcharges = 'Rent Recievables';

        if (amount > 0) {
            $scope.RRTotaldebit = amount;
            $scope.RRTotalcredit = '';
        } else {
            $scope.RRTotaldebit = '';
            $scope.RRTotalcredit = amount;
        }

        $('#adjTotal').val($('#BRnewAmount').val());
    }

    $scope.closeReviewModal = function() {
        $scope.Rcharges = '';
        console.log($scope.Rcharges);

        if ($scope.RDoc_tag == 'Other Charges') {
            console.log("Other Charges");

            $scope.adjTotal = '';
            $scope.RDoc_tag = '';
            $scope.reviewData = [];
            $scope.total = '';

        } else if ($scope.RDoc_tag == 'Basic Rent') {
            console.log("Basic Rental");

            $scope.BRcharges = '';
            $scope.BRtotal = '';
            $scope.adjTotal = '';
            $scope.RDoc_tag = '';
            $scope.reviewData2 = [];
        }
    }

    // PAYMENT ADJUSTMENT FUNCTIONS
    $scope.tenantInfo2 = function(trade_name, tenancy_type) {
        var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };
        var ORNUMBER = { "ORNO": $scope.ORnumber };

        $http.post('../leasing_transaction/get_tenantDetails/', objData).success(function(result) {
            $scope.tenant_id = result[0].tenant_id;
            $scope.corporate_name = result[0].corporate_name;
        });
    }

    $scope.adjustmentsPayment = function(trade_name, tenancy_type) {
        var objData = { "trade_name": trade_name, "tenancy_type": tenancy_type };
        var ORNUMBER = { "ORNO": $scope.ORnumber };

        $http.post('../leasing_transaction/get_tenantDetails/', objData).success(function(result) {
            // $scope.tenant_id      = result[0].tenant_id;
            // $scope.corporate_name = result[0].corporate_name;

            $http.post("../leasing_transaction/get_ledgerAdjustmentPayment/" + $scope.tenant_id, ORNUMBER).success(function(result) {
                if (result == null || result == [{}] || result == '') {
                    pop.alert('<strong>No Payment Found.</strong>');
                } else {
                    $scope.Pdata = result;
                    $scope.Pdata.adjAmount = '0.00';
                }
            });

            $http.post('../leasing_transaction/get_adjNo').success(function(result) {
                console.log(result.adj_no);
                $('#adj_code').val(result.adj_no)
            });
        });
    }

    $scope.submitPaymentADJ = function(e) {
        e.preventDefault();

        pop.confirm('Are you sure you want to continue to submit this adjustment?', (res) => {

            if (!res) return;

            // var formData   = $(e.target).serializeObject();
            // formData.Pdata = $scope.Pdata;

            var formData = new FormData(e.target);
            var Pdata = JSON.parse(angular.toJson($scope.Pdata));
            formData = convertModelToFormData(Pdata, formData, 'Pdata');

            console.log(formData);

            $.ajax({
                type: 'POST',
                url: 'save_paymentADJ',
                data: formData,
                enctype: 'multipart/form-data',
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#spinner_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                success: function(data) {
                    $('#spinner_modal').modal('toggle');

                    if (data.type == 'error_file') {
                        generate('error', data.msg);
                    } else {
                        data = JSON.parse(data);

                        if (data['msg'] == 'Success') {
                            var filename = data['file_name'];
                            window.open(filename);
                            notify('success', 'Successfully Adjusted.');
                        } else if (data['msg'] == 'Error') {
                            generate('error', 'No Data to save.');
                        } else if (data['msg'] == 'DB_error') {
                            generate('error', 'Error on saving, something went wrong.');
                        }
                    }
                }
            });
        });
    }

    $scope.ptotalValue = function() {
        let total = 0;
        var pmt_amount;
        var amount;
        var adj_amount;
        var balance = 0;

        if ($scope.Pdata != undefined) {
            $scope.Pdata.forEach(function(data) {
                total += parseFloat(data.adjAmount.replace(',', ''));

                balance += parseFloat(data.inv_balance);
                pmt_amount = data.pmt_amount;
                amount = data.adjAmount;
                adj_amount = (parseFloat(amount) + parseFloat(pmt_amount));
            });

            // console.log(parseFloat(balance));

            // if ($('#PTotal').val() == '' || $('#PTotal').val() == '0.00') 
            // {
            //     $('#applyAdjP').prop('disabled', false);
            // }

            if ($('#adjAmount').prop('disabled') == true) {
                $('#applyAdjP').prop('disabled', true);
            } else if (total > balance) {
                $('#PTotal').css("border", "1px solid red");
                $('#applyAdjP').prop('disabled', true);
                $scope.errorDisplayP = "Total amount should not be greater than or equal to balance.";
            } else {
                $('#PTotal').css("border", "1px solid #ced4da");
                $('#applyAdjP').prop('disabled', false);
                $scope.errorDisplayP = "";
            }

            return total;
        } else {
            console.log('Table is empty');
        }
    }

    $scope.validateAmount = (p, e) => {
        if (parseFloat(p.adjAmount) < 0) {
            if (parseFloat(p.adjAmount) < -1 * parseFloat(p.pmt_amount)) {
                $(e.target).css("border", "1px solid red");
            } else {
                $(e.target).css("border", "1px solid #ced4da");
            }
        } else {
            if (parseFloat(p.adjAmount) > parseFloat(p.inv_balance)) {
                $(e.target).css("border", "1px solid red");
            } else {
                $(e.target).css("border", "1px solid #ced4da");
            }
        }
    }

    // ================== NOT USED
    // $scope.computeTotalBR = function()
    // {
    //     var balance = $scope.amount;                               //debit
    //     var paidAmount = Math.abs(parseFloat($scope.paidAmount1)); //credit
    //     var BRTotal = $('#BRTotal').val();                         //total

    //     if ($scope.BRmemoType == undefined) 
    //     {
    //         $('#BRTotal').css("border", "1px solid #ced4da");
    //         $scope.errorDisplay1 = "";
    //     }
    //     else if($scope.BRmemoType == 'Credit Memo') 
    //     {
    //         var newInvoiceAmount = (parseFloat(balance.replace(/\,/g,''), 10) - parseFloat(BRTotal.replace(/\,/g,''), 10));

    //         $scope.BRnewAmount = parseFloat(newInvoiceAmount);
    //         console.log(newInvoiceAmount + " = " + paidAmount);

    //         if (BRTotal == '' || BRTotal == '0.00') 
    //         {
    //             $('#BRSubmit').prop('disabled', true);
    //         }
    //         else if (newInvoiceAmount <= paidAmount) 
    //         {
    //             $('#BRnewAmount').css("border", "1px solid red");
    //             $scope.errorDisplay1 = "New Invoice Amount can't be less than it's paid amount!";
    //             $('#BRSubmit').prop('disabled', true);
    //         }
    //         else
    //         {
    //             $('#BRnewAmount').css("border", "1px solid #ced4da");
    //             $scope.errorDisplay1 = "";
    //             $('#BRSubmit').prop('disabled', false);
    //         }
    //     }
    //     else if($scope.BRmemoType == 'Debit Memo')
    //     {
    //         var newInvoiceAmount = parseFloat(balance.replace(/\,/g,''), 10) + parseFloat(BRTotal.replace(/\,/g,''), 10);

    //         $scope.BRnewAmount = newInvoiceAmount;
    //         console.log(newInvoiceAmount + " = " + paidAmount);

    //         if (BRTotal == '' || BRTotal == '0.00') 
    //         {
    //             $('#BRSubmit').prop('disabled', true);
    //         }
    //         else if (newInvoiceAmount >= paidAmount) 
    //         {
    //             $('#BRnewAmount').css("border", "1px solid #ced4da");
    //             $scope.errorDisplay1 = "";
    //             $('#BRSubmit').prop('disabled', false);
    //         }
    //         else
    //         {
    //             $('#BRnewAmount').css("border", "1px solid red");
    //             $scope.errorDisplay1 = "Total should not be less than to the amount paid.";
    //             $('#BRSubmit').prop('disabled', true);
    //         }
    //     }
    // }

    // $scope.computeTotalOC = function()
    // {
    //     var balance = $scope.amountOC; // ==== debit
    //     var paidAmount = Math.abs(parseFloat($scope.OCpaidAmount)); // ==== credit
    //     var OCTotal = $('#OCTotal').val(); // ==== total

    //     if ($scope.memoType == undefined) 
    //     {
    //         $('#OCTotal').css("border", "1px solid #ced4da");
    //         $scope.errorDisplay = "";
    //     }
    //     else if($scope.memoType == 'Credit Memo') 
    //     {
    //         var newInvoiceAmount = (parseFloat(balance.replace(/\,/g,''), 10) - parseFloat(OCTotal.replace(/\,/g,''), 10));

    //         $scope.newAmount = newInvoiceAmount;
    //         console.log(newInvoiceAmount + " = " + paidAmount);

    //         if (OCTotal == '' || OCTotal == '0.00') 
    //         {
    //             $('#OCSubmitBtn').prop('disabled', true);
    //         }
    //         else if (newInvoiceAmount <= paidAmount) 
    //         {
    //             $('#newAmount').css("border", "1px solid red");
    //             $scope.errorDisplay = "New Invoice amount should not be less than or equal to the amount paid.";
    //             $('#OCSubmitBtn').prop('disabled', true);
    //         }
    //         else
    //         {
    //             $('#newAmount').css("border", "1px solid #ced4da");
    //             $scope.errorDisplay = "";
    //             $('#OCSubmitBtn').prop('disabled', false);
    //         }
    //     }
    //     else if($scope.memoType == 'Debit Memo')
    //     {
    //         var newInvoiceAmount = parseFloat(balance.replace(/\,/g,''), 10) + parseFloat(OCTotal.replace(/\,/g,''), 10);

    //         $scope.newAmount = newInvoiceAmount;

    //         console.log(newInvoiceAmount + " = " + paidAmount);

    //         if (OCTotal == '' || OCTotal == '0.00') 
    //         {
    //             $('#newAmount').css("border", "1px solid #ced4da");
    //             $scope.errorDisplay = "";
    //         }
    //         else if (newInvoiceAmount >= paidAmount) 
    //         {
    //             $('#newAmount').css("border", "1px solid #ced4da");
    //             $scope.errorDisplay = "";
    //             $('#OCSubmitBtn').prop('disabled', false);
    //         }
    //         else
    //         {
    //             $('#newAmount').css("border", "1px solid red");
    //             $scope.errorDisplay = "Total should not be less than to the amount paid.";
    //             $('#OCSubmitBtn').prop('disabled', true);
    //         }
    //     }
    // }
    // ========================================== KING ARTHURS REVISION ADDED END ==================================== //
    //======================== End of Transaction Controller ========================//


    //EXPORTATION CODINGS

    $scope.removeExportationTag = function(url) {

        let rs = confirm('Are you sure you want to remove this exportion tagging?');

        if (!rs) return;

        $http.post(url).success(function(result) {
            console.log(result);
            if (result.type == 'success') {
                notify('success', result.msg);
                return;
            }
            generate(result.type, result.msg);
        });
    }
});




//======================== CCM Controller ========================//
myApp.controller('CCMController', function($scope, $http, $timeout, moment, $sce, $q, filterFilter, $rootScope, $interval) {
    //======================= Autocomplete ======================//

    $scope.dirty = {};
    var customer_name = [];
    $scope.ccm_banks = [];

    $scope.populate_ccm_customer = function(url) {
        $http.post(url).success(function(result) {
            customer_name = result;
        });
    }


    function suggest_states(term) {
        var q = term.toLowerCase().trim();
        var results = [];

        // Find first 10 states that start with `term`.
        for (var i = 0; i < customer_name.length && results.length < 10; i++) {
            var state = customer_name[i].fullname;
            if (state.toLowerCase().indexOf(q) === 0) {
                results.push({ label: state, value: state });
            }

        }
        return results;
    }

    /*$scope.autocomplete_options = {
        suggest: suggest_states
    };*/



    $scope.get_ccm_banks = function(url) {
        $http.post(url).success(function(result) {
            console.log(result);
            $scope.ccm_banks = result;
        });
    }

    // ===================== End of Autocomplete =================//
});

myApp.controller('FacilityRentalController', function($scope, $http, $timeout, moment, $sce, $q, filterFilter, $rootScope, $interval, NgTableParams, $filter) {
    //======================= Autocomplete ======================//

    $scope.dirty = {};
    var customer_name = [];


    $scope.populate_facilityrentalcustomer = function(url) {
        $http.post(url).success(function(result) {
            customer_name = result;
        });
    }


    function suggest_states(term) {
        var q = term.toLowerCase().trim();
        var results = [];

        // Find first 10 states that start with `term`.
        for (var i = 0; i < customer_name.length && results.length < 10; i++) {
            var state = customer_name[i].FacilityRental_CusName;
            if (state.toLowerCase().indexOf(q) === 0) {
                results.push({ label: state, value: state });
            }

        }
        return results;
    }

    $scope.autocomplete_options = {
        suggest: suggest_states
    };
    // ===================== End of Autocomplete =================//

    $scope.clear_frcustomerdata = function() {

    }

    $scope.generate_frcustomerdetails = function(frCustomerName, url) {
        $http.post(url + frCustomerName).success(function(result) {
            $scope.frcontactperson = result[0].FacilityRental_ContactPerson;
            $scope.frcontactnumber = result[0].FacilityRental_ContactNumber;
            $scope.fraddress = result[0].FacilityRental_CustomerAddress;
        });
    }

    $rootScope.$on("CallTablelistMethod", function(event, data) {
        $scope.loadList(data);
    });

    $scope.dataList = [];

    $scope.loadList = function(url) {

        $scope.isLoading = true;
        var data = $http.post(url).success(function(data) {
            $scope.dataList = data;
            $scope.$watch("searchedKeyword", function() {
                $scope.tableParams.page(1);
                $scope.tableParams.reload();
            });

            var searchData = function() {
                if ($scope.searchedKeyword)
                    return $filter('filter')($scope.dataList, $scope.searchedKeyword);
                return $scope.dataList;
            }

            $scope.tableParams = new NgTableParams({
                page: 1, // show first page
                count: 15 // count per page
            }, {
                total: $scope.dataList.length, // length of data
                getData: function(params) {
                    var searchedData = searchData();
                    params.total(searchedData.length);
                    $scope.data = searchedData.slice((params.page() - 1) * params.count(), params.page() * params.count());
                    $scope.data = params.sorting() ? $filter('orderBy')($scope.data, params.orderBy()) : $scope.data;
                    return $scope.data;
                }
            });
        });

        $q.all([data]).then(function(ret) {
            $scope.isLoading = false;
        });
    }

    $scope.get_discountdetails = function(url) {

        $scope.discount_details = [];
        $http.post(url).success(function(result) {
            $scope.discount_details = result;
            console.log(result);
        });
    }

    $scope.deletefrCustomer = function(url) {
        document.getElementById('anchor_delete').href = url;
    }
    $scope.deletefrFacility = function(url) {
        document.getElementById('anchor_delete').href = url;
    }
    $scope.delete_tmpreservation = function(url) {
        document.getElementById('anchor_delete').href = url;
    }

    $scope.get_discountforinvoice = function(url) {
        $scope.DiscountOptions = [];
        $http.post(url).success(function(result) {
            $scope.DiscountOptions = result;
            // console.log(result);
        });
    }

    $scope.ajaxDataList = [];

    $scope.ajaxLoadList = function(url, param) {
        var objData = { "param": param };
        $scope.isLoading = true;
        var data = $http.post(url, objData).success(function(ajaxData) {

            $scope.ajaxDataList = ajaxData;
            $scope.$watch("searchedKeyword", function() {
                $scope.ajaxTableParams.page(1);
                $scope.ajaxTableParams.reload();
            });

            var searchData = function() {
                if ($scope.searchedKeyword)
                    return $filter('filter')($scope.ajaxDataList, $scope.searchedKeyword);
                return $scope.ajaxDataList;
            }

            $scope.ajaxTableParams = new NgTableParams({
                page: 1, // show first page
                count: 15 // count per page
            }, {
                total: $scope.ajaxDataList.length, // length of data
                getData: function(params) {
                    var searchedData = searchData();
                    params.total(searchedData.length);
                    $scope.ajaxData = searchedData.slice((params.page() - 1) * params.count(), params.page() * params.count());
                    $scope.ajaxData = params.sorting() ? $filter('orderBy')($scope.ajaxData, params.orderBy()) : $scope.ajaxData;
                    return $scope.ajaxData;
                }
            });
        });

        $q.all([data]).then(function(ret) {
            $scope.isLoading = false;
        });
    }

    $scope.reprint_soa = function(file) {
        window.open(file);
    }

    $scope.populat_tenderTypeDesc = function(typeCode) {

        $scope.tender_typeDesc = "";
        switch (typeCode) {
            case "1":
                $scope.tender_typeDesc = 'Cash';
                break;
            case "2":
                $scope.tender_typeDesc = 'Check';
                break;
            case "3":
                $scope.tender_typeDesc = 'Bank to Bank';
                break;
            case "80":
                $scope.tender_typeDesc = 'JV payment - Business Unit';
                break;
            case "81":
                $scope.tender_typeDesc = 'JV payment - Subsidiary';
                break;
        }
    }

    $scope.populate_combobox = function(url) {
        $scope.itemList = [];
        $http.post(url).success(function(result) {
            $scope.itemList = result;
            // console.log(result);
        });
    }

    $scope.populate_bankCode = function(param) {
        var url = '../leasing_transaction/get_bankName/' + param;
        $scope.populate_combobox(url);
    }




    $scope.get_storeBankCode = function(url) {
        $http.post(url).success(function(data) {
            console.log(data);
            $scope.storeBankCode = data;
        });
    }

    $scope.get_paymentsoaNo = function(url) {
        $http.post(url).success(function(data) {
            console.log(data);
            $scope.SoaNo = data;
        });
    }

    $scope.get_soadetailspayment = function(soano, url) {
        $http.post(url + soano).success(function(result) {
            $scope.frcustomername = result[0].frcustomername;
            $scope.billing_period = result[0].billing_period;
            $scope.expected_amount = result[0].expected_amount;
            $scope.total_discount = result[0].total_discount;
            $scope.actual_amount = result[0].actual_amount;
            $scope.facilityrental_docno = result[0].facilityrental_docno;
            $scope.customer_id = result[0].customer_id;
            $scope.soa_amountPaid = result[0].amount_paid;
            $scope.balance = result[0].balance;
        });
    }

    $scope.get_paymentHistory = function(customer_name, url, url1) {
        $http.post(url + customer_name).success(function(result) {
            console.log(result);
            $scope.customer_id = result[0].id;
            $scope.contact_person = result[0].FacilityRental_ContactPerson;
            $scope.contact_no = result[0].FacilityRental_ContactNumber;
            $scope.address = result[0].FacilityRental_CustomerAddress

            var customer_id = result[0].id;

            var url_payment = url1 + customer_id;
            $rootScope.$emit("CallTablelistMethod", url_payment);
        });
    }
});
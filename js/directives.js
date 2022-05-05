'use strict';

angular

    .module('myApp')
    .directive("isUnique", ["$http", function($http) {
        return {
            require: 'ngModel',
            restrict: 'A',
            link: function(scope, elem, attrs, ctrl) {

                // check that a valid api end point is provided
                if (typeof attrs.isUniqueApi === "undefined" || attrs.isUniqueApi === ""){
                    throw new Error("Missing api end point; use is-unique-api to define the end point");
                }

                // set a watch on the value of the field
                scope.$watch(function () {
                    return ctrl.$modelValue;
                }, function(currentValue) {

                    // when the field value changes
                    // send an xhr request to determine if the value is available
                    var url = attrs.isUniqueApi;
                    if (typeof currentValue !== 'undefined') {
                        url = url + currentValue;
                        // alert(url);
                        elem.addClass('loading');
                        $http.get(url).success(function(data) {

                            data = data.substr(2);

                            elem.removeClass('loading');
                            ctrl.$setValidity('unique', !data);
                        }).error(function() {
                            elem.removeClass('loading');
                        });
                    }
                });
            }
        };
    }])


    .directive("isUniqueUpdate", ["$http", function($http) {

        return {
            require: 'ngModel',
            restrict: 'A',
            link: function(scope, elem, attrs, ctrl) {

                // check that a valid api end point is provided
                if (typeof attrs.isUniqueApi === "undefined" || attrs.isUniqueApi === ""){
                    throw new Error("Missing api end point; use is-unique-api to define the end point");
                }

                // set a watch on the value of the field
                scope.$watch(function () {
                    return ctrl.$modelValue;
                }, function(currentValue) {
                    // when the field value changes
                    // send an xhr request to determine if the value is available
                    var url = attrs.isUniqueApi;
                    var id = attrs.isUniqueId;

                    if (typeof currentValue !== 'undefined') {
                        url = url + currentValue + "_" + id;

                        elem.addClass('loading');
                        $http.get(url).success(function(data) {
                            data = data.substr(2);
                            // console.log(data);
                            elem.removeClass('loading');
                            ctrl.$setValidity('unique', !data);
                        }).error(function() {
                            elem.removeClass('loading');
                        });
                    }
                });
            }
        };

    }])



    .directive('format', ['$filter', function ($filter) {
        return {
            require: '?ngModel',
            link: function (scope, elem, attrs, ctrl) {
                if (!ctrl) return;

                var format = {
                        prefix: '',
                        centsSeparator: '.',
                        thousandsSeparator: ','
                    };

                ctrl.$parsers.unshift(function (value) {
                    elem.priceFormat(format);
                    // console.log('parsers', elem[0].value);
                    return elem[0].value;
                });

                ctrl.$formatters.unshift(function (value) {
                    elem[0].value = ctrl.$modelValue * 100;
                    elem.priceFormat(format);
                    // console.log('formatters', elem[0].value);
                    return elem[0].value ;
                })
            }
        };
    }])


    .directive("passwordVerify", function() {
        return {
            require: "ngModel",
            scope: {
                passwordVerify: '='
            },
            link: function(scope, element, attrs, ctrl) {
                scope.$watch(function() {
                    var combined;

                    if (scope.passwordVerify || ctrl.$viewValue) {
                       combined = scope.passwordVerify + '_' + ctrl.$viewValue;
                    }
                    return combined;
                }, function(value) {
                    if (value) {
                        ctrl.$parsers.unshift(function(viewValue) {
                            var origin = scope.passwordVerify;
                            if (origin !== viewValue) {
                                ctrl.$setValidity("passwordVerify", false);
                                return undefined;
                            } else {
                                ctrl.$setValidity("passwordVerify", true);
                                return viewValue;
                            }
                        });
                    }
                });
            }
        };
    })

    .directive('validFile', function () {
        return {
            require: 'ngModel',
            link: function (scope, el, attrs, ngModel) {
                ngModel.$render = function () {
                    ngModel.$setViewValue(el.val());
                };

                el.bind('change', function () {
                    scope.$apply(function () {
                        ngModel.$render();
                    });
                });
            }
        };
    });

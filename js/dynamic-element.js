$(document).ready(function(){

    var next = 1;
    $(".add-more").click(function(e){
        e.preventDefault();
        var addto = "#field" + next;
        var addRemove = "#field" + (next);
        next = next + 1;
        var newIn = '<input autocomplete="off" required class="input form-control" id="field' + next + '" name="floor_name[]" type="text">';
        var newInput = $(newIn);
        var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >-</button></div><div id="field">';
        var removeButton = $(removeBtn);
        $(addto).after(newInput);
        $(addRemove).after(removeButton);
        $("#field" + next).attr('data-source',$(addto).attr('data-source'));
        $("#count").val(next);

            $('.remove-me').click(function(e){
                e.preventDefault();
                var fieldNum = this.id.charAt(this.id.length-1);
                var fieldID = "#field" + fieldNum;
                $(this).remove();
                $(fieldID).remove();
            });
    });




    var counter = 1;
    $(".add-bank").click(function(e){
        e.preventDefault();


        var addto = "#select" + counter;
        var addRemove = "#select" + (counter);
        counter = counter + 1;
        var newIn = '<select required class="input form-control" id="select' + counter + '" name="accre_bank[]" ></select>';
        var newInput = $(newIn);
        var removeBtn = '<button id="remove' + (counter - 1) + '" class="btn btn-danger remove-bank" >-</button></div><div id="select">';
        var removeButton = $(removeBtn);
        $(addto).after(newInput);
        $(addRemove).after(removeButton);
        $("#field" + counter).attr('data-source',$(addto).attr('data-source'));
        $("#select").val(counter);

        for (var i = 0; i < banks_array.length; i++) {
            $('<option/>').val(banks_array[i]['bank_name']).html(banks_array[i]['bank_name']).appendTo('#select' + counter);
        };


        $('.remove-bank').click(function(e){
                e.preventDefault();
                var fieldNum = this.id.charAt(this.id.length-1);
                var fieldID = "#select" + fieldNum;
                $(this).remove();
                $(fieldID).remove();
            });

    });










    $("#intent_letter").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });

    $("#com_prof").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });

    $("#dti_busireg").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });

    $("#brochures").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });

    $("#perspective").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });

    $("#gross_sales").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });

    $("#pricemenu_list").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });


    $("#proposal_letter").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });

    $("#booth_layout").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });

    $("#contract_docs").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });


    $("#amended_contract_docs").fileinput({
        maxFileCount: 100,
        showCaption: false,
        previewFileType: "text",
        showUpload: false,
        allowedFileExtensions: ["jpg", "gif", "png"]
    });

});


function deleteFirstIndex(id)
{
    document.getElementById(id).remove(0);
}



function prospect_formDefault(floor_name, location_code, area_classification, area_type, payment_mode, rent_period, floor_area, basic_rental)
{
    document.getElementById(floor_name).value = "";
    document.getElementById(location_code).value = "";
    document.getElementById(area_classification).value = "";
    document.getElementById(area_type).value = "";
    document.getElementById(payment_mode).value = "";
    document.getElementById(rent_period).value = "";
    document.getElementById(floor_area).value = "";
    document.getElementById(basic_rental).value = "";
}


function clear_locationCodeData()
{
    document.getElementById('floor_location').value = "";
}



function clear_me(category, floor_price, floor_area, per_day, per_week, per_month)
{
    document.getElementById(category).value = "";
    document.getElementById(floor_price).value = "0.00";
    document.getElementById(floor_area).value = "0.00";
    document.getElementById(per_day).value = "0.00";
    document.getElementById(per_week).value = "0.00";
    document.getElementById(per_month).value = "0.00";
}

function clearComboBox(id, data_id, defaultDataId)
{
    var currentValue = document.getElementById(data_id).value;
    var defaultValue = document.getElementById(defaultDataId).value;
    if (currentValue != defaultValue)
    {
        document.getElementById(id).remove(0);
    }
}


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#previewImg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#selectedImg").change(function(){
    readURL(this);
});

function preview(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#add_previewImg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#addImg").change(function(){
    preview(this);
});


// $("#plus_vat").on('click', function(){


//     if(document.getElementById('plus_vat').checked)
//     {
//         $("#doc_holder").html("");
//     } else {
//         $("#doc_holder").html('<div class="row">' +
//                                     '<div class="form-group">' +
//                                         '<label for="montly_wvat" class="col-md-4 control-label text-right">BIR Document</label>' +
//                                         '<div class="col-md-8">' +
//                                            '<input type = "file" name = "bir_doc" required id = "bir_doc" class = "form-control" />' +
//                                         '</div>' +
//                                     '</div>' +
//                                 '</div>');
//     }
// });



$("#tenant_types").change(function(){
    var tenant_type = $("#tenant_types").val();
    if (tenant_type == 'Cooperative' || tenant_type == 'Government Agencies(w/ Basic)' || tenant_type == 'Government Agencies(w/o Basic)')
    {
        $("#suppDoc_holder").html('<div class="row">' +
                                    '<div class="form-group">' +
                                        '<label for="montly_wvat" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Supporting Document</label>' +
                                        '<div class="col-md-8">' +
                                           '<input type = "file" multiple="multiple" name = "supporting_doc[]" id = "supporting_doc" class = "form-control" />' +
                                        '</div>' +
                                    '</div>' +
                                '</div>');
    }
    else if (tenant_type == 'Private Entities' || tenant_type == 'AGC-Subsidiary')
    {
        // $("#suppDoc_holder").html('<div class="row">' +
        //                                 '<div class="form-group">' +
        //                                     '<label for="montly_wvat" class="col-md-4 control-label text-right"></label>' +
        //                                     '<div class="col-md-8 text-right">' +
        //                                        '<label class="control-label">Vatable</label> <input type = "checkbox" checked onclick = "plusvat()" value = "Added"  name = "plus_vat" id = "plus_vat" />' +
        //                                     '</div>' +
        //                                 '</div>' +
        //                             '</div>' +
        //                             '<div class="row">' +
        //                                 '<div class="form-group">' +
        //                                     '<label for="montly_wvat" class="col-md-4 control-label text-right"></label>' +
        //                                     '<div class="col-md-8 text-right">' +
        //                                        '<label class="control-label">Less Withholding Tax</label> <input type = "checkbox" checked value = "Added"  name = "less_wht" id = "less_wht" />' +
        //                                     '</div>' +
        //                                 '</div>' +
        //                             '</div>');
    }
    // else if (tenant_type == 'AGC-Subsidiary')
    // {
    //     $("#suppDoc_holder").html('');
    // }

});


// function check_tenantType()
// {
//     alert("asdasd");
// }



function plusvat()
{  
    if(document.getElementById('plus_vat').checked)
    {
        $("#doc_holder").html("");
    } else {
        $("#doc_holder").html('<div class="row">' +
                                    '<div class="form-group">' +
                                        '<label for="montly_wvat" class="col-md-4 control-label text-right">BIR Document</label>' +
                                        '<div class="col-md-8">' +
                                           '<input type = "file" name = "bir_doc" required id = "bir_doc" class = "form-control" />' +
                                        '</div>' +
                                    '</div>' +
                                '</div>');
    }
}


function isvat()
{
    if(document.getElementById('amendment_plus_vat').checked)
    {
        $("#doc_holder").html("");
    } else {
        $("#doc_holder").html('<div class="row">' +
                                    '<div class="form-group">' +
                                        '<label for="montly_wvat" class="col-md-4 control-label text-right">BIR Document</label>' +
                                        '<div class="col-md-8">' +
                                           '<input type = "file" name = "bir_doc" id = "bir_doc" class = "form-control" />' +
                                        '</div>' +
                                    '</div>' +
                                '</div>');

    }
}








$("#append_preOp_charges").on('click', function(){
    var charges_type = 'Pre Operation Charges';
    var description = $('#preOp_desc').val();
    var charges_code = $('#preOp_chargeCode').val();
    var uom = $('#preOp_uom').val();
    var basic_rental = $('#basic_rental').val();
    var actual_amount = $('#preOp_actualAmt').val();
    var prev_reading = $('#prev_reading').val();
    var curr_reading = $('#curr_reading').val();


    if(prev_reading == undefined)
    {
        prev_reading = 0.00;
    }
    if(curr_reading == undefined)
    {
        curr_reading = 0.00;
    }

    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Pre Operation Charges' /> " + charges_type + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +" mo(s).Basic/fixed monthly' />" + uom + " mo(s).Basic/fixed monthly rent</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + basic_rental +"' />&#8369;" + basic_rental +  "</td>" +
                                    "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'prev_reading[]' value = '" + prev_reading +"' />&#8369;" + prev_reading +  "</td>" +
                                    "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'curr_reading[]' value = '" + curr_reading +"' />&#8369;" + curr_reading +  "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '0.00' />0.00</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                    "<td><a class = 'btn-sm btn-danger' onClick='deleteme(this)'  href = '#'><i class = 'fa fa-trash'></i></a></td>" +
                                "</tr>");



    $('#preOp_desc').val("");
    $('#preOp_chargeCode').val("");
    $('#preOp_uom').val("");
    $('#basic_rental').val("");
    $('#preOp_actualAmt').val("");

    $('#preop_charges').modal('toggle');

    getTotalCharges();

});



$('#append_penalty_charges').on('click', function(){
    var charges_type = $('#charges_type').val();
    var description = $('#penalty_desc').val();
    var charges_code = $('#penalty_chargeCode').val();
    var uom = $('#penalty_oum').val();
    var unit_price = $('#penalty_unitPrice').val();
    var total_unit = $('#penalty_totalUnit').val();
    var actual_amount = 0;

    if (uom != 'Inputted')
    {
        actual_amount = $('#penalty_actualAmt').val();


        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Other' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />&#8369;" + unit_price +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                        "<td><a class = 'btn-sm btn-danger' onClick='deleteme(this)'  href = '#'><i class = 'fa fa-trash'></i></a></td>" +
                                    "</tr>");

    } else {
        actual_amount = $('#penalty_inputtedAmt').val();

            $('#charges_table tbody').append(
                                            "<tr>" +
                                                "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Other' /> " + charges_type + "</td>" +
                                                "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                                "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                                "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Inputted' />Inputted</td>" +
                                                "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '0.00' />0.00</td>" +
                                                "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '0.00' />0.00</td>" +
                                                "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                                "<td><a class = 'btn-sm btn-danger' onClick='deleteme(this)'  href = '#'><i class = 'fa fa-trash'></i></a></td>" +
                                            "</tr>");

    }



    $('#charges_type').val("");
    $('#penalty_desc').val("");
    $('#penalty_chargeCode').val("");
    $('#penalty_oum').val("");
    $('#penalty_actualAmt').val("");
    $('#penalty_unitPrice').val("");
    $('#penalty_totalUnit').val("");
    $('#penalty_inputtedAmt').val("");
    $('#penalty_charges').modal('toggle');
    getTotalCharges();

});



$('#append_constMat').on('click', function()
{

    var charges_type = 'Construction Materials';
    var description = $('#constMat_desc').val();
    var charges_code = $('#charges_code').val();
    var uom = $('#charges_uom').val();
    var unit_price = $('#charges_unitPrice').val();
    var total_unit = $('#cosntMat_totalUnit').val();
    var prev_reading = $('#prev_reading').val();
    var curr_reading = $('#curr_reading').val();

    if (total_unit == undefined)
    {
        total_unit = '0.00';
    }
    else if(prev_reading == undefined)
    {
        prev_reading = 0.00;
    }
    else if(curr_reading == undefined)
    {
        curr_reading = 0.00;
    }

    var actual_amount = $('#constMat_actualAmt').val();

    $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Construction Materials' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />&#8369;" + unit_price +  "</td>" +
                                        "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'prev_reading[]' value = '" + prev_reading +"' />&#8369;" + prev_reading +  "</td>" +
                                        "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'curr_reading[]' value = '" + curr_reading +"' />&#8369;" + curr_reading +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                        "<td><a class = 'btn-sm btn-danger' onClick='deleteme(this)'  href = '#'><i class = 'fa fa-trash'></i></a></td>" +
                                    "</tr>");


    $('#constMat').modal('toggle');
    getTotalCharges();

});


$('#append_retro_charges').on('click', function() {

    var charges_type = 'Retro Rental';
    var description = $('#rental_type').val();
    added_vat = $('#added_vat').val();
    added_vat = Number(added_vat).toFixed(2);

    less_witholding = $('#less_witholding').val();
    less_witholding = Number(less_witholding).toFixed(2);
    var total_retro = $('#total_retro').val();
    total_retro = Number(toNumber(total_retro)).toFixed(2);


    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td align = 'right'><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + total_retro +"' />&#8369;" + total_retro + "</td>" +
                                    "<td></td>" +
                                "</tr>");

    if (added_vat > 0)
    {
        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'VAT Output' />Vat Output</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td align = 'right'><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + added_vat +"' />&#8369;" + added_vat + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
    }

    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Creditable Witholding Taxes' />Creditable Witholding Taxes</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td align = 'right'><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + less_witholding +"' />&#8369;" + less_witholding + "</td>" +
                                    "<td></td>" +
                                "</tr>");




    document.getElementById("total_amount").value = total_retro.replace(/\d(?=(\d{3})+\.)/g, '$&,');
    closeModal('retro_charges');


});


$('#append_less30_basic_fixedPercentage').on('click', function(){
    var charges_type = 'Basic/Monthly Rental';
    var description = $('#rental_type').val();
    var rental_type = $('#rental_type').val();
    var charges_code = "";
    var uom = "";
    var unit_price = "";
    var total_unit = "";
    var actual_amount;
    var added_vat = $('#vat_fixedPercentage_less_30Days').val();
    added_vat = Number(added_vat).toFixed(2);
    less_witholding = $('#wht_fixedPercentage_less_30Days').val();
    less_witholding = Number(less_witholding).toFixed(2);
    var actual_amount = $('#less_30_fixedPercentage_current_rental').val();
    actual_amount = Number(toNumber(actual_amount)).toFixed(2);


    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />" + unit_price +  "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");





    var tenant_type = document.getElementsByName("less30_fixedPercentage_tenant_type[]");
    var discount_type = document.getElementsByName("less30_fixedPercentage_discount_type[]");
    var discount = document.getElementsByName("less30_fixedPercentage_discount[]");


    var discount_amount = 0;
    for (var i = 0; i < tenant_type.length / 3; i++)
    {
        if (discount_type[i].value == "Fixed Amount")
        {
            discount_amount = Number(discount[i].value).toFixed(2);

            $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");
        } else{

            discount_amount = (Number(discount[i].value) / 100) * actual_amount;

            $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");
        }

    }


    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'VAT Output' />Vat Output</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + added_vat +"' />&#8369;" + added_vat + "</td>" +
                                    "<td></td>" +
                                "</tr>");

    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Creditable Witholding Taxes' />Creditable Witholding Taxes</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + less_witholding +"' />&#8369;" + less_witholding + "</td>" +
                                    "<td></td>" +
                                "</tr>");


    $('#less_than_30Days_FixedPercentage_tenant').modal('toggle');
    getTotalCharges();
});

$('#append_less30_basic').on('click', function(){

    var charges_type = 'Basic/Monthly Rental';
    var description = $('#rental_type').val();
    var rental_type = $('#rental_type').val();
    var charges_code = "";
    var uom = "";
    var unit_price = "";
    var total_unit = "";
    var actual_amount;
    var added_vat = $('#vat_fixed_less_30Days').val();
    added_vat = Number(added_vat).toFixed(2);
    less_witholding = $('#wht_fixed_less_30Days').val();
    less_witholding = Number(less_witholding).toFixed(2);
    var actual_amount = $('#current_rental').val();
    actual_amount = Number(toNumber(actual_amount)).toFixed(2);


    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />" + unit_price +  "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");





    var tenant_type = document.getElementsByName("less30_fixed_tenant_type[]");
    var discount_type = document.getElementsByName("less30_fixed_discount_type[]");
    var discount = document.getElementsByName("less30_fixed_discount[]");


    var discount_amount = 0;
    for (var i = 0; i < tenant_type.length / 3; i++)
    {
        if (discount_type[i].value == "Fixed Amount")
        {
            discount_amount = Number(discount[i].value).toFixed(2);

            $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");
        } else{

            discount_amount = (Number(discount[i].value) / 100) * actual_amount;

            $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");
        }

    }


    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'VAT Output' />Vat Output</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + added_vat +"' />&#8369;" + added_vat + "</td>" +
                                    "<td></td>" +
                                "</tr>");

    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Creditable Witholding Taxes' />Creditable Witholding Taxes</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + less_witholding +"' />&#8369;" + less_witholding + "</td>" +
                                    "<td></td>" +
                                "</tr>");


    $('#less_than_30Days_Fixed_tenant').modal('toggle');
    getTotalCharges();

});


$('#append_basicRental_manual').on('click', function() {

    var charges_type = 'Basic/Monthly Rental';
    var description = $('#rental_type').val();
    var rental_type = $('#rental_type').val();
    var charges_code = "";
    var uom = "";
    var unit_price = "";
    var total_unit = "";
    var actual_amount;
    actual_amount = $('#basicRental_manual').val();
    actual_amount = Number(toNumber(actual_amount)).toFixed(2);
    added_vat = $('#added_vat_manual').val();
    added_vat = Number(added_vat).toFixed(2);

    less_witholding = $('#less_witholding_manual').val();
    less_witholding = Number(less_witholding).toFixed(2);


    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />" + unit_price +  "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");




    var tenant_type = document.getElementsByName("tenant_type_manual[]");
    var discount_type = document.getElementsByName("discount_type_manual[]");
    var discount = document.getElementsByName("discount_manual[]");


    var discount_amount = 0;
    for (var i = 0; i < tenant_type.length / 3; i++)
    {
        if (discount_type[i].value == "Fixed Amount")
        {
            discount_amount = Number(discount[i].value).toFixed(2);

            $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");
        } else{

            discount_amount = (Number(discount[i].value) / 100) * actual_amount;

            $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");
        }

    }


    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'VAT Output' />Vat Output</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + added_vat +"' />&#8369;" + added_vat + "</td>" +
                                    "<td></td>" +
                                "</tr>");

    $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Creditable Witholding Taxes' />Creditable Witholding Taxes</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + less_witholding +"' />&#8369;" + less_witholding + "</td>" +
                                    "<td></td>" +
                                "</tr>");


    $('#basic_manual').modal('toggle');
    getTotalCharges();
});

$('#append_basicRental').on('click', function() {

    var charges_type = 'Basic/Monthly Rental';
    var description = $('#rental_type').val();
    var rental_type = $('#rental_type').val();
    var charges_code = "";
    var uom = "";
    var unit_price = "";
    var total_unit = "";
    var actual_amount;


    if (rental_type == 'Fixed')
    {
        actual_amount = $('#basic_rental').val();
        actual_amount = Number(toNumber(actual_amount)).toFixed(2);
        added_vat = $('#added_vat').val();
        added_vat = Number(added_vat).toFixed(2);

        less_witholding = $('#less_witholding').val();
        less_witholding = Number(less_witholding).toFixed(2);

        percent_increment = $('#percent_increment').val();
        increment_value = $('#increment_value').val();

        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />" + unit_price +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");



        if (percent_increment != undefined)
        {
            $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Rent Incrementation' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Rental Incrementation' />Rental Incrementation</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + percent_increment + "' />" + percent_increment + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + increment_value +"' />&#8369;" + increment_value + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
        }


        var tenant_type = document.getElementsByName("tenant_type[]");
        var discount_type = document.getElementsByName("discount_type[]");
        var discount = document.getElementsByName("discount[]");


        var discount_amount = 0;
        for (var i = 0; i < tenant_type.length / 3; i++)
        {
            if (discount_type[i].value == "Fixed Amount")
            {
                discount_amount = Number(discount[i].value).toFixed(2);

                $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
            } else{

                discount_amount = (Number(discount[i].value) / 100) * actual_amount;

                $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
            }

        }


        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'VAT Output' />Vat Output</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + added_vat +"' />&#8369;" + added_vat + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Creditable Witholding Taxes' />Creditable Witholding Taxes</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + less_witholding +"' />&#8369;" + less_witholding + "</td>" +
                                        "<td></td>" +
                                    "</tr>");


        $('#basicRental_modal').modal('toggle');
        getTotalCharges();

    }
    else if (rental_type == 'Fixed Plus Percentage' || rental_type == 'Percentage')
    {
        actual_amount = $('#rent_sale').val();
        actual_amount = Number(actual_amount).toFixed(2);

        added_vat = $('#added_vat').val();
        added_vat = Number(added_vat).toFixed(2);

        less_witholding = $('#less_witholding').val();
        less_witholding = Number(less_witholding).toFixed(2);

        
        percent_increment = $('#percent_increment').val();
        increment_value = $('#increment_value').val();

        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />" + unit_price +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

        if (percent_increment != undefined)
        {
            $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Rent Incrementation' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Rental Incrementation' />Rental Incrementation</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + percent_increment + "' />" + percent_increment + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + increment_value +"' />&#8369;" + increment_value + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
        }


        var tenant_type = document.getElementsByName("tenant_type[]");
        var discount_type = document.getElementsByName("discount_type[]");
        var discount = document.getElementsByName("discount[]");


        var discount_amount = 0;
        for (var i = 0; i < tenant_type.length / 3; i++)
        {

            if (discount_type[i].value == "Fixed Amount")
            {
                discount_amount = Number(discount[i].value).toFixed(2);

                $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
            } else{

                discount_amount = (Number(discount[i].value) / 100) * actual_amount;

                $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
            }

        }

        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'VAT Output' />Vat Output</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + added_vat +"' />&#8369;" + added_vat + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Creditable Witholding Taxes' />Creditable Witholding Taxes</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + less_witholding +"' />&#8369;" + less_witholding + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

        $('#basicRental_modal').modal('toggle');
        getTotalCharges();
    }
    else if (rental_type == 'Percentage Base Tenant')
    {
        actual_amount = $('#income').val();
        actual_amount = Number(toNumber(actual_amount)).toFixed(2);

        added_vat = $('#added_vat').val();
        added_vat = Number(added_vat).toFixed(2);

        less_witholding = $('#less_witholding').val();
        less_witholding = Number(less_witholding).toFixed(2);


        percent_increment = $('#percent_increment').val();
        increment_value = $('#increment_value').val();



        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />" + unit_price +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");



        if (percent_increment != undefined)
        {
            $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Rent Incrementation' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Rental Incrementation' />Rental Incrementation</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + percent_increment + "' />" + percent_increment + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + increment_value +"' />&#8369;" + increment_value + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
        }


        var tenant_type = document.getElementsByName("tenant_type[]");
        var discount_type = document.getElementsByName("discount_type[]");
        var discount = document.getElementsByName("discount[]");


        var discount_amount = 0;
        for (var i = 0; i < tenant_type.length / 3; i++)
        {
            if (discount_type[i].value == "Fixed Amount")
            {
                discount_amount = Number(discount[i].value).toFixed(2);

                $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
            } else{

                discount_amount = (Number(discount[i].value) / 100) * actual_amount;

                $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
            }

        }


        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'VAT Output' />Vat Output</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + added_vat +"' />&#8369;" + added_vat + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Creditable Witholding Taxes' />Creditable Witholding Taxes</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + less_witholding +"' />&#8369;" + less_witholding + "</td>" +
                                        "<td></td>" +
                                    "</tr>");


        $('#basicRental_modal').modal('toggle');
        getTotalCharges();
    }
});





function append_otherCharges()
{
    var charges_type = 'Other';
    var description = $('#charges_desc').val();
    var charges_code = $('#charges_code').val();
    var uom = $('#charges_uom').val();
    var unit_price = $('#charges_unitPrice').val();
    var total_unit = $('#charges_totalUnit').val();
    var prev_reading = $('#prev_reading').val();
    var curr_reading = $('#curr_reading').val();

    if (total_unit == undefined)
    {
        total_unit = '0.00';
    }
    if(prev_reading == undefined)
    {
        prev_reading = 0.00;
    }
    if(curr_reading == undefined)
    {
        curr_reading = 0.00;
    }




    var actual_amount = $('#otherCharges_actualAmt').val();


    if (description == 'Expanded Withholding Tax')
    {
        actual_amount = -1 * Number(toNumber(actual_amount));
    }

    $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Other' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />&#8369;" + unit_price +  "</td>" +
                                        "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'prev_reading[]' value = '" + prev_reading +"' />&#8369;" + prev_reading +  "</td>" +
                                        "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'curr_reading[]' value = '" + curr_reading +"' />&#8369;" + curr_reading +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                        "<td><a class = 'btn-sm btn-danger' onClick='deleteme(this)'  href = '#'><i class = 'fa fa-trash'></i></a></td>" +
                                    "</tr>");


    $('#other_charges').modal('toggle');
    getTotalCharges();
}


function append_less30_monthlyCharges()
{

    var charges_type = 'Other';
    var tmp_desc = document.getElementsByName('less30_monthly_description[]');
    var description = get_multipleValue(tmp_desc);

    var tmp_code = document.getElementsByName('less30_monthly_charges_code[]');
    var charges_code = get_multipleValue(tmp_code);

    var tmp_uom = document.getElementsByName('less30_monthly_uom[]');
    var uom = get_multipleValue(tmp_uom);

    var tmp_unit_price = document.getElementsByName('less30_monthly_unit_price[]');
    var unit_price = get_multipleValue(tmp_unit_price);

    var tmp_prev = document.getElementsByName('less30_prev_reading[]');
    var prev_reading = get_multipleValue(tmp_prev);

    var tmp_curr = document.getElementsByName('less30_curr_reading[]');
    var curr_reading = get_multipleValue(tmp_curr);

    var tmp_total_unit = document.getElementsByName('less30_monthly_total_unit[]');
    var total_unit = get_multipleValue(tmp_total_unit);

    var tmp_amount = document.getElementsByName('less30_monthly_actual_amount[]');
    var actual_amount = get_multipleValue(tmp_amount);

    for (var i = 0; i < description.length; i++)
    {
        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Other' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code[i] +"' /> " + charges_code[i] + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description[i] +"' /> " + description[i] + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom[i] +"' />" + uom[i] + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price[i] +"' />&#8369;" + unit_price[i] +  "</td>" +
                                        "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'prev_reading[]' value = '" + prev_reading[i] +"' />&#8369;" + prev_reading[i] +  "</td>" +
                                        "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'curr_reading[]' value = '" + curr_reading[i] +"' />&#8369;" + curr_reading[i] +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit[i] +"' />" + total_unit[i] + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount[i] +"' />&#8369;" + actual_amount[i] + "</td>" +
                                        "<td><a class = 'btn-sm btn-danger' onClick='deleteme(this)'  href = '#'><i class = 'fa fa-trash'></i></a></td>" +
                                    "</tr>");



    }

    getTotalCharges();
    $('#less30_monthly_charges').modal('toggle');
}


function append_monthlyCharges()
{
    var charges_type = 'Other';
    var tmp_desc = document.getElementsByName('monthly_description[]');
    var description = get_multipleValue(tmp_desc);

    var tmp_code = document.getElementsByName('monthly_charges_code[]');
    var charges_code = get_multipleValue(tmp_code);

    var tmp_uom = document.getElementsByName('monthly_uom[]');
    var uom = get_multipleValue(tmp_uom);

    var tmp_unit_price = document.getElementsByName('monthly_unit_price[]');
    var unit_price = get_multipleValue(tmp_unit_price);

    var tmp_prev = document.getElementsByName('prev_reading[]');
    var prev_reading = get_multipleValue(tmp_prev);

    var tmp_curr = document.getElementsByName('curr_reading[]');
    var curr_reading = get_multipleValue(tmp_curr);

    var tmp_total_unit = document.getElementsByName('monthly_total_unit[]');
    var total_unit = get_multipleValue(tmp_total_unit);

    var tmp_amount = document.getElementsByName('monthly_actual_amount[]');
    var actual_amount = get_multipleValue(tmp_amount);

    for (var i = 0; i < description.length; i++)
    {

        if (description[i] == 'Expanded Withholding Tax') {
            actual_amount[i] = actual_amount = -1 * Number(toNumber(actual_amount[i]));
        }

        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Other' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code[i] +"' /> " + charges_code[i] + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description[i] +"' /> " + description[i] + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom[i] +"' />" + uom[i] + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price[i] +"' />&#8369;" + unit_price[i] +  "</td>" +
                                        "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'prev_reading[]' value = '" + prev_reading[i] +"' />&#8369;" + prev_reading[i] +  "</td>" +
                                        "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'curr_reading[]' value = '" + curr_reading[i] +"' />&#8369;" + curr_reading[i] +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit[i] +"' />" + total_unit[i] + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount[i] +"' />&#8369;" + actual_amount[i] + "</td>" +
                                        "<td><a class = 'btn-sm btn-danger' onClick='deleteme(this)'  href = '#'><i class = 'fa fa-trash'></i></a></td>" +
                                    "</tr>");



    }

    getTotalCharges();
    $('#monthly_charges').modal('toggle');
}



function get_multipleValue(data)
{
    var tmp = [];
    for (var i = 0; i < data.length; ++i)
    {
        if (typeof data[i].value !== "undefined")
        {
            tmp.push(data[i].value);
        }
    }

    return tmp;
}


function js_spinner_open()
{
    $('#spinner_modal').modal('show');
}

function js_spinner_close()
{
    $('#spinner_modal').modal('toggle');
}






$('#append_OtherCharges').on('click', function()
{

    // var charges_type = 'Other';
    // var description = $('#charges_desc').val();
    // var charges_code = $('#charges_code').val();
    // var uom = $('#charges_uom').val();
    // var unit_price = $('#charges_unitPrice').val();
    // var total_unit = $('#charges_totalUnit').val();
    // var prev_reading = $('#prev_reading').val();
    // var curr_reading = $('#curr_reading').val();

    // if (total_unit == undefined)
    // {
    //     total_unit = '0.00';
    // }

    // if(prev_reading == undefined)
    // {
    //     prev_reading = 0.00;
    // }
    // // else if(curr_reading == undefined)
    // // {
    // //     curr_reading = 0.00;
    // // }

    // var actual_amount = $('#otherCharges_actualAmt').val();

    // $('#charges_table tbody').append(
    //                                 "<tr>" +
    //                                     "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Other' /> " + charges_type + "</td>" +
    //                                     "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
    //                                     "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
    //                                     "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
    //                                     "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />&#8369;" + unit_price +  "</td>" +
    //                                     "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'prev_reading[]' value = '" + prev_reading +"' />&#8369;" + prev_reading +  "</td>" +
    //                                     "<td style = 'display:none'><input type ='text' style = 'display:none' name = 'curr_reading[]' value = '" + curr_reading +"' />&#8369;" + curr_reading +  "</td>" +
    //                                     "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
    //                                     "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
    //                                     "<td><a class = 'btn-sm btn-danger' onClick='deleteme(this)'  href = '#'><i class = 'fa fa-trash'></i></a></td>" +
    //                                 "</tr>");


    // $('#other_charges').modal('toggle');
    // getTotalCharges();
});


$('#')






$('#append_less30_basic_fixedORPercentage').on('click', function() {

    var charges_type = 'Basic/Monthly Rental';
    var description = $('#rental_type').val();
    var rental_type = $('#rental_type').val();
    var charges_code = "";
    var uom = "";
    var unit_price = "";
    var total_unit = "";
    var actual_amount;


    actual_amount = $('#higher_rental').val();
    actual_amount = Number(toNumber(actual_amount)).toFixed(2);
    added_vat = $('#fixedPercentage_addedVat').val();

    if (added_vat == undefined)
    {
        added_vat = '0.00';
    } else {
        added_vat = Number(added_vat).toFixed(2);
    }



    less_witholding = $('#fixedPercentage_lessWHT').val();
    less_witholding = Number(less_witholding).toFixed(2);


    percent_increment = $('#percent_increment').val();
    increment_value = $('#increment_value').val();


    $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />" + unit_price +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");


        var tenant_type = document.getElementsByName("fixedORPercentage_tenant_type[]");
        var discount_type = document.getElementsByName("fixedORPercentage_discount_type[]");
        var discount = document.getElementsByName("fixedORPercentage_discount[]");


    if (percent_increment != undefined)
    {
        $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Rent Incrementation' />Basic</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Rental Incrementation' />Rental Incrementation</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + percent_increment + "' />" + percent_increment + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + increment_value +"' />&#8369;" + increment_value + "</td>" +
                                    "<td></td>" +
                                "</tr>");
    }

        var discount_amount = 0;

        for (var i = 0; i < tenant_type.length / 3; i++)
        {
            if (discount_type[i].value == "Fixed Amount")
            {
                discount_amount = Number(discount[i].value).toFixed(2);

                $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
            } else{

                discount_amount = (Number(discount[i].value) / 100) * actual_amount;

                $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
            }

        }


        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'VAT Output' />Vat Output</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + added_vat +"' />&#8369;" + added_vat + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Creditable Witholding Taxes' />Creditable Witholding Taxes</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + less_witholding +"' />&#8369;" + less_witholding + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

    $('#less_than_30Days_FixedORPercentage_tenant').modal('toggle');
    getTotalCharges();

});

$('#append_fixedORPercentage').on('click', function() {

    var charges_type = 'Basic/Monthly Rental';
    var description = $('#rental_type').val();
    var rental_type = $('#rental_type').val();
    var charges_code = "";
    var uom = "";
    var unit_price = "";
    var total_unit = "";
    var actual_amount;


    actual_amount = $('#higher_rental').val();
    actual_amount = Number(toNumber(actual_amount)).toFixed(2);
    added_vat = $('#fixedPercentage_addedVat').val();

    if (added_vat == undefined)
    {
        added_vat = '0.00';
    } else {
        added_vat = Number(added_vat).toFixed(2);
    }



    less_witholding = $('#fixedPercentage_lessWHT').val();
    less_witholding = Number(less_witholding).toFixed(2);


    var percent_increment = $('#fixedORPercentage_percent_increment').val();
    var increment_value = $('#fixedORPercentage_increment_value').val();


    $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />" + unit_price +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");


        var tenant_type = document.getElementsByName("tenant_type[]");
        var discount_type = document.getElementsByName("discount_type[]");
        var discount = document.getElementsByName("discount[]");


    if (percent_increment != undefined)
    {
        $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Rent Incrementation' />Basic</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Rental Incrementation' />Rental Incrementation</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + percent_increment + "' />" + percent_increment + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + increment_value +"' />&#8369;" + increment_value + "</td>" +
                                    "<td></td>" +
                                "</tr>");
    }

        var discount_amount = 0;

        for (var i = 0; i < tenant_type.length / 3; i++)
        {
            if (discount_type[i].value == "Fixed Amount")
            {
                discount_amount = Number(discount[i].value).toFixed(2);

                $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
            } else{

                discount_amount = (Number(discount[i].value) / 100) * actual_amount;

                $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                        "<td></td>" +
                                    "</tr>");
            }

        }


        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'VAT Output' />Vat Output</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + added_vat +"' />&#8369;" + added_vat + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Creditable Witholding Taxes' />Creditable Witholding Taxes</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + less_witholding +"' />&#8369;" + less_witholding + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

    $('#fixedORPercentage').modal('toggle');
    getTotalCharges();

});




$('#append_shortTerm_charges').on('click', function() {

    var charges_type = 'Basic/Monthly Rental';
    var description = 'Basic Rental';
    var charges_code = "";
    var uom = "";
    var unit_price = "";
    var total_unit = "";
    var actual_amount = $('#sTerm_rental').val();
    actual_amount = Number(actual_amount).toFixed(2);
    added_vat = $('#added_vat').val();
    added_vat = Number(added_vat).toFixed(2);

    less_witholding = $('#less_witholding').val();
    less_witholding = Number(less_witholding).toFixed(2);


    $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = '" + charges_type +"' /> " + charges_type + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '" + charges_code +"' /> " + charges_code + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + description +"' /> " + description + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + uom +"' />" + uom + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + unit_price +"' />" + unit_price +  "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '" + total_unit +"' />" + total_unit + "</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + actual_amount +"' />&#8369;" + actual_amount + "</td>" +
                                    "</tr>");

    var tenant_type = document.getElementsByName("tenant_type[]");
    var discount_type = document.getElementsByName("discount_type[]");
    var discount = document.getElementsByName("discount[]");

    var discount_amount = 0;

    for (var i = 0; i < tenant_type.length / 3; i++)
    {
        if (discount_type[i].value == "Fixed Amount")
        {
            discount_amount = Number(discount[i].value).toFixed(2);

            $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" +  discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");
        } else {

            discount_amount = (Number(discount[i].value) / 100) * actual_amount;
            $('#charges_table tbody').append(
                                "<tr>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Discount' />Discount</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'description[]' value = '" + tenant_type[i].value +"' />" + tenant_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'uom[]' value = '" + discount_type[i].value + "' />" + discount_type[i].value + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '" + discount[i].value + "' />" + Number(discount[i].value).toFixed(2) + "</td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                    "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + discount_amount + "' />&#8369;" + discount_amount + "</td>" +
                                    "<td></td>" +
                                "</tr>");
        }

    }

    $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'VAT Output' />Vat Output</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + added_vat +"' />&#8369;" + added_vat + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

        $('#charges_table tbody').append(
                                    "<tr>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_type[]' value = 'Basic' />Basic</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'charges_code[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'description[]' value = 'Creditable Witholding Taxes' />Creditable Witholding Taxes</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'uom[]' value = 'Percentage' />Percentage</td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'unit_price[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'total_unit[]' value = '' /></td>" +
                                        "<td><input type ='text' style = 'display:none' name = 'actual_amount[]' value = '" + less_witholding +"' />&#8369;" + less_witholding + "</td>" +
                                        "<td></td>" +
                                    "</tr>");

        $('#shortTerm_charges').modal('toggle');
        getTotalCharges();
});


function deleteme(r)
{
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("charges_table").deleteRow(i);
    getTotalCharges();
}


function deletefromPayment(r)
{
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("payment_tbody").deleteRow(i-1);
}


function deleteFormSOA(r)
{
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("soa_table").deleteRow(i);
}





function exclude_soa(r)
{
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("soa_table").deleteRow(i);

    var inps = document.getElementsByName('total_amountDue[]');
    var total_amount = 0;
    for (var i = 0; i <inps.length; i++)
    {
        var inp=inps[i];
        total_amount += toNumber(inp.value);
    }

    total_amount = Number(total_amount).toFixed(2);
    document.getElementById("totalAmount").value = total_amount;
}

function toNumber(data)
{
    data = data.replace(/,/g , "");
    data = parseFloat(data);
    return data;
}

function getTotalCharges()
{
    var inps = document.getElementsByName('actual_amount[]');
    var description = document.getElementsByName('description[]');
    var charges_type = document.getElementsByName('charges_type[]');

    var actual_amount;
    var total_amount = 0;
    for (var i = 0; i <inps.length; i++)
    {
        var inp=inps[i];
        actual_amount = toNumber(inp.value);

        var desc = description[i];
        desc = desc.value;

        var type = charges_type[i];
        type = type.value;

        if (desc == 'Creditable Witholding Taxes' || type == 'Discount')
        {
            total_amount -= actual_amount;
        } else {
            total_amount += actual_amount;
        }
    }

    document.getElementById("total_amount").value = total_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}


function clear_invoicingTable()
{
    $('#charges_table tbody').html("");
}

function reprint_soa(file)
{
    $('#print_preview').html('<iframe id = "printPreview_frame" src="' + file + '" frameborder="0" style = "height:80vh; width:100%"></iframe>');
    $('#print_modal').modal({
        backdrop: 'static',
        keyboard: false
    });
}


function callPrint(iframeId)
{
    var PDF = document.getElementById(iframeId);
    PDF.focus();
    PDF.contentWindow.print();
}


function trigger_managersKey()
{
    $('#contract_termination').modal('toggle');
    // Trigger manager's key modal
    $('#manager_modal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function creditMemo_managerModal(modalname)
{
    $('#' + modalname).modal('toggle');
    // Trigger manager's key modal
    $('#manager_modal').modal({
        backdrop: 'static',
        keyboard: false
    });
}


function trigger_confirmation()
{
    $('#contract_termination').modal('toggle');
    // Trigger manager's key modal
    $('#confirmation1_modal').modal({
        backdrop: 'static',
        keyboard: false
    });
}


function clear_soaTable()
{
    $('#soa_tbody tr td').remove();
}

function clear_paymentData()
{
    $('#payment_tbody tr td').remove();
    $("#tenant_id").val('');
    $("#trade_name").val('');
    $("#soa_no").val('');
    $("#remarks").val('');
    $("#contract_no").val('');
    $("#total_amount").val('');

    $('#tender_typeCode').append('<option value="" disabled="" selected="" style="display:none">Please Select One</option>');
    $("#tender_typeDesc").val('');
    $("#amount_paid").val('0.00');
    $("#bank").val('');
    $("#check_no").val('');
    $("#check_date").val('');
    $("#payor").val('');

}


function clear_paymentHistory()
{
    $('#paymentHistory_tbody tr td').remove();
    $("#tenant_id").val('');
    $("#trade_name").val('');
    $("#address").val('');
    $("#contract_no").val('');
    $("#tin").val('');
    $("#rental_type").val('');
}



function closeModal(id)
{
    $('#' + id).modal('toggle');
}

function openModal(id)
{
    $('#' + id).modal({
                backdrop: 'static',
                keyboard: false
            });
}

function openBasic(tenant_type)
{

    var charges_type = $('#charges_type').val();
    var rental_type = $('#rental_type').val();
    var tenancy_type = $('#tenancy_type').val();

    if (tenant_type != 'Government Agencies(w/o Basic)')
    {
        if (rental_type == 'Fixed' || rental_type == 'Fixed Plus Percentage' || rental_type == 'Percentage' || rental_type == 'Percentage Base Tenant')
        {
            $('#basicRental_modal').modal({
                backdrop: 'static',
                keyboard: false
            });

            // $('#charges_type').append('<option value="" disabled="" selected="" style="display:none">Please Select One</option>');

        } else if(rental_type == 'Fixed/Percentage w/c Higher') {
            $('#fixedORPercentage').modal({
                backdrop: 'static',
                keyboard: false
            });

            // $('#charges_type').append('<option value="" disabled="" selected="" style="display:none">Please Select One</option>');

        }
    }
    // else if (tenancy_type == 'Short Term Tenant' && charges_type == 'Basic Charges') {
    //     $('#shortTerm_charges').modal({
    //         backdrop: 'static',
    //         keyboard: false
    //     });

        // $('#charges_type').append('<option value="" disabled="" selected="" style="display:none">Please Select One</option>');
    // }
}

function clear_selectedChargesType()
{
    // $('#charges_type').append('<option value="" disabled="" selected="" style="display:none">Please Select One</option>');
}


function apply_CreditMemo_modal(charges_type, id, amount)
{
    if (charges_type == 'Basic')
    {
        $('#basic_creditMemo_modal').modal({
            backdrop: 'static',
            keyboard: false
        });

        document.getElementById('ledger_id').value = id;
    }
    else
    {
        $('#modal_creditMemo').modal({
            backdrop: 'static',
            keyboard: false
        });

        document.getElementById('ledger_id').value = id;
    }
}


function apply_DebitMemo_modal(charges_type, id, amount)
{
    if (charges_type == 'Basic')
    {
        $('#basic_debitMemo_modal').modal({
            backdrop: 'static',
            keyboard: false
        });

        document.getElementById('ledger_id').value = id;
    }
    else
    {
        $('#modal_debitMemo').modal({
            backdrop: 'static',
            keyboard: false
        });

        document.getElementById('ledger_id').value = id;
    }
}


function comput_monthly_amount(unit_price, total_unit, actual_amount)
{
    alert(unit_price);
}


// function is_cash()
// {
//     var param = document.getElementById('payment_tender_type').value
//     alert(param);
// }

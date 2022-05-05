

function add_lprospect()
{
    $('#add_data').modal('toggle');
    var formData = new FormData($('form#frm_addlprospect')[0]);
    $.ajax({
        type:'POST',
        url:'add_lprospect/',
        data: formData,
        enctype: 'multipart/form-data',
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function()
        {
            $('#spinner_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#adding').html('Processing...');
        },
        success: function(data)
        {
            console.log(data);
            $('#adding').html('Submit');
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
                notify('success', 'Successfully saved.');
            } else {
                $('#add_data').modal('show');
                generate('alert', data['msg']);
            }
        },
        error: function (e, status)
        {
            $('#spinner_modal').modal('toggle');
            if (e.status == 500)
            {
                notify('alert', 'Internal Error. Please contact the administrator');
            }
        }
    });
}



function save_setForOtherStore(url)
{

    var formData = new FormData($('form#frm_prospect')[0]);
    $.ajax({
        type:'POST',
        url: url,
        data: formData,
        enctype: 'multipart/form-data',
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function()
        {
            $('#spinner_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#adding').html('Processing...');
        },
        success: function(data)
        {
            console.log(data);
            $('#adding').html('Submit');
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
                confirmation('success', 'Successfully Saved.' , 'lst_Ltenants');
            } else {
                $('#add_data').modal('show');
                generate('alert', data['msg']);
            }
        },
        error: function (e, status)
        {
            $('#spinner_modal').modal('toggle');
            if (e.status == 500)
            {
                notify('alert', 'Internal Error. Please contact the administrator');
            }
        }
    });
}


function add_sprospect()
{
    $('#add_data').modal('toggle');
    var formData = new FormData($('form#frm_addsprospect')[0]);
    $.ajax({
        type:'POST',
        url:'add_sprospect/',
        data: formData,
        enctype: 'multipart/form-data',
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function()
        {
            $('#spinner_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#adding').html('Processing...');
        },
        success: function(data)
        {
            console.log(data);
            $('#adding').html('Submit');
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
                notify('success', 'Successfully saved.');
            } else {
                $('#add_data').modal('show');
                generate('alert', data['msg']);
            }
        },
        error: function (e, status)
        {
            $('#spinner_modal').modal('toggle');
            if (e.status == 500)
            {
                notify('alert', 'Internal Error. Please contact the administrator');
            }
        }
    });
}




function checkTag(url)
{
    var tag = $('input[name=tag]:checked', '#frm_tag').val();
    var formData = new FormData($('form#frm_contract')[0]);

    if (tag == 'Pending')
    {

        $.ajax({
            type:'POST',
            url: url,
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
            success: function(data)
            {
                console.log(data);
                $('#spinner_modal').modal('toggle');
                data = JSON.parse(data);

                if (data['msg'] == 'Success')
                {
                    if (data['tenancy_type'] == 'Long Term')
                    {
                        destination = 'lst_Lforcontract';
                    } else {
                        destination = 'lst_Sforcontract';
                    }

                    confirmation('success', 'Contract Successfully Saved.' ,destination);
                } else {
                    generate('error', data['msg']);
                }
            },
            error: function (e, status)
            {
                $('#spinner_modal').modal('toggle');
                if (e.status == 500)
                {
                    notify('alert', 'Internal Error. Please contact the administrator');
                }
            }
        });
    }
    else
    {
        $('#managerkey_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }
}

function add_tenant(url)
{

    var formData = new FormData($('form#frm_contract')[0]);
    var destination = "";

    var username = $('input[name=username]:input', '#frm_managerKey').val();
    var password = $('input[name=password]:input', '#frm_managerKey').val();


    $.ajax({
        type:'POST',
        url: url + username + '/' + password,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                if (data['tenancy_type'] == 'Long Term') {
                    destination = 'lst_Lforcontract';
                } else {
                    destination = 'lst_Sforcontract';
                }

                confirmation('success', 'Contract Successfully Saved.' ,destination);
            } else {
                generate('error', data['msg']);
            }

        },
        error: function (e, status)
        {
            $('#spinner_modal').modal('toggle');
            if (e.status == 500)
            {
                notify('alert', 'Internal Error. Please contact the administrator');
            }
        }
    });
}




function waive_penalty(url)
{
    var formData = new FormData($('form#frm_penalty')[0]);
    var destination = "";

    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Required')
            {
                generate('error', 'Please fill out all required fields');
            }
            else if(data['msg'] == 'Success')
            {
                confirmation('success', 'Successfully Waived.' , 'penalties');
            }
            else
            {
                generate('error', 'Database Error');
            }

        },
        error: function (e, status)
        {
            $('#spinner_modal').modal('toggle');
            if (e.status == 500)
            {
                notify('alert', 'Internal Error. Please contact the administrator');
            }
        }
    });
}

function amend_tenant(url)
{
    var formData = new FormData($('form#frm_amendment')[0]);

    var username = $('input[name=username]:input', '#frm_managerKey').val();
    var password = $('input[name=password]:input', '#frm_managerKey').val();

    $.ajax({
        type:'POST',
        url: url + username + '/' + password,
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
        success: function(data)
        {
            console.log(data);
            $('#spinner_modal').modal('toggle');

            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                if (data['tenancy_type'] == 'Long Term') {
                    confirmation('success', 'Contract Successfully Saved.' , 'lst_Ltenants')
                } else {
                    confirmation('success', 'Contract Successfully Saved.' , 'lst_Stenants')
                }
            }
            else if(data['msg'] == 'Required')
            {
                generate('error', 'Please fill out all required fields');
            }
            else
            {
                generate('error', data['msg']);
            }

        },
        error: function (e, status)
        {
            $('#spinner_modal').modal('toggle');
            if (e.status == 500)
            {
                notify('alert', 'Internal Error. Please contact the administrator');
            }
        }
    });
}


function amend_stenant(url)
{
    var formData = new FormData($('form#frm_amendment')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            console.log(data);
            $('#spinner_modal').modal('toggle');

            data = JSON.parse(data);

            if (data['msg'] == 'Updated')
            {
                contract_amended('lst_Stenants');
            } else {
                generate('error', data['msg']);
            }

        },
        error: function (e, status)
        {
            $('#spinner_modal').modal('toggle');
            if (e.status == 500)
            {
                notify('alert', 'Internal Error. Please contact the administrator');
            }
        }
    });
}







function add_stenant(url)
{
    var formData = new FormData($('form#frm_contract')[0]);
    $.ajax({
        type:'POST',
        url: url,
        data: formData,
        enctype: 'multipart/form-data',
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function()
        {
            $('#spinner_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        },
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');

            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                sTerm_success();
            } else {
                generate('error', data['msg']);
            }

        },
        error: function (e, status)
        {
            $('#spinner_modal').modal('toggle');
            if (e.status == 500)
            {
                notify('alert', 'Internal Error. Please contact the administrator');
            }
        }
    });
}


function check_invoiceTag()
{
    $('#tagAs_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
}


function save_retro(url)
{
    var formData = new FormData($('form#frm_retro')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                notify('success', 'Invoice has been Saved.');
            }
            else if(data['msg'] == 'Duplicate')
            {
                generate('error', 'Duplicate Invoice for Due Date');
            }
            else if(data['msg'] == 'No Charges Added')
            {
                generate('error', 'No Charges Added!');
            }
            else if(data['msg'] == 'Error')
            {
                generate('error', 'Error on saving invoice. Please Contract the Administrator.');
            }

        }
    });
}


function save_invoice(url)
{

    var tag = $('input[name=tag]:checked', '#frm_tag').val();

    var formData = new FormData($('form#frm_invoice')[0]);
    $.ajax({
        type:'POST',
        url: url + tag,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                notify('success', 'Invoice has been Saved.');
            }
            else if(data['msg'] == 'Duplicate')
            {
                generate('error', 'Duplicate Invoice for Due Date');
            }
            else if(data['msg'] == 'No Charges Added')
            {
                generate('error', 'No Charges Added!');
            }
            else if(data['msg'] == 'Error')
            {
                generate('error', 'Error on saving invoice. Please Contract the Administrator.');
            }

        }
    });
}



function save_draftInvoice(url)
{
    // var tag = $('input[name=tag]:checked', '#frm_tag').val();
    var tag = "Posted";
    var formData = new FormData($('form#frm_invoice')[0]);
    $.ajax({
        type:'POST',
        url: url + '/' + tag,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                confirmation('success', 'Successfully saved.', 'draft_invoice');

            } else if (data['msg'] == 'No Charges Added!'){
                generate('error', 'No Charges Added!');
            } else if (data['msg'] == 'DB_error!') {
                generate('error', 'Database Error. Please Contract the Administrator.');
            }
        }
    });
}


function update_locationCode(url)
{
    var username = $('input[name=username]:input', '#frm_managerKey').val();
    var password = $('input[name=password]:input', '#frm_managerKey').val();
    var formData = new FormData($('form#frm_update')[0]);
    $.ajax({
        type:'POST',
        url: url + username + '/' + password,
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
        success: function(data)
        {
            console.log(data);
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                redirect('success', 'Successfully Updated.', 'locationCode_setup');
            } else if (data['msg'] == "Invalid Key") {
                generate('error', 'Invalid Manager Key');
            } else if (data['msg'] == "Required") {
                generate('error', 'Please fill out all required fields');
            }
        }
    });
}

function save_creditMemo(url)
{
    var username = $('input[name=username]:input', '#frm_managerKey').val();
    var password = $('input[name=password]:input', '#frm_managerKey').val();

    var formData = new FormData($('form#frm_creditMemo')[0]);
    $.ajax({
        type:'POST',
        url: url + '/' + username + '/' + password,
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
        success: function(data)
        {
            console.log(data);
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                confirmation('success', 'Successfully Updated.', 'credit_memo');
            } else if (data['msg'] == "Invalid Key") {
                generate('error', 'Invalid Manager Key');
            } else if (data['msg'] == "No Charges Added") {
                generate('error', 'No Charges Added');
            }
        }
    });
}

function save_soa(url)
{
    var formData = new FormData($('form#frm_soa')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            console.log(data);
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];

                // $('#print_preview').html('<iframe id = "printPreview_frame" src="' + filename + '" frameborder="0" style = "height:80vh; width:100%"></iframe>');
                // $('#print_modal').modal({
                //     backdrop: 'static',
                //     keyboard: false
                // });

                //==== clear fields location in dynamic-elements.js ====//
                window.open(filename);
                notify('success', 'SOA Successfully Generated');


            }
            else if (data['msg'] = 'Duplicate')
            {
                generate('error', 'Duplicate SOA for this Billing Period.');
            }
            else
            {
                generate('error', 'No Charges Added!');
            }
        }
    });
}



function generate_paymentProofList(url)
{

    var formData = new FormData($('form#frm_paymentProofList')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            console.log(data);
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];


                window.open(filename);
                notify('success', 'Payment ProofList Successfully Generated');
            }
            else if (data['msg'] == 'No Payment History')
            {
                generate('error', data['msg']);
            }
            else
            {
                generate('error', 'Please Contact the Administrator.');
            }
        }
    });
}



function save_payment(url)
{
    var formData = new FormData($('form#frm_payment')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];
                // $('#print_preview').html('<iframe id = "printPreview_frame" src="' + filename + '" frameborder="0" style = "height:80vh; width:100%"></iframe>');
                // $('#print_modal').modal({
                //     backdrop: 'static',
                //     keyboard: false
                // });
                // clear_paymentData();
                // $("#receipt_no").val('');
                window.open(filename);
                notify('success', 'Payment Successfully Saved.');

            }
            else if(data['msg'] == 'Required')
            {
                generate('error', 'Please fill out all required fields.');
            }
            else if (data['msg'] == 'No Amount')
            {
                generate('error', 'Tenderered description has no amount.');
            }
            else
            {
                generate('error', 'Internal Error. Please contract the administrator');
            }
        }
    });
}


function save_closingRentDue(url)
{
    var formData = new FormData($('form#frm_closingRentDue')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                notify('success', 'Successfully Saved.');
            }
            else if (data['msg'] == 'Required')
            {
                generate('error', 'Please fill up all required fields.');
            }
            else
            {
                generate('error', 'Internal Error. Please contract the administrator');
            }
        }
    });
}


function save_recognizeRentDue(url)
{
    var formData = new FormData($('form#frm_recognizeRent')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                notify('success', 'Successfully Saved.');
            }
            else
            {
                generate('error', 'Internal Error. Please contract the administrator');
            }
        }
    });
}

function setup_3D(url)
{
    var formData = new FormData($('form#frm_3DModel')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                notify('success', 'Successfully saved.');
            }
            else
            {
                generate('error', 'Internal Error. Please contract the administrator');
            }
        }
    });
}


function generate_accountabilityReport(url)
{
    var formData = new FormData($('form#frm_accountability')[0]);
    $.ajax({
        type:'POST',
        url: url,
        data: formData,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                // $('#print_preview').html('<iframe id = "printPreview_frame" src="' + filename + '" frameborder="0" style = "height:80vh; width:100%"></iframe>');
                // $('#print_modal').modal({
                //     backdrop: 'static',
                //     keyboard: false
                // });

                var filename = data['file_name'];
                window.open(filename);
                location.reload();
            }
            else
            {
                generate('error', 'Internal Error. Please contract the administrator');
            }
        }
    });

}


function generate_paymentList(url)
{

    var formData = new FormData($('form#frm_paymentList')[0]);
    $.ajax({
        type:'POST',
        url: url,
        data: formData,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                // $('#print_preview').html('<iframe id = "printPreview_frame" src="' + filename + '" frameborder="0" style = "height:80vh; width:100%"></iframe>');
                // $('#print_modal').modal({
                //     backdrop: 'static',
                //     keyboard: false
                // });

                var filename = data['file_name'];
                window.open(filename);
                location.reload();
            }
            else
            {
                generate('error', 'Internal Error. Please contract the administrator');
            }
        }
    });

}

function save_unreg_fundTransfer()
{

    var formData = new FormData($('form#frm_clearing')[0]);
    $.ajax({
        type:'POST',
        url: 'save_unreg_fundTransfer/',
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            console.log(data);

            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                notify('success', 'Successfully saved.');
            }
            else if(data['msg'] == 'Required')
            {
                generate('error', 'Please fill out all required fields.');
            }
            else
            {
                generate('error', 'Database error. Please Contact the Administrator');
            }
        }
    });

}


function save_internalPayment(url) {
    $('#spinner_modal').modal('toggle');
    var formData = new FormData($('form#frm_recordInternalPayment')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            console.log(data);

            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                notify('success', 'Successfully saved.');
            }
            else if(data['msg'] == 'Required')
            {
                generate('error', 'Please fill out all required fields.');
            }
            else
            {
                generate('error', 'Database error. Please Contact the Administrator');
            }
        }
    });
}


function save_reverseInternalPayment(url) {
    var formData = new FormData($('form#frm_clearing')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            console.log(data);

            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];
                // $('#print_preview').html('<iframe id = "printPreview_frame" src="' + filename + '" frameborder="0" style = "height:80vh; width:100%"></iframe>');
                // $('#print_modal').modal({
                //     backdrop: 'static',
                //     keyboard: false
                // });

                // clear_paymentData();
                // $("#receipt_no").val('');

                window.open(filename);
                location.reload();
            }
            else if(data['msg'] == 'Required')
            {
                generate('error', 'Please fill out all required fields.');
            }
            else
            {
                generate('error', 'Database error. Please Contact the Administrator');
            }
        }
    });
}


function save_reg_fundTransfer()
{

    var formData = new FormData($('form#frm_clearing')[0]);
    $.ajax({
        type:'POST',
        url: 'save_reg_fundTransfer',
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
        success: function(res)
        {
            $('#spinner_modal').modal('toggle');
            console.log(res);

            if(res.type == 'success'){
                window.open(res.file_dir);
                notify(res.type, res.msg);
                return;
            }

            generate(res.type, res.msg);
        }
    });
}


function print_invoiceHistory(url)
{
    var formData = new FormData($('form#frm_invoiceHistory')[0]);
    // console.log(formData);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            console.log(data);

            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];

                $('#print_preview').html('<iframe id = "printPreview_frame" src="' + filename + '" frameborder="0" style = "height:80vh; width:100%"></iframe>');
                $('#print_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                clear_paymentHistory();
            } else if (data['msg'] == 'No Payment History') {
                generate('error', 'No Payment History');
            }
        }
    });

}


function print(url)
{
    $.ajax({
        url: url,
        beforeSend: function() {
            $('#spinner_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        },
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            console.log(data);

            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];
                window.open(filename);
            } else if (data['msg'] == 'No Payment History') {
                generate('error', 'Please Contact the Administrator');
            }
        }
    });
}


function print_subsidiaryLedger(url)
{
    var tenant_id = $('#tenant_id').val();
    $.ajax({
        url: url + tenant_id,
        beforeSend: function() {
            $('#spinner_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        },
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            console.log(data);

            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];
                window.open(filename);
            } else if (data['msg'] == 'No Entry') {
                generate('error', 'Please Contact the Administrator');
            }
        }
    });
}


function generate_monthly_receivable(url)
{
    var formData = new FormData($('form#frm_monthlyReceivable')[0]);
    // console.log(formData);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];
                window.open(filename);
            } else  {
                generate('error', 'Please Contact the Administrator.');
            }
        }
    });
}


function generate_aging(url)
{
    var date = $('#date_created').val();
    $.ajax({
        type:'POST',
        url: url + date,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];
                window.open(filename);
            } else  {
                generate('error', 'Please Contact the Administrator.');
            }
        }
    });
}


function delinquent_tenants(url)
{
    var formData = new FormData($('form#frm_delinquentTenants')[0]);
    // console.log(formData);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];
                window.open(filename);
            } else  {
                generate('error', 'Please Contact the Administrator.');
            }
        }
    });
}


function generate_receivable_summary(url)
{

    var formData = new FormData($('form#frm_receivableSummary')[0]);

    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];
                window.open(filename);
            } else  {
                generate('error', 'Please Contact the Administrator.');
            }
        }
    });

}



function payment_report(url)
{

    var formData = new FormData($('form#frm_paymentReport')[0]);

    $.ajax({
        type:'POST',
        url: url,
        data: formData,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                var filename = data['file_name'];
                window.open(filename);
            } else  {
                generate('error', 'Please Contact the Administrator.');
            }
        }
    });

}

function migrate_bigBal(url)
{

    var formData = new FormData($('form#frm_migrate_bigBal')[0]);
    $.ajax({
        type:'POST',
        url: url,
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
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);

            if (data['msg'] == 'Success')
            {
                notify('success', 'Data Successfully Migrated!');
            } else  {
                generate('error', 'Please Contact the Administrator.');
            }
        }
    });
}
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
//FACILITY RENTAL 
function add_FacilityRentalCustomer(url)
{
    var formData = new FormData($('form#frm_addcustomer')[0]);

    var customername = $('#fr_addcustomername').val();
    var customercontactperson = $('#fr_addcustomercontactperson').val();
    var customercontactnum = $('#fr_addcustomercontactnum').val();
    var addcustomeraddress = $('#fr_addcustomeraddress').val();

    if(customername.length === 0 || customercontactperson.length === 0 || customercontactnum.length === 0 || addcustomeraddress.length === 0)
    {
        alert('Fill all fields.')
    }
    else{
        $('#add_FacilityRentalCustomer').prop('disabled', true);
        $.ajax({
            type:'POST',
            url:url,
            data: formData,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data){
                console.log(data.trim());
                if(data.trim() == '"1"')
                {
                    location.reload();
                }
                else if(data.trim() == '"2"')
                {
                    $('#add_FacilityRentalCustomerbtn').prop('disabled', false);
                    alert('Customer already exists');
                }
                else
                {
                    $('#add_FacilityRentalCustomerbtn').prop('disabled', false);
                    alert('Database Error');
                }
                
            },
            error: function (e, status)
            {
                $('#add_FacilityRentalCustomerbtn').prop('disabled', false);
                alert('Connection Error');
            }
        });
    }
}

function update_FacilityRentalCustomer(url)
{
    var formData = new FormData($('form#frm_updatecustomer')[0]);

    var customername = $('#fr_updatecustomername').val();
    var customercontactperson = $('#fr_updatecustomercontactperson').val();
    var customercontactnum = $('#fr_updatecustomercontactnum').val();
    var addcustomeraddress = $('#fr_updatecustomeraddress').val();

    if(customername.length === 0 || customercontactperson.length === 0 || customercontactnum.length === 0 || addcustomeraddress.length === 0)
    {
        alert('Fill all fields.')
    }
    else{
        $('#update_FacilityRentalCustomer').prop('disabled', true);
        $.ajax({
            type:'POST',
            url:url,
            data: formData,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data){
                console.log(data.trim());
                if(data.trim() == '"1"')
                {
                    location.reload();
                }
                else
                {
                    $('#update_FacilityRentalCustomerbtn').prop('disabled', false);
                    alert('Customer already exists');
                }
                
            },
            error: function (e, status)
            {
                $('#update_FacilityRentalCustomerbtn').prop('disabled', false);
                alert('Connection Error');
            }
        });
    }
}

function add_FacilityRentalFacility(url)
{
    var formData = new FormData($('form#frm_fraddfacility')[0]);

    var facilityname = $('#fr_facilityname').val();
    var facilitydescription = $('#fr_facilitydescription').val();
    var facilityrate = $('#fr_facilityrate').val();



    if(facilityname.length === 0 || facilitydescription.length === 0 || facilityrate.length === 0)
    {
        alert('Fill all fields.')
    }
    else{
        $('#add_FacilityRentalFacility').prop('disabled', true);
        $.ajax({
            type:'POST',
            url:url,
            data: formData,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data){
                console.log(data.trim());
                if(data.trim() == '"1"')
                {
                    location.reload();
                }
                else if(data.trim() == '"2"')
                {
                        $('#add_FacilityRentalFacilitybtn').prop('disabled', false);
                         alert('Facility already exists.');
                }
                else
                {
                    $('#add_FacilityRentalFacilitybtn').prop('disabled', false);
                    alert('Database Error');
                }
                
            },
            error: function (e, status)
            {
                $('#add_FacilityRentalFacilitybtn').prop('disabled', false);
                alert('Connection Error');
            }
        });
    }
}


function update_FacilityRentalFacility(url)
{
    var formData = new FormData($('form#frm_updatefacility')[0]);

    var facilityname = $('#fr_updatefacilityname').val();
    var description = $('#fr_updatefacilitydescription').val();
    var facilityrate = $('#fr_updatefacilityrate').val();


    if(facilityname.length === 0 || description.length === 0 || facilityrate.length === 0)
    {
        alert('Fill all fields.')
    }
    else{
        $('#update_FacilityRentalFacility').prop('disabled', true);
        $.ajax({
            type:'POST',
            url:url,
            data: formData,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data){
                console.log(data.trim());
                if(data.trim() == '"1"')
                {
                    
                    location.reload();
                }
                else if(data.trim() == '"2"')
                {
                      $('#update_FacilityRentalFacilitybtn').prop('disabled', false);
                       alert('Facility already exists.');
                }
                else
                {
                    $('#update_FacilityRentalFacilitybtn').prop('disabled', false);
                    alert('Database Error');
                }
                
            },
            error: function (e, status)
            {
                $('#update_FacilityRentalFacilitybtn').prop('disabled', false);
                alert('Connection Error');
            }
        });
    }
}

function check_frtimecheck_box(cb)
{
    if($('#frreserve_date').val().length === 0)
    {
        $('.frtimecheck_box').prop("checked", false);
        alert('Please select reservation date first.');
    }
    var tmp_reservetime = $("input[class='frtimecheck_box']:checked").map(function() {
        return this.value;
    }).get();   
    console.log(tmp_reservetime);
}

function check_facilityrequested(cb)
{
    var hoursusage = $('#frhoursusage').val();
    if(hoursusage != "0")
    {
        var running_balance = $('#frrentalprice').val();
        var facilityprice = cb.value;
        var totalhours = $('#frhoursusage').val();

        if($(cb).is(":checked"))
        {
            running_balance = Number(running_balance) + (Number(facilityprice) * Number(totalhours));
        }
        else
        {
            running_balance = Number(running_balance) - (Number(facilityprice) * Number(totalhours));
        }

        $('#frrentalprice').val(running_balance.toFixed(2));
    }
    else
    {
        $('.RequestedFacilities').prop("checked", false);
        alert('No schedule for reservation.');
    }
}

function add_FacilityRentalTransaction(url,url1)
{
    var formData = new FormData($('form#frm_addfacilityrental')[0]);

    var facility_requested = $("input[class='RequestedFacilities']:checked").map(function() {
        return this.id;
    }).get();   
      
    var hoursusage = $('#frhoursusage').val();

    if(facility_requested.length == 0)
    {
        alert('No facilities requested.');
    }
    else if(hoursusage == "0")
    {
        alert("No schedule for reservation.")
    }
    else if($('#fraddress').val().length === 0)
    {
        alert('Please select customer information.');
    }
    else if($('#frcontactperson').val().length === 0)
    {
        alert('Please select customer information.');
    }
    else if($('#frcontactnumber').val().length === 0)
    {
        alert('Please select customer information.');
    }
    else if($('#frapprovedintentletter').val().length === 0)
    {
        alert('Please select file to be uploaded.');
    }
    else
    {
        var facilityrequestparam={facility_requested:facility_requested};
        $('#add_FacilityRentalTransactionBtn').prop('disabled', true);
        $.ajax({
            type:'POST',
            url:url,
            data: $('#frm_addfacilityrental').serialize()+ '&' + $.param(facilityrequestparam),
            success:function(data){
                if(data.trim() == '"1"')
                {
                    $.ajax({
                        type:'POST',
                        url:url1,
                        data: formData,
                        enctype: 'multipart/form-data',
                        async: true,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success:function(data){
                            if(data.trim() == '"1"')
                            {
                                location.reload();
                            }
                            else
                            {
                                alert('Database Error');
                                $('#add_FacilityRentalTransactionBtn').prop('disabled', false);
                            }
                        },
                        error: function (e, status)
                        {
                            alert(e.status);
                            $('#add_FacilityRentalTransactionBtn').prop('disabled', false);
                        }
                    });
                }
                else
                {
                    alert('Database Error');
                    $('#add_FacilityRentalTransactionBtn').prop('disabled', false);
                }
            },
            error: function (e, status)
            {
                alert(e.status);
                $('#add_FacilityRentalTransactionBtn').prop('disabled', false);
            }
        });
    }
}

function add_frtmpreservedatetime(url)
{
    var tmp_reservetime = $("input[class='frtimecheck_box']:checked").map(function() {
        return this.value;
    }).get();   

    if(tmp_reservetime.length == 0)
    {
        alert('No time selected.');
    }
    else if($('#frreserve_date').val().length === 0)
    {
        alert('Reserve date is empty.');
    }
    else
    {
        var tmp_reservetime={tmp_reservetime:tmp_reservetime};
        $('#add_frtmpreservedatetimeBtn').prop('disabled', true);
        $.ajax({
            type:'POST',
            url:url,
            data: $('#frm_frscheduledatetime').serialize()+ '&' + $.param(tmp_reservetime),
            success:function(data){
                if(data.trim() == '"1"')
                {
                    location.reload();
                }
                else
                {
                    alert('Database Error')
                    $('#add_frtmpreservedatetimeBtn').prop('disabled', false);
                }
            },
            error: function (e, status)
            {
                alert(e.status);
                $('#add_frtmpreservedatetimeBtn').prop('disabled', false);
            }
        });
    }
}

function reservation_datedetails(frno,url)
{
    console.log(frno,url);
    $.ajax({
        url:url,
        method:'POST',
        data:{frno:frno},
        dataType:'json',
        success:function(data)
        {
        $('#reservationdate_details_tbl tbody').empty().append(data);
        }
    });
}

function disable_reserve_time(url,url1)
{
    var reserve_date = $('#frreserve_date').val();
    $('.frtimecheck_box').prop("disabled", false);
    $('.frtimecheck_box').prop("checked", false);
    $('.tdclass').removeAttr('style');

    //Disable Reserved Time
    $.ajax({
        type:'POST',
        url:url,
        data: {reserve_date:reserve_date},
        success:function(data){
            var data = $.parseJSON(data);  
            var count = data.length;
           
            for(var index = 0; index<=count-1; index++)
            { 
                $('#frtimecheck_box_'+data[index].time.replace(/:/g, '')).prop('disabled', true);
                $('#td'+data[index].time.replace(/:/g, '')).css({ 'background-color': '#b3645f' });

                //Disable date after end time
                if(data[index].time.replace(/:/g, '') == '700-800AM')
                {
                    $('#frtimecheck_box_800-900AM').prop('disabled', true);
                    $('#td800-900AM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '800-900AM')
                {
                    $('#frtimecheck_box_900-1000AM').prop('disabled', true);
                    $('#td900-1000AM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '900-1000AM')
                {
                    $('#frtimecheck_box_1000-1100AM').prop('disabled', true);
                    $('#td1000-1100AM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '1000-1100AM')
                {
                    $('#frtimecheck_box_1100-1200PM').prop('disabled', true);
                    $('#td1100-1200PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '1100-1200PM')
                {
                    $('#frtimecheck_box_1200-100PM').prop('disabled', true);
                    $('#td1200-100PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '1200-100PM')
                {
                    $('#frtimecheck_box_100-200PM').prop('disabled', true);
                    $('#td100-200PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '100-200PM')
                {
                    $('#frtimecheck_box_200-300PM').prop('disabled', true);
                    $('#td200-300PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '200-300PM')
                {
                    $('#frtimecheck_box_300-400PM').prop('disabled', true);
                    $('#td300-400PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '300-400PM')
                {
                    $('#frtimecheck_box_400-500PM').prop('disabled', true);
                    $('#td400-500PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '400-500PM')
                {
                    $('#frtimecheck_box_500-600PM').prop('disabled', true);
                    $('#td500-600PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '500-600PM')
                {
                    $('#frtimecheck_box_600-700PM').prop('disabled', true);
                    $('#td600-700PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '600-700PM')
                {
                    $('#frtimecheck_box_700-800PM').prop('disabled', true);
                    $('#td700-800PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '700-800PM')
                {
                    $('#frtimecheck_box_800-900PM').prop('disabled', true);
                    $('#td800-900PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].time.replace(/:/g, '') == '800-900PM')
                {
                    $('#frtimecheck_box_900-1000PM').prop('disabled', true);
                    $('#td900-1000PM').css({ 'background-color': '#838a85' });
                }
            }
        },
        error: function (e, status)
        {
            alert('Connection Error.');
        }
    });

    //Disable temporary reserved time
    $.ajax({
        type:'POST',
        url:url1,
        data: {reserve_date:reserve_date},
        success:function(data){
            var data = $.parseJSON(data);  
            var count = data.length;
           
            for(var index = 0; index<=count-1; index++)
            { 
                $('#frtimecheck_box_'+data[index].tmp_time.replace(/:/g, '')).prop('disabled', true);
                $('#td'+data[index].tmp_time.replace(/:/g, '')).css({ 'background-color': '#b3645f' });

                //Disable date after end time
                if(data[index].tmp_time.replace(/:/g, '') == '700-800AM')
                {
                    $('#frtimecheck_box_800-900AM').prop('disabled', true);
                    $('#td800-900AM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '800-900AM')
                {
                    $('#frtimecheck_box_900-1000AM').prop('disabled', true);
                    $('#td900-1000AM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '900-1000AM')
                {
                    $('#frtimecheck_box_1000-1100AM').prop('disabled', true);
                    $('#td1000-1100AM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '1000-1100AM')
                {
                    $('#frtimecheck_box_1100-1200PM').prop('disabled', true);
                    $('#td1100-1200PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '1100-1200PM')
                {
                    $('#frtimecheck_box_1200-100PM').prop('disabled', true);
                    $('#td1200-100PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '1200-100PM')
                {
                    $('#frtimecheck_box_100-200PM').prop('disabled', true);
                    $('#td100-200PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '100-200PM')
                {
                    $('#frtimecheck_box_200-300PM').prop('disabled', true);
                    $('#td200-300PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '200-300PM')
                {
                    $('#frtimecheck_box_300-400PM').prop('disabled', true);
                    $('#td300-400PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '300-400PM')
                {
                    $('#frtimecheck_box_400-500PM').prop('disabled', true);
                    $('#td400-500PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '400-500PM')
                {
                    $('#frtimecheck_box_500-600PM').prop('disabled', true);
                    $('#td500-600PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '500-600PM')
                {
                    $('#frtimecheck_box_600-700PM').prop('disabled', true);
                    $('#td600-700PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '600-700PM')
                {
                    $('#frtimecheck_box_700-800PM').prop('disabled', true);
                    $('#td700-800PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '700-800PM')
                {
                    $('#frtimecheck_box_800-900PM').prop('disabled', true);
                    $('#td800-900PM').css({ 'background-color': '#838a85' });
                }
                else if(data[index].tmp_time.replace(/:/g, '') == '800-900PM')
                {
                    $('#frtimecheck_box_900-1000PM').prop('disabled', true);
                    $('#td900-1000PM').css({ 'background-color': '#838a85' });
                }
            }
        },
        error: function (e, status)
        {
            alert('Connection Error.');
        }
    });
}

function cancel_reservation_modal(id)
{
    $('#managerkey_frno').val(id);
    $('#managerkey_modal').modal('show');
}

function cancel_reservation(url)
{
    var username    = $('#username_key').val();
    var password     = $('#password_key').val();
    if(username.length === 0)
    {
        alert('No username input.');
    }
    else if(password.length === 0)
    {
        alert('No password input.');
    }
    else
    {
        $.ajax({
            type:'POST',
            url:url,
            data:$('#frm_managerKey').serialize(),
            success:function(data)
            {
                if(data.trim() == '"1"')
                {
                    location.reload();
                }
                else if(data.trim() == '"0"')
                {
                    alert("Database Error");
                }
                else
                {
                    alert("Invalid Key");
                }
            },
            error: function (e, status)
            {
                alert('Connection Error');
            }
        });
    }
}

function insert_frdiscount(url)
{
    // console.log($('#discount_description').val());
    var discounttype        = $('#discount_type').val();
    var discountoption      = $('#discount_option').val();
    var discountamount      = $('#discount_amount').val();
    var discountdescription = $('#discount_description').val();
    if(discounttype.length === 0)
    {
        alert('No input for discount type.');
    }
    else if(discountoption == null)
    {
        alert('No input for percent/amount.');
    }
    else if(discountamount == '0.00')
    {
        alert('Invalid input for discount amount.');
    }
    else if(discountdescription.length === 0)
    {
        alert('No input for description');
    }
    else
    {
        $('#insert_frdiscountbtn').prop('disabled', true);
        $.ajax({
            method:'POST',
            url:url,
            data:$('#add_frdiscount').serialize(),
            success:function(data)
            {
                if(data.trim() == '"1"')
                {
                    location.reload();
                }
                else if(data.trim() == '"2"')
                {
                    $('#insert_frdiscountbtn').prop('disabled', false);
                    alert('Discount already exists');
                }
                else if(data.trim() == '"0"')
                {
                    alert("Database Error");
                    $('#insert_frdiscountbtn').prop('disabled', false);
                }
               
            },
            error: function (e, status)
            {
                alert('Connection Error');
            }
        });
    }
}

function update_frdiscount(url)
{
    // console.log($('#discount_description').val());
    var discounttype        = $('#discount_typeupdate').val();
    var discountoption      = $('#discount_optionupdate').val();
    var discountamount      = $('#discount_amountupdate').val();
    var discountdescription = $('#discount_descriptionupdate').val();
    if(discounttype.length === 0)
    {
        alert('No input for discount type.');
    }
    else if(discountoption == null)
    {
        alert('No input for percent/amount.');
    }
    else if(discountamount == '0.00')
    {
        alert('Invalid input for discount amount.');
    }
    else if(discountdescription.length === 0)
    {
        alert('No input for description');
    }
    else
    {
        $('#update_frdiscountbtn').prop('disabled', true);
        $.ajax({
            method:'POST',
            url:url,
            data:$('#frm_updatefrdiscount').serialize(),
            success:function(data)
            {
                if(data.trim() == '"1"')
                {
                    location.reload();
                }
                else if(data.trim() == '"2"')
                {
                    $('#update_frdiscountbtn').prop('disabled', false);
                    alert('Discount already exists');
                }
                else if(data.trim() == '"0"')
                {
                    alert("Database Error");
                    $('#update_frdiscountbtn').prop('disabled', false);
                }
               
            },
            error: function (e, status)
            {
                alert('Connection Error');
            }
        });
    }
}
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
//facility rental INVOICE
function showfr_invoicemodal(id,url,url1,url2)
{
    $('#facilityrental_no').val(id);
    $.ajax({
        type:'POST',
        url:url,
        data:{id:id},
        success:function(data)
        {  
            var data = $.parseJSON(data);  
            $('#frcustomername').val(data[0].FacilityRental_Cusname);
            $('#frcontactnum').val(data[0].FacilityRental_ContactNumber);
            $('#frcontactperson').val(data[0].FacilityRental_ContactPerson);
            $('#frcontactaddress').val(data[0].FacilityRental_CustomerAddress);
            $('#curr_date').val(data[0].TransactionDate);
            $('#frcustomerid').val(data[0].frcustomerid);
        }
    });
 
    $.ajax({
        type:'POST',
        url:url1,
        data:{id:id},
        dataType:'json',
        success:function(data)
        {  
            $('#ExpectedAmount').val(data.RunningBalance);
            $('#invoice_table tbody').empty().append(data.FacilityData);
                    
            $.ajax({
                type:'POST',
                url:url2,
                data:{id:id, runningbalance: data.RunningBalance},
                dataType:'json',
                success:function(data2)
                {  
                    $('#discount_table tbody').empty().append(data2.DiscountTable);
                    $('#TotalDiscount').val(data2.DiscountTotal);
                    $('#ActualAmount').val(data2.actual_amount);
                    $('#facilityrental_invoice').modal('show');
                }
            });
        }
    });

}

function append_InvoiceDiscount(url)
{
    var id              = $('#discount_id').val();
    var frno            = $('#facilityrental_no').val();
    var runningbalance = $('#ExpectedAmount').val();
    if(id == null)
    {
        alert('No discount chosen.');
    }
    else
    {
        $.ajax({
            method:'POST',
            url:url,
            data:{id:id,frno:frno,runningbalance:runningbalance},
            dataType:'json',
            success:function(data)
            {
                console.log(data);
                if(data == '0')
                {
                    alert('database error');
                }
                else if(data == '2')
                {
                    alert('Discount already appended.');
                }
                else
                {
                    $('#discount_table tbody').empty().append(data.DiscountTable);
                    $('#TotalDiscount').val(data.DiscountTotal);
                    $('#ActualAmount').val(data.actual_amount);
                    $('#add_invoicediscount').modal('hide');
                }
            }
        });
    }
}

function delete_appendeddiscount(id)
{
    var base_url = $('#base_url').val();
    var url=base_url+'index.php/leasing_facilityrental/delete_appendeddiscount/'+ id;
    document.getElementById('anchor_delete').href = url;
}

function savefrinvoice(url)
{
    var posting_date    = $('#posting_date').val();
    var due_date        = $('#due_date').val();
    if(posting_date.length === 0)
    {
        alert('No input for posting date.');
    }
    else if(due_date.length === 0)
    {
        alert('No input for due date.');
    }
    else
    {
        $('#savefrinvoicebtn').prop('disabled', true);
        $.ajax({
            method:'POST',
            url:url,
            data: $('#frm_invoice').serialize(),
            success:function(data)
            {
                if(data.trim() == '"0"')
                {
                    $('#savefrinvoicebtn').prop('disabled', false);
                    alert('database error');
                }
                else if(data.trim() == '"1"')
                {
                    location.reload();
                }
            },
            error: function (e, status)
            {
                $('#savefrinvoicebtn').prop('disabled', false);
                alert('Connection Error');
            }
        });
    }
}

//facility rental SOA
//facility rental SOA
//facility rental SOA
//facility rental SOA
//facility rental SOA
//facility rental SOA
//facility rental SOA
//facility rental SOA
function showfrsoamodal(frno,url, url1, url2)
{
      $.ajax({
        type:'POST',
        url:url,
        data:{frno:frno},
        success:function(data)
        {  
            var data = $.parseJSON(data);  
            $('#facilityrental_no').val(frno);
            $('#facilityrental_docno').val(data[0].FacilityRental_docno);
            $('#frcustomername').val(data[0].FacilityRental_Cusname);
            $('#frcontactnum').val(data[0].FacilityRental_ContactNumber);
            $('#frcontactperson').val(data[0].FacilityRental_ContactPerson);
            $('#frcontactaddress').val(data[0].FacilityRental_CustomerAddress);
            $('#curr_date').val(data[0].TransactionDate);
            $('#frcustomerid').val(data[0].customer_id);
            $('#ExpectedAmount').val(data[0].expected_amount);
            $('#TotalDiscount').val(data[0].total_discount);
            $('#ActualAmount').val(data[0].actual_amount);
          
        }
    });

    $.ajax({
        type:'POST',
        url:url1,
        data:{frno:frno},
        dataType:'json',
        success:function(data)
        {  
            $('#soa_table tbody').empty().append(data); 
        }
    });

      $.ajax({
        type:'POST',
        url:url2,
        data:{frno:frno},
        dataType:'json',
        success:function(data)
        {  
            $('#Soadiscount_table tbody').empty().append(data);
            $('#facilityrental_soa').modal('show');
        }
    });
}

function savefrsoa(url)
{
    var date_created = $('#date_created').val();
    var collection_date = $('#collection_date').val();
     var formData = new FormData($('form#frm_soa')[0]);

    if(date_created.length === 0)
    {
        alert('No input for date created.');
    }
    else if(collection_date.length === 0)
    {
        alert('No input for collection date.');
    }
    else
    {
        $('#savefrsoabtn').prop('disabled', true);
        $.ajax({
            type:'POST',
            url:url,
            data:formData,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data)
            {
                if(data.trim() == '"0"')
                {
                    $('#savefrsoabtn').prop('disabled', false);
                    alert('Database Error.');
                }
                else
                {
                    data = JSON.parse(data);
                    var filename = data['file_name'];
                    console.log(filename);
                    window.open(filename);
                    location.reload();
                }
            },
            error: function (e, status)
            {
                $('#savefrsoabtn').prop('disabled', false);
                alert('Connection Error');
            }
        })
    }
}
//facility rental payment
//facility rental payment
//facility rental payment
//facility rental payment
//facility rental payment
//facility rental payment
function get_paymentsoatable(url, url1)
{
    var soano = $('#soa_no').val();
    
    $.ajax({
        type:'POST',
        url:url,
        data: {soano:soano},
        dataType:'json',
        success:function(data)
        {
            $('#soa_table tbody').empty().append(data);
        }
    });

      $.ajax({
        type:'POST',
        url:url1,
        data: {soano:soano},
        dataType:'json',
        success:function(data)
        {
            $('#discount_table tbody').empty().append(data);
        }
    });
}

function save_facilityrentalpayment(url)
{
    var formData = new FormData($('form#frm_payment')[0]);
    var soano = $('#soa_no').val();

    if($('#receipt_no').val().length === 0)
    {
        alert('No input for receipt no.');
    }
    else if($('#soa_no').val() == '? undefined:undefined ?')
    {
        alert('No input for soa no.');
    }
    else if($('#frcustomername').val().length === 0)
    {
        alert('No input for customer name');
    }
    else if($('#billing_period').val().length === 0)
    {
        alert('No input for billing period.');
    }
    else if($('#payment_date').val().length === 0)
    {
        alert('No input for payment date.');
    }
    else if($('#remarks').val().length === 0)
    {
        alert('No input for remarks.');
    }
    else if($('#expected_amount').val().length === 0)
    {
        alert('No input for expected amount.');
    }
    else if($('#total_discount').val().length === 0)
    {
        alert('No input for total discount.');
    }
    else if($('#actual_amount').val().length === 0)
    {
        alert('No input for actual amount.');
    }
    else if($('#tender_typeDesc').val().length === 0)
    {
        alert('No input for tender type code.')
    }
    else if($('#tender_typeDesc').val() == 'Check' && $('#supp_doc').val().length === 0)
    {
        alert('No input for supporting documents.');
    }
    else if($('#amount_paid').val() == '0.00')
    {
        alert('Invalid amount.');
    }
    else if($('#payment_bank_code').val() == '? undefined:undefined ?')
    {
        alert('No input for bank code.');
    }
    else if($('#payment_bank_name').val() == '? undefined:undefined ?')
    {
        alert('No input for bank name.');
    }
    else if($('#payor').val().length === 0)
    {
        alert('No input for payor');
    }
    else
    {
        $('#frsavepaymentbtn').prop('disabled', true);
        $.ajax({
            type:'POST',
            url:url,
            data:formData,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            dataType:'json',
            success:function(data)
            {
                if(data == '0')
                {
                    $('#frsavepaymentbtn').prop('disabled', false);
                    alert('database error');
                }
                else if(data == '2')
                {
                    $('#frsavepaymentbtn').prop('disabled', false);
                    alert('Insufficient funds.');
                }
                else
                {
                    var filename = data;
                    console.log(filename);
                    window.open(filename);
                    location.reload();
                }
            }
        });
    }
}

// =================================================================================== KING ARTHURS ADDITION ============================================================ //
function startingDate()
{   

    $('#trade_name_button').prop('disabled', false);
    console.log($('#start_date').val());
}

function postingDate()
{   
    
    $('#trade_name_button').prop('disabled', false);
    console.log($('#posting_date').val());
}

function adjustmentKeyup(id, val)
{ 
    if ($('#' + id.id).val() == '') 
    {
        $('#trade_name_button').prop('disabled', true);
    }
    else
    {
        $('#trade_name_button').prop('disabled', false);
    }
}
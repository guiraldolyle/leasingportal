function generate(type, text) {
    var n = noty({
        text: text,
        type: type,
        dismissQueue: true,
        layout: 'topRight',
        closeWith: ['click'],
        theme: 'relax',
        timeout: 4000,
        maxVisible: 1,
        animation: {
            open: 'animated slideInDown',
            close: 'animated slideOutUp',
            easing: 'swing',
            speed: 500
        }
    });
    console.log('html: ' + n.options.id);
}


function notify(type, text) {

    var n = noty({
        text: text,
        type: type,
        dismissQueue: true,
        layout: 'center',
        focus: true,
        modal: true,
        theme: 'defaultTheme',
        buttons: [{
            addClass: 'btn btn-primary',
            text: 'Ok',
            onClick: function($noty) {
                $noty.close();
                location.reload();
                noty({ dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "Ok" button', type: 'success' });
            }
        }]
    });
    document.getElementById('button-0').focus();
    console.log('html: ' + n.options.id);
}





function success_contract() {
    var n = noty({
        text: 'Contract Successfully Saved.',
        type: 'success',
        dismissQueue: true,
        layout: 'center',
        modal: true,
        theme: 'defaultTheme',
        buttons: [{
            addClass: 'btn btn-primary',
            text: 'Ok',
            onClick: function($noty) {
                $noty.close();
                var url = window.location.origin + '/AGC-PMS/' + 'index.php/' + 'leasing_transaction/' + 'lst_Lforcontract/';
                window.location = url;
                noty({ dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "Ok" button', type: 'success' });
            }
        }]
    });
    document.getElementById('button-0').focus();
    console.log('html: ' + n.options.id);
}



function contract_amended(destination) {
    var n = noty({
        text: 'Contract Successfully Saved.',
        type: 'success',
        dismissQueue: true,
        layout: 'center',
        modal: true,
        theme: 'defaultTheme',
        buttons: [{
            addClass: 'btn btn-primary',
            text: 'Ok',
            onClick: function($noty) {
                $noty.close();
                var url = window.location.origin + '/AGC-PMS/' + 'index.php/' + 'leasing_transaction/' + destination;
                window.location = url;
                noty({ dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "Ok" button', type: 'success' });
            }
        }]
    });
    document.getElementById('button-0').focus();
    console.log('html: ' + n.options.id);
}





function confirmation(type, message, destination) {
    var n = noty({
        text: message,
        type: type,
        dismissQueue: true,
        layout: 'center',
        modal: true,
        theme: 'defaultTheme',
        buttons: [{
            addClass: 'btn btn-primary',
            text: 'Ok',
            onClick: function($noty) {
                $noty.close();
                var url = window.location.origin + '/AGC-PMS/' + 'index.php/' + 'leasing_transaction/' + destination;
                window.location = url;
                noty({ dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "Ok" button', type: 'success' });
            }
        }]
    });
    document.getElementById('button-0').focus();
    console.log('html: ' + n.options.id);
}


function redirect(type, message, destination) {
    var n = noty({
        text: message,
        type: type,
        dismissQueue: true,
        layout: 'center',
        modal: true,
        theme: 'defaultTheme',
        buttons: [{
            addClass: 'btn btn-primary',
            text: 'Ok',
            onClick: function($noty) {
                $noty.close();
                var url = window.location.origin + '/AGC-PMS/' + 'index.php/' + 'leasing_mstrfile/' + destination;
                window.location = url;
                noty({ dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "Ok" button', type: 'success' });
            }
        }]
    });
    document.getElementById('button-0').focus();
    console.log('html: ' + n.options.id);
}


function sTerm_success() {
    var n = noty({
        text: 'Contract Successfully Saved.',
        type: 'success',
        dismissQueue: true,
        layout: 'center',
        modal: true,
        theme: 'defaultTheme',
        buttons: [{
            addClass: 'btn btn-primary',
            text: 'Ok',
            onClick: function($noty) {
                $noty.close();
                var url = window.location.origin + '/AGC-PMS/' + 'index.php/' + 'leasing_transaction/' + 'lst_Sforcontract/';
                window.location = url;
                noty({ dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "Ok" button', type: 'success' });
            }
        }]
    });
    document.getElementById('button-0').focus();
    console.log('html: ' + n.options.id);
}
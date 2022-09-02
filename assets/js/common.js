/**
 * Created by KGY on 2018-01-12.
 */
function ShowSweetAlert(message, button_type) {
    var sa_message = message;

    swal({
            title: '',
            text: sa_message,
            type: '',
            confirmButtonClass: button_type,
            confirmButtonText: "확인"
        },

        function (isConfirm) {
        });
}

function showAlertDlg(message, button_type, callback, str_btn = "Confirm") {
    swal({
            customClass: 'alert_dlg',
            title: '',
            text: message,
            type: '',
            allowOutsideClick: true,
            confirmButtonClass: button_type + " width-100",
            confirmButtonText: str_btn,
        },

        function (isConfirm) {
            if (callback != undefined) {
                callback();
            }
        });
}

function showConfirmDlg(message, callback, close_flag, cancel_callback, str_confirm = "Confirm", str_cancel = "Cancel") {
    if (close_flag == undefined) {
        close_flag = true;
    }
    swal({
            customClass: 'confirm_dlg',
            title: '',
            text: message,
            type: '',
            showCancelButton: true,
            allowOutsideClick: true,
            confirmButtonClass: "btn-primary width-100",
            confirmButtonText: str_confirm,
            closeOnConfirm: close_flag,
            cancelButtonClass: "btn-danger width-50",
            cancelButtonText: str_cancel
        },

        function (isConfirm) {
            if (isConfirm) {
                if (callback != undefined) {
                    callback();
                }
            } else {
                if (cancel_callback != undefined) {
                    cancel_callback()
                }
            }
        });
}

function showNotification(no_title, message, type) {

    var shortCutFunction = type;
    var msg = message;
    var title = no_title || '';

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
}

function showLoadingProgress() {
    App.blockUI({
        animate: true,
        target: '#total_body',
        boxed: false
    });
}

function hideLoadingProgress() {
    App.unblockUI('#total_body');
}

function ajaxRequest(url, data, callback, dataType = '') {
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        dataType: dataType,
        beforeSend: function () {
            showLoadingProgress();
        },
        success: function (data) {
            hideLoadingProgress();

            callback(data);
        },
        error: function () {
            hideLoadingProgress();
            showNotification("Error", "Network Error...", "error");
        }
    })
}

function number_format(value) {
    return new Intl.NumberFormat().format(value);
}

function sprintf(template, values) {
    return template.replace(/%s/g, function () {
        return values.shift();
    });
}

function arrayConfirmPush(arr, ele) {
    if (arr.indexOf(ele) == -1) {
        arr.push(ele);
    }
}

function arrayConfirmPop(arr, ele) {
    var ele_idx = arr.indexOf(ele);
    if (ele_idx != -1) {
        arr.splice(ele_idx, 1);
    }
}
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>BunYang</title>
    <link href="<?=base_url()?>assets/images/logo2.png" rel="icon">
    <link href="<?=base_url()?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url()?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?=base_url()?>assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="<?=base_url()?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <link href="<?=base_url()?>assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="<?=base_url()?>assets/css/custom.css?time=<?= time() ?>" rel="stylesheet" type="text/css" />

</head>
<body class="login">

<div class="content" style="margin-top: 200px;">
    <!-- BEGIN LOGIN FORM -->
    <form class="" action="" method="post" style="padding: 0px;width:400px;margin-top:200px;background-color:white;margin: auto" id="login_form">
        <div class="form-actions" style="height: 50px;background-color:#92d050;text-align: center;padding-top: 10px;padding-bottom: 10px;">
            <label style="color:white;font-weight: 700;font-size: 22px;">BunYang Admin</label>
        </div>

        <div class="row" style="padding: 30px;">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        <span style="font-size: 14px;line-height: 42px;">ID</span>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" style="background-color: white;height: 38px;" name="usrid" id="usrid">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        <span style="font-size: 14px;line-height: 42px;">Password</span>
                    </div>
                    <div class="col-md-9">
                        <input type="password" class="form-control" style="background-color: white;height: 38px;" name="password" id="password">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-9">
                    <button type="submit" class="btn btn-success" style="width: 200px;background: #92d050;">Login</button>
                </div>
            </div>
        </div>

    </form>
</div>
<script>
    var validator;
    var error1;
    $(function(){
        var login_form = $('#login_form');

        validator = login_form.validate({
            doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            messages: {
                usrid: {
                    required:"아이디를 입력해주세요."
                },
                password: {
                    required: '비밀번호를 입력해주세요.'
                }
            },
            rules: {
                usrid: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            beforeSubmit:function(){
            },
            submitHandler: function (form) {
                onLogin();
            }
        });
    })

    function onLogin(){
        $.ajax({
            type:'post',
            url:'<?=site_url("Login/login")?>',
            data:'usrid=' + $('#usrid').val() + '&password=' + $('#password').val(),
            beforeSend: function() {
                showLoadingProgress();
            },
            success: function(data) {
                hideLoadingProgress();
                data = data.trim();

                if(data === "success"){
                    location.href="<?=site_url('User')?>";
                } else {
                    showAlertDlg("Please check your ID and\npassword again.","btn-danger");
                }
            },
            error: function(data) {
                hideLoadingProgress();
                showNotification("오류", "Network Error..", "error");
            }
        })
    }

</script>
<script src="<?=base_url()?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/common.js" type="text/javascript"></script>
</body>
</html>

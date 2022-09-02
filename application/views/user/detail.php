<style>
    .lbl_group_title {
        background: #d9d9d9;
        color: black;
        line-height: 50px;
        padding-left: 10px;
        font-size: 16px;
        font-weight: bold
    }

    .font_15 {
        font-size: 15px;
    }

    .flex_center {
        display: flex;
        align-items: center;
    }

    .flex {
        display: flex;
    }

</style>

<div class="row">
    <div class="col-md-12 margin-top-10">
        <label class="width-100 lbl_group_title" style="">Member History</label>

        <div class="width-100 margin-top-10 flex">
            <label class="font_15" style="margin-top: 5px">[<?= _make_phone_format($info->phone) ?>] Point : <?= number_format($info->point) ?>Point</label>
            <div style="margin-left: 30px">
                <div class="flex_center">
                    <input id="ipt_payment_point" type="number" class="form-control" style="width: 200px;">
                    <a onclick="onPayment()" class="btn btn-primary" style="width: 120px">Point Payment</a>
                </div>
                <div class="flex_center" style="margin-top: 5px">
                    <input id="ipt_refund_point" type="number" class="form-control" style="width: 200px;">
                    <a onclick="onRefund()" class="btn btn-primary" style="width: 120px">Point Refund</a>
                </div>
            </div>

            <a onclick="onWithdrawal()" class="btn btn-danger" style="height: 34px;margin-left: auto">Withdrawal</a>
        </div>
    </div>

    <div class="col-md-12 margin-top-10">
        <label class="width-100 lbl_group_title" style="">Member PointHistory</label>

        <div class="width-100 margin-top-10">
            <div class="col-md-6 padding_none">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="center">Use Date</th>
                        <th class="center">Content</th>
                        <th class="center">Point</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($arr_point_his as $item) {
                        ?>
                        <tr>
                            <td><?=date("y.m.d", strtotime($item->time))?></td>
                            <td><?=$item->type == 1 ? "Payment" : "Refund"?></td>
                            <td><?=$item->type == 1 ? number_format($item->point) : number_format(-$item->point)?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--pwd dlg-->
<a class="btn blue btn-outline sbold hidden" data-toggle="modal" href="#modal_pwd" id="btn_modal_pwd"></a>
<div class="modal fade" id="modal_pwd" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-md-12" style="float: none !important;padding: 10px 0">
                    <label class="width-100 center">Please enter the administrator password.</label>
                    <input id="ipt_admin_pwd" class="form-control margin-top-10" type="password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="btn_cancel_dlg"><i class="fa fa-close"></i>&nbsp;Cancel</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="onConfirmWithdrawal()"><i class="fa fa-save"></i>&nbsp;Confirm&nbsp;</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#page_title a').html("Member Manage - Member Detail - PointHistory");
    });

    function onPayment() {
        let point = $('#ipt_payment_point').val();
        if(point > 0) {
            showConfirmDlg(sprintf("Would you like to pay %sPoints?", [point]), function() {
                $.ajax({
                    type:'post',
                    url:'<?=site_url("User/pay_refund_point")?>',
                    data: {
                        user: <?=$info->uid?>,
                        point: point,
                        type: 1
                    },
                    beforeSend: function() {
                        showLoadingProgress();
                    },
                    success: function(data) {
                        hideLoadingProgress();
                        if(data == "success") {
                            showAlertDlg("Operation Successed.", "btn-primary", function() {
                                location.reload();
                            })
                        } else {
                            showNotification("Error", "Operation Failed.", "error");
                        }
                    },
                    error: function(data) {
                        hideLoadingProgress();
                        showNotification("오류", "Network Error..", "error");
                    }
                })
            }, false)
        }
    }

    function onRefund() {
        let point = $('#ipt_refund_point').val();
        if(point > 0) {
            if(point > <?=$info->point?>) {
                showNotification("Warning", "보유 Point를 확인하세요.", "warning");
                return;
            }
            showConfirmDlg(sprintf("Would you like to refund %sPoints?", [point]), function() {
                $.ajax({
                    type:'post',
                    url:'<?=site_url("User/pay_refund_point")?>',
                    data: {
                        user: <?=$info->uid?>,
                        point: point,
                        type: 2
                    },
                    beforeSend: function() {
                        showLoadingProgress();
                    },
                    success: function(data) {
                        hideLoadingProgress();
                        if(data == "success") {
                            showAlertDlg("Operation Successed.", "btn-primary", function() {
                                location.reload();
                            })
                        } else {
                            showNotification("Error", "Operation Failed.", "error");
                        }
                    },
                    error: function(data) {
                        hideLoadingProgress();
                        showNotification("오류", "Network Error..", "error");
                    }
                })
            }, false)
        }
    }

    function onWithdrawal() {
        $('#btn_modal_pwd').trigger("click");
    }

    function onConfirmWithdrawal() {
        let pwd = $('#ipt_admin_pwd').val();
        if(pwd != "") {
            $.ajax({
                type:'post',
                url:'<?=site_url("User/withdrawal_usr")?>',
                data: {
                    user: <?=$info->uid?>,
                    pwd: pwd,
                },
                beforeSend: function() {
                    showLoadingProgress();
                },
                success: function(data) {
                    hideLoadingProgress();
                    $('#ipt_admin_pwd').val("");
                    if(data == "success") {
                        showAlertDlg("회원 정보를 삭제하였습니다.", "btn-primary", function() {
                            history.go(-1);
                        })
                    } else if(data == "pwd_err") {
                        showAlertDlg("비밀번호가 틀립니다.","btn-danger");
                    } else {
                        showNotification("Error", "Operation Failed.", "error");
                    }
                },
                error: function(data) {
                    hideLoadingProgress();
                    showNotification("오류", "Network Error..", "error");
                }
            })
        } else {
            showAlertDlg("관리자 비밀번호를 입력하십시오.","btn-danger");
        }
    }
</script>

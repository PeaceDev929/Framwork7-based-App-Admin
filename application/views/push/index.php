<div class="row">
    <form method="post" enctype="multipart/form-data" action="<?= site_url('Notice/register') ?>" id="frm_regist">
        <div class="col-md-12" style="margin-top: 20px;">
            <input id="ipt_title" name="notice_title" class="form-control" placeholder="Please enter a title"
                   value="">
            <textarea id="txv_content" name="notice_content" class="form-control" style="margin-top: 5px;"
                      rows="10" placeholder="Please enter content"></textarea>

        </div>
    </form>
    <div class="col-md-12 center" style="margin-top: 20px;">
        <a onclick="sendPush()" class="btn btn-primary" style="width: 100px;"><i class="fa fa-send"></i>&nbsp;&nbsp;Send</a>
    </div>
</div>

<script>
    $(document).ready(function () {
    });

    function sendPush() {
        var str_title = $('#ipt_title').val();
        var str_content = $('#txv_content').val();


        if (str_title == "") {
            showNotification("Warning", "Please enter a title.", "warning");
            return;
        }

        if (str_content == "") {
            showNotification("Warning", "Please input content.", "warning");
            return;
        }

        showConfirmDlg("Do you want to send it?", function () {
            ajaxRequest('<?=site_url("Push/send_push")?>', {
                title: str_title,
                content: str_content,
            }, function (data) {
                if (data == "success") {
                    $('#ipt_title').val("");
                    $('#txv_content').val("");
                    showNotification("Success", "Operation success.", "success");
                } else {
                    showNotification("Error", "Operation Failed.", "error");
                }
            })
        }, true, null, "Send", "Cancel")
    }
</script>

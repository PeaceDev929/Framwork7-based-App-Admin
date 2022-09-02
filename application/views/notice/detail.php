<div class="row">
    <form method="post" enctype="multipart/form-data" action="<?= site_url('Notice/register') ?>" id="frm_regist">
        <div class="col-md-12" style="margin-top: 20px;">
            <input id="ipt_notice_title" name="notice_title" class="form-control" placeholder="Please input title."
                   value="<?= $info != null ? $info->title : '' ?>">
            <textarea id="area_notice_content" name="notice_content" class="form-control" style="margin-top: 10px;"
                      rows="10"
                      placeholder="Please input content."><?= $info != null ? $info->content : '' ?></textarea>

        </div>
    </form>
    <div class="col-md-12 center" style="margin-top: 20px;">
        <a onclick="history.go(-1)" class="btn btn-danger" style="width: 100px;"><i class="fa fa-close"></i>&nbsp;Cancel</a>
        <a onclick="saveNotice()" class="btn btn-primary" style="width: 100px;"><i class="fa fa-save"></i>&nbsp;Save</a>
    </div>
</div>

<script>
    $(document).ready(function () {
    });

    function saveNotice() {
        var uid = <?=$info != null ? $info->uid : 0?>;
        var str_title = $('#ipt_notice_title').val();
        var str_content = $('#area_notice_content').val();


        if (str_title == "") {
            showNotification("Warning", "Please input title.", "warning");
            return;
        }

        if (str_content == "") {
            showNotification("Warning", "Please input content.", "warning");
            return;
        }

        showConfirmDlg("Do you want to save it?", function () {
            ajaxRequest('<?=site_url("Notice/register")?>', {
                uid: uid,
                title: str_title,
                content: str_content,
            }, function (data) {
                if (data == "success") {
                    showAlertDlg("Operation success.", "btn-primary", function () {
                        history.go(-1);
                    }, "Confirm");
                    // showNotification("Success", "The operation was archived.", "success");
                } else {
                    showNotification("Error", "Operation Failed.", "error");
                }
            })
        }, false, null, "Save", "Cancel")
    }
</script>

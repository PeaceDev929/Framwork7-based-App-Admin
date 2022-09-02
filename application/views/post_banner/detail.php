<div class="row" style="padding-top: 20px;">
    <div class="col-md-12">
        <form id="form-register" method="post" action="<?= site_url('PostBanner/register') ?>"
              enctype="multipart/form-data">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td class="title_gray" style="width: 150px">Image</td>
                    <td class="text-align-left">
                        <img class="img-responsive thumbnail"
                             style="width: 100px; height: 100px"
                             data-file="<?= $info == null ? '' : $info->image ?>"
                             src="<?= $info == null ? Common::getDefaultImage() : _get_file_url($info->image) ?>">
                        <a onclick="registerThumbnail()" class="btn btn-primary ml-20">Image Reg</a>
                    </td>
                </tr>
                <tr>
                    <td class="title_gray">Link URL</td>
                    <td class="padding_none">
                        <input class="form-control" id="link" value="<?= $info != null ? $info->link : '' ?>">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
    <div class="col-md-12 center" style="margin-top: 20px;">
        <a onclick="history.go(-1)" class="btn btn-danger" style="width: 100px;"><i class="fa fa-close"></i>&nbsp;Cancel</a>
        <a onclick="save()" class="btn btn-primary" style="width: 100px;"><i class="fa fa-save"></i>&nbsp;Save</a>
    </div>
</div>

<script>
    function registerThumbnail() {
        uploadedCallback = function (url, file) {
            var obj = $('.thumbnail');
            $(obj).attr('data-file', file);
            $(obj).attr('src', url);
        };

        uploadFile()
    }

    function save() {
        var thumbnail = $('.thumbnail').attr('data-file');

        if (thumbnail == '') {
            showNotification("Warning", "Please register a image.", "warning");
            return;
        }

        showConfirmDlg("Do you want to save it?", function () {
            ajaxRequest('<?=site_url("PostBanner/register")?>', {
                uid: <?= $info == null ? 0 : $info->uid?>,
                image: thumbnail,
                link: $('#link').val(),
            }, function (data) {
                if (data == "success") {
                    showAlertDlg("Operation success.", "btn-primary", function () {
                        history.go(-1);
                    }, "Confirm");
                } else {
                    showNotification("Error", "Operation Failed.", "error");
                }
            })
        }, false, null, "Save", "Cancel")
    }
</script>

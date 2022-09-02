<div class="col-md-12" style="padding-top: 20px;">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <td class="title_gray" width="150px">투자방이름</td>
            <td class="padding_none">
                <input id="ipt_name" name="name" class="form-control" value="<?=$info != null ? $info->name : ""?>">
            </td>
        </tr>
        <tr>
            <td class="title_gray">략어</td>
            <td class="padding_none">
                <input id="ipt_short_name" name="short_name" class="form-control" value="<?=$info != null ? $info->short_name : ""?>">
            </td>
        </tr>
        <tr>
            <td class="title_gray">Thumbnail<br>(1:1)</td>
            <td class="text-align-left" style="display: flex;align-items: center">
                <img class="thumbnail_img mr-20 <?=$info != null && $info->thumbnail_url != "" ? "" : "hidden"?>"
                     style="width: 100px; height: 100px;margin-right: 20px" id="thumbnail"
                     data-file="<?=($info != null && $info->thumbnail_url != "") ? $info->thumbnail : ""?>"
                     src="<?= $info == null ? Common::getDefaultImage() : $info->thumbnail_url ?>">
                <a onclick="registerThumbnail()" class="btn btn-primary">Thumbnail Reg</a>
            </td>
        </tr>
        <tr>
            <td class="title_gray">배경 image<br>(500:96)</td>
            <td class="text-align-left">
                <img class="detail_img <?=$info != null && $info->image_url != "" ? "" : "hidden"?>"
                     style="width: 500px; height: 96px;margin-right: 20px" id="detail_img"
                     data-file="<?=($info != null && $info->image_url != "") ? $info->image : ""?>"
                     src="<?= $info == null ? Common::getDefaultImage() : $info->image_url ?>">
                <a onclick="registerImage()" class="btn btn-primary">Image Reg</a>
            </td>
        </tr>
        <tr>
            <td class="title_gray">초과인원</td>
            <td class="padding_none">
                <input id="ipt_limit_cnt" name="limit_cnt" type="number" class="form-control" value="<?=$info != null ? $info->limit_cnt : ""?>">
            </td>
        </tr>
        <?php
        if($info != null) {
            ?>
            <tr>
                <td class="title_gray">현시순서</td>
                <td class="padding_none">
                    <input id="ipt_ord" name="ord" type="number" class="form-control"
                           value="<?= $info != null ? $info->ord : 0 ?>">
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td class="title_gray">상태</td>
            <td class="padding_none">
                <select id="ipt_status" name="status" class="form-control" style="width: 150px">
                    <option value="0" <?=$info == null || $info->status == 0 ? "selected" : ""?>>Show</option>
                    <option value="1" <?=$info != null && $info->status == 1 ? "selected" : ""?>>Hide</option>
                </select>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="col-md-12 center" style="margin-top: 20px;">
        <a onclick="history.go(-1)" class="btn btn-danger" style="width: 100px;"><i class="fa fa-close"></i>&nbsp;Cancel</a>
        <a onclick="save()" class="btn btn-primary" style="width: 100px;"><i class="fa fa-save"></i>&nbsp;Save</a>
    </div>
</div>

<script>
    function registerThumbnail() {
        uploadedCallback = function (url, file) {
            var obj = $('.thumbnail_img');
            $(obj).removeClass("hidden");
            $(obj).attr('data-file', file);
            $(obj).attr('src', url);
        };

        uploadFile()
    }

    function registerImage() {
        uploadedCallback = function (url, file) {
            var obj = $('.detail_img');
            $(obj).removeClass("hidden");
            $(obj).attr('data-file', file);
            $(obj).attr('src', url);
        };

        uploadFile()
    }

    function save() {
        var str_name = $('#ipt_name').val();
        var str_short_name = $('#ipt_short_name').val();
        var thumbnail = $('#thumbnail').attr("data-file");
        var image = $('#detail_img').attr("data-file");
        var limit_cnt = $('#ipt_limit_cnt').val();
        var ord = $('#ipt_ord').val();

        if (str_name == '') {
            showNotification("Warning", "투자방이름을 입력하세요.", "warning");
            return;
        }

        if (str_short_name == '') {
            showNotification("Warning", "략어를 입력하세요.", "warning");
            return;
        }

        if (thumbnail == '') {
            showNotification("Warning", "Thumbnail을 선택하세요.", "warning");
            return;
        }

        if (image == '') {
            showNotification("Warning", "배경Image를 선택하세요.", "warning");
            return;
        }

        if (limit_cnt == '' || parseInt(limit_cnt) <= 0) {
            showNotification("Warning", "초과인원을 설정하세요.", "warning");
            return;
        }

        if(parseInt('<?= $uid?>') > 0) {
            if(ord == "") {
                showNotification("Warning", "현시순서를 입력하세요.", "warning");
                return;
            }
        }

        showConfirmDlg("Do you want to save it?", function () {
            ajaxRequest('<?=site_url("InvestmentRoom/register")?>', {
                uid: <?= $uid?>,
                name: str_name,
                short_name: str_short_name,
                thumbnail: thumbnail,
                image: image,
                limit_cnt: limit_cnt,
                ord: ord,
                status: $('#ipt_status').val(),
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

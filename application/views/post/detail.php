<div class="row" style="padding-top: 20px;">
    <div class="col-md-12">
        <form id="form-register" method="post" action="<?= site_url('Banner/register') ?>"
              enctype="multipart/form-data">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td style="width: 15%" class="title_gray">Select Board</td>
                    <td>
                        <select class="form-control" id="post-type" style="width: 200px">
                            <?php foreach (REALTY_TYPE as $key => $value) { ?>
                                <option <?= $info != null ? ($info->type == $key ? 'selected' : '') : '' ?>
                                        value="<?= $key ?>"><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="title_gray">Title</td>
                    <td>
                        <input class="form-control" type="text" placeholder="Please enter a title" id="title"
                               value="<?= $info != null ? $info->name : '' ?>">
                    </td>
                </tr>
                <tr>
                    <td class="title_gray">Secondary Title</td>
                    <td>
                        <input class="form-control" type="text" placeholder="Please enter a secondary title"
                               id="sub-title" value="<?= $info != null ? $info->sub_name : '' ?>">
                    </td>
                </tr>
                <tr>
                    <td class="title_gray">Thumbnail Image</td>
                    <td class="text-align-left">
                        <img class="img-responsive thumbnail"
                             style="width: 100px; height: 100px"
                             data-file="<?= $info == null ? '' : $info->thumbnail ?>"
                             src="<?= $info == null ? Common::getDefaultImage() : _get_file_url($info->thumbnail) ?>">
                        <a onclick="registerThumbnail()" class="btn btn-primary ml-20">Image Reg</a>
                    </td>
                </tr>
                <tr>
                    <td class="title_gray">Attached Images</td>
                    <td class="text-align-left">
                        <div class="images">
                            <?php
                            if ($info != null) {
                                $imageArray = explode(',', $info->images);
                                foreach ($imageArray as $item) {
                                    if($item != "") {?>
                                    <div class="image-item" data-file="<?= $item ?>">
                                        <img class="img-responsive"
                                             src="<?= $info == null ? Common::getDefaultImage() : _get_file_url($item) ?>">
                                        <button type="button" class="btn delete">
                                            <img src="<?= base_url('assets/images/ic_close.png') ?>">
                                        </button>
                                    </div>
                                <?php } }
                            } ?>
                            <a onclick="registerImage(this)" class="btn btn-primary ml-20">Image Reg</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="title_gray">Bunyang Info</td>
                    <td>
                        <div class="form-group" id="extra-info">
                            <table class="table table-bordered no-margin-bottom">
                                <tbody>
                                <?php
                                if ($info != null) {
                                    $extra_info = json_decode($info->extra_info);
                                    foreach ($extra_info as $item) {
                                        ?>
                                        <tr>
                                            <td><input class="form-control title" placeholder="Please input title."
                                                       value="<?= $item->title ?>">
                                            </td>
                                            <td><input class="form-control value" placeholder="Please input value."
                                                       value="<?= $item->value ?>">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger form-control delete">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-4">
                                <a onclick="addExtraInfo()" class="btn btn-primary">Add Info</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="title_gray">Danji Info</td>
                    <td>
                        <div class="form-group" id="danji-info">
                            <table class="table table-bordered no-margin-bottom">
                                <tbody>
                                <?php
                                if ($info != null) {
                                    $danji_info = json_decode($info->danji_info);
                                    foreach ($danji_info as $item) {
                                        ?>
                                        <tr>
                                            <td><input class="form-control title" placeholder="Please input title."
                                                       value="<?= $item->title ?>">
                                            </td>
                                            <td><input class="form-control value" placeholder="Please input value."
                                                       value="<?= $item->value ?>">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger form-control delete">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-4">
                                <a onclick="addDanjiInfo()" class="btn btn-primary">Add Info</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="title_gray">Location</td>
                    <td class="form-group">
                        <div class="input-group">
                            <div class="input-icon">
                                <input id="address" class="form-control" readonly type="text"
                                       style="padding-left: 12px" value="<?= $info != null ? $info->address : '' ?>">
                            </div>
                            <span class="input-group-btn">
                                <button onclick="searchAddress()" class="btn btn-success" type="button">
                                    <i class="fa fa-map-marker fa-fw"></i> Location
                                </button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="title_gray">Content</td>
                    <td>
                        <textarea style="height: 200px" class="form-control"
                                  id="content"><?= $info != null ? $info->content : '' ?></textarea>
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
    var latitude = '0';
    var longitude = '0';
    $(document).ready(function () {
    });

    function registerThumbnail() {
        uploadedCallback = function (url, file) {
            var obj = $('.thumbnail');
            $(obj).attr('data-file', file);
            $(obj).attr('src', url);
        };

        uploadFile()
    }

    function registerImage(obj) {
        uploadedCallback = function (url, file) {
            $(obj).before('<div class="image-item" data-file="' + file + '">\n' +
                '                                    <img class="img-responsive"\n' +
                '                                         src="' + url + '">\n' +
                '                                    <button type="button" class="btn delete">\n' +
                '                                        <img src="<?= base_url('assets/images/ic_close.png') ?>">\n' +
                '                                    </button>\n' +
                '                                </div>');
            $(obj).attr('src', url);

            $('.image-item .delete').unbind().click(function () {
                $(this).closest('.image-item').remove();
            })
        };

        uploadFile()
    }

    function searchAddress() {
        $('#address').val('test area');
        latitude = '0';
        longitude = '0';
    }

    function addExtraInfo() {
        $('#extra-info tbody').append('<tr>\n' +
            '                                    <td><input class="form-control title" placeholder="Please input title."></td>\n' +
            '                                    <td><input class="form-control value" placeholder="Please input value."></td>\n' +
            '                                    <td>\n' +
            '                                        <button type="button" class="btn btn-danger form-control delete">Delete</button>\n' +
            '                                    </td>\n' +
            '                                </tr>')
        $('#extra-info tbody tr .delete').unbind().click(function () {
            $(this).closest('tr').remove();
        })

    }

    function addDanjiInfo() {
        $('#danji-info tbody').append('<tr>\n' +
            '                                    <td><input class="form-control title" placeholder="Please input title."></td>\n' +
            '                                    <td><input class="form-control value" placeholder="Please input value."></td>\n' +
            '                                    <td>\n' +
            '                                        <button type="button" class="btn btn-danger form-control delete">Delete</button>\n' +
            '                                    </td>\n' +
            '                                </tr>')
        $('#danji-info tbody tr .delete').unbind().click(function () {
            $(this).closest('tr').remove();
        })

    }

    function save() {
        var type = $('#post-type').val();
        var title = $('#title').val();
        var sub_title = $('#sub-title').val();
        var thumbnail = $('.thumbnail').attr('data-file');
        var images = '';
        $('.images .image-item').each(function () {
            if (images != '') {
                images += ',';
            }
            images += $(this).attr('data-file');
        });

        var extra_infos = [];
        $('#extra-info tr').each(function () {
            var obj = $(this);
            extra_infos.push({
                title: $(obj).find('.title').val(),
                value: $(obj).find('.value').val()
            });
        });

        var danji_infos = [];
        $('#danji-info tr').each(function () {
            var obj = $(this);
            danji_infos.push({
                title: $(obj).find('.title').val(),
                value: $(obj).find('.value').val()
            });
        });

        var address = $('#address').val();
        var content = $('#content').val();

        if (title == '') {
            showNotification("Warning", "Please enter a title.", "warning");
            return;
        }

        if (sub_title == '') {
            showNotification("Warning", "Please enter a secondary title.", "warning");
            return;
        }

        if (thumbnail == '') {
            showNotification("Warning", "Please register a thumbnail image.", "warning");
            return;
        }

        if (address === '') {
            showNotification("Warning", "Please select location.", "warning");
            return;
        }

        showConfirmDlg("Do you want to save it?", function () {
            ajaxRequest('<?=site_url("Post/register")?>', {
                uid: <?= $info == null ? 0 : $info->uid?>,
                type: type,
                title: title,
                sub_title: sub_title,
                thumbnail: thumbnail,
                images: images,
                extra_info: JSON.stringify(extra_infos),
                danji_info: JSON.stringify(danji_infos),
                address: address,
                latitude: latitude,
                longitude: longitude,
                content: content,
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

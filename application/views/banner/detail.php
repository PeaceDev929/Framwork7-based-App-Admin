<div class="col-md-12" style="padding-top: 20px;">
    <form id="form-register" method="post" action="<?= site_url('Banner/register') ?>"
          enctype="multipart/form-data">
        <input class="hidden" name="type" value="<?= $type ?>">
        <input class="hidden" name="width" value="<?= $width ?>">
        <input class="hidden" name="height" value="<?= $height ?>">
        <input class="hidden" name="image-name" value="<?= $image ?>" id="image-name">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td class="title_gray">Banner Area</td>
                <td>[<?= Common::getBannerPage($type) ?>] <?= BANNER_LOCATION[$type] ?></td>
            </tr>
            <tr>
                <td class="title_gray">Banner Size</td>
                <td id="size_td"><?= $width . ' * ' . $height ?></td>
            </tr>
            <tr>
                <td class="title_gray">Image Reg</td>
                <td class="text-align-left">
                    <img class=""
                         style="width: 100px; height: 100px" id="thumbnail"
                         src="<?= $image == '' ? Common::getDefaultImage() : _get_file_url($image) ?>">
                    <a onclick="registerImage()" class="btn btn-primary ml-20">Image Reg</a>
                </td>
            </tr>
            <tr>
                <td class="title_gray">Link URL</td>
                <td><input class="form-control" type="text" name="link" value="<?= $link ?>" id="link"></td>
            </tr>
            <tr>
                <td class="title_gray">Bunyang Post ID</td>
                <td>
                    <div class="width-100" style="display: flex;align-items: center">
                        <input class="hidden" id="selected_post_id" value="<?=$post_id?>" name="post_id">
                        <div id="selected_post_div" class="<?=$post_info == null ? "hidden" : ""?>" style="display: flex;align-items: center;margin-right: 20px">
                            <label id="lbl_selected_post_name"><?=$post_info != null ? $post_info->name : ""?></label>
                            <a onclick="onRemovePost()" style="margin-left: 10px"><i class="fa fa-trash" style="color: red"></i></a>
                        </div>
                        <a onclick="onShowDoctorDlg()" class="btn btn-primary">Select</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <div class="col-md-12 center" style="margin-top: 20px;">
        <a onclick="history.go(-1)" class="btn btn-danger" style="width: 100px;"><i class="fa fa-close"></i>&nbsp;Cancel</a>
        <a onclick="save()" class="btn btn-primary" style="width: 100px;"><i class="fa fa-save"></i>&nbsp;Save</a>
    </div>
</div>

<form id="frm_img" class="hidden" enctype="multipart/form-data" method="post" action="<?=site_url("Banner/file_upload")?>">
    <input class="hidden" type="file" name="image" accept="image/*" id="image-input">
</form>

<a class="hidden" data-toggle="modal" href="#modal_select_post" id="btn_select_post_dlg"></a>
<div class="modal fade" id="modal_select_post" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 600px">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-md-12" style="float: none !important;padding: 10px 0">
                    <div class="width-100" style="display: flex;">
                        <input id="ipt_post_search_key" class="form-control" placeholder="Please enter post title">
                        <a onclick="onSearchPost()" class="btn btn-primary"><i></i> Search</a>
                    </div>
                    <table id="tbl_post" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="center">Title</th>
                                <th style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="btn_post_dlg_close"><i class="fa fa-close"></i>&nbsp;Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    var imageRegistered = false;
    var pTable;

    $(document).ready(function () {
    });
    
    $(function() {
        pTable = $('#tbl_post').dataTable({
            "stateSave": true,
            "processing": true,
            "serverSide": true,
            "autoWidth": false,

            "language": {
                "emptyTable": "Empty",
                "info": "<div style='display: flex'>" +
                    "	<span style='font-weight: 700'>Search count</span>" +
                    "	<span style='font-weight: 700;' class='color_white_blue'>&nbsp;_END_</span> <b></b>&nbsp;/&nbsp;" +
                    "	<span style='font-weight: 700'>Total Count</span><b>&nbsp_TOTAL_ </b>" +
                    "</div>",
                "infoEmpty": "",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ count per page",
                "search": "Search:",
                "zeroRecords": "Empty"
            },

            "ajax": { // define ajax settings
                "url": "<?=site_url('Banner/getPostTableData')?>", // ajax URL
                "type": "POST",
                "beforeSend": function () {
                },
                "data": function (data) {
                    data["search_str"] = $("#ipt_post_search_key").val();
                },
                "dataSrc": function (res) {
                    return res.data;
                }
            },

            "columns": [
                {"orderable": false},
                {"orderable": false},
            ],

            "createdRow": function (row, data, dataIndex) {
                $('td:eq(1)', row).css("padding", 0);
                $('td:eq(1)', row).html('<button class="btn btn-primary" style="width: 80px" onclick="selectItem(' + data["uid"] + ",'" + data['name'] + "'" + ')">Select</button>');
            },

            "order": [],

            buttons: [],

            // pagination control
            "lengthMenu": [
                [10, 20, 50, 100],
                [10, 20, 50, 100],
            ],
            // set the initial value
            "pageLength": 10,
            "pagingType": 'bootstrap_full_number', // pagination type
            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-4 col-sm-12'><'col-sm-12'p>>", // horizobtal scrollable datatable
            "fnDrawCallback": function (oSettings) {

            }
        });
    })

    function registerImage() {
        let inputFile = $('#image-input');
        $(inputFile).trigger('click');
        $(inputFile).unbind().on('change', function (e) {
            let file = e.target.files[0];

            let fr = new FileReader();
            fr.readAsDataURL(file);
            fr.onloadend = function () {
                let img = fr.result;
                console.log(fr);
                $('#thumbnail').attr("src", img);

                imageRegistered = true;
            }

            var options = {
                success: afterSuccess,  // post-submit callback
                beforeSend: beforeSubmit,
                resetForm: false,       // reset the form after successful submit
                dataType: "json",
            };

            $("#frm_img").ajaxSubmit(options);
            // $("#frm_img").submit();

            function beforeSubmit(){
                showLoadingProgress();
            }

            function afterSuccess(data) {
                hideLoadingProgress();

                $("#image-name").val(data.file);
                $("input[name=width]").val(data.width);
                $("input[name=height]").val(data.height);
                $('#size_td').html(data.width + " * " + data.height);
            }
        })
    }

    function save() {
        let link = $('#link').val();

        if (!imageRegistered && $('#image-name').val() == '') {
            showNotification("Warning", "Please register image.", "warning");
            return;
        }

        // if (link === '') {
        //     showNotification("Warning", "Please input link url.", "warning");
        //     return;
        // }

        showConfirmDlg("Do you want to save it?", function () {
            let options = {
                success: afterSuccess,  // post-submit callback
                beforeSend: beforeSubmit,
                resetForm: false        // reset the form after successful submit
            };

            $("#form-register").ajaxSubmit(options);

            function beforeSubmit() {
                showLoadingProgress();
            }

            function afterSuccess(data) {
                hideLoadingProgress();

                if (data == "success") {
                    showAlertDlg("Operation success.", "btn-primary", function () {
                        history.go(-1);
                    }, "Confirm");
                } else if(data == "post_id_err") {
                    showNotification("오유", "Post ID를 확인하세요.", "error");
                } else {
                    showNotification("오유", "Server오류...", "error");
                }
            }
        }, false, null, "Save", "Cancel")
    }

    function onShowDoctorDlg() {
        $('#btn_select_post_dlg').trigger("click");
        $("#ipt_post_search_key").val("");
        pTable._fnReDraw();
    }

    function onSearchPost() {
        pTable._fnReDraw();
    }

    function selectItem(uid, title) {
        $('#selected_post_id').val(uid);
        $('#lbl_selected_post_name').html(title);
        $('#selected_post_div').removeClass("hidden");
        $('#btn_post_dlg_close').trigger("click");
    }

    function onRemovePost() {
        $('#selected_post_id').val(0);
        $('#lbl_selected_post_name').html("");
        $('#selected_post_div').addClass("hidden");
    }
</script>

</div>
</div>
</div>
</div>

<input type="file" id="input-file" class="hidden" accept="*/*">


<!-- BEGIN CORE PLUGINS -->
<script src="<?= base_url() ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js"
        type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"
        type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?= base_url() ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
        type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN DATE PICKER PLUGINS -->
<script src="<?= base_url() ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
        type="text/javascript"></script>
<!-- END DATE PICKER PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="<?= base_url() ?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="<?= base_url() ?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<script src="<?= base_url() ?>assets/js/common.js" type="text/javascript"></script>

<script>
    var uploadedCallback;

    function uploadFile() {
        var inputFile = $('#input-file');
        $(inputFile).trigger('click');
        $(inputFile).unbind().on('change', function (e) {
            var file = e.target.files[0];

            if (file) {
                var form_data = new FormData();
                form_data.append('img', file);

                $.ajax('<?=base_url('User/file_upload')?>', {
                    method: 'POST',
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function () {

                    },
                    success: function (result) {

                        if (Number(result.code) === 0) {
                            $(inputFile).val('');
                            uploadedCallback(result.url, result.file);
                        } else {

                        }
                    }
                });
            }
        })
    }

    $(document).ready(function() {
        let msgInterval = setInterval(() => {
            $.ajax({
                type:'post',
                url:'<?=site_url("Login/get_new_msg_cnt")?>',
                success: function(data) {
                    if(parseInt(data) > 0) {
                        showAlertDlg("새로운 CS문의가 도착하였습니다.", "btn-primary");

                        var audio = new Audio("<?=base_url()?>assets/audio/1.mp3");
                        audio.addEventListener('ended', function() {
                            audio.src ='song/' + arr_music[index++] ;
                            audio.pause();
                        });
                        audio.play();
                    }
                }
            })
        }, 1000);
    })
</script>

<style>
    .btn_table_above {
        background-color: #286090 !important;
        border-color: #286090 !important;
        color: white !important;
        height: 25px !important;
        font-size: 14px !important;
        padding-top: 2px;
        width: 70px;
        margin-top: -3px;
    }
</style>
</body>
</html>
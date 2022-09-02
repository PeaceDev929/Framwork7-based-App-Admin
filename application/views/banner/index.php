<div class="row" style="padding-top: 20px;">
    <div class="col-md-12">
        <table id="table-content" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th class="center title_td">Banner Page</th>
                <th class="center title_td">Thumbnail</th>
                <th class="center title_td">Location</th>
                <th class="center title_td"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach (BANNER_LOCATION as $key => $value) { ?>
                <tr>
                    <td><?= Common::getBannerPage($key) ?></td>
                    <td>
                        <?php if ($infos[$key - 1] == null) { ?>
                            <img class="img-responsive" src="<?= Common::getDefaultImage() ?>">
                        <?php } else { ?>
                            <img class="img-responsive" src="<?= _get_file_url($infos[$key - 1]->image) ?>">
                        <?php } ?>
                    </td>
                    <td><?= $value ?></td>
                    <td>
                        <button onclick="onReg(<?= $key ?>)" class="btn btn-primary">Reg</button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {

    });

    function onReg(type) {
        location.href = "<?= site_url("Banner/detail")?>?type=" + type;
    }

    function deleteItem(uid) {
        showConfirmDlg("Do you want to delete?", function () {
            $.ajax({
                type: 'post',
                url: '<?=site_url("Notice/deleteItem")?>',
                data: {
                    uids: uid
                },
                success: function (data) {
                    data = data.trim();
                    if (data === "success") {
                        showAlertDlg("Operation Success.", "btn-primary");
                        table._fnReDraw();
                    } else {
                        showAlertDlg("Operation Failed.", "btn-danger");
                    }
                }
            })
        }, false);
    }
</script>
<div class="row">
    <div class="col-md-12" style="margin-top: 20px; display: flex">
        <div style="flex: 1;" class="text-right">
            <a class="btn btn-primary" style="width: 100px;" href="<?= site_url('PostBanner/detail') ?>""><i
                    class="fa fa-plus"></i>&nbsp;Add</a>
        </div>
    </div>
    <div class="col-md-12" style="padding-top: 20px;">
        <table id="table-content" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th class="center title_td" width="100px">RegDate</th>
                <th class="center title_td" width="60px">Image</th>
                <th class="center title_td">Link Url</th>
                <th class="center title_td" width="80px"></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    var table;
    $(function () {
        var eTable = $('#table-content');
        table = eTable.dataTable({
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
                "url": "<?=site_url('PostBanner/getTableData')?>", // ajax URL
                "type": "POST",
                "beforeSend": function () {
                },
                "data": function (data) {
                },
                "dataSrc": function (res) {
                    return res.data;
                }
            },

            "columns": [
                {"orderable": false},
                {"orderable": false},
                {"orderable": false},
                {"orderable": false},
            ],

            "createdRow": function (row, data, dataIndex) {
                $('td:eq(1)', row).css("padding", 0);
                $('td:eq(1)', row).html('<img class="img-responsive" src="' + data[1] + '">');
                $('td:eq(2)', row).html('<a target="_blank" class="text-underline" href="' + data[2] + '">' + data[2] + '</a>');
                $('td:eq(3)', row).html('<a onclick="detail(' + data["uid"] + ')"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;' +
                    '<a onclick="deleteItem(' + data["uid"] + ')"><i class="fa fa-trash" style="color:red"></i></a>');
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
            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'i><'col-md-6 col-sm-12'l>r><'table-scrollable't><'row'<'col-md-4 col-sm-12'><'col-sm-12'p>>", // horizobtal scrollable datatable
            "fnDrawCallback": function (oSettings) {

            }
        });
    });

    function searchData() {
        table._fnReDraw();
    }

    function detail(uid) {
        location.href = "<?= site_url("PostBanner/detail")?>?uid=" + uid;
    }

    function deleteItem(uid) {
        showConfirmDlg("Are you sure you want to delete Banner info?", function () {
            $.ajax({
                type: 'post',
                url: '<?=site_url("PostBanner/deleteItem")?>',
                data: {
                    uid: uid
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
<div class="row" style="padding-top: 20px;">
    <div class="col-md-12">
        <a href="<?=site_url("InvestmentRoom/edit_room")?>" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;추가</a>
        <table id="tbl_content" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th class="center title_td" width="60px">No</th>
                <th class="center title_td">투자방이름</th>
                <th class="center title_td">략어</th>
                <th class="center title_td">Thumbnail</th>
                <th class="center title_td">배경 image</th>
                <th class="center title_td">현시순서</th>
                <th class="center title_td">상태</th>
                <th class="center title_td">관리</th>
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
        var eTable = $('#tbl_content');
        table = eTable.dataTable({
            "stateSave": true,
            "processing": true,
            "serverSide": true,
            "autoWidth": false,

            "language": {
                "emptyTable": "Empty",
                "info": "",
                "infoEmpty": "",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "",
                "search": "Search:",
                "zeroRecords": "Empty"
            },

            "ajax": { // define ajax settings
                "url": "<?=site_url('InvestmentRoom/getTableData')?>", // ajax URL
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
                {"orderable": false},
                {"orderable": false},
                {"orderable": false},
                {"orderable": false},
            ],

            "createdRow": function (row, data, dataIndex) {
                if(data['thumbnail'] != "") {
                    $('td:eq(3)', row).html('<a target="_blank" href="' + data['thumbnail'] + '"><img style="width: 100px;height: 100px;" src="' + data['thumbnail'] + '"></a>');
                }

                if(data['image'] != "") {
                    $('td:eq(4)', row).html('<a target="_blank" href="' + data['image'] + '"><img style="width: 100px;height: 100px;" src="' + data['image'] + '"></a>');
                }

                $('td:eq(5)', row).html(data['ord']);

                $('td:eq(6)', row).html(data['status'] == 0 ? "Show" : "Hide");

                $('td:eq(7)', row).html('<a href="<?=site_url("InvestmentRoom/edit_room")?>?uid=' + data['uid'] + '"><i class="fa fa-edit"></i></a>');
            },

            "order": [],

            buttons: [],
            // pagination control
            "lengthMenu": [],
            // set the initial value
            "pageLength": -1,
            "paginate": false,
            "pagingType": 'bootstrap_full_number', // pagination type
            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'i><'col-md-6 col-sm-12'l>r><'table-scrollable't><'row'<'col-md-4 col-sm-12'><'col-sm-12'p>>", // horizobtal scrollable datatable
            "fnDrawCallback": function (oSettings) {
            }
        });
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
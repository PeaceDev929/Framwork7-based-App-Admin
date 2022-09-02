<div class="row">
    <div class="col-md-12" style="margin-top: 20px; display: flex">
        <table>
            <tbody>
            <tr>
                <td style="width: 150px;" class="padding_none center">
                    <select class="form-control select_format" id="sel_search_type">
                        <option value="1">Bunyang Page</option>
                    </select>
                </td>
                <td style="width: 20vw;" class="padding_none"><input id="ipt_search_str" class="form-control"
                                                                     placeholder="Input title"></td>
                <td class="padding_none">
                    <a class="btn btn-primary" style="width: 100px;" onclick="searchData()"><i class="fa fa-search"></i>&nbsp;Search</a>
                </td>
            </tr>
            </tbody>
        </table>
        <div style="flex: 1;" class="text-right">
            <a class="btn btn-primary" style="width: 100px;" href="<?= site_url('Post/detail') ?>""><i
                    class="fa fa-plus"></i>&nbsp;Add</a>
            <a href="<?=site_url("Post/top_setting")?>" class="btn btn-success" style="width: 120px;"><i class="fa fa-edit"></i>&nbsp;Top Setting</a>
        </div>
    </div>
    <div class="col-md-12" style="padding-top: 20px;">
        <table id="table-content" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th class="center title_td" width="60px">No</th>
                <th class="center title_td" width="100px">RegDate</th>
                <th class="center title_td" width="120px">Thumbnail</th>
                <th class="center title_td">Keyword</th>
                <th class="center title_td" width="100px">Kind</th>
                <th class="center title_td" width="80px"></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#ipt_search_str').keypress(function (event) {
            if (event.keyCode == '13') {
                searchData();
            }
        })
    })

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
                "url": "<?=site_url('Post/getTableData')?>", // ajax URL
                "type": "POST",
                "beforeSend": function () {
                },
                "data": function (data) {
                    data["search_type"] = $("#sel_search_type").val();
                    data["search_str"] = $("#ipt_search_str").val();
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
            ],

            "createdRow": function (row, data, dataIndex) {
                $('td:eq(2)', row).html('<img class="img-responsive" src="' + data[2] + '">');
                $('td:eq(3)', row).html('<a class="text-underline" onclick="detail(' + data["uid"] + ')">' + data[3] + '</a>');
                $('td:eq(5)', row).html('<button class="btn btn-primary" onclick="deleteItem(' + data["uid"] + ')">Delete</button>');
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
        location.href = "<?= site_url("Post/detail")?>?uid=" + uid;
    }

    function deleteItem(uid) {
        showConfirmDlg("Are you sure you want to delete Bunyang info?", function () {
            $.ajax({
                type: 'post',
                url: '<?=site_url("Post/deleteItem")?>',
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
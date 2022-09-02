<div class="row">
    <div class="col-md-12" style="margin-top: 20px; display: flex">
        <table>
            <tbody>
            <tr>
                <td style="width: 150px;" class="padding_none center">
                    <select class="form-control select_format" id="sel_search_type">
                        <option value="0">All</option>
                        <option value="1">차단한 회원</option>
                        <option value="2">차단된 회원</option>
                    </select>
                </td>
                <td style="width: 20vw;" class="padding_none"><input id="ipt_search_str" class="form-control"
                                                                     placeholder="Input keyword."></td>
                <td class="padding_none">
                    <a class="btn btn-primary" style="width: 100px;" onclick="searchData()"><i class="fa fa-search"></i>&nbsp;Search</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-12" style="padding-top: 20px;">
        <table id="table-content" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th class="center title_gray" width="110px;">차단날자</th>
                <th class="center title_gray" width="40%">차단한 회원</th>
                <th class="center title_gray">차단된 회원</th>
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
                "url": "<?=site_url('Block/getTableData')?>", // ajax URL
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
            ],

            "createdRow": function (row, data, dataIndex) {
                $('td:eq(1)', row).html('<a href="<?=site_url("User/detail?uid=")?>' + data['usr_uid'] + '">' + data[1] + '</a>');
                $('td:eq(2)', row).html('<a href="<?=site_url("User/detail?uid=")?>' + data['target_usr_uid'] + '">' + data[2] + '</a>');
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
        location.href = "<?= site_url("Notice/detail")?>?uid=" + uid;
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
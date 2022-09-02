<div class="row">
    <div class="col-md-12" style="margin-top: 20px; display: flex">
        <table>
            <tbody>
            <tr>
                <td style="width: 150px;" class="padding_none center">
                    <select class="form-control select_format" id="sel_search_type">
                        <option value="1">Phone Number</option>
                        <option value="2">Name</option>
                        <option value="3">Nickname</option>
                    </select>
                </td>
                <td style="width: 20vw;" class="padding_none"><input id="ipt_search_str" class="form-control"
                                                                     placeholder="- 없이 입력하십시오."></td>
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
                <th class="center title_td" width="100px">Application date</th>
                <th class="center title_td">Name</th>
                <th class="center title_td">Phone Number</th>
                <th class="center title_td">Login Id</th>
                <th class="center title_td">Application Point</th>
                <th class="center title_td" width="60px">State</th>
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

        $('#sel_search_type').change(function() {
            let type = parseInt($(this).val());
            if(type == 1) {
                $('#ipt_search_str').attr("placeholder", "- 없이 입력하십시오.");
            } else if(type == 2) {
                $('#ipt_search_str').attr("placeholder", "이름을 입력하십시오.");
            } else {
                $('#ipt_search_str').attr("placeholder", "NickName을 입력하십시오.");
            }
        })
    })

    var table;

    $(function () {
        var eTable = $('#table-content');
        table = eTable.DataTable({
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
                "url": "<?=site_url('Refund/getTableData')?>", // ajax URL
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
                {"orderable": true},
                {"orderable": false},
                {"orderable": false},
                {"orderable": false},
                {"orderable": false},
                {"orderable": false},
            ],

            "createdRow": function (row, data, dataIndex) {
                $('td:eq(5)', row).css("padding", 0);
                if(parseInt(data['status']) == 0) {
                    $('td:eq(5)', row).html('<a onclick="onRefundConfirm(' + data['uid'] + ')" style="width: 60px" class="btn btn-sm btn-default">비완료</a>');
                } else {
                    $('td:eq(5)', row).html('<a style="width: 60px" class="btn btn-sm btn-primary">완료</a>');
                }
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
        table.draw(true);
    }

    function onRefundConfirm(uid) {
        showConfirmDlg("Do you want to confirm it?", function () {
            ajaxRequest('<?=site_url("Refund/refund_confirm")?>', {
                uid: uid,
            }, function (data) {
                if (data == "success") {
                    showAlertDlg("Point Refund신청이 완료되였습니다.", "btn-primary");
                    table.draw(false);
                } else if(data == "point_error") {
                    showAlertDlg("User has no enough point", "btn-danger");
                } else {
                    showNotification("Error", "Operation Failed.", "error");
                }
            })
        }, false);
    }
</script>
<div class="row">
    <div class="col-md-12" style="margin-top: 20px; display: flex">
        <table>
            <tbody>
            <tr>
                <td style="width: 150px;" class="padding_none center">
                    <select class="form-control select_format" id="sel_search_type">
                        <option value="0">Main</option>
                        <?php foreach (REALTY_TYPE as $key => $value) { ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php } ?>
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
    </div>
    <div id="div_main" class="col-md-12" style="padding-top: 20px;">
        <table id="main-table-content" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th class="center title_td" width="100px">RegDate</th>
                <th class="center title_td" width="120px">Thumbnail</th>
                <th class="center title_td">Keyword</th>
                <th class="center title_td" width="100px">Kind</th>
                <th class="center title_td" width="120px">Top-Status</th>
                <th class="center title_td" width="120px">Bottom-Status</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div id="div_extra" class="col-md-12 hidden" style="padding-top: 20px;">
        <table id="table-content" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th class="center title_td" width="100px">RegDate</th>
                <th class="center title_td" width="120px">Thumbnail</th>
                <th class="center title_td">Keyword</th>
                <th class="center title_td" width="100px">Kind</th>
                <th class="center title_td" width="80px">Status</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#page_title a').html("Bunyang Post - Top Setting");
        $('#ipt_search_str').keypress(function (event) {
            if (event.keyCode == '13') {
                searchData();
            }
        })

        $('#sel_search_type').change(function() {
            if(parseInt($(this).val()) == 0) {
                $('#div_main').removeClass("hidden");
                $('#div_extra').addClass("hidden");
            } else {
                $('#div_main').addClass("hidden");
                $('#div_extra').removeClass("hidden");
            }
            searchData();
        })
    })

    var mainTable;
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
                "url": "<?=site_url('Post/getTopSettingTableData')?>", // ajax URL
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
            ],

            "createdRow": function (row, data, dataIndex) {
                $('td:eq(1)', row).html('<img class="img-responsive" src="' + data[1] + '">');
                $('td:eq(2)', row).html('<a class="text-underline" onclick="detail(' + data["uid"] + ')">' + data[2] + '</a>');
                if(parseInt(data['top_flag']) == 1) {
                    $('td:eq(4)', row).html('<a onclick="onChangeTopStatus(' + data['uid'] + ')" class="btn btn-sm btn-primary" style="width: 60px">ON</a>');
                } else {
                    $('td:eq(4)', row).html('<a onclick="onChangeTopStatus(' + data['uid'] + ')" class="btn btn-sm btn-danger" style="width: 60px">OFF</a>');
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

        var mTable = $('#main-table-content');
        mainTable = mTable.DataTable({
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
                "url": "<?=site_url('Post/getTopSettingTableData')?>", // ajax URL
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
                $('td:eq(1)', row).html('<img class="img-responsive" src="' + data[1] + '">');
                $('td:eq(2)', row).html('<a class="text-underline" onclick="detail(' + data["uid"] + ')">' + data[2] + '</a>');
                if(parseInt(data['main_flag']) == 1) {
                    $('td:eq(4)', row).html('<a onclick="onChangeMainStatus(' + data['uid'] + ')" class="btn btn-sm btn-primary" style="width: 60px">ON</a>');
                } else {
                    $('td:eq(4)', row).html('<a onclick="onChangeMainStatus(' + data['uid'] + ')" class="btn btn-sm btn-danger" style="width: 60px">OFF</a>');
                }
                if(parseInt(data['main_bottom_flag']) == 1) {
                    $('td:eq(5)', row).html('<a onclick="onChangeMainBottomStatus(' + data['uid'] + ')" class="btn btn-sm btn-primary" style="width: 60px">ON</a>');
                } else {
                    $('td:eq(5)', row).html('<a onclick="onChangeMainBottomStatus(' + data['uid'] + ')" class="btn btn-sm btn-danger" style="width: 60px">OFF</a>');
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
        if(parseInt($('#sel_search_type').val()) == 0) {
            mainTable.draw(true);
        } else {
            table.draw(true);
        }
    }

    function detail(uid) {
        location.href = "<?= site_url("Post/detail")?>?uid=" + uid;
    }

    function onChangeMainStatus(uid) {
        $.ajax({
            type: 'post',
            url: '<?=site_url("Post/change_main_flag")?>',
            data: {
                uid: uid
            },
            success: function (data) {
                data = data.trim();
                if (data === "success") {
                    showNotification("Success", "Operation Success.", "success");
                    mainTable.draw(false);
                } else if(data == "cnt_error") {
                    showNotification("Warning", "최대 8개까지 등록하실 수 있습니다.", "warning");
                } else {
                    showNotification("Error", "Operation Failed.", "error");
                }
            }
        })
    }

    function onChangeMainBottomStatus(uid) {
        $.ajax({
            type: 'post',
            url: '<?=site_url("Post/change_main_bottom_flag")?>',
            data: {
                uid: uid
            },
            success: function (data) {
                data = data.trim();
                if (data === "success") {
                    showNotification("Success", "Operation Success.", "success");
                    mainTable.draw(false);
                } else if(data == "cnt_error") {
                    showNotification("Warning", "최대 12개까지 등록하실 수 있습니다.", "warning");
                } else {
                    showNotification("Error", "Operation Failed.", "error");
                }
            }
        })
    }

    function onChangeTopStatus(uid) {
        $.ajax({
            type: 'post',
            url: '<?=site_url("Post/change_top_status")?>',
            data: {
                uid: uid
            },
            success: function (data) {
                data = data.trim();
                if (data === "success") {
                    showNotification("Success", "Operation Success.", "success");
                    table.draw(false);
                } else if(data == "cnt_error") {
                    showNotification("Warning", "최대 4개까지 등록하실 수 있습니다.", "warning");
                } else {
                    showNotification("Error", "Operation Failed.", "error");
                }
            }
        })
    }
</script>
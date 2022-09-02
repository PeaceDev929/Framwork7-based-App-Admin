<style>
    input[type=checkbox] {
        width: 20px;
        height: 20px;
    }

    .room_active {
        background-color: #afafaf !important;
    }

    .unread_cnt {
        background: blue;
        color: white;
        border-radius: 50% !important;
        min-width: 20px;
        height: 20px;
        line-height: 20px;
        margin-left: 10px;
        margin-bottom: 0;
        font-size: 12px;
    }

    .message {
        margin-bottom: 10px;
        max-width: 80%;
        display: flex;
        align-items: flex-end;
    }

    .message-received {
        display: flex;
    }

    .message-sent {
        display: flex;
        flex-direction: row-reverse;
        margin-left: auto;
    }

    .message-received img {
        width: 50px;
        height: 50px;
        border-radius: 50% !important;
        align-self: baseline;
    }

    .message-received .message-content {
         margin-left: 10px;
    }

    .message-received .message-bubble {
        background: white;
        border-radius: 0 7px 7px 7px !important;
        overflow: hidden;
        margin-top: 5px;
    }

    .message-sent .message-bubble {
        background: #bfdafb;
        border-radius: 7px 0 7px 7px !important;
        overflow: hidden;
        margin-top: 5px;
    }

    .message-received .message-time {
        margin-left: 5px;
    }

    .message-sent .message-time {
        margin-right: 5px;
    }

    .message-text {
        color: #594732;
        padding: 10px 20px 10px 10px;
        line-height: 20px;
        white-space: pre-wrap;
    }
</style>
<div class="row">
    <div class="col-md-12" style="margin-top: 20px; display: flex">
        <table>
            <tbody>
            <tr>
                <td style="width: 150px;" class="padding_none center">
                    <select class="form-control select_format" id="sel_search_type">
                        <option value="1">Chatting Room List</option>
                    </select>
                </td>
                <td style="width: 20vw;" class="padding_none">
                    <input id="ipt_search_str" class="form-control" placeholder="- Enter without.">
                </td>
                <td class="padding_none">
                    <a class="btn btn-primary" style="width: 100px;" onclick="searchData()"><i class="fa fa-search"></i>&nbsp;Search</a>
                    <a onclick="onRemoveRoom()" class="btn btn-danger">Chatting Room Exit</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-12" style="padding-top: 20px;">
        <div class="col-md-5" style="padding-left: 0">
            <table id="table-content" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th class="center title_td" width="50px">
                        <input id="chk_total" type="checkbox" style="font-size: 20px">
                    </th>
                    <th class="center title_td">Chatting Room List</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="col-md-7" style="padding-right: 0">
            <div id="chat_content" class="width-100" style="height: 60vh;border: 1px solid gray;overflow-y: auto;padding: 20px;background: #f5f5f5">
<!--                <div class="message message-received">-->
<!--                    <img src="http://192.168.0.58:8081/1.png">-->
<!---->
<!--                    <div class="message-content">-->
<!--                        <div class="message-name">kim</div>-->
<!--                        <div class="message-bubble">-->
<!--                            <div class="message-text">chat osfk awelkrj erkljw rkl wklrj wlkerj lwerj lwkr</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="message-time">11:22:22</div>-->
<!--                </div>-->
<!---->
<!--                <div class="message message-sent">-->
<!--                    <div class="message-content">-->
<!--                        <div class="message-bubble">-->
<!--                            <div class="message-text">gwerwer werwer werwerwe rerw</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="message-time">11:22:22</div>-->
<!--                </div>-->
            </div>

            <div class="width-100" style="margin-top: 10px;display: flex">
                <input id="ipt_content" class="form-control">
                <a onclick="onSendReply()" class="btn btn-primary" style="width: 100px">Send</a>
            </div>
        </div>
    </div>
</div>

<script>
    var selected_room_uid = 0;
    $(document).ready(function () {
        $('#ipt_search_str').keypress(function (event) {
            if (event.keyCode == '13') {
                searchData();
            }
        })

        $('#chk_total').change(function() {
            if($(this).is(":checked")) {
                $('.chk_child').prop("checked", true);
            } else {
                $('.chk_child').prop("checked", false);
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
                "info": "",
                "infoEmpty": "",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "",
                "search": "Search:",
                "zeroRecords": "Empty"
            },

            "ajax": { // define ajax settings
                "url": "<?=site_url('Chat/getCSList')?>", // ajax URL
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
            ],

            "createdRow": function (row, data, dataIndex) {
                $(row).attr("data-uid", data['uid']);
                $('td:eq(0)', row).css("padding", "0");
                $('td:eq(0)', row).html('<input type="checkbox" class="chk_child" data-uid="' + data['uid'] + '">');

                $('td:eq(1)', row).css("cursor","pointer");
                if(data['unread_cnt'] > 0) {
                    $('td:eq(1)', row).html(sprintf("[%s] 1:1 CS", [data['name']]) + "<label class='unread_cnt'>" + data['unread_cnt'] + "<label>")
                } else {
                    $('td:eq(1)', row).html(sprintf("[%s] 1:1 CS", [data['name']]))
                }

                $('td:eq(1)', row).bind("click", function() {
                    $(".room_active").removeClass("room_active");
                    $(row).addClass("room_active");
                    selected_room_uid = data['uid'];
                    $(row).find(".unread_cnt").remove();
                    refreshChatList();
                })
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
                $('.chk_child').unbind();
                $('.chk_child').bind("change", function() {
                    if($('.chk_child:not(:checked)').length == 0) {
                        $('#chk_total').prop("checked", true);
                    } else {
                        $('#chk_total').prop("checked", false);
                    }
                })
            }
        });
    });

    function searchData() {
        selected_room_uid = 0;
        $('#chat_content').html("");
        table._fnReDraw();
    }

    function onRemoveRoom() {
        var arr_uid = Array();
        $('.chk_child:checked').each(function() {
            arr_uid.push($(this).attr("data-uid"));
        })

        if(arr_uid.length == 0) {
            showNotification("경고", "삭제할 Chatting방을 선택하세요.", "warning");
            return;
        }
        
        showConfirmDlg("삭제하시겠습니까?", function () {
            $.ajax({
                type: 'post',
                url: '<?=site_url("Chat/removeRoom")?>',
                data: {
                    arr_uid: arr_uid.join(",")
                },
                success: function (data) {
                    data = data.trim();
                    if (data === "success") {
                        showNotification("성공", "삭제되었습니다.", "success");
                        table._fnReDraw();
                    } else {
                        showNotification("오류", "조작이 실패하였습니다.", "error");
                    }
                }
            })
        }, true);
    }

    function onSendReply() {
        if(selected_room_uid == 0) {
            showNotification("경고", "대화방을 선택하세요.", "warning");
            return;
        }

        let content = $('#ipt_content').val();
        if(content == "") {
            showNotification("경고", "내용을 입력하세요.", "warning");
            return;
        }

        $.ajax({
            type:'post',
            url:'<?=site_url("Chat/send_chat")?>',
            data: {
                room: selected_room_uid,
                content: content,
            },
            beforeSend: function() {
                showLoadingProgress();
            },
            success: function(data) {
                hideLoadingProgress();
                $('#ipt_content').val("");
                refreshChatList();
            },
            error: function(data) {
                hideLoadingProgress();
                showNotification("오류", "Network Error..", "error");
            }
        })
    }

    function refreshChatList() {
        $.ajax({
            type:'post',
            url:'<?=site_url("Chat/get_chat_list_by_room")?>',
            dataType: 'json',
            data: {
                uid: selected_room_uid
            },
            beforeSend: function() {
                showLoadingProgress();
            },
            success: function(data) {
                hideLoadingProgress();

                var str_content = "";
                for(var i=0; i<data.length; i++) {
                    let user = data[i].user;
                    let profile = data[i].profile;
                    let name = data[i].name;
                    let time = data[i].time;
                    let content = data[i].content;

                    if(user == 0) {
                        str_content += '<div class="message message-sent">\n' +
                            '                    <div class="message-content">\n' +
                            '                        <div class="message-bubble">\n' +
                            '                            <div class="message-text">' + content + '</div>\n' +
                            '                        </div>\n' +
                            '                    </div>\n' +
                            '                    <div class="message-time">' + time + '</div>\n' +
                            '                </div>';
                    } else {
                        str_content += '<div class="message message-received">\n' +
                            '                    <img src="' + profile + '">\n' +
                            '\n' +
                            '                    <div class="message-content">\n' +
                            '                        <div class="message-name">' + name + '</div>\n' +
                            '                        <div class="message-bubble">\n' +
                            '                            <div class="message-text">' + content + '</div>\n' +
                            '                        </div>\n' +
                            '                    </div>\n' +
                            '                    <div class="message-time">' + time + '</div>\n' +
                            '                </div>';
                    }
                }

                $('#chat_content').html(str_content);
                $('#chat_content').scrollTop(999999);
            },
            error: function(data) {
                hideLoadingProgress();
                showNotification("오류", "Network Error..", "error");
            }
        })
    }
</script>
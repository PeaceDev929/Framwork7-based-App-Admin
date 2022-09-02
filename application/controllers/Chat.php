<?php
class Chat extends MY_Controller {
    protected $page_index = 1;
    protected $page_title = '1:1 Chatting Manage';

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load_view("chat/index");
    }

    public function getCSList() {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE 1=1";
        switch ($search_type) {
            case 1: //name
                $where .= " AND usr_name LIKE '%$search_str%'";
                break;
            default:
                break;
        }

        $sql_total = <<<EOT
            select * from (select R.*, U.name usr_name,
            (select count(*) from chat where room=R.uid and time > IFNULL((select time from chat_room_visit_log where user=0 and room=R.uid limit 1), "")) unread_cnt
             from chat_room R 
             join `user` U on R.user=U.uid where R.type=4) T $where
EOT;

        $sql = $sql_total . " $limit";

        $this->db->trans_begin();
        $arr_data = $this->db->query($sql)->result();
        $total_data_cnt = $this->db->query($sql_total)->num_rows();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        if (count($arr_data) > 0) {
            $recordsTotal = $total_data_cnt;
            $recordsFiltered = $recordsTotal;
        } else {
            $recordsTotal = 0;
            $recordsFiltered = $recordsTotal;
        }

        $return_data = array();

        $idx = 1;
        foreach ($arr_data as $row) {
            $temp = array();

            $temp[0] = $temp['uid'] = $row->uid;
            $temp[1] = $temp['name'] = $row->usr_name;
            $temp[2] = $temp['unread_cnt'] = $row->unread_cnt;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }

    public function get_chat_list_by_room() {
        $room = $this->input->post("uid");

        //방문이력
        $visit_info = [
            'user' => 0,
            'room' => $room,
        ];

        $visit_log = $this->db->get_where("chat_room_visit_log", $visit_info)->row();
        if($visit_log == null) {
            $visit_info['time'] = date("Y-m-d H:i:s");
            $this->db->insert("chat_room_visit_log", $visit_info);
        } else {
            $this->db->update("chat_room_visit_log", ['time' => date("Y-m-d H:i:s")], ['uid' => $visit_log->uid]);
        }

        $sql = <<<EOT
            select C.*, U.name, U.profile from chat C 
            left join user U on C.user=U.uid  where C.room=$room order by C.time asc
EOT;

        $arr_list = $this->db->query($sql)->result();
        $cur_date = _get_current_date();
        foreach($arr_list as $item) {
            $itemDate = _date_format($item->time, 'Y-m-d');
            if ($itemDate != $cur_date) {
                $item->time =  _date_format($item->time, 'Y.m.d');
            } else {
                $item->time = _date_format($item->time, 'a h:i');
            }
            $item->profile = _get_file_url($item->profile) == "" ? base_url("assets/images/ic_user_profile.png") : _get_file_url($item->profile);
        }

        die(json_encode($arr_list));
    }

    public function send_chat() {
        $room = $this->input->post("room");
        $content = $this->input->post("content");

        $insert_data = [
            'room' => $room,
            'content'=> $content,
            'time' => date("Y-m-d H:i:s"),
        ];

        if($this->db->insert("chat", $insert_data)) {
            $usr_uid = $this->db->get_where("chat_room", ['uid' => $room])->row("user");
            if($usr_uid != null) {
                $usr_info = $this->db->get_where("user", ['uid' => $usr_uid])->row();
                if($usr_info != null) {
                    //TODO: send push
                    $this->load->model("MAlarm");
                    $this->MAlarm->add_alarm($usr_uid, $room, ALARM_TYPE_QNA_REPLY);
                }
            }
        }


        //방문이력
        $visit_info = [
            'user' => 0,
            'room' => $room,
        ];

        $visit_log = $this->db->get_where("chat_room_visit_log", $visit_info)->row();
        if($visit_log == null) {
            $visit_info['time'] = date("Y-m-d H:i:s");
            $this->db->insert("chat_room_visit_log", $visit_info);
        } else {
            $this->db->update("chat_room_visit_log", ['time' => date("Y-m-d H:i:s")], ['uid' => $visit_log->uid]);
        }

        die("success");
    }

    public function removeRoom() {
        $str_arr_uid = $this->input->post("arr_uid");

        $arr_uid = explode(",", $str_arr_uid);
        foreach($arr_uid as $uid) {
            $this->db->delete("chat_room", ['uid' => $uid]);
            $this->db->delete("chat", ['room' => $uid]);
        }

        echo "success";
    }
}
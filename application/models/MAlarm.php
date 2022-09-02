<?php
class MAlarm extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function add_alarm($target_usr_uid, $target_uid, $type, $sub_value = 0) {
        $content = "";
        switch ($type) {
            case ALARM_TYPE_POINT: //Point지불, 출금
                if($sub_value > 0) {
                    $content = number_format($sub_value)." Point 지불 되였습니다.";
                } else {
                    $content = number_format(-$sub_value)." Point 출금 되였습니다.";
                }
                break;
            case ALARM_TYPE_QNA_REPLY: //1:1 문의답변
                $content = "1:1문의에 답변이 완료되였습니다.";
                break;
            case ALARM_TYPE_PUSH: //PUSH
                break;
            case  ALARM_TYPE_NOTICE:    //notice
                $content = $sub_value."공지가 등록되었습니다.";
                break;
        }

        $insert_log = [
            'time' => date("Y-m-d H:i:s"),
            'user' => $target_usr_uid,
            'type' => $type,
            'content' => $content,
            'target_uid' => $target_uid,
        ];

        if($this->db->insert("alarm", $insert_log)) {
            $usr_info = $this->db->get_where("user", ['uid' => $target_usr_uid])->row();
            $push_flag = 0;
//            switch ($type) {
//                case ALARM_TYPE_BOARD_REPLY: //내가 작성한 Review에 Comment
//                    $push_flag = $usr_info->comment_alarm;
//                    break;
//                case ALARM_TYPE_POINT: //내가 작성한 Freetalk에 Comment
//                    $push_flag = $usr_info->comment_alarm;
//                    break;
//                case ALARM_TYPE_INTEREST: //내가 작성한 Review comment에 답글이 달린경우
//                    $push_flag = $usr_info->recommendation_alarm;;
//                    break;
//            }

            if($usr_info->fcm_token != "") {
                //TODO: send push
                $dev_type = $usr_info->device_type;    //1:android, 2:ios
                $this->load->library('fcm_library');
                $push_data = [
                    'type' => $type,
                    'title' => "Bunyang",
                    'message' => $content,
                    'target_uid' => $target_uid
                ];
//                $this->fcm_library->_send_push($dev_type, $usr_info->fcm_token, $push_data);
            }
        }
    }
}
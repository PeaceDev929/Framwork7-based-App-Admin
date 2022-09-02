<?php


class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        if ($this->session->has_userdata($this->g_manager_label)) {
            redirect('User');
        } else {
            $this->load->view('login');
        }
    }

    public function login()
    {
        $data = array(
            'nickname' => $this->input->post('usrid'),
            'password' => $this->input->post("password")
        );
        $manager = $this->db->get_where('admin', $data)->row();

        if ($manager == null) {
            echo "no_exist";
        } else {
            $this->session->set_userdata($this->g_manager_label, $manager);

            echo "success";
        }
    }

    public function logout()
    {
        $this->session->unset_userdata($this->g_manager_label);

        redirect($this->index());
    }

    public function get_new_msg_cnt() {
        $admin_info = $this->session->userdata($this->g_manager_label);
        if($admin_info != null) {
            $last_visit_info = $this->db->get_where("chat_room_visit_log", ['user' => 0, 'room' => 0])->row();
            if($last_visit_info != null) {
                $last_visit_time = $last_visit_info->time;
            }

            $sql = <<<EOT
                select C.* from chat C
                join chat_room R on C.room=R.uid
                where R.type=4 and C.time >= '$last_visit_time'
EOT;

            $new_cnt = $this->db->query($sql)->num_rows();
            if($last_visit_info != null) {
                $this->db->update("chat_room_visit_log", ['time' => date("Y-m-d H:i:s")], ['user' => 0, 'room' => 0]);
            } else {
                $this->db->insert("chat_room_visit_log", [
                    'time' => date("Y-m-d H:i:s"),
                    'user' => 0,
                    'room' => 0
                ]);
            }

            echo $new_cnt;
        } else {
            echo 0;
        }
    }

    //TODO: crontab등록 매일 0시에 호출
    public function deleteFilesByExpiredDate() {
        $this->load->library('mylibrary');
        $url = site_url("Chat/removeFileBg");
        $param = array();
        $this->mylibrary->do_in_background($url, $param);
    }

    public function removeFileBg() {
        $seven_day_ago = date("Y-m-d H:i:s", strtotime("-7 days"));

        $arr_files = $this->db->query("select * from chat where delete_file_flag=0 and type=3 and time <= '$seven_day_ago'")->result();

        foreach($arr_files as $item) {
            unlink(_get_file_path($item->content));
            $this->db->update("chat", ['delete_file_flag' => 1], ['uid' => $item->uid]);
        }
    }
}
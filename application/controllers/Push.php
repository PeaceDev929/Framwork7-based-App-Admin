<?php
class Push extends MY_Controller {
    protected $page_index = 8;
    protected $page_title = 'Push Message';

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load_view("push/index");
    }

    public function send_push() {
        $title = $this->input->post("title");
        $content = $this->input->post("content");

        $this->load->library('mylibrary');

        $url = site_url("Push/send_push_do_background");
        $param = array(
            'title' => $title,
            'content' => $content
        );
        $this->mylibrary->do_in_background($url, $param);

        die("success");
    }

    public function send_push_do_background() {
        $title = $this->input->post("title");
        $content = $this->input->post("content");

        $arr_user = $this->db->get_where("user", ['fcm_token <>' => "", 'status' => 1])->result();

        $this->load->library("fcm_library");
        foreach($arr_user as $user) {
            $this->load->library("fcm_library");
            $data['title'] = $title;
            $data['message'] = $content;
            $this->fcm_library->_send_push($user->device_type, $user->fcm_token, $data);
        }
    }
}
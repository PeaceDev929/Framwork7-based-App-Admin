<?php
require_once(APPPATH . 'core/Common.php');

class Notice extends MY_Controller
{
    protected $page_index = 9;
    protected $page_title = 'Notice Manage';

    protected $models = ['MNotice'];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load_view("notice/index");
    }

    public function getTableData()
    {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE 1=1";

        $where .= " AND title LIKE '%$search_str%'";

        $order_by = " ORDER BY time DESC";
        $sql_total = "SELECT * FROM notice" . $where . $order_by;

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

            $temp[0] = $temp['no'] = $idx;
            $temp[1] = $temp['title'] = $row->title;
            $temp[2] = $temp['uid'] = $row->uid;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }

    public function detail()
    {
        $uid = $this->input->get("uid");

        $info = $this->MNotice->get($uid);

        $this->load_view("notice/detail", [
            'info' => $info
        ]);
    }

    public function register()
    {
        $uid = $this->input->post("uid");
        $title = $this->input->post("title");
        $content = $this->input->post("content");

        $registerData = [
            'title' => $title,
            'content' => $content,
        ];
        if ($uid > 0) {
            $this->MNotice->update($uid, $registerData);
        } else {
            $this->MNotice->insert($registerData);
            $insert_id = $this->db->insert_id();

            $this->load->library('mylibrary');

            $url = site_url("Notice/send_push_do_background");
            $param = array(
                'target_uid' => $insert_id,
                'title' => $title,
            );
            $this->mylibrary->do_in_background($url, $param);
        }

        echo 'success';
    }

    //TODO: push
    public function send_push_do_background() {
        $target_uid = $this->input->post("target_uid");
        $title = $this->input->post("title");

        $arr_user = $this->db->get_where("user", ['fcm_token <>' => "", 'status' => 1])->result();

        foreach($arr_user as $user) {
            //TODO: send push
            $this->load->model("MAlarm");
            $this->MAlarm->add_alarm($user->uid, $target_uid, ALARM_TYPE_NOTICE, $title);
        }
    }

    public function deleteItem()
    {
        $uids = $this->input->post("uids");

        if (!_is_array($uids)) {
            $uids = [$uids];
        }

        $ret = "success";
        foreach ($uids as $uid) {
            $this->MNotice->delete($uid);
        }

        echo $ret;
    }
}
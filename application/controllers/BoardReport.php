<?php
class BoardReport extends MY_Controller {
    protected $page_index = 13;
    protected $page_title = 'Board Report Manage';

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load_view("board_report/index");
    }

    public function getTableData() {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE 1=1";

        if($search_type == 1) {
            $where .= " AND nickname LIKE '%$search_str%'";
        } else if($search_type == 2) {
            $where .= " AND title LIKE '%$search_str%'";
        } else {
            $where .= " AND (title LIKE '%$search_str%' or nickname LIKE '%$search_str%')";
        }


        $order_by = " ORDER BY reg_time DESC";
        $sql_total = <<<EOT
            select * from (select R.*, U.nickname, B.title
             from board_report R
            join user U on R.usr_uid=U.uid
            join board B on R.board_uid=B.uid) T $where $order_by
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

            $temp[0] = $temp['time'] = date("y.m.d H:i", strtotime($row->reg_time));
            $temp[1] = $temp['nickname'] = $row->nickname;
            $temp[2] = $temp['board_title'] = $row->title;
            $temp[3] = $temp['board_uid'] = $row->board_uid;
            $temp[4] = $temp['usr_uid'] = $row->usr_uid;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }
}
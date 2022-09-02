<?php
class Block extends MY_Controller {
    protected $page_index = 14;
    protected $page_title = 'Block Manage';

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load_view("block/index");
    }

    public function getTableData() {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE 1=1";

        if($search_type == 1) {
            $where .= " AND usr_nickname LIKE '%$search_str%'";
        } else if($search_type == 2) {
            $where .= " AND target_usr_nickname LIKE '%$search_str%'";
        } else {
            $where .= " AND (usr_nickname LIKE '%$search_str%' or target_usr_nickname LIKE '%$search_str%')";
        }


        $order_by = " ORDER BY reg_time DESC";
        $sql_total = <<<EOT
            select * from (select R.*, U.nickname usr_nickname, P.nickname target_usr_nickname
             from usr_block R
            join user U on R.usr_uid=U.uid
            join user P on R.target_usr_uid=P.uid) T $where $order_by
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
            $temp[1] = $temp['nickname'] = $row->usr_nickname;
            $temp[2] = $temp['target_nickname'] = $row->target_usr_nickname;
            $temp[3] = $temp['usr_uid'] = $row->usr_uid;
            $temp[4] = $temp['target_usr_uid'] = $row->target_usr_uid;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }
}
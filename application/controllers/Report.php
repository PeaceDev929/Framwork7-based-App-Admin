<?php
class Report extends MY_Controller {
    protected $page_index = 10;
    protected $page_title = 'Report Manage';

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load_view("report/index");
    }

    public function getTableData()
    {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE 1=1";

        $where .= " AND (usr_nickname LIKE '%$search_str%' or target_nickname like '%$search_str%')";

        $order_by = " ORDER BY time DESC";
        $sql_total = <<<EOT
            select * from (select R.*, U.nickname usr_nickname, P.nickname target_nickname,
             (select count(*) from report where target=R.target and uid <= R.uid) reported_cnt
             from report R
            join user U on R.user=U.uid
            join user P on R.target=P.uid) T $where $order_by
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

            $temp[0] = $temp['time'] = date("y.m.d H:i", strtotime($row->time));
            $temp[1] = $temp['target_nickname'] = $row->target_nickname;
            $temp[2] = $temp['report_cnt'] = $row->reported_cnt;
            $temp[3] = $temp['usr_nickname'] = $row->usr_nickname;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }
}
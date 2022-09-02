<?php
class Board extends MY_Controller {
    protected $page_index = 3;
    protected $page_title = 'Board Manage';

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load_view("board/index");
    }

    public function getTableData() {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE 1=1";
        $order_by = " ORDER BY time DESC";

        $order = $this->input->post("order");
        if($order[0]['dir'] == "asc") {
            $order_by = " ORDER BY time asc";
        } else {
            $order_by = " ORDER BY time desc";
        }

        switch ($search_type) {
            case 1: //board
                $where .= " AND B.title LIKE '%$search_str%'";
                break;
            case 2: //nickname
                $where .= " AND U.nickname LIKE '%$search_str%'";
                break;
            default:
                break;
        }

        $sql_total = <<<EOT
            select B.*, U.nickname from board B
            join user U on B.user=U.uid $where $order_by
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

            $temp[0] = $temp['time'] = _date_format($row->time, "y.m.d H:i");
            $temp[1] = $temp['title'] = $row->title;
            $temp[2] = $temp['nickname'] = $row->nickname;
            $temp[3] = $temp['uid'] = $row->uid;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }

    public function deleteItem() {
        $uid = $this->input->post("uid");
        if($this->db->delete("board", ['uid' => $uid])) {
            echo "success";
        } else {
            echo "error";
        }
    }
}
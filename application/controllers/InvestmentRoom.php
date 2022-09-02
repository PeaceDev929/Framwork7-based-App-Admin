<?php
class InvestmentRoom extends MY_Controller {
    protected $page_index = 11;
    protected $page_title = '투자방관리';
    
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load_view("investment_room/index");
    }

    public function edit_room() {
        $uid = $this->input->get("uid") != null ? $this->input->get("uid") : 0;

        $info = null;
        if($uid > 0) {
            $info = $this->db->get_where("investment_room", ['uid' => $uid])->row();
            if($info != null) {
                $info->thumbnail_url = _get_file_url($info->thumbnail);
                $info->image_url = _get_file_url($info->image);
            }
        }

        $this->load_view("investment_room/edit_room", [
            'uid' => $uid,
            'info' => $info
        ]);

    }

    public function getTableData() {
        $limit = SSP::limit($_POST);

        $where = " WHERE 1=1";

        $sql_total = "select * from investment_room";

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
            $temp[1] = $temp['name'] = $row->name;
            $temp[2] = $temp['short_name'] = $row->short_name;
            $temp[3] = $temp['thumbnail'] = _get_file_url($row->thumbnail);
            $temp[4] = $temp['image'] = _get_file_url($row->image);
            $temp[5] = $temp['ord'] = $row->ord;
            $temp[6] = $temp['status'] = $row->status;
            $temp[7] = $temp['uid'] = $row->uid;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }

    public function register() {
        $uid = $this->input->post("uid");
        $name = $this->input->post("name");
        $short_name = $this->input->post("short_name");
        $thumbnail = $this->input->post("thumbnail");
        $image = $this->input->post("image");
        $limit_cnt = $this->input->post("limit_cnt");
        $status = $this->input->post("status");
        $ord = $this->input->post("ord");

        $insert_data = [
            'name' => $name,
            'short_name' => $short_name,
            'thumbnail' => $thumbnail,
            'image' => $image,
            'limit_cnt' => $limit_cnt,
            'status' => $status,
            'ord' => $ord
        ];

        if($uid == 0) {
            $max_ord = $this->db->query("select IFNULL(max(ord), 0) max_ord from investment_room")->row("max_ord");
            $insert_data['ord'] = $max_ord + 1;

            $this->db->insert("investment_room", $insert_data);
            $insert_id = $this->db->insert_id();
            if($insert_id > 0) {
                echo "success";
            } else {
                echo "error";
            }
        } else {
            if($this->db->update("investment_room", $insert_data, ['uid' => $uid])) {
                echo "success";
            } else {
                echo "error";
            }
        }
    }
}
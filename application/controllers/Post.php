<?php
require_once(APPPATH . 'core/Common.php');

class Post extends MY_Controller
{
    protected $page_index = 6;
    protected $page_title = 'Bunyang Post';

    protected $models = ['MPost'];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load_view("post/index");
    }

    public function getTableData()
    {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE 1=1";

        $where .= " AND name LIKE '%$search_str%'";

        $order_by = " ORDER BY time DESC";
        $sql_total = "SELECT * FROM post" . $where . $order_by;

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

            $temp[0] = $temp['id'] = $row->uid;
            $temp[1] = $temp['time'] = _date_format($row->time, "y.m.d H:i");
            $temp[2] = $temp['thumbnail'] = _get_file_url($row->thumbnail) == '' ? Common::getDefaultImage() : _get_file_url($row->thumbnail);
            $temp[3] = $temp['name'] = $row->name;
            $temp[4] = $temp['type'] = array_key_exists($row->type, REALTY_TYPE) ? REALTY_TYPE[$row->type] : '';
            $temp[5] = $temp['uid'] = $row->uid;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }

    public function detail()
    {
        $uid = $this->input->get("uid");

        $info = $this->MPost->get($uid);

        $this->load_view("post/detail", [
            'info' => $info
        ]);
    }

    public function register()
    {
        $uid = $this->input->post("uid");
        $type = $this->input->post("type");
        $name = $this->input->post("title");
        $sub_name = $this->input->post("sub_title");
        $thumbnail = $this->input->post("thumbnail");
        $images = $this->input->post("images");
        $extra_info = $this->input->post("extra_info");
        $danji_info = $this->input->post("danji_info");
        $address = $this->input->post("address");
        $latitude = $this->input->post("latitude");
        $longitude = $this->input->post("longitude");
        $content = $this->input->post("content");

        $registerData = [
            'type' => $type,
            'name' => $name,
            'sub_name' => $sub_name,
            'thumbnail' => $thumbnail,
            'images' => $images,
            'extra_info' => $extra_info,
            'danji_info' => $danji_info,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'content' => $content,
        ];

        if ($uid > 0) {
            $this->MPost->update($uid, $registerData);
        } else {
            $this->MPost->insert($registerData);
        }

        echo 'success';
    }

    public function deleteItem()
    {
        $uids = $this->input->post("uids");

        if (!_is_array($uids)) {
            $uids = [$uids];
        }

        $ret = "success";
        foreach ($uids as $uid) {
            $this->MPost->delete($uid);
        }

        echo $ret;
    }

    public function top_setting() {
        $this->load_view("post/setting");
    }

    public function getTopSettingTableData() {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE 1=1";

        if($search_type != 0) {
            $where .= " AND type=$search_type";
        }

        $where .= " AND name LIKE '%$search_str%'";

        $order_by = " ORDER BY time DESC";
        $sql_total = "SELECT * FROM post" . $where . $order_by;

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
            $temp[1] = $temp['thumbnail'] = _get_file_url($row->thumbnail) == '' ? Common::getDefaultImage() : _get_file_url($row->thumbnail);
            $temp[2] = $temp['name'] = $row->name;
            $temp[3] = $temp['type'] = array_key_exists($row->type, REALTY_TYPE) ? REALTY_TYPE[$row->type] : '';
            $temp[4] = $temp['uid'] = $row->uid;
            $temp[5] = $temp['main_flag'] = $row->main_flag;
            $temp[6] = $temp['top_flag'] = $row->top_flag;
            $temp[7] = $temp['main_bottom_flag'] = $row->main_bottom_flag;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }

    public function change_main_flag() {
        $uid = $this->input->post("uid");
        $info = $this->db->get_where("post", ['uid' => $uid])->row();
        if($info->main_flag == 0) {
            $main_cnt = $this->db->get_where("post", ['main_flag' => 1])->num_rows();
            if($main_cnt >= 8) {
                die("cnt_error");
            }
        }
        if($this->db->update("post", ['main_flag' => 1 - $info->main_flag], ['uid' => $uid])) {
            echo "success";
        } else {
            echo "error";
        }
    }

    public function change_main_bottom_flag() {
        $uid = $this->input->post("uid");
        $info = $this->db->get_where("post", ['uid' => $uid])->row();
        if($info->main_bottom_flag == 0) {
            $main_cnt = $this->db->get_where("post", ['main_bottom_flag' => 1])->num_rows();
            if($main_cnt >= 12) {
                die("cnt_error");
            }
        }
        if($this->db->update("post", ['main_bottom_flag' => 1 - $info->main_bottom_flag], ['uid' => $uid])) {
            echo "success";
        } else {
            echo "error";
        }
    }

    public function change_top_status() {
        $uid = $this->input->post("uid");
        $info = $this->db->get_where("post", ['uid' => $uid])->row();
        if($info->top_flag == 0) {
            $checked_cnt = $this->db->get_where("post", ['top_flag' => 1, 'type' => $info->type])->num_rows();
            if($checked_cnt >= 4) {
                die("cnt_error");
            }
        }
        if($this->db->update("post", ['top_flag' => 1 - $info->top_flag], ['uid' => $uid])) {
            echo "success";
        } else {
            echo "error";
        }
    }
}
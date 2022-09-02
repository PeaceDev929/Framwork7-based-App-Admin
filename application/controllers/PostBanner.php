<?php
require_once(APPPATH . 'core/Common.php');

class PostBanner extends MY_Controller
{
    protected $page_index = 5;
    protected $page_title = 'Bunyang Banner Manage';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load_view("post_banner/index");
    }


    public function detail()
    {
        $uid = $this->input->get("uid");

        $info = $this->db->get_where("banner_post", ['uid' => $uid])->row();

        $this->load_view("post_banner/detail", [
            'uid' => $uid,
            'info' => $info,
        ]);
    }

    public function register()
    {
        $image = $this->input->post("image");
        $link = $this->input->post("link");
        $uid = $this->input->post("uid");

        $registerData = [
            'image' => $image,
            'link' => $link,
        ];

        if($uid > 0) {
            $this->db->update("banner_post", $registerData, ['uid' => $uid]);
        } else {
            $registerData['reg_time'] = date("Y-m-d H:i:s");
            $this->db->insert("banner_post", $registerData);
        }

        echo 'success';
    }

    public function getTableData()
    {
        $limit = SSP::limit($_POST);

        $where = " WHERE 1=1";

        $order_by = " ORDER BY reg_time DESC";
        $sql_total = "SELECT * FROM banner_post" . $where . $order_by;

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

            $temp[0] = $temp['time'] = _date_format($row->reg_time, "y.m.d H:i");
            $temp[1] = $temp['image'] = _get_file_url($row->image) == '' ? Common::getDefaultImage() : _get_file_url($row->image);
            $temp[2] = $temp['link'] = $row->link;
            $temp[3] = $temp['uid'] = $row->uid;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }

    public function deleteItem() {
        $uid = $this->input->post("uid");
        if($this->db->delete("banner_post", ['uid' => $uid])) {
            echo "success";
        } else {
            echo "error";
        }
    }
}
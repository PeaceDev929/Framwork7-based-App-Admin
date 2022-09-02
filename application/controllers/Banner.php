<?php
require_once(APPPATH . 'core/Common.php');

class Banner extends MY_Controller
{
    protected $page_index = 4;
    protected $page_title = 'Banner Manage';

    protected $models = ['MBanner'];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $infos = [];
        foreach (BANNER_LOCATION as $key => $value) {
            $info = $this->MBanner->get_by(['type' => $key]);
            array_push($infos, $info);
        }

        $this->load_view("banner/index", [
            'infos' => $infos
        ]);
    }


    public function detail()
    {
        $type = $this->input->get("type");

        $info = $this->MBanner->get_by(['type' => $type]);

        $this->load_view("banner/detail", [
            'type' => $type,
            'width' => $info == null ? 0 : $info->width,
            'height' => $info == null ? 0 : $info->height,
            'image' => $info == null ? '' : $info->image,
            'link' => $info == null ? '' : $info->link,
            'post_id' => $info == null ? '0' : $info->post_id,
            'post_info' => $info == null ? null : $this->db->get_where("post", ['uid' => $info->post_id])->row(),
        ]);
    }

    public function register()
    {
        $type = $this->input->post("type");
        $width = $this->input->post("width");
        $height = $this->input->post("height");
        $link = $this->input->post("link");
        $imageName = $this->input->post("image-name");
        $post_id = $this->input->post("post_id");

        if($post_id > 0) {
            $post_info = $this->db->get_where("post", ['uid' => $post_id])->row();
            if($post_info == null) {
                die('post_id_err');
            }
        }

        $existInfo = $this->MBanner->get_by(['type' => $type]);

        $registerData = [
            'type' => $type,
            'width' => $width,
            'height' => $height,
            'image' => $imageName,
            'link' => $link,
            'time' => date("Y-m-d H:i:s"),
            'post_id' => $post_id,
        ];
        if ($existInfo != null) {
            $this->MBanner->update($existInfo->uid, $registerData);
        } else {
            $this->MBanner->insert($registerData);
        }

        echo 'success';
    }

    public function file_upload()
    {
        $upload_result = $this->_file_upload(date('Y/m/d'), 'image', false);
        $image_info = getimagesize(_get_file_path($upload_result));

        die(json_encode([
            "url" => _get_file_url($upload_result),
            "file" => $upload_result,
            "width" => $image_info[0],
            "height" => $image_info[1],
        ]));
    }

    public function getPostTableData()
    {
        $limit = SSP::limit($_POST);
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

            $temp[0] = $temp['name'] = $row->name;
            $temp[1] = $temp['uid'] = $row->uid;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }
}
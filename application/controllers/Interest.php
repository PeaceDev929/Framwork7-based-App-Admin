<?php
class Interest extends MY_Controller {
    protected $page_index = 7;
    protected $page_title = 'Interest Customer Manage';

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load_view("interest/index");
    }

    public function getTableData() {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE 1=1";
        $order_by = " ORDER BY time DESC";

        $order = $this->input->post("order");
        if($order[0]['dir'] == "asc") {
            $order_by = " ORDER BY I.time asc";
        } else {
            $order_by = " ORDER BY I.time desc";
        }

        switch ($search_type) {
            case 1: //name
                $where .= " AND I.name LIKE '%$search_str%'";
                break;
            case 2: //phone
                $where .= " AND I.phone LIKE '%$search_str%'";
                break;
            case 3: //id
                $where .= " AND I.email LIKE '%$search_str%'";
                break;
            default:
                break;
        }

        $sql_total = <<<EOT
            select I.*, U.email applicant_email, U.phone applicant_phone, P.name post_name from interest I
            join user U on I.user=U.uid
            join post P on I.post=P.uid $where $order_by
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
            $temp[1] = $temp['name'] = $row->name;
            $temp[2] = $temp['phone'] = _make_phone_format($row->phone);
            $temp[3] = $temp['phone'] = $row->email;
            $temp[4] = $temp['post_name'] = $row->post_name;
            $temp[5] = $temp['applicant_id'] = $row->applicant_email;
            $temp[6] = $temp['applicant_phone'] = _make_phone_format($row->applicant_phone);

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }
}
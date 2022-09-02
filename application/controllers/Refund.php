<?php
class Refund extends MY_Controller {
    protected $page_index = 12;
    protected $page_title = 'Point Application History';

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->load_view("refund/index");
    }

    public function getTableData() {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE 1=1";
        $order_by = " ORDER BY R.reg_time DESC";

        $order = $this->input->post("order");
        if($order[0]['dir'] == "asc") {
            $order_by = " ORDER BY R.reg_time asc";
        } else {
            $order_by = " ORDER BY R.reg_time desc";
        }

        switch ($search_type) {
            case 1: //phone
                $where .= " AND U.phone LIKE '%$search_str%'";
                break;
            case 2: //name
                $where .= " AND U.name LIKE '%$search_str%'";
                break;
            case 3: //nickname
                $where .= " AND U.nickname LIKE '%$search_str%'";
                break;
            default:
                break;
        }

        $sql_total = <<<EOT
            select R.*, U.nickname nickname, U.phone phone, U.name usr_name, U.email email from point_refund_his R
            join user U on R.user=U.uid $where $order_by
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

            $temp[0] = $temp['time'] = _date_format($row->reg_time, "y.m.d H:i");
            $temp[1] = $temp['name'] = $row->usr_name;
            $temp[2] = $temp['phone'] = _make_phone_format($row->phone);
            $temp[3] = $temp['email'] = $row->email;
            $temp[4] = $temp['str_point'] = number_format($row->point)." P";
            $temp[5] = $temp['status'] = $row->status;
            $temp[6] = $temp['point'] = $row->point;
            $temp[7] = $temp['uid'] = $row->uid;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }

    public function refund_confirm() {
        $uid = $this->input->post("uid");
        $refund_info = $this->db->get_where("point_refund_his", ['uid' => $uid])->row();
        if($refund_info != null) {
            $usr_info = $this->db->get_where("user", ['uid' => $refund_info->user])->row();
            if($usr_info != null) {
                if($usr_info->point >= $refund_info->point) {
                    $this->db->update("user", ['point' => $usr_info->point - $refund_info->point], ['uid' => $refund_info->user]);
                    $this->db->update("point_refund_his", ['status' => 1] , ['uid' => $uid]);
                    $this->db->insert("point_history", [
                        'user' => $refund_info->user,
                        'point' => $refund_info->point,
                        'type' => 2,
                        'time' => date("Y-m-d H:i:s")
                    ]);

                    //TODO: send push
                    $this->load->model("MAlarm");
                    $this->MAlarm->add_alarm($refund_info->user, 0, ALARM_TYPE_POINT, -$refund_info->point);
                    die("success");
                } else {
                    die("point_error");
                }
            } else {
                echo "error";
            }
        } else {
            echo "error";
        }
    }
}
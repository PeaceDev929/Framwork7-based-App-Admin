<?php
require_once(APPPATH . 'core/Common.php');

class User extends MY_Controller
{
    protected $page_index = 0;
    protected $page_title = 'Member Manage';

    protected $models = ['MUser'];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load_view("user/index");
    }

    public function getTableData()
    {
        $limit = SSP::limit($_POST);
        $search_type = $this->input->post("search_type") != null ? $this->input->post("search_type") : 0;
        $search_str = $this->input->post("search_str") != null ? $this->input->post("search_str") : "";

        $where = " WHERE status=1";

        switch ($search_type) {
            case 1:
                $where .= " AND phone LIKE '%$search_str%'";
                break;
            case 2:
                $where .= " AND name LIKE '%$search_str%'";
                break;
            case 3:
                $where .= " AND nickname LIKE '%$search_str%'";
                break;
            default:
                break;
        }

        $order = $this->input->post("order");

        $order_by = " ORDER BY time asc";
        switch ($order[0]['column']) {
            case 0: //signup date
                $order_by = " ORDER BY time ".$order[0]['dir'];
                break;
            case 5: //birthday
                $order_by = " ORDER BY birthday ".$order[0]['dir'];
                break;
            case 6: //gender
                $order_by = " ORDER BY gender ".$order[0]['dir'];
                break;
            case 8: //device
                $order_by = " ORDER BY device_type ".$order[0]['dir'];
                break;
        }

        $sql_total = <<<EOT
            select * from (select U.*, B.bank_name from user U
             left join bank B on B.uid=U.bank_uid) T $where $order_by
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
            $temp[1] = $temp['phone'] = _make_phone_format($row->phone);
            $temp[2] = $temp['name'] = $row->name;
            $temp[3] = $temp['bank_name'] = $row->bank_name;
            $temp[4] = $temp['account_number'] = _make_phone_format($row->account_number);
            $temp[5] = $temp['birthday'] = $row->birthday;
            $temp[6] = $temp['gender'] = array_key_exists($row->gender, GENDER) ? GENDER[$row->gender] : '';
            $temp[7] = $temp['email'] = $row->email;
            $temp[8] = $temp['nickname'] = $row->device_type == 1 ? "Android" : ($row->device_type == 2 ? "iOS" : "Web");
            $temp[9] = $temp['point'] = number_format($row->point) . ' P';
            $temp[10] = $temp['uid'] = $row->uid;

            array_push($return_data, $temp);

            $idx++;
        }

        echo json_encode(SSP::generateOutData($_POST, $return_data, $recordsTotal, $recordsFiltered));
    }

    public function detail()
    {
        $uid = $this->input->get("uid");

        $info = $this->MUser->get($uid);

        $this->load_view("user/detail", [
            'info' => $info,
            'arr_point_his' => $this->db->select('*')
                ->where("user", $uid)
                ->order_by("time", "desc")
                ->get("point_history")
                ->result(),
        ]);
    }

    public function register()
    {
        $uid = $this->input->post("uid");
        $title = $this->input->post("title");
        $content = $this->input->post("content");

        $registerData = [
            'title' => $title,
            'content' => $content,
        ];
        if ($uid > 0) {
            $this->MNotice->update($uid, $registerData);
        } else {
            $this->MNotice->insert($registerData);
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
            $this->MNotice->delete($uid);
        }

        echo $ret;
    }


    public function file_upload()
    {
        $upload_result = $this->_file_upload(date('Y/m/d'), 'img', false);

        echo json_encode([
            'code' => RES_SUCCESS,
            'msg' => '업로드 성공',
            'url' => _get_file_url($upload_result),
            'file' => $upload_result
        ]);
    }

    public function pay_refund_point() {
        $user = $this->input->post("user");
        $point = $this->input->post("point");
        $type = $this->input->post("type");

        $usr_info = $this->db->get_where("user", ['uid' => $user])->row();
        if($usr_info != null) {
            if($type == 1) {    //결제
                $point_his = [
                    'time' => date("Y-m-d H:i:s"),
                    'user' => $user,
                    'point' => $point,
                    'type' => $type
                ];

                $this->db->update("user", ['point' => $usr_info->point + $point], ['uid' => $user]);
                $this->db->insert("point_history", $point_his);
                //TODO: send push
                $this->load->model("MAlarm");
                $this->MAlarm->add_alarm($user, 0, ALARM_TYPE_POINT, $point);
            } else {
                if($point > $usr_info->point) {
                    die("point_err");
                } else {
                    $point_his = [
                        'time' => date("Y-m-d H:i:s"),
                        'user' => $user,
                        'point' => $point,
                        'type' => $type
                    ];

                    $this->db->update("user", ['point' => $usr_info->point - $point], ['uid' => $user]);
                    $this->db->insert("point_history", $point_his);
                    //TODO: send push
                    $this->load->model("MAlarm");
                    $this->MAlarm->add_alarm($user, 0, ALARM_TYPE_POINT, -$point);
                }
            }
        }
        die("success");
    }

    public function withdrawal_usr() {
        $user = $this->input->post("user");
        $pwd = $this->input->post("pwd");

        $admin_info = $this->session->userdata($this->g_manager_label);
        if($admin_info->password == $pwd) {
            $this->db->update("user", ['status' => -1], ['uid' => $user]);
//            $this->db->delete("user", ['uid' => $user]);
            die("success");
        } else {
            die("pwd_err");
        }
    }
}
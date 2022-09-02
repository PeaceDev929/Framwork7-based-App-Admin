<?php
require_once(APPPATH . 'core/Common.php');

/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2017-10-26
 * Time: 오후 7:18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    /**
     * 해당 페이지가 로그인을 요구할지를 결정합니다.
     * false 이면 로그인이 필요치 않은 페이지입니다.
     */
    protected $isCheckPrivilegeController = true;
    /**
     * 현재 로그인한 유저의 정보를 저장합니다.
     */
    protected $g_manager = null;
    protected $g_manager_privilege = null;
    protected $g_manager_label = 'by_manager';
    /**
     * A list of models to be auto-loaded
     */
    protected $models = [];
    /**
     * A formatting string for the model auto-loading feature.
     * The percent symbol (%) will be replaced with the model name.
     */
    protected $model_string = '%';
    /**
     * A list of helpers to be auto-loaded
     */
    protected $helpers = [];
    /**
     * A list of libraries to be auto-loaded
     */
    protected $libraries = [];

    /**
     * 오류정보
     */
    private $error_title = '알림';
    private $error_class = 'success';
    private $error_msg = '';
    private $error_flag = false;

    /**
     * 페지정보
     */
    protected $page_index = 0;
    protected $page_title = '';


    public function __construct()
    {
        parent::__construct();

        $this->_load_models();
        $this->_load_helpers();
        $this->_load_libraries();
        $this->load->database();

        $this->load->model("SSP");
        $this->load->library("session");

        date_default_timezone_set("Asia/Seoul");
    }

    public function load_view($view_name = '', $data = array(), $flag = true)
    {
        if ($this->session->has_userdata($this->g_manager_label)) {
            $data['g_manager'] = $this->session->userdata($this->g_manager_label);
            $data['page_index'] = $this->page_index;
            $data['page_title'] = $this->page_title;
            if ($flag) {
                $this->load->view("layout/header", $data);
                $this->load->view($view_name, $data);
                $this->load->view("layout/footer");
            } else {
                $this->load->view($view_name, $data);
            }
        } else {
            redirect(site_url("Login/index"));
        }
    }

    /* --------------------------------------------------------------
     * MODEL LOADING
     * ------------------------------------------------------------ */

    /**
     * Load models based on the $this->models array
     */
    private function _load_models()
    {
        foreach ($this->models as $model) {
            $this->load->model($this->_model_name($model));
        }
    }

    /**
     * Returns the loadable model name based on
     * the model formatting string
     *
     * @param String $model model name to load
     * @return String
     */
    protected function _model_name($model)
    {
        return str_replace('%', $model, $this->model_string);
    }

    /* --------------------------------------------------------------
     * HELPER LOADING
     * ------------------------------------------------------------ */

    /**
     * Load helpers based on the $this->helpers array
     */
    private function _load_helpers()
    {
        foreach ($this->helpers as $helper) {
            $this->load->helper($helper);
        }
    }

    /* --------------------------------------------------------------
     * LIBRARY LOADING
     * ------------------------------------------------------------ */

    /**
     * Load libraries based on the $this->libraries array
     */
    private function _load_libraries()
    {
        foreach ($this->libraries as $library) {
            $this->load->library($library);
        }
    }

    /**
     * 파일 업로드 메서드
     * 요청으로 올라온 파일을  목적폴더에 저장한다.
     * 저장이 성공하면 파일명을 귀환한다.
     *
     * @param string $dir_path 업로드 폴더 경로
     * @param string $file_name 업로드 파일명
     * @param boolean $should_redirect 업로드실패시 redirect로 귀환할것인가 결정 // false : api 요청에 대한 응답으로 json으로 리턴
     * @param string $redirect_url 업로드에 실패할 경우 리턴할 URL
     * @param string $file_type 업로드 파일타입
     * @return string
     */

    protected function _file_upload($dir_path, $file_name, $should_redirect = true, $redirect_url = '', $file_type = 'jpg|jpeg|png|mp4')
    {
        // $file_name 으로 올라온 파일이 없다면 빈 문짜열 귀환
        if (!isset($_FILES[$file_name]) || empty($_FILES[$file_name]['tmp_name'])) {
            return '';
        }

        $config['upload_path'] = _make_dir($dir_path);
        $config['allowed_types'] = $file_type;
        $config['file_name'] = _unique_string();

        $this->load->library('upload');
        $this->upload->initialize($config);

        if ($this->upload->do_upload($file_name)) {
            return $dir_path . '/' . $this->upload->data('file_name');
        } else {
            // 파일업로드 실패라면 에러메시지귀환, 실행중지
            if ($should_redirect) {
                $this->_show_res_msg($this->upload->display_errors('', ''), 'error', '오류');
                redirect($redirect_url == '' ? base_url('member/index') : $redirect_url);
            } else {
                die (json_encode([
                    'code' => API_RES_ERR_FILE_UPLOAD,
                    'msg' => $this->upload->display_errors('', '')
                ]));
            }
        }

        return '';
    }

    /**
     * 다중파일업로드 메서드
     * 파라미터설명은 위메서드와 같다.
     *
     * @param string $dir_path
     * @param $file_name
     * @param bool $should_redirect
     * @param string $redirect_url
     * @param string $file_type
     * @return array
     */
    protected function _multi_file_upload($dir_path, $file_name, $should_redirect = true, $redirect_url = '', $file_type = 'jpg|jpeg|png|mp4')
    {
        $files = [];

        // $file_name 으로 올라온 파일이 없다면 빈 문짜열 귀환
        if (empty($_FILES[$file_name]) || count($_FILES[$file_name]['name']) < 1) {
            return $files;
        }

        $this->load->library('upload');

        $config['upload_path'] = _make_dir($dir_path);
        $config['allowed_types'] = $file_type;

        for ($nInd = 0; $nInd < count($_FILES[$file_name]['name']); $nInd++) {
            if (!empty($_FILES[$file_name]['name'][$nInd])) {
                $config['file_name'] = _unique_string();
                $this->upload->initialize($config);

                $_FILES['server_upload_file']['name'] = is_array($_FILES[$file_name]['name']) ? $_FILES[$file_name]['name'][$nInd] : $_FILES[$file_name]['name'];
                $_FILES['server_upload_file']['type'] = is_array($_FILES[$file_name]['type']) ? $_FILES[$file_name]['type'][$nInd] : $_FILES[$file_name]['type'];
                $_FILES['server_upload_file']['tmp_name'] = is_array($_FILES[$file_name]['tmp_name']) ? $_FILES[$file_name]['tmp_name'][$nInd] : $_FILES[$file_name]['tmp_name'];
                $_FILES['server_upload_file']['error'] = is_array($_FILES[$file_name]['error']) ? $_FILES[$file_name]['error'][$nInd] : $_FILES[$file_name]['error'];
                $_FILES['server_upload_file']['size'] = is_array($_FILES[$file_name]['size']) ? $_FILES[$file_name]['size'][$nInd] : $_FILES[$file_name]['size'];

                if ($this->upload->do_upload('server_upload_file')) {
                    array_push($files, $dir_path . DIRECTORY_SEPARATOR . $this->upload->data('file_name'));
                } else {
                    // 파일업로드 실패라면 에러메시지귀환, 실행중지
                    if ($should_redirect) {
                        $this->_show_res_msg($this->upload->display_errors('', ''), 'error', '오류');
                        redirect($redirect_url == '' ? base_url('member/index') : $redirect_url);
                    } else {
                        die (json_encode([
                            'code' => API_RES_ERR_FILE_UPLOAD,
                            'msg' => $this->upload->display_errors('', '')
                        ]));
                    }
                }
            }
        }

        return $files;
    }

    protected function _show_err_msg($error_msg = '', $error_class = 'success', $error_title = '알림')
    {
        $this->error_flag = true;
        $this->error_msg = $error_msg;
        $this->error_class = $error_class;
        $this->error_title = $error_title;

        $error = array(
            'error_flag' => $this->error_flag,
            'error_msg' => $this->error_msg,
            'error_class' => $this->error_class,
            'error_title' => $this->error_title,
        );

        $this->session->set_tempdata('error', $error, 30); //30초동안 오류메세지 보관.
    }

    protected function _set_temp_value($value)
    {
        $this->session->set_flashdata('wiz_temp', $value);
    }

    protected function _get_temp_value()
    {
        return $this->session->flashdata('wiz_temp');
    }

    public function _send_email($to, $subject, $message)
    {
        $this->load->library('email');

        $this->email->from(BENEFIT_EMAIL_ADMIN_EMAIL);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $ret = $this->email->send();

        return $ret;
    }

    public function _send_push($device = 1, $tokens, $data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = "AAAAaI9OeR8:APA91bFxH7Z1WRXmYpCEJBAHdDSXInzU0SfuvBaJA-X8YnZihgnSHxk1QaojufBt7hcFoE7_UpMs9Xr4j8rlseXNrcxRdM2xifVUKovWp-gab5hWQDBnAeZxY1XFDECvj-Zd1clChTyoWKrwPoxf7Enmw3IutSStvQ";
        $headers = array('Authorization: key=' . $api_key, 'Content-Type: application/json');

        if (!is_array($tokens)) {
            $tokens = [$tokens];
        }

        if ($device == 1) {
            // Android device
            $fields = [
                'registration_ids' => $tokens,
                'data' => $data
            ];
        } else {
            // iPhone device
            $fields = [
                'registration_ids' => $tokens,
                'data' => $data,
                'notification' => [
                    'id' => $data['id'],
                    'body' => $data['title'] . ' ' . $data['message'],
                    'type' => $data['type']
                ]
            ];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function _send_sms($phone, $msg)
    {
        if (defined('DEV_MODE') && DEV_MODE) {
            return true;
        }

        $userid = SMS_USER_ID;
        $passwd = SMS_USER_KEY;
        $hpSender = SMS_CALLBACK_PHONE_NUMBER;
        $hpReceiver = $phone;

        //$hpMesg = $msg;
        $hpMesg = iconv("UTF-8", "EUC-KR", $msg);

        $hpMesg = urlencode($hpMesg);
        $endAlert = 0;  // 전송완료알림창 ( 1:띄움, 0:안띄움 )

        // 한줄로 이어쓰기 하세요.
        $send_result = $this->sendSMSMsg("/MSG/send/web_admin_send.htm?userid=$userid&passwd=$passwd&sender=$hpSender&receiver=$hpReceiver&encode=1&end_alert=$endAlert&message=$hpMesg&allow_mms=1");

        return $send_result;
    }

    public function sendSMSMsg($url)
    {
        $fp = fsockopen("211.233.20.184", 80, $errno, $errstr, 10);
        if (!$fp) echo "$errno : $errstr";

        fwrite($fp, "GET $url HTTP/1.0\r\nHost: 211.233.20.184\r\n\r\n");
        $flag = 0;

        $out = '';
        while (!feof($fp)) {
            $row = fgets($fp, 1024);

            if ($flag) $out .= $row;
            if ($row == "\r\n") $flag = 1;
        }
        fclose($fp);

        return $out;
    }
}

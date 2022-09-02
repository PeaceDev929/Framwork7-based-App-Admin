<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fcm_library
{
    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->database();
    }

    public function _send_push($device, $tokens, $data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

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
                    'body' => $data['message'],
                    'title' => isset($data['title']) ? $data['title'] : 'Bunyang'
                ]
            ];
        }

        self::curl_request_async($url, $fields);
    }



    //. http://reid.tistory.com/71
    function curl_request_async($url, $params, $type = 'POST')
    {
        $google_api_key = 'AAAAlb-d6AI:APA91bFdiu8MKe_AeDgXGq3WsIYr1BFTMAodMo0yZb5GLMUdL8xAQnSRzdwnjKqjH48gRljDJcxNZ_tI_8q1-qCwXTLVtUR-nCRz5dm_YFK-3biBL6L9hcyACJAte0T1TLaA3SrS75U7';
        $post_string = json_encode($params);

        $parts = parse_url($url);
        if ($parts['scheme'] == 'http') {
            $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
        } else if ($parts['scheme'] == 'https') {
            $fp = fsockopen("ssl://" . $parts['host'], isset($parts['port']) ? $parts['port'] : 443, $errno, $errstr, 30);
        }

        // Data goes in the path for a GET request
        if ('GET' == $type)
            $parts['path'] .= '?' . $post_string;

        $out = "$type " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Authorization: key=" . $google_api_key . "\r\n";
        $out .= "Content-Type: application/json\r\n";
        $out .= "Content-Length: " . strlen($post_string) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        // Data goes in the request body for a POST request
        if ('POST' == $type && isset($post_string))
            $out .= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }

}
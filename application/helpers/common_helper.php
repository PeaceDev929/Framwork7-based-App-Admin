<?php

/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-02-12
 * Time: 오후 3:24
 */

defined('BASEPATH') OR exit('No direct script access allowed');


/**++++++++++++++++++++++++++++++++++++++++++
 * Array 관련 메서드들
 * +++++++++++++++++++++++++++++++++++++++++++*/


if (!function_exists('element')) {
    /**
     * Element
     *
     * Lets you determine whether an array index is set and whether it has a value.
     * If the element is empty it returns NULL (or whatever you specify as the default value.)
     *
     * @param string
     * @param array
     * @param mixed
     * @return    mixed    depends on what the array contains
     */
    function element($item, $array, $default = NULL)
    {
        return is_array($array) && array_key_exists($item, $array) ? $array[$item] : $default;
    }
}

if (!function_exists('array_overlap')) {
    function array_overlap($arr, $val)
    {
        for ($i = 0, $m = count($arr); $i < $m; $i++) {
            if ($arr[$i] === $val) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('_is_array')) {
    /**
     * 주어진 배열이 실지 유효한 배열인가를 귀환한다.
     *
     *
     * @param array $array
     * @return    bool
     */
    function _is_array($array)
    {
        return is_array($array) && (count($array) > 0);
    }
}

if (!function_exists('_get_array')) {
    /**
     * 주어진 문짜열을 divider 에 따라 배열로 변환한다.
     *
     *
     * @param string $subject
     * @param string $divider
     * @return   array
     */

    function _get_array($subject, $divider = "}{")
    {
        if (strlen($subject) > 2 && strpos($subject, '{') > -1 && strpos($subject, '}') > -1) {
            $subject = substr($subject, 1, strlen($subject));
            $subject = substr($subject, 0, strlen($subject) - 1);

            return explode($divider, $subject);
        } else {
            return [$subject];
        }
    }
}

if (!function_exists('_make_array')) {
    /**
     * 배열을 {item}{item} 형식의 문짜열로 변환한다.
     * 빈 배열이라면 빈 문자열을 귀환한다.
     *
     * @param array $array
     * @return   string
     */

    function _make_array($array)
    {
        if (_is_array($array)) {
            return '{' . implode('}{', $array) . '}';
        }

        return '';
    }
}


/**++++++++++++++++++++++++++++++++++++++++++
 * URL 관련 메서드들
 * +++++++++++++++++++++++++++++++++++++++++++*/


if (!function_exists('assets_url')) {
    /**
     * Assets URL
     *
     * Create a local URL based on your basepath. Segments can be passed via the
     * first parameter either as a string or an array.
     *
     * @param string $url
     * @return    string
     */
    function assets_url($url = '')
    {
        $url = trim($url, '/');
        return base_url(config_item('assets_url') . '/' . $url);
    }
}

if (!function_exists('admin_url')) {
    /**
     * MAdmin URL
     *
     * Create a local URL based on your basepath. Segments can be passed via the
     * first parameter either as a string or an array.
     *
     * @param string $url
     * @return    string
     */
    function admin_url($url = '')
    {
        $url = trim($url, '/');
        return site_url(config_item('admin_url') . '/' . $url);
    }
}


/**++++++++++++++++++++++++++++++++++++++++++
 * File Upload 관련 메서드들
 * +++++++++++++++++++++++++++++++++++++++++++*/


if (!function_exists('_unique_string')) {
    /**
     * 유일 문자열로 파일명 생성
     *
     * 업로드한 이미지를 서버에 저장시 저장되는 파일명을 생성하여 귀환한다.
     *
     * @param string $ext
     * @return    string $file_name
     */
    function _unique_string($ext = '')
    {
        $returnVar = "" . round(microtime(true) * 1000) . rand(1000, 9999);
        if (!empty($ext)) {
            $returnVar .= "." . $ext;
        }
        return $returnVar;
    }
}

if (!function_exists('_file_exists')) {
    /**
     * 주어진 경로에 파일이 존재하는가를 판단한다.
     *
     *
     * @param string $file_path
     * @return   bool
     */
    function _file_exists($file_path)
    {
        return (file_exists($file_path) && is_file($file_path));
    }
}

if (!function_exists('_make_dir')) {
    /**
     * 폴더생성
     *
     * 주어진 경로에 폴더를 생성한다.
     *
     * @param string $path
     * @param bool $create_mode
     * @return   string $folder_path
     */
    function _make_dir($path, $create_mode = true)
    {
        $dirs = explode('/', $path);
        $mkpath = UPLOAD_PATH;

        $CI = &get_instance();
        $CI->load->helper('file');

        $data = '<!DOCTYPE html>
							<html>
							<head>
								<title>403 Forbidden</title>
							</head>
							<body>
							
							<p>Directory access is forbidden.</p>
							
							</body>
							</html>';

        if (!file_exists($mkpath)) {
            mkdir($mkpath, 0777);

            // 해당 폴더에 index.html 파일을 생성
            write_file($mkpath . DIRECTORY_SEPARATOR . 'index.html', $data);
        }

        foreach ($dirs as $dirname) {
            if ($dirname !== '') {
                $mkpath .= DIRECTORY_SEPARATOR . $dirname;
                if (!file_exists($mkpath) && $create_mode) {
                    mkdir($mkpath, 0777);

                    // 해당 폴더에 index.html 파일을 생성
                    write_file($mkpath . DIRECTORY_SEPARATOR . 'index.html', $data);
                }
            } else
                break;
        }

        return $mkpath;
    }
}

if (!function_exists('_remove_dir')) {
    /**
     * 폴더삭제
     *
     * 주어진 경로에 폴더를 삭제한다.
     *
     * @param string $path
     * @param bool $delete_dir
     * @return   void
     */
    function _remove_dir($path, $delete_dir = false)
    {
        if (!file_exists($path) && !is_dir($path)) {
            return;
        }

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($it as $file) {
            if (in_array($file->getBasename(), array('.', '..'))) {
                continue;
            } elseif ($file->isDir()) {
                rmdir($file->getPathname());
            } elseif ($file->isFile() || $file->isLink()) {
                unlink($file->getPathname());
            }
        }

        if ($delete_dir) {
            rmdir($path);
        }
    }
}

if (!function_exists('_move_target_folder')) {
    /**
     * 파일이동
     *
     * 업로드 temp 폴더에 있는 파일을 주어진 목적경로로 이동한다.
     *
     * @param string $folder_name
     * @param integer $user_id
     * @param string $file_name
     * @return   void
     */
    function _move_target_folder($folder_name, $user_id, $file_name)
    {
        if (empty($file_name))
            return;

        $dst_dir_path = _make_dir($folder_name . DIRECTORY_SEPARATOR . $user_id);
        $src_dir_path = _make_dir('temp');

        if (_file_exists($src_dir_path . DIRECTORY_SEPARATOR . $file_name))
            rename($src_dir_path . DIRECTORY_SEPARATOR . $file_name, $dst_dir_path . DIRECTORY_SEPARATOR . $file_name);
    }
}

if (!function_exists('_get_file_url')) {
    /**
     * 파일의 url 을 귀환
     *
     * 주어진 경로에 있는 파일에 대한 URL 을 귀환한다.
     *
     * @param string $file_upload_path
     * @param bool $is_thumbnail
     * @param string $default_file_path
     * @return string $url
     */
    function _get_file_url($file_upload_path, $default_file_path = '', $is_thumbnail = false)
    {
        if (strpos($file_upload_path, "http:") === 0 || strpos($file_upload_path, "https:") === 0)
            return $file_upload_path;

//		if ($default_file_path == '') {
//			$default_file_path = assets_url('client/img/user_default.png');
//		}

        $file_path = _make_dir('', false) . DIRECTORY_SEPARATOR . $file_upload_path;
//return $file_path;
        if (_file_exists($file_path)) {
            if ($is_thumbnail) {
                return _file_exists(_get_thumbnail($file_path)) ? _get_thumbnail(base_url(UPLOAD_URL . $file_upload_path)) : base_url(UPLOAD_URL . $file_upload_path);
            } else {
//                return base_url(UPLOAD_URL . $file_upload_path);
                return UPLOAD_URL . $file_upload_path;
            }
        } else {
            return $default_file_path;
        }
    }
}

if (!function_exists('_get_thumbnail')) {
    /**
     * 썸네일파일 경로/URL 리턴
     *
     * @param string $src_profile_path
     * @return   string
     */

    function _get_thumbnail($src_profile_path)
    {
        // 이미지파일경로를 파일명까지와 확장자로 분리한다.
        $dot_pos = strrpos($src_profile_path, '.');
        $dst_file_path = substr($src_profile_path, 0, $dot_pos) . '_t' . substr($src_profile_path, $dot_pos);

        return $dst_file_path;
    }
}

if (!function_exists('_get_file_path')) {
    /**
     * 파일의 path 을 귀환
     *
     * 주어진 경로에 있는 파일에 대한 PATH 을 귀환한다.
     *
     * @param string $file_upload_path
     * @param string $default_image_path
     * @return   string $url
     */
    function _get_file_path($file_upload_path, $default_image_path = '')
    {
        $file_path = _make_dir('', false) . DIRECTORY_SEPARATOR . $file_upload_path;
//		return _file_exists($file_path) ? $file_path : $default_image_path;
        return $file_path;
    }
}

if (!function_exists('_remove_file')) {
    /**
     * 파일삭제
     *
     * 주어진 경로의 파일을 삭제한다.
     *
     * @param string $file_path
     * @return   bool
     */
    function _remove_file($file_path)
    {
        if (_file_exists($file_path))
            return unlink($file_path);
        else
            return true;
    }
}

if (!function_exists('_get_random_string')) {
    /**
     * 랜덤 알파벳문자열 생성
     *
     * 주어진 length 만한 랜덤 알파벳문자열을 생성한다.
     *
     * @param int $length 랜덤 알파벳문자열길이
     * @return string
     */
    function _get_random_string($length = 6)
    {
//		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}


/**++++++++++++++++++++++++++++++++++++++++++
 * Pagination 관련 메서드들
 * +++++++++++++++++++++++++++++++++++++++++++*/


if (!function_exists('_get_page_count')) {
    /**
     * 현시갯수에 따르는 페이지수를 생성한다.
     *
     * @param integer $total_size 전체 데이터갯수
     * @param integer $display_num 한 페이지당 현시갯수
     * @return integer
     */
    function _get_page_count($total_size, $display_num)
    {
        return ($display_num == 0) ? 0 : ceil($total_size / $display_num);
    }
}

if (!function_exists('_get_page_offset')) {
    /**
     * 페이지번호에 따르는 offset 을 귀환한다.
     *
     * @param integer $page_num 현재 페이지번호
     * @param integer $display_num 한 페이지당 현시갯수
     * @return integer
     */
    function _get_page_offset($page_num, $display_num)
    {
        return ($page_num > 1) ? (($page_num - 1) * $display_num) : 0;
    }
}


/**++++++++++++++++++++++++++++++++++++++++++
 * Date & Time 관련 메서드들
 * +++++++++++++++++++++++++++++++++++++++++++*/


if (!function_exists('_get_current_time')) {
    /**
     * 현재 서버시간을 귀환한다.
     *
     * @param string $format
     * @return string
     */
    function _get_current_time($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }
}

if (!function_exists('_get_current_date')) {
    /**
     * 현재 서버날짜를 귀환한다.
     *
     * @param string $format
     * @return string
     */
    function _get_current_date($format = 'Y-m-d')
    {
        return date($format);
    }
}

if (!function_exists('_plus_days')) {
    /**
     * 주어진 서버날짜로부터 며칠후의 날짜를 귀환한다.
     *
     * @param string $date 기준날짜
     * @param integer $day_count 얻으려는 날짜수
     * @param string $format
     * @return string
     */
    function _plus_days($date, $day_count, $format = 'Y-m-d H:i:s')
    {
        $result = strtotime($date) + 60 * 60 * 24 * $day_count;

        return date($format, $result);
    }
}

if (!function_exists('_minus_days')) {
    /**
     * 주어진 서버날짜로부터 며칠전의 날짜를 귀환한다.
     *
     * @param string $date 기준날짜
     * @param integer $day_count 얻으려는 날짜수
     * @param string $format
     * @return string
     */
    function _minus_days($date, $day_count, $format = 'Y-m-d H:i:s')
    {
        $result = strtotime($date) - 60 * 60 * 24 * $day_count;

        return date($format, $result);
    }
}


if (!function_exists('_get_day')) {
    /**
     * 현재 시간으로부터 주어진 날짜구간후/전의 날짜를 귀환
     *
     * @param string $date 날짜 ex: 'now', '+1 day', '+1 week 2 days 4 hours 2 seconds', 'next Thursday'
     * @param string $format
     * @return string
     */
    function _get_day($date, $format = 'Y-m-d H:i:s')
    {
        if ($date == '') {
            return _get_current_time($format);
        }

        $result = date($format, strtotime($date));

        if (!$result) {
            return _get_current_time($format);
        }

        return $result;
    }
}

if (!function_exists('_date_format')) {
    /**
     * 주어진 날짜로부터 원하는 형식의 날자 문자열을 귀환한다.
     *
     * @param string $date 날짜문자열 ex: '2021-08-03 12:12:12'
     * @param string $format
     * @return string
     */
    function _date_format($date, $format = 'Y-m-d H:i:s')
    {
        if ($date == '') {
            return _get_current_time($format);
        }

        $result = date($format, strtotime($date));

        if (!$result) {
            return _get_current_time($format);
        }

        return $result;
    }
}


/**++++++++++++++++++++++++++++++++++++++++++
 * Regular Expression 관련 메서드들
 * +++++++++++++++++++++++++++++++++++++++++++*/


if (!function_exists('_is_email_format')) {
    /**
     * 이메일형식이 유효한가를 검사
     *
     * @param string $email
     *
     * @return bool
     */
    function _is_email_format($email)
    {
        $pattern = '/^(|(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6})$/';
        return preg_match($pattern, $email);
    }
}

if (!function_exists('_is_url_format')) {
    /**
     * URL형식이 유효한가를 검사
     *
     * @param string $email
     *
     * @return bool
     */
    function _is_url_format($email)
    {
        $pattern = '/^(|https?):\/\/([a-z0-9-]+\.)+[a-z0-9]{2,4}.*$/';
        return preg_match($pattern, $email);
    }
}


/**++++++++++++++++++++++++++++++++++++++++++
 * Encrypt & Decrypt 관련 메서드들
 * +++++++++++++++++++++++++++++++++++++++++++*/

if (!function_exists('_hash_pwd')) {
    /**
     * 주어진 문짜열을 암호화한다.
     *
     *
     * @param string $plaintext
     * @return   string
     */

    function _hash_pwd($plaintext)
    {
        return password_hash($plaintext, PASSWORD_DEFAULT);
    }
}

if (!function_exists('_verify_pwd')) {
    /**
     * 평문과 암호문짜열 비교결과를 리턴
     *
     *
     * @param string $plaintext
     * @param string $hashedPassword
     * @return   string
     */

    function _verify_pwd($plaintext, $hashedPassword)
    {
        return password_verify($plaintext, $hashedPassword) ? true : false;
    }
}

if (!function_exists('_is_empty')) {
    /**
     * 주어진 값이 null 값이거나 빈 값인가를 판별한다.
     *
     *
     * @param    $value
     * @return   bool
     */

    function _is_empty($value)
    {
        return is_null($value) || $value == '';
    }
}


if (!function_exists('_compress')) {

    /**
     * 원천파일을 압축 퀄리티로 압축하여 목적파일에 저장한다.
     *
     * @param string $src_file_path 원천파일경로
     * @param string $dst_file_path 목적파일경로
     * @param int $quality 압축 퀄리티
     * @return string
     */
    function _compress($src_file_path = '', $dst_file_path = '', $quality = 100)
    {
        if ($src_file_path == '' || $dst_file_path == '') {
            return '';
        }

        $info = getimagesize($src_file_path);

        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($src_file_path);

        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($src_file_path);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($src_file_path);

        imagejpeg($image, $dst_file_path, $quality);

        return $dst_file_path;
    }
}

if (!function_exists('element_not_empty')) {
    /**
     * 배열필터시 이용되는 메서드
     * 배열내 빈요소를 제거하는데 이용
     *
     * @param string $elem 배열요소
     * @return boolean
     */
    function element_not_empty($elem)
    {
        return $elem != '';
    }
}

if (!function_exists('_server_err_msg')) {
    function _server_err_msg()
    {
        return '서버상태가 불안정합니다. 잠시후 다시 시도해주세요';
    }
}


if (!function_exists('_make_phone_format')) {
    /**
     * 전화번호양식으로 만든다.(010-0000-0000)
     *
     *
     * @param    $phone
     * @return   $str
     */

    function _make_phone_format($phone)
    {
        if (is_null($phone) || $phone == '')
            return '';

        $phone = str_replace('-', '', $phone);

        $s_val = '';
        $e_val = '';
        if (strlen($phone) >= 3) {
            $f_val = substr($phone, 0, 3);
            if (strlen($phone) >= 7) {
                $s_val = substr($phone, 3, 4);
                $e_val = substr($phone, 7, strlen($phone) - 7);
            } else {
                $s_val = substr($phone, 3, strlen($phone) - 3);
            }
        } else {
            $f_val = substr($phone, 0, strlen($phone) - 0);
        }

        return $f_val . (strlen($s_val) == 0 ? '' : '-') . $s_val . (strlen($e_val) == 0 ? '' : '-') . $e_val;
    }
}


if (!function_exists('_get_array_value')) {
    /**
     * 배렬에서 key로부터 따르는 값을 얻는다
     *
     *
     * @param    $key
     * @param    $array
     * @return   $str
     */

    function _get_array_value($key, $array)
    {
        if (is_null($array) || is_null($key))
            return '';

        return array_key_exists($key, $array) ? $array[$key] : '';
    }
}


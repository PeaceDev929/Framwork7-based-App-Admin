<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| File Upload Constants
|--------------------------------------------------------------------------
|
| 파일이 업로드되는 서버경로, 파일의 서버 URL,
| 파일들이 업로드되는 폴더명들을 저장한다.
|
| 파일업로드 경로룰
| 회원 프로필 : UPLOAD_PATH/FOLDER_USER/년/월/일/
|
*/
defined('SERVER_PATH') OR define('SERVER_PATH', FCPATH . "../");
defined('UPLOAD_PATH') OR define('UPLOAD_PATH', FCPATH . '../upload');
defined('UPLOAD_URL') OR define('UPLOAD_URL', '/../upload/');


defined('FFMPEG_PATH') OR define('FFMPEG_PATH', FCPATH . "application/ffmpeg/ffmpeg.exe");
defined('TEMP_PATH') OR define('TEMP_PATH', FCPATH . '../../upload/tmp');
//defined('DEFAULT_USER_IMAGE') OR define('DEFAULT_USER_IMAGE', 'assets/images/ic_default_profile.png'); //디폴트

/*
|--------------------------------------------------------------------------
| Email Constants
|--------------------------------------------------------------------------
|
| Email 전송시 이용되는 관리자명, 관리자이메일
|
*/
defined('EMAIL_ADDR') OR define('EMAIL_ADDR', 'ych04436@gmail.com');//ych04436@gmail.com
defined('EMAIL_NAME') OR define('EMAIL_NAME', '골프');

/*
|--------------------------------------------------------------------------
| Pagination Constants
|--------------------------------------------------------------------------
|
| 페이지네이션시 이용되는 Limit 갯수
|
*/
defined('LIMIT_10') OR define('LIMIT_10', 10);
defined('LIMIT_20') OR define('LIMIT_20', 20);


/*
|--------------------------------------------------------------------------
| DEV Constants
|--------------------------------------------------------------------------
|
| Development/Release Constants
|
*/
defined('DEV_MODE') OR define('DEV_MODE', true);

/*
|--------------------------------------------------------------------------
| SMS Constants
|--------------------------------------------------------------------------
|
| SMS 발송시 이용되는 Constants
|
*/
defined('SMS_USER_ID') OR define('SMS_USER_ID', 'dnlemal123');
defined('SMS_USER_KEY') OR define('SMS_USER_KEY', 'BDkMPwg4Bz1QZVxzBjFQbAQ9UjILMAFvAG0DZQcxUjNXIQ==');
defined('SMS_CALLBACK_PHONE_NUMBER') OR define('SMS_CALLBACK_PHONE_NUMBER', '01049517087');

/*
|--------------------------------------------------------------------------
| API Constants
|--------------------------------------------------------------------------
|
| API Error Constants
|
*/
defined('RES_SUCCESS') OR define('RES_SUCCESS', 0);
defined('RES_ERR_PARAMETER') OR define('RES_ERR_PARAMETER', 1);
defined('RES_ERR_DB') OR define('RES_ERR_DB', 2);
defined('RES_ERR_INFO_NO_EXIST') OR define('RES_ERR_INFO_NO_EXIST', 3);
defined('RES_ERR_INCORRECT') OR define('RES_ERR_INCORRECT', 4);
defined('RES_ERR_DUPLICATE') OR define('RES_ERR_DUPLICATE', 5);
defined('RES_ERR_PRIVILEGE') OR define('RES_ERR_PRIVILEGE', 6);
defined('RES_ERR_FILE_UPLOAD') OR define('RES_ERR_FILE_UPLOAD', 7);
defined('RES_ERR_NO_SESSION') OR define('RES_ERR_NO_SESSION', 8);
defined('RES_ERR_EMAIL_DUP') OR define('RES_ERR_EMAIL_DUP', 9);
defined('RES_ERR_NICKNAME_DUP') OR define('RES_ERR_NICKNAME_DUP', 10);
defined('RES_ERROR_INCORRECT_EMAIL') OR define('RES_ERROR_INCORRECT_EMAIL', 11);
defined('RES_ERROR_INCORRECT_PWD') OR define('RES_ERROR_INCORRECT_PWD', 12);
defined('RES_ERROR_USR_BLOCK') OR define('RES_ERROR_USR_BLOCK', 13);
defined('RES_ERROR_USR_EXIT') OR define('RES_ERROR_USR_EXIT', 14);
defined('RES_ERR_UNKNOWN') OR define('RES_ERR_UNKNOWN', 999);


/*
 * Device Type
 *
 * */
defined('DEV_WEB') OR define('DEV_WEB', 999);
defined('DEV_ANDROID') OR define('DEV_ANDROID', 999);
defined('DEV_IOS') OR define('DEV_IOS', 999);

/*
 * Agree Type
 *
 * */
defined('AGREE_TERM') OR define('AGREE_TERM', 1);
defined('AGREE_PERSONAL') OR define('AGREE_PERSONAL', 2);
defined('AGREE_PROVIDE') OR define('AGREE_PROVIDE', 3);
defined('AGREE_USE') OR define('AGREE_USE', 4);

/*
 * Message Type
 *
 * */
defined('MESSAGE_TITLE') OR define('MESSAGE_TITLE', 1);
defined('MESSAGE_MINE_TEXT') OR define('MESSAGE_MINE_TEXT', 2);
defined('MESSAGE_MINE_IMAGE') OR define('MESSAGE_MINE_IMAGE', 3);
defined('MESSAGE_OTHER_TEXT') OR define('MESSAGE_OTHER_TEXT', 4);
defined('MESSAGE_OTHER_IMAGE') OR define('MESSAGE_OTHER_IMAGE', 5);

/*
 * Realty Type
 *
 * */
defined('REALTY_AP') OR define('REALTY_AP', 1);
defined('REALTY_SS') OR define('REALTY_SS', 2);
defined('REALTY_OT') OR define('REALTY_OT', 3);
defined('REALTY_JS') OR define('REALTY_JS', 4);
defined('REALTY_ET') OR define('REALTY_ET', 5);

defined('REALTY_TYPE') OR define('REALTY_TYPE', ['1'=>'Apart', '2'=>'ShopST', '3'=>'Officetel', '4'=>'Jishan', '5'=>'Etc']);

/*
 * Gender Type
 *
 * */
defined('GENDER_MALE') OR define('GENDER_MALE', 1);
defined('GENDER_FEMALE') OR define('GENDER_FEMALE', 2);
defined('GENDER') OR define('GENDER', ['1' => 'male', '2' => 'female']);

/*
 * User Profile Default Image
 *
 * */
defined('DEFAULT_PROFILE') OR define('DEFAULT_PROFILE', 'assets/images/ic_user_profile.png');

/*
 * Banner Location
 *
 * */
defined('BANNER_LOCATION') OR define('BANNER_LOCATION', ['1' => 'Big Banner1-1', '2' => 'Big Banner1-2', '3' => 'Big Banner1-3', '4' => 'Big Banner1-1', '5' => 'Big Banner1-2', '6' => 'Big Banner1-3', '7' => 'Big Banner1-1', '8'=> 'Big Banner1-2', '9' => 'Big Banner1-3', '10' => 'Small Banner1', '11' => 'Small Banner2-1']);

/*
 * Alarm type
 *
 * */
defined('ALARM_TYPE_POINT') OR define('ALARM_TYPE_POINT', 2);
defined('ALARM_TYPE_QNA_REPLY') OR define('ALARM_TYPE_QNA_REPLY', 4);
defined('ALARM_TYPE_PUSH') OR define('ALARM_TYPE_PUSH', 5);
defined('ALARM_TYPE_NOTICE') OR define('ALARM_TYPE_NOTICE', 6);



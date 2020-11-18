<?php
/**
 * @filesource modules/index/controllers/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Index;

use Kotchasan\Curl;
use Kotchasan\Http\Request;

/**
 * Controller สำหรับแสดงหน้าเว็บ
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * หน้าหลักเว็บไซต์ (index.html)
     * ให้ผลลัพท์เป็น HTML.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        // init cURL
        $curl = new Curl();
        // set API Token
        $curl->setHeaders(array(
            'Authorization' => 'Bearer '.self::$cfg->api_token,
        ));
        // ตรวจสอบ username และ password (demo+demo)
        $result = $curl->post(WEB_URL.'api.php/v1/user/login', array(
            'username' => 'demo',
            'password' => 'demo',
        ));
        //print_r($result);
        $login = json_decode($result);
        if (isset($login->result)) {
            // Refresh token
            $refreshToken = $login->result->refreshToken;
            // อ่านข้อมูลสมาชิกของ username และ password ที่ตรวจสอบจาก Refresh token
            $result = $curl->post(WEB_URL.'api.php/v1/user/me', array(
                'refreshToken' => $refreshToken,
            ));
            //print_r($result);
            $user = json_decode($result, false, 512, JSON_UNESCAPED_UNICODE);
            if (isset($user->result)) {
                // ข้อมูล User
                print_r($user->result);
            } else {
                // ข้อผิดพลาดการอ่านข้อมูล user
                print_r($user);
            }
        } else {
            // ข้อผิดพลาดการ login
            print_r($login);
        }
    }
}

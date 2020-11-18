<?php
/**
 * @filesource modules/index/controllers/api.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Api;

use Kotchasan\Http\Request;

/**
 * api.php
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{
    /**
     * Controller หลักสำหรับ API
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function index(Request $request)
    {
        // รับค่าที่ส่งมาจาก Router
        $module = $request->get('module')->filter('a-z0-9');
        $method = $request->get('method')->filter('a-z');
        $action = $request->get('action')->filter('a-z');
        // แปลงเป็นชื่อคลาส สำหรับ Model เช่น
        // api.php/v1/user/create ได้เป็น V1\User\Model::create
        $className = ucfirst($module).'\\'.ucfirst($method).'\\Model';
        // ตรวจสอบ method
        if (method_exists($className, $action)) {
            // ตรวจสอบ Token แบบ Bearer ที่ header (ถ้ามี)
            if (!empty(self::$cfg->api_token) && !preg_match('/^Bearer\s'.self::$cfg->api_token.'$/', $request->getHeaderLine('Authorization'))) {
                // Token ไม่ถูกต้อง
                $result = array(
                    'error' => array(
                        'code' => 401,
                        'message' => 'Invalid token',
                    ),
                );
            } else {
                // เรียกใช้งาน Class
                $result = createClass($className)->$action($request);
            }
        } else {
            // error ไม่พบ class หรือ method
            $result = array(
                'error' => array(
                    'code' => 400,
                    'message' => 'bad request',
                ),
            );
        }
        // Response คืนค่ากลับเป็น JSON ตาม $result
        $response = new \Kotchasan\Http\Response();
        $response->withHeaders(array(
            'Content-type' => 'application/json; charset=UTF-8',
        ))
            ->withContent(json_encode($result))
            ->send();
    }
}

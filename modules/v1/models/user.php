<?php
/**
 * @filesource modules/v1/models/user.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace V1\User;

use Kotchasan\Http\Request;

/**
 * api.php/v1/user
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * ตรวจสอบการ login
     * คืนค่า refreshToken
     *
     * @param Request $request
     *
     * @return array
     */
    public function login(Request $request)
    {
        if ($request->getMethod() !== 'POST') {
            // ตรวจสอบ Method เช่น POST GET PUT DELETE OPTIONS
            $result = array(
                'error' => array(
                    'code' => 405,
                    'message' => 'Method not allowed',
                ),
            );
        } else {
            // สำหรับเก็บ Error
            $error = array();
            // Username+Password ที่ส่งมา
            $username = $request->post('username')->username();
            $password = $request->post('password')->password();
            // ตรวจสอบค่าที่ส่งมา
            if ($username == '') {
                // ไม่ได้ส่ง Username มา
                $error[] = 'Username cannot be blank';
            }
            if ($password == '') {
                // ไม่ได้ส่ง Password มา
                $error[] = 'Password cannot be blank';
            }
            if (empty($error)) {
                // ตรวจสอบ username กับฐานข้อมูล
                $user = $this->db()->first($this->getTableName('user'), array('username', $username));
                // ตรวจสอบรหัสผ่าน
                if ($user && $user->password === sha1(self::$cfg->password_key.$password.$user->salt)) {
                    // สร้าง Token ชั่วคราว
                    $refreshToken = sha1($user->username.uniqid());
                    // อัปเดทการ login ไปยังฐานข้อมูล
                    $this->db()->update($this->getTableName('user'), array('id', $user->id), array(
                        'token' => $refreshToken,
                        'lastvisited' => time(),
                        'visited' => $user->visited + 1,
                    ));
                    $result = array(
                        'code' => 200,
                        'message' => 'OK',
                        // สำเร็จคืนค่า refreshToken (หรือข้อมูลอื่นๆที่ต้องการ)
                        'result' => array(
                            'refreshToken' => $refreshToken,
                        ),
                    );
                } else {
                    // ไม่พบ user หรือ รหัสผ่านไม่ถูกต้อง
                    $result = array(
                        'code' => 404,
                        'message' => 'Username or Password is incorrect',
                    );
                }
            } else {
                // มี error
                $result = array(
                    'code' => 400,
                    'message' => implode(', ', $error),
                );
            }
        }

        return $result;
    }

    /**
     * คืนค่าข้อมูลส่วนตัว
     * จาก refreshToken
     *
     * @param Request $request
     *
     * @return array
     */
    public function me(Request $request)
    {
        // ค่าที่ส่งมา (refreshToken ที่ได้จากการ login)
        $refreshToken = $request->post('refreshToken')->password();
        if ($refreshToken == '') {
            // ไม่มี Refresh token
            $result = array(
                'code' => 400,
                'message' => 'Refresh token cannot be blank',
            );
        } else {
            // ตรวจสอบ Refresh token กับฐานข้อมูล
            $user = $this->db()->first($this->getTableName('user'), array('token', $refreshToken));
            if ($user) {
                // สำเร็จคืนค่ารายละเอียดของ user ตามที่ต้องการ
                $result = array(
                    'code' => 200,
                    'message' => 'OK',
                    'result' => array(
                        'username' => $user->username,
                        'name' => $user->name,
                    ),
                );
            } else {
                // มี error
                $result = array(
                    'code' => 400,
                    'message' => 'invalid Refresh token',
                );
            }
        }

        return $result;
    }
}

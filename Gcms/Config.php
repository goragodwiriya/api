<?php
/**
 * @filesource Gcms/Config.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Gcms;

/**
 * Config Class สำหรับ GCMS.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Config extends \Kotchasan\Config
{
    /**
     * กำหนดอายุของแคช (วินาที)
     * 0 หมายถึงไม่มีการใช้งานแคช.
     *
     * @var int
     */
    public $cache_expire = 5;
    /*
     * คีย์สำหรับการเข้ารหัส ควรแก้ไขให้เป็นรหัสของตัวเอง
     * ตัวเลขหรือภาษาอังกฤษเท่านั้น ไม่น้อยกว่า 10 ตัว
     *
     * @var string
     */
    /**
     * @var string
     */
    public $password_key = '1234567890';
    /**
     * ไดเร็คทอรี่ template ที่ใช้งานอยู่ ตั้งแต่ DOCUMENT_ROOT
     * ไม่ต้องมี / ทั้งเริ่มต้นและปิดท้าย
     * เช่น skin/default.
     *
     * @var string
     */
    public $skin = 'skin/default';
    /**
     * ไอคอนเริ่มต้นของไซต์ (โลโก)
     *
     * @var string
     */
    public $default_icon = 'icon-office';
    /**
     * สีหลักของเว็บไซต์
     *
     * @var string
     */
    public $bg_color = '#3498DB';
    /**
     * สีของเมนูบนสุด+footer
     *
     * @var string
     */
    public $color = '#FFFFFF';
    /**
     * สามารถขอรหัสผ่านในหน้าเข้าระบบได้.
     *
     * @var bool
     */
    public $user_forgot = true;
    /**
     * บุคคลทั่วไป สามารถสมัครสมาชิกได้.
     *
     * @var bool
     */
    public $user_register = true;
    /**
     * ส่งอีเมลต้อนรับ เมื่อบุคคลทั่วไปสมัครสมาชิก
     *
     * @var bool
     */
    public $welcome_email = true;
    /**
     * การเข้าระบบต่อ 1 user
     * ค่าเริ่มต้น true (แนะนำ) สามารถเข้าระบบได้เพียงคนเดียวต่อ 1 user คนที่อยู่ในระบบก่อนหน้าจะถูกบังคับให้ออกจากระบบ.
     *
     * @var bool
     */
    public $member_only = true;

    /**
     * API Token
     *
     * @var string
     */
    public $api_token = '080042cad6356ad5dc0a720c18b53b8e53d4c274';
}

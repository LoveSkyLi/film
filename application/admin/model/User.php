<?php

namespace app\admin\model;

use library\Helper;
use think\Cookie;
use think\Model;
use think\Session;
use app\admin\model\Admin;

class User extends Model
{
    public static function generatePassword($password)
    {
        return md5('film!@#12345'.$password);
    }

    public function getGenderAttr($value)
    {
        $gender = [0 => '未知', 1 => '男', 2 => '女'];
        return $gender[$value];
    }

    public static function getAdminInfo()
    {
        $uid = $appId = '';
        $adminUserCookies = Cookie::get('film_adminUser');
        $admin = \json_decode($adminUserCookies, true);
        if($admin) {
            // list($username, $uid, $appId) = explode("\t", Helper::authcode($adminUserCookies, 'DECODE'));
            $username = $admin['username'];
            $uid = $admin['uid'];
            
        }

        $where['uid'] = $uid;
        $adminInfo = Admin::where($where)->find();
        return $adminInfo;
    }

    public static function getIsSuperAdmin()
    {
        $adminInfo = self::getAdminInfo();
        return $adminInfo->usergroup == 1 ? true : false;
    }
}

<?php

namespace app\admin\controller;

use library\Helper;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Request;

class Base extends Controller
{
    public $uid = '';
    public $appid = '';

    public function _initialize()
    {
        $request = Request::instance();
        $action = $request->controller();

        // //
		// //if (!$adminUserCookies) {
        //     $cookiesValue = Helper::authcode("祁子建\t1089875\t18611546203", 'ENCODE');
        //     cookie('fyshopAdmin', $cookiesValue);
        // //}
        $adminUserCookies = Cookie::get('film_adminUser');
        $admin = \json_decode($adminUserCookies, true);
        $uid = $admin['uid'];
        // $uid = $appId = '';
        // if($adminUserCookies) {
        //     list($username, $uid, $appId) = explode("\t", Helper::authcode($adminUserCookies, 'DECODE'));
        //     $this->appid = $appId;

//            $result = Helper::checkAppExpire($appId);
//            if ($result['code'] > 0) {
//                exit($result['error']);
//            }
//            if ($result['data'] > 0) {
//                exit('您的应用已到期，请续费后使用');
//            }
        //}

        if(!in_array($action, array('Login', 'Logout'))) {
            $this->checkAdmin($uid);
        }
    }

    public function checkAdmin($uid)
    {
        $where = array(
            'uid' => $uid,
            'admingroup' => 1,
        );

        $adminUserData = Db::name('admin')->where($where)->find();
        if(empty($adminUserData['uid'])) {
            $this->redirect('/admin/login');
        } else {
            if ($adminUserData['usergroup'] && $adminUserData['admingroup']) {
                $adminUserData['role'] = '超级管理员';
            } elseif (!$adminUserData['usergroup'] && $adminUserData['admingroup']) {
                $adminUserData['role'] = '管理员';
            } else {
                $adminUserData['role'] = '';
            }

            $this->assign('userInfo', $adminUserData);
        }

    }

}

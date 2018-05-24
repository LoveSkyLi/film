<?php

namespace app\api\controller;

use library\Helper;
use think\Cache;
use think\Controller;
use think\Cookie;
use think\Request;

class Qrcode extends Controller
{
    public function index() {
        echo 'error';
    }

    public function login() {

        $request = Request::instance();

        $code = $request->param('code', '', 'htmlspecialchars');

        $qrcodeData = Cache::get($code);

        if($qrcodeData['expired'] < time()) {
            $qrcodeData = array(
                'status' => 'expired',
            );

            Cache::set($code, '');
        }

        return $qrcodeData;

    }

    public function check()
    {
        $request = Request::instance();

        $code = $request->param('code', '', 'htmlspecialchars');
        $appId = $request->param('appId', '', 'htmlspecialchars');
        $userSession = $request->param('userSession', '', 'htmlspecialchars');

        $checkData = Cache::get($code);

        $userQuery = new \app\api\model\User();

        if($checkData) {
            $userData = $userQuery->checkAdmin($userSession, $appId);
            if($userData['admingroup']) {
                $updatData = array(
                    'expired' => time() + 600,
                    'status' => 'logged',
                );

                $result['icon'] = 'success';
                $result['msg'] = '是否要登录管理后台';
                $result['button'] = true;
				$result['code'] = 0;
            } else {
                $updatData = array(
                    'expired' => time() + 600,
                    'status' => 'nopermission',
                );

                $result['icon'] = 'warn';
                $result['msg'] = '没有登录权限';
                $result['button'] = false;

                cookie('fyhqAdmin', '');
            }

            Cache::set($code, $updatData);
        } else {
            $result['icon'] = 'warn';
            $result['msg'] = '二维码失效';
            $result['button'] = false;

        }

        return $result;
    }

    public function confirm()
    {
        $request = Request::instance();

        $code = $request->param('code', '', 'htmlspecialchars');
        $appId = $request->param('app_id', '', 'htmlspecialchars');
        $userSession = $request->param('userSession', '', 'htmlspecialchars');

        $checkData = Cache::get($code);

        $userQuery = new \app\api\model\User();
        if($checkData) {
            $userData = $userQuery->checkAdmin($userSession, $appId);

            if($userData['admingroup']) {
                $username = $userData['username'];
                $uid = $userData['uid'];

                $cookiesValue = Helper::authcode("$username\t$uid\t$appId", 'ENCODE');

                $updatData = array(
                    'expired' => time() + 600,
                    'status' => 'confirm',
                    'cookiesValue' => $cookiesValue
                );

                $result['icon'] = 'success';
                $result['msg'] = '登录成功';
                $result['button'] = false;
				$result['code'] = 0;
            }  else {
                $updatData = array(
                    'expired' => time() + 600,
                    'status' => 'nopermission',
                );

                $result['icon'] = 'warn';
                $result['msg'] = '没有登录权限';
                $result['button'] = false;

                Cookie::delete('fyhqAdmin');

            }

            Cache::set($code, $updatData);
        } else {
            $result['icon'] = 'warn';
            $result['msg'] = '二维码失效';
            $result['button'] = false;
        }
        return $result;
    }
}

<?php

namespace app\admin\controller;

use think\Cache;
use think\Controller;
use think\Request;
use app\admin\model\User;
use app\admin\model\Admin;
use think\Session;
use think\Cookie;
class Login extends Controller
{
    public function index()
    {
        $this->assign('title', '登录');
        return $this->fetch('login');
    }

    public function ajaxLoginAction()
    {
        $username = $this->request->post('username', '');
        $password = $this->request->post('password', '');
        if (!$username || !$password) {
            return ['error' => 1, 'msg' => '参数错误'];
        }

        $model = Admin::where([
            'username' => $username,
            'password' => User::generatePassword($password),
        ])->find();
        
         if (!$model) {
            return ['error' => 1, 'msg' => '用户名或密码错误'];
        } else {
			Cookie::set('film_adminUser',$model);

        }

        return ['error' => 0, 'msg' => '成功'];
    }
    
    // public function index()
    // {
    //     $code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : '';

    //     $charid = md5(uniqid(mt_rand(), true).time());
    //     $qrcode = 'loginQrcode_'.substr($charid, 20, 12);

    //     $qrcodeData = array(
    //         'expired' => time() + 600,
    //         'status' => 0
    //     );

    //     Cache::set($qrcode, $qrcodeData);

    //     $qrcodeShow = 'admin:login:'.$qrcode;

    //     $this->assign('title', '登录');
    //     $this->assign('qrcode', $qrcode);
    //     $this->assign('qrcodeShow', $qrcodeShow);
    //     $this->assign('code', $code);

    //     return $this->fetch('qrcode_index');
    // }

    
}

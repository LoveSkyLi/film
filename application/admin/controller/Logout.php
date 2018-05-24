<?php

namespace app\admin\controller;

use think\Controller;
use think\Cookie;
use think\Request;

class Logout extends Base
{
    public function index()
    {
        Cookie::delete('film_adminUser');
        $this->redirect('/admin/login');
    }
}

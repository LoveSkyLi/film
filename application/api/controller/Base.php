<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\config;
use app\api\model\UserSession;

class Base extends Controller
{
    public function _initialize(){
        header("Access-Control-Allow-Origin: *");
        header('Content-Type:application/json; charset=utf-8');
        
    }
}

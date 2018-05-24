<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Config;
use app\api\model\Company as Cp;
use app\api\model\User;
use app\api\model\UserSession;
use think\Cache;
use think\Log;

class CompanyApply extends Base
{

    public function _initialize(){
        parent::_initialize();
    }
    
    public function getApply() {
        $apply = \app\api\model\Setting::where(['type' => 1])->value('cover');
        if (!$apply) {
            echo '<script>alert("文件不存在")</script>';
            exit();
        }
        $arr = \explode('/', $apply);
        $name = $arr[count($arr) - 1];
        header('Pragma: public'); // required
        header('Expires: 0'); // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private',false);
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename="'.basename($name).'"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');
        readfile($apply); // push it out
        exit();

    }

    public function index() {
        Cache::clear();
        Log::clear();
    }
}

<?php
namespace app\api\controller;

use app\api\model\UserSession;
use library\Helper;
use library\Weixin;
use think\Controller;
use think\Request;
use think\Db;
class Index extends Controller
{
    public function getwxacode()
    {
        $request = Request::instance();
        $scene = $request->param('scene', '');
        $page = $request->param('page', '');
        $openId = $request->param('openId', '');

        if (!$scene) {
            return ['code' => 1, 'msg' => '参数错误'];
        }

        $model = Weixin::getAccessToken();
        $accessToken = $model['access_token'];
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$accessToken;
        $params = [
            'scene' => $scene,
            'page' => $page,
        ];
        $params = json_encode($params, JSON_UNESCAPED_UNICODE);
        $result = Helper::curlPost($url, $params);

        echo $result;
    }
}

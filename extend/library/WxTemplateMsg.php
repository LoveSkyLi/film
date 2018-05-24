<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/20
 * Time: 11:51
 */

namespace library;

use Curl\Curl;
use think\Log;
use think\Cache;

class WxTemplateMsg
{
    private static function curlPost($url, $data = [])
    {
        $curl  = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->post($url, $data);
        return $curl->response;
    }

    public static function getAccessToken()
    {
        $tokenInfo = Weixin::getAccessToken();
        $accessToken = $tokenInfo['access_token'];
        return $accessToken;
    }

    public static function send($params)
    {
        $accessToken = self::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$accessToken;
        $data = json_encode($params);
        $response = self::curlPost($url, $data);
        $result = json_decode($response, true);
        Log::record($result);
        if ($result['errcode'] == 0) {
            return true;
        }
        return false;
    }
}
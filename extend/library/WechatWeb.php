<?php
namespace library;

use think\Cache;
use think\Log;


/**
* 微信授权相关接口
*
*/
class WechatWeb {

//     app id: wx4899888d6d55c284
// secret: bed6c62f7c6aa2d75b148d104c9d3240
    const APPID = 'wx4899888d6d55c284'; //config('wxapp.appId'); //公众号appid
    const APPSECRET = 'bed6c62f7c6aa2d75b148d104c9d3240'; //config('wxapp.appSecret'); //公众号app_secret
    const REDIRECTURI = 'https://debug.oa.feeyan.com/movie-mp/api/user'; //config('wxapp.redirectUri'); //授权之后跳转地址
    /**
    * 获取微信授权链接
    *
    * @param string $redirect_uri 跳转地址
    * @param mixed $state 参数
    */

    static public function getAuthorizeUrl($type = 'userinfo', $state='')
    {
        $state = md5(rand(100000, 999999));
        session('state', $state);
        $redirect_uri = urlencode(self::REDIRECTURI);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid=". self::APPID ."&secret=". self::APPSECRET ."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_{$type}&state={$state}#wechat_redirect";
    }
    /**
    * 获取授权token
    *
    * @param string $code 通过get_authorize_url获取到的code
    */
    static public function getAccessToken($code)
    {
//         $tokenInfo = cookie('AccessTokenWx', '');
// Log::record(json_encode(['tokenInfo===========' => $tokenInfo]));
//         if (!$tokenInfo || $tokenInfo['update_time'] + 7000 < time()) {

            $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=". self::APPID ."&secret=". self::APPSECRET ."&code={$code}&grant_type=authorization_code";

            $token_data = self::http($token_url, "POST");
            
            Log::record(\json_encode(['token_data'=>$token_data]));

            if($token_data[0] == 200)
            {
                $token_data = json_decode($token_data[1], TRUE);
                $params = [
                    'access_token' => $token_data['access_token'],
                    // 'expires_in' => $token_data['expires_in'],
                    'openid'    => $token_data['openid'],
                    'update_time' => time()
                ];
                // cookie('AccessTokenWx', $params, 7000);
                return $token_data;

            }
            return false;
        // }

        // return $tokenInfo;
    }

    /**
    * 获取授权后的微信用户信息
    *
    * @param string $access_token
    * @param string $open_id
    */
    static public function getUserInfo($access_token, $open_id)
    {
        if($access_token && $open_id)
        {
            $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
            $info_data = self::http($info_url);
            Log::record(\json_encode(['info_data' => $info_data]));
            if($info_data[0] == 200)
            {
                return json_decode($info_data[1], TRUE);
            }
        }

        return FALSE;
    }

    static public function http($url, $method='POST', $postfields = null, $headers = array(), $debug = false)
    {
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);

        switch ($method) {
            case 'POST':
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
            }
            break;
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);

        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);

            echo '=====info=====' . "\r\n";
            print_r(curl_getinfo($ci));

            echo '=====$response=====' . "\r\n";
            print_r($response);
        }
        curl_close($ci);
        return array($http_code, $response);
    }

}
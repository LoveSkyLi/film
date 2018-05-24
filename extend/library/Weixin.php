<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 11:26
 */

namespace library;

use think\Cache;
use think\Log;

class Weixin
{
    public static function getAccessToken()
    {
        $appId =  config('wxapp.appId');
        $appSecret = config('wxapp.appSecret');

        $tokenInfo = Cache::get('wxAccessToken');
        if (!$tokenInfo || $tokenInfo['update_time'] + 7000 < time()) {
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;
            $result = Helper::curlPost($url);
            $tokenInfo = json_decode($result, true);
            
            if (isset($tokenInfo['errcode'])) {
                return ['code' => 1, 'msg' => '获取accessToken失败'];
            }
            $params = [
                'access_token' => $tokenInfo['access_token'],
                'expires_in' => $tokenInfo['expires_in'],
                'update_time' => time()
            ];
            Cache::set('wxAccessToken', $params, 7200);
        }
        return $tokenInfo;
    }

    static public function getTicket() {
        Cache::set('wxAccessToken', '');
        $tokenInfo = self::getAccessToken();

        $ticketInfo = Cache::get('ticket');
        
        if (!$ticketInfo || $ticketInfo['update_time'] + 7000 < time()) {
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$tokenInfo['access_token'].'&type=jsapi';

            $ticket = Helper::http($url, 'GET');

            if ($ticket[0] == 200) {
                $ticketInfo = \json_decode($ticket[1], true);
                $params = [
                    'ticket' => $ticketInfo['ticket'],
                    'update_time' => time(),
                ];
                Cache::set('ticket', $params, 7200);

                return $ticketInfo['ticket'];
            }
            
        }

        return $ticketInfo['ticket'];

    }
}
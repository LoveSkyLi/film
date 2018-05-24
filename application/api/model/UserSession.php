<?php

namespace app\api\model;

use think\Cache;
use think\Model;

class UserSession extends Model
{
    public function user()
    {
        return $this->hasOne('User', 'uid', 'uid');
    }

    public static function getInfo($sessionKey)
    {
        // $userData = Cache::get($sessionKey);
        // dump($userData);
        // if (!$userData) {
            $model = self::where([
                'session_key' => $sessionKey
            ])->field('uid,session_key')->find();
            if (!$model) {
                return false;
            }
            // echo $model->uid;
            $userData = \app\api\model\User::where(['uid' => $model['uid']])->field('uid, nickname, usergroup, admingroup, avatar, openid, cid')->find()->toArray();
        
            return $userData;
        // }
        //     $userData = [
                
        //         'username' => $model['user']['username'],
        //         'avatar' => $model['user']['avatar'],
        //         'uid' => $model['uid'],
        //         'usergroup' => $model['user']['usergroup'],
        //         'admingroup' => $model['user']['admingroup'],
        //         'userSession' => $model['session_key'],
        //         'openId' => $model['user']['openid'],
        //     ];
        //     // Cache::set($sessionKey, $userData, 3600 * 24);
        // }

        // return $userData;
    }
}

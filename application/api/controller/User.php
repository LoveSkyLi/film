<?php

namespace app\api\controller;

use app\api\model\UserSession;
use library\Helper;
use library\WechatWeb;
use Qcloud\Sms\SmsSingleSender;
use think\Cache;
use think\Config;
use think\Controller;
use think\Log;
use think\Request;
use app\api\model\User as Ur;
use think\Cookie;

include_once EXTEND_PATH . "wxaes/wxBizDataCrypt.php";

class User extends Base
{

    public function _initialize(){
        parent::_initialize();
        
    }
    public function index(Request $request)
    {

        $params = $request->param();
        $code = $request->param('code', '');
        $state = $request->param('state', '');
Log::record(\json_encode(['code' => $code, 'state' => $state]));
        $wxappId = config('wxapp.appId');
        $wxappSecret = config('wxapp.appSecret');

        $_state = session('state');

        if ($state != $_state) {
            exit(json_encode(['code' => 2, 'msg' => '参数错误']));
        }
        Cache::set('code', $code, 7000);
        $_code = Cache::get('code');
Log::record(json_encode(['_code' => $code]));
        if (!$code && !$_code) {
            //echo 'code is wrong';
            $url = $this->getAuthorizeUrl();
            header('Location:'.$url);
            exit();
        }
        
        if (!$code && $_code) {
            $rt = WechatWeb::getAccessToken($_code);
        } else {
            $rt = WechatWeb::getAccessToken($code);
        }
            Log::record(\json_encode(['rt' => $rt]));
            if (!$rt) {
                exit(json_encode(['code' => 2, 'msg' => '参数错误']));

            }
    
            $userInfo = WechatWeb::getUserInfo($rt['access_token'], $rt['openid']);
    
            if (!$userInfo) {
                exit(json_encode(['code' => 2, 'msg' => '参数错误']));

            }
    Log::record(json_encode(['userInfo' => $userInfo]));      
            $openid = $userInfo['openid'];
            $nickname = isset($userInfo['nickname']) ? $userInfo['nickname'] : '';
            $gender = isset($userInfo['sex']) ? $userInfo['sex'] : '';
            $province = isset($userInfo['province']) ? $userInfo['province'] : '';
            $city = isset($userInfo['city']) ? $userInfo['city'] : '';
            $country = isset($userInfo['country']) ? $userInfo['country'] : '';
            $avatar = isset($userInfo['headimgurl']) ? $userInfo['headimgurl'] : '';
            $unionid = isset($userInfo['unionid']) ? $userInfo['unionid'] : '';
            
            $userSession = Helper::generateSkey($openid, $wxappId);
    Log::record(json_encode(['userSession' => $userSession]));
            if ($openid) {
                $info = Ur::checkUser($openid);
                $data = [
                    'openid' => $userInfo['openid'],
                    'nickname' => isset($userInfo['nickname']) ? $userInfo['nickname'] : '',
                    'gender' => isset($userInfo['sex']) ? $userInfo['sex'] : '',
                    'province' => isset($userInfo['province']) ? $userInfo['province'] : '',
                    'city' => isset($userInfo['city']) ? $userInfo['city'] : '',
                    'country' => isset($userInfo['country']) ? $userInfo['country'] : '',
                    'avatar' => isset($userInfo['headimgurl']) ? $userInfo['headimgurl'] : '',
                    'unionid' => isset($userInfo['unionid']) ? $userInfo['unionid'] : '',
                    'status'  => 1,
                ];
                if (!isset($info['uid'])) {
                    $uid = Ur::addUser($data, $userSession);
                } else {
                    $uid = Ur::saveUser($data, $info['uid'], $userSession);
    
                }
    Log::record(json_encode(['uid' => $uid]));

                // $authorizeToken = md5('authorize'.$uid.$openid);
                setcookie("usersession", "", time() - 3600);
                setcookie("usersession1", "", time() - 3600);
                setcookie("userSessionToken", "", time() - 3600);
                setcookie('usersession', $userSession);
                cookie('usersession', $userSession);
              Log::record(json_encode(['userSessionToken1' => $_COOKIE]));
               
    
                header("Location:https://debug.oa.feeyan.com/movie-mp/web/html/index/index.html");
                
                
            }
            
        
    }

    public function getAuthorizeUrl() {
        setcookie("usersession", "", time() - 3600);
        setcookie("usersession1", "", time() - 3600);
        setcookie("userSessionToken", "", time() - 3600);
        setcookie("token", "", time() - 3600);

        $url = WechatWeb::getAuthorizeUrl();
        return urlencode($url);
    }
    
    
    // public function login(Request $request) {
        
    // }
//     public function authorize()
//     {
//         $code = $this->request->post('code', '');
//         $encryptedData = $this->request->post('encryptedData', '');
//         $iv = $this->request->post('iv', '');
//         $signature = $this->request->post('signature', '');
//         $rawdata = $this->request->post('rawData', '');
        
// Log::record(json_encode(['code' => $code, 'encryptedData' => $encryptedData, 'iv' => $iv, 'signature' => $signature, 'rawdata' => $rawdata]));
//         if (!$code || !$encryptedData || !$iv || !$signature || !$rawdata) {
//             return ['code' => 1, 'msg' => '参数错误'];
//         }

//         $wxappId = config('wxapp.appId');
//         $wxappSecret = config('wxapp.appSecret');

//         $url = 'https://api.weixin.qq.com/sns/jscode2session';
//         $data = [
//             'appid' => $wxappId,
//             'secret' => $wxappSecret,
//             'js_code' => $code,
//             'grant_type' => 'authorization_code'
//         ];
//         $result = Helper::curlPost($url, $data);
//         Log::record($result);
//         $sessionInfo = json_decode($result, TRUE);
//         if (isset($sessionInfo['errcode'])) {
//             return ['code' => 1, 'msg' => $sessionInfo['errmsg']];
//         }

//         $openId = $sessionInfo['openid'];
//         $sessionKey = $sessionInfo['session_key'];
//         $unionId = isset($sessionInfo['unionid']) ? $sessionInfo['unionid'] : '';
//         $expiresIn = isset($sessionInfo['expires_in']) ? $sessionInfo['expires_in'] : 0;

//         $pc = new \WXBizDataCrypt($wxappId, $sessionKey);
//         $errCode = $pc->decryptData($encryptedData, $iv, $data);
//         if ($errCode !== 0) {
//             return ['code' => 1, 'msg' => '错误'.$errCode];
//         }
//         $userInfo = json_decode($data, TRUE);
//         $userInfo['skey'] = Helper::generateSkey($openId, $wxappId);

//         $userName = isset($userInfo['nickName']) ? $userInfo['nickName'] : '';
//         $openId = $userInfo['openId'];
//         $userAvatar = isset($userInfo['avatarUrl']) ? $userInfo['avatarUrl'] : '';
//         $userSession = $userInfo['skey'];
//         $gender = isset($userInfo['gender']) ? $userInfo['gender'] : 0;
//         $city = isset($userInfo['city']) ? $userInfo['city'] : '';
//         $province = isset($userInfo['province']) ? $userInfo['province'] : '';

//         if($openId) {
//             $user = $this->getUser($openId);
//             if(empty($user['uid'])) {
//                 $admingroup = $this->isAdmin();
//                 $userData = [
//                     'unionid' => $unionId,
//                     'openid' => $openId,
//                     'username' => $userName,
//                     'avatar' => $userAvatar,
//                     'admingroup' => $admingroup,
//                     'usergroup' => $admingroup,
//                     'gender' => $gender,
//                     'city' => $city,
//                     'province' => $province,
//                     'session_key' => $sessionKey,
//                     'expires_in' => $expiresIn,
                    
//                 ];
//                 $uid = $this->addUser($userData, $userSession);
//             } else {
//                 $user->username = $userName;
//                 $user->avatar = $userAvatar;
//                 $user->session_key = $sessionKey;
//                 $user->expires_in = $expiresIn;
//                 $user->save();

//                 $uid = $user['uid'];
//             }

//             $authorizeToken = md5('authorize'.$uid.$openId);
//             Cache::set($authorizeToken, $uid, 3600 * 24 * 7);

//             $data = [
//                 'authorizeToken' => $authorizeToken,
//                 'userSession' => $userSession
//             ];

//             return [
//                 'code' => 0,
//                 'msg' => '成功',
//                 'data' => $data
//             ];
//         }
//     }

    // public function login()
    // {
    //     $request = Request::instance();
    //     $phone = $request->param('phone', '');
    //     $password = $request->param('password', '');
    //     $profitUser = $this->request->post('spreadid', '');
    //     if (!$phone || !$password) {
    //         return ['code' => 1, 'msg' => '参数错误'];
    //     }

    //     $password = \app\api\model\User::generatePassword($password);
    //     $userInfo = \app\api\model\User::where([
    //         'phone' => $phone,
    //         'password' => $password
    //     ])->find();
    //     if (!$userInfo) {
    //         return ['code' => 1, 'msg' => '手机号或密码错误'];
    //     }

    //     if ($profitUser) {
    //         $userInfo->profit_user = $profitUser;
    //         $userInfo->save();
    //     }
    //     $wxappId = config('wxapp.appId');
    //     $userSession = Helper::generateSkey($userInfo['openid'], $wxappId);
    //     $userData  = [
    //         'userInfo' => [
    //             'username' => $userInfo['username'],
    //             'avatar' => $userInfo['avatar'],
    //             'uid' => $userInfo['uid'],
    //             'admingroup' => $userInfo['admingroup'],
    //         ],
    //         'userSession' => $userSession,
    //         'openId' => $userInfo['openid']
    //     ];
    //     $this->addUserSession($userSession, $userData);

    //     return [
    //         'code' => 0,
    //         'msg' => '成功',
    //         'data' => $userData
    //     ];
    // }

    // public function register()
    // {
    //     $request = Request::instance();
    //     $phone = $request->param('phone', '');
    //     $password = $request->param('password', '');
    //     // $code = $request->param('code', '');
    //     $token = $request->param('authorizeToken', '');
    //     // $nickname = $request->param('nickname', '');

    //     if (!$phone || !$password || !$token) {
    //         return ['code' => 1, 'msg' => '参数错误'];
    //     }

    //     // $tmpcode = Cache::get('phone'.$phone);
    //     // if ($tmpcode != $code) {
    //     //     return ['code' => 1, 'msg' => '验证码错误'];
    //     // }

    //     $user = \app\api\model\User::getUserByPhone($phone);
    //     if ($user) {
    //         return ['code' => 1, 'msg' => '手机号已存在'];
    //     }

    //     $uid = Cache::get($token);
    //     if (!$uid) {
    //         return ['code' => 1, 'msg' => 'authorizeToken错误'];
    //     }

    //     $password = \app\api\model\User::generatePassword($password);
    //     $userInfo = \app\api\model\User::where([
    //         'uid' => $uid
    //     ])->find();
    //     $userInfo->save([
    //         // 'nickname' => $nickname,
    //         'phone'    => $phone,
    //         'password' => $password
    //     ]);

    //     $wxappId = config('wxapp.appId');
    //     $userSession = Helper::generateSkey($userInfo['openid'], $wxappId);
    //     $userData  = [
    //         'userInfo' => [
    //             'username' => $userInfo['username'],
    //             'avatar' => $userInfo['avatar'],
    //             'uid' => $uid,
    //             'admingroup' => $userInfo['admingroup'],
    //         ],
    //         'userSession' => $userSession,
    //         'openId' => $userInfo['openid']
    //     ];
    //     $this->addUserSession($userSession, $userData);

    //     return [
    //         'code' => 0,
    //         'msg' => '成功',
    //         'data' => $userData
    //     ];
    // }

    // public function sendCode()
    // {
    //     $request = Request::instance();
    //     $phone = $request->post('phone', '');

    //     if (!$phone) {
    //         return ['code' => 1, 'msg' => '参数错误'];
    //     }

    //     $appid = 1400060636;
    //     $appkey = '471dabe7a6306c783bfe327d45f58bb1';
    //     $code = rand(100000, 999999);

    //     try {
    //         $sms = new SmsSingleSender($appid, $appkey);
    //         $params = [$code, 2];
    //         $response = $sms->sendWithParam('86', $phone, 79584, $params);
    //         $result = json_decode($response, true);
    //         Cache::set('phone'.$phone, $code, 120);

    //         return ['code' => 0, 'msg' => '成功'];
    //     } catch (\Exception $e) {
    //         return ['code' => 1, 'msg' => '失败'];
    //     }
    // }

    

    // private function getUser($openId)
    // {
    //     $where['openid'] = $openId;
    //     $userData = \app\api\model\User::where($where)->find();

    //     return $userData;
    // }

    // private function isAdmin()
    // {
    //     $userCount = \app\api\model\User::where(1)->value('uid');
    //     if($userCount > 0) {
    //         $isAdmin = 0;
    //     } else {
    //         $isAdmin = 1;
    //     }

    //     return $isAdmin;
    // }

    // private function addUser($userData, $userSession)
    // {
    //     $user = \app\api\model\User::create($userData);
    //     if ($user) {
    //         UserSession::create([
    //             'uid' => $user['uid'],
    //             'session_key' => $userSession
    //         ]);
    //     }
    //     return $user->uid;
    // }

    // private function addUserSession($userSession, $userData)
    // {
    //     Cache::set($userSession, $userData, 3600 * 24);
    // }

}

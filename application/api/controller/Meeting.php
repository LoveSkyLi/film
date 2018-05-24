<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\Meeting as Mt;
use app\api\model\UserSession;
use app\api\model\User;
use think\Log;
use think\Config;
use think\Cache;
use library\Helper;
use app\api\model\MeetingOrder as Mo;
use library\Weixin;

class Meeting extends Base
{
    public function _initialize(){
        parent::_initialize();
        //74bcea8beb4fab3a771694408f58a8bf6dd3ccaea031149c058a23ea5e7f695c
        $userSession = isset($_REQUEST['userSession']) ? $_REQUEST['userSession'] : '';
        if (!$userSession) {
            exit(json_encode(['code' => 2, 'msg' => '参数错误']));
            
        } 
        $userData = UserSession::getInfo($userSession);
        if (!$userData) {
            exit(json_encode(['code' => 2, 'msg' => '用户不存在']));
        }

        $this->uid = $userData['uid'];
        $this->userData = $userData;
    }

    public function getMeetingList(Request $request) {
        
        $page = $request->param('page', 1, 'intval');
        $pageSize = Config::get('paginate.list_rows');
        $start = $page;
        $count = Mt::where(['status' => 1])->count();

        
        $list = Mt::field('id, title, cover, address, sTime, eTime')->where(['status' => 1])->order('id desc')->paginate($pageSize, false, ['page' => $page]);;
        $list = $list->toArray();
        
        if (!$list['data']) {
            return ['code' => 1, 'msg' => '暂无会议'];
        }

        foreach ($list['data'] as $k => $v) {
            if (date('Y-m-d', strtotime($v['sTime'])) == date('Y-m-d', strtotime($v['eTime']))) {
                $time = date('m月d日 H:i', strtotime($v['sTime'])) . '-' . date('H:i', strtotime($v['eTime']));
            } else {
                $time = date('m月d日 H:i', strtotime($v['sTime'])) . '-' . date('m月d日 H:i', strtotime($v['eTime']));
            }
            $list['data'][$k]['time'] = $time;

        }
        $pageTotal = ceil($list['total'] / $pageSize);
        
        $data = [
            'pageTotal' => $pageTotal,
            'page'      => $page,
            'size'      => $pageSize,
            'list'      => $list['data'],
        ];

        return ['code' => 0, 'msg' => '成功', 'data' => $data];

    }

    public function getMeetingInfo(Request $request) {
        

        $id = $request->param('mid', '');

        $info = Mt::field('id, title, cover, address, sTime, eTime, info_set, intro')->where(['id' => $id, 'status' => 1])->find();

        if (!$info) {
            return ['code' => 1, 'msg' => '会议信息错误'];
        }

        $set = \json_decode($info['info_set'], true);

        // $data = [
        //     'id'       => $id,
        //     'info_set' => $set,
        //     'intro'    => self::pregContent($info['intro']),
        // ];
        if (date('Y-m-d', strtotime($info['sTime'])) == date('Y-m-d', strtotime($info['eTime']))) {
            $time = date('m月d日 H:i', strtotime($info['sTime'])) . '-' . date('H:i', strtotime($info['eTime']));
        } else {
            $time = date('m月d日 H:i', strtotime($info['sTime'])) . '-' . date('m月d日 H:i', strtotime($info['eTime']));
        }
        $info['time']     = $time;
        $info['info_set'] = $set;
        $info['intro']    = $info['intro'];


        return ['code' => 0, 'msg' => '成功', 'data' => $info];
    }


    static public function pregContent($content) {

        $content = preg_replace('/<\/?section>/i', '', $content);
        $content = preg_replace("/<p.*?>/", '<p style="font-size:12px">', $content);
        return $content;
    }

    public function subOrder(Request $request) {
        $uid = $this->uid;
        $mid = $request->param('mid', '');
        $contact = $request->param('contact', '');
        $phone   = $request->param('phone', '');
        $cname   = $request->param('cname', '');
        $member  = $request->param('member', '');//[{"姓名":"1","职务":"1","电话":"1"},{"姓名":"1","职务":"1","电话":"1"}]
        $extend  = $request->param('extend', '');
        Log::record(\json_encode(['member' => $member]));
        if (!$contact || !$phone || !$cname || !$member || !$mid) {
            return ['code' => 1, 'msg' => '参数错误'];
        }

        $info = Mt::where(['id' => $mid, 'status' => 1])->find()->toArray();

        if (!$info) {
            return ['code' => 1, 'msg' => '会议信息有误'];
        }
        
        $mid       = $info['id'];
        $m_title   = $info['title'];
        $m_address = $info['address'];
        $m_sTime   = $info['sTime'];
        $m_eTime   = $info['eTime'];
        $m_cover   = $info['cover'];

        $members = \json_decode($member, true);
        if (!is_array($members)) {
            return ['code' => 1, 'msg' => '参数错误'];
        }

        $time = time();
        
        foreach($members as $k=>$v) {
            if (!$v['姓名']) {
                continue;
            }
            $data[$k] = [
                'uid'      => $uid,
                'orderSn' => Helper::getOrderSn(),
                'mid'     => $mid,
                'member'  => json_encode($v),
                'contact' => $contact,
                'phone'   => $phone,
                'cname'   => $cname,
                'addtime' => $time,
                'm_title' => $m_title,
                'm_address' => $m_address,
                'm_sTime'   => $m_sTime,
                'm_eTime'   => $m_eTime, 
                'm_cover'   => $m_cover,
                'extend'    => $extend,
            ];
           
        }
        
        $rt = Mo::insertAll($data);

        if ($rt) {
            return ['code' => 0, 'msg' => '成功', 'data' => ['uid' => $uid, 'mid' => $mid, 'flag' => $time]];
        }
        return ['code' => 1, 'msg' => '报名失败'];
       
    }

    public function getOrderList(Request $request) {
        $uid = $this->uid;
        $mid = $request->param('mid', '');
        $flag = $request->param('flag', '');

        $user = User::find($uid);
        $meeting = Mt::find($mid);
        if (!$user || !$meeting) {
            return ['code' => 1, 'msg' => '获取订单列表失败'];
        }

        $page = $request->param('page', 1);
        $pageSize = Config::get('paginate.list_rows');
        $start = $page;
        $count = Mo::where(['uid' => $uid])->count();

        $pageTotal = ceil($count / $pageSize);

        $list = Mo::where(['uid' => $uid, 'mid' => $mid, 'addtime' => $flag])->order('id desc')->page($start, $pageSize)->select();

        if (!$list) {
            return ['code' => 1, 'msg' => '暂无会议订单'];
        }

        $list = $list->toArray();
        
        foreach ($list as $k => $v) {
            if (date('Y-m-d', strtotime($v['m_sTime'])) == date('Y-m-d', strtotime($v['m_eTime']))) {
                $time = date('n月d日 H:i', strtotime($v['m_sTime'])) . '-' . date('H:i', strtotime($v['m_eTime']));
            } else {
                $time = date('m月d日 H:i', strtotime($v['m_sTime'])) . '-' . date('n月d日 H:i', strtotime($v['m_eTime']));
            }
            $list[$k]['member'] = \json_decode($v['member'], true);
            $list[$k]['time'] = $time;
            $list[$k]['send_text'] = Mo::getSendStatusTextAttr($v['send_status']);

        }
        $data = [
            'pageTotal' => $pageTotal,
            'page'      => $page,
            'size'      => $pageSize,
            'list'      => $list,
        ];
        return ['code' => 0, 'msg' => '成功', 'data' => $data];
    }

    public function getOrderInfo(Request $request) {
        $uid = $this->uid;
        
        $id = $request->param('id', '');

        $info = Mo::where(['id' => $id])->find();
        if (!$info) {
            return ['code' => 1, 'msg' => '信息错误'];
        }

        $info = $info->toArray();
        
        if (date('Y-m-d', strtotime($info['m_sTime'])) == date('Y-m-d', strtotime($info['m_eTime']))) {
            $time = date('n月d日 H:i', strtotime($info['m_sTime'])) . '-' . date('H:i', strtotime($info['m_eTime']));
        } else {
            $time = date('m月d日 H:i', strtotime($info['m_sTime'])) . '-' . date('n月d日 H:i', strtotime($info['m_eTime']));
        }
        
        $info['member'] = \json_decode($info['member'], true);
        $info['extend'] = \json_decode($info['extend'], true);
        $info['time'] = $time;

        return ['code' => 0, 'msg' => '成功', 'data' => $info];        

    }

    public function getCheckInfo(Request $request) {
        $uid = $this->uid;
        
        $id = $request->param('orderSn', '');

        $info = Mo::where(['orderSn' => $id])->find();
        if (!$info) {
            return ['code' => 1, 'msg' => '信息错误'];
        }

        $info = $info->toArray();
        
        if (date('Y-m-d', strtotime($info['m_sTime'])) == date('Y-m-d', strtotime($info['m_eTime']))) {
            $time = date('n月d日 H:i', strtotime($info['m_sTime'])) . '-' . date('H:i', strtotime($info['m_eTime']));
        } else {
            $time = date('m月d日 H:i', strtotime($info['m_sTime'])) . '-' . date('n月d日 H:i', strtotime($info['m_eTime']));
        }
        
        $info['member'] = \json_decode($info['member'], true);
        $info['extend'] = \json_decode($info['extend'], true);
        $info['time'] = $time;

        return ['code' => 0, 'msg' => '成功', 'data' => $info];        

    }

    public function sendOrderCode(Request $request) {
        $uid = $this->uid;
        $id = $request->param('orderSn', '');

        $info = Mo::where(['orderSn' => $id, 'uid' => $uid])->find();
        if (!$info) {
            return ['code' => 1, 'msg' => '信息错误'];
        }

        $info->send_status = 1;
        $info->save();

        return ['code' => 0, 'msg' => '操作成功', 'data' => ['orderSn' => $id]];
    }

    public function checkMeeting(Request $request) {
        $uid = $this->uid;
        $userData = $this->userData;

        if ($userData['usergroup'] != 1) {
            return ['code' => 1, 'msg' => '无核销会议权限'];

        }

        $order_sn = $request->param('orderSn', '');

        $info = Mo::where(['orderSn' => $order_sn, 'status' => 1])->find();

        if (!$info) {
            return ['code' => 1, 'msg' => '会议订单有误'];
        }

        $rt = Mo::where(['orderSn' => $order_sn, 'status' => 1])->update(['status' => 0, 'update_time' => date('Y-m-d H:i:s')]);

        if ($rt) {
            return ['code' => 0, 'msg' => '核销成功'];
        } else {
            return ['code' => 1, 'msg' => '核销失败'];
        }

    }

    public function getCodeParams(Request $request) {
        $uid = $this->uid;
        $appid = config('wxapp.appId');

        $ticket = Weixin::getTicket();
        $noncestr = Helper::createRandomStr(16);
        $url = $request->param('url', '');
        if (!$url) {
            return ['code' => 1, 'msg' => '参数错误'];
        }
        // $url = $request->url(true);   
        // echo $url;     
        $time = time();
        //jsapi_ticket=sM4AOVdWfPE4DxkXGEs8VMCPGGVi4C3VM0P37wVUCFvkVAy_90u5h9nbSlYy3-Sl-HhTdfl2fzFy1AOcHKP7qg&noncestr=Wm3WZYTPz0wzccnW×tamp=1414587457&url=http://mp.weixin.qq.com?params=value
        $str = 'jsapi_ticket='.$ticket.'&noncestr='.$noncestr.'&timestamp='.$time.'&url='.$url;
        $signature = sha1($str);

        $data = [
            'appid'     => $appid,
            'ticket'    => $ticket,
            'noncestr'  => $noncestr,
            'timestamp' => $time,
            'signature' => $signature,
            'ticket'    => $ticket,
        ];
        return ['code' => 0, 'msg' => '成功', 'data' => $data];
    }

}

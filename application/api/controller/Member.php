<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Config;
use app\api\model\User;
use app\api\model\Company;
use app\api\model\MeetingOrder as Mo;
use app\api\model\ReserveTimes as Rt;
use app\api\model\News;
use app\api\controller\Experts;
use app\api\model\UserSession;
use app\api\model\NewsAuditLog as Nl;


class Member extends Base
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

    /**
     * userType = 1 申请入会，2审核中，3审核通过, 4审核未通过
     */
    public function getUserInfo(Request $request) {

        $uid = $this->uid;
        $user = User::where(['uid' => $uid])->find();

        if (!$user) {
            return ['code' => 1, 'msg' => '用户信息错误'];
        }

        $cid = $user->cid;
        if (!$cid) {
            $userType = 1; 
        } else {
            $company = Company::where(['id' => $cid])->find();
            switch ($company->status) {
                case 0:
                    $userType = 2;
                    break;
                case 1:
                    $userType = 3;
                    break;
                case 2:
                    $userType = 4;
                    break;  
            }
        }

        $userData = $this->userData;
        $data = [
            'avatar' => $user->avatar,
            'username' => $user->nickname,
            'userType' => $userType,
            'usergroup' => $userData['usergroup'],
        ];

        return ['code' => 0, 'msg' => '成功', 'data' => $data];

    }

    public function getMeetingLists(Request $request) {
        $uid = $this->uid;

        $page = $request->param('page', 1);
        $pageSize = Config::get('paginate.list_rows');
        $start = $page;
        $count = Mo::where(['uid' => $uid])->count();

        $pageTotal = ceil($count / $pageSize);

        $list = Mo::where(['uid' => $uid])->order('id desc')->page($start, $pageSize)->select();
        $list = $list->toArray();

        if (!$list) {
            return ['code' => 1, 'msg' => '暂无会议订单'];
        }

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

    /**
     * 0审核未通过，1一级审核，2二级审核，3三级审核，4四级审核，5审核通过
     */
    public function getNewsLists (Request $request) {

        $uid = $this->uid;
        $where = [
            'status' => 1,
            'uid'    => $uid,
        ];

        $page = $request->param('page', 1);
        $pageSize = Config::get('paginate.list_rows');
        $start = $page;
        $count = News::where($where)->count();

        $pageTotal = ceil($count / $pageSize);

        $list = News::where($where)->field('id, title, cover, author, content, audit_status, create_time')->order('create_time desc')->page($start, $pageSize)->select()->toArray();

        if (!$list) {
            return ['code' => 1, 'msg' => '暂无数据'];
        }
        
        foreach ($list as $k => $v) {
            $content = \json_decode($v['content'], true);
            $status = News::getStatusName($v['audit_status']);
            $list[$k]['content'] = $content;
            $list[$k]['create_time'] = date('Y-m-d', strtotime($v['create_time']));
            $list[$k]['status_name'] = $status['name'];
            $list[$k]['flag']        = $status['flag'];
        }

        $data = [
            'pageTotal' => $pageTotal,
            'page'      => $page,
            'size'      => $pageSize,
            'list'      => $list,
        ];

        return ['code' => 0, 'msg' => '成功', 'data' => $data];
    }

    public function getNewsInfo (Request $request) {
        $id = $request->param('id', '');
        $uid = $this->uid;
        $info = News::field('id, title, cover, author, content, audit_status, pubdate, create_time')->where(['id' => $id, 'status' => 1, 'uid' => $uid])->find();

        if (!$info) {
            return ['code' => 1, 'msg' => '信息错误'];
        }

        $info = $info->toArray();
        $status = News::getStatusName($info['audit_status']);
        
        $reason = '';
        if ($status == 0) {
            $reason = Nl::where(['news_id' => $id])->order('id desc')->value('reason');
        }

        $info['content'] = \json_decode($info['content'], true);
        if ($info['audit_status'] != 5) {
            $info['pubdate'] = date('Y-m-d', strtotime($info['create_time']));
        } else {
            $info['pubdate'] = date('Y-m-d', strtotime($info['pubdate']));
            
        }
        $info['status_name'] = $status['name'];
        $info['flag']        = $status['flag'];
        $info['reason'] = $reason;

        return ['code' => 0, 'msg' => '成功', 'data' => $info];

    }


    public function getReserveLists(Request $request) {
        $uid = $this->uid;
        $where = [
            'uid'   => $uid,
            'status' => 2,
        ];

        $page = $request->param('page', 1);
        $pageSize = Config::get('paginate.list_rows');
        $start = $page;
        $count = Rt::with('experts')->where($where)->count();
        $pageTotal = ceil($count / $pageSize);
        

        $list = Rt::with('experts')->where($where)->order('confirm_time desc')->page($start, $pageSize)->select();
        $list = $list->toArray();
        
        if (!$list) {
            return ['code' => 1, 'msg' => '暂无预约'];
        }
        
        foreach ($list as $k => $v) {
            $day = date('m-d', strtotime($v['rDate']));
            $week = Experts::getWeeks(date('w', strtotime($v['rDate'])), false);
            $flag = 3;
            $sTime = date('H:i', strtotime($v['sTime']));
            $eTime = date('H:i', strtotime($v['eTime']));
            $list[$k]['day'] = $day;
            $list[$k]['week'] = $week;
            $list[$k]['sTime'] = $sTime;
            $list[$k]['eTime'] = $eTime;
            $list[$k]['flag']  = $flag;
        }

        
        $data = [
            'pageTotal' => $pageTotal,
            'page'      => $page,
            'size'      => $pageSize,
            'list'      => $list,
        ];

        return ['code' => 0, 'msg' => '成功', 'data' =>  $data];
    }


    public function getCompanyInfo(Request $request) {
        $uid = $this->uid;
        $info = \app\api\model\Setting::field('phone, address, brief')->where(['type' => 2])->find();

        if (!$info) {
            return ['code' => 1, 'msg' => '暂无公司信息'];
        }

        return ['code' => 0, 'msg' => '成功', 'data' => $info];
    }

    
}

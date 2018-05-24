<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\Experts as Ep;
use app\api\model\ReserveTimes as Rt;
use app\api\model\Reserve as Re;
use think\Config;
use app\api\model\UserSession;


class Experts extends Base
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
    }

    public function getExpertsList(Request $request) {

        $page = $request->param('page', 1);

        $pageSize = Config::get('paginate.list_rows');
        $start = ($page - 1) * $pageSize;
        $count = Ep::where(['status' => 1])->count();
        
        $list = Ep::where(['status' => 1])->order('id desc')->paginate($pageSize, false, ['page' => $page]);;
        $list = $list->toArray();
        
        if (!$list['data']) {
            return ['code' => 1, 'msg' => '暂无预约'];
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

    // public function index() {
    //     // echo date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))),"\n";
    //     // echo date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))),"\n";

    //     // if((int) 'aaa08' < 12) {
    //     //     echo 'a';
    //     // } else {
    //     // echo 'b';
            
    //     // }
    //     echo date('H:i', strtotime('11:00:00'));

    // }

    public function  getReserveList(Request $request) {

        //$eid = $request->param('id', '2');
        $page = $request->param('page', 0);

        $dateList = self::getWeeks($page);

        $sDate = $dateList['星期一'];
        $eDate = $dateList['星期日'];

        // $where = [
        //     //'eid'   => $eid,
        //     //'rDate' => ['between', [$sDate, $eDate]],
        //     'film_reserve_times.status' => 1,
        // ];

        $list = Rt::hasWhere('experts', ['status' => 1])
                    ->with('reserve,experts')
                    ->field('film_reserve_times.id, film_reserve_times.rid, film_reserve_times.eid, film_reserve_times.rDate, film_reserve_times.sTime, film_reserve_times.eTime')
                    ->where(['film_reserve_times.status' => 1])->order('rDate asc, sTime asc')->select()->toArray();
        if (!$list) {
            return ['code' => 1, 'msg' => '暂无可约时间'];
        }
        $arr = [];
        $i = 0;
        foreach ($dateList as $key=>$val) {
            
            $arr[$i]['day'] = date('m月d日', strtotime($val));
            $arr[$i]['week'] = $key;
            $arr[$i]['rDate'] = $val;
            $am = [];
            $pm = [];
            foreach ($list as $k => $v) {
                $v['sTime'] = date('H:i', strtotime($v['sTime']));
                $v['eTime'] = date('H:i', strtotime($v['eTime']));
                
                if ($val == $v['rDate']) {
                        
                    if ((int) $v['sTime'] < 12){
                        $am[] = $v;
                    } else {
                        $pm[] = $v;
                    }
                    $arr[$i]['am'] = $am;
                    $arr[$i]['pm'] = $pm;

                } else {

                    $arr[$i]['am'] = $am;
                    $arr[$i]['pm'] = $pm;
                }
                  
            }
            
            $i++;            
        }
        return ['code' => 0, 'msg' => '成功', 'data' =>  ['list' => $arr]];
    }

    public function getReserveInfo(Request $request) {

        $uid = $this->uid;
        $eid = $request->param('eid', '');
        $rDate = $request->param('rDate', '');
        $type = $request->param('type', '1');

        $info = Ep::where(['id' => $eid, 'status' => 1])->find();

        if (!$info) {
            return ['code' => 1, 'msg' => '专家信息错误'];
        }

        $where = [
            'eid'   => $eid,
            'rDate' => $rDate,
            'status' => ['>', 0],
        ];
        if ($type == 1) {
            $where['sTime'] = ['<', '12:00:00'];
        } else {
            $where['sTime'] = ['>=', '12:00:00'];
        }
        $list = Rt::with('reserve,experts')->where($where)->order('rDate asc, sTime asc')->select()->toArray();

        if (!$list) {
            return ['code' => 1, 'msg' => '暂无可约时间'];
        }
        $arr = [];
        foreach ($list as $k => $v) {
            $arr['day'] = date('m-d', strtotime($rDate));
            $arr['week'] = self::getWeeks(date('w', strtotime($rDate)), false);
            $arr['rDate'] = $rDate;
            $arr['reserve'] = $v['reserve'];
            $arr['experts'] = $v['experts'];
            
            unset($v['reserve'], $v['experts']);

            if ($v['status'] == 2) {
                $v['flag'] = 2;
            } else {
                $v['flag'] = 1;
            }

            if ($v['uid'] = $uid && $v['status'] == 2) {
                $v['flag'] = 3;
            }

            $v['sTime'] = date('H:i', strtotime($v['sTime']));
            $v['eTime'] = date('H:i', strtotime($v['eTime']));
        
            if ((int) $v['sTime'] < 12){
                $arr['time'] = '上午';
                $arr['am'][] = $v;
            } else {
                $arr['time'] = '下午';
                $arr['pm'][] = $v;
            }
        }

        return ['code' => 0, 'msg' => '成功', 'data' =>  ['list' => $arr]];

    }


    public function subOrder(Request $request) {

        $uid = $this->uid;//

        $id = $request->param('id', '');
        $contact = $request->param('contact', '');
        $phone   = $request->param('phone', '');
        $cname   = $request->param('cname', '');


        $info = Rt::where(['status' => 1, 'id' => $id])->find();

        if (!$info) {
            return ['code' => 1, 'msg' => '预约失败'];
        }

        $data = [
            'uid' => $uid,
            'status' => 2,
            'confirm_time' => date('Y-m-d H:i:s'),
            'contact' => $contact,
            'phone'   => $phone,
            'cname'   => $cname,
        ];
        $rt = $info->save($data);

        return ['code' => 0, 'msg' => '成功', 'data' => ['uid' => $uid, 'id' => $id]];
    }

    
    public function orderInfo(Request $request) {
        $uid = $this->uid;

        $id = $request->param('id', '');

        $where = ['status' => 2, 'uid' => $uid, 'id' => $id];
        $info = Rt::with('reserve,experts')->where($where)->find();
        if (!$info) {
            return ['code' => 1, 'msg' => '信息错误'];
        }
        $info = $info->toArray();
        $info['day'] = date('m-d', strtotime($info['rDate']));
        $info['week'] = self::getWeeks(date('w', strtotime($info['rDate'])), false);
        $info['rDate'] = $info['rDate'];
        $info['sTime'] = date('H:i', strtotime($info['sTime']));
        $info['eTime'] = date('H:i', strtotime($info['eTime']));
        if ((int) $info['sTime'] < 12){
            $info['time'] = '上午';
        } else {
            $info['time'] = '下午';
        }
        
        return ['code' => 0, 'msg' => '成功', 'data' => $info];
    }

    public function cancelOrder(Request $request) {
        $uid = $this->uid;
        $id = $request->param('id', '');

        $info = Rt::where(['uid' => $uid, 'id' => $id])->find();
        if (!$info) {
            return ['code' => 1, 'msg' => '信息错误'];
        }

        $info->save(['uid'=> 0, 'status' => 1, 'confirm_time' => date('Y-m-d H:i:s')]);

        return ['code' => 0, 'msg' => '取消成功', 'data' => ['uid' => $uid, 'id' => $id]];
    }

    static public function getWeeks($offset=0, $type = true) {
        $week = [
            1 => '星期一', 
            2 => '星期二', 
            3 => '星期三', 
            4 => '星期四', 
            5 => '星期五', 
            6 => '星期六', 
            7 => '星期日'
        ];

        if ($type) {
            for($i=1; $i<=7; $i++) {
                $num = $i + $offset * 7;
                $date[$week[$i]] = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-date("w")+ $num, date("Y")));
            }
        } else {
            return $week[$offset];
        }
        
        return $date;
    }


    public function  getExpertsInfo(Request $request) {

        $eid = $request->param('id', '');
        $page = $request->param('page', 0);

        $dateList = self::getWeeks($page);

        $sDate = $dateList['星期一'];
        $eDate = $dateList['星期日'];

        // $where = [
        //     //'eid'   => $eid,
        //     //'rDate' => ['between', [$sDate, $eDate]],
        //     'film_reserve_times.status' => 1,
        // ];

        $list = Rt::where(['status' => 2, 'eid' => $eid])->order('rDate asc, sTime asc')                   
                    ->select()->toArray();
        //with('company')
                    //->field('film_reserve_times.id, film_reserve_times.rid, film_reserve_times.eid, film_reserve_times.rDate, film_reserve_times.sTime, film_reserve_times.eTime')
                    
        if (!$list) {
            return ['code' => 1, 'msg' => '暂无已约数据'];
        }
        $arr = [];
        $i = 0;
        foreach ($dateList as $key=>$val) {
            
            $arr[$i]['day'] = date('m月d日', strtotime($val));
            $arr[$i]['week'] = $key;
            $arr[$i]['rDate'] = $val;
            $am = [];
            $pm = [];
            foreach ($list as $k => $v) {
                $v['sTime'] = date('H:i', strtotime($v['sTime']));
                $v['eTime'] = date('H:i', strtotime($v['eTime']));
                // $v['cname'] = $v['company']['cname'];
                // unset($v['company']);
                if ($val == $v['rDate']) {
                        
                    if ((int) $v['sTime'] < 12){
                        $am[] = $v;
                    } else {
                        $pm[] = $v;
                    }
                    $arr[$i]['am'] = $am;
                    $arr[$i]['pm'] = $pm;

                } else {

                    $arr[$i]['am'] = $am;
                    $arr[$i]['pm'] = $pm;
                }
                  
            }
            
            $i++;            
        }
        return ['code' => 0, 'msg' => '成功', 'data' =>  ['list' => $arr]];
    }

}

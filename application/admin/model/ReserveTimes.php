<?php

namespace app\admin\model;

use think\Model;

class ReserveTimes extends Model
{
    //
    static public function getDateList($rid) {
        $list = model('reserve_times')->field('rid, rDate')->where(['rid' => $rid, 'status' => 1])->group('rDate')->select();
        $list = $list->toArray();
        foreach($list as $k=>$v) {
            $times = self::getTimesList($v['rid'], $v['rDate']);
            $list[$k]['times'] = $times;
        }
        return $list;
    }

    static private function getTimesList($rid, $rDate) {
        $list = model('reserve_times')->field('id, rDate, sTime, eTime')->where(['rid' => $rid, 'rDate' => $rDate, 'status' => 1])->select();
        $list = $list->toArray();
        foreach ($list as $k=>$v) {
            // $list[$k]['sTime'] = date('Y-m-d H:i:s', strtotime($v['rDate']. ' ' .$v['sTime']));
            // $list[$k]['eTime'] = date('Y-m-d H:i:s', strtotime($v['rDate']. ' ' .$v['eTime']));
            $list[$k]['sTime'] = $v['rDate']. ' ' .$v['sTime'];
            $list[$k]['eTime'] = $v['rDate']. ' ' .$v['eTime'];
        }
        return $list;
    }

    public function reserve() {
        return $this->hasOne('reserve', 'id', 'rid')->field('id, title, address');
    }

    public function experts() {
        return $this->hasOne('experts', 'id', 'eid')->field('id, name, avatar');
    }
}

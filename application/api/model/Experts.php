<?php

namespace app\api\model;

use think\Model;

class Experts extends Model
{
    public function reserve() {
        return $this->hasMany('reserve', 'eid', 'id')->field('eid,status')->where(['status' => 1]);
    }

    public function reserveTimes() {
        return $this->hasMany('reserve_times', 'eid', 'id')->field('eid,rid,ename,rDate,sTime,eTime,status')->order('rDate asc, sTime asc')->where(['status' => 1]);
    }
}

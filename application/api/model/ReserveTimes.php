<?php

namespace app\api\model;

use think\Model;

class ReserveTimes extends Model
{
    public function reserve() {
        return $this->hasOne('reserve', 'id', 'rid')->where(['status' => 1])->field('id, title, address, intro');
    }

    public function experts() {
        return $this->hasOne('experts', 'id', 'eid')->field('id, name, avatar, breif');
    }

    public function company() {
        return $this->hasOne('user', 'uid', 'uid')->field('uid, cname');
    }
}

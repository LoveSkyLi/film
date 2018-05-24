<?php

namespace app\admin\model;

use think\Model;

class Reserve extends Model
{
    public function experts() {
        return $this->hasOne('experts', 'id', 'eid')->field('name');
    }
}

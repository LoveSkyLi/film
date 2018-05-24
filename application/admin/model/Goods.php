<?php

namespace app\admin\model;

use think\Model;

class Goods extends Model
{
    public function sort() {
        return $this->hasOne('sort', 'id', 'sort_id');

    }

    public function attr() {
        return $this->hasOne('attribute', 'id', 'attr_id');
    }

    public function marker() {
        return $this->hasOne('marker', 'id', 'marker_id');
    }
}

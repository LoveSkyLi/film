<?php

namespace app\admin\model;

use think\Model;

class Experts extends Model
{
    static public function getList() {
        $list = model('experts')->field('id, name')->where(['status' => 1])->select();
        $list = $list->toArray();
        return $list;
    }

    static public function getName($id) {
        $name = \model('experts')->where(['id' => $id])->value('name');
        return $name;
    }
}

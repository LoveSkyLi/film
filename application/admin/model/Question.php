<?php

namespace app\admin\model;

use think\Model;

class Question extends Model
{
    //
    static public function getType() {
        $arr = [
            1 => '单选题',
            2 => '多选题',
            3 => '填空题',
        ];
        return $arr;
    }
}

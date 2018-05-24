<?php

namespace app\api\model;

use think\Model;

class News extends Model
{
    //
    static function getStatusName($status) {
        $name = '';
        $flag = '';
        if ($status == 0) {
            $name = '审核未通过';
            $flag = 1;
        } else if ($status == 5) {
            $name = '已发布';
            $flag = 2;
        } else if ($status >= 1 && $status <= 4) {
            $name = '审核中';
            $flag = 3;
        }

        return ['name' => $name, 'flag' => $flag];
    }
}

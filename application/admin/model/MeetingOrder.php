<?php

namespace app\admin\model;

use think\Model;

class MeetingOrder extends Model
{
    static public function getStatusTextAttr($key=-1, $data=[]) {
        $status = [
            2 => '已核销',
            1 => '未核销',
        ];

        if ($data) {
            return $status[$data['status']];
        }

        if ($key >= 0 ) {
            return $status[$key];
        }
        return $status;
    }
}

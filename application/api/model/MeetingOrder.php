<?php

namespace app\api\model;

use think\Model;

class MeetingOrder extends Model
{
    static public function getSendStatusTextAttr($key='', $data=[]) {
        $status = [
            0 => '未发送',
            1 => '已发送',
        ];

        if ($data) {
            return $status[$data['send_status']];
        }

        if ($key >= 0 ) {
            return $status[$key];
        }
        return $status;
    }
}

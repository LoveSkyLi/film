<?php

namespace app\admin\model;

use think\Model;

class News extends Model
{
    static function getAuditStatusTextAttr($value=1, $data=[]) {
        $arr = [
            0 => '审核未通过',
            1 => '一级审核中',
            2 => '二级审核中',
            3 => '三级审核中',
            4 => '四级审核中',
            5 => '审核通过',
        ];

        if ($data) {
            return $arr[$data['audit_status']];
        }
        return $arr;
    }
}

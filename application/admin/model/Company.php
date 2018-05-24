<?php

namespace app\admin\model;

use think\Model;

class Company extends Model
{
    const COMPANY_STATUS_DEFAULT = 0;
    const COMPANY_STATUS_SUCCESS = 1;
    const COMPANY_STATUS_FAIL    = 2;

    static public function getStatusTextAttr($key=-1, $data=[]) {
        $status = [
            self::COMPANY_STATUS_DEFAULT => '待审核',
            self::COMPANY_STATUS_SUCCESS => '审核通过',
            self::COMPANY_STATUS_FAIL    => '审核未通过',
        ];

        if ($data) {
            return $status[$data['status']];
        }

        return $status;
    }
}

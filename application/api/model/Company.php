<?php

namespace app\api\model;

use think\Model;

class Company extends Model
{
    //
    static public function getInfo($id) {
        $info = model('company')->where(['id' => $id])->find();
        if ($info) {
            $info = $info->toArray();
        } else {
            $info = [];
        }
        return $info;
    }

}

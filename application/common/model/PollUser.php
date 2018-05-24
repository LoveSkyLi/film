<?php

namespace app\common\model;

use think\Model;

class PollUser extends Model
{
    public function option()
    {
        return $this->hasOne('PollOption', 'id', 'option_id');
    }
}

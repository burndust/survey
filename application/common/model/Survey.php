<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/8
 * Time: 19:52
 */

namespace app\common\model;

use traits\model\SoftDelete;

class Survey extends Base
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $hidden = ['user_id', 'create_time', 'update_time', 'delete_time'];
    public function questions(){
        return $this->hasMany('Question','survey_id');
    }
}
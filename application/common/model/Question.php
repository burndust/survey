<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/9
 * Time: 10:18
 */

namespace app\common\model;

use traits\model\SoftDelete;

class Question extends Base
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;

    public function option(){
        return $this->hasMany('QuestionOption','question_id');
    }

    public function answers(){
        return $this->hasMany('Answer','question_id');
    }
}
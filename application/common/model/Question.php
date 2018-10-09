<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/9
 * Time: 10:18
 */

namespace app\common\model;


class Question extends Base
{
    public function option(){
        return $this->hasMany('QuestionOption','question_id');
    }
}
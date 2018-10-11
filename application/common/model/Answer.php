<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/9
 * Time: 16:26
 */

namespace app\common\model;


class Answer extends Base
{
    public function option()
    {
        return $this->hasMany('AnswerOption', 'answer_id');
    }

    public function content()
    {
        return $this->hasOne('AnswerContent', 'answer_id');
    }
}
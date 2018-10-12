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

    public function bindContent()
    {
        return $this->hasOne('AnswerContent', 'answer_id')
            ->bind([
                'content'
            ]);
    }

    public function bindQuestion()
    {
        return $this->belongsTo('Question', 'question_id')
            ->bind([
                'name', 'type'
            ]);
    }
}
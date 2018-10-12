<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/11
 * Time: 8:00
 */

namespace app\common\model;


class AnswerOption extends Base
{
    public function bindQuestionOption()
    {
        return $this->belongsTo('QuestionOption', 'option_id')
            ->bind([
                'content'
            ]);
    }
}
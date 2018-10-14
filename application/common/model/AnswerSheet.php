<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/9
 * Time: 16:26
 */

namespace app\common\model;


class AnswerSheet extends Base
{
    public function answers()
    {
        return $this->hasMany('Answer', 'answer_sheet_id');
    }

    public function bindSurvey()
    {
        return $this->belongsTo('Survey', 'survey_id')
            ->bind([
                'name'
            ]);
    }
}
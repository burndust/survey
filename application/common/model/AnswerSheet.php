<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/9
 * Time: 16:26
 */

namespace app\common\model;

use traits\model\SoftDelete;

class AnswerSheet extends Base
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;

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
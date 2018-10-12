<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/9
 * Time: 10:13
 */

namespace app\api\controller\v1;

use app\common\model\Answer;
use app\common\model\Question as QuestionModel;

class Question extends Base
{
    public function delete($id){
        QuestionModel::destroy($id);
        return show([]);
    }

    public function answerList($id){
        $result = Answer::all(['question_id' => $id],['bindContent']);
        $result = $result ? collection($result)->toArray() : [];
        return show($result);
    }
}
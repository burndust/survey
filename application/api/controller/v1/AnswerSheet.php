<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/10
 * Time: 23:14
 */

namespace app\api\controller\v1;

use app\common\model\Answer;
use app\common\model\AnswerSheet as AnswerSheetModel;
use app\common\model\QuestionOption;
use app\common\model\Survey;
use think\Db;

class AnswerSheet extends Base
{
    public function save()
    {
        $data = [
            'user_id'   => $this->user['id'],
            'survey_id' => $this->params['survey_id'],
        ];
        AnswerSheetModel::startTrans();
        try {
            $answerSheet = AnswerSheetModel::Create($data);
            $questions   = $this->params['questions'];
            $pollList = [];
            foreach ($questions as $k => $v) {
                $answer = Answer::create([
                    'answer_sheet_id' => $answerSheet['id'],
                    'question_id'     => $v['id'],
                ]);
                if (!empty($v['selected_list'])){
                    $option = [];
                    foreach ($v['selected_list'] as $vk => $vv){
                        $pollList[] = $vv;
                        $option[] = ['option_id' => $vv];
                    }
                    $answer->option()->saveAll($option);
                }
                if (!empty($v['content'])) $answer->content()->save(['content' => $v['content']]);
            }
            Survey::where(['id' => $this->params['survey_id']])
                ->update(['sheet_count' => Db::raw('sheet_count+1')]);
            $pollList && QuestionOption::where(['id' => ['in',$pollList]])->update(['poll' => Db::raw('poll+1')]);
            AnswerSheetModel::commit();
            return show([]);
        } catch (\Exception $e) {
            AnswerSheetModel::rollback();
            throw new \Exception($e->getFile() . $e->getLine() . ':' . $e->getMessage());
        }
    }
}
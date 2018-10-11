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
            foreach ($questions as $k => $v) {
                $answer = Answer::create([
                    'answer_sheet_id' => $answerSheet['id'],
                    'question_id'     => $v['id'],
                ]);
                if (!empty($v['option'])) $answer->option()->saveAll($v['option']);
                if (!empty($v['content'])) $answer->content()->save($v['content']);
            }
            Survey::where(['survey_id' => $this->params['survey_id']])
                ->update(['sheet_count' => ['exp', Db::raw('sheet_count+1')]]);
            AnswerSheetModel::commit();
            return show([]);
        } catch (\Exception $e) {
            AnswerSheetModel::rollback();
            throw new \Exception($e->getFile() . $e->getLine() . ':' . $e->getMessage());
        }
    }
}
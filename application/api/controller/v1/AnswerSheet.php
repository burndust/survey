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
use app\common\model\Question;
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
            $answers     = $this->params['answers'];
            $pollList    = [];//保存选中的选项

            foreach ($answers as $v) {
                $answer    = Answer::create([
                    'answer_sheet_id' => $answerSheet['id'],
                    'question_id'     => $v['id'],
                ]);
                if (!empty($v['content'])) {
                    //判断是选择题还是问答题，选择题需要保存选中的选项，问答题需要保存回答内容
                    if (in_array($v['type'], [1, 2]) && is_array($v['content'])) {
                        $option = [];
                        foreach ($v['content'] as $vk => $vv) {
                            $pollList[] = $vv;
                            $option[]   = ['option_id' => $vv];
                        }
                        $answer->option()->saveAll($option);
                    } elseif (3 == $v['type']) {
                        $answer->bindContent()->save(['content' => $v['content'][0]]);
                    }
                }
                Question::where(['id' => $v['id']])
                    ->update([
                        'count_participant' => Db::raw('count_participant+1'),
                    ]);
            }
            //回答的用户积分+1，该问卷答卷数+1，选中选项投票数+1
            $this->user->save(['integral' => $this->user['integral'] + 1]);
            Survey::where(['id' => $this->params['survey_id']])
                ->update(['sheet_count' => Db::raw('sheet_count+1')]);
            $pollList && QuestionOption::where(['id' => ['in',$pollList]])->update(['poll' => Db::raw('poll+1')]);
            AnswerSheetModel::commit();
            return show([], 0, '', 201);
        } catch (\Exception $e) {
            AnswerSheetModel::rollback();
            throw new \Exception($e->getCode() . ':' . $e->getMessage());
        }
    }

    public function index($page = 1)
    {
        $pageSize = empty($this->params['page_size']) ? config('paginate.list_rows') : $this->params['page_size'];
        $where    = ['user_id' => $this->user['id']];
        $hidden   = ['user_id', 'survey_id'];
        $list     = AnswerSheetModel::all(function ($query) use ($where, $page, $pageSize) {
            $query->where($where)->page($page, $pageSize);
        }, ['bindSurvey']);
        $list     = $list ? collection($list)->hidden($hidden)->toArray() : [];
        $count    = AnswerSheetModel::where($where)->count();
        $result   = [
            'list'  => $list,
            'count' => $count,
        ];
        return show($result);
    }
}
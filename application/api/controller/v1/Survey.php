<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/7
 * Time: 15:29
 */

namespace app\api\controller\v1;

use app\common\exception\ParameterException;
use app\common\model\QuestionOption;
use app\common\model\Survey as SurveyModel;
use app\common\model\Question;

class Survey extends Base
{
    public function index()
    {
        $result = SurveyModel::all(['user_id' => $this->user['id']]);
        $result = $result ? collection($result)->toArray() : [];
        return show($result);
    }

    public function save($name, $description)
    {
        $this->params['user_id'] = $this->user['id'];
        $result                  = SurveyModel::create($this->params, true);
        return show(['id' => $result['id']]);
    }

    public function read($id)
    {
        $result = SurveyModel::get($id);
        return show($result->toArray());
    }

    public function update($id)
    {
        $allowField = ['status'];
        $survey     = SurveyModel::get($id);
        $survey->allowField($allowField)->save($this->params);
        return show([]);
    }

    public function delete($id)
    {
        SurveyModel::destroy($id);
        return show([]);
    }

    public function questions($id)
    {
        $allowField = ['name', 'description', 'status'];
        $survey     = SurveyModel::get($id);
        $survey->allowField($allowField)->save($this->params);
        $questions = $this->params['questions'];
        foreach ($questions as $k => $v) {
            if (!(isset($v['name']) && !empty($v['type']) && isset($v['sort']))) {
                throw new ParameterException();
            }
            if (in_array($v['type'], [1, 2])) {
                if (empty($v['option']) || !is_array($v['option'])) {
                    throw new ParameterException();
                }
            }
        }
        Question::startTrans();
        try {
            foreach ($questions as $k => $v) {
                //校验参数没有错误
                //存在问题id,则表示更新
                if (!empty($v['id'])) {
                    $data = [
                        'name' => $v['name'],
                        'type' => $v['type'],
                        'sort' => $v['sort'],
                    ];
                    Question::where(['id' => $v['id']])->update($data);
                    if (!empty($v['option'])) {
                        foreach ($v['option'] as $vk => $vv) {
                            //存在选项id,则表示更新
                            if (!empty($vv['id'])) {
                                QuestionOption::where(['id' => $vv['id']])->update(['content' => $vv['content']]);
                                continue;
                            }
                            $vv['question_id'] = $v['id'];
                            QuestionOption::create($vv, true);
                        }
                    }
                    continue;
                }
                $v['survey_id'] = $id;
                $question       = Question::create($v, true);
                if (!empty($v['option'])) $question->option()->saveAll($v['option']);
            }
            if(!empty($this->params['question_del_list'])){
                Question::destroy($this->params['question_del_list']);
            }
            if (!empty($this->params['question_del_list'])) Question::destroy($this->params['question_del_list']);
            if (!empty($this->params['option_del_list'])) QuestionOption::destroy($this->params['option_del_list']);
            Question::commit();
            $result = SurveyModel::get($id, ['questions' => function ($query) {
                $query->with(['option'])->order('sort');
            }])->toArray();
            return show($result);
        } catch (\Exception $e) {
            Question::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function info($id){
        $result = SurveyModel::get($id,['questions' => function($query){
            $query->with(['option'])->order('sort');
        }])->toArray();
        return show($result);
    }
}
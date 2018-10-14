<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/7
 * Time: 15:29
 */

namespace app\api\controller\v1;

use app\common\exception\BaseException;
use app\common\exception\ParameterException;
use app\common\model\QuestionOption;
use app\common\model\Survey as SurveyModel;
use app\common\model\Question;
use app\common\model\AnswerSheet;
use app\common\validate\IntegralValidate;
use think\Db;

class Survey extends Base
{
    public function index($page = 1)
    {
        $pageSize = empty($this->params['page_size']) ? config('paginate.list_rows') : $this->params['page_size'];
        $where    = ['user_id' => $this->user['id']];
        $list     = SurveyModel::all(function ($query) use ($where, $page, $pageSize) {
            $query->where($where)->page($page, $pageSize);
        });
        $list     = $list ? collection($list)->toArray() : [];
        $count    = SurveyModel::where($where)->count();
        $result   = [
            'list'  => $list,
            'count' => $count,
        ];
        return show($result);
    }

    public function search($word, $page = 1)
    {
        $pageSize = empty($this->params['page_size']) ? config('paginate.list_rows') : $this->params['page_size'];
        $where    = [
            'user_id' => $this->user['id'],
            'name'    => ['like', "%{$word}%"],
        ];
        $list     = SurveyModel::all(function ($query) use ($where, $page, $pageSize) {
            $query->where($where)->page($page, $pageSize);
        });
        $list     = $list ? collection($list)->toArray() : [];
        $count    = SurveyModel::where($where)->count();
        $result   = [
            'list'  => $list,
            'count' => $count,
        ];
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
            if (!empty($this->params['question_del_list'])) {
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

    public function info($id)
    {
        $result = SurveyModel::get($id, ['questions' => function ($query) {
            $query->with(['option'])->order('sort');
        }])->toArray();
        return show($result);
    }

    public function fill($id)
    {
        $where = [
            'user_id'   => $this->user['id'],
            'survey_id' => $id
        ];
        $sheet = AnswerSheet::get($where);
        if ($sheet) {
            throw new BaseException('您已填写过该问卷', self::HAD_ANSWERED, 200);
        }
        $result = SurveyModel::get($id, ['questions' => function ($query) {
            $query->with(['option'])->order('sort');
        }])->toArray();
        return show($result);
    }

    public function sheets($id)
    {
        $result = AnswerSheet::all(['survey_id' => $id], ['answers' => [
            'option' => ['bindQuestionOption'],
            'bindContent',
            'bindQuestion'
        ]]);
        $result = $result ? collection($result)->toArray() : [];
        return show($result);
    }

    public function statistics($id)
    {
        $result = Question::all(function ($query) use ($id) {
            $query->where(['survey_id' => $id])->order('sort');
        }, ['option']);
        $result = $result ? collection($result)->toArray() : [];
        return show($result);
    }

    public function call($id)
    {
        new IntegralValidate();
        if ($this->user['integral'] < $this->params['integral']) {
            throw new BaseException('积分不足', self::INTEGRAL_NOT_WORTH);
        }
        $this->user->save(['integral' => $this->user['integral'] - $this->params['integral']]);
        SurveyModel::where(['id' => $id])
            ->update(['integral' => Db::raw('integral+' . $this->params['integral'])]);
        return show([]);
    }

    public function helpList()
    {
        $pageSize = empty($this->params['page_size']) ? config('paginate.list_rows') : $this->params['page_size'];
        $where    = [
            'status'   => 1,
            'integral' => ['GT', 0]
        ];
        $count    = SurveyModel::where($where)->count();
        if (0 == $count) {
            throw new BaseException('没有需要帮助的问卷', self::RECORD_NOT_FOUND);
        } elseif ($count <= $pageSize) {
            $result = SurveyModel::all(function ($query) use ($where) {
                $query->where($where);
            });
        } else {
            $ids  = SurveyModel::where($where)->column('id');
            $in   = [];
            $take = [];
            $i    = 0;
            while ($i < $pageSize) {
                $rand = mt_rand(0, $count - 1);
                if (in_array($rand, $take)) {
                    continue;
                }
                $take[] = $rand;
                $in[]   = $ids[$rand];
                ++$i;
            }
            $result = SurveyModel::all(function ($query) use ($in) {
                $query->where(['id' => ['in', $in]]);
            });
        }
        $hidden = ['integral', 'status', 'sheet_count'];
        $result = collection($result)->hidden($hidden)->toArray();
        return show($result);
    }
}
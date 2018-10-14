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

    public function answerList($id, $page = 1)
    {
        $pageSize = empty($this->params['page_size']) ? config('paginate.list_rows') : $this->params['page_size'];
        $where    = ['question_id' => $id];
        $hidden   = ['id', 'question_id', 'answer_sheet_id'];
        $list     = Answer::all(function ($query) use ($where, $page, $pageSize) {
            $query->where($where)->page($page, $pageSize);
        }, ['bindContent']);
        $list     = $list ? collection($list)->hidden($hidden)->toArray() : [];
        $count    = Answer::where($where)->count();
        $result   = [
            'list'  => $list,
            'count' => $count,
        ];
        return show($result);
    }
}
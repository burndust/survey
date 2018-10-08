<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/7
 * Time: 15:29
 */

namespace app\api\controller\v1;

use app\common\model\Survey as SurveyModel;

class Survey extends Base
{
    public function index()
    {
        $result = SurveyModel::all();
        $result = $result?collection($result)->hidden(['user_id','create_time','update_time','delete_time']):[];
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
        $result = SurveyModel::get($id)->hidden(['user_id','create_time','update_time','delete_time']);
        return show($result);
    }

    public function update($id)
    {
        $allowField = ['name', 'description'];
        $survey     = SurveyModel::get($id);
        $survey->allowField($allowField)->save($this->params);
        return show([]);
    }
}
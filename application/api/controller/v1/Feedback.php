<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/14
 * Time: 15:09
 */

namespace app\api\controller\v1;

use app\common\model\Feedback as FeedbackModel;

class Feedback extends Base
{
    public function save($content)
    {
        $this->params['user_id'] = $this->user['id'];
        $result                  = FeedbackModel::create($this->params, true);
        return show(['id' => $result['id']]);
    }
}
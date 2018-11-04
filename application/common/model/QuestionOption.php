<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/9
 * Time: 10:27
 */

namespace app\common\model;

use traits\model\SoftDelete;

class QuestionOption extends Base
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $hidden = ['question_id'];
}
<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/4
 * Time: 16:10
 */

namespace app\common\model;

use traits\model\SoftDelete;

class MiniUser extends Base
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
}
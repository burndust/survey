<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/4
 * Time: 16:24
 */

namespace app\common\model;

use traits\model\SoftDelete;

class User extends Base
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
}
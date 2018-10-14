<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/14
 * Time: 12:45
 */

namespace app\api\controller\v1;


class User extends Base
{
    public function info()
    {
        $result = $this->user->hidden(['id'])->toArray();
        return show($result);
    }
}
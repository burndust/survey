<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/18
 * Time: 23:55
 */

namespace app\common\exception;

use app\common\Constant;
use think\exception\Handle;
use think\Log;

class ExceptionHandle extends Handle implements Constant
{
    private $code = self::INTERNAL_SERVER_ERROR;
    private $statusCode = 500;

    public function render(\Exception $e)
    {
        if ($e instanceof BaseException) {
            $this->code       = $e->getCode();
            $this->statusCode = $e->getStatusCode();
        } else {
            Log::init([
                'type'  => 'File',
                'path'  => LOG_PATH,
                'level' => ['sql', 'error'],
            ]);
            Log::record($e->getFile() . ':' . $e->getLine(), 'error');
        }
        if (config('app_debug')) {
            return parent::render($e);
        }
        return show([], $this->code, $e->getMessage(), $this->statusCode);
    }
}
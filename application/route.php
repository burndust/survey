<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
Route::group('api/:version',function(){
    Route::group('/survey',function(){
        Route::resource('','api/:version.survey');
        Route::get('/:id/info','api/:version.survey/info');
        Route::post('/:id/questions', 'api/:version.survey/questions',[],['id' => '\d+']);
    });
    Route::group('/question',function(){
        Route::get('/index/:surveyId', 'api/:version.question/index',[],['surveyId' => '\d+']);
    });
});

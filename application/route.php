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
        Route::get('/search', 'api/:version.survey/search');
        Route::get('/help_list', 'api/:version.survey/helpList');
        Route::resource('','api/:version.survey');
        Route::get('/:id/info','api/:version.survey/info');
        Route::get('/:id/fill', 'api/:version.survey/fill');
        Route::post('/:id/questions', 'api/:version.survey/questions',[],['id' => '\d+']);
        Route::get('/:id/sheets', 'api/:version.survey/sheets', [], ['id' => '\d+']);
        Route::get('/:id/statistics', 'api/:version.survey/statistics', [], ['id' => '\d+']);
        Route::post('/:id/call', 'api/:version.survey/call', [], ['id' => '\d+']);
    });
    Route::group('/question',function(){
        Route::resource('','api/:version.question');
        Route::get('/:id/answer_list', 'api/:version.question/answerList', [], ['id' => '\d+']);
    });
    Route::group('/answer_sheet', function () {
        Route::resource('', 'api/:version.answer_sheet');
    });
    Route::group('/user', function () {
        Route::get('/info', 'api/:version.user/info');
    });
    Route::group('/feedback', function () {
        Route::resource('', 'api/:version.feedback');
    });
});

Survey
===============

Survey是一款调查小程序的后台接口，基于ThinkPHP5.0开发，使用了overtrue/wechat sdk，API设计参照RESTFul  API设计风格，主要功能是创建问卷和填写答卷。问题类型目前只有三类：单选，多选和问答。

配置信息放置于application目录下的product.php。开发者需要将基类控制下的protected $checkLogin = false改为true,并将$this->user = User::get(4);注释掉。survey.sql为数据库结构文件，edit.sql为结构修改文件。

主要流程：

1. 前端发送code，后台根据code获取用户openid,不需要获取用户具体信息。后端生成token返回。
2. 前端请求其他接口时，需要发送当前时间戳，token，和token校验值。后端进行token校验，并将校验值放入缓存，缓存时间可以设置。在缓存时间内，不允许token校验值重复。

## 环境要求

1. PHP >= 7.0(因为使用了overtrue/wechat sdk最新版本，extend目录下也封装了不用sdk进行小程序登录)
2. Redis

## 版权信息

Survey遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2018 by burndust(http://thinkphp.cn)

All rights reserved。

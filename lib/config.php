<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 下午 12:46
 */

define('TIMEZONE', "PRC");                // 时区设置
date_default_timezone_set(TIMEZONE);

 return $config=[
     'rank_api' => [
         'host' => 'http://event.djsh5.com/rank/?',
     ],
     /*'cache_endpoint' => env('cache_endpoint', 'redis://127.0.0.1:6379/' . APP_NAME),
     'stat_db' => env('stat_db', 'ssdb://127.0.0.1:8888/' . APP_NAME),
     'game_room_history_db' => env('game_room_history_db', 'ssdb://127.0.0.1:8888/' . APP_NAME),
     'game_record_db' => env('game_room_history_db', 'ssdb://127.0.0.1:8888/' . APP_NAME),
     'job_queue' => array('endpoint' => env('job_queue_endpoint', 'redis://127.0.0.1:6379/job_queue_' . APP_NAME),
         'tubes' => array('default' => isProduction() ? 6 : 2)),
     'redlock_endpoints' => env('redlock_endpoints', 'redis://127.0.0.1:6379/' . APP_NAME),*/
 ];

/*$wechat_error = array(
    10003 => 'redirect_uri域名与后台配置不一致',
    10004 => '此公众号被封禁',
    10005 => '此公众号并没有这些scope的权限',
    10006 => '必须关注此测试号',
    10009 => '操作太频繁了，请稍后重试',
    10010 => 'scope不能为空',
    10011 => 'redirect_uri不能为空',
    10012 => 'appid不能为空',
    10013 => 'state不能为空',
    10015 => '公众号未授权第三方平台，请检查授权状态',
    10016 => '不支持微信开放平台的Appid，请使用公众号Appid'
);*/

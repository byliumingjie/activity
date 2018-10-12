<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-10-11
 * Time: 下午 06:19
 */
class ActivitesModel
{
    public $sid = null;
    public $appid = null;
    public $openid = null;
    public $channel = null;
    public $act_type = null;
    public $act_rule = null;
    public $config_data = null;
    private $redis = null;
    private $time = null;
    private $utils = null;
    private $cache_key = null;
    private $file_name = null;
    private $service = null;
    private $server_prefix = null;
    public $ranking_type = [1 => 'power', 2 => 'level'];
    public $role_ranking_type = null;

    public function __construct($appid = null)
    {
        $this->httpDataVerif();
        $this->utils = new Utils();
        $this->time = date(DATE_FORMAT_S, time());
        $this->file_name = $this->getFileName($this->appid);
        $this->config_data = $this->utils->getFileConfig($this->file_name);
        $this->server_prefix = $this->config_data['server_prefix'];
        $this->isActivites(); // 活动争取验证.
        $this->service = new ActivitySer();

        $this->cache_key = isBlank($this->cache_key)
            ?
            $this->cache_key : $this->getCacheKey();

    }

    /***
     * 初始基础配置数据
     * @return bool
     */
    public function httpDataVerif()
    {
        $request = file_get_contents("php://input");

        $this->appid = (isset($request['appid']) && !isBlank($request['appid']))
            ?
            $request['appid'] : 'chuanqi';
        $this->openid = isset($request['openid']) && !isBlank($request['openid'])
            ?
            $request['openid'] : md5(time());
        $this->channel = isset($request['channel']) && !isBlank($request['channel'])
            ?
            $request['channel'] : 'aiweiyou';
        $this->act_type = isset($request['act_type']) && !isBlank($request['act_type'])
            ?
            $request['act_type']
            : 'ranking';
        $this->sid = isset($request['sid']) && !isBlank($request['appid'])
            ?
            $request['sid'] : 1;
        $this->role_ranking_type = isset($request['role_ranking_type']) ? $request['role_ranking_type'] : 1;
        return true;

    }

    /***
     * 参数是否为空
     * @param $data
     * @return bool
     */
    public function isParame($data)
    {
        if (isset($data) && !isBlank($data)) {
            return $data;
        }
        log_message::info("$data is null ...");
        return false;
    }

    //
    public function getCacheKey()
    {
        return 'global_config';
    }

    public function getFileName($appid)
    {
        return $appid . '.json';
    }
    //

    /***
     * 排行榜数据获取
     */
    public function getRanking()
    {
        $data = [];

        $rank_url = config('rank_api');
        log_message::info($rank_url);
        $data = array(
            'g' => 'WCNKH3c3TLdlo',
            'noip' => 1,
            's' => $this->server_prefix . $this->sid,
            'c' => $this->channel,
            'type' => $this->ranking_type[$this->role_ranking_type]
        );
        //$data = json_encode($data);
        log_message::info($rank_url['host'] . $this->channel);
        $ret = $this->utils->send_request($rank_url['host'] . $this->channel, $data);
        $ret = json_encode(json_decode($ret, true), JSON_UNESCAPED_UNICODE);

        return $ret;
    }

    public function ranKingVerif()
    {

    }

    public function rankingBack()
    {
        $this->service->redis->set('ttt', '哈哈');
        $data = $this->service->redis->get('ttt');
        return $data;
    }

    /***
     * @param $role_sid
     * @param $min_sid
     * @param $max_sid
     */
    public function isActivites()
    {
        $this->isApp() ? null : exit('游戏不存在');             // 游戏是否存在
        $this->isActivitesStatus() ? null : exit('活动不存在'); // 活动是否开启
        $this->isActivityType() ? null : exit('活动类型不存在');    // 活动类型是否存在
        $this->isServer() ? null : exit('渠道活动区服不存在或范围有误');          // 渠道活动的区服范围是否正常
        $this->isActivitesTime() ? null : exit('渠道活动时间已经失效');   // 渠道是否在有效时间范围内
        return true;
    }

    /***
     * 活动时间校验是否为有效期
     * @param $start_at
     * @param $end_at
     * @return bool
     */
    public function isActivitesTime($start_at = null, $end_at = null)
    {
        $act_rule = $this->isActivityType();

        $start_at = $act_rule['start_at'];
        $end_at = $act_rule['end_at'];

        if (($this->time >= $start_at) && ($this->time <= $end_at)) {
            return true;
        }
        return false;
    }

    /***
     * 是否存在游戏
     * @return bool
     */
    public function isApp()
    {
        if ($this->appid == $this->config_data['appid']) {
            return true;
        }
        return false;
    }

    /***
     * 是否存在渠道
     * @return bool
     */
    public function isChannel()
    {
        if (strripos($this->config_data['channel_code'], $this->channel)) {
            return true;
        }
        log_message::info('channel is null false !!!');
        return false;
    }

    /***
     * 活动状态是否已经开启
     * @param $status
     * @return bool
     */
    public function isActivitesStatus($status = null)
    {
        $status = $this->config_data['act_status'];
        log_message::info('ddddddddddddddd^^^' . $status);
        if (isset($status)) {
            if ($status == ACTIVITES_STATUS) {
                return true;
            }
            log_message::info($this->utils->errorCode('status is 0 ', FAILURE));
            return false;
        }
        log_message::info($this->utils->errorCode('status is null ', FAILURE));
        return false;
    }

    /***
     * 是否存在活动类型
     * @param null $act_type
     * @return bool
     */
    public function isActivityType($act_type = null)
    {
        $act_rule = json_decode($this->config_data['act_rule'], true);
        log_message::info('ddd' . $this->config_data['act_rule']);
        if (isset($act_rule) && count($act_rule) > ZERO) {

            if (isset($act_rule[$this->act_type])) {
                return $act_rule[$this->act_type];
            }
            log_message::info('不存在的活动类型...' . $act_rule[$this->act_type]);
            return false;
        }
        log_message::info('不存在的活动类型2');
        return false;
    }

    /***
     * 是否在正常的区服范围
     * @param $role_sid
     * @param $min_sid
     * @param $max_sid
     * @return bool
     */
    public function isServer($role_sid = null)
    {
        $min_sid = $this->config_data['server_min'];
        $max_sid = $this->config_data['server_max'];
        $role_sid = $this->sid;
        if (isset($min_sid) && isset($max_sid)) {
            if ($role_sid >= $min_sid && $role_sid <= $max_sid) {
                return true;
            }
            log_message::info('不存在的区服');
            return false;
        }
        log_message::info('区服空');
        return false;
    }

    /***
     * 活动去活动配置
     * @param $file_name
     * @return bool|mixed
     */
    public function getActivitesJson()
    {
        if (!isBlank($this->config_data)) {
            return $this->config_data;
        }
        exit('config data is null');
    }

    public function setConfig($data, $cache_key = null)
    {
        $cache_key = isBlank($cache_key) ? $this->cache_key : $cache_key;
        //
        $ret = $this->service->setConfigCache($cache_key, $this->appid, $data);

        if ($ret) {
            return $ret;
        }
        exit('set config false');
    }

    public function setUserinfo()
    {

    }

}
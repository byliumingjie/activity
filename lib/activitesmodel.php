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
    public $config_data = null;
    private $redis = null;
    private $time = null;
    private $utils = null;
    private $cache_key = null;
    private $file_name = null;
    private $service = null;

    public function __construct($appid = null)
    {
        $this->httpDataVerif();
        $this->utils = new Utils();
        $this->service = new ActivitySer();

        $this->time = date(DATE_FORMAT_S, time());
        $this->file_name = $this->getFileName($this->appid);
        $this->config_data = $this->utils->getFileConfig($this->file_name);
        $this->cache_key = isBlank($this->cache_key)
        ?
        $this->cache_key : $this->getCacheKey();
        $this->isActivites();
    }

    public function httpDataVerif()
    {
        $request = file_get_contents("php://input");

        $this->appid = (isset($request['appid']) && !isBlank($request['appid']))
            ?
            $request['appid'] : 'chuanqi';
        $this->openid = isset($request['openid']) && !isBlank($request['appid'])
            ?
            $request['openid'] : md5(time());
        $this->channel = isset($request['channel']) && !isBlank($request['appid'])
            ?
            $request['channel'] : 1;
        $this->act_type = isset($request['act_type']) && !isBlank($request['appid'])
            ?
            $request['act_type']
            : 'ranking';
        $this->sid = isset($request['sid']) && !isBlank($request['appid'])
            ?
            $request['sid'] : 1;
        return true;
    }

    /***
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
     * app_stat_ranking_back
     * id
     * appid
     * channel
     * sid
     * openid
     * nickname
     * type
     * number
     * create_at
     */
    public function getRanking($redis_key)
    {
        $data = [];

        return $data;
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
        $this->isActivitesStatus(); // 活动是否开启
        $this->isActivityType();    // 活动类型是否存在
        $this->isServer();          // 渠道活动的区服范围是否正常
        $this->isActivitesTime();   // 渠道是否在有效时间范围内
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
     * 活动状态是否已经开启
     * @param $status
     * @return bool
     */
    public function isActivitesStatus($status = null)
    {
        $status = $this->config_data['act_status'];
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
     * @param null $act_type
     * @return bool
     */
    public function isActivityType($act_type = null)
    {
        $act_rule = $this->config_data['act_rule'];

        if (isset($act_rule) && count($act_rule) > ZERO) {

            if (isset($act_rule[$this->act_type]))
            {
                return $act_rule[$this->act_type];
            }
            log_message::info('不存在的活动类型');
            return false;
        }
        log_message::info('不存在的活动类型2');
        return false;
    }

    /***
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
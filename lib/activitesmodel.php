<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-10-11
 * Time: 下午 06:19
 */
class ActivityModel extends Xredis
{
    private $redis = null;
    private $time = null;

    public function __construct()
    {
        $this->redis = new Xredis();
        $this->time = date(DATE_FORMAT_S, time());
    }

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
        $this->redis->set('ttt', '哈哈');
        $data = $this->redis->get('ttt');
        return $data;
    }

    /***
     * 活动时间校验是否为有效期
     * @param $start_at
     * @param $end_at
     * @return bool
     */
    public function isActivitesTime($start_at, $end_at)
    {
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
    public function isActivitesStatus($status)
    {
        if ($status == ACTIVITES_STATUS) {
            return true;
        }
        return false;
    }

}
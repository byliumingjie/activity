<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 下午 6:28
 */
class  ActivitySer extends Mysqldb
{
    public $mysql;

    function __construct()
    {
        $this->mysql = new Mysqldb();
    }

    //wechat_sign_activity
    public function GetActivitySign($openId)
    {
        $sql = "SELECT id,openid,rule,create_at FROM wechat_sign_activity WHERE openid='" . $openId . "' limit 1 ";

        if ($this->mysql->query($sql)) {
            return $this->mysql->fetch_row();
        }
        return false;
    }

    public function SetActivitySign($openId, $ruleinfo)
    {
        $sql = "INSERT INTO wechat_sign_activity (openid,rule,create_at) VALUES('" . $openId . "','" . $ruleinfo . "',NOW())";
        if ($this->mysql->query($sql)) {
            error_log('1' . "\n", 3, 'D:\xampp\htdocs\wechat\log\wechat-log.txt');
            return true;
        }
        return false;
    }

    public function EditActivitySign($infostr, $id)
    {
        $up = $this->mysql->update2('wechat_sign_activity', ['rule' => $infostr], 'id=:id', array('id' => $id));
        if ($up) {

            return true;
        }
        return false;
    }

    /***
     * 礼包码变更
     */
    public function EditGiftCode($id)
    {
        $up = $this->mysql->update2('wechat_gift_code', ['status' => 1], 'id=:id', array('id' => $id));
        if ($up) {

            return true;
        }
        return false;
    }

    /**
     * 礼包码获取 1
     */
    public function GetGiftCode($type)
    {
        $sql = "SELECT type,giftcode,status FROM wechat_gift_code WHERE type=" . $type . " AND status=GIFTCODE_STATUS limit 1 ";
        error_log($sql . "\n", 3, 'D:\xampp\htdocs\wechat\log\wechat-sql-log.txt');
        if ($this->mysql->query($sql)) {
            return $this->mysql->fetch_row();
        }
        return false;
    }

    /**
     * 礼包码清理
     */
    public function ClearGiftCode($id)
    {
        $sql = "DELETE FROM wechat_gift_code WHERE id=$id";

        if ($this->mysql->query($sql)) {
            return true;
        }
        return false;
    }

    /***
     * @param  type 对应不同类型获取不同的配置属性
     * 活动配置获取
     */
    public function ActivityConfigList($type = null)
    {
        $sql = "SELECT type,title,desc FROM wechat_activity_config";
        if ($this->mysql->query($sql)) {
            return $this->mysql->fetch_all();
        }
        return false;
    }
}
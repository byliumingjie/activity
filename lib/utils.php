<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 下午 3:24
 */
class  Utils
{
    public $timelist;
    public $timearray;
    private $day = 1;
    private $datatamp = 86400;
    private $signvar = 1;

    public function __construct()
    {
        unset($this->timelist);
        unset($this->timearray);
        $this->GetDayList();
    }

    public function GetDayList()
    {
        $totalDay = date("t");

        //获取本月第一天时间戳
        $start_time = strtotime(date('Y-m-01'));

        $array = array();

        for ($i = 0; $i < $totalDay; $i++) {
            //每隔一天赋值给数组
            $array[] = date('Ymd', $start_time + $i * $this->datatamp);
        }
        $this->timearray = $array;
        return $array;
    }

    /***
     * 活动 data info
     * @param null $array
     * @return bool
     */
    public function SetDay($array = null)
    {
        $array = (isset($array) && !empty($array)) ? $array : $this->timearray;

        if (empty($array) || count($array) <= ZERO) {
            return false;
        }

        $i = $this->day;

        foreach ($array as $times) {

            $data[$i] = array
            (
                'day' => $times,
            );
            $i++;
        }
        return ['sign' => $data];
    }

    /***
     * 设置 sign
     * @param string $time
     * @return array
     */
    public function SetSignDay($data = array(), $time = '20180902')
    {
        // $data = $this->SetDay();

        $dataOut = [];

        foreach ($data['sign'] as $key => $var) {

            if ($var['day'] == $time) {

                $var['sign'] = $this->signvar;
            }
            $dataOut[$key] = $var;
        }
        return ['sign' => $dataOut];
    }

    public function SetMesage($data, $mesage, $code = ZERO)
    {
        $ret = ($code == ZERO) ? 'success' : 'failure';

        if (isset($data) && count($data) > ZERO) {

            $data['ret'] = $ret;
            $data['msg'] = $mesage;
            $data['login'] = true;
        }
        return $data;
    }

    public function GetdefaultConfig()
    {
        $data = $this->SetDay();
        $data['ret'] = 'success';
        $data['msg'] = 'default day list ';
        $data['login'] = true;
        return $data;
    }

    /***
     * 礼包规则配置 获奖励规则
     */
    public function isGiftDay()
    {

    }

    /***
     * Json 生成规则
     */
    public function ActivityConfig($pc = null, $channel = null)
    {
        $Acitvity = [
            'pc' => $pc,
            'channel' => $channel
        ];
        // $dd = json_encode($Acitvity);
    }

    /**
     * 是不是简单基础类型(null, boolean , string, numeric)
     * @param $object
     * @return bool
     */
    function isPrimary($object)
    {
        return is_null($object) || is_bool($object) || is_string($object) || is_numeric($object);
    }

    function isBlank($object)
    {
        if (is_null($object) || '' === $object || (is_array($object) && count($object) < 1)) {
            return true;
        }
        return empty($object);
    }

    /***
     * @param $json_config_name
     * @return bool|mixed
     */
    function getFileConfig($file_name)
    {
        if ($headers = get_headers(RES_CONFIG_URL . DIR_SEPARATOR . $file_name)) {
            if (strpos($headers[ZERO], '404') === false) {
                $url = RES_CONFIG_URL . DIR_SEPARATOR . $file_name;
                $pagecontent = trim(file_get_contents($url));

                if (!isBlank($pagecontent)) // runtime
                {
                    return json_decode($pagecontent, true);
                }
                log_message::info('open url json data null');
                return false;
            }
            log_message::info('open url json false');
            return false;
        }
        log_message::info('json url false');
        return false;
    }

    /***
     * 区服区间验证
     */
    public function serverVerif($role_sid, $min_sid, $max_sid)
    {
        if (($role_sid >= $min_sid) && ($role_sid <= $max_sid)) {
            return true;
        }
        log_message::info('user sid 区间有误');
        return false;
    }

    /***
     * @param $url
     * @param $data
     * @param string $coding
     * @param string $refererUrl
     * @param string $method
     * @param string $contentType
     * @param int $timeout
     * @param bool $proxy
     * @return bool|null|string
     */
    public function send_request($url, $data, $coding = 'gbk', $refererUrl = '',
                                 $method = 'POST', $contentType = 'application/json;', $timeout = 30, $proxy = false)
    {
        $ch = $responseData = null;
        //$data = trim(mb_convert_encoding($data, "gbk", "utf-8"));
        if ('POST' === strtoupper($method)) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $info = curl_getinfo($ch);
            if ($refererUrl) {
                curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
            }
            $contentType = '';
            if ($contentType) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $contentType);
            }
            if (isset($data['upload_file'])) {
                $file = new \CURLFile($data['upload_file']['file'],
                    $data['upload_file']['type'], $data['upload_file']['name']);
                $params[$data['upload_file']['get_name']] = $file;
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
        } else if ('GET' === strtoupper($method)) {
            if (is_string($data)) {
                $real_url = $url . rawurlencode($data);
            } else {
                $real_url = $url . http_build_query($data);
            }
            $urldata = rawurlencode($data);
            $ch = curl_init($real_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:' . $contentType]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            if ($refererUrl) {
                curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
            }
        } else {
            $args = func_get_args();
            return false;
        }
        if ($proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        $ret = curl_exec($ch);
        curl_close($ch);
        $responseData = $ret;
        //$responseData = mb_convert_encoding($ret, "utf-8", "gbk");
        return $responseData;
    }

    /***
     * @param $messige
     * @param $code
     */
    public function errorCode($messige, $code)
    {
        $data = array(
            'res' => $messige,
            'status' => $code
        );
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
<?php
require "autoload.php";

$apppid = 'chuanqi';
$server_id = 1;
$openid = md5(time());
$act_type = "ranking"; // $act_type = "lottery"; // signin
$json_config_name = $apppid . '.json';

$util = new Utils();
    $act_mod = new  ActivityModel();

$data = $util->getFileConfig($json_config_name);
$act_rule = json_decode($data['act_rule'], true);
//var_dump($act_rule);

switch ($act_type) {
    case 'ranking':
        echo $act_mod->rankingBack();
        break;
    case 'lottery':
        break;
    case 'signin':
        break;
    default;
        break;
}


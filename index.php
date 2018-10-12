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

switch ($act_type) {
    case 'ranking':
        $redvar = $act_mod->rankingBack();
        if ($redvar){
            exit;
        }
        break;
    case 'lottery':
        break;
    case 'signin':
        break;
    default;
        break;
}


<?php
require "autoload.php";

/*$apppid = 'chuanqi';
$server_id = 1;
$openid = md5(time());
$act_type = "ranking"; // $act_type = "lottery"; // signin*/

$activites_mode = new  ActivitesModel();

$data = $activites_mode->getActivitesJson();

var_dump($data);

switch ($activites_mode->act_type) {
    case 'ranking':
        $redvar = $activites_mode->rankingBack();

        break;
    case 'lottery':
        break;
    case 'signin':
        break;
    default;
        break;
}


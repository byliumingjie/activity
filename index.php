<?php
require "autoload.php";

$activites_mode = new  ActivitesModel();

$data = $activites_mode->getActivitesJson();

switch ($activites_mode->act_type) {
    case 'ranking':
        $ranking = $activites_mode->getRanking();
        echo $ranking;
        break;
    case 'lottery':
        echo "223213123rsefesdf4dg";
        break;
    case 'signin':
        break;
    default;
        break;
}
?>
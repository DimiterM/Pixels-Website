<?php

require_once "models/config.php";
require "models/ads.php";

$model = new Ads("ads");
$ad_info = $model->get_details($_GET['id']);

global $ADS_IMAGES_DIR;
$ad_info['filename'] = $ADS_IMAGES_DIR . $ad_info['filename'];
echo json_encode($ad_info);

?>
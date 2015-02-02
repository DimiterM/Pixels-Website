<?php

//require_once $_SERVER['DOCUMENT_ROOT'] . "models/config.php";
require $_SERVER['DOCUMENT_ROOT'] . "models/ads.php";

$model = new Ads("ads");
$ad_info = $model->get_details($_GET['id']);

//global $ADS_IMAGES_DIR;
$ad_info['filename'] = "/images/ads/" . $ad_info['filename'];

echo json_encode($ad_info);

?>
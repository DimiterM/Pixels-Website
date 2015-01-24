<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "models/ads.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "models/big_pic.php";

// cleanup 'newads' table
$newads_model = new Ads("newads");
$newads_to_delete = $newads_model->select_datetime_latest(1);
foreach ($newads_to_delete as $index => $newad)
{
	$newads_model->delete_ad($newad['id']);
}


// create big pic
$ads_model = new Ads("ads");
$ads = $ads_model->get_all_images_info();
$big_pic_model = new BigPic();
$big_pic_model->build_big_pic($ads);

?>
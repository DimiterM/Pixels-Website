<?php

require_once "models/ads.php";
require_once "models/big_pic.php";

$ads_model = new Ads("ads");
$ads = $ads_model->get_all_images_info();

$big_pic_model = new BigPic();
$big_pic_model->build_big_pic($ads);

?>
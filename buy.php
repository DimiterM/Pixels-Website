<!DOCTYPE html>
<html>
<head>
	<title>Buy pixels - More than just 10^6 pixels</title>
</head>
<body>

<?php
include $_SERVER['DOCUMENT_ROOT'] . "models/config.php";
global $PIC_WIDTH, $PIC_HEIGHT;

include $_SERVER['DOCUMENT_ROOT'] . "views/load_templates.php";
echo TemplateLoader::load("header_nav_template.html");
?>

<section>
<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "models/ads.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "models/big_pic.php";

$ads_model = new Ads("ads");
$ads_new = $ads_model->select_datetime_latest(24);

$newads_model = new Ads("newads");
$newads_reserved = $newads_model->select_datetime_latest(1);

$big_pic_model = new BigPic();
$big_pic_model->build_shadow_pic(array_merge($ads_new, $newads_reserved));

// TODO - canvas - $big_pic_model->big_pic_shadow_filename
// + hidden fields in the form for the polygon

?>

<form method="post" action="pixels_reserve.php">
	Name: <input type="text" name="name"><br/>
	Link: <input type="url" name="link"><br/>
	Picture: <input type="file" name="picture"><br/>
	<input type="hidden" name="coords" value="">
	<input type="submit" value="Buy!">
</form>

<div>
<p>Select coordinates: </p>
<img src="/images/big_pic_shadows.png" 
	width="<?php echo $PIC_WIDTH; ?>" height="<?php echo $PIC_HEIGHT; ?>" 
	alt="Pixels Reserved">
</div>

</section>

<?php
echo TemplateLoader::load("footer_template.html");
?>
</body>
</html>
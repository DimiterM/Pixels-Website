<!DOCTYPE html>
<html>
<head>
	<title>More than just 10^6 pixels</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>

<?php
include $_SERVER['DOCUMENT_ROOT'] . "models/config.php";
global $PIC_WIDTH, $PIC_HEIGHT;

include $_SERVER['DOCUMENT_ROOT'] . "views/load_templates.php";
echo TemplateLoader::load("header_nav_template.html");
?>

<section>
<img src="/images/big_pic.png" 
	width="<?php echo $PIC_WIDTH; ?>" height="<?php echo $PIC_HEIGHT; ?>" 
	alt="Pixels" usemap="#pixels">

<map name="pixels">
<?php

require $_SERVER['DOCUMENT_ROOT'] . "models/ads.php";
require $_SERVER['DOCUMENT_ROOT'] . "views/areas.php";

$model = new Ads("ads");
$data = $model->get_basic_info();

$view = new Areas();
echo $view->data_to_html($data);

?>
</map>
</section>

<?php
echo TemplateLoader::load("footer_template.html");
?>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
	<title>News posts - More than just 10^6 pixels</title>
</head>
<body>

<?php
include $_SERVER['DOCUMENT_ROOT'] . "views/load_templates.php";
echo TemplateLoader::load("header_nav_template.html");
?>

<section>
<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "models/blogposts.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "views/article.php";

$model = new Blogposts();
$view = new Article();

if(!isset($_GET['id']))
{
	$news = $model->get_all( (isset($_GET['page']) ? $_GET['page'] : 0) );
	foreach ($news as $n => $article)
	{
		echo $view->data_to_html($article);
	}
}
else
{
	$article = $model->get_details($_GET['id']);
	echo $view->data_to_html($article);
}

?>
</section>

<?php
echo TemplateLoader::load("footer_template.html");
?>
</body>
</html>
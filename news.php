<!DOCTYPE html>
<html>
<head>
	<title>News posts - More than just 10^6 pixels</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
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
	$page = (isset($_GET['page']) ? $_GET['page'] : 0);
	$news_per_page = 10;
	$news = $model->get_all($page, $news_per_page);
	foreach ($news as $n => $article)
	{
		echo $view->data_to_html($article);
	}

	$hasNext = ($model->count() > ($page + 1) * $news_per_page);
	echo $view->page_buttons($page, $hasNext);
}
else
{
	$article = $model->get_details($_GET['id']);
	echo $view->data_to_html($article);
	echo $view->return_button();
}

?>
</section>

<?php
echo TemplateLoader::load("footer_template.html");
?>
</body>
</html>
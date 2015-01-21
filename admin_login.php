<!DOCTYPE html>
<html>
<head>
	<title>Admin login - More than just 10^6 pixels</title>
</head>
<body>

<?php
include "views/load_templates.php";
echo TemplateLoader::load("header_nav_template.html");
?>

<section>
	<form action="admin.php" method="POST">
		Username:
		<br>
		<input type="text" name="username">
		<br>
		Password:
		<br>
		<input type="password" name="password">
		<br>
		<br>
		<input type="submit" value="Login!">
	</form> 
</section>

<?php

require_once "models/config.php";
global $ADMIN_USERNAME;

session_start();

if(isset($_SESSION['username']) && $_SESSION['username'] == $ADMIN_USERNAME)
{
	session_destroy();
	header("Location: index.php");
	die();
}
elseif($_SERVER['REQUEST_METHOD'] == "POST")
{
	// TODO - do login

	header("Location: index.php");
	die();
}
?>

<?php
echo TemplateLoader::load("footer_template.html");
?>
</body>
</html>

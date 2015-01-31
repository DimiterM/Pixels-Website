<!DOCTYPE html>
<html>
<head>
	<title>Admin page - More than just 10^6 pixels</title>
</head>
<body>

<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . "views/load_templates.php";
echo TemplateLoader::load("header_nav_template.html");
?>


<?php

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	if(isset($_SESSION['username']) && $_POST['password'] == 0)
	{
		// logout
		session_destroy();
		header("Location: index.php");
		die();
	}
	elseif(isset($_SESSION['username']) && $_POST['password'] != "")
	{
		// change password
		require_once $_SERVER['DOCUMENT_ROOT'] . "models/admin.php";
		$model = new Admin();

		if($_POST['new1'] != $_POST['new2'])
		{
			die("New password does not match!");
		}

		$result = $model->change_password($_POST['password'], $_POST['new1']);
		if($result)
		{
			echo "Password changed!";
		}
	}
	else
	{
		// login
		require_once $_SERVER['DOCUMENT_ROOT'] . "models/admin.php";
		$model = new Admin();
	
		$user_auth = $model->verify($_POST['username'], $_POST['password']);
		if($user_auth)
		{
			$_SESSION['username'] = $user_auth;
			header("Location: index.php");
			die();
		}

		die("Wrong username/password!");
	}
}
elseif(isset($_SESSION['username'])) 	// && $_SERVER['REQUEST_METHOD'] == "GET"
{
	// admin is online
	echo TemplateLoader::load("admin_info_form.html");
	echo TemplateLoader::load("admin_edit_ads.html");
	echo TemplateLoader::load("admin_edit_blogposts.html");
}
else 	  // !isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] == "GET"
{
	// unidentified user
	echo TemplateLoader::load("admin_login_form.html");
}

?>


<?php
echo TemplateLoader::load("footer_template.html");
?>
</body>
</html>

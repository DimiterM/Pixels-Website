<?php

require_once "models/ads_new.php";

session_start();

$is_reserved = reserve_area($_POST['data']);

if (!is_numeric($is_reserved))
{
	die("Sorry! Pixels could not be reserved! <br/> " . $is_reserved);
}

$_SESSION['id'] = $is_reserved;
require_once "paypal_form.php";

?>
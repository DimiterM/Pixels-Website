<?php

require_once "models/ads.php";
require_once "models/ads_new.php";

$model = new Ads($request['tablename']);

switch ($request['action'])
{
	case "select":
		if(isset($_REQUEST['id']))
			return $model->get_details($_REQUEST['id']);
		return 0;
	case "insert":
		return 0;
    case "update":
        return 0;
    case "delete":
        return $model->delete_ad($_REQUEST['id']);
}

/**
* SELECT:
* 		if id is set -> match by id
*		else -> match by params ("" do not count)
* 
* INSERT: set new values from params
* 
* UPDATE: by id - set new values from params ("" do not count)
* 
* DELETE: by id
* 
*/

?>
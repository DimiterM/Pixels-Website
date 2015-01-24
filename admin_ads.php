<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "models/ads.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "models/ads_new.php";

$model = new Ads($request['tablename']);

switch ($request['action'])
{
	case "select":
		if(isset($_REQUEST['id']))
			return $model->get_details($_REQUEST['id']);
		return $model->select_filter(
			$_REQUEST['name'], $_REQUEST['link'], $_REQUEST['coords'], 
			$_REQUEST['from_datetime'], $_REQUEST['to_datetime']
		);
	case "insert":
		{
			if(isset($_REQUEST['filename']) && $_FILES['picture'])
				upload_picture($_FILES['picture'], $_REQUEST['filename']);
			return $model->insert_ad($_REQUEST['name'], $_REQUEST['link'], 
				$_REQUEST['coords'], $_REQUEST['filename']);
		}
    case "update":
		{
			if(isset($_REQUEST['filename']) && $_FILES['picture'])
			{
				$old_filename = $model->get_details($_REQUEST['id'])['filename'];
				$upload_result = upload_picture($_FILES['picture'], $_REQUEST['filename']);
				if($upload_result)
					unlink($old_filename);
				else
					return $upload_result;
			}
			return $model->update_ad($_REQUEST['name'], $_REQUEST['link'], 
				$_REQUEST['coords'], $_REQUEST['filename']);
		}
    case "delete":
        return $model->delete_ad($_REQUEST['id']);
}

/**
* SELECT:
* 		if id is set -> match by id
*		else -> match by params ("" do not count)
* 
* INSERT:
* 		set new values from params
* 		if filename is set -> upload pic also
* 		(if coords is set -> class method checks for intersections)
* 
* UPDATE:
* 		by id -> set new values from params ("" do not count)
* 		if filename is set -> upload new pic + delete old pic also
* 		(if coords is set -> class method checks for intersections)
* 
* DELETE: by id
* 
*/

?>
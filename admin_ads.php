<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "models/ads.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "models/ads_new.php";

$model = new Ads($_REQUEST['tablename']);

$result = array();

switch ($_REQUEST['action'])
{
	case "select":
		{
			if($_REQUEST['id'])
			{
				$result = $model->get_details($_REQUEST['id']);
				break;
			}
			$result = $model->select_filter(
				$_REQUEST['name'], $_REQUEST['link'], $_REQUEST['coords'], 
				$_REQUEST['from_datetime'], $_REQUEST['to_datetime']
			);
			break;
		}
	case "insert":
		{
			$filename = "";
			if($_FILES['picture']['tmp_name'])
				upload_picture($_FILES['picture'], $filename);
			$result = $model->insert_ad($_REQUEST['name'], $_REQUEST['link'], 
				$_REQUEST['coords'], $filename);
			break;
		}
    case "update":
		{
			$filename = "";
			if($_FILES['picture']['tmp_name'])
			{
				$old_filename = $model->get_details($_REQUEST['id'])['filename'];
				$upload_result = upload_picture($_FILES['picture'], $filename);
				if($upload_result)
					unlink($model->images_dir . $old_filename);
				else
					return $upload_result;
			}
			$result = $model->update_ad($_REQUEST['name'], $_REQUEST['link'], 
				$_REQUEST['coords'], $filename, $_REQUEST['id']);
			break;
		}
    case "delete":
    {
    	$filename = $model->get_details($_REQUEST['id'])['filename'];
    	unlink($model->images_dir . $filename);
    	$result = $model->delete_ad($_REQUEST['id']);
    	break;
    }
}

echo json_encode($result);


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
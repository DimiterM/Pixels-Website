<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "models/blogposts.php";

$model = new Blogposts();

$result = array();

switch ($_REQUEST['action'])
{
	case "select":
	{
		if($_REQUEST['id'])
		{
			$result = array($model->get_details($_REQUEST['id']));
			break;
		}
		$result = $model->select_filter(
			$_REQUEST['title'], $_REQUEST['body'], 
			$_REQUEST['from_datetime'], $_REQUEST['to_datetime']
		);
		break;
	}
	case "insert":
	{
		$result = $model->insert_post($_REQUEST['title'], $_REQUEST['body']);
		break;
	}
    case "update":
    {
        $result = $model->update_post($_REQUEST['id'], $_REQUEST['title'], $_REQUEST['body']);
        break;
    }
    case "delete":
    {
        $result = $model->delete_post($_REQUEST['id']);
        break;
    }
}

echo json_encode($result);


/**
* SELECT:
* 		if id is set -> match by id
*		else -> match by params ("" do not count)
* 
* INSERT: set new values from params
* 
* UPDATE: by id -> set new values from params ("" do not count)
* 
* DELETE: by id
* 
*/

?>
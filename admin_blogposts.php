<?php

require_once "models/blogposts.php";

$model = new Blogposts();

switch ($request['action'])
{
	case "select":
		if(isset($_REQUEST['id']))
			return $model->get_details($_REQUEST['id']);
		return $model->select_filter(
			$_REQUEST['title'], $_REQUEST['body'], 
			$_REQUEST['from_datetime'], $_REQUEST['to_datetime']
		);
	case "insert":
		return $model->insert_post($_REQUEST['title'], $_REQUEST['body']);
    case "update":
        return $model->update_post($_REQUEST['id'], $_REQUEST['title'], $_REQUEST['body']);
    case "delete":
        return $model->delete_post($_REQUEST['id']);
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
<?php

require_once "dbcon.php";
require_once "ads.php";

function reserve_area($data)
{
	// check if the area is free; reserve it

	$db = new DBConnection();
	$db->dbcon->beginTransaction();

	if (check_if_intersects($db->dbcon, "ads", $data['coords']) > 0)
	{
		$db->dbcon->rollBack();
		//return false;
		return "Area is not free!";
	}

	if (check_if_intersects($db->dbcon, "newads", $data['coords']) > 0)
	{
		// try to free out unpaid newads
		$stmt = $db->dbcon->prepare(
            "DELETE FROM newads 
            WHERE datetime <= NOW() - INTERVAL 1 HOUR");
        $stmt->execute();
        $stmt->closeCursor();

        // check again
		if (check_if_intersects($db->dbcon, "newads", $data['coords']) > 0)
		{
			$db->dbcon->rollBack();
			//return false;
			return "Area is not free!";
		}
	}

	// upload file and set $data['filename']
	$is_uploaded = upload_picture($_FILES['picture'], $data['filename']);
	if ($is_uploaded !== true)
	{
		$db->dbcon->rollBack();
		//return false;
		return $is_uploaded;
	}

	// save in newads table
	$db->insert("newads", 
		array("name", "link", "coords", "filename", "datetime"), 
		array($db->dbcon->quote($data['name']), $db->dbcon->quote($data['link']), 
				"GeomFromText(\"" . $data['coords'] . "\")", 
				$db->dbcon->quote($data['filename']), "NOW()")
	);

    $id = $db->dbcon->lastInsertId();
    $db->dbcon->commit();
    return $id;
}


function upload_picture($file_to_upload, &$filename)
{
	global $NEWADS_IMAGES_DIR;
	$target_file = $NEWADS_IMAGES_DIR . basename($file_to_upload['name']);

	if (file_exists($target_file))
	{
		$filename = pathinfo($file_to_upload['name'], PATHINFO_BASENAME) 
			. "_" . uniqid() 
			. pathinfo($file_to_upload['name'], PATHINFO_EXTENSION);

		$target_file = $NEWADS_IMAGES_DIR . $filename;
	}

	$file_type = pathinfo($target_file, PATHINFO_EXTENSION);
	if ($file_type != "png")
	{
		//return false;
		return "Only PNG files are supported!";
	}

	if ($file_to_upload['size'] >= 500000)
	{
		//return false;
		return "File too big!";
	}

	if(!move_uploaded_file($file_to_upload['tmp_name'], $target_file))
	{
		//return false;
		return "There was an error and the file could not be uploaded!";
	}

	return true;
}


function accept_new_ad($id)
{
	//$ads_model = new Ads("ads");
	//$newads_model = new Ads("newads");
	$db = new DBConnection();
	$db->dbcon->beginTransaction();

	// get ad from newads table
	//$ad = $newads_model->get_details($id);
	$ad = $db->select("newads", 
		array("name", "link", "astext(coords) as coords", "filename"), $id);

	// filename
	global $NEWADS_IMAGES_DIR, $ADS_IMAGES_DIR;
	$ad_full_filename = $NEWADS_IMAGES_DIR . $ad['filename'];
	$target_file = $ADS_IMAGES_DIR . $ad['filename'];

	if (file_exists($target_file))
	{
		$ad['filename'] = pathinfo($ad['filename'], PATHINFO_BASENAME) 
			. "_" . uniqid() 
			. pathinfo($ad['filename'], PATHINFO_EXTENSION);

		$target_file = $ADS_IMAGES_DIR . $ad['filename'];
	}

	rename($ad_full_filename, $target_file);

	//$is_accepted = $ads_model->insert_ad(
	//	$ad['name'], $ad['link'], $ad['coords'], $ad['filename']);
	$is_accepted = $db->insert("ads", 
		array("name", "link", "coords", "filename", "datetime"), 
		array($db->dbcon->quote($ad['name']), $db->dbcon->quote($ad['link']), 
				"GeomFromText(\"" . $ad['coords'] . "\")", 
				$db->dbcon->quote($ad['filename']), "NOW()")
	);

	//$newads_model->delete_ad($id);
	$db->delete("newads", $id);
	
	$db->dbcon->commit();
	return $is_accepted;
}


?>
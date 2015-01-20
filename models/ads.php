<?php

require_once "config.php";
require_once "dbcon.php";


class Ads extends DBConnection
{
	protected $tablename;
	protected $images_dir;

	public function __construct($tablename)
	{
		parent::__construct();
		$this->tablename = $tablename;

		global $ADS_IMAGES_DIR, $NEWADS_IMAGES_DIR;
		$this->images_dir = ($tablename == "ads" ? $ADS_IMAGES_DIR : $NEWADS_IMAGES_DIR);
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function get_basic_info()
	{
		return parent::select_all($this->tablename, 
			array("id", "link", "astext(coords) as coords"));
	}

	public function get_details($id)
	{
		return parent::select($this->tablename, 
			array("id", "name", "link", "filename", "astext(coords) as coords", "datetime"), $id);
	}

	public function delete_ad($id)
	{
		return parent::delete($this->tablename, $id);
	}

	public function get_all_images_info()
	{
		$ads = parent::select_all($this->tablename, 
			array("id", "filename", "astext(coords) as coords"));

		foreach ($ads as &$ad)
		{
			$rectangle = text_polygon_to_array($ad['coords']);
			$ad['x0'] = $rectangle[0];
			$ad['y0'] = $rectangle[1];
			$ad['x1'] = $rectangle[4];
			$ad['y1'] = $rectangle[5];
			$ad['width'] = $ad['x1'] - $ad['x0'];
			$ad['height'] = $ad['y1'] - $ad['y0'];

			$ad['filename'] = $this->images_dir . $ad['filename'];
		}

		return $ads;
	}

	public function select_datetime_latest($interval)
	{
		$stmt = $this->dbcon->prepare(
            "SELECT id, astext(coords) as coords 
            FROM " . $this->tablename . " "
            . "WHERE datetime >= NOW() - INTERVAL " . $interval . " HOUR");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
	}

	public function insert_ad($name, $link, $coords, $filename)
	{
		return parent::insert($this->tablename, 
			array("name", "link", "coords", "filename", "datetime"), 
			array($this->dbcon->quote($name), $this->dbcon->quote($link), 
				"GeomFromText(\"" . $coords . "\")", 
				$this->dbcon->quote($filename), "NOW()"));
	}
}


function text_polygon_to_array($poly)
{
	$poly = substr($poly,
				strlen("POLYGON(("), -strlen("))"));
	$poly = str_replace(" ", ",", $poly);
	$poly = explode(",", $poly);
	return $poly;
}


?>
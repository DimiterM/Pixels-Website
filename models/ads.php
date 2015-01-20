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

	public function select_filter($name, $link, $coords, $from_datetime, $to_datetime)
	{
		$stmt = $this->dbcon->prepare(
            "SELECT id, name, link, filename, astext(coords) as coords, datetime 
            FROM " . $this->tablename . " 
            WHERE 
            name LIKE ? 
            AND link LIKE ? 
            AND datetime >= ? 
            AND datetime <= ?"
            . ($coords ? " AND st_intersects(coords, GeomFromText(?))" : "")
        );
        $stmt->bindValue(1, "%" . $name . "%");
		$stmt->bindValue(2, "%" . $link . "%");
		$stmt->bindValue(3, $from_datetime ? $from_datetime : "\"2015-01-01 00:00:00\"");
		$stmt->bindValue(4, $to_datetime ? $to_datetime : "NOW()");
		if ($coords)
		{
			$stmt->bindValue(5, $coords);
		}
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
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
		$this->dbcon->beginTransaction();

		if($coords && check_if_intersects($this->dbcon, $this->tablename, $coords) == 0)
		{
        	$result = parent::insert($this->tablename, 
			array("name", "link", "coords", "filename", "datetime"), 
			array($this->dbcon->quote($name), $this->dbcon->quote($link), 
				"GeomFromText(\"" . $coords . "\")", 
				$this->dbcon->quote($filename), "NOW()"));

        	$this->dbcon->commit();
        	return $result;
		}
		return false;
	}

	public function update_ad($name, $link, $coords, $filename, $id)
	{
		$old_record = $this->get_details($id);

		$this->dbcon->beginTransaction();

		if($coords)
		{
			$old_coords = $this->get_details($id)['coords'];
			$check_self_intersect = $this->dbcon->prepare(
				"SELECT st_intersects(
							GeomFromText(:old), 
							GeomFromText(:new))");
			$check_self_intersect->bindValue(':old', $old_coords);
			$check_self_intersect->bindValue(':new', $coords);
			$check_self_intersect->execute();
			$does_intersect_self = ($check_self_intersect->fetch(PDO::FETCH_NUM)[0] > 0);

			$is_intersecting_others = 
				check_if_intersects($this->dbcon, $this->tablename, $coords) 
					- 1 * $does_intersect_self;

			$not_is_intersect = $is_intersecting_others == 0;
		}
		else
		{
			$not_is_intersect = false;
		}

		$stmt = $this->dbcon->prepare(
            "UPDATE ". $this->tablename . " "
            . "SET "
            . "name = :name, link = :link, 
            filename = :filename,
            datetime = NOW()"
            . ($not_is_intersect ? ", coords = GeomFromText(:coords)" : "")
            . " WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', ($name != "" ? $this->dbcon->quote($name) : $old_record['name']));
        $stmt->bindValue(':link', ($link != "" ? $this->dbcon->quote($link) : $old_record['link']));
        $stmt->bindValue(':filename', ($filename != "" ? $this->dbcon->quote($filename) : $old_record['filename']));
        if($not_is_intersect)
        {
        	$stmt->bindValue(':coords', $coords);
        }
        $result = $stmt->execute();

        $this->dbcon->commit();
        $stmt->closeCursor();
        return $result;
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


function check_if_intersects(&$dbcon, $tablename, $polygon)
{
	$stmt = $dbcon->prepare(
		"SELECT count(1) 
        FROM " . $tablename . " "
        . "WHERE st_intersects(coords, GeomFromText(\"" . $polygon . "\"))");
    $stmt->execute();

	$num_rows = $stmt->fetch(PDO::FETCH_NUM)[0];
	$stmt->closeCursor();
	return $num_rows;
}


?>
<?php

require_once "config.php";
require_once "dbcon.php";


class Blogposts extends DBConnection
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function get_all($page, $count = 10)
	{
		$stmt = $this->dbcon->prepare(
            "SELECT id, title, left(body, 60) as body, datetime 
            FROM blogposts 
            ORDER BY datetime DESC
            LIMIT ? OFFSET ?");
		$stmt->bindValue(1, $count, PDO::PARAM_INT);
		$stmt->bindValue(2, $page * $count, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
	}

	public function select_filter($title, $body, $from_datetime, $to_datetime)
	{
		$stmt = $this->dbcon->prepare(
            "SELECT id, title, body, datetime 
            FROM blogposts 
            WHERE 
            title LIKE ? 
            AND body LIKE ? 
            AND datetime >= ? 
            AND datetime <= ?"
        );
        $stmt->bindValue(1, "%" . $title . "%");
		$stmt->bindValue(2, "%" . $body . "%");
		$stmt->bindValue(3, $from_datetime ? $from_datetime : "\"2015-01-01 00:00:00\"");
		$stmt->bindValue(4, $to_datetime ? $to_datetime : "NOW()");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
	}

	public function get_details($id)
	{
		return parent::select("blogposts", 
			array("id", "title", "body", "datetime"), $id);
	}

	public function delete_post($id)
	{
		return parent::delete("blogposts", $id);
	}

	public function insert_post($title, $body)
	{
		return parent::insert("blogposts", 
			array("title", "body", "datetime"), 
			array($this->dbcon->quote($title), $this->dbcon->quote($body), "NOW()"));
	}

	public function update_post($id, $title, $body)
	{
		$old_record = $this->get_details($id);

		$stmt = $this->dbcon->prepare(
            "UPDATE blogposts "
            . "SET "
            . "title = :title, body = :body, datetime = NOW()"
            . "WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':title', ($title != "" ? $title : $old_record['title']));
        $stmt->bindValue(':body', ($body != "" ? $body : $old_record['body']));
        $result = $stmt->execute();

        $stmt->closeCursor();
        return $result;
	}

	public function count()
	{
		$stmt = $this->dbcon->prepare(
            "SELECT COUNT(1) FROM blogposts");
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        return $row[0];
	}
}

?>
<?php

require_once "config.php";

/**
* Connect to DB; parent for Models
*/
class DBConnection
{
	public $dbcon;

	public function __construct()
	{
		try
        {
            global $HOST, $PORT, $DB, $USER, $PASS;
            $this->dbcon = new PDO("mysql:host=" . $HOST . ";port=" . $PORT . ";dbname=" . $DB, $USER, $PASS);
            $this->dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
	}

	public function __destruct()
    {
        $this->dbcon = null;
    }

    public function select_all($tablename, $fields)
    {
        $fields = implode(", ", $fields);

        $stmt = $this->dbcon->prepare(
            "SELECT ". $fields . " FROM " . $tablename);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
    }

    public function select($tablename, $fields, $id)
    {
        $fields = implode(", ", $fields);

        $stmt = $this->dbcon->prepare(
            "SELECT ". $fields . " FROM " . $tablename . " "
            . "WHERE id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $row;
    }

    public function delete($tablename, $id)
    {
        $stmt = $this->dbcon->prepare(
            "DELETE FROM " . $tablename . " "
            . "WHERE id = ?");
        $stmt->bindValue(1, $id);
        $result = $stmt->execute();

        $stmt->closeCursor();
        return $result;
    }

    public function insert($tablename, $keys, $values)
    {
        $keys = implode(", ", $keys);
        $values = implode(", ", $values);

        $stmt = $this->dbcon->prepare(
            "INSERT INTO " . $tablename . " " 
            . "(" . $keys . ") VALUES "
            . "(" . $values . ")");
        $result = $stmt->execute();

        $stmt->closeCursor();
        return $result;
    }
}

?>
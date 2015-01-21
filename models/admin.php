<?php


require_once "dbcon.php";

class Admin extends DBConnection
{
	const SALT_SIZE_BYTES = 32;

	public function login($username, $password)
	{
		$admin_data = $this->select("users", 
			array("id", "username", "password", "salt"), 0
		);

		$password = Admin::hash_password($password, $admin_data['salt']);
		if ($username == $admin_data['username'] && $password == $admin_data['password'])
		{
			return $admin_data['username'];
		}
		return false;
	}

	public function logout()
	{
		// TODO
	}

	public function change_password($old, $new)
	{
		// check if passwords match
		$old_record = $this->select("users", array("password", "salt"), 0);
		$old = Admin::hash_password($old, $old_record['salt']);
		if ($old_record['password'] != $old)
		{
			return false;
		}

		// update record
		$salt = Admin::generate_salt();
		$password = Admin::hash_password($new, $salt);
		$stmt = $this->dbcon->prepare(
            "UPDATE users 
            SET 
            salt = :salt, password = :password 
            WHERE id = 0");
		$stmt->bindValue(':salt', $salt);
		$stmt->bindValue(':password', $password);
		$result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
	}

	public static function hash_password($password, $salt)
	{
		return hash('sha256', $salt . $password);
	}

	public static function generate_salt()
	{
		$bytes = openssl_random_pseudo_bytes(Admin::SALT_SIZE_BYTES);
		return bin2hex($bytes);
	}
}


?>
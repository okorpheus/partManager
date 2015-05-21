<?php
class UserFactory {
	
	/**
	 * 
	 * @param unknown $field DB field to search
	 * @param unknown $value value to search for in the given field
	 * @return NULL|object object if user is found, NULL if none found
	 */
	private static function findOne($field, $value) {
		
		// Verify that such a user exists
		$sql = "SELECT COUNT(*) from users where $field = ?";
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute(array($value));
		$checkResult = $stmt->fetchColumn();
		$stmt->closeCursor();
		if ($checkResult < 1) return NULL;
		
		// Pull such a user from the database
		$sql = "SELECT id, username, passHash, email, isAdmin, realName FROM users where $field = ?";
		$stmt = Database::$connection->prepare($sql);
		$stmt->setFetchMode( PDO::FETCH_CLASS, 'User');
		$stmt->execute(array($value));
		$returnRow = $stmt->fetch(PDO::FETCH_CLASS);
		$stmt->closeCursor();
		return $returnRow;		
	}
	
	/**
	 * 
	 * @param string $username
	 * @return NULL|object object if username found, NULL if not
	 */
	public static function findByUsername($username) {	
		return self::findOne('username',$username);
	}
	
	/**
	 *
	 * @param string $checkID ID to search for
	 * @return NULL|object object if id found, NULL if not
	 */
	public static function findByID($checkID) {
		if($checkID == 'new') return new User;
		return self::findOne('id',$checkID);
	}


	/**
	 * Returns an array of all users as objects
	 * @return array:objects 
	 */
	public static function getAllUsers() {
		$users = array();
		$sql = "SELECT id FROM users";
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute();
		$userIDs = $stmt->fetchAll(PDO::FETCH_COLUMN,0);
		foreach ($userIDs as $id) {
			$users[] = self::findByID($id);
		}
		return $users;
	}
}
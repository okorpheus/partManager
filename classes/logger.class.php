<?php
class Logger {
	public static $log_table = 'log';
	
	/**
	 * Adds an entry to the log
	 * 
	 * @param string $entry Text of the entry
	 * @param integer $partAffected If the entry effects a part, enter it's id
	 * @param integer $userAffected If the entry effects a user, enter it's id
	 */
	public static function makeEntry($entry, $partAffected = NULL,$userAffected = NULL) {
		global $currentUser;
		if (isset($currentUser)) $currentUserID = $currentUser->getID();
		else $currentUserID = null;
		$sql = 'INSERT INTO ' . self::$log_table . ' (user, partAffected, userAffected, entry) 
				VALUES (:user, :partAffected, :userAffected, :entry)';
		$stmt = Database::$connection->prepare($sql);
		$stmt->bindParam(':user', $currentUserID);
		$stmt->bindParam(':partAffected', $partAffected);
		$stmt->bindParam(':userAffected', $userAffected);
		$stmt->bindParam(':entry', $entry);
		$stmt->execute();
		$stmt->closeCursor();
	}
	
	public static function getSingleEntry($id) {
		$sql = "SELECT id, user, partAffected, userAffected, timestamp, entry FROM " . self::$log_table . " WHERE id = :id";
		$stmt = Database::$connection->prepare($sql);
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		$return = $stmt->fetchObject('loggerentry');
		$stmt->closeCursor();
		return $return;
	}
	
	public static function getAllEntries() {
		$sql = "SELECT id,user, partAffected, userAffected, timestamp, entry FROM " . self::$log_table;
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute();
		$return = $stmt->fetchAll(PDO::FETCH_CLASS, "loggerentry");
		$stmt->closeCursor();
		return $return;
	}
}
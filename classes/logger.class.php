<?php
class Logger {
	public static $log_table = 'log';
	
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
}
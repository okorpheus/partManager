<?php
class Database {
	public static $connection;
	
	public static function connect() {
		$dbDSN = 'mysql:host=' . PM_DB_SERVER . ';dbname=' . PM_DB_NAME;
		try {
			self::$connection = new PDO($dbDSN,PM_DB_USERNAME,PM_DB_PASSWORD);
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br>";
		}
	}
}
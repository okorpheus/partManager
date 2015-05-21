<?php
class PartFactory {
	
	/**
	 * 
	 * @return array: returns an array of filenaems in the parts directory
	 */
	public static function directoryFileList() {
		$files = array_diff(scandir(PM_PARTS_DIRECTORY), array('..','.','.DS_Store'));
		return $files;
	}
	
	/**
	 * 
	 * @return string Returns the number of entries in the parts database
	 */
	public static function partCount() {
		$sql = "SELECT COUNT(*) from parts";
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute();
		$partCount = $stmt->fetchColumn();
		$stmt->closeCursor();
		return $partCount;
	}
	
	/**
	 * 
	 * Check to see if a DB entry exists for the given filename
	 * 
	 * @param string $filename
	 * @return boolean
	 */
	public static function isFileInDatabase($filename) {
		global $config;
		$sql = "SELECT COUNT(*) from parts where fileName = ?";
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute(array($filename));
		$checkResult = $stmt->fetchColumn();
		$stmt->closeCursor();
		if ($checkResult < 1) return FALSE;
		else return TRUE;
	}
	
	/**
	 * Finds files in the parts directory that do not have an entry
	 * in the parts database and adds an entry for them
	 */
	public static function addMissingDbEntries() {
		global $config;
		$fileList = self::directoryFileList();
		$addList = array();
		foreach ($fileList as $checkFile) {
			if(!self::isFileInDatabase($checkFile)) $addList[] = $checkFile;
		}
		
		$sql = "INSERT INTO parts (fileName) VALUES(?)";
		$stmt = Database::$connection->prepare($sql);
		foreach ($addList as $addFile) {
			$stmt->execute(array($addFile));
			UserMessageQueue::addMessage('success', 'Added ' . $addFile . ' to the database');
		}
		$stmt->closeCursor();
		return count($addList);
	}
	
	/**
	 * 
	 * Returns a listing of filenames found in the database
	 * that do not have a corresponding file in the parts directory
	 * @return array
	 */
	public static function listMissingFiles() {
		global $config;
		$sql = "SELECT fileName from parts";
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute();
		$databaseRows = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		$stmt->closeCursor();
		
		$fileList = self::directoryFileList();
		$missingFiles = array();
		
		foreach ($databaseRows as $row) {
			if (!in_array($row,$fileList)) $missingFiles[] = $row;
		}
		return $missingFiles;
	}
	
	/**
	 * Removes database entries that contain a filename which
	 * does not exist in the parts directoryr
	 */
	public static function removeMissingFilesFromDB() {
		$missingFiles = self::listMissingFiles();
		$sql = "DELETE from parts WHERE fileName = ?";
		$stmt = Database::$connection->prepare($sql);
		foreach ($missingFiles as $missingFile) {
			$stmt->execute(array($missingFile));
			UserMessageQueue::addMessage('warning', 'Removed ' . $missingFile . ' from the database.');
		}
		$stmt->closeCursor();
		return count($missingFiles);
	}
	
	/**
	 * Returns an array of parts from the DB.
	 * 
	 * @param array $whileArray
	 * OPTIONAL An array of paramaters for the search query in the format of
	 * columnName=>searchValue
	 * 
	 * @return array
	 */
	public static function getAllParts($whileArray = array()) {
		$sql = "SELECT id, songTitle, partName, fileName, dateAdded from parts";
		if (count($whileArray) > 0) {
			$multipleWhere = FALSE;
			$whereClause = " WHERE ";
			foreach($whileArray as $column => $value) {
				if ($multipleWhere) $whereClause .= " AND ";
				$whereClause .= "$column='$value'";
				$multipleWhere = TRUE;
			}
			$sql .= $whereClause;
		}
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute();
		$parts = $stmt->fetchAll(PDO::FETCH_CLASS,"part");
		$stmt->closeCursor();
		return $parts;
	}
	
	/**
	 * Find a part from the database by ID number
	 * 
	 * @param int $id ID to search for in the database
	 * @return object if the ID is found in the database. FALSE if not
	 */
	public static function getByID($id) {
		$whereArray = array();
		$whereArray['id'] = $id;
		$parts = self::getAllParts($whereArray);
		if (count($parts) == 1) return $parts[0];
		return FALSE;
	}
	
	/**
	 * Return an array of unique titles in the parts database or
	 * a string of option fields for use in a select
	 * 
	 * @param boolean $option FALSE to return an array of titles, TRUE
	 * to return a string of option fields
	 * 
	 * @return string|array: String of option fields or array of titles
	 * 
	 */
	public static function getExistingTitles($option=FALSE) {
		$sql = "SELECT DISTINCT songTitle from parts ORDER BY songTitle";
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute();
		$titles = $stmt->fetchAll(PDO::FETCH_COLUMN,0);
		if ($option) {
			$titleOptions = "";
			foreach ($titles as $title) {
				$titleOptions .= "<option value='$title'>$title</option>";
			}
			return $titleOptions;
		}
		return $titles;
	}
	
	/**
	 * Return an array of unique part names in the parts database or
	 * a string of option fields for use in a select
	 *
	 * @param boolean $option FALSE to return an array of part names, TRUE
	 * to return a string of option fields
	 *
	 * @return string|array: String of option fields or array of titles
	 *
	 */
	public static function getExistingPartNames($option=FALSE) {
		$sql = "SELECT DISTINCT partName from parts ORDER BY partName";
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute();
		$partNames = $stmt->fetchAll(PDO::FETCH_COLUMN,0);
		if ($option) {
			$partNameOptions = "";
			foreach ($partNames as $partName) {
				$partNameOptions .= "<option value='$partName'>$partName</option>";
			}
			return $partNameOptions;
		}
		return $partNames;
	}
	
	/**
	 * 
	 * @param string $selected=NULL part name that should be SELECTED
	 * @return string group of option fields for a form select
	 */
	public static function partOptions($selected = NULL) {
		$sql = "SELECT partName from partNames";
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_COLUMN,0);
		$optionString = "";
		foreach ($results as $partName) {
			$optionString .= '<option value="' . $partName . '"';
			if ($selected == $partName) $optionString .= " SELECTED ";
			$optionString .= '>' . $partName . '</option>';
		}
		return $optionString;
	}
}
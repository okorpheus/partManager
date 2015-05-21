<?php
class Part {
	private $id;
	private $songTitle;
	private $partName;
	private $fileName;
	private $dateAdded;
	
	/**
	 * 
	 * @return string HTML formated table row for the part.
	 */
	public function getFullRow() {
		global $currentUser;
		$link = $this->getLink();
		$returnString = <<< RETURNSTRING
		<tr>
			<td><input type='checkbox' name='multipleParts[$this->id]'></td>
			<td>$this->id</td>
			<td><a href='$link' target='_new'><span class='glyphicon glyphicon-print'></span></a></td>
RETURNSTRING;
			if($currentUser->isAdmin()) {
				$returnString .= "<td><a href='";
				$returnString .= $this->editLink();
				$returnString .= "'><span class='glyphicon glyphicon-pencil'></span></a></td>";
			}
			else $returnString .= "<td></td>";
			$returnString .= <<< RETURNSTRING
			<td>$this->songTitle</td>
			<td>$this->partName</td>
			<td>$this->fileName</td>
			<td>$this->dateAdded</td>
		</tr>
RETURNSTRING;
		return $returnString;
	}
	
	/**
	 * 
	 * @return string Link to download the file
	 */
	public function getLink() {
		$fileLink = 'download.php?dlid='.$this->id;
		return($fileLink);
	}
	
	/**
	 * 
	 * @return string link to edit page
	 */
	public function editLink() {
		$link = "editPart.php?id=" . $this->id;
		return $link;
	}

	public function getSongTitle() {
		return $this->songTitle;
	}
	
	public function setSongTitle($title) {
		$this->songTitle = $title;
	}
	
	public function getPartName() {
		return $this->partName;
	}
	
	public function setPartName($partName) {
		$this->partName = $partName;
	}
	
	public function getFileName() {
		return $this->fileName;
	}
	
	public function getID() {
		return $this->id;
	}

	public function writeToDB() {
		if($this->id != 'new') {
			$sql = "UPDATE parts SET 
					songTitle=:title, 
					partName=:partName, 
					fileName=:fileName, 
					dateAdded=:date 
					WHERE id=:id";
		}
		else {
			$sql = "INSERT INTO parts (
					songTitle,partName,fileName) VALUES (
					:title,:partName,:fileName);";
		}
		
		$stmt = Database::$connection->prepare($sql);
		$stmt->bindParam(':title', $this->songTitle);
		$stmt->bindParam(':partName', $this->partName);
		$stmt->bindParam(':fileName', $this->fileName);
		if($this->id != 'new') $stmt->bindParam(':date', $this->dateAdded);
		if($this->id != 'new') $stmt->bindParam(':id', $this->id);
		$stmt->execute();
		$stmt->closeCursor();
	}

	public function sendFile() {
		$directory = PM_PARTS_DIRECTORY;
		$file = $directory . '/' . $this->fileName;
		if(file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/pdf');
			#header('Content-Disposition: attachment; filename='.basename($file));
			header('Content-Disposition: inline; filename='.basename($file));
			readfile($file);
			return TRUE;
		}
		UserMessageQueue::addMessage('danger', 'Missing File');
	}
}
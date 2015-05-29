<?php
class loggerEntry {
	private $id;
	private $user;
	private $partAffected;
	private $userAffected;
	private $timestamp;
	private $entry;
	private $userObject;
	private $userAffectedObject;
	private $partAffectedObject;
	
	/**
	 * Returns an HTML formated row containing the log entry data
	 * @return string
	 */
	public function tableRow() {
		$this->getPartObject();
		$this->getUserObject();
		$content = "\r\n";
		$content .= "<tr>\r\n";
		$content .= "<td>$this->timestamp</td>\r\n";
		if (is_object($this->userObject)) {
			$content .= "<td>";
			$content .= $this->userObject->getUsername();
			$content .= "</td>\r\n";
		}
		else $content .= "<td>&nbsp;</td>";
		
		if (is_object($this->partAffectedObject)) {
			$content .= "<td>";
			$content .= $this->partAffectedObject->getFilename();
			$content .= "</td>\r\n";
		}
		else $content .= "<td>&nbsp;</td>";		
		if (is_object($this->userAffectedObject)) {
			$content .= "<td>";
			$content .= $this->userAffectedObject->getUsername();
			$content .= "</td>\r\n";
		}
		else $content .= "<td>&nbsp;</td>";
		$content .= "<td>$this->entry</td>\r\n";
		$content .= "</tr>\r\n";
		return $content;	
	}
	
	private function getUserObject($forceReload = FALSE) {
		if (!is_object($this->userObject) OR $forceReload) {
			$this->userObject = UserFactory::findByID($this->user);
		}
		if (!is_object($this->userAffectedObject) OR $forceReload) {
			$this->userAffectedObject = UserFactory::findByID($this->userAffected);
		}
	}
	
	private function getPartObject($forceReload = FALSE) {
		if (!is_object($this->partAffectedObject) OR $forceReload) {
			$this->partAffectedObject = PartFactory::getByID($this->partAffected);
		}
	}
}
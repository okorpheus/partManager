<?php
class User {
	private $id = "new";
	private $username;
	private $passHash;
	private $email;
	private $isAdmin;
	private $realName;
	
	/**
	 * 
	 * @param unknown $checkPassword Password to be checked against
	 * the correct password
	 * 
	 * @return boolean
	 */
	public function verifyPassword($checkPassword) {
		return password_verify($checkPassword, $this->passHash);
	}
	
	/**
	 * Change this users password
	 * 
	 * @param string $newPass
	 * @param string $confirmNewPass
	 */
	public function changePassword($newPass, $confirmNewPass) {
		if ($newPass != $confirmNewPass) {
			UserMessageQueue::addMessage('danger', 'Submitted Passwords Do Not Match');
			redirect('editUser.php?id=' . $this->id);
		}
		$this->passHash = password_hash($newPass, PASSWORD_DEFAULT);
		Logger::makeEntry('Changed password for user', null, $this->id);

	}
	
	public function setUsername($username) {
		if ($username == $this->username) return; // No need to change
		
		// Check to be sure that new username is not in use
		$sql = "SELECT count(*) from users WHERE username = :newUsername";
		$stmt = Database::$connection->prepare($sql);
		$stmt->bindParam(':newUsername', $username);
		$stmt->execute();
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		if ($count > 0) {
			UserMessageQueue::addMessage('danger', 'Username "' . $username . '" is in use.');
			redirect('editUser.php?id=' . $this->id);
			exit();
		}
		$this->username = htmlspecialchars($username);
		
	}
	
	public function setRealName($realname) {
		$this->realName = htmlspecialchars($realname);
		
	}
	
	public function setEmail($email) {
		$this->email = htmlspecialchars($email);
		
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function getRealName() {
		return $this->realName;
	}
	
	public function getIsAdmin() {
		return $this->isAdmin;
	}
	
	public function grantAdmin() {
		$this->isAdmin = 1;
		Logger::makeEntry('Granted admin access for user', null, $this->id);
	}
	
	public function revokeAdmin() {
		$this->isAdmin = 0;
		Logger::makeEntry('Revoked admin access for user', null, $this->id);
		
	}
	/**
	 * 
	 * Does this user have admin privileges
	 * @return boolean
	 */
	public function isAdmin() {
		if ($this->isAdmin == 1) return TRUE;
		else return FALSE;
	}
	
	/**
	 * 
	 * @return string YES or NO
	 */
	public function adminStatusString() {
		if ($this->isAdmin == 1) return "YES";
		return "NO";
	}
	
	/**
	 * Return a table row for the user
	 * @return string HTML formated table row
	 */
	public function getFullRow() {
		$return  = "<tr>";
		$return .= '<td>' . $this->id . '</td>';
		$return .= '<td>';
			$return .= '<a href="' . $this->editLink() . '">';
				$return .= '<span class="glyphicon glyphicon-pencil"></span>';
			$return .= '</a>';
			$return .= '&nbsp;&nbsp;';
			$return .= $this->deleteLink(TRUE);
				$return .= '<span class="glyphicon glyphicon-trash"></span>';
			$return .= '</a>';
		$return .= '</td>';
		$return .= '<td>' . $this->username . '</td>';
		$return .= '<td>' . $this->realName . '</td>';
		$return .= '<td>' . $this->email . '</td>';
		$return .= '<td>' . $this->adminStatusString() . '</td>';
		$return .= '</tr>';
		return $return;
	}
	
	/**
	 * Get the link to edit the user
	 * @return string link to edit user
	 */
	public function editLink() {
		$link = 'editUser.php?id=' . $this->id;
		return $link;
	}
	
	public function deleteLink($withOpeningA = FALSE) {
		$link = 'editUser.php?delete=TRUE&id=' . $this->id;
		if ($withOpeningA) {
			$tag = '<a href="' . $link . '" ';
			$tag .= 'onclick="return confirm(';
			$tag .= "'Are you sure you want to delete the user " . $this->username . "'";
			$tag .= ')">';
			return $tag;
		}
		return $link;
	}
	
	public function delete() {
		$sql = "DELETE from users where id = $this->id";
		$stmt = Database::$connection->prepare($sql);
		$stmt->execute();
		$stmt->closeCursor();
		Logger::makeEntry('Changed password for user ' . $this->username, null, $this->id);
	}
	
	public function writeToDB() {
		if($this->id != 'new') {
			$sql = "UPDATE users SET
					username=:username,
					passHash=:passHash,
					email=:email,
					isAdmin=:isAdmin, 
					realName=:realName 
					WHERE id=:id";
		}
		else {
			$sql = "INSERT INTO users (
					username,passHash,email,isAdmin,realName) VALUES (
					:username,:passHash,:email,:isAdmin,:realName);";
		}
		$stmt = Database::$connection->prepare($sql);
		$stmt->bindParam(':username', $this->username);
		$stmt->bindParam(':passHash', $this->passHash);
		$stmt->bindParam(':email', $this->email);
		$stmt->bindParam(':isAdmin', $this->isAdmin);
		$stmt->bindParam(':realName', $this->realName);
		if($this->id != 'new') $stmt->bindParam(':id', $this->id);
		$stmt->execute();
		if($this->id != 'new') {
			Logger::makeEntry('Updated user', null, $this->id);
		}
		else Logger::makeEntry('Created user', null, Database::$connection->lastInsertId());
		$this->id = Database::$connection->lastInsertId();
		$stmt->closeCursor();
	}
}
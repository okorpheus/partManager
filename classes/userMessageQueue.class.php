<?php
class UserMessageQueue {
	/**
	 * 
	 * @var array Possible message classes
	 */
	private static $messageClasses = array('danger','warning','info','success');
	
	/**
	 * Make sure that $_SESSION is set up
	 */
	private static function checkSessionAarray() {
		if(!isset($_SESSION['userMessageQueue'])) {
			$_SESSION['userMessageQueue'] = NULL;
		}
		
		if(!is_array($_SESSION['userMessageQueue'])) {
			$_SESSION['userMessageQueue']['danger'] = array();
			$_SESSION['userMessageQueue']['warning'] = array();
			$_SESSION['userMessageQueue']['info'] = array();
			$_SESSION['userMessageQueue']['success'] = array();
		}
	}
	
	/**
	 * Add a message to the queue
	 * @param string $messageClass danger|warning|info|success
	 * @param string $message
	 * @throws Exception if unknown message class is specified
	 */
	public static function addMessage($messageClass, $message) {
		self::checkSessionAarray();
		if (!in_array($messageClass, self::$messageClasses)) {
			throw new Exception("No such message class available");
		}
		$_SESSION['userMessageQueue'][$messageClass][] = $message;
	}
	
	/**
	 * echoes divs for all messages in the queue of a given
	 * message class
	 * @param string $classToPrint all|danger|warning|info|success|all
	 * default is all
	 */
	public static function printMessages($classToPrint = "all") {
		self::checkSessionAarray();
		$messageQueue =& $_SESSION['userMessageQueue'];
		if ($classToPrint == "all") {
			self::printMessages("danger");
			self::printMessages("warning");
			self::printMessages("info");
			self::printMessages("success");
			return;
		}
		if (!array_key_exists($classToPrint, $messageQueue)) {
			return;
		}
		foreach ($messageQueue[$classToPrint] as $currentMessage) {
			echo '<div class="alert alert-' . $classToPrint . '">';
				echo '<a href="#" class="close" data-dismiss="alert">&times;</a>';
				echo $currentMessage;
			echo '</div>';
		}
		unset($messageQueue[$classToPrint]);
	} 
}
<?php
class View {
	private static $title = "Default Title";
	private static $pageContent = NULL;
		
	private static function outputHeader() {
		global $currentUser;
		include('header.view.php'); 
	}
	
	public static function getTitle() {
		return self::$title;
	}
	
	private static function outputUserMessages() {
		UserMessageQueue::printMessages('all');
	}
	
	private static function outputFooter() {
		echo "</div>";
		?>
		</body>
		</html>
		<?php
	}
	
	/**
	 * Set title for the HTML page output
	 * @param unknown $title
	 */
	public static function setTitle($title) {
		self::$title = $title;
	}
	
	/**
	 * Add content to the output page
	 * @param unknown $content
	 */
	public static function addContent($content) {
		self::$pageContent .= $content;
	}
	
	/**
	 * Send page to the user
	 */
	public static function sendPage() {
		self::outputHeader();
		self::outputUserMessages();
		echo self::$pageContent;
		self::outputFooter();
	}
}
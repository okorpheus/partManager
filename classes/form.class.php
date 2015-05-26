<?php
/**
 * Creates a form using bootstrap css
 * 
 * This class can be used to set up a form using bootstrap css files. In order to behave
 * as expected, boostrap is ncessary.
 * 
 * @author Matt Young <matt@mattyoung.us>
 * @copyright 2015
 */
class Form {
	
	private $items = array();
	private $colsWide;
	private $formName = "no name";
	private $includeUploads;
	
	/**
	 * Create a new Form object
	 * 
	 * @param number $width Number of bootstrap columns to user. Must be an even number less than 12
	 * @param string $name Name for the form
	 * @param bool $uploads Does the form accept file uploads
	 */
	public function __construct($width=4,$name="",$uploads=FALSE) {
		$this->setName($name);
		$this->setWidth($width);
		$this->includeUploads = $uploads;
	}
	
	// Functions to set up the form
	/**
	 * Set field name
	 * 
	 * @param string $name The field name
	 */
	public function setName($name) {
		$this->formName = $name;
	}
	
	/**
	 * Set form width
	 * 
	 * Sets the number of bootstrap columns that will be taken up by the form. It will use
	 * this number of columns in the center of the page.
	 * 
	 * @param unknown $width Number of bootstrap columns to user. Must be an even number less than 12
	 * @throws Exception if $width is more than 12 or odd
	 */
	public function setWidth($width) {
		if($width > 12 | $width % 2 > 0) {
			throw new Exception("Form width must be an even number less than 12");
		}
		$this->colsWide = $width;
	}
	
	/**
	 * Add a hiden input element
	 * 
	 * @param string $name name for the input element
	 * @param string $value value of the hidden element
	 */
	public function addHidden($name,$value) {
		// <input type='hidden' name='id' value='$id'>
		$content = "\r\n" . '<input type="hidden" name="' . $name . '" value="' . $value . '">';
		$content .= "\r\n";
		$this->items[] = $content;
	}
	
	/**
	 * Add a basic input
	 * 
	 * This function will add a basic input to the form. By default, it creates 
	 * a text field, however, by specifiying a type, it can also create other input
	 * controls that are simple fields. It outputs the html for the input to the objects
	 * items array.
	 * 
	 * @param string $fieldName Used for the name and id of the field
	 * @param string $fieldLabel Label for the field, as well as the placeholder
	 * @param string $defaultValue Default value when the field opens
	 * @param string $type (optional) Defaults to text, can also be password, etc.
	 */
	public function addTextInput($fieldName, $fieldLabel, $defaultValue, $type='text') {
		$content = "";
		$content .=  "\r\n" . '<div class="form-group">' . "\r\n";;
		$content .= '     <label for="' . $fieldName . '">' . $fieldLabel . '</label>' . "\r\n";
		// Begin input element
		$content .= '     <input type="' . $type . '" ';
		$content .= 'name="' . $fieldName . '" ';
		$content .= 'id=' . $fieldName . '" ';
		$content .= 'class="form-control" ';
		$content .= 'placeholder="' . $fieldLabel . '" ';
		if (strlen($defaultValue) > 0) $content .= 'value="' . $defaultValue . '"'; 
		$content .= '>' . "\r\n";
		// End input element
		$content .= '</div>' . "\r\n";
		$this->items[] = $content;
	}
	
	/**
	 * Add a select to the field
	 * 
	 * Adds a select to the field including an array of options
	 * 
	 * @param string $selectName Name and id for the select element
	 * @param string $selectLabel Label to be shown for the select element
	 * @param array $options Array of options as VALUE=>DISPLAY TEXT
	 * @param string $selectedOption (optional) value that is to be selectd by default
	 * 
	 */
	public function addSelect($selectName, $selectLabel, $options, $selectedOption = NULL) {
		if (!is_array($options)) throw new Exception("an array of options must be sent as VALUE=>TEXT");
		$content = "";
		$content .= "\r\n" . '<div class="form-group">' . "\r\n";
		$content .= '<label for="' . $selectName . '">' . $selectLabel . '</label>' . "\r\n";
		$content .= '<select class= "form-control" name="' . $selectName . '" id="' . $selectName . '">' . "\r\n";
		foreach ($options as $optionValue=>$optionText) {
			$content .= '     <option value="' . $optionValue . '"';
			if ($optionValue == $selectedOption) $content .= " SELECTED ";
			$content .= '>' . "\r\n";
			$content .= "          " . $optionText . "\r\n";
			$content .= '     </option>' . "\r\n";
		}
		$content .= '</select>' . "\r\n";
		$content .= '</div>' . "\r\n";
		$this->items[] = $content;
	}
	
	/**
	 * Add static text to the form
	 * 
	 * Adds a piece of static text to a form
	 * @param string $name used as the id for the label and text
	 * @param string $text content of the static field
	 */
	public function addStaticText($name, $label, $text) {
		$content = "";
		$content .= "\r\n" . "<div class='form-group'>" . "\r\n";
		$content .= '     <label for="' . $name  .'">' . $label . '</label>' . "\r\n";
		$content .= '     <p class="form-control" id="' . $name .'">' . $text . '</p>' . "\r\n";
		$content .= '</div>' . "\r\n";
		$this->items[] = $content;
	}
	
	/**
	 * Add a checkbox to the form
	 * 
	 * Adds a checkbox to the form. The checkbox control name is set with the
	 * name paramater. The text beside the box is set with the label paramater.
	 * 
	 * @param string $name name of the checkbox control
	 * @param string $label text beside the checkbox control
	 * @param bool $checked (optional) FALSE by default. Is the box checked.
	 */
	public function addCheckbox($name, $label, $checked=FALSE) {
		$content = "";
		$content .= "\r\n" . "<div class='checkbox'>";
		$content .= '     <label><input type="checkbox" name="' . $name . '"';
		if ($checked) $content .= ' CHECKED ';
		$content .= '>' . $label . '</label>';
		$content .= '</div>' . "\r\n";
		$this->items[] = $content;
	}
	
	
	public function addTextArea($name, $label, $rows = 5, $areaContent = NULL) {
		$content = "";
		$content .= "\r\n" . "<div class='form-group'>";
		$content .= "\r\n" . '<label for="' . $name . '">' . $label . '</label>';
		$content .= "\r\n" . '<textarea class="form-control" rows="' . $rows . '" id="' . $name . '" name="' . $name . '">';
		$content .= $areaContent;
		$content .= '</textarea>';
		$content .= "\r\n" . '</div>';
		$this->items[] = $content;
	}
	
	/**
	 * Generate Button
	 * 
	 * @param string $buttonText Text to be shown inside the button
	 * @param string $type (optional) submit by default. Type of button to create
	 */
	public function addButton($buttonText, $type='submit') {
		//<button type='submit' class='btn btn-primary'>Submit</button>
		$content = '';
		$content .= "\r\n" . '<button type="' . $type . '" ';
		$content .= 'class="btn btn-primary">' . $buttonText . '</button>' . "\r\n";
		$this->items[] = $content;
	}
	
	// Functions to format and output completed form
	
	/**
	 * Form opening
	 * 
	 * Open the form, including padding columns if the width is less than 12
	 * 
	 * @return string HTML for the opening of the form
	 */
	private function formOpening() {
		$content = "\r\n" . "\r\n";
		$content = "\r\n\r\n<!-- ==========BEGIN FORM ($this->formName)========== -->\r\n\r\n";
		if ($this->colsWide < 12) {
			$startPad = (12 - $this->colsWide) / 2;
			$content .= '<div class="row">' . "\r\n";
			$content .= '<div class="col-sm-' . $startPad . ' hidden-xs"></div>' . "\r\n";
			$content .= '<div class="col-sm-' . $this->colsWide . ' col-xs-12">' . "\r\n";
		}
		if ($this->includeUploads == TRUE) {
			$content .= '<form action="" method="post" enctype="multipart/form-data">';
		}
		else $content .= '<form method="post" action="">' . "\r\n";
		return $content;
	}
	
	/**
	 * Form closing
	 * 
	 * Close the form, including padding columns if the width is less than 12
	 * 
	 * @return string HTML for the closing of the form
	 */
	private function formClosing() {
		$content = "";
		if ($this->colsWide < 12) {
			$endPad = (12 - $this->colsWide) / 2;
			$content .= '</div>' . "\r\n";
			$content .= '<div class="col-sm-' . $endPad . 'hidden-xs"></div>' . "\r\n";
			$content .= '</row>' . "\r\n";
		}
		$content .= '</form>' . "\r\n";
		$content .= "\r\n\r\n<!-- ==========END FORM ($this->formName)========== -->\r\n\r\n";
		return $content;
	}
	
	/**
	 * Return the form
	 * 
	 * Returns the HTML for the form including all created elements
	 * 
	 * @return string HTML for the form
	 */
	public function returnForm() {
		$content = "";
		$content .= $this->formOpening();
		foreach ($this->items as $item) {
			$content .= $item;
		}
		$content .= $this->formClosing();
		return $content;
	}
}
<?php
require_once 'Widget.php';

/**
 * Just a plain widget you put HTML in and it kicks it out as is.
 */
class HTML_Widget extends Widget {
	/**
	 * Configuration array.
	 */
	protected $_config = array('html' => null);

	/**
	 * Loads the data into the configuration.
	 *
	 * @param ID The HTML this widget should contain.
	 */
	protected function _load($ID) {
		$this->configure('html', stripslashes($ID));
	}

	/**
	 * Renders the widget.
	 */
	public function render() {

        echo stripslashes($this->configure('html'));
	}
}
?>
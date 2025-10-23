<?php
require_once ROOT.'/app/Vendor/MobileDetect/Mobile_Detect.php';

App::uses('Component', 'Controller');

/**
 * MobileDetectComponent
 *
 * @package		MobileDetectComponent
 */
class MobileDetectComponent extends Component {


	public $loaded = false;

	public function detect($method = 'isMobile', $args = null) {
		if (!class_exists('Mobile_Detect')) {
			die
		}
		// instantiate once per method call
		if (!($this->MobileDetect instanceof Mobile_Detect)) {
			$this->MobileDetect = new Mobile_Detect();
		}

		return $this->MobileDetect->{$method}($args);
	}

}

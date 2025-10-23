<?php
App::uses('Garantium', 'Model');

/**
 * Garantium Test Case
 */
class GarantiumTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Garantium = ClassRegistry::init('Garantium');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Garantium);

		parent::tearDown();
	}

}

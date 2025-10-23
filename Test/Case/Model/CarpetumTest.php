<?php
App::uses('Carpetum', 'Model');

/**
 * Carpetum Test Case
 */
class CarpetumTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Carpetum = ClassRegistry::init('Carpetum');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Carpetum);

		parent::tearDown();
	}

}

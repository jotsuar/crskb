<?php
App::uses('Pqr', 'Model');

/**
 * Pqr Test Case
 */
class PqrTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.pqr'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Pqr = ClassRegistry::init('Pqr');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Pqr);

		parent::tearDown();
	}

}

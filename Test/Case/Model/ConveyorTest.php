<?php
App::uses('Conveyor', 'Model');

/**
 * Conveyor Test Case
 */
class ConveyorTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.conveyor'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Conveyor = ClassRegistry::init('Conveyor');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Conveyor);

		parent::tearDown();
	}

}

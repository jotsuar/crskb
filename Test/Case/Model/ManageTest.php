<?php
App::uses('Manage', 'Model');

/**
 * Manage Test Case
 */
class ManageTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.manage',
		'app.user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Manage = ClassRegistry::init('Manage');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Manage);

		parent::tearDown();
	}

}

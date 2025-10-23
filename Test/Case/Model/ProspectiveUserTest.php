<?php
App::uses('ProspectiveUser', 'Model');

/**
 * ProspectiveUser Test Case
 */
class ProspectiveUserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.prospective_user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProspectiveUser = ClassRegistry::init('ProspectiveUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProspectiveUser);

		parent::tearDown();
	}

}

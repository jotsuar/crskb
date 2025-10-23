<?php
App::uses('ContacsUser', 'Model');

/**
 * ContacsUser Test Case
 */
class ContacsUserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.contacs_user',
		'app.prospective_users'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ContacsUser = ClassRegistry::init('ContacsUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ContacsUser);

		parent::tearDown();
	}

}

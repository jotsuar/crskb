<?php
App::uses('ClientsLegal', 'Model');

/**
 * ClientsLegal Test Case
 */
class ClientsLegalTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.clients_legal'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ClientsLegal = ClassRegistry::init('ClientsLegal');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ClientsLegal);

		parent::tearDown();
	}

}

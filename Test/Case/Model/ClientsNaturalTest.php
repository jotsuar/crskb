<?php
App::uses('ClientsNatural', 'Model');

/**
 * ClientsNatural Test Case
 */
class ClientsNaturalTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.clients_natural',
		'app.prospective_user',
		'app.user',
		'app.manage',
		'app.prospective_users',
		'app.quotation',
		'app.flow_stage',
		'app.quoatatios_product',
		'app.product',
		'app.contacs_user',
		'app.clients_legal'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ClientsNatural = ClassRegistry::init('ClientsNatural');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ClientsNatural);

		parent::tearDown();
	}

}

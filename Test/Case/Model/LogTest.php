<?php
App::uses('Log', 'Model');

/**
 * Log Test Case
 */
class LogTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.log',
		'app.user',
		'app.manage',
		'app.prospective_users',
		'app.quotation',
		'app.flow_stage',
		'app.quotations_product',
		'app.product',
		'app.templates_product',
		'app.template'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Log = ClassRegistry::init('Log');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Log);

		parent::tearDown();
	}

}

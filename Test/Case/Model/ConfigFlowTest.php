<?php
App::uses('ConfigFlow', 'Model');

/**
 * ConfigFlow Test Case
 */
class ConfigFlowTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.config_flow'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ConfigFlow = ClassRegistry::init('ConfigFlow');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ConfigFlow);

		parent::tearDown();
	}

}

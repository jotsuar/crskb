<?php
App::uses('Accessory', 'Model');

/**
 * Accessory Test Case
 */
class AccessoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.accessory',
		'app.product_technicals',
		'app.technical_services'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Accessory = ClassRegistry::init('Accessory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Accessory);

		parent::tearDown();
	}

}

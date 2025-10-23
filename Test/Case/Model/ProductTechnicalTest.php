<?php
App::uses('ProductTechnical', 'Model');

/**
 * ProductTechnical Test Case
 */
class ProductTechnicalTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.product_technical',
		'app.technical_services'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProductTechnical = ClassRegistry::init('ProductTechnical');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProductTechnical);

		parent::tearDown();
	}

}

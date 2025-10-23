<?php
App::uses('TemplatesProduct', 'Model');

/**
 * TemplatesProduct Test Case
 */
class TemplatesProductTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.templates_product',
		'app.template',
		'app.prouct'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TemplatesProduct = ClassRegistry::init('TemplatesProduct');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TemplatesProduct);

		parent::tearDown();
	}

}

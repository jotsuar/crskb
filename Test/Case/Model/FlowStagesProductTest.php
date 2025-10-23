<?php
App::uses('FlowStagesProduct', 'Model');

/**
 * FlowStagesProduct Test Case
 */
class FlowStagesProductTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.flow_stages_product',
		'app.flow_stage',
		'app.prospective_users',
		'app.quotation',
		'app.user',
		'app.manage',
		'app.prospective_user',
		'app.contacs_user',
		'app.clients_legal',
		'app.clients_natural',
		'app.log',
		'app.quotations_product',
		'app.product',
		'app.templates_product',
		'app.template',
		'app.products'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FlowStagesProduct = ClassRegistry::init('FlowStagesProduct');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FlowStagesProduct);

		parent::tearDown();
	}

}

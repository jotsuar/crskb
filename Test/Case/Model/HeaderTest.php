<?php
App::uses('Header', 'Model');

/**
 * Header Test Case
 */
class HeaderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.header',
		'app.quotation',
		'app.flow_stage',
		'app.prospective_user',
		'app.user',
		'app.manage',
		'app.log',
		'app.technical_service',
		'app.contacs_user',
		'app.clients_legal',
		'app.clients_natural',
		'app.product_technical',
		'app.accessory',
		'app.flow_stages_product',
		'app.product',
		'app.quotations_product',
		'app.import',
		'app.quotations_products',
		'app.templates_product',
		'app.template',
		'app.draft_information',
		'app.atention_time',
		'app.payment',
		'app.prospective_users'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Header = ClassRegistry::init('Header');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Header);

		parent::tearDown();
	}

}

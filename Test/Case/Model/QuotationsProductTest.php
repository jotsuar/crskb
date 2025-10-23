<?php
App::uses('QuotationsProduct', 'Model');

/**
 * QuotationsProduct Test Case
 */
class QuotationsProductTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.quotations_product',
		'app.quotation',
		'app.flow_stage',
		'app.prospective_user',
		'app.user',
		'app.manage',
		'app.log',
		'app.technical_service',
		'app.contacs_user',
		'app.clients_legal',
		'app.adress',
		'app.clients_natural',
		'app.product_technical',
		'app.accessory',
		'app.progres_note',
		'app.management_notice',
		'app.import',
		'app.brand',
		'app.import_request',
		'app.import_requests_detail',
		'app.product',
		'app.category',
		'app.templates_product',
		'app.template',
		'app.flow_stages_product',
		'app.draft_information',
		'app.import_product',
		'app.news',
		'app.import_requests_details_product',
		'app.imports_prospective_user',
		'app.bill_product',
		'app.atention_time',
		'app.payment',
		'app.prospective_users',
		'app.receipt',
		'app.header'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->QuotationsProduct = ClassRegistry::init('QuotationsProduct');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->QuotationsProduct);

		parent::tearDown();
	}

}

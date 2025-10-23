<?php
App::uses('ImportProduct', 'Model');

/**
 * ImportProduct Test Case
 */
class ImportProductTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.import_product',
		'app.import',
		'app.user',
		'app.manage',
		'app.prospective_user',
		'app.contacs_user',
		'app.clients_legal',
		'app.adress',
		'app.clients_natural',
		'app.technical_service',
		'app.product_technical',
		'app.accessory',
		'app.flow_stage',
		'app.quotation',
		'app.header',
		'app.quotations_product',
		'app.product',
		'app.brand',
		'app.category',
		'app.templates_product',
		'app.template',
		'app.flow_stages_product',
		'app.draft_information',
		'app.bill_product',
		'app.atention_time',
		'app.payment',
		'app.prospective_users',
		'app.progres_note',
		'app.receipt',
		'app.imports_prospective_user',
		'app.log',
		'app.management_notice',
		'app.import_request',
		'app.import_requests_detail',
		'app.import_requests_details_product',
		'app.quotations_products'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ImportProduct = ClassRegistry::init('ImportProduct');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ImportProduct);

		parent::tearDown();
	}

}

<?php
App::uses('ImportProductsDetail', 'Model');

/**
 * ImportProductsDetail Test Case
 */
class ImportProductsDetailTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.import_products_detail',
		'app.product',
		'app.brand',
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
		'app.clients_natural',
		'app.product_technical',
		'app.accessory',
		'app.progres_note',
		'app.management_notice',
		'app.import',
		'app.import_request',
		'app.import_requests_detail',
		'app.import_requests_details_product',
		'app.import_product',
		'app.news',
		'app.flow_stages_product',
		'app.draft_information',
		'app.atention_time',
		'app.payment',
		'app.prospective_users',
		'app.header',
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
		$this->ImportProductsDetail = ClassRegistry::init('ImportProductsDetail');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ImportProductsDetail);

		parent::tearDown();
	}

}

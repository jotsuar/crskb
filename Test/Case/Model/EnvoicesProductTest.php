<?php
App::uses('EnvoicesProduct', 'Model');

/**
 * EnvoicesProduct Test Case
 */
class EnvoicesProductTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.envoices_product',
		'app.envoice',
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
		'app.import',
		'app.brand',
		'app.import_request',
		'app.import_requests_detail',
		'app.product',
		'app.category',
		'app.quotations_product',
		'app.quotation',
		'app.flow_stage',
		'app.flow_stages_product',
		'app.draft_information',
		'app.header',
		'app.cost',
		'app.import_product',
		'app.news',
		'app.import_requests_details_product',
		'app.imports_prospective_user',
		'app.bill_product',
		'app.atention_time',
		'app.payment',
		'app.prospective_users',
		'app.progres_note',
		'app.receipt',
		'app.log',
		'app.management_notice',
		'app.order',
		'app.shipping',
		'app.conveyor',
		'app.shippings_product',
		'app.orders_product'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->EnvoicesProduct = ClassRegistry::init('EnvoicesProduct');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EnvoicesProduct);

		parent::tearDown();
	}

}

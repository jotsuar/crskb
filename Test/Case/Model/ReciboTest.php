<?php
App::uses('Recibo', 'Model');

/**
 * Recibo Test Case
 */
class ReciboTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.recibo',
		'app.receipt',
		'app.prospective_user',
		'app.user',
		'app.manage',
		'app.quotation',
		'app.flow_stage',
		'app.flow_stages_product',
		'app.product',
		'app.brand',
		'app.category',
		'app.quotations_product',
		'app.cost',
		'app.import_product',
		'app.import',
		'app.import_request',
		'app.import_requests_detail',
		'app.import_requests_details_product',
		'app.imports_prospective_user',
		'app.news',
		'app.features_value',
		'app.feature',
		'app.products_features_value',
		'app.draft_information',
		'app.header',
		'app.log',
		'app.technical_service',
		'app.contacs_user',
		'app.clients_legal',
		'app.adress',
		'app.clients_natural',
		'app.certificado',
		'app.product_technical',
		'app.accessory',
		'app.progres_note',
		'app.management_notice',
		'app.bill_product',
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
		$this->Recibo = ClassRegistry::init('Recibo');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Recibo);

		parent::tearDown();
	}

}

<?php
App::uses('Percentage', 'Model');

/**
 * Percentage Test Case
 */
class PercentageTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.percentage',
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
		'app.templates_product',
		'app.template',
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
		'app.management_notice'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Percentage = ClassRegistry::init('Percentage');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Percentage);

		parent::tearDown();
	}

}

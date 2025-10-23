<?php
App::uses('Approve', 'Model');

/**
 * Approve Test Case
 */
class ApproveTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.approve',
		'app.flow_stage',
		'app.prospective_user',
		'app.user',
		'app.manage',
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
		'app.import_product',
		'app.import',
		'app.import_request',
		'app.import_requests_detail',
		'app.import_requests_details_product',
		'app.imports_prospective_user',
		'app.news',
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
		'app.bill_product',
		'app.atention_time',
		'app.payment',
		'app.prospective_users',
		'app.receipt'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Approve = ClassRegistry::init('Approve');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Approve);

		parent::tearDown();
	}

}

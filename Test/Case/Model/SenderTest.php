<?php
App::uses('Sender', 'Model');

/**
 * Sender Test Case
 */
class SenderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.sender',
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
		'app.templates_product',
		'app.template',
		'app.import_product',
		'app.import',
		'app.import_request',
		'app.import_requests_detail',
		'app.import_requests_details_product',
		'app.imports_prospective_user',
		'app.news',
		'app.draft_information',
		'app.header',
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
		'app.receipt',
		'app.quiz',
		'app.question',
		'app.answer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Sender = ClassRegistry::init('Sender');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Sender);

		parent::tearDown();
	}

}

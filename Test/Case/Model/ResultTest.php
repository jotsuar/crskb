<?php
App::uses('Result', 'Model');

/**
 * Result Test Case
 */
class ResultTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.result',
		'app.answer',
		'app.question',
		'app.quiz',
		'app.product',
		'app.brand',
		'app.category',
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
		'app.import_request',
		'app.import_requests_detail',
		'app.import_requests_details_product',
		'app.import_product',
		'app.news',
		'app.imports_prospective_user',
		'app.bill_product',
		'app.flow_stages_product',
		'app.draft_information',
		'app.atention_time',
		'app.payment',
		'app.prospective_users',
		'app.receipt',
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
		$this->Result = ClassRegistry::init('Result');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Result);

		parent::tearDown();
	}

}

<?php
App::uses('ProgresNote', 'Model');

/**
 * ProgresNote Test Case
 */
class ProgresNoteTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.progres_note',
		'app.prospective_users',
		'app.user',
		'app.manage',
		'app.prospective_user',
		'app.contacs_user',
		'app.clients_legal',
		'app.technical_service',
		'app.clients_natural',
		'app.product_technical',
		'app.accessory',
		'app.flow_stage',
		'app.quotation',
		'app.header',
		'app.quotations_product',
		'app.product',
		'app.templates_product',
		'app.template',
		'app.flow_stages_product',
		'app.draft_information',
		'app.import',
		'app.quotations_products',
		'app.atention_time',
		'app.payment',
		'app.log'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProgresNote = ClassRegistry::init('ProgresNote');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProgresNote);

		parent::tearDown();
	}

}

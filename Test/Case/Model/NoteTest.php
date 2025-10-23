<?php
App::uses('Note', 'Model');

/**
 * Note Test Case
 */
class NoteTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.note',
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
		'app.flow_stages_product',
		'app.product',
		'app.quotations_product',
		'app.import',
		'app.quotations_products',
		'app.templates_product',
		'app.template',
		'app.draft_information',
		'app.atention_time',
		'app.payment',
		'app.prospective_users',
		'app.quotations_note'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Note = ClassRegistry::init('Note');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Note);

		parent::tearDown();
	}

}

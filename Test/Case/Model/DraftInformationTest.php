<?php
App::uses('DraftInformation', 'Model');

/**
 * DraftInformation Test Case
 */
class DraftInformationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.draft_information',
		'app.flow_stages_product',
		'app.flow_stage',
		'app.prospective_user',
		'app.user',
		'app.manage',
		'app.quotation',
		'app.quotations_product',
		'app.product',
		'app.templates_product',
		'app.template',
		'app.log',
		'app.contacs_user',
		'app.clients_legal',
		'app.technical_service',
		'app.clients_natural',
		'app.accessory',
		'app.technical_services',
		'app.atention_time'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->DraftInformation = ClassRegistry::init('DraftInformation');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->DraftInformation);

		parent::tearDown();
	}

}

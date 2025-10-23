<?php
App::uses('TechnicalService', 'Model');

/**
 * TechnicalService Test Case
 */
class TechnicalServiceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.technical_service',
		'app.contacs_users',
		'app.clients_natural',
		'app.prospective_user',
		'app.user',
		'app.manage',
		'app.quotation',
		'app.flow_stage',
		'app.flow_stages_product',
		'app.product',
		'app.quotations_product',
		'app.templates_product',
		'app.template',
		'app.log',
		'app.contacs_user',
		'app.clients_legal',
		'app.atention_time'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TechnicalService = ClassRegistry::init('TechnicalService');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TechnicalService);

		parent::tearDown();
	}

}

<?php
App::uses('Informe', 'Model');

/**
 * Informe Test Case
 */
class InformeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.informe'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Informe = ClassRegistry::init('Informe');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Informe);

		parent::tearDown();
	}

}

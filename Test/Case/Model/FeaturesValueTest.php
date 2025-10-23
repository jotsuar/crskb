<?php
App::uses('FeaturesValue', 'Model');

/**
 * FeaturesValue Test Case
 */
class FeaturesValueTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.features_value',
		'app.feature'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FeaturesValue = ClassRegistry::init('FeaturesValue');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FeaturesValue);

		parent::tearDown();
	}

}

<?php
App::uses('EmailTracking', 'Model');

/**
 * EmailTracking Test Case
 */
class EmailTrackingTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.email_tracking',
		'app.campaign'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->EmailTracking = ClassRegistry::init('EmailTracking');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EmailTracking);

		parent::tearDown();
	}

}

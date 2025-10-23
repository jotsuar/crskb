<?php
App::uses('AtentionTime', 'Model');

/**
 * AtentionTime Test Case
 */
class AtentionTimeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.atention_time',
		'app.prospective_users'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AtentionTime = ClassRegistry::init('AtentionTime');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AtentionTime);

		parent::tearDown();
	}

}

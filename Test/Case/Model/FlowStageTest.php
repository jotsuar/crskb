<?php
App::uses('FlowStage', 'Model');

/**
 * FlowStage Test Case
 */
class FlowStageTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.flow_stage',
		'app.prospective_users'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FlowStage = ClassRegistry::init('FlowStage');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FlowStage);

		parent::tearDown();
	}

}

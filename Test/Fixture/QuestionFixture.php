<?php
/**
 * Question Fixture
 */
class QuestionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'quiz_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'required' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'have_points' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'points' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'quiz_id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'type' => 1,
			'required' => 1,
			'have_points' => 1,
			'points' => 1,
			'state' => 1,
			'created' => '2021-02-09 17:54:41',
			'modified' => '2021-02-09 17:54:41'
		),
	);

}

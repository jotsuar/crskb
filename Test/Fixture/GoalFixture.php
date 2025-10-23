<?php
/**
 * Goal Fixture
 */
class GoalFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'year' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'01' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'02' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'03' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'04' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'05' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'06' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'07' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'08' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'09' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'10' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'11' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'12' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
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
			'year' => 1,
			'user_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'01' => 1,
			'02' => 1,
			'03' => 1,
			'04' => 1,
			'05' => 1,
			'06' => 1,
			'07' => 1,
			'08' => 1,
			'09' => 1,
			'10' => 1,
			'11' => 1,
			'12' => 1,
			'state' => 1,
			'created' => '2022-01-12 13:17:19',
			'modified' => '2022-01-12 13:17:19'
		),
	);

}

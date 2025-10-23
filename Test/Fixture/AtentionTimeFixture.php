<?php
/**
 * AtentionTime Fixture
 */
class AtentionTimeFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'atention_time';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'prospective_users_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'first_stage' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			
		),
		'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_bin', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'prospective_users_id' => 1,
			'first_stage' => '2018-09-05 11:41:41',
			'created' => '2018-09-05 11:41:41'
		),
	);

}

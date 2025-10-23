<?php
/**
 * Sender Fixture
 */
class SenderFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'prospective_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'quotation_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'quiz_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'send_date' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'deadline' => array('type' => 'datetime', 'null' => false, 'default' => null),
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
			'prospective_user_id' => 1,
			'quotation_id' => 1,
			'state' => 1,
			'quiz_id' => 1,
			'send_date' => '2021-05-24 16:27:54',
			'deadline' => '2021-05-24 16:27:54',
			'created' => '2021-05-24 16:27:54',
			'modified' => '2021-05-24 16:27:54'
		),
	);

}

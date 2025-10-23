<?php
/**
 * EmailTracking Fixture
 */
class EmailTrackingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'message_id' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'campaign_id' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'response' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'send' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'delivered' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'read' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'clicked' => array('type' => 'datetime', 'null' => true, 'default' => null),
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
			'message_id' => 'Lorem ipsum dolor sit amet',
			'campaign_id' => 1,
			'email' => 'Lorem ipsum dolor sit amet',
			'response' => 'Lorem ipsum dolor sit amet',
			'send' => '2020-05-14 13:21:55',
			'delivered' => '2020-05-14 13:21:55',
			'read' => '2020-05-14 13:21:55',
			'clicked' => '2020-05-14 13:21:55',
			'state' => 1,
			'created' => '2020-05-14 13:21:55',
			'modified' => '2020-05-14 13:21:55'
		),
	);

}

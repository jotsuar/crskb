<?php
/**
 * ConfigFlow Fixture
 */
class ConfigFlowFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'hours_contact' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'hours_quotation' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'flows_cntact' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'flows_quotation' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_unicode_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'hours_contact' => 1,
			'hours_quotation' => 1,
			'flows_cntact' => 1,
			'flows_quotation' => 1,
			'state' => 1,
			'created' => '2023-04-14 17:08:20',
			'modified' => '2023-04-14 17:08:20'
		),
	);

}

<?php
/**
 * Salesinvoice Fixture
 */
class SalesinvoiceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'bill_value' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'bill_value_iva' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'bill_code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'prospective_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'bill_date' => array('type' => 'date', 'null' => false, 'default' => null),
		'bill_file' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'bill_value' => 1,
			'bill_value_iva' => 1,
			'bill_code' => 'Lorem ipsum dolor sit amet',
			'user_id' => 1,
			'prospective_user_id' => 1,
			'bill_date' => '2020-02-28',
			'bill_file' => 'Lorem ipsum dolor sit amet',
			'state' => 1,
			'created' => '2020-02-28 10:25:02',
			'modified' => '2020-02-28 10:25:02'
		),
	);

}

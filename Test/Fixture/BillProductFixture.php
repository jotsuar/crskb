<?php
/**
 * BillProduct Fixture
 */
class BillProductFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'prospective_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'bill_date' => array('type' => 'date', 'null' => false, 'default' => null),
		'deleted_at' => array('type' => 'date', 'null' => true, 'default' => null),
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
			'product_id' => 1,
			'quantity' => 1,
			'prospective_user_id' => 1,
			'bill_date' => '2020-03-25',
			'deleted_at' => '2020-03-25',
			'state' => 1,
			'created' => '2020-03-25 17:29:05',
			'modified' => '2020-03-25 17:29:05'
		),
	);

}

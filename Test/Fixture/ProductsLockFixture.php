<?php
/**
 * ProductsLock Fixture
 */
class ProductsLockFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'prospective_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'quantity_bog' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'quantity_stm' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'quantity_stb' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'quantity_back' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'due_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'lock_date' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'unlock_date' => array('type' => 'datetime', 'null' => false, 'default' => null),
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
			'prospective_user_id' => 1,
			'quantity' => 1,
			'quantity_bog' => 1,
			'quantity_stm' => 1,
			'quantity_stb' => 1,
			'quantity_back' => 1,
			'due_date' => '2020-04-29',
			'lock_date' => '2020-04-29 11:26:14',
			'unlock_date' => '2020-04-29 11:26:14',
			'state' => 1,
			'created' => '2020-04-29 11:26:14',
			'modified' => '2020-04-29 11:26:14'
		),
	);

}

<?php
/**
 * OrdersProduct Fixture
 */
class OrdersProductFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'order_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'price' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'iva' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'currency' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'cost' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'margin' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
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
			'order_id' => 1,
			'product_id' => 1,
			'price' => 1,
			'quantity' => 1,
			'iva' => 1,
			'currency' => 'Lorem ipsum dolor sit amet',
			'cost' => 1,
			'margin' => 1,
			'state' => 1,
			'created' => '2021-05-03 17:55:50',
			'modified' => '2021-05-03 17:55:50'
		),
	);

}

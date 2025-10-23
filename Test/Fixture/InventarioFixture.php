<?php
/**
 * Inventario Fixture
 */
class InventarioFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'cost_prom' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'stock' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'last_day' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'fecha' => array('type' => 'date', 'null' => false, 'default' => null),
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
			'cost_prom' => 1,
			'stock' => 1,
			'last_day' => 1,
			'fecha' => '2021-03-24',
			'created' => '2021-03-24 17:19:45',
			'modified' => '2021-03-24 17:19:45'
		),
	);

}

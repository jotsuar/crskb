<?php
/**
 * Accessory Fixture
 */
class AccessoryFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'accesorio' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb4_bin', 'charset' => 'utf8mb4'),
		'product_technicals_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'technical_services_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'accesorio' => 'Lorem ipsum dolor sit amet',
			'product_technicals_id' => 1,
			'technical_services_id' => 1
		),
	);

}

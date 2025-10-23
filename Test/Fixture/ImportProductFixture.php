<?php
/**
 * ImportProduct Fixture
 */
class ImportProductFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'import_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'quotations_products_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'prospective_user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'quantity_final' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'quantity_back' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'quantity_back_total' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'currency' => array('type' => 'string', 'null' => false, 'default' => 'COP', 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'price' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'numero_orden' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'proveedor' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'fecha_orden' => array('type' => 'date', 'null' => true, 'default' => null),
		'link' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'fecha_estimada' => array('type' => 'date', 'null' => true, 'default' => null),
		'fecha_miami' => array('type' => 'date', 'null' => true, 'default' => null),
		'numero_guia' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'transportadora' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'fecha_nacionalizacion' => array('type' => 'date', 'null' => true, 'default' => null),
		'fecha_producto_empresa' => array('type' => 'date', 'null' => true, 'default' => null),
		'state_import' => array('type' => 'integer', 'null' => true, 'default' => '1', 'unsigned' => false),
		'created' => array('type' => 'date', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'import_id' => 1,
			'product_id' => 1,
			'quotations_products_id' => 1,
			'prospective_user_id' => 1,
			'quantity' => 1,
			'quantity_final' => 1,
			'quantity_back' => 1,
			'quantity_back_total' => 1,
			'currency' => 'Lorem ipsum dolor sit amet',
			'price' => 1,
			'numero_orden' => 'Lorem ipsum dolor sit amet',
			'proveedor' => 'Lorem ipsum dolor sit amet',
			'fecha_orden' => '2021-02-03',
			'link' => 'Lorem ipsum dolor sit amet',
			'fecha_estimada' => '2021-02-03',
			'fecha_miami' => '2021-02-03',
			'numero_guia' => 'Lorem ipsum dolor sit amet',
			'transportadora' => 'Lorem ipsum dolor sit amet',
			'fecha_nacionalizacion' => '2021-02-03',
			'fecha_producto_empresa' => '2021-02-03',
			'state_import' => 1,
			'created' => '2021-02-03'
		),
	);

}

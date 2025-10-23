<?php
/**
 * Liquidation Fixture
 */
class LiquidationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'valor_recaudo' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'valor_tiempo' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'valor_efectividad' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'valor_bono' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'valor_a_pagar' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'total_recaudado' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'total_ventas' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
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
			'valor_recaudo' => 1,
			'valor_tiempo' => 1,
			'valor_efectividad' => 1,
			'valor_bono' => 1,
			'valor_a_pagar' => 1,
			'total_recaudado' => 1,
			'total_ventas' => 1,
			'state' => 1,
			'created' => '2024-05-31 17:45:01',
			'modified' => '2024-05-31 17:45:01'
		),
	);

}

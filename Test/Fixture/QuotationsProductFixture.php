<?php
/**
 * QuotationsProduct Fixture
 */
class QuotationsProductFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'quotation_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'id_llc' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'state_llc' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'note' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'price' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'quantity' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'currency' => array('type' => 'string', 'null' => false, 'default' => 'cop', 'length' => 3, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'change' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'trm_change' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'quantity_back' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'margen' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'delivery' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'biiled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'warehouse' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'comment' => '0 medellin, 1 bogota, 2 importación, 3 sin gestionar'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'quotation_id' => 1,
			'id_llc' => 1,
			'state_llc' => 1,
			'product_id' => 1,
			'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'price' => 'Lorem ipsum dolor sit amet',
			'quantity' => 'Lorem ipsum dolor sit amet',
			'currency' => 'L',
			'change' => 1,
			'trm_change' => 1,
			'quantity_back' => 1,
			'margen' => 1,
			'delivery' => 'Lorem ipsum dolor sit amet',
			'state' => 1,
			'biiled' => 1,
			'warehouse' => 1,
			'created' => '2021-02-03 16:10:44',
			'modified' => '2021-02-03 16:10:44'
		),
	);

}

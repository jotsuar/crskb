<?php
/**
 * Order Fixture
 */
class OrderFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'clients_legals_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'clients_natural_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'contacs_user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'quotation_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'payment_type' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'payment_text' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'prefijo' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'code' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'total' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'iva' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'deadline' => array('type' => 'date', 'null' => false, 'default' => null),
		'note' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'clients_legals_id' => 1,
			'clients_natural_id' => 1,
			'contacs_user_id' => 1,
			'quotation_id' => 1,
			'payment_type' => 1,
			'payment_text' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'prefijo' => 'Lorem ipsum dolor sit amet',
			'code' => 1,
			'state' => 1,
			'total' => 1,
			'iva' => 1,
			'deadline' => '2021-05-03',
			'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'user_id' => 1,
			'created' => '2021-05-03 17:55:45',
			'modified' => '2021-05-03 17:55:45'
		),
	);

}

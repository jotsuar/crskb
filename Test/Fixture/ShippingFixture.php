<?php
/**
 * Shipping Fixture
 */
class ShippingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'address_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'order_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'document' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'guide' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'conveyor_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'note' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'date_initial' => array('type' => 'date', 'null' => true, 'default' => null),
		'date_preparation' => array('type' => 'date', 'null' => true, 'default' => null),
		'date_send' => array('type' => 'date', 'null' => true, 'default' => null),
		'date_end' => array('type' => 'date', 'null' => true, 'default' => null),
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
			'address_id' => 1,
			'order_id' => 1,
			'document' => 'Lorem ipsum dolor sit amet',
			'type' => 1,
			'guide' => 1,
			'conveyor_id' => 1,
			'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'state' => 1,
			'date_initial' => '2021-05-03',
			'date_preparation' => '2021-05-03',
			'date_send' => '2021-05-03',
			'date_end' => '2021-05-03',
			'created' => '2021-05-03 17:51:50',
			'modified' => '2021-05-03 17:51:50'
		),
	);

}

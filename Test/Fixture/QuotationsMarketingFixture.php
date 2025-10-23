<?php
/**
 * QuotationsMarketing Fixture
 */
class QuotationsMarketingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'customer_note' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'total' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'header_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'proforma_usd' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'proforma' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'total_visible' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'notes' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'notes_description' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'conditions' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'products' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'created' => array('type' => 'date', 'null' => false, 'default' => null),
		'modified' => array('type' => 'date', 'null' => false, 'default' => null),
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
			'name' => 'Lorem ipsum dolor sit amet',
			'customer_note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'total' => 'Lorem ipsum dolor ',
			'header_id' => 1,
			'proforma_usd' => 1,
			'proforma' => 1,
			'total_visible' => 1,
			'notes' => 1,
			'notes_description' => 1,
			'conditions' => 1,
			'state' => 1,
			'products' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2021-05-18',
			'modified' => '2021-05-18'
		),
	);

}

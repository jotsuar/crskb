<?php
/**
 * Recibo Fixture
 */
class ReciboFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'numero' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'fecha_recibo' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'credito' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'debito' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'details' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'receipt_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'numero' => 1,
			'fecha_recibo' => '2024-05-23 11:52:51',
			'credito' => 1,
			'debito' => 1,
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'receipt_id' => 1,
			'created' => '2024-05-23 11:52:51',
			'modified' => '2024-05-23 11:52:51'
		),
	);

}

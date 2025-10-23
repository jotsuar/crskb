<?php
/**
 * Approve Fixture
 */
class ApproveFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'flujo_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'flowstage_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'quotation_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'copias_email' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'inlineRadioOptions' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'flujo_id' => 1,
			'flowstage_id' => 1,
			'quotation_id' => 1,
			'copias_email' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'inlineRadioOptions' => 1,
			'state' => 1,
			'created' => '2020-07-15 13:24:53',
			'modified' => '2020-07-15 13:24:53'
		),
	);

}

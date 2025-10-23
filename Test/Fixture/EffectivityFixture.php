<?php
/**
 * Effectivity Fixture
 */
class EffectivityFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'min' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'max' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'value' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'min' => 1,
			'max' => 1,
			'value' => 1,
			'created' => '2022-08-04 17:50:54',
			'modified' => '2022-08-04 17:50:54'
		),
	);

}

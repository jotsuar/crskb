<?php
/**
 * CarpetaDetalle Fixture
 */
class CarpetaDetalleFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'carpeta_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'document_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'blog_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'carpeta_id' => 1,
			'document_id' => 1,
			'blog_id' => 1,
			'created' => '2022-04-25 15:55:52',
			'modified' => '2022-04-25 15:55:52'
		),
	);

}

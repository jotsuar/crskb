<?php
/**
 * QuotationsSuggestedProduct Fixture
 */
class QuotationsSuggestedProductFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'product_ppal' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'quotation_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'delivery' => array('type' => 'string', 'null' => false, 'length' => 100, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'price_usd' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'price_cop' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
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
			'product_ppal' => 1,
			'product_id' => 1,
			'quotation_id' => 1,
			'quantity' => 1,
			'delivery' => 'Lorem ipsum dolor sit amet',
			'price_usd' => 1,
			'price_cop' => 1,
			'state' => 1,
			'created' => '2023-03-01 13:48:04',
			'modified' => '2023-03-01 13:48:04'
		),
	);

}

<?php
App::uses('AppModel', 'Model');
/**
 * ImportsBySalesProduct Model
 *
 * @property Product $Product
 * @property ImportsBySale $ImportsBySale
 * @property Import $Import
 */
class ImportsBySalesProduct extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ImportsBySale' => array(
			'className' => 'ImportsBySale',
			'foreignKey' => 'imports_by_sale_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Import' => array(
			'className' => 'Import',
			'foreignKey' => 'import_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

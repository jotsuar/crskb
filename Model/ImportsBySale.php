<?php
App::uses('AppModel', 'Model');
/**
 * ImportsBySale Model
 *
 * @property ImportsBySalesProduct $ImportsBySalesProduct
 */
class ImportsBySale extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ImportsBySalesProduct' => array(
			'className' => 'ImportsBySalesProduct',
			'foreignKey' => 'imports_by_sale_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public $belongsTo = array(
		'Import' => array(
			'className' => 'Import',
			'foreignKey' => 'import_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}

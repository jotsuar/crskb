<?php
App::uses('AppModel', 'Model');
/**
 * ImportProductsDetail Model
 *
 * @property Product $Product
 * @property Import $Import
 * @property ImportRequestsDetail $ImportRequestsDetail
 */
class ImportProductsDetail extends AppModel {


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
		'Import' => array(
			'className' => 'Import',
			'foreignKey' => 'import_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ImportRequestsDetail' => array(
			'className' => 'ImportRequestsDetail',
			'foreignKey' => 'import_requests_detail_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'flujo',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

<?php
App::uses('AppModel', 'Model');
/**
 * BillProduct Model
 *
 * @property Product $Product
 * @property ProspectiveUser $ProspectiveUser
 */
class BillProduct extends AppModel {


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
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

<?php
App::uses('AppModel', 'Model');
/**
 * Wo Model
 *
 * @property Product $Product
 * @property Brand $Brand
 */
class Wo extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	//public $useDbConfig = 'default2';


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
		'Brand' => array(
			'className' => 'Brand',
			'foreignKey' => 'brand_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

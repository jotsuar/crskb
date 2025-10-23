<?php
App::uses('AppModel', 'Model');
/**
 * FeaturesValue Model
 *
 * @property Feature $Feature
 */
class ProductsFeaturesValues extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'features_value_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'product_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'FeaturesValue' => array(
			'className' => 'FeaturesValue',
			'foreignKey' => 'features_value_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}

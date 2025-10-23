<?php
App::uses('AppModel', 'Model');
/**
 * QuotationsSuggestedProduct Model
 *
 * @property Product $Product
 * @property Quotation $Quotation
 * @property Product $Principal
 */
class QuotationsSuggestedProduct extends AppModel {


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
		'Quotation' => array(
			'className' => 'Quotation',
			'foreignKey' => 'quotation_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Principal' => array(
			'className' => 'Product',
			'foreignKey' => 'product_ppal',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

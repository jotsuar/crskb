<?php
App::uses('AppModel', 'Model');
/**
 * EnvoicesProduct Model
 *
 * @property Envoice $Envoice
 * @property Product $Product
 */
class EnvoicesProduct extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Envoice' => array(
			'className' => 'Envoice',
			'foreignKey' => 'envoice_id',
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

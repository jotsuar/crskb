<?php
App::uses('AppModel', 'Model');
/**
 * ClientsUser Model
 *
 * @property User $User
 * @property ClientsLegal $ClientsLegal
 * @property ClientsNatural $ClientsNatural
 */
class ClientsUser extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ClientsLegal' => array(
			'className' => 'ClientsLegal',
			'foreignKey' => 'clients_legal_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ClientsNatural' => array(
			'className' => 'ClientsNatural',
			'foreignKey' => 'clients_natural_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

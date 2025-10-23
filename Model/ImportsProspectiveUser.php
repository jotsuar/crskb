<?php
App::uses('AppModel', 'Model');
/**
 * ImportsProspectiveUser Model
 *
 * @property Import $Import
 * @property ProspectiveUser $ProspectiveUser
 */
class ImportsProspectiveUser extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Import' => array(
			'className' => 'Import',
			'foreignKey' => 'import_id',
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

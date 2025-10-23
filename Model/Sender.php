<?php
App::uses('AppModel', 'Model');
/**
 * Sender Model
 *
 * @property ProspectiveUser $ProspectiveUser
 * @property Quotation $Quotation
 * @property Quiz $Quiz
 */
class Sender extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_user_id',
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
		// 'Quiz' => array(
		// 	'className' => 'Quiz',
		// 	'foreignKey' => 'quiz_id',
		// 	'conditions' => '',
		// 	'fields' => '',
		// 	'order' => ''
		// )
	);
}

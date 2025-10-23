<?php
App::uses('AppModel', 'Model');
/**
 * SbUser Model
 *
 * @property SbTiempo $SbTiempo
 */
class SbFlujo extends AppModel {

	public $useDbConfig = 'chat';

	public $useTable = 'sb_flujos';
	// The Associations below have been created with all possible keys, those that are not needed can be removed

	public $belongsTo = array(
		'SbConversation' => array(
			'className' => 'SbConversation',
			'foreignKey' => 'conversation_id',
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

}

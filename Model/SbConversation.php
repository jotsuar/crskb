<?php
App::uses('AppModel', 'Model');
/**
 * SbUser Model
 *
 * @property SbTiempo $SbTiempo
 */
class SbConversation extends AppModel {

	public $useDbConfig = 'chat';

	public $virtualFields = array(
	    "email" => '(SELECT email from sb_users where id = SbConversation.agent_id)',
	    "email_sub" => '(SELECT email from sb_users where id = SbConversation.reasign)',
	);
	// The Associations below have been created with all possible keys, those that are not needed can be removed

	public $belongsTo = array(
		'SbUser' => array(
			'className' => 'SbUser',
			'foreignKey' => 'agent_id',
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

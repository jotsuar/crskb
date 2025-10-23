<?php
App::uses('AppModel', 'Model');
/**
 * SbTiempo Model
 *
 * @property SbUser $SbUser
 */
class SbLinea extends AppModel {

	public $useDbConfig = 'chat';

	public $belongsTo = array(
		'SbUser' => array(
			'className' => 'SbUser',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

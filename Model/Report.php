<?php
App::uses('AppModel', 'Model');
/**
 * Commision Model
 *
 * @property User $User
 */
class Report extends AppModel {

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

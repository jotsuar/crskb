<?php
App::uses('AppModel', 'Model');

class Payment extends AppModel {

	public $belongsTo = array(
		'ProspectiveUsers' => array(
			'className' => 'ProspectiveUsers',
			'foreignKey' => 'prospective_users_id'
		)
	);

	public function there_payment_history($flujo_id){
		$conditions 			= array('Payment.prospective_users_id' => $flujo_id);
		return $this->find('count',compact('conditions'));
	}

	public function data_payment_history($flujo_id){
		$conditions 			= array('Payment.prospective_users_id' => $flujo_id);
		return $this->find('all',compact('conditions'));
	}

}

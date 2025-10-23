<?php
App::uses('AppModel', 'Model');
/**
 * Salesinvoice Model
 *
 * @property User $User
 * @property ProspectiveUser $ProspectiveUser
 */
class Salesinvoice extends AppModel {


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
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function getSales($date_ini = null, $date_end = null, $user_id = null){

		if(is_null($date_ini)){
			$date_ini = date("Y-m-d");
		}

		if(is_null($date_end)){
			$date_end = date("Y-m-d");
		}


		$conditions = array(
			'bill_date IS NOT NULL','bill_date >=' => $date_ini, 'bill_date <=' => $date_end
		);

		if(!is_null($user_id)){
			$conditions["user_id"] = $user_id;
		}

		$this->recursive = -1;

		$fields = array("SUM(bill_value) as total");

		$total = $this->find("first", compact("conditions","fields"));

		return !empty($total["0"]["total"]) ? $total["0"]["total"] : 0;

	}
}

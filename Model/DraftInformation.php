<?php
App::uses('AppModel', 'Model');

class DraftInformation extends AppModel {

	public $hasMany = array(
		'FlowStagesProduct' => array(
			'className' => 'FlowStagesProduct',
			'foreignKey' => 'draft_information_id',
			'dependent' => false
		)
	);

	public function get_data($flujo_id){
		$this->recursive 	= -1;
		$conditions			= array('DraftInformation.prospective_users_id' => $flujo_id);
		return $this->find('first',compact('conditions'));
	}

}

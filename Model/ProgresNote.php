<?php
App::uses('AppModel', 'Model');

class ProgresNote extends AppModel {

	public $belongsTo = array(
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_users_id'
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('ProgresNote.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function get_notes_flujo($flujo_id){
		$this->recursive 	= -1;
		$conditions			= array('ProgresNote.prospective_users_id' => $flujo_id);
		return $this->find('all',compact('conditions'));
	}

	public function count_notes_flujo($flujo_id){
		$conditions			= array('ProgresNote.prospective_users_id' => $flujo_id);
		return $this->find('count',compact('conditions'));
	}

}
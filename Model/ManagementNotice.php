<?php
App::uses('AppModel', 'Model');

class ManagementNotice extends AppModel {

	public $validate = array(
		'title' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'fecha_ini' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'fecha_fin' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		)
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('ManagementNotice.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function all(){
		$this->recursive 	= -1;
		$order				= array('ManagementNotice.id' => 'desc');
		return $this->find('all',compact('order'));
	}

	public function notices_waiting(){
		$this->recursive 	= -1;
		$conditions			= array('ManagementNotice.state' => Configure::read('variables.state_waiting'),'ManagementNotice.fecha_fin' => date("Y-m-d"));
		return $this->find('all',compact('conditions'));
	}

	public function notices_update_enabled(){
		$this->recursive 	= -1;
		$conditions			= array('ManagementNotice.state' => Configure::read('variables.state_enabled'),'ManagementNotice.fecha_fin <' => date("Y-m-d"));
		return $this->find('all',compact('conditions'));
	}

	public function update_enabled($notice_id){
		$this->updateAll(
	    	array('ManagementNotice.state' => Configure::read('variables.state_enabled')), array('ManagementNotice.id' => $notice_id)
	    );
	}

	public function update_disabled($notice_id){
		$this->updateAll(
	    	array('ManagementNotice.state' => Configure::read('variables.state_disabled')), array('ManagementNotice.id' => $notice_id)
	    );
	}

	public function notes_enabled(){
		$this->recursive 	= -1;
		$conditions			= array('ManagementNotice.state' => Configure::read('variables.state_enabled'));
		return $this->find('all',compact('conditions'));
	}
}
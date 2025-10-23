<?php
App::uses('AppModel', 'Model');

class Note extends AppModel {

	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank')
			),
		),
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank')
			),
		),
	);

	public function all_data(){
		$this->recursive 	= -1;
		$order				= array('Note.id' => 'desc');
		return $this->find('all',compact('order'));
	}

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('Note.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function data_notes_previas(){
		$this->recursive 	= -1;
		$conditions			= array('Note.type' => 1);
		return $this->find('all',compact('conditions'));
	}

	public function data_notes_descriptiva(){
		$this->recursive 	= -1;
		$conditions			= array('Note.type' => 2);
		return $this->find('all',compact('conditions'));
	}

	public function data_conditions_negocio(){
		$this->recursive 	= -1;
		$conditions			= array('Note.type' => 3);
		return $this->find('all',compact('conditions'));
	}

}

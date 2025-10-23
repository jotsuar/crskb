<?php
App::uses('AppModel', 'Model');

class Header extends AppModel {

	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank')
			),
		),
		'img_big' => array(
			'notBlank' => array(
				'rule' => array('notBlank')
			),
		),
		'img_small' => array(
			'notBlank' => array(
				'rule' => array('notBlank')
			),
		),
	);

	public $hasMany = array(
		'Quotation' => array(
			'className' => 'Quotation',
			'foreignKey' => 'header_id',
			'dependent' => false
		)
	);

	public function all_headers(){
		$this->recursive 	= -1;
		$order				= array('Header.id' => 'desc');
		return $this->find('all',compact('order'));
	}

	public function get_headers_list(){
		$this->recursive 	= -1;
		return $this->find('list');
	}

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('Header.id' => $id);
		return $this->find('first',compact('conditions'));
	}

}

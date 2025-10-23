<?php
App::uses('AppModel', 'Model');

class Template extends AppModel {

	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public $hasMany = array(
		'TemplatesProduct' => array(
			'className' => 'TemplatesProduct',
			'foreignKey' => 'template_id',
			'dependent' => false
		)
	);

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('Template.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function get_data_models($id){
		$this->recursive 	= 0;
		$conditions			= array('Template.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function get_templates(){
		$this->recursive 	= -1;
		$order				= array('Template.id' => 'desc');
		return $this->find('all',compact('order'));
	}

	public function get_templates_list(){
		$this->recursive 	= -1;
		return $this->find('list');
	}

	public function find_seeker($texto){
		$this->recursive 	= -1;
		$fields 			= array('Template.id');
		$conditions			= array('OR' => array(
							            'LOWER(Template.name) LIKE' 		=> '%'.$texto.'%',
							            'LOWER(Template.description) LIKE' 	=> '%'.$texto.'%'
							        )
								);
		return $this->find('all',compact('conditions','fields'));
	}

}

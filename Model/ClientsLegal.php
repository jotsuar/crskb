<?php
App::uses('AppModel', 'Model');

class ClientsLegal extends AppModel {

	public $virtualFields = array(
	    'full_name' => 'CONCAT(IFNULL(ClientsLegal.nit,""), " | ", ClientsLegal.name)'
	);

	public $belongsTo = array(
		'Parent' => array(
			'className' => 'ClientsLegal',
			'foreignKey' => 'parent_id'
		)
	);

	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'nit' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);

	public $hasMany = array(
		'ContacsUser' => array(
			'className' => 'ContacsUser',
			'foreignKey' => 'clients_legals_id',
			'dependent' => false
		),
		'Adress' => array(
			'className' => 'Adress',
			'foreignKey' => 'clients_legal_id',
			'dependent' => false
		),
		'Certificado' => array(
			'className' => 'Certificado',
			'foreignKey' => 'clients_legal_id',
			'dependent' => false
		),
		'Concession' => array(
			'className' => 'Concession',
			'foreignKey' => 'clients_legal_id',
			'dependent' => false
		)
	);

	public function get_data($id){
		$this->recursive 	= 1;
		$conditions			= array('ClientsLegal.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function get_data_modelos($id){
		$conditions 		= array('ClientsLegal.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function all_business(){
		$this->recursive 	= -1;
		$order				= array('ClientsLegal.id' => 'desc');
		return $this->find('all',compact('order'));
	}

	public function find_seeker($texto){
		$this->recursive 	= -1;
		$fields 			= array('ClientsLegal.*');
		$limit 				= 20;
		$conditions			= array('OR' => array(
							            'LOWER(ClientsLegal.name) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(ClientsLegal.nit) LIKE' 		=> '%'.$texto.'%'
							        )
								);

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ClientsLegal.user_receptor"] = AuthComponent::user("id");
		}

		return $this->find('all',compact('conditions','fields','limit'));
	}

	public function find_name_buscador_flujo($texto){
		$this->recursive 	= -1;
		$fields 			= array('ClientsLegal.id');
		$conditions			= array('LOWER(ClientsLegal.name) LIKE' 	=> '%'.$texto.'%');
		$datos 				= $this->find('all',compact('conditions','fields'));
		$resultFinal		= array();
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.ClientsLegal');
			foreach ($result as $value) {
				$resultFinal[] = $value['id'];
			}
		}
		return $resultFinal;
	}

	public function exist_nit($nit){
		$this->recursive 	= -1;
		$conditions 		= array('ClientsLegal.nit' => $nit);
		return $this->find('count',compact('conditions'));
	}
}

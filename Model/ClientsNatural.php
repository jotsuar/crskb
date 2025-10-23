<?php
App::uses('AppModel', 'Model');

class ClientsNatural extends AppModel {

	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank')
			),
		),
	);

	public $virtualFields = array(
	    'full_name' => 'CONCAT(IFNULL(ClientsNatural.identification,""), " | ", ClientsNatural.name," | ",ClientsNatural.email)'
	);

	public $hasMany = array(
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'clients_natural_id',
			'dependent' => false
		),
		'TechnicalService' => array(
			'className' => 'TechnicalService',
			'foreignKey' => 'clients_natural_id',
			'dependent' => false
		),
		'Adress' => array(
			'className' => 'Adress',
			'foreignKey' => 'clients_natural_id',
			'dependent' => false
		),
		'Certificado' => array(
			'className' => 'Certificado',
			'foreignKey' => 'clients_natural_id',
			'dependent' => false
		)
	);

	public function get_data($id){
		$this->recursive 	= 1;
		$conditions			= array('ClientsNatural.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function get_data_modelos($id){
		$conditions 		= array('ClientsNatural.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function all_clients(){
		$this->recursive 	= -1;
		$order				= array('ClientsNatural.id' => 'desc');
		return $this->find('all',compact('order'));
	}

	public function find_seeker($texto){
		$this->recursive 	= -1;
		$fields 			= array('ClientsNatural.*');
		$limit 				= 20;
		$conditions			= array('OR' => array(
							            'LOWER(ClientsNatural.name) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(ClientsNatural.email) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(ClientsNatural.identification) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(ClientsNatural.telephone) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(ClientsNatural.cell_phone) LIKE' 	=> '%'.$texto.'%',
							        )
								);

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ClientsNatural.user_receptor"] = AuthComponent::user("id");
		}

		return $this->find('all',compact('conditions','fields','limit'));
	}

	public function find_name_buscador_flujo($texto){
		$this->recursive 	= -1;
		$fields 			= array('ClientsNatural.id');
		$conditions			= array('LOWER(ClientsNatural.name) LIKE' 	=> '%'.$texto.'%');
		$datos 				= $this->find('all',compact('conditions','fields'));
		$resultFinal		= array();
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.ClientsNatural');
			foreach ($result as $value) {
				$resultFinal[] = $value['id'];
			}
		}
		return $resultFinal;
	}

	public function get_users_email_name(){
		$this->recursive 	= -1;
		$fields				= array('ClientsNatural.email','ClientsNatural.name');
		return $this->find('list',compact('fields'));
	}

	public function get_data_email($id){
		$this->recursive 	= -1;
		$fields 			= array('ClientsNatural.email');
		$conditions			= array('ClientsNatural.id' => $id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['ClientsNatural']['email'];
	}

	public function exist_email($email){
		$this->recursive 	= -1;
		$conditions 		= array('ClientsNatural.email' => $email);
		return $this->find('count',compact('conditions'));
	}

	public function generate(){
		$this->recursive = -1;
		$code 	= rand(100000,999999);
		$exists = $this->findByCode($code,0);
		if(!empty($exists)){
			return $this->generate();
		}else{
			return $code;
		}

	}

}
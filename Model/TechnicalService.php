<?php
App::uses('AppModel', 'Model');

class TechnicalService extends AppModel {

	public $belongsTo = array(
		'ContacsUser' => array(
			'className' => 'ContacsUser',
			'foreignKey' => 'contacs_users_id'
		),
		'ClientsNatural' => array(
			'className' => 'ClientsNatural',
			'foreignKey' => 'clients_natural_id'
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_users_id'
		)
	);

	public $hasMany = array(
		'ProductTechnical' => array(
			'className' => 'ProductTechnical',
			'foreignKey' => 'technical_services_id',
			'dependent' => false
		)
	);

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('TechnicalService.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function get_services(){
		$this->recursive 	= -1;
		$order				= array('TechnicalService.id' => 'desc');
		return $this->find('all',compact('order'));
	}

	public function find_seeker($texto){
		$this->recursive 	= -1;
		$limit 				= 20;
		$fields 			= array('TechnicalService.id');
		$conditions			= array('OR' => array(
							            'LOWER(TechnicalService.code) LIKE' 	=> '%'.$texto.'%'
							        )
								);
		return $this->find('all',compact('conditions','fields','limit'));
	}

	public function get_services_true(){
		$this->recursive 	= -1;
		$order				= array('TechnicalService.id' => 'desc');
		$conditions			= array('TechnicalService.state' => 1);
		return $this->find('all',compact('conditions','order'));
	}

	public function get_services_false(){
		$this->recursive 	= -1;
		$order				= array('TechnicalService.id' => 'desc');
		$conditions			= array('TechnicalService.state' => 0);
		return $this->find('all',compact('conditions','order'));
	}

	public function count_services_true($data = false){
		$conditions			= array();
		$joins 				= [
				['table' => 'prospective_users','alias' => 'ProspectiveUser','type' => 'INNER','conditions' => array('TechnicalService.prospective_users_id = ProspectiveUser.id')
				]
	    ];
		if ($data) {
	        $conditions["ProspectiveUser.state_flow"] = [1,2,3,4,5];
	        $conditions['TechnicalService.state'] = 1;
		}else{
			$conditions = array("OR" => [ ['TechnicalService.state'=> 1,"ProspectiveUser.state_flow" => [6,8]], ['TechnicalService.state'=> 2] ] );			
		}
		return $this->find('count',["conditions"=>$conditions,"joins"=>$joins,"recursive"=>-1]);
	}

	public function count_services_false(){
		$conditions			= array('TechnicalService.state' => 0);
		return $this->find('count',compact('conditions'));
	}

	public function count_services(){
		$this->recursive 	= -1;
		return $this->find('count');
	}

	public function get_contact($id){
		$this->recursive 	= -1;
		$fields 			= array('TechnicalService.contacs_users_id');
		$conditions			= array('TechnicalService.id' => $id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['TechnicalService']['contacs_users_id'];
	}

	public function state_valid_edit($id){
		$this->recursive 	= -1;
		$fields 			= array('TechnicalService.state');
		$conditions			= array('TechnicalService.id' => $id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		if ($datos['TechnicalService']['state'] == 0) {
			return true;
		} else {
			return false;
		}
	}

	public function consult_codigo_service($id){
		$this->recursive 	= -1;
		$fields 			= array('TechnicalService.code');
		$conditions			= array('TechnicalService.id' => $id);
		$datos 				= $this->find('first',compact('conditions'));
		return $datos['TechnicalService']['code'];
	}

	public function data_natural_products($clients_natural_id){
		$conditions			= array('TechnicalService.clients_natural_id' => $clients_natural_id);
		return $this->find('all',compact('conditions'));
	}

	public function data_legal_products($clients_legal_id){
		$conditions			= array('TechnicalService.clients_legal_id' => $clients_legal_id,'TechnicalService.created >=' => '2022-01-01' );
		return $this->find('all',compact('conditions'));
	}

	public function data_contacts_products($contacs_users_id){
		$conditions			= array('TechnicalService.contacs_users_id' => $contacs_users_id);
		return $this->find('all',compact('conditions'));
	}




}

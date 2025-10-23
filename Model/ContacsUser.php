<?php
App::uses('AppModel', 'Model');

class ContacsUser extends AppModel {

	public $belongsTo = array(
		'ClientsLegal' => array(
			'className' => 'ClientsLegal',
			'foreignKey' => 'clients_legals_id'
		),
		'Concession' => array(
			'className' => 'Concession',
			'foreignKey' => 'concession_id'
		)
	);

	public $virtualFields = array(
	    'empresa' => '( SELECT name from clients_legals WHERE clients_legals.id = ContacsUser.clients_legals_id limit 1 )',
	    'nit' => '( SELECT nit from clients_legals WHERE clients_legals.id = ContacsUser.clients_legals_id limit 1 )',
	);

	public $hasMany = array(
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'contacs_users_id'
		),
		'TechnicalService' => array(
			'className' => 'TechnicalService',
			'foreignKey' => 'contacs_users_id'
		)
	);

	public function get_data($id){
		$this->recursive 	= 1;
		$conditions 		= array('ContacsUser.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function get_data_modelos($id){
		$this->recursive 	= 0;
		$conditions 		= array('ContacsUser.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function find_id_bussines($id){
		$this->recursive 	= -1;
		$fields				= array('ContacsUser.clients_legals_id');
		$conditions 		= array('ContacsUser.id' => $id);
		return $this->find('first',compact('conditions','fields'));
	}

	public function find_list_contacs_users($empresa_id, $state = false){
		$this->recursive 	= 1;
		$conditions 		= array('ContacsUser.clients_legals_id' => $empresa_id);

		if($state){
			$conditions["ContacsUser.state"] = 1;
		}

		$data 	   = [];
		$contactos = $this->find('all',compact('conditions'));

		if (!empty($contactos)) {
			foreach ($contactos as $key => $value) {
				$data[$value["ContacsUser"]["id"]] = $value["ContacsUser"]["name"] . (isset($value["Concession"]["name"]) && !empty($value["Concession"]["name"]) ? " | Concession: " .$value["Concession"]["name"] : '') ;
			}
		}

		return $data;
	}

	public function find_contacs_users_bussines($empresa_id){
		$this->recursive 	= -1;
		$conditions 		= array('ContacsUser.clients_legals_id' => $empresa_id);
		return $this->find('all',compact('conditions'));
	}

	public function id_contacs_user_bussines($empresa_id){
		$this->recursive 	= -1;
		
		return $this->find("list",["fields" => ["id","id"], "conditions" => ["clients_legals_id" => $empresa_id] ]);
	}

	public function all_Contacs(){
		$this->recursive 	= -1;
		$order				= array('ContacsUser.id' => 'desc');
		return $this->find('all',compact('order'));
	}

	public function find_seeker($texto){
		$this->recursive 	= -1;
		$fields 			= array('ContacsUser.id');
		$limit 				= 20;
		$conditions			= array('OR' => array(
							            'LOWER(ContacsUser.name) LIKE' 			=> '%'.$texto.'%',
							            'LOWER(ContacsUser.telephone) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(ContacsUser.cell_phone) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(ContacsUser.email) LIKE' 		=> '%'.$texto.'%'
							        )
								);

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ContacsUser.user_receptor"] = AuthComponent::user("id");
		}

		return $this->find('all',compact('conditions','fields','limit'));
	}

	public function find_name_buscador_flujo($texto){
		$this->recursive 	= -1;
		$fields 			= array('ContacsUser.id');
		$conditions			= array('LOWER(ContacsUser.name) LIKE' 	=> '%'.$texto.'%');
		$datos 				= $this->find('all',compact('conditions','fields'));
		$resultFinal		= array();
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.ContacsUser');
			foreach ($result as $value) {
				$resultFinal[] = $value['id'];
			}
		}
		return $resultFinal;
	}

	public function get_users_email_name(){
		$this->recursive 	= -1;
		$fields				= array('ContacsUser.email','ContacsUser.name');
		return $this->find('list',compact('fields'));
	}

	public function get_data_email($id){
		$return = "";
		try {
			$this->recursive 	= -1;
			$fields 			= array('ContacsUser.email');
			$conditions			= array('ContacsUser.id' => $id);
			$datos 				= $this->find('first',compact('conditions','fields'));
			$return 			= empty($datos) ? "" : $datos['ContacsUser']['email'];
		} catch (Exception $e) {
			$return  			= "";
		}	

		return $return;
	}

	public function exist_email($email){
		$this->recursive 	= -1;
		$conditions 		= array('ContacsUser.email' => $email);
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
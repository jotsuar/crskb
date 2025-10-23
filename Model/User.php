<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {

	public $hasMany = array(
		'Manage' => array(
			'className' => 'Manage',
			'foreignKey' => 'user_id',
			'dependent' => false
		),
		'Quotation' => array(
			'className' => 'Quotation',
			'foreignKey' => 'User_id',
			'dependent' => false
		),
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'User_id',
			'dependent' => false
		),
		'Log' => array(
			'className' => 'Log',
			'foreignKey' => 'User_id',
			'dependent' => false
		),
		'TechnicalService' => array(
			'className' => 'TechnicalService',
			'foreignKey' => 'User_id',
			'dependent' => false
		),
		'ProgresNote' => array(
			'className' => 'ProgresNote',
			'foreignKey' => 'User_id',
			'dependent' => false
		),
		'ManagementNotice' => array(
			'className' => 'ManagementNotice',
			'foreignKey' => 'User_id',
			'dependent' => false
		),
		'Import' => array(
			'className' => 'Import',
			'foreignKey' => 'user_id',
			'dependent' => false
		)
	);

	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Nombre requerido',
				'on' => 'create',
			),
		),
		'telephone' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Teléfono requerido',
				'on' => 'create',
			),
		),
		'cell_phone' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Celular requerido',
				'on' => 'create',
			),
		),
		'email' => array(
            'rule'    => 'isUnique',
            'message' => 'El correo electrónico ya existe en nuestra base de datos',
            'on' => 'create',
        )
	);

	public function beforeSave($options = array()){
		if (isset($this->data[$this->alias]['password']) && !empty($this->data[$this->alias]['password'])){
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
	}

	public function generate_hash_change_password(){
	    $salt 		= Configure::read('Security.salt');
	    $salt_v2 	= Configure::read('Security.cipherSeed');
	    $rand 		= mt_rand(1,999999999);
	    $rand_v2 	= mt_rand(1,999999999);
	    return hash('sha256',$salt.$rand.$salt_v2.$rand_v2);
	}

	public function get_user_email($email){
		return $this->findByEmail($email);
	}

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions 		= array('User.id' => $id);
		return $this->find('first',compact('conditions')); 
	}

	public function get_data_modelos($id){
		$conditions 		= array('User.id' => $id);
		return $this->find('first',compact('conditions')); 
	}

	public function list_users(){
		$users_id_list 		= $this->not_list_id_users();
		$conditions 		= array('User.id !=' => $users_id_list);
		return $this->find('list',compact('conditions'));
	}

	public function all_users(){
		$users_id_list 		= $this->not_list_id_users();
		$order				= array('User.id' => 'desc');
		$conditions 		= array('User.id !=' => $users_id_list);
		return $this->find('all',compact('conditions','order'));
	}

	public function role_asesor_comercial_user(){
		$this->recursive 	= -1;
		$users_id_list 		= $this->not_list_id_users();
		$conditions			= array('User.role' => array("Servicio Técnico",Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'),Configure::read('variables.roles_usuarios.Asesor Comercial'),Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'),Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Servicio al Cliente')),'User.id !=' => $users_id_list);
		return $this->find('list',compact('conditions'));
	}

	public function role_technical_service_user(){
		$this->recursive 	= -1;
		$users_id_list 		= $this->not_list_id_users();
		$conditions			= array('User.role' => array(Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'),Configure::read('variables.roles_usuarios.Servicio Técnico')),'User.id !=' => $users_id_list, "User.state" => 1);
		return $this->find('list',compact('conditions'));
	}

	public function role_asesor_comercial_user_true_agrupado(){
		$this->recursive 	= -1;
		$users_id_list 		= $this->not_list_id_users();
		$conditions			= array('User.role' => array(Configure::read('variables.roles_usuarios.Servicio Técnico'),Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'),Configure::read('variables.roles_usuarios.Asesor Comercial'),Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'),Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),Configure::read('variables.roles_usuarios.Servicio al Cliente'),"Asesor Externo"),'User.state' => 1,'User.id !=' => $users_id_list);
		$listado = $this->find('all',compact('conditions'));

		$users = [];

		foreach ($listado as $key => $value) {
			$users[$value["User"]["role"]][$value["User"]["id"]] = $value["User"]["name"];
		}
		return $users;
	}

	public function role_asesor_comercial_user_true($dev = null){
		$this->recursive 	= -1;
		$users_id_list 		= $this->not_list_id_users($dev);
		$conditions			= array('User.role' => array("Servicio Técnico",Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'),Configure::read('variables.roles_usuarios.Asesor Comercial'),Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'),Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),Configure::read('variables.roles_usuarios.Servicio al Cliente'),"Asesor Externo","Logística"),'User.state' => 1,'User.id !=' => $users_id_list);
		return $this->find('list',compact('conditions'));
	}

	public function role_asesor_comercial_users_all_true($dev = null){
		$this->recursive 	= -1;
		$users_id_list 		= $this->not_list_id_users($dev);
		$conditions			= array('User.role' => array(Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'),Configure::read('variables.roles_usuarios.Asesor Comercial'),Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'),Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),Configure::read('variables.roles_usuarios.Servicio al Cliente'),'Servicio Técnico'),'User.state' => 1,'User.id !=' => $users_id_list);
		return $this->find('all',compact('conditions'));
	}

	public function role_asesor_comercial_user_true_not_user_session($user_id){
		$users_id_list 		= $this->not_list_id_users();
		$users_id_list[] 	= $user_id;
		$conditions			= array('User.role' => array('Servicio Técnico',Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'),Configure::read('variables.roles_usuarios.Asesor Comercial'),Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'),Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),Configure::read('variables.roles_usuarios.Servicio al Cliente')),'User.state' => 1,'User.id !=' => $users_id_list);
		return $this->find('list',compact('conditions'));
	}

	public function role_contablidad_user(){
		$this->recursive 	= -1;
		$users_id_list 		= $this->not_list_id_users();
		$conditions			= array('User.role' => Configure::read('variables.roles_usuarios.Contabilidad'),'User.id !=' => $users_id_list);
		return $this->find('all',compact('conditions'));
	}

	public function role_logistica_user(){
		$this->recursive 	= -1;
		$users_id_list 		= $this->not_list_id_users();
		$conditions			= array('User.role' => array(Configure::read('variables.roles_usuarios.Logística'),Configure::read('variables.roles_usuarios.Asesor Logístico Comercial')),'User.id !=' => $users_id_list);
		return $this->find('all',compact('conditions'));
	}

	public function role_administracion_user(){
		$this->recursive 	= -1;
		$users_id_list 		= $this->not_list_id_users();
		$conditions			= array('User.role' => Configure::read('variables.roles_usuarios.Administración'),'User.id !=' => $users_id_list);
		return $this->find('all',compact('conditions'));
	}	

	public function role_gerencia_user(){
		$this->recursive 	= -1;
		$users_id_list 		= $this->not_list_id_users();
		$conditions			= array('User.role' => Configure::read('variables.roles_usuarios.Gerente General'),'User.id !=' => $users_id_list);
		return $this->find('all',compact('conditions'));
	}

	public function find_seeker($texto){
		$this->recursive 	= -1;
		$users_id_list 		= $this->not_list_id_users();
		$fields 			= array('User.id');
		$conditions			= array('User.id !=' => $users_id_list,
									'OR' => array(
							            'LOWER(User.name) LIKE' 		=> '%'.$texto.'%',
							            'LOWER(User.telephone) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(User.cell_phone) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(User.email) LIKE' 		=> '%'.$texto.'%',
							            'LOWER(User.role) LIKE' 		=> '%'.$texto.'%'
							        )
								);
		return $this->find('all',compact('conditions','fields'));
	}

	public function users_notice_conection(){
		$this->recursive 	= -1;
		$conditions			= array('User.state_conection' => Configure::read('variables.state_enabled'),'User.id !=' => AuthComponent::user('id'));
		$datos 				= $this->find('all',compact('conditions'));
		foreach ($datos as $value) {
			$this->update_notice_conection($value['User']['id']);
		}
		return true;
	}

	public function update_notice_conection($user_id){
		$this->updateAll(
	    	array('User.notice_conection' => Configure::read('variables.state_enabled')), array('User.id' => $user_id)
	    );
	}

	public function users_notice_loguin(){
		$this->recursive 	= -1;
		$conditions			= array('User.id !=' => AuthComponent::user('id'));
		$datos 				= $this->find('all',compact('conditions'));
		foreach ($datos as $value) {
			$this->update_notice_loguin($value['User']['id']);
		}
		return true;
	}

	public function update_notice_loguin($user_id){
		$this->updateAll(
	    	array('User.notice_loguin' => Configure::read('variables.state_enabled')), array('User.id' => $user_id)
	    );
	}

	public function consult_state_conection(){
		$this->recursive 	= -1;
		$conditions 		= array('User.id' => AuthComponent::user('id'));
		$datos 				= $this->find('first',compact('conditions')); 
		return $datos['User']['state_conection'];
	}

	public function consult_notice_conection(){
		$this->recursive 	= -1;
		$conditions 		= array('User.id' => AuthComponent::user('id'));
		$datos 				= $this->find('first',compact('conditions')); 
		return $datos['User']['notice_conection'];
	}

	public function consult_notice_loguin(){
		$this->recursive 	= -1;
		$conditions 		= array('User.id' => AuthComponent::user('id'));
		$datos 				= $this->find('first',compact('conditions')); 
		return $datos['User']['notice_conection'];
	}

	// Metodo para no listar usuarios especificos
	public function not_list_id_users($dev = null){
		$users_not_list 		= array();
		$users_not_list[0] 		= 89;
		if (is_null($dev)) {
			$users_not_list[2] 		= 105;
		}		
		return $users_not_list;
	}
	
}
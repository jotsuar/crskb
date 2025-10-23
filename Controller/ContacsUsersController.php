<?php
App::uses('AppController', 'Controller');

class ContacsUsersController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$this->loadModel('User');
		$origen 					= Configure::read('variables.origenContact');
		$usuarios 		 			= $this->User->role_asesor_comercial_user_true();
		$get 						= $this->request->query;
		if (!empty($get)) {
			$conditions				= array('OR' => array(
									            'LOWER(ContacsUser.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
									            'LOWER(ContacsUser.email) LIKE' 		=> '%'.mb_strtolower($get['q']).'%'
									        )
										);
		} else {
			$conditions 			= array();
		}
		$order						= array('ContacsUser.id' => 'desc');
		$this->paginate 			= array(
										'order' 		=> $order,
							        	'limit' 		=> 19,
							        	'conditions' 	=> $conditions,
							    	);
		$users 						= $this->paginate('ContacsUser');
		$this->set(compact('users','usuarios','origen'));
	}

	public function list_contacts_bussines(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			$empresa 				= $this->ContacsUser->ClientsLegal->findById($this->request->data['empresa_id']);
			$consesiones 			= [];
			if (!empty($empresa["Concession"])) {
				$consesiones['0'] = 'Sin concesión';
				foreach ($empresa["Concession"] as $key => $value) {
					$consesiones[$value["id"]] = $value["name"];
				}			
			}
			$users 			= $this->ContacsUser->find_contacs_users_bussines($this->request->data['empresa_id']);
			$this->set(compact('users','consesiones'));
		}
	}

	public function list_contacts_bussines_select(){
		$this->layout 			= false;
		if ($this->request->is('ajax')) {
			$contac_id 			= 0;
			$servicio_id 		= $this->request->data['servicio_id'];
			if ($servicio_id != 0) {
				$contac_id 		= $this->ContacsUser->TechnicalService->get_contact($servicio_id);
			}
			$users 					= $this->ContacsUser->find_list_contacs_users($this->request->data['empresa_id'],true);
			$empresa 				= $this->ContacsUser->ClientsLegal->findById($this->request->data['empresa_id']);
			$consesiones 			= [];
			if (!empty($empresa["Concession"])) {
				$consesiones['0'] = 'Sin concesión';
				foreach ($empresa["Concession"] as $key => $value) {
					$consesiones[$value["id"]] = $value["name"];
				}			
			}
			$this->set(compact('users','contac_id','consesiones'));
		}
	}

	public function add() {
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$datos['ContacsUser']['name'] 				= $this->request->data['name'];
			$datos['ContacsUser']['telephone'] 			= $this->request->data['telephone'];
			$datos['ContacsUser']['cell_phone'] 		= $this->request->data['cell_phone'];
			if (isset($this->request->data['cityForm'])) {
				$datos['ContacsUser']['city'] 			= $this->request->data['cityForm'];
			} else {
				$datos['ContacsUser']['city'] 			= $this->request->data['city'];
			}
			$datos['ContacsUser']['email'] 				= $this->request->data['email'];
			$datos['ContacsUser']['concession_id'] 		= $this->request->data['concession_id'];
			$conditions			= array('OR' => array(
							            'LOWER(ContacsUser.telephone) LIKE' 	=> '%'.$datos['ContacsUser']['telephone'].'%',
							            'LOWER(ContacsUser.cell_phone) LIKE' 	=> '%'.$datos['ContacsUser']['telephone'].'%',
							            'LOWER(ContacsUser.telephone) LIKE' 	=> '%'.$datos['ContacsUser']['cell_phone'].'%',
							            'LOWER(ContacsUser.cell_phone) LIKE' 	=> '%'.$datos['ContacsUser']['cell_phone'].'%',
							            'LOWER(ContacsUser.email) LIKE' 		=> '%'.$datos['ContacsUser']['email'].'%'
							        )
								);

			$existe 									= $this->ContacsUser->find("count",["conditions"=>$conditions]);

			if ($existe == 0) {
				$datos['ContacsUser']['clients_legals_id'] 	= $this->request->data['empresa_id'];
            	$datos['ClientsNatural']['user_receptor']   = AuthComponent::user('id');
				$this->ContacsUser->create();
				if ($this->ContacsUser->save($datos)) {
					$id_colum 					= $this->ContacsUser->id;
					$this->saveDataLogsUser(2,'ContacsUser',$id_colum);
					return $this->encryptString($id_colum);
				}
			} else {
				return false;
			}
		}
	}

	public function add_user_form(){
		$this->layout 				= false;
		if ($this->request->is('ajax')) {
			$empresa_id 			= $this->request->data['empresa_id'];
			$empresa 				= $this->ContacsUser->ClientsLegal->findById($empresa_id);
			$consesiones 			= [];
			if (!empty($empresa["Concession"])) {
				foreach ($empresa["Concession"] as $key => $value) {
					$consesiones[$value["id"]] = $value["name"];
				}			
			}

			$this->set(compact('empresa_id','consesiones'));
		}
	}

	public function change_state(){
		$this->autoRender = false;
		if($this->request->is("ajax")){
			$id =  $this->request->data["id"];
			$this->ContacsUser->recursive = -1;
			$contact = $this->ContacsUser->findById($id);
			$contact["ContacsUser"]["state"] = $contact["ContacsUser"]["state"] == 1 ? 0 : 1;
			$this->ContacsUser->save($contact);
			$this->Session->setFlash(__('Cambio realizado correctamente.'),'Flash/success');
		}
	}

	public function add_contact_prospective(){
		$this->layout 				= false;
		if ($this->request->is('ajax')) {
			$empresa_id 			= $this->request->data['empresa_id'];
			$empresa 				= $this->ContacsUser->ClientsLegal->findById($this->request->data['empresa_id']);
			$consesiones 			= [];
			if (!empty($empresa["Concession"])) {
				$consesiones['0'] = 'Sin concesión';
				foreach ($empresa["Concession"] as $key => $value) {
					$consesiones[$value["id"]] = $value["name"];
				}			
			}
			$this->set(compact('empresa_id','consesiones'));
		}
	}

	public function edit_user_form(){
		$this->layout 				= false;
		if ($this->request->is('ajax')) {
			$datos 					= $this->ContacsUser->findById($this->request->data['contact_id']);
			$dataWo = false;
			if (isset($this->request->data["order"])) {
				$nit 	= explode("-", $datos["ClientsLegal"]["nit"]);
				$params = ["dni" => trim($nit[0]) ];
				$dataWo = $this->postWoApi($params,"customer");
				if ($dataWo === false) {
					$params = ["email" => $datos["ContacsUser"]["email"] ];
					$dataWo = $this->postWoApi($params,"customer");
				}
			}

			$empresa 				= $this->ContacsUser->ClientsLegal->findById($datos["ContacsUser"]["clients_legals_id"]);
			$consesiones 			= [];
			if (!empty($empresa["Concession"])) {
				foreach ($empresa["Concession"] as $key => $value) {
					$consesiones[$value["id"]] = $value["name"];
				}			
			}

			$contacto_id 			= $this->request->data['contact_id'];
			$flujo_id 				= empty($this->request->data['flujo_id']) ? null : $this->request->data['flujo_id'];
			$this->set(compact('contacto_id','datos','flujo_id','dataWo','consesiones'));
		}
	}

	public function editSave(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$datos['ContacsUser']['name']			= $this->request->data['name'];
			$datos['ContacsUser']['telephone']		= $this->request->data['telephone'];
			$datos['ContacsUser']['cell_phone']		= $this->request->data['cell_phone'];
			$datos['ContacsUser']['city']			= $this->request->data['city'];
			$datos['ContacsUser']['email']			= $this->request->data['email'];
			$datos['ContacsUser']['id']				= $this->request->data['contac_id'];
			$datos['ContacsUser']['concession_id'] 			= $this->request->data['concession_id'];
			$this->ContacsUser->save($datos);
			$this->saveDataLogsUser(3,'ContacsUser',$this->request->data['contac_id']);
			$this->Session->setFlash('Información guardada satisfactoriamente', 'Flash/success');
			return $this->encryptString( $this->ContacsUser->field("clients_legals_id",["id"=>$this->request->data['contac_id']]) );
		}
	}

	public function addUser(){
		$this->autoRender 			= false;
		if ($this->request->is('ajax')) {
			$datos['ContacsUser']['user_receptor'] 		    = AuthComponent::user("id");
			$datos['ContacsUser']['clients_legals_id'] 		= $this->request->data['empresa_id'];
			$datos['ContacsUser']['name'] 					= $this->request->data['name'];
			$datos['ContacsUser']['telephone'] 				= $this->request->data['telephone'];
			$datos['ContacsUser']['cell_phone'] 			= $this->request->data['cell_phone'];
			$datos['ContacsUser']['email'] 					= $this->request->data['email'];
			$datos['ContacsUser']['city'] 					= $this->request->data['cityForm'];
			$datos['ContacsUser']['concession_id'] 			= $this->request->data['concession_id'];


			$conditions			= array('OR' => array(
							            'LOWER(ContacsUser.telephone) LIKE' 	=> '%'.$datos['ContacsUser']['telephone'].'%',
							            'LOWER(ContacsUser.cell_phone) LIKE' 	=> '%'.$datos['ContacsUser']['telephone'].'%',
							            'LOWER(ContacsUser.telephone) LIKE' 	=> '%'.$datos['ContacsUser']['cell_phone'].'%',
							            'LOWER(ContacsUser.cell_phone) LIKE' 	=> '%'.$datos['ContacsUser']['cell_phone'].'%',
							            'LOWER(ContacsUser.email) LIKE' 		=> '%'.$datos['ContacsUser']['email'].'%'
							        )
								);

			$existe 									= $this->ContacsUser->find("count",["conditions"=>$conditions]);

			
			if ( AuthComponent::user("id") == 105 ||  $existe == 0) {
            	$datos['ClientsNatural']['user_receptor']   = AuthComponent::user('id');
				$this->ContacsUser->create();
				if ($this->ContacsUser->save($datos)) {
					$id_colum 					= $this->ContacsUser->id;
					$this->saveDataLogsUser(2,'ContacsUser',$id_colum);
					return $id_colum;
				}
			} else {
				return false;
			}
		}
	}

	public function addUserClientsLegal(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$datos['ContacsUser']['clients_legals_id'] 		= $this->request->data['empresa_id'];
			$datos['ContacsUser']['name'] 					= $this->request->data['name'];
			$datos['ContacsUser']['telephone'] 				= $this->request->data['telephone'];
			$datos['ContacsUser']['cell_phone'] 			= $this->request->data['cell_phone'];
			$datos['ContacsUser']['email'] 					= $this->request->data['email'];
			$datos['ContacsUser']['concession_id'] 			= $this->request->data['concession_id'];
			$datos['ContacsUser']['city'] 					= $this->request->data['cityForm'];
			$datos['ContacsUser']['user_receptor'] 			= AuthComponent::user('id');
			$existe 										= $this->ContacsUser->exist_email($this->request->data['email']);
			if ($existe == 0) {
            	$datos['ClientsNatural']['user_receptor']   = AuthComponent::user('id');
				$this->ContacsUser->create();
				if ($this->ContacsUser->save($datos)) {
					$id_colum 					= $this->ContacsUser->id;
					$this->saveDataLogsUser(2,'ContacsUser',$id_colum);
					return $this->encryptString($this->request->data['empresa_id']);
				}
			} else {
				return false;
			}

		}
	}

	public function validExistencia(){
		$this->autoRender 											= false;
		if ($this->request->is('ajax')) {
			return $this->ContacsUser->exist_email($this->request->data['email']);
		}
	}

	public function list_contacts_bussines_option(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			$user 			= $this->ContacsUser->findById($this->request->data['nuevo_id']);
			$this->set(compact('user'));
		}
	}

	public function getData(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$datos 			= $this->ContacsUser->get_data($this->request->data['contact_id']);
			return json_encode($datos['ContacsUser']);
		}
	}

	public function edit() {
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$datos['ContacsUser']['name'] = $this->request->data['name'];
			$datos['ContacsUser']['telephone'] = $this->request->data['telephone'];
			$datos['ContacsUser']['cell_phone'] = $this->request->data['cell_phone'];
			$datos['ContacsUser']['city'] = $this->request->data['city'];
			$datos['ContacsUser']['email'] = $this->request->data['email'];
			$datos['ContacsUser']['id'] = $this->request->data['id_contact'];
			if ($this->ContacsUser->save($datos)) {
				$this->saveDataLogsUser(3,'ContacsUser',$this->request->data['id_contact']);
			}
		}
	}

	public function view($id){
		$id 				= $this->desencriptarCadena($id);
		if (!$this->ContacsUser->exists($id)) {
			throw new NotFoundException('El contacto no existe');
		}
		$this->loadModel('User');
		$contact 							= $this->ContacsUser->get_data($id);
		$origen 							= Configure::read('variables.origenContact');
		$usuarios 		 					= $this->User->role_asesor_comercial_user_true();
		$count_flujos_clients_juridico 		= $this->ContacsUser->ProspectiveUser->count_flujos_clients_juridico($contact['ContacsUser']['id']);
		$count_cotizaciones_enviadas 		= $this->ContacsUser->ProspectiveUser->FlowStage->count_cotizaciones_enviadas_clients_juridico($contact['ContacsUser']['id']);
		$cotizaciones_enviadas 				= $this->ContacsUser->ProspectiveUser->FlowStage->cotizaciones_enviadas_clients_juridico($contact['ContacsUser']['id']);
		$count_negocios_realizados		 	= $this->ContacsUser->ProspectiveUser->FlowStage->count_flujos_true_clients_juridico($contact['ContacsUser']['id']);
		$pagosVerificadosFlujos		 		= $this->ContacsUser->ProspectiveUser->FlowStage->payments_total_clients_juridico($contact['ContacsUser']['id']);
		$total_dinero_negocios 				= $this->totalDineroQuotation($pagosVerificadosFlujos);
		$negocios_realizados		 		= $this->ContacsUser->ProspectiveUser->FlowStage->flujos_true_clients_juridico($contact['ContacsUser']['id']);
		$requerimientos_cliente 			= $this->ContacsUser->ProspectiveUser->flujos_clients_juridico($contact['ContacsUser']['id']);
		$this->loadModel("TechnicalService");
		$servicio_tecnico 					= $this->TechnicalService->data_contacts_products($id);
		$this->set(compact('contact','origen','usuarios','count_flujos_clients_juridico','count_cotizaciones_enviadas','count_negocios_realizados','total_dinero_negocios','negocios_realizados','cotizaciones_enviadas','requerimientos_cliente','servicio_tecnico'));
	}

	public function totalDineroQuotation($pagosVerificadosFlujos){
		$total_dinero_negocios 		= 0;
		if ($pagosVerificadosFlujos != 0) {
			$totalVerificadosFlujos 		= $this->ContacsUser->ProspectiveUser->FlowStage->calculate_total_sales($pagosVerificadosFlujos);
			for ($i=0; $i < count($totalVerificadosFlujos); $i++) { 
				$total_dinero_negocios = (int)$totalVerificadosFlujos[$i]['priceQuotation'] + $total_dinero_negocios;
			}
		}
		return $total_dinero_negocios;
	}

}
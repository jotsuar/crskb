<?php
App::uses('AppController', 'Controller');

class ClientsNaturalsController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$this->validateTimes();
		$this->loadModel('User');
		$origen 					= Configure::read('variables.origenContact');
		$usuarios 		 			= $this->User->role_asesor_comercial_user_true();

		$get 		   = isset($this->request->query["q"]) && !empty($this->request->query["q"]) ? $this->request->query : array() ;
		$rolesPayments = array(Configure::read('variables.roles_usuarios.Gerente General') );

		if(in_array(AuthComponent::user('role'), $rolesPayments) && empty($get)){
        	$conditions 				= array();
        }elseif(!empty($get)){
        	if(strlen($get["q"]) >=  3 || in_array(AuthComponent::user('role'), $rolesPayments) ){
				$conditions	= array('OR' => 
					array(
			            'LOWER(ClientsNatural.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
			            'LOWER(ClientsNatural.email) LIKE' 			=> '%'.mb_strtolower($get['q']).'%'
			        )
				);
        	}else{
        		$conditions 			= array("ClientsNatural.id" => 0);
        	}
		} else {
			if (AuthComponent::user("role") != "Asesor Externo") {
				$conditions 			= array("ClientsNatural.id" => 0);
			}
		}

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["OR"] = [["ClientsNatural.user_receptor" => AuthComponent::user("id")]];

			$this->loadModel("ClientsUser");
			$clientsAsign = $this->ClientsUser->find("list",["conditions"=>["user_id"=>AuthComponent::user("id"), "clients_natural_id !=" => null],"fields"=>["clients_natural_id","clients_natural_id"]]);
			if (!empty($clientsAsign)) {
				$conditions["OR"][] = ["ClientsNatural.id" => array_values($clientsAsign) ];
			}

		}

		$order						= array('ClientsNatural.id' => 'desc');
		$this->paginate 			= array(
										'order' 		=> $order,
							        	'limit' 		=> 19,
							        	'conditions' 	=> $conditions,
							    	);
		$clientsNaturals 			= $this->paginate('ClientsNatural');
		$this->set(compact('clientsNaturals','origen','usuarios','get','rolesPayments'));
	}

	public function get_data_client(){
		$this->autoRender = false;
		$this->ClientsNatural->recursive = -1;
		$data = $this->ClientsNatural->findById($this->request->data["customer_id"])["ClientsNatural"];
		return json_encode($data);
	}

	public function view($id = null) {
		$this->validateTimes();
		$id 				= $this->desencriptarCadena($id);
		if (!$this->ClientsNatural->exists($id)) {
			throw new NotFoundException('El cliente natural no existe');
		}

		if ($this->request->is("post") || $this->request->is("put")) {
			$this->loadModel("ProspectiveUser");
			$this->ProspectiveUser->updateAll(
				["ProspectiveUser.clients_natural_id" => $this->request->data["ClientsNatural"]["other_id"] ],
				["ProspectiveUser.clients_natural_id" => $this->request->data["ClientsNatural"]["delete_id"] ]
			);

			$this->ClientsNatural->delete($this->request->data["ClientsNatural"]["delete_id"]);
			$this->Session->setFlash('La información se ha guardado satisfactoriamente', 'Flash/success');
			$this->redirect(["action"=>"view", $this->encryptString($this->request->data["ClientsNatural"]["other_id"])]);
		}

		$this->loadModel('User');
		$origen 							= Configure::read('variables.origenContact');
		$usuarios 							= $this->User->role_asesor_comercial_user_true();
		$clientsNaturals 					= $this->ClientsNatural->get_data($id);
		$count_flujos						= $this->ClientsNatural->ProspectiveUser->count_flujos_clients_natural($id);
		$count_cotizaciones_enviadas 		= $this->ClientsNatural->ProspectiveUser->FlowStage->count_cotizaciones_enviadas_clients_natural($id);
		$cotizaciones_enviadas 				= $this->ClientsNatural->ProspectiveUser->FlowStage->cotizaciones_enviadas_clients_natural($id);
		$count_negocios_realizados		 	= $this->ClientsNatural->ProspectiveUser->FlowStage->count_flujos_true_clients_natural($id);
		$negocios_realizados		 		= $this->ClientsNatural->ProspectiveUser->FlowStage->flujos_true_clients_natural($id);
		$pagosVerificadosFlujos		 		= $this->ClientsNatural->ProspectiveUser->FlowStage->payments_total_clients_natural($id);
		$total_dinero_negocios 				= $this->totalDineroQuotation($pagosVerificadosFlujos);
		$requerimientos_cliente 			= $this->ClientsNatural->ProspectiveUser->flujos_clients_natural($id);
		$servicio_tecnico 					= $this->ClientsNatural->TechnicalService->data_natural_products($id);

		$partsNit = explode(" ", str_replace("-", " ", trim($clientsNaturals["ClientsNatural"]["identification"]) ) );

		try {
			$dataCupo = $this->postWoApi(["dni"=>$partsNit[0]],"customers");			
		} catch (Exception $e) {
			$dataCupo = [];
		}

		$otherClients = $this->ClientsNatural->find("list",["conditions"=> ["ClientsNatural.id !="=>$id], "fields" => ["id","full_name"] ]);

		$this->set(compact('clientsNaturals','origen','usuarios','count_flujos','count_cotizaciones_enviadas','count_negocios_realizados','total_dinero_negocios','cotizaciones_enviadas','negocios_realizados','requerimientos_cliente','servicio_tecnico','dataCupo','otherClients'));
	}

	public function totalDineroQuotation($pagosVerificadosFlujos){
		$total_dinero_negocios 		= 0;

		if ($pagosVerificadosFlujos != 0) {
			try {
				$totalVerificadosFlujos = $this->ClientsNatural->ProspectiveUser->FlowStage->calculate_total_sales($pagosVerificadosFlujos);
				for ($i=0; $i < count($totalVerificadosFlujos); $i++) { 
					$price = isset($totalVerificadosFlujos[$i]['priceQuotation']) ? $totalVerificadosFlujos[$i]['priceQuotation'] : $totalVerificadosFlujos["FlowStage"]['priceQuotation'];
					$total_dinero_negocios = (int)$price + $total_dinero_negocios;
				}
			} catch (Exception $e) {
				$total_dinero_negocios = 0;
			}
		}

		return $total_dinero_negocios;
	}

	public function add() {
		$this->layout 						= false;
	}

	public function add_customer(){
		$this->layout = false;
		$this->set("idClient", $this->request->data["identification"]);
		$this->set("emailClient", $this->request->data["email"]);
		$dataWo = false;

		if (!empty($this->request->data["identification"]) || !empty($this->request->data["email"]) ) {
			$params = [];

			if (!empty($this->request->data["identification"])) {
				$params["dni"] = $this->request->data["identification"];
			}

			if (!empty($this->request->data["email"])) {
				$params["email"] = $this->request->data["email"];
			}

			$dataWo = $this->postWoApi($params,"customer");
		}

		$this->set("dataWo",$dataWo);
	}

	public function add_customer_post(){
		$this->autoRender = false;

		if (isset($this->request->data["ClientsNatural"]["document"]) && !empty($this->request->data["ClientsNatural"]["document"]["name"])) {
			$this->loadDocumentPdf($this->request->data["ClientsNatural"]["document"],"clientes_documentos");
			$this->request->data["ClientsNatural"]["document"] = $this->Session->read("documentoModelo");
		}else{
			unset($this->request->data["ClientsNatural"]["document"]);
		}

		if (isset($this->request->data["ClientsNatural"]["document_2"]) && !empty($this->request->data["ClientsNatural"]["document_2"]["name"])) {
			$this->loadPhoto($this->request->data["ClientsNatural"]["document_2"],"clientes_documentos");
			$this->request->data["ClientsNatural"]["document_2"] = $this->Session->read("imagenModelo");
		}else{
			unset($this->request->data["ClientsNatural"]["document_2"]);
		}

		if (isset($this->request->data["ClientsNatural"]["identification"]) && !empty($this->request->data["ClientsNatural"]["identification"]) ) {
			$actualUser = $this->ClientsNatural->findByIdentification($this->request->data["ClientsNatural"]["identification"]);
			if (!empty($actualUser)) {
				return json_encode(array("id" => $actualUser["ClientsNatural"]["id"], "uid" => $this->encryptString( $actualUser["ClientsNatural"]["id"] )));
			}
		}
		
		$continue = true;
		if(isset($this->request->query["especial_api"])){
			$existsCustomer = null;
			$existsCustomerTwo = null;
			if(!empty($this->request->data["ClientsNatural"]["telephone"])){
				$existsCustomer = $this->getClientByPhone($this->request->data["ClientsNatural"]["telephone"]);
			}
			if(!empty($this->request->data["ClientsNatural"]["cell_phone"])){
				$existsCustomerTwo = $this->getClientByPhone($this->request->data["ClientsNatural"]["cell_phone"]);
			}

			if( !is_null($existsCustomer) || !is_null($existsCustomerTwo) ){
				$continue = false;
			}
		}

		if($continue){			
			$this->ClientsNatural->create();
			$this->request->data["ClientsNatural"]["user_receptor"] = AuthComponent::user("id");
			$this->ClientsNatural->save($this->request->data);
			$clientId = $this->ClientsNatural->id;
			$data_return = array("error"=>0,"id" => $clientId, "uid" => $this->encryptString($clientId));
		}else{
			$data_return = array("error" => 1);
		}

		return json_encode($data_return);
	}

	public function edit() {
		$this->layout 						= false;
		if ($this->request->is('ajax')) {
			$clientsNaturals 			= $this->ClientsNatural->get_data($this->request->data['cliente_id']);
			$this->set(compact('clientsNaturals'));
		}
	}

	public function edit_flujos() {
		$this->layout 						= false;
		if ($this->request->is('ajax')) {
			$flujo_id 						= $this->request->data['flujo_id'];
			$clientsNaturals 				= $this->ClientsNatural->get_data($this->request->data['cliente_id']);
			$dataWo 						= false;

			if (isset($this->request->data["identification"])) {
				$params = ["dni" => trim($this->request->data["identification"]) ];
				$dataWo = $this->postWoApi($params,"customer");
			}

			$this->set(compact('clientsNaturals','flujo_id','dataWo'));
		}
	}

	public function addSave(){
		$this->autoRender 								= false;
		if ($this->request->is('ajax')) {

			if (!isset($this->request->data["ClientsNatural"])) {
				$this->request->data = ["ClientsNatural" => $this->request->data];
			}

			if (!isset($this->request->data["ClientsNatural"]['action']) && isset($this->request->data["ClientsNatural"]['view'])) {
				$this->request->data["ClientsNatural"]['action'] = $this->request->data["ClientsNatural"]['view'];
			}

			$datos  = $this->request->data;

			$datos['ClientsNatural']['user_receptor']	= AuthComponent::user("id");
			if ($this->request->data["ClientsNatural"]['action'] == 'add') {
				$existe = $this->ClientsNatural->exist_email($datos['ClientsNatural']['email']);

				if (isset($datos["ClientsNatural"]["identification"]) && !empty($datos["ClientsNatural"]["identification"]) ) {
					$actualUser = $this->ClientsNatural->findByIdentification($datos["ClientsNatural"]["identification"]);
					if (!empty($actualUser)) {
						$existe = 1;
					}
				}

			} else {
				$existe = 0;
			}
			if ($existe == 0) {
				if (!isset($this->request->data["ClientsNatural"]["id"]) || $this->request->data['ClientsNatural']["id"] != '') {
					$add 													= false;
				} else {
            		$datos['ClientsNatural']['user_receptor']               = AuthComponent::user('id');
					$this->ClientsNatural->create();
					$add 													= true;
				}

				if (isset($datos["ClientsNatural"]["document"]) && !empty($datos["ClientsNatural"]["document"]["name"])) {
					$this->loadDocumentPdf($datos["ClientsNatural"]["document"],"clientes_documentos");
					$datos["ClientsNatural"]["document"] = $this->Session->read("documentoModelo");
				}else{
					unset($datos["ClientsNatural"]["document"]);
				}

				if (isset($datos["ClientsNatural"]["document_2"]) && !empty($datos["ClientsNatural"]["document_2"]["name"])) {
					$this->loadPhoto($datos["ClientsNatural"]["document_2"],"clientes_documentos");
					$datos["ClientsNatural"]["document_2"] = $this->Session->read("imagenModelo");
				}else{
					unset($datos["ClientsNatural"]["document_2"]);
				}

				if ($this->ClientsNatural->save($datos)) {
					if ($add) {
						$id_colum 					= $this->ClientsNatural->id;
						$this->saveDataLogsUser(2,'ClientsNatural',$id_colum);
					} else {
						$id_colum 					= $this->ClientsNatural->id;
						$this->saveDataLogsUser(3,'ClientsNatural',$this->ClientsNatural->id);
					}
					return $this->encryptString($id_colum);
				}
			} else {
				return false;
			}
		}
	}

	public function addSaveFlujos(){
		$this->autoRender 								= false;
		if ($this->request->is('ajax')) {
			$datos = $this->request->data;
			$datos['ClientsNatural']['user_receptor']	= AuthComponent::user("id");

			if (isset($datos["ClientsNatural"]["document"]) && !empty($datos["ClientsNatural"]["document"]["name"])) {
				$this->loadDocumentPdf($datos["ClientsNatural"]["document"],"clientes_documentos");
				$datos["ClientsNatural"]["document"] = $this->Session->read("documentoModelo");
			}else{
				unset($datos["ClientsNatural"]["document"]);
			}

			if (isset($datos["ClientsNatural"]["document_2"]) && !empty($datos["ClientsNatural"]["document_2"]["name"])) {
				$this->loadPhoto($datos["ClientsNatural"]["document_2"],"clientes_documentos");
				$datos["ClientsNatural"]["document_2"] = $this->Session->read("imagenModelo");
			}else{
				unset($datos["ClientsNatural"]["document_2"]);
			}

			if($this->ClientsNatural->save($datos)){
				$this->saveDataLogsUser(3,'ClientsNatural',$this->request->data["ClientsNatural"]['id']);
				$this->Session->setFlash('Información guardada satisfactoriamente', 'Flash/success');
				return true;
			}
			$this->Session->setFlash('La información no fue guardada satisfactoriamente', 'Flash/error');
		}
	}

	public function validExistencia(){
		$this->autoRender 											= false;
		if ($this->request->is('ajax')) {
			return $this->ClientsNatural->exist_email($this->request->data['email']);
		}
	}

	public function list_clients_natural_option(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {

			if(!is_numeric($this->request->data["nuevo_id"])){
				$this->request->data["nuevo_id"] = $this->desencriptarCadena($this->request->data["nuevo_id"]);
			}
			$user 			= $this->ClientsNatural->get_data($this->request->data['nuevo_id']);
			$this->set(compact('user'));
		}
	}

	public function products_repair($id = null){
		$id_natural						= $this->desencriptarCadena($id);
		if (!$this->ClientsNatural->exists($id_natural)) {
			throw new NotFoundException('El cliente natural no existe');
		}
		$servicio_tecnico 				= $this->ClientsNatural->TechnicalService->data_natural_products($id_natural);
		$this->set(compact('servicio_tecnico'));
	}
}
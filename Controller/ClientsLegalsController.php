<?php
App::uses('AppController', 'Controller');

class ClientsLegalsController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$this->validateTimes();
		$get 		   = isset($this->request->query["q"]) && !empty($this->request->query["q"]) ? $this->request->query : array() ;
		$rolesPayments = array(Configure::read('variables.roles_usuarios.Gerente General') );

        if(in_array(AuthComponent::user('role'), $rolesPayments) && empty($get)){
        	$conditions 				= array();
        }elseif(!empty($get)){
        	if(strlen($get["q"]) >=  3 || in_array(AuthComponent::user('role'), $rolesPayments) ){
				$conditions	= array('OR' => 
					array(
					        'LOWER(ClientsLegal.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
					        'LOWER(ClientsLegal.nit) LIKE'	 			=> '%'.mb_strtolower($get['q']).'%',
					        'LOWER(Parent.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
					        'LOWER(Parent.nit) LIKE'	 			=> '%'.mb_strtolower($get['q']).'%'
					    )
				);
        	}else{
        		$conditions 			= array("ClientsLegal.id" => 0);
        	}
		} else {
			if (AuthComponent::user("role") != "Asesor Externo") {
				$conditions 			= array("ClientsLegal.id" => 0);
			}
		}

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["OR"] = [ ["ClientsLegal.user_receptor" => AuthComponent::user("id")]];
			$this->loadModel("ClientsUser");
			$clientsAsign = $this->ClientsUser->find("list",["conditions"=>["user_id"=>AuthComponent::user("id"), "clients_legal_id !=" => null],"fields"=>["clients_legal_id","clients_legal_id"]]);
			if (!empty($clientsAsign)) {
				$conditions["OR"][] = ["ClientsLegal.id" => array_values($clientsAsign) ];
			}
		}

		$order						= array('ClientsLegal.id' => 'desc');
		$this->paginate 			= array(
										'order' 		=> $order,
							        	'limit' 		=> 19,
								        'conditions' 	=> $conditions,
								    );
		$clientsLegals   			= $this->Paginate("ClientsLegal");


		$this->set(compact('clientsLegals',"get","rolesPayments"));
	}

	public function add_customer_general(){
		$this->layout = false;
	}


	public function validateCustomer(){
		$this->autoRender 	= false;
		$response 			= null;
		$recursive			= -1;
		if($this->request->data["type"] == Configure::read("CUSTOMERS_TYPE.LEGAL")){

			$conditions 	= array("OR" => array("nit" => $this->request->data["identification"]));
			$conditionsArr 	= explode(" ", $this->request->data["identification"]);

			if(count($conditionsArr) == 2){
				$conditions["OR"][] = array("nit" => $conditionsArr[0]);				
			}

			$customerLegal = $this->ClientsLegal->find("first", compact("conditions","recursive"));

			if(!empty($customerLegal)){
				$response = array("id" => $customerLegal["ClientsLegal"]["id"], "idEncrypt" => $this->encryptString($customerLegal["ClientsLegal"]["id"]), "type" => "legal" );
			}
		} else{
			$this->loadModel("ClientsNatural");
			$conditions = array();
			if(!empty($this->request->data["identification"])){
				$conditions["OR"]["identification"] = $this->request->data["identification"];
			}
			if(!empty($this->request->data["email"])){
				$conditions["OR"]["email"] = $this->request->data["email"];
			}

			$customerNatural = $this->ClientsNatural->find("first", compact("conditions","recursive"));
			if(!empty($customerNatural)){
				$response = array("id" => $customerNatural["ClientsNatural"]["id"], "idEncrypt" => $this->encryptString($customerNatural["ClientsNatural"]["id"]), "type" => "natural" );
			}
		}
		return json_encode($response);
	}

	public function add_customer_legal(){
		$this->layout = false;
		$nit = $this->request->data["nit"];
		$dataWo = false;

		if (!empty($this->request->data["nit"])) {
			$params = [];
			if (!empty($this->request->data["nit"])) {
				$params["dni"] = $this->request->data["nit"];
			}
			$dataWo = $this->postWoApi($params,"customer");
		}

		$legals = $this->ClientsLegal->find("list",["conditions"=>["parent_id" => null]]);
		$this->set("legals",$legals);

		$this->set("nit",$nit);
		$this->set("dataWo",$dataWo);
	}

	public function add_customer_legal_post(){
		$this->autoRender = false;

		$response = null;

		if($this->request->is("ajax") && $this->request->is("post")){
			$this->ClientsLegal->create();
			$this->request->data["ClientsLegal"]["user_receptor"] 		= AuthComponent::user("id");

			if (isset($this->request->data["ClientsLegal"]["document"]) && !empty($this->request->data["ClientsLegal"]["document"]["name"])) {
				$this->loadDocumentPdf($this->request->data["ClientsLegal"]["document"],"clientes_documentos");
				$this->request->data["ClientsLegal"]["document"] = $this->Session->read("documentoModelo");
			}else{
				unset($this->request->data["ClientsLegal"]["document"]);
			}

			if (isset($this->request->data["ClientsLegal"]["document_2"]) && !empty($this->request->data["ClientsLegal"]["document_2"]["name"])) {
				$this->loadPhoto($this->request->data["ClientsLegal"]["document_2"],"clientes_documentos");
				$this->request->data["ClientsLegal"]["document_2"] = $this->Session->read("imagenModelo");
			}else{
				unset($this->request->data["ClientsLegal"]["document_2"]);
			}

			$continue = true;
			if(isset($this->request->query["especial_api"])){
				$existsCustomer = null;
				$existsCustomerTwo = null;
				if(!empty($this->request->data["ContacsUser"]["telephone"])){
					$existsCustomer = $this->getClientByPhone($this->request->data["ContacsUser"]["telephone"]);
				}
				if(!empty($this->request->data["ContacsUser"]["cell_phone"])){
					$existsCustomerTwo = $this->getClientByPhone($this->request->data["ContacsUser"]["cell_phone"]);
				}

				if( !is_null($existsCustomer) || !is_null($existsCustomerTwo) ){
					$continue = false;
				}
			}

			if($continue){	
				$this->ClientsLegal->save($this->request->data["ClientsLegal"]);
				$clients_legals_id = $this->ClientsLegal->id;

				$this->request->data["ContacsUser"]["clients_legals_id"] 	= $clients_legals_id;
				$this->request->data["ContacsUser"]["user_receptor"] 		= AuthComponent::user("id");

				$this->ClientsLegal->ContacsUser->create();
				$this->ClientsLegal->ContacsUser->save($this->request->data["ContacsUser"]);
				$this->Session->setFlash('La información se ha guardado satisfactoriamente', 'Flash/success');
				$response = json_encode(array("error" => 0,"id" => $clients_legals_id, "uid" => $this->encryptString($clients_legals_id)));
			}else{
				$response = json_encode(array("error" => 1));
			}
		}

		return $response;

	}


	public function view($id = null) {
		// $this->validateTimes();
		$ids = $id;
		$id 				= $this->desencriptarCadena($id);
		if (!$this->ClientsLegal->exists($id)) {
			throw new NotFoundException('El cliente juridico no existe');
		}

		if ($this->request->is("post") || $this->request->is("put")) {
			$this->ClientsLegal->ContacsUser->updateAll(
				["ContacsUser.clients_legals_id" => $this->request->data["ClientsLegal"]["other_id"] ],
				["ContacsUser.clients_legals_id" => $this->request->data["ClientsLegal"]["delete_id"] ]
			);

			$this->ClientsLegal->delete($this->request->data["ClientsLegal"]["delete_id"]);
			$this->Session->setFlash('La información se ha guardado satisfactoriamente', 'Flash/success');
			$this->redirect(["action"=>"view", $this->encryptString($this->request->data["ClientsLegal"]["other_id"])]);
		}

		$this->loadModel('User');
		$origen 							= Configure::read('variables.origenContact');
		$usuarios 							= $this->User->role_asesor_comercial_user_true();
		$clientsLegal 						= $this->ClientsLegal->findById($id);
		$id_contacs_user_bussines 			= $this->ClientsLegal->ContacsUser->id_contacs_user_bussines($id);
		$count_flujos_clients_juridico 		= $this->ClientsLegal->ContacsUser->ProspectiveUser->count_flujos_clients_juridico($id_contacs_user_bussines);
		$count_cotizaciones_enviadas 		= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->count_cotizaciones_enviadas_clients_juridico($id_contacs_user_bussines);
		$count_negocios_realizados		 	= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->count_flujos_true_clients_juridico($id_contacs_user_bussines);
		$pagosVerificadosFlujos		 		= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->payments_total_clients_juridico($id_contacs_user_bussines);
		

		$cotizaciones_enviadas 				= [];


		// $total_dinero_negocios 				= $this->totalDineroQuotation($pagosVerificadosFlujos);
		$total_dinero_negocios 				= 0;
		// $negocios_realizados		 		= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->flujos_true_clients_juridico($id_contacs_user_bussines);
		$negocios_realizados		 		= [];
		// $requerimientos_cliente 			= $this->ClientsLegal->ContacsUser->ProspectiveUser->flujos_clients_juridico($id_contacs_user_bussines);
		$requerimientos_cliente 			= [];


		
		// $servicio_tecnico 					= $this->TechnicalService->data_legal_products($id);
		$servicio_tecnico 					= [];

		$partsNit = explode(" ", str_replace("-", " ", trim($clientsLegal["ClientsLegal"]["nit"]) ) );

		try {
			$dataCupo = $this->postWoApi(["dni"=>$partsNit[0]],"customers");			
		} catch (Exception $e) {
			$dataCupo = [];
		}

		// $dataCupo = [];

		$otherClients = $this->ClientsLegal->find("list",["fields"=>["id","full_name"],"conditions" => ["ClientsLegal.id !=" => $id ] ]);

		$consesiones 			= [];
		if (!empty($clientsLegal["Concession"])) {
			foreach ($clientsLegal["Concession"] as $key => $value) {
				$consesiones[$value["id"]] = $value["name"];
			}			
		}

		$this->set(compact('clientsLegal','origen','usuarios','count_flujos_clients_juridico','count_cotizaciones_enviadas','count_negocios_realizados','total_dinero_negocios','cotizaciones_enviadas','negocios_realizados','requerimientos_cliente','servicio_tecnico','dataCupo','otherClients','ids','consesiones'));
	}

	public function getFlowsClient($id){
		$this->layout 				= false;
		$id 						= $this->desencriptarCadena($id);
		$id_contacs_user_bussines 	= $this->ClientsLegal->ContacsUser->id_contacs_user_bussines($id);
		$requerimientos_cliente 	= $this->ClientsLegal->ContacsUser->ProspectiveUser->flujos_clients_juridico($id_contacs_user_bussines);
		$this->set(compact('requerimientos_cliente'));
		$this->render('/ClientsLegals/partials/flows');
	}

	public function getFlowsQuotations($id){
		$this->layout 				= false;
		$id 						= $this->desencriptarCadena($id);
		$id_contacs_user_bussines 	= $this->ClientsLegal->ContacsUser->id_contacs_user_bussines($id);
		$cotizaciones_enviadas 		= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->cotizaciones_enviadas_clients_juridico($id_contacs_user_bussines);
		$this->set(compact('cotizaciones_enviadas'));
		$this->render('/ClientsLegals/partials/quotations');
	}

	public function getTechnicals($id){
		$this->loadModel("TechnicalService");
		$this->layout 				= false;
		$id 						= $this->desencriptarCadena($id);
		$id_contacs_user_bussines 	= $this->ClientsLegal->ContacsUser->id_contacs_user_bussines($id);
		$servicio_tecnico 			= $this->TechnicalService->data_legal_products($id);
		$this->set(compact('servicio_tecnico'));
		$this->render('/ClientsLegals/partials/technicals');
	}

	public function getSells($id){
		$this->layout 				= false;
		$id 						= $this->desencriptarCadena($id);
		$id_contacs_user_bussines 	= $this->ClientsLegal->ContacsUser->id_contacs_user_bussines($id);
		$negocios_realizados		= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->flujos_true_clients_juridico($id_contacs_user_bussines);
		$this->set(compact('negocios_realizados'));
		$this->render('/ClientsLegals/partials/sells');
	}

	public function totalDineroQuotation($pagosVerificadosFlujos){
		$total_dinero_negocios 		= 0;
		if ($pagosVerificadosFlujos != 0) {
			$totalVerificadosFlujos 		= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->calculate_total_sales($pagosVerificadosFlujos);
			if(!empty($totalVerificadosFlujos) && isset($totalVerificadosFlujos[0])){				
				for ($i=0; $i < count($totalVerificadosFlujos); $i++) { 
					$total_dinero_negocios = (int)$totalVerificadosFlujos[$i]['priceQuotation'] + $total_dinero_negocios;
				}
			}
		}
		return $total_dinero_negocios;
	}

	public function add() {
		$this->validateTimes();
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$datos['ClientsLegal']['name']							= $this->request->data['name'];
			$datos['ClientsLegal']['nit']							= $this->request->data['nit'];
            $datos['ClientsLegal']['user_receptor']              	= AuthComponent::user('id');
			if ($this->ClientsLegal->exist_nit($datos['ClientsLegal']['nit']) == 0) {
				$this->ClientsLegal->create();
				if ($this->ClientsLegal->save($datos)) {
					$id_colum 					= $this->ClientsLegal->id;
					$this->saveDataLogsUser(2,'ClientsLegal',$id_colum);
					return $this->encryptString($id_colum);
				}
			} else {
				return false;
			}
		}
	}

	public function add_new(){
		$this->layout 						= false;
	}

	public function edit() {
		$this->layout 						= false;
		if ($this->request->is('ajax')) {
			$datos 							= $this->ClientsLegal->get_data($this->request->data['empresa_id']);
			$legals = $this->ClientsLegal->find("list",["conditions"=>["parent_id" => null]]);
			$this->set("legals",$legals);
			$this->set(compact('datos'));
		}
	}

	public function saveEdit(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {

			if (!isset($this->request->data["ClientsLegal"])) {
				$this->request->data = ["ClientsLegal" => $this->request->data];
			}

			$datos = $this->request->data;
			if ($this->request->data["ClientsLegal"]['client_id'] == '') {
            	$datos['ClientsLegal']['user_receptor'] = AuthComponent::user('id');
				$this->ClientsLegal->create();
				$add 				= true;
				$valid 				= false;
			} else {
				$datos['ClientsLegal']['id']		= $this->request->data['ClientsLegal']['client_id'];
				$add 				= false;
				$valid 				= true;
			}
			if (!$valid) {
				if ($this->ClientsLegal->exist_nit($datos['ClientsLegal']['nit']) == 0) {
					$valid 			= true;
				}
			}

			if (isset($datos["ClientsLegal"]["document"]) && !empty($datos["ClientsLegal"]["document"]["name"])) {
				$this->loadDocumentPdf($datos["ClientsLegal"]["document"],"clientes_documentos");
				$datos["ClientsLegal"]["document"] = $this->Session->read("documentoModelo");
			}else{
				unset($datos["ClientsLegal"]["document"]);
			}

			if (isset($datos["ClientsLegal"]["document_2"]) && !empty($datos["ClientsLegal"]["document_2"]["name"])) {
				$this->loadPhoto($datos["ClientsLegal"]["document_2"],"clientes_documentos");
				$datos["ClientsLegal"]["document_2"] = $this->Session->read("imagenModelo");
			}else{
				unset($datos["ClientsLegal"]["document_2"]);
			}

			if ($valid) {
				if ($this->ClientsLegal->save($datos)) {
					if ($add) {
						$id_colum = $this->ClientsLegal->id;
						$this->saveDataLogsUser(2,'ClientsLegal',$id_colum);
					} else {
						$id_colum 					= $this->request->data['ClientsLegal']['client_id'];
						$this->saveDataLogsUser(3,'ClientsLegal',$this->request->data['ClientsLegal']['client_id']);
					}
					return $this->encryptString($id_colum);
				}
			} else {
				return false;
			}
		}
	}

	public function validExistencia(){
		$this->autoRender 											= false;
		if ($this->request->is('ajax')) {
			return $this->ClientsLegal->exist_nit($this->request->data['nit']);
		}
	}

	public function list_clients_legal_option(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			if(!is_numeric($this->request->data["nuevo_id"])){
				$this->request->data["nuevo_id"] = $this->desencriptarCadena($this->request->data["nuevo_id"]);
			}
			$user 			= $this->ClientsLegal->get_data($this->request->data['nuevo_id']);
			$this->set(compact('user'));
		}
	}

}

<?php

App::uses('AppController', 'Controller');

class ClientesController extends AppController {

	public $uses = ["Order","Shipping"];

	public function beforeFilter() {
        parent::beforeFilter();
        if (AuthComponent::user("id") && !in_array($this->request->action,['find_cliente_phone','find_cliente_detail','deudas','factura','sendMessagePayment','facturas_mora','next_dead'])) {
        	$this->Session->write("Auth",null);
        	$this->redirect(["action"=>"index"]);	
        }
        $this->Auth->allow('index','login','valid_existencia','valid_code','view','shippings','view_shipping','logout','find_cliente_phone','find_cliente_detail','deudas','factura','sendMessagePayment','facturas_mora','next_dead');
    }

    public function facturas_mora($clienteID){
    	$clienteID = $this->decrypt($clienteID);
    	$data = $this->postWoApi(["dni"=>$clienteID],"customers");

    	if($data == false ){
    		throw new NotFoundException(__('Información Inválida'));
    	}

    	$this->set("data",$data);


    }

    public function sendMessagePayment(){
    	$this->autoRender = false;
    	return true;

    	$mensaje = $this->request->data["comentarioCotizacion"];
    	$factura = $this->request->data["factura"];
    	$email   = $this->request->data["email"];
    	$cliente   = $this->request->data["cliente"];

    	$options = array(
			'to' 		=> $email,
			'cc'		=> 'contabilidad@almacendelpintor.com',
			'template'	=> 'mensaje_cliente',
			'subject'	=> 'Comentario Factura Vencida',
			'vars'		=> array("factura" => $factura, "cliente" => $cliente, "mensaje" => $mensaje ),
		);

		if (isset($this->request->form["archivoOrden"]) && !empty($this->request->form["archivoOrden"]["name"])) {
			$this->loadDocumentPdf($this->request->form["archivoOrden"],"pagos_cliente_facturas");
			$options["file"] = WWW_ROOT.'files/pagos_cliente_facturas/'.$this->Session->read("documentoModelo");
		}

		$this->sendMail($options);
   	}


   	public function next_dead(){
   		$this->autoRender = false;
   		return true;
   		$dayDeadline 				= date("Y-m-d",strtotime("+1 day"));
   		$debtsInfo 					= $this->getDataDetbs(["deadline"=>$dayDeadline]);
    	$dataCliente 				= $debtsInfo["dataCliente"];
    	$dataClienteEmails			= $debtsInfo["dataClienteEmails"];

    	$i = 0;

    	foreach ($dataCliente as $nit => $value) {
    		$options = array(
				'layout' 	=> 'new_emails',
				'to' 		=> $dataClienteEmails[$nit]["email"],
				'template'	=> 'next_debt',
				'subject'	=> 'Facturas prontas a vencer - KEBCO SAS',
				'vars'		=> array("facturas" => $value,"deadline"=>$dayDeadline),
			);
    		$this->sendMail($options);
    	}

    	
   	}

   	public function getDataDetbs($params = []){
   		$deudas 					= $this->postWoApi($params,"debts");

    	$dataCliente 				= [];
    	$dataClienteEmails			= [];

    	foreach ($deudas as $key => $value) {
    		$dataClienteEmails[$value->Identificacion] = ["email"=>$value->EMail, "name" => $value->Nombres_terceros];
    		$dataCliente[$value->Identificacion][] = $value;
    	}

    	return compact("dataCliente","dataClienteEmails");
   	}

    public function factura($factura){
    	// $this->autoRender = false;
    	$factura = $this->decrypt($factura);

    	$partsFactura = explode("-", $factura);

    	if(count($partsFactura) != 2){
    		throw new NotFoundException(__('Factura Inválida'));
    	}

    	$params["number"]   = $partsFactura[1];
        $params["prefijo"]  = $partsFactura['0'];
        $datos              = (array) $this->getDataDocument($params);

        $this->loadModel("User");

        $this->User->recursive = -1;
        $user 				= $this->User->findByIdentification($datos["datos_factura"]->IdVendedor);

        $this->set("factValue",$datos);
        $this->set("user",$user);
        $this->set("factura",$factura);
    }

    public function deudas(){
    	$this->autoRender = false;
    	return true;
    	
    	$debtsInfo = $this->getDataDetbs([]);
    	$dataCliente 				= $debtsInfo["dataCliente"];
    	$dataClienteEmails			= $debtsInfo["dataClienteEmails"];

    	$i = 0;

    	foreach ($dataCliente as $nit => $value) {
    		$options = array(
				'layout' 	=> 'new_emails',
				'to' 		=> $dataClienteEmails[$nit]["email"],
				'template'	=> 'deudas',
				'subject'	=> 'Facturas pendientes de pago KEBCO SAS',
				'vars'		=> array("facturas" => $value),
			);
    		$this->sendMail($options);
    	}


    }

    private function validateSession(){
    	$clienteActivo = $this->Session->read("CLIENTE");
    	if (is_null($clienteActivo)) {
    		$this->redirect(["action" => "login"]);
    	}
    	$this->set("cliente",$clienteActivo);
    	return $clienteActivo;
    }

    public function logout(){
    	$this->autoRender = false;
    	$this->Session->write("CLIENTE");
    	$this->redirect(["action" => "login","controller" => "clientes"]);
    }

	public function index() {
		$cliente = $this->validateSession();
		$orders  = $shippings = [];
		if ($cliente["type"] == "natural") {
			$orders = $this->Order->findAllByClientsNaturalIdAndState($cliente["id"],1);
		}else{
			$orders = $this->Order->findAllByContacsUserIdAndState($cliente["id"],1);
		}

		if (!empty($orders)) {
			$orders   	= Set::sort($orders, '{n}.Order.created', 'desc');
			$orderIds 	= Set::extract($orders, "{n}.Order.id");
			$shippings 	= $this->Shipping->findAllByOrderId($orderIds);
		}

		$this->set("orders",$orders);
		$this->set("shippings",$shippings);
		$this->set("cliente",$cliente);
	}

	public function shippings() {
		$cliente = $this->validateSession();
		$orders  = $shippings = [];
		if ($cliente["type"] == "natural") {
			$orders = $this->Order->findAllByClientsNaturalIdAndState($cliente["id"],1);
		}else{
			$orders = $this->Order->findAllByContacsUserIdAndState($cliente["id"],1);
		}

		if (!empty($orders)) {
			$orders   	= Set::sort($orders, '{n}.Order.created', 'desc');
			$orderIds 	= Set::extract($orders, "{n}.Order.id");
			$shippings 	= $this->Shipping->findAllByOrderId($orderIds);
		}

		$this->set("orders",$orders);
		$this->set("shippings",$shippings);
		$this->set("cliente",$cliente);
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Order->exists($id)) {
			throw new NotFoundException(__('Invalid order'));
		}
		$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
		$order   = $this->Order->find('first', $options);
		$this->set('order', $order);

		$datosCliente = $this->getDataCustomer($order["Order"]["prospective_user_id"]);
		$this->validateSession();
		$this->set("datosCliente",$datosCliente);

	}

	public function view_shipping($id = null) {
		if (!$this->Shipping->exists($id)) {
			throw new NotFoundException(__('Invalid shipping'));
		}
		$options = array('conditions' => array('Shipping.' . $this->Shipping->primaryKey => $id));
		$this->set('shipping', $this->Shipping->find('first', $options));
		$this->validateSession();
	}

	public function login() {
		$this->Session->write("validate",null);
		$this->Session->write("CLIENTE",null);
		$this->Session->write("CODEMAILCLIENTE",null);
		$this->Session->write("TIMEMAILCLIENTE",null);
		$this->layout = "start";
		$this->validateSessionTrue();
	}

	public function valid_existencia(){
		$this->autoRender = false;

		$cliente = $this->getClientData($this->request->data);

		if (!empty($cliente)) {
			if($cliente["time"] == 0 || $cliente["time"] < strtotime('now')){
				$this->getOrSendCodeNew($cliente);
			}else{
				$this->sendMessageTxt($cliente["email"],$cliente["code"]);
			}
			return 1;
		}
		return 0;
	}

	public function valid_code(){
		$this->autoRender = false;

		$cliente = $this->getClientData($this->request->data);

		if (!empty($cliente)) {
			if($cliente["time"] == 0 || $cliente["time"] < strtotime('now')){
				$this->getOrSendCodeNew($cliente);
				return 2;
			}else{
				if ($cliente["code"] == $this->Session->read("CODEMAILCLIENTE") && $this->request->data["code"] == $cliente["code"] ) {
					$this->Session->write("CLIENTE",$cliente);
					return 1;
				}else{
					return 0;
				}
			}
		}else{
			return 4;
		}
	}

	public function find_cliente_detail(){
		$this->layout = false;
		$type = $this->request->data["type"];
		$id   = $this->request->data["id"];
		$id   = $this->decrypt($id);
		if ($type == "legal") {
			$this->loadModel('User');
			$this->loadModel('ClientsLegal');

			$id 								= $this->ClientsLegal->ContacsUser->field("clients_legals_id",["id"=>$id]);

			$origen 							= Configure::read('variables.origenContact');
			$usuarios 							= $this->User->role_asesor_comercial_user_true();
			$clientsLegal 						= $this->ClientsLegal->get_data($id);
			$id_contacs_user_bussines 			= $this->ClientsLegal->ContacsUser->id_contacs_user_bussines($id);
			$count_flujos_clients_juridico 		= $this->ClientsLegal->ContacsUser->ProspectiveUser->count_flujos_clients_juridico($id_contacs_user_bussines);
			$count_cotizaciones_enviadas 		= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->count_cotizaciones_enviadas_clients_juridico($id_contacs_user_bussines);
			$cotizaciones_enviadas 				= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->cotizaciones_enviadas_clients_juridico($id_contacs_user_bussines);
			$count_negocios_realizados		 	= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->count_flujos_true_clients_juridico($id_contacs_user_bussines);
			$pagosVerificadosFlujos		 		= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->payments_total_clients_juridico($id_contacs_user_bussines);
			$total_dinero_negocios 				= $this->totalDineroQuotation($pagosVerificadosFlujos);
			$negocios_realizados		 		= $this->ClientsLegal->ContacsUser->ProspectiveUser->FlowStage->flujos_true_clients_juridico($id_contacs_user_bussines);
			$requerimientos_cliente 			= $this->ClientsLegal->ContacsUser->ProspectiveUser->flujos_clients_juridico($id_contacs_user_bussines);
			$this->loadModel("TechnicalService");
			$servicio_tecnico 					= $this->TechnicalService->data_legal_products($id);
			$this->set(compact('clientsLegal','origen','usuarios','count_flujos_clients_juridico','count_cotizaciones_enviadas','count_negocios_realizados','total_dinero_negocios','cotizaciones_enviadas','negocios_realizados','requerimientos_cliente','servicio_tecnico'));
		}else{
			$this->loadModel('User');
			$this->loadModel("ClientsNatural");
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
			$this->set(compact('clientsNaturals','origen','usuarios','count_flujos','count_cotizaciones_enviadas','count_negocios_realizados','total_dinero_negocios','cotizaciones_enviadas','negocios_realizados','requerimientos_cliente','servicio_tecnico'));
		}

		$this->set("type",$type);
	}

	public function totalDineroQuotation($pagosVerificadosFlujos){
		$this->loadModel('ClientsLegal');
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

	public function find_cliente_phone($phone = "000000"){
		$this->autoRender = false;

		$this->loadModel("ClientsNatural");
        $this->loadModel("ContacsUser");
		$datos  	= [];

		if (strlen($phone) == 12 || strlen($phone) == 13) {
			$phone = str_replace([" ","+"], "", $phone);
			$phone = substr($phone, 2);
		}

        $natural    = $this->ClientsNatural->findByTelephoneOrCellPhone($phone,$phone);
        $contacto   = $this->ContacsUser->findByTelephoneOrCellPhone($phone,$phone);

        if(!empty($natural)){
            $datos = array("type" => "natural", "id" => $this->encrypt($natural["ClientsNatural"]["id"]),"email" => $natural["ClientsNatural"]["email"],"name" => $natural["ClientsNatural"]["name"],"city" => $natural["ClientsNatural"]["city"] );
        }
        if(!empty($contacto) && $contacto["ContacsUser"]["state"] == 1){
            $datos = array("type" => "legal", "id" => $this->encrypt($contacto["ContacsUser"]["id"]), "email" => $contacto["ContacsUser"]["email"],"name" => $contacto["ContacsUser"]["name"]." ".$contacto["ClientsLegal"]["name"],"city" => $contacto["ContacsUser"]["city"] );
        }
    	
        $this->response->header(array(
		    'Content-type: application/json'
		));

        return json_encode($datos);
	}

	private function getClientData($data){
        $this->loadModel("ClientsNatural");
        $this->loadModel("ContacsUser");

        $datos  	= null;
        $natural    = $this->ClientsNatural->findByEmail($data["email"]);
        $contacto   = $this->ContacsUser->findByEmailAndState($data["email"],1);
       

        if(!empty($natural)){
            $datos = array("type" => "natural", "id" => $natural["ClientsNatural"]["id"],"code" => $natural["ClientsNatural"]["code"], "time" => is_null($natural["ClientsNatural"]["deadline"]) ? 0 : $natural["ClientsNatural"]["deadline"], "email" => $natural["ClientsNatural"]["email"],"name" => $natural["ClientsNatural"]["name"],"model" => "ClientsNatural" );
        }
        if(!empty($contacto)){
            $datos = array("type" => "legal", "id" => $contacto["ContacsUser"]["id"],"code" => $contacto["ContacsUser"]["code"], "time" => is_null($contacto["ContacsUser"]["deadline"]) ? 0 : $contacto["ContacsUser"]["deadline"], "email" => $contacto["ContacsUser"]["email"],"name" => $contacto["ContacsUser"]["name"],"model" => "ContacsUser" );
        }
    	
        return $datos;
    }

    private function updateCodeDeadLine($cliente){
    	$this->loadModel($cliente["model"]);
    	$modelo = $cliente["model"];
		$data = [
			$modelo=> [ 
				"id" => $cliente["id"],
				"code" => null,
				"deadline"=>null,
			]
		];
		$this->$modelo->save($data);
    }


    private function getOrSendCodeNew($cliente){
    	$this->loadModel($cliente["model"]);
    	$modelo = $cliente["model"];
		$data = [
			$modelo=> [ 
				"id" => $cliente["id"],
				"code" => $this->$modelo->generate(),
				"deadline"=>strtotime("+5 minutes"),
			]
		];
		$this->$modelo->save($data);
		$this->sendMessageTxt($cliente["email"],$data[$modelo]["code"]);
		$this->Session->write("validate",0);
		$this->Session->write("CODEMAILCLIENTE",$data[$modelo]["code"]);
		$this->Session->write("TIMEMAILCLIENTE",$data[$modelo]["deadline"]);
	}

	private function sendMessageTxt($email,$code){
		$options = array(
			'to' 		=> $email,
			'template'	=> 'code_customer',
			'subject'	=> 'Código de acceso CRM KEBCO SAS',
			'vars'		=> array("code" => $code,),
		);

		$this->sendMail($options);
	}

}
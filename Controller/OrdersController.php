<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
/**
 * Orders Controller
 *
 * @property Order $Order
 * @property PaginatorComponent $Paginator
 */
class OrdersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view','change_action');
    }

	public function index() {
		$this->Order->recursive = 0;
		$this->set('orders', $this->Paginator->paginate());
	}

	public function change_post_customer(){
        $this->autoRender = false;

        $this->loadModel("ProspectiveUser");
        $this->loadModel("ContacsUser");

        $datosLegal = [ "ClientsLegal" => [
            "name" => $this->request->data["ClientsNatural"]["name_emp"],
            "nit" => $this->request->data["ClientsNatural"]["nit"],
            "nacional" => $this->request->data["ClientsNatural"]["nacional"],
            "user_receptor" => AuthComponent::user("id"),
        ] ];

        $idActual   = $this->request->data["ClientsNatural"]["id"];
        $flujoID    = $this->request->data["ClientsNatural"]["flujo_id"];
        $type       = $this->request->data["ClientsNatural"]["type"];

        unset($this->request->data["ClientsNatural"]["name_emp"]);
        unset($this->request->data["ClientsNatural"]["nit"]);
        unset($this->request->data["ClientsNatural"]["nacional"]);
        unset($this->request->data["ClientsNatural"]["id"]);
        unset($this->request->data["ClientsNatural"]["flujo_id"]);
        unset($this->request->data["ClientsNatural"]["type"]);

        $this->ProspectiveUser->recursive = -1;
        $flujo = $this->ProspectiveUser->findById($flujoID);

        $this->request->data["ClientsNatural"]["user_receptor"] = AuthComponent::user("id");

        $datosContacto = ["ContacsUser" => $this->request->data["ClientsNatural"] ];

        $conditions	   = array('OR' => array(
							            'LOWER(ContacsUser.telephone) LIKE' 	=> '%'.$datosContacto['ContacsUser']['telephone'].'%',
							            'LOWER(ContacsUser.cell_phone) LIKE' 	=> '%'.$datosContacto['ContacsUser']['telephone'].'%',
							            'LOWER(ContacsUser.telephone) LIKE' 	=> '%'.$datosContacto['ContacsUser']['cell_phone'].'%',
							            'LOWER(ContacsUser.cell_phone) LIKE' 	=> '%'.$datosContacto['ContacsUser']['cell_phone'].'%',
							            'LOWER(ContacsUser.email) LIKE' 		=> '%'.$datosContacto['ContacsUser']['email'].'%'
							        )
								);

		$existe 			= $this->ContacsUser->find("count",["conditions"=>$conditions]);

        $this->ProspectiveUser->ContacsUser->ClientsLegal->create();
        if ($existe == 0 && $this->ProspectiveUser->ContacsUser->ClientsLegal->save($datosLegal)) {
        	
            $clienteLegalID = $this->ProspectiveUser->ContacsUser->ClientsLegal->id;
            $datosContacto["ContacsUser"]["clients_legals_id"] = $clienteLegalID;

            $this->ProspectiveUser->ContacsUser->create();
            if ($this->ProspectiveUser->ContacsUser->save($datosContacto)) {
                $contactoId = $this->ProspectiveUser->ContacsUser->id;
                $flujo["ProspectiveUser"]["clients_natural_id"] = null;
                $flujo["ProspectiveUser"]["contacs_users_id"]   = $contactoId;
                $this->ProspectiveUser->save($flujo);

                $this->ProspectiveUser->recursive = -1;
                $flujos = $this->ProspectiveUser->findAllByClientsNaturalId($idActual);
                foreach ($flujos as $key => $value) {
                    $value["ProspectiveUser"]["clients_natural_id"] = null;
                    $value["ProspectiveUser"]["contacs_users_id"]   = $contactoId;
                    $this->ProspectiveUser->save($value);
                }                
                
                try {

                    $this->loadModel("TechnicalService");
                    $this->TechnicalService->recursive = -1;
                    $tecnicos = $this->TechnicalService->findAllByClientsNaturalId($idActual);
                    if(!empty($tecnicos)){
                        foreach ($tecnicos as $key => $value) {
                            $value["TechnicalService"]["clients_natural_id"] = 0;
                            $value["TechnicalService"]["clients_legal_id"]   = $clienteLegalID;
                            $value["TechnicalService"]["contacs_users_id"]   = $contactoId;
                            $this->TechnicalService->save($value);
                        }
                    }
                    
                } catch (Exception $e) {
                    
                }

                if ($type == "1") {
                    $this->ProspectiveUser->ClientsNatural->recursive = -1;
                    $clienteActual = $this->ProspectiveUser->ClientsNatural->findById($idActual);
                    $this->ProspectiveUser->ClientsNatural->delete($idActual);
                }
                $this->Session->setFlash(__('El cambio fue realizado correctamente correctamente'),'Flash/success');
                return $flujoID;
            }else{
                $this->Session->setFlash(__('El cambio no fue realizado correctamente correctamente'),'Flash/error');
                return false;
            }
        }else{
            $this->Session->setFlash(__('El cambio no fue realizado correctamente correctamente'),'Flash/error');
            return false;
        }
    }

	public function send_order(){
		$this->autoRender = false;
		$id = $this->decrypt($this->request->data["id"]);
		$order = $this->Order->findById($id);

		$email = empty($order["Order"]["clients_natural_id"]) ? $order["ContacsUser"]["email"] : $order["ClientsNatural"]["email"];
		$phone = empty($order["Order"]["clients_natural_id"]) ? $order["ContacsUser"]["cell_phone"] : $order["ClientsNatural"]["cell_phone"];

		$options = array(
            'to'        => [$email],
            // 'cc'        => 'gerencia@almacendelpintor.com',
            'template'  => 'orden_pedido',
            'subject'   => 'Orden de pedido generada: '.$order["Order"]["prefijo"]."-".$order["Order"]["code"],
            'vars'      => array('flujo'=>$order["Order"]["prospective_user_id"],'nameAsesor' => $order["User"]["name"], "id" => $id, "order_num" => $order["Order"]["prefijo"]."-".$order["Order"]["code"]  )
        );

        $this->sendMail($options);


        $strMsg = '
			{
			   "messaging_product": "whatsapp",
			   "to": "57'.$phone.'",
			   "type": "template",
			   "template": {
			       "name": "orden_pedido",
			       "language": {
			           "code": "es",
			           "policy": "deterministic"
			       },
			       "components": [
			           {
			               "type": "header",
			               "parameters": [
			                   {
			                       "type": "text",
			                       "text": "'.$order["Order"]["prefijo"]."-".$order["Order"]["code"].'"
			                   }
			               ]
			           },
			           {
			               "type": "body",
			               "parameters": [
			                   {
			                       "type": "text",
			                       "text": "'.$order["User"]["name"].'"
			                   },
			                   {
			                       "type": "text",
			                       "text": "'.$order["Order"]["prefijo"]."-".$order["Order"]["code"].'"
			                   },
			                   {
			                       "type": "text",
			                       "text": "'.$order["Quotation"]["codigo"].'"
			                   }
			               ]
			           },
			           {
			               "type": "button",
			               "sub_type": "url",
			               "index": 0,
			               "parameters": [
			                   {
			                       "type": "text",
			                       "text": "'.$this->encrypt($order["Order"]["id"])."/".$this->encryptString('clientLink').'"
			                   }
			               ]
			           }
			       ]
			   }
			}
		';
		try {

			$HttpSocket = new HttpSocket();

			$request = ["header" => [
				"Content-Type" => 'application/json',
				"Authorization" => 'Bearer '
			]];

			$responseToken = $HttpSocket->post('https://graph.facebook.com/v13.0/100586116213138/messages',$strMsg,$request);
			$responseToken = json_decode($responseToken->body);
			$this->Session->setFlash(__('Orden enviada correctamente'),'Flash/success');

			$order["Order"]["state"] = 2;
			$order["Order"]["modified"] = date("Y-m-d H:i:s");
			$this->Order->save($order["Order"]);

		} catch (Exception $e) {
			$this->log($e->getMessage());
			$this->Session->setFlash(__('Orden no fue enviada correctamente'),'Flash/error');
		}	

	}

	public function change_action() {
		$this->autoRender = false;

		$orderId = $this->request->data["order"];
		$email   = $this->request->data["correoPrincipal"];
		$type 	 = $this->request->data["type"];

		$order 	 = $this->Order->findById($orderId);

		$order["Order"]["state"] = $type == "approve" ? 1 : 3;
		$order["Order"]["modified"] = date("Y-m-d H:i:s");


		$this->loadModel("User");
		$user 		   = $order["User"]["email"];

    	$emailGerentes = $this->User->role_gerencia_user();

    	if(!empty($emailGerentes) && !empty($user)){
    		$emailGerentes = Set::extract($emailGerentes, "{n}.User.email");
    		$emailGerentes[] = $user;

    		if ($type == "approve") {
    			$message = 'Aprobación orden de pedido '.$order["Order"]["prefijo"]."-".$order["Order"]["code"].' de KEBCO AlmacenDelPintor.com';
    		}else{
    			$message = 'Rechazo orden de pedido '.$order["Order"]["prefijo"]."-".$order["Order"]["code"].' de KEBCO AlmacenDelPintor.com';
    		}

    		$datosCliente = $this->getDataCustomer($order["Order"]["prospective_user_id"]);
    		$rutaURL      = "orders/view/".$this->encrypt($order["Order"]["id"]);

    		$options = array(
				'to'		=> $emailGerentes,
				'template'	=> 'order_notification',
				'subject'	=> $message,
				'vars'		=> array('ruta'=>$rutaURL,"type" => $type, "correoPrincipal" => $this->request->data["correoPrincipal"], "flujo" => $order["ProspectiveUser"]["id"], "comentarioCliente" => $this->request->data["comentarioCotizacion"] )
			); 
			$this->sendMail($options);
			$this->Session->setFlash(__('Orden actualizada correctamente'),'Flash/success');
			$this->Order->save($order);
    	}
	}

	public function validateHaveShipping($order,$return = true){
		$valid = in_array($order["ProspectiveUser"]["state_flow"],[5,6]);
		if(!empty($order["Shipping"])){
			foreach ($order["Shipping"] as $key => $value) {
				if($value["state"] >= 0){ $valid = false; break; }
			}
		}
		if($return){
			return $valid;
		}else{
			if(!$valid){
				$this->Session->setFlash(__('Ya hay una o varias solicitudes de Despacho/facturación en proceso'),'Flash/error');
				$this->redirect(["action"=>'view',$this->encrypt($order["Order"]["id"])]);
			}
		}
	}


	public function view($id = null,$clientLink = null) {
		$id = $this->decrypt($id);
		if (!$this->Order->exists($id)) {
			throw new NotFoundException(__('Invalid order'));
		}

		$aproveeCustomer = false;

		if (!is_null($clientLink) && !AuthComponent::useR("id")) {
			$aproveeCustomer = $this->decrypt($clientLink) == 'clientLink' ? true : false;
		}

		$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
		$order   = $this->Order->find('first', $options);

		$this->set('order', $order);
		$this->set('valid_add', $this->validateHaveShipping($order));

		$datosCliente = $this->getDataCustomer($order["Order"]["prospective_user_id"]);
		$this->set("datosCliente",$datosCliente);
		$this->set("aproveeCustomer",$aproveeCustomer);

	}

	public function change_ref_order($id){
		$this->layout = false;
		$this->loadModel("OrdersProduct");
		$this->OrdersProduct->recursive = 1;
		$productInfo = $this->OrdersProduct->findById($id);


		if ($this->request->is(["post","put"])) {

			if($productInfo["OrdersProduct"]["product_id"] != $this->request->data["OrdersProduct"]["product_id"]){
				$quotationId = $productInfo["Order"]["quotation_id"];

				if($this->OrdersProduct->save($this->request->data)){
					$this->loadModel("QuotationsProduct");
					$this->QuotationsProduct->updateAll(
						["QuotationsProduct.product_id" => $this->request->data["OrdersProduct"]["product_id"], "quantity" => $this->request->data["OrdersProduct"]["quantity"] ],
						[
							"QuotationsProduct.product_id" => $productInfo["OrdersProduct"]["product_id"],
							"QuotationsProduct.quotation_id" => $quotationId
						]
					);
					$this->Session->setFlash(__('Cambio realizado con éxito'),'Flash/success');
					
				}
			}
			$this->redirect(["action"=>'view',$this->encrypt($productInfo["Order"]["id"])]);
		}


		$products = $this->OrdersProduct->Product->find('all',["fields" => ["id","CONCAT(Product.name,' | ',Product.part_number) as nombre "],"recursive" => -1 ]);
		$copy = $products;
		$products = [];
		foreach ($copy as $key => $value) {
			$products[$value["Product"]["id"]] = $value["0"]["nombre"];
		}
		$this->set('productInfo', $productInfo);
		$this->set('references', $products);
	}


	public function add() {
		$clientsLegals      = $this->getClientsLegals();
        $clientsNaturals    = $this->getClientsNaturals();
		$flow = isset($this->request->query["flow"]) ? $this->decrypt($this->request->query["flow"]) : "";
		if ($this->request->is('post')) {

			$this->request->data["Order"]["prefijo"] = $this->request->data["Order"]["nacional"] == 1 ? Configure::read("PrefijoNAC") : Configure::read("PrefijoINT");
			$this->request->data["Order"]["code"]	 = $this->getLastCode();

			if ($this->request->data['Order']['document']['name'] != '' && !empty($this->request->data['Order']['document']['name']) ) {

				$idPdf = strpos(strtolower($this->request->data['Order']['document']['name']), '.pdf');

				if ($idPdf === false) {
					$documento 									= $this->loadPhoto($this->request->data['Order']['document'],'flujo/negociado');
					$this->request->data['Order']['document']	= $this->Session->read('imagenModelo');
				}else{
					$documento 									= $this->loadDocumentPdf($this->request->data['Order']['document'],'flujo/negociado');
					$this->request->data['Order']['document']	= $this->Session->read('documentoModelo');
				}

			} else {
				$this->request->data['Order']['document']   = null;
			}

			$order = $this->request->data["Order"];

			$this->loadModel("Quotation");
			$quotation = $this->Quotation->findById($order["quotation_id"]);

			$this->Order->updateAll(
				["Order.state" => 0],
				["Order.prospective_user_id" => $order["prospective_user_id"]]
			);
			
			$this->Order->create();
			if ($this->Order->save($order)) {
				$orderId    = $this->Order->id;
				
				$otrosDatos = $this->request->data;

				$idProductos = array();
                $itTrs       = array();

                foreach ($otrosDatos as $key => $value) {
                    if(strpos($key, "Cantidad-") !== false){
                        $parts = explode("-", $key);
                        $idProductos[] = count($parts) == 3 ? $parts[2] : $parts[1];
                        $itTrs[] = count($parts) == 3 ? $parts[1] : $parts[0];
                    }
                }

                $total = 0;

                $this->loadModel("OrdersProduct");
                foreach ($quotation["QuotationsProduct"] as $productoID => $value) {
                    $this->OrdersProduct->create();
                    $dataProduct = array(
                        "order_id" 	 => $orderId,
                        "product_id" => $value["product_id"],
                        "price" 	 => $value["price"],
                        "quantity"   => $value["quantity"],
                        "iva" 	     => $value["iva"],
                        "delivery"   => $value["delivery"],
                        "currency"   => $value["currency"],
                        "cost"       => 0,
                        "state"      => 0,
                        "margin"     => $value["margen"]
                    );

                    $this->OrdersProduct->save($dataProduct);

                    $total += $dataProduct["price"]*$dataProduct["quantity"]; 

                }

                $this->loadModel("FlowStage");

                $flowStage = [
					"FlowStage" => [
						"prospective_users_id" => $order["prospective_user_id"],
						"description" => $order["payment_text"],
						"document" 	  => $order['document'],
						"state_flow"  => Configure::read('variables.nombre_flujo.flujo_negociado'),
						"cotizacion"  => $orderId, 
					]
				];
			
				$this->FlowStage->create();
				$this->FlowStage->save($flowStage);

				$this->updateStateProspectiveFlow($order["prospective_user_id"],Configure::read('variables.control_flujo.flujo_negociado'));

				$this->FlowStage->ProspectiveUser->updateAll(["ProspectiveUser.quotation_id" => $order["quotation_id"]],["ProspectiveUser.id" => $order["prospective_user_id"] ]);

				$this->Session->setFlash(__('Orden guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index',"controller" => "prospective_users","?"=>["q"=>$order["prospective_user_id"] ] ));
			} else {
				$this->Session->setFlash(__('Orden no fue guardada correctamente'),'Flash/error');
			}
		}

		$existWo = false;

		$datos   = $this->Order->Quotation->find("all",["conditions" => ["prospective_users_id" => $flow], "fields" => ["id","CONCAT(codigo,' | ',name) as nombre"], "recursive" => -1 ]);
		if (isset($this->request->query["flow"]) && !empty($datos) ) {
			$this->Order->ProspectiveUser->recursive = -1;
			$qtFinal = $this->Order->ProspectiveUser->findById($flow);

			$this->loadModel("FlowStage");

			$idFlowstage 	= $this->FlowStage->id_latest_regystri_state_cotizado($flow);
			$datosFlowstage = $this->FlowStage->get_data($idFlowstage);
			$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->findAllByQuotationId($datosFlowstage['FlowStage']['document']);

			$fecha_cotizacion       = $datosFlowstage["FlowStage"]["created"];
		   	if(!is_null($fecha_cotizacion)){
		        $fecha_cotizacion = date("Y-m-d H:i:s",strtotime($fecha_cotizacion." +15 day"));

		        if(strtotime($fecha_cotizacion) < strtotime(date("Y-m-d H:i:s"))){

		        	$this->Session->setFlash(__('No es posible continuar se debe volver a cotizar pasaron más de 15 días'),'Flash/error');
					return $this->redirect(array('action' => 'index',"controller" => "prospective_users","?"=>["q"=>$flow ] ));

		        	die("No es posible subir un pago se debe volver a cotizar pasaron más de 15 días");
		        }

		    }

			if (!empty($datos) && $qtFinal["ProspectiveUser"]["country"] == 'Colombia') {
				$quotations = [];
				$default = is_null($qtFinal["ProspectiveUser"]["quotation_id"]) ? "" : $qtFinal["ProspectiveUser"]["quotation_id"];
				foreach ($datos  as $key => $value) {
					if (empty($default) && $key == 0) {
						$defaul = $value["Quotation"]["id"];
					}
					$quotations[$value["Quotation"]["id"]] = $value["0"]["nombre"];
				}
				$datosCliente = $this->getDataCustomer($flow);

				if(!empty($datosCliente["identification"]) && !is_null($datosCliente["identification"])){

					$partsNit = explode(" ", str_replace("-", " ", trim($datosCliente["identification"]) ) );
					$params = ["dni" => trim($partsNit[0]) ];
					$dataWo = $this->postWoApi($params,"customer");

					if(!empty($dataWo)){ $existWo = true; }

					if(!$existWo && $datosCliente["type_customer"] == "legal"){
						$this->loadModel("ClientsLegal");
						$clientsLegal 	= $this->ClientsLegal->findById($datosCliente["clients_legals_id"]);




						if(!empty($clientsLegal["ClientsLegal"]["parent_id"])){
							$partsNit = explode(" ", str_replace("-", " ", trim($clientsLegal["Parent"]["nit"]) ) );

							$params = ["dni" => trim($partsNit[0]) ];
							$dataWo = $this->postWoApi($params,"customer");

							if(!empty($dataWo)){ $existWo = true; }
						}

					}

					$default_user = $qtFinal["ProspectiveUser"]["user_id"];
					$this->set("datosCliente",$datosCliente);
					
				}

				
			}else{
				$datosCliente = $this->getDataCustomer($flow);
				$this->set("datosCliente",$datosCliente);
				$quotations = [];
				$default = is_null($qtFinal["ProspectiveUser"]["quotation_id"]) ? "" : $qtFinal["ProspectiveUser"]["quotation_id"];
				foreach ($datos  as $key => $value) {
					if (empty($default) && $key == 0) {
						$defaul = $value["Quotation"]["id"];
					}
					$quotations[$value["Quotation"]["id"]] = $value["0"]["nombre"];
				}
				$existWo = true;
			}
			$this->set("datos",$qtFinal);
		}else{
			$quotations 	= [];
			$datosCliente 	= [];
			$default 		= "";
			$default_user	= "";

		}

		$this->loadModel("CustomerRequest");

		$requestExists = $this->CustomerRequest->field("id",["prospective_user_id" => $flow]);
		$users = $this->Order->User->find('list');
		$this->set(compact('quotations', 'users', 'products','default','flow','default_user','clientsLegals','clientsNaturals','existWo','requestExists'));
	}


	public function get_document(){
        #2904 AND prefijo = 'KE';
        $this->layout = false;
        $this->loadModel("Product");
        $this->Product->recursive = -1;
        $datos = $this->getDataDocument($this->request->data);
        if (!empty($datos)) {
            $datos = (array) $datos;

            if (isset($datos["productos_factura"]) && !empty($datos["productos_factura"])) {
            	foreach ($datos["productos_factura"] as $key => $value) {
            		$product = $this->Product->findByPartNumber($value->CódigoInventario);
            		if(!empty($product)){
            			$datos["productos_factura"][$key]->Product = $product["Product"];
            		}else{
            			unset($datos["productos_factura"][$key]);
            		}
            	}
            }


        }

        $this->set("data",$datos);
    }

	public function getLastCode(){
		$last = $this->Order->find("first",["fields"=>["Order.code"],"order"=>["Order.code" => "DESC"]]);
		
		if (is_null($last) || $last == 0 || empty($last)) {
			$last = 1;
		}else{
			$last = $last["Order"]["code"]+1;
		}
		return $last;
	}

	public function change_customer(){
		$this->autoRender = false;

		$datos["id"] = $this->request->data["id"];

		if(strpos($this->request->data["cliente"], "_LEGAL") === false){
            $datos["clients_natural_id"] = str_replace("_NATURAL", "", $this->request->data["cliente"]);
            $datos["contacs_users_id"]   = 0;
            $type = "1";
        }else{
            $datos["contacs_users_id"]       = $this->request->data["contact"];
            $datos["clients_natural_id"]     = null;
        }

        $this->Order->ProspectiveUser->save($datos);
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Order->exists($id)) {
			throw new NotFoundException(__('Invalid order'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Order->save($this->request->data)) {
				$this->Flash->success(__('The order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The order could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
			$this->request->data = $this->Order->find('first', $options);
		}
		$clientsLegals = $this->Order->ClientsLegal->find('list');
		$clientsNaturals = $this->Order->ClientsNatural->find('list');
		$contacsUsers = $this->Order->ContacsUser->find('list');
		$quotations = $this->Order->Quotation->find('list');
		$users = $this->Order->User->find('list');
		$products = $this->Order->Product->find('list');
		$this->set(compact('clientsLegals', 'clientsNaturals', 'contacsUsers', 'quotations', 'users', 'products'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Order->id = $id;
		if (!$this->Order->exists()) {
			throw new NotFoundException(__('Invalid order'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Order->delete()) {
			$this->Flash->success(__('The order has been deleted.'));
		} else {
			$this->Flash->error(__('The order could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

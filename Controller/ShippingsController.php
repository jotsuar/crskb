<?php
App::uses('AppController', 'Controller');
/**
 * Shippings Controller
 *
 * @property Shipping $Shipping
 * @property PaginatorComponent $Paginator
 */
class ShippingsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');


	public function finals() {
		$this->Shipping->recursive = 1;

		$order = ["Shipping.date_end" => "DESC"];
		$conditions = ["Shipping.state" => 3];

		$this->paginate 			= array(
										'order' 		=> $order,
							        	'limit' 		=> 10,
							        	'conditions' 	=> $conditions,
							    	);
		$this->set('shippings', $this->Paginator->paginate());
	}

	public function index($order_id = null) {

		$order = $this->decrypt($order_id);
		$filter = false;
		$clientes = [];

		$conditions = [];

		if (!empty($this->request->query)) {
			$query = $this->request->query;

			if (isset($query["user_id"]) && !empty($query["user_id"])) {
				$conditions["Shipping.user_id"] = $query["user_id"];
			}
			if (isset($query["conveyor"]) && !empty($query["conveyor"])) {
				$conditions["Shipping.conveyor_id"] = $query["conveyor"];
			}

			if (isset($query["guia"]) && !empty($query["guia"])) {
				$conditions["Shipping.guide LIKE"] = '%'.$query["guia"].'%';
			}

			if (isset($query["flujo_id"]) && !empty($query["flujo_id"])) {
				$conditions["Order.prospective_user_id LIKE"] = '%'.$query["flujo_id"].'%';
			}

			if (isset($query["cliente_id"]) && !empty($query["cliente_id"])) {

				$pos 		= strpos($query["cliente_id"], "_NATURAL");
				$cliente_id = $pos === false ? str_replace("_LEGAL", "", $query["cliente_id"]) : str_replace("_NATURAL", "", $query["cliente_id"]);
				$field_id	= $pos === false ? "clients_legal_id" : "clients_natural_id";
				$conditions["Order.${field_id} LIKE"] = '%'.$cliente_id.'%';

				if($pos === false){
					$this->loadModel("ClientsLegal");
					$cliente = $this->ClientsLegal->field("name",["id" => $cliente_id ] );
				}else{
					$this->loadModel("ClientsNatural");
					$cliente = $this->ClientsNatural->field("name",["id" => $cliente_id ] );
				}
				$clientes   = [$query["cliente_id"] => $cliente];
			}

			if (isset($query["request_type"]) && ($query["request_type"]) != "") {
				$conditions["Shipping.request_type"] = $query["request_type"];
			}

			if (isset($query["state"]) && ($query["state"]) != "") {
				$conditions["Shipping.state"] = $query["state"];
			}

			if (isset($query["id"]) && ($query["id"]) != "") {
				$conditions["Shipping.id"] = $query["id"];
			}

			if (isset($query["fechas"]) && !empty($query["fechas"])) {

				switch ($query["fechas"]) {
					case '1':
						$campo = "Shipping.date_initial";
						break;
					case '2':
						$campo = "Shipping.date_preparation";
						break;
					case '3':
						$campo = "Shipping.date_send";
						break;
					case '4':
						$campo = "Shipping.date_end";
						break;
				}

				$conditions["DATE(${campo}) >="] = $query["fechaIni"];
                $conditions["DATE(${campo}) <="] = $query["fechaEnd"];

                $fechaInicioReporte 		= $query["fechaIni"];
				$fechaFinReporte 			= $query["fechaEnd"];
			}


			$this->set("q",$query);
		}
		$conditions["Shipping.state !="] = -1;

		$order					= array('Shipping.created' => 'desc');
		$this->paginate 		= array(
								        'limit' 		=> 10,
								        'order' 		=> $order,
								        'conditions'	=> $conditions
								    );

		$fechaInicioReporte 		= date("Y-m-d");
		$fechaFinReporte 			= date("Y-m-d");


		$usuarios  = $this->Shipping->User->role_asesor_comercial_user_true(true);
		$conveyors = $this->Shipping->Conveyor->find('list');
		$this->Shipping->recursive = 1;
		$this->set('shippings', $this->Paginator->paginate());
		$this->set('usuarios', $usuarios);
		$this->set('filter', $filter);
		$this->set('conveyors', $conveyors);
		$this->set('clientes', $clientes);
		$this->set('fechaInicioReporte', $fechaInicioReporte);
		$this->set('fechaFinReporte', $fechaFinReporte);

		$max_hour_envoice   = strtolower(date("D",strtotime(date("Y-m-d H:i:s")))) == "sat" ?  Configuration::get_flow("max_hour_envoyce_sat") : Configuration::get_flow("max_hour_envoyce");
		$max_hour_remission = strtolower(date("D",strtotime(date("Y-m-d H:i:s")))) == "sat" ?  Configuration::get_flow("max_hour_envoyce_sat") : Configuration::get_flow("max_hour_remission");
		$max_hour_shipping	= strtolower(date("D",strtotime(date("Y-m-d H:i:s")))) == "sat" ?  Configuration::get_flow("max_hour_shipping_sat") : Configuration::get_flow("max_hour_shipping");

		$this->set('max_hour_envoice', $max_hour_envoice);
		$this->set('max_hour_remission', $max_hour_remission);
		$this->set('max_hour_shipping', $max_hour_shipping);

		$this->validateShipping(true);

	}

	public function return_request($id = null) {
		$id = $this->desencriptarCadena($id);
		$this->Shipping->save(["id"=>$id,"state" => 0, "date_preparation" => null ]);
		$this->redirect(["action"=>"index"]);
	}

	public function return_request_prepare($id) {
		$id = $this->desencriptarCadena($id);


		$shipping = $this->Shipping->findById($id);
		$bill_code = $shipping["Shipping"]["bill_code"];

		$shipping["Shipping"]["bill_file"] = null;
		$shipping["Shipping"]["bill_code"] = null;
		$shipping["Shipping"]["guide"] 	   = null;
		$shipping["Shipping"]["state"] 	   = 1;

		$prospecto = $this->Shipping->Order->ProspectiveUser->findByIdAndBillCode($shipping["Order"]["prospective_user_id"],$bill_code);

		if(!empty($prospecto)){
			$prospecto["ProspectiveUser"]["bill_value"]     = null;
            $prospecto["ProspectiveUser"]["bill_value_iva"] = null;
            $prospecto["ProspectiveUser"]["bill_code"]      = null;
            $prospecto["ProspectiveUser"]["bill_user"]      = null;
            $prospecto["ProspectiveUser"]["bill_date"]      = null;
            $prospecto["ProspectiveUser"]["bill_file"]      = null;
            $prospecto["ProspectiveUser"]["bill_text"]      = null;
            $prospecto["ProspectiveUser"]["locked"]         = 0;
            $this->Shipping->Order->ProspectiveUser->save($prospecto["ProspectiveUser"]);
		}

		$this->Shipping->save($shipping["Shipping"]);
		$this->redirect(["action"=>"index"]);
	}

	public function request_envoice($id = null) {
		$id = $this->desencriptarCadena($id);

		if ($this->request->is("post") || $this->request->is("put")) {
			$this->autoRender = false;

			if ( isset($this->request->data["Shipping"]["document_add"]) && $this->request->data['Shipping']['document_add']['name'] != '' && !empty($this->request->data['Shipping']['document_add']['name']) ) {
				$documento 							    	    = $this->loadDocumentPdf($this->request->data['Shipping']['document_add'],'flujo/despachado');
				$this->request->data['Shipping']['document_add']	= $this->Session->read('documentoModelo');
			} else {
				$this->request->data['Shipping']['document_add']    = null;
			}

			$this->request->data["Shipping"]["request_envoice"] = 1;


			if ($this->Shipping->save($this->request->data)) {

				$correoDespachos = Configure::read("email_shippings");
				$options 	= array('conditions' => array('Shipping.' . $this->Shipping->primaryKey => $id));
				$shipping 	= $this->Shipping->find('first', $options);
				$orderData 	= $this->Shipping->Order->findById($shipping["Order"]["id"]);

				$options = array(
		            'to'        => $correoDespachos,
		            'cc'        => '',
		            'template'  => 'despachos',
		            'subject'   => 'Solicitud de facturación generada para el flujo '.$orderData["Order"]["prospective_user_id"],
		            'vars'      => array('flujo'=>$orderData["Order"]["prospective_user_id"],'nameAsesor' => $orderData["User"]["name"], "id" => $id, "order_num" => $orderData["Order"]["prefijo"]."-".$orderData["Order"]["code"], "actualizar" => true  )
		        );

		        $this->sendMail($options);

		        $this->Shipping->save($shipping["Shipping"]);
				

				$this->Session->setFlash(__('Orden solicitdada correctamente'),'Flash/success');
			} else {
				$this->Session->setFlash(__('Despacho no fue guardado correctamente'),'Flash/error');
			}
			
			return $this->redirect(array('action' => 'view',$this->encrypt($id)));

			
		}else{
			$this->layout = false;
			$this->request->data = $this->Shipping->findById($id);
		}		

	}

	public function request_shipping($id = null) {

		$id = $this->desencriptarCadena($id);
		if (!$this->Shipping->exists($id)) {
			throw new NotFoundException(__('Invalid shipping'));
		}

		$shipping 			= $this->Shipping->findById($id);

		$validateContact  	= $this->validateTimes();

		$max_hour_envoice = strtolower(date("D",strtotime(date("Y-m-d H:i:s")))) == "sat" ? Configuration::get_flow("max_hour_shipping_sat") : Configuration::get_flow("max_hour_envoyce");
		$max_hour_shipping	= Configuration::get_flow("max_hour_shipping");

		if ($max_hour_shipping < date("H:i:s") && in_array($shipping["Shipping"]["request_type"], [1]) ) {
			$this->Session->setFlash(__('La hora máxima de solicitud de despachos es :'.$max_hour_shipping),'Flash/error');
			$this->redirect(["action"=>"index"]);
		}

		$orderData 	 = $this->Shipping->Order->findById($shipping["Shipping"]["order_id"]);

		$datosFlujo  = $this->Shipping->Order->ProspectiveUser->findById($orderData["Order"]["prospective_user_id"]);
		$direcciones = [];
		$cliente 	 = [];
		$emails      = "";

		if (empty($orderData)) {
			$this->Session->setFlash(__('No existe flujo asociado'),'Flash/error');
			$this->redirect($this->request->referer());
		}


		$order_id 	 = $orderData["Order"]["id"];

		if(!is_null($datosFlujo["ProspectiveUser"]["contacs_users_id"]) && $datosFlujo["ProspectiveUser"]["contacs_users_id"] != 0 ){
			$contactos = $this->Shipping->Order->ProspectiveUser->ContacsUser->findAllByClientsLegalsIdAndState($datosFlujo["ContacsUser"]["clients_legals_id"],1);
			if(!empty($contactos)){
				$emails = Set::extract($contactos, "{n}.ContacsUser.email");
			}
		}
		$emails = $emails == "" ? $emails : implode(",", $emails);

		$this->loadModel("Adress");
		$this->loadModel("FlowStage");
		$this->Adress->recursive = 1;


		$cliente = $this->getDataCustomer($orderData["Order"]["prospective_user_id"],true);

		if(!is_null($orderData["ProspectiveUser"]["contacs_users_id"]) && $orderData["ProspectiveUser"]["contacs_users_id"] != 0){
			$direcciones = $this->Adress->findAllByClientsLegalId( $cliente["clients_legals_id"] );
		}else{
			$direcciones = $this->Adress->findAllByClientsNaturalId( is_null($orderData["ProspectiveUser"]["clients_natural_id"]) ? 0 : $orderData["ProspectiveUser"]["clients_natural_id"] );
		}

		if ($this->request->is(["post","put"])) {
			$this->request->data["Shipping"]["adress_id"] = $this->request->data["direccion"];
			unset($this->request->data["Shipping"]["state"]);
			$this->request->data["Shipping"]["request_shipping"] = 1;
		
			if ($this->Shipping->save($this->request->data)) {

				$correoDespachos = Configure::read("email_shippings");
				$options 	= array('conditions' => array('Shipping.' . $this->Shipping->primaryKey => $id));
				$shipping 	= $this->Shipping->find('first', $options);
				$orderData 	= $this->Shipping->Order->findById($shipping["Order"]["id"]);

				$options = array(
		            'to'        => $correoDespachos,
		            'cc'        => '',
		            'template'  => 'despachos',
		            'subject'   => 'Solicitud de facturación generada para el flujo '.$orderData["Order"]["prospective_user_id"],
		            'vars'      => array('flujo'=>$orderData["Order"]["prospective_user_id"],'nameAsesor' => $orderData["User"]["name"], "id" => $id, "order_num" => $orderData["Order"]["prefijo"]."-".$orderData["Order"]["code"], "actualizar" => true  )
		        );

		        $this->sendMail($options);

		        $this->Shipping->save($shipping["Shipping"]);
				

				$this->Session->setFlash(__('Orden solicitdada correctamente'),'Flash/success');
			} else {
				$this->Session->setFlash(__('Despacho no fue guardado correctamente'),'Flash/error');
			}
			
			return $this->redirect(array('action' => 'view',$this->encrypt($id)));

		}else{
			$this->request->data = $shipping;
		}

		// var_dump($shipping["Product"]);
		// die();

		$this->set(compact("orderData","cliente","direcciones","emails","datosFlujo","max_hour_shipping","shipping"));

	}


	public function view($id = null) {
		$id = $this->desencriptarCadena($id);
		if (!$this->Shipping->exists($id)) {
			throw new NotFoundException(__('Invalid shipping'));
		}
		$options 	= array('conditions' => array('Shipping.' . $this->Shipping->primaryKey => $id), 'recursive' => 1);
		$shipping 	= $this->Shipping->find('first', $options);
		$orderData 	= $this->Shipping->Order->findById($shipping["Order"]["id"]);

		$this->loadModel("FlowStage");
		$id_etapa_cotizado 			= $this->FlowStage->id_latest_regystri_state_cotizado($orderData["Order"]["prospective_user_id"]);
		$datosFlowstage 			= $this->FlowStage->get_data($id_etapa_cotizado);
		if (is_numeric($datosFlowstage['FlowStage']['document'])) {
			$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datosFlowstage['FlowStage']['document']);
		} else {
			$produtosCotizacion 	= array();
		}

		$debeVolver 	  = false;
		$cotizacion 	  = null;

		foreach ($produtosCotizacion as $key => $value) {
			if ($value["QuotationsProduct"]["change"] == 1 && $value["QuotationsProduct"]["warehouse"] == 3) {
				$debeVolver = true;
				$cotizacion = $value["QuotationsProduct"]["quotation_id"];
			}
		}

		$this->set('shipping', $shipping );
		$this->set('cotizacion', $cotizacion );
		$this->set('debeVolver', $debeVolver );
		$this->validateShipping(true);
	}

	public function note_logistic($id) {
		$this->layout = false;
		$id 	  	  = $this->decrypt($id);
		$shipping 	  = $this->Shipping->findById($id);

		if ($this->request->is(array('post', 'put'))) {
			if ($this->Shipping->save($this->request->data)) {
				$this->Session->setFlash(__('Nota guardada correctamente.'),'Flash/success');
			} else {
				$this->Session->setFlash(__('Nota no fue guardada correctamente.'),'Flash/error');
			}			
		}else{
			$this->request->data = $shipping;
		}

	}

	public function change($id = null) {
		$this->layout = false;
		// $this->Shipping->recursive = -1;
		$id 	  	 = $this->decrypt($id);
		$shipping 	 = $this->Shipping->findById($id);

		$orderData 	 = $this->Shipping->Order->findById($shipping["Order"]["id"]);
		$prefijo 	 = null;
		$code 	 	 = null;


		if ($this->request->is("post")) {

			$datos = null;

			$this->request->data["Shipping"]["id"] = $id;

			if ($this->request->data["Shipping"]["state"] == 2 || 

				($shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_envoice"] == 1 ) ||
				($shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_shipping"] == 1 )

			){	
				if ( isset($this->request->data["Shipping"]["document"]) && $this->request->data['Shipping']['document']['name'] != '' && !empty($this->request->data['Shipping']['document']['name']) ) {
					$documento 							    	    = $this->loadDocumentPdf($this->request->data['Shipping']['document'],'flujo/despachado');
					$this->request->data['Shipping']['document']	= $this->Session->read('documentoModelo');
				} else {

					$this->request->data['Shipping']['document']    = null;
				}

				if ($shipping["Shipping"]["request_shipping"] != 1) {
					if ( isset($this->request->data["Shipping"]["remision"]) && $this->request->data['Shipping']['remision']['name'] != '' && !empty($this->request->data['Shipping']['remision']['name']) ) {
						$documento 							    	    = $this->loadDocumentPdf($this->request->data['Shipping']['remision'],'flujo/remisiones');
						$this->request->data['Shipping']['remision']	= $this->Session->read('documentoModelo');
					} else {
						$this->request->data['Shipping']['remision']    = null;
					}

					if ( isset($this->request->data["Shipping"]["bill_file"]) && $this->request->data['Shipping']['bill_file']['name'] != '' && !empty($this->request->data['Shipping']['bill_file']['name']) ) {
						$documento 							    	    = $this->loadDocumentPdf($this->request->data['Shipping']['bill_file'],'flujo/facturas');
						$this->request->data['Shipping']['bill_file']	= $this->Session->read('documentoModelo');
					} else {
						$this->request->data['Shipping']['bill_file']    = null;
					}
				}
			}

			if (isset($this->request->data["Shipping"]["bill_prefijo"]) && isset($this->request->data["Shipping"]["bill_code"]) ) {
				$prefijo = $this->request->data["Shipping"]["bill_prefijo"];
				$code 	 = $this->request->data["Shipping"]["bill_code"];

				$this->request->data["Shipping"]["bill_code"] = $prefijo." ".$code;

				
			}

			if ($this->Shipping->save($this->request->data)) {

				if ($this->request->data["Shipping"]["state"] == -1) {
					try {
						$this->Shipping->Order->OrdersProduct->updateAll(
							["OrdersProduct.state" => 0],
							["OrdersProduct.order_id" => $orderData["Order"]["id"] ]
						);
					} catch (Exception $e) {
							
					}
				}
				if ($this->request->data["Shipping"]["state"] == 2 || ($shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_shipping"] == 1 ) ) {
					if ($shipping["Shipping"]["request_type"] == 1 || $shipping["Shipping"]["request_type"] == 2 ) {		
						$this->saveInformationEnvoice($code,$prefijo,$shipping,$orderData);
					}
					if ($shipping["Shipping"]["request_type"] == 0 || $shipping["Shipping"]["request_type"] == 2 || ($shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_shipping"] == 1 ) ) {						
						$this->saveInformationDelivery($shipping["Shipping"]["adress_id"],$orderData["Order"]["prospective_user_id"],$shipping["Shipping"]["copias"]);
						$this->saveStateDespachado($id);
					}					
				}

				if ( ( $shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_envoice"] == 1 ) ) {
					$this->saveInformationEnvoice($code,$prefijo,$shipping,$orderData);
				}

				if ($shipping["Shipping"]["envio"] == 1 && ($this->request->data["Shipping"]["state"] == 2 || ($shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_shipping"] == 1 ) )  ) {
					$this->sendMailCliente($id);
				}

				$newState = '';

				switch ($this->request->data["Shipping"]["state"]) {
					case '-1':
						$newState =  "Solicitud cancelada rechazada";
						break;
					case '0':
						$newState =  "Solicitud creada";
						break;
					case '1':
						$newState =  "Solicitud en preparación";
						break;
					case '2':
						$newState =  "Solicitud enviada y/o facturada";
						break;
					case '3':

						if ($shipping["Shipping"]["request_envoice"] == 1) {
							$newState =  "Despacho enviado y factura solicitada";
						}elseif($shipping["Shipping"]["request_envoice"] == 2){
							$newState =  "Despacho enviado y factura cargada";
						}elseif ($shipping["Shipping"]["request_shipping"] == 1) {
							$newState =  "Despacho solicitado y factura cargada";
						}elseif($shipping["Shipping"]["request_shipping"] == 2){
							$newState =  "Despacho enviado y factura cargada";
						}else{
							$newState =  "Solicitud entregada";
						}
						
						break;
				}

				

		        // if ($shipping["Shipping"]["request_type"] == 3) {
		        // 	$shipping_info["Shipping"]["id"] 		= $id;
		        // 	$shipping_info["Shipping"]["state"] 	= 3;
		        // 	$shipping_info["Shipping"]["date_end"] 	= date("Y-m-d H:i:s");
		        // 	$this->Shipping->save($shipping_info);
		        // }

		        $options = array(
		            'to'        => $orderData["User"]["email"],
		            'template'  => 'despachos_estados',
		            'subject'   => '!'.$newState.'! | Orden de despacho/remisión y/o facturación actualizada para el flujo '.$orderData["Order"]["prospective_user_id"],
		            'vars'      => array('flujo'=>$orderData["Order"]["prospective_user_id"],'nameAsesor' => AuthComponent::user("name"), "id" => $id, "order_num" => $orderData["Order"]["prefijo"]."-".$orderData["Order"]["code"], "shipping" => $this->Shipping->findById($id)  )
		        );	

		        $this->sendMail($options);

		        $this->loadModel("Manage");
		        $this->Manage->sendNotificationShipping($options["subject"],$this->encryptString($id),$orderData["User"]["id"]);


				$this->Session->setFlash(__('Despacho guardado correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'view',$this->encrypt($shipping["Shipping"]["id"])));
			} else {
				$this->Session->setFlash(__('Despacho no fue guardado correctamente'),'Flash/error');
				return $this->redirect(array('action' => 'view',$this->encrypt($shipping["Shipping"]["id"])));
			}

		}
		$conveyors = $this->Shipping->Conveyor->find('list');
		$this->set("shipping",$shipping);
		$this->set("conveyors",$conveyors);
	}

	private function getValueFact($dataFact){

        $valor_factura       = 0;
        $costo_factura       = 0;
        $utilidad_factura    = 0;
        $utilidad_porcentual = 0;
        $totalSt             = 0;
        $totalProductos      = 0;
        $valorSt             = 0;
        $valorBySt           = 0;
        $valorReal           = 0;
        $costoReal           = 0;

        $this->loadModel("Config");
        $this->loadModel("Product");
        $this->loadModel("Category");
        $this->loadModel("Cost");

        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $this->loadModel("Trm");
        $trmDay         = $this->Trm->field("valor",["fecha_inicio >=" => date("Y-m-d",strtotime($dataFact["datos_factura"]->Fecha) ), "fecha_fin <="=> date("Y-m-d",strtotime($dataFact["datos_factura"]->Fecha)) ]);

        $fechaData      = date("Y-m-d",strtotime($dataFact["datos_factura"]->Fecha));

        foreach ($dataFact["productos_factura"] as $key => $value) {
            $cantidad = is_null($value->Cantidad) ? 1 : $value->Cantidad;
            $totalProductos++;
            if ($value->CódigoInventario == 538 ) {

                
                $totalSt += $cantidad;
                $valorBySt += ($value->Precio * Configure::read("VALOR_ST")) * $cantidad;
                $valorSt += $value->Precio * $cantidad;
                // $value->costo = round($valorSt/1.55);
                
            }

            if (isset($value->costo)) {
                $total   = round(floatval($value->costo),2);
            }else{
                $valorWO = $this->postWoApi(["part_number" => $value->CódigoInventario],"get_cost");
                $total   = !empty($valorWO) ? round(floatval($valorWO->Costo),2) : 0; 
            }



            $idProduct   = $this->Product->field("id",["part_number"=>$value->CódigoInventario, "deleted" => 0]);
            $precioUsd   = $this->Product->field("purchase_price_usd",["part_number"=>$value->CódigoInventario, "deleted" => 0]);
            $category_id = $this->Product->field("category_id",["part_number"=>$value->CódigoInventario, "deleted" => 0]);
            $factor      = $this->Category->field("factor",["id" => $category_id]);
            $precioUsd   = round(floatval($precioUsd),2);
            $otroCosto   = $this->Cost->field("pre_purchase_price_usd",["product_id"=> $idProduct, "purchase_price_usd" => $precioUsd, "DATE(created) >=" => $fechaData ]);

            if ($otroCosto != false) {
                $precioUsd = round(floatval($otroCosto),2);
            }

            


            // $costFob   = $precioUsd*$factor*$trmActual;
            // $costFob   = $precioUsd*$factor*$trmDay;
            // if ($total < $costFob) {
            //     $total = $costFob;
            // }

            if ($value->CódigoInventario != 538) {
                $costoReal += ($total * intval($value->Cantidad));
                $valorReal += floatval($value->Precio)* $value->Cantidad;
            }

            $costo_factura += ($total * intval($value->Cantidad));
            $valor_factura += floatval($value->Precio) * $value->Cantidad;
        }

        // if ($valor_factura == 0) {
        //     foreach ($dataFact["productos_factura"] as $key => $value) {
        //     }
        // }
       

        $utilidad_factura       = $valor_factura - $costo_factura;

        if ($valor_factura > $valorReal && $valorReal != 0) {
            $utilidad_factura       = $valorReal - $costoReal;

        }

        $utilidad_porcentual    = $costo_factura < 1000 ? 35 : round( ( ($utilidad_factura / ( ($valor_factura > $valorReal && $valorReal != 0) ? $valorReal : $valor_factura ) ) * 100 ),2 );    

        return compact("valor_factura","costo_factura","utilidad_factura","utilidad_porcentual","totalSt","valorSt","totalProductos","valorBySt","costoReal","valorReal");

    }

	public function saveInformationEnvoice($code,$prefijo,$shipping,$orderData){

		if (is_null($code) || is_null($prefijo)) {
			return false;
		}

		$newData   = $this->request->data;
        $newData["ProspectiveUser"]["id"] = $orderData["ProspectiveUser"]["id"];

		$params["number"]   = $code;
        $params["prefijo"]  = $prefijo;
        $datos              = (array) $this->getDataDocument($params);

        $datos 				= (array)($datos);

        $bill_code 			= $prefijo.= " ".$code;

        $this->loadModel("ProspectiveUser");
        $prospective = $this->ProspectiveUser->findById($newData['ProspectiveUser']['id']);

        try {
        	$valores 			= $this->getValueFact($datos);

	        if(isset($valores["utilidad_porcentual"]) && $valores["utilidad_porcentual"] <= 20){

	        	$this->loadModel("User");
	            // $users = $this->User->role_gerencia_user();

	            $emailsData = ["logistica@kebco.co"];

	            // foreach ($users as $key => $value) {
	            //     $emailsData[] = $value["User"]["email"];
	            // }

	            $subject = "Factura con poco margen de factura ".$bill_code;

	            $options = array(
	                'to'        => $emailsData,
	                'template'  => 'diference',
	                'subject'   => $subject,
	                'vars'      => array(
	                    "flujo"  => $prospective["ProspectiveUser"]["id"],
	                    "margen" => 1,
	                    "margen_val" => $valores["utilidad_porcentual"]
	                )
	            );
	            
	            $this->sendMail($options);
	        }
        } catch (Exception $e) {
        	
        }
        
        $newData["ProspectiveUser"]["bill_user"]         = $orderData["ProspectiveUser"]["user_id"];
        $newData["ProspectiveUser"]["bill_prefijo"]      = $bill_code;
        $newData["ProspectiveUser"]["bill_code"]         = $bill_code;
        $newData["ProspectiveUser"]["bill_file"]         = null;
        $newData["ProspectiveUser"]["bill_text"]         = json_encode($datos);

        if (!empty($datos) && isset($datos["valores_factura"]) && !empty($datos["valores_factura"])) {
            foreach ($datos["valores_factura"] as $key => $value) {
                if (!is_null($value->IdClasificación)) {
                    $newData["ProspectiveUser"]["bill_value"] = floatval($value->Crédito);
                }
                if (is_null($value->IdClasificación)) {
                    $newData["ProspectiveUser"]["bill_value_iva"] = floatval($value->Débito);
                }
            }
        }else{
            $this->Session->setFlash('La información de la factura no esta completa','Flash/error');
            return $this->redirect(["action"=>"carga_factura"]);
        }

        if (!empty($datos) && isset($datos["datos_factura"])) {
            $newData["ProspectiveUser"]["bill_date"] = date("Y-m-d",strtotime($datos["datos_factura"]->Fecha));
        }

        

        $locked      = $this->validateProducts($datos,$newData);
        
        $newData["ProspectiveUser"]["locked"] = $locked;
        if ($locked == 1) {
            $newData["ProspectiveUser"]["date_locked"] = date("Y-m-d H:i:s");
        }

        $this->loadModel("ProductsLock");

        try {
            $this->ProductsLock->updateAll( ["ProductsLock.state" => 2], ["ProductsLock.prospective_user_id" => $prospective["ProspectiveUser"]["id"]  ] );
        } catch (Exception $e) {
            
        }

        $products_for_bill = [];

        foreach ($shipping["Product"] as $key => $value) {
        	if ($value["ShippingsProduct"]["envoice"] == 1) {
        		$products_for_bill[] = $value["id"];
        	}
        }

        if(!empty($prospective["ProspectiveUser"]["bill_code"])){
            $this->loadModel("Salesinvoice");
            $salesinvoiceData = $newData["ProspectiveUser"];
            $salesinvoiceData["prospective_user_id"] = $salesinvoiceData["id"];
            $salesinvoiceData["user_id"] = $salesinvoiceData["bill_user"];
            unset($salesinvoiceData["id"]);
            $this->Salesinvoice->create();
            $this->Salesinvoice->save($salesinvoiceData);
            if (!empty($datos) && isset($datos["productos_factura"]) && !empty($datos["productos_factura"])) {
                $this->loadModel("QuotationsProduct");
                foreach ($orderData["Product"] as $keyProd => $dataValueProd) {
                	if (in_array($value["id"],$products_for_bill)) {
                    	$this->QuotationsProduct->updateAll(["QuotationsProduct.biiled"=> $locked == 1 ? 1 : 2 ],["QuotationsProduct.product_id" => $dataValueProd["id"], "QuotationsProduct.quotation_id" => $orderData["Order"]["quotation_id"] ]);
                	}
                }
            }

        }else{
            $prospective["ProspectiveUser"]["bill_value"]       = floatval($newData["ProspectiveUser"]["bill_value"]);
            $prospective["ProspectiveUser"]["bill_value_iva"]   = floatval($newData["ProspectiveUser"]["bill_value_iva"]);
            $prospective["ProspectiveUser"]["bill_code"]        = $newData["ProspectiveUser"]["bill_code"];
            $prospective["ProspectiveUser"]["bill_user"]        = $newData["ProspectiveUser"]["bill_user"];
            $prospective["ProspectiveUser"]["bill_date"]        = $newData["ProspectiveUser"]["bill_date"];
            $prospective["ProspectiveUser"]["bill_file"]        = $newData["ProspectiveUser"]["bill_file"];
            $prospective["ProspectiveUser"]["bill_text"]        = $newData["ProspectiveUser"]["bill_text"];
            $prospective["ProspectiveUser"]["status_bill"]      = 0;
            $prospective["ProspectiveUser"]["locked"]           = $locked;
            if ($locked == 1) {
                $prospective["ProspectiveUser"]["date_locked"]  = date("Y-m-d H:i:s");
            }
            $prospective["ProspectiveUser"]["updated"]          = date("Y-m-d H:i:s");
            if ($this->ProspectiveUser->save($prospective)) {
                if (!empty($datos) && isset($datos["productos_factura"]) && !empty($datos["productos_factura"])) {
                    $this->loadModel("QuotationsProduct");
                    foreach ($orderData["Product"] as $keyProd => $dataValueProd) {
                    	if (in_array($value["id"],$products_for_bill)) {
	                    	$this->QuotationsProduct->updateAll(["QuotationsProduct.biiled"=> $locked == 1 ? 1 : 2 ],["QuotationsProduct.product_id" => $dataValueProd["id"], "QuotationsProduct.quotation_id" => $orderData["Order"]["quotation_id"] ]);
                    	}
	                }
                }
            }

            $this->loadModel("Sender");
            $this->loadModel("Config");
            $this->Sender->create();
            $tiempo = $this->Config->field("time_factura",["id" => 1]);
            $fecha  = date("Y-m-d", strtotime("+".$tiempo." day"));
            $this->Sender->save(["Sender"=>["prospective_user_id"=> $prospective["ProspectiveUser"]["id"], "quotation_id" => $prospective["ProspectiveUser"]["id"], "quiz_id" => 1, "deadline" => $fecha ]]);

        }

        if($locked == 1){

            $this->loadModel("User");
            $users = $this->User->role_gerencia_user();

            foreach ($users as $key => $value) {
                $emails[] = $value["User"]["email"];
            }

            $subject = "Bloqueo de factura ".$bill_code;

            $options = array(
                'to'        => $emails,
                'template'  => 'diference',
                'subject'   => $subject,
                'vars'      => array(
                    "flujo"  => $prospective["ProspectiveUser"]["id"]
                )
            );
            
            $this->sendMail($options);

            $this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente, pero deberá ser revisada por generencía ya que se encontró diferencias en precios y/o productos cotizados'),'Flash/success'); 


        }else{
            $this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente.'),'Flash/success');
        }

	}


	public function updateStateFinishProspective($flujo_id){

		$this->loadModel("ProspectiveUser");

    	$flowstage_quotatiob_id 	= $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
    	$flowstageDatos 			= $this->ProspectiveUser->FlowStage->get_data($flowstage_quotatiob_id);
    	$cotiacion_id 				= $flowstageDatos['FlowStage']['document'];
    	$numero_productos 			= $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->row_data_quotation_products($cotiacion_id);
    	$count_products_despachados = $this->ProspectiveUser->FlowStage->find_state_despachado_total_products($flujo_id);

    	if ($count_products_despachados >= $numero_productos) {
            $datos = $this->ProspectiveUser->get_data($flujo_id);
        	$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.pedido_entregado'),Configure::read('variables.Gestiones_horas_habiles.envio_confirmado'),$datos['ProspectiveUser']['user_id'],$flujo_id,Configure::read('variables.nombre_flujo.pedido_entregado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
    		$this->updateStateProspectiveFlow($flujo_id,Configure::read('variables.control_flujo.flujo_finalizado'));
    		$datos = $this->ProspectiveUser->get_data($flujo_id);
			$this->saveAtentionTimeFlujoEtapas($flujo_id,'confirm_delivery_date','confirm_delivery_time','comfir_delivery');

            $datos["ProspectiveUser"]["send_final"] = 0;
            $datos["ProspectiveUser"]["send_day"] = date("Y-m-d", strtotime("+3 days"));
            $this->ProspectiveUser->save($datos);
			return true;
    	} else {
    		return false;
    	}
    }


	public function add($flow_id = null) {

		$validateContact  	= $this->validateTimes();

		$max_hour_envoice   = strtolower(date("D",strtotime(date("Y-m-d H:i:s")))) == "sat" ?  Configuration::get_flow("max_hour_envoyce_sat") : Configuration::get_flow("max_hour_envoyce");
		$max_hour_remission = strtolower(date("D",strtotime(date("Y-m-d H:i:s")))) == "sat" ?  Configuration::get_flow("max_hour_envoyce_sat") : Configuration::get_flow("max_hour_remission");
		$max_hour_shipping	= strtolower(date("D",strtotime(date("Y-m-d H:i:s")))) == "sat" ?  Configuration::get_flow("max_hour_shipping_sat") : Configuration::get_flow("max_hour_shipping");

		if (date("H:i:s") >= $max_hour_envoice && date("H:i:s") >= $max_hour_remission && date("H:i:s") >= $max_hour_shipping  ) {
			$this->Session->setFlash(__('Excede la máxima hora de gestión'),'Flash/error');
			$this->redirect(["action"=>"index"]);
		}

		$flow_id = $this->decrypt($flow_id);
		if (!$this->Shipping->Order->ProspectiveUser->exists($flow_id) || $flow_id == null) {
			$this->Session->setFlash(__('No existe flujo asociado'),'Flash/error');
			$this->redirect($this->request->referer());
		}

		$orderData 	 = $this->Shipping->Order->find("first",["conditions" => ["Order.prospective_user_id" => $flow_id ], "order" => ["Order.id" => "DESC" ] ]);

		 // ByProspectiveUserIdAndState($flow_id,[0,1,2]);
		$datosFlujo  = $this->Shipping->Order->ProspectiveUser->findById($orderData["Order"]["prospective_user_id"]);
		$direcciones = [];
		$cliente 	 = [];
		$emails      = "";

		$conditions["Shipping.email_envoice !="]=null;

		$cliente_id = !is_null($orderData["Order"]["clients_legal_id"])  ? $orderData["Order"]["clients_legal_id"] : $orderData["Order"]["clients_natural_id"] ;
		$field_id	= !is_null($orderData["Order"]["clients_legal_id"])  ? "clients_legal_id" : "clients_natural_id";
		$conditions["Order.${field_id} LIKE"] = '%'.$cliente_id.'%';

		$lastShipping = $this->Shipping->find("first",["order"=>["Shipping.id"=>"DESC"], "conditions"=> $conditions,"recursive"=>1]);

		if (empty($orderData)) {
			$this->Session->setFlash(__('No existe flujo asociado'),'Flash/error');
			$this->redirect($this->request->referer());
		}


		$order_id 	 = $orderData["Order"]["id"];

		if(!is_null($datosFlujo["ProspectiveUser"]["contacs_users_id"]) && $datosFlujo["ProspectiveUser"]["contacs_users_id"] != 0 ){
			$contactos = $this->Shipping->Order->ProspectiveUser->ContacsUser->findAllByClientsLegalsIdAndState($datosFlujo["ContacsUser"]["clients_legals_id"],1);
			if(!empty($contactos)){
				$emails = Set::extract($contactos, "{n}.ContacsUser.email");
			}
		}
		$emails = $emails == "" ? $emails : implode(",", $emails);

		$this->loadModel("Adress");
		$this->loadModel("FlowStage");
		$this->Adress->recursive = 1;

		$cliente = $this->getDataCustomer($orderData["Order"]["prospective_user_id"],true);

		if(!is_null($orderData["ProspectiveUser"]["contacs_users_id"]) && $orderData["ProspectiveUser"]["contacs_users_id"] != 0){
			$direcciones = $this->Adress->findAllByClientsLegalId( $cliente["clients_legals_id"] );
		}else{
			$direcciones = $this->Adress->findAllByClientsNaturalId( is_null($orderData["ProspectiveUser"]["clients_natural_id"]) ? 0 : $orderData["ProspectiveUser"]["clients_natural_id"] );
		}

		$id_etapa_cotizado 			= $this->FlowStage->id_latest_regystri_state_cotizado($orderData["Order"]["prospective_user_id"]);
		$datosFlowstage 			= $this->FlowStage->get_data($id_etapa_cotizado);
		if (is_numeric($datosFlowstage['FlowStage']['document'])) {
			$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datosFlowstage['FlowStage']['document']);
		} else {
			$produtosCotizacion 	= array();
		}

		$copyProducts 				= $produtosCotizacion;
		$transportadoras 			= Configure::read('variables.transportadoras');

		$internacionalLLC = false;

		$debeVolver 	  = false;
		$cotizacion 	  = null;

		$this->loadModel("Config");
		$this->loadModel("Trm");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];


		foreach ($produtosCotizacion as $key => $value) {
			if (!is_null($value["QuotationsProduct"]["id_llc"]) && $value["QuotationsProduct"]["state_llc"] != 2 && $value["QuotationsProduct"]["trm_change"] < $trmActual ) {
				$internacionalLLC = true;
				break;
			}
		}

		foreach ($produtosCotizacion as $key => $value) {
			if ($value["QuotationsProduct"]["change"] == 1 && $value["QuotationsProduct"]["warehouse"] == 3) {
				$debeVolver = true;
				$cotizacion = $value["QuotationsProduct"]["quotation_id"];
			}
		}

		$internacionalLLC = false;
		if($internacionalLLC){
			$this->FlowStage->Quotation->QuotationsProduct->setLlc($datosFlowstage['FlowStage']['document']);
			$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datosFlowstage['FlowStage']['document']);
		}else{
			$produtosCotizacion 	= $orderData["Product"];
		}

		$dataProducts = [];

		foreach ($copyProducts as $key => $value) {
			$dataProducts[$value["QuotationsProduct"]["product_id"]] = $value["QuotationsProduct"]; 
		}

		$cantidades 		 	= [];
		$partsData				= $this->getValuesProductsWo($copyProducts);

		if (!empty($partsData)) {
			foreach ($partsData as $reference => $items) {
				if ( $reference != 'Reserva') {
					$cantidades[$reference] = count($items[0]) > 0 ? array_sum( Set::extract($items,'{n}.total') ) : 0;
				}
			}
		}

		if ($this->request->is('post')) {

			if ($max_hour_shipping < date("H:i:s") && in_array($this->request->data["Shipping"]["request_type"], [0,2]) ) {
				$this->Session->setFlash(__('La hora máxima de solicitud de despachos es :'.$max_hour_shipping),'Flash/error');
				$this->redirect(["action"=>"index"]);
			}

			$this->Shipping->create();

			$this->request->data["Shipping"]["date_initial"] = date("Y-m-d");
			$this->request->data["Shipping"]["copias"] = $this->request->data["Shipping"]["copias_email"];

			$productos 			= $this->request->data["Shipping"]["request_type"] == 1 ? [] : $this->request->data["Shipping"]["productos"];
			$productos_factura  = $this->request->data["Shipping"]["productos_factura"];
			$productos_serial   = $this->request->data["productos_serial"];
			unset($this->request->data["Shipping"]["productos"]);
			unset($this->request->data["Shipping"]["productos_factura"]);
			unset($this->request->data["productos_serial"]);

			$dataRequest = $this->request->data;

			$dataRequest["Shipping"]["adress_id"] = isset($dataRequest["direccion"]) && !empty($dataRequest["direccion"]) ? $dataRequest["direccion"] : 0 ;
			$dataRequest["Shipping"]["user_id"]   = $datosFlujo["ProspectiveUser"]["user_id"];

			if ($this->request->data["Shipping"]["request_type"] == 1) {
				$dataRequest["Shipping"]["adress_id"] = -1;
			}



			if (empty($productos) && empty($productos_factura)) {
				$this->Session->setFlash(__('Es necesario tener un producto como mínimo'),'Flash/error');
			}else{

				if ( isset($this->request->data["Shipping"]["rut"]) && $this->request->data['Shipping']['rut']['name'] != '' && !empty($this->request->data['Shipping']['rut']['name']) ) {
					$documento 							    	    = $this->loadDocumentPdf($this->request->data['Shipping']['rut'],'flujo/ruts');
					$dataRequest['Shipping']['rut']	= $this->Session->read('documentoModelo');
				} else {
					$dataRequest['Shipping']['rut'] = null;
				}

				if ( isset($this->request->data["Shipping"]["orden"]) && $this->request->data['Shipping']['orden']['name'] != '' && !empty($this->request->data['Shipping']['orden']['name']) ) {

					$idPdf = strpos(strtolower($this->request->data['Shipping']['orden']['name']), '.pdf');

					if ($idPdf === false) {
						$documento 							= $this->loadPhoto($this->request->data['Shipping']['orden'],'flujo/ordenes');
						$dataRequest['Shipping']['orden']	= $this->Session->read('imagenModelo');
					}else{
						$documento 							= $this->loadDocumentPdf($this->request->data['Shipping']['orden'],'flujo/ordenes');
						$dataRequest['Shipping']['orden']	= $this->Session->read('documentoModelo');
					}

				} else {
					$dataRequest['Shipping']['orden']   = null;
				}


				if ($this->Shipping->save($dataRequest)) {

					$shipping_id = $this->Shipping->id;

					$this->loadModel("ShippingsProduct");
					$this->loadModel("QuotationsProduct");
					$this->loadModel("OrdersProduct");

					foreach ($orderData["Product"] as $key => $value) {
						if ( !in_array($value["OrdersProduct"]["id"], $productos ) && !in_array($value["OrdersProduct"]["id"],$productos_factura) ) {
							echo 2;
							continue;
						}
						if ($internacionalLLC) {
							$this->QuotationsProduct->recursive = -1;
							$dataPqt = $this->QuotationsProduct->findById($value);
							$dataShippingProduct = ["ShippingsProduct" => [
								"shipping_id" => $this->Shipping->id,
								"order_product_id" => $value,
								"product_id" => $dataPqt["QuotationsProduct"]["product_id"],
								"quantity" => $dataPqt["QuotationsProduct"]["quantity"]
		  					] ];
						}else{
							$dataShippingProduct = ["ShippingsProduct" => [
								"shipping_id" => $this->Shipping->id,
								"order_product_id" => $value["OrdersProduct"]["id"],
								"product_id" => $value["OrdersProduct"]["product_id"],
								"quantity" => $value["OrdersProduct"]["quantity"],
								"shipping" => in_array($value["OrdersProduct"]["id"],$productos) ? 1 : 0,
								"envoice" => in_array($value["OrdersProduct"]["id"],$productos_factura) ? 1 : 0,
								"serial_number" => array_key_exists($value["OrdersProduct"]["id"],$productos_serial) && !empty($productos_serial[$value["OrdersProduct"]["id"]]) ? $productos_serial[$value["OrdersProduct"]["id"]] : null,
		  					] ];
		  					$value["OrdersProduct"]["state"] = $dataShippingProduct["ShippingsProduct"]["shipping"] == 1 && $dataShippingProduct["ShippingsProduct"]["envoice"] == 1 ? 1 : 0;
		  					$this->OrdersProduct->save($value["OrdersProduct"]);
						}
	  					$this->ShippingsProduct->create();
	  					$this->ShippingsProduct->save($dataShippingProduct);
					}

					$correoDespachos = Configure::read("email_shippings");

					$options = array(
			            'to'        => $correoDespachos,
			            'cc'        => '',
			            'template'  => 'despachos',
			            'subject'   => 'Orden de despacho y/o facturación generada para el flujo '.$orderData["Order"]["prospective_user_id"],
			            'vars'      => array('flujo'=>$orderData["Order"]["prospective_user_id"],'nameAsesor' => $orderData["User"]["name"], "id" => $shipping_id, "order_num" => $orderData["Order"]["prefijo"]."-".$orderData["Order"]["code"]  )
			        );

			        $this->sendMail($options);

					

					$this->Session->setFlash(__('Orden solicitdada correctamente'),'Flash/success');
					return $this->redirect(array('action' => 'view',$this->encrypt($shipping_id)));
				} else {
					$this->Session->setFlash(__('Orden no fue solicitdada correctamente'),'Flash/error');
				}
			}
		}

		$conveyors = $this->Shipping->Conveyor->find('list');
		$this->set("ajuste",$config["Config"]["ajusteTrm"]);
		$this->set(compact('adresses', 'orders', 'conveyors', 'products','order_id','orderData','cliente','direcciones','datosFlujo','emails','produtosCotizacion','internacionalLLC','debeVolver','cotizacion','trmActual','dataProducts','cantidades','partsData','max_hour_shipping','max_hour_remission','max_hour_envoice','lastShipping'));
	}

	public function saveStateDespachado($shipping_id = null){


		$this->loadModel("FlowStage");
		$shipping 				= $this->Shipping->findById($shipping_id);
		$flujo_id 				= $shipping['Order']['prospective_user_id'];
		$numero_guia 			= $shipping['Shipping']['guide'];
		$transportadora 		= $shipping['Shipping']['conveyor_id'];
		
		$imagen 											= 1;
		$datos['FlowStage']['document']						= $shipping["Shipping"]["document"];
		$datos['FlowStage']['number'] 						= $numero_guia;
		$datos['FlowStage']['cotizacion'] 					= $shipping_id;
		$datos['FlowStage']['conveyor'] 					= $this->Shipping->Conveyor->field("name",["id" => $transportadora ]) ;
		$datos['FlowStage']['prospective_users_id']			= $flujo_id;
		$datos['FlowStage']['state_flow']					= Configure::read('variables.nombre_flujo.flujo_despachado');
		$contador 											= 0;
		$cotizacion_id 										= 0;


		$id_etapa_cotizado 			= $this->FlowStage->id_latest_regystri_state_cotizado($shipping["Order"]["prospective_user_id"]);
		$datosFlowstage 			= $this->FlowStage->get_data($id_etapa_cotizado);
		if (is_numeric($datosFlowstage['FlowStage']['document'])) {
			$cotiacion_id 			= $datosFlowstage['FlowStage']['document'];
		} else {
			$cotiacion_id 			= 0;
		}


		foreach ($shipping["Product"] as $key => $value) {
			$this->FlowStage->Quotation->QuotationsProduct->recursive = -1;
			$producto = $this->FlowStage->Quotation->QuotationsProduct->findByQuotationIdAndProductIdAndQuantity($cotiacion_id,$value["id"],$value["ShippingsProduct"]["quantity"]);

			if (!empty($producto) && $value["ShippingsProduct"]["shipping"] == 1) {
				$producto['QuotationsProduct']['state'] 		= 3;
				$this->FlowStage->Quotation->QuotationsProduct->save($producto['QuotationsProduct']);
				$contador = $contador + 1;
			}			
		}

		if ($contador == 0) {
			$id_etapa_cotizado 		= $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
			$datosFlow 				= $this->FlowStage->get_data($id_etapa_cotizado);
			if (is_numeric($datosFlow['FlowStage']['document'])) {
				$this->FlowStage->Quotation->QuotationsProduct->update_entrega_orden($datosFlow['FlowStage']['document']);
			}
		}
		$datos['FlowStage']['products_send'] 			= $contador;
		$count_row 										= $this->FlowStage->Quotation->QuotationsProduct->finish_products_cotizacion($cotizacion_id);
		if ($count_row < 1) {
			$finish 									= 1;
			$datos['FlowStage']['state'] 				= '6';
		} else {
			$finish 									= 1;
		}
		if ($imagen == 1) {
			$this->FlowStage->create();
			if ($this->FlowStage->save($datos)) {

				$id_flow_ok 					 = $this->FlowStage->id;
				$dataShip["Shipping"]["id"] 	 = $shipping_id;
				$dataShip["Shipping"]["flow_id"] = $id_flow_ok;
				$this->Shipping->id 			 = $id_flow_ok;
				$this->Shipping->save($dataShip);

				$this->FlowStage->setDispatch();
				if ($finish == 1) {
					$this->FlowStage->ProspectiveUser->save(["ProspectiveUser"=>["id" =>$flujo_id, "status" => 1, "updated" => date("Y-m-d H:i:s") ]]);
					$this->updateStateProspectiveFlow($flujo_id,Configure::read('variables.control_flujo.flujo_despachado'));
					$id_inset 			= $this->FlowStage->id;
					$this->updateStateProspective($flujo_id,1);
					$idInfoDespachado 	= $this->FlowStage->find_id_flowStage_state_infoDespacho($flujo_id);
					$this->updateFlowStageState($idInfoDespachado,1);
					
					$this->saveDataLogsUser(1,'FlowStage',$id_inset,Configure::read('variables.nombre_flujo.flujo_pagado').' - '.Configure::read('variables.nombre_flujo.flujo_despachado'));
					$this->saveAtentionTimeFlujoEtapas($flujo_id,'despachado_date','despachado_time','despachado');
				}
				return true;
			}
		} else {
			return $imagen;
		}
	}

	public function saveInformationDelivery($address_id, $flujo_id, $copias){
		
		$this->loadModel("FlowStage");
		$this->loadModel("Adress");

		$datos['FlowStage']['prospective_users_id']		= $flujo_id;

		if (($address_id == 0) || empty($address_id)) {
			$datos['FlowStage']['city']						= "Entrega en local";
			$datos['FlowStage']['address']					= "Entrega en local";
			$datos['FlowStage']['additional_information']	= "";
			$datos['FlowStage']['contact']					= "El cliente recoge en local";
			$datos['FlowStage']['flete']					= "Recoge";
			$datos['FlowStage']['telephone']				= "";
		}else{
			$address = $this->Adress->findById($address_id);
			$datos['FlowStage']['city']						= $address["Adress"]["city"];
			$datos['FlowStage']['address']					= $address["Adress"]["address"];
			$datos['FlowStage']['additional_information']	= $address["Adress"]["address_detail"];
			$datos['FlowStage']['contact']					= $address["Adress"]["name"];
			$datos['FlowStage']['flete']					= "Contraentrega";
			$datos['FlowStage']['telephone']				= $address["Adress"]["phone"];
		}

		
		$datos['FlowStage']['copias_email']				= $copias;
		$datos['FlowStage']['state_flow']				= Configure::read('variables.nombre_flujo.datos_despacho');
		$datos['FlowStage']['state']					= 2;
		$this->FlowStage->create();
		if ($this->FlowStage->save($datos)) {
			$this->updateStateProspective($flujo_id,2);
			$this->messageUserRoleLogistica($datos['FlowStage']['prospective_users_id']);
			$this->saveAtentionTimeFlujoEtapas($datos['FlowStage']['prospective_users_id'],'dispatch_data_date','dispatch_data_time','dispatch');
			return $flujo_id;
		}
	}

	public function sendMailCliente($shipping_id){
		$this->Shipping->recursive = 1;
		$shipping = $this->Shipping->findById($shipping_id);

		if ($shipping["Shipping"]["request_type"] == 1) {
			return true;
		}

		$this->loadModel("FlowStage");
		if ($shipping["Shipping"]["state"] == 2) {
			$ruta 					= '/img/shippings/'.$shipping["Shipping"]["document"];
			$comprobanteImg 		= '';
		}else{
			$ruta 					= '/img/flujo/despachado/default.jpg';
			$comprobanteImg 		= 'No se adjuntó';
		}
		$datos					= $this->FlowStage->ProspectiveUser->get_data($shipping["Order"]["prospective_user_id"]);
		if ($datos['ProspectiveUser']['clients_natural_id'] > 0) {
			$datosC 			= $this->FlowStage->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
			$email_usuario 		= $datosC['ClientsNatural']['email'];
			$name_usuario 		= $datosC['ClientsNatural']['name'];
		} else {
			$datosC 			= $this->FlowStage->ProspectiveUser->ContacsUser->get_data($datos['ProspectiveUser']['contacs_users_id']);
			$email_usuario 		= $datosC['ContacsUser']['email'];
			$name_usuario 		= $datosC['ContacsUser']['name'];
		}
		$emailCliente 			= array();
		$emailCliente[0] 		= $email_usuario;
		$copias_email 			= $shipping["Shipping"]["copias_email"];
    	if ($copias_email != '') {
    		$emails 			= explode(',',$copias_email);
			if (isset($emails[0])) {
				$emailCliente 		= array_merge($emails, $emailCliente);
			} else {
				$emailCliente[1] 	= $copias_email;
			}
    	}
		$options = array('to'		=> $emailCliente,
						'template'	=> 'order_delivery_new',
						'subject'	=> 'Despachos Kebco - Ha ocurrido un cambio en una de tus compras en KEBCO',
						'vars'		=> array("shipping" => $shipping, 'name' => $name_usuario,'ruta' => $ruta,'comprobanteImg' => $comprobanteImg),
					);

		$this->sendMail($options);

		// var_dump($options);
		// die;
	}


	public function edit($id = null) {
		if (!$this->Shipping->exists($id)) {
			throw new NotFoundException(__('Invalid shipping'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Shipping->save($this->request->data)) {
				$this->Flash->success(__('The shipping has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The shipping could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Shipping.' . $this->Shipping->primaryKey => $id));
			$this->request->data = $this->Shipping->find('first', $options);
		}
		$adresses = $this->Shipping->Adress->find('list');
		$orders = $this->Shipping->Order->find('list');
		$conveyors = $this->Shipping->Conveyor->find('list');
		$products = $this->Shipping->Product->find('list');
		$this->set(compact('adresses', 'orders', 'conveyors', 'products'));
	}

	public function delete($id = null) {
		$this->Shipping->id = $id;
		if (!$this->Shipping->exists()) {
			throw new NotFoundException(__('Invalid shipping'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Shipping->delete()) {
			$this->Flash->success(__('The shipping has been deleted.'));
		} else {
			$this->Flash->error(__('The shipping could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

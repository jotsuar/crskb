<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');

class FlowStagesController extends AppController {

	
	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('cronProducts','dislock','automatic_flujos');
    }

    public function automatic_flujos(){
    	$this->autoRender = false;
    	$this->FlowStage->ProspectiveUser->recursive = -1;
    	$params = [];
    	$flows  = $this->FlowStage->ProspectiveUser->find("all",["conditions" => ["state_flow" =>array(5,6,8),"status_bill" => 1, "bill_code !=" => NULL, "bill_text !=" => null, "DATE(created)" => date("Y-m-d")  ]]);

    	if (!empty($flows)) {
    		$flows 	= 	Set::extract($flows,"{n}.ProspectiveUser.id");
    		$flujos =   [];

    		foreach ($flows as $key => $value) {
            	$value    = strtoupper($value);
	            $flujos[] = "'$value'";
	        }
    		$params =   ["flows"=>$flujos];
    	}

    	$flujosData =	$this->postWoApi($params,"get_flujos");

    	if (!empty($flujosData)) {

    		if (!empty($flujosData) && is_array($flujosData)) {
	            foreach ($flujosData as $key => $value) {
	                $this->validateFlowWo($value);                
	            }
	        }

    		$this->loadModel("Factura");
    		$this->Factura->updateAll(["Factura.state" => 1],["Factura.id >" => 0]);
    		$arrSave = [];

    		foreach ($flujosData as $key => $value) {
    			$existe = $this->FlowStage->ProspectiveUser->find("count",["conditions" => ["ProspectiveUser.id" => $value]]);
    			if ( $existe > 0 ) {
    				$arrSave[] = ["Factura" => ["flujo_id" => $value,"state" => 0, "id" => null] ];

    				try {
    					$this->loadModel("Sender");
	                    $this->loadModel("Config");
	                    $this->Sender->create();
	                    $tiempo = $this->Config->field("time_factura",["id" => 1]);
	                    $fecha  = date("Y-m-d", strtotime("+".$tiempo." day"));
	                    $this->Sender->save(["Sender"=>["prospective_user_id"=> $value, "quotation_id" => $value, "quiz_id" => 1, "deadline" => $fecha ]]);
    				} catch (Exception $e) {
    						
    				}

    			}
    		}

    		if (!empty($arrSave)) {
    			$this->Factura->saveAll($arrSave);    			
    		}

    	}
    }

    public function dislock(){
    	$this->autoRender = false;
    	$this->loadModel("ProductsLock");
    	$this->ProductsLock->recursive = -1;
    	$locksData = $this->ProductsLock->find("all",[
    		"conditions" => ["ProductsLock.due_date <="=>date("Y-m-d"), "ProductsLock.state" => 1 ],
    	]);
    	if (!empty($locksData)) {
    		foreach ($locksData as $key => $value) {
    			$value["ProductsLock"]["state"] = 3;
    			$value["ProductsLock"]["unlock_date"] = date("Y-m-d H:i:s");
    			$this->ProductsLock->save($value);
    		}
    	}
    }

	public function change_contactado(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			$flujo_id 			= $this->request->data['flujo_id'];
			$state  			= $this->request->data['state'];

			$validateContact  	= $this->validateTimes(true);
			$valid 				= true;

			if ( (!empty($validateContact["contact"]) && !in_array($flujo_id, $validateContact["contact"] ))  ) {
				$valid = false;
			}elseif(empty($validateContact["contact"]) && !empty($validateContact["quotation"]) ){
				$valid = false;
			}

			if ($state == Configure::read('variables.control_flujo.flujo_asignado') && $valid) {
				$datos				= $this->FlowStage->ProspectiveUser->get_data($flujo_id);
				$datosFlow 			= $this->FlowStage->find_data_prospective_stateFlow_asignado($datos['ProspectiveUser']['id']);
				if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
					$datosC 		= array();
					$empresa_id 	= $this->FlowStage->ProspectiveUser->ContacsUser->find_id_bussines($datos['ProspectiveUser']['contacs_users_id']);
					$users_contacs 	= $this->FlowStage->ProspectiveUser->ContacsUser->find_list_contacs_users($empresa_id['ContacsUser']['clients_legals_id']);
				} else {
					$datosC 			= $this->FlowStage->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
					$users_contacs 		= array();
				}
				$origen 			= Configure::read('variables.origenContact');

				unset($origen["Landing"]);
				unset($origen["Marketing"]);
				unset($origen["Referido"]);
				$this->set(compact('datos','origen','users_contacs','datosC','datosFlow'));
			} else {
				$this->render('/FlowStages/state_invalid');
			}
		}
	}
	public function saveStateContactado(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			
			if (isset($this->request->data["FlowStage"]['name_users'])) {
				$datos['FlowStage']['contact']					= $this->request->data["FlowStage"]['name_users'];
			} else {
				$datos['FlowStage']['contact']					= $this->request->data["FlowStage"]['name'];
			}

			$datos['FlowStage']['reason']                        = $this->request->data["FlowStage"]['reason'];
            $datos['FlowStage']['origin']                        = $this->request->data["FlowStage"]['origin'];

			if (isset($this->request->data["FlowStage"]["description_no_contact"])) {				
				$datos['FlowStage']['description']					= $this->request->data["FlowStage"]["description_no_contact"];
			}else{
				$datos['FlowStage']['description']					= $this->request->data["FlowStage"]['description'];
			}
			$datos['FlowStage']['prospective_users_id']			= $this->request->data["FlowStage"]['flujo_id'];
			$datos['FlowStage']['state_flow']					= Configure::read('variables.nombre_flujo.flujo_contactado');
			$datos['FlowStage']['cotizacion']					= $this->request->data["FlowStage"]['inlineRadioOptions'];
			if ($this->request->data["FlowStage"]['inlineRadioOptions'] == "0") {
				$datos['FlowStage']['state']					= 0;
			}

			$this->request->data['flujo_id'] = $this->request->data["FlowStage"]['flujo_id'];

			if (isset($this->request->data['FlowStage']['image']) && !empty($this->request->data['FlowStage']['image']["name"])) {
				$documento = $this->loadPhoto($this->request->data['FlowStage']['image'],'flujo/contactado');
            	$datos['FlowStage']['image'] = $this->Session->read('imagenModelo');				
			}

			$this->FlowStage->create();
			if ($this->FlowStage->save($datos)) {
				$id_inset 								= $this->FlowStage->id;
				$datosPros 								= $this->FlowStage->ProspectiveUser->get_data($this->request->data['flujo_id']);

				if ($datos['FlowStage']['cotizacion'] == 0 && AuthComponent::user("email") != "gerencia@almacendelpintor.com" && AuthComponent::user("email") != "jotsuar2@gmail.com" && !isset($this->request->data["approved_data"]) ) {

					$this->loadModel("Approve");

					$datosAprovee = ["Approve" => [
						"flujo_id" => $datosPros["ProspectiveUser"]["id"],
						"flowstage_id" => $id_inset,
						"quotation_id" => 0,
						"copias_email" => '',
						"inlineRadioOptions" => 0,
						"type_aprovee" => 2,
						"user_id" => $datosPros["ProspectiveUser"]["user_id"],
					] ];
					$this->Approve->create();
					$this->Approve->save($datosAprovee);

					$dataProspecto = ["ProspectiveUser" => [ "id" => $datosPros["ProspectiveUser"]["id"], "valid" => 1 ] ];

					$this->FlowStage->ProspectiveUser->save($dataProspecto);
					$this->Session->setFlash(__('La cancelación del flujo deberá ser aprobada por un administrador.'),'Flash/success');
					return $this->request->data['flujo_id'];
				}else{
					$this->saveAtentionTimeFlujoEtapas($this->request->data['flujo_id'],'contactado_date','contactado_time','contactado');
					if ($datos['FlowStage']['cotizacion'] == "1") {
						$datosPros["ProspectiveUser"]["time_quotation"] = $this->calculateHoursGest( Configuration::get_flow("hours_quotation") );
						$save = $this->FlowStage->ProspectiveUser->save($datosPros["ProspectiveUser"]);

						$this->saveAtentionTimeFlujoEtapasLimitTime($this->request->data['flujo_id'],'limit_cotizado_date','limit_cotizado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_cotizado'));
						$this->updateStateProspectiveFlow($this->request->data['flujo_id'],Configure::read('variables.control_flujo.flujo_contactado'));
						$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_contactado'),Configure::read('variables.Gestiones_horas_habiles.flujo_contactado'),$datosPros['ProspectiveUser']['user_id'],$this->request->data['flujo_id'],Configure::read('variables.nombre_flujo.flujo_contactado'),$this->webroot.'prospectiveUsers/adviser?q='.$this->request->data['flujo_id']);
						$this->Session->setFlash('Información guardada satisfactoriamente, recuerda que el siguiente paso es enviar la cotización', 'Flash/success');
					} else {
						if (isset($this->request->data["FlowStage"]["descriptionNoContact"])) {
							$datosPros["ProspectiveUser"]["description"].=" - ".$this->request->data["FlowStage"]["descriptionNoContact"];
							$datosPros["ProspectiveUser"]["time_quotation"] = $this->calculateHoursGest( Configuration::get_flow("hours_quotation") );
							$save = $this->FlowStage->ProspectiveUser->save($datosPros["ProspectiveUser"]);
							$conditions 			= array('FlowStage.prospective_users_id' => $this->request->data['flujo_id'],'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'));

							$asginedFlow = $this->FlowStage->find("first",["recursive" => -1, "conditions" => $conditions ]);
							$asginedFlow["FlowStage"]["reason"].=" - Nota: ".$this->request->data["FlowStage"]["descriptionNoContact"];
							$this->FlowStage->save($asginedFlow);
						}
						$this->updateStateProspectiveFlow($this->request->data['flujo_id'],Configure::read('variables.control_flujo.flujo_cancelado'));
						$this->insertProspectiveDesciptionCancel($this->request->data['flujo_id'],$datos['FlowStage']['description']);
						$this->Session->setFlash('Información guardada satisfactoriamente', 'Flash/success');
					}
	                $this->saveDataLogsUser(1,'FlowStage',$id_inset,Configure::read('variables.nombre_flujo.flujo_asignado').' - '.Configure::read('variables.nombre_flujo.flujo_contactado'));
				}				
			}
			return $this->request->data['flujo_id'];
		}
	}

	public function change_cotizado(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			$flujo_id 			= $this->request->data['flujo_id'];
			$state 				= $this->request->data['state'];
			$emails  			= "";

			$validateContact  	= $this->validateTimes(true);
			$valid 				= true;

			if  (!empty($validateContact["contact"])  || ( !empty($validateContact["quotation"]) && !in_array($flujo_id, $validateContact["quotation"] ) )  ) {
				$valid = false;
			}

			if ($state == Configure::read('variables.control_flujo.flujo_contactado') && $valid) {

				$dataFlujo = $this->FlowStage->ProspectiveUser->field("valid",["id" => $flujo_id]);

				if($dataFlujo == "0"){			

					$id_flow_bussines 	= $this->FlowStage->id_flow_bussines_contactado($this->request->data['flujo_id']);
					$quotationList 		= $this->FlowStage->Quotation->list_data_prospective($this->request->data['flujo_id']);
					$datos				= $this->FlowStage->ProspectiveUser->findById($flujo_id);

					if(!is_null($datos["ProspectiveUser"]["contacs_users_id"])){
						$contactos = $this->FlowStage->ProspectiveUser->ContacsUser->findAllByClientsLegalsIdAndState($datos["ContacsUser"]["clients_legals_id"],1);
						if(!empty($contactos)){
							$emails = Set::extract($contactos, "{n}.ContacsUser.email");
						}
					}
					$emails = $emails == "" ? $emails : implode(",", $emails);
					$this->set(compact('datos','quotationList','id_flow_bussines','emails'));
				}else{
					$this->set("dataFlujo",$dataFlujo);
				}
			} else {
				$this->render('/FlowStages/state_invalid');
			}
		}
	}

	public function approve_cancel(){
		$this->autoRender = false;
		$this->loadModel("Approve");

		$datosAprovee = $this->Approve->findById($this->request->data["id"]);

		$datos = $this->request->data;
		$dataProspecto = ["ProspectiveUser" => [ "id" => $datosAprovee["Approve"]["flujo_id"], "valid" => 0 ] ];
		$this->FlowStage->ProspectiveUser->save($dataProspecto);

		$datosAprovee["Approve"]["state"] = 1;
		$datosAprovee["Approve"]["modified"] = date("Y-m-d H:i:s");
		$this->Approve->save($datosAprovee);

		$flowData = $this->FlowStage->ProspectiveUser->findById($datosAprovee["Approve"]["flujo_id"]);

		if ( $datosAprovee["Approve"]["type_aprovee"] == 3 && $flowData["ProspectiveUser"]["state_flow"] >= 3 && $flowData["ProspectiveUser"]["state_flow"] <= 6 && $flowData["ProspectiveUser"]["remarketing"] == 0 ) {
	        // $user_remarketing = Configuration::get("user_remarketing");
	        // if (!empty($user_remarketing) || is_null($user_remarketing) ) {
	        // 	$user_remarketing = 126;
	        // }        
			// $this->FlowStage->ProspectiveUser->save(["id" => $flowData["ProspectiveUser"]["id"], "remarketing" => 1, "user_id" => $user_remarketing,"prorroga_two" => 1, "valid" => 0, "user_lose" => $flowData["ProspectiveUser"]["user_id"], "date_lose" => date("Y-m-d") ]);
            // $this->updateUserSet($flowData["ProspectiveUser"]["user_id"],$user_remarketing,$flowData["ProspectiveUser"]["id"]);

            $this->updateStateProspectiveFlow($datosAprovee["Approve"]["flujo_id"],Configure::read('variables.control_flujo.flujo_cancelado'));
			$this->insertProspectiveDesciptionCancel($datosAprovee["Approve"]["flujo_id"],$datosAprovee['FlowStage']['description']." | Aprobado por: ".
				(AuthComponent::user("email") == "ventas@kebco.co" ? "Sistema KEBCO" : AuthComponent::user("name")) );

		}elseif ($datosAprovee["Approve"]["type_aprovee"] == 4) {
			$this->updateStateProspectiveFlow($flowData["ProspectiveUser"]["id"],Configure::read('variables.control_flujo.flujo_contactado'));	
			$this->Session->setFlash('Información guardada satisfactoriamente', 'Flash/success');
		}elseif ($datosAprovee["Approve"]["type_aprovee"] == 5) {

			$this->FlowStage->ProspectiveUser->save(["id" => $flowData["ProspectiveUser"]["id"], "date_prorroga_final" => $datosAprovee["Approve"]["deadline"],"valid" => 0 ]);
			$this->Session->setFlash('Información guardada satisfactoriamente', 'Flash/success');
		}else{
			$this->updateStateProspectiveFlow($datosAprovee["Approve"]["flujo_id"],Configure::read('variables.control_flujo.flujo_cancelado'));
			$this->insertProspectiveDesciptionCancel($datosAprovee["Approve"]["flujo_id"],$datosAprovee['FlowStage']['description']." | Aprobado por: ".
				(AuthComponent::user("email") == "ventas@kebco.co" ? "Sistema KEBCO" : AuthComponent::user("name")) );
			$this->Session->setFlash('Información guardada satisfactoriamente', 'Flash/success');
		}

	}

	public function approve_qt(){
		$this->autoRender = false;

		$this->loadModel("Approve");
		$this->Approve->recursive = -1;

		$datosAprovee = $this->Approve->findById($this->request->data["id"]);

		$datos = $this->request->data;

		$this->request->data = [
			"inlineRadioOptions" => $datosAprovee["Approve"]["inlineRadioOptions"],
			"aprovee" => true,
			"FlowStage" => [
				"flujo_id" => $datosAprovee["Approve"]["flujo_id"],
				"flowstage_id" => $datosAprovee["Approve"]["flowstage_id"],
				"quotation_id" => $datosAprovee["Approve"]["quotation_id"],
				"copias_email" => $datosAprovee["Approve"]["copias_email"],
			],
		];


		$dataProspecto = ["ProspectiveUser" => [ "id" => $datosAprovee["Approve"]["flujo_id"], "valid" => 0 ] ];
		$this->FlowStage->ProspectiveUser->save($dataProspecto);

		$datosAprovee["Approve"]["state"] = 1;
		$datosAprovee["Approve"]["modified"] = date("Y-m-d H:i:s");
		$this->Approve->save($datosAprovee);

		$this->Session->setFlash(__('La cotización aprobada correctamente.'),'Flash/success');
		$this->saveStateCotizado();
	}

	public function reject_qt(){

		$this->autoRender = false;
		$this->loadModel("Approve");
		$this->Approve->recursive = -1;
		$datosAprovee = $this->Approve->findById($this->request->data["id"]);
		$datosAprovee["Approve"]["modified"] = date("Y-m-d H:i:s");
		$datosAprovee["Approve"]["state"] = 2;
		$this->Approve->save($datosAprovee);

		$subject = "Rechazo de cotización para el flujo ".$datosAprovee["Approve"]["flujo_id"]." KEBCO AlmacenDelPintor.com";

		$this->loadModel("User");
		$this->User->recursive = -1;
		$user = $this->User->findById($datosAprovee["Approve"]["user_id"]);

		$dataProspecto = ["ProspectiveUser" => [ "id" => $datosAprovee["Approve"]["flujo_id"], "valid" => 0 ] ];
		$this->FlowStage->ProspectiveUser->save($dataProspecto);

		$options = array(
			'to'		=> [ $user["User"]["email"] ],
			'template'	=> 'reject_qt',
			'subject'	=> $subject,
			'vars'		=> ["flujo" => $datosAprovee["Approve"]["flujo_id"], "razon" => $this->request->data["razon"] ]
		);
		
		$this->sendMail($options);

	}

	public function reject_cancel(){

		$this->autoRender = false;
		$this->loadModel("Approve");
		$this->Approve->recursive = -1;
		$datosAprovee = $this->Approve->findById($this->request->data["id"]);
		$datosAprovee["Approve"]["modified"] = date("Y-m-d H:i:s");
		$datosAprovee["Approve"]["state"] = 2;
		$this->Approve->save($datosAprovee);

		$subject = "Rechazo de cancelación para el flujo ".$datosAprovee["Approve"]["flujo_id"]." KEBCO AlmacenDelPintor.com";

		$this->loadModel("User");
		$this->User->recursive = -1;
		$user = $this->User->findById($datosAprovee["Approve"]["user_id"]);

		$optionsUpdate = ["id" => $datosAprovee["Approve"]["flujo_id"], "valid" => 0 ];

		if ($datosAprovee["Approve"]["type_aprovee"] == 5) {
			$optionsUpdate["date_prorroga_final"] = date("Y-m-d",strtotime("+".Configure::read("DIAS_PRORROGA_TWO"). " day"));
		}

		$dataProspecto = ["ProspectiveUser" => $optionsUpdate  ];
		$this->FlowStage->ProspectiveUser->save($dataProspecto);

		$options = array(
			'to'		=> [ $user["User"]["email"]  ],
			'template'	=> 'reject_qt',
			'subject'	=> $subject,
			'vars'		=> ["flujo" => $datosAprovee["Approve"]["flujo_id"], "razon" => $this->request->data["razon"], "cancelar" => $datosAprovee["Approve"]["type_aprovee"] == 2 ? true : false ]
		);
		
		$this->sendMail($options);

	}


	public function reenviar(){
		$this->autoRender 	= false;

		$datos 				= $this->FlowStage->findById($this->FlowStage->id_latest_regystri_state_cotizado($this->request->data["id"]));
		$rutaURL 			= 'Quotations/view/'.$this->encryptString($datos['FlowStage']['document']);

		$this->loadModel("Quotation");
		$this->request->data['FlowStage']['flujo_id'] = $this->request->data["id"];

		$this->sendEmailInformationQuotation($rutaURL,trim($datos['FlowStage']['copias_email']),$datos['FlowStage']['codigoQuotation'], $this->Quotation->field("customer_note",["id"=>$datos["FlowStage"]["document"]]));

		$this->Session->setFlash(__('La cotización enviada correctamente.'),'Flash/success');
	}


	public function saveStateCotizado(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {

			$this->loadModel("Config");
			$this->loadModel("QuotationsProduct");
			$this->loadModel("Quotation");

			$dataFlujo = $this->FlowStage->ProspectiveUser->field("user_id",["id" => $this->request->data["FlowStage"]["flujo_id"]]);

			$userValidate = empty($this->Config->field("users_validate",["id" => 1]) ) ? null : explode(",", $this->Config->field("users_validate",["id" => 1]));

			$productsMarginCero = $this->QuotationsProduct->find("count",["conditions" => ["margen" => 0, "quotation_id" => $this->request->data["FlowStage"]["quotation_id"]]]);

			$valorQuotationData = $this->Quotation->field("total",["id"=> $this->request->data["FlowStage"]["quotation_id"]] );
			$currencyQuotation  = $this->Quotation->field("currency",["id"=> $this->request->data["FlowStage"]["quotation_id"]] );
			$comision_completa  = $this->Quotation->field("comision_completa",["id"=> $this->request->data["FlowStage"]["quotation_id"]] );

			$userValue = $this->FlowStage->ProspectiveUser->User->field("role",["id" => $dataFlujo ] );
			$userEmail = $this->FlowStage->ProspectiveUser->User->field("email",["id" => $dataFlujo ] );

			if( 
				( 
					//( !is_null($userValidate) && in_array($dataFlujo, $userValidate) ) || 
					( ( $productsMarginCero >= 1 || $currencyQuotation == 'money_COP' ) && $userEmail != "jotsuar@gmail.com" )  
					//|| $userValue == "Asesor Externo"
				)  
				&& !isset($this->request->data["aprovee"])  
			){
				$this->loadModel("Approve");

				$datosAprovee = ["Approve" => [
					"flujo_id" => $this->request->data["FlowStage"]["flujo_id"],
					"flowstage_id" => $this->request->data["FlowStage"]["flowstage_id"],
					"quotation_id" => $this->request->data["FlowStage"]["quotation_id"],
					"copias_email" => $this->request->data["FlowStage"]["copias_email"],
					"inlineRadioOptions" => $this->request->data["inlineRadioOptions"],
					"comision_completa" => $comision_completa,
					"user_id" => $dataFlujo,
				] ];
				$this->Approve->create();
				$this->Approve->save($datosAprovee);

				$dataProspecto = ["ProspectiveUser" => [ "id" => $this->request->data["FlowStage"]["flujo_id"], "valid" => 1, "returned" => 1 ] ];

				$this->FlowStage->ProspectiveUser->save($dataProspecto);
				$this->Session->setFlash(__('La cotización generada correctamente, fue enviada a validación, cuando sea validada se enviará al cliente y se podrá continuar con el flujo.'),'Flash/success');
				return true;
			}
			
			$flowStage_id									= $this->request->data['FlowStage']['flowstage_id'];
			if (isset($this->request->data['FlowStage']['quotation_id'])) {
				$datosQuotation 							= $this->FlowStage->Quotation->get_data($this->request->data['FlowStage']['quotation_id']);
				$documento 									= 1;
				$datos['FlowStage']['document']				= $this->request->data['FlowStage']['quotation_id'];
				$datos['FlowStage']['priceQuotation']		= $datosQuotation['Quotation']['total'];
				$datos['FlowStage']['codigoQuotation'] 		= $datosQuotation['Quotation']['codigo'];
				$add 										= true;
			} else {
				$documento 									= $this->loadDocumentPdf($this->request->data['FlowStage']['document'],'flujo/cotizado');
				$datos['FlowStage']['document']				= $this->Session->read('documentoModelo');
				$datos['FlowStage']['nameDocument']			= $this->request->data['FlowStage']['nameDocument'];
				// $datos['FlowStage']['priceQuotation']		= $this->replaceText($this->request->data['FlowStage']['priceQuotation'],".", "");
				$datos['FlowStage']['priceQuotation']		= $this->replaceText($datos['FlowStage']['priceQuotation'],",", "");
				$datos['FlowStage']['codigoQuotation']		= $this->generateIdentificationQuotation($this->request->data['FlowStage']['flujo_id']);
				$add 										= false;
			}
			$datos['FlowStage']['state_flow']				= Configure::read('variables.nombre_flujo.flujo_cotizado');
			$datos['FlowStage']['prospective_users_id']		= $this->request->data['FlowStage']['flujo_id'];
			$datos['FlowStage']['copias_email']				= $this->request->data['FlowStage']['copias_email'];
			if ($documento == 1) {
				// $datos['FlowStage']['id']             	= $this->FlowStage->new_row_model() + 1;
				$this->FlowStage->create();
				if ($this->FlowStage->save($datos)) {
					$id_inset 								= $this->FlowStage->id;

					$this->loadModel("Quotation");
					$this->Quotation->save(["Quotation"=> ["id" => $datos["FlowStage"]["document"], "final" => 1] ]);

					$this->saveAtentionTimeFlujoEtapas($this->request->data['FlowStage']['flujo_id'],'cotizado_date','cotizado_time','cotizado');
					$this->updateStateProspectiveFlow($this->request->data['FlowStage']['flujo_id'],Configure::read('variables.control_flujo.flujo_cotizado'));

					$this->FlowStage->ProspectiveUser->save(
						["ProspectiveUser"=> [
							"id" 			 => $this->request->data['FlowStage']['flujo_id'], 
							"quotation_id"   => $datos["FlowStage"]["document"], 
							"updated" => date("Y-m-d H:i:s"), 
							"notified" => 0,
							"alert_one" => 0,
							"returned" => 0,
							"alert_two" => 0,
							"deadline_notified" => null,
							"user_lose" => null,
							"date_final_alert" => null,
							"date_quotation"   => date("Y-m-d H:i:s"),
							"date_alert" 	   => date("Y-m-d H:i:s", strtotime("+".Configure::read("DIAS_NOTIFY_ONE"). " day")),
						] ]
					);

					// try {

					// 	//update-chat
					// 	$whitelist = array(
				    //         '127.0.0.1',
				    //         '::1'
				    //     );
				    //     if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
				    //         $url = 'http://localhost/chatApp/include/api.php';
				    //     }else{
				    //     	$url = 'https://chat.kebcousa.com/api.php';
				    //     }

					// 	$HttpSocket = new HttpSocket(['ssl_allow_self_signed' => false, 'ssl_verify_peer' => false, 'ssl_verify_host' =>false ]);

					// 	$request = ["header" => [
					// 		"Content-Type" => 'application/x-www-form-urlencoded',
					// 	]];

					// 	$params 	   = ["function"=>"update-flow-quotation","token"=>"57b9ef2591277ab300134d7a4d18dfb5b8a9b242","flow_id"=>$this->request->data['FlowStage']['flujo_id'], "quotation" => $datos["FlowStage"]["document"] ];

					// 	$responseToken = $HttpSocket->post($url,$params,$request);
					// 	$this->log($responseToken);
					// 	$this->log($responseToken->body);
					// 	$responseToken = json_decode($responseToken->body);

					// } catch (Exception $e) {
					// 	$this->log($e->getMessage());
					// }

					
					
					if ($add) {
						$emailCliente 						= $this->emailClientFlujo($this->request->data['FlowStage']['flujo_id']);
						$rutaURL 							= 'Quotations/view/'.$this->encryptString($datos['FlowStage']['document']);
					} else {
						$rutaURL 							= 'files/flujo/cotizado/'.$datos['FlowStage']['document'];
					}
					$this->updateFlowStageSendCotizacion($flowStage_id,1);
                	$this->saveDataLogsUser(1,'FlowStage',$id_inset,Configure::read('variables.nombre_flujo.flujo_contactado').' - '.Configure::read('variables.nombre_flujo.flujo_cotizado'));
                	$this->saveDataLogsUser(6,'FlowStage',$id_inset);


                	$prospectiveUserData = $this->FlowStage->ProspectiveUser->find('first',["conditions"=> [ "ProspectiveUser.id" => $this->request->data['FlowStage']['flujo_id'] ], "recursive" => 1 ]);

					if ($prospectiveUserData["ProspectiveUser"]["contacs_users_id"] > 0) {
	                    $lastFlow = $this->FlowStage->ProspectiveUser->find("first",["recursive"=>1,"order"=>["ProspectiveUser.created" => "DESC" ], "conditions" => ["ProspectiveUser.contacs_users_id" => $prospectiveUserData["ProspectiveUser"]["contacs_users_id"], "ProspectiveUser.id !="=> $this->request->data['FlowStage']['flujo_id'] ] ]);
	                }else{
	                    $lastFlow = $this->FlowStage->ProspectiveUser->find("first",["recursive"=>1,"order"=>["ProspectiveUser.created" => "DESC" ], "conditions" => ["ProspectiveUser.clients_natural_id" => $prospectiveUserData["ProspectiveUser"]["clients_natural_id"], "ProspectiveUser.id !="=> $this->request->data['FlowStage']['flujo_id'] ] ]);
	                }

	                $copias_email = $datos['FlowStage']['copias_email'];
	                if(!empty($lastFlow) && $lastFlow["ProspectiveUser"]["user_id"] != $prospectiveUserData["ProspectiveUser"]["user_id"]){
	                	if(empty($copias_email) || trim($copias_email) == ""){
	                		$copias_email = $lastFlow["User"]["email"];
	                	}else{
	                		$copias_email 	= explode(",", $copias_email);
	                		$copias_email[] = $lastFlow["User"]["email"];
	                		$copias_email   = implode(",", $copias_email);
	                	}
	                }

                	if (isset($this->request->data['inlineRadioOptions']) && intval($this->request->data['inlineRadioOptions']) === 1) {
                		$this->sendEmailInformationQuotation($rutaURL,$copias_email,$datos['FlowStage']['codigoQuotation'], $datosQuotation["Quotation"]["customer_note"]);
					}

					if(!empty($lastFlow) && $lastFlow["ProspectiveUser"]["user_id"] != $prospectiveUserData["ProspectiveUser"]["user_id"]){
						$name_cliente 	= $this->nameClientFlujo($this->request->data['FlowStage']['flujo_id']);
	                	$options = array(
							'to' 		=> $lastFlow["User"]["email"],
							'template'	=> 'cotizacion_cliente_user',
							'subject'	=> 'El asesor '.$prospectiveUserData["User"]["name"]." esta cotizando a un cliente que cotizaste recientemente",
							'vars'		=> array("flujo" => $lastFlow["ProspectiveUser"]["id"], "cliente" => $name_cliente, "usuario" => $prospectiveUserData["User"]["name"] ),
						);

						$this->sendMail($options);
	                }

				}
				return $this->request->data['FlowStage']['flujo_id'];
			} else {
				return $documento;
			}
		}
	}

	public function addImportFinal(){
		$this->loadModel("Import");
		$this->autoRender = false;
		// try {

			$importID = null;
			if ($this->request->data["type"] == 2) {
				$brand_id = $this->request->data["brand_id"];
				$this->loadModel('Import');
				$conditions 				= array(
		                    "Import.send_provider" => 0, 'Import.state' => Configure::read('variables.importaciones.solicitud'),'Import.brand_id' => $brand_id  );
		        $order  	=  ["Import.created" => "DESC"];
		        $recursive  = -1;
		        $importActive = $this->Import->find("first",compact("conditions","recursive","order"));

		        if (!empty($importActive)) {
		        	$importID = $importActive["Import"]["id"];
		        	$importActive["Import"]["modified"] = date("Y-m-d H:i:s");

		        	if (is_null($importActive["Import"]["fecha_envio"])) {
		        		$importActive["Import"]["fecha_envio"] = date("Y-m-d H:i:s");
		        	}

		        	$this->Import->save($importActive["Import"]);
		        }
			}
			if (is_null($importID)) {
				$code_import  = $this->generateCodeImport();
				$importID 	  = $this->saveAddImport($code_import,$this->request->data["motive"], $this->request->data["textBrand"], $this->request->data["nota"],$this->request->data["iva"]);
			}

			$products 	  = $this->organiceInfoProducts($this->request->data["products"],$this->request->data["currency"]);
			$productIds   = [];
			
			foreach ($products as $key => $value) {

				if (in_array($value["id_product"], $productIds) || $this->Import->ImportProduct->field("id",["import_id"=>$importID, "product_id" => $value["id_product"] ]) != false ) {
					continue;
				}else{
					$productIds[] = $value["id_product"];
				}

	       		$arrayImportacionProducto['ImportProduct']['import_id']        				= $importID;
				$arrayImportacionProducto['ImportProduct']['state_import']                  = Configure::read('variables.control_importacion.solicitud_importacion');
	       		$arrayImportacionProducto['ImportProduct']['quotations_products_id']        = 0;
	       		$arrayImportacionProducto['ImportProduct']['product_id']        			= $value["id_product"];
	       		$arrayImportacionProducto['ImportProduct']['quantity']        				= $value["quantity"];
	       		$arrayImportacionProducto['ImportProduct']['currency']        				= $value["currency"];
	       		$arrayImportacionProducto['ImportProduct']['price']        					= $value["cost"];
	       		$arrayImportacionProducto['ImportProduct']['flujo']        					= $value["flujo"];
	       		$arrayImportacionProducto['ImportProduct']['quantity_back']        			= $value["quantity_back"];
	       		$this->Import->ImportProduct->create();
	            $this->Import->ImportProduct->save($arrayImportacionProducto);
			}
			
			$this->saveDetailProductImport($importID, $this->request->data["products"]);
			if(!empty($this->request->data["flujos"])){
				$this->saveImportIntoFlow($this->request->data["flujos"],$importID);
			}

			$this->loadModel("ImportRequest");

			$this->ImportRequest->recursive = -1;
			$request = $this->ImportRequest->findById($this->request->data["request_id"]);

			$request["ImportRequest"]["import_id"] 		= $importID;
			$request["ImportRequest"]["user_id"] 		= AuthComponent::user("id");
			$request["ImportRequest"]["type_money"] 	= $this->request->data["currency"];
			$request["ImportRequest"]["state"] 			= 2;
			$this->emailAvisoRevisionGerencia($this->encryptString($importID));
			$this->ImportRequest->save($request);

			$internacional = isset($this->request->data["internacional"]) && $this->request->data["internacional"] == 1 ? 1 : 0;

			$noApprove = $this->ImportRequest->ImportRequestsDetail->find("all",["recursive" => -1,"conditions" => ["state" =>2, "type_request" => 2,"import_request_id" => $this->request->data["request_id"] ] ]);

			if(!empty($this->request->data["after"]) || !empty($noApprove) ){

				if (!empty($this->request->data["after"])) {
					$this->loadModel("ImportRequest");
					$import_id = $this->ImportRequest->getOrSaveRequest($this->request->data["brand_id"],$internacional);					
					foreach ($this->request->data["after"] as $key => $value) {
						$this->ImportRequest->ImportRequestsDetail->recursive = -1;
						$detail = $this->ImportRequest->ImportRequestsDetail->findById($value);
						$detail["ImportRequestsDetail"]["import_request_id"] = $import_id;
						$this->ImportRequest->ImportRequestsDetail->save($detail);
					}
				}

				if ( !empty($noApprove) ) {	
					$this->loadModel("ImportRequest");
					$import_id = $this->ImportRequest->getOrSaveRequest($this->request->data["brand_id"],$internacional);					
					foreach ($noApprove as $key => $value) {
						$value["ImportRequestsDetail"]["import_request_id"] = $import_id;
						$this->ImportRequest->ImportRequestsDetail->save($value);
					}
				}

			}

			$this->ImportRequest->ImportRequestsDetail->updateAll(
			    array('ImportRequestsDetail.state' => 3),
			    array('ImportRequestsDetail.id' => $this->request->data["details"])
			);

			$this->Session->setFlash(__('La solicitud fue generada correctamente.'),'Flash/success');
		// } catch (Exception $e) {
		// 	$this->Session->setFlash(__('La solicitud no fue generada correctamente.'),'Flash/error');
		// }

	}

	private function saveDetailProductImport($import_id, $products){
		$this->loadModel("ImportProductsDetail");
		foreach ($products as $key => $value) {
			$value["import_id"] 				= $import_id;
			$value["id"] 						= null;
			$value["product_id"] 				= $value["id_product"];
			$value["import_requests_detail_id"] = $value["detalle"];
			$this->ImportProductsDetail->create();
			$this->ImportProductsDetail->save($value);
		}
		return true;
	}

	private function saveImportIntoFlow($flows,$import_id){
		$this->loadModel("ImportsProspectiveUser");
		foreach ($flows as $key => $value) {
			if($value == 0){
				continue;
			}
			$this->FlowStage->ProspectiveUser->recursive = -1;
			$flujo = $this->FlowStage->ProspectiveUser->findById($value);

			if(empty($flujo["ProspectiveUser"]["import_id"])){
				$flujo["ProspectiveUser"]["import_id"] = $import_id;
				$this->FlowStage->ProspectiveUser->save($flujo);
			}

			$datosUnion = array(
				"ImportsProspectiveUser" => array(
					"import_id" => $import_id,
					"prospective_user_id" => $value
				)
			);
			$this->ImportsProspectiveUser->create();
			$this->ImportsProspectiveUser->save($datosUnion);

		}
		return true;
	}

	private function organiceInfoProducts($products, $currency){
		$newArrayProducts = array();
		foreach ($products as $key => $value) {
			$value["currency"] = $currency;
			if($value["type"] == 3 || $value["type"] == 2 || $value["type"] == 4 ){
				$value["quantity_back"] = $value["quantity"];
			}else{
				$value["quantity_back"] = 0;
			}
			if(!array_key_exists($value["id_product"], $newArrayProducts)){
				$newArrayProducts[$value["id_product"]] = $value;
			}else{
				$newArrayProducts[$value["id_product"]]["quantity"] += $value["quantity"];
				$newArrayProducts[$value["id_product"]]["quantity_back"] += $value["quantity_back"];
			}
			$this->updateValueProduct($value["id_product"],$currency,$value["cost"]);
		}
		return $newArrayProducts;
	}

	private function updateValueProduct($id_product, $currency, $value){

		$this->loadModel("Product");

		$productInfo = $this->Product->findById($id_product);

		if(strtolower($currency) == "usd"){
			$productInfo["Product"]["purchase_price_usd"] = $value;
		}else{
			$productInfo["Product"]["purchase_price_cop"] = $value;
		}

		$this->Product->save($productInfo["Product"]);

	}

	public function sendEmailInformationQuotationNew2($rutaURL,$copias_email,$codigoCotizacion, $textoCliente = null){
		$email_defecto 				= Configure::read('variables.emails_defecto');
		$emailCliente 				= array();
		$copias 				    = array();
    	$emailCliente 				= $this->emailClientFlujo($this->request->data['FlowStage']['flujo_id']);
    	$datosCliente 				= $this->getDataCustomer($this->request->data['FlowStage']['flujo_id']);

		if ($copias_email != '') {
    		$emails 				= explode(',',$copias_email);
			$copias 				= $emails;
    	}
    	$acopleUrl = "?e=".$this->encryptString($emailCliente);
    	// $emailCliente 				= array_merge($email_defecto, $emailCliente);
    	$copias[] 					= AuthComponent::user('email');
		$name_cliente 				= $this->nameClientFlujo($this->request->data['FlowStage']['flujo_id']);
		$datosFlujo 				= $this->FlowStage->ProspectiveUser->get_data($this->request->data['FlowStage']['flujo_id']);
		$datos_asesor				= $this->FlowStage->ProspectiveUser->User->get_data($datosFlujo['ProspectiveUser']['user_id']);
		$requerimientoFlujo 		= $this->FlowStage->find_reason_prospective($this->request->data['FlowStage']['flujo_id']);

		$this->loadModel("Quotation");
		$nameQt = $this->Quotation->field("name",["codigo" => $codigoCotizacion ]);

		$this->loadModel("Config");
		$iva 			= $this->Config->field("ivaCol",["id" => 1]);

		if (file_exists(WWW_ROOT.'/files/quotations/'.$codigoCotizacion.'.pdf')) {
			$options 					= array(
				'to'		=> $emailCliente,
				'template'	=> 'quote_sent',
				'cc'		=> $email_defecto,
				'subject'	=> 'Has recibido la cotización '.$codigoCotizacion.' de KEBCO AlmacenDelPintor.com',
				'vars'		=> array('codigo' => $codigoCotizacion,'nameClient' => $name_cliente,'nameAsesor' => $datos_asesor['User']['name'],'requerimiento' => $requerimientoFlujo,'ruta'=>$rutaURL,"texto" => $textoCliente,"iva" => $iva),
				//'file'		=> 'files/quotations/'.$codigoCotizacion.'.pdf'
			);
		} else {
			$options 					= array(
				'to'		=> $emailCliente,
				'template'	=> 'quote_sent',
				'cc'		=> $email_defecto,
				'subject'	=> 'Has recibido la cotización '.$codigoCotizacion.' de KEBCO AlmacenDelPintor.com',
				'vars'		=> array('codigo' => $codigoCotizacion,'nameClient' => $name_cliente,'nameAsesor' => $datos_asesor['User']['name'],'requerimiento' => $requerimientoFlujo,'ruta'=>$rutaURL,"texto" => $textoCliente,"iva" => $iva)
			);
		}

		$options2 		= $options;
		$options2["to"] = $copias;

		$options2["vars"]["ruta"] = str_replace($acopleUrl, "", $options2["vars"]["ruta"]);




		if(!empty($datos_asesor["User"]["password_email"])){
			$email  	= $datos_asesor["User"]["email"] == "jotsuar@gmail.com" ? "pruebascorreojs1@gmail.com" : $datos_asesor["User"]["email"];
			$password 	= str_replace("@@KEBCO@@", "", base64_decode($datos_asesor["User"]["password_email"]) );
			$config 	= array("username" => $email, "password" => $password);
			$this->log("con correo","debug");
			$this->log($options2,"debug");
			$this->sendMail($options2, null, $config);
		}else{
			$this->log("normal","debug");
			$this->log($options2,"debug");
			$this->sendMail($options2);
		}	

		try {
			//$this->sendQoutationWhatsapp($datosCliente,$datos_asesor["User"]["name"],$options["subject"], $options["vars"]["ruta"],$nameQt,$acopleUrl);
			$options["vars"]["ruta"].=$acopleUrl;
			$this->sendMailSendGridQuotation($options,$datos_asesor);
		} catch (Exception $e) {
			$this->log($e->getMessage(),"debug");
		}	
	}

	private function accortUrl($url){
		$codigo = "KEBCO-ALMACEN-DEL-PINTOR_".time();
		try {				
			$json = file_get_contents("https://cutt.ly/api/api.php?key=afab6f73f9375c95192d4a83a119d56b&short=$url&name=$codigo");
			$data = json_decode ($json, true);
			if (isset($data["url"]["status"]) && $data["url"]["status"] == 7) {
				$url = $data["url"]["shortLink"];
			}
		} catch (Exception $e) {
			$url = $url;
		}
		return $url;
	}

	public function sendQoutationWhatsappTemplate($datosCliente, $asesor, $subject, $urlLink, $requerimiento,$acopleUrl,$numberAsesor, $codigoCotizacion){
		$phones = array();

		$urlLink = str_replace("Quotations/view/","",$urlLink);

		$datosCliente["telephone"] = str_replace([" ","+57","_"], "", $datosCliente["telephone"]);
		$datosCliente["cell_phone"] = str_replace([" ","+57","_"], "", $datosCliente["cell_phone"]);

		if(!empty($datosCliente["telephone"]) && strlen($datosCliente["telephone"]) == 10){
			$phones[] = $datosCliente["telephone"];
		}

		if(!empty($datosCliente["cell_phone"]) && strlen($datosCliente["cell_phone"]) == 10){
			$phones[] = $datosCliente["cell_phone"];
		}

		if (count($phones) == 2 && $phones[0] == $phones[1] ) {
			unset($phones[1]);
		}

		$whitelist = array(
            '127.0.0.1',
            '::1'
        );
        if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $phones = ['3136429175'];
        }

		if(!empty($phones)){
			foreach ($phones as $key => $phone) {
				$strMsg = '
					{
					   "messaging_product": "whatsapp",
					   "to": "57'.$phone.'",
					   "type": "template",
					   "template": {
					       "name": "cotizacion",
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
					                       "text": "'.$codigoCotizacion.'"
					                   }
					               ]
					           },
					           {
					               "type": "body",
					               "parameters": [
					                   {
					                       "type": "text",
					                       "text": "'.$asesor.'"
					                   },
					                   {
					                       "type": "text",
					                       "text": "'.$requerimiento.'"
					                   },
					                   {
					                       "type": "text",
					                       "text": "'.$codigoCotizacion.'"
					                   },
					                   {
					                       "type": "text",
					                       "text": "https://wa.me/573014485566"
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
					                       "text": "'.$urlLink.'"
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

					$responseToken = $HttpSocket->post('https://graph.facebook.com/v15.0/100586116213138/messages',$strMsg,$request);
					$responseToken = json_decode($responseToken->body);

				} catch (Exception $e) {
					$this->log($e->getMessage());
				}	
			}

			

		}
	}

	private function sendQoutationWhatsapp($datosCliente, $asesor, $subject, $urlLink, $requerimiento,$acopleUrl,$numberAsesor, $codigoCotizacion){
		$phones = array();

		$datosCliente["telephone"] = str_replace([" ","+57","_"], "", $datosCliente["telephone"]);
		$datosCliente["cell_phone"] = str_replace([" ","+57","_"], "", $datosCliente["cell_phone"]);

		if(!empty($datosCliente["telephone"]) && strlen($datosCliente["telephone"]) == 10){
			$phones[] = $datosCliente["telephone"];
		}

		if(!empty($datosCliente["cell_phone"]) && strlen($datosCliente["cell_phone"]) == 10){
			$phones[] = $datosCliente["cell_phone"];
		}

		if (count($phones) == 2 && $phones[0] == $phones[1] ) {
			unset($phones[1]);
		}

		if(!empty($phones)){
			$HttpSocket = new HttpSocket();
			$urlLinkMeg = Configure::read("Application.URL_WPP")."sendLink?token=".Configure::read("Application.TOKEN_WPP");
			$urlMsg 	= Configure::read("Application.URL_WPP")."sendMessage?token=".Configure::read("Application.TOKEN_WPP");
			$urlCheck 	= Configure::read("Application.URL_WPP")."checkPhone?token=".Configure::read("Application.TOKEN_WPP")."&phone=";

			foreach ($phones as $key => $phone) {
				$paramsMessageLink = array(
					"phone" => "57".$phone,
					"body"  => $this->accortUrl(Router::url("/",true).$urlLink.$acopleUrl),
					"title"	=> $subject,
					"previewBase64" => Configure::read("Application.IMAGE_B64"),
				);

				$paramsMessage = array(
					"phone" => "57".$phone,
					"body"  => "El asesor *".$asesor."* te ha enviado la cotización que solicitaste a través de KEBCO S.A.S. para el requerimiento: *".$requerimiento." asociado al código de cotización ".$codigoCotizacion."*. Si deseas ponerte en contacto con el asesor en el siguiente enlace lo podrás hacer: "
				);

				$paramsMessageLinkAsesor = array(
					"phone" => "57".$phone,
					"body"  => 'https://wa.me/57'.$numberAsesor,
					"title"	=> "Contacta el asesor",
					"previewBase64" => Configure::read("Application.IMG_WHATSAPP"),
				);

				try {
					$exists = $HttpSocket->get($urlCheck."57".$phone);
					if(isset($exists->body)){
						$resp = json_decode($exists->body);
						if($resp->result == "exists"){
							$resultsMsg	 = $HttpSocket->post($urlMsg,$paramsMessage);
							$resultsLink = $HttpSocket->post($urlLinkMeg,$paramsMessageLink);
							$resultsMsg	 = $HttpSocket->post($urlLinkMeg,$paramsMessageLinkAsesor);
							$resp 		 = json_decode($resultsMsg->body);
						}
					}				
				} catch (Exception $e) {
					$this->log($e->getMessage());
				}

			}
			
		}
	}

	public function sendEmailInformationQuotation($rutaURL,$copias_email,$codigoCotizacion, $textoCliente = null){
		$this->loadModel("Quotation");
		$this->loadModel("User");
		$copias_email 				= trim($copias_email);
		
		$emailCliente 				= array();
    	$emailCliente[0] 			= $this->emailClientFlujo($this->request->data['FlowStage']['flujo_id']);
		if ($copias_email != '') {
    		$emails 				= explode(',',$copias_email);
			if (isset($emails[0])) {
				$emailCliente 		= array_merge($emails, $emailCliente);
			} else {
				$emailCliente[1] 	= $copias_email;
			}
    	}
    	$rutaNormal = $rutaURL;
    	$rutaURL.="?e=".$this->encryptString(implode(",", $emailCliente));
    	// $acopleUrl = "?e=".$this->encryptString(implode(",", $emailCliente));
    	$acopleUrl = "";
    	
		$name_cliente 				= $this->nameClientFlujo($this->request->data['FlowStage']['flujo_id']);
		$datosFlujo 				= $this->FlowStage->ProspectiveUser->get_data($this->request->data['FlowStage']['flujo_id']);
		$datos_asesor				= $this->FlowStage->ProspectiveUser->User->get_data($datosFlujo['ProspectiveUser']['user_id']);
		$requerimientoFlujo 		= $this->FlowStage->find_reason_prospective($this->request->data['FlowStage']['flujo_id']);

		$email_defecto 				= [];

		if (!empty($datos_asesor["User"]["copias"])) {
			$copias = $this->User->find("all",["recursive" => -1, "fields" => ["User.email"], "conditions" => ["User.id" => explode(",", $datos_asesor["User"]["copias"]) ] ]);
			if (!empty($copias)) {
				$copias 		= Set::extract($copias,"{n}.User.email");
				$email_defecto  = $copias;
			}
		}else{
			$email_defecto 				= Configure::read('variables.emails_defecto');
			if (in_array($datos_asesor["User"]["email"], ["ventas2@almacendelpintor.com"])) {
				unset($email_defecto[1]);
			}
		}

		$email_defecto[] 			= $datos_asesor["User"]["email"];
		$emailCliente 				= array_merge($email_defecto, $emailCliente);
    	$emailCliente[] 			= AuthComponent::user('email');
		if (file_exists(WWW_ROOT.'/files/quotations/'.$codigoCotizacion.'.pdf')) {
			$options 					= array(
				'to'		=> $emailCliente,
				'template'	=> 'quote_sent',
				'cc'		=> $email_defecto,
				'subject'	=> 'Has recibido la cotización '.$codigoCotizacion.' de KEBCO AlmacenDelPintor.com',
				'vars'		=> array('codigo' => $codigoCotizacion,'nameClient' => $name_cliente,'nameAsesor' => $datos_asesor['User']['name'],'requerimiento' => $requerimientoFlujo,'ruta'=>$rutaURL,"texto" => $textoCliente,"quotationID" => $this->Quotation->field("id",["codigo" => $codigoCotizacion ])),
				//'file'		=> 'files/quotations/'.$codigoCotizacion.'.pdf'
			);
		} else {
			$options 					= array(
				'to'		=> $emailCliente,
				'template'	=> 'quote_sent',
				'cc'		=> $email_defecto,
				'subject'	=> 'Has recibido la cotización '.$codigoCotizacion.' de KEBCO AlmacenDelPintor.com',
				'vars'		=> array('codigo' => $codigoCotizacion,'nameClient' => $name_cliente,'nameAsesor' => $datos_asesor['User']['name'],'requerimiento' => $requerimientoFlujo,'ruta'=>$rutaURL,"texto" => $textoCliente,"quotationID" => $this->Quotation->field("id",["codigo" => $codigoCotizacion ]))
			);
		}

		$this->loadModel("Quotation");
		$nameQt 		= $this->Quotation->field("name",["codigo" => $codigoCotizacion ]);
		$datosCliente 	= $this->getDataCustomer($this->request->data['FlowStage']['flujo_id']);
			
		try {
			// $this->sendQoutationWhatsapp($datosCliente,trim($datos_asesor["User"]["name"]),$options["subject"], $rutaNormal,$nameQt,$acopleUrl, trim($datos_asesor["User"]["cell_phone"]),trim($codigoCotizacion) );
			$this->sendQoutationWhatsappTemplate($datosCliente,trim($datos_asesor["User"]["name"]),$options["subject"], $rutaNormal,$nameQt,"", trim($datos_asesor["User"]["cell_phone"]),trim($codigoCotizacion) );
		} catch (Exception $e) {
			$this->log($e->getMessage(),"debug");
		}

		if(!empty($datos_asesor["User"]["password_email"])){
			$email  	= $datos_asesor["User"]["email"] == "jotsuar@gmail.com" ? "pruebascorreojs1@gmail.com" : $datos_asesor["User"]["email"];
			$password 	= str_replace("@@KEBCO@@", "", base64_decode($datos_asesor["User"]["password_email"]) );
			$config 	= array("username" => $email, "password" => $password);

			$this->sendMail($options, null, $config);
		}else{
			$this->sendMail($options);
		}		
	}


	
	public function nameClientFlujo($prospective_id){
		$datosP 			= $this->FlowStage->ProspectiveUser->get_data($prospective_id);
		if ($datosP['ProspectiveUser']['contacs_users_id'] > 0) {
			$datosC 		= $this->FlowStage->ProspectiveUser->ContacsUser->get_data($datosP['ProspectiveUser']['contacs_users_id']);
			$name 			= $datosC['ContacsUser']['name'];
		} else {
			$datosC 		= $this->FlowStage->ProspectiveUser->ClientsNatural->get_data($datosP['ProspectiveUser']['clients_natural_id']);
			$name 			= $datosC['ClientsNatural']['name'];
		}
		return $name;
	}

	public function change_negociado(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			$flujo_id 			= $this->request->data['flujo_id'];
			$state 				= $this->request->data['state'];

			$validateContact  	= $this->validateTimes(true);
			$valid 				= true;

			if  (!empty($validateContact["contact"])  || ( !empty($validateContact["quotation"])  )  ) {
				$valid = false;
			}

			if ($state == Configure::read('variables.control_flujo.flujo_cotizado') && $valid) {
				$datos				= $this->FlowStage->ProspectiveUser->get_data($flujo_id);
				$this->set(compact('datos'));

			} else {
				$this->render('/FlowStages/state_invalid');
			}
		}
	}

	public function get_order_purchase(){
		$this->layout 	= false;
		$conditions 	= array("prospective_users_id" => $this->request->data["flujo"], "state_flow" => Configure::read('variables.nombre_flujo.flujo_negociado'));
		$order 			= array("id" => "DESC");
		$recursive		= -1;
		$ordenCompra 	= $this->FlowStage->find("first",compact("conditions","order","recursive"));
		$this->set("ordenCompra",$ordenCompra);
	}

	public function get_payments_flow(){
		$this->layout 	= false;
		$conditions 	= array("prospective_users_id" => $this->request->data["flujo"], "state_flow" => Configure::read('variables.nombre_flujo.flujo_pagado'),"payment_verification" => 1);
		$order 			= array("id" => "DESC");
		$recursive		= -1;
		$pagos 	= $this->FlowStage->find("all",compact("conditions","order","recursive"));
		$datosProspecto = $this->FlowStage->ProspectiveUser->findById($this->request->data["flujo"]);
		$idLatestRegystri 				= $this->FlowStage->ProspectiveUser->FlowStage->id_latest_regystri($this->request->data['flujo']);
		$this->set("pagos",$pagos);
		$this->set("datosProspecto",$datosProspecto);
		$this->set("idLatestRegystri",$idLatestRegystri);
	}

	public function saveStateNegociado(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			if ($this->request->data['FlowStage']['document']['name'] != '') {
				$documento 										= $this->loadDocumentPdf($this->request->data['FlowStage']['document'],'flujo/negociado');
				$datos['FlowStage']['document']					= $this->Session->read('documentoModelo');
			} else {
				$documento = 1;
			}
			$datos['FlowStage']['state_flow']				= Configure::read('variables.nombre_flujo.flujo_negociado');
			$datos['FlowStage']['prospective_users_id']		= $this->request->data['FlowStage']['flujo_id'];
			$datos['FlowStage']['description']				= $this->request->data['FlowStage']['description'];
			if ($documento == 1) {
				// $datos['FlowStage']['id']             		= $this->FlowStage->new_row_model() + 1;
				$this->FlowStage->create();
				if ($this->FlowStage->save($datos)) {
					$id_inset 		= $this->FlowStage->id;
					$this->updateStateProspectiveFlow($this->request->data['FlowStage']['flujo_id'],Configure::read('variables.control_flujo.flujo_negociado'));
					$this->saveDataLogsUser(1,'FlowStage',$id_inset,Configure::read('variables.nombre_flujo.flujo_cotizado').' - '.Configure::read('variables.nombre_flujo.flujo_negociado'));
					$this->saveAtentionTimeFlujoEtapas($this->request->data['FlowStage']['flujo_id'],'negociado_date','negociado_time','negociado');
				}
				return $this->request->data['FlowStage']['flujo_id'];
			} else {
				return $documento;
			}
		}
	}

	public function applyPayment($id) {
		$this->autoRender = false;
		$this->FlowStage->recursive = -1;
		$flowStage 		= $this->FlowStage->findById($id);
		$flowStage['FlowStage']['state']					= 7;
		$flowStage['FlowStage']['payment_verification']		= 1;
		$datos = $flowStage;
		unset($datos["FlowStage"]["id"]);

		$this->FlowStage->create();
		$this->FlowStage->save($datos);
		$id_inset 		= $this->FlowStage->id;
		$this->updateStateProspectiveFlow($datos['FlowStage']['prospective_users_id'],Configure::read('variables.control_flujo.flujo_pagado'));
		$this->saveDataLogsUser(1,'FlowStage',$id_inset,Configure::read('variables.nombre_flujo.flujo_negociado').' - '.Configure::read('variables.nombre_flujo.flujo_pagado'));
		$this->saveAtentionTimeFlujoEtapas($datos['FlowStage']['prospective_users_id'],'pagado_date','pagado_time','pagado');

		$this->redirect(["controller" => "prospective_users", "action" => "index", "?" => ["q" => $datos['FlowStage']['prospective_users_id'] ]  ]);
	}

	public function change_pagado(){
		$this->layout 			= false;
		if ($this->request->is('ajax')) {
			$flujo_id 			= $this->request->data['flujo_id'];
			$state 				= $this->request->data['state'];

			$validateContact  	= $this->validateTimes(true);
			$valid 				= true;
			$datos				= $this->FlowStage->ProspectiveUser->get_data($flujo_id);

			if  (!empty($validateContact["contact"])  || ( !empty($validateContact["quotation"]) ) || $datos["ProspectiveUser"]["valid"] == 1  ) {
				$valid = false;
			}

			if ($state == Configure::read('variables.control_flujo.flujo_negociado') && $valid) {
				$count_pago 	= $this->FlowStage->ProspectiveUser->Payment->there_payment_history($flujo_id);
				$medios 		= Configure::read('variables.mediosPago');
				$datos			= $this->FlowStage->ProspectiveUser->get_data($flujo_id);

				$quotationFlow  = isset($datos["ProspectiveUser"]["quotation_id"]) && !empty($datos["ProspectiveUser"]["quotation_id"]) ? $this->FlowStage->Quotation->findById($datos["ProspectiveUser"]["quotation_id"]) : [];

				$valorQuotation = isset($quotationFlow["Quotation"]["total"]) && floatval($quotationFlow["Quotation"]["total"]) > 0 ? $quotationFlow["Quotation"]["total"] : $this->FlowStage->valor_latest_regystri_state_cotizado_flujo_id($flujo_id);


				$idFlowstage 			= $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
				$datosFlowstage 		= $this->FlowStage->get_data($idFlowstage);

				if(isset($datos["ProspectiveUser"]["quotation_id"]) && !empty($datos["ProspectiveUser"]["quotation_id"])){
					$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->findAllByQuotationId($datos["ProspectiveUser"]["quotation_id"]);
				}else{
					$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->findAllByQuotationId($datosFlowstage['FlowStage']['document']);
				}

				

				$fecha_cotizacion       = $datosFlowstage["FlowStage"]["created"];
			   	if(!is_null($fecha_cotizacion)){
			        $fecha_cotizacion = date("Y-m-d H:i:s",strtotime($fecha_cotizacion." +15 day"));

			        if(strtotime($fecha_cotizacion) < strtotime(date("Y-m-d H:i:s")) && $count_pago == 0 ){

			        	$html = "<div>";

			        	$html.= '<a class="alingicon d-inline-block " data-uid="'.$datos['ProspectiveUser']['id'].'" data-flow_stage="'.$idFlowstage.'" href="'.Router::url("/",true).'Quotations/add/'.$datos['ProspectiveUser']['id'].'/'.$idFlowstage.'/4'.'" data-toggle="tooltip" data-placement="right" title="Hacer cotización">
							HACER UNA NUEVA COTIZACIÓN &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
						</a><br><h2>No es posible subir un pago se debe volver a cotizar pasaron más de 15 días</h2>';

			        	$html.= "</div>";
			        	echo $html;
			        	die();
			        }

			    }
            

				$valoresPagados 		= $this->FlowStage->find("list",["fields"=>["id","valor"], "conditions" => ["state_flow" => "Pagado", "payment_verification" => 1, "prospective_users_id" => $flujo_id ] ]);

				$totalPagado   = array_sum($valoresPagados);
				$totalParaIva  = 0;

				if ($datosFlowstage["ProspectiveUser"]["state_flow"] < 5 && ($count_pago > 0 || $totalPagado > 0 ) ) {
					$count_pago = 0;
					$totalPagado = 0;

					$id_etapa_pagado 		= $this->FlowStage->id_latest_regystri_state_pagado($flujo_id,true);
					$datosPagado			= $this->FlowStage->get_data($id_etapa_pagado);

					if (!empty($datosPagado)) {
						$this->set("datosPagado",$datosPagado);
					}

				}

				$usdData = array(); 
				$copData = array();
				$totalCop = 0;
				$totalUsdOriginal = 0;
				$totalUsdCop  	  = 0;
				$discount 		  = 0;
				$valor_envio      = 0;

				if(!empty($produtosCotizacion)){
					
					$quotationData = end($produtosCotizacion);
					foreach ($produtosCotizacion as $key => $value) {
						if($value["QuotationsProduct"]["currency"] == "usd"){
							$usdData[] = $value["QuotationsProduct"]["id"];
						}else{

							if($quotationData["Quotation"]["header_id"] == 3 || $quotationData["Quotation"]["show_ship"] == 1  || (!in_array($value["Product"]["part_number"],['S-003']) && $quotationData["Quotation"]["show_ship"] == 0) ){


								$totalCop += ($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"]);
								$copData[] = $value["QuotationsProduct"]["id"];	
								if ($value["QuotationsProduct"]["iva"] == 1) {
									$totalParaIva += ($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"]);							
								}
								if ($value["QuotationsProduct"]["change"] == 1) {
									$totalUsdOriginal += ($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"]);
								}else{
									$totalUsdCop      += ($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"]);
								}		

							}				
						}

						if(in_array($value["Product"]["part_number"],['S-003']) && $quotationData["Quotation"]["show_ship"] == 0){
							$valor_envio = $value["QuotationsProduct"]["price"];
						}

						if($value["Quotation"]["descuento"] > 0){
							$discount = $value["Quotation"]["descuento"];
						}
					}

					if($valorQuotation < $totalCop && $valor_envio > 0){
						$valorQuotation = $totalCop;
					}

					$this->loadModel("Autorization");

					$autorization_info = $this->Autorization->findByProspectiveUserId($flujo_id);


					if($discount > 0){
						$percentDiscount = 100 - $discount;
						$percentDiscount = $percentDiscount <= 0 ? 1 : ($percentDiscount / 100);
						$totalParaIva *= $percentDiscount;
					}

					$this->set("ivaData", ($totalParaIva*0.19) );
					$this->set("totalParaIva",$totalParaIva);
					$this->set("autorization_info",$autorization_info);
					if((count($copData) > 0 && count($usdData) > 0) || ($quotationData["Quotation"]["header_id"] != "3" && count($usdData) > 0 ) ) {
						$this->loadModel("Config");
						$this->loadModel("Trm");
				        $config         = $this->Config->findById(1);
				        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
						$this->set("cotizacion", isset($datos["ProspectiveUser"]["quotation_id"]) && !empty($datos["ProspectiveUser"]["quotation_id"]) ? $datos["ProspectiveUser"]["quotation_id"] : $datosFlowstage['FlowStage']['document']);
						$this->set("cotizacion_usd",true);
						$this->set("ajuste",$config["Config"]["ajusteTrm"]);
						$this->set("trmActual",$trmActual);
						$this->set("flujo_id",$flujo_id);
						

					}
				}

				$this->set(compact('datos','medios','count_pago','valorQuotation','idFlowstage','totalPagado','totalCop','totalUsdOriginal','totalUsdCop'));
			} else {
				$this->render('/FlowStages/state_invalid');
			}
		}
	}
	public function saveStatePagado(){
		$this->autoRender 		= false;
		if ($this->request->is('ajax')) {
			$flujo = $this->request->data['FlowStage']['flujo_id'];
			$stateFlow = $this->FlowStage->ProspectiveUser->field("status_bill",["id"=>$flujo]);

			if (isset($this->request->data['inlineRadioOptions'])) {
				$datos['FlowStage']['type_pay'] 			= $this->request->data['inlineRadioOptions'];
			} else {
				$datos['FlowStage']['type_pay'] 			= 2;
			}

			if ($datos['FlowStage']['type_pay'] == 6) {
				$datos['FlowStage']['type_pay'] = 2;
			}

			if ($datos['FlowStage']['type_pay'] != 3 && $datos['FlowStage']['type_pay'] != 5 ) {
				if ( $this->FlowStage->find("count",["conditions"=>["FlowStage.identificator" => $this->request->data['FlowStage']['identificator'] ]]) >= 1 ) {
					return '10000EC';
				}
				$datos['FlowStage']['identificator']			= $this->request->data['FlowStage']['identificator'];
			}

			if ($stateFlow == 1) {
				$factura = (array) $this->getDocumentAuto(["prospectos"=>$flujo]);
				if (!empty($factura) ) {
					foreach ($factura as $key => $value) {
						if($key == $flujo){
							$this->save_data_document($flujo,$value);
						}
					}					
				}						
			}

			// $this->request->data['FlowStage']['valor'] 		= $this->replaceText($this->request->data['FlowStage']['valor'],".", "");
			$datos['FlowStage']['valor'] 					= $this->replaceText($this->request->data['FlowStage']['valor'],",", "");
			$datos['FlowStage']['payment'] 					= $this->request->data['FlowStage']['payment'];
			$datos['FlowStage']['state_flow']				= Configure::read('variables.nombre_flujo.flujo_pagado');
			$datos['FlowStage']['prospective_users_id']		= $this->request->data['FlowStage']['flujo_id'];
			
			$datos['FlowStage']['state']					= 2;
			
			if (isset($this->request->data['select_dias'])) {
				$datos['FlowStage']['payment_day'] 			= $this->request->data['select_dias'];
			} else {
				$datos['FlowStage']['payment_day'] 			= 0;
			}
			$estados_credito 	= array('3','5');
			if (in_array($datos['FlowStage']['type_pay'],$estados_credito)) {
				$documento 									= 1;
				$datos['FlowStage']['document']				= 'Crédito';
			} else {
				$documento 									= $this->loadPhoto($this->request->data['FlowStage']['img'],'flujo/pagado');
				$datos['FlowStage']['document']				= $this->Session->read('imagenModelo');
			}
			if ($documento == 1) {
				// $datos['FlowStage']['id']             		= $this->FlowStage->new_row_model() + 1;
				$this->FlowStage->create();
				$this->FlowStage->save($datos);
				$id_inset 		= $this->FlowStage->id;
				$this->updateStateProspectiveFlow($datos['FlowStage']['prospective_users_id'],Configure::read('variables.control_flujo.flujo_pagado'));
				$this->saveDataLogsUser(1,'FlowStage',$id_inset,Configure::read('variables.nombre_flujo.flujo_negociado').' - '.Configure::read('variables.nombre_flujo.flujo_pagado'));
				$this->messageUserRoleCotizacion($datos['FlowStage']['prospective_users_id'],'total');
				$this->saveAtentionTimeFlujoEtapas($datos['FlowStage']['prospective_users_id'],'pagado_date','pagado_time','pagado');

				$this->FlowStage->ProspectiveUser->save(["id"=>$this->request->data["FlowStage"]["flujo_id"],"discount_datafono"=> floatval($this->request->data["FlowStage"]["discount_datafono"]) ]);
				return $this->request->data['FlowStage']['flujo_id'];
			} else {
				return $documento;
			}
		}
	}

	public function paymentVerificationSalesDayUser(){
		$this->autoRender 				= false;
		return 0;
		$this->loadModel("Salesinvoice");
		$salesNormal 	= $this->FlowStage->ProspectiveUser->getSales( date('Y-m-d'), date('Y-m-d'), AuthComponent::user("id") );
		$salesAditional = $this->Salesinvoice->getSales( date('Y-m-d'), date('Y-m-d'), AuthComponent::user("id")  );
		$salesFinal     = number_format( ($salesNormal+$salesAditional),2,",","." );
		$parts 			= explode(",", $salesFinal);
		return $parts[0].'<span class="decimales simpledecimal">,'.$parts[1]."</span>" ;
	}

	public function paymentVerificationSalesMonthUser(){
		$this->autoRender 				= false;
		return 0;
		$this->loadModel("Salesinvoice");
		$salesNormal 	= $this->FlowStage->ProspectiveUser->getSales( date('Y-m-1'), date("Y-m-t"), AuthComponent::user("id") );
		$salesAditional = $this->Salesinvoice->getSales( date('Y-m-1'), date("Y-m-t"), AuthComponent::user("id")  );
		$salesFinal     = number_format( ($salesNormal+$salesAditional),2,",","." );
		$parts 			= explode(",", $salesFinal);
		return $parts[0].'<span class="decimales simpledecimal">,'.$parts[1]."</span>" ;
	}

	public function paymentVerificationSalesMonth(){
		$this->autoRender 				= false;
		return 0;
		$this->loadModel("Salesinvoice");
		$salesNormal 	= $this->FlowStage->ProspectiveUser->getSales( date('Y-m-1'), date("Y-m-t"));
		$salesAditional = $this->Salesinvoice->getSales( date('Y-m-1'), date("Y-m-t"));
		$salesFinal     = number_format( ($salesNormal+$salesAditional),2,",","." );
		$parts 			= explode(",", $salesFinal);
		return $parts[0].'<span class="decimales simpledecimal">,'.$parts[1]."</span>" ;
	}

	function object_to_arrayData($data)
	{
	    if (is_array($data) || is_object($data))
	    {
	        $result = array();
	        foreach ($data as $key => $value)
	        {
	            $result[$key] = $this->object_to_arrayData($value);
	        }
	        return $result;
	    }
	    return $data;
	}

	public function changeStatePagadoTrue(){
		$this->autoRender 								= false;
		$flowStage_id 									= $this->request->data['flowStages_id'];
		$user_id 	 									= $this->request->data['user_id'];
		$flujo_id 	 									= $this->request->data['flujo_id'];
		$discount 	 									= $this->request->data['discount'];
		$datos['FlowStage']['id']						= $flowStage_id;
		$datos['FlowStage']['state']					= 7;
		$datos['FlowStage']['payment_verification']		= 1;
		$datos['FlowStage']['date_verification']		= date("Y-m-d");

		$response = $this->FlowStage->field("response",["id" => $flowStage_id]); 

		if(!empty($response) && !is_null($response)){
			$response = $this->object_to_arrayData(json_decode($response));
			$datos["FlowStage"]["response"] = null;
			$datosFlow["ProspectiveUser"]["id"] = $flujo_id;
			$datosFlow["ProspectiveUser"]["flow"] = null;
			$this->FlowStage->ProspectiveUser->save($datosFlow);
		}

		if ($this->FlowStage->save($datos)) {
			$flow = $this->FlowStage->ProspectiveUser->findById($flujo_id);
			if($flow["ProspectiveUser"]["state"] == 6){
				$this->updateStateProspective($flujo_id,1);
			}

			$flow["ProspectiveUser"]["discount_datafono"] = $discount;
			$this->FlowStage->ProspectiveUser->save($flow["ProspectiveUser"]);
			
			switch ($this->FlowStage->find_type_pay_flujo($flujo_id)){
				case 2:
					$datosF 										= $this->FlowStage->get_data($flowStage_id);
					$datosP['Payment']['valor'] 					= $datosF['FlowStage']['valor'];
					$datosP['Payment']['payment'] 					= $datosF['FlowStage']['payment'];
					$datosP['Payment']['document'] 					= $datosF['FlowStage']['document'];
					$datosP['Payment']['prospective_users_id'] 		= $flujo_id;
					// $datosP['Payment']['id']             			= $this->FlowStage->ProspectiveUser->Payment->new_row_model() + 1;
					$this->FlowStage->ProspectiveUser->Payment->create();
					$this->FlowStage->ProspectiveUser->Payment->save($datosP);
					$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.pago_abono'),Configure::read('variables.Gestiones_horas_habiles.pago_verificado'),$user_id,$flujo_id,Configure::read('variables.nombre_flujo.pago_abono'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
					break;
				case 3:
					$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.pago_verificado_credito'),Configure::read('variables.Gestiones_horas_habiles.pago_verificado'),$user_id,$flujo_id,Configure::read('variables.nombre_flujo.pago_credito_true'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
					break;
				case 5:
					$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.pago_verificado_credito'),Configure::read('variables.Gestiones_horas_habiles.pago_verificado'),$user_id,$flujo_id,Configure::read('variables.nombre_flujo.pago_credito_true'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
					break;

				default:
					$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.pago_verificado_asesor'),Configure::read('variables.Gestiones_horas_habiles.pago_verificado'),$user_id,$flujo_id,Configure::read('variables.nombre_flujo.pago_verificado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
					break;
			}
			$this->saveDataLogsUser(9,'ProspectiveUser',$flowStage_id);
			$this->saveAtentionTimeFlujoEtapas($flujo_id,'verify_payment_date','verify_payment_time','verify_payment_true');
		};
		if (!empty($response)) {
			$this->request->data = $response;
			$this->saveManageOrden();
		}
		return true;
	}

	public function changeStatePagadoFalse(){
		$this->autoRender 										= false;
		$flowStage_id 											= $this->request->data['flowStages_id'];
		$user_id 	 											= $this->request->data['user_id'];
		$flujo_id 	 											= $this->request->data['flujo_id'];
		$datos['FlowStage']['id']								= $flowStage_id;
		$datos['FlowStage']['state']							= 4;
		$datos["FlowStage"]["response"]							= null;
		$datos['FlowStage']['payment_verification']				= 2;
		$datos['FlowStage']['date_verification']				= date("Y-m-d");
		$datos['FlowStage']['payment_false_description']		= $this->request->data['rason'];
		$this->FlowStage->save($datos);
		
		$flow = $this->FlowStage->ProspectiveUser->findById($flujo_id);

		$flow["ProspectiveUser"]["flow"] = null;

		if(in_array($this->FlowStage->find_type_payment_flujo($flujo_id), [3,5])){
			$flow["ProspectiveUser"]["rejected"] = 1;
			$flow["ProspectiveUser"]["rejected_reason"] = $this->request->data['rason'];
		}

		$this->FlowStage->ProspectiveUser->save($flow["ProspectiveUser"]);

		if($flow["ProspectiveUser"]["state"] == 6){
			$this->updateStateProspective($flujo_id,5);
		}else{
			if ($this->FlowStage->find_type_payment_flujo($flujo_id) < 2) {
				$this->updateStateProspectiveFlow($flujo_id,Configure::read('variables.control_flujo.flujo_negociado'));
			}
			$this->saveDataLogsUser(10,'ProspectiveUser',$flowStage_id);
			$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.pago_no_verificado'),Configure::read('variables.Gestiones_horas_habiles.pago_verificado'),$user_id,$flujo_id,Configure::read('variables.nombre_flujo.pago_no_verificado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
			$this->saveAtentionTimeFlujoEtapas($flujo_id,'verify_payment_date','verify_payment_time','verify_payment_false');
		}		
		return true;
	}

	public function form_pagado_despachado(){
		$this->layout 			= false;
		if ($this->request->is('ajax')) {
			$flujo_id 			= $this->request->data['flujo_id'];
			$datos				= $this->FlowStage->ProspectiveUser->get_data($flujo_id);
			$flete 				= Configure::read('variables.fleteEnvio');
			$this->set(compact('datos','flete'));
		}
	}

	public function saveInformationDelivery(){
		$this->autoRender 		= false;
		if ($this->request->is('ajax')) {
			$datos['FlowStage']['prospective_users_id']		= $this->request->data['flujo_id'];
			$datos['FlowStage']['city']						= $this->request->data['cityForm'];
			$datos['FlowStage']['address']					= $this->request->data['address'];
			$datos['FlowStage']['additional_information']	= $this->request->data['information'];
			$datos['FlowStage']['contact']					= $this->request->data['contact'];
			$datos['FlowStage']['flete']					= $this->request->data['flete'];
			$datos['FlowStage']['telephone']				= $this->request->data['telefono'];
			$datos['FlowStage']['copias_email']				= $this->request->data['copias'];
			$datos['FlowStage']['state_flow']				= Configure::read('variables.nombre_flujo.datos_despacho');
			$datos['FlowStage']['state']					= 2;
			// $datos['FlowStage']['id']             			= $this->FlowStage->new_row_model() + 1;
			$this->FlowStage->create();
			if ($this->FlowStage->save($datos)) {
				$flowStage_id = $this->FlowStage->id_flow_bussines_latses_pagado($datos['FlowStage']['prospective_users_id']);
				$this->updateFlowStageState($flowStage_id,1);
				$this->updateStateProspective($this->request->data['flujo_id'],2);
				$this->messageUserRoleLogistica($datos['FlowStage']['prospective_users_id']);
				$this->saveAtentionTimeFlujoEtapas($datos['FlowStage']['prospective_users_id'],'dispatch_data_date','dispatch_data_time','dispatch');
				return $this->request->data['flujo_id'];
			}
		}
	}

	public function send_kebco_llc($flujo_id){
		$this->autoRender = false;
		$whitelist = array(
            '127.0.0.1',
            '::1'
        );

        $id_etapa_cotizado 		= $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
		$id_etapa_pagado 		= $this->FlowStage->id_latest_regystri_state_pagado($flujo_id);
		$datos 					= $this->FlowStage->get_data($id_etapa_cotizado);
		$datosPagado     		= $this->FlowStage->get_data($id_etapa_pagado);
		$datosFlujo     		= $this->FlowStage->ProspectiveUser->findById($flujo_id);

        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
			
			$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datos['FlowStage']['document']);
			$this->loadModel("Brand");

			$productosFinal = [];

			foreach ($produtosCotizacion as $key => $value) {
				$produtosCotizacion[$key]["Brand"] = $this->Brand->find("first",["conditions" => ["id" => $value["Product"]["brand_id"]],"recursive" => -1 ])["Brand"];
			}

			foreach ($produtosCotizacion as $key => $value) {
				$productosFinal[$value["Brand"]["id_llc"]][] = [ "Product" => $value["Product"],"QuotationsProduct" => $value["QuotationsProduct"] ];
			}

			$idFactura = [];

			foreach ($productosFinal as $brandID => $value) {
				$dataSend = new stdClass();
				$dataSend->socid = $brandID;
				$dataSend->fk_soc = $brandID;
				$dataSend->type = 0;
				$dataSend->cond_reglement = "Due upon receipt";
				$dataSend->cond_reglement_doc = "Due upon receipt";
				$dataSend->fk_incoterms = "0";
				$dataSend->mode_reglement = "Transfer";
				$dataSend->lines = [];
				$quotationsIds = [];

				foreach ($value as $keyVal => $product) {
					$line = new stdClass();
					$line->ref = $product["Product"]["part_number"];
					$line->product_ref = $product["Product"]["part_number"];
					$line->qty = $product["QuotationsProduct"]["quantity"];
					$line->fk_product = $product["Product"]["id_llc"];
					$dataSend->lines[] = $line;
					$quotationsIds[] = $product["QuotationsProduct"]["id"];
				}


				try {
				
					$HttpSocket = new HttpSocket(['ssl_allow_self_signed' => false, 'ssl_verify_peer' => false, 'ssl_verify_host' =>false ]);

					$response 	= $HttpSocket->post($this->API.'supplierinvoices/', json_encode($dataSend) ,["header" => ["DOLAPIKEY" => "Kebco2020**--","Accept"=>"application/json","Content-Type"=>"application/json"] ]);

					$code 		= $response->code;

					if ($code == 200) {
						$idFactura[] = intval(trim($response->body()));
						foreach ($quotationsIds as $key => $value) {
							$this->FlowStage->Quotation->QuotationsProduct->updateAll(
								[
									"QuotationsProduct.id_llc" => intval(trim($response->body())),
									"QuotationsProduct.state_llc" => 0,
								],
								["QuotationsProduct.id" => $value]
							);
						}			
					}

				} catch (Exception $e) {
					var_dump($e->getMessage());
				}
				
			}
        }

		$datosPagado["FlowStage"]["state"] = 3;
		$this->FlowStage->save($datosPagado["FlowStage"]);

		$this->loadModel("User");
		$users  = $this->User->role_gerencia_user();
		$emails = array("jotsuar@gmail.com",$datosFlujo["User"]["email"],"logistica@yopmail.com");
		foreach ($users as $key => $value) {
			$emails[] = $value["User"]["email"];
		}

		$subject = "Se ha creada una factura en Kebco LLC - Flujo ".$datosFlujo["ProspectiveUser"]["id"]." ".date("Y-m-d H:i:s")." KEBCO AlmacenDelPintor.com";
		$options = array(
			'to'		=> $emails,
			'template'	=> 'factura_llc',
			'subject'	=> $subject,
			'vars'		=> compact("idFactura")
		);
		$this->sendMail($options);

		$this->Session->setFlash('Información enviada a Kebco LLC correctamente.','Flash/success');

	}

	public function administrator_orden(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {

			$tiempos = Configure::read("entregaProductValidation");
			$flujo_id 				= $this->request->data['flujo_id'];

			$stateFlow = $this->FlowStage->ProspectiveUser->field("status_bill",["id"=>$flujo_id]);

			$existeFact = $this->FlowStage->ProspectiveUser->find("count",["conditions"=>["ProspectiveUser.id" => $flujo_id, "ProspectiveUser.bill_code" => null ]]);

			if ($stateFlow == 1) {
				$factura = (array) $this->getDocumentAuto(["prospectos"=>$flujo_id]);
				if (!empty($factura) ) {
					foreach ($factura as $key => $value) {
						if($key == $flujo_id){
							$this->save_data_document($flujo_id,$value);
						}
					}					
				}						
			}

			$id_etapa_cotizado 		= $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
			$id_etapa_pagado 		= $this->FlowStage->id_latest_regystri_state_pagado($flujo_id);
			$datos 					= $this->FlowStage->get_data($id_etapa_cotizado);

			if ($datos["ProspectiveUser"]["country"] != "Colombia") {
				$this->loadModel("Product");
				$this->Product->setLlc();
				// $this->set("internacional",true);
			}

			if (is_numeric($datos['FlowStage']['document'])) {
				$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datos['FlowStage']['document']);
				$validateTypeCotizacion = '';
				$produtosCotizacion = Set::sort($produtosCotizacion, '{n}.QuotationsProduct.quantity', 'asc');

			} else {
				$produtosCotizacion 	= array();
				$validateTypeCotizacion = 'Cotización enviada a traves de un archivo PDF';
			}

			if ($datos["ProspectiveUser"]["country"] != "Colombia") {

				$idsProducts 	= [];
				$idsBrands 		= [];
				$parts 			= [];

				foreach ($produtosCotizacion as $key => $value) {
					if (!is_null($value["Product"]["id_llc"]) ) {
						$idsProducts[] = $value["Product"]["id_llc"];
						
					}else{
						$parts[] = $value["Product"]["part_number"];
					}
					if (!in_array($value["Product"]["brand_id"], $idsBrands)) {
						$idsBrands[] = $value["Product"]["brand_id"];
					}
				}	

				if (count($idsProducts) < count($produtosCotizacion)) {
					$this->set("error_products",$parts);
				}

				$this->loadModel("Brand");
				$brands = $this->Brand->find("all",["conditions" => ["Brand.id" => $idsBrands], "fields" => ["Brand.name", "Brand.id_llc"] ]);

				$brandsNoId = [];

				foreach ($brands as $key => $value) {
					if (is_null($value["Brand"]["id_llc"])) {
						$brandsNoId[] = $value["Brand"]["name"];
					}
				}
				if (!empty($brandsNoId)) {
					$this->set("error_brands",$brandsNoId);
				}
			}
			$inventioWo = $this->getValuesProductsWo($produtosCotizacion);
			$this->set(compact('produtosCotizacion','validateTypeCotizacion','flujo_id','id_etapa_pagado','datos','inventioWo','existeFact'));
		}
	}

	public function change_despachado(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			$flujo_id  				= $this->request->data["flujo_id"];
			$this->validateFlowWo($flujo_id);
			$state 							= $this->request->data['state'];
			$stateflow 						= $this->request->data['stateflow'];
			if ($state == 2) {
				$id_etapa_cotizado 			= $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
				$datosFlowstage 			= $this->FlowStage->get_data($id_etapa_cotizado);
				if (is_numeric($datosFlowstage['FlowStage']['document'])) {
					$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datosFlowstage['FlowStage']['document']);
					$validateTypeCotizacion = '';
				} else {
					$produtosCotizacion 	= array();
					$validateTypeCotizacion = 'Cotización enviada a traves de un archivo PDF';
				}
				$transportadoras 			= Configure::read('variables.transportadoras');
				$datos						= $this->FlowStage->ProspectiveUser->get_data($flujo_id);

				$this->loadModel("Conveyor");
		        $conveyors = $this->Conveyor->find("list",["fields" => "name", "name"]);
		        $transportadoras = array_combine($conveyors, $conveyors);

				$this->set(compact('datos','transportadoras','produtosCotizacion','validateTypeCotizacion'));
			} else {
				$this->render('/FlowStages/state_invalid');
			}
		}
	}

	public function get_table_to_order(){
		$this->layout = false;

		$this->loadModel("Product");

		$products = array();
		foreach ($this->request->data as $key => $value) {
			if(!array_key_exists($value["id"], $products)){
				$products[$value["id"]] = $value;
			}else{
				$products[$value["id"]]["quantity"]+= $value["quantity"];
			}
		}

		$productInfo = !empty($products) ? end($products) : null;

		foreach ($products as $key => $value) {
			$products[$key]["Product"] = $this->Product->findById($value["id"])["Product"];
		}

		$this->set(compact("products","productInfo"));
	}

	public function saveManageOrden(){
		if ($this->request->is('ajax')) {
			$this->loadModel('Import');
			$this->autoRender 					= false;
			$flujo_id 							= $this->request->data['FlowStage']['flujo_id'];
			$flowstage_id 						= $this->request->data['FlowStage']['flowstage_id'];
			$codigoCotizacionFlowstageState 	= $this->FlowStage->codigoQuotation_latest_regystri_state_cotizado($flujo_id);
			$quotation_id 						= $this->FlowStage->Quotation->id_data_codigo_cotizacion($codigoCotizacionFlowstageState);
			$productos 							= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($quotation_id);
			$productsSinGestion 				= $this->FlowStage->Quotation->QuotationsProduct->findAllByQuotationIdAndState($quotation_id,0);



			if (!empty($productsSinGestion)) {

				$flujoState = $this->FlowStage->field("state",["id" => $flowstage_id]);
				$flowData 	= $this->FlowStage->ProspectiveUser->field("flow",["id" => $flujo_id]);

				if($flujoState == 2 && empty($flowData) ){

					$this->FlowStage->recursive = -1;
					$flowDataInfo["FlowStage"]["id"] = $flowstage_id;
					$flowDataInfo["FlowStage"]["response"] = json_encode($this->request->data);
					$flowDataInfo["FlowStage"]["response_history"] = json_encode($this->request->data);
					$this->FlowStage->save($flowDataInfo);

					$flujoDataProspective["ProspectiveUser"]["id"] = $flujo_id;
					$flujoDataProspective["ProspectiveUser"]["flow"] = $flowstage_id;
					$this->FlowStage->ProspectiveUser->save($flujoDataProspective);


					$pago_pendiente = $this->FlowStage->flujos_verify_payment_credito_flujo($flujo_id);

					if(!empty($pago_pendiente)){
						$customer_data 						= $this->getDataCustomer($flujo_id);

						$dni 								= $customer_data["identification"];

						$partsSpace = explode(" ", trim($dni));

						if (count($partsSpace) > 1) {
							$dni = $partsSpace[0];
						}

						$partsPlus = explode("+", $dni);

						if (count($partsPlus) > 1) {
							$dni = $partsPlus[0];
						}
						$dni =  trim($dni);


						$data = $this->postWoApi(["dni"=>$dni],"customers");

						if(!empty($data)){
							$permitido = true;
							$cupo_actual = floatval($data->CupoCredito-$data->Saldo);

							foreach ($data->details as $key => $value){
								if($value->DIAS > 30) { 
									$permitido = false; 
									break;
								}
							}


							if($permitido && $cupo_actual > floatval($pago_pendiente["FlowStage"]["valor"]) ){
								// $this->request->data["flowStages_id"] = $pago_pendiente["FlowStage"]["id"];
								// $this->request->data["flujo_id"] = $flujo_id;
								// $this->request->data["user_id"] = $pago_pendiente["ProspectiveUser"]["user_id"];
								// $this->changeStatePagadoTrue();
							}


						}
					}


					$this->Session->setFlash(__('Se realizó la gestión correctamente, se realizará de forma automática con esta configuración cuando el pago se aprobado'),'Flash/success');
					return true;
				}

				unset($this->request->data['FlowStage']['flujo_id']);
				unset($this->request->data['FlowStage']['flowstage_id']);
				$import  						= false;
				$code_import 					= $this->generateCodeImport();
				$productsImport 				= array();
				$quantityProductsImport			= array();
				$deliveryData					= array();

				$valueI 	= 1;
				$valueEnd 	= count($this->request->data["FlowStage"]);



				for ($i=intval($valueI); $i <= intval($valueEnd); $i++) {
					if (isset($this->request->data['FlowStage']['importacion_'.$i])) {
						if ($this->request->data['FlowStage']['importacion_'.$i] != '0') {
							$arrayImportaciones = array();

							for ($l=0; $l < count($productos); $l++) {
								if (isset($productos[$l])) {
									if ($productos[$l]['QuotationsProduct']['id'] === $this->request->data['FlowStage']['importacion_'.$i]) {

										$dataProduct = $this->getQuantityBlock($productos[$l]["Product"],null,true);

										$inventoryProduct = $dataProduct["Product"]["transito"];

										$quantityProductsImport[$i]["Cantidad-".$productos[$l]['QuotationsProduct']['product_id']] = $this->request->data["importacionFinal"][$this->request->data['FlowStage']['importacion_'.$i]];
										$productsImport[$i][$productos[$l]['QuotationsProduct']['product_id']] = $productos[$l]['QuotationsProduct']['product_id'];

										if(isset($this->request->data["timeDelivery"]) && isset($this->request->data["timeDelivery"][$this->request->data['FlowStage']['importacion_'.$i]])){
											$deliveryData[$i][$productos[$l]['QuotationsProduct']['product_id']] = $this->request->data["timeDelivery"][$this->request->data['FlowStage']['importacion_'.$i]];
										}else{
											$deliveryData[$i][$productos[$l]['QuotationsProduct']['product_id']] = $this->FlowStage->Quotation->QuotationsProduct->field('delivery',array("QuotationsProduct.id" => $this->request->data['FlowStage']['importacion_'.$i]));
										}

										// $l = $l + count($productos);


										if($inventoryProduct < $productos[$l]["QuotationsProduct"]["quantity"] && $inventoryProduct != 0){

											$arrayImportaciones["QuotationsProduct"]["quantity"] = $this->request->data["importacionFinal"][$this->request->data['FlowStage']['importacion_'.$i]] - $inventoryProduct;

											$newProduct 			= $productos[$l]["QuotationsProduct"];	
											$newProduct["quantity"] = $inventoryProduct;
											$arrayImportaciones['QuotationsProduct']['delivery'] = '2-3 días hábiles';
											$this->lockProduct($dataProduct["Product"],$flujo_id,$inventoryProduct);
											$this->newProductInfo($newProduct);
										}else{
											if($inventoryProduct != 0){
												$this->lockProduct($dataProduct["Product"],$flujo_id,$productos[$l]['QuotationsProduct']["quantity"]);
											}
										}
									}
								}
							}

							if(isset($this->request->data["timeDelivery"]) && isset($this->request->data["timeDelivery"][$this->request->data['FlowStage']['importacion_'.$i]])){
								$arrayImportaciones['QuotationsProduct']['delivery'] = $this->request->data["timeDelivery"][$this->request->data['FlowStage']['importacion_'.$i]];
							}

							$arrayImportaciones['QuotationsProduct']['state'] 						= 2;
							$arrayImportaciones['QuotationsProduct']['id'] 							= $this->request->data['FlowStage']['importacion_'.$i];
							$arrayImportaciones['QuotationsProduct']['warehouse'] 					= 3;

							$this->FlowStage->Quotation->QuotationsProduct->save($arrayImportaciones['QuotationsProduct']);
						}
						
					}
				}

				if(!empty($productsImport)){
					$this->loadModel("Product");
					foreach ($productsImport as $key => $value) {

						$datosImporter = $this->Product->getProductAndBrandsWithQuantityForImporter($productsImport[$key],$quantityProductsImport[$key],$deliveryData[$key]);

						if(!empty($datosImporter)){
							$import = true;
						}

						foreach ($datosImporter as $key => $products) {
							$this->sendRequestInporter($products,"",Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY"),$flujo_id);
						}

					}
				}
				
				

				if ($this->FlowStage->find_type_pay_flowstage($flowstage_id) != 2 && $this->FlowStage->find_type_pay_flowstage($flowstage_id) != 3) {
					$this->updateFlowStageState($flowstage_id,3);
				}
				$id_etapa_cotizado 		= $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
				$datos 					= $this->FlowStage->get_data($id_etapa_cotizado);
				if (is_numeric($datos['FlowStage']['document'])) {
					$productosSinImportacion = $this->FlowStage->Quotation->QuotationsProduct->update_entrega_orden($datos['FlowStage']['document']);
					if(!empty($productosSinImportacion)){
						foreach ($productosSinImportacion as $keySin => $valueSin) {
							$dataProduct = $this->getQuantityBlock($valueSin["Product"]);
							$this->lockProduct($dataProduct["Product"],$flujo_id,$valueSin["QuotationsProduct"]["quantity"],true);
						}
					}
				}
				$this->FlowStage->Quotation->update_state_gestionado($flujo_id);
				$codigo_cotizacion 								= $this->FlowStage->Quotation->codigo_for_quotation($quotation_id);
				$ids_products 									= $this->FlowStage->Quotation->QuotationsProduct->products_import_quotation($quotation_id);
				$products_data 									= $this->FlowStage->Quotation->QuotationsProduct->Product->get_data_products_imports($ids_products);
				$this->message_users_logistica($flujo_id,$code_import,$codigo_cotizacion,$products_data,$import);
				$country  = $this->ProspectiveUser->field("country",["id"=>$flujo_id]);	

				if ($country != "Colombia" ) {
					$this->send_kebco_llc($flujo_id);
				}

			} else {
				$this->updateFlowStageState($flowstage_id,3);
			}
			$this->updateStateProspective($flujo_id,1);
			return true;
		}
	}

	public function getQuantityBlock($productData, $flujo_actual = null, $back = null ){

		$this->loadModel("ProductsLock");
		$this->ProductsLock->recursive = -1;

		if(is_null($flujo_actual)){
			$productsLock = $this->ProductsLock->findAllByProductIdAndState($productData["id"],1);
		}else{
			$productsLock = $this->ProductsLock->find("all",["conditions" =>["product_id" => $productData["id"], "state" => 1, "prospective_user_id != " => $flujo_actual ]  ]);
		}

		$lockMed 			= 0;
		$lockBog 			= 0;
		$lockStm 			= 0;
		$lockStb 			= 0;
		$lockBack 			= 0;
		$totalBloqueo 		= 0;
		$totalDisponible 	= 0;

		foreach ($productsLock as $key => $value) {
			//Si se aprueba bloqueo se debe quitar
			if (is_null($value)) {
				$lockMed += $value["ProductsLock"]["quantity_stm"];
			}
			$lockBack	+= $value["ProductsLock"]["quantity_back"];
		}

		$totalBloqueo = $lockMed+$lockBack;

		$productData["quantity"] 		= ( ( $productData["quantity"] - $lockMed ) < 0) || !is_null($back) ? 0 : $productData["quantity"] - $lockMed;

		$productData["quantity_back"] 	= (( $productData["quantity_back"] - $lockBack ) < 0) ? 0 : $productData["quantity_back"] - $lockBack;

		$totalDisponible = $productData["quantity"]+$productData["quantity_back"];

		$Product = $productData;

		return compact("Product","productsLock","totalBloqueo","totalDisponible");

	}

	private function lockProduct($productData, $flujo_id, $quantity, $normal = false){
		$flujoStateBill = $this->FlowStage->ProspectiveUser->field("status_bill",["id" => $flujo_id, "bill_code !=" => NULL]);
		$this->loadModel("ProductsLock");
		$this->loadModel("Transit");

		// if ($normal) {
			$partsData              			= $this->getValuesProductsWo([ ["Product"=>$productData] ],true);
			$productData["quantity"]			= isset($partsData[$productData["part_number"]]) ? $partsData[$productData["part_number"]] : 0;
		// }	


		var_dump($productData);

		$quantityBog 	=  0;
		$total 			=  0;
		$quantityBack 	=  0;
		$quantityMed 	=  $productData["quantity"] <= $quantity ? $productData["quantity"] : $quantity;
		$total 			+= $quantityMed;

		if($total != $quantity && !$normal){
			$diferencia 	=  $quantity - $total;
			$quantityBack 	=  $productData["transito"] <= $diferencia ? $productData["transito"] : $diferencia;
			$total  		+= $quantityBack;
		}else{
			$diferencia 	=  $quantity - $total;
			$quantityBack 	=  $productData["transito"] <= $diferencia ? $productData["transito"] : $diferencia;
		}

		$lockActual = $this->ProductsLock->findByProductIdAndProspectiveUserId($productData["id"],$flujo_id);

		if(!empty($lockActual)){
			$lockActual["ProductsLock"]["state"] = 2;
			$lockActual["ProductsLock"]["unlock_date"] = date("Y-m-d H:i:s");
			$this->ProductsLock->save($lockActual["ProductsLock"]);
		}

		if ($quantityBack > 0) {
			$this->loadModel("Transit");
			$dataTransit = [ 
				"Transit" => [ 
					"product_id" 		  => $productData["id"],
					"prospective_user_id" => $flujo_id,
					"user_id" 			  => $this->FlowStage->ProspectiveUser->field("user_id",["id" => $flujo_id]),
					"quantity" 			  => $quantityBack,
				] 
			];
			$this->Transit->create();
			$this->Transit->save($dataTransit);
		}

		if(($quantityMed + $quantityBack ) != 0 || $flujoStateBill == 1){
			$datos = array(
				"ProductsLock" => array(
					"product_id" 		  => $productData["id"],
					"prospective_user_id" => $flujo_id,
					"quantity" 			  => $quantityMed,
					"quantity_stm"		  => $quantityMed,
					"quantity_back"		  => $quantityBack,
					"lock_date"			  => date("Y-m-d H:i:s"),
					"due_date" 			  => date("Y-m-d",strtotime("+30 day")),
					"state"				  => $flujoStateBill == 1 ? 2 : 1
				)
			);
			$this->ProductsLock->create();
			$this->ProductsLock->save($datos);
		}
	}

	private function newProductInfo($newProduct){
		unset($newProduct["id"]);
		$this->FlowStage->Quotation->QuotationsProduct->create();
		$this->FlowStage->Quotation->QuotationsProduct->save($newProduct);
	}

	public function saveAddImport($code_import,$importDescription = '', $textoProveedor = '',$notaImport = '', $iva = 1){
		$arrayImportaciones['Import']['user_id'] 					= AuthComponent::user('id');
		$arrayImportaciones['Import']['code_import'] 				= $code_import;
		$arrayImportaciones['Import']['description'] 				= $importDescription;
		$arrayImportaciones['Import']['text_brand'] 				= $textoProveedor;
		$arrayImportaciones['Import']['nota'] 						= $notaImport;
		$arrayImportaciones['Import']['fecha_envio'] 				= date("Y-m-d H:i:s");
		if (isset($this->request->data["brand_id"])) {
			$arrayImportaciones["Import"]["brand_id"] 				= $this->request->data["brand_id"];
		}
		$arrayImportaciones['Import']['iva'] 						= $iva;
		$arrayImportaciones['Import']['internacional']				= isset($this->request->data["internacional"]) && $this->request->data["internacional"] == 1 ? 1 : 0;
		$arrayImportaciones['Import']['state'] 						= $importDescription == '' ? Configure::read('variables.importaciones.proceso') : Configure::read('variables.importaciones.solicitud');
		// $arrayImportaciones['Import']['id']          				= $this->Import->new_row_model() + 1;
		if(!empty($this->request->data["payment"])){
			$arrayImportaciones["Import"]["payment"] = $this->request->data["payment"];
		}
		if(!empty($this->request->data["comment"])){
			$arrayImportaciones["Import"]["commetns"] = $this->request->data["comment"];
		}

		if(!empty($this->request->data["cotnumb"])){
			$arrayImportaciones["Import"]["quotation_num"] = $this->request->data["cotnumb"];
		}

		if(!empty($this->request->data["brand_data"])){
			$arrayImportaciones["Import"]["brand_id"] = $this->request->data["brand_data"];
		}	

		if(!empty($this->request->data["address"])){
			$arrayImportaciones["Import"]["address"] = $this->request->data["address"];
		}

		$this->Import->create();
		$this->Import->save($arrayImportaciones['Import']);
		return $this->Import->id;
	}

	public function addImport(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			if ($this->request->data['Import']['description'] == "") {
				$this->Session->setFlash('Debes agregar la razón para revisar tu solicitud de importación','Flash/error');
				return 0;
			} else {

				$this->loadModel("Product");
				$datosImporter = $this->Product->getProductAndBrandsWithQuantityForImporter($this->Session->read('carritoImportaciones'),$this->request->data);

				foreach ($datosImporter as $key => $products) {
					$this->sendRequestInporter($products,$this->request->data["Import"]["description"],Configure::read("TYPE_REQUEST_IMPORT.USER_PETITION"),null,$this->request->data["internacional"]);
				}

				$this->Session->delete('carritoImportaciones');
				$this->Session->setFlash(__('La solicitud fue generada correctamente'),'Flash/success');
				// $this->emailAvisoRevisionGerencia();
				return 1;
			}
		}
	}

	private function getTotales($data){
		$dataReturn = [];
		foreach ($data as $key => $value) {
			if (empty($value)) {
				$dataReturn[$key] = 0;
			}else{
				$total = 0 ;
				foreach ($value as $num => $bodega) {
					$total+=$bodega["total"];
				}
				$dataReturn[$key] = $total;
			}
		}
		return $dataReturn;
	}

	public function cronProducts(){
		$this->autoRender = false;
		$this->loadModel("Product");
		$this->loadModel("ImportRequest");
		$this->loadModel("ImportRequestsDetailsProduct");

		$actives = $this->ImportRequest->find("list",["fields"=>["id","id"], "conditions" => ["ImportRequest.state" => 1, "ImportRequest.type" => 1 ] ]);

		$details = $this->ImportRequest->ImportRequestsDetail->find("list",["fields"=>["id","id"],"conditions" => ["ImportRequestsDetail.import_request_id" => $actives, "ImportRequestsDetail.type_request" => 4] ]);

		$actuals = $this->ImportRequestsDetailsProduct->find("list",["fields"=>["product_id","product_id"], "conditions" => ["ImportRequestsDetailsProduct.import_requests_detail_id" => $details ] ]);

		$conditions =  ["min_stock >=" => 0,"reorder >=" => 0,'max_cost >' => 0, "deleted" => 0, "Product.state" => 1];

		if (!empty($actuals)) {
			$conditions["Product.id !="] = $actuals;
		}

		$nacional = 0;
		$products = $this->Product->find("all",["conditions" => $conditions, "recursive" => -1 ]);

		$partsData					= [];

		if (!empty($products)) {
			$partsData				= $this->getValuesProductsWo($products);	
			$partsData				= $this->getTotales($partsData);		
		}

		if (empty($products)) {
			return false;
		}

		foreach ($products as $productID => $value) {
			$actual =  isset($partsData[$value["Product"]["part_number"]]) ? $partsData[$value["Product"]["part_number"]] : 0;

			$actual += intval($value["Product"]["transito"]);

			if ($actual <= $value["Product"]["min_stock"] || $actual <= $value["Product"]["reorder"] ) {
				$quantity = 0;
				if ($actual == 0) {
					$quantity = $value["Product"]["max_cost"];
				}else{
					if ($actual <= $value["Product"]["reorder"]) {
						$quantity = $value["Product"]["max_cost"] - $actual;
					}
				}

				if ($quantity == 0) {
					continue;
				}

				$brandProduct 		= $value["Product"]["brand_id"];
				$requestInfoId 		= $this->ImportRequest->getOrSaveRequest($brandProduct,$nacional,2);
				$requestDetailId  	= $this->ImportRequest->ImportRequestsDetail->saveDataDetail(
					$requestInfoId,
					112,
					4,
					"Reposición automática por bajo inventario",
					null,
					$nacional
				);
				$this->ImportRequestsDetailsProduct->saveDetailProduct($requestDetailId,[["id_product" => $value["Product"]["id"], "quantity" => $quantity ]]);
			}
		}
		return true;
	}

	private function sendRequestInporter($products, $motive, $type,$flujo_id = null,$internacional = 0){
		$this->loadModel("ProspectiveUser");
		$this->loadModel("Wo");
		if (!isset($this->request->data["bloqueo"]) || (isset($this->request->data["bloqueo"]) && $this->request->data["bloqueo"] == 0 ) ) {			
		
			foreach ($products as $key => $value) {
				$idProduct = $value["id_product"];

		        $this->Wo->recursive = -1;
		        $allVentas = $this->Wo->findAllByProductIdAndState($idProduct,1);
		        foreach ($allVentas as $keyProduct => $valueProduct) {
		            $valueProduct["Wo"]["state"] = 0;
		            $valueProduct["Wo"]["modified"] = date("Y-m-d");
		            $this->Wo->save($valueProduct);
		        }
			}
		}
		
		$nacional = 0;
		$country  = $this->ProspectiveUser->field("country",["id"=>$flujo_id]);	

		if (!is_null($country) && $country != false ) {
			if( (!is_null($country) && $country != "Colombia") ){
				$nacional = 1;
			}
		}else{
			$nacional = $internacional;
		}

		$this->loadModel("ImportRequest");
		$datosProduct 		= end($products);
		$requestInfoId 		= $this->ImportRequest->getOrSaveRequest($datosProduct["brand"],$nacional);
		$requestDetailId  	= $this->ImportRequest->ImportRequestsDetail->saveDataDetail(
			$requestInfoId,
			is_null($flujo_id) ? AuthComponent::user("id") : $this->ProspectiveUser->field("user_id",["id" => $flujo_id]),
			$type,
			$motive,
			$flujo_id,
			$nacional
		);

		$this->loadModel("ImportRequestsDetailsProduct");
		$this->ImportRequestsDetailsProduct->saveDetailProduct($requestDetailId,$products);
	}

	public function addImportSales(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$this->sendRequestInporter($this->request->data["products"],$this->request->data["motive"],Configure::read("TYPE_REQUEST_IMPORT.SALES_TO_INVENTORY"),null,$this->request->data["internacional"]);

			$this->Session->setFlash(__('La solicitud fue generada correctamente'),'Flash/success');

		}
	}


	public function saveImportProduct($arrayIdProducts,$import_id){
		$datosM 														= array();
		foreach ($arrayIdProducts as $value) {
			$datosM[$value]['ImportProduct']['import_id'] 				= $import_id;
			$datosM[$value]['ImportProduct']['product_id'] 				= $value;
			$datosM[$value]['ImportProduct']['quantity'] 				= $this->request->data['Cantidad-'.trim($value)];
			$datosM[$value]['ImportProduct']['state_import'] 			= Configure::read('variables.control_importacion.solicitud_importacion');
			$datosM[$value]['ImportProduct']['quotations_products_id'] 	= 0;
			// $datosM[$value]['ImportsProduct']['id']             		= $this->Import->ImportProduct->new_row_model() + 1;
		}
		$this->Import->ImportProduct->create();
		$this->Import->ImportProduct->saveAll($datosM);
		$this->Session->delete('carritoImportaciones');
		return true;
	}

	public function emailAvisoRevisionGerencia($importID){

		$this->loadModel("User");

		$usersGerencia      		= $this->User->role_gerencia_user();
    	foreach ($usersGerencia as $value) {
			// Falta plantilla para enviar el correo
    		$options = array(
						'to'		=> $value['User']['email'],
						'template'	=> 'request_import',
						'subject'	=> 'Se genera nueva solicitud de importación',
						'vars'		=> array("import" => $importID),
					);
    		$this->sendMail($options);
    	}



	}

	public function message_users_logistica($prospective_id,$code_import,$codigo_cotizacion,$products,$import){
		$datosProspective 		= $this->FlowStage->ProspectiveUser->get_data($prospective_id);
    	$datosAsesor 			= $this->FlowStage->Quotation->User->get_data($datosProspective['ProspectiveUser']['user_id']);
    	$datosCliente 			= $this->findDataCliente($datosProspective['ProspectiveUser']['contacs_users_id'],$datosProspective['ProspectiveUser']['clients_natural_id']);
		$usersRoleLogistica     = $this->FlowStage->ProspectiveUser->User->role_logistica_user();

		foreach ($usersRoleLogistica as $value) {
			if ($import) {
				$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.logistica_pedido_gestionado_importacion'),Configure::read('variables.Gestiones_horas_habiles.pago_verificado'),$value['User']['id'],$prospective_id,Configure::read('variables.nombre_flujo.pago_verificado'),$this->webroot.'prospectiveUsers/information_dispatches');
			} else {
				$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.logistica_pedido_gestionado'),Configure::read('variables.Gestiones_horas_habiles.pago_verificado'),$value['User']['id'],$prospective_id,Configure::read('variables.nombre_flujo.pago_verificado'),$this->webroot.'prospectiveUsers/information_dispatches');
			}
			$arrayUserEmail[]   				= $value['User']['email'];
		}
		if ($import) {
			$options = array(
	            'to'        => $arrayUserEmail,
	            'template'  => 'import_request',
	            'subject'   => 'Solicitud de importación',
	            'vars'      => array('nombreCliente' => $datosCliente['name'],'nombreAsesor' => $datosAsesor['User']['name'],'code_import' => $prospective_id,'codigoCotizacion' => $codigo_cotizacion,'products' => $products)
	        );
			$this->sendMail($options);
		}
	}

	public function generateCodeImport(){
		$code = isset($this->request->data["internacional"]) && $this->request->data["internacional"] == 1 ?  Configure::read('variables.code_importaciones_int') : Configure::read('variables.code_importaciones');
		$filas 				= (int) $this->Import->count_importaciones() + 1;
		if ($filas < 10) {
           $codigo          = $code.'000'.$filas;
        } else if($filas > 9 && $filas < 100){
            $codigo         = $code.'00'.$filas;
        } else if($filas > 99 && $filas < 1000){
            $codigo         = $code.'0'.$filas;
        } else {
            $codigo         = $code.$filas;
        }
		return $codigo;
	}

	public function saveDespachoDatos(){

		$this->autoRender = false;

		$datosRequest = $this->request->data;



		if(isset($this->request->data["direccion"]) || $this->request->data["FlowStage"]["flete"] == "Tienda"){
			$this->loadModel("Adress");
			$this->Adress->recursive = -1;
			
			$datosAddress = array(
				"FlowStage" => array(
					"prospective_users_id" 	=> $this->request->data["FlowStage"]["prospective_user_id"],
					"copias_email" 			=> $this->request->data["FlowStage"]["copias_email"],
					"flete" 				=> $this->request->data["FlowStage"]["flete"],
				)
			);

			if(!isset($this->request->data["direccion"]))
			{
				$this->request->data["direccion"] = null;
			}

			$address 	= $this->Adress->findById($this->request->data["direccion"]);
			$telefono 	= !empty($address["Adress"]["phone_two"]) ? " - ".$address["Adress"]["phone_two"] : "";

			$datosAddress['FlowStage']['city']						= empty($address) ? "Tienda" : $address["Adress"]["city"];
			$datosAddress['FlowStage']['contact']					= empty($address) ? "N/A" : $address["Adress"]["name"];
			$datosAddress['FlowStage']['address']					= empty($address) ? "N/A" : $address["Adress"]["address"];
			$datosAddress['FlowStage']['additional_information']	= empty($address) ? "N/A" : $address["Adress"]["address_detail"];
			$datosAddress['FlowStage']['telephone']					= empty($address) ? "N/A" : $address["Adress"]["phone"].$telefono;
			$datosAddress['FlowStage']['state_flow']				= Configure::read('variables.nombre_flujo.datos_despacho');
			$datosAddress['FlowStage']['state']						= 2;

			$this->FlowStage->create();
			if ($this->FlowStage->save($datosAddress)) {
				$this->FlowStage->setDispatch();
				$flujo = $this->FlowStage->ProspectiveUser->find("first",array("recursive" => -1, "conditions" => array("id" => $datosAddress['FlowStage']['prospective_users_id'])));
				$flujo["ProspectiveUser"]["adress_id"] = $this->request->data["direccion"];
				$this->FlowStage->ProspectiveUser->save($flujo);

				$flowStage_id = $this->FlowStage->id_flow_bussines_latses_pagado($datosAddress['FlowStage']['prospective_users_id']);
				$this->updateFlowStageState($flowStage_id,1);
				$this->updateStateProspective($datosAddress['FlowStage']['prospective_users_id'],2);
				$this->messageUserRoleLogistica($datosAddress['FlowStage']['prospective_users_id']);
				$this->saveAtentionTimeFlujoEtapas($datosAddress['FlowStage']['prospective_users_id'],'dispatch_data_date','dispatch_data_time','dispatch');

				
			}

		}

		$flujo_id 				= $datosRequest['FlowStage']['prospective_user_id'];
		$numero_guia 			= $datosRequest['FlowStage']['number'];
		$transportadora 		= $datosRequest['FlowStage']['conveyor'];
		unset($datosRequest['FlowStage']['flujo_id']);
		unset($datosRequest['FlowStage']['number']);
		unset($datosRequest['FlowStage']['conveyor']);
		if ($datosRequest['FlowStage']['img']['name'] != '') {
			$imagen 										= $this->loadPhoto($datosRequest['FlowStage']['img'],'flujo/despachado');
			$datos['FlowStage']['document']					= $this->Session->read('imagenModelo');
		} else {
			$imagen 										= 1;
			$datos['FlowStage']['document'] 				= '';
		}

		if ($datosRequest['FlowStage']['image_products']['name'] != '') {
			$imagen 										= $this->loadPhoto($datosRequest['FlowStage']['image_products'],'flujo/despachado');
			$datos['FlowStage']['image_products']			= $this->Session->read('imagenModelo');
		} else {
			$imagen 										= 1;
			$datos['FlowStage']['image_products'] 			= null;
		}

		unset($datosRequest['FlowStage']['img']);
		$datos['FlowStage']['number'] 						= $numero_guia;
		$datos['FlowStage']['conveyor'] 					= $transportadora;
		$datos['FlowStage']['prospective_users_id']			= $flujo_id;
		$datos['FlowStage']['state_flow']					= Configure::read('variables.nombre_flujo.flujo_despachado');
		$contador 											= 0;
		$cotizacion_id 										= 0;

		if ($imagen === 1) {
			$products = [];
			for ($i=0; $i <= count($datosRequest['FlowStage']); $i++) {
				if (isset($datosRequest['FlowStage']['importacion_'.$i])) {
					if ($datosRequest['FlowStage']['importacion_'.$i] != '0') {
						$dataQPrdod			 										= $this->FlowStage->Quotation->QuotationsProduct->findById($datosRequest['FlowStage']['importacion_'.$i]);

						$quotation_id = $dataQPrdod["QuotationsProduct"]["quotation_id"];

						$products[]   = ["SendProduct"=> ["id" => $dataQPrdod["QuotationsProduct"]["product_id"],"quantity" => $dataQPrdod["QuotationsProduct"]["quantity"], "created" => date("Y-m-d H:i:s"), "Product" => $dataQPrdod["Product"] ]  ];


						$arrayImportaciones['QuotationsProduct']['id'] 			= $datosRequest['FlowStage']['importacion_'.$i];
						$arrayImportaciones['QuotationsProduct']['state'] 		= 3;
						$this->FlowStage->Quotation->QuotationsProduct->save($arrayImportaciones['QuotationsProduct']);
						$contador 														= $contador + $dataQPrdod["QuotationsProduct"]["quantity"];
					}
				}
			}
			if ($contador == 0) {
				$id_etapa_cotizado 		= $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
				$datosFlow 				= $this->FlowStage->get_data($id_etapa_cotizado);
				if (is_numeric($datosFlow['FlowStage']['document'])) {
					$this->FlowStage->Quotation->QuotationsProduct->update_entrega_orden($datos['FlowStage']['document']);
				}
			}
			$datos['FlowStage']['products_send'] 			= $contador;
			$datos['FlowStage']['products'] 				= json_encode($products);

			$count_row 										= $this->FlowStage->Quotation->QuotationsProduct->finish_products_cotizacion($cotizacion_id);
			if ($count_row < 1) {
				$finish 									= 1;
				$datos['FlowStage']['state'] 				= '6';
			} else {
				$finish 									= 0;
			}
		
			$this->FlowStage->create();
			if ($this->FlowStage->save($datos)) {

				$this->loadModel("ProductsLock");

				try {
					$this->ProductsLock->updateAll( ["ProductsLock.state" => 2], ["ProductsLock.prospective_user_id" => $datos['FlowStage']['prospective_users_id']  ] );
				} catch (Exception $e) {
					
				}

				if ($finish == 1) {
					$this->FlowStage->ProspectiveUser->save(["ProspectiveUser"=>["id" =>$flujo_id, "status" => 1, "updated" => date("Y-m-d H:i:s") ]]);
					$this->updateStateProspectiveFlow($flujo_id,Configure::read('variables.control_flujo.flujo_despachado'));

					$flujo = $this->FlowStage->ProspectiveUser->find("first",array("recursive" => -1, "conditions" => array("id" => $datos['FlowStage']['prospective_users_id'] )));

					if ($flujo["ProspectiveUser"]["type"] > 0) {
	                    $this->loadModel("TechnicalService");
	                    $this->TechnicalService->recursive = -1;
	                    $servicio = $this->TechnicalService->findById($flujo["ProspectiveUser"]["type"]);
	                    $servicio["TechnicalService"]["real_state"] = 2;
	                    $this->TechnicalService->save($servicio);
	                }

					$id_inset 			= $this->FlowStage->id;
					$this->updateStateProspective($flujo_id,1);
					$idInfoDespachado 	= $this->FlowStage->find_id_flowStage_state_infoDespacho($flujo_id);
					$this->updateFlowStageState($idInfoDespachado,1);
					
					$this->sendMailCliente($flujo_id,$numero_guia,$transportadora,$datos['FlowStage']['document']);
					
					$this->saveDataLogsUser(1,'FlowStage',$id_inset,Configure::read('variables.nombre_flujo.flujo_pagado').' - '.Configure::read('variables.nombre_flujo.flujo_despachado'));
					$this->saveAtentionTimeFlujoEtapas($flujo_id,'despachado_date','despachado_time','despachado');
				}
				$this->Session->setFlash(__('Despacho guardado correctamente'),'Flash/success');
				return true;
			}
		} else {
			return $imagen;
		}	
	}

	public function automatic(){
		$this->autoRender = false;
		$flujo_id = $this->request->data["flujo"];
		$factura  = (array) $this->getDocumentAuto(["prospectos"=>$flujo_id]);
		if (!empty($factura) ) {
			foreach ($factura as $key => $factValue) {


				if($key == $flujo_id){
						
					if ($this->request->data["quotation"] ==1) {
						$datos = $factValue;
						if (!empty($datos)) {
		                    $datos = (array) $datos;
		                }
						$this->loadModel("Quotation");
						$this->loadModel("ProspectiveUser");
						$this->loadModel("Product");

						if (!empty($datos) && isset($datos["productos_factura"]) && !empty($datos["productos_factura"]) ) {
							$idFlowstage   = $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
							$dataFlow 	   = $this->FlowStage->findById($idFlowstage);
							$dataQuotation = $this->Quotation->findById($dataFlow["FlowStage"]["document"]);
			                $dataQuotation["Quotation"]["id"] = null;
			                $dataQuotation["Quotation"]["total"] = 0;
			                $dataQuotation["Quotation"]["codigo"] = $this->generateIdentificationQuotation($flujo_id);

			                $qt = ["Quotation" => $dataQuotation["Quotation"] ];


		                    foreach ($datos["productos_factura"] as $key => $value) {
		                        $qt["Quotation"]["total"]  += floatval($value->Precio) * intval($value->Cantidad);
		                        $datos["productos_factura"][$key]->product_id         = $this->Product->field("id",["part_number" => $value->CódigoInventario]);
		                    }


		                    $this->Quotation->create();
		                    $this->Quotation->save($qt);

		                    $idQuotation = $this->Quotation->id;

		                    foreach ($datos["productos_factura"] as $key => $value) {
		                        $this->Quotation->QuotationsProduct->create();
		                        $dataProduct = array(
		                           "QuotationsProduct" => [
		                                "quotation_id"  => $idQuotation,
		                                "product_id"    => $value->product_id,
		                                "price"         => floatval($value->Precio),
		                                "quantity"      => intval($value->Cantidad),
		                                "delivery"      => "Inmediato",
		                                "state"         => 0,
		                                "currency"      => "cop"
		                           ]
		                        );
		                        $this->Quotation->QuotationsProduct->save($dataProduct);
		                    }

		                    $datosFlowsCotizado = array(
		                        "FlowStage" => array(
		                            "document" => $idQuotation,
		                            "priceQuotation" => $qt["Quotation"]["total"],
		                            "codigoQuotation" => $qt["Quotation"]["codigo"],
		                            "state_flow" => Configure::read('variables.nombre_flujo.flujo_cotizado'),
		                            "prospective_users_id" => $flujo_id,
		                            "copias_email" => "",            
		                            "cotizacion" => 2,            
		                        )
		                    );

		                    $this->ProspectiveUser->FlowStage->create();
		                    $this->ProspectiveUser->FlowStage->save($datosFlowsCotizado);

		                    $this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente'),'Flash/success'); 
		                }

					}
					$this->save_data_document($flujo_id,$factValue);
					$this->Session->setFlash(__('Factura cargada correctamente'),'Flash/success');
				}else{
					$this->Session->setFlash(__('La factura no tiene asociado el flujo wn WO.'),'Flash/success');
				}
			}					
		}else{
			$this->Session->setFlash(__('La factura no tiene asociado el flujo wn WO.'),'Flash/success');
		}	
	}

	public function validateFlowWo($flujo_id){
		$stateFlow = $this->FlowStage->ProspectiveUser->field("status_bill",["id"=>$flujo_id]);
		if (!is_bool($stateFlow) && $stateFlow == 1) {
			$factura = (array) $this->getDocumentAuto(["prospectos"=>$flujo_id]);
			if (!empty($factura) ) {
				foreach ($factura as $key => $value) {
					if($key == $flujo_id){
						$this->save_data_document($flujo_id,$value);
					}
				}					
			}						
		}
	}

	public function productos_factura(){
		$this->layout = false;
		$this->loadModel("Order");
		$flujo_id  				= $this->request->data["flujo_id"];
		$datosFlujo  			= $this->FlowStage->ProspectiveUser->findById($this->request->data["flujo_id"]);


		$orderData 	 			= $this->Order->find("first",["conditions" => ["Order.prospective_user_id" => $this->request->data["flujo_id"] ], "order" => ["Order.id" => "DESC" ] ]);


		$id_etapa_cotizado 			= $this->FlowStage->id_latest_regystri_state_cotizado($this->request->data["flujo_id"]);
		$datosFlowstage 			= $this->FlowStage->get_data($id_etapa_cotizado);
		$id_etapa_pagado 			= $this->FlowStage->id_latest_regystri_state_pagado($flujo_id,true);
		$datosPagado				= $this->FlowStage->get_data($id_etapa_pagado);

		$aprobado 					= false;

		if ($datosFlujo["ProspectiveUser"]["state_flow"] == 5 && in_array($datosPagado['FlowStage']['state'], [3,7]) && $datosPagado["FlowStage"]["payment_verification"] == 1) {
			$aprobado = true;
		}

		if (is_numeric($datosFlowstage['FlowStage']['document'])) {
			$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation(isset($orderData["Order"]["quotation_id"]) && !empty($orderData["Order"]["quotation_id"]) ? $orderData["Order"]["quotation_id"] : $datosFlowstage['FlowStage']['document']);
			$validateTypeCotizacion = '';
		} else {
			$produtosCotizacion 	= array();
			$validateTypeCotizacion = 'Cotización enviada a traves de un archivo PDF';
		}
		$transportadoras 			= Configure::read('variables.transportadoras');

		$internacionalLLC = false;

		foreach ($produtosCotizacion as $key => $value) {
			if (!is_null($value["QuotationsProduct"]["id_llc"]) && $value["QuotationsProduct"]["state_llc"] != 2 ) {
				$internacionalLLC = true;
				break;
			}
		}

		if($internacionalLLC){
			$this->FlowStage->Quotation->QuotationsProduct->setLlc($datosFlowstage['FlowStage']['document']);
			$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datosFlowstage['FlowStage']['document']);
		}

		$this->set(compact("produtosCotizacion","validateTypeCotizacion","transportadoras"));
		$this->set(compact("datosFlujo","aprobado"));
	}

	public function get_data_send_products(){

		$this->layout = false;

		$this->loadModel("Adress");
		$this->Adress->recursive = 1;

		$this->FlowStage->ProspectiveUser->unBindModel(array("hasMany" =>array( "FlowStage","AtentionTime","FlowStagesProduct")));


		$flujo_id  				= $this->request->data["flujo_id"];
		$this->validateFlowWo($flujo_id);
		$datosFlujo  			= $this->FlowStage->ProspectiveUser->findById($this->request->data["flujo_id"]);
		$direcciones 			= array();
		$direccionSeleccionado  = array();
		$cliente  				= array();
		$flete 					= Configure::read('variables.fleteEnvio');
		$emails 				= "";

		if($datosFlujo["ProspectiveUser"]["state_flow"] == Configure::read("variables.control_flujo.flujo_pagado")){
			if($this->request->data["type"] == "legal"){
				$direcciones = $this->Adress->findAllByClientsLegalId($this->request->data["cliente"]);
				$cliente     = $this->Adress->ClientsLegal->findById($this->request->data["cliente"])["ClientsLegal"];
			}else{
				$direcciones = $this->Adress->findAllByClientsNaturalId($this->request->data["cliente"]);
				$cliente     = $this->Adress->ClientsNatural->findById($this->request->data["cliente"])["ClientsNatural"];			
			}
		}else{
			$direccionSeleccionado = !empty( $datosFlujo["Adress"] ) ?  $datosFlujo["Adress"][0] : null;
		}


		if(!is_null($datosFlujo["ProspectiveUser"]["contacs_users_id"])){
			$contactos = $this->FlowStage->ProspectiveUser->ContacsUser->findAllByClientsLegalsIdAndState($datosFlujo["ContacsUser"]["clients_legals_id"],1);
			if(!empty($contactos)){
				$emails = Set::extract($contactos, "{n}.ContacsUser.email");
			}
		}
		$emails = $emails == "" ? $emails : implode(",", $emails);

		$this->loadModel("Conveyor");
        $conveyors = $this->Conveyor->find("list",["fields" => "name", "name"]);
        $conveyors = array_combine($conveyors, $conveyors);

		$this->set(compact("datosFlujo","direcciones","direccionSeleccionado", "cliente","flete","emails","conveyors"));

		if( $datosFlujo["ProspectiveUser"]["state_flow"] == Configure::read("variables.control_flujo.flujo_pagado")){
			$id_etapa_cotizado 			= $this->FlowStage->id_latest_regystri_state_cotizado($this->request->data["flujo_id"]);
			$datosFlowstage 			= $this->FlowStage->get_data($id_etapa_cotizado);
			if (is_numeric($datosFlowstage['FlowStage']['document'])) {
				$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datosFlowstage['FlowStage']['document']);
				$validateTypeCotizacion = '';
			} else {
				$produtosCotizacion 	= array();
				$validateTypeCotizacion = 'Cotización enviada a traves de un archivo PDF';
			}
			$transportadoras 			= Configure::read('variables.transportadoras');

			$internacionalLLC = false;

			foreach ($produtosCotizacion as $key => $value) {
				if (!is_null($value["QuotationsProduct"]["id_llc"]) && $value["QuotationsProduct"]["state_llc"] != 2 ) {
					$internacionalLLC = true;
					break;
				}
			}

			if($internacionalLLC){
				$this->FlowStage->Quotation->QuotationsProduct->setLlc($datosFlowstage['FlowStage']['document']);
				$produtosCotizacion 	= $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datosFlowstage['FlowStage']['document']);
			}

			$this->set(compact("produtosCotizacion","validateTypeCotizacion","transportadoras"));

		}

	}

	public function saveStateDespachado(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$flujo_id 				= $this->request->data['FlowStage']['flujo_id'];
			$numero_guia 			= $this->request->data['FlowStage']['number'];
			$transportadora 		= $this->request->data['FlowStage']['conveyor'];
			unset($this->request->data['FlowStage']['flujo_id']);
			unset($this->request->data['FlowStage']['number']);
			unset($this->request->data['FlowStage']['conveyor']);
			if ($this->request->data['FlowStage']['img']['name'] != '') {
				$imagen 										= $this->loadPhoto($this->request->data['FlowStage']['img'],'flujo/despachado');
				$datos['FlowStage']['document']					= $this->Session->read('imagenModelo');
			} else {
				$imagen 										= 1;
				$datos['FlowStage']['document'] 				= '';
			}
			unset($this->request->data['FlowStage']['img']);
			$datos['FlowStage']['number'] 						= $numero_guia;
			$datos['FlowStage']['conveyor'] 					= $transportadora;
			$datos['FlowStage']['prospective_users_id']			= $flujo_id;
			$datos['FlowStage']['state_flow']					= Configure::read('variables.nombre_flujo.flujo_despachado');
			$contador 											= 0;
			$cotizacion_id 										= 0;
			for ($i=0; $i <= count($this->request->data['FlowStage']); $i++) {
				if (isset($this->request->data['FlowStage']['importacion_'.$i])) {
					if ($this->request->data['FlowStage']['importacion_'.$i] != '0') {
						$cotizacion_id		 									= $this->FlowStage->Quotation->QuotationsProduct->quotation_id_sale($this->request->data['FlowStage']['importacion_'.$i]);
						$arrayImportaciones['QuotationsProduct']['id'] 			= $this->request->data['FlowStage']['importacion_'.$i];
						$arrayImportaciones['QuotationsProduct']['state'] 		= 3;
						$this->FlowStage->Quotation->QuotationsProduct->save($arrayImportaciones['QuotationsProduct']);
					}
				}
				$contador 														= $contador + 1;
			}
			if ($contador == 0) {
				$id_etapa_cotizado 		= $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
				$datosFlow 				= $this->FlowStage->get_data($id_etapa_cotizado);
				if (is_numeric($datosFlow['FlowStage']['document'])) {
					$this->FlowStage->Quotation->QuotationsProduct->update_entrega_orden($datos['FlowStage']['document']);
				}
			}
			$datos['FlowStage']['products_send'] 			= $contador;
			$count_row 										= $this->FlowStage->Quotation->QuotationsProduct->finish_products_cotizacion($cotizacion_id);
			if ($count_row < 1) {
				$finish 									= 1;
				$datos['FlowStage']['state'] 				= '6';
			} else {
				$finish 									= 0;
			}
			if ($imagen == 1) {
				// $datos['FlowStage']['id']             			= $this->FlowStage->new_row_model() + 1;
				$this->FlowStage->create();
				if ($this->FlowStage->save($datos)) {
					$this->FlowStage->setDispatch();
					if ($finish == 1) {
						$this->FlowStage->ProspectiveUser->save(["ProspectiveUser"=>["id" =>$flujo_id, "status" => 1, "updated" => date("Y-m-d H:i:s") ]]);
						$this->updateStateProspectiveFlow($flujo_id,Configure::read('variables.control_flujo.flujo_despachado'));
						$id_inset 			= $this->FlowStage->id;
						$this->updateStateProspective($flujo_id,1);
						$idInfoDespachado 	= $this->FlowStage->find_id_flowStage_state_infoDespacho($flujo_id);
						$this->updateFlowStageState($idInfoDespachado,1);
						if ($this->request->data['inlineRadioOptions'] == "1") {
							$this->sendMailCliente($flujo_id,$numero_guia,$transportadora,$datos['FlowStage']['document']);
						}
						$this->saveDataLogsUser(1,'FlowStage',$id_inset,Configure::read('variables.nombre_flujo.flujo_pagado').' - '.Configure::read('variables.nombre_flujo.flujo_despachado'));
						$this->saveAtentionTimeFlujoEtapas($flujo_id,'despachado_date','despachado_time','despachado');
					}
					return true;
				}
			} else {
				return $imagen;
			}
		}
	}

	public function sendMailCliente($prospective_users_id,$numeroGuia,$transportadora,$comprobante, $products = []){
		if ($comprobante != '') {
			$ruta 					= '/img/flujo/despachado/'.$comprobante;
			$comprobanteImg 		= '';
		} else {
			$ruta 					= '/img/flujo/despachado/default.jpg';
			$comprobanteImg 		= 'No se adjuntó';
		}
		$this->FlowStage->ProspectiveUser->recursive = 1;
		$datos					= $this->FlowStage->ProspectiveUser->findById($prospective_users_id);
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
		$copias_email 			= $this->ProspectiveUser->FlowStage->copias_email_despachado($prospective_users_id);
    	if ($copias_email != '') {
    		$emails 			= explode(',',$copias_email);
			if (isset($emails[0])) {
				$emailCliente 		= array_merge($emails, $emailCliente);
			} else {
				$emailCliente[1] 	= $copias_email;
			}
    	}
		$options = array('to'		=> $emailCliente,
						'template'	=> 'order_delivery',
						'subject'	=> 'Un producto o tu pedido en totalidad ha sido enviado',
						'vars'		=> array('numeroGuia' => $numeroGuia, 'transportadora' => $transportadora, 'name' => $name_usuario,'ruta' => $ruta,'comprobanteImg' => $comprobanteImg, "products" => $products ),
					);

		$this->sendMail($options);
		$options2 = array('to'		=> $datos["User"]["email"],
						'template'	=> 'order_delivery_user',
						'subject'	=> 'Un producto o tu pedido en totalidad ha sido enviado',
						'vars'		=> array('numeroGuia' => $numeroGuia, 'transportadora' => $transportadora, 'name' => $datos["User"]["name"],'ruta' => $ruta,'comprobanteImg' => $comprobanteImg, "products" => $products, "flujo"=>$prospective_users_id ),
					);

		$this->sendMail($options2);
	}

	public function state_invalid(){
		$this->layout 	= false;
	}

	public function updateFlowStageSendCotizacion($flowStage_id,$estado){
		$datos['FlowStage']['id']						= $flowStage_id;
		$datos['FlowStage']['send']						= $estado;
		$this->FlowStage->save($datos);
	}

	public function updateStateCotizadoContactado(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$flujo_id 				= $this->request->data['flujo_id'];
			$flowStage_id 			= $this->request->data['flow_id'];
			$latsetCotizado 		= $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
			
			$this->FlowStage->ProspectiveUser->save(["ProspectiveUser"=>["id" => $flujo_id, "date_quotation" => null, "date_prorroga_final" => null, "alert_one" => 0, "date_prorroga" => null,  "date_alert" => null, "alert_two" => 0,"date_final_alert" => null, "prorroga_one" => 0, "prorroga_two" => 0, 'notified' => 0, 'user_lose' => null, "deadline_notified" => null, "returned" => 1, "time_quotation" => $this->calculateHoursGest( Configuration::get_flow("hours_quotation") )  ]]);

			$this->updateStateProspectiveFlow($flujo_id,Configure::read('variables.control_flujo.flujo_contactado'));

			$this->updateFlowStageSendCotizacion($flowStage_id,2);
			$this->updateFlowStageState($latsetCotizado,0);
		}
	}

	public function verificarContabilidadPagoAbono(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$datos['FlowStage']['id']						= $this->request->data['etapa_id'];
			$datos['FlowStage']['state']					= 5;
			$this->FlowStage->save($datos);
	    	$this->messageUserRoleCotizacion($this->request->data['flujo_id'],'abono');
	    }
    }

	public function updatePaymentAbonoForTotalTrue(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {

			$response = $this->FlowStage->field("response",["id" => $this->request->data['etapa_id'] ]); 

			if(!empty($response) && !is_null($response)){

				$response = $this->object_to_arrayData(json_decode($response));
				$datos["FlowStage"]["response"] = null;

				$datosFlow["ProspectiveUser"]["id"] = $this->request->data['flujo_id'];
				$datosFlow["ProspectiveUser"]["flow"] = null;
				$this->FlowStage->ProspectiveUser->save($datosFlow);
			}

			$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.pago_verificado_asesor'),Configure::read('variables.Gestiones_horas_habiles.pago_verificado'),$this->request->data['user_id'],$this->request->data['flujo_id'],Configure::read('variables.nombre_flujo.pago_verificado'),$this->webroot.'prospectiveUsers/adviser?q='.$this->request->data['flujo_id']);
			$datos['FlowStage']['id']				= $this->request->data['etapa_id'];
			$datos['FlowStage']['type_pay'] 		= 0;
			$datos['FlowStage']['state'] 			= 7;
			$this->FlowStage->save($datos);

			if (!empty($response)) {
				$this->request->data = $response;
				$this->saveManageOrden();
			}

		}
	}

	public function updatePaymentAbonoForTotalFalse(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.pago_no_verificado_abono'),Configure::read('variables.Gestiones_horas_habiles.pago_verificado'),$this->request->data['user_id'],$this->request->data['flujo_id'],Configure::read('variables.nombre_flujo.pago_abono'),$this->webroot.'prospectiveUsers/adviser?q='.$this->request->data['flujo_id']);
			$datos['FlowStage']['id']							= $this->request->data['etapa_id'];
			$datos['FlowStage']['type_pay'] 					= 2;
			$datos['FlowStage']['state'] 						= 3;
			$datos['FlowStage']['response'] 					= null;
			$datos['FlowStage']['payment_false_description'] 	= $this->request->data['rason'];

			$flow = $this->FlowStage->ProspectiveUser->findById($this->request->data['flujo_id']);
			$flow["ProspectiveUser"]["flow"] = null;
			$this->FlowStage->ProspectiveUser->save($flow["ProspectiveUser"]);

			if($flow["ProspectiveUser"]["state"] == 6){
				$this->updateStateProspective($flujo_id,5);
			}
			$this->FlowStage->save($datos);
		}
	}

	public function view($id){
		if (!$this->FlowStage->exists($id)) {
			throw new NotFoundException('El documento se encuentra inhabilitado, por favor comunícate con el administrador');
		}
		$datos 		= $this->FlowStage->get_data($id);
		if ($datos['FlowStage']['state_flow'] != Configure::read('variables.nombre_flujo.flujo_cotizado')) {
			throw new NotFoundException('El documento se encuentra inhabilitado, por favor comunícate con el administrador');
		}
		$this->set(compact('datos'));
	}

}
<?php
App::uses('AppController', 'Controller');

App::uses('HttpSocket', 'Network/Http');

require_once ROOT.'/app/Vendor/CifrasEnLetras.php';


class ImportRequestsController extends AppController {


	public $components = array('Paginator');

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('set_trm','imports_faileds');
    }

    public function imports_faileds(){
    	$this->autoRender = false;
    	$this->loadModel("ProspectiveUser");
    	return 1;
    	$datos = $this->ImportRequest->ImportRequestsDetail->find("all",[
    		"recursive" => -1,
    		"joins" => [ 
    			array(
                    'table' => 'import_requests',
                    'alias' => 'ImportRequest',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ImportRequest.id = ImportRequestsDetail.import_request_id AND ImportRequest.import_id is not null'
                    )
                ),
                array(
                    'table' => 'prospective_users',
                    'alias' => 'ProspectiveUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProspectiveUser.id = ImportRequestsDetail.prospective_user_id'
                    )
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProspectiveUser.user_id = User.id'
                    )
                ),
                array(
                    'table' => 'imports',
                    'alias' => 'Import',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ImportRequest.import_id = Import.id AND Import.state in (1,3)'
                    )
                ),
                array(
                    'table' => 'import_products_details',
                    'alias' => 'ImportProductsDetail',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ImportProductsDetail.import_id = Import.id AND ImportRequestsDetail.id = ImportProductsDetail.import_requests_detail_id AND ImportProductsDetail.quantity_final < ImportProductsDetail.quantity'
                    )
                ),
    		],
    		"conditions" => ["ImportRequestsDetail.deadline <" => date("Y-m-d"), "ImportRequestsDetail.notify" => 0 ],
    		"fields" => ["ProspectiveUser.id","ProspectiveUser.clients_natural_id","ProspectiveUser.contacs_users_id","User.*","ImportRequestsDetail.*"],
    		"group" => ["ProspectiveUser.id"]
    	]);

    	if (!empty($datos)) {
    		foreach ($datos as $key => $value) {
    			if ($value['ProspectiveUser']['clients_natural_id'] > 0) {
					$datosC 			= $this->ProspectiveUser->ClientsNatural->get_data($value['ProspectiveUser']['clients_natural_id']);
					$email_usuario 		= $datosC['ClientsNatural']['email'];
					$name_usuario 		= $datosC['ClientsNatural']['name'];
				} else {
					$datosC 			= $this->ProspectiveUser->ContacsUser->get_data($value['ProspectiveUser']['contacs_users_id']);
					$email_usuario 		= $datosC['ContacsUser']['email'];
					$name_usuario 		= $datosC['ContacsUser']['name'];
				}

				$options = array(
					// 'to'		=> "jotsuar@gmail.com",
					'to'		=> $emailCliente,
					'template'	=> 'demora_customer',
					'subject'	=> 'Situación especial sobre tu flujo de proceso',
					'vars'		=> array('name' => $name_usuario, "datos_asesor" => $value["User"], "flujo" => $value["ProspectiveUser"]["id"] ),
				);
				$this->sendMail($options);

				$options2 = array(
					// 'to'		=> "jotsuar@gmail.com",
					'to'		=> $value["User"]["email"],
					'template'	=> 'demora_user',
					'subject'	=> 'Situación especial sobre tu flujo de proceso',
					'vars'		=> array('name' => $value["User"]["name"], "flujo" => $value["ProspectiveUser"]["id"] ),
				);
				$this->sendMail($options2);

				$value["ImportRequestsDetail"]["notify"] = 1;
				$this->ImportRequest->ImportRequestsDetail->save($value["ImportRequestsDetail"]);
    		}
    	}

    }

    public function detail_imports($detail_id) {
    	$this->layout = false;
    	$request 	= $this->ImportRequest->ImportRequestsDetail->findById($this->desencriptarCadena($detail_id));

    	$products = [];

	 	foreach ($request["Product"] as $keyProduct => $product) {
            if ($product["ImportRequestsDetailsProduct"]["state"] == 1) {
                if (!array_key_exists($product["id"], $products)) {
                    $products[$product["id"]] = ["Product"=>$product];
                }
            }else{
                unset($request["Product"][$keyProduct]);
                continue;
            }
        }

        $this->loadModel("Config");
        $inventioWo         = $this->getValuesProductsWo($products);

        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $factorImport   = $config["Config"]["factorUSA"];

        $this->set(compact("request","inventioWo","trmActual","factorImport"));
	}

    public function others_references(){
    	$this->layout = false;

    	$request 	= $this->ImportRequest->ImportRequestsDetail->findById($this->request->data["id"]);
    	$actualIds 	= [];
    	$partsData 	= [];
    	$precios 	= [];
    	$requestId  = $this->request->data["id"];

    	if (!empty($request["Product"])) {
    		foreach ($request["Product"] as $key => $value) {
    			if ($value["ImportRequestsDetailsProduct"]["state"] == 1) {
    				$actualIds[] = $value["id"];
    			}
    		}
    	}

    	$this->loadModel("Product");

    	$categoriesData = $this->getCategoryInfo(true);
    	$otherProducts  = $this->Product->find("all", ["limit" => 10, "conditions" => 
    		[ 
    		"NOT" => [ "Product.id" => $actualIds ], 
    		"Product.deleted" => 0, "Product.state" => 1, 
    		  'OR' => array(
				            'LOWER(Product.part_number) LIKE' 			=> '%'.mb_strtolower(trim($this->request->data["search"])).'%',
				            'LOWER(Product.name) LIKE' 					=> '%'.mb_strtolower(trim($this->request->data["search"])).'%',
				        )
    		 ], 
    	"recursive" => -1, ] );

    	if (!empty($otherProducts)) {
			$partsData				= $this->getValuesProductsWo($otherProducts);
			$precios 				= $this->getPrices($partsData);
		}

    	$this->set(compact("categoriesData","otherProducts","requestId","partsData","precios"));
    }

    public function add_reference(){
		$this->layout = false;
		$this->loadModel("Product");
		$producto = $this->Product->find("first",["recursive" => -1, "conditions" => ["Product.id" => $this->request->data["id"] ] ]);
		$partsData				= $this->getValuesProductsWo([$producto]);
		$precios 				= $this->getPrices($partsData);
		$this->set(compact("producto","partsData","precios"));
	}

	public function add_reference_final(){
		$this->autoRender = false;
		$this->loadModel("ImportRequestsDetail");

		$dataProduct = [ "ImportRequestsDetailsProduct" => [
			"import_requests_detail_id" => $this->request->data["request"],
			"product_id" => $this->request->data["id"],
			"quantity" => $this->request->data["quantity"],
			"state" => 1
		] ];

		$this->ImportRequestsDetail->ImportRequestsDetailsProduct->create();
		if ($this->ImportRequestsDetail->ImportRequestsDetailsProduct->save($dataProduct)){
			$this->Session->setFlash(__('Producto agregado correctamente.'),'Flash/success');	
		} else {
			$this->Session->setFlash(__('No fue posible agregar el producto.'),'Flash/error');
		}
	}

    public function delete_part(){
    	$this->autoRender = false;
    	$id = $this->request->data["id"];

    	$this->loadModel("ImportRequestsDetailsProduct");
    	$this->ImportRequestsDetailsProduct->recursive = -1;
    	$dataProduct = $this->ImportRequestsDetailsProduct->findById($id);
    	$dataProduct["ImportRequestsDetailsProduct"]["state"] = 0;

    	if ($this->ImportRequestsDetailsProduct->save($dataProduct)) {
			$this->Session->setFlash(__('Se eliminó correctamente'),'Flash/success');			
		} else {
			$this->Session->setFlash(__('Error, Se eliminó correctamente'),'Flash/error');
		}
    }

    public function editar_cantidad($id){
    	$this->layout = false;
    	$this->loadModel("ImportRequestsDetailsProduct");

    	$dataProduct = $this->ImportRequestsDetailsProduct->findById($id);
    	$inventioWo  = $this->getValuesProductsWo([$dataProduct]);

    	if ($this->request->is(array('post', 'put'))) {
    		if ($this->ImportRequestsDetailsProduct->save($this->request->data)) {
				$this->Session->setFlash(__('Se cambió correctamente la cantidad'),'Flash/success');
				
			} else {
				$this->Session->setFlash(__('No fue posible cambiar la cantidad'),'Flash/error');
			}
			return $this->redirect(array('action' => 'solicitudes_internas',"controller" => "ProspectiveUsers"));
    	}

    	$this->set("datos",$dataProduct);
    	$this->set("inventario_wo",$inventioWo[$dataProduct["Product"]["part_number"]]);

    	// var_dump($inventioWo);
    	// var_dump($dataProduct);
    }

    public function finally_import($id, $url){
    	$this->autoRender = false;
    	$this->loadModel("Import");
    	$this->loadModel("ImportProductsDetail");
    	$this->loadModel("ProspectiveUser");
    	$this->loadModel("Product");

    	$this->Import->recursive = -1;
    	$import = $this->Import->findById($id);
    	$import["Import"]["state"] = 2;
    	$import["Import"]["updated"] = date("Y-m-d H:i:s");
    	$this->Import->save($import);

    	$details = $this->ImportProductsDetail->find("list",["fields" => ["product_id","flujo"], "conditions" => ["import_id" => $id, "flujo !="=>0 ]   ]);

    	foreach ($details as $productId => $flujo) {
    		$id_etapa_cotizado 			= $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($flujo);
			$datosFlowstage 			= $this->ProspectiveUser->FlowStage->get_data($id_etapa_cotizado);
			if (is_numeric($datosFlowstage['FlowStage']['document']) ) {
				$productoCotizado 	= $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->findByQuotationIdAndProductIdAndState($datosFlowstage['FlowStage']['document'],$productId,[2,5]);
				$productoCotizado["QuotationsProduct"]["state"] 	= 6;
				$this->Product->QuotationsProduct->save($productoCotizado['QuotationsProduct']);
			}
    	}
    	$this->Import->ImportProduct->updateAll(["ImportProduct.state_import" => 7],["ImportProduct.import_id" => $id]);
    	$this->redirect(["controller"=>"products","action" => "products_import",$this->encryptString($id)]);
    }

	public function set_trm(){
		$this->autoRender = false;
		$this->loadModel("Trm");
		
		$HttpSocket = new HttpSocket(['ssl_allow_self_signed' => false, 'ssl_verify_peer' => false, 'ssl_verify_host' =>false ]);
		$url = 'https://www.datos.gov.co/resource/mcec-87by.json?$limit=';
		$results = $HttpSocket->get($url.'1');
		$trm = json_decode($results->body);

		if(!empty($trm[0])){
			$this->loadModel("Config");
			$config = $this->Config->findById(1);
			$config["Config"]["trm"] = $trm[0]->valor;
			$this->Config->save($config);
		}

		$url 		= 'https://www.datos.gov.co/resource/mcec-87by.json?$limit=';
		$results 	= $HttpSocket->get($url.'10');
		$trm2 		= json_decode($results->body);

		if(!empty($trm2)){
			foreach ($trm2 as $key => $value) {
				$fechaIni = str_replace("T00:00:00.000", "", $value->vigenciadesde);
				$fechaFin = str_replace("T00:00:00.000", "", $value->vigenciahasta);
				$trmValue = $this->Trm->findByFechaInicioAndFechaFin($fechaIni,$fechaFin);
				if(empty($trmValue)){
					$dataValue = array("Trm" => array("valor" => $value->valor, 'fecha_inicio' => $fechaIni, "fecha_fin" => $fechaFin));
					$this->Trm->create();
					$this->Trm->save($dataValue);
				}

			}
		}

		$this->loadModel("ProspectiveUser","FlowStage");

        $this->loadModel('SbFlujo');

        $flujos = $this->SbFlujo->find("all",["conditions"=>["SbFlujo.created >=" => date("Y-m-01 00:00:00", strtotime('-1 month') ) ]]);
        // $flujos = $this->SbFlujo->find("all");

        if(!empty($flujos)){
            $allFlujos = [];
            foreach ($flujos as $key => $value) {
                $allFlujos[ $value["SbFlujo"]["flujo"] ] = $value["SbConversation"]["email"] != 'info@kebco.co' ? $value["SbConversation"]["email"] : $value["SbConversation"]["email_sub"];
            }
            $flujosRobot = $this->ProspectiveUser->find("all",["recursive" => 1, "conditions" => ["ProspectiveUser.id" => array_keys($allFlujos) ] ]);
            foreach ($flujosRobot as $key => $value) {
                if($value["ProspectiveUser"]["user_id"] == "112" && !is_null($allFlujos[$value["ProspectiveUser"]["id"]]) &&  $allFlujos[$value["ProspectiveUser"]["id"]] != 'info@kebco.co' ){
                    $this->ProspectiveUser->updateAll(["ProspectiveUser.user_id" => $this->ProspectiveUser->User->field("id",["email"=>$allFlujos[$value["ProspectiveUser"]["id"]]]) ], ["ProspectiveUser.id"=>$value["ProspectiveUser"]["id"]] );
                }
            }
        }

	}

	public function index() {
		$this->ImportRequest->recursive = 0;
		$this->set('importRequests', $this->Paginator->paginate());
	}

	public function view($id = null) {
		$this->layout = false;
		$id = $this->desencriptarCadena($id);
		if (!$this->ImportRequest->exists($id)) {
			throw new NotFoundException(__('Invalid import request'));
		}
		$options = array('conditions' => array('ImportRequest.' . $this->ImportRequest->primaryKey => $id));
		$importRequest = $this->ImportRequest->find('first', $options);

		$importData = $this->ImportRequest->Import->findById($importRequest["Import"]["id"]);

		$productForBrand = end($importData["Product"]);

		$currency = $productForBrand["ImportProduct"]["currency"];

		if(!is_null($importRequest["Import"]["brand_id"])){
			$brandInfo = $this->ImportRequest->Import->Product->Brand->findById($importRequest["Import"]["brand_id"]);
		}else{
			$brandInfo = $this->ImportRequest->Import->Product->Brand->findById($productForBrand["brand_id"]);
		}

		$total = 0;

		foreach ($importData["Product"] as $key => $value) {
			if ($value["ImportProduct"]["quantity"] == 0){
				continue;
			}
			$total+=($value["ImportProduct"]["price"] * $value["ImportProduct"]["quantity"]);
		}

		if($currency == "cop"){
			$letras = CifrasEnLetras::convertirNumeroEnLetras(($total*1.19));
			$this->set("letras",$letras);
		}

		$this->set('import',$importData );
		$this->set('brandInfo',$brandInfo );
		$this->set('productForBrand',$productForBrand );
		$this->set('currency',$currency );
	}

	public function noSendInfoProvider($idRequest = null){
		$this->autoRender 	= false;

		if(is_null($idRequest)){
			return false;
		}
		if (!is_numeric($idRequest)) {
			$idRequest 			= $this->desencriptarCadena($idRequest);
		}
		$options 			= array('conditions' => array('ImportRequest.' . $this->ImportRequest->primaryKey => $idRequest));
		$importRequest 		= $this->ImportRequest->find('first', $options);
		$importData 		= $this->ImportRequest->Import->findById($importRequest["Import"]["id"]);

		$importData["Import"]["fecha_gerencia"] = date("Y-m-d H:i:s");
		$this->ImportRequest->Import->save($importData["Import"]);

		$this->updateProductsQuantities($importData["ImportProduct"]);
		$productForBrand 	= end($importData["Product"]);
		$brandInfo 			= $this->ImportRequest->Import->Product->Brand->findById($productForBrand["brand_id"]);


		$this->updateInfo($importRequest["Import"]["id"],$this->request->data["enviar"]);
		$this->updateFlujoInfo($importRequest["Import"]["id"],$brandInfo);

		if ($importRequest["ImportRequest"]["type_money"] == 'cop') {
			$subject = 'Se aprobó la orden de compra con código : '.$importData["Import"]["code_import"];
			$this->loadModel('Manage');
			$this->Manage->sendNotificationCompra($subject,$importData["Import"]["code_import"],6);
		}

		$other = $this->getOtherAprovee();
		echo $other; 
		die();
	}

	public function reloadData(){
		$this->autoRender = false;
		$this->loadModel("ImportRequestsDetailsProduct");	
		$this->ImportRequest->ImportRequestsDetail->recursive = -1;
		$detail = $this->ImportRequest->ImportRequestsDetail->findById($this->request->data["id"]);
		$details = $this->ImportRequestsDetailsProduct->find("all", ["conditions" => ["import_requests_detail_id" => $this->request->data["id"]],"recursive" => -1 ]);


		if(!empty($details)){
			$this->loadModel("BillProduct");
			foreach ($details as $key => $value) {
				$conditions = array(
					"BillProduct.product_id" => $value["ImportRequestsDetailsProduct"]["product_id"], 
					"BillProduct.quantity" => $value["ImportRequestsDetailsProduct"]["quantity"],
					"BillProduct.state" => 0,
				);
				$this->BillProduct->recursive = -1;
				$productData = $this->BillProduct->find("first", compact("conditions"));

				if(!empty($productData)){
					$productData["BillProduct"]["state"] = 1;
					$this->BillProduct->save($productData);
				}
			}
			if(!empty($detail)){
				$detail["ImportRequestsDetail"]["state"] = 0;
				$this->ImportRequest->ImportRequestsDetail->save($detail);
			}
		}	
	}

	public function approve(){
		$this->autoRender = false;
		
		$request = $this->ImportRequest->ImportRequestsDetail->findById($this->request->data["request_id"]);
		$request["ImportRequestsDetail"]["state"] = 1;
		if (isset($this->request->data["motivo"]) && !empty($this->request->data["motivo"])) {
			$request["ImportRequestsDetail"]["description"].= " <br> Se aprueba por gerencia con la siguiente nota: ".$this->request->data["motivo"];
		}
		$request["ImportRequestsDetail"]["modified"] = date("Y-m-d H:i:s");
		$this->ImportRequest->ImportRequestsDetail->save($request["ImportRequestsDetail"]);

		$emails = array($request["User"]["email"]);

		$options = array(
			'cc'		=> ["logistica@kebco.co"],
			'to'		=> $emails,
			'template'	=> 'approve_solicitud',
			'subject'	=> 'Aprobación de solicitud de importación interna de KEBCO AlmacenDelPintor.com',
			'vars'		=> array("nombreAsesor" => AuthComponent::user("name"), "nota" => $this->request->data["motivo"], "products" => $request["Product"] )
		);

		$this->sendMail($options);

		$this->Session->setFlash('Se aprobo la solicitud, ahora será visible en el panel de logística.', 'Flash/success');
	}


	public function reject(){
		$this->autoRender = false;
		
		$request = $this->ImportRequest->ImportRequestsDetail->findById($this->request->data["request_id"]);
		if(!empty($request)){

			if(!empty($request["ImportRequestsDetail"]["prospective_user_id"])){
				$this->ImportRequest->ImportRequestsDetail->recursive = 1;
				$allRequestProspective = $this->ImportRequest->ImportRequestsDetail->findAllByProspectiveUserIdAndState($request["ImportRequestsDetail"]["prospective_user_id"],1);

				$productIds = array();

				foreach ($allRequestProspective as $key => $value) {
					$value["ImportRequestsDetail"]["state"] = 0;
					$value["ImportRequestsDetail"]["motive"] = $this->request->data["motivo"];

					foreach ($value["Product"] as $keyPr => $product) {
						$productIds[] = $product["id"];
					}

					$this->ImportRequest->ImportRequestsDetail->save($value["ImportRequestsDetail"]);
				}

				$this->loadModel("FlowStage");
				$this->loadModel("ProgresNote");
				$this->loadModel("ProductsLock");
				$this->loadModel("QuotationsProduct");

				$this->ProductsLock->updateAll(
				    array('ProductsLock.state' => 0),
				    array('ProductsLock.prospective_user_id' => $request["ImportRequestsDetail"]["prospective_user_id"],"ProductsLock.product_id" => $productIds)
				);

				$id_etapa_cotizado 			= $this->FlowStage->id_latest_regystri_state_cotizado($request["ImportRequestsDetail"]["prospective_user_id"]);
				$datosFlowstage 			= $this->FlowStage->get_data($id_etapa_cotizado);

				$this->QuotationsProduct->recursive = -1;
				$productos = $this->QuotationsProduct->findAllByQuotationIdAndProductId($datosFlowstage["FlowStage"]["document"],$productIds);

				foreach ($productos as $key => $valueProduct) {
					$valueProduct["QuotationsProduct"]["state"] = 0;
					$this->QuotationsProduct->save($valueProduct);
				}

				
				$stage = $this->FlowStage->find("first",array("conditions" => array("state_flow" => "Pagado", "prospective_users_id" => $request["ImportRequestsDetail"]["prospective_user_id"]),"order" => array("FlowStage.id" => "DESC"), "recursive" => -1 ));

				$stage["FlowStage"]["state"] = 7;
				$this->FlowStage->save($stage);

				$progresNote['ProgresNote']['description'] 				= $this->request->data["motivo"];
		        $progresNote['ProgresNote']['etapa'] 					= "Pagado";
		        $progresNote['ProgresNote']['prospective_users_id'] 	= $request["ImportRequestsDetail"]["prospective_user_id"];
		        $progresNote['ProgresNote']['user_id'] 					= AuthComponent::user('id');
				$this->ProgresNote->save($progresNote);

				
			}
			
			$request["ImportRequestsDetail"]["state"] = 0;
			$request["ImportRequestsDetail"]["motive"] = $this->request->data["motivo"];
			$this->ImportRequest->ImportRequestsDetail->save($request);

			$allRequest = $this->ImportRequest->ImportRequestsDetail->findAllByImportRequestIdAndState($request["ImportRequestsDetail"]["import_request_id"],[1,2]);
			if(count($allRequest) == 0){
				$this->ImportRequest->recursive = -1;
				$importRequest = $this->ImportRequest->findById($request["ImportRequestsDetail"]["import_request_id"]);
				$importRequest["ImportRequest"]["state"] = 0;
				$this->ImportRequest->save($importRequest);
			}

			if ($request["ImportRequestsDetail"]["type_request"] == 4) {
				$this->ImportRequest->ImportRequestsDetail->delete($request["ImportRequestsDetail"]["id"]);				
			}			

			$emails = array($request["User"]["email"],"logistica@kebco.co");

			$options = array(
				'to'		=> $emails,
				'template'	=> 'reject_request',
				'subject'	=> 'Cancelación de solicitud de importación de productos de KEBCO AlmacenDelPintor.com',
				'vars'		=> array("nombreAsesor" => AuthComponent::user("name"),"brand" => $this->ImportRequest->Brand->field("name",array("id" => $request["ImportRequest"]["brand_id"] )), "motive" => $this->request->data["motivo"], "products" => $request["Product"], "prospective" => $request["ImportRequestsDetail"]["prospective_user_id"] )
			);

			$this->sendMail($options);
		}
	}

	private function getOtherAprovee(){
		$this->loadModel('Import');
		$conditions 				= array('Import.state' => Configure::read('variables.importaciones.solicitud'));
		$order						= array('Import.id' => 'desc');

		$other = $this->Import->find("first",compact("conditions","order"));

		return !is_null($other) && !empty($other) ? $this->encryptString($other["Import"]["id"]) : null;
	}

	private function updateProductsQuantities($productos){
		$this->loadModel("Product");
		foreach ($productos as $key => $value) {
			if($value["quantity_back"] > 0){
				$producto = $this->Product->find("first",["conditions" => ["id" => $value["product_id"]], "recursive" => -1]);
				$producto["Product"]["quantity_back"]+= $value["quantity_back"];
				$this->Product->save($producto);

				$this->loadModel("Inventory");

				$inventory = array(
		    		"Inventory" => array(
		    			"product_id" 	=> $value["product_id"],
		    			"warehouse"		=> "Transito",
		    			"quantity" 		=> intval($value["quantity_back"]),
		    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
		    			"type_movement"	=> Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION"),
		    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION")),
		    			"user_id"		=> AuthComponent::user("id"),
		    			"import_id"		=> $value["import_id"],
		    		)
		    	);

		    	$this->Inventory->create();
		    	$this->Inventory->save($inventory);

			}
		}
	}

	public function sendInfoProvider($idRequest){
		$this->autoRender 	= false;
		if (!is_numeric($idRequest)) {
			$idRequest 			= $this->desencriptarCadena($idRequest);
		}
		$emails    			= array();
		$enviar    			= 1;

		$options = array('conditions' => array('ImportRequest.' . $this->ImportRequest->primaryKey => $idRequest));
		$importRequest = $this->ImportRequest->find('first', $options);

		if(!empty($this->request->data["emails"])){
			$emails = explode(",", $this->request->data["emails"]);
		}

		if(isset($this->request->data["enviar"])){
			$enviar = $this->request->data["enviar"];
		}

		$this->updateInfo($importRequest["Import"]["id"],$enviar);


		$this->loadModel("Import");
		$this->Import->recursive = 1;
		$importData = $this->Import->findById($importRequest["Import"]["id"]);

		$importData["Import"]["fecha_gerencia"] = date("Y-m-d H:i:s");
		$this->Import->save($importData["Import"]);

		$this->updateProductsQuantities($importData["ImportProduct"]);

		$productForBrand = end($importData["Product"]);

		$currency = $productForBrand["ImportProduct"]["currency"];

		$total = 0;

		foreach ($importData["Product"] as $key => $value) {
			if ($value["ImportProduct"]["quantity"] == 0){
				continue;
			}
			$total+=($value["ImportProduct"]["price"] * $value["ImportProduct"]["quantity"]);
		}

		$letras = null;

		if($currency == "cop"){
			$letras = CifrasEnLetras::convertirNumeroEnLetras(round(($total*1.19), 2));
			$this->set("letras",$letras);
		}

		if(!is_null($importRequest["Import"]["brand_id"])){
			$brandInfo = $this->ImportRequest->Import->Product->Brand->findById($importRequest["Import"]["brand_id"]);
		}else{
			$brandInfo = $this->ImportRequest->Import->Product->Brand->findById($productForBrand["brand_id"]);
		}

		$this->updateFlujoInfo($importRequest["Import"]["id"],$brandInfo);

		$options 						= array(
			'template'	=> 'request_import',
			'ruta'		=> APP . 'webroot'.DS.'files'.DS.'requests'.DS.md5($idRequest).".pdf",
			'vars'		=> array("import" => $importData, "brandInfo" => $brandInfo, "productForBrand" => $productForBrand,"currency" => $currency,"letras"=>$letras),
		);

		$this->generatePdf($options);

		$this->sendRequestImport($idRequest,$brandInfo, $importRequest["Import"]["text_brand"],$emails);

		if ($importRequest["ImportRequest"]["type_money"] == 'cop') {
			$subject = 'Se aprobó la orden de compra con código : '.$importData["Import"]["code_import"];
			$this->loadModel('Manage');
			$this->Manage->sendNotificationCompra($subject,$importData["Import"]["code_import"],6);
		}

		$this->Session->setFlash(__('La solicitud fue enviada correctamente'),'Flash/success');
		$other = $this->getOtherAprovee();
		echo $other; 
		die();
	}

	public function update_field(){
		$this->autoRender = false;

		$import = $this->ImportRequest->Import->find("first",["recursive" => -1, "conditions" => ["Import.id" => $this->request->data["id"] ] ]);
		$import["Import"]["purchase_order"] = $this->request->data["nuevo_valor"];
		$this->ImportRequest->Import->save($import["Import"]);
	}

	private function updateInfo($importRequest, $enviar = 1){
		$this->loadModel("ImportProductsDetail");
		$this->loadModel("Import");
		$this->Import->recursive 				= 1;
		$import 								= $this->Import->findById($importRequest);
		$maxRequest 							= $this->Import->generate();
		$maxRequest 							= $this->Import->field("MAX(purchase_order)") ;
		
		$import["Import"]["state"] 				= 1;
		$import["Import"]["send_provider"] 		= 1;

		if(!empty($import["Import"]["purchase_order"])){
			$import["Import"]["purchase_order"] 	= is_null($maxRequest) || $maxRequest == false ? time() : $maxRequest+10;
		}

		try {
			$import["Import"]["deadline"]			= $this->calculateFechaFinalEntrega(date("Y-m-d"),15);			
		} catch (Exception $e) {
			$import["Import"]["deadline"] 			= date("Y-m-d",strtotime("+20 day"));
		}
		if (is_null($import["Import"]["deadline"]) || $import["Import"]["deadline"] == "0000-00-00") {
			$import["Import"]["deadline"] = date("Y-m-d",strtotime("+20 day"));
		}
		$this->ImportRequest->Import->save($import["Import"]);
		$importDetail  = $this->ImportProductsDetail->find("all",["conditions" => ["ImportProductsDetail.import_id" => $importRequest, "ImportProductsDetail.flujo !=" => NULL ] ]);

		if (!empty($importDetail) && $enviar = 1) {
			foreach ($importDetail as $key => $value) {
				$requestData = $this->ImportRequest->ImportRequestsDetail->findById($value["ImportProductsDetail"]["import_requests_detail_id"]);

				if (!empty($requestData) && isset($requestData['ProspectiveUser']) && isset($requestData["ImportRequestsDetail"]) ) {

					$datosCliente = $this->getDataCustomer($requestData["ImportRequestsDetail"]["prospective_user_id"]);

					$datos_asesor = $this->ImportRequest->ImportRequestsDetail->User->get_data($requestData['ProspectiveUser']['user_id']);

					$emails 	  = [AuthComponent::user("email"),$datos_asesor["User"]["email"]];
					$emails[] 	  = $datosCliente["email"];

					$fecha  	  = $import["Import"]["deadline"];

					$options = array(
						'to'		=> $emails,
						'template'	=> 'informe_customer_import',
						'subject'	=> 'Te informamos sobre la importación de tus productos - KEBCO AlmacenDelPintor.com',
						'vars'		=> compact("datosCliente","datos_asesor","requestData","fecha"),
					);			

					$this->sendMail($options, true);

				}

			}
		}

		
	}

	private function updateFlujoInfo($importId, $brandInfo){
		$import 								= $this->ImportRequest->Import->findById($importId);
		foreach ($import["Product"] as $key => $product) {
			$product['ImportProduct']['numero_orden'] 			= $import["Import"]["purchase_order"];
			$product['ImportProduct']['proveedor'] 				= $brandInfo["Brand"]["social_reason"];
			$product['ImportProduct']['state_import'] 			= Configure::read('variables.control_importacion.orden_montada');
			$product['ImportProduct']['fecha_orden'] 			= date('Y-m-d');
			$this->ImportRequest->Import->Product->ImportProduct->save($product["ImportProduct"]);
		}
		$this->Session->setFlash(__('Los datos fueron guardados correctamente.'),'Flash/success');	
	}

	private function sendRequestImport($idRequest,$brandInfo, $textoProveedor, $emails = array()){

		$emails[] = $brandInfo["Brand"]["email"];
		// $emails[] = "gerencia@almacendelpintor.com";
		$emails[] = "logistica@kebco.co";

		if (file_exists(WWW_ROOT.'/files/requests/'.md5($idRequest).'.pdf')) {
			$options 					= array(
				'to'		=> $emails,
				'template'	=> 'request_import_provider',
				'subject'	=> 'Solicitud de importación de productos de KEBCO AlmacenDelPintor.com',
				'vars'		=> array("texto" => $textoProveedor),
				'file'		=> 'files/requests/'.md5($idRequest).'.pdf'
			);
		} else {
			$options 					= array(
				'to'		=> $emails,
				'template'	=> 'request_import_provider',
				'subject'	=> 'Solicitud de importación de productos de KEBCO AlmacenDelPintor.com',
				'vars'		=> array("texto" => $textoProveedor)
			);
		}
		$this->sendMail($options, true);
	}

	public function change_payed($uid) {
		$this->autoRender = false;
		$this->loadModel("Import");
		$id = $this->desencriptarCadena($uid);
		if (!$this->Import->exists($id)) {
			throw new NotFoundException(__('Invalid import request'));
		}

		$this->Import->save([
			"payed" => 1,
			"modified" => date("Y-m-d H:i:s"),
			"id" => $id
		]);

		$this->Session->setFlash(__('La solicitud fue cambiada correctamente'),'Flash/success');
		$this->redirect(["controller"=>"prospective_users","action" => "compras_nacionales" ]);
	}

	public function generatePdfImport($uid){
		$this->autoRender = false;
		$id = $this->desencriptarCadena($uid);
		if (!$this->ImportRequest->exists($id)) {
			throw new NotFoundException(__('Invalid import request'));
		}

		$options = array('conditions' => array('ImportRequest.' . $this->ImportRequest->primaryKey => $id));
		$importRequest = $this->ImportRequest->find('first', $options);

		if(!empty($this->request->data["emails"])){
			$emails = explode(",", $this->request->data["emails"]);
		}

		$importData = $this->ImportRequest->Import->findById($importRequest["Import"]["id"]);

		$productForBrand = end($importData["Product"]);

		$currency = $productForBrand["ImportProduct"]["currency"];

		if(!is_null($importRequest["Import"]["brand_id"])){
			$brandInfo = $this->ImportRequest->Import->Product->Brand->findById($importRequest["Import"]["brand_id"]);
		}else{
			$brandInfo = $this->ImportRequest->Import->Product->Brand->findById($productForBrand["brand_id"]);
		}


		$total = 0;

		foreach ($importData["Product"] as $key => $value) {
			if ($value["ImportProduct"]["quantity"] == 0){
				continue;
			}
			$total+=($value["ImportProduct"]["price"] * $value["ImportProduct"]["quantity"]);
		}

		$letras = null;

		$iva = $importData["Import"]["iva"] == 1 ? 1.19 : 1;

		if($currency == "cop"){
			$letras = CifrasEnLetras::convertirNumeroEnLetras(round(($total*$iva), 2));
			$this->set("letras",$letras);
		}

		$options 						= array(
			'template'	=> 'request_import',
			'ruta'		=> APP . 'webroot'.DS.'files'.DS.'requests'.DS.md5($id).".pdf",
			'vars'		=> array("import" => $importData, "brandInfo" => $brandInfo, "productForBrand" => $productForBrand, "currency" => $currency, "letras" => $letras),
		);

		$this->generatePdf($options);
		return Router::url("/",true).'files'.DS.'requests'.DS.md5($id).".pdf";
	}

	public function send_client_data($id){

		$this->layout = false;
		$requestData = $this->ImportRequest->ImportRequestsDetail->findById($id);

		if ($this->request->is("post")) {

			$datosCliente = $this->getDataCustomer($requestData["ImportRequestsDetail"]["prospective_user_id"]);

			$datos_asesor = $this->ImportRequest->ImportRequestsDetail->User->get_data($requestData['ProspectiveUser']['user_id']);

			$emails 	  = [AuthComponent::user("email"),$datosCliente["email"],$datos_asesor["User"]["email"]];

			$texto 		  = $this->request->data["ImportRequestsDetail"]["texto"];

			$options = array(
				'to'		=> $emails,
				'template'	=> 'informe_customer',
				'subject'	=> 'Te informamos de una anomalía en la importación de productos - KEBCO AlmacenDelPintor.com',
				'vars'		=> compact("datosCliente","datos_asesor","requestData","texto"),
			);			

			$this->loadModel("ImportRequestsDetail");
			if($this->ImportRequestsDetail->save($this->request->data)){
				$this->sendMail($options, true);
			}
		}

		$this->set(compact("id","requestData"));
	}

	public function generatePdfImportDetail($uid){
		$this->autoRender = false;
		$id = $this->desencriptarCadena($uid);
		if (!$this->ImportRequest->exists($id)) {
			throw new NotFoundException(__('Invalid import request'));
		}
		$options = array('conditions' => array('ImportRequest.' . $this->ImportRequest->primaryKey => $id));
		$importRequest = $this->ImportRequest->find('first', $options);

		if(!empty($this->request->data["emails"])){
			$emails = explode(",", $this->request->data["emails"]);
		}

		$importData 	= $this->ImportRequest->Import->findById($importRequest["Import"]["id"]);

		$importaciones 	= $this->ImportRequest->Import->ImportProduct->products_import($importRequest["Import"]["id"]);


		if(!empty($importaciones)){
			$this->loadModel("ImportProductsDetail");
			$this->ImportProductsDetail->unBindModel(array("belongsTo" => array("Product","Import")));
			foreach ($importaciones as $key => $value) {
				$detalleImportaciones = $this->ImportProductsDetail->findAllByProductIdAndImportId($value["Product"]["id"],$value["Import"]["id"]);
				$importaciones[$key]["Product"]["details"] = $detalleImportaciones;
			}
		}

		$productForBrand = end($importData["Product"]);
		$currency 		 = $productForBrand["ImportProduct"]["currency"];

		if(!is_null($importRequest["Import"]["brand_id"])){
			$brandInfo = $this->ImportRequest->Import->Product->Brand->findById($importRequest["Import"]["brand_id"]);
		}else{
			$brandInfo = $this->ImportRequest->Import->Product->Brand->findById($productForBrand["brand_id"]);
		}

		$total = 0;

		foreach ($importData["Product"] as $key => $value) {
			if ($value["ImportProduct"]["quantity"] == 0){
				continue;
			}
			$total+=($value["ImportProduct"]["price"] * $value["ImportProduct"]["quantity"]);
		}

		$letras = null;

		$iva = $importData["Import"]["iva"] == 1 ? 1.19 : 1;

		if($currency == "cop"){
			$letras = CifrasEnLetras::convertirNumeroEnLetras(round(($total*$iva), 2));
			$this->set("letras",$letras);
		}

		$options 						= array(
			'template'	=> 'request_import_details',
			'ruta'		=> APP . 'webroot'.DS.'files'.DS.'requests_details'.DS.md5($id).".pdf",
			'vars'		=> array("import" => $importData, "brandInfo" => $brandInfo, "productForBrand" => $productForBrand,"importaciones"=>$importaciones,"currency" => $currency, "letras" => $letras),
		);

		$this->generatePdf($options);
		return Router::url("/",true).'files'.DS.'requests_details'.DS.md5($id).".pdf";
	}

	public function config(){

		if (AuthComponent::user("email") != "jotsuar@gmail.com") {
			$this->redirect(["controller"=>"prospective_users","action"=>"index"]);
		}

		$this->loadModel("Config");
		$this->loadModel("Trm");
		$this->loadModel("Quotation");
		$this->loadModel("Note");
		$this->loadModel("User");

		if($this->request->is("post")){
			$this->request->data["Config"]["id"] = 1;

			$this->request->data["Config"]["roles_add"] 	= implode(",", $this->request->data["Config"]["roles_add"]);
			$this->request->data["Config"]["roles_edit"] 	= implode(",", $this->request->data["Config"]["roles_edit"]);
			$this->request->data["Config"]["roles_unlock"] 	= implode(",", $this->request->data["Config"]["roles_unlock"]);

			$this->request->data["Config"]["users_money"] 	= implode(",", $this->request->data["Config"]["users_money"]);

			$this->request->data["Config"]["users_validate"] 	= implode(",", $this->request->data["Config"]["users_validate"]);
			$this->request->data["Config"]["users_request"] 	= implode(",", $this->request->data["Config"]["users_request"]);
			$this->request->data["Config"]["users_external"] 	= implode(",", $this->request->data["Config"]["users_external"]);
			$this->request->data["Config"]["users_bog"] 	= implode(",", $this->request->data["Config"]["users_bog"]);
			$this->request->data["Config"]["users_med"] 	= implode(",", $this->request->data["Config"]["users_med"]);
			$this->request->data["Config"]["users_blogs"] 	= implode(",", $this->request->data["Config"]["users_blogs"]);
			$this->request->data["Config"]["users_verify_blogs"] 	= implode(",", $this->request->data["Config"]["users_verify_blogs"]);

			$productos = null;

			if (!empty($productos)) {
				foreach ($productos as $key => $value) {
					unset($productos[$key]["Product"]);
				}
				$this->request->data["Config"]["products"] = null;
				$this->Session->write("PRODUCTOS",[]);
			}else{
				$this->request->data["Config"]["products"] = null;
					
			}

			$this->Config->save($this->request->data);
			$this->Session->setFlash(__('Los datos fueron guardados correctamente.'),'Flash/success');	
		}else{
			$this->Session->write("PRODUCTOS",[]);
		}

		$config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"];
        $factorImport   = $config["Config"]["factorUSA"];
        $bloqueoMargen  = $config["Config"]["bloqueoMargen"];
        $ajusteTrm   	= $config["Config"]["ajusteTrm"];
        $ivaCol   		= $config["Config"]["ivaCol"];
        $origin   		= $config["Config"]["origin"];
        $reason   		= $config["Config"]["reason"];
        $textClient   	= $config["Config"]["textClient"];
        $subject   		= $config["Config"]["subject"];
        $descriptive_id = $config["Config"]["descriptive_id"];
        $conditions_id  = $config["Config"]["conditions_id"];
        $header_id   	= $config["Config"]["header_id"];
        $template_id   	= $config["Config"]["template_id"];
        $days_resend    = $config["Config"]["days_resend"];
        $txt_resend   	= $config["Config"]["txt_resend"];
        $msg_redend   	= $config["Config"]["msg_redend"];
        $time_factura    	= $config["Config"]["time_factura"];

        $user_remarketing   = $config["Config"]["user_remarketing"];
        $days_remarketing   = $config["Config"]["days_remarketing"];
        $days_prorroga   	= $config["Config"]["days_prorroga"];
        $max_prorroga   	= $config["Config"]["max_prorroga"];


        $users_money   	= explode(",", $config["Config"]["users_money"]);
        $users_validate = explode(",", $config["Config"]["users_validate"]);
        $users_request  = explode(",", $config["Config"]["users_request"]);
        $users_external  = explode(",", $config["Config"]["users_external"]);
        $users_shipping = explode(",", $config["Config"]["users_shipping"]);
        $users_billing  = explode(",", $config["Config"]["users_billing"]);
        $users_med  	= explode(",", $config["Config"]["users_med"]);
        $users_blogs  	= explode(",", $config["Config"]["users_blogs"]);
        $users_verify_blogs  	= explode(",", $config["Config"]["users_verify_blogs"]);
        $users_bog  	= explode(",", $config["Config"]["users_bog"]);
        $users_shipping_bog = explode(",", $config["Config"]["users_shipping_bog"]);
        $users_billing_bog  = explode(",", $config["Config"]["users_billing_bog"]);
        $roles_add   	= explode(",", $config["Config"]["roles_add"]);
        $roles_edit   	= explode(",", $config["Config"]["roles_edit"]);
        $roles_unlock   = explode(",", $config["Config"]["roles_unlock"]);
        $headers 		= $this->Quotation->Header->get_headers_list();
        $notas_previas 	= $this->Note->data_notes_previas();
        $formas_pago 	= $this->Note->data_conditions_negocio();
        $notas_descriptivas = $this->Note->data_notes_descriptiva();

        if (!empty($config["Config"]["products"])) {
        	$products = $this->object_to_array(json_decode($config["Config"]["products"]));
        	$this->loadModel("Product");
        	foreach ($products as $key => $value) {
        		$this->Product->recursive = -1;
        		$products[$key]["Product"] = $this->Product->findById($key)["Product"];
        	}
        	$this->Session->write("PRODUCTOS",$products);
        }

        $notasPrevias   = array();
        foreach ($notas_previas as $key => $value) {
        	$notasPrevias[$value["Note"]["id"]] = $value["Note"]["name"];
        }

        $conditions = array();
        foreach ($formas_pago as $key => $value) {
        	$conditions[$value["Note"]["id"]] = $value["Note"]["name"];
        }

        $descriptives = array();
        foreach ($notas_descriptivas as $key => $value) {
        	$descriptives[$value["Note"]["id"]] = $value["Note"]["name"];
        }

        $trms		    = $this->Trm->find("all",["limit" => 10,"order" => ["fecha_fin" => "DESC"]]);
        $usersRol	 	= $this->User->role_asesor_comercial_users_all_true(true);

        $users 		 	= array();
        foreach ($usersRol as $key => $value) {
        	$users[$value["User"]["id"]] = $value["User"]["name"];
        }

        $this->set(compact("trmActual","factorImport","bloqueoMargen","ajusteTrm","trms","origin","reason","textClient","subject","time_factura"));

        $this->set(compact("descriptive_id","conditions_id","header_id","headers","notasPrevias","conditions","template_id","descriptives","days_resend","txt_resend","msg_redend","ivaCol"));

        $this->set(compact("roles_add","roles_edit","roles_unlock","users_money","users","users_validate","users_request","days_prorroga","max_prorroga","days_shipping_normal","days_shipping_import","days_billing_normal","days_billing_import","user_remarketing","days_remarketing","users_med","users_bog","users_external","users_verify_blogs","users_blogs"));

	}

	

	public function get_relations(){
		$this->layout = false;

		$requests = $this->ImportRequest->ImportRequestsDetail->getOthersDetails($this->request->data["id"],$this->request->data["request"]);

		$this->set(compact("requests"));

	}

}

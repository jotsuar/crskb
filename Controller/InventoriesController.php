<?php
App::uses('AppController', 'Controller');

require_once '../Vendor/spreadsheet/vendor/autoload.php';


//include the classes needed to create and write .xlsx file
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class InventoriesController extends AppController {

	public $components = array('Paginator');

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('cron_inventory');
    }

	public function cron_inventory(){
		$this->autoRender = false;

		$this->loadModel("Product");
		$this->loadModel("Inventario");

		$firstDayUTS = mktime (0, 0, 0, date("m"), 1, date("Y"));
		$lastDayUTS = mktime (0, 0, 0, date("m"), date('t'), date("Y"));

		$firstDay = date("d-m-Y", $firstDayUTS);
		$lastDay = date("Y-m-d", $lastDayUTS);

		$nowData = date("Y-m-d");

		$last_day = $nowData == $lastDay ? 1 : 0;

		$products = $this->Product->find("all",["fields"=>["id","stock","purchase_price_usd", "purchase_price_cop","aditional_cop","aditional_usd"],"conditions" => ["state" => 1, "deleted"=>0], "recursive" => -1 ]);

		$inventarios = [];

		foreach ($products as $key => $value) {
			$avgCosts = $this->Inventory->find("first",["fields" => ["AVG(cost) total_cop","AVG(cost_usd) total_usd"],"conditions"=>["DATE(created)" => $nowData, "product_id" => $value["Product"]["id"] ], "recursive" => -1 ]);
			$inventarios[] = [ "Inventario" => [
					"product_id" => $value["Product"]["id"],
					"cost" => is_null($avgCosts[0]["total_cop"]) ? ($value["Product"]["purchase_price_cop"]+$value["Product"]["aditional_cop"]) : $avgCosts[0]["total_cop"],
					"cost_usd" => is_null($avgCosts[0]["total_usd"]) ? ($value["Product"]["purchase_price_usd"]+$value["Product"]["aditional_usd"]) : $avgCosts[0]["total_usd"],
					"stock" => $value["Product"]["stock"],
					"last_day" => $last_day,
					"fecha" => $nowData
				]
			];
		}

		$this->Inventario->saveMany($inventarios);
	}

	public function informe_general(){
		$this->loadModel("Inventario");
		$this->validateDatesForReports();

		$this->redirect(["controller" => "products", "action" => "index"]);

		$fechaIni = $this->request->query["ini"];
		$fechaEnd = $this->request->query["end"];

		$avgGeneral = ["0" => ["total_usd"=> 0, "total_cop" => 0]];

		$inventarios = $this->Inventario->find("all",["recursive" => 1, "fields" => ["AVG(Inventario.stock) inventario", "AVG(cost_usd) total_usd","AVG(cost) total_cop","Inventario.*","Product.*"], "conditions" => ["fecha >=" =>$fechaIni,"fecha <=" => $fechaEnd], "group" => ["Inventario.product_id"] ]);

		foreach ($inventarios as $key => $value) {
			$avgGeneral["0"]["total_usd"]+=$value["0"]["inventario"]*$value["0"]["total_usd"];
			$avgGeneral["0"]["total_cop"]+=$value["0"]["inventario"]*$value["0"]["total_cop"];
		}

		$this->set(compact("avgGeneral","inventarios"));
	}

	public function reports(){
		$this->validateDatesForReports();
	}

	public function inventory_report(){
		$this->autoRender = false;

		$ids = $this->Inventory->find("all",["fields" => ["Inventory.id","Inventory.inventory_id"], "conditions" => ["Inventory.inventory_id IS NOT NULL", "DATE(Inventory.created) >=" => $this->request->data["fecha_ini"], "DATE(Inventory.created) <=" => $this->request->data["fecha_end"], ]]);
		$finalIds = [];

		if(!empty($ids)){
			$idsSec 	= Set::extract($ids, "{n}.Inventory.id");
			$idsPpal 	= Set::extract($ids, "{n}.Inventory.inventory_id");
			$finalIds   = array_merge($idsSec,$idsPpal);
		}

		$conditions   	= [ "DATE(Inventory.created) >=" => $this->request->data["fecha_ini"], "DATE(Inventory.created) <=" => $this->request->data["fecha_end"] ];

		if(isset($this->request->data["product"])){
			$cond = $conditions;
			$cond["Inventory.type"] = 2;
			$cond["Inventory.type_movement"] = 4;
			$cond["Inventory.id != "] = $finalIds;

			$products = $this->Inventory->find("all",["conditions" => $cond, "fields" => ["Product.*","COUNT(Inventory.product_id) total","Inventory.product_id"], "group" => ["Inventory.product_id"], "order" => ["COUNT(Inventory.product_id)" => "DESC"], "limit" => 20 ]);

			if (!empty($products)) {
				$copy 		= $products;
				$products 	= [];
				foreach ($copy as $key => $value) {
					$products[] = [
						"número_de_parte" => $value["Product"]["part_number"],
						"nombre" => $value["Product"]["name"],
						"total_vendido" => $value["0"]["total"]
					];
				}
			}
			return json_encode($products);
		}

		$conditionsIn 	= $conditions;
		$conditionsOut 	= $conditions;

		$conditionsIn["Inventory.type"] 	= 1;
		$conditionsIn["Inventory.id != "] = $finalIds;
		$allIn = $this->Inventory->find("all",["conditions" => $conditionsIn, "recursive" => -1 ]);

		$conditionsOut["Inventory.type"] = 2;
		$conditionsOut["Inventory.id != "] = $finalIds;
		$allOut = $this->Inventory->find("all",["conditions" => $conditionsOut, "recursive" => -1 ]);


		$totalEntradasTipos 	= [];
		$totalSalidasTipos 		= [];
		$totalEntradasBodegas 	= [];
		$totalSalidasBodegas 	= [];

		foreach ($allIn as $key => $value) {
			if(!isset($totalEntradasTipos[Configure::read("INVENTORY_TYPE_REASON.".$value["Inventory"]["type_movement"])] )){
				$totalEntradasTipos[Configure::read("INVENTORY_TYPE_REASON.".$value["Inventory"]["type_movement"])] = 1;
			}else{
				$totalEntradasTipos[Configure::read("INVENTORY_TYPE_REASON.".$value["Inventory"]["type_movement"])]+= 1;
			}

			if(!isset($totalEntradasBodegas[$value["Inventory"]["warehouse"]] )){
				$totalEntradasBodegas[$value["Inventory"]["warehouse"]] = 1;
			}else{
				$totalEntradasBodegas[$value["Inventory"]["warehouse"]]+= 1;
			}
		}

		$totalEntradasBodegasCopy = $totalEntradasBodegas;
		$totalEntradasBodegas     = [];

		foreach ($totalEntradasBodegasCopy as $key => $value) {
			$datos 			= new StdClass();
	        $datos->name 	= $key;
	        $datos->y 		= intval($value);
	        $totalEntradasBodegas[] = $datos;
		}

		$totalEntradasTiposCopy = $totalEntradasTipos;
		$totalEntradasTipos     = [];

		foreach ($totalEntradasTiposCopy as $key => $value) {
			$datos 			= new StdClass();
	        $datos->name 	= $key;
	        $datos->y 		= intval($value);
	        $totalEntradasTipos[] = $datos;
		}

		foreach ($allOut as $key => $value) {
			if(!isset($totalSalidasTipos[Configure::read("INVENTORY_TYPE_REASON.".$value["Inventory"]["type_movement"])] )){
				$totalSalidasTipos[Configure::read("INVENTORY_TYPE_REASON.".$value["Inventory"]["type_movement"])] = 1;
			}else{
				$totalSalidasTipos[Configure::read("INVENTORY_TYPE_REASON.".$value["Inventory"]["type_movement"])]+= 1;
			}

			if(!isset($totalSalidasBodegas[$value["Inventory"]["warehouse"]] )){
				$totalSalidasBodegas[$value["Inventory"]["warehouse"]] = 1;
			}else{
				$totalSalidasBodegas[$value["Inventory"]["warehouse"]]+= 1;
			}
		}

		$totalSalidasTiposCopy = $totalSalidasTipos;
		$totalSalidasTipos     = [];

		foreach ($totalSalidasTiposCopy as $key => $value) {
			$datos 			= new StdClass();
	        $datos->name 	= $key;
	        $datos->y 		= intval($value);
	        $totalSalidasTipos[] = $datos;
		}

		$totalSalidasBodegasCopy = $totalSalidasBodegas;
		$totalSalidasBodegas     = [];

		foreach ($totalSalidasBodegasCopy as $key => $value) {
			$datos 			= new StdClass();
	        $datos->name 	= $key;
	        $datos->y 		= intval($value);
	        $totalSalidasBodegas[] = $datos;
		}


		$general  = $this->getGeneral(["total_traslado" => count($finalIds), "total_entrada" => count($allIn), "total_salida" => count($allOut)]);
		$response = [ "general" => $general, "tipos_entrada" => $totalEntradasTipos, "bodegas_entrada" => $totalEntradasBodegas, "tipos_salida" => $totalSalidasTipos, "bodegas_salida" => $totalSalidasBodegas ];

		return json_encode($response);
	}

	public function getGeneral($response){
		$general = [];
		$datos 			= new StdClass();
        $datos->name 	= "Traslado";
        $datos->y 		= intval($response["total_traslado"]);
        $general[]		= $datos;

        $datos 			= new StdClass();
        $datos->name 	= "Entrada";
        $datos->y 		= intval($response["total_entrada"]);
        $general[]		= $datos;

        $datos 			= new StdClass();
        $datos->name 	= "Salida";
        $datos->y 		= intval($response["total_salida"]);
        $general[]		= $datos;

        return $general;
	}

	public function salidas(){
		if(AuthComponent::user("role") != "Gerente General"){
			return $this->redirect(array("controller" => "products","action" => "index"));
		}
		$products = $this->Inventory->find("all",["conditions" => ["Inventory.state" => 2,"Inventory.type" => 2, "Inventory.packnumber IS NULL"], "order" => ["Inventory.created" => "DESC"]]);
		
		$productsGroup = $this->Inventory->find("all",["conditions" => ["Inventory.state" => 2,"Inventory.type" => 2, "Inventory.packnumber IS NOT NULL"], "order" => ["Inventory.created" => "DESC"], "fields" => ["DISTINCT Inventory.packnumber", "Inventory.reason", "Inventory.created", "Inventory.user_id" ] ]);


		$unlokProducts = $this->validateUnlockProducts();
		// $categoriesData = $this->getCategoryInfo(true);
		$this->set(compact("products","unlokProducts","productsGroup"));
	}

	public function get_group_list(){
		$this->layout = false;
		$products = $this->Inventory->find("all",["conditions" => ["Inventory.type" => 2, "Inventory.packnumber" => $this->request->data["id"]], "order" => ["Inventory.created" => "DESC"]]);
		$this->set(compact("products"));
	}

	public function panel_movimientos(){
		$conditions 				= array();

		if(isset($this->request->query["tipoMovimiento"])){

			$ids = $this->Inventory->find("all",["fields" => ["Inventory.id","Inventory.inventory_id"], "conditions" => ["Inventory.inventory_id IS NOT NULL" ]]);
			if(!empty($ids)){
				$idsSec 	= Set::extract($ids, "{n}.Inventory.id");
				$idsPpal 	= Set::extract($ids, "{n}.Inventory.inventory_id");
				$finalIds   = array_merge($idsSec,$idsPpal);				
			}

			if($this->request->query["tipoMovimiento"] == "TR"){
				if(!empty($ids)){
					$conditions["Inventory.id"] = $finalIds;
				}
			}elseif($this->request->query["tipoMovimiento"] == "EN"){
				$conditions["Inventory.type"] = 1;
				$conditions["Inventory.id != "] = $finalIds;
			}elseif($this->request->query["tipoMovimiento"] == "RM"){
				$conditions["Inventory.type"] = 2;
				$conditions["Inventory.id != "] = $finalIds;
			}

			$this->set("queryData", $this->request->query["tipoMovimiento"]);
		}
		$this->Inventory->recursive = 1;
		$this->paginate 			= array('order' => array("Inventory.created" => "DESC"), 'limit' 		=> 23, 'conditions' 	=> $conditions);		
		$inventories 				= $this->paginate('Inventory');

		$this->set('inventories', $inventories);
	}

	public function export2($time = null){
		$this->autoRender = false;

		$order 		= array("quantity" => "DESC" );
		$recursive 	= 1;
		$fields		= array("Product.id","Product.part_number","Product.name","Inventory.quantity","Inventory.type","Inventory.type_movement", "Inventory.prospective_user_id", "User.name", "Inventory.reason", "Inventory.created","Import.code_import","Inventory.warehouse");

		$inventories = $this->Inventory->find("all",compact("order","recursive","fields"));
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$spreadsheet->getProperties()->setCreator('Kebco SAS')
			        ->setLastModifiedBy('Kebco SAS')
			        ->setTitle('Detalle de inventario CRM')
			        ->setSubject('Detalle de inventario CRM')
			        ->setDescription('Detalle de inventario de productos para el CRM')
			        ->setKeywords('Detalle de inventario CRM Productos')
			        ->setCategory('Inventario');

		// Add some data
		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'REFERENCIA')
			        ->setCellValue('B1', 'PRODUCTO')
			        ->setCellValue('C1', 'CANTIDAD')
			        ->setCellValue('D1', 'TIPO MOVIMIENTO')
			        ->setCellValue('E1', 'CONDEPTO')
			        ->setCellValue('F1', 'RAZÓN')
			        ->setCellValue('G1', 'FLUJO')
			        ->setCellValue('H1', 'IMPORTACION')
			        ->setCellValue('I1', 'FECHA')
			        ->setCellValue('J1', 'USUARIO')
			        ->setCellValue('K1', 'BODEGA');

		$i = 2;

		foreach ($inventories as $key => $value) {
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $value["Product"]["part_number"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["Product"]["name"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $value["Inventory"]["quantity"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, Configure::read("TYPES_MOVEMENT_TEXT.".$value["Inventory"]["type"]));
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, Configure::read("INVENTORY_TYPE_REASON.".$value["Inventory"]["type_movement"]));
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $value["Inventory"]["reason"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $value["Inventory"]["prospective_user_id"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $value["Import"]["code_import"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $value["Inventory"]["created"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, $value["User"]["name"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $value["Inventory"]["warehouse"]);
			$i++;
		}

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

		$spreadsheet->getActiveSheet()->setTitle('Inventario');
		$spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		header('Content-Disposition: attachment;filename="detalle_inventario_kebco._'.time().'.xlsx"');
		header('Content-Type: application/vnd.ms-excel');
		header('Cache-Control: max-age=0');

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}


	public function index($productId = null) {

		$this->validateRoleImports();
		if(is_null($productId)){
			return $this->redirect(array("controller" => "products","action" => "index"));
		}

		$productId  					= $this->desencriptarCadena($productId);


		$conditions						= array("Inventory.product_id" => $productId);
		$this->getProduct($productId);

		$this->Inventory->recursive = 1;
		$this->paginate 			= array('order' => array("Inventory.created"), 'limit' 		=> 23, 'conditions' 	=> $conditions);		
		$inventories 				= $this->paginate('Inventory');

		$this->set('inventories', $inventories);
		$this->set(compact("productId"));

	}

	public function import(){
		$this->autoRender = false;
		$fxls ='inventarioMarzo.xlsx';
		$spreadsheet 	= \PhpOffice\PhpSpreadsheet\IOFactory::load($fxls);
		$xls_data 		= $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		$referencesNoExistis = array();
		$productsNumber = 0;

				// var_dump($xls_data);
	
		if (!empty($xls_data)) {
			if($xls_data[1]["A"] == "Descripcion" && $xls_data[1]["B"] == "Referencia " && $xls_data[1]["C"] == "Existencias" &&	$xls_data[1]["D"] == "transito " 
			){
			// 	$xls_data["96"]["G"] = str_replace(",", "", $xls_data["96"]["G"]);
			// 	var_dump($xls_data);
			// die();
				unset($xls_data[1]);

				foreach ($xls_data as $key => $value) {
					if(!empty($value["B"]) && !is_null($value["B"])){
						$this->Inventory->Product->recursive = -1;
						$product = $this->Inventory->Product->findByPartNumber($value["B"]);

						if(!empty($product)){

							$value["D"] = is_null($value["D"]) ? 0 : $value["D"];
							$productID 									= $product["Product"]["id"];
							$product["Product"]["quantity"] 			= intval($value["B"]);
							$product["Product"]["stock"] 				= intval($value["B"]);
							$product["Product"]["quantity_back"] 		= intval($value["D"]);

							$this->Inventory->Product->save($product);


							if(intval($value["C"]) > 0){
								$inventory = array(
						    		"Inventory" => array(
						    			"product_id" 	=> $productID,
						    			"quantity" 		=> intval($value["C"]),
						    			"warehouse"		=> "Medellín",
						    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
						    			"type_movement"	=> Configure::read("INVENTORY_TYPE.CARGA_INICIAL"),
						    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.CARGA_INICIAL")),
						    			"user_id"		=> AuthComponent::user("id"),
						    		)
						    	);

						    	$this->Inventory->create();
						    	$this->Inventory->save($inventory);
							}


						    if(intval($value["D"]) > 0){
						    	$inventory = array(
						    		"Inventory" => array(
						    			"product_id" 	=> $productID,
						    			"warehouse"		=> "Transito",
						    			"quantity" 		=> intval($value["D"]),
						    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
						    			"type_movement"	=> Configure::read("INVENTORY_TYPE.CARGA_INICIAL"),
						    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.CARGA_INICIAL")),
						    			"user_id"		=> AuthComponent::user("id"),
						    		)
						    	);

						    	$this->Inventory->create();
						    	$this->Inventory->save($inventory);
						    }
					    	
						    $productsNumber++;
						}
					}else{
						$referencesNoExistis[] = $value["B"];
					}
				}

			}else{
				echo "El formato no es correcto";
				die();
			}

			
		}

		
		echo "<pre>";
		print_r($referencesNoExistis);
		die();
	}

	public function view($id = null) {
		if (!$this->Inventory->exists($id)) {
			throw new NotFoundException(__('Invalid inventory'));
		}
		$options = array('conditions' => array('Inventory.' . $this->Inventory->primaryKey => $id));
		$this->set('inventory', $this->Inventory->find('first', $options));
	}

	private function getProduct($productId){
		
		$this->Inventory->Product->recursive = -1;
		$productData =  $this->Inventory->Product->findById($productId);

		if (empty($productData)) {
			throw new NotFoundException('El producto esta invalido');
		}else{
			$this->loadModel("Inventory");

			$totalEntMed = $this->Inventory->field("SUM(quantity)",["product_id" => $productId, "state" => 1, "type" => 1, "warehouse" => "Medellin"]);
			$totalSalMed = $this->Inventory->field("SUM(quantity)",["product_id" => $productId, "state" => 1, "type" => 2, "warehouse" => "Medellin"]);

			$totalEntBog = $this->Inventory->field("SUM(quantity)",["product_id" => $productId, "state" => 1, "type" => 1, "warehouse" => "Bogota"]);
			$totalSalBog = $this->Inventory->field("SUM(quantity)",["product_id" => $productId, "state" => 1, "type" => 2, "warehouse" => "Bogota"]);

			$totalEntTrn = $this->Inventory->field("SUM(quantity)",["product_id" => $productId, "state" => 1, "type" => 1, "warehouse" => "Transito"]);
			$totalSalTrn = $this->Inventory->field("SUM(quantity)",["product_id" => $productId, "state" => 1, "type" => 2, "warehouse" => "Transito"]);

			$productData["Product"]["quantity"] 	 	= $totalEntMed - $totalSalMed;
			$productData["Product"]["quantity_bog"] 	= $totalEntBog - $totalSalBog;
			$productData["Product"]["quantity_back"] 	= $totalEntTrn - $totalSalTrn;

			$this->set("product",$productData);
		}

		$this->Session->write("productId",$productId);
		return $productData;
	}

	public function saveMovements(){
		$this->autoRender = false;
		$empaque = null;
		if(isset($this->request->data["productos"]) && !empty($this->request->data["productos"])){
			if (isset($this->request->data["empaque"]) && !empty($this->request->data["empaque"]) ) {
				if($this->request->data["empaque"] == "SI"){
					$empaque = time();
				}
			}
			foreach ($this->request->data["productos"] as $key => $value) {
				if($value["type_movement"] == "TR"){
					$this->Inventory->Product->recursive = -1;
					$producto = $this->Inventory->Product->findById($value["productoId"]);
					switch ($value["bodegaSalidaTraslado"]) {
						case 'Medellín':
							$producto["Product"]["quantity"] = $producto["Product"]["quantity"] - $value["CantidadSalidaTraslado"];
							break;
						case 'Bogotá':
							$producto["Product"]["quantity_bog"] = $producto["Product"]["quantity_bog"] - $value["CantidadSalidaTraslado"];
							break;
					}
					switch ($value["bodegaEntradaTraslado"]) {
						case 'Medellín':
							$producto["Product"]["quantity"] = $producto["Product"]["quantity"] + $value["CantidadSalidaTraslado"];
							break;
						case 'Bogotá':
							$producto["Product"]["quantity_bog"] = $producto["Product"]["quantity_bog"] + $value["CantidadSalidaTraslado"];
							break;
					}
					$this->Inventory->Product->save($producto);
					$this->saveMovementInventory($value,$empaque);
				}elseif ($value["type_movement"] == "ADD") {
					$this->Inventory->Product->recursive = -1;
					$producto = $this->Inventory->Product->findById($value["productoId"]);
					switch ($value["bodegaEntrada"]) {
						case 'Medellín':
							$producto["Product"]["quantity"] = $producto["Product"]["quantity"] + $value["CantidadEntrada"];
							break;
						case 'Bogotá':
							$producto["Product"]["quantity_bog"] = $producto["Product"]["quantity_bog"] + $value["CantidadEntrada"];
							break;
					}
					$this->Inventory->Product->save($producto);
					$this->saveMovementInventory($value,$empaque);
				}elseif ($value["type_movement"] == "RM") {
					$this->Inventory->Product->recursive = -1;
					$this->saveMovementInventory($value,$empaque);
				}
			}
			$users = $this->Inventory->User->role_gerencia_user();
			$emails = [];
			foreach ($users as $key => $value) {
				$emails[] = $value["User"]["email"];
			}

			$subject = "Movimientos de inventario ".date("Y-m-d H:i:s")." KEBCO AlmacenDelPintor.com";

			$products = $this->request->data["productos"];
			$options = array(
				'to'		=> $emails,
				'template'	=> 'inventory_movements',
				'subject'	=> $subject,
				'vars'		=> compact("products")
			);
			$this->sendMail($options);
		}
	}

	private function saveMovementInventory($movement, $empaque = null){
		$datos = array(
			"Inventory" => array(
				"product_id" => $movement["productoId"],
				"reason" 	 => $movement["razonMovimiento"],
				"user_id"	 => AuthComponent::user("id"),
				"packnumber" => $empaque
			) 
		);
		if ($movement["type_movement"] == "TR") {
			$datos["Inventory"]["quantity"] 		= $movement["CantidadSalidaTraslado"];
			$datos["Inventory"]["type"] 			= 2;
			$datos["Inventory"]["type_movement"] 	= Configure::read("INVENTORY_TYPE.SALIDA_MANUAL");
			$datos["Inventory"]["warehouse"] 		= $movement["bodegaSalidaTraslado"];
			$this->Inventory->create();
			$this->Inventory->save($datos);
			$inventoryId = $this->Inventory->id;

			$datos["Inventory"]["type"] 			= 1;
			$datos["Inventory"]["type_movement"] 	= Configure::read("INVENTORY_TYPE.ENTRADA_MANUAL");
			$datos["Inventory"]["warehouse"] 		= $movement["bodegaEntradaTraslado"];
			$datos["Inventory"]["inventory_id"] 	= $inventoryId;
			$this->Inventory->create();
			$this->Inventory->save($datos);

		}elseif ($movement["type_movement"] == "ADD") {

			$datos["Inventory"]["quantity"] 		= $movement["CantidadEntrada"];
			$datos["Inventory"]["type"] 			= 1;
			$datos["Inventory"]["type_movement"] 	= Configure::read("INVENTORY_TYPE.ENTRADA_MANUAL");
			$datos["Inventory"]["warehouse"] 		= $movement["bodegaEntrada"];
			$this->Inventory->create();
			$this->Inventory->save($datos);

		}elseif ($movement["type_movement"] == "RM") {

			$datos["Inventory"]["quantity"] 		= $movement["CantidadSalida"];
			$datos["Inventory"]["type"] 			= 2;
			$datos["Inventory"]["type_movement"] 	= Configure::read("INVENTORY_TYPE.SALIDA_MANUAL");
			$datos["Inventory"]["warehouse"] 		= $movement["bodegaSalida"];
			$datos["Inventory"]["state"] 			= 2;
			$this->Inventory->create();
			$this->Inventory->save($datos);	
		}
	}


	public function add($productId) {

		$this->validateRoleImports();

		$id   = $this->desencriptarCadena($productId);

		$this->getProduct($id);

		if ($this->request->is('post')) {


			$this->Inventory->create();
			$this->request->data["Inventory"]["type_movement"] = $this->request->data["Inventory"]["type"] == Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO") ? Configure::read("INVENTORY_TYPE.ENTRADA_MANUAL") : Configure::read("INVENTORY_TYPE.SALIDA_MANUAL");

			if($this->request->data["Inventory"]["type"] == Configure::read("TYPES_MOVEMENT.SALIDA_INVENTARIO")){
				$this->request->data["Inventory"]["state"] = 2; 
			}
			
			$this->sendInformationToBoss($this->getProduct($id),$this->request->data);
			
			if ($this->Inventory->save($this->request->data)) {
				$this->Session->setFlash(__('Registro guardado correctamente.'),'Flash/success');
				return $this->redirect(array('action' => 'index',$productId));
			} else {
				$this->Flash->error(__('No fue posible guardar el registro.'));
			}
		}else{
			$this->request->data["Inventory"]["product_id"] 	= $id;
			$this->request->data["Inventory"]["user_id"] 		= AuthComponent::user("id");
		}
		$this->set(compact('products', 'users', 'prospectiveUsers', 'imports'));
	}

	private function sendInformationToBoss($product, $inventoryData){

		$users = $this->Inventory->User->role_gerencia_user();

		foreach ($users as $key => $value) {
			$emails[] = $value["User"]["email"];
		}

		if($inventoryData["Inventory"]["type"] == Configure::read("TYPES_MOVEMENT.SALIDA_INVENTARIO")){
			$subject = "Salida de inventario ".$product["Product"]["part_number"]." solicitud de aprobación KEBCO AlmacenDelPintor.com";
		}else{
			$subject = "Entrada de inventario ".$product["Product"]["part_number"]." KEBCO AlmacenDelPintor.com";

			$this->Inventory->Product->recursive = -1;

			$productChange = $this->Inventory->Product->findById($product["Product"]["id"]);

			$quantityFile = "quantity";

			switch ($inventoryData["Inventory"]["warehouse"]) {
				case 'Medellín':
					$quantityFile = "quantity";
					break;
				case 'Bogotá':
					$quantityFile = "quantity_bog";
					break;
				case 'ST Medellín':
					$quantityFile = "quantity_stm";
					break;
				case 'ST Bogotá':
					$quantityFile = "quantity_stb";
					break;
			}

			$productChange["Product"][$quantityFile] = intval($productChange["Product"][$quantityFile]) + intval($inventoryData["Inventory"]["quantity"]);

			$this->Inventory->Product->save($productChange);
		}



		$options = array(
			'to'		=> $emails,
			'template'	=> 'inventory_manual_movement',
			'subject'	=> $subject,
			'vars'		=> compact("product", "inventoryData")
		);
		
		$this->sendMail($options);

	}

	public function approved(){
		$this->autoRender = false;
		$inventory = $this->Inventory->findById($this->request->data["id"]);

		$inventory["Inventory"]["state"] = 1;
		$this->Inventory->id = $inventory["Inventory"]["id"];
		$this->Inventory->save(["Inventory"=> $inventory["Inventory"]]);

		$inventory["Product"]["quantity"] = intval($inventory["Product"]["quantity"]) - $inventory["Inventory"]["quantity"];
		$inventory["Product"]["modified"] = date("Y-m-d H:i:s");

		$this->Inventory->Product->id = $inventory["Product"]["id"];
		$this->Inventory->Product->save(["Product" => $inventory["Product"]]); 

	}

	public function noAprovee(){
		$this->autoRender = false;
		$inventory = $this->Inventory->findById($this->request->data["id"]);
		$inventory["Inventory"]["state"] = 4;
		$inventory["Inventory"]["reason_reject"] = $this->request->data["razon"];
		$inventory["Product"]["modified"] = date("Y-m-d H:i:s");
		$this->Inventory->id = $inventory["Inventory"]["id"];
		$this->Inventory->save(["Inventory"=> $inventory["Inventory"]]);

		$product = $inventory["Product"];
		$razon = $this->request->data["razon"];
		$inventoryData = $inventory;

		$options = array(
			'to'		=> [$inventory["User"]["email"]],
			'template'	=> 'inventory_reject',
			'subject'	=> "Rechazo de salida referencia - ".$inventory["Product"]["part_number"],
			'vars'		=> compact("product", "razon","inventoryData")
		);
		
		$this->sendMail($options);
		$this->Session->setFlash('Rechazo correctamente','Flash/success');
	}

	public function movements(){

		$this->redirect(["controller" => "products", "action" => "index"]);

		$this->loadModel("ProductsLock");
		$totalBloqueo = $this->ProductsLock->find("count",["conditions" => ["ProductsLock.state" => 1]]);
		$this->set(compact("totalBloqueo"));

		if(isset($this->request->query["type"]) && in_array($this->request->query["type"], ["EN","RM","TR"])){
			$type = $this->request->query["type"];
			$this->set("type",$type);
		}

	}

	public function unlock(){

		$this->autoRender = false;


		$this->unlokData($this->request->data["id"]);
		$this->Session->setFlash('Inventario desbloqueado correctamente','Flash/success');

		
	}

	private function unlokData($id){
		$this->loadModel("ProductsLock");
		$this->loadModel("User");
		$this->loadModel("ProspectiveUser");
		$this->loadModel("ProgresNote");
		$this->loadModel("ProgresNote");
		$this->loadModel("QuotationsProduct");

		$lock = $this->ProductsLock->findById($id);		
		$lock["ProductsLock"]["state"] 			= 3;
        $lock["ProductsLock"]["unlock_date"] 	= date("Y-m-d H:i:s");

        $cantidadTotal 		= $lock["ProductsLock"]["quantity"]+$lock["ProductsLock"]["quantity_bog"];
        $cantidadTotalBack 	= $lock["ProductsLock"]["quantity_back"];

        $id_etapa_cotizado 			= $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($lock["ProductsLock"]["prospective_user_id"]);
		$datosFlowstage 			= $this->ProspectiveUser->FlowStage->get_data($id_etapa_cotizado);

		$this->QuotationsProduct->recursive = -1;
		$producto = $this->QuotationsProduct->findByQuotationIdAndProductIdAndQuantityAndQuantityBack($datosFlowstage["FlowStage"]["document"],$lock["Product"]["id"],$cantidadTotal,$cantidadTotalBack);


		if (!empty($producto)) {
			$producto["QuotationsProduct"]["state"] = 0;
			$this->QuotationsProduct->save($producto);

			$stage = $this->ProspectiveUser->FlowStage->find("first",array("conditions" => array("state_flow" => "Pagado", "prospective_users_id" => $lock["ProductsLock"]["prospective_user_id"]),"order" => array("FlowStage.id" => "DESC"), "recursive" => -1 ));

			$stage["FlowStage"]["state"] = 7;
			$this->ProspectiveUser->FlowStage->save($stage);

			$progresNote['ProgresNote']['description'] 				= "Desbloqueo del producto ".$lock["Product"]["part_number"]." por parte de gerencia";
	        $progresNote['ProgresNote']['etapa'] 					= "Pagado";
	        $progresNote['ProgresNote']['prospective_users_id'] 	= $lock["ProductsLock"]["prospective_user_id"];
	        $progresNote['ProgresNote']['user_id'] 					= AuthComponent::user('id');
			$this->ProgresNote->save($progresNote);

		}

        $this->ProductsLock->save($lock["ProductsLock"]);

		$user = $this->User->findById($lock["ProspectiveUser"]["user_id"]);

		$subject = "Desbloqueo de inventario ".$lock["Product"]["part_number"]." KEBCO AlmacenDelPintor.com";

		$options = array(
			'to'		=> [$user["User"]["email"]],
			'template'	=> 'unlock',
			'subject'	=> $subject,
			'vars'		=> ["datos" => $lock]
		);
		
		$this->sendMail($options);
	}

	public function blocks(){

		$this->loadModel("ProductsLock");
		$conditions						= array("ProductsLock.state" => 1);

		if(isset($this->request->query["q"]) && !empty($this->request->query["q"])){
			$conditions["OR"] = [ 'LOWER(Product.part_number) LIKE' => '%'. strtolower($this->request->query["q"]).'%', "ProductsLock.prospective_user_id" => $this->request->query["q"] ];
		}

		$this->ProductsLock->recursive = 1;
		$this->paginate 			= array('order' => array("ProductsLock.created"), 'limit' 		=> 20, 'conditions' 	=> $conditions);		
		$blocks 				= $this->paginate('ProductsLock');
		$this->set(compact("blocks"));
	}

	public function create_movement(){
		$this->layout = false;
		$this->Inventory->Product->recursive = -1;
		$producto = $this->Inventory->Product->findById($this->request->data["id"]);
		$this->set("type",isset($this->request->data["type_move"]) ? $this->request->data["type_move"] : null);
		$this->set(compact("producto"));
	}

	public function create_movement_traslate(){
		$this->layout = false;
		$this->loadModel("ProductsLock");
		$producto = $this->ProductsLock->findById($this->request->data["id"]);
		$this->set(compact("producto"));
	}

	public function saveMovementsLock(){
		$this->autoRender = false;
		$this->loadModel("ProductsLock");
		$this->ProductsLock->recursive = -1;
		$lock = $this->ProductsLock->findById($this->request->data["lockId"]);

		if($this->request->data["bodegaSalidaTraslado"] == "Medellín"){
			$lock["ProductsLock"]["quantity"]-= $this->request->data["cantidadLockSalida"];
		}elseif($this->request->data["bodegaSalidaTraslado"] == "Bogotá"){
			$lock["ProductsLock"]["quantity_bog"]-= $this->request->data["cantidadLockSalida"];
		}elseif($this->request->data["bodegaSalidaTraslado"] == "Transito"){
			$lock["ProductsLock"]["quantity_back"]-= $this->request->data["cantidadLockSalida"];
		}

		if($this->request->data["bodegaEntradaTraslado"] == "Medellín"){
			$lock["ProductsLock"]["quantity"]+= $this->request->data["cantidadLockSalida"];
		}elseif($this->request->data["bodegaEntradaTraslado"] == "Bogotá"){
			$lock["ProductsLock"]["quantity_bog"]+= $this->request->data["cantidadLockSalida"];
		}elseif($this->request->data["bodegaEntradaTraslado"] == "Transito"){
			$lock["ProductsLock"]["quantity_back"]+= $this->request->data["cantidadLockSalida"];
		}

		$this->ProductsLock->save($lock);
	}

	public function table_information(){
		$this->layout = false;
	}

}

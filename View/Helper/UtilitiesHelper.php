<?php

App::uses('HtmlHelper', 'View/Helper');

class UtilitiesHelper extends HtmlHelper {

	public $helpers = array('Html');

	public function UtilitiesHelper(){
		App::import("model","ClientsLegal");
		App::import("model","ContacsUser");
		App::import("model","ProspectiveUser");
		App::import("model","FlowStage");
		App::import("model","ClientsNatural");
		App::import("model","Quotation");
		App::import("model","User");
		App::import("model","Brand");
		App::import("model","Product");
		App::import("model","Template");
		App::import("model","FlowStagesProduct");
		App::import("model","QuotationsProduct");
		App::import("model","TechnicalService");
		App::import("model","Manage");
		App::import("model","Accessory");
		App::import("model","Import");
		App::import("model","ImportProduct");
		App::import("model","ImportProductsDetail");
		App::import("model","ImportRequestsDetail");
		App::import("model","ImportsBySale");
		App::import("model","ProgresNote");
		App::import("model","Salesinvoice");
		App::import("model","News");
		App::import("model","ProductsLock");
		App::import("model","Trm");
		App::import("model","Conveyor");
		App::import("model","Inventory");
		App::import("model","Approve");
		App::import("model","Category");
		App::import("model","ImportRequest");
		App::import("model","Order");
		App::import("model","Shipping");
		App::import("model","Garantia");

		$this->__ClientsLegal			= new ClientsLegal();
		$this->__ClientsNatural			= new ClientsNatural();
		$this->__ContacsUser			= new ContacsUser();
		$this->__ProspectiveUser		= new ProspectiveUser();
		$this->__FlowStage				= new FlowStage();
		$this->__Quotation				= new Quotation();
		$this->__User					= new User();
		$this->__Brand					= new Brand();
		$this->__Product				= new Product();
		$this->__Template				= new Template();
		$this->__FlowStagesProduct		= new FlowStagesProduct();
		$this->__QuotationsProduct		= new QuotationsProduct();
		$this->__TechnicalService		= new TechnicalService();
		$this->__Manage					= new Manage();
		$this->__Accessory				= new Accessory();
		$this->__Import					= new Import();
		$this->__ImportProduct 			= new ImportProduct();
		$this->__ImportProductsDetail 	= new ImportProductsDetail();
		$this->__ImportRequestsDetail 	= new ImportRequestsDetail();
		$this->__ImportsBySale 			= new ImportsBySale();
		$this->__ProgresNote			= new ProgresNote();
		$this->__News					= new News();
		$this->__Salesinvoice			= new Salesinvoice();
		$this->__ProductsLock			= new ProductsLock();
		$this->__Trm					= new Trm();
		$this->__Conveyor				= new Conveyor();
		$this->__Inventory				= new Inventory();
		$this->__Approve				= new Approve();
		$this->__Category				= new Category();
		$this->__Order					= new Order();
		$this->__Shipping				= new Shipping();
		$this->__Garantia				= new Garantia();
	}

	public function validateBultos(){
		
	}

	public function getIdNext(){
		return $this->__Import->field("MAX(purchase_order)")+1;
	}

	public function calcularTiempoPasado($minutos) {
	    $meses = floor($minutos / (30 * 24 * 60));
	    $minutos -= $meses * 30 * 24 * 60;

	    $semanas = floor($minutos / (7 * 24 * 60));
	    $minutos -= $semanas * 7 * 24 * 60;

	    $dias = floor($minutos / (24 * 60));
	    $minutos -= $dias * 24 * 60;

	    $horas = floor($minutos / 60);
	    $minutos -= $horas * 60;

	    $tiempoPasado = '';

	    if($dias > 0 || $semanas > 0 || $meses > 0 ){

		    if ($meses >= 1) {
		        $tiempoPasado .= "$meses meses";
		    }
		    if ($semanas >= 1) {
		        $tiempoPasado .= ($tiempoPasado ? ', ' : '') . "$semanas semanas";
		    }
		    if ($dias >= 1) {
		        $tiempoPasado .= ($tiempoPasado ? ', ' : '') . "$dias días";
		    }
		    if ($horas >= 1) {
		        $tiempoPasado .= ($tiempoPasado ? ', ' : '') . "$horas horas";
		    }
		    if ($minutos >= 1) {
		        $tiempoPasado .= ($tiempoPasado ? ' y ' : '') . "$minutos minutos";
		    }

	    }else{
	    	 if ($horas >= 1) {
		        $tiempoPasado .= ($tiempoPasado ? '' : '') . "$horas";
		    }
		    if ($minutos >= 1) {
		        if($minutos < 10){
		            $minutos = "0".$minutos;
		        }
		        $tiempoPasado .= ($tiempoPasado ? ':' : '') . "$minutos";
		    }
	    }

	    return ($tiempoPasado !== '') ? $tiempoPasado : '0 minutos';
	}

	public function prepararHtmlParaPdfFragmento($html) {
	    // 1. Eliminar etiquetas problemáticas (script, style, etc.)
	    $html = preg_replace('/<(script|style|iframe|object|embed).*?>.*?<\/\\1>/is', '', $html);

	    // 2. Asegurar codificación UTF-8
	    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

	    // 3. Reemplazar etiquetas problemáticas (opcional)
	    $html = str_replace(['<strong>', '</strong>'], ['<b>', '</b>'], $html); // Dompdf maneja mejor <b> que <strong>

	    // 4. Limpiar espacios y saltos de línea innecesarios
	    $html = preg_replace('/\s+/', ' ', $html);
	    $html = trim($html);

	    return $html;
	}

	public function htmlPdf($html) {
	    // Eliminar espacios en blanco innecesarios
	    $html = trim($html);

	    $html = $this->prepararHtmlParaPdfFragmento($html);

	    // Reemplazar caracteres especiales por sus equivalentes HTML seguros
	    $reemplazos = [
	        '&nbsp;' => ' ', // Espacios no rompibles pueden causar problemas en DOMPDF
	        '&quot;' => '"', // Comillas dobles
	        '&apos;' => "'", // Comillas simples
	        '&amp;' => '&',  // Ampersand
	        '<br>' => '<br/>', // Asegurar cierre correcto de <br>
	        '<hr>' => '<hr/>', // Asegurar cierre correcto de <hr>
	    ];
	    $html = str_replace(array_keys($reemplazos), array_values($reemplazos), $html);

	    // Asegurar que <ul> y <ol> tengan <li> dentro (evita errores en DOMPDF)
	    $html = preg_replace('/<ul>(.*?)<\/ul>/s', '<ul>\1</ul>', $html);
	    $html = preg_replace('/<ol>(.*?)<\/ol>/s', '<ol>\1</ol>', $html);

	    // Evitar <p> dentro de listas <ul> o <ol> (DOMPDF a veces los ignora)
	    $html = preg_replace('/<p>\s*(<li>.*?<\/li>)\s*<\/p>/s', '\1', $html);

	    // Asegurar que las etiquetas <li> no contengan <p> dentro (puede generar errores)
	    $html = preg_replace('/<li>\s*<p>(.*?)<\/p>\s*<\/li>/s', '<li>\1</li>', $html);

	    // Agregar estilos en línea básicos (ya que DOMPDF tiene limitaciones con CSS externo)
	    $estilosInline = '<style>
	        body { font-family: Arial, sans-serif; font-size: 12px; }
	        p { margin: 5px 0; line-height: 1.5; }
	        ul { margin: 10px 0; padding-left: 20px; }
	        li { margin-bottom: 5px; }
	        strong { font-weight: bold; }
	    </style>';
	    
	    // Incluir estilos en línea al inicio del HTML
	    return $estilosInline . '<div>' . $html . '</div>';
	}

	public function get_data_abrasivo($ref,$quantity = 25){

		App::import("model","Abrasivo");
		$Abrasivo = new Abrasivo();

		$referencesAbrasivo 		= [];
		$referencesAbrasivo 		= ['M-8','M-25','M-60'];
		$referencesAbrasivoEsferico = ['S230','S280','S330'];
		$referencesAbrasivoAngular 	= ['GL50', 'GL18'];
		$referencesAbrasivoGarnet   = ['GHP 30/60MESH'];
		$typeAbrasivo 				= null;
		$unitPrice 					= null;
		$division 					= 25;

		$isRef = false;
		if(in_array($ref, $referencesAbrasivo) || in_array($ref, $referencesAbrasivoEsferico) || in_array($ref, $referencesAbrasivoAngular) || in_array($ref, $referencesAbrasivoGarnet)){
			$isRef = true;
			if(in_array($ref, $referencesAbrasivo)){
				$typeAbrasivo = 'normal';
				$precio       = 38225 + (25 * 21) + ( 25 * 33 ) ;
				
			}
			if(in_array($ref, $referencesAbrasivoEsferico)){
				$typeAbrasivo = 'esferica';
				$precio 	  = 32500 + 3455 + (25*3455) + (33*25) ;
			}
			if(in_array($ref, $referencesAbrasivoAngular)){
				$precio 	  = 32500 + 3615 + (25*3615) + (33*25) ;
				$typeAbrasivo = 'angular';
			}
			if(in_array($ref, $referencesAbrasivoGarnet)){
				$typeAbrasivo = 'garnet';
				$precio 	  = 32500 + 2445 + (25*2445) + (33*25) ;
			}

			$precio 		  /= $division;
			$unitPrice 	  	  = round($Abrasivo->field("unit_price",["kgs"=>$quantity,"type"=>$typeAbrasivo]) / $division);
			// $precio 	  	  = round($Abrasivo->field("price_cost",["kgs"=>$quantity,"type"=>$typeAbrasivo]) / $division);
		}
		return compact('isRef','typeAbrasivo','precio','unitPrice');
	}

	public function times_out_chat($user_id){
		App::import("model","Time");
		$time 		= new Time();
		$time_user  = 0;
		$active 	= 0;

		$data_user  = $time->findByUserId($user_id);

		if(!empty($data_user)){
			$active 	= $data_user["Time"]["block_user"];

			if($data_user["Time"]["vacation"] == 1 && $active == 1){
				if($data_user["Time"]["date_end"] >= date("Y-m-d")  ){
					$active = 0;
				}
			}

			$time_user  = strtolower(date("D",strtotime(date("Y-m-d H:i:s")))) == "sat" ? $data_user["Time"]["minutes_sat"] : $data_user["Time"]["minutes"];
		}

		return compact('time_user', 'active');

	}

	public function minutosPasadosEntreFechas($fecha1, $fecha2) {
	    // Crea objetos DateTime con las fechas proporcionadas
	    $dateTime1 = new DateTime($fecha1);
	    $dateTime2 = new DateTime($fecha2);

	    // Obtiene la diferencia entre las fechas en minutos
	    $diferencia = $dateTime1->diff($dateTime2);

	    // Calcula el total de minutos entre las fechas
	    $minutos = $diferencia->days * 24 * 60; // Días a minutos
	    $minutos += $diferencia->h * 60; // Horas a minutos
	    $minutos += $diferencia->i; // Minutos

	    return $minutos;
	}

	public function totalTimeLapsed($fecha1,$fecha2){
		$minutes = $this->minutosPasadosEntreFechas($fecha1, $fecha2);
		return $this->calcularTiempoPasado($minutes);
	}

	public function getGarantiaProv($id){

		$textGarantia = $this->__Garantia->field("description",["id" => $id]);

		return is_null($textGarantia) || empty($textGarantia) ? "" : $textGarantia;

	}

	public function getDataDeadline($flow_id){
		$this->ImportRequest = new ImportRequest();
		$datos = $this->ImportRequest->ImportRequestsDetail->find("first",[
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
    		"conditions" => ["ImportRequestsDetail.prospective_user_id" => $flow_id],
    		"fields" => ["ProspectiveUser.id","ProspectiveUser.clients_natural_id","ProspectiveUser.contacs_users_id","User.*","ImportRequestsDetail.*"],
    		"group" => ["ProspectiveUser.id"]
    	]);
    	if (!empty($datos)) {
    		return $datos;
    	}else{
    		return null;
    	}
	}

	public function getCountData($model,$ini = null,$end = null,$user_id = null){

		$ini = is_null($ini) ? date("2018-01-01") : $ini;
		$end = is_null($end) ? date("Y-12-31") : $end;
		$data = new $model();

		$conditions = ["DATE(created) >=" => $ini, "DATE(created) <=" => $end ];

		if ($model == "ProspectiveUser") {
			$conditions["state_flow !="] = [7,9];
		}

		if (!is_null($user_id)) {
			switch ($model) {

				case 'ClientsNatural':
				case 'ContacsUser':
				case 'ClientsLegal':					
						$conditions["user_receptor"] 	= 		$user_id;					
					break;				
				default:
						$conditions["user_id"] 		 	= 		$user_id;
					break;
			}
		}

		return $data->find("count",["conditions" => $conditions,"recursive" => -1 ]);
	}

	public function getIniEndQuotation(){
		$ini = $this->__Quotation->find("first",["fields"=> ["MIN(created) ini"], "recursive" => -1 ])[0]["ini"];
		$end = $this->__Quotation->find("first",["fields"=> ["MAX(created) end"], "recursive" => -1 ])[0]["end"];
		return compact("ini","end");
	}

	public function getArrayFromJson($json){
		
		if (is_array($json) || is_object($json))
	  	{
	      	$result = array();
	      	foreach ($json as $key => $value)
	      	{
	          	$result[$key] = $this->getArrayFromJson($value);
	      	}
	      	return $result;
	  	}
	  	return $json;
	}


	public function getProductFactor($id){
		$productData = $this->data_product($id);
		if($productData["Product"]["type"] == 0 && $productData["Product"]["currency"] == 2){
			return 1;
		}else{
			$category_id = $this->__Product->field("category_id",["id"=>$id]);
			return $this->__Category->field("factor",["id"=>$category_id]);
		}
	}

	public function validateCategoryService($id){
		return strtolower($this->__Category->field("name",["id"=>$id])) == "servicio";
	}

	public function getUrlConveyor($id){
		return $this->__Conveyor->field("url",["id" => $id]);
	}

	public function getTotalGroupInventory($packnumber){
		return $this->__Inventory->find("count",["conditions"=>["Inventory.packnumber" => $packnumber]]);
	}

	public function getListGroupInventory($packnumber){
		$ids = ($this->__Inventory->find("list",["fields"=>["Inventory.id"],"conditions"=>["Inventory.packnumber" => $packnumber]]));
		return implode(",", $ids);
	}

	public function getQuantityBlock($productData, $flujo_actual = null){

		if( !isset($productData["id"]) ){
			return [];
		}

		$this->__ProductsLock->recursive = -1;

		$totalEntTrn = $this->__Inventory->field("SUM(quantity)",["product_id" => $productData["id"], "state" => 1, "type" => 1, "warehouse" => "Transito"]);
		$totalSalTrn = $this->__Inventory->field("SUM(quantity)",["product_id" => $productData["id"], "state" => 1, "type" => 2, "warehouse" => "Transito"]);

		$productData["quantity_back"] 	= $totalEntTrn - $totalSalTrn;
		$productData["transito_real"]	= $productData["quantity_back"] <= 0 ? 0 : $productData["quantity_back"];

		if(is_null($flujo_actual)){
			$productsLock = $this->__ProductsLock->findAllByProductIdAndState($productData["id"],1);
		}else{
			$productsLock = $this->__ProductsLock->find("all",["conditions" =>["product_id" => $productData["id"], "state" => 1, "prospective_user_id != " => $flujo_actual ]  ]);
		}


		$lockMed 			= 0;
		$lockBog 			= 0;
		$lockStm 			= 0;
		$lockStb 			= 0;
		$lockBack 			= 0;
		$totalBloqueo 		= 0;
		$totalDisponible 	= 0;

		foreach ($productsLock as $key => $value) {
			// $lockMed	+= $value["ProductsLock"]["quantity"];
			// $lockBog	+= $value["ProductsLock"]["quantity_bog"];
			$lockBack	+= $value["ProductsLock"]["quantity_back"];
			$lockStm	+= $value["ProductsLock"]["quantity_stm"];
		}

		$totalBloqueo = $lockMed+$lockBog+$lockBack;

		$productData["quantity_back"] 	= ( ( $productData["quantity_back"] - $lockBack ) < 0) ? 0 : $productData["quantity_back"] - $lockBack;

		$totalDisponible 	= $productData["quantity_back"];

		$quantity_back 		= $productData["quantity_back"] - $totalBloqueo;

		return compact("productData","productsLock","totalBloqueo","totalDisponible","quantity_back","lockStm");

	}

	public function calculateMargen($trmActual,$factorImport, $costo, $datosProducto,$cantidad, $currency){
		if (isset($datosProducto["header"]) && $datosProducto["header"] == 3) {
			$costoFinal = $costo;
			$valorFinal = floatval($datosProducto["price"]);
		}elseif($currency == "cop"){
			$costoFinal = $costo;
			$valorFinal = is_null($datosProducto) ? $costoFinal / 0.65 : floatval($datosProducto["price"]);
		}else{
			$costoFinal = ($costo*$trmActual)*$factorImport;
			$valorFinal = is_null($datosProducto) ? (($costo*$trmActual) * 1.1) / 0.65 : round(intval($datosProducto["price"]),2);
		}

		$margenFinal = round( ($valorFinal == 0 ? 0 : ( ($valorFinal-$costoFinal) /$valorFinal) * 100), 2) ;

		return $margenFinal;
	}

	public function validarBoquillas($productData){
		$response  = false;
		$validRefs = [
					'286111','286113','286115','286121','286211','286213','286215','286217','286219','286235','286311','286313','286315',
					'286317','286319','286321','286325','286411','286413','286415','286417','286425','286433','286511','286513','286515','286517','286519','286521','286531',
					'FFLP112','FFLP210','FFLP212','FFLP310','FFLP410','FFLP510','FFLP516','LL5317','LL5319','LL5321','LL5325','LL5327','LL5331','LL5335','LL5339','LL5423',
					'LL5425','LL5427','LL5431','LL5435','LL5643','LTX111','LTX115','LTX211','LTX213','LTX215','LTX217','LTX219','LTX221','LTX311','LTX313','LTX315','LTX317',
					'LTX321','LTX409','LTX413','LTX415','LTX417','LTX511','LTX515','LTX517','LTX519','LTX521','LTX817','XHD115','XHD117','XHD119','XHD121','XHD211','XHD215',
					'XHD217','XHD219','XHD221','XHD225','XHD227','XHD315','XHD317','XHD319','XHD321','XHD323','XHD325','XHD327','XHD329','XHD411','XHD417','XHD419',
					'XHD421','XHD425','XHD427','XHD513','XHD515','XHD517','XHD519','XHD521','XHD523','XHD525','XHD527','XHD529','XHD531','XHD535'];

		$categoryName = $this->__Category->field("name",["id"=>$productData["category_id"]]);

		// if (strtoupper($categoryName) == "BOQUILLAS" ) {
		if ( $productData["category_id"] == "265") {
			if(!in_array(strtoupper($productData["part_number"]), $validRefs)){
				$response = true;
			}
		}else{
			$response = false;
		}

		return $response;
	}

	public function get_user_flujo($flojoId){
		$this->__ProspectiveUser->recursive = -1;
		$datosFlujo = $this->__ProspectiveUser->findById($flojoId);
		return $this->find_name_adviser($datosFlujo["ProspectiveUser"]["user_id"]);
	}

	public function count_imports_process(){
		$nacional = $this->__Import->find("count",array("conditions"=>array("Import.state" => 1, "Import.send_provider" => 1,"Import.description !=" => "", "Import.internacional" => 0)));
		$internacional = $this->__Import->find("count",array("conditions"=>array("Import.state" => 1, "Import.send_provider" => 1,"Import.description !=" => "", "Import.internacional" => 1)));
		return "NAC ($nacional) / INT ($internacional) <br>";
	}

	public function getProspectiveIdVenta($product_id)
	{
		$data = $this->__Quotation->getProspectiveUserLast($product_id);
		return is_null($data) ? 0 : $data;
	}

	public function validateOtherDetails($flujo,$detail){
		return count($this->__ImportRequestsDetail->getOthersDetails($flujo, $detail)) > 0 ? true : false;
	}

	public function getFlowsFactsAnios($str){
		return $this->__ProspectiveUser->find("list",["fields"=>["id","id"],"conditions"=> ["remarketing" => 1, "origin" => "Remarketing", "bill_file" => $str ] ]);
	}

	public function extratctFlujos($importInfo){
		$flujos = array();

		if (!empty($importInfo["ProspectiveUser"])){
			foreach ($importInfo["ProspectiveUser"] as $key => $value) {
				$flujos[] = $value["id"];
			}
		}

		if (!empty($importInfo["Flujos"])){
			foreach ($importInfo["Flujos"] as $key => $value) {
				if(!in_array($value["id"], $flujos)){
					$flujos[] = $value["id"];
				}
			}
		}
		return $flujos;
	}

	public function getCodeBill($prospectiveData, $salesenvoice_id){
		if($salesenvoice_id == 0){
			return $prospectiveData["bill_code"];
		}else{
			$invoiceData = $this->__Salesinvoice->findById($salesenvoice_id);
			return $invoiceData["Salesinvoice"]["bill_code"];
		}
	}

	public function getCodeBillField($prospectiveData, $salesenvoice_id, $field){
		if($salesenvoice_id == 0){
			return $prospectiveData[$field];
		}else{
			$invoiceData = $this->__Salesinvoice->findById($salesenvoice_id);
			if($field == "bill_user"){ $field = "user_id"; }
			return $invoiceData["Salesinvoice"][$field];
		}
	}

	public function getDataBill($prospectiveData, $salesenvoice_id){
		if($salesenvoice_id == 0){
			return $prospectiveData;
		}else{
			$invoiceData = $this->__Salesinvoice->findById($salesenvoice_id);
			return $invoiceData["Salesinvoice"];
		}
	}

	public function calculateMonths($date_ini, $date_end){

		if (is_null($date_ini)) {
			return 12;
		}

		$month = 0;

		$date1 = new DateTime($date_ini);
		$date2 = new DateTime($date_end);
		$diff  = $date1->diff($date2);

		if($diff->y > 0){
			$month = $diff->y * 12;
		}

		$month += $diff->m;

		return $month;
	}

	public function calculateDays($date_ini, $date_end){
		$date1 = new DateTime($date_ini);
		$date2 = new DateTime($date_end);
		$diff = $date1->diff($date2);
		return $diff->invert == 1 ? 0 : $diff->days;
	}

	public function getTypeProspective ($prospective_users_id){
		return $this->__ProspectiveUser->field("contacs_users_id", array("id" => $prospective_users_id));
	}


	public function getDayTrm($day,$ajuste,$default){

		$strValue = $day." - $";

		$trm = $this->__Trm->find(
			"first", [ "conditions" => ["DATE(fecha_inicio) <= " => $day, "DATE(fecha_fin) >= " => $day] ]	
		);

		if(!empty($trm)){
			$valorFinal = ($trm["Trm"]["valor"] * 1) + $ajuste;
			$strValue.= $valorFinal. " COP";
		}else{
			$valorFinal = ($default * 1) + $ajuste;
			$strValue.= $valorFinal. " COP";
		}

		return [$strValue,$valorFinal];

	}


	public function getCostProductForImport($prospective_id, $product_id, $currency = null){
		if($prospective_id != 0){
			$id_etapa_cotizado 			= $this->__FlowStage->id_latest_regystri_state_cotizado($prospective_id);
			$produtosCotizacion 		= array();
			$datosFlowstage 			= $this->__FlowStage->get_data($id_etapa_cotizado);
			if ( isset($datosFlowstage['FlowStage']['document']) && is_numeric($datosFlowstage['FlowStage']['document'])) {
				$this->__FlowStage->Quotation->QuotationsProduct->recursive = -1;
				$produtosCotizacion 	= $this->__FlowStage->Quotation->QuotationsProduct->findAllByQuotationId($datosFlowstage['FlowStage']['document']);
				if (!empty($produtosCotizacion)) {
					$produtosCotizacion = Set::sort($produtosCotizacion, '{n}.QuotationsProduct.currency', 'desc');
				}
				foreach ($produtosCotizacion as $key => $value) {

					if($value["QuotationsProduct"]["product_id"] == $product_id){
						$value["QuotationsProduct"]["header"] = $this->__Quotation->field("header_id",["id" => $value["QuotationsProduct"]["quotation_id"]]);
						return $value["QuotationsProduct"];
					}
										
				}
			}
		}
		
		return null;
	}

	public function calculateQuantityMin($value){
		$min = 0;

		if(!empty($value["details"])){
			foreach ($value["details"] as $key => $detail) {
				if(!empty($detail["ImportRequestsDetail"]["prospective_user_id"])){
					$min+=$detail["ImportProductsDetail"]["quantity"];
				}
			}
		}
		return $min;
	}

	public function getComissionPercentaje($days, $commsionData){

		$percentaje = 0;

		if(intval($commsionData["Commision"]["range_one_end"]) >= $days){
			$percentaje = floatval($commsionData["Commision"]["range_one_percentage"]);
		}elseif($commsionData["Commision"]["range_two_end"] >= $days){
			$percentaje = floatval($commsionData["Commision"]["range_two_percentage"]);
		}elseif($commsionData["Commision"]["range_three_end"] >= $days){
			$percentaje = floatval($commsionData["Commision"]["range_three_percentage"]);
		}elseif($commsionData["Commision"]["range_four_end"] >= $days){
			$percentaje = floatval($commsionData["Commision"]["range_four_percentage"]);
		}

		return $percentaje;
	}

	public function validateImportToSend($imports, $product_id, $sale_id, $quantity_product = null){

		$estadoProducto = 0;

		if(empty($imports)){
			return $estadoProducto;
		}else{
			$datosProducto = null;
			foreach ($imports as $key => $value) {
				$datosProducto =$this->__ImportProductsDetail->find("first",array("conditions" => array(
					"product_id" => $product_id, "ImportProductsDetail.import_id" => $value["id"], "flujo" => $sale_id, "ImportProductsDetail.quantity" => $quantity_product
				)));
				if(!empty($datosProducto)){
					$estadoProducto = $value["state"];
					break;
				}
			}
		}
		
		return $estadoProducto;
	}

	public function getStateImport($estado){
		$text = "";
		switch ($estado) {
			case '1':
				$text = "Importación en proceso";
				break;
			case '2':
				$text = "Importacion finalizada";
				break;
			case '3':
				$text = "Importación en resivion";
				break;
			case '4':
				$text = "Importación en rechazada ";
				break;
		}
		return $text;
	}

	public function validateRoleInventory(){
		$rolesImports = array( Configure::read('variables.roles_usuarios.Gerente General'),
                                Configure::read('variables.roles_usuarios.Logística'));
        if(in_array(AuthComponent::user('role'), $rolesImports)){
        	return true;
        }
        return false;
	}

	public function type_note($type){
		$texto = '';
		switch ($type) {
			case '1':
				$texto = 'Nota previa';
				break;
			case '2':
				$texto = 'Nota descriptiva';
				break;
			case '3':
				$texto = 'Condición del negocio';
				break;
			case '5':
				$texto = 'Garantía general';
				break;
		}
		if(AuthComponent::user("role") == "Gerente General" && $type == 4){
			$texto = "Nota al proveedor";
		}
		return $texto;
	}

	public function getInfoByBrand($brandId){
		$this->__ImportsBySale->recursive = 2;
		$data = array(
			"brand"   => $this->__Brand->findById($brandId)
		);
		return $data;
	}

	public function getClassDate($fecha_f){
		$fecha_i = date("Y-m-d");
		$class   = "";
		$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
		return $dias;
	}

	public function calculateFechaFinalEntrega($fechaProgramado,$dias = 0){
	
		if ($dias == 0){
			return date("Y-m-d",strtotime($fechaProgramado));
		}



		for ($i=1; $i < $dias; $i++) { 
			$fecha = date("d-m-Y",strtotime($fechaProgramado."+ 1 days"));
			$fechaProgramado = $this->validateDay($fecha);
		}

		return $fechaProgramado;

	}

	private function validateDay($day){
		$time = strtotime($day);
		
		$dayValidate = date("D",$time);

		if($dayValidate == "Sat"){
            $day = date("d-m-Y",strtotime($day."+ 2 days"));
        }
        if($dayValidate == "Sun"){
            $day = date("d-m-Y",strtotime($day."+ 1 days"));
        }

        if(in_array($day, Configure::read("variables.diasFestivos"))){
        	$day = date("d-m-Y",strtotime($day."+ 1 days"));
        	return $this->validateDay($day);
        }else{
        	return $day;
        }

	}

	public function consult_cod_service($service_id){
		return $this->__TechnicalService->consult_codigo_service($service_id);
	}

	public function find_codigo_cotizacion($cotizacion_id){
		if ($cotizacion_id == '') {
			$nombre 		= 'Importacion solicitada';
		} else {
			$datos 			= $this->__Quotation->get_data($cotizacion_id);
			$nombre 		= $datos['Quotation']['codigo'];
		}
		return $nombre;
	}

	public function servicio_tecnico_encargado($user_id){
		$datos 			= $this->__User->get_data($user_id);
		$texto 			= '';
		$roles 			= array(Configure::read('variables.roles_usuarios.Servicio Técnico'),Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'));
		if (in_array($datos['User']['role'], $roles)){
			$texto = $datos['User']['name'];
		} else {
			$texto = 'Sin asginar';
		}
		return $texto;
	}

	public function getNameProduct($product_id){

		$name 		= $this->__Product->field(  "name" , ["id" => $product_id] );
		$ref 		= $this->__Product->field(  "part_number" , ["id" => $product_id] );
		$posName 	= strpos(strtolower($name), "covid");
		$posRef 	= strpos(strtolower($ref), "covid");

		if( $posName !== false || $posRef !== false ){
			return true;
		}else{
			return false;
		}
	}

	public function data_product($product_id){
		return $this->__Product->findById($product_id);
	}

	public function getRefProd($product_id)
	{
		return $this->__Product->field(  "part_number" , ["id" => $product_id] );
	}

	public function date_management_product_pedido($producto_date,$pedido_date){
		$texto = '';
		if ($producto_date == '0000-00-00') {
			$texto = 'Fecha de la compra del pedido:  <b>'.$pedido_date.'</b>';
		} else {
			$texto = 'Fecha de la gestión del producto:  <b>'.$producto_date.'</b>';
		}
		return $texto;
	}

	public function find_state_dispatches($state){
		$texto = '';
		switch ($state) {
			case '0':
				$texto = 'Por gestionar el pedido';
				break;
			case '1':
				$texto = 'Sin importación';
				break;
			case '2':
				$texto = 'Solicitud de importación';
				break;
			case  '3':
				$texto = 'Despachado';
				break;
			case  '4':
				$texto = 'Entregado';
				break;
			case '5':
				$texto = 'Importación en proceso';
				break;
			case '6':
				$texto = 'Importación finalizada';
				break;
		}
		return $texto;
	}

	public function find_state_dispatches_shipping($state){
		$texto = '';
		switch ($state) {
			case '0':
				$texto = 'Sin enviar';
				break;
			case '1':
				$texto = 'Enviado';
				break;
			case '2':
				$texto = 'Entregado';
				break;
			case  '3':
				$texto = 'Despachado';
				break;
			case  '4':
				$texto = 'Entregado';
				break;
			case '5':
				$texto = 'Importación en proceso';
				break;
			case '6':
				$texto = 'Importación finalizada';
				break;
		}
		return $texto;
	}

	public function validateShippingForEnd($flow){

		$this->__Shipping->recursive = -1;
		$dataShipper = $this->__Shipping->findByFlowId($flow);
		return $dataShipper;

	}

	public function paint_state_despacho($stateFlujo,$flujo_id,$stateFlow){
		$btn = '<a class="state_despachado btn btn-secondary btn-sm" data-uid="'.$flujo_id.'" data-stateFlow="'.$stateFlow.'" data-state="'.$stateFlujo.'"> Confirmar despacho</a>';

		// $order_id 	= $this->__Order->field("id",["prospective_user_id" => $flujo_id, "state" => 1]);

		// if (empty($order_id) || is_null($order_id)) {
		// 	$btn = '<a class="state_despachado btn btn-secondary" data-uid="'.$flujo_id.'" data-stateFlow="'.$stateFlow.'" data-state="'.$stateFlujo.'"> Confirmar despacho</a>';
		// }else{
		// 	$btn = '<a class="btn btn-secondary" href="'.Router::url("/",true).'shippings/index/'.$this->encryptString($order_id).'" target="_blank"> Confirmar despacho</a>';
		// }

		return $btn;
	}

	public function cotizacion_servicio_tecnico($cotizacion){
		$texto = 'Al servicio NO se le genera cotización';
		if ($cotizacion == 1) {
			$texto  = '<a href="/TechnicalServices/flujos">Este servicio técnico tiene asociado un flujo comercial</a>';
		}
		return $texto;
	}

	public function last_12_days_date(){
		return date('Y-m-d', strtotime('-12 day'));
	}

	public function last_1_month_date(){
		return date('Y-m-d', strtotime('-1 month'));
	}

	public function find_check_equipo($equipo_tecnico_id){
		return $this->__Accessory->find_accesories_machine($equipo_tecnico_id);
	}

	public function find_flujo_userid_date_origen($user_id,$date_ini,$date_fin,$origen){
		return $this->__ProspectiveUser->count_user_origen_date($user_id,$origen,$date_ini,$date_fin);
	}

	public function countFlujoOrigen($user_id,$datos,$origen){
		$total = 0;
		foreach ($datos as $key => $value) {
			if($value["ProspectiveUser"]["user_id"] == $user_id && strtolower($value["ProspectiveUser"]["origin"]) == strtolower($origen)){
				$total++;
			}
		}
		return $total;
	}

	public function find_type_pay_flujo($flujo_id){
		return $this->__FlowStage->find_type_pay_flujo($flujo_id);
	}

	public function find_valor_flujo_pagado($flujo_id){
		return $this->__FlowStage->find_valor_flujo_pagado($flujo_id);
	}

	public function finde_state_flujo($estado){
		$texto = '';
		switch ($estado) {
			case '1':
				$texto = 'Asignado';
				break;
			case '2':
				$texto = 'Contactado';
				break;
			case '3':
				$texto = 'Cotizado';
				break;
			case '4':
				$texto = 'Negociado';
				break;
			case '5':
				$texto = 'Pagado';
				break;
			case '6':
				$texto = 'Despachado';
				break;
			case '7':
				$texto = 'Cancelado';
				break;
			case '8':
				$texto = 'Terminado';
				break;
			case '9':
				$texto = 'Cancelado';
				break;
		}
		return $texto;
	}

	public function number_day_payment_text($dias){
		$texto = $dias;
		switch ($dias) {
			case "1":
				$texto = '30 días';
				break;
			case "2":
				$texto = '60 días';
				break;
			case "3":
				$texto = '90 días';
				break;
			case "4":
				$texto = '45 días';
				break;

		}
		return $texto;
	}

	public function type_pay_quotation($state){
		switch ($state) {
			case 0:
				$texto = 'Pago total con iva';
				break;
			case 1:
				$texto = 'Pago con retención';
				break;
			case 2:
				$texto = 'Pago con abono';
				break;
			case 3:
				$texto = 'Pago a crédito';
				break;
			case 4:
				$texto = 'Pago total sin iva';
				break;
			case 5:
				$texto = 'Pago a crédito sin iva';
				break;

			default:
				$texto = '';
		}
		return $texto;
	}

	public function count_notificaciones_user(){
		return $this->__Manage->count_user_manages_new(AuthComponent::user('id'));
	}

	public function paint_home_menu_user($role){
		$action = '';
		switch ($role) {
            case Configure::read('variables.roles_usuarios.Servicio al Cliente'):
                $action = 'home_adviser';
                break;
            case Configure::read('variables.roles_usuarios.Asesor Comercial'):
            	$action = 'home_adviser';
                break;
            case Configure::read('variables.roles_usuarios.Servicio Técnico'):
            	$action = 'home_tecnico';
                break;
            case Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'):
            	$action = 'home_adviser';
                break;
            case Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'):
            	$action = 'home_adviser';
            	break;
            case Configure::read('variables.roles_usuarios.Gerente General'):
            	$action = 'home_adviser';
                break;
            case Configure::read('variables.roles_usuarios.Administración'):
            	$action = 'home_adviser';
                break;                
            case Configure::read('variables.roles_usuarios.Contabilidad'):
            	$action = 'home_adviser';
                break;
            case Configure::read('variables.roles_usuarios.Logística'):
            	$action = 'home_adviser';
                break;
            case Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'):
            	$action = 'home_adviser';
            case "Asesor Externo":
            	$action = 'home_adviser';
            case "Publicidad":
            	$action = 'home_adviser';
                break;
        }
		return $action;
	}

	public function state_notice_management($state){
		switch ($state) {
			case '0':
				$texto = 'Deshabilitado';
				break;
			case '1':
				$texto = 'Activo';
				break;
			case '2':
				$texto = 'Por habilitar';
				break;
		}
		return $texto;
	}

	public function state_service_tchnical($state){
		$texto = 'En proceso';
		if ($state == 1) {
			$texto = 'Finalizado';
		}
		return $texto;
	}

	public function checked_type_client_natural($cliente_natural){
		$texto = 'checked';
		if ($cliente_natural == 0) {
			$texto = '';
		}
		return $texto;
	}

	public function checked_type_client_legal($contact){
		$texto = 'checked';
		if ($contact == 0) {
			$texto = '';
		}
		return $texto;
	}

	public function find_seeker_data_model($model){
		$id 			= current($model)['id'];
		$modelQuery 	= key($model);
		$modelFind  	= '__'.$modelQuery;
		$datos 			= $this->$modelFind->get_data($id);
		return $datos[$modelQuery];
	}

	public function count_cotizaciones_enviadas(){
		return $this->__FlowStage->count_cotizaciones_enviadas();

	}

	public function count_cotizaciones_aprobar(){
		return $this->__Approve->find("count",["conditions" => ["Approve.state" => 0]]);
	}

	public function count_all_flujos(){
		return $this->__ProspectiveUser->count_all_flujos();
	}

	public function count_me_flujos(){
		return $this->__ProspectiveUser->count_me_flujos();
	}

	public function count_services(){
		return $this->__TechnicalService->count_services_false().' / '.$this->__TechnicalService->count_services_true();
	}

	public function count_services_true($data = false){
		return $this->__TechnicalService->count_services_true($data);
	}

	public function count_services_false(){
		return $this->__TechnicalService->count_services_false();
	}

	public function count_state_despachado_confirm_delievery(){
		return $this->__ProspectiveUser->count_flow_despachado($a = array());
	}

	public function count_state_despachado(){
		return $this->__FlowStage->count_state_despachado();
	}

	public function count_importaciones(){
		return $this->__Import->count_importaciones();
	}

	public function count_importaciones_in_process_finish(){
		return $this->__Import->count_importaciones_in_process_finish();
	}

	public function count_pending_dispatches(){
		return $this->__FlowStage->count_pending_dispatches();
	}

	public function count_flujos_verify(){
		return $this->__FlowStage->count_flujos_verify();
	}

	public function count_flujos_verify_creditos(){
		return $this->__FlowStage->count_flujos_verify_payment_credito();
	}

	public function count_flujos_verify_abonos(){
		return $this->__FlowStage->count_flujos_verify_abonos();
	}

	public function count_state_pagado_true(){
		return $this->__FlowStage->count_state_pagado_true();
	}

	public function count_state_pagado_false(){
		return $this->__FlowStage->count_state_pagado_false();
	}

	public function model_key_array($model){
		return key($model) == "ContacsUser" ? 'ClientsLegals' : key($model).'s';
	}

	public function id_model_array($model){
		if(array_keys($model)[0] == "ContacsUser"){
			return $this->__ContacsUser->field("clients_legals_id",["id" => current($model)['id'] ]);
		}
		return current($model)['id'];
	}

	public function find_nombre_modelo($model){
		$modelQuery 	= key($model);
		switch ($modelQuery) {
			case 'ClientsNatural':
				$nombreModel = 'Cliente';
				break;
			case 'ClientsLegal':
				$nombreModel = 'Cliente';
				break;
			case 'ContacsUser':
				$nombreModel = 'Contacto';
				break;
			case 'FlowStage':
				$nombreModel = 'Cotización enviada';
				break;
			case 'Product':
				$nombreModel = 'Producto';
				break;
			case 'Quotation':
				$nombreModel = 'Cotización';
				break;
			case 'Template':
				$nombreModel = 'Plantilla';
				break;
			case 'User':
				$nombreModel = 'Usuario';
				break;
			case 'ProspectiveUser':
				$nombreModel = 'Flujo';
				break;
			case 'TechnicalService':
				$nombreModel = 'Servicio técnico';
				break;

			default:
				$nombreModel = 'Modelo';
				break;
		}
		return $nombreModel;
	}

	public function valid_position_array($array,$controller){
		$array_model_not_position_name = array('ProspectiveUser','TechnicalService');
		if (!in_array($controller, $array_model_not_position_name)) {
			if (!isset($array['name'])) {
				$array['name'] 		= $array['nameDocument'];
			}
			if ($array['name'] == '') {
				$quotation 			= $this->__Quotation->get_data($array['document']);
				$array['name'] 		= $quotation['Quotation']['name'];
			}
		}
		return $array;
	}

	public function validate_isset_borrador($flujo_id){
		$numero 	= $this->__FlowStagesProduct->count_row_etapa($flujo_id);
		$nombre 	= "";
		if ($numero > 0) {
			$nombre = "BORRADOR EN CURSO";
		}
		return $nombre;
	}

	public function data_null_notifications_new($datos){
		if ($datos == 0) {
			return 'Aún no tienes notificaciones nuevas';
		}
	}

	public function compararTiempoLimiteAtendidoFlujo($fechaHoraLimite,$fechaHoraAtendido){
		$fechaHoraLimite = trim($fechaHoraLimite);
		$fechaHoraAtendido = trim($fechaHoraAtendido);
		if(empty($fechaHoraLimite) || is_null($fechaHoraLimite) || empty($fechaHoraAtendido) || is_null($fechaHoraAtendido)){
			$icon = '<i class="fa fa-times"></i>';
		}elseif ($fechaHoraLimite == '0000-00-00' || $fechaHoraLimite == '0000-00-00 00:00:00') {
			$icon = '';
		} else {
			$tiempo_limite_tiempo_array     		= split(" ",$fechaHoraLimite);
			if ($fechaHoraAtendido == '0000-00-00' || $fechaHoraAtendido == '0000-00-00 00:00:00') {
			   	$tiempo_atendido_tiempo_array[0]     		= date("Y-m-d");
			   	$tiempo_atendido_tiempo_array[1]     		= date("H:i:s");
			} else {
			   	$tiempo_atendido_tiempo_array     			= split(" ",$fechaHoraAtendido);
			}
		   	$tiempo_atendido_time_array     		= split(":",$tiempo_atendido_tiempo_array[1]);
		    $tiempo_atendido_date_array     		= split("-",$tiempo_atendido_tiempo_array[0]);
		    $tiempo_limite_time_array     			= split(":",$tiempo_limite_tiempo_array[1]);
		    $tiempo_limite_date_array     			= split("-",$tiempo_limite_tiempo_array[0]);

		    if ((int) $tiempo_limite_date_array[0] < (int) $tiempo_atendido_date_array[0] || (int) $tiempo_limite_date_array[1] < (int) $tiempo_atendido_date_array[1] || (int) $tiempo_limite_date_array[2] < (int) $tiempo_atendido_date_array[2]) {
		    	$icon = '<i class="fa fa-times"></i>';
		    } else {
		    	$icon = '<i class="fa fa-check"></i>';
		    	if ((int) $tiempo_limite_time_array[0] < (int) $tiempo_atendido_time_array[0] && (int) $tiempo_limite_date_array[2] == (int) $tiempo_atendido_date_array[2]) {
		    		$icon = '<i class="fa fa-times"></i>';
		    	}
		    }
		}
		return $icon;
	}

	public function state_conection($state_conection){
		$texto 			= 'Desconectado';
		if ($state_conection == 1) {
			$texto 		= 'Conectado';
		}
		return $texto;
	}

	public function find_state_conection($user_id){
		$datos 			= $this->__User->get_data($user_id);
		return $this->state_conection($datos['User']['state_conection']);
	}

	public function find_cantidad_product_quotation($product_id,$quotation_id){
		return $this->__QuotationsProduct->find_cantidad_product($quotation_id,$product_id);
	}

	public function find_precio_product_quotation($product_id,$quotation_id){
		return $this->__QuotationsProduct->find_precio_product($quotation_id,$product_id);
	}

	public function find_stateFlow_flujo($flujo_id){
		return $this->__ProspectiveUser->get_stateFlow_flujo($flujo_id);
	}

	public function find_stateFlow_quotation($flujo_id,$quotation_id){
		$existeEtapa 			= $this->__FlowStage->exist_state_cotizado_prospective($flujo_id);
		if ($existeEtapa > 0) {
			$estado 				= $this->__ProspectiveUser->get_stateFlow_flujo($flujo_id);
			$etapa_id_cotizado 		= $this->__FlowStage->id_latest_regystri_state_cotizado($flujo_id);
			$datosF 				= $this->__FlowStage->get_data($etapa_id_cotizado);
			if ($datosF['FlowStage']['document'] == $quotation_id) {
				switch ($estado) {
					case 7:
						$nombre 		= 'No vendida';
					break;
					case 8:
						$nombre 		= 'Vendida y entregada';
					break;

					default:
						$nombre 		= 'En proceso';
						break;
				}
			} else {
				$nombre 				= 'Se generó una nueva';
			}
		} else {
			$nombre 					= 'Por enviar';
		}
		return $nombre;
	}

	public function exist_file($ruta){
		$file = false;
		if (file_exists($ruta)) {
			$file = true;
		}
		return $file;
	}

	public function validate_new_quotation($ultimoRegistro,$idFlow,$state,$flujo_id){
		$validate 			= false;
		if ($ultimoRegistro == $idFlow && $state == 4){
			$validate 		= true;
		}
		return $validate;
	}

	public function getOrder($prospective_user_id){
		$order = $this->__Order->field("id",["prospective_user_id" => $prospective_user_id, "state" => 1]);
		return is_null($order) || empty($order) ? 0 : $order;
	}

	public function validate_active_pago_flujo($state_flow,$flujo_id, $datosFlujo = null, $viewFlujo = false){
		$validate 	= '';
		$order_id 	= $this->__Order->field("id",["prospective_user_id" => $flujo_id, "state" => 1]);
		switch ($state_flow) {
			case 3:
				$cliente = 0;
				$tipoCliente = "";
				if(!is_null($datosFlujo)){
					if(!is_null($datosFlujo["clients_natural_id"]) && $datosFlujo["clients_natural_id"] != "0" ){
						$cliente 		= $datosFlujo["clients_natural_id"];
						$tipoCliente 	= "natural";
					}else{
						$datosContacto  = $this->__ContacsUser->findById($datosFlujo["contacs_users_id"]);
						$cliente 		= $datosContacto["ContacsUser"]["clients_legals_id"];
						$tipoCliente 	= "legal";
					}
				}
				if(!$viewFlujo){
					$validate 	= '<a href="javascript:void(0)" id="btn_informacion_despacho" data-client="'.$cliente.'" data-type="'.$tipoCliente.'" data-uid="'.$flujo_id.'">Gestionar <i class="fa fa-edit"></i></a>';

					// if (empty($order_id) || is_null($order_id)) {
					// 	$validate 	= '<a href="javascript:void(0)" id="btn_informacion_despacho" data-client="'.$cliente.'" data-type="'.$tipoCliente.'" data-uid="'.$flujo_id.'">Ingresar datos de envío <i class="fa fa-edit"></i></a>';
					// }else{
					// 	$validate 	= '<a href="'.Router::url("/",true).'shippings/index/'.$this->encryptString($order_id).'" target="_blank">Ingresar datos de envío <i class="fa fa-edit"></i></a>';
					// }

					
				}else{
					$validate = '<a class="text-b" href="'.Router::url("/",true).'shippings/index" target="_blank"> Se debe proceder al despacho </a>';
				}

				break;
			case 7:
				$validate 	= '<a data-toggle="tooltip"  class="btn_administrar_orden" data-uid="'.$flujo_id.'">Gestionar pedido </a>';
				break;
			case 2:
				if(is_null($datosFlujo["flow"])){
					$validate 	= '<a data-toggle="tooltip"  class="btn_administrar_orden gestionPronta" data-uid="'.$flujo_id.'">Gestionar pedido antes de validar el pago</a>';
				}else{
					$validate 	= '';
				}
				break;

			
			default:
				$validate 	= '';
				break;
		}
		return $validate;
	}

	public function validate_active_pago_new_abono($state_flow,$flujo_id,$ultimoId,$idEtapa){
		$validate 				= '';
		switch ($state_flow) {
			case 3:
				$validate 		= '<a href="javascript:void(0)" class="state_pagado" data-uid="'.$flujo_id.'" data-state="4">Realizar otro abono</a>';
				break;
			case 6:
			case 8:
			case 7:
				$validate 		= '<a href="javascript:void(0)" class="state_pagado" data-uid="'.$flujo_id.'" data-state="4">Realizar otro abono</a>';
				break;
		}
		if ( !in_array( $this->__ProspectiveUser->field("state_flow",["id"=>$flujo_id]) , [6,8]) && $ultimoId != $idEtapa) {
			$validate 			= '';
		}

		return $validate;
	}

	public function verificate_payment_flujo($state_flow,$ultimoId,$idEtapa,$flujo_id){
		$validate 				= '';
		switch ($state_flow) {
			case 3:
				$validate 		= '<a href="javascript:void(0)" data-etapa="'.$idEtapa.'" data-uid="'.$flujo_id.'" class="verificar_pago_abono">Pagar todo el saldo</a>';
				break;
			case 7:
				$validate 		= '<a href="javascript:void(0)" data-etapa="'.$idEtapa.'" data-uid="'.$flujo_id.'" class="verificar_pago_abono">Pagar todo el saldo</a>';
				break;

		}
		if ($ultimoId != $idEtapa) {
			$validate 			= '';
		}
		return $validate;
	}

	public function validate_pedido_importacion_pago_abono($state_flow,$flujo_id,$datosFlujo = null){
		$validate 				= '';
		switch ($state_flow) {
			case 3:
				$validate 		= '<a data-toggle="tooltip"  class="btn_administrar_orden" data-uid="'.$flujo_id.'">Gestionar pedido </a>';
				break;
			case 7:
				$validate 		= '<a data-toggle="tooltip"  class="btn_administrar_orden" data-uid="'.$flujo_id.'">Gestionar pedido </a>';
				break;
			case 2:
				if(!is_null($datosFlujo) &&  is_null($datosFlujo["flow"]) ){
					$validate 	= '<a data-toggle="tooltip"  class="btn_administrar_orden gestionPronta" data-uid="'.$flujo_id.'">Gestionar pedido antes de validar el pago</a>';
				}else{
					$validate 	= '';
				}
				break;

		}
		return $validate;

	}

	public function confirm_despacho($state,$flujo_id){
		$texto = '';
		switch ($state) {
			case Configure::read('variables.control_flujo.flujo_despachado'):
				$texto = '<b><a href="javascript:void(0)" class="btn_confirm_entrega" data-uid="'.$flujo_id.'">Confirmar recibido</a></b>';
				break;
			case Configure::read('variables.control_flujo.flujo_finalizado'):
				$texto = '';
				break;
			
			default:
				$texto = 'Aún faltan productos por enviar';
				break;
		}
		return $texto;
	}

	public function validate_show_accordeon($flow_id,$idLatestRegystri){
		$state_show 		= '';
		if ($flow_id == $idLatestRegystri) {
			$state_show 	= 'show';
		}
		return $state_show;
	}

	public function find_name_adviser($user_id){

		if (is_array($user_id)) {
			$user_id = $user_id[0];
		}

		$result = Cache::read('usuarios');
	    if ((!$result || empty($result)) && !empty($user_id)) {
	    	$all_users = $this->__User->find("list",["fields"=>["id","name"]]);
	        $datos 	 = $all_users[$user_id];
	        Cache::write('phones', $all_users);
	    }else{
	    	$datos = $result[$user_id];
	    }
		if (empty($datos)) {
			return "No registra nombre.";
		}
		$texto = explode(" ", $datos);
		if (isset($texto[1])) {
			$texto = $texto[0];
		}else{
			$texto = $datos;
		}
		return $texto;
	}

	public function find_name_lastname_adviser($user_id){
		$datos 			= $this->__User->get_data($user_id);
		$adicional 		= $datos["User"]["role"] == "Asesor Externo" ? ' <span class="text-danger">- Asesor Externo</span>' : "";
		$name 			= $datos['User']['name'] . $adicional; 
		return $name;
	}

	public function find_name_document_quotation_send($prospective_id){
		if ($prospective_id == 0) {
			$nombre 				= 'Importacion solicitada';
		} else {
			$existeEtapa 			= $this->__FlowStage->exist_state_cotizado_prospective($prospective_id);
			if ($existeEtapa > 0) {
				$datos 				= $this->__FlowStage->id_name_document_latest_regystri_state_cotizado($prospective_id);
				if ($datos['FlowStage']['nameDocument'] != '') {
					$nombre 		= $datos['FlowStage']['nameDocument'];
				} else {
					$quotation 		= $this->__Quotation->get_data($datos['FlowStage']['document']);
					$nombre 		= $quotation['Quotation']['name'];
				}
			} else {
				$nombre = 'La cotización NO ha sido enviada';
			}
		}
		return $nombre;
	}

	public function find_flujoid_quotationid($quotation_id){
		return $this->__Quotation->find_flujoid_for_quotation_id($quotation_id);
	}

	public function find_id_document_quotation_send($prospective_id){
		$texto = 0;
		$existeEtapa 		= $this->__FlowStage->exist_state_cotizado_prospective($prospective_id);
		if ($existeEtapa > 0) {
			$id_cotizado 		= $this->__FlowStage->id_latest_regystri_state_cotizado($prospective_id);
			$datos 				= $this->__FlowStage->get_data($id_cotizado);
			$texto 				= $datos['FlowStage']['document'];
		}
		return $texto;
	}

	public function find_name_file_quotation($nameDocumento,$quotation_id){
		$porciones = explode(".", $quotation_id);
		if (!isset($porciones[1])) {
			$datos = $this->__Quotation->get_data($quotation_id);
			if (isset($datos['Quotation'])) {
				$name = $datos['Quotation']['name'];
			} else {
				$name = 'Documento inhabilitado';
			}
		} else {
			$name = $nameDocumento;
		}
		return $name;
	}

	public function find_valor_quotation_flujo_id($flujo_id){
		$valor 		= $this->__FlowStage->valor_latest_regystri_state_cotizado_flujo_id($flujo_id);
		return number_format((int)h($valor),0,",",".");
	}

	public function data_null_notifications_read($datos){
		if ($datos == 0) {
			return 'No tienes notificaciones';
		}
	}

	public function data_null($datos){
		$texto = $datos;
		if ($datos == null) {
			$texto = 'No hay información';
		}
		return $texto;
	}

	public function data_null_date_importacion($datos){
		$texto = $datos;
		if ($datos == '0000-00-00') {
			$texto = 'No hay información';
		}
		return $texto;
	}

	public function data_null_numeros($datos){
		$texto = '$'.$datos;
		if ($datos == 0) {
			$texto = 'No hay información';
		}
		return $texto;
	}

	public function data_null_date($dato,$etapa,$flujo_id){
		$stateFlujo 				= $this->__ProspectiveUser->get_stateFlow_flujo($flujo_id);
		$texto 						= $dato;
		if ($etapa != '') {
			$texto = $dato.' '.'<a href="javascript:void(0)" data-toggle="tooltip" title="Comparación entre el tiempo límite y el tiempo en el que se atendió" class="btn_find_data" data-etapa="'.$etapa.'" data-uid="'.$flujo_id.'" data-atendido="'.$dato.'"><i class="fa fa-question"></i></a>';
		}
		if ($dato == '0000-00-00 00:00:00') {
			if ($stateFlujo == Configure::read('variables.control_flujo.flujo_cancelado')) {
				$texto = '<i class="fa fa-times"></i>';
			} else {
				$texto = '<i class="fa fa-minus"></i>';
			}
		}
		return $texto;
	}

	public function id_empresa($contacto_id){// Devuelve el id de la empresa a la que pertenece el contacto
		$empresa_id 	= $this->__ContacsUser->find_id_bussines($contacto_id);
		return $empresa_id['ContacsUser']['clients_legals_id'];
	}

	public function idUser_asigno_flujo($prospective_id){
		return $this->__ProspectiveUser->get_userReceptor_flujo($prospective_id); 
	}

	public function name_prospective($prospective_id, $infoCliente = null){//Devuelve el nombre del propecto, sea juridico o natural
		$datos 				= $this->__ProspectiveUser->get_data($prospective_id);
		if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->findById($datos['ProspectiveUser']['contacs_users_id']);
			$nombre 		= $datosC['ContacsUser']['name'];
			if (!is_null($infoCliente)) {
				$nombre.= " - ".$datosC["ClientsLegal"]["name"];
			}
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
			$nombre 		= $datosC['ClientsNatural']['name'];
		}
		return $nombre;
	}

	public function name_prospective_dni($prospective_id){//Devuelve el nombre del propecto, sea juridico o natural
		$dni = "0";
		if ($prospective_id == 0) {
			$dni 			= '0';
		} else {
			$datos 				= $this->__ProspectiveUser->get_data($prospective_id);
			if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
				$datosC 		= $this->__ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
				$dni 		= isset($datosC['ClientsLegal']['nit']) ? $datosC['ClientsLegal']['nit'] : "0";
				// $dni 		= $datosC['ClientsLegal']['name'];

			} elseif(!is_null($datos['ProspectiveUser']['clients_natural_id'])) {
				$datosC 		= $this->__ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
				$dni 		= $datosC['ClientsNatural']['identification'];
			}
		}

		$partsSpace = explode(" ", $dni);

		if (count($partsSpace) > 1) {
			$dni = $partsSpace[0];
		}

		$partsPlus = explode("+", $dni);

		if (count($partsPlus) > 1) {
			$dni = $partsPlus[0];
		}
		return trim($dni);
	}

	public function name_prospective_contact($prospective_id){//Devuelve el nombre del propecto, sea juridico o natural
		if (is_null($prospective_id)) {
			return "";
		}
		$nombre = "Nombre sin gestión";
		if ($prospective_id == 0) {
			$nombre 			= 'Importacion solicitada';
		} else {
			$datos 				= $this->__ProspectiveUser->get_data($prospective_id);
			if (!empty($datos)) {
				if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
					$datosC 		= $this->__ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
					$nombre 		= isset($datosC['ClientsLegal']['name']) ? $datosC['ClientsLegal']['name'].' - '.$datosC['ContacsUser']['name'] : "";
					// $nombre 		= $datosC['ClientsLegal']['name'];

				} elseif(!is_null($datos['ProspectiveUser']['clients_natural_id'])) {
					$datosC 		= $this->__ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
					$nombre 		= $datosC['ClientsNatural']['name'];
				}
			}			
		}
		return $nombre;
	}

	public function name_client_contact_services($service_id){
		$datos 				= $this->__TechnicalService->get_data($service_id);
		if ($datos['TechnicalService']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->get_data_modelos($datos['TechnicalService']['contacs_users_id']);
			$nombre 		= $datosC['ClientsLegal']['name'].' - '.$datosC['ContacsUser']['name'];
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['TechnicalService']['clients_natural_id']);
			$nombre 		= $datosC['ClientsNatural']['name'];
		}
		return $this->data_null($nombre);
	}

	public function telephone_client_contact_services($service_id){
		$datos 				= $this->__TechnicalService->get_data($service_id);
		if ($datos['TechnicalService']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->get_data($datos['TechnicalService']['contacs_users_id']);
			$nombre 		= 'Tel: '.$datosC['ContacsUser']['telephone'].', cel:'.$datosC['ContacsUser']['cell_phone'];
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['TechnicalService']['clients_natural_id']);
			$nombre 		= 'Tel: '.$datosC['ClientsNatural']['telephone'].', cel:'.$datosC['ClientsNatural']['cell_phone'];

		}
		return $this->data_null($nombre);
	}

	public function telephone_client_contact_prospective($prospective_id){
		$datos 				= $this->__ProspectiveUser->get_data($prospective_id);
		if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->get_data($datos['ProspectiveUser']['contacs_users_id']);
			$nombre 		= 'Tel: '.$datosC['ContacsUser']['telephone'].', cel:'.$datosC['ContacsUser']['cell_phone'];
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
			$nombre 		= 'Tel: '.$datosC['ClientsNatural']['telephone'].', cel:'.$datosC['ClientsNatural']['cell_phone'];

		}
		return $this->data_null($nombre);
	}

	public function city_client_contact_services($service_id){
		$datos 				= $this->__TechnicalService->get_data($service_id);
		if ($datos['TechnicalService']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->get_data($datos['TechnicalService']['contacs_users_id']);
			$nombre 		= $datosC['ContacsUser']['city'];
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['TechnicalService']['clients_natural_id']);
			$nombre 		= $datosC['ClientsNatural']['city'];
		}
		return $this->data_null($nombre);
	}

	public function cellphone_client_contact_services($service_id){
		$datos 				= $this->__TechnicalService->get_data($service_id);
		if ($datos['TechnicalService']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->get_data($datos['TechnicalService']['contacs_users_id']);
			$nombre 		= $datosC['ContacsUser']['cell_phone'];
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['TechnicalService']['clients_natural_id']);
			$nombre 		= $datosC['ClientsNatural']['cell_phone'];
		}
		return $this->data_null($nombre);
	}

	public function email_client_contact_services($service_id){
		$datos 				= $this->__TechnicalService->get_data($service_id);
		if ($datos['TechnicalService']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->get_data($datos['TechnicalService']['contacs_users_id']);
			$nombre 		= $datosC['ContacsUser']['email'];
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['TechnicalService']['clients_natural_id']);
			$nombre 		= $datosC['ClientsNatural']['email'];
		}
		return $this->data_null($nombre);
	}


	public function find_email_user($natural_id,$contac_id){//Devuelve el correo del usuario que solicito el flujo
		$user_id = $natural_id;
		$natural = true;
		if ($natural_id == 0) {
			$natural = false;
			$user_id = $contac_id;
		}
		if ($natural) {
			$email_user 	= $this->__ClientsNatural->get_data_email($user_id);
		} else {
			$email_user 	= $this->__ContacsUser->get_data_email($user_id);
		}
		return $email_user;
	}

	public function find_date_state_finish($state,$fecha_fin){//Devuelve la fecha si el flujo a sido terminado
		$fecha 			= '';
		if ($state >=  Configure::read('variables.control_flujo.flujo_cancelado')) {
			$jornal                     = split("-",$fecha_fin);
			$dia 						= (int) ($jornal[2] + 1);
			if ($dia < 10) {
				$dia = '0'.$dia;
			}
			$fecha 		= $jornal[0].'-'.$jornal[1].'-'.$dia;
		}
		return $fecha;
	}

	public function type_client($prospective_id){//Devuelve si el cliente es juridico o natural
		$datos 				= $this->__ProspectiveUser->get_data($prospective_id);
		if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->get_data($datos['ProspectiveUser']['contacs_users_id']);
			$nombre 		= 'Cliente jurídico';
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
			$nombre 		= 'Cliente natural';
		}
		return $nombre;
	}

	public function get_clientData($prospective_id){
		$id 				= null;
		$datos 				= $this->__ProspectiveUser->get_data($prospective_id);
		if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->get_data($datos['ProspectiveUser']['contacs_users_id']);
			$type 	 		= 'legal';
			$id 			= $datosC["ContacsUser"]["id"];
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
			$type 	 		= 'narural';
			$id 			= $datosC["ClientsNatural"]["id"];
		}
		return compact("id","type");
	}

	public function find_reason_prospective($prospective_id){
		$datos 			= $this->__FlowStage->find_reason_prospective($prospective_id);
		return $datos;
	}

	public function city_prospective($prospective_id){ //Ciudad del cliente
		$datos 				= $this->__ProspectiveUser->get_data($prospective_id);
		if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->get_data($datos['ProspectiveUser']['contacs_users_id']);
			$nombre 		= isset($datosC['ContacsUser']) ? $datosC['ContacsUser']['city'] : "";
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
			$nombre 		= isset($datosC['ClientsNatural']) ? $datosC['ClientsNatural']['city'] : "";
		}
		return $nombre;
	}

	public function find_asesor_prospective($prospective_id){ //Ciudad del cliente
		$datos 				= $this->__ProspectiveUser->get_data($prospective_id);
		return $datos['ProspectiveUser']['user_id'];
	}

	public function codigoPaisWhatsapp($nacionalidad){
		$nombre 		= $nacionalidad;
		$porciones 		= explode(",", $nacionalidad);
		$paisWs 		= trim($porciones[count($porciones) - 1]);
		return $this->findCodigoPaisWhatsapp(mb_strtolower($paisWs));
	}

	public function findCodigoPaisWhatsapp($pais){
		switch ($pais) {
			case 'Colombia':
			case 'colombia':
				$codigo = '57';
				break;
			case 'méxico':
				$codigo = '52';
				break;
			case 'mexico':
				$codigo = '52';
				break;
			case 'ecuador':
				$codigo = '593';
				break;
			case 'perú':
				$codigo = '51';
				break;
			case 'peru':
				$codigo = '51';
				break;
			case 'el salvador':
				$codigo = '503';
				break;
			case 'honduras':
				$codigo = '504';
				break;
			case 'panama':
				$codigo = '507';
				break;
			case 'panamá':
				$codigo = '507';
				break;
			case 'ee.uu.':
				$codigo = '1';
				break;
			case 'argentina':
				$codigo = '54';
				break;
			case 'uruguay':
				$codigo = '598';
				break;
			case 'paraguay':
				$codigo = '595';
				break;
			case 'venezuela':
				$codigo = '58';
				break;
			case 'bolivia':
				$codigo = '591';
				break;
			case 'aruba':
				$codigo = '297';
				break;
			case 'costa rica':
				$codigo = '506';
				break;
			case 'nicaragua':
				$codigo = '505';
				break;
			case 'guatemala':
				$codigo = '502';
				break;
			case 'república dominicana':
				$codigo = '1';
				break;
			case 'republica dominicana':
				$codigo = '1';
				break;

			default:
				$codigo = '57';
				break;
		}
		return $codigo;
	}

	public function explode_city($nacionalidad, $country = "Colombia"){ // Dividir la nacionalidad del asesor, y retornar la ciudad
		$nombre 		= $nacionalidad;
		$porciones 		= explode(",", $nacionalidad);
		if (isset($porciones[1])) {
			$nombre = $porciones[0];
		}
		if($country == "Colombia"){
			return $nombre;
		}else{
			return "Miami, Florida";
		}
	}

	public function name_client_contact($id_contact_user){ //Devulve el nombre del contacto que solícito el servicio
		$datos = $this->__ContacsUser->get_data($id_contact_user);
		return $datos['ContacsUser']['name'] . ( isset($datos["Concession"]["id"]) && !empty($datos["Concession"]["id"]) ? " | <b>Concesión: </b>".$datos["Concession"]["name"]."" : '' ) ;
	}

	public function name_bussines($empresa_id){ //Devuelve el nombre de la empresa
		$datos = $this->__ClientsLegal->get_data($empresa_id);
		return $datos['ClientsLegal']['name'];
	}

	public function validate_state_notifications($estado){ //Devuelve el estado de la notificación
		$state 			= 'Sin leer';
		if ($estado == '1') {
			$state 		= 'Vista';
		}
		return $state;
	}

	public function explode_date_time_db($fecha){
		$porciones 		= explode(" ", $fecha);
		return $this->format_time_pm_am($porciones[1]);
	}

	public function format_time_pm_am($horas){
		$porciones 			= explode(":", $horas);
		$hora               = (int) $porciones[0];
        if ($hora <= 12) {
        	$time = $horas.' AM';
        } else {
        	$time = $this->horaPM($porciones[0]).':'.$porciones[1].' PM';
        }
		return $time;
	}

	PUBLIC function horaPM($hora){
		switch ($hora){
			case 13:
				$horaPM = '01';
				break;
			case 14:
				$horaPM = '02';
				break;
			case 15:
				$horaPM = '03';
				break;
			case 16:
				$horaPM = '04';
				break;
			case 17:
				$horaPM = '05';
				break;
			case 18:
				$horaPM = '06';
				break;
			case 19:
				$horaPM = '07';
				break;
			case 20:
				$horaPM = '08';
				break;
			case 21:
				$horaPM = '09';
				break;
			case 22:
				$horaPM = '10';
				break;
			case 23:
				$horaPM = '11';
				break;
			case 24:
				$horaPM = '12';
				break;
		}

		return $horaPM;
	}

	public function find_action_log_user($action,$model){
		switch ($action) {
		    case 1:
		        $nombre = 'Gestionar flujo';
		        break;
		    case 2:
		        $nombre = 'Registro';
		        break;
		    case 3:
		        $nombre = 'Editó';
		        break;
		    case 4:
		        $nombre = 'El usuario cerró sesión';
		        break;
		    case 5:
		        $nombre = 'El usuario inició sesión';
		        break;
		    case 6:
		        $nombre = 'Envió';
		        break;
		    case 7:
			    if ($model == 'Quotation') {
			    	$nombre = 'Eliminó';
			    } else  {
			    	$nombre = 'Habilitó o deshabilitó';
			    }
		        break;
		    case 8:
		        $nombre = 'Guardó borrador de';
		        break;
		    case 9:
		        $nombre = 'Confirmó el pago';
		        break;
		   	case 10:
		        $nombre = 'No confirmó el pago';
		        break;
		    case 11:
		        $nombre = 'Cancelación';
		        break;
		    case 12:
		        $nombre = 'Cambió de asignación';
		        break;

		    default:
		        $nombre = 'Null';
		        break;
		}
		return $nombre;
	}

	public function find_model_log_user($model,$action){
		if ($action == 6) {
			$nombre = 'una cotización';
		} else {
			if ($action == 4 || $action == 5) {
				$nombre 			= '';
			} else {
				switch ($model) {
				    case 'ClientsNatural':
				        $nombre = 'de cliente natural';
				        break;
				    case 'ClientsLegal':
				        $nombre = 'de cliente jurídico';
				        break;
				    case 'ContacsUser':
				        $nombre = 'de contacto';
				        break;
				    case 'User':
				        $nombre = 'de usuario';
				        break;
				    case 'Product':
				        $nombre = 'el producto';
				        break;
				    case 'ProspectiveUser':
				    	$nombre = 'del requerimiento: ';
				    	break;
				   	case 'FlowStage':
				    	$nombre = 'etapa del flujo';
				    	break;
				    case 'Quotation':
				    	$nombre = 'una cotización';
				    	break;
				    case 'TechnicalService':
				    	$nombre = 'una servicio técnico';
				    	break;

				   	default:
				        $nombre = '';
				        break;
				}
			}
		}
		return $nombre;
	}

	public function find_data_id_logs($id,$model,$action){
		$nombre 			= '';
		if ($model == 'TechnicalServices') {
			$model = 'TechnicalService';
		}
		$modelFind  		= '__'.$model;
		if ($id != 0) {
			if ($action == 7 || $action == 9 || $action == 10) {
				if ($model == 'User') {
					$datos 				= $this->$modelFind->get_data($id);
					$position 			= $datos[$model];
					$nombre 			= ': '.$position['name'];
				} else {
					if ($action != 7) {
						$datosP = $this->__FlowStage->get_data($id);
						', '.$nombre = $this->__FlowStage->find_reason_prospective($datosP['FlowStage']['prospective_users_id']);
					}
				}
			} else {
				if ($model == 'ProspectiveUser') {
					', '.$nombre = $this->__FlowStage->find_reason_prospective($id);
				} else {
					$datos 				= $this->$modelFind->get_data($id);
					$position = $datos[$model];
					if ($model == 'FlowStage') {
						$existe = $this->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$position['document']);
						if ($existe) {
							$nombre 			= ': '.$position['nameDocument'];
						} else {
							$datosQ 			= $this->__Quotation->get_data($position['document']);
							$nombre 			= ': '.$datosQ['Quotation']['name'];
						}
					} else {
						if ($model == 'TechnicalService') {
							$nombre 			= ', Servicio técnico';
						} else {
							$nombre 			= ': '.$position['name'];
						}
					}
				}
			}
		}
		return $nombre;
	}

	public function flowsTage_id_find_prospective($flowStage_id){
		$datos 					= $this->__FlowStage->get_data($flowStage_id);
		$prospective_id 		= $datos['FlowStage']['prospective_users_id'];
		return $this->__FlowStage->find_reason_prospective($prospective_id);
	}

	public function name_state_flujo($state){ //Devuelve el nombre del estado en el que se encuentra el flujo
		switch ($state) {
		    case 1:
		        $nombre = 'Asignado';
		        break;
		    case 2:
		        $nombre = 'Contactado';
		        break;
		    case 3:
		        $nombre = 'Cotizado';
		        break;
		    case 4:
		        $nombre = 'Negociado';
		        break;
		    case 5:
		        $nombre = 'Pagado';
		        break;
		    case 6:
		        $nombre = 'Despachado';
		        break;

		    default:
		        $nombre = 'Null';
		        break;
		}
		return $nombre;
	}

	public function validate_flujo_Prospective($prospective_id){
		return $this->__FlowStage->count_data_bussines($prospective_id);
	}

	public function number_novedades($id){
		return $this->__News->number_novedades($id);
	}

	public function name_state_product_import($state_import){
		$tate 				= '';
		switch ($state_import) {
			case '1':
				$state 		= 'Sin importación'; 
				break;
			case '2':
				$state 		= 'Solicitud de importación';
				break;
			case '3':
				$state 		= 'Enviado';
				break;
			case '4':
				$state 		= 'Entregado';
				break;
			case '5':
				$state 		= 'Importación en proceso';
				break;
			case '6':
				$state 		= 'Importación finalizada';
				break;
		}
		return $state;
	}

	public function validate_state_orden($state){
		$flujo 		= 'disabled';
		if ($state > Configure::read('variables.control_importacion.solicitud_importacion')) {
			$flujo = 'complete';
		}elseif($state === Configure::read('variables.control_importacion.orden_montada')){
			$flujo = "active";
		}
		return $flujo;
	}


	public function validate_state_proveedor($state){
		$flujo 		= 'disabled';
		if ($state > Configure::read('variables.control_importacion.orden_montada')) {
			$flujo = 'complete';
		}elseif($state === Configure::read('variables.control_importacion.despacho_proveedor')){
			$flujo = "active";
		}
		return $flujo;
	}


	public function validate_state_miami($state){
		$flujo 		= 'disabled';
		if ($state > Configure::read('variables.control_importacion.despacho_proveedor')) {
			$flujo = 'complete';
		}elseif($state === Configure::read('variables.control_importacion.llegada_miami')){
			$flujo = "active";
		}
		return $flujo;
	}

	public function validate_state_amerimpex($state){
		$flujo 		= 'disabled';
		if ($state > Configure::read('variables.control_importacion.llegada_miami')) {
			$flujo = 'complete';
		}elseif($state === Configure::read('variables.control_importacion.despacho_amerimpex')){
			$flujo = "active";
		}
		return $flujo;
	}

	public function validate_state_nacionalizacion($state){
		$flujo 		= 'disabled';
		if ($state > Configure::read('variables.control_importacion.despacho_amerimpex')) {
			$flujo = 'complete';
		}elseif($state === Configure::read('variables.control_importacion.despacho_amerimpex')){
			$flujo = "active";
		}
		return $flujo;
	}
	public function validate_state_asignado($state){
		$flujo 		= 'active';
		if ($state > Configure::read('variables.control_flujo.flujo_asignado')) {
			$flujo = 'complete';
		}
		return $flujo;
	}

	public function validate_state_contactado($state){
		switch ($state) {
		    case ($state > Configure::read('variables.control_flujo.flujo_contactado')):
		        $flujo = 'complete';
		        break;
		    case ($state < Configure::read('variables.control_flujo.flujo_contactado')):
		        $flujo = 'disabled';
		        break;
		    case Configure::read('variables.control_flujo.flujo_contactado'):
		        $flujo = 'active';
		        break;

		    default:
		        $flujo = 'Null';
		        break;
		}
		return $flujo;
	}

	public function validate_state_cotizado($prospective_id,$state){
		switch ($state) {
		    case ($state > Configure::read('variables.control_flujo.flujo_cotizado')):
		    	$find_state = $this->validate_flujo_Prospective($prospective_id);
		    	if ($find_state >= Configure::read('variables.control_flujo.flujo_cotizado')) {
		    		$flujo = 'complete';
		    	} else {
		        	$flujo = 'disabled';
		    	}
		        break;
		    case ($state < Configure::read('variables.control_flujo.flujo_cotizado')):
		        $flujo = 'disabled';
		        break;
		    case Configure::read('variables.control_flujo.flujo_cotizado'):
		        $flujo = 'active';
		        break;

		    default:
		        $flujo = 'Null';
		        break;
		}
		return $flujo;
	}

	public function validate_state_negociado($prospective_id,$state){
		switch ($state) {
		    case ($state > Configure::read('variables.control_flujo.flujo_negociado')):
		        $find_state = $this->validate_flujo_Prospective($prospective_id);
		    	if ($find_state >= Configure::read('variables.control_flujo.flujo_negociado')) {
		    		$flujo = 'complete';
		    	} else {
		        	$flujo = 'disabled';
		    	}
		        break;
		    case ($state < Configure::read('variables.control_flujo.flujo_negociado')):
		        $flujo = 'disabled';
		        break;
		    case Configure::read('variables.control_flujo.flujo_negociado'):
		        $flujo = 'active';
		        break;

		    default:
		        $flujo = 'Null';
		        break;
		}
		return $flujo;
	}

	public function validate_state_pagado($prospective_id,$state){
		switch ($state) {
		    case ($state > Configure::read('variables.control_flujo.flujo_pagado')):
		        $find_state = $this->validate_flujo_Prospective($prospective_id);
		    	if ($find_state >= Configure::read('variables.control_flujo.flujo_pagado')) {
		    		$flujo = 'complete';
		    	} else {
		        	$flujo = 'disabled';
		    	}
		        break;
		    case ($state < Configure::read('variables.control_flujo.flujo_pagado')):
		        $flujo = 'disabled';
		        break;
		    case Configure::read('variables.control_flujo.flujo_pagado'):
		        $flujo = 'active';
		        break;

		    default:
		        $flujo = 'Null';
		        break;
		}
		return $flujo;
	}

	public function validate_state_despachado($prospective_id,$state){
		switch ($state) {
		    case ($state > Configure::read('variables.control_flujo.flujo_despachado')):
		        $find_state = $this->validate_flujo_Prospective($prospective_id);
		    	if ($find_state >= Configure::read('variables.control_flujo.flujo_despachado')) {
		    		$flujo = 'complete';
		    	} else {
		        	$flujo = 'disabled';
		    	}
		        break;
		    case ($state < Configure::read('variables.control_flujo.flujo_despachado')):
		        $flujo = 'disabled';
		        break;
		    case Configure::read('variables.control_flujo.flujo_despachado'):
		        $flujo = 'active';
		        break;

		    default:
		        $flujo = 'Null';
		        break;
		}
		return $flujo;
	}

	public function validate_state_finish($estado){
		$texto = '';
		$arrayEstados 		= array(Configure::read('variables.control_flujo.flujo_cancelado'),Configure::read('variables.control_flujo.flujo_no_valido'));
		if (in_array($estado, $arrayEstados)) {
			$texto 			= 'terminado';
		}
		return $texto;
	}

	public function date_castellano($fecha){ //Formato de fecha en español
		$fecha = trim($fecha);
		if ($fecha == '0000-00-00' || $fecha == '0000-00-00 00:00:00' || empty($fecha)) {
			$nombre = 'No hay información';
		} else {
			$fechaFinal 	= explode(' ', $fecha);
			$fecha 			= substr($fechaFinal[0], 0, 10);
			$numeroDia 		= date('d', strtotime($fecha));
			$dia 			= date('l', strtotime($fecha));
			$mes 			= date('F', strtotime($fecha));
			$anio 			= date('Y', strtotime($fecha));
			$dias_ES 		= array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
			$dias_EN 		= array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
			$nombredia 		= str_replace($dias_EN, $dias_ES, $dia);
			$meses_ES 		= array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$meses_EN 		= array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
			$nombreMes 		= str_replace($meses_EN, $meses_ES, $mes);
			if (isset($fechaFinal[1])) {
				$nombre 		= $nombredia.", ".$numeroDia."/".$nombreMes."/".$anio.' '.$fechaFinal[1];
			} else {
				$nombre 		= $nombredia.", ".$numeroDia."/".$nombreMes."/".$anio;
			}
		}
		return $nombre;
	}

	public function date_castellano2($fecha){ //Formato de fecha en español
		$fecha = trim($fecha);
		if ($fecha == '0000-00-00' || $fecha == '0000-00-00 00:00:00' || empty($fecha)) {
			$nombre = 'No hay información';
		} else {
			$fechaFinal 	= explode(' ', $fecha);
			$fecha 			= substr($fechaFinal[0], 0, 10);
			$numeroDia 		= date('d', strtotime($fecha));
			$dia 			= date('l', strtotime($fecha));
			$mes 			= date('F', strtotime($fecha));
			$anio 			= date('Y', strtotime($fecha));
			$dias_ES 		= array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
			$dias_EN 		= array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
			$nombredia 		= str_replace($dias_EN, $dias_ES, $dia);
			$meses_ES 		= array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$meses_EN 		= array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
			$nombreMes 		= str_replace($meses_EN, $meses_ES, $mes);
			if (isset($fechaFinal[1])) {
				$nombre 		= $numeroDia."/".$nombreMes."/".$anio.' '.$fechaFinal[1];
			} else {
				$nombre 		= $numeroDia."/".$nombreMes."/".$anio;
			}
		}
		return $nombre;
	}


	public function date_castellano3($fecha){ //Formato de fecha en español
		$fecha = trim($fecha);
		if ($fecha == '0000-00-00' || $fecha == '0000-00-00 00:00:00' || empty($fecha)) {
			$nombre = 'No hay información';
		} else {
			$fechaFinal 	= explode(' ', $fecha);
			$fecha 			= substr($fechaFinal[0], 0, 10);
			$numeroDia 		= date('d', strtotime($fecha));
			$dia 			= date('l', strtotime($fecha));
			$mes 			= date('F', strtotime($fecha));
			$anio 			= date('Y', strtotime($fecha));
			$dias_ES 		= array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
			$dias_EN 		= array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
			$nombredia 		= str_replace($dias_EN, $dias_ES, $dia);
			$meses_ES 		= array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$meses_EN 		= array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
			$nombreMes 		= str_replace($meses_EN, $meses_ES, $mes);
			if (isset($fechaFinal[1])) {
				$nombre 		= $numeroDia."/".$nombreMes."/".$anio;
			} else {
				$nombre 		= $numeroDia."/".$nombreMes."/".$anio;
			}
		}
		return $nombre;
	}	

	public function day_month_castellano($fecha){
		$fecha 			= substr($fecha, 0, 10);
		$numeroDia 		= date('d', strtotime($fecha));
		$dia 			= date('l', strtotime($fecha));
		$mes 			= date('F', strtotime($fecha));
		$dias_ES 		= array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
		$dias_EN 		= array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
		$nombredia 		= str_replace($dias_EN, $dias_ES, $dia);
		$meses_ES 		= array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$meses_EN 		= array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		$nombreMes 		= str_replace($meses_EN, $meses_ES, $mes);
		$nombre 		= $numeroDia." de ".$nombreMes;
		return $nombre;
	}

	public function mes_castellano($mes){
		$mes = (int) $mes;
		switch ($mes) {
		    case 1:
		        $mesNombre 	= 'Ene.';
		        break;
		    case 2:
		        $mesNombre 	= 'Feb.';
		        break;
		    case 3:
		        $mesNombre 	= 'Mar.';
		        break;
		    case 4:
		        $mesNombre 	= 'Abr.';
		        break;
		    case 5:
		        $mesNombre 	= 'May.';
		        break;
		    case 6:
		        $mesNombre 	= 'Jun.';
		        break;
		    case 7:
		        $mesNombre 	= 'Jul.';
		        break;
		    case 8:
		        $mesNombre 	= 'Ago.';
		        break;
		    case 9:
		        $mesNombre 	= 'Sept.';
		        break;
		    case 10:
		        $mesNombre 	= 'Oct.';
		        break;
		    case 11:
		        $mesNombre 	= 'Nov.';
		        break;
		    case 12:
		        $mesNombre 	= 'Dic.';
		        break;

		    default:
		        $mesNombre	= '';
		        break;
		}
		return $mesNombre;
	}

	public function flujo_cotizado($cotizacion){ // Muestra el deber del asesor en cuanto mandar lacotizacion al cliente
		$cotizar 		= 'Enviar cotización';
		if ($cotizacion == '0') {
			$cotizar 	= 'No enviar cotización';
		}
		return $cotizar;
	}

	public function cotizacion_enviada($envio){ // Muestra el estado del envio  de la cotizacion al cliente
		switch ($envio) {
		    case 0:
		        $cotizar 	= 'Por cotizar';
		        break;
		    case 1:
		        $cotizar 	= 'Enviada';
		        break;
		    case 2:
		        $cotizar 	= 'Enviar una nueva cotización';
		        break;

		    default:
		        $cotizar 	= 'Enviada';
		        break;
		}
		return $cotizar;
	}

	public function check_state_flow_stage($state){ //Muestra el estado que se encuentra cada  etapa del flujo
		switch ($state) {
		    case 0:
		        $cotizar 	= 'Flujo terminado';
		        break;
		    case 1:
		        $cotizar 	= 'Pago aceptado';
		        break;
		    case 2:
		        $cotizar 	= 'Esperando respuesta de Contabilidad';
		        break;
		    case 3:
		        $cotizar 	= 'Pago aceptado';
		        break;
		    case 4:
		        $cotizar 	= 'Pago no aceptado';
		        break;
		    case 5:
		        $cotizar 	= 'Esperando la confirmación del pago en su totalidad';
		        break;
		    case 7:
		        $cotizar 	= 'Pago aceptado';
		        break;

		    default:
		        $cotizar 	= '';
		        break;
		}
		return $cotizar;
	}

	public function check_state_prospective($state){ // Muestra el estado en el que fue finalizado el flujo
		switch ($state) {
		    case Configure::read('variables.control_flujo.flujo_cancelado'):
		        $cotizar 	= 'Flujo cancelado';
		        break;
		    case Configure::read('variables.control_flujo.flujo_finalizado'):
		        $cotizar 	= 'Flujo terminado';
		        break;

		    default:
		        $cotizar 	= '';
		        break;
		}
		return $cotizar;
	}

	public function check_state_prospective_despacho($state){ // Muestra el estado en el que se encuentra el despacho
		switch ($state) {
		    case Configure::read('variables.control_flujo.flujo_despachado'):
		        $despacho 	= '<a href="javascript:void(0)" >Pedido enviado <i class="fa fa-cube"></i></a>';
		        break;
		    case Configure::read('variables.control_flujo.flujo_finalizado'):
		        $despacho 	= '<a href="javascript:void(0)">Entregado <i class="fa fa-check entregd"></i></a>';
		        break;

		    default:
		        $despacho = '';
		        break;
		}
		return $despacho;
	}

	public function check_state_prospective_despacho_logistic($state){ // Muestra el estado en el que se encuentra el despacho
		switch ($state) {
		    case Configure::read('variables.control_flujo.flujo_finalizado'):
		        $despacho 	= '<a href="javascript:void(0)">Entregado <i class="fa fa-check entregd"></i></a>';
		        break;

		    default:
		        $despacho 	= '';
		        break;
		}
		return $despacho;
	}

	public function flujo_contactado_user($user_id){
		$usuario = '';
		$datos 			= $this->__ContacsUser->get_data($user_id);
		if (isset($datos['ContacsUser']['name'])) {
			$usuario 		= $datos['ContacsUser']['name'];
		} else {
			$usuario		= $user_id;
		}
		return $usuario;
	}

	public function name_state_model($state){ // Nombre del estado en el que se encuentra el registro
		$estado 		= 'Inhabilitado';
		if ($state == '1') {
			$estado 	= 'Habilitado';
		}
		return $estado;
	}

	public function paint_state_model($state,$model_id){ // Pintar el boton para habilitar o inhabilitar el registro del modelo
		$boton = '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Habilitar" data-uid="'.$model_id.'" class="btn_deshabilitar border-0 p-0 btn btn-primary"><i class="fa vtc fa-lock"></i></a>';
		if ($state == '1') {
			$boton = '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Inhabilitar" data-uid="'.$model_id.'" class="btn_habilitar border-0 p-0 btn btn-primary"><i class="fa vtc fa-unlock"></i></a>';
		}
		return $boton;
	}

	public function paint_edit_model_modal($model_id,$model){ // Pintar el boton para editar atra vez de un modal
		return '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar '.$model.'" class="btn_editar btn btn-primary border-0 p-0"  data-uid="'.$model_id.'"><i class="fa fa-pencil vtc text-white"></i></a>';
	}

	public function identify_contact($contact_id,$id_contact_flujo,$name){ // Identificar el contacto del cliente juridico que solícito el flujo
		$nombre = $name;
		if ($contact_id == $id_contact_flujo) {
			$nombre = $name.'*';
		}
		return $nombre;
	}

	public function identify_contact2($contact_id,$id_contact_flujo,$name){ // Identificar el contacto del cliente juridico que solícito el flujo
		$nombre = $name;
		if ($contact_id == $id_contact_flujo) {
			$nombre = 'show';
		}
		return $nombre;
	}

	public function validate_image_products($imagen){ // Validar si la imagen es nula, modelo de productos
		$ruta = $imagen;
		if ($imagen == '') {
			$ruta = "default.jpg";
		}
		return $ruta;
	}

	public function total_item_products_quotations($cantidad,$precio){
		$precio_final = str_replace('.', ',', $precio);
		$precio_array = split(",",$precio_final);
		$total = $cantidad * $precio_array[0];
		if (isset($precio_array[1])) {
			$total = $total.','.$precio_array[1];
		}
		return number_format((int)$total,0,",",".");
	}

	public function total_item_products_quotations2($cantidad,$precio){

		$total = $cantidad * $precio;
		return number_format((float)$total,2,",",".");
	}

	public function total_visible_quotations($total,$condicion){
		$mostrar = '';
		$totalParts = explode(",", $total);
		if ($condicion == 1) {
			$mostrar = 'Total: $'.$totalParts[0]."<span class='decimales text-danger'>,".$totalParts[1]."</span>";
		}
		return $mostrar;
	}

	public function valid_day_Sale($date_yesterday_sales){
		$fechaAyer 				= date('Y-m-d', strtotime('-1 day'));
		if (date($date_yesterday_sales) == $fechaAyer) {
			$texto 				= 'Ayer';
		} else {
			$texto 				= $this->day_month_castellano(date($date_yesterday_sales));
		}
		return $texto;
	}

	public function validate_existence_flow_notes($flujo_id){
		$resultado 			= '';
		$count_notas 		= $this->__ProgresNote->count_notes_flujo($flujo_id);
		if ($count_notas > 0) {
			$resultado 		= '<a href="javascript:void(0)" class="btn_notas_flujo" data-uid="'.$flujo_id.'" data-toggle="tooltip" title="Ver notas"><i class="fa fa-comments"></i><span class="indicator" id="count_notificaciones">'.$count_notas.'</span></a>';
		}
		return $resultado;
	}

	public function calculate_time_arrival($dias_aproximados,$fecha_solicitud){
		$fecha_solicitud 					= strtotime($fecha_solicitud);
		$Segundos 							= 0;
		$FechaFinal 						= date("Y-m-d");
		if ($dias_aproximados == 'Inmediato') {
			$dias 		= 0;
		} else if($dias_aproximados == '1-2 días hábiles'){
			$dias 		= 2;
		} else if ($dias_aproximados == '2-3 días hábiles') {
			$dias 		= 3;
		} else if ($dias_aproximados == '4-5 días hábiles') {
			$dias 		= 5;
		} else if ($dias_aproximados == '10-12 días hábiles') {
			$dias 		= 12;
		} else if ($dias_aproximados == '12-15 días hábiles'){
			$dias 		= 15;
		} else if ($dias_aproximados == '15-20 días hábiles') {
			$dias 		= 20;
		} else if ($dias_aproximados == '20-30 días hábiles') {
			$dias 		= 30;
		} else if ($dias_aproximados == '30-45 días hábiles') {
			$dias 		= 45;
		} else {
			$dias 		= 60;
		}
        for ($i=0; $i<$dias; $i++) {  
            $Segundos = $Segundos + 86400;  
            $caduca = date("D",time()+$Segundos);
            if ($caduca == "Sat"){  
                $i--;  
            } else if ($caduca == "Sun"){  
                $i--;  
            } else {
                $FechaFinal = date("Y-m-d",time()+$Segundos);
            }  
        }
        return $this->validateFestivo($FechaFinal);
	}

	public function validateFestivo($fecha){
        $fecha_entro        = 0;
        $Segundos           = 0;
        $arrayFestivos      = Configure::read('variables.diasFestivos');
        foreach ($arrayFestivos as $val) {
            if ($fecha == $val) {
                $Segundos = $Segundos + 86400;
                $fecha = date("Y-m-d",time()+$Segundos);
            }
        }
        return $fecha;
    }

    public function return_hours_in_days($horas){
    	return $dias = $horas / 9;
    }

    public function email_prospective_contact($prospective_id){//Devuelve el nombre del propecto, sea juridico o natural
		$datos 				= $this->__ProspectiveUser->get_data($prospective_id);
		if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
			$datosC 		= $this->__ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
			$nombre 		= $datosC['ClientsLegal']['name'].' - '.$datosC['ContacsUser']['email'];
		} else {
			$datosC 		= $this->__ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
			$nombre 		= $datosC['ClientsNatural']['email'];
		}
		return $nombre;
	}

	public function find_state_import($state){
		switch ($state) {
		    case Configure::read('variables.importaciones.proceso'):
		        $estado = 'En proceso';
		        break;
		    case Configure::read('variables.importaciones.finalizadas'):
		        $estado = 'Finalizado';
		        break;
		    case Configure::read('variables.importaciones.solicitud'):
		        $estado = 'Esperando aprobación';
		        break;
		    case Configure::read('variables.importaciones.rechazado'):
		        $estado = 'Rechazado';
		        break;
		}
		return $estado;
	}

	public function count_importaciones_revisiones(){
		$nacional =  $this->__Import->count_importaciones_revisiones();
		$internacional =  $this->__Import->count_importaciones_revisiones(1);

		return "NAC ($nacional) / INT ($internacional)<br> ";
	}

	public function count_imports_approved(){
		$nacional =  $this->__Import->count_imports_approved();
		$internacional =  $this->__Import->count_imports_approved(1);
		return "NAC ($nacional) / INT ($internacional)<br> ";
	}

	public function count_imports_rejected(){
		$nacional = $this->__Import->count_imports_rejected();
		$internacional = $this->__Import->count_imports_rejected(1);
		return "NAC ($nacional) / INT ($internacional)<br> ";
	}

	public function getQuotationId($flujo_id, $data = false){
		$id_etapa_cotizado 			= $this->__FlowStage->id_latest_regystri_state_cotizado($flujo_id);
		$datosFlowstage 			= $this->__FlowStage->get_data($id_etapa_cotizado);

		if ($data) {
			return $datosFlowstage;
		}

		return empty($datosFlowstage) ? null :  $this->encryptString($datosFlowstage['FlowStage']['document']);
	}

	public function get_quotations_products_id($import_id){
		return $this->__ImportProduct->get_quotations_products_id($import_id);
	}

	public function get_data_quotations_product($product_cotizacion_id){
		return $this->__QuotationsProduct->get_data($product_cotizacion_id);
	}

    public function encryptString($value=null){
        if(!$value){return false;}
        $texto              = $value;
        $skey               = "$%&/()=?*-+/1jf8";
        $iv_size            = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv                 = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext          = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $texto, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext)); 
        // return base64_encode($data);
    }

    public function desencriptarCadena($value=null){
        if(!$value){return false;}
        $skey                 = "$%&/()=?*-+/1jf8";
        $crypttext            = $this->safe_b64decode($value); 
        $iv_size              = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv                   = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext          = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
        // return base64_decode($value);
    }

    private function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    private  function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
	public function sanear_string($string)
	{
	    $string = trim($string);
	 
	    $string = str_replace(
	        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
	        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
	        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
	        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
	        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
	        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ñ', 'Ñ', 'ç', 'Ç'),
	        array('n', 'N', 'c', 'C',),
	        $string
	    );
	 
	    //Esta parte se encarga de eliminar cualquier caracter extraño
	    $string = str_replace(
	        array("\\", "'", "º", "-", "~",
	             "#", "@", "|", "!", "\"",
	             "·", "$", "%", "&", "/",
	             "(", ")", "?", "'", "¡",
	             "¿", "[", "^", "<code>", "]",
	             "+", "}", "{", "¨", "´",
	             ">", "< ", ";", ",", ":",
	             ".", ""),
	        '',
	        $string
	    );
	 
	 
	    return $string;
	}

}
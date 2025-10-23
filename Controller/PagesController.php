<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController {

	public function index(){
		$this->paintValidateMenu(AuthComponent::user('role'));
	}
	public function intro(){
		$this->layout = "intromenu";
	}

	public function reports(){

		$this->loadModel("Report");

		$user    	= isset($this->request->query["user_id"]) && !empty($this->request->query["user_id"]) ? $this->request->query["user_id"] : null;

		$ini        = isset($this->request->query["fechaIni"]) ? $this->request->query["fechaIni"] : date("Y-m-d",strtotime("-1 day"));
        $end        = isset($this->request->query["fechaEnd"]) ? $this->request->query["fechaEnd"] : date("Y-m-d",strtotime("-1 day"));

		if(!is_null($user)){
			$reports = $this->Report->find("all", ['conditions' => ['Report.fecha >=' =>  $ini, 'Report.fecha <=' =>  $end , "Report.user_id"=>$user ]]  );
		}else{
			$reports = AuthComponent::user("role") == "Gerente General" ? $this->Report->find("all", ['conditions' => ['Report.fecha >=' =>  $ini, 'Report.fecha <=' =>  $end, "Report.user_id !=" => 8 ]]  ) : $this->Report->find("all", ['conditions' => ['Report.fecha >=' =>  $ini, 'Report.fecha <=' =>  $end , "Report.user_id"=>AuthComponent::user("id") ]]  );
		}

		$this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);

		$this->set(compact('fecha_consulta','reports'));

	}

	public function preguntas(){
		
	}	

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('find_string_seeker','countFindStringSeeker','reports');
    }

    public function datos_productos(){

		$datos2024 = $this->dataSellsProducts(2024);
		$datos2023 = $this->dataSellsProducts(2023);

		$empleados = [];


		foreach ($datos2023["empleados"] as $key => $value) {
			if(!array_key_exists($key,$empleados)){
				$empleados[$key] = $value;
			}
		}

		foreach ($datos2024["empleados"] as $key => $value) {
			if(!array_key_exists($key,$empleados)){
				$empleados[$key] = $value;
			}
		}

		$this->set("datos2024",$datos2024["information"]);
		$this->set("datos2023",$datos2023["information"]);
		$this->set("datosEmpleados",array_values($empleados));
		$this->set("datosEmpleados2024",$datos2024["datosEmpleados"]);
		$this->set("datosEmpleados2023",$datos2023["datosEmpleados"]);
    }

    public function dataSellsProducts($anio = 2024){
    	$data = $this->postWoApi(["anio"=>$anio],"productsData");

    	$information = [];
    	$empleados   = [];

    	$datosEmpleados = [];

    	if(!empty($data->listado)){
    		$data = $this->object_to_array($data->listado);

    		foreach ($data as $key => $value) {
    			$value["total"] = intval($value["total"]);
    			$value["total_conteo"] = intval($value["total_conteo"]);
    			$total = intval($value["total"]);
    			$total_conteo = intval($value["total_conteo"]);


    			// Grupo 1

    			$id_data1 = $value["IdGrupo1"].'_0';

    			if(!array_key_exists($id_data1, $information)){
    				$information[$id_data1] = [
    					"parent" => 0,
    					"id" => $value["IdGrupo1"],
    					"IdEmpleado" => [$value["IdEmpleado"]],
    					"Nombre" => $value["Nombre"],
    					"id_data" => $id_data1,
    					"name" => $value["Grupo1"],
    					"total" => $total,
    					"data" => [],
    					"details" => [ $value ]
    				];

    				if(!array_key_exists($value["IdEmpleado"],$empleados)){
    					$empleados[$value["IdEmpleado"]] = [ "id" => $value["IdEmpleado"], "name"=>$value["Nombre"] ];
    				}

    				$information[$id_data1]["data"][ trim($value["Codigo"]) ] = ["name"=>$value["Descripcion"],"part_number"=>trim($value["Codigo"]),"total"=> $total,"total_conteo"=>$total_conteo, "IdEmpleado" => [$value["IdEmpleado"]] ];
    				
    			}else{
    				if(!array_key_exists($value["IdEmpleado"],$empleados)){
    					$empleados[$value["IdEmpleado"]] = [ "id" => $value["IdEmpleado"], "name"=>$value["Nombre"] ];

    				}
    				$information[$id_data1]["IdEmpleado"][] = $value["IdEmpleado"];
    				$information[ $id_data1 ]["total"] += $total;
    				if(!array_key_exists(trim($value["Codigo"]), $information[ $id_data1 ]["data"] )){
    					$information[$id_data1]["data"][ trim($value["Codigo"]) ] = ["name"=>$value["Descripcion"],"part_number"=>trim($value["Codigo"]),"total"=> $total,"total_conteo"=>$total_conteo,"IdEmpleado" => [$value["IdEmpleado"]]];
    				}else{
    					$information[ $id_data1 ]["data"] [ trim($value["Codigo"]) ] ["total"] += $total;
    					$information[ $id_data1 ]["data"] [ trim($value["Codigo"]) ] ["total_conteo"] += $total_conteo;
    				}

    				$information[$id_data1]["details"][] = $value;
    				
    			}



    			// grupo 2

    			$id_data2 = $value["IdGrupo2"].'_'.$id_data1;

    			if(!empty(trim($value["IdGrupo2"])) && !is_null($value["IdGrupo2"])){
    				if(!array_key_exists($id_data2, $information)){
	    				$information[$id_data2] = [
	    					"parent" => $id_data1,
	    					"id" => $value["IdGrupo2"],
	    					"id_data" => $id_data2,
	    					"IdEmpleado" => [$value["IdEmpleado"]],
    						"Nombre" => $value["Nombre"],
	    					"name" => $value["Grupo2"],
	    					"total" => $total,
	    					"data" => [],
	    					"details" => [ $value ]
	    				];

	    				$information[$id_data2]["data"][ trim($value["Codigo"]) ] = ["name"=>$value["Descripcion"],"part_number"=>trim($value["Codigo"]),"total"=> $total,"total_conteo"=>$total_conteo];

	    			}else{

	    				$information[ $id_data2 ]["total"] += $total;
	    				$information[$id_data2]["IdEmpleado"][] = $value["IdEmpleado"];
	    				if(!array_key_exists(trim($value["Codigo"]), $information[ $id_data2 ]["data"] )){
	    					$information[$id_data2]["data"][ trim($value["Codigo"]) ] = ["name"=>$value["Descripcion"],"part_number"=>trim($value["Codigo"]),"total"=> $total,"total_conteo"=>$total_conteo];
	    				}else{
	    					$information[ $id_data2 ]["data"] [ trim($value["Codigo"]) ] ["total"] += $total;
	    					$information[ $id_data2 ]["data"] [ trim($value["Codigo"]) ] ["total_conteo"] += $total_conteo;
	    				}
	    				$information[$id_data2]["details"][] = $value;
	    			}
    			}

    			

    			// grupo 3

    			$id_data3 = $value["IdGrupo3"].'_'.$id_data2;
    			if(!empty(trim($value["IdGrupo3"])) &&  !is_null($value["IdGrupo3"])){
    				if(!array_key_exists($id_data3, $information)){
	    				$information[$id_data3] = [
	    					"parent" => $id_data2,
	    					"id" => $value["IdGrupo3"],
	    					"id_data" => $id_data3,
	    					"name" => $value["Grupo3"],
	    					"IdEmpleado" => [$value["IdEmpleado"]],
    						"Nombre" => $value["Nombre"],
	    					"total" => $total,
	    					"data" => [],
	    					"details" => [ $value ]
	    				];

	    				$information[$id_data3]["data"][ trim($value["Codigo"]) ] = ["name"=>$value["Descripcion"],"part_number"=>trim($value["Codigo"]),"total"=> $total,"total_conteo"=>$total_conteo];

	    			}else{
	    				$information[$id_data3]["IdEmpleado"][] = $value["IdEmpleado"];
	    				$information[ $id_data3 ]["total"] += $total;
	    				if(!array_key_exists(trim($value["Codigo"]), $information[ $id_data3 ]["data"] )){
	    					$information[$id_data3]["data"][ trim($value["Codigo"]) ] = ["name"=>$value["Descripcion"],"part_number"=>trim($value["Codigo"]),"total"=> $total,"total_conteo"=>$total_conteo];
	    				}else{
	    					$information[ $id_data3 ]["data"] [ trim($value["Codigo"]) ] ["total"] += $total;
	    					$information[ $id_data3 ]["data"] [ trim($value["Codigo"]) ] ["total_conteo"] += $total_conteo;
	    				}
	    				$information[$id_data3]["details"][] = $value;
	    			}
    			}
    			

    			// grupo 4
    			$id_data4 = $value["IdGrupo4"].'_'.$id_data3;
    			if(!empty(trim($value["IdGrupo4"])) &&  !is_null($value["IdGrupo4"])){
    				if(!array_key_exists($id_data4, $information)){
	    				$information[$id_data4] = [
	    					"id" => $value["IdGrupo4"],
	    					"id_data" => $id_data4,
	    					"parent" => $id_data3,
	    					"name" => $value["Grupo4"],
	    					"IdEmpleado" => [$value["IdEmpleado"]],
    						"Nombre" => $value["Nombre"],
	    					"total" => $total,
	    					"data" => [],
	    					"details" => [ $value ]
	    				];

	    				$information[$id_data4]["data"][ trim($value["Codigo"]) ] = ["name"=>$value["Descripcion"],"part_number"=>trim($value["Codigo"]),"total"=> $total,"total_conteo"=>$total_conteo];

	    			}else{
	    				$information[$id_data4]["IdEmpleado"][] = $value["IdEmpleado"];
	    				$information[ $id_data4 ]["total"] += $total;
	    				if(!array_key_exists(trim($value["Codigo"]), $information[ $id_data4 ]["data"] )){
	    					$information[$id_data4]["data"][ trim($value["Codigo"]) ] = ["name"=>$value["Descripcion"],"part_number"=>trim($value["Codigo"]),"total"=> $total,"total_conteo"=>$total_conteo];
	    				}else{
	    					$information[ $id_data4 ]["data"] [ trim($value["Codigo"]) ] ["total"] += $total;
	    					$information[ $id_data4 ]["data"] [ trim($value["Codigo"]) ] ["total_conteo"] += $total_conteo;
	    				}
	    				$information[$id_data4]["details"][] = $value;
	    			}
    			}
    			

    		}
    	}


    	foreach ($information as $key => $value) {
    		$information[$key]["data"] = array_values($value["data"]);
    		$information[$key]["IdEmpleado"] = array_unique($value["IdEmpleado"]);
    		$information[$key]["IdEmpleado"] = array_values($information[$key]["IdEmpleado"]);
    	}

    	return ["information" => array_values($information), "empleados"=>($empleados), "datosEmpleados"=>$datosEmpleados];

    }

	

	public function transito(){
		$this->loadModel("ProspectiveUser");
		$query = "SELECT 
				( SELECT COALESCE(SUM(inventories.quantity),0) total FROM inventories where inventories.product_id = inv.product_id AND inventories.type = 1 AND state = 1 AND warehouse = 'Transito') 
				- 
				(SELECT COALESCE(SUM(inventories.quantity),0) total FROM inventories where inventories.product_id = inv.product_id AND inventories.type = 2 AND state = 1 AND warehouse = 'Transito') 
				totalTransito , 
				Product.part_number, Product.id, Product.img, Product.name
				FROM inventories inv
				INNER JOIN products AS Product ON Product.id = inv.product_id
				WHERE inv.warehouse = 'Transito'
				GROUP BY inv.product_id
				HAVING totalTransito > 0";

		$datos = $this->ProspectiveUser->query($query);

		$partsData = [];
		$precios = [];
		$costos = [];

		try {
			if (!empty($datos)) {
				$partsData				= $this->getValuesProductsWo($datos);
				$precios 				= $this->getPrices($partsData);
				$costos 				= $this->getCosts($partsData);
			}
					
		} catch (Exception $e) {
			$datos = array();			
		}

		$this->set("datosTransito",$datos);
		$this->set("partsData",$partsData);
		$this->set("costos",$costos);
		$this->set("precios",$precios);
	}

	public $meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9"=> "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"	 ];
	public $meses2 = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09"=> "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"	 ];

	public function data_count_ajax(){
		$this->layout 					= false;
		$this->loadModel('ProspectiveUser');
		$count_origen_robot				= $this->ProspectiveUser->count_origen('Robot',$this->request->data);
		$count_origen_chat 				= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Chat'),$this->request->data);
		$count_origen_whatsapp 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Whatsapp'),$this->request->data);
		$count_origen_email 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Email'),$this->request->data);
		$count_origen_llamada 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Llamada'),$this->request->data);
		$count_origen_redes 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Redes sociales'),$this->request->data);
		$count_origen_presencial 		= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Presencial'),$this->request->data);
		$count_origen_referido 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Referido'),$this->request->data);
		
		$count_origen_pelican 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Chat Pelican'),$this->request->data);
		$count_origen_chat_usa 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Chat Kebco USA'),$this->request->data);
		$count_origen_wpp_usa 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Whatsapp Kebco USA'),$this->request->data);
		$count_origen_email_usa			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Email Kebco USA'),$this->request->data);
		$count_landing					= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Landing'),$this->request->data);
		$count_origen_marketing			= $this->ProspectiveUser->count_origen('Remarketing',$this->request->data);

		$this->set(compact('count_origen_chat','count_origen_whatsapp','count_origen_email','count_origen_llamada','count_origen_redes','count_origen_presencial','meta_mes_total_empresa','total_ventas_empresa','porcentaje_meta_mes','total_sales_business_day','total_sales_business_yesterday','porcentaje_sales_day_yesterday_business','date_yesterday_sales','count_origen_referido','totalComisiones','count_origen_pelican','count_origen_chat_usa','count_origen_wpp_usa','count_origen_email_usa','count_landing','count_origen_marketing','lastDocuments','layout','total_ventas_session','count_origen_robot'));

	}

	public function detail_clientes_anio($anio = null) {
		if (is_null($anio)) {
			$this->return(["action" => "datos_clientes"]);
		}else{
			$infoClientes = $this->datos_clientes(true);

			$this->Session->write("infoClientes",$infoClientes);

			$listado      = $infoClientes["dataByAnio"][$anio];

			$this->loadModel("ProspectiveUser");
			$usuarios 			= $this->ProspectiveUser->User->role_asesor_comercial_user_true();
			$this->set("listado",$listado);
			$this->set("usuarios",$usuarios);
		}
		$this->set("anio",$anio);
	}

	public function get_info_detail(){
		$this->layout = false;
		$id 	= $this->request->data["id"];
		$anios  = $this->request->data["anio"];
		$infoClientes = is_null($this->Session->read("infoClientes")) ? $this->datos_clientes(true) : $this->Session->read("infoClientes");

		$anios  = explode("-", $anios);

		$ventas = [];

		foreach ($anios as $key => $anio) {
			$ventasAnio   = $infoClientes["clientesByAnioDetail"][$anio][$id];
			$ventas 	  = array_merge($ventasAnio,$ventas);
		}

		$ventas = $this->object_to_array($ventas);

		$this->set("lastDocuments",$ventas);

	}

	public function create_flow_client(){
		$this->layout 			= false;
		$nombreClientesCache    = Cache::read("CacheDataClients");
		$clienteActual 			= $nombreClientesCache[$this->request->data["id"]];

		if (!empty($clienteActual->Apellidos)) {
			$this->loadModel("ClientsNatural");
			$cliente = $this->ClientsNatural->findByIdentificationOrEmailOrCellPhoneOrTelephone($clienteActual->Identificacion,$clienteActual->EMail,$clienteActual->Telefonos,$clienteActual->Telefonos);
		}else{
			$this->loadModel("ClientsLegal");
			$cliente = $this->ClientsLegal->find("first",[ "order" => ["ClientsLegal.created" => "DESC"] ,"conditions" => [ 'OR' => array( 
													'LOWER(ClientsLegal.name) LIKE' 	=> '%'.$clienteActual->Nombre.'%',
							            			'LOWER(ClientsLegal.nit) LIKE' 		=> '%'.$clienteActual->Identificacion.'%'
							        			) ] ]);

			if (empty($cliente)) {
				$this->loadModel("ContacsUser");
				$cliente = $this->ContacsUser->findByEmailOrCellPhoneOrTelephone($clienteActual->EMail,$clienteActual->Telefonos,$clienteActual->Telefonos);
			}

		}
		$this->loadModel("ProspectiveUser");
		$usuarios 			= $this->ProspectiveUser->User->role_asesor_comercial_user_true();

		$this->set(compact("usuarios","clienteActual","cliente"));

	}

	

	public function metasData($usuarios_search, $usuarios_sistema){
		$this->loadModel("Goal");
		$options 		= ["ini" => date("2023-01-01"), "end" => date("Y-m-d") ];
		if (AuthComponent::user("role") == "Asesor Externo") {
			$options["vendedor"] = AuthComponent::user("identification");
			$options["ini"] 	 = date("Y-01-01");
		}
		$allDocument	= $this->postWoApi($options,"documents");
		$allGoals 		= AuthComponent::user("role") == "Asesor Externo" ? $this->Goal->findAllByUserId(AuthComponent::user("id")) : $this->Goal->find("all",["conditions" => ["Goal.id >" => 0, "Goal.user_id != "=> [0,5,123], "Goal.year >=" => 2023  ] ]);
		$anios 			= [];

		$dataCompare 	= [];
		$dataPrev 		= [];
		$totalByCompany = [];
		$labelsBtn 		= ["TODOS"];
		$mesesCats 		= $this->meses2;

		foreach ($allGoals as $key => $value) {
			$dataPrev[ $value["Goal"]["year"] ][ "TODOS" ] = [];
			if (!in_array($value["Goal"]["year"], $anios)) {
				$anios[] = $value["Goal"]["year"];
			}
			if (!in_array(str_replace(" ", "_", $value["Goal"]["name"]), $labelsBtn)) {
				$labelsBtn[] = str_replace(" ", "_", $value["Goal"]["name"]);
			}
			$dataPrev[ $value["Goal"]["year"] ][ $value["Goal"]["name"] ] = [];
		}

		$usersNames     = ["TODOS"=>Configure::read("variables.meta_mes_total_empresa"),"WILSON LEONARDO" => 80000000, "KENNETH ESCOBAR" => 80000000,"DANIEL HENAO"=>15000000, "BLANCA AURORA" => 50000000, "JAIME ENRIQUE" => 50000000, "MARGARITA MARIA"=>15000000];




		foreach ($allGoals as $key => $value) {
			foreach ($this->meses2 as $keyMes => $mes) {
				$dataPrev[ $value["Goal"]["year"] ][ $value["Goal"]["name"] ][] = [$mes, floatval($value["Goal"][$keyMes]) ];
				$totalByCompany[$value["Goal"]["year"]][$value["Goal"]["name"]][$mes] = 0;
				$dataCompare[$value["Goal"]["year"]][$value["Goal"]["name"]][$mes] = floatval($value["Goal"][$keyMes]);
				$totalByCompany[$value["Goal"]["year"]]["TODOS"][$mes] = 0;
			}
		}

		foreach ($allGoals as $key => $value) {
			foreach ($this->meses2 as $keyMes => $mes) {
				$dataCompare[$value["Goal"]["year"]][ "TODOS" ][$mes] = 0;
			}
		}



		foreach ($dataCompare as $anio => $datosAnio) {

			foreach ($datosAnio as $usuario => $dataMes) {
				if ($usuario != "TODOS") {
					foreach ($dataMes as $mes => $value) {
						$dataCompare[$anio]["TODOS"][$mes] += $value;
					}
				}
			}
		}

		foreach ($dataCompare as $anio => $datosAnio) {
			foreach ($datosAnio as $usuario => $dataMes) {
				if ($usuario == "TODOS") {
					foreach ($dataMes as $mes => $value) {
						$dataPrev[ $anio ][ $usuario ][] = [$mes, floatval($value) ];
					}
				}
			}
		}

		$totalByEmploye = [];
		$totalKennet2022 = 0;
		$clientes = [];
		$clientesTotal = [];

		$allDocument->listado = empty($allDocument->listado) ? [] : $allDocument->listado;



		foreach ($allDocument->listado as $key => $value) {
			$dateIni = explode(" ", $value->Fecha);
			$date 	 = explode("-", $dateIni[0])[1];
			$anio    = explode("-", $dateIni[0])[0];
			$totalByCompany[$anio]["TODOS"][ $this->meses2[$date] ] += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);

			$names = explode(" ", $value->NombreVendedor);
			$nombreVal = $names[0]. " " . $names[1];

			if ($nombreVal == "KEBCO S.A.S." || $nombreVal == 'MONICA ARANGO' || $nombreVal == "WILSON DE" || $nombreVal == 'WILSON LEONARDO' || $nombreVal == 'LUIS FERNANDO' || $nombreVal == 'ELIZABETH BETANCUR' || $nombreVal == 'KAREN VARELAS' || $nombreVal == 'JENNIFER ALEXANDRA' || $nombreVal == 'DANIEL BETANCUR') {
				$nombreVal = "OTROS VENDEDORES";
				$nombreVal = "KENNETH ESCOBAR";
			}

			if ($nombreVal == "HUGO ALBERTO" || $nombreVal == "SERGIO ANDRES") {
				$nombreVal = "DANIEL HENAO";
			}

			if ($anio == 2023 && $nombreVal == "KENNETH ESCOBAR") {
				$totalKennet2022++;
				$clientes[] = [$value->Identificacion, $value->Nombre." ".$value->Apellidos, $value->Factura, round($value->Total_Venta - $value->Total_Descuentos) ] ;
			}

			@$totalByCompany[$anio][ $nombreVal ][ $this->meses2[$date] ] += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);
		}


		// echo "<pre>";
		// var_dump($totalByCompany);
		// die();

		// foreach ($clientes as $key => $value) {
		// 	echo $value[0].";".$value[1].";".$value[2].";".$value[3]."<br>";
		// }
		// die;
		// die;
		// var_dump($clientesTotal);
		// var_dump($clientes);
		// die;
		
		$totalAnio = [];

		foreach ($anios as $key => $anio) {
			if (isset($totalByCompany[$anio]["TODOS"]) && !empty($totalByCompany[$anio]["TODOS"]) && is_array($totalByCompany[$anio]["TODOS"])) {

				$totalAnio[$anio] = array_sum($totalByCompany[$anio]["TODOS"]);
			}
		}

		$copyTotalByCompany = $totalByCompany;
		$totalByCompany     = [];
		$cumplimientoAnual 	= [];
		$metaAnual 			= [];
		$referenteClientes  = [];

		foreach ($copyTotalByCompany as $anio => $dataAnio) {
			$referenteClientes[$anio] = $dataAnio["TODOS"];
			foreach ($dataAnio as $label => $datos) {
				$totalByCompany[$anio][$label] = [];

				foreach ($datos as $key => $value) {
					$valueRef = @$dataCompare[$anio][$label][$key];

					if ($valueRef == 0 || is_null($valueRef)) {
						$valueRef = 1;
					}

					$objDatos = new stdClass();
					$objDatos->name = $key;
					$objDatos->y = $value;
					$objDatos->cumplimiento = round( ($value/$valueRef)  * 100,2) ;
					$totalByCompany[$anio][$label][] = $objDatos;
				}

			}
			
		}

		$metasAnio = [];
		$metasAnioUsuarios = [];

		foreach ($dataCompare as $anio => $dataComp) {
			foreach ($dataComp as $usuario => $meses) {
				$metasAnio[$anio][$usuario] = array_sum($meses);
				$vendido 					= 0;
				foreach ($totalByCompany[$anio][$usuario] as $key => $value) {
					$vendido += $value->y;
				}
				$sumaMeses = array_sum($meses);

				if ($sumaMeses <= 0) {
					$sumaMeses = 1;
				}

				$cumplimientoAnual[$anio][$usuario] = round(($vendido / $sumaMeses ) * 100, 2);
				if ($usuario == "TODOS") {
					$metaAnual[$anio] = array_sum($meses);
				}
			}
		}

		foreach ($anios as $keyanio => $anio) {
			$cumplimientoTodos[$anio] = round(($totalAnio[$anio] / $metaAnual[$anio])*100,2); 
		}

		$metaMesActual = 0;

		if (isset($dataCompare[date("Y")]["TODOS"])) {
			$metaMesActual = $dataCompare[date("Y")]["TODOS"][ $this->meses2[date("m")] ];
		}

		$this->set(compact("totalByCompany","dataPrev","labelsBtn","mesesCats","totalAnio","cumplimientoAnual","metasAnio","metaAnual","cumplimientoTodos","anios","metaMesActual"));
	}

	public function productosPanel(){

		$conditions = ["ImportRequest.state" => 1, "ImportRequest.type" => 1,"ImportRequestsDetail.state" => 1];
		if ($this->request->is("ajax")) {
			$this->layout = false;
			$conditions["DATE(ImportRequestsDetail.created) >="] = $this->request->data["ini"]; 
			$conditions["DATE(ImportRequestsDetail.created) <="] = $this->request->data["end"]; 
		}
		$this->loadModel("ImportRequestsDetailsProduct");

		$joins = [
			['table' => 'products','alias' => 'Product','type' => 'INNER','conditions' => array('Product.id = ImportRequestsDetailsProduct.product_id')],
			['table' => 'import_requests_details','alias' => 'ImportRequestsDetail','type' => 'INNER','conditions' => array('ImportRequestsDetail.id = ImportRequestsDetailsProduct.import_requests_detail_id')],
			['table' => 'import_requests','alias' => 'ImportRequest','type' => 'INNER','conditions' => array('ImportRequest.id = ImportRequestsDetail.import_request_id')],
		];

		$fields   = ["ImportRequestsDetailsProduct.quantity","Product.part_number","Product.name","Product.img","ImportRequestsDetail.prospective_user_id","ImportRequestsDetail.user_id","ImportRequestsDetail.created","ImportRequestsDetail.description","ImportRequestsDetail.motive","ImportRequestsDetail.texto","ImportRequestsDetailsProduct.delivery","ImportRequestsDetail.deadline", "ImportRequest.*","Product.brand","ImportRequestsDetail.type_request"];


		$products = $this->ImportRequestsDetailsProduct->find("all",["recursive" => -1, "joins" => $joins,"fields" => $fields, "conditions" => $conditions , "order" => ["ImportRequestsDetail.created" => "DESC"] ]);

		$this->set("productosImport",$products);
	}

	public function home_adviser($layout = null) {
		$this->productosPanel();
		$this->transito();
		if (!is_null($layout)) {
			$this->layout = false;
		}
		$this->loadModel('ProspectiveUser');

		$this->usuarios_sistema = $this->ProspectiveUser->User->find("list",["fields" =>["id","identification"], "conditions" => ["User.identification !=" => null,"User.identification !=" => '', "User.state" => 1] ]);

		$this->usuarios_names   = $this->ProspectiveUser->User->find("list",["fields" =>["id","name"], "conditions" => ["User.identification !=" => null,"User.identification !=" => '', "User.state" => 1] ]);

		$this->set("usuarios_names",$this->usuarios_names);

		if (AuthComponent::user("role") != "Gerente General") {
			$this->usuarios_search = [AuthComponent::user("id")];
		}else{
			$this->usuarios_search = isset($this->request->query["uid"]) && !empty($this->request->query["uid"]) ? [$this->request->query["uid"]] : $this->ProspectiveUser->User->find("list",["fields" =>["id","id"] ]);
		}

		$this->set("uid", isset($this->request->query["uid"]) && !empty($this->request->query["uid"])  ? $this->request->query["uid"] : "" );

		if (count($this->usuarios_search) == 1) {
			$this->set("id_busca", $this->usuarios_sistema[$this->usuarios_search[0]]);
			$id_user_busca = $this->usuarios_search[0];
			if (AuthComponent::user("role") == "Gerente General") {
				$this->set("filtroADM",true);
			}
			$this->loadModel("Goal");
			$this->loadModel("Percentage");
			$this->loadModel("CollectionGoal");
			$this->loadModel("Effectivity");

			$goals 					= $this->Goal->findAllByUserId($this->usuarios_search[0]);
			$recaudos   			= $this->CollectionGoal->findAllByUserId($this->usuarios_search[0]);
			$porcentajes_comisiones = $this->Percentage->findAllByUserId($this->usuarios_search[0]);
			$percentajeDb 			= $this->Effectivity->find("all",["conditions"=>["Effectivity.user_id" => $this->usuarios_search[0]], "recursive" => -1]);

			if (!empty($goals)) {
				$copyGoals = $goals;
				$goals     = [];

				foreach ($copyGoals as $key => $value) {
					$goals[$value["Goal"]["year"]] = $value["Goal"];
				}
				$this->set("goals_user",$goals);
			}

			if (!empty($recaudos)) {
				$copyRecaudos = $recaudos;
				$recaudos     = [];

				foreach ($copyRecaudos as $key => $value) {
					$recaudos[$value["CollectionGoal"]["year"]] = $value["CollectionGoal"];
				}
				$this->set("recaudos_user",$recaudos);
			}

			if (!empty($porcentajes_comisiones)) {
				$copyporcentajes_comisiones = $porcentajes_comisiones;
				$porcentajes_comisiones     = [];

				foreach ($copyporcentajes_comisiones as $key => $value) {
					$porcentajes_comisiones[] = $value["Percentage"];
				}
				$this->set("porcentajes_comisiones",$porcentajes_comisiones);
			}
            

            if (!empty($percentajeDb)) {
				$copyporpercentajeDb = $percentajeDb;
				$percentajeDb     	 = [];

				foreach ($copyporpercentajeDb as $key => $value) {
					$percentajeDb[] = $value["Effectivity"];
				}
				$this->set("effectivity_user",$percentajeDb);
			}

		}else{
			$id_user_busca = null;
		}
		$this->set("id_user_busca",$id_user_busca);
		

		$this->metasData($this->usuarios_search,$this->usuarios_sistema);

		$totalComisiones = $this->getTotalComisiones();
		$count_origen_chat 				= $this->ProspectiveUser->count_origen_chat();
		$count_origen_whatsapp 			= $this->ProspectiveUser->count_origen_whatsapp();
		$count_origen_email 			= $this->ProspectiveUser->count_origen_email();
		$count_origen_llamada 			= $this->ProspectiveUser->count_origen_llamada();
		$count_origen_redes 			= $this->ProspectiveUser->count_origen_redes();
		$count_origen_presencial 		= $this->ProspectiveUser->count_origen_presencial();
		$count_origen_referido 			= $this->ProspectiveUser->count_origen_referido();
		
		$count_origen_pelican 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Chat Pelican'));
		$count_origen_chat_usa 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Chat Kebco USA'));
		$count_origen_wpp_usa 			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Whatsapp Kebco USA'));
		$count_origen_email_usa			= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Email Kebco USA'));
		$count_landing					= $this->ProspectiveUser->count_origen(Configure::read('variables.origenContact.Landing'));
		$count_origen_marketing			= $this->ProspectiveUser->count_origen('Remarketing');

		$this->loadModel("Salesinvoice");
		$meta_mes_total_empresa         = (int) Configure::read('variables.meta_mes_total_empresa');
		$total_sales_business_day       = 0;
		$total_descuentos_day       	= 0;
		$total_sales_business_yesterday = 0;
		$total_ventas_empresa 			= 0;
		$total_ventas_session 			= 0;
		$lastDocuments 					= $this->postWoApi(["ini" => date("Y-m-d"), "end" => date("Y-m-d") ],"documents");

		$lastDocuments 					= $this->object_to_array($lastDocuments);
		if (isset($lastDocuments["listado"]) && !empty($lastDocuments["listado"])) {
			$lastDocuments 				= Set::sort($lastDocuments["listado"], '{n}.Fecha', 'desc');
		}else{
			$lastDocuments				= [];
		}
		$totalDia						= $this->postWoApi(["ini" => date("Y-m-d"), "end" => date("Y-m-d") ],"documents");
		$totalAyer 						= $this->postWoApi(["ini" => date("Y-m-d", strtotime("-1 day")), "end" => date("Y-m-d", strtotime("-1 day")) ],"documents");
		$totalMes 						= $this->postWoApi(["ini" => date('Y-m-1'), "end" => date("Y-m-t") ],"documents");

		

		if (AuthComponent::user("role") == "Asesor Externo") {

			if (!empty($totalDia) && isset($totalDia->valores)) {
				foreach ($totalDia->listado as $key => $value) {
					if ($value->IdVendedor == AuthComponent::user("identification")) {
						$total_sales_business_day += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta );  
						$total_descuentos_day += floatval($value->Total_Descuentos);  
					}
				}
			}

			if (!empty($totalAyer) && isset($totalAyer->valores)) {
				foreach ($totalAyer->listado as $key => $value) {
					if ($value->IdVendedor == AuthComponent::user("identification")) {
						$total_sales_business_yesterday += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);  
					}
				}
			}

			if (!empty($totalMes) && isset($totalMes->valores)) {
				foreach ($totalMes->listado as $key => $value) {
					if ($value->IdVendedor == AuthComponent::user("identification")) {
						$total_ventas_empresa += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);  
					}
				}
			}
			
		}else{
			if (!empty($totalDia) && isset($totalDia->valores)) {
				foreach ($totalDia->listado as $key => $value) {
					$total_sales_business_day += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta );  
						$total_descuentos_day += floatval($value->Total_Descuentos);  

					if (count($this->usuarios_search) == 1) {
						$identification = $this->usuarios_sistema[$this->usuarios_search[0]];
					}else{
						$identification = AuthComponent::user("identification");
					}
					if ($value->IdVendedor == $identification) {
						$total_ventas_session += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);  
					}

				}
			}
			if (!empty($totalAyer) && isset($totalAyer->valores)) {
				foreach ($totalAyer->listado as $key => $value) {
					$total_sales_business_yesterday += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);  
				}
			}
			if (!empty($totalMes) && isset($totalMes->valores)) {
				foreach ($totalMes->listado as $key => $value) {
					$total_ventas_empresa += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);  
				}
			}
		}
		
		$porcentaje_meta_mes 						= $this->calculatePorcentajeMetaVentasMes($meta_mes_total_empresa,$total_ventas_empresa);

		$date_yesterday_sales			 			= $this->ProspectiveUser->FlowStage->payment_verification_sales_yesterday_business_date();
		$porcentaje_sales_day_yesterday_business 	= $this->calcuatePromedioResult($total_sales_business_day,$total_sales_business_yesterday);

		if (isset($this->request->query["ini"]) && !empty($this->request->query["ini"])) {
			$ini = $this->request->query["ini"];
		}else{
			$ini = date("2018-01-01");
		}

		if (isset($this->request->query["end"]) && !empty($this->request->query["end"])) {
			$end = $this->request->query["end"];
		}else{
			$end = date("Y-12-31");
		}

		$this->dataDashboard($ini,$end,$id_user_busca);

		$this->set(compact('count_origen_chat','count_origen_whatsapp','count_origen_email','count_origen_llamada','count_origen_redes','count_origen_presencial','meta_mes_total_empresa','total_ventas_empresa','porcentaje_meta_mes','total_sales_business_day','total_sales_business_yesterday','porcentaje_sales_day_yesterday_business','date_yesterday_sales','count_origen_referido','totalComisiones','count_origen_pelican','count_origen_chat_usa','count_origen_wpp_usa','count_origen_email_usa','count_landing','count_origen_marketing','lastDocuments','layout','total_ventas_session','total_descuentos_day'));

		$this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
	}

	public function datos_geograficos(){
		$this->autoRender 			= false;

		$ini 						= isset($this->request->data["ini"]) && !empty($this->request->data["ini"]) ? $this->request->data["ini"] : date("Y-m-d");
		$end 						= isset($this->request->data["end"]) && !empty($this->request->data["end"]) ? $this->request->data["end"] : date("Y-m-d");
		$datos						= $this->postWoApi(["ini" => $ini, "end" => $end ],"documents");

		$dataBlock 					= [];
		$dataCiudades 				= [];
		$totalCiudades 				= [];
		$totalDepartamentos 		= [];

		foreach ($datos->listado as $key => $value) {
			if(!array_key_exists($value->Departamento, $dataBlock)){

				$objNew = new stdClass();
				$objNew->name 	   = $value->Departamento;
				$objNew->drilldown = $value->Departamento;
				$objNew->y 		   = floatval($value->Total_Venta) - floatval($value->Total_Descuentos); 

				$objCiudad 		   = new stdClass();
				$objCiudad->name   = $value->Departamento;
				$objCiudad->id     = $value->Departamento;
				$objCiudad->data   = [];

				$dataCiudades[$value->Departamento] = $objCiudad;
				$dataBlock[$value->Departamento] = $objNew;


			}else{
				$dataBlock[$value->Departamento]->y += floatval($value->Total_Venta) - floatval($value->Total_Descuentos);
			}

			if (!isset($totalCiudades[$value->Departamento][$value->Ciudad])) {
				$totalCiudades[$value->Departamento][$value->Ciudad] = floatval($value->Total_Venta) - floatval($value->Total_Descuentos);
			}else{
				$totalCiudades[$value->Departamento][$value->Ciudad] += floatval($value->Total_Venta) - floatval($value->Total_Descuentos);
			}

			if (!isset($totalDepartamentos[$value->Departamento])) {
				$totalDepartamentos[$value->Departamento] = floatval($value->Total_Venta) - floatval($value->Total_Descuentos);
			}else{
				$totalDepartamentos[$value->Departamento] += floatval($value->Total_Venta) - floatval($value->Total_Descuentos);
			}
		}

		ksort($dataCiudades);
		ksort($dataBlock);
		ksort($totalCiudades);

		foreach ($totalCiudades as $departamento => $ciudades) {
			foreach ($ciudades as $ciudad => $total) {
				$dataCiudades[$departamento]->data[] = [$ciudad,$total];
			}
		}

		return json_encode(["dataCiudades" => array_values($dataCiudades),		"dataBlock" => array_values($dataBlock)]);
	}

	public function get_flujos(){
		$this->layout = false;
		$this->loadModel("ProspectiveUser");
		$this->loadModel("Config");
		$data = $this->request->data;


		$type = isset($this->request->data["type"]) ? $this->request->data["type"] : 'all';

		if($type == "all"){
			$flujos_cs = $this->ProspectiveUser->find("all",["order"=>["ProspectiveUser.created"=>"desc"],"conditions" => ['DATE(ProspectiveUser.created) >=' =>  $data["ini"], 'DATE(ProspectiveUser.created) <=' =>  $data["end"] , "ProspectiveUser.state_flow !="=>0 ]]);
		}else{
			$flujos_cs = $this->ProspectiveUser->find("all",[
				"joins" => [
					array(
	                    'table' => 'flow_stages',
	                    'alias' => 'FlowStageInp',
	                    'type' => 'INNER',
	                    'conditions' => array(
	                        'ProspectiveUser.id = FlowStageInp.prospective_users_id '
	                    )
	                ),
				],
				"conditions" => ['DATE(ProspectiveUser.created) >=' =>  $data["ini"], 'DATE(ProspectiveUser.created) <=' =>  $data["end"] , "ProspectiveUser.state_flow !="=>0, 'FlowStageInp.reason' => 'Cotización de productos CODY IA' ],
				"order"=>["ProspectiveUser.created"=>"desc"],
				"group" => ["ProspectiveUser.id"]

			]);
		}


		$config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];

		foreach ($flujos_cs as $key => $value) {
            $total = 0;
            $id_etapa_cotizado          = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($value["ProspectiveUser"]["id"]);
            $datosFlowstage             = $this->ProspectiveUser->FlowStage->field("document",["id"=>$id_etapa_cotizado]);

            if (is_numeric($datosFlowstage)) {

                $productosCotizacion     = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->find("all",["conditions" => ["quotation_id" => $datosFlowstage], "fields" => ["price","currency","quantity"] ]);

                foreach ($productosCotizacion as $keyPro => $quotationProduct) {
                    $precio = $quotationProduct["QuotationsProduct"]["currency"] == "usd" ? $quotationProduct["QuotationsProduct"]["price"] * $trmActual : doubleval($quotationProduct["QuotationsProduct"]["price"]);
                    $total += ($precio * $quotationProduct["QuotationsProduct"]["quantity"]);
                }
                $flujos_cs[$key]["ProspectiveUser"]["total"] = $total;
                $flujos_cs[$key]["ProspectiveUser"]["cotizacion"] = $datosFlowstage;
                
            }else{
            	$flujos_cs[$key]["ProspectiveUser"]["total"] = 0;
            }
        }

		$this->set("flujos_cs",$flujos_cs);
	}

	public function dataDashboard($ini,$end,$id_user_busca){
		$this->loadModel("ClientsNatural");
		$this->loadModel("ClientsLegal");
		$this->loadModel("ProspectiveUser");
		$this->loadModel("Config");
		$this->ClientsNatural->recursive = -1;
		// $this->ClientsLegal->recursive = -1;

		// 
		$config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];

		$naturals_cs = $this->ClientsNatural->find("all",["order"=>["ClientsNatural.created"=>"desc"], "conditions" => ["DATE(ClientsNatural.created)" => date("Y-m-d"), "ClientsNatural.user_receptor" => $this->usuarios_search ] ]);
		$legals_cs 	 = $this->ClientsLegal->find("all",["order"=>["ClientsLegal.created"=>"desc"], "conditions" => ["DATE(ClientsLegal.created)" => date("Y-m-d"), "ClientsLegal.user_receptor" => $this->usuarios_search ] ]);
		$flujos_cs	 = $this->ProspectiveUser->find("all",["order"=>["ProspectiveUser.created"=>"desc"],"conditions" => ["DATE(ProspectiveUser.created)" => date("Y-m-d"), "ProspectiveUser.user_id" => $this->usuarios_search, "ProspectiveUser.state_flow !="=>0 ]]);

		$totalDeuda  = 0;
		$empleados   	  = [];
		$empleadosDeuda   = [];

		$usersFilter = is_null($id_user_busca) ? $this->ProspectiveUser->User->find("list",["fields"=>["id","id"]]) : $id_user_busca;

		$pagados  	 = $this->ProspectiveUser->FlowStage->find("count",["fields" => ["FlowStage.prospective_users_id"], "joins" => [['table' => 'prospective_users','alias' => 'PS','type' => 'INNER','conditions' => array('PS.id = FlowStage.prospective_users_id AND FlowStage.payment_verification = 1')]], "conditions" => ["DATE(FlowStage.created) >="=>$ini,"DATE(FlowStage.created) <=" => $end, "PS.user_id" => $usersFilter ], "group" => ["FlowStage.prospective_users_id"] ]);

		$deudas 					= $this->postWoApi([],"debts");
		// $deudas 					= [];
		if (!empty($deudas)) {
			$deudas 					= $this->object_to_array($deudas);
			$totales 					= Set::extract($deudas,"{n}.Saldo");
			$totalDeuda 				= array_sum($totales);

			// if ( AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Contabilidad"  ) {
				foreach ($deudas as $key => $value) {
					$empleados[$value["Cc_Empleado"]] = $value["Empleado"];
					if (!array_key_exists($value["Cc_Empleado"], $empleadosDeuda)) {
						$empleadosDeuda[$value["Cc_Empleado"]] = floatval($value["Saldo"]);
					}else{
						$empleadosDeuda[$value["Cc_Empleado"]] += floatval($value["Saldo"]);
					}
				}
				arsort($empleadosDeuda);
			// }
		}

		foreach ($flujos_cs as $key => $value) {
            $total = 0;
            $id_etapa_cotizado          = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($value["ProspectiveUser"]["id"]);
            $datosFlowstage             = $this->ProspectiveUser->FlowStage->field("document",["id"=>$id_etapa_cotizado]);

            if (is_numeric($datosFlowstage)) {

                $productosCotizacion     = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->find("all",["conditions" => ["quotation_id" => $datosFlowstage], "fields" => ["price","currency","quantity"] ]);

                foreach ($productosCotizacion as $keyPro => $quotationProduct) {
                    $precio = $quotationProduct["QuotationsProduct"]["currency"] == "usd" ? $quotationProduct["QuotationsProduct"]["price"] * $trmActual : doubleval($quotationProduct["QuotationsProduct"]["price"]);
                    $total += ($precio * $quotationProduct["QuotationsProduct"]["quantity"]);
                }
                $flujos_cs[$key]["ProspectiveUser"]["total"] = $total;
                $flujos_cs[$key]["ProspectiveUser"]["cotizacion"] = $datosFlowstage;
                
            }else{
            	$flujos_cs[$key]["ProspectiveUser"]["total"] = 0;
            }
        }	

        
        $this->dataFlujosComparision();
        $this->dataQuoationComparision();
        $this->dataClients($flujos_cs);

        $totalClientesGrafo = count($naturals_cs)+count($legals_cs);

		$this->set(compact("naturals_cs","legals_cs","flujos_cs","deudas","totalDeuda","empleados","empleadosDeuda","pagados",'totalClientesGrafo'));

	}

	private function dataClients($datos){
		$this->loadModel("ProspectiveUser");
		$prospectosNaturales    = [];
        $prospectosJuridicos    = [];
         if(!empty($datos)){
            $prospectosNaturales    = Set::extract($datos,"{n}.ProspectiveUser");
            $prospectosJuridicos    = Set::extract($datos,"{n}.ProspectiveUser");

            $totalFacturado         = Set::extract($datos,"{n}.ProspectiveUser.bill_value");
            $totalFacturado         = array_sum($totalFacturado);

            $copiaNaturales         = $prospectosNaturales;
            $prospectosNaturales    = [];
            foreach ($copiaNaturales as $key => $value) {
                if($value["clients_natural_id"] != 0 && !is_null($value["clients_natural_id"])  ){
                    $prospectosNaturales[] = $value["clients_natural_id"];
                }
            }

            $copiaJuridicos         = $prospectosJuridicos;
            $prospectosJuridicos    = [];
            foreach ($copiaJuridicos as $key => $value) {
                $value["state_flow"] = $value["state_flow"] == 9 ? 7 : $value["state_flow"];
                if($value["contacs_users_id"] != 0 ){
                    $prospectosJuridicos[] = $value["contacs_users_id"];
                }
            }
        }

        $clientesNaturalesViejos = $this->ProspectiveUser->ClientsNatural->find("list",["conditions" => ["created <" => date("Y-m-d"), "id" => $prospectosNaturales  ] ]);

        $contactosViejos         = $this->ProspectiveUser->ContacsUser->find("list",["conditions" => ["created <" => date("Y-m-d"), "id" => $prospectosJuridicos  ] ]);

        $totalNaturalesNuevos    = (count($prospectosNaturales) - count($clientesNaturalesViejos)) < 0 ? 0 : count($prospectosNaturales) - count($clientesNaturalesViejos);

        $totalContactosNuevos    = (count($prospectosJuridicos) - count($contactosViejos)) < 0 ? 0 : count($prospectosJuridicos) - count($contactosViejos);

        $faltantes               = count($datos) - $totalNaturalesNuevos - count($clientesNaturalesViejos) - $totalContactosNuevos - count($contactosViejos);

        $datosClientes           = [["CLIENTES NATURALES NUEVOS", $totalNaturalesNuevos],["CLIENTES NATURALES YA EXISTENTES",count($clientesNaturalesViejos)],["EMPRESAS NUEVAS",$totalContactosNuevos],["EMPRESAS YA EXISTENTES",count($contactosViejos)],["CLIENTES DESCONOCIDOS",$faltantes]];
        $this->set("datosClientesByOwner",$datosClientes);
	}

	private function dataQuoationComparision(){
		$this->loadModel("Quotation");

		$quotations = $this->Quotation->find("all",["fields" => ["COUNT(Quotation.id) total","MONTH(Quotation.created) mes"], "conditions" => ["DATE(Quotation.created) >=" => date("Y-01-01"), "DATE(Quotation.created) <=" => date("Y-12-31"), "Quotation.user_id" => $this->usuarios_search  ], "group" => ["MONTH(Quotation.created)"], "recursive" => -1, "joins" => [['table' => 'prospective_users','alias' => 'PS','type' => 'INNER','conditions' => array("PS.id = Quotation.prospective_users_id AND PS.origin != 'Marketing'")]] ]);

		$datosQuoatiations 	= [];
		$totalQuoatations 	= 0;
		$catsQuotations 	= ["Cotizaciones creadas"];

		foreach ($quotations as $key => $value) {
			$dataClient = new stdClass();
			$dataClient->name = $this->meses[$value[0]["mes"]];
			$dataClient->data = [intval($value[0]["total"])];
			$datosQuoatiations[$value[0]["mes"]] = $dataClient;
			$totalQuoatations+=intval($value[0]["total"]);
		}


		$this->set("catsQuotations",$catsQuotations);
		$this->set("datosQuoatiations",$datosQuoatiations);
		$this->set("totalQuoatations",$totalQuoatations);
	}

	private function dataFlujosComparision(){
		$this->loadModel("ProspectiveUser");

		$flowsGeneral = $this->ProspectiveUser->find("all",["fields" => ["COUNT(id) total","MONTH(created) mes"], "conditions" => ["DATE(created) >=" => date("Y-01-01"), "DATE(created) <=" => date("Y-12-31"), "origin !=" => 'Marketing', "ProspectiveUser.user_id" => $this->usuarios_search, "ProspectiveUser.state_flow !="=>[7,9]  ], "group" => ["MONTH(created)"], "recursive" => -1 ]);

		$flowsPayment  = $this->ProspectiveUser->FlowStage->find("all",["fields" => ["COUNT(PS.id) total","MONTH(PS.created) mes"], "joins" => [['table' => 'prospective_users','alias' => 'PS','type' => 'INNER','conditions' => array('PS.id = FlowStage.prospective_users_id AND FlowStage.payment_verification = 1')]], "conditions" => ["DATE(PS.created) >=" => date("Y-01-01"), "DATE(PS.created) <=" => date("Y-12-31"), "PS.user_id" => $this->usuarios_search  ], "group" => ["FlowStage.prospective_users_id","MONTH(created)"], "recursive" => -1 ]);


		$catsFlujo = [];
		$datosFlujos = [];
		$totalFlujos = 0;

		$flujosPay = [];

		$dataClient = new stdClass();
		$dataClient->name = "Flujos totales";
		$dataClient->data = [];

		foreach ($flowsGeneral as $key => $value) {
			$catsFlujo[] = $this->meses[$value[0]["mes"]];
			$dataClient->data[] = intval($value[0]["total"]);
			$totalFlujos+=intval($value[0]["total"]);
		}
		$dataAllFlujo  = $dataClient;

		$dataClientPayment = new stdClass();
		$dataClientPayment->name = "Flujos pagados";
		$dataClientPayment->data = [];


		foreach ($flowsPayment as $key => $value) {
			if (array_key_exists($value[0]["mes"], $flujosPay)) {
				$flujosPay[$value[0]["mes"]] += intval($value[0]["total"]);
			}else{
				$flujosPay[$value[0]["mes"]] = intval($value[0]["total"]);
			}
		}

		ksort($flujosPay);
		$keyFlujo = 0;
		foreach ($flujosPay as $key => $value) {
			$payObj = new stdClass();
			$payObj->y = intval($value);
			$payObj->month = intval($key);
			$dataKey = isset($dataClient->data[$keyFlujo]) && $dataClient->data[$keyFlujo] > 0 ? $dataClient->data[$keyFlujo] : 1;
			$payObj->avg = round( ($value/$dataKey) * 100 ,2);
			$dataClientPayment->data[] = $payObj;
			$keyFlujo++;
			// $datosFlujos[$key]->data[] = intval($value);
		}

		$dataAllPayment  = $dataClientPayment;

		$this->set("catsFlujo",$catsFlujo);
		$this->set("dataAllFlujo",$dataAllFlujo->data);
		

		$this->dataClientesComparision($dataAllPayment);
	}

	private function dataClientesComparision($dataAllPayment){
		$this->loadModel("ClientsNatural");
		$this->loadModel("ClientsLegal");

		$dataClientLegals = $this->ClientsLegal->find("all",["fields" => ["COUNT(id) total","MONTH(created) mes"], "conditions" => ["created >=" => date("Y-01-01"), "created <=" => date("Y-12-31"), "ClientsLegal.user_receptor" => $this->usuarios_search  ], "group" => ["MONTH(created)"], "recursive" => -1 ]);

		$dataClientNatural = $this->ClientsNatural->find("all",["fields" => ["COUNT(ClientsNatural.id) total","MONTH(ClientsNatural.created) mes"], "conditions" => ["ClientsNatural.created >=" => date("Y-01-01"), "ClientsNatural.created <=" => date("Y-12-31"), $this->subQueryDataMarketing(), "ClientsNatural.user_receptor" => $this->usuarios_search ], "group" => ["MONTH(ClientsNatural.created)"], "recursive" => -1, ]);

		$catsClientes = [];
		$datosClientes = [];
		$totalClientes = 0;

		$dataClient = new stdClass();
		$dataClient->name = "Clientes Naturales";
		$dataClient->data = [];

		foreach ($dataClientNatural as $key => $value) {
			$catsClientes[$value[0]["mes"]] = $this->meses[$value[0]["mes"]];
			$dataClient->data[] = intval($value[0]["total"]);			
			$totalClientes+=intval($value[0]["total"]);
		}

		$datosClientes[] = $dataClient;

		$dataClientLegalsOb = new stdClass();
		$dataClientLegalsOb->name = "Clientes Júridicos";
		$dataClientLegalsOb->data = [];


		foreach ($dataClientLegals as $key => $value) {
			$catsClientes[$value[0]["mes"]] = $this->meses[$value[0]["mes"]];
			$dataClientLegalsOb->data[] = abs(intval($value[0]["total"]));
			$totalClientes+=abs(intval($value[0]["total"]));
		}

		ksort($catsClientes);

		$catsClientes = array_values($catsClientes);

		$datosClientes[] = $dataClientLegalsOb;

		$this->set("catsClientes",$catsClientes);
		$this->set("datosClientes",$datosClientes);
		$this->set("totalClientes",$totalClientes);

		$this->getDataClientsPayments($dataClientLegals,$dataClientNatural,$dataAllPayment);
	}

	private function subQueryDataMarketing(){
		$this->loadModel("ProspectiveUser");

		$db = $this->ProspectiveUser->getDataSource();
		$subQuery = $db->buildStatement(
		    array(
		        'fields'     => array('ProspectiveUser.clients_natural_id'),
		        'table'      => $db->fullTableName($this->ProspectiveUser),
		        'alias'      => 'ProspectiveUser',
		        'limit'      => null,
		        'offset'     => null,
		        'joins'      => array(),
		        'conditions' => ["ProspectiveUser.origin" => "Marketing","ProspectiveUser.clients_natural_id >" => 0, "ProspectiveUser.clients_natural_id != " => null  ],
		        'order'      => null,
		        'group'      => null
		    ),
		    $this->ProspectiveUser
		);
		$subQuery = 'ClientsNatural.id NOT IN (' . $subQuery . ') ';
		return $db->expression($subQuery);
	}

	private function getDataClientsPayments($dataClientLegals,$dataClientNatural,$dataAllPayment){

		$this->loadModel("ClientsNatural");
		$this->loadModel("ClientsLegal");

		$idsNatualCreated = $this->ClientsNatural->find("list",["fields" => ["id","id"], "conditions" => ["ClientsNatural.created >=" => date("Y-01-01"), "ClientsNatural.created <=" => date("Y-12-31"),$this->subQueryDataMarketing(), "ClientsNatural.user_receptor" => $this->usuarios_search  ] ]);

		$naturalsPayment  = $this->ProspectiveUser->FlowStage->find("all",["fields" => ["PS.clients_natural_id","MONTH(PS.created) mes", "PS.id", "SUM(FlowStage.valor) total"], "joins" => [
			['table' => 'prospective_users','alias' => 'PS','type' => 'INNER','conditions' => array('PS.id = FlowStage.prospective_users_id AND FlowStage.payment_verification = 1 AND PS.clients_natural_id IS NOT NULL AND PS.clients_natural_id > 0')],
		], "conditions" => ["DATE(PS.created) >=" => date("Y-01-01"), "DATE(PS.created) <=" => date("Y-12-31"), "PS.user_id" => $this->usuarios_search  ], "group" => ["FlowStage.prospective_users_id"], "recursive" => -1 ]);

		$totalPaymentNewNatural = [];
		$totalPaymentOldNatural = [];
		$paymentByMonthNewNatural  = [];
		$paymentByMonthOldNatural  = [];

		foreach ($naturalsPayment as $key => $value) {
			if (in_array($value["PS"]["clients_natural_id"], $idsNatualCreated)) {
				if (!array_key_exists($value["0"]["mes"], $totalPaymentNewNatural)) {
					$totalPaymentNewNatural[ $value["0"]["mes"] ] = 1;
				}else{
					$totalPaymentNewNatural[ $value["0"]["mes"] ] += 1;
				}
				if (!array_key_exists($value["0"]["mes"], $paymentByMonthNewNatural)) {
					$paymentByMonthNewNatural[ $value["0"]["mes"] ] = is_null($value["0"]["total"]) ? 0 : $value["0"]["total"];
				}else{
					$paymentByMonthNewNatural[ $value["0"]["mes"] ] += is_null($value["0"]["total"]) ? 0 : $value["0"]["total"];
				}				
			}else{
				if (!array_key_exists($value["0"]["mes"], $totalPaymentOldNatural)) {
					$totalPaymentOldNatural[ $value["0"]["mes"] ] = 1;
				}else{
					$totalPaymentOldNatural[ $value["0"]["mes"] ] += 1;
				}
				if (!array_key_exists($value["0"]["mes"], $paymentByMonthOldNatural)) {
					$paymentByMonthOldNatural[ $value["0"]["mes"] ] = is_null($value["0"]["total"]) ? 0 : $value["0"]["total"];
				}else{
					$paymentByMonthOldNatural[ $value["0"]["mes"] ] += is_null($value["0"]["total"]) ? 0 : $value["0"]["total"];
				}
			}
		}
		
		$idsLegalsCreated = $this->ClientsLegal->find("list",["fields" => ["id","id"], "conditions" => ["created >=" => date("Y-01-01"), "created <=" => date("Y-12-31"), "user_receptor" =>$this->usuarios_search  ], "recursive" => -1 ]);

		$legalsPayment  = $this->ProspectiveUser->FlowStage->find("all",["fields" => ["CS.clients_legals_id","PS.id","MONTH(PS.created) mes","SUM(FlowStage.valor) total"], "joins" => [
			['table' => 'prospective_users','alias' => 'PS','type' => 'INNER','conditions' => array('PS.id = FlowStage.prospective_users_id AND FlowStage.payment_verification = 1 AND PS.clients_natural_id IS NULL AND PS.contacs_users_id > 0')],
			['table' => 'contacs_users','alias' => 'CS','type' => 'INNER','conditions' => array('PS.contacs_users_id = CS.id')],
		], "conditions" => ["DATE(PS.created) >=" => date("Y-01-01"), "DATE(PS.created) <=" => date("Y-12-31"), "PS.user_id" => $this->usuarios_search  ], "group" => ["FlowStage.prospective_users_id"], "recursive" => -1 ]);


		$allCreated  = $this->ProspectiveUser->find("all",["fields" => ["COUNT(id) total","MONTH(created) mes"], "conditions" => ["DATE(created) >=" => date("Y-01-01"), "DATE(created) <=" => date("Y-12-31"), "origin !=" => "Marketing", "ProspectiveUser.user_id" => $this->usuarios_search,  ], "group" => ["MONTH(created)"], "recursive" => -1, ]);


		$totalPaymentNewLegal = [];
		$totalPaymentOldLegal = [];
		$paymentByMonthNewLegal  = [];
		$paymentByMonthOldLegal  = [];

		foreach ($legalsPayment as $key => $value) {
			if (in_array($value["CS"]["clients_legals_id"], $idsLegalsCreated)) {
				if (!array_key_exists($value["0"]["mes"], $totalPaymentNewLegal)) {
					$totalPaymentNewLegal[ $value["0"]["mes"] ] = 1;
				}else{
					$totalPaymentNewLegal[ $value["0"]["mes"] ] += 1;
				}
				if (!array_key_exists($value["0"]["mes"], $paymentByMonthNewLegal)) {
					$paymentByMonthNewLegal[ $value["0"]["mes"] ] = is_null($value["0"]["total"]) ? 0 : $value["0"]["total"];
				}else{
					$paymentByMonthNewLegal[ $value["0"]["mes"] ] += is_null($value["0"]["total"]) ? 0 : $value["0"]["total"];
				}				
			}else{
				if (!array_key_exists($value["0"]["mes"], $totalPaymentOldLegal)) {
					$totalPaymentOldLegal[ $value["0"]["mes"] ] = 1;
				}else{
					$totalPaymentOldLegal[ $value["0"]["mes"] ] += 1;
				}
				if (!array_key_exists($value["0"]["mes"], $paymentByMonthOldLegal)) {
					$paymentByMonthOldLegal[ $value["0"]["mes"] ] = is_null($value["0"]["total"]) ? 0 : $value["0"]["total"];
				}else{
					$paymentByMonthOldLegal[ $value["0"]["mes"] ] += is_null($value["0"]["total"]) ? 0 : $value["0"]["total"];
				}
			}
		}

		$flujosPagadosClientesAll = [];

		foreach ($allCreated as $key => $value) {
			$flujosPagadosClientesAll[$value["0"]["mes"]] = abs(intval($value["0"]["total"]));
		}

		$catsClientesPayments    = [];

		$totalClients 		     = [];
		$totalNewPayment     	 = [];
		$totalOldPayment     	 = [];
		$totalNewPaymentValue  	 = [];
		$totalOldPaymentValue  	 = [];

		foreach ($dataClientNatural as $key => $value) {
			$catsClientesPayments[] = $this->meses[$value[0]["mes"]];
			if (array_key_exists($value["0"]["mes"], $totalClients)) {
				$totalClients[$value["0"]["mes"]] += $value["0"]["total"];
			}else{
				$totalClients[$value["0"]["mes"]] = $value["0"]["total"];
			}
			$totalNewPaymentValue[$value["0"]["mes"]] = 0;
		}

		foreach ($dataClientLegals as $key => $value) {
			if (array_key_exists($value["0"]["mes"], $totalClients)) {
				$totalClients[$value["0"]["mes"]] += $value["0"]["total"];
			}else{
				$totalClients[$value["0"]["mes"]] = $value["0"]["total"];
			}
		}

		/****Nuevos Naturales y legales *****/

		$totalOldPaymentValue = $totalNewPaymentValue;

		foreach ($totalPaymentNewNatural as $key => $value) {
			if (array_key_exists($key, $totalNewPayment)) {
				$totalNewPayment[$key] += $value;
			}else{
				$totalNewPayment[$key] = $value;
			}
			$totalNewPaymentValue[$key] += $paymentByMonthNewNatural[$key];
			$totalOldPaymentValue[$value["0"]["mes"]] = 0;
		}

		foreach ($totalPaymentNewLegal as $key => $value) {
			if (array_key_exists($key, $totalNewPayment)) {
				$totalNewPayment[$key] += $value;
			}else{
				$totalNewPayment[$key] = $value;
			}
			@$totalNewPaymentValue[$key] += @$paymentByMonthNewLegal[$key];
			$totalOldPaymentValue[$value["0"]["mes"]] = 0;
		}
		/****Nuevos Naturales y legales *****/

		foreach ($totalPaymentOldNatural as $key => $value) {
			if (array_key_exists($key, $totalOldPayment)) {
				$totalOldPayment[$key] += $value;
			}else{
				$totalOldPayment[$key] = $value;
			}
			@$totalOldPaymentValue[$key] += @$paymentByMonthOldNatural[$key];
		}

		foreach ($totalPaymentOldLegal as $key => $value) {
			if (array_key_exists($key, $totalOldPayment)) {
				$totalOldPayment[$key] += $value;
			}else{
				$totalOldPayment[$key] = $value;
			}
			@$totalOldPaymentValue[$key] += isset($paymentByMonthNewLegal[$key]) ? $paymentByMonthNewLegal[$key] : 0;
		}

		$totalClientsPayment = [];

		foreach ($totalNewPaymentValue as $key => $value) {
			$objNewPayment 			= new stdClass();
			$objNewPayment->y 		= round(@$totalNewPayment[$key]);
			$objNewPayment->avg 	= round(@($totalNewPayment[$key]/$totalClients[$key]) * 100) ;
			$objNewPayment->payment = number_format(round($value),0,",",".");
			$totalClientsPayment[]  = $objNewPayment;
		}

		$pagadosViejosClientes    = [];

		$copyDataAll 				= $flujosPagadosClientesAll;
		$flujosPagadosClientesAll 	= [];

		foreach ($copyDataAll as $key => $value) {
			$flujosPagadosClientesAll[$key] = abs($value - @$totalClients[$key]);
		}

		foreach ($totalOldPaymentValue as $key => $value) {
			$objNewPayment 			= new stdClass();
			$objNewPayment->y 		= round(@$totalOldPayment[$key]);
			$objNewPayment->avg 	= @$totalOldPayment[$key] == 0 ? 0 : round(@($totalOldPayment[$key]/$flujosPagadosClientesAll[$key]) * 100) ;
			$objNewPayment->payment = number_format(round($value),0,",",".");
			$pagadosViejosClientes[]  = $objNewPayment;
		}


		$flujosPagadosClientesAll = array_values($flujosPagadosClientesAll);

		$this->set("dataAllPayment",$dataAllPayment->data);
		$this->set('totalClients',$totalClients);
		$this->set('totalClientsPayment',$totalClientsPayment);
		$this->set('catsClientesPayments',$catsClientesPayments);
		$this->set('flujosPagadosClientesAll',$flujosPagadosClientesAll);
		$this->set('pagadosViejosClientes',$pagadosViejosClientes);

	}

	private function getTotalComisiones(){
		$this->loadModel("Commision");
		$this->loadModel('ProspectiveUser');

		if (AuthComponent::user("role") != "Gerente General") {
			$existCommision = $this->Commision->findByUserId(AuthComponent::user("id"));
		}else{
			if (count($this->usuarios_search) == 1) {
				$existCommision = $this->Commision->findByUserId($this->usuarios_search[0]);
			}else{
				$existCommision = $this->Commision->findByUserId(AuthComponent::user("id"));
			}
		}

		if(!is_null($existCommision) && !empty($existCommision)){
			
			$totalPagar 			= 0;

			if (count($this->usuarios_search) == 1) {
				$sales 					= $this->ProspectiveUser->getSalesListRecibos(date("Y-m-1"),date("Y-m-t"),$this->usuarios_search[0]);
			}else{
				$sales 					= $this->ProspectiveUser->getSalesListRecibos(date("Y-m-1"),date("Y-m-t"),AuthComponent::user("id"));
			}			

			if(!empty($sales)){
				foreach ($sales as $key => $value) {				
					$dias 			=  		$this->calculateDays($value["ProspectiveUser"]["bill_date"],$value["Receipt"]["date_receipt"]);
					$percentaje 	=  		$this->getComissionPercentaje($dias,$existCommision);
					$pagar 			=  		($percentaje / 100) * floatval($value["Receipt"]["total_iva"]); 
					$totalPagar		+= 		$pagar;
				}
			}			
			return $totalPagar;
		}

		return null;

	}

	public function calculatePorcentajeMetaVentasMes($meta_mes_total_empresa,$total_ventas_empresa){
		$this->loadModel("Salesinvoice");
		$promedio = 1;
		if ($promedio != 0) {
			$promedio = $this->calcuatePromedioResult($total_ventas_empresa,$meta_mes_total_empresa);
		}
		return $promedio;
	}

	public function paymentVerificationSalesDayBusiness(){
		$pagosVerificadosFlujos 			= $this->ProspectiveUser->FlowStage->payment_verification_sales_day_business();
		$totalVentas 						= 0;
		if ($pagosVerificadosFlujos != 0) {
			$totalVerificadosFlujos 		= $this->ProspectiveUser->FlowStage->calculate_total_sales($pagosVerificadosFlujos);
			for ($i=0; $i < count($totalVerificadosFlujos); $i++) { 
				$totalVentas = (int)$totalVerificadosFlujos[$i]['priceQuotation'] + $totalVentas;
			}
		}
		return number_format($totalVentas,0,",",".");
	}

	public function paymentVerificationSalesYesterdayBusiness(){
		$pagosVerificadosFlujos 			= $this->ProspectiveUser->FlowStage->payment_verification_sales_yesterday_business();
		$totalVentas 						= 0;
		if ($pagosVerificadosFlujos != 0) {
			$totalVerificadosFlujos 		= $this->ProspectiveUser->FlowStage->calculate_total_sales($pagosVerificadosFlujos);
			for ($i=0; $i < count($totalVerificadosFlujos); $i++) { 
				$totalVentas = (int)$totalVerificadosFlujos[$i]['priceQuotation'] + $totalVentas;
			}
		}
		return number_format($totalVentas,0,",",".");
	}

	public function find_string_seeker(){
		$this->layout = false;
		$this->loadModel('ProspectiveUser');
		$this->loadModel('Template');
		if ($this->request->is('ajax') || $this->request->is('post')) {

			$validateContact    = $this->validateTimes(true);
	        $valid              = true;

	        if ((!empty($validateContact["contact"])) || !empty($validateContact["quotation"]) ) {
	            echo "Tienes flujos represados en asignado y contactado";
	            die();
	        }

			$texto 					= strtolower(trim($this->request->data['texto']));
			$datos_busqueda 		= array();

			$clientesNaturales 		= $this->ProspectiveUser->ClientsNatural->find_seeker($texto);
			if (count($clientesNaturales) > 0) {
				array_push($datos_busqueda,$clientesNaturales);
			}

			$clientesJuridicos 		= $this->ProspectiveUser->ContacsUser->ClientsLegal->find_seeker($texto);
			if (count($clientesJuridicos) > 0) {
				array_push($datos_busqueda,$clientesJuridicos);
			}

			$contactos 				= $this->ProspectiveUser->ContacsUser->find_seeker($texto);
			if (count($contactos) > 0) {
				array_push($datos_busqueda,$contactos);
			}

			$flujos 				= $this->ProspectiveUser->find_seeker($texto);
			if (count($flujos) > 0) {
				array_push($datos_busqueda,$flujos);
			}

			// $etapas 				= $this->ProspectiveUser->FlowStage->find_seeker($texto);
			// if (count($etapas) > 0) {
			// 	array_push($datos_busqueda,$etapas);
			// }

			$productos 				= $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->Product->find_seeker($texto);
			if (count($productos) > 0) {
				array_push($datos_busqueda,$productos);
			}

			$cotizaciones 			= $this->ProspectiveUser->FlowStage->Quotation->find_seeker($texto);
			if (count($cotizaciones) > 0) {
				array_push($datos_busqueda,$cotizaciones);
			}

			// $plantillas 			= $this->Template->find_seeker($texto);
			// if (count($plantillas) > 0) {
			// 	array_push($datos_busqueda,$plantillas);
			// }

			// $usuarios 				= $this->ProspectiveUser->User->find_seeker($texto);
			// if (count($usuarios) > 0) {
			// 	array_push($datos_busqueda,$usuarios);
			// }

			$technical_service 		= $this->ProspectiveUser->TechnicalService->find_seeker($texto);
			if (count($technical_service) > 0) {
				array_push($datos_busqueda,$technical_service);
			}

			$this->set(compact('datos_busqueda'));
		}
	}

	public function countFindStringSeeker(){
		$this->autoRender = false;
		$this->loadModel('ProspectiveUser');
		$this->loadModel('Template');
		if ($this->request->is('ajax') || $this->request->is('post')) {
			$texto 					= strtolower($this->request->data['texto']);
			$datos_busqueda 		= array();

			$validateContact    = $this->validateTimes(true);
	        $valid              = true;

	        if ((!empty($validateContact["contact"])) || !empty($validateContact["quotation"]) ) {
	            return 0;
	        }

			$clientesNaturales 		= $this->ProspectiveUser->ClientsNatural->find_seeker($texto);
			array_push($datos_busqueda,$clientesNaturales);

			$clientesJuridicos 		= $this->ProspectiveUser->ContacsUser->ClientsLegal->find_seeker($texto);
			array_push($datos_busqueda,$clientesJuridicos);

			$contactos 				= $this->ProspectiveUser->ContacsUser->find_seeker($texto);
			array_push($datos_busqueda,$contactos);

			$flujos 				= $this->ProspectiveUser->find_seeker($texto);
			array_push($datos_busqueda,$flujos);

			$etapas 				= $this->ProspectiveUser->FlowStage->find_seeker($texto);
			array_push($datos_busqueda,$etapas);

			$productos 				= $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->Product->find_seeker($texto);
			array_push($datos_busqueda,$productos);

			$cotizaciones 			= $this->ProspectiveUser->FlowStage->Quotation->find_seeker($texto);
			array_push($datos_busqueda,$cotizaciones);

			$plantillas 			= $this->Template->find_seeker($texto);
			array_push($datos_busqueda,$plantillas);

			$usuarios 				= $this->ProspectiveUser->User->find_seeker($texto);
			array_push($datos_busqueda,$usuarios);

			$technical_service 		= $this->ProspectiveUser->TechnicalService->find_seeker($texto);
			array_push($datos_busqueda,$technical_service);

			$filas = 0;
			foreach ($datos_busqueda as $model){
				foreach ($model as $value){
					$filas++;
				}
			}
			return $filas;
		}
	}

	public function gmail(){
		$this->loadModel('ProspectiveUser');
		$clientes 					= array();
		$clientesNaturales 			= $this->ProspectiveUser->ClientsNatural->get_users_email_name();
		$clienteContactos 			= $this->ProspectiveUser->ContacsUser->get_users_email_name();
		$clientes 					= array_merge($clientesNaturales, $clienteContactos);
		$this->set(compact('clientes'));
	}

	public function correo(){}

	public function tools(){}	


}
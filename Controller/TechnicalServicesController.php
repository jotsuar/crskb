<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');+

set_time_limit(0);
ini_set('memory_limit', '-1');


require_once '../Vendor/spreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class TechnicalServicesController extends AppController {

	public $components = array('Paginator');

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('report','view','reporte_sin_asignar','sin_terminar','notify_automatic_demora');
    }

    public function sendMessage($id) {
    	$this->layout = false;
    	$this->TechnicalService->bindModel(
			array("belongsTo" => array("ClientsLegal"))
		);
    	$technical = $this->TechnicalService->find("first",["recursive"=>1, "conditions" => ["TechnicalService.id" => $id] ]);
    	if ($this->request->is(array('post', 'put'))) {

    		$email    = is_null($technical["ClientsNatural"]["email"]) ? $technical["ContacsUser"]["email"] : $technical["ClientsNatural"]["email"];

			$mensaje  = $this->request->data["TechnicalService"]["message"];

			$servicio = $technical["TechnicalService"];
			$asesor   = $technical["User"];
			$name 	  = is_null($technical["ClientsNatural"]["name"]) ? $technical["ContacsUser"]["name"] : $technical["ClientsNatural"]["name"];
			$options  = array(
				'to'		=> $email,
				'template'	=> 'demora_manual',
				'subject'	=> 'Alerta Servicio técnico - '.date("Y-m-d").' - KEBCO AlmacenDelPintor.com',
				'vars'		=> compact("servicio","asesor","name","mensaje")
			);
			$this->sendMail($options);
    		$this->loadModel("Binnacle");
    		$dataBinacle = ["Binnacle" => [
    			"note" => "Nota enviada al usuario",
    			"mensaje" => $mensaje,
    			"technical_service_id" => $id,
    			"user_id" => AuthComponent::user("id"),
    			"date_ini" => date("Y-m-d H:i:s"),
    			"date_end" => date("Y-m-d H:i:s"),
    		]];
    		$this->Binnacle->create();
    		$this->Binnacle->save($dataBinacle);
    		return true;
    	}else{
    		$this->request->data = $technical;
    	}
    	$this->set("technical",$technical);
	}

    public function sin_terminar(){
    	$this->autoRender 		= false;
    	$conditions				= array('TechnicalService.state' => 0);
    	$this->TechnicalService->bindModel(
			array("belongsTo" => array("ClientsLegal"))
		);
    	$services 				= $this->TechnicalService->find("all",["conditions"=>$conditions]);
    	
    	if (count($services) == 0) {
    		return false;
    	}

    	$this->loadModel("Informe");
        $dataInforme = ["Informe" => [ "type" => "servicios_sin_terminar","datos" => json_encode($services), "total" => count($services), "fecha" => date("Y-m-d") ] ];

        $this->Informe->create();
        $this->Informe->save($dataInforme);




    	$emails 				= ["jhernandez@almacendelpintor.com","ventas1@almacendelpintor.com","ventasbogota@almacendelpintor.com"];

    	$options = array(
			'to'		=> $emails,
			'template'	=> 'report_no_terminados',
			'subject'	=> 'Servicios sin terminar Servicio técnico - '.date("Y-m-d").' - KEBCO AlmacenDelPintor.com',
			'vars'		=> compact("services")
		);


		$this->sendMail($options, true);
    }

    private function sendDataMailDemora($services,$tiempo){
    	foreach ($services as $key => $value) {
			$email    = is_null($value["ClientsNatural"]["email"]) ? $value["ContacsUser"]["email"] : $value["ClientsNatural"]["email"];

			$datosCliente = !is_null($value["ClientsNatural"]["email"]) ? $value["ClientsNatural"] : $value["ContacsUser"];

			$email    = "jotsuar@gmail.com";

			$servicio = $value["TechnicalService"];
			$asesor   = $value["User"];
			$name 	  = is_null($value["ClientsNatural"]["name"]) ? $value["ContacsUser"]["name"] : $value["ClientsNatural"]["name"];
			$options  = array(
				'to'		=> $email,
				'template'	=> 'demora_automatica',
				'subject'	=> 'Alerta Servicio técnico - '.date("Y-m-d").' - KEBCO AlmacenDelPintor.com',
				'vars'		=> compact("servicio","asesor","name","tiempo")
			);

			switch ($tiempo) {
				case 30:
					$value["TechnicalService"]["alerta_1"] = 1;
					$value["TechnicalService"]["alerta_2"] = 0;
					break;
				case 60:
					$value["TechnicalService"]["alerta_2"] = 1;
					$value["TechnicalService"]["alerta_3"] = 0;
					break;
				case 90:
					$value["TechnicalService"]["alerta_3"] = 1;
					$value["TechnicalService"]["alerta_4"] = 0;
					break;
				case 100:
					$value["TechnicalService"]["alerta_4"] = 1;
					$this->loadModel("ProspectiveUser");
					$this->ProspectiveUser->recursive = -1;
					$prospecto = $this->ProspectiveUser->findById($value["TechnicalService"]["prospective_users_id"]);
					$prospecto['ProspectiveUser']['description'] 		= 'Tiempo de gestión agotado';
					$prospecto['ProspectiveUser']['state_flow'] 		= Configure::read('variables.control_flujo.flujo_cancelado');
					$this->ProspectiveUser->save($prospecto);
					break;
			}

			
			$this->sendMail($options, true);

			$this->TechnicalService->save($value["TechnicalService"]);

		}
    }

    public function notify_automatic_demora(){
    	$this->autoRender 		= false;
    	$conditions				= array('TechnicalService.state' => 1, "ProspectiveUser.state_flow" => [1,2,3,4] );
    	
    	$conditions30 			= $conditions;
    	$conditions60 			= $conditions;
    	$conditions90 			= $conditions;
    	$conditions100 			= $conditions;

    	$conditions30["TechnicalService.fecha_1 !="] 	= null;
    	$conditions30["TechnicalService.fecha_1 <"] 	= date("Y-m-d");
    	$conditions30["TechnicalService.alerta_1"]  	= 0;
    	$this->TechnicalService->bindModel(	array("belongsTo" => array("ClientsLegal")));
    	$services30				= $this->TechnicalService->find("all",["conditions"=>$conditions30]);

    	if (!empty($services30)) {
    		$this->sendDataMailDemora($services30,30);
    	}

    	$conditions60["TechnicalService.fecha_2 !="] 	= null;
    	$conditions60["TechnicalService.fecha_2 <"] 	= date("Y-m-d");
    	$conditions60["TechnicalService.alerta_2"]  	= 0;
    	$this->TechnicalService->bindModel(	array("belongsTo" => array("ClientsLegal")));
    	$services60				= $this->TechnicalService->find("all",["conditions"=>$conditions60]);

    	if (!empty($services60)) {
    		$this->sendDataMailDemora($services60,60);
    	}

    	$conditions90["TechnicalService.fecha_3 !="] 	= null;
    	$conditions90["TechnicalService.fecha_3 <"] 	= date("Y-m-d");
    	$conditions90["TechnicalService.alerta_3"]  	= 0;
    	$this->TechnicalService->bindModel(	array("belongsTo" => array("ClientsLegal")));
    	$services90	= $this->TechnicalService->find("all",["conditions"=>$conditions90]);

    	if (!empty($services90)) {
    		$this->sendDataMailDemora($services90,90);
    	}

    	$conditions100["TechnicalService.fecha_4 !="] 	= null;
    	$conditions100["TechnicalService.fecha_4 <"] 	= date("Y-m-d");
    	$conditions100["TechnicalService.alerta_4"]  	= 1;
    	$this->TechnicalService->bindModel(	array("belongsTo" => array("ClientsLegal")));
    	$services100 = $this->TechnicalService->find("all",["conditions"=>$conditions100]);

    	if (!empty($services100)) {
    		$this->sendDataMailDemora($services100,100);
    	}
    }

    public function servicios_sin_pago(){
    	$this->autoRender 		= false;
    	$conditions				= array('TechnicalService.state' => 1, "ProspectiveUser.state_flow" => [1,2,3,4] );
    	$this->TechnicalService->bindModel(
			array("belongsTo" => array("ClientsLegal"))
		);
    	$services 				= $this->TechnicalService->find("all",["conditions"=>$conditions]);

    	if (count($services) == 0) {
    		return false;
    	}

    	$this->loadModel("Informe");
        $dataInforme = ["Informe" => [ "type" => "servicios_sin_pago","datos" => json_encode($services), "total" => count($services), "fecha" => date("Y-m-d") ] ];

        $this->Informe->create();
        $this->Informe->save($dataInforme);

    	$emails 				= ["jhernandez@almacendelpintor.com","ventas1@almacendelpintor.com","ventasbogota@almacendelpintor.com"];

    	$options = array(
			'to'		=> $emails,
			'template'	=> 'report_no_terminados',
			'subject'	=> 'Servicios sin terminar Servicio técnico - '.date("Y-m-d").' - KEBCO AlmacenDelPintor.com',
			'vars'		=> compact("services")
		);


		$this->sendMail($options, true);
    }

	public function index() {
		$usuarios_asesores 		 	= $this->TechnicalService->User->role_technical_service_user();
		$q 							= $this->request->query;
		$fechaInicioReporte 		= date("Y-m-d");
		$fechaFinReporte 			= date("Y-m-d");
		$filter 					= false;
		$conditions					= array('TechnicalService.state'=> 0);
		if (!empty($q)) {
			$conditions = $this->getContidionsFilter($conditions,$q);
			if (count($conditions) > 1) {
				$filter = true;
			}
			if (isset($q["fechas"]) && !empty($q["fechas"]) && $q["fechas"] == 1) {
				$fechaInicioReporte 		= $q["fechaIni"];
				$fechaFinReporte 			= $q["fechaEnd"];

				$conditions["DATE(TechnicalService.created) >="] = $fechaInicioReporte;
				$conditions["DATE(TechnicalService.created) <="] = $fechaFinReporte;
			}
			$this->set("q",$q);
		} 

		$order						= array('TechnicalService.id' => 'desc');
		$this->paginate 			= array(
							        	'limit' 		=> 20,
							        	'conditions' 	=> $conditions,
							        	'order'			=> $order
							    	);
		$technicalServices 			= $this->paginate('TechnicalService');
		$this->set(compact('technicalServices','usuarios_asesores',"fechaInicioReporte","fechaFinReporte","filter"));
		$this->dataFilters();
	}

	private function getContidionsFilter($conditions, $q){

		if (isset($q["brand"]) && !empty($q["brand"])) {
			$conditions["ProductTechnical.brand"] = $q["brand"];
		}
		if (isset($q["cliente"]) && !empty($q["cliente"])) {
			if(strpos($q["cliente"], "_LEGAL") === false){
            	$conditions["TechnicalService.clients_natural_id"] = str_replace("_NATURAL", "", $q["cliente"]);
	        }else{
	            $conditions["TechnicalService.clients_legal_id"] = str_replace("_LEGAL", "", $q["cliente"]);
	        }
		}
		if (isset($q["user_id"]) && !empty($q["user_id"])) {
			$conditions["TechnicalService.user_id"] = $q["user_id"];
		}
		if (isset($q["equipment"]) && !empty($q["equipment"])) {
			$conditions["UPPER(ProductTechnical.equipment) LIKE"] = '%'.mb_strtoupper($q["equipment"]).'%';
		}
		if (isset($q["part_number"]) && !empty($q["part_number"])) {
			$conditions["UPPER(ProductTechnical.part_number) LIKE"] = '%'.mb_strtoupper($q["part_number"]).'%';
		}
		if (isset($q["serial_number"]) && !empty($q["serial_number"])) {
			$conditions["UPPER(ProductTechnical.serial_number) LIKE"] = '%'.mb_strtoupper($q["serial_number"]).'%';
		}
		if (isset($q["serial_garantia"]) && !empty($q["serial_garantia"])) {
			$conditions["UPPER(ProductTechnical.serial_garantia) LIKE"] = '%'.mb_strtoupper($q["serial_garantia"]).'%';
		}
		if (isset($q["flujo"]) && $q["flujo"] != "") {
			$condicion = $q["flujo"] == 1 ? ">" : "";
			$conditions["TechnicalService.prospective_users_id ".$condicion] = 0;
		}
		if (isset($q["flujo_id"]) && $q["flujo_id"] != "") {
			$conditions["TechnicalService.prospective_users_id"] = $q["flujo_id"];
		}
		if (isset($q["txt_buscador"]) && !empty($q["txt_buscador"])) {
			$conditions["UPPER(TechnicalService.code) LIKE"] = '%'.mb_strtoupper($q["txt_buscador"]).'%';
		}
		return $conditions;
	}

	private function dataFilters(){
		$brands 	= $this->TechnicalService->ProductTechnical->find("list",["fields"=>["brand","brand"]]);

		$naturals 	= $this->TechnicalService->find("list",["fields"=>["clients_natural_id","clients_natural_id"], "conditions"=> ["clients_natural_id >" => 0] ]);

		$legals 	= $this->TechnicalService->find("list",["fields"=>["clients_legal_id","clients_legal_id"], "conditions"=> ["clients_natural_id" => 0] ]);

		$clientsNaturals = $this->getDataNaturals(null, $naturals);
		$clientsLegals 	 = $this->getDataLegals(null, $legals);

		$clientes 		 = array_merge($clientsNaturals,$clientsLegals);

		

		$this->set(compact("brands","clientes"));
	}

	public function identificadores($id = null) {
		if (!$this->TechnicalService->exists($id)) {
			throw new NotFoundException('El servicio técnico no existe');
		}
		$technicalServices 				= $this->TechnicalService->get_data($id);
		$equipos_servicio 				= $this->TechnicalService->ProductTechnical->get_all($id);
		$this->set(compact('technicalServices','equipos_servicio'));
	}

	public function reporte_sin_asignar(){
		$this->autoRender = false;	

		$conditions = array(
			"TechnicalService.state" => 0,
			"User.role !=" => array(Configure::read('variables.roles_usuarios.Servicio Técnico'),Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'))
		);

		$order = array("TechnicalService.created" => "ASC");

		$this->TechnicalService->bindModel(
			array("belongsTo" => array("ClientsLegal"))
		);

		$serviciosSinAsignar = $this->TechnicalService->find("all",compact("conditions","order"));

		if(empty($serviciosSinAsignar)){
			return false;
		}

		$this->loadModel("Informe");
        $dataInforme = ["Informe" => [ "type" => "reporte_sin_asignar","datos" => json_encode($serviciosSinAsignar), "total" => count($serviciosSinAsignar), "fecha" => date("Y-m-d") ] ];

        $this->Informe->create();
        $this->Informe->save($dataInforme);

        return true;

		$emails = array("gerencia@almacendelpintor.com");

		$options = array(
			'to'		=> $emails,
			'template'	=> 'report_no_assinged',
			'subject'	=> 'Servicios sin asignar a rol de Servicio técnico KEBCO AlmacenDelPintor.com',
			'vars'		=> compact("serviciosSinAsignar")
		);


		$this->sendMail($options, true);

	}


	public function flujos(){
		$get 				= $this->request->query;
		$conditions 		= array('ProspectiveUser.type !=' => 0,'ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'));
		if (!empty($get)) {
			if (isset($get['q'])) {
				$conditionsBusqueda 	= array('ProspectiveUser.type !=' => 0);
				if (isset($this->request->params['named']['page'])) {
					unset($this->request->params['named']['page']);
				}

				$conditionsBusqueda["OR"] = [ "ProspectiveUser.id" => $get["q"], 'LOWER(TechnicalService.code) LIKE' 			=> '%'.mb_strtolower(trim($get["q"])).'%' ]; 
				$conditions = $conditionsBusqueda;
				// $conditions 	= $this->searchUser($get['q'],$conditionsBusqueda); 

			}
			if (isset($get['filterEtapa'])) {
				$conditions 	= $this->filterUser($get['filterEtapa'],$conditions1 = array());
				$conditions["ProspectiveUser.type !="] = 0;
			}
			if (isset($get['filterAsesores'])) {
				$conditions 	= $this->filterAsesor($get['filterAsesores'], $conditions);
			}
			if (isset($get['filter'])) {
				switch ($get['filter']) {
					case '8':
						$conditions 	= array('ProspectiveUser.type !=' => 0,'ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_finalizado'));
						break;
				}
			}
			if(isset($get["fechaInicioReporte"]) && isset($get["fechaFinReporte"])){
                $conditions["DATE(ProspectiveUser.created) >="] = $get["fechaInicioReporte"];
                $conditions["DATE(ProspectiveUser.created) <="] = $get["fechaFinReporte"];
                $this->set("fechaInicioReporte",$get["fechaInicioReporte"]);
                $this->set("fechaFinReporte",$get["fechaFinReporte"]);
            }
		}
		
		$order					= array('ProspectiveUser.id' => 'desc');
		$this->paginate 		= array(
	        'limit' 			=> 20,
	        'recursive' 		=> -1,
	        "joins" => [
	        	['table' => 'technical_services','alias' => 'TechnicalService','type' => 'INNER','conditions' => array('TechnicalService.prospective_users_id = ProspectiveUser.id')]
	        ],
	        'order' 			=> $order,
	        'conditions'		=> $conditions,
	        "fields" 			=> ["ProspectiveUser.*","TechnicalService.*"]
	    );
	    $prospectiveUsers 		= $this->paginate('ProspectiveUser');
	    $usuarios_asesores 		= $this->TechnicalService->User->role_technical_service_user();
	    // if (count($prospectiveUsers) < 1) {
	    // 	$this->redirect(array('action' => 'filtro_null_index'));
	    // }
	    $this->set(compact('prospectiveUsers','usuarios_asesores'));
	}

	public function filtro_null_index(){}

	public function process() {
		$q 							= $this->request->query;
		$fechaInicioReporte 		= date("Y-m-d");
		$fechaFinReporte 			= date("Y-m-d");
		$filter 					= false;
		$conditions					= array('TechnicalService.state'=> 1, "ProspectiveUser.state_flow" => [1,2,3,4,5] );


		if (!empty($q)) {
			$conditions = $this->getContidionsFilter($conditions,$q);
			if (count($conditions) > 1) {
				$filter = true;
			}
			if (isset($q["fechas"]) && !empty($q["fechas"]) && $q["fechas"] == 1) {
				$fechaInicioReporte 		= $q["fechaIni"];
				$fechaFinReporte 			= $q["fechaEnd"];

				$conditions["DATE(TechnicalService.created) >="] = $fechaInicioReporte;
				$conditions["DATE(TechnicalService.created) <="] = $fechaFinReporte;
			}
			$this->set("q",$q);
		}

		if(isset($q["generate_excel"])){
			$technicalServices = $this->TechnicalService->find("all",["recursive" => 1, "conditions" => $conditions, "order" => ["TechnicalService.id" => "DESC"], "fields" => ["TechnicalService.*", "ProspectiveUser.*","User.name","ClientsNatural.*","ContacsUser.*",]  ]);

			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$spreadsheet->getProperties()->setCreator('Kebco SAS')
				        ->setLastModifiedBy('Kebco SAS')
				        ->setTitle('Servicios técnicos CRM')
				        ->setSubject('Servicios técnicos CRM')
				        ->setDescription('Servicios técnicos en proceso CRM')
				        ->setKeywords('Servicios técnicos CRM st')
				        ->setCategory('Servicios técnicos');

			$spreadsheet->setActiveSheetIndex(0)
				        ->setCellValue('A1', 'Asesor')
				        ->setCellValue('B1', 'Código')
				        ->setCellValue('C1', 'Cliente')
				        ->setCellValue('D1', 'Fecha de Ingreso')
				        ->setCellValue('E1', 'Fecha diagnóstico')
				        ->setCellValue('F1', 'Fecha límite')
				        ->setCellValue('G1', 'Flujo')
				        ->setCellValue('H1', 'Estado Flujo')
				        ->setCellValue('I1', 'Nombre Cotización');

			$i = 2;

			foreach ($technicalServices as $key => $value) {


				$flowState = $this->name_state_flujo($value["ProspectiveUser"]["state_flow"]);

				if($value["TechnicalService"]["clients_natural_id"] > 0){
					$clientName = $value["ClientsNatural"]["name"]." | ".$value["ClientsNatural"]["identification"];
				}else{
					$contactName = $this->TechnicalService->ContacsUser->findById($value["TechnicalService"]["contacs_users_id"]);
					$clientName  = $contactName["ContacsUser"]["name"]." | ".$contactName["ClientsLegal"]["name"]." | ".$contactName["ClientsLegal"]["nit"];
				}

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $value["User"]["name"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["TechnicalService"]["code"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $clientName);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $value['TechnicalService']['created']);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $value['TechnicalService']['date_end']);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $value['TechnicalService']['deadline']);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $value["TechnicalService"]["prospective_users_id"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $flowState);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $this->find_name_document_quotation_send($value["TechnicalService"]["prospective_users_id"]));
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

			$spreadsheet->getActiveSheet()->setTitle('Servicios técnicos en proceso');
			$spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="servicios_tecnicos_proceso'.date("YmdHi").'.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 2025 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0

			$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
			exit;


		}else{


			$order						= array('TechnicalService.id' => 'desc');
			
			$this->paginate 			= array(
								        	'limit' 		=> 20,
								        	'recursive'  	=> 1,
								        	'conditions' 	=> $conditions,
								        	'order'			=> $order,
								        	'fields'		=> [ "TechnicalService.*", "ProspectiveUser.*","User.name" ]
								    	);
			$technicalServices 			= $this->paginate('TechnicalService');
		}

		$usuarios_asesores 		 	= $this->TechnicalService->User->role_technical_service_user();
		$this->dataFilters();
		$this->set(compact('technicalServices','usuarios_asesores',"fechaInicioReporte","fechaFinReporte","filter"));
	}

	public function technical() {
		$q 							= $this->request->query;
		$fechaInicioReporte 		= date("Y-m-d");
		$fechaFinReporte 			= date("Y-m-d");
		$filter 					= false;
		$conditions					= array("OR" => [ ['TechnicalService.state'=> 1,"ProspectiveUser.state_flow" => [6,8]], ['TechnicalService.state'=> 2] ] );
		if (!empty($q)) {
			$conditions = $this->getContidionsFilter($conditions,$q);
			if (count($conditions) > 1) {
				$filter = true;
			}
			if (isset($q["fechas"]) && !empty($q["fechas"]) && $q["fechas"] == 1) {
				$fechaInicioReporte 		= $q["fechaIni"];
				$fechaFinReporte 			= $q["fechaEnd"];

				$conditions["DATE(TechnicalService.created) >="] = $fechaInicioReporte;
				$conditions["DATE(TechnicalService.created) <="] = $fechaFinReporte;
			}
			$this->set("q",$q);
		}
		$order						= array('TechnicalService.id' => 'desc');
		$this->paginate 			= array(
							        	'limit' 		=> 20,
							        	'conditions' 	=> $conditions,
							        	'order'			=> $order
							    	);
		$technicalServices 			= $this->paginate('TechnicalService');
		$usuarios_asesores 		 	= $this->TechnicalService->User->role_technical_service_user();
		$this->dataFilters();
		$this->set(compact('technicalServices','usuarios_asesores',"fechaInicioReporte","fechaFinReporte","filter"));
	}

	public function upload_document($id = null) {
		$this->layout = false;
		if(!is_numeric ($id)){
			$id 				= $this->desencriptarCadena($id);
		}
		if (!$this->TechnicalService->exists($id)) {
			throw new NotFoundException('El servicio técnico no existe');
		}

		if ($this->request->is("post")) {
			$this->loadDocumentPdf($this->request->data["TechnicalService"]["document"],"servicios_tecnicos");
			$this->request->data["TechnicalService"]["document"] = $this->Session->read("documentoModelo");
			$this->TechnicalService->save($this->request->data["TechnicalService"]);

			$this->Session->setFlash('El servicio técnico se ha guardado satisfactoriamente', 'Flash/success');
			return $this->redirect(array('action' => 'index'));
		}

		$technicalService 				= $this->TechnicalService->findById($id);
		$this->set(compact('technicalService'));
	}

	public function terminate($id) {
		if(!is_numeric ($id)){
			$id 				= $this->desencriptarCadena($id);
		}
		if (!$this->TechnicalService->exists($id)) {
			throw new NotFoundException('El servicio técnico no existe');
		}
		$technicalServices 				= $this->TechnicalService->findById($id);
		$technicalServices["TechnicalService"]["state"] = 2;
		$technicalServices["TechnicalService"]["modified"] = date("Y-m-d H:i:s");
		$this->TechnicalService->save($technicalServices["TechnicalService"]);
		$this->Session->setFlash('El servicio técnico se ha guardado satisfactoriamente', 'Flash/success');
		return $this->redirect(array('action' => 'process'));
	}

	public function view($id = null) {
		if(!is_numeric ($id)){
			$id 				= $this->desencriptarCadena($id);
		}
		if (!$this->TechnicalService->exists($id)) {
			throw new NotFoundException('El servicio técnico no existe');
		}

		if ($this->request->is("post") || $this->request->is("put")) {
			$datos = $this->request->data;
			if (!empty($datos["TechnicalService"]["firma_img"])) {
	            $datos["TechnicalService"]["firma_img"]    	   = str_replace("data:image/png;base64,", "", $datos["TechnicalService"]["firma_img"]);
	            $datos['TechnicalService']['firma_cliente']    = $this->saveImage64($datos["TechnicalService"]["firma_img"],'asistencias/imagenes');
	        }

	        if ($this->TechnicalService->save($datos['TechnicalService'])) {
	        	$this->Session->setFlash('El servicio técnico se ha guardado satisfactoriamente', 'Flash/success');
	        } else {
				$this->Session->setFlash('El servicio técnico no se ha guardado, por favor inténtalo mas tarde','Flash/error');
			}
		}

		$technicalServices 				= $this->TechnicalService->findById($id);
		$equipos_servicio 				= $this->TechnicalService->ProductTechnical->get_all($id);
		$accesorios_mantenimiento 		= Configure::read('variables.accesorios_equipo_mantenimiento');
		$this->set(compact('technicalServices','accesorios_mantenimiento','equipos_servicio'));
	}

	public function send_client_data($id){
		$this->layout = false;
		if(!is_numeric ($id)){
			$id 				= $this->desencriptarCadena($id);
		}
		if (!$this->TechnicalService->exists($id)) {
			throw new NotFoundException('El servicio técnico no existe');
		}
		$technicalServices 				= $this->TechnicalService->findById($id);

		if($this->request->is("post") || $this->request->is("put")){
			$datos = $this->request->data;
			$this->loadModel("Binnacle");

			$binnacleData = ["Binnacle" =>
				[
					"note" => "Se envía mensaje informando mora, la nueva fecha es: ".$datos["TechnicalService"]["deadline"].", el mensaje enviad es: ". $datos["TechnicalService"]["texto"],
					"deadline" => $datos["TechnicalService"]["deadline"],
					"date_ini" => date("Y-m-d H:i:s"),
					"date_end" => date("Y-m-d H:i:s"),
					"user_id"  => AuthComponent::user("id"),
					"technical_service_id" => $datos["TechnicalService"]["id"]
 				]
			 ];

			 $this->Binnacle->create();
			 $this->Binnacle->save($binnacleData);


			if($technicalServices["TechnicalService"]["clients_natural_id"] != 0){
				$nombre = $technicalServices["ClientsNatural"]["name"];
				$email = $technicalServices["ClientsNatural"]["email"];
			}else{
				$this->loadModel("ContacsUser");
				$contacto = $this->ContacsUser->findById($technicalServices["TechnicalService"]["contacs_users_id"]);
				$nombre   = $contacto["ContacsUser"]["name"]. " | ".$contacto["ContacsUser"]["empresa"];
				$email = $contacto["ContacsUser"]["email"];
			}
			$datos_asesor = $technicalServices;
			$emails 	  = [AuthComponent::user("email"),$email,$datos_asesor["User"]["email"]];

			$texto 		  = $this->request->data["TechnicalService"]["texto"];

			$deadline 	  = $datos["TechnicalService"]["deadline"];




			$options = array(
				'to'		=> $emails,
				'template'	=> 'informe_customer_st',
				'subject'	=> 'Te informamos de una anomalía en en la orden de servicio '.$technicalServices["TechnicalService"]["code"].' - KEBCO AlmacenDelPintor.com',
				'vars'		=> compact("technicalServices","datos_asesor","texto", "deadline","nombre"),
			);			


			if($this->TechnicalService->save($this->request->data)){
				$this->sendMail($options, true);
				$this->Session->setFlash('El servicio técnico se ha guardado satisfactoriamente', 'Flash/success');
			}
			return $this->redirect($this->request->referer());
		}

		$equipos_servicio 				= $this->TechnicalService->ProductTechnical->get_all($id);
		$accesorios_mantenimiento 		= Configure::read('variables.accesorios_equipo_mantenimiento');
		$this->set(compact('technicalServices','accesorios_mantenimiento','equipos_servicio','id'));
	}

	public function report($id = null){
		if (!is_numeric($id)) {
			$id = $this->desencriptarCadena($id);
		}
		if (!$this->TechnicalService->exists($id)) {
			throw new NotFoundException('El servicio técnico no existe');
		}
		$technicalServices 				= $this->TechnicalService->get_data($id);
		$equipos_ingresados_num 		= $this->TechnicalService->ProductTechnical->count_product_technical($id);
		$productClient 					= $this->TechnicalService->ProductTechnical->get_all($id);
		$this->set(compact('technicalServices','equipos_ingresados_num','productClient'));
	}

	public function btn_equipo_nuevo(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			$equipos_creados 				= $this->request->data['equipos_ingresados'];
			$this->set(compact('equipos_creados'));
		}
	}

	public function equipo_nuevo(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			$equipos_creados 				= $this->request->data['equipos_ingresados'];
			$marca 							= Configure::read('variables.marcaProduct');
			$estados_mantenimiento 			= Configure::read('variables.estados_equipo_mantenimiento');
			$accesorios_mantenimiento 		= Configure::read('variables.accesorios_equipo_mantenimiento');
			$this->set(compact('marca','estados_mantenimiento','accesorios_mantenimiento','equipos_creados'));
		}
	}

	public function add() {
		if ($this->request->is('post')) {

			$datos 											= $this->validateData('add',null);
			$datos['TechnicalService']['code'] 				= $this->generateCodeServices();
			if (empty($this->request->data['TechnicalService']["1"]['user_id'])) {
				$datos['TechnicalService']['user_id'] 			= AuthComponent::user('id');
			}else{
				$datos['TechnicalService']['user_id'] 			= $this->request->data['TechnicalService']["1"]['user_id'];
			}
			$datos["TechnicalService"]["contact_name"]		= $this->request->data["TechnicalService"]["1"]["contact_name"];
			$datos["TechnicalService"]["contact_phone"]		= $this->request->data["TechnicalService"]["1"]["contact_phone"];
			$datos["TechnicalService"]["contact_identification"]		= $this->request->data["TechnicalService"]["1"]["contact_identification"];
			$datos["TechnicalService"]["contact_address"]		= $this->request->data["TechnicalService"]["1"]["contact_address"];
			$datos['TechnicalService']['wpp'] 			= $this->request->data['TechnicalService']["1"]['wpp'];
			$datos['TechnicalService']['prospective_users_id'] 			= 0;

			$datos['TechnicalService']['state'] 			= 0;
			if (isset($datos['ProductTechnical'])) {
				$this->TechnicalService->create();
				if ($this->TechnicalService->save($datos['TechnicalService'])) {
					foreach ($datos['ProductTechnical'] as $value) {
						$value['technical_services_id'] 		= $this->TechnicalService->id;
						$this->TechnicalService->ProductTechnical->create();
						$this->TechnicalService->ProductTechnical->save($value);
						$j 															= 0;
						foreach ($value['Accessory'] as $valueAcces) {
							$value['Accessory'][$j]['technical_services_id'] 		= $this->TechnicalService->id;
							$value['Accessory'][$j]['product_technicals_id'] 		= $this->TechnicalService->ProductTechnical->id;
							$j++;
						}
						$this->TechnicalService->ProductTechnical->Accessory->create();
						$this->TechnicalService->ProductTechnical->Accessory->saveAll($value['Accessory']);
					}
					$this->saveDataLogsUser(2,'TechnicalService',$this->TechnicalService->id);
					$data_client 		= $this->informationClient($datos['TechnicalService']['contacs_users_id'],$datos['TechnicalService']['clients_natural_id']);
					$data_asesor 		= $this->TechnicalService->User->get_data($datos['TechnicalService']['user_id']);
					$numero_equipos 	= $this->TechnicalService->ProductTechnical->count_product_technical($this->TechnicalService->id);
					$data_servicio['TechnicalService']['id'] 		= $this->TechnicalService->id;
					$data_servicio['TechnicalService']['code'] 		= $datos['TechnicalService']['code'];
					$this->sendEmailClient($data_client,$data_asesor,$data_servicio,$numero_equipos,'entry',0);
					$this->Session->setFlash('El servicio técnico se ha guardado satisfactoriamente', 'Flash/success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('El servicio técnico no se ha guardado, por favor inténtalo mas tarde','Flash/error');
				}
			} else {
				$this->Session->setFlash('El servicio técnico debe tener como mínimo un equipo','Flash/error');
			}
		}
		$this->loadModel("Aditional");
		$marca 							= Configure::read('variables.marcaProduct');
		$estados_mantenimiento 			= Configure::read('variables.estados_equipo_mantenimiento');
		$accesorios_mantenimiento 		= $this->Aditional->find("list",["fields"=>["accesorio","accesorio"],"conditions"=>["state" => 1]]);
		// $clientsLegals 				= $this->TechnicalService->ContacsUser->ClientsLegal->find('list');
		// $clientsNaturals	 			= $this->TechnicalService->ClientsNatural->find('list');
		$clientsLegals 					= [];
		$clientsNaturals	 			= [];
		$usuarios_asesores 		 	= $this->TechnicalService->User->role_technical_service_user();

		$this->set(compact('clientsNaturals','marca','clientsLegals','estados_mantenimiento','accesorios_mantenimiento','usuarios_asesores'));
	}

	public function get_user(){
        $this->autoRender = false;

        $this->request->query = isset($this->request->query["q"]) ? ["q" => $this->request->query["q"], "type" => $this->request->query["type"] ] : ["q" => "", "type" => $this->request->query["type"] ];

        if($this->request->query["type"] != "natural"){
        	$clientes      = $this->getClientsLegals($this->request->query["q"]);
        }else{
        	$clientes      = $this->getClientsNaturals($this->request->query["q"]);
        }

        $result             = [];

        if (!empty($clientes)) {            
            foreach ($clientes as $key => $value) {
                $result[] = ["id" => str_replace(["_NATURAL","_LEGAL"], "", $key), "text" => $value ];
            }
        }

        return empty($result) ? json_encode([]) :  json_encode(["items" => $result ]);
    }

	public function saveDataAjax(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$datos 											= $this->validateData('add',null);
			$datos['TechnicalService']['code'] 				= $this->generateCodeServices();
			$datos['TechnicalService']['user_id'] 			= AuthComponent::user('id');
			if (isset($datos['ProductTechnical'])) {
				$this->TechnicalService->create();
				if ($this->TechnicalService->save($datos['TechnicalService'])) {
					foreach ($datos['ProductTechnical'] as $value) {
						$value['technical_services_id'] 		= $this->TechnicalService->id;
						$this->TechnicalService->ProductTechnical->create();
						$this->TechnicalService->ProductTechnical->save($value);
						$j 															= 0;
						foreach ($value['Accessory'] as $valueAcces) {
							$value['Accessory'][$j]['technical_services_id'] 		= $this->TechnicalService->id;
							$value['Accessory'][$j]['product_technicals_id'] 		= $this->TechnicalService->ProductTechnical->id;
							$j++;
						}
						$this->TechnicalService->ProductTechnical->Accessory->create();
						$this->TechnicalService->ProductTechnical->Accessory->saveAll($value['Accessory']);
					}
					$this->saveDataLogsUser(2,'TechnicalService',$this->TechnicalService->id);
					$data_client 		= $this->informationClient($datos['TechnicalService']['contacs_users_id'],$datos['TechnicalService']['clients_natural_id']);
					$data_asesor 		= $this->TechnicalService->User->get_data($datos['TechnicalService']['user_id']);
					$numero_equipos 	= $this->TechnicalService->ProductTechnical->count_product_technical($this->TechnicalService->id);
					$data_servicio['TechnicalService']['id'] 		= $this->TechnicalService->id;
					$data_servicio['TechnicalService']['code']		= $datos['TechnicalService']['code'];
					$this->sendEmailClient($data_client,$data_asesor,$data_servicio,$numero_equipos,'entry',0);
					$this->Session->setFlash('El servicio técnico se ha guardado satisfactoriamente', 'Flash/success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('El servicio técnico no se ha guardado, por favor inténtalo mas tarde','Flash/error');
				}
			}
		}
	}

	public function edit($id = null) {
		if (!$this->TechnicalService->exists($id)) {
			throw new NotFoundException('El servicio técnico no existe');
		}
		if ($this->TechnicalService->state_valid_edit($id)) {
			if ($this->request->is(array('post', 'put'))) {
				$datos 											= $this->validateData('edit',$id);
				$datos['TechnicalService']['id']	 			= $id;

				if (isset($datos['ProductTechnical'])) {
					if ($this->TechnicalService->save($datos['TechnicalService'])) {
						$this->deleteAccesoris($id);
						$this->deleteEquiposTecnico($id);
						foreach ($datos['ProductTechnical'] as $value) {
							$value['technical_services_id'] 		= $id;
							$this->TechnicalService->ProductTechnical->create();
							$this->TechnicalService->ProductTechnical->save($value);
							$j 															= 0;
							foreach ($value['Accessory'] as $valueAcces) {
								$value['Accessory'][$j]['technical_services_id'] 		= $id;
								$value['Accessory'][$j]['product_technicals_id'] 		= $this->TechnicalService->ProductTechnical->id;
								$j++;
							}
							$this->TechnicalService->ProductTechnical->Accessory->create();
							$this->TechnicalService->ProductTechnical->Accessory->saveAll($value['Accessory']);
						}
						$this->saveDataLogsUser(3,'TechnicalService',$id);
						$this->Session->setFlash('El servicio técnico ha sido actualizado','Flash/success');
						return $this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash('El servicio técnico no ha sido actualizado, por favor inténtalo más tarde','Flash/error');
					}
				} else {
					$this->Session->setFlash('El servicio técnico debe tener como mínimo un equipo','Flash/error');
				}
			}
			$datosT 						= $this->TechnicalService->get_data($id);
			$equipos_ingresados_num 		= $this->TechnicalService->ProductTechnical->count_product_technical($id);
			$id_client_legal 				= '';
			if ($datosT['TechnicalService']['clients_natural_id'] == 0) {
				$id_client_legal_model 		= $this->TechnicalService->ContacsUser->find_id_bussines($datosT['TechnicalService']['contacs_users_id']);
				$id_client_legal 			= $id_client_legal_model['ContacsUser']['clients_legals_id'];
			}
			$this->loadModel("Aditional");
			$marca 							= Configure::read('variables.marcaProduct');
			$accesorios_mantenimiento 		= $this->Aditional->find("list",["fields"=>["accesorio","accesorio"],"conditions"=>["state" => 1]]);
			$estados_mantenimiento 			= Configure::read('variables.estados_equipo_mantenimiento');
			$accesorios_mantenimiento 		= Configure::read('variables.accesorios_equipo_mantenimiento');
			$equipos_servicio 				= $this->TechnicalService->ProductTechnical->get_all($id);
			$clientsLegals 					= $this->TechnicalService->ContacsUser->ClientsLegal->find('list');
			$clientsNaturals 				= $this->TechnicalService->ClientsNatural->find('list');
			$this->set(compact('clientsNaturals','clientsLegals','marca','datosT','id_client_legal','estados_mantenimiento','accesorios_mantenimiento','equipos_servicio','equipos_ingresados_num'));
		} else {
			$this->Session->setFlash('No es posible editar los datos del servicio','Flash/error');
		}
	}

	public function deleteAccesoris($id){
		$this->TechnicalService->ProductTechnical->Accessory->deleteAll([
								'Accessory.technical_services_id' => $id
							],false
						);
	}

	public function deleteEquiposTecnico($id){
		$this->TechnicalService->ProductTechnical->deleteAll([
								'ProductTechnical.technical_services_id' => $id
							],false
						);
	}

	public function generateCodeServices(){
		$filas 				= (int) $this->TechnicalService->count_services() + 1;
		if ($filas < 10) {
           $codigo         = Configure::read('variables.code_servicio_tecnico').'000'.$filas;
        } else if($filas > 9 && $filas < 100){
            $codigo         = Configure::read('variables.code_servicio_tecnico').'00'.$filas;
        } else if($filas > 99 && $filas < 1000){
            $codigo         = Configure::read('variables.code_servicio_tecnico').'0'.$filas;
        } else {
            $codigo         = Configure::read('variables.code_servicio_tecnico').$filas;
        }
		return $codigo;
	}

	public function validateData($accion,$id){
		if ($this->request->data['inlineRadioOptions'] == 'Natural') {
			$datos['TechnicalService']['clients_natural_id'] 				= $this->request->data['TechnicalService']['clients_natural_id'];
			$datos['TechnicalService']['contacs_users_id'] 					= 0;
			$datos['TechnicalService']['clients_legal_id'] 					= 0;

		} else {
			$datos['TechnicalService']['contacs_users_id'] 					= $this->request->data['contac_id'];
			$datos['TechnicalService']['clients_natural_id'] 				= 0;
			$datos['TechnicalService']['clients_legal_id'] 					= $this->request->data['TechnicalService']['clients_legal_id'];
		}
		if ($accion == "edit") {
			$datos['TechnicalService']['wpp']								= $this->request->data["TechnicalService"]["wpp"];
			$datos['TechnicalService']['contact_address']					= $this->request->data["TechnicalService"]["contact_address"];
			unset($this->request->data["TechnicalService"]["wpp"]);
			unset($this->request->data["TechnicalService"]["contact_address"]);
		}
		
		unset($this->request->data['TechnicalService']['clients_legal_id']);
    	unset($this->request->data['TechnicalService']['clients_natural_id']);
    	if (isset($this->request->data['TechnicalService']['id'])) {
			unset($this->request->data['TechnicalService']['id']);
		}
		unset($this->request->data['TechnicalService']['numero']);
		
		$i = 1;

		foreach ($this->request->data['TechnicalService'] as $value) {
			$datos['ProductTechnical'][$i] = array();
			$datos['ProductTechnical'][$i]['otros_input'] 			= '';
			$datos['ProductTechnical'][$i]['equipment'] 			= $value['equipment'];
			$datos['ProductTechnical'][$i]['reason'] 				= $value['reason'];
			$datos['ProductTechnical'][$i]['part_number'] 			= $value['part_number'];
			$datos['ProductTechnical'][$i]['serial_number'] 		= $value['serial_number'];
			$datos['ProductTechnical'][$i]['brand'] 				= $value['brand'];
			$datos['ProductTechnical'][$i]['observation'] 			= $value['observations'];
			$datos['ProductTechnical'][$i]['brand'] 				= $value['brand'];
			$datos['ProductTechnical'][$i]['possible_failures'] 	= $value['possible_failures'];
			$datos['ProductTechnical'][$i]['maintenance_status'] 	= $value['maintenance_status'];
			$datos['ProductTechnical'][$i]['serial_garantia'] 		= $value['serial_garantia'];

			if (isset($value['accessories'][0])) {
				$j 					= 0;
				foreach ($value['accessories'] as $valueAcces) {
					if ($valueAcces == 'Otros') {
						$datos['ProductTechnical'][$i]['otros_input'] = $value['otros_input'];
						if ($value['otros_input'] != '') {
							$datos['ProductTechnical'][$i]['otros_input'] = $value['otros_input'];
							$datos['ProductTechnical'][$i]['Accessory'][$j]['accesorio'] 					= $valueAcces;
						} else {
							$j = $j - 1;
						}
					} else {
						$datos['ProductTechnical'][$i]['Accessory'][$j]['accesorio'] 						= $valueAcces;
					}
					if ($accion == 'edit') {
						$datos['ProductTechnical'][$i]['Accessory'][$j]['technical_services_id']	 		= $id;
					}
					$j++;
				}
			} else {
				$datos['ProductTechnical'][$i]['Accessory'] 												= array();
			}
			if (isset($value['img1'])) {
				if ($value['img1']['name'] != '') {
					$image1 	= $this->loadPhoto($value['img1'],'servicioTecnico');
					if ($image1 == 1) {
						$datos['ProductTechnical'][$i]['image1'] 		= $this->Session->read('imagenModelo');
						$datos['TechnicalService']['image1'] 			= $this->Session->read('imagenModelo');
					}
				} else {
					$datos['ProductTechnical'][$i]['image1'] = '';
					$datos['TechnicalService']['image1'] = '';
				}
			} else {
				$imagenEdit1 		= $this->TechnicalService->ProductTechnical->get_img1($id);
				$datos['ProductTechnical'][$i]['image1'] = $imagenEdit1;
			}
			if (isset($value['img2'])) {
				if ($value['img2']['name'] != '') {
					$image2 	= $this->loadPhoto($value['img2'],'servicioTecnico');
					if ($image2 == 1) {
						$datos['ProductTechnical'][$i]['image2'] 		= $this->Session->read('imagenModelo');
						$datos['TechnicalService']['image2'] 			= $this->Session->read('imagenModelo');
					}	
				} else {
					$datos['ProductTechnical'][$i]['image2'] = '';
					$datos['TechnicalService']['image2'] 	 = '';
				}
			} else {
				$imagenEdit2 		= $this->TechnicalService->ProductTechnical->get_img1($id);
				$datos['ProductTechnical'][$i]['image2'] = $imagenEdit2;
			}
			if (isset($value['img3'])) {
				if ($value['img3']['name'] != '') {
					$image3 	= $this->loadPhoto($value['img3'],'servicioTecnico');
					if ($image3 == 1) {
						$datos['ProductTechnical'][$i]['image3'] 		= $this->Session->read('imagenModelo');
						$datos['TechnicalService']['image3'] 			= $this->Session->read('imagenModelo');
					}
				} else {
					$datos['ProductTechnical'][$i]['image3'] = '';
					$datos['TechnicalService']['image3'] 	 = '';
				}
			} else {
				$imagenEdit3 		= $this->TechnicalService->ProductTechnical->get_img1($id);
				$datos['ProductTechnical'][$i]['image3'] 				= $imagenEdit3;
			}
			if (isset($value['img4'])) {
				if ($value['img4']['name'] != '') {
					$image4 	= $this->loadPhoto($value['img4'],'servicioTecnico');
					if ($image4 == 1) {
						$datos['ProductTechnical'][$i]['image4'] 		= $this->Session->read('imagenModelo');
						$datos['TechnicalService']['image4'] 			= $this->Session->read('imagenModelo');
					}
				} else {
					$datos['ProductTechnical'][$i]['image4'] = '';
					$datos['TechnicalService']['image4'] 	 = '';
				}
			} else {
				$imagenEdit4 		= $this->TechnicalService->ProductTechnical->get_img4($id);
				$datos['ProductTechnical'][$i]['image4'] 				= $imagenEdit4;
			}
			if (isset($value['img5'])) {
				if ($value['img5']['name'] != '') {
					$image5 	= $this->loadPhoto($value['img5'],'servicioTecnico');
					if ($image5 == 1) {
						$datos['ProductTechnical'][$i]['image5'] 		= $this->Session->read('imagenModelo');
						$datos['TechnicalService']['image5'] 			= $this->Session->read('imagenModelo');
					}
				} else {
					$datos['ProductTechnical'][$i]['image5'] = '';
					$datos['TechnicalService']['image5'] 	 = '';
				}
			} else {
				$imagenEdit5 		= $this->TechnicalService->ProductTechnical->get_img4($id);
				$datos['ProductTechnical'][$i]['image5'] 				= $imagenEdit5;
			}
			$i++;
		}
		return $datos;
	}

	public function deleteImageService(){
		$this->autoRender   = false;
        if ($this->request->is('ajax')) {
        	$product_tecnico 	= $this->request->data['product_tecnico'];
        	$image_num 			= $this->request->data['image_num'];
        	$image_name 		= $this->request->data['image_name'];
        	$this->deleteImageServer(WWW_ROOT.'img/servicioTecnico/'.$image_name);
        	$datos['ProductTechnical']['id'] = $product_tecnico;
        	switch ($image_num) {
        		case '1':
        			$datos['ProductTechnical']['image1'] = '';
        			break;
        		case '2':
        			$datos['ProductTechnical']['image2'] = '';
        			break;
        		case '3':
        			$datos['ProductTechnical']['image3'] = '';
        			break;
        		case '4':
        			$datos['ProductTechnical']['image4'] = '';
        			break;
        		case '5':
        			$datos['ProductTechnical']['image5'] = '';
        			break;
        	}
        	$this->TechnicalService->ProductTechnical->save($datos);
        }
	}

	public function change_state_finalizado(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			$modelo_id = $this->request->data['modelo_id'];
			$this->set(compact('modelo_id'));
		}
	}

	public function changeStateFinalizadoCotizacionTrue(){
		$this->autoRender 	= false;
		if ($this->request->is('ajax')) {
			$technicalServices 										= $this->TechnicalService->get_data($this->request->data['TechnicalService']['id']);
			$datos['TechnicalService']['prospective_users_id'] 		= $this->saveModels($technicalServices);
			$datos['TechnicalService']['id'] 						= $this->request->data['TechnicalService']['id'];
			$datos['TechnicalService']['report'] 					= $this->request->data['TechnicalService']['report'];
			$datos['TechnicalService']['observation'] 				= $this->request->data['TechnicalService']['observation'];
			$datos['TechnicalService']['cotizacion'] 				= 1;
			$datos['TechnicalService']['state'] 					= 1;
			$datos['TechnicalService']['real_state']				= 1;
			$datos['TechnicalService']['date_end'] 					= date("Y-m-d");

			$datos["TechnicalService"]["fecha_1"] 					= date("Y-m-d",strtotime("+30 day"));
			$datos["TechnicalService"]["fecha_2"] 					= date("Y-m-d",strtotime("+60 day"));
			$datos["TechnicalService"]["fecha_3"] 					= date("Y-m-d",strtotime("+90 day"));
			$datos["TechnicalService"]["fecha_4"] 					= date("Y-m-d",strtotime("+100 day"));
			$datos["TechnicalService"]["alerta_1"]					= 0;

			if ($this->request->data['TechnicalService']['img1']['name'] != '') {
				$image1 	= $this->loadPhoto($this->request->data['TechnicalService']['img1'],'servicioTecnico');
				if ($image1 == 1) {
					$datos['TechnicalService']['image1'] 		= $this->Session->read('imagenModelo');
				}
			} else {
				$datos['TechnicalService']['image1'] = '';
			}
			if ($this->request->data['TechnicalService']['img2']['name'] != '') {
				$image1 	= $this->loadPhoto($this->request->data['TechnicalService']['img2'],'servicioTecnico');
				if ($image1 == 1) {
					$datos['TechnicalService']['image2'] 		= $this->Session->read('imagenModelo');
				}
			} else {
				$datos['TechnicalService']['image2'] = '';
			}
			if ($this->request->data['TechnicalService']['img3']['name'] != '') {
				$image1 	= $this->loadPhoto($this->request->data['TechnicalService']['img3'],'servicioTecnico');
				if ($image1 == 1) {
					$datos['TechnicalService']['image3'] 		= $this->Session->read('imagenModelo');
				}
			} else {
				$datos['TechnicalService']['image3'] = '';
			}
			if ($this->request->data['TechnicalService']['img4']['name'] != '') {
				$image1 	= $this->loadPhoto($this->request->data['TechnicalService']['img4'],'servicioTecnico');
				if ($image1 == 1) {
					$datos['TechnicalService']['image4'] 		= $this->Session->read('imagenModelo');
				}
			} else {
				$datos['TechnicalService']['image4'] = '';
			}
			if ($this->request->data['TechnicalService']['img5']['name'] != '') {
				$image1 	= $this->loadPhoto($this->request->data['TechnicalService']['img5'],'servicioTecnico');
				if ($image1 == 1) {
					$datos['TechnicalService']['image5'] 		= $this->Session->read('imagenModelo');
				}
			} else {
				$datos['TechnicalService']['image5'] = '';
			}
			$this->TechnicalService->save($datos);
			$this->generatePdfTrue($datos['TechnicalService']['id'],$datos['TechnicalService']['report'],$datos['TechnicalService']['observation']);
			$data_servicio 		= $this->TechnicalService->get_data($datos['TechnicalService']['id']);
			$data_client 		= $this->informationClient($technicalServices['TechnicalService']['contacs_users_id'],$technicalServices['TechnicalService']['clients_natural_id']);
			$data_asesor 		= $this->TechnicalService->User->get_data($technicalServices['TechnicalService']['user_id']);
			$numero_equipos 	= $this->TechnicalService->ProductTechnical->count_product_technical($technicalServices['TechnicalService']['id']);
			$this->sendEmailClient($data_client,$data_asesor,$data_servicio,$numero_equipos,'finish',$technicalServices['TechnicalService']['code']);
			$this->Session->setFlash('El flujo se ha creado satisfactoriamente, recuerda avanzar en las etapas', 'Flash/success');
			return $datos['TechnicalService']['prospective_users_id'];
		}
	}

	public function saveModels($datos){
		$datosP['ProspectiveUser']['type'] 						= $datos['TechnicalService']['id'];
		$datosP['ProspectiveUser']['contacs_users_id']  		= $datos['TechnicalService']['contacs_users_id'];
        $datosP['ProspectiveUser']['clients_natural_id']  		= $datos['TechnicalService']['clients_natural_id'];
        $datosP['ProspectiveUser']['origin']  					= 'Servicio técnico';
        $datosP['ProspectiveUser']['user_id']  					= $datos['TechnicalService']['user_id'];
        $datosP['ProspectiveUser']['user_receptor']  			= $datos['TechnicalService']['user_id'];
        $datosP['ProspectiveUser']['state_flow']  				= Configure::read('variables.control_flujo.flujo_contactado');
        $this->TechnicalService->ProspectiveUser->save($datosP);

		$datosF['FlowStage']['contact']							= 'Servicio técnico';
		$datosF['FlowStage']['reason']							= 'Servicio técnico';
		$datosF['FlowStage']['origin']							= 'Servicio técnico';
		$datosF['FlowStage']['description']						= 'Servicio técnico';
		$datosF['FlowStage']['prospective_users_id']			= $this->TechnicalService->ProspectiveUser->id;
		$datosF['FlowStage']['state_flow']						= Configure::read('variables.nombre_flujo.flujo_contactado');
		$datosF['FlowStage']['cotizacion']						= 1;
		$datosF['FlowStage']['state']							= 1;
        $this->TechnicalService->ProspectiveUser->FlowStage->save($datosF);
        return $this->TechnicalService->ProspectiveUser->id;
	}

	public function saveFlujoForService(){
		$this->autoRender 	= false;
		$datos 													= $this->TechnicalService->get_data($this->request->data['service_id']);
		$datosP['ProspectiveUser']['type'] 						= $datos['TechnicalService']['id'];
		$datosP['ProspectiveUser']['contacs_users_id']  		= $datos['TechnicalService']['contacs_users_id'];
        $datosP['ProspectiveUser']['clients_natural_id']  		= $datos['TechnicalService']['clients_natural_id'];
        $datosP['ProspectiveUser']['origin']  					= 'Servicio técnico';
        $datosP['ProspectiveUser']['user_id']  					= $datos['TechnicalService']['user_id'];
        $datosP['ProspectiveUser']['state_flow']  				= Configure::read('variables.control_flujo.flujo_contactado');
        $this->TechnicalService->ProspectiveUser->save($datosP);

        $datosF['FlowStage']['contact']							= 'Servicio técnico';
		$datosF['FlowStage']['reason']							= 'Servicio técnico';
		$datosF['FlowStage']['origin']							= 'Servicio técnico';
		$datosF['FlowStage']['description']						= 'Servicio técnico';
		$datosF['FlowStage']['prospective_users_id']			= $this->TechnicalService->ProspectiveUser->id;
		$datosF['FlowStage']['state_flow']						= Configure::read('variables.nombre_flujo.flujo_contactado');
		$datosF['FlowStage']['cotizacion']						= 1;
		$datosF['FlowStage']['state']							= 1;
        $this->TechnicalService->ProspectiveUser->FlowStage->save($datosF);

		$datosS['TechnicalService']['id'] 						= $this->request->data['service_id'];
		$datosS['TechnicalService']['prospective_users_id'] 	= $this->TechnicalService->ProspectiveUser->id;
		$this->TechnicalService->save($datosS);
	}

	public function changeStateFinalizadoCotizacionFalse(){
		$this->autoRender 	= false;
		if ($this->request->is('ajax')) {
			$technicalServices 									= $this->TechnicalService->get_data($this->request->data['TechnicalService']['id']);
			$datos['TechnicalService']['id'] 					= $this->request->data['TechnicalService']['id'];
			$datos['TechnicalService']['report'] 				= $this->request->data['TechnicalService']['report'];
			$datos['TechnicalService']['observation'] 			= $this->request->data['TechnicalService']['observation'];
			$datos['TechnicalService']['cotizacion'] 			= 0;
			$datos['TechnicalService']['state'] 				= 1;
			$datos['TechnicalService']['real_state'] 			= 2;
			$datos['TechnicalService']['date_end'] 				= date("Y-m-d");

			if ($this->request->data['TechnicalService']['img1']['name'] != '') {
				$image1 	= $this->loadPhoto($this->request->data['TechnicalService']['img1'],'servicioTecnico');
				if ($image1 == 1) {
					$datos['TechnicalService']['image1'] 		= $this->Session->read('imagenModelo');
				}
			} else {
				$datos['TechnicalService']['image1'] = '';
			}
			if ($this->request->data['TechnicalService']['img2']['name'] != '') {
				$image1 	= $this->loadPhoto($this->request->data['TechnicalService']['img2'],'servicioTecnico');
				if ($image1 == 1) {
					$datos['TechnicalService']['image2'] 		= $this->Session->read('imagenModelo');
				}
			} else {
				$datos['TechnicalService']['image2'] = '';
			}
			if ($this->request->data['TechnicalService']['img3']['name'] != '') {
				$image1 	= $this->loadPhoto($this->request->data['TechnicalService']['img3'],'servicioTecnico');
				if ($image1 == 1) {
					$datos['TechnicalService']['image3'] 		= $this->Session->read('imagenModelo');
				}
			} else {
				$datos['TechnicalService']['image3'] = '';
			}
			if ($this->request->data['TechnicalService']['img4']['name'] != '') {
				$image1 	= $this->loadPhoto($this->request->data['TechnicalService']['img4'],'servicioTecnico');
				if ($image1 == 1) {
					$datos['TechnicalService']['image4'] 		= $this->Session->read('imagenModelo');
				}
			} else {
				$datos['TechnicalService']['image4'] = '';
			}
			if ($this->request->data['TechnicalService']['img5']['name'] != '') {
				$image1 	= $this->loadPhoto($this->request->data['TechnicalService']['img5'],'servicioTecnico');
				if ($image1 == 1) {
					$datos['TechnicalService']['image5'] 		= $this->Session->read('imagenModelo');
				}
			} else {
				$datos['TechnicalService']['image5'] = '';
			}
			$this->TechnicalService->save($datos);
			$this->generatePdfTrue($datos['TechnicalService']['id'],$datos['TechnicalService']['report'],$datos['TechnicalService']['observation']);
			$data_servicio 		= $this->TechnicalService->get_data($datos['TechnicalService']['id']);
			$data_client 		= $this->informationClient($technicalServices['TechnicalService']['contacs_users_id'],$technicalServices['TechnicalService']['clients_natural_id']);
			$data_asesor 		= $this->TechnicalService->User->get_data($technicalServices['TechnicalService']['user_id']);
			$numero_equipos 	= $this->TechnicalService->ProductTechnical->count_product_technical($technicalServices['TechnicalService']['id']);
			$this->sendEmailClient($data_client,$data_asesor,$data_servicio,$numero_equipos,'finish',$technicalServices['TechnicalService']['code'], false);
			return true;
		}
	}

	public function generatePdfTrue($id,$report,$observation){
		$technicalServices 				= $this->TechnicalService->get_data($id);
		$nombreArchivo 					= $technicalServices['TechnicalService']['code'].'.pdf';
		$productClient 					= $this->TechnicalService->ProductTechnical->get_all($id);
		$datosUsuario					= $this->TechnicalService->User->get_data($technicalServices['TechnicalService']['user_id']);
		$options 						= array(
			'template'	=> 'report_service',
			'ruta'		=> APP . 'webroot/files/reports_service/'.$nombreArchivo,
			'vars'		=> array('productClient' => $productClient,'technicalServices' => $technicalServices,'report' => $report,'observation' => $observation,'datosUsuario' => $datosUsuario),
		);
		$this->generatePdf($options);
	}

	public function sendEmailClient($cliente_information,$asesor_information,$servicio_information,$numero_equipos,$action,$code, $cotizacion = true){
		$email_defecto 		= Configure::read('variables.emails_defecto');
		$emailCliente 		= array();
		$emailCliente[0] 	= $cliente_information['email'];
		$emailCliente 		= array_merge($email_defecto, $emailCliente);
		if ($action == 'finish') {
			$nombreArchivo 				= $code.'.pdf';
			$rutaURL 					= 'TechnicalServices/report/'.$servicio_information['TechnicalService']['id'];
			$observationServicio 		= $servicio_information['TechnicalService']['observation'];
			$reportServicio 			= $servicio_information['TechnicalService']['report'];
			if (file_exists(WWW_ROOT.'/files/reports_service/'.$nombreArchivo)) {
				$options = array(
					'to'		=> $emailCliente,
					'template'	=> 'finish_service_technical',
					'subject'	=> 'La orden ST00'.$servicio_information["TechnicalService"]["id"].' de Servicio Técnico ha sido finalizada en KEBCO AlmacenDelPintor.com',
					'vars'		=> array('codigo' => $servicio_information['TechnicalService']['code'],'observation' => $observationServicio,'report' => $reportServicio,'ruta' => $rutaURL,'numero_equipos' => $numero_equipos,'nameClient' => $cliente_information['name'],'nameAsesor' => $asesor_information['User']['name'], "cotizacion" => $cotizacion),
					// 'file'		=> 'files/reports_service/'.$nombreArchivo
				);
			} else {
				$options = array(
					'to'		=> $emailCliente,
					'template'	=> 'finish_service_technical',
					'subject'	=> 'La orden ST00'.$servicio_information["TechnicalService"]["id"].' de Servicio Técnico ha sido finalizada en KEBCO AlmacenDelPintor.com',
					'vars'		=> array('codigo' => $servicio_information['TechnicalService']['code'],'observation' => $observationServicio,'report' => $reportServicio,'ruta' => $rutaURL,'numero_equipos' => $numero_equipos,'nameClient' => $cliente_information['name'],'nameAsesor' => $asesor_information['User']['name'], "cotizacion" => $cotizacion)
				);
			}
		} else {
			$rutaURL 			= 'TechnicalServices/view/'.$servicio_information['TechnicalService']['id'];
			$options = array(
				'to'		=> $emailCliente,
				'template'	=> 'entry_service_technical',
				'subject'	=> 'Hemos creado la orden de Servicio Técnico ST00'.$servicio_information["TechnicalService"]["id"].' en KEBCO AlmacenDelPintor.com',
				'vars'		=> array('codigo' => $servicio_information['TechnicalService']['code'],'ruta' => $rutaURL,'numero_equipos' => $numero_equipos,'nameClient' => $cliente_information['name'],'nameAsesor' => $asesor_information['User']['name']),
			);
		}

		$this->sendMail($options);

		if ($action != 'finish') {

			$phones = array();
			$cliente_information["telephone"] = str_replace([" ","+57","_"], "", $cliente_information["telephone"]);
			$cliente_information["cell_phone"] = str_replace([" ","+57","_"], "", $cliente_information["cell_phone"]);

			if(!empty($cliente_information["telephone"]) && strlen($cliente_information["telephone"]) == 10){
				$phones[] = $cliente_information["telephone"];
			}

			if(!empty($cliente_information["cell_phone"]) && strlen($cliente_information["cell_phone"]) == 10){
				$phones[] = $cliente_information["cell_phone"];
			}

			if (count($phones) == 2 && $phones[0] == $phones[1] ) {
				unset($phones[1]);
			}

			if(!empty($phones)){

				foreach ($phones as $key => $phone) {
					$strMsg = '
						{
						   "messaging_product": "whatsapp",
						   "to": "57'.$phone.'",
						   "type": "template",
						   "template": {
						       "name": "servicio_tecnico_diagnostico",
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
						                       "text": "'.$servicio_information["TechnicalService"]["id"].'"
						                   }
						               ]
						           },
						           {
						               "type": "body",
						               "parameters": [
						                   {
						                       "type": "text",
						                       "text": "'.$cliente_information['name'].'"
						                   },
						                   {
						                       "type": "text",
						                       "text": "'.$asesor_information['User']['name'].'"
						                   },
						                   {
						                       "type": "text",
						                       "text": "'.$servicio_information['TechnicalService']['code'].'"
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
						                       "text": "'.$servicio_information['TechnicalService']['id'].'"
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

						$responseToken = $HttpSocket->post('https://graph.facebook.com/v16.0/100586116213138/messages',$strMsg,$request);
						$responseToken = json_decode($responseToken->body);

					} catch (Exception $e) {
						$this->log($e->getMessage());
					}	
				}

				

			}
		}

	}

	public function informationClient($contac_id,$client_natural_id){
		if ($contac_id > 0) {
			$datos = $this->TechnicalService->ContacsUser->get_data($contac_id);
			$datosC['name'] 		= $datos['ContacsUser']['name'];
			$datosC['email'] 		= $datos['ContacsUser']['email'];
			$datosC['telephone'] 		= $datos['ContacsUser']['telephone'];
			$datosC['cell_phone'] 		= $datos['ContacsUser']['cell_phone'];
		} else {
			$datos = $this->TechnicalService->ClientsNatural->get_data($client_natural_id);
			$datosC['name'] 		= $datos['ClientsNatural']['name'];
			$datosC['email'] 		= $datos['ClientsNatural']['email'];
			$datosC['telephone'] 		= $datos['ClientsNatural']['telephone'];
			$datosC['cell_phone'] 		= $datos['ClientsNatural']['cell_phone'];
		}
		return $datosC;
	}

	public function updateuserAsignado(){
		$this->autoRender   = false;
        if ($this->request->is('ajax')) {
			$service_id 									= $this->request->data['service_id'];
			$user_id 										= $this->request->data['asesor'];
			$datosP['TechnicalService']['id'] 				= $service_id;
			$datosP['TechnicalService']['user_id'] 			= $user_id;
			$datosUserSession 								= $this->TechnicalService->User->get_data(AuthComponent::user('id'));
			$datosUserAsesor 								= $this->TechnicalService->User->get_data($user_id);
			if ($this->TechnicalService->save($datosP)) {
				$this->saveDataLogsUser(12,'TechnicalServices',$service_id);
				$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.asesor_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosUserAsesor['User']['id'],$service_id,AuthComponent::user('id'),$this->webroot.'TechnicalServices');
			}
    	}
	}


}
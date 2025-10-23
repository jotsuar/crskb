<?php
App::uses('AppModel', 'Model');

class FlowStage extends AppModel {

	public $belongsTo = array(
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_users_id'
		),
		'Quotation' => array(
			'className' => 'Quotation',
			'foreignKey' => 'document' // O el usuario adjunta un archivo
		)
	);

	public $hasMany = array(
		'Quotation' => array(
			'className' => 'Quotation',
			'foreignKey' => 'flow_stage_id',
			'dependent' => false
		),
		'FlowStagesProduct' => array(
			'className' => 'FlowStagesProduct',
			'foreignKey' => 'flow_stage_id',
			'dependent' => false
		)
	);

	public function get_data($id){
		$this->recursive 		= 0;
		$conditions 			= array('FlowStage.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function find_type_pay_flujo($flujo_id){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.type_pay');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
		$datos 					= $this->find('first',compact('conditions','fields','order'));
		return $datos['FlowStage']['type_pay'];
	}

	public function copias_email_despachado($flujo_id){
		$this->recursive 		= -1;
		$fields 				= array('FlowStage.copias_email');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.datos_despacho'));
		$datos 					= $this->find('first',compact('conditions','fields'));
		return $datos['FlowStage']['copias_email'];
	}

	public function id_flow_bussines_contactado($flujo_id){
		$this->recursive 		= -1;
		$fields 				= array('FlowStage.id');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_contactado'));
		$order					= array('FlowStage.id' => 'desc');
		$datos					= $this->find('first',compact('conditions','fields','order'));
		return $datos['FlowStage']['id'];
	}

	public function id_flow_bussines_latses_pagado($flujo_id){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.id');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
		$datos					= $this->find('first',compact('conditions','fields','order'));
		return $datos['FlowStage']['id'];
	}

	public function find_type_payment_flujo($flujo_id){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.type_pay');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
		$datos					= $this->find('first',compact('conditions','fields','order'));
		return $datos['FlowStage']['type_pay'];
	}

	public function find_type_pay_flowstage($FlowStage_id){
		$this->recursive 		= -1;
		$fields 				= array('FlowStage.type_pay');
		$conditions 			= array('FlowStage.id' => $FlowStage_id);
		$datos					= $this->find('first',compact('conditions','fields'));
		return $datos['FlowStage']['type_pay'];
	}

	public function data_flow_bussines_latses_pagado($flujo_id){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
		return $this->find('first',compact('conditions','order'));
	}

	public function id_latest_regystri_state_pagado($flujo_id, $verified = false){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.id');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));

		if ($verified) {
			$conditions["FlowStage.payment_verification"] = 1;
		}

		$datos					= $this->find('first',compact('conditions','fields','order'));
		return empty($datos['FlowStage']['id']) ? null : $datos['FlowStage']['id'];
	}

	public function get_all_flows_payment_verified($dateIni,$dateEnd){
		$ids 					= array();
		$recursive 				= -1;
		$fields 				= array("ProspectiveUser.id","ProspectiveUser.bill_date");
		$conditions				= array("DATE(ProspectiveUser.bill_date) >=" => $dateIni, "DATE(ProspectiveUser.bill_date) <= " => $dateEnd  );

		$db = $this->getDataSource();
		$subQuery = $db->buildStatement(
		    array(
		        'fields'     => array('document'),
		        'table'      => $db->fullTableName($this),
		        'alias'      => 'FlowStage',
		        'limit'      => 1,
		        'offset'     => null,
		        'joins'      => array(),
		        'conditions' => ["FlowStage.prospective_users_id = ProspectiveUser.id", "FlowStage.state_flow" => "Cotizado" ],
		        'order'      => ["FlowStage.id"=>"DESC"],
		        'group'      => null
		    ),
		    $this
		);

		$fields[] = '('.$subQuery.') as documento';
		$data 					= $this->ProspectiveUser->find("all",compact("fields","conditions","recursive"));

		if(!empty($data)){
			$ids = $data;
		}

		return $this->get_products_by_payments($ids,$dateIni,$dateEnd);
	}

	public function get_products_by_payments($ids,$dateIni,$dateEnd){
		$products = array();

		$actualProds = $this->Quotation->QuotationsProduct->Product->find("list",["fields" => ["id","id"], "conditions" => 
			[
				"OR" => [ ["Product.category_id"=>[1257,631,1369]], ["Product.reorder >" => 0, "Product.min_stock" => 0,]  ], 
				 
			] 
		] );


		foreach ($ids as $keyId => $idPayment) {
			$produtosCotizacion = $this->Quotation->QuotationsProduct->get_data_quotation_for_importer_2($idPayment[0]["documento"],$actualProds);
			if(!empty($produtosCotizacion)){
				foreach ($produtosCotizacion as $key => $productoInfo) {
					$conditions = array(
						"BillProduct.product_id" => $productoInfo,
						"BillProduct.quantity" 	 => $key,
						"BillProduct.bill_date"  => $idPayment["ProspectiveUser"]["bill_date"],
						"BillProduct.prospective_user_id" => $idPayment["ProspectiveUser"]["id"],
					);
					$countProduct = $this->ProspectiveUser->BillProduct->find("count",compact("conditions"));
					if($countProduct == 0){
						$dataSave = array();
						foreach ($conditions as $keyData => $value) {
							$dataSave[str_replace("BillProduct.", "", $keyData)] = $value;
						}
						$this->ProspectiveUser->BillProduct->create();
						$this->ProspectiveUser->BillProduct->save($dataSave);
					}
				}
			}
		}
		return $this->organice_info($this->ProspectiveUser->BillProduct->find("all",["conditions" => ["BillProduct.state" => 1, "BillProduct.bill_date >=" => $dateIni, "BillProduct.bill_date <=" => $dateEnd  ] ]));

	}

	private function organice_info($productosInfo){
		$newData     = array();
		foreach ($productosInfo as $key => $value) {

			if(isset($newData[ $value["Product"]["brand_id"] ][ $value["Product"]["id"] ])){
				$newData[ $value["Product"]["brand_id"] ][ $value["Product"]["id"] ]["QuantityFinal"] += $value["BillProduct"]["quantity"];
			}else{
				$newData[$value["Product"]["brand_id"]][$value["Product"]["id"]] = array(
					"QuantityFinal"=> intval($value["BillProduct"]["quantity"]),
					"name" 	       => $value["Product"]["name"],
					"comment" 	   => $value["Product"]["notes"],
					"part" 	   	   => $value["Product"]["part_number"],
					"brand_id" 	   => $value["Product"]["brand_id"],
					"img" 	   	   => $value["Product"]["img"],
				);
			}	
		}
		return $newData;
	}

	public function id_latest_regystri_state_cotizado($flujo_id, $getDocument = false){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.id','FlowStage.document');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'));
		$datos					= $this->find('first',compact('conditions','fields','order'));
		if($getDocument){
			return empty($datos) ? null :  $datos["FlowStage"]["document"];
		}
		return empty($datos) ? null :  $datos['FlowStage']['id'];
	}

	public function codigoQuotation_latest_regystri_state_cotizado($flujo_id){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.codigoQuotation');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'));
		$datos					= $this->find('first',compact('conditions','fields','order'));
		return $datos['FlowStage']['codigoQuotation'];
	}

	public function valor_latest_regystri_state_cotizado_flujo_id($flujo_id){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.priceQuotation');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'));
		$datos					= $this->find('first',compact('conditions','fields','order'));
		return isset($datos['FlowStage']['priceQuotation']) ? $datos['FlowStage']['priceQuotation'] : 0;
	}

	public function id_name_document_latest_regystri_state_cotizado($flujo_id){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.nameDocument','FlowStage.document');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'));
		$datos					= $this->find('first',compact('conditions','fields','order'));
		return $datos;
	}

	public function exist_state_cotizado_prospective($flujo_id){
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'));
		$datos					= $this->find('count',compact('conditions','fields','order'));
		return $datos;
	}

	public function id_latest_regystri($flujo_id, $estado = null){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.id');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id);

		if (!is_null($estado)) {
			$texto = $this->finde_state_flujo($estado);
			$conditions["FlowStage.state_flow"] = $texto;
		}

		$datos					= $this->find('first',compact('conditions','fields','order'));
		return !empty($datos) ? $datos['FlowStage']['id'] : "";
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

	public function get_data_bussines($flujo_id){
		$this->recursive 		= -1;
		$order 					= ["id" => "DESC"];
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id);
		return $this->find('all',compact('conditions','order'));
	}

	public function count_data_bussines($flujo_id){
		$fields 				= 'DISTINCT FlowStage.state_flow';
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id);
		return $this->find('count',compact('conditions','fields'));
	}

	public function find_reason_prospective($flujo_id){
		$this->recursive 		= -1;
		$fields 				= array('FlowStage.reason');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'));
		$datos 					= $this->find('first',compact('conditions','fields'));
		if (isset($datos['FlowStage']['reason'])) {
			$razon 				= $datos['FlowStage']['reason'];
		} else {
			$razon 				= $this->find_reason_prospective_no_valido($flujo_id);
		}
		return $razon;
	}

	public function find_reason_prospective_no_valido($flujo_id){
		$this->recursive 		= -1;
		$fields 				= array('FlowStage.reason');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_no_valido'));
		$datos 					= $this->find('first',compact('conditions','fields'));
		if (isset($datos['FlowStage']['reason'])) {
			$razon = $datos['FlowStage']['reason'];
		} else {
			$razon = '';
		}
		return $razon;
	}

	public function find_reason_buscador_flujo($texto){
		$this->recursive 	= -1;
		$fields 			= array('FlowStage.prospective_users_id');
		$conditions			= array('LOWER(FlowStage.reason) LIKE' 	=> '%'.$texto.'%');
		$datos 				= $this->find('all',compact('conditions','fields'));
		$resultFinal		= array();
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.FlowStage');
			foreach ($result as $value) {
				$resultFinal[] = $value['prospective_users_id'];
			}
		}
		return $resultFinal;
	}

	public function find_data_prospective_stateFlow_asignado($flujo_id){
		$this->recursive 		= -1;
		$fields 				= array('FlowStage.*');
		$conditions 			= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'));
		$datos 					= $this->find('first',compact('conditions','fields'));
		return $datos;
	}

	public function payment_verification_sales_day_user(){
		$fields 				= array('FlowStage.prospective_users_id');
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.date_verification' => date("Y-m-d"),'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'ProspectiveUser.user_id' => AuthComponent::user('id'));
		$datos 					= $this->find('all',compact('conditions','fields'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.FlowStage');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['prospective_users_id']] = $value['prospective_users_id'];
			}
		} else {
			$resultFinal 		= 0;
		}
		return $resultFinal;
	}

	public function payment_verification_sales_day_business(){
		$this->recursive 		= -1;
		$fields 				= array('FlowStage.prospective_users_id');
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.date_verification' => date("Y-m-d"),'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
		$datos 					= $this->find('all',compact('conditions','fields'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.FlowStage');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['prospective_users_id']] = $value['prospective_users_id'];
			}
		} else {
			$resultFinal 		= 0;
		}
		return $resultFinal;
	}

	public function payment_verification_sales_product($ini = null, $end = null){
		$this->recursive 		= -1;
		$fields 				= array('FlowStage.prospective_users_id');
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));

		if (!is_null($ini) || !is_null($end) ) {
			$conditions["DATE(FlowStage.created) >="] = $ini;
			$conditions["DATE(FlowStage.created) <="] = $end;
		}

		$datos 					= $this->find('all',compact('conditions','fields'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.FlowStage');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['prospective_users_id']] = $value['prospective_users_id'];
			}
		} else {
			$resultFinal 		= 0;
		}
		return $resultFinal;
	}

	public function payment_verification_sales_yesterday_business(){
		$this->recursive 		= -1;
		$validate 				= true;
		$horas24				= 3600;
		$datos 					= 0;
		$resultFinal1 			= 0;
		$resultFinal 			= 0;
		$dia 					= 1;
		while ($validate) {
			$fechaAyer 				= date('Y-m-d', strtotime('-'.$dia.' day'));
			$fields 				= array('FlowStage.prospective_users_id');
			$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.date_verification' => $fechaAyer,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
			$datos 					= $this->find('all',compact('conditions','fields'));
			if (count($datos) > 0) {
				$validate 		= false;
				$resultFinal1 	= 1;
				$dia 			= 6;
			} else {
				if ($dia >= 6) {
					$validate 		= false;
				}
				$dia 			= $dia + 1;
			}
		}
		if ($resultFinal1 == 1) {
			$result 			= Set::extract($datos, '{n}.FlowStage');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['prospective_users_id']] = $value['prospective_users_id'];
			}
		}
		return $resultFinal;
	}

	public function payment_verification_sales_yesterday_business_date(){
		$this->recursive 		= -1;
		$validate 				= true;
		$dia 					= 1;
		while ($validate) {
			$fechaAyer 				= date('Y-m-d', strtotime('-'.$dia.' day'));
			$order					= array('FlowStage.id' => 'desc');
			$fields 				= array('FlowStage.date_verification');
			$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.date_verification' => $fechaAyer,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
			$datos 					= $this->find('first',compact('conditions','fields','order'));
			if (count($datos) > 0) {
				$validate 		= false;
				$dia 			= 6;
			} else {
				if ($dia >= 6) {
					$validate 		= false;
					$fechaAyer 		= '0000-00-00'; 
				}
				$dia 			= $dia + 1;
			}
		}
		return $fechaAyer;
	}

	public function payment_verification_sales($fecha_inicio,$fecha_fin){
		$this->recursive 		= 0;
		$fields 				= array('FlowStage.prospective_users_id');
		$conditions 			= array('FlowStage.payment_verification' => 1,
										'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),
										'FlowStage.date_verification >=' => $fecha_inicio,
										'FlowStage.date_verification <=' => $fecha_fin);
		$datos 					= $this->find('all',compact('conditions','fields'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.FlowStage');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['prospective_users_id']] = $value['prospective_users_id'];
			}
		} else {
			$resultFinal 		= 0;
		}
		return $resultFinal;
	}

	public function payment_verification_sales_month_user($fecha_inicio = null, $fecha_fin = null, $user_id = null){
		$this->recursive 		= 0;
		if(is_null($fecha_inicio) && is_null($fecha_fin)){
			$anoActual 				= date("Y");
			$mesActual 				= date("m");
			$fechaInicioBetween 	= $anoActual."-".$mesActual."-01";
			$fechaFinBetween 		= $anoActual."-".$mesActual."-31";
		}else{
			$fechaInicioBetween		= $fecha_inicio;
			$fechaFinBetween		= $fecha_fin;
		}

		$user = is_null($user_id) ? AuthComponent::user('id') : $user_id;
		
		$fields 				= array('FlowStage.prospective_users_id');
		$conditions 			= array('FlowStage.payment_verification' => 1,
										'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),
										'ProspectiveUser.user_id' => $user,
										'FlowStage.date_verification >=' => $fechaInicioBetween,
										'FlowStage.date_verification <=' => $fechaFinBetween);
		$datos 					= $this->find('all',compact('conditions','fields'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.FlowStage');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['prospective_users_id']] = $value['prospective_users_id'];
			}
		} else {
			$resultFinal 		= 0;
		}
		return $resultFinal;
	}

	public function consult_information_user_flujos_ventas($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.payment_verification' => 1,
									'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),
									'FlowStage.date_verification >=' => $fecha_inicio,
									'FlowStage.date_verification <=' => $fecha_fin,
									'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$filasArray 		= count($datos);
		$contadorFinal = 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function payment_verification_sales_month_bussines(){
		$this->recursive 		= -1;
		$anoActual 				= date("Y");
		$mesActual 				= date("m");
		$fechaInicioBetween 	= $anoActual."-".$mesActual."-01";
		$fechaFinBetween 		= $anoActual."-".$mesActual."-31";
		$fields 				= array('FlowStage.prospective_users_id');
		$conditions 			= array('FlowStage.payment_verification' => 1,
										'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),
										'FlowStage.date_verification >=' => $fechaInicioBetween,
										'FlowStage.date_verification <=' => $fechaFinBetween);
		$datos 					= $this->find('all',compact('conditions','fields'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.FlowStage');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['prospective_users_id']] = $value['prospective_users_id'];
			}
		} else {
			$resultFinal 		= 0;
		}
		return $resultFinal;
	}
	
	public function calculate_total_sales($pagosVerificadosFlujos){
		if (count($pagosVerificadosFlujos) > 0 && isset($pagosVerificadosFlujos[0])) {
			$fields 			= array('FlowStage.prospective_users_id','FlowStage.valor as priceQuotation', 'ProspectiveUser.bill_value');
			$conditions 		= array('FlowStage.prospective_users_id' => $pagosVerificadosFlujos,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.payment_verification' => 1);
			$datos 				= $this->find('all',compact('conditions','fields'));
			$result['FlowStage']['priceQuotation'] 	= isset($datos["ProspectiveUser"]) ?  $datos["ProspectiveUser"]["bill_value"] : 0;
			// $result 			= Set::extract($datos, '{n}.FlowStage');
		} else {
			$result['FlowStage']['priceQuotation'] = 0;
		}
		return $result;
	}

	public function count_cotizaciones_enviadas_clients_natural($client_id){
		$conditions = array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'ProspectiveUser.clients_natural_id' => $client_id);
		return $this->find('count',compact('conditions'));
	}

	public function cotizaciones_enviadas_clients_natural($client_id){
		$order			= array('FlowStage.id' => 'desc');
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id');
		$conditions 	= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'ProspectiveUser.clients_natural_id' => $client_id);
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function cotizaciones_enviadas(){
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.contacs_users_id','ProspectiveUser.clients_natural_id');
		$conditions 	= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'));
		return $this->find('all',compact('conditions','fields'));
	}

	public function count_cotizaciones_enviadas(){
		$conditions 			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'));
		return $this->find('count',compact('conditions'));
	}

	public function count_cotizaciones_enviadas_clients_juridico($contacs_ids){
		$conditions 	= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'ProspectiveUser.contacs_users_id' => $contacs_ids);
		return $this->find('count',compact('conditions'));
	}

	public function count_cotizaciones_enviadas_client_codigo($texto){
		$conditions 	= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'FlowStage.codigoQuotation' => $texto);
		return $this->find('count',compact('conditions'));
	}

	public function cotizaciones_enviadas_clients_juridico($contacs_ids){
		$order			= array('FlowStage.id' => 'desc');
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id');
		$conditions 	= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'ProspectiveUser.contacs_users_id' => $contacs_ids, 'ProspectiveUser.created >=' => '2022-01-01' );
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function find_id_flowStage_state_infoDespacho($flujo_id){
		$this->recursive 	= -1;
		$order				= array('FlowStage.id' => 'desc');
		$fields 			= array('FlowStage.id');
		$conditions 		= array('FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.datos_despacho'));
		$datos 				= $this->find('first',compact('conditions','fields','order'));
		return $datos['FlowStage']['id'];
	}

	public function count_flujos_true_clients_natural($client_id){
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'ProspectiveUser.clients_natural_id' => $client_id);
		return $this->find('count',compact('conditions'));
	}

	public function count_flujos_true_clients_juridico($contacs_ids){
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'ProspectiveUser.contacs_users_id' => $contacs_ids);
		return $this->find('count',compact('conditions'));
	}

	public function flujos_true(){
		$fields 				= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.contacs_users_id','ProspectiveUser.clients_natural_id');
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
		return $this->find('all',compact('conditions','fields'));
	}

	public function flujos_true_clients_natural($client_id){
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.*','ProspectiveUser.user_id');
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'ProspectiveUser.clients_natural_id' => $client_id);
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function flujos_true_clients_juridico($contacs_ids){
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.*','ProspectiveUser.user_id');
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'ProspectiveUser.contacs_users_id' => $contacs_ids, 'ProspectiveUser.created >=' => '2022-01-01' );
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function payments_total_clients_natural($client_id){
		$fields 				= array('FlowStage.prospective_users_id');
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'ProspectiveUser.clients_natural_id' => $client_id);
		$datos 					= $this->find('all',compact('conditions','fields'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.FlowStage');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['prospective_users_id']] = $value['prospective_users_id'];
			}
		} else {
			$resultFinal 		= 0;
		}
		return $resultFinal;
	}

	public function payments_total_clients_juridico($contacs_ids){
		$fields 				= array('FlowStage.prospective_users_id', 'FlowStage.prospective_users_id');
		$joins					= [ ['table' => 'prospective_users','alias' => 'ProspectiveUser','type' => 'INNER','conditions' => array('ProspectiveUser.id = FlowStage.prospective_users_id')] ];
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'ProspectiveUser.contacs_users_id' => $contacs_ids);
		return $this->find('list',compact('conditions','fields','joins'));
	}

	public function flujos_verify($flujo_id = null){
		$order			= array('FlowStage.id' => 'desc');
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type','ProspectiveUser.contacs_users_id',"ProspectiveUser.discount_datafono");
		$conditions 	= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),"ProspectiveUser.state != " => 6);
		if (!is_null($flujo_id)) {
			$id_etapa_pagado = $this->id_latest_regystri_state_pagado($flujo_id,true);

			if(is_null($id_etapa_pagado)){
				return [ $flujo_id ];
			}

			$this->recursive = 1;
			$conditions["FlowStage.payment_verification"] = [0,2];
			$conditions["FlowStage.prospective_users_id"] = $flujo_id;
			$conditions["FlowStage.id"] = $id_etapa_pagado;
		}else{
			$conditions["FlowStage.payment !="] = 'Crédito';
			$conditions["FlowStage.payment_verification"] = 0;
		}
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function flujos_verify_tienda(){
		$order			= array('FlowStage.id' => 'desc');
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type','ProspectiveUser.contacs_users_id');
		$conditions 	= array('FlowStage.payment_verification' => 0,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.payment !=' => 'Crédito',"ProspectiveUser.state" => 6 );
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function count_flujos_verify(){
		$conditions 	= array('FlowStage.payment_verification' => 0,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.payment !=' => 'Crédito');
		return $this->find('count',compact('conditions'));
	}

	public function flujos_verify_payment_credito(){
		$dateSixMonth   = date("Y-m-d",strtotime("-8 month"));
		$order			= array('FlowStage.id' => 'desc');
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type','ProspectiveUser.contacs_users_id');
		$conditions 	= array('FlowStage.payment_verification' => 0,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.payment' => 'Crédito'
			,'DATE(ProspectiveUser.created) >='=>$dateSixMonth
		);
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function flujos_verify_payment_credito_flujo($flujo_id){
		$recursive 	    = 1;
		$order			= array('FlowStage.id' => 'desc');
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type','ProspectiveUser.contacs_users_id');
		$conditions 	= array('FlowStage.payment_verification' => 0,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.payment' => 'Crédito','ProspectiveUser.id'=>$flujo_id);
		return $this->find('first',compact('conditions','fields','order','recursive'));
	}

	public function flujos_verify_payment_credito_days(){
		$order			= array('FlowStage.id' => 'desc');
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type','ProspectiveUser.contacs_users_id');
		$conditions 	= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.payment' => 'Crédito', 'DATE(FlowStage.created) >='=>"2022-01-01" );
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function count_flujos_verify_payment_credito(){
		$conditions 	= array('FlowStage.payment_verification' => 0,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.payment' => 'Crédito');
		return $this->find('count',compact('conditions'));
	}

	public function count_flujos_verify_abonos(){
		$conditions 	= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.state' => 5);
		return $this->find('count',compact('conditions'));
	}

	public function flujos_verify_abonos(){
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type','ProspectiveUser.contacs_users_id');
		$conditions 	= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.state' => 5);
		return $this->find('all',compact('conditions','fields'));
	}

	public function pending_dispatches($new = null){
		$order			= array('FlowStage.id' => 'DESC');
		$fields 		= array('FlowStage.*','ProspectiveUser.*');
		$conditions 	= array('FlowStage.state' => 2,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.datos_despacho'),"ProspectiveUser.state_flow !=" => 8,"flete !="=>'Tienda');

		// if (!is_null($new)) {
		// 	App::import("model","Order");
		// 	$Order = new Order();
		// 	$prospectos = $this->ProspectiveUser->find("list",["conditions" => ["ProspectiveUser.id" => $Order->find("list",["fields" => ["prospective_user_id","prospective_user_id"],"conditions" => ["Order.state" => 1]  ]) ], "fields" => ["id","id"]  ]);

		// 	$conditions["FlowStage.prospective_users_id"] = empty($prospectos) ? 0 : $prospectos;
		// }

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}

		return $this->find('all',compact('conditions','fields','order'));
	}

	public function count_pending_dispatches(){
		$conditions 	= array('FlowStage.state' => 2,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.datos_despacho'));
		return $this->find('count',compact('conditions'));
	}

	public function information_dispatches_order($new = null){
		$conditions 			= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_pagado'),'FlowStage.payment_verification' => 1,'ProspectiveUser.state' => 1,'FlowStage.type_pay !=' => 2,'FlowStage.state' => 3);

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}

		if (!is_null($new)) {
			App::import("model","Order");
			$Order = new Order();
			$prospectos = $this->ProspectiveUser->find("list",["conditions" => ["ProspectiveUser.id" => $Order->find("list",["fields" => ["prospective_user_id","prospective_user_id"],"conditions" => ["Order.state" => 1]  ]) ], "fields" => ["id","id"]  ]);

			$conditions["FlowStage.prospective_users_id"] = empty($prospectos) ? 0 : $prospectos;
		}

		return $this->find('all',compact('conditions'));
	}

	public function find_state_pagado_true(){
		$order			= array('FlowStage.id' => 'desc');
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type','ProspectiveUser.contacs_users_id');
		$conditions 	= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function count_state_pagado_true(){
		$conditions 	= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
		return $this->find('count',compact('conditions'));
	}

	public function find_state_pagado_false(){
		$dateSixMonth   = date("Y-m-d",strtotime("-6 month"));
		$order			= array('FlowStage.id' => 'desc');
		$fields 		= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type','ProspectiveUser.contacs_users_id');
		$conditions 	= array('FlowStage.payment_verification' => 2,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'), 'DATE(ProspectiveUser.created) >='=>$dateSixMonth);
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function count_state_pagado_false(){
		$conditions 	= array('FlowStage.payment_verification' => 2,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
		return $this->find('count',compact('conditions'));
	}

	public function find_valor_flujo_pagado($flujo_id){
		$this->recursive 		= -1;
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.valor');
		$conditions 			= array('FlowStage.payment_verification' => 1,'FlowStage.prospective_users_id' => $flujo_id,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
		$datos 					= $this->find('first',compact('conditions','order','fields'));
		return $datos['FlowStage']['valor'];
	}

	public function find_state_despachado_total_products($flow_id){
		$fields 				= array('FlowStage.products_send');
		$conditions 			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_despachado'),'FlowStage.prospective_users_id' => $flow_id);
		$datos 					= $this->find('all',compact('conditions','fields'));
		$countProductosEnviados = 0;
		foreach ($datos as $value) {
			$countProductosEnviados += $value['FlowStage']['products_send'];
		}
		return $countProductosEnviados;
	}

	public function find_state_despachado($new = null){
		$order					= array('FlowStage.id' => 'desc');
		$fields 				= array('FlowStage.*','ProspectiveUser.*');
		$conditions 			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_despachado'),'ProspectiveUser.state_flow !=' => Configure::read('variables.control_flujo.flujo_finalizado'),'ProspectiveUser.state' => 1);

		if (!is_null($new)) {
			App::import("model","Shipping");
			$Shipping = new Shipping();
			$prospectos = $Shipping->find("list",["conditions" => ["Shipping.state" => 2, "Shipping.flow_id !=" => null  ] , "fields" => ["flow_id","flow_id"]  ]);

			$conditions["FlowStage.id"] = empty($prospectos) ? 0 : $prospectos;
		}

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}

		return $this->find('all',compact('conditions','fields','order'));
	}

	public function find_state_despachado_finish(){
		$order					= array('FlowStage.id' => 'desc');
		$conditions 			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_despachado'),'ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_finalizado'));
		return $this->find('all',compact('conditions','order'));
	}

	public function count_state_despachado(){
		$conditions 			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_despachado'));
		return $this->find('count',compact('conditions'));
	}

	public function count_state_despachado_confirm_delievery(){
		$conditions 			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_despachado'),'ProspectiveUser.state_flow' => Configure::read('variables.nombre_flujo.flujo_despachado'));
		return $this->find('count',compact('conditions'));
	}

	public function find_data_despachado_flujo($flujo_id){
		$this->recursive 		= -1;
		$conditions 			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.datos_despacho'),'FlowStage.prospective_users_id' => $flujo_id);
		return $this->find('first',compact('conditions'));
	}

	public function find_seeker($texto){
		$this->recursive 	= -1;
		$fields 			= array('FlowStage.id');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),
									'OR' => array(
								            'LOWER(FlowStage.nameDocument) LIKE' 			=> '%'.$texto.'%',
								            'LOWER(FlowStage.priceQuotation) LIKE' 			=> '%'.$texto.'%',
								            'LOWER(FlowStage.codigoQuotation) LIKE' 		=> '%'.$texto.'%'
								        )
									);
		return $this->find('all',compact('conditions','fields'));
	}

	public function consult_number_data_day_prospectos_contactados($fecha,$user_id,$state_flow,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.state_flow' => $state_flow,'FlowStage.created' => $fecha,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$contadorFinal 		= 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function consult_number_data_day_ventas($fecha,$user_id,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.date_verification' => $fecha,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$contadorFinal 		= 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function consult_information_user_flujos_asignado($fecha_inicio,$fecha_fin,$user_id){
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'),'DATE(FlowStage.created) >=' => $fecha_inicio,'DATE(FlowStage.created) <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		return $this->find('count',compact('conditions'));
	}

	public function total_flujos_by_user($fecha_inicio,$fecha_fin,$user_id){
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		return $this->find('count',compact('conditions'));
	}

	public function consult_idsFlujos_user_flujos_asignado($fecha_inicio,$fecha_fin,$user_id, $getLose = null){
		$this->recursive 	= 1;
		$group 				= array("FlowStage.prospective_users_id");
		$fields 			= array('FlowStage.prospective_users_id');

		if (is_null($getLose)) {
			$conditions			= array( 'OR' => [ 'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'), "ProspectiveUser.type >" => 0 ] ,'DATE(ProspectiveUser.created) >=' => $fecha_inicio,'DATE(ProspectiveUser.created) <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id );	

			return $this->find('all',compact('conditions','fields','group'));
		}else{
			$conditions			= array('OR' => [ 'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'), "ProspectiveUser.type >" => 0 ],'DATE(ProspectiveUser.created) >=' => $fecha_inicio,'DATE(ProspectiveUser.created) <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id );

			$actual = $this->find('all',compact('conditions','fields','group'));

			$conditions			= array('OR' => [ 'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'), "ProspectiveUser.type >" => 0 ],'DATE(ProspectiveUser.created) >=' => $fecha_inicio,'DATE(ProspectiveUser.created) <=' => $fecha_fin,'ProspectiveUser.user_lose' => $user_id );

			$perdidos = $this->find('all',compact('conditions','fields','group'));

			return array_merge($actual, $perdidos);

		}
		
	}

	public function consult_empresa_flujos_asignado($fecha_inicio,$fecha_fin,$lista_ids){
		$this->recursive 	= 0;
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin);
		$datos 				= $this->find('all',compact('conditions'));
		$datosArray 		= array();
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$datosArray[] = $valueRegistros;
				}
			}
		}
		return $datosArray;
	}

	public function consult_information_user_prospectives_week($fecha_inicio,$user_id){
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'),'FlowStage.created' => $fecha_inicio,'ProspectiveUser.user_id' => $user_id);
		return $this->find('count',compact('conditions'));
	}

	public function consult_information_user_flujos_contactados($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_contactado'),'DATE(FlowStage.created) >=' => $fecha_inicio,'DATE(FlowStage.created) <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$contadorFinal 		= 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function consult_information_user_flujos_cancelados($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cancelado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$contadorFinal 		= 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function consult_information_user_flujos_negociados($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_negociado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$contadorFinal 		= 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function consult_information_user_flujos_pagados($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$contadorFinal 		= 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function setDispatch(){
		$sqlQuery = "UPDATE flow_stages fl
		INNER JOIN conveyors cy ON cy.name = fl.conveyor
		SET fl.conveyor_id = cy.id
		WHERE fl.conveyor IS NOT NULL AND fl.state_flow IN ('Despachado','Despacho');
		";
		$this->query($sqlQuery);
	}

	public function consult_information_user_flujos_cotizadosByDay($fecha_inicio,$fecha_fin,$user_id,$lista_ids){

		if(empty($lista_ids)){
			$lista_ids = 0;
		}else{
			$lista_ids 			= Set::extract($lista_ids, "{n}.FlowStage.prospective_users_id");
			$lista_ids 			= implode(",", $lista_ids);
		}
		
		$estadoCotizado		= Configure::read('variables.nombre_flujo.flujo_cotizado');

		$datosFecha 		= array();
		$datosClienteFecha	= array();

		$total = 0;

		$query 				= " SELECT CONCAT (YEAR(ProspectiveUser.created),'-', 
							    LPAD(MONTH(ProspectiveUser.created), 2, '0'),'-',
							    LPAD(DAY(ProspectiveUser.created), 2, '0')) AS FECHA,
							    clients_naturals.name AS ClienteNatural,clients_legals.name ClienteEmpresa,  1 AS TOTAL
								FROM flow_stages AS FlowStage 
								LEFT JOIN prospective_users AS ProspectiveUser ON (FlowStage.prospective_users_id = ProspectiveUser.id) 
								LEFT JOIN quotations AS Quotation ON (FlowStage.document = Quotation.id) 
								LEFT JOIN clients_naturals ON clients_naturals.id = ProspectiveUser.clients_natural_id
								LEFT JOIN contacs_users ON contacs_users.id = ProspectiveUser.contacs_users_id
								LEFT JOIN clients_legals ON clients_legals.id = contacs_users.clients_legals_id
								WHERE FlowStage.state_flow = '$estadoCotizado' AND FlowStage.created >= '$fecha_inicio' AND FlowStage.created <= '$fecha_fin' AND ProspectiveUser.user_id = $user_id AND ProspectiveUser.id in ($lista_ids)
								GROUP BY YEAR(ProspectiveUser.created),MONTH(ProspectiveUser.created),DAY(ProspectiveUser.created), ProspectiveUser.clients_natural_id,ProspectiveUser.contacs_users_id";

		$datos = $this->query($query);
		foreach ($datos as $key => $value) {
			$total+= intval($value[0]["TOTAL"]);
			$datosFecha[$value[0]["FECHA"]] = isset($datosFecha[$value[0]["FECHA"]]) ? $datosFecha[$value[0]["FECHA"]] + $value[0]["TOTAL"] : intval($value[0]["TOTAL"]);
			if(!is_null($value["clients_naturals"]["ClienteNatural"]) && !empty($value["clients_naturals"]["ClienteNatural"]) && $value["clients_naturals"] != '' ){
				$datosClienteFecha[ $value[0]["FECHA"] ][ $value["clients_naturals"]["ClienteNatural"]] = isset($datosClienteFecha[ $value[0]["FECHA"] ][ $value["clients_naturals"]["ClienteNatural"]]) ? $datosClienteFecha[ $value[0]["FECHA"] ][ $value["clients_naturals"]["ClienteNatural"]] + intval($value[0]["TOTAL"]) : intval($value[0]["TOTAL"]);
			}else{
				$datosClienteFecha[ $value[0]["FECHA"] ][ $value["clients_legals"]["ClienteEmpresa"]] = isset($datosClienteFecha[ $value[0]["FECHA"] ][ $value["clients_legals"]["ClienteEmpresa"]]) ? $datosClienteFecha[ $value[0]["FECHA"] ][ $value["clients_legals"]["ClienteEmpresa"]] + intval($value[0]["TOTAL"]) : intval($value[0]["TOTAL"]);
			}
			
		}
		return array("total" => $total, "fecha" => $datosFecha, "cliente" => $datosClienteFecha);
		
	}

	public function consult_information_user_flujos_cotizados_new($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'DATE(FlowStage.created) >=' => $fecha_inicio,'DATE(FlowStage.created) <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$contadorFinal = 0;
		foreach ($datos as $valueRegistros) {
			if(in_array($valueRegistros['FlowStage']['prospective_users_id'], $lista_ids)){
				$contadorFinal++;
			}
		}
		return $contadorFinal;
	}

	public function consult_information_user_flujos_cotizados($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'DATE(FlowStage.created) >=' => $fecha_inicio,'DATE(FlowStage.created) <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$contadorFinal = 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function consult_information_user_flujos_cotizados_detail($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)','FlowStage.created');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'DATE(FlowStage.created) >=' => $fecha_inicio,'DATE(FlowStage.created) <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$arrFinal 			= [];
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$fecha = date("Y-m-d",strtotime($valueRegistros["FlowStage"]["created"]));
					if (array_key_exists($fecha, $arrFinal)) {
						$arrFinal[$fecha]+=1;
					}else{
						$arrFinal[$fecha] = 1;
					}
				}
			}
		}
		return $arrFinal;
	}

	public function consult_valor_user_flujos_cotizados($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$fields 			= array('FlowStage.priceQuotation','FlowStage.prospective_users_id');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$filasArray 		= count($datos);
		foreach ($datos as $count => $valueRegistros) {
			$contador = $count + 1;
			for ($i=$contador; $i < $filasArray; $i++) {
				if (isset($datos[$contador]['FlowStage']['prospective_users_id'])) {
					if ($datos[$contador]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
						unset($datos[$contador]);
					} else {
						$i++;
					}
				} else {
					$i++;
				}
			}
		}
		$sumaFinal 	= 0;
		foreach ($datos as $valueRegistros) {
			for ($l=0; $l < count($lista_ids); $l++) { 
				if ($lista_ids[$l]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$sumaFinal += $valueRegistros['FlowStage']['priceQuotation'];
				}
			}
		}
		return $sumaFinal;
	}

	public function consult_valor_user_flujos_cotizados_detail($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$fields 			= array('FlowStage.priceQuotation','FlowStage.prospective_users_id','FlowStage.created');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$filasArray 		= count($datos);
		foreach ($datos as $count => $valueRegistros) {
			$contador = $count + 1;
			for ($i=$contador; $i < $filasArray; $i++) {
				if (isset($datos[$contador]['FlowStage']['prospective_users_id'])) {
					if ($datos[$contador]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
						unset($datos[$contador]);
					} else {
						$i++;
					}
				} else {
					$i++;
				}
			}
		}
		$arrFinal 			= [];
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$fecha = date("Y-m-d",strtotime($valueRegistros["FlowStage"]["created"]));
					if (array_key_exists($fecha, $arrFinal)) {
						$arrFinal[$fecha]+= intval($valueRegistros["FlowStage"]["priceQuotation"]);
					}else{
						$arrFinal[$fecha] =  intval($valueRegistros["FlowStage"]["priceQuotation"]);
					}
				}
			}
		}
		return $arrFinal;
	}





	public function consult_valor_user_flujos_ventas($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$fields 			= array('FlowStage.valor','FlowStage.prospective_users_id');
		$conditions			= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.date_verification >=' => $fecha_inicio,'FlowStage.date_verification <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$filasArray 		= count($datos);
		foreach ($datos as $count => $valueRegistros) {
			$contador = $count + 1;
			for ($i=$contador; $i < $filasArray; $i++) {
				if (isset($datos[$contador]['FlowStage']['prospective_users_id'])) {
					if ($datos[$contador]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
						unset($datos[$contador]);
					} else {
						$i++;
					}
				} else {
					$i++;
				}
			}
		}
		$sumaFinal 	= 0;
		foreach ($datos as $valueRegistros) {
			for ($l=0; $l < count($lista_ids); $l++) { 
				if ($lista_ids[$l]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$sumaFinal = $valueRegistros['FlowStage']['valor'] + $sumaFinal;
				}
			}
		}
		return $sumaFinal;
	}






	public function count_information_empresa_flujos_asignado($fecha_inicio,$fecha_fin,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$contadorFinal 		= 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['ProspectiveUser']['id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function count_information_empresa_flujos_asignado_user($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		$datos 				= $this->find('all',compact('conditions'));
		$contadorFinal 		= 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function consult_idsFlujos_empresa_flujos_asignado($fecha_inicio,$fecha_fin){
		$this->recursive 	= 0;
		$fields 			= array('FlowStage.prospective_users_id');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin);
		return $this->find('all',compact('conditions','fields'));
	}

	public function consult_idsFlujos_empresa_flujos_asignado_asesor($fecha_inicio,$fecha_fin,$user_id){
		$fields 			= array('FlowStage.prospective_users_id');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		return $this->find('all',compact('conditions','fields'));
	}











	// REPORTE ADVISER
	public function count_flujos_cotizados_rango_fechas($fecha_inicio,$fecha_fin,$lista_ids, $lista = null){

		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'DATE(FlowStage.created) >=' => $fecha_inicio,'DATE(FlowStage.created) <=' => $fecha_fin);

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}

		$datos 				= $this->find('all',compact('conditions','fields'));
		$filasArray 		= count($datos);
		$contadorFinal 		= 0;
		$listaIds			= [];

		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['ProspectiveUser']['id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$listaIds[] = $valueRegistros['FlowStage']['prospective_users_id'];
					$contadorFinal++;
				}
			}
		}
		if (!is_null($lista)) {
			return $listaIds;
		}
		return $contadorFinal;
	}

	public function ids_flujos_cotizados_rango_fechas($fecha_inicio,$fecha_fin,$lista_ids){
		$this->recursive 	= 0;
		$fields 			= array('DISTINCT(FlowStage.prospective_users_id)');
		$conditions			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),'FlowStage.created >=' => $fecha_inicio,'FlowStage.created <=' => $fecha_fin);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$datosArray 		= array();
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['ProspectiveUser']['id'] == $valueRegistros['FlowStage']['prospective_users_id']) {
					$datosArray['prospective_id'][] = $valueRegistros['FlowStage']['prospective_users_id'];
				}
			}
		}
		return $datosArray;
	}



}
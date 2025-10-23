<?php
App::uses('AppModel', 'Model');

App::uses('HttpSocket', 'Network/Http');

class QuotationsProduct extends AppModel {

	public $belongsTo = array(
		'Quotation' => array(
			'className' => 'Quotation',
			'foreignKey' => 'quotation_id'
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id'
		)
	);


	public function setLlc($id_quotation = null){
		
		$productsLLC  = [];
		try {

			$conditions = ["QuotationsProduct.id_llc != "=>null,"QuotationsProduct.state_llc != "=> 2];

			if (!is_null($id_quotation)) {
				$conditions["QuotationsProduct.quotation_id"] = $id_quotation;
			}

			$this->recursive = -1;

			$products = $this->find("all",compact("conditions"));

			if (!empty($products)) {
				foreach ($products as $key => $value) {
					$HttpSocket = new HttpSocket();
					$response 	= $HttpSocket->get($this->API.'supplierinvoices/'.$value["QuotationsProduct"]["id_llc"], [] ,["header" => ["DOLAPIKEY" => "Kebco2020**--","Accept"=>"application/json"] ]);
					$code 		= $response->code;

					if ($code == 200) {
						$productsLLC = json_decode($response->body());
						$this->updateAll(
							["QuotationsProduct.state_llc" => intval($productsLLC->statut)],
							["QuotationsProduct.id_llc" => intval($value["QuotationsProduct"]["id_llc"])]
						);
					}
				}
			}

		} catch (Exception $e) {

		}
			
	}

	public function get_data($product_cotizacion_id){
		$this->recursive 	= -1;
		$conditions 		= array('QuotationsProduct.id' => $product_cotizacion_id);
		return $this->find('first',compact('conditions'));
	}

	public function get_data_quotation($quotation_id, $todo = null){
		if (is_null($todo)) {
			$this->recursive 	= 0;
			$fields				= array('QuotationsProduct.*','Product.*');
		}else{
			$this->recursive 	= 2;
		}
		$order  			= array("QuotationsProduct.delivery ='Inmediato'" => "DESC","QuotationsProduct.id" => "ASC" );
		$conditions			= array('QuotationsProduct.quotation_id' => $quotation_id);
		return $this->find('all',compact('conditions','fields','order'));
	}

	public function getDataProducts(){
		$strQuery = "SELECT QuotationsProduct.quantity, Product.id, Product.name, 
		Product.purchase_price, Product.brand 
		FROM quotations_products AS QuotationsProduct 
		LEFT JOIN quotations AS Quotation ON (QuotationsProduct.quotation_id = Quotation.id) 
		LEFT JOIN products AS Product ON (QuotationsProduct.product_id = Product.id) 
		WHERE QuotationsProduct.quotation_id IN (

		SELECT FlowStage.document FROM flow_stages AS FlowStage WHERE FlowStage.prospective_users_id IN 
		(
			SELECT DISTINCT FlowStage2.prospective_users_id FROM flow_stages AS FlowStage2 
			WHERE FlowStage2.state_flow = '".Configure::read('variables.nombre_flujo.flujo_pagado')."' 
			AND FlowStage2.payment_verification = 1 ORDER BY FlowStage2.prospective_users_id desc
		) 
		AND FlowStage.state_flow = '".Configure::read('variables.nombre_flujo.flujo_cotizado')."' ORDER BY FlowStage.id DESC 

					)";
		$data = $this->query($strQuery);
		if(!empty($data)){
			return $data;
		}else{
			return null;
		}
	}

	public function get_data_quotation_for_importer($quotation_id){
		
		$this->recursive 	= 0;
		$fields				= array('QuotationsProduct.quantity as QuantityFinal','Product.id','Product.name','Product.part_number','Product.brand','Product.brand_id','Product.img','Product.quantity',"Product.quantity_bog","Product.quantity_stm","Product.quantity_stb");
		$conditions			= array('QuotationsProduct.quotation_id' => $quotation_id);
		return $this->find('all',compact('conditions','fields'));
	}

	public function get_data_quotation_list($cotizacion_id){
		$this->recursive 	= -1;
		$fields				= array('QuotationsProduct.product_id','QuotationsProduct.product_id');
		$conditions			= array('QuotationsProduct.quotation_id' => $cotizacion_id);
		return $this->find('list',compact('conditions','fields'));
	}

	public function row_data_quotation_products($cotizacion_id){
		$this->recursive 	= -1;
		$fields				= array('QuotationsProduct.product_id','QuotationsProduct.product_id');
		$conditions			= array('QuotationsProduct.quotation_id' => $cotizacion_id);
		return $this->find('count',compact('conditions','fields'));
	}

	public function find_quotation_id($product_id, $grupo = null){
		$this->recursive 	= -1;
		$fields				= array('QuotationsProduct.quotation_id','QuotationsProduct.quotation_id');
		$conditions			= array('QuotationsProduct.product_id' => $product_id);

		if(!is_null($grupo)){
			if($grupo == "one"){
				$conditions = array("OR" => $conditions);
			}
			$fields = array('QuotationsProduct.quotation_id','QuotationsProduct.product_id');
			$datos  = $this->find('all',compact('conditions','fields'));
			$quotations = array();
			$quotationsFinal = array();
			foreach ($datos as $key => $value) {
				if(!isset($quotations[$value["QuotationsProduct"]["quotation_id"]])){
					$quotations[$value["QuotationsProduct"]["quotation_id"]][] = $value["QuotationsProduct"]["product_id"];
				}elseif(isset($quotations[$value["QuotationsProduct"]["quotation_id"]]) && !in_array($value["QuotationsProduct"]["product_id"], $quotations[$value["QuotationsProduct"]["quotation_id"]])){
					$quotations[$value["QuotationsProduct"]["quotation_id"]][] = $value["QuotationsProduct"]["product_id"];
				}
			}
			foreach ($quotations as $key => $value) {
				if(count($product_id) == count($value) && $grupo == "all" ){
					$quotationsFinal[$key] = $key;
				}elseif($grupo == "one"){
					$quotationsFinal[$key] = $key;
				}
			}
			return $quotationsFinal;
		}

		return $this->find('list',compact('conditions','fields'));
	}

	public function find_cantidad_product($quotation_id,$product_id){
		$fields				= array('QuotationsProduct.quantity');
		$conditions			= array('QuotationsProduct.product_id' => $product_id,'QuotationsProduct.quotation_id' => $quotation_id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['QuotationsProduct']['quantity'];
	}

	public function find_precio_product($quotation_id,$product_id){
		$fields				= array('QuotationsProduct.price');
		$conditions			= array('QuotationsProduct.product_id' => $product_id,'QuotationsProduct.quotation_id' => $quotation_id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['QuotationsProduct']['price'];
	}

	public function quotation_id_sale($id){
		$this->recursive 	= -1;
		$fields				= array('QuotationsProduct.quotation_id');
		$conditions			= array('QuotationsProduct.id' => $id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['QuotationsProduct']['quotation_id'];
	}
	
	public function finish_products_cotizacion($cotizacion_id){
		$this->recursive 	= -1;
		$list 				= array('3');
		$conditions			= array('QuotationsProduct.quotation_id' => $cotizacion_id,'QuotationsProduct.state !=' => $list);
		return $this->find('count',compact('conditions'));
	}

	public function update_entrega_orden($cotizacion_id) {
		$datosSinImportacion = $this->find("all",["conditions" => ['QuotationsProduct.state !=' => '2', 'QuotationsProduct.quotation_id' => $cotizacion_id]]);
	    $this->updateAll( 
	    	array('QuotationsProduct.state' => '1', 'QuotationsProduct.warehouse' => 3), array('QuotationsProduct.state !=' => '2', 'QuotationsProduct.quotation_id' => $cotizacion_id)
	    );
	    return $datosSinImportacion;
	}

	public function products_import_quotation($cotizacion_id){
		$this->recursive 	= -1;
		$fields				= array('QuotationsProduct.product_id','QuotationsProduct.product_id');
		$conditions 		= array('QuotationsProduct.state !=' => '1','QuotationsProduct.quotation_id' => $cotizacion_id);
		return $this->find('list',compact('conditions','fields'));
	}

}
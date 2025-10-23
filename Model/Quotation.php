<?php
App::uses('AppModel', 'Model');

class Quotation extends AppModel {

	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Nombre requerido',
			),
		)
	);

	public $belongsTo = array(
		'FlowStage' => array(
			'className' => 'FlowStage',
			'foreignKey' => 'flow_stage_id'
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Header' => array(
			'className' => 'Header',
			'foreignKey' => 'header_id'
		),
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_users_id'
		),
	);

	public $hasMany = array(
		'QuotationsProduct' => array(
			'className' => 'QuotationsProduct',
			'foreignKey' => 'quotation_id',
			'dependent' => false
		)
	);

	public function getProspectiveUserLast($product_id){
		$query = "SELECT products.name, quotations_products.quantity, prospective_users.id,
			quotations_products.price, prospective_users.bill_date, quotations.id cotizacionID, quotations.state
			FROM quotations_products 
			INNER JOIN products ON products.id = quotations_products.product_id
			INNER JOIN quotations ON quotations.id = quotations_products.quotation_id
			INNER JOIN prospective_users ON prospective_users.id = quotations.prospective_users_id
			WHERE prospective_users.bill_code IS NOT NULL AND quotations.state IN (1,2)
			AND products.id = $product_id
			ORDER BY prospective_users.bill_date DESC, quotations_products.price DESC
			LIMIT 1
			";
		$datos = $this->query($query);
		if(!empty($datos)){
			return $datos["0"]["prospective_users"]["id"];
		}
		return null;
	}

	public function get_data($quotation_id){
		$this->recursive 	= -1;
		$conditions			= array('Quotation.id' => $quotation_id);
		return $this->find('first',compact('conditions'));
	}

	public function list_quotations_sales_product($quotation_ids){
		$this->recursive 	= -1;
		$conditions			= array('Quotation.id' => $quotation_ids);
		return $this->find('all',compact('conditions'));
	}

	public function list_data_prospective($prospective_id){
		$dataReturn = array();
		$this->recursive 	= -1;
		$order				= array('Quotation.id' => 'desc');
		$conditions			= array('Quotation.prospective_users_id' => $prospective_id,'Quotation.state' => 1);
		$data = $this->find('all',compact('conditions','order'));

		foreach ($data as $key => $value) {
			$dataReturn[$value["Quotation"]["id"]] = $value["Quotation"]["name"]." - ".$value["Quotation"]["codigo"];
		}
		return $dataReturn;
	}

	public function all_data_prospective($prospective_id){
		$this->recursive 	= -1;
		$order				= array('Quotation.id' => 'desc');
		$conditions			= array('Quotation.prospective_users_id' => $prospective_id);
		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["Quotation.user_id"] = AuthComponent::user("id");
		}
		return $this->find('all',compact('conditions','order'));
	}

	public function count_quotation_flujo($prospective_id){
		$this->recursive 	= -1;
		$conditions			= array('Quotation.prospective_users_id' => $prospective_id);
		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["Quotation.user_id"] = AuthComponent::user("id");
		}
		return $this->find('count',compact('conditions'));
	}

	public function find_seeker($texto){
		$this->recursive 	= -1;
		$fields 			= array('Quotation.id');
		$limit 				= 20;
		$conditions			= array('OR' => array(
							            'LOWER(Quotation.codigo) LIKE' 		=> '%'.$texto.'%',
							            'LOWER(Quotation.name) LIKE' 		=> '%'.$texto.'%',
							            'LOWER(Quotation.total) LIKE' 		=> '%'.$texto.'%'
							        )
								);

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["Quotation.user_id"] = AuthComponent::user("id");
		}

		return $this->find('all',compact('conditions','fields','limit'));
	}

	public function count_cotizaciones_enviadas_client_codigo($texto){
		$conditions 	= array('Quotation.codigo' => $texto);
		return $this->find('count',compact('conditions'));
	}

	public function update_state_gestionado($flujo_id) {
	    $this->updateAll(
	    	array('Quotation.state' => '2'), array('Quotation.prospective_users_id' => $flujo_id)
	    );
	}

	public function find_flujoid_for_quotation_id($quotation_id){
		$this->recursive 	= -1;
		$conditions			= array('Quotation.id' => $quotation_id);
		$datos 				= $this->find('first',compact('conditions'));
		return $datos['Quotation']['prospective_users_id'];
	}

	public function codigo_for_quotation($quotation_id){
		$this->recursive 	= -1;
		$conditions			= array('Quotation.id' => $quotation_id);
		$datos 				= $this->find('first',compact('conditions'));
		return $datos['Quotation']['codigo'];
	}

	public function id_data_codigo_cotizacion($codigo){
		$this->recursive 	= -1;
		$conditions			= array('Quotation.codigo' => $codigo);
		$datos 				= $this->find('first',compact('conditions'));
		return $datos['Quotation']['id'];
	}
}
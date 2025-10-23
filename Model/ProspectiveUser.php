<?php
App::uses('AppModel', 'Model');

class ProspectiveUser extends AppModel {

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'ContacsUser' => array(
			'className' => 'ContacsUser',
			'foreignKey' => 'contacs_users_id'
		),
		'ClientsNatural' => array(
			'className' => 'ClientsNatural',
			'foreignKey' => 'clients_natural_id'
		),
		'Import' => array(
			'className' => 'Import',
			'foreignKey' => 'import_id'
		),
		'Adress' => array(
			'className' => 'Adress',
			'foreignKey' => 'adress_id'
		)
	);

	public $hasOne = [];

	public $hasAndBelongsToMany = array(
		'Imports' => array(
			'className' => 'ImportsProspectiveUser',
			'joinTable' => 'imports_prospective_users',
			'foreignKey' => 'prospective_user_id',
			'associationForeignKey' => 'import_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),		
	);

	public function setAttentioData(){
		$this->hasOne = array(		
			'AtentionTime' => array(
				'className' => 'AtentionTime',
				'foreignKey' => 'prospective_users_id'
			),		
		);
	}

	public function setOtrasFacturas(){
		$this->hasMany = array(		
			'Salesinvoice' => array(
				'className' => 'Salesinvoice',
				'foreignKey' => 'prospective_user_id'
			),		
		);
	}

	public $hasMany = array(
		'FlowStage' => array(
			'className' => 'FlowStage',
			'foreignKey' => 'prospective_users_id'
		),
		'BillProduct' => array(
			'className' => 'BillProduct',
			'foreignKey' => 'prospective_user_id'
		),
		'FlowStagesProduct' => array(
			'className' => 'FlowStagesProduct',
			'foreignKey' => 'prospective_users_id',
			'dependent' => false
		),
		'AtentionTime' => array(
				'className' => 'AtentionTime',
				'foreignKey' => 'prospective_users_id'
			),
		
		'Payment' => array(
			'className' => 'Payment',
			'foreignKey' => 'prospective_users_id'
		),
		'TechnicalService' => array(
			'className' => 'TechnicalService',
			'foreignKey' => 'prospective_users_id'
		),
		'ProgresNote' => array(
			'className' => 'ProgresNote',
			'foreignKey' => 'prospective_users_id'
		),
		'Receipt' => array(
			'className' => 'Receipt',
			'foreignKey' => 'prospective_user_id'
		)
	);

	public function total_flujos_by_user($fecha_inicio,$fecha_fin,$user_id){
		$conditions			= array('DATE(ProspectiveUser.created) >=' => $fecha_inicio,'DATE(ProspectiveUser.created) <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id);
		return $this->find('count',compact('conditions'));
	}

	public function getSalesList($date_ini = null, $date_end = null, $user_id = null, $ventas = null){

		if(is_null($date_ini)){
			$date_ini = date("Y-m-d");
		}

		if(is_null($date_end)){
			$date_end = date("Y-m-d");
		}

		$conditions 	= array( 'bill_date IS NOT NULL','bill_date >=' => $date_ini, 'bill_date <=' => $date_end );
		// $recursive 		= 2;
		$fields 		= array("ProspectiveUser.*","ClientsNatural.*","ContacsUser.*");

		if(!is_null($user_id)){
			$conditions["bill_user"] = $user_id;
		}

		if(!is_null($ventas)){
			$conditions["origin"] = "Tienda";
		}
		$this->unBindModel(array(
			"hasMany" => array('FlowStage','FlowStagesProduct','AtentionTime','Payment','TechnicalService','ProgresNote'),
			"belongsTo" => array("User","Import","Adress"),
			"hasAndBelongsToMany" => array("Imports")
			)
		);

		$this->ContacsUser->unBindModel(array(
			"belongsTo" => array(
				"ProspectiveUser"
			)
		));

		$this->ContacsUser->ClientsLegal->recursive = -1;
		$sales = $this->find("all", compact("conditions","recursive","fields"));


		foreach ($sales as $key => $value) {
			if(!empty($value["ContacsUser"]["id"])){
				$sales[$key]["ContacsUser"]["ClientsLegal"] = $this->ContacsUser->ClientsLegal->findById($value["ContacsUser"]["clients_legals_id"])["ClientsLegal"];
			}
		}
		return $sales;
	}

	public function getSalesListTecnical($date_ini = null, $date_end = null, $user_id = null){

		if(is_null($date_ini)){
			$date_ini = date("Y-m-d");
		}

		if(is_null($date_end)){
			$date_end = date("Y-m-d");
		}

		$conditions 	= array( 'bill_date IS NOT NULL','bill_date >=' => $date_ini, 'bill_date <=' => $date_end, "ProspectiveUser.type != " => "0", "locked" => 0 );
		// $recursive 		= 2;
		//$fields 		= array("ProspectiveUser.*","ClientsNatural.*","ContacsUser.*","TechnicalService.*");

		if(!is_null($user_id)){
			$conditions["bill_user"] = $user_id;
		}
		$this->unBindModel(array(
			"hasMany" => array('FlowStage','FlowStagesProduct','AtentionTime','Payment','ProgresNote'),
			"belongsTo" => array("Import","Adress"),
			"hasAndBelongsToMany" => array("Imports")
			)
		);

		$this->ContacsUser->unBindModel(array(
			"belongsTo" => array(
				"ProspectiveUser"
			)
		));

		$this->ContacsUser->ClientsLegal->recursive = -1;
		$sales = $this->find("all", compact("conditions","recursive","fields"));


		foreach ($sales as $key => $value) {
			if(!empty($value["ContacsUser"]["id"])){
				$sales[$key]["ContacsUser"]["ClientsLegal"] = $this->ContacsUser->ClientsLegal->findById($value["ContacsUser"]["clients_legals_id"])["ClientsLegal"];
			}
		}
		return $sales;
	}

	public function getSalesListCodesExt($user_id = null,$account_id = null){

		$joins = array(
	        array(
	            'table' => 'receipts',
	            'alias' => 'Receipt',
	            'type' => 'INNER',
	            'conditions' => array(
	                'Receipt.prospective_user_id = ProspectiveUser.id'
	            )
	        )
		);

		$conditions 	= array('bill_date IS NOT NULL',"Receipt.state" => 0);
		// $recursive 		= 2;
		$fields 		= array("ProspectiveUser.*","ClientsNatural.*","ContacsUser.*","Receipt.*");

		if (!is_null($account_id)) {
			$conditions["Receipt.account_id"] = $account_id;
			$conditions["Receipt.state"] = 1;
		}else{
			if(!is_null($user_id)){
				$conditions["bill_user"] = $user_id;
				$conditions["Receipt.user_id"] = $user_id;
			}
		}
		$this->unBindModel(array(
			"hasMany" => array('FlowStage','FlowStagesProduct','AtentionTime','Payment','TechnicalService','ProgresNote','Receipt'),
			"belongsTo" => array("User","Import","Adress"),
			"hasAndBelongsToMany" => array("Imports")
			)
		);

		$this->ContacsUser->unBindModel(array(
			"belongsTo" => array(
				"ProspectiveUser"
			)
		));

		// $this->ContacsUser->ClientsLegal->recursive = -1;
		$sales = $this->find("all", compact("conditions","recursive","fields","joins"));


		foreach ($sales as $key => $value) {
			if(!empty($value["ContacsUser"]["id"])){
				$sales[$key]["ContacsUser"]["ClientsLegal"] = $this->ContacsUser->ClientsLegal->findById($value["ContacsUser"]["clients_legals_id"])["ClientsLegal"];
			}
		}
		return $sales;
	}

	public function getSalesListCodes($date_ini = null, $date_end = null, $user_id = null, $restricted = false){

		if(is_null($date_ini)){
			$date_ini = date("Y-m-d");
		}

		if(is_null($date_end)){
			$date_end = date("Y-m-d");
		}

		$joins = array(
	        array(
	            'table' => 'receipts',
	            'alias' => 'Receipt',
	            'type' => 'INNER',
	            'conditions' => array(
	                'Receipt.prospective_user_id = ProspectiveUser.id'
	            )
	        ),
	        array(
	            'table' => 'salesinvoices',
	            'alias' => 'Salesinvoice',
	            'type' => 'LEFT',
	            'conditions' => array(
	                'Salesinvoice.id = Receipt.salesinvoice_id'
	            )
	        )
		);

		if ($restricted) {
			$conditions 	= array("OR"=>[['Salesinvoice.bill_date !=' => null, "Salesinvoice.locked" => 0],['ProspectiveUser.bill_date !=' => null, "ProspectiveUser.locked" => 0]],'date_receipt >=' => $date_ini, 'date_receipt <=' => $date_end, "ProspectiveUser.state_flow" => [5,6,8], );
		}else{
			$conditions 	= array("OR"=>[['Salesinvoice.bill_date IS NOT NULL'],['ProspectiveUser.bill_date IS NOT NULL']],'date_receipt >=' => $date_ini, 'date_receipt <=' => $date_end );
		}	

		// $condOr = ["OR" =>
		// 	[ 'date_receipt >=' => $date_ini, 'date_receipt <=' => $date_end, ],
		// 	[ 'bill_date >=' => $date_ini, 'bill_date <=' => $date_end, ],
		// ];

		// if ($restricted) {
		// 	$conditions 	= array('bill_date IS NOT NULL', $condOr, "ProspectiveUser.state_flow" => [5,6,8] );
		// }else{
		// 	$conditions 	= array('bill_date IS NOT NULL',$condOr );
		// }			

		// $recursive 		= 2;
		$fields 		= array("ProspectiveUser.*","ClientsNatural.*","ContacsUser.*","Receipt.*","Salesinvoice.*");

		if(!is_null($user_id)){
			$conditions["bill_user"] = $user_id;
			$conditions["Receipt.user_id"] = $user_id;

			
			App::import("model","Salesinvoice");
			$Salesinvoice = new Salesinvoice();

			$salesList = $Salesinvoice->find("list",["conditions"=>["Salesinvoice.user_id" => $user_id, "Salesinvoice.locked" => 1 ], "fields" => ["id","id"] ]);

			if (!empty($salesList)) {
				$conditions["Receipt.salesinvoice_id !="] = $salesList;
			}



		}
		$this->unBindModel(array(
			"hasMany" => array('FlowStage','FlowStagesProduct','AtentionTime','Payment','TechnicalService','Receipt'),
			"belongsTo" => array("User","Import","Adress"),
			"hasAndBelongsToMany" => array("Imports")
			)
		);

		$this->ContacsUser->unBindModel(array(
			"belongsTo" => array(
				"ProspectiveUser"
			)
		));

		// $this->ContacsUser->ClientsLegal->recursive = -1;
		$sales = $this->find("all", compact("conditions","recursive","fields","joins"));

		foreach ($sales as $key => $value) {
			if(!empty($value["ContacsUser"]["id"])){
				$sales[$key]["ContacsUser"]["ClientsLegal"] = $this->ContacsUser->ClientsLegal->findById($value["ContacsUser"]["clients_legals_id"])["ClientsLegal"];
			}
		}
		return $sales;
	}

	public function getSalesListCodesAllInformation($date_ini = null, $date_end = null, $user_id = null, $restricted = false, $state = "",$liquidation_id = null){

		if(is_null($date_ini)){
			$date_ini = date("Y-m-d");
		}

		if(is_null($date_end)){
			$date_end = date("Y-m-d");
		}

		$joins = array(
	        array(
	            'table' => 'receipts',
	            'alias' => 'Receipt',
	            'type' => 'INNER',
	            'conditions' => array(
	                'Receipt.prospective_user_id = ProspectiveUser.id'
	            )
	        ),
	        array(
	            'table' => 'salesinvoices',
	            'alias' => 'Salesinvoice',
	            'type' => 'LEFT',
	            'conditions' => array(
	                'Salesinvoice.id = Receipt.salesinvoice_id'
	            )
	        )
		);

		if ($restricted) {
			$conditions 	= array("OR"=>
				[
					
					['Salesinvoice.bill_date !=' => null, "Salesinvoice.locked" => 0,'Salesinvoice.bill_date >=' => $date_ini, 'Salesinvoice.bill_date <=' => $date_end, "date_receipt < Salesinvoice.bill_date"],
					['ProspectiveUser.bill_date !=' => null, "ProspectiveUser.locked" => 0, 'ProspectiveUser.bill_date >=' => $date_ini, 'ProspectiveUser.bill_date <=' => $date_end, "date_receipt < ProspectiveUser.bill_date"],
					['date_receipt >=' => $date_ini, 'date_receipt <=' => $date_end],

				], "ProspectiveUser.state_flow" => [5,6,8], 
			);
		}else{
			$conditions 	= array("OR"=>[['Salesinvoice.bill_date IS NOT NULL'],['ProspectiveUser.bill_date IS NOT NULL']],'date_receipt >=' => $date_ini, 'date_receipt <=' => $date_end );
		}	

		if ($state != "") {
			$conditions["Receipt.state"] = $state;	
		}

		if (!is_null($liquidation_id)) {
			$conditions["Receipt.liquidation_id"] = $liquidation_id;	
		}


		// $condOr = ["OR" =>
		// 	[ 'date_receipt >=' => $date_ini, 'date_receipt <=' => $date_end, ],
		// 	[ 'bill_date >=' => $date_ini, 'bill_date <=' => $date_end, ],
		// ];

		// if ($restricted) {
		// 	$conditions 	= array('bill_date IS NOT NULL', $condOr, "ProspectiveUser.state_flow" => [5,6,8] );
		// }else{
		// 	$conditions 	= array('bill_date IS NOT NULL',$condOr );
		// }			

		// $recursive 		= 2;
		$fields 		= array("ProspectiveUser.*","ClientsNatural.*","ContacsUser.*","Receipt.*","Salesinvoice.*");

		if(!is_null($user_id)){
			$conditions["bill_user"] = $user_id;
			$conditions["Receipt.user_id"] = $user_id;

			
			App::import("model","Salesinvoice");
			$Salesinvoice = new Salesinvoice();

			$salesList = $Salesinvoice->find("list",["conditions"=>["Salesinvoice.user_id" => $user_id, "Salesinvoice.locked" => 1 ], "fields" => ["id","id"] ]);

			if (!empty($salesList)) {
				$conditions["Receipt.salesinvoice_id !="] = $salesList;
			}



		}
		$this->unBindModel(array(
			"hasMany" => array('FlowStage','FlowStagesProduct','AtentionTime','Payment','TechnicalService','Receipt'),
			"belongsTo" => array("User","Import","Adress"),
			"hasAndBelongsToMany" => array("Imports")
			)
		);

		$this->ContacsUser->unBindModel(array(
			"belongsTo" => array(
				"ProspectiveUser"
			)
		));

		
		// echo "<pre>";
		// var_dump($conditions); die();

		$sales = $this->find("all", compact("conditions","recursive","fields","joins"));
		// echo "<pre>";
		// print_r($sales);
		// print_r($conditions);
		// die;

		// $this->ContacsUser->ClientsLegal->recursive = -1;

		foreach ($sales as $key => $value) {
			if(!empty($value["ContacsUser"]["id"])){
				$sales[$key]["ContacsUser"]["ClientsLegal"] = $this->ContacsUser->ClientsLegal->findById($value["ContacsUser"]["clients_legals_id"])["ClientsLegal"];
			}
		}
		return $sales;
	}

	public function getSalesListRecibos($date_ini = null, $date_end = null, $user_id = null){

		if(is_null($date_ini)){
			$date_ini = date("Y-m-d");
		}

		if(is_null($date_end)){
			$date_end = date("Y-m-d");
		}

		$joins = array(
			        array(
			            'table' => 'receipts',
			            'alias' => 'Receipt',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Receipt.prospective_user_id = ProspectiveUser.id'
			            )
			        ),
			        array(
			            'table' => 'users',
			            'alias' => 'User',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Receipt.user_id = User.id'
			            )
			        ),
		);

		$conditions 	= array('date_receipt >=' => $date_ini, 'date_receipt <=' => $date_end );
		// $recursive 		= 2;
		$fields 		= array("ProspectiveUser.*","ClientsNatural.*","ContacsUser.*","Receipt.*","User.*");

		$this->unBindModel(array(
			"hasMany" => array('FlowStage','FlowStagesProduct','AtentionTime','Payment','TechnicalService','Receipt'),
			"belongsTo" => array("User","Import","Adress"),
			"hasAndBelongsToMany" => array("Imports")
			)
		);

		$this->ContacsUser->unBindModel(array(
			"belongsTo" => array(
				"ProspectiveUser"
			)
		));

		if(!is_null($user_id)){
			$conditions["bill_user"] = $user_id;
			$conditions["Receipt.user_id"] = $user_id;
		}

		// $this->ContacsUser->ClientsLegal->recursive = -1;
		$sales = $this->find("all", compact("conditions","recursive","fields","joins"));


		foreach ($sales as $key => $value) {
			if(!empty($value["ContacsUser"]["id"])){
				$sales[$key]["ContacsUser"]["ClientsLegal"] = $this->ContacsUser->ClientsLegal->findById($value["ContacsUser"]["clients_legals_id"])["ClientsLegal"];
			}
		}
		return $sales;
	}

	public function getSales($date_ini = null, $date_end = null, $user_id = null){

		if(is_null($date_ini)){
			$date_ini = date("Y-m-d");
		}

		if(is_null($date_end)){
			$date_end = date("Y-m-d");
		}


		$conditions = array(
			'bill_date IS NOT NULL','bill_date >=' => $date_ini, 'bill_date <=' => $date_end,
			"state != " => array(5,6)
		);

		if(!is_null($user_id)){
			$conditions["bill_user"] = $user_id;
		}

		$this->recursive = -1;

		$fields = array("SUM(bill_value) as total");

		$total = $this->find("first", compact("conditions","fields"));

		return !empty($total["0"]["total"]) ? $total["0"]["total"] : 0;

	}

	public function getSalesCount($date_ini = null, $date_end = null, $user_id = null){

		if(is_null($date_ini)){
			$date_ini = date("Y-m-d");
		}

		if(is_null($date_end)){
			$date_end = date("Y-m-d");
		}


		$conditions = array(
			'bill_date IS NOT NULL','bill_date >=' => $date_ini, 'bill_date <=' => $date_end,
			"state != " => array(5,6)
		);

		if(!is_null($user_id)){
			$conditions["bill_user"] = $user_id;
		}

		$this->recursive = -1;

		$fields = array("COUNT(id) as total");

		$total = $this->find("first", compact("conditions","fields"));

		return !empty($total["0"]["total"]) ? $total["0"]["total"] : 0;

	}

	// MODELO DATA
	public function get_data($id){
		$this->recursive 	= -1;
		$conditions 		= array('ProspectiveUser.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function find_seeker($texto){
		$this->recursive 	= -1;
		$fields 			= array('ProspectiveUser.id');
		$conditions			= array('OR' => [ 'ProspectiveUser.id LIKE' 			=> '%'.$texto.'%', 'ProspectiveUser.email_customer LIKE' 			=> '%'.$texto.'%', 'ProspectiveUser.phone_customer LIKE' 			=> '%'.$texto.'%' ]);
		return $this->find('all',compact('conditions','fields'));
	}

	public function get_stateFlow_flujo($id){
		$this->recursive 	= -1;
		$fields				= array('ProspectiveUser.state_flow');
		$conditions 		= array('ProspectiveUser.id' => $id);
		$datos 				= $this->find('first',compact('conditions','fields'));;
		return $datos['ProspectiveUser']['state_flow'];
	}

	public function get_userReceptor_flujo($id){
		$this->recursive 	= -1;
		$fields				= array('ProspectiveUser.user_receptor');
		$conditions 		= array('ProspectiveUser.id' => $id);
		$datos 				= $this->find('first',compact('conditions','fields'));;
		return $datos['ProspectiveUser']['user_receptor'];
	}

	public function get_data_modelos($id){
		$conditions 		= array('ProspectiveUser.id' => $id);
		return $this->find('first',compact('conditions')); 
	}

	public function get_data_model_user($id,$recursive = 0){
		$this->recursive 	= $recursive;
		$fields				= array('ProspectiveUser.*,User.*');
		$conditions 		= array('ProspectiveUser.id' => $id);
		return $this->find('first',compact('conditions','fields')); 
	}

	public function get_data_model_contacs($id){
		$this->recursive 	= 0;
		$fields				= array('ProspectiveUser.*,ContacsUser.*');
		$conditions 		= array('ProspectiveUser.id' => $id);
		return $this->find('first',compact('conditions','fields')); 
	}

	public function find_adviser_flujo($id){
		$this->recursive 	= -1;
		$fields				= array('ProspectiveUser.user_id');
		$conditions 		= array('ProspectiveUser.id' => $id);
		return $this->find('first',compact('conditions','fields'));
	}

	public function consult_information_flujos_asignados(){
		$conditions			= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_asignado'));
		return $this->find('count',compact('conditions'));
	}

	public function count_flujos_asignados_range_fechas($fecha_inicio,$fecha_fin){
		$conditions			= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_asignado'),'DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin);
		return $this->find('count',compact('conditions'));
	}

	public function consult_information_flujos_asignados_all(){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_asignado'));
		return $this->find('all',compact('conditions'));
	}

	public function consult_information_flujos_asignados_all_rango_fechas($fecha_inicio,$fecha_fin){
		$this->recursive 	= -1;
		$conditions			= array('DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin,'ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_asignado'));
		return $this->find('all',compact('conditions'));
	}

	public function count_all_flujos(){
		$conditions 		= array('ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0);
		return $this->find('count',compact('conditions'));
	}

	public function count_me_flujos(){
		$conditions 		= array('ProspectiveUser.user_id' => AuthComponent::user('id'),'ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0);
		return $this->find('count',compact('conditions'));
	}

	public function count_flujos_proceso(){
		$conditions		= array('ProspectiveUser.state_flow >' => Configure::read('variables.control_flujo.flujo_asignado'),'ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0);
		return $this->find('count',compact('conditions'));
	}

	public function flujos_proceso(){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow >' => Configure::read('variables.control_flujo.flujo_asignado'),'ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0);
		return $this->find('all',compact('conditions'));
	}

	public function count_flujos_cancelados(){
		$conditions		= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0);
		return $this->find('count',compact('conditions'));
	}

	public function flujos_cancelados(){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0);
		return $this->find('all',compact('conditions'));
	}

	public function flujos_terminados(){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_finalizado'),'ProspectiveUser.type' => 0);
		return $this->find('all',compact('conditions'));
	}

	public function count_flujos_terminados(){
		$conditions		= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_finalizado'),'ProspectiveUser.type' => 0);
		return $this->find('count',compact('conditions'));
	}

	public function list_prodpective_id($ids_prospectives){
		$this->recursive 	= -1;
		$fields 			= array('ProspectiveUser.user_id','ProspectiveUser.state_flow','ProspectiveUser.created','ProspectiveUser.id');
		$conditions 		= array('ProspectiveUser.id' => $ids_prospectives);
		return $this->find('all',compact('conditions','fields'));
	}

	public function count_flow_asignado($conditionsC = array()){
		$conditions1		= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_asignado'),'ProspectiveUser.type' => 0);
		$conditions 		= array_merge($conditionsC, $conditions1);
		return $this->find('count',compact('conditions'));
	}

	public function count_flow_contactado($conditionsC = array()){
		$conditions1		= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_contactado'));
		$conditions 		= array_merge($conditionsC, $conditions1);
		return $this->find('count',compact('conditions'));
	}

	public function count_flow_cotizado($conditionsC = array()){
		$conditions1		= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_cotizado'));
		$conditions 		= array_merge($conditionsC, $conditions1);
		return $this->find('count',compact('conditions'));
	}

	public function count_flow_negociado($conditionsC = array()){
		$conditions1		= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_negociado'));
		$conditions 		= array_merge($conditionsC, $conditions1);
		return $this->find('count',compact('conditions'));
	}

	public function count_flow_pagado($conditionsC = array()){
		$conditions["DATE(ProspectiveUser.created) >="] = '2022-07-01';
		$conditions1		= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_pagado'));
		$conditions 		= array_merge($conditionsC, $conditions1);
		return $this->find('count',compact('conditions'));
	}

	public function count_flow_despachado($conditionsC = array()){
		$conditions1		= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_despachado'));
		$conditions 		= array_merge($conditionsC, $conditions1);
		return $this->find('count',compact('conditions'));
	}

	public function count_flow_cancelado($conditionsC = array()){
		$conditions1		= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_cancelado'));
		$conditions 		= array_merge($conditionsC, $conditions1);
		return $this->find('count',compact('conditions'));
	}

	public function count_flow_terminado($conditionsC = array()){
		$conditions1		= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_finalizado'),'ProspectiveUser.type' => 0);
		$conditions 		= array_merge($conditionsC, $conditions1);
		return $this->find('count',compact('conditions'));
	}

	public function count_user_origen_date($user_id,$origen,$date_ini,$date_fin){
		$conditions 		= array('ProspectiveUser.origin' => $origen,'DATE(ProspectiveUser.created) >=' => $date_ini, 'DATE(ProspectiveUser.created) <=' => $date_fin, 'ProspectiveUser.user_id' => $user_id);
		return $this->find('count',compact('conditions'));
	}

	public function count_origen_chat(){
		$conditions 		= array('ProspectiveUser.origin' => Configure::read('variables.origenContact.Chat'),'DATE(ProspectiveUser.created)' => date("Y-m-d"));
		return $this->find('count',compact('conditions'));
	}

	public function count_origen_whatsapp(){
		$conditions 		= array('ProspectiveUser.origin' => Configure::read('variables.origenContact.Whatsapp'),'DATE(ProspectiveUser.created)' => date("Y-m-d"));
		return $this->find('count',compact('conditions'));
	}

	public function count_origen_email(){
		$conditions 		= array('ProspectiveUser.origin' => Configure::read('variables.origenContact.Email'),'DATE(ProspectiveUser.created)' => date("Y-m-d"));
		return $this->find('count',compact('conditions'));
	}

	public function count_origen_llamada(){
		$conditions 		= array('ProspectiveUser.origin' => Configure::read('variables.origenContact.Llamada'),'DATE(ProspectiveUser.created)' => date("Y-m-d"));
		return $this->find('count',compact('conditions'));
	}

	public function count_origen_redes(){
		$conditions 		= array('ProspectiveUser.origin' => Configure::read('variables.origenContact.Redes sociales'),'DATE(ProspectiveUser.created)' => date("Y-m-d"));
		return $this->find('count',compact('conditions'));
	}

	public function count_origen_presencial(){
		$conditions 		= array('ProspectiveUser.origin' => Configure::read('variables.origenContact.Presencial'),'DATE(ProspectiveUser.created)' => date("Y-m-d"));
		return $this->find('count',compact('conditions'));
	}

	public function count_origen_referido(){
		$conditions 		= array('ProspectiveUser.origin' => Configure::read('variables.origenContact.Referido'),'DATE(ProspectiveUser.created)' => date("Y-m-d"));
		return $this->find('count',compact('conditions'));
	}

	public function count_origen($origen,$data = null){
		if (is_null($data)) {
			$conditions 		= array('ProspectiveUser.origin' => $origen,'DATE(ProspectiveUser.created)' => date("Y-m-d"));
		}else{
			$conditions 		= array('ProspectiveUser.origin' => $origen,'DATE(ProspectiveUser.created) >=' =>  $data["ini"], 'DATE(ProspectiveUser.created) <=' =>  $data["end"] );
		}
		if (!is_null($data) && isset($data["user_id"])) {
			$conditions["ProspectiveUser.user_id"] = $data["user_id"];
		}
		return $this->find('count',compact('conditions'));
	}

	public function count_flujos_clients_natural($clients_natural_id){
		$conditions 		= array('ProspectiveUser.clients_natural_id' => $clients_natural_id);
		return $this->find('count',compact('conditions'));
	}

	public function flujos_clients_natural($clients_natural_id){
		$this->recursive 	= -1;
		$order				= array('ProspectiveUser.id' => 'desc');
		$conditions 		= array('ProspectiveUser.clients_natural_id' => $clients_natural_id);
		return $this->find('all',compact('conditions','order'));
	}

	public function count_flujos_clients_juridico($contacs_business){
		$conditions 		= array('ProspectiveUser.contacs_users_id' => $contacs_business);
		return $this->find('count',compact('conditions'));
	}

	public function flujos_clients_juridico($contacs_business){
		$this->recursive 	= -1;
		$order				= array('ProspectiveUser.id' => 'desc');
		$conditions 		= array('ProspectiveUser.contacs_users_id' => $contacs_business, 'ProspectiveUser.created >=' => '2022-01-01' );
		return $this->find('all',compact('conditions','order'));
	}

	public function time_management_flujos_attended(){
		$this->recursive 	= -1;
		$fechaMesAnterior 	= date('Y-m-d', strtotime('-1 month'));
		$conditions 		= array('ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_negociado'),'ProspectiveUser.modified <=' => $fechaMesAnterior,'ProspectiveUser.type' => 0);
		return $this->find('all',compact('conditions'));
	}





	// REPORT USUARIOS
	public function all_prospetives_group_user($date_inicio,$date_fin){
		$this->recursive 	= -1;
		$group 				= array('ProspectiveUser.user_id');
		$conditions 		= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin,);
		return $this->find('all',compact('conditions','group'));
	}



	// REPORT DATE FLUJOS
	public function count_user_prospectives_week($fecha_inicio,$user_id){
		$conditions			= array('ProspectiveUser.created' => $fecha_inicio,'ProspectiveUser.user_id' => $user_id);
		return $this->find('count',compact('conditions'));
	}



	// REPORTE ADVISER
	public function count_prospetives_range_date($date_inicio,$date_fin){
		$this->recursive 	= -1;
		$conditions 		= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin);
		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}
		return $this->find('count',compact('conditions'));
	}

	public function all_prospetives_range_date($date_inicio,$date_fin, $users = [], $origin = []){
		$this->recursive 	= -1;
		$conditions 		= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin );

		if (!empty($users)) {
			$conditions["ProspectiveUser.user_id"] = $users;
		}

		if (!empty($origin)) {
			$conditions["ProspectiveUser.origin"] = $origin;
		}

		return $this->find('all',compact('conditions'));
	}

	public function all_prospetives_landings($date_inicio,$date_fin){
		$this->recursive 	= -1;
		$conditions 		= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin,'ProspectiveUser.type' => 0, "ProspectiveUser.origin" => "landing" );
		return $this->find('all',compact('conditions'));
	}

	public function landing_prospective_count($date_inicio,$date_fin){
		$this->recursive 	= -1;		
		$conditions 		= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin, "ProspectiveUser.origin" => "landing" );
		return $this->find('count',compact('conditions'));
	}

	public function landing_prospective_total($date_inicio,$date_fin,$users = []){
		$this->recursive 	= -1;
		$fields 			= array("count(ProspectiveUser.id) total","ProspectiveUser.page");
		$group  			= array("ProspectiveUser.page");
		$conditions 		= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin , "ProspectiveUser.page != " => null );

		if (!empty($users)) {
			$conditions["ProspectiveUser.user_id"] = $users;
		}

		return $this->find('all',compact('conditions','fields','group'));
	}

	public function all_prospetives_range_date_group_cliente_natural($date_inicio,$date_fin){
		$fields 				= array('ProspectiveUser.clients_natural_id','ProspectiveUser.origin','ProspectiveUser.id','ProspectiveUser.user_id','ProspectiveUser.created','ClientsNatural.created');
		$conditions 		 	= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin,'ProspectiveUser.type' => 0,'ProspectiveUser.contacs_users_id' => '0','ProspectiveUser.state_flow !=' => Configure::read('variables.control_flujo.flujo_no_valido'),'ClientsNatural.created >=' => $date_inicio,'ClientsNatural.created <=' => $date_fin);
		$group 		 			= 'ProspectiveUser.clients_natural_id';
		return $this->find('all',compact('conditions','group','fields'));
	}

	public function list_ids_range_date_group_cliente_natural($date_inicio,$date_fin){
		$fields 				= array('ProspectiveUser.id');
		$conditions 		 	= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin,'ProspectiveUser.type' => 0,'ProspectiveUser.contacs_users_id' => '0','ProspectiveUser.state_flow !=' => Configure::read('variables.control_flujo.flujo_no_valido'),'ClientsNatural.created >=' => $date_inicio,'ClientsNatural.created <=' => $date_fin);
		$group 		 			= 'ProspectiveUser.clients_natural_id';
		$datos 					= $this->find('all',compact('conditions','group','fields'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.ProspectiveUser');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['id']] = $value['id'];
			}
		} else {
			$resultFinal 		= array();
		}
		return $resultFinal;
	}

	public function all_prospetives_range_date_group_cliente_juridico($date_inicio,$date_fin){
		$fields 				= array('ProspectiveUser.contacs_users_id','ProspectiveUser.origin','ProspectiveUser.id','ProspectiveUser.user_id','ProspectiveUser.created','ContacsUser.created');
		$conditions 		 	= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin,'ProspectiveUser.type' => 0,'ProspectiveUser.clients_natural_id' => '0','ProspectiveUser.state_flow !=' => Configure::read('variables.control_flujo.flujo_no_valido'),'ContacsUser.created >=' => $date_inicio,'ContacsUser.created <=' => $date_fin);
		$group 			 		= 'ProspectiveUser.contacs_users_id';
		return $this->find('all',compact('conditions','group','fields'));
	}

	public function lits_ids_range_date_group_cliente_juridico($date_inicio,$date_fin){
		$fields 				= array('ProspectiveUser.contacs_users_id','ProspectiveUser.origin','ProspectiveUser.id','ProspectiveUser.user_id','ProspectiveUser.created','ContacsUser.created');
		$conditions 		 	= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin,'ProspectiveUser.type' => 0,'ProspectiveUser.clients_natural_id' => '0','ProspectiveUser.state_flow !=' => Configure::read('variables.control_flujo.flujo_no_valido'),'ContacsUser.created >=' => $date_inicio,'ContacsUser.created <=' => $date_fin);
		$group 			 		= 'ProspectiveUser.contacs_users_id';
		$datos 					= $this->find('all',compact('conditions','group','fields'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.ProspectiveUser');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['id']] = $value['id'];
			}
		} else {
			$resultFinal 		= array();
		}
		return $resultFinal;
	}

	public function ids_prospetives_range_date($date_inicio,$date_fin){
		$this->recursive 	= -1;
		$fields 			= array('ProspectiveUser.id');
		$conditions 		= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin);
		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}
		return $this->find('all',compact('conditions','fields'));
	}

	public function count_flujos_state_asignado($date_inicio,$date_fin){
		$this->recursive 	= -1;
		$conditions 		= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin,'ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_asignado'));
		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}
		return $this->find('count',compact('conditions'));
	}

	public function all_flujos_state_asignado($date_inicio,$date_fin){
		$this->recursive 	= -1;
		$conditions 		= array('DATE(ProspectiveUser.created) >=' => $date_inicio,'DATE(ProspectiveUser.created) <=' => $date_fin,'ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_asignado'),'ProspectiveUser.type' => 0);
		return $this->find('all',compact('conditions'));
	}

	public function count_flujos_proceso_rango_fechas($fecha_inicio,$fecha_fin){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow >' => Configure::read('variables.control_flujo.flujo_asignado'),'ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin);
		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}
		return $this->find('count',compact('conditions'));
	}

	public function all_flujos_proceso_rango_fechas($fecha_inicio,$fecha_fin){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow >' => Configure::read('variables.control_flujo.flujo_asignado'),'ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin,'ProspectiveUser.type' => 0);
		return $this->find('all',compact('conditions'));
	}

	public function ids_flujos_proceso_rango_fechas($fecha_inicio,$fecha_fin){
		$this->recursive 	= -1;
		$fields 			= array('ProspectiveUser.id');
		$conditions			= array('ProspectiveUser.state_flow >' => Configure::read('variables.control_flujo.flujo_asignado'),'ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin);
		return $this->find('all',compact('conditions','fields'));
	}

	public function count_flujos_no_validos_rango_fechas($fecha_inicio,$fecha_fin,$users = [], $origin = []){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow' => [7,9],'DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin);

		if (!empty($users)) {
			$conditions["ProspectiveUser.user_id"] = $users;
		}

		if (!empty($origin)) {
			$conditions["ProspectiveUser.origin"] = $origin;
		}

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}

		return $this->find('count',compact('conditions'));
	}

	public function all_flujos_no_validos_rango_fechas($fecha_inicio,$fecha_fin){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_no_valido'),'DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin,'ProspectiveUser.type' => 0);
		return $this->find('all',compact('conditions'));
	}

	public function count_flujos_validos_rango_fechas($fecha_inicio,$fecha_fin,$users = [], $origin = []){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow !=' => [7,9],'DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin);

		if (!empty($users)) {
			$conditions["ProspectiveUser.user_id"] = $users;
		}

		if (!empty($origin)) {
			$conditions["ProspectiveUser.origin"] = $origin;
		}

		return $this->find('count',compact('conditions'));
	}

	public function count_flujos_cancelados_rango_fechas($fecha_inicio,$fecha_fin){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_cancelado'),'DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin);
		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}
		return $this->find('count',compact('conditions'));
	}

	public function all_flujos_cancelados_rango_fechas($fecha_inicio,$fecha_fin){
		$this->recursive 	= -1;
		$conditions			= array('DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin,'ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0);
		return $this->find('all',compact('conditions'));
	}

	public function count_flujos_terminados_rango_fechas($fecha_inicio,$fecha_fin){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_finalizado'),'DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin);
		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}
		return $this->find('count',compact('conditions'));
	}

	public function all_flujos_terminados_rango_fechas($fecha_inicio,$fecha_fin){
		$this->recursive 	= -1;
		$conditions			= array('DATE(ProspectiveUser.created) >=' => $fecha_inicio, 'DATE(ProspectiveUser.created) <=' => $fecha_fin,'ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_finalizado'),'ProspectiveUser.type' => 0);
		return $this->find('all',compact('conditions'));
	}

	public function all_data_flujos_ids($ids){
		$this->recursive 	= -1;
		$conditions			= array('ProspectiveUser.id' => $ids);
		return $this->find('all',compact('conditions'));
	}


	public function getNoSend($conditions,$user_id = null){

		$finalIds = array(0);
		unset($conditions["ProspectiveUser.state_flow <"]);
		$conditions["ProspectiveUser.state_flow"] = 5;
		$conditions["ProspectiveUser.quotation_id !="] = null;
		$conditions["DATE(ProspectiveUser.created) >="] = '2022-07-01';
		$prospectiveIds = $this->find("all",["fields"=>["ProspectiveUser.id",'quotation_id'],"recursive"=>-1,"conditions"=>$conditions]);

	

		if(!empty($prospectiveIds)){
			$prospectiveIds = Set::extract($prospectiveIds,"{n}.ProspectiveUser.quotation_id");

			$quotationsNoGest = $this->FlowStage->Quotation->QuotationsProduct->find("all",[
				"conditions" => ["QuotationsProduct.quotation_id" => $prospectiveIds, "QuotationsProduct.state" => 0 ],
				"fields" 	 => ["QuotationsProduct.quotation_id", "Quotation.prospective_users_id"],
				"group"		 => ["QuotationsProduct.quotation_id"]
			]);


			if(empty($quotationsNoGest)){
				$finalIds = [0];
			}else{
				$finalIds = Set::extract($quotationsNoGest,"{n}.Quotation.prospective_users_id");
			}
		}
		return $finalIds;
	}

	public function withImport($conditions,$user_id = null){

		$finalIds = array(0);
		unset($conditions["ProspectiveUser.state_flow <"]);
		$conditions["ProspectiveUser.state_flow"] = 5;
		$conditions["DATE(ProspectiveUser.created) >="] = '2022-06-01';
		$prospectiveIds = $this->Imports->find("list",["fields"=>["Imports.prospective_user_id",'Imports.prospective_user_id']]);

		$conditions["ProspectiveUser.id"] = empty($prospectiveIds) ? 0 : $prospectiveIds ;
		$prospectiveIds = $this->find("list",["fields"=>["ProspectiveUser.id",'ProspectiveUser.id'],"conditions"=>$conditions]);

		return empty($prospectiveIds) ? [0] : $prospectiveIds;
	}

	public function withoutShipping($conditions,$user_id = null){

		$finalIds = array(0);
		unset($conditions["ProspectiveUser.state_flow <"]);
		$conditions["ProspectiveUser.state_flow"] = 5;
		$conditions["DATE(ProspectiveUser.created) >="] = '2023-02-01';
		App::import("model","Order");
		$Order = new Order();

		$withShipping = $Order->Shipping->find("all",["fields"=>["Order.prospective_user_id"], "recursive" => 1 ]);

		try {
			if (!empty($withShipping)) {
				$withShipping = Set::extract($withShipping,"{n}.Order.prospective_user_id");
				$conditions["ProspectiveUser.id !="] = $withShipping;
			}
			$prospectiveIds = $this->find("list",["fields"=>["ProspectiveUser.id",'ProspectiveUser.id'],"conditions"=>$conditions]);
		} catch (Exception $e) {
			$prospectiveIds = [];
		}

		return empty($prospectiveIds) ? [0] : $prospectiveIds;
	}

	public function middleShipping($conditions,$user_id = null){

		$finalIds = array(0);
		unset($conditions["ProspectiveUser.state_flow <"]);
		$conditions["ProspectiveUser.state_flow"] = 5;
		$conditions["DATE(ProspectiveUser.created) >="] = '2023-02-01';
		App::import("model","Order");
		$Order = new Order();

		try {
			$withShipping = $Order->Shipping->find("all",[ "recursive" => 1 ,"fields"=>["Order.prospective_user_id"], "conditions" => ["Shipping.request_type" => 0, "Shipping.request_envoice" => 0 ] ]);
 
			if (!empty($withShipping)) {
				$withShipping = Set::extract($withShipping,"{n}.Order.prospective_user_id");
				$conditions["ProspectiveUser.id"] = $withShipping;
			}
			$prospectiveIds = $this->find("list",["fields"=>["ProspectiveUser.id",'ProspectiveUser.id'],"conditions"=>$conditions]);
		} catch (Exception $e) {
			$prospectiveIds = [];
		}

		return empty($prospectiveIds) ? [0] : $prospectiveIds;
	}



}
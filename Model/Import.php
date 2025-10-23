<?php
App::uses('AppModel', 'Model');

class Import extends AppModel {

	public $virtualFields = array(
	    'total_price' => 'SELECT ROUND(SUM(price)) FROM import_products where import_id = Import.id'
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Brand' => array(
			'className' => 'Brand',
			'foreignKey' => 'brand_id'
		)
	);

	public $hasMany = array(
		'ImportProduct' => array(
			'className' => 'ImportProduct',
			'foreignKey' => 'import_id',
			'dependent' => false
		),
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'import_id',
			'dependent' => false
		),
		
	);

	public $hasOne = array(
		'ImportRequest' => array(
			'className' => 'ImportRequest',
			'foreignKey' => 'import_id',
			'dependent' => false
		)
	);

	public $hasAndBelongsToMany = array(
		'Product' => array(
			'className' => 'Product',
			'joinTable' => 'import_products',
			'foreignKey' => 'import_id',
			'associationForeignKey' => 'product_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Flujos' => array(
			'className' => 'ProspectiveUser',
			'joinTable' => 'imports_prospective_users',
			'foreignKey' => 'import_id',
			'associationForeignKey' => 'prospective_user_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),	
		
	);


	public function get_data($id){
		// $this->recursive 	= -1;
		$conditions			= array('Import.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function all_import(){
		return $this->find('all');
	}

	public function count_importaciones(){
		$this->recursive 		= -1;
		$importaciones 			= $this->find('all');
		return count($importaciones);
	}

	public function count_importaciones_in_process_finish(){
		$this->recursive 		= -1;
		$conditions 			= array('Import.state <' => Configure::read('variables.importaciones.solicitud'));
		$importaciones 			= $this->find('all',compact('conditions'));
		return count($importaciones);
	}

	public function ids_importaciones_solicitudes($state){
		$this->recursive 		= -1;
		$fields 				= array('Import.id');
		$conditions 			= array('Import.description !=' => '','Import.state' => $state);
		$datos					= $this->find('all',compact('conditions','fields'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.Import');
		} else {
			$result 			= array();
		}
		$resultFinal 			= array();
		foreach ($result as $value) {
			$resultFinal[] = $value['id'];
		}
		return $resultFinal;
	}

	public function importaciones_proceso_cotizacion(){
		$order					= array('Import.id' => 'desc');
		// $ids 					= $this->ids_importaciones_solicitudes(Configure::read('variables.importaciones.proceso'));
		$ids 					= array();
		$conditions 			= array('Import.state' => Configure::read('variables.importaciones.proceso'),'Import.id !=' => $ids);
		return $this->find('all',compact('conditions','order'));
	}

	public function importaciones_proceso_solicitudes(){
		$order					= array('Import.id' => 'desc');
		$conditions 			= array('Import.description !=' => '','Import.state' => Configure::read('variables.importaciones.proceso'));
		return $this->find('all',compact('conditions','order'));
	}

	public function importaciones_finalizado_cotizacion(){
		$order					= array('Import.id' => 'desc');
		$ids 					= $this->ids_importaciones_solicitudes(Configure::read('variables.importaciones.finalizadas'));
		$conditions 			= array('Import.state' => Configure::read('variables.importaciones.finalizadas'),'Import.id !=' => $ids);
		return $this->find('all',compact('conditions','order'));
	}

	public function importaciones_finalizado_solicitudes(){
		$order					= array('Import.id' => 'desc');
		$conditions 			= array('Import.description !=' => '','Import.state' => Configure::read('variables.importaciones.finalizadas'));
		return $this->find('all',compact('conditions','order'));
	}

	public function update_state_finish($import_id){
		$this->updateAll(
	    	array('Import.state' => Configure::read('variables.importaciones.finalizadas')), array('Import.id' => $import_id)
	    );
	}

	public function count_importaciones_revisiones($internacional = 0){
		$conditions 				= array(
            "OR" => array(
                array("Import.send_provider" => 0, "Import.state !=" => array(2,4),  ),
                    'Import.state' => Configure::read('variables.importaciones.solicitud')
            ),
            "Import.internacional" => $internacional
        );
		return $this->find('count',compact('conditions'));
	}

	public function count_imports_approved($internacional = 0){
		$conditions 			= array('Import.description !=' => '',
									'Import.state' => 1, "Import.send_provider" => 1, "Import.internacional" => $internacional
								);
		return $this->find('count',compact('conditions'));
	}

	public function count_imports_rejected($internacional = 0){
    	$conditions 			= array('Import.state' => Configure::read('variables.importaciones.rechazado'), "Import.internacional" => $internacional);
		return $this->find('count',compact('conditions'));
	}

	public function update_state_approved($import_id){
		$this->updateAll(
	    	array('Import.state' => Configure::read('variables.importaciones.proceso')), array('Import.id' => $import_id)
	    );
	}

	public function update_state_rejectec($import_id){
		$this->updateAll(
	    	array('Import.state' => Configure::read('variables.importaciones.rechazado')), array('Import.id' => $import_id)
	    );
	}

	public function generate($i = 1){
		$this->recursive = -1;
		$code 	= $this->field("MAX(purchase_order)+${i}");
		return $code;
		$exists = $this->findByPurchaseOrder($code);
		if(!empty($exists)){
			return $this->generate($i+1);
		}else{
			return $code;
		}

	}

}
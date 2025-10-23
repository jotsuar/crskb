<?php
App::uses('AppModel', 'Model');

class ImportProduct extends AppModel {

	public $belongsTo = array(
		'Import' => array(
			'className' => 'Import',
			'foreignKey' => 'import_id'
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id'
		)
	);

	public $hasMany = array(
		'News' => array(
			'className' => 'News',
			'foreignKey' => 'import_products_id',
			'dependent' => false
		),
	);

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('ImportProduct.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function get_id_quations_product($quotations_products_id){
		$this->recursive 	= -1;
		$fields				= array('ImportProduct.id');
		$conditions			= array('ImportProduct.quotations_products_id' => $quotations_products_id);
		return $this->find('first',compact('conditions','fields'));
	}

	public function get_quotations_products_id($import_id){
		$this->recursive 	= -1;
		$fields				= array('ImportProduct.quotations_products_id');
		$conditions			= array('ImportProduct.import_id' => $import_id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['ImportProduct']['quotations_products_id'];
	}

	public function all_imports_products(){
		$this->recursive 	= -1;
		return $this->find('all');
	}

	public function products_import($import_id){
		$conditions 			= array('ImportProduct.import_id' => $import_id);
		return $this->find('all',compact('conditions'));
	}

	public function products_imports($import_id){
		$this->recursive 	= -1;
		$fields				= array('ImportProduct.product_id','ImportProduct.product_id');
		$conditions 		= array('ImportProduct.import_id' => $import_id);
		return $this->find('list',compact('conditions','fields'));
	}

	public function count_products_import_solicitud($import_id){
		$conditions 		= array('ImportProduct.state_import' => Configure::read('variables.control_importacion.solicitud_importacion'),'ImportProduct.import_id' => $import_id);
		return $this->find('count',compact('conditions'));
	}

	public function count_products_finish_cotizacion($import_id){
		$conditions 		= array('ImportProduct.state_import !=' => '7','ImportProduct.import_id' => $import_id);
		return $this->find('count',compact('conditions'));
	}

}
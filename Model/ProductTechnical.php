<?php
App::uses('AppModel', 'Model');

class ProductTechnical extends AppModel {

	public $belongsTo = array(
		'TechnicalService' => array(
			'className' => 'TechnicalService',
			'foreignKey' => 'technical_services_id'
		)
	);

	public $hasMany = array(
		'Accessory' => array(
			'className' => 'Accessory',
			'foreignKey' => 'product_technicals_id',
			'dependent' => false
		)
	);

	public function get_all($service_id){
		$this->recursive = -1;
		$conditions = array('ProductTechnical.technical_services_id' => $service_id);
		return $this->find('all',compact('conditions'));
	}

	public function count_product_technical($service_id){
		$conditions = array('ProductTechnical.technical_services_id' => $service_id);
		return $this->find('count',compact('conditions'));
	}

	public function get_img1($service_id){
		$this->recursive 	= -1;
		$fields 			= array('ProductTechnical.image1');
		$conditions			= array('ProductTechnical.technical_services_id' => $service_id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['ProductTechnical']['image1'];
	}

	public function get_img2($service_id){
		$this->recursive 	= -1;
		$fields 			= array('ProductTechnical.image2');
		$conditions			= array('ProductTechnical.technical_services_id' => $service_id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['ProductTechnical']['image2'];
	}

	public function get_img3($service_id){
		$this->recursive 	= -1;
		$fields 			= array('ProductTechnical.image3');
		$conditions			= array('ProductTechnical.technical_services_id' => $service_id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['ProductTechnical']['image3'];
	}

	public function get_img4($service_id){
		$this->recursive 	= -1;
		$fields 			= array('ProductTechnical.image4');
		$conditions			= array('ProductTechnical.technical_services_id' => $service_id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['ProductTechnical']['image4'];
	}

	public function get_img5($service_id){
		$this->recursive 	= -1;
		$fields 			= array('ProductTechnical.image5');
		$conditions			= array('ProductTechnical.technical_services_id' => $service_id);
		$datos 				= $this->find('first',compact('conditions','fields'));
		return $datos['ProductTechnical']['image5'];
	}
}
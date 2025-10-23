<?php
App::uses('AppModel', 'Model');

class Accessory extends AppModel {

	public $belongsTo = array(
		'ProductTechnical' => array(
			'className' => 'ProductTechnical',
			'foreignKey' => 'product_technicals_id'
		)
	);

	public function find_accesories_machine($id_service){
		$this->recursive 	= -1;
		$conditions			= array('Accessory.product_technicals_id' => $id_service);
		$datos 				= $this->find('all',compact('conditions'));
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.Accessory');
			$resultFinal		= array();
			foreach ($result as $value) {
				$resultFinal[$value['accesorio']] = $value['accesorio'];
			}
		} else {
			$resultFinal 		= array();
		}
		return $resultFinal;
	}

}

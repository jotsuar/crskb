<?php
App::uses('AppModel', 'Model');

class FlowStagesProduct extends AppModel {

	public $belongsTo = array(
		'FlowStage' => array(
			'className' => 'FlowStage',
			'foreignKey' => 'flow_stage_id',
			'dependent' => false
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'dependent' => false
		),
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_users_id',
			'dependent' => false
		),
		'DraftInformation' => array(
			'className' => 'DraftInformation',
			'foreignKey' => 'draft_information_id',
			'dependent' => false
		)
	);

	public function get_data_borrador($flow_stage_id){
		$this->recursive 	= 2;
		$conditions			= array('FlowStagesProduct.flow_stage_id' => $flow_stage_id, "Product.deleted" => 0);
		return $this->find('all',compact('conditions','fields'));
	}

	public function get_data_borrador_list($flow_stage_id){
		$this->recursive 	= 2;
		$fields				= array('FlowStagesProduct.product_id','FlowStagesProduct.product_id');
		$conditions			= array('FlowStagesProduct.flow_stage_id' => $flow_stage_id);
		return $this->find('list',compact('conditions','fields'));
	}

	//Borrador en curso
	public function count_row_etapa($flujo_id){
		$conditions			= array('FlowStagesProduct.prospective_users_id' => $flujo_id);
		return $this->find('count',compact('conditions','fields'));
	}
}

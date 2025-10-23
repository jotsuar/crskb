<?php
App::uses('AppModel', 'Model');

class TemplatesProduct extends AppModel {

	public $belongsTo = array(
		'Template' => array(
			'className' => 'Template',
			'foreignKey' => 'template_id'
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id'
		)
	);

	public function get_data_template($template_id){
		$this->recursive 	= 2;
		$conditions			= array('TemplatesProduct.template_id' => $template_id);
		return $this->find('all',compact('conditions','fields'));
	}

	public function get_data_template_list($template_id){
		$this->recursive 	= -1;
		$fields				= array('TemplatesProduct.product_id','TemplatesProduct.product_id');
		$conditions			= array('TemplatesProduct.template_id' => $template_id);
		return $this->find('list',compact('conditions','fields'));
	}
}

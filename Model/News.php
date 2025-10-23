<?php
App::uses('AppModel', 'Model');

class News extends AppModel {

	public $belongsTo = array(
		'ImportProduct' => array(
			'className' => 'ImportProduct',
			'foreignKey' => 'import_products_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function all_news_data($import_products_id){
		$this->recursive 	= -1;
		$conditions = array('News.import_products_id' => $import_products_id);
		return $this->find('all',compact('conditions'));
	}

	public function number_novedades($import_products_id){
		$conditions = array('News.import_products_id' => $import_products_id);
		return $this->find('count',compact('conditions'));
	}
}

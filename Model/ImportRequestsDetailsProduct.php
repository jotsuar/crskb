<?php
App::uses('AppModel', 'Model');
/**
 * ImportRequestsDetailsProduct Model
 *
 * @property ImportRequestsDetail $ImportRequestsDetail
 * @property Product $Product
 */
class ImportRequestsDetailsProduct extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'ImportRequestsDetail' => array(
			'className' => 'ImportRequestsDetail',
			'foreignKey' => 'import_requests_detail_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function saveDetailProduct($detailId, $products){
		foreach ($products as $key => $value) {
			$this->create();
			$this->save(array(
				"ImportRequestsDetailsProduct" => array(
					"import_requests_detail_id" => $detailId,
					"product_id"				=> $value["id_product"],
					"quantity"					=> $value["quantity"],
					"delivery"					=> isset($value["delivery"]) ? $value["delivery"] : null,
				)
			));
		}
	}

}

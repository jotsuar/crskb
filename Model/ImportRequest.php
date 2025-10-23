<?php
App::uses('AppModel', 'Model');
/**
 * ImportRequest Model
 *
 * @property Brand $Brand
 * @property Import $Import
 * @property User $User
 */
class ImportRequest extends AppModel {


	public $virtualFields = array(
	    'state_import' => '(SELECT state FROM imports where imports.id = ImportRequest.import_id)'
	);

	public $validate = array(
		'brand_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'state' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Brand' => array(
			'className' => 'Brand',
			'foreignKey' => 'brand_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Import' => array(
			'className' => 'Import',
			'foreignKey' => 'import_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		'ImportRequestsDetail' => array(
			'className' => 'ImportRequestsDetail',
			'foreignKey' => 'import_request_id',
			'dependent' => false,
			'conditions' => "ImportRequestsDetail.state in (1,2)",
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


	public function getOrSaveRequest($brand_id,$internacional = 0, $type = 1){
		$actualInfo = $this->findByBrandIdAndStateAndInternacionalAndType($brand_id,1,$internacional,$type);

		if(empty($actualInfo)){
			$this->create();
			$this->save(array("ImportRequest" => array("brand_id" => $brand_id,"internacional" => $internacional,"type" => $type)));
			return $this->id;
		}else{
			return $actualInfo["ImportRequest"]["id"];
		}
	}

	private function validateBrandsPPal($data){
		$others = 0;
		$arrOthers = [];
		$actualBrands = Set::extract($data,"{n}.Brand.id");
		foreach ($data as $key => $value) {
			if ($value["Brand"]["brand_id"] != 0 && in_array($value["Brand"]["brand_id"], $actualBrands)) {
				$others++;
				$arrOthers[$value["Brand"]["brand_id"]] = $key;
				$pos = array_search($value["Brand"]["id"], $actualBrands);
				unset($actualBrands[$pos]);
			}
		}

		if ($others > 0) {
			foreach ($data as $key => $value) {
				if (array_key_exists($value["Brand"]["id"], $arrOthers)) {
					
					$data[$key]["ImportRequestsDetail"] = array_merge($data[$key]["ImportRequestsDetail"], $data[ $arrOthers[$value["Brand"]["id"]] ]["ImportRequestsDetail"] );
					unset($data[ $arrOthers[$value["Brand"]["id"]] ]);

				}
			}
		}
		return $data;
	}

	public function getImportRequestBrands($internacional = null, $type = 1){

		$this->unBindModel(
			array("belongsTo" => array("User","Import"))
		);
		$intData = 0;
		if (!is_null($internacional)) {
			$intData = 1;
		}
		$data = $this->findAllByStateAndInternacionalAndType(1,$intData,$type);
		$data = $this->validateBrandsPPal($data);

		if(!empty($data)){
			foreach ($data as $keyRequest => $request) {
				$response = $this->ImportRequestsDetail->getDetailDataProducts($request["ImportRequestsDetail"]);
				if ($type == 2) {
					foreach ($request["ImportRequestsDetail"] as $keyValidator => $validator) {
						if ($validator["type_request"] != 4) {
							unset($request["ImportRequestsDetail"][$keyValidator]);
						}
					}
				}

				if (!is_null($response) && $response != "pendiente") {
					foreach ($response["details"] as $key => $value) {
						if (isset($value["InfoProducts"])) {
							foreach ($value["InfoProducts"]["Product"] as $keyProduct => $valueProduct) {
								// unset($valueProduct["ImportRequestsDetailsProduct"]);
								if ($valueProduct["normal"] == 2) {
									App::import('Model', 'Composition');
									$Composition = new Composition();
									$compositions = $Composition->findAllByPrincipal($valueProduct["id"]);
									if (!empty($compositions)) {
										$valueProduct["compositions"] = $compositions;
									}
								}
								$response["details"][$key]["InfoProducts"]["Product"][$keyProduct] = $valueProduct;
							}							
						}
					}
					foreach ($response["otherDetails"] as $key => $value) {

						foreach ($value as $keyOther => $valueOther) {
							if (!isset($valueOther["InfoProducts"])) {
								continue;
							}
							foreach ($valueOther["InfoProducts"]["Product"] as $keyProduct => $valueProduct) {
								// unset($valueProduct["ImportRequestsDetailsProduct"]);
								if ($valueProduct["normal"] == 2) {
									App::import('Model', 'Composition');
									$Composition = new Composition();
									$compositions = $Composition->findAllByPrincipal($valueProduct["id"]);
									if (!empty($compositions)) {
										$valueProduct["compositions"] = $compositions;
									}
								}
								$response["otherDetails"][$key][$keyOther]["InfoProducts"]["Product"][$keyProduct] = $valueProduct;
							}
						}
					}
				}

				$data[$keyRequest]["ImportRequestsDetail"] = $response;

				if ($data[$keyRequest]["ImportRequestsDetail"] == "pendiente") {
					unset($data[$keyRequest]);
				}elseif( is_null($data[$keyRequest]["ImportRequestsDetail"])){
					$dataRequest = $request["ImportRequest"];
					$dataRequest["state"] = 0;
					$this->save($dataRequest);
					unset($data[$keyRequest]);
				}
			}
		}
		return $data;
	}

	public function getImportRequestProducts($internacional = null, $type = 1){

		$this->unBindModel(
			array("belongsTo" => array("User","Import"))
		);
		$intData = 0;
		if (!is_null($internacional)) {
			$intData = 1;
		}
		$data = $this->findAllByStateAndInternacionalAndType(1,$intData,$type);
		$products = [];

		if(!empty($data)){
			foreach ($data as $keyRequest => $request) {
				$response = $this->ImportRequestsDetail->getDetailDataProducts($request["ImportRequestsDetail"]);
				if (!is_null($response) && $response != "pendiente") {
					foreach ($response["details"] as $key => $value) {
						if (isset($value["InfoProducts"])) {
							foreach ($value["InfoProducts"]["Product"] as $keyProduct => $valueProduct) {
								unset($valueProduct["ImportRequestsDetailsProduct"]);
								if ($valueProduct["normal"] == 2) {
									App::import('Model', 'Composition');
									$Composition = new Composition();
									$compositions = $Composition->findAllByPrincipal($valueProduct["id"]);
									if (!empty($compositions)) {
										$valueProduct["compositions"] = $compositions;
									}
								}
								$products[] = ["Product" => $valueProduct];
							}							
						}
					}
					foreach ($response["otherDetails"] as $key => $value) {

						foreach ($value as $keyOther => $valueOther) {
							if (!isset($valueOther["InfoProducts"])) {
								continue;
							}
							foreach ($valueOther["InfoProducts"]["Product"] as $keyProduct => $valueProduct) {
								unset($valueProduct["ImportRequestsDetailsProduct"]);
								if ($valueProduct["normal"] == 2) {
									App::import('Model', 'Composition');
									$Composition = new Composition();
									$compositions = $Composition->findAllByPrincipal($valueProduct["id"]);
									if (!empty($compositions)) {
										$valueProduct["compositions"] = $compositions;
									}
								}
								$products[] = ["Product" => $valueProduct];
							}
						}
					}
				}
			}
		}
		return $products;
	}



}

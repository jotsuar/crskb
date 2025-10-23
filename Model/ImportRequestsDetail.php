<?php
App::uses('AppModel', 'Model');
/**
 * ImportRequestsDetail Model
 *
 * @property ImportRequest $ImportRequest
 * @property User $User
 * @property ProspectiveUser $ProspectiveUser
 * @property Product $Product
 */
class ImportRequestsDetail extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ImportRequest' => array(
			'className' => 'ImportRequest',
			'foreignKey' => 'import_request_id',
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
		),
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Product' => array(
			'className' => 'Product',
			'joinTable' => 'import_requests_details_products',
			'foreignKey' => 'import_requests_detail_id',
			'associationForeignKey' => 'product_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

	public function saveDataDetail($import_request_id,$user_id,$type,$description, $prospective_user_id = null,$internacional = 0){
		$this->create();
		$dataCreate = array(
			"ImportRequestsDetail" => array(
				"import_request_id" => $import_request_id,
				"user_id"			=> $user_id,
				"type_request"		=> $type,
				"description"		=> $description,
				"prospective_user_id" => $prospective_user_id,
				"internacional" => $internacional,
				"deadline" => date("Y-m-d",strtotime("+20 day"))
			)
		);

		if ($type == 2) {
			if (AuthComponent::user("role") != "Gerente General" ) {
				if ($type == 2 && !in_array(AuthComponent::user("email"), ["logistica@almacendelpintor.com","logistica@kebco.co"]) ) {
					$dataCreate["ImportRequestsDetail"]["state"] = 2;
				}
			}			
		}

		$this->save($dataCreate);
		return $this->id;
	}

	public function getOthersDetails($flujoId, $ImportRequestId){
		$conditions = array(
			"prospective_user_id" 	=> $flujoId,
			"import_request_id"		=> $ImportRequestId,
			"ImportRequestsDetail.state" => 1
		);
		return $this->find("all",compact("conditions"));
	}

	public function getDetailDataProducts($details){

		$this->unBindModel(array("belongsTo" => array("ImportRequest")));

		$totalActive = 0;
		$totalInactive = 0;
		$totalSinAProbar = 0;

		foreach ($details as $keyDetail => $detailValue) {
			if($detailValue["state"] == 0){
				$totalInactive++;
				continue;
			}

			if($detailValue["state"] == 2){
				$totalSinAProbar++;
				continue;
			}

			$detail = $this->findById($detailValue["id"]);
			unset($detail["ImportRequestsDetail"]);
			if(!isset($detail["ProspectiveUser"]) || is_null($detail["ProspectiveUser"]["id"])){
				$detail["ProspectiveUser"] = array();
			}
			$detailValue["InfoProducts"] = $detail;
			$details[$keyDetail] = $detailValue;
			$totalActive++;
		}

		if($totalActive == 0 && $totalSinAProbar > 0){
			return "pendiente";
		}

		if($totalActive == 0){
			return null;
		}

		$otherDetails = array();

		foreach ($details as $key => $value) {
			$otherDetails[$value["type_request"]][] = $value;
		}

		return compact("details","otherDetails","totalSinAProbar");

	}

}

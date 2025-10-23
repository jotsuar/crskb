<?php
App::uses('AppModel', 'Model');
/**
 * Approve Model
 *
 * @property Flowstage $Flowstage
 * @property Quotation $Quotation
 */
class Approve extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'FlowStage' => array(
			'className' => 'FlowStage',
			'foreignKey' => 'flowstage_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Quotation' => array(
			'className' => 'Quotation',
			'foreignKey' => 'quotation_id',
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
			'foreignKey' => 'flujo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function getDataByUser($dayIni, $dayEnd, $state, $qt = null){
		$approves = $this->find("all",[
			"conditions" => [ "DATE(Approve.created) >=" => $dayIni, "DATE(Approve.created) <=" => $dayEnd, "Approve.state" => $state,  ],
		]);
		$qoutations = [];
		$datos = [];
		if (!empty($approves)) {
			if(!is_null($qt)){
				$quotationsId = Set::extract($approves,"{n}.Approve.quotation_id");
				return $this->Quotation->findAllById($quotationsId);
			}
			foreach ($approves as $key => $value) {
				if(!array_key_exists($value["User"]["id"], $datos)){
					$datos[$value["User"]["id"]] = 1;
				}else{
					$datos[$value["User"]["id"]]++;
				}
			}
		}

		return $datos;
	}

}

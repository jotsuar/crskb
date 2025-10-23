<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
/**
 * Campaigns Controller
 *
 * @property Campaign $Campaign
 * @property PaginatorComponent $Paginator
 */
class CampaignsController extends AppController {

	public $components = array('Paginator');

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('send_mails');
    }


    public function send_mails(){
    	$this->autoRender = false;
    	$this->Campaign->recursive = -1;

    	$campanas = $this->Campaign->findAllByStateAndType(1,2);



    	if(!empty($campanas)){
    		foreach ($campanas as $key => $value) {

    			$dias 		= $value["Campaign"]["deadline"];
    			$campana 	= $value["Campaign"]["id"];
    			$campaigData = $value;

    			$this->loadModel("Product");
				$this->Product->recursive = -1;

				$data = (array) json_decode($value["Campaign"]["products"]);

				$productos = $this->Product->findAllById(explode(",", $data["products"]));

				$productsIds = explode(",", $data["products"]);

				if(!empty($productos)){
					$ids = Set::extract($productos, "{n}.Product.id");
					$quotations	= $this->Product->QuotationsProduct->find_quotation_id($ids,$data["type"]);
					$pagados 			= $this->Product->FlowStagesProduct->FlowStage->ProspectiveUser->find("all",["conditions" => ["bill_code IS NOT NULL","ProspectiveUser.bill_date" => date("Y-m-d",strtotime("-".$dias." day")) ], "recursive" => -1,"fields" => ["ProspectiveUser.id" ], "order" => ["ProspectiveUser.bill_date" => "DESC"] ]);

					$arrPagados = array();

					if(!empty($pagados)){
						$arrPagados = Set::extract($pagados, "{n}.ProspectiveUser.id");
					}

					if(!empty($arrPagados)){

						$conditions		= array('Quotation.id' => array_values($quotations));
						$conditions["Quotation.prospective_users_id"] = $arrPagados;

						$this->loadModel('Quotation');
						$this->Quotation->unBindModel(["belongsTo" => ["FlowStage","User","Header"], "hasMany" => []]);
						$cotizaciones 		= $this->Quotation->find("all",compact("conditions"));
						$datosCotizaciones 	= array();

						if(!empty($cotizaciones)){
							foreach ($cotizaciones as $key => $valueCot) {
								$id_etapa_cotizado 		= $this->Product->FlowStagesProduct->FlowStage->id_latest_regystri_state_cotizado($valueCot["ProspectiveUser"]["id"]);
								$datos 					= $this->Product->FlowStagesProduct->FlowStage->get_data($id_etapa_cotizado);
								if($datos['FlowStage']['document'] == $valueCot["Quotation"]["id"]){
									$datosCotizaciones[$valueCot["ProspectiveUser"]["id"]] = $valueCot;
								}
							}
						}



						$cotizaciones = $datosCotizaciones;
						if(!empty($cotizaciones)){

							$this->loadModel("NewsletterReject");

							$emailsReject 	= $this->NewsletterReject->find("all",["fields" => ["email"]]);

							if(!empty($emailsReject)){
								$emailsReject = Set::extract($emailsReject,"{n}.NewsletterReject.email");
							}else{
								$emailsReject = array();
							}

							foreach ($cotizaciones as $key => $valueCotArr) {

								$email 		= $this->get_clientData($valueCotArr["ProspectiveUser"]["id"]);
								$name  		= $this->get_clientData($valueCotArr["ProspectiveUser"]["id"],"name");
								$producto 	= null;

								foreach ($valueCotArr["QuotationsProduct"] as $keyProd => $prod) {
									if(in_array($prod["product_id"], $productsIds)){
										$producto = $this->Product->field("name",["id"=>$prod["product_id"]]);
										break;
									}
								}

								if(!is_null($producto)){
									$bodyEmail = str_replace(["@EQUIIPO@","@NOMBRE@"], [$producto,$name], $campaigData["Campaign"]["content"]);

									if(!in_array($email, $emailsReject)){
										$existe = $this->Campaign->EmailTracking->findByEmailAndCampaignId($email, $campana);
										if(empty($existe)){
											$options = array(
												'to'		=> $email,
												'subject'	=> $campaigData["Campaign"]["subject"],
												'content'	=> $bodyEmail,
											);
											$this->sendMailSendGrid($options, $campana);
											$this->sendQoutationWhatsappTemplate($valueCotArr["ProspectiveUser"]["id"], $name, $producto );
										}
									}
								}
								
							}
						}
					}
				}
    		}
    	}


    }


    public function sendQoutationWhatsappTemplate($flowId, $name, $producto ){
		$phones = array();

		$datosCliente = []; 

		$datosCliente["telephone"] 	= $this->get_clientData($flowId,"telephone");
		$datosCliente["cell_phone"] = $this->get_clientData($flowId,"cell_phone");

		$datosCliente["telephone"] = str_replace([" ","+57","_"], "", $datosCliente["telephone"]);
		$datosCliente["cell_phone"] = str_replace([" ","+57","_"], "", $datosCliente["cell_phone"]);

		if(!empty($datosCliente["telephone"]) && strlen($datosCliente["telephone"]) == 10){
			$phones[] = $datosCliente["telephone"];
		}

		if(!empty($datosCliente["cell_phone"]) && strlen($datosCliente["cell_phone"]) == 10){
			$phones[] = $datosCliente["cell_phone"];
		}

		if (count($phones) == 2 && $phones[0] == $phones[1] ) {
			unset($phones[1]);
		}

		$whitelist = array(
            '127.0.0.1',
            '::1'
        );
        if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $phones = ['3136429175'];
        }

		if(!empty($phones)){
			foreach ($phones as $key => $phone) {
				$strMsg = '
					{
					   "messaging_product": "whatsapp",
					   "to": "57'.$phone.'",
					   "type": "template",
					   "template": {
					       "name": "invitacion_servicio",
					       "language": {
					           "code": "es",
					           "policy": "deterministic"
					       },
					       "components": [
					           {
					               "type": "body",
					               "parameters": [
					                   {
					                       "type": "text",
					                       "text": "'.$name.'"
					                   },
					                   {
					                       "type": "text",
					                       "text": "'.$producto.'"
					                   }
					               ]
					           }
					       ]
					   }
					}
				';
				try {

					$HttpSocket = new HttpSocket();

					$request = ["header" => [
						"Content-Type" => 'application/json',
						"Authorization" => 'Bearer '
					]];

					$responseToken = $HttpSocket->post('https://graph.facebook.com/v15.0/100586116213138/messages',$strMsg,$request);
					$responseToken = json_decode($responseToken->body);

				} catch (Exception $e) {
					$this->log($e->getMessage());
				}	
			}		

		}
	}

    public function get_clientData($prospective_id, $field = "email"){
    	$this->loadModel("ProspectiveUser");
		$id 				= null;
		$datos 				= $this->ProspectiveUser->get_data($prospective_id);
		if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
			$this->loadModel("ContacsUser");
			$datosC 		= $this->ContacsUser->get_data($datos['ProspectiveUser']['contacs_users_id']);
			return $datosC["ContacsUser"][$field];
		} else {
			$this->loadModel("ClientsNatural");
			$datosC 		= $this->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
			return $datosC["ClientsNatural"][$field];
		}
		return null;
	}

	public function index() {
		$this->Campaign->recursive = 1;
		$conditions = array();
		$order = array("created" => "DESC");
		$this->paginate 			= array(
										'order' 		=> $order,
							        	'limit' 		=> 10,
							        	'conditions' 	=> $conditions,
							    	);
		$campaigns = $this->Paginator->paginate();
		$this->set('campaigns', $campaigns);
	}

	
	public function view($id = null) {
		$id = $this->desencriptarCadena($id);
		if (!$this->Campaign->exists($id)) {
			throw new NotFoundException(__('Invalid campaign'));
		}
		$options 	= array('conditions' => array('Campaign.' . $this->Campaign->primaryKey => $id));
		$campaign 	= $this->Campaign->find('first', $options);
		$this->set('campaign', $campaign);

		$totalDelivered = $this->Campaign->EmailTracking->field("count(id) as total", ["EmailTracking.campaign_id" => $id, "EmailTracking.delivered IS NOT NULL"]);
		$totalRead 		= $this->Campaign->EmailTracking->field("count(id) as total", ["EmailTracking.campaign_id" => $id, "EmailTracking.read IS NOT NULL"]);
		$totalClick 	= $this->Campaign->EmailTracking->field("count(id) as total", ["EmailTracking.campaign_id" => $id, "EmailTracking.clicked IS NOT NULL"]);

		if($campaign["Campaign"]["type"] == 2){
			$this->loadModel("Product");
			$this->Product->recursive = -1;
			$productos = $this->Product->findAllById(explode(",", $campaign["Campaign"]["products"]));
			$this->set("productos",$productos);
		}

		$categoriesData = $this->getCategoryInfo(true);

		$this->set(compact("totalClick","totalDelivered","totalRead","categoriesData"));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Campaign->create();
			if ($this->Campaign->save($this->request->data)) {
				$this->Flash->success(__('The campaign has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The campaign could not be saved. Please, try again.'));
			}
		}
		
		$this->loadModel("Product");
		$categoriesInfoFinal = $this->getCagegoryData();

		$brands 			 = $this->Product->Brand->find("all");

		$this->set(compact("brands","categoriesInfoFinal"));

	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Campaign->exists($id)) {
			throw new NotFoundException(__('Invalid campaign'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Campaign->save($this->request->data)) {
				$this->Flash->success(__('The campaign has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The campaign could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Campaign.' . $this->Campaign->primaryKey => $id));
			$this->request->data = $this->Campaign->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Campaign->id = $id;
		if (!$this->Campaign->exists()) {
			throw new NotFoundException(__('Invalid campaign'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Campaign->delete()) {
			$this->Flash->success(__('The campaign has been deleted.'));
		} else {
			$this->Flash->error(__('The campaign could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

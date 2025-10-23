<?php
require_once APP.'Vendor'.DS.'autoload.php';
App::uses('AppController', 'Controller');
/**
 * Commitments Controller
 *
 * @property Commitment $Commitment
 * @property PaginatorComponent $Paginator
 */
class CommitmentsController extends AppController {


	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('notify_commitments_semana');
    }

	public $components = array('Paginator');

	public function notify_commitments_semana(){
		$this->autoRender = false;

        if (date("l") == "Wednesday" || date("l") == "Lunes" || date("l") == "Mon" || date("l") == "Lun" ) {
        	$iniDay = date('Y-m-d', strtotime("this week"));
       	 	$endDay = date('Y-m-d', strtotime("sunday 0 week"));

       	 	$commitments = $this->Commitment->find("all",["conditions" => [ "Commitment.state" => [1,0], "Commitment.deadline >=" =>$iniDay, "Commitment.deadline <=" => $endDay ]]);

       	 	if (!empty($commitments)) {
       	 		
       	 		$users 		 = [];
       	 		$infoByUser  = [];
       	 		foreach ($commitments as $key => $value) {
       	 			$infoByUser[$value["Commitment"]["user_id"]] = [];
       	 			$users[$value["Commitment"]["user_id"]] = $value["User"]["email"];
       	 		}
       	 		foreach ($commitments as $key => $value) {
       	 			$infoByUser[$value["Commitment"]["user_id"]][] = $value;
       	 		}

       	 		foreach ($infoByUser as $userId => $commitmentsUser) {
       	 			
       	 			$emails  		 = [$users[$userId]];
					$commitments 	 = $commitmentsUser;
					$type	  		 = "semana";
					$options 		 = array(

						'to'		=> $emails,
						'template'	=> 'commitments',
						'subject'	=> "Compromisos que vencen esta semana CRM ${iniDay} - ${endDay}",
						'vars'		=> compact("commitments",'type')

					);
					
					$this->sendMail($options);

       	 			try {

						$pushNotifications = new \Pusher\PushNotifications\PushNotifications(array(
						  "instanceId" => "c40fa57b-967c-42e6-8ee6-80084a1aef4c",
						  "secretKey" => "C81C2CA939B40F50D6E38DAB0E4B472D28DD3AF079C097F189B951F50B8D9E66",
						));

						$publishResponse = $pushNotifications->publishToInterests(
						  ["inter".$userId],
						  [
						    "web" => [
						      "notification" => [
						        "title" => "Notificación CRM",
						        "body" => "Tienes ".count($commitmentsUser)." compromiso(s) que vencen esta semana",
						        "icon" => "https://crm.kebco.co/img/favicon.png",
						        "deep_link" => Router::url("/",true)."/commitments"
						      ],
						    ],
						  ]
						);
					} catch (Exception $e) {
					}

       	 		}
       	 	}

        } 

        $commitments = $this->Commitment->find("all",["conditions" => [ "Commitment.state" => [1,0], "DATE(Commitment.deadline) >=" =>date("Y-m-d"), "DATE(Commitment.deadline) <=" => date("Y-m-d") ]]);

        if (!empty($commitments)) {
       	 		
   	 		$users 		 = [];
   	 		$infoByUser  = [];
   	 		foreach ($commitments as $key => $value) {
   	 			$infoByUser[$value["Commitment"]["user_id"]] = [];
   	 			$users[$value["Commitment"]["user_id"]] = $value["User"]["email"];
   	 		}
   	 		foreach ($commitments as $key => $value) {
   	 			$infoByUser[$value["Commitment"]["user_id"]][] = $value;
   	 		}

   	 		foreach ($infoByUser as $userId => $commitmentsUser) {
   	 			
   	 			$emails  		 = [$users[$userId]];
				$commitments 	 = $commitmentsUser;
				$type	  		 = "hoy";
				$options 		 = array(

					'to'		=> $emails,
					'template'	=> 'commitments',
					'subject'	=> "Compromisos que vencen el día de hoy CRM ".date("Y-m-d"),
					'vars'		=> compact("commitments",'type')

				);
				
				$this->sendMail($options);

   	 			try {

					$pushNotifications = new \Pusher\PushNotifications\PushNotifications(array(
					  "instanceId" => "c40fa57b-967c-42e6-8ee6-80084a1aef4c",
					  "secretKey" => "C81C2CA939B40F50D6E38DAB0E4B472D28DD3AF079C097F189B951F50B8D9E66",
					));

					$publishResponse = $pushNotifications->publishToInterests(
					  ["inter".$userId],
					  [
					    "web" => [
					      "notification" => [
					        "title" => "Notificación CRM",
					        "body" => "Tienes ".count($commitmentsUser)." compromiso(s) que vencen el día de hoy",
					        "icon" => "https://crm.kebco.co/img/favicon.png",
					        "deep_link" => Router::url("/",true)."/commitments"
					      ],
					    ],
					  ]
					);
				} catch (Exception $e) {
				}

   	 		}
   	 	}
	}


	public function index($gestion = null) {
		$this->Commitment->recursive = 0;
		
		$conditions = [];

		if (is_null($gestion)) {
			$conditions["Commitment.user_id"] = AuthComponent::user("id");
		}else{
			$conditions["Commitment.user_id !="] = AuthComponent::user("id");
			$conditions["Commitment.assiged_by"] = AuthComponent::user("id");
		}

		$options = ['order' 		=> ["Commitment.created" => "DESC"],'conditions' 	=> $conditions];

		if (isset($this->request->query["calendar"])) {
			$conditions["DATE(Commitment.deadline) >="] = date("Y-m-d");
			$dataCommitments 		= $this->Commitment->find("all",$options);

			$sources 				= [ ["id" => AuthComponent::user("id"), 'title' => "Mis compromisos"  ] ];
			if (!is_null($gestion)) {
				$sources 			= [];
				
			}

			$commitments 			= [];
			foreach ($dataCommitments as $key => $value) {

				if (!is_null($gestion)) {
					$sources[$value["Commitment"]["user_id"]] = [ "id" => $value["Commitment"]["user_id"], "title" => $value["User"]["name"] ];
				}

				$start = date("Y-m-dTH:i:s",strtotime($value["Commitment"]["deadline"]));
				$start = str_replace("COT", "T", $start);

				$commitments[] = [
					"title" => $value["Commitment"]["name"],
					"url"	=> Router::url("/",true)."commitments/view/".$this->encrypt($value["Commitment"]["id"]),
					"start" => $start,
					"end" => $start,
					"resourceId" => $value["Commitment"]["user_id"]
				];
			}
			$sources = array_values($sources);
			$this->set('sources',$sources);

		}else{
			$options["limit"]			= 20;
			$this->paginate 			= $options;
			$commitments 				= $this->Paginator->paginate();
		}

		

		$this->set('commitments', $commitments);
		$this->set('gestion', $gestion);
	}

	/**
	* view method
	*
	* @throws NotFoundException
	* @param string $id
	* @return void
	*/
	public function view($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Commitment->exists($id)) {
			throw new NotFoundException(__('Invalid commitment'));
		}
		$options = array('conditions' => array('Commitment.' . $this->Commitment->primaryKey => $id));
		$this->set('commitment', $this->Commitment->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($gestion = null) {
		if ($this->request->is('post')) {
			$this->Commitment->create();

			$this->request->data["Commitment"]["deadline"] = date("Y-m-d H:i:s",strtotime($this->request->data["Commitment"]["deadline"]));
			if ($this->Commitment->save($this->request->data)) {

				if ($this->request->data["Commitment"]["user_id"] != $this->request->data["Commitment"]["assiged_by"] ) {
					$email   = $this->Commitment->User->field("email",["id" => $this->request->data["Commitment"]["user_id"] ]);
					$emails  = [$email, AuthComponent::user("email")];
					$data 	 = $this->request->data["Commitment"];
					$user    = AuthComponent::user("name");
					$options = array(
						'to'		=> $emails,
						'template'	=> 'new_commitment',
						'subject'	=> "Te han generado un nuevo compromiso en el CRM ".date("Y-m-d"),
						'vars'		=> compact("data","user")
					);
					
					$this->sendMail($options);
				}

				$this->Session->setFlash(__('La información fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index', is_null($gestion) ? "" : $gestion ));
			} else {
				$this->Session->setFlash(__('La información no fue guardada correctamente'),'Flash/error');
			}
		}
		if (!is_null($gestion)) {
			$cats = [];
		}else{
			$cats = $this->Commitment->Cat->find('list',["conditions" => ["Cat.user_id" => AuthComponent::user("id") ] ]);
		}
		$users = $this->Commitment->User->find('list',["conditions" => ["state" => 1, "id !=" => AuthComponent::user("id")] ]);
		$this->set(compact('cats', 'users', 'prospectiveUsers','gestion'));
	}

	public function change_commitment($id = null,$gestion = null) {
		$this->autoRender = false;
		$id 				= $this->desencriptarCadena($id);
		$options 			= array('recursive'=>-1,'conditions' => array('Commitment.' . $this->Commitment->primaryKey => $id));
		$commitment 		= $this->Commitment->find('first', $options);
		$commitment["Commitment"]["state"] = 2;
		$commitment["Commitment"]["date_end"] = date("Y-m-d H:i:s");
		$this->Commitment->save($commitment);

		if ($commitment["Commitment"]["assiged_by"] != $commitment["Commitment"]["user_id"]) {
			$userEmail = $this->Commitment->User->field("email",["id"=>$commitment["Commitment"]["assiged_by"]]);
			$emails  = [$userEmail, AuthComponent::user("email")];
			$data 	 = $commitment["Commitment"];
			$user    = $this->Commitment->User->field("name",["id"=>$commitment["Commitment"]["user_id"]]);;
			$options = array(
				'to'		=> $emails,
				'template'	=> 'update_commitment',
				'subject'	=> "Compromiso completado ".date("Y-m-d"),
				'vars'		=> compact("data","user")
			);
			
			$this->sendMail($options);
		}

		$this->Session->setFlash(__('La información fue guardada correctamente'),'Flash/success');
		return $this->redirect(array('action' => 'index',is_null($gestion) ? "" : $gestion ));
	}


	public function edit($id = null,$gestion = null) {

		$id 				= $this->desencriptarCadena($id);
		$options 			= array('conditions' => array('Commitment.' . $this->Commitment->primaryKey => $id));
		$commitment 		= $this->Commitment->find('first', $options);
		if (!$this->Commitment->exists($id)) {
			throw new NotFoundException(__('Invalid commitment'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data["Commitment"]["deadline"] = date("Y-m-d H:i:s",strtotime($this->request->data["Commitment"]["deadline"]));

			if ($this->Commitment->save($this->request->data)) {

				if ($commitment["Commitment"]["user_id"] != $this->request->data["Commitment"]["user_id"]) {
					$email   = $this->Commitment->User->field("email",["id" => $this->request->data["Commitment"]["user_id"] ]);
					$emails  = [$email, AuthComponent::user("email")];
					$data 	 = $this->request->data["Commitment"];
					$user    = AuthComponent::user("name");
					$options = array(
						'to'		=> $emails,
						'template'	=> 'new_commitment',
						'subject'	=> "Te han generado un nuevo compromiso en el CRM ".date("Y-m-d"),
						'vars'		=> compact("data","user")
					);
					
					$this->sendMail($options);
				}

				$this->Session->setFlash(__('La información fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index',is_null($gestion) ? "" : $gestion ));
			} else {
				$this->Session->setFlash(__('La información no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$this->request->data = $commitment;
		}

		if (!is_null($gestion)) {
			$cats = [];
		}else{
			$cats = $this->Commitment->Cat->find('list',["conditions" => ["Cat.user_id" => AuthComponent::user("id") ] ]);
		}
		$users = $this->Commitment->User->find('list',["conditions" => ["state" => 1, "id !=" => AuthComponent::user("id")] ]);
		$this->set(compact('cats', 'users', 'prospectiveUsers','gestion'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Commitment->id = $id;
		if (!$this->Commitment->exists()) {
			throw new NotFoundException(__('Invalid commitment'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Commitment->delete()) {
			$this->Flash->success(__('The commitment has been deleted.'));
		} else {
			$this->Flash->error(__('The commitment could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

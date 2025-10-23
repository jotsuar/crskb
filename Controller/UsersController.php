<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
class UsersController extends AppController {

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add','logout','remember_password','remember_password_step_2','change_password','sendReportGerenciaAsesores','validateTimeAttention','email_point','chat_point','register','get_user','set_times_init','set_times_end');
    }

    public function set_times_end(){
    	$this->autoRender = false;
    	$this->loadModel("SbTiempo");
    	$times_to_end = $this->SbTiempo->find("all",["conditions"=> [ "date_end" => null ] ]);

    	if(!empty($times_to_end)){
    		foreach ($times_to_end as $key => $value) {
    			$ourFinal = strtolower(date("D",strtotime(date("Y-m-d H:i:s")))) == "sat" ? date("Y-m-d 12:00:00") : date("Y-m-d 18:00:00");
    			$value["SbTiempo"]["date_end"] = $ourFinal;
    			$this->SbTiempo->save($value["SbTiempo"]);
    		}
    	}
    }

    public function set_times_init(){
    	$this->autoRender = false;
    	try {
    		$HttpSocket = new HttpSocket(['ssl_allow_self_signed' => false, 'ssl_verify_peer' => false, 'ssl_verify_host' =>false ]);
    		$chats      = $HttpSocket->post(Configure::read('URL_CHAT'),["function"=>"tiempo-actividad",'token'=>'57b9ef2591277ab300134d7a4d18dfb5b8a9b242']);
    	} catch (Exception $e) {
    		
    	}

    	$this->loadModel("ProspectiveUser","FlowStage");

        $this->loadModel('SbFlujo');

        $flujos = $this->SbFlujo->find("all",["conditions"=>["SbFlujo.created >=" => date("Y-m-01 00:00:00", strtotime('-1 month') ) ]]);
        // $flujos = $this->SbFlujo->find("all");

        if(!empty($flujos)){
            $allFlujos = [];
            foreach ($flujos as $key => $value) {
                $allFlujos[ $value["SbFlujo"]["flujo"] ] = $value["SbConversation"]["email"] != 'info@kebco.co' ? $value["SbConversation"]["email"] : $value["SbConversation"]["email_sub"];
            }
            $flujosRobot = $this->ProspectiveUser->find("all",["recursive" => 1, "conditions" => ["ProspectiveUser.id" => array_keys($allFlujos) ] ]);
            foreach ($flujosRobot as $key => $value) {
                if($value["ProspectiveUser"]["user_id"] == "112" && !is_null($allFlujos[$value["ProspectiveUser"]["id"]]) &&  $allFlujos[$value["ProspectiveUser"]["id"]] != 'info@kebco.co' ){
                    $this->ProspectiveUser->updateAll(["ProspectiveUser.user_id" => $this->ProspectiveUser->User->field("id",["email"=>$allFlujos[$value["ProspectiveUser"]["id"]]]) ], ["ProspectiveUser.id"=>$value["ProspectiveUser"]["id"]] );
                }
            }
        }
    }

    public function get_user(){
    	$this->autoRender = false;
    	if (isset($this->request->data["email"])) {
    		$this->User->recursive = -1;
    		$data = $this->User->findByEmailAndPassword($this->request->data["email"], AuthComponent::password($this->request->data["password"]) );
    		if (!empty($data)) {
    			unset($data["User"]["id"],$data["User"]["password"],$data["User"]["users"]);
    			return json_encode($data["User"]);
    		}
    	}
    }

    public function chat_point(){
    	$this->log($this->request->data,'debug');
    	$this->log($this->request->query,'debug');
    	$this->log($this->request,'debug');
    	$this->autoRender = false;
    	return true;
    	$this->setMessagesFirebase();
    }

    public function chat(){
    	$this->layout = false;
    }

    public function permissions(){
    	$this->layout = false;

    	$this->loadModel("Config");

    	$users = $this->Config->field("users_validate",["id" => 1]);
    	$users = explode(",", $users);
    	$user_id = $this->request->query["user_id"];

    	$conditions = ["User.state" => 1 ];

    	$usuarios = $this->User->find("list",["conditions"=> $conditions, "recursive" => -1]);

    	$actuales = $this->User->field("users",["id"=>$this->request->query["user_id"]]);

    	if ($this->request->is("post")) {
    		if (!empty($this->request->data["User"]["users"])) {
    			$this->request->data["User"]["users"] = implode(",", $this->request->data["User"]["users"]);
    		}
    		if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('La información se ha guardado satisfactoriamente', 'Flash/success');
				$this->overwrite_session_user(AuthComponent::user('id'));
				$this->redirect(array('controller' => 'Users', 'action' => 'index'));
			} else {
				$this->Session->setFlash('La información no se ha guardado, por favor inténtalo mas tarde','Flash/error');
			}
    	}

    	$actuales = explode(",", $actuales);


    	$this->set(compact("usuarios","actuales","user_id"));

    }

    public function copias(){
    	$this->layout = false;

    	$this->loadModel("Config");

    	$users = $this->Config->field("users_validate",["id" => 1]);
    	$users = explode(",", $users);
    	$user_id = $this->request->query["user_id"];

    	$conditions = ["User.id !=" => $this->request->query["user_id"],"User.state" => 1 ];

    	$usuarios = $this->User->find("list",["conditions"=> $conditions, "recursive" => -1]);

    	$actuales = $this->User->field("copias",["id"=>$this->request->query["user_id"]]);

    	if ($this->request->is("post")) {

    		if (!empty($this->request->data["User"]["copias"])) {
    			$this->request->data["User"]["copias"] = implode(",", $this->request->data["User"]["copias"]);
    		}
    		if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('La información se ha guardado satisfactoriamente', 'Flash/success');
				$this->overwrite_session_user(AuthComponent::user('id'));
				$this->redirect(array('controller' => 'Users', 'action' => 'index'));
			} else {
				$this->Session->setFlash('La información no se ha guardado, por favor inténtalo mas tarde','Flash/error');
			}
    	}

    	$actuales = explode(",", $actuales);


    	$this->set(compact("usuarios","actuales","user_id"));

    }

    public function initialMessages($number, $numberFinal){

    	$this->layout = false;
    	App::uses('AppController', 'Controller');
    	$urlMensajes     = Configure::read("Application.URL_WPP")."messages?token=".Configure::read("Application.TOKEN_WPP")."&limit=0";
    	$this->set("urlMensajes",$urlMensajes);

    }

    private function setMessageChats(){

    }

    private function getMessageDialog($existsChats, $chatId){
    	$response = null;
    	foreach ($existsChats->documents as $key => $value) {
    		if(isset($value->fields->id) && $value->fields->id->stringValue == $chatId ){
    			$response = $value;
    			unset($existsChats->documents[$key]);
    			break;
    		}
    	}
    	return ["chats" => $existsChats, "response" => $response];
    }

    private function setMessagesFirebase(){
		$urlChats     = Configure::read("Application.URL_WPP")."dialogs?token=".Configure::read("Application.TOKEN_WPP");
		try {
			$HttpSocket = new HttpSocket();
    		$chats      = $HttpSocket->get($urlChats);
    		$response 	= json_decode($chats->body);

    		$urlFirebase = "https://firestore.googleapis.com/v1/projects/chatempresa-68899/databases/(default)/documents/chats/";
			$clientGet   = new  HttpSocket();
			$existsChats = json_decode($clientGet->get($urlFirebase)->body);

    		if(isset($response->dialogs) && isset($existsChats->documents) && !empty($existsChats->documents) ){
    			$num = 0;
    			foreach ($response->dialogs as $keyDialog => $dialog) {
    				$paramsFinal = new Object();
    				$params = new Object();
    				$chatId = $dialog->id;
    				foreach ($dialog as $key => $value) {
    					$valueParam = new Object();

    					if($key == "last_time" ){
    						$valueParam->integerValue = intval($value);
    						$params->$key 			  = $valueParam;
    					}elseif($key != "metadata"){
    						$valueParam->stringValue = strval($value);
    						$params->$key 			 = $valueParam;
    					}
    				}
    				$paramsFinal->fields = $params;
    				try {    					
    					$datos 	 	 = $this->getMessageDialog($existsChats, $chatId);
    					$existsChats = $datos["chats"];
    					$exists      = $datos["response"];

    					$clientSave  = new HttpSocket();
    					
    					if(is_null($exists)){
    						$urlFirebasePost = "https://firestore.googleapis.com/v1/projects/chatempresa-68899/databases/(default)/documents/chats?collectionId=chats&documentId=".$chatId;
    						$clientSave->post($urlFirebasePost,json_encode($paramsFinal),["header" => ["Content-Type" => "application/json"]]);
    					}else{
    						$lastTime = (array)$exists->fields->last_time;
    						$lastTime = array_values($lastTime);
    						if($lastTime != $paramsFinal->fields->last_time->integerValue){
    							$clientSave->patch($urlFirebase,json_encode($paramsFinal),["header" => ["Content-Type" => "application/json"]]);
    						}
    					}

    				} catch (Exception $e) {
    					$this->log($e->getMessage(),"debug");	
    					die();
    				}
    			}
    			echo "termino";
    			die();
    		}

		} catch (Exception $e) {
			$this->log($e->getMessage(),"debug");
		}
    }

	public function index() {
		$this->User->recursive = -1;
		$users = $this->User->find("all",["conditions" => ["User.role !=" => "Asesor Externo" ] ]);
		$this->set(compact('users'));
	}

	public function externos() {
		$this->User->recursive = -1;
		$users = $this->User->find("all",["conditions" => ["User.role" => "Asesor Externo" ] ]);
		$this->set(compact('users'));
	}

	public function overwrite_session_user($user_id){
		$user 				= $this->User->get_data($user_id);
        $this->Session->write('Auth.User', $user['User'], true);
	}

	public function email_point(){

    	$this->autoRender = false;
    	$this->loadModel("EmailTracking");

    	$data 		= $this->request->input('json_decode');   
    	$emailId 	= explode(".", $data[0]->sg_message_id)['0'];
    	$emailTrack = $this->EmailTracking->findByMessageId($emailId);

    	if($data[0]->event == "delivered" && is_null($emailTrack["EmailTracking"]["delivered"]) ){
    		$emailTrack["EmailTracking"]["delivered"] 	= date("Y-m-d H:i:s");
    	}

    	if($data[0]->event == "open" && is_null($emailTrack["EmailTracking"]["read"])){
    		$emailTrack["EmailTracking"]["read"] 		= date("Y-m-d H:i:s");
    	}

    	if($data[0]->event == "click"  && is_null($emailTrack["EmailTracking"]["clicked"])){
    		$emailTrack["EmailTracking"]["clicked"] 	= date("Y-m-d H:i:s");
    	}

    	try {
    		$this->EmailTracking->save($emailTrack);
    	} catch (Exception $e) {
    		$this->log($e->getMessage(),"debug");
    	}

    	return json_encode(["status"=>true]);

    }



    public function clientes($id) {
    	$id 					= $this->desencriptarCadena($id);
    	$this->layout 			= false;
    	$this->loadModel("ClientsUser");

    	if ($this->request->is(array('post', 'put'))) {
    		
    		$this->ClientsUser->deleteAll(["ClientsUser.user_id" => $id ]);
    		$clientesInsert = [];
    		foreach ($this->request->data["User"]["cliente_id"] as $key => $value) {
    			 $pos = strpos($value,"LEGAL");
    			 if($pos === false){
    			 	$clientesInsert[] = ["user_id" => $id, "clients_natural_id" => str_replace("_NATURAL", "", $value) ];
    			 }else{
    			 	$clientesInsert[] = ["user_id" => $id, "clients_legal_id" => str_replace("_LEGAL", "", $value) ];
    			 }
    		}
    		$this->ClientsUser->saveAll($clientesInsert);
    		$this->Session->setFlash('La información se ha guardado satisfactoriamente', 'Flash/success');
    	}

    	$clientes = $this->ClientsUser->find("all",["conditions" => ["ClientsUser.user_id" => $id ] ]);
    	$ids 	  = [];

    	if (!empty($clientes)) {
    		$copyClient = $clientes;
    		$clientes 	= [];
    		foreach ($copyClient as $key => $value) {
    			if (is_null($value["ClientsUser"]["clients_legal_id"])) {
    				$clientes[$value["ClientsNatural"]["id"]."_NATURAL"] = trim($value["ClientsNatural"]["identification"])." | ".trim($value["ClientsNatural"]["name"])." | ".trim($value["ClientsNatural"]["email"]);
    				$ids[] = $value["ClientsNatural"]["id"]."_NATURAL";
    			}else{
    				$clientes[$value["ClientsLegal"]["id"]."_LEGAL"] = trim($value["ClientsLegal"]["nit"])." | ".trim($value["ClientsLegal"]["name"]);
    				$ids[] = $value["ClientsLegal"]["id"]."_LEGAL";
    			}
    		}
    	}


    	$this->set(compact("id","clientes","ids"));
	}

    public function categories($id){
    	$this->autoRender   	= false;
    	$id 					= $this->desencriptarCadena($id);
	    $this->loadModel("CategoriesUser");

    	if ($this->request->is(array('post', 'put'))) {

    		$this->CategoriesUser->deleteAll(["CategoriesUser.user_id" => $this->request->data["user_id"] ]);
    		$categoriesInsert = [];
    		foreach ($this->request->data["ids"] as $key => $value) {
    			$categoriesInsert[] = ["user_id" => $this->request->data["user_id"], "category_id" => $value ];
    		}
    		$this->CategoriesUser->saveAll($categoriesInsert);
    		$this->Session->setFlash('La información se ha guardado satisfactoriamente', 'Flash/success');
    	}else{
    		$categoriesInfoFinal 	= $this->getCagegoryData();

            $catInfo        = new stdClass();
            $catInfo->id    = 0;
            $catInfo->title = "Asignar todas";

	    	$categories     = $this->getCatsAndSubs(0,$categoriesInfoFinal);
            $catInfo->subs  = $categories;
            $categories     = [$catInfo];

	    	$actualCategories = $this->CategoriesUser->find("list",["fields"=>["category_id","category_id"],"conditions"=>["user_id" => $id]]);
	    	if (!empty($actualCategories)) {
	    		$actualCategories = array_values($actualCategories);
	    	}
	    	return json_encode(["categories"=>$categories, "actual" => $actualCategories ]);
    	}
    }

    private function getCatsAndSubs($catId,$cats){
    	$categories = [];
    	foreach ($cats[$catId] as $key => $value) {
    		$catInfo = new stdClass();
    		$catInfo->id 	= intval($value["id"]);
    		$catInfo->title = $value["name"];
    		if (isset($cats[$value["id"]]) && !empty($cats[$value["id"]]) ) {
    			$catInfo->subs = $this->getCatsAndSubs($value["id"],$cats);
    		}
    		$categories[] = $catInfo;
    	}
    	return $categories;
    }

	public function view($id) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->User->exists($id)) {
			throw new NotFoundException('El usuario no existe');
		}
		$user 				= $this->User->get_data($id);
		$this->loadModel("Commision");
		$this->Commision->recursive = -1;
		$datosComision = $this->Commision->findByUserId($id);
		$this->set("comision",$datosComision);
		$this->findDataLogsUserView($user['User']['id'],$user);
	}

	public function profile() {
		$user 		= $this->User->get_data(AuthComponent::user('id'));
		if ($this->request->is('post')) {
			if (isset($this->request->data['User']['email'])) {
				unset($this->request->data['User']['email']);
			}
			if ($this->request->data['User']['img']['name'] == '') {
				$imagen_user 	= false;
				$imagen 		= 1;
				unset($this->request->data['User']['img']);
				$this->request->data['User']['img'] 		= AuthComponent::user('img');
			} else {
				$imagen_user = true;
				$imagen = $this->loadPhoto($this->request->data['User']['img'],'users');
				$this->deleteImageServer(WWW_ROOT.'img/users/'.AuthComponent::user('img'));
				$this->request->data['User']['img'] 		= $this->Session->read('imagenModelo');
			}

			if ($this->request->data['User']['signature']['name'] == '') {
				$imagen_user 	= false;
				$imagen 		= 1;
				unset($this->request->data['User']['signature']);
				$this->request->data['User']['signature'] 		= AuthComponent::user('signature');
			} else {
				$imagen_user 	= true;
				$imagen 		= $this->loadPhoto($this->request->data['User']['signature'],'users');
				$this->request->data['User']['signature'] 		= $this->Session->read('imagenModelo');
			}
			if ($imagen == 1) {
				$this->request->data['User']['id']			= AuthComponent::user('id');
				if(!empty($this->request->data["User"]["password_email"])){
					$this->request->data["User"]["password_email"] = base64_encode("@@KEBCO@@".$this->request->data["User"]["password_email"]);
				}
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash('La información se ha guardado satisfactoriamente', 'Flash/success');
					$this->overwrite_session_user(AuthComponent::user('id'));
					if ($imagen_user) {
						$this->Session->delete('imagenModelo');
					}
					$this->redirect(array('controller' => 'users', 'action' => 'profile'));
				} else {
					$this->Session->setFlash('La información no se ha guardado, por favor inténtalo mas tarde','Flash/error');
				}
			} else {
				$this->validateImageState($imagen);
			}	
		}

		$optionsDays = [];

		$this->loadModel("Config");
		$days_remarketing = empty($this->Config->field("days_remarketing",["id" => 1]) ) ? 15 :$this->Config->field("days_remarketing",["id" => 1]);

		$divisor = $days_remarketing;
 
		for($i = 1; $i < $divisor; $i ++) {
	        if ($divisor % $i == 0) {
	            $optionsDays[$i] = $i;
	        }
		}
		$this->set(compact("optionsDays"));

		$this->findDataLogsUserView(AuthComponent::user('id'),$user);
	}


	public function findDataLogsUserView($user_id,$user){
		$datosLogDay 						= $this->User->Log->get_logs_user_day($user_id);
		$datosLogYesterday 					= $this->User->Log->get_logs_user_yesterday($user_id);
		$datosLogBeforeDayYesterday 		= $this->User->Log->get_logs_user_before_day_yesterday($user_id);
		$this->set(compact('user','datosLogDay','datosLogYesterday','datosLogBeforeDayYesterday'));
	}

	public function register() {
		die();
		if ($this->request->is("post")) {
			$imagen = $this->loadPhoto($this->request->data['User']['img'],'users');
			if ($imagen == 1) {
				$this->request->data['User']['img'] 		= $this->Session->read('imagenModelo');
			}
			$imagen = $this->loadPhoto($this->request->data['User']['img_identification_up'],'users');
			if ($imagen == 1) {
				$this->request->data['User']['img_identification_up'] 		= $this->Session->read('imagenModelo');
			}
			$imagen = $this->loadPhoto($this->request->data['User']['img_identification_down'],'users');
			if ($imagen == 1) {
				$this->request->data['User']['img_identification_down'] 		= $this->Session->read('imagenModelo');
			}
            $imagen                                         = $this->loadDocumentPdf($this->request->data['User']['rut'],'users');
            if ($imagen == 1) {
                $this->request->data['User']['rut']         = $this->Session->read('documentoModelo');
            }
			$this->request->data['User']['state']  			= 2; 

			if($this->request->data["User"]["name"] == '' || $this->request->data["User"]["identification"] == '' || $this->request->data["User"]["city"] == '' || $this->request->data["User"]["name"] == 'telephone' || $this->request->data["User"]["email"] == '' ){
				$this->Session->setFlash('Faltan datos requeridos','Flash/error');
				$this->redirect(['action'=>'login']);
			}else{
				return 1;
			}

			if ($this->User->save($this->request->data)) {
				$id_colum 			= $this->User->id;

				$emails = $this->User->find("list",["fields"=>["id","email"],"conditions" => ["User.role" => "Gerente General", "User.state" => 1] ]);

				$this->saveDataLogsUser(2,'User',$id_colum);
				$email = $this->request->data['User']['email'];
				$name = $this->request->data['User']['name'];
				$options = array(
					'to'		=> $emails,
					'template'	=> 'new_asesor',
					'subject'	=> '¡Se ha registrado un nuevo asesor para tu aprobación!',
					'vars'		=> array('email' => $email,'name' => $name,),
				);

				$this->loadModel("Commision");
				$this->loadModel("Percentage");

				$defaultComission = $this->Commision->find("first",["recursive" => -1, "conditions" => ["Commision.user_id" => 0] ]);
				$defaultComission["Commision"]["user_id"] = $id_colum;
				unset($defaultComission["Commision"]["id"],$defaultComission["Commision"]["created"],$defaultComission["Commision"]["modified"]);
				$this->Commision->create();
				$this->Commision->save($defaultComission);

				$defaultPorcentaje = $this->Percentage->find("all",["recursive" => -1, "conditions" => ["Percentage.user_id" => 0] ]);

				foreach ($defaultPorcentaje as $key => $value) {
					$value["Percentage"]["user_id"] = $id_colum;
					unset($value["Percentage"]["id"],$value["Percentage"]["created"],$value["Percentage"]["modified"]);
					$this->Percentage->create();
					$this->Percentage->save($value);
				}

				$this->sendMail($options);
				$this->Session->delete('imagenModelo');
				$this->Session->setFlash('El usuario se ha creado satisfactoriamente, debes esperar que sea aprobado se te avisará al correo registrado.', 'Flash/success');
				try {
					$httpSocket = new HttpSocket();
                	$response = $httpSocket->post(Router::url("/",true)."cats/setGeneralCategory",[]);
				} catch (Exception $e) {
					
				}
				return $this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash('El usuario no se ha creado, por favor inténtalo mas tarde','Flash/error');
			}
		}
	}

	public function add() {
		$roles 		= Configure::read('variables.roles_usuarios');
		if ($this->request->is('post')) {
			$imagen = $this->loadPhoto($this->request->data['User']['img'],'users');
			if ($imagen == 1) {
				$this->request->data['User']['img'] 		= $this->Session->read('imagenModelo');
				$this->request->data['User']['password'] 	= Configure::read('variables.password');
				$this->User->create();
				if ($this->User->save($this->request->data)) {
					$id_colum 			= $this->User->id;
					$this->saveDataLogsUser(2,'User',$id_colum);
					$email = $this->request->data['User']['email'];
					$name = $this->request->data['User']['name'];
					$options = array(
						'to'		=> $this->request->data['User']['email'],
						'template'	=> 'new_user_advisers',
						'subject'	=> '¡Ahora haces parte del nuevo CRM de KEBCO!',
						'vars'		=> array('email' => $email,'name' => $name,'password' => Configure::read('variables.password')),
					);
					$this->sendMail($options);
					$this->Session->delete('imagenModelo');
					$this->Session->setFlash('El usuario se ha creado satisfactoriamente', 'Flash/success');

					try {
						$httpSocket = new HttpSocket();
                		$response = $httpSocket->post(Router::url("/",true)."cats/setGeneralCategory",[]);
					} catch (Exception $e) {
							
					}

					return $this->redirect(array('action' => 'login'));
				} else {
					$this->Session->setFlash('El usuario no se ha creado, por favor inténtalo mas tarde','Flash/error');
				}
			} else {
				$this->validateImageState($imagen);
			}
		}
		$this->set(compact('roles'));
	}

	public function approve(){
		$this->autoRender = false;

		$this->loadModel("CategoriesUser");

		$cats = $this->CategoriesUser->find("count", ["conditions" => ["CategoriesUser.user_id" => $this->request->data["id"] ] ] );

		if ($cats == 0 || empty($cats)) {
			$this->Session->setFlash('El usuario no se puede aprobar ya que no se han configurado categorías de productos', 'Flash/error');
			return false;
		}

		$user = $this->User->findById($this->request->data["id"]);
		$user["User"]["state"] = 1;
		$email = $user['User']['email'];
		$name = $user['User']['name'];
		$options = array(
			'to'		=> $user['User']['email'],
			'template'	=> 'new_user_advisers',
			'subject'	=> '¡Ahora haces parte del nuevo CRM de KEBCO!',
			'vars'		=> array('email' => $email,'name' => $name,'password' => Configure::read('variables.password'),"externo" => true),
		);
		unset($user["User"]["password"]);
		$this->sendMail($options);
		$this->User->save($user["User"]);

        $this->loadModel("Goal");

        $goalUser = $this->Goal->findById(0);
        $nameParts = explode(" ", $name);

        $goalUser["Goal"]["id"] = null;
        $goalUser["Goal"]["anio"] = date("Y");
        $goalUser["Goal"]["name"] = strtoupper($nameParts["0"]). " ".strtoupper($nameParts["1"]);
        $goalUser["Goal"]["user_id"] = $this->request->data["id"];
        $this->Goal->create();
        $this->Goal->save($goalUser["Goal"]);

		$this->Session->setFlash('El usuario se habilitó satisfactoriamente', 'Flash/success');
	}

	public function reject(){
		$this->autoRender = false;
		$user = $this->User->findById($this->request->data["id"]);
		$user["User"]["state"] = 3;
		$email = $user['User']['email'];
		$name = $user['User']['name'];
		$options = array(
			'to'		=> $user['User']['email'],
			'template'	=> 'reject_user',
			'subject'	=> '¡No fue aprobado tu registro en CRM de KEBCO!',
			'vars'		=> array('email' => $email,'name' => $name,"razon"=>$this->request->data["razon"],"externo" => true),
		);
		unset($user["User"]["password"]);
		$this->sendMail($options);
		$this->User->save($user["User"]);
		$this->Session->setFlash('El usuario se habilitó satisfactoriamente', 'Flash/success');
	}

	public function edit(){
		$this->layout 						= false;
		if ($this->request->is('ajax')) {
			$roles 							= Configure::read('variables.roles_usuarios');
			$datos 							= $this->User->get_data($this->request->data['user_id']);
			$this->set(compact('datos','roles'));
		}
	}

	public function editSave(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$this->request->data["User"]["id"]		= $this->request->data["User"]["user_id"];
			$documento 								= $this->loadPhoto($this->request->data['User']['img_signature'],'users/signatures');
			if($documento == 4){
				return 0;
			}else{
				$this->request->data['User']['img_signature']			= $this->Session->read('imagenModelo');
			}
			$this->User->save($this->request->data);
			$this->saveDataLogsUser(3,'User',$this->request->data["User"]['id']);
			$this->Session->setFlash('Datos actualizados correctamente', 'Flash/success');
			return true;
		}
	}

	public function changePasswordUser(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$datos 		= $this->User->get_data(AuthComponent::user('id'));
			$actual 	= AuthComponent::password($this->request->data['actual']);
			$nueva 		= $this->request->data['nueva'];
			if ($actual == $datos['User']['password']) {
				$datos['User']['password'] = $nueva;
				if ($this->User->save($datos)) {
					return 1;
				} else {
					return 0;
				}
			} else {
				return 2;
			}
		}
	}
	
	public function login() {
		$this->layout = "start";
		$this->validateSessionTrue();
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->validateStateLogin(AuthComponent::user('state'));
				$this->saveDataLogsUser(5,'User',0);
				$this->paintValidateMenu(AuthComponent::user('role'));
			} else {
				$this->Session->setFlash('El correo electrónico o la contraseña no coinciden','Flash/error');
			}
		}
	}

	public function validateStateLogin($state){
		if ($state == '0' || $state == '3' ) {
			$this->Session->destroy();
			$this->Session->setFlash('Tu cuenta se ecuentra inhabilitada, por favor comunícate con el administrador','Flash/error');
			$this->redirect(array('action' => 'login'));
		}
	}

	public function logout() {
		if (AuthComponent::user('id')) {
			$this->updateStateConectionFalse(AuthComponent::user('id'));
			$this->saveDataLogsUser(4,'User',0);
			$this->Session->destroy();
		}
		$this->redirect(array('action' => 'login'));
	}

	public function session_off(){
		$this->layout 	= false;
		$this->updateStateConectionFalse(AuthComponent::user('id'));
		$this->saveDataLogsUser(4,'User',0);
		$this->Session->destroy();
	}

	public function role_permissions_false(){
		$this->Session->destroy();
		$this->Session->setFlash('Por favor comunícate con el administrador, tu cuenta tiene problemas','Flash/error');
		$this->set(compact(null));
	}

	public function remember_password(){
		$this->layout = "start";
		$this->validateSessionTrue();
		if ($this->request->is('post')) {
			$user = $this->User->get_user_email($this->request->data['User']['email']);
			if (empty($user)) {
				$this->Session->setFlash('El correo electrónico no existe en nuestra base de datos','Flash/error');
				$this->redirect(array('action' => 'login'));
			}
			$hash = $this->User->generate_hash_change_password();
			$data = array(
				'User' => array(
					'id' => $user['User']['id'],
					'hash_change_password' => $hash
				)
			);
			$this->User->save($data);
			$options = array(
				'to'		=> $this->request->data['User']['email'],
				'template'	=> 'remember_password',
				'subject'	=> '¡Ya puedes restablecer tu contraseña!',
				'vars'		=> array('hash' => $hash, 'name' => $user['User']['name']),
			);
			$this->sendMail($options);
			$this->Session->setFlash('Ahora ingresa a tu correo electrónico y sigue las instrucciones', 'Flash/success');
			$this->redirect(array('action' => 'login'));
		}
	}

	public function remember_password_step_2($hash = null) {
		$user = $this->User->findByHashChangePassword($hash);
		if ($user['User']['hash_change_password'] != $hash || empty($user)) {
			$this->Session->setFlash('Ocurrió un error, por favor vuelve a restablecer la contraseña','Flash/error');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		if ($this->request->is('post')) {
			if ($this->request->data['User']['password'] === $this->request->data['User']['re_password']) {
				$this->request->data['User']['id'] = $user['User']['id'];
				$this->request->data['User']['hash_change_password'] = '';
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash('Se guardó la contraseña satisfactoriamente', 'Flash/success');
				} else {
					$this->Session->setFlash('No se pudo guardar la contraseña','Flash/error');
				}
				$this->redirect(array('action'=>'login'));
			}
		}
		$this->set(compact('hash'));
	}

	public function usersAdviserStateTrueAll(){
		$this->autoRender 					= false;
		if ($this->request->is('ajax')) {
			$usuarios 		 		= $this->User->role_asesor_comercial_users_all_true();
			return json_encode($usuarios);
		}
	}

	public function updateNoticeView(){
		$this->autoRender 								= false;
		if ($this->request->is('ajax')) {
			$state_conection 							= $this->User->consult_notice_conection();
			$datos['User']['id']						= AuthComponent::user('id');
			if ($state_conection == Configure::read('variables.state_enabled')) {
				$datos['User']['notice_conection']		= Configure::read('variables.state_disabled');
			} else {
				$datos['User']['notice_loguin']			= Configure::read('variables.state_disabled');
			}
			$this->User->save($datos);
			$this->overwrite_session_user(AuthComponent::user('id'));
			return true;
		}
	}

	public function sendReportGerenciaAsesores(){
		$this->autoRender 			= false;
    	$flujosTimeInvalid 			= $this->reportGerenciaTimeAsesoresAtender();
    	$usersGerencia      		= $this->User->role_gerencia_user();
    	foreach ($usersGerencia as $value) {
    		$options = array(
						'to'		=> $value['User']['email'],
						'template'	=> 'report_gerencia_asesor',
						'subject'	=> 'Reporte de atención invalida de los asesores',
						'vars'		=> array('flujos' => $flujosTimeInvalid),
					);
    		$this->sendMail($options);
    	}
    }

    public function validateTimeAttention(){
    	$this->autoRender 			= false;
    	$this->User->ProspectiveUser->AtentionTime->validate_date_atention_cotizado();
    	$this->User->ProspectiveUser->AtentionTime->validate_date_atention_contactado();
    	return true;
    }

    public function test_email(){
    	$this->autoRender = false;

    	$email  = $this->request->data["email"] == "jotsuar@gmail.com" ? "sistemas@almacendelpintor.com" : $this->request->data["email"];

    	$config = array(
    		"username" => $email,
    		"password" => $this->request->data["password"],
    	);

    	$options = array(
			'to'		=> $email,
			'template'	=> 'test_email',
			'subject'	=> 'Email de prueba - KEBCO AlmacenDelPintor.com',
			'vars'		=> array(),
		);
		$emailSend = $this->sendMail($options,null,$config);
		if($emailSend === false){
			return 0;
		}else{
			$this->Session->setFlash('La información se ha guardado satisfactoriamente', 'Flash/success');
			return 1;
		}
    }
    
}
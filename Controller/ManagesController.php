<?php
App::uses('AppController', 'Controller');

class ManagesController extends AppController {

	public function notificaciones(){
		$this->layout 			= false;
		if ($this->request->is('ajax')) {
			$datos 				= $this->Manage->get_data_user_limit_new(AuthComponent::user('id'));
			$this->set(compact('datos'));
		}
	}

	public function index(){
		$datosLeida 			= $this->Manage->count_user_manages_read(AuthComponent::user('id'));
		$datosNueva 			= $this->Manage->count_user_manages_new(AuthComponent::user('id'));
		$datos 					= $this->Manage->get_data_user(AuthComponent::user('id'));
		$this->set(compact('datos','datosLeida','datosNueva'));
	}

	public function message(){
		$this->layout = false;
		if ($this->request->is("post")) {
			$users = $this->Manage->User->find("list",["conditions"=>["User.state"=> 1, "User.id !="=> AuthComponent::user("id")],"fields" => ["id","id"] ]);
			$descNotification = "<p>".$this->request->data["Manage"]["title"]."</p><br>".$this->request->data["Manage"]["message"];

			foreach ($users as $key => $value) {
				$this->saveManagesUser($descNotification,24,$value,null,null,"/",$this->request->data["Manage"]["type"]);
			}

			$this->Session->setFlash(__('Mensaje enviado a todos los usuarios.'),'Flash/success'); 

		}
	}

	public function diary(){}

	public function diary_dashboard(){
		$this->layout 			= 'agenda';
	}

	public function paintDataCalendar(){
		$this->autoRender 							= false;
		$datosNotificaciones 						= $this->Manage->get_data_user(AuthComponent::user('id'));
		$datos 										= array();
		for ($i=0; $i < count($datosNotificaciones) ; $i++) { 
			$datos[$i]['title']						=	$datosNotificaciones[$i]['Manage']['description'].', '.mb_strtoupper($this->nameProspective($datosNotificaciones[$i]['Manage']['prospective_users_id']));
			$datos[$i]['start'] 					=	$datosNotificaciones[$i]['Manage']['date'].'T'.$datosNotificaciones[$i]['Manage']['time'];
			$datos[$i]['end'] 						=	$datosNotificaciones[$i]['Manage']['date'].'T'.$datosNotificaciones[$i]['Manage']['time_end'];
			$datos[$i]['url'] 						=	$datosNotificaciones[$i]['Manage']['url'];
		}
		return json_encode($datos);
	}

	public function paintDataCalendarDashboard(){
		$this->autoRender 								= false;
		$datosNotificaciones 							= $this->Manage->get_data_user(AuthComponent::user('id'));
		$datos 											= array();
		for ($i=0; $i < count($datosNotificaciones) ; $i++) { 
			$datos[$i]['title']						=	$datosNotificaciones[$i]['Manage']['description'].' '.mb_strtoupper($this->nameProspective($datosNotificaciones[$i]['Manage']['prospective_users_id']));
			$datos[$i]['start'] 					=	$datosNotificaciones[$i]['Manage']['date'].'T'.$datosNotificaciones[$i]['Manage']['time'];
			$datos[$i]['end'] 						=	$datosNotificaciones[$i]['Manage']['date'].'T'.$datosNotificaciones[$i]['Manage']['time_end'];
		}
		return json_encode($datos);
	}

	public function changestate(){
		$this->autoRender 	= false;
		if ($this->request->is('ajax')) {
			$datosA 								= $this->Manage->get_data($this->request->data['notificacion_id']);
			$datosN['Manage']['state']				= $this->request->data['state'];
			$datosN['Manage']['id']					= $this->request->data['notificacion_id'];
			$this->Manage->save($datosN);
			return $datosA["Manage"]["type"] == 1 ? Router::url("/",true)."Manages" : $datosA['Manage']['url'] ;
		}
	}

	public function nameProspective($id){
		$this->loadModel('ProspectiveUser');
		$nombre = "";
		$datos 				= $this->ProspectiveUser->get_data($id);
		if(!empty($datos)){
			if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
				$datosC 		= $this->ProspectiveUser->ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
				$nombre 		= $datosC['ClientsLegal']['name'].' - '.$datosC['ContacsUser']['name'];
			} elseif(!is_null($datos['ProspectiveUser']['clients_natural_id'])) {
				$datosC 		= $this->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
				$nombre 		= $datosC['ClientsNatural']['name'];
			}
		}
		
		return $nombre;
	}

	public function marcarNotificacionesLeidas(){
		$this->autoRender 	= false;
		if ($this->request->is('ajax')) {
			$this->Manage->update_notify_leidas_all(AuthComponent::user('id'));
			return true;
		}
	}

	public function countManagesUser(){
		$this->autoRender 	= false;
		return $this->Manage->count_user_manages_new(AuthComponent::user('id'));
	}



}

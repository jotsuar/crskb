<?php
App::uses('AppController', 'Controller');

class ManagementNoticesController extends AppController {

	public $components = array('Paginator');
	
	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('validateTime');
    }

	public function index() {
		$get 						= $this->request->query;
		if (!empty($get)) {
			$conditions				= array('OR' => array(
									            'LOWER(ManagementNotice.title) LIKE' 		=> '%'.mb_strtolower($get['q']).'%',
									            'LOWER(ManagementNotice.description) LIKE' 	=> '%'.mb_strtolower($get['q']).'%'
									        )
										);
		} else {
			$conditions 			= array();
		}
		$order						= array('ManagementNotice.id' => 'desc');
		$this->paginate 			= array(
										'order' 		=> $order,
							        	'limit' 		=> 19,
							        	'conditions' 	=> $conditions,
							    	);
		$managementNotices 			= $this->paginate('ManagementNotice');
		$this->set(compact('managementNotices'));
	}

	public function view($id = null) {
		$id 			= $this->desencriptarCadena($id);
		if (!$this->ManagementNotice->exists($id)) {
			throw new NotFoundException('La nota de gerencia no existe');
		}
		$managementNotice 											= $this->ManagementNotice->get_data($id);
		$this->set(compact('managementNotice','id'));
	}

	public function add_new(){
		$this->layout 												= false;
	}

	public function add() {
		$this->autoRender 											= false;
		if ($this->request->is('ajax')) {
			$this->request->data['ManagementNotice']['user_id'] 	= AuthComponent::user('id');
			if (strtotime($this->request->data['ManagementNotice']['fecha_ini']) == strtotime(date("Y-m-d"))) {
				$this->request->data['ManagementNotice']['state'] 	= Configure::read('variables.state_enabled');
				$this->ManagementNotice->User->users_notice_conection();
				$this->ManagementNotice->User->users_notice_loguin();
			} else {
				$this->request->data['ManagementNotice']['state'] 	= Configure::read('variables.state_waiting');
			}
			$this->request->data['ManagementNotice']['price'] 		= $this->replaceText($this->request->data['ManagementNotice']['price'],".", "");
			if ($this->request->data['ManagementNotice']['imge']['name'] != '') {
				$this->loadPhoto($this->request->data['ManagementNotice']['imge'],'managementNotices');
				$this->request->data['ManagementNotice']['img'] 	= $this->Session->read('imagenModelo');
			}
			$this->ManagementNotice->create();
			$this->ManagementNotice->save($this->request->data);
			$this->sendEmail();
		}
	}

	public function sendEmail(){
        $users                  = $this->ManagementNotice->User->all_users();
        $arrayUserEmail         = array();
        foreach ($users as $value) {
        	$arrayUserEmail[]   = $value['User']['email'];
		}
		$options = array(
			'to'		=> $arrayUserEmail,
			'template'	=> 'new_management',
			'subject'	=> 'Aviso de gerencia nuevo',
			'vars'		=> array(),
		);
		$this->sendMail($options);
	}

	public function activeNotice(){
		$this->autoRender 									= false;
		$date 												= $this->request->data['date'];
		$state 												= $this->request->data['state'];
		if (strtotime($date) <= strtotime(date("Y-m-d"))) {
			$this->ManagementNotice->User->users_notice_conection();
			$this->ManagementNotice->User->users_notice_loguin();
			return true;
		} else {
			return false;
		}
	}

	public function validateTime(){
		$this->autoRender 									= false;
		$noticias_espera 									= $this->ManagementNotice->notices_waiting();
		$noticias_update_disabled 							= $this->ManagementNotice->notices_update_enabled();
		foreach ($noticias_espera as $value) {
			$this->ManagementNotice->update_enabled($value['ManagementNotice']['id']);
		}
		foreach ($noticias_update_disabled as $value) {
			$this->ManagementNotice->update_disabled($value['ManagementNotice']['id']);
		}
		return true;
	}

}
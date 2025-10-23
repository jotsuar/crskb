<?php
App::uses('AppController', 'Controller');
/**
 * CustomerRequests Controller
 *
 * @property CustomerRequest $CustomerRequest
 * @property PaginatorComponent $Paginator
 */
class CustomerRequestsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {

		$get 		   = isset($this->request->query["q"]) && !empty($this->request->query["q"]) ? $this->request->query : array() ;
		if(!empty($get["q"]) ){
			$conditions	= array('prospective_user_id' => $get['q']);
    	}else{
    		$conditions = [];
    	}
		$this->CustomerRequest->recursive = 0;
		$this->paginate 			= array( 'conditions' 	=> $conditions );
		$this->set('customerRequests', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CustomerRequest->exists($id)) {
			throw new NotFoundException(__('Invalid customer request'));
		}
		$options = array('conditions' => array('CustomerRequest.' . $this->CustomerRequest->primaryKey => $id));
		$this->set('customerRequest', $this->CustomerRequest->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->layout = false;

		$type  = $this->request->query["type"];
		$id    = $this->request->query["id"];
		$flujo = $this->request->query["flujo"];

		if($type == 'natural'){
			$this->loadModel('ClientsNatural');
			$customer_data = $this->ClientsNatural->findById($id);
		}else{
			$this->loadModel('ContacsUser');
			$customer_data = $this->ContacsUser->findById($id);
		}

		if ($this->request->is('post')) {
			if (isset($this->request->data["CustomerRequest"]["rut"]) && !empty($this->request->data["CustomerRequest"]["rut"]["name"])) {
				$this->loadDocumentPdf($this->request->data["CustomerRequest"]["rut"],"requests");
				$this->request->data["CustomerRequest"]["rut"] = $this->Session->read("documentoModelo");
			}else{
				$this->request->data["CustomerRequest"]["rut"] = null;
			}
			$this->CustomerRequest->create();
			if ($this->CustomerRequest->save($this->request->data)) {
				$this->Session->setFlash('Información guardada satisfactoriamente', 'Flash/success');
			} else {
				$this->Session->setFlash('Información no fue guardada satisfactoriamente', 'Flash/error');
			}

			$options = array(
				'cc'		=> ["logistica@kebco.co"],
				'to'		=> 'ventas1@almacendelpintor.com',
				'template'	=> 'request_customer',
				'subject'	=> 'Solicitud de creación de cliente de KEBCO AlmacenDelPintor.com',
				'vars'		=> array("type"=>"initial","usuario" => AuthComponent::user("name"), "flujo" => $flujo, "url" => Router::url("/",true)."customer_requests/index?q=".$flujo )
			);

			$this->sendMail($options);
			return $this->redirect(array('action' => 'add','controller' => 'orders', "?" => ["flow" => $this->encryptString($flujo) ] ));
		}
		$this->set(compact('type','id','flujo','customer_data'));
	}

	public function change_payed($uid) {
		$this->autoRender = false;

		$id = $this->desencriptarCadena($uid);
		if (!$this->CustomerRequest->exists($id)) {
			throw new NotFoundException(__('Invalid import request'));
		}

		$this->CustomerRequest->save([
			"state" => 1,
			"date_created" => date("Y-m-d H:i:s"),
			"id" => $id,
			"user_create" => AuthComponent::user("id")
		]);

		$request = $this->CustomerRequest->findById($id);


		$options = array(
			'cc'		=> [],
			'to'		=> $request["User"]["email"],
			'template'	=> 'request_customer',
			'subject'	=> 'Actualización de creación de cliente de KEBCO AlmacenDelPintor.com',
			'vars'		=> array("type"=>"general","usuario" => AuthComponent::user("name"), "flujo" => $flujo, "url" => Router::url("/",true)."orders/add?flow=".$this->encryptString($request["CustomerRequest"]["prospective_user_id"]) )
		);

		$this->sendMail($options);

		$this->Session->setFlash(__('La solicitud fue cambiada correctamente'),'Flash/success');
		$this->redirect(["action" => "index" ]);
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CustomerRequest->exists($id)) {
			throw new NotFoundException(__('Invalid customer request'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CustomerRequest->save($this->request->data)) {
				$this->Flash->success(__('The customer request has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The customer request could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CustomerRequest.' . $this->CustomerRequest->primaryKey => $id));
			$this->request->data = $this->CustomerRequest->find('first', $options);
		}
		$users = $this->CustomerRequest->User->find('list');
		$this->set(compact('users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->CustomerRequest->exists($id)) {
			throw new NotFoundException(__('Invalid customer request'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->CustomerRequest->delete($id)) {
			$this->Flash->success(__('The customer request has been deleted.'));
		} else {
			$this->Flash->error(__('The customer request could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

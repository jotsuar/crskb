<?php
App::uses('AppController', 'Controller');
/**
 * Pqrs Controller
 *
 * @property Pqr $Pqr
 * @property PaginatorComponent $Paginator
 */
class PqrsController extends AppController {

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add','view');
    }

	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Pqr->recursive = 0;
		$this->set('pqrs', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->loadModel("Responsepqr");
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Pqr->exists($id)) {
			throw new NotFoundException(__('Invalid pqr'));
		}
		$options = array('conditions' => array('Pqr.' . $this->Pqr->primaryKey => $id));
		$pqr     = $this->Pqr->find('first', $options);
		$this->set('pqr', $pqr );

		if ($this->request->is("post")) {
			if (!empty($this->request->data["Responsepqr"]["file"]["name"])) {
				$this->loadDocumentPdf($this->request->data["Responsepqr"]["file"],"responses");
				$this->request->data["Responsepqr"]["file"] = $this->Session->read("documentoModelo");
			}else{
				unset($this->request->data["Responsepqr"]["file"]);
			}
			$this->request->data["Responsepqr"]["id_pqr"] = $this->desencriptarCadena($this->request->data["Responsepqr"]["id_pqr"]);
			$this->Responsepqr->create();
			if ($this->Responsepqr->save($this->request->data) && AuthComponent::user("id") && $this->request->data["Responsepqr"]["response_type"] == 1) {
				$this->loadModel("User");
				$this->User->recursive = -1;
	            $users = $this->User->findAllByRoleAndState(["Gerente General"],1);
	            if(!empty($users)){
	                $users = Set::extract($users, "{n}.User.email");
	            }

	            $pqr["Pqr"]["state"] = $this->request->data["Responsepqr"]["state"];
	            $pqr["Pqr"]["modified"] = date("Y-m-d H:i:s");
	            $this->Pqr->save($pqr);

	            $users[] = $pqr["Pqr"]["email"];
	            
	            $options = array(
	                'to'        => $users,
	                'template'  => 'pqr_creado',
	                'subject'   => 'Se ha gestionado el PQRS #'.$pqr["Pqr"]["code"],
	                'vars'      => ["id" => $pqr["Pqr"]["id"],"type" => 2 ]
	            );


	            $this->sendMail($options, true);
			}
		}

		$responses = $this->Responsepqr->findAllByIdPqr($id);
		$this->set("responses",$responses);

	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$clienteActivo = $this->Session->read("CLIENTE");
		if ($this->request->is('post')) {
			$this->Pqr->create();
			if (!empty($this->request->data["Pqr"]["file1"]["name"])) {
				$this->loadDocumentPdf($this->request->data["Pqr"]["file1"],"pqrs");
				$this->request->data["Pqr"]["file1"] = $this->Session->read("documentoModelo");
			}else{
				unset($this->request->data["Pqr"]["file1"]);
			}
			if (!empty($this->request->data["Pqr"]["file2"]["name"])) {
				$this->loadDocumentPdf($this->request->data["Pqr"]["file2"],"pqrs");
				$this->request->data["Pqr"]["file2"] = $this->Session->read("documentoModelo");
			}else{
				unset($this->request->data["Pqr"]["file2"]);
			}
			if (!empty($this->request->data["Pqr"]["file3"]["name"])) {
				$this->loadDocumentPdf($this->request->data["Pqr"]["file3"],"pqrs");
				$this->request->data["Pqr"]["file3"] = $this->Session->read("documentoModelo");
			}else{
				unset($this->request->data["Pqr"]["file3"]);
			}
			if ($this->Pqr->save($this->request->data)) {
				$this->Session->setFlash(__('PQRS fue guardado correctamente'),'Flash/success');
				$this->loadModel("User");
				$this->User->recursive = -1;
	            $users = $this->User->findAllByRoleAndState(["Gerente General"],1);
	            if(!empty($users)){
	                $users = Set::extract($users, "{n}.User.email");
	            }

	            $users[] = $this->request->data["Pqr"]["email"];
	            $datos = $this->request->data["Pqr"];
	            $options = array(
	                'to'        => $users,
	                'template'  => 'pqr_creado',
	                'subject'   => 'Se ha creado el PQRS #'.$this->request->data["Pqr"]["code"],
	                'vars'      => ["id" => $this->Pqr->id,"type" => 1]
	            );


	            $this->sendMail($options, true);

				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('PQRS no fue guardado correctamente'),'Flash/error');
			}
		}
		$this->set("clienteActivo",$clienteActivo);
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Pqr->exists($id)) {
			throw new NotFoundException(__('Invalid pqr'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Pqr->save($this->request->data)) {
				$this->Flash->success(__('The pqr has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The pqr could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Pqr.' . $this->Pqr->primaryKey => $id));
			$this->request->data = $this->Pqr->find('first', $options);
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
		$this->Pqr->id = $id;
		if (!$this->Pqr->exists()) {
			throw new NotFoundException(__('Invalid pqr'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Pqr->delete()) {
			$this->Flash->success(__('The pqr has been deleted.'));
		} else {
			$this->Flash->error(__('The pqr could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

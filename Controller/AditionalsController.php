<?php
App::uses('AppController', 'Controller');

class AditionalsController extends AppController {


	public $components = array('Paginator');


	public function index() {
		$this->Aditional->recursive = 0;
		$this->set('aditionals', $this->Paginator->paginate());
	}


	public function view($id = null) {
		if (!$this->Aditional->exists($id)) {
			throw new NotFoundException(__('Invalid aditional'));
		}
		$options = array('conditions' => array('Aditional.' . $this->Aditional->primaryKey => $id));
		$this->set('aditional', $this->Aditional->find('first', $options));
	}


	public function add($otro = null) {
		if ($this->request->is('post')) {
			$this->Aditional->create();
			if ($this->Aditional->save($this->request->data)) {
				$this->Session->setFlash(__('El accesorio fue guardado correctamente'),'Flash/success');
				if (!is_null($otro)) {
					return $this->redirect(array('action' => 'add',"controller" => "technical_services"));
				}
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El accesorio ni fue guardado correctamente. Intente de nuevo por favor.'),'Flash/error');
			}
		}
		$users = $this->Aditional->User->find('list');
		$this->set(compact('users'));
	}


	public function edit($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Aditional->exists($id)) {
			throw new NotFoundException(__('Invalid aditional'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Aditional->save($this->request->data)) {
				$this->Session->setFlash(__('El accesorio fue guardado correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El accesorio ni fue guardado correctamente. Intente de nuevo por favor.'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Aditional.' . $this->Aditional->primaryKey => $id));
			$this->request->data = $this->Aditional->find('first', $options);
		}
		$users = $this->Aditional->User->find('list');
		$this->set(compact('users'));
	}


}

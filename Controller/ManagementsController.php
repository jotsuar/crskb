<?php
App::uses('AppController', 'Controller');
/**
 * Managements Controller
 *
 * @property Management $Management
 * @property PaginatorComponent $Paginator
 */
class ManagementsController extends AppController {

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
		$this->Management->recursive = 0;
		$this->set('managements', $this->Paginator->paginate());
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
		if (!$this->Management->exists($id)) {
			throw new NotFoundException(__('Invalid management'));
		}
		$options = array('conditions' => array('Management.' . $this->Management->primaryKey => $id));
		$this->set('management', $this->Management->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Management->create();
			if ($this->Management->save($this->request->data)) {
				$this->Session->setFlash(__('Informe guardado correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El informe no fue guardado correctamente'),'Flash/error');
			}
		}
		$this->set(compact('users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Management->exists($id)) {
			throw new NotFoundException(__('Invalid management'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Management->save($this->request->data)) {
				$this->Flash->success(__('The management has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The management could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Management.' . $this->Management->primaryKey => $id));
			$this->request->data = $this->Management->find('first', $options);
		}
		$users = $this->Management->User->find('list');
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
		if (!$this->Management->exists($id)) {
			throw new NotFoundException(__('Invalid management'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Management->delete($id)) {
			$this->Flash->success(__('The management has been deleted.'));
		} else {
			$this->Flash->error(__('The management could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

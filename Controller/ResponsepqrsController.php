<?php
App::uses('AppController', 'Controller');
/**
 * Responsepqrs Controller
 *
 * @property Responsepqr $Responsepqr
 * @property PaginatorComponent $Paginator
 */
class ResponsepqrsController extends AppController {

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
		$this->Responsepqr->recursive = 0;
		$this->set('responsepqrs', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Responsepqr->exists($id)) {
			throw new NotFoundException(__('Invalid responsepqr'));
		}
		$options = array('conditions' => array('Responsepqr.' . $this->Responsepqr->primaryKey => $id));
		$this->set('responsepqr', $this->Responsepqr->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Responsepqr->create();
			if ($this->Responsepqr->save($this->request->data)) {
				$this->Flash->success(__('The responsepqr has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The responsepqr could not be saved. Please, try again.'));
			}
		}
		$users = $this->Responsepqr->User->find('list');
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
		if (!$this->Responsepqr->exists($id)) {
			throw new NotFoundException(__('Invalid responsepqr'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Responsepqr->save($this->request->data)) {
				$this->Flash->success(__('The responsepqr has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The responsepqr could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Responsepqr.' . $this->Responsepqr->primaryKey => $id));
			$this->request->data = $this->Responsepqr->find('first', $options);
		}
		$users = $this->Responsepqr->User->find('list');
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
		$this->Responsepqr->id = $id;
		if (!$this->Responsepqr->exists()) {
			throw new NotFoundException(__('Invalid responsepqr'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Responsepqr->delete()) {
			$this->Flash->success(__('The responsepqr has been deleted.'));
		} else {
			$this->Flash->error(__('The responsepqr could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

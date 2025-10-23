<?php
App::uses('AppController', 'Controller');
/**
 * Envoices Controller
 *
 * @property Envoice $Envoice
 * @property PaginatorComponent $Paginator
 */
class EnvoicesController extends AppController {

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
		$this->Envoice->recursive = 0;
		$this->set('envoices', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Envoice->exists($id)) {
			throw new NotFoundException(__('Invalid envoice'));
		}
		$options = array('conditions' => array('Envoice.' . $this->Envoice->primaryKey => $id));
		$this->set('envoice', $this->Envoice->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Envoice->create();
			if ($this->Envoice->save($this->request->data)) {
				$this->Flash->success(__('The envoice has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The envoice could not be saved. Please, try again.'));
			}
		}
		$users = $this->Envoice->User->find('list');
		$orders = $this->Envoice->Order->find('list');
		$products = $this->Envoice->Product->find('list');
		$this->set(compact('users', 'orders', 'products'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Envoice->exists($id)) {
			throw new NotFoundException(__('Invalid envoice'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Envoice->save($this->request->data)) {
				$this->Flash->success(__('The envoice has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The envoice could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Envoice.' . $this->Envoice->primaryKey => $id));
			$this->request->data = $this->Envoice->find('first', $options);
		}
		$users = $this->Envoice->User->find('list');
		$orders = $this->Envoice->Order->find('list');
		$products = $this->Envoice->Product->find('list');
		$this->set(compact('users', 'orders', 'products'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Envoice->exists($id)) {
			throw new NotFoundException(__('Invalid envoice'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Envoice->delete($id)) {
			$this->Flash->success(__('The envoice has been deleted.'));
		} else {
			$this->Flash->error(__('The envoice could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

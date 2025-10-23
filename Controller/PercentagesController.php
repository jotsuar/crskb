<?php
App::uses('AppController', 'Controller');
/**
 * Percentages Controller
 *
 * @property Percentage $Percentage
 * @property PaginatorComponent $Paginator
 */
class PercentagesController extends AppController {

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
		$this->Percentage->recursive = 0;
		$this->set('percentages', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Percentage->exists($id)) {
			throw new NotFoundException(__('Invalid percentage'));
		}
		$options = array('conditions' => array('Percentage.' . $this->Percentage->primaryKey => $id));
		$this->set('percentage', $this->Percentage->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Percentage->create();
			if ($this->Percentage->save($this->request->data)) {
				$this->Flash->success(__('The percentage has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The percentage could not be saved. Please, try again.'));
			}
		}
		$users = $this->Percentage->User->find('list');
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
		if (!$this->Percentage->exists($id)) {
			throw new NotFoundException(__('Invalid percentage'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Percentage->save($this->request->data)) {
				$this->Flash->success(__('The percentage has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The percentage could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Percentage.' . $this->Percentage->primaryKey => $id));
			$this->request->data = $this->Percentage->find('first', $options);
		}
		$users = $this->Percentage->User->find('list');
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
		$this->Percentage->id = $id;
		if (!$this->Percentage->exists()) {
			throw new NotFoundException(__('Invalid percentage'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Percentage->delete()) {
			$this->Flash->success(__('The percentage has been deleted.'));
		} else {
			$this->Flash->error(__('The percentage could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

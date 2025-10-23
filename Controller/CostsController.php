<?php
App::uses('AppController', 'Controller');
/**
 * Costs Controller
 *
 * @property Cost $Cost
 * @property PaginatorComponent $Paginator
 */
class CostsController extends AppController {

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
		$this->Cost->recursive = 0;
		$this->set('costs', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Cost->exists($id)) {
			throw new NotFoundException(__('Invalid cost'));
		}
		$options = array('conditions' => array('Cost.' . $this->Cost->primaryKey => $id));
		$this->set('cost', $this->Cost->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Cost->create();
			if ($this->Cost->save($this->request->data)) {
				$this->Flash->success(__('The cost has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cost could not be saved. Please, try again.'));
			}
		}
		$products = $this->Cost->Product->find('list');
		$users = $this->Cost->User->find('list');
		$this->set(compact('products', 'users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Cost->exists($id)) {
			throw new NotFoundException(__('Invalid cost'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Cost->save($this->request->data)) {
				$this->Flash->success(__('The cost has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The cost could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Cost.' . $this->Cost->primaryKey => $id));
			$this->request->data = $this->Cost->find('first', $options);
		}
		$products = $this->Cost->Product->find('list');
		$users = $this->Cost->User->find('list');
		$this->set(compact('products', 'users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Cost->id = $id;
		if (!$this->Cost->exists()) {
			throw new NotFoundException(__('Invalid cost'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Cost->delete()) {
			$this->Flash->success(__('The cost has been deleted.'));
		} else {
			$this->Flash->error(__('The cost could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

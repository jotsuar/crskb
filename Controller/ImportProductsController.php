<?php
App::uses('AppController', 'Controller');
/**
 * ImportProducts Controller
 *
 * @property ImportProduct $ImportProduct
 * @property PaginatorComponent $Paginator
 */
class ImportProductsController extends AppController {

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
		$this->ImportProduct->recursive = 0;
		$this->set('importProducts', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ImportProduct->exists($id)) {
			throw new NotFoundException(__('Invalid import product'));
		}
		$options = array('conditions' => array('ImportProduct.' . $this->ImportProduct->primaryKey => $id));
		$this->set('importProduct', $this->ImportProduct->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ImportProduct->create();
			if ($this->ImportProduct->save($this->request->data)) {
				$this->Flash->success(__('The import product has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The import product could not be saved. Please, try again.'));
			}
		}
		$imports = $this->ImportProduct->Import->find('list');
		$products = $this->ImportProduct->Product->find('list');
		$quotationsProducts = $this->ImportProduct->QuotationsProduct->find('list');
		$prospectiveUsers = $this->ImportProduct->ProspectiveUser->find('list');
		$this->set(compact('imports', 'products', 'quotationsProducts', 'prospectiveUsers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ImportProduct->exists($id)) {
			throw new NotFoundException(__('Invalid import product'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ImportProduct->save($this->request->data)) {
				$this->Flash->success(__('The import product has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The import product could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ImportProduct.' . $this->ImportProduct->primaryKey => $id));
			$this->request->data = $this->ImportProduct->find('first', $options);
		}
		$imports = $this->ImportProduct->Import->find('list');
		$products = $this->ImportProduct->Product->find('list');
		$quotationsProducts = $this->ImportProduct->QuotationsProduct->find('list');
		$prospectiveUsers = $this->ImportProduct->ProspectiveUser->find('list');
		$this->set(compact('imports', 'products', 'quotationsProducts', 'prospectiveUsers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ImportProduct->id = $id;
		if (!$this->ImportProduct->exists()) {
			throw new NotFoundException(__('Invalid import product'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ImportProduct->delete()) {
			$this->Flash->success(__('The import product has been deleted.'));
		} else {
			$this->Flash->error(__('The import product could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

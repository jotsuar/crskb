<?php
App::uses('AppController', 'Controller');
/**
 * Rejects Controller
 *
 * @property Reject $Reject
 * @property PaginatorComponent $Paginator
 */
class RejectsController extends AppController {

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
		$this->Reject->recursive = 0;
		$this->set('rejects', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->layout = false;
		$this->loadModel("ImportProductsDetail");
		$id = $this->desencriptarCadena($id);
		if (!$this->Reject->exists($id)) {
			throw new NotFoundException(__('Invalid reject'));
		}
		$options = array('conditions' => array('Reject.' . $this->Reject->primaryKey => $id));
		$reject = $this->Reject->find('first', $options);
		$this->set('reject', $reject);
		$detalles = $this->ImportProductsDetail->findAllByProductIdAndImportId($reject["Product"]["id"],$reject["Import"]["id"]);
		$this->set('detalles', $detalles);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Reject->create();
			if ($this->Reject->save($this->request->data)) {
				$this->Flash->success(__('The reject has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The reject could not be saved. Please, try again.'));
			}
		}
		$products = $this->Reject->Product->find('list');
		$imports = $this->Reject->Import->find('list');
		$users = $this->Reject->User->find('list');
		$this->set(compact('products', 'imports', 'users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Reject->exists($id)) {
			throw new NotFoundException(__('Invalid reject'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Reject->save($this->request->data)) {
				$this->Flash->success(__('The reject has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The reject could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Reject.' . $this->Reject->primaryKey => $id));
			$this->request->data = $this->Reject->find('first', $options);
		}
		$products = $this->Reject->Product->find('list');
		$imports = $this->Reject->Import->find('list');
		$users = $this->Reject->User->find('list');
		$this->set(compact('products', 'imports', 'users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Reject->id = $id;
		if (!$this->Reject->exists()) {
			throw new NotFoundException(__('Invalid reject'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Reject->delete()) {
			$this->Flash->success(__('The reject has been deleted.'));
		} else {
			$this->Flash->error(__('The reject could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

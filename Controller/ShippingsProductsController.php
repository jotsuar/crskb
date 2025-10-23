<?php
App::uses('AppController', 'Controller');
/**
 * ShippingsProducts Controller
 *
 * @property ShippingsProduct $ShippingsProduct
 * @property PaginatorComponent $Paginator
 */
class ShippingsProductsController extends AppController {

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
		$this->ShippingsProduct->recursive = 0;
		$this->set('shippingsProducts', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ShippingsProduct->exists($id)) {
			throw new NotFoundException(__('Invalid shippings product'));
		}
		$options = array('conditions' => array('ShippingsProduct.' . $this->ShippingsProduct->primaryKey => $id));
		$this->set('shippingsProduct', $this->ShippingsProduct->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ShippingsProduct->create();
			if ($this->ShippingsProduct->save($this->request->data)) {
				$this->Flash->success(__('The shippings product has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The shippings product could not be saved. Please, try again.'));
			}
		}
		$shippings = $this->ShippingsProduct->Shipping->find('list');
		$products = $this->ShippingsProduct->Product->find('list');
		$this->set(compact('shippings', 'products'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ShippingsProduct->exists($id)) {
			throw new NotFoundException(__('Invalid shippings product'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ShippingsProduct->save($this->request->data)) {
				$this->Flash->success(__('The shippings product has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The shippings product could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ShippingsProduct.' . $this->ShippingsProduct->primaryKey => $id));
			$this->request->data = $this->ShippingsProduct->find('first', $options);
		}
		$shippings = $this->ShippingsProduct->Shipping->find('list');
		$products = $this->ShippingsProduct->Product->find('list');
		$this->set(compact('shippings', 'products'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ShippingsProduct->id = $id;
		if (!$this->ShippingsProduct->exists()) {
			throw new NotFoundException(__('Invalid shippings product'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ShippingsProduct->delete()) {
			$this->Flash->success(__('The shippings product has been deleted.'));
		} else {
			$this->Flash->error(__('The shippings product could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

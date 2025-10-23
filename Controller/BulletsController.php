<?php
App::uses('AppController', 'Controller');
/**
 * Bullets Controller
 *
 * @property Bullet $Bullet
 * @property PaginatorComponent $Paginator
 */
class BulletsController extends AppController {

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
		$this->Bullet->recursive = 0;
		$this->set('bullets', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Bullet->exists($id)) {
			throw new NotFoundException(__('Invalid bullet'));
		}
		$options = array('conditions' => array('Bullet.' . $this->Bullet->primaryKey => $id));
		$this->set('bullet', $this->Bullet->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($productId) {
		$this->layout = false;
		if ($this->request->is('post')) {
			$this->Bullet->create();
			if ($this->Bullet->save($this->request->data)) {
				$this->Flash->success(__('The bullet has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The bullet could not be saved. Please, try again.'));
			}
		}
		$products = $this->Bullet->Product->find('list');
		$this->set(compact('products','productId'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Bullet->exists($id)) {
			throw new NotFoundException(__('Invalid bullet'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Bullet->save($this->request->data)) {
				$this->Flash->success(__('The bullet has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The bullet could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Bullet.' . $this->Bullet->primaryKey => $id));
			$this->request->data = $this->Bullet->find('first', $options);
		}
		$products = $this->Bullet->Product->find('list');
		$this->set(compact('products'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Bullet->exists($id)) {
			throw new NotFoundException(__('Invalid bullet'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Bullet->delete($id)) {
			$this->Flash->success(__('The bullet has been deleted.'));
		} else {
			$this->Flash->error(__('The bullet could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

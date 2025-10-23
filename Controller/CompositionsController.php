<?php
App::uses('AppController', 'Controller');
/**
 * Compositions Controller
 *
 * @property Composition $Composition
 * @property PaginatorComponent $Paginator
 */
class CompositionsController extends AppController {

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
		$this->Composition->recursive = 0;
		$this->set('compositions', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Composition->exists($id)) {
			throw new NotFoundException(__('Invalid composition'));
		}
		$options = array('conditions' => array('Composition.' . $this->Composition->primaryKey => $id));
		$this->set('composition', $this->Composition->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Composition->create();
			if ($this->Composition->save($this->request->data)) {
				$this->Flash->success(__('The composition has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The composition could not be saved. Please, try again.'));
			}
		}
		$products = $this->Composition->Product->find('list');
		$this->set(compact('products'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Composition->exists($id)) {
			throw new NotFoundException(__('Invalid composition'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Composition->save($this->request->data)) {
				$this->Flash->success(__('The composition has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The composition could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Composition.' . $this->Composition->primaryKey => $id));
			$this->request->data = $this->Composition->find('first', $options);
		}
		$products = $this->Composition->Product->find('list');
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
		$this->Composition->id = $id;
		if (!$this->Composition->exists()) {
			throw new NotFoundException(__('Invalid composition'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Composition->delete()) {
			$this->Flash->success(__('The composition has been deleted.'));
		} else {
			$this->Flash->error(__('The composition could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

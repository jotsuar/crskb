<?php
App::uses('AppController', 'Controller');
/**
 * CarpetaDetalles Controller
 *
 * @property CarpetaDetalle $CarpetaDetalle
 * @property PaginatorComponent $Paginator
 */
class CarpetaDetallesController extends AppController {

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
		$this->CarpetaDetalle->recursive = 0;
		$this->set('carpetaDetalles', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CarpetaDetalle->exists($id)) {
			throw new NotFoundException(__('Invalid carpeta detalle'));
		}
		$options = array('conditions' => array('CarpetaDetalle.' . $this->CarpetaDetalle->primaryKey => $id));
		$this->set('carpetaDetalle', $this->CarpetaDetalle->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->CarpetaDetalle->create();
			if ($this->CarpetaDetalle->save($this->request->data)) {
				$this->Flash->success(__('The carpeta detalle has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The carpeta detalle could not be saved. Please, try again.'));
			}
		}
		$carpetas = $this->CarpetaDetalle->Carpetum->find('list');
		$documents = $this->CarpetaDetalle->Document->find('list');
		$blogs = $this->CarpetaDetalle->Blog->find('list');
		$this->set(compact('carpetas', 'documents', 'blogs'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CarpetaDetalle->exists($id)) {
			throw new NotFoundException(__('Invalid carpeta detalle'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CarpetaDetalle->save($this->request->data)) {
				$this->Flash->success(__('The carpeta detalle has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The carpeta detalle could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CarpetaDetalle.' . $this->CarpetaDetalle->primaryKey => $id));
			$this->request->data = $this->CarpetaDetalle->find('first', $options);
		}
		$carpetas = $this->CarpetaDetalle->Carpetum->find('list');
		$documents = $this->CarpetaDetalle->Document->find('list');
		$blogs = $this->CarpetaDetalle->Blog->find('list');
		$this->set(compact('carpetas', 'documents', 'blogs'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->CarpetaDetalle->id = $id;
		if (!$this->CarpetaDetalle->exists()) {
			throw new NotFoundException(__('Invalid carpeta detalle'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->CarpetaDetalle->delete()) {
			$this->Flash->success(__('The carpeta detalle has been deleted.'));
		} else {
			$this->Flash->error(__('The carpeta detalle could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

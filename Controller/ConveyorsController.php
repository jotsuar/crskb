<?php
App::uses('AppController', 'Controller');
/**
 * Conveyors Controller
 *
 * @property Conveyor $Conveyor
 * @property PaginatorComponent $Paginator
 */
class ConveyorsController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$this->Conveyor->recursive = 0;
		$this->set('conveyors', $this->Paginator->paginate());
	}

	public function view($id = null) {
		$id = $this->desencriptarCadena($id);
		if (!$this->Conveyor->exists($id)) {
			throw new NotFoundException(__('Invalid conveyor'));
		}
		$options = array('conditions' => array('Conveyor.' . $this->Conveyor->primaryKey => $id));
		$this->set('conveyor', $this->Conveyor->find('first', $options));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Conveyor->create();
			if ($this->Conveyor->save($this->request->data)) {
				$this->Session->setFlash(__('La transportadora fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La transportadora no fue guardada correctamente'),'Flash/error');
			}
		}
	}

	public function edit($id = null) {
		$id = $this->desencriptarCadena($id);
		if (!$this->Conveyor->exists($id)) {
			throw new NotFoundException(__('Invalid conveyor'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Conveyor->save($this->request->data)) {
				$this->Session->setFlash(__('La transportadora fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La transportadora no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Conveyor.' . $this->Conveyor->primaryKey => $id));
			$this->request->data = $this->Conveyor->find('first', $options);
		}
	}

	public function delete($id = null) {
		$id = $this->desencriptarCadena($id);
		$this->Conveyor->id = $id;
		if (!$this->Conveyor->exists()) {
			throw new NotFoundException(__('Invalid conveyor'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Conveyor->delete()) {
			$this->Flash->success(__('The conveyor has been deleted.'));
		} else {
			$this->Flash->error(__('The conveyor could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

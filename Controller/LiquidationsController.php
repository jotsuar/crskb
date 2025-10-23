<?php
App::uses('AppController', 'Controller');

/**
 * Liquidations Controller
 *
 * @property Liquidation $Liquidation
 * @property PaginatorComponent $Paginator
 */

class LiquidationsController extends AppController {

	public $components = array('Paginator');

	/**
	 * index method
	 *
	 * @return void
	 */

	public function index() {
		$this->Liquidation->recursive = 0;
		$this->paginate 			= array( 'order' 	=> ["Liquidation.id" => "DESC"] );
		$this->set('liquidations', $this->Paginator->paginate());
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */

	public function view($id = null) {
		if (!$this->Liquidation->exists($id)) {
			throw new NotFoundException(__('Invalid liquidation'));
		}
		$options = array('conditions' => array('Liquidation.' . $this->Liquidation->primaryKey => $id));
		$this->set('liquidation', $this->Liquidation->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */

	public function add() {
		if ($this->request->is('post')) {
			$this->Liquidation->create();
			if ($this->Liquidation->save($this->request->data)) {
				$this->Flash->success(__('The liquidation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The liquidation could not be saved. Please, try again.'));
			}
		}
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */

	public function edit($id = null) {
		if (!$this->Liquidation->exists($id)) {
			throw new NotFoundException(__('Invalid liquidation'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Liquidation->save($this->request->data)) {
				$this->Flash->success(__('The liquidation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The liquidation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Liquidation.' . $this->Liquidation->primaryKey => $id));
			$this->request->data = $this->Liquidation->find('first', $options);
		}
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */

	public function delete($id = null) {
		if (!$this->Liquidation->exists($id)) {
			throw new NotFoundException(__('Invalid liquidation'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Liquidation->delete($id)) {
			$this->Flash->success(__('The liquidation has been deleted.'));
		} else {
			$this->Flash->error(__('The liquidation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * Binnacles Controller
 *
 * @property Binnacle $Binnacle
 * @property PaginatorComponent $Paginator
 */
class BinnaclesController extends AppController {

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
	public function index($technical_id) {
		$this->layout = false;
		$this->set('binnacles', $this->Binnacle->find("all",["conditions"=>["Binnacle.technical_service_id"=>$technical_id]]));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Binnacle->exists($id)) {
			throw new NotFoundException(__('Invalid binnacle'));
		}
		$options = array('conditions' => array('Binnacle.' . $this->Binnacle->primaryKey => $id));
		$this->set('binnacle', $this->Binnacle->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($technical_id) {
		$this->layout = false;
		if ($this->request->is('post')) {
			$this->Binnacle->create();
			$this->request->data["Binnacle"]["date_ini"] = date("Y-m-d H:i:s",strtotime($this->request->data["Binnacle"]["date_ini"]));
			$this->request->data["Binnacle"]["date_end"] = date("Y-m-d H:i:s",strtotime($this->request->data["Binnacle"]["date_end"]));
			if ($this->Binnacle->save($this->request->data)) {
				$this->Session->setFlash('Bitácora guardada correctamente','Flash/success');
			} else {
				$this->Session->setFlash('No es posible guardar la bitácora.','Flash/error');
			}
		}
		$this->set(compact('technicalServices', 'users','technical_id'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Binnacle->exists($id)) {
			throw new NotFoundException(__('Invalid binnacle'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Binnacle->save($this->request->data)) {
				$this->Flash->success(__('The binnacle has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The binnacle could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Binnacle.' . $this->Binnacle->primaryKey => $id));
			$this->request->data = $this->Binnacle->find('first', $options);
		}
		$technicalServices = $this->Binnacle->TechnicalService->find('list');
		$users = $this->Binnacle->User->find('list');
		$this->set(compact('technicalServices', 'users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Binnacle->id = $id;
		if (!$this->Binnacle->exists()) {
			throw new NotFoundException(__('Invalid binnacle'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Binnacle->delete()) {
			$this->Flash->success(__('The binnacle has been deleted.'));
		} else {
			$this->Flash->error(__('The binnacle could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

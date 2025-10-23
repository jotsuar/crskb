<?php
App::uses('AppController', 'Controller');
/**
 * Goals Controller
 *
 * @property Goal $Goal
 * @property PaginatorComponent $Paginator
 */
class GoalsController extends AppController {

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
		$this->Goal->recursive = 0;
		$this->set('goals', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Goal->exists($id)) {
			throw new NotFoundException(__('Invalid goal'));
		}
		$options = array('conditions' => array('Goal.' . $this->Goal->primaryKey => $id));
		$this->set('goal', $this->Goal->find('first', $options));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Goal->create();
			if ($this->Goal->save($this->request->data)) {
				$this->Session->setFlash(__('La meta fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La meta no fue guardada correctamente'),'Flash/error');
			}
		}
		$users = $this->Goal->User->role_asesor_comercial_user_true_agrupado();
		$this->set(compact('users'));
	}


	public function edit($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Goal->exists($id)) {
			throw new NotFoundException(__('Invalid goal'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Goal->save($this->request->data)) {
				$this->Session->setFlash(__('La meta fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La meta no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Goal.' . $this->Goal->primaryKey => $id));
			$this->request->data = $this->Goal->find('first', $options);
		}
		$users = $this->Goal->User->find('list',["conditions"=>["User.state" => 1]]);
		$this->set(compact('users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Goal->id = $id;
		if (!$this->Goal->exists()) {
			throw new NotFoundException(__('Invalid goal'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Goal->delete()) {
			$this->Flash->success(__('The goal has been deleted.'));
		} else {
			$this->Flash->error(__('The goal could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

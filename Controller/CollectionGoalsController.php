<?php
App::uses('AppController', 'Controller');
/**
 * CollectionGoals Controller
 *
 * @property CollectionGoal $CollectionGoal
 * @property PaginatorComponent $Paginator
 */
class CollectionGoalsController extends AppController {

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
		$this->CollectionGoal->recursive = 0;
		$this->set('collectionGoals', $this->Paginator->paginate());
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
		if (!$this->CollectionGoal->exists($id)) {
			throw new NotFoundException(__('Invalid collection goal'));
		}
		$options = array('conditions' => array('CollectionGoal.' . $this->CollectionGoal->primaryKey => $id));
		$this->set('goal', $this->CollectionGoal->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->CollectionGoal->create();
			if ($this->CollectionGoal->save($this->request->data)) {
				$this->Session->setFlash(__('La meta fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La meta no fue guardada correctamente'),'Flash/error');
			}
		}
		$users = $this->CollectionGoal->User->find('list');
		$this->set(compact('users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->CollectionGoal->exists($id)) {
			throw new NotFoundException(__('Invalid collection goal'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CollectionGoal->save($this->request->data)) {
				$this->Session->setFlash(__('La meta fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La meta no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('CollectionGoal.' . $this->CollectionGoal->primaryKey => $id));
			$this->request->data = $this->CollectionGoal->find('first', $options);
		}
		$users = $this->CollectionGoal->User->find('list');
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
		if (!$this->CollectionGoal->exists($id)) {
			throw new NotFoundException(__('Invalid collection goal'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->CollectionGoal->delete($id)) {
			$this->Flash->success(__('The collection goal has been deleted.'));
		} else {
			$this->Flash->error(__('The collection goal could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

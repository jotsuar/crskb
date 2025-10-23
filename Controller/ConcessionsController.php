<?php
App::uses('AppController', 'Controller');
/**
 * Concessions Controller
 *
 * @property Concession $Concession
 * @property PaginatorComponent $Paginator
 */
class ConcessionsController extends AppController {

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
		$get 		   = isset($this->request->query["q"]) && !empty($this->request->query["q"]) ? $this->request->query : array() ;
		if(!empty($get["q"]) ){
			$conditions	= array('OR' => 
				array(
				        'LOWER(Concession.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
				    )
			);
    	}else{
    		$conditions = [];
    	}
		$this->Concession->recursive = 0;
		$this->paginate 			 = array( 'conditions' 	=> $conditions );
		$this->set('concessions', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Concession->exists($id)) {
			throw new NotFoundException(__('Invalid concession'));
		}
		$options = array('conditions' => array('Concession.' . $this->Concession->primaryKey => $id));
		$this->set('concession', $this->Concession->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Concession->create();
			if ($this->Concession->save($this->request->data)) {
				$this->Session->setFlash(__('La información fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La información no fue gardada. Intente de nuevo por favor.'),'Flash/error');
			}
		}
		$clientsLegals = $this->Concession->ClientsLegal->find('list');
		$this->set(compact('clientsLegals'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Concession->exists($id)) {
			throw new NotFoundException(__('Invalid concession'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Concession->save($this->request->data)) {
				$this->Session->setFlash(__('La información fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La información no fue gardada. Intente de nuevo por favor.'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Concession.' . $this->Concession->primaryKey => $id));
			$this->request->data = $this->Concession->find('first', $options);
		}
		$clientsLegals = $this->Concession->ClientsLegal->find('list');
		$this->set(compact('clientsLegals'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Concession->exists($id)) {
			throw new NotFoundException(__('Invalid concession'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Concession->delete($id)) {
			$this->Flash->success(__('The concession has been deleted.'));
		} else {
			$this->Flash->error(__('The concession could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

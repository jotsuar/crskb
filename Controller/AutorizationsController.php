<?php
App::uses('AppController', 'Controller');
/**
 * Autorizations Controller
 *
 * @property Autorization $Autorization
 * @property PaginatorComponent $Paginator
 */
class AutorizationsController extends AppController {

	public function beforeFilter() {
        parent::beforeFilter();
        if (!AuthComponent::user("id") || !in_array(AuthComponent::user("email"), ["jotsuar@gmail.com", "gerencia@almacendelpintor.com",'logistica@kebco.co'])  ) {
        	$this->redirect(["action"=>"index",'controller' => 'prospective_users']);	
        }
    }

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
		$this->Autorization->recursive = 0;
		$this->set('autorizations', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Autorization->exists($id)) {
			throw new NotFoundException(__('Invalid autorization'));
		}
		$options = array('conditions' => array('Autorization.' . $this->Autorization->primaryKey => $id));
		$this->set('autorization', $this->Autorization->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Autorization->create();
			if ($this->Autorization->save($this->request->data)) {
				$this->Session->setFlash(__('La autorización fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La autorización no fue guardada correctamente'),'Flash/error');
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
		if (!$this->Autorization->exists($id)) {
			throw new NotFoundException(__('Invalid autorization'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Autorization->save($this->request->data)) {
				$this->Session->setFlash(__('La autorización fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La autorización no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Autorization.' . $this->Autorization->primaryKey => $id));
			$this->request->data = $this->Autorization->find('first', $options);
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
		if (!$this->Autorization->exists($id)) {
			throw new NotFoundException(__('Invalid autorization'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Autorization->delete($id)) {
			$this->Session->setFlash(__('La autorización fue guardada correctamente'),'Flash/success');
		} else {
			$this->Session->setFlash(__('La autorización no fue guardada correctamente'),'Flash/error');
		}
		return $this->redirect(array('action' => 'index'));
	}
}

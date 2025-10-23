<?php
App::uses('AppController', 'Controller');
/**
 * Times Controller
 *
 * @property Time $Time
 * @property PaginatorComponent $Paginator
 */
class TimesController extends AppController {

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
		$this->Time->recursive = 0;
		$this->set('times', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Time->exists($id)) {
			throw new NotFoundException(__('Invalid time'));
		}
		$options = array('conditions' => array('Time.' . $this->Time->primaryKey => $id));
		$this->set('time', $this->Time->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Time->create();
			if ($this->Time->save($this->request->data)) {
				$this->Session->setFlash(__('La configuración fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La configuración no fue guardada correctamente'),'Flash/error');
			}
		}
		$users_ids = $this->Time->find("list",["fields"=>["user_id","user_id"]]);
		$users = $this->Time->User->find('list',["conditions"=>["id !="=>$users_ids,"state"=>1]]);
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
		$options 			= array('conditions' => array('Time.' . $this->Time->primaryKey => $id));
		$time 				= $this->Time->find('first', $options);

		if (!$this->Time->exists($id)) {
			throw new NotFoundException(__('Invalid time'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Time->save($this->request->data)) {
				$this->Session->setFlash(__('La configuración fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La configuración no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$this->request->data = $time;
		}
		$fechaInicioReporte = $time["Time"]["date_ini"];
		$fechaFinReporte = $time["Time"]["date_end"];

		$users_ids = $this->Time->find("list",["fields"=>["user_id","user_id"], ]);
		$users = $this->Time->User->find('list',["conditions"=>["id !="=>$users_ids,"state"=>1]]);
		$users[$time["User"]["id"]] = $time["User"]["name"];
		$this->set(compact('users','fechaInicioReporte','fechaFinReporte'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Time->exists($id)) {
			throw new NotFoundException(__('Invalid time'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Time->delete($id)) {
			$this->Flash->success(__('The time has been deleted.'));
		} else {
			$this->Flash->error(__('The time could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * Notices Controller
 *
 * @property Notice $Notice
 * @property PaginatorComponent $Paginator
 */
class NoticesController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$this->Notice->recursive = 0;
		$this->set('notices', $this->Paginator->paginate());
	}

	public function list_data(){
		$this->layout = false;
		$notices = $this->Notice->find("list",["conditions"=>["Notice.user_id"=>AuthComponent::user("id")]]);
		$this->set("notices",$notices);
	}

	public function view($id = null) {
		$this->autoRender = false;
		if (!$this->Notice->exists($id)) {
			throw new NotFoundException(__('Invalid notice'));
		}
		$options = array('conditions' => array('Notice.' . $this->Notice->primaryKey => $id), "recursive" => -1);
		$notice  = $this->Notice->find('first', $options);
		return json_encode($notice);
	}

	public function add() {
		$this->layout = false;
		if ($this->request->is('post')) {
			$this->Notice->create();
			if ($this->Notice->save($this->request->data)) {
				$this->Session->setFlash('Nota guardada correctamente.', 'Flash/success');
			} else {
				$this->Session->setFlash('Nota no guardada correctamente.', 'Flash/error');
			}
		}
	}

	public function edit($id = null) {
		if (!$this->Notice->exists($id)) {
			throw new NotFoundException(__('Invalid notice'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Notice->save($this->request->data)) {
				$this->Flash->success(__('The notice has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The notice could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Notice.' . $this->Notice->primaryKey => $id));
			$this->request->data = $this->Notice->find('first', $options);
		}
		$users = $this->Notice->User->find('list');
		$this->set(compact('users'));
	}


	public function delete($id = null) {
		$this->Notice->id = $id;
		if (!$this->Notice->exists()) {
			throw new NotFoundException(__('Invalid notice'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Notice->delete()) {
			$this->Flash->success(__('The notice has been deleted.'));
		} else {
			$this->Flash->error(__('The notice could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

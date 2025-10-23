<?php
App::uses('AppController', 'Controller');
/**
 * MailingLists Controller
 *
 * @property MailingList $MailingList
 * @property PaginatorComponent $Paginator
 */
class MailingListsController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$conditions = array();

		if(isset($this->request->query["type"])){
			$conditions = array("MailingList.type" => $this->request->query["type"]);
		}
		$this->paginate = array("conditions" => $conditions);
		$this->set('mailingLists', $this->paginate("MailingList"));
	}

	public function saveFromRemarketing(){
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->MailingList->create();
			if ($this->MailingList->save($this->request->data)) {
				$this->Session->setFlash(__('Lista creada correctamente'),'Flash/success');
				return 1;
			} else {
				$this->Session->setFlash(__('La lista no fue creada correctamente.'),'Flash/error');
				return 0;
			}
		}
	}

	public function view($id = null) {
		$id = $this->desencriptarCadena($id);
		if (!$this->MailingList->exists($id)) {
			throw new NotFoundException(__('Invalid mailing list'));
		}
		$options = array('conditions' => array('MailingList.' . $this->MailingList->primaryKey => $id));
		$this->set('mailingList', $this->MailingList->find('first', $options));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->MailingList->create();
			if ($this->MailingList->save($this->request->data)) {
				$this->Session->setFlash(__('Lista creada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La lista no fue creada correctamente.'),'Flash/error');
			}
		}
	}


	public function edit($id = null) {
		$id = $this->desencriptarCadena($id);
		if (!$this->MailingList->exists($id)) {
			throw new NotFoundException(__('Invalid mailing list'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->MailingList->save($this->request->data)) {
				$this->Flash->success(__('The mailing list has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mailing list could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('MailingList.' . $this->MailingList->primaryKey => $id));
			$this->request->data = $this->MailingList->find('first', $options);
		}
	}

	public function delete($id = null) {
		$this->MailingList->id = $id;
		if (!$this->MailingList->exists()) {
			throw new NotFoundException(__('Invalid mailing list'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->MailingList->delete()) {
			$this->Flash->success(__('The mailing list has been deleted.'));
		} else {
			$this->Flash->error(__('The mailing list could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * Adresses Controller
 *
 * @property Adress $Adress
 * @property PaginatorComponent $Paginator
 */
class AdressesController extends AppController {

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
		$this->Adress->recursive = 0;
		$this->set('adresses', $this->Paginator->paginate());
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		if (!$this->Adress->exists($id)) {
			throw new NotFoundException(__('Invalid adress'));
		}
		$options = array('conditions' => array('Adress.' . $this->Adress->primaryKey => $id));
		$this->set('adress', $this->Adress->find('first', $options));
	}

	public function admin() {
		$this->layout = false;

		if($this->request->is("ajax")){			
			if($this->request->data["id"]){
				$this->request->data = $this->Adress->findById($this->request->data);
			}else{
				if($this->request->data["type"] == "natural"){
					$clientsNaturals = $this->Adress->ClientsNatural->find('list');
					$this->request->data["Adress"]["clients_natural_id"] = $this->request->data["client"];
				}else{
					$clientsLegals = $this->Adress->ClientsLegal->find('list');
					$this->request->data["Adress"]["clients_legal_id"] = $this->request->data["client"];
				}
				$this->set(compact('clientsLegals', 'clientsNaturals'));
			}			
			$this->render("add");
		}
	}


	public function store() {

		$this->autoRender = false;
		if($this->request->is("ajax")){	
			if(empty($this->request->data["Adress"]["id"])){
				$this->Adress->create();
			}

			if ($this->Adress->save($this->request->data)) {
				$this->Session->setFlash('La dirección se ha guardado satisfactoriamente', 'Flash/success');
			} else {
				$this->Session->setFlash('La dirección no se ha guardado, por favor inténtalo mas tarde','Flash/error');
			}
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
		$this->Adress->id = $id;
		if (!$this->Adress->exists()) {
			throw new NotFoundException(__('Invalid adress'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Adress->delete()) {
			$this->Flash->success(__('The adress has been deleted.'));
		} else {
			$this->Flash->error(__('The adress could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

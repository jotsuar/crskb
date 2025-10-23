<?php
App::uses('AppController', 'Controller');
/**
 * Transits Controller
 *
 * @property Transit $Transit
 * @property PaginatorComponent $Paginator
 */
class TransitsController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$get 		   = isset($this->request->query["q"]) && !empty($this->request->query["q"]) ? $this->request->query : array() ;
		if(!empty($get["q"]) ){
			$conditions	= array('OR' => 
				array(
				    'LOWER(Transit.prospective_user_id) LIKE' => '%'.mb_strtolower($get['q']).'%',
				    'LOWER(Product.part_number) LIKE' => '%'.mb_strtolower($get['q']).'%'
				)
			);
    	}else{
    		$conditions = [];
    	}
		$this->Transit->recursive = 1;
		$this->paginate 			= array( 'conditions' 	=> $conditions );
		$this->set('transits', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (!$this->Transit->exists($id)) {
			throw new NotFoundException(__('Invalid transit'));
		}
		$options = array('conditions' => array('Transit.' . $this->Transit->primaryKey => $id));
		$this->set('transit', $this->Transit->find('first', $options));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Transit->create();
			if ($this->Transit->save($this->request->data)) {
				$this->Session->setFlash('La nota se ha guardado satisfactoriamente', 'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('La nota no se ha guardado satisfactoriamente', 'Flash/error');
			}
		}
		$products = $this->Transit->Product->find('list');
		$imports = $this->Transit->Import->find('list');
		$prospectiveUsers = $this->Transit->ProspectiveUser->find('list');
		$users = $this->Transit->User->find('list');
		$this->set(compact('products', 'imports', 'prospectiveUsers', 'users'));
	}


	public function edit($id = null) {
		if (!$this->Transit->exists($id)) {
			throw new NotFoundException(__('Invalid transit'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Transit->save($this->request->data)) {
				$this->Session->setFlash('La nota se ha guardado satisfactoriamente', 'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('La nota no se ha guardado satisfactoriamente', 'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Transit.' . $this->Transit->primaryKey => $id));
			$this->request->data = $this->Transit->find('first', $options);
		}
		$products = $this->Transit->Product->find('list');
		$imports = $this->Transit->Import->find('list');
		$prospectiveUsers = $this->Transit->ProspectiveUser->find('list');
		$users = $this->Transit->User->find('list');
		$this->set(compact('products', 'imports', 'prospectiveUsers', 'users'));
	}

	public function change(){
		$this->autoRender = false;

		$dataChange = ["Transit" => [ "id" => $this->request->data["id"], "note" => date("Y-m-d")." | ".$this->request->data["razon"], "state" => 1 ]  ];
		$this->Transit->save($dataChange);
		$this->Session->setFlash('La nota se ha guardado satisfactoriamente', 'Flash/success');
	}

}

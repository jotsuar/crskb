<?php
App::uses('AppController', 'Controller');
/**
 * Features Controller
 *
 * @property Feature $Feature
 * @property PaginatorComponent $Paginator
 */
class FeaturesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');


	public function index() {
		$get 		   = isset($this->request->query["q"]) && !empty($this->request->query["q"]) ? $this->request->query : array() ;
		if(!empty($get["q"]) ){
			$conditions	= array('OR' => 
				array(
				        'LOWER(Feature.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%'
				    )
			);
    	}else{
    		$conditions = [];
    	}
		$this->Feature->recursive = 1;
		$this->paginate 			= array( 'conditions' 	=> $conditions );
		$this->set('features', $this->Paginator->paginate());
	}


	public function view($id = null) {
		if (!$this->Feature->exists($id)) {
			throw new NotFoundException(__('Invalid feature'));
		}
		$options = array('conditions' => array('Feature.' . $this->Feature->primaryKey => $id));
		$this->set('feature', $this->Feature->find('first', $options));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Feature->create();
			if ($this->Feature->save($this->request->data)) {
				$this->Session->setFlash(__('La característica fue guardada correctamente'),'Flash/success');
				if (isset($this->request->data["other"])) {
					return $this->redirect(array('action' => 'add'));
				}else{
					return $this->redirect(array('action' => 'index',"controller"=>"features_values",$this->encrypt($this->Feature->id)));
				}
			} else {
				$this->Session->setFlash(__('La característica no fue guardada correctamente'),'Flash/error');
			}
		}
	}

	public function feature_row(){
		$this->layout = false;

		$this->set("features",$this->Feature->find("list"));

	}

	public function feature_values_row(){
		$this->layout = false;
		$values = $this->Feature->FeaturesValue->find("list",["conditions"=>["feature_id" => $this->request->data["feature_id"] ]]);
		$this->set("values",$values);
	}

	public function edit($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Feature->exists($id)) {
			throw new NotFoundException(__('Invalid feature'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Feature->save($this->request->data)) {
				$this->Session->setFlash(__('La característica fue guardada correctamente'),'Flash/success');
				if (!isset($this->request->data["other"])) {
					return $this->redirect(array('action' => 'index'));
				}else{
					return $this->redirect(array('action' => 'index',"controller"=>"features_values",$this->encrypt($this->Feature->id)));
				}
			} else {
				$this->Session->setFlash(__('La característica no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Feature.' . $this->Feature->primaryKey => $id));
			$this->request->data = $this->Feature->find('first', $options);
		}
	}


	public function delete($id = null) {
		$this->Feature->id = $id;
		if (!$this->Feature->exists()) {
			throw new NotFoundException(__('Invalid feature'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Feature->delete()) {
			$this->Flash->success(__('The feature has been deleted.'));
		} else {
			$this->Flash->error(__('The feature could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

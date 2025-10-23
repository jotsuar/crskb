<?php
App::uses('AppController', 'Controller');
/**
 * FeaturesValues Controller
 *
 * @property FeaturesValue $FeaturesValue
 * @property PaginatorComponent $Paginator
 */
class FeaturesValuesController extends AppController {

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
	public function index($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->FeaturesValue->Feature->exists($id)) {
			return $this->redirect(array('action' => 'index',"controller"=>"features"));
		}

		$get 		   = isset($this->request->query["q"]) && !empty($this->request->query["q"]) ? $this->request->query : array() ;
		if(!empty($get["q"]) ){
			$conditions	= array(
				'OR' => array('LOWER(FeaturesValue.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%'),
				"FeaturesValue.feature_id" => $id,
			);
    	}else{
    		$conditions["FeaturesValue.feature_id"] = $id;
    	}

		$this->FeaturesValue->Feature->recursive = -1;
		$feature = $this->FeaturesValue->Feature->findById($id);
		$this->FeaturesValue->recursive = 0;
		$this->set('featuresValues', $this->Paginator->paginate());
		$this->set("feature", $feature);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->FeaturesValue->exists($id)) {
			throw new NotFoundException(__('Invalid features value'));
		}
		$options = array('conditions' => array('FeaturesValue.' . $this->FeaturesValue->primaryKey => $id));
		$this->set('featuresValue', $this->FeaturesValue->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($idFeature = null) {
		if (!is_null($idFeature)) {
			$idFeature 				= $this->desencriptarCadena($idFeature);
		}
		if ($this->request->is('post')) {
			$this->FeaturesValue->create();

			if ($this->FeaturesValue->save($this->request->data)) {
				$this->Session->setFlash(__('El valor fue guardado correctamente'),'Flash/success');
				if (!isset($this->request->data["other"])) {
					return $this->redirect(array('action' => 'index',$this->encrypt($this->request->data["FeaturesValue"]["feature_id"])));
				}else{
					return $this->redirect(array('action' => 'add',$this->encrypt($this->request->data["FeaturesValue"]["feature_id"])));
				}
			} else {
				$this->Session->setFlash(__('El valor no fue guardado correctamente'),'Flash/error');
			}
		}
		$features = $this->FeaturesValue->Feature->find('list');
		$this->set(compact('features','idFeature'));
	}


	public function edit($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->FeaturesValue->exists($id)) {
			throw new NotFoundException(__('Invalid features value'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->FeaturesValue->save($this->request->data)) {
				$this->Session->setFlash(__('El valor fue guardado correctamente'),'Flash/success');
				if (!isset($this->request->data["other"])) {
					return $this->redirect(array('action' => 'index',$this->encrypt($this->request->data["FeaturesValue"]["feature_id"])));
				}else{
					return $this->redirect(array('action' => 'add',$this->encrypt($this->request->data["FeaturesValue"]["feature_id"])));
				}
			} else {
				$this->Session->setFlash(__('El valor no fue guardado correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('FeaturesValue.' . $this->FeaturesValue->primaryKey => $id));
			$this->request->data = $this->FeaturesValue->find('first', $options);
		}
		$features = $this->FeaturesValue->Feature->find('list');
		$this->set(compact('features'));
	}


	public function delete($id = null) {
		$this->FeaturesValue->id = $id;
		if (!$this->FeaturesValue->exists()) {
			throw new NotFoundException(__('Invalid features value'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->FeaturesValue->delete()) {
			$this->Flash->success(__('The features value has been deleted.'));
		} else {
			$this->Flash->error(__('The features value could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

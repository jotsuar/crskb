<?php
App::uses('AppController', 'Controller');
/**
 * Carpetas Controller
 *
 * @property Carpeta $Carpeta
 * @property PaginatorComponent $Paginator
 */
class CarpetasController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function permisionCreate(){
		$this->loadModel("Config");
		$usersCreate = $this->Config->field("users_blogs",["id" => 1]);
		$action      = false;

		if (!empty($usersCreate)) {
			$usersCreate =  explode(",",$usersCreate);
			if (in_array(AuthComponent::user("id"), $usersCreate)) {
				$action = true;
			}
		}
		return $action;
	}

	public function index($id = null) {
		$this->loadModel("CarpetaDetalle");
		$ids = "";
		$permision = $this->permisionCreate();

		$this->Carpeta->recursive = 1;
		$this->set('carpetas', $this->Carpeta->find("all",["conditions"=>["Carpeta.carpeta_id" => 0]]));
		$conditions = [];

		$get 		   = isset($this->request->query["q"]) && !empty($this->request->query["q"]) ? $this->request->query : array() ;
		if(!empty($get["q"]) ){
			$conditions	= array('OR' => 
				array(
				        'LOWER(Blog.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
				        'LOWER(Blog.description) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
				        'LOWER(Document.name) LIKE'	=> '%'.mb_strtolower($get['q']).'%',
				        'LOWER(Document.description) LIKE'	=> '%'.mb_strtolower($get['q']).'%',
				    )
			);
    	}

		if (!is_null($id)) {
			$id 				= $this->desencriptarCadena($id);
			$ids 				= $id;
			$carpetaData 		= $this->Carpeta->find("first",["conditions" => ["Carpeta.id" => $id]  ]);
			$this->set("capetaInfo",$carpetaData);
			$idsData = [];
			if (!is_null($carpetaData["Sub"])) {
				$idsData = Set::extract($carpetaData["Sub"],"{n}.id");
			}
			$idsData[$id] = $id;

			$conditions["CarpetaDetalle.carpeta_id"] = $idsData;
		}

		


		$this->CarpetaDetalle->recursive = 1;
		$this->paginate 			= array( 'conditions' 	=> $conditions );
		$this->set('detalles', $this->Paginator->paginate("CarpetaDetalle"));

		$this->set("permision",$permision);
		$this->set("ids",$ids);

	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		if (!$this->Carpeta->exists($id)) {
			throw new NotFoundException(__('Invalid carpeta'));
		}
		$options = array('conditions' => array('Carpeta.' . $this->Carpeta->primaryKey => $id));
		$this->set('carpeta', $this->Carpeta->find('first', $options));
	}


	public function add() {
		$permision = $this->permisionCreate();
		if (!$permision) {
			$this->redirect(["action"=>"index"]);
		}
		if ($this->request->is('post')) {
			$this->Carpeta->create();
			if ($this->Carpeta->save($this->request->data)) {
				$this->Session->setFlash(__('La categoría fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index',$this->encrypt($this->Carpeta->id)));
			} else {
				$this->Session->setFlash(__('La categoría no fue gardada. Intente de nuevo por favor.'),'Flash/error');
			}
		}
		$carpetas = $this->Carpeta->find("list",["conditions" => ["carpeta_id" => 0] ]);

		$this->set("carpetas",$carpetas);
	}



	public function edit($id = null) {
		$id 				= $this->desencriptarCadena($id);
		$permision = $this->permisionCreate();
		if (!$permision) {
			$this->redirect(["action"=>"index"]);
		}
		if (!$this->Carpeta->exists($id)) {
			throw new NotFoundException(__('Invalid carpeta'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Carpeta->save($this->request->data)) {
				$this->Session->setFlash(__('La categoría fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index',$this->encrypt($this->Carpeta->id)));
			} else {
				$this->Session->setFlash(__('La categoría no fue gardada. Intente de nuevo por favor.'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Carpeta.' . $this->Carpeta->primaryKey => $id));
			$this->request->data = $this->Carpeta->find('first', $options);
		}
		$carpetas = $this->Carpeta->find("list",["conditions" => ["carpeta_id" => 0,"id !="=>$id] ]);

		$this->set("carpetas",$carpetas);
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Carpeta->id = $id;
		if (!$this->Carpeta->exists()) {
			throw new NotFoundException(__('Invalid carpeta'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Carpeta->delete()) {
			$this->Flash->success(__('The carpeta has been deleted.'));
		} else {
			$this->Flash->error(__('The carpeta could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

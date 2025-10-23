<?php
App::uses('AppController', 'Controller');
/**
 * Documents Controller
 *
 * @property Document $Document
 * @property PaginatorComponent $Paginator
 */
class DocumentsController extends AppController {

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
		$this->Document->recursive = 0;
		$this->set('documents', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Document->exists($id)) {
			throw new NotFoundException(__('Invalid document'));
		}
		$options = array('conditions' => array('Document.' . $this->Document->primaryKey => $id));
		$this->set('document', $this->Document->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Document->Carpeta->exists($id)) {
			throw new NotFoundException(__('Invalid document'));
		}
		if ($this->request->is('post')) {
			$this->Document->create();

			


			if ($this->Document->save($this->request->data)) {
				$this->loadModel("CarpetaDetalle");
				$this->CarpetaDetalle->create();
				$this->CarpetaDetalle->save(["carpeta_id" => $id, "document_id" => $this->Document->id]);
				$this->Session->setFlash(__('El archivo fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index',"controller" => "carpetas", $this->encrypt($id)));
			} else {
				$this->Session->setFlash(__('El archivo no fue gardado. Intente de nuevo por favor.'),'Flash/error');
			}
		}
		$this->set(compact('carpetas',"id"));
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
		if (!$this->Document->exists($id)) {
			throw new NotFoundException(__('Invalid document'));
		}
		$options  = array('conditions' => array('Document.' . $this->Document->primaryKey => $id));
		$document = $this->Document->find('first', $options);
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Document->save($this->request->data)) {
				$this->Session->setFlash(__('El archivo fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index',"controller" => "carpetas", $this->encrypt($document["Document"]["carpeta_id"])));
			} else {
				$this->Session->setFlash(__('El archivo no fue gardado. Intente de nuevo por favor.'),'Flash/error');
			}
		} else {
			
			$this->request->data = $document;
		}
		$carpetas = $this->Document->Carpeta->find('list');
		$this->set(compact('carpetas','document'));
	}

}

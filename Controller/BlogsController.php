<?php
App::uses('AppController', 'Controller');
/**
 * Blogs Controller
 *
 * @property Blog $Blog
 * @property PaginatorComponent $Paginator
 */
class BlogsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');


	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view','comment_quotation','resend_quotation','approve_quotation','resend_mail','view_whatsapp','show_image','quiz_data');
    }
	
	public function index() {
		$this->Blog->recursive = 0;
		$this->set('blogs', $this->Paginator->paginate());
	}

	public function view($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Blog->exists($id)) {
			throw new NotFoundException(__('Invalid blog'));
		}
		$options = array('conditions' => array('Blog.' . $this->Blog->primaryKey => $id));
		$this->set('blog', $this->Blog->find('first', $options));
	}

	public function add($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if ($this->request->is('post')) {

			if ($this->request->data['Blog']['imagen_1']['name'] == '') {
				unset($this->request->data['Blog']['imagen_1']);
			}
			if ($this->request->data['Blog']['imagen_2']['name'] == '') {
				unset($this->request->data['Blog']['imagen_2']);
			}
			if ($this->request->data['Blog']['imagen_3']['name'] == '') {
				unset($this->request->data['Blog']['imagen_3']);
			}
			if ($this->request->data['Blog']['archivo_1']['name'] == '') {
				unset($this->request->data['Blog']['archivo_1']);
			}
			if ($this->request->data['Blog']['archivo_2']['name'] == '') {
				unset($this->request->data['Blog']['archivo_2']);
			}
			if ($this->request->data['Blog']['archivo_3']['name'] == '') {
				unset($this->request->data['Blog']['archivo_3']);
			}

			$this->Blog->create();
			if ($this->Blog->save($this->request->data)) {
				$this->loadModel("CarpetaDetalle");
				$this->CarpetaDetalle->create();
				$this->CarpetaDetalle->save(["carpeta_id" => $id, "blog_id" => $this->Blog->id]);
				$this->Session->setFlash(__('El artículo fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index',"controller" => "carpetas", $this->encrypt($id)));
			} else {
				$this->Session->setFlash(__('El artículo no fue gardado. Intente de nuevo por favor.'),'Flash/error');
			}
		}
		$carpetas = $this->Blog->Carpeta->find('list');
		$this->set(compact('carpetas','id'));
	}


	public function edit($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Blog->exists($id)) {
			throw new NotFoundException(__('Invalid blog'));
		}
		$options = array('conditions' => array('Blog.' . $this->Blog->primaryKey => $id));
		$blog 	 = $this->Blog->find('first', $options);
		if ($this->request->is(array('post', 'put'))) {
			if ($this->request->data['Blog']['imagen_1']['name'] == '') {
				unset($this->request->data['Blog']['imagen_1']);
			}
			if ($this->request->data['Blog']['imagen_2']['name'] == '') {
				unset($this->request->data['Blog']['imagen_2']);
			}
			if ($this->request->data['Blog']['imagen_3']['name'] == '') {
				unset($this->request->data['Blog']['imagen_3']);
			}
			if ($this->request->data['Blog']['archivo_1']['name'] == '') {
				unset($this->request->data['Blog']['archivo_1']);
			}
			if ($this->request->data['Blog']['archivo_2']['name'] == '') {
				unset($this->request->data['Blog']['archivo_2']);
			}
			if ($this->request->data['Blog']['archivo_3']['name'] == '') {
				unset($this->request->data['Blog']['archivo_3']);
			}
			if ($this->Blog->save($this->request->data)) {
				$this->Session->setFlash(__('El artículo fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index',"controller" => "carpetas", $this->encrypt($this->request->data["Blog"]["carpeta_id"])));
			} else {
				$this->Session->setFlash(__('El artículo no fue gardado. Intente de nuevo por favor.'),'Flash/error');
			}
		} else {
			$this->request->data = $blog;
		}
		$carpetas = $this->Blog->Carpeta->find('list');
		$this->set(compact('carpetas','blog'));
	}

}

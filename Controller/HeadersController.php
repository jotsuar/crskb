<?php
App::uses('AppController', 'Controller');

class HeadersController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$this->Header->recursive = 0;
		$get 						= $this->request->query;
		if (!empty($get)) {
			$conditions				= array('OR' => array(
									            'LOWER(Header.name) LIKE' 		=> '%'.mb_strtolower($get['q']).'%'
									        )
										);
		} else {
			$conditions 			= array();
		}
		$order						= array('Header.id' => 'desc');
		$this->paginate 			= array(
										'order'			=> $order,
							        	'limit' 		=> 19,
							        	'conditions' 	=> $conditions,
							    	);
		$headers 					= $this->paginate('Header');
		$this->set(compact('headers'));
	}

	public function view($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Header->exists($id)) {
			throw new NotFoundException('El header no existe');
		}
		$header = $this->Header->get_data($id);
		$this->set(compact('header'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Header->create();
			$imagen_big 										= $this->loadPhoto($this->request->data['Header']['big_img'],'header/header');
			$this->request->data['Header']['img_big'] 			= $this->Session->read('imagenModelo');
			$imagen_small 										= $this->loadPhoto($this->request->data['Header']['small_img'],'header/miniatura');
			$this->request->data['Header']['img_small'] 		= $this->Session->read('imagenModelo');
			if ($imagen_big == 1 && $imagen_small == 1) {
				$imagen = 1;
			} else  {
				if ($imagen_big == 1) {
					$imagen = $imagen_small;
				} else {
					$imagen = $imagen_big;
				}
			}
			if ($imagen == 1) {
				if ($this->Header->save($this->request->data)) {
					$this->Session->setFlash('El header se ha guardado satisfactoriamente', 'Flash/success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('No se pudo guardar la información, por favor vuelve a intentar mas tarde','Flash/error');
				}
			} else {
				$this->validateImageState($imagen);
			}
		}
	}

	public function edit($id = null) {
		if (!$this->Header->exists($id)) {
			throw new NotFoundException('El header no existe');
		}
		$header = $this->Header->get_data($id);
		if ($this->request->is(array('post', 'put'))) {
			if ($this->request->data['Header']['big_img']['name'] != '') {
				$imagen_big 										= $this->loadPhoto($this->request->data['Header']['big_img'],'header/header');
				$imagen_small 										= 1;
				$this->request->data['Header']['img_big'] 			= $this->Session->read('imagenModelo');
				$this->deleteImageServer(WWW_ROOT.'img/header/header/'.$header['Header']['img_big']);
			} else {
				$this->request->data['Header']['img_big'] 			= $header['Header']['img_big'];
				$imagen_big 										= 1;
			}
			if ($this->request->data['Header']['small_img']['name'] != '') {
				$imagen_small 										= $this->loadPhoto($this->request->data['Header']['small_img'],'header/miniatura');
				$imagen_big 										= 1;
				$this->request->data['Header']['img_small'] 		= $this->Session->read('imagenModelo');
				$this->deleteImageServer(WWW_ROOT.'img/header/miniatura/'.$header['Header']['img_small']);
			} else {
				$this->request->data['Header']['img_small'] 		= $header['Header']['img_small'];
				$imagen_small 										= 1;
			}
			if ($imagen_big == 1 && $imagen_small == 1) {
				$imagen = 1;
			} else  {
				if ($imagen_big == 1) {
					$imagen = $imagen_small;
				} else {
					$imagen = $imagen_big;
				}
			}
			if ($imagen == 1) {
				if ($this->Header->save($this->request->data)) {
					$this->Session->setFlash('El header se ha actualizado satisfactoriamente', 'Flash/success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('No se pudo guardar la información, por favor vuelve a intentar mas tarde','Flash/error');
				}
			} else {
				$this->validateImageState($imagen);
			}
		}
		$this->set(compact('header'));
	}

	public function data_header(){
		$this->layout 						= false;
		if ($this->request->is('ajax')) {
			$header_id 		= isset($this->request->data['radio_option']) ? $this->request->data['radio_option'] : 1;
			$header 		= $this->Header->get_data($header_id);
			$this->set(compact('header'));
		}
	}
}

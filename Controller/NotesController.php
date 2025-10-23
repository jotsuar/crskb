<?php
App::uses('AppController', 'Controller');

class NotesController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$get 						= $this->request->query;
		if (!empty($get)) {
			$conditions				= array('OR' => array(
									            'LOWER(Note.name) LIKE' 		=> '%'.mb_strtolower($get['q']).'%',
									            'LOWER(Note.description) LIKE' 	=> '%'.mb_strtolower($get['q']).'%'
									        )
										);
		} else {
			$conditions 			= array();
		}
		$order						= array('Note.id' => 'desc');
		$this->paginate 			= array(
										'order' 		=> $order,
							        	'limit' 		=> 19,
							        	'conditions' 	=> $conditions,
							    	);
		$notes 						= $this->paginate('Note');
		$this->set(compact('notes'));
	}

	public function view($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Note->exists($id)) {
			throw new NotFoundException('La nota no existe');
		}
		$note = $this->Note->get_data($id);
		$this->set(compact('note'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Note->create();
			if ($this->Note->save($this->request->data)) {
				$this->Session->setFlash('La nota se ha guardado satisfactoriamente', 'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('No se pudo guardar la información, por favor vuelve a intentar mas tarde', 'Flash/error');
			}
		}
		$tipo_nota = Configure::read('variables.type_note_quotation');
		if(AuthComponent::user("role") == "Gerente General"){
			$tipo_nota[4] = "Nota al proveedor";
		}
		$this->set(compact('tipo_nota'));
	}

	public function edit($id = null) {
		if (!$this->Note->exists($id)) {
			throw new NotFoundException('La nota no existe');
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Note->save($this->request->data)) {
				$this->Session->setFlash('La nota se ha actualizado satisfactoriamente', 'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('No se pudo actualizar la información, por favor vuelve a intentar mas tarde','Flash/error');
			}
		}
		$note 			= $this->Note->get_data($id);
		$tipo_nota 		= Configure::read('variables.type_note_quotation');
		$tipo_nota = Configure::read('variables.type_note_quotation');
		if(AuthComponent::user("role") == "Gerente General"){
			$tipo_nota[4] = "Nota al proveedor";
		}
		$this->set(compact('tipo_nota','note'));
	}

	public function dataNotesPrevious(){
		$this->autoRender = false;
		return json_encode($this->Note->data_notes_previas());
	}

	public function getNote(){
		$this->autoRender = false;
		return json_encode($this->Note->get_data($this->request->data["id"]));
	}
}

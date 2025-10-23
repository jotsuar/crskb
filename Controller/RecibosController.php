<?php
App::uses('AppController', 'Controller');
/**
 * Recibos Controller
 *
 * @property Recibo $Recibo
 * @property PaginatorComponent $Paginator
 */
class RecibosController extends AppController {

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

		if(empty($this->request->query["ini"]) || empty($this->request->query["end"]) ){
            $ini = date("Y-m-01");
            $end = date("Y-m-t");
        }
        else{
            $ini = $this->request->query["ini"];
            $end = $this->request->query["end"];
        }
        
        $this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
               
             
		$this->Recibo->recursive = 0;

		$this->paginate 			= array(
							        	'limit' 		=> 19,
							        	'conditions' 	=> ["Recibo.receipt_id"=>null],
							    	);
		$this->set('recibos', $this->Paginator->paginate());
	}


	public function update_receipt(){
		$this->autoRender = false;
		$id 		= $this->request->data["recibo_id"];
		$receipt_id = $this->request->data["receipt_id"];
		$this->Recibo->recursive = -1;
		$recibo = $this->Recibo->findById($id);
		$recibo["Recibo"]["receipt_id"] = $receipt_id;
		$this->Recibo->save($recibo);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->layout = false;
		if (!$this->Recibo->exists($id)) {
			throw new NotFoundException(__('Invalid recibo'));
		}
		$options = array('conditions' => array('Recibo.' . $this->Recibo->primaryKey => $id));
		$recibo  = $this->Recibo->find('first', $options);

		$details = json_decode($recibo["Recibo"]["details"]);

		$empleado = "";
		$empresa  = "";
		$nit      = "";

		$details = $this->object_to_array($details);

		foreach ($details["details"] as $key => $value) {
			$empleado = $value["Recaudado_Por"];
			$empresa = $value["Tercero"];
			$nit = $value["Identificacion_Tercero"];
		}

		// var_dump($empleado);
		// var_dump($empresa);
		// var_dump($details);
		// die();

		$this->set('recibo', $recibo );
		$this->set('details', $details );
		$this->set('empleado', $empleado );
		$this->set('empresa', $empresa );
		$this->set('nit', $nit );
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Recibo->create();
			if ($this->Recibo->save($this->request->data)) {
				$this->Flash->success(__('The recibo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The recibo could not be saved. Please, try again.'));
			}
		}
		$receipts = $this->Recibo->Receipt->find('list');
		$this->set(compact('receipts'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Recibo->exists($id)) {
			throw new NotFoundException(__('Invalid recibo'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Recibo->save($this->request->data)) {
				$this->Flash->success(__('The recibo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The recibo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Recibo.' . $this->Recibo->primaryKey => $id));
			$this->request->data = $this->Recibo->find('first', $options);
		}
		$receipts = $this->Recibo->Receipt->find('list');
		$this->set(compact('receipts'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Recibo->exists($id)) {
			throw new NotFoundException(__('Invalid recibo'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Recibo->delete($id)) {
			$this->Flash->success(__('The recibo has been deleted.'));
		} else {
			$this->Flash->error(__('The recibo could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

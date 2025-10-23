<?php
App::uses('AppController', 'Controller');
/**
 * QuotationsProducts Controller
 *
 * @property QuotationsProduct $QuotationsProduct
 * @property PaginatorComponent $Paginator
 */
class QuotationsProductsController extends AppController {

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
		$this->QuotationsProduct->recursive = 0;
		$this->set('quotationsProducts', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->QuotationsProduct->exists($id)) {
			throw new NotFoundException(__('Invalid quotations product'));
		}
		$options = array('conditions' => array('QuotationsProduct.' . $this->QuotationsProduct->primaryKey => $id));
		$this->set('quotationsProduct', $this->QuotationsProduct->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->QuotationsProduct->create();
			if ($this->QuotationsProduct->save($this->request->data)) {
				$this->Flash->success(__('The quotations product has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The quotations product could not be saved. Please, try again.'));
			}
		}
		$quotations = $this->QuotationsProduct->Quotation->find('list');
		$products = $this->QuotationsProduct->Product->find('list');
		$this->set(compact('quotations', 'products'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null,$quotation ) {
		if (!$this->QuotationsProduct->exists($id)) {
			throw new NotFoundException(__('Invalid quotations product'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->QuotationsProduct->save($this->request->data)) {
				$this->Session->setFlash('Cambio realizado correctamente.', 'Flash/success');
				return $this->redirect(array('action' => 'view',"controller" => "quotations",$quotation));
			} else {
				$this->Session->setFlash('Cambio no realizado correctamente.', 'Flash/error');
			}
		} else {
			$options = array('conditions' => array('QuotationsProduct.' . $this->QuotationsProduct->primaryKey => $id));
			$this->request->data = $this->QuotationsProduct->find('first', $options);
		}
		$quotations = $this->QuotationsProduct->Quotation->find('all',["fields" => ["id","CONCAT(Quotation.name,' | ',Quotation.codigo) as nombre "],"recursive" => -1 ]);
		$copy = $quotations;
		$quotations = [];
		foreach ($copy as $key => $value) {
			$quotations[$value["Quotation"]["id"]] = $value["0"]["nombre"];
		}

		$products = $this->QuotationsProduct->Product->find('all',["fields" => ["id","CONCAT(Product.name,' | ',Product.part_number) as nombre "],"recursive" => -1 ]);
		$copy = $products;
		$products = [];
		foreach ($copy as $key => $value) {
			$products[$value["Product"]["id"]] = $value["0"]["nombre"];
		}
		$this->set(compact('quotations', 'products'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->QuotationsProduct->id = $id;
		if (!$this->QuotationsProduct->exists()) {
			throw new NotFoundException(__('Invalid quotations product'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->QuotationsProduct->delete()) {
			$this->Flash->success(__('The quotations product has been deleted.'));
		} else {
			$this->Flash->error(__('The quotations product could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

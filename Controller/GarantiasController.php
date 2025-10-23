<?php
App::uses('AppController', 'Controller');

class GarantiasController extends AppController {


	public $components = array('Paginator');


	public function index() {

		if(!empty($get["q"]) ){
			$conditions	= array('OR' => 
				array(
				        'LOWER(Garantia.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
				        'LOWER(Garantia.description) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
				        'LOWER(Brand.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
				        'LOWER(Brand.social_reason) LIKE'	=> '%'.mb_strtolower($get['q']).'%'
				    )
			);
    	}else{
    		$conditions = [];
    	}
		$this->paginate 			= array( 'conditions' 	=> $conditions );
		$this->Garantia->recursive = 0;
		$this->set('garantias', $this->Paginator->paginate());

		$this->set("categories", $this->Garantia->Product->Category->find("list"));

	}


	public function view($id = null) {
		$this->autoRender = false;
		if (!$this->Garantia->exists($id)) {
			throw new NotFoundException(__('Invalid garantia'));
		}
		$options = array('conditions' => array('Garantia.' . $this->Garantia->primaryKey => $id));
		$garantia = $this->Garantia->find('first', $options);
		echo '<p><span style="color: rgb(255, 0, 0);"><b>NOTA</b> : '.$garantia["Garantia"]["description"].'</span></p>';
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Garantia->create();
			$dataRequest = $this->request->data["Garantia"];
			$products    = $this->request->data["product_id"];
			unset($this->request->data["Garantia"]);
			unset($this->request->data["product_id"]);
			$dataRequest = array_merge($dataRequest, $this->request->data);
			if ($this->Garantia->save($dataRequest)) {
				$this->Garantia->Product->updateAll( ["Product.garantia_id" => $this->Garantia->id ], ["Product.id" => $products ] );
				$this->Session->setFlash(__('La garantía para la marca fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La información no fue gardada. Intente de nuevo por favor.'),'Flash/error');
			}
		}
		
		$this->setBrandsData();
	}


	public function edit($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Garantia->exists($id)) {
			throw new NotFoundException(__('Invalid garantia'));
		}
		if ($this->request->is(array('post', 'put'))) {

			$dataRequest = $this->request->data["Garantia"];
			$products    = $this->request->data["product_id"];
			unset($this->request->data["Garantia"]);
			unset($this->request->data["product_id"]);
			$dataRequest = array_merge($dataRequest, $this->request->data);

			if ($this->Garantia->save($dataRequest)) {
				$idGarantia = $this->Garantia->id;
				$this->Garantia->Product->updateAll( ["Product.garantia_id" => null ], [ "Product.garantia_id" => $this->Garantia->id ] );
				$this->Garantia->Product->updateAll( ["Product.garantia_id" => $this->Garantia->id ], ["Product.id" => $products ] );

				$this->Session->setFlash(__('La garantía para la marca fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La información no fue gardada. Intente de nuevo por favor.'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Garantia.' . $this->Garantia->primaryKey => $id));
			$garantia = $this->Garantia->find('first', $options);
			$this->request->data = $garantia;
			$produtsIds = $this->Garantia->Product->find("list",["fields"=>["id","id"], "conditions" => ["Product.garantia_id" => $id] ]);
			$this->set("garantia",$garantia);
			$this->set("produtsIds",array_values($produtsIds));
		}
		$this->setBrandsData();
	}

	private function setBrandsData(){
		$brands = $this->Garantia->Brand->find("list",["conditions" => ["Brand.brand_id" => 0] ]);
		unset($brands[1]);
		$categoriesInfoFinal = $this->getCagegoryData();
		$this->set("brands",$brands);
		$this->set("categoriesInfoFinal",$categoriesInfoFinal);
	}


}

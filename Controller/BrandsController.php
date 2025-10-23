<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');



/**
 * Brands Controller
 *
 * @property Brand $Brand
 * @property PaginatorComponent $Paginator
 */
class BrandsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function list_brands()
	{
		$this->autoRender = false;
		$brand_list = $this->Brand->find("list",["fields"=> ["name","provider"] ]);

		echo json_encode($brand_list);
		die();

	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$get 		   = isset($this->request->query["q"]) && !empty($this->request->query["q"]) ? $this->request->query : array() ;
		if(!empty($get["q"]) ){
			$conditions	= array('OR' => 
				array(
				        'LOWER(Brand.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
				        'LOWER(Brand.social_reason) LIKE'	=> '%'.mb_strtolower($get['q']).'%'
				    )
			);
    	}else{
    		$conditions = [];
    	}
		$this->Brand->recursive = 1;
		$this->paginate 			= array( 'conditions' 	=> $conditions );
		$this->set('brands', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Brand->exists($id)) {
			throw new NotFoundException(__('La marca no existe'));
		}
		$options = array('conditions' => array('Brand.' . $this->Brand->primaryKey => $id));
		$brand = $this->Brand->find('first', $options);

		if (!empty($brand["Brand"]["id_llc"])) {
			$normalData = $this->getBrandsLLc($brand["Brand"]["id_llc"]);
			$this->set("marcaLlc",$normalData);
		}

		$this->set('brand', $brand);
	}

	private function getBrandsLLc($id=''){
		$brandsLLC  = [];

		try {

			$params 	= ["sortfield"=>"t.rowid","sortorder"=>"ASC","limit"=>"500","sqlfilters"=>"t.fournisseur=1"];
			
			$HttpSocket = new HttpSocket(['ssl_allow_self_signed' => false, 'ssl_verify_peer' => false, 'ssl_verify_host' =>false ]);

			$response 	= $HttpSocket->get($this->API.'thirdparties/'.$id, $params ,["header" => ["DOLAPIKEY" => "Kebco2020**--","Accept"=>"application/json"] ]);

			$code 		= $response->code;

			if ($code == 200) {
				$brandsLLC = json_decode($response->body());
				if (!empty($brandsLLC)) {
					$otherBrands = $brandsLLC;
					if ($id != '') {
						return $otherBrands;
					}
					$brandsLLC = [];
					foreach ($otherBrands as $key => $value) {
						$brandsLLC[$value->id] = $value->code_fournisseur." | ".$value->name;
					}
					
				}
			}

		} catch (Exception $e) {
			
		}

		$this->set("id_llc",$brandsLLC);
	}

	public function add() {
		$this->getBrandsLLc();
		if ($this->request->is('post')) {
			$this->Brand->create();
			$this->request->data['Brand']['min_price_importer'] = $this->replaceText($this->request->data['Brand']['min_price_importer'],".", "");
			if ($this->Brand->save($this->request->data)) {
				$this->Session->setFlash(__('La marca fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La marca no fue gardada. Intente de nuevo por favor.'),'Flash/error');
			}
		}

		$brands = $this->Brand->find("list",["conditions" => ["Brand.brand_id" => 0] ]);
		$brands[0] = "Ninguna";
		ksort($brands);
		$this->set(compact("brands"));
	}


	/**
	 * @author Jhonatan Suarez <jotsuar@gmail.com>
	 * @date(20-11-2019)
	 * @description Metodo de cambio
	 * @param  int $id id del producto
	 * @return array Datos del metodo
	 */
	public function edit($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Brand->exists($id)) {
			throw new NotFoundException(__('La marca no existe'));
		}
		$this->getBrandsLLc();
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['Brand']['min_price_importer'] = $this->replaceText($this->request->data['Brand']['min_price_importer'],".", "");

			if ($this->Brand->save($this->request->data)) {
				$this->Session->setFlash(__('La marca fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La marca no fue gardada. Intente de nuevo por favor.'));
			}
		} else {
			$options = array('conditions' => array('Brand.' . $this->Brand->primaryKey => $id));
			$this->request->data = $this->Brand->find('first', $options);
		}
		$this->set("brand", $this->Brand->findById($id));
		$brands = $this->Brand->find("list",["conditions" => ["Brand.brand_id" => 0] ]);
		$brands[0] = "Ninguna";
		ksort($brands);
		$this->set(compact("brands"));
	}

}

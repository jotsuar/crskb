<?php
App::uses('AppController', 'Controller');
/**
 * SuggestedProducts Controller
 *
 * @property SuggestedProduct $SuggestedProduct
 * @property PaginatorComponent $Paginator
 */
class SuggestedProductsController extends AppController {

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
		$this->SuggestedProduct->recursive = 0;
		$this->paginate 			= array( 'group' => ["product_ppal"], "fields" => [
			"SuggestedProduct.*", "Product.*", "Principal.*", "count(SuggestedProduct.id) as total"
		] );
		$suggestedProducts = $this->Paginator->paginate();

		$this->set('suggestedProducts', $suggestedProducts );
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
		if ($this->SuggestedProduct->find("count",["conditions"=>["SuggestedProduct.product_ppal"=>$id]]) == 0) {
			throw new NotFoundException(__('Invalid suggested product'));
		}
		$options 			= array('conditions' => array('SuggestedProduct.product_ppal' => $id), "recursive" => 2);
		$suggestedProducts  = $this->SuggestedProduct->find('all', $options);
		$categoriesData 	= $this->getCategoryInfo();
		$product   			= end($suggestedProducts);
		$product["Product"] = $product["Principal"];
 		$partsData = $this->getValuesProductsWo([$product]);
 		$costos 		= $this->getCosts($partsData);
		$costoWo   			= $this->postWoApi(["part_number" => $product["Product"]["part_number"]],"get_cost");
		unset($categoriesData[0]);

		$this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
		$this->set(compact("categoriesData",'partsData','costoWo','trmActual','costos'));
		$this->set('suggestedProducts',$suggestedProducts );
	}

	private function setProducts(){
		$products = $this->SuggestedProduct->Product->find('all',["fields" => ["id","CONCAT(Product.name,' | ',Product.part_number) as nombre "],"recursive" => -1 ]);
		$copy = $products;
		$products = [];
		foreach ($copy as $key => $value) {
			$products[$value["Product"]["id"]] = $value["0"]["nombre"];
		}
		$this->set("products",$products);
	}

	public function products_related(){
		$this->layout = false;
		$actualProducts   = $this->SuggestedProduct->findAllByProductPpal($this->request->data["product"]);
		$this->set("products",$actualProducts);
	}

	public function get_product_tr(){
		$this->layout = false;
		// $this->SuggestedProduct->Product->recursive = -1;
		$product = $this->SuggestedProduct->Product->findById($this->request->data["id"]);
		$this->set("product",$product);
	}

	public function add($principal = null, $segundario = null) {
		$principal 				= $this->desencriptarCadena($principal);
		$segundario 			= $this->desencriptarCadena($segundario);
		if ($this->request->is('post')) {

			if (!empty($this->request->data["product_id"])) {

				$this->SuggestedProduct->deleteAll(["product_ppal" => $this->request->data["SuggestedProduct"]["product_ppal"]]);
				foreach ($this->request->data["product_id"] as $id_product => $value) {
					$suggested = [ "SuggestedProduct" => [
						"product_ppal" => $this->request->data["SuggestedProduct"]["product_ppal"],
						"product_aditional" => $this->request->data["SuggestedProduct"]["product_aditional"],
						"product_id" => $id_product,
						"quantity" => $this->request->data["quantity"][$id_product],
						"delivery" => $this->request->data["delivery"][$id_product],
						"price_usd" => $this->request->data["price_usd"][$id_product],
					] ];
					$this->SuggestedProduct->create();
					$this->SuggestedProduct->save($suggested);
				}
				$this->Session->setFlash('La información se ha guardado', 'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash("No tienes productos para asociar.",'Flash/error');
			}
		}
		$this->setProducts();
		$this->set("principal",$principal);
		$this->set("segundario",$segundario);
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SuggestedProduct->exists($id)) {
			throw new NotFoundException(__('Invalid suggested product'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SuggestedProduct->save($this->request->data)) {
				$this->Flash->success(__('The suggested product has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The suggested product could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SuggestedProduct.' . $this->SuggestedProduct->primaryKey => $id));
			$this->request->data = $this->SuggestedProduct->find('first', $options);
		}
		$products = $this->SuggestedProduct->Product->find('list');
		$this->set(compact('products'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$id 				= $this->desencriptarCadena($id);
		$this->request->allowMethod('post', 'delete');
		if ($this->SuggestedProduct->deleteAll( ["SuggestedProduct.product_ppal" => $id ] )) {
			$this->Session->setFlash('La información se ha guardado', 'Flash/success');
		} else {
			$this->Session->setFlash('La información no se ha guardado', 'Flash/error');
		}
		$this->Session->setFlash('La información se ha guardado', 'Flash/success');
		return $this->redirect(array('action' => 'index'));
	}
}

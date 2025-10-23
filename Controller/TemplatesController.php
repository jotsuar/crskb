<?php
App::uses('AppController', 'Controller');

class TemplatesController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$this->deleteCacheProducts1();
		$get 						= $this->request->query;
		if (!empty($get)) {
			$conditions				= array('OR' => array(
									            'LOWER(Template.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
									            'LOWER(Template.description) LIKE' 		=> '%'.mb_strtolower($get['q']).'%'
									        )
										);
		} else {
			$conditions 			= array();
		}
		$order						= array('Template.id' => 'desc');
		$this->paginate 			= array(
										'order' 		=> $order,
							        	'limit' 		=> 19,
							        	'conditions' 	=> $conditions,
							    	);
		$templates 					= $this->paginate('Template');
		$this->set(compact('templates'));
	}

	public function view($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Template->exists($id)) {
			throw new NotFoundException('El template no existe');
		}
		$template 			= $this->Template->get_data_models($id);
		$datosTP 			= $this->Template->TemplatesProduct->get_data_template($id);
		$this->set(compact('template','datosTP'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$arrayIdProducts 											= $this->Session->read('plantillaProductos');
			if (count($arrayIdProducts) > 0) {
				$this->request->data['Template']['user_id'] 			= AuthComponent::user('id');
				$this->Template->create();
				if ($this->Template->save($this->request->data)) {
					$id_colum 				= $this->Template->id;
					$this->saveDataLogsUser(2,'Template',$id_colum);
					$datosT = array();
					foreach ($arrayIdProducts as $value) {
						$datosT[$value]['TemplatesProduct']['template_id'] 	= $id_colum;
						$datosT[$value]['TemplatesProduct']['product_id'] 	= $value;
					}
					$this->Template->TemplatesProduct->create();
					$this->Template->TemplatesProduct->saveAll($datosT);
					$this->Session->setFlash('La plantilla se ha guardado satisfactoriamente', 'Flash/success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('La plantilla no se ha guardado, por favor inténtalo mas tarde','Flash/error');
				}
			} else {
				$this->Session->setFlash('Debes seleccionar mínimo un producto para la plantilla','Flash/error');
			}
		}	
	}

	public function edit($id = null) {
		if (!$this->Template->exists($id)) {
			throw new NotFoundException('El template no existe');
		}
		$datosT 					= $this->Template->get_data($id);
		$datosListTP 				= $this->Template->TemplatesProduct->get_data_template_list($id);
		if (count($datosListTP) > 0) {
			$this->writeProductsEdit($datosListTP);
		}
		$datosTP 					= $this->Template->TemplatesProduct->get_data_template($id);
		if ($this->request->is(array('post', 'put'))) {
			$arrayIdProductsViejos 				= $this->Session->read('plantillaProductos1');
			$arrayIdProductsViejosDelete 		= $this->Session->read('plantillaProductos2');
			if (count($arrayIdProductsViejosDelete) > 0) {
				$arrayIdProductsViejos 			= $this->joinCarrosEditDeleteViejos($arrayIdProductsViejos,$arrayIdProductsViejosDelete);
			} 
			$arrayIdProductsNuevos 				= $this->Session->read('plantillaProductos');
			if (count($arrayIdProductsNuevos) > 0) {
				$arrayIdProductsFin 			= $this->joinCarrosEdit($arrayIdProductsViejos,$arrayIdProductsNuevos);
			} else {
				$arrayIdProductsFin 			= $arrayIdProductsViejos;
			}
			if (count($arrayIdProductsFin) > 0) {
				foreach ($arrayIdProductsFin as $keyProducto => $producto) {
					$this->Template->TemplatesProduct->Product->recursive = -1;
					$dataBrand = $this->Template->TemplatesProduct->Product->findByBrandAndId("N/A",$producto);
					
					if(!empty($dataBrand)){
						$Html = (new View($this))->loadHelper('Html');
						$linkData = $Html->link($dataBrand['Product']['name']." - Referencia ".$dataBrand['Product']['part_number'],array('controller' => 'Products', 'action' => 'edit',$dataBrand["Product"]["id"], 'full_base' => true),array("target"=>"_blank"));
						
						$this->Session->setFlash("El producto {$linkData}, debe modificarse, no se permite ningún producto con la marca N/A",'Flash/error',array("escape" => true));
						return $this->redirect(array('action' => 'edit',$id));
					}
				}
				if ($this->Template->save($this->request->data)) {
					$this->saveDataLogsUser(3,'Template',$id);
					$this->Template->TemplatesProduct->deleteAll([
							'TemplatesProduct.template_id' => $id
						],false
					);
					$datosT = array();
					foreach ($arrayIdProductsFin as $value) {
						$datosT[$value]['TemplatesProduct']['template_id'] 	= $id;
						$datosT[$value]['TemplatesProduct']['product_id'] 	= $value;
					}
					$this->Template->TemplatesProduct->create();
					$this->Template->TemplatesProduct->saveAll($datosT);
					$this->Session->setFlash('La plantilla se ha actualizado satisfactoriamente', 'Flash/success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('La plantilla no se ha actualizado, por favor inténtalo mas tarde','Flash/error');
				}
			} else {
				$this->Session->setFlash('Debes seleccionar mínimo un producto para la plantilla','Flash/error');
			}
		}
		$this->set(compact('datosT','datosTP'));
	}

	public function writeProductsEdit($productArray){
        $this->Session->write('plantillaProductos1', $productArray);
    }

    public function deleteProductTemplateEdit(){
		$this->autoRender 						= false;
		$product_id 							= $this->request->data['product_id'];
		$arrayIdProductsDelete 					= $this->Session->read('plantillaProductos2');
		if(!is_array($arrayIdProductsDelete)) {
			$arrayIdProductsDelete				= array();
			$this->Session->write('plantillaProductos2', array());
		}
		if (!in_array($product_id, $arrayIdProductsDelete)) {
			array_push($arrayIdProductsDelete, $product_id);
			$this->Session->write('plantillaProductos2', $arrayIdProductsDelete);
		}
		return true;
	}

    public function joinCarrosEditDeleteViejos($arrayIdProductsViejos,$arrayIdProductsViejosDelete){
		$arrayIdProductsNuevosPosition = array();
		foreach ($arrayIdProductsViejos as $value) {
			$arrayIdProductsNuevosPosition[$value] 	= $value;
		}
		foreach ($arrayIdProductsViejosDelete as $value) {
			unset($arrayIdProductsNuevosPosition[$value]);
		}
		return $arrayIdProductsNuevosPosition;
	}

	public function joinCarrosEdit($arrayIdProductsViejos,$arrayIdProductsNuevos){
		$arrayIdProductsNuevosPosition = array();
		foreach ($arrayIdProductsViejos as $value) {
			$arrayIdProductsNuevosPosition[$value] 	= $value;
		}
		foreach ($arrayIdProductsNuevos as $value) {
			$arrayIdProductsNuevosPosition[$value] 	= $value;
		}
		return $arrayIdProductsNuevosPosition;
	}

	public function find_products_template(){
		$this->layout 								= false;
		if ($this->request->is('ajax')) {
			$entrega 								= Configure::read('variables.entregaProduct');
			$this->deleteCacheProducts1();
			$template_id 							= $this->request->data['template_id'];
			$datosListTP 							= $this->Template->TemplatesProduct->get_data_template_list($template_id);
			$this->Session->write('carritoProductos', $datosListTP);
			$productTemplate 						= $this->Template->TemplatesProduct->get_data_template($template_id);

			if (!empty($productTemplate)) {
                foreach ($productTemplate as $key => $value) {
                    $this->updateCostCol($value["Product"]["id"]);
                }
            }

            $productTemplate 						= $this->Template->TemplatesProduct->get_data_template($template_id);

			if (!empty($productTemplate)) {
                foreach ($productTemplate as $key => $value) {
                    $productTemplate[$key]["productsSugestions"] =  $this->sugestions($value["Product"]["id"]);
                }
            }

			$this->loadModel("Config");
			$config         = $this->Config->findById(1);
	        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
	        $factorImport   = $config["Config"]["factorUSA"];

	        $currency 	  = isset($this->request->data["currency"]) ? $this->request->data["currency"] : null ;
	        $header 	  = isset($this->request->data["header"]) ? $this->request->data["header"] : null ;
	        $editProducts = $this->validateEditProducts();
	        $inventioWo   = $this->getValuesProductsWo($productTemplate);
	        $costos       = $this->getCosts($inventioWo);
			$this->set(compact('productTemplate','entrega','currency','header','editProducts','inventioWo','costos','factorImport','trmActual'));
		}
	}
}

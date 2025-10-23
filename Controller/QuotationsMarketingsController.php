<?php
App::uses('AppController', 'Controller');
/**
 * QuotationsMarketings Controller
 *
 * @property QuotationsMarketing $QuotationsMarketing
 * @property PaginatorComponent $Paginator
 */
class QuotationsMarketingsController extends AppController {

	public $components = array('Paginator');

	public function index($bot = null) {
		$this->delete_productos(true);
		$this->QuotationsMarketing->recursive = 0;
		$this->Session->write("PRODUCTOS",[]);

		$conditions = [ "QuotationsMarketing.type" => is_null($bot) ? 1 : 2 ];

		$this->paginate 			= array(
							        	'limit' 		=> 20,
							        	'conditions' 	=> $conditions,
							    	);

		$this->set('quotationsMarketings', $this->Paginator->paginate());
		$this->set( "bot", $bot);
		$this->setFilejson();
	}

	public function setFilejson(){
		$allQuotations = $this->QuotationsMarketing->find("list",["conditions"=>["type"=>2]]);
		$quotations = [];
		if(!empty($allQuotations)){
			foreach ($allQuotations as $id => $name) {
				$quotations[$id] = compact('id','name');
			}
		}
		$file = new File(WWW_ROOT."bot_twt.json", true);
		$file->write(json_encode((array)["list"=>$quotations]));
	}


	public function view($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->QuotationsMarketing->exists($id)) {
			throw new NotFoundException(__('Invalid quotations marketing'));
		}
		$options = array('conditions' => array('QuotationsMarketing.' . $this->QuotationsMarketing->primaryKey => $id));

		$quotationsMarketing = $this->QuotationsMarketing->find('first', $options);

		$this->loadModel("Note");

		$quotationsMarketing["QuotationsMarketing"]["notes"] = $this->Note->field("description",["id"=> $quotationsMarketing["QuotationsMarketing"]["notes"] ]);

		$quotationsMarketing["QuotationsMarketing"]["notes_description"] =  $this->Note->field("description",["id"=> $quotationsMarketing["QuotationsMarketing"]["notes_description"] ]);

        $quotationsMarketing["QuotationsMarketing"]["conditions"] = $this->Note->field("description",["id"=> $quotationsMarketing["QuotationsMarketing"]["conditions"] ]);

		$datosQuation 		 = ["Quotation" => $quotationsMarketing["QuotationsMarketing"] ];

		$datosHeaders 		 = ["Header" =>$quotationsMarketing["Header"] ];
		$datosUsuario 		 = ["User" =>$quotationsMarketing["User"] ];

		$datosProductos  	 = [];

		if (!empty($quotationsMarketing) && !empty($quotationsMarketing["QuotationsMarketing"]["products"])) {
            $productos  = $this->object_to_array(json_decode($quotationsMarketing["QuotationsMarketing"]["products"]));
            $productos = Set::sort($productos, '{n}.Producto.number', 'asc');
            $this->loadModel("Product");
            foreach ($productos as $key => $value) {
               $qtProd  = ["QuotationsProduct" => $value["Producto"]];
               $this->Product->unBindModel(["hasMany"=>["ImportProduct","QuotationsProduct","TemplatesProduct","FlowStagesProduct"]]);
               $product = $this->Product->findById($value["Producto"]["product_id"]);
               $qtProd  = array_merge($qtProd,$product);
               $qtProd["Product"]["Category"] = $qtProd["Category"];
               $datosProductos[] = $qtProd;
            }

        }

        $this->loadModel("Config");
		$iva 			= $this->Config->field("ivaCol",["id" => 1]);
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];

		$this->set('quotationsMarketing',$quotationsMarketing );
		$this->set('datosQuation',$datosQuation );
		$this->set('datosHeaders',$datosHeaders );
		$this->set('datosUsuario',$datosUsuario );
		$this->set('datosProductos',$datosProductos );

		$this->set(compact("trmActual","factorImport","iva"));
	}


	public function add($bot = null) {

		if ($this->request->is('post')) {
			$this->QuotationsMarketing->create();
			$productos = $this->get_productos();
			$this->request->data["QuotationsMarketing"]["total"] = 0;
			if (!empty($productos)) {
				foreach ($productos as $key => $value) {
					unset($productos[$key]["Product"]);
				}
				$this->request->data["QuotationsMarketing"]["products"] = json_encode($productos);				
			}else{
				$this->request->data["QuotationsMarketing"]["products"] = null;					
			}

			if ($this->QuotationsMarketing->save($this->request->data)) {
				$this->Session->write("PRODUCTOS",[]);
				$this->Session->setFlash(__('La infomación fue guardada correctamente'),'Flash/success');
				if(is_null($bot)){
					return $this->redirect(array('action' => 'index'));
				}else{
					return $this->redirect(array('action' => 'index',$bot));					
				}
			} else {
				$this->Session->setFlash(__('La infomación no fue guardada correctamente'),'Flash/error');
			}
		}else{
			$this->delete_productos(true);
		}

		$this->loadModel("Note");
		$this->loadModel("User");
		$notasPrevias 		= $this->Note->data_notes_previas();
        $conditions 		= $this->Note->data_conditions_negocio();
        $descriptives 		= $this->Note->data_notes_descriptiva();
		$headers 			= $this->QuotationsMarketing->Header->find('list');

		$notas_previas   = array();
        foreach ($notasPrevias as $key => $value) {
        	$notas_previas[$value["Note"]["id"]] = $value["Note"]["name"];
        }

        $formas_pago = array();
        foreach ($conditions as $key => $value) {
        	$formas_pago[$value["Note"]["id"]] = $value["Note"]["name"];
        }

        $notas_descriptivas = array();
        foreach ($descriptives as $key => $value) {
        	$notas_descriptivas[$value["Note"]["id"]] = $value["Note"]["name"];
        }

        $usersRol	 	= $this->User->role_asesor_comercial_users_all_true(true);

        $users 		 	= array();
        foreach ($usersRol as $key => $value) {
        	$users[$value["User"]["id"]] = $value["User"]["name"];
        }

		$this->set(compact('headers','notas_previas','formas_pago','notas_descriptivas','users','bot'));
	}

	public function delete_productos($show = false){
		if (!$show) {
			$this->autoRender = false;
		}
		$this->Session->write("PRODUCTOS",[]);
		if (!$this->request->is("ajax")) {
			return [];
		}			
	}

	public function delete_producto(){
		$this->autoRender = false;
		$productos = $this->get_productos();
		unset($productos[$this->request->data["id"]]);
		$this->Session->write("PRODUCTOS",$productos);
	}

	public function add_producto(){
		$this->loadModel("Product");
		$this->Product->recursive = -1;
		$this->layout 	= 	false;
		$productos 		= $this->get_productos();
		$ids 			= [];
		$number 		= 1;
		if (!empty($productos)) {
			$ids = Set::extract($productos,"{n}.Producto.product_id");
			$number = count($ids);
			$number++;
		}

		if ($this->request->is("post")) {
			$this->request->data["Producto"]["id"] = $this->request->data["Producto"]["product_id"];	
			$this->Product->recursive = -1;			
			$ingredient = $this->Product->findById($this->request->data["Producto"]["product_id"]);
			$data 		= $this->request->data;
			$data		= array_merge($data,$ingredient);
			$productos = $this->get_productos();
			$productos[$ingredient["Product"]["id"]] = $data;
			$this->Session->write("PRODUCTOS",$productos);
			die();
		}

		if (isset($this->request->query["id"]) && !empty($productos)) {
			foreach ($ids as $key => $value) {
				if($value == $this->request->query["id"]){
					unset($ids[$key]);
				}
			}
			$this->request->data = $productos[$this->request->query["id"]];
		}

		$productos 	= 	$this->Product->find("all",["conditions"=> ["Product.state" => 1, "Product.deleted" => 0, "Product.id !=" => $ids ] ]);
		$datos 		=	$productos;
		$productos 	= 	[];

		$this->loadModel("Config");

      $config         = $this->Config->findById(1);
      $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
      $factorImport   = $config["Config"]["factorUSA"];

		foreach ($datos as $key => $value) {
			$productos[] = ["name"=> $value["Product"]["part_number"]." - ".$value["Product"]["name"], "value" => $value["Product"]["id"], "data-cost" => $value["Product"]["type"] == 2 ? $value["Product"]["purchase_price_cop"] : round(($value["Product"]["purchase_price_usd"] * 1.1) * $trmActual) ] ;
		}
		$this->set("productos",$productos);
		$entrega 								= Configure::read('variables.entregaProduct');
		$this->set("entrega",$entrega);
		$this->set("number",$number);
	}

	public function order_products(){
		$this->autoRender = false;
		if (isset($this->request->data["order"])) {
			$order 	   = $this->request->data["order"];
			$productos = $this->get_productos();
			$num = 1;
			foreach ($order as $key => $number) {
				$productos[$number]["Producto"]["number"] = $num;
				$num++;
			}
			$this->Session->write("PRODUCTOS",$productos);
		}
	}

	private function get_productos(){
		return is_null($this->Session->read("PRODUCTOS")) ? $this->delete_productos() : $this->Session->read("PRODUCTOS");
	}

	public function list_productos(){
		$this->layout = false;
		$productos = $this->get_productos();
		$this->set("productos",$productos);
	}

	public function edit($id = null,$bot = null) {

		$id 				= $this->desencriptarCadena($id);
		if (!$this->QuotationsMarketing->exists($id)) {
			throw new NotFoundException(__('Invalid quotations marketing'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$productos = $this->get_productos();
			$this->request->data["QuotationsMarketing"]["total"] = 0;

			if (!empty($productos)) {
				foreach ($productos as $key => $value) {
					unset($productos[$key]["Product"]);
					if (!isset($value["Producto"]["iva"])) {
						$productos[$key]["Producto"]["iva"] = 1;
					}
				}
				$this->request->data["QuotationsMarketing"]["products"] = json_encode($productos);				
			}else{
				$this->request->data["QuotationsMarketing"]["products"] = null;					
			}

			if ($this->QuotationsMarketing->save($this->request->data)) {
				$this->Session->write("PRODUCTOS",[]);
				$this->Session->setFlash(__('La infomación fue guardada correctamente'),'Flash/success');
				if(is_null($bot)){
					return $this->redirect(array('action' => 'index'));
				}else{
					return $this->redirect(array('action' => 'index',$bot));					
				}
			} else {
				$this->Session->setFlash(__('La infomación no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('QuotationsMarketing.' . $this->QuotationsMarketing->primaryKey => $id));
			$this->request->data = $this->QuotationsMarketing->find('first', $options);

			if (!empty($this->request->data["QuotationsMarketing"]["products"])) {
	        	$products = $this->object_to_array(json_decode($this->request->data["QuotationsMarketing"]["products"]));
	        	$this->loadModel("Product");
	        	foreach ($products as $key => $value) {
	        		$this->Product->recursive = -1;
	        		$products[$key]["Product"] = $this->Product->findById($key)["Product"];
	        		if (!isset($value["Producto"]["iva"])) {
						$products[$key]["Producto"]["iva"] = 1;
					}
	        	}
	        	$this->Session->write("PRODUCTOS",$products);
	        }else{
	        	$this->delete_productos(true);
	        }

		}
		$this->loadModel("Note");
		$this->loadModel("User");
		$notasPrevias 		= $this->Note->data_notes_previas();
        $conditions 		= $this->Note->data_conditions_negocio();
        $descriptives 		= $this->Note->data_notes_descriptiva();
		$headers 			= $this->QuotationsMarketing->Header->find('list');

		$notas_previas   = array();
        foreach ($notasPrevias as $key => $value) {
        	$notas_previas[$value["Note"]["id"]] = $value["Note"]["name"];
        }

        $formas_pago = array();
        foreach ($conditions as $key => $value) {
        	$formas_pago[$value["Note"]["id"]] = $value["Note"]["name"];
        }

        $notas_descriptivas = array();
        foreach ($descriptives as $key => $value) {
        	$notas_descriptivas[$value["Note"]["id"]] = $value["Note"]["name"];
        }

        $usersRol	 	= $this->User->role_asesor_comercial_users_all_true(true);

        $users 		 	= array();
        foreach ($usersRol as $key => $value) {
        	$users[$value["User"]["id"]] = $value["User"]["name"];
        }

		$this->set(compact('headers','notas_previas','formas_pago','notas_descriptivas','users','bot'));
	}

	public function delete($id = null) {
		$this->QuotationsMarketing->id = $id;
		if (!$this->QuotationsMarketing->exists()) {
			throw new NotFoundException(__('Invalid quotations marketing'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->QuotationsMarketing->delete()) {
			$this->Flash->success(__('The quotations marketing has been deleted.'));
		} else {
			$this->Flash->error(__('The quotations marketing could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

<?php
App::uses('AppModel', 'Model');

App::uses('HttpSocket', 'Network/Http');

class Product extends AppModel {

	public $virtualFields = array(
	    'transito' => 'transito(Product.id)',
	    "categoria" => '(SELECT name from categories where id = Product.category_id)',
	    "imagen_categoria" => '(SELECT imagen from categories where id = Product.category_id)',
	);

	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'part_number' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'list_price_usd' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		)
	);

	public $belongsTo = array(
		'Brand' => array(
			'className' => 'Brand',
			'foreignKey' => 'brand_id'
		),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id'
		)
	);

	public $hasMany = array(
		'QuotationsProduct' => array(
			'className' => 'QuotationsProduct',
			'foreignKey' => 'product_id',
			'dependent' => false
		),
		'Cost' => array(
			'className' => 'Cost',
			'foreignKey' => 'product_id',
			'dependent' => false
		),
		'FlowStagesProduct' => array(
			'className' => 'FlowStagesProduct',
			'foreignKey' => 'product_id',
			'dependent' => false
		),
		'ImportProduct' => array(
			'className' => 'ImportProduct',
			'foreignKey' => 'product_id',
			'dependent' => false
		),
		'Bullet' => array(
			'className' => 'Bullet',
			'foreignKey' => 'product_id',
			'dependent' => false
		)
	);

	public $hasAndBelongsToMany = array(
		'FeaturesValue' => array(
			'className' => 'FeaturesValue',
			'joinTable' => 'products_features_values',
			'foreignKey' => 'product_id',
			'associationForeignKey' => 'features_value_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

	public function setLlc(){
		
		$productsLLC  = [];
		try {
			$params 	= ["sortfield"=>"t.rowid","sortorder"=>"ASC","limit"=>"500","sqlfilters"=>""];			
			$HttpSocket = new HttpSocket(["ssl_verify_peer"=>false,"ssl_verify_host"=>false]);

			$response 	= $HttpSocket->get($this->API.'products/', $params ,["header" => ["DOLAPIKEY" => "Kebco2020**--","Accept"=>"application/json"] ]);

			$code 		= $response->code;

			if ($code == 200) {
				$productsLLC = json_decode($response->body());
				if (!empty($productsLLC)) {
					foreach ($productsLLC as $key => $value) {
						$this->updateAll(
							["Product.id_llc" => $value->id],
							["LOWER(Product.part_number)" => strtolower($value->ref)]
						);
					}					
				}
			}

		} catch (Exception $e) {

		}
			
	}

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('Product.id' => $id, "deleted" => 0);
		return $this->find('first',compact('conditions'));
	}

	public function get_products(){
		$this->recursive 	= -1;
		$conditions 		= array("deleted" => 0);
		$order				= array('Product.id' => 'desc');
		return $this->find('all',compact('order','conditions'));
	}

	public function get_referencia($categories = array()){
		$this->recursive 	= -1;
		$conditions 		= array("deleted" => 0, "state" => 1);
		if (!empty($categories)) {
			$conditions["Product.category_id"] = $categories;
		}
		$fields				= array('Product.name','Product.part_number','Product.brand_id',"Product.id");
		return $this->find('all',compact('fields','conditions'));
	}

	public function find_name($name){
		$this->recursive 	= -1;
		$fields 			= array('Product.id');
		$conditions			= array('deleted' => 0,'OR' => array(
							            'Product.name' => $name,
							            'Product.part_number' => $name
							        )
								);
		$dato 				= $this->find('first',compact('conditions','fields'));
		return $dato['Product']['id'];
	}

	public function exist_partNumber($parte){
		$this->recursive 	= -1;
		$conditions 		= array('Product.part_number' => $parte, "deleted" => 0);
		return $this->find('count',compact('conditions'));
	}

	public function find_seeker($texto){
		$this->recursive 	= -1;
		$fields 			= array('Product.id');
		$limit 				= 20;
		$conditions			= array('OR' => array(
							            'LOWER(Product.name) LIKE' 			=> '%'.$texto.'%',
							            'LOWER(Product.description) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(Product.part_number) LIKE' 	=> '%'.$texto.'%',
							            'LOWER(Product.brand) LIKE' 		=> '%'.$texto.'%'
							        ),
									'Product.deleted' => 0
								);
		return $this->find('all',compact('conditions','fields','limit'));
	}

	public function exist_product($referencia, $product_id = null){

		if(!is_null($product_id)){
			$countData = $this->find('count',["conditions" => ["Product.id" => $product_id, "Product.part_number" => $referencia, "Product.deleted" => 0]]);
			if($countData > 0){
				return 1;
			}else{
				$conditions			= array('Product.part_number' => $referencia, "deleted" => 0);
				$countData  		= $this->find('count',compact('conditions'));

				if($countData > 0){
					return 0;
				}else{
					return 1;
				}

			}
		}

		$conditions			= array('Product.part_number' => $referencia, "deleted" => 0);
		return $this->find('count',compact('conditions'));
	}

	public function get_data_products_imports($ids_products){
		$this->recursive 	= -1;
		$conditions 		= array('Product.id' => $ids_products);
		return $this->find('all',compact('conditions'));
	}

	public function getProductAndBrandsWithQuantityForImporter($products,$quantities, $delivery = null){

		$this->recursive = -1;
		$producsInfo = $this->findAllById($products);
		$dataImporter = array();

		if(!empty($producsInfo)){
			foreach ($producsInfo as $key => $value) {
				$dataArr = array(
					"id_product"				=> $value["Product"]["id"],
					"quantity"					=> $quantities['Cantidad-'.trim($value["Product"]["id"])],
					"brand"						=> $value["Product"]["brand_id"]
				);
				if(!is_null($delivery)){
					$dataArr["delivery"] = $delivery[$value["Product"]["id"]];
				}
				$dataImporter[$value["Product"]["brand_id"]][] = $dataArr;
			}
		}

		return $dataImporter;

	}

}
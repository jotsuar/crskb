<?php

require_once '../Vendor/spreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


set_time_limit(0);
ini_set('memory_limit', '-1');


App::uses('AppController', 'Controller');


class ProductsController extends AppController {

	public $components = array('Paginator');

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('block_products','bot_info','bot_reference','update_price_batch','generate_seo_data');
    }

    public function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}

    public function limpiarRutaCategoria($ruta) {
	    // 1. Separa el string en un array usando '-->' como delimitador.
	    $partes = explode('-->', $ruta);

	    // 2. Limpia los espacios en blanco de cada elemento para una comparación segura.
	    $partes = array_map('trim', $partes);

	    foreach ($partes as $key => $value) {
	    	$partes[$key] = ucfirst(mb_strtolower($value));
	    }

	    // 3. Cuenta cuántas partes hay.
	    $num_partes = count($partes);

	    // 4. Si hay 2 o más partes, procede a comparar.
	    if ($num_partes >= 2) {
	        // Compara el último elemento ($partes[$num_partes - 1]) con el penúltimo ($partes[$num_partes - 2]).
	        if ($partes[$num_partes - 1] === $partes[$num_partes - 2]) {
	            // Si son iguales, elimina el último elemento del array.
	            array_pop($partes);
	        }
	    }

	    // 5. Vuelve a unir las partes en un string, separadas por ' --> '.
	    return "Inicio|".implode('|', $partes);
	}


    public function exports_products_prestashop(){
    	$this->autoRender = false;

    	$this->Product->hasMany = [];
    	$products_array = $this->Product->find("all",["conditions"=>["garantia_id"=>1, "deleted" => 0, "img !=" => "default.jpg",'meta_description !=' => null ]]);

    	$categoriesData = $this->getCategoryInfo(true);
		unset($categoriesData[0]);

    	// 2. RECOLECTAR TODAS LAS CARACTERÍSTICAS ÚNICAS
		$all_feature_names = [];
		foreach ($products_array as $product) {
		    if (!empty($product['FeaturesValue'])) {
		        foreach ($product['FeaturesValue'] as $feature) {
		            // Usamos trim() para limpiar espacios en blanco
		            $feature_name = (trim($feature['feature_name']));
		            if (!in_array($feature_name, $all_feature_names)) {
		                $all_feature_names[] = $feature_name;
		            }
		        }
		    }
		}
		// Ordenar alfabéticamente para un resultado consistente
		sort($all_feature_names);


		// 3. CONSTRUIR LAS CABECERAS DEL EXCEL
		$headers = [
		    // 'name',
		    // 'description',
		    // 'short_description',
		    'reference',
		    // 'meta_title',
		    // 'meta_description',
		    // 'brand',
		    'category',
		    // 'image',
		    // 'feature_name',
		    // 'feature_value'
		];

		// Añadir las cabeceras de características dinámicas
		// foreach ($all_feature_names as $feature_name) {
		//     $headers[] = 'Feature: ' . $feature_name;
		// }


		// 4. PROCESAR CADA PRODUCTO PARA CREAR LAS FILAS
		$rows = [];
		foreach ($products_array as $product) {
		    // Mapear las características del producto actual para una búsqueda rápida
		    // $product_features = [];
		    // if (!empty($product['FeaturesValue'])) {
		    //     foreach ($product['FeaturesValue'] as $feature) {
		    //         $product_features[(trim($feature['feature_name']))] = $feature['name'];
		    //     }
		    // }

		    // Fila para el producto actual
		    $current_row = [];

		    $ruta 		   = $this->validate_image_products($product['Product']['img']);
		    // --- Datos básicos ---
		    // $current_row[] = str_replace($product["Product"]["part_number"], "", $product['Product']['name']). ", ".$product["Product"]["part_number"] ;
		    // Limpiamos el HTML de la descripción para el Excel
		    // $current_row[] = ($product['Product']['description']);
		    // $current_row[] = ($product['Product']['short_description']);
		    // $current_row[] = $product['Product']['part_number']; // reference
		    $current_row[] = str_replace($product["Product"]["part_number"], "", $product['Product']['meta_title']). ", ".$product["Product"]["part_number"]; // reference
		    // $current_row[] = $product['Product']['meta_description']; // reference
		    // $current_row[] = trim($product['Product']['brand']); // reference
		    // $current_row[] = $product['Product']['list_price_usd']; // price
		    // $current_row[] = $product['Product']['purchase_price_wo']; // cost_price
		    // $current_row[] = $this->limpiarRutaCategoria($categoriesData[$product["Product"]["category_id"]]); // category
		    // $current_row[] = Router::url("/",true).'img/products/'.$ruta; // image

		    $category = $this->limpiarRutaCategoria($categoriesData[$product["Product"]["category_id"]]);

		    foreach (explode("|", $category) as $keyCat => $valueCat) {
		    	$current_row[] = $valueCat;
		    }
		    
		    if(!empty($product_features)){
		    	$i = 0;
		    	foreach ($product_features as $featureName => $featureValue) {
		    		if($i == 0){
			    		$current_row[] = $featureName;
			    		$current_row[] = $featureValue;
			    		$current_row[] = 0;
			    		
			    	}else{
			    		$current_row = [
			    			null,null,null,null,null,null,null,null,$featureName,$featureValue,0
			    		];
			    	}
			    	$rows[] = $current_row;
			    	$i++;
		    	}
		    }else{
		    	$rows[] = $current_row;
		    }



		    // // --- Datos de características ---
		    // // Recorremos la lista completa de características para mantener el orden de las columnas
		    // foreach ($all_feature_names as $feature_name) {
		    //     // Si el producto tiene esta característica, añadimos su valor. Si no, un string vacío.
		    //     if (isset($product_features[$feature_name])) {
		    //         $current_row[] = $product_features[$feature_name];
		    //     } else {
		    //         $current_row[] = ''; // Celda vacía
		    //     }
		    // }
		    
		    // Añadimos la fila completa al array de filas
		    // $rows[] = $current_row;
		}


		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $spreadsheet->getProperties()->setCreator('Kebco SAS')
                    ->setLastModifiedBy('Kebco SAS')
                    ->setTitle('Productos para prestashop')
                    ->setSubject('Productos para prestashop')
                    ->setDescription('Productos para prestashop')
                    ->setKeywords('Prestashop CRM Productos')
                    ->setCategory('Prestashop');

        $spreadsheet->getActiveSheet()->fromArray(
        	$headers,  // The data to set
	        NULL,        // Array values with this value will not be set
	        'A1'         // Top left coordinate of the worksheet range where
	                     //    we want to set these values (default is A1)
	    );

	    $spreadsheet->getActiveSheet()->fromArray(
        	$rows,  // The data to set
	        NULL,        // Array values with this value will not be set
	        'A2'         // Top left coordinate of the worksheet range where
	                     //    we want to set these values (default is A1)
	    );

	    $spreadsheet->getActiveSheet()->setTitle("Productos CRM");

	    foreach (range('A', $spreadsheet->getActiveSheet()->getHighestDataColumn()) as $col) {
		    $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="productos_crm_kebco_'.time().'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 2027 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;


		// $categoriesInfoFinal = $this->getCagegoryData();

		echo "<pre>";
		// var_dump($categoriesData);
		// var_dump($categoriesInfoFinal);
		var_dump($headers);
		var_dump($rows);
		die();

		// 6. FORZAR LA DESCARGA DEL ARCHIVO
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="exportacion_productos.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		// 'php://output' envía el archivo directamente al navegador
		$writer->save('php://output');

		exit;

    	// echo "<pre>";
    	// var_dump($products);
    	echo json_encode($products,true);
    }

    public function validate_image_products($imagen){ // Validar si la imagen es nula, modelo de productos
		$ruta = $imagen;
		if ($imagen == '') {
			$ruta = "default.jpg";
		}
		return $ruta;
	}

    public function listPriceProducts($brand_id){
    	$this->autoRender = false;

    	$products = $this->Product->findallByBrandId($brand_id);
    	// $products = $this->Product->find("all",["conditions"=> ["Product.category_id" => [287,288] ] ]);

    	$partsData              = $this->getValuesProductsWo($products);
        $inventory              = $this->getTotalesStock($partsData);
        $costos                 = $this->getCosts($partsData);

        $datas 					= [];

        /*echo "<h1Descripción de Productos - Medidores y Equipos de Medición</h1>

				<h2>A continuación, se presenta una lista de productos relacionados con la medición de espesores, adherencia, rugosidad, temperatura y humedad, entre otros. Cada producto incluye su descripción, especificaciones y accesorios incluidos. </h2> <br> <ol>";
				*/
		$i = 1;
    	foreach ($products as $key => $value) {
    		$precio = $this->getPriceForProduct($value,'cop',true,$costos);
    		if($precio > 0){
    			$ruta = $this->validate_image_products($value['Product']['img']);
    			$datas[] = [
    				"Producto" => $value["Product"]["name"], 
    				"sku" =>$value["Product"]["part_number"], 
    				"precio" =>  "$".number_format($precio)." COP",
    				"imagen_url" =>  Router::url("/",true).'img/products/'.$ruta,
    				"video_url" => !empty($value["Product"]["url_video"]) ? $value["Product"]["url_video"] : '',
    				"pdf_url" => !empty($value["Product"]["manual_1"]) ? Router::url("/",true).'img/products/'.$value["Product"]["manual_1"] : '',
    			];
    			// echo "<li>";
    			// echo "<b>".$value["Product"]["name"]."</b>";
    			// echo "<ul>";
    			// echo "<li> SKU: ".$value["Product"]["part_number"]."</li>";
    			// echo "<li> Precio: "."$".number_format($precio)." COP"."</li>";
    			// echo "<li> Descripcion: <br>".$value["Product"]["description"]."</li>";
    			// $datas[] = [
    			// 	"Producto" => $value["Product"]["name"],
    			// 	"SKU" => $value["Product"]["part_number"],
    			// 	"Precio" => "$".number_format($precio)." COP",
    			// 	"Descripcion" => trim(html_entity_decode(strip_tags( $value["Product"]["description"])))
    			// ];
    			// echo "</ul>";
    			// echo "</li> <br>";
    			//echo "Producto: ".$value["Product"]["name"]." | Referencia o SKU: ".$value["Product"]["part_number"]." | Precio: $".number_format($precio)." COP<br>";
    		}
    	}

    	// echo "</ol>";
    	$this->response->type('json'); // Establecer el tipo de contenido a JSON
	    $this->response->body(json_encode($datas)); // Convertir el array a JSON y asignarlo al cuerpo de la respuesta

	    // return $this->response;
    	// echo json_encode($datas);
    	// getPriceForProduct
    	// var_dump($products);
    }

    public function bot_reference(){
    	$this->autoRender = false;
    	$reference = isset($this->request->data["reference"]) ? $this->request->data["reference"] : '';

    	$this->Product->hasMany = [];
    	$partsData 				= [];
    	$costos 				= [];
    	$product = $this->Product->findByPartNumberAndDeletedAndState($reference,0,1);
    	if (empty($product)) {
			$product = $this->Product->find("first", [ "conditions" => [ 
									'Product.deleted' => '0',
									'Product.state' => '1',
									'OR' => array(
							            'LOWER(Product.name) LIKE' 			=> '%'.$reference.'%',
							            'LOWER(Product.description) LIKE' 	=> '%'.$reference.'%',
							            'LOWER(Product.part_number) LIKE' 	=> '%'.$reference.'%')
							        ] ] );
		}
		if(!empty($product)){
			$partsData				= $this->getValuesProductsWo([$product]);
			$costos 				= $this->getCosts($partsData);
		}

		$this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
		return json_encode( ["product" => $product, "partsData" => $partsData, "costos" => $costos, "trmActual" => $trmActual ] );

    }

    public function bot_info($page = 1){


    	$conditionData = 1;
		$referencias = $this->postWoApi(["number"=>$conditionData],"inventory_exists");
		$referencias = $this->object_to_array($referencias);
		$referencias = Set::extract($referencias,"{n}.Referencia");
		$this->Product->hasMany = [];
		$products    = $this->Product->find("all",["conditions"=>["part_number"=>$referencias], "page"=> $page, "limit" => 100]);

		$this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $grupoMax 		= $this->Product->Category->field("MAX(grupo)");
        $costos 		= [];


        try {
			if (!empty($products)) {
				$partsData				= $this->getValuesProductsWo($products);
				// $precios 				= $this->getPrices($partsData);
				$costos 				= $this->getCosts($partsData);
			}
					
		} catch (Exception $e) {
			$products = array();			
		}

		$this->set("products",$products);
		$this->set("costos",$costos);
		$this->set("trmActual",$trmActual);
		$this->set("page",$page);

    }

    public function delete_details(){
    	$this->autoRender = false;

    	$this->loadModel("ImportRequestsDetail");

    	foreach ($this->request->data["details"] as $key => $value) {
    		$this->ImportRequestsDetail->delete($value);
    	}

    	$this->Session->setFlash('Solicitudes eliminadas correctamente.','Flash/success');

    }

    public function getSqlForupdateprices(){
    	$this->autoRender = false;
    	$products = $this->Product->find("list",["fields" => ["id", "purchase_price_usd" ], "conditions" => ["brand_id" => 4 ] ] );

    	foreach ($products as $id => $price) {
    		echo "INSERT INTO `costs` (`product_id`, `purchase_price_usd`, `pre_purchase_price_usd`, `user_id`, `created`, `modified`) VALUES ($id, $price, $price, 4,NOW(), NOW());"."<br>";
    	}
    	die();
    }

    public function no_transit($productId) {
    	$this->autoRender = false;
    	$productId = $this->decrypt($productId);
    	$numberActual = $this->Product->query('select transito('.$productId.') as Transito');

    	if (!empty($numberActual) && isset($numberActual[0][0]["Transito"]) && $numberActual[0][0]["Transito"] > 0 ) {
    		$quantity = $numberActual[0][0]["Transito"];
    		$inventory = array(
	    		"Inventory" => array(
	    			"product_id" 	=> $productId,
	    			"warehouse"		=> "Transito",
	    			"quantity" 		=> intval($quantity),
	    			"type" 			=> Configure::read("TYPES_MOVEMENT.SALIDA_INVENTARIO"),
	    			"type_movement"	=> Configure::read("INVENTORY_TYPE.SALIDA_MANUAL"),
	    			"reason"		=> "Cuadre de unidades en tránsito",
	    			"user_id"		=> AuthComponent::user("id"),
	    		)
	    	);
    		$this->loadModel("Inventory");
	    	$this->Inventory->create();
	    	$this->Inventory->save($inventory);
	    	$this->Session->setFlash('Productos acutalizados correctamente.','Flash/success');
    	}
    	$query = $this->request->query;
    	$this->redirect(["action"=>"index","?" => $query ]);
	}

    public function products_rotation(){
    	$products 		= $this->Product->find("all",["conditions" => ["min_stock >=" => 0,"reorder >=" => 0,'max_cost >' => 0, "deleted" => 0, "Product.state" => 1], "recursive" => -1 ]);
    	$categoriesData = $this->getCategoryInfo(true);

    	if (!empty($products)) {
    		$partsData	 	= $this->getValuesProductsWo($products);

    		$copyProducts 	= $products;
    		$products 		= [];

    		foreach ($copyProducts as $key => $value) {
    			$products[trim($value["Product"]["brand"])][trim($value["Product"]["categoria"])][] = $value;
    		}

    	}else{
    		$partsData 		= [];
    	}

    	$this->loadModel("ImportRequest");
    	$totalRequest 	 = 0;
    	// $internacional 	 = null;
    	// $requests        = $this->ImportRequest->getImportRequestBrands($internacional,2);

    	// foreach ($requests as $key => $value) {
        //     $totalRequest += count($value["ImportRequestsDetail"]["details"]);
        // }

    	$this->set(compact("products","categoriesData","partsData","totalRequest"));
    }

    public function new_panel($internacional = null){
		$this->validateRoleImports();
        $this->loadModel("ImportRequest");
        $this->loadModel("Config");
        $this->loadModel("Notes");
        $this->loadModel("Brand");
		$this->loadModel("Reject");

        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $factorImport   = $config["Config"]["factorUSA"];
		$requests        = $this->ImportRequest->getImportRequestBrands($internacional,2);
        $products        = $this->ImportRequest->getImportRequestProducts($internacional,2);
        $inventioWo      = $this->getValuesProductsWo($products);

        $totalRequest 	 = 0;

        $this->Brand->recursive = -1;

        foreach ($requests as $key => $value) {
            $requests[$key]["Brand"]["children"] = $this->Brand->findAllByBrandId($value["Brand"]["id"]);

            foreach ($value["ImportRequestsDetail"]["details"] as $keyD => $valueD) {
            	if ($valueD["type_request"] != 4) {
            		unset($value["ImportRequestsDetail"]["details"][$keyD]);
            	}
            }
            $totalRequest += count($value["ImportRequestsDetail"]["details"]);
        }

        $totalRechazo = $this->Reject->find("count");

        $notas = $this->Notes->find("list",array("conditions" => array("type" => 4))); 

		$this->set(compact("requests","trmActual","factorImport","notas","totalRechazo","internacional","inventioWo","totalRequest"));
	}

    public function solicitar($productId) {
    	$this->autoRender = false;
    	$total = $this->request->data["total"];

    	$this->loadModel("ImportRequest");

		$this->Product->recursive = -1;
		$product 			= $this->Product->findById($productId);

		$nacional 			= 0;

		$brandProduct 		= $product["Product"]["brand_id"];
		$requestInfoId 		= $this->ImportRequest->getOrSaveRequest($brandProduct,$nacional,1);
		$requestDetailId  	= $this->ImportRequest->ImportRequestsDetail->saveDataDetail(
			$requestInfoId,
			AuthComponent::user("id"),
			4,
			"Reposición manual por bajo inventario",
			null,
			$nacional
		);
		$this->loadModel("ImportRequestsDetailsProduct");
		$this->ImportRequestsDetailsProduct->saveDetailProduct($requestDetailId,[["id_product" => $product["Product"]["id"], "quantity" => $total ]]);

	}

    public function set_llc(){
    	$this->autoRender = false;
    	$this->Product->setLlc();
    	$this->redirect(["action"=>"index"]);
    	$this->Session->setFlash('Productos acutalizados correctamente.','Flash/success');
    }

    public function others_references(){
    	$this->layout = false;

    	$brandId = $this->request->data["brandId"];
    	$productsIds = $this->request->data["productsIds"];
    	$currency = $this->request->data["currency"];
    	$importId = $this->request->data["importId"];
    	$requestId = $this->request->data["requestId"];

    	$categoriesData = $this->getCategoryInfo(true);
    	$otherProducts  = $this->Product->find("all", ["limit" => 10, "conditions" => 
    		[ 
    			// "NOT" => [ "Product.id" => $productsIds ], 
    		"Product.brand_id" => $brandId, "Product.deleted" => 0, "Product.state" => 1, 
    		  'OR' => array(
				            'LOWER(Product.part_number) LIKE' 			=> '%'.mb_strtolower(trim($this->request->data["search"])).'%',
				            'LOWER(Product.name) LIKE' 					=> '%'.mb_strtolower(trim($this->request->data["search"])).'%',
				        )
    		 ], 
    	"recursive" => -1, ] );

    	$partsData = [];
    	if (!empty($otherProducts)) {
    		$partsData				= $this->getValuesProductsWo($otherProducts);
    	}


    	$this->set(compact("categoriesData","otherProducts","currency","importId","requestId","partsData"));
    }

    public function arrayToHtmlList($array) {
	    $html = "<ul>\n";
	    foreach ($array as $key => $value) {
	        $html .= "    <li><strong>{$key}:</strong> {$value}</li>\n";
	    }
	    $html .= "</ul>";
	    return $html;
	}

	public function generateDescriptionProduct(){
		$this->autoRender = false;
		$responseAi = $this->callOpenAIProduct($this->request->data["nombre"],$this->request->data["referencia"], $this->request->data["description"], $this->request->data["marca"] );

		$resp = count($responseAi["output"]) == 2 ? $responseAi['output'][1]['content'][0]["text"] : $responseAi['output'][0]['content'][0]["text"];


		if(isset($responseAi["error"])){
			return json_encode(["descripcion"=>$this->request->data["description"]]);
		}
		// $resp = $responseAi['choices'][0]['message']['content'];

		//  Eliminar caracteres extraños al inicio
		$json_limpio = trim(str_replace('json',"",$resp ) );
	    $json_limpio = trim(str_replace('```',"",$json_limpio ) );
	    $json_limpio = preg_replace('/^[^{\[]+/', '', $json_limpio); // Quita cualquier texto antes del JSON

	    //  Decodificar JSON a un array de PHP
	    $data = json_decode($json_limpio, true);

	    return json_encode($data);
	}

	public function update_price_batch(){
		$this->autoRender = false;

		$product = $this->Product->find("first",["conditions"=>["deleted"=>0, "garantia_id" => 0, "brand_id" => [44,70,132,4,9,78,5,39,66,33,137,17,130,10,100,77,148,58,150], "category_id !=" => [1264,1257,631,1263,1265,1266], "brand not like" => "'%"."kebco"."'", "OR" => 
			[
			"part_number" => ['17E852','17E825','17E827','17E831','24W923','24W925','24W929','249617','17H572','257030','17E842','25M224','26A672','571267','17D899','17E854','17H450','17H455','17H461','288420','241705','24C854','24C855','WR1221','FFT310','286517','LL5321','243103','246453','248936','224081','246215','243161','244279','287032','24G6XX','XTR722','XHD001','XHD010','248837','XHD517','24F150','24F151','24F158','24F159','24F152','24F153','24F156','24F157','288488','18B260','248213','295616','226940','226941','226942','226946','237129','256200','244067','206994','243176','249125','181072','248157','287689','288705','243080','249052','235474','288817','187190','262004','277064','277062','287553','248212','17H467','1065203','1035203','10C25194','106004I','106002','1015000','K7-1','HC/EE2012G','331210','DLTG18','ARM020','AGU03621290C','17H474','EE3030A','K7-1E','674','6000','6012PRO-10G','DCFSCP20GZ','CL21549','SE 12 -115','206995','10TC4UN','10TC5UN','10TC6UN','4367','4361','10C03547','SPG1-G','F1-G','17E574','22909','1002005','1014102','289992','289003','16U918','16U920','248942','NV3-703-50','D51D05','DF4888','652414','F3-G','FS1-G','FS3-G','N1-G','N3-G','FN1-G','FN3-G','PosiTest PC','200B3-G','200C1-G','200C3-G','200D1-G','UTGC3-G','UTGM1-G','UTGM3-G','PRBUTGM-C','DPM1-G','DPM3-G','DPMS1-G','SPG3-G','RTRH1-G','ATM','ATA','AIR','17H469','PPS2527HAI','PPS2533HAI','E3030HCI','E4040HA-20','17H448','288747','04-900','RPBKIT-25205','AIF001','ATG001','288932','245924','236158','236153','236154','206758','207199','222698','206760','225883','24C728','24C729','224854','235534','236629','24C293','24C522','231414','231413','238157','240209','224571','222695','204536','226086','243340','236661','236662','236663','68260','08-400-01','PS905700D','25A503','NV3-722','NV3-724','NV3-725','69274','17-201-12','24Y680','AR390SS','08-420-03','XO1','XO4','XO6','XO55','289006','289009','289994','289996','288931','2888965','288983','288975','SSTKITP3','SSTKITD1','SSTKITD3','SSTKITP1','10BEDP16TE','LPDKITB','LPDKITC','24j603','289255','289258','DLTG12','DLTG24','03-901-G','16-810','03-985','17E839','16w896','255261','248243','248244','248245','248246','243297','4012-10G','16W894','DB3311','DB3366','DF3311','652046','E3032HA-CAT','AR620','KITMARCO-1','25d235','RE36DL','RE40','RE270','RE49','RE48','17N264','PPS2527HA','PPS2527KAI','PPS2533HCI','E3032HG-20','E4040HC','E4040HG','E4042HV-20','E4042KV-20','VB5535VGEA411','VB5040HAEA411','4012-10C','4012-42HV','4012-42KV','6012PRO-20G','6012PRO-20G-V','8012PRO-35HG','02-105','02-8700.10','080175AE','1-3V3.35','1-3V3.65','10-115','10-116','10-300','10-311','10-319','100728P','105-705','105-709','110-402','1219D','125-560','125-568','1320-0015','137095-B00','137097-B00','137231-B00','138223-B00','140R','1431D','1456D','14880-4K136BAB8T-104','14880-R-T-C','150-683','150-703','150-707','1510-0057','1530.APP','1604D','1605D','1606D','1631-F','17210-Z6L-010','184TBDR17031','200 049 650','2088-343-135','2088-394-154','2088-713-534','21441U','215TBDW17029','2184402U','2184404U','2383AU','24-786-11S','2460U','25 098 11-S','25080-116','25090-2K136BBB5T-036','2900U','2X525-5','3-3V8.00','3300-0016','3403D','3430-0390','356447-0270-G1','3CP1120','4-3V4.12','4101N','440-105','462181A','468855A','4PPX25GSI','4SF35GS1','4YM37','5038.C2','51771U','5199701U','520-738','52145U','5218301U','5CP3120G1','5PP3140','66DX35GG1','66DX40GG1','67DX39G1I','67PPX39G1I','6AMA1','7475K','7475S','7800-GLOSSY','7896-0616','7896-2315','7896-4904','7896-7415','7896-8026','8000-543-238','9274-1080','94-230-36','94-375-18','953A02','988VR-50','9910-D252GRGI','9910-D30GRGI','AA430ML-1-PP-50','ACI6000','AGU02421290C-I','AGU03621290C-SS','AGU03621290C5000','AGU036PPS','AGU036ST2000-I','AGU036ST2605-I','AGU036YG5000','AGU04821290C-I','AGU048SS21290C-I','AGU048ST3300-L51','AGU060','AGU070-S','AGU070','AGU34421290C','AGU344YG4060SS','AHS235','AHS270-AFP','AHS275-AFP','AHS280-AFP','AHS280','AHS285','AHS330-6K','AHS330-SSQC','AHS380','AHS830','AHS835','AL344','AL416','AL693','AR11-063GHT50','AR1855','AR1857','AR20242','AR20821','AR20831','AR20890','AR2546','AR2864','ARJ040-QC-S','ARJ040-QC','ARJ040-S','ARJ055','ARJ703-QC','ARM010','ARM581-SS','ATG002','ATG050','ATL040','ATN7K-35','AWA036SS','B3WEM3-S','E4032KLDG','E4032KLDGE','HCEE3010G','HCEE2015A','HCEE2015G','HCEE2015V','WMEE3010G','WMEE2015A','WMEE2015G','WMEE4020A','EE3010A','EE2015G','EE3015G','EE4020A','EE3530G','EE4030A','EE3035A','EE4035A','EE3540A','EE7020A','B4035E3G403','B4560E3G600','B4035HCP407','4012-15G','4230VB-30G1','TRHDCV5535HG','1003008PKA','KEBD4042V-E','233468','07-123','SPRGD','pruebas1','243281','17E605','17N166','17N163','RAX517','17C487','LLG7','I3-7-2A','prueba123','ZMM 5000','25D492','APC201','247955','246978','247616','APC204','17G340','246101','25P772','246679','25P770','246678','246102','K1-3','K1R','K1R-6','K1R','278860','26A587','26A620','K70FH1','ELS 225','4313-0125-200','10L850120','KITBLAST','277241','262950','17Y042','KEB-23024','KEB-23025','M508G','T10','L50','L50RCA','WMC','MT1000','SL','SNP','SP','SSA','M1250','SCT','ST9','TURBORAIL','LS-180','LS-747','LS-350','LS-5050','LS-400','LS-320','LS-60L','LS-310','LS-1200','LS-1700','LS-100','LS-200','LS-350','LS-500','LS-600','SPR457','SPR456','SPR550','SPR552','SPR321','SPR450','SPR467','SPR468','SPR473','SPR474','SPR481','SPR553','SPR555','SPR623','SPR630','SPR483','SPM001','SPM008','SPM009','SPM007','932','19Y128','17E574','17E605','DPM1-G','NV2029','220310-01','IS15004D/SKID','IS11506D/SKID','EZO11506D/-KUB','IS1156E-3-460','IS150045E-3','05113M','KITCOUTO','RPC-900','CPC-80/125','04534','RCH-300','RCH-200','RCH-100','RCH-20','Chorreadora Racohi 60 l','Freeblast 9 litros','1700','3400','4500','RCH-20-LP','RCH-60-LP','RCH-100-LP','RCH 1030','RCH 1020','RCH 1050','RCH 1070','RCH 1070 DUAL','RCH 1020','360 C-T','360 L-T','Mangueras de Chorro Premium','Mangueras de Chorro Extra-Premiu','Acoples de Chorro Metálicos','Acoples de Chorro Nylon','Maxi-circublast','RCH-S-30','Mini-circublast','Super-Miniblast','Salas de chorreado','FSV','Micro I y II','Pinch','RAC-7','RGV','Thompson I y II','RCV-125','RCV-50','CV-Compact','Combo','Auto Air','RCH-S-60','04279','DMH-125/E','DMH-125 PNEUMATIC','Gatillo Magnético','WE-125','RC-I','UNI-HOSE','Portaboquillas de chorro','Stormblast II TCR','Boquillas de chorro XL','Stormblast SBP','Stormblast SBA','Syclone SSY','Silicio SBS','Hi-Velocity HVA','Medias SBM','Cortas SBC','Stormblast SBAN','RCH-S-90','Boro BCB','Stick-Up RSU','Laterales AAM','Mini-Giroblast RGBN y Maxi-Girob','Fan Blast FB','Circublast RCB','Water-Jet RWJ','S/VB5535HVEA411','S/VB8035HVEA406','S/B1230HVE105/VHL M6','STRIPEMASTER 3','M-8','M-25','M-60','OXALB','OXALM','GRANALLA DE ACERO ESFERICA','GRANALLA DE ACERO ANGULAR','GHP 30/60MESH','H2682','KEB-INT','ROD-200MM','H8','H7','H6','H5','H4','H3-L','H2','H3-R/L','H3-R','H3-R-2','H265','MIXER-MI','MIXER ME','MAE-39D','DMP7672','DM4139C','S250','A2','A5','A8','R850','LEOPARD','FALCON','V3 Vacuum','V12 Vacuum','19D519','19D521','20B305','19F545','19F550','278862-USADO','288932-USADO','25N669','04279','04038','04469','05466S-60','06427','04357','04355','04356','MINI PIPEPAINT','07-755-M','06077','09075','LACS681CL','CABINACOUTO'], 
			"category_id" => ["288",'287',268,1128,1129,541,544,574,613,348,891,909,265,938,1269,1270,1271,1272,1273,564,563]  
		] ], "recursive" => -1, "fields" => ["name","part_number","description","id","brand"], "order" => ["id"=>"DESC"] ]);


		$product = $this->Product->find("first",["conditions"=>[ "part_number LIKE" => "%LS-%", "id !=" => 2423, "garantia_id !=" => [1,3] ], "recursive" => -1]);

		if(empty($product)){
			return true;
		}

		$responseAi = $this->callOpenAIProduct($product["Product"]["name"],$product["Product"]["part_number"], $product["Product"]["description"], $product["Product"]["brand"] );

		if(isset($responseAi["error"])){
			$product["Product"]["garantia_id"] = 2;
			$this->Product->save($product);
			var_dump($product);
			return json_encode($responseAi);
		}

		$resp = count($responseAi["output"]) == 2 ? $responseAi['output'][1]['content'][0]["text"] : $responseAi['output'][0]['content'][0]["text"];

		//  Eliminar caracteres extraños al inicio
		$json_limpio = trim(str_replace('json',"",$resp ) );
	    $json_limpio = trim(str_replace('```',"",$json_limpio ) );
	    $json_limpio = preg_replace('/^[^{\[]+/', '', $json_limpio); // Quita cualquier texto antes del JSON

	    //  Decodificar JSON a un array de PHP
	    $data = json_decode($json_limpio, true);


// 	    $json_limpio = '{
//   "sku": "SPR659",
//   "descripcion": "<h3>Descripción General</h3><p>El GVS Elipse Integra SPR659 es un sistema de protección personal 2 en 1 que combina un respirador de medio rostro con unas gafas de seguridad totalmente integradas. GVS, como marca líder mundial en tecnología de filtración, ha diseñado este equipo para ofrecer una protección respiratoria y ocular sin compromisos en un formato compacto, ligero y de bajo perfil. Con una calificación NIOSH OV/P100, este respirador protege contra una amplia gama de vapores orgánicos y contra el 99.97% de todas las partículas en el aire. Es la solución ideal para trabajar en entornos donde se requiere protección tanto respiratoria como ocular, como en la pintura, la construcción o la industria química.</p><h3>Características Principales</h3><ul><li><b>Protección Integrada 2-en-1:</b> Combina un respirador y unas gafas de seguridad en una sola unidad, eliminando los problemas de compatibilidad y asegurando un sellado perfecto tanto en la cara como alrededor de los ojos.</li><li><b>Filtración de Máximo Nivel (OV/P100):</b> Los cartuchos combinados ofrecen protección contra ciertos vapores orgánicos (OV) y cuentan con un filtro de partículas P100, la máxima eficiencia de filtración (99.97%) contra partículas de base aceite y no aceite.</li><li><b>Diseño Compacto y de Bajo Perfil:</b> Su diseño anatómico y compacto permite un campo de visión ininterrumpido y una excelente compatibilidad con otro tipo de EPP, como cascos de soldadura o protectores faciales.</li><li><b>Lente Antiempañante y Antirayaduras:</b> Las gafas integradas cumplen con el estándar ANSI Z87+ y están recubiertas para resistir el empañamiento y las rayaduras.</li><li><b>Material Hipoalergénico:</b> La máscara está fabricada con un elastómero termoplástico (TPE) de grado médico, suave y confortable, libre de látex, silicona y olores.</li></ul><h3>Especificaciones Técnicas</h3><table class=\"table\"><tbody><tr><td>Modelo</td><td>Elipse Integra®</td></tr><tr><td>Certificación NIOSH</td><td>OV/P100 (TC-84A-9372)</td></tr><tr><td>Protección Ocular</td><td>ANSI Z87+</td></tr><tr><td>Material de la Máscara</td><td>TPE de grado médico (sin látex ni silicona)</td></tr><tr><td>Material de los Filtros</td><td>Carbón Activado y medio sintético HESPA</td></tr><tr><td>Eficiencia de Filtración</td><td>99.97% contra partículas de 0.3 micras</td></tr><tr><td>Válvula de Exhalación</td><td>Válvula de no retorno de nylon y silicona</td></tr><tr><td>Peso (Máscara + Filtros)</td><td>Aprox. 327 g</td></tr></tbody></table><h3>Aplicaciones Típicas</h3><ul><li>Pintura automotriz, naval e industrial</li><li>Aplicación de adhesivos y disolventes</li><li>Construcción y demolición</li><li>Manejo de productos químicos y agricultura</li><li>Soldadura y trabajos con metales</li></ul>",
//   "nombre": "Respirador GVS Elipse Integra OV/P100 GVS SPR659",
//   "url_producto": "https://www.gvs.com/en/catalog/elipse-integra-p100-nuisance-odor-respirator",
//   "bullets": {
//     "Marca": "GVS",
//     "Modelo": "Elipse Integra®",
//     "Protección": "OV/P100 (Vapores Orgánicos y Partículas)",
//     "Característica Clave": "Protección Ocular y Respiratoria Integrada",
//     "Certificación Ocular": "ANSI Z87+",
//     "Material": "TPE Hipoalergénico",
//     "Diseño": "Compacto y de Bajo Perfil"
//   }
// }';

	    echo json_encode($data);
		// $data = json_decode($json_limpio, true);

	    if(isset($data["descripcion"]) && !empty($data["descripcion"]) ){
	    	$product["Product"]["description"] = $data["descripcion"];
	    	$product["Product"]["garantia_id"] = 3;
			$this->Product->save($product);
			$product_id = $product["Product"]["id"];

			if(isset($data["bullets"]) && !empty($data["bullets"])){
				$bullets = $data["bullets"];
				$this->loadModel("Feature");
				$this->loadModel("FeaturesValue");
				$this->loadModel("ProductsFeaturesValues");
				if(!empty($bullets)){
					$this->ProductsFeaturesValues->deleteAll([
			                'ProductsFeaturesValues.product_id' => $product_id
			            ],false
			        );
					foreach ($bullets as $keyB => $valueB) {
						$idFeat = $this->Feature->field("id",["name"=>$keyB]);

						if($idFeat === false){
							$this->Feature->create();
							$this->Feature->save(["name" =>$keyB ]);
							$feature_id = $this->Feature->id;
						}else{
							$feature_id = $idFeat;
						}
							

						$this->FeaturesValue->create();
						$this->FeaturesValue->save(["name" => $valueB, "feature_id" => $feature_id ]);
						$features_value_id = $this->FeaturesValue->id;

						$this->ProductsFeaturesValues->create();
						$this->ProductsFeaturesValues->save(
							["product_id" => $product_id, "features_value_id" => $features_value_id ]
						);
					}

					
				}
			}
	    }else{
	    	$product["Product"]["garantia_id"] = 1;
			$this->Product->save($product);
	    }
		
	}


	public function callOpenOLDAIProductOld($nombre, $sku, $description, $marca, $maxTokens = 3500) {


		$description  = str_replace(["Gun","GUN","gun"], "Pistola de pulverización", $description);

	    $openaiApiKey = '';

	    $url = 'https://api.openai.com/v1/chat/completions';

	    // Aquí pegas el prompt de sistema modificado que te acabo de dar
	    $systemPrompt = 'Actuarás como un Especialista de Producto y Redactor Técnico para el e-commerce de KEBCO SAS. Tu tarea se divide en dos fases:

						1.  **INVESTIGACIÓN:** Primero, usarás el SKU (referencia), nombre y marca del producto proporcionado por el usuario para buscar en tu base de conocimientos interna la ficha técnica (datasheet), especificaciones detalladas y aplicaciones verificables de dicho producto. El SKU es el identificador más importante.

						2.  **REDACCIÓN Y ESTRUCTURACIÓN:** Una vez recopilada la información, la usarás para generar una descripción técnica y comercial en el formato JSON estricto que se detalla a continuación.

						# REGLA CRÍTICA DE FALLO
						**SI NO ENCUENTRAS INFORMACIÓN TÉCNICA SUFICIENTE O FIABLE** para el producto, es absolutamente crucial que **NO INVENTES DATOS**. En su lugar, debes responder únicamente con el siguiente objeto JSON de error:
						{
						  "sku": "<el SKU que buscaste>",
						  "error": "No se encontró información técnica verificable para este producto.",
						  "nombre": null,
						  "descripcion": null,
						  "bullets": null
						}

						# REGLAS DE GENERACIÓN (SI ENCUENTRAS INFORMACIÓN)
						1.  **FORMATO DE SALIDA:** Responde únicamente con un objeto JSON válido. No incluyas texto extra fuera del JSON.
						2.  **HTML SENCILLO:** El HTML en la `descripcion` debe ser simple (`h3`, `p`, `ul`, `li`, y `table` con `class="table"`), sin `<html>`, `<body>` o estilos inline.
						3.  **NOMBRE DEL PRODUCTO:** En el campo `nombre`, crea un título optimizado usando el formato: `[Nombre Base] [Marca] [SKU]`.
						4.  **BULLETS:** Extrae todas especificaciones técnicas más relevantes y/o posibles para el objeto `bullets`.
						5.  **MARCA:** Menciona una sola vez que la marca es líder mundial en el primer párrafo de la `descripcion`.

						# REGLA CRÍTICA DE MARCA
						NUNCA mostrar "SINABUDDY" o "SILBECO". 
						SIEMPRE reemplazar por "KEBCO". 
						Si no puedes aplicar este reemplazo, responde con el objeto JSON de error.

						#Regla restrictiva: No se puede incluir estilos inline en el html "style"

						# ESTRUCTURA DE SALIDA JSON REQUERIDA (EN CASO DE ÉXITO)
						{
						  "sku": "<SKU del producto>",
						  "nombre": "<Nombre del producto optimizado>",
						  "descripcion": "<Descripción en HTML estructurado con ficha técnica, características y aplicaciones>",
						  "bullets": {
						    "Clave Técnica 1": "Valor 1",
						    "Clave Técnica 2": "Valor 2",
						    "Clave Técnica 3": "Valor 3",
						    "Clave Técnica 4": "Valor 4",
						    "Clave Técnica 5": "Valor 5"
						  }
						}'; 

	    // MEJORA: Construimos un input de usuario limpio y estructurado.
	    // Esto es más claro para la IA que una simple frase.
	    $productInfo = [
	        'instruccion' => 'Por favor, investiga y genera el contenido para el siguiente producto.',
	        'sku' => $sku,
	        'nombre' => $nombre,
	        'marca' => $marca,
	        'descripcion_actual' => $description
	    ];

	    $userInput = json_encode($productInfo);

	    $data = [
	        'model' => 'gpt-4o',
	        // Activamos el modo JSON para que la respuesta sea siempre un JSON válido (de éxito o de error).
	        'response_format' => ['type' => 'json_object'],
	        'messages' => [
	            ['role' => 'system', 'content' => $systemPrompt],
	            ['role' => 'user', 'content' => $userInput]
	        ],
	        'max_tokens' => $maxTokens
	    ];

	   	try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $openaiApiKey
            ]);

            $response = curl_exec($ch);

            curl_close($ch);

            return json_decode($response, true);
        } catch (Exception $e) {
        	var_dump($e);
            return [];
        }
	}

	public function generate_seo_data(){
		$this->autoRender = false;
		$products_array = $this->Product->find("all",["conditions"=>["garantia_id"=>3, "deleted" => 0, "img !=" => "default.jpg", "short_description" => null, "meta_title" => null, "meta_description" => null ], "recursive" => -1, "limit" => 10]);

		// echo "<pre>";
		foreach ($products_array as $key => $value) {
			$promt = [
				"prompt" => [
					"id" => "pmpt_68b71d7c0a788196a3fdb8f100002faf0cf01dd2f5b7213f",
					"version" => "1",
					"variables" => [
						"product_name" => str_replace($value["Product"]["part_number"], "", $value['Product']['name']). ", ".$value["Product"]["part_number"],
						"reference" => $value["Product"]["part_number"],
						"current_description" => $value["Product"]["description"],
					]
				]
			];
			$responseAi = $this->callOpenAiResponse($promt);
			$resp = count($responseAi["output"]) == 2 ? $responseAi['output'][1]['content'][0]["text"] : $responseAi['output'][0]['content'][0]["text"];



			//  Eliminar caracteres extraños al inicio
			$json_limpio = trim(str_replace('json',"",$resp ) );
		    $json_limpio = trim(str_replace('```',"",$json_limpio ) );
		    $json_limpio = preg_replace('/^[^{\[]+/', '', $json_limpio); // Quita cualquier texto antes del JSON

		    //  Decodificar JSON a un array de PHP
		    $data = json_decode($json_limpio, true);

		    if(isset($data["short_description"]) && !empty($data["short_description"]) && isset($data["meta_title"]) && !empty($data["meta_title"]) && isset($data["meta_description"]) && !empty($data["meta_description"])){
		    	$value["Product"]["short_description"] = $data["short_description"];
		    	$value["Product"]["meta_title"] 		= $data["meta_title"];
		    	$value["Product"]["meta_description"] 	= $data["meta_description"];
		    	$this->Product->save($value);
		    }
		}


		
		// var_dump($products_array);
		// die();


	}


	public function callOpenAiResponse($data){
		$openaiApiKey = '';    

        $url = 'https://api.openai.com/v1/responses';
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $openaiApiKey
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            return json_decode($response, true);
        } catch (Exception $e) {
        	var_dump($e);
            return [];
        }
	}


    public function callOpenAIProduct($nombre,$sku,$description, $marca, $maxTokens = 3500) {

        $promptIntencion = "El nombre del producto es: $nombre, de la marca: $marca, el SKU o referencia que debes buscar es: $sku";


        if(!empty($description)){
    		$description  	 = str_replace(["Gun","GUN","gun"], "Pistola de pulverización", $description);
        	$promptIntencion .= " la descripción base que tengo es '$description' ";
        }


        $openaiApiKey = '';    

        $url = 'https://api.openai.com/v1/responses';

        $data = [
            'model' => 'gpt-4.1',
            'tools' => [['type'=>"web_search_preview","search_context_size"=>"medium"]],
            // "instructions" => , 
            'input' => [
                [
                	'role' => 'system', 'content' => '
                	Eres un experto en redacción técnica para e-commerce de KEBCO SAS. Tu tarea es generar una descripción en formato HTML altamente técnica y precisa de un producto basado en la información proporcionada. Para productos graco busca 

						📌 Requisitos para la descripción:

						Tono técnico y profesional, dirigido a ingenieros, técnicos y especialistas.

						Información detallada y objetiva, basada en datos reales, sin lenguaje subjetivo.

						Formato estructurado para e-commerce (Prestashop) , con listas técnicas y especificaciones claras.

						Sin invención de datos: Si alguna información no está disponible, deja el campo vacío ("").

						Nunca digas que debe consultar en la página del proveedor o que consulte en un enlace

						No incluyas Documentación Adicional en la descripción y si no existen accesorios opcionales no pongas el titulo

						Los bullets son especificaciones técnicas ( no se incluyen Accesorios opcionales ) en clave valor ejemplo:

						Temperatura del aire : 0.1° F
						Punto de rocío: -60° a 60° C
						Humedad: 0.1° C

						# REGLAS DE GENERACIÓN (SI ENCUENTRAS INFORMACIÓN)
						1.  **FORMATO DE SALIDA:** Responde únicamente con un objeto JSON válido. No incluyas texto extra fuera del JSON.
						2.  **HTML SENCILLO:** El HTML en la `descripcion` debe ser simple (`h3`, `p`, `ul`, `li`, y `table` con `class="table"`), sin `<html>`, `<body>` o estilos inline.
						3.  **NOMBRE DEL PRODUCTO:** En el campo `nombre`, crea un título optimizado usando el formato: `[Nombre Base] [Marca] [SKU]`.

						# Regla restrictiva: No se puede incluir estilos inline en el html "style"

						🛠 Estructura de salida (JSON):
						{
						  "sku": "<SKU del producto>",
						  "descripcion": "<Descripción del producto en formato HTML>",
						  "nombre": "<Nombre del producto>",
						  "url_producto" : "<URL DEL PRODUCTO en la página del fabricante>",
						  "bullets": {
						     "clave 1": "Especificación técnica 1",
						     "clave 2": "Especificación técnica 2",
						     "clave 3": "Especificación técnica 3",
						     "clave 4": "Especificación técnica 4",
						     "clave 5": "Especificación técnica 5",
						  }
						}						

						Condiciones de la descripción:

							1. No puedes inventar información debe ser información totalmente real del producto

							2. Se requiere que en la descripción se encuentre la ficha técnica, accesorios posibles

							3. Descripción en español con un lenguaje técnico

							4. Devuelve el html, debe ser sencillo sin usar style inline etiqueta HTML, BODY,HEAD, no usar html5 la tabla html debe tener la clase "table"

							5. En caso de que no encuentres información devuelve el json con el campo "descripcion" vacio ("")

							6. Las medidas se deben expresar en centimetros (cm)

						# REGLA CRÍTICA DE MARCA
						NUNCA mostrar "SINABUDDY" o "SILBECO". 
						SIEMPRE reemplazar por "KEBCO". 

						📢 Reglas Adicionales:

						No inventes URLs para imágenes, videos o documentos. Si no hay fuentes verificadas, deja el campo vacío ("").

						Evita repeticiones y frases genéricas, solo datos precisos y estructurados.

						Resalta cada marca como la marca líder mundial, pero solo en una oración breve.

						No agregues texto adicional fuera del JSON.

				' ],
                ['role' => 'user', 'content' => $promptIntencion]
            ],
            // 'max_tokens' => $maxTokens
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $openaiApiKey
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            return json_decode($response, true);
        } catch (Exception $e) {
        	var_dump($e);
            return [];
        }
    }

    public function create_ia(){
    	$costos = [];

    	if($this->request->is("post")){
            $file_mimes = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if(isset($this->request->data["Product"]["file"]["name"]) && in_array($this->request->data["Product"]["file"]["type"], $file_mimes)){
            	$this->autoRender = false;
                try {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadsheetOrigi = $reader->load($this->request->data["Product"]["file"]['tmp_name']);
                    $xls_data = $spreadsheetOrigi->getActiveSheet()->toArray(null, true, true, true);

                     unset($xls_data[1]);
                } catch (Exception $e) {
                    $xls_data = array();
                }

              

                if(!empty($xls_data)){

                	$this->loadModel("Feature");
					$this->loadModel("ProductsFeaturesValues");
	
					$this->loadModel("FeaturesValue");

                	foreach ($xls_data as $key => $value) {
                		//408
                		// $product = $this->Product->find("first", ["conditions" => ["part_number LIKE" => $value["A"]."-%" ], "order" => ["LENGTH(part_number)" => "ASC"],"recursive"=>-1] );

                		if(empty($product)){
                		}
                		$product = $this->Product->find("first", ["conditions" => ["part_number LIKE" => "%".$value["A"]."%" ], "order" => ["LENGTH(part_number)" => "ASC"],"recursive"=>-1] );

                		if(!empty($product)){
                			$product["Product"]["description"] = $value["E"];
                			$this->Product->save($product);
                			$product_id = $product["Product"]["id"];
                		}else{
                			// $this->Product->create();
                			// $this->Product->save([
                			// 	"name" => $value["B"],
                			// 	"part_number" => $value["A"],
                			// 	"description" => $value["E"],
                			// 	"purchase_price_usd" => $value["G"],
                			// 	"category_id" => 408,
                			// 	"brand_id" => 9,
                			// 	"brand" => "Defelsko"
                			// ]);
                			// $product_id = $this->Product->id;
                		}

                		if(!empty($value["H"])){
                			$bullets = json_decode($value["H"],true);

                			if(!empty($bullets)){

                				foreach ($bullets as $keyB => $valueB) {
                					$idFeat = $this->Feature->field("id",["name"=>$keyB]);

									if($idFeat === false){
										$this->Feature->create();
										$this->Feature->save(["name" =>$keyB ]);
										$feature_id = $this->Feature->id;
									}else{
										$feature_id = $idFeat;
									}
										

									$this->FeaturesValue->create();
									$this->FeaturesValue->save(["name" => $valueB, "feature_id" => $feature_id ]);
									$features_value_id = $this->FeaturesValue->id;

									$this->ProductsFeaturesValues->create();
									$this->ProductsFeaturesValues->save(
										["product_id" => $product_id, "features_value_id" => $features_value_id ]
									);
                				}

                				
                			}

                		}
                	}

			        
                	$this->Session->setFlash('Costos actualizados correctamente.','Flash/success');
                }
            }else{
            	$this->Session->setFlash('El archivo no tiene un formato válido.','Flash/error');
            }
       	}

    	$this->set("costos",$costos);

    }

    public function create_ia_generate(){
    	$costos = [];

    	if($this->request->is("post")){
            $file_mimes = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if(isset($this->request->data["Product"]["file"]["name"]) && in_array($this->request->data["Product"]["file"]["type"], $file_mimes)){
                try {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadsheetOrigi = $reader->load($this->request->data["Product"]["file"]['tmp_name']);
                    $xls_data = $spreadsheetOrigi->getActiveSheet()->toArray(null, true, true, true);

                     unset($xls_data[1]);
                } catch (Exception $e) {
                    $xls_data = array();
                }

                // $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $spreadsheet = $spreadsheetOrigi;

                // $spreadsheet->getProperties()->setCreator('Kebco SAS')
                //     ->setLastModifiedBy('Kebco SAS')
                //     ->setTitle('Comisiones por vendedor')
                //     ->setSubject('Comisiones por vendedor')
                //     ->setDescription('Comisiones por vendedor')
                //     ->setKeywords('Comisiones CRM Productos')
                //     ->setCategory('Comisiones');

		        // $spreadsheet->setActiveSheetIndex(0)
		        //             ->setCellValue('A1', 'NUMERO DE PARTE')
		        //             ->setCellValue('B1', 'NOMBRE DEL PRODUCTO')
		        //             ->setCellValue('C1', 'DESCRIPCION ACTUAL')
		        //             ->setCellValue('D1', 'NUEVA DESCRIPCIÓN')
		        //             ->setCellValue('E1', 'NUEVA DESCRIPCIÓN LARGA')
		        //             ->setCellValue('F1', 'COSTO ACTUAL')
		        //             ->setCellValue('G1', 'COSTO NUEVO')
		        //             ->setCellValue('H1', 'BULLETS');

                if(!empty($xls_data)){
                	$i = 2;

                	foreach ($xls_data as $key => $value) {

                		$responseAi = $this->callOpenAIProduct($value["B"],$value["A"], $value["C"], $value["G"] );
                		$resp = $responseAi['choices'][0]['message']['content'];

                		//  Eliminar caracteres extraños al inicio
                		$json_limpio = trim(str_replace('json',"",$resp ) );
					    $json_limpio = trim(str_replace('```',"",$json_limpio ) );
					    $json_limpio = preg_replace('/^[^{\[]+/', '', $json_limpio); // Quita cualquier texto antes del JSON

					    //  Decodificar JSON a un array de PHP
					    $data = json_decode($json_limpio, true);


					    $descripcion = $data["descripcion"];
					    $descripcion.= "<p>especificaciones técnicas </p>";
					    $descripcion.= "<p>";
					    $descripcion.= $this->arrayToHtmlList($data["bullets"]);
					    $descripcion.= "</p>";

						$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $value["A"]);
			            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["B"]);
			            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $value["C"] );
			            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $value["D"]);
                		$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $descripcion);
			            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $value["F"] );
			            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $value["G"] );

                		$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i,json_encode( $data["bullets"]));
                		$i++;

                		// var_dump($value);
                		// echo($descripcion);
                		// var_dump($data);
                		// die();

                	}


                	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			        // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);


			        $spreadsheet->getActiveSheet()->setTitle("Nuevos datos");
			        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

			        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			        $name = "files/airless_productos_" . time() . ".xlsx";
			        $writer->save($name);

			        $url = Router::url("/", true) . $name;
			        $this->redirect($url);

			        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			        header('Content-Disposition: attachment;filename="comisiones_kebco_'.time().'.xlsx"');
			        header('Cache-Control: max-age=0');
			        // If you're serving to IE 9, then the following may be needed
			        header('Cache-Control: max-age=1');

			        // If you're serving to IE over SSL, then the following may be needed
			        header('Expires: Mon, 26 Jul 2025 05:00:00 GMT'); // Date in the past
			        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			        header('Pragma: public'); // HTTP/1.0

			        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
			        $writer->save('php://output');
			        exit;

                	var_dump($products);
                	var_dump($partsData);
                	var_dump($xls_data);
                	die();

                	// foreach ($xls_data as $key => $value) {
                	// 	if(trim($value["A"]) != ""){
                	// 		$this->Product->recursive = -1;
                	// 		$product = $this->Product->field("id", ["LOWER(part_number)" => strtolower($value["A"])]);
                	// 		if(!empty($product) && !is_null($product)){
                	// 			$pre_purchase_price_usd = $this->Product->field("purchase_price_usd", ["LOWER(part_number)" => strtolower($value["A"])]);
                	// 			$value["C"] = str_replace(",", "", $value["C"]);
                	// 			$fields = ["Product" => ["id" => $product ] ];
                	// 			$usd = floatval($value["C"]);

                	// 			echo "UPDATE products SET purchase_price_usd=${usd}, last_change = '".date("Y-m-d H:i:s")."' WHERE id=${product};<br>";
                	// 			echo "INSERT INTO costs (id,user_id,purchase_price_usd,product_id,pre_purchase_price_usd,created,modified) VALUES (null,".AuthComponent::user("id").",${usd},${product},$pre_purchase_price_usd,NOW(),NOW());<br>";
                	// 		}
                	// 	}
                	// }
                	die;
                	$this->Session->setFlash('Costos actualizados correctamente.','Flash/success');
                }
            }else{
            	$this->Session->setFlash('El archivo no tiene un formato válido.','Flash/error');
            }
       	}

    	$this->set("costos",$costos);

    }

    public function update_prices(){


    	$costos = [];

    	if($this->request->is("post")){
            $file_mimes = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if(isset($this->request->data["Product"]["file"]["name"]) && in_array($this->request->data["Product"]["file"]["type"], $file_mimes)){
                try {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadsheet = $reader->load($this->request->data["Product"]["file"]['tmp_name']);
                    $xls_data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                     unset($xls_data[1]);
                } catch (Exception $e) {
                    $xls_data = array();
                }
                if(!empty($xls_data)){
                	foreach ($xls_data as $key => $value) {
                		if(trim($value["A"]) != ""){
                			$this->Product->recursive = -1;
                			$product = $this->Product->field("id", ["LOWER(part_number)" => strtolower($value["A"])]);
                			if(!empty($product) && !is_null($product)){
                				$pre_purchase_price_usd = $this->Product->field("purchase_price_usd", ["LOWER(part_number)" => strtolower($value["A"])]);
                				$value["C"] = str_replace(",", "", $value["C"]);
                				$fields = ["Product" => ["id" => $product ] ];
                				$usd = floatval($value["C"]);

                				echo "UPDATE products SET purchase_price_usd=${usd}, last_change = '".date("Y-m-d H:i:s")."' WHERE id=${product};<br>";
                				echo "INSERT INTO costs (id,user_id,purchase_price_usd,product_id,pre_purchase_price_usd,created,modified) VALUES (null,".AuthComponent::user("id").",${usd},${product},$pre_purchase_price_usd,NOW(),NOW());<br>";
                			}
                		}
                	}
                	die;
                	$this->Session->setFlash('Costos actualizados correctamente.','Flash/success');
                }
            }else{
            	$this->Session->setFlash('El archivo no tiene un formato válido.','Flash/error');
            }
       	}

    	$this->set("costos",$costos);

    }

    public function unsubscribe(){
    	$this->layout = false;

    	if(isset($this->request->query["qt"])){
    		$this->set("email_user", $this->desencriptarCadena($this->request->query["qt"]));
    	}

    	if(isset($this->request->query["scs"])){
    		$this->set("dataList", true);
    	}

    	if($this->request->is("post")){
    		$this->loadModel("NewsletterReject");

    		$data = array(
    			"NewsletterReject" => array(
    				"email" => $this->request->data["Product"]["email"]
    			)
    		);
    		$this->NewsletterReject->create();
    		$this->NewsletterReject->save($data);
    		$this->redirect(array("controller" => "products", "action" => "unsubscribe" ,"?" => ["scs" => time()]));
    	}

    }

    public function changeCostData(){
    	$this->autoRender = false;
    	$this->Product->recursive = -1;
    	$product = $this->Product->findById($this->request->data["id"]);

    	if(empty($this->request->data)){
    		throw new NotFoundException(__('La marca no existe'));
    		return '$'.$product['Product'][$this->request->data["type"]].' '.$this->request->data["currency"].'</div>';
    	}else{

    		if ($this->request->data["type"] == "purchase_price_usd") {
    			$this->loadModel("Cost");
    			$this->Cost->create();
    			$this->Cost->save(["Cost" => ["user_id"=>AuthComponent::user("id"),"purchase_price_usd" => $this->request->data["value"], "product_id" => $this->request->data["id"], "pre_purchase_price_usd" => $product["Product"]["purchase_price_usd"]  ] ]);
    		}
    		$product["Product"]["last_change"] = date("Y-m-d H:i:s");

    		$product["Product"][$this->request->data["type"]] = $this->request->data["value"];
	    	$this->Product->save($product);
	    	$this->Session->setFlash(__('Cambio de costo correcto'),'Flash/success');
	    	return '$'.$product['Product'][$this->request->data["type"]].' '.$this->request->data["currency"].'</div>';
    	}
    }

    public function importer($id){
    	$id = $this->desencriptarCadena($id);
    	$this->autoRender = false;
    	$this->Product->recursive = -1;
    	$product = $this->Product->findById($id);
    	$product["Product"]["type"] = $product["Product"]["type"] == 1 ? 0 : 1;
    	$this->Product->save($product);
    	$this->Session->setFlash(__('Cambio de estado correcto'),'Flash/success');
    }

    public function getEmpresaProducto(){
    	$this->layout = false;
    	// $this->autoRender = false;
    	$this->loadModel("ImportProduct");
    	$this->loadModel("ImportProductsDetail");
    	$datos = $this->ImportProduct->findById($this->request->data["id"]);
    	$details = $this->ImportProductsDetail->find("all",["conditions" => ["ImportProductsDetail.import_id" => $datos["Import"]["id"], "ImportProductsDetail.product_id" => $datos["Product"]["id"], "ImportProductsDetail.quantity > ImportProductsDetail.quantity_final" ] ]);
    	
    	$this->set(compact("datos","details"));
    	$this->set("productImport",$this->request->data["id"]);

    }

    public function block_products(){
    	$this->autoRender = false;
    	$this->loadModel("Config");
		$this->loadModel("User");

		$config = $this->Config->field("roles_unlock",["id" => 1]);

		$this->User->recursive = -1;
		$users  = $this->User->findAllByRoleAndState(explode(",", $config),1);

		$this->Product->recursive = -1;
		$products = $this->Product->findAllByStateAndDeleted(0,0);

		if(!empty($users) && !empty($products)){
			$emails 	= Set::extract($users, "{n}.User.email");

			foreach ($emails as $key => $value) {
                if ($value == "jotsuar@gmail.com") {
                    unset($emails[$key]);
                }
            }

			$options = array(
				'to'		=> $emails,
				'template'	=> 'products_block',
				'subject'	=> "Productos bloqueados CRM ".date("Y-m-d"),
				'vars'		=> compact("products")
			);
			
			$this->sendMail($options);
		}

		die();

    }

    public function report_quotations_products(){

    	$ini        = date("Y-m-d",strtotime('-3 month'));
        $end        = date("Y-m-d");

        $categoriesInfoFinal = $this->getCagegoryData();

        $categoriasData = $this->Product->Category->find("list");
        $grupoMax 		= $this->Product->Category->field("MAX(grupo)");

		$categoriesData = $this->getCategoryInfo(true);
		unset($categoriesData[0]);

        $brands 			 = $this->Product->Brand->find("all");

    	$this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
        $this->set("categoriesData", $categoriesData);
        $this->set("categoriesInfoFinal", $categoriesInfoFinal);
        $this->set("grupoMax", $grupoMax);
        $this->set("brands", $brands);
    }

	public function remarketing(){
		$conditions 			= array();
		$productos 				= $this->Product->find("all",["fields"=>["brand_id","category_id"],"conditions"=>$conditions,"recursive" => -1]);
		$idsBrands				= Set::extract($productos,"{n}.Product.brand_id");
		$idsCategory			= Set::extract($productos,"{n}.Product.category_id");
		$brands 				= $this->Product->Brand->find("all");

		$fields = array("Product.purchase_price_usd","SUM(Product.quantity+Product.quantity_bog+Product.quantity_stm+Product.quantity_stb) total", "Product.id");
		$recursive = -1;
		$group = array("id");
		$costoUsd = $this->Product->find("all",compact("conditions","fields","recursive","group"));

		$costoTotalUsd = 0;

		if(!empty($costoUsd)){
			foreach ($costoUsd as $key => $value) {
				$costoTotalUsd+= ($value["0"]["total"]*$value["Product"]["purchase_price_usd"]);
			}
		}

		$this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];

        $categoriasData = $this->Product->Category->find("list");
        $grupoMax 		= $this->Product->Category->field("MAX(grupo)");

		$categoriesData = $this->getCategoryInfo(true);
		unset($categoriesData[0]);

		$categoriesInfoFinal = $this->getCagegoryData();

		$brands 			 = $this->Product->Brand->find("all");

		$this->loadModel("MailingList");

		$lists 		= array();
		$listEmails = array();
		$data 		= $this->MailingList->findAllByState(1);

		if(!empty($data)){
			$dataLists 	= $data;
			foreach ($dataLists as $key => $value) {
				if($value["MailingList"]["type"] == 1){
					$lists[$value["MailingList"]["id"]] = $value["MailingList"];
				}else{
					$listEmails[$value["MailingList"]["id"]] = $value["MailingList"];
				}
			}
		}

		$this->set(compact('products','brands','costoMin','costoMax','precioMax','precioMin','idsCategory','costoTotalUsd','trmActual','categoriasData','grupoMax',"grupoData","dataCategory","categoriesData","categoriesInfoFinal","brands","lists","listEmails"));
	}

	public function changeProduct() {
		$this->autoRender = false;
		$this->loadModel("Config");
		$this->loadModel("User");

		$config = $this->Config->field("roles_edit",["id" => 1]);

		$this->User->recursive = -1;
		$users  = $this->User->findAllByRole(explode(",", $config));

		if(!empty($users)){
			$this->Product->recursive = -1;
			$emails 	= Set::extract($users, "{n}.User.email");
			$usuario 	= AuthComponent::user("name");
			$razon		= $this->request->data["razon"];
			$producto   = $this->Product->findById($this->request->data["id"]);
			$product 	= $producto["Product"];

			$options = array(
				'to'		=> $emails,
				'template'	=> 'edit_product',
				'subject'	=> "Solicitud de edición de producto",
				'vars'		=> compact("usuario","razon","product")
			);
			
			$this->sendMail($options);

		}
	}

	public function block($productId){
		$productId 					= $this->desencriptarCadena($productId);
		$this->autoRender 			= false;
		$this->Product->recursive 	= -1;
		$producto = $this->Product->findById($productId);
		$producto["Product"]["state"] = $producto["Product"]["state"] == 1 ? 0 : 1;
		$this->Product->save($producto);
		$this->Session->setFlash(__('Producto cambiado correctamente.'),'Flash/success');
	}

	public function delete_product($productId){
		$productId 					= $this->desencriptarCadena($productId);
		$this->autoRender 			= false;
		$this->Product->recursive 	= -1;
		$producto = $this->Product->findById($productId);
		$producto["Product"]["deleted"] = 1;
		$this->Product->save($producto);
		$this->Session->setFlash(__('Producto eliminado correctamente, si cometiste un error debes consultarlo con gerencia.'),'Flash/success');
	}

	public function deleteMasive(){
		$this->autoRender = false;

		if($this->request->is("ajax") && !empty($this->request->data["products"])){
			foreach ($this->request->data["products"] as $key => $value) {
				$producto = $this->Product->findById($value);
				$producto["Product"]["deleted"] = 1;
				$this->Product->save($producto);
			}
		}
		$this->Session->setFlash(__('Producto eliminados correctamente, si cometiste un error debes consultarlo con gerencia.'),'Flash/success');
	}

	public function unlockMasive(){
		$this->autoRender = false;

		if($this->request->is("ajax") && !empty($this->request->data["products"])){
			foreach ($this->request->data["products"] as $key => $value) {
				$producto = $this->Product->findById($value);
				$producto["Product"]["state"] = 1;
				$this->Product->save($producto);
			}
		}
		$this->Session->setFlash(__('Producto desbloqueados correctamente.'),'Flash/success');
	}

	public function export_template($type, $time){
		$this->autoRender = false;

		if($type == "update"){
			$this->export_update(time());
			return;
		}

		$this->loadModel("Category");
		$categoriesData = $this->getCategoryInfo(false);
		unset($categoriesData[0]);

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$spreadsheet->getProperties()->setCreator('Kebco SAS')
			        ->setLastModifiedBy('Kebco SAS')
			        ->setTitle('Creación CRM')
			        ->setSubject('Creación CRM')
			        ->setDescription('Creación de productos para el CRM')
			        ->setKeywords('Creación CRM Productos')
			        ->setCategory('Creación');

		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'REFERENCIA')
			        ->setCellValue('B1', 'PRODUCTO')
			        ->setCellValue('C1', 'CANTIDAD MEDELLIN')
			        ->setCellValue('D1', 'CANTIDAD BOGOTA')
			        ->setCellValue('E1', 'CANTIDAD ST MEDELLIN')
			        ->setCellValue('F1', 'CANTIDAD ST BOGOTA')
			        ->setCellValue('G1', 'COSTO UNITARIO')
			        ->setCellValue('H1', 'COSTO USD')
			        ->setCellValue('I1', 'COSTO WO')
			        ->setCellValue('J1', 'PRECIO DE VENTA')
			        ->setCellValue('K1', 'MARCA')
			        ->setCellValue('L1', 'DESCRIPCIÓN')
			        ->setCellValue('M1', 'LINK')
			        ->setCellValue('N1', 'ESTADO')
			        ->setCellValue('O1', 'CATEGORÍA');


		
		$listaI = 0;

		foreach ($categoriesData as $key => $value) {
			$listaI++;
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('ZA'.$listaI, $value);
		}

		$i = 2;
		$estado = "Activo/Bloqueado";
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, "XXX000");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, "NOMBRE");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, 0);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, 0);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, 0);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, 0);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, 0);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, 0);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, 0);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, 0);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, "N/A");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, "N/A");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$i, "N/A");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$i, $estado);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "General");
		$objValidation = $spreadsheet->getActiveSheet()->getCell('O'.$i)->getDataValidation();
        $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
        $objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setShowDropDown(true);
        $objValidation->setErrorTitle('Error de seleccion');
        $objValidation->setError('Categoría no válida');
        $objValidation->setPromptTitle('Seleccione una categoría existente');
        $objValidation->setPrompt('');
        $objValidation->setFormula1('=$ZA$1:$ZA$'.$listaI);
		$i++;

		

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(150);

		$spreadsheet->getActiveSheet()->setTitle('Productos');
		$spreadsheet->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="plantilla_crear_productos_kebco_'.time().'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 2025 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	private function export_update($time){

		$this->loadModel("Category");
		$categoriesData = $this->getCategoryInfo(false);
		unset($categoriesData[0]);

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$spreadsheet->getProperties()->setCreator('Kebco SAS')
			        ->setLastModifiedBy('Kebco SAS')
			        ->setTitle('Actualización/Creación CRM')
			        ->setSubject('Actualización/Creación CRM')
			        ->setDescription('Actualización/Creación de productos para el CRM')
			        ->setKeywords('Actualización/Creación CRM Productos')
			        ->setCategory('Actualización/Creación');

		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'REFERENCIA')
			        ->setCellValue('B1', 'PRODUCTO')
			        ->setCellValue('C1', 'COSTO UNITARIO')
			        ->setCellValue('D1', 'COSTO USD')
			        ->setCellValue('E1', 'COSTO WO')
			        ->setCellValue('F1', 'PRECIO DE VENTA')
			        ->setCellValue('G1', 'MARCA')
			        ->setCellValue('H1', 'DESCRIPCIÓN')
			        ->setCellValue('I1', 'LINK')
			        ->setCellValue('J1', 'ESTADO')
			        ->setCellValue('K1', 'CATEGORÍA');


		
		$listaI = 0;

		foreach ($categoriesData as $key => $value) {
			$listaI++;
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('ZA'.$listaI, $value);
		}
		
		$i = 2;
		$order 		= array("quantity" => "DESC" );
		$recursive 	= -1;
		$conditions = array("deleted" => 0, "id" => [490, 4697, 5483, 3150, 5925, 2761, 2762, 4132, 1405, 2443, 3438, 1223, 3530, 1428, 3848, 3617, 2728, 2720, 2725, 1230, 5857, 5855, 5856, 5853, 5848, 5850, 5849, 128, 6105, 6106, 6109, 6108, 6107] );
		$fields		= array("part_number","name","quantity","quantity_bog","quantity_stm","quantity_stb","brand","description","purchase_price_cop","purchase_price_usd","purchase_price_wo","list_price_usd","link","state","category_id");
		$products 	= $this->Product->find("all",compact("order","recursive","fields","conditions"));
		
		foreach ($products as $key => $value) {
			$nombreCategoria = $categoriesData[$value["Product"]["category_id"]];
			$estado = $value["Product"]["state"] == 1 ? "Activo" : "Bloqueado";
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $value["Product"]["part_number"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["Product"]["name"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $value["Product"]["purchase_price_cop"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $value["Product"]["purchase_price_usd"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $value["Product"]["purchase_price_wo"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $value["Product"]["list_price_usd"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $value["Product"]["brand"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $value["Product"]["description"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $value["Product"]["link"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, $estado);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $nombreCategoria);

			$objValidation = $spreadsheet->getActiveSheet()->getCell('K'.$i)->getDataValidation();
            $objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
            $objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setErrorTitle('Error de seleccion');
            $objValidation->setError('Categoría no válida');
            $objValidation->setPromptTitle('Seleccione una categoría existente');
            $objValidation->setPrompt('');
            $objValidation->setFormula1('=$ZA$1:$ZA$'.$listaI);
			$i++;
		}


		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(150);

		$spreadsheet->getActiveSheet()->setTitle('Productos');
		$spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->setAutoFilter('A1:K'.$i);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="plantilla_actualizar_productos_kebco_'.time().'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 2025 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	public function admin_masive(){
		$this->layout = false;
	}

	private function validateRow($row){
		$result = true;
		foreach ($row as $key => $value) {
			if((empty($value) || is_null($value)) && $value != 0){
				$result = false;
				break;
			}
		}
		return $result;
	}

	public function dataProduct(){
		$this->autoRender = false;
		$this->Product->recursive = -1;
		return json_encode($this->Product->findById($this->request->data["id"]));
	}

	public function saveComentary(){
		$this->autoRender = false;
		$this->Product->recursive = -1;
		$product = $this->Product->findById($this->request->data["Product"]["id"]);
		$product["Product"]["notes"] = $this->request->data["Product"]["notes"];
		$this->Product->save($product);
		$this->Session->setFlash('El comentario se ha guardado satisfactoriamente','Flash/success');
	}


	public function import_categories(){
		if ($this->request->is("post")) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$spreadsheet = $reader->load($this->request->data["Producto"]["file"]['tmp_name']);
		    $xls_data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		    unset($xls_data[1]);
		    $totalContuinue = 0;
		    $referencias = [];
		    foreach ($xls_data as $key => $value) {

		    	if($totalContuinue >= 10){
		    		break;
		    	}

		    	if(empty($value["A"])){
		    		$totalContuinue++;
		    		continue;
		    	}

		    	
		    	$this->Product->recursive = -1;
		    	$product = $this->Product->findByPartNumber($value["A"]);

		    	$category_id = 0;

		    	if (!empty($value["D"])) {
		    		$category_id = $this->Product->Category->findOrCreate($value["D"],$category_id);	    		
		    	}
		    	if (!empty($value["E"])) {
		    		$category_id = $this->Product->Category->findOrCreate($value["E"],$category_id);	    		
		    	}
		    	if (!empty($value["F"])) {
		    		$category_id = $this->Product->Category->findOrCreate($value["F"],$category_id);	    		
		    	}
		    	if (!empty($value["G"])) {
		    		$category_id = $this->Product->Category->findOrCreate($value["G"],$category_id);	    		
		    	}

	    		$brand 		= $this->Product->Brand->find("first",array("conditions" => array("LOWER(name)" => strtolower($value["H"]) ),"recursive" => -1 ));

	    		$brandId 		= 	empty($brand) ? "1" 	: $brand["Brand"]["id"];
				$brandName 		= 	empty($brand) ? "N/A" 	: $brand["Brand"]["name"];

		    	if(empty($product)){
		    		$product = array("Product" => [
		    			"description" => "N/A",
		    			"link" => "#",
		    			"part_number" => $value["A"],
		    			"id" => null
		    		]);

		    		$referencias[] = $value["A"];
		    		$this->Product->create();
		    	}

		    	$product["Product"]["category_id"] 	=  	$category_id == 0 ? 1 : $category_id;
	    		$product["Product"]["brand_id"] 	=	$brandId;
	    		$product["Product"]["brand"]	 	=	$brandName;
	    		$product["Product"]["name"]		 	=	$value["B"];
	    		$product["Product"]["type"]		 	=	strtolower($value["C"]) == "producto" ? 1 : 2; 

	    		$product["Product"]["purchase_price_usd"]		 	=	empty($value["I"]) ? 0 : floatval($value["I"]);
	    		$product["Product"]["purchase_price_wo"]		 	=	empty($value["J"]) ? 0 : floatval($value["J"]);
	    		$this->Product->save($product);

		    }

		    var_dump($referencias);
		    die();

		}
	}


	public function validador_inventario(){
		if ($this->request->is("post")) {
					$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

			if(isset($this->request->data["Product"]["file"]["name"]) && in_array($this->request->data["Product"]["file"]["type"], $file_mimes)){

		    	$reader 		= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();			 
			    $spreadsheet 	= $reader->load($this->request->data["Product"]["file"]['tmp_name']);
			    $xls_data 		= $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

			    $noExisten 		= [];
			    $diferencias    = [];
			    $costo  		= $this->request->data["Product"]["costos"] == "0" ? false : true;
			    

			    if( $xls_data[4]["C"] == "Descripcion" && $xls_data[4]["D"] == "UM" && $xls_data[4]["E"] == "Existencias" && $xls_data[4]["F"] == "Promedio" && $xls_data[4]["G"] == "Costo"){
			    	unset($xls_data[1],$xls_data[2],$xls_data[3],$xls_data[4]);

			    	if (!empty($xls_data)) {
			    		foreach ($xls_data as $key => $value) {
			    			if (empty($value["C"]) || empty($value["G"])) {
			    				continue;
			    			}else{
			    				$this->Product->recursive = -1;
			    				$infoProduct =  explode(" ",$value["C"]);
			    				$partNumber  = $infoProduct[0];
			    				$product 	 = $this->Product->findByPartNumber($partNumber);

			    				if(empty($product)){
			    					$noExisten[] = $partNumber;
			    				}else{
			    					$productData = $this->getTotalInventoryProduct($product["Product"]["id"]);
			    					$value["G"]  = str_replace([","], ["."], $value["G"]);
			    					$value["E"]  = doubleval($value["E"]);	
			    					if($costo){
			    						$product["Product"]["purchase_price_usd"] = doubleval($value["G"]);
			    						$this->Product->save($product);
			    					}

			    					if ($value["E"]  !=  ($productData["quantity"] + $productData["quantity_bog"] ) ) {
			    						$diferencias[] = [
			    							"part" => $partNumber,
			    							"product" => $product["Product"]["name"]. " - ".$product["Product"]["brand"],
			    							"inventory" => ($productData["quantity"] + $productData["quantity_bog"]),
			    							"inventory_wo" => $value["E"],
			    							"diference" => abs( ($value["E"] - ($productData["quantity"] + $productData["quantity_bog"]) ) )
			    						];
			    					}
			    				}
			    			}
			    		}
			    	}
			    	if (!empty($noExisten)) {
			    		$this->set("noExisten",$noExisten);
			    	}
			    	if (!empty($diferencias)) {
			    		$this->set("diferencias",$diferencias);
			    	}
			    	$this->loadModel("User");
			    	$users = $this->User->role_gerencia_user();

					foreach ($users as $key => $value) {
						$emails[] = $value["User"]["email"];
					}
					$emails[] = "logistica@kebco.co";
			    	$options = array(
						'to'		=> $emails,
						'template'	=> 'inventory_valdiation',
						'subject'	=> "validación de inventario - ".date("Y-m-d H:i:s"),
						'vars'		=> compact("diferencias", "noExisten")
					);
					
					$this->sendMail($options);
			    	$this->Session->setFlash('Validación realizada satisfactoriamente','Flash/success');
				}else{
					$this->Session->setFlash('El archivo no cumple con el formato requerido','Flash/error');
				}
			}
		}
	}

	public function importProducts(){
		$this->autoRender = false;
		$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		if(isset($this->request->data["Product"]["file"]["name"]) && in_array($this->request->data["Product"]["file"]["type"], $file_mimes)){

		    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		 
		    $spreadsheet = $reader->load($this->request->data["Product"]["file"]['tmp_name']);
		     
		    $xls_data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

		    if( ( 
		    	$xls_data[1]["A"] == "REFERENCIA" && $xls_data[1]["B"] == "PRODUCTO" && $xls_data[1]["C"] == "CANTIDAD MEDELLIN" && $xls_data[1]["D"] == "CANTIDAD BOGOTA" && $xls_data[1]["E"] == "CANTIDAD ST MEDELLIN" && $xls_data[1]["F"] == "CANTIDAD ST BOGOTA" && $xls_data[1]["G"] == "COSTO UNITARIO" && $xls_data[1]["H"] == "COSTO USD" && $xls_data[1]["I"] == "COSTO WO" && $xls_data[1]["J"] == "PRECIO DE VENTA" && $xls_data[1]["K"] == "MARCA" && $xls_data[1]["L"] == "DESCRIPCIÓN" && $xls_data[1]["M"] == "LINK" && $xls_data[1]["N"] == "ESTADO" && $xls_data[1]["O"] == "CATEGORÍA" &&  $this->request->data["Product"]["type"] == "create"
		    ) 
		    || 
		    (
		    	$xls_data[1]["A"] == "REFERENCIA" && $xls_data[1]["B"] == "PRODUCTO" && $xls_data[1]["C"] == "COSTO UNITARIO" && $xls_data[1]["D"] == "COSTO USD" && $xls_data[1]["E"] == "COSTO WO" && $xls_data[1]["F"] == "PRECIO DE VENTA" && $xls_data[1]["G"] == "MARCA" && $xls_data[1]["H"] == "DESCRIPCIÓN" && $xls_data[1]["I"] == "LINK" && $xls_data[1]["J"] == "ESTADO" && $xls_data[1]["K"] == "CATEGORÍA" &&  $this->request->data["Product"]["type"] == "update"
		    ) 
			){
				unset($xls_data[1]);
				if(empty($xls_data)){
					return 2;
				}else{
					$rowsError  = array();
					$rowsCreate = array();
					$rowsUpdate = array();

					$categoriesData = $this->getCategoryInfo(false);
					unset($categoriesData[0]);

					foreach ($xls_data as $key => $value) {
						if(!$this->validateRow($xls_data[$key]) || empty($value["A"])){
							$rowsError[] = $key;
						}else{

							$this->loadModel("Inventory");
							$this->Product->recursive = -1;
							$product = $this->Product->findByPartNumber($value["A"]);

							if(!empty($product) && $this->request->data["Product"]["type"] == "update"){
								
								$value["C"] = str_replace(",", "", $value["C"]);
								$value["D"] = str_replace(",", "", $value["D"]);
								$value["E"] = str_replace(",", "", $value["E"]);
								$value["F"] = str_replace(",", "", $value["F"]);
								$state      = 	strtolower($value["J"]) == "activo" ? 1 : 0;

								$brand 		= $this->Product->Brand->find("first",array("conditions" => array("LOWER(name)" => strtolower($value["G"]) ),"recursive" => -1 ));
								$brandName 		= 	empty($brand) ? "N/A" 	: $brand["Brand"]["name"];
								$brandId 		= 	empty($brand) ? "1" 	: $brand["Brand"]["id"];
								$category 		= 	array_search($value["K"], $categoriesData);
								$cateogoryId	= 	empty($cateogory) ? "1" : $cateogory;

								$productID 									= $product["Product"]["id"];
								$product["Product"]["name"] 				= $value["B"];
								$product["Product"]["link"] 				= $value["I"];
								$product["Product"]["description"]			= empty($value["H"]) || is_null($value["H"]) ? "N/A" : $value["H"];
								$product["Product"]["purchase_price_cop"] 	= floatval($value["C"]);
								$product["Product"]["purchase_price_usd"] 	= floatval($value["D"]);
								$product["Product"]["purchase_price_wo"] 	= floatval($value["E"]);
								$product["Product"]["list_price_usd"] 		= floatval($value["F"]);
								$product["Product"]["brand"]				= $brandName;
								$product["Product"]["brand_id"]				= $brandId;
								$product["Product"]["state"]				= $state;
								$product["Product"]["category_id"]			= $cateogoryId;

								$this->Product->save($product);	
								$rowsUpdate[] = $key;
							}else{

								$value["G"] = str_replace(",", "", $value["G"]);
								$value["H"] = str_replace(",", "", $value["H"]);
								$value["I"] = str_replace(",", "", $value["I"]);
								$value["J"] = str_replace(",", "", $value["J"]);
								$state      = 	strtolower($value["N"]) == "activo" ? 1 : 0;

								$brand 		= $this->Product->Brand->find("first",array("conditions" => array("LOWER(name)" => strtolower($value["K"]) ),"recursive" => -1 ));
								$brandName 		= 	empty($brand) ? "N/A" 	: $brand["Brand"]["name"];
								$brandId 		= 	empty($brand) ? "1" 	: $brand["Brand"]["id"];
								$category 		= 	array_search($value["O"], $categoriesData);
								$cateogoryId	= 	empty($cateogory) ? "1" : $cateogory;

								$product = array(
									"Product" => array(
										"part_number" => $value["A"],
										"name" => $value["B"],
										"description" => empty($value["L"]) ? "N/A": $value["L"],
										"brand" => $brandName,
										"brand_id" => $brandId,
										"link" => $value["M"],
										"purchase_price_cop" => $value["G"],
										"purchase_price_usd" => $value["H"],
										"purchase_price_wo" => $value["I"],
										"list_price_usd" => $value["J"],
										"quantity" 		=> intval($value["C"]),
										"quantity_bog" => intval($value["D"]),
										"quantity_stm" => intval($value["E"]),
										"quantity_stb" => intval($value["F"]),
										"state"		   => $state,
										"category_id"  => $cateogoryId,
									)
								);

								$this->Product->create();
								$this->Product->save($product);
								$productID 	= $this->Product->id;
								$rowsCreate[] = $key;
							}
							
							if(intval($value["C"]) > 0 && $this->request->data["Product"]["type"] == "create"){
								$inventory = array(
						    		"Inventory" => array(
						    			"product_id" 	=> $productID,
						    			"quantity" 		=> intval($value["C"]),
						    			"warehouse"		=> "Medellín",
						    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
						    			"type_movement"	=> Configure::read("INVENTORY_TYPE.CARGA_INICIAL"),
						    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.CARGA_INICIAL")),
						    			"user_id"		=> AuthComponent::user("id"),
						    		)
						    	);

						    	$this->Inventory->create();
						    	$this->Inventory->save($inventory);
							}

					    	
							if(intval($value["D"]) > 0 && $this->request->data["Product"]["type"] == "create"){
						    	$inventory = array(
						    		"Inventory" => array(
						    			"product_id" 	=> $productID,
						    			"warehouse"		=> "Bogotá",
						    			"quantity" 		=> intval($value["D"]),
						    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
						    			"type_movement"	=> Configure::read("INVENTORY_TYPE.CARGA_INICIAL"),
						    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.CARGA_INICIAL")),
						    			"user_id"		=> AuthComponent::user("id"),
						    		)
						    	);

						    	$this->Inventory->create();
						    	$this->Inventory->save($inventory);
						    }

						    if(intval($value["E"]) > 0 && $this->request->data["Product"]["type"] == "create"){
						    	$inventory = array(
						    		"Inventory" => array(
						    			"product_id" 	=> $productID,
						    			"warehouse"		=> "ST Medellín",
						    			"quantity" 		=> intval($value["E"]),
						    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
						    			"type_movement"	=> Configure::read("INVENTORY_TYPE.CARGA_INICIAL"),
						    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.CARGA_INICIAL")),
						    			"user_id"		=> AuthComponent::user("id"),
						    		)
						    	);

						    	$this->Inventory->create();
						    	$this->Inventory->save($inventory);
						    }

						    if(intval($value["F"]) > 0 && $this->request->data["Product"]["type"] == "create"){
						    	$inventory = array(
						    		"Inventory" => array(
						    			"product_id" 	=> $productID,
						    			"warehouse"		=> "ST Bogotá",
						    			"quantity" 		=> intval($value["F"]),
						    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
						    			"type_movement"	=> Configure::read("INVENTORY_TYPE.CARGA_INICIAL"),
						    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.CARGA_INICIAL")),
						    			"user_id"		=> AuthComponent::user("id"),
						    		)
						    	);

						    	$this->Inventory->create();
						    	$this->Inventory->save($inventory);
						    }

						}
					}
					$mensaje = "";
					if(!empty($rowsError)){
						$mensaje.=  "<br> \n No se procesaron los registros de los número de líneas: ".implode(",", $rowsError);
					}
					if(!empty($rowsCreate)){
						$mensaje.=  "<br> \n Se crearon los registros de los número de líneas: ".implode(",", $rowsCreate);
					}
					if(!empty($rowsUpdate)){
						$mensaje.=  "<br> \n Se actualizaron los registros de los número de líneas: ".implode(",\n", $rowsUpdate);
					}
					return $mensaje;
				}
			}else{
				return 1;
			}

			
			die();
		}else{
			return 0;
		}

		
	}

	public function masive_products_post(){
		var_dump($this->request->data);
		die();
	}

	public function change($id){
		$this->autoRender = false;
		$id = $this->desencriptarCadena($id);
		$this->Product->recursive 		= -1;
		$product 						= $this->Product->findById($id);
		$product["Product"]["state"] 	= $product["Product"]["state"] == "1" ?  0 : 1;
		$this->Product->save($product);
	}

	

	public function export1($time = null){
		$this->autoRender = false;
		$this->loadModel("ProductsLock");

		$order 		= array("quantity" => "DESC" );
		$recursive 	= -1;
		$conditions = array("deleted" => 0);
		$fields		= array("id","part_number","name","quantity","brand","description","purchase_price_cop","purchase_price_usd","purchase_price_wo");
		$products 	= $this->Product->find("all",compact("order","recursive","fields","conditions"));

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$spreadsheet->getProperties()->setCreator('Kebco SAS')
			        ->setLastModifiedBy('Kebco SAS')
			        ->setTitle('Inventario CRM')
			        ->setSubject('Inventario CRM')
			        ->setDescription('Inventario de productos para el CRM')
			        ->setKeywords('Inventario CRM Productos')
			        ->setCategory('Inventario');

		// Add some data
		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'REFERENCIA')
			        ->setCellValue('B1', 'PRODUCTO')
			        ->setCellValue('C1', 'CANTIDAD MEDELLIN')
			        ->setCellValue('D1', 'CANTIDAD BOGOTA')
			        ->setCellValue('E1', 'CANTIDAD TRANSITO')
			        ->setCellValue('F1', 'CANTIDAD BLOQUEDADO')
			        ->setCellValue('G1', 'COSTO UNITARIO')
			        ->setCellValue('H1', 'COSTO FINAL')
			        ->setCellValue('I1', 'MARCA')
			        ->setCellValue('J1', 'DESCRIPCIÓN');

		$i = 2;
		$costoTotal = 0;

		foreach ($products as $key => $value) {

			$productData = $this->getTotalInventoryProduct($value["Product"]["id"]);

			$bloqueo = $this->ProductsLock->field("SUM(ProductsLock.quantity+ProductsLock.quantity_bog+ProductsLock.quantity_back) total",["ProductsLock.product_id" =>$value["Product"]["id"],"state"=>"1"]); 
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $value["Product"]["part_number"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $value["Product"]["name"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $productData["quantity"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $productData["quantity_bog"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $productData["quantity_back"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, empty( $bloqueo ) ? 0 : $bloqueo );
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $value["Product"]["purchase_price_cop"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, floatval($value["Product"]["purchase_price_cop"]) * ($productData["quantity"] + $productData["quantity_bog"]));
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $value["Product"]["brand"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, utf8_encode(strip_tags($value["Product"]["description"])));
			$costoTotal+= floatval($value["Product"]["purchase_price_cop"]) * ($productData["quantity"] + $productData["quantity_bog"]) ;
			$i++;
		}

		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, "");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, "");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, "");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, "");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, "Costo total");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $costoTotal);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, "");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, "");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, "");
		$i++;

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

		$spreadsheet->getActiveSheet()->setTitle('Inventario');
		$spreadsheet->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="inventario_kebco_'.time().'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 2025 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	public function costs($productId) {
		$this->layout = false;
		$cambios = $this->Product->Cost->findAllByProductId($this->desencriptarCadena($productId));
		$this->set("cambios",$cambios);
	}

	public function add_reference_final(){
		$this->autoRender = false;

		$requestData = $this->request->data;

		unset($this->request->data["request"]);
		unset($this->request->data["currency"]);
		unset($this->request->data["import_id"]);
		unset($this->request->data["quantity"]);

		$this->loadModel("ImportRequestsDetail");
		$this->loadModel("ImportRequestsDetailsProduct");
		$this->loadModel("ImportProductsDetail");
		$this->loadModel("ImportProduct");

		$lastImportProduct = $this->ImportProduct->findByImportId($requestData["import_id"]);

		$dataDetail = [
			"ImportRequestsDetail" => [
				"import_request_id" => $requestData["request"],
				"user_id"			=> AuthComponent::user("id"),
				"type_request"		=> 2,
				"description"		=> "Referencia adicional para INVENTARIO",
				"state"				=> 2,
			]
		];

		$this->ImportRequestsDetail->create();
		if($this->ImportRequestsDetail->save($dataDetail)){
			$idDetail 			= $this->ImportRequestsDetail->id;

			$dataDetailProduct  = [
				"ImportRequestsDetailsProduct" => [
					"import_requests_detail_id" => $idDetail,
					"product_id"				=> $requestData["id"],
					"quantity"					=> $requestData["quantity"],
				]
			];

			$this->ImportRequestsDetailsProduct->create();
			if($this->ImportRequestsDetailsProduct->save($dataDetailProduct)){
				$detailRequestProductId = $this->ImportRequestsDetailsProduct->id;

				$dataDetailProductImport  = [
					"ImportProductsDetail" => [
						"import_requests_detail_id" => $idDetail,
						"product_id"				=> $requestData["id"],
						"quantity"					=> $requestData["quantity"],
						"import_id"					=> $requestData["import_id"],
					]
				];

				$this->ImportProductsDetail->create();
				if($this->ImportProductsDetail->save($dataDetailProductImport)){

					$dataProductImport = $this->ImportProduct->findByProductIdAndImportId($requestData["id"],$requestData["import_id"]);

					if (!empty($dataProductImport)) {
						$dataProductImport["ImportProduct"]["quantity"]+=$requestData["quantity"];
						$dataProductImport["ImportProduct"]["quantity_back"]+=$requestData["quantity"];
					}else{
						$dataProductImport  = [
							"ImportProduct" => [
								"product_id"				=> $requestData["id"],
								"quantity"					=> $requestData["quantity"],
								"quantity_back"				=> $requestData["quantity"],
								"quotations_products_id"	=> 0,
								"import_id"					=> $requestData["import_id"],
								"currency"					=> $requestData["currency"],
								"price"						=> $requestData["currency"] == "usd" ? $requestData["purchase_price_usd"] : $requestData["purchase_price_cop"],
							]
						];

						$this->loadModel("Inventory");

						$this->Inventory->create();
						$this->Inventory->save([
							"product_id" => $requestData["id"],
							"import_id" => $requestData["import_id"],
							"quantity" => $requestData["quantity"],
							"warehouse" => 'Transito',
							"user_id" => AuthComponent::user("id"),
							"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
		    				"type_movement"	=> Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION"),
		    				"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION")),
						]);

						if ($lastImportProduct["Import"]["state"] == 1) {
							$dataProductImport["ImportProduct"]["proveedor"] 	= $lastImportProduct["ImportProduct"]["proveedor"];
							$dataProductImport["ImportProduct"]["numero_orden"] = $lastImportProduct["ImportProduct"]["numero_orden"];
							$dataProductImport["ImportProduct"]["fecha_orden"] 	= date("Y-m-d");
							$dataProductImport["ImportProduct"]["state_import"] = $lastImportProduct["ImportProduct"]["state_import"];
						}

						$this->ImportProduct->create();
					}

					
					if($this->ImportProduct->save($dataProductImport["ImportProduct"])){
						$this->Product->save($this->request->data);
						$this->Session->setFlash(__('Producto agregado correctamente.'),'Flash/success');
					}

				}

			}
		}

	}

	public function get_inventory_brand($brand_id){
		$this->layout 	= false;

		if ($this->request->is("post")) {

			if (isset($this->request->data["Product"]["import_id"])) {
				$requestData = $this->request->data["Product"];

				if (!empty($this->request->data["quantity"])) {

					$this->loadModel("ImportRequestsDetail");
					$this->loadModel("ImportRequestsDetailsProduct");
					$this->loadModel("ImportProductsDetail");
					$this->loadModel("ImportProduct");

					foreach ($this->request->data["quantity"] as $productID => $value) {

						$dataDetail = [
							"ImportRequestsDetail" => [
								"import_request_id" => $requestData["request"],
								"user_id"			=> AuthComponent::user("id"),
								"type_request"		=> 2,
								"description"		=> "Referencia adicional para INVENTARIO",
								"state"				=> 2,
							]
						];

						$costProduct = $requestData["currency"]=="usd" ? $this->Product->field("purchase_price_usd",["id" => $productID]) : $this->Product->field("purchase_price_cop",["id" => $productID]);

						$this->ImportRequestsDetail->create();
						if($this->ImportRequestsDetail->save($dataDetail)){
							$idDetail 			= $this->ImportRequestsDetail->id;

							$dataDetailProduct  = [
								"ImportRequestsDetailsProduct" => [
									"import_requests_detail_id" => $idDetail,
									"product_id"				=> $productID,
									"quantity"					=> $value[0],
								]
							];

							$this->ImportRequestsDetailsProduct->create();
							if($this->ImportRequestsDetailsProduct->save($dataDetailProduct)){
								$detailRequestProductId = $this->ImportRequestsDetailsProduct->id;

								$dataDetailProductImport  = [
									"ImportProductsDetail" => [
										"import_requests_detail_id" => $idDetail,
										"product_id"				=> $productID,
										"quantity"					=> $value[0],
										"import_id"					=> $requestData["import_id"],
									]
								];

								$this->ImportProductsDetail->create();
								if($this->ImportProductsDetail->save($dataDetailProductImport)){

									$dataProductImport  = [
										"ImportProduct" => [
											"product_id"				=> $productID,
											"quantity"					=> $value[0],
											"quantity_back"				=> $value[0],
											"quotations_products_id"	=> 0,
											"import_id"					=> $requestData["import_id"],
											"currency"					=> $requestData["currency"],
											"price"						=> $costProduct,
										]
									];

									$this->ImportProduct->create();
									if($this->ImportProduct->save($dataProductImport)){
										$this->Product->save($this->request->data);
										$this->Session->setFlash(__('Producto agregado correctamente, si cometiste un error debes consultarlo con gerencia y/o soporte.'),'Flash/success');
									}

								}

							}
						}


					}

					$this->redirect(["action"=>"products_import",$this->encryptString($requestData["import_id"])]);

				}
			}else{
				if (!empty($this->request->data["quantity"])) {

					$this->loadModel("ImportRequest");
					$this->loadModel("ImportRequestsDetailsProduct");

					$nacional 			= 0;
					$requestInfoId 		= $this->ImportRequest->getOrSaveRequest($brand_id,$nacional,1);

					foreach ($this->request->data["quantity"] as $productID => $value) {
						$total = $value[0];
						if ($total > 0) {
							$requestDetailId  	= $this->ImportRequest->ImportRequestsDetail->saveDataDetail(
								$requestInfoId,
								AuthComponent::user("id"),
								4,
								"Reposición manual por bajo inventario",
								null,
								$nacional
							);
							App::import("model","ImportRequestsDetailsProduct");
							$importRequestsDetailsProduct = new ImportRequestsDetailsProduct();
							$importRequestsDetailsProduct->saveDetailProduct($requestDetailId,[ ["id_product" => $productID, "quantity" => $total ] ]);
						}
					}
				}
				$this->Session->setFlash(__('Productos agregados correctamente'),'Flash/success');
				$this->redirect(["controller"=>"prospective_users","action"=>"request_import_brands","?"=>["brand_id"=>$brand_id]]);
			}

			

			
			
		}

		$conditions = ["min_stock >" => 0,"reorder >" => 0, "deleted" => 0, "Product.state" => 1 ];

		if ($brand_id != 0) {
			$conditions["Product.brand_id"] = $brand_id;
		}

		$this->Product->unBindModel( ["hasMany" => array_keys($this->Product->hasMany)] );
		$productos 		= $this->Product->find("all",[ "conditions" => $conditions]);
		$partsData 		= [];
		$costos 		= [];

		if (!empty($productos)) {
			$partsData				= $this->getValuesProductsWo($productos);
			$costos 				= $this->getCosts($partsData);
		}

		if ($brand_id == 4) {

			$categories 			= Set::extract($productos,"{n}.Category.name");
			$categories 			= array_unique($categories);

			$finalProducts 			= [];

			foreach ($productos as $key => $value) {
				$finalProducts[$value["Category"]["name"]][] = $value;
			}

			foreach ($finalProducts as $categoria => $_products) {
				$finalProducts[$categoria] = Set::sort($_products, '{n}.Product.part_number', 'desc');
			}

			$this->set("categories",$categories);
			$this->set("finalProducts",$finalProducts);
			$productos = [];
		}

		$this->set(compact("categoriesData","costos","partsData","productos",'brand_id'));
	}

	public function index() {
		$this->deleteCacheProducts1();
		$get 						= $this->request->query;
		if (!empty($get)) {
			$conditions = array();
			if(isset($get["q"])){
				$conditions				= array('OR' => array(
									            'LOWER(Product.part_number) LIKE' 			=> '%'.mb_strtolower(trim($get['q'])).'%',
									            'LOWER(Product.name) LIKE' 					=> '%'.mb_strtolower(trim($get['q'])).'%',
									            'LOWER(Product.description) LIKE' 			=> '%'.mb_strtolower(trim($get['q'])).'%',
									            'LOWER(Product.brand) LIKE' 				=> '%'.mb_strtolower(trim($get['q'])).'%',
									            'LOWER(Product.list_price_usd) LIKE' 		=> '%'.mb_strtolower(trim($get['q'])).'%'
									        )
										);
			}
			
			if (isset($get["brand"])) {
				$conditions["Product.brand_id"] = $get["brand"];
				$this->set("brandSelect", $get["brand"]);
			}

			if (isset($get["category1"])) {
				$categoriesInfo = $this->getAllIdsCategories($get["category1"]);
				$conditions["Product.category_id"] = $categoriesInfo ;
				$this->set("category1Select", $get["category1"]);
			}

			if (isset($get["category2"])) {
				$categoriesInfo = $this->getAllIdsCategories($get["category2"]);
				$conditions["Product.category_id"] = $categoriesInfo ;
				$this->set("category2Select", $get["category2"]);
			}

			if (isset($get["category3"])) {
				$categoriesInfo = $this->getAllIdsCategories($get["category3"]);
				$conditions["Product.category_id"] = $categoriesInfo ;
				$this->set("category3Select", $get["category3"]);
			}

			if (isset($get["category4"])) {
				$categoriesInfo = $this->getAllIdsCategories($get["category4"]);
				$conditions["Product.category_id"] = $categoriesInfo ;
				$this->set("category4Select", $get["category4"]);
			}
			
			if (isset($get["costo"])) {
				$costos = explode(",", $get["costo"]);
				if(count($costos) == 2){
					$conditions["Product.purchase_price_usd >="] = floatval($costos[0]);
					$conditions["Product.purchase_price_usd <="] = floatval($costos[1]);
					$this->set("costoMinSelect", $costos[0]);
					$this->set("costoMaxSelect", $costos[1]);
				}
			}

			if (isset($get["precio"])) {
				$precios = explode(",", $get["precio"]);
				if(count($precios) == 2){
					$conditions["Product.list_price_usd >="] = floatval($precios[0]);
					$conditions["Product.list_price_usd <="] = floatval($precios[1]);
					$this->set("precioMinSelect", $precios[0]);
					$this->set("precioMaxSelect", $precios[1]);
				}
			}

			if (isset($get["inventario"]) && !isset($get["q"])) {
				$conditionData = $get["inventario"];
				$referencias = $this->postWoApi(["number"=>$conditionData],"inventory_exists");
				$referencias = $this->object_to_array($referencias);
				$referencias = Set::extract($referencias,"{n}.Referencia");
				
				if(isset($conditions["OR"])){
					$conditions["OR"][] = array(
						"Product.part_number" => $referencias,
					);
				}else{
					$conditions["OR"] = array(
						"Product.part_number" => $referencias,
					);
				}
				$this->set("inventarioSelect",$get["inventario"]);
			}

		} else {
			$conditions 			= array();
		}

		if (AuthComponent::user("role") == "Asesor Externo") {
			$this->loadModel("CategoriesUser");
			$categoriesUser = $this->CategoriesUser->find("list",["fields"=>["category_id","category_id"],"conditions" => ["user_id"=>AuthComponent::user("id")] ]);
			$conditions["Product.category_id"] = array_values($categoriesUser);
		}

		$partsData 					= [];
		$precios 					= [];
		$costos 					= [];
		$conditions["deleted"] 		= 0;
		$order						= array('Product.id' => 'desc');
		$this->paginate 			= array(
										'order' 		=> $order,
							        	'limit' 		=> 10,
							        	'conditions' 	=> $conditions,
							    	);
		try {
			$products 					= $this->paginate('Product');
			if (!empty($products)) {
				$partsData				= $this->getValuesProductsWo($products);
				$precios 				= $this->getPrices($partsData);
				$costos 				= $this->getCosts($partsData);
			}
					
		} catch (Exception $e) {
			$products = array();			
		}

		$productos 				= $this->Product->find("all",["fields"=>["brand_id","category_id"],"conditions"=>$conditions,"recursive" => -1]);
		$idsBrands				= Set::extract($productos,"{n}.Product.brand_id");
		$idsCategory			= Set::extract($productos,"{n}.Product.category_id");
		$brands 				= $this->Product->Brand->find("all");

		$costoMin = $this->Product->field("MIN(purchase_price_usd) min",[]);
		$costoMax = $this->Product->field("MAX(purchase_price_usd) max",[]);

		$precioMin = $this->Product->field("MIN(list_price_usd) min",[]);
		$precioMax = $this->Product->field("MAX(list_price_usd) max",[]);

		$fields = array("Product.purchase_price_usd","SUM(Product.quantity+Product.quantity_bog) total", "Product.id");
		$recursive = -1;
		$group = array("id");
		$costoUsd = $this->Product->find("all",compact("conditions","fields","recursive","group"));

		$costoTotalUsd = 0;

		// if(!empty($costoUsd)){
		// 	foreach ($costoUsd as $key => $value) {
		// 		$productData 	= $this->getTotalInventoryProduct($value["Product"]["id"]);
		// 		$costoTotalUsd	+= ( ($productData["quantity"]+$productData["quantity_bog"]) *$value["Product"]["purchase_price_usd"]);
		// 	}
		// }

		$this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $grupoMax 		= $this->Product->Category->field("MAX(grupo)");

		$categoriesData = $this->getCategoryInfo(true);
		unset($categoriesData[0]);

		$categoriesInfoFinal = $this->getCagegoryData();
		// $categoriesSelect = $this->getEstructure(0,$categoriesInfoFinal);

		$unlokProducts 			= $this->validateUnlockProducts();
		$editProducts 			= $this->validateEditProducts();

		$productosBloqueados 	= $this->Product->field("count(*)",["state" => 0,"deleted" => 0]);

		$this->set(compact('products','brands','costoMin','costoMax','precioMax','precioMin','idsCategory','costoTotalUsd','trmActual','grupoMax',"grupoData","dataCategory","categoriesData","categoriesSelect","unlokProducts","productosBloqueados","editProducts","categoriesInfoFinal","partsData","precios","costos"));
	}

	public function export_by_category(){


		if($this->request->is("post")){

			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

			$spreadsheet->getProperties()->setCreator('Kebco SAS')
			        ->setLastModifiedBy('Kebco SAS')
			        ->setTitle('Categorias CRM')
			        ->setSubject('Categorias CRM')
			        ->setDescription('Categorias para aplicativo Dolibarr')
			        ->setKeywords('Categorias Dolibarr')
			        ->setCategory('Categorias');

			// Add some data
			$spreadsheet->setActiveSheetIndex(0)
				        ->setCellValue("A1", 'Ref.* (p.ref)')
				        ->setCellValue("B1", 'Etiqueta* (p.label)')
				        ->setCellValue("C1", 'Tipo* (p.fk_product_type)')
				        ->setCellValue("D1", 'En venta* (p.tosell)')
				        ->setCellValue("E1", 'En compra* (p.tobuy)')
				        ->setCellValue("F1", 'Descripción (p.description)')
				        ->setCellValue("G1", 'URL pública (p.url)')
				        ->setCellValue("H1", 'Código aduanero (p.customcode)')
				        ->setCellValue("I1", 'Código país (p.fk_country)')
				        ->setCellValue("J1", 'Código contable (ventas) (p.accountancy_code_sell)')
				        ->setCellValue("K1", 'Código contable (venta intracomunitaria) (p.accountancy_code_sell_intra)')
				        ->setCellValue("L1", 'Código de contabilidad (venta de exportación) (p.accountancy_code_sell_export)')
				        ->setCellValue("M1", 'Código contable (compras) (p.accountancy_code_buy)')
				        ->setCellValue("N1", 'Nota (pública) (p.note_public)')
				        ->setCellValue("O1", 'Nota (privada) (p.note)')
				        ->setCellValue("P1", 'Weight (p.weight)')
				        ->setCellValue("Q1", 'Peso unitario (p.weight_units)')
				        ->setCellValue("R1", 'Length (p.length)')
				        ->setCellValue("S1", 'Longitud unitaria (p.length_units)')
				        ->setCellValue("T1", 'Width (p.width)')
				        ->setCellValue("U1", 'Anchura unitaria (p.width_units)')
				        ->setCellValue("V1", 'Height (p.height)')
				        ->setCellValue("W1", 'Altura unitaria (p.height_units)')
				        ->setCellValue("X1", 'Surface (p.surface)')
				        ->setCellValue("Y1", 'Superficie unitaria (p.surface_units)')
				        ->setCellValue("Z1", 'Volume (p.volume)')
				        ->setCellValue("AA1", 'Volumen unitario (p.volume_units)')
				        ->setCellValue("AB1", 'Duración (p.duration)')
				        ->setCellValue("AC1", 'Nature of product (material/finished) (p.finished)')
				        ->setCellValue("AD1", 'PVP sin IVA (p.price)')
				        ->setCellValue("AE1", 'Precio de venta mín. (p.price_min)')
				        ->setCellValue("AF1", 'PVP con IVA (p.price_ttc)')
				        ->setCellValue("AG1", 'Precio mínimo de venta (IVA incluido) (p.price_min_ttc)')
				        ->setCellValue("AH1", 'PriceBaseType (p.price_base_type)')
				        ->setCellValue("AI1", 'Tasa IVA (p.tva_tx)')
				        ->setCellValue("AJ1", 'Fecha de creación (p.datec)')
				        ->setCellValue("AK1", 'Precio de compra (p.cost_price)')
				        ->setCellValue("AL1", 'Stock límite para alertas (p.seuil_stock_alerte)')
				        ->setCellValue("AM1", 'Valor (PMP) (p.pmp)')
				        ->setCellValue("AN1", 'Stock deseado (p.desiredstock)');

			$conditions = ["Product.deleted" => 0, "Product.state" => 1];

			$i = 2;

			if(!isset($this->request->data["select_all"])){
				if (!empty($this->request->data["category_1"])) {
					// $categoriesInfo = $this->getAllIdsCategories($get["category1"]);
					$conditions["Product.category_id"] = $this->request->data["category_1"] ;
					$this->set("category1Select", $this->request->data["category_1"]);
				}

				if (!empty($this->request->data["category_2"])) {
					// $categoriesInfo = $this->getAllIdsCategories($this->request->data["category2"]);
					$conditions["Product.category_id"] = $this->request->data["category_2"];
					$this->set("category2Select", $this->request->data["category_2"]);
				}

				if (!empty($this->request->data["category_3"])) {
					// $categoriesInfo = $this->getAllIdsCategories($this->request->data["category3"]);
					$conditions["Product.category_id"] = $this->request->data["category_3"] ;
					$this->set("category3Select", $this->request->data["category_3"]);
				}

				if (!empty($this->request->data["category_4"])) {
					// $categoriesInfo = $this->getAllIdsCategories($this->request->data["category4"]);
					$conditions["Product.category_id"] = $this->request->data["category_4"] ;
					$this->set("category4Select", $this->request->data["category_4"]);
				}
			}

			$products = $this->Product->find("all",["recursive" => -1, "conditions" => $conditions]);

			if(!empty($products)){
				foreach ($products as $key => $value) {
					$spreadsheet->setActiveSheetIndex(0)
				        ->setCellValue("A".$i, $value["Product"]["part_number"])
				        ->setCellValue("B".$i, $value["Product"]["name"])
				        ->setCellValue("C".$i, '1')
				        ->setCellValue("D".$i, '1')
				        ->setCellValue("E".$i, '1')
				        ->setCellValue("F".$i, $value["Product"]["description"])
				        ->setCellValue("G".$i, $value["Product"]["link"])
				        ->setCellValue("H".$i, '')
				        ->setCellValue("I".$i, '')
				        ->setCellValue("J".$i, '')
				        ->setCellValue("K".$i, '')
				        ->setCellValue("L".$i, '')
				        ->setCellValue("M".$i, '')
				        ->setCellValue("N".$i, '')
				        ->setCellValue("O".$i, '')
				        ->setCellValue("P".$i, '')
				        ->setCellValue("Q".$i, '')
				        ->setCellValue("R".$i, '')
				        ->setCellValue("S".$i, '')
				        ->setCellValue("T".$i, '')
				        ->setCellValue("U".$i, '')
				        ->setCellValue("V".$i, '')
				        ->setCellValue("W".$i, '')
				        ->setCellValue("X".$i, '')
				        ->setCellValue("Y".$i, '')
				        ->setCellValue("Z".$i, '')
				        ->setCellValue("AA".$i, '')
				        ->setCellValue("AB".$i, '')
				        ->setCellValue("AC".$i, '1')
				        ->setCellValue("AD".$i, $value["Product"]["list_price_usd"])
				        ->setCellValue("AE".$i, $value["Product"]["list_price_usd"])
				        ->setCellValue("AF".$i, $value["Product"]["list_price_usd"])
				        ->setCellValue("AG".$i, $value["Product"]["list_price_usd"])
				        ->setCellValue("AH".$i, $value["Product"]["list_price_usd"])
				        ->setCellValue("AI".$i, '0')
				        ->setCellValue("AJ".$i, date("Y-m-d",strtotime($value["Product"]["created"]) ))
				        ->setCellValue("AK".$i, $value["Product"]["purchase_price_usd"])
				        ->setCellValue("AL".$i, '1')
				        ->setCellValue("AM".$i, $value["Product"]["purchase_price_usd"])
				        ->setCellValue("AN".$i, '10');
				    $i++;
				}
			}

			$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);

			$spreadsheet->getActiveSheet()->setTitle('Productos');
			$spreadsheet->getActiveSheet()->getStyle('A1:AN1')->getFont()->setBold(true);
			$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());			

			$name = time();

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="categorias_kebco_'.$name.'.xlsx"');
			header('Cache-Control: max-age=0');
			header('Cache-Control: max-age=1');
			header('Expires: Mon, 26 Jul 2025 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0

			$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
			exit;										
		}



		$categoriasData = $this->Product->Category->find("list");
        $grupoMax 		= $this->Product->Category->field("MAX(grupo)");

		$categoriesData = $this->getCategoryInfo(true);
		unset($categoriesData[0]);

		$categoriesInfoFinal = $this->getCagegoryData();

		$this->set(compact("categoriasData","grupoMax","categoriesData","categoriesInfoFinal"));
	}

	public function search_params(){
		$this->layout = false;
		$conditions = array();

		if (!empty($this->request->data["marcasData"])) {
			$conditions["Product.brand_id"] = $this->request->data["marcasData"];
		}

		if (!empty($this->request->data["category_1"])) {
			$categoriesInfo = $this->getAllIdsCategories($this->request->data["category_1"]);
			$conditions["Product.category_id"] = $categoriesInfo ;
		}

		if (!empty($this->request->data["category_2"])) {
			$categoriesInfo = $this->getAllIdsCategories($this->request->data["category_2"]);
			$conditions["Product.category_id"] = $categoriesInfo ;
		}

		if (!empty($this->request->data["category_3"])) {
			$categoriesInfo = $this->getAllIdsCategories($this->request->data["category_3"]);
			$conditions["Product.category_id"] = $categoriesInfo ;
		}

		if (!empty($this->request->data["category_4"])) {
			$categoriesInfo = $this->getAllIdsCategories($this->request->data["category_4"]);
			$conditions["Product.category_id"] = $categoriesInfo ;
		}

		if(empty($conditions)){
			$conditions["Product.id"] = 0;
		}

		$products = $this->Product->find("all",compact("conditions"));

		$this->set("products", $products);

	}

	public function unlock_products(){
		$unlokProducts = $this->validateUnlockProducts();
		if(!$unlokProducts){
			$this->Session->setFlash('Acción no permitida','Flash/error');
			$this->redirect(array("controller" => "products", "action" => "index"));
		}

		$products 		= $this->Product->findAllByStateAndDeleted(0,0);
		$categoriesData = $this->getCategoryInfo(true);

		$this->set("products",$products);
		$this->set("categoriesData",$categoriesData);
		$this->set("unlokProducts",$unlokProducts);

	}

	public function view($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Product->exists($id)) {
			throw new NotFoundException('El producto esta invalido');
		}
		$this->loadModel('Quotation');
		$product 								= $this->Product->findByIdAndDeleted($id,0);
		if(empty($product)){
			throw new NotFoundException(__('Invalid product'));
		}
		$cotizaciones_productos	 				= $this->Product->QuotationsProduct->find_quotation_id($id);
		$verification_sales_product 			= $this->Product->FlowStagesProduct->FlowStage->payment_verification_sales_product();
		$conditions								= array('Quotation.id' => $cotizaciones_productos);

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}

		// $conditions								= array('Quotation.id' => $cotizaciones_productos,'Quotation.created >=' => '2019-01-01',);

		$this->paginate 						= array(
													'order' 		=> ["Quotation.created"=>"DESC"],
										        	'limit' 		=> 22,
										        	'conditions' 	=> $conditions,
										    	);
		$cotizaciones 							= $this->paginate('Quotation');
		$categoriasData = $this->Product->Category->find("list");
        $grupoMax 		= $this->Product->Category->field("MAX(grupo)");

        $categoriesData = $this->getCategoryInfo();
        $editProducts = $this->validateEditProducts();
        if ($product["Product"]["normal"] == 2) {
        	$this->loadModel("Composition");
        	$compositions = $this->Composition->findAllByPrincipal($id);
        	$this->set("compositions",$compositions);
        }
        $partsData = $this->getValuesProductsWo([$product]);
        $costoWo   = $this->postWoApi(["part_number" => $product["Product"]["part_number"]],"get_cost");
		unset($categoriesData[0]);

		$this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];

		$this->set(compact("categoriesData"));
		$this->set(compact('product','cotizaciones','grupoMax','categoriasData','editProducts','partsData','costoWo',"trmActual"));
	}

	public function getDataFinalSend(){
		$this->layout = false;
		$this->loadModel("ClientsNatural");
		$this->loadModel("ContacsUser");

		$celulares 	= array();
		$correos 	= array();

		if(!empty($this->request->data["datos"])){
			foreach ($this->request->data["datos"] as $key => $value) {
				if($value["type"] == "natural"){
					$dataC = $this->ClientsNatural->find("first",["fields" => ["email", "cell_phone"], "conditions" => ["id" => $value["id"]], "recursive" => -1]);
					$dataC = Set::extract($dataC, "ClientsNatural");
				}else{
					$dataC = $this->ContacsUser->find("first",["fields" => ["email", "cell_phone"], "conditions" =>["id" => $value["id"]], "recursive" => -1 ]);
					$dataC = Set::extract($dataC, "ContacsUser");
				}
				if(trim($dataC["email"]) != "" && filter_var(trim($dataC["email"]), FILTER_VALIDATE_EMAIL)){
					if(!in_array($dataC["email"], $correos)){
						$correos[] = $dataC["email"];
					}
				}

				if(trim($dataC["cell_phone"]) != ""){
					if(!in_array("+57".$dataC["cell_phone"], $celulares)){
						$celulares[] = "+57".$dataC["cell_phone"];
					}
				}
			}
		}
		$this->set(compact("celulares","correos"));
	}

	public function send_campana(){
		$this->loadModel("MailingList");

		$list 		= array();
		$listEmails = array();
		$data 		= $this->MailingList->findAllByState(1);

		if(!empty($data)){
			$dataLists 	= $data;
			foreach ($dataLists as $key => $value) {
				if($value["MailingList"]["type"] == 1){
					$lists[$value["MailingList"]["id"]] = $value["MailingList"];
				}else{
					$listEmails[$value["MailingList"]["id"]] = $value["MailingList"];
				}
			}
		}

		$this->set(compact("lists","listEmails"));
	}

	public function send_remarketing(){
		$this->autoRender = false;
		$this->loadModel("Campaign");
		$this->loadModel("NewsletterReject");

		if(in_array($this->request->data["Product"]["type"], ["EMAIL","AMBOS"])){

			$emailsReject 	= $this->NewsletterReject->find("all",["fields" => ["email"]]);
			$emails 		= !isset($this->request->data["Product"]["correos"]) ? null : explode(",", $this->request->data["Product"]["correos"]);
			$cuerpo 		= $this->request->data["Product"]["cuerpo"];
			$nombre 		= $this->request->data["Product"]["name"];

			if(!empty($emailsReject)){
				$emailsReject = Set::extract($emailsReject,"{n}.NewsletterReject.email");
				$emailData = $emails;
				$emails = array();
				if(!empty($emailsReject)){
					foreach ($emailData as $key => $value) {
						if(!in_array($value, $emailsReject)){
							$emails[] = $value;
						}
					}
				}
			}
		}else{
			$emails = array();
			$cuerpo = "";
			$nombre = "";
		}

		if(in_array($this->request->data["Product"]["type"], ["WHATSAPP","AMBOS"])){
			$numbers  		= !isset($this->request->data["Product"]["whatsapps"]) ? [] : explode(",", $this->request->data["Product"]["whatsapps"]);
			$msg_text 		= $this->request->data["Product"]["msg_text"];
			$msg_file_txt 	= empty($this->request->data["Product"]["msg_file_txt"]) ? "" : $this->request->data["Product"]["msg_file_txt"]; 
			$msg_file 	    = $this->request->data["Product"]["msg_file"];
			$this->Session->write("dataFileWp",$msg_file);
		}else{
			$numbers 		= array();
			$msg_text 		= "";
			$msg_file_txt	= "";
			$msg_file 		= "";
			$this->Session->write("dataFileWp",$msg_file);
		}

		if($this->request->data["Product"]["type"] == "WHATSAPP"){
			$this->request->data["Product"]["name"] = $this->request->data["Product"]["name_campaign"];
		}

		$dataCampaign = array(
			"Campaign" => array(
				"name" => $this->request->data["Product"]["name_campaign"],
				"subject" => $this->request->data["Product"]["name"],
				"content" => $this->request->data["Product"]["cuerpo"],
				"mails_senders" => !isset($this->request->data["Product"]["correos"]) && !empty($emails) ? null : implode(",", $emails),
				"cell_senders" => !isset($this->request->data["Product"]["whatsapps"]) ? null : $this->request->data["Product"]["whatsapps"],
			)
		);

		if(isset($this->request->data["Product"]["product_ids"])){
			$dataCampaign["Campaign"]["type"] = 2;
			$dataCampaign["Campaign"]["deadline"] = $this->request->data["Product"]["deadline"];
			$datosProductos = array("type" => $this->request->data["Product"]["controlProductos"], "products" => $this->request->data["Product"]["product_ids"] );
			$dataCampaign["Campaign"]["products"] = json_encode($datosProductos);
 		}

 		if(!isset($this->request->data["Product"]["test"]) || (isset($this->request->data["Product"]["test"]) && $this->request->data["Product"]["test"] == 0) ){
			$this->Campaign->create();
			$this->Campaign->save($dataCampaign);
			$campaignId = $this->Campaign->id;
 		}else{
 			$campaignId = 0;
 		}

		if(in_array($this->request->data["Product"]["type"], ["WHATSAPP","AMBOS"])){
			if(!empty($this->request->data["Product"]["msg_file"]) && !empty($this->request->data["Product"]["msg_file"]["name"]) ){

				$file 		= $this->request->data["Product"]["msg_file"];
				$path_parts = pathinfo($file["name"]);
				$fileName   = time().".".$path_parts["extension"];
				$ruta_img 	= WWW_ROOT.'files/Whatsapp/';

				if (!file_exists($ruta_img)) {
                    mkdir($ruta_img, 0777, true);
                }
                if(move_uploaded_file($file['tmp_name'], $ruta_img.$fileName)) {
                	$fileMsgUrl = Router::url("/",true)."files/Whatsapp/".$fileName;
                	foreach ($numbers as $key => $phone) {

						$phone = str_replace("+", "", $phone);
                		time_sleep_until(microtime(true)+2);

                		$this->setFileMsgMasive($phone,$msg_text,$fileName,$fileMsgUrl);

                		if(!in_array($path_parts["extension"], ["jpg","jpeg","png","gif"])){
                            $this->setTextMsg($phone,$msg_text,true);
                        }

                	}
                }
			}else{
				foreach ($numbers as $key => $phone) {
					$phone = str_replace("+", "", $phone);
                	time_sleep_until(microtime(true)+2);
                	$this->setTextMsg($phone,$msg_text,true);
				}
			}
		}

		$this->Session->setFlash('Campaña gestionada con éxito.','Flash/success');
 		return json_encode(compact("campaignId","emails","numbers","cuerpo","nombre","msg_text","msg_file_txt","msg_file"));
		
	}

	private function sendMessageEmail(){

	}

	public function sendMessageOneEmail(){
		$this->autoRender = false;
		$email   = $this->request->data["email"];
		$options = array(
			'to'		=> $email,
			'subject'	=> $this->request->data["name"],
			'content'	=> str_replace('@@EMAIL@@', $this->encryptString($email), $this->request->data["cuerpo"]),
		);
		$this->sendMailSendGrid($options, $this->request->data["campaignId"]);
	}

	public function sendMessageOneTXT(){
		$this->autoRender = false;
		time_sleep_until(microtime(true)+1);
		$phone = str_replace("+", "", $this->request->data["phone"]);
		time_sleep_until(microtime(true)+1);
		if(empty($this->request->data["msg_file_txt"])){
			$this->setTextMsg($phone, $this->request->data["msg_text"],true);
		}else{
			$this->setFileMsg($phone, $this->request->data["msg_text"], $this->request->form["file"],$this->request->data["msg_file_txt"]);
		}
	}

	public function getDataRemarket(){
		$this->layout = false;
		$cotizaciones = array();

		$ini = isset($this->request->data["ini"]) ? $this->request->data["ini"] : date('Y-m-d',strtotime('-6 month'));
		$end = isset($this->request->data["end"]) ? $this->request->data["end"] : date('Y-m-d');

		$quotations	= $this->Product->QuotationsProduct->find_quotation_id($this->request->data["products"],$this->request->data["controlProductos"],$this->request->data["type"],$ini, $end);

		if(!empty($quotations)){
			$conditions	= array('Quotation.id' => $quotations);


			if($this->request->data["type"] == "venta"){
				$pagados 			= $this->Product->FlowStagesProduct->FlowStage->payment_verification_sales_product();
				$conditions["Quotation.prospective_users_id"] = $pagados;
			}
			$this->loadModel('Quotation');
			if(!isset($this->request->data["is_report"])){
				$this->Quotation->unBindModel(["belongsTo" => ["FlowStage","User","Header"], "hasMany" => ["QuotationsProduct"]]);
			}else{
				$this->Quotation->unBindModel(["belongsTo" => ["FlowStage","User","Header"]]);
			}
			$cotizaciones = $this->Quotation->find("all",compact("conditions"));

			$datosCotizaciones = array();

			if(!empty($cotizaciones)){
				foreach ($cotizaciones as $key => $value) {
					if(isset($this->request->data["is_report"])){
						$value["Customer"] = $this->getDataCustomer($value["ProspectiveUser"]["id"],true);
					}
					if($this->request->data["type"] == "venta"){
						$id_etapa_cotizado 		= $this->Product->FlowStagesProduct->FlowStage->id_latest_regystri_state_cotizado($value["ProspectiveUser"]["id"]);
						$datos 					= $this->Product->FlowStagesProduct->FlowStage->get_data($id_etapa_cotizado);
						if($datos['FlowStage']['document'] == $value["Quotation"]["id"]){
							$datosCotizaciones[$value["ProspectiveUser"]["id"]] = $value;
						}
					}else{
						$datosCotizaciones[$value["ProspectiveUser"]["id"]] = $value;
					}

				}
			}
			$cotizaciones = $datosCotizaciones;
		}

		// echo "<pre>";
		// print_r(array_values($cotizaciones)[0]);
		// die();

		$this->set(compact("cotizaciones"));
	}

	public function validExistencia(){
		$this->autoRender 											= false;
		if ($this->request->is('ajax')) {
			if(empty($this->request->data["id"])){
				return $this->Product->exist_product($this->request->data['numero_parte']);
			}else{
				return $this->Product->exist_product($this->request->data['numero_parte'], $this->request->data["id"]) == 1 ? 0 : 1;
			}
		}
	}

	private function getListReferences(){
		$referencias = $this->Product->find("list",["fields" => ["part_number","part_number"],"conditions" => ["deleted" => 0]]);
		return $referencias;
	}

	public function add() {
		$rolesPermitidos = $this->validateAddProducts();
		$marca 			 = $this->Product->Brand->find("list",["conditions2"=>["Brand.brand_id" => 0]]);
		$referencias     = $this->getListReferences();
		// $referencias     = [];
		unset($marca["1"]);
		if ($this->request->is('post')) {
			$exist = $this->Product->exist_product(trim($this->request->data['Product']['part_number']));
			if ($exist == 0) {
				
				$imagen = 1;
				$this->request->data["Product"]["img"] 					= "default.jpg";

				$this->request->data['Product']['list_price_usd'] 		= $this->replaceText($this->request->data['Product']['list_price_usd'],".", "");
				$this->request->data['Product']['purchase_price_cop'] 	= $this->replaceText($this->request->data['Product']['purchase_price_cop'],".", "");
				
				$this->request->data["Product"]["brand_id"] 			= $this->request->data["Product"]["brand"];
				$this->request->data["Product"]["brand"]    			= $this->Product->Brand->findById($this->request->data["Product"]["brand"])["Brand"]["name"];


				if(!$rolesPermitidos){
					$this->request->data["Product"]["state"] = 0;
				}else{
					$this->request->data["Product"]["state"] = 1;
				}

				$this->request->data["Product"]["long_description"] = $this->request->data["Product"]["description"];

				if(!empty($this->request->data["Product"]["category_4"])){
					$this->request->data["Product"]["category_id"] = $this->request->data["Product"]["category_4"];
				}elseif(!empty($this->request->data["Product"]["category_3"])){
					$this->request->data["Product"]["category_id"] = $this->request->data["Product"]["category_3"];
				}elseif(!empty($this->request->data["Product"]["category_2"])){
					$this->request->data["Product"]["category_id"] = $this->request->data["Product"]["category_2"];
				}elseif(!empty($this->request->data["Product"]["category_1"])){
					$this->request->data["Product"]["category_id"] = $this->request->data["Product"]["category_1"];
				}else{
					$this->request->data["Product"]["category_id"] = 1;
				}

				$this->request->data["Product"]["user_id"] = AuthComponent::user("id");

				$this->request->data['Product']['part_number'] = strtoupper(trim($this->request->data['Product']['part_number']));

				if ($this->request->data["Product"]["purchase_price_usd"] != 0) {
	    			$this->request->data["Product"]["last_change"] = date("Y-m-d H:i:s");
				}

				if ($imagen == 1) {
					$this->Product->create();
					if ($this->Product->save($this->request->data)) {
						$id_colum 			= $this->Product->id;

						if ($this->request->data["Product"]["purchase_price_usd"] != 0) {
							$this->loadModel("Cost");
			    			$this->Cost->create();
			    			$this->Cost->save(["Cost" => ["user_id"=>AuthComponent::user("id"),"purchase_price_usd" => $this->request->data["Product"]["purchase_price_usd"], "product_id" => $id_colum, "pre_purchase_price_usd" => 0  ] ]);
						}

						$this->saveDataLogsUser(2,'Product',$id_colum);
						$this->Session->delete('imagenModelo');
						if ($this->request->data["Product"]["state"] == 0) {
							$mensaje = 'El producto se ha guardado satisfactoriamente, pero no podrá ser usado en cotizaciones ya que debe ser aprobado por el área encargada.';
						}else{
							$mensaje = 'El producto se ha guardado satisfactoriamente';
						}

						if ($this->request->data["Product"]["normal"] == "2" && !empty($this->request->data["Composition"]["product_id"])) 
						{
							$this->loadModel("Composition");
							foreach ($this->request->data["Composition"]["product_id"] as $key => $value) {
								$composition = ["Composition" => ["principal"=>$id_colum,"product_id" => $value, ] ];
								$this->Composition->create();
								$this->Composition->save($composition);
							}
						}

						$this->Session->setFlash($mensaje,'Flash/success');
						return $this->redirect(array('action' => 'images', $this->encryptString($id_colum) ));
					} else {
						$this->Session->setFlash('El producto no se ha guardado, por favor inténtalo más tarde','Flash/error');
					}
				} else {
					$this->validateImageState($imagen,true);
				}
			} else {
				$this->Session->setFlash('La referencia ingresada ya existe, por favor valida','Flash/error');
			}
		}
		
		$categoriesData = $this->getCategoryInfo();
		unset($categoriesData[0]);
		$categoriesInfoFinal = $this->getCagegoryData();
		$this->set(compact("categoriesData","categoriesInfoFinal"));
		$this->set(compact('marca','referencias'));

		$this->set("action","add");
		$this->set("id",0);
	}

	public function delete_compuestos(){
		$this->autoRender = false;
		$this->Session->write("COMPUESTOS",[]);
		if (!$this->request->is("ajax")) {
			return [];
		}			
	}

	public function delete_compuesto(){
		$this->autoRender = false;
		$compuestos = $this->get_compuestos();
		unset($compuestos[$this->request->data["id"]]);
		$this->Session->write("COMPUESTOS",$compuestos);
	}

	public function add_compuesto($id,$type){
		$this->Product->recursive = -1;
		$this->layout 	= 	false;
		$compuestos 	= $this->get_compuestos();
		$ids 			= [];
		if (!empty($compuestos)) {
			$ids = Set::extract($compuestos,"{n}.Composition.product_id");
		}

		if ($this->request->is("post")) {
			$this->request->data["Composition"]["id"] = time();	
			$this->Product->recursive = -1;			
			$ingredient = $this->Product->findById($this->request->data["Composition"]["product_id"]);
			$data 		= $this->request->data;
			$data		= array_merge($data,$ingredient);
			$compuestos = $this->get_compuestos();
			$compuestos[$ingredient["Product"]["id"]] = $data;
			$this->Session->write("COMPUESTOS",$compuestos);
			die();
		}

		$ingredients 	= 	$this->Product->find("all",["conditions"=> ["Product.state" => 1, "Product.normal" => 1, "Product.id !=" => $ids ] ]);
		$ingredientes 	=	$ingredients;
		$ingredients 	= 	[];

		foreach ($ingredientes as $key => $value) {
			$ingredients[$value["Product"]["id"]] = $value["Product"]["part_number"]." - ".$value["Product"]["name"];
		}
		$this->set("ingredients",$ingredients);
		$this->set("id",$id);
	}

	private function get_compuestos(){
		return is_null($this->Session->read("COMPUESTOS")) ? $this->delete_compuestos() : $this->Session->read("COMPUESTOS");
	}

	public function list_compuestos(){
		$this->layout = false;
		$compuestos = $this->get_compuestos();
		$this->set("id",$this->request->query["id"]);
		$this->set("action",$this->request->query["action"]);
		$this->set("compuestos",$compuestos);
	}

	public function set_gruposView($show = true){
		$this->Product->Category->unBindModel(array("hasMany" => array("Product"),"belongsTo" => array("FatherCategory")));
		$categories = $this->Product->Category->find("all",["order"=> ["Grupo" => "ASC","id" => "ASC"]]);
		$grupos = array();
		$categorias = array();

		foreach ($categories as $key => $value) {
			if(!array_key_exists($value["Category"]["grupo"], $grupos)){
				$percentaje = $show ? "0%" : "";
				$grupos[$value["Category"]["grupo"]] = "Grupo ".$value["Category"]["grupo"];
				$categorias[$value["Category"]["grupo"]][0] = "Sin categoría ".$percentaje;
			}
		}

		foreach ($categories as $key => $value) {
			$marge = $show ? $value["Category"]["margen"]."%" : "";
			$categorias[$value["Category"]["grupo"]][$value["Category"]["id"]] = $value["Category"]["name"]." ".$marge; 
			
		}
		$this->set(compact('marca','categorias','grupos'));
	}

	public function editor() {
		$this->layout = false;
		$this->set("root_url",$this->request->referer());
	}

	public function delete_document($id, $num) {
		$this->layout = false;
		$id 	 = $this->decrypt($id);
		$product = $this->Product->findById($id);
		$product["Product"]["manual_".$num] = null;
		$this->Product->save($product);
		$this->redirect(["action"=>"images",$this->encryptString($id)]);
 	}

 	public function caracteristicas($id)
 	{
 		$this->loadModel("Feature");
 		$id 	= $this->decrypt($id);
		$datos  = $this->Product->findById($id);

		if ($this->request->is(array('post', 'put'))) {

			$products = $this->reorder_data();

			$bullets  = isset($this->request->data["bullets"]["title"]) ? $this->request->data["bullets"]["title"] : [];

			$this->loadModel("Bullet");
			$this->Bullet->deleteAll(["Bullet.product_id"=>$id],false);
			if(!empty($bullets)){
				foreach ($bullets as $key => $value) {
					$this->Bullet->create();
					$this->Bullet->save(["product_id"=>$id,"title"=>$value]);
				}
			}

			$this->loadModel("ProductsFeaturesValues");
			
			$this->loadModel("FeaturesValue");

			$this->ProductsFeaturesValues->deleteAll(array('ProductsFeaturesValues.product_id' => $this->request->data["Product"]["id"]), false);

			foreach ($products as $key => $value) {
				if($value["features_value_id"] != ''){
					$features_value_id = $value["features_value_id"];
				}else{
					if($value["feature_name"] != ""){

						$idFeat = $this->Feature->field("id",["name"=>$value["feature_name"]]);

						if($idFeat === false){
							$this->Feature->create();
							$this->Feature->save(["name" =>$value["feature_name"] ]);
							$feature_id = $this->Feature->id;
						}else{
							$feature_id = $idFeat;
						}
						
					}else{
						$feature_id = $value["feature_id"];
					}

					$this->FeaturesValue->create();
					$this->FeaturesValue->save(["name" => $value["features_value_id_name"], "feature_id" => $feature_id ]);
					$features_value_id = $this->FeaturesValue->id;
				}

				$this->ProductsFeaturesValues->create();
				$this->ProductsFeaturesValues->save(
					["product_id" => $this->request->data["Product"]["id"], "features_value_id" => $features_value_id ]
				);
			}
			return $this->redirect(array('action' => 'caracteristicas', $this->encryptString($id)));
			$this->Session->setFlash(__('Datos guardadas correctamente'),'Flash/success');
		}

		$this->set("features",$this->Feature->find("list"));

		$this->set("datos",$datos);
 	}


 	public function reorder_data(){
 		$products = [];

 		foreach ($this->request->data["feature_id"] as $key => $value) {
 			$products[] = [
 				"feature_id" => $this->request->data["feature_id"][$key],
 				"feature_name" => $this->request->data["feature_name"][$key],
 				"features_value_id" => $this->request->data["features_value_id"][$key],
 				"features_value_id_name" => $this->request->data["features_value_id_name"][$key],
 			];
 		}

 		return $products;
 	}


	public function images($id) {
		$id = $this->decrypt($id);
		$datos = $this->Product->findById($id);
		if ($this->request->is(array('post', 'put'))) {
			$imagen = 0;
			if ($this->request->data['Product']['img']['name'] != '') {
				$imagen 											= $this->loadPhoto($this->request->data['Product']['img'],'products');
				$this->request->data['Product']['img'] 				= $this->Session->read('imagenModelo');
			} else {
				unset($this->request->data['Product']['img']);
			}

			if ($this->request->data['Product']['img2']['name'] != '') {
				$imagen 											= $this->loadPhoto($this->request->data['Product']['img2'],'products');
				$this->request->data['Product']['img2'] 			= $this->Session->read('imagenModelo');
			}else{
				unset($this->request->data['Product']['img2']);
			}

			if ($this->request->data['Product']['img3']['name'] != '') {
				$imagen 											= $this->loadPhoto($this->request->data['Product']['img3'],'products');
				$this->request->data['Product']['img3'] 			= $this->Session->read('imagenModelo');
			}else{
				unset($this->request->data['Product']['img3']);
			}

			if ($this->request->data['Product']['img4']['name'] != '') {
				$imagen 											= $this->loadPhoto($this->request->data['Product']['img4'],'products');
				$this->request->data['Product']['img4'] 			= $this->Session->read('imagenModelo');
			}else{
				unset($this->request->data['Product']['img4']);
			}

			if ($this->request->data['Product']['img5']['name'] != '') {
				$imagen 											= $this->loadPhoto($this->request->data['Product']['img5'],'products');
				$this->request->data['Product']['img5'] 			= $this->Session->read('imagenModelo');
			}else{
				unset($this->request->data['Product']['img5']);
			}

			if ($this->request->data['Product']['manual_1']['name'] != '') {
				$imagen 											= $this->loadDocumentPdf($this->request->data['Product']['manual_1'],'products');
				$this->request->data['Product']['manual_1'] 			= $this->Session->read('documentoModelo');
			}else{
				unset($this->request->data['Product']['manual_1']);
			}

			if ($this->request->data['Product']['manual_2']['name'] != '') {
				$imagen 											= $this->loadDocumentPdf($this->request->data['Product']['manual_2'],'products');
				$this->request->data['Product']['manual_2'] 			= $this->Session->read('documentoModelo');
			}else{
				unset($this->request->data['Product']['manual_2']);
			}

			if ($this->request->data['Product']['manual_3']['name'] != '') {
				$imagen 											= $this->loadDocumentPdf($this->request->data['Product']['manual_3'],'products');
				$this->request->data['Product']['manual_3'] 		= $this->Session->read('documentoModelo');
			}else{
				unset($this->request->data['Product']['manual_3']);
			} 

			if ($imagen == 1) {
				if ($this->Product->save($this->request->data)) {
					$this->Session->setFlash(__('El imágenes guardadas correctamente'),'Flash/success');
				}else {
					$this->Session->setFlash('El producto no se ha guardado, por favor inténtalo más tarde','Flash/error');
				}
				return $this->redirect(array('action' => 'caracteristicas', $this->encryptString($id)));
			}

		}
		$this->set("datos",$datos);
	}

	public function updateActualRequest($brand_id, $new_brand, $product_id){
		$this->loadModel('ImportRequest');

        $importActive = $this->ImportRequest->findByBrandIdAndStateAndType($brand_id,1,1);


        if (!empty($importActive)) {
        	$details = $this->ImportRequest->ImportRequestsDetail->findAllByImportRequestIdAndState($importActive["ImportRequest"]["id"],1);
        	


        	foreach ($details as $keyDetail => $valueDetail) {
        		$products = $valueDetail["Product"];
        		if (count($products) == 1 && $products[0]["id"] == $product_id ) {
        			$requestInfoId 		= $this->ImportRequest->getOrSaveRequest($new_brand,$importActive["ImportRequest"]["internacional"],$importActive["ImportRequest"]["type"]);
        			$valueDetail["ImportRequestsDetail"]["import_request_id"] = $requestInfoId;

        			$this->ImportRequest->ImportRequestsDetail->save($valueDetail["ImportRequestsDetail"]);
        		}else{

        			foreach ($products as $key => $value) {
        				$requestInfoId 		= $this->ImportRequest->getOrSaveRequest($new_brand,$importActive["ImportRequest"]["internacional"],$importActive["ImportRequest"]["type"]);
        				$copiaDetail        = $valueDetail;
        				unset($copiaDetail["ImportRequestsDetail"]["id"]);
        				$copiaDetail["ImportRequestsDetail"]["import_request_id"] = $requestInfoId;
        				$this->ImportRequest->ImportRequestsDetail->create();
        				$this->ImportRequest->ImportRequestsDetail->save($copiaDetail["ImportRequestsDetail"]);

        				$value["ImportRequestsDetailsProduct"]["import_requests_detail_id"] = $this->ImportRequest->ImportRequestsDetail->id;
        				$this->ImportRequest->ImportRequestsDetail->ImportRequestsDetailsProduct->save($value);
        			}

        		}
        	}
        }
	}

	public function edit($id = null,$unlokProducts = null, $reorder = null) {
		$rolesPermitidos = $this->validateEditProducts();
		$referencias     = $this->getListReferences();
		if(!$rolesPermitidos){
			$this->Session->setFlash("No tienes permitido editar productos.",'Flash/error');
			$this->redirect(array("action" => "index"));
		}

		if (!$this->Product->exists($id)) {
			throw new NotFoundException('El producto esta invalido');
		}
		$marca 					= $this->Product->Brand->find("list",["conditions2"=>["Brand.brand_id" => 0]]);
		$datos 					= $this->Product->get_data($id);
		if ($this->request->is(array('post', 'put'))) {
			$exist = $this->Product->exist_product(trim($this->request->data['Product']['part_number']),$id);
			if($exist == 0){
				$this->Session->setFlash('La referencia ingresada ya existe, por favor valida','Flash/error');
			}else{
				if($this->request->data["Product"]["brand_id"] === "1"){
					$this->Session->setFlash("Debe modificarse la marca, no se permite ningún producto con la marca N/A",'Flash/error');
					return $this->redirect(array('action' => 'edit',$id));
				}
				$imagen 			= 1;
				unset($this->request->data['Product']['img']);

				if ($imagen == 1) {
					$this->request->data['Product']['list_price_usd'] 		= $this->replaceText($this->request->data['Product']['list_price_usd'],".", "");
					$this->request->data['Product']['list_price_usd'] 		= $this->replaceText($this->request->data['Product']['list_price_usd'],",", "");
					$this->request->data['Product']['purchase_price_cop'] 		= $this->replaceText($this->request->data['Product']['purchase_price_cop'],".", "");

					$this->request->data["Product"]["brand"]    = $this->Product->Brand->findById($this->request->data["Product"]["brand_id"])["Brand"]["name"];

					
					if(!$rolesPermitidos){
						$this->request->data["Product"]["state"] = 0;
					}

					if(!empty($this->request->data["Product"]["category_4"])){
						$this->request->data["Product"]["category_id"] = $this->request->data["Product"]["category_4"];
					}elseif(!empty($this->request->data["Product"]["category_3"])){
						$this->request->data["Product"]["category_id"] = $this->request->data["Product"]["category_3"];
					}elseif(!empty($this->request->data["Product"]["category_2"])){
						$this->request->data["Product"]["category_id"] = $this->request->data["Product"]["category_2"];
					}elseif(!empty($this->request->data["Product"]["category_1"])){
						$this->request->data["Product"]["category_id"] = $this->request->data["Product"]["category_1"];
					}else{
						$this->request->data["Product"]["category_id"] = 1;
					}

					$this->request->data["Product"]["user_id"] = AuthComponent::user("id");

					$this->request->data['Product']['part_number'] = strtoupper(trim($this->request->data['Product']['part_number']));

					if ($datos["Product"]["purchase_price_usd"] != $this->request->data["Product"]["purchase_price_usd"]) {
						$this->loadModel("Cost");
		    			$this->Cost->create();
		    			$this->Cost->save(["Cost" => ["user_id"=>AuthComponent::user("id"),"purchase_price_usd" => $this->request->data["Product"]["purchase_price_usd"], "product_id" => $id, "pre_purchase_price_usd" => $datos["Product"]["purchase_price_usd"]  ] ]);
		    			$this->request->data["Product"]["last_change"] = date("Y-m-d H:i:s");
					}

					$actualBrand = $this->Product->field("brand_id",["id" => $id]);


					if ($this->Product->save($this->request->data)) {
						$this->saveDataLogsUser(3,'Product',$this->request->data['Product']['id']);

						if ($actualBrand != $this->request->data["Product"]["brand_id"]) {
							$this->updateActualRequest($actualBrand, $this->request->data["Product"]["brand_id"], $id);
						}

						$this->Session->setFlash('La información se ha actualizado', 'Flash/success');

						$action = "index";
						if(!is_null($reorder)){
							$action ="products_rotation";
						}else{
							if(!is_null($unlokProducts)){
								$action ="unlock_products";
							}
						}
						

						if ($this->request->data["Product"]["normal"] == "2" && !empty($this->request->data["Composition"]["product_id"])) 
						{
							$this->loadModel("Composition");
							$this->Composition->deleteAll(array('Composition.principal' => $id), false);

							foreach ($this->request->data["Composition"]["product_id"] as $key => $value) {
								$composition = ["Composition" => ["principal"=>$id,"product_id" => $value, ] ];
								$this->Composition->create();
								$this->Composition->save($composition);
							}
						}

						$optionsRedirect = array('action' => $action);

						if (!is_null($reorder)) {
							$optionsRedirect["?"] = [ "brand" => $this->request->data["Product"]["brand_id"] ];
						}

						return $this->redirect($optionsRedirect);
					} else {
						$this->Session->setFlash('La información no se ha actualizado', 'Flash/error');
					}
				} else {
					$this->validateImageState($imagen,true);
				}
			}			
		}else{
			$this->request->data = $datos;
		}
		$categoriesData = $this->getCategoryInfo();
		$this->getCategoriesForProduct($datos["Product"]["category_id"]);
		unset($categoriesData[0]);
		$categoriesInfoFinal = $this->getCagegoryData();
		$this->set(compact("categoriesData","categoriesInfoFinal"));
		$this->set(compact('datos','marca','categorias','referencias'));
		$this->set("action","edit");
		$this->set("id",$id);
	}

	public function form_quotation(){
		$this->layout 						= false;
		if ($this->request->is('ajax')) {
			$marca 			= $this->Product->Brand->find("list",[]);
			$referencias     = $this->getListReferences();
			$datos 			= array();
			$action 		= $this->request->data['action'];
			$categoriesData = $this->getCategoryInfo(true);
			unset($categoriesData[0]);
			$categoriesInfoFinal = $this->getCagegoryData();

			if (isset($this->request->data['product_id'])) {
				$datos = $this->Product->findByIdAndDeleted($this->request->data['product_id'],0);
				$this->getCategoriesForProduct($datos["Product"]["category_id"]);
			}else{
				unset($marca[1]);
			}
			$this->set(compact('datos','marca','action','categorias','categoriesData','categoriesInfoFinal','referencias'));
		}
	}

	public function saveFormQuotation(){
		$this->autoRender 											= false;
		if ($this->request->is('ajax')) {
			$datos['Product']['part_number']	 					= $this->request->data['Product']['part_number'];
			$datos['Product']['name'] 								= $this->request->data['Product']['name'];
			$datos['Product']['description'] 						= $this->request->data['Product']['description'];
			$datos['Product']['list_price_usd'] 					= $this->request->data['Product']['list_price_usd'];
			$this->request->data["Product"]["brand_id"]				= $this->request->data["Product"]["brand"];
			$this->request->data["Product"]["brand"]    			= $this->Product->Brand->findById($this->request->data["Product"]["brand_id"])["Brand"]["name"];
			$datos['Product']['link'] 								= $this->request->data['Product']['link'];
			$datos['Product']['list_price_usd'] 					= $this->replaceText($this->request->data['Product']['list_price_usd'],".", "");
			$datos["Product"]["brand_id"]							= $this->request->data["Product"]["brand_id"];
			$datos["Product"]["brand"]    							= $this->request->data["Product"]["brand"];
			$datos["Product"]["url_video"]    						= $this->request->data["Product"]["url_video"];

			$datos['Product']['part_number'] 						= strtoupper(trim($datos['Product']['part_number']));

			if(!empty($this->request->data["Product"]["category_4"])){
				$datos["Product"]["category_id"] = $this->request->data["Product"]["category_4"];
			}elseif(!empty($this->request->data["Product"]["category_3"])){
				$datos["Product"]["category_id"] = $this->request->data["Product"]["category_3"];
			}elseif(!empty($this->request->data["Product"]["category_2"])){
				$datos["Product"]["category_id"] = $this->request->data["Product"]["category_2"];
			}elseif(!empty($this->request->data["Product"]["category_1"])){
				$datos["Product"]["category_id"] = $this->request->data["Product"]["category_1"];
			}else{
				$datos["Product"]["category_id"] = 1;
			}

			if ($this->request->data['Product']['id'] == '') {
				$add 						= true;
				$imagen 					= $this->loadPhoto($this->request->data['Product']['img'],'products');
				$datos['Product']['img'] 	= $this->Session->read('imagenModelo');
				$exist 						= $this->Product->exist_product($this->request->data['Product']['part_number']);
				if ($exist != 0) {
					$imagen = 6;
				}

				if ($this->request->data['Product']['img2']["name"] != '' && $imagen == 1) {
					$imagen 											= $this->loadPhoto($this->request->data['Product']['img2'],'products');
					$datos['Product']['img2'] 			= $this->Session->read('imagenModelo');
				}
				if ($this->request->data['Product']['img3']["name"] != '' && $imagen == 1) {
					$imagen 											= $this->loadPhoto($this->request->data['Product']['img3'],'products');
					$datos['Product']['img3'] 			= $this->Session->read('imagenModelo');
				}
				if ($this->request->data['Product']['img4']["name"] != '' && $imagen == 1) {
					$imagen 											= $this->loadPhoto($this->request->data['Product']['img4'],'products');
					$datos['Product']['img4'] 			= $this->Session->read('imagenModelo');
				}
				if ($this->request->data['Product']['img5']["name"] != '' && $imagen == 1) {
					$imagen 											= $this->loadPhoto($this->request->data['Product']['img5'],'products');
					$datos['Product']['img5'] 			= $this->Session->read('imagenModelo');
				}

				$this->Product->create();
			} else {
				$add 							= false;
				$datos['Product']['id'] 		= $this->request->data['Product']['id'];
				if ($this->request->data['Product']['img']['name'] == '') {
					$imagen 					= 1;
				} else {
					$datosP 					= $this->Product->get_data($this->request->data['Product']['id']);
					$imagen 					= $this->loadPhoto($this->request->data['Product']['img'],'products');
					$datos['Product']['img'] 	= $this->Session->read('imagenModelo');
					if ($datosP['Product']['img'] != 'default.jpg') {
						$this->deleteImageServer(WWW_ROOT.'img/products/'.$datosP['Product']['img']);
					}
				}

				if ($this->request->data['Product']['img2']["name"] != '' && $imagen == 1) {
					$imagen 											= $this->loadPhoto($this->request->data['Product']['img2'],'products');
					$datos['Product']['img2'] 			= $this->Session->read('imagenModelo');
				}else{
					unset($datos['Product']['img2']);
				}

				if ($this->request->data['Product']['img3']["name"] != '' && $imagen == 1) {
					$imagen 							= $this->loadPhoto($this->request->data['Product']['img3'],'products');
					$datos['Product']['img3'] 			= $this->Session->read('imagenModelo');
				}else{
					unset($datos['Product']['img3']);
				}
				if ($this->request->data['Product']['img4']["name"] != '' && $imagen == 1) {
					$imagen 							= $this->loadPhoto($this->request->data['Product']['img4'],'products');
					$datos['Product']['img4'] 			= $this->Session->read('imagenModelo');
				}else{
					unset($datos['Product']['img4']);
				}

				if ($this->request->data['Product']['img5']["name"] != '' && $imagen == 1) {
					$imagen 							= $this->loadPhoto($this->request->data['Product']['img5'],'products');
					$datos['Product']['img5'] 			= $this->Session->read('imagenModelo');
				}else{
					unset($datos['Product']['img5']);
				}
			}

			$actualCost = null;
			
			if($add){
				if (!in_array(AuthComponent::user("role"), array("Logística","Gerente General","Asesor Comercial")) && AuthComponent::user("email") != "ventas2@almacendelpintor.com") {
					$datos["Product"]["purchase_price_usd"] = 0;
					$datos["Product"]["purchase_price_wo"] 	= 0;
					$datos["Product"]["purchase_price_cop"] = 0;					
					$datos["Product"]["aditional_usd"] = 0;					
					$datos["Product"]["aditional_cop"] = 0;					
				}
				$rolesPermitidos = $this->validateAddProducts();
				if(!$rolesPermitidos){
					$datos["Product"]["state"] = 0;
				}else{
					$datos["Product"]["state"] = 1;
				}
				if ($this->request->data["Product"]["purchase_price_usd"] != 0) {
	    			$datos["Product"]["last_change"] = date("Y-m-d H:i:s");
	    			$actualCost = 0;
				}		
			}else{
				if (in_array(AuthComponent::user("role"), array("Logística","Gerente General","Asesor Comercial")) || AuthComponent::user("email") == "ventas2@almacendelpintor.com" ) {
					$datos["Product"]["purchase_price_usd"] = $this->request->data["Product"]["purchase_price_usd"];
					$datos["Product"]["purchase_price_cop"] = $this->request->data["Product"]["purchase_price_cop"];
					$datos["Product"]["aditional_usd"] = $this->request->data["Product"]["aditional_usd"];					
					$datos["Product"]["aditional_cop"] = $this->request->data["Product"]["aditional_cop"];	

					$actualBrand = $this->Product->field("brand_id",["id" => $this->request->data['Product']['id']]);
					if ($actualBrand != $this->request->data["Product"]["brand_id"]) {
						$this->updateActualRequest($actualBrand, $this->request->data["Product"]["brand_id"], $this->request->data['Product']['id']);
					}
								
				}
				$rolesPermitidos = $this->validateEditProducts();
				if(!$rolesPermitidos){
					$datos["Product"]["state"] = 0;
				}
				$actualCost = $this->Product->field("purchase_price_usd",["id" => $this->request->data['Product']['id'] ]);
				if ($this->request->data["Product"]["purchase_price_usd"] != $actualCost) {
	    			$datos["Product"]["last_change"] = date("Y-m-d H:i:s");
				}else{
					$actualCost = null;
				}
			}
			$datos["Product"]["user_id"] = AuthComponent::user("id");
			if ($imagen == 1) {
				if ($this->Product->save($datos)) {
					if ($add) {
						$id_colum = $this->Product->id;
						$this->saveDataLogsUser(2,'Product',$id_colum);
						$this->Session->delete('imagenModelo');
					} else {
						$id_colum 					= $this->request->data['Product']['id'];
						$this->saveDataLogsUser(3,'Product',$this->request->data['Product']['id']);
					}

					if ($actualCost != null) {
						if ($datos["Product"]["purchase_price_usd"] != $actualCost) {
							$this->loadModel("Cost");
			    			$this->Cost->create();
			    			$this->Cost->save(["Cost" => ["user_id"=>AuthComponent::user("id"),"purchase_price_usd" => $this->request->data["Product"]["purchase_price_usd"], "product_id" => $id_colum, "pre_purchase_price_usd" => $actualCost  ] ]);
			    			$this->request->data["Product"]["last_change"] = date("Y-m-d H:i:s");
						}
					}

					return $id_colum;
				} else {
					return 5;
				}
			} else {
				return $imagen;
			}
		}
	}

	public function get_product_tr(){
		$this->layout = false;
		$this->Product->recursive = -1;
		$product = $this->Product->findById($this->request->data["id"]);
		$this->set("product",$product);
	}

	public function paintData(){
		$this->autoRender 									= false;
		if ($this->request->is('ajax')) {
			$categories = [];

			if (AuthComponent::user("role") == "Asesor Externo") {
				$this->loadModel("CategoriesUser");
				$categories = $this->CategoriesUser->find("list",["fields"=>["category_id","category_id"], "conditions" => ["user_id" => AuthComponent::user("id") ] ]);
				if (!empty($categories)) {
					$categories = array_values($categories);
				}
			}

			$datosReferencia 								= $this->Product->get_referencia($categories);
			$datos 											= array();
			if(isset($this->request->data["remarketing"])){
				$datos =  Set::classicExtract($datosReferencia,"{n}.Product");

				if (isset($this->request->data["referencias"])) {
					$copyData 	= $datos;
					$datos 		= [];

					foreach ($copyData as $key => $value) {
						if (!in_array($value["part_number"], $this->request->data["referencias"])) {
							$datos[] = $value;
						}
					}
				}

				if (isset($this->request->data["products_ids"])) {
					$copyData 	= $datos;
					$datos 		= [];

					foreach ($copyData as $key => $value) {
						if (!in_array($value["id"], $this->request->data["products_ids"])) {
							$datos[] = $value;
						}
					}
				}

			}else{				
				for ($i=0; $i < count($datosReferencia); $i++) { 
					$datos['name'][$i] 							=	$datosReferencia[$i]['Product']['name'];
					$datos['numero_parte'][$i] 					=	$datosReferencia[$i]['Product']['part_number'];
					$datos['brand_id'][$i] 						=	$datosReferencia[$i]['Product']['brand_id'];
				}
			}
			return json_encode($datos);
		}
	}

	public function paintDataTwo(){
		$this->autoRender 									= false;
		if ($this->request->is('ajax')) {
			$this->Product->recursive = -1;
			$conditions = array("Product.state" => 1, "Product.deleted" => 0);

			$datosReferencia = $this->Product->find("all",compact("conditions"));
			$datos = Set::classicExtract($datosReferencia,"{n}.Product");
			$productos = array();
			if(!empty($datos) ){
				$this->loadModel("Inventory");
				foreach ($datos as $key => $value) {
					if (isset($this->request->data["tienda"])) {
						$productos[] = $value;
					}elseif((isset($this->request->data["type_move"]) && in_array($this->request->data["type_move"], ["RM","TR"] ) ) ){
						if($value["stock"] > 0){
							$productos[] = $value;
						}
					}else{
						$productos[] = $value;						
					}
					
				}
			}
			return json_encode($productos);
		}
	}

	private function getProductsLock($productId = null){
		$this->loadModel("ProductsLock");
		$this->ProductsLock->recursive = -1;
		if(is_null($productId)){
			$locks 			= $this->ProductsLock->findAllByState(1);
		}else{
			$locks 			= $this->ProductsLock->findAllByStateAndProductId(1,$productId);
		}
		$newDataLocks 	= array();
		if(!empty($locks)){
			$locks 			= Set::classicExtract($locks,"{n}.ProductsLock");
			foreach ($locks as $key => $value) {
				if (!array_key_exists($value["product_id"], $newDataLocks)) {
					$newDataLocks[$value["product_id"]] = $value;
				}else{
					$newDataLocks[$value["product_id"]]["quantity"]+= $value["quantity"];
					$newDataLocks[$value["product_id"]]["quantity_bog"]+= $value["quantity_bog"];
				}
			}
		}
		return $newDataLocks;
	}

	public function findName(){
		$this->autoRender 									= false;
		if ($this->request->is('ajax')) {

			if (isset($this->request->data["quotationView"])) {
				
				$conditions		= array('deleted' => 0,'OR' => array(
							            'Product.name' => $this->request->data['name'],
							            'Product.part_number' => $this->request->data['name']
							        )
								);
				$product = $this->Product->find('first',compact('conditions'));

				if ($product["Category"]["grupo"] == 1) {
					$this->loadModel("Garantia");					
					if ($this->validateGarantia($product["Product"]["brand_id"])) {
						return "REQUIERE_GARANTIA";
					}
				}
				return $product["Product"]["id"];
			}

			return $this->Product->find_name($this->request->data['name']);
		}
	}

	public function get_data_edit_template(){
		$this->layout 							= false;
		if ($this->request->is('ajax')) {
			$product_id 						= $this->request->data['id'];
			$arrayIdProductsViejos 				= $this->Session->read('plantillaProductos1');
			$arrayIdProducts 					= $this->Session->read('plantillaProductos');
			if(!is_array($arrayIdProducts)) {
				$arrayIdProducts				= array();
				$this->Session->write('plantillaProductos', array());
			}
			if (in_array($product_id, $arrayIdProductsViejos)) {
				$this->autoRender 						= false;
				return $product_id;
			} else {
				if (in_array($product_id, $arrayIdProducts)) {
					$this->autoRender 					= false;
					return $product_id;
				} else {
					array_push($arrayIdProducts, $product_id);
					$this->Session->write('plantillaProductos', $arrayIdProducts);
					$datos 								= $this->Product->get_data($product_id);
					$this->set(compact('datos'));
				}
			}
		}
	}

	public function get_data_add_template(){
		$this->layout 							= false;
		if ($this->request->is('ajax')) {
			$product_id 						= $this->request->data['id'];
			$arrayIdProducts 					= $this->Session->read('plantillaProductos');
			if(!is_array($arrayIdProducts)) {
				$arrayIdProducts				= array();
				$this->Session->write('plantillaProductos', array());
			}
			if (in_array($product_id, $arrayIdProducts)) {
				$this->autoRender 					= false;
				return $product_id;
			} else {
				$arrayIdProducts[$product_id] 		= $product_id;
				$this->Session->write('plantillaProductos', $arrayIdProducts);
				$datos 								= $this->Product->get_data($product_id);
				$this->set(compact('datos'));
			}
		}
	}

	public function get_data_quotation(){
		if ($this->request->is('ajax')) {
			$this->loadModel("Inventory");
			$this->loadModel("Abrasivo");
			$entrega 							= Configure::read('variables.entregaProduct');
			$product_id 						= $this->request->data['id'];
			$cantidadBloqueo 					= false;
			$cantidadCotiza						= false;
			$this->Product->unBindModel(["hasMany"=>["QuotationsProduct"]]);
			$this->updateCostCol($product_id);
			$datosProducto 						= $this->Product->findByIdAndDeleted($product_id,0);
			$header 							= isset($this->request->data["header"]) ? $this->request->data["header"] : 1;
			$type 								= isset($this->request->data["type"]) ? $this->request->data["type"] : 1;

			$totalEntMed = $this->Inventory->field("SUM(quantity)",["product_id" => $product_id, "state" => 1, "type" => 1, "warehouse" => "Medellin"]);
			$totalSalMed = $this->Inventory->field("SUM(quantity)",["product_id" => $product_id, "state" => 1, "type" => 2, "warehouse" => "Medellin"]);

			$totalEntBog = $this->Inventory->field("SUM(quantity)",["product_id" => $product_id, "state" => 1, "type" => 1, "warehouse" => "Bogota"]);
			$totalSalBog = $this->Inventory->field("SUM(quantity)",["product_id" => $product_id, "state" => 1, "type" => 2, "warehouse" => "Bogota"]);

			$totalEntTrn = $this->Inventory->field("SUM(quantity)",["product_id" => $product_id, "state" => 1, "type" => 1, "warehouse" => "Transito"]);
			$totalSalTrn = $this->Inventory->field("SUM(quantity)",["product_id" => $product_id, "state" => 1, "type" => 2, "warehouse" => "Transito"]);

			$datosProducto["Product"]["quantity"] 	 	= $totalEntMed - $totalSalMed;
			$datosProducto["Product"]["quantity_bog"] 	= $totalEntBog - $totalSalBog;
			$datosProducto["Product"]["quantity_back"] 	= $totalEntTrn - $totalSalTrn;


			$users = explode(",", $datosProducto["Product"]["users"]);

			if(isset($this->request->data["products"])){
				$totalCop = 0;
				$totalUsd = 0;
				$existeProducto = false;
				
				if($type == 1){

					foreach ($this->request->data["products"] as $key => $value) {
						if($value["id"] == $product_id){
							$existeProducto = true;
							if($value["currency"] == "cop"){
								$totalCop++;
							}else{
								$totalUsd++;
							}
						}
					}

				}

				if($existeProducto && $totalCop >= 1 && $totalUsd >= 1){
					$this->autoRender = false;
					return "TOTAL_PRODUCTS";
				}elseif ($existeProducto && $totalCop >= 1 && $totalUsd == 0) {
					$cantidadBloqueo = true;
				}elseif ($existeProducto && $totalCop == 0 && $totalUsd >= 1) {

					$dataLock = $this->getProductsLock($product_id);

					if(!empty($dataLock)){
						$quantityNoAviable = 0;
						foreach ($dataLock as $key => $value) {
							$quantityNoAviable+= ($value["quantity"]+$value["quantity_bog"]);
						}

						if($quantityNoAviable> 0 ){
							$cantidadCotiza = ($datosProducto["Product"]["quantity_bog"] + $datosProducto["Product"]["quantity"]) - $quantityNoAviable;
						}else{
							$cantidadCotiza = $datosProducto["Product"]["quantity_bog"] + $datosProducto["Product"]["quantity"];
						}

						if($cantidadCotiza <= 0){
							$cantidadCotiza = false;
						}
					}
					if(!$cantidadCotiza){
						$this->autoRender = false;
						return "TOTAL_USD";
					}
				}

			}

			if(!isset($this->request->data["other"]) && !in_array(AuthComponent::user("role"),["Gerente General","Logística"]) && $users["0"] != 'all' && !in_array(AuthComponent::user("id"), $users) ){
				$this->autoRender = false;
				return "NOT_QT";
			}elseif(!isset($this->request->data["other"]) && $datosProducto["Product"]["state"] == "0" && $type == 1){
				$this->autoRender = false;
				return "bloqueo";
			}elseif(!isset($this->request->data["other"]) && $type == 1 && ( /*trim(mb_strlen(strip_tags($datosProducto['Product']['description']))) > 500 ||*/ $datosProducto['Product']['brand'] == "N/A" || $datosProducto['Product']['brand'] == "Kebco" ) && strtolower($datosProducto["Category"]["name"]) != "servicio" ) {
				$this->autoRender 				= false;
				return 'editar';
			} else {
				$this->Session->delete('carritoProductos');
				if(!isset($this->request->data["other"])){
					$arrayIdProducts 							= $this->Session->read('carritoProductos');
					$this->Session->delete('carritoProductos');
					if(!is_array($arrayIdProducts)) {
						$arrayIdProducts						= array();
						$this->Session->write('carritoProductos', array());
					}
				}else{
					$arrayIdProducts = array();
				}		
				if (in_array($product_id, $arrayIdProducts) && $type == 1) {
					$this->autoRender 						= false;
					return $product_id;
				} else {
					$this->layout 							= false;
					$arrayIdProducts[$product_id] 			= $product_id;
					if(!isset($this->request->data["other"])){
						$this->Session->write('carritoProductos', $arrayIdProducts);
					}elseif (!isset($this->request->data["noValidate"])) {
						$entrega = array("Inmediato" => "Inmediato");
						$noChange = true;
						$this->set("noChange",$noChange);
					}

					$inventioWo 	= $this->getValuesProductsWo([$datosProducto]);
					$costos 		= $this->getCosts($inventioWo);
					$totalGeneral 	= 0;
					$precio         = 0;
					if (!empty($inventioWo[$datosProducto['Product']['part_number']])) {
						foreach ($inventioWo[$datosProducto['Product']['part_number']] as $key => $value) {
							$totalGeneral+= $value["total"];
							if ($value["utilidad"] > 1) {
								$precio = $value["precio"];
							}
						}
					}
					$productsSugestions = $this->sugestions($product_id);



					$this->set(compact('datosProducto','entrega','inventioWo','totalGeneral','precio','productsSugestions','costos',"type"));
				}
			}
			$this->loadModel("Config");

	        $config         = $this->Config->findById(1);
	        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
	        $factorImport   = $config["Config"]["factorUSA"];

	        $currency = isset($this->request->data["currency"]) ? $this->request->data["currency"] : null ;

	        $editProducts = $this->validateEditProducts();

	        $this->set(compact("trmActual","factorImport","currency","cantidadBloqueo","cantidadCotiza","header","editProducts"));
		}
	}

	public function get_data_tienda(){
		if ($this->request->is('ajax')) {
			$this->loadModel("Inventory");
			$entrega 							= Configure::read('variables.entregaProduct');
			$product_id 						= $this->request->data['id'];
			$cantidadBloqueo 					= false;
			$cantidadCotiza						= false;
			$datosProducto 						= $this->Product->findByIdAndDeleted($product_id,0);
			$arrayIdProducts 				    = array();					
			$this->layout 						= false;			
			$entrega 							= array("Inmediato" => "Inmediato");
			$noChange = true;
			$this->set("noChange",$noChange);
			

			$inventioWo 	= $this->getValuesProductsWo([$datosProducto]);
			$totalGeneral 	= 0;
			$precio         = 0;
			if (!empty($inventioWo[$datosProducto['Product']['part_number']])) {
				foreach ($inventioWo[$datosProducto['Product']['part_number']] as $key => $value) {
					$totalGeneral+= $value["total"];
					if ($value["utilidad"] > 1) {
						$precio = $value["precio"];
					}
				}
			}
			$this->set(compact('datosProducto','entrega','inventioWo','totalGeneral','precio'));
			
			
			$this->loadModel("Config");

	        $config         = $this->Config->findById(1);
	        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
	        $factorImport   = $config["Config"]["factorUSA"];

	        $currency = isset($this->request->data["currency"]) ? $this->request->data["currency"] : null ;

	        $editProducts = $this->validateEditProducts();

	        $this->set(compact("trmActual","factorImport","currency","cantidadBloqueo","cantidadCotiza","header","editProducts"));
		}
	}

	public function get_data_importacion(){
		if ($this->request->is('ajax')) {
			$product_id 						= $this->request->data['id'];
			$datosProducto 						= $this->Product->get_data($product_id);
			if($datosProducto['Product']['brand'] == "N/A") {
				$this->autoRender 				= false;
				return 'editar';
			} 
			$arrayIdProducts 					= $this->Session->read('carritoImportaciones');
			if(!is_array($arrayIdProducts)) {
				$arrayIdProducts							= array();
				$this->Session->write('carritoImportaciones', array());
			}
			if (in_array($product_id, $arrayIdProducts)) {
				$this->autoRender 						= false;
				return 0;
			} else {
				$this->layout 							= false;
				$arrayIdProducts[$product_id] 			= $product_id;
				$this->Session->write('carritoImportaciones', $arrayIdProducts);
				$editProducts = $this->validateEditProducts();

				if (!empty($datosProducto)) {
		    		$partsData				= $this->getValuesProductsWo([$datosProducto]);
		    	}else{
		    		$partsData 				= [];
		    	}

				$this->set(compact('datosProducto','editProducts','partsData'));
			}
		}
	}

	public function deleteProductCotizacion(){
		$this->autoRender 						= false;
		$product_id 							= $this->request->data['product_id'];
		$arrayIdProducts 						= $this->Session->read('carritoProductos');
		foreach ($arrayIdProducts as $value) {
			if ($arrayIdProducts[$value] == $product_id) {
				unset($arrayIdProducts[$value]);
			}
		}
		$this->Session->delete('carritoProductos');
		$this->Session->write('carritoProductos', $arrayIdProducts);
		return true;
	}

	public function deleteProductImportacion(){
		$this->autoRender 						= false;
		$product_id 							= $this->request->data['product_id'];
		$arrayIdProducts 						= $this->Session->read('carritoImportaciones');
		foreach ($arrayIdProducts as $value) {
			if ($arrayIdProducts[$value] == $product_id) {
				unset($arrayIdProducts[$value]);
			}
		}
		$this->Session->delete('carritoImportaciones');
		$this->Session->write('carritoImportaciones', $arrayIdProducts);
		return true;
	}

	public function deleteProductTemplate(){
		$this->autoRender 						= false;
		$product_id 							= $this->request->data['product_id'];
		$arrayIdProducts 						= $this->Session->read('plantillaProductos');
		foreach ($arrayIdProducts as $value) {
			if ($arrayIdProducts[$value] == $product_id) {
				unset($arrayIdProducts[$value]);
			}
		}
		$this->Session->write('plantillaProductos', $arrayIdProducts);
		return true;
	}

	public function update_cost_max($importId,$productId){
		$this->autoRender = false;
		$this->Product->save(["Product" => ["id" => $productId, "max_cost" => $this->request->data["max_cost"], "user_id" => AuthComponent::user("id"),"modified" => date("Y-m-d H:i:s") ] ]);
		$this->Session->setFlash('Costo modificado correctamente.','Flash/success');
		$this->redirect( Router::url("/",true)."Products/products_import/".$this->encryptString($importId)."#"."divData_".$productId);
	}

	public function change_quantities($importId,$productId){
		$this->autoRender = false;

		if (!empty($this->request->data["quantity"])) {

			$this->Product->ImportProduct->recursive = -1;
			$cantidad = 0;
			$cantidadBack = 0;

			$productoImport = $this->Product->ImportProduct->findByImportIdAndProductId($importId, $productId);

			$messageFlash = "Cantidades modificadas correctamente.";

			if($productoImport["ImportProduct"]["state_import"] == 2){
				$messageFlash = 'Cantidades modificadas correctamente, recuerda generar la órden de compra nuevamente y enviarla al proveedor.';
			}

			$this->loadModel("ImportProductsDetail");
			foreach ($this->request->data["quantity"] as $key => $value) {
				$detalle = $this->ImportProductsDetail->findById($key);
				if($detalle["ImportRequestsDetail"]["type_request"] == "3"){
					$cantidadBack+=$value;
				}
				$cantidad+=$value;
				$detalle["ImportProductsDetail"]["quantity"] = $value;
				$detalle["ImportRequestsDetail"]["quantity"] = $value;
				$this->ImportProductsDetail->save($detalle["ImportProductsDetail"]);
				$this->ImportProductsDetail->ImportRequestsDetail->save($detalle["ImportRequestsDetail"]);
			}

			$productoImport["ImportProduct"]["quantity"] = $cantidad;
			$productoImport["ImportProduct"]["quantity_back"] = $cantidadBack;
			$this->Product->ImportProduct->save($productoImport);
		}
		$this->Session->setFlash('Cantidades modificadas correctamente, recuerda generar la órden de compra y enviarla al proveedor.','Flash/success');
		$this->redirect( Router::url("/",true)."Products/products_import/".$this->encryptString($importId)."#"."divData_".$productId);
	}

	public function validate_import($product_id,$prospective_users_id,$quotation_id){
		$this->autoRender 	= false;
		$quotation_id 		= $this->desencriptarCadena($quotation_id);

		$this->loadModel("Quotation");
		$this->Quotation->QuotationsProduct->updateAll(
			["QuotationsProduct.state" => 6],
			["QuotationsProduct.quotation_id" => $quotation_id, "QuotationsProduct.product_id" => $product_id]
		);

		$url = $this->request->referer();

		if(empty($url) || is_null($url) || $url == Router::url($this->here,true)){
			$this->redirect(["controller" => "prospective_users","action"=>"import_finalizadas"]);
		}else{
			$this->redirect($url);
		}

	}

	private function getImportsExternas($ids = array()){
        if (AuthComponent::user("role") != "Asesor Externo") {
            return $ids;
        }
        $this->loadModel("ImportRequestsDetail");
        $this->ImportRequestsDetail->unBindModel(["hasAndBelongsToMany"=>["Product"]]);
        $ids = $this->ImportRequestsDetail->find("list",["fields" => ["id","id"], "conditions" => ["ImportRequestsDetail.user_id" => AuthComponent::user("id"),"ImportRequest.import_id !=" => null  ] ]);
        return $ids;
    }

	public function products_import($import_id){

		if (isset($this->request->data["modal"])) {
			$this->layout = false;
		}

		$import_id 				= $this->desencriptarCadena($import_id);
		$this->loadModel('Import');
		$datosImport 			= $this->Import->get_data($import_id);
		$importaciones 			= $this->Import->ImportProduct->products_import($import_id);
		$totalEmpresa 			= 0;

		$productsIds 			= [];
		$validState 			= 0;
		$moreItems 				= false;

		if(!empty($importaciones)){
			$this->loadModel("ImportProductsDetail");
			$this->ImportProductsDetail->unBindModel(array("belongsTo" => array("Product","Import")));
			$productsIds = Set::extract($importaciones,"{n}.ImportProduct.product_id");

			$idsDetails = $this->getImportsExternas();

			$this->loadModel("Composition");
			foreach ($importaciones as $key => $value) {
				if ($value["ImportProduct"]["state_import"] == 2) {
					$validState++;
				}

				if (!empty($idsDetails)) {
					$detalleImportaciones = $this->ImportProductsDetail->findAllByProductIdAndImportIdAndImportRequestsDetailId($value["Product"]["id"],$value["Import"]["id"],$idsDetails);					
				}else{
					$detalleImportaciones = $this->ImportProductsDetail->findAllByProductIdAndImportId($value["Product"]["id"],$value["Import"]["id"]);
				}

				if (!empty($idsDetails) && empty($detalleImportaciones)) {
					unset($importaciones[$key]);
				}else{
					$importaciones[$key]["Product"]["details"] = $detalleImportaciones;
					if ($value["Product"]["normal"] == 2) {
						$compositions = $this->Composition->findAllByPrincipal($value["Product"]["id"]);
						if (!empty($compositions)) {
							$importaciones[$key]["Product"]["compositions"] = $compositions;
						}
					}
				}
				if ($value["ImportProduct"]["state_import"] == 7) {
					$totalEmpresa++;
				}
			}

		}
		
		switch ($datosImport['Import']['state']) {
			case Configure::read('variables.importaciones.proceso'):
				$ids 				= $this->Import->ids_importaciones_solicitudes(Configure::read('variables.importaciones.proceso'));
				break;
			case Configure::read('variables.importaciones.solicitud'):
				$ids 				= $this->Import->ids_importaciones_solicitudes(Configure::read('variables.importaciones.solicitud'));
				break;
			case Configure::read('variables.importaciones.finalizadas'):
				$ids 				= $this->Import->ids_importaciones_solicitudes(Configure::read('variables.importaciones.finalizadas'));
				break;
			case Configure::read('variables.importaciones.rechazado'):
				$ids 				= $this->Import->ids_importaciones_solicitudes(Configure::read('variables.importaciones.rechazado'));
				break;
		}
		if (in_array($datosImport['Import']['id'], $ids)) {
			$solicitud 			= 0;
		} else {
			$solicitud 			= 1;
		}
		$this->loadModel("Brand");
		$this->loadModel("Config");
		$brand 			= $this->Brand->findById($datosImport["ImportRequest"]["brand_id"]);

		$config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $factorImport   = $config["Config"]["factorUSA"];

        if ($validState == count($importaciones)) {
        	$moreItems = true;
        }

        $inventioWo      = $this->getValuesProductsWo($importaciones);
		$this->set(compact("trmActual","factorImport","inventioWo"));
		$this->set(compact('importaciones','datosImport','solicitud','brand','productsIds','totalEmpresa','moreItems'));
	}

	public function add_reference(){
		$this->layout = false;
		$producto = $this->Product->find("first",["recursive" => -1, "conditions" => ["Product.id" => $this->request->data["id"] ] ]);

		$partsData = [];
    	if (!empty($producto)) {
    		$partsData				= $this->getValuesProductsWo([$producto]);
    	}

		$this->set(compact("producto",'partsData'));
	}

	

	public function editQuantityProductImport(){
		$this->autoRender 										= false;
		if ($this->request->is('ajax')) {
			$datos['ImportProduct']['id'] 						= $this->request->data['id_fila'];
			$datos['ImportProduct']['quantity'] 				= $this->request->data['cantidad'];
			$this->Product->ImportProduct->save($datos);
			return true;
		}
	}

    public function orden_montada(){
    	$this->layout 											= false;
		if ($this->request->is('ajax')) {
			$state 					 							= $this->request->data['state'];
			if ($state == Configure::read('variables.control_importacion.solicitud_importacion')) {
				$producto_import_id 							= $this->request->data['producto_import_id'];
				$this->set(compact('producto_import_id'));
			} else {
				$this->render('/FlowStages/state_invalid');
			}
		}
    }
    public function ordenMontada(){
    	$this->autoRender 										= false;
		if ($this->request->is('ajax')) {
			//Gerente General
			if (AuthComponent::user('role') == 'Logística')  {
				$datoProductoImportacion 							= $this->Product->ImportProduct->get_data($this->request->data['orden']['producto_import_id']);
				$datos['ImportProduct']['id'] 						= $this->request->data['orden']['producto_import_id'];
				$datos['ImportProduct']['numero_orden'] 			= $this->request->data['orden']['numero_orden'];
				$datos['ImportProduct']['proveedor'] 				= $this->request->data['orden']['proveedor'];
				$datos['ImportProduct']['state_import'] 			= Configure::read('variables.control_importacion.orden_montada');
				$datos['ImportProduct']['fecha_orden'] 				= date('Y-m-d');
				if ($datoProductoImportacion['ImportProduct']['quotations_products_id'] != 0) {
					$datos['QuotationsProduct']['state'] 			= 5;
					$datos['QuotationsProduct']['id'] 				= $datoProductoImportacion['ImportProduct']['quotations_products_id'];
					$this->Product->QuotationsProduct->save($datos['QuotationsProduct']);
				}
				$numero_productos 									= $this->Product->ImportProduct->count_products_import_solicitud($datoProductoImportacion['ImportProduct']['import_id']);
				if ($numero_productos == 1) {
					if ($datoProductoImportacion['ImportProduct']['quotations_products_id'] != 0) {
						$solicitud 									= 1;
						$quotation_id 								= $this->Product->QuotationsProduct->quotation_id_sale($datoProductoImportacion['ImportProduct']['quotations_products_id']);
						$prospective_id 							= $this->Product->QuotationsProduct->Quotation->find_flujoid_for_quotation_id($quotation_id);
						$codigo_cotizacion 							= $this->Product->QuotationsProduct->Quotation->codigo_for_quotation($quotation_id);
					} else {
						$solicitud 									= 0;
						$codigo_cotizacion 							= array();
						$prospective_id 							= array();
					}
					$datosImportacion 								= $this->Product->ImportProduct->Import->get_data($datoProductoImportacion['ImportProduct']['import_id']);
					$code_import 									= $datosImportacion['Import']['code_import'];
					$ids_products 									= $this->Product->ImportProduct->products_imports($datoProductoImportacion['ImportProduct']['import_id']);
					$products_data 									= $this->Product->get_data_products_imports($ids_products);
					$this->sendMailEtapa(Configure::read('variables.control_importacion.orden_montada'),$prospective_id,$code_import,$codigo_cotizacion,$products_data,$solicitud,$datosImportacion['Import']['user_id']);
				}
				$this->Product->ImportProduct->save($datos);
			} else {
                $this->Session->setFlash('Por favor valida, tu rol no tiene permiso de realizar esta acción','Flash/error');
			}
		}
    }

    public function despacho_proveedor_view(){
    	$this->layout 											= false;
		if ($this->request->is('ajax')) {
			$state 					 							= $this->request->data['state'];
			if ($state == Configure::read('variables.control_importacion.orden_montada')) {
				$producto_import_id 						= $this->request->data['producto_import_id'];
				$this->loadModel("Import");
				$this->Import->recursive = -1;
				$datosImport = $this->Import->findById( $this->Import->ImportProduct->field("import_id",["id" => $producto_import_id] ) );
				$this->set(compact('producto_import_id','datosImport'));
			} else {
				$this->render('/FlowStages/state_invalid');
			}
		}
    }
    public function despachoProveedorSave(){
    	$this->autoRender 										= false;
		if ($this->request->is('ajax')) {
		
			if (AuthComponent::user('role') == 'Logística' || AuthComponent::user('role') == 'Gerente General') {
				if ($this->request->data["product"]["all_products"] == 1) {
					$importId = $this->Product->ImportProduct->field("import_id",array("id"=>$this->request->data['product']['producto_import_id']));

					$this->Product->ImportProduct->recursive = -1;

					$importProducts = $this->Product->ImportProduct->findAllByImportId($importId);
					foreach ($importProducts as $key => $value) {
						$value['ImportProduct']['link'] 				= $this->request->data['product']['link'];
						$value['ImportProduct']['fecha_estimada'] 		= $this->request->data['product']['fecha_fin'];
						$value['ImportProduct']['state_import'] 		= Configure::read('variables.control_importacion.despacho_proveedor');
						$this->Product->ImportProduct->save($value);
					}

				}
				else{
					$datos['ImportProduct']['id'] 					= $this->request->data['product']['producto_import_id'];
					$datos['ImportProduct']['link'] 				= $this->request->data['product']['link'];
					$datos['ImportProduct']['fecha_estimada'] 		= $this->request->data['product']['fecha_fin'];
					$datos['ImportProduct']['state_import'] 		= Configure::read('variables.control_importacion.despacho_proveedor');
					$this->Product->ImportProduct->save($datos);
				}
			} else {
                $this->Session->setFlash('Por favor valida, tu rol no tiene permiso de realizar esta acción','Flash/error');
			}
		}
    }

    public function llegadaMiami(){
    	$this->autoRender 									= false;
		if ($this->request->is('ajax')) {
			if (AuthComponent::user('role') == 'Logística' || AuthComponent::user('role') == 'Gerente General') {
				if ($this->request->data["all"] == 1) {
					$importId = $this->Product->ImportProduct->field("import_id",array("id"=>$this->request->data['producto_import_id']));

					$this->Product->ImportProduct->recursive = -1;

					$importProducts = $this->Product->ImportProduct->findAllByImportId($importId);
					foreach ($importProducts as $key => $value) {
						$value['ImportProduct']['state_import'] 		= Configure::read('variables.control_importacion.llegada_miami');
						$value['ImportProduct']['fecha_miami'] 			= date('Y-m-d');
						$this->Product->ImportProduct->save($value);
					}

				}else{
					$datos['ImportProduct']['id'] 					= $this->request->data['producto_import_id'];
					$datos['ImportProduct']['state_import'] 		= Configure::read('variables.control_importacion.llegada_miami');
					$datos['ImportProduct']['fecha_miami'] 			= date('Y-m-d');
					$this->Product->ImportProduct->save($datos);
				}
				
			} else {
                $this->Session->setFlash('Por favor valida, tu rol no tiene permiso de realizar esta acción','Flash/error');
			}
		}
    }

    public function amerimpex(){
    	$this->layout 											= false;
		if ($this->request->is('ajax')) {
			$state 					 							= $this->request->data['state'];
			if ($state == Configure::read('variables.control_importacion.llegada_miami')) {
				$producto_import_id 							= $this->request->data['producto_import_id'];
				$this->set(compact('producto_import_id'));
			} else {
				$this->render('/FlowStages/state_invalid');
			}
		}
    }
    public function amerimpexSave(){
    	$this->autoRender 									= false;
		if ($this->request->is('ajax')) {
			if (AuthComponent::user('role') == 'Logística' || AuthComponent::user('role') == 'Gerente General') {
				if ($this->request->data["product"]["all_products"] == 1) {
					$importId = $this->Product->ImportProduct->field("import_id",array("id"=>$this->request->data['product']['producto_import_id']));

					$this->Product->ImportProduct->recursive = -1;

					$importProducts = $this->Product->ImportProduct->findAllByImportId($importId);
					foreach ($importProducts as $key => $value) {
						$value['ImportProduct']['numero_guia'] 			= $this->request->data['product']['numero_guia'];
						$value['ImportProduct']['transportadora'] 		= $this->request->data['product']['transportadora'];
						$value['ImportProduct']['state_import'] 		= Configure::read('variables.control_importacion.despacho_amerimpex');
						$this->Product->ImportProduct->save($value);
					}

				}else{
					$datos['ImportProduct']['id'] 					= $this->request->data['product']['producto_import_id'];
					$datos['ImportProduct']['numero_guia'] 			= $this->request->data['product']['numero_guia'];
					$datos['ImportProduct']['transportadora'] 		= $this->request->data['product']['transportadora'];
					$datos['ImportProduct']['state_import'] 		= Configure::read('variables.control_importacion.despacho_amerimpex');
					$this->Product->ImportProduct->save($datos);
				}
				
			} else {
                $this->Session->setFlash('Por favor valida, tu rol no tiene permiso de realizar esta acción','Flash/error');
			}
		}
    }

    public function nacionalizacion(){
    	$this->autoRender 										= false;
		if ($this->request->is('ajax')) {
			if (AuthComponent::user('role') == 'Logística' || AuthComponent::user('role') == 'Gerente General') {
				if ($this->request->data["all"] == 1) {
					$importId = $this->Product->ImportProduct->field("import_id",array("id"=>$this->request->data['producto_import_id']));

					$this->Product->ImportProduct->recursive = -1;

					$importProducts = $this->Product->ImportProduct->findAllByImportId($importId);
					foreach ($importProducts as $key => $value) {
						$value['ImportProduct']['fecha_nacionalizacion'] 	= date('Y-m-d');
						$value['ImportProduct']['state_import'] 			= Configure::read('variables.control_importacion.nacionalizacion');
						$this->Product->ImportProduct->save($value);
					}

				}else{
					$datos['ImportProduct']['id'] 						= $this->request->data['producto_import_id'];
					$datos['ImportProduct']['fecha_nacionalizacion'] 	= date('Y-m-d');
					$datos['ImportProduct']['state_import'] 			= Configure::read('variables.control_importacion.nacionalizacion');
					$this->Product->ImportProduct->save($datos);
				}
			} else {
                $this->Session->setFlash('Por favor valida, tu rol no tiene permiso de realizar esta acción','Flash/error');
			}
		}
    }


    private function getQuotationId($productos, $productoBuscar){
    	$productoFinal 	= null;
    	$estados 		= array("2","5","6");
    	foreach ($productos as $key => $value) {
    		if($value["QuotationsProduct"]["product_id"] == $productoBuscar && in_array($value["QuotationsProduct"]["state"], $estados)){
    			$productoFinal = $value["QuotationsProduct"]["id"];
    			break;
    		}
    	}
    	return $productoFinal;
    }

    public function productoEmpresaParts(){
    	$this->autoRender = false;

    	if ($this->request->is('ajax')) {
			if (AuthComponent::user('role') == 'Logística' || AuthComponent::user('role') == 'Gerente General') {

				$envioMensajeOption = $this->request->data["envio_mensaje"];

				$this->loadModel("ImportProductsDetail");
				$this->loadModel("ImportProduct");
				$this->loadModel("ProspectiveUser");

				$importProduct = $this->ImportProduct->findById($this->request->data["product_import"]);
				$internacional = $importProduct["Import"]["internacional"];

				$total = intval($importProduct["ImportProduct"]["quantity_final"]); 
				$totalLlega = 0;

				if(isset($this->request->data["cantidad_flujo"])){
					foreach ($this->request->data["cantidad_flujo"] as $idDetail => $value) {
						$total+= $value;
						$totalLlega+=$value;

						// $this->ImportProductsDetail->recursive = -1;
						$detail = $this->ImportProductsDetail->findById($idDetail);
						$detail["ImportProductsDetail"]["quantity_final"]+=$value;

						$flujo  = $detail["ImportProductsDetail"]["flujo"];

						$id_etapa_cotizado 			= $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($flujo);
						$datosFlowstage 			= $this->ProspectiveUser->FlowStage->get_data($id_etapa_cotizado);

						$importProduct["ImportProduct"]["quantity_final"] += $value;

						if (is_numeric($datosFlowstage['FlowStage']['document']) ) {

							$productoCotizado 	= $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->findByQuotationIdAndProductIdAndState($datosFlowstage['FlowStage']['document'],$detail["ImportProductsDetail"]["product_id"],[2,5]);

							if(!empty($productoCotizado) && $value != 0){
								// Validar si es la cantidad solicitada, cambiar estado y enviar correo
								if($importProduct["ImportProduct"]["quantity_final"] >= $productoCotizado["QuotationsProduct"]["quantity"]){
									$productoCotizado["QuotationsProduct"]["state"] 	= 6;
									$solicitud 											= 0;
									$ids_products 										= [$detail["ImportProductsDetail"]["product_id"]];
									$products_data 										= $this->Product->get_data_products_imports($ids_products);
									$code_import 										= $importProduct['Import']['code_import'];	
									$codigo_cotizacion 									= $this->Product->QuotationsProduct->Quotation->codigo_for_quotation($datosFlowstage['FlowStage']['document']);

									if($envioMensajeOption == 1){
										$this->sendMailEtapa(Configure::read('variables.control_importacion.producto_empresa'),$flujo,$code_import,$codigo_cotizacion,$products_data,$solicitud,$detail["ImportRequestsDetail"]["user_id"],$productoCotizado["QuotationsProduct"]["price"],$productoCotizado["QuotationsProduct"]["currency"]);
									}
									
								}else{
									$productoCotizado["QuotationsProduct"]["state"] = 5;
								}

								if($envioMensajeOption == 1){
									$solicitud = 1;
									$this->sendMailEtapa(Configure::read('variables.control_importacion.producto_empresa'),$flujo,$code_import,$codigo_cotizacion,$products_data,$solicitud,$detail["ImportRequestsDetail"]["user_id"],$productoCotizado["QuotationsProduct"]["price"],$productoCotizado["QuotationsProduct"]["currency"]);
								}

								$this->Product->QuotationsProduct->save($productoCotizado['QuotationsProduct']);
								if ($internacional == 0) {
									$this->updateInventoryProductFlujo($importProduct['ImportProduct'],$value,$flujo);
								}									
							}else{
								$productoCotizado["QuotationsProduct"]["state"] 	= 6;
								$this->Product->QuotationsProduct->save($productoCotizado['QuotationsProduct']);
							}
							$this->ImportProductsDetail->save($detail["ImportProductsDetail"]);		
							
						}
					}
				}


				if(isset($this->request->data["cantidat_transito"])){
					foreach ($this->request->data["cantidat_transito"] as $idDetail => $value) {
						$total+= $value;
						$totalLlega+=$value;
						if($value > 0){
							$this->ImportProductsDetail->recursive = -1;
							$detail = $this->ImportProductsDetail->findById($idDetail);
							$detail["ImportProductsDetail"]["quantity_final"]+=$value;
							$importProduct["ImportProduct"]["quantity_final"] += $value;
							$importProduct["ImportProduct"]["quantity_back_total"] += $value;
							$this->ImportProductsDetail->save($detail);
							
							if ($importProduct["Product"]["normal"] == 2) {

								$this->loadModel("Composition");
								$compositions = $this->Composition->find("list",["conditions" => ["principal" => $idProduct  ], "fields" => ["product_id","product_id"] ]);
								$impoProduct = $importProduct["ImportProduct"];

								foreach ($compositions as $keyComp => $valueComp) {
									$impoProduct["product_id"] = $valueComp;
									$this->updateInventoryBack($impoProduct,$value);
								}

							}else{
								$this->updateInventoryBack($importProduct["ImportProduct"],$value);
							}

						}else{
							$importProduct["ImportProduct"]["state_import"] 				= Configure::read('variables.control_importacion.producto_empresa');
							$importProduct['ImportProduct']['fecha_producto_empresa'] 		= date('Y-m-d');
						}
					}
				}
				
				if(isset($this->request->data["cantidad_interna"])){
					foreach ($this->request->data["cantidad_interna"] as $idDetail => $value) {
						$total+= $value;
						$totalLlega+=$value;
						if($value > 0){							
							$this->ImportProductsDetail->recursive = -1;
							$detail = $this->ImportProductsDetail->findById($idDetail);
							$detail["ImportProductsDetail"]["quantity_final"]+=$value;
							$importProduct["ImportProduct"]["quantity_final"]+= $value;
							$importProduct["ImportProduct"]["quantity_back_total"] += $value;
							// Guardar detalle;
							$this->ImportProductsDetail->save($detail);
							// Actualizar inventario
							if ($importProduct["Product"]["normal"] == 2) {
								
								$this->loadModel("Composition");
								$compositions = $this->Composition->find("list",["conditions" => ["principal" => $idProduct  ], "fields" => ["product_id","product_id"] ]);
								$impoProduct = $importProduct["ImportProduct"];

								foreach ($compositions as $keyComp => $valueComp) {
									$impoProduct["product_id"] = $valueComp;
									$this->updateInventoryBack($impoProduct,$value);
								}

							}else{
								$this->updateInventoryBack($importProduct["ImportProduct"],$value);
							}
							// $this->updateInventoryInterno($importProduct["ImportProduct"],$value);
						}else{
							$importProduct["ImportProduct"]["state_import"] 				= Configure::read('variables.control_importacion.producto_empresa');
							$importProduct['ImportProduct']['fecha_producto_empresa'] 		= date('Y-m-d');
						}
					}
				}

				if($total >= $importProduct["ImportProduct"]["quantity"] || $totalLlega == 0 ){
					$importProduct['ImportProduct']['state_import'] 				= Configure::read('variables.control_importacion.producto_empresa');
					$importProduct['ImportProduct']['fecha_producto_empresa'] 		= date('Y-m-d');
				}

				$this->loadModel("Inventory");
				foreach ($this->request->data["cantidad_adicional"] as $idProduct => $value) {
					if ($value == 0) {
						continue;
					}

					$productAdd = $this->Product->find("first",["conditions" => ["id" => $idProduct], "recursive" => -1 ]);

					if ($productAdd["Product"]["normal"] == 2) {
						$this->loadModel("Composition");
						$compositions = $this->Composition->find("list",["conditions" => ["principal" => $idProduct  ], "fields" => ["product_id","product_id"] ]);

						foreach ($compositions as $keyComp => $valueComp) {
							$inventory = array(
					    		"Inventory" => array(
					    			"product_id" 	=> $valueComp,
					    			"quantity" 		=> $value,
					    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
					    			"type_movement"	=> Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION"),
					    			"warehouse"		=> Configure::read("BODEGAS.Medellín"),
					    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION")).", Cantidad adicional que llega.",
					    			"user_id"		=> AuthComponent::user("id"),
					    			"import_id"		=> $importProduct["ImportProduct"]["import_id"],
					    		)
					    	);

					    	$this->Inventory->create();
					    	$this->Inventory->save($inventory);
						}

					}else{
						$inventory = array(
				    		"Inventory" => array(
				    			"product_id" 	=> $idProduct,
				    			"quantity" 		=> $value,
				    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
				    			"type_movement"	=> Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION"),
				    			"warehouse"		=> Configure::read("BODEGAS.Medellín"),
				    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION")).", Cantidad adicional que llega.",
				    			"user_id"		=> AuthComponent::user("id"),
				    			"import_id"		=> $importProduct["ImportProduct"]["import_id"],
				    		)
				    	);

				    	$this->Inventory->create();
				    	$this->Inventory->save($inventory);
					}

					
				}

				$this->Product->ImportProduct->save($importProduct["ImportProduct"]);
				// Para finalizar importación
				
				$numero_productos = $this->Product->ImportProduct->count_products_finish_cotizacion($importProduct['ImportProduct']['import_id']);

				if($numero_productos == 0 && $importProduct["ImportProduct"]["quantity"] <= $importProduct["ImportProduct"]["quantity_final"]){
					$this->Product->ImportProduct->Import->update_state_finish($importProduct['Import']['id']);
				}


			}
		}
    }


    public function productoEmpresa(){
    	$this->autoRender 	= false;
		if ($this->request->is('ajax')) {
			if (AuthComponent::user('role') == 'Logística' || AuthComponent::user('role') == 'Gerente General') {

				$this->loadModel("ProspectiveUser");

				if ($this->request->data["all"] == 1) {
					$importId = $this->Product->ImportProduct->field("import_id",array("id"=>$this->request->data['producto_import_id']));
			
					$importProducts = $this->Product->ImportProduct->findAllByImportId($importId);


					foreach ($importProducts as $key => $value) {
						$this->loadModel("ImportsProspectiveUser");

						$flujos   = $this->ProspectiveUser->find("all",array("fields" => array("import_id", "id"), "conditions" =>array("import_id" => $importId), "recursive" => -1));
						$flujos2   = $this->ImportsProspectiveUser->find("all",array("fields" => array("import_id", "prospective_user_id"), "conditions" =>array("import_id" => $importId), "recursive" => -1,"group" => array("import_id","prospective_user_id")));

						foreach ($flujos2 as $key2 => $value2) {
							$flujos[]["ProspectiveUser"]["id"] = $value2["ImportsProspectiveUser"]["prospective_user_id"];
						}
						
						$flujoIdParaBloqueo = 0;
						foreach ($flujos as $keyFlujo => $flujo) {

							$id_etapa_cotizado 			= $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($flujo["ProspectiveUser"]["id"]);
							$datosFlowstage 			= $this->ProspectiveUser->FlowStage->get_data($id_etapa_cotizado);
							
							if (is_numeric($datosFlowstage['FlowStage']['document'])) {
								$produtosCotizacion 	= $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datosFlowstage['FlowStage']['document']);
								$quotation_id 			= $this->getQuotationId($produtosCotizacion, $value["ImportProduct"]["product_id"]);
								if(!is_null($quotation_id)){
									$value['ImportProduct']['quotations_products_id'] = $quotation_id;
									$flujoIdParaBloqueo = $flujo["ProspectiveUser"]["id"];
									break;
								}
							}
						}

						$numero_productos = $this->Product->ImportProduct->count_products_finish_cotizacion($value['ImportProduct']['import_id']);

						if ($value['ImportProduct']['quotations_products_id'] != 0) {
							$datos = array();
							$datos['QuotationsProduct']['state'] 					= 6;
							$datos['QuotationsProduct']['id'] 						= $value['ImportProduct']['quotations_products_id'];
							$this->Product->QuotationsProduct->save($datos['QuotationsProduct']);
						}

						if ($numero_productos == 1) {
							$quotation_id  = null;
							$datosImportacion	 								= $this->Product->ImportProduct->Import->get_data($value['ImportProduct']['import_id']);
							if ($value['ImportProduct']['quotations_products_id'] != 0) {
								$solicitud 										= 0;
								$quotation_id 									= $this->Product->QuotationsProduct->quotation_id_sale($value['ImportProduct']['quotations_products_id']);
								$prospective_id 								= $this->Product->QuotationsProduct->Quotation->find_flujoid_for_quotation_id($quotation_id);
								$codigo_cotizacion 								= $this->Product->QuotationsProduct->Quotation->codigo_for_quotation($quotation_id);
							} 

							if($quotation_id != null){

								$ids_products 										= $this->Product->QuotationsProduct->products_import_quotation($quotation_id);
								$products_data 										= $this->Product->get_data_products_imports($ids_products);
								$code_import 										= $datosImportacion['Import']['code_import'];
								$this->Product->ImportProduct->Import->update_state_finish($datosImportacion['Import']['id']);
								$this->sendMailEtapa(Configure::read('variables.control_importacion.producto_empresa'),$prospective_id,$code_import,$codigo_cotizacion,$products_data,$solicitud,$datosImportacion['Import']['user_id']);
							}							
						}

						$value['ImportProduct']['state_import'] 				= Configure::read('variables.control_importacion.producto_empresa');
						$value['ImportProduct']['fecha_producto_empresa'] 		= date('Y-m-d');

						if(is_null($value["ImportProduct"]["fecha_orden"]) || $value["ImportProduct"]["fecha_orden"] == '0000-00-00'){
							$value['ImportProduct']['fecha_orden'] 		= date('Y-m-d');
						}
						if(is_null($value["ImportProduct"]["fecha_estimada"]) || $value["ImportProduct"]["fecha_estimada"] == '0000-00-00'){
							$value['ImportProduct']['fecha_estimada'] 		= date('Y-m-d');
						}
						if(is_null($value["ImportProduct"]["fecha_miami"]) || $value["ImportProduct"]["fecha_miami"] == '0000-00-00'){
							$value['ImportProduct']['fecha_miami'] 		= date('Y-m-d');
						}
						if(is_null($value["ImportProduct"]["fecha_nacionalizacion"]) || $value["ImportProduct"]["fecha_nacionalizacion"] == '0000-00-00'){
							$value['ImportProduct']['fecha_nacionalizacion'] 		= date('Y-m-d');
						}
						$this->Product->ImportProduct->save($value["ImportProduct"]);
						$this->updateInventoryProduct($value['ImportProduct'],$flujoIdParaBloqueo);

					}

				}
				
				else{
					$this->loadModel("ImportsProspectiveUser");
					$datoProductoImportacion 								= $this->Product->ImportProduct->get_data($this->request->data['producto_import_id']);
					$importId = $this->Product->ImportProduct->field("import_id",array("id"=>$this->request->data['producto_import_id']));
					$flujos   = $this->ProspectiveUser->find("all",array("fields" => array("import_id", "id"), "conditions" =>array("import_id" => $importId), "recursive" => -1));
					$flujos2   = $this->ImportsProspectiveUser->find("all",array("fields" => array("import_id", "prospective_user_id"), "conditions" =>array("import_id" => $importId), "recursive" => -1,"group" => array("import_id","prospective_user_id")));

					foreach ($flujos2 as $key2 => $value2) {
						$flujos[]["ProspectiveUser"]["id"] = $value2["ImportsProspectiveUser"]["prospective_user_id"];
					}

					$flujoIdParaBloqueo = 0;
					foreach ($flujos as $keyFlujo => $flujo) {

						$id_etapa_cotizado 			= $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($flujo["ProspectiveUser"]["id"]);
						$datosFlowstage 			= $this->ProspectiveUser->FlowStage->get_data($id_etapa_cotizado);
					
						
						if (is_numeric($datosFlowstage['FlowStage']['document'])) {
							$produtosCotizacion 	= $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datosFlowstage['FlowStage']['document']);
							$quotation_id 			= $this->getQuotationId($produtosCotizacion, $datoProductoImportacion["ImportProduct"]["product_id"]);
							if(!is_null($quotation_id)){
								$datoProductoImportacion['ImportProduct']['quotations_products_id'] = $quotation_id;
								$flujoIdParaBloqueo = $flujo["ProspectiveUser"]["id"];
								break;
							}
						}
					}
					$this->updateInventoryProduct($datoProductoImportacion['ImportProduct'],$flujoIdParaBloqueo);

					$numero_productos 										= $this->Product->ImportProduct->count_products_finish_cotizacion($datoProductoImportacion['ImportProduct']['import_id']);

					$datos['ImportProduct']['id'] 							= $this->request->data['producto_import_id'];
					$datos['ImportProduct']['state_import'] 				= Configure::read('variables.control_importacion.producto_empresa');
					$datos['ImportProduct']['fecha_producto_empresa'] 		= date('Y-m-d');


					if ($datoProductoImportacion['ImportProduct']['quotations_products_id'] != 0) {
						$datos['QuotationsProduct']['state'] 					= 6;
						$datos['QuotationsProduct']['id'] 						= $datoProductoImportacion['ImportProduct']['quotations_products_id'];
						$this->Product->QuotationsProduct->save($datos['QuotationsProduct']);
					}
					if ($numero_productos == 1) {
						$datosImportacion	 								= $this->Product->ImportProduct->Import->get_data($datoProductoImportacion['ImportProduct']['import_id']);
						if ($datoProductoImportacion['ImportProduct']['quotations_products_id'] != 0) {
							$solicitud 										= 0;
							$quotation_id 									= $this->Product->QuotationsProduct->quotation_id_sale($datoProductoImportacion['ImportProduct']['quotations_products_id']);
							$prospective_id 								= $this->Product->QuotationsProduct->Quotation->find_flujoid_for_quotation_id($quotation_id);
							$codigo_cotizacion 								= $this->Product->QuotationsProduct->Quotation->codigo_for_quotation($quotation_id);
						} else {
							$solicitud 										= 1;
							$codigo_cotizacion 								= array();
							$prospective_id 								= array();
						}
						$ids_products 										= $this->Product->QuotationsProduct->products_import_quotation($quotation_id);
						$products_data 										= $this->Product->get_data_products_imports($ids_products);
						$code_import 										= $datosImportacion['Import']['code_import'];
						$this->Product->ImportProduct->Import->update_state_finish($datosImportacion['Import']['id']);
						$this->sendMailEtapa(Configure::read('variables.control_importacion.producto_empresa'),$prospective_id,$code_import,$codigo_cotizacion,$products_data,$solicitud,$datosImportacion['Import']['user_id']);
					}
					$this->Product->ImportProduct->save($datos);
					$this->updateInventoryProduct($datoProductoImportacion['ImportProduct'],$flujoIdParaBloqueo);
				}

				$importId = $this->Product->ImportProduct->field("import_id",array("id"=>$this->request->data['producto_import_id']));
				$importProductsFinal = $this->Product->ImportProduct->findAllByImportId($importId);
				$initial = 0;
				$importer = 0;
				foreach ($importProductsFinal as $key => $value) {
					if($value['ImportProduct']['state_import'] == Configure::read('variables.control_importacion.producto_empresa')){
						$importer++;
					}else{
						$initial++;
					}
				}

				if($initial == 0 && $importer > 0){
					$this->Product->ImportProduct->Import->update_state_finish($importId);
				}

			} else {
                $this->Session->setFlash('Por favor valida, tu rol no tiene permiso de realizar esta acción','Flash/error');
			}
		}
    }

    private function updateInventoryProductFlujo($productImport, $quantity, $flujo){
    	$this->loadModel("Inventory");
    	$this->loadModel("ProductsLock");

    	$datos = array(
			"ProductsLock" => array(
				"id"				  => null,
				"product_id" 		  => $productImport["product_id"],
				"prospective_user_id" => $flujo,
				"quantity" 			  => $quantity,
				"quantity_bog"		  => 0,
				"quantity_back"		  => 0,
				"lock_date"			  => date("Y-m-d H:i:s"),
				"due_date" 			  => date("Y-m-d",strtotime("+25 day"))
			)
		);

		$this->ProductsLock->create();
		$this->ProductsLock->save($datos);

		$product 						= $this->Product->get_data($productImport["product_id"]);
    	$product["Product"]["quantity"] = intval($product["Product"]["quantity"]) + $quantity;
    	$this->Product->save($product); 

    	$inventory = array(
    		"Inventory" => array(
    			"product_id" 	=> $productImport["product_id"],
    			"quantity" 		=> $quantity,
    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
    			"type_movement"	=> Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION"),
    			"warehouse"		=> Configure::read("BODEGAS.Medellín"),
    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION")),
    			"user_id"		=> AuthComponent::user("id"),
    			"import_id"		=> $productImport["import_id"],
    		)
    	);

    	$this->Inventory->create();
    	$this->Inventory->save($inventory);

    }

    private function updateInventoryBack($productImport, $quantity){

    	$this->loadModel("Inventory");
    	$this->loadModel("ProductsLock");

    	$product 						= $this->Product->get_data($productImport["product_id"]);
    	$product["Product"]["quantity"] = intval($product["Product"]["quantity"]) + $quantity;

    	$totalEntTrn = $this->Inventory->field("SUM(quantity)",["product_id" => $productImport["product_id"], "state" => 1, "type" => 1, "warehouse" => "Transito"]);
		$totalSalTrn = $this->Inventory->field("SUM(quantity)",["product_id" => $productImport["product_id"], "state" => 1, "type" => 2, "warehouse" => "Transito"]);

		$totalBack 	 = $totalEntTrn - $totalSalTrn;
    	

    	if ($quantity > 0 && $totalBack >= $quantity && $productImport["quantity_back"] > 0) {
    		$product["Product"]["quantity_back"] = intval($product["Product"]["quantity_back"]) - $quantity;

    		$cantidadBloqueo = $quantity;

    		$bloqueosExistentes = $this->ProductsLock->find("all",["conditions" => ["product_id" => $productImport["product_id"], "quantity_back >" => 0, "state" => 1 ], "recursive" => -1]);

    		if (!empty($bloqueosExistentes)) {
    			foreach ($bloqueosExistentes as $key => $value) {
    				if($cantidadBloqueo != 0 && $value["ProductsLock"]["quantity_back"] <= $cantidadBloqueo){
    					$value["ProductsLock"]["quantity"]+=$value["ProductsLock"]["quantity_back"];
    					$value["ProductsLock"]["quantity_back"]-=$value["ProductsLock"]["quantity_back"];
    					$cantidadBloqueo-=$value["ProductsLock"]["quantity_back"];
    					$this->ProductsLock->save($value);
    				}
    			}
    		}

    		$inventory = array(
	    		"Inventory" => array(
	    			"product_id" 	=> $productImport["product_id"],
	    			"quantity" 		=> $quantity,
	    			"type" 			=> Configure::read("TYPES_MOVEMENT.SALIDA_INVENTARIO"),
	    			"type_movement"	=> Configure::read("INVENTORY_TYPE.SALIDA_MANUAL"),
	    			"warehouse"		=> Configure::read("BODEGAS.Transito"),
	    			"reason"		=> "Salida de bodega temporal por llegada de importación",
	    			"user_id"		=> AuthComponent::user("id"),
	    			"import_id"		=> $productImport["import_id"],
	    		)
	    	);

	    	$this->Inventory->create();
	    	$this->Inventory->save($inventory);
    	}

    	$inventory = array(
    		"Inventory" => array(
    			"product_id" 	=> $productImport["product_id"],
    			"quantity" 		=> $quantity,
    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
    			"type_movement"	=> Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION"),
    			"warehouse"		=> Configure::read("BODEGAS.Medellín"),
    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION")),
    			"user_id"		=> AuthComponent::user("id"),
    			"import_id"		=> $productImport["import_id"],
    		)
    	);

    	$this->Inventory->create();
    	$this->Inventory->save($inventory);

    	$this->Product->save($product);
    }

    private function updateInventoryInterno($productImport, $quantity){

    	$this->loadModel("Inventory");
    	$this->loadModel("ProductsLock");

    	$product 						= $this->Product->get_data($productImport["product_id"]);
    	$product["Product"]["quantity"] = intval($product["Product"]["quantity"]) + $quantity;
    	$this->Product->save($product);

    	
    	$inventory = array(
    		"Inventory" => array(
    			"product_id" 	=> $productImport["product_id"],
    			"quantity" 		=> $quantity,
    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
    			"type_movement"	=> Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION"),
    			"warehouse"		=> Configure::read("BODEGAS.Medellín"),
    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION")),
    			"user_id"		=> AuthComponent::user("id"),
    			"import_id"		=> $productImport["import_id"],
    		)
    	);

    	$this->Inventory->create();
    	$this->Inventory->save($inventory);

    	
    }

    private function updateInventoryProduct($productImport, $flujoBloqueo = null){
    	$this->loadModel("Inventory");
    	$this->loadModel("ProductsLock");

    	if(!is_null($flujoBloqueo) && $flujoBloqueo != 0){
    		$this->loadModel("ImportProductsDetail");
    		$detalleImportaciones = $this->ImportProductsDetail->findAllByImportIdAndProductId($productImport["import_id"],$productImport["product_id"]);

    		if(!empty($detalleImportaciones)){
    			
    			foreach ($detalleImportaciones as $key => $value) {
    				$datos = array(
						"ProductsLock" => array(
							"id"				  => null,
							"product_id" 		  => $productImport["product_id"],
							"prospective_user_id" => $value["ImportProductsDetail"]["flujo"],
							"quantity" 			  => $value["ImportProductsDetail"]["quantity"],
							"quantity_bog"		  => 0,
							"quantity_back"		  => 0,
							"lock_date"			  => date("Y-m-d H:i:s"),
							"due_date" 			  => date("Y-m-d",strtotime("+25 day"))
						)
					);

					$this->ProductsLock->create();
					$this->ProductsLock->save($datos);
    			}
    		}
    	}
    	$product 						= $this->Product->get_data($productImport["product_id"]);
    	$product["Product"]["quantity"] = intval($product["Product"]["quantity"]) + $productImport["quantity"];

    	if ($productImport["quantity_back"] > 0 && $product["Product"]["quantity_back"] >= $productImport["quantity_back"]) {
    		$product["Product"]["quantity_back"] = intval($product["Product"]["quantity_back"]) - $productImport["quantity_back"];

    		$cantidadBloqueo = $productImport["quantity_back"];

    		$bloqueosExistentes = $this->ProductsLock->find("all",["conditions" => ["product_id" => $productImport["product_id"], "quantity_back >" => 0, "state" => 1 ], "recursive" => -1]);

    		if (!empty($bloqueosExistentes)) {
    			foreach ($bloqueosExistentes as $key => $value) {
    				if($cantidadBloqueo != 0 && $value["ProductsLock"]["quantity_back"] <= $cantidadBloqueo){
    					$value["ProductsLock"]["quantity"]+=$value["ProductsLock"]["quantity_back"];
    					$value["ProductsLock"]["quantity_back"]-=$value["ProductsLock"]["quantity_back"];
    					$cantidadBloqueo-=$value["ProductsLock"]["quantity_back"];
    					$this->ProductsLock->save($value);
    				}
    			}
    		}

    		$inventory = array(
	    		"Inventory" => array(
	    			"product_id" 	=> $productImport["product_id"],
	    			"quantity" 		=> $productImport["quantity"],
	    			"type" 			=> Configure::read("TYPES_MOVEMENT.SALIDA_INVENTARIO"),
	    			"type_movement"	=> Configure::read("INVENTORY_TYPE.SALIDA_MANUAL"),
	    			"warehouse"		=> Configure::read("BODEGAS.Transito"),
	    			"reason"		=> "Salida de bodega temporal por llegada de importación",
	    			"user_id"		=> AuthComponent::user("id"),
	    			"import_id"		=> $productImport["import_id"],
	    		)
	    	);

	    	$this->Inventory->create();
	    	$this->Inventory->save($inventory);
    	}

    	$this->Product->save($product); 

    	

    	$inventory = array(
    		"Inventory" => array(
    			"product_id" 	=> $productImport["product_id"],
    			"quantity" 		=> $productImport["quantity"],
    			"type" 			=> Configure::read("TYPES_MOVEMENT.ENTRADA_INVENTARIO"),
    			"type_movement"	=> Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION"),
    			"warehouse"		=> Configure::read("BODEGAS.Medellín"),
    			"reason"		=> Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.ENTRADA_IMPORTACION")),
    			"user_id"		=> AuthComponent::user("id"),
    			"import_id"		=> $productImport["import_id"],
    		)
    	);

    	$this->Inventory->create();
    	$this->Inventory->save($inventory);

    }

    public function novedades_importacion(){
    	$this->layout 											= false;
		if ($this->request->is('ajax')) {
			$producto_import_id 								= $this->request->data['producto_import_id'];
			$novedades 											= $this->Product->ImportProduct->News->all_news_data($producto_import_id);
			$this->set(compact('novedades'));
		}
    }

    public function data_importaciones(){
    	$this->layout 											= false;
		if ($this->request->is('ajax')) {
			$producto_import_id 								= $this->request->data['producto_import_id'];
			$datos 												= $this->Product->ImportProduct->get_data($producto_import_id);
			$this->set(compact('datos'));
		}
    }

    public function save_novedad(){
    	$this->layout 											= false;
		if ($this->request->is('ajax')) {
			$producto_import_id 								= $this->request->data['producto_import_id'];
			$this->set(compact('producto_import_id'));
		}
    }

    public function saveNovedad(){
    	$this->autoRender 												= false;
		if ($this->request->is('ajax')) {
			$datos['News']['import_products_id'] 						= $this->request->data['product']['producto_import_id'];
			$datos['News']['description'] 								= $this->request->data['product']['description'];
			$this->Product->ImportProduct->News->save($datos);
		}
    }

    public function sendMailEtapa($etapa,$prospective_id,$code_import,$codigo_cotizacion,$products,$solicitud,$user_id,$costo_cotizacion = null,$currency = null){
    	$this->loadModel('ProspectiveUser');
    	$datosAsesor 				= $this->Product->ImportProduct->Import->User->get_data($user_id);
    	if ($solicitud == 1) {
    		$nombreCliente 			= '';
	    	$emails[] 				= $datosAsesor['User']['email'];
	    	// $emails[] 			= Configure::read('variables.emails_defecto');
	    	switch ($etapa) {
	    		case Configure::read('variables.control_importacion.orden_montada'):
					$options = array(
			            'to'        => $emails,
			            'template'  => 'import_in_process',
			            'subject'   => 'Importación en proceso',
			            'vars'      => array('nombreCliente' => $nombreCliente,'nombreAsesor' => $datosAsesor['User']['name'],'code_import' => $code_import,'codigoCotizacion' => $codigo_cotizacion,'products' => $products,'solicitud' => $solicitud)
			        );
	    			break;
	    		case Configure::read('variables.control_importacion.producto_empresa'):
	    			$options = array(
			            'to'        => $emails,
			            'template'  => 'imported_products',
			            'subject'   => 'Importación finalizada',
			            'vars'      => array('nombreCliente' => $datosCliente['name'],'nombreAsesor' => $datosAsesor['User']['name'],'code_import' => $code_import,'codigoCotizacion' => $codigo_cotizacion,'products' => $products,'solicitud' => $solicitud,"costo"=>$costo_cotizacion, "currency" => $currency)
			        );
	    			break;
	    	}
    	} else {
	    	$datosProspective 		= $this->ProspectiveUser->get_data($prospective_id);
	    	$emails[] 				= $datosAsesor['User']['email'];
	    	$datosCliente 			= $this->findDataCliente($datosProspective['ProspectiveUser']['contacs_users_id'],$datosProspective['ProspectiveUser']['clients_natural_id']);
	    	$emails[] 				= $this->findEmailCliente($datosProspective['ProspectiveUser']['contacs_users_id'],$datosProspective['ProspectiveUser']['clients_natural_id']);
	    	// $emails[] 			= Configure::read('variables.emails_defecto');
	    	switch ($etapa) {
	    		case Configure::read('variables.control_importacion.orden_montada'):
					$options = array(
			            'to'        => $emails,
			            'template'  => 'import_in_process',
			            'subject'   => 'Importación en proceso',
			            'vars'      => array('nombreCliente' => $datosCliente['name'],'nombreAsesor' => $datosAsesor['User']['name'],'code_import' => $code_import,'codigoCotizacion' => $codigo_cotizacion,'products' => $products,'solicitud' => $solicitud)
			        );
	    			break;
	    		case Configure::read('variables.control_importacion.producto_empresa'):
	    			$options = array(
			            'to'        => $emails,
			            'template'  => 'imported_products',
			            'subject'   => 'Importación finalizada',
			            'vars'      => array('nombreCliente' => $datosCliente['name'],'nombreAsesor' => $datosAsesor['User']['name'],'code_import' => $code_import,'codigoCotizacion' => $codigo_cotizacion,'products' => $products,'solicitud' => $solicitud,"costo"=>$costo_cotizacion, "currency" => $currency)
			        );
	    			break;
	    	}
	    }
    	$this->sendMail($options);
    	return true;
    }

}
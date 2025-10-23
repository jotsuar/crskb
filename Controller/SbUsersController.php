<?php
App::uses('AppController', 'Controller');
App::uses('ConnectionManager', 'Model');

require_once '../Vendor/spreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;


set_time_limit(0);
ini_set('memory_limit', '-1');

/**
 * SbUsers Controller
 *
 * @property SbUser $SbUser
 * @property PaginatorComponent $Paginator
 */
class SbUsersController extends AppController {
	
	public $components = array('Paginator');

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('set_lineas');
    }

    public function getCategoryList($category_parents,$category_list, $category_id, $actualList = []){
    	$categoryFinal = [];

    	if(empty($actualList)){
    		$categoryFinal[] = $category_list[$category_id];
    	}else{
    		$categoryFinal = $actualList;
    	}

    	$parent = 0;


    	foreach ($category_parents as $idParent => $childrens) {
    		if(in_array($category_id, $childrens) && $idParent > 1){
    			$categoryFinal[] = $category_list[$idParent];
    			$parent = $idParent;
    			break;    			
    		}
    	}

    	if($parent > 2){

	    	foreach ($category_parents as $idParent => $childrens) {
	    		if(in_array($parent, $childrens) && $idParent > 1 ){
	    			$categoryFinal[] = $category_list[$idParent];
	    			$parent = $idParent;
	    			break;   	
	    		}
	    	}

	    	foreach ($category_parents as $idParent => $childrens) {
	    		if(in_array($parent, $childrens) && $idParent > 1 ){
	    			$categoryFinal[] = $category_list[$idParent];
	    			$parent = $idParent;
	    			break;   	
	    		}
	    	}


	    	foreach ($category_parents as $idParent => $childrens) {
	    		if(in_array($parent, $childrens) && $idParent > 1 ){
	    			$categoryFinal[] = $category_list[$idParent];
	    			$parent = $idParent;	
	    		}
	    	}
    	}

    	

    	return array_reverse($categoryFinal);

    }

    public function racing_ventas($variable){
    	$this->autoRender = false;
    	$db = ConnectionManager::getDataSource('racingrc');
    	$list = $db->rawQuery("SELECT p.id_product,p.reference AS Referencia,man.name as 'Fabricante', pl.name Producto,pl.link_rewrite as url, p.id_category_default, 
					ROUND(p.price) AS 'Precio Unitario Actual', 
					ROUND( AVG(od.product_price) ) AS 'Precio Unitario Promedio',
					ROUND(p.wholesale_price) AS 'Costo Unitario Actual', 
					ROUND(AVG(od.purchase_supplier_price)) AS 'Costo Unitario Promedio', 
					SUM(od.product_quantity) AS 'Cantidad Vendida', 
					sa.quantity AS 'Stock Actual',
					ROUND(od.product_price* od.product_quantity) AS 'Precio Final', ROUND(od.purchase_supplier_price* od.product_quantity) AS 'Costo Final', 
					ROUND( od.product_price* od.product_quantity - od.purchase_supplier_price* od.product_quantity) AS 'Utilidad Final', 
					ROUND( ( (od.product_price - od.purchase_supplier_price)/od.product_price ),2)*100 AS 'Margen' 
					FROM li1x_orders o 
					JOIN li1x_order_detail od ON o.id_order = od.id_order 
					JOIN li1x_product p ON od.product_id = p.id_product 
					LEFT JOIN li1x_product_lang pl ON pl.id_product = p.id_product 
					LEFT JOIN li1x_category_lang cl ON cl.id_category = p.id_category_default 
					LEFT JOIN li1x_stock_available sa ON sa.id_product = p.id_product 
					LEFT JOIN li1x_manufacturer man ON man.id_manufacturer = p.id_manufacturer
					WHERE o.valid = 1 AND o.current_state = 5 
					GROUP BY p.id_product;");

    	$result = $list->fetchAll(PDO::FETCH_ASSOC);


    	$category_list_query = "SELECT lc.id_category, id_parent,lcl.name FROM li1x_category lc INNER JOIN li1x_category_lang lcl on lcl.id_category = lc.id_category WHERE lc.id_category > 1";
    	$listCategory = $db->rawQuery($category_list_query);
    	$resultCategory = $listCategory->fetchAll(PDO::FETCH_ASSOC);

    	$category_list = [];
    	$category_parents = [];

    	foreach ($resultCategory as $key => $value) {
    			
    		$category_list[$value["id_category"]] = $value["name"];
    		$category_parents[$value["id_parent"]][] = $value["id_category"];

    	}

    	$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$spreadsheet->getProperties()->setCreator('RACING RC')
		        ->setLastModifiedBy('RACING RC')
		        ->setTitle('Inventario Vendido')
		        ->setSubject('Inventario Vendido')
		        ->setDescription('Inventario VendidoRACINGRC')
		        ->setKeywords('Inventario Vendido RACINGRC')
		        ->setCategory('Inventario Vendido RACINGRC');



		$spreadsheet->setActiveSheetIndex(0)
				        ->setCellValue("A1", 'Referencia')
				        ->setCellValue("B1", 'Producto')
				        ->setCellValue("C1", 'Marca')
				        ->setCellValue("D1", 'Categoría 1')
				        ->setCellValue("E1", 'Categoría 2')
				        ->setCellValue("F1", 'Categoría 3')
				        ->setCellValue("G1", 'Categoría 4')
				        ->setCellValue("H1", 'Categoría 5')
				        ->setCellValue("I1", 'Precio Unitario Actual')
				        ->setCellValue("J1", 'Precio Unitario Promedio')
				        ->setCellValue("K1", 'Costo Unitario Actual')
				        ->setCellValue("L1", 'Costo Unitario Promedio')
				        ->setCellValue("M1", 'Cantidad Vendida')
				        ->setCellValue("N1", 'Stock Actual')
				        ->setCellValue("O1", 'Precio Final')
				        ->setCellValue("P1", 'Costo Final')
				        ->setCellValue("Q1", 'Utilidad Final')
				        ->setCellValue("R1", 'Margen')
				        ->setCellValue("S1", 'LINK');

		$i = 2;


		if(!empty($result)){
				foreach ($result as $key => $value) {


    				$categorias = $this->getCategoryList($category_parents,$category_list,$value["id_category_default"]);

					$spreadsheet->setActiveSheetIndex(0)
				        ->setCellValue("A".$i, $value["Referencia"])
				        ->setCellValue("B".$i, $value["Producto"])
				        ->setCellValue("C".$i, empty($value["Fabricante"]) ? 'Sin Marca' : $value["Fabricante"] )
				        ->setCellValue("D".$i, isset($categorias[0]) ? $categorias[0] : '' )
				        ->setCellValue("E".$i, isset($categorias[1]) ? $categorias[1] : '' )
				        ->setCellValue("F".$i, isset($categorias[2]) ? $categorias[2] : '' )
				        ->setCellValue("G".$i, isset($categorias[3]) ? $categorias[3] : '' )
				        ->setCellValue("H".$i, isset($categorias[4]) ? $categorias[4] : '' )
				        ->setCellValue("I".$i, $value["Precio Unitario Actual"])
				        ->setCellValue("J".$i, $value["Precio Unitario Promedio"])
				        ->setCellValue("K".$i, $value["Costo Unitario Actual"])
				        ->setCellValue("L".$i, $value["Costo Unitario Promedio"])
				        ->setCellValue("M".$i, $value["Cantidad Vendida"])
				        ->setCellValue("N".$i, $value["Stock Actual"])
				        ->setCellValue("O".$i, $value["Precio Final"])
				        ->setCellValue("P".$i, $value["Costo Final"])
				        ->setCellValue("Q".$i, $value["Utilidad Final"])
				        ->setCellValue("R".$i, $value["Margen"])
				        ->setCellValue("S".$i, 'https://racingrc.net/'.$value["id_product"]."-".$value["url"].".html");
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


			$spreadsheet->getActiveSheet()->setTitle('Productos Vendidos RACING');
			$spreadsheet->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);
			$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());			

			$name = time();

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="ventas_racing_'.$name.'.xlsx"');
			header('Cache-Control: max-age=0');
			header('Cache-Control: max-age=1');
			header('Expires: Mon, 26 Jul 2025 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . time() . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0

			$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
			exit;				



    	// foreach ($resultCategory as $key => $value) {
    			
    	// 	$category_list[$value["id_category"]] = $value["name"];

    	// }



    }

    public function set_lineas(){
    	$this->autoRender = false;
    	$this->loadModel("SbLinea");
    	$conditions = ["response_crm !=" => null, "user_type" => "user"];

    	$lineas = $this->SbLinea->find("list",["fields"=>["user_id","user_id"]]);

    	if(!empty($lineas)){
    		$conditions["id !="] = $lineas;
    	}

    	$users = $this->SbUser->find("all",["conditions" => $conditions, "recursive" => -1 ]);
    	$lineasSave = [];

    	if(!empty($users)){
    		foreach ($users as $user_id => $value) {
    			$response_crm = json_decode($value["SbUser"]["response_crm"]);
    			if(isset($response_crm->linea) && !empty($response_crm->linea)){
    				$lineasSave[] = [
    					"user_id"=>$value["SbUser"]["id"],
    					"linea"=>$response_crm->linea,
    					"date_creation"=>$value["SbUser"]["creation_time"],
    				];
    			}
    		}
    	}
    	if(!empty($lineasSave)){
    		$this->SbLinea->saveAll($lineasSave);
    	}
    	var_dump($lineasSave);
    }

	public function index() {
		$this->SbUser->recursive = 0;
		$this->set('sbUsers', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (!$this->SbUser->exists($id)) {
			throw new NotFoundException(__('Invalid sb user'));
		}
		$options = array('conditions' => array('SbUser.' . $this->SbUser->primaryKey => $id));
		$this->set('sbUser', $this->SbUser->find('first', $options));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->SbUser->create();
			if ($this->SbUser->save($this->request->data)) {
				$this->Flash->success(__('The sb user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The sb user could not be saved. Please, try again.'));
			}
		}
	}

	public function edit($id = null) {
		if (!$this->SbUser->exists($id)) {
			throw new NotFoundException(__('Invalid sb user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SbUser->save($this->request->data)) {
				$this->Flash->success(__('The sb user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The sb user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SbUser.' . $this->SbUser->primaryKey => $id));
			$this->request->data = $this->SbUser->find('first', $options);
		}
	}

	public function delete($id = null) {
		if (!$this->SbUser->exists($id)) {
			throw new NotFoundException(__('Invalid sb user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->SbUser->delete($id)) {
			$this->Flash->success(__('The sb user has been deleted.'));
		} else {
			$this->Flash->error(__('The sb user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

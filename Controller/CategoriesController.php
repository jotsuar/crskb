<?php
App::uses('AppController', 'Controller');

require_once '../Vendor/spreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
// use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
// use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

set_time_limit(0);

/**
 * Categories Controller
 *
 * @property Category $Category
 * @property PaginatorComponent $Paginator
 */
class CategoriesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');


	public function index() {
		
		$categoriesData = $this->getCategoryInfo(false,true);
		$categories = $this->getCagegoryData(0);

		// var_dump($categoriesData);
		// die();
		
		$this->Category->recursive = -1;
		$categoriesList = $this->Category->find("list");
		$this->set(compact("categories","categoriesList","categoriesData"));
	}

	public function export($id,$time){
		$this->autoRender = false;
		$categories = $this->Category->find("all",["conditions" => ["Category.category_id" => $id],"recursive" => -1]);

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
			        ->setCellValue('A1', 'Etiqueta* (ca.label)')
			        ->setCellValue('B1', 'Tipo* (ca.type)')
			        ->setCellValue('C1', 'Descripcion (ca.description)')
			        ->setCellValue('D1', 'Parent (ca.fk_parent)');

		$i = 2;

		if(!empty($categories)){
			$defaultOne = "1";
			$defaultCero = "0";
			foreach ($categories as $key => $value) {
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $value["Category"]["name"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $defaultOne);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $value["Category"]["description"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $defaultCero);
				$i++;
			}
		}


		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

		$spreadsheet->getActiveSheet()->setTitle('Categorias');
		$spreadsheet->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$name = $id = 0 ? "Principales" : $this->Category->field("name",["id"=>$id]);

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

	

	public function view($id = null) {
		$id = $this->desencriptarCadena($id);
		if (!$this->Category->exists($id)) {
			throw new NotFoundException(__('Invalid category'));
		}
		$options 	=	 array('conditions' => array('Category.' . $this->Category->primaryKey => $id),"recursive" => -1);
		$category 	= $this->Category->find('first', $options);
		$this->set('category', $category);

		$categoriesData 	= $this->getCategoryInfo();
		$categories 		= $this->getCagegoryData($id);

		$categoriesInfo = $this->getAllIdsCategories($id);
		$conditions["Product.category_id"] = $categoriesInfo ;

		$products = $this->Category->Product->find("all",["recursive" => -1, "conditions" => $conditions]);

		
		$this->set(compact("categoriesData","categories","products"));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {

			$documento = 0;
			$imagen    = false;

			if (isset($this->request->data["Category"]["imagen"]) && isset($this->request->data["Category"]["imagen"]["name"]) && !empty($this->request->data["Category"]["imagen"]["name"]) ) {
				$documento = $this->loadPhoto($this->request->data['Category']['imagen'],'categories');
				$this->request->data["Category"]["imagen"]	= $this->Session->read('imagenModelo');
				$imagen    = true;
			}else{
				$this->request->data["Category"]["imagen"]  = null;
			}

			if ($documento != 1 && $imagen) {
				$this->Session->setFlash(__('El archivo enviado no es una imagen por favor verifique.'),"Flash/error");
			}else{
				$this->Category->create();

				if(!empty($this->request->data["Category"]["category_3"])){
					$this->request->data["Category"]["category_id"] = $this->request->data["Category"]["category_3"];
				}elseif(!empty($this->request->data["Category"]["category_2"])){
					$this->request->data["Category"]["category_id"] = $this->request->data["Category"]["category_2"];
				}elseif(!empty($this->request->data["Category"]["category_1"])){
					$this->request->data["Category"]["category_id"] = $this->request->data["Category"]["category_1"];
				}else{
					$this->request->data["Category"]["category_id"] = 0;
				}

				if ($this->Category->save($this->request->data)) {
					$this->Session->setFlash(__('La categoría fue guardada correctamente'),'Flash/success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('La categoría no fue guardada por favor, intente de nuevo por favor.'),"Flash/error");
				}
			}

			
		}

		$categories = $this->getCategoryInfo();

		$categoriesB = $this->getCagegoryData();
		$categoriesSelect = $this->getEstructure(0,$categoriesB,true);
		$categoriesInfoFinal = $this->getCagegoryData(0,[],true);
		$this->set(compact("categories","categoriesSelect","categoriesInfoFinal"));
	}

	

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		$id = $this->desencriptarCadena($id);
		if (!$this->Category->exists($id)) {
			throw new NotFoundException(__('Invalid category'));
		}
		if ($this->request->is(array('post', 'put'))) {

			$documento = 0;
			$imagen    = false;

			if (isset($this->request->data["Category"]["imagen"]) && isset($this->request->data["Category"]["imagen"]["name"]) && !empty($this->request->data["Category"]["imagen"]["name"]) ) {
				$documento = $this->loadPhoto($this->request->data['Category']['imagen'],'categories');
				$this->request->data["Category"]["imagen"]	= $this->Session->read('imagenModelo');
				$imagen    = true;
			}else{
				$this->request->data["Category"]["imagen"]  = null;
			}

			if ($documento != 1 && $imagen) {
				$this->Session->setFlash(__('El archivo enviado no es una imagen por favor verifique.'),"Flash/error");
			}else{
				if(!empty($this->request->data["Category"]["category_3"])){
					$this->request->data["Category"]["category_id"] = $this->request->data["Category"]["category_3"];
				}elseif(!empty($this->request->data["Category"]["category_2"])){
					$this->request->data["Category"]["category_id"] = $this->request->data["Category"]["category_2"];
				}elseif(!empty($this->request->data["Category"]["category_1"])){
					$this->request->data["Category"]["category_id"] = $this->request->data["Category"]["category_1"];
				}else{
					$this->request->data["Category"]["category_id"] = 0;
				}

				if($this->request->data["Category"]["margen_general"] == 1){
					$categoriesInfo = $this->getAllIdsCategories($this->request->data["Category"]["id"]);

					$fieldsUpdate   = array(
				    	'Category.margen' => $this->request->data["Category"]["margen"],
				    	'Category.margen_wo' => $this->request->data["Category"]["margen_wo"],
				    	'Category.factor' => $this->request->data["Category"]["factor"],
				    	'Category.grupo' => $this->request->data["Category"]["grupo"],
				    	'Category.show_cost' => $this->request->data["Category"]["show_cost"],
				    );

				    if ($imagen) {
				    	$fieldsUpdate["Category.imagen"] = "'".$this->request->data["Category"]["imagen"]."'";
				    }

					$this->Category->updateAll(
					    $fieldsUpdate,
					    array('Category.id' => $categoriesInfo)
					);
				}
				if ($this->Category->save($this->request->data)) {
					$this->Session->setFlash(__('La categoría fue guardada correctamente'),'Flash/success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('La categoría no fue guardada por favor, intente de nuevo por favor.'),"Flash/error");
				}
			}
		} else {
			$options = array('conditions' => array('Category.' . $this->Category->primaryKey => $id));
			$this->request->data = $this->Category->find('first', $options);
		}
		$categories 		= $this->getCategoryInfo();
		$categoriesData 		= $this->getCagegoryData($id);
		$categoriesB = $this->getCagegoryData();
		$categoriesSelect = $this->getEstructure(0,$categoriesB,true);

		$categoriesInfoFinal = $this->getCagegoryData(0,[],true);
		$this->getCategoriesForProduct($this->request->data["Category"]["category_id"]);

		$this->set(compact("categories","categoriesData","categoriesSelect","categoriesInfoFinal"));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException(__('Invalid category'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Category->delete()) {
			$this->Flash->success(__('The category has been deleted.'));
		} else {
			$this->Flash->error(__('The category could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

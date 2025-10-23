<?php
require_once '../Vendor/spreadsheet/vendor/autoload.php';
App::uses('AppController', 'Controller');
/**
 * Accounts Controller
 *
 * @property Account $Account
 * @property PaginatorComponent $Paginator
 */
class AccountsController extends AppController {


	public $components = array('Paginator');

	public function index() {
		$this->Account->recursive = 0;
		$this->set('accounts', $this->Paginator->paginate());
	}

	public function change($id) {
		$this->layout   = false;
		$account 		= $this->Account->findById($id);

		if ($this->request->is("post")) {
			if ($this->request->data["Account"]["state"] == 2) {
				$imagen 											= $this->loadPhoto($this->request->data['Account']['document'],'accounts');
				$this->request->data['Account']['document'] 		= $this->Session->read('imagenModelo');
			}
			$this->Account->save($this->request->data);
			$this->Session->setFlash('Cambio de estado correcto','Flash/success');

			$options = array(
	            'to'        => [$account["User"]["email"]],
	            'template'  => 'account_cambio',
	            'subject'   => 'Cambio en cuenta de cobro CBKEB #'.$account['Account']['id'],
	            'vars'      => array('account' => array_merge($account,$this->request->data) )
	        );

	        $this->sendMail($options);

			$this->redirect(["action"=>"informe_comisiones_externals_view","controller"=>"ProspectiveUsers",$this->encrypt($this->request->data["Account"]["id"])]);
		}

		$this->set("account",$account);
	}

	public function reject() {
		$this->autoRender = false;
		$account = $this->Account->findById($this->request->data["id"]);

		$account["Account"]["state"] = 3;
		$account["Account"]["notes"] = $this->request->data["Motivo"];
		$account["Account"]["modified"] = date("Y-m-d H:i:s");
		if ($this->Account->save($account["Account"])) {
			$this->Account->Receipt->updateAll(["Receipt.state" => 0, ["Receipt.account_id" => null ] ], ["Receipt.account_id" => $this->request->data["id"]] );
			

			$options = array(
	            'to'        => [$account["User"]["email"]],
	            'template'  => 'account_cambio',
	            'subject'   => 'Cambio en cuenta de cobro CBKEB #'.$account['Account']['id'],
	            'vars'      => array('account' => $account )
	        );

	        $this->sendMail($options);
	        $this->Session->setFlash('Rechazo correcto.','Flash/success');

		}
	}


	public function view($id = null) {
		if (!$this->Account->exists($id)) {
			throw new NotFoundException(__('Invalid account'));
		}
		$options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
		$this->set('account', $this->Account->find('first', $options));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Account->create();
			if ($this->Account->save($this->request->data)) {
				$this->Flash->success(__('The account has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The account could not be saved. Please, try again.'));
			}
		}
		$users = $this->Account->User->find('list');
		$this->set(compact('users'));
	}


	public function edit($id = null) {
		if (!$this->Account->exists($id)) {
			throw new NotFoundException(__('Invalid account'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Account->save($this->request->data)) {
				$this->Flash->success(__('The account has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The account could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
			$this->request->data = $this->Account->find('first', $options);
		}
		$users = $this->Account->User->find('list');
		$this->set(compact('users'));
	}

	public function report() {

		if ($this->request->is("post") && empty($this->request->query)) {
			$this->request->query["ini"] = $this->request->data["fechaIni"];
			$this->request->query["end"] = $this->request->data["fechaEnd"];
		}

		$this->validateDatesForReports();
		$this->validateRolePayments();
		
		$get = $this->request->query;
    	$get["date_ini"] = $get["ini"];
    	$get["date_fin"] = $get["end"];
    	$sales			 = array();
    	$comision		 = array();
    	$filter 		 = false;
    	$idsUsers 		 = $this->Account->find("list",["fields" => ["user_id","user_id"], "conditions" ]);
    	$users 			 = $this->Account->User->find("list",["conditions"=>["User.id" => $idsUsers]]);
    	$user_id 		 = "";

    	if($this->request->is("post")){

    		$ini  		= $this->request->data["fechaIni"];
    		$end 		= $this->request->data["fechaEnd"];
    		$conditions = array('date_payment >=' => $ini, 'date_payment <=' => $end, "Account.state" => 2 );

    		if (isset($this->request->data["ProspectiveUser"]["user_id"]) && !empty($this->request->data["ProspectiveUser"]["user_id"])) {
    			$conditions["Account.user_id"] = $this->request->data["ProspectiveUser"]["user_id"];
    			$user_id = $this->request->data["ProspectiveUser"]["user_id"];
    		}

    		if (isset($this->request->data["ProspectiveUser"]["state"]) && $this->request->data["ProspectiveUser"]["state"] == 1) {
    			$conditions["Account.state"] = 1;
    		}

    		$sales 		= $this->Account->find("all",compact("conditions"));

    		if($this->request->data["ProspectiveUser"]["excel"] == 1){
    			$this->export_comissions($sales);
    		}
    	}

    	$this->set(compact('sales','filter','users','user_id'));
	}


	public function delete($id = null) {
		$this->Account->id = $id;
		if (!$this->Account->exists()) {
			throw new NotFoundException(__('Invalid account'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Account->delete()) {
			$this->Flash->success(__('The account has been deleted.'));
		} else {
			$this->Flash->error(__('The account could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	private function export_comissions($sales){


    	$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$spreadsheet->getProperties()->setCreator('Kebco SAS')
			        ->setLastModifiedBy('Kebco SAS')
			        ->setTitle('Comisiones por vendedor')
			        ->setSubject('Comisiones por vendedor')
			        ->setDescription('Comisiones por vendedor')
			        ->setKeywords('Comisiones CRM Productos')
			        ->setCategory('Comisiones');

		$spreadsheet->setActiveSheetIndex(0)
			        ->setCellValue('A1', 'CÓDIGO CUENTA')
			        ->setCellValue('B1', 'USUARIO QUE SOLICITA')
			        ->setCellValue('C1', 'FECHA SOLICITUD')
			        ->setCellValue('D1', 'VALOR SOLICITUD')
			        ->setCellValue('E1', 'FECHA PAGO')
			        ->setCellValue('F1', 'VALOR PAGO')
			        ->setCellValue('G1', '# RECIBOS ASOCIADOS');

		$i = 2;
		$totalPagar = 0;

		foreach ($sales as $key => $value) {

			$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, "CBKEB #".$value["Account"]["id"] );
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, strtoupper($value['User']['name']) );
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $value['Account']['date_send'] );
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $value['Account']['initial_value']);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $value['Account']['date_payment']);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $value['Account']['value_payment']);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, count($value["Receipt"]));
			$totalPagar+=$value['Account']['value_payment'];
			$i++;
		}

		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, "Total pagado");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $totalPagar);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, "");

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		if (isset($this->request->data["ProspectiveUser"]["state"]) && $this->request->data["ProspectiveUser"]["state"] == 1) {
			$spreadsheet->getActiveSheet()->setTitle("CUENTAS SIN PAGO EXTERNOS");
			header('Content-Disposition: attachment;filename="sin_pagar_externos_kebco_'.time().'.xlsx"');
		}else{
			$spreadsheet->getActiveSheet()->setTitle("PAGOS ASESORES EXTERNOS");
			header('Content-Disposition: attachment;filename="pagos_externos_kebco_'.time().'.xlsx"');
		}
		$spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);

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
}

<?php
require_once '../Vendor/spreadsheet/vendor/autoload.php';
App::uses('AppController', 'Controller');
/**
 * Receipts Controller
 *
 * @property Receipt $Receipt
 * @property PaginatorComponent $Paginator
 */
class ReceiptsController extends AppController {


	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('automatic_receipts');
    }

	public $components = array('Paginator');

	public function automatic_receipts(){
		$this->autoRender = false;

		$this->loadModel("Recibo");
		$this->loadModel("ProspectiveUser");
		$this->loadModel("Salesinvoice");

		$lastDateReceipt = $this->Receipt->field("MAX(date_receipt)");
		$lastDateRecibo = $this->Recibo->field("MAX(fecha_gestion)");

		$deadline = $lastDateRecibo == null ? $lastDateReceipt." 00:00:00" : $lastDateRecibo;

		$recibos_wo = $this->postWoApi(["deadline" => $deadline],"receipts_details");
		// $recibos_wo = $this->postWoApi(["deadline" => '2024-04-01 00:00:00'],"receipts_details");



		if( isset($recibos_wo->receipts) && !empty($recibos_wo->receipts)){
			foreach ($recibos_wo->receipts as $key => $details) {
				$detailsCopy = $this->object_to_array($details->details);

				$credito = 0;
				$debito  = 0;
				$retencion = 0;

				$receipt_id = null;

				foreach ($detailsCopy as $keyDetail => $detail) {
					$credito += ($detail["Crédito"]);
					$debito += ($detail["Débito"]);
					if(!is_null($detail["Valor_retenido"])){
						$retencion = 1;
					}
				}

				$diferencia = $credito - $debito;

				if($detail["PrefFact"] == 'KE' || $detail["PrefFact"] == 'KEB' ){
					$this->Receipt->recursive = -1;
					$receipt = $this->Receipt->findByCode($details->DocumentoNumero);

					if(empty($receipt)){
						$this->ProspectiveUser->recursive = -1;
						$prospecto 		= $this->ProspectiveUser->findByBillCode($detail["PrefFact"]. " " .$detail["NumFact"]);
						$sales_invoice  = $this->Salesinvoice->findByBillCode($detail["PrefFact"]. " " .$detail["NumFact"]);

						if( (!empty($prospecto) && $prospecto["ProspectiveUser"]["bill_value_iva"] == $credito) 
							||  
							(!empty($sales_invoice) && $sales_invoice["Salesinvoice"]["bill_value_iva"] == $credito)
						){

							$prospective_id  = null;
							$salesinvoice_id = null;
							$user_id 		 = null;
							if(!empty($prospecto) && $prospecto["ProspectiveUser"]["bill_value_iva"] == $credito) {
								$prospective_id = $prospecto["ProspectiveUser"]["id"];
								$user_id 		= $prospecto["ProspectiveUser"]["user_id"];
							}elseif(!empty($sales_invoice) && $sales_invoice["Salesinvoice"]["bill_value_iva"] == $credito) {
								$prospective_id  = $sales_invoice["Salesinvoice"]["prospective_user_id"];
								$salesinvoice_id = $sales_invoice["Salesinvoice"]["id"];
								$user_id 		 = $sales_invoice["Salesinvoice"]["user_id"];
							}


							$this->Receipt->create();
							$this->Receipt->save([
								"Receipt" => [
									"prospective_user_id" => $prospective_id,
									"salesinvoice_id" => $salesinvoice_id,
									"code" => $details->DocumentoNumero,
									"total" => $credito,
									"date_receipt" => $credito,
									"total_iva" => intval($credito/1.19),
									"user_id" => $user_id,
									"date_receipt" => date("Y-m-d",strtotime($details->Fecha)),
									"otras" => $retencion 
								]
							]);

							$receipt_id = $this->Receipt->id;
						}else{
							$receipt_id = 0;
						}
					}else{
						$receipt_id = $receipt["Receipt"]["id"];
					}					
				}

				$this->Recibo->create();
				$this->Recibo->save(
					["Recibo" => [
						"numero" => $details->DocumentoNumero,
						"fecha_recibo" => date("Y-m-d",strtotime($details->Fecha)),
						"fecha_gestion" => date("Y-m-d H:i:s",strtotime($details->HoraRegistro)),
						"credito" => $credito,
						"debito" => $debito,
						"details" => json_encode($details),
						"receipt_id" => $receipt_id,
					]]
				);
				
			}
		}
	}

	public function change_states() {
		$this->autoRender = false;
		$this->Receipt->updateAll(["Receipt.state" => 1],["Receipt.id" => $this->request->data["receipts"] ]);
		$this->Session->setFlash(__('Los datos fueron guardados correctamente'),'Flash/success');	
	}


	public function index() {
		$this->Receipt->recursive = 0;
		$this->set('receipts', $this->Paginator->paginate());
	}

	public function get_info_wo(){
		$this->autoRender = false;
		$response = $this->postWoApi(["code" => $this->request->data["code"] ],"receipts");
		return json_encode( isset($response[0]->Total) ? $response[0] : [] );
	}

	public function edit_recipe() {

		$this->layout 	= false;
		// $this->Receipt->ProspectiveUser->recursive = -1;
		$id 			= $this->request->query["id"];
		$receipt = $this->Receipt->findById($id);
		$flujoId = $receipt["Receipt"]["prospective_user_id"];

		$prospetive 	= $this->Receipt->ProspectiveUser->findById($flujoId);
		$valorQuotation = $this->Receipt->ProspectiveUser->FlowStage->valor_latest_regystri_state_cotizado_flujo_id($flujoId);


		$this->loadModel("Salesinvoice");
		$this->Salesinvoice->recursive = -1;
		$facturasAdicionales = $this->Salesinvoice->findAllByProspectiveUserId($flujoId);
		
		$totalActual	= is_null($this->Receipt->field("SUM(total) as totalNoIva",array("prospective_user_id"=> $flujoId))) ? 0 : $this->Receipt->field("SUM(total) as totalNoIva",array("prospective_user_id"=> $flujoId));

		if ($this->request->is(array('post', 'put'))) {
			if ($this->Receipt->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente'),'Flash/success');	
			} else {
				$this->Session->setFlash(__('Los datos de la factura fueron no guardados correctamente'),'Flash/error');				
			}
		} 
		if ($prospetive["User"]["role"] == "Asesor Externo") {
			$users = $this->Receipt->ProspectiveUser->User->find("list",["fields"=>["id","name"],"conditions" => ["User.role" => "Asesor Externo","User.state" => 1] ]);
		}else{
			$users = $this->Receipt->User->role_asesor_comercial_user();
		}
		$id = $flujoId;
		$this->set(compact('prospectiveUsers', 'users',"valorQuotation","prospetive","totalActual","id","facturasAdicionales"));
		
		$this->request->data = $receipt;
		$this->render("edit");
	}

	public function edit() {

		$this->layout 	= false;
		// $this->Receipt->ProspectiveUser->recursive = -1;

		$id 			= $this->request->query["id"];
		$prospetive 	= $this->Receipt->ProspectiveUser->findById($id);
		$valorQuotation = $this->Receipt->ProspectiveUser->FlowStage->valor_latest_regystri_state_cotizado_flujo_id($id);


		$this->loadModel("Salesinvoice");
		$this->Salesinvoice->recursive = -1;
		$facturasAdicionales = $this->Salesinvoice->findAllByProspectiveUserIdAndLocked($id,0);
		
		$totalActual	= is_null($this->Receipt->field("SUM(total) as totalNoIva",array("prospective_user_id"=> $id))) ? 0 : $this->Receipt->field("SUM(total) as totalNoIva",array("prospective_user_id"=> $id));

		if ($this->request->is(array('post', 'put'))) {
			if ($this->Receipt->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente'),'Flash/success');	
				echo $this->Receipt->id;
				die();
			} else {
				$this->Session->setFlash(__('Los datos de la factura fueron no guardados correctamente'),'Flash/error');				
			}
		} 
		if ($prospetive["User"]["role"] == "Asesor Externo") {
			$users = $this->Receipt->ProspectiveUser->User->find("list",["fields"=>["id","name"],"conditions" => ["User.role" => "Asesor Externo","User.state" => 1] ]);
		}else{
			$users = $this->Receipt->User->role_asesor_comercial_user_true(true);
		}
		$this->set(compact('prospectiveUsers', 'users',"valorQuotation","prospetive","totalActual","id","facturasAdicionales"));
	}

	public function report(){

		$this->validateDatesForReports();
		$this->validateRolePayments();
		
		$get 												= $this->request->query;
    	$get["date_ini"] = $get["ini"];
    	$get["date_fin"] = $get["end"];
    	$sales			 = array();
    	$comision		 = array();
    	$filter 		 = false;

    	if($this->request->is("post")){
    		
    		$this->loadModel("ProspectiveUser");

    		$ini  		= $this->request->data["fechaIni"];
    		$end 		= $this->request->data["fechaEnd"];
    		$sales  	= $this->ProspectiveUser->getSalesListRecibos($ini, $end);
    		if($this->request->data["ProspectiveUser"]["excel"] == 1){
    			$this->export_comissions($sales);
    		}
    	}

    	$this->set(compact('sales','filter'));

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
			        ->setCellValue('A1', 'CLIENTE')
			        ->setCellValue('B1', 'FACTURA')
			        ->setCellValue('C1', 'FECHA FACTURA')
			        ->setCellValue('D1', 'RECIBO')
			        ->setCellValue('E1', 'FECHA RECIBO')
			        ->setCellValue('F1', 'VALOR VENTA')
			        ->setCellValue('G1', 'VALOR PAGO')
			        ->setCellValue('H1', 'BASE COMISIÓN')
			        ->setCellValue('I1', 'APLICA RETEFUENTE')
			        ->setCellValue('J1', 'APLICAR RETEIVA')
			        ->setCellValue('K1', 'APLICA OTRAS COMISIONES')
			        ->setCellValue('L1', 'USUARIO');

		$i = 2;
		$totalPagar = 0;

		foreach ($sales as $key => $value) {

			$cliente 		=  		!empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"];

			$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $cliente);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, strtoupper($this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code")) );
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date") );
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, strtoupper($value["Receipt"]["code"]));
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $value["Receipt"]["date_receipt"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_value"));
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $value["Receipt"]["total"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $value["Receipt"]["total_iva"]);
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $value["Receipt"]["retefuente"] == 1 ? "Si" : "No");
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, $value["Receipt"]["reteiva"] == 1 ? "Si" : "No");
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $value["Receipt"]["otras"] == 1 ? "Si" : "No");
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, $value["User"]["name"]);
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
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

		$spreadsheet->getActiveSheet()->setTitle("Recibos de caja");
		$spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="recibos_caja_kebco_'.time().'.xlsx"');
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

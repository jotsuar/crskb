<?php
App::uses('AppController', 'Controller');

class PaymentsController extends AppController {

	public function payment_history(){
		$this->layout 					= false;
		if ($this->request->is('ajax')) {
			$history 					= $this->Payment->data_payment_history($this->request->data['flujo_id']);
			if (count($history) > 0) {
				$this->set(compact('history'));
			} else {
				$this->render('/Payments/invalid');
			}
		}
	}

	public function list_option_payments_credito(){
		$this->layout 					= false;
	}

	public function list_option_payments(){
		$this->layout 					= false;
		if ($this->request->is('ajax')) {
			$medios 		= Configure::read('variables.mediosPago');
			$this->set(compact('medios'));
		}
	}

	public function invalid(){
		$this->layout 					= false;
	}

}
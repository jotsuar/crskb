<?php
App::uses('AppModel', 'Model');

class Log extends AppModel {

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('Log.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function get_logs(){
		$this->recursive 	= -1;
		$order				= array('Log.id' => 'desc');
		return $this->find('all',compact('order'));
	}

	public function get_logs_user($user_id){
		$this->recursive 	= -1;
		$order				= array('Log.id' => 'desc');
		$conditions 		= array('Log.user_id' => $user_id);
		return $this->find('all',compact('conditions','order'));
	}

	public function get_logs_user_day($user_id){
		$this->recursive 	= -1;
		$order				= array('Log.id' => 'desc');
		$conditions 		= array('Log.user_id' => $user_id,'Log.date' => date("Y-m-d"));
		return $this->find('all',compact('conditions','order'));
	}

	public function get_logs_user_yesterday($user_id){
		$this->recursive 	= -1;
		$fechaAyer 			= date('Y-m-d', strtotime('-1 day'));
		$order				= array('Log.id' => 'desc');
		$conditions 		= array('Log.user_id' => $user_id,'Log.date' => $fechaAyer);
		return $this->find('all',compact('conditions','order'));
	}

	public function get_logs_user_before_day_yesterday($user_id){
		$this->recursive 	= -1;
	    $fechaAntier 		= date('Y-m-d', strtotime('-2 day'));
		$order				= array('Log.id' => 'desc');
		$conditions 		= array('Log.user_id' => $user_id,'Log.date' => $fechaAntier);
		return $this->find('all',compact('conditions','order'));
	}



	

}

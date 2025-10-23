<?php
require_once APP.'Vendor'.DS.'autoload.php';
App::uses('AppModel', 'Model');

class Manage extends AppModel {

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_users_id'
		)
	);

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('Manage.id' => $id);
		return $this->find('first',compact('conditions'));
	}
	
	public function get_data_user($user_id){
		$this->recursive 		= -1;
		$limit 					= 100;
		$order					= array('Manage.id' => 'desc');
		$conditions 			= array('Manage.user_id' => $user_id);
		return $this->find('all',compact('conditions','order','limit')); 
	}

	public function get_data_user_limit_new($user_id){
		$this->recursive 		= -1;
		$limit 					= 6;
		$order					= array('Manage.id' => 'desc');
		$conditions 			= array('Manage.user_id' => $user_id,'Manage.state' => "0");
		return $this->find('all',compact('conditions','order','limit')); 
	}

	public function count_user_manages_new($user_id){
		$conditions 			= array('Manage.user_id' => $user_id,'Manage.state' => "0");
		return $this->find('count',compact('conditions')); 
	}

	public function count_user_manages_read($user_id){
		$conditions 			= array('Manage.user_id' => $user_id,'Manage.state' => "1");
		return $this->find('count',compact('conditions'));
	}

	public function find_time_limit_prospecto_etapa($flujo_id,$etapa){
		$fields 				= array('Manage.time_end','Manage.date');
		$conditions 			= array('Manage.prospective_users_id' => $flujo_id,'Manage.state_flow' => $etapa);
		return $this->find('first',compact('conditions','fields'));
	}

	public function count_time_limit_prospecto_etapa($flujo_id,$etapa){
		$fields 				= array('Manage.time_end','Manage.date');
		$conditions 			= array('Manage.prospective_users_id' => $flujo_id,'Manage.state_flow' => $etapa);
		return $this->find('count',compact('conditions','fields'));
	}

	public function update_notify_leidas_all($user_id) {
	    $this->updateAll(
	    	array('Manage.state' => '1'), array('Manage.user_id' => $user_id)
	    );
	}

	public function afterSave($created, $options = array()){
		$data = isset($this->data["Manage"]) ? $this->data["Manage"] : $this->data;
		if ($created && isset($data["user_id"]) && isset($data["description"]) && isset($data["url"]) && isset($data["prospective_users_id"]) ) {
			
			try {
				$msgText = $data["description"]." Flujo: ".$data["prospective_users_id"];
				$url 	 = str_replace(Router::url("/",true), "", $data["url"]);
				$url 	 = substr(Router::url("/",true), 0, -1).$url;

				$pushNotifications = new \Pusher\PushNotifications\PushNotifications(array(
				  "instanceId" => "c40fa57b-967c-42e6-8ee6-80084a1aef4c",
				  "secretKey" => "C81C2CA939B40F50D6E38DAB0E4B472D28DD3AF079C097F189B951F50B8D9E66",
				));

				$publishResponse = $pushNotifications->publishToInterests(
				  ["inter".$data["user_id"]],
				  [
				    "web" => [
				      "notification" => [
				        "title" => "Notificación CRM",
				        "body" => strip_tags($msgText),
				        "icon" => "https://crm.kebco.co/img/favicon.png",
				        "deep_link" => $url
				      ],
				    ],
				  ]
				);
				return true;
			} catch (Exception $e) {
				return true;
			}

		}
		return true;

	}


	public function sendNotificationCompra($subject,$id,$user_id){
		try {
			$msgText = $subject;
			$url 	 = Router::url('/', true).'prospective_users/compras_nacionales?q='.$id;

			$pushNotifications = new \Pusher\PushNotifications\PushNotifications(array(
			  "instanceId" => "c40fa57b-967c-42e6-8ee6-80084a1aef4c",
			  "secretKey" => "C81C2CA939B40F50D6E38DAB0E4B472D28DD3AF079C097F189B951F50B8D9E66",
			));

			$publishResponse = $pushNotifications->publishToInterests(
			  ["inter".$data["user_id"]],
			  [
			    "web" => [
			      "notification" => [
			        "title" => "Notificación CRM",
			        "body" => strip_tags($msgText),
			        "icon" => "https://crm.kebco.co/img/favicon.png",
			        "deep_link" => $url
			      ],
			    ],
			  ]
			);
			return true;
		} catch (Exception $e) {
			return true;
		}
	}

	public function sendNotificationLiquidation($subject,$id,$user_id){
		try {
			$msgText = $subject;
			$url 	 = Router::url('/', true).'liquidations/view/'.$id;

			$pushNotifications = new \Pusher\PushNotifications\PushNotifications(array(
			  "instanceId" => "c40fa57b-967c-42e6-8ee6-80084a1aef4c",
			  "secretKey" => "C81C2CA939B40F50D6E38DAB0E4B472D28DD3AF079C097F189B951F50B8D9E66",
			));

			$publishResponse = $pushNotifications->publishToInterests(
			  ["inter".$user_id],
			  [
			    "web" => [
			      "notification" => [
			        "title" => "Notificación CRM",
			        "body" => strip_tags($msgText),
			        "icon" => "https://crm.kebco.co/img/favicon.png",
			        "deep_link" => $url
			      ],
			    ],
			  ]
			);
			return true;
		} catch (Exception $e) {
			return true;
		}
	}

	public function sendNotificationShipping($subject,$id,$user_id){
		try {
			$msgText = $subject;
			$url 	 = Router::url('/', true).'shippings/view/'.$id;

			$pushNotifications = new \Pusher\PushNotifications\PushNotifications(array(
			  "instanceId" => "c40fa57b-967c-42e6-8ee6-80084a1aef4c",
			  "secretKey" => "C81C2CA939B40F50D6E38DAB0E4B472D28DD3AF079C097F189B951F50B8D9E66",
			));

			$publishResponse = $pushNotifications->publishToInterests(
			  ["inter".$user_id],
			  [
			    "web" => [
			      "notification" => [
			        "title" => "Notificación CRM",
			        "body" => strip_tags($msgText),
			        "icon" => "https://crm.kebco.co/img/favicon.png",
			        "deep_link" => $url
			      ],
			    ],
			  ]
			);
			return true;
		} catch (Exception $e) {
			return true;
		}
	}
}
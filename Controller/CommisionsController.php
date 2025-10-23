<?php
App::uses('AppController', 'Controller');
/**
 * Commisions Controller
 *
 * @property Commision $Commision
 * @property PaginatorComponent $Paginator
 */
class CommisionsController extends AppController {

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit() {
		$this->layout = false;
		$this->loadModel("Percentage");
		$this->loadModel("PercentagesLocal");
		$this->loadModel("Effectivity");
		if($this->request->is("ajax")){

			$this->loadModel("User");
			$porcentajes  		= $this->Percentage->find("all",["recursive" => -1, "conditions" => ["Percentage.user_id" => $this->request->data["user_id"]] ]);
			$porcentajesLocal	= $this->PercentagesLocal->find("all",["recursive" => -1, "conditions" => ["PercentagesLocal.user_id" => $this->request->data["user_id"]] ]);
			$efectividad  		= $this->Effectivity->find("all",["recursive" => -1, "conditions" => ["Effectivity.user_id" => $this->request->data["user_id"]] ]);
			$usuarios 			= $this->User->role_asesor_comercial_user_true();
	    	$usersidsValids 	= $this->Commision->find("all",array("fields"=> array("user_id"), "recursive" => -1, "conditions" => ["user_id !=" => $this->request->data["user_id"] ] ));
	    	$userIds 			= Set::extract($usersidsValids, "{n}.Commision.user_id");
	    	$comision 			= null;

	    	if(!empty($userIds)  && !is_null($userIds)){

		    	foreach ($usuarios as $key => $value) {
		    		if(!in_array($key, $userIds)){
		    			unset($usuarios[$key]);
		    		}
		    	}
	    	}else{
	    		$usuarios = array();
	    	}

			$this->set("user_id", $this->request->data["user_id"]);
			$this->set("user", $this->User->findById($this->request->data["user_id"]));
			$this->set("usuarios", $usuarios);
			$this->set("porcentajes", $porcentajes);
			$this->set("porcentajesLocal", $porcentajesLocal);
			$this->set("efectividad", $efectividad);
			if(isset($this->request->data["user_id"])){
				$commisionUser = $this->Commision->findByUserId($this->request->data["user_id"]);
				$this->request->data = $commisionUser;
				$this->set("actual", !empty($commisionUser) ? false : true );
			}
		}else{
			if ($this->request->is(array('post', 'put'))) {

				if ($this->Commision->save($this->request->data)) {

					if (!empty($this->request->data["User"]["users_money"])) {
						$this->request->data["User"]["users_money"] = json_encode($this->request->data["User"]["users_money"]);
					}else{
						$this->request->data["User"]["users_money"] = '[]';
					}

					foreach ($this->request->data["Percentage"] as $key => $value) {
						$this->Percentage->save($value);
					}

					foreach ($this->request->data["PercentagesLocal"] as $key => $value) {
						$this->PercentagesLocal->save($value);
					}

					$this->loadModel("User");
					$this->User->save($this->request->data["User"]);

					$this->Session->setFlash('La información se ha guardado satisfactoriamente', 'Flash/success');
				} else {
					$this->Session->setFlash('La información no se ha guardado, por favor inténtalo mas tarde','Flash/error');
				}
				return $this->redirect(array("controller"=>"users",'action' => 'view', $this->encryptString($this->request->data["Commision"]["user_id"])));
			} else {
				return $this->redirect($this->request->refered());
			}
			
		}
		
	}

}

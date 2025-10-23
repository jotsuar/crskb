<?php
App::uses('AppController', 'Controller');
/**
 * Assists Controller
 *
 * @property Assist $Assist
 * @property PaginatorComponent $Paginator
 */
class AssistsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {

		if(!isset($this->request->query["ini"]) && !isset($this->request->query["end"])){
			$ini = date("Y-m-01");
			$end = date("Y-m-d");
		}else{
			$ini = $this->request->query["ini"];
        	$end = $this->request->query["end"];
		}
        
		$allAssists = $this->Assist->find("list",["conditions" => ["Assist.user_id !=" => 0],"fields" =>["user_id","user_id"] ]);

		$usuarios 	= $this->Assist->User->find("list",["conditions"=>["User.id"=>$allAssists]]);

		$conditions = [];
		if (AuthComponent::user("role") != "Gerente General" && AuthComponent::user("role") != "Logística") {
			$conditions["Assist.user_id"] = AuthComponent::user("id");

		}else{
			$conditions["date(Assist.created) >="] = $ini;
			$conditions["date(Assist.created) <="] = $end;

			if(isset($this->request->query["user_id"])){
				$conditions["Assist.user_id"] = $this->request->query["user_id"];
			}
		}

		$this->paginate 		 = ["conditions" => $conditions, "order" =>["Assist.created" => "DESC"], "limit" => 10 ];
		$this->Assist->recursive = 0;
		$assists 				 = $this->Paginator->paginate();

		if(!empty($assists)){
			foreach ($assists as $key => $value) {
				$assists[$key]["Assist"]["demora"] = $this->calcularRetraso($value["Assist"]["user_id"],$value["Assist"]["created"],$value["Assist"]["created"]);
			}
		}

        $this->set("fechaFinReporte", $end);
        $this->set("fechaInicioReporte", $ini);
		$this->set('assists', $assists );
		$this->set('usuarios', $usuarios );
	}

	public function report_assists() {
		$this->validateDatesForReports();
        $ini = $this->request->query["ini"];
        $end = $this->request->query["end"];

        $allAssists = $this->Assist->find("list",["conditions" => [
            "DATE(Assist.created) >=" => $ini,
            "DATE(Assist.created) <=" => $end,
            "Assist.user_id !=" => 0
        ],"fields" =>["user_id","user_id"] ]);


        $usuarios = [];
        $demoras  = [];

        $totalEmpresa = 0;

        foreach ($allAssists as $userId => $user) {
        	$demora = $this->calcularRetraso($userId,$ini,$end);
        	$totalMinutos = $demora["minutos"];

        	if($demora["dias"] > 0){
        		$totalMinutos+= $demora["dias"]*510;
        	}

        	$totalHorasDemora = $demora["horas"] + ( $totalMinutos > 0 ? round($totalMinutos/60,2) : 0  );
        	$name = $this->Assist->User->field("name",["id"=>$userId]);
        	$demoras[ $name ] = $totalHorasDemora;
        	$usuarios[ $userId ] = ["name" => $name, "demora" => $totalHorasDemora ] ;

        	$totalEmpresa+=$totalHorasDemora;

        }

        $this->set(compact('demoras','usuarios','totalEmpresa'));

	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Assist->exists($id)) {
			throw new NotFoundException(__('Invalid assist'));
		}
		$options = array('conditions' => array('Assist.' . $this->Assist->primaryKey => $id));
		$this->set('assist', $this->Assist->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Assist->create();
			$datos = $this->request->data;



			if (!empty($datos["Assist"]["image_file"])) {
	            $datos["Assist"]["image_file"]    = str_replace("data:image/png;base64,", "", $datos["Assist"]["image_file"]);
	            $datos['Assist']['image_file']    = $this->saveImage64($datos["Assist"]["image_file"],'asistencias/imagenes');
	        }

	        if ($datos['Assist']['file_excuse']['name'] == '') {
				$datos['Assist']['file_excuse'] 		= null;
			} else {
				$imagen_user 	= true;
				$imagen 		= $this->loadPhoto($datos['Assist']['file_excuse'],'asistencias/imagenes');
				$datos['Assist']['file_excuse'] 		= $this->Session->read('imagenModelo');
			}

			if ($this->Assist->save($datos)) {
				$this->Session->setFlash(__('La información fue guardada correctamente'),'Flash/success');
				// return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La información no fue guardada correctamente'),'Flash/error');
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Assist->exists($id)) {
			throw new NotFoundException(__('Invalid assist'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Assist->save($this->request->data)) {
				$this->Flash->success(__('The assist has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The assist could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Assist.' . $this->Assist->primaryKey => $id));
			$this->request->data = $this->Assist->find('first', $options);
		}
		$users = $this->Assist->User->find('list');
		$this->set(compact('users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Assist->exists($id)) {
			throw new NotFoundException(__('Invalid assist'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Assist->delete($id)) {
			$this->Flash->success(__('The assist has been deleted.'));
		} else {
			$this->Flash->error(__('The assist could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

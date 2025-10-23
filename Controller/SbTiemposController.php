<?php
App::uses('AppController', 'Controller');
/**
 * SbTiempos Controller
 *
 * @property SbTiempo $SbTiempo
 * @property PaginatorComponent $Paginator
 */
class SbTiemposController extends AppController {

	public $components = array('Paginator');

	private  function minutosPasadosEntreFechas($fecha1, $fecha2) {
	    // Crea objetos DateTime con las fechas proporcionadas
	    $dateTime1 = new DateTime($fecha1);
	    $dateTime2 = new DateTime($fecha2);

	    // Obtiene la diferencia entre las fechas en minutos
	    $diferencia = $dateTime1->diff($dateTime2);

	    // Calcula el total de minutos entre las fechas
	    $minutos = $diferencia->days * 24 * 60; // Días a minutos
	    $minutos += $diferencia->h * 60; // Horas a minutos
	    $minutos += $diferencia->i; // Minutos

	    return $minutos;
	}

	public function index() {
		$this->validateDatesForReports();
		$ini = $this->request->query["ini"];
        $end = $this->request->query["end"];


        $conditions = ['date(fecha) BETWEEN ? AND ?' => array($ini, $end)];

        $SbTiempos  = $this->SbTiempo->find("all",["conditions" => $conditions ]);

        $users      = [];
        if(!empty($SbTiempos)){
        	foreach ($SbTiempos as $key => $value) {
        		$users[$value["SbUser"]["id"]] = [ "name" => $value["SbUser"]["first_name"]." ".$value["SbUser"]["last_name"], "activo" => 0, "inactivo" => 0 ];
        	}

        	foreach ($SbTiempos as $key => $value) {
        		if($value["SbTiempo"]["type"] == 0){
        			$users[$value["SbUser"]["id"]]["inactivo"] += $this->minutosPasadosEntreFechas($value["SbTiempo"]["date_ini"], $value["SbTiempo"]["date_end"]);
        		}else{
        			$users[$value["SbUser"]["id"]]["activo"] += $this->minutosPasadosEntreFechas($value["SbTiempo"]["date_ini"], $value["SbTiempo"]["date_end"]);        			
        		}
        	}
        } 
        $this->set("users",$users);
	}

	public function index_user() {
		$this->validateDatesForReports();
		$ini = $this->request->query["ini"];
        $end = $this->request->query["end"];


        $conditions = ["SbTiempo.date_ini >=" =>$ini, "SbTiempo.date_end <=".$end ];

        

		$this->SbTiempo->recursive = 0;
		$this->set('sbTiempos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$id = $this->desencriptarCadena($id);
		$ini = $this->request->query["ini"];
        $end = $this->request->query["end"];
        $conditions = ['date(fecha) BETWEEN ? AND ?' => array($ini, $end), "user_id" => $id ];
		$this->SbTiempo->recursive = 1;
		$this->paginate = array( 'conditions' 	=> $conditions, "limit" => 30 );
		$this->set('sbTiempos', $this->Paginator->paginate());
		$this->set('user', $this->SbTiempo->SbUser->findById($id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SbTiempo->create();
			if ($this->SbTiempo->save($this->request->data)) {
				$this->Flash->success(__('The sb tiempo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The sb tiempo could not be saved. Please, try again.'));
			}
		}
		$sbUsers = $this->SbTiempo->SbUser->find('list');
		$this->set(compact('sbUsers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SbTiempo->exists($id)) {
			throw new NotFoundException(__('Invalid sb tiempo'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SbTiempo->save($this->request->data)) {
				$this->Flash->success(__('The sb tiempo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The sb tiempo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SbTiempo.' . $this->SbTiempo->primaryKey => $id));
			$this->request->data = $this->SbTiempo->find('first', $options);
		}
		$sbUsers = $this->SbTiempo->SbUser->find('list');
		$this->set(compact('sbUsers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->SbTiempo->exists($id)) {
			throw new NotFoundException(__('Invalid sb tiempo'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->SbTiempo->delete($id)) {
			$this->Flash->success(__('The sb tiempo has been deleted.'));
		} else {
			$this->Flash->error(__('The sb tiempo could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
